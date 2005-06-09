function FormataCPF (campo,teclapres) { 
	var tecla = teclapres.keyCode; 
	vr = campo.value; 
	vr = vr.replace( '-', '' ); 
	vr = vr.replace( '.', '' ); 
	vr = vr.replace( '.', '' ); 
	tam = vr.length; 
	if (tam < 11 && tecla != 8){ tam = vr.length + 1 ; } 
	if (tecla == 8 ){	tam = tam - 1 ; } 
	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ 
		if ( tam <= 2 ){ 
	 		campo.value = vr ; } 
	 	if ( (tam > 2) && (tam <= 5) ){ 
	 		campo.value = vr.substr( 0, tam - 2 ) + '-' + vr.substr( tam - 2, tam ) ; } 
	 	if ( (tam >= 6) && (tam <= 8) ){ 
	 		campo.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + '-' + vr.substr( tam - 2, tam ) ; } 
	 	if ( (tam >= 9) && (tam <= 11) ){ 
	 		campo.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + '-' + vr.substr( tam - 2, tam ) ; } 
	} 
} 
  function modulo (dividendo,divisor) { 
    var quociente = 0; 
    var ModN = 0; 
    quociente = Math.floor(dividendo/divisor); 
    ModN = dividendo - (divisor*quociente); 
    return divisor - ModN; 
  } 
function ValidaLogin (theForm)
{
  while (theForm.Login1.value.substring(0,1) == ' ') {
     theForm.Login1.value = theForm.Login1.value.substring(1,theForm.Login1.value.length);
  }
  if (theForm.Login1.value == '')
  {
    alert('Favor informar um valor para o campo CPF');
    theForm.Login1.focus();
    return (false);
  }

  if (theForm.Login1.value.length < 14 && theForm.Login1.value != '')
  {
    alert('Favor digitar pelo menos 14 posições no campo CPF');
    theForm.Login1.focus();
    return (false);
  }

  if (theForm.Login1.value.length > 14 && theForm.Login1.value != '')
  {
    alert('Favor digitar no máximo 14 posições no campo CPF');
    theForm.Login1.focus();
    return (false);
  }

  var checkOK = '0123456789-.,/: ';
  var checkStr = theForm.Login1.value;
  var allValid = true;
  for (i = 0;  i < checkStr.length;  i++)
  {
    ch = checkStr.charAt(i);
    if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != '\\')) {
       for (j = 0;  j < checkOK.length;  j++) {
         if (ch == checkOK.charAt(j))
           break;
       } 
       if (j == checkOK.length)
       {
         allValid = false;
         break;
       }
    } 
  }
  if (!allValid)
  {
    alert('Favor digitar apenas números no campo CPF.');
    theForm.Login1.focus();
    return (false);
  }
    var igual = 0;
    var allValid = true;
    var soma = 0;
    var D1 = 0;
    var D2 = 0;
    var checkStr = theForm.Login1.value;
    checkStr = checkStr.replace('.','');
    checkStr = checkStr.replace('.','');
    checkStr = checkStr.replace('-','');
    igual = 0;
    for (i = 1;  i < 10;  i++)
    {
      soma = soma + (checkStr.charAt(i-1)*(11-i));
      if (checkStr.charAt(i) != checkStr.charAt(i-1)) igual = 1
    }
    if (igual == 0 && checkStr > '') {
       alert('CPF inválido.');
       theForm.Login1.focus();
       return (false);
    }
    D1 = modulo(soma,11);
    if (D1 > 9) { D1 = 0}
    soma = 0;
    for (i = 1;  i < 11;  i++)
    {
      soma = soma + (checkStr.charAt(i-1)*(12-i));
    }
    D2 = modulo(soma,11)
    if (D2 > 9) { D2 = 0}
    if ((D1 == checkStr.charAt(10-1)) && (D2 == checkStr.charAt(11-1))) { allValid = true}
    else { allValid = false }
    if (!allValid && checkStr > '') {
       alert('CPF inválido.');
       theForm.Login1.focus();
       return (false);
    }
    if (igual == 0 && checkStr > '') {
       alert('CPF inválido.');
       theForm.Login1.focus();
       return (false);
    }
  if (theForm.par.value == 'Senha') {
     if (confirm('Este procedimento irá reinicializar sua senha de acesso e sua assinatura eletrônica, enviando os dados para seu e-mail.\nConfirma?')) {
     } else {
       return false;
     }
  } else {
  while (theForm.Password1.value.substring(0,1) == ' ') {
     theForm.Password1.value = theForm.Password1.value.substring(1,theForm.Password1.value.length);
  }
  if (theForm.Password1.value == '')
  {
    alert('Favor informar um valor para o campo Senha');
    theForm.Password1.focus();
    return (false);
  }

  if (theForm.Password1.value.length < 3 && theForm.Password1.value != '')
  {
    alert('Favor digitar pelo menos 3 posições no campo Senha');
    theForm.Password1.focus();
    return (false);
  }

  if (theForm.Password1.value.length > 19 && theForm.Password1.value != '')
  {
    alert('Favor digitar no máximo 19 posições no campo Senha');
    theForm.Password1.focus();
    return (false);
  }

  var checkOK = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜÀÈÌÒÙÂÊÎÔÛàáâãçéêíîóôõúûüàèìòìâêîôû0123456789-,.()-:;[]{}*&%$#@!/ºª?<>|+=_\"\' 0123456789-.,/: ';
  var checkStr = theForm.Password1.value;
  var allValid = true;
  for (i = 0;  i < checkStr.length;  i++)
  {
    ch = checkStr.charAt(i);
    if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != '\\')) {
       for (j = 0;  j < checkOK.length;  j++) {
         if (ch == checkOK.charAt(j))
           break;
       } 
       if (j == checkOK.length)
       {
         allValid = false;
         break;
       }
    } 
  }
  if (!allValid)
  {
    alert('Você digitou caracteres inválidos no campo Senha.');
    theForm.Password1.focus();
    return (false);
  }
  }
  theForm.Login.value = theForm.Login1.value; 
  theForm.Password.value = theForm.Password1.value; 
  theForm.Login1.value = ""; 
  theForm.Password1.value = ""; 
  return (true); 
} 
function makeArray(len)
{
 for (var i = 0; i < len; i++) this[i] = null;
	this.length = len;
}

function fcDataDDMMYYYY(parametro){
	var now = new Date(parametro);
	var day = "00" + now.getDate();
	var month = now.getMonth()+1;
	month = "00" + month
	var year = now.getYear();
	document.write( day.substr(day.length-2,2) + "/" + month.substr(month.length-2,2) + "/" + year );
}

function fcDataBr(parametro){
	var dayNames = new makeArray(7); // Array of day names
	dayNames[0] = "Domingo";
	dayNames[1] = "Segunda-feira";
	dayNames[2] = "Terça-feira";
	dayNames[3] = "Quarta-feira";
	dayNames[4] = "Quinta-feira";
	dayNames[5] = "Sexta-feira";
	dayNames[6] = "Sábado";

	var monthNames = new makeArray(12); // Array of month Names
	monthNames[0] = "janeiro";
	monthNames[1] = "fevereiro";
	monthNames[2] = "março";
	monthNames[3] = "abril";
	monthNames[4] = "maio";
	monthNames[5] = "junho";
	monthNames[6] = "julho";
	monthNames[7] = "agosto";
	monthNames[8] = "setembro";
	monthNames[9] = "outubro";
	monthNames[10] = "novembro";
	monthNames[11] = "dezembro";

	var now = new Date(parametro);
	var day = now.getDay();
	var month = now.getMonth();
	var year = now.getYear();
	var date = now.getDate();
	document.write(dayNames[day] + ", " + date + " " + "de" + " " + monthNames[month] + " " + "de" + " " + year );
}

function PopupPic(sPicURL) {
	window.open('/gcs/foto.asp?id='+sPicURL, '', 'resizable=yes,width=200,height=200');
}

function nova_jan(newwindow)
{
  var desktop = window.open(newwindow,'new_window','toolbar=yes,location=yes,directories=yes,status=yes,scrollbars=yes,menubar=yes,resizable=yes');
}
