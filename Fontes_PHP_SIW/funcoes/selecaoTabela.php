<?
include_once($w_dir_volta.'classes/sp/db_getTabela.php');
// =========================================================================
// Montagem da seleção de Tabela
// -------------------------------------------------------------------------
function selecaoTabela($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getTabela::getInstanceOf($dbms,$cliente,null,null,$chaveAux2,$chaveAux,null,null,$restricao);
  $RS = SortArray($RS,'nm_usuario','asc','nome','asc');
  if (Nvl($hint,'')>'')
    ShowHTML(' <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML(' <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (Nvl(f($row,'chave'),0)==Nvl($chave,0)) {
      if (Nvl($chaveAux,'nulo')=='nulo')
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nm_usuario').'.'.f($row,'nome'));
      else
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
      if (Nvl($chaveAux,'nulo')=='nulo')
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nm_usuario').'.'.f($row,'nome'));
      else
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>');
} 

?>