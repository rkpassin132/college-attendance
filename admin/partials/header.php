<header class="mdc-top-app-bar">
  <div class="mdc-top-app-bar__row">
    <div class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
      <button class="material-icons mdc-top-app-bar__navigation-icon mdc-icon-button sidebar-toggler">menu</button>
      <span class="mdc-top-app-bar__title"><?php echo $_SESSION['user']['name'] ?></span>
      <div data-toggle="modal" data-target="#searchModal" class="search-desktop mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon search-text-field d-none d-md-flex">
        <i class="material-icons mdc-text-field__icon">search</i>
        <input readonly class="mdc-text-field__input" id="text-field-hero-input">
        <div class="mdc-notched-outline mdc-notched-outline--upgraded mdc-notched-outline--notched">
          <div class="mdc-notched-outline__leading"></div>
          <div class="mdc-notched-outline__notch">
            <label for="text-field-hero-input" class="mdc-floating-label">Search department, student and teacher</label>
          </div>
          <div class="mdc-notched-outline__trailing"></div>
        </div>
      </div>
    </div>
    <div class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end mdc-top-app-bar__section-right">
      <button data-toggle="modal" data-target="#searchModal" class="mdc-button text-dark mdc-menu-button search-mobile">
        <i class="material-icons">search</i>
      </button>
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
    </div>
  </div>
</header>
<?php require_once('search-modal.php'); ?>