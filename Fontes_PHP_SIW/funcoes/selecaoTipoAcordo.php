<?
include_once($w_dir_volta.'classes/sp/db_getAgreeType.php');
// =========================================================================
// Montagem da seleção de tipos de acordo
// -------------------------------------------------------------------------
function SelecaoTipoAcordo($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getAgreeType::getInstanceOf($dbms,null,$chaveAux,$chaveAux2,$restricao);
  $RS = SortArray($RS,'nm_tipo','asc');
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_tipo_acordo'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_tipo_acordo').'" SELECTED>'.f($row,'nm_tipo'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_tipo_acordo').'">'.f($row,'nm_tipo'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>
