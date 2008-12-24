<?
// =========================================================================
// Montagem da seleção de tipo de utilização de bilhete
// -------------------------------------------------------------------------
function selecaoTipoUtilBilhete($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
   if (!isset($hint)) {
      ShowHTML('          <td colspan="'.$colspan.'">'.((!isset($label)) ? '' :'<b>'.$label.'</b><br>').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'">'.((!isset($label)) ? '' :'<b>'.$label.'</b><br>').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   ShowHTML('          <option value="I" '.(($chave=='I') ? 'selected': '').'>Integral');
   ShowHTML('          <option value="P" '.(($chave=='P') ? 'selected': '').'>Parcial');
   ShowHTML('          <option value="C" '.(($chave=='C') ? 'selected': '').'>Não utilizado');
   ShowHTML('          </select>');
}
?>