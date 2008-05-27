<?
include_once($w_dir_volta.'classes/sp/db_getTipoGuarda_PA.php');
// =========================================================================
// Montagem da seleção do tipo de guardas
// -------------------------------------------------------------------------
function selecaoTipoGuarda($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if($restricao=='CORRENTE')       $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,null,$w_cliente,null,null,'S',null,null,null,'S',null);
  elseif($restricao=='INTERMED')   $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,null,$w_cliente,null,null,null,'S',null,null,'S',null);
  elseif($restricao=='FINAL')      $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,'S',null,'S',null);
  elseif($restricao=='DESTINACAO') $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,'S','S',null);
  else                             $RS = db_getTipoGuarda_PA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,null,'S',null);
  $RS = SortArray($RS,'descricao','asc');
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'descricao').'('.f($row,'sigla').')');
    } else {
       ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'descricao').'('.f($row,'sigla').')');
    }
  }
  ShowHTML('          </select>');
}
?>
