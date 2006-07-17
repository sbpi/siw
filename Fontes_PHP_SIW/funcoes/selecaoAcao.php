<?
include_once($w_dir_volta.'classes/sp/db_getAcao_IS.php');
// =========================================================================
// Montagem da seleção de ações cadastradas
// -------------------------------------------------------------------------
function selecaoAcao($label,$accesskey,$hint,$p_cliente,$p_ano,$p_programa,$p_acao,$p_subacao,$p_unidade,$campo,$restricao,$atributo,$chave) {
  extract($GLOBALS);
  $RS = db_getAcao_IS::getInstanceOf($dbms,null,null,null,$w_ano,$w_cliente,$restricao,null);
  $RS = SortArray($RS,'titulo','asc');
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($RS,'chave'),0)==nvl($chave,0)) {
      if (Nvl(f($RS,'sq_isprojeto'),'')>'')
        ShowHTML('          <option value="'.f($RS,'chave').'" SELECTED>'.f($RS,'chave').' - '.f($RS,'titulo'));
      else
        ShowHTML('          <option value="'.f($RS,'chave').'" SELECTED>'.f($RS,'codigo').' - '.f($RS,'titulo'));
    } else {
      if (Nvl(f($RS,'sq_isprojeto'),'')>'')
        ShowHTML('          <option value="'.f($RS,'chave').'">'.f($RS,'chave').' - '.f($RS,'titulo'));
      else
        ShowHTML('          <option value="'.f($RS,'chave').'">'.f($RS,'codigo').' - '.f($RS,'titulo'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>