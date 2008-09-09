<?
include_once($w_dir_volta.'classes/sp/db_getConvOutraParte.php');
// =========================================================================
// Montagem da seleção das outras partes de um contrato
// -------------------------------------------------------------------------
function selecaoOutraParte($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getConvOutraParte::getInstanceOf($dbms,null,$chaveAux,null,null);
  $RS = SortArray($RS,'nome_resumido','asc');
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_acordo_outra_parte'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'sq_acordo_outra_parte').'" SELECTED>'.f($row,'nome_resumido'));
    else
      ShowHTML('          <option value="'.f($row,'sq_acordo_outra_parte').'">'.f($row,'nome_resumido'));
  } 
  ShowHTML('          </select>');
} 
?>