<?php
include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
// =========================================================================
// Montagem da seleção de assuntos
// -------------------------------------------------------------------------
function selecaoAssunto($label,$accesskey,$hint,$chave,$chaveAux,$campo,$descricao,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$chave,null,null,$descricao,null,null,null,null,'S',$restricao);
  $RS = SortArray($RS,'descricao','asc');
  if (!isset($hint)) {
     ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'codigo').' - '.lower(f($row,'descricao')));
    } else {
       ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'codigo').' - '.lower(f($row,'descricao')));
    }
  }
  ShowHTML('          </select>');
}
?>
