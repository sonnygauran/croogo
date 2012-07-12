$(document).ready(function(){
   
   $('ul.tabs-menu li').each(function(index) {
            
        if($(this).hasClass('current')){

            $(this).removeClass('current').addClass('tab');

        }

    });

    $(this).addClass('current');
    
});