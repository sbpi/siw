<?
include_once($w_dir_volta.'classes/sp/db_getPrograma_IS.php');
// =========================================================================
// Montagem da seleção de programas cadastrados no INFRASIG
// -------------------------------------------------------------------------
function selecaoProgramaIS($label,$accesskey,$hint,$cliente,$ano,$chave,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if ($restricao=='CADASTRADOS') {
    $RS = db_getPrograma_IS::getInstanceOf($dbms,${'w_cd_programa'},$w_ano,$w_cliente,$restricao);
    $RS = SortArray($RS,'titulo','asc');
  } 
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'titulo'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'titulo'));
  } 
  ShowHTML('          </select>');
}
?>