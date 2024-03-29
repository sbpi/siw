<?php
//---------------------------Defini��es do BODY
$conBodyText		= '#000000';
$conBodyLink		= '#0000FF';
$conBodyALink		= '#0000FF';
$conBodyVLink		= '#0000FF';
$conBodyBgColor		= '#F8F8FF';
$conBodyBackground	= '';
$conBodyBgproperties	= 'fixed';
$conBodyTopmargin	= '5';
$conBodyLeftmargin	= '5';

//---------------------------Defini��es de Tabela
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
$conTrBgColorLightBlue1		= '#BBBBFF';
$conTrBgColorLightRed1		= '#FF9999';
$conTrBgColorLightBlue2		= '#AADDFF';
$conTrBgColorLightRed2		= '#FFCCCC';
$conTrBgColorLightYellow1	= '#FFFFCC';
$conTrBgColorLightYellow2	= '#FFFF99';
$conTrBgColorLightGreen1	= '#98FB98';
$conTrBgColorLightGreen2	= '#00FF7F';

//---------------------------Defini��es de fontes
$conFontSize		= '2';
$conFontFace		= 'Arial, Helvetica, sans-serif';
$conFontColor		= '#000000';
$conTituloFontSize	= '3';
$conTituloFontFace	= 'Arial';
$conTituloFontColor	= '#008372';

//---------------------------Defini��es de imagens
$conImgAlHigh	= 'images/icone/alert_animated.gif';
$conImgAlMed	= 'images/icone/alert_red.gif';
$conImgAlLow	= 'images/icone/alert_yellow.gif';

$conImgOkAcima	= 'images/tickazul.gif';
$conImgOkNormal	= 'images/tickverde.gif';
$conImgOkAtraso	= 'images/tickvermelho.gif';

$conImgStAcima	= 'images/icone/arrow_blue.gif';
$conImgStNormal	= 'images/icone/arrow_green.gif';
$conImgStAviso	= 'images/icone/arrow_yellow.gif';
$conImgStAtraso	= 'images/icone/arrow_red.gif';

$conImgNormal	= 'images/BallGreen.gif';
$conImgAviso	= 'images/BallYellow.gif';
$conImgAtraso	= 'images/BallRed.gif';
$conImgCancel 	='images/icone/REQUIRED.GIF';
$conImgProblem  ='images/icone/problem.gif';
$conImgRiskLow  ='images/icone/risk_low.gif';
$conImgRiskMed  ='images/icone/risk_low.gif';
$conImgRiskHig  ='images/icone/risk_medium.gif';
$conImgDownload ='images/icone/application_put.png';

//$conImgSmNormal = 'images/icone/smile_approve.gif';
//$conImgSmAviso  = 'images/icone/smile_sleepy.gif';
//$conImgSmAtraso = 'images/icone/smile_dissapprove.gif';

$conImgSmNormal = 'images/BallGreen.gif';
$conImgSmAviso  = 'images/BallYellow.gif';
$conImgSmAtraso = 'images/BallRed.gif';

$conImgGeo      = 'images/icone/earth_16.png';
$conImgFin      = 'images/icone/dollar-green.png';
$conImgSheet     = 'images/Folder/SheetImg.gif';

//---------------------------Defini��es de banco

$strschema	= '';
$strschema_is	= 'siw_is.';
$conPageSize	= 30;
$conRefreshSec  = 1000;

//---------------------------Configura��o do ambiente do sistema

// Define se o servi�o de envio autom�tico de e-mails deve ser ativado (true) fou n�o (false)
$conEnviaMail   = false;

$crlf           = chr(13).chr(10);
$conSgSistema   = 'SIW';
$conDefaultPath = 'http://jupiter.sbpi1.com.br';
$conNmSistema   = 'Solu��o Integrada Web';
$conRootSIW     = 'http://jupiter.sbpi1.com.br/siw_otca/';

if (strpos(PHP_OS, "W")===0) { // Windows
    $conApacheStat  = 'http://jupiter.sbpi1.com.br/server-status-sbpi';
    $conDiretorio   = 'c:/www/siw_otca/';
    $conFontPath    = $conDiretorio.'fonts/linux/';
    $conFileVirtual = '/siw_files/';
    $conFilePhysical= 'c:/www/siw_files/';
} else {
    $conDiretorio   = '/var/www/html/siw_otca/';
    $conFileVirtual = '/siw_files/';
    $conFilePhysical= '/var/www/html/siw_files/'; // Formato Linux
}

// Define se deve ser gravado arquivo de log e o seu caminho
$conLog         = true;
$conLogPath     = $conFilePhysical.'log/';
?>
