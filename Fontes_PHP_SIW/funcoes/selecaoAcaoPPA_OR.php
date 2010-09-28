<?php
include_once($w_dir_volta.'classes/sp/db_getAcaoPPA.php');
// =========================================================================
// Montagem da seleção de ações do PPA
// -------------------------------------------------------------------------
function selecaoAcaoPPA_OR($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getAcaoPPA; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,$chaveAux,null,null,$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if ($restricao=='CADASTRO') {
      if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome').' ('.f($row,'codigo').')');
      } else {
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome').' ('.f($row,'codigo').')');
      } 
    } else {
      if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome').' ('.f($row,'cd_pai').'.'.f($row,'codigo').')');
      } else {
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome').' ('.f($row,'cd_pai').'.'.f($row,'codigo').')');
      } 
    } 
  } 
  ShowHTML('          </select>');
} 
?>