<?
include_once($w_dir_volta.'classes/sp/db_getRecurso_Disp.php');
// =========================================================================
// Montagem da seleção de períodos de disponibilidade de um recurso
// -------------------------------------------------------------------------
function selecaoDispRecurso($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getRecurso_Disp::getInstanceOf($dbms,$w_cliente,$chaveAux,null,null,null,'REGISTROS');
  $RS = SortArray($RS,'inicio','desc','fim','desc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.formataDataEdicao(f($row,'inicio')).' a '.formataDataEdicao(f($row,'fim')));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.formataDataEdicao(f($row,'inicio')).' a '.formataDataEdicao(f($row,'fim')));
    } 
  } 
  ShowHTML('          </select>');
} 
?>