<?php
include_once($w_dir_volta.'classes/sp/db_getLancamentoProjeto.php');
// =========================================================================
// Montagem da sele��o de tipo de rubrica
// -------------------------------------------------------------------------
function SelecaoTipoRubrica ($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getLinkData; $l_RS = $sql->getInstanceOf($dbms,$w_cliente,$restricao);
  $sql = new db_getLancamentoProjeto; $l_RS1 = $sql->getInstanceOf($dbms,$chaveAux,f($l_RS,'sq_menu'),null);
  ShowHTML('          <td colspan="'.$colspan.'"'.((isset($hint)) ? ' title="'.$hint.'"' : '').'>'.((isset($label)) ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---'); 
  // Se n�o existir outro lan�amento financeiro, trata o atual como sendo dota��o inicial
  if(count($l_RS1)==0) {
    ShowHTML('          <option value="1" '.((nvl($chave,0)==1) ? 'SELECTED' : '').'/>Dota��o Inicial');
  } else {
    ShowHTML('          <option value="2" '.((nvl($chave,0)==2) ? 'SELECTED' : '').'/>Transfer�ncias entre rubricas');
    ShowHTML('          <option value="3" '.((nvl($chave,0)==3) ? 'SELECTED' : '').'/>Atualiza��o de Aplica��o');
    ShowHTML('          <option value="4" '.((nvl($chave,0)==4) ? 'SELECTED' : '').'/>Entradas');
  }
  ShowHTML('          </select>');
} 
?>
