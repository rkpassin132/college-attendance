function load_department() {
  let data = { submit: "department-list-active" };
  $.ajax({
    url: "api/branch_subject.php",
    traditional: true,
    type: "POST",
    data,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("ul.department-list-active").each(function () {
          $(this).children(".mdc-list-item").remove();
        });
        for (let { id, name } of response.data) {
          let html = `<li class="mdc-list-item" data-value="${id}">${name}</li>`;
          $("ul.department-list-active").each(function () {
            $(this).append(html);
          });
        }
      }
    },
  });
}
function load_department_course(formtarget, department) {
  let data = { submit: "department-course-type-list-active", department };
  $.ajax({
    url: "api/branch_subject.php",
    traditional: true,
    type: "POST",
    data,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("ul.course-type-list-active[form-target='"+formtarget+"'] .mdc-list-item").remove();
        for (let { id, name } of response.data) {
          let html = `<li class="mdc-list-item" data-value="${id}">${name}</li>`;
          $("ul.course-type-list-active[form-target='"+formtarget+"']").append(html);
        }
      }
    },
  });
}

function load_department_course_session(formtarget, department, course) {
  let data = {
    submit: "department-course-session-list-active",
    department,
    course,
  };
  $.ajax({
    url: "api/branch_subject.php",
    type: "POST",
    traditional: true,
    data,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("ul.session-list-active[form-target='"+formtarget+"'] .mdc-list-item").remove();
        for (let session of response.data) {
          let html = `<li class="mdc-list-item" data-value="${session}">${session}</li>`;
          $("ul.session-list-active[form-target='"+formtarget+"']").append(html);
        }
      }
    },
  });
}

function load_department_course_session_subject(formtarget, department, course, session) {
  let data = {
    submit: "department-course-session-subject-list-active",
    department,
    course,
    session,
  };
  $.ajax({
    url: "api/branch_subject.php",
    type: "POST",
    traditional: true,
    data,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("ul.subject-list-active[form-target='"+formtarget+"'] .mdc-list-item").remove();
        for (let { id, name } of response.data) {
          let html = `<li class="mdc-list-item" data-value="${id}">${name}</li>`;
          $("ul.subject-list-active[form-target='"+formtarget+"']").append(html);
        }
      }
    },
  });
}

$("ul.department-list-active").click(function () {
  let formtarget = $(this).attr("form-target");
  console.log($("#"+formtarget+" div.course-type-list-active"));
  if($("#"+formtarget+" div.course-type-list-active").length == 0) return false;
  let department = $(this).find(".mdc-list-item--selected").attr("data-value");
  load_department_course(formtarget, department);
  resetSelectInput($("#"+formtarget+" div.course-type-list-active"));
  resetSelectInput($("#"+formtarget+" div.session-list-active"));
  resetSelectInput($("#"+formtarget+" div.subject-list-active"));
});

$("ul.course-type-list-active").click(function () {
  let formtarget = $(this).attr("form-target");
  if($("#"+formtarget+" div.session-list-active").length == 0) return false;
  let course = $(this).find(".mdc-list-item--selected").attr("data-value");
  let department = $("ul.department-list-active[form-target='"+formtarget+"']").find(".mdc-list-item--selected").attr("data-value");
  load_department_course_session(formtarget, department, course);
  resetSelectInput($("#"+formtarget+" div.session-list-active"));
  resetSelectInput($("#"+formtarget+" div.subject-list-active"));
});

$("ul.session-list-active").click(function () {
  let formtarget = $(this).attr("form-target");
  if($("#"+formtarget+" div.subject-list-active").length == 0) return false;
  let session = $(this).find(".mdc-list-item--selected").attr("data-value");
  let department = $("ul.department-list-active[form-target='"+formtarget+"']").find(".mdc-list-item--selected").attr("data-value");
  let course = $("ul.course-type-list-active[form-target='"+formtarget+"']").find(".mdc-list-item--selected").attr("data-value");
  load_department_course_session_subject(formtarget, department, course, session);
  resetSelectInput($("#"+formtarget+" div.subject-list-active"));
});

$("#clear-search-filter").click(function (e) {
  e.preventDefault();
  resetForm($(this).closest("form"));
});

function load_subject(){
  $.ajax({
    url: "api/subject.php",
    type: "POST",
    traditional: true,
    data:{submit:'subject-list'},
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        let data = [];
        for (let { id, name } of response.data) data.push({ id, text: name });
        $("#subject-select-list").html("").select2({
          placeholder: "Select subjects",
          theme: "material",
          multiple: true,
          closeOnSelect: false,
          data,
        });
      }
    },
  });
}

$("#addBranchSubject").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;

  $(this).find(":input[type=submit]").addClass("button--loading");
  $.ajax({
    url: "api/branch_subject.php",
    type: "POST",
    data: $(this).serialize() + "&submit=create-branch-subject",
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        resetForm($(this));
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

let SEARCH_SUBJECT;
function search_subject(form) {
  let data = {};
  let arr = form.serializeArray();
  for (let key in arr) data[arr[key].name] = arr[key].value;
  data["submit"] = "subject-search";

  form.find(":input[type=submit]").addClass("button--loading");
  SEARCH_SUBJECT = $("#table2").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1, 2, 3]),
    ajax: {
      url: "api/branch_subject.php",
      type: "POST",
      data: data,
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () =>
        form.find(":input[type=submit]").removeClass("button--loading"),
    },
    columns: [
      { data: "department_name" },
      { data: "course_name" },
      { data: "session" },
      { data: "name" },
      {
        data: "action",
        render: function (data, type) {
          return `
                    <button class="mdc-button text-button--secondary mdc-ripple-upgraded subject-connection-delete-btn">
                        <i class="material-icons mdc-button__icon">delete</i>Delete
                    </button>`;
        },
      },
    ],
  });
}

$("#searchBranchSubject").on("submit", function (e) {
  e.preventDefault();
  search_subject($(this));
});

$("#clear-search-filter").click(function (e) {
  e.preventDefault();
  resetForm($(this).closest("form"));
});

$("#table2").on("click", "tr .subject-connection-delete-btn", function () {
  let data = SEARCH_SUBJECT.row($(this).parent().parent()).data();
  let session_type = data.session_type == 0 ? "semester" : "year";
  let session = "";
  if (data.session == 1) session = data.session + "st";
  if (data.session == 2) session = data.session + "nd";
  if (data.session == 3) session = data.session + "rd";
  if (data.session > 3) session = data.session + "th";
  swal({
    title: `Delete ${data.department_short_name} ${session} ${session_type} subject`,
    text: `Do you want to delete ${data.name} subject of ${session} ${session_type} of (${data.department_short_name}) - ${data.department_name} branch of ${data.course_name} course`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/branch_subject.php",
        type: "POST",
        data: "key=" + data.id + "&submit=subject-branch-delete",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            $("#searchBranchSubject").submit();
            swal("Deleted!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});
