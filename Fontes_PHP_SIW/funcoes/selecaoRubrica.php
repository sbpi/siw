<?
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
// =========================================================================
// Montagem da seleção das rubricas de um projeto
// -------------------------------------------------------------------------
function selecaoRubrica($label,$accesskey,$hint,$chave,$chaveAux,$sq_rubrica_destino,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if ($restricao=='RUBRICAS')   $RS = db_getSolicRubrica::getInstanceOf($dbms,$chaveAux,null,'S',$sq_rubrica_destino,null,'N',null);
  else                          $RS = db_getSolicRubrica::getInstanceOf($dbms,$chaveAux,null,'S',$sq_rubrica_destino,null,null,$restricao);
  $RS = SortArray($RS,'codigo','asc','nome','asc');
  if (nvl($label,'')=='') $l_label = ''; else $l_label = '<b>'.$label.'</b><br>';
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td>'.$l_label.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td title="'.$hint.'">'.$l_label.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_projeto_rubrica'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_projeto_rubrica').'" SELECTED>'.f($row,'codigo').' - '.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_projeto_rubrica').'">'.f($row,'codigo').' - '.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>');
} 
?>