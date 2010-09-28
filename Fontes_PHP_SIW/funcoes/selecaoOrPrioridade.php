<?php
include_once($w_dir_volta.'classes/sp/db_getOrPrioridade.php');
// =========================================================================
// Montagem da seleção de iniciativas prioritarias
// -------------------------------------------------------------------------
function SelecaoOrPrioridade($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getOrPrioridade; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,null,null,null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint)) {
    ShowHTML('          <td valign=\'top\'><font size=\'1\'><b>'.$label.'</b><br><SELECT ACCESSKEY=\''.$accesskey.'\' CLASS=\'STS\' NAME=\''.$campo.'\' '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign=\'top\' title=\''.$hint.'\'><font size=\'1\'><b>'.$label.'</b><br><SELECT ACCESSKEY=\''.$accesskey.'\' CLASS=\'STS\' NAME=\''.$campo.'\' '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value=\'\'>---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)!=$w_chave_test) {
      if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
        ShowHTML('          <option value=\''.f($row,'chave').'\' SELECTED>'.f($row,'Nome'));
      } else {
        ShowHTML('          <option value=\''.f($row,'chave').'\'>'.f($row,'Nome'));
      } 
    } 
    $w_chave_test=nvl(f($row,'chave'),0);
  } 
  ShowHTML('          </select>');
} 
?>
