<?php

// =========================================================================
// Montagem da seleção de integração entre senha de acesso e assinatura eletrônica
// -------------------------------------------------------------------------

function selecaoAutenticacao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  ShowHTML('          <td colspan="'.$colspan.'"'.((!isset($hint)) ? '' : ' title="'.$hint.'"').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('      <option value="">---');
  ShowHTML('      <option value="1" '.((Nvl($chave,'')=='1') ? 'SELECTED' : '').'>Senha de acesso e assinatura eletrônica separadas');
  ShowHTML('      <option value="2" '.((Nvl($chave,'')=='2') ? 'SELECTED' : '').'>Senha de acesso e assinatura eletrônica integradas');
  ShowHTML('    </select>');
}
?>