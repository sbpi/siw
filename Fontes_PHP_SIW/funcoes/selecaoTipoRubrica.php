<?
include_once($w_dir_volta.'classes/sp/db_getLancamentoProjeto.php');
// =========================================================================
// Montagem da seleção de tipo de conclusão
// -------------------------------------------------------------------------
function SelecaoTipoRubrica ($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $l_RS  = db_getLinkData::getInstanceOf($dbms,$w_cliente,$restricao);
  $l_RS1 = db_getLancamentoProjeto::getInstanceOf($dbms,$chaveAux,f($l_RS,'sq_menu'),null);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---'); 
  if(count($l_RS1)==0) {
    if (nvl($chave,0)==1) ShowHTML('          <option value="1" SELECTED>Dotação Inicial');                else ShowHTML('          <option value="1">Dotação inicial');
  } else {
    if (nvl($chave,0)==2) ShowHTML('          <option value="2" SELECTED>Transferências entre rubricas');  else ShowHTML('          <option value="2">Transferências entre rubricas');
    if (nvl($chave,0)==3) ShowHTML('          <option value="3" SELECTED>Atualização de Aplicação');       else ShowHTML('          <option value="3">Atualização de Aplicação');
    if (nvl($chave,0)==4) ShowHTML('          <option value="4" SELECTED>Entradas');                       else ShowHTML('          <option value="4">Entradas');
  }
  ShowHTML('          </select>');
} 
?>
