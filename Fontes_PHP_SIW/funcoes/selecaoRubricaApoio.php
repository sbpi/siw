<?php
include_once($w_dir_volta.'classes/sp/db_getCronograma.php');
// =========================================================================
// Montagem da seleção das fontes de financiamento de uma rubrica de projeto
// -------------------------------------------------------------------------
function selecaoRubricaApoio($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getCronograma; $RS = $sql->getInstanceOf($dbms,$chaveAux,null,null,null,null,'RUBFONTES');
  $RS = SortArray($RS,'entidade','asc','sq_solic_apoio','asc');
  if (nvl($label,'')=='') $l_label = ''; else $l_label = '<b>'.$label.'</b><br>';
  if (Nvl($hint,'') !='') $l_hint  = ''; else $l_hint  = ' title="'.$hint.'"';
  ShowHTML('          <td colspan="'.$colspan.'"'.$l_hint.'>'.$l_label.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    ShowHTML('          <option value="'.f($row,'sq_solic_apoio').'"'.((nvl(f($row,'sq_solic_apoio'),0)==nvl($chave,0)) ? ' SELECTED' : '').'>'.f($row,'entidade'));
  } 
  ShowHTML('          </select>');
} 
?>