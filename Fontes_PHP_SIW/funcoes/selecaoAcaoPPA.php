<?
include_once($w_dir_volta.'classes/sp/db_getAcaoPPA_IS.php');
// =========================================================================
// Montagem da seleção de ações do PPA(tabela SIGPLAN)
// -------------------------------------------------------------------------
function selecaoAcaoPPA($label,$accesskey,$hint,$v_cliente,$v_ano,$v_programa,$v_acao,$v_subacao,$v_unidade,$campo,$restricao,$atributo,$v_chave,$menu,$macro,$opcao){
  extract($GLOBALS);
  $l_chave = $v_programa.$v_acao.$v_subacao.$v_unidade;
  if ($restricao=='FINANCIAMENTO') {
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$v_cliente,$v_ano,$v_programa,$v_acao,null,$v_unidade,$restricao,$v_chave,null,$macro,$opcao);
    $RS = SortArray($RS,'descricao_acao','asc');
  } elseif ($restricao=='IDENTIFICACAO' || $restricao=='CONSULTA') {
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$v_cliente,$v_ano,null,null,null,null,$restricao,null,null,$macro,$opcao);
    $RS = SortArray($RS,'descricao_acao','asc');
  } else {
    $RS = db_getAcaoPPA_IS::getInstanceOf($dbms,$v_cliente,$v_ano,$v_programa,$v_acao,null,$v_unidade,null,null,null,$macro,$opcao);
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
  ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\''.$w_dir.'acao.php?par=BuscaAcao&TP='.RemoveTP($TP).'&w_cliente='.$v_cliente.'&w_ano='.$v_ano.'&w_programa='.$v_programa.'&w_unidade='.$v_unidade.'&w_acao='.$v_acao.'&w_chave='.$v_chave.'&w_menu='.$menu.'&restricao='.$restricao.'&campo='.$campo.'\',\'Acao\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar a ação."><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>');
}
?>