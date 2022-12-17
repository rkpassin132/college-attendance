<?php
require('venders/PHPExcel/PHPExcel.php');
require('venders/PHPExcel/PHPExcel/IOFactory.php');

class ImportExcel
{
    private $sheet;
    private $data;
    private $error;

    function __construct($conn, $file, $col)
    {
        $this->file = $file;
        $this->data = array();
        $this->error = array();
        $this->sheet = PHPExcel_IOFactory::load($file)->getSheet(0);

        $getHighestRow = $this->sheet->getHighestRow();
        for ($row = 2; $row <= $getHighestRow; $row++) {
            $colNo = 0;
            foreach ($col as $colName => $validations) {
                $value = $this->sheet->getCellByColumnAndRow($colNo, $row)->getValue();
                $value = sql_prevent($conn, xss_prevent($value));
                $valid = $this->validate_value($value, $validations, $row, $colNo+1, $colName);
                $this->data[$row][str_replace(" ","_",$colName)] = ($valid) ? $value : "";
                $colNo++;
            }
        }
    }

    private function validate_value($value, $validations, $row, $col, $colName)
    {
        $msg = "Cell [$row, $col] : $colName ";
        if (in_array('required', $validations)) {
            if (strlen($value) <= 0) {
                array_push($this->error, $msg . "is required");
                return false;
            }
        }
        if (strlen($value) <= 0) return false;
        foreach ($validations as $key => $valid) {
            $check = true;
            // if($row == 5 && $col == 2) print_r([$value]);
            if(is_array($valid) == 1){
                if($key == 'option') $check = $this->check_valid(in_array($value, $valid), $msg);
            }
            else if ($valid == 'name') $check = $this->check_valid(valid_name($value), $msg);
            else if ($valid == 'number') $check = $this->check_valid(valid_number($value), $msg);
            else if ($valid == 'phone') $check = $this->check_valid(valid_phone($value), $msg);
            else if ($valid == 'email') $check = $this->check_valid(valid_email($value), $msg);
            else if ($valid == 'time') $check = $this->check_valid(valid_time(TIME_FORMATE, $value), $msg);
            else if ($valid == 'password') $check = $this->check_valid(valid_password($value), $msg);
            if(!$check) return false;
        }
        return $valid;
    }

    private function check_valid($result, $msg)
    {
        if ($result != 1) {
            array_push($this->error, $msg . $result);
            return false;
        }
        return true;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrors()
    {
        if(count($this->data) <= 0) return ["Excel is empty"];
        else return $this->error;
    }

    public function hasErrors()
    {
        return (count($this->error) > 0 || count($this->data) <= 0);
    }
}
