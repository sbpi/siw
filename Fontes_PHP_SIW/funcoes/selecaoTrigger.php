<?
include_once($w_dir_volta.'classes/sp/db_getTrigger.php');
// =========================================================================
// Montagem da seleção de tipos de tabela
// -------------------------------------------------------------------------
function selecaoTrigger($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$chaveAux2,$chaveAux3,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getTrigger::getInstanceOf($dbms,$cliente,$chave,$chaveAux3,$chaveAux2,$chaveAux);
  $RS = SortArray($RS,'nm_trigger','asc','nm_sistema','asc','nm_usuario','asc');
  if (Nvl($hint,'')>'')
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (Nvl(f($row,'chave'),0)==Nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nm_trigger'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nm_trigger'));
  } 
  ShowHTML('          </select>');
}
?>