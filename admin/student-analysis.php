<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);

if (!isset($_GET['student'])) page_goBack();
if (empty($_GET['student']) || valid_email($_GET['student']) != 1) page_goBack();
$email = sql_prevent($conn, xss_prevent($_GET['student']));
$user = query_getData1($conn, "SELECT u.id,u.status, u.email, u.name from user u LEFT JOIN student_detail td on td.user_id=u.id where u.email='$email' and u.role=" . USER_ROLE['student']);
if ($user == null) page_goBack();

include_once('./partials/head_start.php');
?>

<title>Student</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <span class="d-flex align-items-center mb-4">
            <i title="<?= ($user['status'] == '1')?'Activated':'Deactivated' ?>" class="material-icons text-white p-1 rounded-circle <?= ($user['status'] == '1')?'bg-success':'bg-danger' ?>" id="student-key" data-value="<?= $user['id'] ?>">power_settings_new</i>
            <h3 class="card-title m-0 pl-2"><?= $user['name'] ?></h3>
        </span>
        <span class="d-flex align-items-center mb-4">
            <i class="material-icons">adjust</i>
            <h3 class="card-title m-0 pl-2" id="student-current-class">Class not assigned</h3>
        </span>
        <?php $active_tab='analysis'; include_once('./partials/student-page-tab.php'); ?>

        <div class="mdc-layout-grid__inner">

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                <div class="mdc-card info-card info-card--success">
                    <div class="card-inner">
                        <h4>Total subject</h4>
                        <h4 class="font-weight-light pb-2 mb-1 " id="total-subject" title="Active">
                            <i class="material-icons options-icon text-success align-middle">check_circle</i>
                            <span class="align-middle">0</span>
                        </h4>
                        <p class="tx-14 text-dark">Total subjects in this session</p>
                        <div class="card-icon-wrapper">
                            <i class="material-icons">dashboard</i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                <div class="mdc-card info-card info-card--danger">
                    <div class="card-inner">
                        <h4>Total attendace</h4>
                        <h4 class="font-weight-light pb-2 mb-1 " id="total-attendance" title="Active">
                            <i class="material-icons options-icon text-success align-middle">check_circle</i>
                            <span class="align-middle">0</span>
                        </h4>
                        <p class="tx-14 text-dark">Number of classes take by you in this session</p>
                        <div class="card-icon-wrapper">
                            <i class="material-icons">assignment_turned_in</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4 ">
                <div class="mdc-card info-card">
                    <h4>Attendnace</h4>
                    <canvas id="attendance-chart"></canvas>
                </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card p-0">
                    <h3 class="text-dark card-padding pb-0 mb-2">Today's remain schedule - <?= date('l') ?></h3>
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
<script src="<?= BASE_URL ?>assets/vendor/datatable/jquery.dataTables.min.js"></script>
<script src="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= BASE_URL ?>assets/vendor/datatable/dataTables.responsive.min.js"></script>
<script src="<?= BASE_URL ?>assets/vendors/chartjs/Chart.min.js"></script>
<!-- Page js-->
<script src="ajax/student-analysis.js"></script>

<script>
    $(document).ready(function() {
        load_student_current_class();
        $('#table2').DataTable({
            responsive: true,
            bDestroy: true,
        });
    });
</script>

<?php require_once("./partials/script_end.php"); ?>