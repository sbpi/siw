<?
include_once($w_dir_volta.'classes/sp/db_getDeficGroupList.php');
// =========================================================================
// Montagem da seleção do grupo de deficiência
// -------------------------------------------------------------------------
function selecaoGrupoDef($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getDeficGroupList::getInstanceOf($dbms, null, null);
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_grupo_defic'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_grupo_defic').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_grupo_defic').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
