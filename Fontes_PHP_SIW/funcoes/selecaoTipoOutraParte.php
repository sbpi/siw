<?php
// =========================================================================
// Montagem da seleção de tipo de conclusão
// -------------------------------------------------------------------------
function SelecaoTipoOutraParte ($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---'); 
  if (nvl($chave,0)==1) ShowHTML('          <option value="1" SELECTED>concedente/contratante/parceiro');         else ShowHTML('          <option value="1">concedente/contratante/parceiro');
  if (nvl($chave,0)==2) ShowHTML('          <option value="2" SELECTED>convenente');                              else ShowHTML('          <option value="2">convenente');
  if (nvl($chave,0)==3) ShowHTML('          <option value="3" SELECTED>executor/contratado');                     else ShowHTML('          <option value="3">executor/contratado');
  ShowHTML('          </select>');
} 
?>