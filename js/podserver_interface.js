$(document).ready(function(){ 
	$(".show_hide").show(); 
	$(".show_hide").click(function(){
	toggleTerminal();
	}); 
	if (!readCookie('podserver_terminal_status')){
		closeTerminal();
	}
	else{
		if (readCookie('podserver_terminal_status')=='opened'){
			openTerminal();
		}
		else{
			closeTerminal();
		}
	}
}); 


//alert(readCookie('podserver_terminal_status'));

function openTerminal(){
	eraseCookie('podserver_terminal_status');
	createCookie('podserver_terminal_status','opened',1);
	$("#div_terminal").show();	
}

function closeTerminal(){
	eraseCookie('podserver_terminal_status');
	createCookie('podserver_terminal_status','closed',1);
	$("#div_terminal").hide();
}
function toggleTerminal(){
	if (readCookie('podserver_terminal_status')=='closed'){
		openTerminal();
	}
	else{
		closeTerminal();
	}
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}


