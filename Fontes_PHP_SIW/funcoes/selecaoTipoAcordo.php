<?php
include_once($w_dir_volta.'classes/sp/db_getAgreeType.php');
// =========================================================================
// Montagem da seleção de tipos de acordo
// -------------------------------------------------------------------------
function SelecaoTipoAcordo($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);

  $RS = db_getAgreeType::getInstanceOf($dbms,null,$chaveAux,$chaveAux2,null,null,$restricao);
  $RS = SortArray($RS,'nm_tipo','asc');
  ShowHTML('          <td colspan="'.$colspan.'"'.((!isset($hint)) ? '' : 'TITLE="'.$hint.'"').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    ShowHTML('          <option value="'.f($row,'sq_tipo_acordo').'" '.(((nvl(f($row,'sq_tipo_acordo'),0)==nvl($chave,0))) ? 'SELECTED' : '').'>'.f($row,'nm_tipo'));
  } 
  ShowHTML('          </select>');
} 
?>
