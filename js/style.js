$(document).ready( function() { 
	$('.move-id').click( function() {
		location.hash = $(this).attr('href'); 
		return false; 
	});
});