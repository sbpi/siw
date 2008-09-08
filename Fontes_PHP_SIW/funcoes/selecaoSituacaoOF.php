<?
// =========================================================================
// Montagem da seleção de tipo de conclusão
// -------------------------------------------------------------------------
function SelecaoSituacaoOF($label,$accesskey,$hint,$chave,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  if (nvl($chave,'N')=='N') ShowHTML('          <option value="N" SELECTED>Normal');                     else ShowHTML('          <option value="N">Normal');
  if (nvl($chave,'')=='D')  ShowHTML('          <option value="D" SELECTED>Disponível para pagamento');  else ShowHTML('          <option value="D">Disponível para pagamento');
  if (nvl($chave,'')=='C')  ShowHTML('          <option value="C" SELECTED>Cancelada');                  else ShowHTML('          <option value="C">Cancelada');
  ShowHTML('          </select>');
} 
?>
