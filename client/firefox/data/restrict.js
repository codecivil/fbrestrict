//collect fbrestrict server info when hitting
var cred = new Object();

if ( document.getElementById('fbrconfig') ) {
	browser.storage.local.set({ "fbrconfig": document.getElementById('fbrconfig').textContent });
	browser.storage.local.set({ "fbrurl": location.href });
	browser.storage.local.set({ "fbrcred": document.getElementById('fbrcred').textContent });
	document.getElementById('fbrcred').remove();
	document.getElementById('fbrconfig').remove();
	keepalive();
	//listen to fbrerror changing
	browser.storage.onChanged.addListener(function(changes,area){
		if ( mustCloseIFrame(changes,area) ) { setTimeout(closeIFrame,1000); }
	});
}

// observe restrictions as soon as logged intio fritzbox
if ( document.getElementById('uiPageTitle') ) {
	observeIFrame();
}

// login when hitting the login page for the first time
if ( document.querySelector('.dialog_outer') ) {
	FBlogin();
}

// start again if error page is hit
browser.storage.local.set({"fbrerror": false});
if ( document.querySelector('meta[fbrerror]') ) {
	browser.storage.local.set({"fbrerror": true});
}

//event listener test function for storage changes
function mustCloseIFrame(changes, area) {
	if ( area == "local" && changes['fbrerror'] && changes['fbrerror'].newValue == true ) { return true; } else { return false; }
}

function closeIFrame() {
	document.querySelector('#fritzbox').remove();
	window.location = window.location;
}

//keepalive
function keepalive(cycles) { 
	//by default 240 cycles = 20 minutes
	if (! cycles ) { cycles = 240; }
	counter = 0;
	let setMeta = function(){
		if ( counter < cycles && ! document.head.querySelector('meta[name=fbraddon]') ) {
			//create a watermark element
			var fbrmeta = document.createElement('meta');
			fbrmeta.name = "fbraddon";
			fbrmeta.content = "on";
			document.head.appendChild(fbrmeta);
			counter++;
			console.log(counter);
		}
	}
	browser.storage.local.get("fbrurl").then(result => {
		setMeta();
		keepAliveInterval = setInterval(setMeta,5000);
	});
}

function FBlogin() {
	//supply username and password on login site if given...
	browser.storage.local.get("fbrcred").then( result => {
		if ( result.fbrcred == "[]" ) { browser.storage.local.set({"fbrerror": true}); return; }
		cred = JSON.parse(result.fbrcred);
		browser.storage.local.set({"fbrcred": "[]"});
		if ( window.document.getElementById('uiViewUser') && cred.USERNAME != "" && cred.USERNAME != undefined ) {
			window.document.getElementById('uiViewUser').value = cred.USERNAME;
		}
		if ( window.document.getElementById('uiPass') && cred.PASSWORD != "" && cred.PASSWORD != undefined ) {
			window.document.getElementById('uiPass').value = cred.PASSWORD;
		}
		//... and login
		if ( cred.USERNAME != undefined && cred.PASSWORD != undefined && cred.USERNAME + cred.PASSWORD != "" ) {
			window.wrappedJSObject.gData = window.wrappedJSObject.data;
			window.document.getElementById('submitLoginBtn').click();
			cred = new Object();
		}
	});
}

function observeIFrame() {
	const observeTarget = document.body;
	const observeConfig = { attributes: true, childList: false, subtree: false, characterData: false };
	const onChangeCheck = function(mutationsList, observer) {
		browser.storage.local.get('fbrconfig').then( configstring => {
			let config = JSON.parse(configstring.fbrconfig);
			let ok = false;
			window.document.querySelectorAll('.arrow_box').forEach(function(arrowbox) {
				//check the config.TITLE permissions
				if ( config.TITLE.includes(arrowbox.textContent) ) { ok = true; }
			});
			// do not loop endlessly if there is no arrow_box on the config.URL (or login page, for that matter)
			if ( ! window.document.querySelector('.arrow_box') ) { ok = true; }
			if ( ! ok ) { window.location = config.URL; };
			//remove navigation menu to make cheating harder
			if ( window.document.getElementById('navigationMenu') ) { window.document.getElementById('navigationMenu').remove(); };
		});				
	}
	const observer = new MutationObserver(onChangeCheck);
	// start observing
	observer.observe(observeTarget, observeConfig);
}

//create a watermark element
var fbrmeta = document.createElement('meta');
fbrmeta.name = "fbraddon";
fbrmeta.content = "on";
document.head.appendChild(fbrmeta);

