<?
// =========================================================================
// Montagem da seleção de sexo
// -------------------------------------------------------------------------
function selecaoFormato($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
   if (Nvl($hint,'')=='') {
      ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   if (Nvl($chave,'')=='W') {
      ShowHTML('          <option value="A">Arquivo');
      ShowHTML('          <option value="W" SELECTED>Web service');
      ShowHTML('          <option value="T">TXT');
   }
   elseif (Nvl($chave,'')=='A') {
     ShowHTML('          <option value="A" SELECTED>Arquivo');
     ShowHTML('          <option value="W">Web service');
     ShowHTML('          <option value="T">TXT');
   }
   elseif (Nvl($chave,'')=='T') {
     ShowHTML('          <option value="A">Arquivo');
     ShowHTML('          <option value="W">Web service');
     ShowHTML('          <option value="T" SELECTED>TXT');
   }   
   else {
     ShowHTML('          <option value="A">Arquivo');
     ShowHTML('          <option value="W">Web service');
     ShowHTML('          <option value="T">TXT');
   }
   ShowHTML('          </select>');
}
?>