let BRANCH_TABLE;
function load_department() {
  let count = 0;
  BRANCH_TABLE = $("#table2").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1, 2, 3]),
    ajax: {
      url: "api/department.php",
      type: "POST",
      data: { submit: "department-list" },
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () => count=0,
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
                    <button class="mdc-button text-button--secondary mdc-ripple-upgraded department-delete-btn">
                        <i class="material-icons mdc-button__icon">delete</i>Delete
                    </button>
                    <button class="mdc-button  mdc-ripple-upgraded department-update-btn">
                        <i class="material-icons mdc-button__icon">create</i>Edit
                    </button>`;
        },
      },
    ],
  });
  BRANCH_TABLE.on("buttons-action", () => (count = 0));
}

$("#createDepartmentForm").on("submit", function(e){
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;

  let key = $("#updateDepartmentBtn").attr("department-key");
  let keyData= (isEmpty(key)) ? "" : "&key="+key;
  $(this).find(':input[type=submit]').addClass('button--loading');
  $.ajax({
    url: "api/department.php",
    type: "POST",
    data: $(this).serialize() + "&submit=" + $(this).attr("submit-type")+keyData,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        resetForm($(this));
        if(!isEmpty(key)){
          $("#createDepartmentForm").attr("submit-type", "department-create");
          $("#updateDepartmentBtn").parent().hide();
          $("#updateDepartmentBtn").attr("department-key", "");
          $("#addDepartmentBtn").parent().show();
        }
        load_department();
        swal("Success", response.message, "success");
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
    complete: () => $(this).find(':input[type=submit]').removeClass('button--loading'),
  });
});

$("#table2").on("click", "tr .department-update-btn", function () {
  let data = BRANCH_TABLE.row($(this).parent().parent()).data();
  $("#department-name").val(data.name);
  $("#department-shortname").val(data.short_name);
  $("#createDepartmentForm").attr("submit-type", "department-update");
  $("#updateDepartmentBtn").parent().show();
  $("#updateDepartmentBtn").attr("department-key", data.id);
  $("#addDepartmentBtn").parent().hide();
  windowScrollTo(".content-wrapper");
});

$("#table2").on("click", "tr .department-delete-btn", function () {
  let data = BRANCH_TABLE.row($(this).parent().parent()).data();
  swal({
    title: "Delete",
    text: "Do you want to delete this data!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "api/department.php",
        type: "POST",
        data: "key=" + data.id + "&submit=department-delete",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            load_department();
            swal("Deleted!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});

$("#create-department-file-form").on('submit', function(e){
  e.preventDefault();
  if ($(this).attr("valid") != "true") return;
  let formdata  = new FormData(this);
  formdata.append("submit", "department-excel-create");
  $.ajax({
    url: "api/department.php",
    type: "POST",
    data: formdata,
    processData: false,
    contentType: false,
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        resetForm($(this));
        load_department();
        swal("Created", response.message, "success");
        hideFileError($('.file-error'));
      } 
      else {
        if ("data" in response) {
          if('file_error' in response.data) showFileError($('.file-error'), response.data.file_error);
          else {
            hideFileError($('.file-error'));
            showValidError($(this), response.data);
          }
        }
        else {
          hideFileError($('.file-error'));
          swal("Error", response.message, "error");
        }
      }
    },
  });
});

$("#download-department-template").click(function(e){
  e.preventDefault();
  $.ajax({
    url: "api/department.php",
    type: "POST",
    data: {submit:'department-template-download'},
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
      } 
      else swal("Error!", response.message, "error");
    },
  });
});
