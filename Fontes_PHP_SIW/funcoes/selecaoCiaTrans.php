<?php
include_once($w_dir_volta.'classes/sp/db_getCiaTrans.php');
// =========================================================================
// Montagem da sele��o de companhias de viagem
// -------------------------------------------------------------------------
function selecaoCiaTrans($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);

  $sql = new db_getCiaTrans; $RS = $sql->getInstanceOf($dbms,$cliente,null,null,null,null,null,null,null,'S',null,$restricao);
  $RS = SortArray($RS,'padrao','desc','nome','asc');
  if (!isset($hint)) {
    if ($label=='') {
      ShowHTML('          <td><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
      ShowHTML('          <td><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } 
  } else {
    if ($label=='') {
      ShowHTML('          <td><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
      ShowHTML('          <td colspan="'.$colspan.'" TITLE="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } 
  } 
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>
