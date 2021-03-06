<?php
include_once($w_dir_volta.'classes/sp/db_getMacroObjetivo_IS.php');
// =========================================================================
// Montagem da sele��o dos macros objetivos
// -------------------------------------------------------------------------
function selecaoMacroObjetivo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if($restricao=='ATIVO') { $sql = new db_getMacroObjetivo_IS; $RS = $sql->getInstanceOf($dbms,null,$chaveAux,null,'S'); }
  else                    { $sql = new db_getMacroObjetivo_IS; $RS = $sql->getInstanceOf($dbms,null,$chaveAux,null,null); }
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint)) {
     ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,'')) {
       ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>