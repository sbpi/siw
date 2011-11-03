<?php
// =========================================================================
// Montagem da seleção da situação de caixas de arquivamento
// -------------------------------------------------------------------------
function selecaoCaixaCheck($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b>');
  ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="C"'.((nvl($chave,'')=='' || strpos($chave,'C')!==false) ? ' checked': '').'>Arquivada central'); 
  ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="T"'.((nvl($chave,'')=='' || strpos($chave,'T')!==false) ? ' checked': '').'>Trânsito para arq. central'); 
  ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="S"'.((nvl($chave,'')=='' || strpos($chave,'S')!==false) ? ' checked': '').'>Não enviada para arq. central'); 
}
?>
