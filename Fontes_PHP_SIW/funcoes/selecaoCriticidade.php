<?

// =========================================================================
// Montagem da seleção de tipo de criticidade
// -------------------------------------------------------------------------
function SelecaoCriticidade ($label,$accesskey,$hint,$chave,$chave_aux1,$chave_aux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---'); 
  // Se não existir outro lançamento financeiro, trata o atual como sendo dotação inicial
  if (nvl($chave,0)==1) ShowHTML('          <option value="1" SELECTED>Baixa');     else ShowHTML('          <option value="1">Baixa');
  if (nvl($chave,0)==2) ShowHTML('          <option value="2" SELECTED>Moderada');  else ShowHTML('          <option value="2">Moderada');
  if (nvl($chave,0)==3) ShowHTML('          <option value="3" SELECTED>Alta');      else ShowHTML('          <option value="3">Alta');
  ShowHTML('          </select>');
} 
?>