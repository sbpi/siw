<?
include_once($w_dir_volta.'classes/sp/db_getFormaPagamento.php');
// =========================================================================
// Montagem da seleção da forma de pagamento
// -------------------------------------------------------------------------
function selecaoFormaPagamento($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getFormaPagamento::getInstanceOf($dbms, $w_cliente, null, $chave_aux, $restricao);
  $RS->Filter='ativo = \'S\'';
  $RS->Sort='nome';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }

  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_forma_pagamento'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_forma_pagamento').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_forma_pagamento').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
