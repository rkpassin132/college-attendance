$(document).ready(function () {
    load_branch();
});
let BRANCH_TABLE;
function load_branch() {
    let count = 0;
    BRANCH_TABLE = $('#table2').DataTable({
        dom: "Blfrtip",
        responsive: true,
        bDestroy: true,
        processing: true,
        lengthChange: true,
        buttons: get_tableButton([0, 1, 2]),
        ajax: {
            url: "api/branch.php",
            type: 'POST',
            data: { submit: "branch-list" },
            dataSrc: (res) => ("data" in res ? res.data : []),
            complete: () => count=0,
        },
        'columns': [
            {
                'data': 'id',
                render: () => ++count,
            },
            { 'data': 'name' },
            { 'data': 'short_name' },
            { 'data': 'course_name' },
            { 'data': 'course_short_name' },
        ]
    });
    BRANCH_TABLE.on("buttons-action", () => (count = 0));
}