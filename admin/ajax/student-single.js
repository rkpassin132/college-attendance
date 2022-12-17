$(document).ready(function(){
  let key = $("#student-key").attr("data-value");
  $.ajax({
    url: "api/student-single.php",
    type: "POST",
    data:"submit=student-class&student=" + key,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        let data = response.data;
        $("#student-class").text(`( ${data.course} ) ${data.branch} - ${data.session} ${data.session_type}`);
      }
    },
  });
});
$("#change-password-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let key = $("#student-key").attr("data-value");

  $(this).find(':input[type=submit]').addClass('button--loading');
  $.ajax({
    url: "api/student-single.php",
    type: "POST",
    data:
      $(this).serialize() + "&submit=student-change-password&student=" + key,
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

$("#change-personal-info-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let key = $("#student-key").attr("data-value");

  $(this).find(':input[type=submit]').addClass('button--loading');
  $.ajax({
    url: "api/student-single.php",
    type: "POST",
    data:
      $(this).serialize() + "&submit=student-change-personal&student=" + key,
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
    let key = $("#student-key").attr("data-value");
  SCHEDULE_TABLE = $("#table2").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1, 2, 3]),
    ajax: {
      url: "api/student-single.php",
      type: "POST",
      data: { submit: "student-schedule-day", "student": key, day },
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () => {},
    },
    columns: [
      { data: "teacher" },
      { data: "subject" },
      { data: "start_time" },
      { data: "end_time" },
    ],
  });
}

$("#student-delete").on("click", function (e) {
  e.preventDefault();
  let key = $("#student-key").attr("data-value");
  swal({
    title: "Delete",
    text: "Do you want to delete this student!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/student-single.php",
        type: "POST",
        data: "key=" + key + "&submit=student-delete",
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
$("#student-deactivate").on("click", function () {
  let key = $("#student-key").attr("data-value");
  swal({
    title: "Deactivate",
    text: "Do you want to deactivate this student!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/student-single.php",
        type: "POST",
        data: "key=" + key + "&submit=student-deactivate",
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
$("#student-activate").on("click", function () {
let key = $("#student-key").attr("data-value");
swal({
  title: "Activate",
  text: "Do you want to activate this student!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
}).then((willDelete) => {
  if (willDelete) {
    $.ajax({
      url: "api/student-single.php",
      type: "POST",
      data: "key=" + key + "&submit=student-activate",
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