<?php

include_once($w_dir_volta . 'classes/sp/db_getCaixa.php');

// =========================================================================
// Montagem da seleção da Caixa
// -------------------------------------------------------------------------

function selecaoCaixa($label, $accesskey, $hint, $chave, $cliente, $chaveAux, $campo, $restricao, $atributo, $colspan=1) {
  extract($GLOBALS);
  $sql = new db_getCaixa;
  $l_rs = $sql->getInstanceOf($dbms, null, $cliente, (($restricao=='CENTRAL') ? '' : $w_usuario),$chaveAux, null, null, null, null, null, null, null, null,null,null,null,$restricao);
  $l_rs = SortArray($l_rs, 'nm_unidade', 'asc', 'numero', 'asc');
  ShowHTML('          <td colspan="' . $colspan . '" ' . ((isset($hint)) ? 'title="' . $hint : '') . '"><b>' . $label . '</b><br><SELECT ACCESSKEY="' . $accesskey . '" CLASS="sts" NAME="' . $campo . '" ' . $w_Disabled . ' ' . $atributo . '>');
  ShowHTML('          <option value="">---');
  if ($restricao == 'PREPARA') ShowHTML('          <option value="0">Nova caixa');
  foreach ($l_rs as $row) {
    ShowHTML('          <option value="' . f($row, 'sq_caixa') . '" ' . ((nvl(f($row, 'sq_caixa'), 0) == nvl($chave, 0)) ? 'SELECTED' : '') . '>' . f($row, 'numero') . '/' . f($row, 'sg_unidade'));
  }
  ShowHTML('          </select>');
}

?>