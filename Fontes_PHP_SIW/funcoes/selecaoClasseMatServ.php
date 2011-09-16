<?php
// =========================================================================
// Montagem da seleção da classe do material ou serviço
// -------------------------------------------------------------------------
function selecaoClasseMatServ($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  ShowHTML('          <td colspan="'.$colspan.'"'.((!isset($hint)) ? '' : ' title="'.$hint.'"').'>'.((!isset($label)) ? '' : '<b>'.$label.'</b><br>').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  if ($_SESSION['P_CLIENTE']!=10135) ShowHTML('          <option value="1"'.((Nvl($chave,'')=='1') ? ' SELECTED' : '').'>Medicamento');
  ShowHTML('          <option value="2"'.((Nvl($chave,'')=='2') ? ' SELECTED' : '').'>Alimentos');
  ShowHTML('          <option value="3"'.((Nvl($chave,'')=='3') ? ' SELECTED' : '').'>Material de consumo');
  ShowHTML('          <option value="4"'.((Nvl($chave,'')=='4') ? ' SELECTED' : '').'>Material permanente');
  ShowHTML('          <option value="5"'.((Nvl($chave,'')=='5') ? ' SELECTED' : '').'>Serviço');
  ShowHTML('          </select>');
}
?>