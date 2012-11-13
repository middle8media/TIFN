//active class
		$('#navbar li a').click(function() {
			$('#navbar li').removeClass('active');
			$(this).addClass('active');
		});

//Fruitcake remove class on logo click
//remove after Fruitcake Film Challenge
		$('#logo').click(function() {
			$('nav li').removeClass('active');
		});

//Scrollspy
		$('#navbar').scrollspy();

//blinking "Click to Enter" text
$(document).ready(function() {
	$('.click-here').addClass('flash animated');
});

