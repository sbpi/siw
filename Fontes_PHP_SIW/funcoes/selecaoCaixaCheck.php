<?php
// =========================================================================
// Montagem da sele��o da situa��o de caixas de arquivamento
// -------------------------------------------------------------------------
function selecaoCaixaCheck($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b>');
  ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="C"'.((nvl($chave,'')=='' || strpos($chave,'C')!==false) ? ' checked': '').'>Arquivada central'); 
  ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="T"'.((nvl($chave,'')=='' || strpos($chave,'T')!==false) ? ' checked': '').'>Tr�nsito para arq. central'); 
  ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="S"'.((nvl($chave,'')=='' || strpos($chave,'S')!==false) ? ' checked': '').'>N�o enviada para arq. central'); 
}
?>
