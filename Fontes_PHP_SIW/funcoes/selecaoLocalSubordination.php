<?php
include_once($w_dir_volta.'classes/sp/db_getAlmoxarifado.php');
// =========================================================================
// Montagem da seleção de Tipos estratégicos
// -------------------------------------------------------------------------
function selecaoLocalSubordination($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao,$condicao) {
  extract($GLOBALS);
  $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms, $w_cliente, $chave_aux, null, null, null, null, $restricao, 'S', null);
  $RS = SortArray($RS,'nome','asc'); 
  ShowHTML('          <td valign="top" '.((!isset($hint)) ? '' : 'title="'.$hint.'"').'>'.((!isset($label)) ? '' : '<b>'.$label.'</b>').'<br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  ShowHTML('          <OPTION VALUE="">---');
  foreach($RS as $row)  {
    ShowHTML('          <option value="'.f($row,'chave').'" '.((f($row,'chave')==nvl($chave,0)) ? 'SELECTED' : '').'>'.f($row,'nome_completo'));
  }
  ShowHTML('          </SELECT></td>');
}
?>
