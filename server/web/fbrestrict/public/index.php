<!DOCTYPE html>

<?php
	require_once('../config.php');

	$_redirect = false;
	$telclass = 'hidden';
	$propertelno = "";
	$logo = "";
	if ( scandir(getcwd().'/img') ) { $logo = scandir(getcwd().'/img')[2]; }; // indices 0 and 1 are . and ..
	if ( $FBRSERVER['TYPE'] == "single" AND isset($_POST['letsgo']) ) {
		$_redirect = true;
		$additionalclass = "notfirst";
		$telclass = "hidden";
		if ( isset($FBRSERVER['REDIRECT']) ) { $FBRCLIENT['REDIRECT'] = $FBRSERVER['REDIRECT']; }
	}
	if ( $FBRSERVER['TYPE'] == "multiple" AND isset($_POST['yourtelno']) ) {
		$additionalclass = "notfirst";
		$telclass = "show";
		$propertelno = preg_replace('/[^\d]/','',$_POST['yourtelno']);
		$propertelno = preg_replace('/^['.$FBRSERVER['COC'].']{0,4}'.$FBRSERVER['CIC'].'/','',$propertelno);
		foreach ( $FBRSERVER['TELLIST'] as $router => $list ) {
			if ( in_array($propertelno,$list) ) {	
				$_redirect = true;
				$telclass = "hidden";
				$FBRCLIENT['REDIRECT'] = $router;
			}
		}
	}
?>

<html lang="de">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/vendor.css">
</head>
<body>
	<style>
		h1 { text-align: center; width: 100%; background: var(--headline-background); color: var(--headline-color); font-family: "Muli", sans-serif; }
		form { position: relative; width: 70%; margin: 33vh auto; font-family: "Muli", sans-serif; text-align: left; font-size: 12pt; }
		input[type="tel"],p { text-align: left; width: 50%; margin: 0 25%; border-width: 0 0 2px 0; border-color: var(--headline-background); background: var(--headline-color); color: var(--headline-background); font-family: "Muli", sans-serif; }
		input[type="submit"] { text-align: left; width: 20%; background: white; color: black; font-family: "Muli", sans-serif; }
		label { position: relative; top: -15pt; color: #a4a4a4; transition: 0.7s ease; width: 50%; margin: 0 25%; padding: 2px; }
		.single, label.single { margin: 0 auto; display: block; text-align: center; cursor: pointer; color: var(--headline-background); }
		input:focus ~ label { top: -32pt; font-size: small; }
		.notexists.hidden, .redirect { opacity: 0; }
		.notexists.show, .redirect.notfirst.hidden { opacity: 1; }
		._input.notfirst.hidden { display: none; }
		p { transition: 0.7s ease; padding: 0.3rem; }
		#noaddon, #telno { display: none; }
		div.logo { text-align: center; }
		img { max-height: 20vh; margin: 0 auto;}
	</style>
	<div class="logo">
		<img src="img/<?php echo($logo); ?>">
	</div>
	<h1>FBrestrict</h1>
	<form id="noaddon">
		<p>Diese Seite funktioniert nur mit Firefox-basierten Browsern und installiertem FBRestrict-Addon.</p>
		<p>Das Addon kannst Du hier installieren bzw. aktivieren:</p>
		<p><a href="https://addons.mozilla.org/de/firefox/addon/fbrestrict/">https://addons.mozilla.org/de/firefox/addon/fbrestrict/</a></p>
	</form>
	<?php if ( $FBRSERVER['TYPE'] == 'multiple' ) { ?>
		<form id="telno" method="POST">
			<p class="redirect <?php echo($telclass.' '.$additionalclass); ?>">Einen Moment, Du wirst gleich weitergeleitet.</p>
			<p class="notexists <?php echo($telclass); ?>">Die angegebene Telefonnummer <?php echo($propertelno); ?> existiert leider nicht.</p>
			<p>&nbsp;</p>
			<input id="yourtelno" name="yourtelno" type="tel" class="_input <?php echo($telclass.' '.$additionalclass); ?>"><br />
			<label for="yourtelno" class="_input <?php echo($telclass.' '.$additionalclass); ?>">Deine dienstliche Telefonnummer</label><br />
		</form>
	<?php } else { ?>
		<form id="telno" method="POST">
			<p class="single redirect <?php echo($telclass.' '.$additionalclass); ?>">Einen Moment, Du wirst gleich weitergeleitet.</p>
			<input id="letsgo" name="letsgo" type="text" value="letsgo" hidden>
			<label for="_submit" class="single _input <?php echo($telclass.' '.$additionalclass); ?>">Login</label>
			<input id="_submit" type="submit" value="Auf zur FritzBox!" hidden>
		</form>
	<?php } ?>
	<?php if ( $_redirect && isset($FBRCLIENT['REDIRECT']) ) {
		?>
		<div id="fbrconfig" hidden><?php echo(json_encode($FBRCLIENT)); ?></div>
		<div id="fbrcred" hidden><?php echo(json_encode($FBRSERVER['CREDENTIALS'])); ?></div>
		<iframe id="fritzbox" frameBorder="0" style="position:fixed;top:0;left:0;margin:0;padding:0;overflow:hidden;height:100%;width:100%" height="100%" width="100%"></iframe>
		<script>
			setTimeout(function(){ setInterval(function(){
				if ( ! document.querySelector('meta[name=fbraddon]') ) {
					document.querySelector('#noaddon').style.display = "block";
					if ( document.querySelector('#telno') ) { document.querySelector('#telno').style.display = "none"; }
					if ( document.querySelector('#fritzbox') ) { document.querySelector('#fritzbox').remove(); }
				} else {
					if ( document.querySelector('#telno') ) { document.querySelector('#telno').style.display = "block"; }
					document.querySelector('#noaddon').style.display = "none";
					document.querySelector('meta[name=fbraddon]').remove();
				}	
			},5000)},1000);
		</script>
		<?php
	} ?>
	<script>
	//test if watermark element from fbrestrict is there...
	setTimeout(function(){
		if ( ! document.querySelector('meta[name=fbraddon]') ) {
			document.querySelector('#noaddon').style.display = "block";
			if ( document.querySelector('#telno') ) { document.querySelector('#telno').style.display = "none"; }
			if ( document.querySelector('#fritzbxox') ) { document.querySelector('#fritzbox').remove(); }
		} else {
			if ( document.querySelector('#telno') ) { document.querySelector('#telno').style.display = "block"; }
			document.querySelector('#noaddon').style.display = "none";
			if ( document.querySelector('#fritzbox') ) {
				document.querySelector('#fritzbox').src="<?php echo($FBRCLIENT['REDIRECT'].$FBRCLIENT['URL']); ?>";
			}
		}
	},500);
	</script>
</body>
</html>
