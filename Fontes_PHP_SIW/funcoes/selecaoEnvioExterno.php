<?php
// =========================================================================
// Montagem da seleção de tipos de envio externo de protocolos
// -------------------------------------------------------------------------
function selecaoEnvioExterno($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('      <option value="">---');
  ShowHTML('      <option value="X" '.((Nvl($chave,'')=='X') ? 'SELECTED' : '').'>Apenas unidade executora');
  ShowHTML('      <option value="T" '.((Nvl($chave,'')=='T') ? 'SELECTED' : '').'>Todas as unidades');
  ShowHTML('    </select>');
}
?>