$(document).ready(function(){
    
    $('ul.tabs li').click(function(){
        
        $('ul#week-forecast li').each(function(index) {
            
            if($(this).hasClass('current-tab')){
                
                $(this).removeClass('current-tab').addClass('tab');
                $('div#'+$(this).attr('id')).removeClass('current-tab').addClass('tab');
                
            }
            
        });
        
        $(this).addClass('current-tab');
        $('div#'+$(this).attr('id')).addClass('current-tab');
        
    });
    
});