<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCiaTrans.php');
include_once($w_dir_volta.'classes/sp/db_getMeioTransporte.php');
include_once($w_dir_volta.'classes/sp/db_getValorDiaria.php');
include_once($w_dir_volta.'classes/sp/db_getPDParametro.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getCategoriaDiaria.php');
include_once($w_dir_volta.'classes/sp/db_getDescontoAgencia.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putCiaTrans.php');
include_once($w_dir_volta.'classes/sp/dml_putMeioTrans.php');
include_once($w_dir_volta.'classes/sp/dml_putPDParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUnidade.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUnidLimite.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUsuario.php');
include_once($w_dir_volta.'classes/sp/dml_putCategoriaDiaria.php');
include_once($w_dir_volta.'classes/sp/dml_putValorDiaria.php');
include_once($w_dir_volta.'classes/sp/dml_putDescontoAgencia.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoAno.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoContinente.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDiaria.php');
include_once($w_dir_volta.'funcoes/selecaoCategoriaDiaria.php');
include_once($w_dir_volta.'funcoes/selecaoMoeda.php');


// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia as rotinas de tabelas básicas do módulo de passagens e diárias
// Mail     : celso@sbpi.com.br
// Criacao  : 04/10/2005 11:00
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = P   : Filtragem

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'tabelas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];

if ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';  
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';  break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão';  break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'C': $w_TP=$TP.' - Cópia';     break;
  case 'V': $w_TP=$TP.' - Envio';     break;
  case 'H': $w_TP=$TP.' - Herança';   break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

$p_ordena       = lower($_REQUEST['p_ordena']);

// Recupera os dados do cliente
$RS_Cliente = db_getCustomerData::getInstanceOf($dbms,$w_cliente);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Manter Tabela básica 'PD_CIA_TRANSPORTE'
// -------------------------------------------------------------------------
function CiaTrans() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave=$_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_nome         = $_REQUEST['w_nome'];
    $w_sigla        = $_REQUEST['w_sigla'];
    $w_aereo        = $_REQUEST['w_aereo'];
    $w_rodoviario   = $_REQUEST['w_rodoviario'];
    $w_aquaviario   = $_REQUEST['w_aquaviario'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_ativo        = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false) || nvl($w_troca,'')!='') {
    // Recupera os dados chave informada
    $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave        = f($RS,'chave');
    $w_nome         = f($RS,'nome');
    $w_sigla        = f($RS,'sigla');
    $w_aereo        = f($RS,'aereo');
    $w_rodoviario   = f($RS,'rodoviario');
    $w_aquaviario   = f($RS,'aquaviario');
    $w_padrao       = f($RS,'padrao');
    $w_ativo        = f($RS,'ativo');
    $w_sigla        = f($RS,'sigla');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','2','30','1','1');
      Validate('w_sigla','Sigla','1','1','2','20','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Aéreo','nm_aereo').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Rodoviário','nm_rodoviario').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Aquaviário','nm_aquaviario').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Padrão','nm_padrao').'</font></td>');
    ShowHTML('          <td><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_aereo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_rodoviario').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_aquaviario').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><b><u>N</u>ome:</b><br>     <input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td></tr>');
    ShowHTML('           <tr><td colspan=3><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_sigla.'"></td></tr>');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Aéreo?</b>',$w_aereo,'w_aereo');
    MontaRadioNS('<b>Rodoviário?</b>',$w_rodoviario,'w_rodoviario');
    MontaRadioNS('<b>Aquaviário?</b>',$w_aquaviario,'w_aquaviario');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Padrão?</b>',$w_padrao,'w_padrao');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') { 
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Manter Tabela básica 'PD_CIA_TRANSPORTE'
// -------------------------------------------------------------------------
function Desconto() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave=$_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_desconto     = $_REQUEST['w_desconto'];
    $w_agencia      = $_REQUEST['w_agencia'];
    $w_faixa_inicio = $_REQUEST['w_faixa_inicio'];
    $w_faixa_fim    = $_REQUEST['w_faixa_fim'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getDescontoAgencia::getInstanceOf($dbms,$w_cliente,null,$w_agencia,null,$w_faixa_inicio,$faixa_fim,$desconto,$w_ativo);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc','faixa_inicio','asc');
    } else {
      $RS = SortArray($RS,'nome','asc','faixa_inicio','asc');
    }
  } elseif (!(strpos('AE',$O)===false) || nvl($w_troca,'')!='') {
    // Recupera os dados chave informada
    $RS = db_getDescontoAgencia::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave        = f($RS,'chave');
    $w_agencia      = f($RS,'agencia_viagem');
    $w_desconto     = formatNumber(f($RS,'desconto'),2);
    $w_faixa_inicio = formatNumber(f($RS,'faixa_inicio'),2);
    $w_faixa_fim    = formatNumber(f($RS,'faixa_fim'),2);
    $w_ativo        = f($RS,'ativo');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      //Validate('w_nome','Nome','1','1','2','30','1','1');
      Validate('w_agencia','Agência de viagem','SELECT','1','1','18','','0123456789');
      Validate('w_faixa_inicio','Faixa inicial de desconto','1','1','4','6','','0123456789,');
      Validate('w_faixa_fim','Faixa final de desconto','1','1','4','6','','0123456789,');
      Validate('w_desconto','Desconto da agência','1','1','4','6','','0123456789,');
      CompValor('w_faixa_inicio','Faixa inicial','<','w_faixa_fim','faixa final de desconto');
      CompValor('w_faixa_inicio','Faixa inicial','<=','100','100%');
      CompValor('w_faixa_fim','Faixa final','<=','100','100%');
      CompValor('w_desconto','Desconto da agência','<=','100','100%');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_agencia.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Desconto<br />(Trf Cheia X Trf Aplicada)','faixa_inicio').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Desconto Agência de Viagens','desconto').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'agencia_viagem'),$TP,f($row,'nome_resumido')).'</td>');
        ShowHTML('        <td align="center">'.formatNumber(f($row,'faixa_inicio'),2).'% a '.formatNumber(f($row,'faixa_fim'),2).'%&nbsp;</td>');
        ShowHTML('        <td align="center">'.formatNumber(f($row,'desconto'),2).'%</td>');
        ShowHTML('        <td align="center">'.f($row,'ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%">');
    ShowHTML('       <tr valign="top">');
    SelecaoPessoa('Agê<u>n</u>cia de viagem:','N','Selecione a agência de viagem emissora da fatura.',$w_agencia,null,'w_agencia','FORNECPJ');
    ShowHTML('       <tr>');
    ShowHTML('          <td><b>Faixa de desconto (Tarifa cheia x Tarifa aplicada):</b><br><input type="text" '.$w_Disabled.' accesskey="I" name="w_faixa_inicio" class="sti" SIZE="8" MAXLENGTH="6" VALUE="'.$w_faixa_inicio.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);">%&nbsp;a&nbsp;<input type="text" '.$w_Disabled.' accesskey="F" name="w_faixa_fim" class="sti" SIZE="8" maxlength="6" VALUE="'.$w_faixa_fim.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);">%&nbsp;</td>');
    ShowHTML('       <tr>');
    ShowHTML('          <td><b>Desconto da Agência de viagens:</b><br><input type="text" '.$w_Disabled.' accesskey="D" name="w_desconto" class="sti" SIZE="8" MAXLENGTH="6" VALUE="'.$w_desconto.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);">%</td>');
    ShowHTML('       <tr>');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');    
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') { 
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Manter Tabela básica PD_MEIO_TRANSPORTE
// -------------------------------------------------------------------------
function MeioTrans() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave=$_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_nome         = $_REQUEST['w_nome'];
    $w_aereo        = $_REQUEST['w_aereo'];
    $w_rodoviario   = $_REQUEST['w_rodoviario'];
    $w_ferroviario   = $_REQUEST['w_ferroviario'];
    $w_aquaviario   = $_REQUEST['w_aquaviario'];    
    $w_ativo        = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getMeioTransporte::getInstanceOf($dbms,$w_cliente,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false) || $w_troca > '') {
    // Recupera os dados chave informada
    $RS = db_getMeioTransporte::getInstanceOf($dbms,$w_cliente, null, $w_chave,null,null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave        = f($RS,'chave');
    $w_nome         = f($RS,'nome');
    $w_aereo        = f($RS,'aereo');
    $w_rodoviario   = f($RS,'rodoviario');
    $w_ferroviario   = f($RS,'ferroviario');    
    $w_aquaviario   = f($RS,'aquaviario');
    $w_ativo        = f($RS,'ativo');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','2','30','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Aéreo','aereo').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Rodoviário','rodoviario').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ferroviário','ferroviario').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Aquaviário','aquaviario').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativo').'</font></td>');
    //ShowHTML('          <td><b>'.LinkOrdena('Padrão','nm_padrao').'</font></td>');
    ShowHTML('          <td><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><!>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'aereo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'rodoviario').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ferroviario').'</td>');
        ShowHTML('        <td align="center">'.f($row,'aquaviario').'</td>');
        if (Nvl(f($row,'ativo'),'')=='S') {
          ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        } else {
          ShowHTML('        <td align="center"><font color="red" size="1">'.f($row,'nm_ativo').'</td>');
        } 
        //ShowHTML('        <td align="center">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Aéreo?</b>',$w_aereo,'w_aereo');
    MontaRadioNS('<b>Rodoviário?</b>',$w_rodoviario,'w_rodoviario');
    MontaRadioNS('<b>Ferroviário?</b>',$w_ferroviario,'w_ferroviario');
    MontaRadioNS('<b>Aquaviário?</b>',$w_aquaviario,'w_aquaviario');
    ShowHTML('        <tr valign="top">');    
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') { 
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina para registro das categorias e valores de diárias
// -------------------------------------------------------------------------
function ValorDiaria() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave=$_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_nacional    = $_REQUEST['w_nacional'];
    $w_continente  = $_REQUEST['w_continente'];
    $w_sq_pais     = $_REQUEST['w_sq_pais'];
    $w_sq_cidade   = $_REQUEST['w_sq_cidade'];
    $w_sq_moeda    = $_REQUEST['w_sq_moeda'];
    $w_tipo_diaria = $_REQUEST['w_tipo_diaria'];
    $w_categoria   = $_REQUEST['w_categoria'];
    $w_valor       = $_REQUEST['w_valor'];
    $w_uf          = $_REQUEST['w_uf'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getValorDiaria::getInstanceOf($dbms,$w_cliente,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_continente','desc','nm_pais','asc','nm_cidade','asc','nm_categoria_diaria','asc','nm_tipo_diaria','asc');
    } else {
      $RS = SortArray($RS,'nm_continente','desc','nm_pais','asc','nm_cidade','asc','nm_categoria_diaria','asc','nm_tipo_diaria','asc');
    }
  } elseif (!(strpos('AE',$O)===false) || $w_troca > '') {
    // Recupera os dados chave informada
    $RS = db_getValorDiaria::getInstanceOf($dbms, $w_cliente, $w_chave);
    foreach($RS as $row) { $RS = $row; break; }
    $w_nacional    = f($RS,'nacional');
    $w_continente  = f($RS,'continente');
    $w_pais        = f($RS,'pais');
    $w_sq_pais     = f($RS,'sq_pais');
    $w_sq_cidade   = f($RS,'sq_cidade');
    $w_sq_moeda    = f($RS,'sq_moeda');
    $w_tipo_diaria = f($RS,'tipo_diaria');
    $w_categoria   = f($RS,'sq_categoria_diaria');
    $w_valor       = formatNumber(f($row,'valor'));
    $w_uf          = f($RS, 'nm_uf');
  } 

  if (nvl($w_nacional,'S')=='S') {
    $w_continente = 1;
    $w_sq_pais    = f($RS_Cliente,'sq_pais');
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_categoria','Categoria da diária','SELECT',1,1,18,'','1');
      Validate('w_tipo_diaria','Tipo da diária','SELECT',1,1,1,'1','');
      if ($w_nacional=='N') Validate('w_continente','Continente','SELECT',1,1,1,'','12345');
      Validate('w_uf','Estado','SELECT','',2,2,'1','');
      Validate('w_sq_cidade','Cidade','SELECT','',1,18,'','1');
      ShowHTML('  if (theForm.w_uf.selectedIndex>0 && theForm.w_sq_cidade.selectedIndex==0) {');
      ShowHTML('    alert("Se o estado for informado, é obrigatório indicar a cidade!");');
      ShowHTML('    theForm.w_sq_cidade.focus();');
      ShowHTML('    return false;');
      ShowHTML('  } ');
      Validate('w_sq_moeda','Unidade monetária','SELECT',1,1,18,'','1');
      Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789,.');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'' && $w_troca!='w_continente') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('IA',$O)!==false && $w_troca!='w_continente') {
    BodyOpen('onLoad=\'document.Form.w_categoria.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Continente','nm_continente').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('País','nm_pais').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Cidade','nm_cidade').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Categoria','nm_categoria_diaria').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_diaria').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Moeda','sg_moeda').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</font></td>');
    //ShowHTML('          <td><b>'.LinkOrdena('Padrão','nm_padrao').'</font></td>');
    ShowHTML('          <td><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_continente').'</td>');
        ShowHTML('        <td>'.f($row,'nm_pais').'</td>');
        ShowHTML('        <td>'.f($row,'nm_cidade').'</td>');       
        ShowHTML('        <td>'.f($row,'nm_categoria_diaria').'</td>');
        ShowHTML('        <td>'.f($row,'nm_tipo_diaria').'</td>');
        ShowHTML('        <td width="1%" nowrap align="center" title="'.f($row,'nm_moeda').'">'.f($row,'sg_moeda').'</td>');       
        ShowHTML('        <td align="right">'.((f($row,'tipo_diaria')=='V') ? '-'.formatNumber(f($row,'valor'),0).'%' : formatNumber(f($row,'valor'))) . '</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table cellspacing="3" border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('        <tr valign="top">');
    SelecaoCategoriaDiaria('Ca<u>t</u>egoria da diária:','G',null, $w_cliente, $w_categoria,null,'w_categoria',null,null);
    SelecaoTipoDiaria('Tipo de <u>D</u>iária:','D',null,$w_tipo_diaria,null,'w_tipo_diaria',null,null);
    MontaRadioSN('<b>Diária nacional?</b>',$w_nacional,'w_nacional',null,null,'onClick="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_continente\'; document.Form.submit();"');    
    ShowHTML('        <tr/>');
    ShowHTML('        <tr valign="top">');
    if ($w_nacional == 'N') {
      selecaoContinente('<u>C</u>ontinente:','C','Selecione o continente na relação.',$w_continente,null,'w_continente',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_pais\'; document.Form.submit();"');
      selecaoPais('<u>P</u>aís:','P','Selecione o país na relação.',$w_sq_pais,null,'w_sq_pais','CONTINENTE'.$w_continente,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
    } else {
      ShowHTML('<input type="hidden" name="w_sq_pais" value="'.$w_sq_pais.'"/>');
      ShowHTML('<input type="hidden" name="w_continente" value="'.$w_continente.'"/>');
    }
    SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_sq_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'w_sq_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$w_sq_cidade,$w_sq_pais,$w_uf,'w_sq_cidade',null,null);    
    
    ShowHTML('        <tr valign="top">');
    selecaoMoeda('<u>U</u>nidade monetária:','U','Selecione a unidade monetária na relação.',$w_sq_moeda,null,'w_sq_moeda','ATIVO',null);
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input type="text" '.$w_Disabled.' accesskey="V" name="w_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('  </td>');
    ShowHTML('<tr/>');
        
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') { 
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}


// =========================================================================
// Rotina dos parâmetros
// -------------------------------------------------------------------------
function Parametros() {
  extract($GLOBALS);
  global $w_Disabled;

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_sequencial        = $_REQUEST['w_sequencial'];
    $w_sequencial_atual  = $_REQUEST['w_sequencial_atual'];
    $w_ano_corrente      = $_REQUEST['w_ano_corrente'];
    $w_prefixo           = $_REQUEST['w_prefixo'];
    $w_sufixo            = $_REQUEST['w_sufixo'];
    $w_dias_antecedencia = $_REQUEST['w_dias_antecedencia'];
    $w_dias_anteced_int  = $_REQUEST['w_dias_anteced_int'];
    $w_dias_prest_contas = $_REQUEST['w_dias_prest_contas'];
    $w_limite_unidade    = $_REQUEST['w_limite_unidade'];
  } else {
    // Recupera os dados do parâmetro
    $RS = db_getPDParametro::getInstanceOf($dbms,$w_cliente,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_sequencial         = f($RS,'sequencial');
      $w_sequencial_atual   = f($RS,'sequencial');
      $w_ano_corrente       = f($RS,'ano_corrente');
      $w_prefixo            = f($RS,'prefixo');
      $w_sufixo             = f($RS,'sufixo');
      $w_dias_antecedencia  = f($RS,'dias_antecedencia');
      $w_dias_anteced_int   = f($RS,'dias_antecedencia_int');
      $w_dias_prest_contas  = f($RS,'dias_prestacao_contas');
      $w_limite_unidade     = f($RS,'limite_unidade');
    } 
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  ShowHTML('  if (theForm.w_sequencial_atual.value > \'\'){ ');
  ShowHTML('    if (theForm.w_sequencial.value <  theForm.w_sequencial_atual.value){ ');
  ShowHTML('      alert(\'O número sequencial atual nao pode ser menor que ' + theForm.w_sequencial_atual.value + '!\');');
  ShowHTML('      return false;');
  ShowHTML('    };');
  ShowHTML('  };');
  Validate('w_sequencial','Sequencial','1',1,1,18,'','0123456789');
  //Validate 'w_ano_corrente', 'Ano corrente', '1', 1, 4, 4, '', '0123456789'
  Validate('w_prefixo','Prefixo','1','',1,10,'1','1');
  Validate('w_sufixo','Sufixo','1','',1,10,'1','1');
  Validate('w_dias_antecedencia','Antecedência nacional','1',1,1,3,'','0123456789');
  Validate('w_dias_anteced_int','Antecedência internacional','1',1,1,3,'','0123456789');
  Validate('w_dias_prest_contas','Dias para prestação de contas','1',1,1,3,'','0123456789');
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_sequencial.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_sequencial_atual" value="'.$w_sequencial_atual.'">');
  ShowHTML('<INPUT type="hidden" name="w_ano_corrente" value="'.strftime('%Y',(time())).'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0"><tr><td>');
  ShowHTML('      <table width="100%" border="0">');
  ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Parâmetros</td></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  //ShowHTML '      <tr><td><font size=1>Falta definir a explicação.</font></td></tr>'
  //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
  ShowHTML('      </table>');
  ShowHTML('      <table width="100%" border="0">');
  ShowHTML('      <tr><td><b><u>S</u>equencial:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sequencial" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sequencial.'"></td>');
  //ShowHTML '          <td><font size=''1''><b><u>A</u>no corrente:</b><br><input ' & w_Disabled & ' accesskey=''A'' type=''text'' name=''w_ano_corrente'' class=''sti'' SIZE=''10'' MAXLENGTH=''10'' VALUE=''' & w_ano_corrente & '''></td>'
  ShowHTML('      <tr><td><b><u>P</u>refixo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_prefixo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_prefixo.'"></td>');
  ShowHTML('          <td><b><u>S</u>ufixo:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sufixo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sufixo.'"></td>');
  ShowHTML('      <tr><td><b><u>D</u>ias de antecedência para viagens nacionais:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_dias_antecedencia" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dias_antecedencia.'"></td>');
  ShowHTML('          <td><b><u>D</u>ias de antecedência para viagens internacionais:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_dias_anteced_int" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dias_anteced_int.'"></td>');
  ShowHTML('      <tr><td><b>D<u>i</u>as para prestação de contas:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_dias_prest_contas" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dias_prest_contas.'"></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr>');
  MontaRadioNS('<b>Controla limite orçamentário de passagens e diárias por unidade e ano?</b>',$w_limite_unidade,'w_limite_unidade');
  ShowHTML('      <tr>');
  ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
  // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina de unidade
// -------------------------------------------------------------------------
function Unidade() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_nome             = $_REQUEST['w_nome'];
    $w_sigla            = $_REQUEST['w_sigla'];
    $w_ativo            = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera os parâmetros do módulo
    $RS = db_getPDParametro::getInstanceOf($dbms,$w_cliente,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_limite_unidade     = f($RS,'limite_unidade');
    } 

    // Recupera todos os registros para a listagem
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,null,'VIAGEM',null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_chave,'VIAGEM',null,null,$w_ano);
    foreach($RS as $row) { $RS = $row; break; }
    $w_nome             = f($RS,'nome');
    $w_sigla            = f($RS,'sigla');
    $w_ativo            = f($RS,'ativo');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      if ($O=='I') {
        Validate('w_chave','Unidade','HIDDEN','1','1','50','1','1');
      } 
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('A',$O)===false)) {
    BodyOpen('onLoad=\'this.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen(null);
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Unidade','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativo').'</font></td>');
    ShowHTML('          <td><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').' ('.f($row,'sigla').')</td>');
        if (f($row,'ativo')=='S') {
          ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        } else {
          ShowHTML('        <td align="center"><font size="1" color="red">'.f($row,'nm_ativo').'</td>');
        } 
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        if ($w_limite_unidade=='S') {
          ShowHTML('          <a class="HL" href="javascript:this.status.value;" onclick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'LIMUNIDADE&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PDUNIDLIM').'\',\'Limites\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Limites</a>');
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O!='I') {
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    if ($O=='I') {
      SelecaoUnidade('<U>U</U>nidade:','S',null,$w_chave,null,'w_chave',null,null);
    } else {
      ShowHTML('        <tr><td><font size=1><b>Unidade:<br>'.$w_nome.' ('.$w_sigla.')</b>');
    } 
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('         </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
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
  Rodape();
} 

// =========================================================================
// Rotina de unidade
// -------------------------------------------------------------------------
function LimiteUnidade() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_ano    = $_REQUEST['w_ano'];

  // Recupera os dados da unidade
  $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null);
  foreach($RS as $row) { $RS = $row; break; }
  $w_nome  = f($RS,'nome');
  $w_sigla = f($RS,'sigla');
  $w_ativo = f($RS,'ativo');

  if ($w_troca>'') {
    // Se for recarga da página
    $w_limite_passagem  = $_REQUEST['w_limite_passagem'];
    $w_limite_diaria    = $_REQUEST['w_limite_diaria'];
    $w_ano              = $_REQUEST['w_ano'];
  } elseif ($O=='L') {
    // Recupera os parâmetros do módulo
    $RS = db_getPDParametro::getInstanceOf($dbms,$w_cliente,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_limite_unidade     = f($RS,'limite_unidade');
    } 

    // Recupera todos os registros para a listagem
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_chave,'PDUNIDLIM',null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'ano','desc','nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_chave,'PDUNIDLIM',null,null,$w_ano);
    foreach($RS as $row) { $RS = $row; break; }
    $w_limite_passagem  = number_format(f($RS,'limite_passagem'),2,',','.');
    $w_limite_diaria    = number_format(f($RS,'limite_diaria'),2,',','.');
    $w_ano              = f($RS,'ano');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Limites de passagens e diárias por unidade</TITLE>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      if ($O=='I') {
        Validate('w_ano','Ano','SELECT','1','4','4','','0123456789');
      } 
      Validate('w_limite_passagem','Limite financeiro para passagens','VALOR','1',4,18,'','0123456789.,');
      Validate('w_limite_diaria','Limite financeiro para diárias','VALOR','1',4,18,'','0123456789.,');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_ano.focus()\';');
  } elseif (!(strpos('A',$O)===false)) {
    BodyOpen('onLoad=\'this.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 

  ShowHTML('<div align=center><center>');
  ShowHTML('<table border=1 width="100%" bgcolor="#FAEBD7"><tr><td>');
  ShowHTML('  <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('    <tr valign="top"><td>Limites orçamentários da unidade:<b> '.$w_nome.' ('.$w_sigla.')</td>');
  ShowHTML('  </TABLE>');
  ShowHTML('</table>');
  ShowHTML('<HR>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="opener.focus(); window.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Ano','ano').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Limite passagens','limite_passagem').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Limite diárias','limite_diaria').'</font></td>');
    ShowHTML('          <td><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'ano').'</td>');
        ShowHTML('        <td align="right">'.number_format(f($row,'limite_passagem'),2,',','.').'</td>');
        ShowHTML('        <td align="right">'.number_format(f($row,'limite_diaria'),2,',','.').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_unidade').'&w_ano='.f($row,'ano').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_unidade').'&w_ano='.f($row,'ano').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('      <tr valign="top">');
    if ($O=='I') {
      SelecaoAno('<U>A</U>no:','A',null,$w_ano,null,'w_ano',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
      ShowHTML('          <td valign="top"><b>Ano:<br>'.$w_ano.'</b></td>');
    } 
    ShowHTML('          <td valign="top"><b><u>L</u>imite para passagens:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_limite_passagem" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_limite_passagem.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o limite financeiro para passagens para a unidade selecionada."></td>');
    ShowHTML('          <td valign="top"><b>L<u>i</u>mite para diárias:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_limite_diaria" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_limite_diaria.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o limite financeiro para diárias para a unidade selecionada."></td>');
    ShowHTML('         </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
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
  Rodape();
} 

// =========================================================================
// Rotina de usuário
// -------------------------------------------------------------------------
function Usuario() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];

  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getPersonList::getInstanceOf($dbms,$w_cliente,$w_chave,$SG,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome_resumido','asc');
    } else {
      $RS = SortArray($RS,'nome_resumido','asc');
    }
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if ($O=='I') {
    ScriptOpen('JavaScript');
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('w_chave','Pessoa','HIDDEN','1','1','50','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 

  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'this.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome_resumido').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Lotação','sg_unidade').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ramal','ramal').'</font></td>');
    ShowHTML('          <td><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'chave'),$TP,f($row,'nome_resumido')).'</td>');
        ShowHTML('        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($row,'nm_local'),f($row,'sq_unidade'),$TP).'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'ramal'),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('<u>P</u>essoa:','p','Selecione a pessoa.',$w_chave,null,'w_chave','USUARIOS');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
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
  Rodape();
} 

// =========================================================================
// Manter Tabela básica 'PD_CATEGORIA_DIARIA'
// -------------------------------------------------------------------------
function CategoriaDiaria() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave=$_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_nome         = $_REQUEST['w_nome'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_tramite      = $_REQUEST['w_tramite'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getCategoriaDiaria::getInstanceOf($dbms,$w_cliente,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false) && $w_troca=='') {
    // Recupera os dados chave informada
    $RS = db_getCategoriaDiaria::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave        = f($RS,'chave');
    $w_nome         = f($RS,'nome');
    $w_ativo        = f($RS,'ativo');
    $w_tramite      = f($RS,'tramite_especial');
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','2','30','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Trâmite especial','nm_tramite_especial').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_tramite_especial').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Categoria passa por trâmite especial?</b>',$w_tramite,'w_tramite');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpenClean('onLoad=this.focus();');
  
  if (!(strpos($SG,'PDDESC')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (!(strpos('IA',$O)===false)) {
        if ($_REQUEST['w_ativo']=='S') {
          //$RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,'S',null,$_REQUEST['w_chave'],null);
          $RS = db_getDescontoAgencia::getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_agencia'],null,$_REQUEST['w_faixa_inicio'],$_REQUEST['faixa_fim'],$_REQUEST['desconto'],$_REQUEST['w_ativo']);
          //print_r($RS);
          foreach($RS as $row) {
           if($O=='I' || ($O=='A' && $_REQUEST['w_chave']!=f($row,'chave'))){
              if((toNumber($_REQUEST['w_faixa_inicio'])+0>= f($row,'faixa_inicio') && 
                  toNumber($_REQUEST['w_faixa_inicio'])+0 <= f($row,'faixa_fim')
                 ) || 
                 (toNumber($_REQUEST['w_faixa_fim'])+0<= f($row,'faixa_inicio') && 
                  toNumber($_REQUEST['w_faixa_fim'])+0 >= f($row,'faixa_fim')
                 )||
                 (f($row,'faixa_inicio')>= toNumber($_REQUEST['w_faixa_inicio'])+0 && 
                  f($row,'faixa_inicio') <= toNumber($_REQUEST['w_faixa_fim'])+0
                 ) || 
                 (f($row,'faixa_fim')>= toNumber($_REQUEST['w_faixa_inicio'])+0 && 
                  f($row,'faixa_fim') <= toNumber($_REQUEST['w_faixa_fim'])+0
                 )
                ){
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\''.$w_chave.'Não pode haver sobreposição na faixa ativa de desconto de uma mesma agência.\');');
                  ScriptClose();
                  retornaFormulario('w_faixa_inicio');
                  exit;
                }            
              }
          }
          
          //exit();          
          /*if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Somente pode existir uma companhia padrão!\');');
            ScriptClose();
            retornaFormulario('w_nome');
            exit;
          } */
        }
        /*$RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_nome'],null,null,null,null,null,null,$_REQUEST['w_chave'],null);
        if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Companhia já cadastrada!\');');
          ScriptClose();
          retornaFormulario('w_nome');
          exit;
        }*/ 
      } 
      //($dbms, $operacao, $p_cliente, $p_chave, $p_agencia, $p_inicio, $p_fim, $p_desconto, $p_ativo)
      dml_putDescontoAgencia::getInstanceOf($dbms,$O,$w_cliente,
      $_REQUEST['w_chave'],$_REQUEST['w_agencia'],$_REQUEST['w_faixa_inicio'],$_REQUEST['w_faixa_fim'],$_REQUEST['w_desconto'],$_REQUEST['w_ativo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit();
    } 
  }
  if (!(strpos($SG,'PDCIA')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (!(strpos('IA',$O)===false)) {
        if ($_REQUEST['w_padrao']=='S') {
          $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,'S',null,$_REQUEST['w_chave'],null);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Somente pode existir uma companhia padrão!\');');
            ScriptClose();
            retornaFormulario('w_nome');
            exit;
          } 
        }
        $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_nome'],null,null,null,null,null,null,$_REQUEST['w_chave'],null);
        if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Companhia já cadastrada!\');');
          ScriptClose();
          retornaFormulario('w_nome');
          exit;
        } 
      } 
      dml_putCiaTrans::getInstanceOf($dbms,$O,$w_cliente,
      $_REQUEST['w_chave'],$_REQUEST['w_nome'],$_REQUEST['w_sigla'],$_REQUEST['w_aereo'],$_REQUEST['w_rodoviario'],
      $_REQUEST['w_aquaviario'],$_REQUEST['w_padrao'],$_REQUEST['w_ativo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit();
    } 
  } elseif (!(strpos($SG,'PDCATDIA')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (!(strpos('IA',$O)===false)) {
          $RS = db_getCategoriaDiaria::getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_nome'],null,null);
          if (count($RS)>0) {
            foreach($RS as $row) { $RS = $row; break; }
            if ($O=='I' || ($O=='A' && f($row,'chave')!=$_REQUEST['w_chave'])) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Categoria já cadastrada!\');');
              ScriptClose();
              retornaFormulario('w_nome');
              exit;
            }
          }
      }
      dml_putCategoriaDiaria::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_nome'],$_REQUEST['w_ativo'],$_REQUEST['w_tramite']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
      exit;
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif (!(strpos($SG,'PDMEIO')===false)) {
    // Verifica se a Assinatura Eletrônica é válida

    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (!(strpos('IA',$O)===false)) {
        //if ($_REQUEST['w_padrao']=='S') {
          /*$RS = db_getMeioTransporte::getInstanceOf($dbms,$w_cliente,null,null,null);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Somente pode existir uma companhia padrão!\');');
            ScriptClose();
            retornaFormulario('w_nome');
            exit;
          }*/ 
        //} 
        $RS = db_getMeioTransporte::getInstanceOf($dbms, $w_cliente, null, null, null, $_REQUEST['w_nome']);
        if (count($RS)>0) {        
          foreach($RS as $row) { $RS = $row; break; }
          if (f($RS,'chave')!=nvl($_REQUEST['w_chave'],0)) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Meio de transporte já cadastrado!\');');
            ScriptClose();
            retornaFormulario('w_nome');
            exit;
          }
        } 
      }
      dml_putMeioTrans::getInstanceOf($dbms, $O, $w_cliente,
          $_REQUEST['w_chave'],$_REQUEST['w_nome'],$_REQUEST['w_aereo'],$_REQUEST['w_rodoviario'],
          $_REQUEST['w_ferroviario'], $_REQUEST['w_aquaviario'],$_REQUEST['w_ativo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'PDVALDIA')===false)) {
    // Verifica se a Assinatura Eletrônica é válida

    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (!(strpos('IA',$O)===false)) {
        $w_erro = false;
        $RS = db_getValorDiaria::getInstanceOf($dbms, $w_cliente, null);
        foreach ($RS as $row) {
          if (f($row,'nacional')==$_REQUEST['w_nacional'] &&
              f($row,'continente')==intVal($_REQUEST['w_continente']) &&
              nvl(f($row,'sq_pais'),0)==nvl(intVal($_REQUEST['w_sq_pais']),0) &&
              nvl(f($row,'sq_cidade'),0)==nvl(intVal($_REQUEST['w_sq_cidade']),0) &&
              f($row,'tipo_diaria')==$_REQUEST['w_tipo_diaria'] &&
              f($row,'sq_categoria_diaria')==intVal($_REQUEST['w_categoria']) &&
              f($row,'chave')!=nvl($_REQUEST['w_chave'],0)) {
            $w_erro = true;
            break;
          }
        }
        if ($w_erro) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Já existe valor de diária para os dados informados!\');');
          ScriptClose();
          retornaFormulario('w_tipo_diaria');
          exit;
        }
      }
      dml_putValorDiaria::getInstanceOf($dbms, $O, $w_cliente,
          $_REQUEST['w_nacional'],$_REQUEST['w_continente'],$_REQUEST['w_sq_pais'],$_REQUEST['w_sq_cidade'],
          $_REQUEST['w_sq_moeda'], $_REQUEST['w_tipo_diaria'],$_REQUEST['w_categoria'], 
          $_REQUEST['w_valor'], $_REQUEST['w_chave']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'PDPARAM')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putPDParametro::getInstanceOf($dbms,$w_cliente,
          $_REQUEST['w_sequencial'],$_REQUEST['w_ano_corrente'],$_REQUEST['w_prefixo'],
          $_REQUEST['w_sufixo'],$_REQUEST['w_dias_antecedencia'],$_REQUEST['w_dias_anteced_int'],
          $_REQUEST['w_dias_prest_contas'],$_REQUEST['w_limite_unidade']);

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'PDUNIDADE')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {
        $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],'VIAGEM',null,null,null);
        if (count($RS)==0) {
          dml_putPDUnidade::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_ativo']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Unidade já cadastrada!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
        } 
      } else {
        dml_putPDUnidade::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'PDUNIDLIM')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {
        $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],'PDUNIDLIM',null,null,$_REQUEST['w_ano']);
        if (count($RS)==0) {
          dml_putPDUnidLimite::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_limite_passagem'],$_REQUEST['w_limite_diaria'],$_REQUEST['w_ano']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Limite da unidade já cadastrado para o ano de '.$_REQUEST['w_ano'].'!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
        } 
      } else {
        dml_putPDUnidLimite::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_limite_passagem'],$_REQUEST['w_limite_diaria'],$_REQUEST['w_ano']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } elseif (!(strpos($SG,'PDUSUARIO')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {
        $RS = db_getPersonList::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],$SG,null,null,null,null);
        if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Usuário já cadastrado!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
          exit;
        } 
      } 
      dml_putPDUsuario::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
  }
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'CIATRANS'      : CiaTrans();        break;
  case 'MEIOTRANS'     : MeioTrans();       break;
  case 'CATEGDIARIA'   : CategoriaDiaria(); break;
  case 'VALORDIARIA'   : ValorDiaria();     break;
  case 'PARAMETROS'    : Parametros();      break;
  case 'UNIDADE'       : Unidade();         break;
  case 'LIMUNIDADE'    : LimiteUnidade();   break;
  case 'USUARIO'       : Usuario();         break;
  CASE 'DESCONTO'      : Desconto();        break;
  case 'GRAVA'         : Grava();           break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
