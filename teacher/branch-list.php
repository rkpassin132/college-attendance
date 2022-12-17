<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['teacher']);
include_once('./partials/head_start.php');
?>

<title>Branches</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">


<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <span class="d-flex align-items-center mb-4">
            <i class="material-icons">adjust</i>
            <h2 class="card-title m-0 pl-2">Branches</h2>
        </span>

        <div class="mdc-layout-grid__inner">

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card p-0">
                    <h6 class="card-title card-padding pb-0 mb-2">Branch related to you</h6>
                    <div class="table-responsive mb-2">
                        <table class="table table-hover datatable-shadow" id="table2">
                          <thead>
                            <th>Sno</th>
                            <th>Department</th>
                            <th>Department Short</th>
                            <th>Course</th>
                            <th>Course Short</th>
                          </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once("./partials/script_start.php"); ?>

<script src="<?= BASE_URL ?>ajax/common.js"></script>
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

<script src="ajax/branch.js"></script>

<?php require_once("./partials/script_end.php"); ?>