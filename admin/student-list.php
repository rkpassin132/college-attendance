<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Students</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">


<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
  <div class="mdc-layout-grid">
    <span class="d-flex align-items-center mb-4">
      <i class="material-icons">adjust</i>
      <h2 class="card-title m-0 pl-2">Students</h2>
    </span>

    <div class="mdc-layout-grid__inner">

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title card-padding p-0 mb-2">Student list</h6>
          <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
            <p class="m-0 text-dark">You can search those student who is in any class or having student account.</p>
            <p class="m-0 text-dark">If you want to see those who only having student account and <span class="text-danger">not assign to any class</span> <span class="font-weight-bold">then clear filter and click search button</span></p>
          </div>
          <form class="mdc-layout-grid__inner" id="searchStudent">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                <input type="hidden" name="department">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list department-list-active" form-target="searchStudent"></ul>
                </div>
                <span class="mdc-floating-label">Department</span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class course-type-list-active" data-validate="Select course" data-mdc-auto-init="MDCSelect">
                <input type="hidden" name="course-type">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list course-type-list-active" form-target="searchStudent"></ul>
                </div>
                <span class="mdc-floating-label">Course Type</span>
                <div class="mdc-line-ripple"></div>
              </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
              <div class="mdc-select demo-width-class session-list-active" data-validate="Select session" data-mdc-auto-init="MDCSelect">
                <input type="hidden" name="session">
                <i class="mdc-select__dropdown-icon"></i>
                <div class="mdc-select__selected-text"></div>
                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                  <ul class="mdc-list session-list-active" form-target="searchStudent"></ul>
                </div>
                <span class="mdc-floating-label">Session</span>
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
          <div class="table-responsive m-0 mb-2 mt-3 p-1">
            <table class="table table-hover datatable-shadow" id="student-list">
              <thead>
                <tr>
                  <th>S.no</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Roll no</th>
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
<script src="ajax/branch-subject.js"></script>
<script src="ajax/student-list.js"></script>
<script>
  load_department();
  $('#student-list').DataTable({
    responsive: true,
    "bDestroy": true,
  });
</script>

<?php require_once("./partials/script_end.php"); ?>