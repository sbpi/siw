<?
include_once($w_dir_volta.'classes/sp/db_getTipoVeiculo.php');
// =========================================================================
// Montagem da sele��o dos grupos de veiculos
// -------------------------------------------------------------------------
function selecaoTipoVeiculo($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getTipoVeiculo::getInstanceOf($dbms, null, $w_cliente, null, null, null,'S');
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML(' <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML(' <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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