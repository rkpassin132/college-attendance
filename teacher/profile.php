<?php
include_once('../api/function.php');
check_user_page($conn, USER_ROLE['teacher']);
$user = query_getData1($conn, "SELECT * from teacher_detail where user_id=" . get_userId());
include_once('./partials/head_start.php');
?>

<title>Profile</title>

<?php include_once('./partials/head_end.php'); ?>


<main class="content-wrapper">
  <div class="mdc-layout-grid">
    <span class="d-flex align-items-center mb-4">
      <i class="material-icons">adjust</i>
      <h2 class="card-title m-0 pl-2">Profile</h2>
    </span>

    <div class="mdc-layout-grid__inner">

      <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card ">
          <h6 class="card-title mb-3">Update personal info</h6>
          <form id="change-personal-info-form" class="mdc-layout-grid__inner validate-form" method="post">

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <div class="mdc-text-field validate-input" data-validate="First name is required">
                <input class="mdc-text-field__input valid-input" value="<?= $user['first_name'] ?>" type="text" name="first_name">
                <div class="mdc-line-ripple"><?= $user['first_name'] ?></div>
                <label for="first-name" class="mdc-floating-label">First name</label>
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
              <div class="mdc-text-field validate-input" data-validate="Phone is required">
                <input class="mdc-text-field__input valid-input" value="<?= $user['phone'] ?>" type="text" name="phone">
                <div class="mdc-line-ripple"><?= $user['phone'] ?></div>
                <label for="phone" class="mdc-floating-label">Phone</label>
              </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <div class="mdc-text-field validate-input" data-validate="Address is required">
                <input class="mdc-text-field__input valid-input" value="<?= $user['address'] ?>" type="text" name="address">
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
              <div class="mdc-text-field validate-input" data-validate="Password is required">
                <input class="mdc-text-field__input valid-input" type="password" name="password">
                <div class="mdc-line-ripple"></div>
                <label for="user-password" class="mdc-floating-label">Password</label>
              </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <div class="mdc-text-field validate-input" data-validate="Confirm is required">
                <input class="mdc-text-field__input valid-input" type="password" name="confirm_password">
                <div class="mdc-line-ripple"></div>
                <label for="user-password" class="mdc-floating-label">Confirm Password</label>
              </div>
            </div>

            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
              <button type="submit" class="mdc-button mdc-button--raised filled-button--success mdc-ripple-upgraded">
                <span class="button__text">Change</span>
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</main>

<?php require_once("./partials/script_start.php"); ?>

<script src="ajax/profile.js"></script>

<?php require_once("./partials/script_end.php"); ?>