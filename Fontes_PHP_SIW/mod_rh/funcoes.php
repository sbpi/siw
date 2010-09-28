<?php
include_once($w_dir_volta.'classes/sp/db_getBankHouseList.php');
// =========================================================================
// Montagem da seleção de idiomas
// -------------------------------------------------------------------------
function SelecaoIdioma($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getIdiomList::getInstanceOf($dbms,null,'S');
  $RS = SortArray($RS,'Nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_idioma'),0)== nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_idioma').'" SELECTED>'.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_idioma').'">'.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>');
} 

include_once($w_dir_volta.'classes/sp/db_getEtniaList.php');
// =========================================================================
// Montagem da seleção de etnia
// -------------------------------------------------------------------------
function SelecaoEtnia($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getEtniaList::getInstanceOf($dbms,null,'S');
  $RS = SortArray($RS,'codigo_siape','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_etnia'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_etnia').'" SELECTED>'.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_etnia').'">'.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>');  
}
 
include_once($w_dir_volta.'classes/sp/db_getDeficiencyList.php');
// =========================================================================
// Montagem da seleção de deficiência
// -------------------------------------------------------------------------
function SelecaoDeficiencia($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getDeficiencyList::getInstanceOf($dbms,null,'S');
  $RS = SortArray($RS,'sq_grupo_defic','asc','nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_deficiencia'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_deficiencia').'" SELECTED>'.f($row,'sq_grupo_deficiencia').' - '.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_deficiencia').'">'.f($row,'sq_grupo_defic').' - '.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>');
} 

include_once($w_dir_volta.'classes/sp/db_getCivStateList.php');
// =========================================================================
// Montagem da seleção de estado civil
// -------------------------------------------------------------------------

function SelecaoEstadoCivil($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getCivStateList::getInstanceOf($dbms,$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_estado_civil'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_estado_civil').'" SELECTED>'.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_estado_civil').'">'.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>');
}
 
include_once($w_dir_volta.'classes/sp/db_getFormationList.php');
// =========================================================================
// Montagem da seleção de formação acadêmica
// -------------------------------------------------------------------------
function SelecaoFormacao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getFormationList::getInstanceOf($dbms,$chaveAux,null,null);
  $RS = SortArray($RS,'ordem','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_formacao'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_formacao').'" SELECTED>'.f($row,'Nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_formacao').'">'.f($row,'Nome'));
    } 
  } 
  ShowHTML('          </select>'); 
} 

include_once($w_dir_volta.'classes/sp/db_getTipoPostoList.php');
// =========================================================================
// Montagem da seleção dos tipos de postos
// -------------------------------------------------------------------------
function SelecaoTipoPosto2($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getTipoPostoList::getInstanceOf($dbms,$w_cliente,null,null);
  $RS = SortArray($RS,'descricao','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_eo_tipo_posto'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_eo_tipo_posto').'" SELECTED>'.f($row,'descricao'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_eo_tipo_posto').'">'.f($row,'descricao'));
    } 
  } 
  ShowHTML('          </select>'); 
}
 
include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
// =========================================================================
// Montagem da seleção do tipo da data
// -------------------------------------------------------------------------
function SelecaoTipoData($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $w_tipos='';
  $RS = db_getDataEspecial($RS1,$w_cliente,null,null,null,null,null,'VERIFICATIPO');
  if (!(count($RS)<=0)) {
    foreach ($RS1 as $row) {
      $w_tipos = $w_tipos.f($row,'tipo');
    } 
  } if (Nvl($hint,'')>''){
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='I') {
    ShowHTML('          <option value="I" SELECTED>Invariável');
  } else {
    ShowHTML('          <option value="I">Invariável');
  } if (Nvl($chave,'')=='E') {
    ShowHTML('          <option value="E" SELECTED>Específica');
  } else {
    ShowHTML('          <option value="E">Específica');
  } if (strpos($w_tipos,'S')===false || Nvl($chave,'')=='S') {
    if (Nvl($chave,'')=='S') {
      ShowHTML('          <option value="S" SELECTED>Segunda Carnaval');
    } else {
      ShowHTML('          <option value="S">Segunda Carnaval');
    } 
  } if (strpos($w_tipos,'C')===false || Nvl($chave,'')=='C') {
    if (Nvl($chave,'')=='C') {
      ShowHTML('          <option value="C" SELECTED>Terça Carnaval');
    } else {
      ShowHTML('          <option value="C">Terça Carnaval');
    } 
  } if (strpos($w_tipos,'Q')===false || Nvl($chave,'')=='Q') {
    if (Nvl($chave,'')=='Q') {
      ShowHTML('          <option value="Q" SELECTED>Quarta Cinzas');
    } else {
      ShowHTML('          <option value="Q">Quarta Cinzas');
    } 
  } if (strpos($w_tipos,'P')===false || Nvl($chave,'')=='P') {
    if (Nvl($chave,'')=='P') {
      ShowHTML('          <option value="P" SELECTED>Sexta Santa');
    } else {
      ShowHTML('          <option value="P">Sexta Santa');
    } 
  } if (strpos($w_tipos,'D')===false || Nvl($chave,'')=='D') {
    if (Nvl($chave,'')=='D') {
      ShowHTML('          <option value="D" SELECTED>Domingo Páscoa');
    } else {
      ShowHTML('          <option value="D">Domingo Páscoa');
    } 
  } if (strpos($w_tipos,'H')===false || Nvl($chave,'')=='H') {
    if (Nvl($chave,'')=='H') {
      ShowHTML('          <option value="H" SELECTED>Corpus Christi');
    } else {
      ShowHTML('          <option value="H">Corpus Christi');
    } 
  } 
  ShowHTML('          </select>');
} 

// =========================================================================
// Montagem da seleção de abragência da data
// -------------------------------------------------------------------------

function SelecaoAbrangData($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='I') {
    ShowHTML('          <option value="I" SELECTED>Internacional');
  } else {
    ShowHTML('          <option value="I">Internacional');
  } if (Nvl($chave,'')=='N') {
    ShowHTML('          <option value="N" SELECTED>Nacional');
  } else {
    ShowHTML('          <option value="N">Nacional');
  } if (Nvl($chave,'')=='E') {
    ShowHTML('          <option value="E" SELECTED>Estadual');
  } else {
    ShowHTML('          <option value="E">Estadual');
  } if (Nvl($chave,'')=='M') {
    ShowHTML('          <option value="M" SELECTED>Municipal');
  } else {
    ShowHTML('          <option value="M">Municipal');
  } if (Nvl($chave,'')=='O') {
    ShowHTML('          <option value="O" SELECTED>Organização');
  } else {
   ShowHTML('          <option value="O">Organização');
  } 
  ShowHTML('          </select>'); 
}

include_once($w_dir_volta.'classes/sp/db_getGPTipoAfast.php'); 
// =========================================================================
// Montagem da seleção dos tipos de afastamentos
// -------------------------------------------------------------------------
function SelecaoTipoAfastamento($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if ($restricao=='AFASTAMENTO') {
    $RS = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,null,null,'S',null,null,$restricao);
    $RS = SortArray($RS,'nome','asc');
  } else {
    $RS = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null);
    $RS = SortArray($RS,'nome','asc');
  } if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>'); 
}

include_once($w_dir_volta.'classes/sp/db_getGPColaborador.php'); 
// =========================================================================
// Montagem da seleção dos colaboradores
// -------------------------------------------------------------------------
function SelecaoColaborador($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getGPColaborador::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  $RS = SortArray($RS,'nome_resumido','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'sq_contrato_colaborador'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'sq_contrato_colaborador').'" SELECTED>'.f($row,'nome_resumido'));
    } else {
      ShowHTML('          <option value="'.f($row,'sq_contrato_colaborador').'">'.f($row,'nome_resumido'));
    } 
  } 
  ShowHTML('          </select>');
  ShowHTML('              <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\'Afastamento.asp?par=BuscaColaborador&TP='.RemoveTP($TP).'&w_cliente='.$w_cliente.'&chaveAux='.$chaveAux.'&w_menu='.$w_menu.'&restricao='.$restricao.'&campo='.$campo.'\',\'Colaborador\',\'top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar o colaborador."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
}

// =========================================================================
// Montagem da URL com os dados de um colaborador
// -------------------------------------------------------------------------
function ExibeColaborador($p_dir,$p_cliente,$p_pessoa,$p_tp,$p_nome) {
  extract($GLOBALS);
  if (Nvl($p_nome,'')=='') {
    $l_string='---';
  } else {
    $l_string=$l_string.'<A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.$p_dir.'Afastamento.asp?par=TELACOLABORADOR&w_cliente='.$p_cliente.'&w_sq_pessoa='.$p_pessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_TP.'&SG='.$SG.'\',\'Colaborador\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados deste colaborador!">'.$p_nome.'</A>';
  } 
  $ExibeColaborador = $l_string;
  $l_string         = null; 
} 

include_once($w_dir_volta.'classes/sp/db_getGPModalidade.php');
// =========================================================================
// Montagem da seleção de modalidades de contrato
// -------------------------------------------------------------------------
function SelecaoModalidade($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getGPModalidade::getInstanceOf($dbms,$w_cliente,null,null,null,'S',null,$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>');
}

include_once($w_dir_volta.'classes/sp/db_getCargo.php'); 
// =========================================================================
// Montagem da seleção dos cargos
// -------------------------------------------------------------------------

function SelecaoCargo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getCargo::getInstanceOf($dbms,$w_cliente,null,null,null,null,'S',$restricao);
  $RS = SortArray($RS,'nome','asc');
  if (Nvl($hint,'')>'') {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$Label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    if (nvl(f($row,'chave'),0)==nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome'));
    } else {
      ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome'));
    } 
  } 
  ShowHTML('          </select>'); 
} 
?>