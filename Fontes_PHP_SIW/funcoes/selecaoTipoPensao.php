<?php
// =========================================================================
// Montagem da sele��o dos tipos de pens�es
// -------------------------------------------------------------------------
function selecaoTipoPensao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  ShowHTML('          <td '.(($separador=='<BR />') ? 'colspan="'.$colspan.'" ' : ' ').((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  ShowHTML('          <option '.($chave==1?'SELECTED':'').' value="1">Valor fixo');
  ShowHTML('          <option '.($chave==2?'SELECTED':'').' value="2">Percentual do sal�rio bruto');
  ShowHTML('          <option '.($chave==3?'SELECTED':'').' value="3">Percentual do sal�rio l�quido');
  ShowHTML('          <option '.($chave==4?'SELECTED':'').' value="4">N�mero de sal�rios m�nimos');
  ShowHTML('          </select>'); 
}
?>