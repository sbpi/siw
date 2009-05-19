<?php
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
include_once($w_dir_volta.'classes/sp/db_getSolicEV.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteResp.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteSolic.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'funcoes/selecaoTipoEvento.php');
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
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoPrograma.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEvento.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once('visualevento.php');
include_once('validaevento.php');

// =========================================================================
//  /evento.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o cadastro de eventos
// Mail     : alex@sbpi.com.br
// Criacao  : 22/03/2009 12:25
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = C   : Conclusão
//                   = P   : Pesquisa


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
$w_pagina       = 'evento.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'cl_pitce/';
$w_troca        = $_REQUEST['w_troca'];

// Se for acompanhamento, entra na filtragem  
if (nvl($O,'')=='') {
  if ($P1==3) $O = 'P'; else $O = 'L';
}

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.(($P1==1) ? ' - Cópia' : ' - Conclusão');       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Herança';     break;
  case 'F': $w_TP=$TP.' - Informações'; break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
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

  $w_tipo=$_REQUEST['w_tipo'];
  if ($O=='L') {
    if (($P1==3) || ($w_tipo=='WORD')) {
      $w_filtro='';
      if ($p_sq_menu>'') {
        $RS = db_getMenuData::getInstanceOf($dbms,$p_sq_menu);
        $w_filtro .= '<tr valign="top"><td align="right">Serviço <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') $w_filtro .= '<tr valign="top"><td align="right">Demanda nº <td>[<b>'.$p_chave.'</b>]';
      if ($p_prazo>'') $w_filtro .= ' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
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
      if ($p_prioridade>'') {
        $RS = db_getOpiniao::getInstanceOf($dbms,null,$w_cliente,null,$p_prioridade, null);
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro .= '<tr valign="top"><td align="right">Opinião <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_proponente>'') $w_filtro .= '<tr valign="top"><td align="right">Proponente <td>[<b>'.$p_proponente.'</b>]';
      if ($p_assunto>'')    $w_filtro .= '<tr valign="top"><td align="right">Detalhamento <td>[<b>'.$p_assunto.'</b>]';
      if ($p_palavra>'')    $w_filtro .= '<tr valign="top"><td align="right">Palavras-chave <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data programada <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
      if ($w_filtro>'')     $w_filtro  = '<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 

    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as solicitações visíveis pelo usuário
      $RS = db_getSolicEV::getInstanceOf($dbms, $w_cliente,
          (($P1==3) ? nvl($p_sq_menu,0) : f($RS,'sq_menu')),
          $w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, null, null, null, null, null);
    } else {
      $RS = db_getSolicEV::getInstanceOf($dbms, $w_cliente,
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
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($w_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf($w_TP,$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    ShowHTML('<HEAD>');
    Estrutura_CSS($w_cliente);
    if ($P1==2) ShowHTML ('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.MontaURL('MESA').'">');
    ShowHTML("<TITLE>".$conSgSistema." - Listagem de solicitações</TITLE>");
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ((strpos('CP',$O)!==false)) {
      if ($P1!=1 || $O=='C') {
        // Se não for cadastramento ou se for cópia
        Validate('p_chave','Número da solicitação','','','1','18','','0123456789');
        Validate('p_assunto','descricao','','','2','90','1','1');
        Validate('p_ini_i','Início pedido','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Fim pedido','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas do período ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Início pedido','<=','p_ini_f','Fim pedido');
        Validate('p_fim_i','Conclusão inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim_f','Conclusão final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas do período ou nenhuma delas!\');');
        ShowHTML('     theForm.p_fim_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_fim_i','Conclusão inicial','<=','p_fim_f','Conclusão final');
      } 
      Validate('P4','Linhas por página','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_troca>'') {
      // Se for recarga da página
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
    if($w_embed!='WORD') {
      if ((strpos(strtoupper($R),'GR_'))===false) {
        Estrutura_Texto_Abre();
      } else {
        CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
      }
    }
    if ($w_filtro>'') ShowHTML($w_filtro);
    
    if ($P1==1) ShowHTML('<div align="left"><table border=0><tr valign="top"><td><b>Finalidade:</b><td>'.f($RS_Menu,'finalidade').'</tr></table></div>');
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($P1==1 && $w_copia=='') {
      // Se for cadastramento e não for resultado de busca para cópia
      if ($w_embed!='WORD') { 
        ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;'); 
        ShowHTML('    <a accesskey="C" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
      }
    } 
    if ((strpos(strtoupper($R),'GR_')===false) && $P1!=6 && $w_embed!='WORD') {
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
    ShowHTML('    <td align="right">');
    if ((strpos(strtoupper($R),'GR_'))===false && $w_embed!='WORD') {
      ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('    <b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    if ($w_embed!='WORD') {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('Nº','sq_siw_solicitacao').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Data/período','phpdt_inicio').'</td>');
      ShowHTML('          <td><b>Horário</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_evento').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Assunto/tema','titulo').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Relevância','justificativa').'</td>');
      if ($P1!=1) ShowHTML('          <td><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      ShowHTML('          <td><b>Operações</td>');
    } else {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Nº</td>');
      ShowHTML('          <td><b>Data</td>');
      ShowHTML('          <td><b>Horário</td>');
      ShowHTML('          <td><b>Tipo</td>');
      ShowHTML('          <td><b>Solicitante</td>');
      ShowHTML('          <td><b>Detalhamento</td>');
      ShowHTML('          <td><b>Relevância</td>');
      if ($P1!=1) ShowHTML('          <td><b>Fase atual</td>');
    } 
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="'.(($P1==3) ? 8 : 7).'" align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      if ($w_embed!='WORD') $RS1 = array_slice($RS, (($P3-1)*$P4), $P4); else $RS1 = $RS;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),nvl(f($row,'phpdt_inicio'),f($row,'phpdt_inclusao')),f($row,'phpdt_programada'),f($row,'phpdt_inicio'),f($row,'phpdt_conclusao'),'S',addDays(f($row,'fim'),-1),f($row,'sg_tramite'), null));
        if ($w_embed!='WORD') {
          ShowHTML('        <A class="HL" href="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'</a>');
        } else {
          ShowHTML('        '.f($row,'sq_siw_solicitacao'));
        }
        if (FormataDataEdicao(f($row,'phpdt_inicio'))==FormataDataEdicao(f($row,'phpdt_fim'))) { 
          ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'phpdt_inicio'),5).'</td>');
        } else {
          ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'phpdt_inicio'),5).' a '.FormataDataEdicao(f($row,'phpdt_fim'),5).'</td>');
        }
        ShowHTML('        <td align="center">'.date('H:i',f($row,'phpdt_inicio')).'-'.date('H:i',f($row,'phpdt_fim')).'</td>');
        ShowHTML('        <td>'.f($row,'nm_tipo_evento').'</td>');
        // Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        // Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        $w_texto = f($row,'titulo');
        if ($_REQUEST['p_tamanho']=='N') {
          ShowHTML('        <td>'.Nvl($w_texto,'-').'</td>');
        } else {
          if ($w_embed!='WORD' && strlen(Nvl($w_texto,'-'))>50) $w_titulo = substr(Nvl($w_texto,'-'),0,50).'...'; else $w_titulo = Nvl($w_texto,'-');
          if (f($row,'sg_tramite')=='CA') {
            ShowHTML('        <td title="'.htmlspecialchars($w_texto).'"><strike>'.htmlspecialchars($w_titulo).'</strike></td>');
          } else {
            ShowHTML('        <td title="'.htmlspecialchars($w_texto).'">'.htmlspecialchars($w_titulo).'</td>');
          } 
        }
        ShowHTML('        <td title="'.htmlspecialchars($w_texto).'">'.htmlspecialchars(f($row,'justificativa')).'</td>');
        if ($P1!=1) ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        if ($w_embed!='WORD') {
          ShowHTML('        <td align="top" nowrap>');
          if ($P1!=3) {
            // Se não for acompanhamento
            if ($w_copia>'') {
              // Se for listagem para cópia
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
                ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento=Nr. '.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'" title="Altera os dados da solicitação" TARGET="menu">AL</a>&nbsp;');
              } else {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais do lançamento">AL</A>&nbsp');
              } 
              ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão da solicitação.">EX</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'ANEXO&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=EVANEXO" title="Atribui anexos ao item.">Arquivos</A>&nbsp');
            } elseif ($P1==2 || $P1==6) {
              if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S' || f($row,'acesso')>15) {
                // Se for execução
                if (f($row,'sg_tramite')=='AT') {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'opiniao&R='.$w_pagina.$par.'&O=O&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite opinião sobre o atendimento.">Opinião</A>&nbsp');
                }
                if (f($row,'sg_tramite')=='EA') {
                  if ($SG!='SRTRANSP') {
                    ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'DadosExecucao&R='.$w_pagina.$par.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=DADEXEC'.MontaFiltro('GET').'" title="Informar dados da execucao.">IN</A>&nbsp');
                  }
                }
                if (f($row,'sg_tramite')=='EE') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para o lançamento, sem enviá-la.">AN</A>&nbsp');
                  if ($SG=='SRTRANSP') {
                    // link para informar o motorista e o carro
                    ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Informar&R='.$w_pagina.$par.'&O=F&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Informar dados do atendimento.">IN</A>&nbsp');
                  }
                  if (nvl(f($row,'emite_os'),'N')=='S') {
                    if ($SG=='SRTRANSP') {
                      // OS de transporte só pode ser emitida após informar veículo e motorista
                      if (nvl(f($row,'sq_veiculo'),'')=='') {
                        ShowHTML('          <A class="HL" onClick="alert(\'Antes de emitir a OS é necessário clicar na operação IN (informar)!\'); return false;" href="'.$w_dir.$w_pagina.'EmiteOS&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite Ordem de Serviço." target="OS">OS</A>&nbsp');
                      } else {
                        ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'EmiteOS&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite Ordem de Serviço." target="OS">OS</A>&nbsp');
                      }
                    } else {
                      ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'EmiteOS&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite Ordem de Serviço." target="OS">OS</A>&nbsp');
                    }
                  }
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicitação para outro trâmite.">EN</A>&nbsp');
                  ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=C&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução da solicitação.">CO</A>&nbsp');
                } else {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicitação para outro trâmite.">EN</A>&nbsp');
                }
              } else {
                if (f($row,'sg_tramite')=='AT') {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'opiniao&R='.$w_pagina.$par.'&O=O&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite opinião sobre o atendimento.">Opinião</A>&nbsp');
                } else {
                  ShowHTML('          ---&nbsp');
                }
              } 
            } 
          } else {
            if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Envia a solicitação para outro trâmite.">EN</A>&nbsp');
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
    if ($w_embed!='WORD') {
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
      // Se for cópia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar a solicitação que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
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
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      if ($P1==3) {
        ShowHTML('      <tr valign="top">');
        selecaoServico('<U>S</U>erviço:', 'S', null, $p_sq_menu, null, 'SR', 'p_sq_menu', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_menu\'; document.Form.submit();"', null, null, null);
      }
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>Número <U>d</U>a solicitação:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      if ($P1==3) {
        SelecaoUnidade('<U>S</U>etor executor:','S','Selecione a unidade responsável pelo serviço na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      }
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Usuário solicita<u>n</u>te:','N','Selecione o solicitante na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor solicitante:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>Deta<U>l</U>hamento:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Da<u>t</u>a da solicitação entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
      ShowHTML('          <td valign="top"><b>Da<u>t</u>a da conclusão entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente solicitações em atraso?</b><br>');
        if ($p_atraso=='S') {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
        } else {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
        } 
        if ($P1 != 3) {
          SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
        } else {
          SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$p_sq_menu,'p_fase[]',null,null);
        }
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_Ordena=='ASSUNTO') {
      ShowHTML('          <option value="assunto" SELECTED>Detalhamento<option value="inicio">Início previsto<option value="">Término previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='INICIO') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio" SELECTED>Início previsto<option value="">Término previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='NM_TRAMITE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">Início previsto<option value="">Término previsto<option value="nm_tramite" SELECTED>Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='PRIORIDADE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">Início previsto<option value="">Término previsto<option value="nm_tramite">Fase atual<option value="prioridade" SELECTED>Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='PROPONENTE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">Início previsto<option value="">Término previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente" SELECTED>Proponente externo');
    } else {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">Início previsto<option value="" SELECTED>Término previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for cópia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar cópia">');
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
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  if($w_tipo == 'PDF') RodapePdf();
  else Rodape();
} 

// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  if ($P1==1 && $O=='I' &&f($RS_Menu,'envio_inclusao')=='S') {
    // Recupera a chave do trâmite de cadastramento
    $RS = db_getTramiteList::getInstanceOf($dbms,$w_menu,null,null);
    $RS = SortArray($RS,'ordem','asc');
    foreach($RS as $row) { 
      $w_tramite = f($row,'sq_siw_tramite'); 
      break; 
    }
    
    $w_envio_inclusao = 'S';
  } else {
    $w_envio_inclusao = 'N';
  }
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';
  $RS = db_getUorgData::getInstanceOf($dbms,$_SESSION['LOTACAO']);
  $w_nm_unidade = f($RS,'nome');
  
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    $w_sq_menu_relac            = $_REQUEST['w_sq_menu_relac'];    
    if($w_sq_menu_relac=='CLASSIF') {
      $w_solic_pai              = '';
    } else {
      $w_solic_pai              = $_REQUEST['w_solic_pai'];
    }
    // Se for recarga da página
    $w_pais          = $_REQUEST['w_pais'];
    $w_uf            = $_REQUEST['w_uf'];
    $w_cidade        = $_REQUEST['w_cidade'];
    $w_tipo_evento   = $_REQUEST['w_tipo_evento'];
    $w_programa      = $_REQUEST['w_programa'];
    $w_solic_pai     = $_REQUEST['w_solic_pai'];
    $w_indicador1    = $_REQUEST['w_indicador1'];
    $w_indicador2    = $_REQUEST['w_indicador2'];
    $w_indicador3    = $_REQUEST['w_indicador3'];
    $w_observacao    = $_REQUEST['w_observacao'];
    $w_titulo        = $_REQUEST['w_titulo'];
    $w_motivo        = $_REQUEST['w_motivo'];
    $w_data_inicio   = $_REQUEST['w_data_inicio'];
    $w_data_termino  = $_REQUEST['w_data_termino'];
    $w_hora_inicio   = $_REQUEST['w_hora_inicio'];
    $w_hora_termino  = $_REQUEST['w_hora_termino'];
    $w_justificativa = $_REQUEST['w_justificativa'];
    $w_descricao     = $_REQUEST['w_descricao'];
  } else {
    if ((strpos('AEV',$O)!==false) || $w_copia>'') {
      // Recupera os dados da solicitação
      $RS = db_getSolicEV::getInstanceOf($dbms, $w_cliente,$w_menu,$w_usuario,
          $SG,$P1,$p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          (($w_copia>'') ? $w_copia : $w_chave),
          $p_assunto, 
          $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,$p_uorg_resp, 
          $p_palavra, $p_prazo, $p_fase, null, null, null, null, null);
      if (count($RS)>0) {
        $RS = $RS[0];
        $w_programa         = f($RS,'sq_solic_avo');
        $w_plano            = f($RS,'sq_plano_avo');
        $w_solic_pai        = f($RS,'sq_solic_pai');
        $w_chave_pai        = f($RS,'sq_solic_pai');
        $w_sq_menu          = f($RS,'sq_menu');
        $w_tipo_evento      = f($RS,'sq_tipo_evento');
        $w_sq_unidade       = f($RS,'sq_unidade');
        $w_solicitante      = f($RS,'solicitante');
        $w_justificativa    = f($RS,'justificativa');
        $w_descricao        = f($RS,'descricao');
        $w_observacao       = f($RS,'observacao');
        $w_indicador1       = f($RS,'indicador1');
        $w_indicador2       = f($RS,'indicador2');
        $w_indicador3       = f($RS,'indicador3');
        $w_titulo           = f($RS,'titulo');
        $w_motivo           = f($RS,'motivo_insatisfacao');
        $w_pais             = f($RS,'sq_pais');
        $w_uf               = f($RS,'co_uf');
        $w_cidade           = f($RS,'sq_cidade_origem');
        $w_data_inicio      = formataDataEdicao(f($RS,'phpdt_inicio'));
        $w_data_termino     = formataDataEdicao(f($RS,'phpdt_fim'));
        $w_hora_inicio      = date('H:i',f($RS,'phpdt_inicio'));
        $w_hora_termino     = date('H:i',f($RS,'phpdt_fim'));
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataData();
  FormataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
    Validate('w_programa','Macroprograma','SELECT',1,1,18,'','0123456789');
  //if(Nvl($w_sq_menu_relac,'')!='') {
    Validate('w_solic_pai','Programa','SELECT',1,1,18,'','0123456789');
    //}    
    ShowHTML('  if (theForm.w_indicador1.checked || theForm.w_indicador2.checked || theForm.w_indicador3.checked) {');
    ShowHTML('  } else {');
    ShowHTML('     alert(\'Pelo menos um interessado deve ser selecionado.\');');
    ShowHTML('     return false;');
    ShowHTML('  }'); 
    Validate('w_observacao','Encaminhar para','','','2','1000','1','1');
    Validate('w_tipo_evento','Tipo de evento','SELECT',1,1,18,'','0123456789');
    Validate('w_titulo','Assunto/tema','',1,1,80,'1','1');  
    Validate('w_motivo','Local','',1,1,80,'1','1');  
    Validate('w_pais','País','SELECT',1,1,18,'','0123456789');
    Validate('w_uf','Estado','SELECT',1,1,3,'1','1');
    Validate('w_cidade','Cidade','SELECT',1,1,18,'','0123456789');
    Validate('w_data_inicio','Data de início','DATA','1',10,10,'','0123456789/');
    Validate('w_data_termino','Data de término','DATA','1',10,10,'','0123456789/');
    CompData('w_data_termino','Data de término','>=','w_data_inicio','Data de início');
    Validate('w_hora_inicio','Hora de início','HORA','1',5,5,'','0123456789:');
    Validate('w_hora_termino','Hora de término','HORA','1',5,5,'','0123456789:');
    ShowHTML('  if (theForm.w_data_termino.value == theForm.w_data_inicio.value) {');
    CompHora('w_hora_termino','Hora de término','>','w_hora_inicio','Hora de início');
    Validate('w_justificativa','Relevância para a PDP','',1,3,500,'1','1');
    Validate('w_descricao','Descrição','',1,3,500,'1','1');
    ShowHTML('  }');
    if ($w_envio_inclusao=='S') {
      Validate('w_assinatura','Assinatura Eletrônica','1','','6','30','1','1');
      ShowHTML('  if (theForm.w_envio.value=="S" && theForm.w_assinatura.value=="") {');
      ShowHTML('     alert("Para envio automático a assinatura eletrônica deve ser informada!");');
      ShowHTML('     theForm.w_assinatura.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
    }
  }
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  if ($w_envio_inclusao=='S')  ShowHTML('  theForm.btEnvio.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpenClean('onLoad=\'document.Form.$w_assinatura.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  if ($P1==1) ShowHTML('<div align="left"><table border=0><tr valign="top"><td><b>Finalidade:</b><td nowrap>'.f($RS_Menu,'finalidade').'</tr></table></div>');  
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    // if ($w_cidade=='') {
      // Carrega o valores padrão para cidade
      // $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      // $w_cidade   = f($RS,'sq_cidade_padrao');
    // }
    if ($w_pais=='') {
      // Carrega os valores padrão para país, estado e cidade
      $RS_Cliente = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_pais=f($RS_Cliente,'sq_pais');
      $w_uf=f($RS_Cliente,'co_uf');
      $w_cidade=f($RS_Cliente,'sq_cidade_padrao');
    }     
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') {
        $w_Erro = Validacao($w_sq_solicitacao,$SG);
      } 
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_envio" value="N">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_solicitante" value="'.$_SESSION['SQ_PESSOA'].'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <fieldset><table width="97%" border="0">');
    ShowHTML('          <tr valign="top">');
    SelecaoPrograma('Macrop<u>r</u>ograma:', 'R', 'Se desejar, selecione um dos programas.', $w_programa, $w_plano, $w_objetivo, 'w_programa', $w_sq_menu_relac, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.target=\'\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_solic_pai\'; document.Form.submit();"',1,1,'<td colspan="3">');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    $RS = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PJCAD');
    $w_sq_menu_relac = f($RS,'sq_menu');
    ShowHTML('<INPUT type="hidden" name="w_sq_menu_relac" value="'.$w_sq_menu_relac.'">');
    ShowHTML('   <tr>');
    $RS = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PJCAD');
    SelecaoProjeto('<u>P</u>rograma', 'P', 'Selecione um item na relação.', $w_solic_pai, $w_usuario, f($RS, 'sq_menu'), $w_programa, null, null, 'w_solic_pai', 'PJLIST', null, 1, null, '<td colspan="3">');
    ShowHTML('   </tr>');
    if (nvl($w_solic_pai,'')!='') {
      // Recupera os dados do projeto
      $RS = db_getSolicData::getInstanceOf($dbms,$w_solic_pai,f($RS,'sigla'));
      ShowHTML('   <tr><td><b>Órgão responsável: </b></td><td colspan="3">'.ExibeUnidade(null,$w_cliente,f($RS,'sg_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</td></tr>');
      ShowHTML('   <tr><td><b>Gestor: </b></td><td colspan="3"> '.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')). '</td></tr>');
    }
    ShowHTML('   <tr><td>&nbsp;</td></tr>');
    SelecaoTipoEvento('<u>T</u>ipo de evento:','T','Selecione o tipo de evento.',$w_tipo_evento,f($RS_Menu,'sq_menu'),'w_tipo_evento',null,null,null,'<td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr><td><b>Interessados:</b></td><td colspan="3">');
    ShowHTML('       <input '.$w_Disabled.' name="w_indicador1" type="checkbox" class="STC" value="S" '.((nvl($w_indicador1,'')=='S') ? 'checked' : '').' /> Secretaria Executiva');
    ShowHTML('       <input '.$w_Disabled.' name="w_indicador2" type="checkbox" class="STC" value="S" '.((nvl($w_indicador2,'')=='S') ? 'checked' : '').' /> Coordenação');
    ShowHTML('       <input '.$w_Disabled.' name="w_indicador3" type="checkbox" class="STC" value="S" '.((nvl($w_indicador3,'')=='S') ? 'checked' : '').' /> Comitê Executivo');
    ShowHTML('       </td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr valign="top"><td><b>E<u>n</u>caminhar também para:</b><td colspan="3"><textarea '.$w_Disabled.' class="STI" name="w_observacao" cols="80" rows="2">'.$w_observacao.'</textarea></td></tr>');
    ShowHTML('   <tr><td><b>Assunto/tema:</b><td colspan="3"><input '.$w_Disabled.' name="w_titulo" type="text" class="STI" size="80" maxlength="80" value="'.$w_titulo.'" /></td></tr>');
    ShowHTML('   <tr valign="top"><td><b><u>L</u>ocal:</b><td colspan="3"><textarea '.$w_Disabled.' class="STI" name="w_motivo" cols="80" rows="2">'.$w_motivo.'</textarea></td></tr>');
    //SelecaoPais('<u>C</u>idade:','C',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"',1,'<td>');
    ShowHTML('<INPUT type="hidden" name="w_pais" value="'.$w_pais.'">'); 
    ShowHTML('   <tr>');
    SelecaoEstado('<u>C</u>idade',null,null,$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"',null,'<td>');
    SelecaoCidade(null,null,null,$w_cidade,$w_pais,$w_uf,'w_cidade',null,null,null,'<td>');
    ShowHTML('      </tr>');
    ShowHTML('   <tr><td><b>Data/período:</b><td colspan="3">');
    ShowHTML('       <input '.$w_Disabled.' name="w_data_inicio" type="text" class="STI" size="10" maxlength="10" value="'.$w_data_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" />');
    ShowHTML('       &nbsp;&nbsp;a&nbsp;&nbsp;<input '.$w_Disabled.' name="w_data_termino" type="text" class="STI" maxlength="10"  size="10" value="'.$w_data_termino.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" /></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr><td><b>Horário:</b><td colspan="3">');
    ShowHTML('       <input '.$w_Disabled.' name="w_hora_inicio" type="text" class="STI" size="5" maxlength="5" value="'.$w_hora_inicio.'" onKeyDown="FormataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,5,event);" />');
    ShowHTML('       &nbsp;&nbsp;a&nbsp;&nbsp;<input '.$w_Disabled.' name="w_hora_termino" type="text" class="STI" size="5" maxlength="5" value="'.$w_hora_termino.'" onKeyDown="FormataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,5,event);" /></td>');
    ShowHTML('   </tr>');
    if(nvl($w_justificativa,'') != ''){
      ShowHTML('   <tr valign="top"><td><b>Relevância para a PDP:</b><td colspan="3"><textarea '.$w_Disabled.' class="STI" name="w_justificativa" cols="80" rows="2" >'.$w_justificativa.'</textarea></td></tr>');    
    }else{
      ShowHTML('   <tr valign="top"><td><b>Relevância para a PDP:</b><td colspan="3"><textarea '.$w_Disabled.' class="STI" name="w_justificativa" cols="80" rows="2" >descrever a justificativa para vinculação com a PDP</textarea></td></tr>');        
    }    
    ShowHTML('   <tr valign="top"><td><b>Descrição:</b><td colspan="3"><textarea '.$w_Disabled.' class="STI" name="w_descricao" cols="80" rows="5" >'.$w_descricao.'</textarea></td></tr>');
    if ($w_envio_inclusao=='S') ShowHTML('      <tr valign="top"><td><b><U>A</U>ssinatura Eletrônica:</b><br>(apenas para envio automático)<td colspan="3"><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="4" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td><td colspan="4">');
    if ($w_envio_inclusao=='N') {
      ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    } else {
      ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar" onClick="document.Form.w_envio.value=\'N\';">');
      ShowHTML('            <input class="STB" type="submit" name="btEnvio" value="Gravar e Enviar" onClick="document.Form.w_envio.value=\'S\'; confirm(\'Confirma gravação e envio automático da solicitação para a próxima fase?\nSe sim, a assinatura eletrônica deve ser informada.\');">');
    }
    $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML(' </form>');
    ShowHTML(' </table>');
    ShowHTML(' </fieldset>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
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

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = strtoupper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0);
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    $w_embed = 'WORD';
  }  elseif($w_tipo == 'PDF') {
    headerPdf('Visualização de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);  
  } 
  
  if ($w_embed!='WORD') ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualEvento($w_chave,'L',$w_usuario,$SG,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  if ($w_tipo=='PDF')      RodapePDF();
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
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao=$_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (strpos('E',$O)!==false) {
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
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualEvento($w_chave,'V',$w_usuario,$SG,null));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
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

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $RS = db_getTramiteData::getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');

  if ($w_sg_tramite!='CI') {
    //Verifica a fase anterior para a caixa de seleção da fase.
    $RS = db_getTramiteList::getInstanceOf($dbms,$w_tramite,'ANTERIOR',null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 

  // Se for envio, executa verificações nos dados da solicitação
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
        ShowHTML('     alert(\'Informe o despacho apenas se for devolução para a fase anterior!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_envio[1].checked && theForm.w_despacho.value==\'\') {');
        ShowHTML('     alert(\'Informe um despacho descrevendo o motivo da devolução!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
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
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualEvento($w_chave,'V',$w_usuario,$SG,null));
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
        ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr>');
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
      }
    } else {
      ShowHTML('    <tr><td><b>Tipo do Encaminhamento</b><br>');
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE' || $w_ativo=='N') {
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
      SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_novo_tramite,'w_novo_tramite','DEVOLUCAO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolução):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a PCD.">'.$w_despacho.'</TEXTAREA></td>');
      if (!(substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE' || $w_ativo=='N')) {
        if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
          if (addDays($w_inicio,-$w_prazo)<addDays(time(),-1)) {
            ShowHTML('    <tr><td><b><u>J</u>ustificativa para não cumprimento do prazo regulamentar de '.$w_prazo.' dias:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o início da viagem for anterior a '.FormataDataEdicao(addDays(time(),$w_prazo)).', justifique o motivo do não cumprimento do prazo regulamentar para o pedido.">'.$w_justificativa.'</TEXTAREA></td>');
          } 
        } 
      } 
      ShowHTML('      </table>');
      ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
    } 
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    
    // Exibe mapa de alocação de veículos
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
// Rotina de anotação
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
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
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_observacao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualEvento($w_chave,'V',$w_usuario,$SG,null));
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
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</td>');
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
// Rotina de informações complementares da solicitação
// -------------------------------------------------------------------------
function Informar() {
  extract($GLOBALS);
  if ($SG=='SRTRANSP') {
    include_once('transporte_inf.php');
  }
} 

// =========================================================================
// Rotina de dados adicionais
// -------------------------------------------------------------------------
function DadosExecucao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave   = $_REQUEST['w_chave'];
  $w_sq_menu = $_REQUEST['w_sq_menu'];

  $w_readonly       = '';
  $w_erro           = '';

  // Recupera os dados da solicitação
  $RS_Solic = db_getSolicData::getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_valor            = $_REQUEST['w_valor'];
  } else {
    $w_inicio           = FormataDataEdicao(f($RS_Solic,'inicio'));
    $w_fim              = FormataDataEdicao(f($RS_Solic,'fim'));
    $w_valor            = ((nvl(f($RS_Solic,'valor'),'')!='') ? formatNumber(f($RS_Solic,'valor')) : ''); 
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
  FormataDataHora();
  FormataValor();  
  ValidateOpen('Validacao');
  switch (f($RS_Menu,'data_hora')) {
    case 0: Validate('w_fim','Término previsto','DATA',1,10,10,'','0123456789/');        break;
    case 1: Validate('w_fim','Término previsto','DATA',1,10,10,'','0123456789/');        break;
    case 2: Validate('w_fim','Término previsto','DATAHORA',1,17,17,'','0123456789/');    break;
    case 3: 
      Validate('w_inicio','Início previsto','DATA',1,10,10,'','0123456789/');       
      Validate('w_fim','Término previsto','DATA',1,10,10,'','0123456789/');
      CompData('w_inicio','Início previsto','<=','w_fim','Término previsto');
      break;
  case 4:
      Validate('w_inicio','Início previsto','DATAHORA',1,17,17,'','0123456789/,: ');
      Validate('w_fim','Término previsto','DATAHORA',1,17,17,'','0123456789/,: ');
      CompData('w_inicio','Início previsto','<=','w_fim','Término previsto');
      break;
  } 
  Validate('w_valor','Valor previsto','VALOR','',4,18,'','0123456789.,');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  switch (f($RS_Menu,'data_hora')) {
    case 0: BodyOpenClean('onLoad=\'document.Form.w_fim.focus()\';');    break;
    case 1: BodyOpenClean('onLoad=\'document.Form.w_fim.focus()\';');    break;
    case 2: BodyOpenClean('onLoad=\'document.Form.w_fim.focus()\';');    break;
    case 3: BodyOpenClean('onLoad=\'document.Form.w_inicio.focus()\';'); break;
    case 4: BodyOpenClean('onLoad=\'document.Form.w_inicio.focus()\';'); break;
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Exibe os dados da solicitação
  ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=3><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td><table border=0 width="100%">');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>'.f($RS_Menu,'nome').':<b><br>'.f($RS_Solic,'sq_siw_solicitacao').'</td>');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_sol')).'</td>');
  ShowHTML('            <td>Setor solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_solic'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('          <tr><td colspan="2">Descrição:<b><br>'.f($RS_Solic,'descricao').'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,'F');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  
  ShowHTML('<tr><td>');
  ShowHTML('    <tr><td><table width="100%" border="0" bgcolor="'.$conTrBgColor.'">');
  ShowHTML('      <tr><td colspan="3"><font size="2"><b>DADOS DA EXECUÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
  ShowHTML('      <tr valign="top">');
  switch (f($RS_Menu,'data_hora')) {
    case 0: ShowHTML('              <td><b><u>T</u>érmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data limite para que a execução da demanda esteja concluída.">'.ExibeCalendario('Form','w_fim').'</td>');           break;
    case 1: ShowHTML('              <td><b><u>T</u>érmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data limite para que a execução da demanda esteja concluída.">'.ExibeCalendario('Form','w_fim').'</td>');           break;
    case 2: ShowHTML('              <td><b><u>T</u>érmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Data/hora limite para que a execução da demanda esteja concluída.">'.ExibeCalendario('Form','w_fim').'</td>');  break;
    case 3: 
      ShowHTML('              <td><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Início previsto da demanda.">'.ExibeCalendario('Form','w_inicio').'</td>'); 
      ShowHTML('              <td><b><u>T</u>érmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data limite para que a execução da demanda esteja concluída.">'.ExibeCalendario('Form','w_fim').'</td>');
      break;
    case 4:
      ShowHTML('              <td><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Data/hora de início previsto da demanda.">'.ExibeCalendario('Form','w_inicio').'</td>');
      ShowHTML('              <td><b><u>T</u>érmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="Data/hora limite para que a execução da demanda esteja concluída.">'.ExibeCalendario('Form','w_fim').'</td>');
      break;
  } 
  ShowHTML('              <td><b>Valo<u>r</u> previsto:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o orçamento disponível para execução da demanda, ou zero se não for o caso."></td>');
  ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('      <tr><td align="center" colspan="2">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina de conclusão
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  if ($SG=='SRTRANSP') {
    include_once('transporte_conc.php');
  } else {
    include_once('geral_conc.php');
  }
} 

function Anexos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_menu       = $_REQUEST['w_menu'];
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endereço informado 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach ($RS as $row) {
      $w_nome      = f($row,'nome');
      $w_descricao = f($row,'descricao');
      $w_caminho   = f($row,'chave_aux');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_nome','Título','1','1','1','255','1','1');
      Validate('w_descricao','Descrição','1','1','1','1000','1','1');
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
  if ($O=='L') {
    //Recupera os dados do endereço para exibição no cabeçalho
    //$RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJCAD');
    $RS_Evento = db_getSolicEV::getInstanceOf($dbms, $w_cliente,$w_menu,$w_usuario,
      'EVCAD',5,null,null,null,null,null,null,null,null,null,null,$w_chave, null, 
      null, null, null, null, null,null, null, null, null, null, null, null, null, null);
    foreach($RS_Evento as $row){$RS_Evento = $row; break;}
  
    $w_cabecalho.='<table border=1 width="100%"><tr><td bgcolor="#FAEBD7">';
    $w_cabecalho.='    <TABLE WIDTH="90%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_cabecalho.='        <tr valign="top">';
    $w_cabecalho.='          <td><font size="1">Data/Período:<br/><font size=1 class="hl">'.f($RS_Evento,'codigo_interno').'</font></b>';
    if (FormataDataEdicao(f($row,'phpdt_inicio'))==FormataDataEdicao(f($RS_Evento,'phpdt_fim'))) { 
      $w_cabecalho.='          <b>'.FormataDataEdicao(f($RS_Evento,'phpdt_inicio'),5).'</b></td>';
    } else {
      $w_cabecalho.='          <b>'.FormataDataEdicao(f($RS_Evento,'phpdt_inicio'),5).' a '.FormataDataEdicao(f($RS_Evento,'phpdt_fim'),5).'</b></td>';
    }
    $w_cabecalho.='          <td><font size="1">Horário:<br><b><font size=1 class="hl">';
    $w_cabecalho.='          <b>'.date('H:i',f($RS_Evento,'phpdt_inicio')).'-'.date('H:i',f($RS_Evento,'phpdt_fim')).'</b></td>';
    //$w_cabecalho.='        <tr valign="top">';
    $w_cabecalho.='          <td><font size="1">Tipo:<br>';
    $w_cabecalho.='          <b>'.f($RS_Evento,'nm_tipo_evento').'</b></td>';
    $w_cabecalho.='          <td><font size="1">Assunto/Tema:<br><b><font size=1 class="hl">'.f($RS_Evento,'descricao').'</font></b></td>';
    $w_cabecalho.='    </TABLE>';
    $w_cabecalho.='</TABLE><BR>';
    echo $w_cabecalho;
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
  //ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');    
    ShowHTML('<tr><td>');
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('<a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.'inicial&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=EVCAD"><u>V</u>oltar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Título</td>');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
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
        ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'" name="Form" onSubmit="return(Validacao(this)); alert(\'!\');" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_caminho.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I' || $O=='A') {
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</font></b>.</td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    }  
    ShowHTML('      <tr><td><b><u>T</u>ítulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="OBRIGATÓRIO. Informe um título para o arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="OBRIGATÓRIO. Descreva a finalidade do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    if ($w_caminho>'') {
      ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
    } 
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_menu='.$w_menu.'&O=L&SG='.$SG.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);' 
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}


// =========================================================================
// Rotina de preparação para envio de e-mail
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
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,$SG);
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    // Recupera os dados da solicitação
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    $w_html='<HTML>'.$crlf;
    $w_html.=BodyOpenMail(null).$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    $w_nome='Serviço: '.f($RSM,'nome').' - Solicitação '.f($RSM,'sq_siw_solicitacao');
    if ($p_tipo==1) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE SOLICITAÇÃO</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==2) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITAÇÃO DE SOLICITAÇÃO</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==3) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUSÃO DE SOLICITAÇÃO</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==4) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>COMUNICADO DE INSATISFAÇÃO</b><br><br><td></tr>'.$crlf;
    } 
    //  $w_html.='      <tr valign="top"><td align="center"><font size=2><b>'.strtoupper($w_nome).'</b><br><br><td></tr>'.$crlf;
    if ($p_tipo==2) {
      // Tramitação
      $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO: Esta solicitação precisa da sua intervenção para ser atendida. Acesse o sistema e verifique o bloco de ocorrências e anotações.</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==3) {
      // Conclusão
      $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO: Esta solicitação foi concluída. Acesse o sistema e, na mesa de trabalho, informe sua opinião sobre o atendimento.</b><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==4) {
      // Insatisfação
      $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO: O solicitante declarou-se insatisfeito com o atendimento. Verifique abaixo os motivos apontados.</b><br><br><td></tr>'.$crlf;
   } 
    $w_html.=$crlf.'<tr><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'       <table border=1 width="100%"><tr><td bgcolor="#FAEBD7">';
    $w_html.=$crlf.'         <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html.=$crlf.'           <tr valign="top">';
    $w_html.=$crlf.'             <td>Serviço:<br><b>'.f($RSM,'nome').'</b></td>';
    $w_html.=$crlf.'             <td align="right">N:<br><b>'.f($RSM,'sq_siw_solicitacao').'</b></td>';
    $w_html.=$crlf.'           <tr valign="top">';
    $w_html.=$crlf.'             <td>Solicitante:<br><b>'.f($RSM,'nm_sol').'</b></td>';
    $w_html.=$crlf.'             <td align="right">Unidade solicitante:<br><b>'.f($RSM,'nm_unidade_solic').'</b></td>';
    $w_html.=$crlf.'         </table>';
    $w_html.=$crlf.'       </table>';
    // Identificação da solicitação
    $w_html.=$crlf.'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';

    // Exibe as informações da data, conforme definição para o serviço.
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
      $w_html.=$crlf.'          <td WIDTH="30%"><b>Início:</b>';
      $w_html.=$crlf.'            <td>'.Nvl(FormataDataEdicao(f($RSM,'phpdt_inicio')),'-').' </td>';
      $w_html.=$crlf.'        <tr valign="top">';
      $w_html.=$crlf.'          <td><b>Término:</b>';
      $w_html.=$crlf.'            <td>'.Nvl(FormataDataEdicao(f($RSM,'phpdt_fim')),'-').' </td>';
      break;
    case 4 :
      $w_html.=$crlf.'          <td WIDTH="30%"><b>Início:</b>';
      $w_html.=$crlf.'            <td>'.Nvl(substr(FormataDataEdicao(f($RSM,'phpdt_inicio'),3),0,-3),'-').' </td>';
      $w_html.=$crlf.'        <tr valign="top">';
      $w_html.=$crlf.'          <td><b>Término:</b>';
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

    // Se for conclusão, exibe.
    if (nvl(f($RSM,'conclusao'),'')!='') {
      $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=$crlf.'   <tr valign="top"><td><b>Data de conclusão:</b></font></td><td>'.FormataDataEdicao(substr(f($RSM,'phpdt_conclusao'),0,-3),3).'</font></td></tr>';
      $w_html.=$crlf.'   <tr><td><b>Unidade executora:</b></font></td>';
      $w_html.=$crlf.'       <td>'.f($RSM,'nm_unidade_exec').'</font></td></tr>';
      if ($SG=='SRTRANSP') {
        $w_html.=$crlf.'   <tr><td><b>Motorista:</b></font></td>';
        $w_html.=$crlf.'       <td>'.f($RSM,'nm_exec').'</font></td></tr>';
        $w_html.=$crlf.'   <tr><td><b>Veículo:</b></font></td>';
        $w_html.=$crlf.'       <td>'.f($RSM,'nm_placa').'</font></td></tr>';
        $w_html.=$crlf.'       <tr valign="top"><td><b>Data do atendimento:</td>';
        $w_html.=$crlf.'         <td>Saída: '.substr(FormataDataEdicao(f($RSM,'phpdt_horario_saida'),3),0,-3).'<br>Retorno: '.substr(FormataDataEdicao(f($RSM,'phpdt_horario_chegada'),3),0,-3).'<b></font></td></tr>';
        $w_html.=$crlf.'       <tr valign="top"><td><b>Hodômetro:</td>';
        $w_html.=$crlf.'         <td>Saída: '.f($RSM,'hodometro_saida').'<br>Retorno:'.f($RSM,'hodometro_chegada').'<b></font></td></tr>';
        $w_html.=$crlf.'       <tr><td><b>Parcial:</td>';
        $w_html.=$crlf.'     <td>'.RetornaSimNao(f($RSM,'parcial')).'</b></td></tr>';
        $w_html.=$crlf.'   <tr><td><b>Passageiro:</b></font></td>';
        $w_html.=$crlf.'       <td>'.f($RSM,'nm_recebedor').'</font></td></tr>';
      }
      // Se o serviço pede justificativa, exibe.
      if (nvl(f($RSM,'nm_opiniao'),'')!='') {
        $w_html.=$crlf.'   <tr valign="top"><td><b>Opinião:</b></font></td><td>'.nvl(f($RSM,'nm_opiniao'),'---').'</font></td></tr>';
      }
      if (nvl(f($RSM,'motivo_insatisfacao'),'')!='') {
        $w_html.=$crlf.'   <tr valign="top"><td><b>Motivo da insatisfação:</b></font></td><td><font size=2 color="red">'.crlf2br(nvl(f($RSM,'motivo_insatisfacao'),'---')).'</font></td></tr>';
      }
    } 
    $w_html.=$crlf.'      </table>';
    $w_html.=$crlf.'      </tr>';
    
    //Recupera o último log
    $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc');
    foreach ($RS as $row) { $RS = $row; if(strpos(f($row,'despacho'),'*** Nova versão')===false) break; }
    $w_data_encaminhamento = f($RS,'phpdt_data');
    // Exibe dados da ocorrência
    $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>ÚLTIMO ENCAMINHAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>Responsável: <b>'.f($RS,'responsavel').'</b></td>';
    $w_html.=$crlf.'          <tr><td>Ocorrência:<ul>';
    if ($p_tipo==3) {
      $w_html.=$crlf.'            <li><b>Comunicado de conclusão</b>';
    } elseif ($p_tipo==4) {
      $w_html.=$crlf.'            <li><b>Comunicado de insatisfação</b>';
    } else {
      $w_html.=$crlf.'            <li><b>'.CRLF2BR(f($RS,'observacao')).' </b>';
    }
    $w_html.=$crlf.'            <li><b>Responsável: </b>'.$_SESSION['NOME'].'</li>'.$crlf;
    $w_html.=$crlf.'            <li><b>Data: </b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</li>'.$crlf;
    $w_html.=$crlf.'            <li><b>IP de origem: </b>'.$_SERVER['REMOTE_ADDR'].'</li>'.$crlf;
    $w_html.=$crlf.'            </ul>'.$crlf;
    $w_html.=$crlf.'          </table>';
    $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRAS INFOMAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $RS = db_getCustomerSite::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    $w_html.='      <tr valign="top"><td>'.$crlf;
    $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='    </table>'.$crlf;
    $w_html.='</td></tr>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</BODY>'.$crlf;
    $w_html.='</HTML>'.$crlf;

   // Configura os destinatários da mensage m

    if ($p_tipo==2) {
      // Se for tramitação, envia e-mail para os responsáveis pelo seu cumprimento
      $RS = db_getTramiteResp::getInstanceOf($dbms,$p_solic,null,null);
      if (count($RS)>0) {
        foreach($RS as $row) {
          $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
        } 
      } 
    } elseif ($p_tipo==3) {
      if(f($RSM,'st_sol')=='S') {
        // Se for conclusão, envia e-mail ao solicitante comunicando a necessidade de informar sua opinião
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
        $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
      }
    } elseif ($p_tipo==4) {
      // Se for comunicado de insatisfação, envia e-mail para os responsáveis pelo cumprimento do trâmite "Em execução".
      $RS = db_getTramiteList::getInstanceOf($dbms,f($RSM,'sq_siw_tramite'),'ANTERIOR',null);
      foreach($RS as $row) { $RS = $row; break; }
      $RS = db_getTramiteResp::getInstanceOf($dbms,$p_solic,f($RS,'sq_siw_tramite'),null);
      if (count($RS)>0) {
        foreach($RS as $row) {
          $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
        } 
      } 
    }
    // Prepara os dados necessários ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclusão ou Conclusão
      if ($p_tipo==1) $w_assunto='Inclusão - '.$w_nome; else $w_assunto='Conclusão - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramitação
      $w_assunto='Tramitação - '.$w_nome;
    } elseif ($p_tipo==4) {
      // Comunicado de insatisfação
      $w_assunto='Comunicado de insatisfação - '.$w_nome;
    } 
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado=EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail.\n'.$w_resultado.'\');');
      ScriptClose();
    }
  }
} 

// =========================================================================
// Procedimento que executa as operações de BD
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
  if($SG=='EVANEXO') {
  //exibeVariaveis();
      if (UPLOAD_ERR_OK==0) {
        $w_maximo = $_REQUEST['w_upload_maximo'];
        $w_tamanho = 0;
        foreach ($_FILES as $Chv => $Field) {
          if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
            ScriptClose();
            retornaFormulario('w_observacao');
            exit();
          }
          $w_tamanho = $Field['size'];
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
            if ($_REQUEST['w_atual']>'') {
              $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
              foreach ($RS as $row) {
                if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                if (strpos(f($row,'caminho'),'.')!==false) {
                  $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,30);
                } else {
                  $w_file = basename(f($row,'caminho'));
                }
              }
            } else {
              $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
              if (strpos($Field['name'],'.')!==false) {
                $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
              }
            } 
            $w_tipo    = $Field['type'];
            $w_nome    = $Field['name'];
            if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
          }elseif(nvl($Field['name'],'')!=''){
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
            ScriptClose();
            retornaFormulario('w_caminho');
            exit();
          }
        } 
        // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
        if ($O=='E' && $_REQUEST['w_atual']>'') {
          $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
          foreach ($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          }
        }
        dml_putSolicArquivo::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$w_file,$w_tamanho,$w_tipo,$w_nome);        
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
        ScriptClose();
        exit();
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&R='.$R.'&O=L&w_menu='.$w_menu.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
  }elseif (strpos('IAE',$O)!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se for operação de exclusão, verifica se é necessário excluir os arquivos físicos
      if ($O=='E') {
        $RS = db_getSolicLog::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e não excluída.
        // Nessa situação, não é necessário excluir os arquivos.
        if (count($RS)<=1) {
          $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
          foreach($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          } 
        } 
      } 
      include_once($w_dir_volta.'classes/sp/dml_putSolicEvento.php');
      if(nvl($_REQUEST['w_data_inicio'],'') != ''){
        $w_inicio = $_REQUEST['w_data_inicio'].', '.$_REQUEST['w_hora_inicio'];
      }
      if(nvl($_REQUEST['w_data_termino'],'') != ''){
        $w_fim    = $_REQUEST['w_data_termino'].', '.$_REQUEST['w_hora_termino'];
      }
      dml_putSolicEvento::getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_solic_pai'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],
            $_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_tipo_evento'],
            $_REQUEST['w_indicador1'],$_REQUEST['w_indicador2'],$_REQUEST['w_indicador3'],
            $_REQUEST['w_observacao'],$_REQUEST['w_titulo'],$_REQUEST['w_motivo'],
            $_REQUEST['w_justificativa'],$_REQUEST['w_descricao'],$w_inicio,$w_fim,
            $_REQUEST['w_data_hora'], $_REQUEST['w_cidade'], $w_copia, &$w_chave_nova);
      // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
      if ($O=='E') {
        $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
        foreach ($RS as $row) {
          if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
        }
      }
            
            if (nvl($_REQUEST['w_envio'],'N')=='S') {
        dml_putSolicEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$w_chave_nova,$w_usuario,$_REQUEST['w_tramite'],null,
          'N',null,null,null,null,null);
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='F') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {

      if ($SG=='SRTRANSP') {
        include_once($w_dir_volta.'classes/sp/dml_putSolicInfTransp.php');
        dml_putSolicInfTransp::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,
              $_REQUEST['w_executor'],$_REQUEST['w_sq_veiculo']);
      } else {
        include_once($w_dir_volta.'classes/sp/dml_putSolicInfevento.php');
        dml_putSolicInfGeral::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,
              $_REQUEST['w_executor'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_valor']);
      }
            
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='O') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Verifica se outro usuário já emitiu opinião sobre o atendimento
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (nvl(f($RS,'opiniao'),'')!='') {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já emitiu opinião sobre este atendimento!\');');
        ScriptClose();
      } else {
        // Recupera a chave da opinião emitida
        $RS = db_getOpiniao::getInstanceOf($dbms,null,$w_cliente,null,$_REQUEST['w_opiniao'],$restricao);
        foreach($RS as $row) { $RS = $row; break; }

        // Grava a opinião do solicitante
        dml_putSolicOpiniao::getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS,'chave'),$_REQUEST['w_motivo']);
        
        // Se o solicitante ficou insatisfeito, envia e-mail para a área responsável pelo atendimento.
        if ($_REQUEST['w_opiniao']=='IN') SolicMail($_REQUEST['w_chave'],4);
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='V') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ((false!==(strpos(strtoupper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(strtoupper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
        // Verifica se outro usuário já enviou a solicitação
        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
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
            ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
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
          // Envia mail avisando sobre a tramitação da solicitação
          SolicMail($_REQUEST['w_chave'],2);
          
          // Volta para a listagem 
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } 
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='C') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta solicitação para outra fase de execução!\');');
        ScriptClose();
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
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
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
          dml_putSolicConc::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_fim'],$_REQUEST['w_executor'],$_REQUEST['w_observacao'],nvl($_REQUEST['w_valor'],'0,00'),
              $w_file,$w_tamanho,$w_tipo,$w_nome,null,null,null);
        }
        // Envia e-mail comunicando a conclusão
        SolicMail($_REQUEST['w_chave'],3);
        ScriptOpen('JavaScript');
        // Volta para a listagem
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Operação não prevista: '.nvl($O,'nulo').'\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  if ($P1==1 && nvl(f($RS_Menu,'sq_unid_executora'),'')=='' && $par == 'INICIAL') {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b><font color="RED">Atenção: unidade executora do serviço não informada. Entre em contato com os gestores.</b></font><br><br><br><br><br><br><br><br><br><br></center></div>');
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
  case 'ANEXO':         Anexos();         break;
  case 'OPINIAO':       Opiniao();        break;
  case 'DADOSEXECUCAO': DadosExecucao();  break;
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
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 
} 
?>