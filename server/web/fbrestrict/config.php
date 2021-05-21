<?php
	// FBRestrict Config
	
	/**
	 **  configuration parameters stored on the clients
	 **/
	$FBRCLIENT = array(
	// redirect URL
		"URL" => "/?lp=callRedi",
		
	// for allowed sites the "arrow_box"es contain at least one of the values
		"TITLE" => array(
			"Rufbehandlung",
			"Rufumleitung"
		)
	);
	
	/**
	 **  configuration parameters used only on the server (exception: CREDENTIALS, see below)
	 **/
	$FBRSERVER = array(
	// Username and password are stored in plaintext here and appear on the client side, although only for a very brief period; you may feel safer
	// not supplying the credentials here (leaving them empty); in this case the fritzbox login page is presented as usual
		"CREDENTIALS" => array( "USERNAME" => "", "PASSWORD" => "" ),
	
	// type of redirect; available are "single" (for a single fritzbox) and "mutliple" (for multiple fritzboxes)
		"TYPE" => "single",
		
	// if type is "single", please give the URL of the redirected fritzbox here:
		"REDIRECT" => "http://fb.exmaple.com",
	
	/* 
	 * All the following is only relevant for "multiple" type; in this case the redirection is governed by the user's
	 * telephone number; you can replace the telephone number by any other type of code, of course; but be aware, that these
	 * codes are stored in plaintext here, so these are NOT equivalent to passwords!
	 */
	// country telephone code
		"COC" => "0049",
	
	// your local telephone prefix ("city code"), without leading zero
		"CIC" => "123",
	
	// list of telephone numbers without country and city prefixes in your FritzBoxes: <Port of redirected FritzBox> => <Array of telephone numbers>
		"TELLIST" => array(
			"http://fb1.example.com" => array('1234'),
			"http://fb2.example.com" => array('2345','3456')
		)
	);	
?>
