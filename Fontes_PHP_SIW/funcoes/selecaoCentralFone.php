<?
include_once($w_dir_volta.'classes/sp/db_getCentralTel.php');
// =========================================================================
// Montagem da seleção de centrais telefônicas
// -------------------------------------------------------------------------
function selecaoCentralFone($label,$accesskey,$hint,$chave,$campo,$atributo) {
  extract($GLOBALS);

  $RS = db_getCentralTel::getInstanceOf($dbms, null, null, null, null, null);

  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'logradouro'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'logradouro'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>