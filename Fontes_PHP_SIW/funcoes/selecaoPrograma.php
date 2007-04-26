<?
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getMenuRelac.php');
// =========================================================================
// Montagem da seleção de Programas
// -------------------------------------------------------------------------
function selecaoPrograma($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo,$formato=1) {
  extract($GLOBALS);

  if (is_numeric($restricao)) {
    $RS1 = db_getMenuRelac::getInstanceOf($dbms, $restricao, null, null, null, null);
  } else {
   $RS1 = array(0);
  }
  if (count($RS1)>0) {
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PEPROCAD');
    $RS = db_getSolicList::getInstanceOf($dbms, f($RS,'sq_menu'), $w_usuario, f($RS,'sigla'), 4, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $chaveAux);
    $RS = SortArray($RS,'titulo','asc');

    if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    }
    ShowHTML('          <option value="">---');
    foreach($RS as $row) {
      if (nvl(f($row,'sq_siw_solicitacao'),0)==nvl($chave,0)) {
        if($formato==1) ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'" SELECTED>'.f($row,'titulo'));
        else            ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'" SELECTED>'.f($row,'titulo').' ('.FormataDataEdicao(f($row,'inicio')).' - '.FormataDataEdicao(f($row,'fim')).')');
      } else {
        if($formato==1) ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'">'.f($row,'titulo'));
        else            ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'">'.f($row,'titulo').' ('.FormataDataEdicao(f($row,'inicio')).' - '.FormataDataEdicao(f($row,'fim')).')');
      }
    }
    ShowHTML('          </select>');
  }
}
?>
