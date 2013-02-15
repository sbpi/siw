<?php
include_once($w_dir_volta . 'classes/sp/db_getPersonList.php');

// =========================================================================
// Montagem da seleção de pessoas
// -------------------------------------------------------------------------
function selecaoPessoaOrigem($label, $accesskey, $hint, $chave, $chaveAux, $campo, $tipo_pessoa, $restricao, $atributo, $colspan=1, $mandatory=null, $obj_solic=null, $separador='<br />') {
  extract($GLOBALS);
  include_once($w_dir_volta . 'classes/sp/db_getBenef.php');
  ShowHTML('<INPUT type="hidden" name="' . $campo . '" value="' . $chave . '">');
  if (strpos($campo,'[]')!==false) {
    ShowHTML('<INPUT type="hidden" name="obj_origem[]" value="' . $chave . '">');
  } else {
    ShowHTML('<INPUT type="hidden" name="obj_origem" value="' . $chave . '">');
  }
  if ($chave > '') {
    $sql = new db_getBenef; $l_rs = $sql->getInstanceOf($dbms, $w_cliente, $chave, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
    foreach ($l_rs as $row) { $l_rs = $row; break; }
    $l_pessoa = f($l_rs, 'nm_pessoa');
  }

  ShowHTML('          <td colspan="'.$colspan.'" '.((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador);
  ShowHTML('          <input READONLY ACCESSKEY="' . $accesskey . '" CLASS="sti" type="text" name="'.((strpos($campo,'[]')!==false) ? substr($campo,0,strpos($campo,'[')) : $campo).'_nm'.((strpos($campo,'[]')!==false) ? '[]' : '').'" SIZE="40" VALUE="' . substr($l_pessoa,0,40) . '" ' . $atributo . '>');
  ShowHTML('          <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\'' . $conRootSIW . 'pessoa.php?par=BuscaPessoa&TP=' . $TP . '&restricao=' . $restricao . '&mandatory=' . lower($mandatory) . '&p_tipo_pessoa=' . $tipo_pessoa . '&SG=' . $SG . '&campo=' . $campo . '\',\'Pessoa\',\'top=10,left=10,width=780,height=550,toolbar=no,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar a pessoa."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
  if (strpos($campo,'[]')!==false) {
    ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick=\'document.Form["'.substr($campo,0,strpos($campo,'[')).'_nm'.'[]"].value=""; document.Form["'.$campo.'"].value=""; return false;\' title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0 align=top height=15 width=15></a>');
  } else {
    ShowHTML('          <a class="ss" HREF="javascript:this.status.value;" onClick="document.Form.' . $campo . '_nm.value=\'\'; document.Form.' . $campo . '.value=\'\'; return false;" title="Clique aqui para apagar o valor deste campo."><img src="images/Folder/Recyfull.gif" border=0 align=top height=15 width=15></a>');
  }
}

?>