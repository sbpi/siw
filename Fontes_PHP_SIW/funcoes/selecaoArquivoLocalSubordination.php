<?php
include_once($w_dir_volta.'classes/sp/db_getArquivo_PA.php');
// =========================================================================
// Montagem da seleção de Tipos estratégicos
// -------------------------------------------------------------------------
function selecaoArquivoLocalSubordination($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao,$condicao,$formato=1,$colspan=1,$separador='<BR />') {
  extract($GLOBALS); 
    
  $sql = new db_getArquivo_PA; $RS = $sql->getInstanceOf($dbms, $w_cliente, $chave, $chave_aux, null, 'S', $restricao);  
  $RS = SortArray($RS,'nome','asc'); 
  
  ShowHTML('          <td '.(($separador=='<BR />') ? 'colspan="'.$colspan.'" ' : ' ').((isset($hint)) ? 'title="'.$hint.'"' : '').'><b>'.$label.'</b>'.$separador.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <OPTION VALUE="">---');
  
  foreach($RS as $row)  {  
    ShowHTML('          <option value="'.f($row,'chave').'" '.((f($row,'chave')==nvl($_REQUEST['pai'],0)) ? 'SELECTED' : '').'>'.f($row,'nome_completo'));
  }
  ShowHTML('          </SELECT></td>');
}
?>
