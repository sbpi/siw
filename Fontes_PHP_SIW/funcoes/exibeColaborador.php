<?
// =========================================================================
// Montagem da URL com os dados de um colaborador
// -------------------------------------------------------------------------
function exibeColaborador($l_dir,$l_cliente,$l_pessoa,$l_TP,$l_nome) {
  extract($GLOBALS);
  if (Nvl($l_nome,'')=='') {
    $l_string = '---';
  } else {
    $l_string = $l_string.'<A class="hl" HREF="#" onClick="window.open(\''.$l_dir.'afastamento.php?par=TELACOLABORADOR&w_cliente='.$l_cliente.'&w_sq_pessoa='.$l_pessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$l_TP.'&SG='.$SG.'\',\'Colaborador\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados deste colaborador!">'.$l_nome.'</A>';
  } 
  return $l_string;
} 
?>