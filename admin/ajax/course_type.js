let COURSE_TYPE_TABLE;
function load_course_type() {
  let count = 0;
  COURSE_TYPE_TABLE = $("#table3").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1]),
    ajax: {
      url: "api/course_type.php",
      type: "POST",
      data: { submit: "course-list" },
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () => (count = 0),
    },
    columns: [
      {
        data: "id",
        render: function (data, type) {
          return ++count;
        },
      },
      { data: "name" },
      { data: "short_name" },
      {
        data: "action",
        render: function (data, type) {
          return `
                    <button class="mdc-button text-button--secondary mdc-ripple-upgraded course-delete-btn">
                        <i class="material-icons mdc-button__icon">delete</i>Delete
                    </button>
                    <button class="mdc-button  mdc-ripple-upgraded course-update-btn">
                        <i class="material-icons mdc-button__icon">create</i>Edit
                    </button>`;
        },
      },
    ],
  });
  COURSE_TYPE_TABLE.on("buttons-action", () => (count = 0));
}

$("#createCourseTypeForm").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let key = $("#updateCourseTypeBtn").attr("course-key");
  let keyData = "";
  if (key.length > 0) keyData = "&submit=course-update&key=" + key;
  else keyData = "&submit=course-create";

  $(this).find(":input[type=submit]").addClass("button--loading");
  $.ajax({
    url: "api/course_type.php",
    type: "POST",
    data: $(this).serialize() + keyData,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        load_course_type();
        resetForm($(this));
        swal("Success", response.message, "success");
        if (key.length > 0) {
          $("#updateCourseTypeBtn").parent().hide();
          $("#updateCourseTypeBtn").attr("course-key", "");
          $("#addCourseTypeBtn").parent().show();
        }
      } else swal("Error", response.message, "error");
    },
    complete: () =>
      $(this).find(":input[type=submit]").removeClass("button--loading"),
  });
});

$("#table3").on("click", ".course-update-btn", function () {
  let data = COURSE_TYPE_TABLE.row($(this).parent().parent()).data();
  $("#createCourseTypeForm input[name='name']").val(data.name);
  $("#createCourseTypeForm input[name='short_name']").val(data.short_name);
  $("#updateCourseTypeBtn").parent().show();
  $("#updateCourseTypeBtn").attr("course-key", data.id);
  $("#addCourseTypeBtn").parent().hide();
  windowScrollTo(".content-wrapper");
});

$("#table3").on("click", ".course-delete-btn", function () {
  let data = COURSE_TYPE_TABLE.row($(this).parent().parent()).data();
  swal({
    title: "Delete",
    text: "Do you want to delete this data!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/course_type.php",
        type: "POST",
        data: "key=" + data.id + "&submit=course-delete",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            load_course_type();
            swal("Deleted!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});
