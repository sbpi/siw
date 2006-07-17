<? 
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
// =========================================================================
// Montagem da seleção dos acordos
// -------------------------------------------------------------------------
function selecaoAcordo($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'GC'.substr($SG,2,1).'CAD');
  $l_menu = f($RS1,'sq_menu');
  if ($restricao=='EXECUCAO') {
    $RS1 = db_getTramiteList($RS1,$l_menu,null,null);
    foreach ($RS1 as $row) {
      if (Nvl(f($row,'sigla'),'-')=='EE' || Nvl(f($row,'sigla'),'-')=='ER') {
        if ($l_fase>'') 
          $l_fase = $l_fase.','.f($row,'sq_siw_tramite'); 
        else 
          $l_fase = f($row,'sq_siw_tramite');
      }
    }  
  } 
  $RS = db_getSolicList::getInstanceOf($dbms,$l_menu,$w_usuario,'GC'.substr($SG,2,1).'CAD',3,
          null,null,null,null,null,null,
          null,null,null,null,
          null,null,null,null,null,null,null,
          null,null,null,$l_fase,null,null,null,null,null);
  $RS = SortArray($RS,'nm_outra_parte_resumido','asc','fim','desc');
  if (!isset($hint))
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_siw_solicitacao'),0)==nvl($chave,0))
      ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'" SELECTED>'.f($row,'codigo_interno').' - '.f($row,'nm_outra_parte_resumido').' ('.substr(f($row,'objeto'),0,45).')');
    else
      ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'">'.f($row,'codigo_interno').' - '.f($row,'nm_outra_parte_resumido').' ('.substr(f($row,'objeto'),0,45).')');
  } 
  ShowHTML('          </select>');
} 
?>