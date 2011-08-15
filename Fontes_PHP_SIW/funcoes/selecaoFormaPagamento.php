<?php
include_once($w_dir_volta.'classes/sp/db_getFormaPagamento.php');
// =========================================================================
// Montagem da seleção da forma de pagamento
// -------------------------------------------------------------------------
function selecaoFormaPagamento($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao,$atributo=null,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getFormaPagamento; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, $chave_aux, $restricao,'S',null);
  $RS = SortArray($RS,'nome','asc');

  ShowHTML('          <td colspan="'.$colspan.'" '.((!isset($hint)) ? '' : 'TITLE="'.$hint.'"').'>'.((!isset($label)) ? '' : '<b>'.$label.'</b><br>').'<SELECT ACCESSKEY="'.$accesskey.'" class="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    ShowHTML('          <option value="'.f($row,'sq_forma_pagamento').'"'.((nvl(f($row,'sq_forma_pagamento'),0)==nvl($chave,0) || count($RS)==1) ? ' SELECTED' : '').'>'.f($row,'nome'));
  }
  ShowHTML('          </select>');
}
?>
