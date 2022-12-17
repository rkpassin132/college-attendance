$("#login-form").on("submit", function (e) {
    e.preventDefault();
    if ($(this).attr("valid") != "true") return;
  
    $(this).find(':input[type=submit]').addClass('button--loading');
    $.ajax({
      type: "POST",
      url: "api/login.php",
      data: $(this).serialize() + "&submit=true",
      cache: false,
      success: (response) => {
        response = JSON.parse(response);
        if (response.success === true) {
          $(location).prop("href", response['redirect']);
        } else {
          if ("data" in response) showValidError($(this), response.data);
          else swal("Error", response.message, "error");
        }
      },
      complete: () => $(this).find(':input[type=submit]').removeClass('button--loading'),
      error: () => $(this).find(':input[type=submit]').removeClass('button--loading'),
    });
  });
  