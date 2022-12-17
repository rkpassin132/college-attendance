function load_student_current_class() {
  $.ajax({
    url: "api/profile.php",
    type: "POST",
    traditional: true,
    data: { submit: "student-current-class" },
    success: (response) => {
      response = JSON.parse(response);
      if (response.success) {
        let data = response.data;
        $("#student-current-class").text(
          `( ${data.course} - ${data.course_short} ) ${data.branch} - ${data.session} ${data.session_type}`
        );
      }
    },
  });
}
