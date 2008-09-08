<?
// =========================================================================
// Montagem da seleção de protocolos IP
// -------------------------------------------------------------------------
function selecaoIP_Protocol($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
   if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   ShowHTML('          <option value="UDP"'. (($chave=='UDP')  ? ' SELECTED' : '').'>UDP'); 
   ShowHTML('          <option value="TCP"'. (($chave=='TCP')  ? ' SELECTED' : '').'>TCP'); 
   ShowHTML('          </select>');
}
?>