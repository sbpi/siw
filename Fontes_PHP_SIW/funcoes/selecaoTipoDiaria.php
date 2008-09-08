<?
// =========================================================================
// Montagem da seleção de tipos de diária
// -------------------------------------------------------------------------
function selecaoTipoDiaria($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (!isset($hint)) {
      ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   ShowHTML('          <option value="D"'.(($chave=='D') ? ' SELECTED' : '').'>Diárias');
   ShowHTML('          <option value="H"'.(($chave=='H') ? ' SELECTED' : '').'>Hospedagem');
   ShowHTML('          <option value="V"'.(($chave=='V') ? ' SELECTED' : '').'>Locação de veículo');
   ShowHTML('          </select>');
}
?>