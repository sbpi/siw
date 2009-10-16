<?
include_once($w_dir_volta.'classes/sp/db_getCcList.php');
// =========================================================================
// Montagem da seleção do centro de custo
// -------------------------------------------------------------------------
function selecaoCC($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo=null,$colspan=1) {
  extract($GLOBALS);
  $l_RS1 = db_getCCList::getInstanceOf($dbms, $w_cliente, $chaveAux, $restricao);
  ShowHTML('          <td colspan="'.$colspan.'" '.((!isset($hint)) ? '' : 'TITLE="'.$hint.'"').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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
