<?
include_once($w_dir_volta.'classes/sp/db_getVincKindList.php');
// =========================================================================
// Montagem da seleção dos tipos de vínculo
// -------------------------------------------------------------------------
function selecaoVinculo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$ativo, $tipo_pessoa, $interno) {
  extract($GLOBALS);
  $RS = db_getVincKindList::getInstanceOf($dbms, $w_cliente, $ativo, $tipo_pessoa, null, $interno);
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_tipo_vinculo'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_tipo_vinculo').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_tipo_vinculo').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
