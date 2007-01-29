<?
//---------------------------Definições do BODY

$conBodyText		= '#000000';
$conBodyLink		= '#0000FF';
$conBodyALink		= '#0000FF';
$conBodyVLink		= '#0000FF';
$conBodyBgColor		= '#F8F8FF';
$conBodyBackground	= '';
$conBodyBgproperties	= 'fixed';
$conBodyTopmargin	= '5';
$conBodyLeftmargin	= '5';

//---------------------------Definições de Tabela

$conTableHeader			= '#CCCCCC';
$conTableBgColor		= '#F7F7F7';
$conTableAlternateBgColor	= '#E1F2FF';
$conTableBorder			= '0';
$conTableBorderColorDark	= '#000000';
$conTableBorderColor		= '#000000';
$conTableBorderColorlight	= '#FFFFFF';
$conTableCellPadding		= '1';
$conTableCellSpacing		= '1';
$conTrBgColor			= '#EFEFEF';
$conTrTotalBgColor		= '#55BBFF';
$conTrAlternateBgColor		= '#E0E0E0';
$conTrRelatorioBgColor		= '#E1F2FF';
$conTrBgColorLightBlue1		= '#77AAFF';
$conTrBgColorLightRed1		= '#FF9999';
$conTrBgColorLightBlue2		= '#AADDFF';
$conTrBgColorLightRed2		= '#FFCCCC';

//---------------------------Definições de fontes

$conFontSize		= '2';
$conFontFace		= 'Arial, Helvetica, sans-serif';
$conFontColor		= '#000000';
$conTituloFontSize	= '3';
$conTituloFontFace	= 'Arial';
$conTituloFontColor	= '#008000';

//---------------------------Definições de imagens

$conImgOkNormal	= 'images/tickverde.gif';
$conImgOkAtraso	= 'images/tickvermelho.gif';
$conImgNormal	= 'images/BallGreen.png';
$conImgAviso	= 'images/BallYellow.png';
$conImgAtraso	= 'images/BallRed.png';
$conImgCancel 	='images/icone/REQUIRED.GIF';


//---------------------------Definições de banco

$strschema	= 'siw.';
$strschema_is	= 'siw_is.';
$conPageSize	= 30;

//---------------------------Configuração do ambiente do sistema

// Define se o serviço de envio automático de e-mails deve ser ativado (true) fou não (false)
$conEnviaMail   = true;

$crlf           = chr(13).chr(10);
$conSgSistema   = 'SIW';
$conDefaultPath = 'http://www.sbpi.com.br';
$conNmSistema   = 'Solução Integrada Web';
$conRootSIW     = 'http://www2.sbpi.com.br/siw/';
$conDiretorio   = '/var/www/html/siw/';
$conFileVirtual = '/siw_files/';
$conFilePhysical= '/var/www/html/siw_files/'; // Formato Linux
//$conFilePhysical= 'c:/inetpub/wwwroot/siw_Files/'; // Formato Windows (IIS)
?>
