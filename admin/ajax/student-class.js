function load_student_select() {
    $.ajax({
      url: "api/student-class.php",
      type: "POST",
      data: "submit=student-list-add-class",
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
            let data = [];
            for (let { id, name, roll_no } of response.data) data.push({ id, text: `${roll_no} (${name})` });
            $("#student-select-list").html("").select2({
              placeholder: "Select student",
              theme: "material",
              multiple: true,
              closeOnSelect: false,
              data,
            });
        }
      },
    });
}

$("ul.session-list-active[form-target='promoteStudentClass']").click(function () {
    let formtarget = $(this).attr("form-target");
    let session = $(this).find(".mdc-list-item--selected").attr("data-value");
    let department = $("ul.department-list-active[form-target='"+formtarget+"']").find(".mdc-list-item--selected").attr("data-value");
    let course = $("ul.course-type-list-active[form-target='"+formtarget+"']").find(".mdc-list-item--selected").attr("data-value");
    let data ={
      "course-type" : course,
      "department":department,
      "session":session,
      "submit":"student-class-list"
    }
    $.ajax({
      url: "api/student-class.php",
      type: "POST",
      data,
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          $("#student-select-promote .mdc-list-item").remove();
          for (let student of response.data) {
            let html = `<li class="mdc-list-item" data-value="${student.id}">${student.roll_no} - (<span class="text-dark font-weight-bold">${student.name}</span>)</li>`;
            $("#student-select-promote").append(html);
          }
        } else {
          if ("data" in response) showValidError($(this), response.data);
          else swal("Error", response.message, "error");
        }
      },
    });
});

$("#addStudentClass").on("submit", function (e) {
    e.preventDefault();
    if ($(this).attr("valid") != "true") return;
  
    $(this).find(':input[type=submit]').addClass('button--loading');
    $.ajax({
      url: "api/student-class.php",
      type: "POST",
      data: $(this).serialize() + "&submit=student-add-class",
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          swal("Success", response.message, "success");
          load_student_promote();
        } else {
          if ("data" in response) showValidError($(this), response.data);
          else swal("Error", response.message, "error");
        }
      },
      complete: () => $(this).find(':input[type=submit]').removeClass('button--loading')
    });
});
  
$("#addStudentClassExcel").on("submit", function (e) {
    e.preventDefault();
    if ($(this).attr("valid") != "true") return;
    let formdata = new FormData(this);
    formdata.append("submit", "student-excel-add-class");
    $.ajax({
      url: "api/student-class.php",
      type: "POST",
      data: formdata,
      processData: false,
      contentType: false,
      success: (response) => {
        response = JSON.parse(response);
        console.log($(this).find(".file-error"));
        if (response.success) {
          hideFileError($(this).find(".file-error"));
          load_student_select();
          resetForm($(this));
          swal("Created", response.message, "success");
        } else {
          if ("data" in response) {
            if ("file_error" in response.data)
              showFileError($(this).find(".file-error"), response.data.file_error);
            else {
              hideFileError($(this).find(".file-error"));
              showValidError($(this), response.data);
            }
          } else {
            hideFileError($(this).find(".file-error"));
            swal("Error", response.message, "error");
          }
        }
      },
    });
});
  
$("#promoteStudentClass").on("submit", function (e) {
    e.preventDefault();
    if ($(this).attr("valid") != "true") return;
  
    $(this).find(':input[type=submit]').addClass('button--loading');
    $.ajax({
      url: "api/student-class.php",
      type: "POST",
      data: $(this).serialize() + "&submit=student-promote-class",
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
      complete: () => $(this).find(':input[type=submit]').removeClass('button--loading')
    });
});
  
$("#promoteClass").on("submit", function (e) {
    e.preventDefault();
    if ($(this).attr("valid") != "true") return;
  
    $(this).find(':input[type=submit]').addClass('button--loading');
    $.ajax({
      url: "api/student-class.php",
      type: "POST",
      data: $(this).serialize() + "&submit=promote-class-student",
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
      complete: () => $(this).find(':input[type=submit]').removeClass('button--loading')
    });
});
  