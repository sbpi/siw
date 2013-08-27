<?php

// =========================================================================
// Montagem da seleção de sexo
// -------------------------------------------------------------------------

function selecaoSexo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  ShowHTML('      <td colspan="'.$colspan.'"'.((!isset($hint)) ? '' : ' title="'.$hint.'"').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('        <option value="">---');
  ShowHTML('        <option value="F" '.((Nvl($chave,'')=='F') ? 'SELECTED' : '').'>Feminino');
  ShowHTML('        <option value="M" '.((Nvl($chave,'')=='M') ? 'SELECTED' : '').'>Masculino');
  ShowHTML('        </select>');
  ShowHTML('      </td>');
}
?>