<?php
include_once($w_dir_volta . 'classes/sp/db_getTipoMovimentacao.php');

// =========================================================================
// Montagem da seleção da Caixa
// -------------------------------------------------------------------------

function selecaoTipoMovimentacao($label, $accesskey, $hint, $chave, $entrada, $saida, $campo, $restricao, $atributo, $colspan=1) {
  extract($GLOBALS);

  $sql = new db_getTipoMovimentacao; $l_rs = $sql->getInstanceOf($dbms,$w_cliente,null,null,$entrada,$saida,null,null,null,null,'S',$restricao);
  $l_rs = SortArray($l_rs,'nome','asc');
  ShowHTML('          <td colspan="' . $colspan . '" ' . ((isset($hint)) ? 'title="' . $hint : '') . '"><b>' . $label . '</b><br><SELECT ACCESSKEY="' . $accesskey . '" CLASS="sts" NAME="' . $campo . '" ' . $w_Disabled . ' ' . $atributo . '>');
  ShowHTML('          <option value="">---');
  foreach ($l_rs as $row) {
    ShowHTML('          <option value="' . f($row, 'chave') . '" ' . ((nvl(f($row, 'chave'), 0) == nvl($chave, 0)) ? 'SELECTED' : '') . '>' . f($row, 'nome'));
  }
  ShowHTML('          </select>');
}
?>