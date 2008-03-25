<?
// =========================================================================
// Montagem da seleção de fontes de pesquisa
// -------------------------------------------------------------------------
function selecaoFontePesquisa($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.((nvl($label,'')!='') ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1">'.((nvl($label,'')!='') ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='SA') ShowHTML('          <option value="SA" SELECTED>ARP externa');          else ShowHTML('          <option value="SA">ARP externa');
  if (Nvl($chave,'')=='SG') ShowHTML('          <option value="SG" SELECTED>Governo');              else ShowHTML('          <option value="SG">Governo');
  if (Nvl($chave,'')=='SF') ShowHTML('          <option value="SF" SELECTED>Site comercial');       else ShowHTML('          <option value="SF">Site comercial');
  if (Nvl($chave,'')=='PF') ShowHTML('          <option value="PF" SELECTED>Proposta fornecedor');  else ShowHTML('          <option value="PF">Proposta fornecedor');
  ShowHTML('          </select>');
}
?>