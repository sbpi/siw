<?php
// =========================================================================
// Montagem da sele��o de tipo de conclus�o
// -------------------------------------------------------------------------
function SelecaoSituacaoOF($label,$accesskey,$hint,$chave,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  if (nvl($chave,'N')=='N') ShowHTML('          <option value="N" SELECTED>Normal');                     else ShowHTML('          <option value="N">Normal');
  if (nvl($chave,'')=='D')  ShowHTML('          <option value="D" SELECTED>Dispon�vel para pagamento');  else ShowHTML('          <option value="D">Dispon�vel para pagamento');
  if (nvl($chave,'')=='C')  ShowHTML('          <option value="C" SELECTED>Cancelada');                  else ShowHTML('          <option value="C">Cancelada');
  ShowHTML('          </select>');
} 
?>
