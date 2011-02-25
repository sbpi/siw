<?php 
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getMenuRelac.php');
// =========================================================================
// Montagem da seleção dos acordos
// -------------------------------------------------------------------------
function selecaoAcordo($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (strpos('0123456789',substr($restricao,0,1))!==false) {
    $sql = new db_getMenuRelac; $RS1 = $sql->getInstanceOf($dbms, $restricao, null, null, null, null);
    if (count($RS1)>0) {
      $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,$chaveAux,$w_usuario,$restricao,3,
              null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
              null,null,null,$l_fase,null,null,null,null,null);
    }
  } else {
      $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,$chaveAux,$w_usuario,$restricao,4,
            null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
            null,null,null,$l_fase,null,null,null,null,null);    
  }
  $RS = SortArray($RS,'titulo','asc', 'inicio', 'asc');
  ShowHTML('          <td colspan="'.$colspan.'" '.((isset($hint)) ? 'TITLE="'.$hint.'"' : '').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (f($row,'sg_modulo')=='AC') {
      ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'" '.((nvl(f($row,'sq_siw_solicitacao'),0)==nvl($chave,0)) ? 'SELECTED' : '').'>'.f($row,'titulo').' ('.f($row,'codigo_interno').')');
    }
  } 
  ShowHTML('          </select>');
} 
?>