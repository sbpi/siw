<?php
include_once($w_dir_volta.'classes/sp/db_getCentralTel.php');
// =========================================================================
// Montagem da seleção de centrais telefônicas
// -------------------------------------------------------------------------
function selecaoCentralFone($label,$accesskey,$hint,$chave,$campo,$atributo,$colspan=1) {
  extract($GLOBALS);

  $sql = new db_getCentralTel; $RS = $sql->getInstanceOf($dbms, null, null, null, null, null);

  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'logradouro'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'logradouro'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>