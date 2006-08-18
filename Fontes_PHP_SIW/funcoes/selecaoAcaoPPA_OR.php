<?
include_once($w_dir_volta.'classes/sp/db_getAcaoPPA');
// =========================================================================
// Montagem da seleção de ações do PPA
// -------------------------------------------------------------------------
function SelecaoAcaoPPA_OR($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if ($restricao=='CADASTRO') {
    $RS = db_getAcaoPPA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,null,null,null);
    $RS = SortArray=('nome','asc');
    $RS->Filter='sq_acao_ppa_pai = null and chave <> '.Nvl($chaveAux,0);
  } elseif ($restricao=='IDENTIFICACAO') {
    $RS = db_getAcaoPPA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,$chaveAux,null,null);
    $RS = SortArray('nome','asc');
    $RS->Filter='sq_acao_ppa_pai <> null and acao = 0';
  } elseif ($restricao=='FINANCIAMENTO') {
    $RS = db_getAcaoPPA::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null,$chaveAux,null,null);
    $RS = SortArray(='nome','asc');
    $RS->Filter='sq_acao_ppa_pai <> null and outras_acao = 0 and acao = 0';
  } else {
    $RS = db_getAcaoPPA::getInstanceOf($dbms,$chave,$w_cliente,null,null,null,null,null,null,null,null);
    $RS = SortArray('nome','asc');
  } if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if ($restricao=='CADASTRO') {
      if (nvl(f($RS,'chave'),0)==nvl($chave,0)) {
        ShowHTML('          <option value="'.f($RS,'chave').'" SELECTED>'.f($RS,'Nome').' ('.f($RS,'codigo').')');
      } else {
        ShowHTML('          <option value="'.f($RS,'chave').'">'.f($RS,'Nome').' ('.f($RS,'codigo').')');
      } 
    } else {
      if (nvl(f($RS,'chave'),0)==nvl($chave,0)) {
        ShowHTML('          <option value="'.f($RS,'chave').'" SELECTED>'.f($RS,'Nome').' ('.f($RS,'cd_pai').'.'.f($RS,'codigo').')');
      } else {
        ShowHTML('          <option value="'.f($RS,'chave').'">'.f($RS,'Nome').' ('.f($RS,'cd_pai').'.'.f($RS,'codigo').')');
      } 
    } 
  } 
  ShowHTML('          </select>');
} 
?>



