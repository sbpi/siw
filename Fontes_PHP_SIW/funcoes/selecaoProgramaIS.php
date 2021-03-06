<?php
include_once($w_dir_volta.'classes/sp/db_getPrograma_IS.php');
// =========================================================================
// Montagem da sele��o de programas cadastrados no INFRASIG
// -------------------------------------------------------------------------
function selecaoProgramaIS($label,$accesskey,$hint,$cliente,$ano,$chave,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if ($restricao=='CADASTRADOS') {
    $sql = new db_getPrograma_IS; $RS = $sql->getInstanceOf($dbms,${'w_cd_programa'},$w_ano,$w_cliente,$restricao);
    $RS = SortArray($RS,'titulo','asc');
  } 
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'titulo'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'titulo'));
  } 
  ShowHTML('          </select>');
}
?>