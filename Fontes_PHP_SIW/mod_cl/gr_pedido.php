<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServSubord.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoLCModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoReajuste.php');
include_once($w_dir_volta.'funcoes/selecaoIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoLCFonteRecurso.php');
include_once($w_dir_volta.'funcoes/selecaoCTEspecificacao.php');
include_once($w_dir_volta.'funcoes/selecaoLCJulgamento.php');
include_once($w_dir_volta.'funcoes/selecaoLCSituacao.php');
include_once($w_dir_volta.'funcoes/FusionCharts.php'); 
include_once($w_dir_volta.'funcoes/FC_Colors.php');
/**/
// =========================================================================
//  gr_pedido.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o m�dulo de passagens e di�rias
// Mail     : celso@sbpi.com.br
// Cria��o  : 26/05/2006 10:00
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

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);

$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'gr_pedido.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_cl/';
$w_troca        = $_REQUEST['w_troca'];

if ($O=='') $O='P';

switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem';   break;
  case 'V': $w_TP = $TP . ' - Gr�fico';     break;
  default:  $w_TP = $TP . ' - Listagem';    break;
} 

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;
$w_ano      = RetornaAno();

$p_tipo         = strtoupper($_REQUEST['w_tipo']);
$p_projeto      = strtoupper($_REQUEST['p_projeto']);
$p_atividade    = strtoupper($_REQUEST['p_atividade']);
$p_graf         = strtoupper($_REQUEST['p_graf']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);
$p_unidade      = strtoupper($_REQUEST['p_unidade']);
$p_proponente   = strtoupper($_REQUEST['p_proponente']);
$p_usu_resp      = strtoupper($_REQUEST['p_usu_resp']);
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$p_ini_i        = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f        = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i        = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f        = strtoupper($_REQUEST['p_fim_f']);
$p_atraso       = strtoupper($_REQUEST['p_atraso']);
$p_empenho       = strtoupper($_REQUEST['p_empenho']);
$p_chave        = strtoupper($_REQUEST['p_chave']);
$p_assunto      = strtoupper($_REQUEST['p_assunto']);
$p_pais         = strtoupper($_REQUEST['p_pais']);
$p_regiao       = strtoupper($_REQUEST['p_regiao']);
$p_uf           = strtoupper($_REQUEST['p_uf']);
$p_cidade       = strtoupper($_REQUEST['p_cidade']);
$p_uorg_resp    = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra      = strtoupper($_REQUEST['p_palavra']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = strtoupper($_REQUEST['p_sqcc']);
$p_agrega       = strtoupper($_REQUEST['p_agrega']);
$p_tamanho      = strtoupper($_REQUEST['p_tamanho']);

// Verifica se o cliente tem o m�dulo de protocolo e arquivo
$RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_pa='S'; else $w_pa='N'; 

// Recupera a configura��o do servi�o
$RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);

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
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_projeto>'') {
      $w_linha++;
      $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informa��es do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $RS = db_getSolicEtapa::getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_empenho>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">C�digo <td>[<b>'.$p_empenho.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descri��o <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Respons�vel <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $RS = db_getLCSituacao::getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
      foreach ($RS as $row) {
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situa��o do certame <td>[<b>'.f($row,'nome').'</b>]';
        break;
      }
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">N�mero do certame <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_pais>'') {
      $w_linha++;
      $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/servi�o<td>[<b>'.f($RS,'nome_completo').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $w_linha++;
      $RS = db_getLCModalidade::getInstanceOf($dbms, $p_usu_resp, $w_cliente, null, null, null, null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Modalidade<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'' || $p_cidade>'') {
      $w_linha++;
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
    } 
    if ($p_ativo=='S') {
      $w_linha++;
      $w_filtro .= '<tr valign="top"><td align="right">Restri��o<td>[<b>Apenas compras por decis�o judicial</b>]';
    } 
    if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Abertura de propostas <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Autoriza��o <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    switch ($p_agrega) {
      case 'GRCLABERTURA':
        $RS1 = db_getSolicCL::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
            null, null, $p_empenho, null);
        $w_TP = ' - Por m�s de abertura';
        $RS1 = SortArray($RS1,'data_abertura','asc');
        break;
      case 'GRCLAUTORIZ':
        $RS1 = db_getSolicCL::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
            null, null, $p_empenho, null);
        $w_TP = ' - Por m�s de autoriza��o';
        $RS1 = SortArray($RS1,'data_homologacao','asc');
        break;
      case 'GRCLUNIDADE':
        $RS1 = db_getSolicCL::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
            null, null, $p_empenho, null);
        $w_TP = ' - Por unidade solicitante';
        $RS1 = SortArray($RS1,'nm_unidade_resp','asc');
        break;
      case 'GRCLPROJ':
        $RS1 = db_getSolicCL::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
            null, null, $p_empenho, null);
        $w_TP = ' - Por projeto';
        $RS1 = SortArray($RS1,'dados_pai','asc');
        break;
      case 'GRCLMODAL':
        $RS1 = db_getSolicCL::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
            null, null, $p_empenho, null);
        $w_TP = ' - Por modalidade';
        $RS1 = SortArray($RS1,'nm_lcmodalidade','asc');
        break;
      case 'GRCLSITUACAO':
        $RS1 = db_getSolicCL::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
            null, null, $p_empenho, null);
        $w_TP = ' - Por situa��o do certame';
        $RS1 = SortArray($RS1,'nm_lcsituacao','asc');
        break;
    } 
  } 

  $w_linha_filtro = $w_linha;
  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome').$w_TP,$w_pag);
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
    ShowHTML('<HEAD>');
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_empenho','C�digo','','','2','60','1','1');
      Validate('p_proponente','Material','','','2','60','1','');
      if ($SG=='GRCLLIC') {
        Validate('p_palavra','Certame','','','2','14','1','1');
        Validate('p_regiao','Sequencial do protocolo','','','1','10','','0123456789');
        Validate('p_cidade','Ano do protocolo','','','4','4','','0123456789');
      }
      Validate('p_ini_i','In�cio do per�odo','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','T�rmino do per�odo','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_ini_i','In�cio do per�odo','<=','p_ini_f','T�rmino do per�odo');
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
      if ($P1==1) { // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      } 
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome').$w_TP,4);
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 

  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $w_embed == 'WORD') {
    if ($w_embed != 'WORD') {
      ShowHTML('<tr><td>');
      if (MontaFiltro('GET')>'') {
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
          case 'GRCLCIAVIAGEM': ShowHTML('     document.Form.p_usu_resp.value=filtro;'); break;
          case 'GRCLCIDADE':    ShowHTML('     document.Form.p_cidade.value=filtro;');   break;
          case 'GRCLUNIDADE':   ShowHTML('     document.Form.p_unidade.value=filtro;');  break;
          case 'GRCLPROJ':      ShowHTML('     document.Form.p_projeto.value=filtro;');  break;
          case 'GRCLABERTURA':  ShowHTML('     document.Form.p_ini_i.value=filtro;');    break;
          case 'GRCLAUTORIZ':   ShowHTML('     document.Form.p_fim_i.value=filtro;');    break;
          case 'GRCLMODAL':     ShowHTML('     document.Form.p_usu_resp.value=filtro;'); break;
          case 'GRCLSITUACAO':  ShowHTML('     document.Form.p_uf.value=filtro;');       break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRCLCIAVIAGEM': ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';'); break;
          case 'GRCLCIDADE':    ShowHTML('    else document.Form.p_cidade.value="'.$_REQUEST['p_cidade'].'";');       break;
          case 'GRCLUNIDADE':   ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');   break;
          case 'GRCLPROJ':      ShowHTML('    else document.Form.p_projeto.value=\''.$_REQUEST['p_projeto'].'\';');   break;
          case 'GRCLABERTURA':  ShowHTML('    else document.Form.p_ini_i.value=\''.$_REQUEST['p_ini_i'].'\';');       break;
          case 'GRCLAUTORIZ':   ShowHTML('    else document.Form.p_fim_i.value=\''.$_REQUEST['p_fim_i'].'\';');       break;
          case 'GRCLMODAL':     ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';'); break;
          case 'GRCLSITUACAO':  ShowHTML('    else document.Form.p_uf.value=\''.$_REQUEST['p_uf'].'\';');             break;
        } 
        $RS2 = db_getTramiteList::getInstanceOf($dbms,$P2,null,null);
        $RS2 = SortArray($RS2,'ordem','asc');
        $w_fase_exec='';
        foreach($RS2 as $row) {
          if (f($row,'sigla')=='CI') {
            $w_fase_cad=f($row,'sq_siw_tramite');
          } elseif (f($row,'sigla')=='AT') {
            $w_fase_conc=f($row,'sq_siw_tramite');
          } elseif (f($row,'ativo')=='S') {
            $w_fase_exec=$w_fase_exec.','.f($row,'sq_siw_tramite');
          } 
        } 
        ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
        ShowHTML('    if (exec >= 0) document.Form.p_fase.value=\''.substr($w_fase_exec,1,100).'\';');
        ShowHTML('    if (conc >= 0) document.Form.p_fase.value='.$w_fase_conc.';');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\';');
        ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value=\'S\'; else document.Form.p_atraso.value=\''.$_REQUEST['p_atraso'].'\'; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        ShowHTML('<BASE HREF="'.$conRootSIW.'">');
        $RS2 = db_getMenuData::getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Lista',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_dir.$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        ShowHTML('<input type="Hidden" name="p_atraso" value="N">');
        switch ($p_agrega) {
          case 'GRCLCIAVIAGEM': if ($_REQUEST['p_usu_resp']=='') ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');  break;
          case 'GRCLCIDADE':    if ($_REQUEST['p_cidade']=='')   ShowHTML('<input type="Hidden" name="p_cidade" value="">');    break;
          case 'GRCLUNIDADE':   if ($_REQUEST['p_unidade']=='')  ShowHTML('<input type="Hidden" name="p_unidade" value="">');   break;
          case 'GRCLPROJ':      if ($_REQUEST['p_projeto']=='')  ShowHTML('<input type="Hidden" name="p_projeto" value="">');   break;
          case 'GRCLABERTURA':  if ($_REQUEST['p_ini_i']=='')    ShowHTML('<input type="Hidden" name="p_ini_i" value="">');     break;
          case 'GRCLAUTORIZ':   if ($_REQUEST['p_fim_i']=='')    ShowHTML('<input type="Hidden" name="p_fim_i" value="">');     break;
          case 'GRCLMODAL':     if ($_REQUEST['p_usu_resp']=='') ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');  break;
          case 'GRCLSITUACAO':  if ($_REQUEST['p_uf']=='')       ShowHTML('<input type="Hidden" name="p_uf" value="">');        break;
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
      $RST = array_slice($RS1, (($P3-1)*$P4), $P4);
      foreach($RST as $row) {
        switch ($p_agrega) { 
          case 'GRCLCIAVIAGEM':
            if ($w_nm_quebra!=f($row,'nm_cia_viagem')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_cia_viagem'));
              } 
              $w_nm_quebra  = f($row,'nm_cia_viagem');
              $w_chave      = f($row,'sq_cia_transporte');
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
              $w_linha     += 1;
            } 
            break;
          case 'GRCLABERTURA':
            if ($w_nm_quebra!=date('Y/m',f($row,'data_abertura'))) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.date('Y/m',f($row,'data_abertura')));
              } 
              $w_nm_quebra  = date('Y/m',f($row,'data_abertura'));
              $w_chave      = date('Y/m',f($row,'data_abertura'));
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
              $w_linha     += 1;
            } 
            break;
          case 'GRCLAUTORIZ':
            if ($w_nm_quebra!=date('Y/m',f($row,'data_homologacao'))) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.date('Y/m',f($row,'data_homologacao')));
              } 
              $w_nm_quebra  = date('Y/m',f($row,'data_homologacao'));
              $w_chave      = date('Y/m',f($row,'data_homologacao'));
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
              $w_linha     += 1;
            } 
            break;
          case 'GRCLCIDADE':
            if ($w_nm_quebra!=f($row,'nm_destino')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_destino'));
              } 
              $w_nm_quebra  = f($row,'nm_destino');
              $w_chave      = f($row,'destino');
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
              $w_linha     += 1;
            } 
            break;
          case 'GRCLUNIDADE':
            if ($w_nm_quebra!=f($row,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
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
              $w_linha     += 1;
            } 
            break;
          case 'GRCLPROJ':
            if ($w_nm_quebra!=piece(f($row,'dados_pai'),null,'|@|',2)) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.piece(f($row,'dados_pai'),null,'|@|',2).' - '.piece(f($row,'dados_pai'),null,'|@|',3));
              } 
              $w_nm_quebra  = piece(f($row,'dados_pai'),null,'|@|',2);
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
              $w_linha     += 1;
            } 
            break;
          case 'GRCLMODAL':
            if (Nvl($w_nm_quebra,'')!=f($row,'nm_lcmodalidade')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_lcmodalidade'));
              } 
              $w_nm_quebra  = f($row,'nm_lcmodalidade');
              $w_chave      = f($row,'sq_lcmodalidade');
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
              $w_linha     += 1;
            } 
            break;
          case 'GRCLSITUACAO':
            if ($w_nm_quebra!=f($row,'nm_lcsituacao')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_lcsituacao'));
              } 
              $w_nm_quebra  = f($row,'nm_lcsituacao');
              $w_chave      = f($row,'sq_lcsituacao');
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
              $w_linha     += 1;
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
          $w_pag    += 1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case 'GRCLCIAVIAGEM':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_cia_transporte')); break;
            case 'GRCLCIDADE':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_destino'));        break;
            case 'GRCLUNIDADE':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));   break;
            case 'GRCLPROJ':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.piece(f($row,'dados_pai'),null,'|@|',2));        break;
            case 'GRCLABERTURA':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'data_abertura'));     break;
            case 'GRCLAUTORIZ':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'data_homologacao'));  break;
            case 'GRCLMODAL':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_lcmodalidade'));   break;
            case 'GRCLSITUACAO':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_lcsituacao'));     break;
          } 
          $w_linha += 1;
        } 
        if (nvl(f($row,'conclusao'),'')=='') {
          if (f($row,'fim') < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          if (f($row,'or_tramite')==1) {
            $t_cad      += 1;
            $t_totcad   += 1;
          } else {
            $t_tram     += 1;
            $t_tottram  += 1;
          } 
        } else {
          $t_conc=$t_conc+1;
          $t_totconc=$t_totconc+1;
          if (Nvl(f($row,'valor'),0)<Nvl(f($row,'custo_real'),0)) {
            $t_acima    += 1;
            $t_totacima += 1;
          } 
        } 
        $t_solic        += 1;
        $t_valor        += Nvl(f($row,'valor'),0);
        $t_custo        += Nvl(f($row,'custo_real'),0);

        $t_totvalor     += Nvl(f($row,'valor'),0);
        $t_totcusto     += Nvl(f($row,'custo_real'),0);
        $t_totsolic     += 1;
        $w_qt_quebra    += 1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
      if ($p_agrega!='GRCLCIAVIAGEM' && $p_agrega!='GRCLCIDADE') {
        ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
        ShowHTML('          <td><b>Totais</td>');
        ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1,$p_agrega);
      } 
    } 
    ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_graf=='N') {
      // Coloca o gr�fico somente se o usu�rio desejar
        if($w_embed != 'WORD'){
            include_once($w_dir_volta.'funcoes/geragraficoflash.php');
            ShowHTML('<tr><td align="center" height=20>');
            ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'mod_pd/'.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_graf='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
            //ShowHTML('<tr><td align="center" height=20>');
            //barra_flash(array(genero => "M", "nome" =>  f($RS_Menu,'nome'), "total" => $t_totsolic, "cadastramento" => $t_totcad, "execucao" => $t_tottram, "concluidos" => $t_totconc, "atrasados" => $t_totatraso, "aviso" => $t_totaviso, "acima" => $t_totacima), "barra");
            if (($t_totcad+$t_tottram)>0) {
                ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'mod_pd/'.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_graf='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
                //pizza_flash(array(genero => "M", "nome" =>  f($RS_Menu,'nome'), "total" => $t_totsolic, "cadastramento" => $t_totcad, "execucao" => $t_tottram, "concluidos" => $t_totconc, "atrasados" => $t_totatraso, "aviso" => $t_totaviso, "acima" => $t_totacima), "pizza");
            } 
        }
    }    
  } elseif ($O='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    // Exibe par�metros de apresenta��o
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Par�metros de Apresenta��o</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="A" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    //if ($p_agrega=='GRCLCIAVIAGEM') ShowHTML(' <option value="GRCLCIAVIAGEM" selected>Cia. Viagem'); else ShowHTML(' <option value="GRCLCIAVIAGEM">Cia. viagem');
    //if ($p_agrega=='GRCLCIDADE')    ShowHTML(' <option value="GRCLCIDADE" selected>Cidade destino'); else ShowHTML(' <option value="GRCLCIDADE">Cidade destino');
    if ($p_agrega=='GRCLABERTURA')  ShowHTML(' <option value="GRCLABERTURA" selected>M�s de abertura');     else ShowHTML(' <option value="GRCLABERTURA">M�s de abertura');
    if ($p_agrega=='GRCLAUTORIZ')   ShowHTML(' <option value="GRCLAUTORIZ" selected>M�s de autoriza��o');   else ShowHTML(' <option value="GRCLAUTORIZ">M�s de autoriza��o');
    if ($p_agrega=='GRCLMODAL')     ShowHTML(' <option value="GRCLMODAL" selected>Modalidade');             else ShowHTML(' <option value="GRCLMODAL">Modalidade');
    if ($p_agrega=='GRCLPROJ')      ShowHTML(' <option value="GRCLPROJ" selected>Projeto');                 else ShowHTML(' <option value="GRCLPROJ">Projeto');
    if ($p_agrega=='GRCLSITUACAO')  ShowHTML(' <option value="GRCLSITUACAO" selected>Situa��o do certame'); else ShowHTML(' <option value="GRCLSITUACAO">Situa��o do certame');
    if ($p_agrega=='' || $p_agrega=='GRCLUNIDADE')  ShowHTML(' <option value="GRCLUNIDADE" selected>Unidade solicitante'); else ShowHTML(' <option value="GRCLUNIDADE">Unidade solicitante');
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibi��o do gr�fico?</b>',$p_graf,'p_graf');
    MontaRadioSN('<b>Limita tamanho do detalhamento?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Crit�rios de Busca</td>');

    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na rela��o.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    selecaoTipoMatServSubord('<u>T</u>ipo de material/servi�o:','S','Selecione o grupo/subgrupo de material/servi�o desejado.',null,$p_pais,'p_pais','SUBTODOS',null);
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>C</U>�digo '.(($SG=='GRCLLIC') ? ' da licita��o': ' da solicita��o').':<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="p_empenho" size="20" maxlength="60" value="'.$p_empenho.'"></td>');
    if ($SG=='GRCLLIC') ShowHTML('     <td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_regiao" style="text-align:right;" size="7" maxlength="6" value="'.$p_regiao.'">/<INPUT class="STI" type="text" name="p_cidade" size="4" maxlength="4" value="'.$p_cidade.'"></td>');
    //ShowHTML('     <td><b><U>D</U>escri��o:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    //SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel pela PCD na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante',$p_unidade,null,'p_unidade','VIAGEM',null);
    ShowHTML('   <tr valign="top">');
    if ($SG=='GRCLLIC') {
      ShowHTML('     <td><b>N�mero d<u>o</u> certame:<br><INPUT ACCESSKEY="F" TYPE="text" class="sti" NAME="p_palavra" VALUE="'.$p_palavra.'" SIZE="14" MaxLength="14">');
      SelecaoLCModalidade('<u>M</u>odalidade:','M','Selecione na lista a modalidade do certame.',$p_usu_resp,null,'p_usu_resp',null,null);
      ShowHTML('<tr valign="top">');
      SelecaoLCSituacao('<u>S</u>itua��o do certame:','S','Selecione a situa��o do certame.',$p_uf,null,'p_uf',null,null);
      //MontaRadioNS('<b>Apenas decis�o judicial?</b>',$p_ativo,'p_ativo');
    }
    ShowHTML('   <tr valign="top">');
    if ($SG=='GRCLLIC') {
      ShowHTML('     <td>');
      ShowHTML('       <b>A<u>b</u>ertura de propostas entre:</b><br><input '.$w_Disabled.' accesskey="B" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa">');
      ShowHTML('       <br><b>A<u>u</u>toriza��o entre:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa">');
    } else {
      ShowHTML('     <td>&nbsp;</td>');
    }
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('        </td>');
    ShowHTML('    </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 

  ShowHTML('</table>');
  ShowHTML('</center>');
  if($p_tipo == 'PDF')  RodapePdf();
  else                  Rodape();
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
    case 'GRCLCIAVIAGEM':   ShowHTML('          <td><b>Cia. Viagem</td>');          break;
    case 'GRCLCIDADE':      ShowHTML('          <td><b>Cidade destino</td>');       break;
    case 'GRCLUNIDADE':     ShowHTML('          <td><b>Unidade solicitante</td>');  break;
    case 'GRCLPROJ':        ShowHTML('          <td><b>Projeto</td>');              break;
    case 'GRCLABERTURA':    ShowHTML('          <td><b>M�s de abertura</td>');      break;
    case 'GRCLAUTORIZ':     ShowHTML('          <td><b>M�s de autorizacao</td>');   break;
    case 'GRCLMODAL':       ShowHTML('          <td><b>Modalidade</td>');           break;
    case 'GRCLSITUACAO':    ShowHTML('          <td><b>Situa��o do certame</td>');  break;
  } 
  ShowHTML('          <td><b>Total</td>');
  ShowHTML('          <td><b>Cad.</td>');
  ShowHTML('          <td><b>Tram.</td>');
  ShowHTML('          <td><b>Enc.</td>');
  ShowHTML('          <td><b>Atraso</td>');
  ShowHTML('          <td><b>Aviso</td>');
  ShowHTML('          <td><b>$ Prev.</td>');
  ShowHTML('          <td><b>$ Real</td>');
  ShowHTML('          <td><b>Real > Previsto</td>');
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave,$l_agrega) {
  extract($GLOBALS);
  if($p_tipo == 'PDF'){
    $w_embed = 'WORD';  
  }

  if ($w_embed != 'WORD')                  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</td>');                else ShowHTML('          <td align="right">'.number_format($l_solic,0,',','.').'&nbsp;</td>');
  if ($l_cad>0 && $w_embed != 'WORD')      ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_cad,0,',','.').'</a>&nbsp;</td>');                   else ShowHTML('          <td align="right">'.number_format($l_cad,0,',','.').'&nbsp;</td>');
  if ($l_tram>0 && $w_embed != 'WORD')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_tram,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="right">'.number_format($l_tram,0,',','.').'&nbsp;</td>');
  if ($l_conc>0 && $w_embed != 'WORD')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_conc,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="right">'.number_format($l_conc,0,',','.').'&nbsp;</td>');
  if ($l_atraso>0 && $w_embed != 'WORD')   ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.number_format($l_atraso,0,',','.').'</a>&nbsp;</td>'); else ShowHTML('          <td align="right"><b>'.$l_atraso.'&nbsp;</td>');
  if ($l_agrega=='GRCLCIAVIAGEM' || $l_agrega=='GRCLCIDADE' || $l_agrega=='GRCLAUTORIZ') {
    ShowHTML('          <td align="right">---&nbsp;</td>');
    ShowHTML('          <td align="right">---&nbsp;</td>');
    ShowHTML('          <td align="right">---&nbsp;</td>');
    ShowHTML('          <td align="right">---&nbsp;</td>');
  } else {
    ShowHTML('          <td align="right">'.number_format($l_valor,2,',','.').'&nbsp;</td>');
    ShowHTML('          <td align="right">'.number_format($l_custo,2,',','.').'&nbsp;</td>');
    if ($l_aviso>0 && $O=='L') {
      ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_aviso,0,',','.').'&nbsp;</td>');
    } else {
      ShowHTML('          <td align="right"><b>'.$l_aviso.'&nbsp;</td>');
    } 
    if ($l_acima>0) {
      ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_acima,0,',','.').'&nbsp;</td>');
    } else {
      ShowHTML('          <td align="right"><b>'.$l_acima.'&nbsp;</td>');
    } 
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
    break;
  } 
} 
?>