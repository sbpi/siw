<?
include_once($w_dir_volta.'classes/sp/db_getEspecieDocumento_PA.php');
// =========================================================================
// Montagem da seleção de especies do documento
// -------------------------------------------------------------------------
function selecaoEspecieDocumento($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,null,$w_cliente,null,null,'S',null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint)) ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else               ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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
