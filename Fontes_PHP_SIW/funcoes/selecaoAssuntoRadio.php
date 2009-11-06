<?
// =========================================================================
// Montagem da seleção de assuntos
// -------------------------------------------------------------------------
function selecaoAssuntoRadio($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
  ShowHTML('<INPUT type="hidden" name="'.$campo.'" value="'.$chave.'">');
  if ($chave>'') {
    $RS = db_getAssunto_PA::getInstanceOf($dbms,$w_cliente,$chave,null,null,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS as $row) { $RS = $row; break; }
    $l_assunto = strtolower(f($row,'codigo').' - '.f($row,'descricao'));
  }

  ShowHTML('          <td '.(($separador=='<BR />') ? 'colspan="'.$colspan.'" ' : ' ').((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador);
  ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="sti" type="text" name="'.$campo.'_nm'.'" SIZE="80" VALUE="'.$l_assunto.'" '.$atributo.'>');
  ShowHTML('          <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.'mod_pa/documento.php?par=BuscaAssunto&TP='.$TP.'&w_ano='.$w_ano.'&w_cliente='.$w_cliente.'&chaveAux='.$chaveAux.'&restricao='.$restricao.'&SG='.$SG.'&campo='.$campo.'\',\'Assunto\',\'top=10,left=10,width=780,height=550,toolbar=no,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar o assunto."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
  ShowHTML('          <a class="ss" HREF="javascript:this.status.value;" onClick="document.Form.'.$campo.'_nm.value=\'\'; document.Form.'.$campo.'.value=\'\'; return false;" title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0 align=top height=15 width=15></a>');
}
?>
