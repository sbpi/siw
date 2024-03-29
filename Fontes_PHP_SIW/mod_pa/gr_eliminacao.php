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
include_once($w_dir_volta.'classes/sp/db_getSolicPA.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
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
//  gr_eliminacao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Consultas do m�dulo de protocolo
// Mail     : alex@sbpi.com.br
// Criacao  : 09/09/2008, 15:00
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Gera��o de gr�fico
//                   = W   : Gera��o de documento no formato MS-Word (Office 2003)

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'gr_eliminacao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pa/';
$w_troca        = upper($_REQUEST['w_troca']);
$w_embed        = '';

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }


// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O='P';

switch ($O) {
  case 'V': $w_TP = $TP.' - Gr�fico'; break;
  case 'P': $w_TP = $TP.' - Filtragem'; break;
  default : $w_TP = $TP.' - Listagem'; 
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
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
$p_processo       = upper($_REQUEST['p_processo']);
$p_prazo         = upper($_REQUEST['p_prazo']);
$p_fase          = explodeArray($_REQUEST['p_fase']);
$p_sqcc          = upper($_REQUEST['p_sqcc']);
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

// Recupera a configura��o do servi�o
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Pesquisa gerencial
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);
  global $w_embed;

  $w_pag   = 1;
  $w_linha = 0;
  
  // Verifica se o cliente tem o m�dulo de acordos contratado
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'AC');
  if (count($RS)>0) $w_acordo='S'; else $w_acordo='N'; 

  // Verifica se o cliente tem o m�dulo viagens contratado
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PD');
  if (count($RS)>0) $w_viagem='S'; else $w_viagem='N'; 

  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'IS');
  if (count($RS)>0) $w_acao='S'; else $w_acao='N'; 

  // Verifica se o cliente tem o m�dulo de planejamento estrat�gico
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PE');
  if (count($RS)>0) $w_pe='S'; else $w_pe='N'; 
  
  if ($O=='L' || $O=='V' || $p_tipo=='WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_uf>'') {
      $w_linha++;
      $w_filtro.='<tr valign="top"><td align="right">Busca por <td>[<b>'.(($p_uf=='S') ? 'Processos' : 'Documentos').'</b>]';
    } 
    if ($p_prioridade>''){
      $w_linha++;
      $w_filtro.='<tr valign="top"><td align="right">N�mero do pedido<td>[<b>'.$p_prioridade.'</b>]';
    } 
    if (nvl($p_chave_pai,'')>'') {
      $w_linha++;
      if ($p_tipo!='WORD' && $p_tipo!='PDF') {
        $w_filtro.='<tr valign="top"><td align="right">Vincula��o<td>['.exibeSolic($w_dir,$p_chave_pai,null,'S','S').']</td></tr>';
      } else {
        $w_filtro.='<tr valign="top"><td align="right">Vincula��o<td>['.exibeSolic($w_dir,$p_chave_pai,null,'S').']</td></tr>';
      }
    }    
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_chave_pai,$p_atividade,'REGISTRO',null);
      foreach ($RS as $row) { $RS = $row; break; }
      $w_filtro.='<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_sqcc>'') {
      $w_linha++;
      $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
      $w_filtro.='<tr valign="top"><td align="right">Classifica��o <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Demanda n� <td>[<b>'.$p_chave.'</b>]'; }
    //if ($p_prazo>'') { $w_linha++; $w_filtro.=' <tr valign="top"><td align="right">Prazo de devolu��o at�<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]'; }
    if ($p_ini_i>'')      { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Data da solicita��o entre <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]'; }
    if ($p_fim_i>'')      { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Elimina��o entre <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    if ($p_usu_resp>'') {
      $w_linha++;
      $sql = new db_getEspecieDocumento_PA; $RS = $sql->getInstanceOf($dbms,$p_usu_resp,$w_cliente,null,null,null,null);
      foreach ($RS as $row) {$RS = $row; break;}
      $w_filtro.='<tr valign="top"><td align="right">Esp�cie documental <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_empenho>'') {
      $w_filtro.='<tr valign="top"><td align="right">N� documento original <td>[<b>'.$p_empenho.'</b>]';
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro.='<tr valign="top"><td align="right">Origem interna <td>[<b>'.f($RS,'nome').'</b>]';
    } 
      if ($p_solicitante>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro.='<tr valign="top"><td align="right">Solicitante <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
    if ($p_uorg_resp>''){
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro.='<tr valign="top"><td align="right">Unidade solicitante<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'' || $p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro.='<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_pais>'') ? $p_pais : '*').'.'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Origem externa <td>[<b>'.$p_proponente.'</b>]'; }
    if ($p_assunto>'')    { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_processo>'')    { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Interessado <td>[<b>'.$p_processo.'</b>]'; }
    //if ($p_atraso=='S')   { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasados</b>]'; }
    if ($w_filtro>'')     { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }
      
    $sql = new db_getSolicPA; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,$p_prioridade,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,$p_uorg_resp, $p_palavra, $p_prazo, 
        $p_fase, $p_sqcc, $p_chave_pai, $p_atividade, null, null, $p_empenho, $p_processo);

    switch ($p_agrega) {
      case 'GRELETAPA':
        $w_TP = $TP.' - Por etapa de projeto';
        $RS1 = SortArray($RS1,'cd_ordem','asc');
        break;
      case 'GRELPROJ':
        $w_TP = $TP.' - Por projeto';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
        break;
      case 'GRELPROP':
        $w_TP = $TP.' - Por proced�ncia externa';
        $RS1  = SortArray($RS1,'proponente','asc');
        break;
      case 'GRELRESP':
        $w_TP = $TP.' - Por respons�vel';
        $RS1  = SortArray($RS1,'nm_solic_ind','asc');
        break;
      case 'GRELRESPATU':
        $w_TP = $TP.' - Por executor';
        $RS1  = SortArray($RS1,'nm_exec_ind','asc');
        break;
      case 'GRELCC':
        $w_TP = $TP.' - Por classifica��o';
        $RS1  = SortArray($RS1,'sg_cc','asc');
        break;
      case 'GRELSETOR':
        $w_TP = $TP.' - Por Unidade Solicitante';
        $RS1  = SortArray($RS1,'nm_unidade_resp','asc');
        break;
      case 'GRELPRIO':
        $w_TP = $TP.' - Por �ltimo despacho';
        $RS1  = SortArray($RS1,'nm_tipo_despacho','asc');
        break;
      case 'GRELLOCAL':
        $w_TP = $TP.' - Por UF';
        $RS1  = SortArray($RS1,'co_uf','asc');
        break;
      case 'GRELTIPDEM':
        $w_TP = $TP.' - Por tipo de demanda';
        $RS1  = SortArray($RS1,'nm_demanda_tipo','asc');
        break;
    } 
  }
  $w_linha_filtro = $w_linha;
  $w_linha_pag    = 0;
  headerGeral('P', $p_tipo, $w_chave, 'Consulta de '.f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);
  
  if ($w_embed!='WORD') {
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
          ShowHTML('    alert(\'Voc� deve indicar a classifica��o!\');');
          ShowHTML('    theForm.p_sqcc.focus();');
          ShowHTML('    return false;');
          ShowHTML('  }');
        } else {
          ShowHTML('  if (theForm.p_chave_pai.selectedIndex==0) {');
          ShowHTML('    alert(\'Voc� deve indicar a vincula��o!\');');
          ShowHTML('    theForm.p_chave_pai.focus();');
          ShowHTML('    return false;');
          ShowHTML('  }');
        }
      }      
      //Validate('p_chave','Chave','','','1','18','','0123456789');
      //Validate('p_prazo','Dias para a devolu��o','','','1','2','','0123456789');
      Validate('p_prioridade','N�mero do pedido','','','3','30','1','1');
      Validate('p_pais','Prefixo','','','1','5','','0123456789');
      Validate('p_regiao','Sequencial','','','1','6','','0123456789');
      Validate('p_cidade','Ano','','','1','4','','0123456789');
      Validate('p_ini_i','Solicita��o inicial','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','Solicita��o final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de solicita��o ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["p_fase[]"].length; i++) {');
      ShowHTML('    if (theForm["p_fase[]"][i].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Voc� deve informar pelo menos uma fase!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');      
      CompData('p_ini_i','Solicita��o inicial','<=','p_ini_f','Solicita��o final');
      Validate('p_fim_i','Elimina��o inicial','DATA','','10','10','','0123456789/');
      Validate('p_fim_f','Elimina��o final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas da elimina��o ou nenhuma delas!\');');
      ShowHTML('     theForm.p_fim_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_fim_i','Elimina��o inicial','<=','p_fim_f','Elimina��o final');
      Validate('p_proponente','Origem externa','','','2','90','1','');
      Validate('p_assunto','Detalhamento','','','2','90','1','1');
      Validate('p_processo','Interessado','','','2','30','1','1');
      if ($SG=='PROJETO') {
        ShowHTML('  if (theForm.p_agrega[theForm.p_agrega.selectedIndex].value==\'GRELETAPA\' && theForm.p_chave_pai.selectedIndex==0) {');
        ShowHTML('     alert (\'A agrega��o por etapa exige a sele��o de um projeto!\');');
        ShowHTML('     theForm.p_chave_pai.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
      }
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["p_fase[]"].length; i++) {');
      ShowHTML('    if (theForm["p_fase[]"][i].checked) {');
      ShowHTML('       w_erro=false; ');
      ShowHTML('       break; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Voc� deve selecionar pelo menos uma das op��es do campo \"Recuperar fases\"!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_Troca>'') {
      // Se for recarga da p�gina
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
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L' && $w_embed != 'WORD') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRELETAPA':     ShowHTML('      document.Form.p_atividade.value=filtro;');      break;
          case 'GRELPROJ':      ShowHTML('      document.Form.p_chave_pai.value=filtro;');      break;
          case 'GRELPROP':      ShowHTML('      document.Form.p_proponente.value=filtro;');     break;
          case 'GRELRESP':      ShowHTML('      document.Form.p_solicitante.value=filtro;');    break;
          case 'GRELRESPATU':   ShowHTML('      document.Form.p_usu_resp.value=filtro;');       break;
          case 'GRELCC':        ShowHTML('      document.Form.p_sqcc.value=filtro;');           break;
          case 'GRELSETOR':     ShowHTML('      document.Form.p_uorg_resp.value=filtro;');      break;
          case 'GRELLOCAL':     ShowHTML('      document.Form.p_uf.value=filtro;');             break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRELETAPA':     ShowHTML('    else document.Form.p_atividade.value=\''.$_REQUEST['p_atividade'].'\';');       break;
          case 'GRELPROJ':      ShowHTML('    else document.Form.p_chave_pai.value=\''.$_REQUEST['p_chave_pai'].'\';');       break;
          case 'GRELPROP':      ShowHTML('    else document.Form.p_proponente.value=\''.$_REQUEST['p_proponente'].'\';');     break;
          case 'GRELRESP':      ShowHTML('    else document.Form.p_solicitante.value=\''.$_REQUEST['p_solicitante'].'\';');   break;
          case 'GRELRESPATU':   ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';');         break;
          case 'GRELCC':        ShowHTML('    else document.Form.p_sqcc.value=\''.$_REQUEST['p_sqcc'].'\';');                 break;
          case 'GRELSETOR':     ShowHTML('    else document.Form.p_uorg_resp.value=\''.$_REQUEST['p_uorg_resp'].'\';');       break;
          case 'GRELPRIO':      ShowHTML('    else document.Form.p_prioridade.value=\''.$_REQUEST['p_prioridade'].'\';');     break;
          case 'GRELLOCAL':     ShowHTML('    else document.Form.p_uf.value=\''.$_REQUEST['p_uf'].'\';');                     break;
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
        ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
        ShowHTML('    if (exec >= 0) document.Form.p_fase.value=\''.substr($w_fase_exec,1,100).'\';');
        ShowHTML('    if (conc >= 0) document.Form.p_fase.value='.$w_fase_conc.';');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\'; ');
        //ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value=\'S\'; else document.Form.p_atraso.value=\''.$_REQUEST['p_atraso'].'\'; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        $sql = new db_getMenuData; $RS2 = $sql->getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Protocolo',5,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        ShowHTML('<input type="Hidden" name="p_tipo" value="">');
        switch ($p_agrega) {
          case 'GRELETAPA':     if ($_REQUEST['p_atividade']=='')   ShowHTML('<input type="Hidden" name="p_atividade" value="">');    break;
          case 'GRELPROJ':      if ($_REQUEST['p_chave_pai']=='')   ShowHTML('<input type="Hidden" name="p_chave_pai" value="">');    break;
          case 'GRELPROP':      if ($_REQUEST['p_proponente']=='')  ShowHTML('<input type="Hidden" name="p_proponente" value="">');   break;
          case 'GRELRESP':      if ($_REQUEST['p_solicitante']=='') ShowHTML('<input type="Hidden" name="p_solicitante" value="">');  break;
          case 'GRELRESPATU':   if ($_REQUEST['p_usu_resp']=='')    ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');     break;
          case 'GRELCC':        if ($_REQUEST['p_sqcc']=='')        ShowHTML('<input type="Hidden" name="p_sqcc" value="">');         break;
          case 'GRELSETOR':     if ($_REQUEST['p_uorg_resp']=='')   ShowHTML('<input type="Hidden" name="p_uorg_resp" value="">');  break;
          case 'GRELPRIO':      if ($_REQUEST['p_prioridade']=='')  ShowHTML('<input type="Hidden" name="p_prioridade" value="">');   break;
          case 'GRELLOCAL':     if ($_REQUEST['p_uf']=='')          ShowHTML('<input type="Hidden" name="p_uf" value="">');           break;
          case 'GRELTIPDEM':    if ($_REQUEST['p_empenho']=='')     ShowHTML('<input type="Hidden" name="p_empenho" value="">');      break;
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
          case 'GRELETAPA':
            if ($w_nm_quebra!=f($row,'nm_etapa')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
          case 'GRELPROJ':
            if ($w_nm_quebra!=f($row,'nm_projeto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
          case 'GRELPROP':
            if ($w_nm_quebra!=f($row,'nm_pessoa_origem')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
          case 'GRELRESP':
            if ($w_nm_quebra!=f($row,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
          case 'GRELRESPATU': 
            if ($w_nm_quebra!=f($row,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
          case 'GRELCC':
            if ($w_nm_quebra!=f($row,'sg_cc')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_cc'));
              } 
              $w_nm_quebra  = f($row,'sg_cc');
              $w_chave      = f($row,'sq_cc');
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
          case 'GRELSETOR':
            if ($w_nm_quebra!=f($row,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));
              } 
              $w_nm_quebra  = f($row,'nm_unidade_resp');
              $w_chave      = f($row,'sq_unidade');
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
          case 'GRELPRIO':
            if ($w_nm_quebra!=f($row,'nm_tipo_despacho')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
          case 'GRELLOCAL':
            if ($w_nm_quebra!=f($row,'co_uf')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
          case 'GRELTIPDEM':
            if ($w_nm_quebra!=f($row,'nm_demanda_tipo')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 1;
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
          // Se for gera��o de MS-Word, quebra a p�gina
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
            case 'GRELETAPA':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_etapa'));          break;
            case 'GRELPROJ':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));        break;
            case 'GRELPROP':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_pessoa_origem'));  break;
            case 'GRELRESP':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));          break;
            case 'GRELRESPATU':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));           break;
            case 'GRELCC':          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_cc'));             break;
            case 'GRELSETOR':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));   break;
            case 'GRELPRIO':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_tipo_despacho'));  break;
            case 'GRELLOCAL':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_uf'));             break;
            case 'GRELTIPDEM':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_empenho'));        break;
          } 
          $w_linha = $w_linha + 1;
        } 
        if (nvl(f($row,'conclusao'),'')=='') {
          if (Nvl(f($row,'fim'),f($row,'limite_conclusao')) < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          if (f($row,'sg_tramite')=='CI') {
            $t_cad      = $t_cad + 1;
            $t_totcad   = $t_totcad + 1;
          } else {
            $t_tram     = $t_tram + 1;
            $t_tottram  = $t_tottram + 1;
          } 
        } else {
          $t_conc       = $t_conc + 1;
          $t_totconc    = $t_totconc + 1;
        } 
        $t_valor        = $t_valor + f($row,'qtd_processo');
        $t_totvalor     = $t_totvalor + f($row,'qtd_processo');
        $t_custo        = $t_custo + f($row,'qtd_documento');
        $t_totcusto     = $t_totcusto + f($row,'qtd_documento');
        $t_solic        = $t_solic + 1;
        $t_totsolic     = $t_totsolic + 1;
        $w_qt_quebra    = $w_qt_quebra + 1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
      ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
      ShowHTML('          <td><b>Totais</td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1);
    } 
    if ($w_embed != 'WORD') ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_graf=='N') {
      $w_legenda = array('Documentos','Processos','Cadastramento','Em execu��o','Conclu�dos','Total');
      include_once($w_dir_volta.'funcoes/geragraficogoogle.php');
      ShowHTML('<tr><td align="center"><br>');
      ShowHTML(geraGraficoGoogle(f($RS_Menu,'nome').' - Resumo',$SG,'bar',
                                 array($t_totsolic,$t_totconc,$t_tottram,$t_totcad,$t_totvalor,$t_totcusto),
                                 $w_legenda
                                )
              );
    }    
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Exibe par�metros de apresenta��o
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="97%" cellspacing=0>');
    ShowHTML('         <tr><td width="50%"><td></tr>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Par�metros de Apresenta��o</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    //if (f($RS_Menu,'solicita_cc')=='S') {
    //  if ($p_agrega=='GRELCC')      ShowHTML('          <option value="GRELCC" selected>Classifica��o');              else ShowHTML('          <option value="GRELCC">Classifica��o');
    //} 
    //if ($SG=='PROJETO') {
    //  if ($p_agrega=='GRELETAPA')   ShowHTML('          <option value="GRELETAPA" selected>Etapa de projeto');        else ShowHTML('          <option value="GRELETAPA">Etapa de projeto');
    //} 
    //if ($p_agrega=='GRELPRIO')      ShowHTML('          <option value="GRELPRIO" selected>�ltimo despacho');                 else ShowHTML('          <option value="GRELPRIO">�ltimo despacho');
    //if ($p_agrega=='GRELRESPATU')   ShowHTML('          <option value="GRELRESPATU" selected>Executor');              else ShowHTML('          <option value="GRELRESPATU">Executor');
    //if ($p_agrega=='GRELPROP')      ShowHTML('          <option value="GRELPROP" selected>Proced�ncia externa');              else ShowHTML('          <option value="GRELPROP">Proced�ncia externa');
    //if ($SG=='PROJETO') {
    //  if ($p_agrega=='GRELPROJ')    ShowHTML('          <option value="GRELPROJ" selected>Projeto');                  else ShowHTML('          <option value="GRELPROJ">Projeto');
    //} 
    if ($p_agrega=='GRELRESP')                       ShowHTML('          <option value="GRELRESP" selected>Solicitante');                else ShowHTML('          <option value="GRELRESP">Solicitante');
    if (Nvl($p_agrega,'GRELSETOR')=='GRELSETOR')     ShowHTML('          <option value="GRELSETOR" selected>Unidade solicitante');       else ShowHTML('          <option value="GRELSETOR">Unidade solicitante');
    //if (substr(f($RS_Menu,'sigla'),0,3)=='GDT') {
    //  if ($p_agrega=='GRELTIPDEM')  ShowHTML('          <option value="GRELTIPDEM" selected>Tipo da demanda');        else ShowHTML('          <option value="GRELTIPDEM">Tipo da demanda');
    //}
    //if ($p_agrega=='GRELLOCAL')     ShowHTML('          <option value="GRELLOCAL" selected>UF');                      else ShowHTML('          <option value="GRELLOCAL">UF');
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibi��o do gr�fico?</b>',$p_graf,'p_graf');
    MontaRadioSN('<b>Limita tamanho do assunto?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Crit�rios de Busca</td>');
    // Se a op��o for ligada ao m�dulo de projetos, permite a sele��o do projeto  e da etapa
    if ($SG=='PROJETO') {
      ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
      $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
      SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na rela��o.',$p_chave_pai,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_chave_pai','PJLIST','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_atividade\'; document.Form.submit();"');
      ShowHTML('      </tr>');
      ShowHTML('      <tr>');
      SelecaoEtapa('Eta<u>p</u>a:','P','Se necess�rio, indique a etapa � qual esta atividade deve ser vinculada.',$p_atividade,$p_chave_pai,null,'p_atividade',null,null);
      ShowHTML('      </tr>');
      ShowHTML('          </table>');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
        SelecaoCC('C<u>l</u>assifica��o:','C','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('          </table>');
      } 
    } else {
      //ShowHTML('          <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
      //ShowHTML('          <tr valign="top">');
      //selecaoServico('<U>R</U>estringir a:', 'S', null, $p_sq_menu_relac, $P2, null, 'p_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
      //if(Nvl($p_sq_menu_relac,'')!='') {
      //  ShowHTML('          <tr valign="top">');
      //  if ($p_sq_menu_relac=='CLASSIF') {
      //    SelecaoSolic('Classifica��o',null,null,$w_cliente,$p_sqcc,$p_sq_menu_relac,null,'p_sqcc','SIWSOLIC',null);
      //  } else {
      //    SelecaoSolic('Vincula��o',null,null,$w_cliente,$p_chave_pai,$p_sq_menu_relac,f($RS_Menu,'sq_menu'),'p_chave_pai',null,null);
      //  }
      //}
      //ShowHTML('          </td></tr></table></td></tr>');    
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Buscar por?</b><br>');
    if ($p_uf=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_uf" value="S" checked> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value="N"> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value=""> Ambos');
    } elseif ($p_uf=='N') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_uf" value="S"> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value="N" checked> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value=""> Ambos');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_uf" value="S"> Processo <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value="N"> Documento <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_uf" value="" checked> Ambos');
    }
    
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>N</U>�mero do pedido:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_prioridade" size="18" maxlength="18" value="'.$p_prioridade.'"></td>');
    ShowHTML('          <td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_pais" size="6" maxlength="5" value="'.$p_pais.'">.<INPUT class="STI" type="text" name="p_regiao" style="text-align:right;" size="7" maxlength="6" value="'.$p_regiao.'">/<INPUT class="STI" type="text" name="p_cidade" size="4" maxlength="4" value="'.$p_cidade.'"></td>');

    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do empr�stimo na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    //ShowHTML('          <td><b>Dias para a de<U>v</U>olu��o:<br><INPUT ACCESSKEY="V" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Data da solici<u>t</u>a��o entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
    ShowHTML('          <td><b>E<u>l</u>imina��o entre:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>N�mero:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="'.$p_empenho.'">');
    selecaoEspecieDocumento('<u>E</u>sp�cie documental:','E','Selecione a esp�cie do documento.',$p_usu_resp,null,'p_usu_resp',null,null);
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:','O',null,$p_unidade,null,'p_unidade',null,null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>A</U>ssunto:<br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="'.$p_assunto.'"></td>');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" '.$w_Disabled.' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="'.$p_processo.'"></td>');
    ShowHTML('        </tr></table>');
    //SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor da demanda na rela��o.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
    //ShowHTML('      <tr>');
    //SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
    //SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    //ShowHTML('      <tr>');
    //SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
    ShowHTML('      <tr valign="top">');
    //ShowHTML('          <td><b>Apenas empr�stimos com data limite excedida?</b><br>');
    //if ($p_atraso=='S') {
    //  ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> N�o');
    //} else {
    //  ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> N�o');
    //} 
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  if($p_tipo == 'PDF') RodapePdf();
  else                 Rodape();
  
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
    case 'GRELETAPA':   ShowHTML('          <td><b>Etapa</td>');                break;
    case 'GRELPROJ':    ShowHTML('          <td><b>Projeto</td>');              break;
    case 'GRELPROP':    ShowHTML('          <td><b>Origem externa</td>');       break;
    case 'GRELRESP':    ShowHTML('          <td><b>Respons�vel</td>');          break;
    case 'GRELRESPATU': ShowHTML('          <td><b>Executor</td>');             break;
    case 'GRELCC':      ShowHTML('          <td><b>Classifica��o</td>');        break;
    case 'GRELSETOR':   ShowHTML('          <td><b>Unidade solicitante</td>');  break;
    case 'GRELPRIO':    ShowHTML('          <td><b>�ltimo despacho</td>');      break;
    case 'GRELLOCAL':   ShowHTML('          <td><b>UF</td>');                   break;
    case 'GRELTIPDEM':  ShowHTML('          <td><b>Tipo de demanda</td>');      break;
  } 
  ShowHTML('          <td><b>Total</td>');
  ShowHTML('          <td><b>Cadastramento</td>');
  ShowHTML('          <td><b>Execu��o</td>');
  ShowHTML('          <td><b>Conclu�do</td>');
  //ShowHTML('          <td><b>Atraso</td>');
  //ShowHTML('          <td><b>Aviso</td>');
  ShowHTML('          <td><b>Processos</td>');
  ShowHTML('          <td><b>Documentos</td>');
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
  if ($l_conc>0 && $w_embed != 'WORD')     ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe lista.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_conc,0).'</a>&nbsp;</td>');                         else ShowHTML('          <td align="center">'.formatNumber($l_conc,0).'&nbsp;</td>');
  //if ($l_atraso>0 && $w_embed != 'WORD')   ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe lista.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.formatNumber($l_atraso,0).'</a>&nbsp;</td>'); else ShowHTML('          <td align="center"><b>'.$l_atraso.'&nbsp;</td>');
  //if ($l_aviso>0 && $w_embed != 'WORD')    ShowHTML('          <td align="center"><font color="red"><b>'.formatNumber($l_aviso,0).'&nbsp;</td>');                                                                                                                                                                                     else ShowHTML('          <td align="center"><b>'.$l_aviso.'&nbsp;</td>');
  ShowHTML('          <td align="center">'.$l_valor.'&nbsp;</td>');
  ShowHTML('          <td align="center">'.$l_custo.'&nbsp;</td>');
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
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 
} 
?>


