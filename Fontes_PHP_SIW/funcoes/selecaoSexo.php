<?
// =========================================================================
// Montagem da seleção de sexo
// -------------------------------------------------------------------------
function selecaoSexo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
   if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   if (Nvl($chave,'')=='M') {
      ShowHTML('          <option value="F">Feminino');
      ShowHTML('          <option value="M" SELECTED>Masculino');
   }
   elseif (Nvl($chave,'')=='F') {
     ShowHTML('          <option value="F" SELECTED>Feminino');
     ShowHTML('          <option value="M">Masculino');
   }
   else {
     ShowHTML('          <option value="F">Feminino');
     ShowHTML('          <option value="M">Masculino');
   }
   ShowHTML('          </select>');
}
?>