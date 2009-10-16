<?php
include_once($w_dir_volta.'classes/sp/db_getGPColaborador.php'); 
// =========================================================================
// Montagem da seleção dos colaboradores
// -------------------------------------------------------------------------
function selecaoColaborador($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getGPColaborador::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  $RS = SortArray($RS,'nome_resumido','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  if(count($RS) && nvl($restricao,'')=='SELAFAST' && $O=='I' && $w_sg_afast !== false){
    ShowHTML('          <option value="0">Todos');
  }
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_contrato_colaborador'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_contrato_colaborador').'" SELECTED>'.f($row,'nome_resumido'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_contrato_colaborador').'">'.f($row,'nome_resumido'));
    } 
  } 
  ShowHTML('          </select>');
  ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\'afastamento.php?par=BuscaColaborador&TP='.RemoveTP($TP).'&w_cliente='.$w_cliente.'&chaveAux='.$chaveAux.'&w_menu='.$w_menu.'&restricao='.$restricao.'&campo='.$campo.'\',\'Colaborador\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar o colaborador."><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>');
}
?>