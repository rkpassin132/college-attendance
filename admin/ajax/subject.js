let SUBJECT_LIST_TABLE;
function subject_list_load() {
  let count = 0;
  SUBJECT_LIST_TABLE = $("#subject-list-table").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1]),
    ajax: {
      url: "api/subject.php",
      type: "POST",
      data: { submit: "subject-list" },
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
      {
        data: "action",
        render: function (data, type) {
          return `
                    <button class="mdc-button text-button--secondary mdc-ripple-upgraded subject-delete-btn">
                        <i class="material-icons mdc-button__icon">delete</i>Delete
                    </button>
                    <button class="mdc-button  mdc-ripple-upgraded subject-update-btn">
                        <i class="material-icons mdc-button__icon">create</i>Edit
                    </button>`;
        },
      },
    ],
  });
  SUBJECT_LIST_TABLE.on("buttons-action", () => (count = 0));
}

$("#create-subject-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let key = $("#update-subject-btn").attr("subject-key");

  $(this).find(":input[type=submit]").addClass("button--loading");
  $.ajax({
    url: "api/subject.php",
    type: "POST",
    data:
      $(this).serialize() +
      "&submit=" +
      $(this).attr("submit-type") +
      "&key=" +
      key,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        resetForm($(this));
        subject_list_load();
        if (!isEmpty(key)) $("#clear-update-subject-btn").click();
        swal("Success", response.message, "success");
        subject_list_load();
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
    complete: () =>
      $(this).find(":input[type=submit]").removeClass("button--loading"),
  });
});

$("#subject-list-table").on("click", "tr .subject-delete-btn", function () {
  let data = SUBJECT_LIST_TABLE.row($(this).parent().parent()).data();
  swal({
    title: `Delete subject`,
    text: `Do you want to delete this subject.\n Deleted subject not longer in any branch of course.`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/subject.php",
        type: "POST",
        data: "key=" + data.id + "&submit=subject-delete",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            subject_list_load();
            swal("Deleted!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});

$("#clear-update-subject-btn").click(function (e) {
  e.preventDefault();
  $("#create-subject-form").attr("submit-type", "subject-create");
  $("#update-subject-btn").parent().hide();
  $("#clear-update-subject-btn").parent().hide();
  $("#update-subject-btn").attr("subject-key", "");
  $("#create-subject-btn").parent().show();
});

$("#subject-list-table").on("click", "tr .subject-update-btn", function () {
  let data = SUBJECT_LIST_TABLE.row($(this).parent().parent()).data();
  $("#create-subject-form input[name='subject-name']").val(data.name);
  $("#create-subject-form").attr("submit-type", "subject-update");
  $("#update-subject-btn").parent().show();
  $("#clear-update-subject-btn").parent().show();
  $("#update-subject-btn").attr("subject-key", data.id);
  $("#create-subject-btn").parent().hide();
  $("html, body").animate(
    {
      scrollTop: $(".mdc-layout-grid__inner").offset().top,
    },
    500
  );
});

  
$("#create-subject-file-form").on("submit", function (e) {
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let formdata = new FormData(this);
  formdata.append("submit", "subject-excel-create");
  $.ajax({
    url: "api/subject.php",
    type: "POST",
    data: formdata,
    processData: false,
    contentType: false,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        subject_list_load();
        resetForm($(this));
        swal("Created", response.message, "success");
        hideFileError($(".file-error"));
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