<? 
include_once($w_dir_volta.'classes/sp/db_getAcordoParcela.php');
// =========================================================================
// Montagem da seleção dos cronogramas de prestação de contas
// -------------------------------------------------------------------------
function selecaoContasCronograma($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getContasCronograma::getInstanceOf($dbms,null,$chaveAux,null,null,null,null,null,$restricao);
  $RS = SortArray($RS,'tipo','desc','fim','asc');
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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