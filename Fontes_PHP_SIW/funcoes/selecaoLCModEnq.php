<?php
include_once($w_dir_volta.'classes/sp/db_getLCModEnq.php');
// =========================================================================
// Montagem da seleção da região
// -------------------------------------------------------------------------
function selecaoLCModEnq($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $l_rs = db_getLCModEnq::getInstanceOf($dbms, $chaveAux, null, null, null, null);
  $l_rs = SortArray($l_rs,'sigla','asc');

  if (!isset($hint)) {
     ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }

  ShowHTML('          <option value="">---');
  foreach($l_rs as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'sigla'));
    } else {
       ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'sigla'));
    }
  }
  ShowHTML('          </select>');
}
?>
