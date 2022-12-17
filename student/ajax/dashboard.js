$(document).ready(() => {
  $("#table2").DataTable({
    dom: "Blfrtip",
    responsive: true,
    bDestroy: true,
    processing: true,
    lengthChange: true,
    buttons: get_tableButton([0, 1, 2, 3, 4, 5]),
    ajax: {
      url: "api/schedule.php",
      type: "POST",
      data: { submit: "schedule-today-remain", time: getCurrentTime() },
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

  $.ajax({
    url: "api/dashboard.php",
    type: "POST",
    traditional: true,
    data: { submit: "dashboard-count" },
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("#total-subject span").text(response.data.subject);
        $("#total-attendance span").text(response.data.attendance);
      }
    },
  });

  $.ajax({
    url: "api/dashboard.php",
    type: "POST",
    traditional: true,
    data: { submit: "dashboard-percent-classes" },
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        attendance_graph(response.data)
      }
    },
  });

});

function attendance_graph(data) {
  let label = [];
  let value = [];
  let bg = [];
  for(let d of data){
    label.push(d.name);
    value.push(parseInt(d.count));
    bg.push(d.color);
  }
  var ctx = document.getElementById("attendance-chart");
  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: label,
      datasets: [{
        data: value,
        backgroundColor: bg,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true, 
      legend: {
        display: true,
        position: 'left',
      }
    },
  });
}
