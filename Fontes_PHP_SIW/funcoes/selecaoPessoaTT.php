<?
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
// =========================================================================
// Montagem da seleção de pessoas para o módulo de tarifação telefônica
// -------------------------------------------------------------------------
function selecaoPessoaTT($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);

  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.'>');
  } 
  ShowHTML('          <option value="">---');

  $RS = db_getPersonList::getInstanceOf($dbms, $chaveAux, $chave, $restricao, null, null, null, null);
  array_key_case_change(&$RS);
  $RS = SortArray($RS,'nome_resumido','asc');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_pessoa'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_pessoa').'" SELECTED>'.f($row,'NOME_RESUMIDO').' ('.f($row,'SG_UNIDADE').')');
    } else {
      ShowHTML('          <option value="'.f($row,'sq_pessoa').'">'.f($row,'NOME_RESUMIDO').' ('.f($row,'SG_UNIDADE').')');
    }
  } 
  ShowHTML('          </select>');
} 
?>