<?
// =========================================================================
// Montagem da seleção de procedimentos desejados para o transporte
// -------------------------------------------------------------------------
function selecaoProcedimentoTransp($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
   if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   if (Nvl($chave,'1')=='1') {
      ShowHTML('          <option value="1" SELECTED>Somente levar ao destino');
      ShowHTML('          <option value="2">Levar ao destino e aguardar');
      ShowHTML('          <option value="3">Somente buscar');
      ShowHTML('          <option value="4">Abastecimento (uso exclusivo do setor de tráfego)');
   } elseif (Nvl($chave,'')=='2') {
     ShowHTML('          <option value="1">Somente levar ao destino');
     ShowHTML('          <option value="2" SELECTED>Levar ao destino e aguardar');
     ShowHTML('          <option value="3">Somente buscar');
     ShowHTML('          <option value="4">Abastecimento (uso exclusivo do setor de tráfego)');
   } elseif (Nvl($chave,'')=='3') {
     ShowHTML('          <option value="1">Somente levar ao destino');
     ShowHTML('          <option value="2">Levar ao destino e aguardar');
     ShowHTML('          <option value="3" SELECTED>Somente buscar');
     ShowHTML('          <option value="4">Abastecimento (uso exclusivo do setor de tráfego)');
   } else {
     ShowHTML('          <option value="1">Somente levar ao destino');
     ShowHTML('          <option value="2">Levar ao destino e aguardar');
     ShowHTML('          <option value="3">Somente buscar');
     ShowHTML('          <option value="4" SELECTED>Abastecimento (uso exclusivo do setor de tráfego)');
   }
   ShowHTML('          </select>');
}
?>