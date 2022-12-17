
// create branch - start
let BRANCH_LIST_TABLE;
function branch_list_load() {
  let count = 0;
  BRANCH_LIST_TABLE = $("#branch-list-table").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1]),
    ajax: {
      url: "api/branch.php",
      type: "POST",
      data: { submit: "branch-list" },
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () => (count = 0),
    },
    columns: [
      {
        data: "id",
        render: () => ++count,
      },
      { data: "department" },
      { data: "course_type" },
      { data: "session_type" },
      {
        data: "action",
        render: function (data, type) {
          return `
                    <button class="mdc-button text-button--secondary mdc-ripple-upgraded branch-delete-btn">
                        <i class="material-icons mdc-button__icon">delete</i>Delete
                    </button>`;
        },
      },
    ],
  });
  BRANCH_LIST_TABLE.on("buttons-action", () => (count = 0));
}
function load_course_type_list() {
    $.ajax({
      url: "api/course_type.php",
      type: "POST",
      data: "submit=course-list",
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          for (let { id, short_name } of response.data) {
            let html = `<li class="mdc-list-item" data-value="${id}">${short_name}</li>`;
            $("#course-type-select-list").append(html);
          }
        }
      },
    });
}
  
function load_department_list() {
  $.ajax({
    url: "api/department.php",
    type: "POST",
    data: "submit=department-list",
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("#department-select-list .mdc-list-item").remove();
        for (let { id, name, short_name } of response.data) {
          let html = `<li class="mdc-list-item" data-value="${id}"><span class="text-dark font-weight-bold">(${short_name})</span> &nbsp;-&nbsp;${name}</li>`;
          $("#department-select-list").append(html);
        }
      }
    },
  });
}

$("#createBranch").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;

  $(this).find(":input[type=submit]").addClass("button--loading");
  $.ajax({
    url: "api/branch.php",
    type: "POST",
    data: $(this).serialize() + "&submit=branch-create",
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        resetForm($(this));
        swal("Success", response.message, "success");
        branch_list_load();
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
    complete: () =>
      $(this).find(":input[type=submit]").removeClass("button--loading"),
  });
});

$("#branch-list-table").on("click", ".branch-delete-btn", function () {
  let data = BRANCH_LIST_TABLE.row($(this).parent().parent()).data();
  swal({
    title: "Delete",
    text: "Do you want to delete this data!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/branch.php",
        type: "POST",
        data: "key=" + data.id + "&submit=branch-delete",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            branch_list_load();
            swal("Deleted!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});

  // create branch - end
  
  $("#branch-select-list").click(function () {
    let key = $(this).find(".mdc-list-item--selected").attr("data-value");
    load_session(key);
  });
  
  function load_session(key) {
    $.ajax({
      url: "api/branch.php",
      type: "POST",
      data: "submit=branch-session&branch-key=" + key,
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          $("#session-select-list .mdc-list-item").remove();
          for (let session of response.data) {
            let html = `<li class="mdc-list-item" data-value="${session}">${session}</li>`;
            $("#session-select-list").append(html);
          }
        }
      },
    });
  }
  