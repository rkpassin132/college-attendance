<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['admin']);
include_once('./partials/head_start.php');
?>

<title>Teacher Create</title>

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
  <div class="mdc-layout-grid">
    <span class="d-flex align-items-center mb-4">
      <i class="material-icons">adjust</i>
      <h2 class="card-title m-0 pl-2">Teacher Create</h2>
    </span>

    <div class="mdc-layout-grid__inner">

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title mb-3">Create Teacher</h6>
          <form id="create-user-form" class="mdc-layout-grid__inner validate-form" method="post">

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <div class="mdc-text-field validate-input" data-validate="Email is required">
                <input class="mdc-text-field__input valid-input" type="email" name="email">
                <div class="mdc-line-ripple"></div>
                <label for="user-email" class="mdc-floating-label">Email <span class="text-danger">*</span></label>
              </div>
            </div>

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
              <div class="mdc-text-field validate-input" data-validate="First name is required">
                <input class="mdc-text-field__input valid-input" name="first-name">
                <div class="mdc-line-ripple"></div>
                <label for="user-first-name" class="mdc-floating-label">First name <span class="text-danger">*</span></label>
              </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <div class="mdc-text-field validate-input" data-validate="Last name is required">
                <input class="mdc-text-field__input valid-if-input" name="last-name">
                <div class="mdc-line-ripple"></div>
                <label for="user-last-name" class="mdc-floating-label">Last name</label>
              </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <div class="mdc-text-field">
                <input class="mdc-text-field__input" type="number" name="phone">
                <div class="mdc-line-ripple"></div>
                <label for="user-phone" class="mdc-floating-label">Phone</label>
              </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <div class="mdc-text-field">
                <input class="mdc-text-field__input" name="address">
                <div class="mdc-line-ripple"></div>
                <label for="user-address" class="mdc-floating-label">Address</label>
              </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                <span class="button__text">Create</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title mb-3">Create Teacher From Excel</h6>
          <div class="bd-callout bd-callout-warning pl-3 pr-3 pb-2 pt-2 mt-2">
            <h4 class="alert-heading d-flex align-items-center text-dark">
              <i class="material-icons mdc-button__icon m-0 text-info">info</i>
              <span class="ml-2">File upload information - [ <a download="teacher" href="<?= BASE_URL ?>assets/excel-template/teacher.csv" class="text-danger">Template</a> ]</span>
            </h4>
            <p class="m-0 text-dark">Create multiple teacher at once by uploading excel file. Please select only excel file (<span class="text-danger">.csv, .xls, .xlsx</span>) with correct data</p>
            <hr />
            <ul class="pl-3">
              <li>
                <p class="m-0 text-dark">First row of sheet is negligible. You can set your column name there.</p>
              </li>
              <li>
                <p class="m-0 text-dark"><span class="text-danger">Column 1</span> = Email, <span class="text-danger">2</span> = Password, <span class="text-danger">3</span> = First name, <span class="text-danger">4</span> = Last name, <span class="text-danger">5</span> = Phone, <span class="text-danger">6</span> = Address</p>
              </li>
            </ul>
          </div>
          <form class="mdc-layout-grid__inner validate-form" id="create-teacher-file-form">
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
                  <input style="display: none;" class="valid-file-input" valid-file="excel,pdf" name="teacher-file" type="file" accept="<?= join(",", FILE['extention']['excel']) ?>">
                </label>
                <input class="mdc-text-field__input mdc-notched-outline--upgraded mdc-notched-outline--notched" readonly name="teacher-file-name" type="text">
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

    </div>
  </div>
</main>

<?php require_once("./partials/script_start.php"); ?>

<script src="ajax/teacher-create.js"></script>

<?php require_once("./partials/script_end.php"); ?>