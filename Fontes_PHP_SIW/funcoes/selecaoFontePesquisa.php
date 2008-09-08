<?
// =========================================================================
// Montagem da seleção de fontes de pesquisa
// -------------------------------------------------------------------------
function selecaoFontePesquisa($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.((nvl($label,'')!='') ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'">'.((nvl($label,'')!='') ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='SA') ShowHTML('          <option value="SA" SELECTED>ARP externa');          else ShowHTML('          <option value="SA">ARP externa');
  if (Nvl($chave,'')=='SG') ShowHTML('          <option value="SG" SELECTED>Governo');              else ShowHTML('          <option value="SG">Governo');
  if (Nvl($chave,'')=='SF') ShowHTML('          <option value="SF" SELECTED>Site comercial');       else ShowHTML('          <option value="SF">Site comercial');
  if (Nvl($chave,'')=='PF') ShowHTML('          <option value="PF" SELECTED>Proposta fornecedor');  else ShowHTML('          <option value="PF">Proposta fornecedor');
  ShowHTML('          </select>');
}
?>