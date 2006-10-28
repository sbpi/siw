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
include_once($w_dir_volta.'classes/sp/db_getCiaTrans.php');
include_once($w_dir_volta.'classes/sp/db_getSolicViagem.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPCD.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
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
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
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
$w_pagina       = 'gr_viagem.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];

if ($O=='') $O='P';

switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem';   break;
  case 'V': $w_TP = $TP . ' - Gráfico';     break;
  default:  $w_TP = $TP . ' - Listagem';    break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;
$w_ano      = RetornaAno();

$p_projeto      = strtoupper($_REQUEST['p_projeto']);
$p_atividade    = strtoupper($_REQUEST['p_atividade']);
$p_tipo         = strtoupper($_REQUEST['p_tipo']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);
$p_unidade      = strtoupper($_REQUEST['p_unidade']);
$p_proponente   = strtoupper($_REQUEST['p_proponente']);
$p_sq_prop      = strtoupper($_REQUEST['p_sq_prop']);
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$p_ini_i        = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f        = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i        = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f        = strtoupper($_REQUEST['p_fim_f']);
$p_atraso       = strtoupper($_REQUEST['p_atraso']);
$p_codigo       = strtoupper($_REQUEST['p_codigo']);
$p_chave        = strtoupper($_REQUEST['p_chave']);
$p_assunto      = strtoupper($_REQUEST['p_assunto']);
$p_pais         = strtoupper($_REQUEST['p_pais']);
$p_regiao       = strtoupper($_REQUEST['p_regiao']);
$p_uf           = strtoupper($_REQUEST['p_uf']);
$p_cidade       = strtoupper($_REQUEST['p_cidade']);
$p_usu_resp     = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra      = strtoupper($_REQUEST['p_palavra']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = strtoupper($_REQUEST['p_sqcc']);
$p_agrega       = strtoupper($_REQUEST['p_agrega']);
$p_tamanho      = strtoupper($_REQUEST['p_tamanho']);

// Recupera a configuração do serviço
$RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Pesquisa gerencial
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);

  if ($O=='L' || $O=='V' || $O=='W') {
    $w_filtro='';
    if ($p_projeto>'') {
      $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_atividade>'') {
      $RS = db_getSolicEtapa::getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
    } 
    if ($p_codigo>'') $w_filtro .= '<tr valign="top"><td align="right">PCD nº <td>[<b>'.$p_codigo.'</b>]';
    if ($p_assunto>'') $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>'.$p_assunto.'</b>]';
    if ($p_solicitante>'') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade proponente <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_proponente>'') $w_filtro .= '<tr valign="top"><td align="right">Proposto<td>[<b>'.$p_proponente.'</b>]';
    if ($p_palavra>'') $w_filtro .= '<tr valign="top"><td align="right">CPF proposto <td>[<b>'.$p_palavra.'</b>]';
    if ($p_sq_prop>'') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_sq_prop,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Proposto<td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_pais>'') {
      $RS = db_getCountryData::getInstanceOf($dbms,$p_pais);
      $w_filtro .= '<tr valign="top"><td align="right">País <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'') {
      $RS = db_getRegionData::getInstanceOf($dbms,$p_regiao);
      $w_filtro .= '<tr valign="top"><td align="right">Região <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uf>'') {
      $RS = db_getStateData::getInstanceOf($dbms,$p_pais,$p_uf);
      $w_filtro .= '<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_cidade>'')  {
      $RS = db_getCityData::getInstanceOf($dbms,$p_cidade);
      $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null,null,null,null,null,null,null);
      foreach($RS as $row) { $RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Companhia de viagem<td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_ativo>'') {
      $w_filtro .= '<tr valign="top"><td align="right">Tipo<td>[<b>';
      if ($p_ativo=='I')        $w_filtro .= 'Inicial';
      elseif ($p_ativo=='P')    $w_filtro .= 'Prorrogação';
      elseif ($p_ativo=='C')    $w_filtro .= 'Complementação';
      $w_filtro .= '</b>]';
    } 
    if ($p_fim_i>'') $w_filtro .= '<tr valign="top"><td align="right">Mês <td>[<b>'.$p_fim_i.'</b>]';
    if ($w_filtro>'') $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';

    switch ($p_agrega) {
      case 'GRPDCIAVIAGEM':
        $RS1 = db_getSolicViagem::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante, $p_unidade,null,$p_ativo,$p_proponente, $p_chave, $p_assunto, 
            $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, $p_acao_ppa, $p_orprior);
        $w_TP .= ' - Por cia de viagem';
        $RS1 = SortArray($RS1,'nm_cia_viagem','asc');
        break;
      case 'GRPDCIDADE':
        $RS1 = db_getSolicViagem::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante, $p_unidade,null,$p_ativo,$p_proponente, $p_chave, $p_assunto, 
            $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, $p_acao_ppa, $p_orprior);
        $w_TP .= ' - Por cidade de destino';
        $RS1 = SortArray($RS1,'nm_destino','asc');
        break;
      case 'GRPDUNIDADE':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null);
        $w_TP .= ' - Por unidade proponente';
        $RS1 = SortArray($RS1,'nm_unidade_resp','asc');
        break;
      case 'GRPDPROJ':
        $RS1 = db_getSolicViagem::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante, $p_unidade,null,$p_ativo,$p_proponente, $p_chave, $p_assunto, 
            $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, $p_acao_ppa, $p_orprior);
        $w_TP .= ' - Por projeto';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
        break;
      case 'GRPDDATA':
        $RS1 = db_getSolicViagem::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante, $p_unidade,null,$p_ativo,$p_proponente, $p_chave, $p_assunto, 
            $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, $p_acao_ppa, $p_orprior);
        $w_TP .= ' - Por mês';
        $RS1 = SortArray($RS1,'nm_mes','desc');
        break;
      case 'GRPDPROPOSTO':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null);
        $w_TP .= ' - Por proposto';
        $RS1 = SortArray($RS1,'nm_prop','asc');
        break;
      case 'GRPDTIPO':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null);
        $w_TP .= ' - Por tipo';
        $RS1 = SortArray($RS1,'tp_missao','asc');
        break;
    } 
  } 

  if ($O=='W') {
    HeaderWord(null);
    $w_pag=1;
    $w_linha=0;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    if ($O=='P') {
      ScriptOpen('Javascript');
      Modulo();
      FormataCPF();
      CheckBranco();
      FormataData();
      ValidateOpen('Validacao');
      Validate('p_codigo','Número da PCD','','','2','60','1','1');
      Validate('p_assunto','Assunto','','','2','90','1','1');
      Validate('p_proponente','Proposto','','','2','60','1','');
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
      BodyOpenClean('onLoad=document.focus();');
    } 

    if ($O=='L') {
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
  if ($O=='L' || $O=='W') {
    if ($O=='L') {
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
      if ($O=='L') {
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
      $RST = array_slice($RS1, (($P3-1)*$P4), $P4);
      foreach($RST as $row) {
        switch ($p_agrega) { 
          case 'GRPDCIAVIAGEM':
            if ($w_nm_quebra!=f($row,'nm_cia_viagem')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
                $w_linha += 2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
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
                $w_linha += 2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
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
                $w_linha += 2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
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
                $w_linha += 2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
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
                $w_linha += 2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
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
                $w_linha += 2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
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
                $w_linha += 2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
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
        if ($O=='W' && $w_linha>25) {
          // Se for geração de MS-Word, quebra a página
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha   = 0;
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
          if (f($row,'fim') < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          if ($cDbl[f($row,'or_tramite')]==1) {
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
    if (count($RS1)>0 && $p_tipo=='N') {
      // Coloca o gráfico somente se o usuário desejar
      ShowHTML('<tr><td align="center" height=20>');
      ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'mod_pd/'.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      ShowHTML('<tr><td align="center" height=20>');
      if (($t_totcad+$t_tottram)>0) {
        ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'mod_pd/'.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      } 
    } 
  } elseif ($O='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="A" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if ($p_agrega=='GRPDCIAVIAGEM') ShowHTML(' <option value="GRPDCIAVIAGEM" selected>Cia. Viagem'); else ShowHTML(' <option value="GRPDCIAVIAGEM">Cia. viagem');
    if ($p_agrega=='GRPDCIDADE')    ShowHTML(' <option value="GRPDCIDADE" selected>Cidade destino'); else ShowHTML(' <option value="GRPDCIDADE">Cidade destino');
    if ($p_agrega=='' || $p_agrega=='GRPDUNIDADE')  ShowHTML(' <option value="GRPDUNIDADE" selected>Unidade proponente'); else ShowHTML(' <option value="GRPDUNIDADE">Unidade proponente');
    if ($p_agrega=='GRPDPROJ')      ShowHTML(' <option value="GRPDPROJ" selected>Projeto');          else ShowHTML(' <option value="GRPDPROJ">Projeto');
    if ($p_agrega=='GRPDDATA')      ShowHTML(' <option value="GRPDDATA" selected>Mês');              else ShowHTML(' <option value="GRPDDATA">Mês');
    if ($p_agrega=='GRPDPROPOSTO')  ShowHTML(' <option value="GRPDPROPOSTO" selected>Proposto');     else ShowHTML(' <option value="GRPDPROPOSTO">Proposto');
    if ($p_agrega=='GRPDTIPO')      ShowHTML(' <option value="GRPDTIPO" selected>Tipo');             else ShowHTML(' <option value="GRPDTIPO">Tipo');
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibição do gráfico?</b>',$p_tipo,'p_tipo');
    MontaRadioSN('<b>Limita tamanho do detalhamento?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');

    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),'p_projeto','PJLIST','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_atividade\'; document.Form.submit();"');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoEtapa('Eta<u>p</u>a:','P','Se necessário, indique a etapa à qual esta atividade deve ser vinculada.',$p_atividade,$p_projeto,null,'p_atividade',null,null);
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td valign="top"><b>Número da P<U>C</U>D:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="p_codigo" size="20" maxlength="60" value="'.$p_codigo.'"></td>');
    ShowHTML('     <td valign="top"><b><U>D</U>escrição:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
    ShowHTML('   <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela PCD na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('<U>U</U>nidade proponente:','U','Selecione a unidade proponente da PCD',$p_unidade,null,'p_unidade','VIAGEM',null);
    ShowHTML('   <tr>');
    ShowHTML('     <td valign="top"><b><U>P</U>roposto:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="'.$p_proponente.'"></td>');
    ShowHTML('     <td valign="top"><b>CP<u>F</u> do proposto:<br><INPUT ACCESSKEY="F" TYPE="text" class="sti" NAME="p_palavra" VALUE="'.$p_palavra.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    ShowHTML('   <tr>');
    SelecaoPais('Pa<u>í</u>s destino:','I',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
    SelecaoRegiao('<u>R</u>egião destino:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    ShowHTML('   <tr>');
    SelecaoEstado('E<u>s</u>tado destino:','S',null,$p_uf,$p_pais,'N','p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade destino:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
    ShowHTML('   <tr>');
    SelecaoTipoPCD('Ti<u>p</u>o:','P',null,$p_ativo,'p_ativo',null,null);
    SelecaoCiaTrans('Cia. Via<u>g</u>em','R','Selecione a companhia de transporte desejada.',$w_cliente,$p_usu_resp,null,'p_usu_resp','S',null);
    ShowHTML('   <tr>');
    ShowHTML('     <td valign="top"><b>Pri<u>m</u>eira saída e Último retorno:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" title="Usar formato dd/mm/aaaa"></td>');
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
    case 'GRPDPROPOSTO':    ShowHTML('          <td><b>Proposto</td>');           break;
    case 'GRPDTIPO':        ShowHTML('          <td><b>Tipo</td>');               break;
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

  if ($O=='L')                  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe as pcds.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</td>');                else ShowHTML('          <td align="right">'.number_format($l_solic,0,',','.').'&nbsp;</td>');
  if ($l_cad>0 && $O=='L')      ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe as pcds.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_cad,0,',','.').'</a>&nbsp;</td>');                   else ShowHTML('          <td align="right">'.number_format($l_cad,0,',','.').'&nbsp;</td>');
  if ($l_tram>0 && $O=='L')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe as pcds.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_tram,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="right">'.number_format($l_tram,0,',','.').'&nbsp;</td>');
  if ($l_conc>0 && $O=='L')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe as pcds.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_conc,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="right">'.number_format($l_conc,0,',','.').'&nbsp;</td>');
  if ($l_atraso>0 && $O=='L')   ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe as pcds.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.number_format($l_atraso,0,',','.').'</a>&nbsp;</td>'); else ShowHTML('          <td align="right"><b>'.$l_atraso.'&nbsp;</td>');
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
    BodyOpen('onLoad=document.focus();');
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
