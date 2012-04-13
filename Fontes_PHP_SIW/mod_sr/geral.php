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
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getOpiniao.php');
include_once($w_dir_volta.'classes/sp/db_getCelular.php');
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
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o módulo de recursos logísticos
// Mail     : alex@sbpi.com.br
// Criacao  : 17/11/2006 12:25
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
$w_pagina       = 'geral.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_sr/';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Se for acompanhamento, entra na filtragem  
if (nvl($O,'')=='') {
  if ($P1==3) $O = 'P'; else $O = 'L';
}

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.(($SG=='SRSOLCEL') ? ' - Termo de Referência' : ' - Exclusão');    break;
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
$p_sq_menu      = upper($_REQUEST['p_sq_menu']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_ordena       = lower($_REQUEST['p_ordena']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
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

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
} 

// Recupera a configuração do serviço
if ($P2>0) {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$P2);
} else {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
}

// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
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
        $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$p_sq_menu);
        $w_filtro .= '<tr valign="top"><td align="right">Serviço <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') $w_filtro .= '<tr valign="top"><td align="right">Demanda nº <td>[<b>'.$p_chave.'</b>]';
      if ($p_prazo>'') $w_filtro .= ' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_solicitante>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Solicitante <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>'') {
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Setor solicitante <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_usu_resp>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_uorg_resp>''){
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_uorg_resp);
        $w_filtro .= '<tr valign="top"><td align="right">Setor executor <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_pais>'') {
        $sql = new db_getCountryData; $RS = $sql->getInstanceOf($dbms,$p_pais);
        $w_filtro .= '<tr valign="top"><td align="right">País <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_regiao>'') {
        $sql = new db_getRegionData; $RS = $sql->getInstanceOf($dbms,$p_regiao);
        $w_filtro .= '<tr valign="top"><td align="right">Região <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uf>'') {
        $sql = new db_getStateData; $RS = $sql->getInstanceOf($dbms,$p_pais,$p_uf);
        $w_filtro .= '<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_cidade>'') {
        $sql = new db_getCityData; $RS = $sql->getInstanceOf($dbms,$p_cidade);
        $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_prioridade>'') {
        $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,$p_prioridade, null);
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

    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as solicitações visíveis pelo usuário
      $sql = new db_getSolicSR; $RS = $sql->getInstanceOf($dbms, $w_cliente,
          (($P1==3) ? nvl($p_sq_menu,0) : f($RS,'sq_menu')),
          $w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, null, null, null, null, null);
    } else {
      $sql = new db_getSolicSR; $RS = $sql->getInstanceOf($dbms, $w_cliente,
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
    head();
    Estrutura_CSS($w_cliente);
    if ($P1==2) ShowHTML ('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$conRootSIW.MontaURL('MESA').'">');
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
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
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
      if ((strpos(upper($R),'GR_'))===false) {
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
    if ((strpos(upper($R),'GR_')===false) && $P1!=6 && $w_embed!='WORD') {
      if ($w_copia>'') {
        // Se for cópia
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } else {
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } 
    } 
    ShowHTML('    <td align="right">');
    ShowHTML('    '.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    if ($w_embed!='WORD') {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Nº','sq_siw_solicitacao').'</td>');
      if ($P1==3) {
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Serviço','nome').'</td>');
        ShowHTML('          <td colspan=3><b>Data</td>');
      } elseif (f($RS_Menu,'data_hora')==0 || f($RS_Menu,'data_hora')==1 || f($RS_Menu,'data_hora')==2) {
        ShowHTML('          <td colspan=2><b>Data</td>');
      } elseif (f($RS_Menu,'data_hora')>0) {
        ShowHTML('          <td colspan=3><b>Data</td>');
      }
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Solicitante','nm_solic').'</td>');
      if ($SG=='SRTRANSP') {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Detalhamento','justificativa').'</td>');
      } elseif ($SG=='SRSOLCEL') {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Destino','nm_pais_cel').'</td>');
      } else {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Detalhamento','descricao').'</td>');
      }
      if ($P1>1) ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      ShowHTML('          <td class="remover" rowspan=2><b>Operações</td>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>'.LinkOrdena('Inclusão','phpdt_inclusao').'</td>');
      if (f($RS_Menu,'data_hora')==0 || f($RS_Menu,'data_hora')==1 || f($RS_Menu,'data_hora')==2 || $P1==3) {
        ShowHTML('          <td><b>'.LinkOrdena('Programada','phpdt_programada').'</td>');
      } elseif (f($RS_Menu,'data_hora')>0) {
        ShowHTML('          <td><b>'.LinkOrdena('Início','phpdt_inicio').'</td>');
        ShowHTML('          <td><b>'.LinkOrdena('Término','phpdt_fim').'</td>');
      }
      if ($P1==3) ShowHTML('          <td><b>'.LinkOrdena('Conclusão','phpdt_conclusao').'</td>');
    } else {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td rowspan=2><b>Nº</td>');
      if ($P1==3) {
        ShowHTML('          <td rowspan=2><b>Serviço</td>');
        ShowHTML('          <td colspan=3><b>Data</td>');
      } elseif (f($RS_Menu,'data_hora')==1 || f($RS_Menu,'data_hora')==2) {
        ShowHTML('          <td colspan=2><b>Data</td>');
      } elseif (f($RS_Menu,'data_hora')>0) {
        ShowHTML('          <td colspan=3><b>Data</td>');
      }
      ShowHTML('          <td rowspan=2><b>Solicitante</td>');
      ShowHTML('          <td rowspan=2><b>Detalhamento</td>');
      if ($P1>1) ShowHTML('          <td rowspan=2><b>Fase atual</td>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Inclusão</td>');
      if (f($RS_Menu,'data_hora')==1 || f($RS_Menu,'data_hora')==2 || $P1==3) {
        ShowHTML('          <td><b>Programada</td>');
      } elseif (f($RS_Menu,'data_hora')>0) {
        ShowHTML('          <td><b>Início</td>');
        ShowHTML('          <td><b>Término</td>');
      }
      if ($P1==3) ShowHTML('          <td><b>Conclusão</td>');
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
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),nvl(f($row,'phpdt_inicio'),f($row,'phpdt_inclusao')),f($row,'phpdt_programada'),f($row,'phpdt_inicio'),f($row,'phpdt_conclusao'),f($row,'aviso_prox_conc'),addDays(f($row,'fim'),-1),f($row,'sg_tramite'), null));
        if ($w_embed!='WORD') {
          ShowHTML('        <A class="HL" href="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'</a>');
        } else {
          ShowHTML('        '.f($row,'sq_siw_solicitacao'));
        } 
        if ($P1==3) ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'phpdt_inclusao')),'-').'</td>');
        switch (f($row,'data_hora')) {
        case 0 :
          ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'phpdt_programada')),'-').'</td>');
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
        if ($P1==3) {
          if (f($row,'sigla')=='SRSERVGER') {
            ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'phpdt_conclusao')).'</td>');
          } else {
            ShowHTML('        <td align="center">'.Nvl(substr(FormataDataEdicao(f($row,'phpdt_conclusao'),3),0,-3),'---').'</td>');
          }
        }
        if ($w_embed!='WORD') {
          ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>');
        } else {
          ShowHTML('        <td>'.f($row,'nm_solic').'</td>');
        } 

        // Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        // Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        if ($SG=='SRTRANSP') $w_texto = f($row,'justificativa');
        elseif ($SG=='SRSOLCEL') $w_texto = f($row,'nm_pais_cel');
        else $w_texto = f($row,'descricao');
        if ($_REQUEST['p_tamanho']=='N') {
          ShowHTML('        <td>'.Nvl($w_texto,'-').'</td>');
        } else {
          if ($w_embed!='WORD' && strlen(Nvl($w_texto,'-'))>50) $w_titulo = substr(Nvl($w_texto,'-'),0,50).'...'; else $w_titulo = Nvl($w_texto,'-');
          if (f($row,'sg_tramite')=='CA') {
            ShowHTML('        <td title="'.htmlspecialchars($w_texto).'"><strike>'.$w_titulo.'</strike></td>');
          } else {
            ShowHTML('        <td title="'.htmlspecialchars($w_texto).'">'.$w_titulo.'</td>');
          } 
        }
        if ($P1>1) ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        if ($w_embed!='WORD') {
          ShowHTML('        <td  class="remover" align="top" nowrap>');
          if ($P1!=3) {
            // Se não for acompanhamento
            if ($w_copia>'') {
              // Se for listagem para cópia
              if ($w_submenu=='Existe') {
                $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
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
              if (f($RS_Menu,'sigla')!='SRSOLCEL') ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
            } elseif ($P1==2 || $P1==6) {
              if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S' || f($row,'acesso')>15) {
                // Se for execução
                if (f($row,'sg_tramite')=='AT') {
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'opiniao&R='.$w_pagina.$par.'&O=O&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite opinião sobre o atendimento.">Opinião</A>&nbsp');
                }
                if (f($row,'sg_tramite')=='EA') {
                  if ($SG=='SRSOLCEL') {
                    ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'AnaliseCelular&R='.$w_pagina.$par.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=ANALCEL'.MontaFiltro('GET').'" title="Informar a análise do atendimento.">EN</A>&nbsp');
                  } elseif ($SG!='SRTRANSP') {
                    ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'DadosExecucao&R='.$w_pagina.$par.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=DADEXEC'.MontaFiltro('GET').'" title="Informar dados da execucao.">IN</A>&nbsp');
                  }
                }
                if (f($row,'sg_tramite')=='TR') {
                  if ($SG=='SRSOLCEL') {
                    ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'TermoCelular&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emitir o termo de responsabilidade do empréstimo.">EN</A>&nbsp');
                  }
                }
                if (f($row,'sg_tramite')=='DE') {
                  if ($SG=='SRSOLCEL') {
                    ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'EntregaCelular&R='.$w_pagina.$par.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=ENTCEL'.MontaFiltro('GET').'" title="Registrar a entrega do celular ao beneficiário da solicitação.">EN</A>&nbsp');
                  }
                }
                if (f($row,'sg_tramite')=='AD') {
                  if ($SG=='SRSOLCEL') {
                    ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'DevolCelular&R='.$w_pagina.$par.'&O=D&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registrar a devolução do celular.">EN</A>&nbsp');
                  }
                }
                if (f($row,'sg_tramite')=='EE') {
                  if ($SG!='SRSOLCEL') ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para o lançamento, sem enviá-la.">AN</A>&nbsp');
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
                    } elseif ($SG!='SRSOLCEL') {
                      ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'EmiteOS&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Emite Ordem de Serviço." target="OS">OS</A>&nbsp');
                    }
                  }
                  ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicitação para outro trâmite.">EN</A>&nbsp');
                  ShowHTML('          <A class="HL" href="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=C&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução da solicitação.">CO</A>&nbsp');
                } else {
                  if ($SG!='SRSOLCEL' || ($SG=='SRSOLCEL' && strpos('CB,PP',f($row,'sg_tramite'))!==false) || strpos('AD,DE,EA,TR',f($row,'sg_tramite'))===false) {
                    ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicitação para outro trâmite.">EN</A>&nbsp');
                  }
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
      //ShowHTML('      <tr>');
      //SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      //SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      //ShowHTML('      <tr>');
      //SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      //SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>Deta<U>l</U>hamento:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      if ($P1==3) selecaoOpiniao('Exibir somente opiniões do tipo:',null,null,$p_prioridade,$w_cliente,'p_prioridade',null,'SELECT');
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
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_dir.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Abandonar cópia">');
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
    ShowHTML(' alert("Opção não disponível");');
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
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_menu,null,null,null);
    $RS = SortArray($RS,'ordem','asc');
    foreach($RS as $row) { 
      $w_tramite = f($row,'sq_siw_tramite'); 
      break; 
    }
    
    $w_envio_inclusao = 'S';
  } else {
    $w_envio_inclusao = 'N';
  }
  if ($SG=='SRTRANSP') {
    include_once('transporte_gerais.php');
  } elseif ($SG=='SRSOLCEL') {
    include_once('celular_gerais.php');
  } else {
    include_once('geral_gerais.php');
  }
} 

// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = upper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de demanda</TITLE>');
    ShowHTML('</head>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    $w_embed = 'WORD';
  }  elseif($w_tipo == 'PDF') {
    headerPdf('Visualização de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de demanda</TITLE>');
    ShowHTML('</head>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\'; ');
    CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);  
  } 
  
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualGeral($w_chave,'L',$w_usuario,$SG,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  ScriptOpen('JavaScript');
  ShowHTML('  var comando, texto;');
  ShowHTML('  if (window.name!="content") {');
  ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
  ShowHTML('  }');
  ScriptClose();
  if ($w_tipo=='PDF')      RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
} 

// =========================================================================
// Rotina de assinatura eletrônica do termo de responsabilidade
// -------------------------------------------------------------------------
function TermoCelular() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave     = $_REQUEST['w_chave'];
  $w_sq_menu   = $_REQUEST['w_sq_menu'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  $w_tipo      = Nvl($_REQUEST['w_tipo'],'');

  if ($P1=='6') $w_envio = 'S';

  // Recupera os dados da solicitação
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  $w_inicio = formataDataEdicao(f($RS_Solic,'inicio'));
  $w_fim    = formataDataEdicao(f($RS_Solic,'fim'));

  $w_readonly       = '';
  $w_erro           = '';

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
  } else {
    $w_tramite          = f($RS_Solic,'sq_siw_tramite');
  }
  
  $w_envio = nvl($w_envio,'N');
  
  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');

  //Verifica a fase anterior para a caixa de seleção da fase.
  $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,null,'ANTERIOR',null);
  foreach($RS as $row) { $RS = $row; break; }
  $w_novo_tramite = f($RS,'sq_siw_tramite');

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();  
  ShowHTML('  function texto(linha) {');
  ShowHTML('    document.Form.w_acessorios.value=document.Form["w_texto["+linha+"]"].value;');
  ShowHTML('  }');
  ValidateOpen('Validacao');
  if (nvl($w_envio,'')=='S') {
    Validate('w_despacho','Despacho','1','1','1','2000','1','1');
  }
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus();\'');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,'V');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS_Solic,'sq_siw_tramite').'">');

  ShowHTML('<tr><td>');
  ShowHTML('    <tr><td><table width="100%" border="0"><tr><td colspan=3>');
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,$SG,null));
  if (Nvl($w_envio,'N')=='N') {
    if ($O=='E') {
      include_once('celular_termoentrega.php');
      ShowHTML('    <tr><td>');
      ShowHTML(celular_termoentrega($w_chave,$SG,1));
    } elseif ($O=='D') {
      include_once('celular_termodevol.php');
      ShowHTML('    <tr><td>');
      ShowHTML(celular_termodevol($w_chave,$SG,1));
    }
    ShowHTML('  <tr><td colspan="3"><font size="2"><hr NOSHADE color=#000000 SIZE=1></font></td></tr>');
  }
  ShowHTML('      <tr><td colspan="3">');
  ShowHTML('        <input class="STR" '.(($P1==6) ? 'DISABLED' : '').' type="radio" name="w_envio" value="N" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.'\'; document.Form.O.value=\''.$O).'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();"'.((Nvl($w_envio,'N')=='N') ? ' checked' : '').'> Enviar para a próxima fase <br>');
  ShowHTML('        <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.'\'; document.Form.O.value=\''.$O).'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();"'.((Nvl($w_envio,'N')=='S') ? ' checked' : '').'> Devolver para a fase anterior');
  if ($w_envio=='S') {
    ShowHTML('      <tr>');
    SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','DEVFLUXO',null);
    ShowHTML('    <tr><td colspan="3"><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o motivo da devolução e ações a serem executadas.">'.$w_despacho.'</TEXTAREA></td>');
  }
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('      <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=3><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  Rodape();
} 

// =========================================================================
// Rotina de emissão dos termos de responsabilidade de celular
// -------------------------------------------------------------------------
function EmiteTermoCelular() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = upper(trim($_REQUEST['w_tipo']));

  // Recupera o logo do cliente a ser usado nas listagens
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.'</TITLE>');
  ShowHTML('</head>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpenClean('onLoad=\'this.focus()\'; ');
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
  if ($O=='E') {
    ShowHTML('TERMO DE RECEBIMENTO E RESPONSABILIDADE');
  } elseif ($O=='D') {
    ShowHTML('TERMO DE DEVOLUÇÃO');
  }
  ShowHTML('<TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Emissão: '.DataHora().'</font></B></TD></TR>');
  ShowHTML('</B></TD></TR></TABLE>');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  if ($O=='E') {
    include_once('celular_termoentrega.php');
    ShowHTML(celular_termoentrega($w_chave,$SG));
  } elseif ($O=='D') {
    include_once('celular_termodevol.php');
    ShowHTML(celular_termodevol($w_chave,$SG));
  }
  Rodape();
} 

// =========================================================================
// Rotina de emissão da ordem de serviço
// -------------------------------------------------------------------------
function EmiteOS() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = upper(trim($_REQUEST['w_tipo']));

  // Recupera o logo do cliente a ser usado nas listagens
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Ordem de Serviço</TITLE>');
  ShowHTML('</head>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpenClean('onLoad=\'this.focus()\'; ');
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
  ShowHTML('ORDEM DE SERVIÇO');
  ShowHTML('<TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Emissão: '.DataHora().'</font></B></TD></TR>');
  ShowHTML('</B></TD></TR></TABLE>');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
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
  $sql = new db_getRecurso; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_menu,null,null,null,null,null,'SERVICO');
  if (count($RS)) $w_exibe_recurso = true; else $w_exibe_recurso = false;
  if ($w_exibe_recurso) {
    $sql = new db_getSolicRecursos; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_solic_recurso   = f($RS,'chave_aux');
  }
  Cabecalho();
  head();
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
  ShowHTML('</head>');
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
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,$SG,null));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_solic_recurso" value="'.$w_solic_recurso.'">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
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
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    $w_inicio        = f($RS,'inicio');
    $w_fim           = f($RS,'fim');
    $w_tramite       = f($RS,'sq_siw_tramite');
    $w_justificativa = f($RS,'justificativa');
  } 

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');

  if ($w_sg_tramite!='CI') {
    //Verifica a fase anterior para a caixa de seleção da fase.
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,null,'ANTERIOR',null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 

  // Se for envio, executa verificações nos dados da solicitação
  if ($O=='V') $w_erro = ValidaGeral($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);

  Cabecalho();
  head();
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
  ShowHTML('</head>');
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
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
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
      SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_novo_tramite,null,'w_novo_tramite','DEVOLUCAO',null);
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
  Rodape();
} 

// =========================================================================
// Rotina de registro da opinião
// -------------------------------------------------------------------------
function Opiniao() {
  extract($GLOBALS);
  global $w_Disabled;

  $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,null,null);
  if (count($RS)==0) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b><font color="RED">Atenção: a tabela de opiniões disponíveis não foi alimentada. Entre em contato com os gestores.</b></font><br><br><br><br><br><br><br><br><br><br></center></div>');
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
  head();
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
  ShowHTML('    alert(\'Você deve selecionar uma das opiniões!\'); ');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  if (theForm.w_opiniao[w_indice].value==\'IN\' && theForm.w_motivo.value==\'\') {');
  ShowHTML('    alert(\'Você deve informar o motivo da insatisfação!\'); ');
  ShowHTML('    theForm.w_motivo.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  if (theForm.w_opiniao[w_indice].value!=\'IN\' && theForm.w_motivo.value!=\'\') {');
  ShowHTML('    alert(\'O campo motivo deve ser informado apenas se você ficou insatisfeito com o atendimento!\'); ');
  ShowHTML('    theForm.w_motivo.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  Validate('w_motivo','Motivo da insatisfação','1','','6','1000','1','1');
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<center>');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,$SG,null));
  ShowHTML('<HR>');
  ShowHTML('  <table width="97%" border="0" bgcolor="'.$conTrBgColor.'">');
  ShowHTML('    <tr><td align="justify"><font size="2">É importante para as áreas executoras saber sua opinião sobre o atendimento desta solicitação. Selecione uma das alternativas abaixo, informe sua assinatura e clique no botão <i>Gravar</i>.</font>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  selecaoOpiniao(null,null,null,null,$w_cliente,'w_opiniao',null,'CHECKBOX');
  ShowHTML('    <tr><td>');
  ShowHTML('      <tr><td><b><u><br>M</u>otivo da insatisfação: (apenas se ficou insatisfeito com o atendimento)</b><br><textarea '.$w_Disabled.' accesskey="M" name="w_motivo" class="STI" ROWS=5 cols=75 title="Descreva os motivos pelos quais você ficou insatisfeito com o atendimento.">'.$w_motivo.'</TEXTAREA></td>');
  ShowHTML('      <br>');
  ShowHTML('    </td></tr>');
  ShowHTML('    <tr><td><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  head();
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
  ShowHTML('</head>'); 
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
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_novo_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
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
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));

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
  head();
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
  ShowHTML('</head>');
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
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
// Rotina de análise de solicitações de celular
// -------------------------------------------------------------------------
function AnaliseCelular() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave     = $_REQUEST['w_chave'];
  $w_sq_menu   = $_REQUEST['w_sq_menu'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  $w_tipo      = Nvl($_REQUEST['w_tipo'],'');
  
  if ($P1=='6') $w_envio = 'S';

  // Recupera os dados da solicitação
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  $w_inicio = addDays(f($RS_Solic,'inicio'),-5);
  $w_fim    = addDays(f($RS_Solic,'fim'),5);
  $w_dias   = ceil(($w_fim-$w_inicio)/84600);

  $w_readonly       = '';
  $w_erro           = '';

  if ($w_troca>'') {
    // Se for recarga da página
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_celular          = $_REQUEST['w_celular'];
    $w_acessorios       = $_REQUEST['w_acessorios'];
  } else {
    $w_celular          = f($RS_Solic, 'sq_celular');
    $w_acessorios       = f($RS_Solic, 'acessorios_entregues');
    $w_tramite          = f($RS_Solic,'sq_siw_tramite');
    $w_justificativa    = f($RS_Solic,'justificativa');
  }
  
  $w_envio = nvl($w_envio,'N');
  
  $sql = new db_getCelular; $RS_Celular = $sql->getInstanceOf($dbms, $w_cliente, null,null,null,null,'S',$w_chave,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),'MAPAFUTURO');
  $RS_Celular = SortArray($RS_Celular,'numero_linha','asc');
  
  // Monta array com os celulares e as disponibilidades no período informado
  $dados = array();
  $w_linha    = '';
  $cont       = 0;
  $disponivel = 0;
  foreach($RS_Celular as $row) {
    if ($w_linha=='' or $w_linha!=f($row,'numero_linha')) {
      $cont++;
      $w_linha         = f($row,'numero_linha');
      $dados[$cont][0] = f($row,'chave');
      $dados[$cont][1] = $w_linha;
      $dados[$cont][2] = f($row,'acessorios');

      $j = 3;
      for ($i=$w_inicio; toDate(formataDataEdicao($i))<=$w_fim; $i+=86400) {
        // Verifica períodos de bloqueio
        if (f($RS_Solic,'inicio')<=toDate(formataDataEdicao($i)) && toDate(formataDataEdicao($i))<=f($RS_Solic,'fim')) {
          $dados[$cont][$j]= 'bgcolor="#CCFFCC" title="Disponível"';
        } else {
          $dados[$cont][$j]= '';
        }
        $j++;
      }
    }

    $j = 3;
    // Verifica se o celular está alocado
    if (nvl(f($row,'sq_siw_solicitacao'),'')!='') {
      // Recupera o período da solicitação
      $w_ini_sol = f($row,'inicio');
      $w_fim_sol = f($row,'fim');
      for ($i=$w_inicio; toDate(formataDataEdicao($i))<=$w_fim; $i+=86400) {
        if ($w_ini_sol<=toDate(formataDataEdicao($i)) && toDate(formataDataEdicao($i))<=$w_fim_sol) {
          $dados[$cont][$j] = 'bgcolor="#EE0000" title="Emprestado: Solicitação '.f($row,'codigo_interno').'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'visual&w_chave='.f($row,'sq_siw_solicitacao').'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=SRSOLCEL').'\',\'visual\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=no\')"';
          if (f($RS_Solic,'inicio')<=$i && $i<=f($RS_Solic,'fim')) $dados[$cont][0] = '';
        } 
        $j++;
      }
    }

    $j = 3;
    for ($i=$w_inicio; toDate(formataDataEdicao($i))<=$w_fim; $i+=86400) {
      // Verifica períodos de bloqueio
      if (f($row,'inicio_bloqueio')<=toDate(formataDataEdicao($i)) && ((f($row,'bloqueado')=='N' && toDate(formataDataEdicao($i))<=f($row,'fim_bloqueio')) || (f($row,'bloqueado')=='S' && toDate(formataDataEdicao($i))<=$w_fim))) {
        $dados[$cont][$j] = 'bgcolor="#999999" title="Bloqueado'.((nvl(f($row,'motivo_bloqueio'),'')!='') ? ': '.f($row,'motivo_bloqueio') : '').'"';
        if (f($RS_Solic,'inicio')<=toDate(formataDataEdicao($i)) && toDate(formataDataEdicao($i))<=f($RS_Solic,'fim')) $dados[$cont][0] = '';
      }
      $j++;
    }
  }
  
  // Verifica quantos são os aparelhos disponíveis para empréstimo
  $disponiveis = 0;
  foreach($dados as $row) {
    if ($row[0]!='') $disponiveis++;
  }

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');

  //Verifica a fase anterior para a caixa de seleção da fase.
  $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,null,'ANTERIOR',null);
  foreach($RS as $row) { $RS = $row; break; }
  $w_novo_tramite = f($RS,'sq_siw_tramite');

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();  
  ShowHTML('  function texto(linha) {');
  ShowHTML('    document.Form.w_acessorios.value=document.Form["w_texto["+linha+"]"].value;');
  ShowHTML('  }');
  ValidateOpen('Validacao');
  if (nvl($w_envio,'')=='S' || !$disponiveis) {
    Validate('w_despacho','Despacho','1','1','1','2000','1','1');
  } else {
    Validate('w_celular','Celular','RADIO','1','1','18','','1');
    Validate('w_acessorios','Acessórios','','','2','1000','1','1');
  }
  if ($disponiveis) {
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
  } else {
    ShowHTML('  theForm.Botao.disabled=true;');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($w_envio=='S' || !$disponiveis) {
    BodyOpenClean('onLoad=\'document.Form.w_novo_tramite.focus();\'');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_acessorios.focus();\'');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,'F');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS_Solic,'sq_siw_tramite').'">');
  
  ShowHTML('<tr><td>');
  ShowHTML('    <tr><td><table width="100%" border="0"><tr><td>');
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,'SRSOLCEL',null));
  
  $w_atual = addDays(f($RS_Solic,'inicio'),-5);
  $v_html ='    <tr><td colspan="3"><table width="100%" border="1"'.((!$disponiveis) ? ' bgcolor="FEFEFE"' : '').'><tr><td>';
  $v_html.='      <tr align="center">';
  $v_html.='        <td rowspan="2"'.(($disponiveis) ? ' colspan="2"' : '').'>Número Linha</td>';
  $l_html  = '';
  for ($i=1; $i<=$w_dias; $i++) {
    $l_html.='<td>'.substr(formataDataEdicao($w_atual),0,2).'</td>';
    $l_mes[substr(formataDataEdicao($w_atual),3,3).substr(formataDataEdicao($w_atual),8)] += 1;
    $w_atual = addDays($w_atual,1);
  }
  foreach($l_mes as $k => $v) $v_html.='<td colspan="'.$v.'">'.$k.'</td>';
  $v_html.='      <tr align="center">'.$l_html.'</tr>';

  foreach($dados as $row) {
    $v_html.='      <tr>';
    if ($disponiveis) {
      if ($row[0]!='') {
        $v_html.='        <td><input class="STR" type="radio" name="w_celular" value="'.$row[0].'"'.(($disponiveis==1 || $w_celular==$row[0]) ? ' checked' : '').' onClick="texto('.$row[0].')">';
        $v_html.='<INPUT type="hidden" name="w_texto['.$row[0].']" value="'.$row[2].'">';
      } else {
        $v_html.='        <td>&nbsp;&nbsp;&nbsp;</td>';
      }
    }
    $v_html.='<td nowrap><b>'.$row[1].'</b></td>';
    $w_atual = f($RS_Solic,'inicio');
    for ($i=3; $i<=($w_dias+2); $i++) {
      $v_html.='<td '.$row[$i].'>&nbsp;</td>';
    }
    $w_atual = addDays($w_atual,1);
  }
  $v_html.='</tr></table>';
  ShowHTML('    <tr><td><table width="100%" border="0" bgcolor="'.$conTrBgColor.'">');
  if (!$disponiveis || $P1==6) {
    ShowHTML('    <tr><td>'.(($P1!=6) ? '<b><font color="#FF0000">ATENÇÃO: Nenhum aparelho disponível para empréstimo no período indicado!</font></b>' : '').'</td>');
    ShowHTML('            <td align="right"><input class="stb" type="button" onClick="window.open(\''.$conRootSIW.$w_dir.$w_pagina.'DispCelular&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Mapa de Disponibilidade de Celular&SG='.$SG.'\',\'Indicador\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=no\');" value="MAPA DE DISPONIBILIDADE DE CELULAR">');
    ShowHTML('    <tr><td colspan=2><table id="Tudo" border="1" bgcolor="#f5f5f5" cellspacing="0">'.$v_html.'</table>');
    ShowHTML('    <tr><td colspan="2"><b>Tipo do Encaminhamento</b><br>');
    ShowHTML('        <input DISABLED class="STR" type="radio" name="w_envio" value="N"> Enviar para a próxima fase <br><input DISABLED class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
    ShowHTML('        <INPUT type="hidden" name="w_envio" value="S">');
    ShowHTML('    <tr>');
    SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','DEVFLUXO',null,2);
    ShowHTML('    <tr><td colspan="2"><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o motivo da devolução e ações a serem executadas.">'.$w_despacho.'</TEXTAREA></td>');
  } else {
    if (Nvl($w_envio,'N')=='N') {
      ShowHTML('              <input class="STR" type="radio" name="w_envio" value="N" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.montaFiltro('GET')).'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();" checked> Enviar para a próxima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.montaFiltro('GET')).'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_novo_tramite\'; document.Form.submit();"> Devolver para a fase anterior');
    } else {
      ShowHTML('              <input class="STR" type="radio" name="w_envio" value="N" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.montaFiltro('GET')).'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();"> Enviar para a próxima fase <br><input class="STR" class="STR" type="radio" name="w_envio" value="S" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.montaFiltro('GET')).'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_novo_tramite\'; document.Form.submit();" checked> Devolver para a fase anterior');
    } 
    ShowHTML('  <tr><td colspan="3"><font size="2"><hr NOSHADE color=#000000 SIZE=1></font></td></tr>');
    if ($w_envio=='S') {
      ShowHTML('    <tr>');
      SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','DEVFLUXO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o motivo da devolução e ações a serem executadas.">'.$w_despacho.'</TEXTAREA></td>');
    } elseif ($w_envio=='N') {
      ShowHTML('        <tr><td><font size="2"><b>DADOS DA EXECUÇÃO');
      ShowHTML('            <td align="right"><input class="stb" type="button" onClick="window.open(\''.$conRootSIW.$w_dir.$w_pagina.'DispCelular&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Mapa de Disponibilidade de Celular&SG='.$SG.'\',\'Indicador\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=no\');" value="MAPA DE DISPONIBILIDADE DE CELULAR">');
      ShowHTML('        <tr><td colspan="2"><hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
      ShowHTML('      <tr><td colspan="3"><b>'.$disponiveis.' aparelho'.(($disponiveis==1) ? ' disponível' : 's disponíveis').' para empréstimo no período indicado.<table id="Tudo" border="1" bgcolor="#f5f5f5" cellspacing="0">');
      
      ShowHTML($v_html);

      ShowHTML('    </table>');
      if ($disponiveis) {
        ShowHTML('      <tr><td colspan="3"><b>A<u>c</u>essórios:</b><br><textarea '.$w_Disabled.' accesskey="C" name="w_acessorios" class="STI" ROWS=5 cols=75 title="Relacione, se necessário, a lista de acessórios entregues com o aparelho.">'.$w_acessorios.'</TEXTAREA></td>');
        if ($disponiveis==1) {
          ShowHTML('        <SCRIPT LANGUAGE="JAVASCRIPT">');
          foreach($dados as $row) {
            if ($disponiveis) {
              if ($row[0]!='') {
                ShowHTML('        texto('.$row[0].')');
              }
            }
          }
          ShowHTML('        </SCRIPT>');
        }
      }
    }
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  }
  ShowHTML('    <tr><td align="center" colspan=3><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
// Exibição do mapa de disponibilidade de celulares
// -------------------------------------------------------------------------
function DispCelular() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave     = $_REQUEST['w_chave'];
  $w_sq_menu   = $_REQUEST['w_sq_menu'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  $w_tipo      = upper($_REQUEST['w_tipo']);

  $w_readonly       = '';
  $w_erro           = '';

  $w_mes      = $_REQUEST['w_mes'];
  $w_ano      = retornaAno();
  $w_pag      = 1;
  $w_linha    = 0;
  $w_TP       = 'MAPA DE DISPONIBILIDADE DE CELULAR';
  
  // Configura variáveis para montagem do calendário
  if (nvl($w_mes,'')=='') $w_mes = date('m',time());
  $w_inicio  = first_day(toDate('01/'.substr(100+(intVal($w_mes)),1,2).'/'.$w_ano));
  $w_fim     = last_day(toDate('01/'.substr(100+(intVal($w_mes)),1,2).'/'.$w_ano));
  $w_dias    = ceil((($w_fim-$w_inicio)/86400)+1);
  $w_mes1    = substr(100+intVal($w_mes)-1,1,2);
  $w_mes3    = substr(100+intVal($w_mes)+1,1,2);
  $w_ano1    = $w_ano;
  $w_ano3    = $w_ano;
  // Ajusta a mudança de ano
  if ($w_mes1=='00') { $w_mes1 = '12'; $w_ano1 = $w_ano-1; }
  if ($w_mes3=='13') { $w_mes3 = '01'; $w_ano3 = $w_ano+1; }

  $sql = new db_getCelular; $RS_Celular = $sql->getInstanceOf($dbms, $w_cliente, null,null,null,null,'S',null,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),'MAPA');
  $RS_Celular = SortArray($RS_Celular,'numero_linha','asc'); 

  // Monta array com os celulares e as disponibilidades no período informado
  $dados = array();
  $w_linha    = '';
  $cont       = 0;
  $disponivel = 0;
  foreach($RS_Celular as $row) {
    if ($w_linha=='' || $w_linha!=f($row,'numero_linha')) {
      $cont++;
      $w_linha         = f($row,'numero_linha');
      $j               = 3;
      $dados[$cont][0] = f($row,'chave');
      $dados[$cont][1] = $w_linha;
      $dados[$cont][2] = f($row,'acessorios');

      // Inicializa as células da tabela
      for ($i=$w_inicio; $i<=($w_fim+86400); $i+=86400) {
        $dados[$cont][$j]= 'bgcolor="#CCFFCC" title="Disponível"';
        $j++;
      }
      $j = 3;
    }

    // Verifica se o celular está alocado
    if (nvl(f($row,'sq_siw_solicitacao'),'')!='') {
      // Recupera o período da solicitação
      $w_ini_sol = (($w_inicio>=nvl(f($row,'inicio'), $w_inicio)) ? $w_inicio : f($row,'inicio'));
      $w_fim_sol = (($w_fim   <=nvl(f($row,'fim'),    $w_fim))    ? $w_fim    : f($row,'fim'));
      $k = intVal(substr(formataDataEdicao($w_ini_sol),0,2));
      $l = intVal(substr(formataDataEdicao($w_fim_sol),0,2));
      
      for ($i=$k; $i<=$l; $i++) {
        $dados[$cont][$i+2] = 'bgcolor="#EE0000" title="Emprestado: Solicitação '.f($row,'codigo_interno').'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'visual&w_chave='.f($row,'sq_siw_solicitacao').'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=SRSOLCEL').'\',\'visual\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=no\')"';
      }
    }
    
    // Inicialização das variáveis para análise do bloqueio
    $w_ini_bloqueio = nvl(f($row,'inicio_bloqueio'),$w_inicio);
    $w_fim_bloqueio = nvl(f($row,'fim_bloqueio'),addDays($w_ini_bloqueio,900));

    // Verifica se o celular está bloqueado no mês em exibição
    if ((f($row,'bloqueado')=='S' || nvl(f($row,'fim_bloqueio'),'')!='') &&
        (($w_inicio<=$w_ini_bloqueio && $w_ini_bloqueio<=$w_fim) ||
         ($w_inicio<=$w_fim_bloqueio && $w_fim_bloqueio<=$w_fim) ||
         ($w_ini_bloqueio<=$w_inicio && $w_inicio<=$w_fim_bloqueio) ||
         ($w_ini_bloqueio<=$w_fim    && $w_fim   <=$w_fim_bloqueio)
        )
       ) 
    {
      // Recupera o período do bloqueio
      $w_ini_blq = (($w_inicio>=nvl(f($row,'inicio_bloqueio'), $w_inicio))       ? $w_inicio       : f($row,'inicio_bloqueio'));
      $w_fim_blq = (($w_fim   <=nvl(f($row,'fim_bloqueio'),    $w_fim_bloqueio)) ? $w_fim_bloqueio : f($row,'fim_bloqueio'));
      
      $k = intVal(substr(formataDataEdicao($w_ini_blq),0,2));
      $l = intVal(substr(formataDataEdicao(((f($row,'bloqueado')=='S') ? $w_fim : $w_fim_blq)),0,2));

      for ($i=$k; $i<=$l; $i++) {
        $dados[$cont][$i+2] = 'bgcolor="grey" title="Bloqueado'.((nvl(f($row,'motivo_bloqueio'),'')!='') ? ': '.f($row,'motivo_bloqueio') : '').'"';
      }
    }
  }

  if ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif ($w_tipo=='EXCEL') {
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($w_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf($w_TP,$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } else {
      BodyOpenClean('onLoad=\'this.focus();\'');
    } 
    if ($O=='L') {
      CabecalhoRelatorio($w_cliente,$w_TP,4);
      ShowHTML('<HR>');
      if ($w_filtro>'') ShowHTML($w_filtro);
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
      ShowHTML('<HR>');
    } 
  } 
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td><TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('  <tr valign="top">');
  ShowHTML('    <td>'.(($w_embed=='WORD') ? '' : '<A class="hl" HREF="'.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes1.'&w_ano='.$w_ano1.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET')).'"><<<</A>'));
  ShowHTML('    <td align="center"><b>'.upper(nomeMes($w_mes)).'/'.$w_ano.'</b></td>');
  ShowHTML('    <td align="right">'.(($w_embed=='WORD') ? '' : '<A class="hl" HREF="'.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes3.'&w_ano='.$w_ano3.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET')).'">>>></A>'));
  ShowHTML('  </tr>');
  ShowHTML('</table>');
  ShowHTML('<tr><td><TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="1" CELLSPACING="0" CELLPADDING="0" BorderColorDark="'.$conTableBorderColorLight.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('  <tr align="center"><td><font size="1">Linha</font></td>');
  $w_atual = $w_inicio;
  for ($i=1; $i<=$w_dias; $i++) {
    echo('<td nowrap><font size="1">'.substr(formataDataEdicao($w_atual),0,2).'</font></td>');
    $w_atual = addDays($w_atual,1);
  }
  ShowHTML('</tr>');

  foreach($dados as $row) {
    ShowHTML('  <tr>');
    echo('<td align="center"><font size="1"><b>'.$row[1].'</b></font></td>');
    $w_atual = $w_inicio;
    for ($i=3; $i<=($w_dias+2); $i++) {
      echo('<td '.$row[$i].'><font size="1">&nbsp;</font></td>');
    }
    $w_atual = addDays($w_atual,1);
  }
  ShowHTML('</table>');
  if($w_tipo == 'PDF') RodapePdf();
  else Rodape();
} 

// =========================================================================
// Rotina de dados da entrega do celular ao beneficiário
// -------------------------------------------------------------------------
function EntregaCelular() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave     = $_REQUEST['w_chave'];
  $w_sq_menu   = $_REQUEST['w_sq_menu'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  $w_tipo      = Nvl($_REQUEST['w_tipo'],'');

  if ($P1=='6') $w_envio = 'S';
  
  // Recupera os dados da solicitação
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,'SRSOLCEL');
  $w_inicio = formataDataEdicao(f($RS_Solic,'inicio'));
  $w_fim    = formataDataEdicao(f($RS_Solic,'fim'));
  
  $w_readonly       = '';

  if ($w_troca>'') {
    // Se for recarga da página
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_observacao       = $_REQUEST['w_observacao'];
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
  } else {
    $w_inicio           = FormataDataEdicao(f($RS_Solic,'inicio'));
    $w_observacao       = crlf2br(f($RS_Solic,'observacao'));
    $w_tramite          = f($RS_Solic,'sq_siw_tramite');
  }
  
  $w_envio = nvl($w_envio,'N');
  
  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');

  //Verifica a fase anterior para a caixa de seleção da fase.
  $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,null,'ANTERIOR',null);
  foreach($RS as $row) { $RS = $row; break; }
  $w_novo_tramite = f($RS,'sq_siw_tramite');

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();  
  ShowHTML('  function texto(linha) {');
  ShowHTML('    document.Form.w_acessorios.value=document.Form["w_texto["+linha+"]"].value;');
  ShowHTML('  }');
  ValidateOpen('Validacao');
  if (nvl($w_envio,'')=='S') {
    Validate('w_despacho','Despacho','1','1','1','2000','1','1');
  } else {
    Validate('w_inicio','Início do empréstimo','DATA',1,10,10,'','0123456789/');       
    Validate('w_observacao','Observações','','',4,1000,'1','1');
  }
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_inicio.focus();\'');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,'N');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS_Solic,'sq_siw_tramite').'">');

  ShowHTML('<tr><td>');
  ShowHTML('    <tr><td><table width="100%" border="0"><tr><td>');
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,'SRSOLCEL',null));
  ShowHTML('      <tr valign="top"><td colspan="3">');
  ShowHTML('        <input class="STR" '.(($P1==6) ? 'DISABLED' : '').' type="radio" name="w_envio" value="N" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.'\'; document.Form.O.value=\''.$O).'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();"'.((Nvl($w_envio,'N')=='N') ? ' checked' : '').'> Enviar para a próxima fase <br>');
  ShowHTML('        <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.'\'; document.Form.O.value=\''.$O).'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();"'.((Nvl($w_envio,'N')=='S') ? ' checked' : '').'> Devolver para a fase anterior');
  ShowHTML('  <tr><td colspan="3"><font size="2"><hr NOSHADE color=#000000 SIZE=1></font></td></tr>');
  if (Nvl($w_envio,'N')=='N') {
    ShowHTML('      <tr><td><b>Iní<u>c</u>io do empréstimo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Início previsto da demanda.">'.ExibeCalendario('Form','w_inicio').'</td>'); 
    ShowHTML('      <tr><td><b><u>O</u>bservações:</b><br><textarea '.$w_Disabled.' accesskey="C" name="w_observacao" class="STI" ROWS=5 cols=75 title="Registre observações gerais a respeito da entrega, caso desejado.">'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('  <tr><td colspan="3"><font size="2"><hr NOSHADE color=#000000 SIZE=1></font></td></tr>');
  }
  if ($w_envio=='S') {
    SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','DEVFLUXO',null);
    ShowHTML('    <tr><td colspan="3"><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o motivo da devolução e ações a serem executadas.">'.$w_despacho.'</TEXTAREA></td>');
  }
  ShowHTML('      <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=3><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  Rodape();
} 

// =========================================================================
// Rotina de dados da devolução do celular
// -------------------------------------------------------------------------
function DevolCelular() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_sq_menu    = $_REQUEST['w_sq_menu'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');

  if ($P1=='6') $w_envio = 'S';
  
  $w_readonly       = '';
  $w_erro           = '';

  // Recupera os dados da solicitação
  $sql      = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  $w_inicio = formataDataEdicao(f($RS_Solic,'inicio'));
  $w_fim    = formataDataEdicao(f($RS_Solic,'fim'));

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_pendencia        = $_REQUEST['w_pendencia'];
    $w_bloqueio         = $_REQUEST['w_bloqueio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_acessorios       = $_REQUEST['w_acessorios'];
    $w_observacao       = $_REQUEST['w_observacao'];
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
  } else {
    $w_pendencia        = f($RS_Solic,'pendencia');
    $w_bloqueio         = f($RS_Solic,'bloqueado');
    $w_fim              = FormataDataEdicao(f($RS_Solic,'fim'));
    $w_acessorios       = f($RS_Solic,'acessorios_pendentes');
    $w_observacao       = ($w_bloqueio=='S') ? crlf2br(f($RS_Solic,'motivo_bloqueio')) : crlf2br(f($RS_Solic,'descricao'));
    $w_tramite          = f($RS_Solic,'sq_siw_tramite');
  } 

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  ShowHTML('  function pendencia() {');
  ShowHTML('    var theForm = document.Form; ');
  ShowHTML('    if (theForm.w_pendencia[0].checked) {');
  ShowHTML('      theForm.w_acessorios.className="STIO";');
  ShowHTML('      theForm.w_acessorios.focus();');
  ShowHTML('    } else {');
  ShowHTML('      theForm.w_acessorios.className="STI";');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  function bloqueio() {');
  ShowHTML('    var theForm = document.Form; ');
  ShowHTML('    if (theForm.w_bloqueio[0].checked) {');
  ShowHTML('      theForm.w_observacao.className="STIO";');
  ShowHTML('      theForm.w_observacao.focus();');
  ShowHTML('    } else {');
  ShowHTML('      theForm.w_observacao.className="STI";');
  ShowHTML('    }');
  ShowHTML('  }');
  ValidateOpen('Validacao');
  if (nvl($w_envio,'')=='S') {
    Validate('w_despacho','Despacho','1','1','1','2000','1','1');
  } else {
    Validate('w_fim','Devolução do aparelho','DATA',1,10,10,'','0123456789/');
    Validate('w_acessorios','Acessórios pendentes','','',4,1000,'1','1');
    ShowHTML('  if (theForm.w_pendencia[0].checked && theForm.w_acessorios.value=="") {');
    ShowHTML('    alert("Informe a lista de acessórios pendentes!")');
    ShowHTML('    theForm.w_acessorios.focus();');
    ShowHTML('    return false;');
    ShowHTML('  } else if (theForm.w_pendencia[1].checked && theForm.w_acessorios.value!="") {');
    ShowHTML('    alert("Lista de acessórios pendentes deve ser informada apenas se houver pendências!")');
    ShowHTML('    theForm.w_acessorios.focus();');
    ShowHTML('    return false;');
    ShowHTML('  }');
    Validate('w_observacao','Observações','','',4,1000,'1','1');
    ShowHTML('  if (theForm.w_bloqueio[0].checked && theForm.w_observacao.value=="") {');
    ShowHTML('    alert("Informe o motivo do bloqueio para novos empréstimos!")');
    ShowHTML('    theForm.w_observacao.focus();');
    ShowHTML('    return false;');
    ShowHTML('  }');
  }
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();'.(($w_envio=='N') ?  ' pendencia(); bloqueio();' : '').'"');
  } elseif ($w_envio=='S') {
    BodyOpen('onLoad="document.Form.w_novo_tramite.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_fim.focus(); pendencia(); bloqueio();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,'D');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS_Solic,'sq_siw_tramite').'">');
  
  ShowHTML('<tr><td>');
  ShowHTML('    <tr><td><table width="100%" border="0"><tr><td>');
  ShowHTML(VisualGeral($w_chave,'V',$w_usuario,'SRSOLCEL',null));
  ShowHTML('      <tr valign="top"><td colspan="3">');
  ShowHTML('        <input class="STR" '.(($P1==6) ? 'DISABLED' : '').' type="radio" name="w_envio" value="N" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.'\'; document.Form.O.value=\''.$O).'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();"'.((Nvl($w_envio,'N')=='N') ? ' checked' : '').'> Enviar para a próxima fase <br>');
  ShowHTML('        <input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S" onClick="document.Form.action=\''.montaURL_JS($w_dir,$w_pagina.$par.'\'; document.Form.O.value=\''.$O).'\'; document.Form.w_troca.value=\'w_assinatura\'; document.Form.submit();"'.((Nvl($w_envio,'N')=='S') ? ' checked' : '').'> Devolver para a fase anterior');
  ShowHTML('  <tr><td colspan="3"><font size="2"><hr NOSHADE color=#000000 SIZE=1></font></td></tr>');
  if (Nvl($w_envio,'N')=='N') {
    ShowHTML('<tr><td>');
    ShowHTML('    <tr><td><table width="100%" border="0" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('      <tr><td colspan="3"><font size="2"><b>DADOS DA DEVOLUÇÃO DO CELULAR<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data de devolução do aparelho.">'.ExibeCalendario('Form','w_fim').'</td>'); 
    ShowHTML('      <tr valign="top">');
    MontaRadioNS('<b>Pendência de acessórios?</b>',$w_pendencia,'w_pendencia',null,null,'onClick="pendencia()"');
    ShowHTML('        <td><b>A<u>c</u>essórios pendentes:</b><br><textarea '.$w_Disabled.' accesskey="C" name="w_acessorios" class="STI" ROWS=5 cols=75 title="Relacione os acessórios pendentes de devolução, se for o caso.">'.$w_acessorios.'</TEXTAREA></td>');
    ShowHTML('      <tr valign="top">');
    MontaRadioNS('<b>Aparelho bloqueado para novos empréstimos?</b>',$w_bloqueio,'w_bloqueio',null,null,'onClick="bloqueio()"','2');
    ShowHTML('      <tr><td colspan="3"><b><u>O</u>bservações/motivo do bloqueio:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75 title="Registre observações gerais a respeito da devolução, caso desejado.">'.$w_observacao.'</TEXTAREA></td>');
  }
  if ($w_envio=='S') {
    SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','DEVFLUXO',null);
    ShowHTML('    <tr><td colspan="3"><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o motivo da devolução e ações a serem executadas.">'.$w_despacho.'</TEXTAREA></td>');
  }
  ShowHTML('      <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=3><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
  } elseif ($SG=='SRSOLCEL') {
    include_once('celular_conc.php');
  } else {
    include_once('geral_conc.php');
  }
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
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $sql = new db_getSolicData; $RSM = $sql->getInstanceOf($dbms,$p_solic,(($SG=='ANALCEL') ? 'SRSOLCEL' : $SG));
  if(f($RS,'envia_mail_tramite')=='S' && f($RS_Menu,'envia_email')=='S' && f($RSM,'envia_mail')=='S') {
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
    //  $w_html.='      <tr valign="top"><td align="center"><font size=2><b>'.upper($w_nome).'</b><br><br><td></tr>'.$crlf;
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
    if (nvl(f($RSM,'justificativa'),'')!='') {
      $w_html.=$crlf.'      <tr><td><b>Justificativa:</b> ';
      $w_html.=$crlf.'        <td>'.CRLF2BR(f($RSM,'justificativa')).'</td></tr>';
    }

    if ($SG=='SRTRANSP') {
      $w_html.=$crlf.'      <tr><td><b>Destino:</b>';
      $w_html.=$crlf.'        <td>'.CRLF2BR(f($RSM,'destino')).'</td></tr>';
      $w_html.=$crlf.'      <tr><td><b>Qtd. Pessoas:</b> ';
      $w_html.=$crlf.'        <td>'.f($RSM,'qtd_pessoas').'</td>';
      $w_html.=$crlf.'      <tr><td><b>Carga: </b>';
      $w_html.=$crlf.'        <td>'.RetornaSimNao(f($RSM,'carga')).'</td></tr>';
    } elseif ($SG=='ANALCEL') {
      $w_html.=$crlf.'   <tr><td colspan="2"><br><font size="2"><b>CELULAR DISPONÍVEL PARA ENTREGA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=$crlf.'   <tr><td><b>Número da linha:</b></td><td>'.f($RSM,'numero_linha').'</td></tr>';
      $w_html.=$crlf.'   <tr><td><b>Acessórios:</b></td><td>'.f($RSM,'acessorios_entregues').'</td></tr>';
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
    $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null,'LISTA');
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
    } elseif (nvl(f($RS,'observacao'),'')!='') {
      $w_html.=$crlf.'            <li><b>'.CRLF2BR(f($RS,'observacao')).' </b>';
    }
    $w_html.=$crlf.'            <li><b>Data: </b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</li>'.$crlf;
    $w_html.=$crlf.'            <li><b>IP de origem: </b>'.$_SERVER['REMOTE_ADDR'].'</li>'.$crlf;
    $w_html.=$crlf.'            </ul>'.$crlf;
    $w_html.=$crlf.'          </table>';
    $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRAS INFOMAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $sql = new db_getCustomerSite; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    $w_html.='      <tr valign="top"><td>'.$crlf;
    $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='    </table>'.$crlf;
    $w_html.='</td></tr>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</BODY>'.$crlf;
    $w_html.='</HTML>'.$crlf;

    // Configura os destinatários da mensagem
    if ($p_tipo==2) {
      // Se for tramitação, envia e-mail para os responsáveis pelo seu cumprimento
      $sql = new db_getTramiteResp; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null);
      if (count($RS)>0) {
        foreach($RS as $row) {
          $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
        } 
      } 
    } elseif ($p_tipo==3) {
      if(f($RSM,'st_sol')=='S') {
        // Se for conclusão, envia e-mail ao solicitante comunicando a necessidade de informar sua opinião
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
        $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
      }
    } elseif ($p_tipo==4) {
      // Se for comunicado de insatisfação, envia e-mail para os responsáveis pelo cumprimento do trâmite "Em execução".
      $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,f($RSM,'sq_siw_tramite'),null,'ANTERIOR',null);
      foreach($RS as $row) { $RS = $row; break; }
      $sql = new db_getTramiteResp; $RS = $sql->getInstanceOf($dbms,$p_solic,f($RS,'sq_siw_tramite'),null);
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');

  BodyOpen('onLoad=this.focus();');
  if (strpos('IAE',$O)!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se for operação de exclusão, verifica se é necessário excluir os arquivos físicos
      if ($O=='E' && f($RS_Menu,'cancela_sem_tramite')=='N') {
        $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],null,null,'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e não excluída.
        // Nessa situação, não é necessário excluir os arquivos.
        if (count($RS)<=1) {
          $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
          foreach($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          } 
        } 
      } 
      if ($SG=='SRTRANSP') {
        include_once($w_dir_volta.'classes/sp/dml_putSolicTransp.php');
        $SQL = new dml_putSolicTransp; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],
            $_SESSION['SQ_PESSOA'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],
            $_REQUEST['w_data_hora'], $_REQUEST['w_cidade'], $_REQUEST['w_destino'],$_REQUEST['w_sq_veiculo'],$_REQUEST['w_qtd_pessoas'],
            $_REQUEST['w_procedimento'], $_REQUEST['w_carga'], &$w_chave_nova, $w_copia);
      } elseif ($SG=='SRSOLCEL') {
        include_once($w_dir_volta.'classes/sp/dml_putSolicCelular.php');
        $SQL = new dml_putSolicCelular; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_REQUEST['w_sq_unidade'],$_REQUEST['w_solicitante'],
            $_SESSION['SQ_PESSOA'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],
            $_REQUEST['w_data_hora'], $_REQUEST['w_cidade'], $_REQUEST['w_pais'], &$w_chave_nova, $w_copia);
      } else {
        include_once($w_dir_volta.'classes/sp/dml_putSolicGeral.php');
        if (nvl($_REQUEST['w_solic_recurso'],'')!='' && $O=='E') {
          // Grava o cabeçalho da alocação
          $SQL = new dml_putSolicRecurso; $SQL->getInstanceOf($dbms,$O,$w_usuario, $w_chave_nova, $_REQUEST['w_solic_recurso'],
                '0',$_REQUEST['w_recurso'],nvl($_REQUEST['w_descricao'],$_REQUEST['w_justificativa']), null, null, null);
        }
        $SQL = new dml_putSolicGeral; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],
            $_SESSION['SQ_PESSOA'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],
            $_REQUEST['w_data_hora'], $_REQUEST['w_cidade'], &$w_chave_nova, $w_copia);
        
        if (nvl($_REQUEST['w_recurso'],'')!='') {
          // Grava o cabeçalho da alocação
          $SQL = new dml_putSolicRecurso; $SQL->getInstanceOf($dbms,$O,$w_usuario, $w_chave_nova, $_REQUEST['w_solic_recurso'],
                '0',$_REQUEST['w_recurso'],nvl($_REQUEST['w_descricao'],$_REQUEST['w_justificativa']), null, null, null);
        }
      }

      if (nvl($_REQUEST['w_envio'],'N')=='S') {
        $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$w_chave_nova,$w_usuario,$_REQUEST['w_tramite'],null,
          'N',null,null,null,null,null);
      }
      
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='F') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Verifica se outro usuário já enviou a solicitação
      if ($SG=='SRTRANSP') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!");');
          ScriptClose();
          retornaFormulario('');
          exit();
        }
        include_once($w_dir_volta.'classes/sp/dml_putSolicInfTransp.php');
        $SQL = new dml_putSolicInfTransp; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,
              $_REQUEST['w_executor'],$_REQUEST['w_sq_veiculo']);
      } elseif ($SG=='ANALCEL') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],'SRSOLCEL');
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!");');
          ScriptClose();
          retornaFormulario('');
          exit();
        }
        // Verifica se o celular está disponível para vinculação
        $w_inicio = formataDataEdicao(f($RS,'inicio'));
        $w_fim    = formataDataEdicao(f($RS,'fim'));
        $sql = new db_getCelular; $RS_Celular = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_celular'],null,null,null,'S',$_REQUEST['w_chave'],$w_inicio,$w_fim,'MAPAFUTURO');
        foreach($RS_Celular as $row) { $RS_Celular = $row; break; }
        if (nvl(f($RS_Celular,'sq_siw_solicitacao'),$_REQUEST['w_chave'])!=$_REQUEST['w_chave']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já vinculou o celular à solicitação '.nvl(f($RS_Celular,'codigo_interno'),f($RS_Celular,'sq_siw_solicitacao')).'!");');
          ScriptClose();
          retornaFormulario('');
          exit();
        }

        include_once($w_dir_volta.'classes/sp/dml_putSolicInfCelular.php');
        $SQL = new dml_putSolicInfCelular; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,
              $_REQUEST['w_celular'],$_REQUEST['w_acessorios']);
      
        // Verifica o próximo trâmite
        if ($_REQUEST['w_envio']=='N') {
          $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite'],null,'PROXIMO',null);
        } else {
          $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite'],null,'ANTERIOR',null);
        } 
        foreach($RS as $row) { $RS = $row; break; }
        $sql = new db_getTramiteSolic; $RS1 = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS,'sq_siw_tramite'),null,null);
        if (count($RS1)<=0) {
          foreach($RS1 as $row) { $RS1 = $row; break; }
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Não há nenhuma pessoa habilitada a cumprir o trâmite "'.f($RS,'nome').'"!\');');
          ScriptClose();
          retornaFormulario('w_assinatura');
          exit();
        } 
        if ($_REQUEST['w_envio']=='N') {
          $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
            $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
        } else {
          $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
            $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
        } 
        // Envia mail avisando sobre a tramitação da solicitação
        SolicMail($_REQUEST['w_chave'],2);
      } else {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!");');
          ScriptClose();
          retornaFormulario('');
          exit();
        }
        include_once($w_dir_volta.'classes/sp/dml_putSolicInfGeral.php');
        $SQL = new dml_putSolicInfGeral; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,
              $_REQUEST['w_executor'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_valor']);
      }

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='N') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Verifica se outro usuário já enviou a solicitação
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],'SRSOLCEL');
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!");');
        ScriptClose();
        retornaFormulario('');
        exit();
      } else {
        if ($SG=='ENTCEL') {
          include_once($w_dir_volta.'classes/sp/dml_putSolicEntCelular.php');
          $SQL = new dml_putSolicEntCelular; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,
                $_REQUEST['w_inicio'],$_REQUEST['w_observacao']);
        }

        // Verifica o próximo trâmite
        if ($_REQUEST['w_envio']=='N') {
          $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite'],null,'PROXIMO',null);
        } else {
          $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite'],null,'ANTERIOR',null);
        } 
        foreach($RS as $row) { $RS = $row; break; }
        $sql = new db_getTramiteSolic; $RS1 = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS,'sq_siw_tramite'),null,null);
        if (count($RS1)<=0) {
          foreach($RS1 as $row) { $RS1 = $row; break; }
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Não há nenhuma pessoa habilitada a cumprir o trâmite "'.f($RS,'nome').'"!\');');
          ScriptClose();
          retornaFormulario('w_assinatura');
          exit();
        } 
        
        if ($_REQUEST['w_envio']=='N') {
          $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
            $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
        } else {
          $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
            $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
        } 

        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='D') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Verifica se outro usuário já enviou a solicitação
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!");');
        ScriptClose();
        retornaFormulario('');
        exit();
      } else {
        if ($SG=='SRSOLCEL') {
          include_once($w_dir_volta.'classes/sp/dml_putSolicDevCelular.php');
          $SQL = new dml_putSolicDevCelular; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,
                $_REQUEST['w_fim'],$_REQUEST['w_pendencia'],$_REQUEST['w_acessorios'],$_REQUEST['w_bloqueio'],$_REQUEST['w_observacao']);
        }

        // Verifica o próximo trâmite
        if ($_REQUEST['w_envio']=='N') {
          $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite'],null,'PROXIMO',null);
        } else {
          $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite'],null,'ANTERIOR',null);
        } 
        foreach($RS as $row) { $RS = $row; break; }
        $sql = new db_getTramiteSolic; $RS1 = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS,'sq_siw_tramite'),null,null);
        if (count($RS1)<=0) {
          foreach($RS1 as $row) { $RS1 = $row; break; }
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Não há nenhuma pessoa habilitada a cumprir o trâmite "'.f($RS,'nome').'"!\');');
          ScriptClose();
          retornaFormulario('w_assinatura');
          exit();
        } 
        $w_despacho = $_REQUEST['w_despacho'];
        if ($_REQUEST['w_envio']=='N' && $SG=='SRSOLCEL' && $_REQUEST['w_pendencia']==='S' && $_REQUEST['w_acessorios']!='') {
          // Se o celular foi devolvido com pendência de acessórios, grava no log de envio
          $w_despacho = $crlf.'Pendências: '.$_REQUEST['w_acessorios'];
        }
        if ($_REQUEST['w_envio']=='N') {
          $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
            $_REQUEST['w_envio'],$w_despacho,null,null,null,null);
        } else {
          $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
            $_REQUEST['w_envio'],$w_despacho,null,null,null,null);
        } 

        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='O') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Verifica se outro usuário já emitiu opinião sobre o atendimento
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (nvl(f($RS,'opiniao'),'')!='') {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já emitiu opinião sobre este atendimento!\');');
        ScriptClose();
      } else {
        // Recupera a chave da opinião emitida
        $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,null,$_REQUEST['w_opiniao'],$restricao);
        foreach($RS as $row) { $RS = $row; break; }

        // Grava a opinião do solicitante
        $SQL = new dml_putSolicOpiniao; $SQL->getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS,'chave'),$_REQUEST['w_motivo']);
        
        // Se o solicitante ficou insatisfeito, envia e-mail para a área responsável pelo atendimento.
        if ($_REQUEST['w_opiniao']=='IN') SolicMail($_REQUEST['w_chave'],4);
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='V') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ((false!==(strpos(upper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(upper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
        // Verifica se outro usuário já enviou a solicitação
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
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
            $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
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
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!\');');
          ScriptClose();
          retornaFormulario('w_observacao');
          exit();
        } else {
          // Verifica o próximo trâmite
          if ($_REQUEST['w_envio']=='N') {
            $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite'],null,'PROXIMO',null);
          } else {
            $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite'],null,'ANTERIOR',null);
          } 
          foreach($RS as $row) { $RS = $row; break; }
          $sql = new db_getTramiteSolic; $RS1 = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS,'sq_siw_tramite'),null,null);
          if (count($RS1)<=0) {
            foreach($RS1 as $row) { $RS1 = $row; break; }
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: Não há nenhuma pessoa habilitada a cumprir o trâmite "'.f($RS,'nome').'"!\');');
            ScriptClose();
            retornaFormulario('w_assinatura');
            exit();
          } 
          if ($_REQUEST['w_envio']=='N') {
            $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
              $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
          } else {
            $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
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
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($O=='C') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
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
          $SQL = new dml_putSolicConcTransp; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_recebedor'],$_REQUEST['w_tramite'],$_REQUEST['w_executor'],$_REQUEST['w_observacao'],$_REQUEST['w_valor'],
              $w_file,$w_tamanho,$w_tipo,$w_nome,$_REQUEST['w_sq_veiculo'], $_REQUEST['w_hodometro_saida'], $_REQUEST['w_hodometro_chegada'], $_REQUEST['w_horario_saida'], $_REQUEST['w_horario_chegada'], $_REQUEST['w_parcial']);
        } else {
          include_once($w_dir_volta.'classes/sp/dml_putSolicConc.php');
          $SQL = new dml_putSolicConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_fim'],$_REQUEST['w_executor'],$_REQUEST['w_observacao'],nvl($_REQUEST['w_valor'],'0,00'),
              $w_file,$w_tamanho,$w_tipo,$w_nome,null,null,null,null,null,null);
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
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
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
  if ($P1==1 && nvl(f($RS_Menu,'sq_unid_executora'),'')=='') {
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
  case 'INICIAL':           Inicial();            break;
  case 'GERAL':             Geral();              break;
  case 'VISUAL':            Visual();             break;
  case 'EXCLUIR':           Excluir();            break;
  case 'OPINIAO':           Opiniao();            break;
  case 'DADOSEXECUCAO':     DadosExecucao();      break;
  case 'ANALISECELULAR':    AnaliseCelular();     break;
  case 'TERMOCELULAR':      TermoCelular();       break;
  case 'EMITETERMOCELULAR': EmiteTermoCelular();  break;
  case 'ENTREGACELULAR':    EntregaCelular();     break;
  case 'DEVOLCELULAR':      DevolCelular();       break;
  case 'DISPCELULAR':       DispCelular();        break;
  case 'ENVIO':             Encaminhamento();     break;
  case 'ANOTACAO':          Anotar();             break;
  case 'EMITEOS':           EmiteOS();            break;
  case 'INFORMAR':          Informar();           break;
  case 'CONCLUIR':          Concluir();           break;
  case 'GRAVA':             Grava();              break;
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
