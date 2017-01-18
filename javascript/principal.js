//Function called by the page "principal" and it will verify if the user is logged.
function loadPage()
{
	var url = 'principalController.class.php';
	//Call the ajax function passing the name of the file.
    sendRequest(url,callback);
}
//Function that will deal with the return ajax
function callback(resultado){
	if(resultado.responseText != 'null'){
		//Convert return to JSON
		var json = JSON.parse(resultado.responseText);
		
		if (json.result == "true") {
			document.getElementById('principal').innerHTML = '<p><h1>Welcome!</h1></p>'+
															'<p>ID: ' + json.id +' Username: ' + json.username + '</p>'; 
		}
	}
}