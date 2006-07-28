<? 
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
// =========================================================================
// Montagem da seleção dos acordos
// -------------------------------------------------------------------------
function selecaoAcordo($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getSolicList::getInstanceOf($dbms,$chaveAux,$w_usuario,$restricao,3,
          null,null,null,null,null,null,
          null,null,null,null,
          null,null,null,null,null,null,null,
          null,null,null,$l_fase,null,null,null,null,null);
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_siw_solicitacao'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'" SELECTED>'.f($row,'titulo'));
    else
      ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'">'.f($row,'titulo'));
  } 
  ShowHTML('          </select>');
} 
?>