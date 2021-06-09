$(document).ready(function () {

    $('.carousel').carousel({
      interval: 9000,
    });

    $('#formHome').validator();

    $(".navbar-toggle").on("click", function () {
        $(this).toggleClass("active");
        $("#navbar").toggleClass("small-screen-active");
    });

});

function scrollPageTo(element){
  var element = $(element);
  $('html,body').animate({scrollTop: element.offset().top},'slow');
}