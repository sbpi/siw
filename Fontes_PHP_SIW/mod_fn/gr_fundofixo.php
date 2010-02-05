<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getTipofundofixo.php');
include_once($w_dir_volta.'funcoes/selecaoTipofundofixo.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
/* Adicionado César Martin em 04/06/2008
Funções que geram gráficos em flash: */
include_once($w_dir_volta.'funcoes/FusionCharts.php'); 
include_once($w_dir_volta.'funcoes/FC_Colors.php');
/**/
// =========================================================================
//  /gr_fundofixo.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Consultas gerenciais do módulo de contratos
// Mail     : celso@sbpi.com.br
// Criacao  : 13/07/2006, 11:30
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Geração de gráfico
//                   = W   : Geração de documento no formato MS-Word (Office 2003)

// Verifica se o usuário está autenticado
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }
// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);
// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'gr_fundofixo.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
if ($O=='') $O='P';
switch ($O) {
  case 'V': $w_TP=$TP.' - Gráfico'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
}
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;

$p_tipo         = $_REQUEST['p_tipo'];
$w_troca        = $_REQUEST['w_troca'];
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_atividade    = upper($_REQUEST['p_atividade']);
$p_graf         = upper($_REQUEST['p_graf']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_ordena       = $_REQUEST['p_ordena'];
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_chave        = upper($_REQUEST['p_chave']);
$p_objeto       = upper($_REQUEST['p_objeto']);
$p_pais         = upper($_REQUEST['p_pais']);
$p_regiao       = upper($_REQUEST['p_regiao']);
$p_uf           = upper($_REQUEST['p_uf']);
$p_cidade       = upper($_REQUEST['p_cidade']);
$p_usu_resp     = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = upper($_REQUEST['p_uorg_resp']);
$p_palavra      = upper($_REQUEST['p_palavra']);
$p_prazo        = upper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = upper($_REQUEST['p_sqcc']);
$p_sq_orprior   = $_REQUEST['p_sq_orprior'];
$p_empenho      = upper($_REQUEST['p_empenho']);
$p_processo     = upper($_REQUEST['p_processo']);
$p_agrega       = upper($_REQUEST['p_agrega']);
$p_tamanho      = upper($_REQUEST['p_tamanho']);
// Recupera a configuração do serviço
$RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);

// Recupera a configuração do serviço de origem
$RS_Menu_Origem = db_getMenuData::getInstanceOf($dbms,$P2);

// Carrega o segmento do cliente
$RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente); 
$w_segmento = f($RS,'segmento');

Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Pesquisa gerencial
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo == 'PDF') {
    $w_filtro='';
    if ($p_projeto>'') {
      $w_linha++;
      $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    }
    if ($p_atividade>'') {
      $w_linha++;
      $RS = db_getSolicEtapa::getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    }     
    if ($p_sqcc>'') {
      $w_linha++;
      $RS = db_getCCData::getInstanceOf($dbms,$p_sqcc);
      $w_filtro .= '<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Contrato nº <td>[<b>'.$p_chave.'</b>]';}
    if ($p_prazo>'') { $w_linha++; $w_filtro .= ' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade responsável <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $w_linha++;
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_sq_orprior>''){
      $w_linha++;
      $RS = db_getTipoLancamento::getInstanceOf($dbms,$p_sq_orprior,null,$w_cliente,null);
      foreach($RS as $row) {$RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo do lançamento <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uorg_resp>'') {
      $w_linha++;
      $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'') {
      $w_linha++;
      $RS = db_getCountryData::getInstanceOf($dbms,$p_pais);
      $w_filtro .= '<tr valign="top"><td align="right">País <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'') {
      $w_linha++;
      $RS = db_getRegionData::getInstanceOf($dbms,$p_regiao);
      $w_filtro .= '<tr valign="top"><td align="right">Região <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $RS = db_getStateData::getInstanceOf($dbms,$p_pais,$p_uf);
      $w_filtro .= '<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_cidade>'') {
      $w_linha++;
      $RS = db_getCityData::getInstanceOf($dbms,$p_cidade);
      $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_prioridade>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Prioridade <td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]'; }
    if ($p_proponente>'') {
      $w_linha++;
      if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB')    $w_filtro .= '<tr valign="top"><td align="right">Bolsista <td>[<b>'.$p_proponente.'</b>]';
      else                                                  $w_filtro .= '<tr valign="top"><td align="right">Outra parte <td>[<b>'.$p_proponente.'</b>]';
    }
    if ($p_objeto>'')   { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Objeto <td>[<b>'.$p_objeto.'</b>]'; }
    if ($p_palavra>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código interno <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_ini_i>'')    { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Início vigência <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]'; }
    if ($p_fim_i>'')    { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Término vigência <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    if ($p_atraso>'')   { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código externo <td>[<b>'.$p_atraso.'</b>]'; }
    if ($p_empenho>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do empenho<td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_processo>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Número do processo<td>[<b>'.$p_processo.'</b>]'; }
    if ($w_filtro>'')   {$w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }
    $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,4,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente, 
        $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, 
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
        null, $p_sq_orprior, $p_empenho, $p_processo);
    switch ($p_agrega) {
      case substr(f($RS_Menu,'sigla'),0,3).'TIPO':
        $w_TP=$TP.' - Por tipo de lançamento';
        $RS1 = SortArray($RS1,'nm_tipo_lancamento','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'PROJ':
        $w_TP=$TP.' - Por projeto';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':
        $w_TP = $TP.' - Por etapa de projeto';
        $RS1 = SortArray($RS1,'cd_ordem','asc');
        break;        
      case substr(f($RS_Menu,'sigla'),0,3).'PROP':
        if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB')  $w_TP .= ' - Pelo bolsista';
        else                                                $w_TP .= ' - Pela outra parte';
        $RS1 = SortArray($RS1,'nm_pessoa_resumido_ind','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'RESP':
        $w_TP=$TP.' - Por responsável';
        $RS1 = SortArray($RS1,'nm_solic','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':
        $w_TP=$TP.' - Por executor';
        $RS1 = SortArray($RS1,'nm_exec','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'CC':
        $w_TP=$TP.' - Por classificação';
        $RS1 = SortArray($RS1,'sg_cc','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'SETOR':
        $w_TP=$TP.' - Por setor responsável';
        $RS1 = SortArray($RS1,'nm_unidade_resp','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':
        $w_TP=$TP.' - Por UF';
        $RS1 = SortArray($RS1,'co_uf','asc');
        break;
    } 
  } 
  $w_linha_filtro = $w_linha;
  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de '.f($RS_Menu,'nome'),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_chave','Número do contrato','','','1','18','','0123456789');
      if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB')    Validate('p_proponente','Bolsista','','','2','90','1','');
      else                                                  Validate('p_proponente','Outra parte','','','2','90','1','');
      Validate('p_palavra','Código interno','','','3','90','1','1');
      Validate('p_atraso','Código externo','','','1','90','1','1');
      if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB')    Validate('p_objeto','Plano de trabalho','','','2','90','1','1');
      else                                                  Validate('p_objeto','Objeto','','','2','90','1','1');
      Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
      Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["p_fase[]"].length; i++) {');
      ShowHTML('    if (theForm["p_fase[]"][i].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Você deve informar pelo menos uma fase!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
      Validate('p_fim_i','Conclusão inicial','DATA','','10','10','','0123456789/');
      Validate('p_fim_f','Conclusão final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
      ShowHTML('     theForm.p_fim_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_fim_i','Conclusão inicial','<=','p_fim_f','Conclusão final');
      ShowHTML('  if (theForm.p_agrega[theForm.p_agrega.selectedIndex].value==\''.substr(f($RS_Menu,'sigla'),0,3).'ETAPA\' && theForm.p_projeto.selectedIndex==0) {');
      ShowHTML('     alert (\'A agregação por etapa exige a seleção de um projeto!\');');
      ShowHTML('     theForm.p_projeto.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');      
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
    if ($w_troca>'') {
      // Se for recarga da página
      BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
    } elseif ($O=='P') {
      if ($P1==1) {
        // Se for cadastramento
        BodyOpenClean('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpenClean('onLoad=\'document.Form.p_agrega.focus()\';');
      } 
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 
    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
      ShowHTML('<HR>');
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
    } 
  } 
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ($O=='L' && $w_embed != 'WORD') {
      ShowHTML('<tr><td>');
      if (MontaFiltro('GET')>'' ) {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      } else {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    } 
    ImprimeCabecalho();
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L' && $w_embed != 'WORD') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (chave_aux, filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':     ShowHTML('      document.Form.p_atividade.value=filtro;');      break;
          case substr(f($RS_Menu,'sigla'),0,3).'TIPO':      ShowHTML('      document.Form.p_sq_orprior.value=filtro;');     break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROJ':      ShowHTML('      document.Form.p_projeto.value=filtro;');        break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROP':      ShowHTML('      document.Form.p_proponente.value=filtro;');     break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESP':      ShowHTML('      document.Form.p_solicitante.value=filtro;');    break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':   ShowHTML('      document.Form.p_usu_resp.value=filtro;');       break;
          case substr(f($RS_Menu,'sigla'),0,3).'CC':        ShowHTML('      document.Form.p_sqcc.value=filtro;');           break;
          case substr(f($RS_Menu,'sigla'),0,3).'SETOR':     ShowHTML('      document.Form.p_unidade.value=filtro;');        break;
          case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':     ShowHTML('      document.Form.p_uf.value=filtro;');             break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':     ShowHTML('    else document.Form.p_atividade.value=\''.$_REQUEST['p_atividade'].'\';');     break;
          case substr(f($RS_Menu,'sigla'),0,3).'TIPO':      ShowHTML('    else document.Form.p_sq_orprior.value=\''.$_REQUEST['p_sq_orprior'].'\';');   break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROJ':      ShowHTML('    else document.Form.p_projeto.value=\''.$_REQUEST['p_projeto'].'\';');         break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROP':      ShowHTML('    else document.Form.p_proponente.value=\''.$_REQUEST['p_proponente'].'\';');   break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESP':      ShowHTML('    else document.Form.p_solicitante.value=\''.$_REQUEST['p_solicitante'].'\';'); break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':   ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';');       break;
          case substr(f($RS_Menu,'sigla'),0,3).'CC':        ShowHTML('    else document.Form.p_sqcc.value=\''.$_REQUEST['p_sqcc'].'\';');               break;
          case substr(f($RS_Menu,'sigla'),0,3).'SETOR':     ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');         break;
          case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':     ShowHTML('    else { document.Form.p_uf.value=\''.$_REQUEST['p_uf'].'\';}');                break;
        } 
        $RS2 = db_getTramiteList::getInstanceOf($dbms,$P2,null,null,null);
        $RS2 = SortArray($RS2,'ordem','asc');
        $w_fase_exec='';
        $w_fase_conc='';
        foreach ($RS2 as $row2) {
          if (f($row2,'sigla')=='CI')
            $w_fase_cad=f($row2,'sq_siw_tramite');
          elseif (f($row2,'ativo')=='N' && Nvl(f($row2,'sigla'),'-')!='CA')
            $w_fase_conc=$w_fase_conc.','.f($row2,'sq_siw_tramite');
          elseif (f($row2,'ativo')=='S')
            $w_fase_exec=$w_fase_exec.','.f($row2,'sq_siw_tramite');
        } 
        ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
        ShowHTML('    if (exec >= 0) document.Form.p_fase.value=\''.substr($w_fase_exec,1,100).'\';');
        ShowHTML('    if (conc >= 0) document.Form.p_fase.value=\''.substr($w_fase_conc,1,100).'\';');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\'; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        $RS2 = db_getMenuData::getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Contrato',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        switch ($p_agrega) {
          case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':     if ($_REQUEST['p_atividade']=='')       ShowHTML('<input type="Hidden" name="p_atividade" value="">');  break;
          case substr(f($RS_Menu,'sigla'),0,3).'TIPO':      if ($_REQUEST['p_sq_orprior']=='')      ShowHTML('<input type="Hidden" name="p_sq_orprior" value="">'); break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROJ':      if ($_REQUEST['p_projeto']=='')         ShowHTML('<input type="Hidden" name="p_projeto" value="">');    break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROP':      if ($_REQUEST['p_proponente']=='')      ShowHTML('<input type="Hidden" name="p_proponente" value="">'); break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESP':      if ($_REQUEST['p_solicitante']=='')     ShowHTML('<input type="Hidden" name="p_solicitante" value="">');break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':   if ($_REQUEST['p_usu_resp']=='')        ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');   break;
          case substr(f($RS_Menu,'sigla'),0,3).'CC':        if ($_REQUEST['p_sqcc']=='')            ShowHTML('<input type="Hidden" name="p_sqcc" value="">');       break;
          case substr(f($RS_Menu,'sigla'),0,3).'SETOR':     if ($_REQUEST['p_unidade']=='')         ShowHTML('<input type="Hidden" name="p_unidade" value="">');    break;
          case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':     if ($_REQUEST['p_uf']=='')              ShowHTML('<input type="Hidden" name="p_uf" value="">');         break;
        } 
      } 
      $w_nm_quebra='';
      $w_qt_quebra=0.00;
      $t_solic=0.00;
      $t_cad=0.00;
      $t_tram=0.00;
      $t_conc=0.00;
      $t_atraso=0.00;
      $t_aviso=0.00;
      $t_valor=0.00;
      $t_acima=0.00;
      $t_custo=0.00;
      $t_totcusto=0.00;
      $t_totsolic=0.00;
      $t_totcad=0.00;
      $t_tottram=0.00;
      $t_totconc=0.00;
      $t_totatraso=0.00;
      $t_totaviso=0.00;
      $t_totvalor=0.00;
      $t_totacima=0.00;
      foreach ($RS1 as $row1) {
        switch ($p_agrega) {
          case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':
            if ($w_nm_quebra!=f($row1,'nm_etapa')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.montaOrdemEtapa(f($row1,'sq_projeto_etapa')).' - '.f($row1,'nm_etapa'));
              } 
              $w_nm_quebra=f($row1,'nm_etapa');
              $w_chave=f($row1,'sq_projeto_etapa');
              $w_chave_aux=-1;
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha += 1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'TIPO':
            if ($w_nm_quebra!=f($row1,'nm_tipo_lancamento')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha <= $w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_tipo_lancamento'));
              } 
              $w_nm_quebra=f($row1,'nm_tipo_lancamento');
              $w_chave=f($row1,'sq_tipo_lancamento');
              $w_chave_aux=-1;
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROJ':
            if ($w_nm_quebra!=f($row1,'nm_projeto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_projeto'));
              } 
              $w_nm_quebra=f($row1,'nm_projeto');
              $w_chave=f($row1,'sq_solic_pai');
              $w_chave_aux=-1;
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha += 1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROP':
            if ($w_nm_quebra!=f($row1,'nm_pessoa_resumido')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_pessoa_resumido'));
              } 
              $w_nm_quebra=f($row1,'nm_pessoa_resumido');
              $w_chave=f($row1,'nm_pessoa_resumido');
              $w_chave_aux=-1;
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha += 1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESP':
            if ($w_nm_quebra!=f($row1,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_solic'));
              } 
              $w_nm_quebra=f($row1,'nm_solic');
              $w_chave=f($row1,'solicitante');
              $w_chave_aux=-1;
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha += 1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':
            if ($w_nm_quebra!=f($row1,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_exec'));
              } 
              $w_nm_quebra=f($row1,'nm_exec');
              $w_chave=f($row1,'executor');
              $w_chave_aux=-1;
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha += 1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'CC':
            if ($w_nm_quebra!=f($row1,'sg_cc')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'sg_cc'));
              }
              $w_nm_quebra=f($row1,'sg_cc');
              $w_chave=f($row1,'sq_cc');
              $w_chave_aux=-1;
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha += 1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'SETOR':
            if ($w_nm_quebra!=f($row1,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              }
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_unidade_resp'));
              }
              $w_nm_quebra=f($row1,'nm_unidade_resp');
              $w_chave=f($row1,'sq_unidade_resp');
              $w_chave_aux=-1;
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha += 1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':
            if ($w_nm_quebra!=f($row1,'co_uf')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              }
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'co_uf'));
              }
              $w_nm_quebra=f($row1,'co_uf');
              $w_chave=f($row1,'co_uf');
              $w_chave_aux=f($row1,'sq_pais');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha += 1;
            } 
            break;
        } 
        if ($w_embed == 'WORD' && $w_linha>$w_linha_pag) {
          // Se for geração de MS-Word, quebra a página
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          if ($p_tipo=='PDF') ShowHTML('    <pd4ml:page.break>');
          else                ShowHTML('    <br style="page-break-after:always">');
          $w_linha=$w_linha_filtro;
          $w_pag=$w_pag+1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_etapa'));                   break;
            case substr(f($RS_Menu,'sigla'),0,3).'TIPO':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_tipo_acordo'));             break;
            case substr(f($RS_Menu,'sigla'),0,3).'PROJ':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_projeto'));                 break;
            case substr(f($RS_Menu,'sigla'),0,3).'PROP':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_pessoa_resumido'));    break;
            case substr(f($RS_Menu,'sigla'),0,3).'RESP':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_solic'));                   break;
            case substr(f($RS_Menu,'sigla'),0,3).'RESPATU': ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_exec'));                    break;
            case substr(f($RS_Menu,'sigla'),0,3).'CC':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'sg_cc'));                      break;
            case substr(f($RS_Menu,'sigla'),0,3).'SETOR':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'nm_unidade_resp'));            break;
            case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row1,'co_uf'));                      break;
          } 
          $w_linha += 1;
        }
        if (Nvl(f($row1,'conclusao'),'')=='') {
          if (f($row1,'fim') < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row1,'aviso_prox_conc') == 'S' && (f($row1,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          if (f($row1,'or_tramite')==1) {
            $t_cad=$t_cad+1;
            $t_totcad=$t_totcad+1;
          } else {
            $t_tram=$t_tram+1;
            $t_tottram=$t_tottram+1;
          }
        } else {
          $t_conc=$t_conc+1;
          $t_totconc=$t_totconc+1;
          if (f($row1,'valor')<Nvl(f($row1,'valor_atual'),0)) {
            $t_acima=$t_acima+1;
            $t_totacima=$t_totacima+1;
          }
        }
        $t_solic=$t_solic+1;
        $t_valor=$t_valor+Nvl(f($row1,'valor'),0);
        $t_custo=$t_custo+Nvl(f($row1,'valor_atual'),0);
        $t_totvalor=$t_totvalor+Nvl(f($row1,'valor'),0);
        $t_totcusto=$t_totcusto+Nvl(f($row1,'valor_atual'),0);
        $t_totsolic=$t_totsolic+1;
        $w_qt_quebra=$w_qt_quebra+1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
      ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
      ShowHTML('          <td><b>Totais</font></td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1,-1);
    } 
    ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_graf=='N') {
	// Coloca o gráfico somente se o usuário desejar
	  include_once($w_dir_volta.'funcoes/geragraficoflash.php');
      ShowHTML('<tr><td align="center" height=20>');
    // ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.$w_dir.'geragrafico.php?p_genero=M&p_objeto='.f($RS_Menu,'nome').'&p_graf='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      ShowHTML('<tr><td align="center" height=20>');
	  barra_flash(array(genero => "M", "nome" =>  f($RS_Menu,'nome'), "total" => $t_totsolic, "cadastramento" => $t_totcad, "execucao" => $t_tottram, "concluidos" => $t_totconc, "atrasados" => $t_totatraso, "aviso" => $t_totaviso, "acima" => $t_totacima), "barra");
      if (($t_totcad+$t_tottram)>0)
      //  ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.$w_dir.'geragrafico.php?p_genero=M&p_objeto='.f($RS_Menu,'nome').'&p_graf='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
		pizza_flash(array(genero => "M", "nome" =>  f($RS_Menu,'nome'), "total" => $t_totsolic, "cadastramento" => $t_totcad, "execucao" => $t_tottram, "concluidos" => $t_totconc, "atrasados" => $t_totatraso, "aviso" => $t_totaviso, "acima" => $t_totacima), "pizza");
		
    } 
  } elseif ($O=='P') {
    // Carrega o segmento do cliente
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      if ($p_agrega==(substr(f($RS_Menu,'sigla'),0,3).'CC'))
        ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'CC" selected>Classificação');
      else
        ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'CC'.'">Classificação');
    } 
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'RESPATU')                                                    ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'RESPATU" selected>Executor');         else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'RESPATU">Executor');
    if (Nvl($p_agrega,substr(f($RS_Menu,'sigla'),0,3).'PROP')==substr(f($RS_Menu,'sigla'),0,3).'PROP') {
      if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB')    ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROP" selected>Bolsista');
      else                                                  ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROP" selected>Outra parte');
    } else { 
      if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB')    ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROP">Bolsista');
      else                                                  ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROP">Outra parte');
    }
    if (substr(f($RS_Menu_Origem,'sigla'),0,3)!='GCA') { if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'PROJ')  ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROJ" selected>projeto');             else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROJ">Projeto'); }
    if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB') { if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'ETAPA') ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'ETAPA" selected>Modalidade de bolsa');else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'ETAPA">Modalidade de bolsa'); }
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'RESP')                                                       ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'RESP" selected>Responsável');         else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'RESP">Responsável');
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'TIPO')                                                       ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'TIPO" selected>Tipo de lançamento');  else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'TIPO">Tipo de lançamento');
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'SETOR')                                                      ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'SETOR" selected>Setor responsável');  else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'SETOR">Setor responsável');
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'LOCAL')                                                      ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'LOCAL" selected>UF');                 else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'LOCAL">UF');
    ShowHTML('          </select></td>');
    MontaRadioSN('<b>Inibe exibição do gráfico?</b>',$p_graf,'p_graf');
    MontaRadioNS('<b>Limita tamanho do objeto?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    if (substr(f($RS_Menu_Origem,'sigla'),0,3)!='GCA') {
        // Se a opção for ligada ao módulo de projetos, permite a seleção do projeto  e da etapa
      ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
      if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB') {
        $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCADBOLSA');
        $w_atributo = 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_atividade\'; document.Form.submit();"';
      } else {
        $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
      }
      SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto do contrato na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',$w_atributo);
      ShowHTML('      </tr>');
      if (substr(f($RS_Menu_Origem,'sigla'),0,3)=='GCB') {
        ShowHTML('      <tr>');
        SelecaoEtapa('<u>T</u>ema e modalidade:','T','Se necessário, indique a modalidade à qual este contrato deve ser vinculada.',$p_atividade,$p_projeto,null,'p_atividade',null,null);
        ShowHTML('      </tr>');
      }
      ShowHTML('          </table>');
    }
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoLancamento('<u>T</u>ipo de lancamento:','T','Selecione na lista o tipo de lançamento adequado.',$p_sq_orprior,null,$w_cliente,'p_sq_orprior',f($RS_Menu_Origem,'sigla'),null,2);
    ShowHTML('      <tr valign="top">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      SelecaoCC('C<u>l</u>assificação:','C','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC',null,2);
    } 
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Número do c<U>o</U>ntrato:<br><INPUT ACCESSKEY="O" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
    ShowHTML('          <td><b>O<U>u</U>tra parte:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Có<U>d</U>igo interno:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_palavra" size="18" maxlength="18" value="'.$p_palavra.'"></td>');
    ShowHTML('          <td><b>Có<U>d</U>igo externo:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_atraso" size="18" maxlength="18" value="'.$p_atraso.'"></td>');
    ShowHTML('      <tr valign="top">');
    if($w_segmento=='Público') {
      if (substr(f($RS_Menu_Origem,'sigla'),0,3)!='GCA') ShowHTML('          <td><b><U>N</U>úmero do empenho:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_empenho" size="18" maxlength="18" value="'.$p_empenho.'"></td>');
      ShowHTML('          <td><b><U>N</U>úmero do processo:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_processo" size="18" maxlength="18" value="'.$p_processo.'"></td>');
    }
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('<U>S</U>etor responsável:','S',null,$p_unidade,null,'p_unidade',null,null);
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor do contrato na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
    SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde o contrato se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    ShowHTML('      <tr>');
    SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
    SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
    ShowHTML('          <td><b>Dias para <U>t</U>érmino da vigência:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Iní<u>c</u>io vigência entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
    ShowHTML('          <td><b>Fi<u>m</u> vigência entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\';">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  if($p_tipo == 'PDF'){
    RodapePdf();  
  }
  Rodape();
}
// =========================================================================
// Rotina de impressao do cabecalho
// -------------------------------------------------------------------------
function ImprimeCabecalho() {
  extract($GLOBALS);
  ShowHTML('<tr><td align="center">');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
  switch ($p_agrega) {
    case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':       ShowHTML('          <td><b>Etapa</font></td>');              break;
    case substr(f($RS_Menu,'sigla'),0,3).'TIPO':        ShowHTML('          <td><b>Tipo de lançamento</font></td>'); break;
    case substr(f($RS_Menu,'sigla'),0,3).'PROJ':        ShowHTML('          <td><b>Projeto</font></td>');            break;
    case substr(f($RS_Menu,'sigla'),0,3).'PROP':        ShowHTML('          <td><b>Outra parte</font></td>');         break;
    case substr(f($RS_Menu,'sigla'),0,3).'RESP':        ShowHTML('          <td><b>Responsável</font></td>');        break;
    case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':     ShowHTML('          <td><b>Executor</font></td>');           break;
    case substr(f($RS_Menu,'sigla'),0,3).'CC':          ShowHTML('          <td><b>Classificação</font></td>');      break;
    case substr(f($RS_Menu,'sigla'),0,3).'SETOR':       ShowHTML('          <td><b>Setor responsável</font></td>');  break;
    case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':       ShowHTML('          <td><b>UF</font></td>');                 break;
  } 
  ShowHTML('          <td><b>Total</font></td>');
  ShowHTML('          <td><b>Cad.</font></td>');
  ShowHTML('          <td><b>Exec.</font></td>');
  ShowHTML('          <td><b>Conc.</font></td>');
  ShowHTML('          <td><b>Aviso</font></td>');

  if ($_SESSION['INTERNO']=='S' && substr(f($RS_Menu_Origem,'sigla'),0,3)!='GCA') {
    ShowHTML('          <td><b>$ Previsto</font></td>');
    ShowHTML('          <td><b>$ '.((substr(f($RS_Menu,'sigla'),0,3)=='FNR') ? 'Recebido' : 'Pago').'</font></td>');
  } 
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave,$l_chave_aux) {
  extract($GLOBALS);
  if($p_tipo == 'PDF' || $p_tipo == 'WORD'){
    $w_embed = 'WORD';  
  }
  if ($w_embed != 'WORD')               ShowHTML('          <td align="right"><a class="hl" href="javascript:lista('.$l_chave_aux.', \''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe os contratos.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</font></td>');     else ShowHTML('          <td align="right">'.number_format($l_solic,0,',','.').'&nbsp;</font></td>');
  if ($l_cad>0 && $w_embed != 'WORD')   ShowHTML('          <td align="right"><a class="hl" href="javascript:lista('.$l_chave_aux.', \''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe os contratos.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_cad,0,',','.').'</a>&nbsp;</font></td>');        else ShowHTML('          <td align="right">'.number_format($l_cad,0,',','.').'&nbsp;</font></td>');
  if ($l_tram>0 && $w_embed != 'WORD')  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista('.$l_chave_aux.', \''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe os contratos.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_tram,0,',','.').'</a>&nbsp;</font></td>');       else ShowHTML('          <td align="right">'.number_format($l_tram,0,',','.').'&nbsp;</font></td>');
  if ($l_conc>0 && $w_embed != 'WORD')  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista('.$l_chave_aux.', \''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe os contratos.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_conc,0,',','.').'</a>&nbsp;</font></td>');       else ShowHTML('          <td align="right">'.number_format($l_conc,0,',','.').'&nbsp;</font></td>');
  if ($l_aviso>0 && $w_embed != 'WORD') ShowHTML('          <td align="right"><font size="1" color="red"><b>'.number_format($l_aviso,0,',','.').'&nbsp;</font></td>'); else ShowHTML('          <td align="right"><b>'.$l_aviso.'&nbsp;</font></td>');
  if ($_SESSION['INTERNO']=='S' && substr(f($RS_Menu_Origem,'sigla'),0,3)!='GCA') {
    ShowHTML('          <td align="right">'.number_format($l_valor,2,',','.').'&nbsp;</font></td>');
    ShowHTML('          <td align="right">'.number_format($l_custo,2,',','.').'&nbsp;</font></td>');
  } 
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'GERENCIAL': Gerencial(); break;
    default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');    
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  }
}
?>