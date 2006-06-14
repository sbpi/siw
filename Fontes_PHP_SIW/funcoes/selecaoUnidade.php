<?
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
// =========================================================================
// Montagem da seleção das unidades organizacionais
// -------------------------------------------------------------------------
function selecaoUnidade($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);

  if (isset($restricao)) {
    $RS = db_getUorgList::getInstanceOf($dbms, $w_cliente, $ChaveAux, $restricao, null, null, null);
  } else {
    $RS = db_getUorgList::getInstanceOf($dbms, $w_cliente, $ChaveAux, 'ATIVO', null, null, null);
  }

  ShowHTML('<INPUT type="hidden" name="'.$campo.'" value="'.$chave.'">');
  if ($chave>'') {
    $RS = db_getUorgList::getInstanceOf($dbms, $w_cliente, $chave, null, null, null, null);
    foreach ($RS as $row) {
      $w_nm_unidade=f($row,'nome');
      $w_sigla=f($row,'sigla');
    }
  }

  if (!isset($hint)) {
    ShowHTML('      <td valign="top"><font size="1"><b>'.$label.'</b><br>');
    ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="sti" type="text" name="'.$campo.'_nm'.'" SIZE="60" VALUE="'.$w_nm_unidade.'" '.$atributo.'>');
  } else {
    ShowHTML('      <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br>');
    ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="sti" type="text" name="'.$campo.'_nm'.'" SIZE="60" VALUE="'.$w_nm_unidade.'" '.$atributo.'>');
  }

  ShowHTML('              <a class="ss" href="#" onClick="window.open(\''.str_replace('/files','',$conFileVirtual).'eo.php?par=BuscaUnidade&TP='.$TP.'&w_cliente='.$w_cliente.'&ChaveAux='.$ChaveAux.'&restricao='.$restricao.'&campo='.$campo.'\',\'Unidade\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar a unidade."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
  ShowHTML('              <a class="ss" href="#" onClick="document.Form.'.$campo.'_nm.value=\'\'; document.Form.'.$campo.'.value=\'\'; return false;" title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0 align=top height=15 width=15></a>');
  return $function_ret;
}
?>
