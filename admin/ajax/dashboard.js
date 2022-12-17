$(document).ready(function () {
  $.ajax({
    url: "api/dashboard.php",
    type: "POST",
    traditional: true,
    data: {submit: 'dashboard-count'},
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("#course-active-count span").text(response.data.course_active);
        $("#course-inactive-count span").text(response.data.course_inactive);
        $("#branch-active-count span").text(response.data.branch_active);
        $("#branch-inactive-count span").text(response.data.branch_inactive);
        $("#teacher-active-count span").text(response.data.teacher_active);
        $("#teacher-inactive-count span").text(response.data.teacher_inactive);
        $("#student-active-count span").text(response.data.student_active);
        $("#student-inactive-count span").text(response.data.student_inactive);
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
  });

  $.ajax({
    url: "api/dashboard.php",
    type: "POST",
    traditional: true,
    data: {submit: 'dashboard-branch-student'},
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        $("#course-type-select .mdc-list-item").remove();
        let display = ``;
        for(let [key, {course_id, course_name}] of Object.entries(response.data)){
          $("#chart-department-student").append(`<canvas  ${display} class="chart-department-student" course="${course_id}" height="260"></canvas>`);
          $("#course-type-select").append(`<li class="mdc-list-item" data-value="${course_id}">${course_name}</li>`);
          display = `style="display:none"`;
        }
        for(let [key, value] of Object.entries(response.data)){
          setBranchStudentChart(value);
        }
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
  });

  $.ajax({
    url: "api/dashboard.php",
    type: "POST",
    traditional: true,
    data: {submit: 'dashboard-teacher-student-bar-graph'},
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        setTeacherStudentBar(response.data);
      } else {
        if ("data" in response) showValidError($(this), response.data);
        else swal("Error", response.message, "error");
      }
    },
  });

  function setBranchStudentChart(data){
    console.log(data);
    let label = [], labelData = [], bgColor = [];
    for (let { department_name } of data.department) label.push(department_name);
    for (let { student } of data.department) labelData.push(student);
    for (let { color } of data.department) bgColor.push(color); 
    new Chart($(`.chart-department-student[course=${data.course_id}]`), {
      type: "doughnut",
      data: {
        labels: label,
        datasets: [{
          backgroundColor: bgColor,
          data: labelData
        }]
      },
      options: {
        responsive: true,
        transitions: {
          show: {
            animations: {
              x: {
                from: 0
              },
              y: {
                from: 0
              }
            }
          },
          hide: {
            animations: {
              x: {
                to: 0
              },
              y: {
                to: 0
              }
            }
          }
        },
        title: {
          display: true,
          text: data.course_name+" branch students"
        }
      }
    });
  }

  function setTeacherStudentBar(data){
    new Chart($(`#teachet-student-bar-graph`), {
      type: "bar",
      data: {
        labels: data.year,
        datasets: [
          {
            label:"Teacher",
            backgroundColor: data.color[0],
            data: data.teacher
          },
          {
            label:"Student",
            backgroundColor: data.color[1],
            data: data.student
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
        	yAxes: [{
            	ticks: {
                	beginAtZero: true
            	}
        	}]
    	  }
      }
    });
  }
});

$("#course-type-select").click(function () {
  let course = $(this).find(".mdc-list-item--selected").attr("data-value");
  $(".chart-department-student").each(function(){
    if($(this).attr("course") == course) $(this).show();
    else $(this).hide();
  });
});
