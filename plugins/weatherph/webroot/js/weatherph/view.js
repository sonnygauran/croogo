$(document).ready(function(){
    
    $('.panel').hide();
    $('div.temperature').show();
    
    $('ul#week-forecast li').click(function(){
        
        $('ul#week-forecast li').each(function(index) {
            
            if($(this).hasClass('current-tab')){
                
                $(this).removeClass('current-tab').addClass('tab');
                $('div#'+$(this).attr('id')).removeClass('current-tab').addClass('tab');
                
            }
            
        });
        
        $(this).addClass('current-tab');
        $('div#'+$(this).attr('id')).addClass('current-tab');
        
    });
    
    $('div#charts .tabs li').click(function(){
        
        $('div#charts .tabs li').each(function(index) {
            
            if($(this).hasClass('current-tab')){
                
                $(this).removeClass('current-tab').addClass('tab');
                
            }
            
        });
        
        $(this).addClass('current-tab');
        
        
    });
    
    $('div#outlook .tabs li').click(function(){
        
        $('div#outlook .tabs li').each(function(index) {
            
            if($(this).hasClass('current-tab')){
                
                $(this).removeClass('current-tab').addClass('tab');
                
                
            }
            
        });
        
        $(this).addClass('current-tab');
        
        
    });
    
    
    $('.flip').click(function(){
        attributes = $(this).attr('class').split(' ');
        
        $('.panel').hide();
        $('.' + attributes[0]).show();
        
    });
    
        $(".flip a").each(function() {
            $(this).click(function(){
                evt.preventDefault();
                loader();
            });
        });

    
    
});