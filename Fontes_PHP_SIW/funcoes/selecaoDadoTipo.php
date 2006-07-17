<?
include_once($w_dir_volta.'classes/sp/db_getTipoDado.php');
// =========================================================================
// Montagem da seleção de tipos de dado
// -------------------------------------------------------------------------
function selecaoDadoTipo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getTipoDado::getInstanceOf($dbms,null);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'')
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (Nvl(f($row,'chave'),0)==Nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
  } 
  ShowHTML('          </select>');
} 
?>