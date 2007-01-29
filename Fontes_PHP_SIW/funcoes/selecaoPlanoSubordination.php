<?
include_once($w_dir_volta.'classes/sp/db_getPlanoEstrategico.php');
// =========================================================================
// Montagem da seleção de planos estratégicos
// -------------------------------------------------------------------------
function selecaoPlanoSubordination($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao,$condicao) {
  extract($GLOBALS);
  
  $RS = db_getPlanoEstrategico::getInstanceOf($dbms, $w_cliente, $chave, null, null, null, null, 'S', $restricao);
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <OPTION VALUE="">---');
  foreach($RS as $row)  {
    // Testa se o plano já tem solicitações vinculadas. Se tiver, não pode ser pai de nenhum outro tipo
    // Garante que as solicitações sempre estarão ligadas no nível folha da tabela de planos estratégicos
    if ($restricao=='SUBHERDA' || (f($row,'qt_solic')==0 && f($row,'qt_menu')==0)) {
      if (f($row,'chave')==nvl($chave_aux,0)) {
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
      } else {
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
      }
    }
  }
  ShowHTML('          </SELECT></td>');
}
?>
