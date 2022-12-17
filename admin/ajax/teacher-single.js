$("#change-password-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let key = $("#teacher-key").attr("data-value");
  $.ajax({
    url: "api/teacher-single.php",
    type: "POST",
    data:
      $(this).serialize() + "&submit=teacher-change-password&teacher=" + key,
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
  let key = $("#teacher-key").attr("data-value");

  $(this).find(':input[type=submit]').addClass('button--loading');
  $.ajax({
    url: "api/teacher-single.php",
    type: "POST",
    data:
      $(this).serialize() + "&submit=teacher-change-personal&teacher=" + key,
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

$("#day-select").click(function () {
  let day = $(this).find(".mdc-list-item--selected").attr("data-value");
  load_schedule(day);
});

let SCHEDULE_TABLE = null;
function load_schedule(day) {
    let key = $("#teacher-key").attr("data-value");
  SCHEDULE_TABLE = $("#table2").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1, 2, 3, 4, 5]),
    ajax: {
      url: "api/teacher-single.php",
      type: "POST",
      data: { submit: "teacher-schedule-day", "teacher": key, day },
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () => {},
    },
    columns: [
      { data: "course" },
      { data: "department" },
      { data: "session" },
      { data: "subject" },
      { data: "start_time" },
      { data: "end_time" },
    ],
  });
}

$("#teacher-delete").on("click", function (e) {
  e.preventDefault();
  let key = $("#teacher-key").attr("data-value");
  swal({
    title: "Delete",
    text: "Do you want to delete this teacher!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/teacher-single.php",
        type: "POST",
        data: "key=" + key + "&submit=teacher-delete",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            history.back();
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});
$("#teacher-deactivate").on("click", function () {
  let key = $("#teacher-key").attr("data-value");
  swal({
    title: "Deactivate",
    text: "Do you want to deactivate this teacher!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/teacher-single.php",
        type: "POST",
        data: "key=" + key + "&submit=teacher-deactivate",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            window.location.reload();
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});
$("#teacher-activate").on("click", function () {
let key = $("#teacher-key").attr("data-value");
swal({
  title: "Activate",
  text: "Do you want to activate this teacher!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
}).then((willDelete) => {
  if (willDelete) {
    $.ajax({
      url: "api/teacher-single.php",
      type: "POST",
      data: "key=" + key + "&submit=teacher-activate",
      success: function (response) {
        response = JSON.parse(response);
        if (response.success) {
          window.location.reload();
        } else swal("Error!", response.message, "error");
      },
    });
  }
});
});