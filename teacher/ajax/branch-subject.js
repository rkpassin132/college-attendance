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
        for (let {session} of response.data) {
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
