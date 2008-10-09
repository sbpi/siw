<?
include_once($w_dir_volta.'classes/sp/db_getAcao_IS.php');
// =========================================================================
// Montagem da seleção de ações cadastradas
// -------------------------------------------------------------------------
function selecaoAcao($label,$accesskey,$hint,$l_cliente,$l_ano,$l_programa,$l_acao,$l_subacao,$l_unidade,$campo,$restricao,$atributo,$chave) {
  extract($GLOBALS);
  $RS = db_getAcao_IS::getInstanceOf($dbms,null,null,null,$l_ano,$l_cliente,$restricao,null);
  $RS = SortArray($RS,'titulo','asc');
  if (!isset($hint))
    ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      if (Nvl(f($row,'sq_isprojeto'),'')>'')
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'chave').' - '.f($row,'titulo'));
      else
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'codigo').' - '.f($row,'titulo'));
    } else {
      if (Nvl(f($row,'sq_isprojeto'),'')>'')
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'chave').' - '.f($row,'titulo'));
      else
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'codigo').' - '.f($row,'titulo'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>