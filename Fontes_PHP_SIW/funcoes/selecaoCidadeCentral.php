<?php
include_once($w_dir_volta.'classes/sp/db_getCentralTel.php');
// =========================================================================
// Montagem da seleção de cidade
// -------------------------------------------------------------------------
function SelecaoCidadeCentral($label,$accesskey,$hint,$chave,$campo,$atributo,$colspan=1) {
  extract($GLOBALS);

  $sql = new db_getCentralTel; $RS = $sql->getInstanceOf($dbms, null, null, $chave, null, null);
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_cidade'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_pessoa_endereco').'" SELECTED>'.f($row,'nm_cidade'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_pessoa_endereco').'">'.f($row,'nm_cidade'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>