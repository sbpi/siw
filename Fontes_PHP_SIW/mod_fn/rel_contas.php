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
include_once($w_dir_volta . 'classes/sp/db_getLancamento.php');
include_once($w_dir_volta . 'classes/sp/db_getBenef.php');
include_once($w_dir_volta . 'funcoes/selecaoOrdenaRel.php');
// =========================================================================
//  /rel_contas.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
// Mail     : billy@sbpi.com.br
// Criacao  : 25/07/2006 08:57
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
if ($_SESSION['LOGON'] != 'Sim') {
  EncerraSessao();
}

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

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
$w_pagina = 'rel_contas.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_fn/';
$w_troca = $_REQUEST['w_troca'];
if ($O == '') {
  if ($par == 'INICIAL')
    $O = 'P'; else
    $O='L';
}
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
  case 'H': $w_TP = $TP . ' - Herança';
    break;
  default: $w_TP = $TP . ' - Listagem';
    break;
}
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relatório de contas a pagar e contas a receber
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_troca = $_REQUEST['w_troca'];
  $w_tipo_rel = upper(trim($_REQUEST['w_tipo_rel']));
  $p_dt_ini = upper(trim($_REQUEST['p_dt_ini']));
  $p_dt_fim = upper(trim($_REQUEST['p_dt_fim']));
  $p_pg_ini = upper(trim($_REQUEST['p_pg_ini']));
  $p_pg_fim = upper(trim($_REQUEST['p_pg_fim']));
  $p_co_ini = upper(trim($_REQUEST['p_co_ini']));
  $p_co_fim = upper(trim($_REQUEST['p_co_fim']));
  $p_nome = upper(trim($_REQUEST['p_nome']));
  $w_sq_pessoa = upper(trim($_REQUEST['w_sq_pessoa']));
  $p_ordena = $_REQUEST['p_ordena'];
  if (nvl($p_ordena, '') != '') {
    $lista = explode(',', str_replace(' ', ',', $p_ordena));
  }
  if ($O == 'L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms, $w_cliente);
    if (f($RS, 'logo') > '')
      $w_logo = '/img/logo' . substr(f($RS, 'logo'), (strpos(f($RS, 'logo'), '.') ? strpos(f($RS, 'logo'), '.') + 1 : 0) - 1, 30);
    // Recupera todos os registros para a listagem
    $RS = db_getLancamento::getInstanceOf($dbms, $w_cliente, substr($SG, 0, 3), $p_dt_ini, $p_dt_fim, $p_pg_ini, $p_pg_fim, $p_co_ini, $p_co_fim, $w_sq_pessoa, 'EE,ER');
    if (nvl($p_ordena, '') != '') {
      $RS = SortArray($RS, lower($lista[0]), lower($lista[1]), 'vencimento', 'asc', 'tipo', 'asc');
    } else {
      $RS = SortArray($RS, 'vencimento', 'asc', 'tipo', 'asc');
    }
  }
  if ($w_tipo_rel == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag = 1;
    $w_linha = 5;
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    if (substr($SG, 2, 1) == 'R') {
      CabecalhoWord($w_cliente, 'Contas a receber', $w_pag);
    } elseif (substr($SG, 2, 1) == 'D') {
      CabecalhoWord($w_cliente, 'Contas a pagar', $w_pag);
    }
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>Relatório de contas</TITLE>');
    if (!(strpos('P', $O) === false)) {
      ScriptOpen('JavaScript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      ShowHTML('  if (theForm.Botao.value == "Procurar") {');
      Validate('p_nome', 'Nome', '', '1', '3', '20', '1', '');
      ShowHTML('  theForm.Botao.value = "Procurar";');
      ShowHTML(' }');
      ShowHTML('else {');
      Validate('p_dt_ini', 'Vencimento inicial', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_dt_fim', 'Vencimento final', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_dt_ini.value!="" && theForm.p_dt_fim.value=="") || (theForm.p_dt_ini.value=="" && theForm.p_dt_fim.value!="")) {');
      ShowHTML('     alert ("Informe ambas as datas de vencimento ou nenhuma delas!");');
      ShowHTML('     theForm.p_dt_ini.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_dt_ini', 'Vencimento inicial', '<=', 'p_dt_fim', 'Vencimento final');
      Validate('p_pg_ini', 'Pagamento inicial', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_pg_fim', 'Pagamento final', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_pg_ini.value!="" && theForm.p_pg_fim.value=="") || (theForm.p_pg_ini.value=="" && theForm.p_pg_fim.value!="")) {');
      ShowHTML('     alert ("Informe ambas as datas de pagamento ou nenhuma delas!");');
      ShowHTML('     theForm.p_pg_ini.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_pg_ini', 'Pagamento inicial', '<=', 'p_pg_fim', 'Pagamento final');
      Validate('p_co_ini', 'Conclusão inicial', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_co_fim', 'Conclusão final', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_co_ini.value!="" && theForm.p_co_fim.value=="") || (theForm.p_co_ini.value=="" && theForm.p_co_fim.value!="")) {');
      ShowHTML('     alert ("Informe ambas as datas de conclusão ou nenhuma delas!");');
      ShowHTML('     theForm.p_co_ini.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_co_ini', 'Conclusão inicial', '<=', 'p_co_fim', 'Conclusão final');
      ShowHTML('  if (theForm.p_dt_ini.value == \'\' && theForm.p_pg_ini.value == \'\' && theForm.p_co_ini.value == \'\') {');
      ShowHTML('     alert (\'Informe pelo menos um criterio de filtragem!\');');
      ShowHTML('     theForm.p_dt_ini.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('p_ordena', 'Agregar por', 'SELECT', '1', '1', '30', '1', '1');
      ShowHTML(' }');
      ValidateClose();
      ScriptClose();
    }
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    if ($O == 'L') {
      BodyOpenClean('onLoad=\'this.focus()\';');
      if (substr($SG, 2, 1) == 'R') {
        CabecalhoRelatorio($w_cliente, 'Contas a receber', 4, $w_chave);
      } elseif (substr($SG, 2, 1) == 'D') {
        CabecalhoRelatorio($w_cliente, 'Contas a pagar', 4, $w_chave);
      }
    } else {
      BodyOpen('onLoad=\'document.Form.p_dt_ini.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
    }
    ShowHTML('<HR>');
  }
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro = '';
    if ($p_dt_ini > '')
      $w_filtro .= '<tr valign="top"><td align="right"><font size=1>Vencimento de <td><font size=1><b>' . $p_dt_ini . '</b> até <b>' . $p_dt_fim . '</b>';
    if ($p_pg_ini > '')
      $w_filtro .= '<tr valign="top"><td align="right"><font size=1>Pagamento realizado entre <td><font size=1><b>' . $p_pg_ini . '</b> e <b>' . $p_pg_fim . '</b>';
    if ($p_co_ini > '')
      $w_filtro .= '<tr valign="top"><td align="right"><font size=1>Lançamento financeiro concluído entre <td><font size=1><b>' . $p_co_ini . '</b> e <b>' . $p_co_fim . '</b>';
    if ($w_sq_pessoa > '') {
      if (substr($SG, 2, 1) == 'R') {
        $w_filtro .= '<tr valign="top"><td align="right"><font size=1>Cliente<td><font size=1>: <b>' . $p_nome . '</b>';
      } elseif (substr($SG, 2, 1) == 'D') {
        $w_filtro .= '<tr valign="top"><td align="right"><font size=1>Fornecedor<td><font size=1>: <b>' . $p_nome . '</b>';
      }
    }
    if ($p_ordena > '') {
      $w_filtro .= '<tr valign="top"><td align="right"><font size=1>Agregado por<td><font size=1>: <b>';
      switch (strtoupper($lista[0])) {
        case 'NM_PESSOA_RESUMIDO':
          if (substr($SG, 2, 1) == 'R') {
            $w_filtro .= 'Cliente';
          } elseif (substr($SG, 2, 1) == 'D') {
            $w_filtro .= 'Fornecedor';
          }
          break;
        default: $w_filtro .= 'Vencimento';
          break;
      }
      $w_filtro .= '</b>';
    }
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro > '') {
      ShowHTML('<table border=0><tr valign="top"><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>' . $w_filtro . '</ul></tr></table>');
    }
    ShowHTML('    <td align="right" valign="botton"><font size="1"><b>Registros listados: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrAlternateBgColor . '" align="center">');
    ShowHTML('          <td><font size="1"><b>' . LinkOrdena('Código', 'codigo_interno') . '</font></td>');
    ShowHTML('          <td><font size="1"><b>' . LinkOrdena('Vencto.', 'vencimento') . '</font></td>');
    ShowHTML('          <td><font size="1"><b>' . LinkOrdena('Quitação', 'quitacao') . '</font></td>');
    ShowHTML('          <td><font size="1"><b>' . LinkOrdena('Conclusão', 'phpdt_conclusao') . '</font></td>');
    if (substr($SG, 2, 1) == 'R') {
      ShowHTML('       <td><font size="1"><b>' . LinkOrdena('Cliente', 'nm_pessoa_resumido') . '</font></td>');
    } elseif (substr($SG, 2, 1) == 'D') {
      ShowHTML('       <td><font size="1"><b>' . LinkOrdena('Fornecedor', 'nm_pessoa_resumido') . '</font></td>');
    }
    ShowHTML('          <td width="45%"><font size="1"><b>' . LinkOrdena('Histórico', 'descricao') . '</font></td>');
    ShowHTML('          <td><font size="1"><b>' . LinkOrdena('Prazo', 'prazo') . '</font></td>');
    ShowHTML('          <td><font size="1"><b>' . LinkOrdena('Valor', 'valor') . '</font></td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan="8" align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_valor = 0.00;
      $w_valor_total = 0.00;
      $w_atual = '';
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        if ($w_linha > 22 && $w_tipo_rel == 'WORD') {
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha = 5;
          $w_pag += 1;
          if (substr($SG, 2, 1) == 'R') {
            CabecalhoWord($w_cliente, 'Contas a receber', $w_pag);
          } elseif (substr($SG, 2, 1) == 'D') {
            CabecalhoWord($w_cliente, 'Contas a pagar', $w_pag);
          }
          if ($w_filtro > '')
            ShowHTML('<table border=0><tr valign="top"><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>' . $w_filtro . '</ul></tr></table>');
          ShowHTML('    <td align="right" valign="botton"><font size="1"><b>Registros listados: ' . count($row));
          ShowHTML('<tr><td align="center" colspan=3>');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
          ShowHTML('        <tr bgcolor="' . $conTrAlternateBgColor . '" align="center">');
          ShowHTML('          <td><font size="1"><b>Código</font></td>');
          ShowHTML('          <td><font size="1"><b>Vencto.</font></td>');
          ShowHTML('          <td><font size="1"><b>Quitação</font></td>');
          ShowHTML('          <td><font size="1"><b>Conclusão</font></td>');
          if (substr($SG, 2, 1) == 'R') {
            ShowHTML('       <td><font size="1"><b>Cliente</font></td>');
          } elseif (substr($SG, 2, 1) == 'D') {
            ShowHTML('       <td><font size="1"><b>Fornecedor</font></td>');
          }
          ShowHTML('          <td><font size="1"><b>Histórico</font></td>');
          ShowHTML('          <td><font size="1"><b>Prazo</font></td>');
          ShowHTML('          <td><font size="1"><b>Valor</font></td>');
          ShowHTML('        </tr>');
        }
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        $lista = explode(',', str_replace(' ', ',', $p_ordena));
        if (Nvl($w_atual, '') > '') {
          switch (strtoupper($lista[0])) {
            case 'NM_PESSOA_RESUMIDO':
              if (Nvl($w_atual, '') != Nvl(f($row, 'nm_pessoa_resumido'), '')) {
                ShowHTML('      <tr bgcolor="' . $conTrAlternateBgColor . '" valign="top">');
                if (substr($SG, 2, 1) == 'R') {
                  ShowHTML('        <td colspan=7 align="right" height=18><font size="1"><b>Total do cliente: </td>');
                } elseif (substr($SG, 2, 1) == 'D') {
                  ShowHTML('        <td colspan=7 align="right" height=18><font size="1"><b>Total do fornecedor: </td>');
                }
                ShowHTML('        <td align="right"><font size="1"><b>' . number_format($w_valor, 2, ',', '.') . '</b></td>');
                ShowHTML('      </tr>');
                $w_valor = 0.00;
                $w_linha = $w_linha + 1;
              }
              break;
            default :
              if (Nvl($w_atual, '') != f($row, 'vencimento')) {
                ShowHTML('      <tr bgcolor="' . $conTrAlternateBgColor . '" valign="top">');
                ShowHTML('        <td colspan=7 align="right" height=18><font size="1"><b>Total do dia: </td>');
                ShowHTML('        <td align="right"><font size="1"><b>' . number_format($w_valor, 2, ',', '.') . '</b></td>');
                ShowHTML('      </tr>');
                $w_valor = 0.00;
                $w_linha = $w_linha + 1;
              }
              break;
          }
        }
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '" valign="top">');
        ShowHTML('        <td nowrap><font size=1>');
        if (Nvl($w_tipo_rel, '') != 'WORD') {
          if (Nvl(f($row, 'conclusao'), 'nulo') == 'nulo') {
            if (f($row, 'fim') < time()) {
              ShowHTML('           <img src="' . $conImgAtraso . '" border=0 width=15 heigth=15 align="center">');
            } elseif (f($row, 'aviso_prox_conc') == 'S' && (f($row, 'aviso') <= time())) {
              ShowHTML('           <img src="' . $conImgAviso . '" border=0 width=15 height=15 align="center">');
            } else {
              ShowHTML('           <img src="' . $conImgNormal . '" border=0 width=15 height=15 align="center">');
            }
          } else {
            if (f($row, 'vencimento') < Nvl(f($row, 'quitacao'), f($row, 'vencimento'))) {
              ShowHTML('           <img src="' . $conImgOkAtraso . '" border=0 width=15 heigth=15 align="center">');
            } else {
              ShowHTML('           <img src="' . $conImgOkNormal . '" border=0 width=15 height=15 align="center">');
            }
          }
        }
        if (Nvl($w_tipo_rel, '') == 'WORD' || nvl(f($row, 'codigo_interno'), '') == '') {
          ShowHTML('        ' . f($row, 'codigo_interno') . '&nbsp;');
        } else {
          ShowHTML('        <A class="hl" HREF="' . $w_dir . 'lancamento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exibe as informações deste registro.">' . f($row, 'codigo_interno') . '&nbsp;</a>');
        }
        ShowHTML('        <td align="center"><font size="1">' . FormataDataEdicao(f($row, 'vencimento'),5) . '</td>');
        ShowHTML('        <td align="center"><font size="1">' . Nvl(FormataDataEdicao(f($row, 'quitacao'),5), '---') . '</td>');
        ShowHTML('        <td align="center" nowrap><font size="1">' . Nvl(FormataDataEdicao(f($row, 'phpdt_conclusao'), 6), '---') . '</td>');
        ShowHTML('        <td><font size="1">' . f($row, 'nm_pessoa_resumido') . '</td>');
        if (Nvl(f($row, 'cd_acordo'), '') > '') {
          ShowHTML('        <td><font size="1">' . f($row, 'descricao') . ' - ' . f($row, 'objeto') . '</td>');
        } else {
          ShowHTML('        <td><font size="1">' . f($row, 'descricao') . '</td>');
        }
        ShowHTML('        <td align="right"><font size="1">' . Nvl(f($row, 'prazo'), '---') . '</td>');
        ShowHTML('        <td align="right" nowrap><font size="1">' . number_format(f($row, 'valor'), 2, ',', '.') . '</td>');
        ShowHTML('</tr>');
        $w_valor = $w_valor + f($row, 'valor');
        $w_valor_total = $w_valor_total + f($row, 'valor');
        $w_linha = $w_linha + 1;
        switch (strtoupper($lista[0])) {
          case 'NM_PESSOA_RESUMIDO': $w_atual = f($row, 'nm_pessoa_resumido');
            break;
          default : $w_atual = f($row, 'vencimento');
            break;
        }
      }
      ShowHTML('      <tr bgcolor="' . $conTrAlternateBgColor . '" valign="top">');
      switch (strtoupper($lista[0])) {
        case 'VENCIMENTO':
          ShowHTML('        <td colspan=7 align="right" height=18><font size="1"><b>Total do dia: </td>');
          break;
        case 'NM_PESSOA_RESUMIDO':
          if (substr($SG, 2, 1) == 'R') {
            ShowHTML('        <td colspan=7 align="right" height=18><font size="1"><b>Total do cliente: </td>');
          } elseif (substr($SG, 2, 1) == 'D') {
            ShowHTML('        <td colspan=7 align="right" height=18><font size="1"><b>Total do fornecedor: </td>');
          }
          break;
        case 'NM_TRAMITE':
          ShowHTML('        <td colspan=7 align="right" height=18><font size="1"><b>Total: <b>' . $w_atual . '</b></td>');
          break;
        default :
          ShowHTML('        <td colspan=7 align="right" height=18><font size="1"><b>Total do dia: </td>');
          break;
      }
      ShowHTML('        <td align="right" nowrap><font size="1"><b>' . number_format($w_valor, 2, ',', '.') . '</b></td>');
      ShowHTML('      </tr>');
      $w_valor = 0.00;
      $w_linha = $w_linha + 1;
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '" height=5><td colspan=7><font size=1>&nbsp;</td></tr>');
      ShowHTML('      <tr bgcolor="' . $conTrAlternateBgColor . '" valign="center" height=30>');
      ShowHTML('        <td colspan="7" align="right"><font size="2"><b>Totais do relatório: </td>');
      ShowHTML('        <td align="right" nowrap><font size="1"><b>' . number_format($w_valor_total, 2, ',', '.') . '</b></td>');
      $w_linha = $w_linha + 1;
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', 'Contas', $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table border="0">');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>V</u>encimento entre:</b><br><input ' . $w_Disabled . ' accesskey="V" type="text" name="p_dt_ini" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_dt_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_dt_ini') . ' e <input ' . $w_Disabled . ' type="text" name="p_dt_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_dt_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_dt_fim') . '</td>');
    ShowHTML('      <tr><td valign="top"><br><font size="1"><b><u>P</u>agamento entre:</b><br><input ' . $w_Disabled . ' accesskey="V" type="text" name="p_pg_ini" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_pg_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_pg_ini') . ' e <input ' . $w_Disabled . ' type="text" name="p_pg_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_pg_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_pg_fim') . '</td>');
    ShowHTML('      <tr><td valign="top"><br><font size="1"><b><u>L</u>ançamento financeiro concluído entre:</b><br><input ' . $w_Disabled . ' accesskey="V" type="text" name="p_co_ini" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_co_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_co_ini') . ' e <input ' . $w_Disabled . ' type="text" name="p_co_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_co_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_co_fim') . '</td>');
    ShowHTML('      <tr><td valign="top"><br><font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="p_nome" VALUE="' . $p_nome . '" SIZE="20" MaxLength="20">');
    ShowHTML('              <INPUT class="stb" TYPE="button" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.O.value=\'P\'; document.Form.target=\'\'; if (Validacao(document.Form)) {document.Form.submit();}">');
    if ($p_nome > '') {
      $RS = db_getBenef::getInstanceOf($dbms, $w_cliente, null, null, null, null, $p_nome, null, null, null, null, null, null, null, null);
      $RS = SortArray($RS, 'nm_pessoa', 'asc');
      ShowHTML('      <tr><td valign="top"><font size="1"><b><u>P</u>essoa:</b><br><SELECT ACCESSKEY="P" CLASS="STS" NAME="w_sq_pessoa">');
      ShowHTML('          <option value="">---');
      foreach ($RS as $row) {
        if (f($row, 'sq_tipo_pessoa') == 1) {
          ShowHTML('          <option value="' . f($row, 'sq_pessoa') . '">' . f($row, 'nome_resumido') . ' (' . Nvl(f($row, 'cpf'), '---') . ')');
        } else {
          ShowHTML('          <option value="' . f($row, 'sq_pessoa') . '">' . f($row, 'nome_resumido') . ' (' . Nvl(f($row, 'cnpj'), '---') . ')');
        }
      }
      ShowHTML('          </select>');
    }
    ShowHTML('      <tr>');
    SelecaoOrdenaRel('<u>A</u>gregado por:', 'A', null, $w_cliente, $p_ordena, 'p_ordena', $SG, null);
    ShowHTML('      </table>');
    ShowHTML('    <table width="99%" border="0">');
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
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($w_tipo_rel != 'WORD')
    Rodape(); else
    ShowHTML('</div>');
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL': Inicial();
      break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      break;
  }
}

// =========================================================================
// Fim da rotina principal
// -------------------------------------------------------------------------
?>