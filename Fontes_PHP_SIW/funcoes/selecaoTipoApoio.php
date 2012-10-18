<?php 
include_once($w_dir_volta.'classes/sp/db_getTipoApoioList.php');
// =========================================================================
// Montagem da seleção de tipos de documento
// -------------------------------------------------------------------------
function selecaoTipoApoio($label,$accesskey,$hint,$chave,$cliente,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getTipoApoioList; $RS = $sql->getInstanceOf($dbms,$cliente, $p_chave, null, null, null, 'S');
  $RS = SortArray($RS,'nome','asc');
  ShowHTML(' <td colspan="'.$colspan.'"'.((isset($hint)) ? ' TITLE="'.$hint.'"' : '').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    ShowHTML(' <option value="'.f($row,'sq_tipo_apoio').'"'.((f($row,'sq_tipo_apoio')==nvl($chave,0)) ? ' SELECTED' : '').'>'.f($row,'nome'));
  }
  ShowHTML('          </select>');
} 
?>