<?php
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');
// =========================================================================
// Montagem da seleção dos colaboradores
// -------------------------------------------------------------------------
function selecaoContaBAnco($label,$accesskey,$hint,&$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  $sql = new db_getContaBancoList; $l_rs = $sql->getInstanceOf($dbms,$w_cliente,null,'FINANCEIRO');
  $l_rs = SortArray($l_rs,'cd_banco','asc','cd_agencia','asc','numero','asc');
  ShowHTML('          <td '.(($separador=='<BR />') ? 'colspan="'.$colspan.'" ' : ' ').((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($l_rs as $row) {
    $w_selected = '';
    if (nvl(f($row,'sq_pessoa_conta'),0)==nvl($chave,0) || count($l_rs)==1) { $w_selected = ' SELECTED'; $chave = f($row,'sq_pessoa_conta'); }
    ShowHTML('          <option value="'.f($row,'sq_pessoa_conta').'" '.$w_selected.'> '.f($row,'nm_banco').' AG. '.f($row,'cd_agencia').' C/C '.f($row,'numero').((nvl(f($row,'sg_moeda'),'')!='') ? ' ('.f($row,'sg_moeda').')' : ''));
  } 
  ShowHTML('          </select>');
}
?>