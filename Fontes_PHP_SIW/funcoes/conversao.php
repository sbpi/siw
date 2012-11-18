<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
include_once($w_dir_volta.'classes/sp/db_getMoedaCotacao.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');

// =========================================================================
//  conversao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Converte moedas
// Mail     : alex@sbpi.com.br
// Criacao  : 17/11/2012, 15:51
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------

$w_pagina     = 'conversao.php';

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$w_cliente    = RetornaCliente();
$w_form       = nvl($_REQUEST['w_form'], $_REQUEST['form']);
$w_campo      = nvl($_REQUEST['w_campo'], $_REQUEST['field']);
$w_data       = nvl($_REQUEST['w_data'], $_REQUEST['vData']);
$w_valor      = nvl($_REQUEST['w_valor'], $_REQUEST['vValor']);
$w_origem     = nvl($_REQUEST['w_origem'], $_REQUEST['vOrigem']);
$w_destino    = nvl($_REQUEST['w_destino'], $_REQUEST['vDestino']);
$w_taxa       = $_REQUEST['w_taxa'];

$SQL = new db_getMoeda; 

$RS_Origem = $SQL->getInstanceOf($dbms,$w_origem,null,null,null,null);
foreach ($RS_Origem as $row) {$RS_Origem = $row; break;}

$RS_Destino = $SQL->getInstanceOf($dbms,$w_destino,null,null,null,null);
foreach ($RS_Destino as $row) {$RS_Destino = $row; break;}

$sql = new db_getMoedaCotacao; 

$RS_CotOri = $sql->getInstanceOf($dbms,$w_cliente,null,$w_origem,$w_data,$w_data,'ANTERIOR');
foreach ($RS_CotOri as $row) {$RS_CotOri = $row; break;}

$RS_CotDes = $sql->getInstanceOf($dbms,$w_cliente,null,$w_destino,$w_data,$w_data,'ANTERIOR');
foreach ($RS_CotDes as $row) {$RS_CotDes = $row; break;}

// Verifica se há cotação que permita a conversão de valores
$w_erro = false;

if (f($RS_Origem,'sigla')=='BRL') {
  if (count($RS_CotDes)==0) {
    $w_erro = true;
  } else {
    $w_compra = f($RS_CotDes,'taxa_compra');
    $w_venda  = f($RS_CotDes,'taxa_venda');
    if (nvl($w_taxa,'')!='') {
      $w_conversao = conversao($w_cliente, $w_data, $w_origem, $w_destino, $w_valor, $w_taxa);
    }
  }
} elseif (f($RS_Destino,'sigla')=='BRL') {
  if (count($RS_CotOri)==0) {
    $w_erro = true;
  } else {
    $w_compra = f($RS_CotOri,'taxa_compra');
    $w_venda  = f($RS_CotOri,'taxa_venda');
    if (nvl($w_taxa,'')!='') {
      $w_conversao = conversao($w_cliente, $w_data, $w_origem, $w_destino, $w_valor, $w_taxa);
    }
  }
} else {
  if (count($RS_CotDes)==0 || count($RS_CotOri)==0) {
    $w_erro = true;
  } else {
    $w_compra = f($RS_CotOri,'taxa_compra') / f($RS_CotDes,'taxa_compra');
    $w_venda  = f($RS_CotOri,'taxa_venda') / f($RS_CotDes,'taxa_venda');
    if (nvl($w_taxa,'')!='') {
      $w_conversao = conversao($w_cliente, $w_data, $w_origem, $w_destino, $w_valor, $w_taxa);
    }
  }
}

cabecalho();
head();
ScriptOpen('JavaScript'); 
ShowHTML('function sendToForm(val,field,form) { '); 
ShowHTML('  eval(\'opener.document.\' + form+\'.\'+field+\'.\'+\'value="\'+val+\'"\'); ');
ShowHTML('  window.close(); '); 
ShowHTML('} '); 
ScriptClose();
ShowHTML('<link rel="stylesheet" href="classes/menu/xPandMenu.css" />');
ShowHTML('</head>');
bodyOpen(null);
ShowHTML('<form action="'.$w_pagina.'" method="get" target="_self">');
ShowHTML('<input type="hidden" name="form" id="word" value="'.$w_form.'">');
ShowHTML('<input type="hidden" name="field" id="word" value="'.$w_campo.'">');
ShowHTML('<input type="hidden" name="w_data" id="word" value="'.$w_data.'">');
ShowHTML('<input type="hidden" name="w_valor" id="word" value="'.$w_valor.'">');
ShowHTML('<input type="hidden" name="w_origem" id="word" value="'.$w_origem.'">');
ShowHTML('<input type="hidden" name="w_destino" id="word" value="'.$w_destino.'">');
ShowHTML('<table width="100%" border="0" align="center" CELLSPACING=0 CELLPADDING=0 BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>');
ShowHTML('  <tr><td align="center" colspan="2" bgcolor="#DAEABD"><font size="2"><b>'.$w_data.'</b></font></td></tr>');
ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td>Valor a ser convertido</td><td><b>'.f($RS_Origem,'sigla').' '.$w_valor.'</b></td></tr>');
if ($w_erro) {
  ShowHTML('  <tr valign="top"><td colspan="2" align="center"><hr>');
  ShowHTML('    <B><font size=1>Não há cotação na data informada. Clique <a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> para fechar esta janela</font></b>');
  ShowHTML('    </td>');
  ShowHTML('  </tr>');
} else {
  ShowHTML('  <tr valign="top" bgcolor="'.$conTrAlternateBgColor.'">');
  ShowHTML('    <td>Taxa de conversão para '.f($RS_Destino,'nome').':</td>');
  ShowHTML('    <td>');
  ShowHTML('      <input type="radio" name="w_taxa" value="C" '.(($w_taxa=='C') ? 'checked' : '').' onClick="submit();">Compra ('.formatNumber($w_compra,4).')');
  ShowHTML('      <br><input type="radio" name="w_taxa" value="V" '.(($w_taxa=='V') ? 'checked' : '').' onClick="submit();">Venda ('.formatNumber($w_venda,4).')');
  ShowHTML('    </td>');
  ShowHTML('  </tr>');

  if (nvl($w_taxa,'')!='') {
    ShowHTML('  <tr valign="top"><td colspan="2" align="center"><hr>');
    $w_conversao = formatNumber(floor(str_replace(',','.',str_replace('.','',$w_conversao))*100)/100);
    ShowHTML('    <B><font size=2>Valor convertido: '.$w_conversao.'</b>');
    ShowHTML('    <br />Clique <a class="hl" href="javascript:sendToForm(\''.$w_conversao.'\',\''.$w_campo.'\',\''.$w_form.'\');">aqui</a> transferir o valor para o formulário.</font></b>');
    ShowHTML('    </td>');
    ShowHTML('  </tr>');
  }
}
ShowHTML('</table>');
ShowHTML('</form>');
ShowHTML('</body>');
Rodape();
?>