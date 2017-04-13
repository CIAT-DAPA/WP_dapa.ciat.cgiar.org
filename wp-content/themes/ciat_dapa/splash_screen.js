$(document).ready(function(){
	var estado = false;

	$('#btn-toggle').on('click',function(){
		$('.seccionToggle').slideToggle();

		if (estado == true) {
			$(this).text("We have moved!");
			$('body').css({
				"overflow": "auto"
			});
			estado = false;
		} else {
			$(this).text("Close");
			$('body').css({
				"overflow": "hidden"
			});
			estado = true;
		}
	});
});