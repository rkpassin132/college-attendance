function load_teacher_select() {
    $.ajax({
      url: "api/teacher-shedule.php",
      type: "POST",
      data: "submit=teacher-list-select",
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          $("#teacher-select-list .mdc-list-item").remove();
          for (let teacher of response.data) {
            let html = `<li class="mdc-list-item" data-value="${teacher.id}">${teacher.name} - (<span class="text-dark font-weight-bold">${teacher.email}</span>)</li>`;
            $("#teacher-select-list").append(html);
          }
        }
      },
    });
}

$("#addTeacherShedule").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;

  $(this).find(":input[type=submit]").addClass("button--loading");
  $.ajax({
    url: "api/teacher-shedule.php",
    type: "POST",
    data: $(this).serialize() + "&submit=teacher-add-shedule",
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        // resetForm($(this));
        swal("Success", response.message, "success");
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
    complete: () =>
      $(this).find(":input[type=submit]").removeClass("button--loading"),
  });
});

// search teacher shedule/classes

function load_course_type_search() {
  let data = { submit: "course-type-list" };
  $.ajax({
    url: "api/branch_subject.php",
    traditional: true,
    type: "POST",
    data,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("#course-type-select-search .mdc-list-item").remove();
        for (let { id, name } of response.data) {
          let html = `<li class="mdc-list-item" data-value="${id}">${name}</li>`;
          $("#course-type-select-search").append(html);
        }
      }
    },
  });
}

function load_course_branch_search(course) {
  let data = {
    submit: "course-branch-list",
    "course-key": course,
  };
  $.ajax({
    url: "api/branch_subject.php",
    type: "POST",
    traditional: true,
    data,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("#branch-select-search .mdc-list-item").remove();
        for (let { id, name, short_name } of response.data) {
          let html = `<li class="mdc-list-item" data-value="${id}"><span class="text-dark font-weight-bold">(${short_name})</span> &nbsp;-&nbsp;${name}</li>`;
          $("#branch-select-search").append(html);
        }
      }
    },
  });
}

function load_course_branch_session_search(course, branch) {
  let data = {
    submit: "course-branch-session-list",
    "course-key": course,
    "branch-key": branch,
  };
  $.ajax({
    url: "api/branch_subject.php",
    type: "POST",
    traditional: true,
    data,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("#session-select-search .mdc-list-item").remove();
        for (let { session } of response.data) {
          let html = `<li class="mdc-list-item" data-value="${session}">${session}</li>`;
          $("#session-select-search").append(html);
        }
      }
    },
  });
}
$("#course-type-select-search").click(function () {
  let course = $(this).find(".mdc-list-item--selected").attr("data-value");
  load_course_branch_search(course);
  resetSelectInput($(".branch-select-search"));
  resetSelectInput($(".session-select-search"));
});

$("#branch-select-search").click(function () {
  let course = $("#course-type-select-search")
    .find(".mdc-list-item--selected")
    .attr("data-value");
  let branch = $(this).find(".mdc-list-item--selected").attr("data-value");
  load_course_branch_session_search(course, branch);
  resetSelectInput($(".session-select-search"));
});

$("#clear-search-filter").click(function (e) {
  e.preventDefault();
  resetForm($(this).closest("form"));
});

let TEACHER_CLASS;
$("#searchTeacherClasses").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let data = {};
  let arr = $(this).serializeArray();
  for (let key in arr) data[arr[key].name] = arr[key].value;
  data["submit"] = "teacher-search-shedule";

  $(this).find(":input[type=submit]").addClass("button--loading");
  TEACHER_CLASS = $("#table2").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1, 2, 3, 4]),
    ajax: {
      url: "api/teacher-shedule.php",
      type: "POST",
      data: data,
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () =>
        $(this).find(":input[type=submit]").removeClass("button--loading"),
    },
    columns: [
      {
        data: "days",
        render: (data, type) => `<span class="text-capitalize">${data}</span>`,
      },
      { data: "start_time" },
      { data: "end_time" },
      {
        data: "teacher",
        render: (data, type) => `<span class="text-capitalize">${data}</span>`,
      },
      {
        data: "subject",
        render: (data, type) => `<span class="text-capitalize">${data}</span>`,
      },
      {
        data: "action",
        render: function (data, type) {
          return `
              <button class="mdc-button text-button--secondary mdc-ripple-upgraded teacher-class-delete-btn">
                <i class="material-icons mdc-button__icon">delete</i>Delete
              </button>`;
        },
      },
    ],
  });
});

$("#table2").on("click", "tr .teacher-class-delete-btn", function () {
  let data = TEACHER_CLASS.row($(this).parent().parent()).data();
  swal({
    title: "Delete",
    text: "Do you want to delete this teacher class!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/teacher-shedule.php",
        type: "POST",
        data: "key=" + data.id + "&submit=teacher-class-delete",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            $("#searchTeacherClasses").submit();
            swal("Deleted!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});
