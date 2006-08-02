<?
// =========================================================================
// Montagem da URL com os dados de um colaborador
// -------------------------------------------------------------------------
function exibeColaborador($p_dir,$p_cliente,$p_pessoa,$p_tp,$p_nome) {
  extract($GLOBALS);
  if (Nvl($p_nome,'')=='') {
    $l_string = '---';
  } else {
    $l_string = $l_string.'<A class="hl" HREF="#" onClick="window.open(''.$p_dir.'Afastamento.asp?par=TELACOLABORADOR&w_cliente='.$p_cliente.'&w_sq_pessoa='.$p_pessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_TP.'&SG='.$SG.'','Colaborador','width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no'); return false;" title="Clique para exibir os dados deste colaborador!">'.$p_nome.'</A>';
  } 
  $ExibeColaborador = $l_string;
  $l_string         = null; 
} 
?>