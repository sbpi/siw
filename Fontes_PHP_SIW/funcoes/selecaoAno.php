<?php
include_once($w_dir_volta.'classes/sp/db_getCTEspecificacao.php');
// =========================================================================
// Montagem da seleção de anos
// -------------------------------------------------------------------------
function selecaoAno($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$anos=2,$inicio, $fim) {
  extract($GLOBALS);
  ShowHTML('          <td valign="top"'.((!isset($hint)) ? ' TITLE="'.$hint.'"' : '').'><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');

  $sql = new db_getCTEspecificacao; $l_RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,'ANOS');
  $l_cont=strftime('%Y',(time()))-$anos;
  if($restricao=='ESPEC') {
    while($l_cont<(strftime('%Y',(time()))+($anos+1))) {
      $l_teste = 'sim';
      foreach($l_RS as $l_row) {
         if(f($l_row,'ano')==$l_cont) $l_teste = 'nao'; 
      }
      if($l_teste=='sim') {
        if (nvl($l_cont,0)==nvl($chave,0)) {
          ShowHTML('          <option value="'.$l_cont.'" SELECTED>'.$l_cont);
        } else {
          ShowHTML('          <option value="'.$l_cont.'">'.$l_cont);
        } 
      }
      $l_cont += 1;
    } 
  } elseif($restricao=='ESPEC2') {
    while($l_cont<(strftime('%Y',(time()))+($anos+1))) {
      $l_teste = 'sim';
      foreach($l_RS as $l_row) {
         if(f($l_row,'ano')==$l_cont) $l_teste = 'nao'; 
      }
      if($l_teste=='nao') {
        if (nvl($l_cont,0)==nvl($chave,0)) {
          ShowHTML('          <option value="'.$l_cont.'" SELECTED>'.$l_cont);
        } else {
          ShowHTML('          <option value="'.$l_cont.'">'.$l_cont);
        } 
      }
      $l_cont += 1;
    } 

  } else {
    while($l_cont<(strftime('%Y',(time()))+($anos+1))) {
      if (nvl($l_cont,0)==nvl($chave,0)) {
        ShowHTML('          <option value="'.$l_cont.'" SELECTED>'.$l_cont);
      } else {
        ShowHTML('          <option value="'.$l_cont.'">'.$l_cont);
      } 
      $l_cont += 1;
    } 
  }
  ShowHTML('          </select>');
} 
?>