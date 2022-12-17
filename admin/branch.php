<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Branches</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/select2/select2.min.css">


<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <span class="d-flex align-items-center mb-4">
            <i class="material-icons">adjust</i>
            <h2 class="card-title m-0 pl-2">Branches</h2>
        </span>

        <div class="mdc-layout-grid__inner">

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Create Branch</h6>
                    <div class="bd-callout bd-callout-warning pl-2 pb-2 pt-2 mt-2">
                        <p class="m-0 text-dark">Create branch by joining Department, course type and session type (Semester or year).</p>
                    </div>
                    <form class="mdc-layout-grid__inner validate-form" id="createBranch">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                            <div class="mdc-select demo-width-class validate-input" data-validate="Select course" data-mdc-auto-init="MDCSelect">
                                <input class="valid-input" type="hidden" name="department">
                                <i class="mdc-select__dropdown-icon"></i>
                                <div class="mdc-select__selected-text"></div>
                                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                    <ul class="mdc-list" id="department-select-list"></ul>
                                </div>
                                <span class="mdc-floating-label">Department <span class="text-danger">*</span></span>
                                <div class="mdc-line-ripple"></div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                            <div class="mdc-select demo-width-class validate-input" data-validate="Select branch" data-mdc-auto-init="MDCSelect">
                                <input class="valid-input" type="hidden" name="course-type">
                                <i class="mdc-select__dropdown-icon"></i>
                                <div class="mdc-select__selected-text"></div>
                                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                    <ul class="mdc-list" id="course-type-select-list"></ul>
                                </div>
                                <span class="mdc-floating-label">Course Type <span class="text-danger">*</span></span>
                                <div class="mdc-line-ripple"></div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-4">
                            <div class="mdc-select demo-width-class m-0 validate-input" data-validate="Select seession type" data-mdc-auto-init="MDCSelect">
                                <input type="hidden" class="valid-input" value="<?= strtolower(date('l')) ?>" name="session-type">
                                <i class="mdc-select__dropdown-icon"></i>
                                <div class="mdc-select__selected-text text-capitalize"><?= date('l') ?></div>
                                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                    <ul class="mdc-list" id="session-select">
                                        <?php foreach (SESSION_TYPE as $key => $session) { ?>
                                            <li class="mdc-list-item text-capitalize" data-value="<?= $key ?>"><?= $session['name'] ?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <span class="mdc-floating-label mdc-floating-label--float-above">Session type <span class="text-danger">*</span></span>
                                <div class="mdc-line-ripple"></div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                            <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">Create</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title card-padding p-0 mb-2">Branch list</h6>
                    <div class="table-responsive m-0 mb-2 mt-3 p-1">
                        <table class="table table-hover datatable-shadow" id="branch-list-table">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Department</th>
                                    <th>Course Type</th>
                                    <th>Session Type</th>
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
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/select2/select2.min.js"></script>
<!-- Page js-->
<script src="<?= BASE_URL ?>assets/vendor/sweetAlerts/sweetalert.min.js"></script>

<script src="ajax/branch.js"></script>
<script>
    load_department_list();
    load_course_type_list();
    branch_list_load();

    $('#table2').DataTable({
        responsive: true,
        "bDestroy": true
    });
</script>
<?php require_once("./partials/script_end.php"); ?>