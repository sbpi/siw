<?
include_once($w_dir_volta.'classes/sp/db_getGrupoVeiculo.php');
// =========================================================================
// Montagem da seleção dos grupos de veiculos
// -------------------------------------------------------------------------
function selecaoGrupoVeiculo($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getGrupoVeiculo::getInstanceOf($dbms, null, $w_cliente, null, null,'S');
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML(' <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML(' <td valign="top" TITLE="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML(' <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    else
      ShowHTML(' <option value="'.f($row,'chave').'">'.f($row,'nome')); 
  }
  ShowHTML('          </select>');
} 
?>