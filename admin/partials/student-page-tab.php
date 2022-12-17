<div class="mt-4 page-tab">
    <a href="student-single.php?student=<?= $user['email'] ?>" class="mdc-button mdc-ripple-upgraded text-button--dark <?= ($active_tab=='profile') ? 'active' : '' ?>">
        <i class="material-icons mdc-button__icon">account_circle</i>
        Profile
    </a>
    <a href="student-analysis.php?student=<?= $user['email'] ?>" class="mdc-button mdc-ripple-upgraded text-button--dark <?= ($active_tab=='analysis') ? 'active' : '' ?>">
        <i class="material-icons mdc-button__icon">pie_chart</i>
        Analysis
    </a>
</div>
<hr class="mt-0 mb-4" />