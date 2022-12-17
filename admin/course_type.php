<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Course types</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">


<?php include_once('./partials/head_end.php'); ?>

<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <span class="d-flex align-items-center mb-4">
            <i class="material-icons">adjust</i>
            <h2 class="card-title m-0 pl-2">Course type</h2>
        </span>

        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Create Course Type</h6>

                    <div style="display:none;" class="alert alert-danger" role="alert" id="course-type-error">
                        Course Type should not be blank
                    </div>

                    <form class="mdc-layout-grid__inner validate-form" id="createCourseTypeForm">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Course name is required">
                                <input class="mdc-text-field__input valid-input" name="name">
                                <div class="mdc-line-ripple"></div>
                                <label for="text-field-hero-input" class="mdc-floating-label">Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Short name is required">
                                <input class="mdc-text-field__input valid-input" name="short_name">
                                <div class="mdc-line-ripple"></div>
                                <label for="text-field-hero-input" class="mdc-floating-label">Short Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <button class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded" id="addCourseTypeBtn">
                                <span class="button__text">Create</span>
                            </button>
                        </div>
                        <div style="display: none;" class=" mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                            <button class=" mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded" course-key="" id="updateCourseTypeBtn">
                                <span class="button__text">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title card-padding p-0 mb-2">Course type list</h6>
                    <div class="table-responsive m-0 mb-2 mt-3 p-1">
                        <table class="table table-hover datatable-shadow" id="table3">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Name</th>
                                    <th>Short Name</th>
                                    <th>Action</th>
                                </tr>
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
<script type="text/javascript" src="ajax/course_type.js"></script>

<script>
    $(document).ready(function() {
        load_course_type();
    });
</script>

<?php require_once("./partials/script_end.php"); ?>