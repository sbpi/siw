<?
include_once($w_dir_volta.'classes/sp/db_getCivStateList.php');
// =========================================================================
// Montagem da seleção de estado civil
// -------------------------------------------------------------------------
function selecaoEstadoCivil($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getCivStateList::getInstanceOf($dbms,$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_estado_civil'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_estado_civil').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_estado_civil').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>');
}
?>