<?php

include_once($w_dir_volta . 'classes/sp/db_getCaixa.php');

// =========================================================================
// Montagem da seleção da Caixa
// -------------------------------------------------------------------------

function selecaoCaixa($label, $accesskey, $hint, $chave, $cliente, $chaveAux, $campo, $restricao, $atributo, $colspan=1) {
  extract($GLOBALS);
  $sql = new db_getCaixa;
  $l_rs = $sql->getInstanceOf($dbms, null, $cliente, (($restricao=='CENTRAL') ? '' : $w_usuario),$chaveAux, null, null, null, null, null, null, null, null,null,null,null,$restricao);
  $l_rs = SortArray($l_rs, 'sg_unidade', 'asc', 'numero', 'asc');
  ShowHTML('          <td colspan="' . $colspan . '" ' . ((isset($hint)) ? 'title="' . $hint : '') . '"><b>' . $label . '</b><br><SELECT ACCESSKEY="' . $accesskey . '" CLASS="sts" NAME="' . $campo . '" ' . $w_Disabled . ' ' . $atributo . '>');
  ShowHTML('          <option value="">---</option>');
  if ($restricao == 'PREPARA') {
    ShowHTML('          <option value="0">Nova caixa</option>');
  }
  foreach ($l_rs as $row) {
    ShowHTML('          <option value="' . f($row, 'sq_caixa') . '"' . ((nvl(f($row, 'sq_caixa'), 0) == nvl($chave, 0)) ? ' SELECTED' : '') . '>' . f($row, 'numero') . '/' . f($row, 'sg_unidade'));
    if ($restricao == 'PREPARA' && nvl(f($row, 'destinacao_final'),'')!='') {
      ShowHTML('            (' . upper(f($row, 'destinacao_final')) . ')');
    }
  }
  ShowHTML('          </select>');
  if ($restricao == 'PREPARA') {
    ShowHTML('          <input type="hidden" name="w_dest_cx[]" value="">');
    reset($l_rs);
    foreach ($l_rs as $row) {
      ShowHTML('          <input type="hidden" name="w_dest_cx[]" value="' . f($row, 'destinacao_final') . '">');
    }
  }
}

?>