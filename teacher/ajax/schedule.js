$(document).ready(function () {
  $("#day-select").click(function () {
    let day = $(this).find(".mdc-list-item--selected").attr("data-value");
    load_schedule(day);
  });

  let SCHEDULE_TABLE = null;
  function load_schedule(day) {
    SCHEDULE_TABLE = $("#table2").DataTable({
      dom: "Blfrtip",
      responsive: true,
      bDestroy: true,
      processing: true,
      lengthChange: true,
      buttons: get_tableButton([0, 1, 2, 3, 4, 5]),
      ajax: {
        url: "api/schedule.php",
        type: "POST",
        data: { submit: "schedule-day", day },
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
  }
});
