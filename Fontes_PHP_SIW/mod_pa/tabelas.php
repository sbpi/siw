<?php
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
include_once($w_dir_volta.'classes/sp/db_getProtocolo.php');
include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCaixa.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoDespacho_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putEspecieDocumento_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putUnidade_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putNaturezaDoc_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoGuarda_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putPAParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putAssunto_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putRenumeraProtocolo.php');
include_once($w_dir_volta.'classes/sp/db_getArquivo_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putArquivo_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putArquivoLocal_PA.php');
include_once($w_dir_volta.'classes/sp/dml_putCaixa.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoArqCen.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDespacho.php');
include_once($w_dir_volta.'funcoes/selecaoTipoGuarda.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');
include_once($w_dir_volta.'funcoes/selecaoAssuntoRadio.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoCaixa.php');
include_once($w_dir_volta.'funcoes/selecaoCaixaCheck.php');
include_once($w_dir_volta.'funcoes/selecaoArquivoLocalSubordination.php');

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

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par = upper($_REQUEST['par']);
$P1 = nvl($_REQUEST['P1'], 0);
$P2 = nvl($_REQUEST['P2'], 0);
$P3 = nvl($_REQUEST['P3'], 1);
$P4 = nvl($_REQUEST['P4'], $conPageSize);
$TP = $_REQUEST['TP'];
$SG = upper($_REQUEST['SG']);
$R = $_REQUEST['R'];
$O = upper($_REQUEST['O']);
$w_assinatura = upper($_REQUEST['w_assinatura']);
$w_pagina = 'tabelas.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_pa/';
$w_troca = $_REQUEST['w_troca'];
$p_ordena = $_REQUEST['p_ordena'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos('PACAIXA,ALTLOCAL', nvl($SG,'.')) !== false) {
  if ($O == '') {
    $O = 'P';
  }
} elseif ($O == '')
  $O = 'L';


switch ($O) {
  case 'I': $w_TP = $TP.' - Inclusão';
    break;
  case 'A': $w_TP = $TP.' - Alteração';
    break;
  case 'E': $w_TP = $TP.' - Exclusão';
    break;
  case 'P': $w_TP = $TP.' - Filtragem';
    break;
  case 'C': $w_TP = $TP.' - Cópia';
    break;
  case 'V': $w_TP = $TP.' - Envio';
    break;
  case 'M': $w_TP = $TP.' - Serviços';
    break;
  case 'H': $w_TP = $TP.' - Herança';
    break;
  case 'T': $w_TP = $TP.' - Ativar';
    break;
  case 'D': $w_TP = $TP.' - Desativar';
    break;
  default: $w_TP = $TP.' - Listagem';
    break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);

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
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b>Operação não permitida!</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exit();
  }
}

// Recupera os parâmetros do módulo
$sql = new db_getParametro;
$RS_Parametro = $sql->getInstanceOf($dbms, $w_cliente, 'PA', null);
foreach ($RS_Parametro as $row) { $RS_Parametro = $row; break; }

$w_ano = RetornaAno();
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Manter Tabela básica "Tipo de despacho"
// -------------------------------------------------------------------------
function TipoDespacho() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_chave = $_REQUEST['w_chave'];
    $w_nome = $_REQUEST['w_nome'];
    $w_sigla = $_REQUEST['w_sigla'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_ativo = $_REQUEST['w_ativo'];
    $w_original = $_REQUEST['w_original'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getTipoDespacho_PA;
    $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, null, null);
    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'nome', 'asc');
    } else {
      $RS = SortArray($RS, 'nome', 'asc');
    }
  } elseif (!(strpos('AEV', $O) === false)) {
    // Recupera os dados chave informada
    $sql = new db_getTipoDespacho_PA;
    $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null);
    foreach ($RS as $row) { $RS = $row; break; }
    $w_chave = f($RS, 'chave');
    $w_nome = f($RS, 'nome');
    $w_sigla = f($RS, 'sigla');
    $w_descricao = f($RS, 'descricao');
    $w_ativo = f($RS, 'ativo');
    $w_original = f($RS, 'despacho_original');
  }
  Cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA', $O) === false)) {
      Validate('w_nome', 'Nome', '1', '1', '4', '60', '1', '1');
      Validate('w_sigla', 'Sigla', '1', '1', '2', '10', '1', '1');
      Validate('w_descricao', 'Descrição', '1', '1', '4', '255', '1', '1');
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O == 'E') {
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA', $O) === false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O == 'E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Nome', 'nome').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Sigla', 'sigla').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Descricao', 'descricao').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Envia no trâmite original', 'nm_despacho_original').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Ativo', 'nm_ativo').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Operações</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row, 'nome').'</td>');
        ShowHTML('        <td>'.f($row, 'sigla').'</td>');
        ShowHTML('        <td>'.f($row, 'descricao').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_despacho_original').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">EX</A>&nbsp');
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
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false))
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML(montaFiltro('POST'));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      <tr><td><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=5 cols=80 title="Detalhe o tipo de despacho." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Permite envio no trâmite original?</b>', $w_original, 'w_original');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>', $w_ativo, 'w_ativo');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

function imprimir() {
  extract($GLOBALS);
  $w_chave = $_REQUEST['w_chave'];

  include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
  $sql = new db_getCustomerData;
  $RS_Logo = $sql->getInstanceOf($dbms, $w_cliente);

  if (f($RS_Logo, 'logo') > '') {
    $p_logo = '/img/'.f($RS_Logo,'logo');
  }

  $sql = new db_getCaixa;
  $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, $w_usuario,null, null, null, null, null, null, null, null, null,null,null,null,null);
  foreach ($RS as $row) {
    $RS = $row;
    break;
  }
  $w_unidade = f($RS, 'sq_unidade');
  $w_assunto = f($RS, 'assunto');
  $w_descricao = f($RS, 'descricao');
  $w_destinacao_final = f($RS, 'destinacao_final');
  $w_intermediario = f($RS, 'intermediario');
  $w_data_limite = formataDataEdicao(f($RS, 'data_limite'));
  $w_nome_unidade = f($RS, 'unidade');
  $w_numero = str_pad((int) f($RS, 'numero'), 2, "0", STR_PAD_LEFT);
  $w_sigla = f($RS, 'sg_unidade');
  HeaderWord();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('<table border=0 width=45%"><tr><td style="border: solid 7px #000" align="center">');
  ShowHTML('<IMG ALIGN="center" SRC="'.$conFileVirtual.$w_cliente.$p_logo.'"><p>');
  ShowHTML('<table border=1 cellspacing=0 bordercolor=black  style="width:400px;"  >');
  ShowHTML('  <tr>');
  ShowHTML('    <td  align="right" width="30%">');
  ShowHTML('      Setor:');
  ShowHTML('    <td  style="font-size:18px">');
  ShowHTML($w_sigla);
  ShowHTML('    </td>');
  ShowHTML('  </tr>');

  ShowHTML('  <tr>');
  ShowHTML('    <td align="right">');
  ShowHTML('      Código e assunto:');
  ShowHTML('    <td style="font-size:10px" valign=top>');
  ShowHTML($w_assunto);
  ShowHTML('  </tr>');

  ShowHTML('  <tr>');
  ShowHTML('    <td align="right">');
  ShowHTML('      Espécies documentais:');
  ShowHTML('    <td  style="font-size:10px" valign=top>');
  ShowHTML($w_descricao);

  ShowHTML('  </tr>');

  ShowHTML('  <tr>');
  ShowHTML('    <td align="right">');
  ShowHTML('      Data-Limite:');
  ShowHTML('    <td  style="font-size:20px">'.nvl($w_data_limite, '&nbsp;'));
  ShowHTML('  </tr>');

  ShowHTML('</table>');

  ShowHTML('<p>&nbsp;</p>');
  ShowHTML('<span style="font-size:80px"><strong>'.$w_numero.'</strong></span>');
  ShowHTML('<p>&nbsp;</p>');
  ShowHTML('<p>&nbsp;</p>');


  ShowHTML('<table border=1 cellspacing=0 bordercolor=black style="border:1px; width:400px;">');
  ShowHTML('  <tr>');
  ShowHTML('    <td align="center">');
  ShowHTML('      <b>INTERMEDIÁRIO</b>');
  ShowHTML('    </td>');
  ShowHTML('    <td align="center">');
  ShowHTML('<b>DESTINAÇÃO FINAL</b>');
  ShowHTML('    </td>');
  ShowHTML('  </tr>');
  ShowHTML('  <tr>');
  ShowHTML('    <td align="center">');
  if (nvl($w_intermediario, '') != '')
    ShowHTML($w_intermediario); else
    ShowHTML('&nbsp;');
  ShowHTML('    </td>');
  ShowHTML('    <td align="center">'.nvl($w_destinacao_final, '&nbsp;'));
  ShowHTML('    </td>');
  ShowHTML('  </tr>');
  ShowHTML('</table>');

  ShowHTML('</table><p>&nbsp;</p>');
  ShowHTML('</table>');
}

// =========================================================================
// Manter Tabela básica 'Especies do documentos'
// -------------------------------------------------------------------------
function Caixa() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave    = $_REQUEST['w_chave'];
  $p_unidade  = $_REQUEST['p_unidade'];
  $p_caixa    = $_REQUEST['p_caixa'];
  $p_ini      = $_REQUEST['p_ini'];
  $p_fim      = $_REQUEST['p_fim'];
  $p_local    = $_REQUEST['p_local'];
  $p_fase     = explodeArray($_REQUEST['p_fase']);
  $p_central  = (strpos($p_fase,'C')!==false) ? 'C' : '';
  $p_transito = (strpos($p_fase,'T')!==false) ? 'T' : '';
  $p_setorial = (strpos($p_fase,'S')!==false) ? 'S' : '';

  // Recupera os parâmetros do módulo
  $sql = new db_getParametro;
  $RS = $sql->getInstanceOf($dbms, $w_cliente, 'PA', null);
  foreach ($RS as $row) {
    $RS = $row;
  }
  if ($_SESSION['LOTACAO'] == f($RS, 'arquivo_central') || RetornaModMaster($w_cliente, $w_usuario, $w_menu) == 'S') {
    $w_gestor = true;
  } else {
    $w_gestor = false;
  }

  if ($w_troca > '' && $O != 'E') {

    // Se for recarga da página
    $w_chave            = $_REQUEST['w_chave'];
    $w_assunto          = $_REQUEST['w_assunto'];
    $w_unidade          = $_REQUEST['w_unidade'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_destinacao_final = $_REQUEST['w_destinacao_final'];
    $w_intermediario    = $_REQUEST['w_intermediario'];
    $w_data_limite      = $_REQUEST['w_data_limite'];
    $w_nome_unidade     = $_REQUEST['w_nome_unidade'];
    $w_numero           = $_REQUEST['w_numero'];
    $w_caixa            = $_REQUEST['w_caixa'];
    $w_ini              = $_REQUEST['w_ini'];
    $w_fim              = $_REQUEST['w_fim'];
    
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCaixa;
    $RS = $sql->getInstanceOf($dbms, $p_caixa, $w_cliente, $w_usuario,$p_unidade, null, null, null, null, null, null, null, $p_local, $p_central, $p_transito, $p_setorial,$SG);
    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'nm_unidade', 'asc', 'numero', 'asc');
    } else {
      $RS = SortArray($RS, 'nm_unidade', 'asc', 'numero', 'asc');
    }
  } elseif (!(strpos('AEV', $O) === false)) {
    // Recupera os dados do endereço informado
    $sql = new db_getCaixa;
    $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, $w_usuario,null, null, null, null, null, null, $p_ini, $p_fim, null,null,null,null,null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }

    $w_cliente = f($RS, 'cliente');
    $w_unidade = f($RS, 'sq_unidade');
    $w_assunto = f($RS, 'assunto');
    $w_descricao = f($RS, 'descricao');
    $w_destinacao_final = f($RS, 'destinacao_final');
    $w_intermediario = f($RS, 'intermediario');
    $w_data_limite = formataDataEdicao(f($RS, 'data_limite'));
    $w_nome_unidade = f($RS, 'nm_unidade');
    $w_numero = f($RS, 'numero').'/'.f($RS, 'sg_unidade');
  }
  Cabecalho();
  head();
  if ((strpos('IAEP', $O)!==false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IA', $O) === false)) {
      Validate('w_assunto', 'Assunto', '1', '1', '4', '750', '1', '1');
      Validate('w_descricao', 'Descrição', '1', '1', '4', '2000', '1', '1');
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O == 'E') {
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O == 'P') {
      Validate('p_unidade', 'Unidade', 'SELECT', '', '1', '18', '', '1');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["p_fase[]"].length; i++) {');
      ShowHTML('    if (theForm["p_fase[]"][i].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve informar pelo menos uma fase!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');      
    }
    ShowHTML('  theForm.Botao.disabled=true;');
    //ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O == 'I') {
    BodyOpen('onLoad=\'document.Form.w_unidade.focus()\';');
  } elseif ($O == 'A') {
    BodyOpen('onLoad=\'document.Form.w_assunto.focus()\';');
  } elseif ($O == 'E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Número', 'numero').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Unidade', 'nm_unidade').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Data Limite', 'data_limite').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Prazo Guarda', 'intermediario').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Destinação Final', 'destinacao_final').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Itens', 'qtd').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Localização', 'nm_localizacao').'</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row, 'numero').'</td>');
        ShowHTML('        <td>'.f($row, 'nm_unidade').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row, 'data_limite'), 5).'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row, 'intermediario'), 5).'</td>');
        ShowHTML('        <td>'.f($row, 'destinacao_final').'</td>');
        ShowHTML('        <td align="right">'.f($row, 'qtd').'&nbsp;</td>');
        ShowHTML('        <td>'.f($row, 'nm_localizacao').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        if ($w_gestor) {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'sq_caixa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row, 'sq_caixa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">EX</A>&nbsp');
        }
        ShowHTML('          <A href="'.montaURL_JS($w_dir, $w_pagina.'IMPRIMIR'.'&R='.$w_pagina.'IMPRIMIR'.'&O=V&w_chave='.f($row, 'sq_caixa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'" class="HL"  title="Imprime o espelho da caixa.">ES</A>&nbsp');
        ShowHTML('          <A onclick="window.open (\''.montaURL_JS($w_dir, 'relatorio.php?par=ConteudoCaixa'.'&R='.$w_pagina.'IMPRIMIR'.'&O=L&w_chave='.f($row, 'sq_caixa').'&w_formato=HTML&orientacao=PORTRAIT&&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">LS</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false)) {
      $w_Disabled = ' DISABLED ';
    }
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<input type="hidden" name="w_data_limite" value="'.$w_data_limite.'">');
    ShowHTML('<input type="hidden" name="w_intermediario" value="'.$w_intermediario.'">');
    ShowHTML('<input type="hidden" name="w_destinacao_final" value="'.$w_destinacao_final.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    if ((strpos('AE', $O) === false)) {
      selecaoUnidade('<U>U</U>nidade:', 'U', 'Selecione a unidade e aguarde a recarga da página para selecionar sua localização.', $w_unidade, $w_usuario, 'w_unidade', 'CADPA', null, 3);
    } else {
      ShowHTML('          <td colspan=3>Unidade:<br/><b> '.$w_nome_unidade.'<b>');
      ShowHTML('        <tr><td colspan=3>Número:<br><b> '.$w_numero.'</b><p>');
      ShowHTML('        <input type="hidden" name="w_nome_unidade" value="'.$w_nome_unidade.'">');
      ShowHTML('        <input type="hidden" name="w_numero" value="'.$w_numero.'">');
    }
    ShowHTML('        <tr><td colspan=3><b>A<u>s</u>sunto:</b><br><textarea '.$w_Disabled.' accesskey="S"  name="w_assunto" rows=5 cols=80  class="sti">'.$w_assunto.'</textarea></td>');
    ShowHTML('        <tr><td colspan=3><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D"  name="w_descricao" rows=5 cols=80  class="sti">'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG. montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form', $w_dir.$w_pagina.$par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    SelecaoCaixa('<u>C</u>aixa:', 'C', "Selecione a caixa transferida.", $p_caixa, $w_cliente, null, 'p_caixa', $SG, null);
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade original da caixa:', 'U', 'Selecione a unidade que transferiu a caixa.', $p_unidade, $w_usuario, 'p_unidade', 'CADPA', null);
    ShowHTML('      <tr valign="top">');
    selecaoArquivoLocalSubordination('<u>L</u>ocalização', 'L', 'Informe a localização da caixa no arquivo central.', $p_local, f($RS_Parametro, 'arquivo_central'), 'p_local',null,null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Perío<u>d</u>o entre:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    selecaoCaixaCheck('<u>S</u>ituação', 'S', 'Selecione as situações que deseja recuperar.', $p_fase, null , 'p_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="3"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Altera localização de caixas
// -------------------------------------------------------------------------
function alteraLocal() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave    = $_REQUEST['w_chave'];
  $p_unidade  = $_REQUEST['p_unidade'];
  $p_caixa    = $_REQUEST['p_caixa'];
  $p_ini      = $_REQUEST['p_ini'];
  $p_fim      = $_REQUEST['p_fim'];
  $p_local    = $_REQUEST['p_local'];
  $p_fase     = explodeArray($_REQUEST['p_fase']);
  $p_central  = (strpos($p_fase,'C')!==false) ? 'S' : '';
  $p_transito = (strpos($p_fase,'T')!==false) ? 'S' : '';
  $p_setorial = (strpos($p_fase,'S')!==false) ? 'S' : '';


  if ($_SESSION['LOTACAO'] == f($RS_Parametro, 'arquivo_central') || RetornaModMaster($w_cliente, $w_usuario, $w_menu) == 'S') {
    $w_gestor = true;
  } else {
    $w_gestor = false;
  }

  if ($w_troca > '' && $O != 'E') {

    // Se for recarga da página
    $w_chave            = $_REQUEST['w_chave'];
    $w_local            = $_REQUEST['w_local'];
    
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCaixa;
    $RS = $sql->getInstanceOf($dbms, $p_caixa, $w_cliente, $w_usuario,$p_unidade, null, null, null, null, null, null, null, $p_local, $p_central, $p_transito, $p_setorial,$SG);
    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'nm_unidade', 'asc', 'numero', 'asc');
    } else {
      $RS = SortArray($RS, 'nm_unidade', 'asc', 'numero', 'asc');
    }
  }
  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if ($O=='L') {
    ShowHTML('  for (ind=1; ind < theForm["w_local[]"].length; ind++) {');
    Validate('["w_local[]"][ind]','Localização','SELECT','1','1','18','','1');
    ShowHTML('  }');
    Validate('w_observacao', 'Observação', '1', '', '4', '2000', '1', '1');
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
  } elseif ($O == 'P') {
    Validate('p_unidade', 'Unidade', 'SELECT', '', '1', '18', '', '1');
  }
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Número', 'numero').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Unidade', 'sg_unidade').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Data Limite', 'data_limite').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Itens', 'qtd').'</td>');
    ShowHTML('          <td class="remover"><b>Localização</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina.$par, $O);
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML(montaFiltro('POST'));
      ShowHTML('<input type="hidden" name="w_chave[]" value="">');
      ShowHTML('<input type="hidden" name="w_local[]" value="">');
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('<input type="hidden" name="w_chave[]" value="'.f($row,'sq_caixa').'">');
        ShowHTML('        <td align="center">'.f($row, 'numero').'</td>');
        ShowHTML('        <td>'.f($row, 'sg_unidade').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row, 'data_limite'), 5).'</td>');
        ShowHTML('        <td align="right">'.f($row, 'qtd').'&nbsp;</td>');
        selecaoArquivoLocalSubordination(null, null, 'Informe a localização da caixa no arquivo central.', f($row,'sq_arquivo_local'), f($RS_Parametro, 'arquivo_central'), 'w_local[]', 'FOLHA', null, null, null, null, null, null);
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
      ShowHTML('    </table>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="3">');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('        <tr><td colspan=3><b><u>O</u>bservação:</b><br><textarea '.$w_Disabled.' accesskey="O"  name="w_observacao" rows=5 cols=80  class="sti">'.$w_observacao.'</textarea></td>');
      ShowHTML('      <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('      <tr><td colspan=3 align="center"><hr>');
      ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      ShowHTML('</FORM>');
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form', $w_dir.$w_pagina.$par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    SelecaoCaixa('<u>C</u>aixa:', 'C', "Selecione a caixa transferida.", $p_caixa, $w_cliente, null, 'p_caixa', $SG, null);
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade original da caixa:', 'U', 'Selecione a unidade que transferiu a caixa.', $p_unidade, $w_usuario, 'p_unidade', 'CADPA', null);
    ShowHTML('      <tr valign="top">');
    selecaoArquivoLocalSubordination('<u>L</u>ocalização', 'L', 'Informe a localização da caixa no arquivo central.', $p_local, f($RS_Parametro, 'arquivo_central'), 'p_local',null,null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Perío<u>d</u>o entre:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('      <tr><td align="center" colspan="3"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
  $w_chave = $_REQUEST['w_chave'];
  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_chave = $_REQUEST['w_chave'];
    $w_nome = $_REQUEST['w_nome'];
    $w_sigla = $_REQUEST['w_sigla'];
    $w_ativo = $_REQUEST['w_ativo'];
    $w_nm_assunto = $_REQUEST['w_nm_assunto'];
    $w_assunto = $_REQUEST['w_assunto'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getEspecieDocumento_PA;
    $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, null, null);
    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'nome', 'asc');
    } else {
      $RS = SortArray($RS, 'nome', 'asc');
    }
  } elseif (!(strpos('AEV', $O) === false)) {
    // Recupera os dados do endereço informado
    $sql = new db_getEspecieDocumento_PA;
    $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_chave = f($RS, 'chave');
    $w_cliente = f($RS, 'cliente');
    $w_nome = f($RS, 'nome');
    $w_sigla = f($RS, 'sigla');
    $w_ativo = f($RS, 'ativo');
    $w_assunto = f($RS, 'sq_assunto');
  }
  Cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA', $O) === false)) {
      Validate('w_nome', 'Nome', '1', '1', '4', '30', '1', '1');
      Validate('w_sigla', 'Sigla', '1', '1', '1', '10', '1', '1');
      Validate('w_assunto', 'Classificação', 'HIDDEN', '', 1, 18, '', '0123456789');
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O == 'E') {
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA', $O) === false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O == 'E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('    <td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Nome', 'nome').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Sigla', 'sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Assunto', 'cd_assunto').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Ativo', 'nm_ativo').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Operações</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row, 'nome').'</td>');
        ShowHTML('        <td>'.f($row, 'sigla').'</td>');
        ShowHTML('        <td width="50" title="'.f($row, 'ds_assunto').'">&nbsp;'.ExibeAssunto('../', $w_cliente, f($row, 'cd_assunto'), f($row, 'sq_assunto'), $TP).'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">EX</A>&nbsp');
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
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false)) {
      $w_Disabled = ' DISABLED ';
    }
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('           <td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('        <tr valign="top">');
    SelecaoAssuntoRadio('A<u>s</u>sunto vinculado à espécie: (usado para classificação automática de protocolos)', 'L', 'Clique na lupa para selecionar a classificação vinculada à espécie documental. Se indicado o assunto, documentos criados nesta espécie serão automaticamente vinculados ao assunto.', $w_assunto, null, 'w_assunto', 'FOLHA', null);
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>', $w_ativo, 'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG. montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
  $w_chave = $_REQUEST['w_chave'];

  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_unidade_pai = $_REQUEST['w_unidade_pai'];
    $w_nome = $_REQUEST['w_nome'];
    $w_sigla = $_REQUEST['w_sigla'];
    $w_registra_documento = $_REQUEST['w_registra_documento'];
    $w_autua_processo = $_REQUEST['w_autua_processo'];
    $w_prefixo = $_REQUEST['w_prefixo'];
    $w_nr_documento = $_REQUEST['w_nr_documento'];
    $w_nr_tramite = $_REQUEST['w_nr_tramite'];
    $w_nr_transferencia = $_REQUEST['w_nr_transferencia'];
    $w_nr_eliminacao = $_REQUEST['w_nr_eliminacao'];
    $w_arquivo_setorial = $_REQUEST['w_arquivo_setorial'];
    $w_ativo = $_REQUEST['w_ativo'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getUnidade_PA;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null, null);
    $RS = SortArray($RS, 'ordena', 'asc');
  } elseif (!(strpos('AEV', $O) === false)) {
    // Recupera os dados do endereço informado
    $sql = new db_getUnidade_PA;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_unidade_pai = f($RS, 'sq_unidade_pai');
    $w_nome = f($RS, 'nome');
    $w_sigla = f($RS, 'sigla');
    $w_registra_documento = f($RS, 'registra_documento');
    $w_autua_processo = f($RS, 'autua_processo');
    $w_prefixo = f($RS, 'prefixo');
    $w_nr_documento = f($RS, 'numero_documento');
    $w_nr_tramite = f($RS, 'numero_tramite');
    $w_nr_transferencia = f($RS, 'numero_transferencia');
    $w_nr_eliminacao = f($RS, 'numero_eliminacao');
    $w_arquivo_setorial = f($RS, 'arquivo_setorial');
    $w_ativo = f($RS, 'ativo');
  }
  Cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    FormataCNPJ();
    ValidateOpen('Validacao');
    if (strpos('IA', $O) !== false) {
      if ($O == 'I') {
        Validate('w_chave', 'Unidade', 'SELECT', '1', '1', '18', '', '1');
      }
      ShowHTML('  if (theForm.w_chave.value==theForm.w_unidade_pai[theForm.w_unidade_pai.selectedIndex].value) {');
      ShowHTML('     alert("Não é permitido subordinar uma unidade a si mesma!"); ');
      ShowHTML('     theForm.w_unidade_pai.focus(); ');
      ShowHTML('     return false; ');
      ShowHTML('  }; ');
      if (nvl($w_unidade_pai, '') == '') {
        Validate('w_prefixo', 'Prefixo', '1', '1', '5', '5', '1', '1');
        Validate('w_nr_documento', 'Número de criação do documento', '1', '1', '1', '10', '', '1');
        Validate('w_nr_tramite', 'Número da guia de remessa', '1', '', '1', '5', '', '1');
        Validate('w_nr_transferencia', 'Número da guia de transferência', '1', '', '1', '5', '', '1');
        Validate('w_nr_eliminacao', 'Número da guia de eliminação', '1', '', '1', '5', '', '1');
      }
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O == 'E') {
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('I', $O) !== false) {
    BodyOpen('onLoad=\'document.Form.w_chave.focus()\';');
  } elseif (strpos('A', $O) !== false) {
    BodyOpen('onLoad=\'document.Form.w_unidade_pai.focus()\';');
  } elseif ($O == 'E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpenClean(null);
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td colspan=2 rowspan=2><b>Unidade</td>');
    ShowHTML('          <td colspan=5><b>Numeração automática</td>');
    ShowHTML('          <td rowspan=2><b>Registra<br>documentos</td>');
    ShowHTML('          <td rowspan=2><b>Autua<br>processos</td>');
    ShowHTML('          <td rowspan=2><b>Arquivo<br>setorial</td>');
    ShowHTML('          <td rowspan=2><b>Ativo</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover" rowspan=2><b>Operações</td>');
    }
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Prefixo</td>');
    ShowHTML('          <td><b>Doc.</td>');
    ShowHTML('          <td><b>Remessa</td>');
    ShowHTML('          <td><b>Transf.</td>');
    ShowHTML('          <td><b>Elim.</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=12 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if (nvl(f($row, 'sq_unidade_pai'), '') == '') {
          ShowHTML('        <td colspan=2>'.f($row, 'nome').' ('.f($row, 'sigla').')</td>');
          ShowHTML('        <td align="center">'.f($row, 'prefixo').'</td>');
          ShowHTML('        <td align="right">'.f($row, 'numero_documento').'</td>');
          ShowHTML('        <td align="right">'.f($row, 'numero_tramite').'</td>');
          ShowHTML('        <td align="right">'.f($row, 'numero_transferencia').'</td>');
          ShowHTML('        <td align="right">'.f($row, 'numero_eliminacao').'</td>');
        } else {
          ShowHTML('        <td width="1%">&rarr;<td>'.f($row, 'nome').' ('.f($row, 'sigla').')</td>');
          ShowHTML('        <td align="center">"</td>');
          ShowHTML('        <td align="right">"</td>');
          ShowHTML('        <td align="right">"</td>');
          ShowHTML('        <td align="right">"</td>');
          ShowHTML('        <td align="right">"</td>');
        }
        ShowHTML('        <td align="center">'.retornaSimNao(f($row, 'registra_documento'), 'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row, 'autua_processo'), 'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row, 'arquivo_setorial'), 'IMAGEM').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">EX</A>&nbsp');
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
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false)) {
      $w_Disabled = ' DISABLED ';
    }
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_nome" value="'.$w_nome.'">');
    ShowHTML('<INPUT type="hidden" name="w_sigla" value="'.$w_sigla.'">');
    if ($O != 'I') {
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=3><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    if ($O == 'I') {
      SelecaoUnidade('<U>U</U>nidade:', 'U', null, $w_chave, null, 'w_chave', null, null);
    } else {
      ShowHTML('           <td>Unidade:<br><b>'.$w_nome.' ('.$w_sigla.')</b><br><br>');
    }
    ShowHTML('           </table>');
    ShowHTML('      <tr><td colspan=3><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade pai:', 'U', 'Deixe em branco apenas se a unidade for numeradora.', $w_unidade_pai, null, 'w_unidade_pai', 'MOD_PA_PAI', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_unidade_pai\'; document.Form.submit();"');
    ShowHTML('           </table>');
    ShowHTML('      <tr valign="top">');
    // Apenas unidades de nível zero (sem pai) podem ter controle automático de numeração
    if (nvl($w_unidade_pai, '') == '') {
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
      MontaRadioSN('<b>Registra documentos</b>?', $w_registra_documento, 'w_registra_documento');
      MontaRadioNS('<b>Autua processos</b>?', $w_autua_processo, 'w_autua_processo');
      MontaRadioNS('<b>Arquivo setorial</b>?', $w_arquivo_setorial, 'w_arquivo_setorial');
    }
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo</b>?', $w_ativo, 'w_ativo');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan="3" align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG. montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
  $w_chave = $_REQUEST['w_chave'];
  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_chave = $_REQUEST['w_chave'];
    $w_nome = $_REQUEST['w_nome'];
    $w_sigla = $_REQUEST['w_sigla'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_ativo = $_REQUEST['w_ativo'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getNaturezaDoc_PA;
    $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, null, null);
    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'nome', 'asc');
    } else {
      $RS = SortArray($RS, 'nome', 'asc');
    }
  } elseif (!(strpos('AEV', $O) === false)) {
    // Recupera os dados chave informada
    $sql = new db_getNaturezaDoc_PA;
    $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_chave = f($RS, 'chave');
    $w_nome = f($RS, 'nome');
    $w_sigla = f($RS, 'sigla');
    $w_descricao = f($RS, 'descricao');
    $w_ativo = f($RS, 'ativo');
  }
  Cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA', $O) === false)) {
      Validate('w_nome', 'Nome', '1', '1', '4', '60', '1', '1');
      Validate('w_sigla', 'Sigla', '1', '1', '2', '10', '1', '1');
      Validate('w_descricao', 'Descrição', '1', '1', '4', '1000', '1', '1');
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O == 'E') {
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA', $O) === false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O == 'E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('    <td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Nome', 'nome').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Sigla', 'sigla').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Descricao', 'descricao').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Ativo', 'nm_ativo').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Operações</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row, 'nome').'</td>');
        ShowHTML('        <td>'.f($row, 'sigla').'</td>');
        ShowHTML('        <td>'.f($row, 'descricao').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">EX</A>&nbsp');
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
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false))
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      <tr><td><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=5 cols=80 title="Detalhe o tipo de despacho." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>', $w_ativo, 'w_ativo');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG. montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
  $w_chave = $_REQUEST['w_chave'];
  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_chave = $_REQUEST['w_chave'];
    $w_sigla = $_REQUEST['w_sigla'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_fase_corrente = $_REQUEST['w_fase_corrente'];
    $w_fase_intermed = $_REQUEST['w_fase_intermed'];
    $w_fase_final = $_REQUEST['w_fase_final'];
    $w_destinacao_final = $_REQUEST['w_destinacao_final'];
    $w_ativo = $_REQUEST['w_ativo'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getTipoGuarda_PA;
    $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, null, null, null, null, null, null);
    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'sigla', 'asc');
    } else {
      $RS = SortArray($RS, 'sigla', 'asc');
    }
  } elseif (!(strpos('AEV', $O) === false)) {
    // Recupera os dados chave informada
    $sql = new db_getTipoGuarda_PA;
    $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_chave = f($RS, 'chave');
    $w_sigla = f($RS, 'sigla');
    $w_descricao = f($RS, 'descricao');
    $w_fase_corrente = f($RS, 'fase_corrente');
    $w_fase_intermed = f($RS, 'fase_intermed');
    $w_fase_final = f($RS, 'fase_final');
    $w_destinacao_final = f($RS, 'destinacao_final');
    $w_ativo = f($RS, 'ativo');
  }
  Cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA', $O) === false)) {
      Validate('w_sigla', 'Sigla', '1', '1', '1', '4', '1', '1');
      Validate('w_descricao', 'Descrição', '1', '1', '4', '255', '1', '1');
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O == 'E') {
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA', $O) === false)) {
    BodyOpen('onLoad=\'document.Form.w_sigla.focus()\';');
  } elseif ($O == 'E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('    <td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Sigla', 'sigla').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Descrição', 'descricao').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Fase corrente', 'nm_fase_corrente').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Fase intermediária', 'nm_fase_intermed').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Fase final', 'nm_fase_final').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Destinação final', 'nm_destinacao_final').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Ativo', 'nm_ativo').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Operações</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row, 'sigla').'</td>');
        ShowHTML('        <td>'.f($row, 'descricao').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_fase_corrente').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_fase_intermed').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_fase_final').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_destinacao_final').'</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">EX</A>&nbsp');
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
    MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false))
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td colspan="2"><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td colspan="2"><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=5 cols=80 title="Detalhe o tipo de despacho." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Fase corrente?</b>', $w_fase_corrente, 'w_fase_corrente');
    MontaRadioSN('<b>Fase intermediária?</b>', $w_fase_intermed, 'w_fase_intermed');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Fase final?</b>', $w_fase_final, 'w_fase_final');
    MontaRadioSN('<b>Destinacao final?</b>', $w_destinacao_final, 'w_destinacao_final');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>', $w_ativo, 'w_ativo');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG. montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina da tabela de parâmetros do módulo de protocolo e arquivo
// -------------------------------------------------------------------------
function Parametro() {
  extract($GLOBALS);
  global $w_Disabled;

  $sql = new db_getParametro;
  $RS = $sql->getInstanceOf($dbms, $w_cliente, 'PA', null);
  foreach ($RS as $row) {
    $RS = $row;
  }
  $w_despacho_desarqcentral = f($RS, 'despacho_desarqcentral');
  $w_despacho_arqcentral = f($RS, 'despacho_arqcentral');
  $w_despacho_emprestimo = f($RS, 'despacho_emprestimo');
  $w_despacho_devolucao = f($RS, 'despacho_devolucao');
  $w_despacho_autuar = f($RS, 'despacho_autuar');
  $w_despacho_arqsetorial = f($RS, 'despacho_arqsetorial');
  $w_despacho_anexar = f($RS, 'despacho_anexar');
  $w_despacho_apensar = f($RS, 'despacho_apensar');
  $w_despacho_eliminar = f($RS, 'despacho_eliminar');
  $w_despacho_desmembrar = f($RS, 'despacho_desmembrar');
  $w_arquivo_central = f($RS, 'arquivo_central');
  $w_limite_interessados = f($RS, 'limite_interessados');
  $w_ano_corrente = f($RS, 'ano_corrente');
  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_despacho_arqcentral', 'Despacho para arquivo central', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_desarqcentral', 'Despacho para desarquivamento central', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_arqsetorial', 'Despacho para arquivo setorial', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_emprestimo', 'Despacho para emprestimo', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_devolucao', 'Despacho para devolução', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_eliminar', 'Despacho para eliminar', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_autuar', 'Despacho para autuar', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_anexar', 'Despacho para anexar', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_apensar', 'Despacho para apensar', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_despacho_desmembrar', 'Despacho para desmembrar', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_arquivo_central', 'Unidade do arquivo central', 'SELECT', '1', '1', '18', '', '1');
  Validate('w_limite_interessados', 'Limite de interessados', '1', '1', '1', '18', '', '1');
  Validate('w_ano_corrente', 'Ano corrente', '1', '1', '4', '4', '', '1');
  ShowHTML('  if ((theForm.w_despacho_arqcentral.value==theForm.w_despacho_emprestimo.value)||(theForm.w_despacho_arqcentral.value==theForm.w_despacho_devolucao.value)||(theForm.w_despacho_devolucao.value==theForm.w_despacho_emprestimo.value)) {');
  ShowHTML('    alert("Nenhum dos despachos podem ser iguais!");');
  ShowHTML('    theForm.w_despacho_arqcentral.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  Validate('w_assinatura', 'Assinatura eletrônica', '1', '1', '6', '15', '1', '1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad=\'document.Form.w_despacho_arqsetorial.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.str_replace('Listagem', 'Alteração', $w_TP).'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina.$par, $O);
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="97%" border="0">');
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para ar<U>q</U>uivo central:', 'A', null, $w_cliente, $w_despacho_arqcentral, null, 'w_despacho_arqcentral', 'TODOS', null);
  SelecaoTipoDespacho('Despacho para desarqu<U>i</U>vamento central:', 'I', null, $w_cliente, $w_despacho_desarqcentral, null, 'w_despacho_desarqcentral', 'TODOS', null);
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para <U>e</U>mpréstimo:', 'E', null, $w_cliente, $w_despacho_emprestimo, null, 'w_despacho_emprestimo', 'TODOS', null);
  SelecaoTipoDespacho('Despacho para <U>d</U>evolução:', 'D', null, $w_cliente, $w_despacho_devolucao, null, 'w_despacho_devolucao', 'TODOS', null);
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para elimi<U>n</U>ar:', 'N', null, $w_cliente, $w_despacho_eliminar, null, 'w_despacho_eliminar', 'TODOS', null);
  SelecaoTipoDespacho('Despacho para a<U>u</U>tuar:', 'U', null, $w_cliente, $w_despacho_autuar, null, 'w_despacho_autuar', 'TODOS', null);
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para ane<U>x</U>ar:', 'X', null, $w_cliente, $w_despacho_anexar, null, 'w_despacho_anexar', 'TODOS', null);
  SelecaoTipoDespacho('Despacho para apen<U>s</U>ar:', 'S', null, $w_cliente, $w_despacho_apensar, null, 'w_despacho_apensar', 'TODOS', null);
  ShowHTML('      <tr valign="top">');
  SelecaoTipoDespacho('Despacho para <U>d</U>esmembrar:', 'D', null, $w_cliente, $w_despacho_desmembrar, null, 'w_despacho_desmembrar', 'TODOS', null);
  SelecaoTipoDespacho('Despacho para arqu<U>i</U>vo setorial:', 'I', null, $w_cliente, $w_despacho_arqsetorial, null, 'w_despacho_arqsetorial', 'TODOS', null);
  ShowHTML('      <tr valign="top"><td colspan="2"><table width="97%" border="0">');
  SelecaoUnidade('<U>A</U>rquivo central:', 'A', null, $w_arquivo_central, null, 'w_arquivo_central', 'MOD_PA', null);
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
// Rotina para renumeração de números de protocolo
// -------------------------------------------------------------------------
function Renumera() {
  extract($GLOBALS);
  global $w_Disabled;

  // Recupera as variáveis utilizadas na filtragem
  $w_prefixo_ant    = $_REQUEST['w_prefixo_ant'];
  $w_numero_ant     = $_REQUEST['w_numero_ant'];
  $w_ano_ant        = $_REQUEST['w_ano_ant'];
  
  $w_prefixo        = nvl($_REQUEST['w_prefixo'], $w_prefixo_ant);
  $w_numero         = $_REQUEST['w_numero'];
  $w_ano            = nvl($_REQUEST['w_ano'], $w_ano_ant);

  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  ShowHTML('  function carregaDados () {');
  ShowHTML('    if ($("#w_prefixo_ant").val()!="") $("#w_prefixo").val($("#w_prefixo_ant").val());');
  ShowHTML('    if ($("#w_ano_ant").val()!="") $("#w_ano").val($("#w_ano_ant").val());');
  ShowHTML('  }');
  ValidateOpen('Validacao');
  Validate('w_prefixo_ant', 'Prefixo atual', '1', '1', '5', '5', '', '0123456789');
  Validate('w_numero_ant', 'Número atual', '1', '1', '1', '6', '', '0123456789');
  Validate('w_ano_ant', 'Ano atual', '1', '1', '4', '4', '', '0123456789');
  Validate('w_prefixo', 'Prefixo', '1', '1', '5', '5', '', '0123456789');
  Validate('w_numero', 'Número', '1', '1', '1', '6', '', '0123456789');
  Validate('w_ano', 'Ano', '1', '1', '4', '4', '', '0123456789');
  ShowHTML('  if (parseFloat(theForm.w_ano.value)>'.date('Y', time()).') {');
  ShowHTML('    alert("Ano do novo protocolo não pode ser maior que o ano corrente!");');
  ShowHTML('    theForm.w_ano.focus();');
  ShowHTML('    return false');
  ShowHTML('  }');
  Validate('w_assinatura', 'Assinatura eletrônica', '1', '1', '6', '15', '1', '1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if (nvl($w_prefixo_ant, '') == '') {
    BodyOpen('onLoad=\'document.Form.w_prefixo_ant.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_prefixo.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.str_replace(' - Listagem', '', $w_TP).'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=2 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orientação:<ul>');
  ShowHTML('  <li>Informe o número de protocolo atual.');
  ShowHTML('  <li>Em seguida, informe o novo protocolo. O sistema irá sugerir o mesmo prefixo e ano do protocolo informado, mas permitirá sua alteração.');
  ShowHTML('  <li>Os dois últimos dígitos do novo protocolo serão gerados automaticamente pelo sistema.');
  ShowHTML('  <li>As regras para a renumeração de protocolo são:<ul><li>O ano do novo protocolo não pode ser superior ao ano corrente;<li>O protocolo atual deve existir;<li>O novo protocolo não pode estar associado a um documento/processo existente.</ul>');
  ShowHTML('  </ul></b></font></td>');
  AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina.$par, $O);
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="97%" border="0">');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td><b >Protocolo atual:<br>');
  ShowHTML('            <INPUT '.$w_Disabled.' class="sti" type="text" id="w_prefixo_ant" name="w_prefixo_ant" size="5" maxlength="5" value="'.$w_prefixo_ant.'" onBlur="carregaDados();">.');
  ShowHTML('            <INPUT '.$w_Disabled.' class="sti" type="text" id="w_numero_ant" style="text-align:right;" name="w_numero_ant" size="6" maxlength="6" value="'.$w_numero_ant.'">/');
  ShowHTML('            <INPUT '.$w_Disabled.' class="sti" type="text" id="w_ano_ant" name="w_ano_ant" size="4" maxlength="4" value="'.$w_ano_ant.'" onBlur="carregaDados();"></td>');
  ShowHTML('        <td><b>Novo protocolo:<br>');
  ShowHTML('            <INPUT '.$w_Disabled.' class="sti" type="text" id="w_prefixo" name="w_prefixo" size="5" maxlength="5" value="'.$w_prefixo.'">.');
  ShowHTML('            <INPUT '.$w_Disabled.' class="sti" type="text" id="w_numero" name="w_numero" style="text-align:right;" size="6" maxlength="6" value="'.$w_numero.'">/');
  ShowHTML('            <INPUT '.$w_Disabled.' class="sti" type="text" id="w_ano" name="w_ano" size="4" maxlength="4" value="'.$w_ano.'"></td>');
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
  $w_chave = $_REQUEST['w_chave'];
  $w_copia = $_REQUEST['w_copia'];
  $p_ordena = $_REQUEST['p_ordena'];
  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_chave_pai = $_REQUEST['w_chave_pai'];
    $w_codigo = $_REQUEST['w_codigo'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_detalhamento = $_REQUEST['w_detalhamento'];
    $w_observacao = $_REQUEST['w_observacao'];
    $w_corrente_guarda = $_REQUEST['w_corrente_guarda'];
    $w_corrente_anos = $_REQUEST['w_corrente_anos'];
    $w_intermed_guarda = $_REQUEST['w_intermed_guarda'];
    $w_intermed_anos = $_REQUEST['w_intermed_anos'];
    $w_final_guarda = $_REQUEST['w_final_guarda'];
    $w_final_anos = $_REQUEST['w_final_anos'];
    $w_destinacao_final = $_REQUEST['w_destinacao_final'];
    $w_provisorio = $_REQUEST['w_provisorio'];
    $w_ativo = $_REQUEST['w_ativo'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getAssunto_PA;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, null, null, null, null, null, 'ISNULL');
    if (nvl($p_ordena, '') != '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'provisorio', 'desc', 'codigo', 'asc', 'descricao', 'asc');
    } else {
      $RS = SortArray($RS, 'provisorio', 'desc', 'codigo', 'asc', 'descricao', 'asc');
    }
  } elseif (!(strpos('AEV', $O) === false) && $w_troca == '') {
    // Recupera os dados de um assunto
    $sql = new db_getAssunto_PA;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, null, null, null, null, null, null, null, null, 'REGISTROS');
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_chave_pai = f($RS, 'sq_assunto_pai');
    $w_codigo = f($RS, 'codigo');
    $w_descricao = f($RS, 'descricao');
    $w_detalhamento = f($RS, 'detalhamento');
    $w_observacao = f($RS, 'observacao');
    $w_corrente_guarda = f($RS, 'fase_corrente_guarda');
    $w_corrente_anos = f($RS, 'fase_corrente_anos');
    $w_intermed_guarda = f($RS, 'fase_intermed_guarda');
    $w_intermed_anos = f($RS, 'fase_intermed_anos');
    $w_final_guarda = f($RS, 'fase_final_guarda');
    $w_final_anos = f($RS, 'fase_final_anos');
    $w_destinacao_final = f($RS, 'destinacao_final');
    $w_provisorio = f($RS, 'provisorio');
    $w_ativo = f($RS, 'ativo');
  }
  cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IAC', $O) === false)) {
      Validate('w_chave_pai', 'Assunto pai', 'SELECT', '', '1', '18', '', '1');
      Validate('w_codigo', 'Código', '1', '1', '1', '10', '1', '1');
      Validate('w_descricao', 'Descricao', '1', '1', '3', '255', '1', '1');
      Validate('w_detalhamento', 'Detalhamento', '1', '', '4', '2000 ', '1', '1');
      Validate('w_observacao', 'Observação', '1', '', '4', '2000 ', '1', '1');
      Validate('w_corrente_guarda', 'Guarda na fase corrente', 'SELECT', '1', '1', '18', '', '1');
      Validate('w_corrente_anos', 'Nº de anos na fase corrente', '1', '', '1', '18', '', '1');
      Validate('w_intermed_guarda', 'Guarda na fase intemediária', 'SELECT', '1', '1', '18', '', '1');
      Validate('w_intermed_anos', 'Nº de anos na fase intemediária', '1', '', '1', '18', '', '1');
      Validate('w_final_guarda', 'Guarda na fase final', 'SELECT', '1', '1', '18', '', '1');
      Validate('w_final_anos', 'Nº de anos na fase final', '1', '', '1', '18', '', '1');
      Validate('w_destinacao_final', 'Destinação final do documento', 'SELECT', '1', '1', '18', '', '1');
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O == 'E') {
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O == 'C' || $O == 'I' || $O == 'A') {
    BodyOpen('onLoad=\'document.Form.w_chave_pai.focus();\'');
  } elseif ($O == 'L') {
    BodyOpen('onLoad=\'this.focus();\'');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><table width="99%" border="0">');
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Código', 'codigo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Descrição', 'descricao', '').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Corrente', 'ds_corrente_guarda').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Intermediária', 'ds_intermed_guarda').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Final', 'ds_final_guarda').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Destinação final', 'ds_destinacao_final').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Provisório', 'nm_provisorio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo', 'nm_ativo').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td><b>Operações</td>');
    }
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHtml(AssuntoLinha(f($row, 'chave'), f($row, 'codigo'), f($row, 'descricao'), f($row, 'ds_corrente_guarda'), f($row, 'sg_corrente_guarda'), f($row, 'fase_corrente_anos'), f($row, 'ds_intermed_guarda'), f($row, 'sg_intermed_guarda'), f($row, 'fase_intermed_anos'), f($row, 'ds_final_guarda'), f($row, 'sg_final_guarda'), f($row, 'fase_final_anos'), f($row, 'ds_destinacao_final'), f($row, 'sg_destinacao_final'), f($row, 'nm_provisorio'), f($row, 'nm_ativo'), 'S', $w_cor));
        // Recupera os assuntos vinculados ao nível acima
        $sql = new db_getAssunto_PA;
        $RS1 = $sql->getInstanceOf($dbms, $w_cliente, null, f($row, 'chave'), null, null, null, null, null, null, null, 'REGISTROS');
        $RS1 = SortArray($RS1, 'codigo', 'asc', 'descricao', 'asc');
        foreach ($RS1 as $row1) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHtml(AssuntoLinha(f($row1, 'chave'), f($row1, 'codigo'), f($row1, 'descricao'), f($row1, 'ds_corrente_guarda'), f($row1, 'sg_corrente_guarda'), f($row1, 'fase_corrente_anos'), f($row1, 'ds_intermed_guarda'), f($row1, 'sg_intermed_guarda'), f($row1, 'fase_intermed_anos'), f($row1, 'ds_final_guarda'), f($row1, 'sg_final_guarda'), f($row1, 'fase_final_anos'), f($row1, 'ds_destinacao_final'), f($row1, 'sg_destinacao_final'), f($row1, 'nm_provisorio'), f($row1, 'nm_ativo'), 'S', $w_cor));
          // Recupera os assuntos vinculados ao nível acima
          $sql = new db_getAssunto_PA;
          $RS2 = $sql->getInstanceOf($dbms, $w_cliente, null, f($row1, 'chave'), null, null, null, null, null, null, null, 'REGISTROS');
          $RS2 = SortArray($RS2, 'codigo', 'asc', 'descricao', 'asc');
          foreach ($RS2 as $row2) {
            $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
            ShowHtml(AssuntoLinha(f($row2, 'chave'), f($row2, 'codigo'), f($row2, 'descricao'), f($row2, 'ds_corrente_guarda'), f($row2, 'sg_corrente_guarda'), f($row2, 'fase_corrente_anos'), f($row2, 'ds_intermed_guarda'), f($row2, 'sg_intermed_guarda'), f($row2, 'fase_intermed_anos'), f($row2, 'ds_final_guarda'), f($row2, 'sg_final_guarda'), f($row2, 'fase_final_anos'), f($row2, 'ds_destinacao_final'), f($row2, 'sg_destinacao_final'), f($row2, 'nm_provisorio'), f($row2, 'nm_ativo'), 'S', $w_cor));
            // Recupera as etapas vinculadas ao nível acima
            $sql = new db_getAssunto_PA;
            $RS3 = $sql->getInstanceOf($dbms, $w_cliente, null, f($row2, 'chave'), null, null, null, null, null, null, null, 'REGISTROS');
            $RS3 = SortArray($RS3, 'codigo', 'asc', 'descricao', 'asc');
            foreach ($RS3 as $row3) {
              $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
              ShowHtml(AssuntoLinha(f($row3, 'chave'), f($row3, 'codigo'), f($row3, 'descricao'), f($row3, 'ds_corrente_guarda'), f($row3, 'sg_corrente_guarda'), f($row3, 'fase_corrente_anos'), f($row3, 'ds_intermed_guarda'), f($row3, 'sg_intermed_guarda'), f($row3, 'fase_intermed_anos'), f($row3, 'ds_final_guarda'), f($row3, 'sg_final_guarda'), f($row3, 'fase_final_anos'), f($row3, 'ds_destinacao_final'), f($row3, 'sg_destinacao_final'), f($row3, 'nm_provisorio'), f($row3, 'nm_ativo'), 'S', $w_cor));
              // Recupera os assuntos vinculados ao nível acima
              $sql = new db_getAssunto_PA;
              $RS4 = $sql->getInstanceOf($dbms, $w_cliente, null, f($row3, 'chave'), null, null, null, null, null, null, null, 'REGISTROS');
              $RS4 = SortArray($RS4, 'codigo', 'asc', 'descricao', 'asc');
              foreach ($RS4 as $row4) {
                $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
                ShowHtml(AssuntoLinha(f($row4, 'chave'), f($row4, 'codigo'), f($row4, 'descricao'), f($row4, 'ds_corrente_guarda'), f($row4, 'sg_corrente_guarda'), f($row4, 'fase_corrente_anos'), f($row4, 'ds_intermed_guarda'), f($row4, 'sg_intermed_guarda'), f($row4, 'fase_intermed_anos'), f($row4, 'ds_final_guarda'), f($row4, 'sg_final_guarda'), f($row4, 'fase_final_anos'), f($row4, 'ds_destinacao_final'), f($row4, 'sg_destinacao_final'), f($row4, 'nm_provisorio'), f($row4, 'nm_ativo'), 'S', $w_cor));
              }
            }
          }
        }
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    ShowHTML('<tr><td><table width="99%" border="0" bgcolor="'.$conTrBgColor.'">');
    if (!(strpos('EV', $O) === false))
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('  <tr valign="top">');
    ShowHTML('      <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0><tr><td>');
    SelecaoAssunto('Assun<u>t</u>o pai:', 'T', null, $w_chave_pai, null, 'w_chave_pai', null, 'SUBGRUPO', null);
    ShowHTML('      </table><tr><td>');
    ShowHTML('      <tr><td colspan="2"><b><u>C</u>ódigo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_codigo.'" title="Informe o código do assunto."></td>');
    ShowHTML('      <tr><td colspan="2"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva o assunto.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><b>D<u>e</u>talhamento:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_detalhamento" class="STI" ROWS=5 cols=75 title="Detalhe o assunto.">'.$w_detalhamento.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><b><u>O</u>bservacao:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75 >'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr>');
    SelecaoTipoGuarda('<u>G</u>uarda na fase corrente:', 'G', null, $w_corrente_guarda, null, 'w_corrente_guarda', 'CORRENTE', null);
    ShowHTML('          <td><b>Nº de anos:</b><br><input '.$w_Disabled.' type="text" name="w_corrente_anos" class="STI" SIZE="10" MAXLENGTH="18" VALUE="'.Nvl($w_corrente_anos, 0).'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoTipoGuarda('G<u>u</u>arda na fase intermediária:', 'U', null, $w_intermed_guarda, null, 'w_intermed_guarda', 'INTERMED', null);
    ShowHTML('          <td><b>Nº de anos:</b><br><input '.$w_Disabled.' type="text" name="w_intermed_anos" class="STI" SIZE="10" MAXLENGTH="18" VALUE="'.Nvl($w_intermed_anos, 0).'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoTipoGuarda('Gua<u>r</u>da na fase final:', 'R', null, $w_final_guarda, null, 'w_final_guarda', 'FINAL', null);
    ShowHTML('          <td><b>Nº de anos:</b><br><input '.$w_Disabled.' type="text" name="w_final_anos" class="STI" SIZE="10" MAXLENGTH="18" VALUE="'.Nvl($w_final_anos, 0).'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoTipoGuarda('De<u>s</u>tinação final do documento:', 'S', null, $w_destinacao_final, null, 'w_destinacao_final', 'DESTINACAO', null);
    ShowHTML('      </tr>');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Registro para classificação provisória?</b>', $w_provisorio, 'w_provisorio');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>', $w_ativo, 'w_ativo');
    ShowHTML('      <tr><td><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3"><input class="stb" type="submit" name="Botao" value="Gravar">&nbsp;');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG. montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
function AssuntoLinha($l_chave, $l_codigo, $l_descricao, $l_ds_corrente, $l_sg_corrente, $l_corrente_ano, $l_ds_intermed, $l_sg_intermed, $l_intermed_ano, $l_ds_final, $l_sg_final, $l_final_ano, $l_ds_destinacao, $l_sg_destinacao, $l_provisorio, $l_ativo, $l_oper, $l_cor) {
  extract($GLOBALS);
  $l_html = $l_html.chr(13).'       <tr bgcolor="'.$l_cor.'" valign="top">';
  $l_html = $l_html.chr(13).'        <td>'.$l_codigo.'</b>';
  $l_html = $l_html.chr(13).'        <td>'.$l_descricao.'</b>';
  $l_html = $l_html.chr(13).'        <td nowrap align="center" title="'.$l_ds_corrente.'">'.(($l_sg_corrente == 'ANOS') ? $l_corrente_ano.' '.$l_sg_corrente : $l_sg_corrente).'</td>';
  $l_html = $l_html.chr(13).'        <td nowrap align="center" title="'.$l_ds_intermed.'">'.(($l_sg_intermed == 'ANOS') ? $l_intermed_ano.' '.$l_sg_intermed : $l_sg_intermed).'</td>';
  $l_html = $l_html.chr(13).'        <td nowrap align="center" title="'.$l_ds_final.'">'.(($l_sg_final == 'ANOS') ? $l_final_ano.' '.$l_sg_final : $l_sg_final).'</td>';
  $l_html = $l_html.chr(13).'        <td nowrap align="center" title="'.$l_ds_destinacao.'">'.$l_sg_destinacao.'</b>';
  $l_html = $l_html.chr(13).'        <td align="center">'.$l_provisorio.'</b>';
  $l_html = $l_html.chr(13).'        <td align="center">'.$l_ativo.'</b>';
  if ($l_oper == 'S' && $w_libera_edicao=='S') {
    $l_html = $l_html.chr(13).'        <td nowrap>';
    $l_html = $l_html.chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">AL</A>&nbsp';
    $l_html = $l_html.chr(13).'          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">EX</A>&nbsp';
    $l_html = $l_html.chr(13).'        </td>';
  }
  $l_html = $l_html.chr(13).'      </tr>';
  return $l_html;
}

// =========================================================================
// Rotina de tela de exibição do recurso
// -------------------------------------------------------------------------
function TelaAssunto() {
  extract($GLOBALS);
  global $w_Disabled, $w_TP;

  $w_chave = $_REQUEST['w_chave'];

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<TITLE>SIW - Protocolo - Assunto</TITLE>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad="this.focus();"');
  $w_TP = 'Protocolo e Arquivo - Visualização de assunto';
  Estrutura_Texto_Abre();

  // Recupera os dados de um assunto
  $sql = new db_getAssunto_PA;
  $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, null, null, null, null, null, null, null, null, 'REGISTROS');
  foreach ($RS as $row) {
    $RS = $row;
    break;
  }
  $l_html = '';
  $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
  $l_html.=chr(13).'<tr><td align="center">';

  $l_html.=chr(13).'    <table width="99%" border="0">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><b>['.f($RS, 'codigo').'] '.f($RS, 'descricao').'</font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  if (nvl(f($RS, 'ds_assunto_pai'), '') != '') {
    $l_html.=chr(13).'   <tr><td valign="top"><b>Subordinação:</b></td>';
    $l_html.=chr(13).'       <td align="justify">';
    if (nvl(f($RS, 'ds_assunto_bis'), '') != '')
      $l_html.=chr(13).ExibeAssunto('../', $w_cliente, f($RS, 'ds_assunto_bis'), f($row, 'sq_assunto_bis'), $TP).' &rarr; ';
    if (nvl(f($RS, 'ds_assunto_avo'), '') != '')
      $l_html.=chr(13).ExibeAssunto('../', $w_cliente, f($RS, 'ds_assunto_avo'), f($row, 'sq_assunto_avo'), $TP).' &rarr; ';
    if (nvl(f($RS, 'ds_assunto_pai'), '') != '')
      $l_html.=chr(13).ExibeAssunto('../', $w_cliente, f($RS, 'ds_assunto_pai'), f($row, 'sq_assunto_pai'), $TP);
  }
  $l_html.=chr(13).'       </td></tr>';

  $l_html.=chr(13).'   <tr><td valign="top"><b>Detalhamento:</b></td>';
  $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS, 'detalhamento'), '---')).'</td></tr>';
  $l_html.=chr(13).'   <tr><td valign="top"><b>Observação:</b></td>';
  $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS, 'observacao'), '---')).'</td></tr>';
  $l_html.=chr(13).'   <tr><td valign="top"><b>Prazos de guarda:</b></td>';
  $l_html.=chr(13).'       <td align="justify"><table border=1>';
  $l_html.=chr(13).'         <tr valign="top"><td align="center"><b>Fase corrente<td align="center"><b>Fase intermediária<td align="center"><b>Destinação final';
  $l_html.=chr(13).'         <tr valign="top">';
  $l_html.=chr(13).'           '.((strpos(upper(f($RS, 'guarda_corrente')), 'ANOS') === false) ? '<td>' : '<td align="center">').f($RS, 'guarda_corrente').'</td>';
  $l_html.=chr(13).'           '.((strpos(upper(f($RS, 'guarda_intermed')), 'ANOS') === false) ? '<td>' : '<td align="center">').f($RS, 'guarda_intermed').'</td>';
  $l_html.=chr(13).'           '.((strpos(upper(f($RS, 'guarda_final')), 'ANOS') === false) ? '<td>' : '<td align="center">').f($RS, 'guarda_final').'</td>';
  $l_html.=chr(13).'         </table>';
  $l_html.=chr(13).'    </table>';

  // Assuntos subordinados
  $sql = new db_getAssunto_PA;
  $RS1 = $sql->getInstanceOf($dbms, $w_cliente, null, $w_chave, null, null, null, null, null, null, null, 'REGISTROS');
  $RS1 = SortArray($RS1, 'codigo', 'asc', 'descricao', 'asc');
  if (count($RS1) > 0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ASSUNTOS SUBORDINADOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
    $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'       <tr valign="top">';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" width="1%" nowrap align="center"><b>Código</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Descrição</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Corrente</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Intermediária</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Final</b></td>';
    $l_html.=chr(13).'       </tr>';
    foreach ($RS1 as $row) {
      $l_html.=chr(13).'       <tr valign="top">';
      $l_html.=chr(13).'           <td nowrap>&nbsp;'.ExibeAssunto('../', $w_cliente, f($row, 'codigo'), f($row, 'sq_assunto'), $TP).'</td>';
      $l_html.=chr(13).'           <td>'.nvl(f($row, 'descricao'), '---');
      $l_html.=chr(13).'           '.((strpos(upper(f($row, 'guarda_corrente')), 'ANOS') === false) ? '<td>' : '<td align="center">').f($row, 'guarda_corrente').'</td>';
      $l_html.=chr(13).'           '.((strpos(upper(f($row, 'guarda_intermed')), 'ANOS') === false) ? '<td>' : '<td align="center">').f($row, 'guarda_intermed').'</td>';
      $l_html.=chr(13).'           '.((strpos(upper(f($row, 'guarda_final')), 'ANOS') === false) ? '<td>' : '<td align="center">').f($row, 'guarda_final').'</td>';
      $l_html.=chr(13).'      </tr>';
    }
    $l_html.=chr(13).'         </table></td></tr>';
  }
  $l_html.=chr(13).'</table>';
  ShowHTML($l_html);
  Estrutura_Texto_Fecha();
}

// =========================================================================
// Manutenção da tabela de arquivos do protocolo
// -------------------------------------------------------------------------
function Arquivo() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];

  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_nome = $_REQUEST['w_nome'];
    $w_ativo = $_REQUEST['w_ativo'];
    $w_unidade = $_REQUEST['w_unidade'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getArquivo_PA;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, 'OUTROS');

    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'nome', 'asc');
    } else {
      $RS = SortArray($RS, 'nome', 'asc');
    }
  } elseif (!(strpos('AE', $O) === false)) {
    // Recupera os dados chave informada
    $sql = new db_getArquivo_PA;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, null, null, null, 'OUTROS');
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_chave = f($RS, 'chave');
    $w_nome = f($RS, 'nome');
    $w_unidade = f($RS, 'sq_unidade');
    $w_ativo = f($RS, 'ativo');
  }
  Cabecalho();
  head();
  if (!(strpos('IAE', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA', $O) === false)) {
      Validate('w_nome', 'Nome', '1', '1', '2', '30', '1', '1');
      Validate('w_unidade', 'Unidade', 'SELECT', '1', '1', '18', '', '1');
      Validate('w_chave', 'Localização', 'SELECT', '1', '1', '18', '', '1');
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    } elseif ($O == 'E') {
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    }

    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }

  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA', $O) === false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O == 'E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('    <td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome', 'nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Localização', 'nm_unidade').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo', 'nm_ativo').'</font></td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td><b>Operações</font></td>');
    }
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row, 'nome').'</td>');
        ShowHTML('        <td>'.f($row, 'nm_unidade').' ('.f($row, 'nm_localizacao').')</td>');
        ShowHTML('        <td align="center">'.f($row, 'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">EX</A>&nbsp');
          ShowHTML('          <a class="HL" href="javascript:this.status.value;" onclick="window.open(\''.montaURL_JS(null, $conRootSIW.$w_dir.$w_pagina.'LOCAIS&R='.$w_pagina.$par.'&O=L&w_chave='.f($row, 'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PDLOCAIS').'\',\'Locais\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Locais</a>');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE', $O) === false)) {
    if ($O == 'E')
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O == 'E') ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <tr>');
    selecaoUnidade('<U>U</U>nidade:', 'U', 'Selecione a unidade e aguarde a recarga da página para selecionar sua localização.', $w_unidade, null, 'w_unidade', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_localizacao\'; document.Form.submit();"');
    ShowHTML('          <tr>');
    selecaoLocalizacao('Locali<u>z</u>ação:', 'Z', null, $w_chave, nvl($w_unidade, 0), 'w_chave', null);
    ShowHTML('          </tr>');
    MontaRadioSN('<b>Ativo?</b>', $w_ativo, 'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG. montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Manutenção das localizações de um arquivo
// -------------------------------------------------------------------------
function Locais() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ImagemPadrao = 'images/Folder/SheetLittle.gif';
  $w_troca = $_REQUEST['w_troca'];
  $w_copia = $_REQUEST['w_copia'];
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  // Recupera o nome do arquivo
  $sql = new db_getArquivo_PA;
  $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, null, null, null, 'OUTROS');
  foreach ($RS as $row) {
    $RS = $row;
    break;
  }
  $w_nome_arquivo = f($RS, 'nome');

  if ($w_troca > '' && $O != 'E' && $O != 'D' && $O != 'T') {
    $w_cliente = $_REQUEST['w_cliente'];
    $w_chave_pai = $_REQUEST['w_chave_pai'];
    $w_chave_aux = $_REQUEST['w_chave_aux'];
    $w_nome = $_REQUEST['w_nome'];
    $w_sigla = $_REQUEST['w_sigla'];
    $w_ativo = $_REQUEST['w_ativo'];
  } elseif ($O != 'L' && $O != 'I') {
    // Se for herança, atribui a chave da opção selecionada para w_chave
    if ($w_copia > '')
      $w_chave = $w_copia;
    $sql = new db_getArquivo_PA;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, $w_chave_aux, null, null, 'REGISTROS');
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_chave_pai = f($RS, 'sq_local_pai');
    $w_nome = f($RS, 'nome');
    $w_ativo = f($RS, 'ativo');
  }

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Locais de Arquivos</TITLE>');
  Estrutura_CSS($w_cliente);

  if ($O != 'L') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($O != 'P') {
      if ($O == 'C' || $O == 'I' || $O == 'A') {
        Validate('w_nome', 'Nome', '1', '1', '2', '30', '1', '1');
      }
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O == 'C' || $O == 'I' || $O == 'A') {
    BodyOpen('onLoad="document.Form.w_chave_pai.focus();"');
  } elseif ($O == 'L') {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td align="center"><font size="2"><b>'.$w_nome_arquivo.'&nbsp;');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="99%" border="0">');
  if ($O == 'L') {

    ShowHTML('      <tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('      <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td><b>');
    $sql = new db_getArquivo_PA;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, null, null, null, 'IS NULL');
    $w_contOut = 0;
    foreach ($RS as $row) {
      $w_nome = f($row, 'nome');
      $w_contOut = $w_contOut + 1;
      if (f($row, 'Filho') > 0) {
        ShowHTML('<A HREF=#"'.f($row, 'chave').'"></A>');
        ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row, 'nome').'');
        if (f($row, 'ativo') == 'S')
          $w_classe = 'hl'; else
          $w_classe='lh';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'sq_localizacao').'&w_chave_aux='.f($row, 'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Altera as informações deste tipo">AL</A>&nbsp');
        ShowHTML('       </div></span>');
        ShowHTML('   <div style="position:relative; left:12;">');
        $sql = new db_getArquivo_PA;
        $RS1 = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, f($row, 'chave'));
        foreach ($RS1 as $row1) {
          $w_nome .= ' - '.f($row1, 'nome');
          if (f($row1, 'Filho') > 0) {
            $w_contOut = $w_contOut + 1;
            ShowHTML('<A HREF=#"'.f($row1, 'chave').'"></A>');
            ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row1, 'nome').'');
            if (f($row1, 'ativo') == 'S')
              $w_classe = 'hl'; else
              $w_classe='lh';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1, 'sq_localizacao').'&w_chave_aux='.f($row1, 'chave').'&w_cliente='.$w_cliente.'&nome='.f($row1, 'nome').'&pai='.f($row1, 'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Altera as informações deste tipo">AL</A>&nbsp');
//            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row1,'chave').'&w_chave_aux='.f($row1,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row1,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
            ShowHTML('       </div></span>');
            ShowHTML('   <div style="position:relative; left:12;">');
            $sql = new db_getArquivo_PA;
            $RS2 = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, f($row1, 'chave'));
            foreach ($RS2 as $row2) {
              $w_nome .= ' - '.f($row2, 'nome');
              if (f($row2, 'Filho') > 0) {
                $w_contOut = $w_contOut + 1;
                ShowHTML('<A HREF=#"'.f($row2, 'chave').'"></A>');
                ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row2, 'nome').'');
                if (f($row2, 'ativo') == 'S')
                  $w_classe = 'hl'; else
                  $w_classe='lh';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2, 'sq_localizacao').'&w_chave_aux='.f($row2, 'chave').'&w_cliente='.$w_cliente.'&nome='.f($row2, 'nome').'&pai='.f($row2, 'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Altera as informações deste tipo">AL</A>&nbsp');
//                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row2,'chave').'&w_chave_aux='.f($row2,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row2,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                ShowHTML('       </div></span>');
                ShowHTML('   <div style="position:relative; left:12;">');
                $sql = new db_getArquivo_PA;
                $RS3 = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, f($row2, 'chave'));
                foreach ($RS3 as $row3) {
                  $w_nome .= ' - '.f($row3, 'nome');
                  $w_Imagem = $w_ImagemPadrao;
                  ShowHTML('<A HREF=#"'.f($row3, 'chave').'"></A>');
                  ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row3, 'nome'));
                  if (f($row3, 'ativo') == 'S')
                    $w_classe = 'hl'; else
                    $w_classe='lh';
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row3, 'sq_localizacao').'&w_chave_aux='.f($row3, 'chave').'&w_cliente='.$w_cliente.'&nome='.f($row3, 'nome').'&pai='.f($row3, 'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Altera as informações deste tipo">AL</A>&nbsp');
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row3, 'sq_localizacao').'&w_chave_aux='.f($row3, 'chave').'&w_cliente='.$w_cliente.'&pai='.f($row3, 'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Exclui o tipo">EX</A>&nbsp');
//                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row3,'chave').'&w_chave_aux='.f($row3,'chave').'&pai='.f($row3,'sq_local_pai').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                  ShowHTML('    <BR>');
                  $w_nome = str_replace(' - '.f($row3, 'nome'), '', $w_nome);
                }
                ShowHTML('   </div>');
              } else {
                $w_Imagem = $w_ImagemPadrao;
                ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row2, 'nome'));
                if (f($row2, 'ativo') == 'S')
                  $w_classe = 'hl'; else
                  $w_classe='lh';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row2, 'sq_localizacao').'&w_chave_aux='.f($row2, 'chave').'&w_cliente='.$w_cliente.'&nome='.f($row2, 'nome').'&pai='.f($row2, 'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Altera as informações deste tipo">AL</A>&nbsp');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row2, 'sq_localizacao').'&w_chave_aux='.f($row2, 'chave').'&w_cliente='.$w_cliente.'&pai='.f($row2, 'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Exclui o tipo">EX</A>&nbsp');
//                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row2,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row2,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
                ShowHTML('    <BR>');
              }
              $w_nome = str_replace(' - '.f($row2, 'nome'), '', $w_nome);
            }
            ShowHTML('   </div>');
          } else {
            $w_Imagem = $w_ImagemPadrao;
            ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row1, 'nome'));
            if (f($row1, 'ativo') == 'S')
              $w_classe = 'hl'; else
              $w_classe='lh';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row1, 'sq_localizacao').'&w_chave_aux='.f($row1, 'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Altera as informações deste tipo">AL</A>&nbsp');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row1, 'sq_localizacao').'&w_chave_aux='.f($row1, 'chave').'&w_cliente='.$w_cliente.'&pai='.f($row1, 'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Exclui o tipo">EX</A>&nbsp');
            //          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row1,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row1,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
            ShowHTML('    <BR>');
          }
          $w_nome = str_replace(' - '.f($row1, 'nome'), '', $w_nome);
        }
        ShowHTML('   </div>');
      } else {
        $w_Imagem = $w_ImagemPadrao;
        ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row, 'nome'));
        if (f($row, 'ativo') == 'S')
          $w_classe = 'hl'; else
          $w_classe='lh';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row, 'sq_localizacao').'&w_chave_aux='.f($row, 'chave').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Altera as informações deste tipo">AL</A>&nbsp');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row, 'sq_localizacao').'&w_chave_aux='.f($row, 'chave').'&w_cliente='.$w_cliente.'&pai='.f($row, 'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Exclui o tipo">EX</A>&nbsp');
//        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_copia='.f($row,'chave').'&w_chave_aux='.f($row,'chave').'&w_cliente='.$w_cliente.'&pai='.f($row,'sq_local_pai').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'" title="Insere um novo tipo a partir das informações deste registro">Copiar</A>&nbsp');
        ShowHTML('    <BR>');
      }
    }
    if ($w_contOut == 0) {
      // Se não achou registros
      ShowHTML('Não foram encontrados registros.');
    }
  } elseif (strpos('CIAEDT', $O) !== false) {
    if ($O == 'C' || $O == 'I' || $O == 'A') {
      ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">Orientação:<ul><li>Não é permitido subordinar um tipo de recurso a outro que já tenha recursos vinculados.</ul></b></font></td>');
      if ($O == 'C')
        ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: Dados importados de outro registro. Altere os dados necessários antes de executar a inclusão.</b></font>.</td>');
    }
    if ($O != 'C' && $O != 'I' && $O != 'A')
      $w_Disabled = 'disabled';
    AbreForm('Form', $w_dir.$w_pagina.'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina.$par, $O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_ativo" value="'.$w_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O != 'C')
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('      <tr valign="top">');


    if ($O != 'I' && $O != 'C') {
      // Se for alteração, não deixa vincular a opção a ela mesma, nem a seus filhos
      selecaoArquivoLocalSubordination('<u>S</u>ubordinação:', 'S', 'Se esta opção estiver subordinada a outra já existente, informe qual.', $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], 'w_chave_pai', 'SUBPARTE', null);
    } else {
      selecaoArquivoLocalSubordination('<u>S</u>ubordinação:', 'S', 'Se esta opção estiver subordinada a outra já existente, informe qual.', $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], 'w_chave_pai', 'SUBTODOS', null);
    }
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('            <td><b><u>N</u>ome:<br><INPUT ACCESSKEY="N" TYPE="TEXT" CLASS="sti" NAME="w_nome" SIZE=30 MAXLENGTH=30 VALUE="'.$w_nome.'" '.$w_Disabled.' title="Nome do tipo."></td>');
    ShowHTML('        </table>');
    if ($O == 'I' || $O == 'C' || $O == 'A') {
      ShowHTML('      <tr align="left">');
      MontaRadioSN('Ativo?', $w_ativo, 'w_ativo');
      ShowHTML('      </tr>');
    }
    ShowHTML('      </td></tr>');
    ShowHTML('      <tr><td><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    if ($O == 'E') {
      ShowHTML('    <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O == 'I') {
      ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O == 'A') {
      ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Atualizar">');
    } elseif ($O == 'T') {
      ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Ativar">');
    } elseif ($O == 'C') {
      ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Copiar">');
    } elseif ($O == 'D') {
      ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Desativar">');
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir, $R.'&O=L&w_cliente='.$w_cliente.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('      </td></tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');

    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'ALTLOCAL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {

        $SQL = new dml_putDocumentoArqCen;
        for ($i = 0; $i <= count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $_SESSION['SQ_PESSOA'], $_REQUEST['w_local'][$i], $_REQUEST['w_observacao']);
          }
        }
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Alteração nas localizações realizada com sucesso!");');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $w_pagina.'AltLocal&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        exit;
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PACAIXA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {

        $SQL = new dml_putCaixa;
        $SQL->getInstanceOf($dbms, trim($O), $w_cliente, $_REQUEST['w_chave'], $_REQUEST['w_unidade'], null, $_REQUEST['w_assunto'], $_REQUEST['w_descricao'], $_REQUEST['w_data_limite'], null, $_REQUEST['w_intermediario'], $_REQUEST['w_destinacao_final'], null, null, null, null, null, null, null);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        exit;
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PARENUM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        // Testa a existência do novo protocolo
        $sql = new db_getLinkData;
        $RS_Menu = $sql->getInstanceOf($dbms, $w_cliente, 'PADCAD');
        // Verifica se o protocolo atual existe
        $sql = new db_getProtocolo;
        $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, 'EXISTE', null, null,
                        Nvl($_REQUEST['w_prefixo_ant'], ''), Nvl($_REQUEST['w_numero_ant'], ''),
                        Nvl($_REQUEST['w_ano_ant'], ''), null, null, null, null, null, null, null, 
                        null, null, null, null, null, null, null, null);
        if (count($RS) == 0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("Protocolo atual não encontrado!");');
          ScriptClose();
          retornaFormulario('w_prefixo_ant');
          break;
        } else {
          foreach ($RS as $row) { $RS = $row; break; }
          $w_chave = f($RS, 'sq_siw_solicitacao');
        }
        // Verifica se o novo protocolo existe
        $sql = new db_getProtocolo;
        $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, 'EXISTE', null, null,
                        Nvl($_REQUEST['w_prefixo'], ''), Nvl($_REQUEST['w_numero'], ''), Nvl($_REQUEST['w_ano'], ''),
                        null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
        if (count($RS) > 0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("Novo protocolo já está associado a um documento/processo existente!");');
          ScriptClose();
          retornaFormulario('w_numero');
          break;
        }
        // Executa a renumeração do protocolo
        $SQL = new dml_putRenumeraProtocolo;
        $SQL->getInstanceOf($dbms, $w_usuario, $w_chave, $_REQUEST['w_prefixo'], $_REQUEST['w_numero'], $_REQUEST['w_ano']);

        // Recupera o novo protocolo, com DV
        $sql = new db_getProtocolo;
        $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, 'EXISTE', null, null,
                        Nvl($_REQUEST['w_prefixo'], ''), Nvl($_REQUEST['w_numero'], ''), Nvl($_REQUEST['w_ano'], ''),
                        null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
        foreach ($RS as $row) { $RS = $row; break; }

        ScriptOpen('JavaScript');
        ShowHTML('  alert("Protocolo '.$_REQUEST['w_prefixo_ant'].'.'.$_REQUEST['w_numero_ant'].'/'.$_REQUEST['w_ano_ant'].' renumerado com sucesso para '.f($RS, 'protocolo').'!");');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PATPDESPAC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O == 'I' || $O == 'A') {
          // Testa a existência do nome
          $sql = new db_getTipoDespacho_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, Nvl($_REQUEST['w_nome'], ''), null, null, 'EXISTE');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe tipo de despacho com este nome!");');
            ScriptClose();
            retornaFormulario('w_nome');
            break;
          }
          // Testa a existência do sigla
          $sql = new db_getTipoDespacho_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, null, Nvl($_REQUEST['w_sigla'], ''), null, 'EXISTE');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe tipo despacho com esta sigla!");');
            ScriptClose();
            retornaFormulario('w_sigla');
            break;
          }
        } elseif ($O == 'E') {
          $sql = new db_getTipoDespacho_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, null, null, null, 'VINCULADO');
          if (nvl(f($RS, 'existe'), 0) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Não é possível excluir esta tipo de despacho. Ele está indicado de parâmetro!");');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          }
        }
        $SQL = new dml_putTipoDespacho_PA;
        $SQL->getInstanceOf($dbms, $O, Nvl($_REQUEST['w_chave'], ''), $w_cliente, $_REQUEST['w_nome'], $_REQUEST['w_sigla'], $_REQUEST['w_descricao'], $_REQUEST['w_original'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PAESPECIE':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O == 'I' || $O == 'A') {
          // Testa a existência do nome
          $sql = new db_getEspecieDocumento_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, Nvl($_REQUEST['w_nome'], ''), null, null, 'EXISTE');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe espécie de documento com este nome!");');
            ScriptClose();
            retornaFormulario('w_nome');
            break;
          }

          // Testa a existência do sigla
          $sql = new db_getEspecieDocumento_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, null, Nvl($_REQUEST['w_sigla'], ''), null, 'EXISTE');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe espécie de documento com esta sigla!");');
            ScriptClose();
            retornaFormulario('w_sigla');
            break;
          }
        } elseif ($O == 'E') {
          $sql = new db_getEspecieDocumento_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, null, null, null, 'VINCULADO');
          if (nvl(f($RS, 'existe'), 0) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Não é possível excluir esta espécie de documento. Ele está ligado a algum documento!");');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          }
        }
        $SQL = new dml_putEspecieDocumento_PA;
        $SQL->getInstanceOf($dbms, $O, Nvl($_REQUEST['w_chave'], ''), $w_cliente, $_REQUEST['w_nome'], $_REQUEST['w_sigla'], $_REQUEST['w_assunto'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PAUNIDADE':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O == 'I' || $O == 'A') {
          if ($O == 'I') {
            $sql = new db_getUnidade_PA;
            $RS = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_chave'], null, null);
            if (count($RS) > 0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Unidade já cadastrada!");');
              ScriptClose();
              RetornaFormulario('w_chave');
              exit();
            }
          }
          if (nvl($_REQUEST['w_unidade_pai'], '') == '') {
            $sql = new db_getUnidade_PA;
            $RS = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_chave'], null, $_REQUEST['w_prefixo']);
            if (count($RS) > 0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Não é possivel definir o mesmo prefixo para duas unidades!");');
              ScriptClose();
              RetornaFormulario('w_prefixo');
              exit();
            }
          }
        }
        $SQL = new dml_putUnidade_PA;
        $SQL->getInstanceOf($dbms, $O, $w_cliente, Nvl($_REQUEST['w_chave'], ''), $_REQUEST['w_unidade_pai'], $_REQUEST['w_registra_documento'],
                $_REQUEST['w_autua_processo'], $_REQUEST['w_prefixo'], $_REQUEST['w_nr_documento'], $_REQUEST['w_nr_tramite'], $_REQUEST['w_nr_transferencia'],
                $_REQUEST['w_nr_eliminacao'], $_REQUEST['w_arquivo_setorial'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PANATUREZA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O == 'I' || $O == 'A') {
          // Testa a existência do nome
          $sql = new db_getNaturezaDoc_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, Nvl($_REQUEST['w_nome'], ''), null, null, 'EXISTE');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe natureza de documento com este nome!");');
            ScriptClose();
            retornaFormulario('w_nome');
            break;
          }

          // Testa a existência do sigla
          $sql = new db_getNaturezaDoc_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, null, Nvl($_REQUEST['w_sigla'], ''), null, 'EXISTE');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe natureza de documento com esta sigla!");');
            ScriptClose();
            retornaFormulario('w_sigla');
            break;
          }
        } elseif ($O == 'E') {
          $sql = new db_getNaturezaDoc_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, null, null, null, 'VINCULADO');
          if (nvl(f($RS, 'existe'), 0) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Não é possível excluir esta natureza de documento. Ela está ligada a algum documento!");');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          }
        }
        $SQL = new dml_putNaturezaDoc_PA;
        $SQL->getInstanceOf($dbms, $O, Nvl($_REQUEST['w_chave'], ''), $w_cliente, $_REQUEST['w_nome'], $_REQUEST['w_sigla'], $_REQUEST['w_descricao'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PATPGUARDA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O == 'I' || $O == 'A') {
          // Testa a existência da desrcricao
          $sql = new db_getTipoGuarda_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, null, Nvl($_REQUEST['w_descricao'], ''), null, null, null, null, null, 'EXISTE');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe tipo de guarda com esta desrição!");');
            ScriptClose();
            retornaFormulario('w_descricao');
            break;
          }
          // Testa a existência do sigla
          $sql = new db_getTipoGuarda_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, Nvl($_REQUEST['w_sigla'], ''), null, null, null, null, null, null, 'EXISTE');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe tipo de guarda com esta sigla!");');
            ScriptClose();
            retornaFormulario('w_sigla');
            break;
          }
        } elseif ($O == 'E') {
          $sql = new db_getTipoGuarda_PA;
          $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_chave'], ''), $w_cliente, null, null, null, null, null, null, null, 'VINCULADO');
          if (nvl(f($RS, 'existe'), 0) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Não é possível excluir este tipo de guarda. Ele está ligado a algum assunto!");');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          }
        }
        $SQL = new dml_putTipoGuarda_PA;
        $SQL->getInstanceOf($dbms, $O, Nvl($_REQUEST['w_chave'], ''), $w_cliente, $_REQUEST['w_sigla'],
                $_REQUEST['w_descricao'], $_REQUEST['w_fase_corrente'], $_REQUEST['w_fase_intermed'],
                $_REQUEST['w_fase_final'], $_REQUEST['w_destinacao_final'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PAPARAM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putPAParametro;
        $SQL->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_despacho_arqcentral'], $_REQUEST['w_despacho_desarqcentral'], 
                $_REQUEST['w_despacho_emprestimo'], $_REQUEST['w_despacho_devolucao'], $_REQUEST['w_despacho_autuar'], 
                $_REQUEST['w_despacho_arqsetorial'], $_REQUEST['w_despacho_anexar'], $_REQUEST['w_despacho_apensar'], 
                $_REQUEST['w_despacho_eliminar'], $_REQUEST['w_despacho_desmembrar'], $_REQUEST['w_arquivo_central'], 
                $_REQUEST['w_limite_interessados'], $_REQUEST['w_ano_corrente']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      }
      break;
    case 'PAASSUNTO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O == 'E') {
          $sql = new db_getAssunto_PA;
          $RS = $sql->getInstanceOf($dbms, $w_cliente, Nvl($_REQUEST['w_chave'], ''), null, null, null, null, null, null, null, null, 'VINCULADO');
          if (nvl(f($RS, 'existe'), 0) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Não é possível excluir este assunto. Ele está ligado a algum documento!");');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          }
        } elseif ($_REQUEST['w_provisorio'] == 'S' && ($O == 'I' || $O == 'A')) {
          // Só pode haver um registro para classificação provisória
          $sql = new db_getAssunto_PA;
          $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, null, null, null, null, null, 'PROVISORIO');
          foreach ($RS as $row) {
            $RS = $row;
            break;
          }
          if (count($RS) > 0 && ($O == 'I' || ($O == 'A' && f($RS, 'sq_assunto') != $_REQUEST['w_chave']))) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Só pode haver um registro para classificação provisória de assuntos!");');
            ScriptClose();
            retornaFormulario('w_assinatura');
            break;
          }
        }
        $sql = new db_getTipoGuarda_PA;
        $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_corrente_guarda'], ''), $w_cliente, null, null, null, null, null, null, null, null);
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        if (f($RS, 'sigla') == 'ANOS')
          $w_corrente_anos = $_REQUEST['w_corrente_anos'];
        else
          $w_corrente_anos = 0;
        $sql = new db_getTipoGuarda_PA;
        $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_intermed_guarda'], ''), $w_cliente, null, null, null, null, null, null, null, null);
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        if (f($RS, 'sigla') == 'ANOS')
          $w_intermed_anos = $_REQUEST['w_intermed_anos'];
        else
          $w_intermed_anos = 0;
        $sql = new db_getTipoGuarda_PA;
        $RS = $sql->getInstanceOf($dbms, Nvl($_REQUEST['w_final_guarda'], ''), $w_cliente, null, null, null, null, null, null, null, null);
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        if (f($RS, 'sigla') == 'ANOS')
          $w_final_anos = $_REQUEST['w_final_anos'];
        else
          $w_final_anos = 0;
        $SQL = new dml_putAssunto_PA;
        $SQL->getInstanceOf($dbms, $O, Nvl($_REQUEST['w_chave'], ''), $w_cliente, $_REQUEST['w_chave_pai'], $_REQUEST['w_codigo'],
                $_REQUEST['w_descricao'], $_REQUEST['w_detalhamento'], $_REQUEST['w_observacao'], $_REQUEST['w_corrente_guarda'],
                $w_corrente_anos, $_REQUEST['w_intermed_guarda'], $w_intermed_anos, $_REQUEST['w_final_guarda'],
                $w_final_anos, $_REQUEST['w_destinacao_final'], $_REQUEST['w_provisorio'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      }
      break;
    case 'PAARQUIV':
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if (!(strpos('IA', $O) === false)) {
          $sql = new db_getArquivo_PA;
          $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null, $_REQUEST['w_nome'], null, null, 'OUTROS');
          if (count($RS) > 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Nome de arquivo já cadastrado!");');
            ScriptClose();
            retornaFormulario('w_nome');
            exit;
          }
        }
        $SQL = new dml_putArquivo_PA;
        $SQL->getInstanceOf($dbms, $O, $w_cliente, $_REQUEST['w_chave'],$_REQUEST['w_nome'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        exit();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
        exit();
      }
      break;
    case 'PDLOCAIS':
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O != 'E') {
          $sql = new db_getArquivo_PA; $RS = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_chave'], null, $_REQUEST['w_nome'], null, $_REQUEST['w_chave_pai'], null);
          foreach($RS as $row) {
            if ($O=='I' ||($O=='A' && f($row,'sq_localizacao')==$_REQUEST['w_chave'] && f($row,'chave')!=$_REQUEST['w_chave_aux'])) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Local já cadastrado!");');
              ScriptClose();
              retornaFormulario('w_nome');
              exit();
            }
          }
        }
        $SQL = new dml_putArquivoLocal_PA;
        $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $_REQUEST['w_nome'], $_REQUEST['w_chave_aux'], $_REQUEST['w_chave_pai'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, $R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }


    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Bloco de dados não encontrado: '.$SG.'");');
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

    case 'CAIXA':             caixa();            break;
    case 'ALTLOCAL':          alteraLocal();      break;
    case 'IMPRIMIR':          imprimir();         break;
    case 'RENUMERA':          Renumera();         break;
    case 'TIPODESPACHO':      TipoDespacho();     break;
    case 'ESPECIEDOCUMENTO':  EspecieDocumento(); break;
    case 'UNIDADE':           Unidade();          break;
    case 'NATUREZADOC':       NaturezaDoc();      break;
    case 'TIPOGUARDA':        TipoGuarda();       break;
    case 'TELAASSUNTO':       TelaAssunto();      break;
    case 'PARAMETRO':         Parametro();        break;
    case 'ASSUNTO':           Assunto();          break;
    case 'ARQUIVO':           Arquivo();          break;
    case 'LOCAIS':            Locais();           break;
    case 'GRAVA':             Grava();            break;
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