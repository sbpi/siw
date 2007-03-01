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
include_once($w_dir_volta.'classes/sp/db_getTipoDespacho_PA.php');
include_once($w_dir_volta.'classes/sp/db_getEspecieDocumento_PA.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PA.php');
include_once($w_dir_volta.'classes/sp/db_getNaturezaDoc_PA.php');
include_once($w_dir_volta.'classes/sp/db_getTipoGuarda_PA.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoDespacho_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putEspecieDocumento_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putUnidade_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putNaturezaDoc_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoGuarda_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putPAParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putAssunto_PA.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDespacho.php');
include_once($w_dir_volta.'funcoes/selecaoTipoGuarda.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');

// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerenciar tabelas básicas do módulo	
// Mail     : celso@sbpi.com.br
// Criacao  : 08/02/2007, 16:30
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
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);
$w_assinatura = strtoupper($_REQUEST['w_assinatura']);
$w_pagina     = 'tabelas.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_pa/';
$w_troca      = $_REQUEST['w_troca'];
$p_ordena     = $_REQUEST['p_ordena'];

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';        break;
  case 'A': $w_TP=$TP.' - Alteração';       break;
  case 'E': $w_TP=$TP.' - Exclusão';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - Cópia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'M': $w_TP=$TP.' - Serviços';        break;
  case 'H': $w_TP=$TP.' - Herança';         break;
  case 'T': $w_TP=$TP.' - Ativar';          break;
  case 'D': $w_TP=$TP.' - Desativar';       break;
  default:  $w_TP=$TP.' - Listagem';        break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Manter Tabela básica "Tipo de despacho"
// -------------------------------------------------------------------------
function TipoDespacho() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave     = $_REQUEST['w_chave'];
    $w_nome      = $_REQUEST['w_nome'];
    $w_sigla     = $_REQUEST['w_sigla'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_ativo     = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados chave informada
    $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,$w_chave,$w_cliente,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave     = f($RS,'chave');
    $w_nome      = f($RS,'nome');
    $w_sigla     = f($RS,'sigla');
    $w_descricao = f($RS,'descricao');
    $w_ativo     = f($RS,'ativo');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','4','60','1','1');
      Validate('w_sigla','Sigla','1','1','2','10','1','1');
      Validate('w_descricao','Descrição','1','1','4','255','1','1');
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
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Sigla</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      <tr><td><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=5 cols=80 title="Detalhe o tipo de despacho." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
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
// Manter Tabela básica 'Especies do documentos'
// -------------------------------------------------------------------------
function EspecieDocumento() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave    = $_REQUEST['w_chave'];
    $w_nome     = $_REQUEST['w_nome'];
    $w_sigla    = $_REQUEST['w_sigla'];
    $w_ativo    = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endereço informado
    $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,$w_chave,$w_cliente,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave    = f($RS,'chave');
    $w_cliente  = f($RS,'cliente');
    $w_nome     = f($RS,'nome');
    $w_sigla    = f($RS,'sigla');
    $w_ativo    = f($RS,'ativo');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','4','30','1','1');
      Validate('w_sigla','Sigla','1','1','1','10','1','1');
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
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Sigla</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>'); 
    ShowHTML('           <td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>'); 
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
// Rotina de unidade
// -------------------------------------------------------------------------
function Unidade() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_unidade_pai        = $_REQUEST['w_unidade_pai'];
    $w_nome               = $_REQUEST['w_nome'];
    $w_sigla              = $_REQUEST['w_sigla'];
    $w_registra_documento = $_REQUEST['w_registra_documento'];
    $w_autua_processo     = $_REQUEST['w_autua_processo'];
    $w_prefixo            = $_REQUEST['w_prefixo'];
    $w_nr_documento       = $_REQUEST['w_nr_documento'];
    $w_nr_tramite         = $_REQUEST['w_nr_tramite'];
    $w_nr_transferencia   = $_REQUEST['w_nr_transferencia'];
    $w_nr_eliminacao      = $_REQUEST['w_nr_eliminacao'];
    $w_arquivo_setorial   = $_REQUEST['w_arquivo_setorial'];
    $w_ativo              = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getUnidade_PA::getInstanceOf($dbms,$w_cliente,null,null,null);
    $RS = SortArray($RS,'ordena','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endereço informado
    $RS = db_getUnidade_PA::getInstanceOf($dbms,$w_cliente,$w_chave,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_unidade_pai        = f($RS,'sq_unidade_pai');
    $w_nome               = f($RS,'nome');
    $w_sigla              = f($RS,'sigla');
    $w_registra_documento = f($RS,'registra_documento');
    $w_autua_processo     = f($RS,'autua_processo');
    $w_prefixo            = f($RS,'prefixo');
    $w_nr_documento       = f($RS,'numero_documento');
    $w_nr_tramite         = f($RS,'numero_tramite');
    $w_nr_transferencia   = f($RS,'numero_transferencia');
    $w_nr_eliminacao      = f($RS,'numero_eliminacao');
    $w_arquivo_setorial   = f($RS,'arquivo_setorial');
    $w_ativo              = f($RS,'ativo');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    FormataCNPJ();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      if ($O=='I') {
        Validate('w_chave','Unidade','SELECT','1','1','18','','1');
      } 
      ShowHTML('  if (theForm.w_chave.value==theForm.w_unidade_pai[theForm.w_unidade_pai.selectedIndex].value) {');
      ShowHTML('     alert(\'Não é permitido subordinar uma unidade a si mesma!\'); ');
      ShowHTML('     theForm.w_unidade_pai.focus(); ');
      ShowHTML('     return false; ');
      ShowHTML('  }; ');
      if (nvl($w_unidade_pai,'')=='') {
        Validate('w_prefixo','Prefixo','1','1','5','5','1','1');
        Validate('w_nr_documento','Número de criação do documento','1','1','1','10','','1');
        Validate('w_nr_tramite','Número da guia de remessa','1','','1','5','','1');
        Validate('w_nr_transferencia','Número da guia de transferência','1','','1','5','','1');
        Validate('w_nr_eliminacao','Número da guia de eliminação','1','','1','5','','1');
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
  } elseif (strpos('I',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_chave.focus()\';');
  } elseif (strpos('A',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_unidade_pai.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpenClean(null);
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
    ShowHTML('          <td colspan=2 rowspan=2><b>Unidade</td>');
    ShowHTML('          <td colspan=5><b>Numeração automática</td>');
    ShowHTML('          <td rowspan=2><b>Registra<br>documentos</td>');
    ShowHTML('          <td rowspan=2><b>Autua<br>processos</td>');
    ShowHTML('          <td rowspan=2><b>Arquivo<br>setorial</td>');
    ShowHTML('          <td rowspan=2><b>Ativo</td>');
    ShowHTML('          <td rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Prefixo</td>');
    ShowHTML('          <td><b>Doc.</td>');
    ShowHTML('          <td><b>Remessa</td>');
    ShowHTML('          <td><b>Transf.</td>');
    ShowHTML('          <td><b>Elim.</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if (nvl(f($row,'sq_unidade_pai'),'')=='') {
          ShowHTML('        <td colspan=2>'.f($row,'nome').' ('.f($row,'sigla').')</td>');
          ShowHTML('        <td align="center">'.f($row,'prefixo').'</td>');
          ShowHTML('        <td align="right">'.f($row,'numero_documento').'</td>');
          ShowHTML('        <td align="right">'.f($row,'numero_tramite').'</td>');
          ShowHTML('        <td align="right">'.f($row,'numero_transferencia').'</td>');
          ShowHTML('        <td align="right">'.f($row,'numero_eliminacao').'</td>');
        } else {
          ShowHTML('        <td width="1%">&rarr;<td>'.f($row,'nome').' ('.f($row,'sigla').')</td>');
          ShowHTML('        <td align="center">"</td>');
          ShowHTML('        <td align="right">"</td>');
          ShowHTML('        <td align="right">"</td>');
          ShowHTML('        <td align="right">"</td>');
          ShowHTML('        <td align="right">"</td>');
        }
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'registra_documento'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'autua_processo'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'arquivo_setorial'),'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled   = ' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_nome" value="'.$w_nome.'">');
    ShowHTML('<INPUT type="hidden" name="w_sigla" value="'.$w_sigla.'">');
    if ($O!='I') {
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=3><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    if ($O=='I') {
      SelecaoUnidade('<U>U</U>nidade:','U',null,$w_chave,null,'w_chave',null,null);
    } else {
      ShowHTML('           <td>Unidade:<br><b>'.$w_nome.' ('.$w_sigla.')</b><br><br>');
    } 
    ShowHTML('           </table>');
    ShowHTML('      <tr><td colspan=3><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade pai:','U','Deixe em branco apenas se a unidade for numeradora.',$w_unidade_pai,null,'w_unidade_pai','MOD_PA_PAI','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_unidade_pai\'; document.Form.submit();"');
    ShowHTML('           </table>');
    ShowHTML('      <tr valign="top">');
    // Apenas unidades de nível zero (sem pai) podem ter controle automático de numeração
    if (nvl($w_unidade_pai,'')=='') {
      ShowHTML('<INPUT type="hidden" name="w_registra_documento" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_autua_processo" value="S">');
      ShowHTML('<INPUT type="hidden" name="w_arquivo_setorial" value="S">');
      ShowHTML('           <td><b>Registra documentos</b>?<br><b>Sim</b><br><br>');
      ShowHTML('           <td><b>Autua processos</b>?<br><b>Sim</b><br><br>');
      ShowHTML('           <td><b>Arquivo setorial</b>?<br><b>Sim</b><br><br>');
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td colspan="3"><b>DADOS PARA NUMERAÇÃO AUTOMÁTICA</b></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td title="Informe um número com 5 posições."><b><u>P</u>refixo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_prefixo" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_prefixo.'"></td>');
      ShowHTML('        <td title="Último número utilizado para numeração de documentos, ou zero se não existir nenhum."><b>Número para <u>d</u>ocumentos:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_nr_documento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_nr_documento.'"></td>');
      ShowHTML('        <td title="Último número utilizado para numeração de guias de remessa de documentos e processos, ou zero se não existir nenhum."><b>Número para guias de <u>r</u>emessa:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_nr_tramite" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_nr_tramite.'"></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td></td>');    
      ShowHTML('        <td title="Último número utilizado para guias de transferência para o arquivo setorial/central, ou zero se não existir nenhum."><b>Número para guias de <u>t</u>ransferência:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nr_transferencia" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_nr_transferencia.'"></td>');    
      ShowHTML('        <td title="Último número utilizado para guias de eliminação, ou zero se não existir nenhum."><b>Número para guias de <u>e</u>liminação:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_eliminacao" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_nr_eliminacao.'"></td>');    
    } else {
      MontaRadioSN('<b>Registra documentos</b>?',$w_registra_documento,'w_registra_documento');
      MontaRadioNS('<b>Autua processos</b>?',$w_autua_processo,'w_autua_processo');
      MontaRadioNS('<b>Arquivo setorial</b>?',$w_arquivo_setorial,'w_arquivo_setorial');
    }
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo</b>?',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan="3" align="center"><hr>');
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
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Manter Tabela básica "Natureza do documento"
// -------------------------------------------------------------------------
function NaturezaDoc() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave     = $_REQUEST['w_chave'];
    $w_nome      = $_REQUEST['w_nome'];
    $w_sigla     = $_REQUEST['w_sigla'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_ativo     = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getNaturezaDoc_PA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados chave informada
    $RS = db_getNaturezaDoc_PA::getInstanceOf($dbms,$w_chave,$w_cliente,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave     = f($RS,'chave');
    $w_nome      = f($RS,'nome');
    $w_sigla     = f($RS,'sigla');
    $w_descricao = f($RS,'descricao');
    $w_ativo     = f($RS,'ativo');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','4','60','1','1');
      Validate('w_sigla','Sigla','1','1','2','10','1','1');
      Validate('w_descricao','Descrição','1','1','4','1000','1','1');
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
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Sigla</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      <tr><td><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=5 cols=80 title="Detalhe o tipo de despacho." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
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
// Manter Tabela básica "Tipo de guarda"
// -------------------------------------------------------------------------
function TipoGuarda() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave            = $_REQUEST['w_chave'];
    $w_sigla            = $_REQUEST['w_sigla'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_fase_corrente    = $_REQUEST['w_fase_corrente'];
    $w_fase_intermed    = $_REQUEST['w_fase_intermed'];
    $w_fase_final       = $_REQUEST['w_fase_final'];
    $w_destinacao_final = $_REQUEST['w_destinacao_final'];
    $w_ativo            = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'sigla','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados chave informada
    $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,$w_chave,$w_cliente,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave            = f($RS,'chave');
    $w_sigla            = f($RS,'sigla');
    $w_descricao        = f($RS,'descricao');
    $w_fase_corrente    = f($RS,'fase_corrente');
    $w_fase_intermed    = f($RS,'fase_intermed');
    $w_fase_final       = f($RS,'fase_final');
    $w_destinacao_final = f($RS,'destinacao_final');
    $w_ativo            = f($RS,'ativo');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sigla','Sigla','1','1','1','4','1','1');
      Validate('w_descricao','Descrição','1','1','4','255','1','1');
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
    BodyOpen('onLoad=\'document.Form.w_sigla.focus()\';');
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
    ShowHTML('          <td><b>Sigla</td>');
    ShowHTML('          <td><b>Fase corrente</td>');
    ShowHTML('          <td><b>Fase intermediária</td>');
    ShowHTML('          <td><b>Fase final</td>');
    ShowHTML('          <td><b>Destinação final</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) { 
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_fase_corrente').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_fase_intermed').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_fase_final').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_destinacao_final').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td colspan="2"><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td colspan="2"><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=5 cols=80 title="Detalhe o tipo de despacho." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Fase corrente?</b>',$w_fase_corrente,'w_fase_corrente');
    MontaRadioSN('<b>Fase intermediária?</b>',$w_fase_intermed,'w_fase_intermed');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Fase final?</b>',$w_fase_final,'w_fase_final');
    MontaRadioSN('<b>Destinacao final?</b>',$w_destinacao_final,'w_destinacao_final');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('      </table>');
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
// Rotina da tabela de parâmetros do módulo de prootocolo e arquivo
// -------------------------------------------------------------------------
function Parametro() {
  extract($GLOBALS);
  global $w_Disabled;

  $RS = db_getParametro::getInstanceOf($dbms,$w_cliente,'PA',null);
  foreach($RS as $row){$RS=$row;}
  $w_despacho_arqcentral   = f($RS,'despacho_arqcentral');
  $w_despacho_emprestimo   = f($RS,'despacho_emprestimo');
  $w_despacho_devolucao    = f($RS,'despacho_devolucao');
  $w_despacho_autuar       = f($RS,'despacho_autuar');
  $w_despacho_arqsetorial  = f($RS,'despacho_arqsetorial');
  $w_despacho_anexar       = f($RS,'despacho_anexar');
  $w_despacho_apensar      = f($RS,'despacho_apensar');
  $w_despacho_eliminar     = f($RS,'despacho_eliminar');
  $w_arquivo_central       = f($RS,'arquivo_central');
  $w_limite_interessados   = f($RS,'limite_interessados');  
  $w_ano_corrente          = f($RS,'ano_corrente');
  Cabecalho();
  ShowHTML('<HEAD>');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_despacho_arqsetorial','Despacho para arquivo setorial','SELECT','1','1','18','','1');
  Validate('w_despacho_arqcentral','Despacho para arquivo central','SELECT','1','1','18','','1');
  Validate('w_despacho_emprestimo','Despacho para emprestimo','SELECT','1','1','18','','1');
  Validate('w_despacho_devolucao','Despacho para devolução','SELECT','1','1','18','','1');
  Validate('w_despacho_eliminar','Despacho para eliminar','SELECT','1','1','18','','1');
  Validate('w_despacho_autuar','Despacho para autuar','SELECT','1','1','18','','1');
  Validate('w_despacho_anexar','Despacho para anexar','SELECT','1','1','18','','1');
  Validate('w_despacho_apensar','Despacho para apensar','SELECT','1','1','18','','1');
  Validate('w_arquivo_central','Unidade do arquivo central','SELECT','1','1','18','','1');
  Validate('w_limite_interessados','Limite de interessados','1','1','1','18','','1');
  Validate('w_ano_corrente','Ano corrente','1','1','4','4','','1');
  ShowHTML('  if ((theForm.w_despacho_arqcentral.value==theForm.w_despacho_emprestimo.value)||(theForm.w_despacho_arqcentral.value==theForm.w_despacho_devolucao.value)||(theForm.w_despacho_devolucao.value==theForm.w_despacho_emprestimo.value)) {');
  ShowHTML('    alert(\'Nenhum dos despachos podem ser iguais!\');');
  ShowHTML('    theForm.w_despacho_arqcentral.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  Validate('w_assinatura','Assinatura eletrônica','1','1','6','15','1','1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  BodyOpen('onLoad=\'document.Form.w_despacho_arqsetorial.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.str_replace('Listagem','Alteração',$w_TP).'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="97%" border="0">');
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para arqu<U>i</U>vo setorial:','I',null,$w_cliente,$w_despacho_arqsetorial,null,'w_despacho_arqsetorial',null,null);
  SelecaoTipoDespacho('Despacho para ar<U>q</U>uivo central:','A',null,$w_cliente,$w_despacho_arqcentral,null,'w_despacho_arqcentral',null,null);
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para <U>e</U>mpréstimo:','E',null,$w_cliente,$w_despacho_emprestimo,null,'w_despacho_emprestimo',null,null);
  SelecaoTipoDespacho('Despacho para <U>d</U>evolução:','D',null,$w_cliente,$w_despacho_devolucao,null,'w_despacho_devolucao',null,null);
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para elimi<U>n</U>ar:','N',null,$w_cliente,$w_despacho_eliminar,null,'w_despacho_eliminar',null,null);
  SelecaoTipoDespacho('Despacho para a<U>u</U>tuar:','U',null,$w_cliente,$w_despacho_autuar,null,'w_despacho_autuar',null,null);
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para ane<U>x</U>ar:','X',null,$w_cliente,$w_despacho_anexar,null,'w_despacho_anexar',null,null);
  SelecaoTipoDespacho('Despacho para apen<U>s</U>ar:','S',null,$w_cliente,$w_despacho_apensar,null,'w_despacho_apensar',null,null);
  ShowHTML('      <tr valign="top"><td colspan="2"><table width="97%" border="0">');
  SelecaoUnidade('<U>A</U>rquivo central:','A',null,$w_arquivo_central,null,'w_arquivo_central','MOD_PA',null);
  ShowHTML('          </table>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td><b><U>L</U>imite de interessados:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="w_limite_interessados" size="4" maxlength="18" value="'.$w_limite_interessados.'"></td>');
  ShowHTML('        <td><b>Ano <U>c</U>orrente:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_ano_corrente" size="4" maxlength="18" value="'.$w_ano_corrente.'"></td>');
  ShowHTML('      <tr valign="top"><td colspan="2"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
  ShowHTML('      <tr><td align="center" colspan="2"><hr>');
  ShowHTML('      <tr><td align="center" colspan="2"><input class="stb" type="submit" name="Botao" value="Gravar"></td></tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
//  Rotina da tabela de assuntos
// -------------------------------------------------------------------------
function Assunto() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_copia      = $_REQUEST['w_copia'];
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave_pai            = $_REQUEST['w_chave_pai'];
    $w_codigo               = $_REQUEST['w_codigo'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_detalhamento         = $_REQUEST['w_detalhamento'];
    $w_observacao           = $_REQUEST['w_observacao'];
    $w_corrente_guarda      = $_REQUEST['w_corrente_guarda'];
    $w_corrente_anos        = $_REQUEST['w_corrente_anos'];
    $w_intermed_guarda      = $_REQUEST['w_intermed_guarda'];
    $w_intermed_anos        = $_REQUEST['w_intermed_anos'];
    $w_final_guarda         = $_REQUEST['w_final_guarda'];
    $w_final_anos           = $_REQUEST['w_final_anos'];
    $w_destinacao_final     = $_REQUEST['w_destinacao_final'];
    $w_ativo                = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null,'ISNULL');
    $RS = SortArray($RS,'codigo','asc','descricao','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados de um assunto
    $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_chave_pai            = f($RS,'sq_assunto_pai');
    $w_codigo               = f($RS,'codigo');
    $w_descricao            = f($RS,'descricao');
    $w_detalhamento         = f($RS,'detalhamento');
    $w_observacao           = f($RS,'observacao');
    $w_corrente_guarda      = f($RS,'fase_corrente_guarda');
    $w_corrente_anos        = f($RS,'fase_corrente_anos');
    $w_intermed_guarda      = f($RS,'fase_intermed_guarda');
    $w_intermed_anos        = f($RS,'fase_intermed_anos');
    $w_final_guarda         = f($RS,'fase_final_guarda');
    $w_final_anos           = f($RS,'fase_final_anos');
    $w_destinacao_final     = f($RS,'destinacao_final');
    $w_ativo                = f($RS,'ativo');
  }
  cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IAC',$O)===false)) {
      Validate('w_chave_pai','Assunto pai','SELECT','','1','18','','1');
      Validate('w_codigo','Código','1','1','1','10','1','1');
      Validate('w_descricao','Descricao','1','1','3','255','1','1');
      Validate('w_detalhamento','Detalhamento','1','','4','2000 ','1','1');
      Validate('w_observacao','Observação','1','','4','2000 ','1','1');
      Validate('w_corrente_guarda','Guarda na fase corrente','SELECT','1','1','18','','1');
      Validate('w_corrente_anos','Nº de anos na fase corrente','1','','1','18','','1');
      Validate('w_intermed_guarda','Guarda na fase intemediária','SELECT','1','1','18','','1');
      Validate('w_intermed_anos','Nº de anos na fase intemediária','1','','1','18','','1');
      Validate('w_final_guarda','Guarda na fase final','SELECT','1','1','18','','1');
      Validate('w_final_anos','Nº de anos na fase final','1','','1','18','','1');
      Validate('w_destinacao_final','Destinação final do documento','SELECT','1','1','18','','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='C' || $O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_chave_pai.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=this.focus();');
  } else {
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><table width="99%" border="0">');
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Código</td>');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Corrente</td>');
    ShowHTML('          <td><b>Intermediária</td>');
    ShowHTML('          <td><b>Final</td>');
    ShowHTML('          <td><b>Destinação final</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHtml(AssuntoLinha(f($row,'chave'),f($row,'codigo'),f($row,'descricao'),f($row,'ds_corrente_guarda'),f($row,'sg_corrente_guarda'),f($row,'fase_corrente_anos'),f($row,'ds_intermed_guarda'),f($row,'sg_intermed_guarda'),f($row,'fase_intermed_anos'),f($row,'ds_final_guarda'),f($row,'sg_final_guarda'),f($row,'fase_final_anos'),f($row,'ds_destinacao_final'),f($row,'sg_destinacao_final'),f($row,'nm_ativo'),'S',$w_cor));
        // Recupera os assuntos vinculados ao nível acima
        $RS1 = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,null,f($row,'chave'),null,null,null,null,null,null,null,'REGISTROS');
        $RS1 = SortArray($RS1,'codigo','asc','descricao','asc');
        foreach($RS1 as $row1) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHtml(AssuntoLinha(f($row1,'chave'),f($row1,'codigo'),f($row1,'descricao'),f($row1,'ds_corrente_guarda'),f($row1,'sg_corrente_guarda'),f($row1,'fase_corrente_anos'),f($row1,'ds_intermed_guarda'),f($row1,'sg_intermed_guarda'),f($row1,'fase_intermed_anos'),f($row1,'ds_final_guarda'),f($row1,'sg_final_guarda'),f($row1,'fase_final_anos'),f($row1,'ds_destinacao_final'),f($row1,'sg_destinacao_final'),f($row1,'nm_ativo'),'S',$w_cor));
          // Recupera os assuntos vinculados ao nível acima
          $RS2 = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,null,f($row1,'chave'),null,null,null,null,null,null,null,'REGISTROS');
          $RS2 = SortArray($RS2,'codigo','asc','descricao','asc');
          foreach($RS2 as $row2) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHtml(AssuntoLinha(f($row2,'chave'),f($row2,'codigo'),f($row2,'descricao'),f($row2,'ds_corrente_guarda'),f($row2,'sg_corrente_guarda'),f($row2,'fase_corrente_anos'),f($row2,'ds_intermed_guarda'),f($row2,'sg_intermed_guarda'),f($row2,'fase_intermed_anos'),f($row2,'ds_final_guarda'),f($row2,'sg_final_guarda'),f($row2,'fase_final_anos'),f($row2,'ds_destinacao_final'),f($row2,'sg_destinacao_final'),f($row2,'nm_ativo'),'S',$w_cor));
            // Recupera as etapas vinculadas ao nível acima
            $RS3 = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,null,f($row2,'chave'),null,null,null,null,null,null,null,'REGISTROS');
            $RS3 = SortArray($RS3,'codigo','asc','descricao','asc');
            foreach($RS3 as $row3) {
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              ShowHtml(AssuntoLinha(f($row3,'chave'),f($row3,'codigo'),f($row3,'descricao'),f($row3,'ds_corrente_guarda'),f($row3,'sg_corrente_guarda'),f($row3,'fase_corrente_anos'),f($row3,'ds_intermed_guarda'),f($row3,'sg_intermed_guarda'),f($row3,'fase_intermed_anos'),f($row3,'ds_final_guarda'),f($row3,'sg_final_guarda'),f($row3,'fase_final_anos'),f($row3,'ds_destinacao_final'),f($row3,'sg_destinacao_final'),f($row3,'nm_ativo'),'S',$w_cor));
              // Recupera os assuntos vinculados ao nível acima
              $RS4 = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,null,f($row3,'chave'),null,null,null,null,null,null,null,'REGISTROS');
              $RS4 = SortArray($RS4,'codigo','asc','descricao','asc');
              foreach($RS4 as $row4) {
                $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
                ShowHtml(AssuntoLinha(f($row4,'chave'),f($row4,'codigo'),f($row4,'descricao'),f($row4,'ds_corrente_guarda'),f($row4,'sg_corrente_guarda'),f($row4,'fase_corrente_anos'),f($row4,'ds_intermed_guarda'),f($row4,'sg_intermed_guarda'),f($row4,'fase_intermed_anos'),f($row4,'ds_final_guarda'),f($row4,'sg_final_guarda'),f($row4,'fase_final_anos'),f($row4,'ds_destinacao_final'),f($row4,'sg_destinacao_final'),f($row4,'nm_ativo'),'S',$w_cor));
              } 
            }    
          } 
        } 
      } 
    } 
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    ShowHTML('<tr><td><table width="99%" border="0" bgcolor="'.$conTrBgColor.'">');
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('  <tr valign="top">');
    ShowHTML('      <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0><tr><td>');
    SelecaoAssunto('Assun<u>t</u>o pai:','T',null,$w_chave_pai,null,'w_chave_pai',null,'SUBGRUPO',null);
    ShowHTML('      </table><tr><td>');
    ShowHTML('      <tr><td colspan="2"><b><u>C</u>ódigo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_codigo.'" title="Informe o código do assunto."></td>');
    ShowHTML('      <tr><td colspan="2"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva o assunto.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><b>D<u>e</u>talhamento:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_detalhamento" class="STI" ROWS=5 cols=75 title="Detalhe o assunto.">'.$w_detalhamento.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><b><u>O</u>bservacao:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75 >'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr>');
    SelecaoTipoGuarda('<u>G</u>uarda na fase corrente:','G',null,$w_corrente_guarda,null,'w_corrente_guarda','CORRENTE',null);
    ShowHTML('          <td><b>Nº de anos:</b><br><input '.$w_Disabled.' type="text" name="w_corrente_anos" class="STI" SIZE="10" MAXLENGTH="18" VALUE="'.Nvl($w_corrente_anos,0).'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoTipoGuarda('G<u>u</u>arda na fase intermediária:','U',null,$w_intermed_guarda,null,'w_intermed_guarda','INTERMED',null);
    ShowHTML('          <td><b>Nº de anos:</b><br><input '.$w_Disabled.' type="text" name="w_intermed_anos" class="STI" SIZE="10" MAXLENGTH="18" VALUE="'.Nvl($w_intermed_anos,0).'"></td>');
    ShowHTML('      </tr>');    
    ShowHTML('      <tr>');
    SelecaoTipoGuarda('Gua<u>r</u>da na fase final:','R',null,$w_final_guarda,null,'w_final_guarda','FINAL',null);
    ShowHTML('          <td><b>Nº de anos:</b><br><input '.$w_Disabled.' type="text" name="w_final_anos" class="STI" SIZE="10" MAXLENGTH="18" VALUE="'.Nvl($w_final_anos,0).'"></td>');
    ShowHTML('      </tr>');    
    ShowHTML('      <tr>');
    SelecaoTipoGuarda('De<u>s</u>tinação final do documento:','S',null,$w_destinacao_final,null,'w_destinacao_final','DESTINACAO',null);
    ShowHTML('      </tr>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');    
    ShowHTML('      <tr><td><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3"><input class="stb" type="submit" name="Botao" value="Gravar">&nbsp;');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
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
// Gera uma linha de apresentação da tabela de assuntos
// -------------------------------------------------------------------------
function AssuntoLinha($l_chave,$l_codigo,$l_descricao,$l_ds_corrente,$l_sg_corrente,$l_corrente_ano,$l_ds_intermed,$l_sg_intermed,$l_intermed_ano,$l_ds_final,$l_sg_final,$l_final_ano,$l_ds_destinacao,$l_sg_destinacao,$l_ativo,$l_oper,$l_cor) {
  extract($GLOBALS);
  $l_html=$l_html.chr(13).'       <tr bgcolor="'.$l_cor.'" valign="top">';
  $l_html=$l_html.chr(13).'        <td>'.$l_codigo.'</b>';
  $l_html=$l_html.chr(13).'        <td>'.$l_descricao.'</b>';
  $l_html=$l_html.chr(13).'        <td nowrap align="center" title="'.$l_ds_corrente.'">'.(($l_sg_corrente=='ANOS')? $l_corrente_ano.' '.$l_sg_corrente:$l_sg_corrente).'</td>';  
  $l_html=$l_html.chr(13).'        <td nowrap align="center" title="'.$l_ds_intermed.'">'.(($l_sg_intermed=='ANOS')? $l_intermed_ano.' '.$l_sg_intermed:$l_sg_intermed).'</td>';
  $l_html=$l_html.chr(13).'        <td nowrap align="center" title="'.$l_ds_final.'">'.(($l_sg_final=='ANOS')   ? $l_final_ano.' '.$l_sg_final:$l_sg_final).'</td>';  
  $l_html=$l_html.chr(13).'        <td nowrap align="center" title="'.$l_ds_destinacao.'">'.$l_sg_destinacao.'</b>';
  $l_html=$l_html.chr(13).'        <td align="center">'.$l_ativo.'</b>';
  if ($l_oper == 'S') {
    $l_html=$l_html.chr(13).'        <td nowrap>';
    $l_html=$l_html.chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp';
    $l_html=$l_html.chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp';
    $l_html=$l_html.chr(13).'        </td>';
  } 
  $l_html=$l_html.chr(13).'      </tr>';
  return $l_html;
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
    case 'PATPDESPAC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,Nvl($_REQUEST['w_nome'],''),null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo de despacho com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 
          // Testa a existência do sigla
          $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,null,Nvl($_REQUEST['w_sigla'],''),null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo despacho com esta sigla!\');');
            ScriptClose(); 
            retornaFormulario('w_sigla');
            break;
          } 
        } elseif ($O=='E') {
          $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,null,null,null,'VINCULADO');
          if (nvl(f($RS,'existe'),0)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Não é possível excluir esta tipo de despacho. Ele está indicado de parâmetro!\');');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          } 
        } 
        dml_putTipoDespacho_PA::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente,$_REQUEST['w_nome'],$_REQUEST['w_sigla'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }     
      break;
    case 'PAESPECIE':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,Nvl($_REQUEST['w_nome'],''),null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe espécie de documento com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 

          // Testa a existência do sigla
          $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,null,Nvl($_REQUEST['w_sigla'],''),null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe espécie de documento com esta sigla!\');');
            ScriptClose(); 
            retornaFormulario('w_sigla');
            break;
          } 
        } elseif ($O=='E') {
          $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,null,null,null,'VINCULADO');
          if (nvl(f($RS,'existe'),0)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Não é possível excluir esta espécie de documento. Ele está ligado a algum documento!\');');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          } 
        } 
        dml_putEspecieDocumento_PA::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente,$_REQUEST['w_nome'],$_REQUEST['w_sigla'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PAUNIDADE':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if ($O=='I') {
            $RS = db_getUnidade_PA::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],null,null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Unidade já cadastrada!\');');
              ScriptClose();
              RetornaFormulario('w_chave');
              exit();
            }
          }
          if (nvl($_REQUEST['w_unidade_pai'],'')=='') {
            $RS = db_getUnidade_PA::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],null,$_REQUEST['w_prefixo']);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Não é possivel definir o mesmo prefixo para duas unidades!\');');
              ScriptClose();
              RetornaFormulario('w_prefixo');
              exit();
            }
          }
        }
        dml_putUnidade_PA::getInstanceOf($dbms,$O,$w_cliente,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_unidade_pai'],$_REQUEST['w_registra_documento'],
            $_REQUEST['w_autua_processo'],$_REQUEST['w_prefixo'],$_REQUEST['w_nr_documento'],$_REQUEST['w_nr_tramite'],$_REQUEST['w_nr_transferencia'],
            $_REQUEST['w_nr_eliminacao'],$_REQUEST['w_arquivo_setorial'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PANATUREZA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getNaturezaDoc_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,Nvl($_REQUEST['w_nome'],''),null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe natureza de documento com este nome!\');');
            ScriptClose(); 
            retornaFormulario('w_nome');
            break;
          } 

          // Testa a existência do sigla
          $RS = db_getNaturezaDoc_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,null,Nvl($_REQUEST['w_sigla'],''),null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe natureza de documento com esta sigla!\');');
            ScriptClose(); 
            retornaFormulario('w_sigla');
            break;
          } 
        } elseif ($O=='E') {
          $RS = db_getNaturezaDoc_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,null,null,null,'VINCULADO');
          if (nvl(f($RS,'existe'),0)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Não é possível excluir esta natureza de documento. Ela está ligada a algum documento!\');');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          } 
        } 
        dml_putNaturezaDoc_PA::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente,$_REQUEST['w_nome'],$_REQUEST['w_sigla'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PATPGUARDA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          // Testa a existência da desrcricao
          $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,null,Nvl($_REQUEST['w_descricao'],''),null,null,null,null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo de guarda com esta desrição!\');');
            ScriptClose(); 
            retornaFormulario('w_descricao');
            break;
          } 

          // Testa a existência do sigla
          $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,Nvl($_REQUEST['w_sigla'],''),null,null,null,null,null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo de guarda com esta sigla!\');');
            ScriptClose(); 
            retornaFormulario('w_sigla');
            break;
          } 
        } elseif ($O=='E') {
          $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_chave'],''),$w_cliente,null,null,null,null,null,null,null,'VINCULADO');
          if (nvl(f($RS,'existe'),0)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Não é possível excluir este tipo de guarda. Ele está ligado a algum assunto!\');');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          } 
        } 
        dml_putTipoGuarda_PA::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente,$_REQUEST['w_sigla'],
                $_REQUEST['w_descricao'],$_REQUEST['w_fase_corrente'],$_REQUEST['w_fase_intermed'],
                $_REQUEST['w_fase_final'],$_REQUEST['w_destinacao_final'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PAPARAM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putPAParametro::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_despacho_arqcentral'],$_REQUEST['w_despacho_emprestimo'],$_REQUEST['w_despacho_devolucao'],
            $_REQUEST['w_despacho_autuar'],$_REQUEST['w_despacho_arqsetorial'],$_REQUEST['w_despacho_anexar'],$_REQUEST['w_despacho_apensar'],$_REQUEST['w_despacho_eliminar'],
            $_REQUEST['w_arquivo_central'],$_REQUEST['w_limite_interessados'],$_REQUEST['w_ano_corrente']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'PAASSUNTO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='E') {
          $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,null,null,null,null,null,null,null,'VINCULADO');
          if (nvl(f($RS,'existe'),0)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Não é possível excluir este assunto. Ele está ligado a algum documento!\');');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          } 
        }
        $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_corrente_guarda'],''),$w_cliente,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if(f($RS,'sigla')=='ANOS') $w_corrente_anos = $_REQUEST['w_corrente_anos'];
        else                       $w_corrente_anos = 0;
        $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_intermed_guarda'],''),$w_cliente,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if(f($RS,'sigla')=='ANOS') $w_intermed_anos = $_REQUEST['w_intermed_anos'];
        else                       $w_intermed_anos = 0;        
        $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,Nvl($_REQUEST['w_final_guarda'],''),$w_cliente,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if(f($RS,'sigla')=='ANOS') $w_final_anos = $_REQUEST['w_final_anos'];
        else                       $w_final_anos = 0;
        dml_putAssunto_PA::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente,$_REQUEST['w_chave_pai'],$_REQUEST['w_codigo'],
                $_REQUEST['w_descricao'],$_REQUEST['w_detalhamento'],$_REQUEST['w_observacao'],$_REQUEST['w_corrente_guarda'],
                $w_corrente_anos,$_REQUEST['w_intermed_guarda'],$w_intermed_anos,$_REQUEST['w_final_guarda'],
                $w_final_anos,$_REQUEST['w_destinacao_final'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;      
    default:
      exibevariaveis();
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
      ScriptClose();
      break;
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'TIPODESPACHO':       TipoDespacho();      break;
    case 'ESPECIEDOCUMENTO':   EspecieDocumento();  break;
    case 'UNIDADE':            Unidade();           break;    
    case 'NATUREZADOC':        NaturezaDoc();       break;
    case 'TIPOGUARDA':         TipoGuarda();        break;
    case 'PARAMETRO':          Parametro();         break;
    case 'ASSUNTO':            Assunto();           break;
    case 'GRAVA':              Grava();             break;
    default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exibevariaveis();
  break;
  } 
} 
?>