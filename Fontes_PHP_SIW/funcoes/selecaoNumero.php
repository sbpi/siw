<?php
include_once($w_dir_volta.'classes/sp/db_getCTEspecificacao.php');
// =========================================================================
// Montagem da seleção de número a partir de lista
// -------------------------------------------------------------------------
function selecaoNumero($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$inicio,$fim) {
  extract($GLOBALS);
  ShowHTML('          <td valign="top"'.((!isset($hint)) ? ' TITLE="'.$hint.'"' : '').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  for($l_cont=$inicio;$l_cont<=$fim;$l_cont++) {
    if ($l_cont==nvl($chave,0)) {
      ShowHTML('          <option value="'.$l_cont.'" SELECTED>'.$l_cont);
    } else {
      ShowHTML('          <option value="'.$l_cont.'">'.$l_cont);
    } 
  }
  ShowHTML('          </select>');
} 
?>