<?php
// =========================================================================
// Montagem da seleção de unidade monetária
// -------------------------------------------------------------------------
function selecaoMoeda($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1,$separador='<BR />') {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
  $sql = new db_getMoeda; $RS = $sql->getInstanceOf($dbms, null, $restricao, $chaveAux, null, null, null);
  $RS = SortArray($RS,'ativo','desc','nome','asc');
  ShowHTML('          <td '.(($separador=='<BR />') ? 'colspan="'.$colspan.'" ' : ' ').((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
  
    if (nvl(f($row,'sq_moeda'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_moeda').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_moeda').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>