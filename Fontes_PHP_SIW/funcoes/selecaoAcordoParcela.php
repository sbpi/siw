<? 
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoParcela.php');
// =========================================================================
// Montagem da seleção de parcelas de um acordo
// -------------------------------------------------------------------------
function selecaoAcordoParcela($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'GC'.substr($SG,2,1).'CAD');
  $l_menu = f($RS1,'sq_menu');
  $RS = db_getAcordoParcela::getInstanceOf($dbms,$chaveAux,null,$restricao,null,null,null,$w_usuario,"'EE','ER'",$l_menu,null);
  $RS = SortArray($RS,'ordem','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_acordo_parcela'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'sq_acordo_parcela').'" SELECTED>'.substr(1000+f($row,'ordem'),1,3).' - '.FormataDataEdicao(f($row,'vencimento')).' - '.number_format(f($row,'valor'),2,',','.'));
    else
     ShowHTML('          <option value="'.f($row,'sq_acordo_parcela').'">'.substr(1000+f($row,'ordem'),1,3).' - '.FormataDataEdicao(f($row,'vencimento')).' - '.number_format(f($row,'valor'),2,',','.'));
  } 
  ShowHTML('          </select>');
}
?>