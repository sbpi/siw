<? 
include_once($w_dir_volta.'classes/sp/db_getTipoDocumento.php');
// =========================================================================
// Montagem da sele��o de tipos de documento
// -------------------------------------------------------------------------
function selecaoTipoDocumento($label,$accesskey,$hint,$chave,$cliente,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getTipoDocumento::getInstanceOf($dbms,null,$cliente);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML(' <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML(' <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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