<?
include_once($w_dir_volta.'classes/sp/db_getFormationList.php');
// =========================================================================
// Montagem da seleção de formação acadêmica
// -------------------------------------------------------------------------
function selecaoFormacao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getFormationList::getInstanceOf($dbms,$chaveAux,null,null);
  $RS = SortArray($RS,'ordem','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_formacao'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_formacao').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_formacao').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>'); 
} 
?>