
<!--
function makeArray(len)
{
 for (var i=0; i<len; i++) this[i] = null;
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
!-->
