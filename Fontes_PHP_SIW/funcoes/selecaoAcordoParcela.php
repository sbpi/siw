<?php 
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoParcela.php');
// =========================================================================
// Montagem da seleção de parcelas de um acordo
// -------------------------------------------------------------------------
function selecaoAcordoParcela($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'GC'.substr($SG,2,1).'CAD');
  $l_menu = f($RS1,'sq_menu');
  $sql = new db_getAcordoParcela; $RS = $sql->getInstanceOf($dbms,$chaveAux,null,$restricao,null,null,null,$w_usuario,"'EE','ER'",$l_menu,null);
  $RS = SortArray($RS,'ordem','asc');
  ShowHTML('          <td colspan="'.$colspan.'"'.((!isset($hint)) ? '' : ' TITLE="'.$hint.'"').'>'.((!isset($label)) ? '' : '<b>'.$label.'</b><br>').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    ShowHTML('          <option value="'.f($row,'sq_acordo_parcela').'"'.((nvl(f($row,'sq_acordo_parcela'),0)==nvl($chave,0)) ? ' SELECTED' : '').'>'.substr(1000+f($row,'ordem'),1,3).' - '.FormataDataEdicao(f($row,'vencimento')).' - '.((nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').number_format(f($row,'valor'),2,',','.'));
  } 
  ShowHTML('          </select>');
}
?>