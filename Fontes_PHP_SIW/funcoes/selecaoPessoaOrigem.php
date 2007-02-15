<?
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
// =========================================================================
// Montagem da seleção de pessoas
// -------------------------------------------------------------------------
function selecaoPessoaOrigem($label,$accesskey,$hint,$chave,$chaveAux,$campo,$nome,$tipo_pessoa,$restricao) {
  extract($GLOBALS);
  $RS = db_getPersonList::getInstanceOf($dbms, $w_cliente, $chaveAux, $restricao, $nome, null, null, $tipo_pessoa);
  $RS = SortArray($RS,'nome_resumido_ind','asc');
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
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