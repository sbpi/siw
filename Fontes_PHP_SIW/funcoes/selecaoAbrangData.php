<?
// =========================================================================
// Montagem da sele��o de abrag�ncia da data
// -------------------------------------------------------------------------
function selecaoAbrangData($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='I') {
    ShowHTML('          <option value="I" SELECTED>Internacional');
  } else {
    ShowHTML('          <option value="I">Internacional');
  } if (Nvl($chave,'')=='N') {
    ShowHTML('          <option value="N" SELECTED>Nacional');
  } else {
    ShowHTML('          <option value="N">Nacional');
  } if (Nvl($chave,'')=='E') {
    ShowHTML('          <option value="E" SELECTED>Estadual');
  } else {
    ShowHTML('          <option value="E">Estadual');
  } if (Nvl($chave,'')=='M') {
    ShowHTML('          <option value="M" SELECTED>Municipal');
  } else {
    ShowHTML('          <option value="M">Municipal');
  } if (Nvl($chave,'')=='O') {
    ShowHTML('          <option value="O" SELECTED>Organiza��o');
  } else {
   ShowHTML('          <option value="O">Organiza��o');
  } 
  ShowHTML('          </select>'); 
}
?>