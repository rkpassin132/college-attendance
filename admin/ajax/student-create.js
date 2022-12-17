$("#create-user-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;

  $(this).find(':input[type=submit]').addClass('button--loading');
  $.ajax({
    url: "api/student-create.php",
    type: "POST",
    data: $(this).serialize() + "&submit=student-create",
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $(this).get(0).reset();
        swal("Success", response.message, "success");
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
    complete: () => $(this).find(':input[type=submit]').removeClass('button--loading')
  });
});

$("#create-student-file-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let formdata = new FormData(this);
  formdata.append("submit", "student-excel-create");
  $.ajax({
    url: "api/student-create.php",
    type: "POST",
    data: formdata,
    processData: false,
    contentType: false,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        hideFileError($(".file-error"));
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