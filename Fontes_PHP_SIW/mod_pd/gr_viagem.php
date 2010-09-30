<?php
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
include_once($w_dir_volta.'classes/sp/db_getCiaTrans.php');
include_once($w_dir_volta.'classes/sp/db_getSolicViagem.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPCD.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoCiaTrans.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');

// =========================================================================
//  gr_viagem.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o módulo de passagens e diárias
// Mail     : celso@sbpi.com.br
// Criação  : 26/05/2006 10:00
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
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'gr_viagem.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];

if ($O=='') $O='P';

switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem';   break;
  case 'V': $w_TP = $TP . ' - Gráfico';     break;
  default:  $w_TP = 'Listagem';       break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;
$w_ano      = RetornaAno();

$p_tipo         = upper($_REQUEST['w_tipo']);
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_atividade    = upper($_REQUEST['p_atividade']);
$p_graf         = upper($_REQUEST['p_graf']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_sq_prop      = upper($_REQUEST['p_sq_prop']);
$p_ordena       = lower($_REQUEST['p_ordena']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_codigo       = upper($_REQUEST['p_codigo']);
$p_chave        = upper($_REQUEST['p_chave']);
$p_assunto      = upper($_REQUEST['p_assunto']);
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
$p_agrega       = upper($_REQUEST['p_agrega']);
$p_tamanho      = upper($_REQUEST['p_tamanho']);

// Recupera a configuração do serviço
$RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,$w_menu);

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
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_codigo>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código da viagem <td>[<b>'.$p_codigo.'</b>]'; }
    if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade proponente <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Beneficiário<td>[<b>'.$p_proponente.'</b>]'; }
    if ($p_palavra>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">CPF Beneficiário <td>[<b>'.$p_palavra.'</b>]'; }
    if ($p_sq_prop>'') {
      $w_linha++;
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_sq_prop,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Beneficiário<td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_pais>'') {
      $w_linha++;
      $sql = new db_getCountryData; $RS = $sql->getInstanceOf($dbms,$p_pais);
      $w_filtro .= '<tr valign="top"><td align="right">País <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'') {
      $w_linha++;
      $sql = new db_getRegionData; $RS = $sql->getInstanceOf($dbms,$p_regiao);
      $w_filtro .= '<tr valign="top"><td align="right">Região <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uf>'') {
      $w_linha++;
      $sql = new db_getStateData; $RS = $sql->getInstanceOf($dbms,$p_pais,$p_uf);
      $w_filtro .= '<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_cidade>'')  {
      $w_linha++;
      $sql = new db_getCityData; $RS = $sql->getInstanceOf($dbms,$p_cidade);
      $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $w_linha++;
      $sql = new db_getCiaTrans; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null,null,null,null,null,null,null,null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Companhia de viagem<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Mês <td>[<b>'.$p_fim_i.'</b>]'; }
    if ($p_ativo=='S')   $w_filtro .= '<tr valign="top"><td align="right">Conformidade <td>[<b>Somente solicitações fora do prazo</b>]';
    if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situação <td>[<b>Somente pendente de prestação de contas</b>]';
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }

    switch ($p_agrega) {
      case 'GRPDCIAVIAGEM':
        $sql = new db_getSolicViagem; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante, $p_unidade,null,$p_ativo,$p_proponente, $p_chave, $p_assunto, 
            $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, $p_codigo, $p_orprior);
        $w_TP .= ' por cia de viagem';
        $RS1 = SortArray($RS1,'nm_cia_viagem','asc');
        break;
      case 'GRPDCIDADE':
        $sql = new db_getSolicViagem; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante, $p_unidade,null,$p_ativo,$p_proponente, $p_chave, $p_assunto, 
            $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, $p_codigo, $p_orprior);
        $w_TP .= ' por cidade de destino';
        $RS1 = SortArray($RS1,'nm_destino_ind','asc');
        break;
      case 'GRPDUNIDADE':
        $sql = new db_getSolicList; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_codigo, null);
        $w_TP .= ' por unidade proponente';
        $RS1 = SortArray($RS1,'nm_unidade_resp','asc');
        break;
      case 'GRPDPROJ':
        $sql = new db_getSolicViagem; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante, $p_unidade,null,$p_ativo,$p_proponente, $p_chave, $p_assunto, 
            $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, $p_codigo, $p_orprior);
        $w_TP .= ' por projeto';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
        break;
      case 'GRPDDATA':
        $sql = new db_getSolicViagem; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante, $p_unidade,null,$p_ativo,$p_proponente, $p_chave, $p_assunto, 
            $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, $p_codigo, $p_orprior);
        $w_TP .= ' por mês';
        $RS1 = SortArray($RS1,'nm_mes','desc');
        break;
      case 'GRPDPROPOSTO':
        $sql = new db_getSolicList; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_codigo, null);
        $w_TP .= ' por beneficiário';
        $RS1 = SortArray($RS1,'nm_prop_ind','asc');
        break;
      case 'GRPDTIPO':
        $sql = new db_getSolicList; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_codigo, null);
        $w_TP .= ' por tipo';
        $RS1 = SortArray($RS1,'tp_missao','asc');
        break;
    } 
  } 

  $w_linha_filtro = $w_linha;
  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 40: 25);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 25: 25);
    $w_embed = 'WORD';
    HeaderPdf($w_TP,$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      Modulo();
      FormataCPF();
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_codigo','Código da viagem','','','2','60','1','1');
      Validate('p_assunto','Assunto','','','2','90','1','1');
      Validate('p_proponente','Beneficiário','','','2','60','1','');
      Validate('p_palavra','CPF','CPF','','14','14','','0123456789-.');
      Validate('p_ini_i','Primeira saída','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','Último retorno','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_ini_i','Primeira saída','<=','p_ini_f','Último retorno');
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
      if ($P1==1) { // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      } 
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
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
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L' && $w_embed != 'WORD') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRPDCIAVIAGEM': ShowHTML('     document.Form.p_usu_resp.value=filtro;'); break;
          case 'GRPDCIDADE':    ShowHTML('     document.Form.p_cidade.value=filtro;');   break;
          case 'GRPDUNIDADE':   ShowHTML('     document.Form.p_unidade.value=filtro;');  break;
          case 'GRPDPROJ':      ShowHTML('     document.Form.p_projeto.value=filtro;');  break;
          case 'GRPDDATA':      ShowHTML('     document.Form.p_fim_i.value=filtro;');    break;
          case 'GRPDPROPOSTO':  ShowHTML('     document.Form.p_sq_prop.value=filtro;');  break;
          case 'GRPDTIPO':      ShowHTML('     document.Form.p_ativo.value=filtro;');    break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRPDCIAVIAGEM': ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';'); break;
          case 'GRPDCIDADE':    ShowHTML('    else document.Form.p_cidade.value="'.$_REQUEST['p_cidade'].'";');     break;
          case 'GRPDUNIDADE':   ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');   break;
          case 'GRPDPROJ':      ShowHTML('    else document.Form.p_projeto.value=\''.$_REQUEST['p_projeto'].'\';');   break;
          case 'GRPDDATA':      ShowHTML('    else document.Form.p_fim_i.value=\''.$_REQUEST['p_fim_i'].'\';');       break;
          case 'GRPDPROPOSTO':  ShowHTML('    else document.Form.p_sq_prop.value=\''.$_REQUEST['p_sq_prop'].'\';');   break;
          case 'GRPDTIPO':      ShowHTML('    else document.Form.p_ativo.value=\''.$_REQUEST['p_ativo'].'\';');       break;
        } 
        $sql = new db_getTramiteList; $RS2 = $sql->getInstanceOf($dbms,$P2,null,null,null);
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
        $RS2 = new db_getMenuData; $RS2 = $RS2->getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Lista',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_dir.$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        if ($_REQUEST['p_atraso']=='') ShowHTML('<input type="Hidden" name="p_atraso" value="N">');
        switch ($p_agrega) {
          case 'GRPDCIAVIAGEM': if ($_REQUEST['p_usu_resp']=='') ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');  break;
          case 'GRPDCIDADE':    if ($_REQUEST['p_cidade']=='')   ShowHTML('<input type="Hidden" name="p_cidade" value="">');    break;
          case 'GRPDUNIDADE':   if ($_REQUEST['p_unidade']=='')  ShowHTML('<input type="Hidden" name="p_unidade" value="">');   break;
          case 'GRPDPROJ':      if ($_REQUEST['p_projeto']=='')  ShowHTML('<input type="Hidden" name="p_projeto" value="">');   break;
          case 'GRPDDATA':      if ($_REQUEST['p_fim_i']=='')    ShowHTML('<input type="Hidden" name="p_fim_i" value="">');     break;
          case 'GRPDPROPOSTO':  if ($_REQUEST['p_sq_prop']=='')  ShowHTML('<input type="Hidden" name="p_sq_prop" value="">');   break;
          case 'GRPDTIPO':      if ($_REQUEST['p_ativo']=='')    ShowHTML('<input type="Hidden" name="p_ativo" value="">');     break;
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
          case 'GRPDCIAVIAGEM':
            if ($w_nm_quebra!=f($row,'nm_cia_viagem')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && ($w_linha+1)<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
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
          case 'GRPDDATA':
            if ($w_nm_quebra!=f($row,'nm_mes')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && ($w_linha+1)<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_mes'));
              } 
              $w_nm_quebra  = f($row,'nm_mes');
              $w_chave      = FormataDataEdicao(f($row,'cd_mes'));
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
          case 'GRPDCIDADE':
            if ($w_nm_quebra!=f($row,'nm_destino')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && ($w_linha+1)<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
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
          case 'GRPDUNIDADE':
            if ($w_nm_quebra!=f($row,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && ($w_linha+1)<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));
              } 
              $w_nm_quebra  = f($row,'nm_unidade_resp');
              $w_chave      = f($row,'sq_unidade_resp');
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
          case 'GRPDPROJ':
            if ($w_nm_quebra!=f($row,'nm_projeto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && ($w_linha+1)<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));
              } 
              $w_nm_quebra  = f($row,'nm_projeto');
              $w_chave      = f($row,'sq_projeto');
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
          case 'GRPDPROPOSTO':
            if (Nvl($w_nm_quebra,'')!=f($row,'nm_prop')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && ($w_linha+1)<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_prop'));
              } 
              $w_nm_quebra  = f($row,'nm_prop');
              $w_chave      = f($row,'sq_prop');
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
          case 'GRPDTIPO':
            if ($w_nm_quebra!=f($row,'nm_tp_missao')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
              } 
              if ($w_embed != 'WORD' || ($w_embed == 'WORD' && ($w_linha+1)<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_tp_missao'));
              } 
              $w_nm_quebra  = f($row,'nm_tp_missao');
              $w_chave      = f($row,'tp_missao');
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
          // Se for geração de MS-Word, quebra a página
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
            case 'GRPDCIAVIAGEM':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_cia_transporte')); break;
            case 'GRPDCIDADE':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_destino'));        break;
            case 'GRPDUNIDADE':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));   break;
            case 'GRPDPROJ':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));        break;
            case 'GRPDDATA':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_mes'));            break;
            case 'GRPDPROPOSTO':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_prop'));           break;
            case 'GRPDTIPO':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'tp_missao'));         break;
          } 
          $w_linha += 1;
        } 
        if (f($row,'concluida')=='N') {
          if (f($row,'atraso_pc')=='S') {
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
          $t_conc    += 1;
          $t_totconc += 1;
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
      if ($p_agrega!='GRPDCIAVIAGEM' && $p_agrega!='GRPDCIDADE' && $p_agrega!='GRPDDATA') {
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
      include_once($w_dir_volta.'funcoes/geragraficogoogle.php');
      if($p_tipo == 'PDF') $w_embed = 'WORD';
      $w_legenda = array('Prest. contas pendente','Cadastramento','Tramitando','Encerradas','Total');
      ShowHTML('<tr><td align="center"><br>');
      if ($p_tipo=='PDF') ShowHTML('    <pd4ml:page.break>');
      else                ShowHTML('    <br style="page-break-after:always">');
      ShowHTML(geraGraficoGoogle(f($RS_Menu,'nome').' - Resumo',$SG,'bar',
                                 array($t_totsolic,$t_totconc,$t_tottram,$t_totcad,$t_totatraso),
                                 $w_legenda
                                )
              );
      ShowHTML('<tr><td align="center"><br>');
      //if ($p_tipo=='PDF') ShowHTML('    <pd4ml:page.break>');
      //else                ShowHTML('    <br style="page-break-after:always">');
      ShowHTML(geraGraficoGoogle(f($RS_Menu,'nome').' em andamento',$SG,'pie',
                                 array(($t_tottram+$t_totcad-$t_totatraso-$t_totaviso),$t_totatraso),
                                 array('Normal','Prest. contas pendente')
                                )
              );
    }    
  } elseif ($O='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="A" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if ($p_agrega=='GRPDPROPOSTO')  ShowHTML(' <option value="GRPDPROPOSTO" selected>Beneficiário'); else ShowHTML(' <option value="GRPDPROPOSTO">Beneficiário');
    if ($p_agrega=='GRPDCIAVIAGEM') ShowHTML(' <option value="GRPDCIAVIAGEM" selected>Cia. Viagem'); else ShowHTML(' <option value="GRPDCIAVIAGEM">Cia. viagem');
    if ($p_agrega=='GRPDCIDADE')    ShowHTML(' <option value="GRPDCIDADE" selected>Cidade destino'); else ShowHTML(' <option value="GRPDCIDADE">Cidade destino');
    if ($p_agrega=='GRPDUNIDADE')  ShowHTML(' <option value="GRPDUNIDADE" selected>Unidade proponente'); else ShowHTML(' <option value="GRPDUNIDADE">Unidade proponente');
    if ($p_agrega=='' || $p_agrega=='GRPDPROJ')      ShowHTML(' <option value="GRPDPROJ" selected>Projeto');          else ShowHTML(' <option value="GRPDPROJ">Projeto');
    if ($p_agrega=='GRPDDATA')      ShowHTML(' <option value="GRPDDATA" selected>Mês');              else ShowHTML(' <option value="GRPDDATA">Mês');
    if ($p_agrega=='GRPDTIPO')      ShowHTML(' <option value="GRPDTIPO" selected>Tipo');             else ShowHTML(' <option value="GRPDTIPO">Tipo');
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibição do gráfico?</b>',$p_graf,'p_graf');
    MontaRadioSN('<b>Limita tamanho do detalhamento?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');

    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr valign="top">');
    $RS = new db_getLinkData; $RS = $RS->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoSolic('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na relação.',$w_cliente,$p_projeto,f($RS,'sq_menu'),f($RS_Menu,'sq_menu'),'p_projeto',f($RS,'sigla'),null,null,'<BR />',2);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>C</U>ódigo da viagem:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="p_codigo" size="20" maxlength="60" value="'.$p_codigo.'"></td>');
    ShowHTML('     <td><b><U>D</U>escrição:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
    ShowHTML('   <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela viagem na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('<U>U</U>nidade proponente:','U','Selecione a unidade proponente da viagem',$p_unidade,null,'p_unidade','VIAGEM',null);
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>B</U>eneficiário:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    ShowHTML('     <td><b>CP<u>F</u> do beneficiário:<br><INPUT ACCESSKEY="F" TYPE="text" class="sti" NAME="p_palavra" VALUE="'.$p_palavra.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    ShowHTML('   <tr valign="top">');
    SelecaoPais('Pa<u>í</u>s destino:','I',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
    SelecaoRegiao('<u>R</u>egião destino:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    ShowHTML('   <tr valign="top">');
    SelecaoEstado('E<u>s</u>tado destino:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade destino:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
    ShowHTML('   <tr valign="top">');
    SelecaoCiaTrans('Cia. Via<u>g</u>em','R','Selecione a companhia de transporte desejada.',$w_cliente,$p_usu_resp,null,'p_usu_resp','S',null);
    ShowHTML('     <td><b>Pri<u>m</u>eira saída e Último retorno:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b>Somente pendentes de prestação de contas?</b><br>');
    if ($p_atraso=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
    } 
    ShowHTML('         <br><b>Somente fora do prazo?</b><br>');
    if ($p_ativo=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_ativo" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_ativo" value="N"> Não');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_ativo" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_ativo" value="N" checked> Não');
    } 
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
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
  ShowHTML('</center>');
  if($p_tipo == 'PDF'){

    RodapePdf();
  }
  Rodape();
}

// =========================================================================
// Rotina de filtragem para a conciliação eletrônica
// -------------------------------------------------------------------------

function Conciliacao(){

  extract($GLOBALS);

  $w_pag   = 1;
  $w_linha = 0;

  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_projeto>'') {
      $w_linha++;
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    }
    if ($p_atividade>'') {
      $w_linha++;
      $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    }
    if ($p_fatura>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código da fatura <td>[<b>'.$p_fatura.'</b>]'; }
    //if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]'; }
    
    if ($p_cidade>'')  {
      $w_linha++;
      $sql = new db_getCityData; $RS = $sql->getInstanceOf($dbms,$p_cidade);
      $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
    }
    if ($p_usu_resp>'') {
      $w_linha++;
      $sql = new db_getCiaTrans; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null,null,null,null,null,null,null,null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Companhia de viagem<td>[<b>'.f($RS,'nome').'</b>]';
    }
    if ($p_fim_i>'')  { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Mês <td>[<b>'.$p_fim_i.'</b>]'; }
    if ($p_ativo=='S')   $w_filtro .= '<tr valign="top"><td align="right">Conformidade <td>[<b>Somente solicitações fora do prazo</b>]';
    if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situação <td>[<b>Somente pendente de prestação de contas</b>]';
    if ($w_filtro>'') { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }
  }

  $w_linha_filtro = $w_linha;
  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 40: 25);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 25: 25);
    $w_embed = 'WORD';
    HeaderPdf($w_TP,$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    if ($O=='P') {
      ScriptOpen('Javascript');
      Modulo();
      FormataCPF();
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_codigo','Código da viagem','','','2','60','1','1');
      Validate('p_assunto','Assunto','','','2','90','1','1');
      Validate('p_proponente','Beneficiário','','','2','60','1','');
      Validate('p_palavra','CPF','CPF','','14','14','','0123456789-.');
      Validate('p_ini_i','Primeira saída','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','Último retorno','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_ini_i','Primeira saída','<=','p_ini_f','Último retorno');
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
      if ($P1==1) { // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      }
    } else {
      BodyOpenClean('onLoad=this.focus();');
    }

    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
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
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L' && $w_embed != 'WORD') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRPDCIAVIAGEM': ShowHTML('     document.Form.p_usu_resp.value=filtro;'); break;
          case 'GRPDCIDADE':    ShowHTML('     document.Form.p_cidade.value=filtro;');   break;
          case 'GRPDUNIDADE':   ShowHTML('     document.Form.p_unidade.value=filtro;');  break;
          case 'GRPDPROJ':      ShowHTML('     document.Form.p_projeto.value=filtro;');  break;
          case 'GRPDDATA':      ShowHTML('     document.Form.p_fim_i.value=filtro;');    break;
          case 'GRPDPROPOSTO':  ShowHTML('     document.Form.p_sq_prop.value=filtro;');  break;
          case 'GRPDTIPO':      ShowHTML('     document.Form.p_ativo.value=filtro;');    break;
        }
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRPDCIAVIAGEM': ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';'); break;
          case 'GRPDCIDADE':    ShowHTML('    else document.Form.p_cidade.value="'.$_REQUEST['p_cidade'].'";');     break;
          case 'GRPDUNIDADE':   ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');   break;
          case 'GRPDPROJ':      ShowHTML('    else document.Form.p_projeto.value=\''.$_REQUEST['p_projeto'].'\';');   break;
          case 'GRPDDATA':      ShowHTML('    else document.Form.p_fim_i.value=\''.$_REQUEST['p_fim_i'].'\';');       break;
          case 'GRPDPROPOSTO':  ShowHTML('    else document.Form.p_sq_prop.value=\''.$_REQUEST['p_sq_prop'].'\';');   break;
          case 'GRPDTIPO':      ShowHTML('    else document.Form.p_ativo.value=\''.$_REQUEST['p_ativo'].'\';');       break;
        }
        $sql = new db_getTramiteList; $RS2 = $sql->getInstanceOf($dbms,$P2,null,null,null);
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
        $RS2 = new db_getMenuData; $RS2 = $RS2->getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Lista',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_dir.$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        if ($_REQUEST['p_atraso']=='') ShowHTML('<input type="Hidden" name="p_atraso" value="N">');
        switch ($p_agrega) {
          case 'GRPDCIAVIAGEM': if ($_REQUEST['p_usu_resp']=='') ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');  break;
          case 'GRPDCIDADE':    if ($_REQUEST['p_cidade']=='')   ShowHTML('<input type="Hidden" name="p_cidade" value="">');    break;
          case 'GRPDUNIDADE':   if ($_REQUEST['p_unidade']=='')  ShowHTML('<input type="Hidden" name="p_unidade" value="">');   break;
          case 'GRPDPROJ':      if ($_REQUEST['p_projeto']=='')  ShowHTML('<input type="Hidden" name="p_projeto" value="">');   break;
          case 'GRPDDATA':      if ($_REQUEST['p_fim_i']=='')    ShowHTML('<input type="Hidden" name="p_fim_i" value="">');     break;
          case 'GRPDPROPOSTO':  if ($_REQUEST['p_sq_prop']=='')  ShowHTML('<input type="Hidden" name="p_sq_prop" value="">');   break;
          case 'GRPDTIPO':      if ($_REQUEST['p_ativo']=='')    ShowHTML('<input type="Hidden" name="p_ativo" value="">');     break;
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
          $w_pag    += 1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          $w_linha += 1;
        }
        $t_solic        += 1;
        $t_valor        += Nvl(f($row,'valor'),0);
        $t_custo        += Nvl(f($row,'custo_real'),0);

        $t_totvalor     += Nvl(f($row,'valor'),0);
        $t_totcusto     += Nvl(f($row,'custo_real'),0);
        $t_totsolic     += 1;
        $w_qt_quebra    += 1;
      }
    }
    ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');

    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr valign="top">');
    //$RS = new db_getLinkData; $RS = $RS->getInstanceOf($dbms,$w_cliente,'PJCAD');
    //SelecaoSolic('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na relação.',$w_cliente,$p_projeto,f($RS,'sq_menu'),f($RS_Menu,'sq_menu'),'p_projeto',f($RS,'sigla'),null,null,'<BR />',2);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    /*ShowHTML('   <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela viagem na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('<U>U</U>nidade proponente:','U','Selecione a unidade proponente da viagem',$p_unidade,null,'p_unidade','VIAGEM',null);
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b><U>B</U>eneficiário:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    ShowHTML('     <td><b>CP<u>F</u> do beneficiário:<br><INPUT ACCESSKEY="F" TYPE="text" class="sti" NAME="p_palavra" VALUE="'.$p_palavra.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    */
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b>Número da fatura</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b>Data de emissão da fatura</b><br><input '.$w_Disabled.' type="text" name="p_emissao" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('     <td><b>Data de vencimento da fatura</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_vencimento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_vencimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td><b>Início do decêndio</b><br><input '.$w_Disabled.' type="text" name="p_ini_dec" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('     <td><b>Término do decêndio</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_fim_dec" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_vencimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
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
  ShowHTML('</center>');
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
    case 'GRPDCIAVIAGEM':   ShowHTML('          <td><b>Cia. Viagem</td>');        break;
    case 'GRPDCIDADE':      ShowHTML('          <td><b>Cidade destino</td>');     break;
    case 'GRPDUNIDADE':     ShowHTML('          <td><b>Unidade proponente</td>'); break;
    case 'GRPDPROJ':        ShowHTML('          <td><b>Projeto</td>');            break;
    case 'GRPDDATA':        ShowHTML('          <td><b>Mês</td>');                break;
    case 'GRPDPROPOSTO':    ShowHTML('          <td><b>Beneficiário</td>');           break;
    case 'GRPDTIPO':        ShowHTML('          <td><b>Tipo</td>');               break;
  } 
  ShowHTML('          <td><b>Total</td>');
  ShowHTML('          <td><b>Cadastramento</td>');
  ShowHTML('          <td><b>Tramitando</td>');
  ShowHTML('          <td><b>Encerrada</td>');
  ShowHTML('          <td><b>Prestação de Contas Pendente</td>');
  /*
  ShowHTML('          <td><b>Aviso</td>');
  ShowHTML('          <td><b>$ Prev.</td>');
  ShowHTML('          <td><b>$ Real</td>');
  ShowHTML('          <td><b>Real > Previsto</td>');
  */
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

  if ($w_embed != 'WORD')                  ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe as viagens.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</td>');                else ShowHTML('          <td align="center">'.number_format($l_solic,0,',','.').'&nbsp;</td>');
  if ($l_cad>0 && $w_embed != 'WORD')      ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe as viagens.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_cad,0,',','.').'</a>&nbsp;</td>');                   else ShowHTML('          <td align="center">'.number_format($l_cad,0,',','.').'&nbsp;</td>');
  if ($l_tram>0 && $w_embed != 'WORD')     ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe as viagens.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_tram,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="center">'.number_format($l_tram,0,',','.').'&nbsp;</td>');
  if ($l_conc>0 && $w_embed != 'WORD')     ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe as viagens.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_conc,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="center">'.number_format($l_conc,0,',','.').'&nbsp;</td>');
  if ($l_atraso>0 && $w_embed != 'WORD')   ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe as viagens.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.number_format($l_atraso,0,',','.').'</a>&nbsp;</td>'); else ShowHTML('          <td align="center"><b>'.$l_atraso.'&nbsp;</td>');
  /*
  if ($l_agrega=='GRPDCIAVIAGEM' || $l_agrega=='GRPDCIDADE' || $l_agrega=='GRPDDATA') {
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
  */
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'GERENCIAL': Gerencial(); break;
  case 'CONCILIACAO': Conciliacao(); break;
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
    break;
  } 
} 
?>
