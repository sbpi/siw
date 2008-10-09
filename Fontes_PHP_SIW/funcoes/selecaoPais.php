<?
// =========================================================================
// Montagem da seleção de país
// -------------------------------------------------------------------------
function selecaoPais($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getCountryList.php');
  if ($restricao=='INDICADOR') $RS = db_getCountryList::getInstanceOf($dbms, $restricao, $chaveAux, 'S', null);
  else $RS = db_getCountryList::getInstanceOf($dbms, $restricao, $chaveAux, null, null);
  $RS = SortArray($RS,'padrao','desc','nome','asc');
  if (!isset($hint)) {
     ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }

  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_pais'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_pais').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_pais').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
