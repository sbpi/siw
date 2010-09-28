<?php 
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
// =========================================================================
// Montagem da seleção de tipos de lançamento
// -------------------------------------------------------------------------
function selecaoTipoLancamento($label,$accesskey,$hint,$chave,$chaveAux,$cliente,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getTipoLancamento; $l_RS = $sql->getInstanceOf($dbms,null,$chaveAux,$cliente,$restricao);
  $l_RS = SortArray($l_RS,'nm_tipo','asc');
  if (Nvl($label,'')>'') $l_label.='<br>'; else $l_label='';
  ShowHTML('          <td colspan="'.$colspan.'" '.((!isset($hint)) ? '' : 'TITLE="'.$hint.'"').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($l_RS as $row) {
    if (substr($restricao,0,4)=='PDSV' || substr($restricao,0,4)=='CLPC' || substr($restricao,0,3)=='FND') {
      // se tela de cadastramento de viagens ou pedidos de compra, mostra apenas o nome do nível folha
      ShowHTML('          <option value="'.f($row,'chave').'" '.(((nvl(f($row,'chave'),0)==nvl($chave,0))) ? 'SELECTED' : '').'>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'" '.(((nvl(f($row,'chave'),0)==nvl($chave,0))) ? 'SELECTED' : '').'>'.f($row,'nm_tipo'));
    }
  } 
  ShowHTML('          </select>');
}
?>