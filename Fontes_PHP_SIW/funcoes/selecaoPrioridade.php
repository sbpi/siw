<?
// =========================================================================
// Montagem da sele��o de prioridade
// -------------------------------------------------------------------------
function selecaoPrioridade($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  if (nvl($chave,-1)==0) { ShowHTML('          <option value="0" SELECTED>Alta');   } else { ShowHTML('          <option value="0">Alta'); }
  if (nvl($chave,-1)==1) { ShowHTML('          <option value="1" SELECTED>M�dia');  } else { ShowHTML('          <option value="1">M�dia'); }
  if (nvl($chave,-1)==2) { ShowHTML('          <option value="2" SELECTED>Normal'); } else { ShowHTML('          <option value="2">Normal'); }
  ShowHTML('          </select>');
  return $function_ret;
}
?>
