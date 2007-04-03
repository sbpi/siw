<?
include_once($w_dir_volta.'classes/sp/db_getRecurso.php');
// =========================================================================
// Montagem da seleção dos recursos
// -------------------------------------------------------------------------
function selecaoRecurso($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getRecurso::getInstanceOf($dbms,$w_cliente,$w_usuario,null,$chaveAux,$chaveAux2,null,null,'S',$restricao);
  $RS = SortArray($RS,'nome','asc');
  $atributo = str_replace('onBlur','onChange',$atributo);
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome').((nvl(f($row,'codigo'),'nulo')=='nulo') ? '' : ' ('.f($row,'codigo').')'));
    } else {
       ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome').((nvl(f($row,'codigo'),'nulo')=='nulo') ? '' : ' ('.f($row,'codigo').')'));
    }
  }
  ShowHTML('          </select>');
}
?>
