<?
// =========================================================================
// Montagem da seleção de sexo
// -------------------------------------------------------------------------
function selecaoInteresse($label,$accesskey,$hint,$chave,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='N') {
     ShowHTML('          <option value="S">Positivo');
     ShowHTML('          <option value="M" SELECTED>Negativo');
  }
  elseif (Nvl($chave,'')=='S') {
    ShowHTML('          <option value="S" SELECTED>Positivo');
    ShowHTML('          <option value="N">Negativo');
  }
  else {
    ShowHTML('          <option value="S">Positivo');
    ShowHTML('          <option value="N">Negativo');
  }
  ShowHTML('          </select>');
}
?>