let ATTENDANCE_TYPE_TABLE;
function load_attendance_type() {
  let count = 0;
  ATTENDANCE_TYPE_TABLE = $("#table2").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1]),
    ajax: {
      url: "api/attendance_type.php",
      type: "POST",
      data: { submit: "attendance-list" },
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () => count=0,
    },
    columns: [
      {
        data: "id",
        render: () => ++count,
      },
      { data: "name" },
      {
        data: "action",
        render: function (data, type) {
          return `
              <button class="mdc-button text-button--secondary mdc-ripple-upgraded attendance-delete-btn">
                  <i class="material-icons mdc-button__icon">delete</i>Delete
              </button>
              <button class="mdc-button  mdc-ripple-upgraded attendance-update-btn">
                  <i class="material-icons mdc-button__icon">create</i>Edit
              </button>`;
        },
      },
    ],
  });
  ATTENDANCE_TYPE_TABLE.on("buttons-action", () => (count = 0));
}

$("#createAttendanceTypeForm").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let key = $("#updateAttendanceTypeBtn").attr("attendance-key");
  let keydata = "";
  if (!isEmpty(key)) keydata = "&key=" + key;

  $(this).find(':input[type=submit]').addClass('button--loading');
  $.ajax({
    url: "api/attendance_type.php",
    type: "POST",
    data:
      $(this).serialize() + "&submit=" + $(this).attr("submit-type") + keydata,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        resetForm($(this));
        if (!isEmpty(key)) {
          $("#attendance-type").val("");
          $("#createAttendanceTypeForm").attr(
            "submit-type",
            "attendance-create"
          );
          $("#updateAttendanceTypeBtn").parent().hide();
          $("#updateAttendanceTypeBtn").attr("attendance-key", "");
          $("#addAttendanceTypeBtn").parent().show();
        }
        load_attendance_type();
        swal("Success", response.message, "success");
      } else swal("Error", response.message, "error");
    },
    complete: () => $(this).find(':input[type=submit]').removeClass('button--loading')
  });
});

$("#table2").on("click", ".attendance-update-btn", function () {
  let data = ATTENDANCE_TYPE_TABLE.row($(this).parent().parent()).data();
  $("#createAttendanceTypeForm input[name='attendance-name']").val(data.name);
  $("#createAttendanceTypeForm").attr("submit-type", "attendance-update");
  $("#updateAttendanceTypeBtn").parent().show();
  $("#updateAttendanceTypeBtn").attr("attendance-key", data.id);
  $("#addAttendanceTypeBtn").parent().hide();
});

$("#table2").on("click", ".attendance-delete-btn", function () {
  let data = ATTENDANCE_TYPE_TABLE.row($(this).parent().parent()).data();
  swal({
    title: "Delete",
    text: "Do you want to delete this attendance!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/attendance_type.php",
        type: "POST",
        data: "key=" + data.id + "&submit=attendance-delete",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            load_attendance_type();
            swal("Deleted!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});
