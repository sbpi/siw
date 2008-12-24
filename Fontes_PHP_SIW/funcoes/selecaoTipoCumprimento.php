<?
// =========================================================================
// Montagem da seleção de tipo de cumprimento de viagem
// -------------------------------------------------------------------------
function selecaoTipoCumprimento($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
   if (!isset($hint)) {
      ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   ShowHTML('          <option value="I" '.(($chave=='I') ? 'selected': '').'>Viagem cumprida integralmente');
   ShowHTML('          <option value="P" '.(($chave=='P') ? 'selected': '').'>Viagem cumprida parcialmente');
   ShowHTML('          <option value="C" '.(($chave=='C') ? 'selected': '').'>Viagem cancelada');
   ShowHTML('          </select>');
}
?>