<?
include_once($w_dir_volta.'classes/sp/db_getTipoIndicador.php');
// =========================================================================
// Montagem da seleção de indicadores
// -------------------------------------------------------------------------
function selecaoIndicador($label,$accesskey,$hint,$chave,$usuario,$tipo_indicador,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$usuario,null,null,null,null,$tipo_indicador,'S',null,null,null,null,null,null,null,null,null,$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome').' ('.f($row,'sg_unidade_medida').')');
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome').' ('.f($row,'sg_unidade_medida').')');
  } 
  ShowHTML('          </select>');
} 
?>