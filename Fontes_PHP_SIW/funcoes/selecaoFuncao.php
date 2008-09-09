<?
include_once($w_dir_volta.'classes/sp/db_getPrograma_IS.php');
// =========================================================================
// Montagem da seleção de ações do PPA(tabela SIGPLAN)
// -------------------------------------------------------------------------
function selecaoFuncao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$chave,$chaveaux,$w_cliente,$w_ano,null,null,null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'Nome').' ('.f($row,'chave').')');
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'Nome').' ('.f($row,'chave').')');
  } 
  ShowHTML('          </select>');
} 
?>