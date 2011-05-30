<?php
include_once($w_dir_volta.'classes/sp/db_getAlmoxarifado.php');
// =========================================================================
// Montagem da seleção de localizações de almoxarifado
// -------------------------------------------------------------------------
function selecaoLocalSubordination($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao,$atributo=null,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms, $w_cliente, $chave_aux, null, null, null, null, $restricao, 'S', null);
  $RS = SortArray($RS,'nome_completo','asc'); 
  ShowHTML('          <td colspan="'.$colspan.'"'.((!isset($hint)) ? '' : ' title="'.$hint.'"').'>'.((!isset($label)) ? '' : '<b>'.$label.'</b>').$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <OPTION VALUE="">---');
  foreach($RS as $row)  {
    ShowHTML('          <option value="'.f($row,'chave').'" '.((f($row,'chave')==nvl($chave,0)) ? 'SELECTED' : '').'>'.f($row,'nome_completo'));
  }
  ShowHTML('          </SELECT></td>');
}
?>
