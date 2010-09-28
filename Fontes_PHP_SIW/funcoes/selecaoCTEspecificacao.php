<?php
include_once($w_dir_volta.'classes/sp/db_getCTEspecificacao.php');
include_once($w_dir_volta.'classes/sp/db_getEspecOrdem.php');
// =========================================================================
// Montagem da seleção da especificacao de despesa
// -------------------------------------------------------------------------
function selecaoCTEspecificacao($label,$accesskey,$hint,$chave,$pai,$sq_ctcc,$ano,$campo,$ultimo_nivel,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getCTEspecificacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,$chave,null,$ano,'S',$ultimo_nivel,$sq_ctcc,$restricao);
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <OPTION VALUE="">---');
  foreach($RS as $row)  {
    if (f($row,'chave')==nvl($pai,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.MontaOrdemEspec(f($row,'chave')).' - '.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.MontaOrdemEspec(f($row,'chave')).' - '.f($row,'nome'));
    }
  }
  ShowHTML('          </SELECT></td>');
  return $function_ret;
}
?>
