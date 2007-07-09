<?
include_once($w_dir_volta.'classes/sp/db_getCcList.php');
// =========================================================================
// Montagem da seleção do centro de custo
// -------------------------------------------------------------------------
function selecaoCC($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $l_RS1 = db_getCCList::getInstanceOf($dbms, $w_cliente, $chaveAux, $restricao);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <OPTION VALUE="">---');
  foreach($l_RS1 as $l_row1) {
    if (nvl(f($l_row1,'sq_cc'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($l_row1,'sq_cc').'" SELECTED>'.f($l_row1,'nome'));
    } else {
      ShowHTML('          <option value="'.f($l_row1,'sq_cc').'">'.f($l_row1,'nome'));
    }

  }
  ShowHTML('          </SELECT></td>');
}
?>
