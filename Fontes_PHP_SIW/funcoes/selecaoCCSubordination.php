<?
include_once($w_dir_volta.'classes/sp/db_getCCSubordination.php');
// =========================================================================
// Montagem da seleção do centro de custo
// -------------------------------------------------------------------------
function selecaoCCSubordination($label,$accesskey,$hint,$chave,$pai,$campo,$restricao,$condicao) {
  extract($GLOBALS);
  $RS = db_getCCSubordination::getInstanceOf($dbms, $w_cliente, $chave, $restricao);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <OPTION VALUE="">---');
  foreach($RS as $row)  {
    if (f($row,'sq_cc')]==nvl($pai,0)) {
      ShowHTML('          <option value="'.f($row,'sq_cc').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_cc').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </SELECT></td>');
  return $function_ret;
}
?>
