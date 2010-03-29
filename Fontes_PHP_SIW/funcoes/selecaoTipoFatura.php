<?php
// =========================================================================
// Montagem da sele��o de tipo de fatura
// -------------------------------------------------------------------------
/*
Indica o tipo de fatura: 
0 - Bilhetes a�reos; 
1 - Hospedagem, Loca��o de ve�culos, Seguro de viagem
*/
function selecaoTipoFatura($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
extract($GLOBALS);
   if (!isset($hint)) {
      ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('      <option value="">---');
   ShowHTML('      <option value="0" '.((Nvl($chave,'')=='0') ? 'SELECTED' : '').'>Bilhetes a�reos');
   ShowHTML('      <option value="1" '.((Nvl($chave,'')=='1') ? 'SELECTED' : '').'>Hospedagem, loca��o de ve�culos e seguro de viagem');
   ShowHTML('    </select>');
}
?>