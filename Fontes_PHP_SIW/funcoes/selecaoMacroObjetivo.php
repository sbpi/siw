<?
include_once($w_dir_volta.'classes/sp/db_getMacroObjetivo_IS.php');
// =========================================================================
// Montagem da seleção dos macros objetivos
// -------------------------------------------------------------------------
function selecaoMacroObjetivo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if($restricao=='ATIVO') $RS = db_getMacroObjetivo_IS::getInstanceOf($dbms,null,$chaveAux,null,'S');
  else                    $RS = db_getMacroObjetivo_IS::getInstanceOf($dbms,null,$chaveAux,null,null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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