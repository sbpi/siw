<?php
header('Expires: ' . -1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta . 'constants.inc');
include_once($w_dir_volta . 'jscript.php');
include_once($w_dir_volta . 'funcoes.php');
include_once($w_dir_volta . 'classes/db/abreSessao.php');
include_once($w_dir_volta . 'classes/sp/db_getLinkData.php');
include_once($w_dir_volta . 'classes/sp/db_getMenuData.php');
include_once($w_dir_volta . 'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta . 'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicData.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicPA.php');
include_once($w_dir_volta . 'classes/sp/db_getPAElimItem.php');
include_once($w_dir_volta . 'classes/sp/db_getPAEmpItem.php');
include_once($w_dir_volta . 'classes/sp/db_getEspecieDocumento_PA.php');
include_once($w_dir_volta . 'classes/sp/db_getUnidade_PA.php');
include_once($w_dir_volta . 'classes/sp/db_getNaturezaDoc_PA.php');
include_once($w_dir_volta . 'classes/sp/db_getParametro.php');
include_once($w_dir_volta . 'classes/sp/db_getCaixa.php');
include_once($w_dir_volta . 'classes/sp/db_getAssunto_PA.php');
include_once($w_dir_volta . 'classes/sp/db_getProtocolo.php');
include_once($w_dir_volta . 'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta . 'funcoes/selecaoUnidade.php');
include_once($w_dir_volta . 'funcoes/selecaoTipoGuarda.php');
include_once($w_dir_volta . 'funcoes/selecaoAssunto.php');
include_once($w_dir_volta . 'funcoes/selecaoCaixa.php');
include_once('visualGR.php');
include_once('visualGT.php');
include_once('visualFE.php');
include_once('visualGF.php');
include_once('visualCaixa.php');

// =========================================================================
//  /relatorio.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatórios do módulo de protocolo e arquivo
// Mail     : alex@sbpi.com.br
// Criacao  : 27/02/2007, 15:25
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
$w_pagina = 'relatorio.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_pa/';
$w_troca = $_REQUEST['w_troca'];
$p_ordena = $_REQUEST['p_ordena'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O == '')
  $O = 'P';

switch ($O) {
  case 'I': $w_TP = $TP . ' - Inclusão';
    break;
  case 'A': $w_TP = $TP . ' - Alteração';
    break;
  case 'E': $w_TP = $TP . ' - Exclusão';
    break;
  case 'P': $w_TP = $TP . ' - Filtragem';
    break;
  case 'C': $w_TP = $TP . ' - Cópia';
    break;
  case 'V': $w_TP = $TP . ' - Envio';
    break;
  case 'M': $w_TP = $TP . ' - Serviços';
    break;
  case 'H': $w_TP = $TP . ' - Herança';
    break;
  case 'T': $w_TP = $TP . ' - Ativar';
    break;
  case 'D': $w_TP = $TP . ' - Desativar';
    break;
  default: $w_TP = $TP . ' - Listagem';
    break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, 'PADCAD');
$w_ano = RetornaAno();

$sql = new db_getParametro; $RS_Parametro = $sql->getInstanceOf($dbms, $w_cliente, 'PA', null);
foreach ($RS_Parametro as $row) {
  $RS_Parametro = $row;
  break;
}

$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms, $w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Emite guias de tramitação
// -------------------------------------------------------------------------
function Tramitacao() {
  extract($GLOBALS);
  global $w_Disabled;

  // Recupera as variáveis utilizadas na filtragem
  $p_protocolo = $_REQUEST['p_protocolo'];
  $p_chave = $_REQUEST['p_chave'];
  $p_chave_aux = $_REQUEST['p_chave_aux'];
  $p_prefixo = substr($p_protocolo, 0, 5);
  $p_numero = substr($p_protocolo, 6, 6);
  $p_ano = substr($p_protocolo, 13, 4);
  $p_unid_autua = $_REQUEST['p_unid_autua'];
  $p_unid_receb = $_REQUEST['p_unid_receb'];
  if ((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_'))===false) $p_unid_receb = $_SESSION['LOTACAO'];
  $p_nu_guia = $_REQUEST['p_nu_guia'];
  $p_ano_guia = $_REQUEST['p_ano_guia'];
  $p_ini = $_REQUEST['p_ini'];
  $p_fim = $_REQUEST['p_fim'];

  if ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getProtocolo; $RS = $sql->getInstanceOf($dbms, $w_menu, $w_usuario, $SG, $p_chave, $p_chave_aux,
                    $p_prefixo, $p_numero, $p_ano, $p_unid_autua, $p_unid_receb, $p_nu_guia, $p_ano_guia,
                    $p_ini, $p_fim, 2, null, null, null, null, null, null, null, null);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    } else {
      $RS = SortArray($RS, 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    }
  }
  Cabecalho();
  head();
  if ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_protocolo', 'Número de protocolo', '1', '', '20', '20', '', '0123456789./-');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif ($O == 'P') {
    BodyOpen('onLoad=\'document.Form.p_protocolo.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=2 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Selecione a guia desejada para impressão, clicando sobre a operação <i>Emitir</i>.');
    ShowHTML('  <li>A impressão não ocorre diretamente. Será gerado um arquivo no formato Word, que você poderá enviar para a impressora.');
    ShowHTML('  <li>ATENÇÃO: recomenda-se salvar o arquivo gerado, ao invés de abri-lo diretamente.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td nowrap>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right" nowrap>'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=2>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>' . linkOrdena('Guia', 'guia_tramite') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Destino', 'nm_destino') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Despacho', 'nm_despacho') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Protocolo', 'protocolo') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Envio', 'phpdt_envio') . '</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        if ($w_atual == '' || $w_atual != f($row, 'guia_tramite')) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('        <td>' . f($row, 'guia_tramite') . '</td>');
          ShowHTML('        <td>' . f($row, 'nm_destino') . '</td>');
          ShowHTML('        <td>' . f($row, 'nm_despacho') . '</td>');
          ShowHTML('        <td align="center"><A class="HL" HREF="' . $w_dir . 'documento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
          ShowHTML('        <td align="center">' . substr(formataDataEdicao(f($row, 'phpdt_envio'),6),0,-3) . '</td>');
          ShowHTML('        <td align="top" nowrap class="remover">');
          ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'EmitirGR&R=' . $w_pagina . $par . '&O=L&w_nu_guia=' . f($row, 'nu_guia') . '&w_ano_guia=' . f($row, 'ano_guia') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '" target="GR">Emitir</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
          $w_atual = f($row, 'guia_tramite');
        } else {
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td align="center"><A class="HL" HREF="' . $w_dir . 'documento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a></td>');
          ShowHTML('        <td align="center">' . substr(formataDataEdicao(f($row, 'phpdt_envio'), 6),0,-3) . '</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('      </tr>');
        }
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=2>');
    MontaBarra($w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=' . $O . '&P1=' . $P1 . '&P2=' . $P2 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>P</u>rotocolo:</b><br><input ' . $w_Disabled . ' accesskey="P" type="text" name="p_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="' . $p_protocolo . '" onKeyDown="FormataProtocolo(this,event);"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que detém a posse do protocolo:', 'U', 'Selecione a unidade de posse.', $p_unid_receb, null, 'p_unid_receb', 'MOD_PA', null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Perío<u>d</u>o entre:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input ' . $w_Disabled . ' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
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
  Rodape();
}

// =========================================================================
// Emite guias de transferência
// -------------------------------------------------------------------------
function Transferencia() {
  extract($GLOBALS);
  global $w_Disabled;
  global $p_unid_autua;
  
  // Recupera as variáveis utilizadas na filtragem
  $p_protocolo = $_REQUEST['p_protocolo'];
  $p_chave = $_REQUEST['p_chave'];
  $p_unid_autua = $_REQUEST['p_unid_autua'];
  if ((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_'))===false) $p_unid_autua = $_SESSION['LOTACAO'];
  $p_nu_guia = $_REQUEST['p_nu_guia'];
  $p_ano_guia = $_REQUEST['p_ano_guia'];
  $p_ini = $_REQUEST['p_ini'];
  $p_fim = $_REQUEST['p_fim'];

  if ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCaixa; $RS = $sql->getInstanceOf($dbms, $p_chave, $w_cliente, $w_usuario, null, null, null, $p_unid_autua, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, $SG);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'sg_unidade', 'asc', 'numero', 'asc', 'pasta', 'asc', 'cd_assunto', 'asc', 'protocolo', 'asc');
    } else {
      $RS = SortArray($RS, 'sg_unidade', 'asc', 'numero', 'asc', 'pasta', 'asc', 'cd_assunto', 'asc', 'protocolo', 'asc');
    }
  }
  Cabecalho();
  head();
  if ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif ($O == 'P') {
    BodyOpen('onLoad=\'document.Form.w_caixa.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Selecione a guia desejada para impressão, clicando sobre a operação <i>Emitir</i>.');
    ShowHTML('  <li>A impressão não ocorre diretamente. Será gerado um arquivo no formato Word, que você poderá enviar para a impressora.');
    ShowHTML('  <li>ATENÇÃO: recomenda-se salvar o arquivo gerado, ao invés de abri-lo diretamente.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>' . linkOrdena('Guia', 'arquivo_guia_numero') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Ano', 'arquivo_guia_ano') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Caixa', 'numero') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Assunto', 'assunto') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Protocolos', 'qtd') . '</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        if ($w_atual == '' || $w_atual != f($row, 'guia_tramite')) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('        <td>' . f($row, 'arquivo_guia_numero') . '</td>');
          ShowHTML('        <td>' . f($row, 'arquivo_guia_ano') . '</td>');
          ShowHTML('        <td><A onclick="window.open (\'' . montaURL_JS($w_dir, 'relatorio.php?par=ConteudoCaixa' . '&R=' . $w_pagina . 'IMPRIMIR' . '&O=L&w_chave=' . f($row, 'sq_caixa') . '&w_formato=HTML&orientacao=PORTRAIT&&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">' . f($row, 'numero') . '/' . f($row, 'sg_unidade') . '</a>&nbsp;');
          ShowHTML('        <td>' . f($row, 'assunto') . '</td>');
          ShowHTML('        <td align="center">' . f($row, 'qtd') . '</td>');
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'EmitirGT&R=' . $w_pagina . $par . '&O=L&w_unidade=' . f($row, 'sq_unidade') . '&w_formato=WORD&orientacao=PORTRAIT&w_nu_guia=' . f($row, 'arquivo_guia_numero') . '&w_ano_guia=' . f($row, 'arquivo_guia_ano') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '" target="GT">Emitir</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
          $w_atual = f($row, 'guia_tramite');
        } else {
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td><A onclick="window.open (\'' . montaURL_JS($w_dir, 'relatorio.php?par=ConteudoCaixa' . '&R=' . $w_pagina . 'IMPRIMIR' . '&O=L&w_chave=' . f($row, 'sq_caixa') . '&w_formato=HTML&orientacao=PORTRAIT&&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">' . f($row, 'numero') . '/' . f($row, 'sg_unidade') . '</a>&nbsp;');
          ShowHTML('        <td>' . f($row, 'assunto') . '</td>');
          ShowHTML('        <td align="center">' . f($row, 'qtd') . '</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('      </tr>');
        }
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=' . $O . '&P1=' . $P1 . '&P2=' . $P2 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    SelecaoCaixa('<u>C</u>aixa:', 'C', "Selecione a caixa transferida.", $w_caixa, $w_cliente, null, 'w_caixa', 'TRAMITE', null);
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade original da caixa:', 'U', 'Selecione a unidade que transferiu a caixa.', $p_unid_autua, null, 'p_unid_autua', 'MOD_PA', null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Perío<u>d</u>o entre:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input ' . $w_Disabled . ' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
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
  Rodape();
}

// =========================================================================
// Rotina de visualização do conteúdo de uma caixa
// -------------------------------------------------------------------------
function ConteudoCaixa() {
  extract($GLOBALS);
  $w_chave   = $_REQUEST['w_chave'];
  $w_formato = $_REQUEST['w_formato'];
  $w_espelho = nvl($_REQUEST['w_espelho'], 'N');
  $w_tipo = upper(trim($_REQUEST['w_tipo']));
  
  if ($w_tipo == 'PDF') {
    headerpdf('Visualização de ' . f($RS_Menu, 'nome'), $w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='EXCEL') {
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0,1,7);
    $w_embed = 'WORD';
  } elseif ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente, 'Visualização de ' . f($RS_Menu, 'nome'), 0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<title>' . $conSgSistema . ' - Visualização de Caixa</title>');
    ShowHTML('</head>');
    ShowHTML('<base HREF="' . $conRootSIW . '">');
    BodyOpenClean('onLoad="this.focus();" ');
    if ($w_tipo != 'WORD')
      CabecalhoRelatorio($w_cliente, 'Visualização de ' . f($RS_Menu, 'nome'), 4, $w_chave);
    $w_embed = 'HTML';
  }
  ShowHTML(VisualCaixa($w_chave, $w_embed, $w_espelho));
  ShowHTML('</body>');
  ShowHTML('</html>');
  if ($w_tipo=='PDF') RodapePDF();
  else                Rodape();
}

// =========================================================================
// Emite etiqueta de processo
// -------------------------------------------------------------------------
function Etiqueta() {
  extract($GLOBALS);
  global $w_Disabled;

  // Recupera as variáveis utilizadas na filtragem
  $p_posicao        = $_REQUEST['p_posicao'];
  $w_volume         = $_REQUEST['w_volume'];
  $p_chave          = $_REQUEST['p_chave'];
  $p_chave_aux      = $_REQUEST['p_chave_aux'];
  $p_prefixo        = $_REQUEST['p_prefixo'];
  $p_numero         = $_REQUEST['p_numero'];
  $p_ano            = $_REQUEST['p_ano'];
  
  if (nvl($_REQUEST['p_protocolo'],'')!='' && nvl($p_numero,'')=='') {
    $l_protocolo = explode('/',$_REQUEST['p_protocolo']);
    $p_numero = $l_protocolo[0];
    $p_ano    = $l_protocolo[1];
    if (strlen($p_ano)==2) $p_ano = '20'.$p_ano;
  }

  if ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getProtocolo; $RS = $sql->getInstanceOf($dbms, $w_menu, $w_usuario, $SG, $p_chave, $p_chave_aux,
                    $p_prefixo, $p_numero, $p_ano, null, null, null, null, null, null, 2, null, null, null, null, 
                    null, null, null, null);
    $RS = SortArray($RS, 'sg_unidade', 'asc', 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
  }
  Cabecalho();
  head();
  if ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
    Validate('p_numero', 'Número', '1', '1', '1', '6', '', '0123456789');
    Validate('p_ano', 'Ano', '1', '1', '4', '4', '', '0123456789');
    Validate('w_volume', 'Número do volume', '1', '', '1', '2', '', '0123456789');
//     ShowHTML('  var i; ');
//     ShowHTML('  var w_erro=true; ');
//     ShowHTML('  for (i=0; i < theForm["p_posicao"].length; i++) {');
//     ShowHTML('    if (theForm["p_posicao"][i].checked) w_erro=false;');
//     ShowHTML('  }');
//     ShowHTML('  if (w_erro) {');
//     ShowHTML('    alert(\'Indique a posição de impressão da etiqueta!\'); ');
//     ShowHTML('    return false;');
//     ShowHTML('  }');
    ShowHTML('  if (theForm["w_volume"].value != \'\' && theForm["w_volume"].value <= 0) {');
    ShowHTML('    alert(\'O número de volume não deve ser menor ou igual a 0.\'); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif ($O == 'P') {
    BodyOpen('onLoad=\'document.Form.p_numero.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Verifique se este é realmente o documento que deseja imprimir a etiqueta e clique sobre o botão <i>Emitir</i>.');
    ShowHTML('  <li>A impressão não ocorre diretamente. Será gerado um arquivo no formato Word, que você poderá enviar para a impressora.');
    ShowHTML('  <li>ATENÇÃO: recomenda-se salvar o arquivo gerado, ao invés de abri-lo diretamente.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td rowspan=2><b>Último despacho</td>');
    ShowHTML('          <td rowspan=2><b>Protocolo</td>');
    ShowHTML('          <td rowspan=2><b>Unidade de Origem</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2><b>Limite</td>');
    ShowHTML('          <td rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Espécie</td>');
    ShowHTML('          <td><b>Nº</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>Procedência</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=9 align="center"><font size=3><b>O protocolo informado não foi encontrado ou não é um processo.</b></font></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td>' . f($row, 'nm_despacho') . '</td>');
        ShowHTML('        <td align="center"><A class="HL" HREF="' . $w_dir . 'documento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
        ShowHTML('        <td>' . f($row, 'nm_unid_origem') . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
        ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
        ShowHTML('        <td align="center">' . date(d . '/' . m . '/' . y, f($row, 'inicio')) . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
        ShowHTML('        <td align="center">' . ((nvl(f($row, 'fim'), '') != '') ? date(d . '/' . m . '/' . y, f($row, 'fim')) : '&nbsp;') . '</td>');
        ShowHTML('        <td align="top" nowrap>');

        // Configura o texto que informa ao usuário a posição de impressão da etiqueta
//         if ($p_posicao=='S') $w_texto = 'Emitir na parte superior da folha';
//         elseif ($p_posicao=='M') $w_texto = 'Emitir no meio da folha';
//         else $w_texto = 'Emitir na parte inferior da folha';

        ShowHTML('          <A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS($w_dir, $w_pagina . 'EmitirEtiqueta&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_posicao=' . $p_posicao . '&w_volume=' . $w_volume . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\',\'Etiqueta\',\'width=500,height=240,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=yes\');" title="Prepara etiqueta para impressão.">Emitir</A>&nbsp');
        //ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'EmitirEtiqueta&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_posicao='.$p_posicao.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Emitir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_chave = f($row, 'sq_siw_solicitacao');
        $w_atual = f($row, 'guia_tramite');
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1) > 0) {
      ShowHTML('<tr><td align="center" colspan=3><br><br><br><br>');
      // Recupera os dados do cliente
      $sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
      // Recupera os dados do documento
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, 'PADGERAL');
      ShowHTML('<table cellpadding=0 cellspacing=0 border=1>');
      ShowHTML('<tr><td width="480"  height="200">');
      ShowHTML('  <table width="100%" cellpadding=3 cellspacing=0 border=0>');
      ShowHTML('    <tr><td colspan=2><font size=2><b>' . f($RS_Cliente, 'nome_resumido') . '/' . f($RS, 'sg_unidade_resp') . '</b></font>');
      ShowHTML('    <tr><td><font size=2><b>'.((nvl(f($RS, 'processo'),'')=='S') ? 'PROCESSO' : 'DOCUMENTO').': </b>'.f($RS, 'protocolo_completo').'</font></td>');
      If (nvl($_REQUEST['w_volume'], '') != '') {
        ShowHTML('<td rowspan="2" class="volume" width="28%"><h1>');
        if (strlen($_REQUEST['w_volume']) > 1) {
          echo 'VOL.' . $_REQUEST['w_volume'];
        } else {
          echo 'VOL. ' . $_REQUEST['w_volume'];
        }
        ShowHTML('</h1></td>');
      }
      if (nvl(f($RS,'protocolo_siw'),'')!='') {
        $sql = new db_getSolicData; $RS_Vinc = $sql->getInstanceOf($dbms,f($RS,'protocolo_siw'),'PADCAD');
        if (nvl(f($RS,'copias'),0)>0) {
          if (f($RS_Vinc,'processo')=='S') $w_tipo_vinc = 'CÓPIA '.f($RS,'copias').' DO DOCUMENTO ';
          else                             $w_tipo_vinc = 'CÓPIA '.f($RS,'copias').' DO PROCESSO ';
        } else {
          if (f($RS_Vinc,'processo')=='S') $w_tipo_vinc = 'VINCULADO AO PROCESSO: ';
          else                             $w_tipo_vinc = 'VINCULADO AO DOCUMENTO: ';
        }
        $w_tipo_vinc.=f($RS_Vinc,'protocolo_completo');
        ShowHTML('      <tr><td nowrap><font size=2><b>'.$w_tipo_vinc.'</b></font></td>');
      }
      ShowHTML('<td rowspan="3" width="5%">&nbsp;</td>');
      if (nvl(f($RS, 'processo'), '') == 'S')
        ShowHTML('        <tr><td nowrap><font size=2><b>AUTUAÇÃO: </b>' . formataDataEdicao(f($RS, 'data_autuacao')) . '</font>');
      else
        ShowHTML('        <tr><td align="left" nowrap><font size=2><b>REGISTRO: </b>' . formataDataEdicao(f($RS, 'inclusao')) . '</font>');
      if (nvl(f($RS, 'nm_pessoa_interes'), '') != '' && f($RS, 'interno')=='N') {
        ShowHTML('    <tr><td colspan=2><font size=1><b>INTERESSADO: </b>' . upper(f($RS, 'nm_pessoa_interes')) . '</font>');
      } elseif (nvl(f($RS, 'processo'), '') == 'S') {
        ShowHTML('    <tr><td colspan=2><font size=1><b>INTERESSADO: </b>' . upper(f($RS, 'nm_unidade_autua')) . '</font>');
      } else {
        ShowHTML('    <tr><td colspan=2><font size=1><b>INTERESSADO: </b>' . upper(nvl(f($RS, 'nm_origem'),'---')) . '</font>');
      }
      ShowHTML('    <tr><td colspan=2><font size=1><b>CLASSIFICAÇÃO ARQUIVÍSTICA: </b>' . f($RS, 'cd_assunto') . ' - ' . upper(f($RS, 'ds_assunto')) . '</font>');
      if (strlen(Nvl(f($RS, 'descricao'), '-')) > 1000)
        ShowHTML('    <tr><td colspan=2><font size=1><b>ASSUNTO: </b>' . substr(upper(nvl(f($RS, 'descricao'), '---')), 0, 1000) . '...</font>');
      else
        ShowHTML('    <tr><td colspan=2><font size=1><b>ASSUNTO: </b>' . upper(nvl(f($RS, 'descricao'), '---')) . '</font>');
      ShowHTML('    <tr><td colspan=2 align="right">' . geraCB(str_replace('/', '', str_replace('-', '', str_replace('.', '', f($RS, 'protocolo_completo'))))));
      ShowHTML('  </table>');
      ShowHTML('<br></td></tr>');
      ShowHTML('</table>');
      ShowHTML('</tr>');
    }
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe o número do processo, a posição da folha em que deseja imprimir a etiqueta e clique sobre a operação <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Após verificar se o protocolo informado existe e que é um processo, o sistema permitirá a impressão da etiqueta.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <td align="left"><b><u>V</u>olume:</b><br><input ' . $w_Disabled . ' accesskey="V" type="text" name="w_volume" class="sti" SIZE="3" MAXLENGTH="2" VALUE="' . $w_volume . '" onKeyDown="FormataProtocolo(this,event);"></td>');
//     ShowHTML('      <tr><td><b>Posição da etiqueta');
//     if ($p_posicao=='S') ShowHTML('<br><input checked type="radio" name="p_posicao" class="STR" VALUE="S"> Etiqueta localizada na parte superior da folha'); else ShowHTML('<br><input type="radio" name="p_posicao" class="STR" VALUE="S"> Etiqueta localizada na parte superior da folha');
//     if ($p_posicao=='M') ShowHTML('<br><input checked type="radio" name="p_posicao" class="STR" VALUE="M"> Etiqueta localizada no meio da folha'); else ShowHTML('<br><input type="radio" name="p_posicao" class="STR" VALUE="M"> Etiqueta localizada no meio da folha');
//     if ($p_posicao=='I') ShowHTML('<br><input checked type="radio" name="p_posicao" class="STR" VALUE="I"> Etiqueta localizada na parte inferior da folha'); else ShowHTML('<br><input type="radio" name="p_posicao" class="STR" VALUE="I"> Etiqueta localizada na parte inferior da folha');
    ShowHTML('      <tr><td colspan="2" align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
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
  Rodape();
}

// =========================================================================
// Rotina de visualização da guia de tramitação
// -------------------------------------------------------------------------
function EmitirGR() {
  extract($GLOBALS);
  $w_unidade = nvl($w_unidade, $_REQUEST['w_unidade']);
  $w_nu_guia = nvl($w_nu_guia, $_REQUEST['w_nu_guia']);
  $w_ano_guia = nvl($w_ano_guia, $_REQUEST['w_ano_guia']);
  $w_formato = $_REQUEST['w_formato'];

  if ($w_formato == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
  } else {
    Cabecalho();
    BodyOpen('onLoad=\'this.focus();\'');
  }
  ShowHTML(VisualGR($w_unidade, $w_nu_guia, $w_ano_guia));
  Rodape();
}

// =========================================================================
// Rotina de visualização do formulário de empréstimo
// -------------------------------------------------------------------------
function EmitirFE() {
  extract($GLOBALS);
  $w_chave = $_REQUEST['w_chave'];
  $w_formato = $_REQUEST['w_formato'];

  if ($w_formato == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
  } else {
    Cabecalho();
    BodyOpen(null);
  }
  ShowHTML(VisualFE($w_chave));
  Rodape();
}

// =========================================================================
// Rotina de visualização da guia fora
// -------------------------------------------------------------------------
function EmitirGF() {
  extract($GLOBALS);
  $w_chave = $_REQUEST['w_chave'];
  $w_formato = $_REQUEST['w_formato'];

  if ($w_formato == 'WORD') {
    HeaderWord('PORTRAIT');
    BodyOpenWord(null);
  } else {
    Cabecalho();
    BodyOpen(null);
  }
  ShowHTML(VisualGF($w_chave));
  Rodape();
}

// =========================================================================
// Rotina de visualização da guia de transferência
// -------------------------------------------------------------------------
function EmitirGT() {
  extract($GLOBALS);
  $w_unidade = nvl($w_unidade, $_REQUEST['w_unidade']);
  $w_nu_guia = nvl($w_nu_guia, $_REQUEST['w_nu_guia']);
  $w_ano_guia = nvl($w_ano_guia, $_REQUEST['w_ano_guia']);
  $w_formato = $_REQUEST['w_formato'];

  if ($w_formato == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
  } else {
    Cabecalho();
    BodyOpen(null);
  }
  ShowHTML(VisualGT($w_unidade, $w_nu_guia, $w_ano_guia, $formato));
  Rodape();
}

// =========================================================================
// Rotina de visualização da etiqueta
// -------------------------------------------------------------------------
function EmitirEtiqueta() {
  extract($GLOBALS);

//exibevariaveis();
  $w_chave = $_REQUEST['w_chave'];
  $w_posicao = $_REQUEST['w_posicao'];

  $w_altura = 250;
  $w_largura = 400;

  // Recupera os dados do cliente
  $sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
  // Recupera os dados do documento
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, 'PADGERAL');
  //HeaderEtiqueta();
  Cabecalho();
  ShowHTML('<style>');
  ShowHTML('.volume{');
  ShowHTML('border:solid thin darkgray;');
  ShowHTML('text-align: center;');
  ShowHTML('vertical-align: middle;');
  ShowHTML('}');
  ShowHTML('.volume h2{');
  ShowHTML('margin-bottom: auto;');
  ShowHTML('margin-right: auto;');
  ShowHTML('margin-top: auto;');
  ShowHTML('}');
  ShowHTML('.etiqueta td{');
  ShowHTML('line-height: 150%;');
  ShowHTML('}');
  ShowHTML('</style>');
  ShowHTML('<table cellpadding=0 cellspacing=0 border=0>');
  ShowHTML('<tr><td width="480"  height="200">');
  ShowHTML('  <table width="100%" cellpadding=3 cellspacing=0 border=0>');
  ShowHTML('    <tr><td colspan=2><font size=2><b>' . f($RS_Cliente, 'nome_resumido') . '/' . f($RS, 'sg_unidade_resp') . '</b></font>');
  ShowHTML('    <tr><td><font size=2><b>'.((nvl(f($RS, 'processo'),'')=='S') ? 'PROCESSO' : 'DOCUMENTO').': </b>'.f($RS, 'protocolo_completo').'</font></td>');
  If (nvl($_REQUEST['w_volume'], '') != '') {
    ShowHTML('<td rowspan="2" class="volume" width="28%"><h2>');
    if (strlen($_REQUEST['w_volume']) > 1) {
      echo 'VOL.' . $_REQUEST['w_volume'];
    } else {
      echo 'VOL. ' . $_REQUEST['w_volume'];
    }
    ShowHTML('</h2></td>');
  }
  if (nvl(f($RS,'protocolo_siw'),'')!='') {
    $sql = new db_getSolicData; $RS_Vinc = $sql->getInstanceOf($dbms,f($RS,'protocolo_siw'),'PADCAD');
    if (nvl(f($RS,'copias'),0)>0) {
      if (f($RS_Vinc,'processo')=='S') $w_tipo_vinc = 'CÓPIA '.f($RS,'copias').' DO DOCUMENTO ';
      else                             $w_tipo_vinc = 'CÓPIA '.f($RS,'copias').' DO PROCESSO ';
    } else {
      if (f($RS_Vinc,'processo')=='S') $w_tipo_vinc = 'VINCULADO AO PROCESSO: ';
      else                             $w_tipo_vinc = 'VINCULADO AO DOCUMENTO: ';
    }
    $w_tipo_vinc.=f($RS_Vinc,'protocolo_completo');
    ShowHTML('      <tr><td nowrap><font size=2><b>'.$w_tipo_vinc.'</b></font></td>');
  }
  ShowHTML('<td rowspan="3" width="5%">&nbsp;</td>');
  if (nvl(f($RS, 'processo'), '') == 'S')
    ShowHTML('        <tr><td nowrap><font size=2><b>AUTUAÇÃO: </b>' . formataDataEdicao(f($RS, 'data_autuacao')) . '</font>');
  else
    ShowHTML('        <tr><td align="left" nowrap><font size=2><b>REGISTRO: </b>' . formataDataEdicao(f($RS, 'inclusao')) . '</font>');
  if (nvl(f($RS, 'nm_pessoa_interes'), '') != '' && f($RS, 'interno')=='N') {
    ShowHTML('    <tr><td colspan=2><font size=1><b>INTERESSADO: </b>' . upper(f($RS, 'nm_pessoa_interes')) . '</font>');
  } elseif (nvl(f($RS, 'processo'), '') == 'S') {
    ShowHTML('    <tr><td colspan=2><font size=1><b>INTERESSADO: </b>' . upper(f($RS, 'nm_unidade_autua')) . '</font>');
  } else {
    ShowHTML('    <tr><td colspan=2><font size=1><b>INTERESSADO: </b>' . upper(nvl(f($RS, 'nm_origem'),'---')) . '</font>');
  }
  ShowHTML('    <tr><td colspan=2><font size=1><b>CLASSIFICAÇÃO ARQUIVÍSTICA: </b>' . f($RS, 'cd_assunto') . ' - ' . upper(f($RS, 'ds_assunto')) . '</font>');
  if (strlen(Nvl(f($RS, 'descricao'), '-')) > 1000)
    ShowHTML('    <tr><td colspan=2><font size=1><b>ASSUNTO: </b>' . substr(upper(nvl(f($RS, 'descricao'), '---')), 0, 1000) . '...</font>');
  else
    ShowHTML('    <tr><td colspan=2><font size=1><b>ASSUNTO: </b>' . upper(nvl(f($RS, 'descricao'), '---')) . '</font>');
  ShowHTML('    <tr><td colspan=2 align="right">' . geraCB(str_replace('/', '', str_replace('-', '', str_replace('.', '', f($RS, 'protocolo_completo'))))));
  ShowHTML('  </table>');
  ShowHTML('</td></tr>');
  ShowHTML('</table>');
  ShowHTML('</body>');
  ShowHTML('</html>');
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'ETIQUETA': Etiqueta();
      break;
    case 'EMITIRETIQUETA': EmitirEtiqueta();
      break;
    case 'CONTEUDOCAIXA': ConteudoCaixa();
      break;
    case 'TRAMITE': Tramitacao();
      break;
    case 'TRANSFERENCIA': Transferencia();
      break;
    case 'EMITIRGR': EmitirGR();
      break;
    case 'EMITIRGT': EmitirGT();
      break;
    case 'EMITIRGF': EmitirGF();
      break;
    case 'EMITIRFE': EmitirFE();
      break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      exibevariaveis();
      break;
  }
}
?>