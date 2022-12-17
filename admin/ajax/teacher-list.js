let TEACHER_TABLE;
function teacher_list() {
  let count = 0;
  TEACHER_TABLE = $("#table2").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1, 2, 3]),
    ajax: {
      url: "api/teacher-list.php",
      type: "POST",
      data: { submit: "teacher-list" },
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () => count=0,
    },
    columns: [
      {
        data: "id",
        render: () => ++count,
      },
      {
        data: "name",
        render: function (data, type) {
          return `<a href="#" class="pe-auto text-capitalize text-primary student-detail-btn teacher-redirect-btn" >${data}</a>`;
        },
      },
      {
        data: "email",
        render: function (data, type) {
          return `<a class="text-dark" href="mailto:${data}" >${data}</a>`;
        },
      },
      {
        data: "phone",
        render: function (data, type) {
          return `<a class="text-dark" href="tel:${data}" >${data}</a>`;
        },
      },
      {
        data: "action",
        render: function (data, type) {
          html = "";
          if(data == '1'){
            html += `<button class="mdc-button text-button--secondary mdc-ripple-upgraded teacher-deactivate-btn">
                        <i class="material-icons mdc-button__icon" >power_settings_new</i>Deactivate
                    </button>`;
          }else{
            html += `<button class="mdc-button text-button--success mdc-ripple-upgraded teacher-activate-btn">
                      <i class="material-icons mdc-button__icon" >power_settings_new</i>Activate
                  </button>`;
          }
          html += `<button class="mdc-button text-button--primary mdc-ripple-upgraded teacher-analysis-btn">
                      <i class="material-icons mdc-button__icon">pie_chart</i>Analysis
                  </button>`;
          return html;
        },
      },
    ],
  });
  TEACHER_TABLE.on("buttons-action", () => (count = 0));
}

$("#table2").on("click", "tr .teacher-deactivate-btn", function () {
  let data = TEACHER_TABLE.row($(this).parent().parent()).data();
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
        data: "key=" + data.id + "&submit=teacher-deactivate",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            teacher_list();
            swal("Deactivate!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});
$("#table2").on("click", "tr .teacher-activate-btn", function () {
let data = TEACHER_TABLE.row($(this).parent().parent()).data();
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
      data: "key=" + data.id + "&submit=teacher-activate",
      success: function (response) {
        response = JSON.parse(response);
        if (response.success) {
          teacher_list();
          swal("Activate!", response.message, "success");
        } else swal("Error!", response.message, "error");
      },
    });
  }
});
});

$("#table2").on("click", "tr .teacher-analysis-btn", function () {
  let data = TEACHER_TABLE.row($(this).parent().parent()).data();
  window.location.href = "teacher-analysis.php?teacher=" + data.email;
});
  
$("#table2").on("click", "tr .teacher-redirect-btn", function () {
    let data = TEACHER_TABLE.row($(this).parent().parent()).data();
    window.location.href = "teacher-single.php?teacher="+data.email;
});