<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Student Class</title>

<?php include_once('./partials/head_end.php'); ?>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/custom.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/select2/select2.min.css">


<main class="content-wrapper">
  <div class="mdc-layout-grid">
    <span class="d-flex align-items-center mb-4">
      <i class="material-icons">adjust</i>
      <h2 class="card-title m-0 pl-2">Student Class</h2>
    </span>

    <div class="mdc-layout-grid__inner">

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title mb-3">Add Student Class</h6>
          <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
            <p class="m-0 text-dark">Add student class ones at admission time.</p>
          </div>
          <form class="mdc-layout-grid__inner validate-form" id="addStudentClass">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="department">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list department-list-active" form-target="addStudentClass"></ul>
                </div>
                <span class="mdc-floating-label">Department <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input course-type-list-active" data-validate="Select course" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="course-type">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list course-type-list-active" form-target="addStudentClass"></ul>
                </div>
                <span class="mdc-floating-label">Course Type <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input session-list-active" data-validate="Select session" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="session">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list session-list-active" form-target="addStudentClass"></ul>
                </div>
                <span class="mdc-floating-label">Session <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6">
              <div class="validate-input w-100" data-validate="Select student">
                <select class=" js-states form-control valid-input" id="student-select-list" name="student[]" multiple="multiple"></select>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                <span class="button__text">Add</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title mb-3">Add Student Class From Excel</h6>
          <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
            <h4 class="alert-heading d-flex align-items-center text-dark">
              <i class="material-icons mdc-button__icon m-0 text-info">info</i>
              <span class="ml-2">File upload information - [ <a download="student" href="<?= BASE_URL ?>assets/excel-template/student-class.csv" class="text-danger">Template</a> ]</span>
            </h4>
            <p class="m-0 text-dark">Create multiple student at once by uploading excel file. Please select only excel file (<span class="text-danger">.csv, .xls, .xlsx</span>) with correct data</p>
            <hr />
            <ul class="pl-3">
              <li>
                <p class="m-0 text-dark">First row of sheet is negligible. You can set your column name there.</p>
              </li>
              <li>
                <p class="m-0 text-dark"><span class="text-danger">Column 1</span> = Roll no</p>
              </li>
            </ul>
          </div>
          <form class="mdc-layout-grid__inner validate-form" id="addStudentClassExcel">
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
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="department">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list department-list-active" form-target="addStudentClassExcel"></ul>
                </div>
                <span class="mdc-floating-label">Department <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input course-type-list-active" data-validate="Select course" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="course-type">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list course-type-list-active" form-target="addStudentClassExcel"></ul>
                </div>
                <span class="mdc-floating-label">Course Type <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input session-list-active" data-validate="Select session" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="session">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list session-list-active" form-target="addStudentClassExcel"></ul>
                </div>
                <span class="mdc-floating-label">Session <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6">
              <div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon validate-file-input" data-validate="Password is required">
                <label style="cursor: pointer; width: 50px;">
                  <i class="material-icons mdc-text-field__icon">file_upload</i>
                  <input style="display: none;" class="valid-file-input" valid-file="excel,pdf" name="student-file" type="file" accept="<?= join(",", FILE['extention']['excel']) ?>">
                </label>
                <input class="mdc-text-field__input mdc-notched-outline--upgraded mdc-notched-outline--notched" readonly name="student-file-name" type="text">
                <div class="mdc-notched-outline ">
                  <div class="mdc-notched-outline__leading"></div>
                  <div class="mdc-notched-outline__notch">
                    <label class="mdc-floating-label ">Select Student File</label>
                  </div>
                  <div class="mdc-notched-outline__trailing"></div>
                </div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                <span class="button__text">Add</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title mb-3">Promote Student</h6>
          <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
            <p class="m-0 text-dark">Promote those student who is already in some class.</p>
          </div>
          <form class="mdc-layout-grid__inner validate-form" id="promoteStudentClass">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="department">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list department-list-active" form-target="promoteStudentClass"></ul>
                </div>
                <span class="mdc-floating-label">Department <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input course-type-list-active" data-validate="Select course" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="course-type">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list course-type-list-active" form-target="promoteStudentClass"></ul>
                </div>
                <span class="mdc-floating-label">Course Type <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input session-list-active" data-validate="Select session" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="session">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list session-list-active" form-target="promoteStudentClass"></ul>
                </div>
                <span class="mdc-floating-label">Session <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <div class="mdc-select demo-width-class validate-input" data-validate="Select student" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="student">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list" id="student-select-promote"></ul>
                </div>
                <span class="mdc-floating-label">Student <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                <span class="button__text">Promote</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title mb-3">Promote Student Class</h6>
          <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
            <p class="m-0 text-dark">Select current class and promote by 1 class</p>
          </div>
          <form class="mdc-layout-grid__inner validate-form" id="promoteClass">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="department">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list department-list-active" form-target="addStudentClassExcel"></ul>
                </div>
                <span class="mdc-floating-label">Department <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input course-type-list-active" data-validate="Select course" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="course-type">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list course-type-list-active" form-target="addStudentClassExcel"></ul>
                </div>
                <span class="mdc-floating-label">Course Type <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input session-list-active" data-validate="Select session" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="session">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list session-list-active" form-target="addStudentClassExcel"></ul>
                </div>
                <span class="mdc-floating-label">Session <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                <span class="button__text">Promote</span>
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</main>

<?php require_once("./partials/script_start.php"); ?>
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/select2/select2.min.js"></script>

<script src="ajax/student-class.js"></script>
<script src="ajax/branch-subject.js"></script>
<script>
  $('#student-select-list').select2({
    placeholder: "Select students",
    theme: "material",
    multiple: true,
    closeOnSelect: false
  });
  load_student_select();
  load_department();
</script>

<?php require_once("./partials/script_end.php"); ?>