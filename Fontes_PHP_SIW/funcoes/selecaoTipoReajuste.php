<?
// =========================================================================
// Montagem da sele��o de tipos de reajustes
// -------------------------------------------------------------------------
function selecaoTipoReajuste($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  if (nvl($chave,-1)==0) ShowHTML('          <option value="0" SELECTED>N�o permite');  else  ShowHTML('          <option value="0">N�o permite');
  if (nvl($chave,-1)==1) ShowHTML('          <option value="1" SELECTED>Com �ndice');   else  ShowHTML('          <option value="1">Com �ndice'); 
  if (nvl($chave,-1)==2) ShowHTML('          <option value="2" SELECTED>Sem �ndice');   else  ShowHTML('          <option value="2">Sem �ndice'); 
  ShowHTML('          </select>');
}
?>
