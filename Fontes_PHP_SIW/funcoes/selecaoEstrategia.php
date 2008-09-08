<?
// =========================================================================
// Montagem da seleção de estrategia
// -------------------------------------------------------------------------
function selecaoEstrategia($label,$accesskey,$hint,$chave,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---'); 
  if (nvl($chave,'')=='A') ShowHTML('          <option value="A" SELECTED>Aceitar');     else ShowHTML('          <option value="A">Aceitar');
  if (nvl($chave,'')=='E') ShowHTML('          <option value="E" SELECTED>Evitar');      else ShowHTML('          <option value="E">Evitar');
  if (nvl($chave,'')=='M') ShowHTML('          <option value="M" SELECTED>Mitigar');     else ShowHTML('          <option value="M">Mitigar');
  if (nvl($chave,'')=='T') ShowHTML('          <option value="T" SELECTED>Transferir');  else ShowHTML('          <option value="T">Transferir');
  ShowHTML('          </select>');

}
?>