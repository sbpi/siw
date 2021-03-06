<?php 
include_once($w_dir_volta.'classes/sp/db_getAcordoParcela.php');
// =========================================================================
// Montagem da sele��o dos cronogramas de presta��o de contas
// -------------------------------------------------------------------------
function selecaoContasCronograma($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getContasCronograma; $RS = $sql->getInstanceOf($dbms,null,$chaveAux,null,null,null,null,null,$restricao);
  $RS = SortArray($RS,'tipo','desc','fim','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" TITLE="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.FormataDataEdicao(f($row,'inicio')).' - '.FormataDataEdicao(f($row,'fim')).' - '.f($row,'nm_prestacao_contas').' - '.f($row,'nm_tipo'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.FormataDataEdicao(f($row,'inicio')).' - '.FormataDataEdicao(f($row,'fim')).' - '.f($row,'nm_prestacao_contas').' - '.f($row,'nm_tipo'));
  } 
  ShowHTML('          </select>');
}
?>