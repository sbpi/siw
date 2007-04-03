<?
include_once($w_dir_volta.'classes/sp/db_getAcordoAditivo.php');
// =========================================================================
// Montagem da seleção dos aditivos do contrato
// -------------------------------------------------------------------------
function selecaoAditivo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getAcordoAditivo::getInstanceOf($dbms,$w_cliente,null,$chaveAux,null,null,null,null,null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_acordo_aditivo'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'sq_acordo_aditivo').'" SELECTED>'.f($row,'codigo'));
    else
      ShowHTML('          <option value="'.f($row,'sq_acordo_aditivo').'">'.f($row,'codigo'));
  } 
  ShowHTML('          </select>');
} 
?>