$(function () {
  $('.search_conditions').click(function () {
    $(this).toggleClass('open');
    $('.search_conditions_inner').slideToggle();
  });

  $('.subject_edit_btn').click(function () {
    $(this).toggleClass('open');
    $('.subject_inner').slideToggle();
  });
});
