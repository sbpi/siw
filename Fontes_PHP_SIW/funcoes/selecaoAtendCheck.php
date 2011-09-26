<?php
// =========================================================================
// Montagem da seleção do atendimento/cumprimento
// -------------------------------------------------------------------------
function selecaoAtendCheck($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $l_atend[1]['nome'] = 'Integralmente'; $l_atend[1]['marcado'] = ((nvl($chave,'')=='') ? true : false);
  $l_atend[2]['nome'] = 'Parcialmente';  $l_atend[2]['marcado'] = ((nvl($chave,'')=='') ? true : false);
  $l_atend[3]['nome'] = 'Não';           $l_atend[3]['marcado'] = false; 
  ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b>');
  $l_chave   = $chave.',';
  while (strpos($l_chave,',')!==false) {
    $l_item  = trim(substr($l_chave,0,strpos($l_chave,',')));
    $l_chave = trim(substr($l_chave,(strpos($l_chave,',')+1)));
    if ($l_item > '') $l_atend[$l_item]['marcado'] = true;
  }
  foreach ($l_atend as $k => $v) {
    ShowHTML('          <br/><input type="CHECKBOX" name="'.$campo.'" value="'.$k.'"'.(($v['marcado'] || count($l_atend)==1) ? ' CHECKED' : '').'>'.$v['nome']); 
  }
}
?>
