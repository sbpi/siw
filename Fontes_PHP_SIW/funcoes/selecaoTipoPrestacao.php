<?php
include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
// =========================================================================
// Montagem da seleção do tipo das prestacoes de contas
// -------------------------------------------------------------------------
function selecaoTipoPrestacao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (Nvl($hint,'')>''){
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='P') {
    ShowHTML('          <option value="P" SELECTED>Parcial');
  } else {
    ShowHTML('          <option value="P">Parcial');
  } 
  if (Nvl($chave,'')=='F') {
    ShowHTML('          <option value="F" SELECTED>Final');
  } else {
    ShowHTML('          <option value="F">Final');
  } 
  ShowHTML('          </select>');
} 
?>