<?
include_once($w_dir_volta.'classes/sp/db_getFoneList.php');
// =========================================================================
// Montagem da seleção dos telefones de uma pessoa
// -------------------------------------------------------------------------
function SelecaoTelefoneTT($label,$accesskey,$hint,$chave,$chaveAux,$campo,$O,$restricao) {
  extract($GLOBALS);

  $RS = db_getFoneList::getInstanceOf($dbms, $w_cliente, null, $restricao, null);

  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.'>');
  } 
  ShowHTML('          <option value="">---');
  if ($O=='A') {
    foreach ($RS as $row) {
      if (nvl(f($row,'sq_pessoa_telefone'),0)==nvl($chave,0)) {
        ShowHTML('          <option value="'.f($row,'sq_pessoa_telefone').'" SELECTED>'.f($row,'numero').' - '.f($row,'tipo_telefone'));
      } 
    } 
    $RS = db_getFoneList::getInstanceOf($dbms, $w_cliente, null, 'TRONCO', null);
    foreach ($RS as $row) {
      if (nvl(f($row,'sq_pessoa_telefone'),0)!=nvl($chave,0)) {
        ShowHTML('          <option value="'.f($row,'sq_pessoa_telefone').'">'.f($row,'numero').' - '.f($row,'tipo_telefone'));
      } 
    } 
  } else {
    foreach ($RS as $row) {
      if (nvl(f($row,'sq_pessoa_telefone'),0)==nvl($chave,0)) {
        ShowHTML('          <option value="'.f($row,'sq_pessoa_telefone').'" SELECTED>'.f($row,'numero').' - '.f($row,'tipo_telefone'));
      } else {
        ShowHTML('          <option value="'.f($row,'sq_pessoa_telefone').'">ssss'.f($row,'numero').' - '.f($row,'tipo_telefone'));
      } 
    } 
  } 
  ShowHTML('          </select>');
} 
?>