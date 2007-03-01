<?
include_once($w_dir_volta.'classes/sp/db_getTipoDespacho_PA.php');
// =========================================================================
// Montagem da seleção de Tipos de despachos
// -------------------------------------------------------------------------
function selecaoTipoDespacho($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,null,$cliente,null,null,'S',$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')=='')
    ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (Nvl(f($row,'chave'),0)==Nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
  } 
  ShowHTML('          </select>');
} 
?>