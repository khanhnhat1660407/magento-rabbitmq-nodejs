$(document).ready(function(){
    $('.product-container').hover(
        function(){
            $(this).children('.product-image-container').css("box-shadow", "0 10px 30px 0 rgba(0, 0, 0, 0.2)");
            $(this).children('.card-body.product-detail').children('.card-title.product-name').css("color", "#ff1695");
        },
        function(){
            $(this).children('.product-image-container').css( "box-shadow", "none" );
            $(this).children('.card-body.product-detail').children('.card-title.product-name').css("color", "#000000");
        }
    )
  });