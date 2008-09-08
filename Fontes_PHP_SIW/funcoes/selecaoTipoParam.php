<?
// =========================================================================
// Montagem da seleção de Tipos de Arquivo
// -------------------------------------------------------------------------
function selecaoTipoParam($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (Nvl($hint,'')>'')
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  if ($chave=='E')
    ShowHTML('          <option value="E" SELECTED>Entrada');
  else
    ShowHTML('          <option value="E">Entrada');
  if ($chave=='S')
    ShowHTML('          <option value="S" SELECTED>Saída');
  else
    ShowHTML('          <option value="S">Saída');
  if ($chave=='A')
    ShowHTML('          <option value="A" SELECTED>Ambos');
  else
    ShowHTML('          <option value="A">Ambos');
  ShowHTML('          </select>');
} 
?>