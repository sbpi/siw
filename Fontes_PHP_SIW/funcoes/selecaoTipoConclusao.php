<?php
// =========================================================================
// Montagem da sele��o de tipo de conclus�o
// -------------------------------------------------------------------------
function SelecaoTipoConclusao($label,$accesskey,$hint,$chave,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---'); 
  //if (nvl($chave,-1)==0) ShowHTML('          <option value="0" SELECTED>Conclus�o com renova��o');  else ShowHTML('          <option value="0">Conclus�o com renova��o');
  if (nvl($chave,-1)==1) ShowHTML('          <option value="1" SELECTED>Encerramento na data prevista');  else ShowHTML('          <option value="1">Encerramento na data prevista');
  if (nvl($chave,-1)==2) ShowHTML('          <option value="2" SELECTED>Rescis�o');                       else ShowHTML('          <option value="2">Rescis�o');
  ShowHTML('          </select>');
} 
?>
