<?
include_once($w_dir_volta.'classes/sp/db_getFoneTypeList.php');
// =========================================================================
// Montagem da seleção do tipo de endereco
// -------------------------------------------------------------------------
function selecaoTipoFone($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getFoneTypeList::getInstanceOf($dbms, $chaveAux, null, null);
  $RS = SortArray($RS,'nome','asc');
  //if ($restricao>'') {
  //  $RS->Filter=$restricao;
  //}
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_tipo_telefone'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_tipo_telefone').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_tipo_telefone').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
  return $function_ret;
}
?>
