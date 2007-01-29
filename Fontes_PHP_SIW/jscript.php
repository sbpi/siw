<?
// FunÁ„o para crÌtica da hora
function VerfHora() {
  print "function VerfHora(Datac) {"."\r\n";
  print "   var numero = '0123456789:';"."\r\n";
  print "   var conta=0;"."\r\n";
  print "   var i=0;"."\r\n";
  print "   if (Datac.length==4){"."\r\n";
  print "     var nHora = parseFloat(Datac.substring(0,1));"."\r\n";
  print "     var nMin = parseFloat(Datac.substring(2,4));"."\r\n";
  print "   }"."\r\n";
  print "   else if (Datac.length==5){"."\r\n";
  print "     var nHora = parseFloat(Datac.substring(0,2));"."\r\n";
  print "     var nMin = parseFloat(Datac.substring(3,5));"."\r\n";
  print "   }"."\r\n";
  print "   if (Datac.length==0){"."\r\n";
  print "      return (true);"."\r\n";
  print "   }"."\r\n";
  print "   else if ((Datac.length!=5) && (Datac.length!=4)){"."\r\n";
  print "      alert('O formato da hora È HH:MM.');"."\r\n";
  print "      return (false);"."\r\n";
  print "   }"."\r\n";
  print "   if (nHora<0 || nHora>23){"."\r\n";
  print "       alert('Hora inv·lida !');"."\r\n";
  print "       return (false);"."\r\n";
  print "   }"."\r\n";
  print "   if (nMin<0 || nMin>59){"."\r\n";
  print "       alert('Hora inv·lida !');"."\r\n";
  print "       return (false);"."\r\n";
  print "   }"."\r\n";
  print "    for (i=0;i<Datac.length;i++) {"."\r\n";
  print "        if (numero.indexOf(Datac.charAt(i))==-1){"."\r\n";
  print "           alert('O formato da hora È HH:MM.');"."\r\n";
  print "           return (false);"."\r\n";
  print "        }"."\r\n";
  print "        if (Datac.charAt(i)==':') {"."\r\n";
  print "           conta = conta + 1;"."\r\n";
  print "        }"."\r\n";
  print "    }"."\r\n";
  print "    if (conta >1 && conta<1){"."\r\n";
  print "       alert('O formato da hora È HH:MM.');"."\r\n";
  print "       return (false);"."\r\n";
  print "    }"."\r\n";
  print "   return (true);"."\r\n";
  print "}"."\r\n";
}

// Abre a tag SCRIPT
function ScriptOpen($Language) { print chr(13).chr(10).'<SCRIPT LANGUAGE="'.$Language.'"><!--'.chr(13).chr(10); }

// Encerra a tag SCRIPT
function ScriptClose() { print "--></SCRIPT>"."\r\n"; }

// Abre a funÁ„o de validaÁ„o de formul·rios
function ValidateOpen($FunctionName) { 
  ShowHTML('function Trim(TRIM_VALUE){ ');
  ShowHTML('  if(TRIM_VALUE.length < 1){ return""; }');
  ShowHTML('  TRIM_VALUE = RTrim(TRIM_VALUE);');
  ShowHTML('  TRIM_VALUE = LTrim(TRIM_VALUE);');
  ShowHTML('  if(TRIM_VALUE==""){ return ""; } else { return TRIM_VALUE; }');
  ShowHTML('}');
  ShowHTML('');
  ShowHTML('function RTrim(VALUE){');
  ShowHTML('  var w_space = String.fromCharCode(32);');
  ShowHTML('  var v_length = VALUE.length;');
  ShowHTML('  var strTemp = "";');
  ShowHTML('  if(v_length < 0){ return""; }');
  ShowHTML('  var iTemp = v_length -1;');
  ShowHTML('  while(iTemp > -1){ ');
  ShowHTML('    if(VALUE.charAt(iTemp) != w_space){');
  ShowHTML('      strTemp = VALUE.substring(0,iTemp +1);');
  ShowHTML('      break;');
  ShowHTML('    }');
  ShowHTML('    iTemp = iTemp-1;');
  ShowHTML('  }');
  ShowHTML('  return strTemp;');
  ShowHTML('}');
  ShowHTML('');
  ShowHTML('function LTrim(VALUE){');
  ShowHTML('  var w_space = String.fromCharCode(32);');
  ShowHTML('  if(v_length < 1){ return""; }');
  ShowHTML('  var v_length = VALUE.length;');
  ShowHTML('  var strTemp = "";');
  ShowHTML('  var iTemp = 0;');
  ShowHTML('  while(iTemp < v_length){');
  ShowHTML('    if(VALUE.charAt(iTemp) != w_space){');
  ShowHTML('      strTemp = VALUE.substring(iTemp,v_length);');
  ShowHTML('      break;');
  ShowHTML('    }');
  ShowHTML('    iTemp = iTemp + 1;');
  ShowHTML('  }');
  ShowHTML('  return strTemp;');
  ShowHTML('}');
  ShowHTML('');
  ShowHTML('function '.$FunctionName.' (theForm) {'); 
}

// Encerra a funÁ„o de validaÁ„o de formul·rios
function ValidateClose() {
  print "  return (true); "."\r\n";
  print "} "."\r\n";
}

// C·lculo de mÛdulo
function Modulo() {
  print "  function modulo (dividendo,divisor) { "."\r\n";
  print "    var quociente = 0; "."\r\n";
  print "    var ModN = 0; "."\r\n";
  print "    quociente = Math.floor(dividendo/divisor); "."\r\n";
  print "    ModN = dividendo - (divisor*quociente); "."\r\n";
  print "    return divisor - ModN; "."\r\n";
  print "  } "."\r\n";
}


// Rotina auxiliar ‡ de verificaÁ„o de datas
function CheckBranco() {
  print "  function checkbranco(elemento){ "."\r\n";
  print "    var flagbranco = true "."\r\n";
  print "    //alert( 'elemento = ' + elemento) "."\r\n";
  print "    for (i=0;i < elemento.length;i++){ "."\r\n";
  print "    	//alert('elemento.charat( ' + i + ') = ' + elemento.charAt(i) ) "."\r\n";
  print "    	if (elemento.charAt(i) != ' '){ "."\r\n";
  print "    		flagbranco = false "."\r\n";
  print "    	} "."\r\n";
  print "    } "."\r\n";
  print "    //alert('valor de flagbranco = ' + flagbranco) "."\r\n";
  print "    return flagbranco "."\r\n";
  print "  } "."\r\n";
}

// Rotina de comparaÁ„o de datas
function CompData($Date1,$DisplayName1,$Operator,$Date2,$DisplayName2) {
  switch ($Operator) {
    case "==":  $w_Operator=" igual a ";            break;
    case "!=":  $w_Operator=" diferente de ";       break;
    case ">":   $w_Operator=" maior que ";          break;
    case "<":   $w_Operator=" menor que ";          break;
    case ">=":  $w_Operator=" maior ou igual a ";   break;
    case "=>":  $w_Operator=" maior ou igual a ";   break;
    case "<=":  $w_Operator=" menor ou igual a ";   break;
    case "=<":  $w_Operator=" menor ou igual a ";   break;
  }
  print "  var D1 = theForm.".$Date1.".value; "."\r\n";
  if (strpos("1234567890",substr($Date2,0,1))===false) {
     print "  var D2 = theForm.".$Date2.".value;"."\r\n"; }
  else {
    print "  var D2 = '".$Date2."';"."\r\n";
  }

  print "  if (D1.length != 0 && D2.length != 0) { "."\r\n";
  print "     var d1; "."\r\n";
  print "     var m1; "."\r\n";
  print "     var a1; "."\r\n";
  print "     var h1; "."\r\n";
  print "     var d2; "."\r\n";
  print "     var m2; "."\r\n";
  print "     var a2; "."\r\n";
  print "     var h2; "."\r\n";
  print "     var Data1; "."\r\n";
  print "     var Data2; "."\r\n";
  print "     if (D1.length == 17) { "."\r\n";
  print "        d1 = D1.substr(0,2); "."\r\n";
  print "        m1 = D1.substr(3,2); "."\r\n";
  print "        a1 = D1.substr(6,4); "."\r\n";
  print "        h1 = D1.substr(12,2) + D1.substr(15,2); "."\r\n";
  print "        d2 = D2.substr(0,2); "."\r\n";
  print "        m2 = D2.substr(3,2); "."\r\n";
  print "        a2 = D2.substr(6,4); "."\r\n";
  print "        h2 = D2.substr(12,2) + D2.substr(15,2); "."\r\n";
  print "        Data1 = a1 + m1 + d1 + h1; "."\r\n";
  print "        Data2 = a2 + m2 + d2 + h2; "."\r\n";
  print "     } "."\r\n";
  print "     if (D1.length == 10) { "."\r\n";
  print "        d1 = D1.substr(0,2); "."\r\n";
  print "        m1 = D1.substr(3,2); "."\r\n";
  print "        a1 = D1.substr(6,4); "."\r\n";
  print "        d2 = D2.substr(0,2); "."\r\n";
  print "        m2 = D2.substr(3,2); "."\r\n";
  print "        a2 = D2.substr(6,4); "."\r\n";
  print "        Data1 = a1 + m1 + d1; "."\r\n";
  print "        Data2 = a2 + m2 + d2; "."\r\n";
  print "     } "."\r\n";
  print "     if (D1.length == 7) { "."\r\n";
  print "        d1 = '01'; "."\r\n";
  print "        m1 = D1.substr(0,2); "."\r\n";
  print "        a1 = D1.substr(3,6); "."\r\n";
  print "        d2 = '01'; "."\r\n";
  print "        m2 = D2.substr(0,2); "."\r\n";
  print "        a2 = D2.substr(3,7); "."\r\n";
  print "        Data1 = a1 + m1 + d1; "."\r\n";
  print "        Data2 = a2 + m2 + d2; "."\r\n";
  print "     } "."\r\n";
  print "     if (!(Data1 ".$Operator." Data2)) { "."\r\n";
  print "        alert('".$DisplayName1." deve ser ".$w_Operator.$DisplayName2.".'); "."\r\n";
  print "        theForm.".$Date1.".focus(); "."\r\n";
  print "        return (false); "."\r\n";
  print "     } "."\r\n";
  print "  } "."\r\n";
}

function CompHora ($hour1, $DisplayName1, $Operator, $hour2, $DisplayName2) {
  switch ($Operator) {
    case "==":  $w_Operator=" igual a ";            break;
    case "!=":  $w_Operator=" diferente de ";       break;
    case ">":   $w_Operator=" maior que ";          break;
    case "<":   $w_Operator=" menor que ";          break;
    case ">=":  $w_Operator=" maior ou igual a ";   break;
    case "=>":  $w_Operator=" maior ou igual a ";   break;
    case "<=":  $w_Operator=" menor ou igual a ";   break;
    case "=<":  $w_Operator=" menor ou igual a ";   break;
  }
  print "  var D1 = theForm.".$hour1.".value; "."\r\n";
  if (strpos("1234567890", substr($hour2,0,1))===false) {
    print "   var D2 = theForm.".$hour2.".value;"."\r\n";
  } else {
    print "   var D2 = '".$hour2."';"."\r\n";
  }
  print "  if (D1.length != 0 && D2.length != 0) { "."\r\n";
  print "   var h1; "."\r\n";
  print "   var h2; "."\r\n";
  print "   h1 = D1.substr(0,2) + D1.substr(3,2); "."\r\n";
  print "   h2 = D2.substr(0,2) + D2.substr(3,2); "."\r\n";
  print "   if (!(parseFloat(h1) ".$Operator." parseFloat(h2))) { "."\r\n";
  print "      alert('".$DisplayName1." deve ser ".$w_Operator.$DisplayName2.".'); "."\r\n";
  print "      theForm.".$hour1.".focus(); "."\r\n";
  print "      return (false); "."\r\n";
  print "   } "."\r\n";
  print " } "."\r\n";
}

function CompValor ($Valor1, $DisplayName1, $Operator, $Valor2, $DisplayName2) {
  switch ($Operator) {
    case "==":  $w_Operator=" igual a ";            break;
    case "!=":  $w_Operator=" diferente de ";       break;
    case ">":   $w_Operator=" maior que ";          break;
    case "<":   $w_Operator=" menor que ";          break;
    case ">=":  $w_Operator=" maior ou igual a ";   break;
    case "=>":  $w_Operator=" maior ou igual a ";   break;
    case "<=":  $w_Operator=" menor ou igual a ";   break;
    case "=<":  $w_Operator=" menor ou igual a ";   break;
  }
  print "  var V1 = theForm." . $Valor1 . ".value; "."\r\n";
  if (strpos("1234567890", substr($Valor2,0,1))===false) {
    print "   var V2 = theForm." . $Valor2 . ".value;"."\r\n";
  } else {
    print "   var V2 = '" . $Valor2 . "';"."\r\n";
  }
  print "  if (V1.length != 0 && V2.length != 0) { "."\r\n";
  print "     V1 = V1.toString().replace(/\\$|\\./g,''); "."\r\n";
  print "     V2 = V2.toString().replace(/\\$|\\./g,''); "."\r\n";
  print "     V1 = V1.toString().replace(',','.'); "."\r\n";
  print "     V2 = V2.toString().replace(',','.'); "."\r\n";
  print "     if (isNaN(V1)) { "."\r\n";
  print "        alert('" . $DisplayName1 . " n„o È um valor v·lido!.'); "."\r\n";
  print "        theForm." . $Valor1 . ".focus(); "."\r\n";
  print "        return false; "."\r\n";
  print "     } "."\r\n";
  print "     if (isNaN(V2)) { "."\r\n";
  print "        alert('" . $DisplayName2 . " n„o È um valor v·lido!.'); "."\r\n";
  if (strpos("1234567890",substr($Valor2,0,1))===false) {
     print "        theForm." . $Valor2 . ".focus(); "."\r\n";
  } else {
     print "        theForm." . $Valor1 . ".focus(); "."\r\n";
  }
  print "        return false; "."\r\n";
  print "     } "."\r\n";
  print "     var v1 = parseFloat(V1);"."\r\n";
  print "     var v2 = parseFloat(V2);"."\r\n";
  print "     if (!(v1 " . $Operator . " v2)) { "."\r\n";
  print "        alert('" . $DisplayName1 . " deve ser " .$w_Operator . $DisplayName2 . ".'); "."\r\n";
  print "        theForm." . $Valor1 . ".focus(); "."\r\n";
  print "        return false; "."\r\n";
  print "     } "."\r\n";
  print "  } "."\r\n";
}

function toMoney() {
  print " function toMoney(campo, fmt) { "."\r\n";
  print "  num = campo.toString().replace(/\$|\,/g,''); "."\r\n";
  print "  if (isNaN(num)) { "."\r\n";
  print "    return false;} "."\r\n";
  print "  if (fmt.toUpperCase() == 'US') { "."\r\n";
  print "   cents = Math.floor((num*100+0.5)%100); "."\r\n";
  print "   num = Math.floor((num*100+0.5)/100).toString(); "."\r\n";
  print "   if(cents < 10) cents = '0' + cents; "."\r\n";
  print "   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) "."\r\n";
  print "   num = num.substring(0,num.length-(4*i+3)) + ";
  print "   return (num + '.' + cents); "."\r\n";
  print "  } "."\r\n";
  print "  if (fmt.toUpperCase() == 'BR') { "."\r\n";
  print "   cents = Math.floor((num*100+0.5)%100); "."\r\n";
  print "   num = Math.floor((num*100+0.5)/100).toString(); "."\r\n";
  print "   if(cents < 10) cents = '0' + cents; "."\r\n";
  print "   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) "."\r\n";
  print "   num = num.substring(0,num.length-(4*i+3)) + '.' +num.substring(num.length-(4*i+3)); "."\r\n";
  print "   return (num + ',' + cents); "."\r\n";
  print "  } "."\r\n";
  print "  return false; "."\r\n";
  print " } "."\r\n";
}

function DecodeDate() {
  print "function LeapYear(intYear) { "."\r\n";
  print " if (intYear % 100 == 0) { "."\r\n";
  print "  if (intYear % 400 == 0) { return true; } "."\r\n";
  print " } "."\r\n";
  print "  else { "."\r\n";
  print " if ((intYear % 4) == 0) { return true; } "."\r\n";
  print " } "."\r\n";
  print " return false; "."\r\n";
  print " } "."\r\n";
  print " function DecodeDate(date) { "."\r\n";
  print "  var day, month, year; "."\r\n";
  print "  if (date.length < 10) return false; "."\r\n";
  print "  day = date.substr(0, 2); "."\r\n";
  print "  month = date.substr(3, 2); "."\r\n";
  print "  year = date.substr(6, 4); "."\r\n";
  print "  if (parseInt(month) == 4 || parseInt(month) == 6 || parseInt(month) == 9 || parseInt(month) == 11) { "."\r\n";
  print "   if (parseInt(day) == 31) return false; } "."\r\n";
  print "  if (LeapYear(parseInt(year))) { "."\r\n";
  print "    if (parseInt(day) > 29) return false;  "."\r\n";
  print "    else { "."\r\n";
  print "          if (parseInt(day) > 28) return false; "."\r\n";
  print "         } "."\r\n";
  print "   } "."\r\n";
  print "  return (new Date(parseInt(year), parseInt(month) - 1, parseInt(day))); "."\r\n";
  print " } "."\r\n";
}

function FormataData() {
  print "function FormataData(campo, teclapres) { "."\r\n";
  print "	var tecla = teclapres.keyCode; "."\r\n";
  print "	vr = campo.value; "."\r\n";
  print "	vr = vr.replace( '/', '' ); "."\r\n";
  print "	vr = vr.replace( '/', '' ); "."\r\n";
  print "	tam = vr.length + 1; "."\r\n";
  print "	if (tecla == 8 ) tam = tam - 1 ; "."\r\n";
  print "	if ( tecla != 9 && tecla != 8 ) { "."\r\n";
  print "	   if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ) { "."\r\n";
  print "		   if ( tam <= 2 ) campo.value = vr ; "."\r\n";
  print "		   if ( tam > 2 && tam < 5 ) campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, tam ); "."\r\n";
  print "		   if ( tam >= 5 && tam <= 10 ) campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, tam );  "."\r\n";
  print "      } "."\r\n";
  print "   } "."\r\n";
  print "} "."\r\n";
}

function FormataHora() {
 print "function FormataHora(campo, teclapres) { " ."\r\n";
 print "    var tecla = teclapres.keyCode; " ."\r\n";
 print "    vr = campo.value; " ."\r\n";
 print "    vr = vr.replace( ':', '' ); " ."\r\n";
 print "    tam = vr.length + 1; " ."\r\n";
 print "    if (tecla == 8 ){    tam = tam - 1 ; } " ."\r\n";
 print "    if ( tecla != 9 && tecla != 8 ){ " ."\r\n";
 print "    if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ " ."\r\n";
 print "        if ( tam <= 2 ) campo.value = vr ; " ."\r\n";
 print "        if ( tam > 2 ) campo.value = vr.substr( 0, 2 ) + ':' + vr.substr( 2, tam ); " ."\r\n";
 print "    } " ."\r\n";
 print "  } " ."\r\n";
 print "} " ."\r\n";
}

function FormataMat() {
  print "function FormataMat (campo,teclapres) { "."\r\n";
  print "	var tecla = teclapres.keyCode; "."\r\n";
  print "	vr = campo.value; "."\r\n";
  print "	vr = vr.replace( '-', '' ); "."\r\n";
  print "	tam = vr.length; "."\r\n";
  print "	if (tam < 9 && tecla != 7){ tam = vr.length + 1 ; } "."\r\n";
  print "	if (tecla == 7 ){	tam = tam - 1 ; } "."\r\n";
  print "	if ( tecla == 7 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ "."\r\n";
  print "	 	if ( (tam > 8) && (tam <= 10) ){ "."\r\n";
  print "	 		campo.value = vr.substr( 0, tam - 1 ) + '-' + vr.substr( tam - 1, tam ) ; } "."\r\n";
  print "	} "."\r\n";
  print "} "."\r\n";
}

function CriticaNumero() {
  print "function CriticaNumero(campo, teclapres) { "."\r\n";
  print "	var tecla = teclapres.keyCode; "."\r\n";
  print "     alert(tecla); "."\r\n";
  print " }"."\r\n";
}

function DaysLeft() {
  print " function DaysLeft(date) { "."\r\n";
  print "  var now = date.getDate(); "."\r\n";
  print "  var year = date.getYear(); "."\r\n";
  print "  if (year < 2000) year += 1900; // Y2K fix "."\r\n";
  print "  var month = date.getMonth(); "."\r\n";
  print "  var monarr = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); "."\r\n";
  print "  if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) monarr[1] = '29'; "."\r\n";
  print "  return (monarr[month]-now); "."\r\n";
  print " } "."\r\n";
}

function FormataValor() {
  ShowHTML("function FormataValor(campo, maximo, tammax, teclapres) {");
  ShowHTML("	var tecla = teclapres.keyCode;");
  ShowHTML("	ant_vr = campo.value;");
  ShowHTML("	vr = campo.value;");
  ShowHTML("	vr = vr.replace( ',', '' );");
  ShowHTML("	vr = vr.replace( '.', '' );");
  ShowHTML("	vr = vr.replace( '.', '' );");
  ShowHTML("	vr = vr.replace( '.', '' );");
  ShowHTML("	vr = vr.replace( '.', '' );");
  ShowHTML("	tam = vr.length + 1;");
  ShowHTML("	if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }");
  ShowHTML("	if (tecla == 8 ) { tam = tam - 1 ; }");
  ShowHTML("	if (tecla == 8 && tam < 1) { vr.value = ''; return true; }");
  ShowHTML("	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){");
  ShowHTML("		if ( tam <= tammax ){ campo.value = vr ; } ");
  ShowHTML("	 	if ( (tam > tammax) && (tam <= (tammax + 3)) ){");
  ShowHTML("	 		campo.value = vr.substr( 0, tam - tammax ) + ',' + vr.substr( tam - tammax, tam ) ; }");
  ShowHTML("	 	if ( (tam >= (tammax+4)) && (tam <= (tammax + 6)) ){");
  ShowHTML("	 		campo.value = vr.substr( 0, tam - (tammax + 3) ) + '.' + vr.substr( tam - (tammax + 3), 3 ) + ',' + vr.substr( tam - tammax, tam ) ; }");
  ShowHTML("	 	if ( (tam >= (tammax + 7)) && (tam <= (tammax + 9)) ){");
  ShowHTML("	 		campo.value = vr.substr( 0, tam - (tammax + 6) ) + '.' + vr.substr( tam - (tammax + 6), 3 ) + '.' + vr.substr( tam - (tammax + 3), 3 ) + ',' + vr.substr( tam - tammax, tam ) ; }");
  ShowHTML("	 	if ( (tam >= (tammax + 10)) && (tam <= (tammax + 12)) ){");
  ShowHTML("	 		campo.value = vr.substr( 0, tam - (tammax + 9) ) + '.' + vr.substr( tam - (tammax + 9), 3 ) + '.' + vr.substr( tam - (tammax + 6), 3 ) + '.' + vr.substr( tam - (tammax + 3), 3 ) + ',' + vr.substr( tam - tammax, tam ) ; }");
  ShowHTML("	 	if ( (tam >= (tammax + 13)) && (tam <= (tammax + 15)) ){");
  ShowHTML("	 		campo.value = vr.substr( 0, tam - (tammax + 12) ) + '.' + vr.substr( tam - (tammax + 12), 3 ) + '.' + vr.substr( tam - (tammax + 9), 3 ) + '.' + vr.substr( tam - (tammax + 6), 3 ) + '.' + vr.substr( tam - (tammax + 3), 3 ) + ',' + vr.substr( tam - tammax, tam ) ;}");
  ShowHTML("	  if ( campo.value.length + 1 > maximo ) { campo.value = ant_vr.substr(0, maximo -1); }");
  ShowHTML("	}");
  ShowHTML("}");
}

function FormataCPF() {
  print "function FormataCPF (campo,teclapres) { "."\r\n";
  print "	var tecla = teclapres.keyCode; "."\r\n";
  print "	vr = campo.value; "."\r\n";
  print "	vr = vr.replace( '-', '' ); "."\r\n";
  print "	vr = vr.replace( '.', '' ); "."\r\n";
  print "	vr = vr.replace( '.', '' ); "."\r\n";
  print "	tam = vr.length; "."\r\n";
  print "	if (tam < 11 && tecla != 8){ tam = vr.length + 1 ; } "."\r\n";
  print "	if (tecla == 8 ){	tam = tam - 1 ; } "."\r\n";
  print "	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ "."\r\n";
  print "		if ( tam <= 2 ){ "."\r\n";
  print "	 		campo.value = vr ; } "."\r\n";
  print "	 	if ( (tam > 2) && (tam <= 5) ){ "."\r\n";
  print "	 		campo.value = vr.substr( 0, tam - 2 ) + '-' + vr.substr( tam - 2, tam ) ; } "."\r\n";
  print "	 	if ( (tam >= 6) && (tam <= 8) ){ "."\r\n";
  print "	 		campo.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + '-' + vr.substr( tam - 2, tam ) ; } "."\r\n";
  print "	 	if ( (tam >= 9) && (tam <= 11) ){ "."\r\n";
  print "	 		campo.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + '-' + vr.substr( tam - 2, tam ) ; } "."\r\n";
  print "	} "."\r\n";
  print "} "."\r\n";
}

function FormataCNPJ() {
  print "function FormataCNPJ (campo,teclapres) { "."\r\n";
  print "	var tecla = teclapres.keyCode; "."\r\n";
  print "	vr = campo.value; "."\r\n";
  print "	vr = vr.replace( '-', '' ); "."\r\n";
  print "	vr = vr.replace( '/', '' ); "."\r\n";
  print "	vr = vr.replace( '.', '' ); "."\r\n";
  print "	vr = vr.replace( '.', '' ); "."\r\n";
  print "	tam = vr.length; "."\r\n";
  print "	if (tam < 14 && tecla != 8){ tam = vr.length + 1 ; } "."\r\n";
  print "	if (tecla == 8 ){	tam = tam - 1 ; } "."\r\n";
  print "	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ "."\r\n";
  print "		if ( tam <= 2 ){ "."\r\n";
  print "	 		campo.value = vr ; } "."\r\n";
  print "	 	if ( (tam > 2) && (tam <= 5) ){ "."\r\n";
  print "	 		campo.value = vr.substr( 0, 2 ) + '.' + vr.substr( 2, tam ) ; } "."\r\n";
  print "	 	if ( (tam >= 6) && (tam <= 8) ){ "."\r\n";
  print "	 		campo.value = vr.substr( 0, 2 ) + '.' + vr.substr( 2, 3 ) + '.' + vr.substr( 5, tam ) ; } "."\r\n";
  print "	 	if ( (tam >= 9) && (tam <= 12) ){ "."\r\n";
  print "	 		campo.value = vr.substr( 0, 2 ) + '.' + vr.substr( 2, 3 ) + '.' + vr.substr( 5, 3 ) + '/' + vr.substr( 8, tam ) ; } "."\r\n";
  print "	 	if ( (tam >= 13) && (tam <= 14) ){ "."\r\n";
  print "	 		campo.value = vr.substr( 0, 2 ) + '.' + vr.substr( 2, 3 ) + '.' + vr.substr( 5, 3 ) + '/' + vr.substr( 8, 4 ) + '-' + vr.substr( 12, tam ) ; } "."\r\n";
  print "	} "."\r\n";
  print "} "."\r\n";
}

function FormataCEP() {
  print "function FormataCEP (campo,teclapres) { "."\r\n";
  print "	var tecla = teclapres.keyCode; "."\r\n";
  print "	vr = campo.value; "."\r\n";
  print "	vr = vr.replace( '.', '' ); "."\r\n";
  print "	vr = vr.replace( '-', '' ); "."\r\n";
  print "	tam = vr.length; "."\r\n";
  print "	if (tam < 2 && tecla != 8){ tam = vr.length + 1 ; } "."\r\n";
  print "	if (tecla == 8 ){	tam = tam - 1 ; } "."\r\n";
  print "	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ "."\r\n";
  print "	 	if ( (tam <= 5) ){ campo.value = vr ; } "."\r\n";
  print "	 	else { campo.value = vr.substr( 0, 5 )  + '-' + vr.substr( 5, tam ); } "."\r\n";
  print "	} "."\r\n";
  print "} "."\r\n";
}

function FormataDataHora() {
  print "function FormataDataHora(campo, teclapres) { "."\r\n";
  print "	var tecla = teclapres.keyCode; "."\r\n";
  print "	vr = campo.value; "."\r\n";
  print "	vr = vr.replace( ':', '' ); "."\r\n";
  print "	vr = vr.replace( ',', '' ); "."\r\n";
  print "	vr = vr.replace( ' ', '' ); "."\r\n";
  print "	vr = vr.replace( '/', '' ); "."\r\n";
  print "	vr = vr.replace( '/', '' ); "."\r\n";
  print "	tam = vr.length + 1; "."\r\n";
  print "	if (tecla == 8 ){	tam = tam - 1 ; } "."\r\n";
  print "	if ( tecla != 9 && tecla != 8 ){ "."\r\n";
  print "	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ "."\r\n";
  print "		if ( tam <= 2 ){ "."\r\n";
  print "	 		campo.value = vr ; } "."\r\n";
  print "		if ( tam > 2 && tam < 5 ) "."\r\n";
  print "			campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, tam ); "."\r\n";
  print "		if ( tam >= 5 && tam < 10 ) "."\r\n";
  print "			campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, tam );  "."\r\n";
  print "		if ( tam >=10 && tam <= 11 ) "."\r\n";
  print "			campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, 4 ) + ', ' + vr.substr( 8, tam );  "."\r\n";
  print "		if ( tam >=12 ) "."\r\n";
  print "			campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, 4 ) + ', ' + vr.substr( 8, 2 ) + ':' + vr.substr( 10, tam );  "."\r\n";
  print "    } "."\r\n";
  print "  } "."\r\n";
  print "} "."\r\n";
}

function FormataDataMA() {
  print "function FormataDataMA(campo, teclapres) { "."\r\n";
  print "	var tecla = teclapres.keyCode; "."\r\n";
  print "	vr = campo.value; "."\r\n";
  print "	vr = vr.replace( '/', '' ); "."\r\n";
  print "	tam = vr.length + 1; "."\r\n";
  print "	if (tecla == 8 ){	tam = tam - 1 ; } "."\r\n";
  print "	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ "."\r\n";
  print "		if ( tam <= 2 ) campo.value = vr ;  "."\r\n";
  print "		if ( tam > 2 ) campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, tam ); "."\r\n";
  print "    } "."\r\n";
  print "} "."\r\n";
}

// Abre a tag SCRIPT
function Validate($VariableName,$DisplayName,$DataType,$ValueRequired,$MinimumLength,$MaximumLength,$AllowLetters,$AllowDigits) {
  if ($ValueRequired>"") {
    if (strtoupper($DataType)=="SELECT") { print "  if (theForm.".$VariableName.".selectedIndex == 0)"."\r\n"; }
    else { 
      print "  theForm.".$VariableName.".value = Trim(theForm.".$VariableName.".value);"."\r\n"; 
      print "  if (theForm.".$VariableName.".value == '')"."\r\n"; 
    }

    print "  {"."\r\n";
    print "    alert('Favor informar um valor para o campo ".$DisplayName."');"."\r\n";
    if (strtoupper($DataType)!="HIDDEN") { print "    theForm.".$VariableName.".focus();"."\r\n"; }
    print "    return (false);"."\r\n";
    print "  }"."\r\n";
    print "\r\n";
  }

  if ($MinimumLength>"") {
    print "  if (theForm.".$VariableName.".value.length < ".$MinimumLength." && theForm.".$VariableName.".value != '')"."\r\n";
    print "  {"."\r\n";
    print "    alert('Favor digitar pelo menos ".$MinimumLength." posiÁıes no campo ".$DisplayName."');"."\r\n";
    if (strtoupper($DataType)!="HIDDEN") { print "    theForm.".$VariableName.".focus();"."\r\n"; }
    print "    return (false);"."\r\n";
    print "  }"."\r\n";
    print "\r\n";
  }

  if ($MaximumLength>"") {
    print "  if (theForm.".$VariableName.".value.length > ".$MaximumLength." && theForm.".$VariableName.".value != '')"."\r\n";
    print "  {"."\r\n";
    print "    alert('Favor digitar no m·ximo ".$MaximumLength." posiÁıes no campo ".$DisplayName."');"."\r\n";
    if (strtoupper($DataType)!="HIDDEN") { print "    theForm.".$VariableName.".focus();"."\r\n"; }
    print "    return (false);"."\r\n";
    print "  }"."\r\n";
    print "\r\n";
  }

  if ($AllowLetters>"" || $AllowDigits>"") {
    $checkOK="";
    if ($AllowLetters>"") {
      if ($AllowLetters=='1') { $checkOK='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz¿¡¬√«»… ÃÕŒ“”‘’Ÿ⁄€‹¿»Ã“Ÿ¬ Œ‘€‡·‚„ÁÈÍÌÓÛÙı˙˚¸‡ËÏÚÏ‚ÍÓÙ˚0123456789-ñ,.()-:;[]{}*&%$#@!/∫™?<>|+=_\\ìî"\\\' '; }
      else { $checkOK=$checkOK.$AllowLetters; }
    }

    if ($AllowDigits>"") {
      if ($AllowDigits=="1") { $checkOK=$checkOK."0123456789-.,/: "; }
      else { $checkOK=$checkOK.$AllowDigits; }
    }

    print "  var checkOK = '".$checkOK."';"."\r\n";
    print "  var checkStr = theForm.".$VariableName.".value;"."\r\n";
    print "  var allValid = true;"."\r\n";
    print "  for (i = 0;  i < checkStr.length;  i++)"."\r\n";
    print "  {"."\r\n";
    print "    ch = checkStr.charAt(i);"."\r\n";
    print "    if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != '\\\\')) {"."\r\n";
    print "       for (j = 0;  j < checkOK.length;  j++) {"."\r\n";
    print "         if (ch == checkOK.charAt(j))"."\r\n";
    print "           break;"."\r\n";
    print "       } "."\r\n";
    print "       if (j == checkOK.length)"."\r\n";
    print "       {"."\r\n";
    print "         allValid = false;"."\r\n";
    print "         break;"."\r\n";
    print "       }"."\r\n";
    print "    } "."\r\n";
    print "  }"."\r\n";
    print "  if (!allValid)"."\r\n";
    print "  {"."\r\n";
    if     ($AllowLetters>"" && $AllowDigits>"")  { print "    alert('VocÍ digitou caracteres inv·lidos no campo ".$DisplayName.".');"."\r\n"; }
    elseif ($AllowLetters>"" && $AllowDigits=="") { 
      if ($AllowLetters == "1") { 
        print "    alert('Favor digitar apenas letras no campo ".$DisplayName.".');"."\r\n"; 
      } else {
        print "    alert('O campo ".$DisplayName." aceita apenas os caracteres abaixo.\\n".$AllowLetters."');"."\r\n"; 
      }
    } elseif ($AllowLetters=="" && $AllowDigits>"") { print "    alert('Favor digitar apenas n˙meros no campo ".$DisplayName.".');"."\r\n"; }

    if (strtoupper($DataType)!="HIDDEN") { print "    theForm.".$VariableName.".focus();"."\r\n"; }
    print "    return (false);"."\r\n";
    print "  }"."\r\n";
  }

  if (strtoupper($DataType)=="CGC" || strtoupper($DataType)=="CNPJ") {
    $checkOK="";
    print
    "    var allValid = true;"."\r\n".
    "    var soma = 0;"."\r\n".
    "    var D1 = 0;"."\r\n".
    "    var D2 = 0;"."\r\n".
    "    var checkStr = theForm.".$VariableName.".value;"."\r\n".
    "    checkStr = checkStr.replace('.','');"."\r\n".
    "    checkStr = checkStr.replace('.','');"."\r\n".
    "    checkStr = checkStr.replace('.','');"."\r\n".
    "    checkStr = checkStr.replace('/','');"."\r\n".
    "    checkStr = checkStr.replace('-','');"."\r\n".
    "    for (i = 1;  i < 13;  i++)"."\r\n".
    "    {"."\r\n".
    "      if (i < 5) { soma = soma + (checkStr.charAt(i-1)*(6-i)); }"."\r\n".
    "      else { soma = soma + (checkStr.charAt(i-1)*(14-i)); }"."\r\n".
    "    }"."\r\n".
    "    D1 = modulo(soma,11)"."\r\n".
    "    if (D1 > 9) { D1 = 0}"."\r\n".
    "    soma = 0;"."\r\n".
    "    for (i = 1;  i < 14;  i++)"."\r\n".
    "    {"."\r\n".
    "      if (i < 6) { soma = soma + (checkStr.charAt(i-1)*(7-i)); }"."\r\n".
    "      else { soma = soma + (checkStr.charAt(i-1)*(15-i)); }"."\r\n".
    "    }"."\r\n".
    "    D2 = modulo(soma,11)"."\r\n".
    "    if (D2 > 9) { D2 = 0}"."\r\n".
    "    if (D1 == checkStr.charAt(13-1) && D2 == checkStr.charAt(14-1)) { allValid = true}"."\r\n".
    "    else { allValid = false }"."\r\n".
    "    if (!allValid) {"."\r\n".
    "       alert('".$DisplayName." inv·lido.');"."\r\n".
    "       theForm.".$VariableName.".focus();"."\r\n".
    "       return (false);"."\r\n".
    "    }"."\r\n";
  }
  elseif (strtoupper($DataType)=="CPF") {
    $checkOK="";
    print
    "    var igual = 0;"."\r\n".
    "    var allValid = true;"."\r\n".
    "    var soma = 0;"."\r\n".
    "    var D1 = 0;"."\r\n".
    "    var D2 = 0;"."\r\n".
    "    var checkStr = theForm.".$VariableName.".value;"."\r\n".
    "    checkStr = checkStr.replace('.','');"."\r\n".
    "    checkStr = checkStr.replace('.','');"."\r\n".
    "    checkStr = checkStr.replace('-','');"."\r\n".
    "    igual = 0;"."\r\n".
    "    for (i = 1;  i < 10;  i++)"."\r\n".
    "    {"."\r\n".
    "      soma = soma + (checkStr.charAt(i-1)*(11-i));"."\r\n".
    "      if (checkStr.charAt(i) != checkStr.charAt(i-1)) igual = 1"."\r\n".
    "    }"."\r\n".
    "    if (igual == 0 && checkStr > '') {"."\r\n".
    "       alert('".$DisplayName." inv·lido.');"."\r\n".
    "       theForm.".$VariableName.".focus();"."\r\n".
    "       return (false);"."\r\n".
    "    }"."\r\n".
    "    D1 = modulo(soma,11);"."\r\n".
    "    if (D1 > 9) { D1 = 0}"."\r\n".
    "    soma = 0;"."\r\n".
    "    for (i = 1;  i < 11;  i++)"."\r\n".
    "    {"."\r\n".
    "      soma = soma + (checkStr.charAt(i-1)*(12-i));"."\r\n".
    "    }"."\r\n".
    "    D2 = modulo(soma,11)"."\r\n".
    "    if (D2 > 9) { D2 = 0}"."\r\n".
    "    if ((D1 == checkStr.charAt(10-1)) && (D2 == checkStr.charAt(11-1))) { allValid = true}"."\r\n".
    "    else { allValid = false }"."\r\n".
    "    if (!allValid && checkStr > '') {"."\r\n".
    "       alert('".$DisplayName." inv·lido.');"."\r\n".
    "       theForm.".$VariableName.".focus();"."\r\n".
    "       return (false);"."\r\n".
    "    }"."\r\n".
    "    if (igual == 0 && checkStr > '') {"."\r\n".
    "       alert('".$DisplayName." inv·lido.');"."\r\n".
    "       theForm.".$VariableName.".focus();"."\r\n".
    "       return (false);"."\r\n".
    "    }"."\r\n";
  }
  elseif (strtoupper($DataType)=="DATA") {
    print
    "    var checkStr = theForm.".$VariableName.".value;"."\r\n".
    "    var err=0;"."\r\n".
    "    var psj=0;"."\r\n".
    "    if (checkStr.length != 0) {"."\r\n".
    "       if (!checkbranco(checkStr))"."\r\n".
    "       {"."\r\n".
    "    	   if (checkStr.length != 10) err=1"."\r\n".
    "       	dia = checkStr.substring(0, 2);"."\r\n".
    "       	barra1 = checkStr.substring(2, 3);"."\r\n".
    "       	mes = checkStr.substring(3, 5);"."\r\n".
    "       	barra2 = checkStr.substring(5, 6);"."\r\n".
    "	        ano = checkStr.substring(6, 10);"."\r\n".
    "    	    //verificaÁıes b·sicas"."\r\n".
    "    	    if (mes<1 || mes>12) err = 1;"."\r\n".
    "    	    if (barra1 != '/') err = 1;"."\r\n".
    "    	    if (dia<1 || dia>31) err = 1;"."\r\n".
    "    	    if (barra2 != '/') err = 1;"."\r\n".
    "    	    if (ano<1900 || ano>2900) err = 1;"."\r\n".
    "    	    //verificaÁıes avanÁadas"."\r\n".
    "    	    // mÍs com 30 dias"."\r\n".
    "    	    if (mes==4 || mes==6 || mes==9 || mes==11){"."\r\n".
    "    		   if (dia==31) err=1;"."\r\n".
    "    	    }"."\r\n".
    "    	    // fevereiro e ano bissexto"."\r\n".
    "    	    if (mes==2){"."\r\n".
    "    		    var g=parseInt(ano/4);"."\r\n".
    "    		    if (isNaN(g)) {"."\r\n".
    "    			    err=1;"."\r\n".
    "    		    }"."\r\n".
    "    		    if (dia>29) err=1;"."\r\n".
    "    		    if (dia==29 && ((ano/4)!=parseInt(ano/4))) err=1;"."\r\n".
    "    	    }"."\r\n".
    "       }"."\r\n".
    "       else"."\r\n".
    "       {"."\r\n".
    "    	   err=1;"."\r\n".
    "       }"."\r\n".
    "    }"."\r\n".
    "    if (err==1){"."\r\n".
    "       alert('Campo ".$DisplayName." inv·lido.');"."\r\n".
    "       theForm.".$VariableName.".focus();"."\r\n".
    "       return (false);"."\r\n".
    "    }"."\r\n";
  } elseif (strtoupper($DataType)=="DATAHORA") {
    print
    "    var checkStr = theForm.".$VariableName.".value;"."\r\n".
    "    var err=0;"."\r\n".
    "    var psj=0;"."\r\n".
    "    if (checkStr.length != 0) {"."\r\n".
    "       if (!checkbranco(checkStr))"."\r\n".
    "       {"."\r\n".
    "    	   if (checkStr.length != 17) err=1"."\r\n".
    "       	dia = checkStr.substr(0, 2);"."\r\n".
    "       	barra1 = checkStr.substr(2, 1);"."\r\n".
    "       	mes = checkStr.substr(3, 2);"."\r\n".
    "       	barra2 = checkStr.substr(5, 1);"."\r\n".
    "	        ano = checkStr.substr(6, 4);"."\r\n".
    "	        hora = checkStr.substr(12, 2);"."\r\n".
    "	        minuto = checkStr.substr(15, 2);"."\r\n".
    "    	    //verificaÁıes b·sicas"."\r\n".
    "    	    if (mes<1 || mes>12) err = 1;"."\r\n".
    "    	    if (barra1 != '/') err = 1;"."\r\n".
    "    	    if (dia<1 || dia>31) err = 1;"."\r\n".
    "    	    if (barra2 != '/') err = 1;"."\r\n".
    "    	    if (ano<1900 || ano>2900) err = 1;"."\r\n".
    "    	    if (hora<0 || hora>23) err = 1;"."\r\n".
    "    	    if (minuto<0 || minuto>59) err = 1;"."\r\n".
    "    	    //verificaÁıes avanÁadas"."\r\n".
    "    	    // mÍs com 30 dias"."\r\n".
    "    	    if (mes==4 || mes==6 || mes==9 || mes==11){"."\r\n".
    "    		   if (dia==31) err=1;"."\r\n".
    "    	    }"."\r\n".
    "    	    // fevereiro e ano bissexto"."\r\n".
    "    	    if (mes==2){"."\r\n".
    "    		    var g=parseInt(ano/4);"."\r\n".
    "    		    if (isNaN(g)) {"."\r\n".
    "    			    err=1;"."\r\n".
    "    		    }"."\r\n".
    "    		    if (dia>29) err=1;"."\r\n".
    "    		    if (dia==29 && ((ano/4)!=parseInt(ano/4))) err=1;"."\r\n".
    "    	    }"."\r\n".
    "       }"."\r\n".
    "       else"."\r\n".
    "       {"."\r\n".
    "    	   err=1;"."\r\n".
    "       }"."\r\n".
    "    }"."\r\n".
    "    if (err==1){"."\r\n".
    "       alert('Campo ".$DisplayName." inv·lido.');"."\r\n".
    "       theForm.".$VariableName.".focus();"."\r\n".
    "       return (false);"."\r\n".
    "    }"."\r\n";
  } elseif (strtoupper($DataType)=="DATADM") {
    print
    "    var checkStr = theForm.".$VariableName.".value;"."\r\n".
    "    var err=0;"."\r\n".
    "    var psj=0;"."\r\n".
    "    if (checkStr.length != 0) {"."\r\n".
    "       if (!checkbranco(checkStr))"."\r\n".
    "       {"."\r\n".
    "    	   if (checkStr.length != 5) err=1"."\r\n".
    "       	dia = checkStr.substring(0, 2);"."\r\n".
    "       	barra1 = checkStr.substring(2, 3);"."\r\n".
    "       	mes = checkStr.substring(3, 5);"."\r\n".
    "    	    //verificaÁıes b·sicas"."\r\n".
    "    	    if (mes<1 || mes>12) err = 1;"."\r\n".
    "    	    if (barra1 != '/') err = 1;"."\r\n".
    "    	    if (dia<1 || dia>31) err = 1;"."\r\n".
    "    	    //verificaÁıes avanÁadas"."\r\n".
    "    	    // mÍs com 30 dias"."\r\n".
    "    	    if (mes==4 || mes==6 || mes==9 || mes==11){"."\r\n".
    "    		   if (dia==31) err=1;"."\r\n".
    "    	    }"."\r\n".
    "    	    // fevereiro e ano bissexto"."\r\n".
    "    	    if (mes==2){"."\r\n".
    "    		    var g=parseInt(ano/4);"."\r\n".
    "    		    if (isNaN(g)) {"."\r\n".
    "    			    err=1;"."\r\n".
    "    		    }"."\r\n".
    "    		    if (dia>29) err=1;"."\r\n".
    "    		    if (dia==29 && ((ano/4)!=parseInt(ano/4))) err=1;"."\r\n".
    "    	    }"."\r\n".
    "       }"."\r\n".
    "       else"."\r\n".
    "       {"."\r\n".
    "    	   err=1;"."\r\n".
    "       }"."\r\n".
    "    }"."\r\n".
    "    if (err==1){"."\r\n".
    "       alert('Campo ".$DisplayName." inv·lido.');"."\r\n".
    "       theForm.".$VariableName.".focus();"."\r\n".
    "       return (false);"."\r\n".
    "    }"."\r\n";
  } elseif (strtoupper($DataType)=="DATAMA") {
    print
    "var checkStr = theForm.".$VariableName.".value;"."\r\n".
    "var err=0;"."\r\n".
    "var psj=0;"."\r\n".
    "if (checkStr.length > 0) {"."\r\n".
    "   if (!checkbranco(checkStr))"."\r\n".
    "   {"."\r\n".
    "	   if (checkStr.length != 7) err=1"."\r\n".
    "	     mes = checkStr.substring(0, 2)"."\r\n".
    "	     barra2 = checkStr.substring(2, 3)"."\r\n".
    "	     ano = checkStr.substring(3, 7)"."\r\n".
    "	     if (mes<1 || mes>12) err = 1"."\r\n".
    "	     if (barra2 != '/') err = 1"."\r\n".
    "	     if (ano<1900 || ano>2900) err = 1"."\r\n".
    "   }"."\r\n".
    "   else"."\r\n".
    "   {"."\r\n".
    "	   err=1"."\r\n".
    "   }"."\r\n".
    "}"."\r\n".
    "if (err==1){"."\r\n".
    "   alert('Campo ".$DisplayName." inv·lido.');"."\r\n".
    "   theForm.".$VariableName.".focus();"."\r\n".
    "   return (false);"."\r\n".
    "}"."\r\n";
  }
}
?>