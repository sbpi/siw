<?
include_once($w_dir_volta.'classes/sp/db_getCCList.php');
// =========================================================================
// Montagem da seleção do centro de custo
// -------------------------------------------------------------------------
function selecaoCC($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);

  $RS = db_getCCList::getInstanceOf($dbms, $w_cliente, $ChaveAux, $restricao);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <OPTION VALUE="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_cc'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_cc').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_cc').'">'.f($row,'nome'));
    }

  }
  ShowHTML('          </SELECT></td>');
  return $function_ret;
}
?>
