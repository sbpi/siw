<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getOrPrioridadeList.php');
include_once($w_dir_volta.'classes/sp/db_getOrPrioridade.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getAcaoPPA.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaMensal.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getOrImport.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/dml_putOrPrioridade.php');
include_once($w_dir_volta.'classes/sp/dml_putAcaoPPA.php');
include_once($w_dir_volta.'funcoes/selecaoAcaoPPA.php');
include_once($w_dir_volta.'funcoes/selecaoAcaoPPA_OR.php');
include_once($w_dir_volta.'funcoes/selecaoOrPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/cabecalhoWordOR.php');

// =========================================================================
//  /or_tabelas.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar tabelas básicas do módulo  
// Mail     : billy@sbpi.com.br
// Criacao  : 18/08/2006 14:20
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
$par            = upper($_REQUEST['par']);
$P1             = Nvl($_REQUEST['P1'],0);
$P2             = Nvl($_REQUEST['P2'],0);
$P3             = Nvl($_REQUEST['P3'],1);
$P4             = Nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = upper($_REQUEST['SG']);
$R              = lower($_REQUEST['R']);
$O              = upper($_REQUEST['O']);
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'or_tabelas.php?par=';
$w_dir          = 'mod_or_pub/';
$w_dir_volta    = '../';
$w_Disabled     = 'ENABLED';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];

if ($O=='') {
  if ($par=='REL_PPA' || $par=='REL_INICIATIVA' || $par=='REL_SINTETICO_IP' || $par=='REL_SINTETICO_PPA'){
    $O='P';
  } else {
    $O='L';
  } 
} 

switch ($O){
  case 'I':    $w_TP=$TP.' - Inclusão';     break;
  case 'A':    $w_TP=$TP.' - Alteração';    break;
  case 'E':    $w_TP=$TP.' - Exclusão';     break;
  case 'P':    $w_TP=$TP.' - Filtragem';    break;
  case 'C':    $w_TP=$TP.' - Cópia';        break;
  case 'V':    $w_TP=$TP.' - Envio';        break;
  case 'H':    $w_TP=$TP.' - Herança';      break;  
  default:     $w_TP=$TP.' - Listagem';     break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado. 
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu    = RetornaMenu($w_cliente,$SG);
Main();
FechaSessao($dbms);
exit;

// Recupera a configuração do serviço
if ($P2>0) {
  $RS = db_getMenuData::getInstanceOf($dbms,$P2); 
} else {
  $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
}
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  // Se for sub-menu, pega a configuração do pai
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

// =========================================================================
// Rotina de iniciativas prioritárias do Governo
// -------------------------------------------------------------------------

function Iniciativa() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_codigo       = $_REQUEST['w_codigo'];
    $w_nome         = $_REQUEST['w_nome'];
    $w_responsavel  = $_REQUEST['w_responsavel'];
    $w_telefone     = $_REQUEST['w_telefone'];
    $w_email        = $_REQUEST['w_email'];
    $w_ordem        = $_REQUEST['w_ordem'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_padrao       = $_REQUEST['w_padrao'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getOrPrioridadeList::getInstanceOf($dbms,null,$w_cliente,null);
    $RS = SortArray($RS,'ordem','asc');
  } elseif (!(strpos('AEV',$O)===false && $w_troca=='')) {
    // Recupera os dados do endereço informado
    $RS = db_getOrPrioridade::getInstanceOf($dbms,null,$w_cliente,$w_chave,null,null,null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_codigo      = f($RS,'codigo');
    $w_nome        = f($RS,'nome');
    $w_responsavel = f($RS,'responsavel');
    $w_telefone    = f($RS,'telefone');
    $w_email       = f($RS,'email');
    $w_ordem       = f($RS,'ordem');
    $w_ativo       = f($RS,'ativo');
    $w_padrao      = f($RS,'padrao');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','2','100','1','1');
      Validate('w_codigo','Código externo','1','','1','50','','0123456789');
      Validate('w_responsavel','Responsável','1','','2','60','1','1');
      Validate('w_telefone','Telefone','1','','2','20','1','1');
      Validate('w_email','e-Mail','1','','5','60','1','1');
      Validate('w_ordem','Ordem','1','1','1','4','','0123456789');
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
  if ($O=='L'){
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Padrão</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
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
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="90" MAXLENGTH="100" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('           <td><b><u>C</u>ódigo externo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="sti" SIZE="10" MAXLENGTH="50" VALUE="'.$w_codigo.'"></td>');
    ShowHTML('           <td><b><u>R</u>esponsável:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_responsavel" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_responsavel.'"></td>');
    ShowHTML('           <td><b><u>T</u>elefone:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_telefone" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_telefone.'"></td>');
    ShowHTML('        <tr><td colspan=3><b><u>e</u>-Mail:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_email" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_email.'"></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('           <td><b><u>O</u>rdem:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_ordem" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ordem.'"></td>');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    MontaRadioNS('<b>Padrão?</b>',$w_padrao,'w_padrao');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I'){
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de ações do PPA
// -------------------------------------------------------------------------
function PPA() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];
  if ($w_troca>'') {
    //Se for recarga da página  
    $w_codigo                 = $_REQUEST['w_codigo'];
    $w_nome                   = $_REQUEST['w_nome'];
    $w_responsavel            = $_REQUEST['w_responsavel'];
    $w_telefone               = $_REQUEST['w_telefone'];
    $w_email                  = $_REQUEST['w_email'];
    $w_ativo                  = $_REQUEST['w_ativo'];
    $w_padrao                 = $_REQUEST['w_padrao'];
    $w_aprovado               = $_REQUEST['w_aprovado'];
    $w_saldo                  = $_REQUEST['w_saldo'];
    $w_empenhado              = $_REQUEST['w_empenhado'];
    $w_liquidado              = $_REQUEST['w_liquidado'];
    $w_liquidar               = $_REQUEST['w_liquidar'];
    $w_sq_acao_ppa_pai        = $_REQUEST['w_sq_acao_ppa_pai'];
    $w_selecionada_mpog       = $_REQUEST['w_selecionada_mpog'];
    $w_selecionada_relevante  = $_REQUEST['w_selecionada_relevante'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getAcaoPPA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'ordena','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getAcaoPPA::getInstanceOf($dbms,$w_chave,$w_cliente,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_codigo                = f($RS,'codigo');
    $w_nome                  = f($RS,'nome');
    $w_responsavel           = f($RS,'responsavel');
    $w_telefone              = f($RS,'telefone');
    $w_email                 = f($RS,'email');
    $w_ativo                 = f($RS,'ativo');
    $w_padrao                = f($RS,'padrao');
    $w_aprovado              = number_format(f($RS,'aprovado'),2,',','.');
    $w_saldo                 = number_format(f($RS,'saldo'),2,',','.');
    $w_empenhado             = number_format(f($RS,'empenhado'),2,',','.');
    $w_liquidado             = number_format(f($RS,'liquidado'),2,',','.');
    $w_liquidar              = number_format(f($RS,'liquidar'),2,',','.');
    $w_sq_acao_ppa_pai       = f($RS,'sq_acao_ppa_pai');
    $w_selecionada_mpog      = f($RS,'selecionada_mpog');
    $w_selecionada_relevante = f($RS,'selecionada_relevante');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)){
    ScriptOpen('JavaScript');
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','2','100','1','1');
      Validate('w_sq_acao_ppa_pai','Vinculação','SELECT','','1','18','','1');
      Validate('w_codigo','Código externo','1','1','1','50','1','1');
      Validate('w_responsavel','Responsável','1','','2','60','1','1');
      Validate('w_telefone','Telefone','1','','2','20','1','1');
      Validate('w_email','e-Mail','1','','5','60','1','1');
      Validate('w_aprovado','Aprovado','VALOR','1','4','18','','0123456789,.');
      Validate('w_empenhado','Empenhado','VALOR','1','4','18','','0123456789,.');
      Validate('w_liquidado','Liquidado','VALOR','1','4','18','','0123456789,.');
      Validate('w_liquidar','Liquidar','VALOR','1','4','18','','0123456789,.');
      Validate('w_saldo','Saldo','1','VALOR','4','18','','0123456789,.');
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
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Código</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Padrão</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if (Nvl(f($row,'sq_acao_ppa_pai'),'')=='') {
          ShowHTML('        <td><b>'.f($row,'codigo').'</td>');
        } else {
          ShowHTML('        <td>&nbsp;&nbsp;'.f($row,'codigo').'</td>');
        } 
        ShowHTML('        <td>'.f($row,'nome').'</td>');
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
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled =' DISABLED '; 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="90" MAXLENGTH="100" VALUE="'.$w_nome.'"></td>');
    ShowHTML('           <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    SelecaoAcaoPPA_OR('<u>S</u>ubordinação:','S',null,$w_sq_acao_ppa_pai,$w_chave,'w_sq_acao_ppa_pai','CADASTRO',null);
    ShowHTML('           </table>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('           <td><b><u>C</u>ódigo externo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="sti" SIZE="10" MAXLENGTH="50" VALUE="'.$w_codigo.'"></td>');
    ShowHTML('           <td><b><u>R</u>esponsável:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_responsavel" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_responsavel.'"></td>');
    ShowHTML('           <td><b><u>T</u>elefone:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_telefone" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_telefone.'"></td>');
    ShowHTML('        <tr><td colspan=3><b><u>e</u>-Mail:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_email" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_email.'"></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Selecionada MP?</b>',$w_selecionada_mpog,'w_selecionada_mpog');
    MontaRadioNS('<b>Selecionada Relevante?</b>',$w_selecionada_relevante,'w_selecionada_relevante');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0>');
    ShowHTML('         <tr valign="top">');
    ShowHTML('           <td><b>A<u>p</u>rovado:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_aprovado" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.Nvl($w_aprovado,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('           <td><b><u>S</u>aldo:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_saldo" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.Nvl($w_saldo,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('         <tr valign="top">');
    ShowHTML('           <td><b><u>E</u>mpenhado:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_empenhado" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.Nvl($w_empenhado,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('           <td><b><u>L</u>iquidado:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_liquidado" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.Nvl($w_liquidado,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('           <td><b>A l<u>i</u>quidar:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_liquidar" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.Nvl($w_liquidar,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('           </table>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    MontaRadioNS('<b>Padrão?</b>',$w_padrao,'w_padrao');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I'){
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Relatório da tabela do PPA
// -------------------------------------------------------------------------

function Rel_PPA() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave                  = $_REQUEST['w_chave'];
  $w_tipo_rel               = upper(trim($_REQUEST['w_tipo_rel']));
  $p_sq_acao_ppa_pai        = upper(trim($_REQUEST['p_sq_acao_ppa_pai']));
  $p_sq_acao_ppa            = upper(trim($_REQUEST['p_sq_acao_ppa']));
  $p_responsavel            = upper(trim($_REQUEST['p_responsavel']));
  $p_sq_unidade_resp        = upper(trim($_REQUEST['p_sq_unidade_resp']));
  $p_prioridade             = upper(trim($_REQUEST['p_prioridade']));
  $p_selecionada_mpog       = upper(trim($_REQUEST['p_selecionada_mpog']));
  $p_selecionada_relevante  = upper(trim($_REQUEST['p_selecionada_relevante']));
  $p_tarefas_atraso         = upper(trim($_REQUEST['p_tarefas_atraso']));
  $p_campos                 = explodeArray($_REQUEST['p_campos']);
  $p_tarefas                = $_REQUEST['p_tarefas'];
  $p_metas                  = $_REQUEST['p_metas'];
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    } 
    // Recupera todos os registros para a listagem
    $RS = db_getAcaoPPA::getInstanceOf($dbms,null,$w_cliente,$p_sq_acao_ppa_pai,$p_sq_acao_ppa,$p_responsavel,$p_selecionada_mpog,$p_selecionada_relevante,null,null,null,null);
    $RS = SortArray($RS,'ordena','asc');
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag   = 1;
    $w_linha = 5;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Tabela PPA');
    ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Tabela PPA</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_acao_ppa_pai','Programa','SELECT','','1','18','','1');
      Validate('p_sq_acao_ppa','Ação','SELECT','','1','18','','1');
      Validate('p_responsavel','Responsável','1','','2','60','1','1');
      ShowHTML('  if (theForm.p_tarefas.checked == false) {');
      ShowHTML('     theForm.p_prioridade.value = \'\'');
      ShowHTML('  }');
      ShowHTML('  if (theForm.p_tarefas.checked == false && theForm.p_tarefas_atraso[0].checked == true) {');
      ShowHTML('      alert(\'Para exibir somente as tarefas em atraso,\n\n é preciso escolher a exibição da tarefa \');');
      ShowHTML('      return (false);');
      ShowHTML('  }');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean('onLoad=\'this.focus()\';');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Tabela PPA');
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=WORD&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');      
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_sq_acao_ppa_pai.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    $w_col      = 2;
    $w_col_word = 2;
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='';
    if ($p_responsavel>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Responsável<td>[<b>'.$p_responsavel.'</b>]';
    if ($p_prioridade>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Prioridade<td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
    if ($p_selecionada_mpog>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada MP<td>[<b>'.$p_selecionada_mpog.'</b>]';
    if ($p_selecionada_relevante>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada Relevante<td>[<b>'.$p_selecionada_relevante.'</b>]';
    if ($p_tarefas_atraso>'') $w_filtro=$w_filtro.'<td>Ações com tarefas em atraso&nbsp;[<b>'.$p_tarefas_atraso.'</b>]&nbsp;';
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
    ShowHTML('    <td align="right" valign="botton"><b>Registros listados: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Código</td>');
    ShowHTML('          <td><b>Nome</td>');
    if (!(strpos($p_campos,'responsavel')===false)) {
      ShowHTML('          <td><b>Responsável</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'email')===false)) {
      ShowHTML('          <td><b>e-Mail</td>');
      $w_col        += 1;
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'telefone')===false)) {
      ShowHTML('          <td><b>Telefone</td>');
      $w_col        +=  1;
      $w_col_word   +=  1;
    } 
    if (!(strpos($p_campos,'aprovado')===false)) {
      ShowHTML('          <td><b>Aprovado</td>');
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'empenhado')===false)) {
      ShowHTML('          <td><b>Empenhado</td>');
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'saldo')===false)) {
      ShowHTML('          <td><b>Saldo</td>');
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'liquidado')===false)) {
      ShowHTML('          <td><b>Liquidado</td>');
      $w_col_word   += 1;
    } 
    if (!(strpos($p_campos,'liquidar')===false)) {
      ShowHTML('          <td><b>A liquidar</td>');
      $w_col_word   += 1;
    } 
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col.' align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_acao_aprovado  = 0.00;
      $w_acao_saldo     = 0.00;
      $w_acao_empenhado = 0.00;
      $w_acao_liquidado = 0.00;
      $w_acao_liquidar  = 0.00;
      $w_tot_aprovado   = 0.00;
      $w_tot_saldo      = 0.00;
      $w_tot_empenhado  = 0.00;
      $w_tot_liquidado  = 0.00;
      $w_tot_liquidar   = 0.00;
      $w_atual='';
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        if ($w_linha>22 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=5;
          $w_pag=$w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.$w_logo.'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Tabela PPA');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
          ShowHTML('    <td align="right" valign="botton"><b>Registros listados: '.count($RS));
          ShowHTML('<tr><td align="center" colspan=3>');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Código</td>');
          ShowHTML('          <td><b>Nome</td>');
          if (!(strpos($p_campos,'responsavel')===false)){
            ShowHTML('          <td><b>Responsável</td>');       
          } if (!(strpos($p_campos,'email')===false)) {
            ShowHTML('          <td><b>e-Mail</td>');    
          } if (!(strpos($p_campos,'telefone')===false)) {
            ShowHTML('          <td><b>Telefone</td>');       
          } if (!(strpos($p_campos,'aprovado')===false)) {
            ShowHTML('          <td><b>Aprovado</td>');       
          } if (!(strpos($p_campos,'empenhado')===false)) {
            ShowHTML('          <td><b>Empenhado</td>');      
          } if (!(strpos($p_campos,'saldo')===false)) {
            ShowHTML('          <td><b>Saldo</td>');       
          } if (!(strpos($p_campos,'liquidado')===false)) {
            ShowHTML('          <td><b>Liquidado</td>');       
          } if (!(strpos($p_campos,'liquidar')===false)) {
            ShowHTML('          <td><b>A liquidar</td>');      
          }
          ShowHTML('        </tr>');
        } 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if (Nvl(f($row,'sq_acao_ppa_pai'),'')=='') {
          if ($w_atual>'') {
            if ((!(strpos($p_campos,'aprovado')===false)) || (!(strpos($p_campos,'saldo')===false)) || (!(strpos($p_campos,'empenhado')===false)) || (!(strpos($p_campos,'liquidado')===false)) || (!(strpos($p_campos,'liquidar')===false))) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
              ShowHTML('        <td colspan='.$w_col.' align="right"><b>Totais do programa <b>'.$w_atual.'</b></td>');
              if (!(strpos($p_campos,'aprovado')===false)) {
                ShowHTML('        <td align="right">'.number_format($w_acao_aprovado,2,',','.').'</td>');        
              } if (!(strpos($p_campos,'empenhado')===false)) {
                ShowHTML('        <td align="right">'.number_format($w_acao_empenhado,2,',','.').'</td>');        
              } if (!(strpos($p_campos,'saldo')===false)) {
                ShowHTML('        <td align="right">'.number_format($w_acao_saldo,2,',','.').'</td>');       
              } if (!(strpos($p_campos,'liquidado')===false)) {
                ShowHTML('        <td align="right">'.number_format($w_acao_liquidado,2,',','.').'</td>');         
              } if (!(strpos($p_campos,'liquidar')===false)) {
                ShowHTML('        <td align="right" nowrap>'.number_format($w_acao_liquidar,2,',','.').'</td>');        
              }
              ShowHTML('      </tr>');
              //ShowHTML '      <tr bgcolor=''' & conTrBgColor & ''' height=5><td colspan=10></td></tr>'
            } 
            $w_acao_aprovado  = 0.00;
            $w_acao_saldo     = 0.00;
            $w_acao_empenhado = 0.00;
            $w_acao_liquidado = 0.00;
            $w_acao_liquidar  = 0.00;
          } 
          ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
          ShowHTML('        <td><b>'.f($row,'codigo').'</td>');
          ShowHTML('        <td><b>'.f($row,'nome').'</td>');
          if (!(strpos($p_campos,'responsavel')===false)) {
            ShowHTML('        <td>'.Nvl(f($row,'responsavel'),'---').'</td>');    
          } if (!(strpos($p_campos,'email')===false)) {
            ShowHTML('        <td>'.Nvl(f($row,'email'),'---').'</td>');     
          } if (!(strpos($p_campos,'telefone')===false)) {
            ShowHTML('        <td>'.Nvl(f($row,'telefone'),'---').'</td>');     
          } if ((!(strpos($p_campos,'aprovado') ===false)) || (!(strpos($p_campos,'saldo')===false)) || (!(strpos($p_campos,'empenhado')===false)) || (!(strpos($p_campos,'liquidado')===false)) || (!(strpos($p_campos,'liquidar')===false))) {
            ShowHTML('        <td colspan='.($w_col_word-$w_col).'>&nbsp;</td>');
          } 
          $w_atual=f($row,'codigo');
          $w_linha+=1;
        } else {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td>&nbsp;&nbsp;'.f($row,'codigo').'</td>');
          ShowHTML('        <td>'.f($row,'nome').'</td>');
          if (!(strpos($p_campos,'responsavel')===false))  {
            ShowHTML('        <td>'.Nvl(f($row,'responsavel'),'---').'</td>');     
          } if (!(strpos($p_campos,'email')===false)) {
            ShowHTML('        <td>'.Nvl(f($row,'email'),'---').'</td>');    
          } if (!(strpos($p_campos,'telefone')===false)) {
            ShowHTML('        <td>'.Nvl(f($row,'telefone'),'---').'</td>'); 
          } if (!(strpos($p_campos,'aprovado')===false)) {
            ShowHTML('        <td align="right">'.number_format(f($row,'aprovado'),2,',','.').'</td>');    
          } if (!(strpos($p_campos,'empenhado')===false)) {
            ShowHTML('        <td align="right">'.number_format(f($row,'empenhado'),2,',','.').'</td>');    
          } if (!(strpos($p_campos,'saldo')===false)) {
            ShowHTML('        <td align="right">'.number_format(f($row,'aprovado')-f($row,'empenhado'),2,',','.').'</td>');    
          } if (!(strpos($p_campos,'liquidado')===false)) {
            ShowHTML('        <td align="right">'.number_format(f($row,'liquidado'),2,',','.').'</td>');    
          } if (!(strpos($p_campos,'liquidar')===false)) {
            ShowHTML('        <td align="right" nowrap>'.number_format(f($row,'empenhado')-f($row,'liquidado'),2,',','.').'</td>');    
          }
          $w_linha+=1;
          ShowHTML('</tr>');
          if ($p_metas>'') {
            ShowHTML('      <tr><td><td colspan='.$w_col_word.'><table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
            $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                   null,null,null,null,null,null,null,null,null,null,null,null,
                   null,null,null,null,null,null,null,null,null,null,null,null,
                   f($row,'chave'),null);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(metas).</b></td></tr>');
              $w_linha+=1;
            } else {
              foreach ($RS2 as $row2){$RS2=$row2; break;}
              $RS3 = db_getSolicEtapa::getInstanceOf($dbms,f($RS2,'sq_siw_solicitacao'),null,'LSTNULL',null);
              $RS3 = SortArray($RS3,'ordem','asc');
              if (count($RS3)<=0){
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(metas).</b></td></tr>');
                $w_linha+=1;
              } else {
                ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('          <td><b>Produto</td>');
                ShowHTML('          <td><b>Meta LOA</td>');
                ShowHTML('          <td><b>Fim previsto</td>');
                ShowHTML('          <td><b>Unidade<br>medida</td>');
                ShowHTML('          <td><b>Quantitativo<br>programado</td>');
                ShowHTML('          <td><b>% Realizado</td>');
                ShowHTML('        </tr>');
                $w_linha+=1;
                foreach ($RS3 as $row3) {
                  ShowHtml(EtapaLinha(f($RS2,'sq_siw_solicitacao'),f($row3,'sq_projeto_etapa'),f($row3,'titulo'),$w_tipo_rel,f($row3,'programada'),f($row3,'unidade_medida'),f($row3,'quantidade'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),'S','PROJETO'));
                  $w_linha=$w_linha+1;
                } 
              } 
            } 
            ShowHTML('        </table>');
          } if ($p_tarefas>'') {  
            ShowHTML('      <tr><td><td colspan='.$w_col_word.'><table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
            $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                   null,null,null,null,null,null,null,null,null,null,null,null,
                   null,null,null,null,null,null,null,null,null,null,null,null,
                   f($row,'chave'),null);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(tarefas).</b></td></tr>');
              $w_linha+=1;
            } else {
              foreach ($RS2 as $row2){$RS2=$row2; break;}
              $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORPCAD');
              $RS3 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                     null,null,null,null,null,null,null,$p_prioridade,null,null,
                     null,null,null,null,null,null,null,null,null,null,null,null, 
                     f($RS2,'sq_siw_solicitacao'),null,null,null);                                                 
              if ($p_tarefas_atraso>'') {
                //$RS3->Filter='fim < '.time();
              } 
              $RS3 = SortArray($RS3,'phpdt_fim','asc','prioridade','asc');
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(tarefas).</b></td></tr>');
                $w_linha+=1;
              } else {
                ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('          <td><b>Tarefas</td>');
                ShowHTML('          <td><b>Detalhamento</td>');
                ShowHTML('          <td><b>Responsável</td>');
                ShowHTML('          <td><b>Parcerias</td>');
                ShowHTML('          <td><b>Fim previsto</td>');
                ShowHTML('          <td><b>Programado</td>');
                ShowHTML('          <td><b>Executado</td>');
                ShowHTML('          <td><b>Fase atual</td>');
                if ($p_prioridade=='') ShowHTML('<td><b>Prioridade</font></td>');           
                ShowHTML('        </tr>');
                $w_linha+=1;
                foreach ($RS3 as $row3) {
                  //If w_cor = conTrBgColor or w_cor = '' Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                  $w_cor = $conTrBgColor;
                  ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
                  ShowHTML('        <td nowrap>');
                  if (f($row3,'concluida')=='N') {
                    if (f($row3,'fim')<addDays(time(),-1)) {
                      ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
                    } elseif (f($row3,'aviso_prox_conc')=='S' && (f($row3,'aviso')<=addDays(time(),-1))) {
                      ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
                    } else {
                      ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                    } 
                  } else {
                    if (f($row3,'fim')<Nvl(f($row3,'fim_real'),f($row3,'fim'))) {
                      ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
                    } else {
                      ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                    } 
                  } 
                  if ($w_tipo_rel!='WORD') ShowHTML('        <A class="HL" HREF="'.$w_dir.'projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row3,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" TARGET="VisualTarefa" title="Exibe as informações desta tarefa.">'.f($row3,'sq_siw_solicitacao').'&nbsp;</a>');
                  else                     ShowHTML('        '.f($row3,'sq_siw_solicitacao').'');
                  if (strlen(Nvl(f($row3,'assunto'),'-'))>50) {
                    $w_titulo = substr(Nvl(f($row3,'assunto'),'-'),0,50).'...';
                  } else {
                    $w_titulo = Nvl(f($row3,'assunto'),'-');
                  }
                  ShowHTML('        <td>'.$w_titulo.'</td>');
                  ShowHTML('        <td>'.f($row3,'nm_solic').'</td>');
                  ShowHTML('        <td>'.Nvl(f($row3,'proponente'),'---').'</td>');
                  ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row3,'fim')),'-').'</td>');
                  ShowHTML('        <td align="right">'.number_format(f($row3,'valor'),2,',','.').'&nbsp;</td>');
                  ShowHTML('        <td align="right">'.number_format(f($row3,'custo_real'),2,',','.').'&nbsp;</td>');
                  ShowHTML('        <td nowrap>'.f($row3,'nm_tramite').'</td>');
                  if ($p_prioridade=='') {
                    ShowHTML('<td nowrap>'.RetornaPrioridade(f($row3,'prioridade')).'</td>');         
                  }
                  ShowHTML('        </td>');
                  ShowHTML('      </tr>');
                  $w_linha+=1;
                } 
              } 
            } 
            ShowHTML('        </table>');
          } if ($p_sq_unidade_resp>'') {
            ShowHTML('      <tr><td><td colspan='.$w_col_word.'><table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
            $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                   null,null,null,null,null,null,null,null,null,null,null,null,
                   null,null,null,null,null,null,null,null,null,null,null,null,
                   f($row,'chave'),null);
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
              $w_linha+=1;
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="left"><b>Não foi informado o setor responsável.</b></td></tr>');
            } else {
              ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
              ShowHTML('          <td align="left"><b>Setor responsável</td>');
              ShowHTML('        </tr>');
              $w_linha+=1;
              foreach ($RS2 as $row2) {
                ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('          <td align="left"><b>'.f($row2,'nm_unidade_resp').'</td>');
                ShowHTML('        </tr>');
                $w_linha+=1;
              } 
            } 
            ShowHTML('        </table>');
          } 
        } 
        ShowHTML('      </tr>');
        $w_acao_aprovado  = $w_acao_aprovado    + f($row,'aprovado');
        $w_acao_saldo     = $w_acao_saldo       + f($row,'aprovado')     -f($row,'empenhado');
        $w_acao_empenhado = $w_acao_empenhado   + f($row,'empenhado');
        $w_acao_liquidado = $w_acao_liquidado   + f($row,'liquidado');
        $w_acao_liquidar  = $w_acao_liquidar    + f($row,'empenhado')    -f($row,'liquidado');  
        $w_tot_aprovado   = $w_tot_aprovado     + f($row,'aprovado');
        $w_tot_saldo      = $w_tot_saldo        + f($row,'aprovado')     -f($row,'empenhado');
        $w_tot_empenhado  = $w_tot_empenhado    + f($row,'empenhado');
        $w_tot_liquidado  = $w_tot_liquidado    + f($row,'liquidado');
        $w_tot_liquidar   = $w_tot_liquidar     + f($row,'empenhado')    -f($row,'liquidado');
      } 
      if (!(strpos($p_campos,'aprovado')===false) || (!(strpos($p_campos,'saldo')===false)) || (!(strpos($p_campos,'empenhado')===false)) || (!(strpos($p_campos,'liquidado')===false)) || (!(strpos($p_campos,'liquidar')===false))) {
        if (!$p_sq_acao_ppa>' ') {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('        <td colspan='.$w_col.' align="right"><b>Totais do programa <b>'.$w_atual.'</b></td>');
          if (!(strpos($p_campos,'aprovado')===false)) {
            ShowHTML('        <td align="right">'.number_format($w_acao_aprovado,2,',','.').'</td>');  
          } if (!(strpos($p_campos,'empenhado')===false)) {
            ShowHTML('        <td align="right">'.number_format($w_acao_empenhado,2,',','.').'</td>');   
          } if (!(strpos($p_campos,'saldo')===false)) {
            ShowHTML('        <td align="right">'.number_format($w_acao_saldo,2,',','.').'</td>');  
          } if (!(strpos($p_campos,'liquidado')===false)) {
            ShowHTML('        <td align="right">'.number_format($w_acao_liquidado,2,',','.').'</td>');    
          } if (!(strpos($p_campos,'liquidar')===false)) {
            ShowHTML('        <td align="right">'.number_format($w_acao_liquidar,2,',','.').'</td>');  
          }
          ShowHTML('      </tr>');
          $w_linha+=1;
        } 
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" height=5><td colspan='.$w_col.'></td></tr>');
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="center" height=30>');
        ShowHTML('        <td colspan='.$w_col.' align="right"><font size="2"><b>Totais do relatório</td>');
        if (!(strpos($p_campos,'aprovado')===false)){
           ShowHTML('        <td align="right">'.number_format($w_tot_aprovado,2,',','.').'</td>');
        } if (!(strpos($p_campos,'empenhado')===false)) {
          ShowHTML('        <td align="right">'.number_format($w_tot_empenhado,2,',','.').'</td>');
        } if (!(strpos($p_campos,'saldo')===false)) {
          ShowHTML('        <td align="right">'.number_format($w_tot_saldo,2,',','.').'</td>'); 
        } if (!(strpos($p_campos,'liquidado')===false)) {
          ShowHTML('        <td align="right">'.number_format($w_tot_liquidado,2,',','.').'</td>'); 
        } if (!(strpos($p_campos,'liquidar')===false)) {
          ShowHTML('        <td align="right">'.number_format($w_tot_liquidar,2,',','.').'</td>');
        }
        ShowHTML('      </tr>');
        $w_linha+=1;
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    } elseif ($O=='P') {
      AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Tabela PPA',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr>');
      SelecaoAcaoPPA_OR('<u>P</u>rograma:','S',null,$p_sq_acao_ppa_pai,$w_chave,'p_sq_acao_ppa_pai','CADASTRO',null);
      ShowHTML('      <tr>');
      SelecaoAcaoPPA_OR('<u>A</u>ção:','A',null,$p_sq_acao_ppa,$w_chave,'p_sq_acao_ppa','CONSULTA',null);
      ShowHTML('      <tr><td><b><u>R</u>esponsável:</b><br><input '.$w_disabled.' accesskey="R" type="text" name="p_responsavel" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$p_responsavel.'"></td>');
      ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
      SelecaoPrioridade('<u>P</u>rioridade das tarefas:','P','Informe a prioridade da tarefa.',$p_prioridade,null,'p_prioridade',null,null);
      ShowHTML('          <td><b>Exibir somente tarefas em atraso?</b><br><input '.$w_Disabled.' type="radio" name="p_tarefas_atraso" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_tarefas_atraso" value="" checked> Não');
      ShowHTML('          </table>');
      ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
      ShowHTML('          <td><b>Selecionada MP?</b><br>');
      if ($p_selecionada_mpog=='S') {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecionada_mpog.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value=""> Tanto faz');
      } elseif ($p_selecionada_mpog=='N') {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value=""> Tanto faz');
      } else {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="" checked> Tanto faz');
      } 
      ShowHTML('          <td><b>Selecionada SE/MS?</b><br>');
      if ($p_selecionada_relevante=='S') {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecionada_relevante.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value=""> Tanto faz');
      } elseif ($p_selecionada_relevante=='N') {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value=""> Tanto faz');
      } else {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="" checked> Tanto faz');
    } 
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('      <tr><td colspan=2><b>Campos a serem exibidos');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="responsavel"> Responsável</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="aprovado"> Aprovado</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="email"> e-Mail</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="saldo"> Saldo</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="telefone"> Telefone</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="liquidado"> Liquidado</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="liquidar"> A liquidar</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="empenhado"> Empenhado</td>');
    ShowHTML('      <tr><td colspan=2><b>Blocos adicionais');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_metas" value="metas"> Metas físicas</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_tarefas" value="tarefas"> Tarefas</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_sq_unidade_resp" value="unidade"> Setor responsável</td>');
    ShowHTML('     </table>');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($w_tipo_rel!='WORD') {
    Rodape();
  } 
} 
// =========================================================================
// Relatório da tabela de Iniciativas
// -------------------------------------------------------------------------
function Rel_Iniciativa() {
  extract($GLOBALS);
  $w_chave                  = $_REQUEST['w_chave'];
  $w_tipo_rel               = upper(trim($_REQUEST['w_tipo_rel']));
  $p_sq_orprioridade        = upper(trim($_REQUEST['p_sq_orprioridade']));
  $p_responsavel            = upper(trim($_REQUEST['p_responsavel']));
  $p_prioridade             = upper(trim($_REQUEST['p_prioridade']));
  $p_sq_unidade_resp        = upper(trim($_REQUEST['p_sq_unidade_resp']));
  $p_selecionada_mpo        = upper(trim($_REQUEST['p_selecionada_mpog']));
  $p_selecionada_relevante  = upper(trim($_REQUEST['p_selecionada_relevante']));
  $p_tarefas_atraso         = upper(trim($_REQUEST['p_tarefas_atraso']));
  $p_campos                 = explodeArray($_REQUEST['p_campos']);
  $p_tarefas                = $_REQUEST['p_tarefas'];
  $p_metas                  = $_REQUEST['p_metas'];
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    } 
    // Recupera todos os registros para a listagem
    $RS = db_getOrPrioridade::getInstanceOf($dbms,null,$w_cliente,$p_sq_orprioridade,$p_responsavel,$p_selecionada_mpog,$p_selecionada_relevante);
    $RS = SortArray($RS,'ordem','asc');
  } 
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag   =1;
    $w_linha =5;
    CabecalhoWordOR('Iniciativa Prioritária',$w_pag,LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED'));
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Iniciativas Prioritárias</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_orprioridade','Iniciativa prioritária','SELECT','','1','18','','1');
      Validate('p_responsavel','Responsável','1','','2','60','1','1');
      ShowHTML('  if (theForm.p_tarefas.checked == false) {');
      ShowHTML('     theForm.p_prioridade.value = \'\'');
      ShowHTML('  }');
      ShowHTML('  if (theForm.p_tarefas.checked == false && theForm.p_tarefas_atraso[0].checked == true) {');
      ShowHTML('      alert(\'Para exibir somente as tarefas em atraso,\n\n é preciso escolher a exibição da tarefa \');');
      ShowHTML('      return (false);');
      ShowHTML('  }');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean('onLoad=\'this.focus()\';');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Iniciativa Prioritária');
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=WORD&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_sq_orprioridade.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    $w_col      = 1;
    $w_col_word = 1;
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    //ShowHTML '<tr><td colspan=2><b>Filtro:'
    $w_filtro='';
    if ($p_responsavel>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">>Responsável<td>[<b>'.RetornaSimNao($p_responsavel).'</b>]';
    if ($p_prioridade>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Prioridade<td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
    if ($p_selecionada_mpog>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada MP<td>[<b>'.RetornaSimNao($p_selecionada_mpog).'</b>]';
    if ($p_selecionada_relevante>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Selecionada Relevante<td>[<b>'.RetornaSimNao($p_selecionada_relevante).'</b>]';
    if ($p_tarefas_atraso>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Tarefas em atraso&nbsp;[<b>'.RetornaSimNao($p_tarefas_atraso).'</b>]&nbsp;';    
    ShowHTML('<tr><td align="left" colspan=3>');
    if ($w_filtro>'')ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></td></tr></table></td></tr>');  
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('        <td><b>Nome</td>');
    if (!(strpos($p_campos,'responsavel')===false)) {
      ShowHTML('     <td><b>Responsável</td>');
      $w_col+=1;
      $w_col_word+=1;
    } 
    if (!(strpos($p_campos,'email')===false)) {
      ShowHTML('     <td><b>e-Mail</td>');
      $w_col+=1;
      $w_col_word+=1;
    } 
    if (!(strpos($p_campos,'telefone')===false)) {
      ShowHTML('     <td><b>Telefone</td>');
      $w_col+=1;
      $w_col_word+=1;
    }
    if (!(strpos($p_campos,'aprovado')===false)) {
      ShowHTML('          <td><b>Aprovado</td>');
      $w_col_word+=1;
    }
    if (!(strpos($p_campos,'empenhado')===false)) {
      ShowHTML('          <td><b>Empenhado</td>');
      $w_col_word+=1;
    }
    if (!(strpos($p_campos,'saldo')===false)) {
      ShowHTML('          <td><b>Saldo</td>');
      $w_col_word+=1;
    }
    if (!(strpos($p_campos,'liquidado')===false)) {
      ShowHTML('          <td><b>Liquidado</td>');
      $w_col_word+=1;
    }
    if (!(strpos($p_campos,'liquidar')===false)) {
      ShowHTML('          <td><b>A liquidar</td>');
      $w_col_word+=1;
    } 
    ShowHTML('      </tr>');
    $w_linha+=1;
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros.</b></td></tr>');
      $w_linha+=1;
    } else {
      $w_acao_aprovado  = 0.00;
      $w_acao_saldo     = 0.00;
      $w_acao_empenhado = 0.00;
      $w_acao_liquidado = 0.00;
      $w_acao_liquidar  = 0.00;
      $w_tot_aprovado   = 0.00;
      $w_tot_saldo      = 0.00;
      $w_tot_empenhado  = 0.00;
      $w_tot_liquidado  = 0.00;
      $w_tot_liquidar   = 0.00;
      $w_atual          = 0;
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        if ($w_linha>20 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style=\'page-break-after:always\'>');
          $w_linha=5;
          $w_pag+= 1;
          CabecalhoWordOR('Iniciativa Prioritária',$w_pag,$w_logo);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border=\'0\' cellpadding=\'0\' cellspacing=\'0\' width=\'100%\'>');
          ShowHTML('<tr><td align=\'center\' colspan=3>');
          ShowHTML('    <TABLE WIDTH=\'100%\' bgcolor=\''.$conTableBgColor.'\' BORDER=\''.$conTableBorder.'\' CELLSPACING=\''.$conTableCellSpacing.'\' CELLPADDING=\''.$conTableCellPadding.'\' BorderColorDark=\''.$conTableBorderColorDark.'\' BorderColorLight=\''.$conTableBorderColorLight.'\'>');
          ShowHTML('      <tr bgcolor=\''.$conTrBgColor.'\' align=\'center\'>');
          ShowHTML('        <td><font size=\'1\'><b>Nome</font></td>');
          if (!(strpos($p_campos,'responsavel')===false))   ShowHTML('     <td><font size=\'1\'><b>Responsável</font></td>');
          if (!(strpos($p_campos,'email')===false))         ShowHTML('     <td><font size=\'1\'><b>e-Mail</font></td>');
          if (!(strpos($p_campos,'telefone')===false))      ShowHTML('     <td><font size=\'1\'><b>Telefone</font></td>');
          if (!(strpos($p_campos,'aprovado')===false))      ShowHTML('          <td><font size=\'1\'><b>Aprovado</font></td>');  
          if (!(strpos($p_campos,'empenhado')===false))     ShowHTML('          <td><font size=\'1\'><b>Empenhado</font></td>'); 
          if (!(strpos($p_campos,'saldo')===false))         ShowHTML('          <td><font size=\'1\'><b>Saldo</font></td>');   
          if (!(strpos($p_campos,'liquidado')===false))     ShowHTML('          <td><font size=\'1\'><b>Liquidado</font></td>');  
          if (!(strpos($p_campos,'liquidar')===false))      ShowHTML('          <td><font size=\'1\'><b>A liquidar</font></td>');  
          ShowHTML('      </tr>');
          $w_linha+=1;
        }  
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if ($w_atual!=f($row,'chave')) {
          if (((!(strpos($p_campos,'aprovado')===false)) || (!(strpos($p_campos,'saldo')===false)) || (!(strpos($p_campos,'empenhado')===false)) || (!(strpos($p_campos,'liquidado')===false)) || (!(strpos($p_campos,'liquidar')===false))) && $p_sq_orprioridade=='' && $w_sq_siw_solicitacao>'') {
            ShowHTML('   <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('     <td colspan='.$w_col.' align="right"><b>Totais da iniciativa</td>');
            if (!(strpos($p_campos,'aprovado')===false)) ShowHTML('        <td align="right">'.number_format($w_acao_aprovado,2,',','.').'</td>');  
            if (!(strpos($p_campos,'empenhado')===false)) ShowHTML('        <td align="right">'.number_format($w_acao_empenhado,2,',','.').'</td>');  
            if (!(strpos($p_campos,'saldo')===false)) ShowHTML('        <td align="right">'.number_format($w_acao_saldo,2,',','.').'</td>');  
            if (!(strpos($p_campos,'liquidado')===false)) ShowHTML('        <td align="right">'.number_format($w_acao_liquidado,2,',','.').'</td>');   
            if (!(strpos($p_campos,'liquidar')===false)) ShowHTML('        <td align="right">'.number_format($w_acao_liquidar,2,',','.').'</td>');  
            ShowHTML('   </tr>');
            //ShowHTML '   <tr bgcolor=''' & conTrBgColor & ''' height=5><td colspan=10></td></tr>'
            $w_linha+=1;
          } 
          $w_acao_aprovado    = 0.00;
          $w_acao_saldo       = 0.00;
          $w_acao_empenhado   = 0.00;
          $w_acao_liquidado   = 0.00;
          $w_acao_liquidar    = 0.00;
          ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
          ShowHTML('        <td><b>Iniciativa: '.f($row,'nome').'</td>');
          if (!(strpos($p_campos,'responsavel')===false)) ShowHTML('        <td>'.Nvl(f($row,'responsavel'),'---').'</td>');
          if (!(strpos($p_campos,'email')===false)) ShowHTML('        <td>'.Nvl(f($row,'email'),'---').'</td>');
          if (!(strpos($p_campos,'telefone')===false)) ShowHTML('        <td>'.Nvl(f($row,'telefone'),'---').'</td>'); 
          if ((!(strpos($p_campos,'aprovado')===false)) || (!(strpos($p_campos,'saldo')===false)) || (!(strpos($p_campos,'empenhado')===false))|| (!(strpos($p_campos,'liquidado')===false)) || (!(strpos($p_campos,'liquidar')===false))) {
            ShowHTML('        <td colspan='.($w_col_word-$w_col).'>&nbsp;</td>');
          } 
          ShowHTML('         </tr>');
          $w_linha+=1;
        } 
        $w_sq_siw_solicitacao = Nvl(f($row,'sq_siw_solicitacao'),'');
        $w_atual              = f($row,'chave');
        if ($w_sq_siw_solicitacao>'') {
          ShowHTML('         <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          if ($w_tipo_rel=='WORD') {
            ShowHTML('        <td colspan='.$w_col.'><b>Ação:</b>'.f($row,'titulo').'</td>');
          } else {
            ShowHTML('        <td colspan='.$w_col.'><b>Ação:</b> <A class="HL" HREF="'.$w_dir.'projeto.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações da ação.">'.f($row,'titulo').'</a></td>');
          } 
          if (!(strpos($p_campos,'aprovado')===false)) ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'aprovado'),0),2,',','.').'</td>'); 
          if (!(strpos($p_campos,'empenhado')===false)) ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'empenhado'),0),2,',','.').'</td>');
          if (!(strpos($p_campos,'saldo')===false)) ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'aprovado'),0)-Nvl(f($row,'empenhado'),0),2,',','.').'</td>');
          if (!(strpos($p_campos,'liquidado')===false))  ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'liquidado'),0),2,',','.').'</td>');
          if (!(strpos($p_campos,'liquidar')===false))   ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'empenhado'),0)-Nvl(f($row,'liquidado'),0),2,',','.').'</td>');
          $w_linha+=1;
          ShowHTML('         </tr>');
        } else {
          // ShowHTML '        <td></td>'
          // ShowHTML '        <td colspan=' &  w_col & '>' & RS('titulo') & '</td>'
          // w_linha = w_linha + 1
        } 
        if ($w_sq_siw_solicitacao>'') {
          if ($p_metas>'') {
            ShowHTML('   <tr><td colspan='.$w_col_word.'>');
            ShowHTML('     <table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
            $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                    null,null,null,null,null,null,null,null,null,null,$w_sq_siw_solicitacao,null,
                    null,null,null,null,null,null,null,null,null,null,null,null,
                    null, f($row,'chave'));
            $RS2 = SortArray($RS2,'fim, prioridade','asc');
            if (count($RS2)<=0){
              ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(metas).</b></td></tr>');
              $w_linha+=1;
            } else {
              foreach ($RS2 as $row2){$RS2=$row2; break;}
              $RS3 = db_getSolicEtapa::getInstanceOf($dbms,f($RS2,'sq_siw_solicitacao'),null,'LSTNULL',null);
              $RS3 = SortArray($RS3,'ordem','asc');
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML(' <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(metas).</b></td></tr>');
                $w_linha+=1;
              } else {
                ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('          <td><b>Produto</td>');
                ShowHTML('          <td><b>Meta LOA</td>');
                ShowHTML('          <td><b>Fim previsto</td>');
                ShowHTML('          <td><b>Unidade<br>medida</td>');
                ShowHTML('          <td><b>Quantitativo<br>programado</td>');
                ShowHTML('          <td><b>% Realizado</td>');
                ShowHTML('        </tr>');
                $w_linha+=1;
                foreach ($RS3 as $row3) {
                  ShowHtml(EtapaLinha(f($RS2,'sq_siw_solicitacao'),f($row3,'sq_projeto_etapa'),f($row3,'titulo'),$w_tipo_rel,f($row3,'programada'),f($row3,'unidade_medida'),f($row3,'quantidade'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),'S','PROJETO'));
                  $w_linha+=1;
                } 
              } 
            } 
            ShowHTML('     </table>');
          } 
          if ($p_tarefas>'') {
            ShowHTML('     <tr><td colspan='.$w_col_word.'>');
            ShowHTML('       <table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
            $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                    null,null,null,null,null,null,null,null,null,null,$w_sq_siw_solicitacao,null,
                    null,null,null,null,null,null,null,null,null,null,null,null,
                    null, f($row,'chave'));
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(tarefas).</b></td></tr>');
              $w_linha+=1;
            } else {
              foreach($RS2 as $row2){$RS2=$row2; break;}
              $RS1= db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORPCAD');
              $RS3= db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                      null,null,null,null,null,null,null,$p_prioridade,null,null,null,null,
                      null,null,null,null,null,null,null,null,null,null,f($RS2,'sq_siw_solicitacao'),null,
                      null,null);
              if ($p_tarefas_atraso>'') {
                //$RS3->Filter='fim < '.time();  
              }         
              $RS3 = SortArray($RS3,'phpdt_fim','asc','prioridade','asc');
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML('   <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros(tarefas).</b></td></tr>');
                $w_linha+=1;
              } else {
                ShowHTML('   <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('     <td><b>Tarefas</td>');
                ShowHTML('     <td><b>Detalhamento</td>');
                ShowHTML('     <td><b>Responsável</td>');
                ShowHTML('     <td><b>Parcerias</td>');
                ShowHTML('     <td><b>Fim previsto</td>');
                ShowHTML('     <td><b>Programado</td>');
                ShowHTML('     <td><b>Executado</td>');
                ShowHTML('     <td><b>Fase atual</td>');
                if ($p_prioridade=='') ShowHTML('<td><b>Prioridade</td>');       
                ShowHTML('   </tr>');
                $w_linha+=1;
                foreach($RS3 as $row3) {
                  //If w_cor = conTrBgColor or w_cor = '' Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                  $w_cor=$conTrBgColor;
                  ShowHTML(' <tr bgcolor="'.$w_cor.'" valign="top">');
                  ShowHTML('   <td nowrap>');
                  if (f($row3,'concluida')=='N') {
                    if (f($row3,'fim')<addDays(time(),-1)) {
                      ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
                    } elseif (f($row3,'aviso_prox_conc')=='S' && (f($row3,'aviso')<=addDays(time(),-1))) {
                      ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
                    } else {
                      ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
                    } 
                  } else {
                    if (f($row3,'fim')<Nvl(f($row3,'fim_real'),f($row3,'fim'))) {
                      ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
                    } else {
                      ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
                    } 
                  } 
                  if ($w_tipo_rel=='WORD') {
                    ShowHTML(''.f($row3,'sq_siw_solicitacao').'&nbsp;');
                  } else {
                    ShowHTML('        <A class="HL" HREF="'.$w_dir.'projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row3,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" TARGET="VisualTarefa" title="Exibe as informações desta tarefa.">'.f($row3,'sq_siw_solicitacao').'&nbsp;</a>');
                  } 
                  ShowHTML('   </td>');
                  ShowHTML('   <td>'.Nvl(f($row3,'assunto'),'-').'</td>');
                  ShowHTML('   <td>'.f($row3,'nm_solic').'</td>');
                  ShowHTML('   <td>'.Nvl(f($row3,'proponente'),'---').'</td>');
                  ShowHTML('   <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row3,'fim')),'-').'</td>');
                  ShowHTML('   <td align="right">'.number_format(f($row3,'valor'),2,',','.').'&nbsp;</td>');
                  ShowHTML('   <td align="right">'.number_format(f($row3,'custo_real'),2,',','.').'&nbsp;</td>');
                  ShowHTML('   <td nowrap>'.f($row3,'nm_tramite').'</td>');
                  if ($p_prioridade=='') ShowHTML('<td nowrap>'.RetornaPrioridade(f($row3,'prioridade')).'</td>');        
                  ShowHTML(' </tr>');
                  $w_linha+=1;
                } 
              } 
            } 
            ShowHTML('       </table>');
          } 
          if ($p_sq_unidade_resp>'') {
            ShowHTML('     <tr><td colspan='.$w_col_word.'>');
            ShowHTML('       <table border=1 width="100%">');
            $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
            $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                    null,null,null,null,null,null,null,null,null,null,f($row,'sq_siw_solicitacao'),null,
                    null,null,null,null,null,null,null,null,null,null,null,null,
                    null, f($row,'chave'));
            $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
            if (count($RS2)<=0) {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="left"><b>Não foi informado o setor responsável.</b></td></tr>');
              $w_linha+=1;
            } else {
              ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" align="center">');
              ShowHTML('        <td align="left"><b>Setor responsável</td>');
              ShowHTML('      </tr>');
              $w_linha+=1;
              foreach($RS2 as $row2) {
                ShowHTML('   <tr bgcolor="'.$conTrBgColor.'" align="center">');
                ShowHTML('     <td align="left"><b>'.f($row2,'nm_unidade_resp').'</td>');
                ShowHTML('   </tr>');
                $w_linha+=1;
              } 
            } 
            ShowHTML('       </table>');  
          }
        } else {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan='.$w_col_word.' align="center"><b>Não foram encontrados registros.</td>');
          $w_linha+=1;
        } 
        ShowHTML('           </tr>');
        $w_acao_aprovado  = $w_acao_aprovado    + Nvl(f($row,'aprovado'),0.00);
        $w_acao_saldo     = $w_acao_saldo       + Nvl(f($row,'aprovado'),0.00)  - Nvl(f($row,'empenhado'),0.00);
        $w_acao_empenhado = $w_acao_empenhado   + Nvl(f($row,'empenhado'),0.00);
        $w_acao_liquidado = $w_acao_liquidado   + Nvl(f($row,'liquidado'),0.00);
        $w_acao_liquidar  = $w_acao_liquidar    + Nvl(f($row,'empenhado'),0.00) - Nvl(f($row,'liquidado'),0.00);
        $w_tot_aprovado   = $w_tot_aprovado     + Nvl(f($row,'aprovado'),0.00);
        $w_tot_saldo      = $w_tot_saldo        + Nvl(f($row,'aprovado'),0.00)  - Nvl(f($row,'empenhado'),0.00);
        $w_tot_empenhado  = $w_tot_empenhado    + Nvl(f($row,'empenhado'),0.00);
        $w_tot_liquidado  = $w_tot_liquidado    + Nvl(f($row,'liquidado'),0.00);
        $w_tot_liquidar   = $w_tot_liquidar     + Nvl(f($row,'empenhado'),0.00) - Nvl(f($row,'liquidado'),0.00);
      } 
      if ((!(strpos($p_campos,'aprovado')===false)) ||(!(strpos($p_campos,'saldo')===false)) || (!(strpos($p_campos,'empenhado')===false)) || (!(strpos($p_campos,'liquidado')===false)) || (!(strpos($p_campos,'liquidar')===false))) {
        if (Nvl($p_sq_orprioridade,'')>'') {
          ShowHTML('       <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('         <td colspan='.$w_col.' align="right"><b>Totais da iniciativa </b></td>');
          if (!(strpos($p_campos,'aprovado')===false)) ShowHTML('        <td align="right">'.number_format($w_acao_aprovado,2,',','.').'</td>');
          if (!(strpos($p_campos,'empenhado')===false)) ShowHTML('        <td align="right">'.number_format($w_acao_empenhado,2,',','.').'</td>'); 
          if (!(strpos($p_campos,'saldo')===false))  ShowHTML('        <td align="right">'.number_format($w_acao_saldo,2,',','.').'</td>'); 
          if (!(strpos($p_campos,'liquidado')===false)) ShowHTML('        <td align="right">'.number_format($w_acao_liquidado,2,',','.').'</td>');
          if (!(strpos($p_campos,'liquidar')===false)) ShowHTML('        <td align="right" nowrap>'.number_format($w_acao_liquidar,2,',','.').'</td>'); 
          ShowHTML('       </tr>');
          $w_linha+=1;
        } 
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" height=5><td colspan='.$w_col.'></td></tr>');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" valign="center" height=30>');
        ShowHTML('            <td colspan='.$w_col.' align="right"><font size="2"><b>Totais do relatório</td>');
        if (!(strpos($p_campos,'aprovado')===false)) ShowHTML('        <td align="right">'.number_format($w_tot_aprovado,2,',','.').'</td>');
        if (!(strpos($p_campos,'empenhado')===false)) ShowHTML('        <td align="right">'.number_format($w_tot_empenhado,2,',','.').'</td>');
        if (!(strpos($p_campos,'saldo')===false)) ShowHTML('        <td align="right">'.number_format($w_tot_saldo,2,',','.').'</td>');
        if (!(strpos($p_campos,'liquidado')===false)) ShowHTML('        <td align="right">'.number_format($w_tot_liquidado,2,',','.').'</td>'); 
        if (!(strpos($p_campos,'liquidar')===false)) ShowHTML('        <td align="right" nowrap>'.number_format($w_tot_liquidar,2,',','.').'</td>');
        ShowHTML('          </tr>');
        $w_linha+=1;
      } 
    } 
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('    </table>');
    ShowHTML('</center></div>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Tabela PPA',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('          <tr>');
    SelecaoOrPrioridade('<u>I</u>niciativa prioritária:','I',null,$p_sq_orprioridade,null,'p_sq_orprioridade','VINCULACAO',null);
    ShowHTML('          </tr>');
    ShowHTML('      <tr><td><b><u>R</u>esponsável:</b><br><input '.$w_disabled.' accesskey="R" type="text" name="p_responsavel" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$p_responsavel.'"></td>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    SelecaoPrioridade('<u>P</u>rioridade das tarefas:','P','Informe a prioridade da tarefa.',$p_prioridade,null,'p_prioridade',null,null);
    ShowHTML('          <td><b>Exibir somente tarefas em atraso?</b><br><input '.$w_Disabled.' type="radio" name="p_tarefas_atraso" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_tarefas_atraso" value="" checked> Não');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada MP?</b><br>');
    if ($p_selecionada_mpog=='S') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecionada_mpog.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value=""> Tanto faz');
    } elseif ($p_selecionada_mpog=='N') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value=""> Tanto faz');
    } else {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="" checked> Tanto faz');
    } 
    ShowHTML('          <td><b>Selecionada SE/MS?</b><br>');
    if ($p_selecionada_relevante=='S') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$p_selecionada_relevante.'" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value=""> Tanto faz');
    } elseif ($p_selecionada_relevante=='N') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="N" checked> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value=""> Tanto faz');
    } else {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="" checked> Tanto faz');
    } 
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('      <tr><td colspan=2><b>Campos a serem exibidos');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="responsavel"> Responsável</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="aprovado"> Aprovado</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="email"> e-Mail</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="saldo"> Saldo</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="telefone"> Telefone</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="liquidado"> Liquidado</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="liquidar"> A liquidar</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_campos[]" value="empenhado"> Empenhado</td>');
    ShowHTML('      <tr><td colspan=2><b>Blocos adicionais');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_metas" value="metas"> Metas físicas</td>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_tarefas" value="tarefas"> Tarefas</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td><INPUT '.$w_Disabled.' class="sti" type="CHECKBOX" name="p_sq_unidade_resp" value="unidade"> Setor responsável</td>');
    ShowHTML('     </table>');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($w_tipo_rel!='WORD') {
    Rodape();
  } 
} 
// =========================================================================
// Relatório Sintético de Iniciativas Prioritárias
// -------------------------------------------------------------------------
function Rel_Sintetico_IP() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave                  = $_REQUEST['w_chave'];
  $w_tipo_rel               = upper(trim($_REQUEST['w_tipo_rel']));
  $p_sq_orprioridade        = upper(trim($_REQUEST['p_sq_orprioridade']));
  $p_responsavel            = upper(trim($_REQUEST['p_responsavel']));
  $p_prioridade             = upper(trim($_REQUEST['p_prioridade']));
  $p_sq_unidade_resp        = upper(trim($_REQUEST['p_sq_unidade_resp']));
  $p_selecionada_mpog       = upper(trim($_REQUEST['p_selecionada_mpog']));
  $p_selecionada_relevante  = upper(trim($_REQUEST['p_selecionada_relevante']));
  $p_programada             = upper(trim($_REQUEST['p_programada']));
  $p_exequivel              = upper(trim($_REQUEST['p_exequivel']));
  $p_fim_previsto           = upper(trim($_REQUEST['p_fim_previsto']));
  $p_atraso                 = upper(trim($_REQUEST['p_atraso']));
  $p_tarefas_atraso         = upper(trim($_REQUEST['p_tarefas_atraso']));
  $w_cont = 0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
      $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
    } 
    // Recupera todos os registros para a listagem
    $RS = db_getOrPrioridade::getInstanceOf($dbms,null,$w_cliente,$p_sq_orprioridade,$p_responsavel,$p_selecionada_mpog,$p_selecionada_relevante);
    $RS = SortArray($RS,'ordem','asc');
  } if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag  = 1;
    $w_linha= 8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML('Iniciativa Prioritária');
    ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
    ShowHTML('</TD></TR>');
    ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Sintético das Iniciativas Prioritárias</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_orprioridade','Iniciativa prioritária','SELECT','','1','18','','1');
      Validate('p_responsavel','Responsável','1','','2','60','1','1');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean('onLoad=\'this.focus()\';');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Iniciativa Prioritária');
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<IMG BORDER=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\',\'VisualRelPPAWord\',\'menubar=yes resizable=yes scrollbars=yes\');">');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_sq_orprioridade.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='<tr valign="top">';
    if ($p_responsavel>'')  $w_filtro=$w_filtro.'<td>Responsável&nbsp;[<b>'.$p_responsavel.'</b>]&nbsp;';
    if ($p_prioridade>'') $w_filtro=$w_filtro.'<td>Prioridade&nbsp;[<b>'.RetornaPrioridade($p_prioridade).'</b>]&nbsp;'; 
    if ($p_selecionada_mpog>'') $w_filtro=$w_filtro.'<td>Selecionada MP&nbsp;[<b>'.$p_selecionada_mpog.'</b>]&nbsp;';
    if ($p_selecionada_relevante>'') $w_filtro=$w_filtro.'<td>Selecionada Relevante&nbsp;[<b>'.$p_selecionada_relevante.'</b>]&nbsp;';
    if ($p_programada>'')$w_filtro=$w_filtro.'<td>Meta LOA&nbsp;[<b>'.$p_programada.'</b>]&nbsp;';
    if ($p_exequivel>'') $w_filtro=$w_filtro.'<td>Meta será cumprida&nbsp;[<b>'.$p_exequivel.'</b>]&nbsp;';
    if ($p_fim_previsto>'') $w_filtro=$w_filtro.'<td>Metas em atraso&nbsp;[<b>'.$p_fim_previsto.'</b>]&nbsp;';
    if ($p_atraso>'') $w_filtro=$w_filtro.'<td>Ações em atraso&nbsp;[<b>'.$p_atraso.'</b>]&nbsp;';
    if ($p_tarefas_atraso>'') $w_filtro=$w_filtro.'<td>Ações com tarefas em atraso&nbsp;[<b>'.$p_tarefas_atraso.'</b>]&nbsp;';
    ShowHTML('<tr><td align="center">');
    if ($w_filtro>'') ShowHTML('<table border=0 width="100%"><tr><td width="25%"><b>Filtro:</b><td><ul>'.$w_filtro.'</ul></tr></table>');
    ShowHTML('<tr><td align="center" colspan="2">');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan="2"><b>Iniciativa Prioritária</td>');
    ShowHTML('          <td rowspan="2"><b>Ações Cadastradas</td>');
    ShowHTML('          <td rowspan="1" colspan="5"><b>Metas</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Produto</td>');
    ShowHTML('          <td><b>Unidade<br>medida</td>');
    ShowHTML('          <td><b>Quantitativo<br>programado</td>');
    ShowHTML('          <td><b>Quantitativo<br>realizado</td>');
    ShowHTML('          <td><b>% Realizado</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $w_cont+=1;
      $w_linha+=1;
      ShowHTML('      <tr><td colspan="7" align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_atual=0;
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        if ($w_linha>30 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha = 6;
          $w_pag   = $w_pag+1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.$w_logo.'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Iniciativa Prioritária');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          $w_filtro='<tr valign="top">';
          if ($p_responsavel>'')   $w_filtro=$w_filtro.'<td>Responsável&nbsp;[<b>'.$p_responsavel.'</b>]&nbsp;';        
          if ($p_prioridade>'')  $w_filtro=$w_filtro.'<td>Prioridade&nbsp;[<b>'.RetornaPrioridade($p_prioridade).'</b>]&nbsp;';
          if ($p_selecionada_mpog>'')   $w_filtro=$w_filtro.'<td>Selecionada MP&nbsp;[<b>'.$p_selecionada_mpog.'</b>]&nbsp;';
          if ($p_selecionada_relevante>'')   $w_filtro=$w_filtro.'<td>Selecionada Relevante&nbsp;[<b>'.$p_selecionada_relevante.'</b>]&nbsp;';
          if ($p_programada>'')   $w_filtro=$w_filtro.'<td>Meta LOA&nbsp;[<b>'.$p_programada.'</b>]&nbsp;';
          if ($p_exequivel>'')   $w_filtro=$w_filtro.'<td>Meta será cumprida&nbsp;[<b>'.$p_exequivel.'</b>]&nbsp;';
          if ($p_fim_previsto>'')   $w_filtro=$w_filtro.'<td>Metas em atraso&nbsp;[<b>'.$p_fim_previsto.'</b>]&nbsp;';
          if ($p_atraso>'')   $w_filtro=$w_filtro.'<td>Ações em atraso&nbsp;[<b>'.$p_atraso.'</b>]&nbsp;';
          if ($p_tarefas_atraso>'')   $w_filtro=$w_filtro.'<td>Ações com tarefas em atraso&nbsp;[<b>'.$p_tarefas_atraso.'</b>]&nbsp;';
          ShowHTML('<tr><td align="center">');
          if ($w_filtro>'')  ShowHTML('<table border=0 width="100%"><tr><td width="25%"><b>Filtro:</b><td><ul>'.$w_filtro.'</ul></tr></table>');
          ShowHTML('    <td align="right" valign="botton"><b>Registros listados: '.count($row));
          ShowHTML('<tr><td align="center" colspan="2">');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td rowspan="2"><b>Iniciativa Prioritária</td>');
          ShowHTML('          <td rowspan="2"><b>Ações Cadastradas</td>');
          ShowHTML('          <td rowspan="1" colspan="5"><b>Metas</td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Produto</td>');
          ShowHTML('          <td><b>Unidade<br>medida</td>');
          ShowHTML('          <td><b>Quantitativo<br>programado</td>');
          ShowHTML('          <td><b>Quantitativo<br>realizado</td>');
          ShowHTML('          <td><b>% Realizado</td>');
          ShowHTML('        </tr>');
        } 
        //Montagem da lista das ações
        $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
        $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
               null,null,null,null,$p_atraso,null,null,null,null,null,f($row,'sq_siw_solicitacao'),
               null,null,null,null,null,null,null,null,null,null,null,null,null,null,f($row,'chave'));
        $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
        //Variarel para o teste de existencia de metas e açoes para visualização no relatorio
        $w_teste_metas = 0;
        $w_teste_acoes = 0;
        //Recuperação e verificação das metas das ações de acordo com a visão do usuário
        if (count($RS2)>0) {
          $w_teste_acoes = 1;
          //Verificaçao da visao das ação do usuario
          //If cDbl(Nvl(RS2('solicitante'),0)) = cDbl(w_usuario) or _
          //   cDbl(Nvl(RS2('executor'),0))    = cDbl(w_usuario) or _
          //   cDbl(Nvl(RS2('cadastrador'),0)) = cDbl(w_usuario) or _
          //   cDbl(Nvl(RS2('titular'),0))     = cDbl(w_usuario) or _
          //   cDbl(Nvl(RS2('substituto'),0))  = cDbl(w_usuario) or _
          //   cDbl(Nvl(RS2('tit_exec'),0))    = cDbl(w_usuario) or _
          //   cDbl(Nvl(RS2('subst_exec'),0))  = cDbl(w_usuario) Then
          //   ' Se for solicitante, executor ou cadastrador, tem visão completa
          //   w_visao = 0
          //Else
          //   $RS = db_getSolicInter Rsquery, RS('sq_siw_solicitacao'), w_usuario, 'REGISTRO'
          //   If Not RSquery.EOF Then
          //      ' Se for interessado, verifica a visão cadastrada para ele.
          //      w_visao = cDbl(RSquery('tipo_visao'))
          //      RSquery.Close
          //   Else
          //      $RS = db_getSolicAreas Rsquery, RS('sq_siw_solicitacao'), Session('sq_lotacao'), 'REGISTRO'
          //      If Not RSquery.EOF Then
          //         ' Se for de uma das unidades envolvidas, tem visão parcial
          //         w_visao = 1
          //         RSquery.Close
          //      Else
          //         ' Caso contrário, tem visão resumida
          //         w_visao = 2
          //      End If
          //   End If
          //End If
          $w_visao = 0;
          if ($w_visao<2) {
            $RS3 = db_getSolicEtapa::getInstanceOf($dbms,f($RS2,'sq_siw_solicitacao'),null,'LSTNULL',null);
            if ($p_programada>'' && $p_exequivel>'' && $p_fim_previsto>'') {        
              $RS3->Filter='programada = \''.$p_programada.'\' and exequivel = \''.$p_exequivel.'\' and fim_previsto < \''.time().'\' and perc_conclusao < 100';
            } elseif ($p_programada>'' && $p_exequivel>'') {
              $RS3->Filter='programada = \''.$p_programada.'\' and exequivel = \''.$p_exequivel.'\'';
            } elseif ($p_programada>'' && $p_fim_previsto>'') {
              $RS3->Filter='programada = \''.$p_programada.'\' and fim_previsto < \''.time().'\' and perc_conclusao < 100';
            } elseif ($p_fim_previsto>'' && $p_exequivel>'') {
              $RS3->Filter='exequivel = \''.$p_exequivel.'\' and fim_previsto < \''.time().'\' and perc_conclusao < 100';
            } elseif ($p_programada>'') {
              $RS3->Filter='programada = \''.$p_programada.'\'';
            } elseif ($p_exequivel>'') {
              $RS3->Filter='exequivel = \''.$p_exequivel.'\'';
            } elseif ($p_fim_previsto>'') {
              $RS3->Filter='fim_previsto < \''.time().'\' and perc_conclusao < 100';
            } 
            $RS3 = SortArray($RS3,'ordem','asc');
            if (count(RS3)>0) {
              $w_teste_metas=1;
            } elseif ($p_programada=='' && $p_exequivel=='' && $p_fim_previsto=='') { 
              $w_teste_metas=3;
            } 
          } else {
            $w_teste_metas = 0;
          } 
        } else {
          if (f($row,'sq_siw_solicitacao')>'') {
            $w_teste_acoes = 1;
            $w_teste_metas = 0;
          } else {
            $w_teste_acoes = 0;
          } 
        } 
        if ($w_teste_metas==1 || $w_teste_metas==3) {
          //Inicio da montagem da lista das ações e metas de acordo com o filtro
          $w_cont+=1;
          if (($w_atual)!=f($row,'chave') || $p_programada>'' || $p_exequivel>'' || $p_fim_previsto>'' || $p_atraso>'') {
            ShowHTML('      <tr valign="top">');
            ShowHTML('        <td><b>'.f($row,'nome').'</td>');
          } else {
            ShowHTML('      <tr valign="top">');
            ShowHTML('        <td><b>&nbsp;</td>');
          } 
          $w_linha+=1;
          $w_sq_siw_solicitacao=Nvl(f($row,'sq_siw_solicitacao'),'');
          if ($w_sq_siw_solicitacao>'') {
            if ($w_tipo_rel=='WORD') {
              ShowHTML('        <td><b>'.f($row,'titulo').'</td>');
            } else {
              ShowHTML('        <td><b><A class="HL" HREF="'.$w_dir.'projeto.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1=1&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações da ação.">'.f($row,'titulo').'</a></td>');
            } 
            if (count($RS2)<=0) {
              ShowHTML('      <td colspan="5" align="center"><b>Não foram encontrados registros.<b></td>');
            } else {
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML('      <td colspan="5" align="center"><b>Não foram encontrados registros.</b></td></tr>');
              } else {
                if ($w_tipo_rel=='WORD') {
                  ShowHTML('      <td>'.f($RS3,'titulo').'</td>');
                } else {
                  ShowHTML('      <td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'projeto.php?par=AtualizaEtapa&O=V&w_chave='.f($RS2,'sq_siw_solicitacao').'&w_chave_aux='.f($RS3,'sq_projeto_etapa').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($RS3,'titulo').'</A></td>');
                } 
                ShowHTML('      <td nowrap align="center">'.Nvl(f($RS3,'unidade_medida'),'---').'</td>');
                ShowHTML('      <td nowrap align="right" >'.number_format(f($RS3,'quantidade'),0,',','.').'</td>');
                $RS4 = db_getEtapaMensal::getInstanceOf($dbms,f($RS3,'sq_projeto_etapa'));
                $RS4 = SortArray($RS4,'referencia','desc');
                if (count($RS4)>0) {
                  if (f($RS3,'cumulativa')=='S') {
                    ShowHTML('      <td nowrap align="right" >'.number_format(Nvl(f($RS4,'execucao_fisica'),0),0,',','.').'</td>');
                  } else {
                    $w_quantitativo_total=0;
                    foreach($RS4 as $row4) {
                      $w_quantitativo_total = $w_quantitativo_total  +   Nvl(f($row4,'execucao_fisica'),0);
                    } 
                    ShowHTML('      <td nowrap align="right" >'.number_format($w_quantitativo_total,0,',','.').'</td>');
                  } 
                } else {
                  ShowHTML('      <td nowrap align="right" >---</td>');
                } 
                ShowHTML('      <td nowrap align="right" >'.f($RS3,'perc_conclusao').'</td>');
                if (count($RS3)>0){
                  foreach(RS3 as $row3) {
                    ShowHTML('      <tr><td colspan="2">&nbsp;');
                    if ($w_tipo_rel=='WORD') {
                      ShowHTML('      <td>'.f($row3,'titulo').'</td>');
                    } else {
                      ShowHTML('      <td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'projeto.php?par=AtualizaEtapa&O=V&w_chave='.f($RS2,'sq_siw_solicitacao').'&w_chave_aux='.f($row3,'sq_projeto_etapa').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row3,'titulo').'</A></td>');
                    } 
                    ShowHTML('      <td nowrap align="center">'.Nvl(f($row3,'unidade_medida'),'---').'</td>');
                    ShowHTML('      <td nowrap align="right" >'.number_format(f($row3,'quantidade'),0,',','.').'</td>');
                    $RS4 = db_getEtapaMensal::getInstanceOf($dbms,f($row3,'sq_projeto_etapa'));
                    $RS4 = SortArray($RS4,'referencia','desc');
                    if (count($RS4)>0) {
                      if (f($row3,'cumulativa')=='S') {
                        ShowHTML('      <td nowrap align="right" >'.number_format(Nvl(f($RS4,'execucao_fisica'),0),0,',','.').'</td>');
                      } else {
                        $w_quantitativo_total=0;
                        foreach ($RS4 as $row4) {
                          $w_quantitativo_total =   $w_quantitativo_total   +   Nvl(f($row4,'execucao_fisica'),0);
                        } 
                        ShowHTML('      <td nowrap align="right" >'.number_format($w_quantitativo_total,0,',','.').'</td>');
                      } 
                    } else {
                      ShowHTML('      <td nowrap align="right" >---</td>');
                    } 
                    ShowHTML('           <td nowrap align="right" >'.f($row3,'perc_conclusao').'</td>');
                    ShowHTML('        </tr>');
                    $w_linha+=1;
                  } 
                } 
              } 
            }   
          } else {
            ShowHTML('        <td colspan="6" align="middle"><b>Não foram encontrados registros.</b></td>');
          }  
        } else {
          if ($p_programada=='' && $p_exequivel=='' && $p_fim_previsto=='' && $p_atraso=='') {
            $w_cont+=1;
            if ($w_atual!=f($row,'chave')) {
              ShowHTML('      <tr valign="top">');
              ShowHTML('        <td><b>'.f($row,'nome').'</td>');
            } else {
              ShowHTML('      <tr valign="top">');
              ShowHTML('        <td><b>&nbsp;</td>');
            } 
            $w_linha+=1;
            if ($w_teste_acoes==1) {
              ShowHTML('        <td colspan="1"><b>'.f($row,'titulo').'</b></td>');
              ShowHTML('        <td colspan="5" align="center"><b>Não há permissão para visualização da ação<b></td>');
            } else {
              ShowHTML('        <td colspan="6" align="center"><b>Não foram encontrados registros.</b></td>');
            } 
          } 
        } 
        $w_atual = f($row,'chave');
      } 
    } if ($w_cont==0) {
      ShowHTML('        <td colspan="7" align="center"><b>Não foram encontrados registros.</b></td>');
    } 
      ShowHTML('      </center>');
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Tabela PPA',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('          <tr>');
    SelecaoOrPrioridade('<u>I</u>niciativa prioritária:','I',null,$p_sq_orprioridade,null,'p_sq_orprioridade','VINCULACAO',null);
    ShowHTML('          </tr>');
    ShowHTML('      <tr><td><b><u>R</u>esponsável:</b><br><input '.$w_disabled.' accesskey="R" type="text" name="p_responsavel" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$p_responsavel.'"></td>');
    //ShowHTML '      <tr>'
    //SelecaoPrioridade '<u>P</u>rioridade das tarefas:', 'P', 'Informe a prioridade da tarefa.', p_prioridade, null, 'p_prioridade', null, null
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada MP?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="" checked> Tanto faz');
    ShowHTML('          <td><b>Selecionada SE/MS?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="" checked> Tanto faz');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente metas da LOA?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_programada" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_programada" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente metas que não serão cumpridas?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_exequivel" value="N"> Sim <br><input '.$w_Disabled.' type="radio" name="p_exequivel" value="" checked> Não');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente metas em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_fim_previsto" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_fim_previsto" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente ações em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_atraso" value="" checked> Não');
    //ShowHTML '      <tr valign=''top''>'
    //ShowHTML '          <td><b>Exibir ações com tarefas em atraso?</b><br>'
    //ShowHTML '              <input ' & w_Disabled & ' type=''radio'' name=''p_tarefas_atraso'' value=''S''> Sim <br><input ' & w_Disabled & ' type=''radio'' name=''p_tarefas_atraso'' value='''' checked> Não'
    ShowHTML('          </table>');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
     //ShowHTML ' history.back(1);'
       ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($w_tipo_rel!='WORD') {
    Rodape();
  } 
} 
// =========================================================================
// Relatório Sintético das Ações do PPA
// -------------------------------------------------------------------------
function Rel_Sintetico_PPA() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave                   = $_REQUEST['w_chave'];
  $w_tipo_rel                = upper(trim($_REQUEST['w_tipo_rel']));
  $p_sq_acao_ppa_pai         = upper(trim($_REQUEST['p_sq_acao_ppa_pai']));
  $p_sq_acao_ppa             = upper(trim($_REQUEST['p_sq_acao_ppa']));
  $p_responsavel             = upper(trim($_REQUEST['p_responsavel']));
  $p_sq_unidade_resp         = upper(trim($_REQUEST['p_sq_unidade_resp']));
  $p_prioridade              = upper(trim($_REQUEST['p_prioridade']));
  $p_selecionada_mpog        = upper(trim($_REQUEST['p_selecionada_mpog']));
  $p_selecionada_relevante   = upper(trim($_REQUEST['p_selecionada_relevante']));
  $p_programada              = upper(trim($_REQUEST['p_programada']));
  $p_exequivel               = upper(trim($_REQUEST['p_exequivel']));
  $p_fim_previsto            = upper(trim($_REQUEST['p_fim_previsto']));
  $p_atraso                  = upper(trim($_REQUEST['p_atraso']));
  $p_tarefas_atraso          = upper(trim($_REQUEST['p_tarefas_atraso']));
  $w_cont = 0;
  $w_teste_pai=0;
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens  
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'') {
        $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);  
    } 
    // Recupera todos os registros para a listagem
    $RS = db_getAcaoPPA::getInstanceOf($dbms,null,$w_cliente,$p_sq_acao_ppa_pai,$p_sq_acao_ppa,$p_responsavel,$p_selecionada_mpog,$p_selecionada_relevante,null,null,null,null);
    $RS = SortArray($RS,'ordena','asc');
  } if ($w_tipo_rel=='WORD') {
      HeaderWord($_REQUEST['orientacao']);
      $w_pag   = 1;
      $w_linha = 8;
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Ações do PPA');
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Relatório Sintético das Ações do PPA</TITLE>');
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_sq_acao_ppa_pai','Programa','SELECT','','1','18','','1');
      Validate('p_sq_acao_ppa','Ação','SELECT','','1','18','','1');
      Validate('p_responsavel','Responsável','1','','2','60','1','1');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean('onLoad=\'this.focus()\';');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
      ShowHTML('Ações do PPA');
      ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<IMG BORDER=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_tipo_rel=word&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\',\'VisualRelPPAWord\',\'menubar=yes resizable=yes scrollbars=yes\');">');
      ShowHTML('</TD></TR>');
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_sq_acao_ppa_pai.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    } 
    ShowHTML('<HR>');
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro='<tr valign="top">';
    if ($p_responsavel>'')           $w_filtro = $w_filtro.'<td>Responsável&nbsp;[<b>'.$p_responsavel.'</b>]&nbsp;';
    if ($p_prioridade>'')            $w_filtro = $w_filtro.'<td>Prioridade&nbsp;[<b>'.RetornaPrioridade($p_prioridade).'</b>]&nbsp;';
    if ($p_selecionada_mpog>'')      $w_filtro = $w_filtro.'<td>Selecionada MP&nbsp;[<b>'.$p_selecionada_mpog.'</b>]&nbsp;';
    if ($p_selecionada_relevante>'') $w_filtro = $w_filtro.'<td>Selecionada Relevante&nbsp;[<b>'.$p_selecionada_relevante.'</b>]&nbsp;';
    if ($p_programada>'')            $w_filtro = $w_filtro.'<td>Meta LOA&nbsp;[<b>'.$p_programada.'</b>]&nbsp;';
    if ($p_exequivel>'')             $w_filtro = $w_filtro.'<td>Meta será cumprida&nbsp;[<b>'.$p_exequivel.'</b>]&nbsp;';
    if ($p_fim_previsto>'')          $w_filtro = $w_filtro.'<td>Metas em atraso&nbsp;[<b>'.$p_fim_previsto.'</b>]&nbsp;';
    if ($p_atraso>'')                $w_filtro = $w_filtro.'<td>Ações em atraso&nbsp;[<b>'.$p_atraso.'</b>]&nbsp;'; 
    if ($p_tarefas_atraso>'')        $w_filtro = $w_filtro.'<td>Ações com tarefas em atraso&nbsp;[<b>'.$p_tarefas_atraso.'</b>]&nbsp;';
    ShowHTML('<tr><td align="left">');
    if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
    ShowHTML('<tr><td align="center" colspan="2">');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan="1" colspan="2"><b>Programas</td>');
    ShowHTML('          <td rowspan="1" colspan="2"><b>Ações</td>');
    $RS1 = db_getOrImport::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null);
    $RS1 = SortArray($RS1,'phpdt_data_arquivo','desc');
    ShowHTML('          <td rowspan="1" colspan="5"><b>Dados SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: '.Nvl(FormataDataEdicao(f($RS1,'data_arquivo')),'-').'</td>');
    ShowHTML('          <td rowspan="1" colspan="6"><b>Metas</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Cód</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Cód</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Aprovado</td>');
    ShowHTML('          <td><b>Empenhado</td>');
    ShowHTML('          <td><b>Saldo</td>');
    ShowHTML('          <td><b>Liquidado</td>');
    ShowHTML('          <td><b>A liquidar</td>');
    ShowHTML('          <td><b>Produto</td>');
    ShowHTML('          <td><b>Unidade<br>medida</td>');
    ShowHTML('          <td><b>Quantitativo<br>programado</td>');
    ShowHTML('          <td><b>Quatintativo<br>realizado</td>');
    ShowHTML('          <td><b>% Realizado</td>');
    ShowHTML('          <td><b>Meta<br>LOA</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $w_cont+=1;
      $w_linha+=1;
      ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td colspan=16 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_atual=0;
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        if ($w_linha>19 && $w_tipo_rel=='WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=6;
          $w_pag+=1;
          ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.$w_logo.'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
          ShowHTML('Iniciativa Prioritária');
          ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
          ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
          ShowHTML('</TD></TR>');
          ShowHTML('</FONT></B></TD></TR></TABLE>');
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ShowHTML('<tr><td align="left">');
          if ($w_filtro>'') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>');
          ShowHTML('<tr><td align="center" colspan="2">');
          ShowHTML('      <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td rowspan="1" colspan="2"><b>Programas</td>');
          ShowHTML('          <td rowspan="1" colspan="2"><b>Ações</td>');
          $RS1 = db_getOrImport::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null);
          $RS1 = SortArray($RS1,'phpdt_data_arquivo','desc');
          ShowHTML('          <td rowspan="1" colspan="5"><b>Dados SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: '.Nvl(FormataDataEdicao(f($RS1,'data_arquivo')),'-').'</td>');
          ShowHTML('          <td rowspan="1" colspan="6"><b>Metas</td>');
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td><b>Cód</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td><b>Cód</td>');
          ShowHTML('          <td><b>Nome</td>');
          ShowHTML('          <td><b>Aprovado</td>');
          ShowHTML('          <td><b>Empenhado</td>');
          ShowHTML('          <td><b>Saldo</td>');
          ShowHTML('          <td><b>Liquidado</td>');
          ShowHTML('          <td><b>A liquidar</td>');
          ShowHTML('          <td><b>Produto</td>');
          ShowHTML('          <td><b>Unidade<br>medida</td>');
          ShowHTML('          <td><b>Quantitativo<br>programado</td>');
          ShowHTML('          <td><b>Quatintativo<br>realizado</td>');
          ShowHTML('          <td><b>% Realizado</td>');
          ShowHTML('          <td><b>Meta<br>LOA</td>');
          ShowHTML('        </tr>');
        } 
        if (Nvl(f($row,'sq_acao_ppa_pai'),'')!='') {
          //Montagem da lista das ações
          $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
          $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),5,
                 null,null,null,null,$p_atraso,null,null,null,null,null,f($row,'sq_siw_solicitacao'),null,
                 null,null,null,null,null,null,null,null,null,null,null,null,f($row,'chave'),null);
          $RS2 = SortArray($RS2,'phpdt_fim','asc','prioridade','asc');
          foreach($RS2 as $row2){$RS2=$row2; break;}
          //Variarel para o teste de existencia de metas e açoes para visualização no relatorio
          $w_teste_metas = 0 ;
          $w_teste_acoes = 0;
          //Recuperação e verificação das metas das ações de acordo com a visão do usuário
          if (count($RS2)>0) {
            $w_teste_acoes = 1;
            $w_visao = 0;
            if ($w_visao<2) {
              $RS3 = db_getSolicEtapa::getInstanceOf($dbms,f($RS2,'sq_siw_solicitacao'),null,'LSTNULL',null);
              $RS3 = SortArray($RS3,'ordem','asc');
              if (count($RS3)>0) {
                $w_teste_metas = 1;
              } elseif ($p_programada=='' && $p_exequivel=='' && $p_fim_previsto=='') {
                $w_teste_metas = 3;
              }           
            } else {
              $w_teste_metas = 0;
            } 
          } else {
            if (f($row,'sq_siw_solicitacao')>'') {
              $w_teste_acoes = 1;
              $w_teste_metas = 0;
            } else {
              $w_teste_acoes=0;
            } 
          } 
        }
        if ($w_teste_metas==1 || $w_teste_metas==3) {
          //Inicio da montagem da lista das ações e metas de acordo com o filtro
          $w_cont+=1;
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          if (Nvl(f($row,'sq_acao_ppa_pai'),'')=='' || $p_programada>'' || $p_exequivel>'' || $p_fim_previsto>'' || $p_atraso>'') {
            ShowHTML(' <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
            ShowHTML('   <td><b>'.f($row,'codigo').'</td>');
            ShowHTML('   <td><b>'.f($row,'nome').'</td>');
            $w_atual=1;
          } else {
            if(w_atual!=1) {
              ShowHTML(' <tr valign="top">');
              ShowHTML('   <td><b>&nbsp;</td>');
              ShowHTML('   <td><b>&nbsp;</td>');
            }
            $w_atual=0;
          }
          if(w_atual!=1) {
            $w_linha+=1;
            ShowHTML('      <td><b>'.f($row,'codigo').'</td>');
            if ($w_tipo_rel=='WORD' || f($row,'acao')==0) {
              ShowHTML('   <td><b>'.f($row,'nome').'</td>');
            } else {
              ShowHTML('   <td><b><A class="HL" HREF="'.$w_dir.'projeto.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TARGET="VisualAcao" title="Exibe as informações da ação.">'.f($row,'nome').'</a></td>');
            } 
            ShowHTML('      <td align="right" nowrap>'.number_format(Nvl(f($row,'aprovado'),0.00),2,',','.').'</td>');
            ShowHTML('      <td align="right" nowrap>'.number_format(Nvl(f($row,'empenhado'),0.00),2,',','.').'</td>');
            ShowHTML('      <td align="right" nowrap>'.number_format(Nvl(f($row,'aprovado'),0.00)-Nvl(f($row,'empenhado'),0.00),2,',','.').'</td>');
            ShowHTML('      <td align="right" nowrap>'.number_format(Nvl(f($row,'liquidado'),0.00),2,',','.').'</td>');
            ShowHTML('      <td align="right" nowrap>'.number_format(Nvl(f($row,'empenhado'),0.00)-Nvl(f($row,'liquidado'),0.00),2,',','.').'</td>');
            if (count($RS2)<=0) {          
              ShowHTML('   <td colspan="6" align="center"><b>Não foram encontrados registros.</b></td>');
            } else {
              if (count($RS3)<=0) {
                // Se não foram selecionados registros, exibe mensagem
                ShowHTML('<td colspan="6" align="center"><b>Não foram encontrados registros.</b></td>');
              } else {
                $i = 0;
                foreach ($RS3 as $row3) {
                  if ($i==1) ShowHTML('<tr><td colspan="9">&nbsp;');
                  $i=1;
                  if ($w_tipo_rel=='WORD') {
                    ShowHTML('<td>'.f($row3,'titulo').'</td>');
                  } else {
                    ShowHTML('<td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'projeto.php?par=AtualizaEtapa&O=V&w_chave='.f($RS2,'sq_siw_solicitacao').'&w_chave_aux='.f($row3,'sq_projeto_etapa').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta'.'\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row3,'titulo').'</A></td>');
                  } 
                  ShowHTML('      <td nowrap align="center">'.Nvl(f($row3,'unidade_medida'),'---').'</td>');
                  ShowHTML('      <td nowrap align="right">'.f($row3,'quantidade').'</td>');
                  $RS4 = db_getEtapaMensal::getInstanceOf($dbms,f($row3,'sq_projeto_etapa'));
                  $RS4 = SortArray($RS4,'phpdt_referencia','desc');
                  if (count($RS4)>0) {
                    if (f($row3,'cumulativa')=='S') {
                      foreach($RS4 as $row4){$RS4=$row4; break;}
                      ShowHTML('      <td nowrap align="right">'.Nvl(f($RS4,'execucao_fisica'),0).'</td>');
                    } else {
                      $w_quantitativo_total=0;
                      foreach ($RS4 as $row4) {
                        $w_quantitativo_total = $w_quantitativo_total + Nvl(f($row4,'execucao_fisica'),0);
                      } 
                      ShowHTML('      <td nowrap align="right">'.$w_quantitativo_total.'</td>');
                    } 
                  } else {
                    ShowHTML('      <td nowrap align="right">---</td>');
                  } 
                  $w_linha=$w_linha+1;
                } 
              } 
            } 
          }
        } else {
          if ($p_programada=='' && $p_exequivel=='' && $p_fim_previsto=='' && $p_atraso=='') {
            $w_cont+=1;
            if (Nvl(f($row,'sq_acao_ppa_pai'),'')=='') {
              ShowHTML(' <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
              ShowHTML('   <td><b>'.f($row,'codigo').'</td>');
              ShowHTML('   <td><b>'.f($row,'nome').'</td>');
              $w_atual=1;
            } else {
              if(w_atual!=1) {
                ShowHTML(' <tr valign="top">');
                ShowHTML('   <td><b>&nbsp;</td>');
                ShowHTML('   <td><b>&nbsp;</td>');
              }
              $w_atual=0;
            }
            if(w_atual!=1) {
              $w_linha+=1;
              if ($w_teste_acoes==1) {
                ShowHTML('        <td colspan="1"><b>'.f($row,'codigo').'</b></td>');
                ShowHTML('        <td colspan="1"><b>'.f($row,'nome').'</b></td>');
                if ($w_teste_metas==3) {
                  ShowHTML('        <td colspan="11" align="center"><b>Não foram encontrados registros.<b></td>');
                } else {
                  ShowHTML('        <td colspan="11" align="center"><b>Não há permissão para visualização da ação<b></td>');
                } 
              } else {
                ShowHTML('        <td colspan="1"><b>'.f($row,'codigo').'</b></td>');
                ShowHTML('        <td colspan="1"><b>'.f($row,'nome').'</b></td>');
                ShowHTML('        <td colspan="11" align="center"><b>Não foram encontrados registros.</b></td>');
              } 
            } 
          } 
        } 
      }
    } 
    if ($w_cont==0) {
      ShowHTML('        <td colspan="17" align="center"><b>Não foram encontrados registros.</b></td>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {  
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Tabela PPA',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoAcaoPPA_OR('<u>P</u>rograma:','S',null,$p_sq_acao_ppa_pai,$w_chave,'p_sq_acao_ppa_pai','CADASTRO',null);
    ShowHTML('      <tr>');
    SelecaoAcaoPPA_OR('<u>A</u>ção:','A',null,$p_sq_acao_ppa,$w_chave,'p_sq_acao_ppa','CONSULTA',null);
    ShowHTML('      <tr><td><b><u>R</u>esponsável:</b><br><input '.$w_disabled.' accesskey="R" type="text" name="p_responsavel" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$p_responsavel.'"></td>');
    ShowHTML('      <tr>');
    //SelecaoPrioridade '<u>P</u>rioridade das tarefas:', 'P', 'Informe a prioridade da tarefa.', p_prioridade, null, 'p_prioridade', null, null
    ShowHTML('      <tr><td colspan=3><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b>Selecionada MP?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_mpog" value="" checked> Tanto faz');
    ShowHTML('          <td><b>Selecionada SE/MS?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="S"> Sim <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="N"> Não <input '.$w_Disabled.' type="radio" name="p_selecionada_relevante" value="" checked> Tanto faz');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente metas da LOA?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_programada" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_programada" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente metas que não serão cumpridas?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_exequivel" value="N"> Sim <br><input '.$w_Disabled.' type="radio" name="p_exequivel" value="" checked> Não');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Exibir somente metas em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_fim_previsto" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_fim_previsto" value="" checked> Não');
    ShowHTML('          <td><b>Exibir somente ações em atraso?</b><br>');
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="p_atraso" value="" checked> Não');
    //ShowHTML '      <tr valign=''top''>'
    //ShowHTML '          <td><b>Exibir ações com tarefas em atraso?</b><br>'
    //ShowHTML '              <input ' & w_Disabled & ' type=''radio'' name=''p_tarefas_atraso'' value=''S''> Sim <br><input ' & w_Disabled & ' type=''radio'' name=''p_tarefas_atraso'' value='''' checked> Não'
    ShowHTML('          </table>');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($w_tipo_rel!='WORD') {
    Rodape();
  } 
} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------

function Grava() {
  extract($GLOBALS);
  global $w_Disabled;
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'ORTBINIC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {   
        dml_putOrPrioridade::getInstanceOf($dbms,$O,
        $_REQUEST['w_chave'],$w_cliente,$_REQUEST['w_codigo'],$_REQUEST['w_nome'],
        $_REQUEST['w_responsavel'],$_REQUEST['w_telefone'],$_REQUEST['w_email'],
        $_REQUEST['w_ordem'],$_REQUEST['w_ativo'],$_REQUEST['w_padrao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'ORTBPPA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putAcaoPPA::getInstanceOf($dbms,$O,
          $_REQUEST['w_chave'],$w_cliente,$_REQUEST['w_sq_acao_ppa_pai'],
          $_REQUEST['w_codigo'],$_REQUEST['w_nome'],
          $_REQUEST['w_responsavel'],$_REQUEST['w_telefone'],$_REQUEST['w_email'],
          $_REQUEST['w_ativo'],$_REQUEST['w_padrao'],$_REQUEST['w_aprovado'],$_REQUEST['w_saldo'],
          $_REQUEST['w_empenhado'],$_REQUEST['w_liquidado'],$_REQUEST['w_liquidar'],$_REQUEST['w_selecionada_mpog'],
          $_REQUEST['w_selecionada_relevante'],null,null);
        
       
        
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
      break;
  } 
} 
// =========================================================================
// Gera uma linha de apresentação da tabela de etapas
// -------------------------------------------------------------------------
function EtapaLinha($l_chave,$l_chave_aux,$l_titulo,$l_word,$l_programada,$l_unidade_medida,$l_quantidade,$l_fim,$l_perc,$l_oper,$l_tipo) {
  extract($GLOBALS);
  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html=$l_html.chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
  $l_html=$l_html.chr(13).'        <td nowrap '.$l_row.'>';
  if ($l_fim<time() && $l_perc<100) {
    $l_html=$l_html.chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">';
  } elseif (($l_perc)<100) {
    $l_html=$l_html.chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
  } else {
    $l_html=$l_html.chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
  } if ($l_word!='WORD') {
    $l_html=$l_html.chr(13).'<A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS($w_dir,'projeto.php?par=AtualizaEtapa&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.$l_titulo.'</A>';
  } else {
    $l_html=$l_html.chr(13).'        '.$l_titulo.'</td>';
  } 
  $l_html       = $l_html.chr(13).'        <td align="center">'.RetornaSimNao($l_programada).'</b>';
  $l_html       = $l_html.chr(13).'        <td align="center" '.$l_row.'>'.FormataDataEdicao($l_fim).'</td>';
  $l_html       = $l_html.chr(13).'        <td nowrap '.$l_row.'>'.Nvl($l_unidade_medida,'---').' </td>';
  $l_html       = $l_html.chr(13).'        <td nowrap align="right" '.$l_row.'>'.$l_quantidade.'</td>';
  $l_html       = $l_html.chr(13).'        <td nowrap align="right" '.$l_row.'>'.$l_perc.' %</td>';
  $l_html       = $l_html.chr(13).'      </tr>';
  return $l_html;
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par){
    case 'INICIATIVA':          Iniciativa();           break;
    case 'PPA':                 PPA();                  break;
    case 'REL_PPA':             Rel_PPA();              break;
    case 'REL_INICIATIVA':      Rel_Iniciativa();       break;
    case 'REL_SINTETICO_IP':    Rel_Sintetico_IP();     break;
    case 'REL_SINTETICO_PPA':   Rel_Sintetico_PPA();    break;
    case 'GRAVA':               Grava();                break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      break;
  } 
} 
?>