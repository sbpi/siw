<?
include_once($w_dir_volta.'classes/sp/db_getProgramaPPA_IS.php');
// =========================================================================
// Montagem da seleção de ações do PPA(tabela SIGPLAN)
// -------------------------------------------------------------------------
function selecaoProgramaPPA($label,$accesskey,$hint,$cliente,$ano,$chave,$campo,$restricao,$atributo,$menu) {
  extract($GLOBALS);
  if ($restricao=='IDENTIFICACAO') {
    $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,null,$w_cliente,$w_ano,$restricao,null);
    $RS = SortArray($RS,'ds_programa','asc');
  } elseif ($restricao=='RELATORIO') {
    $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,null,$w_cliente,$w_ano,null,null);
    $RS = SortArray($RS,'ds_programa','asc');
  } else {
    $RS = db_getProgramaPPA_IS::getInstanceOf($dbms,$chave,$w_cliente,$w_ano,$restricao,null);
    $RS = SortArray($RS,'ds_programa','asc');
  } 
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'cd_programa'),'-')==nvl($chave,'-'))
      ShowHTML('          <option value="'.f($row,'cd_programa').'" SELECTED>'.f($row,'cd_programa').' - '.f($row,'ds_programa'));
    else
      ShowHTML('          <option value="'.f($row,'cd_programa').'">'.f($row,'cd_programa').' - '.f($row,'ds_programa'));
  } 
  ShowHTML('          </select>');
  ShowHTML('              <a class="ss" href="#" onClick="window.open(\'programa.php?par=BuscaPrograma&TP='.RemoveTP($TP).'&w_cliente='.$cliente.'&w_ano='.$ano.'&w_menu='.$menu.'&restricao='.$restricao.'&campo='.$campo.'\',\'Programa\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar o programa."><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>');
} 
?>