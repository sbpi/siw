<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getMenuCode.php');
include_once('classes/sp/db_getMenuData.php');
include_once('classes/sp/db_getUorgList.php');
include_once('classes/sp/db_getUorgData.php');
include_once('classes/sp/db_getAddressList.php');
include_once('classes/sp/db_getUorgResp.php');
include_once('classes/sp/db_verificaAssinatura.php');
include_once('classes/sp/dml_EoUnidade.php');
include_once('classes/sp/dml_EoLocal.php');
include_once('classes/sp/dml_EoResp.php');
include_once('funcoes/selecaoTipoUnidade.php');
include_once('funcoes/selecaoEOAreaAtuacao.php');
include_once('funcoes/selecaoUnidadePai.php');
include_once('funcoes/selecaoUnidadePag.php');
include_once('funcoes/selecaoUnidadeGest.php');
include_once('funcoes/selecaoEndereco.php');
include_once('funcoes/selecaoUsuUnid.php');

// =========================================================================
//  /eo.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Estrutura organizacional
// Mail     : alex@sbpi.com.br
// Criacao  : 30/07/2001 08:05PM
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = C   : Cancelamento
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);

$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'eo.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir_volta    = '';
$w_troca        = $_REQUEST['w_troca'];

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP = $TP.' - Inclusão'; break;
  case 'A': $w_TP = $TP.' - Alteração'; break;
  case 'E': $w_TP = $TP.' - Exclusão'; break;
  case 'P': $w_TP = $TP.' - Filtragem'; break;
  default : $w_TP = $TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de montagem das unidades
// -------------------------------------------------------------------------
function Unidade() {
  extract($GLOBALS);
  global $w_Disabled;

  $RS = db_getMenuData::getInstanceOf($dbms, $w_menu);
  $w_libera_edicao = f($RS,'libera_edicao');

  cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ShowHTML('  <script src="classes/menu/xPandMenu.js"></script>');
  if (!(strpos('IAE',$O)===false))   {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','3','50','1','1');
      Validate('w_sigla','Sigla','1','1','1','20','1','1');
      Validate('w_ordem','Ordem','1','1','1','2','','1');
      Validate('w_codigo','Código','1','','1','15','','1');
      Validate('w_email','e-Mail','1','','3','60','1','1');
      Validate('w_sq_tipo_unidade','Tipo da unidade','SELECT','1','1','18','','1');
      Validate('w_sq_area_atuacao','Área de atuação','SELECT','1','1','18','','1');
      Validate('w_sq_pessoa_endereco','Endereço unidade','SELECT','1','1','10','','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
    } 
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('L',$O)===false)) {
    $w_imagem=$conRootSIW.'images/ballw.gif';
    ShowHTML('<tr><td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<a accesskey="I" class="ss" href="'.$w_pagina.$par.'&TP='.$TP.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    } 
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="0" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,null,'IS NULL',null,null,null);
    $RS = SortArray($RS,'ordem','asc');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center"><b>Estrutura organizacional inexistente.</b></td></tr>');
    } else {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td valign="center">');
      $w_ContOut=0;
      $w_ContImg=0;
      ShowHTML('<div id="container">');
      ShowHTML('<ul id="XRoot" class="XtreeRoot">');
      foreach($RS as $row) {
        $w_ContImg += 1;
        $w_ContOut += 1;
        ShowHTML('<li id="Xnode" class="Xnode" nowrap><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row,'NOME').'</span> ');
        if ($w_libera_edicao=='S') {
          ShowHTML('<A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
          ShowHTML('<A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        } 
        ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Locais</a>&nbsp');
        ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>');
        ShowHTML('</li>');
        ShowHTML('   <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:true;">');
        $RS1 = db_getUorgList::getInstanceOf($dbms, $w_cliente,f($row,'sq_unidade'),'FILHO',null,null,null);
        $RS1 = SortArray($RS1,'ordem','asc');
        foreach($RS1 as $row1) {
          $w_ContImg += 1;
          $w_ContOut += 1;
          ShowHTML('   <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row1,'NOME').'</span> ');
          if ($w_libera_edicao=='S') {
            ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row1,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
            ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row1,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
          } 
          ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row1,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
          ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row1,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
          ShowHTML('   </li>');
          ShowHTML('      <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:none;">');
          $RS2 = db_getUorgList::getInstanceOf($dbms,$w_cliente,f($row1,'sq_unidade'),'FILHO',null,null,null);
          $RS2 = SortArray($RS2,'ordem','asc');
          foreach($RS2 as $row2) {
            $w_ContImg += 1;
            $w_ContOut += 1;
            ShowHTML('         <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row2,'NOME').'</span> ');
            if ($w_libera_edicao=='S') {
              ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row2,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
              ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row2,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
            } 
            ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row2,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
            ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row2,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
            ShowHTML('         </li>');
            ShowHTML('            <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:none;">');
            $RS3 = db_getUorgList::getInstanceOf($dbms,$w_cliente,f($row2,'sq_unidade'),'FILHO',null,null,null);
            $RS3 = SortArray($RS3,'ordem','asc');
            foreach($RS3 as $row3) {
              $w_ContImg += 1;
              $w_ContOut += 1;
              ShowHTML('            <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row3,'NOME').'</span> ');
              if ($w_libera_edicao=='S') {
                ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row3,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
                ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row3,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
              } 
              ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row3,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
              ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row3,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
              ShowHTML('            </li>');
              ShowHTML('               <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:none;">');
              $RS4 = db_getUorgList::getInstanceOf($dbms,$w_cliente,f($row3,'sq_unidade'),'FILHO',null,null,null);
              $RS4 = SortArray($RS4,'ordem','asc');
              foreach($RS4 as $row4) {
                $w_ContImg += 1;
                $w_ContOut += 1;
                ShowHTML('               <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row4,'NOME').'</span> ');
                if ($w_libera_edicao=='S') {
                  ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row4,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
                  ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row4,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
                } 
                ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row4,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
                ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row4,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
                ShowHTML('               </li>');
                ShowHTML('                  <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:none;">');
                $RS5 = db_getUorgList::getInstanceOf($dbms,$w_cliente,f($row4,'sq_unidade'),'FILHO',null,null,null);
                $RS5 = SortArray($RS5,'ordem','asc');
                foreach($RS5 as $row5) {
                  $w_ContImg += 1;
                  $w_ContOut += 1;
                  ShowHTML('                  <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row5,'NOME').'</span> ');
                  if ($w_libera_edicao=='S') {
                    ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row5,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
                    ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row5,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
                  } 
                  ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row5,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
                  ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row5,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
                  ShowHTML('                  </li>');
                  ShowHTML('                     <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:none;">');
                  $RS6 = db_getUorgList::getInstanceOf($dbms,$w_cliente,f($row5,'sq_unidade'),'FILHO',null,null,null);
                  $RS6 = SortArray($RS6,'ordem','asc');
                  foreach($RS6 as $row6) {
                    $w_ContImg += 1;
                    $w_ContOut += 1;
                    ShowHTML('                     <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row6,'NOME').'</span> ');
                    if ($w_libera_edicao=='S') {
                      ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row6,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
                      ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row6,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
                    } 
                    ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row6,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
                    ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row6,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
                    ShowHTML('                     </li>');
                    ShowHTML('                        <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:none;">');
                    $RS7 = db_getUorgList::getInstanceOf($dbms,$w_cliente,f($row6,'sq_unidade'),'FILHO',null,null,null);
                    $RS7 = SortArray($RS7,'ordem','asc');
                    foreach($RS7 as $row7) {
                      $w_ContImg += 1;
                      $w_ContOut += 1;
                      ShowHTML('                        <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row7,'NOME').'</span> ');
                      if ($w_libera_edicao=='S') {
                        ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row7,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
                        ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row7,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
                      } 
                      ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row7,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
                      ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row7,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
                      ShowHTML('                        </li>');
                      ShowHTML('                           <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:none;">');
                      $RS8 = db_getUorgList::getInstanceOf($dbms,$w_cliente,f($row7,'sq_unidade'),'FILHO',null,null,null);
                      $RS8 = SortArray($RS8,'ordem','asc');
                      foreach($RS8 as $row8) {
                        $w_ContImg += 1;
                        $w_ContOut += 1;
                        ShowHTML('                           <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row8,'NOME').'</span> ');
                        if ($w_libera_edicao=='S') {
                          ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row8,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
                          ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row8,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
                        } 
                        ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row8,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
                        ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row8,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
                        ShowHTML('                           </li>');
                        ShowHTML('                              <ul id="Xtree'.$w_ContOut.'" class="Xtree" style="display:none;">');
                        $RS9 = db_getUorgList::getInstanceOf($dbms,$w_cliente,f($row8,'sq_unidade'),'FILHO',null,null,null);
                        $RS9 = SortArray($RS9,'ordem','asc');
                        foreach($RS9 as $row9) {
                          $w_ContImg += 1;
                          $w_ContOut += 1;
                          ShowHTML('                              <li id="Xnode" class="Xnode"><span onClick="xSwapImg(document.getElementById(\'Ximg'.$w_ContImg.'\'),\''.$w_imagem.'\',\''.$w_imagem.'\');xMenuShowHide(document.getElementById(\'Xtree'.$w_ContOut.'\'));"><img id="Ximg'.$w_ContImg.'" src="'.$w_imagem.'" border="0">&nbsp;'.f($row9,'NOME').'</span> ');
                          if ($w_libera_edicao=='S') {
                            ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.f($row9,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
                            ShowHTML(' <A class="Xlink" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.f($row9,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
                          } 
                          ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Localizacao&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Localização&O=L&SG=LUORG&w_sq_unidade='.f($row9,'sq_unidade').'\',\'Local\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Locais</a>&nbsp');
                          ShowHTML('<a class="Xlink" href="#" onclick="window.open(\''.$w_pagina.'Responsavel&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade='.f($row9,'sq_unidade').'\',\'Responsaveis\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Responsáveis</a>&nbsp');
                          ShowHTML('                              </li>');
                        } 
                      } 
                      ShowHTML('                        </ul>');
                    } 
                    ShowHTML('                     </ul>');
                  } 
                  ShowHTML('                  </ul>');
                } 
                ShowHTML('               </ul>');
              } 
              ShowHTML('            </ul>');
            } 
            ShowHTML('         </ul>');
          } 
          ShowHTML('      </ul>');
        } 
        ShowHTML('   </ul>');
      } 
      ShowHTML('</ul>');
      ShowHTML('</span>');
    } 
    ShowHTML('    </table>');
  //INCLUSÃO
  } elseif (!(strpos('EIA',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    if (!(strpos('EA',$O)===false)) {
      $w_sq_unidade = $_REQUEST['w_sq_unidade'];
      $RS = db_getUorgData::getInstanceOf($dbms,$w_sq_unidade);
      $w_nome                   = f($RS,'nome');
      $w_sigla                  = f($RS,'sigla');
      $w_ordem                  = f($RS,'ordem');
      $w_informal               = f($RS,'informal');
      $w_vinculada              = f($RS,'vinculada');
      $w_adm_central            = f($RS,'adm_central');
      $w_sq_unidade_gestora     = f($RS,'sq_unidade_gestora');
      $w_sq_unidade_pagadora    = f($RS,'sq_unid_pagadora');
      $w_sq_area_atuacao        = f($RS,'sq_area_atuacao');
      $w_sq_unidade_pai         = f($RS,'sq_unidade_pai');
      $w_sq_pessoa_endereco     = f($RS,'sq_pessoa_endereco');
      $w_sq_tipo_unidade        = f($RS,'sq_tipo_unidade');
      $w_unidade_gestora        = f($RS,'unidade_gestora');
      $w_externo                = f($RS,'externo');
      $w_ativo                  = f($RS,'ativo');
      $w_codigo                 = f($RS,'codigo');
      $w_unidade_pagadora       = f($RS,'unidade_pagadora');
      $w_email                  = f($RS,'email');
    } 
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="50" maxlength="50" value="'.$w_nome.'"></td>');
    ShowHTML('      <tr align="left"><td valign="top"><table width="100%" cellpadding=0 cellspacing=0><tr>');
    ShowHTML('        <td valign="top"><b><U>S</U>igla:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_sigla" size="20" maxlength="20" value="'.$w_sigla.'"></td>');
    ShowHTML('        <td valign="top"><b><U>O</U>rdem:<br><INPUT ACCESSKEY="O" '.$w_Disabled.' class="sti" type="text" name="w_ordem" size="2" maxlength="2" value="'.$w_ordem.'"></td>');
    ShowHTML('        <td valign="top"><b><U>C</U>ódigo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_codigo" size="15" maxlength="15" value="'.$w_codigo.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr><td valign="top"><b><U>e</U>-Mail:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="sti" type="text" name="w_email" size="60" maxlength="60" value="'.$w_email.'"></td></tr>');
    ShowHTML('      <tr>');
    SelecaoTipoUnidade('<u>T</u>ipo Unidade:','T',null,$w_sq_tipo_unidade,$w_cliente,'w_sq_tipo_unidade',null);
    SelecaoEOAreaAtuacao('Á<u>r</u>ea Atuação:','R',null,$w_sq_area_atuacao,$w_cliente,'w_sq_area_atuacao',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoUnidadePai('Unidade <u>p</u>ai:','P',null,$w_sq_unidade_pai,$O,$w_cliente,$w_sq_unidade,'w_sq_unidade_pai',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoUnidadeGest('Unidade <u>g</u>estora:','G',null,$w_sq_unidade_gestora,$w_sq_unidade,'w_sq_unidade_gestora',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoUnidadePag('Unidade p<u>a</u>gadora:','A',null,$w_sq_unidade_pagadora,$w_sq_unidade,'w_sq_unidade_pagadora',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoEndereco('En<u>d</u>ereço principal:','d',null,$w_sq_pessoa_endereco,$w_cliente,'w_sq_pessoa_endereco','FISICO');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left"><td valign="top"><table width="100%" cellpadding=0 cellspacing=0><tr>');
    MontaRadioNS('<b>Informal:</b>',$w_informal,'w_informal');
    MontaRadioNS('<b>Vinculada:</b>',$w_vinculada,'w_vinculada');
    MontaRadioSN('<b>Adm. Central:</b>',$w_adm_central,'w_adm_central');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioNS('<b>Unidade Gestora:</b>',$w_unidade_gestora,'w_unidade_gestora');
    MontaRadioNS('<b>Unidade Pagadora:</b>',$w_unidade_pagadora,'w_unidade_pagadora');
    MontaRadioNS('<b>Externa:</b>',$w_externo,'w_externo');
    ShowHTML('      </tr></table></td></tr>');
    MontaRadioSN('<b>Ativo:</b>',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td valign="top"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina da tabela de localização
// -------------------------------------------------------------------------
function Localizacao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_sq_unidade = $_REQUEST['w_sq_unidade'];
  $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_sq_unidade,null,null,null,null);
  foreach ($RS as $row) {
    $w_nome_unidade = f($row,'nome');
  }
  if ($O=='L') {
    $RS = db_getaddressList::getInstanceOf($dbms,$w_cliente,$w_sq_unidade,'LISTALOCALIZACAO',null);
  } elseif (($O=='A' || $O=='E')) {
    $w_sq_localizacao       = $_REQUEST['w_sq_localizacao'];
    
    $RS = db_getaddressList::getInstanceOf($dbms,$w_cliente,$w_sq_localizacao,'LOCALIZACAO',null);
    foreach ($RS as $row) {
      $w_sq_localizacao       = f($row,'sq_localizacao');
      $w_sq_pessoa_endereco   = f($row,'sq_pessoa_endereco');
      $w_sq_unidade           = f($row,'sq_unidade');
      $w_nome                 = f($row,'nome');
      $w_fax                  = f($row,'fax');
      $w_telefone             = f($row,'telefone');
      $w_ramal                = f($row,'ramal');
      $w_telefone2            = f($row,'telefone2');
      $w_ativo                = f($row,'ativo');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_pessoa_endereco','Endereço','SELECT','1','1','18','','1');
      Validate('w_nome','Localização','1','1','3','30','1','1');
      Validate('w_telefone','Telefone','1','','1','12','','1');
      Validate('w_ramal','Ramal','1','','1','6','','1');
      Validate('w_fax','Fax','1','','1','12','','1');
      Validate('w_telefone2','Telefone','1','','1','12','','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ShowHTML('  theForm.Botao[2].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<TITLE>'.$conSgSistema.' - Localizações</TITLE>');
  ShowHTML('</HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_sq_pessoa_endereco.focus()\';');
    }  
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=4 align="center"><font size="2"><b>'.$w_nome_unidade.'&nbsp;');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_unidade='.$w_sq_unidade.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <a accesskey="F" class="ss" href="#" onClick="opener.focus(); window.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Localização</td>');
    ShowHTML('          <td><b>Cidade</td>');
    ShowHTML('          <td><b>Telefone</td>');
    ShowHTML('          <td><b>Ramal</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="left">'.f($row,'cidade').'</td>');
        ShowHTML('        <td align="center">'.f($row,'telefone').'&nbsp;</td>');
        ShowHTML('        <td align="center">'.f($row,'ramal').'&nbsp;</td>');
        ShowHTML('        <td align="center">'.f($row,'ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_localizacao='.f($row,'sq_localizacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_unidade='.$w_sq_unidade.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_localizacao='.f($row,'sq_localizacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_unidade='.$w_sq_unidade.'">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_localizacao" value="'.$w_sq_localizacao.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr>');
    SelecaoEndereco('En<u>d</u>ereço:','D',null,$w_sq_pessoa_endereco,$w_cliente,'w_sq_pessoa_endereco','FISICO');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>L</U>ocalização:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td valign="top"><table width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>T</U>elefone:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" name="w_telefone" size="12" maxlength="12" value="'.$w_telefone.'"></INPUT></td>');
    ShowHTML('          <td><b><U>R</U>amal:<br><INPUT ACCESSKEY="R" '.$w_Disabled.' class="sti" name="w_ramal" size="6" maxlength="6" value="'.$w_ramal.'"></INPUT></td>');
    ShowHTML('          <td><b><U>F</U>ax:<br><INPUT ACCESSKEY="F" '.$w_Disabled.' class="sti" type="text" name="w_fax" size="12" maxlength="12" value="'.$w_fax.'"></td>');
    ShowHTML('          <td><b>T<U>e</U>lefone 2:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="sti" name="w_telefone2" size="12" maxlength="12" value="'.$w_telefone2.'"></INPUT></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo:</b>',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_unidade='.$w_sq_unidade.'\';" name="Botao" value="Cancelar">');
    ShowHTML('            <input class="stb" type="button" onClick="opener.focus(); window.close();" name="Botao" value="Fechar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
} 

// =========================================================================
// Rotina da tabela de responsavel
// -------------------------------------------------------------------------
function Responsavel() {
  extract($GLOBALS);
  global $w_Disabled;

  $SG           = 'RESPONSAVEL';
  $w_sq_unidade = $_REQUEST['w_sq_unidade'];
  $p_sq_pessoa  = $_REQUEST['p_sq_pessoa'];
  $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_sq_unidade,null,null,null,null);
  foreach ($RS as $row) {
    $w_nome_unidade = f($row,'nome');
  }
  if ($O=='L') {
    $RS = db_getUorgResp::getInstanceOf($dbms,$w_sq_unidade);
    foreach ($RS as $row) {
      $w_titular      = f($row,'titular2');
      $w_substituto   = f($row,'substituto2');
    }
  } elseif (($O=='A' || $O=='E')) {
    $RS = db_getUorgResp::getInstanceOf($dbms,$w_sq_unidade);
    foreach ($RS as $row) {
      $w_sq_pessoa            = f($row,'titular2');
      $w_sq_pessoa_substituto = f($row,'substituto2');
      $w_inicio_titular       = Nvl(f($row,'inicio_titular'),time());
      if (f($row,'inicio_substituto')>'') $w_inicio_substituto = f($row,'inicio_substituto');
    }
  } elseif ($O=='I') {
    $w_inicio_titular = date('d/m/Y',time());
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_pessoa','Pessoa titular','SELECT','1','1','10','','1');
      Validate('w_inicio_titular','Início titular','DATA','1','10','10','','0123456789/');
      Validate('w_fim_titular','Início titular','DATA','','10','10','','0123456789/');
      CompData('w_inicio_titular','Início titular','<=','w_fim_titular','Início titular');
      Validate('w_sq_pessoa_substituto','Pessoa substituto','SELECT','','1','10','','1');
      Validate('w_inicio_substituto','Início substituto','DATA','','10','10','','0123456789/');
      Validate('w_fim_substituto','Início substituto','DATA','','10','10','','0123456789/');
      CompData('w_inicio_substituto','Início substituto','<=','w_fim_substituto','Início substituto');
      ShowHTML('  if (theForm.w_sq_pessoa_substituto.selectedIndex > 0 && theForm.w_inicio_substituto.value == \'\') {');
      ShowHTML('     alert(\'Informe a data de início do substituto!\');');
      ShowHTML('     theForm.w_inicio_substituto.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm.w_sq_pessoa_substituto.selectedIndex == 0) {');
      ShowHTML('        theForm.w_inicio_substituto.value = \'\';');
      ShowHTML('        theForm.w_fim_substituto.value = \'\';');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_sq_pessoa(theForm.w_sq_pessoa.selectedIndex).value == theForm.w_sq_pessoa_substituto(theForm.w_sq_pessoa_substituto.selectedIndex).value) { ');
      ShowHTML('     alert(\'A mesma pessoa não pode ser indicada para titular e substituto de uma unidade!\');');
      ShowHTML('     theForm.w_sq_pessoa_substituto.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ShowHTML('  theForm.Botao[2].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<TITLE>'.$conSgSistema.' - Responsáveis</TITLE>');
  ShowHTML('</HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_sq_pessoa.focus()\';');
    } 
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=4 align=center><font size="2"><b>'.$w_nome_unidade.'&nbsp;');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_unidade='.$w_sq_unidade.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <a accesskey="F" class="ss" href="#" onClick="opener.focus(); window.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Titular</td>');
    ShowHTML('          <td><b>Substituto</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=3 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if (Nvl($w_titular,0)==0 && Nvl($w_substituto,0)==0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=3 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        foreach($RS as $row) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('        <td align="left">'.f($row,'titular1').'</td>');
          ShowHTML('        <td align="left">'.f($row,'substituto1').'</td>');
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_unidade='.$w_sq_unidade.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Alterar</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_unidade='.$w_sq_unidade.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Excluir</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<FORM action="'.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="w_titular_ant" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_substituto_ant" value="'.$w_sq_pessoa_substituto.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan=3><font color="#FF0000"><b>ATENÇÃO: antes de alterar o titular ou o substituto da unidade, informe a data de término da responsabilidade do ocupante atual, grave e entre novamente na opção de alteração.</b></td></tr>');
    ShowHTML('      <tr>');
    SelecaoUsuUnid('<u>T</u>itular:','T',null,$w_sq_pessoa,null,'w_sq_pessoa',$O);
    ShowHTML('          <td valign="top"><b>A partir <U>d</U>e:<br><INPUT TYPE="TEXT" ACCESSKEY="D" '.$w_Disabled.' class="sti" name="w_inicio_titular" size="10" maxlength="10" value="'.FormataDataEdicao($w_inicio_titular).'" onKeyDown="FormataData(this,event);">');
    ShowHTML('          <td valign="top"><b>A<U>t</U>é:<br><INPUT TYPE="TEXT" ACCESSKEY="T" '.$w_Disabled.' class="sti" name="w_fim_titular" size="10" maxlength="10" value="'.$w_fim_titular.'" onKeyDown="FormataData(this,event);">');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoUsuUnid('<u>S</u>ubstituto:','S',null,$w_sq_pessoa_substituto,null,'w_sq_pessoa_substituto',$O);
    ShowHTML('          <td valign="top"><b>A partir <U>d</U>e:<br><INPUT TYPE="TEXT" ACCESSKEY="D" '.$w_Disabled.' class="sti" name="w_inicio_substituto" size="10" maxlength="10" value="'.FormataDataEdicao($w_inicio_substituto).'" onKeyDown="FormataData(this,event);">');
    ShowHTML('          <td valign="top"><b>A<U>t</U>é:<br><INPUT TYPE="TEXT" ACCESSKEY="T" '.$w_Disabled.' class="sti" name="w_fim_substituto" size="10" maxlength="10" value="'.$w_fim_substituto.'" onKeyDown="FormataData(this,event);">');
    ShowHTML('      <tr><td valign="top" colspan=3><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_unidade='.$w_sq_unidade.'\';" name="Botao" value="Cancelar">');
    ShowHTML('            <input class="stb" type="button" onClick="opener.focus(); window.close();" name="Botao" value="Fechar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
} 

// =========================================================================
// Rotina de busca das unidades da organização
// -------------------------------------------------------------------------
function BuscaUnidade() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ano        = $_REQUEST['w_ano'];
  $w_nome       = strtoupper($_REQUEST['w_nome']);
  $w_sigla      = strtoupper($_REQUEST['w_sigla']);
  $w_cliente    = $_REQUEST['w_cliente'];
  $chaveaux     = $_REQUEST['chaveaux'];
  $restricao    = $_REQUEST['restricao'];
  $campo        = $_REQUEST['campo'];

  $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$chaveaux,nvl($restricao,'ATIVO'),$w_nome,$w_sigla,$w_ano);
  $RS = SortArray($RS,'nome','asc', 'co_uf', 'asc');
  Cabecalho();
  ShowHTML('<TITLE>Seleção de unidade</TITLE>');
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_nome, l_sigla, l_chave) {');
  ShowHTML("     opener.document.Form.".$campo."_nm.value=l_nome.replace('\'','\"') + ' (' + l_sigla + ')';");
  ShowHTML('     opener.document.Form.'.$campo.'.value=l_chave;');
  ShowHTML('     opener.document.Form.'.$campo.'_nm.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  if (count($RS)>100 || ($w_nome>'' || $w_sigla>'')) {
    ValidateOpen('Validacao');
    Validate('w_nome','Nome','1','','4','30','1','1');
    Validate('w_sigla','Sigla','1','','2','20','1','1');
    ShowHTML('  if (theForm.w_nome.value == \'\' && theForm.w_sigla.value == \'\') {');
    ShowHTML('     alert (\'Informe um valor para o nome ou para a sigla!\');');
    ShowHTML('     theForm.w_nome.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
  } 
  ScriptClose();
  ShowHTML('</HEAD>');
  if (count($RS)>100 || ($w_nome>'' || $w_sigla>'')) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  if (count($RS)>100 || ($w_nome>'' || $w_sigla>'')) {
    AbreForm('Form',$w_pagina.'BuscaUnidade','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="chaveaux" value="'.$chaveaux.'">');
    ShowHTML('<INPUT type="hidden" name="restricao" value="'.$restricao.'">');
    ShowHTML('<INPUT type="hidden" name="campo" value="'.$campo.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome da unidade.<li>Quando a relação for exibida, selecione a unidade desejada clicando sobre a caixa ao seu lado.<li>Após informar o nome da unidade, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><b>Parte do <U>n</U>ome da unidade:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="50" maxlength="50" value="'.$w_nome.'">');
    ShowHTML('      <tr><td valign="top"><b><U>S</U>igla  da unidade:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_sigla" size="20" maxlength="20" value="'.$w_sigla.'">');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
    if ($w_nome>'' || $w_sigla>'') {
      ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
      ShowHTML('<tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" border=0>');
      if (count($RS)<=0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Sigla</td>');
        ShowHTML('            <td><b>Nome</td>');
        ShowHTML('            <td><b>Endereço</td>');
        ShowHTML('            <td><b>Cidade</td>');
        ShowHTML('            <td><b>Operações</td>');
        ShowHTML('          </tr>');
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('            <td align="center">'.f($row,'sigla').'</td>');
          ShowHTML('            <td>'.f($row,'nome').'</td>');
          ShowHTML('            <td>'.f($row,'logradouro').'</td>');
          ShowHTML('            <td>'.f($row,'nm_cidade').'-'.f($row,'co_uf').'</td>');
          ShowHTML('            <td><a class="ss" href="#" onClick="javascript:volta(\''.f($row,'nome').'\', \''.f($row,'sigla').'\', '.f($row,'sq_unidade').');">Selecionar</a>');
        } 
        ShowHTML('        </table></tr>');
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      } 
    } 
  } else {
    ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=6>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('            <td><b>Sigla</td>');
      ShowHTML('            <td><b>Nome</td>');
      ShowHTML('            <td><b>Endereço</td>');
      ShowHTML('            <td><b>Cidade</td>');
      ShowHTML('            <td><b>Operações</td>');
      ShowHTML('          </tr>');
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('            <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('            <td>'.f($row,'nome').'</td>');
        ShowHTML('            <td>'.f($row,'logradouro').'</td>');
        ShowHTML('            <td>'.f($row,'nm_cidade').'-'.f($row,'co_uf').'</td>');
        ShowHTML('            <td><a class="ss" href="#" onClick="javascript:volta(\''.f($row,'nome').'\', \''.f($row,'sigla').'\', '.f($row,'sq_unidade').');">Selecionar</a>');
      } 
      ShowHTML('        </table></tr>');
      ShowHTML('      </center>');
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
    } 
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'EOUORG':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_EoUnidade::getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_unidade'],$_REQUEST['w_sq_tipo_unidade'],$_REQUEST['w_sq_area_atuacao'],$_REQUEST['w_sq_unidade_gestora'],
            $_REQUEST['w_sq_unidade_pai'],$_REQUEST['w_sq_unidade_pagadora'],$_REQUEST['w_sq_pessoa_endereco'],
            $_REQUEST['w_ordem'],$_REQUEST['w_email'],$_REQUEST['w_codigo'],$w_cliente,$_REQUEST['w_nome'],
            $_REQUEST['w_sigla'],$_REQUEST['w_informal'],$_REQUEST['w_vinculada'],$_REQUEST['w_adm_central'],
            $_REQUEST['w_unidade_gestora'],$_REQUEST['w_unidade_pagadora'],$_REQUEST['w_externo'],$_REQUEST['w_ativo']);
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
            ScriptClose();
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
            ScriptClose();
            retornaFormulario('w_assinatura');
          } 
          break;
    case 'LUORG':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_EoLocal::getInstanceOf($dbms,$O,
            $_REQUEST['w_sq_localizacao'],$_REQUEST['w_sq_pessoa_endereco'],$_REQUEST['w_sq_unidade'],
            $_REQUEST['w_nome'],$_REQUEST['w_fax'],$_REQUEST['w_telefone'],$_REQUEST['w_ramal'],
            $_REQUEST['w_telefone2'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&w_sq_unidade='.$_REQUEST['w_sq_unidade'].'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'RESPONSAVEL':  //CADASTRO DE REPONSÁVEL
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_EOResp::getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_unidade'],$_REQUEST['w_fim_substituto'],$_REQUEST['w_sq_pessoa_substituto'],$_REQUEST['w_inicio_substituto'],
            $_REQUEST['w_fim_titular'],$_REQUEST['w_sq_pessoa'],$_REQUEST['w_inicio_titular']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&SG='.$SG.'&w_sq_unidade='.$_REQUEST['w_sq_unidade'].'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
  case 'UORG':           Unidade();          break;
  case 'BUSCAUNIDADE':   BuscaUnidade();     break;
  case 'BUSCALCUNIDADE': BuscaLcUnidade;     break;
  case 'LOCALIZACAO':    Localizacao();      break;
  case 'RESPONSAVEL':    Responsavel();      break;
  case 'GRAVA':          Grava();            break;
  default: 
    Cabecalho();
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 
} 
?>
