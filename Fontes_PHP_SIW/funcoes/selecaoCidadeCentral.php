<?
include_once($w_dir_volta.'classes/sp/db_getCentralTel.php');
// =========================================================================
// Montagem da seleção de cidade
// -------------------------------------------------------------------------
function SelecaoCidadeCentral($label,$accesskey,$hint,$chave,$campo,$atributo) {
  extract($GLOBALS);

  $RS = db_getCentralTel::getInstanceOf($dbms, null, null, $chave, null, null);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_cidade'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_pessoa_endereco').'" SELECTED>'.f($row,'nm_cidade'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_pessoa_endereco').'">'.f($row,'nm_cidade'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>