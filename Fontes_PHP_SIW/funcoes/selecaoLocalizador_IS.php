<?
include_once($w_dir_volta.'classes/sp/db_getPPALocalizador_IS.php');
// =========================================================================
// Montagem da seleção de ações do PPA(tabela SIGPLAN)
// -------------------------------------------------------------------------
function selecaoLocalizador_IS($label,$accesskey,$hint,$chave,$w_cd_programa,$w_cd_acao,$w_cd_unidade,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getPPALocalizador_IS::getInstanceOf($dbms,$w_cliente,$w_ano,$w_cd_programa,$w_cd_acao,$w_cd_unidade,null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'cd_subacao'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'cd_subacao').'" SELECTED>'.f($row,'Nome'));
    else
      ShowHTML('          <option value="'.f($row,'cd_subacao').'">'.f($row,'Nome'));
  } 
  ShowHTML('          </select>');
} 
?>