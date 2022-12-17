$("#change-password-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;

  $.ajax({
    url: "api/profile.php",
    type: "POST",
    data: $(this).serialize() + "&submit=profile-change-password",
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
  });
});

$("#change-personal-info-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;

  $(this).find(':input[type=submit]').addClass('button--loading');
  $.ajax({
    url: "api/profile.php",
    type: "POST",
    data: $(this).serialize() + "&submit=profile-change-personal",
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        swal("Success", response.message, "success");
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
    complete: () => $(this).find(':input[type=submit]').removeClass('button--loading')
  });
});
