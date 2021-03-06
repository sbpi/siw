<?php
include_once($w_dir_volta.'classes/sp/db_getEtniaList.php');
// =========================================================================
// Montagem da sele��o de etnia
// -------------------------------------------------------------------------
function selecaoEtnia($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getEtniaList; $RS = $sql->getInstanceOf($dbms,null,'S');
  $RS = SortArray($RS,'codigo_siape','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_etnia'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_etnia').'" SELECTED>'.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_etnia').'">'.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>');  
}
?>