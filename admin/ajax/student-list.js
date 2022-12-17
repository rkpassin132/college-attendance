let STUDENT_TABLE;
function search_student(form) {
  let data = {};
  let count = 0;
  let arr = form.serializeArray();
  for (let key in arr) data[arr[key].name] = arr[key].value;
  data["submit"] = "student-search";

  form.find(':input[type=submit]').addClass('button--loading');
  STUDENT_TABLE = $("#student-list").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1, 2, 3]),
    ajax: {
      url: "api/student-list.php",
      type: "POST",
      data: data,
      dataSrc: (res) => ("data" in res ? res.data : []),
      complete: () => {
        form.find(':input[type=submit]').removeClass('button--loading');
        count=0;
      },
    },
    columns: [
      {
        data: "id",
        render: () => ++count,
      },
      {
        data: "name",
        render: function (data, type) {
          return `<a href="#" class="pe-auto text-capitalize text-primary student-single-btn" >${data}</a>`;
        },
      },
      {
        data: "email",
        render: function (data, type) {
          return `<a class="text-dark" href="mailto:${data}" >${data}</a>`;
        },
      },
      {
        data: "roll_no",
        render: function (data, type) {
          return `<a class="text-dark" href="tel:${data}" >${data}</a>`;
        },
      },
      {
        data: "action",
        render: function (data, type) {
          html = "";
          if(data == '1'){
            html += `<button class="mdc-button text-button--secondary mdc-ripple-upgraded student-deactivate-btn">
                        <i class="material-icons mdc-button__icon" >power_settings_new</i>Deactivate
                    </button>`;
          }else{
            html += `<button class="mdc-button text-button--success mdc-ripple-upgraded student-activate-btn">
                      <i class="material-icons mdc-button__icon" >power_settings_new</i>Activate
                  </button>`;
          }
          html += `<button class="mdc-button text-button--primary mdc-ripple-upgraded student-analysis-btn">
                      <i class="material-icons mdc-button__icon">pie_chart</i>Analysis
                  </button>`;
          return html;
        },
      },
    ],
  });
  STUDENT_TABLE.on("buttons-action", () => (count = 0));
}

$("#searchStudent").on("submit", function (e) {
  e.preventDefault();
  search_student($(this));
});

$("#student-list").on("click", "tr .student-deactivate-btn", function () {
    let data = STUDENT_TABLE.row($(this).parent().parent()).data();
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
          data: "key=" + data.id + "&submit=student-deactivate",
          success: function (response) {
            response = JSON.parse(response);
            if (response.success) {
              search_student($("#searchStudent"));
              swal("Deactivate!", response.message, "success");
            } else swal("Error!", response.message, "error");
          },
        });
      }
    });
});
$("#student-list").on("click", "tr .student-activate-btn", function () {
  let data = STUDENT_TABLE.row($(this).parent().parent()).data();
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
        data: "key=" + data.id + "&submit=student-activate",
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            search_student($("#searchStudent"));
            swal("Activate!", response.message, "success");
          } else swal("Error!", response.message, "error");
        },
      });
    }
  });
});
  
  
$("#student-list").on("click", "tr .student-analysis-btn", function () {
    let data = STUDENT_TABLE.row($(this).parent().parent()).data();
    window.location.href = "student-analysis.php?student=" + data.email;
});

$("#student-list").on("click", "tr .student-single-btn", function () {
  let data = STUDENT_TABLE.row($(this).parent().parent()).data();
  window.location.href = "student-single.php?student=" + data.email;
});