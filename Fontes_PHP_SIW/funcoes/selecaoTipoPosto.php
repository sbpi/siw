<?
include_once($w_dir_volta.'classes/sp/db_getTipoPostoList.php');
// =========================================================================
// Montagem da seleção dos tipos de postos
// -------------------------------------------------------------------------
function selecaoTipoPosto($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getTipoPostoList::getInstanceOf($dbms,$w_cliente,null,null);
  //if (nvl($restricao,'')>'') { $RS->Filter=$restricao; }
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br>');
  }
  foreach($RS as $row) {
     if (nvl(f($row,'sq_eo_tipo_posto'),0)==nvl($chave,0)) {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="'.f($row,'sq_eo_tipo_posto').'" checked>'.f($row,'descricao').'<br>');
     } else {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="'.f($row,'sq_eo_tipo_posto').'">'.f($row,'descricao').'<br>');
     }
  }
  ShowHTML('          </select>');
}
?>
