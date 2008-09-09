<?
include_once($w_dir_volta.'classes/sp/db_getDeficiencyList.php');
// =========================================================================
// Montagem da seleção de deficiência
// -------------------------------------------------------------------------
function selecaoDeficiencia($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getDeficiencyList::getInstanceOf($dbms,null,'S');
  $RS = SortArray($RS,'sq_grupo_defic','asc','nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_deficiencia'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_deficiencia').'" SELECTED>'.f($row,'sq_grupo_deficiencia').' - '.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_deficiencia').'">'.f($row,'sq_grupo_defic').' - '.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>