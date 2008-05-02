<?
// =========================================================================
// Montagem da seleção de base geográfica
// -------------------------------------------------------------------------
function selecaoBaseGeografica($label,$accesskey,$hint,$chave,$usuario,$indicador,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  if (nvl($restricao,'nulo')=='nulo') {
    ShowHTML('          <option value="">---');
    if (Nvl($chave,'')==1) ShowHTML('          <option value="1" SELECTED>Nacional');        else ShowHTML('          <option value="1">Nacional');
    if (Nvl($chave,'')==2) ShowHTML('          <option value="2" SELECTED>Regional');        else ShowHTML('          <option value="2">Regional');
    if (Nvl($chave,'')==3) ShowHTML('          <option value="3" SELECTED>Estadual');        else ShowHTML('          <option value="3">Estadual');
    if (Nvl($chave,'')==4) ShowHTML('          <option value="4" SELECTED>Municipal');       else ShowHTML('          <option value="4">Municipal');
    if (Nvl($chave,'')==5) ShowHTML('          <option value="5" SELECTED>Organizacional');  else ShowHTML('          <option value="5">Organizacional');
  } else {
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$usuario,$indicador,null,null,null,null,'S',null,null,null,null,null,null,null,null,null,$restricao);
    $RS = SortArray($RS,'chave','asc');
    ShowHTML('          <option value="">---');
    foreach($RS as $row) {
      if (nvl(f($row,'chave'),0)==nvl($chave,0))
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
      else
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  }
  ShowHTML('          </select>');
}
?>