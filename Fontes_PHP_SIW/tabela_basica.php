<?php
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getMenuData.php');
include_once('classes/sp/db_getMenuCode.php');
include_once('classes/sp/db_getAdressTypeList.php');
include_once('classes/sp/db_getAdressTypeData.php');
include_once('classes/sp/db_getFoneTypeList.php');
include_once('classes/sp/db_getFoneTypeData.php');
include_once('classes/sp/db_getUserTypeList.php');
include_once('classes/sp/db_getUserTypeData.php');
include_once('classes/sp/db_getDeficiencyList.php');
include_once('classes/sp/db_getDeficiencyData.php');
include_once('classes/sp/db_getDeficGroupList.php');
include_once('classes/sp/db_getDeficiencyGroupData.php');
include_once('classes/sp/db_getIdiomList.php');
include_once('classes/sp/db_getIdiomData.php');
include_once('classes/sp/db_getEtniaList.php');
include_once('classes/sp/db_getEtniaData.php');
include_once('classes/sp/db_getFormationList.php');
include_once('classes/sp/db_getFormationData.php');
include_once('classes/sp/db_verificaAssinatura.php');
include_once('classes/sp/dml_CoTpEnder.php');
include_once('classes/sp/dml_CoTpFone.php');
include_once('classes/sp/dml_CoTpPessoa.php');
include_once('classes/sp/dml_CoTpDef.php');
include_once('classes/sp/dml_CoGrDef.php');
include_once('classes/sp/dml_CoIdioma.php');
include_once('classes/sp/dml_CoEtnia.php');
include_once('classes/sp/dml_CoForm.php');
include_once('funcoes/selecaoTipoPessoa.php');
include_once('funcoes/selecaoGrupoDef.php');

// =========================================================================
//  /Tabela_Basica.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia a atualiza��o das tabelas de localiza��o
// Mail     : alex@sbpi.com.br
// Criacao  : 19/03/2003, 16:35
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = C   : Cancelamento
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'tabela_basica.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir_volta    = '';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o'; break;
  case 'A': $w_TP=$TP.' - Altera��o'; break;
  case 'E': $w_TP=$TP.' - Exclus�o'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

if(nvl($w_menu,'')!=''){
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS_Menu,'libera_edicao');
  
  if ($w_libera_edicao=='N' && strpos('LP',$O)===false) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b>Opera��o n�o permitida!</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exit();
  }
}

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina da tabela de tipo de endereco
// -------------------------------------------------------------------------
function TipoEndereco() {
  extract($GLOBALS);
  global $w_Disabled;

  $p_nome               = trim(upper($_REQUEST['p_nome']));
  $p_ativo              = trim($_REQUEST['p_ativo']);
  $w_sq_tipo_endereco   = $_REQUEST['w_sq_tipo_endereco'];
  $p_ordena             = $_REQUEST['p_ordena'];

  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS,'libera_edicao');

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da p�gina
    $w_nome                 = $_REQUEST['w_nome'];
    $w_sq_tipo_pessoa       = $_REQUEST['w_sq_tipo_pessoa'];
    $w_email                = $_REQUEST['w_email'];
    $w_internet             = $_REQUEST['w_internet'];
    $w_ativo                = $_REQUEST['w_ativo'];
    $w_padrao               = $_REQUEST['w_padrao'];
    $p_ordena               = $_REQUEST['p_ordena'];
    
  } elseif (!(strpos('LP',$O)===false)) {
    $SQL = new db_getAdressTypeList; $RS = $SQL->getInstanceOf($dbms,null,$p_nome,$p_ativo);
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'sq_tipo_pessoa','asc','padrao','desc','nome','asc');    
    } else {
      $RS = SortArray($RS,'sq_tipo_pessoa','asc','padrao','desc','nome','asc');
    }
  } elseif (($O=='A' || $O=='E')) {
    $SQL = new db_getAdressTypeData; $RS = $SQL->getInstanceOf($dbms,$w_sq_tipo_endereco);
    $w_nome             = f($RS,'nome');
    $w_sq_tipo_pessoa   = f($RS,'sq_tipo_pessoa');
    $w_email            = f($RS,'email');
    $w_internet         = f($RS,'internet');
    $w_ativo            = f($RS,'ativo');
    $w_padrao           = f($RS,'padrao');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_tipo_pessoa','Aplica��o','SELECT','1','1','18','','1');
      Validate('w_nome','Nome','1','1','1','30','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','1','10','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_sq_tipo_pessoa.focus()\';');
    } 
  } elseif (!(strpos('P',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>I</u>ncluir</a>&nbsp;');
    } 
    if ($p_nome.$p_ativo>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_tipo_endereco').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Aplica��o','sq_tipo_pessoa').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('e-Mail','email').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Internet','internet').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativodesc').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Padr�o','padraodesc').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    } 
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_tipo_endereco').'</td>');
        ShowHTML('        <td>'.f($row,'sq_tipo_pessoa').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'email').'</td>');
        ShowHTML('        <td align="center">'.f($row,'internet').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativodesc').'</td>');
        ShowHTML('        <td align="center">'.f($row,'padraodesc').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_endereco='.f($row,'sq_tipo_endereco').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_endereco='.f($row,'sq_tipo_endereco').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        } 
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_endereco" value="'.$w_sq_tipo_endereco.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr>');
    SelecaoTipoPessoa('<u>A</u>plica��o:','A','Selecione o tipo de pessoa na rela��o.',$w_sq_tipo_pessoa,null,'w_sq_tipo_pessoa',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'"></td></tr>');
    ShowHTML('      <tr align="left"><td valign="top"><table width="100%" cellpadding=0 cellspacing=0><tr><td>');
    MontaRadioNS('Endere�o de e-Mail?',$w_email,'w_email');
    MontaRadioNS('Endere�o Internet?',$w_internet,'w_internet');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioNS('Padr�o?',$w_padrao,'w_padrao');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('P',$O)===false)) {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,null,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="10" maxlength="10" value="'.$p_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de tipo de telefone
// -------------------------------------------------------------------------
function TipoTelefone() {
  extract($GLOBALS);
  global $w_Disabled;

  $p_nome             = trim(upper($_REQUEST['p_nome']));
  $p_ativo            = trim($_REQUEST['p_ativo']);
  $w_sq_tipo_telefone = $_REQUEST['w_sq_tipo_telefone'];
  $p_ordena           = $_REQUEST['p_ordena'];

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da p�gina
    $w_nome                 = $_REQUEST['w_nome'];
    $w_sq_tipo_pessoa       = $_REQUEST['w_sq_tipo_pessoa'];
    $w_ativo                = $_REQUEST['w_ativo'];
    $w_padrao               = $_REQUEST['w_padrao'];
    
  } elseif (!(strpos('LP',$O)===false)) {
    $SQL = new db_getFoneTypeList; $RS = $SQL->getInstanceOf($dbms,null,$p_nome,$p_ativo);
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'sq_tipo_pessoa','asc','padrao','desc','nome','asc');
    } else {
      $RS = SortArray($RS,'sq_tipo_pessoa','asc','padrao','desc','nome','asc');
    }
  } elseif (($O=='A' || $O=='E')) {
    $SQL = new db_getFoneTypeData; $RS = $SQL->getInstanceOf($dbms,$w_sq_tipo_telefone);
    $w_nome             = f($RS,'nome');
    $w_sq_tipo_pessoa   = f($RS,'sq_tipo_pessoa');
    $w_ativo            = f($RS,'ativo');
    $w_padrao           = f($RS,'padrao');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_tipo_pessoa','Aplica��o','SELECT','1','1','18','','1');
      Validate('w_nome','Nome','1','1','1','25','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','1','10','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_sq_tipo_pessoa.focus()\';');
    } 
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>I</u>ncluir</a>&nbsp;');
    }
    if ($p_nome.$p_ativo>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_tipo_telefone').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Aplica��o','sq_tipo_pessoa').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativodesc').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Padr�o','padraodesc').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_tipo_telefone').'</td>');
        ShowHTML('        <td>'.f($row,'nm_tipo_pessoa').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativodesc').'</td>');
        ShowHTML('        <td align="center">'.f($row,'padraodesc').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_telefone='.f($row,'sq_tipo_telefone').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_telefone='.f($row,'sq_tipo_telefone').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_telefone" value="'.$w_sq_tipo_telefone.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr>');
    SelecaoTipoPessoa('<u>A</u>plica��o:','A','Selecione o tipo de pessoa na rela��o.',$w_sq_tipo_pessoa,null,'w_sq_tipo_pessoa',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_nome" size="25" maxlength="25" value="'.$w_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioNS('Padr�o?',$w_padrao,'w_padrao');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="10" maxlength="10" value="'.$p_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de tipo de pessoa
// -------------------------------------------------------------------------
function TipoPessoa() {
  extract($GLOBALS);
  global $w_Disabled;

  $p_nome           = trim(upper($_REQUEST['p_nome']));
  $p_ativo          = trim($_REQUEST['p_ativo']);
  $w_sq_tipo_pessoa = $_REQUEST['w_sq_tipo_pessoa'];
  $p_ordena         = $_REQUEST['p_ordena'];

  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao=f($RS,'libera_edicao');

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da p�gina
    $w_nome                 = $_REQUEST['w_nome'];
    $w_ativo                = $_REQUEST['w_ativo'];
    $w_padrao               = $_REQUEST['w_padrao'];
  } elseif (!(strpos('LP',$O)===false)) {
    $SQL = new db_getUserTypeList; $RS = $SQL->getInstanceOf($dbms,$p_nome,$p_ativo);
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'padrao','desc','nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (($O=='A' || $O=='E')) {
    $SQL = new db_getUserTypeData; $RS = $SQL->getInstanceOf($dbms,$w_sq_tipo_pessoa);
    $w_nome     = f($RS,'nome');
    $w_ativo    = f($RS,'ativo');
    $w_padrao   = f($RS,'padrao');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','1','60','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','1','10','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 

  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
    } 
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>I</u>ncluir</a>&nbsp;');
    } 
    if ($p_nome.$p_ativo>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_tipo_pessoa').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativodesc').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Padr�o','padraodesc').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    }  
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_tipo_pessoa').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativodesc').'</td>');
        ShowHTML('        <td align="center">'.f($row,'padraodesc').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_tipo_pessoa='.f($row,'sq_tipo_pessoa').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_tipo_pessoa='.f($row,'sq_tipo_pessoa').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        } 
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_pessoa" value="'.$w_sq_tipo_pessoa.'">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_nome" size="60" maxlength="60" value="'.$w_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioNS('Padr�o?',$w_padrao,'w_padrao');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="10" maxlength="10" value="'.$p_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de defici�ncias
// -------------------------------------------------------------------------
function Deficiencia() {
  extract($GLOBALS);
  global $w_Disabled;

  $p_nome           = trim(upper($_REQUEST['p_nome']));
  $p_ativo          = trim($_REQUEST['p_ativo']);
  $w_sq_deficiencia = $_REQUEST['w_sq_deficiencia'];
  $p_ordena         = $_REQUEST['p_ordena'];

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da p�gina
    $w_nome                 = $_REQUEST['w_nome'];
    $w_codigo               = $_REQUEST['w_codigo'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_sq_grupo_deficiencia = $_REQUEST['w_sq_grupo_deficiencia'];
    $w_ativo                = $_REQUEST['w_ativo'];
  } elseif (!(strpos('LP',$O)===false)) {
    $SQL = new db_getDeficiencyList; $RS = $SQL->getInstanceOf($dbms,$p_nome,$p_ativo);
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'sq_grupo_defic','asc','codigo','asc');
    } else {
      $RS = SortArray($RS,'sq_grupo_defic','asc','codigo','asc');
    }
  } elseif (($O=='A' || $O=='E')) {
    $SQL = new db_getDeficiencyData; $RS = $SQL->getInstanceOf($dbms,$w_sq_deficiencia);
    $w_nome                 = f($RS,'nome');
    $w_codigo               = f($RS,'codigo');
    $w_descricao            = f($RS,'descricao');
    $w_sq_grupo_deficiencia = f($RS,'sq_grupo_defic');
    $w_ativo                = f($RS,'ativo');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_grupo_deficiencia','Grupo defici�ncia','SELECT','1','1','18','','1');
      Validate('w_codigo','C�digo','1','1','3','3','','0123456789');
      Validate('w_nome','Nome','1','1','1','50','1','1');
      Validate('w_descricao','Descricao','1','','5','200','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','1','50','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    }  
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_sq_grupo_deficiencia.focus()\';');
    } 
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>I</u>ncluir</a>&nbsp;');
    }
    if ($p_nome.$p_ativo>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_deficiencia').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Grupo','sq_grupo_defic').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('C�digo','codigo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Descri��o','descricao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativodesc').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'sq_deficiencia').'</td>');
        ShowHTML('        <td>'.f($row,'sq_grupo_defic').'</td>');
        ShowHTML('        <td align="center">'.f($row,'codigo').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'descricao').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativodesc').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_deficiencia='.f($row,'sq_deficiencia').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_deficiencia='.f($row,'sq_deficiencia').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_deficiencia" value="'.$w_sq_deficiencia.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr>');
    selecaoGrupoDef('<u>G</u>rupo de defici�ncia:','G','Selecione o grupo de defic�ncia.',$w_sq_grupo_deficiencia,null,'w_sq_grupo_deficiencia',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left"><td valign="top"><table width="100%" cellpadding=0 cellspacing=0><tr><td>');
    ShowHTML('          <td valign="top"><b><U>C</U>�digo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="w_codigo" size="3" maxlength="3" value="'.$w_codigo.'"></td>');
    ShowHTML('          <td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_nome" size="50" maxlength="50" value="'.$w_nome.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr><td valign="top"><b><U>D</U>escri��o: (200 posi��es)<br><TEXTAREA ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_descricao" rows=5 cols=75>'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="50" maxlength="50" value="'.$p_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de grupos de defici�ncia
// -------------------------------------------------------------------------
function GrupoDeficiencia() {
  extract($GLOBALS);
  global $w_Disabled;

  $p_nome                   = trim(upper($_REQUEST['p_nome']));
  $p_codigo_externo         = trim(upper($_REQUEST['p_codigo_externo']));
  $p_ativo                  = trim($_REQUEST['p_ativo']);
  $w_sq_grupo_deficiencia   = $_REQUEST['w_sq_grupo_deficiencia'];
  $p_ordena                 = $_REQUEST['p_ordena'];

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da p�gina
    $w_nome                 = $_REQUEST['w_nome'];
    $w_codigo_externo       = $_REQUEST['w_codigo_externo'];
    $w_ativo                = $_REQUEST['w_ativo'];
  } elseif (!(strpos('LP',$O)===false)) {
    $SQL = new db_getDeficGroupList; $RS = $SQL->getInstanceOf($dbms,$p_nome,$p_codigo_externo,$p_ativo);
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'nome','asc');
    }
  } elseif (($O=='A' || $O=='E')) {
    $SQL = new db_getDeficiencyGroupData; $RS = $SQL->getInstanceOf($dbms,$w_sq_grupo_deficiencia);
    $w_nome             = f($RS,'nome');
    $w_codigo_externo   = f($RS,'codigo_externo');
    $w_ativo            = f($RS,'ativo');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','1','50','1','1');
      Validate('w_codigo_externo','C�digo Externo','1','','','60','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','1','50','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
    } 
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>I</u>ncluir</a>&nbsp;');
    }
    if ($p_nome.$p_ativo>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_grupo_defic').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('C�digo externo','codigo_externo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativodesc').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_grupo_defic').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'codigo_externo'),"&nbsp;").'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativodesc').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_grupo_deficiencia='.f($row,'sq_grupo_defic').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_grupo_deficiencia='.f($row,'sq_grupo_defic').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<FORM action="'.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_grupo_deficiencia" value="'.$w_sq_grupo_deficiencia.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_nome" size="50" maxlength="50" value="'.$w_nome.'"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b><U>C</U>odigo externo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="w_codigo_externo" size="60" maxlength="60" value="'.$w_codigo_externo.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="50" maxlength="50" value="'.$p_nome.'"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b><U>C</U>�digo externo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="p_codigo_externo" size="60" maxlength="60" value="'.$p_codigo_externo.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de idioma
// -------------------------------------------------------------------------
function Idioma() {
  extract($GLOBALS);
  global $w_Disabled;

  $p_nome       = trim(upper($_REQUEST['p_nome']));
  $p_ativo      = trim($_REQUEST['p_ativo']);
  $w_sq_idioma  = $_REQUEST['w_sq_idioma'];
  $p_ordena     = $_REQUEST['p_ordena'];

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da p�gina
    $w_nome           = $_REQUEST['w_nome'];
    $w_ativo          = $_REQUEST['w_ativo'];
    $w_padrao         = $_REQUEST['w_padrao'];
  } elseif (!(strpos('LP',$O)===false)) {
    $SQL = new db_getIdiomList; $RS = $SQL->getInstanceOf($dbms,$p_nome,$p_ativo);
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'padrao','desc','nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (($O=='A' || $O=='E')) {
    $SQL = new db_getIdiomData; $RS = $SQL->getInstanceOf($dbms,$w_sq_idioma);
    $w_nome     = f($RS,'nome');
    $w_ativo    = f($RS,'ativo');
    $w_padrao   = f($RS,'padrao');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','1','20','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','1','10','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
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
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>I</u>ncluir</a>&nbsp;');
    }
    if ($p_nome.$p_ativo>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_idioma').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativodesc').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Padr�o','padraodesc').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_idioma').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativodesc').'</td>');
        ShowHTML('        <td align="center">'.f($row,'padraodesc').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_idioma='.f($row,'sq_idioma').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_idioma='.f($row,'sq_idioma').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_idioma" value="'.$w_sq_idioma.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_nome" size="20" maxlength="20" value="'.$w_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioNS('Padr�o?',$w_padrao,'w_padrao');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');    
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="10" maxlength="10" value="'.$p_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }  
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de etnia
// -------------------------------------------------------------------------
function Etnia() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_sq_etnia   = $_REQUEST['w_sq_etnia'];
  $p_ordena     = $_REQUEST['p_ordena'];

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da p�gina
    $w_nome           = $_REQUEST['w_nome'];
    $w_ativo          = $_REQUEST['w_ativo'];
    $w_codigo_siape   = $_REQUEST['w_codigo_siape'];
  } elseif (!(strpos('LP',$O)===false)) {
    $SQL = new db_getEtniaList; $RS = $SQL->getInstanceOf($dbms,$p_nome,$p_ativo);
    if ($p_ordena>'') {
    $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'codigo_siape','asc','nome','asc');
    } else {
      $RS = SortArray($RS,'codigo_siape','asc','nome','asc');
    }
  } elseif (($O=='A' || $O=='E')) {
    $SQL = new db_getEtniaData; $RS = $SQL->getInstanceOf($dbms,$w_sq_etnia);
    $w_nome         = f($RS,'nome');
    $w_ativo        = f($RS,'ativo');
    $w_codigo_siape = f($RS,'codigo_siape');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','1','10','1','1');
      Validate('w_codigo_siape','C�digo externo','1','1','2','2','','0123456789');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','1','10','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }  
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
    } 
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>I</u>ncluir</a>&nbsp;');
    }
    if ($p_nome.$p_ativo>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_etnia').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('C�digo externo','codigo_siape').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','descativo').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_etnia').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'codigo_siape').'</td>');
        ShowHTML('        <td align="center">'.f($row,'descativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_etnia='.f($row,'sq_etnia').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_etnia='.f($row,'sq_etnia').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
    DesConectaBD();
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_etnia" value="'.$w_sq_etnia.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_nome" size="10" maxlength="10" value="'.$w_nome.'"></td></tr>');
    if ($O=='I') $w_codigo_siape=0;
    ShowHTML('      <tr><td valign="top"><b><U>C</U>�digo externo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="w_codigo_siape" size="2" maxlength="2" value="'.$w_codigo_siape.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
      ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
      ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="10" maxlength="10" value="'.$p_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de forma��o
// -------------------------------------------------------------------------
function Formacao() {
  extract($GLOBALS);
  global $w_Disabled;

  $p_nome           = trim(upper($_REQUEST['p_nome']));
  $p_ativo          = trim($_REQUEST['p_ativo']);
  $w_sq_formacao    = $_REQUEST['w_sq_formacao'];
  $p_ordena         = $_REQUEST['p_ordena'];

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da p�gina
    $w_nome      = $_REQUEST['w_nome'];
    $w_ativo     = $_REQUEST['w_ativo'];
    $w_ativo     = $_REQUEST['w_ativo'];
    $w_ordem     = $_REQUEST['w_ordem'];
  } elseif (!(strpos('LP',$O)===false)) {
    $SQL = new db_getFormationList; $RS = $SQL->getInstanceOf($dbms,null,$p_nome,$p_ativo);
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'tipo','asc','ordem','asc','nome','asc');
    } else {
      $RS = SortArray($RS,'tipo','asc','ordem','asc','nome','asc');
    }
  } elseif (($O=='A' || $O=='E')) {
    $SQL = new db_getFormationData; $RS = $SQL->getInstanceOf($dbms,$w_sq_formacao);
    $w_nome     = f($RS,'nome');
    $w_tipo     = f($RS,'tipo');
    $w_ativo    = f($RS,'ativo');
    $w_ordem    = f($RS,'ordem');
  } 

  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','1','50','1','1');
      Validate('w_ordem','ordem','1','1','1','4','','0123456789');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_nome','Nome','1','','1','50','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } elseif ($O=='P') {
    BodyOpen('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>I</u>ncluir</a>&nbsp;');
    }
    if ($p_nome.$p_ativo>'') {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_formacao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','tipo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ordem','ordem').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativodesc').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_formacao').'</td>');
        ShowHTML('        <td align="center">'.f($row,'tipo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativodesc').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_formacao='.f($row,'sq_formacao').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_formacao='.f($row,'sq_formacao').'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="p_nome" value="'.$p_nome.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_formacao" value="'.$w_sq_formacao.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    if ($O=='I') $w_ordem=0; 
    ShowHTML('      <tr><td valign="top"><b>Tipo forma��o:</b><br>');
    if ($w_tipo=='' || $w_tipo=='1') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="1" checked> Acad�mica <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="2"> Curso t�cnico <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="3"> Produ��o cientifica');
    } elseif ($w_tipo=='2') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="1"> Acad�mica <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="2"  checked> Curso t�cnico <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="3"> Produ��o cientifica');
    } elseif ($w_tipo=='3') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="1"> Acad�mica <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="2" > Curso t�cnico <input '.$w_Disabled.' class="STR" type="radio" name="w_tipo" value="3"  checked> Produ��o cientifica');
    } 
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_nome" size="50" maxlength="50" value="'.$w_nome.'"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b><U>O</U>rdem:<br><INPUT ACCESSKEY="O" '.$w_Disabled.' class="STI" type="text" name="w_ordem" size="4" maxlength="4" value="'.$w_ordem.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,1,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="30" maxlength="30" value="'.$p_nome.'"></td></tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }  
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');

  switch ($SG) {
    case 'COTPENDER':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CoTpEnder; $SQL->getInstanceOf($dbms,$O, 
          $_REQUEST['w_sq_tipo_endereco'],$_REQUEST['w_sq_tipo_pessoa'],$_REQUEST['w_nome'],
          $_REQUEST['w_padrao'],$_REQUEST['w_ativo'],$_REQUEST['w_email'],$_REQUEST['w_internet']);

        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COTPFONE':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CoTpFone; $SQL->getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_tipo_telefone'],$_REQUEST['w_sq_tipo_pessoa'],$_REQUEST['w_nome'],
            $_REQUEST['w_padrao'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COTPPESSOA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CoTpPessoa; $SQL->getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_tipo_pessoa'],$_REQUEST['w_nome'],
            $_REQUEST['w_padrao'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COTPDEF': 
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CoTpDef; $SQL->getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_deficiencia'],$_REQUEST['w_sq_grupo_deficiencia'],$_REQUEST['w_codigo'],
            $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COGRDEF':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if (!(strpos('IA',$O)===false)) { 
            $SQL = new db_getDeficGroupList; $RS = $SQL->getInstanceOf($dbms, $_REQUEST['w_nome'], null, null);
            if (count($RS)>0) {
              $w_erro = false;
              foreach($RS as $row) { 
                if(upper($_REQUEST['w_nome']) == upper(f($row,'nome')) && ($O=='I' || ($O=='A' && $_REQUEST['w_sq_grupo_deficiencia']!=f($row,'sq_grupo_defic') ))){
                  $w_erro = true;
                }                
              }
              if($w_erro){
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Nome j� cadastrado!\');');
                ScriptClose();
                retornaFormulario('w_nome');
                exit;
              }                      
            }
            $SQL = new db_getDeficGroupList; $RS = $SQL->getInstanceOf($dbms, null, $_REQUEST['w_codigo_externo'], null);
            if (count($RS)>0) {
              $w_erro = false;
              foreach($RS as $row) { 
                if($O=='I' || ($O=='A' && $_REQUEST['w_sq_grupo_deficiencia']!=f($row,'sq_grupo_defic') )){
                  $w_erro = true;
                }                
              }
              if($w_erro){
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'C�digo externo j� cadastrado!\');');
                ScriptClose();
                retornaFormulario('w_nome');
                exit;
              }                      
            }
        } 
        $SQL = new dml_CoGrDef; $SQL->getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_grupo_deficiencia'],$_REQUEST['w_nome'],
            $_REQUEST['w_codigo_externo'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COIDIOMA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $w_cont = 0;
        $SQL = new db_getIdiomList; $RS = $SQL->getInstanceOf($dbms,null,null);
        foreach($RS as $row) {
          if(f($row,'padrao')=='S' && $_REQUEST['w_padrao']=='S') {
            if ($O=='I') $w_cont = $w_cont + 1;
            elseif ($O=='A' && $_REQUEST['w_sq_idioma']!=f($row,'sq_idioma'))  $w_cont = $w_cont + 1;
          }
        }
        if($w_cont>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: S� pode haver um valor padr�o para idioma. Favor verificar.\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
        } else {
          $SQL = new dml_CoIdioma; $SQL->getInstanceOf($dbms, $O,
              $_REQUEST['w_sq_idioma'],$_REQUEST['w_nome'],
              $_REQUEST['w_padrao'],$_REQUEST['w_ativo']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
          ScriptClose();
        }
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COETNIA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CoEtnia; $SQL->getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_etnia'],$_REQUEST['w_nome'],$_REQUEST['w_codigo_siape'],
            $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COFORM':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CoForm; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_sq_formacao'],$_REQUEST['w_tipo'],$_REQUEST['w_nome'],
            $_REQUEST['w_ordem'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
//        ShowHTML('  alert(\'ATEN��O: S� pode haver um valor padr�o para idioma Favor verificar.!\');');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
  } 

  return $function_ret;
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  // Verifica se o usu�rio tem lota��o e localiza��o
  switch ($par) {
  case 'ENDERECO':      TipoEndereco();     break;
  case 'TELEFONE':      TipoTelefone();     break;
  case 'PESSOA':        TipoPessoa();       break;
  case 'DEFICIENCIA':   Deficiencia();      break;
  case 'GRDEFICIENCIA': GrupoDeficiencia(); break;
  case 'IDIOMA':        Idioma();           break;
  case 'ETNIA':         Etnia();            break;
  case 'FORMACAO':      Formacao();         break;
  case 'GRAVA':         Grava();            break;
  default:
    Cabecalho();
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 
  return $function_ret;
} 
?>
