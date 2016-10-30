$(function(){
	$(document).on('click','.open-panel',function(e){		
		var $target = $($(this).data('target'));
		//$target.addClass('transition');
		$target.removeClass('panel-close').addClass('panel-open');		
	});

	$(document).on('click','.close-panel',function(e){
		var target = $(this).data('target');
		$(target).removeClass('panel-open').addClass('panel-close');
	});
});