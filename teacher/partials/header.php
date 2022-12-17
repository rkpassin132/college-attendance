<header class="mdc-top-app-bar">
  <div class="mdc-top-app-bar__row">
    <div class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
      <button class="material-icons mdc-top-app-bar__navigation-icon mdc-icon-button sidebar-toggler">menu</button>
      <span class="mdc-top-app-bar__title"><?php echo $_SESSION['user']['name'] ?></span>
    </div>
    <div class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end mdc-top-app-bar__section-right">
      <div class="menu-button-container menu-profile d-md-block">
        <button class="mdc-button mdc-menu-button">
          <i class="mdi mdi-account"></i>
        </button>

        <div class="mdc-menu mdc-menu-surface" tabindex="-1">
          <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical">
            <li class="mdc-list-item reload-button" role="menuitem" onclick="window.location.reload();">
              <div class="item-thumbnail item-thumbnail-icon-only">
                <i class="mdi mdi-reload text-primary"></i>
              </div>
              <div class="item-content d-flex align-items-start flex-column justify-content-center">
                <span class="item-subject font-weight-normal">Reload</span>
              </div>
            </li>
            <li class="mdc-list-item" role="menuitem" onclick="window.location.href= 'profile.php'">
              <div class="item-thumbnail item-thumbnail-icon-only">
                <i class="mdi mdi-account-edit-outline text-primary"></i>
              </div>
              <div class="item-content d-flex align-items-start flex-column justify-content-center">
                <h6 class="item-subject font-weight-normal">Edit profile</h6>
              </div>
            </li>
            <li class="mdc-list-item" role="menuitem" onclick="window.location.href= '<?= BASE_URL . 'api/logout.php' ?>'">
              <div class="item-thumbnail item-thumbnail-icon-only">
                <i class="mdi mdi-settings-outline text-primary"></i>
              </div>
              <div href="<?= BASE_URL . 'api/logout.php' ?>" class="item-content d-flex align-items-start flex-column justify-content-center">
                <span class="item-subject font-weight-normal">Logout</span>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <!-- <div class="divider d-none d-md-block"></div>

            <div class="menu-button-container">
              <button class="mdc-button mdc-menu-button">
                <i class="mdi mdi-bell"></i>
              </button>
              <div class="mdc-menu mdc-menu-surface" tabindex="-1">
                <h6 class="title"> <i class="mdi mdi-bell-outline mr-2 tx-16"></i> Notifications</h6>
                <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical">
                  <li class="mdc-list-item" role="menuitem">
                    <div class="item-thumbnail item-thumbnail-icon">
                      <i class="mdi mdi-email-outline"></i>
                    </div>
                    <div class="item-content d-flex align-items-start flex-column justify-content-center">
                      <h6 class="item-subject font-weight-normal">You received a new message</h6>
                      <small class="text-muted"> 6 min ago </small>
                    </div>
                  </li>
                </ul>
              </div>
            </div> -->
    </div>
  </div>
</header>