<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Attendance type</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">


<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <span class="d-flex align-items-center mb-4">
            <i class="material-icons">adjust</i>
            <h2 class="card-title m-0 pl-2">Attendance type</h2>
        </span>

        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Create attendance type</h6>
                    <form class="mdc-layout-grid__inner validate-form" method="POST" submit-type="attendance-create" id="createAttendanceTypeForm">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Name is empty">
                                <input class="mdc-text-field__input valid-input" name="attendance-name">
                                <div class="mdc-line-ripple"></div>
                                <label for="attendance-type-name" class="mdc-floating-label">Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                            <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded" id="addAttendanceTypeBtn">
                                <span class="button__text">Create</span>
                            </button>
                        </div>
                        <div style="display: none;" class=" mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                            <button type="submit" class=" mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded" attendance-type-key="" id="updateAttendanceTypeBtn">
                                <span class="button__text">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card p-0">
                    <h6 class="card-title card-padding pb-0 mb-2">Attendance type list</h6>
                    <div class="table-responsive mb-2">
                        <table class="table table-hover datatable-shadow" id="table2">
                            <thead>
                                <th>Sno</th>
                                <th>Name</th>
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

<script src="ajax/attendance_type.js"></script>
<script>
    $(document).ready(function() {
        load_attendance_type();
    });
</script>

<?php require_once("./partials/script_end.php"); ?>