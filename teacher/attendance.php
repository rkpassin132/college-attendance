<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['teacher']);
include_once('./partials/head_start.php');
?>

<title>Teachers</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
  <div class="mdc-layout-grid">
    <div class="mdc-layout-grid__inner mb-4">
      <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
        <span class="d-flex align-items-center">
          <i class="material-icons">adjust</i>
          <h2 class="card-title m-0 pl-2">Attendance</h2>
        </span>
      </div>
      <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
        <span class="d-flex align-items-center">
          <i class="material-icons">access_time</i>
          <h3 class="text-danger m-0 pl-2" id="current-time"></h3>
        </span>
      </div>
    </div>

    <div class="mdc-layout-grid__inner">

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card p-0">
          <div class=" card-padding pb-0 mb-4" id="attendance-class">
            <h3 class="mb-3 text-capitalize">Students attendance</h3>
          </div>
          <div class="table-responsive mb-2">
            <table class="table table-hover datatable-shadow" id="table2">
              <thead>
                <tr>
                  <th>Student</th>
                  <th>Roll no</th>
                  <th>Acton</th>
                </tr>
              </thead>
              <tbody></tbody>
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
<script src="ajax/attendance.js"></script>
<script>
  setInterval(() => {
    $("#current-time").text(new Date().toLocaleTimeString({
      hour12: true
    }));
  }, 1000)
  $(document).ready(function() {
    $('#table2').DataTable({
      responsive: true
    });
  });
</script>

<?php require_once("./partials/script_end.php"); ?>