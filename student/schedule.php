<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['student']);
include_once('./partials/head_start.php');
?>

<title>Student</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card p-0">
                    <div class="mdc-layout-grid__inner mb-4">
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
                            <h3 class="text-dark card-padding pb-0 mb-2">Your Schedule</h3>
                        </div>
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
                            <div class="mdc-select demo-width-class mt-4 ml-4" data-mdc-auto-init="MDCSelect">
                                <input type="hidden" value="<?= strtolower(date('l')) ?>" name="enhanced-select">
                                <i class="mdc-select__dropdown-icon"></i>
                                <div class="mdc-select__selected-text text-capitalize"><?= date('l') ?></div>
                                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                    <ul class="mdc-list" id="day-select">
                                        <?php foreach (WEEK_DAYS as $key => $day) { ?>
                                            <li class="mdc-list-item text-capitalize <?= (strtolower(date('l')) == $key) ? 'mdc-list-item--selected' : '' ?>" data-value="<?= $key ?>"><?= $key ?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <span class="mdc-floating-label mdc-floating-label--float-above">Day</span>
                                <div class="mdc-line-ripple"></div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mb-2">
                        <table class="table table-hover datatable-shadow" id="table2">
                            <thead>
                                <th>Teacher</th>
                                <th>Subject</th>
                                <th>Start Time</th>
                                <th>End Time</th>
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
<script src="ajax/schedule.js"></script>

<script>
    $(document).ready(function() {
        $("#day-select").click();
    });
</script>

<?php require_once("./partials/script_end.php"); ?>