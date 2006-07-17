<?
include_once($w_dir_volta.'classes/sp/db_getAcaoPPA.php');
// =========================================================================
// Montagem da seleção de ações do PPA(tabela SIGPLAN)
// -------------------------------------------------------------------------
function selecaoAcaoPPA($label,$accesskey,$hint,$p_cliente,$p_ano,$p_programa,$p_acao,$p_subacao,$p_unidade,$campo,$restricao,$atributo,$p_chave,$menu){
  extract($GLOBALS);
  $l_chave=$p_programa.$p_acao.$p_subacao.$p_unidade;
  if ($restricao=='FINANCIAMENTO') {
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$p_cliente,$p_ano,$p_programa,$p_acao,null,$p_unidade,$restricao,$p_chave,null);
    $RS = SortArray($RS,'descricao_acao','asc');
  } elseif ($restricao=='IDENTIFICACAO' || $restricao=='CONSULTA') {
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$p_cliente,$p_ano,null,null,null,null,$restricao,null,null);
    $RS = SortArray($RS,'descricao_acao','asc');
  } else {
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$p_cliente,$p_ano,$p_programa,$p_acao,null,$p_unidade,null,null,null);
    $RS = SortArray($RS,'descricao_acao','asc');
  } 
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),'-')==nvl($l_chave,'-'))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').' - '.substr(f($row,'descricao_acao'),0,40).' ('.substr(f($row,'ds_unidade'),0,30).')');
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'cd_unidade').'.'.f($row,'cd_programa').'.'.f($row,'cd_acao').' - '.substr(f($row,'descricao_acao'),0,40).' ('.substr(f($row,'ds_unidade'),0,30).')');
  } 
  ShowHTML('          </select>');
  ShowHTML('              <a class="ss" href="#" onClick="window.open(\'Acao.php?par=BuscaAcao&TP='.RemoveTP($TP).'&w_cliente='.$p_cliente.'&w_ano='.$p_ano.'&w_programa='.$p_programa.'&w_unidade='.$p_unidade.'&w_acao='.$p_acao.'&w_chave='.$p_chave.'&w_menu='.$menu.'&restricao='.$restricao.'&campo='.$campo.'\',\'Acao\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar a ação."><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>');
}
?>