<?
include_once($w_dir_volta.'classes/sp/db_getCivStateList.php');
// =========================================================================
// Montagem da seleção de estado civil
// -------------------------------------------------------------------------
function selecaoEstadoCivil($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getCivStateList::getInstanceOf($dbms,$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_estado_civil'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_estado_civil').'" SELECTED>'.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_estado_civil').'">'.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>');
}
?>