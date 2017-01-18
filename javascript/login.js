//Function called by the page "login" and it will send the data to PHP file that will give a return.
function login()
{
	document.getElementById('btn-entrar').disabled=true;
	
	document.getElementById('LOGIN').disabled = false;
	document.getElementById('SENHA').disabled = false;

	var username = document.getElementById('LOGIN').value;
	var pass 	= document.getElementById('SENHA').value;
	var captcha = document.getElementById('captcha').value;

	//Declare variable "variaveis" to send the data to ajax.
	var variaveis = '';
	//verify if it's the first user access 
	if (document.getElementById('div-retorno').innerHTML == ''){
	 	variaveis = "LOGIN="+username+"&SENHA="+pass+"&captcha="+captcha;
	 }else{
	 	var new_pass = document.getElementById('NOVA_SENHA').value;
	 	var confirm_password = document.getElementById('CONFIRMA_SENHA').value;
	 	//Verify if the fields "New password" and "Confirm new password" is correct.
	 	var newpass = verificaNovaSenha(new_pass,confirm_password);
	 	if (newpass == false) {
	 		return newpass;
	 	};

	 	variaveis = "LOGIN="+username+"&SENHA="+pass+"&captcha="+captcha+"&NOVA_SENHA="+new_pass+"&CONFIRMA_SENHA="+confirm_password;
	 }
	var url = 'userController.class.php';
	//Call the ajax function passing the name of the file and variables.
    sendRequest(url,callback,variaveis);
	return false;
}
//Function that will deal with the return ajax
function callback(resultado){
	//Convert return to JSON
	var json = JSON.parse(resultado.responseText);
	document.getElementById('img_captchaphp').src = '';
	document.getElementById('img_captchaphp').src = 'captcha.php';
	//Treat the callback return.
	if (json.new_pass == "true") {
		document.getElementById('div-retorno').innerHTML = json.result;
		document.getElementById('LOGIN').disabled = true;
		document.getElementById('SENHA').disabled = true;
		document.getElementById('img_captchaphp').src = '';
		document.getElementById('img_captchaphp').src = 'captcha.php';
		document.getElementById('captcha').value = '';		
	}
	else if(json.result == "true"){
		window.location.href = "principal.html";
	}
	else{
		alert(json.result);
		if (json.error_type == 'captchaError') {
			document.getElementById('captcha').value = '';
			if (document.getElementById('div-retorno').innerHTML != ''){
				document.getElementById('LOGIN').disabled = true;
				document.getElementById('SENHA').disabled = true;
			}
		}
		else if(json.tipo_erro == 'novaSenhaError'){
			document.getElementById('captcha').value = '';
		}else{
			document.getElementById('captcha').value = '';
			document.getElementById('div-retorno').innerHTML = '';
		}		
	}
	document.getElementById('btn-entrar').disabled=false;
}
//Function that verifies if the fields "New password" and "Confirm new password" is correct.
function verificaNovaSenha(new_pass,confirm_password){
	if(new_pass == ''){
 		alert('Please enter your new password!');
 		erroNovaSenha();
 		return false;
 	}else if(confirm_password == ''){
		alert('Please enter the confirmation of your new password!');
		erroNovaSenha();
 		return false;
 	}else if (new_pass != confirm_password) {
 		alert("New password and Confirm new password don't match!");
 		erroNovaSenha();
 		return false;
 	}else{
 		return true;
 	}
}
//Function that clean the fields after validation in case of it return some error.
function erroNovaSenha(){
	document.getElementById('img_captchaphp').src = '';
	document.getElementById('img_captchaphp').src = 'captcha.php';
	document.getElementById('btn-entrar').disabled=false;
	document.getElementById('captcha').value = '';
	document.getElementById('LOGIN').disabled = true;
	document.getElementById('SENHA').disabled = true;
}
