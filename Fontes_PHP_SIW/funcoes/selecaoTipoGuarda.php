<?php
include_once($w_dir_volta.'classes/sp/db_getTipoGuarda_PA.php');
// =========================================================================
// Montagem da sele��o do tipo de guardas
// -------------------------------------------------------------------------
function selecaoTipoGuarda($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if($restricao=='CORRENTE')       { $sql = new db_getTipoGuarda_PA; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,null,'S',null,null,null,'S',null); }
  elseif($restricao=='INTERMED')   { $sql = new db_getTipoGuarda_PA; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,null,null,'S',null,null,'S',null); }
  elseif($restricao=='FINAL')      { $sql = new db_getTipoGuarda_PA; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,null,null,null,'S',null,'S',null); }
  elseif($restricao=='DESTINACAO') { $sql = new db_getTipoGuarda_PA; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,'S','S',null); }
  else                             { $sql = new db_getTipoGuarda_PA; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,null,'S',null); }
  $RS = SortArray($RS,'descricao','asc');
  if (!isset($hint)) {
     ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
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
