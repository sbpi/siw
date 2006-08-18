<?
include_once($w_dir_volta.'classes/sp/db_getBankHouseList.php');
// =========================================================================
// Montagem da seleção de agências bancárias
// -------------------------------------------------------------------------
function selecaoAgencia($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);

  $RS = db_getBankHouseList::getInstanceOf($dbms, $chaveAux, null, 'padrao desc, codigo asc', null);

  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_agencia'),-1)==nvl($chave,-1)) {
      ShowHTML('          <option value="'.f($row,'sq_agencia').'" SELECTED>'.f($row,'codigo').' - '.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_agencia').'">'.f($row,'codigo').' - '.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
  return $function_ret;
}
?>
