<?
include_once($w_dir_volta.'classes/sp/db_getKindPersonList.php');
// =========================================================================
// Montagem da seleção do tipo da pessoa
// -------------------------------------------------------------------------
function selecaoTipoPessoa($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getKindPersonList::getInstanceOf($dbms, null);
  if ($restricao>'') { $RS->Filter=$restricao; }
  if (!isset($hint)) {
     ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_tipo_pessoa'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_tipo_pessoa').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_tipo_pessoa').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
