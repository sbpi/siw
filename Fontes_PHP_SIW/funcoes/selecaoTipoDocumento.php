<?php 
include_once($w_dir_volta.'classes/sp/db_getTipoDocumento.php');
// =========================================================================
// Montagem da seleção de tipos de documento
// -------------------------------------------------------------------------
function selecaoTipoDocumento($label,$accesskey,$hint,$chave,$cliente,$menu,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getTipoDocumento; $RS = $sql->getInstanceOf($dbms,null,$cliente,$menu);
  $RS = SortArray($RS,'nome','asc');

  ShowHTML('          <td colspan="'.$colspan.'" '.((!isset($hint)) ? '' : 'TITLE="'.$hint.'"').'>'.((!isset($label)) ? '' : '<b>'.$label.'</b><br>').'<SELECT ACCESSKEY="'.$accesskey.'" class="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    ShowHTML(' <option value="'.f($row,'chave').'"'.((nvl(f($row,'chave'),0)==nvl($chave,0)) ? ' SELECTED' : '').'>'.f($row,'nome'));
  }
  ShowHTML('          </select>');
} 
?>