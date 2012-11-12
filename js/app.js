//active class
		$('nav li a').click(function()
		{
			$('nav li').removeClass('active');
			$(this).parent().addClass('active');
		});

//Fruitcake remove class on logo click
//remove after Fruitcake Film Challenge
		$('#logo').click(function()
		{
			$('nav li').removeClass('active');
		});

//Scrollspy
		$('#navbar').scrollspy();

//blinking "Click to Enter" text
$(document).ready(function(){
	$('.click-here').toggleClass('flash animated');
});

