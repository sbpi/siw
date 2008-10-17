<?
// =========================================================================
// Montagem da seleção de protocolos
// -------------------------------------------------------------------------
function selecaoProtocolo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $atributo = str_replace('onChange','onBlur',$atributo);
  ShowHTML('<INPUT type="hidden" name="'.$campo.'" value="'.$chave.'">');
  if ($chave>'') {
    // Recupera os dados do documento
    $l_rs = db_getSolicData::getInstanceOf($dbms,$chave,'PADCAD');
    $l_protocolo = f($l_rs,'protocolo');
  }

  if (!isset($hint)) {
    ShowHTML('      <td colspan="'.$colspan.'"><b>'.$label.'</b><br>');
    ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="sti" type="text" name="'.$campo.'_nm'.'" SIZE="20" VALUE="'.$w_nm_unidade.'" '.$atributo.'>');
  } else {
    ShowHTML('      <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br>');
    ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="sti" type="text" name="'.$campo.'_nm'.'" SIZE="20" VALUE="'.$w_nm_unidade.'" '.$atributo.'>');
  }

  ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.'mod_pa/documento.php?par=BuscaProtocolo&TP=Seleção de protocolo&SG=PADCAD&w_ano='.$w_ano.'&w_cliente='.$w_cliente.'&chaveAux='.$chaveAux.'&restricao='.$restricao.'&campo='.$campo.'\',\'Protocolo\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar um protocolo."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
  ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick="document.Form.'.$campo.'_nm.value=\'\'; document.Form.'.$campo.'.value=\'\'; return false;" title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0 align=top height=15 width=15></a>');
}
?>
