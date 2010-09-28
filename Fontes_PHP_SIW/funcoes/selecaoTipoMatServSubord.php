<?php
include_once($w_dir_volta.'classes/sp/db_getTipoMatServ.php');
// =========================================================================
// Montagem da seleção de tipos de material ou serviço
// -------------------------------------------------------------------------
function selecaoTipoMatServSubord($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms, $w_cliente, $chave, null, null, null, null, 'S', null, $restricao);
  $RS = SortArray($RS,'nome_completo','asc'); 
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <OPTION VALUE="">---');
  foreach($RS as $row)  {
    // Testa se o tipo já tem recursos vinculados. Se tiver, não pode ser pai de nenhum outro tipo
    // Garante que os recursos sempre estarão ligados no nível folha da tabela de tipos de recurso
    if (f($row,'qt_recursos')==0) {
      if (f($row,'chave')==nvl($chave_aux,0)) {
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome_completo'));
      } else {
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome_completo'));
      }
    }
  }
  ShowHTML('          </SELECT></td>');
}
?>
