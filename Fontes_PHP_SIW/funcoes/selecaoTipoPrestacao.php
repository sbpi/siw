<?
include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
// =========================================================================
// Montagem da seleção do tipo das prestacoes de contas
// -------------------------------------------------------------------------
function selecaoTipoPrestacao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (Nvl($hint,'')>''){
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='P') {
    ShowHTML('          <option value="P" SELECTED>Parcial');
  } else {
    ShowHTML('          <option value="P">Parcial');
  } 
  if (Nvl($chave,'')=='F') {
    ShowHTML('          <option value="F" SELECTED>Final');
  } else {
    ShowHTML('          <option value="F">Final');
  } 
  ShowHTML('          </select>');
} 
?>