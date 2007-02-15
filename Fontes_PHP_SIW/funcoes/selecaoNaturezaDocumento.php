<?
include_once($w_dir_volta.'classes/sp/db_getNaturezaDoc_PA.php');
// =========================================================================
// Montagem da seleção de naturezas do documento
// -------------------------------------------------------------------------
function selecaoNaturezaDocumento($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getNaturezaDoc_PA::getInstanceOf($dbms,null,$w_cliente,null,null,'S',null);
  $RS = SortArray($RS,'nome','asc');  
  if (!isset($hint)) ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else               ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) { 
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome')); 
    } else { 
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome')); 
    }
  }
  ShowHTML('          </select>');
}
?>
