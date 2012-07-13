$(document).ready(function(){
    
    $('ul.tabs-menu li').click(function(){

        $('ul.tabs-menu li').each(function(index) {
            if($(this).hasClass('current')){
                $(this).removeClass('current');
            }
        });
        
        $('div.readings').each(function(index){
            if($(this).hasClass('current')){
                $(this).removeClass('current');
            }
        });

        $(this).addClass('current');
        $('div#mtbl-' + $(this).attr('id')).addClass('current');
        
    });
   
});