<?php
// =========================================================================
// Montagem da sele��o da classe do material ou servi�o
// -------------------------------------------------------------------------
function selecaoClasseCheck($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']!=10135) { $l_classe[1]['nome'] = 'Medicamento'; $l_classe[1]['marcado'] = false; }
  $l_classe[2]['nome'] = 'Alimento';    $l_classe[2]['marcado'] = false; 
  $l_classe[3]['nome'] = 'Consumo';     $l_classe[3]['marcado'] = false; 
  $l_classe[4]['nome'] = 'Permanente';  $l_classe[4]['marcado'] = false; 
  $l_classe[5]['nome'] = 'Servi�o';     $l_classe[5]['marcado'] = false; 
  if ($restricao=='CONSUMO') {
    unset($l_classe[4]);
    unset($l_classe[5]);
  } elseif ($restricao=='PERMANENTE') {
    unset($l_classe[1]);
    unset($l_classe[2]);
    unset($l_classe[3]);
    unset($l_classe[5]);
  } elseif ($restricao=='SERVICO') {
    unset($l_classe[1]);
    unset($l_classe[2]);
    unset($l_classe[3]);
    unset($l_classe[4]);
  }
  ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b>');
  $l_chave   = $chave.',';
  while (strpos($l_chave,',')!==false) {
    $l_item  = trim(substr($l_chave,0,strpos($l_chave,',')));
    $l_chave = trim(substr($l_chave,(strpos($l_chave,',')+1)));
    if ($l_item > '') $l_classe[$l_item]['marcado'] = true;
  }
  foreach ($l_classe as $k => $v) {
    ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="'.$k.'"'.(($v['marcado'] || count($l_classe)==1) ? ' CHECKED' : '').'>'.$v['nome']); 
  }
}
?>
