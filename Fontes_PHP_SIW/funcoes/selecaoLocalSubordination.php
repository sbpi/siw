<?
include_once($w_dir_volta.'classes/sp/db_getAlmoxarifado.php');
// =========================================================================
// Montagem da sele��o de Tipos estrat�gicos
// -------------------------------------------------------------------------
function selecaoLocalSubordination($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao,$condicao) {
  extract($GLOBALS);
  $RS = db_getAlmoxarifado::getInstanceOf($dbms, $w_cliente, $chave, $chave_aux, null, null, null, $restricao, 'S', null);
  
  $RS = SortArray($RS,'nome','asc'); 
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <OPTION VALUE="">---');
  foreach($RS as $row)  {
    // Testa se o tipo j� tem recursos vinculados. Se tiver, n�o pode ser pai de nenhum outro tipo
    // Garante que os recursos sempre estar�o ligados no n�vel folha da tabela de tipos de recurso
      if (f($row,'chave')==nvl($_REQUEST['pai'],0)) {
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome_completo'));
      } else {
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome_completo'));
      }
  }
  ShowHTML('          </SELECT></td>');
}
?>
