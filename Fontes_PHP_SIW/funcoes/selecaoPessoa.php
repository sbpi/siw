<?
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
// =========================================================================
// Montagem da seleção de pessoas
// -------------------------------------------------------------------------
function selecaoPessoa($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo=null) {
  extract($GLOBALS);
  $RS = db_getPersonList::getInstanceOf($dbms, $w_cliente, $chaveAux, $restricao, null, null, null, null);
  $RS = SortArray($RS,'nome_resumido_ind','asc');
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_pessoa'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_pessoa').'" SELECTED>'.f($row,'nome_resumido').' ('.f($row,'sg_unidade').')');
    } else {
       ShowHTML('          <option value="'.f($row,'sq_pessoa').'">'.f($row,'nome_resumido').' ('.f($row,'sg_unidade').')');
    }
  }
  ShowHTML('          </select>');
}
?>