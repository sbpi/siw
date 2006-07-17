<?
include_once($w_dir_volta.'classes/sp/db_getArquivo.php');
// =========================================================================
// Montagem da seleção de Tipos de Arquivo
// -------------------------------------------------------------------------
function selecaoArquivo($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getArquivo::getInstanceOf($dbms,$cliente,null,$chaveAux,null,null);
  $RS = SortArray($RS,'nm_arquivo','asc');
  if (Nvl($hint,'')>'')
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (Nvl(f($row,'chave'),0)==Nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nm_arquivo'));
    else
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nm_arquivo'));
  } 
  ShowHTML('          </select>');
} 
?>