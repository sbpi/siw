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
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteResp.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteSolic.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicPA.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicObjetivo.php');
include_once($w_dir_volta.'classes/sp/db_getMatServ.php');
include_once($w_dir_volta.'classes/sp/db_getPAEmpItem.php');
include_once($w_dir_volta.'classes/sp/db_getCLFinanceiro.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putPAEmpGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicConc.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putPAEmpItem.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDespacho.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoPlanoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoObjetivoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoCaixa.php');
include_once('visualemprestimo.php');
include_once('validaemprestimo.php');

// =========================================================================
//  /emprestimo.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o sevi�o de pedido de compra
// Mail     : celso@sbpi.com.br
// Criacao  : 24/08/2007, 11:00
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = C   : Cancelamento
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio

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
$w_pagina       = 'emprestimo.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pa/';
$w_troca        = $_REQUEST['w_troca'];
if (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

$w_tipo         = $_REQUEST['w_tipo'];
$w_copia        = $_REQUEST['w_copia'];
$p_projeto      = strtoupper($_REQUEST['p_projeto']);
$p_atividade    = strtoupper($_REQUEST['p_atividade']);
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
$p_empenho      = strtoupper($_REQUEST['p_empenho']);
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
$p_sq_orprior   = strtoupper($_REQUEST['p_sq_orprior']);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
} 

// Recupera a configura��o do servi�o
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}

// Se for sub-menu, pega a configura��o do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

// Recupera os par�metros de funcionamento do m�dulo de compras
$RS_Parametro = db_getParametro::getInstanceOf($dbms,$w_cliente,'CL',null);
foreach($RS_Parametro as $row){$RS_Parametro=$row; break;}
$w_pede_valor_pedido       = f($RS_Parametro,'pede_valor_pedido');

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de listagem dos pedidos
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;

  if ($O=='L') {
    if ((strpos(strtoupper($R),'GR_')!==false) || ($w_tipo=='WORD')) {
      $w_filtro='';
      if ($p_uf>'') {
        $w_linha++;
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Busca por <td>[<b>'.(($p_uf=='S') ? 'Processos' : 'Documentos').'</b>]';
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
        $RS = db_getSolicEtapa::getInstanceOf($dbms,$p_chave_pai,$p_atividade,'REGISTRO',null);
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
      } 
      if ($p_sqcc>'') {
        $w_linha++;
        $RS = db_getCCData::getInstanceOf($dbms,$p_sqcc);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Classifica��o <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') { $w_linha++; $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Demanda n� <td>[<b>'.$p_chave.'</b>]'; }
      if ($p_prazo>'') { $w_linha++; $w_filtro = $w_filtro.' <tr valign="top"><td align="right">Prazo para conclus�o at�<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]'; }
      if ($p_usu_resp>'') {
        $w_linha++;
        $RS = db_getEspecieDocumento_PA::getInstanceOf($dbms,$p_usu_resp,$w_cliente,null,null,null,null);
        foreach ($RS as $row) {$RS = $row; break;}
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Esp�cie documental <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_empenho>'') {
        $w_filtro.='<tr valign="top"><td align="right">N� documento original <td>[<b>'.$p_empenho.'</b>]';
      } 
      if ($p_unidade>'') {
        $w_linha++;
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Origem interna <td>[<b>'.f($RS,'nome').'</b>]';
      } 
        if ($p_solicitante>'') {
          $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
          $w_filtro .= '<tr valign="top"><td align="right">Solicitante <td>[<b>'.f($RS,'nome_resumido').'</b>]';
        } 
      if ($p_unidade>''){
        $w_linha++;
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Unidade solicitante<td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_pais>'' || $p_regiao>'' || $p_cidade>'') {
        $w_linha++;
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_pais>'') ? $p_pais : '*').'.'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
      } 
      if ($p_prioridade>''){
        $w_linha++;
        $RS = db_getTipoDespacho_PA::getInstanceOf($dbms,$p_prioridade,$w_cliente,null,null,null,null);
        foreach ($RS as $row) {$RS = $row; break;}
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">�ltimo despacho<td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_proponente>'') { $w_linha++; $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Origem externa <td>[<b>'.$p_proponente.'</b>]'; }
      if ($p_assunto>'')    { $w_linha++; $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]'; }
      if ($p_processo>'')    { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Interessado <td>[<b>'.$p_processo.'</b>]'; }
      if ($p_ini_i>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data cria��o/recebimento entre <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]'; }
      if ($p_fim_i>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite da tramita��o entre <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
      if ($p_atraso=='S')   { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasados</b>]'; }
      if ($w_filtro>'')     { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>'; }
    } 
 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for c�pia, aplica o filtro sobre todas as PCDs vis�veis pelo usu�rio
      $RS = db_GetSolicPA::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_servico);
    } else {
      $RS = db_GetSolicPA::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_servico);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'fim', 'desc', 'codigo_interno', 'asc');
    } else {
      $RS = SortArray($RS,'fim', 'desc', 'codigo_interno', 'asc');
    }
  }
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ScriptOpen('Javascript');
    Modulo();
    FormataCPF();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('CP',$O)!==false) {
      if ($P1!=1 || $O=='C') {
        // Se n�o for cadastramento ou se for c�pia        
        Validate('p_codigo','N�mero do pedido','','','2','60','1','1');
        Validate('p_ini_i','In�cio','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Fim','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
          ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','In�cio','<=','p_ini_f','Fim');
      } 
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_Troca>'') {
    // Se for recarga da p�gina
    BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
  } elseif (strpos('CP',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.p_codigo.focus()\';');
  } elseif ($P1==2) {
    BodyOpen(null);
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  if($w_tipo!='WORD') {
    if ((strpos(strtoupper($R),'GR_'))===false) {
      Estrutura_Texto_Abre();
    } else {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
    }
  }
  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($P1==1 && $w_copia=='') {
      // Se for cadastramento e n�o for resultado de busca para c�pia
      if ($w_tipo!='WORD') { 
        ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;'); 
        ShowHTML('    <a accesskey="C" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
      }
    } 
    if ((strpos(strtoupper($R),'GR_'))===false && $P1!=6 && $w_tipo!='WORD') {
      if ($w_copia>'') {
        // Se for c�pia
        if (MontaFiltro('GET')>'') {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } else {
        if (MontaFiltro('GET')>'') {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } 
    }
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('C�digo','codigo_interno').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Justificativa','justificativa').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Devolu��o','fim').'</td>');
      ShowHTML('          <td colspan=2><b>Solicitante</td>');
      if ($P1!=1) ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan=2><b>Opera��es</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('Pessoa','nm_solic').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Setor','sg_unidade_resp').'</td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td rowspan=2><b>C�digo</td>');
      ShowHTML('          <td rowspan=2><b>Justificativa</td>');
      ShowHTML('          <td rowspan=2><b>Devolu��o</td>');
      ShowHTML('          <td colspan=2><b>Solicitante</td>');
      if ($P1!=1) ShowHTML('          <td rowspan=2><b>Fase atual</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Pessoa</td>');
      ShowHTML('          <td><b>Setor</td>');
      ShowHTML('        </tr>');
    }
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td width="1%" nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),null,null,f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
        ShowHTML('        <td>'.htmlspecialchars(f($row,'justificativa')).'</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.formataDataEdicao(f($row,'fim')).'&nbsp;</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade'),$TP).'&nbsp;</td>');
        if ($P1!=1) ShowHTML('        <td>'.f($row,'nm_tramite').'</td>');
        ShowHTML('        <td width="1%" nowrap>');
        if ($P1!=3 && $P1!=5 && $P1!=6) {
          // Se n�o for acompanhamento
          if ($w_copia>'') {
            // Se for listagem para c�pia
            $RS = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
            foreach($RS as $row1) { $RS = $row1; break; }
            ShowHTML('          <a accesskey="I" class="HL" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
          } elseif ($P1==1) {
            // Se for cadastramento
            if ($w_submenu>'') {
              ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'codigo_interno').MontaFiltro('GET').'" title="Altera as informa��es cadastrais do pedido" TARGET="menu">AL</a>&nbsp;');
            } else {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informa��es cadastrais do pedido">AL</A>&nbsp');
            } 
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclus�o do pedido.">EX</A>&nbsp');
            ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Itens'.'&SG='.substr($SG,0,4).'ITEM').'\',\'Itens\',\'resizable=yes,toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Escolhe os itens da solicita��o.">Itens</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento do pedido">EN</A>&nbsp');
          } elseif ($P1==2) {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anota��es para a solicita��o, sem envi�-la.">AN</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicita��o para outro respons�vel.">EN</A>&nbsp');
            if (f($row,'sg_tramite')=='EE') {
              ShowHTML('          <A onclick="window.open (\''.montaURL_JS($w_dir,'relatorio.php?par=EmitirFE'.'&R='.$w_pagina.'IMPRIMIR'.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_formato=HTML&orientacao=PORTRAIT&&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Imprimir\',\'width=780,height=530,top=30,left=10, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime o formul�rio de empr�stimo.">FE</A>&nbsp');
              ShowHTML('          <A onclick="window.open (\''.montaURL_JS($w_dir,'relatorio.php?par=EmitirGF'.'&R='.$w_pagina.'IMPRIMIR'.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_formato=WORD&orientacao=PORTRAIT&&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Imprimir\',\'width=780,height=530,top=30,left=10, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a guia fora.">GF</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registrar devolu��o.">AT</A>&nbsp');
            } 
          } 
        } else {
          if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o pedido para outro respons�vel.">EN</A>&nbsp');
          } else {
            ShowHTML('          ---&nbsp');
          } 
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_tipo!='WORD') {
      ShowHTML('<tr><td align="center" colspan=3>');
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
      ShowHTML('</tr>');
    } 
  } elseif (strpos('CP',$O)!==false) {
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar o pedido que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    ShowHTML('      <tr><td valign="top" colspan="2">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se n�o for cadastramento ou se for c�pia
      ShowHTML('   <tr valign="top">');
      ShowHTML('     <td valign="top"><b>N�mero do <U>p</U>edido:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_codigo" size="20" maxlength="60" value="'.$p_codigo.'"></td>');
      ShowHTML('   <tr valign="top">');
      SelecaoPessoa('<u>S</u>olicitante:','N','Selecione o solicitante do pedido na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$p_unidade,null,'p_unidade','CLCP',null);
      ShowHTML('   <tr>');
      ShowHTML('     <td valign="top"><b><u>D</u>ata de recebimento e limite para atendimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O!='C') {
        // Se n�o for c�pia
        ShowHTML('<tr>');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('        <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar c�pia">');
    } else {
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  Rodape();
} 

// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';
  
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_justificativa            = $_REQUEST['w_justificativa'];
    $w_fim                      = $_REQUEST['w_fim'];
  } else {
    if (strpos('AEV',$O)!==false || $w_copia>'') {
      // Recupera os dados do pedido
      if ($w_copia>'') {
        $RS = db_GetSolicPA::getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
      } else {
        $RS = db_GetSolicPA::getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
      }
      if (count($RS)>0) {
        foreach($RS as $row){$RS=$row; break;}
        $w_justificativa     = f($RS,'justificativa');
        $w_fim               = FormataDataEdicao(f($RS,'fim'));
      } 
    } 
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ShowHTML('function botoes() {');
  if ($O=='I') {
    ShowHTML('  document.Form.Botao[0].disabled = true;');
    ShowHTML('  document.Form.Botao[1].disabled = true;');
  } else {
    ShowHTML('  document.Form.Botao.disabled = true;');
  } 
  ShowHTML('}');
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_justificativa','Justificativa','','1',3,2000,'1','1');
    Validate('w_fim','Data de devolu��o','DATA','1',10,10,'1','0123456789/');
    CompData('w_fim','Data de devolu��o','>=',FormataDataEdicao(time()),'data atual');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'this.focus()\';');
  } elseif (strpos('IA',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_justificativa.focus()\';');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpen('onLoad=\'this.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if ($w_cidade=='') {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_cidade=f($RS,'sq_cidade_padrao');
    }   
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro=Validacao($w_sq_solicitacao,$sg);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><tdcolspan=3><b><u>J</u>ustificativa da necessidade:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="� obrigat�rio justificar.">'.$w_justificativa.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top"><b><u>D</u>ata prevista para devolu��o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data prevista para devolu��o dos itens.">'.ExibeCalendario('Form','w_fim').'</td>');

    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de itens da compra
// -------------------------------------------------------------------------
function Itens() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_chave_aux          = $_REQUEST['w_chave_aux'];

  // Recupera os dados da solicitacao
  $RS_Solic = db_GetSolicPA::getInstanceOf($dbms,null,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O <> 'E') {
    $w_sq_material        = $_REQUEST['w_sq_material'];
    $w_quantidade         = $_REQUEST['w_quantidade'];
  } elseif ($O=='I') {
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PADCAD');
    $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),7,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, $p_sq_orprior, $p_empenho, $p_processo);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
     } else {
      $RS = SortArray($RS,'phpdt_fim','asc');
    }
  } elseif (strpos('L',$O)!==false) {
    $RS = db_getPAEmpItem::getInstanceOf($dbms,null,$w_chave,null,null,null,null);
    $RS = SortArray($RS,'nm_tipo_material','asc','nome','asc'); 
  } elseif (strpos('AEV',$O)!==false) {
    $RS = db_getPAEmpItem::getInstanceOf($dbms,$w_chave_aux,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave_aux           = f($RS,'chave');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.f($RS_Menu,'nome').' - Itens</TITLE>');
  Estrutura_CSS($w_cliente);
  Estrutura_CSS($w_cliente);
  if (strpos('PLIA',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    FormataValor();
    ShowHTML('  function MarcaTodos() {');
    ShowHTML('    if (document.Form["marca"].checked) {');
    ShowHTML('       for (i=1; i < document.Form["w_protocolo[]"].length; i++) {');
    ShowHTML('         document.Form["w_protocolo[]"][i].checked=true;');
    ShowHTML('       } ');
    ShowHTML('    } else { ');
    ShowHTML('       for (i=1; i < document.Form["w_protocolo[]"].length; i++) {');
    ShowHTML('         document.Form["w_protocolo[]"][i].checked=false;');
    ShowHTML('       } ');
    ShowHTML('    }');
    ShowHTML('  }');
    ValidateOpen('Validacao');
    if ($O=='P') {
      ShowHTML('if (theForm.p_sq_orprior.value==\'\' && theForm.p_pais.value==\'\' && theForm.p_uf.value==\'\' && theForm.p_empenho.value==\'\') {');
      ShowHTML(' alert(\'Informe pelo menos um crit�rio de filtragem!\');');
      ShowHTML(' return false;');
      ShowHTML('}');
    } elseif($O=='I') {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_protocolo[]"].value==undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_protocolo[]"].length; i++) {');
      ShowHTML('       if (theForm["w_protocolo[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_protocolo[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Voc� deve informar pelo menos um protocolo!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    if ($O=='P'){
      ShowHTML('  theForm.Botao[2].disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='P'){
    BodyOpen('onLoad="this.focus();"');
  } elseif (strpos('LIA',$O)!==false) {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  // Exibe os dados da solicita��o
  ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=3><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td><table border=0 width="100%">');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>'.f($RS_Menu,'nome').':<b><br>'.f($RS_Solic,'codigo_interno').'</td>');
  ShowHTML('            <td>Data prevista de devolu��o:<b><br>'.formataDataEdicao(f($RS_Solic,'fim')).'</td>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Unidade solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($O=='L') {
    ShowHTML('<tr><td>');
    ShowHTML('                <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Tipo</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td colspan=3><b>Localiza��o</td>');
    ShowHTML('          <td rowspan=2><b>Opera��es</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Esp�cie</td>');
    ShowHTML('          <td><b>N�</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>Proced�ncia</td>');
    ShowHTML('          <td><b>Caixa</td>');
    ShowHTML('          <td><b>Pasta</td>');
    ShowHTML('          <td><b>Local</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=11 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        ShowHTML('        <td width="10">&nbsp;'.f($row,'nm_tipo').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'numero_original').'</td>');
        ShowHTML('        <td>&nbsp;'.formataDataEdicao(f($row,'data_recebimento'),5).'&nbsp;</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'nm_origem_doc').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'nr_caixa').'/'.f($row,'sg_unid_caixa').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'pasta').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'nm_arquivo_local').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_protocolo='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir" onClick="return confirm(\'Confirma a exclus�o do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('        </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
  } elseif ($O=='I') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_protocolo[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<tr><td>');
    if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=P&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=P&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td NOWRAP rowspan="2"><font size="2"><input type="checkbox" name="marca" value="" onClick="javascript:MarcaTodos();" TITLE="Marca/desmarca todos os itens da rela��o">');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>'.linkOrdena('Protocolo','protocolo').'</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>'.linkOrdena('Tipo','nm_tipo_protocolo').'</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2><b>'.linkOrdena('Caixa','nr_caixa').'</td>');
    ShowHTML('          <td rowspan=2><b>'.linkOrdena('Pasta','pasta').'</td>');
    //ShowHTML('          <td rowspan=2><b>Emprestar toda a caixa?</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Esp�cie','nm_especie').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('N�','numero_original').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Data','inicio').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Proced�ncia','nm_origem_doc').'</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_cont=0;
      foreach($RS1 as $row){ 
        $w_cont+= 1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center" width="1%" nowrap>'); 
        ShowHTML('          <input type="CHECKBOX" name="w_protocolo[]" value="'.f($row,'sq_siw_solicitacao').'" ></td>'); 
        ShowHTML('        </td>');
        ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
        ShowHTML('        <td width="10">&nbsp;'.f($row,'nm_tipo_protocolo').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'nm_especie').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'numero_original').'</td>');
        ShowHTML('        <td>&nbsp;'.formataDataEdicao(f($row,'inicio'),5).'&nbsp;</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'nm_origem_doc').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'nr_caixa').'/'.f($row,'sg_unid_caixa').'</td>');
        ShowHTML('        <td>&nbsp;'.f($row,'pasta').'</td>');
        //MontaRadioNS(null,$w_aviso,'w_aviso_'.f($row,'sq_siw_solicitacao'));
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&R='.$R.'&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('  </td>');    
    ShowHTML('</FORM>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&w_menu='.$w_menu.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&w_menu='.$w_menu.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('      <tr><td colspan=2><table border=0 bgcolor="'.$conTrBgColor.'" width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('<tr><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'I');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('      <tr><td colspan=2 align="center"><table border=0 width="90%" cellspacing=0><tr valign="top">');
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
    ShowHTML('      <tr valign="top">');
    SelecaoCaixa('<u>C</u>aixa:','C',"Selecione a caixa para arquivamento.",$p_sq_orprior,$w_cliente,null,'p_sq_orprior','CENTRAL',null);
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>N�mero:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="'.$p_empenho.'">');
    selecaoEspecieDocumento('<u>E</u>sp�cie documental:','E','Selecione a esp�cie do documento.',$p_usu_resp,null,'p_usu_resp',null,null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
    ShowHTML('          <td><b>Limi<u>t</u>e para tramita��o entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:','O',null,$p_unidade,null,'p_unidade',null,null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>A</U>ssunto:<br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="'.$p_assunto.'"></td>');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" '.$w_Disabled.' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="'.$p_processo.'"></td>');
    ShowHTML('        </tr></table>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&R='.$R.'&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 

// ------------------------------------------------------------------------- 
// Rotina de anexos 
// ------------------------------------------------------------------------- 
function Anexos() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];

  // Recupera os dados da solicitacao
  $RS_Solic = db_GetSolicPA::getInstanceOf($dbms,null,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endere�o informado 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach ($RS as $row) {
      $w_nome      = f($row,'nome');
      $w_descricao = f($row,'descricao');
      $w_caminho   = f($row,'chave_aux');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.f($RS_Menu,'nome').' - Anexos</TITLE>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','T�tulo','1','1','1','255','1','1');
      Validate('w_descricao','Descri��o','1','1','1','1000','1','1');
      if ($O=='I') {
        Validate('w_caminho','Arquivo','','1','5','255','1','1');
      } 
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_descricao.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  // Exibe os dados da solicita��o
  ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=3><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td><table border=0 width="100%">');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>'.f($RS_Menu,'nome').':<b><br>'.f($RS_Solic,'codigo_interno').'</td>');
  ShowHTML('            <td>Data do pedido:<b><br>'.formataDataEdicao(f($RS_Solic,'inicio')).'</td>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Unidade solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('<a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>T�tulo</td>');
    ShowHTML('          <td><b>Descri��o</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>');
        ShowHTML('        <td>'.Nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td>'.f($row,'tipo').'</td>');
        ShowHTML('        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_caminho.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I' || $O=='A') {
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</b></font>.</td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    }  
    ShowHTML('      <tr><td><b><u>T</u>�tulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="OBRIGAT�RIO. Informe um t�tulo para o arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="OBRIGAT�RIO. Descreva a finalidade do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo. Ele ser� transferido automaticamente para o servidor.">');
    if ($w_caminho>'') {
      ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
    } 
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclus�o do registro?\');">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);' 
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de visualiza��o
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));

  if ($w_tipo=='PDF') {
    headerPdf('Visualiza��o de '.f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualiza��o de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    CabecalhoRelatorio($w_cliente,'Visualiza��o de '.f($RS_Menu,'nome'),4,$w_chave);
    $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  // Chama a rotina de visualiza��o dos dados da PCD, na op��o 'Listagem'
  ShowHTML(VisualEmprestimo($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  if ($w_tipo=='PDF')      RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
}
// =========================================================================
// Rotina de exclus�o
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML(VisualEmprestimo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PAEMCAD',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<tr ><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de tramita��o
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');

  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
  } else {
    // Recupera os dados da solicitacao
    $RS = db_GetSolicPA::getInstanceOf($dbms,null,$w_usuario,$SG,5,
            null,null,null,null,null,null,null,null,null,null,
            $w_chave,null,null,null,null,null,null,
            null,null,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
    $w_inicio        = f($RS,'inicio');
    $w_fim           = f($RS,'fim');
    $w_tramite       = f($RS,'sq_siw_tramite');
    $w_justificativa = f($RS,'justificativa');
  } 

  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $RS = db_getTramiteData::getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');

  if ($w_sg_tramite!='CI') {
    //Verifica a fase anterior para a caixa de sele��o da fase.
    $RS = db_getTramiteList::getInstanceOf($dbms,$w_tramite,null,'ANTERIOR',null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 

  // Se for envio, executa verifica��es nos dados da solicita��o
  if ($O=='V') $w_erro = ValidaEmprestimo($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($w_sg_tramite!='CI') {
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE' || $w_ativo=='N') {
        Validate('w_despacho','Despacho','1','1','1','2000','1','1');
      } else {
        Validate('w_despacho','Despacho','','','1','2000','1','1');
        ShowHTML('  if (theForm.w_envio[0].checked && theForm.w_despacho.value != \'\') {');
        ShowHTML('     alert(\'Informe o despacho apenas se for devolu��o para a fase anterior!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_envio[1].checked && theForm.w_despacho.value==\'\') {');
        ShowHTML('     alert(\'Informe um despacho descrevendo o motivo da devolu��o!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
      } 
    } 
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    if ($P1!=1 || ($P1==1 && $w_tipo=='Volta')) {
      // Se n�o for encaminhamento e nem o sub-menu do cadastramento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($P1==1) {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da solicita��o, na op��o 'Listagem'
  ShowHTML(VisualEmprestimo($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || $w_ativo=='N' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PAEMENVIO',$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%">');
    if ($w_sg_tramite=='CI') {
      if (substr(Nvl($w_erro,'nulo'),0,1)!='0') {
        // Se cadastramento inicial
        ShowHTML('<INPUT type="hidden" name="w_envio" value="N">');
        ShowHTML('      </table>');
        ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr>');
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
      }
    } else {
      ShowHTML('    <tr><td><b>Tipo do Encaminhamento</b><br>');
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE' || $w_ativo=='N') {
        ShowHTML('              <input DISABLED class="STR" type="radio" name="w_envio" value="N"> Enviar para a pr�xima fase <br><input DISABLED class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
        ShowHTML('<INPUT type="hidden" name="w_envio" value="S">');
      } else {
        if (Nvl($w_envio,'N')=='N') {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N" checked> Enviar para a pr�xima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S"> Devolver para a fase anterior');
        } else {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N"> Enviar para a pr�xima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
        } 
      } 
      ShowHTML('    <tr>');
      SelecaoFase('<u>F</u>ase: (v�lido apenas se for devolu��o)','F','Se deseja devolver a PCD, selecione a fase para a qual deseja devolv�-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','DEVFLUXO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolu��o):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinat�rio deve fazer quando receber a PCD.">'.$w_despacho.'</TEXTAREA></td>');
      if (!(substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE' || $w_ativo=='N')) {
        if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
          if (addDays($w_inicio,-$w_prazo)<addDays(time(),-1)) {
            ShowHTML('    <tr><td><b><u>J</u>ustificativa para n�o cumprimento do prazo regulamentar de '.$w_prazo.' dias:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o in�cio da viagem for anterior a '.FormataDataEdicao(addDays(time(),$w_prazo)).', justifique o motivo do n�o cumprimento do prazo regulamentar para o pedido.">'.$w_justificativa.'</TEXTAREA></td>');
          } 
        } 
      } 
      ShowHTML('      </table>');
      ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
    } 
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de anota��o
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anota��o','','1','1','2000','1','1');
    Validate('w_caminho','Arquivo','','','5','255','1','1');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>'); 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_observacao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da solicita��o, na op��o 'Listagem'
  ShowHTML(VisualEmprestimo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_dir.$w_pagina.'Grava&SG=PAEMENVIO&O='.$O.'&w_menu='.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $RS = db_GetSolicPA::getInstanceOf($dbms,null,$w_usuario,$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}  
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_novo_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top"><b>A<u>n</u>ota��o:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anota��o desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de conclus�o
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  //Recupera os dados da solicitacao de passagens e di�rias
  $RS = db_GetSolicPA::getInstanceOf($dbms,null,$w_usuario,$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}  
  $w_nota_conclus�o = f($RS,'nota_conclusao');
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');

  ShowHTML('  for (ind=1; ind < theForm["w_protocolo[]"].length; ind++) {');
  Validate('["w_devolucao[]"][ind]','Data da devolu��o','DATA','',10,10,'','0123456789/');
  CompData('["w_devolucao[]"][ind]','Data da devolu��o','<=',FormataDataEdicao(time()),'data atual');
  ShowHTML('  }');
  Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da PCD, na op��o 'Listagem'
  ShowHTML(VisualEmprestimo($w_chave,'X',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=PAEMCONC&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_protocolo[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_devolucao[]" value="">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="100%" border="0">');
  $RS1 = db_getPAEmpItem::getInstanceOf($dbms,null,$w_chave,null,null,null,null);
  $RS1 = SortArray($RS1,'ano','asc','protocolo','asc');
  ShowHTML('<tr><td colspan=4><b>Informe para cada item a data de devolu��o. A solicita��o ser� conclu�da quando todos os itens tiverem data de devolu��o informada.</b>');
  ShowHTML('<tr><td align="center" colspan=4>');  
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
  ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Tipo</td>');
  ShowHTML('          <td colspan=4><b>Documento original</td>');
  ShowHTML('          <td colspan=3><b>Localiza��o</td>');
  ShowHTML('          <td rowspan=2><b>Devolu��o</td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td><b>Esp�cie</td>');
  ShowHTML('          <td><b>N�</td>');
  ShowHTML('          <td><b>Data</td>');
  ShowHTML('          <td><b>Proced�ncia</td>');
  ShowHTML('          <td><b>Caixa</td>');
  ShowHTML('          <td><b>Pasta</td>');
  ShowHTML('          <td><b>Local</td>');
  ShowHTML('        </tr>');
  if (count($RS1)<=0) {
    // Se n�o foram selecionados registros, exibe mensagem
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=11 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
  } else {
    // Lista os registros selecionados para listagem
    foreach($RS1 as $row){ 
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>');
      ShowHTML('        <td width="10">&nbsp;'.f($row,'nm_tipo').'</td>');
      ShowHTML('        <td>&nbsp;'.f($row,'nm_especie').'</td>');
      ShowHTML('        <td>&nbsp;'.f($row,'numero_original').'</td>');
      ShowHTML('        <td>&nbsp;'.formataDataEdicao(f($row,'data_recebimento'),5).'&nbsp;</td>');
      ShowHTML('        <td>&nbsp;'.f($row,'nm_origem_doc').'</td>');
      ShowHTML('        <td>&nbsp;'.f($row,'nr_caixa').'/'.f($row,'sg_unid_caixa').'</td>');
      ShowHTML('        <td>&nbsp;'.f($row,'pasta').'</td>');
      ShowHTML('        <td>&nbsp;'.f($row,'nm_arquivo_local').'</td>');
      ShowHTML('<INPUT type="hidden" name="w_protocolo[]" value="'.f($row,'chave').'">');
      ShowHTML('        <td align="center"><input '.$w_Disabled.' accesskey="D" type="text" name="w_devolucao[]" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.formataDataEdicao(f($row,'devolucao')).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data da devolu��o do item."></td>');
      ShowHTML('        </tr>');
    }
  } 
  ShowHTML('    </table>');
  ShowHTML('    <tr><td colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Concluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de prepara��o para envio de e-mail relativo a PCDs
// Finalidade: preparar os dados necess�rios ao envio autom�tico de e-mail
// Par�metro: p_solic: n�mero de identifica��o da solicita��o. 
//            p_tipo:  1 - Inclus�o
//                     2 - Tramita��o
//                     3 - Conclus�o
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  global $w_Disabled;
  //Verifica se o cliente est� configurado para receber email na tramita�ao de solicitacao
  $RS   = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $RSM = db_GetSolicPA::getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $p_solic,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    $w_anexos         = array();

    // Recupera os dados da PCD
    $w_sg_tramite = f($RSM,'sg_tramite');
    $w_nome       = f($RSM,'codigo_interno');

    // Se for o tr�mite de presta��o de contas, envia e-mail ao proposto com o relat�rio de viagem anexado
    if ($w_sg_tramite=='EE') {
      // Configura o nome dos arquivo recebido e do arquivo registro
      $w_file = $conFilePhysical.$w_cliente.'/'.'relatorio_'.str_replace('/','-',$w_nome).'.doc';
      if (!is_writable($conFilePhysical.$w_cliente)) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: n�o h� permiss�o de escrita no diret�rio.\\n'.$conFilePhysical.$w_cliente.'\');');
        ScriptClose();
      } else {
        if (!$handle = fopen($w_file,'w')) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel abrir o arquivo para escrita.\\n'.$w_file.'\');');
          ScriptClose();
        } else {
          if (!fwrite($handle, RelatorioViagem($p_solic))) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel inserir o conte�do do arquivo.\\n'.$w_file.'\');');
            ScriptClose();
            fclose($handle);
          } else {
            fclose($handle);
            $w_anexos[0] = array(
              "FileName"=>$w_file,
              "Content-Type"=>"automatic/name",
              "Disposition"=>"attachment"
            );
          }
        }
      }
    } 
    $w_html='<HTML>'.$crlf;
    $w_html .= BodyOpenMail(null).$crlf;
    $w_html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html .= '<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
    $w_html .= '    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1) {
      $w_html .= '      <tr valign="top"><td align="center"><b>INCLUS�O DE PCD</b><br><br><td></tr>'.$crlf;
    } elseif ($w_sg_tramite=='EE') {
      $w_html .= '      <tr valign="top"><td align="center"><b>PRESTA��O DE CONTAS DE PCD</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==2) {
      $w_html .= '      <tr valign="top"><td align="center"><b>TRAMITA��O DE PCD</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==3) {
      $w_html .= '      <tr valign="top"><td align="center"><b>CONCLUS�O DE PCD</b><br><br><td></tr>'.$crlf;
    } 
    if ($w_sg_tramite=='EE') {
      $w_html .= '      <tr valign="top"><td><b><font color="#BC3131">ATEN��O:<br>Conforme Portaria N� 47/MPO 29/04/2003 � DOU 30/04/2003, � necess�rio elaborar o relat�rio de viagem e entregar os bilhetes de embarque.<br><br>Use o arquivo anexo para elaborar seu relat�rio de viagem e entregue-o assinado ao setor competente, juntamente com os bilhetes.</font></b><br><br><td></tr>'.$crlf;
    } else {
      $w_html .= '      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATEN��O: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</font></b><br><br><td></tr>'.$crlf;
    } 
    $w_html .= $crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html .= $crlf.'    <table width="99%" border="0">';
    // Identifica��o da PCD
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DA PCD</td>';
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html .= $crlf.'          <tr valign="top">';
    $w_html .= $crlf.'            <td>Proposto:<br><b>'.f($RSM,'nm_prop').'</b></td>';
    $w_html .= $crlf.'            <td>Unidade proponente:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
    $w_html .= $crlf.'          <tr valign="top">';
    $w_html .= $crlf.'            <td>Primeira sa�da:<br><b>'.FormataDataEdicao(f($RSM,'inicio')).' </b></td>';
    $w_html .= $crlf.'            <td>�ltimo retorno:<br><b>'.FormataDataEdicao(f($RSM,'fim')).' </b></td>';
    $w_html .= $crlf.'          </table>';
    // Informa��es adicionais
    if (Nvl(f($RSM,'descricao'),'')>'') {
      if (Nvl(f($RSM,'descricao'),'')>'') $w_html .= $crlf.'      <tr><td valign="top">Descri��o da PCD:<br><b>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
    } 
    $w_html .= $crlf.'    </table>';
    $w_html .= $crlf.'</tr>';

    //Recupera o �ltimo log
    $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
    foreach ($RS as $row) { $RS = $row; break; }
    $w_data_encaminhamento = f($RS,'phpdt_data');
    if ($p_tipo==2) {
      if ($w_sg_tramite=='EE') {
        // Recupera o n�mero m�ximo de dias para entrega da presta��o de contas
        $RS1 = db_getPDParametro::getInstanceOf($dbms,$w_cliente,null,null);
        foreach($RS1 as $row) { $RS1 = $row; break; }
        $w_dias_prest_contas = f($RS1,'dias_prestacao_contas');

        $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ORIENTA��ES PARA PRESTA��O DE CONTAS</td>';
        $w_html .= $crlf.'        <tr><td valign="top" colspan="2" bgcolor="'.$w_TrBgColor.'">';
        $w_html .= $crlf.'          <p>Esta PCD foi autorizada. Voc� deve entregar os documentos abaixo na unidade proponente (<b>'.f($RSM,'nm_unidade_resp').')</b>';
        $w_html .= $crlf.'          <ul>';
        $w_html .= $crlf.'          <li>Relat�rio de viagem (anexo) preenchido;';
        $w_html .= $crlf.'          <li>Bilhetes de embarque;';
        $w_html .= $crlf.'          <li>Notas fiscais de taxi, restaurante e hotel.';
        $w_html .= $crlf.'          </ul>';
        $w_html .= $crlf.'          <p>A data limite para entrega � at� o �ltimo dia �til antes de: <b>'.substr(FormataDataEdicao(addDays(f($RSM,'fim'),$w_dias_prest_contas),4),0,-10).' </b>; caso contr�rio, suas viagens ser�o automaticamente bloqueadas pelo sistema.';

        $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA CONCESS�O</td>';
        // Benef�cios servidor
        $RS1 = db_getSolicData::getInstanceOf($dbms,$p_solic,'PDGERAL');
        if (count($RS1)>0) {
          $w_html .= $crlf.'        <tr><td valign="top" colspan="2" align="center" bgcolor="'.$w_TrBgColor.'"><b>Benef�cios recebidos pelo proposto</td>';
          $w_html .= $crlf.'        <tr><td align="center" colspan="2">';
          $w_html .= $crlf.'          <TABLE WIDTH="100%" bgcolor="'.$w_TrBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
          $w_html .= $crlf.'            <tr>';
          if (Nvl(f($RS1,'valor_alimentacao'),0)>0) $w_html .= $crlf.'           <td>Aux�lio-alimenta��o: <b>Sim</b></td>'; else $w_html .= $crlf.'           <td>Aux�lio-alimenta��o: <b>N�o</b></td>';
          $w_html .= $crlf.'              <td>Valor R$: <b>'.formatNumber(Nvl(f($RS1,'valor_alimentacao'),0)).'</b></td>';
          $w_html .= $crlf.'            <tr>';
          if (Nvl(f($RS1,'valor_transporte'),0)>0) $w_html .= $crlf.'           <td>Aux�lio-transporte: <b>Sim</b></td>'; else $w_html .= $crlf.'           <td>Aux�lio-transporte: <b>N�o</b></td>';
          $w_html .= $crlf.'              <td>Valor R$: <b>'.formatNumber(Nvl(f($RS1,'valor_transporte'),0)).'</b></td>';
          $w_html .= $crlf.'          </table></td></tr>';
        }  

      } else {
        $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>�LTIMO ENCAMINHAMENTO</td>';
        $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
        $w_html .= $crlf.'          <tr><td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
        if (Nvl(f($RS,'despacho'),'')!='') {
          $w_html.=$crlf.'          <tr><td>Despacho:<br><b>'.CRLF2BR(f($RS,'despacho')).' </b></td>';
        }
        $w_html .= $crlf.'          </table>';
      }
    } 
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMA��ES</td>';
    $RS = db_getCustomerSite::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    $w_html .= '      <tr valign="top"><td>'.$crlf;
    $w_html .= '         Para acessar o sistema use o endere�o: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html .= '      </td></tr>'.$crlf;
    $w_html .= '      <tr valign="top"><td>'.$crlf;
    $w_html .= '         Dados da ocorr�ncia:<br>'.$crlf;
    $w_html .= '         <ul>'.$crlf;
    $w_html .= '         <li>Respons�vel: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html .= '         <li>Data: <b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
    $w_html .= '         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html .= '         </ul>'.$crlf;
    $w_html .= '      </td></tr>'.$crlf;
    $w_html .= '    </table>'.$crlf;
    $w_html .= '</td></tr>'.$crlf;
    $w_html .= '</table>'.$crlf;
    $w_html .= '</BODY>'.$crlf;
    $w_html .= '</HTML>'.$crlf;
    // Prepara os dados necess�rios ao envio
    $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclus�o ou Conclus�o
      if ($p_tipo==1) $w_assunto='Inclus�o - '.$w_nome; else $w_assunto='Encerramento - '.$w_nome;
    } elseif ($w_sg_tramite=='EE') {
      // Presta��o de contas
      $w_assunto='Presta��o de Contas - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramita��o
      $w_assunto='Tramita��o - '.$w_nome;
    } 
    // Configura os destinat�rios da mensagem
    $RS = db_getTramiteResp::getInstanceOf($dbms,$p_solic,null,null);
    if (!count($RS)<=0) {
      foreach($RS as $row) {
        $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
     } 
    } 
    if(f($RSM,'st_sol')=='S') {
      // Recupera o e-mail do respons�vel
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    if(f($RSM,'st_prop')=='S') {
      // Recupera o e-mail do proposto
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'sq_prop'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    // Executa o envio do e-mail
    if ($w_destinatarios>'') $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,$w_anexos);

    if ($w_sg_tramite=='EE') {
      // Remove o arquivo tempor�rio
      if (!unlink($w_file)) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel remover o arquivo tempor�rio.\\n'.$w_file.'\');');
        ScriptClose();
      }
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\\n'.$w_resultado.'\');');
      ScriptClose();
    } 
  } 
}
// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'PAEMP':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putPAEmpGeral::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_copia'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],
          $_SESSION['SQ_PESSOA'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_justificativa'],$_REQUEST['w_fim'],$_REQUEST['w_cidade']);
        
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PAEMITEM':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_protocolo'])-1; $i=$i+1) {
            if ($_REQUEST['w_protocolo'][$i]>'') {
              dml_putPAEmpItem::getInstanceOf($dbms,$O,$_REQUEST['w_protocolo'][$i],$_REQUEST['w_chave'],null);
            }
          } 
        } elseif ($O=='E') {
          dml_putPAEmpItem::getInstanceOf($dbms,$O,$_REQUEST['w_protocolo'],$_REQUEST['w_chave'],null);
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }     
      break;
    case 'PAEMANEXO':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            $w_tamanho = $Field['size'];            
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
              if ($_REQUEST['w_atual']>'') {
                $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
                foreach ($RS as $row) {
                  if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                  if (!(strpos(f($row,'caminho'),'.')===false)) {
                    $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,30);
                  } else {
                    $w_file = basename(f($row,'caminho'));
                  }
                }
              } else {
                $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
                }
              } 
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            }elseif(nvl($Field['name'],'')!=''){
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Aten��o: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            }
          } 
          // Se for exclus�o e houver um arquivo f�sico, deve remover o arquivo do disco.  
          if ($O=='E' && $_REQUEST['w_atual']>'') {
            $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
            foreach ($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
            }
          } 
          dml_putSolicArquivo::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        } 
        ScriptOpen('JavaScript');
        // Recupera a sigla do servi�o pai, para fazer a chamada ao menu 
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;      
    case 'PAEMENVIO':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Trata o recebimento de upload ou dados 
        if ((false!==(strpos(strtoupper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(strtoupper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
          // Se foi feito o upload de um arquivo 
          if (UPLOAD_ERR_OK==0) {
            $w_maximo = $_REQUEST['w_upload_maximo'];
            foreach ($_FILES as $Chv => $Field) {
              if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
                // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              }
              if ($Field['size'] > 0) {
                // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
                if ($Field['size'] > $w_maximo) {
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                  ScriptClose();
                  retornaFormulario('w_observacao');
                  exit();
                } 
                // Se j� h� um nome para o arquivo, mant�m 
                $w_file = basename($Field['tmp_name']);
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
                }
                $w_tamanho = $Field['size'];
                $w_tipo    = $Field['type'];
                $w_nome    = $Field['name'];
                if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
              } 
            } 
            dml_putSolicEnvio::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
            //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
            if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
              $RS = db_getTramiteData::getInstanceOf($dbms,$_REQUEST['w_tramite']);
              $w_sg_tramite = f($RS,'sigla');
              if($w_sg_tramite=='CI') {
                $w_html = VisualEmprestimo($_REQUEST['w_chave'],'L',$w_usuario,null,'1');
                CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
              }
            }  
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
            ScriptClose();
          } 
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } else {
          if ($_REQUEST['w_envio']=='N') {
            dml_putSolicEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
              $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
          } else {
            dml_putSolicEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
              $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
          } 
          //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
          if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
            $RS = db_getTramiteData::getInstanceOf($dbms,$_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS,'sigla');
            if($w_sg_tramite=='CI') {
              $w_html = VisualEmprestimo($_REQUEST['w_chave'],'L',$w_usuario,null,'1');
              CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
            }
          }  
          // Envia e-mail comunicando o envio
          SolicMail($_REQUEST['w_chave'],2);
          // Se for envio da fase de cadastramento, remonta o menu principal
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PAEMCONC':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $RS = db_GetSolicPA::getInstanceOf($dbms,null,$w_usuario,$SG,3,
                null,null,null,null,null,null,null,null,null,null,
                $_REQUEST['w_chave'],null,null,null,null,null,null,
                null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� encaminhou o pedido para fase de execu��o!\');');
          ScriptClose();
          exit();
        } else {
          // Grava as quantidades autorizadas
          for ($i=0; $i<=count($_POST['w_protocolo'])-1; $i=$i+1) {
            if ($_REQUEST['w_protocolo'][$i]>'') {
              dml_putPAEmpItem::getInstanceOf($dbms,$O,$_REQUEST['w_protocolo'][$i],$_REQUEST['w_chave'],$_REQUEST['w_devolucao'][$i]);
            }
          }

          $RS = db_getPAEmpItem::getInstanceOf($dbms,null,$_REQUEST['w_chave'],null,null,null,null);
          $concluir = true;
          foreach($RS as $row) {
            // Se pelo menos um item n�o foi devolvido, n�o encerra a solicita��o
            if (nvl(f($row,'devolucao'),'')=='') $concluir = false;
          }
          if ($concluir) {
            // Se todos os itens foram devolvidos, encerra a solicita��o
            dml_putSolicConc::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
                $_SESSION['SQ_PESSOA'],null,null,null,null,null,null,null,null,null);
            // Envia e-mail comunicando a conclus�o
            SolicMail($_REQUEST['w_chave'],3);
          }
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
      break;
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'INICIAL':           Inicial(); break;
  case 'GERAL':             Geral(); break;
  case 'ITENS':             Itens(); break;
  case 'ANEXOS':            Anexos(); break;
  case 'VISUAL':            Visual(); break;
  case 'EXCLUIR':           Excluir(); break;
  case 'ENVIO':             Encaminhamento(); break;
  case 'ANOTACAO':          Anotar(); break;
  case 'CONCLUIR':          Concluir(); break;
  case 'GRAVA':             Grava(); break; 
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

