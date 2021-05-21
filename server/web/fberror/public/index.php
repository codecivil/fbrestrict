<!DOCTYPE html>
<?php
	$logo = "";
	if ( scandir(getcwd().'/img') ) { $logo = scandir(getcwd().'/img')[2]; }; // indices 0 and 1 are . and ..
?>
<html lang="de">
<head>
	<meta charset="utf-8">
	<meta fbrerror="true">
	<link rel="stylesheet" type="text/css" href="css/vendor.css">
</head>
<body>
	<style>
		h1 { text-align: center; width: 100%; background: var(--headline-background); color: var(--headline-color); font-family: "Muli", sans-serif; }
		form { position: relative; width: 70%; margin: 33vh auto; font-family: "Muli", sans-serif; text-align: left; font-size: 12pt; }
		input[type="tel"],p { text-align: left; width: 50%; margin: 0 25%; border-width: 0 0 2px 0; border-color: var(--headline-background); background: var(--headline-color); color: var(--headline-background); font-family: "Muli", sans-serif; }
		input[type="submit"] { text-align: left; width: 20%; background: white; color: black; font-family: "Muli", sans-serif; }
		label { position: relative; top: -15pt; color: #a4a4a4; transition: 0.7s ease; width: 50%; margin: 0 25%; padding: 2px; }
		input:focus ~ label { top: -32pt; font-size: small; }
		.notexists.hidden, .redirect { opacity: 0; }
		.notexists.show, .redirect.notfirst.hidden { opacity: 1; }
		._input.notfirst.hidden { display: none; }
		p { transition: 0.7s ease; padding: 0.3rem; }
		#noaddon, #telno { display: none; }
		img { max-height: 20vh; }
	</style>
	<div>
		<img src="img/<?php echo($logo); ?>">
	</div>
	<div>
		<h1>Dieser Zugriff wurde nicht erlaubt.</h1>
	</div>
</body>
</html>
