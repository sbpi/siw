<?
//include_once($w_dir_volta.'classes/sp/db_getLancamentoProjeto.php');
// =========================================================================
// Montagem da seleção de tipo de conclusão
// -------------------------------------------------------------------------
function SelecaoTipoOutraParte ($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  //$l_RS  = db_getLinkData::getInstanceOf($dbms,$w_cliente,$restricao);
  //$l_RS1 = db_getLancamentoProjeto::getInstanceOf($dbms,$chaveAux,f($l_RS,'sq_menu'),null);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---'); 
  if (nvl($chave,0)==1) ShowHTML('          <option value="1" SELECTED>concedente/contratante/parceiro');         else ShowHTML('          <option value="1">concedente/contratante/parceiro');
  if (nvl($chave,0)==2) ShowHTML('          <option value="2" SELECTED>convenente');                              else ShowHTML('          <option value="2">convenente');
  if (nvl($chave,0)==3) ShowHTML('          <option value="3" SELECTED>executor/contratado');                     else ShowHTML('          <option value="3">executor/contratado');
  ShowHTML('          </select>');
} 
?>

