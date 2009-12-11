<?php
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');
// =========================================================================
// Montagem da seleção dos colaboradores
// -------------------------------------------------------------------------
function selecaoContaBAnco($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  $l_rs = db_getContaBancoList::getInstanceOf($dbms,$w_cliente,null,'FINANCEIRO');
  $l_rs = SortArray($l_rs,'cd_banco','asc','cd_agencia','asc','numero','asc');
  ShowHTML('          <td '.(($separador=='<BR />') ? 'colspan="'.$colspan.'" ' : ' ').((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($l_rs as $row) {
    ShowHTML('          <option value="'.f($row,'sq_pessoa_conta').'" '.((nvl(f($row,'sq_pessoa_conta'),0)==nvl($chave,0)) ? 'SELECTED' : '').'>BANCO '.f($row,'cd_banco').' AGÊNCIA '.f($row,'cd_agencia').' CONTA '.f($row,'numero'));
  } 
  ShowHTML('          </select>');
}
?>