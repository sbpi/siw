<?php
include_once($w_dir_volta.'classes/sp/db_getAcordoAditivo.php');
// =========================================================================
// Montagem da sele��o dos aditivos do contrato
// -------------------------------------------------------------------------
function selecaoAditivo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getAcordoAditivo; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$chaveAux,null,null,null,null,null,null,null,null,null);
  $RS = SortArray($RS,'fim','desc','nome','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if(nvl(f($row,'valor_aditivo'),0)>0) {
      if (nvl(f($row,'sq_acordo_aditivo'),0)==nvl($chave,0))
        ShowHTML('          <option value="'.f($row,'sq_acordo_aditivo').'" SELECTED>'.f($row,'codigo'));
      else
        ShowHTML('          <option value="'.f($row,'sq_acordo_aditivo').'">'.f($row,'codigo'));
    } 
  }
  ShowHTML('          </select>');
} 
?>