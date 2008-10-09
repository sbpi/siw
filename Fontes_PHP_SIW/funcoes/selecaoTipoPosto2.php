<?
include_once($w_dir_volta.'classes/sp/db_getTipoPostoList.php');
// =========================================================================
// Montagem da seleção dos tipos de postos
// -------------------------------------------------------------------------
function selecaoTipoPosto2($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getTipoPostoList::getInstanceOf($dbms,$w_cliente,null,null);
  $RS = SortArray($RS,'descricao','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_eo_tipo_posto'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_eo_tipo_posto').'" SELECTED>'.f($row,'descricao'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_eo_tipo_posto').'">'.f($row,'descricao'));
    } 
  } 
  ShowHTML('          </select>'); 
}
?>