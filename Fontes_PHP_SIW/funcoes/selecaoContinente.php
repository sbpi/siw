<?
// =========================================================================
// Montagem da sele��o de continente
// -------------------------------------------------------------------------
function selecaoContinente($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
   if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   if ($chave==3) ShowHTML('          <option value=3 SELECTED>�sia');    else ShowHTML('          <option value=3>�sia'); 
   if ($chave==4) ShowHTML('          <option value=4 SELECTED>�frica');  else ShowHTML('          <option value=4>�frica'); 
   if ($chave==1) ShowHTML('          <option value=1 SELECTED>Am�rica'); else ShowHTML('          <option value=1>Am�rica'); 
   if ($chave==2) ShowHTML('          <option value=2 SELECTED>Europa');  else ShowHTML('          <option value=2>Europa'); 
   if ($chave==5) ShowHTML('          <option value=5 SELECTED>Oceania'); else ShowHTML('          <option value=5>Oceania'); 
   ShowHTML('          </select>');
}
?>