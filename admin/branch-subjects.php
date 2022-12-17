<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Branche Subject</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/custom.css">
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
            <h2 class="card-title m-0 pl-2">Branche Subject</h2>
        </span>

        <div class="mdc-layout-grid__inner">

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Add Subject</h6>
                    <div class="bd-callout bd-callout-warning pl-2 pb-2 pt-2 mt-2">
                        <p class="m-0 text-dark">Add subject to session of branch of course. Add subject carefully.</p>
                        <p class="m-0 text-dark"><span class="font-weight-bold">Session</span> is semester or year according to branch.</p>
                    </div>
                    <form class="mdc-layout-grid__inner validate-form" id="addBranchSubject">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                            <div class="mdc-select demo-width-class validate-input department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                                <input class="valid-input" type="hidden" name="department">
                                <i class="mdc-select__dropdown-icon"></i>
                                <div class="mdc-select__selected-text"></div>
                                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                    <ul class="mdc-list department-list-active" form-target="addBranchSubject"></ul>
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
                                    <ul class="mdc-list course-type-list-active" form-target="addBranchSubject"></ul>
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
                                    <ul class="mdc-list session-list-active" form-target="addBranchSubject"></ul>
                                </div>
                                <span class="mdc-floating-label">Session <span class="text-danger">*</span></span>
                                <div class="mdc-line-ripple"></div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6">
                            <div class="validate-input w-100" data-validate="Select subject">
                                <select class=" js-states form-control valid-input" id="subject-select-list" name="subjects[]" multiple="multiple"></select>
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
                    <h6 class="card-title card-padding p-0 mb-2">Search subject</h6>
                    <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
                        <p class="m-0 text-dark">Search by course, branch and session. You can also alear filter and search all subject connection as per your requirement.</p>
                        <p class="m-0 text-dark">You can delete subject related to session of branch of course.</p>
                    </div>
                    <form class="mdc-layout-grid__inner" id="searchBranchSubject">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                            <div class="mdc-select demo-width-class validate-input department-list-active" data-validate="Select department" data-mdc-auto-init="MDCSelect">
                                <input class="valid-input" type="hidden" name="department">
                                <i class="mdc-select__dropdown-icon"></i>
                                <div class="mdc-select__selected-text"></div>
                                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                    <ul class="mdc-list department-list-active" form-target="searchBranchSubject"></ul>
                                </div>
                                <span class="mdc-floating-label">Department</span>
                                <div class="mdc-line-ripple"></div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                            <div class="mdc-select demo-width-class validate-input course-type-list-active" data-validate="Select course" data-mdc-auto-init="MDCSelect">
                                <input class="valid-input" type="hidden" name="course-type">
                                <i class="mdc-select__dropdown-icon"></i>
                                <div class="mdc-select__selected-text"></div>
                                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                    <ul class="mdc-list course-type-list-active" form-target="searchBranchSubject"></ul>
                                </div>
                                <span class="mdc-floating-label">Course Type</span>
                                <div class="mdc-line-ripple"></div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                            <div class="mdc-select demo-width-class validate-input session-list-active" data-validate="Select session" data-mdc-auto-init="MDCSelect">
                                <input class="valid-input" type="hidden" name="session">
                                <i class="mdc-select__dropdown-icon"></i>
                                <div class="mdc-select__selected-text"></div>
                                <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                    <ul class="mdc-list session-list-active" form-target="searchBranchSubject"></ul>
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
                    <div class="table-responsive m-0 mb-2 mt-3">
                        <table class="table table-hover datatable-shadow" id="table2">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>Session</th>
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
<script type="text/javascript" src="<?= BASE_URL ?>assets/vendor/select2/select2.min.js"></script>
<!-- Page js-->
<script src="<?= BASE_URL ?>assets/vendor/sweetAlerts/sweetalert.min.js"></script>

<script src="ajax/branch-subject.js"></script>
<script>
    load_department();
    load_subject();
    $('#subject-select-list').select2({
        placeholder: "Select subjects",
        theme: "material",
        multiple: true,
        closeOnSelect: false
    });

    $('#table2').DataTable({
        responsive: true,
        "bDestroy": true
    });
</script>
<?php require_once("./partials/script_end.php"); ?>