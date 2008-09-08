<?
include_once($w_dir_volta.'classes/sp/db_getIsUnidade_IS.php');
// =========================================================================
// Rotina de selecao das unidades de planejamento e administrativas do modulo infra-sig
// -------------------------------------------------------------------------
function selecaoUnidade_IS($label,$accesskey,$hint,$chave,$chaveAux,$campo,$atributo,$tipo) {
  extract($GLOBALS);
  if ($tipo=='ADMINISTRATIVA')
    $RS = db_getIsUnidade_IS::getInstanceOf($dbms,null,$w_cliente,'S',null);
  elseif ($tipo=='PLANEJAMENTO')
    $RS = db_getIsUnidade_IS::getInstanceOf($dbms,null,$w_cliente,null,'S');
  $RS = SortArray($RS,'nome','asc');
  if (count($RS)>100) {
    ShowHTML('<INPUT type="hidden" name="'.$campo.'" value="'.$chave.'">');
    if ($chave>'') {
      $RS = db_getIsUnidade_IS::getInstanceOf($dbms,$chave,$w_cliente,null,null);
      foreach ($RS as $row) {
        $w_nm_unidade = f($row,'nome');
        $w_sigla      = f($row,'sigla');
      }
    } 
    if (!isset($hint)) {
      ShowHTML('      <td valign="top"><font size="1"><b>'.$label.'</b><br>');
      ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="STS" type="text" name="'.$campo.'_nm'.'" SIZE="60" VALUE="'.$w_nm_unidade.'" '.$atributo.'>');
    } else {
      ShowHTML('      <td valign="top"title="'.$hint.'"><font size="1"><b>'.$label.'</b><br>');
      ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="STS" type="text" name="'.$campo.'_nm'.'" SIZE="60" VALUE="'.$w_nm_unidade.'" '.$atributo.'>');
    } 
    ShowHTML('              <a class="SS" HREF="javascript:this.status.value;" onClick="window.open(\''.$w_dir_volta.'eo.php?par=BuscaUnidade&TP='.$TP.'&w_cliente='.$w_cliente.'&ChaveAux='.$ChaveAux.'&restricao='.$restricao.'&campo='.$campo.'\',\'Unidade\',\'top=70 left=100 width=600 height=400 toolbar=yes status=yes resizable=yes scrollbars=yes\'); return false;" title="Clique aqui para selecionar a unidade."><img src=images/Folder/Explorer.gif border=0></a>');
    ShowHTML('              <a class="SS" HREF="javascript:this.status.value;" onClick="document.Form.'.$campo.'_nm.value=\'\'; document.Form.'.$campo.'.value=\'\'; return false;" title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0></a>');
  } else {
    if (!isset($hint))
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    else
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    ShowHTML('          <option value="">---');
    foreach($RS as $row) {
      if (nvl(f($row,'chave'),0)==nvl($chave,0))
        ShowHTML('          <OPTION VALUE="'.f($row,'chave').'" SELECTED>'.f($row,'Nome').' ('.f($row,'Sigla').')');
      else
        ShowHTML('          <OPTION VALUE="'.f($row,'chave').'">'.f($row,'Nome').' ('.f($row,'Sigla').')');
    } 
    ShowHTML('          </select>');
  } 
} 
?>