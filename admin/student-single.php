<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);

if (!isset($_GET['student'])) page_goBack();
if (empty($_GET['student']) || valid_email($_GET['student']) != 1) page_goBack();
$email = sql_prevent($conn, xss_prevent($_GET['student']));
$user = query_getData1($conn, "SELECT u.status, u.email, td.* from user u LEFT JOIN student_detail td on td.user_id=u.id where u.email='$email' and u.role=" . USER_ROLE['student']);
if ($user == null) page_goBack();

include_once('./partials/head_start.php');
?>

<title>Student-Profile</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/bootstrap.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatable/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/datatable/buttons.dataTables.min.css">

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <span class="d-flex align-items-center mb-4">
            <i title="<?= ($user['status'] == '1') ? 'Activated' : 'Deactivated' ?>" class="material-icons text-white p-1 rounded-circle <?= ($user['status'] == '1') ? 'bg-success' : 'bg-danger' ?>" id="student-key" data-value="<?= $user['id'] ?>">power_settings_new</i>
            <h3 class="card-title m-0 pl-2">Student Profile - (<?= $user['email'] ?>)</h3>
        </span>
        <?php $active_tab='profile'; include_once('./partials/student-page-tab.php'); ?>

        <div class="mdc-layout-grid__inner">

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Personal info</h6>
                    <form id="change-personal-info-form" class="mdc-layout-grid__inner validate-form" method="post">

                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field validate-input" data-validate="First name is required">
                                <input class="mdc-text-field__input valid-input" value="<?= $user['first_name'] ?>" type="text" name="first_name">
                                <div class="mdc-line-ripple"><?= $user['first_name'] ?></div>
                                <label for="first-name" class="mdc-floating-label">First name <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Last name is required">
                                <input class="mdc-text-field__input valid-if-input" value="<?= $user['last_name'] ?>" type="text" name="last_name">
                                <div class="mdc-line-ripple"><?= $user['last_name'] ?></div>
                                <label for="last-name" class="mdc-floating-label">Last name</label>
                            </div>
                        </div>

                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Last name is required">
                                <input class="mdc-text-field__input valid-if-input" value="<?= $user['roll_no'] ?>" type="text" name="roll-no">
                                <div class="mdc-line-ripple"><?= $user['roll_no'] ?></div>
                                <label for="last-name" class="mdc-floating-label">Roll no <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Phone is required">
                                <input class="mdc-text-field__input valid-if-inpu" value="<?= $user['phone'] ?>" type="text" name="phone">
                                <div class="mdc-line-ripple"><?= $user['phone'] ?></div>
                                <label for="phone" class="mdc-floating-label">Phone</label>
                            </div>
                        </div>

                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field validate-input" data-validate="Address is required">
                                <input class="mdc-text-field__input valid-if-inpu" value="<?= $user['address'] ?>" type="text" name="address">
                                <div class="mdc-line-ripple"><?= $user['address'] ?></div>
                                <label for="address" class="mdc-floating-label">Address</label>
                            </div>
                        </div>

                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                                <span class="button__text">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card ">
                    <h6 class="card-title mb-3">Change Password</h6>
                    <form id="change-password-form" class="mdc-layout-grid__inner validate-form" method="post">

                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon validate-file-input" data-validate="Password is required">
                                <label class="password-visibility">
                                    <i class="material-icons mdc-text-field__icon">visibility</i>
                                </label>
                                <input class="mdc-text-field__input mdc-notched-outline--upgraded mdc-notched-outline--notched pl-4 valid-input" name="password" type="password">
                                <div class="mdc-notched-outline ">
                                    <div class="mdc-notched-outline__leading"></div>
                                    <div class="mdc-notched-outline__notch">
                                        <label class="mdc-floating-label ">Password <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="mdc-notched-outline__trailing"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon validate-file-input" data-validate="Confirm is required">
                                <label class="password-visibility">
                                    <i class="material-icons mdc-text-field__icon">visibility</i>
                                </label>
                                <input class="mdc-text-field__input mdc-notched-outline--upgraded mdc-notched-outline--notched pl-4 valid-input" name="confirm_password" type="password">
                                <div class="mdc-notched-outline ">
                                    <div class="mdc-notched-outline__leading"></div>
                                    <div class="mdc-notched-outline__notch">
                                        <label class="mdc-floating-label ">Confirm Password <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="mdc-notched-outline__trailing"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                            <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                                <span class="button__text">change</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card p-0">
                    <div class="mdc-layout-grid__inner mb-4">
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
                            <h3 class="text-dark card-padding pb-0 mb-2">Time table</h3>
                            <h5 class="ml-4 text-dark text-capitalize" id="student-class"></h5>
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

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card " style="box-shadow: 0 1px 3px 0 rgb(191 6 6 / 75%);">
                    <h3 class="text-danger mb-3">Danger Zone</h3>
                    <ul class="list-group align-items-center">
                        <li class="list-group-item list-group-item-action d-flex row align-items-center">
                            <div class="col-sm-8 p-0">
                                <h5 class="mb-1">Change account visibility</h5>
                                <p class="mb-1">Change visibility by activating and deactivating user. If you deactivate user, anyone not able to do any operation. Even user not able to login his/her account.</p>
                            </div>
                            <div class="col-sm-4 p-0 text-right">
                                <button type="button" id="<?= ($user['status'] == '1') ? 'student-deactivate' : 'student-activate' ?>" class="btn btn-outline-danger"><?= ($user['status'] == '1') ? 'Deactivate' : 'Activate' ?></button>
                            </div>
                        </li>
                        <li class="list-group-item list-group-item-action d-flex row align-items-center">
                            <div class="col-sm-8 p-0">
                                <h5 class="mb-1">Delete this account</h5>
                                <p class="mb-1">Once you delete an account, there is no going back. Every think related to this user will going to delete.</p>
                            </div>
                            <div class="col-sm-4 p-0 text-right">
                                <button type="button" class="btn btn-outline-danger">Delete Account</button>
                            </div>
                        </li>
                    </ul>
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

<script src="ajax/student-single.js"></script>
<script>
    $(document).ready(function() {
        $("#day-select").click();
        $('#table2').DataTable({
            responsive: true,
            bDestroy: true,
        });
    });
</script>

<?php require_once("./partials/script_end.php"); ?>