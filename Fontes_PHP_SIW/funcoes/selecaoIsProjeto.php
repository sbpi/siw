<?
include_once($w_dir_volta.'classes/sp/db_getProjeto_IS.php');
// =========================================================================
// Montagem da seleção de iniciativas prioritarias
// -------------------------------------------------------------------------
function selecaoIsProjeto($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getProjeto_IS::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,null,'S',null,null,null,$restricao,null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)!=$w_chave_test) {
      if (nvl(f($row,'chave'),0)==nvl($chave,0))
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'Nome'));
      else
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'Nome'));
    } 
    $w_chave_test=nvl(f($row,'chave'),0);
  } 
  ShowHTML('          </select>');
}
?>