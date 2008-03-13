<?
// =========================================================================
// Montagem da seleção de fontes de pesquisa
// -------------------------------------------------------------------------
function selecaoFontePesquisa($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
   if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.((nvl($label,'')!='') ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1">'.((nvl($label,'')!='') ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   if (Nvl($chave,'')=='SA') ShowHTML('          <option value="SA" SELECTED>Site ARP');                else ShowHTML('          <option value="SA">Site ARP');
   if (Nvl($chave,'')=='SG') ShowHTML('          <option value="SG" SELECTED>Site do governo');         else ShowHTML('          <option value="SG">Site do governo');
   if (Nvl($chave,'')=='SF') ShowHTML('          <option value="SF" SELECTED>Site do fornecedor');      else ShowHTML('          <option value="SF">Site do fornecedor');
   if (Nvl($chave,'')=='PF') ShowHTML('          <option value="PF" SELECTED>Proposta do fornecedor');  else ShowHTML('          <option value="PF">Proposta do fornecedor');
   ShowHTML('          </select>');
}
?>