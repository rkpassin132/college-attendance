$("#create-user-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;

  $(this).find(':input[type=submit]').addClass('button--loading');
  $.ajax({
    url: "api/teacher-create.php",
    type: "POST",
    data: $(this).serialize() + "&submit=teacher-create",
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        resetForm($(this));
        swal("Success", response.message, "success");
        // load_teacher_select();
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
    complete: () => $(this).find(':input[type=submit]').removeClass('button--loading')
  });
});

$("#create-teacher-file-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let formdata = new FormData(this);
  formdata.append("submit", "teacher-excel-create");
  $.ajax({
    url: "api/teacher-create.php",
    type: "POST",
    data: formdata,
    processData: false,
    contentType: false,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        hideFileError($(".file-error"));
        teacher_list();
        resetForm($(this));
        swal("Created", response.message, "success");
      } else {
        if ("data" in response) {
          if ("file_error" in response.data)
            showFileError($(".file-error"), response.data.file_error);
          else {
            hideFileError($(".file-error"));
            showValidError($(this), response.data);
          }
        } else {
          hideFileError($(".file-error"));
          swal("Error", response.message, "error");
        }
      }
    },
  });
});
