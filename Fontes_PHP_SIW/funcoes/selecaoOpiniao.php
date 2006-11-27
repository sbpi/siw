<? 
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
// =========================================================================
// Montagem da seleção de opiniões
// -------------------------------------------------------------------------
function selecaoOpiniao($label,$accesskey,$hint,$chave,$cliente,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $l_RS = db_getOpiniao::getInstanceOf($dbms,null,$cliente,$restricao);
  $l_RS = SortArray($l_RS,'ordem','asc','nome','asc');
  if ($atributo=='SELECT') {
    if (Nvl($label,'')>'') $l_label=$label.'<br>'; else $l_label='';
    if (!isset($hint))
      ShowHTML('          <td valign="top"><font size="1"><b>'.$l_label.'</b><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    else
      ShowHTML('          <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$l_label.'</b><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    ShowHTML('          <option value="">---');
    foreach ($l_RS as $row) {
      if (nvl(f($row,'chave'),0)==nvl($chave,0))
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
      else
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
    ShowHTML('          </select>');
  } elseif ($atributo=='CHECKBOX') {
    foreach ($l_RS as $row) {
      if (f($row,'chave')==nvl($chave,0))
        ShowHTML('          <input type="radio" name="'.$campo.'" value="'.f($row,'chave').'" CHECKED> <b>'.f($row,'nome').'</b><br>');
      else
        ShowHTML('          <input type="radio" name="'.$campo.'" value="'.f($row,'chave').'"> <b>'.f($row,'nome').'</b><br>');
    }
  }
}
?>