<?php

include_once 'api/function.php';
if (check_user($conn)) {
    redirect_dashboard();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="assets/images/favicon.png" />

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">

</head>

<style>
    .body-wrapper .main-wrapper .page-wrapper {
        background: #0e70ae;
    }

    .limiter {
        background: url(assets/images/bg-main.jpg) left top no-repeat;
        background-size: 100%;
        min-height: calc(100vh - 13vh) !important;
    }

    .footer {
        background-size: 100%;
        text-align: center;
        padding-top: 13px;
        position: relative;
        background: #e99856;
    }

    @media only screen and (max-width: 950px) {
        .img-div {
            display: none;
        }
        .mdc-card{
            padding: 26px 17px;
        }
    }
</style>

<body>
    <div class="body-wrapper">
        <div class="main-wrapper mdc-drawer-app-content">
            <div class="page-wrapper mdc-toolbar-fixed-adjust">
                <main class="content-wrapper d-flex align-items-center justify-content-center limiter">
                    <div class="mdc-layout-grid__inner">
                        <div class="img-div mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6  d-flex align-items-center justify-content-center">
                            <div class="login100-pic js-tilt" data-tilt>
                                <img src="assets/images/img-01.png" alt="IMG">
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6 mdc-layout-grid__cell--span-12-tablet">
                            <div class="mdc-card" style="border-radius: 12px;">
                                <form class="mdc-layout-grid__inner validate-form" id="login-form">
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                                        <img width="80%" src="assets/images/logo-long.png" alt="">
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                                        <div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon validate-input" data-validate="Email is required">
                                            <i class="material-icons mdc-text-field__icon">account_circle</i>
                                            <input class="mdc-text-field__input valid-input" type="email" name="email" >
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label class="mdc-floating-label">Email</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                                        <div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon validate-input" data-validate="Password is required">
                                            <i class="material-icons mdc-text-field__icon">lock</i>
                                            <input class="mdc-text-field__input valid-input" name="password" type="password">
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label  class="mdc-floating-label">Password</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                                        <button type="submit" class="w-100 mdc-button mdc-button--raised filled-button--warning mdc-ripple-upgraded" style="background: #f7941d;" id="addCourseTypeBtn">
                                            <span class="button__text">Login</span>
                                        </button>
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                                        <p class="text-dark pl-4 pr-4"><span class="font-weight-bold">Any Issues?</span> Please Contact ERP & LMS Helpdesk help@dseu.ac.in</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
            </main>
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 col-md-12">
                            <p class="text-white">Design &amp; Developed By Rahul-kumar, India</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>


    <script src="<?= BASE_URL ?>assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?= BASE_URL ?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/vendor/tilt/tilt.jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/material.js"></script>
    <script src="<?= BASE_URL ?>assets/js/misc.js"></script>

    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <script src="ajax/common.js"></script>
    <script src="ajax/login.js"></script>

</body>

</html>