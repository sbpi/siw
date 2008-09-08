<? 
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
// =========================================================================
// Montagem da seleção de tipos de lançamento
// -------------------------------------------------------------------------
function selecaoTipoLancamento($label,$accesskey,$hint,$chave,$cliente,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $l_RS = db_getTipoLancamento::getInstanceOf($dbms,null,$cliente,$restricao);
  $l_RS = SortArray($l_RS,'ordena','asc');
  if (Nvl($label,'')>'') $l_label=$label.'<br>'; else $l_label='';
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$l_label.'</b><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" TITLE="'.$hint.'"><b>'.$l_label.'</b><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($l_RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
  } 
  ShowHTML('          </select>');
}
?>