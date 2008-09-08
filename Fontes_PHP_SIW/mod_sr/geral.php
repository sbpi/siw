<?
session_start();
$w_dir_volta    = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicSR.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteResp.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteSolic.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getOpiniao.php');
include_once($w_dir_volta.'classes/sp/db_getRecurso.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRecursos.php');
include_once($w_dir_volta.'funcoes/selecaoTipoRecurso_PE.php');
include_once($w_dir_volta.'funcoes/selecaoRecurso.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoTipoVisao.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoOpiniao.php');
include_once($w_dir_volta.'funcoes/selecaoProcedimentoTransp.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicRecurso.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicOpiniao.php');
include_once('visualgeral.php');
include_once('validageral.php');

// =========================================================================
//  /Geral.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia o m�dulo de recursos log�sticos
// Mail     : alex@sbpi.com.br
// Criacao  : 17/11/2006 12:25
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = C   : Conclus�o
//                   = P   : Pesquisa


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
$w_pagina       = 'geral.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_sr/';
$w_troca        = $_REQUEST['w_troca'];

// Se for acompanhamento, entra na filtragem  
if (nvl($O,'')=='') {
  if ($P1==3) $O = 'P'; else $O = 'L';
}

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  case 'F': $w_TP=$TP.' - Informa��es'; break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

$w_copia        = $_REQUEST['w_copia'];
$p_sq_menu      = strtoupper($_REQUEST['p_sq_menu']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);
$p_unidade      = strtoupper($_REQUEST['p_unidade']);
$p_proponente   = strtoupper($_REQUEST['p_proponente']);
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$p_ini_i        = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f        = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i        = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f        = strtoupper($_REQUEST['p_fim_f']);
$p_atraso       = strtoupper($_REQUEST['p_atraso']);
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

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de visualiza��o resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);

  $w_tipo=$_REQUEST['w_tipo'];
  if ($O=='L') {
    if (($P1==3) || ($w_tipo=='WORD')) {
      $w_filtro='';
      if ($p_chave>'') $w_filtro .= '<tr valign="top"><td align="right">Demanda n� <td>[<b>'.$p_chave.'</b>]';
      if ($p_prazo>'') $w_filtro .= ' <tr valign="top"><td align="right">Prazo para conclus�o at�<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_solicitante>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Solicitante <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Setor solicitante <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_usu_resp>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_uorg_resp>''){
        $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
        $w_filtro .= '<tr valign="top"><td align="right">Setor executor <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_pais>'') {
        $RS = db_getCountryData::getInstanceOf($dbms,$p_pais);
        $w_filtro .= '<tr valign="top"><td align="right">Pa�s <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_regiao>'') {
        $RS = db_getRegionData::getInstanceOf($dbms,$p_regiao);
        $w_filtro .= '<tr valign="top"><td align="right">Regi�o <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uf>'') {
        $RS = db_getStateData::getInstanceOf($dbms,$p_pais,$p_uf);
        $w_filtro .= '<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_cidade>'') {
        $RS = db_getCityData::getInstanceOf($dbms,$p_cidade);
        $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_prioridade>'') {
        $RS = db_getOpiniao::getInstanceOf($dbms,null,$w_cliente,null,$p_prioridade, null);
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro .= '<tr valign="top"><td align="right">Opini�o <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_proponente>'') $w_filtro .= '<tr valign="top"><td align="right">Proponente <td>[<b>'.$p_proponente.'</b>]';
      if ($p_assunto>'')    $w_filtro .= '<tr valign="top"><td align="right">Detalhamento <td>[<b>'.$p_assunto.'</b>]';
      if ($p_palavra>'')    $w_filtro .= '<tr valign="top"><td align="right">Palavras-chave <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">In�cio <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data programada <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasadas</b>]';
      if ($w_filtro>'')     $w_filtro  = '<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 

    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for c�pia, aplica o filtro sobre todas as solicita��es vis�veis pelo usu�rio
      $RS = db_getSolicSR::getInstanceOf($dbms, $w_cliente,
          (($P1==3) ? nvl($p_sq_menu,0) : f($RS,'sq_menu')),
          $w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, null, null, null, null, null);
    } else {
      $RS = db_getSolicSR::getInstanceOf($dbms, $w_cliente,
          (($P1==3) ? nvl($p_sq_menu,0) : f($RS,'sq_menu')),
          $w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, null, null, null, null, null);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'phpdt_fim','asc','phpdt_inclusao','asc');
    } else {
      $RS = SortArray($RS,'phpdt_fim','asc','phpdt_inclusao','asc');
    }
  }

  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']); 
    $w_embed = 'WORD';
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    ShowHTML('<HEAD>');
    Estrutura_CSS($w_cliente);
    if ($P1==2) ShowHTML ('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.MontaURL('MESA').'">');
    ShowHTML("<TITLE>".$conSgSistema." - Listagem de solicita��es</TITLE>");
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ((strpos('CP',$O)!==false)) {
      if ($P1!=1 || $O=='C') {
        // Se n�o for cadastramento ou se for c�pia
        Validate('p_chave','N�mero da solicita��o','','','1','18','','0123456789');
        Validate('p_assunto','descricao','','','2','90','1','1');
        Validate('p_fim_i','Conclus�o inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim_f','Conclus�o final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de conclus�o ou nenhuma delas!\');');
        ShowHTML('     theForm.p_fim_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_fim_i','Conclus�o inicial','<=','p_fim_f','Conclus�o final');
      } 
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    // Se for recarga da p�gina
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='I') {
    BodyOpenClean('onLoad=\'document.Form.w_smtp_server.focus();\'');
  } elseif ($O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O=='E') {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } elseif (!(strpos('CP',$O)===false)) {
    if ($P1!=1 || $O=='C') {
      // Se for cadastramento
      BodyOpenClean('onLoad=\'document.Form.p_chave.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.p_ordena.focus()\';');
    } 
  } else {
    BodyOpenClean('onLoad=this.focus();');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($w_filtro>'') ShowHTML($w_filtro);
  if ($P1==1) ShowHTML('<div align="left"><table border=0><tr valign="top"><td><b>Finalidade:</b><td>'.f($RS_Menu,'finalidade').'</tr></table></div>');
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
    if ((strpos(strtoupper($R),'GR_')===false) && $P1!=6 && $w_tipo!='WORD') {
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
    ShowHTML('    <td align="right">');
    if ($w_tipo!='WORD') {
      ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('    <b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    if ($w_tipo!='WORD') {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('N�','sq_siw_solicitacao').'</td>');
      if ($P1==3) {
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Servi�o','nome').'</td>');
        ShowHTML('          <td colspan=2><b>Data</td>');
      } elseif (f($RS_Menu,'data_hora')==1 || f($RS_Menu,'data_hora')==2) {
        ShowHTML('          <td colspan=1><b>Data</td>');
      } elseif (f($RS_Menu,'data_hora')>0) {
        ShowHTML('          <td colspan=2><b>Data</td>');
      }
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Solicitante','nm_solic').'</td>');
      if ($P1!=3) {
        if ($SG=='SRTRANSP') {
          // Se for cadastramento ou mesa de trabalho
          ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Detalhamento','justificativa').'</td>');
        } else {
          // Se for cadastramento ou mesa de trabalho
          ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Detalhamento','descricao').'</td>');
        }
      } else {
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      } 
      ShowHTML('          <td rowspan=2><b>Opera��es</td>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      if (f($RS_Menu,'data_hora')==1 || f($RS_Menu,'data_hora')==2 || $P1==3) {
        ShowHTML('          <td><b>'.LinkOrdena('Programada','phpdt_programada').'</td>');
      } elseif (f($RS_Menu,'data_hora')>0) {
        ShowHTML('          <td><b>'.LinkOrdena('In�cio','phpdt_inicio').'</td>');
        ShowHTML('          <td><b>'.LinkOrdena('T�rmino','phpdt_fim').'</td>');
      }
      if ($P1==3) ShowHTML('          <td><b>'.LinkOrdena('Conclus�o','phpdt_conclusao').'</td>');
    } else {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td rowspan=2><b>N�</td>');
      if ($P1==3) {
        ShowHTML('          <td rowspan=2><b>Servi�o</td>');
        ShowHTML('          <td colspan=2><b>Data</td>');
      } elseif (f($RS_Menu,'data_hora')==1 || f($RS_Menu,'data_hora')==2) {
        ShowHTML('          <td colspan=1><b>Data</td>');
      } elseif (f($RS_Menu,'data_hora')>0) {
        ShowHTML('          <td colspan=2><b>Data</td>');
      }
      ShowHTML('          <td rowspan=2><b>Solicitante</td>');
      if ($P1!=3) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>Detalhamento</td>');
      } else {
        ShowHTML('          <td rowspan=2><b>Fase atual</td>');
      } 
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      if (f($RS_Menu,'data_hora')==1 || f($RS_Menu,'data_hora')==2 || $P1==3) {
        ShowHTML('          <td><b>Programada</td>');
      } elseif (f($RS_Menu,'data_hora')>0) {
        ShowHTML('          <td><b>In�cio</td>');
        ShowHTML('          <td><b>T�rmino</td>');
      }
      if ($P1==3) ShowHTML('          <td><b>Conclus�o</td>');
    } 
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="'.(($P1==3) ? 8 : 7).'" align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;

        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),nvl(f($row,'phpdt_inicio'),f($row,'phpdt_inclusao')),f($row,'phpdt_programada'),f($row,'phpdt_inicio'),f($row,'phpdt_conclusao'),'S',addDays(f($row,'fim'),-1),f($row,'sg_tramite'), null));
        if ($w_tipo!='WORD') {
          ShowHTML('        <A class="HL" href="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'sq_siw_solicitacao').'</a>');
        } else {
          ShowHTML('        '.f($row,'sq_siw_solicitacao'));
        } 
        if ($P1==3) ShowHTML('        <td>'.f($row,'nome').'</td>');
        switch (f($row,'data_hora')) {
        case 0 :
          if ($P1==3) ShowHTML('        <td align="center">---</td>');
          break;
        case 1 :
          ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'phpdt_programada')),'-').'</td>');
          break;
        case 2 :
          ShowHTML('        <td align="center">'.Nvl(substr(FormataDataEdicao(f($row,'phpdt_programada'),3),0,-3),'-').'</td>');
          break;
        case 3 :
          if ($P1!=3) ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'phpdt_inicio')),'-').'</td>');
          ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'phpdt_fim')),'-').'</td>');
          break;
        case 4 :
          if ($P1!=3) ShowHTML('        <td align="center">'.Nvl(substr(FormataDataEdicao(f($row,'phpdt_inicio'),3),0,-3),'-').'</td>');
          ShowHTML('        <td align="center">'.Nvl(substr(FormataDataEdicao(f($row,'phpdt_fim'),3),0,-3),'-').'</td>');
          break;
        }
        if ($P1==3) ShowHTML('        <td align="center">'.Nvl(substr(FormataDataEdicao(f($row,'phpdt_conclusao'),3),0,-3),'---').'</td>');
        if ($w_tipo!='WORD') {
          ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>');
        } else {
          ShowHTML('        <td>'.f($row,'nm_solic').'</td>');
        } 
        if ($P1!=3) {
          // Verifica se foi enviado o par�metro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
          // Este par�metro � enviado pela tela de filtragem das p�ginas gerenciais
          if ($SG=='SRTRANSP') $w_texto = f($row,'justificativa'); else $w_texto = f($row,'descricao');
          if ($_REQUEST['p_tamanho']=='N') {
            ShowHTML('        <td>'.Nvl($w_texto,'-').'</td>');
          } else {
            if ($w_tipo!='WORD' && strlen(Nvl($w_texto,'-'))>50) $w_titulo = substr(Nvl($w_texto,'-'),0,50).'...'; else $w_titulo = Nvl($w_texto,'-');
            if (f($row,'sg_tramite')=='CA') {
              ShowHTML('        <td title="'.htmlspecialchars($w_texto).'"><strike>'.htmlspecialchars($w_titulo).'</strike></td>');
            } else {
              ShowHTML('        <td title="'.htmlspecialchars($w_texto).'">'.htmlspecialchars($w_titulo).'</td>');
            } 
          }
        } else {
          ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        } 
        if ($w_tipo!='WORD') {
          ShowHTML('        <td align="top" nowrap>');
          if ($P1!=3) {
            // Se n�o for acompanhamento
            if ($w_copia>'') {
              // Se for listagem para c�pia
              if ($w_submenu=='Existe') {
                $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
                foreach($RS1 as $row1) { 
                  ShowHTML('          <a class="HL" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
                  break;
                }
              } else {
                ShowHTML('          <a class="HL" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
              }
            } elseif ($P1==1) {
              // Se for cadastramento
              if ($w_submenu>'') {
                ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento=Nr. '.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'" title="Altera os dados da solicita��o" TARGET="menu">AL</a>&nbsp;');
              } else {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informa��es cadastrais do lan�amento">AL</A>&nbsp');
              } 
              ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclus�o da solicita��o.">EX</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lan�amento para outro respons�vel.">EN</A>&nbsp');
            } elseif ($P1==2 || $P1==6) {
              if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S' || f($row,'acesso')>15) {
                // Se for execu��o
                if (f($row,'sg_tramite')=='AT') {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'opiniao&R='.$w_pagina.$par.'&O=O&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite opini�o sobre o atendimento.">Opini�o</A>&nbsp');
                }
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicita��o para outro tr�mite.">EN</A>&nbsp');
                if (f($row,'sg_tramite')=='EE') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anota��es para o lan�amento, sem envi�-la.">AN</A>&nbsp');
                  if ($SG=='SRTRANSP') {
                    // link para informar o motorista e o carro
                    ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Informar&R='.$w_pagina.$par.'&O=F&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Informar dados do atendimento.">IN</A>&nbsp');
                  }
                  if (nvl(f($row,'emite_os'),'N')=='S') {
                    if ($SG=='SRTRANSP') {
                      // OS de transporte s� pode ser emitida ap�s informar ve�culo e motorista
                      if (nvl(f($row,'sq_veiculo'),'')=='') {
                        ShowHTML('          <A class="HL" onClick="alert(\'Antes de emitir a OS � necess�rio clicar na opera��o IN (informar)!\'); return false;" href="'.$w_dir.$w_pagina.'EmiteOS&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite Ordem de Servi�o." target="OS">OS</A>&nbsp');
                      } else {
                        ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'EmiteOS&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite Ordem de Servi�o." target="OS">OS</A>&nbsp');
                      }
                    } else {
                      ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'EmiteOS&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite Ordem de Servi�o." target="OS">OS</A>&nbsp');
                    }
                  }
                  ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=C&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execu��o da solicita��o.">CO</A>&nbsp');
                }
              } else {
                if (f($row,'sg_tramite')=='AT') {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'opiniao&R='.$w_pagina.$par.'&O=O&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite opini�o sobre o atendimento.">Opini�o</A>&nbsp');
                } else {
                  ShowHTML('          ---&nbsp');
                }
              } 
            } 
          } else {
            if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Envia a solicita��o para outro tr�mite.">EN</A>&nbsp');
            } else {
              ShowHTML('          ---&nbsp');
            } 
          } 
          ShowHTML('        </td>');
        } 
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
  } elseif (!(strpos('CP',$O)===false)) {
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar a solicita��o que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
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
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se n�o for cadastramento ou se for c�pia
      if ($P1==3) {
        ShowHTML('      <tr valign="top">');
        selecaoServico('<U>S</U>ervi�o:', 'S', null, $p_sq_menu, null, 'SR', 'p_sq_menu', null, null, null, null, null);
      }
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>N�mero <U>d</U>a solicita��o:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      if ($P1==3) {
        SelecaoUnidade('<U>S</U>etor executor:','S','Selecione a unidade respons�vel pelo servi�o na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      }
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Usu�rio solicita<u>n</u>te:','N','Selecione o solicitante na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor solicitante:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr>');
      SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Deta<U>l</U>hamento:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      ShowHTML('          <td valign="top"><b>Da<u>t</u>a da solicita��o entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
      if ($O!='C') {
        // Se n�o for c�pia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente solicita��es em atraso?</b><br>');
        if ($p_atraso=='S') {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> N�o');
        } else {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> N�o');
        } 
        if ($P1 != 3) {
          SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
        } else {
          selecaoOpiniao('Exibir somente opini�es do tipo:',null,null,$p_prioridade,$w_cliente,'p_prioridade',null,'SELECT');
        }
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_Ordena=='ASSUNTO') {
      ShowHTML('          <option value="assunto" SELECTED>Detalhamento<option value="inicio">In�cio previsto<option value="">T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='INICIO') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio" SELECTED>In�cio previsto<option value="">T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='NM_TRAMITE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">In�cio previsto<option value="">T�rmino previsto<option value="nm_tramite" SELECTED>Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='PRIORIDADE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">In�cio previsto<option value="">T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade" SELECTED>Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='PROPONENTE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">In�cio previsto<option value="">T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente" SELECTED>Proponente externo');
    } else {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">In�cio previsto<option value="" SELECTED>T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_dir.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Abandonar c�pia">');
    } else {
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_dir.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
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
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  if ($SG=='SRTRANSP') {
    include_once('transporte_gerais.php');
  } else {
    include_once('geral_gerais.php');
  }
} 

// =========================================================================
// Rotina de visualiza��o
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = strtoupper(trim($_REQUEST['w_tipo']));

  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  if ($w_tipo=='PDF') {
    ob_start();  
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('<link rel="stylesheet" type="text/css" href="' . $conRootSIW . '/classes/menu/xPandMenu.css">');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    CabecalhoWord($w_cliente,f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    if ($w_embed!='WORD') CabecalhoRelatorio($w_cliente,f($RS_Menu,'nome'),4,$w_chave);
    $w_embed = 'HTML';
  }
   if ($w_embed!='WORD') ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  // Chama a rotina de visualiza��o dos dados da solicita��o, na op��o 'Listagem'
  ShowHTML(VisualGeral($w_chave,'L',$w_usuario,$SG,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  if ($w_tipo=='PDF') RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
} 

// =========================================================================
// Rotina de emiss�o da ordem de servi�o
// -------------------------------------------------------------------------
function EmiteOS() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = strtoupper(trim($_REQUEST['w_tipo']));

  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Ordem de Servi�o</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpenClean('onLoad=\'this.focus()\'; ');
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
  ShowHTML('ORDEM DE SERVI�O');
  ShowHTML('<TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Emiss�o: '.DataHora().'</font></B></TD></TR>');
  ShowHTML('</B></TD></TR></TABLE>');
  // Chama a rotina de visualiza��o dos dados da solicita��o, na op��o 'Listagem'
  if ($SG=='SRTRANSP') {
    include_once('visualos_transp.php');
    ShowHTML(VisualOS($w_chave,$SG));
  } else {
    include_once('visualos.php');
    ShowHTML(VisualOS($w_chave,$SG));
  }
  Rodape();
} 

// =========================================================================
// Rotina de exclus�o
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao=$_REQUEST['w_observacao'];
  } 
  $RS = db_getRecurso::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_menu,null,null,null,null,null,'SERVICO');
  if (count($RS)) $w_exibe_recurso = true; else $w_exibe_recurso = false;
  if ($w_exibe_recurso) {
    $RS = db_getSolicRecursos::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_solic_recurso   = f($RS,'chave_aux');
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (strpos('E',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da solicita��o, na op��o 'Listagem'
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,$SG,null));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_solic_recurso" value="'.$w_solic_recurso.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
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
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
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
    $RS = db_getTramiteList::getInstanceOf($dbms,$w_tramite,'ANTERIOR',null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 

  // Se for envio, executa verifica��es nos dados da solicita��o
  if ($O=='V') $w_erro = ValidaGeral($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);

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
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,$SG,null));
  ShowHTML('<HR>');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || $w_ativo=='N' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
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
      SelecaoFase('<u>F</u>ase: (v�lido apenas se for devolu��o)','F','Se deseja devolver a solicita��o, selecione a fase para a qual deseja devolv�-la.',$w_novo_tramite,$w_novo_tramite,'w_novo_tramite','DEVOLUCAO',null);
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
    
    // Exibe mapa de aloca��o de ve�culos
    if ($SG=='SRTRANSP' && $w_sg_tramite=='EA') {
      include_once('visualmapaveiculo.php');
      ShowHTML(visualMapaVeiculo(null,$SG,null,null,'S',$w_chave,formataDataEdicao(nvl($w_inicio,$w_fim)),formataDataEdicao($w_fim),'MAPAFUTURO'));
    }

    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de registro da opini�o
// -------------------------------------------------------------------------
function Opiniao() {
  extract($GLOBALS);
  global $w_Disabled;

  $RS = db_getOpiniao::getInstanceOf($dbms,null,$w_cliente,null,null,null);
  if (count($RS)==0) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b><font color="RED">Aten��o: a tabela de opini�es dispon�veis n�o foi alimentada. Entre em contato com os gestores.</b></font><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
    exit;
  }

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  ShowHTML('  var i; ');
  ShowHTML('  var w_erro=true; ');
  ShowHTML('  var w_indice; ');
  ShowHTML('  for (i=0; i < theForm.w_opiniao.length; i++) {');
  ShowHTML('    if (theForm.w_opiniao[i].checked) { w_erro=false; w_indice = i; }');
  ShowHTML('  }');
  ShowHTML('  if (w_erro) {');
  ShowHTML('    alert(\'Voc� deve selecionar uma das opini�es!\'); ');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  if (theForm.w_opiniao[w_indice].value==\'IN\' && theForm.w_motivo.value==\'\') {');
  ShowHTML('    alert(\'Voc� deve informar o motivo da insatisfa��o!\'); ');
  ShowHTML('    theForm.w_motivo.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  if (theForm.w_opiniao[w_indice].value!=\'IN\' && theForm.w_motivo.value!=\'\') {');
  ShowHTML('    alert(\'O campo motivo deve ser informado apenas se voc� ficou insatisfeito com o atendimento!\'); ');
  ShowHTML('    theForm.w_motivo.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  Validate('w_motivo','Motivo da insatisfa��o','1','','6','1000','1','1');
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
  ShowHTML('<center>');
  // Chama a rotina de visualiza��o dos dados da solicita��o, na op��o 'Listagem'
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,$SG,null));
  ShowHTML('<HR>');
  ShowHTML('  <table width="97%" border="0" bgcolor="'.$conTrBgColor.'">');
  ShowHTML('    <tr><td align="justify"><font size="2">� importante para as �reas executoras saber sua opini�o sobre o atendimento desta solicita��o. Selecione uma das alternativas abaixo, informe sua assinatura e clique no bot�o <i>Gravar</i>.</font>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  selecaoOpiniao(null,null,null,null,$w_cliente,'w_opiniao',null,'CHECKBOX');
  ShowHTML('    <tr><td>');
  ShowHTML('      <tr><td><b><u><br>M</u>otivo da insatisfa��o: (apenas se ficou insatisfeito com o atendimento)</b><br><textarea '.$w_Disabled.' accesskey="M" name="w_motivo" class="STI" ROWS=5 cols=75 title="Descreva os motivos pelos quais voc� ficou insatisfeito com o atendimento.">'.$w_motivo.'</TEXTAREA></td>');
  ShowHTML('      <br>');
  ShowHTML('    </td></tr>');
  ShowHTML('    <tr><td><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center"><hr>');
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
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,$SG,null));
  ShowHTML('<HR>');
  ShowHTML('<FORM name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'&w_menu='.$w_menu.'">');
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
// Rotina de informa��es complementares da solicita��o
// -------------------------------------------------------------------------
function Informar() {
  extract($GLOBALS);
  if ($SG=='SRTRANSP') {
    include_once('transporte_inf.php');
  }
} 

// =========================================================================
// Rotina de conclus�o
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  if ($SG=='SRTRANSP') {
    include_once('transporte_conc.php');
  } else {
    include_once('geral_conc.php');
  }
} 

// =========================================================================
// Rotina de prepara��o para envio de e-mail
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
  $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,$SG);
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    // Recupera os dados da solicita��o
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    $w_html='<HTML>'.$crlf;
    $w_html.=BodyOpenMail(null).$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    $w_nome='Servi�o: '.f($RSM,'nome').' - Solicita��o '.f($RSM,'sq_siw_solicitacao');
    if ($p_tipo==1) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUS�O DE SOLICITA��O</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==2) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITA��O DE SOLICITA��O</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==3) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUS�O DE SOLICITA��O</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==4) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>COMUNICADO DE INSATISFA��O</b><br><br><td></tr>'.$crlf;
    } 
    //  $w_html.='      <tr valign="top"><td align="center"><font size=2><b>'.strtoupper($w_nome).'</b><br><br><td></tr>'.$crlf;
    if ($p_tipo==2) {
      // Tramita��o
      $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATEN��O: Esta solicita��o precisa da sua interven��o para ser atendida. Acesse o sistema e verifique o bloco de ocorr�ncias e anota��es.</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==3) {
      // Conclus�o
      $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATEN��O: Esta solicita��o foi conclu�da. Acesse o sistema e, na mesa de trabalho, informe sua opini�o sobre o atendimento.</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==4) {
      // Insatisfa��o
      $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATEN��O: O solicitante declarou-se insatisfeito com o atendimento. Verifique abaixo os motivos apontados.</b><br><br><td></tr>'.$crlf;
   } 
    $w_html.=$crlf.'<tr><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'       <table border=1 width="100%"><tr><td bgcolor="#FAEBD7">';
    $w_html.=$crlf.'         <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html.=$crlf.'           <tr valign="top">';
    $w_html.=$crlf.'             <td>Servi�o:<br><b>'.f($RSM,'nome').'</b></td>';
    $w_html.=$crlf.'             <td align="right">N:<br><b>'.f($RSM,'sq_siw_solicitacao').'</b></td>';
    $w_html.=$crlf.'           <tr valign="top">';
    $w_html.=$crlf.'             <td>Solicitante:<br><b>'.f($RSM,'nm_sol').'</b></td>';
    $w_html.=$crlf.'             <td align="right">Unidade solicitante:<br><b>'.f($RSM,'nm_unidade_solic').'</b></td>';
    $w_html.=$crlf.'         </table>';
    $w_html.=$crlf.'       </table>';
    // Identifica��o da solicita��o
    $w_html.=$crlf.'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';

    // Exibe as informa��es da data, conforme defini��o para o servi�o.
    $w_html.=$crlf.'          <tr valign="top">';
    switch (f($RS_Menu,'data_hora')) {
    case 1 :
      $w_html.=$crlf.'          <td WIDTH="30%"><b>Data programada:</b>';
      $w_html.=$crlf.'            <td>'.Nvl(FormataDataEdicao(f($RSM,'phpdt_fim')),'-').' </td>';
      break;
    case 2 :
      $w_html.=$crlf.'          <td WIDTH="30%"><b>Data programada:<b>';
      $w_html.=$crlf.'            <td>'.Nvl(substr(FormataDataEdicao(f($RSM,'phpdt_fim'),3),0,-3),'-').' </td>';
      break;
    case 3 :
      $w_html.=$crlf.'          <td WIDTH="30%"><b>In�cio:</b>';
      $w_html.=$crlf.'            <td>'.Nvl(FormataDataEdicao(f($RSM,'phpdt_inicio')),'-').' </td>';
      $w_html.=$crlf.'        <tr valign="top">';
      $w_html.=$crlf.'          <td><b>T�rmino:</b>';
      $w_html.=$crlf.'            <td>'.Nvl(FormataDataEdicao(f($RSM,'phpdt_fim')),'-').' </td>';
      break;
    case 4 :
      $w_html.=$crlf.'          <td WIDTH="30%"><b>In�cio:</b>';
      $w_html.=$crlf.'            <td>'.Nvl(substr(FormataDataEdicao(f($RSM,'phpdt_inicio'),3),0,-3),'-').' </td>';
      $w_html.=$crlf.'        <tr valign="top">';
      $w_html.=$crlf.'          <td><b>T�rmino:</b>';
      $w_html.=$crlf.'            <td>'.Nvl(substr(FormataDataEdicao(f($RSM,'phpdt_fim'),3),0,-3),'-').' </td>';
      break;
    } 
    if (nvl(f($RSM,'descricao'),'')!='') {
      $w_html.=$crlf.'      <tr><td><b>Detalhamento:</b> ';
      $w_html.=$crlf.'        <td>'.CRLF2BR(f($RSM,'descricao')).'</td></tr>';
    }
    if ($SG=='SRTRANSP') {
      $w_html.=$crlf.'      <tr><td><b>Destino:</b>';
      $w_html.=$crlf.'        <td>'.CRLF2BR(f($RSM,'destino')).'</td></tr>';
      $w_html.=$crlf.'      <tr><td><b>Qtd. Pessoas:</b> ';
      $w_html.=$crlf.'        <td>'.f($RSM,'qtd_pessoas').'</td>';
      $w_html.=$crlf.'      <tr><td><b>Carga: </b>';
      $w_html.=$crlf.'        <td>'.RetornaSimNao(f($RSM,'carga')).'</td></tr>';
    }
    if (nvl(f($RSM,'justificativa'),'')!='') {
      $w_html.=$crlf.'      <tr><td><b>Justificativa:</b> ';
      $w_html.=$crlf.'        <td>'.CRLF2BR(f($RSM,'justificativa')).'</td></tr>';
    }

    // Se for conclus�o, exibe.
    if (nvl(f($RSM,'conclusao'),'')!='') {
      $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUS�O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=$crlf.'   <tr valign="top"><td><b>Data de conclus�o:</b></font></td><td>'.FormataDataEdicao(substr(f($RSM,'phpdt_conclusao'),0,-3),3).'</font></td></tr>';
      $w_html.=$crlf.'   <tr><td><b>Unidade executora:</b></font></td>';
      $w_html.=$crlf.'       <td>'.f($RSM,'nm_unidade_exec').'</font></td></tr>';
      if ($SG=='SRTRANSP') {
        $w_html.=$crlf.'   <tr><td><b>Motorista:</b></font></td>';
        $w_html.=$crlf.'       <td>'.f($RSM,'nm_exec').'</font></td></tr>';
        $w_html.=$crlf.'   <tr><td><b>Ve�culo:</b></font></td>';
        $w_html.=$crlf.'       <td>'.f($RSM,'nm_placa').'</font></td></tr>';
        $w_html.=$crlf.'       <tr valign="top"><td><b>Data do atendimento:</td>';
        $w_html.=$crlf.'         <td>Sa�da: '.substr(FormataDataEdicao(f($RSM,'phpdt_horario_saida'),3),0,-3).'<br>Retorno: '.substr(FormataDataEdicao(f($RSM,'phpdt_horario_chegada'),3),0,-3).'<b></font></td></tr>';
        $w_html.=$crlf.'       <tr valign="top"><td><b>Hod�metro:</td>';
        $w_html.=$crlf.'         <td>Sa�da: '.f($RSM,'hodometro_saida').'<br>Retorno:'.f($RSM,'hodometro_chegada').'<b></font></td></tr>';
        $w_html.=$crlf.'       <tr><td><b>Parcial:</td>';
        $w_html.=$crlf.'     <td>'.RetornaSimNao(f($RSM,'parcial')).'</b></td></tr>';
        $w_html.=$crlf.'   <tr><td><b>Passageiro:</b></font></td>';
        $w_html.=$crlf.'       <td>'.f($RSM,'nm_recebedor').'</font></td></tr>';
      }
      // Se o servi�o pede justificativa, exibe.
      if (nvl(f($RSM,'nm_opiniao'),'')!='') {
        $w_html.=$crlf.'   <tr valign="top"><td><b>Opini�o:</b></font></td><td>'.nvl(f($RSM,'nm_opiniao'),'---').'</font></td></tr>';
      }
      if (nvl(f($RSM,'motivo_insatisfacao'),'')!='') {
        $w_html.=$crlf.'   <tr valign="top"><td><b>Motivo da insatisfa��o:</b></font></td><td><font size=2 color="red">'.crlf2br(nvl(f($RSM,'motivo_insatisfacao'),'---')).'</font></td></tr>';
      }
    } 
    $w_html.=$crlf.'      </table>';
    $w_html.=$crlf.'      </tr>';
    
    //Recupera o �ltimo log
    $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc');
    foreach ($RS as $row) { $RS = $row; if(strpos(f($row,'despacho'),'*** Nova vers�o')===false) break; }
    $w_data_encaminhamento = f($RS,'phpdt_data');
    // Exibe dados da ocorr�ncia
    $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>�LTIMO ENCAMINHAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>Respons�vel: <b>'.f($RS,'responsavel').'</b></td>';
    $w_html.=$crlf.'          <tr><td>Ocorr�ncia:<ul>';
    if ($p_tipo==3) {
      $w_html.=$crlf.'            <li><b>Comunicado de conclus�o</b>';
    } elseif ($p_tipo==4) {
      $w_html.=$crlf.'            <li><b>Comunicado de insatisfa��o</b>';
    } else {
      $w_html.=$crlf.'            <li><b>'.CRLF2BR(f($RS,'observacao')).' </b>';
    }
    $w_html.=$crlf.'            <li><b>Respons�vel: </b>'.$_SESSION['NOME'].'</li>'.$crlf;
    $w_html.=$crlf.'            <li><b>Data: </b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</li>'.$crlf;
    $w_html.=$crlf.'            <li><b>IP de origem: </b>'.$_SERVER['REMOTE_ADDR'].'</li>'.$crlf;
    $w_html.=$crlf.'            </ul>'.$crlf;
    $w_html.=$crlf.'          </table>';
    $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRAS INFOMA��ES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $RS = db_getCustomerSite::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    $w_html.='      <tr valign="top"><td>'.$crlf;
    $w_html.='         Para acessar o sistema use o endere�o: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='    </table>'.$crlf;
    $w_html.='</td></tr>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</BODY>'.$crlf;
    $w_html.='</HTML>'.$crlf;

   // Configura os destinat�rios da mensage m

    if ($p_tipo==2) {
      // Se for tramita��o, envia e-mail para os respons�veis pelo seu cumprimento
      $RS = db_getTramiteResp::getInstanceOf($dbms,$p_solic,null,null);
      if (count($RS)>0) {
        foreach($RS as $row) {
          $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
        } 
      } 
    } elseif ($p_tipo==3) {
      if(f($RSM,'st_sol')=='S') {
        // Se for conclus�o, envia e-mail ao solicitante comunicando a necessidade de informar sua opini�o
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
        $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
      }
    } elseif ($p_tipo==4) {
      // Se for comunicado de insatisfa��o, envia e-mail para os respons�veis pelo cumprimento do tr�mite "Em execu��o".
      $RS = db_getTramiteList::getInstanceOf($dbms,f($RSM,'sq_siw_tramite'),'ANTERIOR',null);
      foreach($RS as $row) { $RS = $row; break; }
      $RS = db_getTramiteResp::getInstanceOf($dbms,$p_solic,f($RS,'sq_siw_tramite'),null);
      if (count($RS)>0) {
        foreach($RS as $row) {
          $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
        } 
      } 
    }
    // Prepara os dados necess�rios ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclus�o ou Conclus�o
      if ($p_tipo==1) $w_assunto='Inclus�o - '.$w_nome; else $w_assunto='Conclus�o - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramita��o
      $w_assunto='Tramita��o - '.$w_nome;
    } elseif ($p_tipo==4) {
      // Comunicado de insatisfa��o
      $w_assunto='Comunicado de insatisfa��o - '.$w_nome;
    } 
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado=EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n'.$w_resultado.'\');');
      ScriptClose();
    }
  }
} 

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  $w_file    = '';
  $w_tamanho = '';
  $w_tipo    = '';
  $w_nome    = '';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpenClean('onLoad=this.focus();');
  if (strpos('IAE',$O)!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se for opera��o de exclus�o, verifica se � necess�rio excluir os arquivos f�sicos
      if ($O=='E') {
        $RS = db_getSolicLog::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e n�o exclu�da.
        // Nessa situa��o, n�o � necess�rio excluir os arquivos.
        if (count($RS)<=1) {
          $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
          foreach($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          } 
        } 
      } 
      if ($SG=='SRTRANSP') {
        include_once($w_dir_volta.'classes/sp/dml_putSolicTransp.php');
        dml_putSolicTransp::getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],
            $_SESSION['SQ_PESSOA'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],
            $_REQUEST['w_data_hora'], $_REQUEST['w_cidade'], $_REQUEST['w_destino'],$_REQUEST['w_sq_veiculo'],$_REQUEST['w_qtd_pessoas'],
            $_REQUEST['w_procedimento'], $_REQUEST['w_carga'], &$w_chave_nova, $w_copia);
      } else {
        include_once($w_dir_volta.'classes/sp/dml_putSolicGeral.php');
        if (nvl($_REQUEST['w_solic_recurso'],'')!='' && $O=='E') {
          // Grava o cabe�alho da aloca��o
          dml_putSolicRecurso::getInstanceOf($dbms,$O,$w_usuario, $w_chave_nova, $_REQUEST['w_solic_recurso'],
                '0',$_REQUEST['w_recurso'],nvl($_REQUEST['w_descricao'],$_REQUEST['w_justificativa']), null, null, null);
        }
        dml_putSolicGeral::getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],
            $_SESSION['SQ_PESSOA'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],
            $_REQUEST['w_data_hora'], $_REQUEST['w_cidade'], &$w_chave_nova, $w_copia);
        
        if (nvl($_REQUEST['w_recurso'],'')!='') {
          // Grava o cabe�alho da aloca��o
          dml_putSolicRecurso::getInstanceOf($dbms,$O,$w_usuario, $w_chave_nova, $_REQUEST['w_solic_recurso'],
                '0',$_REQUEST['w_recurso'],nvl($_REQUEST['w_descricao'],$_REQUEST['w_justificativa']), null, null, null);
        }
      }

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='F' && $SG=='SRTRANSP') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {

      include_once($w_dir_volta.'classes/sp/dml_putSolicInfTransp.php');
      dml_putSolicInfTransp::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,
            $_REQUEST['w_executor'],$_REQUEST['w_sq_veiculo']);

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='O') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Verifica se outro usu�rio j� emitiu opini�o sobre o atendimento
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (nvl(f($RS,'opiniao'),'')!='') {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� emitiu opini�o sobre este atendimento!\');');
        ScriptClose();
      } else {
        // Recupera a chave da opini�o emitida
        $RS = db_getOpiniao::getInstanceOf($dbms,null,$w_cliente,null,$_REQUEST['w_opiniao'],$restricao);
        foreach($RS as $row) { $RS = $row; break; }

        // Grava a opini�o do solicitante
        dml_putSolicOpiniao::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS,'chave'),$_REQUEST['w_motivo']);
        
        // Se o solicitante ficou insatisfeito, envia e-mail para a �rea respons�vel pelo atendimento.
        if ($_REQUEST['w_opiniao']=='IN') SolicMail($_REQUEST['w_chave'],4);
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='V') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ((false!==(strpos(strtoupper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(strtoupper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
        // Verifica se outro usu�rio j� enviou a solicita��o
        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� encaminhou a solicita��o para outra fase!\');');
          ScriptClose();
          retornaFormulario('w_observacao');
          exit();
        } else {
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
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
            ScriptClose();
          } 
          ScriptOpen('JavaScript');
          // Volta para a listagem 
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } 
      } else {
        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� encaminhou a solicita��o para outra fase!\');');
          ScriptClose();
          retornaFormulario('w_observacao');
          exit();
        } else {
          // Verifica o pr�ximo tr�mite
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
            ShowHTML('  alert(\'ATEN��O: N�o h� nenhuma pessoa habilitada a cumprir o tr�mite "'.f($RS,'nome').'"!\');');
            ScriptClose();
            retornaFormulario('w_assinatura');
            exit();
          } 
          if ($_REQUEST['w_envio']=='N') {
            dml_putSolicEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
              $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
          } else {
            dml_putSolicEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
              $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
          } 
          // Envia mail avisando sobre a tramita��o da solicita��o
          SolicMail($_REQUEST['w_chave'],2);
          
          // Volta para a listagem 
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } 
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='C') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� encaminhou esta solicita��o para outra fase de execu��o!\');');
        ScriptClose();
      } else {
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
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
          retornaFormulario('w_observacao');
          exit();
        } 
        if ($SG=='SRTRANSP') {
          include_once($w_dir_volta.'classes/sp/dml_putSolicConcTransp.php');
          dml_putSolicConcTransp::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_recebedor'],$_REQUEST['w_tramite'],$_REQUEST['w_executor'],$_REQUEST['w_observacao'],$_REQUEST['w_valor'],
              $w_file,$w_tamanho,$w_tipo,$w_nome,$_REQUEST['w_sq_veiculo'], $_REQUEST['w_hodometro_saida'], $_REQUEST['w_hodometro_chegada'], $_REQUEST['w_horario_saida'], $_REQUEST['w_horario_chegada'], $_REQUEST['w_parcial']);
        } else {
          include_once($w_dir_volta.'classes/sp/dml_putSolicConc.php');
          dml_putSolicConc::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_fim'],$_REQUEST['w_executor'],$_REQUEST['w_observacao'],$_REQUEST['w_valor'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
        }
        // Envia e-mail comunicando a conclus�o
        SolicMail($_REQUEST['w_chave'],3);
        ScriptOpen('JavaScript');
        // Volta para a listagem
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Opera��o n�o prevista: '.nvl($O,'nulo').'\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  if ($P1==1 && nvl(f($RS_Menu,'sq_unid_executora'),'')=='') {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b><font color="RED">Aten��o: unidade executora do servi�o n�o informada. Entre em contato com os gestores.</b></font><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
    exit;
  }
  switch ($par) {
  case 'INICIAL':       Inicial();        break;
  case 'GERAL':         Geral();          break;
  case 'VISUAL':        Visual();         break;
  case 'EXCLUIR':       Excluir();        break;
  case 'OPINIAO':       Opiniao();        break;
  case 'ENVIO':         Encaminhamento(); break;
  case 'ANOTACAO':      Anotar();         break;
  case 'EMITEOS':       EmiteOS();        break;
  case 'INFORMAR':      Informar();       break;
  case 'CONCLUIR':      Concluir();       break;
  case 'GRAVA':         Grava();          break;
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


