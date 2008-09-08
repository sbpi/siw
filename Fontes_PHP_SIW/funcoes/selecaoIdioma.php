<?
include_once($w_dir_volta.'classes/sp/db_getIdiomList.php');
// =========================================================================
// Montagem da seleção de idiomas
// -------------------------------------------------------------------------
function selecaoIdioma($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getIdiomList::getInstanceOf($dbms,null,'S');
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_idioma'),0)== nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_idioma').'" SELECTED>'.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_idioma').'">'.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>');
}
?>