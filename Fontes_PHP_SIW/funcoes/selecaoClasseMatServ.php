<?php
// =========================================================================
// Montagem da seleção da classe do material ou serviço
// -------------------------------------------------------------------------
function selecaoClasseMatServ($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
   if (!isset($hint)) {
      ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   if (Nvl($chave,'')=='1') ShowHTML('          <option value="1" SELECTED>Medicamento');         else ShowHTML('          <option value="1">Medicamento');
   if (Nvl($chave,'')=='3') ShowHTML('          <option value="3" SELECTED>Material de consumo'); else ShowHTML('          <option value="3">Material de consumo');
   if (Nvl($chave,'')=='4') ShowHTML('          <option value="4" SELECTED>Material permanente'); else ShowHTML('          <option value="4">Material permanente');
   if (Nvl($chave,'')=='5') ShowHTML('          <option value="5" SELECTED>Serviço');             else ShowHTML('          <option value="5">Serviço');
   ShowHTML('          </select>');
}
?>