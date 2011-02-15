<?php
header('Expires: ' . -1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta . 'constants.inc');
include_once($w_dir_volta . 'jscript.php');
include_once($w_dir_volta . 'funcoes.php');
include_once($w_dir_volta . 'classes/db/abreSessao.php');
include_once($w_dir_volta . 'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta . 'classes/sp/db_getLinkData.php');
include_once($w_dir_volta . 'classes/sp/db_getMenuData.php');
include_once($w_dir_volta . 'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta . 'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta . 'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta . 'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta . 'classes/sp/db_getPersonData.php');
include_once($w_dir_volta . 'classes/sp/db_getUorgData.php');
include_once($w_dir_volta . 'classes/sp/db_getCountryData.php');
include_once($w_dir_volta . 'classes/sp/db_getRegionData.php');
include_once($w_dir_volta . 'classes/sp/db_getStateData.php');
include_once($w_dir_volta . 'classes/sp/db_getCityData.php');
include_once($w_dir_volta . 'classes/sp/db_getCiaTrans.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicViagem.php');
include_once($w_dir_volta . 'classes/sp/db_getPD_Fatura.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicList.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicData.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta . 'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta . 'funcoes/selecaoSolic.php');
include_once($w_dir_volta . 'funcoes/selecaoTipoPCD.php');
include_once($w_dir_volta . 'funcoes/selecaoPessoa.php');
include_once($w_dir_volta . 'funcoes/selecaoUnidade.php');
include_once($w_dir_volta . 'funcoes/selecaoCC.php');
include_once($w_dir_volta . 'funcoes/selecaoEtapa.php');
include_once($w_dir_volta . 'funcoes/selecaoCiaTrans.php');
include_once($w_dir_volta . 'funcoes/selecaoPais.php');
include_once($w_dir_volta . 'funcoes/selecaoRegiao.php');
include_once($w_dir_volta . 'funcoes/selecaoEstado.php');
include_once($w_dir_volta . 'funcoes/selecaoCidade.php');
include_once($w_dir_volta . 'funcoes/selecaoFaseCheck.php');
include_once('visualfatura.php');

// =========================================================================
//  gr_conciliacao.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o módulo de passagens e diárias
// Mail     : celso@sbpi.com.br
// Criação  : 26/05/2006 10:00
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Geração de gráfico
//                   = W   : Geração de documento no formato MS-Word (Office 2003)
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
$w_pagina = 'gr_conciliacao.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_pd/';
$w_troca = $_REQUEST['w_troca'];

if ($O == '')
  $O = 'P';

switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem';
    break;
  case 'V': $w_TP = $TP . ' - Gráfico';
    break;
  default: $w_TP = 'Listagem';
    break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = $P2;
$w_ano = RetornaAno();

/* $p_tipo = upper($_REQUEST['w_tipo']);
  $p_projeto = upper($_REQUEST['p_projeto']);
  $p_atividade = upper($_REQUEST['p_atividade']);
  $p_graf = upper($_REQUEST['p_graf']);
  $p_ativo = upper($_REQUEST['p_ativo']);
  $p_solicitante = upper($_REQUEST['p_solicitante']);
  $p_prioridade = upper($_REQUEST['p_prioridade']);
  $p_unidade = upper($_REQUEST['p_unidade']);
  $p_proponente = upper($_REQUEST['p_proponente']);
  $p_sq_prop = upper($_REQUEST['p_sq_prop']);
  $p_ordena = lower($_REQUEST['p_ordena']);
  $p_ini_i = upper($_REQUEST['p_ini_i']);
  $p_ini_f = upper($_REQUEST['p_ini_f']);
  $p_fim_i = upper($_REQUEST['p_fim_i']);
  $p_fim_f = upper($_REQUEST['p_fim_f']);
  $p_atraso = upper($_REQUEST['p_atraso']);
  $p_codigo = upper($_REQUEST['p_codigo']);
  $p_chave = upper($_REQUEST['p_chave']);
  $p_assunto = upper($_REQUEST['p_assunto']);
  $p_pais = upper($_REQUEST['p_pais']);
  $p_regiao = upper($_REQUEST['p_regiao']);
  $p_uf = upper($_REQUEST['p_uf']);
  $p_cidade = upper($_REQUEST['p_cidade']);
  $p_usu_resp = upper($_REQUEST['p_usu_resp']);
  $p_uorg_resp = upper($_REQUEST['p_uorg_resp']);
  $p_palavra = upper($_REQUEST['p_palavra']);
  $p_prazo = upper($_REQUEST['p_prazo']);
  $p_fase = explodeArray($_REQUEST['p_fase']);
  $p_sqcc = upper($_REQUEST['p_sqcc']);
  $p_agrega = upper($_REQUEST['p_agrega']); */

$p_tipo = upper($_REQUEST['w_tipo']);
$p_ordena = lower($_REQUEST['p_ordena']);
$p_agencia = $_REQUEST['p_agencia'];
$p_numero_fat = $_REQUEST['p_numero_fat'];
$p_codigo = $_REQUEST['p_codigo'];
$p_bilhete = $_REQUEST['p_bilhete'];
$p_fatura = $_REQUEST['p_fatura'];
$p_numero_fat = $_REQUEST['p_numero_fat'];
$p_arquivo = $_REQUEST['p_arquivo'];
$p_cia_trans = $_REQUEST['p_cia_trans'];
$p_solic_viagem = $_REQUEST['p_solic_viagem'];
$p_solic_pai = $_REQUEST['p_solic_pai'];
$p_numero_bil = $_REQUEST['p_numero_bil'];
$p_ini_dec = $_REQUEST['p_ini_dec'];
$p_fim_dec = $_REQUEST['p_fim_dec'];
$p_ini_emifat = $_REQUEST['p_ini_emifat'];
$p_fim_emifat = $_REQUEST['p_fim_emifat'];
$p_ini_ven = $_REQUEST['p_ini_ven'];
$p_fim_ven = $_REQUEST['p_fim_ven'];
$p_ini_emibil = $_REQUEST['p_ini_emibil'];
$p_fim_emibil = $_REQUEST['p_fim_emibil'];

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms, $w_menu);

Main();

FechaSessao($dbms);

exit;

function Conciliacao() {

  extract($GLOBALS);

  $w_pag = 1;
  $w_linha = 0;

  if ($O == 'L' || $O == 'V' || $p_tipo == 'WORD' || $p_tipo == 'PDF') {
    $w_filtro = '';
    $sql = new db_getPD_Fatura; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $p_agencia, $p_fatura, $p_bilhete, $p_numero_fat, $p_arquivo, $p_cia_trans, $p_solic_viagem,
                    $p_codigo, $p_solic_pai, $p_numero_bil, $p_ini_dec, $p_fim_dec, $p_ini_emifat, $p_fim_emifat, $p_ini_ven, $p_fim_ven,
                    $p_ini_emibil, $p_fim_emibil, 'TODOS');
    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS1 = SortArray($RS1, $lista[0], $lista[1], 'nr_fatura', 'asc', 'nm_tp_fatura', 'asc', 'cd_projeto', 'asc', 'emissao_fat', 'asc');
    } else {
      $RS1 = SortArray($RS1, 'nr_fatura', 'asc', 'nm_tp_fatura', 'asc', 'cd_projeto', 'asc', 'emissao_fat', 'asc');
    }
    if ($p_numero_fat > '') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Número da fatura<td>[<b>' . $p_numero_fat . '</b>]';
    }

    if ($p_bilhete > '') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Número do bilhete<td>[<b>' . $p_bilhete . '</b>]';
    }
    if ($p_codigo > '') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Código da viagem <td>[<b>' . $p_codigo . '</b>]';
    }
    if ($p_cia_trans > '') {
      $w_linha++;
      $sql = new db_getCiaTrans; $RS = $sql->getInstanceOf($dbms, $w_cliente, $p_cia_trans, null, null, null, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $RS = $row;
        break;
      }
      $w_filtro .= '<tr valign="top"><td align="right">Companhia de viagem<td>[<b>' . f($RS, 'nome') . '</b>]';
    }
    if ($p_agencia > '') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms, $w_cliente, $p_agencia, null, null);
      $w_filtro .= '<tr valign="top"><td align="right">Agência de viagem <td>[<b>' . f($RS, 'nome_resumido') . '</b>]';
    }
    if ($p_ini_emibil > '') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Emissão de bilhetes <td>[<b>';
      if ((nvl($p_ini_emibil, '') != '' && nvl($p_fim_emibil, '') != '') && nvl($p_ini_emibil, '') != nvl($p_fim_emibil, '')) {
        $w_filtro .= $p_ini_emibil . ' a ' . $p_fim_emibil . ' </b>]';
      } elseif ((nvl($p_ini_emibil, '') != '' && nvl($p_fim_emibil, '') != '') && nvl($p_ini_emibil, '') == nvl($p_fim_emibil, '')) {
        $w_filtro .= $p_ini_emibil . ' </b>]';
      }
    }
    if ($p_ini_emifat > '') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Emissão das faturas <td>[<b>';
      if ((nvl($p_ini_emifat, '') != '' && nvl($p_fim_emifat, '') != '') && nvl($p_ini_emifat, '') != nvl($p_fim_emifat, '')) {
        $w_filtro .= $p_ini_emifat . ' a ' . $p_fim_emifat . ' </b>]';
      } elseif ((nvl($p_ini_emifat, '') != '' && nvl($p_fim_emifat, '') != '') && nvl($p_ini_emifat, '') == nvl($p_fim_emifat, '')) {
        $w_filtro .= $p_ini_emifat . ' </b>]';
      }
    }
    if ($p_ini_ven > '') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Vencimento das faturas <td>[<b>';
      if ((nvl($p_ini_ven, '') != '' && nvl($p_fim_ven, '') != '') && nvl($p_ini_ven, '') != nvl($p_fim_ven, '')) {
        $w_filtro .= $p_ini_ven . ' a ' . $p_fim_ven . ' </b>]';
      } elseif ((nvl($p_ini_ven, '') != '' && nvl($p_fim_ven, '') != '') && nvl($p_ini_ven, '') == nvl($p_fim_ven, '')) {
        $w_filtro .= $p_ini_ven . ' </b>]';
      }
    }
    if ($p_ini_dec > '') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Decêndio das faturas <td>[<b>';
      if ((nvl($p_ini_dec, '') != '' && nvl($p_fim_dec, '') != '') && nvl($p_ini_dec, '') != nvl($p_fim_dec, '')) {
        $w_filtro .= $p_ini_dec . ' a ' . $p_fim_dec . ' </b>]';
      } elseif ((nvl($p_ini_dec, '') != '' && nvl($p_fim_dec, '') != '') && nvl($p_ini_dec, '') == nvl($p_fim_dec, '')) {
        $w_filtro .= $p_ini_dec . ' </b>]';
      }
    }
    if ($p_ativo == 'S')
      $w_filtro .= '<tr valign="top"><td align="right">Conformidade <td>[<b>Somente solicitações fora do prazo</b>]';
    if ($p_atraso == 'S')
      $w_filtro .= '<tr valign="top"><td align="right">Situação <td>[<b>Somente pendente de prestação de contas</b>]';
    if ($w_filtro > '') {
      $w_linha++;
      $w_filtro = '<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>' . $w_filtro . '</ul></tr></table>';
    }
  }

  $w_linha_filtro = $w_linha;
  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'], 'PORTRAIT') == 'PORTRAIT') ? 40 : 25);
    CabecalhoWord($w_cliente, $w_TP, $w_pag);
    $w_embed = 'WORD';
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  }elseif ($p_tipo == 'PDF') {
    $w_linha_pag = ((nvl($_REQUEST['orientacao'], 'PORTRAIT') == 'PORTRAIT') ? 25 : 25);
    $w_embed = 'WORD';
    HeaderPdf($w_TP, $w_pag);
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O == 'P') {
      ScriptOpen('Javascript');
      Modulo();
      FormataCPF();
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_codigo', 'Código da viagem', '', '', '2', '60', '1', '1');
      //Validate('p_assunto', 'Assunto', '', '', '2', '90', '1', '1');
      //Validate('p_proponente', 'Beneficiário', '', '', '2', '60', '1', '');
      //Validate('p_palavra', 'CPF', 'CPF', '', '14', '14', '', '0123456789-.');
      Validate('p_ini_emibil', 'Emissão do bilhete', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_ini_emifat', 'Emissão da fatura', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_ini_emibil.value != \'\' && theForm.p_fim_emibil.value == \'\') || (theForm.p_ini_emibil.value == \'\' && theForm.p_fim_emibil.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_emibil.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if ((theForm.p_ini_emifat.value != \'\' && theForm.p_fim_emifat.value == \'\') || (theForm.p_ini_emifat.value == \'\' && theForm.p_fim_emifat.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_emifat.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if ((theForm.p_ini_dec.value != \'\' && theForm.p_fim_dec.value == \'\') || (theForm.p_ini_dec.value == \'\' && theForm.p_fim_dec.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_dec.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if ((theForm.p_ini_ven.value != \'\' && theForm.p_fim_ven.value == \'\') || (theForm.p_ini_ven.value == \'\' && theForm.p_fim_ven.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_ven.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_ini_emifat', 'Primeira data', '<=', 'p_fim_emifat', 'Última data');
      CompData('p_ini_emibil', 'Primeira data', '<=', 'p_fim_emibil', 'Última data');
      CompData('p_ini_dec', 'Primeira data do decêndio', '<=', 'p_fim_dec', 'Última data do decêndio');
      CompData('p_ini_ven', 'Primeira data do vencimento', '<=', 'p_fim_ven', 'Última data do vencimento');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>' . $w_TP . '</TITLE>');
    }

    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    if ($w_Troca > '') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.' . $w_Troca . '.focus();\'');
    } elseif ($O == 'P') {
      if ($P1 == 1) { // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'this.focus()\';');
      }
    } else {
      BodyOpenClean('onLoad=this.focus();');
    }

    if ($O == 'L') {
      CabecalhoRelatorio($w_cliente, 'Consulta de ' . f($RS_Menu, 'nome'), 4);
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
      ShowHTML('<HR>');
      if ($w_filtro > '')
        ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
      ShowHTML('<HR>');
    }
  }

  ShowHTML('<div align=center><center>');

  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="right"><b>Registros: ' . count($RS1));
  if ($O == 'L' || $w_embed == 'WORD') {
    if ($w_embed != 'WORD') {
      ShowHTML('<tr><td>');
      if (MontaFiltro('GET') > '') {
        ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      } else {
        ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
      }
    }
    ImprimeCabecalho();
    if (count($RS1) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        //if ($w_embed == 'WORD' && $w_linha > $w_linha_pag) {
        ShowHTML('<tr bgcolor="' . $w_cor . '" valign="top">');
        if ($p_tipo != 'WORD' && $p_tipo != 'PDF') {
          ShowHTML('<td align="center">' . exibeFatura($w_cliente, f($row, 'sq_fatura_agencia'), f($row, 'nr_fatura')) . '</td>');
        } else {
          ShowHTML('<td align="center">' . f($row, 'nr_fatura') . '</td>');
        }
        ShowHTML('<td>');
        if ($p_tipo != 'WORD' || $p_tipo != 'PDF')
          ShowHTML(exibeSolic($w_dir, f($row, 'sq_projeto'), f($row, 'cd_projeto'), 'N', $w_embed));
        else
          ShowHTML('        ' . f($row, 'cd_projeto') . '');
        ShowHTML('<td>' . f($row, 'nm_tp_fatura') . '</td>');
        ShowHTML('<td>' . f($row, 'nm_agencia_res') . '</td>');
        ShowHTML('<td align="center">' . formataDataEdicao(f($row, 'emissao_fat'), 5) . '</td>');
        ShowHTML('<td align="center">' . formataDataEdicao(f($row, 'vencimento'), 5) . '</td>');
        ShowHTML('<td align="right">' . formatNumber(f($row, 'valor')) . '</td>');
        ShowHTML('<td align="center">' . formataDataEdicao(f($row, 'data_importacao'), 5) . '</td>');
        ShowHTML('<td align="center">' . f($row, 'reg_fatura') . '</td>');
        /* ShowHTML('<td align="center">'.f($row,'imp_numero_fat').'</td>');
          ShowHTML('<td align="center">'.f($row,'rej_fatura').'</td>'); */
        ShowHTML('</tr>');
        //}
      }
    }
    ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');

    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr valign="top">');
    //$sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    //SelecaoSolic('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na relação.',$w_cliente,$p_projeto,f($RS,'sq_menu'),f($RS_Menu,'sq_menu'),'p_projeto',f($RS,'sigla'),null,null,'<BR />',2);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    /* ShowHTML('   <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela viagem na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>U</U>nidade proponente:','U','Selecione a unidade proponente da viagem',$p_unidade,null,'p_unidade','VIAGEM',null);
      ShowHTML('   <tr valign="top">');
      ShowHTML('     <td><b><U>B</U>eneficiário:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
      ShowHTML('     <td><b>CP<u>F</u> do beneficiário:<br><INPUT ACCESSKEY="F" TYPE="text" class="sti" NAME="p_palavra" VALUE="'.$p_palavra.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
     */
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td colspan="2"><br><fieldset class="rh_fieldset"><legend><big>Bilhetes</big></legend>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b>Número do bilhete</b><br><input ' . $w_Disabled . ' type="text" name="p_bilhete" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_bilhete . '" title="Caso deseje pesquisar por este critério, informe o valor do mesmo.">');
    ShowHTML('     <td><b>Data de emissão do bilhete</b><br><input ' . $w_Disabled . ' type="text" name="p_ini_emibil" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini_emibil . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> a <input ' . $w_Disabled . ' type="text" name="p_fim_emibil" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim_emibil . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('   <tr valign="top">');
    SelecaoCiaTrans('Cia. Via<u>g</u>em', 'R', 'Selecione a companhia de transporte desejada.', $w_cliente, $p_cia_trans, null, 'p_cia_trans', null, null);
    ShowHTML('   </table>');
    ShowHTML('     </fieldset><br></td>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td colspan="2"><fieldset class="rh_fieldset"><legend><big>Faturas</big></legend>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b>Número da fatura</b><br><input ' . $w_Disabled . ' type="text" name="p_numero_fat" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_numero_fat . '" title="Caso deseje pesquisar por este critério, informe o valor do mesmo.">');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>C</U>ódigo da viagem:<br><INPUT ACCESSKEY="C" ' . $w_Disabled . ' class="STI" type="text" name="p_codigo" size="20" maxlength="60" value="' . $p_codigo . '"></td>');
    ShowHTML('   <tr valign="top">');
    SelecaoPessoa('Agê<u>n</u>cia de viagem:', 'N', 'Selecione a agência de viagem emissora da fatura.', $p_agencia, null, 'p_agencia', 'FORNECPD');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b>Data de emissão da fatura</b><br><input ' . $w_Disabled . ' type="text" name="p_ini_emifat" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini_emifat . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> a <input ' . $w_Disabled . ' type="text" name="p_fim_emifat" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim_emifat . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('     <td><b>Data de vencimento da fatura</b><br><input ' . $w_Disabled . ' type="text" name="p_ini_ven" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini_ven . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> a <input ' . $w_Disabled . ' type="text" name="p_fim_ven" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim_ven . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b>Período do decêndio</b><br><input ' . $w_Disabled . ' type="text" name="p_ini_dec" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini_dec . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> a <input ' . $w_Disabled . ' type="text" name="p_fim_dec" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim_dec . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('   </table>');
    ShowHTML('     </fieldset></td>');
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }

  ShowHTML('</table>');
  ShowHTML('</center>');
  if ($p_tipo == 'PDF') {

    RodapePdf();
  }
  Rodape();
}

// =========================================================================
// Rotina de impressao do cabecalho
// -------------------------------------------------------------------------
function ImprimeCabecalho() {
  extract($GLOBALS);

  ShowHTML('<tr><td align="center">');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
  if (!$p_tipo == 'WORD' || $p_tipo == 'PDF') {
    ShowHTML('          <td><b>' . LinkOrdena('Número', 'nr_fatura') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Projeto', 'cd_projeto') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Tipo', 'nm_tp_fatura') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Agência de Viagem', 'nm_agencia_res') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Data de emissão', 'emissao_fat') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Vencimento', 'vencimento') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Valor', 'valor') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Data de importação', 'data_importacao') . '</td>');
    ShowHTML('          <td colspan="3"><b>' . LinkOrdena('Registros', 'reg_fatura') . '</td>');
  } else {
    ShowHTML('          <td><b>Número</b></td>');
    ShowHTML('          <td><b>Projeto</b></td>');
    ShowHTML('          <td><b>Tipo</b></td>');
    ShowHTML('          <td><b>Agência de Viagem</b></td>');
    ShowHTML('          <td><b>Data de emissão</b></td>');
    ShowHTML('          <td><b>Vencimento</b></td>');
    ShowHTML('          <td><b>Valor</b></td>');
    ShowHTML('          <td><b>Data de importação</b></td>');
    ShowHTML('          <td colspan="3"><b>Registros</b></td>');
  }
  ShowHTML('        </tr>');
}

function Visual() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo = upper(trim(nvl($_REQUEST['w_tipo'], $_REQUEST['p_tipo'])));

  if ($w_tipo == 'PDF') {
    headerpdf('Visualização de ' . f($RS_Menu, 'nome'), $w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente, 'Visualização de ' . f($RS_Menu, 'nome'), 0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>' . $conSgSistema . ' - Visualização de fatura eletrônica</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    if ($w_tipo != 'WORD')
      CabecalhoRelatorio($w_cliente, 'Visualização de ' . f($RS_Menu, 'nome'), 4, $w_chave);
    $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualFatura($w_chave, 'L', $w_usuario, $P1, $w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  ScriptOpen('JavaScript');
  ShowHTML('  var comando, texto;');
  ShowHTML('  if (window.name!="content") {');
  ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
  ShowHTML('  }');
  ScriptClose();
  if     ($w_tipo=='PDF')  RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
    case 'VISUAL': Visual();
      break;
    case 'GERENCIAL': Gerencial();
      break;
    case 'CONCILIACAO': Conciliacao();
      break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
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
      break;
  }
}
?>
