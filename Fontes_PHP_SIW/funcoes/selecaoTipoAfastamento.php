<?php
include_once($w_dir_volta.'classes/sp/db_getGPTipoAfast.php'); 
// =========================================================================
// Montagem da seleção dos tipos de afastamentos
// -------------------------------------------------------------------------
function selecaoTipoAfastamento($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  if ($restricao=='AFASTAMENTO') {
    $RS = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,null,null,'S',null,null,$restricao);
    $RS = SortArray($RS,'nome','asc');
  } else {
    $RS = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null);
    $RS = SortArray($RS,'nome','asc');
  }
  ShowHTML('          <td '.(($separador=='<BR />') ? 'colspan="'.$colspan.'" ' : ' ').((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>'); 
}
?>