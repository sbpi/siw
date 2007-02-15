<?
include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
// =========================================================================
// Montagem da seleção de assuntos
// -------------------------------------------------------------------------
function selecaoAssunto($label,$accesskey,$hint,$chave,$chaveAux,$campo,$descricao,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,null,null,null,$descricao,null,null,null,null,'S',$restricao);
  $RS = SortArray($RS,'descricao','asc');
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_assunto'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_assunto').'" SELECTED>'.f($row,'codigo').' - '.f($row,'descricao'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_assunto').'">'.f($row,'codigo').' - '.f($row,'descricao'));
    }
  }
  ShowHTML('          </select>');
}
?>
