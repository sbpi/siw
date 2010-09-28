<?php
// =========================================================================
// Montagem da seleção de tipo de PCD
// -------------------------------------------------------------------------
function selecaoTipoPCD($label,$accesskey,$hint,$chave,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);

  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 

  ShowHTML('          <option value="">---');
  if (nvl($chave,'')=='I') ShowHTML('          <option value="I" SELECTED>Inicial');              else ShowHTML('          <option value="I">Inicial');
  if (nvl($chave,'')=='P') ShowHTML('          <option value="P" SELECTED>Prorrogação');          else ShowHTML('          <option value="P">Prorrogação');
  if (nvl($chave,'')=='C') ShowHTML('          <option value="C" SELECTED>Complementação');       else ShowHTML('          <option value="C">Complementação');
  ShowHTML('          </select>');
} 
?>
