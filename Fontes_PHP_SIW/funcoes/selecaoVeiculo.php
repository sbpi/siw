<?
include_once($w_dir_volta.'classes/sp/db_getVeiculo.php');
// =========================================================================
// Montagem da seleção dos grupos de veiculos
// -------------------------------------------------------------------------
function selecaoVeiculo($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo=null) {
  extract($GLOBALS);
  $RS = db_getVeiculo::getInstanceOf($dbms, null, null , $w_cliente, null, null, 'S');
  $RS = SortArray($RS,'modelo','asc');
  if (!isset($hint))
    ShowHTML(' <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML(' <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML(' <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nm_veiculo'));
    else
      ShowHTML(' <option value="'.f($row,'chave').'">'.f($row,'nm_veiculo'));
  }
  ShowHTML('          </select>');
} 
?>