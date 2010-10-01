<?php
include_once($w_dir_volta.'classes/sp/db_getSolicList_IS.php');
// =========================================================================
// Montagem da seleção das tarefas
// -------------------------------------------------------------------------
function selecaoTarefa($label,$accesskey,$hint,$cliente,$ano,$p_chave,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'ISTCAD');
  $sql = new db_getSolicList_IS; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'ISTCAD',3,
          null,null,null,null,null,null,
          null,null,null,null,
          null,null,null,null,null,null,null,
          null,null,null,null,$restricao,null,null,null,null,null,$w_ano);
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if ($cDbl[nvl(f($row,'sq_siw_solicitacao'),0)]==$cDbl[nvl($p_chave,0)])
      ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'" SELECTED>'.f($row,'titulo').' ('.f($row,'sq_siw_solicitacao').')');
    else
      ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'">'.f($row,'titulo').' ('.f($row,'sq_siw_solicitacao').')');
  } 
  ShowHTML('          </select>');
} 
?>