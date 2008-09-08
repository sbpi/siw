<?
include_once($w_dir_volta.'classes/sp/db_getAdressTypeList.php');
// =========================================================================
// Montagem da seleção do tipo de endereco
// -------------------------------------------------------------------------
function selecaoTipoEndereco($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);

  $RS = db_getAdressTypeList::getInstanceOf($dbms, $chaveAux, null, null);
  $RS = SortArray($RS,'nm_tipo_pessoa','asc','nome','asc');

  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---'); 
  foreach($RS as $row) {
    if (nvl(f($row,'sq_tipo_endereco'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_tipo_endereco').'" SELECTED>'.f($row,'nm_tipo_pessoa').' - '.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_tipo_endereco').'">'.f($row,'nm_tipo_pessoa').' - '.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
