$(document).ready(function(){
 $('ul.tabs a').click(function() {
  var curChildIndex = $(this).parent().prevAll().length + 1;
  $(this).parent().parent().children('.current-tab').removeClass('current-tab');
  $(this).parent().addClass('current-tab');
  $(this).parent().parent().next('.tabs').children('.current-tab').slideUp('fast',function() {
   $(this).removeClass('current-tab');
   $(this).parent().children('div:nth-child('+curChildIndex+')').slideDown('normal',function() {
    $(this).addClass('current-tab');
   });
  });
 });
});