<?php

// =========================================================================
// Rotina de visualização dos dados do cliente
// -------------------------------------------------------------------------
function visualFatura($w_sq_cliente, $p_fatura, $w_usuario, $P1, $w_embed) {
  extract($GLOBALS);

  // Carrega variáveis locais com os dados dos parâmetros recebidos
  $par = upper($_REQUEST['par']);
  $P1 = nvl($_REQUEST['P1'], 0);
  $P2 = nvl($_REQUEST['P2'], 0);
  $P3 = nvl($_REQUEST['P3'], 1);
  $P4 = nvl($_REQUEST['P4'], $conPageSize);
  $TP = $_REQUEST['TP'];
  $SG = 'PDINICIAL';
  $R = $_REQUEST['R'];
  $O = upper($_REQUEST['O']);

  $w_troca = $_REQUEST['w_troca'];
  $p_ordena = $_REQUEST['p_ordena'];

  $RS = db_getPD_Fatura::getInstanceOf($dbms, $w_cliente, $p_agencia, $p_fatura, $p_bilhete, $p_numero_fat, $p_arquivo, $p_cia_trans, $p_solic_viagem,
                  $p_codigo, $p_solic_pai, $p_numero_bil, $p_ini_dec, $p_fim_dec, $p_ini_emifat, $p_fim_emifat, $p_ini_ven, $p_fim_ven,
                  $p_ini_emibil, $p_fim_emibil, 'TODOS');



  foreach ($RS as $row) {
    $RS = $row;
    break;
  }

  $tp_fatura = f($RS, 'nm_tp_fatura');

  //Dados gerais da fatura
  $l_html.=chr(13) . '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
  $l_html.=chr(13) . '<tr><td>';
  $l_html.=chr(13) . '    <table width="99%" border="0">';
  $l_html.=chr(13) . '      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13) . '      <tr><td colspan="2" bgcolor="#f0f0f0"><font size=2><b>Fatura: ' . f($RS, 'nr_fatura') . ' - ' . f($RS, 'nm_tp_fatura') . ' (' . f($RS, 'sq_fatura_agencia') . ')</b></font></td></tr>';
  $l_html.=chr(13) . '      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13) . '      <tr><td colspan="2"><br><font size="2"><b>DADOS GERAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';

  $l_html.=chr(13) . '      <tr><td valign="top"><b>Centro de custo: </b></td>';
  if ($w_embed != 'WORD')
    $l_html.=chr(13) . '        <td>' . exibeSolic($w_dir, f($RS, 'sq_projeto'), f($RS, 'sq_projeto'), 'S') . '</td></tr>';
  else
    $l_html.=chr(13) . '        <td>' . f($RS, 'cd_projeto') . '</td></tr>';
  $l_html.=chr(13) . '      <tr><td valign="top"><b>Agência de viagem: </b></td>';
  if ($w_embed != 'WORD')
    $l_html.=chr(13) . '        <td>' . ExibeFornecedor('../', $w_cliente, f($RS, 'agencia_viagem'), $TP, f($RS, 'nm_agencia')) . '</td>';
  else
    $l_html.=chr(13) . '        <td>' . f($RS, 'nm_agencia') . '</td></tr>';
  $l_html.=chr(13) . '      <tr><td valign="top"><b>Emissão: </b></td>';
  $l_html.=chr(13) . '        <td>' . formataDataEdicao(f($RS, 'emissao_fat')) . '</td></tr>';
  $l_html.=chr(13) . '      <tr><td valign="top"><b>Vencimento: </b></td>';
  $l_html.=chr(13) . '        <td>' . formataDataEdicao(f($RS, 'vencimento')) . '</td></tr>';
  $l_html.=chr(13) . '      <tr><td valign="top"><b>Valor: </b></td>';
  $l_html.=chr(13) . '        <td>' . formatNumber(f($RS, 'valor'), 2) . '</td></tr>';
  /* $l_html.=chr(13) . '      <tr><td valign="top"><b>Valor por extenso: </b></td>';
    $l_html.=chr(13) . '        <td>' . extenso(f($RS, 'valor'), true) . '</td></tr>';
    $l_html.=chr(13) . '      <tr><td valign="top"><b>Número de registros: </b></td>';
    $l_html.=chr(13) . '        <td>' . f($RS, 'reg_fatura') . '</td></tr>'; */
  $l_html.=chr(13) . '      <tr><td valign="top"><b>Data da importação: </b></td>';
  $l_html.=chr(13) . '        <td>' . formataDataEdicao(f($RS, 'phpdt_data_importacao')) . '</td></tr>';
  $l_html.=chr(13) . '      <tr><td valign="top"><b>Responsável pela importação: </b></td>';
  if ($w_embed != 'WORD')
    $l_html.=chr(13) . '        <td>' . ExibePessoa('../', $w_cliente, f($RS, 'sq_resp_imp'), $TP, f($RS, 'nm_resp_imp')) . '</td>';
  else
    $l_html.=chr(13) . '        <td>' . f($RS, 'nm_resp_imp') . '</td>';

  //Detalhamento da fatura
  $RS = db_getPD_Fatura::getInstanceOf($dbms, $w_cliente, $p_agencia, $p_fatura, $p_bilhete, $p_numero_fat, $p_arquivo, $p_cia_trans, $p_solic_viagem,
                  $p_codigo, $p_solic_pai, $p_numero_bil, $p_ini_dec, $p_fim_dec, $p_ini_emifat, $p_fim_emifat, $p_ini_ven, $p_fim_ven,
                  $p_ini_emibil, $p_fim_emibil, 'OUTROS');

  if (nvl($p_ordena, '') > '') {
    $lista = explode(',', str_replace(' ', ',', $p_ordena));
    $RS = SortArray($RS, $lista[0], $lista[1], 'inicio_reg', 'asc', 'fim_reg', 'asc');
  } else {
    $RS = SortArray($RS, 'inicio_reg', 'fim_reg', 'nr_fatura', 'asc');
  }
  if ($w_embed == 'WORD')
    $l_html.=chr(13) . '<tr><td colspan="2">';
  $l_html.=chr(13) . '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
  $l_html.=chr(13) . '<tr><td>';
  $l_html.=chr(13) . '    <table width="99%" border="0">';
  $l_html.=chr(13) . '      <tr><td colspan="2"><br><font size="2"><b>DETALHAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
  if (strtoupper($tp_fatura) == 'OUTROS') {
    $l_html.=chr(13) . '    <tr><td align="right"><b>Registros: ' . count($RS);
    $l_html.=chr(13) . '      <tr>';
    $l_html.=chr(13) . '        <td>';
    $l_html.=chr(13) . '<TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">';
    $l_html.=chr(13) . '      <tr>';
    $l_html.=chr(13) . '        <td><b>Item</b></td>';
    if ($w_embed != 'WORD') {
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Código da viagem', 'cd_solic_viagem') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Beneficiário', 'nm_beneficiario') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Tipo', 'nm_tipo_reg') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Início', 'inicio_reg') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Término', 'fim_reg') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Valor', 'valor_reg') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Nome', 'nm_hotel') . '</td>';
    } else {
      $l_html.=chr(13) . '        <td><b>Código da viagem</b></td>';
      $l_html.=chr(13) . '        <td><b>Beneficiário</b></td>';
      $l_html.=chr(13) . '        <td><b>Tipo</b></td>';
      $l_html.=chr(13) . '        <td><b>Início</b></td>';
      $l_html.=chr(13) . '        <td><b>Término</b></td>';
      $l_html.=chr(13) . '        <td><b>Valor</b></td>';
      $l_html.=chr(13) . '        <td><b>Nome</b></td>';
    }
    $l_html.=chr(13) . '      </tr>';

    $i = 1;
    foreach ($RS as $row) {
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      //if ($w_embed == 'WORD' && $w_linha > $w_linha_pag) {
      $l_html.=chr(13) . '<tr bgcolor="' . $w_cor . '" valign="top">';
      $l_html.=chr(13) . '        <td align="center">' . $i++ . '</td>';
      $l_html.=chr(13) . '        <td align="center">';
      if ($w_embed != 'WORD') {
        $l_html.=chr(13) . exibeSolic($w_dir, f($row, 'sq_solic_viagem'), f($row, 'cd_solic_viagem'), 'N', $w_embed);
      } else {
        $l_html.=chr(13) . f($row, 'cd_solic_viagem');
      }
      $l_html.=chr(13) . '        </td>';
      if ($w_embed != 'WORD') {
        $l_html.=chr(13) . '        <td>' . ExibePessoa('../', $w_cliente, f($row, 'sq_beneficiario'), $TP, f($row, 'nm_beneficiario')) . '</td>';
      } else {
        $l_html.=chr(13) . '        <td>' . f($RS, 'nm_beneficiario') . '</td>';
      }
      $l_html.=chr(13) . '        <td>' . f($row, 'nm_tipo_reg') . '</td>';
      $l_html.=chr(13) . '        <td>' . formataDataEdicao(f($row, 'inicio_reg'), 5) . '</td>';
      $l_html.=chr(13) . '        <td>' . formataDataEdicao(f($row, 'fim_reg'), 5) . '</td>';
      $l_html.=chr(13) . '        <td>' . formatNumber(f($row, 'valor_reg'), 2) . '</td>';
      if ($w_embed != 'WORD') {
        $l_html.=chr(13) . '        <td>' . ExibeFornecedor(null, $w_cliente, f($row, 'sq_hotel'), $TP, f($row, 'nm_hotel')) . '</b></td>';
      } else {
        $l_html.=chr(13) . '        <td>' . f($RS, 'nm_hotel') . '</td>';
      }

      $l_html.=chr(13) . '      </tr>';
    }
    $l_html.=chr(13) . '    </table>';
    $l_html.=chr(13) . '        </td></tr>';
  } elseif (strtoupper($tp_fatura) == 'AÉREOS') {

    //Detalhamento da fatura
    $RS = db_getPD_Fatura::getInstanceOf($dbms, $w_cliente, $p_agencia, $p_fatura, $p_bilhete, $p_numero_fat, $p_arquivo, $p_cia_trans, $p_solic_viagem,
                    $p_codigo, $p_solic_pai, $p_numero_bil, $p_ini_dec, $p_fim_dec, $p_ini_emifat, $p_fim_emifat, $p_ini_ven, $p_fim_ven,
                    $p_ini_emibil, $p_fim_emibil, 'BILHETE');

    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'inicio_reg', 'asc', 'fim_reg', 'asc');
    } else {
      $RS = SortArray($RS, 'inicio_reg', 'fim_reg', 'nr_fatura', 'asc');
    }
    $l_html.=chr(13) . '    <tr><td align="right"><b>Registros: ' . count($RS);
    $l_html.=chr(13) . '      <tr>';
    $l_html.=chr(13) . '        <td>';
    $l_html.=chr(13) . '<TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">';
    $l_html.=chr(13) . '      <tr align="center">';
    $l_html.=chr(13) . '        <td><b>Item</b></td>';
    if ($w_embed != 'WORD') {
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Código da viagem', 'cd_solic_viagem') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Beneficiário', 'nm_beneficiario') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Cia.', 'nm_cia_transporte') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Número do bilhete', 'nr_bilhete') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Emissão do bilhete', 'emissao_bil') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Trecho', 'trecho') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Valor com desconto', 'valor_bilhete') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Valor bilhete', 'valor_bilhete_cheio') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Embarque', 'valor_taxa_embarque') . '</td>';
      $l_html.=chr(13) . '        <td>' . LinkOrdena('Taxas', 'valor_pta') . '</td>';
    } else {
      $l_html.=chr(13) . '        <td><b>Código da viagem</b></td>';
      $l_html.=chr(13) . '        <td><b>Beneficiário</b></td>';
      $l_html.=chr(13) . '        <td><b>Cia.</b></td>';
      $l_html.=chr(13) . '        <td><b>Número do bilhete</b></td>';
      $l_html.=chr(13) . '        <td><b>Emissão do bilhete</b></td>';
      $l_html.=chr(13) . '        <td><b>Trecho</b></td>';
      $l_html.=chr(13) . '        <td><b>Valor com desconto</b></td>';
      $l_html.=chr(13) . '        <td><b>Valor bilhete</b></td>';
      $l_html.=chr(13) . '        <td><b>Embarque</b></td>';
      $l_html.=chr(13) . '        <td><b>Taxas</b></td>';
    }
    $l_html.=chr(13) . '        <td><b>Total</b></td>';
    $l_html.=chr(13) . '      </tr>';

    $i = 1;
    foreach ($RS as $row) {
      $w_tot_bilhete = f($row, 'valor_bilhete_cheio') + f($row, 'valor_pta') + f($row, 'valor_taxa_embarque');
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      //if ($w_embed == 'WORD' && $w_linha > $w_linha_pag) {
      $l_html.=chr(13) . '<tr bgcolor="' . $w_cor . '" valign="top">';
      $l_html.=chr(13) . '        <td align="center">' . $i++ . '</td>';
      $l_html.=chr(13) . '        <td align="center"  nowrap>';
      if ($w_embed != 'WORD') {
        $l_html.=chr(13) . exibeSolic($w_dir, f($row, 'sq_solic_viagem'), f($row, 'cd_solic_viagem'), 'N', $w_embed);
      } else {
        $l_html.=chr(13) . f($row, 'cd_solic_viagem');
      }
      $l_html.=chr(13) . '        </td>';
      if ($w_embed != 'WORD') {
        $l_html.=chr(13) . '        <td>' . ExibePessoa('../', $w_cliente, f($row, 'sq_beneficiario'), $TP, f($row, 'nm_beneficiario')) . '</td>';
      } else {
        $l_html.=chr(13) . '        <td>' . f($RS, 'nm_beneficiario') . '</td>';
      }
      $l_html.=chr(13) . '        <td>' . f($row, 'nm_cia_transporte') . '</td>';
      $l_html.=chr(13) . '        <td align="center">' . f($row, 'nr_bilhete') . '</td>';
      $l_html.=chr(13) . '        <td align="center">' . formataDataEdicao(f($row, 'emissao_bil'), 5) . '</td>';
      $l_html.=chr(13) . '        <td align="center">' . f($row, 'trecho') . '</td>';
      $l_html.=chr(13) . '        <td align="center">' . formatNumber(f($row, 'valor_bilhete_cheio'), 2) . '</td>';
      $l_html.=chr(13) . '        <td align="center">' . formatNumber(f($row, 'valor_bilhete'), 2) . '</td>';
      $l_html.=chr(13) . '        <td align="center">' . formatNumber(f($row, 'valor_taxa_embarque'), 2) . '</td>';
      $l_html.=chr(13) . '        <td align="center">' . formatNumber(f($row, 'valor_pta'), 2) . '</td>';
      $l_html.=chr(13) . '        <td align="right">' . formatNumber($w_tot_bilhete) . '</td>';
      $l_html.=chr(13) . '      </tr>';
    }
    $l_html.=chr(13) . '    </table>';
    $l_html.=chr(13) . '        </td></tr>';
  }

  return $l_html;
}
?>
