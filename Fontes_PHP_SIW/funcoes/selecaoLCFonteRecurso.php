<?php
include_once($w_dir_volta.'classes/sp/db_getLCFonteRecurso.php');
// =========================================================================
// Montagem da sele��o das fontes de recurso de contrato
// -------------------------------------------------------------------------
function selecaoLCFonteRecurso($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getLCFonteRecurso; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,'S',null,null,null,$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome').' ('.f($row,'codigo').')');
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome').' ('.f($row,'codigo').')');
    } 
  } 
  ShowHTML('          </select>');
}
?>