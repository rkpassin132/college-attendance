<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open">
  <div class="mdc-drawer__header">
    <a href="<?= BASE_URL . 'admin/' ?>" class="brand-logo">
      <img width="100%" src="../assets/images/logo.png" alt="logo">
    </a>
  </div>
  <div class="mdc-drawer__content">
    <div class="mdc-list-group">
      <nav class="mdc-list mdc-drawer-menu">
        <div class="mdc-list-item mdc-drawer-item">
          <a class="mdc-drawer-link" href="<?= BASE_URL . 'admin/' ?>">
            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
            Dashboard
          </a>
        </div>

        <div class="mdc-list-item mdc-drawer-item">
          <a class="mdc-drawer-link" href="department.php">
            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
            Department
          </a>
        </div>

        <div class="mdc-list-item mdc-drawer-item">
          <a class="mdc-drawer-link" href="course_type.php">
            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">golf_course</i>
            Course Type
          </a>
        </div>

        <div class="mdc-list-item mdc-drawer-item">
          <a class="mdc-drawer-link" href="subject.php">
            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">subject</i>
            Subject
          </a>
        </div>

        <div class="mdc-list-item mdc-drawer-item">
          <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="branch-sub-menu">
            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">library_add</i>
            Branch
            <i class="mdc-drawer-arrow material-icons">chevron_right</i>
          </a>
          <div class="mdc-expansion-panel" id="branch-sub-menu">
            <nav class="mdc-list mdc-drawer-submenu">
              <div class="mdc-list-item mdc-drawer-item">
                <a class="mdc-drawer-link" href="branch.php">
                  Create
                </a>
              </div>
              <div class="mdc-list-item mdc-drawer-item">
                <a class="mdc-drawer-link" href="branch-subjects.php">
                  Add subject
                </a>
              </div>
            </nav>
          </div>
        </div>

        <div class="mdc-list-item mdc-drawer-item">
          <a class="mdc-drawer-link" href="attendance_type.php">
            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">assignment_turned_in</i>
            Attendance Type
          </a>
        </div>

        <div class="mdc-list-item mdc-drawer-item">
          <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="teacher-sub-menu">
            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">assignment_ind</i>
            Teacher
            <i class="mdc-drawer-arrow material-icons">chevron_right</i>
          </a>
          <div class="mdc-expansion-panel" id="teacher-sub-menu">
            <nav class="mdc-list mdc-drawer-submenu">
              <div class="mdc-list-item mdc-drawer-item">
                <a class="mdc-drawer-link" href="teacher-create.php">
                  Create
                </a>
              </div>
              <div class="mdc-list-item mdc-drawer-item">
                <a class="mdc-drawer-link" href="teacher-shedule.php">
                  Shedule
                </a>
              </div>
              <div class="mdc-list-item mdc-drawer-item">
                <a class="mdc-drawer-link" href="teacher-list.php">
                  List
                </a>
              </div>
            </nav>
          </div>
        </div>

        <div class="mdc-list-item mdc-drawer-item">
          <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="student-sub-menu">
            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">account_circle</i>
            Student
            <i class="mdc-drawer-arrow material-icons">chevron_right</i>
          </a>
          <div class="mdc-expansion-panel" id="student-sub-menu">
            <nav class="mdc-list mdc-drawer-submenu">
              <div class="mdc-list-item mdc-drawer-item">
                <a class="mdc-drawer-link" href="student-create.php">
                  Create
                </a>
              </div>
              <div class="mdc-list-item mdc-drawer-item">
                <a class="mdc-drawer-link" href="student-class.php">
                  Class
                </a>
              </div>
              <div class="mdc-list-item mdc-drawer-item">
                <a class="mdc-drawer-link" href="student-list.php">
                  List
                </a>
              </div>
            </nav>
          </div>
        </div>
      </nav>
    </div>
  </div>
</aside>