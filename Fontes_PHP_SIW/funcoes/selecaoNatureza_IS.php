<?
include_once($w_dir_volta.'classes/sp/db_getNatureza_IS.php');
// =========================================================================
// Montagem da sele��o da natureza dos programas do PPA
// -------------------------------------------------------------------------
function selecaoNatureza_IS($label,$accesskey,$hint,$cliente,$chave,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getNatureza_IS::getInstanceOf($dbms,null,$cliente,null,null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
  } 
  ShowHTML('          </select>');
} 
?>