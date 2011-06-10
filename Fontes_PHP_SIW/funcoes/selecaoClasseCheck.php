<?php
// =========================================================================
// Montagem da seleção da classe do material ou serviço
// -------------------------------------------------------------------------
function selecaoClasseCheck($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $l_classe[1]['nome'] = 'Medicamento'; $l_classe[1]['marcado'] = false; 
  $l_classe[2]['nome'] = 'Alimento';    $l_classe[2]['marcado'] = false; 
  $l_classe[3]['nome'] = 'Consumo';     $l_classe[3]['marcado'] = false; 
  $l_classe[4]['nome'] = 'Permanente';  $l_classe[4]['marcado'] = false; 
  $l_classe[5]['nome'] = 'Serviço';     $l_classe[5]['marcado'] = false; 
  ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b>');
  $l_chave   = $chave.',';
  while (strpos($l_chave,',')!==false) {
    $l_item  = trim(substr($l_chave,0,strpos($l_chave,',')));
    $l_chave = trim(substr($l_chave,(strpos($l_chave,',')+1)));
    if ($l_item > '') $l_classe[$l_item]['marcado'] = true;
  }
  foreach ($l_classe as $k => $v) {
    ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="'.$k.'"'.(($v['marcado']) ? ' CHECKED': '').'>'.$v['nome']); 
  }
}
?>
