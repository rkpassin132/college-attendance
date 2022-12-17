$(document).ready(function () {
  let attendanceType = null;
  $.ajax({
    url: "api/attendance.php",
    type: "POST",
    traditional: true,
    data: { submit: "attendance-list" },
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        if (response.data.length > 0) {
          attendanceType = response.data;
          attendance_class(response.data);
        } else swal("Error", "Please contact administrator", "error");
      }
    },
  });

  function attendance_class(attendance_type) {
    $.ajax({
      url: "api/attendance.php",
      type: "POST",
      traditional: true,
      data: { submit: "attendance-class", time: getCurrentTime() },
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          let data = response.data;
          $("#attendance-class")
            .html(`<h3 class="mb-3 text-capitalize">( ${data.course} ) ${data.branch} </h3>
                <h4 class="text-capitalize">${data.session}-${data.session_type} - ${data.subject}</h4>`);
          load_student(attendance_type);
        } else swal("Information", response.message, "info");
      },
    });
  }

  let STUDENT_TABLE = null;
  function load_student(attendance_type) {
    let count = 0;
    STUDENT_TABLE = $("#table2").DataTable({
      dom: "Blfrtip",
      responsive: true,
      bDestroy: true,
      processing: true,
      lengthChange: true,
      buttons: get_tableButton([0, 1]),
      aaSorting: [[ 1, 'asc' ]],
      ajax: {
        url: "api/attendance.php",
        type: "POST",
        data: { submit: "attendance-student-list", time: getCurrentTime() },
        dataSrc: (res) => ("data" in res ? res.data : []),
        complete: () => (count = 0),
      },
      columns: [
        {
          data: "name",
          render: (data, type, row) => {
            return `${get_attendance_badge(row.action.attendance)} <span class="student_name">${data}</sapn>`;
          },
        },
        { data: "roll_no" },
        {
          data: "action",
          render: (data, type) => {
            html = "";
            for (let { id, name } of attendance_type) {
              html += `<label for="opt-${data.id}-${id}" attendance="${id}" class="ml-3 take-attendance-btn">
                        <input type="radio" ${data.attendance == id ? "checked" : ""} name="attendance-${data.id}" id="opt-${data.id}-${id}" class="" /><span class="ml-2">${name}</span>
                        </label>`;
            }
            html += "";
            return html;
          },
        },
      ],
    });
    STUDENT_TABLE.on("buttons-action", () => (count = 0));
  }

  $("#table2").on("input", "tr input[type='radio']", function () {
    let row = STUDENT_TABLE.row($(this).parent().parent().parent()).data();
    let data = {
      submit: "attendance-take",
      time: getCurrentTime(),
      key: row.id,
      attendance: $(this).parent().attr("attendance"),
    };
    $.ajax({
      url: "api/attendance.php",
      type: "POST",
      traditional: true,
      data,
      success: (response) => {
        response = JSON.parse(response);
        if (!response.success) swal("Error!", response.message, "error");
        else {
          let table_row = $(this).closest('tr');
          let html = `${get_attendance_badge($(this).parent().attr("attendance"))} <span class="student_name">${row.name}</sapn>`;
          if(table_row.hasClass('child')){
            table_row.prev().find('.student_name').parent().html(html);
          }else{
            table_row.find('.student_name').parent().html(html);
          }
        }
      },
    });
  });

  $('#table2').on('click', 'tr td.dtr-control', function () {
    // Collapse row details
    var tr = $(this).closest('tr');
    var row = STUDENT_TABLE.row( tr );
    if(row.child.isShown()){
    // This row is already open - close it
      row.child.hide();
    }
    STUDENT_TABLE.rows().every(function() {
    if(this.child.isShown()) {
    // Collapse row details
      this.child.hide();
      $(this.node()).removeClass('parent');
    }
    })
    if(row.child.hide()) {
    // Open this row
      row.child.show();
    }
  });

  function get_attendance_badge(attendance) {
    let html = "";
    for (let { id, name } of attendanceType) {
      if (parseInt(id) == parseInt(attendance)) {
        let n = name.toLowerCase().charAt(0);
        let type = n == "p" ? "success" : n == "a" ? "danger" : "warning";
        html =  `<span class="badge badge-${type}">${n.toLocaleUpperCase()}</span>`;
      }
    }
    return html;
  }
});
