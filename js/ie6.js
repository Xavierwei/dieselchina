jQuery(document).ready(function() {
    $('#menu li').eq(1).hover(function(){
        $(this).find('.sublevel').height(170);
        $(this).find('.sublevel ul').css({opacity:1});
    },function(){
        $(this).find('.sublevel').height(0);
        $(this).find('.sublevel ul').css({opacity:0});
    });
});