<?
include_once($w_dir_volta.'classes/sp/db_getStoredProcedure.php');
// =========================================================================
// Montagem da sele��o de Tabela
// -------------------------------------------------------------------------
function selecaoSP($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getStoredProcedure::getInstanceOf($dbms,$cliente,null,$chave,null,null,$chaveAux2,null,$restricao);
  $RS = SortArray($RS,'nm_sp','asc');
  if (Nvl($hint,'')>'')
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (Nvl(f($row,'chave'),0)==Nvl($chaveAux,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nm_usuario').'.'.f($row,'nm_sp'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nm_usuario').'.'.f($row,'nm_sp'));
  } 
  ShowHTML('          </select>');
} 
?>