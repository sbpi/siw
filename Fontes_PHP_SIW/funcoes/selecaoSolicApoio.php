<?php
// =========================================================================
// Montagem da seleção de fontes de recurso de projetos
// -------------------------------------------------------------------------
function selecaoSolicApoio($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getSolicApoioList.php');
  $sql = new db_getSolicApoioList; $RS = $sql->getInstanceOf($dbms, $chaveAux, null, null, null);
  $RS = SortArray($RS,'entidade','asc');
  ShowHTML('          <td colspan="'.$colspan.'" '.((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    ShowHTML('          <option value="'.f($row,'sq_solic_apoio').'"'.((nvl(f($row,'sq_solic_apoio'),0)==nvl($chave,0)) ? ' SELECTED' : '').'>'.f($row,'entidade'));
  }
  ShowHTML('          </select>');
}
?>