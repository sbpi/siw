<? 
// =========================================================================
// Montagem da seleção de prioridade
// -------------------------------------------------------------------------
function selecaoEsfera($label,$accesskey,$hint,$chave,$chaveAux,$cliente,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  if ($chaveAux=='F') ShowHTML(' <option value="F" SELECTED>Federal');   else  ShowHTML('<option value="F">Federal');
  if ($chaveAux=='E') ShowHTML(' <option value="E" SELECTED>Estadual');  else ShowHTML(' <option value="E">Estadual');
  if ($chaveAux=='M') ShowHTML(' <option value="M" SELECTED>Municipal'); else ShowHTML(' <option value="M">Municipal');
  ShowHTML('          </select>');
} 
?>