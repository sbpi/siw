<?php
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'funcoes/selecaoAssuntoRadio.php');
include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDespacho.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDemanda.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/FusionCharts.php'); 
include_once($w_dir_volta.'funcoes/FC_Colors.php');

// =========================================================================
//  gr_protocolo.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Consultas do módulo de protocolo
// Mail     : alex@sbpi.com.br
// Criacao  : 09/09/2008, 15:00
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
$w_pagina       = 'gr_protocolo.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pa/';
$w_troca        = upper($_REQUEST['w_troca']);

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }


// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O='P';

switch ($O) {
  case 'V': $w_TP = $TP.' - Gráfico'; break;
  case 'P': $w_TP = $TP.' - Filtragem'; break;
  default : $w_TP = $TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;

$p_tipo          = upper($_REQUEST['p_tipo']);
$p_chave_pai     = upper($_REQUEST['p_chave_pai']);
$p_atividade     = upper($_REQUEST['p_atividade']);
$p_graf          = upper($_REQUEST['p_graf']);
$p_ativo         = upper($_REQUEST['p_ativo']);
$p_solicitante   = upper($_REQUEST['p_solicitante']);
$p_prioridade    = upper($_REQUEST['p_prioridade']);
$p_unidade       = upper($_REQUEST['p_unidade']);
$p_proponente    = upper($_REQUEST['p_proponente']);
$p_ordena        = lower($_REQUEST['p_ordena']);
$p_ini_i         = upper($_REQUEST['p_ini_i']);
$p_ini_f         = upper($_REQUEST['p_ini_f']);
$p_fim_i         = upper($_REQUEST['p_fim_i']);
$p_fim_f         = upper($_REQUEST['p_fim_f']);
$p_atraso        = upper($_REQUEST['p_atraso']);
$p_chave         = upper($_REQUEST['p_chave']);
$p_assunto       = upper($_REQUEST['p_assunto']);
$p_pais          = upper($_REQUEST['p_pais']);
$p_regiao        = upper($_REQUEST['p_regiao']);
$p_uf            = upper($_REQUEST['p_uf']);
$p_cidade        = upper($_REQUEST['p_cidade']);
$p_usu_resp      = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp     = upper($_REQUEST['p_uorg_resp']);
$p_processo      = upper($_REQUEST['p_processo']);
$p_prazo         = upper($_REQUEST['p_prazo']);
$p_fase          = explodeArray($_REQUEST['p_fase']);
$p_sqcc          = upper($_REQUEST['p_sqcc']);
$p_sq_acao_ppa   = upper($_REQUEST['p_sq_acao_ppa']);
$p_classif       = upper($_REQUEST['p_classif']);
$p_agrega        = upper($_REQUEST['p_agrega']);
$p_tamanho       = upper($_REQUEST['p_tamanho']);
$p_sq_menu_relac = upper($_REQUEST['p_sq_menu_relac']);
$p_chave_pai     = upper($_REQUEST['p_chave_pai']);
$p_empenho       = lower($_REQUEST['p_empenho']);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

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
  
  // Verifica se o cliente tem o módulo de acordos contratado
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'AC');
  if (count($RS)>0) $w_acordo='S'; else $w_acordo='N'; 

  // Verifica se o cliente tem o módulo viagens contratado
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PD');
  if (count($RS)>0) $w_viagem='S'; else $w_viagem='N'; 

  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'IS');
  if (count($RS)>0) $w_acao='S'; else $w_acao='N'; 

  // Verifica se o cliente tem o módulo de planejamento estratégico
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PE');
  if (count($RS)>0) $w_pe='S'; else $w_pe='N'; 
  
  if ($O=='L' || $O=='V' || $p_tipo=='WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_uf>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Busca por <td>[<b>'.(($p_uf=='S') ? 'Processos' : 'Documentos').'</b>]';
    } 
    if (nvl($p_chave_pai,'')>'') {
      $w_linha++;
      if ($p_tipo!='WORD' && $p_tipo!='PDF') {
        $w_filtro.='<tr valign="top"><td align="right">Vinculação<td>['.exibeSolic($w_dir,$p_chave_pai,null,'S','S').']</td></tr>';
      } else {
        $w_filtro.='<tr valign="top"><td align="right">Vinculação<td>['.exibeSolic($w_dir,$p_chave_pai,null,'S').']</td></tr>';
      }
    }    
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_chave_pai,$p_atividade,'REGISTRO',null);
      foreach ($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_sqcc>'') {
      $w_linha++;
      $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') { $w_linha++; $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Demanda nº <td>[<b>'.$p_chave.'</b>]'; }
    if ($p_prazo>'') { $w_linha++; $w_filtro = $w_filtro.' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getEspecieDocumento_PA; $RS = $sql->getInstanceOf($dbms,$p_solicitante,$w_cliente,null,null,null,null);
      foreach ($RS as $row) {$RS = $row; break;}
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Espécie documental <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_empenho>'') {
      $w_filtro.='<tr valign="top"><td align="right">Nº documento original <td>[<b>'.$p_empenho.'</b>]';
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Origem interna <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uorg_resp>''){
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Unidade de posse<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'' || $p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_pais>'') ? $p_pais : '*').'.'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',STR_PAD_LEFT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    if ($p_prioridade>''){
      $w_linha++;
      $sql = new db_getTipoDespacho_PA; $RS = $sql->getInstanceOf($dbms,$p_prioridade,$w_cliente,null,null,null,null);
      foreach ($RS as $row) {$RS = $row; break;}
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Último despacho<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Origem externa <td>[<b>'.$p_proponente.'</b>]'; }
    if ($p_sq_acao_ppa>''){ $w_linha++; $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Código do assunto <td>[<b>'.$p_sq_acao_ppa.'</b>]'; }
    if ($p_assunto>'')    { $w_linha++; $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Detalhamento do assunto <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_processo>'')   { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Interessado <td>[<b>'.$p_processo.'</b>]'; }
    if ($p_ini_i>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data criação/recebimento entre <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]'; }
    if ($p_fim_i>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite da tramitação entre <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    if ($p_atraso=='S')   { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasados</b>]'; }
    if ($w_filtro>'')     { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    if (nvl($p_classif, '') != '') {
      $sql = new db_getAssunto_PA;
      $RS_Assunto = $sql->getInstanceOf($dbms, $w_cliente, $p_classif, null, null, null, null, null, null, null, null, 'REGISTROS');
      foreach ($RS_Assunto as $row) {
        $RS_Assunto = $row;
        break;
      }
      $p_sq_acao_ppa = f($row, 'codigo').'#';
    }
   
    $sql = new db_getSolicList;
    $RS1 = $sql->getInstanceOf($dbms, $P2, $w_usuario, $p_agrega, 5,
                    $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante,
                    $p_unidade, $p_prioridade, $p_ativo, $p_proponente,
                    $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                    $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_chave_pai, $p_atividade,
                    $p_sq_acao_ppa, null, $p_empenho, $p_processo);

    switch ($p_agrega) {
      case 'GRPAETAPA':
        $w_TP = $TP.' - Por etapa de projeto';
        $RS1 = SortArray($RS1,'cd_ordem','asc');
        break;
      case 'GRPAPROJ':
        $w_TP = $TP.' - Por projeto';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
        break;
      case 'GRPAPROP':
        $w_TP = $TP.' - Por procedência externa';
        $RS1  = SortArray($RS1,'proponente','asc');
        break;
      case 'GRPARESP':
        $w_TP = $TP.' - Por responsável';
        $RS1  = SortArray($RS1,'nm_solic_ind','asc');
        break;
      case 'GRPARESPATU':
        $w_TP = $TP.' - Por executor';
        $RS1  = SortArray($RS1,'nm_exec_ind','asc');
        break;
      case 'GRPACC':
        $w_TP = $TP.' - Por classificação';
        $RS1  = SortArray($RS1,'cd_assunto','asc');
        break;
      case 'GRPASETOR':
        $w_TP = $TP.' - Por Unidade de posse';
        $RS1  = SortArray($RS1,'nm_unidade_posse','asc');
        break;
      case 'GRPAPRIO':
        $w_TP = $TP.' - Por último despacho';
        $RS1  = SortArray($RS1,'nm_tipo_despacho','asc');
        break;
      case 'GRPALOCAL':
        $w_TP = $TP.' - Por UF';
        $RS1  = SortArray($RS1,'co_uf','asc');
        break;
      case 'GRPATIPDEM':
        $w_TP = $TP.' - Por tipo de demanda';
        $RS1  = SortArray($RS1,'nm_demanda_tipo','asc');
        break;
    } 
  } 
  $w_linha_filtro = $w_linha;
  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),$w_pag);
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
      if(nvl($p_sq_menu_relac,'')>'' && $SG!='PROJETO') {
        if ($p_sq_menu_relac=='CLASSIF') {
          ShowHTML('  if (theForm.p_sqcc.selectedIndex==0) {');
          ShowHTML('    alert(\'Você deve indicar a classificação!\');');
          ShowHTML('    theForm.p_sqcc.focus();');
          ShowHTML('    return false;');
          ShowHTML('  }');
        } else {
          ShowHTML('  if (theForm.p_chave_pai.selectedIndex==0) {');
          ShowHTML('    alert(\'Você deve indicar a vinculação!\');');
          ShowHTML('    theForm.p_chave_pai.focus();');
          ShowHTML('    return false;');
          ShowHTML('  }');
        }
      }      
      //Validate('p_chave','Chave','','','1','18','','0123456789');
      //Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
      Validate('p_pais','Prefixo','','','1','5','','0123456789');
      Validate('p_regiao','Sequencial','','','1','6','','0123456789');
      Validate('p_cidade','Ano','','1','1','4','','0123456789');
      Validate('p_proponente','Origem externa','','','2','90','1','');
      //Validate('p_sq_acao_ppa','Código do assunto','','','1','10','1','1');
      Validate('p_assunto','Detalhamento do assunto/Despacho','','','2','90','1','1');
      Validate('p_processo','Interessado','','','2','90','1','1');
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
      if ($SG=='PROJETO') {
        ShowHTML('  if (theForm.p_agrega[theForm.p_agrega.selectedIndex].value==\'GRPAETAPA\' && theForm.p_chave_pai.selectedIndex==0) {');
        ShowHTML('     alert (\'A agregação por etapa exige a seleção de um projeto!\');');
        ShowHTML('     theForm.p_chave_pai.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
      }
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } elseif ($O=='P') {
      if ($P1==1) {
        // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      } 
    } else {
      BodyOpenClean('onLoad=\'this.focus();\'');
    } 
    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
      ShowHTML('<HR>');
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ($O=='L' && $w_embed != 'WORD') {
      ShowHTML('<tr><td>');
      if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      } else {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    } 
    ImprimeCabecalho();
    flush();
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L' && $w_embed != 'WORD') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRPAETAPA':     ShowHTML('      document.Form.p_atividade.value=filtro;');      break;
          case 'GRPAPROJ':      ShowHTML('      document.Form.p_chave_pai.value=filtro;');      break;
          case 'GRPAPROP':      ShowHTML('      document.Form.p_proponente.value=filtro;');     break;
          case 'GRPARESP':      ShowHTML('      document.Form.p_solicitante.value=filtro;');    break;
          case 'GRPARESPATU':   ShowHTML('      document.Form.p_usu_resp.value=filtro;');       break;
          case 'GRPACC':        ShowHTML('      document.Form.p_sq_acao_ppa.value=filtro;');    break;
          case 'GRPASETOR':     ShowHTML('      document.Form.p_uorg_resp.value=filtro;');      break;
          case 'GRPAPRIO':      ShowHTML('      document.Form.p_prioridade.value=filtro;');     break;
          case 'GRPALOCAL':     ShowHTML('      document.Form.p_uf.value=filtro;');             break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRPAETAPA':     ShowHTML('    else document.Form.p_atividade.value=\''.$_REQUEST['p_atividade'].'\';');       break;
          case 'GRPAPROJ':      ShowHTML('    else document.Form.p_chave_pai.value=\''.$_REQUEST['p_chave_pai'].'\';');       break;
          case 'GRPAPROP':      ShowHTML('    else document.Form.p_proponente.value=\''.$_REQUEST['p_proponente'].'\';');     break;
          case 'GRPARESP':      ShowHTML('    else document.Form.p_solicitante.value=\''.$_REQUEST['p_solicitante'].'\';');   break;
          case 'GRPARESPATU':   ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';');         break;
          case 'GRPACC':        ShowHTML('    else document.Form.p_sq_acao_ppa.value=\''.$_REQUEST['p_sq_acao_ppa'].'\';');   break;
          case 'GRPASETOR':     ShowHTML('    else document.Form.p_uorg_resp.value=\''.$_REQUEST['p_uorg_resp'].'\';');       break;
          case 'GRPAPRIO':      ShowHTML('    else document.Form.p_prioridade.value=\''.$_REQUEST['p_prioridade'].'\';');     break;
          case 'GRPALOCAL':     ShowHTML('    else document.Form.p_uf.value=\''.$_REQUEST['p_uf'].'\';');                     break;
        } 
        $sql = new db_getTramiteList; $RS2 = $sql->getInstanceOf($dbms,$P2,null,null,null);
        $RS2  = SortArray($RS2,'ordem','asc');
        $w_fase_exec = '';
        foreach($RS2 as $row) {
          if (f($row,'sigla')=='CI') {
            $w_fase_cad = f($row,'sq_siw_tramite');
          } elseif (f($row,'sigla')=='AT') {
            $w_fase_conc = f($row,'sq_siw_tramite');
          } elseif (f($row,'ativo')=='S') {
            $w_fase_exec = $w_fase_exec.','.f($row,'sq_siw_tramite');
          } 
        } 
        ShowHTML('    if (cad<0 && exec<0) document.Form.p_tipo.value="";');
        ShowHTML('    if (cad >= 0) document.Form.p_tipo.value="P";');
        ShowHTML('    if (exec >= 0) document.Form.p_tipo.value="D";');
        ShowHTML('    if (conc >= 0) document.Form.p_fase.value='.$w_fase_conc.';');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\'; ');
        ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value=\'S\'; else document.Form.p_atraso.value=\''.$_REQUEST['p_atraso'].'\'; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        $sql = new db_getMenuData; $RS2 = $sql->getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Protocolo',5,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        if (strpos(montaFiltro('GET'),'p_sq_acao_ppa')===false && nvl($p_sq_acao_ppa,'')!='') ShowHTML('<input type="Hidden" name="p_sq_acao_ppa" value="'.$p_sq_acao_ppa.'">');
        ShowHTML('<input type="Hidden" name="p_tipo" value="">');
        switch ($p_agrega) {
          case 'GRPAETAPA':     if ($_REQUEST['p_atividade']=='')   ShowHTML('<input type="Hidden" name="p_atividade" value="">');    break;
          case 'GRPAPROJ':      if ($_REQUEST['p_chave_pai']=='')   ShowHTML('<input type="Hidden" name="p_chave_pai" value="">');    break;
          case 'GRPAPROP':      if ($_REQUEST['p_proponente']=='')  ShowHTML('<input type="Hidden" name="p_proponente" value="">');   break;
          case 'GRPARESP':      if ($_REQUEST['p_solicitante']=='') ShowHTML('<input type="Hidden" name="p_solicitante" value="">');  break;
          case 'GRPARESPATU':   if ($_REQUEST['p_usu_resp']=='')    ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');     break;
          case 'GRPACC':        if ($_REQUEST['p_sq_acao_ppa']=='') ShowHTML('<input type="Hidden" name="p_sq_acao_ppa" value="">');  break;
          case 'GRPASETOR':     if ($_REQUEST['p_uorg_resp']=='')   ShowHTML('<input type="Hidden" name="p_uorg_resp" value="">');    break;
          case 'GRPAPRIO':      if ($_REQUEST['p_prioridade']=='')  ShowHTML('<input type="Hidden" name="p_prioridade" value="">');   break;
          case 'GRPALOCAL':     if ($_REQUEST['p_uf']=='')          ShowHTML('<input type="Hidden" name="p_uf" value="">');           break;
          case 'GRPATIPDEM':    if ($_REQUEST['p_empenho']=='')     ShowHTML('<input type="Hidden" name="p_empenho" value="">');      break;
        } 
      } 
      $w_nm_quebra  = '';
      $w_qt_quebra  = 0;
      $t_solic      = 0;
      $t_cad        = 0;
      $t_tram       = 0;
      $t_conc       = 0;
      $t_atraso     = 0;
      $t_aviso      = 0;
      $t_valor      = 0;
      $t_acima      = 0;
      $t_custo      = 0;
      $t_totcusto   = 0;
      $t_totsolic   = 0;
      $t_totcad     = 0;
      $t_tottram    = 0;
      $t_totconc    = 0;
      $t_totatraso  = 0;
      $t_totaviso   = 0;
      $t_totvalor   = 0;
      $t_totacima   = 0;
      foreach($RS1 as $row) {
        switch ($p_agrega) {
          case 'GRPAETAPA':
            if ($w_nm_quebra!=f($row,'nm_etapa')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.MontaOrdemEtapa(f($row,'sq_projeto_etapa')).' - '.f($row,'nm_etapa'));
              } 
              $w_nm_quebra  = f($row,'nm_etapa');
              $w_chave      = f($row,'sq_projeto_etapa');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRPAPROJ':
            if ($w_nm_quebra!=f($row,'nm_projeto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));
              } 
              $w_nm_quebra  =  f($row,'nm_projeto');
              $w_chave      = f($row,'sq_solic_pai');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRPAPROP':
            if ($w_nm_quebra!=f($row,'nm_pessoa_origem')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_pessoa_origem'));
              } 
              $w_nm_quebra  = f($row,'nm_pessoa_origem');
              $w_chave      = f($row,'pessoa_origem');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRPARESP':
            if ($w_nm_quebra!=f($row,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));
              } 
              $w_nm_quebra  = f($row,'nm_solic');
              $w_chave      = f($row,'solicitante');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRPARESPATU': 
            if ($w_nm_quebra!=f($row,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));
              } 
              $w_nm_quebra  = f($row,'nm_exec');
              $w_chave      = f($row,'executor');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRPACC':
            if ($w_nm_quebra!=f($row,'cd_assunto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'cd_assunto').' - '.f($row,'ds_assunto'));
              } 
              $w_nm_quebra  = f($row,'cd_assunto');
              $w_chave      = f($row,'cd_assunto');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRPASETOR':
            if ($w_nm_quebra!=f($row,'nm_unidade_posse')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_posse'));
              } 
              $w_nm_quebra  = f($row,'nm_unidade_posse');
              $w_chave      = f($row,'unidade_int_posse');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            }
            flush();
            break;
          case 'GRPAPRIO':
            if ($w_nm_quebra!=f($row,'nm_tipo_despacho')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_tipo_despacho'));
              } 
              $w_nm_quebra  = f($row,'nm_tipo_despacho');
              $w_chave      = f($row,'sq_tipo_despacho');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRPALOCAL':
            if ($w_nm_quebra!=f($row,'co_uf')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_uf'));
              } 
              $w_nm_quebra  = f($row,'co_uf');
              $w_chave      = f($row,'co_uf');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
            } 
            break;
          case 'GRPATIPDEM':
            if ($w_nm_quebra!=f($row,'nm_demanda_tipo')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                //ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_demanda_tipo'));
              } 
              $w_nm_quebra  = f($row,'nm_demanda_tipo');
              $w_chave      = f($row,'sq_demanda_tipo');
              $w_qt_quebra  = 0;
              $t_solic      = 0;
              $t_cad        = 0;
              $t_tram       = 0;
              $t_conc       = 0;
              $t_atraso     = 0;
              $t_aviso      = 0;
              $t_valor      = 0;
              $t_acima      = 0;
              $t_custo      = 0;
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
          $w_pag=$w_pag + 1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case 'GRPAETAPA':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_etapa'));          break;
            case 'GRPAPROJ':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));        break;
            case 'GRPAPROP':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_pessoa_origem'));  break;
            case 'GRPARESP':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));          break;
            case 'GRPARESPATU':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));           break;
            case 'GRPACC':          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'cd_assunto'));        break;
            case 'GRPASETOR':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_posse'));  break;
            case 'GRPAPRIO':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_tipo_despacho'));  break;
            case 'GRPALOCAL':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_uf'));             break;
            case 'GRPATIPDEM':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_empenho'));        break;
          } 
          $w_linha = $w_linha + 1;
        } 
        if (nvl(f($row,'ativo'),'')=='S') {
          if (Nvl(f($row,'fim'),time()) < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
        }
        if (f($row,'processo')=='S') {
          $t_cad      = $t_cad + 1;
          $t_totcad   = $t_totcad + 1;
        } else {
          $t_tram     = $t_tram + 1;
          $t_tottram  = $t_tottram + 1;
        } 
        $t_conc       = $t_conc + 1;
        $t_totconc    = $t_totconc + 1;
        $t_solic        = $t_solic + 1;
        $t_valor        = $t_valor + Nvl(f($row,'valor'),0);
        $t_custo        = $t_custo + Nvl(f($row,'custo_real'),0);
        $t_totvalor     = $t_totvalor + Nvl(f($row,'valor'),0);
        $t_totcusto     = $t_totcusto + Nvl(f($row,'custo_real'),0);
        $t_totsolic     = $t_totsolic + 1;
        $w_qt_quebra    = $w_qt_quebra + 1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
      ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
      ShowHTML('          <td><b>Totais</td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1);
//      echo '=>  '.$t_totsolic.'  e '.$t_totcad.'  e '.$t_tottram.'  e '.$t_totconc.'  e '.$t_totatraso.'  e '.$t_totaviso.'  e '.$t_totvalor.'  e '.$t_totcusto.'  e '.$t_totacima;
    } 
    if ($w_embed != 'WORD') ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_graf=='N') {
      include_once($w_dir_volta.'funcoes/geragraficogoogle.php');
      if($p_tipo == 'PDF') $w_embed = 'WORD';
      $w_legenda = array('Atrasados','Documentos','Processos','Protocolos');
      ShowHTML('<tr><td align="center"><br>');
      ShowHTML(geraGraficoGoogle(f($RS_Menu,'nome').' - Resumo',$SG,'bar',
                                 array($t_totsolic,$t_totcad,$t_tottram,$t_totatraso),
                                 $w_legenda
                                )
              );
      ShowHTML('<tr><td align="center"><br>');
      ShowHTML(geraGraficoGoogle(f($RS_Menu,'nome').' tramitando',$SG,'pie',
                                 array(($t_tottram+$t_totcad-$t_totatraso),$t_totaviso,$t_totatraso),
                                 array('Normal','Aviso','Atrasados')
                                )
              );
    } 
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="97%" cellspacing=0>');
    ShowHTML('         <tr><td width="50%"><td></tr>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if ($p_agrega=='GRPACC')      ShowHTML('          <option value="GRPACC" selected>Classificação');              else ShowHTML('          <option value="GRPACC">Classificação');
    //if ($SG=='PROJETO') {
    //  if ($p_agrega=='GRPAETAPA')   ShowHTML('          <option value="GRPAETAPA" selected>Etapa de projeto');        else ShowHTML('          <option value="GRPAETAPA">Etapa de projeto');
    //} 
    if ($p_agrega=='GRPAPRIO')      ShowHTML('          <option value="GRPAPRIO" selected>Último despacho');                 else ShowHTML('          <option value="GRPAPRIO">Último despacho');
    //if ($p_agrega=='GRPARESPATU')   ShowHTML('          <option value="GRPARESPATU" selected>Executor');              else ShowHTML('          <option value="GRPARESPATU">Executor');
    if ($p_agrega=='GRPAPROP')      ShowHTML('          <option value="GRPAPROP" selected>Procedência externa');              else ShowHTML('          <option value="GRPAPROP">Procedência externa');
    //if ($SG=='PROJETO') {
    //  if ($p_agrega=='GRPAPROJ')    ShowHTML('          <option value="GRPAPROJ" selected>Projeto');                  else ShowHTML('          <option value="GRPAPROJ">Projeto');
    //} 
    //if ($p_agrega=='GRPARESP')      ShowHTML('          <option value="GRPARESP" selected>Responsável');   else ShowHTML('          <option value="GRPARESP">Responsável');
    if (Nvl($p_agrega,'GRPASETOR')=='GRPASETOR')     ShowHTML('          <option value="GRPASETOR" selected>Unidade de posse');       else ShowHTML('          <option value="GRPASETOR">Unidade de posse');
    //if (substr(f($RS_Menu,'sigla'),0,3)=='GDT') {
    //  if ($p_agrega=='GRPATIPDEM')  ShowHTML('          <option value="GRPATIPDEM" selected>Tipo da demanda');        else ShowHTML('          <option value="GRPATIPDEM">Tipo da demanda');
    //}
    //if ($p_agrega=='GRPALOCAL')     ShowHTML('          <option value="GRPALOCAL" selected>UF');                      else ShowHTML('          <option value="GRPALOCAL">UF');
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibição do gráfico?</b>',$p_graf,'p_graf');
    MontaRadioSN('<b>Limita tamanho do assunto?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    // Se a opção for ligada ao módulo de projetos, permite a seleção do projeto  e da etapa
    if ($SG=='PROJETO') {
      ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
      $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
      SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na relação.',$p_chave_pai,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_chave_pai','PJLIST','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_atividade\'; document.Form.submit();"');
      ShowHTML('      </tr>');
      ShowHTML('      <tr>');
      SelecaoEtapa('Eta<u>p</u>a:','P','Se necessário, indique a etapa à qual esta atividade deve ser vinculada.',$p_atividade,$p_chave_pai,null,'p_atividade',null,null);
      ShowHTML('      </tr>');
      ShowHTML('          </table>');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
        SelecaoCC('C<u>l</u>assificação:','C','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('          </table>');
      } 
    } else {
      //ShowHTML('          <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
      //ShowHTML('          <tr valign="top">');
      //selecaoServico('<U>R</U>estringir a:', 'S', null, $p_sq_menu_relac, $P2, null, 'p_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
      //if(Nvl($p_sq_menu_relac,'')!='') {
      //  ShowHTML('          <tr valign="top">');
      //  if ($p_sq_menu_relac=='CLASSIF') {
      //    SelecaoSolic('Classificação',null,null,$w_cliente,$p_sqcc,$p_sq_menu_relac,null,'p_sqcc','SIWSOLIC',null);
      //  } else {
      //    SelecaoSolic('Vinculação',null,null,$w_cliente,$p_chave_pai,$p_sq_menu_relac,f($RS_Menu,'sq_menu'),'p_chave_pai',null,null);
      //  }
      //}
      //ShowHTML('          </td></tr></table></td></tr>');    
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_pais" size="6" maxlength="5" value="'.$p_pais.'">.<INPUT class="STI" type="text" name="p_regiao" style="text-align:right;" size="7" maxlength="6" value="'.$p_regiao.'">/<INPUT class="STI" type="text" name="p_cidade" size="4" maxlength="4" value="'.$p_cidade.'"></td>');
    ShowHTML('          <td><b>Buscar por?</b><br>');
    if ($p_uf=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_uf" value="S" checked> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value="N"> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value=""> Ambos');
    } elseif ($p_uf=='N') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_uf" value="S"> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value="N" checked> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value=""> Ambos');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_uf" value="S"> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value="N"> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value="" checked> Ambos');
    }
    //ShowHTML('          <td><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
    
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade de posse:','U','Selecione a Unidade de posse do protocolo na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    selecaoTipoDespacho('Último des<u>p</u>acho:','P','Selecione o despacho desejado.',$w_cliente,$p_prioridade,null,'p_prioridade','SELECAO',null);
    
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="'.$p_empenho.'">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:','E','Selecione a espécie do documento.',$p_solicitante,null,'p_solicitante',null,null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
    ShowHTML('          <td><b>Limi<u>t</u>e para tramitação entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:','O',null,$p_unidade,null,'p_unidade',null,null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr valign="top">');
    //ShowHTML('          <td><b>Código do <U>a</U>ssunto:<br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="p_sq_acao_ppa" size="10" maxlength="10" value="'.$p_sq_acao_ppa.'"></td>');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="'.$p_assunto.'"></td>');
//    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" '.$w_Disabled.' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="'.$p_processo.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $p_classif, null, 'p_classif', 'FOLHA', null, '2');
    ShowHTML('        </tr></table>');
    //SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor da demanda na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
    //ShowHTML('      <tr>');
    //SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
    //SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    //ShowHTML('      <tr>');
    //SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Apenas protocolos com data limite excedida?</b><br>');
    if ($p_atraso=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
    } 
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir resumo" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\'; document.Form.target=\'\';">');
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
  ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
  switch ($p_agrega) {
    case 'GRPAETAPA':   ShowHTML('          <td><b>Etapa</td>');            break;
    case 'GRPAPROJ':    ShowHTML('          <td><b>Projeto</td>');          break;
    case 'GRPAPROP':    ShowHTML('          <td><b>Origem externa</td>');   break;
    case 'GRPARESP':    ShowHTML('          <td><b>Responsável</td>');      break;
    case 'GRPARESPATU': ShowHTML('          <td><b>Executor</td>');         break;
    case 'GRPACC':      ShowHTML('          <td><b>Classificação</td>');    break;
    case 'GRPASETOR':   ShowHTML('          <td><b>Unidade de posse</td>'); break;
    case 'GRPAPRIO':    ShowHTML('          <td><b>Último despacho</td>');  break;
    case 'GRPALOCAL':   ShowHTML('          <td><b>UF</td>');               break;
    case 'GRPATIPDEM':  ShowHTML('          <td><b>Tipo de demanda</td>');  break;
  } 
  ShowHTML('          <td><b>Protocolos</td>');
  ShowHTML('          <td><b>Processos</td>');
  ShowHTML('          <td><b>Documentos</td>');
  //ShowHTML('          <td><b>Conc.</td>');
  ShowHTML('          <td><b>Atraso</td>');
  //ShowHTML('          <td><b>Aviso</td>');
  //if ($_SESSION['INTERNO']=='S') {
  //  ShowHTML('          <td><b>$ Prev.</td>');
  //  ShowHTML('          <td><b>$ Real</td>');
  //  ShowHTML('          <td><b>Real > Previsto</td>');
  //} 
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave) {
  extract($GLOBALS);
  if($p_tipo == 'PDF' || $p_tipo == 'WORD'){
    $w_embed = 'WORD';  
  }

  if ($w_embed != 'WORD')                  ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe lista.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_solic,0).'</a>&nbsp;</td>');                      else ShowHTML('          <td align="center">'.formatNumber($l_solic,0).'&nbsp;</td>');
  if ($l_cad>0 && $w_embed != 'WORD')      ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe lista.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_cad,0).'</a>&nbsp;</td>');                         else ShowHTML('          <td align="center">'.formatNumber($l_cad,0).'&nbsp;</td>');
  if ($l_tram>0 && $w_embed != 'WORD')     ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe lista.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_tram,0).'</a>&nbsp;</td>');                        else ShowHTML('          <td align="center">'.formatNumber($l_tram,0).'&nbsp;</td>');
  //if ($l_conc>0 && $w_embed != 'WORD')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe lista.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_conc,0).'</a>&nbsp;</td>');                         else ShowHTML('          <td align="center">'.formatNumber($l_conc,0).'&nbsp;</td>');
  if ($l_atraso>0 && $w_embed != 'WORD')   ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe lista.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.formatNumber($l_atraso,0).'</a>&nbsp;</td>'); else ShowHTML('          <td align="center"><b>'.$l_atraso.'&nbsp;</td>');
  //if ($l_aviso>0 && $w_embed != 'WORD')    ShowHTML('          <td align="center"><font color="red"><b>'.formatNumber($l_aviso,0).'&nbsp;</td>');                                                                                                                                                                                     else ShowHTML('          <td align="center"><b>'.$l_aviso.'&nbsp;</td>');
  //if ($_SESSION['INTERNO']=='S') {
  //  ShowHTML('          <td align="right">'.formatNumber($l_valor,2).'&nbsp;</td>');
  //  ShowHTML('          <td align="right">'.formatNumber($l_custo,2).'&nbsp;</td>');
  //  if ($l_acima>0) ShowHTML('          <td align="right"><font color="red"><b>'.formatNumber($l_acima,0).'&nbsp;</td>'); else ShowHTML('          <td align="right"><b>'.$l_acima.'&nbsp;</td>');
  //} 
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


