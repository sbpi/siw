<?
include_once($w_dir_volta.'classes/sp/db_getTTUsuario.php');
// =========================================================================
// Montagem da seleção de usuários da central telefônica
// -------------------------------------------------------------------------
function selecaoTTUsuario($label,$accesskey,$hint,$chave,$chaveAux,$campo,$atributo) {
  extract($GLOBALS);

  $RS = db_getTTUsuario::getInstanceOf($dbms, null, null, $chave, null, null);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (intval(nvl(f($row,'usuario'),0))==intval(nvl($chave,0))) {
      ShowHTML('          <option value="'.f($row,'usuario').'" SELECTED>'.f($row,'nm_usuario'));
    } else {
      ShowHTML('          <option value="'.f($row,'usuario').'">'.f($row,'nm_usuario'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>