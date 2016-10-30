$(function(){

	$(document).on('submit','form[name=enrolment]',function(e){
		
		e.preventDefault();
		
		var data = $(this).serialize();

		var settings = {
			url: '/enrolme/enrolment/create'
			, type: 'POST'
			, dataType: 'json'
			, data: data
			, success: function(response) {
				if (response.error) {
					alert(response.message);
					return;
				}
				location.reload();
			}
		}

		$.ajax(settings);

	})



});