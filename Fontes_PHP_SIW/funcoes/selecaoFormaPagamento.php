<?php
include_once($w_dir_volta.'classes/sp/db_getFormaPagamento.php');
// =========================================================================
// Montagem da seleção da forma de pagamento
// -------------------------------------------------------------------------
function selecaoFormaPagamento($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao,$atributo=null,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getFormaPagamento::getInstanceOf($dbms, $w_cliente, null, $chave_aux, $restricao,'S',null);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_forma_pagamento'),0)==nvl($chave,0) || count($RS)==1) {
       ShowHTML('          <option value="'.f($row,'sq_forma_pagamento').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_forma_pagamento').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
