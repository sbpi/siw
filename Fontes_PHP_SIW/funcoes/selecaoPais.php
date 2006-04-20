<?
include_once($w_dir_volta.'classes/sp/db_getCountryList.php');
// =========================================================================
// Montagem da seleção de país
// -------------------------------------------------------------------------
function selecaoPais($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  array_key_case_change(&$RS);
  $RS = SortArray($RS,'padrao','asc','nome','asc');
  $RS = db_getCountryList::getInstanceOf($dbms);
  //if ($restricao>'') { $RS->Filter=$restricao; }
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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
