<?
include_once($w_dir_volta.'classes/sp/db_getCiaTrans.php');
// =========================================================================
// Montagem da seleção de companhias de viagem
// -------------------------------------------------------------------------
function selecaoCiaTrans($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);

  $RS = db_getCiaTrans:getInstanceOf($dbms,$cliente,null,null,null,null,null,null,'S',null,null);
  $RS = SortArray($RS,'padrao','desc','nome','asc');
  if (!isset($hint)) {
    if ($label=='') {
      ShowHTML('          <td><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
      ShowHTML('          <td><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } 
  } else {
    if ($label=='') {
      ShowHTML('          <td><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
      ShowHTML('          <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } 
  } 
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($RS,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>
