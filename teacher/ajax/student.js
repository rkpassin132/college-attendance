$(document).ready(function () {
    let STUDENT_SEARCH_TABLE;
    function search_student(form) {
      let data = {};
      let count = 0;
      let arr = form.serializeArray();
      for (let key in arr) data[arr[key].name] = arr[key].value;
      data["submit"] = "student-search";

      form.find(':input[type=submit]').addClass('button--loading');
      STUDENT_SEARCH_TABLE = $("#student-list").DataTable({
        dom: "Blfrtip",
        responsive: true,
        bDestroy: true,
        processing: true,
        lengthChange: true,
        buttons: get_tableButton([0, 1, 2]),
        ajax: {
          url: "api/student.php",
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
          { data: "name" },
          { data: "email" },
          {
            data: "phone",
            render: function (data, type) {
              return `<a class="text-dark" href="tel:${data}" >${data}</a>`;
            },
          },
        ],
      });
      STUDENT_SEARCH_TABLE.on("buttons-action", () => (count = 0));
    }
    
    $("#searchStudent").on("submit", function (e) {
      e.preventDefault();
      if ($(this).attr("valid") != "true") return;
      search_student($(this));
    });
});
