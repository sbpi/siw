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
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getBankData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteResp.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteSolic.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getCiaTrans.php');
include_once($w_dir_volta.'classes/sp/db_getSolicViagem.php');
include_once($w_dir_volta.'classes/sp/db_getPD_Deslocamento.php');
include_once($w_dir_volta.'classes/sp/db_getPD_Vinculacao.php');
include_once($w_dir_volta.'classes/sp/db_getPDParametro.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putViagemGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putViagemOutra.php');
include_once($w_dir_volta.'classes/sp/dml_putViagemEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putPD_Deslocamento.php');
include_once($w_dir_volta.'classes/sp/dml_putPD_Atividade.php');
include_once($w_dir_volta.'classes/sp/dml_putPD_Missao.php');
include_once($w_dir_volta.'classes/sp/dml_putPD_Diaria.php');
include_once($w_dir_volta.'classes/sp/dml_putDemandaEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putDemandaConc.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/retornaCadastrador_PD.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPCD.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
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
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAgencia.php');
include_once('visualviagem.php');
include_once('validaviagem.php');

// =========================================================================
//  /viagem.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o seviço de viagens
// Mail     : celso@sbpi.com.br
// Criacao  : 05/10/2005, 11:19
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = C   : Cancelamento
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio

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
$w_pagina       = 'viagem.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];

if (strpos('PDTRECHO,PDVINC',nvl($SG,'nulo'))!==false) {
  if ($O!='I' && $_REQUEST['w_chave_aux']=='' && $_REQUEST['w_demanda']=='') $O='L';
} elseif (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - Cópia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Herança';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
$w_cadgeral = RetornaCadastrador_PD($w_menu, $w_usuario);

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


// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
} 

// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}

// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;

  if ($O=='L') {
    if (strpos(strtoupper($R),'GR_')!==false || strpos(strtoupper($R),'PROJETO')!==false) {
      $w_filtro='';
      if ($p_projeto>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'PJGERAL');
        $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
      } 
      if ($p_atividade>'') {
        $RS = db_getSolicEtapa::getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
        foreach($RS as $row) { $RS = $row; break; }
        $w_filtro .= '<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
      } 
      if ($p_codigo>'')  $w_filtro .= '<tr valign="top"><td align="right">PCD nº <td>[<b>'.$p_codigo.'</b>]';
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
      if ($p_palavra>'')    $w_filtro .= '<tr valign="top"><td align="right">CPF proposto <td>[<b>'.$p_palavra.'</b>]';
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
      if ($p_cidade>'') {
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
        if ($p_ativo=='I') $w_filtro .= 'Inicial';
        elseif ($p_ativo=='P') $w_filtro .= 'Prorrogação';
        elseif ($p_ativo=='C') $w_filtro .= 'Complementação';
        $w_filtro .= '</b>]';
      } 
      if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Mês <td>[<b>'.$p_ini_i.'</b>]';
      if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
      if ($w_filtro>'')     $w_filtro  ='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as PCDs visíveis pelo usuário
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,$SG,3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_codigo, null);
    } else {
      if (Nvl($_REQUEST['p_agrega'],'')=='GRPDACAO') {
        $RS = db_getSolicList_IS::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
            $p_ini_i,$p_ini_f,null,null,$p_atraso,$p_solicitante,
            $p_unidade,null,$p_ativo,$p_proponente);
      } elseif (Nvl($_REQUEST['p_agrega'],'')=='GRPDCIAVIAGEM' || Nvl($_REQUEST['p_agrega'],'')=='GRPDCIDADE' || Nvl($_REQUEST['p_agrega'],'')=='GRPDDATA') {
        $RS = db_getSolicViagem::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante, $p_unidade,$p_prioridade,$p_ativo,$p_proponente, 
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, 
            $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_acao_ppa, $p_orprior);
      } else {
        $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_codigo, null);
      } 
    } 

    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'ordem','asc', 'fim', 'desc', 'prioridade', 'asc');
    } else {
      $RS = SortArray($RS,'ordem','asc', 'fim', 'desc', 'prioridade', 'asc');
    }
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de Viagens</TITLE>');
  ScriptOpen('Javascript');
  Modulo();
  FormataCPF();
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (strpos('CP',$O)!==false) {
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia        
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
    } 
    Validate('P4','Linhas por página','1','1','1','4','','0123456789');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_Troca>'') {
    // Se for recarga da página
    BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
  } elseif (strpos('CP',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.p_projeto.focus()\';');
  } elseif ($P1==2) {
    BodyOpen(null);
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($P1==1 && $w_copia=='') {
      // Se for cadastramento e não for resultado de busca para cópia
      if ($w_submenu>'') {
        $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
        foreach($RS1 as $row) { $RS1 = $row; break; }
        ShowHTML('<tr><td>');
        ShowHTML('    <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($RS1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        ShowHTML('    <a accesskey="C" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
      } else {
        ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      } 
    } 
    if (strpos(strtoupper($R),'GR_')===false && strpos(strtoupper($R),'PROJETO')===false) {
      if ($w_copia>'') {
        // Se for cópia
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
    ShowHTML('          <td><b>'.LinkOrdena('Nº','codigo_interno').'</td>');
    if (Nvl($_REQUEST['p_agrega'],'')=='GRPDACAO') {
      ShowHTML('          <td><b>'.LinkOrdena('Ação','codigo_acao').'</td>');
    } 
    ShowHTML('          <td><b>'.LinkOrdena('Proposto','nm_prop').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Proponente','sg_unidade_resp').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Início','inicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Fim','fim').'</td>');
    if ($P1>1) {
      ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
    } 
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
        if (Nvl($_REQUEST['p_agrega'],'')=='GRPDACAO') {
          ShowHTML('        <td align="center">'.f($row,'codigo_acao'));
        } 
        ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'sq_prop'),$TP,f($row,'nm_prop')).'</td>');
        ShowHTML('        <td>'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade_resp'),$TP).'</td>');
        if (f($row,'sg_tramite')=='AT') {
          ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'inicio_real')),'-').'</td>');
          ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim_real')),'-').'</td>');
          if ($P1>1) {
            ShowHTML('        <td align="right">'.formatNumber(f($row,'custo_real')).'&nbsp;</td>');
            $w_parcial += f($row,'custo_real');
            ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
          } 
        } else {
          ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'inicio')),'-').'</td>');
          ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>');
          if ($P1>1) {
            ShowHTML('        <td align="right">'.formatNumber(f($row,'valor')).'&nbsp;</td>');
            $w_parcial += f($row,'valor');
            ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
          } 
        } 
        ShowHTML('        <td align="top" nowrap>');
        if ($P1!=3 && $P1!=5 && $P1!=6) {
          // Se não for acompanhamento
          if ($w_copia>'') {
            // Se for listagem para cópia
            $RS = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
            foreach($RS as $row1) { $RS = $row1; break; }
            ShowHTML('          <a accesskey="I" class="HL" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
          } elseif ($P1==1) {
            // Se for cadastramento
            if ($w_submenu>'') {
              ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'codigo_interno').MontaFiltro('GET').'" title="Altera as informações cadastrais da PCD" TARGET="menu">AL</a>&nbsp;');
            } else {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais da PCD">AL</A>&nbsp');
            } 
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão da PCD.">EX</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento da PCD.">EN</A>&nbsp');
          } elseif ($P1==2) {
            // Se for execução
            if (f($row,'sg_tramite')=='DF') {
              ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'DadosFinanceiros&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Dados Financeiros&SG=DADFIN').'\',\'Financeiro\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informar os dados financeiros da viagem.">Diárias</A>&nbsp');
            } elseif (f($row,'sg_tramite')=='AE') {
              ShowHTML('          <a target="Emissao" class="hl" title="Emitir autorização e proposta de concessão." href="'.$w_dir.$w_pagina.'Emissao&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'">Emitir</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'InformarPassagens&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Informar dados das passagens&SG=INFPASS').'\',\'Passagens\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informar os dados das passagens.">Informar</A>&nbsp');
            } elseif (f($row,'sg_tramite')=='EE') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a pcd, sem enviá-la.">AN</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Prestacaocontas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Financeiro\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Emitir relatório para prestacao de contas.">Relatório</A>&nbsp');
            } 
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a PCD para outro responsável.">EN</A>&nbsp');
            if (f($row,'sg_tramite')=='EE') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução da pcd.">CO</A>&nbsp');
            } 
          } 
        } else {
          if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a PCD para outro responsável.">EN</A>&nbsp');
          } else {
            ShowHTML('          ---&nbsp');
          } 
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
      if (Nvl($_REQUEST['p_agrega'],'')=='GRPDACAO') $w_colspan=6; else $w_colspan=5;
      if ($P1!=1 && $P1!=2) {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan='.$w_colspan.' align="right"><b>Total desta página&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.formatNumber($w_parcial).'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          reset($RS);
          foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') {
              $w_total += f($row,'custo_real');
            } else {
              $w_total += f($row,'valor');
            } 
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan='.$w_colspan.' align="right"><b>Total da listagem&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.formatNumber($w_total).'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
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
      // Se for cópia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar a PCD que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    ShowHTML('      <tr><td valign="top" colspan="2">');
    ShowHTML('        <table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr>');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto da PCD na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',null);
    ShowHTML('          <tr>');
    SelecaoEtapa('Eta<u>p</u>a:','P','Se necessário, indique a etapa à qual esta atividade deve ser vinculada.',$p_atividade,$p_projeto,null,'p_atividade',null,null);
    ShowHTML('          </tr>');
    ShowHTML('        </table></td></tr>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
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
      SelecaoEstado('E<u>s</u>tado destino:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade destino:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('   <tr>');
      SelecaoTipoPCD('Ti<u>p</u>o:','P',null,$p_ativo,'p_ativo',null,null);
      SelecaoCiaTrans('Cia. Via<u>g</u>em','R','Selecione a companhia de transporte desejada.',$w_cliente,$p_usu_resp,null,'p_usu_resp','S',null);
      ShowHTML('   <tr>');
      ShowHTML('     <td valign="top"><b>Pri<u>m</u>eira saída e Último retorno:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('<tr>');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('        <td valign="top"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for cópia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar cópia">');
    } else {
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
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

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    if (Nvl($_REQUEST['w_cpf'],'')>'') {
      // Recupera os dados do proponente
      $RS = db_getBenef::getInstanceOf($dbms,$w_cliente,null,null,$_REQUEST['w_cpf'],null,null,1,null,null,null,null,null,null,null);
      if (count($RS)>0) {
        foreach($RS as $row) { $RS = $row; break; }
        $w_cpf          = f($RS,'cpf');
        $w_sq_prop      = f($RS,'sq_pessoa');
        $w_nm_prop      = f($RS,'nm_pessoa');
        $w_nm_prop_res  = f($RS,'nome_resumido');
        $w_sexo         = f($RS,'sexo');
        $w_vinculo      = f($RS,'sq_tipo_vinculo');
      } else {
        $w_cpf          = $_REQUEST['w_cpf'];
        $w_sq_prop      = '';
        $w_nm_prop      = '';
        $w_nm_prop_res  = '';
        $w_sexo         = '';
        $w_vinculo      = '';
      }  
    } 
    $w_sq_unidade_resp  = $_REQUEST['w_sq_unidade_resp'];
    $w_assunto          = $_REQUEST['w_assunto'];
    $w_prioridade       = $_REQUEST['w_prioridade'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_inicio_atual     = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
    $w_atividade        = $_REQUEST['w_atividade'];
    $w_chave_pai        = $_REQUEST['w_chave_pai'];
    $w_chave_aux        = $_REQUEST['w_chave_aux'];
    $w_sq_menu          = $_REQUEST['w_sq_menu'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite       = $_REQUEST['w_sq_tramite'];
    $w_solicitante      = $_REQUEST['w_solicitante'];
    $w_cadastrador      = $_REQUEST['w_cadastrador'];
    $w_executor         = $_REQUEST['w_executor'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_justif_dia_util  = $_REQUEST['w_justif_dia_util'];
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_inclusao         = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao = $_REQUEST['w_ultima_alteracao'];
    $w_conclusao        = $_REQUEST['w_conclusao'];
    $w_opiniao          = $_REQUEST['w_opiniao'];
    $w_data_hora        = $_REQUEST['w_data_hora'];
    $w_uf               = $_REQUEST['w_uf'];
    $w_tipo_missao      = $_REQUEST['w_tipo_missao'];
  } else {
    if (strpos('AEV',$O)!==false || $w_copia>'') {
      // Recupera os dados da PCD
      if ($w_copia>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG);
      } else {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
      } 
      if (count($RS)>0) {
        $w_sq_unidade_resp  = f($RS,'sq_unidade_resp');
        $w_assunto          = f($RS,'assunto');
        $w_prioridade       = f($RS,'prioridade');
        $w_aviso            = f($RS,'aviso_prox_conc');
        $w_dias             = f($RS,'dias_aviso');
        $w_inicio_real      = f($RS,'inicio_real');
        $w_fim_real         = f($RS,'fim_real');
        $w_concluida        = f($RS,'concluida');
        $w_data_conclusao   = f($RS,'data_conclusao');
        $w_nota_conclusao   = f($RS,'nota_conclusao');
        $w_custo_real       = f($RS,'custo_real');
        $w_chave_pai        = f($RS,'sq_solic_pai');
        $w_chave_aux        = null;
        $w_sq_menu          = f($RS,'sq_menu');
        $w_sq_unidade       = f($RS,'sq_unidade');
        $w_sq_tramite       = f($RS,'sq_siw_tramite');
        $w_solicitante      = f($RS,'solicitante');
        $w_cadastrador      = f($RS,'cadastrador');
        $w_executor         = f($RS,'executor');
        $w_descricao        = f($RS,'descricao');
        $w_tipo_missao      = f($RS,'tp_missao');
        $w_justif_dia_util  = f($RS,'justificativa_dia_util');
        $w_inicio           = FormataDataEdicao(f($RS,'inicio'));
        if (strpos('AEV',$O)!==false) {
          $w_inicio_atual   = FormataDataEdicao(f($RS,'inicio'));
        } 
        $w_fim              = FormataDataEdicao(f($RS,'fim'));
        $w_inclusao         = f($RS,'inclusao');
        $w_ultima_alteracao = f($RS,'ultima_alteracao');
        $w_conclusao        = f($RS,'conclusao');
        $w_opiniao          = f($RS,'opiniao');
        $w_data_hora        = f($RS,'data_hora');
        $w_cpf              = f($RS,'cpf');
        $w_nm_prop          = f($RS,'nm_prop');
        $w_nm_prop_res      = f($RS,'nm_prop_res');
        $w_sexo             = f($RS,'sexo');
        $w_vinculo          = f($RS,'sq_tipo_vinculo');
        $w_uf               = f($RS,'co_uf');
        $w_sq_prop          = f($RS,'sq_prop');
      } 
    } 
  } 

  // Se não puder cadastrar para outros, carrega os dados do usuário logado
  if ($w_cadgeral=='N') {
    $RS = db_getBenef::getInstanceOf($dbms,$w_cliente,null,$_SESSION['USERNAME'],null,null,null,1,null,null,null,null,null,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_cpf            = f($RS,'cpf');
      $w_sq_prop        = f($RS,'sq_pessoa');
      $w_nm_prop        = f($RS,'nm_pessoa');
      $w_nm_prop_res    = f($RS,'nome_resumido');
      $w_sexo           = f($RS,'sexo');
      $w_vinculo        = f($RS,'sq_tipo_vinculo');
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
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
    Validate('w_descricao','Descrição','1',1,5,2000,'1','1');
    if ($w_cadgeral=='S') {
      Validate('w_sq_unidade_resp','Unidade proponente','SELECT',1,1,18,'','0123456789');
    } 
    Validate('w_tipo_missao','Tipo da PCD','SELECT',1,1,1,'1','');
    Validate('w_inicio','Primeira saída','DATA',1,10,10,'','0123456789/');
    Validate('w_fim','Último retorno','DATA',1,10,10,'','0123456789/');
    CompData('w_inicio','Início previsto','<=','w_fim','Fim previsto');
    Validate('w_justif_dia_util','Justificativa','1','',5,2000,'1','1');
    ShowHTML('  var w_data, w_data1, w_data2;');
    ShowHTML('  w_data = theForm.w_inicio.value;');
    ShowHTML('  w_data = w_data.substr(3,2) + \'/\' + w_data.substr(0,2) + \'/\' + w_data.substr(6,4);');
    ShowHTML('  w_data1  = new Date(Date.parse(w_data));');
    ShowHTML('  if ((w_data1.getDay() == 0 || w_data1.getDay() == 5 || w_data1.getDay() == 6) && theForm.w_justif_dia_util.value==\'\') {');
    ShowHTML('     alert(\'É necessário justificar o início de viagens em sextas-feiras, sábados, domingos e feriados!\');');
    ShowHTML('     theForm.w_inicio.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  w_data = theForm.w_fim.value;');
    ShowHTML('  w_data = w_data.substr(3,2) + \'/\' + w_data.substr(0,2) + \'/\' + w_data.substr(6,4);');
    ShowHTML('  w_data2  = new Date(Date.parse(w_data));');
    ShowHTML('  if ((w_data2.getDay() == 0 || w_data2.getDay() == 5 || w_data2.getDay() == 6) && theForm.w_justif_dia_util.value==\'\') {');
    ShowHTML('     alert(\'É necessário justificar o término de viagens em sextas-feiras, sábados, domingos e feriados!\');');
    ShowHTML('     theForm.w_fim.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    if ($O=='I') {
      if ($w_cadgeral=='S') {
        Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
        if ($w_sq_prop>'') {
          if (Nvl($w_sexo,'')=='') {
            Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
          } 
          if (Nvl($w_vinculo,'')=='') {
            Validate('w_vinculo','Tipo de vínculo','SELECT',1,1,18,'','1');
          } 
        } else {
          Validate('w_nm_prop','Nome do proposto','1',1,5,60,'1','1');
          Validate('w_nm_prop_res','Nome resumido do proposto','1',1,2,15,'1','1');
          Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
          Validate('w_vinculo','Tipo de vínculo','SELECT',1,1,18,'','1');
        } 
      } else {
        if ($w_sexo=='') {
          Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
        } 
      } 
    } 
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'this.focus()\';');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpen('onLoad=\'this.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro=Validacao($w_sq_solicitacao,$sg);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_inicio_atual" value="'.$w_inicio_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_atividade_ant" value="'.$w_atividade_ant.'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="N">');
    ShowHTML('<INPUT type="hidden" name="w_sq_prop" value="'.$w_sq_prop.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para identificação da PCD, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b>Des<u>c</u>rição sucinta do serviço a ser executado (Objetivo/assunto a ser tratado/evento):</b><br><textarea '.$w_Disabled.' accesskey="c" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva, de forma detalhada, os objetivos da PCD.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($w_sq_unidade_resp=='') {
      // Recupera todos os registros para a listagem
      $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$_SESSION['LOTACAO'],'VIAGEMUNID',null,null,$w_ano);
      if (!count($RS)<=0) {
        foreach($RS as $row) { $RS = $row; break; }
        $w_sq_unidade_resp = f($RS,'sq_unidade');
        if ($w_cadgeral=='N') {
          ShowHTML('<INPUT type="hidden" name="w_sq_unidade_resp" value="'.$w_sq_unidade_resp.'">');
        } else {
          SelecaoUnidade('<U>U</U>nidade proponente:','U','Selecione a unidade proponente da PCD',$w_sq_unidade_resp,null,'w_sq_unidade_resp','VIAGEM',null);
        } 
      } else {
        if ($w_cadgeral=='N') {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Sua lotação não está ligada a nenhuma unidade proponente. Entre em contato com os gestores do sistema!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
        } else {
          SelecaoUnidade('<U>U</U>nidade proponente:','U','Selecione a unidade proponente da PCD',$w_sq_unidade_resp,null,'w_sq_unidade_resp','VIAGEM',null);
        } 
      } 
    } else {
      if ($w_cadgeral=='N') {
        ShowHTML('<INPUT type="hidden" name="w_sq_unidade_resp" value="'.$w_sq_unidade_resp.'">');
      } else {
        SelecaoUnidade('<U>U</U>nidade proponente:','U','Selecione a unidade proponente da PCD',$w_sq_unidade_resp,null,'w_sq_unidade_resp','VIAGEM',null);
      } 
    } 
    SelecaoTipoPCD('Ti<u>p</u>o:','P',null,$w_tipo_missao,'w_tipo_missao',null,null);
    ShowHTML('              <td valign="top"><b>Pri<u>m</u>eira saída:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa">'.ExibeCalendario('Form','w_inicio').'</td>');
    ShowHTML('              <td valign="top"><b>Último re<u>t</u>orno:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa">'.ExibeCalendario('Form','w_fim').'</td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b><u>J</u>ustificativa para início e término de viagens em sextas-feiras, sábados, domingos e feriados:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justif_dia_util" class="STI" ROWS=5 cols=75 title="É obrigatório justificar, neste campo, início ou término de viagens sextas-feiras, sábados, domingos e feriados. Caso contrário, deixe este campo em branco.">'.$w_justif_dia_util.'</TEXTAREA></td>');
    if ($O=='I') {
      if ($w_cadgeral=='S') {
        ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Dados do Proposto</td></td></tr>');
        ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td>Insira abaixo os dados do proposto. Após a gravação serão solicitados dados complementares sobre ele.</td></tr>');
        ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('        <tr valign="top">');
        ShowHTML('            <td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);" onBlur="botoes(); document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nm_prop\'; document.Form.submit();">');
        if ($w_sq_prop>'') {
          ShowHTML('            <td>Nome completo:<b><br>'.$w_nm_prop.'</td>');
          ShowHTML('            <td>Nome resumido:<b><br>'.$w_nm_prop_res.'</td>');
          if (Nvl($w_sexo,'')=='') {
            SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
          } else {
            ShowHTML('<INPUT type="hidden" name="w_sexo" value="'.$w_sexo.'">');
          } 
          if (Nvl($w_vinculo,'')=='') {
            SelecaoVinculo('Tipo de <u>v</u>ínculo:','V',null,$w_vinculo,null,'w_vinculo','S','Física',null);
          } else {
            ShowHTML('<INPUT type="hidden" name="w_vinculo" value="'.$w_vinculo.'">');
          } 
        } else {
          ShowHTML('            <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nm_prop" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nm_prop.'"></td>');
          ShowHTML('            <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nm_prop_res" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_nm_prop_res.'"></td>');
          SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
          SelecaoVinculo('Tipo de <u>v</u>ínculo:','V',null,$w_vinculo,null,'w_vinculo','S','Física',null);
        } 
        ShowHTML('          </table>');
      } else {
        if ($w_sexo=='N') {
          ShowHTML('<INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
          ShowHTML('<INPUT type="hidden" name="w_nm_prop" value="'.$w_nm_prop.'">');
          ShowHTML('<INPUT type="hidden" name="w_nm_prop_res" value="'.$w_nm_prop_res.'">');
          ShowHTML('<INPUT type="hidden" name="w_vinculo" value="'.$w_vinculo.'">');
          ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Dados do Proposto</td></td></tr>');
          ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td>Confirme os dados abaixo, informando ou alterando o sexo, se necessário. Após a gravação serão solicitados dados complementares sobre ele.</td></tr>');
          ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('        <tr valign="top">');
          ShowHTML('            <td>CPF:<b><br><font size="2">'.$w_cpf.'</b></td>');
          ShowHTML('            <td>Nome completo:<b><br><font size="2">'.$w_nm_prop.'</td>');
          ShowHTML('            <td>Nome resumido:<b><br><font size="2">'.$w_nm_prop_res.'</td>');
          SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
          ShowHTML('          </table>');
        } else {
          ShowHTML('<INPUT type="hidden" name="w_sexo" value="'.$w_sexo.'">');
        } 
      } 
    } 
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
      $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
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
// Rotina de cadastramento da outra parte
// -------------------------------------------------------------------------
function OutraParte() {
  extract($GLOBALS);
  global $w_Disabled;

  if ($O=='') $O='P';

  $w_erro           = '';
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_cpf            = $_REQUEST['w_cpf'];
  $w_cnpj           = $_REQUEST['w_cnpj'];
  $w_sq_pessoa      = $_REQUEST['w_sq_pessoa'];
  $w_pessoa_atual   = $_REQUEST['w_pessoa_atual'];

  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  if ($w_sq_pessoa=='' && strpos($_REQUEST['Botao'],'Selecionar')===false) {
    $w_sq_pessoa    = f($RS,'sq_prop');
    $w_pessoa_atual = f($RS,'sq_prop');
  } elseif (strpos($_REQUEST['Botao'],'Selecionar')===false) {
    $w_sq_banco         = f($RS,'sq_banco');
    $w_sq_agencia       = f($RS,'sq_agencia');
    $w_operacao         = f($RS,'operacao_conta');
    $w_nr_conta         = f($RS,'numero_conta');
    $w_sq_pais_estrang  = f($RS,'sq_pais_estrang');
    $w_aba_code         = f($RS,'aba_code');
    $w_swift_code       = f($RS,'swift_code');
    $w_endereco_estrang = f($RS,'endereco_estrang');
    $w_banco_estrang    = f($RS,'banco_estrang');
    $w_agencia_estrang  = f($RS,'agencia_estrang');
    $w_cidade_estrang   = f($RS,'cidade_estrang');
    $w_informacoes      = f($RS,'informacoes');
    $w_codigo_deposito  = f($RS,'codigo_deposito');
  } 
  $w_forma_pagamento    = 'CREDITO';
  $w_sq_tipo_pessoa     = 1;
  if (Nvl($w_sq_pessoa,0)==0) $O='I'; else $O='A';
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_chave                = $_REQUEST['w_chave'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_nome_resumido        = $_REQUEST['w_nome_resumido'];
    $w_sq_pessoa_pai        = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa       = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo      = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo      = $_REQUEST['w_nm_tipo_vinculo'];
    $w_sq_banco             = $_REQUEST['w_sq_banco'];
    $w_sq_agencia           = $_REQUEST['w_sq_agencia'];
    $w_operacao             = $_REQUEST['w_operacao'];
    $w_nr_conta             = $_REQUEST['w_nr_conta'];
    $w_sq_pais_estrang      = $_REQUEST['w_sq_pais_estrang'];
    $w_aba_code             = $_REQUEST['w_aba_code'];
    $w_swift_code           = $_REQUEST['w_swift_code'];
    $w_endereco_estrang     = $_REQUEST['w_endereco_estrang'];
    $w_banco_estrang        = $_REQUEST['w_banco_estrang'];
    $w_agencia_estrang      = $_REQUEST['w_agencia_estrang'];
    $w_cidade_estrang       = $_REQUEST['w_cidade_estrang'];
    $w_informacoes          = $_REQUEST['w_informacoes'];
    $w_codigo_deposito      = $_REQUEST['w_codigo_deposito'];
    $w_interno              = $_REQUEST['w_interno'];
    $w_vinculo_ativo        = $_REQUEST['w_vinculo_ativo'];
    $w_sq_pessoa_telefone   = $_REQUEST['w_sq_pessoa_telefone'];
    $w_ddd                  = $_REQUEST['w_ddd'];
    $w_nr_telefone          = $_REQUEST['w_nr_telefone'];
    $w_sq_pessoa_celular    = $_REQUEST['w_sq_pessoa_celular'];
    $w_nr_celular           = $_REQUEST['w_nr_celular'];
    $w_sq_pessoa_fax        = $_REQUEST['w_sq_pessoa_fax'];
    $w_nr_fax               = $_REQUEST['w_nr_fax'];
    $w_email                = $_REQUEST['w_email'];
    $w_sq_pessoa_endereco   = $_REQUEST['w_sq_pessoa_endereco'];
    $w_logradouro           = $_REQUEST['w_logradouro'];
    $w_complemento          = $_REQUEST['w_complemento'];
    $w_bairro               = $_REQUEST['w_bairro'];
    $w_cep                  = $_REQUEST['w_cep'];
    $w_sq_cidade            = $_REQUEST['w_sq_cidade'];
    $w_co_uf                = $_REQUEST['w_co_uf'];
    $w_sq_pais              = $_REQUEST['w_sq_pais'];
    $w_pd_pais              = $_REQUEST['w_pd_pais'];
    $w_cpf                  = $_REQUEST['w_cpf'];
    $w_nascimento           = $_REQUEST['w_nascimento'];
    $w_rg_numero            = $_REQUEST['w_rg_numero'];
    $w_rg_emissor           = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao           = $_REQUEST['w_rg_emissao'];
    $w_matricula            = $_REQUEST['w_matricula'];
    $w_sq_pais_passaporte   = $_REQUEST['w_sq_pais_passaporte'];
    $w_sexo                 = $_REQUEST['w_sexo'];
    $w_cnpj                 = $_REQUEST['w_cnpj'];
    $w_inscricao_estadual   = $_REQUEST['w_inscricao_estadual'];
  } else {
    if (strpos($_REQUEST['Botao'],'Alterar')===false && strpos($_REQUEST['Botao'],'Procurar')===false && ($O=='A' || $w_sq_pessoa>'' || $w_cpf>'' || $w_cnpj>'')) {
      // Recupera os dados do beneficiário em co_pessoa
      $RS = db_getBenef::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,$w_cpf,$w_cnpj,null,null,null,null,null,null,null,null,null);
      if (!count($RS)<=0) {
        foreach($RS as $row) { $RS = $row; break; }
        $w_sq_pessoa            = f($RS,'sq_pessoa');
        $w_nome                 = f($RS,'nm_pessoa');
        $w_nome_resumido        = f($RS,'nome_resumido');
        $w_sq_pessoa_pai        = f($RS,'sq_pessoa_pai');
        $w_nm_tipo_pessoa       = f($RS,'nm_tipo_pessoa');
        $w_sq_tipo_vinculo      = f($RS,'sq_tipo_vinculo');
        $w_nm_tipo_vinculo      = f($RS,'nm_tipo_vinculo');
        $w_interno              = f($RS,'interno');
        $w_vinculo_ativo        = f($RS,'vinculo_ativo');
        $w_sq_pessoa_telefone   = f($RS,'sq_pessoa_telefone');
        $w_ddd                  = f($RS,'ddd');
        $w_nr_telefone          = f($RS,'nr_telefone');
        $w_sq_pessoa_celular    = f($RS,'sq_pessoa_celular');
        $w_nr_celular           = f($RS,'nr_celular');
        $w_sq_pessoa_fax        = f($RS,'sq_pessoa_fax');
        $w_nr_fax               = f($RS,'nr_fax');
        $w_email                = f($RS,'email');
        $w_sq_pessoa_endereco   = f($RS,'sq_pessoa_endereco');
        $w_logradouro           = f($RS,'logradouro');
        $w_complemento          = f($RS,'complemento');
        $w_bairro               = f($RS,'bairro');
        $w_cep                  = f($RS,'cep');
        $w_sq_cidade            = f($RS,'sq_cidade');
        $w_co_uf                = f($RS,'co_uf');
        $w_sq_pais              = f($RS,'sq_pais');
        $w_pd_pais              = f($RS,'pd_pais');
        $w_cpf                  = f($RS,'cpf');
        $w_nascimento           = FormataDataEdicao(f($RS,'nascimento'));
        $w_rg_numero            = f($RS,'rg_numero');
        $w_rg_emissor           = f($RS,'rg_emissor');
        $w_rg_emissao           = FormataDataEdicao(f($RS,'rg_emissao'));
        $w_matricula            = f($RS,'passaporte_numero');
        $w_sq_pais_passaporte   = f($RS,'sq_pais_passaporte');
        $w_sexo                 = f($RS,'sexo');
        $w_cnpj                 = f($RS,'cnpj');
        $w_inscricao_estadual   = f($RS,'inscricao_estadual');
        if (Nvl($w_nr_conta,'')=='') {
          $w_sq_banco           = f($RS,'sq_banco');
          $w_sq_agencia         = f($RS,'sq_agencia');
          $w_operacao           = f($RS,'operacao');
          $w_nr_conta           = f($RS,'nr_conta');
        } 
      } 
    } 
  } 

  // Recupera informação do campo operação do banco selecionado
  if (nvl($w_sq_banco,'')>'') {
    $RS_Banco = db_getBankData::getInstanceOf($dbms, $w_sq_banco);
    $w_exige_operacao = f($RS_Banco,'exige_operacao');
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  FormataCNPJ();
  FormataCEP();
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (($w_cpf=='' && $w_cnpj=='') || strpos($_REQUEST['Botao'],'Procurar')!==false || strpos($_REQUEST['Botao'],'Alterar')!==false) {
    // Se o beneficiário ainda não foi selecionado
    ShowHTML('  if (theForm.Botao.value == "Procurar") {');
    Validate('w_nome','Nome','','1','4','20','1','');
    ShowHTML('  theForm.Botao.value = "Procurar";');
    ShowHTML('}');
    ShowHTML('else {');
    Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
    ShowHTML('  theForm.w_sq_pessoa.value = \'\';');
    ShowHTML('}');
  } elseif ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value.indexOf(\'Alterar\') >= 0) { return true; }');
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    Validate('w_nome_resumido','Nome resumido','1',1,2,15,'1','1');
    Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
    if ($w_sq_tipo_vinculo=='') {
      Validate('w_sq_tipo_vinculo','Tipo de vínculo','SELECT',1,1,1,'','1');
    } 
    Validate('w_matricula','Matrícula','1','',1,20,'1','1');
    Validate('w_rg_numero','Identidade','1',1,2,30,'1','1');
    Validate('w_rg_emissao','Data de emissão','DATA','',10,10,'','0123456789/');
    Validate('w_rg_emissor','Órgão expedidor','1',1,2,30,'1','1');
    Validate('w_ddd','DDD','1','1',3,4,'','0123456789');
    Validate('w_nr_telefone','Telefone','1',1,7,25,'1','1');
    Validate('w_nr_fax','Fax','1','',7,25,'1','1');
    Validate('w_nr_celular','Celular','1','',7,25,'1','1');
    if (strpos('CREDITO,DEPOSITO',$w_forma_pagamento)!==false) {
      Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
      Validate('w_sq_agencia','Agencia','SELECT',1,1,10,'1','1');
      if ($w_exige_operacao=='S') Validate('w_operacao','Operação','1','1',1,6,'','0123456789');
      Validate('w_nr_conta','Número da conta','1','1',2,30,'ZXAzxa','0123456789-');
    } elseif ($w_forma_pagamento=='ORDEM') {
      Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
      Validate('w_sq_agencia','Agencia','SELECT',1,1,10,'1','1');
    } elseif ($w_forma_pagamento=='EXTERIOR') {
      Validate('w_banco_estrang','Banco de destino','1','1',1,60,1,1);
      Validate('w_aba_code','Código ABA','1','',1,12,1,1);
      Validate('w_swift_code','Código SWIFT','1','',1,30,'',1);
      Validate('w_endereco_estrang','Endereço da agência destino','1','',3,100,1,1);
      ShowHTML('  if (theForm.w_aba_code.value == \'\' && theForm.w_swift_code.value == \'\' && theForm.w_endereco_estrang.value == \'\') {');
      ShowHTML('     alert(\'Informe código ABA, código SWIFT ou endereço da agência!\');');
      ShowHTML('     document.Form.w_aba_code.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_agencia_estrang','Nome da agência destino','1','1',1,60,1,1);
      Validate('w_nr_conta','Número da conta','1',1,1,10,1,1);
      Validate('w_cidade_estrang','Cidade da agência','1','1',1,60,1,1);
      Validate('w_sq_pais_estrang','País da agência','SELECT','1',1,18,1,1);
      Validate('w_informacoes','Informações adicionais','1','',5,200,1,1);
    } 
    if ($w_cadgeral=='S') {
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (($w_cpf=='' && $w_cnpj=='') || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
    // Se o beneficiário ainda não foi selecionado
    if (strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se está sendo feita busca por nome
      BodyOpenClean('onLoad=\'this.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.w_cpf.focus()\';');
    } 
  } elseif ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IA',$O)!==false) {
    if (($w_cpf=='' && $w_cnpj=='') || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se o beneficiário ainda não foi selecionado
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    } else {
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    } 
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="'.$w_pessoa_atual.'">');
    if (($w_cpf=='' && $w_cnpj=='') || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
      $w_nome=$_REQUEST['w_nome'];
      if (strpos($_REQUEST['Botao'],'Alterar')!==false) {
        $w_cpf  = '';
        $w_cnpj = '';
        $w_nome = '';
      } 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('        <tr><td colspan=4>Informe os dados abaixo e clique no botão "Selecionar" para continuar.</TD>');
      ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
      ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
      ShowHTML('        <tr><td colspan=4>');
      ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
      ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('      </table>');
      if ($w_nome>'') {
        $RS = db_getBenef::getInstanceOf($dbms,$w_cliente,null,null,null,null,$w_nome,1,null,null,null,null,null,null,null);
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('          <td><b>Nome resumido</td>');
        ShowHTML('          <td><b>CPF</td>');
        ShowHTML('          <td><b>Operações</td>');
        ShowHTML('        </tr>'); 
        if (count($RS)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não há pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
            ShowHTML('        <td nowrap>');
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          } 
        } 
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      } 
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td>CPF:<br><b><font size=2>'.$w_cpf);
      ShowHTML('              <INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
      ShowHTML('          <tr valign="top">');
      ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_nome_resumido.'"></td>');
      SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
      if (Nvl($w_sq_tipo_vinculo,'')=='') {
        SelecaoVinculo('Tipo de <u>v</u>ínculo:','V',null,$w_sq_tipo_vinculo,null,'w_sq_tipo_vinculo','S','Física',null);
      } else {
        ShowHTML('<INPUT type="hidden" name="w_sq_tipo_vinculo" value="'.$w_sq_tipo_vinculo.'">');
      } 
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td title="Informe este campo apenas se o proposto tiver matrícula. Caso contrário, deixe-o em branco."><b><u>M</u>atrícula:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_matricula" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_matricula.'"></td>');
      ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
      ShowHTML('          <td><b>Data de <u>e</u>missão:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b>Ór<u>g</u>ão emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
      ShowHTML('          </table>');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Telefones</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ddd.'"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_nr_telefone.'"> '.consultaTelefone($w_cliente).'</td>');
      ShowHTML('          <td title="Se a outra parte informar um número de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_fax.'"></td>');
      ShowHTML('          <td title="Se a outra parte informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_celular.'"></td>');
      ShowHTML('          </table>');
      if (strpos('CREDITO,DEPOSITO',$w_forma_pagamento)!==false) {
        ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados bancários</td></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('      <tr valign="top">');
        SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde deverão ser feitos os pagamentos referentes ao acordo.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
        SelecaoAgencia('A<u>g</u>ência:','A','Selecione a agência onde deverão ser feitos os pagamentos referentes ao acordo.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
        ShowHTML('      <tr valign="top">');
        if ($w_exige_operacao=='S') ShowHTML('          <td title="Alguns bancos trabalham com o campo "Operação", além do número da conta. A Caixa Econômica Federal é um exemplo. Se for o caso,informe a operação neste campo; caso contrário, deixe-o em branco."><b>O<u>p</u>eração:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_operacao" class="sti" SIZE="6" MAXLENGTH="6" VALUE="'.$w_operacao.'"></td>');
        ShowHTML('          <td title="Informe o número da conta bancária, colocando o dígito verificador, se existir, separado por um hífen. Exemplo: 11214-3. Se o banco não trabalhar com dígito verificador, informe apenas números. Exemplo: 10845550."><b>Número da con<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nr_conta.'"></td>');
        ShowHTML('          </table>');
      } elseif ($w_forma_pagamento=='ORDEM') {
        ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados para Ordem Bancária</td></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('      <tr valign="top">');
        SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde deverão ser feitos os pagamentos referentes ao acordo.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
       SelecaoAgencia('A<u>g</u>ência:','A','Selecione a agência onde deverão ser feitos os pagamentos referentes ao acordo.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
      } elseif ($w_forma_pagamento=='EXTERIOR') {
        ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados da conta no exterior</td></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2"><b><font color="#BC3131">ATENÇÃO:</b> É obrigatório o preenchimento de um destes campos: Swift Code, ABA Code ou Endereço da Agência.</font></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('      <tr valign="top">');
        ShowHTML('          <td title="Banco onde o crédito deve ser efetuado."><b><u>B</u>anco de crédito:</b><br><input '.$w_Disabled.' accesskey="B" type="text" name="w_banco_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_banco_estrang.'"></td>');
        ShowHTML('          <td title="Código ABA da agência destino."><b>A<u>B</u>A code:</b><br><input '.$w_Disabled.' accesskey="B" type="text" name="w_aba_code" class="sti" SIZE="12" MAXLENGTH="12" VALUE="'.$w_aba_code.'"></td>');
        ShowHTML('          <td title="Código SWIFT da agência destino."><b>S<u>W</u>IFT code:</b><br><input '.$w_Disabled.' accesskey="W" type="text" name="w_swift_code" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_swift_code.'"></td>');
        ShowHTML('      <tr><td colspan=3 title="Endereço da agência."><b>E<u>n</u>dereço da agência:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_endereco_estrang" class="sti" SIZE="80" MAXLENGTH="100" VALUE="'.$w_endereco_estrang.'"></td>');
        ShowHTML('      <tr valign="top">');
        ShowHTML('          <td colspan=2 title="Nome da agência destino."><b>Nome da a<u>g</u>ência:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_agencia_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_agencia_estrang.'"></td>');
        ShowHTML('          <td title="Número da conta destino."><b>Número da con<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nr_conta.'"></td>');
        ShowHTML('      <tr valign="top">');
        ShowHTML('          <td colspan=2 title="Cidade da agência destino."><b><u>C</u>idade:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_cidade_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_cidade_estrang.'"></td>');
        SelecaoPais('<u>P</u>aís:','P','Selecione o país de destino',$w_sq_pais_estrang,null,'w_sq_pais_estrang',null,null);
        ShowHTML('          </table>');
        ShowHTML('      <tr><td colspan=2 title="Se necessário, escreva informações adicionais relevantes para o pagamento."><b>Info<u>r</u>mações adicionais:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_informacoes" class="sti" ROWS=3 cols=75 >'.$w_informacoes.'</TEXTAREA></td>');
      } 
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
      if ($w_cadgeral=='S') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Alterar proposto" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.submit();">');
      } 
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    } 
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina de cadastramento da trechos
// -------------------------------------------------------------------------
function Trechos() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro       = '';
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_inicio     = $_REQUEST['w_inicio'];
  $w_fim        = $_REQUEST['w_fim'];

  if (($O=='I' || $O=='A') && Nvl($w_inicio,'')=='') {
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
    $w_inicio = FormataDataEdicao(f($RS,'inicio'));
    $w_fim    = FormataDataEdicao(f($RS,'fim'));
  } 
  if ($w_troca>'') {
    // Se for recarga da página
    $w_pais_orig    = $_REQUEST['w_pais_orig'];
    $w_uf_orig      = $_REQUEST['w_uf_orig'];
    $w_cidade_orig  = $_REQUEST['w_cidade_orig'];
    $w_pais_dest    = $_REQUEST['w_pais_dest'];
    $w_uf_dest      = $_REQUEST['w_uf_dest'];
    $w_cidade_dest  = $_REQUEST['w_cidade_dest'];
    $w_data_saida   = $_REQUEST['w_data_saida'];
    $w_hora_saida   = $_REQUEST['w_hora_saida'];
    $w_data_chegada = $_REQUEST['w_data_chegada'];
    $w_hora_chegada = $_REQUEST['w_hora_chegada'];
  } elseif ($O=='L') {
    $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave,null,$SG);
    $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  } elseif (strpos('AE',$O)!==false) {
    $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave,$w_chave_aux,$SG);
    foreach($RS as $row) { $RS = $row; break; }
    $w_pais_orig    = f($RS,'pais_orig');
    $w_uf_orig      = f($RS,'uf_orig');
    $w_cidade_orig  = f($RS,'cidade_orig');
    $w_pais_dest    = f($RS,'pais_dest');
    $w_uf_dest      = f($RS,'uf_dest');
    $w_cidade_dest  = f($RS,'cidade_dest');
    $w_data_saida   = FormataDataEdicao(f($RS,'phpdt_saida'));
    $w_hora_saida   = substr(FormataDataEdicao(f($RS,'phpdt_saida'),2),0,5);
    $w_data_chegada = FormataDataEdicao(f($RS,'phpdt_chegada'));
    $w_hora_chegada = substr(FormataDataEdicao(f($RS,'phpdt_chegada'),2),0,5);
  } 
  if ($O=='I') {
    if ($w_pais_orig=='') {
      $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave,null,$SG);
      $RS1 = SortArray($RS1,'phpdt_saida','desc', 'phpdt_chegada', 'desc');
      if (count($RS1)==0) {
        // Carrega os valores padrão para país, estado e cidade
        $RS1 = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
        $w_pais_orig    = f($RS1,'sq_pais');
        $w_uf_orig      = f($RS1,'co_uf');
        $w_cidade_orig  = f($RS1,'sq_cidade_padrao');
        $w_pais_dest    = f($RS1,'sq_pais');
      } else {
        foreach($RS1 as $row) { $RS1 = $row; break; }
        // Carrega os valores da última saída
        $w_pais_orig    = f($RS1,'pais_dest');
        $w_uf_orig      = f($RS1,'uf_dest');
        $w_cidade_orig  = f($RS1,'cidade_dest');
        $w_pais_dest    = f($RS1,'pais_dest');
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataHora();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    Validate('w_pais_orig','País de origem','SELECT',1,1,18,'','1');
    Validate('w_uf_orig','UF de origem','SELECT',1,1,2,'1','');
    Validate('w_cidade_orig','Cidade de origem','SELECT',1,1,18,'','1');
    Validate('w_data_saida','Data de saída','DATA','1',10,10,'','0123456789/');
    CompData('w_data_saida','Data de saída','>=',$w_inicio,'início da missão ('.$w_inicio.'), informado na tela de dados gerais');
    CompData('w_data_saida','Data de saída','<=',$w_fim,'término da missão ('.$w_fim.'), informado na tela de dados gerais');
    Validate('w_hora_saida','Hora de saída','HORA','1',5,5,'','0123456789:');
    Validate('w_pais_dest','País de destino','SELECT',1,1,18,'','1');
    Validate('w_uf_dest','UF de destino','SELECT',1,1,2,'1','');
    Validate('w_cidade_dest','Cidade de destino','SELECT',1,1,18,'','1');
    Validate('w_data_chegada','Data de chegada','DATA','1',10,10,'','0123456789/');
    CompData('w_data_chegada','Data de chegada','>=',$w_inicio,'início da missão ('.$w_inicio.'), informado na tela de dados gerais');
    CompData('w_data_chegada','Data de chegada','<=',$w_fim,'término da missão ('.$w_fim.'), informado na tela de dados gerais');
    Validate('w_hora_chegada','Hora de chegada','HORA','1',5,5,'','0123456789:');
    ShowHTML('  if (theForm.w_pais_orig.selectedIndex == theForm.w_pais_dest.selectedIndex && theForm.w_uf_orig.selectedIndex == theForm.w_uf_dest.selectedIndex && theForm.w_cidade_orig.selectedIndex == theForm.w_cidade_dest.selectedIndex) {');
    ShowHTML('      alert(\'Cidades de origem e de destino não podem ser iguais!\'); ');
    ShowHTML('      theForm.w_cidade_dest.focus(); ');
    ShowHTML('      return (false); ');
    ShowHTML('  }');
    CompData('w_data_saida','Data de saída','<=','w_data_chegada','Data de chegada');
    ShowHTML('  if (theForm.w_data_saida.value == theForm.w_data_chegada.value) {');
    CompHora('w_hora_saida','Hora de saída','<','w_hora_chegada','Hora de chegada');
    ShowHTML('  }');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_pais_orig.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Origem</td>');
    ShowHTML('          <td><b>Destino</td>');
    ShowHTML('          <td><b>Saída</td>');
    ShowHTML('          <td><b>Chegada</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_origem').'</td>');
        ShowHTML('        <td>'.f($row,'nm_destino').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'phpdt_saida'),6).'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'phpdt_chegada'),6).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave_aux='.f($row,'sq_deslocamento').'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera os dados do trecho.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_chave_aux='.f($row,'sq_deslocamento').'&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão do trecho." onClick="return(confirm(\'Confirma exclusão do trecho?\'));">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
  } elseif (strpos('IA',$O)!==false) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Origem</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoPais('<u>P</u>aís:','P',null,$w_pais_orig,null,'w_pais_orig',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf_orig\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf_orig,$w_pais_orig,null,'w_uf_orig',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cidade_orig\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$w_cidade_orig,$w_pais_orig,$w_uf_orig,'w_cidade_orig',null,null);
    ShowHTML('          <td><b><u>S</u>aída:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_data_saida" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_saida.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> '.ExibeCalendario('Form','w_data_saida').'</td>');
    ShowHTML('          <td><b><u>H</u>ora local:</b><br><input '.$w_Disabled.' accesskey="H" type="text" name="w_hora_saida" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_hora_saida.'" onKeyDown="FormataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,5,event);" ></td>');
    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Destino</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoPais('<u>P</u>aís:','P',null,$w_pais_dest,null,'w_pais_dest',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf_dest\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf_dest,$w_pais_dest,null,'w_uf_dest',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cidade_dest\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$w_cidade_dest,$w_pais_dest,$w_uf_dest,'w_cidade_dest',null,null);
    ShowHTML('          <td><b><u>C</u>hegada:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_data_chegada" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_chegada.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" onFocus="if (document.Form.w_data_chegada.value==\'\') { document.Form.w_data_chegada.value = document.Form.w_data_saida.value; }"> '.ExibeCalendario('Form','w_data_chegada').'</td>');
    ShowHTML('          <td><b><u>H</u>ora local:</b><br><input '.$w_Disabled.' accesskey="H" type="text" name="w_hora_chegada" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_hora_chegada.'" onKeyDown="FormataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,5,event);" ></td>');
    ShowHTML('      <tr><td colspan="5"><table border="0" width="100%">');
    ShowHTML('      <tr><td align="center" colspan="5" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="5">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
    ShowHTML('              <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina de vinculação a Tarefas e Demandas
// -------------------------------------------------------------------------
function Vinculacao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro       = '';
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_operacao   = $_REQUEST['w_operacao'];
  $p_sigla      = Nvl($_REQUEST['p_sigla'],'GDPCAD');

  if ($O=='L') {
    $RS = db_getPD_Vinculacao::getInstanceOf($dbms,$w_chave,null,null);
    $RS = SortArray($RS,'inicio','asc');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  if ($O=='I') {
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataHora();
    ValidateOpen('Validacao');
    if ($p_sigla=='GDPCAD') {
      Validate('p_projeto','Projeto','SELECT','1','1','18','','0123456789');
    }
    Validate('p_chave','Número da demanda','','','1','18','','0123456789');
    Validate('p_proponente','Proponente externo','','','2','90','1','');
    Validate('p_assunto','Detalhamento','','','2','90','1','1');
    Validate('p_fim_i','Conclusão inicial','DATA','','10','10','','0123456789/');
    Validate('p_fim_f','Conclusão final','DATA','','10','10','','0123456789/');
    ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
    ShowHTML('     theForm.p_fim_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_fim_i','Conclusão inicial','<=','p_fim_f','Conclusão final');
    if ($p_sigla=='GDPCAD') {
      ShowHTML('  if (theForm.p_projeto.value==\'\' && theForm.p_atividade.value==\'\' && theForm.p_chave.value==\'\' && theForm.p_proponente.value==\'\' && theForm.p_assunto.value==\'\' && theForm.p_pais.value==\'\' && theForm.p_uf.value==\'\' && theForm.p_cidade.value==\'\' && theForm.p_fim_i.value==\'\' && theForm.p_fim_f.value==\'\') {');
    } else {
      ShowHTML('  if (theForm.p_chave.value==\'\' && theForm.p_proponente.value==\'\' && theForm.p_assunto.value==\'\' && theForm.p_pais.value==\'\' && theForm.p_uf.value==\'\' && theForm.p_cidade.value==\'\' && theForm.p_fim_i.value==\'\' && theForm.p_fim_f.value==\'\') {');
    }
    ShowHTML('     alert (\'Você deve informar algum critério de busca!\');');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  theForm.w_operacao.value=\'LISTA\';');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    if (Nvl($w_operacao,'')>'') {
      ValidateOpen('Validacao1');
      ShowHTML('  if (theForm.Botao.value==\'Procurar\') {');
      Validate('p_assunto','Detalhamento','','1','2','90','1','1');
      ShowHTML('  } else {');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_demanda[]"].value==undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_demanda[]"].length; i++) {');
      ShowHTML('       if (theForm["w_demanda[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_demanda[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      if ($p_sigla=='GDPCAD') {
        ShowHTML('    alert(\'Você deve selecionar pelo menos uma atividade!\'); ');
      } else {
        ShowHTML('    alert(\'Você deve selecionar pelo menos uma demanda eventual!\'); ');
      }
      ShowHTML('    return false;');
      ShowHTML('  }');
      ShowHTML('  }');
      ShowHTML('  theForm.Botao.disabled=true;');
      ValidateClose();
    } 
  } 
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' && Nvl($p_assunto,'')=='') {
    BodyOpenClean('onLoad=\'document.Form.p_assunto.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Nº</td>');
    ShowHTML('          <td><b>Projeto</td>');
    ShowHTML('          <td><b>Detalhamento</td>');
    ShowHTML('          <td><b>Início</td>');
    ShowHTML('          <td><b>Fim</td>');
    ShowHTML('          <td><b>Situação</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        if (f($row,'concluida')=='N') {
          if (f($row,'fim')<addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
          } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
            ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
          } 
        } else {
          if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
            ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
          } 
        } 
        if (nvl(f($row,'sq_projeto'),'')=='') {
          ShowHTML('        <A class="HL" TARGET="VISUAL" HREF="demanda.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>');
        } else {
          ShowHTML('        <A class="HL" TARGET="VISUAL" HREF="projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>');
        }
        ShowHTML('        <td>'.nvl(f($row,'nm_projeto'),'---').'</td>');
        if (strlen(Nvl(f($row,'assunto'),'-'))>50) $w_assunto=substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_assunto=Nvl(f($row,'assunto'),'-');
        if (f($row,'sg_tramite')=='CA') {
          ShowHTML('        <td title="'.str_replace('\\r\\n','\\n',str_replace('"','"',str_replace('\'','"',f($row,'assunto')))).'"><strike>'.$w_assunto.'</strike></td>');
        } else {
          ShowHTML('        <td title="'.str_replace('\\r\\n','\\n',str_replace('"','"',str_replace('\'','"',f($row,'assunto')))).'">'.$w_assunto.'</td>');
        } 
        if (f($row,'concluida')=='N') {
          ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>');
          ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'fim')).'</td>');
        } else {
          ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio_real')).'</td>');
          ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'fim_real')).'</td>');
        } 
        ShowHTML('        <td>'.f($row,'nm_tramite').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_solic_missao').'&w_demanda='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Desvinculação da atividade/demanda eventual." onClick="return(confirm(\'Confirma desvinculação?\'));">Desvincular</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
  } elseif ($O=='I') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_operacao" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.<br><br>Você pode fazer diversas procuras ou ainda clicar sobre o botão <i>Cancelar</i> para retornar à listagem das tarefas e demandas eventuais já vinculadas.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><table border=0 cellpadding=0 cellspacing=0 width="100%">');
    ScriptOpen('JavaScript');
    ShowHTML('  function trocaForm(p_sigla) {');
    ShowHTML('    document.Form.action=\''.$w_dir.$w_pagina.$par.'\';');
    ShowHTML('    document.Form.O.value=\''.$O.'\';');
    ShowHTML('    document.Form.p_sigla.value=p_sigla;');
    ShowHTML('    document.Form.submit();');
    ShowHTML('  }');
    ScriptClose();
    ShowHTML(  '<b>Fazer busca em:</b> ');
    if (nvl($p_sigla,'GDPCAD')=='GDPCAD') {
      ShowHTML('              <input type="radio" name="p_sigla" value="GDPCAD" checked onclick="trocaForm(\'GDPCAD\');"> Tarefas <input type="radio" name="p_sigla" value="GDCAD" onclick="trocaForm(\'GDCAD\');"> Demandas eventuais');
    } else {
      ShowHTML('              <input type="radio" name="p_sigla" value="GDPCAD" onclick="trocaForm(\'GDPCAD\');"> Tarefas <input type="radio" name="p_sigla" value="GDCAD" checked onclick="trocaForm(\'GDCAD\');"> Demandas eventuais');
    }
    ShowHTML('         <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Critérios de Busca</td>');
    // Se a opção for ligada ao módulo de projetos, permite a seleção do projeto  e da etapa
    if ($p_sigla=='GDPCAD') {
      ShowHTML('      <tr><td colspan=3><table border=0 width="90%" cellspacing=0><tr valign="top">');
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
      SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto da atividade na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto',f($RS_Menu,'sq_menu'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_atividade\'; document.Form.submit();"');
      ShowHTML('      </tr>');
      ShowHTML('      <tr>');
      SelecaoEtapa('Eta<u>p</u>a:','P','Se necessário, indique a etapa à qual esta atividade deve ser vinculada.',$p_atividade,$p_projeto,null,'p_atividade',null,null);
      ShowHTML('      </tr>');
      ShowHTML('          </table>');
    } 
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><font size="1"><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b>Detalh<U>a</U>mento:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr>');
    SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,null,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><font size="1"><b>Conclusão en<u>t</u>re:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$p_sigla);
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,f($RS,'sq_menu'),'p_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('</FORM>');
    if (Nvl($w_operacao,'')>'') {
      AbreForm('Form1',$w_dir.$w_pagina.'GRAVA','POST','return(Validacao1(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('<INPUT type="hidden" name="w_operacao" value="">');
      ShowHTML(MontaFiltro('POST'));
      // Recupera os registros
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$p_sigla);
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),4,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null);
      $RS = SortArray($RS,'assunto','asc');
      ShowHTML('<tr><td colspan=3>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
      ShowHTML('          <td><b>&nbsp;</td>');
      ShowHTML('          <td><b>Nº</td>');
      if ($p_sigla=='GDCAD') {
        ShowHTML('          <td><b>Demanda</td>');
      } else {
        ShowHTML('          <td><b>Projeto</td>');
        ShowHTML('          <td><b>Atividade</td>');
      }
      ShowHTML('          <td><b>Início</td>');
      ShowHTML('          <td><b>Fim</td>');
      ShowHTML('          <td><b>Situação</td>');
      ShowHTML('        </tr>');
      if (count($RS)<=0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td align="center"><input type="checkbox" name="w_demanda[]" value="'.f($row,'sq_siw_solicitacao').'">');
          ShowHTML('        <td nowrap>');
          if (f($row,'concluida')=='N') {
            if (f($row,'fim')<addDays(time(),-1)) {
              ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">'); 
            } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
              ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
            } else {
              ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
            } 
          } else {
            if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
              ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
            } else {
              ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
            } 
          } 
          ShowHTML('        <A class="HL" HREF="'.$w_dir.'tarefas.php?par=visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações da tarefa.">'.f($row,'sq_siw_solicitacao').'</a>');
          if ($p_sigla=='GDPCAD') ShowHTML('        <td>'.f($row,'nm_projeto').'</td>');
          if (strlen(Nvl(f($row,'assunto'),'-'))>50) $w_assunto=substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_assunto=Nvl(f($row,'assunto'),'-');
          if (f($row,'sg_tramite')=='CA') {
            ShowHTML('        <td title="'.htmlspecialchars(f($row,'assunto')).'"><strike>'.$w_assunto.'</strike></td>');
          } else {
            ShowHTML('        <td title="'.htmlspecialchars(f($row,'assunto')).'">'.$w_assunto.'</td>');
          } 
          if (f($row,'concluida')=='N') {
            ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'inicio')),'---').'</td>');
            ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim')),'---').'</td>');
          } else {
            ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'inicio_real')),'---').'</td>');
            ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim_real')),'---').'</td>');
          } 
          ShowHTML('        <td>'.f($row,'nm_tramite').'</td>');
          ShowHTML('      </tr>');
        } 
      } 
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
      ShowHTML('  <tr><td align="center" colspan=3><input class="stb" type="submit" name="Botao" value="Vincular"></td></tr>');
      ShowHTML('</FORM>');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina para informação dos dados financeiros
// -------------------------------------------------------------------------
function DadosFinanceiros() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_menu   = $_REQUEST['w_menu'];
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PDGERAL');
  $w_adicional          = Nvl(formatNumber(f($RS,'valor_adicional')),0);
  $w_desc_alimentacao   = Nvl(formatNumber(f($RS,'desconto_alimentacao')),0);
  $w_desc_transporte    = Nvl(formatNumber(f($RS,'desconto_transporte')),0);
  Cabecalho();
  ShowHTML('<HEAD>');
  ScriptOpen('JavaScript');
  FormataValor();
  ValidateOpen('Validacao');
  ShowHTML('  if (theForm.w_aux_alimentacao[0].checked) {');
  ShowHTML('    if (theForm.w_vlr_alimentacao.value==\'\') {');
  ShowHTML('      alert(\'Se houver auxílio-alimentação, informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  CompValor('w_vlr_alimentacao','Valor auxílio-alimentação','>','0,00','zero');
  ShowHTML('  } else { ');
  ShowHTML('    if (theForm.w_vlr_alimentacao.value!=\'0,00\' && theForm.w_vlr_alimentacao.value!=\'\') {');
  ShowHTML('      alert(\'Se não houver auxílio-alimentação, não informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  if (theForm.w_aux_transporte[0].checked) {');
  ShowHTML('    if (theForm.w_vlr_transporte.value==\'\') {');
  ShowHTML('      alert(\'Se houver auxílio-transporte, informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  CompValor('w_vlr_transporte','Valor auxílio-transporte','>','0,00','zero');
  ShowHTML('  } else { ');
  ShowHTML('    if (theForm.w_vlr_transporte.value!=\'0,00\' && theForm.w_vlr_transporte.value!=\'\') {');
  ShowHTML('      alert(\'Se não houver auxílio-transporte, não informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  var i,k;');
  ShowHTML('  for (k=0; k < theForm["w_qtd_diarias[]"].length; k++) {');
  ShowHTML('    var w_campo = \'theForm["w_qtd_diarias"][\'+k+\')"]\';');
  ShowHTML('    if((eval(w_campo + \'.value\')!=\'\')&&(eval(w_campo + \'.value\')==\'\')){');
  ShowHTML('      alert(\'Para cada quantidade de diárias informada, informe o valor unitário correspondente!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length < 3 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar pelo menos 3 posições no campo Quantidade de diárias.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length > 5 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar no máximo 5 posições no campo Quantidade de diárias.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    var checkOK = \'0123456789,\';');
  ShowHTML('    var checkStr = eval(w_campo + \'.value\');');
  ShowHTML('    var allValid = true;');
  ShowHTML('    for (i = 0;  i < checkStr.length;  i++) {');
  ShowHTML('      ch = checkStr.charAt(i);');
  ShowHTML('      if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != \'\')) {');
  ShowHTML('        for (j = 0;  j < checkOK.length;  j++) {');
  ShowHTML('          if (ch==checkOK.charAt(j)){');
  ShowHTML('            break;');
  ShowHTML('          } ');
  ShowHTML('          if (j==checkOK.length-1)');
  ShowHTML('          {');
  ShowHTML('            allValid = false;');
  ShowHTML('            break;');
  ShowHTML('          }');
  ShowHTML('        }');
  ShowHTML('      }');
  ShowHTML('      if (!allValid) {');
  ShowHTML('        alert(\'Favor digitar apenas números no campo Quantidade de diárias.\');');
  ShowHTML('        eval(w_campo + \'.focus()\');');
  ShowHTML('        theForm.Botao.disabled=false;');
  ShowHTML('        return (false);');
  ShowHTML('      }');
  ShowHTML('    } ');
  ShowHTML('    if((theForm["w_qtd_diarias[]"][k].value.charAt(theForm["w_qtd_diarias[]"][k].value.indexOf(\',\')+1)!=5) && (theForm["w_qtd_diarias[]"][k].value.charAt(theForm["w_qtd_diarias[]"][k].value.indexOf(\',\')+1)!=0)) {');
  ShowHTML('      alert(\'O valor decimal para quantidade de diarias deve ser 0 ou 5.\');');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    var V1, V2;');
  ShowHTML('    V1 = theForm["w_qtd_diarias[]"][k].value.toString().replace(/\\$|\\./g,\'\');');
  ShowHTML('    V2 = theForm["w_maximo_diarias[]"][k].value.toString().replace(/\\$|\\./g,\'\');');
  ShowHTML('    V1 = V1.toString().replace(\',\',\'.\'); ');
  ShowHTML('    V2 = V2.toString().replace(\',\',\'.\'); ');
  ShowHTML('    if(parseFloat(V1) > parseFloat(V2)){');
  ShowHTML('      alert(\'Quantidade informada  da \' + (k + 1) + \'ª cidade foi excedido(\'+theForm["w_maximo_diarias[]"][k].value + \').\');');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  for (k=0; k < theForm["w_vlr_diarias[]"].length; k++) {');
  ShowHTML('    if((theForm["w_vlr_diarias[]"][k].value!=\'\')&&(theForm["w_vlr_diarias[]"][k].value==\'\')){');
  ShowHTML('      alert(\'Para cada valor unitário da diária informado, informe a quantidade de diárias correspondente!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('    var w_campo = \'theForm["w_vlr_diarias"][\'+k+\')"]\';');
  ShowHTML('    if (eval(w_campo + \'.value.length < 3 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar pelo menos 3 posições no campo Valor unitário da diária.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length > 18 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar no máximo 18 posições no campo Valor unitário da diária.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    var checkOK = \'0123456789,.\';');
  ShowHTML('    var checkStr = eval(w_campo + \'.value\');');
  ShowHTML('    var allValid = true;');
  ShowHTML('    for (i = 0;  i < checkStr.length;  i++) {');
  ShowHTML('      ch = checkStr.charAt(i);');
  ShowHTML('      if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != \'\')) {');
  ShowHTML('        for (j = 0;  j < checkOK.length;  j++) {');
  ShowHTML('          if (ch==checkOK.charAt(j)){');
  ShowHTML('            break;');
  ShowHTML('          } ');
  ShowHTML('          if (j==checkOK.length-1) {');
  ShowHTML('            allValid = false;');
  ShowHTML('            break;');
  ShowHTML('          }');
  ShowHTML('        }');
  ShowHTML('      }');
  ShowHTML('      if (!allValid)  {');
  ShowHTML('        alert(\'Favor digitar apenas números no campo Valor unitário da diária.\');');
  ShowHTML('        eval(w_campo + \'.focus()\');');
  ShowHTML('        theForm.Botao.disabled=false;');
  ShowHTML('        return (false);');
  ShowHTML('      }');
  ShowHTML('    } ');
  ShowHTML('  }');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'this.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('            <tr><td>Número:<b><br>'.f($RS,'codigo_interno').' ('.$w_chave.')</td>');
  $RS1 = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null);
  foreach($RS1 as $row) { $RS1 = $row; break; }
  ShowHTML('                <td colspan="2">Proposto:<b><br>'.f($RS1,'nm_pessoa').'</td></tr>');
  ShowHTML('            <tr><td>Tipo:<b><br>'.f($RS,'nm_tipo_missao').'</td>');
  ShowHTML('                <td>Primeira saída:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('      <table width="99%" border="0">');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Benefícios recebidos pelo proposto</td>');
  ShowHTML('        <tr valign="top">');
  if (Nvl(f($RS,'valor_alimentacao'),0)>0) {
    MontaRadioSN('<b>Auxílio-Alimentação?</b>',$w_aux_alimentacao,'w_aux_alimentacao');
  } else {
    MontaRadioNS('<b>Auxílio-Alimentação?</b>',$w_aux_alimentacao,'w_aux_alimentacao');
  } 
  ShowHTML('            <td><b>Valor R$: </b><input type="text" name="w_vlr_alimentacao" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.formatNumber(Nvl(f($RS,'valor_alimentacao'),0)).'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do auxílio-alimentação."></td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr valign="top">');
  if (Nvl(f($RS,'valor_transporte'),0)>0) {
    MontaRadioSN('<b>Auxílio-Transporte?</b>',$w_aux_transporte,'w_aux_transporte');
  } else {
    MontaRadioNS('<b>Auxílio-Transporte?</b>',$w_aux_transporte,'w_aux_transporte');
  } 
  ShowHTML('        <td><b>Valor R$: </b><input type="text" name="w_vlr_transporte" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.formatNumber(Nvl(f($RS,'valor_transporte'),0)).'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do auxílio-transporte."></td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados da viagem/cálculo das diárias</td>');
  $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave,null,$SG);
  $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RS)>0) {
    $i = 1;
    foreach($RS as $row) {
      $w_vetor_trechos[$i][1] = f($row,'sq_diaria');
      $w_vetor_trechos[$i][2] = f($row,'cidade_dest');
      $w_vetor_trechos[$i][3] = f($row,'nm_destino');
      $w_vetor_trechos[$i][4] = substr(FormataDataEdicao(f($row,'phpdt_chegada'),4),0,-3);
      $w_vetor_trechos[$i][5] = substr(FormataDataEdicao(f($row,'phpdt_saida'),4),0,-3);
      $w_vetor_trechos[$i][6] = formatNumber(Nvl(f($row,'quantidade'),0),1,',','.');
      $w_vetor_trechos[$i][7] = formatNumber(Nvl(f($row,'valor'),0));
      $w_vetor_trechos[$i][8] = f($row,'saida');
      $w_vetor_trechos[$i][9] = f($row,'chegada');
      if ($i>1) {
        $w_vetor_trechos[$i-1][5] = substr(FormataDataEdicao(f($row,'phpdt_saida'),4),0,-3);
      }
      $i += 1;
    } 
    ShowHTML('     <tr><td align="center" colspan="2">');
    ShowHTML('       <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('         <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('         <td><b>Destino</td>');
    ShowHTML('         <td><b>Chegada</td>');
    ShowHTML('         <td><b>Saida</td>');
    ShowHTML('         <td><b>Quantidade de diárias</td>');
    ShowHTML('         <td><b>Valor unitário R$</td>');
    ShowHTML('         </tr>');
    $w_cor  = $conTrBgColor;
    $j      = $i;
    $i      = 1;
    while($i!=($j-1)) {
      ShowHTML('<INPUT type="hidden" name="w_sq_diaria[]" value="'.$w_vetor_trechos[$i][1].'">');
      ShowHTML('<INPUT type="hidden" name="w_sq_cidade[]" value="'.$w_vetor_trechos[$i][2].'">');
      ShowHTML('<INPUT type="hidden" name="w_maximo_diarias[]" value="'.(intval($DateDiff['d'][$FormatDateTime[$w_vetor_trechos[$i][9]][2]][$FormatDateTime[Nvl($w_vetor_trechos[$i+1][8],$w_vetor_trechos[$i][9])][2]])+intval(1)).'">');
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('     <tr valign="top" bgcolor="'.$w_cor.'">');
      ShowHTML('       <td>'.$w_vetor_trechos[$i][3].'</td>');
      ShowHTML('       <td align="center">'.$w_vetor_trechos[$i][4].'</td>');
      ShowHTML('       <td align="center">'.$w_vetor_trechos[$i][5].'</td>');
      ShowHTML('       <td align="right"><input type="text" name="w_qtd_diarias[]" class="sti" SIZE="10" MAXLENGTH="5" VALUE="'.$w_vetor_trechos[$i][6].'" onKeyDown="FormataValor(this,5,1,event);" title="Informe a quantidade de diárias para este destino."></td>');
      ShowHTML('       <td align="right"><input type="text" name="w_vlr_diarias[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_vetor_trechos[$i][7].'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor unitário das diárias para este destino."></td>');
      ShowHTML('     </tr>');
      $i += 1;
    } 
    ShowHTML('        <tr><td valign="top" colspan="5" align="center" bgcolor="'.$conTrBgColor.'"><b>Outros valores</td>');
    ShowHTML('        <tr bgcolor="'.$conTrAlternateBgColor.'">');
    ShowHTML('          <td align="right" colspan="4"><b>adicional:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_adicional" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_adicional.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor adicional."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td align="right" colspan="4"><b>desconto auxílio-alimentação:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_desc_alimentacao" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_desc_alimentacao.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o desconto do auxílio-alimentação."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrAlternateBgColor.'">');
    ShowHTML('          <td align="right" colspan="4"><b>desconto auxílio-transporte:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_desc_transporte" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_desc_transporte.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o desconto do auxílio-transporte."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        </table></td></tr>');
  } 
  ShowHTML('        <tr><td align="center" colspan="2">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));

  if ($w_tipo=='PDF') {
    headerpdf('Visualização de '.f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de PCD</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);
    $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') {
    ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  }
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') {
    ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  }
  if     ($w_tipo=='PDF')  RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
} 

// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
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
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PDIDENT',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de tramitação
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');

  if ($w_troca>'') {
    // Se for recarga da página
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
  } else {
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
    $w_inicio        = f($RS,'inicio');
    $w_tramite       = f($RS,'sq_siw_tramite');
    $w_justificativa = f($RS,'justificativa');
    $w_prazo         = f($RS,'limite_envio');
    $w_antecedencia   = f($RS,'dias_antecedencia');
  } 

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $RS = db_getTramiteData::getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');

  if ($w_sg_tramite!='CI') {
    //Verifica a fase anterior para a caixa de seleção da fase.
    $RS = db_getTramiteList::getInstanceOf($dbms,$w_tramite,'ANTERIOR',null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 

  // Se for envio, executa verificações nos dados da solicitação
  if ($O=='V') $w_erro = ValidaViagem($w_cliente,$w_chave,$SG,'PDGERAL',null,null,$w_tramite);

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($w_sg_tramite=='CI') {
      if (mktime(0,0,0,date(m),date(d),date(Y))>$w_prazo) {
        Validate('w_justificativa','Justificativa','','1','1','2000','1','1');
      } 
    } else {
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE') {
        Validate('w_despacho','Despacho','1','1','1','2000','1','1');
      } else {
        Validate('w_despacho','Despacho','','','1','2000','1','1');
        ShowHTML('  if (theForm.w_envio[0].checked && theForm.w_despacho.value != \'\') {');
        ShowHTML('     alert(\'Informe o despacho apenas se for devolução para a fase anterior!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_envio[1].checked && theForm.w_despacho.value==\'\') {');
        ShowHTML('     alert(\'Informe um despacho descrevendo o motivo da devolução!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        if (Nvl(substr($w_erro,0,1),'')=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
          if (mktime(0,0,0,date(m),date(d),date(Y))>$w_prazo) {
            Validate('w_justificativa','Justificativa','','','1','2000','1','1');
            ShowHTML('if (theForm.w_envio[0].checked && theForm.w_justificativa.value==\'\') {');
            ShowHTML('     alert(\'Informe uma justificativa para o não cumprimento do prazo regulamentar!\');');
            ShowHTML('     theForm.w_justificativa.focus();');
            ShowHTML('     return false;');
            ShowHTML('}');
          } 
        } 
      } 
    } 
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1 || ($P1==1 && $w_tipo=='Volta')) {
      // Se não for encaminhamento e nem o sub-menu do cadastramento
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
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PDENVIO',$w_pagina.$par,$O);
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
        // Se a data de início da viagem não respeitar os dias de antecedência, exige justificativa.
        if (mktime(0,0,0,date(m),date(d),date(Y))>$w_prazo) {
          ShowHTML('    <tr><td><b><u>J</u>ustificativa para não cumprimento do prazo regulamentar de '.$w_antecedencia.' dias:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o início da viagem for anterior a '.FormataDataEdicao(addDays(time(),$w_antecedencia)).', justifique o motivo do não cumprimento do prazo regulamentar para o pedido.">'.$w_justificativa.'</TEXTAREA></td>');
        } 
        ShowHTML('      </table>');
        ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr>');
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
      }
    } else {
      ShowHTML('    <tr><td><b>Tipo do Encaminhamento</b><br>');
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE') {
        ShowHTML('              <input DISABLED class="STR" type="radio" name="w_envio" value="N"> Enviar para a próxima fase <br><input DISABLED class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
        ShowHTML('<INPUT type="hidden" name="w_envio" value="S">');
      } else {
        if (Nvl($w_envio,'N')=='N') {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N" checked> Enviar para a próxima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S"> Devolver para a fase anterior');
        } else {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N"> Enviar para a próxima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
        } 
      } 
      ShowHTML('    <tr>');
      SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a PCD, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_novo_tramite,'w_novo_tramite','DEVOLUCAO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolução):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a PCD.">'.$w_despacho.'</TEXTAREA></td>');
      if (!(substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE')) {
        if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
          if (mktime(0,0,0,date(m),date(d),date(Y))>$w_prazo) {
            ShowHTML('    <tr><td><b><u>J</u>ustificativa para não cumprimento do prazo regulamentar de '.$w_antecedencia.' dias:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o início da viagem for anterior a '.FormataDataEdicao(addDays(time(),$w_prazo)).', justifique o motivo do não cumprimento do prazo regulamentar para o pedido.">'.$w_justificativa.'</TEXTAREA></td>');
          } 
        } 
      } 
      ShowHTML('      </table>');
      ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
    } 
    if ($P1!=1) {
      // Se não for cadastramento, volta para a listagem
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } elseif ($P1==1 && $w_tipo=='Volta') {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } 
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
// Rotina de anotação
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anotação','','1','1','2000','1','1');
    Validate('w_caminho','Arquivo','','','5','255','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
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
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=PDENVIO&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top"><b>A<u>n</u>otação:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anotação desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de conclusão
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
  } 

  //Recupera a data da primeira saída
  $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave,null,'DADFIN');
  $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (!count($RS)<=0) {
    $w_inicio_real = f($RS,'saida');
    foreach($RS as $row) {
      $w_custo_real += Nvl(f($row,'quantidade'),0)*Nvl(f($row,'valor'),0);
      $w_fim_real   = f($row,'chegada');
    } 
  } 

  //Recupera os dados da solicitacao de passagens e diárias
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  $w_custo_real += Nvl(f($RS,'valor_passagem'),0)+Nvl(f($RS,'valor_adicional'),0)+Nvl(f($RS,'valor_alimentacao'),0)+Nvl(f($RS,'valor_transporte'),0)-Nvl(f($RS,'desconto_alimentacao'),0)-Nvl(f($RS,'desconto_transporte'),0);
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=PDCONC&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_inicio_real" value="'.$w_inicio_real.'">');
  ShowHTML('<INPUT type="hidden" name="w_fim_real" value="'.$w_fim_real.'">');
  ShowHTML('<INPUT type="hidden" name="w_custo_real" value="'.$w_custo_real.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="100%" border="0">');
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  $w_Disabled = 'READONLY';
  ShowHTML('      <tr><td valign="top"><table border=1 width="100%" cellspacing=0 bgcolor="'.$conTableBgColor.'">');
  ShowHTML('          <tr bgcolor="'.$conTrAlternateBgColor.'">');
  ShowHTML('              <td align="center"><b>Primeira saída</b></td>');
  ShowHTML('              <td align="center"><b>Último retorno</b></td>');
  ShowHTML('              <td align="center"><b>Custo total</b></td>');
  ShowHTML('          </tr>');
  ShowHTML('          <tr>');
  ShowHTML('              <td align="center">'.FormataDataEdicao($w_inicio_real).'</td>');
  ShowHTML('              <td align="center">'.FormataDataEdicao($w_fim_real).'</td>');
  ShowHTML('              <td align="right">'.formatNumber($w_custo_real).'</td>');
  ShowHTML('          </tr>');
  ShowHTML('          </table>');
  ShowHTML('      <tr><td valign="top"><b>Nota d<u>e</u> conclusão:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75>Conferi a documentação necessária para prestação de contas desta PCD.</TEXTAREA></td>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de emissão da autorização e da proposta de concessão de passagens e diárias
// -------------------------------------------------------------------------
function Emissao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];

  //Recupera a data da primeira saída
  $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave,null,'PDGERAL');
  $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  foreach($RS as $row) { $RS = $row; break; }
  $w_primeira_saida = f($RS,'saida');

  //Recupera os dados da solicitacao de passagens e diárias
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  $w_prazo          = f($RS,'dias_antecedencia');

  //Recupera os dados do proposto
  $RS1 = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null);
  foreach($RS1 as $row) { $RS1 = $row; break; }

  header('Content-Disposition'.': '.'attachment; filename=Emissao'.$w_chave.'.doc');
  header('Content-type: '.'application/msword');

  ShowHTML("{\\rtf1\\ansi\\ansicpg1252\\uc1\\deff0\\stshfdbch0\\stshfloch0\\stshfhich0\\stshfbi0\\deflang1033\\deflangfe1033{\\fonttbl{\\f0\\froman\\fcharset0\\fprq2{\\*\\panose 02020603050405020304}Times New Roman;}{\\f1\\fswiss\\fcharset0\\fprq2{\\*\\panose 020b0604020202020204}Arial;}");
  ShowHTML("{\\f36\\fswiss\\fcharset0\\fprq2{\\*\\panose 020b0506020202030204}Arial Narrow;}{\\f129\\froman\\fcharset238\\fprq2 Times New Roman CE;}{\\f130\\froman\\fcharset204\\fprq2 Times New Roman Cyr;}{\\f132\\froman\\fcharset161\\fprq2 Times New Roman Greek;}");
  ShowHTML("{\\f133\\froman\\fcharset162\\fprq2 Times New Roman Tur;}{\\f134\\froman\\fcharset177\\fprq2 Times New Roman (Hebrew);}{\\f135\\froman\\fcharset178\\fprq2 Times New Roman (Arabic);}{\\f136\\froman\\fcharset186\\fprq2 Times New Roman Baltic;}");
  ShowHTML("{\\f137\\froman\\fcharset163\\fprq2 Times New Roman (Vietnamese);}{\\f139\\fswiss\\fcharset238\\fprq2 Arial CE;}{\\f140\\fswiss\\fcharset204\\fprq2 Arial Cyr;}{\\f142\\fswiss\\fcharset161\\fprq2 Arial Greek;}{\\f143\\fswiss\\fcharset162\\fprq2 Arial Tur;}");
  ShowHTML("{\\f144\\fswiss\\fcharset177\\fprq2 Arial (Hebrew);}{\\f145\\fswiss\\fcharset178\\fprq2 Arial (Arabic);}{\\f146\\fswiss\\fcharset186\\fprq2 Arial Baltic;}{\\f147\\fswiss\\fcharset163\\fprq2 Arial (Vietnamese);}{\\f489\\fswiss\\fcharset238\\fprq2 Arial Narrow CE;}");
  ShowHTML("{\\f490\\fswiss\\fcharset204\\fprq2 Arial Narrow Cyr;}{\\f492\\fswiss\\fcharset161\\fprq2 Arial Narrow Greek;}{\\f493\\fswiss\\fcharset162\\fprq2 Arial Narrow Tur;}{\\f496\\fswiss\\fcharset186\\fprq2 Arial Narrow Baltic;}}{\\colortbl;\\red0\\green0\\blue0;");
  ShowHTML("\\red0\\green0\\blue255;\\red0\\green255\\blue255;\\red0\\green255\\blue0;\\red255\\green0\\blue255;\\red255\\green0\\blue0;\\red255\\green255\\blue0;\\red255\\green255\\blue255;\\red0\\green0\\blue128;\\red0\\green128\\blue128;\\red0\\green128\\blue0;\\red128\\green0\\blue128;");
  ShowHTML("\\red128\\green0\\blue0;\\red128\\green128\\blue0;\\red128\\green128\\blue128;\\red192\\green192\\blue192;\\red255\\green255\\blue255;}{\\stylesheet{\\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 ");
  ShowHTML("\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 \\snext0 \\styrsid9184405 Normal;}{\\s1\\qc \\fi708\\li0\\ri0\\keepn\\widctlpar\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0\\itap0 \\b\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 ");
  ShowHTML("\\sbasedon0 \\snext0 \\styrsid9184405 heading 1;}{\\s2\\ql \\li0\\ri0\\keepn\\widctlpar\\aspalpha\\aspnum\\faauto\\outlinelevel1\\adjustright\\rin0\\lin0\\itap0 \\b\\f1\\fs22\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 \\sbasedon0 \\snext0 \\styrsid9184405 heading 2;}{\\*");
  ShowHTML("\\cs10 \\additive \\ssemihidden Default Paragraph Font;}{\\*\\ts11\\tsrowd\\trftsWidthB3\\trpaddl108\\trpaddr108\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3\\tscellwidthfts0\\tsvertalt\\tsbrdrt\\tsbrdrl\\tsbrdrb\\tsbrdrr\\tsbrdrdgl\\tsbrdrdgr\\tsbrdrh\\tsbrdrv ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 \\fs20\\lang1024\\langfe1024\\cgrid\\langnp1024\\langfenp1024 \\snext11 \\ssemihidden Normal Table;}{\\s15\\ql \\li0\\ri0\\widctlpar");
  ShowHTML("\\tqc\\tx4419\\tqr\\tx8838\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 \\fs20\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 \\sbasedon0 \\snext15 \\styrsid9184405 header;}{");
  ShowHTML("\\s16\\qj \\fi708\\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 \\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 \\sbasedon0 \\snext16 \\styrsid9184405 Body Text Indent;}}{\\*\\latentstyles\\lsdstimax156\\lsdlockeddef0}");
  ShowHTML("{\\*\\rsidtbl \\rsid1071686\\rsid3545814\\rsid8462233\\rsid9184405\\rsid10884206\\rsid12078955\\rsid12326642\\rsid14038699}{\\*\\generator Microsoft Word 11.0.5604;}{\\info{\\title  }{\\author Suporte T\\'e9cnico}{\\operator Suporte T\\'e9cnico}");
  ShowHTML("{\\creatim\\yr2006\\mo5\\dy10\\hr14\\min6}{\\revtim\\yr2006\\mo5\\dy10\\hr14\\min6}{\\version2}{\\edmins0}{\\nofpages2}{\\nofwords644}{\\nofchars3677}{\\*\\company SBPI Consultoria}{\\nofcharsws4313}{\\vern24689}}\\margl1701\\margr1797\\margt454\\margb567 ");
  ShowHTML("\\widowctrl\\ftnbj\\aenddoc\\noxlattoyen\\expshrtn\\noultrlspc\\dntblnsbdb\\nospaceforul\\formshade\\horzdoc\\dgmargin\\dghspace180\\dgvspace180\\dghorigin1701\\dgvorigin454\\dghshow1\\dgvshow1");
  ShowHTML("\\jexpand\\viewkind1\\viewscale75\\pgbrdrhead\\pgbrdrfoot\\splytwnine\\ftnlytwnine\\htmautsp\\nolnhtadjtbl\\useltbaln\\alntblind\\lytcalctblwd\\lyttblrtgr\\lnbrkrule\\nobrkwrptbl\\snaptogridincell\\allowfieldendsel\\wrppunct\\asianbrkrule\\nojkernpunct\\rsidroot9184405 \\fet0");
  ShowHTML("\\sectd \\linex0\\headery709\\footery709\\colsx708\\endnhere\\sectlinegrid360\\sectdefaultcl\\sectrsid12326642\\sftnbj {\\*\\pnseclvl1\\pnucrm\\pnstart1\\pnindent720\\pnhang {\\pntxta .}}{\\*\\pnseclvl2\\pnucltr\\pnstart1\\pnindent720\\pnhang {\\pntxta .}}{\\*\\pnseclvl3");
  ShowHTML("\\pndec\\pnstart1\\pnindent720\\pnhang {\\pntxta .}}{\\*\\pnseclvl4\\pnlcltr\\pnstart1\\pnindent720\\pnhang {\\pntxta )}}{\\*\\pnseclvl5\\pndec\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}{\\*\\pnseclvl6\\pnlcltr\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}");
  ShowHTML("{\\*\\pnseclvl7\\pnlcrm\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}{\\*\\pnseclvl8\\pnlcltr\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}{\\*\\pnseclvl9\\pnlcrm\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}\\pard\\plain \\s15\\ql \\li0\\ri0\\widctlpar");
  ShowHTML("\\tqc\\tx4419\\tqr\\tx8838\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid3545814 \\fs20\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\lang1024\\langfe1024\\noproof\\insrsid9184405 ");
  ShowHTML("\\par }\\pard\\plain \\ql \\li0\\ri0\\widctlpar\\pvpara\\phpg\\posx5615\\posy210\\dxfrtext141\\dfrmtxtx141\\dfrmtxty0\\nowrap\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {");
  ShowHTML("\\lang1024\\langfe1024\\noproof\\insrsid9184405 {\\*\\shppict{\\pict{\\*\\picprop\\shplid1025{\\sp{\\sn shapeType}{\\sv 75}}{\\sp{\\sn fFlipH}{\\sv 0}}{\\sp{\\sn fFlipV}{\\sv 0}}{\\sp{\\sn fillColor}{\\sv 268435473}}{\\sp{\\sn fFilled}{\\sv 0}}");
  ShowHTML("{\\sp{\\sn fLine}{\\sv 0}}{\\sp{\\sn fLayoutInCell}{\\sv 1}}}\\picscalex100\\picscaley100\\piccropl0\\piccropr0\\piccropt0\\piccropb0\\picw1958\\pich2170\\picwgoal1110\\pichgoal1230\\pngblip\\bliptag-1973820408{\\*\\blipuid 8a59e408278a8b7c4ae763e2dfaf7202}");
  ShowHTML("89504e470d0a1a0a0000000d494844520000004a000000520803000000c48bb2b50000000467414d410000b1889598f4a6000002be504c544500000000003300");
  ShowHTML("00660000990000cc0000ff3300003300333300663300993300cc3300ff6600006600336600666600996600cc6600ff9900009900339900669900999900cc9900");
  ShowHTML("ffcc0000cc0033cc0066cc0099cc00cccc00ffff0000ff0033ff0066ff0099ff00ccff00ff0d0d0d1a1a1a2828280033000033330033660033990033cc0033ff");
  ShowHTML("3333003333333333663333993333cc3333ff6633006633336633666633996633cc6633ff9933009933339933669933999933cc9933ffcc3300cc3333cc3366cc");
  ShowHTML("3399cc33cccc33ffff3300ff3333ff3366ff3399ff33ccff33ff3535354343435050505d5d5d0066000066330066660066990066cc0066ff3366003366333366");
  ShowHTML("663366993366cc3366ff6666006666336666666666996666cc6666ff9966009966339966669966999966cc9966ffcc6600cc6633cc6666cc6699cc66cccc66ff");
  ShowHTML("ff6600ff6633ff6666ff6699ff66ccff66ff6b6b6b7878788686869393930099000099330099660099990099cc0099ff3399003399333399663399993399cc33");
  ShowHTML("99ff6699006699336699666699996699cc6699ff9999009999339999669999999999cc9999ffcc9900cc9933cc9966cc9999cc99cccc99ffff9900ff9933ff99");
  ShowHTML("66ff9999ff99ccff99ffa1a1a1aeaeaebbbbbbc9c9c900cc0000cc3300cc6600cc9900cccc00ccff33cc0033cc3333cc6633cc9933cccc33ccff66cc0066cc33");
  ShowHTML("66cc6666cc9966cccc66ccff99cc0099cc3399cc6699cc9999cccc99ccffcccc00cccc33cccc66cccc99ccccccccccffffcc00ffcc33ffcc66ffcc99ffccccff");
  ShowHTML("ccffd6d6d6e4e4e4f1f1f100ff0000ff3300ff6600ff9900ffcc00ffff33ff0033ff3333ff6633ff9933ffcc33ffff66ff0066ff3366ff6666ff9966ffcc66ff");
  ShowHTML("ff99ff0099ff3399ff6699ff9999ffcc99ffffccff00ccff33ccff66ccff99ccffccccffffffff00ffff33ffff66ffff99ffffccffffff43f3a7ba0000000970");
  ShowHTML("48597300000ec300000ec301c76fa86400000c7049444154789ca558cb6edb4816cdca2baff213b20394b460365ce913664ca05865545140c90db037ca074c6f");
  ShowHTML("62b423838f465106d21fd233025fe043bfd26d8be45fcc294a8e9db49deec198b024d3d4e1a973cf7d14df0cdfffe99e3eedfee2d237dfff77df74fbc307bc77");
  ShowHTML("dfbff6fb507dddd57db31bd2a66b7134ff0754d735fb5d1755ba6bfbb6eb1f69957f1faa68eadebce7fb66dff4b9d29532acfa763cdb77f1df87eafabeee3be8");
  ShowHTML("d40f6ddf69fda35fe133cee0847939eaf777a0767b68039d7270293a5ff9bedf40a9aeddb74d3da45df762005e6655e0ce664561dd75b9b6fdb90fb53eeb2ed1");
  ShowHTML("756ff4effabf09b52f463691a0562e3235f74938affc2ebcb20a2bf3fda4817e7f0105e2bd5101f70d8b2e14425295b47a25c2cad749d337bdb8d189d2aaeb6e");
  ShowHTML("f4f7a1ca620f1775bbaec9c53ea78252410a55d9823c04bec69148b25cce3734d9d3a0e971dd57b17c0ed5effb7d8be847821814290415aa9ae3b57ef0e755b3");
  ShowHTML("a96da502b221c2fa99c271b8bc7c050a1142ccfa460860e11044b8a9bf02ea43edfb3aebdbcda62640266422446322f31a2bf8b96f712f2aa961159070b3d7be");
  ShowHTML("2fa8bc7f08567ed6f43ab1c34d4424162f365db7ef5f630537eff6ad5060430958e54d6f0109ec1eaa7ba8a59ba2e9eb9da666fd54e47db37f55abbea9bb8628");
  ShowHTML("b3364a71ef26f9ec2bf3bdfa016ad51a2e405c2971718e126532e01528282e72619dd896e144ad02d9e78fda8055952878ab2d812426464b2155b17f050a3e88");
  ShowHTML("289544d8751346267b43ed0ba39bacaafae161e5d71d34888c8e882fb923b23189f92d54671c181b8d84504a6dc0b0ed949ee3ee4a2799d63a9dfb7e8bf80ed2");
  ShowHTML("2061fd4b41555bb75d7d54ec00d5c36d79d3809185efc20b7503e7b7a9f1915c7fe48caf3d9df82a00aba6be91ee5da8086453500bccca67505be47c41f2f16e");
  ShowHTML("648c5fdee4a1528cadcb92f3983b8b72cb1757816a704f136155c813dc75ac16e5570b84cb039186667960262d658741a0f8fa9aefca6bbe64ebb8c7e7db24b1");
  ShowHTML("125fd3a5b0ac6423c48d801ffac7301ea14ad40162138b9c12cb523a4dab545bfcae64dcf544b8cc85eb7a7c5d2ebc4a5769a5b5651165626932b1dd7e05d569");
  ShowHTML("a8ab1128fdd9f7519f56bee6b7ceedc20bc219e797ce355f44e174cdaf39aa8cbf9a2304fee1fa54ebcdd711c4bdd24a0165fcd14a89355b301a30c773b8f313");
  ShowHTML("e70e0f33c279e4694b58b8cc80d9caaefc2afbc60c5582ff1a46bfeabaaaefb582488e949c5fb7bf5f5cf36b0707dbb0ed8e5f65d4c458da0adc57fe87aaf91a");
  ShowHTML("aa8990b796aae06a38bbbe170ee74c182ef100c12ec1ecd271784e3c7e3923f45085840850a95572ec8f6f8e3eefb5f62d41b20ad9563d68fe71cd97f2d2b9e4");
  ShowHTML("9757eb8f4756502ca7d79c8b319ba9c82a4851b5fdb103bd199dd0b72da9b47f6afc790f668a95fff104779c77f8758ebfe0e85c09677be153735d5501495562");
  ShowHTML("a3f72d7ad408854e851fac3ff8e08f7713a1b8665c72b0c0f1af2323e717c3cd5b17bf2c9300ccab87f90715aa4ec2f25d179706aa01ab06a59c54816f2a27b2");
  ShowHTML("8e82837bee3897ef1ce88477b05a17bbf8a3c3a7d04f57a30a2bbb22792e14f275571e1668d6879820b2fab365b0fce9dae1ec7af4d3e591d3251ffa127f0bfe");
  ShowHTML("9127da20f976657283e4a8bc657c80ea8b3ca21332b1c1673e37c9c3cb8534faf0673ac1ec6b7c102cda4a5ddfc35701e288944dbaba28bf449006f466827a27");
  ShowHTML("6af5415112f2c579f2a813378ca28beb6ba0e15ce2162cd60ffa839f91b196d1245c8a2f6688053851837552e9954597f85672e9c4c89d839f16f0165c603e4b");
  ShowHTML("e422ab52df4e9183f41fe63b94164f167547b5c77e851a6c073c66c162d70ffca0d525e7fca0d73557d36bbed62b159aca36ae50d0f699db193adbc86b222432");
  ShowHTML("51b092054ed4978b51a79f9e347338651197daaa2c3a5e0f56139a7db1e8d0493a411719e4c4dca9b61316b3d0f9587aece0f1e7af212b58a4b3600f362ec5bd");
  ShowHTML("27941e4a96814247a074ac9f63405a1db80e3f67473f19ad0ebfe36782f7402c6169dc17bcf04d5d77ed2354d784238a89212dfa65406e3923dc64a0336a65fc");
  ShowHTML("c50f5a317ecb34e93b31a5e3e59446bb7e9c78df8c0eed323110b8d342daa0f758c1fa0f4f4d9f7290f3a3c7b8c7624f29dd76130f5a1013a76c747b0ca8b88c");
  ShowHTML("9143ed0d21a7fe49dedeb4519128937f7a3aea63fcc56f7198cf37721dc73a09222a37ff1e5509cde8101fdd1e97bd990a0b619dda45484a926b31e57cf13ec0");
  ShowHTML("aa0e5addc67f94c83fbe14a8576065eaba1f01e72aab8173e85e6304cbb83333a665db85e99561fa36a1bc5c734df951275c8d5aea4e7f293933fdd0d8dce82b");
  ShowHTML("83b01fbeee833b54bfdc3a393dcc55dad629595f701608e6982ec1e385c7a65238dec094b5127a4c3e44cf12cbe69b823c34fdbe20cbc35c815e57eb0c6bbbe5");
  ShowHTML("21f598c9bc35b225442d5cf35051ffc7948c53894b458809a4fb1aaa43c533f180e789b67c5452f4971c393893f0631a0453e6c43beefaa0e2db9fc921670955");
  ShowHTML("9805bf86eabb4f63ed318e13d5dc463d0a6a6af86c39baaa0756e7d73c7693000acc3174999c8560d966573f4e598f1da7db18ef8efe257aaec0aa122961336f");
  ShowHTML("17a330a0ff95a61a0681b9c69eabf13a4a024cc15dfd70e8cf072858a3fb442712f6956fb55a992a69d45035c5bc00a53eb248642986ac4a0a7ab20294c454b0");
  ShowHTML("4c10ad0ed36bff046576688da02637897deacf75759fd2319a98aba4c2788af7b1a2df438113ec2fc8c84b180fed0e0e7df4551f77dd76ac57d6c9d2f6c14ad0");
  ShowHTML("c37467239a95ae3103d4638f446f26f3b918731fd7271d72e6b9af4cf66c4b93e70493ccdcbe0fe08a8938812aaa5a16651461b6415f1fbbb710a72b6d907698");
  ShowHTML("0ccba719f969168d4b579ab2b8d1f3a432fc88fdc1f6ab3ae9774314e71bec2f3ff8866f4ddffb9ae441d698ec1b5e801ae242bac43db1f44a67708d3547fd4e");
  ShowHTML("c3ac2bfabed8746d8b75627a49ab8a5a73adcc1ecfb07a11aa6c7675017799fd918db9a2d60459ed85bf97437cb7cb170b370ceaca9eafb4f53651fbb6dbe378");
  ShowHTML("8555e97acb90d8da3eb56de5075674666a152b066825cf51afcea3a54ab5bd9adbb6ce9b369261ff32ab01d354d74498b031f3e904a136956acd4a68753735bd");
  ShowHTML("8ba3332dabda5ea13648d59c71f67c0ff01c8acdf8459e5796d6a8a9a3f399c366b329b49227d305e3d3717e9e60876fe96c878acd8e558147df425dceb8ecb3");
  ShowHTML("1a194626d89c9c8889f4dce90fbb216633b22ce4a18f20b61626c34f18e6d8717d7c6cf40cc25db0f1c4c2b9e0ce55a546271f0ed5cbb3e9ae8ca60cb5fad364");
  ShowHTML("32d0b15f522bbd4225bb700e500e2f06cf7b33ac51d7f8e11447aef19d87fd8b30ace8afa90e24dc512beb4428c4d4189858c8bf7ec74dc71ebfb7859e1805b0");
  ShowHTML("c0129141fd46049d19576256949f1e59d9caaad2f474858917937580d23a31bb37a2f2067b8c73f44640adc7be34769c6161ba1d280ea8e19970df0f4d313658");
  ShowHTML("e432e5174cda7eb25c0939e5e87f638e4ff2dd4eb25900d7954379e88fc7f92a36e330815e78d54213c38a4e2613b445d3fddefac5768ebaec78e93827d03c62");
  ShowHTML("cc0bd000c976282fe0bd773f3c5506ce43546e905d6837104246119d12112782a337d8d626f283902cdcba2d5c57dc6dd717cbe037addc3bf34d74a4387ef2d5");
  ShowHTML("e2c20ba6261c3389bd924fa457de61920bc131ad2c6c432c9dd6285a3037b288b144682b9de20f29cf4c3f7a6ed1129b3e2e5c6e1886228332621ac751841ede");
  ShowHTML("14559585e8bafd26bcdbb2c59407a1f84d982a651693b2c73af368d1d271907f1cff8fcf15baf392e8a9645ed494fdc6ec7e865dbf8de177cc0b241564cae2ed");
  ShowHTML("b054ff44d7bd7bcce82f6e2ff99adc30e9e26e33ef5cd0250d340fe5d4f3ae64849603efbc972c0323116667d8df711e640172f44b6d784a9c325e9c7b9a22d7");
  ShowHTML("ca053c331581f4b2136c6ab508a51662a34420deeba97b7e71bee04c868a85ded52bf52a5ecf4229ccac39c3a476c6261ef5dc8dca6846eb59380523ca6718b0");
  ShowHTML("e069befc98a07ff3d79e7e9471cce419bce85c9c39b8373be7b333956834ee40c2bbf28ce11c06ae7367969d876ae10dc32b5063b7b88d6590510a0fe3dee70c");
  ShowHTML("6722150a52963118e1dcb58b5f787acdbf7d78f8ed53b56d7ccbbca920de04f707d60f7199dfc35bdbb80427fece6112550c7789fff444f3cf0fe8628c50efe5");
  ShowHTML("8d8b8d257fc7e19abe7ac814d88db39613fd2cb0ef2d5f789ef9d2b33ef4c4c5e29da9a98eb3407f1c4a336b6e0d97775833d0b72f3d197de5692ddcb5e0e314");
  ShowHTML("5a0ee883fd5094f16142be8b5f62f41da871a546b705f6cec8c70dd6ed2cf82f77afe17c1feac80e98bb3b33618cc777aefd0ba8035ed9bfcee57f837ae549f6b73fff05532332b865345cd90000000049454e44ae426082}}{\\nonshppict");
  ShowHTML("{\\pict\\picscalex101\\picscaley101\\piccropl0\\piccropr0\\piccropt0\\piccropb0\\picw1958\\pich2170\\picwgoal1110\\pichgoal1230\\wmetafile8\\bliptag-1973820408\\blipupi95{\\*\\blipuid 8a59e408278a8b7c4ae763e2dfaf7202}");
  ShowHTML("010009000003720e000000004d0e000000000400000003010800050000000b0200000000050000000c0253004b00030000001e0004000000070104004d0e0000");
  ShowHTML("410b2000cc0052004a000000000052004a0000000000280000004a00000052000000010008000000000000000000000000000000000000000000000000000000");
  ShowHTML("0000ffffff00fefefe0098fefe00cbcbfe00cbfefe0065cbfe0065fefe0032cbfe0000cbcb0098cbfe006598980000cbfe000098cb00cbcbcb0098cbcb0065cb");
  ShowHTML("cb0000659800989898003265cb000065cb0032989800003298000032cb000098fe003298cb0000656500006532000032650032cbcb0000323200656598003265");
  ShowHTML("65000065fe003298650032fefe0000989800326598006598cb0000986500326532000032000000fefe00000032009898cb009898650098983200986532006565");
  ShowHTML("320065656500cbcb98009865000098656500cb983200cb9865000000650065323200cb65320032323200cb9800003298fe00cbfecb00656500003265fe0098cb");
  ShowHTML("98009832000098980000cb9898006532000000650000fefecb0065cb9800323265000000980065986500323200009865980032cb980032650000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
  ShowHTML("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000202");
  ShowHTML("020202020202020202020202020202020202020202020202020202020202020202020e0202020202020202020202020202020202020202020202020202020202");
  ShowHTML("0202020202020202000002020202020202020202020202020202020202020202020202020202020202020202020e0e0512020202020202020202020202020202");
  ShowHTML("020202020202020202020202020202020202020200000202020202020202020202020202020202020202020202020202020202020e120e020212042c05120e12");
  ShowHTML("0e120e120e0202020202020202020202020202020202020202020202020202020000020202020202020202020202020202020202020202020202020212321236");
  ShowHTML("303412020e0e2c0f2602120e33342f432c0e12020202020202020202020202020202020202020202020202020000020202020202020202020202020202020202");
  ShowHTML("020202020e120e342f2f352f352f0e0205040205020f0e123533392f352f2c120e12020202020202020202020202020202020202020202020000020202020202");
  ShowHTML("020202020202020202020202020e123234344130382f3e33333412320e020e05040e1232332f442f412f4430300e123212020202020202020202020202020202");
  ShowHTML("0202020200000202020202020202020202020202020202120e2f30482022281a2f2f2f412f300e12050c231d11120e122f33393335330b271a1e2f2f0e120202");
  ShowHTML("02020202020202020202020202020202000002020202020202020202020202020232123630313a1a1e3129304433442f444c120e102a090c1c32120e4b30442f");
  ShowHTML("413330202930332f442f120e02020202020202020202020202020202000002020202020202020202020202120e12361220241d1b1b2f35332f442f4430120e12");
  ShowHTML("0b11151a1e0e0e122f382f332f4435282f4435332f2f2d2f12020202020202020202020202020202000002020202020202020202020e121230204b203a2f333e");
  ShowHTML("4b33443e4430442f384b11190d1b1a1a24201a2848204b304430443333334433381d441511343102020202020202020202020202000002020202020202020202");
  ShowHTML("0e122d3815201a3a352f2d382f44304b3029311b312826111d1b1d1b0838153a1d24151b2f382f302f332f442f252e191d2f2f2f0e0202020202020202020202");
  ShowHTML("00000202020202020202020e123444310d1b3a34300e342f44333a1a2420311d2b1a1a110d1a15200d1a1a26111a241a480d3a28443e44342f30201511301531");
  ShowHTML("1f0e02020202020202020202000002020202020202020e2f2d1e223020280e120e3831441a1b251b240d2f15203a31311f200b31312d2d302f4820301d24151a");
  ShowHTML("1a151d4b2f2c0e2f2f241d24352f2d1202020202020202020000020202020202020e333325151e2f120e021244110d1a3a251e0d3a2030310b060e05260c090c");
  ShowHTML("110204053212302f3a11110d1e091a1e3a3012320225114e2f0d1b2c120202020202020200000202020202020e2d2e2f2f1a282f02020e2f151a1d1a203a1a3a");
  ShowHTML("12120b0b070605060f0c0c1d1505050a0526152c3230343a2f24151a4d1a0e120e022f2f151509300e1202020202020200000202020202343e31243a330e0202");
  ShowHTML("38201c201c141e20310e2c0f0e02150d1a0d090c1c1717141c0d0908111a0d050e020e02123030191a0d1c093a0e020e20204b39332f12020202020200000202");
  ShowHTML("02020e2f2f15151a2f02022d340d28241a1a200b0b1b3a1e1a12191a2f3a201a161c260b1424151e3428080a0f28281a2c322d3e25244d0d11282c020e2f2f33");
  ShowHTML("352f121202020202000002020212332f2f301e3002022f301e0d1a0d1e4a1e1b291b2929291e1c1a332f332f49140f321731302f3331091b291b291e1b100b30");
  ShowHTML("3031241e111d1e32020e3e33332f414c1202020200000202342f3933392f0f02022f392f1d1a284802060f1b1e1b1e1e1b1e0d1b2f3e302016141925211a203a");
  ShowHTML("2f300d1e1b1e1b1e1b29100404203038243a2f2f0e020e2f3533392f351202020000020e332f4439333302021233382f111a380f0e4a1e1b291e291e291e1611");
  ShowHTML("1c0c090937211414170d0d0c240d091e291b1b1b1e1e1110060e2f2f44332f390e02022d3333412f330e12020000022d2f12342f0e02020202022f2f2e302f04");
  ShowHTML("311e201e1a291c1e1e1b3c090d24202b1c0d08111c2b1a11140d081e1e1c1c1e1c1b201e05042f2f2f2f352f0e020202322f2d302f340e020000301202023e30");
  ShowHTML("020202020234333e2f331f0c111e1e1b291e291e291e291e1105204b3a040e0b3a302b100a4a2929021b291e291b291b1c091f30332f3e330e0202020202332f");
  ShowHTML("0f023131000002020202020202020202023039382f20051d08111e1e1e291c1e1e2b1a292031283a4c04021230381e250f201e1e1b1e1e1e1a1e1e1c080c084a");
  ShowHTML("2f2f30300e0202020202022f0e0202020000020202020202020202020230382f3e10100c1c0d0d1a021e291b1e1b2930333038383a05050f202b3a34302f3a1b");
  ShowHTML("1e1e1e1a29110d0c1c0d1d041e2f3330060202020202022f0b02020200000202020202020202020202202f2f03060b110d1721140d0d191e1e1a352f352f2d30");
  ShowHTML("0e04020b2c3a352f2f332f281a1e250c0816211411242011064b2f30070502020202020202020202000002020202020202020202120e283125151e160d163716");
  ShowHTML("161114241c0b4b33332f33300f020e0b0b20442f332f440b2511090d16211716160d10060a0b2015050e02020202020202020202000002020202020202020202");
  ShowHTML("0e0604080f1a1a1e0c161a1e1617140d080d1e3a2f2f31153d0e02122c04103434281524080c1617210d1c491911070a03070307031202020202020202020202");
  ShowHTML("000002020202020202020202120f20281e282b1c0d210d1d291b291c1714160909151d030f05050f26030a0a0d0c0d141414090c0c1b1c170d110a030a050605");
  ShowHTML("0a0e020202020202020202020000020202020202020202120e28201a1e1b1b260d17190c1b1e1a1e1e163f16140c08190504020b1210260d0c113f14140d230c");
  ShowHTML("081b48140d1e10241d0603070512020202020202020202020000020202020202120e12320e1b291e1b1e1e1c1c17162a241a1e1b291a2b161711110c250f0e0b");
  ShowHTML("0b09090c1721140c0c2a0c231a1a37210d1e1e1b1e0406050a0e120202020202020202020000020202020e1205120303321e1b1e1a1e1c2b2516140d230c1a1b");
  ShowHTML("1b1b1a1e1a1e1614080d0c0d08111416080c2308230c1d1b1a1e17171a1e1e1e1b1e1b1e1a1903040e120e020202020200000202020202050a0305061a1b291a");
  ShowHTML("291b1e163714170d0c2a091b1e1b1e1b1e0d0d0920111a111a0d0d090d0c08230c2a1b1b1e2b17141e1e1e1b291e1e1a08050a030405120e1202020200000202");
  ShowHTML("020202030705034a1b29201e1b1a20490d11210d230c231e1a1b1a11081b25302d2f2d2f2e300b1a1d090823230c201a1b2b21111e1b201e1c1e251905060303");
  ShowHTML("070705050202020200000202020202020603261b1e1b291b1e1b291e1e0d17110c2a0c0c1a091e3038310412332f342f2f0b0e2d332f3024241b291b1e161711");
  ShowHTML("1e1b291e1e1b1e240a07060308230a3202020202000002020202020205034a1a1a1e1a1e1e1b1b1e1a0c1716230c2324081b2f2f352f322f2f2831312d2f2d44");
  ShowHTML("352f122819241b1e1b1714111e1e1a1e1a1b1b1b1d2604080305050202020202000002020202020202031b1a1a1e1e1a1e1e291b1e0d1c140c2a0c1d1e2f1036");
  ShowHTML("332f38200d1d240924243a303312042f28141e1b1b1416181e1e291b291e1e1e1b111d050a030202020202020000020202020202020605070f1e25291a1e1b1e");
  ShowHTML("1a1c1914080d1d2f2f33362f151b31302f2f352f2d2f2d30151a2f332d2f15111b1413091e1e1a1b1a1e281b1a15070502030202020202020000020202020232");
  ShowHTML("04050603101c2b371e1b1e1b1e1a0d160d091e2d3e2f30241a30332f2f39332f123431303a201a33310230111c211a0d1b1b1e1e1e1e1b1b1c1a250606020202");
  ShowHTML("020202020000020202020e120308050a031c16160f1e1b1e20150949191b2f0e2d2f0d1b2f333933352f392f0e050e3e352f283a430e2f30191411241b1b1b20");
  ShowHTML("151e1b1e2024150502050e020202020200000202120e05050a030a0f1a1c37160f151e0e1e100d1a3a2f2f3448242f392f2f333933332f3612460e2e332f2f25");
  ShowHTML("1a312f39311111111a1b1e140b1b1e151f1d0805120e050e02020202000002120e120503030a221e1e37142b100a220a0b0f19112f33352f0d3a352f3533352f");
  ShowHTML("352f352f362f362f3533352f22202d12120d19240b1b2026471b1b1e1b1e1503030705120e120202000002020a050a031d1a021e1c1a1e1c1a1b1a0306051c24");
  ShowHTML("2d4631113a2f3333412f3333332f332f2f2f332f332f33332f3130123111110d0a0329311e1a1e1e1e1a1e111d070605040e12020000020202030306151e1b1e");
  ShowHTML("1e1e1e1e1b1a1b0f0525241e362f301e2f33392f3933393335333933352f392f3533392f352f302f36300d1e05031a3a1b1e1e1b1a1b1b1b0b04050a03060202");
  ShowHTML("0000020202030a051b1b1e1b1e1e1e1b1e1e1e190a0d2428332f443033333339332f333933332f3933332f39332f333933331e39332f241a25031a1a291b1e1b");
  ShowHTML("291e1b1b1e0d08030a02020200000202020203201b29281e1b0220291b1e1a1e08161930123015332e0e313035333533352f35333533352f35333533352f1530");
  ShowHTML("0f300b0d1d24071e1a1b281b1e291a291b1b05050502020200000202020202301e1e291e291b291e291a200c11141a300e3024330b050e34332f33332f3b332f");
  ShowHTML("332f3333332f3333332f1b300b2f200d1c0c1c1b291b291e291e1e1b291b1d070202020200000202020202281a1e1a1e1a1e1b1e1a1e110d1416242f12302434");
  ShowHTML("0405022f352f39333533392f2f2f35333533392f352f113a352f2d0d2111151c1b1e1a1e1b1b1e1e1a1b0a0202020202000002020202121a291e291b291e1e1b");
  ShowHTML("2b0c0d211c1b252f332f193933122f36332f333933332f340e302f352f2f3339333320312f2d300c091416091a1b1e1b451e1e1b2b1b1b0f0202020200000202");
  ShowHTML("0202051b241b22291e1e1b1a08113f111b1e152f3444192f352f35423533352f3533392f0b3e35352f332f2f352f19280f30120d230d3f141d1e201b1b1b201b");
  ShowHTML("1915251a020202020000020202050f100b1b291e29161e1a1117171e1e1a1a2f0e342033332f332f332f3333332f33332f2f332f2d0e2d33332f1a300b32200d");
  ShowHTML("082a171414241e1b1b1e1e1e1903080f0e02020200000202020a050a0a1a1a1e11160c0d181c1a1e1a1e143a2d332f30352f3933352f3933353335333533392f");
  ShowHTML("02050e33352f1a2f2e2f190d230c230d14160c1a1a1e1a1e100a07070e1202020000020206070a0506101b1a1e0c24141e1a1e1a1e1a111b33332f2844333339");
  ShowHTML("41332f3933333339332f332d0f04122e332f3a2f332f0d0c08230c230d21160c1e10101a11070605040e0202000002020706030303031a1a1a243f141a1b1a1b");
  ShowHTML("1a1a151a35302f202f33352f352f35423533353335333533432f353335312d0f43251924230c230c23173f11151103201103050303120e020000020202070a05");
  ShowHTML("0a0a1e0c0d14371b1e1a1a2a0c2a0c11300e40311a2f3333412f3333332f3333332f33332f2f33331f1a33323e141b1b1e1a1a0c0c0c1414110c1a260d070a02");
  ShowHTML("04070202000002020202050603050d0d141c1a1e1d0c2308230c230d2f2e2d2f25203933392f391f2d2e352f353335333533352f101b352f2f1a1b1b1a1b1a1b");
  ShowHTML("1a1d0c14140d0c0a05050307020202020000020202020202060b0d14141c110c0c2a0c230c2a082a1a332f2f301a2f2f332f12020e302f39332f333933332f10");
  ShowHTML("1e312f39381e1b1a1e1b1b1a1e1a1a2417140d1119030602020202020000020202020202020c19141417181414163c0d080c082a0d112f040e3320202f2f350a");
  ShowHTML("3d3e352f353335333530263032042f1f1d1b1a1a1a1e20161117211421143f0c06020202020202020000020202020202020c090c090c1114161414141414140d");
  ShowHTML("11111a2d3034301b24303331332f333b2f2f332f151d1e2f2f0b310d1c1a1c161414171414141611160c140c1d020202020202020000020202020202030d1a11");
  ShowHTML("0d0d0c0c080d18141416141721140d2f36333634312415382f2f3933352f340b153a2d2f352f1d1414142117171414140d0d0c0c080d090d0d26020202020202");
  ShowHTML("00000202020202020e030b1a1e161e1c1e371e1c1c110d09090c090d1c2f2c0e302f28311a1525101a2828312f2d0e34310c0d0c090c0d0d1c1c1e111a1b1e1a");
  ShowHTML("060504030a320202020202020000020202020202070a07241a09151e1e1a1a1e1a1c201c1116152424112f2d352f040e352f25302d12052d352f3619191c1914");
  ShowHTML("0d1b251a1c1c19261a1a221a05060705050502020202020200000202020202320a070a0f1e101e1a1e1b1e1e1e1a1b1e1e10061015111130302d0e0e2f2f122f");
  ShowHTML("33340e322f341f2411060603101a1e1a1b110f202b1b1e1a06030a070a0e0202020202020000020202020205030603100606201e1a1c1b1e1a1a1a1e08060303");
  ShowHTML("050a0916111124202d2e2f302f2d310d19141124050a0506082220282c15111e151a1a110305050605050e02020202020000020202020202020306050605121a");
  ShowHTML("1b1e1e1a1a111111260706050a03110d171b1e1a110d191d0d0d0d091714140a0a050602201a1e1a1e1b1e20251a1b110a080a03080712020202020200000202");
  ShowHTML("02020202020202020506030b1b1b201e1a160806150b030a050a150d141a201b1b1b200c0c08232321140803050a050b1b1b1a1e1a1e201e2c1e200605050305");
  ShowHTML("0202020202020202000002020202020202020202020706051a101a1c2b161c1e1e1b1e1b1c0f10110d141e1a1b1a1e230c2a0c19110c14030a0e1a1b1e1a291a");
  ShowHTML("1e1b1e1a20110f07050202020202020202020202000002020202020202020202020505060803061e111e1b1e1a1b1b1e201b06191814111e1b1b1a0823082314");
  ShowHTML("080d060605151a1a1b1e281e1b1b1b1e1106030505020202020202020202020200000202020202020202020202050a0504070a1a111a1e1a1b1e1e1b1e100605");
  ShowHTML("1c14141a1e1a1e230c2a24140d0d060f060508040d1b1e1b1b1a1b1a10050607120202020202020202020202000002020202020202020202020a0305050a0707");
  ShowHTML("051b201b1b1b1a1e241e1526070d14141a1b202a232421141d06050d191615260b1e201b1a1b1a26030307050e02020202020202020202020000020202020202");
  ShowHTML("0202020202030a0710030a05061a1a1a1e1b1e1b1e1a1e1a280914211b1a1e230814140d1d050a2626081e1a1b1a1e1e291a1a0605030a031202020202020202");
  ShowHTML("0202020200000202020202020202020202050705070a0706050b1a1e1b1b1a1e201a1a1b1a110c171a1b1a082314170c062608161111241e261e1a1c271b0306");
  ShowHTML("070705070e0202020202020202020202000002020202020202020202020e0f0708050603040306101a07101b1a1b1b1a1e0f110c161a1e2324140d140d141920");
  ShowHTML("1e252517261b19071b240605040306031202020202020202020202020000020202020202020202020203030305070202020a030a150f03151a1a201b11190a0d");
  ShowHTML("211a150c140d19060506051a220d19110d110303220d020202020202020202020202020202020202000002020202020202020202020202020202020202070f03");
  ShowHTML("0a030f030b1a1b1c101a1d1117141e15170c1403151f1c0f1c1d1414190606051002020202020202020202020202020202020202000002020202020202020202");
  ShowHTML("02020202020202020202030f0305030a030a0515080303100d161417140d030a180d03190d08050a030a03030502020202020202020202020202020202020202");
  ShowHTML("00000202020202020202020202020202020202020202040706030a05080310030a030f031411141414060a0306030a070a060802090303030202020202020202");
  ShowHTML("0202020202020202020202020000020202020202020202020202020202020202020203050508050305060305020a05030d0d13140c0603050503050305020305");
  ShowHTML("030503070202020202020202020202020202020202020202000002020202020202020202020202020202020202020f0608050202020210070607060510110d0c");
  ShowHTML("11030a030a050a050202020205031202020202020202020202020202020202020202020200000202020202020202020202020202020202020202020202020202");
  ShowHTML("0202020303060704030b0c0d030a050a030503020202020202020e02020202020202020202020202020202020202020200000202020202020202020202020202");
  ShowHTML("02020202020202020202020202020205080308050604020906030a030a0306020202020202020202020202020202020202020202020202020202020200000202");
  ShowHTML("02020202020202020202020202020202020202020202020202020202050603070202020202050307020302020202020202020202020202020202020202020202");
  ShowHTML("0202020202020202000002020202020202020202020202020202020202020202020202020202020202030202020202020202020304020202020202020202020202020202020202020202020202020202020202020000040000002701ffff030000000000}}");
  ShowHTML("\\par }\\pard\\plain \\s15\\qc \\li0\\ri0\\widctlpar\\tqc\\tx4419\\tqr\\tx8838\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 \\fs20\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\b\\f36\\fs28\\insrsid9184405 Presid\\'eancia da Rep\\'fablica}{");
  ShowHTML("\\insrsid9184405 ");
  ShowHTML("\\par }\\pard\\plain \\qc \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\f1\\insrsid9184405 Secretaria Especial de Pol\\'edticas de Promo\\'e7\\'e3o da Igualdade Racial");
  ShowHTML("");
  ShowHTML("\\par }{\\insrsid9184405 ");
  ShowHTML("\\par ");
  ShowHTML("\\par ");
  ShowHTML("\\par }\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 {\\f1\\insrsid9184405 Autoriza\\'e7\\'e3o n\\'ba " . f($RS,'codigo_interno'));
  ShowHTML("\\par ");
  ShowHTML("\\par ");
  ShowHTML("\\par ");
  ShowHTML("\\par \\tab \\tab \\tab \\tab \\tab              Bras\\'edlia-DF, ". FormataDataEdicao(time()));
  ShowHTML("\\par ");
  ShowHTML("\\par }{\\f1\\insrsid9184405 ");
  ShowHTML("\\par Ao Senhor");
  ShowHTML("\\par }\\pard\\plain \\s2\\ql \\li0\\ri0\\keepn\\widctlpar\\aspalpha\\aspnum\\faauto\\outlinelevel1\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 \\b\\f1\\fs22\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\fs24\\insrsid9184405 S\\'edlvio Andrade Junior");
  ShowHTML("\\par }\\pard\\plain \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\f1\\insrsid9184405 Coordenador-Geral de Log\\'edstica");
  ShowHTML("\\par Minist\\'e9rio da Justi\\'e7a");
  ShowHTML("\\par ");
  ShowHTML("\\par ");
  ShowHTML("\\par ");
  ShowHTML("\\par \\tab       Senhor Coordenador,");
  ShowHTML("\\par }{\\f1\\insrsid9184405 ");
  ShowHTML("\\par ");
  if (addDays($w_primeira_saida,-$w_prazo)<addDays(time(),-1)) {
     ShowHTML("\\par }\\pard \\qj \\fi708\\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid2980338 {\\f1\\insrsid2980338         Autorizo o encaminhamento da PCD, em nome do(a) " . f($RS1,'nm_tipo_vinculo') . " }{\\b\\f1\\insrsid2980338 " . f($RS1,'nm_pessoa') . ", }{");
     ShowHTML("\\f1\\insrsid2980338 que se encontra fora do prazo de (" . w_prazo . ") dez dias, conforme Portaria n\\'ba 98 de 16 de julho de 2003 do Minist\\'e9rio do Planejamento.");
  } else {
     ShowHTML("\\par }\\pard \\qj \\fi708\\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 {\\f1\\insrsid9184405        Reporto-me, mui respeitosamente a Vossa Senhoria, a fim de solicitar-lhe as provid\\'eancias cab\\'edveis e necess\\'e1");
     ShowHTML("rias, para a concess\\'e3o de di\\'e1rias e passagens para o(a) " . f($RS1,'nm_tipo_vinculo') . " }{\\b\\f1\\insrsid9184405 " . f($RS1,'nm_pessoa') . "}{\\f1\\insrsid9184405 .}{\\b\\f1\\insrsid9184405 ");
  }
  ShowHTML("\\par }\\pard\\plain \\s16\\qj \\fi708\\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 \\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid9184405       ");
  ShowHTML("\\par }\\pard\\plain \\qj \\fi708\\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\f1\\insrsid9184405 ");
  ShowHTML("\\par ");
  ShowHTML("\\par }\\pard \\qc \\fi708\\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 {\\f1\\insrsid9184405 Atenciosamente,");
  ShowHTML("\\par ");
  ShowHTML("\\par ");
  ShowHTML("\\par ");
  ShowHTML("\\par }\\pard\\plain \\s1\\qc \\li0\\ri0\\keepn\\widctlpar\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0\\itap0\\pararsid9184405 \\b\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid9184405 " . f($RS,'nm_tit_exec'));
  ShowHTML("\\par }\\pard\\plain \\qc \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid3545814 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\f1\\insrsid9184405 " . f($RS,'nm_unidade_exec') . "}{\\f1\\insrsid12326642 ");
  ShowHTML("\\par \\page }{\\insrsid12326642\\charrsid3545814 ");
  ShowHTML("\\par }\\trowd \\irow0\\irowband0\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4500 \\cellx3060\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7220 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\page \\~\\cell }{");
  ShowHTML("\\b\\fs18\\insrsid12326642\\charrsid5664258 PROPOSTA DE CONCESS\\'c3O DE PASSAGENS E DI\\'c1RIAS\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow0\\irowband0");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4500 \\cellx3060\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7220 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow1\\irowband1\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 ");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4500 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7220 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid12326642\\charrsid5664258 \\~}{\\b\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }{\\fs14\\insrsid12326642\\charrsid5664258 ");
  if (f($RS,'tipo_missao')=='I') {
     ShowHTML("( x ) INICIAL               (   ) PRORROGA\\'c7\\'c3O               (   ) COMPLEMENTA\\'c7\\'c3O\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  } elseif (f($RS,'tipo_missao')=='P') {
     ShowHTML("(   ) INICIAL               ( x ) PRORROGA\\'c7\\'c3O               (   ) COMPLEMENTA\\'c7\\'c3O\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  } elseif (f($RS,'tipo_missao')=='C') {
     ShowHTML("(   ) INICIAL               (   ) PRORROGA\\'c7\\'c3O               ( x ) COMPLEMENTA\\'c7\\'c3O\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  } else {
     ShowHTML("(   ) INICIAL               (   ) PRORROGA\\'c7\\'c3O               (   ) COMPLEMENTA\\'c7\\'c3O\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  }
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow1\\irowband1");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4500 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7220 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow2\\irowband2\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb");
  ShowHTML("\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2318 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1232 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth928 \\cellx5220\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1812 \\cellx7032\\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth452 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596 \\cellx8080");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs12\\insrsid12326642\\charrsid5664258 \\~}{\\b\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell \\cell }{");
  ShowHTML("\\fs12\\insrsid12326642\\charrsid5664258 \\~\\cell \\~}{\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }{\\fs12\\insrsid12326642\\charrsid5664258 \\~}{\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }{\\fs12\\insrsid12326642\\charrsid5664258 \\~}{");
  ShowHTML("\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }{\\fs12\\insrsid12326642\\charrsid5664258 \\~}{\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }{\\fs12\\insrsid12326642\\charrsid5664258 \\~}{\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow2\\irowband2\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2318 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1232 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth928 \\cellx5220");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1812 \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth452 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow3\\irowband3\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone"); 
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2318 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1232 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth928 \\cellx5220\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1812 \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1048 \\cellx8080\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 ");
  ShowHTML("\\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs18\\insrsid12326642\\charrsid5664258 \\~}{\\f1\\fs18\\insrsid12326642\\charrsid5664258 \\cell \\cell }{\\fs18\\insrsid12326642\\charrsid5664258 \\~}{");
  ShowHTML("\\f1\\fs18\\insrsid12326642\\charrsid5664258 \\cell \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs18\\insrsid12326642\\charrsid5664258    BENEFICI\\'c1RIO}{\\b\\f1\\fs18\\insrsid12326642\\charrsid5664258 ");
  ShowHTML("\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs18\\insrsid12326642\\charrsid5664258 \\cell }{\\fs18\\insrsid12326642\\charrsid5664258 \\~}{\\f1\\fs18\\insrsid12326642\\charrsid5664258 \\cell \\cell ");
  ShowHTML("}\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs18\\insrsid12326642\\charrsid5664258 \\trowd \\irow3\\irowband3");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 ");
  ShowHTML("\\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2318 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1232 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth928 \\cellx5220\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1812 \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1048 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow4\\irowband4\\ts11\\trrh150\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4500 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7220 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs22\\insrsid12326642\\charrsid5664258 MINIST\\'c9RIO DA JUSTI\\'c7A \\cell }");
  if (strtoupper(trim(f($RS1,'nm_tipo_vinculo')))=='COLABORADOR EVENTUAL') {
     ShowHTML("\\pard \\ql \\li0\\ri-280\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin-280\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 (  ) Servidor    ( X ) Colaborador Eventual    (  ) Convidado  (  ) Assessoramento Especial \\cell } ");
  } elseif (strtoupper(trim(f($RS1,'nm_tipo_vinculo')))=='QUADRO PERMANENTE') {
     ShowHTML("\\pard \\ql \\li0\\ri-280\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin-280\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 ( X ) Servidor    (  ) Colaborador Eventual    (  ) Convidado  (  ) Assessoramento Especial \\cell } ");
  } elseif (strtoupper(trim(f($RS1,'nm_tipo_vinculo')))=='DIRIGENTE' || !(strpos(f($RS1,'nm_tipo_vinculo'),'Função')===false)) {
     ShowHTML("\\pard \\ql \\li0\\ri-280\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin-280\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 (  ) Servidor    (  ) Colaborador Eventual    (  ) Convidado  ( X ) Assessoramento Especial \\cell } ");  
  } elseif (f($RS1,'interno')=='N') {
     ShowHTML("\\pard \\ql \\li0\\ri-280\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin-280\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 (  ) Servidor    (  ) Colaborador Eventual    ( X ) Convidado  (  ) Assessoramento Especial \\cell } ");
  } else {
     ShowHTML("\\pard \\ql \\li0\\ri-280\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin-280\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 (  ) Servidor    (  ) Colaborador Eventual    (  ) Convidado  (  ) Assessoramento Especial \\cell } ");
  }
  ShowHTML("\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow4\\irowband4\\ts11\\trrh150\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4500 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7220 \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow5\\irowband5");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2318 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1232 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3788 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\~}{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\~}{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell \\cell }{\\fs16\\insrsid12326642\\charrsid5664258 \\~}{");
  ShowHTML("\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow5\\irowband5");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2318 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1232 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3788 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow6\\irowband6\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4500 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2399 \\cellx5459\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 ");
  ShowHTML("\\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4821 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\~\\cell }\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\b\\fs16\\insrsid12326642\\charrsid5664258 N\\'ba:\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\~\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow6\\irowband6");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4500 \\cellx3060\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2399 \\cellx5459\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 ");
  ShowHTML("\\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4821 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow7\\irowband7\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth8867 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid12326642\\charrsid5664258 1 - PROPONENTE\\cell }{\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow7\\irowband7");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 ");
  ShowHTML("\\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth8867 \\cellx10354\\row }\\trowd \\irow8\\irowband8");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 \\'d3RG\\'c3O/UNIDADE\\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow8\\irowband8\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow9\\irowband9\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 SECRETARIA ESPECIAL DE POL\\'cdTICAS DE PROMO\\'c7\\'c3O DA IGUALDADE RACIAL\\cell \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow9\\irowband9");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow10\\irowband10");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 NOME\\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow10\\irowband10\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow11\\irowband11\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr");
  ShowHTML("\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 " . f($RS,'nm_titular') . "\\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow11\\irowband11\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow12\\irowband12\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 CARGO/FUN\\'c7\\'c3O\\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow12\\irowband12\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow13\\irowband13\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr");
  ShowHTML("\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 Subsecretário da " . f($RS,'nm_unidade_resp') . "  \\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow13\\irowband13\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow14\\irowband14\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1110 \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth643 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1052 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1167 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1573 \\cellx7032");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth452 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid12326642\\charrsid5664258 2 - PROPOSTO\\cell }{");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 \\cell \\cell \\cell \\cell \\cell \\cell \\cell \\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow14\\irowband14");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1110 \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth643 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1052 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1167 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1573 \\cellx7032");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth452 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow15\\irowband15\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth8472 \\cellx7032\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3248 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 NOME\\cell TELEFONE\\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow15\\irowband15\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth8472 \\cellx7032\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3248 \\cellx10280");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow16\\irowband16");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth8472 \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3248 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 " . f($RS1,'nm_pessoa'));
  ShowHTML("\\cell (" . Nvl(f($RS1,'ddd'),'  ') . ") " . Nvl(f($RS1,'nr_telefone'),'       ') . " / ". Nvl(f($RS1,'nr_celular'),'       ') . "\\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow16\\irowband16");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth8472 \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3248 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow17\\irowband17\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb");
  ShowHTML("\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2219 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2025 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 CARGO/FUN\\'c7\\'c3O\\cell MATR\\'cdCULA\\cell CI\\cell CPF\\cell \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow17\\irowband17");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2219 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2025 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796 \\cellx10280");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow18\\irowband18");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2219 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2025 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796 ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 ---\\cell \\~\\cell " . f($RS1,'rg_numero') . " " . f($RS1,'rg_emissor') . "\\cell " . f($RS1,'cpf') . "\\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow18\\irowband18");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2219 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2025 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796 ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow19\\irowband19");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 \\cellx1487\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2805 \\cellx4292\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2740 \\cellx7032\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3248 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 BANCO\\cell AG\\'caNCIA\\cell C/C N\\'ba\\cell \\'d3RG\\'c3O DE ORIGEM/UNIDADE\\cell \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow19\\irowband19");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 \\cellx1487\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2805 \\cellx4292\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2740 \\cellx7032\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3248 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow20\\irowband20");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2805 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2740 \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3248 ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 " . f($RS1,'nm_banco') . "\\cell " . f($RS1,'cd_agencia') . "\\cell " . f($RS1,'nr_conta') . "\\cell \\~\\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow20\\irowband20");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2805 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2740 \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3248 ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow21\\irowband21");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5732 ");
  ShowHTML("\\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6062 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\b\\fs16\\insrsid12326642\\charrsid5664258 3 - DESCRI\\'c7\\'c3O SUCINTA DO SERVI\\'c7O A SER EXECUTADO\\cell }{\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow21\\irowband21\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5732 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6062 \\cellx10354\\row }\\trowd \\irow22\\irowband22");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 Objetivo/Assunto a ser tratado/Evento\\cell \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow22\\irowband22");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow23\\irowband23");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmgf\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid9440179\\charrsid5664258 " . f($RS,'descricao'));
  ShowHTML("\\par }{\\fs16\\insrsid9440179\\charrsid5664258 \\~");
  ShowHTML("\\par }{\\b\\fs16\\insrsid9440179\\charrsid5664258 \\~");
  ShowHTML("\\par \\~}{\\fs16\\insrsid9440179\\charrsid5664258 \\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid9440179\\charrsid5664258 \\trowd \\irow23\\irowband23");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmgf\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow24\\irowband24");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid9440179\\charrsid5664258 \\cell \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid9440179\\charrsid5664258 \\trowd \\irow24\\irowband24\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrtbl \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354");
  ShowHTML("\\row }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid9440179\\charrsid5664258 \\cell }{\\fs16\\insrsid9440179\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid9440179\\charrsid5664258 \\trowd \\irow25\\irowband25");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid9440179\\charrsid5664258 \\cell }{\\fs16\\insrsid9440179\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid9440179\\charrsid5664258 \\trowd \\irow26\\irowband26");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow27\\irowband27");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid9440179\\charrsid5664258 \\cell }{\\f1\\fs16\\insrsid9440179\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid9440179\\charrsid5664258 \\trowd \\irow27\\irowband27");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow28\\irowband28");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680\\clshdrawnil \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid12326642\\charrsid5664258");
  ShowHTML("4 - BENEF\\'cdCIOS RECEBIDOS PELO SERVIDOR\\cell }{\\fs16\\insrsid12326642\\charrsid5664258 \\cell }{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow28\\irowband28\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680\\clshdrawnil \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow29\\irowband29");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5732\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx4292\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3192\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard ");
  if (nvl(f($RS,'valor_alimentacao'),0)>0) {
     ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs14\\insrsid12326642\\charrsid2128030  AUX\\'cdLIO-ALIMENTA\\'c7\\'c3O       SIM ( x )   N\\'c3O (   )     -                  \\cell }\\pard ");
  } else {
     ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs14\\insrsid12326642\\charrsid2128030  AUX\\'cdLIO-ALIMENTA\\'c7\\'c3O       SIM (   )   N\\'c3O ( X )     -                  \\cell }\\pard ");
  }
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 Valor R$\\cell }\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid16481867 {");
  ShowHTML("\\fs16\\insrsid16481867 " . formatNumber(nvl(f($RS,'valor_alimentacao'),0)) . "}{\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow29\\irowband29");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5732\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx4292\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3192\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow30\\irowband30\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5732\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3192\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard ");
  if (nvl(f($RS,'valor_transporte'),0)>0) {
     ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs14\\insrsid12326642\\charrsid2128030 AUX\\'cdLIO-TRANSPORTE          SIM ( x )   N\\'c3O (   )     -                  \\cell }\\pard ");
  } else {
     ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs14\\insrsid12326642\\charrsid2128030 AUX\\'cdLIO-TRANSPORTE          SIM (   )   N\\'c3O ( X )     -                  \\cell }\\pard ");
  }
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 Valor R$\\cell }\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid16481867 {");
  ShowHTML("\\fs16\\insrsid16481867 " . formatNumber(nvl(f($RS,'valor_transporte'),0)) . "}{\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow30\\irowband30");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5732\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3192\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow31\\irowband31\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5040\\clshdrawnil \\cellx3600\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx5760\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4520\\clshdrawnil ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 ");
  ShowHTML("{\\b\\fs16\\insrsid12326642\\charrsid5664258 5 - DADOS DA VIAGEM/ C\\'c1LCULO DAS DI\\'c1RIAS\\cell }{\\fs16\\insrsid12326642\\charrsid5664258 \\cell }{\\b\\fs16\\insrsid12326642\\charrsid5664258 \\cell }{\\fs16\\insrsid12326642\\charrsid5664258 \\cell }{");
  ShowHTML("\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow31\\irowband31");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5040\\clshdrawnil \\cellx3600\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx5760\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4520\\clshdrawnil ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow32\\irowband32");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1003\\clshdrawnil \\cellx3600\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx5760\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1724\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 \\~\\cell DATA DE\\cell DATA DE\\cell QUANTIDADE\\cell ");
  ShowHTML("VALOR UNIT\\'c1RIO\\cell TOTAL POR\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow32\\irowband32");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1003\\clshdrawnil \\cellx3600\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx5760\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1724\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow33\\irowband33\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1003\\clshdrawnil \\cellx3600\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx5760\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1724\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\fs12\\insrsid12326642\\charrsid5664258 DESTINOS\\cell IN\\'cdCIO\\cell T\\'c9RMINO\\cell DE DI\\'c1RIAS\\cell R$\\cell LOCALIDADE - R$\\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }\\pard");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0"); 
  ShowHTML("{\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow33\\irowband33");   
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1003\\clshdrawnil \\cellx3600\\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx5760\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1724\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }");
  $RS2 = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave, null, 'DADFIN');
  $RS2 = SortArray($RS2,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RS2)>0) {
    $i = 1;
    foreach($RS2 as $row) {
      $w_vetor_trechos[$i][1] = f($row,'sq_diaria');
      $w_vetor_trechos[$i][2] = f($row,'cidade_dest');
      $w_vetor_trechos[$i][3] = f($row,'nm_destino');
      $w_vetor_trechos[$i][4] = FormataDataEdicao(f($row,'phpdt_chegada'));
      $w_vetor_trechos[$i][5] = FormataDataEdicao(f($row,'phpdt_saida'));
      $w_vetor_trechos[$i][6] = formatNumber(Nvl(f($row,'quantidade'),0),1,',','.');
      $w_vetor_trechos[$i][7] = formatNumber(Nvl(f($row,'valor'),0));
      $w_vetor_trechos[$i][8] = Nvl(f($row,'quantidade'),0);
      $w_vetor_trechos[$i][9] = Nvl(f($row,'valor'),0);
      if ($i>1) {
        $w_vetor_trechos[$i-1][5] = FormataDataEdicao(f($row,'phpdt_saida'));
      }
      $i += 1;
    } 
    $j       = $i;
    $i       = 1;
    $w_total = 0;
    while($i!=($j-1)) {
      ShowHTML("\\trowd \\irow34\\irowband34\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 ");
      ShowHTML("\\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
      ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1003\\clshdrawnil \\cellx3600\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone ");
      ShowHTML("\\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx5760\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb");
      ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1724\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
      ShowHTML("\\clcfpat8\\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard ");     
      ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 ".$w_vetor_trechos[$i][3]. "\\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
      ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 ". $w_vetor_trechos[$i][4] . "\\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid6649499 {\\fs16\\insrsid16481867\\charrsid5664258 ". $w_vetor_trechos[$i][5] . "\\cell }\\pard ");
      ShowHTML("\\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 ". $w_vetor_trechos[$i][6] . "\\cell ". $w_vetor_trechos[$i][7] . "\\cell ". formatNumber(($w_vetor_trechos[$i][8]*$w_vetor_trechos[$i][9])) . "\\cell }\\pard ");
      ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 ");
      ShowHTML("{ \\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow34\\irowband34\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
      ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1003\\clshdrawnil ");
      ShowHTML("\\cellx3600\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr");
      ShowHTML("\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx5760\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
      ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1724\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16\\clcfpatraw8 ");
      ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\pard");
      $w_total += ($w_vetor_trechos[$i][8]*$w_vetor_trechos[$i][9]);
      $i += 1;
    }
  }
  ShowHTML("\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 ");
  ShowHTML("{ \\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow37\\irowband37");

  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3\\tblrsid16481867 \\clvertalb\\clbrdrt\\");

  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1003\\clshdrawnil \\cellx3600\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx5760\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1724\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\clcfpat8\\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil ");
  ShowHTML("}\\trowd \\irow38\\irowband38\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx5760\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1272\\clshdrawnil \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth452\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid2128030 (\\'c9 obrigat\\'f3rio justificar, neste campo, in\\'ed");
  ShowHTML("cio e t\\'e9rmino de viagens ");
  ShowHTML("\\par }{\\fs16\\insrsid16481867\\charrsid13388689 sextas-feiras, s\\'e1bados, domigos e feriados)");
  ShowHTML("\\par }{\\fs18\\insrsid16481867 \\charrsid13388689 " . nvl(f($RS,'justificativa_dia_util'),'---'));
  ShowHTML("\\par }{\\fs18\\insrsid16481867\\charrsid13388689 \\~}{\\fs16\\insrsid16481867\\charrsid2128030 \\cell }{\\fs16\\insrsid16481867\\charrsid5664258 \\~\\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 \\~\\cell \\~\\cell }\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid6649499 {\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs18\\insrsid16481867\\charrsid2128030 \\trowd \\irow38\\irowband38\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmgf\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrtbl \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\clcfpat8\\clcbpat8\\clbgdcross\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1080\\clcbpatraw8\\clcfpatraw8\\clbgdcross \\cellx5760\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1272\\clshdrawnil \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth452\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow39\\irowband39\\ts11\\trrh164\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl ");
  ShowHTML("\\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16\\clcfpatraw8 ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 ");
  ShowHTML("{\\fs16\\insrsid16481867\\charrsid13388689 \\cell }{\\fs16\\insrsid16481867\\charrsid5664258 (a) subtotal\\cell }\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 " . formatNumber(nvl($w_total,0)) . "\\cell ");
  ShowHTML("}\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow39\\irowband39\\ts11\\trrh164\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow40\\irowband40\\ts11\\trrh119\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 ");
  ShowHTML("\\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 ");
  ShowHTML("{\\fs18\\insrsid16481867 \\cell }{\\fs16\\insrsid16481867\\charrsid5664258 (b) adicional\\cell }\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 " . formatNumber(nvl(f($RS,'valor_adicional'),0)) . "\\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow40\\irowband40\\ts11\\trrh119\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow41\\irowband41\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 ");
  ShowHTML("\\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 ");
  ShowHTML("{\\fs18\\insrsid16481867\\charrsid13388689 \\cell }{\\fs16\\insrsid16481867\\charrsid5664258 (c) desconto aux\\'edlio-alimenta\\'e7\\'e3o\\cell }\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid6649499 {");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 " . formatNumber(nvl(f($RS,'desconto_alimentacao'),0)) . "\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow41\\irowband41");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow42\\irowband42\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl ");
  ShowHTML("\\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 ");
  ShowHTML("\\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 ");
  ShowHTML("{\\fs18\\insrsid16481867\\charrsid13388689 \\cell }{\\fs16\\insrsid16481867\\charrsid5664258 (d) desconto aux\\'edlio-transporte\\cell }\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid6649499 {");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 " . formatNumber(nvl(f($RS,'desconto_transporte'),0)) . "\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow42\\irowband42");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvmrg\\clvertalb\\clbrdrt\\brdrtbl \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw8\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow43\\irowband43\\ts11\\trrh195\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182\\clshdrawnil \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth745\\clshdrawnil \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1110\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth643\\clshdrawnil \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1440\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb");
  ShowHTML("\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\clcfpat8\\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 \\~}{");
  ShowHTML("\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }{\\fs16\\insrsid16481867\\charrsid5664258 \\~}{\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }{\\fs16\\insrsid16481867\\charrsid5664258 \\~}{\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }{");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 \\~}{\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }{\\fs16\\insrsid16481867\\charrsid5664258 \\~}{\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }{\\b\\fs12\\insrsid16481867\\charrsid5664258  TOTAL (a + b - c - d)\\cell ");
  ShowHTML("}\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 " . formatNumber(Nvl($w_total,0)+Nvl(f($RS,'valor_adicional'),0)-Nvl(f($RS,'desconto_alimentacao'),0)-Nvl(f($RS,'desconto_transporte'),0)) . "\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow43\\irowband43");
  ShowHTML("\\ts11\\trrh195\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182\\clshdrawnil \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth745\\clshdrawnil \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1110\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth643\\clshdrawnil \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1440\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\clcfpat8\\clcbpat16\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2796\\clcbpatraw16\\clcfpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow44\\irowband44\\ts11\\trrh152\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596\\clcbpatraw8 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200\\clcbpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard ");
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 \\~\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 \\~\\cell \\~\\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow44\\irowband44\\ts11\\trrh152\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2804\\clshdrawnil \\cellx7484\\clvertalb");
  ShowHTML("\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596\\clcbpatraw8 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\clcbpat8\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200\\clcbpatraw8 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow45\\irowband45");
  ShowHTML("\\ts11\\trrh132\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5600\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\fs14\\insrsid16481867\\charrsid13388689 DATA: " .FormataDataEdicao(time()) . "\\cell }{\\fs16\\insrsid16481867\\charrsid5664258 __________________________________________\\cell }{\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow45\\irowband45");
  ShowHTML("\\ts11\\trrh132\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5600\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow46\\irowband46");
  ShowHTML("\\ts11\\trrh102\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5600\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\fs16\\insrsid16481867\\charrsid5664258 \\~\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs14\\insrsid16481867\\charrsid13388689        ASSINATURA/CARIMBO DO PROPONENTE\\cell }{");
  ShowHTML("\\f1\\fs16\\insrsid16481867\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow46\\irowband46");
  ShowHTML("\\ts11\\trrh102\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6120\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5600\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow47\\irowband47");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182\\clshdrawnil \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth745\\clshdrawnil \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1110\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth643\\clshdrawnil \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1440\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth779\\clshdrawnil \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1573\\clshdrawnil \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth452\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596\\clshdrawnil \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid16481867\\charrsid5664258 \\~\\cell \\~\\cell \\~\\cell \\~\\cell \\~\\cell \\~\\cell \\~\\cell \\~\\cell \\~\\cell \\~\\cell }{\\f1\\fs16\\insrsid16481867\\charrsid5664258 ");
  ShowHTML("\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid16481867\\charrsid5664258 \\trowd \\irow47\\irowband47");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182\\clshdrawnil \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth745\\clshdrawnil \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1110\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth643\\clshdrawnil \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1440\\clshdrawnil \\cellx4680\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth779\\clshdrawnil \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1573\\clshdrawnil \\cellx7032\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth452\\clshdrawnil \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596\\clshdrawnil \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\row }\\trowd \\irow48\\irowband48");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037\\clshdrawnil \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7683\\clshdrawnil \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74\\clshdrawnil \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid16481867\\charrsid5664258 ");
  
  ShowHTML("6 - BILHETE DE PASSAGEM:\\cell }{");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 \\cell }{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow48\\irowband48");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037 ");
  ShowHTML("\\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7683 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow49\\irowband49\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354");
  ShowHTML("\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 RESERVA EFETUADA COM O MENOR PRE\\'c7O\\cell }{\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow49\\irowband49");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow50\\irowband50");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 \\~\\cell \\cell \\~\\cell }{\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow50\\irowband50");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow51\\irowband51");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258  (  ) EMISS\\'c3O LO");
  ShowHTML("CAL                                       (   ) PTA:  ______________________________________                                                        \\cell }{\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow51\\irowband51");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid5664258 \\~\\cell }{\\f1\\fs12\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid5664258 \\trowd \\irow52\\irowband52");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow53\\irowband53");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5732 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1167 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4821 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 DATA e HOR\\'c1RIO:                    IDA:                              \\cell \\cell VOLTA:                                 ");
  ShowHTML("\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow53\\irowband53");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5732 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1167 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4821 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow54\\irowband54");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth745 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1110 \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5483 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone"); 
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\~\\cell \\cell \\cell \\cell \\~\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow54\\irowband54");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth745 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1110 \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth5483 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow55\\irowband55\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037 \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth643 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3600 \\cellx6840\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth644 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 V");
  ShowHTML("\\'f4o:                                                                  }{\\v\\fs16\\insrsid12326642\\charrsid5664258                                                                      }{\\fs16\\insrsid12326642\\charrsid5664258 \\cell        \\cell \\cell        ");
  ShowHTML("\\cell \\cell C\\'d3D.:       \\cell }{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow55\\irowband55");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4037 \\cellx2597\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth643 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3600 \\cellx6840\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth644 \\cellx7484\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth596 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow56\\irowband56");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\~\\cell \\cell \\~\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow56\\irowband56");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow57\\irowband57");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1052 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3788 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 ");
  ShowHTML("Valor da passagem (num\\'e9rico e por extenso):\\cell \\cell \\cell \\~\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 ");
  ShowHTML("\\trowd \\irow57\\irowband57\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth1052 \\cellx4292\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3788 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl");
  ShowHTML("\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow58\\irowband58\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3");
  ShowHTML("\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\~\\cell \\cell \\~\\cell }{");
  ShowHTML("\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow58\\irowband58");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow59\\irowband59");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs14\\insrsid12326642\\charrsid13388689 ");
  ShowHTML("DATA:  ________/____________/___________\\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 __________________________________________________\\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid5664258 \\trowd \\irow59\\irowband59\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow60\\irowband60");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2498 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid1071686\\charrsid5664258 \\cell \\cell }{\\fs14\\insrsid1071686\\charrsid13388689 ASSINATURA e CARIMBO\\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid1071686\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid1071686\\charrsid14172830 \\trowd \\irow60\\irowband60\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2498 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 ");
  ShowHTML("\\cellx10354\\row }\\trowd \\irow61\\irowband61\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrtbl \\clbrdrb\\brdrtbl \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2498 \\cellx3240\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrtbl \\clbrdrr\\brdrtbl \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid5664258 \\cell \\cell }{\\fs14\\insrsid12326642\\charrsid13388689 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid5664258 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow61\\irowband61\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrtbl \\clbrdrb");
  ShowHTML("\\brdrtbl \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2498 \\cellx3240\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrtbl \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 ");
  ShowHTML("\\cellx10354\\row }\\trowd \\irow62\\irowband62\\ts11\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\v\\insrsid12326642 \\cell }{\\v\\f1\\insrsid12326642 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\v\\insrsid12326642 ");
  ShowHTML("\\trowd \\irow62\\irowband62\\ts11\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow63\\irowband63");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 ");
  ShowHTML("\\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth8793 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid12326642\\charrsid14172830 7  - CONCESS\\'c3O\\cell }{");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid14172830 \\cell }{\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow63\\irowband63");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2927 ");
  ShowHTML("\\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth8793 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow64\\irowband64\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354");
  ShowHTML("\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid14172830 NA QUALIDADE DE ORDENADOR DE DESPESA AUTORIZO O PAGAMENTO DA(S) DI\\'c1RIA(S) E EMISS\\'c3O DA REQUISI\\'c7\\'c3");
  ShowHTML("O DE TRANSPORTE POR VIA\\cell }{\\f1\\fs12\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid14172830 \\trowd \\irow64\\irowband64");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow65\\irowband65");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid14172830  (    ) TERRESTRE                         (     ) A\\'c9REA                                  RT N\\'ba");
  ShowHTML("______________                                                             DATA: ____/____/________     \\cell }{\\f1\\fs12\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs12\\insrsid12326642\\charrsid14172830 \\trowd \\irow65\\irowband65\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row ");
  ShowHTML("}\\trowd \\irow66\\irowband66\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid14172830 \\~\\cell \\cell \\~\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow66\\irowband66");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow67\\irowband67");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 ");
  ShowHTML("\\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2498 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid14172830 \\~\\cell \\cell                                    __________________________________________________\\cell }{");
  ShowHTML("\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow67\\irowband67");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrtbl \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 ");
  ShowHTML("\\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2498 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow68\\irowband68");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2498 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid14172830 \\~\\cell \\cell }{\\fs14\\insrsid12326642\\charrsid15559966 ");
  ShowHTML("                                                      ASSINATURA/CARIMBO DO ORDENADOR DE DESPESAS\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs18\\insrsid12326642\\charrsid15559966 \\trowd \\irow68\\irowband68\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2498 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone ");
  ShowHTML("\\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 ");
  ShowHTML("\\cellx10354\\row }\\trowd \\irow69\\irowband69\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 \\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\b\\fs16\\insrsid12326642\\charrsid14172830 8 - SETOR FINANCEIRO/ PUBLICA\\'c7\\'c3O\\cell }");
  ShowHTML("{\\fs16\\insrsid12326642\\charrsid14172830 \\cell }{\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow69\\irowband69");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth4680 ");
  ShowHTML("\\cellx3240\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7040 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow70\\irowband70\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354");
  ShowHTML("\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid14172830 O PAGAMENTO DO VALOR ACIMA FOI EFETIVADO MEDIANTE ORDEM BANC\\'c1RIA N\\'ba");
  ShowHTML(" _______________ ,       DE ____/____/________\\cell }{\\f1\\fs12\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid14172830 \\trowd \\irow70\\irowband70");
  ShowHTML("\\ts11\\trrh90\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow71\\irowband71");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid14172830 \\~\\cell \\cell \\~\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow71\\irowband71");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid14172830 \\~\\cell \\cell \\~\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow72\\irowband72");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow73\\irowband73");
  ShowHTML("\\ts11\\trrh30\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid14172830 O PRESENTE DOCUMENTO EST\\'c1 DE ACORDO COM AS NORMAS REGULAMENTARES E SER\\'c1 PUBLICADO, NOS   TERMOS   DA   LEGISLA\\'c7\\'c3");
  ShowHTML("O    EM\\cell }{\\f1\\fs12\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid14172830 \\trowd \\irow73\\irowband73");
  ShowHTML("\\ts11\\trrh30\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth11720 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow74\\irowband74");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6899 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2621 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard"); 
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs12\\insrsid12326642\\charrsid14172830 VIGOR, NO BOLETIM DE SERVI\\'c7O N\\'ba _________________  DE ____/____/_________\\cell \\cell \\~\\cell }{");
  ShowHTML("\\f1\\fs12\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs12\\insrsid12326642\\charrsid14172830 \\trowd \\irow74\\irowband74");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth6899 \\cellx5459\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2621 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow75\\irowband75");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid14172830 \\~\\cell \\cell \\~\\cell }{\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow75\\irowband75");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7338 \\cellx8080\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone ");
  ShowHTML("\\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2200 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow76\\irowband76");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3780 \\cellx2340\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7940 \\cellx10280\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone ");
  ShowHTML("\\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs14\\insrsid12326642\\charrsid14172830 ");
  ShowHTML("DATA: ____/____/________                            }{\\v\\fs14\\insrsid12326642\\charrsid14172830                          }{\\fs14\\insrsid12326642\\charrsid14172830 \\cell }\\pard ");
  ShowHTML("\\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs16\\insrsid12326642\\charrsid14172830 ________________________________________________________\\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\f1\\fs16\\insrsid12326642\\charrsid14172830 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {");
  ShowHTML("\\fs16\\insrsid12326642\\charrsid14172830 \\trowd \\irow76\\irowband76\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb");
  ShowHTML("\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth3780 \\cellx2340\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7940 \\cellx10280\\clvertalb\\clbrdrt");
  ShowHTML("\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrnone \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\trowd \\irow77\\irowband77\\lastrow ");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth745 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth853 \\cellx2340\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7940 \\cellx10280\\clvertalb");
  ShowHTML("\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {");
  ShowHTML("\\fs18\\insrsid12326642\\charrsid14172830 \\cell \\~\\cell \\~\\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\fs14\\insrsid12326642\\charrsid15559966 ASSINATURA/CARIMBO DO RESPONS\\'c1");
  ShowHTML("VEL PELO SETOR FINANCEIRO\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\pararsid12326642 {\\insrsid12326642 \\~}{\\f1\\fs18\\insrsid12326642\\charrsid14172830 \\cell }\\pard ");
  ShowHTML("\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\fs18\\insrsid12326642\\charrsid15559966 \\trowd \\irow77\\irowband77\\lastrow ");
  ShowHTML("\\ts11\\trrh100\\trleft-1440\\trkeep\\trftsWidth3\\trwWidth11794\\trftsWidthB3\\trftsWidthA3\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3 \\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone ");
  ShowHTML("\\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth2182 \\cellx742\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth745 \\cellx1487\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb");
  ShowHTML("\\brdrs\\brdrw10 \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth853 \\cellx2340\\clvertalb\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10\\brdrcf1 \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth7940 \\cellx10280\\clvertalb");
  ShowHTML("\\clbrdrt\\brdrnone \\clbrdrl\\brdrnone \\clbrdrb\\brdrtbl \\clbrdrr\\brdrnone \\cltxlrtb\\clNoWrap\\clftsWidth3\\clwWidth74 \\cellx10354\\row }\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0\\pararsid12326642 {");
  ShowHTML("\\insrsid8462233\\charrsid3545814 ");
  ShowHTML("\\par }}");
} 

// =========================================================================
// Rotina para informação dos dados da viagem
// -------------------------------------------------------------------------
function InformarPassagens() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_menu   = $_REQUEST['w_menu'];

  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PDGERAL');
  $w_valor_passagem     = formatNumber(f($RS,'valor_passagem'));
  $w_pta                = f($RS,'pta');
  $w_emissao_bilhete    = FormataDataEdicao(f($RS,'emissao_bilhete'));
  Cabecalho();
  ShowHTML('<HEAD>');
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataValor();
  ValidateOpen('Validacao');
  ShowHTML('  var i,k;');
  ShowHTML('  for (k=0; k < theForm["w_sq_cia_transporte[]"].length; k++) {');
  ShowHTML('    var w_campo = \'theForm["w_sq_cia_transporte[]"][\'+k+\']\';');
  ShowHTML('    if(eval(w_campo + \'.value\')==\'\'){');
  ShowHTML('      alert(\'Informe a companhia de transporte para cada trecho!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  for (k=0; k < theForm["w_codigo_voo[]"].length; k++) {');
  ShowHTML('    if(theForm["w_codigo_voo[]"][k].value==\'\'){');
  ShowHTML('      alert(\'Informe os códigos de vôos para cada trecho!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('    var w_campo = \'theForm["w_codigo_voo[]"][\'+k+\']\';');
  ShowHTML('    if (eval(w_campo + \'.value.length < 3 && \' + w_campo + \'.value != ""\')){');
  ShowHTML('      alert(\'Favor digitar pelo menos 3 posições no campo Código do vôo.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length > 30 && \' + w_campo + \'.value != ""\')){');
  ShowHTML('      alert(\'Favor digitar no máximo 30 posições no campo Código do vôo.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('  }');
  Validate('w_pta','Número do PTA/Ticket','','1','1','100','1','1');
  Validate('w_valor_passagem','Valor das passagens','VALOR','1',4,18,'','0123456789.,');
  Validate('w_emissao_bilhete','Data da emissão','DATA','1','10','10','','0123456789/');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'this.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('            <tr><td>Número:<b><br>'.f($RS,'codigo_interno').' ('.$w_chave.')</td>');
  $RS1 = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null);
  foreach($RS1 as $row) { $RS1 = $row; break; }
  ShowHTML('                <td colspan="2">Proposto:<b><br>'.f($RS1,'nm_pessoa').'</td></tr>');
  ShowHTML('            <tr><td>Tipo:<b><br>'.f($RS,'nm_tipo_missao').'</td>');
  ShowHTML('                <td>Primeira saída:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('      <table width="99%" border="0">');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Bilhete de passagem</td>');
  $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave,null,$SG);
  $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RS)>0) {
    $i = 1;
    foreach($RS as $row) {
      $w_vetor_trechos[$i][1] = f($row,'sq_deslocamento');
      $w_vetor_trechos[$i][2] = f($row,'cidade_dest');
      $w_vetor_trechos[$i][10] = f($row,'nm_origem');
      $w_vetor_trechos[$i][3] = f($row,'nm_destino');
      $w_vetor_trechos[$i][4] = substr(FormataDataEdicao(f($row,'phpdt_saida'),3),0,-3);
      $w_vetor_trechos[$i][5] = substr(FormataDataEdicao(f($row,'phpdt_chegada'),3),0,-3);
      $w_vetor_trechos[$i][6] = f($row,'sq_cia_transporte');
      $w_vetor_trechos[$i][7] = f($row,'codigo_voo');
      $w_vetor_trechos[$i][8] = f($row,'saida');
      $w_vetor_trechos[$i][9] = f($row,'chegada');
      $i += 1;
    } 
    ShowHTML('     <tr><td align="center" colspan="2">');
    ShowHTML('       <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('         <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('         <td><b>Origem</td>');
    ShowHTML('         <td><b>Destino</td>');
    ShowHTML('         <td><b>Saida</td>');
    ShowHTML('         <td><b>Chegada</td>');
    ShowHTML('         <td><b>Cia. transporte</td>');
    ShowHTML('         <td><b>Código vôo</td>');
    ShowHTML('         </tr>');
    $w_cor=$conTrBgColor;
    $j = $i;
    $i = 1;
    while($i!=$j) {
      ShowHTML('<INPUT type="hidden" name="w_sq_deslocamento[]" value="'.$w_vetor_trechos[$i][1].'">');
      ShowHTML('<INPUT type="hidden" name="w_sq_cidade[]" value="'.$w_vetor_trechos[$i][2].'">');
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('     <tr valign="middle" bgcolor="'.$w_cor.'">');
      ShowHTML('       <td>'.$w_vetor_trechos[$i][10].'</td>');
      ShowHTML('       <td>'.$w_vetor_trechos[$i][3].'</td>');
      ShowHTML('       <td align="center">'.$w_vetor_trechos[$i][4].'</td>');
      ShowHTML('       <td align="center">'.$w_vetor_trechos[$i][5].'</td>');
      SelecaoCiaTrans('','','Selecione a companhia de transporte para este destino.',$w_cliente,$w_vetor_trechos[$i][6],null,'w_sq_cia_transporte[]','S',null);
      ShowHTML('       <td align="left"><input type="text" name="w_codigo_voo[]" class="sti" SIZE="10" MAXLENGTH="30" VALUE="'.$w_vetor_trechos[$i][7].'"  title="Informe o código do vôo para este destino."></td>');
      ShowHTML('     </tr>');
      $i += 1;
    } 
    ShowHTML('        </tr>');
    ShowHTML('        </table></td></tr>');
  } 
  ShowHTML('        <tr><td colspan="2"><b>Nº do PTA/Ticket: </b><input type="text" name="w_pta" class="sti" SIZE="100" MAXLENGTH="100" VALUE="'.$w_pta.'" title="Informe o número do bilhete(PTA/eTicket)."></td>');
  ShowHTML('        <tr><td><b>Data da emissão: </b><input type="text" name="w_emissao_bilhete" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_emissao_bilhete.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
  ShowHTML('            <td><b>Valor das passagens R$: </b><input type="text" name="w_valor_passagem" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_valor_passagem.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total das passagens."></td>');
  ShowHTML('        <tr><td align="center" colspan="2">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de emissão do relatório para prestação de contas.
// -------------------------------------------------------------------------
function PrestacaoContas() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  header('Content-Disposition'.': '.'attachment; filename=Relatorio'.$w_chave.'.doc');
  header('Content-type: '.'application/msword');
  ShowHTML(RelatorioViagem($w_chave));
} 

// =========================================================================
// Devolve string com o relatório de viagem no formato RTF
// -------------------------------------------------------------------------
function RelatorioViagem($w_chave) {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  //Recupera os dados da solicitacao de passagens e diárias
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');

  // inicializa o valor da viagem
  $w_valor    = (Nvl(f($RS,'valor_adicional'),0)-Nvl(f($RS,'desconto_alimentacao'),0)-Nvl(f($RS,'desconto_transporte'),0));
  $w_diaria   = 0;
  $w_percurso = '';
  
  //Recupera a data da primeira saída
  $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$w_chave,null,'DADFIN');
  $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RS1)>0) {
    $i        = 0;
    foreach($RS1 as $row) {
      if ($i==0) {
        $w_percurso = f($row,'nm_origem');
        $i = 1;
      }
      $w_diaria     += f($row,'quantidade');
      $w_valor      += (Nvl(f($row,'quantidade'),0)*Nvl(f($row,'valor'),0));
      $w_percurso   .= '/'.f($row,'nm_destino');
    } 
  } 

  //Recupera os dados do proposto
  $RS1 = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null);
  foreach($RS1 as $row) { $RS1 = $row; break; }
  $l_html='';
  $l_html .= "{\\rtf1\\ansi\\ansicpg1252\\uc1\\deff0\\stshfdbch0\\stshfloch0\\stshfhich0\\stshfbi0\\deflang1033\\deflangfe1033{\\fonttbl{\\f0\\froman\\fcharset0\\fprq2{\\*\\panose 02020603050405020304}Times New Roman;}{\\f1\\fswiss\\fcharset0\\fprq2{\\*\\panose 020b0604020202020204}Arial;}";
  $l_html .= "{\\f36\\froman\\fcharset238\\fprq2 Times New Roman CE;}{\\f37\\froman\\fcharset204\\fprq2 Times New Roman Cyr;}{\\f39\\froman\\fcharset161\\fprq2 Times New Roman Greek;}{\\f40\\froman\\fcharset162\\fprq2 Times New Roman Tur;}";
  $l_html .= "{\\f41\\froman\\fcharset177\\fprq2 Times New Roman (Hebrew);}{\\f42\\froman\\fcharset178\\fprq2 Times New Roman (Arabic);}{\\f43\\froman\\fcharset186\\fprq2 Times New Roman Baltic;}{\\f44\\froman\\fcharset163\\fprq2 Times New Roman (Vietnamese);}";
  $l_html .= "{\\f46\\fswiss\\fcharset238\\fprq2 Arial CE;}{\\f47\\fswiss\\fcharset204\\fprq2 Arial Cyr;}{\\f49\\fswiss\\fcharset161\\fprq2 Arial Greek;}{\\f50\\fswiss\\fcharset162\\fprq2 Arial Tur;}{\\f51\\fswiss\\fcharset177\\fprq2 Arial (Hebrew);}";
  $l_html .= "{\\f52\\fswiss\\fcharset178\\fprq2 Arial (Arabic);}{\\f53\\fswiss\\fcharset186\\fprq2 Arial Baltic;}{\\f54\\fswiss\\fcharset163\\fprq2 Arial (Vietnamese);}}{\\colortbl;\\red0\\green0\\blue0;\\red0\\green0\\blue255;\\red0\\green255\\blue255;\\red0\\green255\\blue0;";
  $l_html .= "\\red255\\green0\\blue255;\\red255\\green0\\blue0;\\red255\\green255\\blue0;\\red255\\green255\\blue255;\\red0\\green0\\blue128;\\red0\\green128\\blue128;\\red0\\green128\\blue0;\\red128\\green0\\blue128;\\red128\\green0\\blue0;\\red128\\green128\\blue0;\\red128\\green128\\blue128;";
  $l_html .= "\\red192\\green192\\blue192;}{\\stylesheet{\\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 \\snext0 Normal;}{";
  $l_html .= "\\s1\\qc \\li0\\ri0\\keepn\\widctlpar\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0\\itap0 \\b\\i\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 \\sbasedon0 \\snext0 heading 1;}{\\*\\cs10 \\additive \\ssemihidden Default Paragraph Font;}{\\*";
  $l_html .= "\\ts11\\tsrowd\\trftsWidthB3\\trpaddl108\\trpaddr108\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3\\tscellwidthfts0\\tsvertalt\\tsbrdrt\\tsbrdrl\\tsbrdrb\\tsbrdrr\\tsbrdrdgl\\tsbrdrdgr\\tsbrdrh\\tsbrdrv ";
  $l_html .= "\\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 \\fs20\\lang1024\\langfe1024\\cgrid\\langnp1024\\langfenp1024 \\snext11 \\ssemihidden Normal Table;}}{\\*\\latentstyles\\lsdstimax156\\lsdlockeddef0}{\\*\\rsidtbl \\rsid1469200\\rsid6709522";
  $l_html .= "\\rsid13377954\\rsid13532367}{\\*\\generator Microsoft Word 11.0.5604;}{\\info{\\title RELAT\\'d3RIO DE VIAGEM}{\\author TERESA SOARES}{\\operator Suporte T\\'e9cnico}{\\creatim\\yr2006\\mo5\\dy12\\hr10\\min30}{\\revtim\\yr2006\\mo5\\dy12\\hr10\\min30}";
  $l_html .= "{\\printim\\yr2006\\mo4\\dy25\\hr18\\min28}{\\version2}{\\edmins0}{\\nofpages1}{\\nofwords153}{\\nofchars873}{\\*\\company Minist\\'e9rio da Jusit\\'e7a}{\\nofcharsws1024}{\\vern24689}}\\margl1418\\margr1418\\margt899\\margb851 ";
  $l_html .= "\\deftab708\\widowctrl\\ftnbj\\aenddoc\\hyphhotz425\\noxlattoyen\\expshrtn\\noultrlspc\\dntblnsbdb\\nospaceforul\\formshade\\horzdoc\\dgmargin\\dghspace180\\dgvspace180\\dghorigin1418\\dgvorigin899\\dghshow1\\dgvshow1";
  $l_html .= "\\jexpand\\viewkind1\\viewscale100\\pgbrdrhead\\pgbrdrfoot\\nolnhtadjtbl\\nojkernpunct\\rsidroot6709522 \\fet0\\sectd \\linex0\\colsx708\\endnhere\\sectlinegrid360\\sectdefaultcl\\sftnbj {\\*\\pnseclvl1\\pnucrm\\pnstart1\\pnindent720\\pnhang {\\pntxta .}}{\\*\\pnseclvl2";
  $l_html .= "\\pnucltr\\pnstart1\\pnindent720\\pnhang {\\pntxta .}}{\\*\\pnseclvl3\\pndec\\pnstart1\\pnindent720\\pnhang {\\pntxta .}}{\\*\\pnseclvl4\\pnlcltr\\pnstart1\\pnindent720\\pnhang {\\pntxta )}}{\\*\\pnseclvl5\\pndec\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}{\\*\\pnseclvl6";
  $l_html .= "\\pnlcltr\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}{\\*\\pnseclvl7\\pnlcrm\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}{\\*\\pnseclvl8\\pnlcltr\\pnstart1\\pnindent720\\pnhang {\\pntxtb (}{\\pntxta )}}{\\*\\pnseclvl9\\pnlcrm\\pnstart1\\pnindent720\\pnhang ";
  $l_html .= "{\\pntxtb (}{\\pntxta )}}\\pard\\plain \\qc \\li0\\ri0\\widctlpar\\tx4680\\tx5040\\tx5580\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\fs20\\lang1024\\langfe1024\\noproof\\insrsid1469200 ";
  $l_html .= "{\\shp{\\*\\shpinst\\shpleft4320\\shptop-720\\shpright4860\\shpbottom-180\\shpfhdr0\\shpbxcolumn\\shpbxignore\\shpbypara\\shpbyignore\\shpwr2\\shpwrk2\\shpfblwtxt1\\shpz0\\shplid1026{\\sp{\\sn shapeType}{\\sv 75}}{\\sp{\\sn fFlipH}{\\sv 0}}{\\sp{\\sn fFlipV}{\\sv 0}}";
  $l_html .= "{\\sp{\\sn pib}{\\sv {\\pict\\picscalex40\\picscaley37\\piccropl0\\piccropr0\\piccropt0\\piccropb0\\picw2381\\pich2593\\picwgoal1350\\pichgoal1470\\pngblip\\bliptag93535635{\\*\\blipuid 05933d93d5af8c665d329e24f38e3896}";
  $l_html .= "89504e470d0a1a0a0000000d494844520000005a000000620803000000e75f34f0000000c0504c54453537342826220e663970642f39286446386f374c411074";
  $l_html .= "49306c504944574b514f476754736a4e534a696e6b73645d614d4634a27232d379258d714a9e754f05874a288645968d38ab9334a89618d3ac1ad3c914f2d307";
  $l_html .= "f7e807ecdc319c935bc9b65eebdb5fb5ad2a6a806e8f8d90b0afb0a4a29af8ec93f8efb3ddd19acecdcef9f3d1fdfae7fffffffdfcf9e7e7e7bfbbb57b778900";
  $l_html .= "0000000000000000000000000000000000000000000000000000000000000000000000000000000000c940afe600000001624b47440088051d480000000c636d";
  $l_html .= "50504a436d703037313200000003480073bc000010d0494441546843b5990b77a2dab28589f8027968d08022080888803cfeff9f3b5f2d349df4eef4d9778c7b";
  $l_html .= "e8b4b115e6aa35eb356bb5f6f89f5ddabf416ed2eecb6dedadfd370f3dfe0d749746d5e305d73e6ed1edff0dba6de2b64b27ec267d5471fbafccfec9eae6f6c9";
  $l_html .= "0174c4d523ae6e6dd5de6ee9839fe73a2cf517767e82965d3fc19bf891de1e6915dfa23416e85b1b3fcdee60a7fa899d9fa02bd02677555ddcdeefa0c7d1394a";
  $l_html .= "6fb288b0a2d8e11f69f37f857ec06f0542094cda54c9e37e4ba3f7284dab47529677ecbe355dda34f18fc83f46488b995d527f5cbb04829be451dde3d3fbe996";
  $l_html .= "36f5a52cca2169582111a67e74e90f84e0ba26692f87d5aa4feee5a54dd842f4fe8ed9ed50ef7779998fc36e5f704ff72de6bf92f30374c7c62f1f20cfd6abd5";
  $l_html .= "0ad39be67c02fa04ef176b575e2ebb71bfeff1017efde1fac9ea2629d6abc36c76007c1db4c9bd8d829dbe0ba276b81445b12fbd6114a3d9cf4f8cfc191a1f26";
  $l_html .= "e672b95ecd56abf53abf57f7fbedb4d3f5dd2ebc0d83371690f4482470ee77eefdb3d9dfa0c90952a0bb55c454b15c2e37a0afd7cba6ba5709466bfa7117c4fe";
  $l_html .= "f5dd1b86cba5bef497b6b9b431915e11876d537dad348fef11d2902737820e66634bd3cca5f6b634979bcb75dd9c4e7b5dd3c3dd3e4c6aa748e0e16e5dadf252";
  $l_html .= "6e367523418839d177f3bf5b2d617b4bfbbbe779ba86d9da1baf5062c1f47eabe9e7e33e88ee494df83569feb13aacaeb3d5c7a5ac40be11addf98f98deb2669";
  $l_html .= "1ec966d03d7dab2d35ed4da0d7ebebfd16eef5a5a647a1b70fd3fb507bfbfa7e5de3e8c312fcf5eaa3be27bf53fe0dbabb11cdf961a56f7561039bc5f44dde55";
  $l_html .= "62b4409f7798ddf4f96e77a92dfcb03ae06b09a4c3051f7d77e777e834edaf87c3eab27dd34a2264b97c7b5b3a97e651857b0b7af4283a79fb73f3188aa2beeb";
  $l_html .= "6b3624b1b912e88f6bf25b847f87ee928f03d039a1b1c94bd3dc6eb74357ddbb38f0b66c03e8f3ce0bd247d277ed65d86c049b3812e8c36c95ff2d42d20df790";
  $l_html .= "2584f36a3b2449d326d5a34baad0b3081581c66cef5c251d01dd9b58c05ed89b823e1c861fddd8753e9b0359e0d77ade5eaa8742897c8c7e428bd9e7fba3c5df";
  $l_html .= "571899695c6ff02d4f5d7f84bea59b0d250370c9c16ddd5797f681d962b409f452ac16b3a306a31f8d871f67b3d91b0e59cf08d2c3a1fe5379229b1e8d24b7b6";
  $l_html .= "1693c9415e0400b353df12a32742a2b3ee653752a64b24fc30e50034e1b4e4c175df54ed8b71e5467a534b6fea0a933b36c0ae37e2fdabd0fc486ea1a563f4cb";
  $l_html .= "eae86879712ad5a3c391eb355408279200abf51594f4d93915741bc555d7a4692e8b2b78f1fd9aa8a3b65511466f80765d2f3b8567ccb6b2b8e19b47636d3626";
  $l_html .= "7690b540cb1fb36e6f713cd93d055f5591e25d63c9e2dc4634acd79b6bc5d355da8496653af950d7fcf4fd9064981d891b49ddd154f64237e9c55bf342b37c36";
  $l_html .= "e2099a9adba449953fa1a94c9bcda69fc2230eac7c2846d7b40d2e16a9871cb3e95ca0b7de1650738216d365a74fb29f2913d3989b44579124b799662945a14a";
  $l_html .= "db2a1c8bdc05b42c1cd7a91dde39c57089da563d52f517089487a60773787d86c9131a86d251112685e36dab27b72a8dd3388ab2b2748dc119f36128dda22ec6";
  $l_html .= "7c2c002fc328e2fb5bd3b64d42617841ebbfeadf0bbae9064d27af75ff28ae22c6c22cf03dcf2a47ccacebdccef3d1c8f3dc28fb814de485e5f981b83566fd73";
  $l_html .= "783aee74e126ef5e7cbc5a4117c749f6843c9d8260bfa7647b9665958e638f7959e48663bbb6c31f6af538daee585896dce3edf741700a5fcf66349d6f84b429";
  $l_html .= "2b9f4fd2b377bbdd5e80e983c7e34ec8e821c0759dbc18ea01da6dc731eabe341c6a23bbd42dc0f73c25fdfe7466c3dfb96e2af48b5cdc2290420a57983b0656";
  $l_html .= "16c01535c137107c7dc1368aa2b48d319744922c95153045219ce2ead913a69449210c6c5d3fb2eeafeb321ad8383a6e89fb6c7bb158cc6d023cb7f96cb48ddc";
  $l_html .= "79624b012400b6809f1086cf9620d00d7eae6e62b7b5fc0a1ee4609686ed1685036c3df2b2581844b96bdb7591db547465b65c5bff74149bc1927aa4b2b16b6f";
  $l_html .= "189dde1427d67ab3f944bfbac00ca3cdee81244c8a09dc290a8c1f466354660bee915804393ca7770526354aa334356dd756f78f8f287cdf511528200afd981b";
  $l_html .= "763e8e46a990c702d2e5cd625cb82c467c1ba6b0cdcde04651f0fe7e0e3f3eee0a2d45c16a4d1c87a724b9e787d947f8c2067deb7b2e661165a50bc7f3b94350";
  $l_html .= "0bb2dd3bd8edb8069132ba4f5c1adb4e906907f73409e31849a591f25d5395fe8cb605f66eb29b8a668ee628de2bc7b920cf711dd044475d974e8ed9c3e0b857";
  $l_html .= "b157126cb70b409ed1c6aeb7aae94408e2c6a6aada0275477bd363d4c00b9be42b8a71cc6141a0e545e828cb32b7ddd22102b9437111615170ce3e68bdb3c347";
  $l_html .= "df023815d50ee22dd1bb605fbf603b863be66e2e462bec097a012f0e81974310b19d4dc87b854c57c0c0003f4af19b6a48777d42aff4f065b70b9bb66d0bd38b";
  $l_html .= "e2092d3b18896adb70c0b5f9157c41065731f2b5a8de62e9b62c287d393b078a13a00bf25ad09c1ecec5e87e1825c259d12ddd7ca0b0f8d8eca1a832c115606c";
  $l_html .= "7cce37caeaeeae1a395fc8abc236812676813616d2610a3819e5b78a3f81364851c7f5454f05d1e983f141594dcfbe4ff56982ae455cc9174a636551803aa0ad";
  $l_html .= "8cb982a69e1226184d099c9246a0a5cadaae7ff43c6c6636a1eb8a025c2f37c517e82a1641a114c85a54c8846d1ae4b39d9b10429c6035bf25fe264272feba86";
  $l_html .= "4b610de23b72f830c97c11a0c134f04de5a9274764bc902f0fab8fb241e4f120563b396e74140b404ebf80c68d6cc1311ccbcbe2b640321c34a4004d984e554c";
  $l_html .= "a54fa0093e915df47c1931c02f29048105745fbba3ab42ee79293e30da60237c0934b5ff7e118b3591b50a3aa0274fd04478830291cf5109b4dcab9481d8a738";
  $l_html .= "911dae637c41eec570904dca1e5f1a6e96765d5bce26e9a75aafb6ac2b35d5680805294ed26e4580885c28988be8b896497731ecd1fdc4768a1ed92046b31903";
  $l_html .= "c64c8fde7bbfaf0f339ed6de94fed390399daa7c2da5244e124f5abd92296fdb204bd25b5361b64b6cb80e8e549743eda3e689d1a35d53130df7786bee09ae17";
  $l_html .= "4ca4b082f61210c9472a5f2a0ab0977eaf64ca9b9f20501f8f5b46e32eebc1c1f497fb240a0599d64393374c3783d55aaa8f4256bf4c6a13624c2a1f74482f48";
  $l_html .= "143657de948702311505849f140cc3319713b89da34740867f6a1f4c5b41746baf40afde3425df59e0834e80b3a60869a50c36f7095bdbde19672e718a8a344d";
  $l_html .= "1b94c2755c4d23736ca4826d68262a8a266fdba6e9eda3382327a6ba04b4e8f7a69aa6bca7e6435e269eb0b1f5de0a2961cc2f7b6f678a0aa387c3b7a82a75b9";
  $l_html .= "747404046cb99abe3fb70ca5b85121ab5ffe6b107baa2786e0c2d3df2eebd9f6ed42c6afcadb79bf3beb60b373a204cb49090d2e5c0a5f299a4a04f73ebc951b";
  $l_html .= "425690856b1e7d26cce720dd55176bbb7dab597fada0d7710874a86f6962a32b3ea397a1a45c9baa648f342fe9b7dbfd29b64c6d49f590ca26a705b3cfa1e3a5";
  $l_html .= "f9e244461d4b4d48cc274067fb2322076c9aafb4ef9ab81b8642b402262b64a083d8624691b194dac39432dbfc26cc287e8fab2c3dd5c5d9726d31df2ab9c344";
  $l_html .= "0d29e34845cd0d942ac503643850ba897a7a54b336f64c03d0afe1f16975774b319ba2c88ed89566fa1c52281975a29869e23355ab72b2d0b0d17abe825e7a3b";
  $l_html .= "8116a1ff3ae3483e8f2e9e0d0ce9de314f49bd85edf572798c7613b42ec33ab18788820697d10005a68791afa0f5ddf9b4650bd41ff0999137728af7a55ecbc0";
  $l_html .= "51a50943e34ceec706ed14eea0fa7cf64d648352fdaa0cc8b5dd491b973505fa7416e86900829a7f0c1cd39894cbea529fcc8d763e29e8a352755cda56553544";
  $l_html .= "e95184bd34dbadf81168b5af69a258ba77c9c5af56a767146095c8d18a3cbf3475a0f16228362bf145030ccfd9f9245a7ad21ed151e4296a4c173a54fdd1b412";
  $l_html .= "d5fb3a3215aebb9aa358045a93ab8acbf2a61ebdef90d8baa8280e1850e7a72873dc3c8d00673f0a5b3077ef2c314d48fca53ea0539b6e7c257aef90f36dd555";
  $l_html .= "899a91a07319000dd14aa06dbdddee14dd6a52322f875b741275afc61d4e5f507aa7696ae3d29b4ee9a6c2e1a5561162978a1da4df34586adae9fc7e3c9f94f6";
  $l_html .= "53c0a8c327f4a38f214b3e836e4dd311912fe4fc390fd48bfcf1e81dad077358bcce930357b9727b166801d677ef709e5dfa17342bf8443ba309e0476dcbd753";
  $l_html .= "a9f7b2d7dcefd8fda31b0bad28f8c47e4a879643eb7ae83778f1fd04d14f608b88ce0745482e939d194467c61674b52ed03ac84ecdc1d51419c3a278f4a3dd69";
  $l_html .= "8fdc2eba91759e576dcfc72d89f0eeaf751e178b658066fe52d0a65b933de5257b826f39c2dd6da9b9dad3ba079db9cee7f35aea7539770a04f97349254813bc";
  $l_html .= "88c5efe728f30f6ba62d67904e2bd00c4e459e738c152a70ee8ad29a0cd5e6102c177a021d44fd13378e7384ee28dbe9584ea02d395006f8e493a1074ee37229";
  $l_html .= "2213b4e1f47242b55af9a14c6ddc96286434a14014b4e7f9424ea1047a988b2e920d89d8f58df9dcc2a073140628d6e5156f0e74449a0073296aade47c652da7";
  $l_html .= "926b9fc5b9d19f908584470d92bdb03fe31ad94f53e58b7e3ecffcc0980767e6bfd37d5852181cdaa5c948adef3cdddbe9bc33497a87f3a3cdea03ca4fa74c9b";
  $l_html .= "2f7c6b3eb7bb4737693715cc2aaecba95f437769d9999599c728d393412aa0a601ad2abfee5927cbe3a8682bb1ef6c36570e84364d1646c7b96505966fd584dc";
  $l_html .= "84f4abed76a2c7334b92124233cbb3a4b7d45459509c378910c390d9303ceaea3dd0a6092deba234b4adcdb3966fc0afd2b1af9098ea3501e3f9b6e548a8fb9e";
  $l_html .= "9d65d65c33dc1a52679fd0ba2595f4135a29e96b610819bee5056ef969f333d89e5da6777c3bc46e3ecd6d9f930e29fc8e60af7e111266a110425fc06a39fcba";
  $l_html .= "96c65cec08011f48ed696278f5dd27347667568088e1f37201773ede24ccc6af6ed471e5a71b918d65495e7a59280fd68fdaf5958cfdbda3c3c925736df43a31";
  $l_html .= "582fbccc0b33df5d3843ed8c720c87f9a688b4abb441d735d199b461cbc6668e7aecb17f1476e88784ddaf03ca97d552aa1659105a721b6ffd2c0b16beedd979";
  $l_html .= "dde7e8a3ab3a68611ecd37eb4dd175542bdbcd32dbcf3cd7868c91d00a33ef0bf2d7ff2be89d8ccbb75d1626f3bdc00e321c64db6339f4602968c2a0efd5e948";
  $l_html .= "9005449c6d2d900783fc8b65ae5f0f557f59cd3323b666b6e5e562b8e1f996efba219be61dca898b570e5e4cc37233dbf3323f7317c41c261b5918fab644c1af";
  $l_html .= "eb2bf4a32bbdcc924871d93242c60efc2cb42d92c8cbf8f102dff4bdd01773cf1090c9a90e7c09e1301dfced689c7262db3ea653290629490b0643dbb7049c93";
  $l_html .= "378b45cf56e0e2630b4fb8c6dcb0a92e6c2be470d497c2ffa3d5923c8e6545199b63e82c55e6a120d90bfbf55dfc24219cd9812b5a47248fc1a66c974fbf93f1";
  $l_html .= "5943beaed595ae2f46318e92d092d4f2625b4c009409cfb45c25a3a8bc7c2ed3989f59b8e577935fe5e9fb467a075a893dc98039d02260886301640d2777be9c";
  $l_html .= "60c0063168e3f77f5edfdcf8fcba1b303108c300d33d0668110379c7e85f4af0d5d3c10b5167593284d8c6e5db39fee71a7f82a6db142e8e2312e086e415acfc";
  $l_html .= "15d7727a41790f323b2430996ffe0cfcdb7faf7dd95437e04fc2c2cf88181f30391a572923670c730a6508b44bb2fe818ae9a33f5badbeaa73d7931c2237b09b";
  $l_html .= "634f4e54c7e2094dc071ba50ff166f7f0dbedf3cca044a6e88d572546b7232d74d563384fcc9757f8beb7f6eaf1fc05fcc876228b68cd21d136f4d49f9efd75f08f916ed7d8f6b4da7fc6fa6fe5443fe6e49cd61d4cf4efb7771fdc30a5deefc21e77e36e73fbe24c8e7499be1b00000000049454e44ae426082}";
  $l_html .= "}}{\\sp{\\sn pibName}{\\sv http://www.tjdf.gov.br/armas1.gif}}{\\sp{\\sn pibFlags}{\\sv 10}}{\\sp{\\sn pictureGray}{\\sv 1}}{\\sp{\\sn pictureBiLevel}{\\sv 0}}{\\sp{\\sn fLine}{\\sv 0}}";
  $l_html .= "{\\sp{\\sn wzDescription}{\\sv }}{\\sp{\\sn fLayoutInCell}{\\sv 0}}{\\sp{\\sn fLayoutInCell}{\\sv 0}}}{\\shprslt\\par\\pard\\ql \\li0\\ri0\\widctlpar\\pvpara\\posx4319\\posnegy-721\\dxfrtext180\\dfrmtxtx180\\dfrmtxty0\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 ";
  $l_html .= "{\\pict\\picscalex40\\picscaley37\\piccropl0\\piccropr0\\piccropt0\\piccropb0\\picw2381\\pich2593\\picwgoal1350\\pichgoal1470\\wmetafile8\\bliptag93535635{\\*\\blipuid 05933d93d5af8c665d329e24f38e3896}";
  $l_html .= "010009000003e21300000000bd13000000000400000003010800050000000b0200000000050000000c0263005b00030000001e000400000007010400bd130000";
  $l_html .= "410b2000cc0062005a000000000062005a0000000000280000005a00000062000000010008000000000000000000000000000000000000000000000000000000";
  $l_html .= "0000ffffff00d1f3f9005fdbeb00f9fcfd00e7fafd0093ecf80007e8f700b3eff80031dcec009ad1dd0007d3f2009aa2a4005eb6c9002579d300cecdce004586";
  $l_html .= "28006e806a001aacd3005b939c00b0afb000b5bbbf004a87050049741000e7e7e7004f759e003272a200908d8f0089777b0039660e00506c30004e6a73003493";
  $l_html .= "ab002f6470004a718d004f514b00414c370014c9d30054674700343735001896a800388d960022262800736b6e0034464d002aadb500615d6400694a53005744";
  $l_html .= "49006f38460064283900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
  $l_html .= "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000101";
  $l_html .= "01010101010101010101010101010101010101010101010101010101010101010101010101010101041b2b140401010101010101010101010101010101010101";
  $l_html .= "01010101010101010101010101010101010101010101010100000101010101010101010101010101010101010101010101010101010101010101010101010101";
  $l_html .= "010101010f2e1b150f01010101010101010101010101010101010101010101010101010101010101010101010101010101010101000001010101010101010101";
  $l_html .= "0101010101010101010101010101010101010101010101010101010104181804142e232b0c180101010101010101010101010101010101010101010101010101";
  $l_html .= "01010101010101010101010101010101000001010101010101010101010101010101010101010101010101010101010101010118150c1c2f3132151415142c2b";
  $l_html .= "0c14041b1b0c0f180401010101010101010101010101010101010101010101010101010101010101010101010000010101010101010101010101010101010101";
  $l_html .= "0101010101010101010101180c1c2f1f2f2d293232320f15141b232e1c0c041c32323232312f1b15180101010101010101010101010101010101010101010101";
  $l_html .= "0101010101010101000001010101010101010101010101010101010101010101010101010f1b2e22292d2d2d22222d3132320f151515141b1c14011c32323232";
  $l_html .= "312f2d252f2e1b0f040101010101010101010101010101010101010101010101010101010000010101010101010101010101010101010101010101010104152b";
  $l_html .= "1f1f2d2920292d29292d29323232150f0f15141c0c15011c32323232312229252f25252e2f1b0f01010101010101010101010101010101010101010101010101";
  $l_html .= "000001010101010101010101010101010101010101010104142e1f2d292d202029251f313131323232321b0f0f15141c0c15012f323232323131202d2f25252f";
  $l_html .= "2f2f2f2b0f010101010101010101010101010101010101010101010100000101010101010101010101010101010101010104152e1f2020252520202e2f223132";
  $l_html .= "3227272a2a1b040f0f140c1c0c15010f3132323232312f1f311f1f312f2f2f2f30130f0101010101010101010101010101010101010101010000010101010101";
  $l_html .= "010101010101010101010101182b222d222d2d20292f3131323030272427272a2e1b1b2e30303030302f2b1b2b32323232323232313131312f2f2f2f2e2d2f1b";
  $l_html .= "1801010101010101010101010101010101010101000001010101010101010101010101010101040c2e29251f2d202e2f2f30302730303027273030302f1f2e1f";
  $l_html .= "201f291f22231f2e2f2e30303032323127303031312f2f292d1f3022291404010101010101010101010101010101010100000101010101010101010101010101";
  $l_html .= "01182b1f1f072d25292f2f2e0c0c30303030302f2e2f222d1f252529292e252d222529291f2d2f202e2e2f30303030302e302f1f2d2f2e2529292b1801010101";
  $l_html .= "01010101010101010101010100000101010101010101010101010101152f2f29292d2d23302e15041b3030312f2e292f291f1f2d1f2d29202d1f252d1f0b1f20";
  $l_html .= "2e2d22251f2d20312e3032302f151b2f2f2f2d25251f1f29140401010101010101010101010101010000010101010101010101010101040c222d1f2f252d1f2e";
  $l_html .= "1404041c30302f20292d252f1f2d1f2d222d2d222d22202923292225292d20201f0b1f2f2d2d3131322f0f181b2f2d2d292f2529201b04010101010101010101";
  $l_html .= "0101010100000101010101010101010101181c292d2d2d2f1f221b1801182b3022291f292e2e252f2f2d22291f2e2b2b2b2b2b2e2b2b1c2b2e23301f2e2d2f22";
  $l_html .= "222d292d2e31301501181c1f302d2525292f2b18010101010101010101010101000001010101010101010101182b2d2d2d2d292f2e0f010114312f22202f202d";
  $l_html .= "291f222f2f1c0c151804010f0f15141c0c15010118180f141c2f2f202d20201f292f1f311b0401152f202d202f292d110f010101010101010101010100000101";
  $l_html .= "01010101010101182b312d20251f2f1c1801182b31292d2d2031292f222f1b0f181b26261e1114150f15141c0c0c1b261e26140404010f1c2f2e2d2d2f292d22";
  $l_html .= "2e2f1501181c1f2f2d252d222f0f010101010101010101010000010101010101010118132d2e2f222e2e1401010f31222d22252d292e312b150f2b1c23232e2e";
  $l_html .= "261e1e231b1b1b2b11232626262e2e232e1b1b150f1b1f2f2f2d2d2922222f1c1801142f2529252f312f0f010101010101010101000001010101010101182f2d";
  $l_html .= "1f202f2f2f0f01010c31321f1f29292030302b181c23242b2a272c231f1f1f211a211f211a211f1f1f232c272a2b2a27230c0c302320222d251f202f2f0f010f";
  $l_html .= "2e292f3131312f0f01010101010101010000010101010101182b292f252d1f2b1801011b2f292229202f2e30302b0f261e1e172b2a2730303023232122252d25";
  $l_html .= "22212323272730272a2b27272426110c30322f2d2d1f2529291f1501182b2f313131312f0f0101010101010100000101010101182f3122292f1f2b1801041c29";
  $l_html .= "222f2d2d1f3227302e0f261e1e1d272b2a303030303030211f07070b21213030303030272a2b241e1e242611143027323129291f22202e0c01041c3131313131";
  $l_html .= "2f18010101010101000001010101042b3131312d1f2b0401011c2e2e2d1f221f2c30302f0f111e1e2727272b2a3030303030302c282507252821303030303027";
  $l_html .= "2a2b23231e1e1e1e140c30272c2f312d1f1f29301501011b31313131312f1801010101010000010101010c31313131312f1801010c1f2f2d1f2d2328072d210d";
  $l_html .= "151e1e242427272b2a27303030232321221f121f222123232c2727272a2b2626111e171e26022d250728321f1f201f291f0f01041c31313131312f0401010101";
  $l_html .= "0000010101183131323131310f01011832322d29203032200b12070b120a15242423242b2a272c231f1f1f211a2221221a211f1f1f232c272a2b171e1e261710";
  $l_html .= "2d070b12122d273231291f32322f0401042f31313131310c0101010100000101011532312f31311401010114323232293032321f0b0e0e0b0725201f26262411";
  $l_html .= "262626151b2f302e2b2e2e2b1b23302e0c261e1e1e111d17101e290b07120e0e0b2927272732323232320f01010f2f31312f312f0101010100000101011c321c";
  $l_html .= "32311c04010101041b3232323232311f070e0e0e0e0b071229261e1716261b041b30302e1c2e2e1b0c2330302b18261616161717292507120e0e0e0e0b0d3027";
  $l_html .= "27273232321c040101011431312b323115010101000001010c322b14312f180101010101011532323232310d070e1a211a0e0e0b072d29161e0c2f302b15302e";
  $l_html .= "1c2e2e1b0c23302f2b141b1b16161025070b0e0e0e211a0e07030f30272727321b0101010101011c31311b31310f01010000011b2f14041c2f18010101010101";
  $l_html .= "01010f2f321c2c1a0b120e291e210e0e0e0b0b2d13303030301b14151c2e2e1b0c232e0f30302f0c132d070b0e0e0e28252c0e0e071a2c2b2327271401010101";
  $l_html .= "010101182f310f1b32320f01000014140401012f14010101010101050601040f151826210b120e12212626211a0e120b0b2d1f3030301b151c2e2e1b0c1b152e";
  $l_html .= "30302320070b0e0e0e282507212c0e120b212a2a2a2b0f04060401010101010115311401181c2f0400001801010115310401010101010102202e23242427272c";
  $l_html .= "0b0b0e280b26262623211a0e120b0b2d1318010f1c2e2e1b0c150f1c2e290b0b120e1a1a12070728272c0e120b2c2a2a2a2a27222d04010101010101012b2f18";
  $l_html .= "01041c140000010101012f0c010101010102080c2a2727272a272727120b0e280b122626262623221a1a12070905010f1c2e2e1b0c150118090b121a1a1a1207";
  $l_html .= "0707252727210e0b25272a2a2a2a2a2a2a1b080501010101010f311b0101182b0000010101182b040101050804080d272a27272a2727272420070e1a0b0b2926";
  $l_html .= "262623232c221a1a1207060f1c2e2e1b0c150607121a1a1a280b0707070b2c24271a0e0b1227272a2a2a2a2a2a2a2102040805010101142b0101010f00000101";
  $l_html .= "01040f080605020909021b2a2a272a2a2724272321070e1a250b0b212626232323232c22191912072d2b2e1b03071219191a280b0707070707212424271a0e07";
  $l_html .= "2824272a2a2a2a272a2a2a13090305020602041c0101010100000101010101030b060501080d24272a272a2a27272423210b121a120b0b252326232323232324";
  $l_html .= "21221920250b2d0b121919191a2507070707070728242424270e0e0b212424272a2a2a27272a27270c0105030706010401010101000001010101010808030308";
  $l_html .= "041b23242427272a27242326270b121a280b0b0b282323232323232323272c272a2a2a27272c2c280b070707070707252c2424242c1a120b21232427272a2a27";
  $l_html .= "242727242e0a090306020101010101010000010101010105050108030d261e171d232424242626262712251a280b0b0b0b292326232324272a2a2a2a2a272727";
  $l_html .= "272727272c1a250707070b2123242424211a120b2c242324272a2a2423242724241302010204010101010101000001010101010409030204111e1e16241e1e16";
  $l_html .= "1d1e1e242428071a1a0b0b0b0b0b2123242727272730302f2e3030302e2f303027272c212507292323242424211a0b122c24232424272a232626242426260803";
  $l_html .= "060101010101010100000101010101010406030d1e1717162417171d1d171727232907201a0b0b0b0b0b12242727272e30302e1c1b1b2b1b1b1c2e30302f242c";
  $l_html .= "2c29242323242424221a07282724232626262424242624241e1e0d080401010101010101000001010101010101040111171716161d17161d16161d241e1f0720";
  $l_html .= "19120b0b0b0b212c2730301b1c1c1c150f1b30140f142b1b1c1c30302423242323242427192007282724241e1e171d1d1e1e1e24171e1b040101010101010101";
  $l_html .= "000001010101010105090d1e1716171f1d161616171d1d1d171e092d19280b0b25212c30302f1b15151c300f140c2f151514301b15141c30303023232424242c";
  $l_html .= "1920091f24271e1e17161d1617171e24171d2603010101010101010100000101010101010408131f2c2c2a0f1e161d1e1317161d171e0925191a0b25212c301c";
  $l_html .= "2b1c2e0f0f1b302b2f2e2b2b2f2e2f0c0f142f1c2b2b30232324242c1912091f23272417171616171d16171d17171d0f01010101010101010000010101010101";
  $l_html .= "0208041422212a131e1d2c2a1317161d170c060b191a0b212330301b0c1b2f1c2e2e2b2e23272727232e2b2e2b1c2f0c0c1c3030232424211925060c26242324";
  $l_html .= "171616161d16161d17161d14050101010101010100000101010101050609090d22272a2a2a2c222a14101616170a040919221a2330302b1418142b2e2b23272a";
  $l_html .= "272727272727272e2b2f1c1518142e30302e2721190b050c26261e161d1716161616161d17161629060101010101010100000101010506080204020a2c2a2a2a";
  $l_html .= "2a2a272c0a11161613090307291f23232f2e2b30142e2b2e272730312f2f2f2b2f2f3027232b2e2b14302b2f2f2323221907092d1e1e1d16171d17161616161d";
  $l_html .= "1d1616110208060401010101000001040508060309090d2c2a2a2a2a2a2a2a1b020c161401020307202323301b1b1b2b302b23243031313131312f1b2b1c1b2f";
  $l_html .= "30232e2b302b1b0c1b302e1f2007060511171616172a271d1616161d2a2a1d100903060205040101000002090308050401182c2a2a2a2a27272a2a2a290d1305";
  $l_html .= "010101062d2e30300c0f152b2b2e30313131313131312f1b1b0c1c2f2f30232b2b1c0f0f1b30232e2d0601010f1716161d2a2a2a1d16161d2a2a2a2304050508";
  $l_html .= "03030501000005090909090d0d222a2a2a2a2a2727272a2a2c202d0d03060208292e302e1b1b0c2e2b23313131313131312b0c1414140c2f2f2f30231c2f0f1c";
  $l_html .= "1c30302e2806020a0d1e1d161d2a2a2a2a1d161d2a2a2a27220d09090903040100000105050405080a27272a272a2a2423272a27241402080a0309071f23302b";
  $l_html .= "2e302f1c2e3031313131313131312b141815141c2f2f2f302e1c2e302e2e3023220709030a0a1e161d2a272a272a1d1d2a2a2a27230f05040504010100000101";
  $l_html .= "050808021b24272a24272a2423242a2723110205040509292e301b0c0c1b2e2b30313131313131313131311c182f3131312f2f30231b2f0c0c0c1b302e2d0304";
  $l_html .= "04050c1d1d272727272a2a272a2a2a27240f080804010101000001010103090923232427262427242624241d1e1e0c0a0309201f2e301c180f2e1c2e32313131";
  $l_html .= "31313131313131311c313131312f2f2f302b1c1c180f2e302b1f2d09030a1324241e1e27242a2a24272a2a272420090601010101000001010101051826262424";
  $l_html .= "1e171d1e1e1e241617100c020920222e2e301c1b1b2b1b2e31312b2f3131313131312f313131313131312f2f302b1b1c1b0c2b302b1f222d0905141e1e161627";
  $l_html .= "23272723272a2a27241405010101010100000101010108091e1e1d1d17171d16171e1d1617172d0720221f2323302e2f2f2f1b2331312b1c1c1b313232312b1c";
  $l_html .= "2b1b313131312f2f2f2e1b2f2f2e2e302e1f222220090a1017161624241e1e1e242727242420020101010101000001010101040f1e171d1d17161d16171d1d16";
  $l_html .= "162d0720221f232e232f1c1b1b2e1b30312f1b1b0c1c323232311c1b0c1c31313131312f2f2e0c2f1b1b1c2f2e2928222220072d101617241e171716241e1e24";
  $l_html .= "2614010101010101000001010101040a1e17171d16161d16171d16162d0b20191f23262e2e1b15151b2e1b30311b150f0f143132321c150f0f142f313131312f";
  $l_html .= "2f2e0c300c0f142b2e2907122219200712101d1e171d17161d1616171e0a01010101010100000101010106091e17171d17161616171d162d0b20191f2626262e";
  $l_html .= "2e3014141b2e1b2f31312b18141c2f3232323118151c2b3131312f2f2f2e0c2e1b151b302b29070725221920072d1d17161d1d161d1616171709020101010101";
  $l_html .= "0000010101010104111617171d16161d1721250b1a191f262626262e2e302b2f2f2f0c2e31313114323232323232321b2f31311c2f2b1c2f302b1b2f302f2f30";
  $l_html .= "2b20070707251a19200b2d10161d17161d161d16110401010101010100000101040309091f1617171d16161d11090b1a191f2626262626262b302e1c1b2e1b1c";
  $l_html .= "3231323132323232323232313131311b1b1b2b2f301b1b2b1b1b2f301c2d07070707251a19200b2d101616171d17161620090903040101010000010102080805";
  $l_html .= "14171d1716161d21090b1a191f262626262626231c301b14141c1c1b2f313132323232313232323131311c0c0c0c1b2f2e0c2b1c14141c301b25070707070725";
  $l_html .= "1a1a200b2d10161d171d1610180508080501010100000102040402080c291d171d1d1025251a1a1f26262626262623231c231c0f141c2f0c1c3231323232322b";
  $l_html .= "1c2b1b31312f1c140f0f0c2b1b0c2f1c15152b2e11070707070707070b1a1a200b25101d161d16110608020405050101000002090925120d0d0a1b161d102525";
  $l_html .= "0e1a1f262626262626242c29222e301c2b2f2e1c0c2e31313232322b1b1b1b313131312f182b312b142b2e302b2b301c2e21282507070707070b1a1a0e0b2510";
  $l_html .= "1616110a030d250909090501000008090602050405080a101007250e1a1f26262626242128120b0b281b302f1b1c1c300c0c2f3131311c140c14142f31313131";
  $l_html .= "1c312e140c2f1c1b1c30231b2323232421282507070707200e0e0b2510100a02050405080309020100000104050603090903060809120e0e1f1e26262128120b";
  $l_html .= "0b0b0b0b0b112e301b15151c2f140c2f3131312b0f0f141c31313131312b14142f1b15151c301c2b232323232424242c28250707280e0e250b030a0309090306";
  $l_html .= "05040101000001010102060205080609120e0e1f242128120b0b0b0b0b0b0b0b0b201b2e0c0f0c302f2e14142b3131310f1c3131313131301b15142e2f2f0f14";
  $l_html .= "1b2f0c23232323232424242424242c2912280e0e1209060205020605010101010000010101010503090907120e0e212128120b0b0b0b0b0b0b0b0b0b0b0b131c";
  $l_html .= "302f2b2b1c2b2e0c0f141c2f2f313131322f1b150f1b2f2b1c2b1c2f301b2b23232323232424242424242427272c1a0e0e12070b070601010101010100000101";
  $l_html .= "010101020809120e0e0e0e0e1a1a1a1a1a28282812250b0b0b0b251b2b302f0c14142e2f2b140f0f0f15150f181818142b2f1c14140c2f301c1b232323232324";
  $l_html .= "24272c2c2121211a1a0e0e0e0e0e1209080501010101010100000101010101050307070b0b12120e0e0e1a1a1a1919192222211a1a28281a0c2e30151814302b";
  $l_html .= "2e2e2b1b14150f15141b2e2f2b2b300f180c302b0c23242c2c21211f221919191a1a1a0e0e0e12120b0b0707030101010101010100000101010101010409090d";
  $l_html .= "2d250b0707070b25121220201919222222221f1f1f0c2b2b2f1c1b0c141b302b2f2e2f2b2f2b2f0c140c1c1c2f2e1c141f1f2222222222191920201225250b07";
  $l_html .= "07070b25122d0903010101010101010100000101010101010502041527212c2c2c21292812250b0707070b252d202922221f0c1c30302b0f0f1b2f1b1b1c301b";
  $l_html .= "1b1b300c18152e30301b141f2229202d12250b090907070b2512281a212c2a2a271b040204010101010101010000010101010101050609031c2a2a272427272a";
  $l_html .= "27272a2c210d06080309030907072d0c0c2e30152b1c1b15150c2e140f141c1b1c14302b140c25070709030906020505020d132c2a2a2a272a2a2a2a27210306";
  $l_html .= "0401010101010101000001010101010109030201020d21272727272a2a2a2a2723230c0a0d050108030508251c141b2e3030300c142b2e2b0f2b3030302b1415";
  $l_html .= "13070505030201020d081813272724272a2a2a272a2a2a2a271b020303010101010101010000010101010105050102030d081b272a2724272a2a272723262626";
  $l_html .= "1801080d04010a09201f1b15141c2e232f30303030232b1b0f151b1f2d090601050d020105222c2a2a27232a2a2a2a27272a2a27241302010204010101010101";
  $l_html .= "00000101010101080803030804021f272a2724272a2a2423241e1e2611080d0501080302251f1f232b1b150f0f150f0f1818150d2d281f2207050d0201020d02";
  $l_html .= "1c23272a2724242a2a2a2a2727272a2723130303020801010101010100000101010105070908040203031b24272724232727242626241e1e1e130201020d0508";
  $l_html .= "07291f2c262323232e2e222d12250b070b211f2d0902020d0501021f262623241e2624272a2a272427242724230f050609090401010101010000010101010809";
  $l_html .= "0605030906041423272724261e1e241e261e241e1e1101040d08010d06251f21232323232424210707070707281f2209030a010a0a041526261e1e16171d2727";
  $l_html .= "272a2724241e171e11090604030305010101010100000101010101010102060501020d232424271e1616171e1e1e24171e1e010a0a01080d0403291f2c232324";
  $l_html .= "242721070707070b21222d06050d05040d0a111e1e1e1717162a2a24242727241e16161e18020805040101010101010100000101010101010101010101060326";
  $l_html .= "261e17171d161717171e1e2c0f18081305010d08010825221f232323242421070707071222220702010a0a0102221e1e1e1d1d1d2a2a2a241e1d24241716160f";
  $l_html .= "010101010101010101010101000001010101010101010101010404291e171d16171d161616171e211b05130801080d04010a0729222c23232427210707070b1a";
  $l_html .= "22200b0a01050d0201111e241e1d272727272a1e1716171d16161101010101010101010101010101000001010101010101010101010102032616161616171d1d";
  $l_html .= "1717172c150d0d02050d0801040d0825221f24232427210707071222220b080d04010a0a182326242424242724242a1d1616161d161615010101010101010101";
  $l_html .= "01010101000001010101010101010101010104040d10161616171b262424261b0c23232c2c2c1504080a040920222c2324272107070b1a222003010d18040513";
  $l_html .= "152626241e1e172426241d1d1616161d171b040101010101010101010101010100000101010101010101010101010403031811161617132c2a270c2324242424";
  $l_html .= "2a2a242b1302010825191f2424272107071222190b02011514140f0a211e241e1e171d1e1e171d1d161610110d03010101010101010101010101010100000101";
  $l_html .= "01010101010101010101040604020d1b1617212c2127232427272a2727242424261b04080720192c242721070b1a19200702181b1a1a130f1f1e1e1e1716241e";
  $l_html .= "17161d1d161613050406010101010101010101010101010100000101010101010101010101010101020d02010d26152122212c272427272a2a2724241e1e110a";
  $l_html .= "062519212427210725221925030a150f22190f0a111e1e171616241716171e17100f0a0d05010101010101010101010101010101000001010101010101010101";
  $l_html .= "010105030308010603180a2222222127242424272a272424171e1e1f050720192c27210b281920070a0d0f0c140c02031b10171617111d1710130c0809080106";
  $l_html .= "03060401010101010101010101010101000001010101010101010101010102070601060905080d0202091f27272324272a2423242613111e0212121921272125";
  $l_html .= "1a19250d0f2b140d050d050a1f111f0c130c26260f02030202090801030705010101010101010101010101010000010101010101010101010101050805050605";
  $l_html .= "040608010303142424242624241e1e1e2402080a080d07201a2721281a200929110e22151513130f131813050213050903010608010206050208040101010101";
  $l_html .= "010101010101010100000101010101010101010101010101010101010101010209050d2324241e171d17171e1e1b0a080a0a06121a21211a1a25061515221c14";
  $l_html .= "0a0c13010d050a0a010a0a0209050101010101010101010101010101010101010101010100000101010101010101010101010101010101010101010102020d14";
  $l_html .= "1e17161d1d16171d1e1e20050d0a0507201a211a2007051513141414190c22151c14020d05050d05050101010101010101010101010101010101010101010101";
  $l_html .= "0000010101010101010101010101010101010101010101010403080113101617171d1e17171711040d02020b120e0e0e1209050a131f1a0c13131a1c1919180a";
  $l_html .= "0a010603010101010101010101010101010101010101010101010101000001010101010101010101010101010101010101010101020304020d0513161717180d";
  $l_html .= "0a0c13050d050803070e0e0e070305150f191a1b0c1b1c151a1914020d0504030401010101010101010101010101010101010101010101010000010101010101";
  $l_html .= "010101010101010101010101010101010105010306040d0f1011040d050606020d01080a09120e120603020f130c140c150d020d0c0c0d010306010501010101";
  $l_html .= "01010101010101010101010101010101010101010000010101010101010101010101010101010101010101010101020905080301080c0203010302080d010a06";
  $l_html .= "03070e07030308010d0c0c0a05090506020403020209020101010101010101010101010101010101010101010101010100000101010101010101010101010101";
  $l_html .= "01010101010101010108030601060501010101080409050a0a01030609070b070306060103080203010801010101020601030302010101010101010101010101";
  $l_html .= "01010101010101010101010100000101010101010101010101010101010101010101010101060706010401010101010102090103060103080906070309060301";
  $l_html .= "06060509050101010101010401060708010101010101010101010101010101010101010101010101000001010101010101010101010101010101010101010101";
  $l_html .= "01020604010101010101010102030409080109080605080506060301060301030501010101010101010506050101010101010101010101010101010101010101";
  $l_html .= "01010101000001010101010101010101010101010101010101010101010101010101010101010101010502090504030501010101010203010809050501010101";
  $l_html .= "01010101010101010101010101010101010101010101010101010101010101010000010101010101010101010101010101010101010101010101010101010101";
  $l_html .= "01010101010403070801050101010101010105010607060101010101010101010101010101010101010101010101010101010101010101010101010100000101";
  $l_html .= "01010101010101010101010101010101010101010101010101010101010101010101020304010101010101010101010105060501010101010101010101010101";
  $l_html .= "01010101010101010101010101010101010101010101010100000101010101010101010101010101010101010101010101010101010101010101010101010101";
  $l_html .= "010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010000040000002701ffff030000000000}\\par}}}{\\f1\\fs16\\insrsid1469200 Minist\\'e9rio da Justi\\'e7a";
  $l_html .= "\\par }\\pard \\qc \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 {\\f1\\fs16\\insrsid1469200 Secretaria Executiva";
  $l_html .= "\\par Subsecretaria de Planejamento, Or\\'e7amento e Administra\\'e7\\'e3o";
  $l_html .= "\\par Coordena\\'e7\\'e3o-Geral de Log\\'edstica";
  $l_html .= "\\par Divis\\'e3o de Execu\\'e7\\'e3o Or\\'e7ament\\'e1ria e Financeira";
  $l_html .= "\\par ";
  $l_html .= "\\par }\\trowd \\irow0\\irowband0\\lastrow \\ts11\\trgaph70\\trleft-70\\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrtbl \\clbrdrl\\brdrtbl \\clbrdrb\\brdrtbl \\clbrdrr\\brdrtbl \\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil \\cellx9360";
  $l_html .= "\\pard\\plain \\s1\\qc \\li0\\ri0\\keepn\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0 \\b\\i\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 RELAT\\'d3RIO DE VIAGENS NACIONAIS/INTERNACIONAIS";
  $l_html .= "\\par }\\pard\\plain \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\f1\\fs18\\insrsid1469200 Portaria N\\'ba 47/MPO 29/04/2003 \\endash  DOU 30/04/2003\\cell }\\pard ";
  $l_html .= "\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\b\\insrsid1469200 \\trowd \\irow0\\irowband0\\lastrow \\ts11\\trgaph70\\trleft-70\\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrtbl \\clbrdrl\\brdrtbl \\clbrdrb";
  $l_html .= "\\brdrtbl \\clbrdrr\\brdrtbl \\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil \\cellx9360\\row }\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 {\\b\\fs16\\insrsid1469200 ";
  $l_html .= "\\par }\\trowd \\irow0\\irowband0\\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt";
  $l_html .= "\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil \\cellx9360\\pard\\plain ";
  $l_html .= "\\s1\\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0 \\b\\i\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 1 \\endash  IDENTIFICA\\'c7\\'c3O DO SERVIDOR\\cell }\\pard\\plain ";
  $l_html .= "\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 \\trowd \\irow0\\irowband0\\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb";
  $l_html .= "\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ";
  $l_html .= "\\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil \\cellx9360\\row }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 ";
  $l_html .= "\\par }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\tx5220\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 Nome:  }{\\insrsid6709522" .f($RS1,"nm_pessoa"). "}{\\insrsid1469200                }{\\insrsid6709522                 }{\\insrsid1469200 Matr\\'edcula: ";
  $l_html .= "\\par Fun\\'e7\\'e3o/Cargo : " .f($RS1,"nm_tipo_vinculo"). "                             C\\'f3digo: ";
  $RS2 = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $l_html .= "\\par }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 \\'d3rg\\'e3o de Exerc\\'edcio: " . f($RS2,"nome_resumido");
  $l_html .= "\\par \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 \\trowd \\irow1\\irowband1\\lastrow \\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 ";
  $l_html .= "\\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil ";
  $l_html .= "\\cellx9360\\row }\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 {\\fs16\\insrsid1469200 ";
  $l_html .= "\\par }\\pard\\plain \\s1\\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0 \\b\\i\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 2 \\endash  IDENTIFICA\\'c7\\'c3O DO AFASTAMENTO\\tab \\cell ";
  $l_html .= "}\\pard\\plain \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 \\trowd \\irow0\\irowband0\\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 ";
  $l_html .= "\\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ";
  $l_html .= "\\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil \\cellx9360\\row }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 Autoriza\\'e7\\'e3o do Afastamento: " . f($RS,"codigo_interno");
  $l_html .= "\\par ";
  $l_html .= "\\par Percurso: " .strtolower($w_percurso). "     \\par Di\\'e1rias recebidas: Qtd: " .formatNumber($w_diaria,1,',','.'). " Valor: " .formatNumber($w_valor);
  $l_html .= "\\par ";
  $l_html .= "\\par }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\tx5040\\tx5220\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 Sa\\'edda: " .FormataDataEdicao(f($RS,"inicio")). "                                         Chegada:  " .FormataDataEdicao(f($RS,"fim")). "";
  $l_html .= "\\par }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 \\trowd \\irow1\\irowband1\\lastrow ";
  $l_html .= "\\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 ";
  $l_html .= "\\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil \\cellx9360\\row }\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 {\\fs16\\insrsid1469200 ";
  $l_html .= "\\par }\\pard\\plain \\s1\\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0 \\b\\i\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 3 \\endash  DESCRI\\'c7\\'c3O SUCINTA DA VIAGEM\\cell }\\pard\\plain ";
  $l_html .= "\\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 \\trowd \\irow0\\irowband0\\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb";
  $l_html .= "\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 ";
  $l_html .= "\\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil \\cellx9360\\row }\\trowd \\irow1\\irowband1\\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 ";
  $l_html .= "\\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth1150\\clshdrawnil \\cellx1080\\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl";
  $l_html .= "\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth8280\\clshdrawnil \\cellx9360\\pard\\plain \\s1\\qc \\li0\\ri0\\keepn\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0 ";
  $l_html .= "\\b\\i\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\b0\\insrsid1469200 Data";
  $l_html .= "\\par }\\pard\\plain \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par \\cell }\\pard\\plain \\s1\\qc \\li0\\ri0\\keepn\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0 \\b\\i\\f1\\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\b0\\insrsid1469200 Atividades";
  $l_html .= "\\par }\\pard\\plain \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \\fs24\\lang1046\\langfe1046\\cgrid\\langnp1046\\langfenp1046 {\\insrsid1469200 ";
  $l_html .= "\\par ";
  $l_html .= "\\par ";
  $l_html .= "\\par \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 \\trowd \\irow1\\irowband1\\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh";
  $l_html .= "\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth1150\\clshdrawnil \\cellx1080";
  $l_html .= "\\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth8280\\clshdrawnil \\cellx9360\\row }\\trowd \\irow2\\irowband2\\lastrow \\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl";
  $l_html .= "\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr";
  $l_html .= "\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil \\cellx9360\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 ";
  $l_html .= "Data: _____/_____/_____                                __________________________________    ";
  $l_html .= "\\par                                                                                 Assinatura do Servidor/Colaborador";
  $l_html .= "\\par \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\insrsid1469200 \\trowd \\irow2\\irowband2\\lastrow \\ts11\\trgaph70\\trleft-70\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 ";
  $l_html .= "\\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 \\trftsWidth1\\trpaddl70\\trpaddr70\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth9430\\clshdrawnil ";
  $l_html .= "\\cellx9360\\row }\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 {\\insrsid1469200 ";
  $l_html .= "\\par }}";

  return $l_html;
} 

// =========================================================================
// Rotina de preparação para envio de e-mail relativo a PCDs
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação. 
//            p_tipo:  1 - Inclusão
//                     2 - Tramitação
//                     3 - Conclusão
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  global $w_Disabled;
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,'PDGERAL');
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    $w_anexos         = array();

    // Recupera os dados da PCD
    $w_sg_tramite = f($RSM,'sg_tramite');
    $w_nome       = f($RSM,'codigo_interno');

    // Se for o trâmite de prestação de contas, envia e-mail ao proposto com o relatório de viagem anexado
    if ($w_sg_tramite=='EE') {
      // Configura o nome dos arquivo recebido e do arquivo registro
      $w_file = $conFilePhysical.$w_cliente.'/'.'relatorio_'.str_replace('/','-',$w_nome).'.doc';
      if (!is_writable($conFilePhysical.$w_cliente)) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: não há permissão de escrita no diretório.\\n'.$conFilePhysical.$w_cliente.'\');');
        ScriptClose();
      } else {
        if (!$handle = fopen($w_file,'w')) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: não foi possível abrir o arquivo para escrita.\\n'.$w_file.'\');');
          ScriptClose();
        } else {
          if (!fwrite($handle, RelatorioViagem($p_solic))) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: não foi possível inserir o conteúdo do arquivo.\\n'.$w_file.'\');');
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
      $w_html .= '      <tr valign="top"><td align="center"><b>INCLUSÃO DE PCD</b><br><br><td></tr>'.$crlf;
    } elseif ($w_sg_tramite=='EE') {
      $w_html .= '      <tr valign="top"><td align="center"><b>PRESTAÇÃO DE CONTAS DE PCD</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==2) {
      $w_html .= '      <tr valign="top"><td align="center"><b>TRAMITAÇÃO DE PCD</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==3) {
      $w_html .= '      <tr valign="top"><td align="center"><b>CONCLUSÃO DE PCD</b><br><br><td></tr>'.$crlf;
    } 
    if ($w_sg_tramite=='EE') {
      $w_html .= '      <tr valign="top"><td><b><font color="#BC3131">ATENÇÃO:<br>Conforme Portaria Nº 47/MPO 29/04/2003 – DOU 30/04/2003, é necessário elaborar o relatório de viagem e entregar os bilhetes de embarque.<br><br>Use o arquivo anexo para elaborar seu relatório de viagem e entregue-o assinado ao setor competente, juntamente com os bilhetes.</font></b><br><br><td></tr>'.$crlf;
    } else {
      $w_html .= '      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO: Esta é uma mensagem de envio automático. Não responda esta mensagem.</font></b><br><br><td></tr>'.$crlf;
    } 
    $w_html .= $crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html .= $crlf.'    <table width="99%" border="0">';
    // Identificação da PCD
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DA PCD</td>';
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html .= $crlf.'          <tr valign="top">';
    $w_html .= $crlf.'            <td>Proposto:<br><b>'.f($RSM,'nm_prop').'</b></td>';
    $w_html .= $crlf.'            <td>Unidade proponente:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
    $w_html .= $crlf.'          <tr valign="top">';
    $w_html .= $crlf.'            <td>Primeira saída:<br><b>'.FormataDataEdicao(f($RSM,'inicio')).' </b></td>';
    $w_html .= $crlf.'            <td>Último retorno:<br><b>'.FormataDataEdicao(f($RSM,'fim')).' </b></td>';
    $w_html .= $crlf.'          </table>';
    // Informações adicionais
    if (Nvl(f($RSM,'descricao'),'')>'') {
      if (Nvl(f($RSM,'descricao'),'')>'') $w_html .= $crlf.'      <tr><td valign="top">Descrição da PCD:<br><b>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
    } 
    $w_html .= $crlf.'    </table>';
    $w_html .= $crlf.'</tr>';

    //Recupera o último log
    $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
    foreach ($RS as $row) { $RS = $row; if(strpos(f($row,'despacho'),'*** Nova versão')===false) break; }
    $w_data_encaminhamento = f($RS,'phpdt_data');
    if ($p_tipo==2) {
      if ($w_sg_tramite=='EE') {
        // Recupera o número máximo de dias para entrega da prestação de contas
        $RS1 = db_getPDParametro::getInstanceOf($dbms,$w_cliente,null,null);
        foreach($RS1 as $row) { $RS1 = $row; break; }
        $w_dias_prest_contas = f($RS1,'dias_prestacao_contas');

        $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ORIENTAÇÕES PARA PRESTAÇÃO DE CONTAS</td>';
        $w_html .= $crlf.'        <tr><td valign="top" colspan="2" bgcolor="'.$w_TrBgColor.'">';
        $w_html .= $crlf.'          <p>Esta PCD foi autorizada. Você deve entregar os documentos abaixo na unidade proponente (<b>'.f($RSM,'nm_unidade_resp').')</b>';
        $w_html .= $crlf.'          <ul>';
        $w_html .= $crlf.'          <li>Relatório de viagem (anexo) preenchido;';
        $w_html .= $crlf.'          <li>Bilhetes de embarque;';
        $w_html .= $crlf.'          <li>Notas fiscais de taxi, restaurante e hotel.';
        $w_html .= $crlf.'          </ul>';
        $w_html .= $crlf.'          <p>A data limite para entrega é até o último dia útil antes de: <b>'.substr(FormataDataEdicao(addDays(f($RSM,'fim'),$w_dias_prest_contas),4),0,-10).' </b>; caso contrário, suas viagens serão automaticamente bloqueadas pelo sistema.';

        $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA CONCESSÃO</td>';
        // Benefícios servidor
        $RS1 = db_getSolicData::getInstanceOf($dbms,$p_solic,'PDGERAL');
        if (count($RS1)>0) {
          $w_html .= $crlf.'        <tr><td valign="top" colspan="2" align="center" bgcolor="'.$w_TrBgColor.'"><b>Benefícios recebidos pelo proposto</td>';
          $w_html .= $crlf.'        <tr><td align="center" colspan="2">';
          $w_html .= $crlf.'          <TABLE WIDTH="100%" bgcolor="'.$w_TrBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
          $w_html .= $crlf.'            <tr>';
          if (Nvl(f($RS1,'valor_alimentacao'),0)>0) $w_html .= $crlf.'           <td>Auxílio-alimentação: <b>Sim</b></td>'; else $w_html .= $crlf.'           <td>Auxílio-alimentação: <b>Não</b></td>';
          $w_html .= $crlf.'              <td>Valor R$: <b>'.formatNumber(Nvl(f($RS1,'valor_alimentacao'),0)).'</b></td>';
          $w_html .= $crlf.'            <tr>';
          if (Nvl(f($RS1,'valor_transporte'),0)>0) $w_html .= $crlf.'           <td>Auxílio-transporte: <b>Sim</b></td>'; else $w_html .= $crlf.'           <td>Auxílio-transporte: <b>Não</b></td>';
          $w_html .= $crlf.'              <td>Valor R$: <b>'.formatNumber(Nvl(f($RS1,'valor_transporte'),0)).'</b></td>';
          $w_html .= $crlf.'          </table></td></tr>';
        }  

        //Dados da viagem
        $w_html .= $crlf.'        <tr><td valign="top" colspan="2" align="center" bgcolor="'.$w_TrBgColor.'"><b>Dados da viagem/cálculo das diárias</td>';

        $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$p_solic,null,'DADFIN');
        $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
        if (count($RS1)>0) {
          $i = 1;
          foreach($RS1 as $row) {
            $w_vetor_trechos[$i][1] = f($row,'sq_diaria');
            $w_vetor_trechos[$i][2] = f($row,'cidade_dest');
            $w_vetor_trechos[$i][3] = f($row,'nm_destino');
            $w_vetor_trechos[$i][4] = FormataDataEdicao(f($row,'phpdt_chegada'));
            $w_vetor_trechos[$i][5] = FormataDataEdicao(f($row,'phpdt_saida'));
            $w_vetor_trechos[$i][6] = formatNumber(Nvl(f($row,'quantidade'),0),1,',','.');
            $w_vetor_trechos[$i][7] = formatNumber(Nvl(f($row,'valor'),0));
            $w_vetor_trechos[$i][8] = Nvl(f($row,'quantidade'),0);
            $w_vetor_trechos[$i][9] = Nvl(f($row,'valor'),0);
            if ($i>1) {
              $w_vetor_trechos[$i-1][5] = FormataDataEdicao(f($row,'phpdt_saida'));
            }
            $i += 1;
          } 
          $j       = $i;
          $i       = 1;
          $w_total = 0;
          $w_html .= $crlf.'     <tr><td align="center" colspan="2">';
          $w_html .= $crlf.'       <TABLE WIDTH="100%" bgcolor="'.$w_TrBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
          $w_html .= $crlf.'         <tr align="center">';
          $w_html .= $crlf.'         <td><b>Destino</td>';
          $w_html .= $crlf.'         <td><b>Saida</td>';
          $w_html .= $crlf.'         <td><b>Chegada</td>';
          $w_html .= $crlf.'         <td><b>Quantidade de diárias</td>';
          $w_html .= $crlf.'         <td><b>Valor unitário R$</td>';
          $w_html .= $crlf.'         <td><b>Total por localidade - R$</td>';
          $w_html .= $crlf.'         </tr>';
          $w_cor=$conTrBgColor;
          while($i!=($j-1)) {
            $w_html .= $crlf.'     <tr valign="top">';
            $w_html .= $crlf.'       <td>'.$w_vetor_trechos[$i][3].'</td>';
            $w_html .= $crlf.'       <td align="center">'.$w_vetor_trechos[$i][4].'</td>';
            $w_html .= $crlf.'       <td align="center">'.$w_vetor_trechos[$i][5].'</td>';
            $w_html .= $crlf.'       <td align="right">'.$w_vetor_trechos[$i][6].'</td>';
            $w_html .= $crlf.'       <td align="right">'.$w_vetor_trechos[$i][7].'</td>';
            $w_html .= $crlf.'       <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.formatNumber(($w_vetor_trechos[$i][8]*$w_vetor_trechos[$i][9])).'</td>';
            $w_html .= $crlf.'     </tr>';
            $w_total += ($w_vetor_trechos[$i][8]*$w_vetor_trechos[$i][9]);
            $i += 1;
          }

          $w_html .= $crlf.'        <tr>';
          $w_html .= $crlf.'          <td rowspan="5" align="right" colspan="3">&nbsp;</td>';
          $w_html .= $crlf.'          <td colspan="2"><b>(a) subtotal:</b></td>';
          $w_html .= $crlf.'          <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.formatNumber(Nvl($w_total,0)).'</td>';
          $w_html .= $crlf.'        </tr>';
          $w_html .= $crlf.'        <tr>';
          $w_html .= $crlf.'          <td colspan="2"><b>(b) adicional:</b></td>';
          $w_html .= $crlf.'          <td align="right">'.formatNumber(Nvl(f($RS,'valor_adicional'),0)).'</td>';
          $w_html .= $crlf.'        </tr>';
          $w_html .= $crlf.'        <tr>';
          $w_html .= $crlf.'          <td colspan="2"><b>(c) desconto auxílio-alimentação:</b></td>';
          $w_html .= $crlf.'          <td align="right">'.formatNumber(Nvl(f($RS,'desconto_alimentacao'),0)).'</td>';
          $w_html .= $crlf.'        </tr>';
          $w_html .= $crlf.'        <tr>';
          $w_html .= $crlf.'          <td colspan="2"><b>(d) desconto auxílio-transporte:</b></td>';
          $w_html .= $crlf.'          <td align="right">'.formatNumber(Nvl(f($RS,'desconto_transporte'),0)).'</td>';
          $w_html .= $crlf.'        </tr>';
          $w_html .= $crlf.'        <tr>';
          $w_html .= $crlf.'          <td colspan="2"><b>Total(a + b - c - d):</b></td>';
          $w_html .= $crlf.'          <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.formatNumber(Nvl($w_total,0)+Nvl(f($RS,'valor_adicional'),0)-Nvl(f($RS,'desconto_alimentacao'),0)-Nvl(f($RS,'desconto_transporte'),0)).'</td>';
          $w_html .= $crlf.'        </tr>';
          $w_html .= $crlf.'        </table></td></tr>';
        } 

        // Bilhete de passagem
        $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$p_solic,null,$SG);
        $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
        if (count($RS1)>0) {
          $i=0;
          $j=0;
          foreach($RS1 as $row) {
            if (nvl(f($row,'sq_cia_transporte'),'')>'') {
              if ($i==0) {
                $w_html .= $crlf.'        <tr><td valign="top" colspan="2" align="center" bgcolor="'.$w_TrBgColor.'"><b>Bilhete de passagem</td>';
                $w_html .= $crlf.'        <tr><td align="center" colspan="2"><TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
                $w_html .= $crlf.'         <tr bgcolor="'.$conTrBgColor.'" align="center">';
                $w_html .= $crlf.'         <td><b>Origem</td>';
                $w_html .= $crlf.'         <td><b>Destino</td>';
                $w_html .= $crlf.'         <td><b>Saida</td>';
                $w_html .= $crlf.'         <td><b>Chegada</td>';
                $w_html .= $crlf.'         <td><b>Cia. transporte</td>';
                $w_html .= $crlf.'         <td><b>Código vôo</td>';
                $w_html .= $crlf.'         </tr>';
                $w_cor=$conTrBgColor;
                $i=1;
              }
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              $w_html .= $crlf.'     <tr valign="middle" bgcolor="'.$w_cor.'">';
              $w_html .= $crlf.'       <td>'.Nvl(f($row,'nm_origem'),'---').'</td>';
              $w_html .= $crlf.'       <td>'.Nvl(f($row,'nm_destino'),'---').'</td>';
              $w_html .= $crlf.'       <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),3),0,-3).'</td>';
              $w_html .= $crlf.'       <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),3),0,-3).'</td>';
              $w_html .= $crlf.'       <td>'.Nvl(f($row,'nm_cia_transporte'),'---').'</td>';
              $w_html .= $crlf.'       <td>'.Nvl(f($row,'codigo_voo'),'---').'</td>';
              $w_html .= $crlf.'     </tr>';
              $j=1;
            }
          } 
          if ($j==1) {
            $w_html .= $crlf.'        </tr>';
            $w_html .= $crlf.'        </table></td></tr>';
            $RS1 = db_getSolicData::getInstanceOf($dbms,$p_solic,'PDGERAL');
            $w_html .= $crlf.'        <tr><td align="center" colspan="2"><TABLE WIDTH="100%" bgcolor="'.$w_TrBgColor.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
            $w_html .= $crlf.'        <tr><td><b>Nº do PTA/Ticket: </b>'.f($RS1,'PTA').'</td>';
            $w_html .= $crlf.'            <td><b>Data da emissão: </b>'.FormataDataEdicao(f($RS1,'emissao_bilhete')).'</td>';
            $w_html .= $crlf.'      </table>';
            $w_html .= $crlf.'    </td>';
          }
        } 
      } else {
        $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
        $w_html .= $crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
        $w_html .= $crlf.'          <tr><td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
        if (Nvl(f($RS,'despacho'),'')!='') {
          $w_html.=$crlf.'          <tr><td>Despacho:<br><b>'.CRLF2BR(f($RS,'despacho')).' </b></td>';
        }
        $w_html .= $crlf.'          </table>';
      }
    } 
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
    $RS = db_getCustomerSite::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    $w_html .= '      <tr valign="top"><td>'.$crlf;
    $w_html .= '         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html .= '      </td></tr>'.$crlf;
    $w_html .= '      <tr valign="top"><td>'.$crlf;
    $w_html .= '         Dados da ocorrência:<br>'.$crlf;
    $w_html .= '         <ul>'.$crlf;
    $w_html .= '         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html .= '         <li>Data: <b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
    $w_html .= '         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html .= '         </ul>'.$crlf;
    $w_html .= '      </td></tr>'.$crlf;
    $w_html .= '    </table>'.$crlf;
    $w_html .= '</td></tr>'.$crlf;
    $w_html .= '</table>'.$crlf;
    $w_html .= '</BODY>'.$crlf;
    $w_html .= '</HTML>'.$crlf;
    // Prepara os dados necessários ao envio
    $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclusão ou Conclusão
      if ($p_tipo==1) $w_assunto='Inclusão - '.$w_nome; else $w_assunto='Encerramento - '.$w_nome;
    } elseif ($w_sg_tramite=='EE') {
      // Prestação de contas
      $w_assunto='Prestação de Contas - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramitação
      $w_assunto='Tramitação - '.$w_nome;
    } 
    // Configura os destinatários da mensagem
    $RS = db_getTramiteResp::getInstanceOf($dbms,$p_solic,null,null);
    if (!count($RS)<=0) {
      foreach($RS as $row) {
        $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
     } 
    } 
    if(f($RSM,'st_sol')=='S') {
      // Recupera o e-mail do responsável
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
      // Remove o arquivo temporário
      if (!unlink($w_file)) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: não foi possível remover o arquivo temporário.\\n'.$w_file.'\');');
        ScriptClose();
      }
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail.\\n'.$w_resultado.'\');');
      ScriptClose();
    } 
  } 
}
// =========================================================================
// Procedimento que executa as operações de BD
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
    case 'PDIDENT':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O!='E') {
          if (Nvl($_REQUEST['w_justif_dia_util'],'')=='' && (RetornaExpediente($_REQUEST['w_inicio'],$w_cliente,null,null,null)=='N' || RetornaExpediente($_REQUEST['w_fim'],$w_cliente,null,null,null)=='N')) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'É necessário justificar o início e término de viagens em feriados!\');');
            ScriptClose();
            retornaFormulario('w_justif_dia_util');
            exit();
          } 
        }
        dml_putViagemGeral::getInstanceOf($dbms,$O,$w_cliente,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_sq_unidade_resp'],
            $_REQUEST['w_sq_prop'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_tipo_missao'],$_REQUEST['w_descricao'],
            $_REQUEST['w_justif_dia_util'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_data_hora'],
            $_REQUEST['w_aviso'], $_REQUEST['w_dias'], $_REQUEST['w_projeto'], $_REQUEST['w_demanda'],
            $_REQUEST['w_cpf'], $_REQUEST['w_nm_prop'], $_REQUEST['w_nm_prop_res'],
            $_REQUEST['w_sexo'], $_REQUEST['w_vinculo'], $_REQUEST['w_inicio_atual'], 
            &$w_chave_nova, $w_copia, &$w_codigo);
        if ($O=='I') {
          // Recupera os dados para montagem correta do menu
          $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\''.$w_codigo.' cadastrada com sucesso!\');');
          ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento='.$w_codigo.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.RemoveTP($TP)).'\';');
          ScriptClose();
        } elseif ($O=='E') {
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } else {
          // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          $RS1 = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PDOUTRA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putViagemOutra::getInstanceOf($dbms,$O,$SG,
            $_REQUEST['w_chave'],              $_REQUEST['w_chave_aux'],          $_REQUEST['w_sq_pessoa'],
            $_REQUEST['w_cpf'],                $_REQUEST['w_nome'],               $_REQUEST['w_nome_resumido'],
            $_REQUEST['w_sexo'],               $_REQUEST['w_sq_tipo_vinculo'],    $_REQUEST['w_matricula'],
            $_REQUEST['w_rg_numero'],          $_REQUEST['w_rg_emissao'],         $_REQUEST['w_rg_emissor'],
            $_REQUEST['w_ddd'],                $_REQUEST['w_nr_telefone'],        $_REQUEST['w_nr_fax'],
            $_REQUEST['w_nr_celular'],         $_REQUEST['w_sq_agencia'],         $_REQUEST['w_operacao'],
            $_REQUEST['w_nr_conta'],           $_REQUEST['w_sq_pais_estrang'],    $_REQUEST['w_aba_code'],
            $_REQUEST['w_swift_code'],         $_REQUEST['w_endereco_estrang'],   $_REQUEST['w_banco_estrang'],
            $_REQUEST['w_agencia_estrang'],    $_REQUEST['w_cidade_estrang'],     $_REQUEST['w_informacoes'],
            $_REQUEST['w_codigo_deposito']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PDTRECHO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putPD_Deslocamento::getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],
            $_REQUEST['w_cidade_orig'],$_REQUEST['w_data_saida'],$_REQUEST['w_hora_saida'],
            $_REQUEST['w_cidade_dest'],$_REQUEST['w_data_chegada'],$_REQUEST['w_hora_chegada'],
            null,null);
        ScriptOpen('JavaScript');
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PDVINC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_demanda'])-1; $i=$i+1) {
            if (Nvl($_POST['w_demanda'][$i],'')>'') {
              dml_putPD_Atividade::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_POST['w_demanda'][$i]);
            } 
          } 
        } elseif ($O=='E') {
          dml_putPD_Atividade::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_demanda']);
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$_REQUEST['w_chave']).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'DADFIN':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putPD_Missao::getInstanceOf($dbms,null,$_REQUEST['w_chave'],Nvl($_REQUEST['w_vlr_alimentacao'],0),Nvl($_REQUEST['w_vlr_transporte'],0),Nvl($_REQUEST['w_adicional'],0),
            Nvl($_REQUEST['w_desc_alimentacao'],0),Nvl($_REQUEST['w_desc_trasnporte'],0),null,null,null,null);
        for ($i=0; $i<=count($_POST['w_sq_diaria'])-1; $i=$i+1) {
          if ($_POST['w_sq_diaria'][$i]>'') {
            dml_putPD_Diaria::getInstanceOf($dbms,'A',$_REQUEST['w_chave'],$_POST['w_sq_diaria'][$i],$_POST['w_sq_cidade'][$i],
                Nvl($_POST['w_qtd_diarias'][$i],0),Nvl($_POST['w_vlr_diarias'][$i],0));
          } else {
            dml_putPD_Diaria::getInstanceOf($dbms,'I',$_REQUEST['w_chave'],null,$_POST['w_sq_cidade'][$i],
                Nvl($_POST['w_qtd_diarias'][$i],0),Nvl($_POST['w_vlr_diarias'][$i],0));
          } 
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'DadosFinanceiros&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&w_menu='.$_REQUEST['w_menu'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'INFPASS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putPD_Missao::getInstanceOf($dbms,null,$_REQUEST['w_chave'],null,null,null,
            null,null,$_REQUEST['w_pta'],$_REQUEST['w_emissao_bilhete'],$_REQUEST['w_valor_passagem'],$SG);
        for ($i=0; $i<=count($_POST['w_sq_deslocamento'])-1; $i=$i+1) {
          dml_putPD_Deslocamento::getInstanceOf($dbms,'P', $_REQUEST['w_chave'],$_POST['w_sq_deslocamento'][$i], null,null,null,null,null,null,
            $_POST['w_sq_cia_transporte'][$i],Nvl($_POST['w_codigo_voo'][$i],0));
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'Informarpassagens&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&w_menu='.$_REQUEST['w_menu'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PDENVIO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ((false!==(strpos(strtoupper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(strtoupper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
          // Verifica se outro usuário já enviou a solicitação
          $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PDINICIAL');
          if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!\');');
            ScriptClose();
            retornaFormulario('w_observacao');
            exit();
          } else {
            // Se foi feito o upload de um arquivo 
            if (UPLOAD_ERR_OK==0) {
              $w_maximo = $_REQUEST['w_upload_maximo'];
              foreach ($_FILES as $Chv => $Field) {
                if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
                  // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                  ScriptClose();
                  retornaFormulario('w_observacao');
                  exit();
                }
                if ($Field['size'] > 0) {
                  // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                  if ($Field['size'] > $w_maximo) {
                    ScriptOpen('JavaScript');
                    ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                    ScriptClose();
                    retornaFormulario('w_observacao');
                    exit();
                  } 
                  // Se já há um nome para o arquivo, mantém 
                  $w_file = basename($Field['tmp_name']);
                  if (strpos($Field['name'],'.')!==false) {
                    $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
                  }
                  $w_tamanho = $Field['size'];
                  $w_tipo    = $Field['type'];
                  $w_nome    = $Field['name'];
                  if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
                } 
              } 
              dml_putDemandaEnvio::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                  $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
                  $w_file,$w_tamanho,$w_tipo,$w_nome);
              //Rotina para gravação da imagem da versão da solicitacão no log.
              if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
                $RS = db_getTramiteData::getInstanceOf($dbms,$_REQUEST['w_tramite']);
                $w_sg_tramite = f($RS,'sigla');
                if($w_sg_tramite=='CI') {
                  $w_html = VisualViagem($w_chave,'L',$w_usuario,$P1,'1');
                  CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
                }
              }      
            } else {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
              ScriptClose();
            } 
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
            ScriptClose();
          } 
        } else {
          $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PDINICIAL');
          if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!\');');
            ScriptClose();
            retornaFormulario('w_observacao');
            exit();
          } else {
            // Verifica o próximo trâmite
            if ($_REQUEST['w_envio']=='N') {
              $RS = db_getTramiteList::getInstanceOf($dbms,$_REQUEST['w_tramite'],'PROXIMO',null);
            } else {
              $RS = db_getTramiteList::getInstanceOf($dbms,$_REQUEST['w_tramite'],'ANTERIOR',null);
            } 
            foreach($RS as $row) { $RS = $row; break; }
            $RS1 = db_getTramiteSolic::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS,'sq_siw_tramite'),null,null);
            if (count($RS1)<=0) {
              foreach($RS1 as $row) { $RS1 = $row; break; }
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'ATENÇÃO: Não há nenhuma pessoa habilitada a cumprir o trâmite "'.f($RS,'nome').'"!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            } 
            if ($_REQUEST['w_envio']=='N') {
              dml_putViagemEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
                $_REQUEST['w_envio'],$_REQUEST['w_despacho'],$_REQUEST['w_justificativa']);
            } else {
              dml_putViagemEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
                $_REQUEST['w_envio'],$_REQUEST['w_despacho'],$_REQUEST['w_justificativa']);
            }
            if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
              $RS = db_getTramiteData::getInstanceOf($dbms,$_REQUEST['w_tramite']);
              $w_sg_tramite = f($RS,'sigla');
              if($w_sg_tramite=='CI') {
                $w_html = VisualViagem($_REQUEST['w_chave'],'L',$w_usuario,$P1,'1');
                CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
              }
            }                  
            // Envia e-mail comunicando de tramitação
            SolicMail($_REQUEST['w_chave'],2);
            if ($P1==1) {
              // Se for envio da fase de cadastramento, remonta o menu principal
              // Recupera os dados para montagem correta do menu
              $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
              ScriptOpen('JavaScript');
              ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG='.f($RS,'sigla').'&TP='.RemoveTP(RemoveTP($TP)).MontaFiltro('GET')).'\';');
              ScriptClose();
            } else {
              // Volta para a listagem 
              ScriptOpen('JavaScript');
              ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
              ScriptClose();
            } 
          } 
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'PDCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta PCD para outra fase de execução!\');');
          ScriptClose();
          exit();
        } else {
          dml_putDemandaConc::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],$_REQUEST['w_custo_real'],
            $w_file,$w_tamanho,$w_tipo,$w_nome);
          // Envia e-mail comunicando a conclusão
          SolicMail($_REQUEST['w_chave']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
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
  case 'OUTRA':             OutraParte(); break;
  case 'TRECHOS':           Trechos(); break;
  case 'VINCULACAO':        Vinculacao(); break;
  case 'DADOSFINANCEIROS':  DadosFinanceiros(); break;
  case 'VISUAL':            Visual(); break;
  case 'EXCLUIR':           Excluir(); break;
  case 'ENVIO':             Encaminhamento(); break;
  case 'ANOTACAO':          Anotar(); break;
  case 'CONCLUIR':          Concluir(); break;
  case 'EMISSAO':           Emissao(); break;
  case 'INFORMARPASSAGENS': InformarPassagens(); break;
  case 'PRESTACAOCONTAS':   PrestacaoContas(); break;
  case 'GRAVA':             Grava(); break; 
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

