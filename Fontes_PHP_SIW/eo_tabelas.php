<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getMenuCode.php');
include_once('classes/sp/db_getMenuData.php');
include_once('classes/sp/db_getEoAAtuac.php');
include_once('classes/sp/db_getEoAAtuacData.php');
include_once('classes/sp/db_getUnitTypeList.php');
include_once('classes/sp/db_getUnitTypeData.php');
include_once('classes/sp/db_getTipoPostoList.php');
include_once('classes/sp/db_verificaAssinatura.php');
include_once('classes/sp/dml_putEoAAtuac.php');
include_once('classes/sp/dml_putEoTipoUni.php');
include_once('classes/sp/dml_putEoTipoPosto.php');

// =========================================================================
//  /EO_Tabelas.asp
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia a atualização das tabelas do sistema
// Mail     : alex@sbpi.com.br
// Criacao  : 24/03/2003 16:55
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
$w_pagina       = 'eo_tabelas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir_volta    = '';

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão'; break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
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
// Rotina da tabela de áreas de atuação
// -------------------------------------------------------------------------
function AreaAtuacao(){
  extract($GLOBALS);
  global $w_Disabled;
  $p_nome   = strtoupper($_REQUEST['p_nome']);
  $p_ativo  = strtoupper($_REQUEST['p_ativo']);
  $p_ordena = strtoupper($_REQUEST['p_ordena']);
  $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS,'libera_edicao');
  if ($O=='L') {
    $RS = db_getEOAAtuac::getInstanceOf($dbms,$w_cliente,$p_nome,$p_ativo);
    array_key_case_change(&$RS);
    if ($p_ordena>'') { 
      $RS = SortArray($RS,$p_ordena,'asc');
    } else {
      $RS = SortArray($RS,'nome','asc');
    }
  } elseif ($O=='A' || $O=='E') {
    $w_sq_area_atuacao=$_REQUEST['w_sq_area_atuacao'];
    $RS = db_getEOAAtuacData::getInstanceOf($dbms,$w_sq_area_atuacao,null,null);
    $w_nome  = f($RS,'nome');
    $w_ativo = f($RS,'ativo');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  if (!(strpos('IAEP',$O)===false)) {
    if (!(strpos('IA',$O)===false)){
      Validate('w_nome','Nome','1','1','3','25','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','3','25','1','1');
    } 
  } 
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
    } 
  } elseif (!(strpos('P',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=document.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<font size="2"><a accesskey="I" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'"><u>I</u>ncluir</a>&nbsp;');
    } 
    if ($p_nome.$p_ativo.$p_ordena>'') {
      ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a></font>');
    } else {
      ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Chave</font></td>');
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    }  
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font  size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'sq_area_atuacao').'</td>');
        ShowHTML('        <td align="left"><font size="1">'.f($row,'nome').'</td>');
        if (f($row,'ativo')=='S') {
          ShowHTML('        <td align="center"><font size="1">Sim</td>');
        } else {
          ShowHTML('        <td align="center"><font size="1">Não</td>');
        } 
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td align="top" nowrap><font size="1">');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_area_atuacao='.f($row,'sq_area_atuacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Alterar</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_area_atuacao='.f($row,'sq_area_atuacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Excluir</A>&nbsp');
          ShowHTML('        </td>');
        } 
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
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="p_ordena" value="'.$p_ordena.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_atuacao" value="'.$w_sq_area_atuacao.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="25" maxlength="25" value="'.$w_nome.'"></td>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo:</b>',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('P',$O)===false)) {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_nome" size="25" maxlength="25" value="'.$p_nome.'"></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b>Ativo:</b><br>');
    if ($p_Ativo=='') {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"> Não <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="" checked> Todos');
    } elseif ($p_Ativo=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"> Não <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""> Todos');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N" checked> Não <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""> Todos');
    } 
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if ($p_ordena=='NOME') {
      ShowHTML('          <option value="">Nome<option value="ativo">Ativo');
    } elseif ($p_ordena=='ATIVO') {
      ShowHTML('          <option value="">Nome<option value="ativo" SELECTED>Ativo');
    } else {
      ShowHTML('          <option value="" SELECTED>Nome<option value="ativo">Ativo');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
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
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina da tabela de tipos de unidade organizacional
// -------------------------------------------------------------------------
function TipoUnidade() {
  extract($GLOBALS);
  global $w_Disabled;
  $p_nome   = strtoupper($_REQUEST['p_nome']);
  $p_ativo  = strtoupper($_REQUEST['p_ativo']);
  $p_ordena = strtoupper($_REQUEST['p_ordena']);
 
  $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
  $w_libera_edicao=f($RS,'libera_edicao');

  if ($O=='L') {
    $RS = db_getUnitTypeList::getInstanceOf($dbms,$w_cliente,$p_nome,$p_ativo);
    array_key_case_change(&$RS);
    if ($p_ordena>'') { 
      $RS = SortArray($RS,$p_ordena,'asc');
    } else {
      $RS = SortArray($RS,'nome','asc');
    }
  } elseif ($O=='A' || $O=='E') {
    $w_sq_tipo_unidade=$_REQUEST['w_sq_tipo_unidade'];
    $RS = db_getUnitTypeData::getInstanceOf($dbms,$w_sq_tipo_unidade);
    $w_nome  = f($RS,'nome');
    $w_ativo = f($RS,'ativo');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','3','25','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','3','25','1','1');
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
  } elseif (!(strpos('P',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=document.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<font size="2"><a accesskey="I" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'"><u>I</u>ncluir</a>&nbsp;');
    } 
    if ($p_nome.$p_ativo.$p_ordena>'') {
      ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a></font>');
    } else {
      ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Chave</font></td>');
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    } 
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font  size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'sq_tipo_unidade').'</td>');
        ShowHTML('        <td align="left"><font size="1">'.f($row,'nome').'</td>');
        if (f($row,'ativo')=='S') {
          ShowHTML('        <td align="center"><font size="1">Sim</td>');
        } else   {
          ShowHTML('        <td align="center"><font size="1">Não</td>');
        } 
        if ($w_libera_edicao=='S')   {
          ShowHTML('        <td align="top" nowrap><font size="1">');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_unidade='.f($row,'sq_tipo_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Alterar</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_unidade='.f($row,'sq_tipo_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Excluir</A>&nbsp');
          ShowHTML('        </td>');
        } 
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
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="p_ordena" value="'.$p_ordena.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_unidade" value="'.$w_sq_tipo_unidade.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="25" maxlength="25" value="'.$w_nome.'"></td>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo:</b>',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('P',$O)===false)) {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_nome" size="25" maxlength="25" value="'.$p_nome.'"></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b>Ativo:</b><br>');
    if ($p_Ativo=='') {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"> Não <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="" checked> Todos');
    } elseif ($p_Ativo=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S" checked> Sim <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"> Não <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""> Todos');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"> Sim <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N" checked> Não <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""> Todos');
    } 
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if ($p_ordena=='NOME') {
      ShowHTML('          <option value="">Código<option value="nome" SELECTED>Nome<option value="ativo">Ativo');
    } elseif ($p_ordena=='ATIVO') {
      ShowHTML('          <option value="">Código<option value="nome">Nome<option value="ativo" SELECTED>Ativo');
    } else {
      ShowHTML('          <option value="" SELECTED>Código<option value="nome">Nome<option value="ativo">Ativo');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
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
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape(); 
} 

// =========================================================================
// Rotina da tabela de tipos de posto
// -------------------------------------------------------------------------
function TipoPosto() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave=$_REQUEST['w_chave'];
  if ($O=='L') {
    $RS = db_getTipoPostoList::getInstanceOf($dbms,$w_cliente,null,null);
  } elseif ($O=='A' || $O=='E') {
    $RS = db_getTipoPostoList::getInstanceOf($dbms,$w_cliente,$w_chave,null);
    foreach ($RS as $row) {
      $w_nome      = f($row,'nome');
      $w_sigla     = f($row,'sigla');
      $w_descricao = f($row,'descricao');
      $w_padrao    = f($row,'padrao');
      $w_ativo     = f($row,'ativo');
    }
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','3','30','1','1');
      Validate('w_sigla','Sigla','1','1','2','5','1','1');
      Validate('w_descricao','Descricao','1','1','3','200','1','1');
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
    BodyOpen('onLoad=document.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Sigla</font></td>');
    ShowHTML('          <td><font size="1"><b>Ativo</font></td>');
    ShowHTML('          <td><font size="1"><b>Padrao</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font  size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="left"><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="left"><font size="1">'.f($row,'sigla').'</td>');
        if (f($row,'ativo')=='S') { 
          ShowHTML('        <td align="center"><font size="1">Sim</td>');
        } else {
          ShowHTML('        <td align="center"><font size="1">Não</td>');
        } 
        if (f($row,'padrao')=='S') {
          ShowHTML('        <td align="center"><font size="1">Sim</td>');
        } else {
          ShowHTML('        <td align="center"><font size="1">Não</td>');
        } 
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_eo_tipo_posto').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_eo_tipo_posto').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
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
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b><U>S</U>igla:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_sigla" size="5" maxlength="5" value="'.$w_sigla.'"></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><font size="1"><b><U>D</U>escrição:<br>');
    ShowHTML('             <textarea ACCESSKEY="D" '.$w_Disabled.' name="w_descricao" class="sti" rows=3 cols=55>'.$w_descricao.'</textarea>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo:</b>',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Padrao:</b>',$w_padrao,'w_padrao');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
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
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=document.focus();');
  switch ($SG) {
    case 'EOTPUNID':
      $p_nome   = strtoupper($_REQUEST['p_nome']);
      $p_ativo  = strtoupper($_REQUEST['p_ativo']);
      $p_ordena = strtoupper($_REQUEST['p_ordena']);
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putEOTipoUni::getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_tipo_unidade'],$w_cliente,$_REQUEST['w_nome'],
            $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'EOAREAATU':
      $p_nome   = strtoupper($_REQUEST['p_nome']);
      $p_ativo  = strtoupper($_REQUEST['p_ativo']);
      $p_ordena = strtoupper($_REQUEST['p_ordena']);
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putEOAAtuac::getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_area_atuacao'],$w_cliente,$_REQUEST['w_nome'],
            $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'EOTPPOSTO':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putEOTipoPosto::getInstanceOf($dbms, $O,
            $_REQUEST['w_chave'],$w_cliente,$_REQUEST['w_nome'],$_REQUEST['w_sigla'],
            $_REQUEST['w_descricao'],$_REQUEST['w_ativo'],$_REQUEST['w_padrao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
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
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'TPUNIDADE': TipoUnidade();  break;
  case 'AREA':      AreaAtuacao();  break;
  case 'TPPOSTO':   TipoPosto();    break;
  case 'GRAVA':     Grava();        break;
  default:
    Cabecalho();
    BodyOpen('onLoad=document.focus();');
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


