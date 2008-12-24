<?
include_once($w_dir_volta.'classes/sp/db_getCaixa.php');
// =========================================================================
// Montagem da seleção da Caixa
// -------------------------------------------------------------------------

function selecaoCaixa($label,$accesskey,$hint,$chave,$cliente,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
 
  $l_rs = db_getCaixa::getInstanceOf($dbms,$chave,$cliente,$chaveAux,null,null,$restricao);
  $l_rs = SortArray($l_rs,'nm_unidade','asc','numero','asc');
  if (!isset($hint)) {
     ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($l_rs as $row) {
    if (nvl(f($row,'sq_caixa'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_caixa').'" SELECTED>'.f($row,'numero').'/'.f($row,'sg_unidade'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_caixa').'">'.f($row,'numero').'/'.f($row,'sg_unidade'));
    }
  }
  ShowHTML('          </select>');
}
?>