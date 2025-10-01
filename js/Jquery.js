$(document).ready(function () {
  $('.row').hover(
      function () {
          $(this).addClass('bounce-effect');
      },
      function () {
          $(this).removeClass('bounce-effect');
      }
  );

  // Optionally, for individual boxes
  $('.food-item').hover(
      function () {
          $(this).addClass('bounce-effect');
      },
      function () {
          $(this).removeClass('bounce-effect');
      }
  );
});
