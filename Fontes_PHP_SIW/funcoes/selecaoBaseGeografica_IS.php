<?
include_once($w_dir_volta.'classes/sp/db_getBaseGeografica_IS.php');
// =========================================================================
// Montagem da sele��o das bases geogr�ficas (esquema SIGPLAN)
// -------------------------------------------------------------------------
function selecaoBaseGeografica_IS($label,$accesskey,$hint,$l_chave,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getBaseGeografica_IS::getInstanceOf($dbms,null,'S');
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($l_chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
  } 
  ShowHTML('          </select>');
} 
?>