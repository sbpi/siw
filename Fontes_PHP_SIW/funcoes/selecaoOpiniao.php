<? 
// =========================================================================
// Montagem da seleção de opiniões
// -------------------------------------------------------------------------
function selecaoOpiniao($label,$accesskey,$hint,$chave,$cliente,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getOpiniao.php');
  $l_RS = db_getOpiniao::getInstanceOf($dbms,null,$cliente,null,null,$restricao);
  $l_RS = SortArray($l_RS,'ordem','asc','nome','asc');
  if ($atributo=='SELECT') {
    if (Nvl($label,'')>'') $l_label=$label.'<br>'; else $l_label='';
    if (!isset($hint))
      ShowHTML('          <td colspan="'.$colspan.'"><b>'.$l_label.'</b><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    else
      ShowHTML('          <td colspan="'.$colspan.'" TITLE="'.$hint.'"><b>'.$l_label.'</b><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    ShowHTML('          <option value="">---');
    foreach ($l_RS as $row) {
      if (nvl(f($row,'sigla'),'')==nvl($chave,''))
        ShowHTML('          <option value="'.f($row,'sigla').'" SELECTED>'.f($row,'nome'));
      else
        ShowHTML('          <option value="'.f($row,'sigla').'">'.f($row,'nome'));
    } 
    ShowHTML('          </select>');
  } elseif ($atributo=='CHECKBOX') {
    foreach ($l_RS as $row) {
      if (f($row,'sigla')==nvl($chave,'')) {
        ShowHTML('          <input type="radio" name="'.$campo.'" value="'.f($row,'sigla').'" CHECKED> <b>'.f($row,'nome'));
        if (f($row,'sigla')=='IN') ShowHTML('          (é necessário descrever o motivo da insatisfaçao)');
        ShowHTML('          </b><br>');
      } else {
        ShowHTML('          <input type="radio" name="'.$campo.'" value="'.f($row,'sigla').'"> <b>'.f($row,'nome'));
        if (f($row,'sigla')=='IN') ShowHTML('          (é necessário descrever o motivo da insatisfaçao)');
        ShowHTML('          </b><br>');
      }
    }
  }
}
?>