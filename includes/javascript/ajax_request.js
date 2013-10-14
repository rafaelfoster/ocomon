function ajaxFunction(div,script){
	var ajaxRequest;  // The variable that makes Ajax possible!

	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			var ajaxDisplay = document.getElementById(div);
			ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}

	var args = ajaxFunction.arguments.length;

	var idx1 = ajaxFunction.arguments[2];
	var i;
	var j;
	var array = new Array();

	for (i=2; i<args; i++){//Jogando os argumentos (apartir do terceiro pois os dois primeiros sao fixos) para um array
		j = i-2;
		array[j] = ajaxFunction.arguments[i];
	}

	var queryString = MontaQueryString(array);

	ajaxRequest.open("GET", script + queryString, true); //"ajax-example.php"
	ajaxRequest.send(null);
}

function MontaQueryString (array) {
	var i;
	var size = array.length;
	var queryString = '?';

	for (i=0; i<size; i++){
		var param = array[i].split('=');
		param[1] = document.getElementById(param[1]).value;

		queryString += param[0] + "=" + param[1] + "&";
	}
	return queryString;
}
