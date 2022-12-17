<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open">
      <div class="mdc-drawer__header">
        <a href="<?= BASE_URL.'student/' ?>" class="brand-logo">
          <img width="100%" src="../assets/images/logo.png" alt="logo">
        </a>
      </div>
      <div class="mdc-drawer__content">
        <div class="mdc-list-group">
          <nav class="mdc-list mdc-drawer-menu">
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="<?= BASE_URL.'teacher/' ?>">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Dashboard
              </a>
            </div>

            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="schedule.php">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">schedule</i>
                Schedule
              </a>
            </div>
          </nav>
        </div>
      </div>
    </aside>