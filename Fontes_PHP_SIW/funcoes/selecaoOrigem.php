<?
// =========================================================================
// Montagem da seleção da origem do documento
// -------------------------------------------------------------------------
function selecaoOrigem($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  if ($chave=='S') {
    ShowHTML('          <option value="S" SELECTED>Interna');
    ShowHTML('          <option value="N">Externa');
  } elseif ($chave=='N') {
    ShowHTML('          <option value="S">Interna');
    ShowHTML('          <option value="N" SELECTED>Externa');
  } else {
    ShowHTML('          <option value="S">Interna');
    ShowHTML('          <option value="N">Externa');
  } 
  ShowHTML('          </select>');  
}
?>