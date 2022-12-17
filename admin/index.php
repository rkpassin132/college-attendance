<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Admin</title>

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
                <div class="mdc-card info-card info-card--danger">
                    <div class="card-inner">
                        <h4>Department</h4>
                        <h5 class="font-weight-light pb-2 mb-1 " id="branch-active-count" title="Active">
                            <i class="material-icons options-icon text-success align-middle">check_circle</i>
                            <span class="align-middle">0</span>
                        </h5>
                        <h5 class="font-weight-light pb-2 mb-1 " id="branch-inactive-count" title="Inactive">
                            <i class="material-icons options-icon text-danger align-middle">indeterminate_check_box</i>
                            <span class="align-middle">0</span>
                        </h5>
                        <p class="tx-12 text-muted">Total number of branch</p>
                        <div class="card-icon-wrapper">
                            <i class="material-icons">library_add</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
                <div class="mdc-card info-card info-card--success">
                    <div class="card-inner">
                        <h4>Courses</h4>
                        <h5 class="font-weight-light pb-2 mb-1 " id="course-active-count" title="Active">
                            <i class="material-icons options-icon text-success align-middle">check_circle</i>
                            <span class="align-middle">0</span>
                        </h5>
                        <h5 class="font-weight-light pb-2 mb-1 " id="course-inactive-count" title="Inactive">
                            <i class="material-icons options-icon text-danger align-middle">indeterminate_check_box</i>
                            <span class="align-middle">0</span>
                        </h5>
                        <p class="tx-12 text-muted">Total number of course</p>
                        <div class="card-icon-wrapper">
                            <i class="material-icons">golf_course</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
                <div class="mdc-card info-card info-card--primary">
                    <div class="card-inner">
                        <h4>Teachers</h4>
                        <h5 class="font-weight-light pb-2 mb-1 " id="teacher-active-count" title="Active">
                            <i class="material-icons options-icon text-success align-middle">check_circle</i>
                            <span class="align-middle">0</span>
                        </h5>
                        <h5 class="font-weight-light pb-2 mb-1 " id="teacher-inactive-count" title="Inactive">
                            <i class="material-icons options-icon text-danger align-middle">indeterminate_check_box</i>
                            <span class="align-middle">0</span>
                        </h5>
                        <p class="tx-12 text-muted">Total number teachers</p>
                        <div class="card-icon-wrapper">
                            <i class="material-icons">assignment_ind</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
                <div class="mdc-card info-card info-card--info">
                    <div class="card-inner">
                        <h4>Students</h4>
                        <h5 class="font-weight-light pb-2 mb-1 " id="student-active-count" title="Active">
                            <i class="material-icons options-icon text-success align-middle">check_circle</i>
                            <span class="align-middle">0</span>
                        </h5>
                        <h5 class="font-weight-light pb-2 mb-1 " id="student-inactive-count" title="Inactive">
                            <i class="material-icons options-icon text-danger align-middle">indeterminate_check_box</i>
                            <span class="align-middle">0</span>
                        </h5>
                        <p class="tx-12 text-muted">Total number of students</p>
                        <div class="card-icon-wrapper">
                            <i class="material-icons">account_circle</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-8">
                <div class="mdc-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-2 mb-sm-0">Teaher / Student</h4>
                    </div>
                    <div class="d-block d-sm-flex justify-content-between align-items-center">
                        <h6 class="card-sub-title mb-0 text-dark mt-2">Teacher and student admission record according to year</h6>
                    </div>
                    <div class="chart-container mt-4">
                        <canvas id="teachet-student-bar-graph" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4 mdc-layout-grid__cell--span-8-tablet">
                <div class="mdc-card">
                    <div class="d-flex d-lg-block d-xl-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Branch students</h4>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                                <div class="mdc-select demo-width-class" data-mdc-auto-init="MDCSelect">
                                    <input type="hidden" name="course-type">
                                    <i class="mdc-select__dropdown-icon"></i>
                                    <div class="mdc-select__selected-text"></div>
                                    <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                        <ul class="mdc-list" id="course-type-select"></ul>
                                    </div>
                                    <span class="mdc-floating-label">Course Type</span>
                                    <div class="mdc-line-ripple"></div>
                                </div>
                            </div>
                        </div>
                        <div id="sales-legend" class="d-flex flex-wrap"></div>
                    </div>
                    <div class="chart-container mt-4" id="chart-department-student">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once("./partials/script_start.php"); ?>

<!-- Page js -->
<script src="<?= BASE_URL ?>assets/vendors/chartjs/Chart.min.js"></script>
<!-- Page js-->
<script src="ajax/dashboard.js"></script>

<?php require_once("./partials/script_end.php"); ?>