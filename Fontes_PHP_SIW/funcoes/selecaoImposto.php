<?php 
include_once($w_dir_volta.'classes/sp/db_getImposto.php');
// =========================================================================
// Montagem da seleção de imposto
// -------------------------------------------------------------------------
function selecaoImposto($label,$accesskey,$hint,$chave,$cliente,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getImposto; $RS = $sql->getInstanceOf($dbms,null,$cliente);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML(' <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML(' <td colspan="'.$colspan.'" TITLE="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML(' <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    else
      ShowHTML(' <option value="'.f($row,'chave').'">'.f($row,'nome')); 
  }
  ShowHTML('          </select>');
} 
?>