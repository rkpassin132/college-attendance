<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
$branches = get_branches($conn);
include_once('./partials/head_start.php');
?>

<title>Subject</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/custom.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <span class="d-flex align-items-center mb-4">
            <i class="material-icons">adjust</i>
            <h2 class="card-title m-0 pl-2">Subject</h2>
        </span>

        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Subject</h6>
                    <form id="create-subject-form" submit-type="subject-create" class="mdc-layout-grid__inner validate-form" method="post">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Subject name is required">
                                <input class="mdc-text-field__input valid-input" name="subject-name" id="text-field-hero-input">
                                <div class="mdc-line-ripple"></div>
                                <label for="text-field-hero-input" class="mdc-floating-label">Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                            <button type="submit" id="create-subject-btn" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                                <span class="button__text">Create</span>
                            </button>
                        </div>
                        <div style="display: none;" class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                            <button type="submit" id="update-subject-btn" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                                <span class="button__text">Update</span>
                            </button>
                        </div>
                        <div style="display: none;" class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                            <button id="clear-update-subject-btn" class="mdc-button mdc-button--outlined outlined-button--secondary mdc-ripple-upgraded">
                                <i class="material-icons mdc-button__icon">close</i> Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Create Subject From Excel</h6>
                    <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
                        <h4 class="alert-heading d-flex align-items-center text-dark">
                            <i class="material-icons mdc-button__icon m-0 text-info">info</i>
                            <span class="ml-2">File upload information - [ <a download="subject" href="<?= BASE_URL ?>assets/excel-template/subject.csv" class="text-danger">Template</a> ]</span>
                        </h4>
                        <p class="m-0 text-dark">Create multiple subject at once by uploading excel file. Please select only excel file (<span class="text-danger">.csv, .xls, .xlsx</span>) with correct data</p>
                        <hr/>
                        <ul class="pl-3">
                            <li>
                                <p class="m-0 text-dark">First row of sheet is negligible. You can set your column name there.</p>
                            </li>
                            <li>
                                <p class="m-0 text-dark"><span class="text-danger">Column 1</span> = Subject name</p>
                            </li>
                        </ul>
                    </div>
                    <form class="mdc-layout-grid__inner validate-form" id="create-subject-file-form">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12" style="display: none;">
                            <div class="alert alert-danger w-100" role="alert">
                                <h4 class="alert-heading d-flex align-items-center">
                                    <i class="material-icons mdc-button__icon m-0">error</i>
                                    <span class="ml-2">File error! [ row, column ]</span>
                                </h4>
                                <div class="file-error"></div>
                                <hr>
                                <p class="mb-0">Please add file data carefully.</p>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                            <div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon validate-file-input" data-validate="Password is required">
                                <label style="cursor: pointer; width: 50px;">
                                    <i class="material-icons mdc-text-field__icon">file_upload</i>
                                    <input style="display: none;" class="valid-file-input" valid-file="excel,pdf" name="subject-file" type="file" accept="<?= join(",", FILE['extention']['excel']) ?>">
                                </label>
                                <input class="mdc-text-field__input mdc-notched-outline--upgraded mdc-notched-outline--notched" readonly name="subject-file-name" type="text">
                                <div class="mdc-notched-outline ">
                                    <div class="mdc-notched-outline__leading"></div>
                                    <div class="mdc-notched-outline__notch">
                                        <label class="mdc-floating-label ">Select File</label>
                                    </div>
                                    <div class="mdc-notched-outline__trailing"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                            <button type="submit" class="mdc-button mdc-button--outlined outlined-button--dark mdc-ripple-upgraded">
                                <span class="button__text"><i class="material-icons mdc-button__icon m-0">file_upload</i> Create</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title card-padding p-0 mb-2">Subject list</h6>
                    <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
                        <p class="m-0 text-dark">Updated subject name change from everywhere. Delete data carefully</p>
                    </div>
                    <div class="table-responsive m-0 mb-2 mt-3 p-1">
                        <table class="table table-hover datatable-shadow" id="subject-list-table">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Name</th>
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
<script src="ajax/subject.js"></script>
<script>
    subject_list_load();
</script>

<?php require_once("./partials/script_end.php"); ?>