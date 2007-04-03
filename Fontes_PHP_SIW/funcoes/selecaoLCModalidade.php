<?
include_once($w_dir_volta.'classes/sp/db_getLCModalidade.php');
// =========================================================================
// Montagem da seleção de modalidades de contrato
// -------------------------------------------------------------------------
function selecaoLCModalidade($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getLCModalidade::getInstanceOf($dbms,null,$w_cliente,null,null,'S',$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
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