<?
include_once($w_dir_volta.'classes/sp/db_getUniMedida_IS.php');
// =========================================================================
// Montagem da seleção das unidades de medidas (esquema SIGPLAN)
// -------------------------------------------------------------------------
function selecaoUniMedida_IS($label,$accesskey,$hint,$l_chave,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getUniMedida_IS::getInstanceOf($dbms,null,'S');
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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