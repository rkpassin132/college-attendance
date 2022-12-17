<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Departmentes</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">


<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <span class="d-flex align-items-center mb-4">
            <i class="material-icons">adjust</i>
            <h2 class="card-title m-0 pl-2">Departmentes</h2>
        </span>

        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Create Department</h6>
                    <div style="display:none;" class="alert alert-danger" role="alert" id="fullname_error">
                        Full Name should not be blank
                    </div>
                    <div style="display:none;" class="alert alert-danger" role="alert" id="shortname_error">
                        Short Name should not be blank
                    </div>
                    <form class="mdc-layout-grid__inner validate-form" submit-type="department-create" id="createDepartmentForm">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Full name is required">
                                <input class="mdc-text-field__input valid-input" id="department-name" name="fullname">
                                <div class="mdc-line-ripple"></div>
                                <label for="department-name" class="mdc-floating-label">Full Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Last name is required">
                                <input class="mdc-text-field__input valid-input" id="department-shortname" name="shortname">
                                <div class="mdc-line-ripple"></div>
                                <label for="department-shortname" class="mdc-floating-label">Short Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                            <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded" id="addDepartmentBtn">
                                <span class="button__text">Create</span>
                            </button>
                        </div>
                        <div style="display: none;" class=" mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                            <button type="submit" class=" mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded" department-key="" id="updateDepartmentBtn">
                                <span class="button__text">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Create Department From Excel</h6>
                    <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
                        <h4 class="alert-heading d-flex align-items-center text-dark">
                            <i class="material-icons mdc-button__icon m-0 text-info">info</i>
                            <span class="ml-2">File upload information - [ <a download="department" href="<?= BASE_URL ?>assets/excel-template/department.csv" class="text-danger">Template</a> ]</span>
                        </h4>
                        <p class="m-0 text-dark">Create multiple department at once by uploading excel file. Please select only excel file (<span class="text-danger">.csv, .xls, .xlsx</span>) with correct data</p>
                        <hr />
                        <ul class="pl-3">
                            <li>
                                <p class="m-0 text-dark">First row of sheet is negligible. You can set your column name there.</p>
                            </li>
                            <li>
                                <p class="m-0 text-dark"><span class="text-danger">Column 1</span> = Full name, <span class="text-danger">2</span> = Short name</p>
                            </li>
                        </ul>
                    </div>
                    <form class="mdc-layout-grid__inner validate-form" id="create-department-file-form">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12" style="display: none;">
                            <div class="alert alert-danger w-100" role="alert">
                                <h4 class="alert-heading d-flex align-items-center">
                                    <i class="material-icons mdc-button__icon m-0">error</i>
                                    <span class="ml-2">File error! [ row, column ]</span>
                                </h4>
                                <div class="file-error"></div>
                                <hr>
                                <p class="mb-0">Please add file data carefully.</p>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                            <div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon validate-file-input" data-validate="Password is required">
                                <label style="cursor: pointer; width: 50px;">
                                    <i class="material-icons mdc-text-field__icon">file_upload</i>
                                    <input style="display: none;" class="valid-file-input" valid-file="excel,pdf" name="department-file" type="file" accept="<?= join(",", FILE['extention']['excel']) ?>">
                                </label>
                                <input class="mdc-text-field__input mdc-notched-outline--upgraded mdc-notched-outline--notched" readonly name="department-file-name" type="text">
                                <div class="mdc-notched-outline ">
                                    <div class="mdc-notched-outline__leading"></div>
                                    <div class="mdc-notched-outline__notch">
                                        <label class="mdc-floating-label ">Select File</label>
                                    </div>
                                    <div class="mdc-notched-outline__trailing"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                            <button type="submit" class="mdc-button mdc-button--outlined outlined-button--dark mdc-ripple-upgraded">
                                <span class="button__text"><i class="material-icons mdc-button__icon m-0">file_upload</i> Create</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card p-0">
                    <h6 class="card-title card-padding pb-0 mb-2">Department list</h6>
                    <div class="table-responsive mb-2">
                        <table class="table table-hover datatable-shadow" id="table2">
                            <thead>
                                <th>Sno</th>
                                <th>Name</th>
                                <th>Short Name</th>
                                <th>Actions</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once("./partials/script_start.php"); ?>

<!-- Page js -->
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/jszip.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/datatable/buttons.colVis.min.js"></script>
<!-- Page js-->
<script src="<?= BASE_URL ?>assets/vendor/sweetAlerts/sweetalert.min.js"></script>

<script src="ajax/department.js"></script>
<script>
    $(document).ready(function() {
        load_department();
    });
</script>
<?php require_once("./partials/script_end.php"); ?>