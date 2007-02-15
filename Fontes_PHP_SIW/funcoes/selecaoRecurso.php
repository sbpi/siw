<?
include_once($w_dir_volta.'classes/sp/db_getRecurso.php');
// =========================================================================
// Montagem da seleção dos recursos
// -------------------------------------------------------------------------
function selecaoRecurso($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getRecurso::getInstanceOf($dbms,$w_cliente,$w_usuario,null,$chaveAux,$chaveAux2,null,null,'S',$restricao);
  if (count($RS)<=50) {
    $RS = SortArray($RS,'nome','asc');
    $atributo = str_replace('onBlur','onChange',$atributo);
    if (!isset($hint)) {
       ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
       ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    }
    ShowHTML('          <option value="">---');
    foreach($RS as $row) {
      if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
         ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome').((nvl(f($row,'codigo'),'nulo')=='nulo') ? '' : ' ('.f($row,'codigo').')'));
      } else {
         ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome').((nvl(f($row,'codigo'),'nulo')=='nulo') ? '' : ' ('.f($row,'codigo').')'));
      }
    }
    ShowHTML('          </select>');
  } else {
    $atributo = str_replace('onChange','onBlur',$atributo);
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

    ShowHTML('              <a class="ss" href="#" onClick="window.open(\''.$conRootSIW.'eo.php?par=BuscaUnidade&TP='.$TP.'&w_ano='.$w_ano.'&w_cliente='.$w_cliente.'&chaveAux='.$chaveAux.'&restricao='.$restricao.'&campo='.$campo.'\',\'Unidade\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar a unidade."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
    ShowHTML('              <a class="ss" href="#" onClick="document.Form.'.$campo.'_nm.value=\'\'; document.Form.'.$campo.'.value=\'\'; return false;" title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0 align=top height=15 width=15></a>');
  }
}
?>
