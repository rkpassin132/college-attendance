$(document).ready(function () {
    
    $("#table2").DataTable({
      dom: "Blfrtip",
      responsive: true,
      bDestroy: true,
      processing: true,
      lengthChange: true,
      buttons: get_tableButton([0, 1, 2, 3, 4, 5]),
      ajax: {
        url: "api/teacher-analysis.php",
        type: "POST",
        data: { submit: "schedule-today-remain", time: getCurrentTime(), key: $("#teacher-key").attr("data-value") },
        dataSrc: (res) => ("data" in res ? res.data : []),
        complete: () => {},
      },
      columns: [
        { data: "department" },
        { data: "course" },
        { data: "session" },
        { data: "subject" },
        { data: "start_time" },
        { data: "end_time" },
      ],
    });
  
    $.ajax({
      url: "api/teacher-analysis.php",
      type: "POST",
      traditional: true,
      data: { submit: "dashboard-count",key: $("#teacher-key").attr("data-value") },
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          $("#week-classes span").text(response.data.week_classes);
          $("#no-classes span").text(response.data.classes);
          $("#no-subject span").text(response.data.subjects);
        }
      },
    });
  
    $.ajax({
      url: "api/teacher-analysis.php",
      type: "POST",
      traditional: true,
      data: { submit: "dashboard--graph-classes", key: $("#teacher-key").attr("data-value") },
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          load_weekly_class(response.data.weekly, response.data.colors[0]);
          load_yearly_class(response.data.yearly, response.data.colors[1]);
        }
      },
    });
  
    function load_weekly_class(data, color) {
      new Chart($("#weekly-class-line-graph"), {
        type: "line",
        data: {
          labels: data.weeks, // weekly
          datasets: [
            {
              label:"Classes Attend",
              backgroundColor: color+"33",
              borderColor: color,
              data: data.classes, // classes
              fill: true,
              cubicInterpolationMode: "monotone",
              tension: 0.5,
              pointRadius: 8,
              pointHoverRadius: 4
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              min: 10,
              max: 50,
            }
          },
  
        },
      });
    }
  
    function load_yearly_class(data, color) {
      new Chart($("#yearly-class-line-graph"), {
        type: "line",
        data: {
          labels: data.years, // yearly
          datasets: [
            {
              label:"Classes Attend",
              backgroundColor: color+"33",
              borderColor: color,
              data: data.classes, // classes
              fill: true,
              cubicInterpolationMode: "monotone",
              tension: 0.5,
              pointRadius: 8,
              pointHoverRadius: 4
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              min: 10,
              max: 50,
            }
          },
  
        },
      });
    }
    const setOpacity = (hex, alpha) => `${hex}${Math.floor(alpha * 255).toString(16).padStart(2, 0)}`;
  });
  