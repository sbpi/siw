<?
// =========================================================================
// Montagem da seleção de Obrigatoriedade
// -------------------------------------------------------------------------
function selecaoObrigatorio($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (Nvl($hint,'')>'')
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  if ($chave=='S')
    ShowHTML('          <option value="S" SELECTED>Sim');
  else
    ShowHTML('          <option value="S">SIM');
  if ($chave=='N')
    ShowHTML('          <option value="N" SELECTED>Não');
  else
    ShowHTML('          <option value="N">Não');
  ShowHTML('          </select>');
}
?>