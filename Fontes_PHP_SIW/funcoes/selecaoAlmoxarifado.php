<?php
include_once($w_dir_volta.'classes/sp/db_getAlmoxarifado.php');
// =========================================================================
// Montagem da seleção de almoxarifado
// -------------------------------------------------------------------------
function selecaoAlmoxarifado($label, $accesskey, $hint, $chave, $campo, $restricao, $atributo, $colspan=1) {
  extract($GLOBALS);

  // Recupera todos os registros para a listagem
  $sql = new db_getAlmoxarifado; $l_rs = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,'S','OUTROS');
  $l_rs = SortArray($l_rs,'padrao','desc','nome','asc');
  
  ShowHTML('          <td colspan="' . $colspan . '" ' . ((isset($hint)) ? 'title="' . $hint : '') . '"><b>' . $label . '</b><br><SELECT ACCESSKEY="' . $accesskey . '" CLASS="sts" NAME="' . $campo . '" ' . $w_Disabled . ' ' . $atributo . '>');
  ShowHTML('          <option value="">---');
  foreach ($l_rs as $row) {
    ShowHTML('          <option value="' . f($row, 'chave') . '" ' . ((count($l_rs)==1 || nvl(f($row, 'chave'), 0)==nvl($chave, 0)) ? 'SELECTED' : '') . '>' . f($row, 'nome'));
    if (count($l_rs)==1 || nvl(f($row, 'chave'), 0)) $chave = f($row,'chave');
  }
  ShowHTML('          </select>');
} 
?>
