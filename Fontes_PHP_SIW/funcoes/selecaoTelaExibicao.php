<?php

// =========================================================================
// Montagem da sele��o da tela de exibi��o das situa��es de um certame
// -------------------------------------------------------------------------

function selecaoTelaExibicao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  ShowHTML('      <td colspan="'.$colspan.'"'.((!isset($hint)) ? '' : ' title="'.$hint.'"').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('        <option value="">---');
  ShowHTML('        <option value="T" '.((Nvl($chave,'')=='T') ? 'SELECTED' : '').'>Todas as telas');
  ShowHTML('        <option value="C" '.((Nvl($chave,'')=='C') ? 'SELECTED' : '').'>Somente na tela de conclus�o');
  ShowHTML('        <option value="E" '.((Nvl($chave,'')=='E') ? 'SELECTED' : '').'>Exceto na tela de conclus�o');
  ShowHTML('        </select>');
  ShowHTML('      </td>');
}
?>