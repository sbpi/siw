<?
include_once($w_dir_volta.'classes/sp/db_getLocalList.php');
// =========================================================================
// Montagem da seleção da localização
// -------------------------------------------------------------------------
function selecaoLocalizacao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getLocalList::getInstanceOf($dbms, $w_cliente, $ChaveAux, $restricao);
  //if (!!isset($chaveAux)) $RS->Filter='sq_unidade = '.$chaveAux;

  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }

  ShowHTML('          <option value="">---');

  foreach ($RS as $row)  {
    if (nvl(f($row,'SQ_LOCALIZACAO'),0) == nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'SQ_LOCALIZACAO').'" SELECTED>'.f($row,'LOCALIZACAO'));
    } else {
      ShowHTML('          <option value="'.f($row,'SQ_LOCALIZACAO').'">'.f($row,'LOCALIZACAO'));
    }
  }
  ShowHTML('          </select>');
  return $function_ret;
}
?>
