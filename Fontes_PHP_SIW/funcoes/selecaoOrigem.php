<?php
// =========================================================================
// Montagem da seleção da origem do documento
// -------------------------------------------------------------------------
function selecaoOrigem($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  ShowHTML('          <td colspan="'.$colspan.'" '.((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  ShowHTML('          <option value="S" '.(($chave=='S') ? 'SELECTED' : '').'>Interna');
  ShowHTML('          <option value="N" '.(($chave=='N') ? 'SELECTED' : '').'>Externa');
  ShowHTML('          </select>');  
}
?>