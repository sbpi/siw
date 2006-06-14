<?
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
// =========================================================================
// Montagem da seleção das unidades pagadoras
// -------------------------------------------------------------------------
function selecaoUnidadePag($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);


  $RS = db_getUorgList::getInstanceOf($dbms, $w_cliente, $chaveAux, 'PAGADORA', null, null, null);
  array_key_case_change(&$RS);
  $RS = SortArray($RS,'nome','asc');
  //$w_filter=" unidade_pagadora = 'S' and ativo = 'S'";
  //if ($chaveAux>'') {
  //  $w_filter=$w_filter.' and sq_unidade <> '.$chaveAux;
  //}
  //$RS->Filter=$w_filter;
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row)  {
    if (nvl(f($row,'sq_unidade'),0)==nvl($chave,0) && nvl(f($row,'sq_unidade'),0)>0) {
      ShowHTML('          <option value="'.f($row,'sq_unidade').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_unidade').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
  return $function_ret;
}
?>
