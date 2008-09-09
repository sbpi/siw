<?
include_once($w_dir_volta.'classes/sp/db_getOpcaoEstrat_IS.php');
// =========================================================================
// Montagem da seleção das opções estratégicas
// -------------------------------------------------------------------------
function selecaoOpcaoEstrat($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if($restricao=='ATIVO') $RS = db_getOpcaoEstrat_IS::getInstanceOf($dbms,null,null,'S');
  else                    $RS = db_getOpcaoEstrat_IS::getInstanceOf($dbms,null,null,null);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint)) {
     ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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