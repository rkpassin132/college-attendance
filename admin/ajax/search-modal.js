$("#searchModal input[type=search]").on("input", function (e) {
  e.preventDefault();
  console.log("val: ", $(this).val());
  $.ajax({
    url: "api/search.php",
    type: "POST",
    data: "search=" + $(this).val() + "&submit=search",
    success: function (response) {
      response = JSON.parse(response);
      let list = $("#searchModal .list-group");
        list.children().remove();
      if (response.success) {
        for (const [key, data] of Object.entries(response.data)) {
          list.append(`<button type="button" class="text-capitalize list-group-item list-group-item-action" disabled>${key}</button>`);
          data.forEach((value) => {
            let redirect = "";
            if (key == "teacher") redirect = `teacher-single.php?teacher=${value.email}`;
            else if (key == "student") redirect = `student-single.php?student=${value.email}`;
            else redirect = "department.php";
            let html = `<a href="${redirect}" type="button" class="text-capitalize list-group-item list-group-item-action">`;
            if (key == "student" || key == "teacher"){
              html += `<i class="material-icons text-white mr-1 text-small  rounded-circle bg-${
                value.status ? "success" : "danger"
              }">power_settings_new</i>`;
            }
            html += `${value.name}</a>`;
            list.append(html);
          });
        }
      } else {
      }
    },
  });
});
