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
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
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
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');

// =========================================================================
//  /gr_convenios.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Consultas gerenciais do m�dulo de contratos
// Mail     : celso@sbpi.com.br
// Criacao  : 13/07/2006, 11:30
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
$w_pagina       = 'gr_convenios.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_ac/';

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O='P';

switch ($O) {
  case 'V': $w_TP=$TP.' - Gr�fico'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;

$w_troca         = $_REQUEST['w_troca'];
$p_projeto       = upper($_REQUEST['p_projeto']);
$p_atividade     = upper($_REQUEST['p_atividade']);
$p_tipo          = upper($_REQUEST['p_tipo']);
$p_ativo         = upper($_REQUEST['p_ativo']);
$p_solicitante   = upper($_REQUEST['p_solicitante']);
$p_prioridade    = upper($_REQUEST['p_prioridade']);
$p_unidade       = upper($_REQUEST['p_unidade']);
$p_proponente    = upper($_REQUEST['p_proponente']);
$p_ordena        = $_REQUEST['p_ordena'];
$p_ini_i         = upper($_REQUEST['p_ini_i']);
$p_ini_f         = upper($_REQUEST['p_ini_f']);
$p_fim_i         = upper($_REQUEST['p_fim_i']);
$p_fim_f         = upper($_REQUEST['p_fim_f']);
$p_atraso        = upper($_REQUEST['p_atraso']);
$p_chave         = upper($_REQUEST['p_chave']);
$p_objeto        = upper($_REQUEST['p_objeto']);
$p_pais          = upper($_REQUEST['p_pais']);
$p_regiao        = upper($_REQUEST['p_regiao']);
$p_uf            = upper($_REQUEST['p_uf']);
$p_cidade        = upper($_REQUEST['p_cidade']);
$p_usu_resp      = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp     = upper($_REQUEST['p_uorg_resp']);
$p_palavra       = upper($_REQUEST['p_palavra']);
$p_prazo         = upper($_REQUEST['p_prazo']);
$p_fase          = explodeArray($_REQUEST['p_fase']);
$p_sqcc          = upper($_REQUEST['p_sqcc']);
$p_empenho       = upper($_REQUEST['p_empenho']);
$p_processo      = upper($_REQUEST['p_processo']);
$p_agrega        = upper($_REQUEST['p_agrega']);
$p_tamanho       = upper($_REQUEST['p_tamanho']);
$p_sq_menu_relac = upper($_REQUEST['p_sq_menu_relac']);
$p_chave_pai     = upper($_REQUEST['p_chave_pai']);

// Recupera a configura��o do servi�o
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

// Recupera a configura��o do servi�o de origem
$sql = new db_getMenuData; $RS_Menu_Origem = $sql->getInstanceOf($dbms,$P2);

// Carrega o segmento do cliente
$sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente); 
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
  
  if ($O=='L' || $O=='V' || $O=='W' || $p_tipo=='WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if (nvl($p_chave_pai,'')>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_chave_pai);
      $w_filtro.='<tr valign="top"><td align="right">Vincula��o <td>[<b>'.exibeSolic($w_dir,$p_chave_pai,f($RS,'dados_solic'),'S').'</b>]';
    }    
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    }     
    if ($p_sqcc>'') {
      $w_linha++;
      $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Classifica��o <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') {
      $w_linha++;
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Contrato n� <td><font size=1>[<b>'.$p_chave.'</b>]';
    }
    if ($p_prazo>'') {
      $w_linha++;
      $w_filtro=$w_filtro.' <tr valign="top"><td align="right"><font size=1>Prazo para conclus�o at�<td><font size=1>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
    }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Respons�vel <td><font size=1>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Unidade respons�vel <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Executor <td><font size=1>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uorg_resp>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Unidade atual <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getCountryData; $RS = $sql->getInstanceOf($dbms,$p_pais);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Pa�s <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'') {
      $w_linha++;
      $sql = new db_getRegionData; $RS = $sql->getInstanceOf($dbms,$p_regiao);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Regi�o <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getStateData; $RS = $sql->getInstanceOf($dbms,$p_pais,$p_uf);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Estado <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_cidade>'') {
      $w_linha++;
      $sql = new db_getCityData; $RS = $sql->getInstanceOf($dbms,$p_cidade);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Cidade <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_prioridade>'') { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Prioridade <td><font size=1>[<b>'.RetornaPrioridade($p_prioridade).'</b>]'; }
    if ($p_proponente>'') { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Outra parte <td><font size=1>[<b>'.$p_proponente.'</b>]'; }
    if ($p_objeto>'')     { $w_linha++;  $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Objeto <td><font size=1>[<b>'.$p_objeto.'</b>]'; }
    if ($p_palavra>'')    {  $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>C�digo interno <td><font size=1>[<b>'.$p_palavra.'</b>]'; }
    if ($p_ini_i>'')      { $w_linha++;  $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>In�cio vig�ncia <td><font size=1>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]'; }
    if ($p_fim_i>'')      { $w_linha++;  $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>T�rmino vig�ncia <td><font size=1>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    if ($p_atraso>'')     { $w_linha++;  $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>C�digo externo <td><font size=1>[<b>'.$p_atraso.'</b>]'; }
    if ($p_empenho>'')    { $w_linha++;  $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>N�mero do empenho<td><font size=1>[<b>'.$p_empenho.'</b>]'; }
    if ($p_processo>'')   { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>N�mero do processo<td><font size=1>[<b>'.$p_processo.'</b>]'; }
    if ($w_filtro>'')     { $w_linha++;  $w_filtro='<table border=0><tr valign="top"><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>'.$w_filtro.'</ul></tr></table>'; }
    $sql = new db_getSolicList; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,4,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente, 
        $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, 
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_chave_pai, $p_atividade, 
        null, null, $p_empenho, $p_processo);
    switch ($p_agrega) {
      case substr(f($RS_Menu,'sigla'),0,3).'PROJ':
        $w_TP=$TP.' - Por projeto';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':
        $w_TP = $TP.' - Por etapa de projeto';
        $RS1 = SortArray($RS1,'cd_ordem','asc');
        break;        
      case substr(f($RS_Menu,'sigla'),0,3).'PROP':
        $w_TP=$TP.' - Pela outra parte';
        $RS1 = SortArray($RS1,'nm_outra_parte_resumido_ind','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'RESP':
        $w_TP=$TP.' - Por respons�vel';
        $RS1 = SortArray($RS1,'nm_solic','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':
        $w_TP=$TP.' - Por executor';
        $RS1 = SortArray($RS1,'nm_exec','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'CC':
        $w_TP=$TP.' - Por classifica��o';
        $RS1 = SortArray($RS1,'sg_cc','asc');
        break;
      case substr(f($RS_Menu,'sigla'),0,3).'SETOR':
        $w_TP=$TP.' - Por setor respons�vel';
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
  } elseif($p_tipo == 'PDF') {
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de '.f($RS_Menu,'nome'),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif ($p_tipo=='EXCEL') {
    $w_embed = 'WORD';
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
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
      if(nvl($p_sq_menu_relac,'')>'') {
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
      Validate('p_chave','Chave','','','1','18','','0123456789');
      Validate('p_proponente','Outra parte','','','2','90','1','');
      Validate('p_palavra','C�digo interno','','','3','90','1','1');
      Validate('p_atraso','C�digo externo','','','1','90','1','1');
      Validate('p_objeto','Objeto','','','2','90','1','1');
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
      ShowHTML('    alert(\'Voc� deve informar pelo menos uma fase!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
      Validate('p_fim_i','Conclus�o inicial','DATA','','10','10','','0123456789/');
      Validate('p_fim_f','Conclus�o final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de conclus�o ou nenhuma delas!\');');
      ShowHTML('     theForm.p_fim_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_fim_i','Conclus�o inicial','<=','p_fim_f','Conclus�o final');
      ShowHTML('  if (theForm.p_agrega[theForm.p_agrega.selectedIndex].value==\''.substr(f($RS_Menu,'sigla'),0,3).'ETAPA\' && theForm.p_projeto.selectedIndex==0) {');
      ShowHTML('     alert (\'A agrega��o por etapa exige a sele��o de um projeto!\');');
      ShowHTML('     theForm.p_projeto.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
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
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
    if ($w_troca>'') {
      // Se for recarga da p�gina
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
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),3);
      ShowHTML('<HR>');
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
    } 
  } 
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $O=='W') {
    if ($O=='L') {
      ShowHTML('<tr><td><font size="1">');
      if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      } else {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    } 
    ImprimeCabecalho();
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (chave_aux, filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':     ShowHTML('      document.Form.p_atividade.value=filtro;');      break;
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
          case substr(f($RS_Menu,'sigla'),0,3).'PROJ':      ShowHTML('    else document.Form.p_projeto.value=\''.$_REQUEST['p_projeto'].'\';');         break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROP':      ShowHTML('    else document.Form.p_proponente.value=\''.$_REQUEST['p_proponente'].'\';');   break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESP':      ShowHTML('    else document.Form.p_solicitante.value=\''.$_REQUEST['p_solicitante'].'\';'); break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':   ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';');       break;
          case substr(f($RS_Menu,'sigla'),0,3).'CC':        ShowHTML('    else document.Form.p_sqcc.value=\''.$_REQUEST['p_sqcc'].'\';');               break;
          case substr(f($RS_Menu,'sigla'),0,3).'SETOR':     ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');         break;
          case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':     ShowHTML('    else { document.Form.p_uf.value=\''.$_REQUEST['p_uf'].'\';}');                break;
        } 
        $sql = new db_getTramiteList; $RS2 = $sql->getInstanceOf($dbms,$P2,null,null,null);
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
        $sql = new db_getMenuData; $RS2 = $sql->getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Contrato',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        switch ($p_agrega) {
          case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':     if ($_REQUEST['p_atividade']=='')       ShowHTML('<input type="Hidden" name="p_atividade" value="">');  break;
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
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.montaOrdemEtapa(f($row1,'sq_projeto_etapa')).' - '.f($row1,'nm_etapa'));
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
              $w_linha=$w_linha+1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROJ':
            if ($w_nm_quebra!=f($row1,'nm_projeto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_projeto'));
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
              $w_linha=$w_linha+1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'PROP':
            if ($w_nm_quebra!=f($row1,'nm_outra_parte_resumido')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_outra_parte_resumido'));
              } 
              $w_nm_quebra=f($row1,'nm_outra_parte_resumido');
              $w_chave=f($row1,'nm_outra_parte_resumido');
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
          case substr(f($RS_Menu,'sigla'),0,3).'RESP':
            if ($w_nm_quebra!=f($row1,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_solic'));
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
              $w_linha=$w_linha+1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':
            if ($w_nm_quebra!=f($row1,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_exec'));
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
              $w_linha=$w_linha+1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'CC':
            if ($w_nm_quebra!=f($row1,'sg_cc')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'sg_cc'));
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
              $w_linha=$w_linha+1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'SETOR':
            if ($w_nm_quebra!=f($row1,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_unidade_resp'));
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
              $w_linha=$w_linha+1;
            } 
            break;
          case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':
            if ($w_nm_quebra!=f($row1,'co_uf')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$w_chave_aux);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'co_uf'));
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
              $w_linha=$w_linha+1;
            } 
            break;
        } 
        if ($O=='W' && $w_linha>25) {
          // Se for gera��o de MS-Word, quebra a p�gina
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
            case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_etapa'));                   break;
            case substr(f($RS_Menu,'sigla'),0,3).'PROJ':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_projeto'));                 break;
            case substr(f($RS_Menu,'sigla'),0,3).'PROP':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_outra_parte_resumido'));    break;
            case substr(f($RS_Menu,'sigla'),0,3).'RESP':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_solic'));                   break;
            case substr(f($RS_Menu,'sigla'),0,3).'RESPATU': ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_exec'));                    break;
            case substr(f($RS_Menu,'sigla'),0,3).'CC':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'sg_cc'));                      break;
            case substr(f($RS_Menu,'sigla'),0,3).'SETOR':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_unidade_resp'));            break;
            case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'co_uf'));                      break;
          } 
          $w_linha=$w_linha+1;
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
      ShowHTML('          <td><font size="1"><b>Totais</font></td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1,-1);
    } 
    ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_tipo=='N') {
      // Coloca o gr�fico somente se o usu�rio desejar
      ShowHTML('<tr><td align="center" height=20>');
      ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.$w_dir.'geragrafico.php?p_genero=M&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      ShowHTML('<tr><td align="center" height=20>');
      if (($t_totcad+$t_tottram)>0)
        ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.$w_dir.'geragrafico.php?p_genero=M&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
    } 
  } elseif ($O=='P') {
    // Carrega o segmento do cliente
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Exibe par�metros de apresenta��o
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Par�metros de Apresenta��o</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><font size="1"><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      if ($p_agrega==(substr(f($RS_Menu,'sigla'),0,3).'CC'))
        ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'CC" selected>Classifica��o');
      else
        ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'CC'.'">Classifica��o');
    } 
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'RESPATU')                                                    ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'RESPATU" selected>Executor');         else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'RESPATU">Executor');
    if (Nvl($p_agrega,substr(f($RS_Menu,'sigla'),0,3).'PROP')==substr(f($RS_Menu,'sigla'),0,3).'PROP') {
      ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROP" selected>Outra parte');
    } else { 
      ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROP">Outra parte');
    }
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'PROJ')              ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROJ" selected>projeto');             else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'PROJ">Projeto');
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'ETAPA')             ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'ETAPA" selected>Al�nea');             else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'ETAPA">Al�nea');
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'RESP')              ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'RESP" selected>Respons�vel');         else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'RESP">Respons�vel');
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'SETOR')             ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'SETOR" selected>Setor respons�vel');  else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'SETOR">Setor respons�vel');
    if ($p_agrega==substr(f($RS_Menu,'sigla'),0,3).'LOCAL')             ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'LOCAL" selected>UF');                 else ShowHTML('          <option value="'.substr(f($RS_Menu,'sigla'),0,3).'LOCAL">UF');
    ShowHTML('          </select></td>');
    MontaRadioSN('<b>Inibe exibi��o do gr�fico?</b>',$p_tipo,'p_tipo');
    MontaRadioNS('<b>Limita tamanho do objeto?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Crit�rios de Busca</td>');
    // Se a op��o for ligada ao m�dulo de projetos, permite a sele��o do projeto  e da etapa
    ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
    ShowHTML('          <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    ShowHTML('          <tr valign="top">');
    selecaoServico('<U>R</U>estringir a:', 'S', null, $p_sq_menu_relac, $P2, null, 'p_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($p_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($p_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classifica��o',null,null,$w_cliente,$p_sqcc,$p_sq_menu_relac,null,'p_sqcc','SIWSOLIC',null);
      } else {
        SelecaoSolic('Vincula��o',null,null,$w_cliente,$p_chave_pai,$p_sq_menu_relac,f($RS_Menu,'sq_menu'),'p_chave_pai',null,null);
      }
    }
    ShowHTML('          </td></tr></table></td></tr>');    
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><font size="1"><b>C<u>h</u>ave:<br><INPUT ACCESSKEY="H" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
    ShowHTML('          <td><font size="1"><b>O<U>u</U>tra parte:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><font size="1"><b>C�<U>d</U>igo interno:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_palavra" size="18" maxlength="18" value="'.$p_palavra.'"></td>');
    ShowHTML('          <td><font size="1"><b>C�<U>d</U>igo externo:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_atraso" size="18" maxlength="18" value="'.$p_atraso.'"></td>');
    ShowHTML('      <tr valign="top">');
    if($w_segmento=='P�blico') {
      ShowHTML('          <td><font size="1"><b><U>N</U>�mero do empenho:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_empenho" size="18" maxlength="18" value="'.$p_empenho.'"></td>');
      ShowHTML('          <td><font size="1"><b><U>N</U>�mero do processo:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_processo" size="18" maxlength="18" value="'.$p_processo.'"></td>');
    }
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('<U>S</U>etor respons�vel:','S',null,$p_unidade,null,'p_unidade',null,null);
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor do contrato na rela��o.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
    SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde o contrato se encontra na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    ShowHTML('      <tr>');
    SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
    SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><font size="1"><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
    ShowHTML('          <td><font size="1"><b>Dias para <U>t</U>�rmino da vig�ncia:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><font size="1"><b>In�<u>c</u>io vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
    ShowHTML('          <td><font size="1"><b>Fi<u>m</u> vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\';">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gerar Word" onClick="javascript:document.Form.O.value=\'W\'; document.Form.target=\'Word\'">');
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
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
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
    case substr(f($RS_Menu,'sigla'),0,3).'ETAPA':       ShowHTML('          <td><font size="1"><b>Al�nea</font></td>');             break;
    case substr(f($RS_Menu,'sigla'),0,3).'PROJ':        ShowHTML('          <td><font size="1"><b>Projeto</font></td>');            break;
    case substr(f($RS_Menu,'sigla'),0,3).'PROP':        ShowHTML('          <td><font size="1"><b>Proponente</font></td>');         break;
    case substr(f($RS_Menu,'sigla'),0,3).'RESP':        ShowHTML('          <td><font size="1"><b>Respons�vel</font></td>');        break;
    case substr(f($RS_Menu,'sigla'),0,3).'RESPATU':     ShowHTML('          <td><font size="1"><b>Executor</font></td>');           break;
    case substr(f($RS_Menu,'sigla'),0,3).'CC':          ShowHTML('          <td><font size="1"><b>Classifica��o</font></td>');      break;
    case substr(f($RS_Menu,'sigla'),0,3).'SETOR':       ShowHTML('          <td><font size="1"><b>Setor respons�vel</font></td>');  break;
    case substr(f($RS_Menu,'sigla'),0,3).'LOCAL':       ShowHTML('          <td><font size="1"><b>UF</font></td>');                 break;
  } 
  ShowHTML('          <td><font size="1"><b>Total</font></td>');
  ShowHTML('          <td><font size="1"><b>Cad.</font></td>');
  ShowHTML('          <td><font size="1"><b>Exec.</font></td>');
  ShowHTML('          <td><font size="1"><b>Conc.</font></td>');
  ShowHTML('          <td><font size="1"><b>Aviso</font></td>');

  if ($_SESSION['INTERNO']=='S') {
    ShowHTML('          <td><font size="1"><b>$ Prev.</font></td>');
    ShowHTML('          <td><font size="1"><b>$ Real</font></td>');
    ShowHTML('          <td><font size="1"><b>Real > Previsto</font></td>');
  } 
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave,$l_chave_aux) {
  extract($GLOBALS);
  if ($O=='L')               ShowHTML('          <td align="right"><font size="1"><a class="hl" href="javascript:lista('.$l_chave_aux.', \''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe os contratos.\'; return true" onMouseOut="window.status=\'\'; return true">'.formatNumber($l_solic,0).'</a>&nbsp;</font></td>');     else ShowHTML('          <td align="right"><font size="1">'.formatNumber($l_solic,0).'&nbsp;</font></td>');
  if ($l_cad>0 && $O=='L')   ShowHTML('          <td align="right"><a class="hl" href="javascript:lista('.$l_chave_aux.', \''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe os contratos.\'; return true" onMouseOut="window.status=\'\'; return true"><font size="1">'.formatNumber($l_cad,0).'</a>&nbsp;</font></td>');        else ShowHTML('          <td align="right"><font size="1">'.formatNumber($l_cad,0).'&nbsp;</font></td>');
  if ($l_tram>0 && $O=='L')  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista('.$l_chave_aux.', \''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe os contratos.\'; return true" onMouseOut="window.status=\'\'; return true"><font size="1">'.formatNumber($l_tram,0).'</a>&nbsp;</font></td>');       else ShowHTML('          <td align="right"><font size="1">'.formatNumber($l_tram,0).'&nbsp;</font></td>');
  if ($l_conc>0 && $O=='L')  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista('.$l_chave_aux.', \''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe os contratos.\'; return true" onMouseOut="window.status=\'\'; return true"><font size="1">'.formatNumber($l_conc,0).'</a>&nbsp;</font></td>');       else ShowHTML('          <td align="right"><font size="1">'.formatNumber($l_conc,0).'&nbsp;</font></td>');
  if ($l_aviso>0 && $O=='L') ShowHTML('          <td align="right"><font size="1" color="red"><b>'.formatNumber($l_aviso,0).'&nbsp;</font></td>'); else ShowHTML('          <td align="right"><font size="1"><b>'.$l_aviso.'&nbsp;</font></td>');
  if ($_SESSION['INTERNO']=='S') {
    ShowHTML('          <td align="right"><font size="1">'.formatNumber($l_valor,2).'&nbsp;</font></td>');
    ShowHTML('          <td align="right"><font size="1">'.formatNumber($l_custo,2).'&nbsp;</font></td>');
    if ($l_acima>0) ShowHTML('          <td align="right"><font size="1" color="red"><b>'.formatNumber($l_acima,0).'&nbsp;</font></td>'); else ShowHTML('          <td align="right"><font size="1"><b>'.$l_acima.'&nbsp;</font></td>');
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
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  }
}
?>