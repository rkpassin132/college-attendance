<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Teacher Shedule</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
  <div class="mdc-layout-grid">
    <span class="d-flex align-items-center mb-4">
      <i class="material-icons">adjust</i>
      <h2 class="card-title m-0 pl-2">Teacher Shedule</h2>
    </span>

    <div class="mdc-layout-grid__inner">

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title mb-3">Create Teacher shedule</h6>
          <form class="mdc-layout-grid__inner validate-form" id="addTeacherShedule">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <div class="mdc-select demo-width-class validate-input" data-validate="Select teacher" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="teacher">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list" id="teacher-select-list"></ul>
                </div>
                <span class="mdc-floating-label">Teacher <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="department">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list department-list-active" form-target="addTeacherShedule"></ul>
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
                  <ul class="mdc-list course-type-list-active" form-target="addTeacherShedule"></ul>
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
                  <ul class="mdc-list session-list-active" form-target="addTeacherShedule"></ul>
                </div>
                <span class="mdc-floating-label">Session <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input subject-list-active" data-validate="Select subject" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="subject">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list subject-list-active" form-target="addTeacherShedule"></ul>
                </div>
                <span class="mdc-floating-label">Subject <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <div class="mdc-select demo-width-class validate-input subject-select" data-validate="Select day" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="day">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list" id="day-select">
                    <?php foreach (WEEK_DAYS as $key => $value) { ?>
                      <li class="mdc-list-item" data-value="<?= $key ?>"><?= $key ?></li>
                    <?php } ?>
                  </ul>
                </div>
                <span class="mdc-floating-label">Day <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <div class="mdc-text-field validate-input" data-validate="Set start time">
                <input class="mdc-text-field__input valid-input" type="time" name="start-time">
                <div class="mdc-line-ripple"></div>
                <label for="start-time" class="mdc-floating-label">Start Time <span class="text-danger">*</span></label>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <div class="mdc-text-field validate-input" data-validate="Set end time">
                <input class="mdc-text-field__input valid-input" type="time" name="end-time">
                <div class="mdc-line-ripple"></div>
                <label for="end-time" class="mdc-floating-label">End Time <span class="text-danger">*</span></label>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">Add</button>
            </div>
          </form>
        </div>
      </div>

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title card-padding p-0 mb-2">Search shedule</h6>
          <form class="mdc-layout-grid__inner validate-form" id="searchTeacherClasses">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class validate-input department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                <input class="valid-input" type="hidden" name="department">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list department-list-active" form-target="searchTeacherClasses"></ul>
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
                  <ul class="mdc-list course-type-list-active" form-target="searchTeacherClasses"></ul>
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
                  <ul class="mdc-list session-list-active" form-target="searchTeacherClasses"></ul>
                </div>
                <span class="mdc-floating-label">Session <span class="text-danger">*</span></span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                <span class="button__text">Search</span>
              </button>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
              <button type="button" id="clear-search-filter" class="mdc-button mdc-button--outlined outlined-button--dark mdc-ripple-upgraded">
                <i class="material-icons mdc-button__icon">close</i> Clear
              </button>
            </div>
          </form>
          <div class="table-responsive m-0 mb-2 mt-3">
            <table class="table table-hover datatable-shadow" id="table2">
              <thead>
                <tr>
                  <th>Day</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Teacher</th>
                  <th>Subject</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
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

<script src="ajax/teacher-shedule.js"></script>
<script src="ajax/branch-subject.js"></script>
<script>
  load_teacher_select();
  load_department();
  load_course_type_search();
  $('#table2').DataTable({
    responsive: true,
    "bDestroy": true
  });
</script>

<?php require_once("./partials/script_end.php"); ?>