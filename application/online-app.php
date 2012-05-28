<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>TIFN - Triad Indie Film Network</title>

	<link rel="stylesheet" href="../css/960.min.css">
	<link rel="stylesheet" href="../css/style.css">

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="../js/scrollspy.js"></script>

</head>


<body data-spy="scroll">
<div class="container_12">

	<div id="header" class="grid_12">
		<a href="http://www.facebook.com/TriadIndie" title="TIFN on Facebook" alt="Like us on Facebook">
			<div id="facebook" class="grid_1 alpha"></div>
		</a>
		<div id="logo"class="grid_10">
			<h1><a href="/#welcome" title="TRIAD INDIE FILM NETWORK">TRIAD INDIE FILM NETWORK</a></h1>
		</div>
		<a href="http://twitter.com/TriadIndieFilm" title="TIFN on Twitter" alt="Follow us on Twitter">
			<div id="twitter" class="grid_1 omega"></div>
		</a>	
		<nav id="navbar" class="grid_12">
			<ul>
				<li><a href="/#welcome" title="Welcome">Welcome</a></li>
				<li><a href="/#about" title="About">About</a></li>
				<li><a href="/#membership" title="Membership">Membership</a></li>
				<li><a href="/#events" title="Events">Events</a></li>
				<li><a href="/#links" title="Links">Links</a></li>
				<li><a href="/#contact" title="Contact">Contact</a></li>
			</ul>
		</nav>
	</div>
</div>

<div class="container_12">
	<div class="grid_12" style="position:relative; top:250px;">
		<h2>Online Application</h2>
			<div class="grid_5 alpha">
				<p>Please fill out this form with all of the required information. Upon submission of the from you will be directed to pay via PayPal. Thank you for joining Triad Indie Film Network.</p>
			</div>
			<div class="grid_7 omega">
				<iframe frameborder="none" src="/application/form/form.php" allowTransparency="true" style="width:100%;height:567px;border:none;"></iframe>
			</div>
	</div>


</div>
	<script type="text/javascript">
		$('nav li a').click(function()
		{
		  $('nav li').removeClass('active');
		  $(this).parent().addClass('active');
		});
	</script>

	<script>
	  $('#navbar').scrollspy()
	</script>

</body>
</html>