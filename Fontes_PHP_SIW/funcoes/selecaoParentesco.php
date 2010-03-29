<?php
// =========================================================================
// Montagem da seleção de parentesco
// -------------------------------------------------------------------------
/*
Indica o tipo de vínculo: 
10 - Mãe; 
20 - Pai; 
30 - Madrasta; 
40 - Padrasto; 
50 - Cônjuge; 
55 - Companheiro(a); 
60 - Filha; 
70 - Filho; 
80 - Enteada; 
90 - Enteado.

*/
function selecaoParentesco($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
extract($GLOBALS);
   if (!isset($hint)) {
      ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('      <option value="">---');
   ShowHTML('      <option value="10" '.((Nvl($chave,'')=='10') ? 'SELECTED' : '').'>Mãe');
   ShowHTML('      <option value="20" '.((Nvl($chave,'')=='20') ? 'SELECTED' : '').'>Pai');
   ShowHTML('      <option value="30" '.((Nvl($chave,'')=='30') ? 'SELECTED' : '').'>Madrasta');
   ShowHTML('      <option value="40" '.((Nvl($chave,'')=='40') ? 'SELECTED' : '').'>Padrasto');
   ShowHTML('      <option value="50" '.((Nvl($chave,'')=='50') ? 'SELECTED' : '').'>Cônjuge');      
   ShowHTML('      <option value="55" '.((Nvl($chave,'')=='55') ? 'SELECTED' : '').'>Companheiro(a)');
   ShowHTML('      <option value="60" '.((Nvl($chave,'')=='60') ? 'SELECTED' : '').'>Filha');
   ShowHTML('      <option value="70" '.((Nvl($chave,'')=='70') ? 'SELECTED' : '').'>Filho');
   ShowHTML('      <option value="71" '.((Nvl($chave,'')=='71') ? 'SELECTED' : '').'>Irmã');
   ShowHTML('      <option value="72" '.((Nvl($chave,'')=='72') ? 'SELECTED' : '').'>Irmão');
   ShowHTML('      <option value="80" '.((Nvl($chave,'')=='80') ? 'SELECTED' : '').'>Enteada');
   ShowHTML('      <option value="90" '.((Nvl($chave,'')=='90') ? 'SELECTED' : '').'>Enteado');   
   ShowHTML('    </select>');
}
?>