<?
include_once($w_dir_volta.'classes/sp/db_getCategoriaDiaria.php');
// =========================================================================
// Montagem da sele��o de companhias de viagem
// -------------------------------------------------------------------------
function selecaoCategoriaDiaria($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);

  $RS = db_getCategoriaDiaria::getInstanceOf($dbms,$cliente,null,null,'S',$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint)) {
    if ($label=='') {
      ShowHTML('          <td colspan="'.$colspan.'"><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
      ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } 
  } else {
    if ($label=='') {
      ShowHTML('          <td colspan="'.$colspan.'"><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
      ShowHTML('          <td valign="top" TITLE="'.$hint.'" colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } 
  } 
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>
