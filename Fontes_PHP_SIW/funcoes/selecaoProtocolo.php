<?php
// =========================================================================
// Montagem da seleção de protocolos
// -------------------------------------------------------------------------
function selecaoProtocolo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  $atributo = str_replace('onChange','onBlur',$atributo);
  ShowHTML('<INPUT type="hidden" name="'.$campo.'" value="'.$chave.'">');

  ShowHTML('          <td colspan="'.$colspan.'" '.((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador);
  ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="sti" type="text" name="'.((strpos($campo,'[]')!==false) ? substr($campo,0,strpos($campo,'[')) : $campo).'_nm'.((strpos($campo,'[]')!==false) ? '[]' : '').'" SIZE="23" VALUE="'.$chave.'" '.$atributo.'>');

  ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.'mod_pa/documento.php?par=BuscaProtocolo&TP=Seleção de protocolo&SG=PADCAD&w_ano='.$w_ano.'&w_cliente='.$w_cliente.'&chaveAux='.$chaveAux.'&restricao='.$restricao.'&campo='.$campo.'\',\'Protocolo\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar um protocolo."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
  if (strpos($campo,'[]')!==false) {
    ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick=\'document.Form["'.substr($campo,0,strpos($campo,'[')).'_nm'.'[]"].value=""; document.Form["'.$campo.'"].value=""; return false;\' title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0 align=top height=15 width=15></a>');
  } else {
    ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick="document.Form.'.$campo.'_nm.value=\'\'; document.Form.'.$campo.'.value=\'\'; return false;" title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0 align=top height=15 width=15></a>');
  }
}
?>
