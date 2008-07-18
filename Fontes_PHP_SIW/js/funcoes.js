/*
objetivo: mascarar de acordo com a mascara passada
caracteres: # - caracter a ser mascarado
| - separador de mascaras
modos (exemplos):
mascara simples: "###-####"	                 mascara utilizando a mascara passada
mascara composta: "###-####|####-####"       mascara de acordo com o tamanho (length) do valor passado
mascara din?mica: "[###.]###,##"             multiplica o valor entre colchetes de acordo com o length do valor para que a mascara seja din?mica ex: ###.###.###.###,##
utilizar no onkeyup do objeto
ex: onkeyup="this.value = mascara_global('#####-###',this.value);"
tratar o maxlength do objeto na p?gina (a fun??o n?o trata isso)
onkeyup="this.value = mascaraGlobal('###.###.###-##',this.value);"

onkeyup="this.value = mascaraGlobal('##.###.###/####-##',this.value);"

onkeyup="this.value = mascaraGlobal('#####-###',this.value);"

onkeyup="this.value = mascaraGlobal('####-####',this.value);"

onkeyup="this.value = mascaraGlobal('##/##/####',this.value);"

*/
function mascaraGlobal(mascara, valor)
{
	var mascara_utilizar;
	var mascara_limpa;
	var temp;
	var i;
	var j;
	var caracter;
	var separador;
	var dif;
	var validar;
	var mult;
	var ret;
	var tam;
	var tvalor;
	var valorm;
	var masct;
	tvalor = "";
	ret = "";
	caracter = "#";
	separador = "|";
	mascara_utilizar = "";
	valor = trim(valor);
	if (valor == "")return valor;
	temp = mascara.split(separador);
	dif = 1000;

	valorm = valor;
	//tirando mascara do valor já existente
	for (i=0;i<valor.length;i++){
		if (!isNaN(valor.substr(i,1))){
			tvalor = tvalor + valor.substr(i,1);
		}
	}
	valor = tvalor;

	//formatar mascara dinamica
	for (i = 0; i<temp.length;i++){
		mult = "";
		validar = 0;
		for (j=0;j<temp[i].length;j++){
			if (temp[i].substr(j,1) == "]"){
				temp[i] = temp[i].substr(j+1);
				break;
			}
			if (validar == 1)mult = mult + temp[i].substr(j,1);
			if (temp[i].substr(j,1) == "[")validar = 1;
		}
		for (j=0;j<valor.length;j++){
			temp[i] = mult + temp[i];
		}
	}


	//verificar qual mascara utilizar
	if (temp.length == 1){
		mascara_utilizar = temp[0];
		mascara_limpa = "";
		for (j=0;j<mascara_utilizar.length;j++){
			if (mascara_utilizar.substr(j,1) == caracter){
				mascara_limpa = mascara_limpa + caracter;
			}
		}
		tam = mascara_limpa.length;
	}else{
		//limpar caracteres diferente do caracter da m?scara
		for (i=0;i<temp.length;i++){
			mascara_limpa = "";
			for (j=0;j<temp[i].length;j++){
				if (temp[i].substr(j,1) == caracter){
					mascara_limpa = mascara_limpa + caracter;
				}
			}

			if (valor.length > mascara_limpa.length){
				if (dif > (valor.length - mascara_limpa.length)){
					dif = valor.length - mascara_limpa.length;
					mascara_utilizar = temp[i];
					tam = mascara_limpa.length;
				}
			}else if (valor.length < mascara_limpa.length){
				if (dif > (mascara_limpa.length - valor.length)){
					dif = mascara_limpa.length - valor.length;
					mascara_utilizar = temp[i];
					tam = mascara_limpa.length;
				}
			}else{
				mascara_utilizar = temp[i];
				tam = mascara_limpa.length;
				break;
			}
		}
	}

	//validar tamanho da mascara de acordo com o tamanho do valor
	if (valor.length > tam){
		valor = valor.substr(0,tam);
	}else if (valor.length < tam){
		masct = "";
		j = valor.length;
		for (i = mascara_utilizar.length-1;i>=0;i--){
			if (j == 0) break;
			if (mascara_utilizar.substr(i,1) == caracter){
				j--;
			}
			masct = mascara_utilizar.substr(i,1) + masct;
		}
		mascara_utilizar = masct;
	}

	//mascarar
	j = mascara_utilizar.length -1;
	for (i = valor.length - 1;i>=0;i--){
		if (mascara_utilizar.substr(j,1) != caracter){
			ret = mascara_utilizar.substr(j,1) + ret;
			j--;
		}
		ret = valor.substr(i,1) + ret;
		j--;
	}
	return ret;
}
//tirar os espaços das extremidades do valor passado (utilizada pela mascaraglobal)
function trim(s) {
	return (s ? '' + s : '').replace(/^\s*|\s*$/g, '');
}

function repeteCaracter(car,qtd){
	var saida = ""
	var i =0;
	for(i=0;i<qtd;i++)
	{ 
		saida += car;
	}
	return saida;
}

messageObj = new DHTML_modalMessage();	// We only create one object of this class
messageObj.setShadowOffset(5);	// Large shadow

function displayMessage(x,y,url)
{
	messageObj.setSource(url);
	messageObj.setCssClassMessageBox(false);
	messageObj.setSize(x,y);
	messageObj.setShadowDivVisible(true);	// Enable shadow for these boxes
	messageObj.display();
}

function displayStaticMessage(messageContent,cssClass)
{
	messageObj.setHtmlContent(x,ymessageContent);
	messageObj.setSize(x,y);
	messageObj.setCssClassMessageBox(cssClass);
	messageObj.setSource(false);	// no html source since we want to use a static message here.
	messageObj.setShadowDivVisible(false);	// Disable shadow for these boxes	
	messageObj.display();		
}

function closeMessage()
{
	messageObj.close();	
}