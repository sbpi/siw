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

include_once($w_dir_volta.'classes/sp/db_getPDParametro.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getMtSituacao.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getAlmoxarifado.php');
include_once($w_dir_volta.'classes/sp/db_getTipoMovimentacao.php');
include_once($w_dir_volta.'classes/sp/db_getSolicMT.php');

include_once($w_dir_volta.'classes/sp/dml_putMtSituacao.php');
include_once($w_dir_volta.'classes/sp/dml_putPDParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUnidade.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUnidLimite.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUsuario.php');
include_once($w_dir_volta.'classes/sp/dml_putAlmoxarifado.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoMovimentacao.php');
include_once($w_dir_volta.'classes/sp/dml_putAlmoxarifadoLocal.php');
include_once($w_dir_volta.'classes/sp/dml_putMtAjuste.php');

include_once($w_dir_volta.'funcoes/selecaoAlmoxarifado.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServSubord.php');
include_once($w_dir_volta.'funcoes/selecaoClasseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoLocalSubordination.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoAno.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoContinente.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoMoeda.php');


// =========================================================================
//  /gestao.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia as rotinas de tabelas b�sicas do m�dulo de passagens e di�rias
// Mail     : celso@sbpi.com.br
// Criacao  : 04/10/2005 11:00
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = P   : Filtragem

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

$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'gestao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_mt/';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3 || $par=='AJUSTE') $O='P'; else $O='L';  
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';  break;
  case 'A': $w_TP=$TP.' - Altera��o'; break;
  case 'E': $w_TP=$TP.' - Exclus�o';  break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'C': $w_TP=$TP.' - C�pia';     break;
  case 'V': $w_TP=$TP.' - Envio';     break;
  case 'H': $w_TP=$TP.' - Heran�a';   break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

$p_ordena       = lower($_REQUEST['p_ordena']);
$p_tipo         = upper($_REQUEST['w_tipo']);
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_atividade    = upper($_REQUEST['p_atividade']);
$p_graf         = upper($_REQUEST['p_graf']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_usu_resp     = upper($_REQUEST['p_usu_resp']);
$p_ordena       = lower($_REQUEST['p_ordena']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_acao_ppa     = upper($_REQUEST['p_acao_ppa']);
$p_empenho      = upper($_REQUEST['p_empenho']);
$p_chave        = upper($_REQUEST['p_chave']);
$p_assunto      = upper($_REQUEST['p_assunto']);
$p_pais         = upper($_REQUEST['p_pais']);
$p_regiao       = upper($_REQUEST['p_regiao']);
$p_uf           = upper($_REQUEST['p_uf']);
$p_cidade       = upper($_REQUEST['p_cidade']);
$p_uorg_resp    = upper($_REQUEST['p_uorg_resp']);
$p_palavra      = upper($_REQUEST['p_palavra']);
$p_prazo        = upper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = upper($_REQUEST['p_sqcc']);
$p_agrega       = upper($_REQUEST['p_agrega']);
$p_tamanho      = upper($_REQUEST['p_tamanho']);


// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Manter Tabela b�sica 'MT_TIPO_MOVIMENTACAO'
// -------------------------------------------------------------------------
function TipoMovimentacao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave=$_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_nome         = $_REQUEST['w_nome'];
    $w_entrada      = $_REQUEST['w_entrada'];
    $w_saida        = $_REQUEST['w_saida'];
    $w_orcamentario = $_REQUEST['w_orcamentario'];
    $w_consumo      = $_REQUEST['w_consumo'];
    $w_permanente   = $_REQUEST['w_permanente'];
    $w_inativa_bem  = $_REQUEST['w_inativa_bem'];
    $w_ativo        = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getTipoMovimentacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (strpos('AE',$O)!==false && $w_troca=='') {
    // Recupera os dados chave informada
    $sql = new db_getTipoMovimentacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave        = f($RS,'chave');
    $w_nome         = f($RS,'nome');
    $w_entrada      = f($RS,'entrada');
    $w_saida        = f($RS,'saida');
    $w_orcamentario = f($RS,'orcamentario');
    $w_consumo      = f($RS,'consumo');
    $w_permanente   = f($RS,'permanente');
    $w_inativa_bem  = f($RS,'inativa_bem');
    $w_ativo        = f($RS,'ativo');
  }
  Cabecalho();
  head();
  if (strpos('IAE',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_nome','Nome','1','1','2','30','1','1');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
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
  } elseif (strpos('IA',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td nowrap><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right" colspan=2 nowrap>'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Entrada','nm_entrada').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sa�da','nm_saida').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Or�ament�rio','nm_orcamentario').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Consumo','nm_consumo').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Permanente','nm_permanente').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Inativa Bem','nm_inativa_bem').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td class="remover"><b>Opera��es</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_entrada').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_saida').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_orcamentario').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_consumo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_permanente').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_inativa_bem').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap class="remover">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAE',$O)!==false) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Entrada?</b>',$w_entrada,'w_entrada');
    MontaRadioSN('<b>Sa�da?</b>',$w_saida,'w_saida');
    MontaRadioSN('<b>Or�ament�rio?</b>',$w_orcamentario,'w_orcamentario');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Consumo?</b>',$w_consumo,'w_consumo');
    MontaRadioSN('<b>Permanente?</b>',$w_permanente,'w_permanente');
    MontaRadioSN('<b>Inativar bem?</b>',$w_inativa_bem,'w_inativa_bem');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}



// =========================================================================
// Manter Tabela b�sica 'MT_SITUACAO'
// -------------------------------------------------------------------------
function Situacao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave=$_REQUEST['w_chave'];
  

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_nome             = $_REQUEST['w_nome'];
    $w_sigla            = $_REQUEST['w_sigla'];
    $w_entrada          = $_REQUEST['w_entrada'];
    $w_saida            = $_REQUEST['w_saida'];
    $w_estorno          = $_REQUEST['w_estorno'];
    $w_consumo          = $_REQUEST['w_consumo'];
    $w_permanente       = $_REQUEST['w_permanente'];
    $w_inativa_bem      = $_REQUEST['w_inativa_bem'];
    $w_situacao_fisica  = $_REQUEST['w_situacao_fisica'];
    $w_ativo            = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (strpos('AE',$O)!==false) {
    // Recupera os dados chave informada
    $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_chave,null,null,null,null);  
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave            = f($RS,'chave');
    $w_nome             = f($RS,'nome');
    $w_sigla            = f($RS,'sigla');
    $w_entrada          = f($RS,'entrada');
    $w_saida            = f($RS,'saida');
    $w_estorno          = f($RS,'estorno');
    $w_consumo          = f($RS,'consumo');
    $w_permanente       = f($RS,'permanente');
    $w_inativa_bem      = f($RS,'inativa_bem');
    $w_situacao_fisica  = f($RS,'situacao_fisica');
    $w_ativo            = f($RS,'ativo');
  }

  Cabecalho();
  head();
  if (strpos('IAE',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_nome','Nome','1','1','2','30','1','1');
      Validate('w_sigla','Sigla','1','1','2','2','1','1');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
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
  } elseif (strpos('IA',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td nowrap><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right" colspan=2 nowrap>'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Entrada','entrada').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Saida','saida').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Consumo','consumo').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Permanente','permanete').'</font></td>');
    ShowHTML('          <td class="remover"><b>Opera��es</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'entrada').'</td>');
        ShowHTML('        <td align="center">'.f($row,'saida').'</td>');
        ShowHTML('        <td align="center">'.f($row,'consumo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'permanente').'</td>');
        ShowHTML('        <td valign="top" nowrap class="remover">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAE',$O)!==false) {
    if ($O=='E') $w_Disabled=' DISABLED ';

    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('       <tr>    <td colspan=3><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_sigla" class="sti" SIZE="3" MAXLENGTH="2" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Entrada?</b>',$w_entrada,'w_entrada');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Saida?</b>',$w_saida,'w_saida');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Estorno?</b>',$w_estorno,'w_estorno');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Consumo?</b>',$w_consumo,'w_consumo');
  ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Permanente?</b>',$w_permanente,'w_permanente');
  ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Inativa Bem?</b>',$w_inativa_bem,'w_inativa_bem');
  ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Situa��o F�sica?</b>',$w_situacao_fisica,'w_situacao_fisica');
  ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}


function Locais() {
  extract($GLOBALS);
  global $w_Disabled;
  
  $w_ImagemPadrao = 'images/Folder/SheetLittle.gif';
  $w_troca        = $_REQUEST['w_troca'];
  $w_copia        = $_REQUEST['w_copia'];
  $w_chave        = $_REQUEST['w_chave'];
  $w_chave_aux    = $_REQUEST['w_chave_aux'];
  
  if ($w_troca>'' && $O!='E' && $O!='D' && $O!='T') {  
    $w_cliente        = $_REQUEST['w_cliente'];
    $w_chave_pai      = $_REQUEST['w_chave_pai'];
    $w_chave_aux      = $_REQUEST['w_chave_aux'];    
    $w_nome           = $_REQUEST['w_nome'];
    $w_sigla          = $_REQUEST['w_sigla'];
    $w_ativo          = $_REQUEST['w_ativo'];
  } elseif ($O != 'L' && $O != 'I') {
      // Se for heran�a, atribui a chave da op��o selecionada para w_chave
    if ($w_copia>'') $w_chave = $w_copia;
    $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,$w_chave_aux,null,null,null,'REGISTROS');
    foreach ($RS as $row) { $RS = $row; break; }
    $w_chave_pai      = f($RS,'sq_local_pai');
    $w_nome           = f($RS,'nome');
    $w_ativo          = f($RS,'ativo');    
  }  
  
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Locais de Almoxarifado</TITLE>');
  Estrutura_CSS($w_cliente);

  if ($O!='L') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($O!='P') {
      if ($O=='C' || $O=='I' || $O=='A') {
        Validate('w_nome','Nome','1','1','2','30','1','1');
      } 
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='C' || $O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_chave_pai.focus();"');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="99%" border="0">');
  if ($O=='L') {
  
    ShowHTML('      <tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td><b>');
    $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,'IS NULL');
    $w_contOut = 0;
    foreach($RS as $row) {
      $w_nome  = f($row,'nome');
      $w_contOut = $w_contOut+1;
      if (f($row,'Filho')>0) {
        ShowHTML('<A HREF=#"'.f($row,'chave').'"></A>');
        ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row,'nome').'');
        if (f($row,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_almoxarifado').'&w_chave_aux='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informa��es deste tipo">AL</A>&nbsp');
        ShowHTML('       </div></span>');
        ShowHTML('   <div style="position:relative; left:12;">');
        $sql = new db_getAlmoxarifado; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,f($row,'chave'));
        foreach($RS1 as $row1) {
          $w_nome .= ' - '.f($row1,'nome');
          if (f($row1,'Filho')>0) {          
            $w_contOut=$w_contOut+1;
            ShowHTML('<A HREF=#"'.f($row1,'chave').'"></A>');
            ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row1,'nome').'');
            if (f($row1,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1,'sq_almoxarifado').'&w_chave_aux='.f($row1,'chave').'&w_cliente='.$w_cliente.'&nome='.f($row1,'nome').'&pai='.f($row1,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informa��es deste tipo">AL</A>&nbsp');
            ShowHTML('       </div></span>');
            ShowHTML('   <div style="position:relative; left:12;">');
            $sql = new db_getAlmoxarifado; $RS2 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,f($row1,'chave'));
            foreach($RS2 as $row2) {
              $w_nome .= ' - '.f($row2,'nome');
              if (f($row2,'Filho')>0) {
                $w_contOut = $w_contOut+1;
                ShowHTML('<A HREF=#"'.f($row2,'chave').'"></A>');
                ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row2,'nome').'');
                if (f($row2,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2,'sq_almoxarifado').'&w_chave_aux='.f($row2,'chave').'&w_cliente='.$w_cliente.'&nome='.f($row2,'nome').'&pai='.f($row2,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informa��es deste tipo">AL</A>&nbsp');
                ShowHTML('       </div></span>');
                ShowHTML('   <div style="position:relative; left:12;">');
                $sql = new db_getAlmoxarifado; $RS3 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,f($row2,'chave'));
                foreach($RS3 as $row3) {
                  $w_nome .= ' - '.f($row3,'nome');
                  $w_Imagem=$w_ImagemPadrao;
                  ShowHTML('<A HREF=#"'.f($row3,'chave').'"></A>');
                  ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row3,'nome'));
                  if (f($row3,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row3,'sq_almoxarifado').'&w_chave_aux='.f($row3,'chave').'&w_cliente='.$w_cliente.'&nome='.f($row3,'nome').'&pai='.f($row3,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informa��es deste tipo">AL</A>&nbsp');
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row3,'sq_almoxarifado').'&w_chave_aux='.f($row3,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row3,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
                  ShowHTML('    <BR>');
                  $w_nome = str_replace(' - '.f($row3,'nome'),'',$w_nome);
                } 
                ShowHTML('   </div>');
              } else {
                $w_Imagem=$w_ImagemPadrao;
                ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row2,'nome'));
                if (f($row2,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2,'sq_almoxarifado').'&w_chave_aux='.f($row2,'chave').'&w_cliente='.$w_cliente.'&nome='.f($row2,'nome').'&pai='.f($row2,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informa��es deste tipo">AL</A>&nbsp');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row2,'sq_almoxarifado').'&w_chave_aux='.f($row2,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row2,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
                ShowHTML('    <BR>');
              } 
              $w_nome=str_replace(' - '.f($row2,'nome'),'',$w_nome);
            } 
            ShowHTML('   </div>');
          } else {
            $w_Imagem=$w_ImagemPadrao;
            ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row1,'nome'));
            if (f($row1,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1,'sq_almoxarifado').'&w_chave_aux='.f($row1,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informa��es deste tipo">AL</A>&nbsp');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row1,'sq_almoxarifado').'&w_chave_aux='.f($row1,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row1,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
            ShowHTML('    <BR>');
          } 
          $w_nome=str_replace(' - '.f($row1,'nome'),'',$w_nome);
        } 
        ShowHTML('   </div>');
      } else {
        $w_Imagem=$w_ImagemPadrao;
        ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row,'nome'));
        if (f($row,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_almoxarifado').'&w_chave_aux='.f($row,'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Altera as informa��es deste tipo">AL</A>&nbsp');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_almoxarifado').'&w_chave_aux='.f($row,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exclui o tipo">EX</A>&nbsp');
        ShowHTML('    <BR>');
      } 
    } 
    if ($w_contOut==0) {
      // Se n�o achou registros
      ShowHTML('N�o foram encontrados registros.');
    } 
  } elseif (strpos('CIAEDT',$O)!==false) {
    if ($O == 'C' || $O=='I' || $O=='A') {
      ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">Orienta��o:<ul><li>N�o � permitido subordinar um tipo de recurso a outro que j� tenha recursos vinculados.</ul></b></font></td>');
      if ($O=='C') ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O: Dados importados de outro registro. Altere os dados necess�rios antes de executar a inclus�o.</b></font>.</td>');
    } 
    if ($O != 'C' && $O!='I' && $O!='A') $w_Disabled='disabled';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_ativo" value="'.$w_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O!='C') ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('      <tr valign="top">');
    if ($O!='I' && $O!='C') {
      // Se for altera��o, n�o deixa vincular a op��o a ela mesma, nem a seus filhos
      selecaoLocalSubordination('<u>S</u>ubordina��o:','S','Se esta op��o estiver subordinada a outra j� existente, informe qual.',$w_chave_pai,$w_chave,'w_chave_pai','SUBPARTE',null);
    } else {
      selecaoLocalSubordination('<u>S</u>ubordina��o:','S','Se esta op��o estiver subordinada a outra j� existente, informe qual.',$w_chave_pai,$w_chave,'w_chave_pai','SUBTODOS',null);
    } 
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('            <td><b><u>N</u>ome:<br><INPUT ACCESSKEY="N" TYPE="TEXT" CLASS="sti" NAME="w_nome" SIZE=30 MAXLENGTH=30 VALUE="'.$w_nome.'" '.$w_Disabled.' title="Nome do tipo."></td>');
    ShowHTML('        </table>');
    if ($O=='I' || $O=='C' || $O=='A') {
      ShowHTML('      <tr align="left">');
      MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </td></tr>');
    ShowHTML('      <tr><td><b><U>A</U>ssinatura Eletr�nica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    if ($O=='E') {
      ShowHTML('    <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Atualizar">');
    } elseif ($O=='T') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Ativar">');
    } elseif ($O=='C') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Copiar">');
    } elseif ($O=='D') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Desativar">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('      </td></tr>');
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
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================

function Almoxarifado() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave=$_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_nome         = $_REQUEST['w_nome'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_unidade      = $_REQUEST['w_unidade'];
    $w_localizacao  = $_REQUEST['w_localizacao'];
    
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,'OUTROS');
    
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (strpos('AE',$O)!==false && $w_troca=='') {
    // Recupera os dados chave informada
    $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,'OUTROS');
    $sql = new db_getAlmoxarifado; //$RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,'OUTROS');
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave        = f($RS,'chave');
    $w_nome         = f($RS,'nome');
    $w_localizacao  = f($RS,'sq_localizacao');
    $w_unidade      = f($RS,'sq_unidade');
    $w_ativo        = f($RS,'ativo');
  }
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Almoxarifado</TITLE>');
  if (strpos('IAE',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_nome','Nome','1','1','2','30','1','1');
      Validate('w_unidade','Unidade','SELECT','1','1','18','','1');
      Validate('w_localizacao','Localiza��o','SELECT','1','1','18','','1');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }

    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }

  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('IA',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td nowrap><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right" colspan=2 nowrap>'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Localiza��o','nm_unidade').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td class="remover"><b>Opera��es</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'nm_unidade').' (' . f($row, 'nm_localizacao') . ')</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap class="remover">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('          <a class="HL" href="javascript:this.status.value;" onclick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'LOCAIS&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PDLOCAIS').'\',\'Locais\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Locais</a>');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAE',$O)!==false) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <tr>');
    selecaoUnidade('<U>U</U>nidade:','U','Selecione a unidade e aguarde a recarga da p�gina para selecionar sua localiza��o.',$w_unidade,null,'w_unidade',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_localizacao\'; document.Form.submit();"');
    ShowHTML('          <tr>');
    selecaoLocalizacao('Locali<u>z</u>a��o:','Z',null,$w_localizacao,nvl($w_unidade,0),'w_localizacao',null);
    ShowHTML('          </tr>');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Ajuste de estoque
// -------------------------------------------------------------------------
function Ajuste() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L') {
    $w_filtro='';
    if ($p_chave>'') {
      $w_linha++;
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_chave,null,null,null,null,'OUTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Almoxarifado<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/servi�o<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    /*
    if ($p_projeto>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informa��es do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_empenho>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">C�digo <td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descri��o <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Respons�vel <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situa��o do certame <td>[<b>'.f($row,'nome').'</b>]';
        break;
      }
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">N�mero do certame <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    */
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restri��o<td>[<b>Apenas materiais dispon�veis para pedidos internos</b>]';
    } 
    /*
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Abertura de propostas <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autoriza��o <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    */
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    $sql = new db_getSolicMT; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ALINV',3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $p_acao_ppa, null, $p_empenho, null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_material','asc');
    } else {
      $RS = SortArray($RS,'nm_material','asc');
    }
  }
  $w_linha_filtro = $w_linha;

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
  ScriptOpen('Javascript');
  if ($O=='L' && count($RS)) {
    ShowHTML('  function MarcaTodos() {');
    ShowHTML('    for (i=1; i < document.Form["w_sq_estoque[]"].length; i++) {');
    ShowHTML('      document.Form["w_sq_estoque[]"][i].checked=true;');
    ShowHTML('      document.Form["w_minimo[]"][i].disabled=false;');
    ShowHTML('      document.Form["w_minimo[]"][i].className="STIO";');
    ShowHTML('      document.Form["w_consumo[]"][i].disabled=false;');
    ShowHTML('      document.Form["w_consumo[]"][i].className="STIO";');
    ShowHTML('      document.Form["w_ciclo[]"][i].disabled=false;');
    ShowHTML('      document.Form["w_ciclo[]"][i].className="STIO";');
    ShowHTML('      document.Form["w_ponto[]"][i].disabled=false;');
    ShowHTML('      document.Form["w_ponto[]"][i].className="STIO";');
    ShowHTML('      document.Form["w_disponivel[]"][i].disabled=false;');
    ShowHTML('      document.Form["w_disponivel[]"][i].className="STIO";');
    ShowHTML('      document.Form["w_chefe_autoriza[]"][i].disabled=false;');
    ShowHTML('      document.Form["w_chefe_autoriza[]"][i].className="STIO";');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  function DesmarcaTodos() {');
    ShowHTML('    for (i=1; i < document.Form["w_sq_estoque[]"].length; i++) {');
    ShowHTML('      document.Form["w_sq_estoque[]"][i].checked=false;');
    ShowHTML('      document.Form["w_minimo[]"][i].disabled=true;');
    ShowHTML('      document.Form["w_minimo[]"][i].className="STI";');
    ShowHTML('      document.Form["w_consumo[]"][i].disabled=true;');
    ShowHTML('      document.Form["w_consumo[]"][i].className="STI";');
    ShowHTML('      document.Form["w_ciclo[]"][i].disabled=true;');
    ShowHTML('      document.Form["w_ciclo[]"][i].className="STI";');
    ShowHTML('      document.Form["w_ponto[]"][i].disabled=true;');
    ShowHTML('      document.Form["w_ponto[]"][i].className="STI";');
    ShowHTML('      document.Form["w_disponivel[]"][i].disabled=true;');
    ShowHTML('      document.Form["w_disponivel[]"][i].className="STI";');
    ShowHTML('      document.Form["w_chefe_autoriza[]"][i].disabled=true;');
    ShowHTML('      document.Form["w_chefe_autoriza[]"][i].className="STI";');
    ShowHTML('    } ');
    ShowHTML('  }');
    ShowHTML('  function marca(i) {');
    ShowHTML('    if (document.Form["w_sq_estoque[]"][i].checked) {');
    ShowHTML('       document.Form["w_minimo[]"][i].disabled=false;');
    ShowHTML('       document.Form["w_minimo[]"][i].className="STIO";');
    ShowHTML('       document.Form["w_consumo[]"][i].disabled=false;');
    ShowHTML('       document.Form["w_consumo[]"][i].className="STIO";');
    ShowHTML('       document.Form["w_ciclo[]"][i].disabled=false;');
    ShowHTML('       document.Form["w_ciclo[]"][i].className="STIO";');
    ShowHTML('       document.Form["w_ponto[]"][i].disabled=false;');
    ShowHTML('       document.Form["w_ponto[]"][i].className="STIO";');
    ShowHTML('       document.Form["w_disponivel[]"][i].disabled=false;');
    ShowHTML('       document.Form["w_disponivel[]"][i].className="STIO";');
    ShowHTML('       document.Form["w_chefe_autoriza[]"][i].disabled=false;');
    ShowHTML('       document.Form["w_chefe_autoriza[]"][i].className="STIO";');
    ShowHTML('    } else {');
    ShowHTML('       document.Form["w_minimo[]"][i].disabled=true;');
    ShowHTML('       document.Form["w_minimo[]"][i].className="STI";');
    ShowHTML('       document.Form["w_consumo[]"][i].disabled=true;');
    ShowHTML('       document.Form["w_consumo[]"][i].className="STI";');
    ShowHTML('       document.Form["w_ciclo[]"][i].disabled=true;');
    ShowHTML('       document.Form["w_ciclo[]"][i].className="STI";');
    ShowHTML('       document.Form["w_ponto[]"][i].disabled=true;');
    ShowHTML('       document.Form["w_ponto[]"][i].className="STI";');
    ShowHTML('       document.Form["w_disponivel[]"][i].disabled=true;');
    ShowHTML('       document.Form["w_disponivel[]"][i].className="STI";');
    ShowHTML('       document.Form["w_chefe_autoriza[]"][i].disabled=true;');
    ShowHTML('       document.Form["w_chefe_autoriza[]"][i].className="STI";');
    ShowHTML('    }');
    ShowHTML('  }');
  }
  CheckBranco();
  FormataData();
  FormataValor();
  SaltaCampo();
  ValidateOpen('Validacao');
  if ($O=='P') {
    Validate('p_chave','Almoxarifado','SELECT','1','1','18','','1');
    Validate('p_proponente','Material','','','2','60','1','');
  } else {
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=1; i < theForm["w_sq_estoque[]"].length; i++) {');
    ShowHTML('    if (theForm["w_sq_estoque[]"][i].checked) w_erro=false;');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert("Voc� deve informar pelo menos um item!"); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    ShowHTML('  for (ind=1; ind < theForm["w_sq_estoque[]"].length; ind++) {');
    ShowHTML('    if(theForm["w_sq_estoque[]"][ind].checked){');
    Validate('["w_minimo[]"][ind]','Estoque m�nimo','VALOR','1',1,18,'','0123456789.');
    CompValor('["w_minimo[]"][ind]','Estoque m�nimo','>','0','zero');
    Validate('["w_consumo[]"][ind]','Consumo m�dio mensal','VALOR','1',1,18,'','0123456789.,');
    CompValor('["w_consumo[]"][ind]','Consumo m�dio mensal','>','0','zero');
    Validate('["w_ciclo[]"][ind]','Ciclo de compra','VALOR','1',1,4,'','0123456789.');
    CompValor('["w_ciclo[]"][ind]','Ciclo de compra','>','0','zero');
    Validate('["w_ponto[]"][ind]','Ponto de ressuprimento','VALOR','1',1,18,'','0123456789.');
    CompValor('["w_ponto[]"][ind]','Ponto de ressuprimento','>','0','zero');
    ShowHTML('    }');
    ShowHTML('  }');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    // Se for recarga da p�gina
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='L' && count($RS)) {
    BodyOpenClean('onLoad="this.focus(); DesmarcaTodos();"');
  } else {
    BodyOpenClean('onLoad="this.focus();"');
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');

  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"><u>F</u>iltrar (Ativo)</font>' : '<u>F</u>iltrar (Inativo)').'</a>');
    $i    = 0;
    if (count($RS)==0) {
      ShowHTML('<tr><td align="center"><hr/><b>N�o foram encontrados registros</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        if ($i==0) {
          if (nvl($p_pais,0)!=f($row,'sq_tipo_material')) $tipo = true;
          ShowHTML('<tr><td align="center">');
          ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
          ShowHTML('            <td NOWRAP><font size="2"><U ID="INICIO" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da rela��o"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
          ShowHTML('                                      <U CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da rela��o"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');
          ShowHTML('          <td><b>Material</b></td>');
          ShowHTML('          <td><b>U.M.</b></td>');
          ShowHTML('          <td><b>Qtd.</b></td>');
          ShowHTML('          <td><b>Pre�o m�dio</b></td>');
          ShowHTML('          <td><b>Estoque M�nimo</b></td>');
          ShowHTML('          <td><b>C.M.M.</b></td>');
          ShowHTML('          <td><b>Ciclo de compra</b></td>');
          ShowHTML('          <td><b>Ponto de Ressuprimento</b></td>');
          ShowHTML('          <td><b>Dispon�vel</b></td>');
          ShowHTML('          <td><b>Autoriza��o Chefia</b></td>');
          ShowHTML('        </tr>');
          AbreForm('Form',$w_dir.$w_pagina.'grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$w_pagina.$par,'L');
          ShowHTML('<INPUT type="hidden" name="w_sq_estoque[]" value="">');
          ShowHTML('<INPUT type="hidden" name="w_minimo[]" value="">');
          ShowHTML('<INPUT type="hidden" name="w_consumo[]" value="">');
          ShowHTML('<INPUT type="hidden" name="w_ciclo[]" value="">');
          ShowHTML('<INPUT type="hidden" name="w_ponto[]" value="">');
          ShowHTML('<INPUT type="hidden" name="w_disponivel[]" value="">');
          ShowHTML('<INPUT type="hidden" name="w_chefe_autoriza[]" value="">');
          ShowHTML(montaFiltro('POST'));
        }
        $i++;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('          <td align="center" width="1%" nowrap><input type="checkbox" name="w_sq_estoque[]" value="'.f($row,'sq_estoque').'" onClick="marca('.$i.')">');
        ShowHTML('          <td>'.(($w_embed=='WORD') ? f($row,'nm_material') : ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nm_material'),f($row,'sq_material'),$TP,null)).'</td>');
        ShowHTML('          <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('          <td align="center">'.formatNumber(f($row,'saldo_atual'),0).'</td>');
        ShowHTML('          <td align="right">'.formatNumber(f($row,'preco_medio'),5).'</td>');
        ShowHTML('          <td align="center"><input disabled type="text" name="w_minimo[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.f($row,'estoque_minimo').'" style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);" title="Informe o estoque m�nimo do material."></td>');
        ShowHTML('          <td align="center"><input disabled type="text" name="w_consumo[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.f($row,'consumo_medio_mensal').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o consumo m�dio mensal."></td>');
        ShowHTML('          <td align="center"><input disabled type="text" name="w_ciclo[]" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.f($row,'ciclo_compra').'" style="text-align:right;" onKeyDown="FormataValor(this,4,0,event);" title="Informe o ciclo de compra do material."></td>');
        ShowHTML('          <td align="center"><input disabled type="text" name="w_ponto[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.f($row,'ponto_ressuprimento').'" style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);" title="Informe o ponto de ressuprimento do material."></td>');
        ShowHTML('          <td align="center"><select disabled name="w_disponivel[]" class="sts"><option value="S" '.((f($row,'disponivel')=='S') ? 'SELECTED' : '').' />Sim<option value="N" '.((f($row,'disponivel')=='N') ? 'SELECTED' : '').' />N�o</selected></td>');
        ShowHTML('          <td align="center"><select disabled name="w_chefe_autoriza[]" class="sts"><option value="S" '.((f($row,'chefe_autoriza')=='S') ? 'SELECTED' : '').' />Sim<option value="N" '.((f($row,'chefe_autoriza')=='N') ? 'SELECTED' : '').' />N�o</selected></td>');
        ShowHTML('        </tr>');
      }
      ShowHTML('</table>');
      ShowHTML('  </td>');
      ShowHTML('<tr><td colspan="3"><hr style="margin:0px;" NOSHADE color=#000000 size=1 /></td></tr>');
      ShowHTML('<tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
      ShowHTML('<tr><td colspan="3"><dl>');
      ShowHTML('<dt><b>U.M.</b><dd>Unidade de medida');
      ShowHTML('<dt><b>Qtd.</b><dd>Quantidade dispon�vel em estoque');
      ShowHTML('<dt><b>Estoque m�nimo</b><dd>Quantidade m�nima do item para que o trabalho da organiza��o n�o seja prejudicado');
      ShowHTML('<dt><b>C.M.M.</b><dd>Consumo m�dio mensal');
      ShowHTML('<dt><b>Ciclo de compra</b><dd>N�mero de dias corridos desde a autoriza��o da compra at� a chegada para armazenamento');
      ShowHTML('<dt><b>Ponto de ressuprimento</b><dd>Quantidade de unidades existentes em estoque que, quando atingida, � feito um novo pedido de compra ou fornecimento');
      ShowHTML('<dt><b>Dispon�vel</b><dd>Indica se o item est� dispon�vel para ser solicitado');
      ShowHTML('<dt><b>Autoriza��o chefia</b><dd>A solicita��o que contiver o item deve ser autorizada pela chefia imediata da unidade solicitante antes de ser encaminhada ao almoxarifado');
      ShowHTML('</dl></td></tr>');
      ShowHTML('</form>');
    }
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Exibe par�metros de apresenta��o
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Crit�rios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr>');
    SelecaoAlmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado onde o material ser� armazenado.', &$p_chave,'p_chave',null,'onChange="document.Form.O.value=\'P\'; document.Form.w_troca.value=\'p_pais\'; document.Form.submit();"',2);
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material:','S','Selecione o grupo/subgrupo de material/servi�o desejado.',$p_chave,$p_pais,'p_pais','ALMOXARIFADO',null,2);
    ShowHTML('      </tr>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    SelecaoClasseCheck('Recuperar classes:','S',null,$p_fase,$P2,'p_fase[]','CONSUMO',null);
    ShowHTML('   <tr valign="top">');
    MontaRadioNS('<b>Apenas dispon�veis para pedidos internos?</b>',$p_ativo,'p_ativo');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('        </td>');
    ShowHTML('    </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 

  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  Cabecalho();
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');

 if (strpos($SG,'MTSIT')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida

    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (strpos('IA',$O)!==false) {
        
        $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, $_REQUEST['w_nome'],null);
        if (count($RS)>0) {        
          foreach($RS as $row) { $RS = $row; break; }

          if (f($RS,'chave')!=nvl($_REQUEST['w_chave'],0)) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Situa��o j� cadastrada!");');
            ScriptClose();
            retornaFormulario('w_nome');
            exit;
          }
        }
        $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null , $_REQUEST['w_sigla']);
        if (count($RS)>0) {
          foreach($RS as $row) { $RS = $row; break; }
          if (f($RS,'chave')!=nvl($_REQUEST['w_chave'],0)) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Sigla j� cadastrada!");');
            ScriptClose();
            retornaFormulario('w_sigla');
            exit;
          }
        } 
      }
      $SQL = new dml_putMtSituacao; $SQL->getInstanceOf($dbms, $O, $w_cliente,
        $_REQUEST['w_chave'],
        $_REQUEST['w_nome'],
        $_REQUEST['w_sigla'],
        $_REQUEST['w_entrada'],
        $_REQUEST['w_saida'],
        $_REQUEST['w_estorno'],
        $_REQUEST['w_consumo'],
        $_REQUEST['w_permanente'],
        $_REQUEST['w_inativa_bem'],
        $_REQUEST['w_situacao_fisica'],
        $_REQUEST['w_ativo']
      );
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletr�nica inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'MTTIPMOV')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (strpos('IA',$O)!==false) {
          $sql = new db_getTipoMovimentacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['$p_chave'],$_REQUEST['w_nome'],null,null,null,null,null,null,null,null);
          if (count($RS)>0) {        
            foreach($RS as $row) { $RS = $row; break; }
            
            if (f($RS,'chave')!=nvl($_REQUEST['w_chave'],0)) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Movimenta��o j� cadastrada!");');
              ScriptClose();
              retornaFormulario('w_nome');
              exit;
            }
          }
      }
      $SQL = new dml_putTipoMovimentacao; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_nome'],$_REQUEST['w_entrada'],
      $_REQUEST['w_saida'],$_REQUEST['w_orcamentario'],$_REQUEST['w_consumo'],$_REQUEST['w_permanente'],$_REQUEST['w_inativa_bem'],$_REQUEST['w_ativo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
      exit;
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletr�nica inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif (strpos($SG,'MTAJUSTE')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_putMtAjuste; 
      //Grava os novos itens
      for ($i=0; $i<=count($_POST['w_sq_estoque'])-1; $i=$i+1) {
        if ($_REQUEST['w_sq_estoque'][$i]>'') {
          $SQL->getInstanceOf($dbms,'A',$w_cliente,$w_usuario,$_REQUEST['w_sq_estoque'][$i],$_REQUEST['w_minimo'][$i],
                $_REQUEST['w_consumo'][$i],$_REQUEST['w_ciclo'][$i],$_REQUEST['w_ponto'][$i],$_REQUEST['w_disponivel'][$i],
                $_REQUEST['w_chefe_autoriza'][$i]);
        }
      } 

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }     
  } elseif (strpos($SG,'MTALM')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {
          $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,$_REQUEST['w_nome'],null,null,'OUTROS');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Nome de almoxarifado j� cadastrado!");');
            ScriptClose();
            retornaFormulario('w_nome');
            exit;
          }
      }
      $SQL = new dml_putAlmoxarifado; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_localizacao'],$_REQUEST['w_chave'],
      $_REQUEST['w_nome'],$_REQUEST['w_ativo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
      exit;
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletr�nica inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif (strpos($SG,'PDLOCAIS')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {

        $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],null,$_REQUEST['w_nome'],null,null,$_REQUEST['w_chave_pai']);
        if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("Local j� cadastrado!");');
          ScriptClose();
          retornaFormulario('w_nome');
          exit;
        } 
      } 
      $SQL = new dml_putAlmoxarifadoLocal; $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $_REQUEST['w_nome'], $_REQUEST['w_chave_aux'], $_REQUEST['w_chave_pai'], $_REQUEST['w_ativo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletr�nica inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("Bloco de dados n�o encontrado: '.$SG.'");');
    ShowHTML('  history.back(1);');
    ScriptClose();
  }
  Rodape();
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'SITUACAO'      : Situacao();          break;
  case 'AJUSTE'        : Ajuste();            break;
  case 'MTTIPMOV'      : TipoMovimentacao();  break;
  case 'ALMOX'         : Almoxarifado();      break;
  case 'LOCAIS'        : Locais();            break;
  case 'GRAVA'         : Grava();             break;
  default:
    Cabecalho();
    head();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 
} 
?>
