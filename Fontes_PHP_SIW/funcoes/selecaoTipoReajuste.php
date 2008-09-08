<?
// =========================================================================
// Montagem da seleção de tipos de reajustes
// -------------------------------------------------------------------------
function selecaoTipoReajuste($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  if (nvl($chave,-1)==0) ShowHTML('          <option value="0" SELECTED>Não permite');  else  ShowHTML('          <option value="0">Não permite');
  if (nvl($chave,-1)==1) ShowHTML('          <option value="1" SELECTED>Com índice');   else  ShowHTML('          <option value="1">Com índice'); 
  if (nvl($chave,-1)==2) ShowHTML('          <option value="2" SELECTED>Sem índice');   else  ShowHTML('          <option value="2">Sem índice'); 
  ShowHTML('          </select>');
}
?>
