<?php
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
// =========================================================================
// Montagem da seleção da fase de uma solicitação
// -------------------------------------------------------------------------
function selecaoFase($label,$accesskey,$hint,$chave,$chaveAux,$p_solic,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getTramiteList; $rs = $sql->getInstanceOf($dbms, $chaveAux, $p_solic, $restricao,'S');
  $rs = SortArray($rs,'ordem','asc');
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  if ($restricao=='DEVFLUXO') {
    $rs = SortArray($rs,'ordem','desc');
    foreach($rs as $row) {
      ShowHTML('          <option value="'.f($row,'sq_siw_tramite').'"'.((f($row,'sq_siw_tramite')==$chave) ? ' SELECTED' : '').'>'.f($row,'ordem').' - '.f($row,'nome').' ('.f($row,'nm_chefia').')');
    }
  } else {
    foreach($rs as $row) {
      if (!(f($row,'sq_siw_tramite')==$chaveAux && $restricao!='DEVOLUCAO' && f($row,'destinatario')=='N')) {
        ShowHTML('          <option value="'.f($row,'sq_siw_tramite').'"'.((f($row,'sq_siw_tramite')==$chave) ? ' SELECTED' : '').'>'.f($row,'ordem').' - '.f($row,'nome').' ('.f($row,'nm_chefia').')');
      }
    }
  }
  ShowHTML('          </select>');
}
?>