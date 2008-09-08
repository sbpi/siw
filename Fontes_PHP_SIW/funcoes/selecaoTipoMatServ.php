<?
include_once($w_dir_volta.'classes/sp/db_getTipoMatServ.php');
// =========================================================================
// Montagem da seleção de tipos de material ou serviço
// -------------------------------------------------------------------------
function selecaoTipoMatServ($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,null,$chaveAux,null,null,null,'S',null,$restricao);
  $RS = SortArray($RS,'nome_completo','asc','classe','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.substr(f($row,'nome_completo'),0,strpos(f($row,'nome_completo'),' ')).f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.substr(f($row,'nome_completo'),0,strpos(f($row,'nome_completo'),' ')).f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>