<?
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
// =========================================================================
// Montagem da seleção da fase de uma solicitação
// -------------------------------------------------------------------------
function selecaoFase($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getTramiteList::getInstanceOf($dbms, $chaveAux, $restricao);
  array_key_case_change(&$RS);
  $RS = SortArray($RS,'ordem','asc');
  //$RS->Filter="ativo = 'S'";
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  foreach($RS as $row) {
    if (f($row,'sq_siw_tramite')]==$chave]) {
      ShowHTML('          <option value="'.f($row,'sq_siw_tramite').'" SELECTED>'.f($row,'ordem').' - '.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_siw_tramite').'">'.f($row,'ordem').' - '.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
  return $function_ret;
}
?>
