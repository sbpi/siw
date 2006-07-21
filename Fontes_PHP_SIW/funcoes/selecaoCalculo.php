<? 
// =========================================================================
// Montagem da seleção de prioridade
// -------------------------------------------------------------------------
function selecaoCalculo($label,$accesskey,$hint,$chave,$chaveAux,$cliente,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  if ($chaveAux=='Nominal')  
    ShowHTML(' <option value=0 SELECTED>Nominal');  
  else 
    ShowHTML(' <option value=0>Nominal');
  if ($chaveAux=='Retenção') 
    ShowHTML(' <option value=1 SELECTED>Retencao'); 
  else 
    ShowHTML(' <option value=1>Retencao');
ShowHTML('          </select>');
}  
?>