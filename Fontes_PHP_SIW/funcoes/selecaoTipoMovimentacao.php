<?php
include_once($w_dir_volta . 'classes/sp/db_getTipoMovimentacao.php');

// =========================================================================
// Montagem da seleção da Caixa
// -------------------------------------------------------------------------

function selecaoTipoMovimentacao($label, $accesskey, $hint, $chave, $entrada, $saida, $campo, $restricao, $atributo, $colspan=1) {
  extract($GLOBALS);

  $l_orcamentario = null;
  $l_consumo = null;
  $l_permanente = null;
  $l_inativa_bem = null;
  $l_restricao = null;
  
  if ($restricao=='CONSUMO') {
    $l_consumo = 'S';
  } elseif (strpos($restricao,'BAIXA')!==false) {
    $l_permanente = 'S';
    $l_inativa_bem = 'S';
  } elseif ($restricao=='SAIDATEMP') {
    $l_permanente = 'S';
    $l_inativa_bem = 'N';
  }
  
  $sql = new db_getTipoMovimentacao; 
  $l_rs = $sql->getInstanceOf($dbms,$w_cliente,null,null,
                              $entrada, $saida, 
                              $l_orcamentario, $l_consumo, $l_permanente, $l_inativa_bem,
                              'S',
                              $l_restricao
                             );
  $l_rs = SortArray($l_rs,'nome','asc');
  
  ShowHTML('          <td colspan="' . $colspan . '" ' . ((isset($hint)) ? 'title="' . $hint : '') . '"><b>' . $label . '</b><br><SELECT ACCESSKEY="' . $accesskey . '" CLASS="sts" NAME="' . $campo . '" ' . $w_Disabled . ' ' . $atributo . '>');
  ShowHTML('          <option value="">---');
  foreach ($l_rs as $row) {
    ShowHTML('          <option value="' . f($row, 'chave') . '" ' . ((nvl(f($row, 'chave'), 0) == nvl($chave, 0)) ? 'SELECTED' : '') . '>' . f($row, 'nome'));
  }
  ShowHTML('          </select>');
}
?>