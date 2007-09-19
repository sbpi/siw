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
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getSolicObjetivo.php');
include_once($w_dir_volta.'classes/sp/db_getMatServ.php');
include_once($w_dir_volta.'classes/sp/db_getCLSolicItem.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putCLGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicConc.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putCLSolicItem.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'funcoes/retornaCadastrador_CL.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
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
include_once('visualpedido.php');
include_once('validapedido.php');

// =========================================================================
//  /pedido.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o seviço de pedido de compra
// Mail     : celso@sbpi.com.br
// Criacao  : 24/08/2007, 11:00
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
$w_pagina       = 'pedido.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_cl/';
$w_troca        = $_REQUEST['w_troca'];

if (strpos($SG,'ENVIO')!==false) {
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
$w_cadgeral = RetornaCadastrador_CL($w_menu, $w_usuario);

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
// Rotina de listagem dos pedidos
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;

  if ($O=='L') {
    if ((strpos(strtoupper($R),'GR_')!==false) || ($w_tipo=='WORD')) {
      $w_filtro='';

      if (nvl($p_solic_pai,'')!='') {
      $RS = db_getSolicCL::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),5,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null, null, $p_servico);
        if($w_tipo=='WORD') $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.exibeSolic($w_dir,$p_projeto,f($RS,'dados_solic'),'S','S').'</b>]';
        else                $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.exibeSolic($w_dir,$p_projeto,f($RS,'dados_solic'),'S').'</b>]';
      } elseif (nvl($p_servico,'')!='') {
        if ($p_servico=='CLASSIF') {
          $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>Apenas pedidos com classificação</b>]';
        } else {
          $RS = db_getMenuData::getInstanceOf($dbms,$p_servico);
          $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.f($RS,'nome').'</b>]';
        }
      } elseif (nvl($_REQUEST['p_agrega'],'')=='GRPRVINC') {
        $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>Apenas pedidos com vinculação</b>]';
      } elseif (nvl($p_chave,'')!='') {
        $RS = db_getSolicCL::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),5,
                  $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                  $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                  $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                  $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null, null, $p_servico);
        $w_filtro.='<tr valign="top"><td align="right">Pedido <td>[<b>'.f($RS,'codigo_interno').'</b>]';
      } 
      if ($p_prazo>'') $w_filtro.=' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_codigo>'')  $w_filtro .= '<tr valign="top"><td align="right"><td>[<b>'.$p_codigo.'</b>]';
      if ($p_solicitante>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Solicitante <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Setor solicitante <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro.='<tr valign="top"><td align="right">Conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($w_filtro>'')     $w_filtro  ='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as PCDs visíveis pelo usuário
      $RS = db_getSolicCL::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null, null, $p_servico);
    } else {
      $RS = db_getSolicCL::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null, null, $p_servico);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'ordem','asc', 'fim', 'desc', 'prioridade', 'asc');
    } else {
      $RS = SortArray($RS,'ordem','asc', 'fim', 'desc', 'prioridade', 'asc');
    }
  }
  if ($w_tipo=='WORD') {
    HeaderWord();
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de Viagens</TITLE>');
    ShowHTML('</HEAD>');
  } else {
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
        Validate('p_codigo','Número da pedido','','','2','60','1','1');
        Validate('p_ini_i','Início','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Fim','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
          ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Início','<=','p_ini_f','Fim');
      } 
      Validate('P4','Linhas por página','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_Troca>'') {
    // Se for recarga da página
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
      // Se for cadastramento e não for resultado de busca para cópia
      if ($w_tipo!='WORD') { 
        ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;'); 
        ShowHTML('    <a accesskey="C" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
      }
    } 
    if ((strpos(strtoupper($R),'GR_'))===false && $P1!=6 && $w_tipo!='WORD') {
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
    if ($w_tipo!='WORD') {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Código','codigo_interno').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML ('          <td rowspan=2><b>'.LinkOrdena('Vinculação','dados_pai').'</td>');
      ShowHTML('          <td colspan=2><b>Solicitante</td>');
      if ($P1==1 || $P1==2) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td colspan=2><b>Datas</td>');
      } else {
        ShowHTML('          <td colspan=2><b>Execução</td>');
        if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Valor','valor').'</td>');
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      } 
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Judicial','decisao_judicial').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan=2><b>Operações</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('Pessoa','nm_solic').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Setor','nm_unidade_solic').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Inclusão','inicio').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Limite','fim').'</td>');
      ShowHTML('        </tr>');
      
    } else {
      ShowHTML('          <td rowspan=2><b>Código</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML ('          <td rowspan=2><b>Vinculação</td>');
      ShowHTML('          <td rowspan=2><b>Solicitante</td>');
      ShowHTML('          <td rowspan=2><b>Setor solicitante</td>');
      if ($P1==1 || $P1==2) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td colspan=2><b>Execução</td>');
      } else {
        ShowHTML('          <td colspan=2><b>Execução</td>');
        if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan=2><b>Valor</td>');
        ShowHTML('          <td rowspan=2><b>Fase atual</td>');
      } 
      ShowHTML('          <td rowspan=2><b>Judicial</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>De</td>');
      ShowHTML('          <td><b>Até</td>');
      ShowHTML('        </tr>');    
    }
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),null,null,f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
        if ($_SESSION['INTERNO']=='S') {
           if (Nvl(f($row,'dados_pai'),'')!='') ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai')).'</td>');
          else                                 ShowHTML('        <td>---</td>');
        } 
        ShowHTML('        <td>'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>');
        ShowHTML('        <td>'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade'),$TP).'</td>');
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'inicio'),5).'</td>');
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'fim'),5).'</td>');
        if ($P1!=1 && $P1!=2) {
          if ($_SESSION['INTERNO']=='S') ShowHTML('        <td align="right">'.formatNumber(f($row,'valor'),4).'&nbsp;</td>');
          $w_parcial += f($row,'valor');
          ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        } 
        ShowHTML('        <td align="center" nowrap>'.RetornaSimNao(f($row,'decisao_judicial')).'</td>');
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
              ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'codigo_interno').MontaFiltro('GET').'" title="Altera as informações cadastrais do pedido" TARGET="menu">AL</a>&nbsp;');
            } else {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais do pedido">AL</A>&nbsp');
            } 
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão do pedido.">EX</A>&nbsp');
            ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Itens'.'&SG='.substr($SG,0,4).'ITEM').'\',\'Itens\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Escolhe os itens da compra.">Itens</A>&nbsp');
            ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Anexos&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Anexos'.'&SG='.substr($SG,0,4).'ANEXO').'\',\'Anexos\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Vincula arquivos ao pedido de compra.">Anexos</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento do pedido">EN</A>&nbsp');
          } elseif ($P1==2) {
            if (f($row,'sg_tramite')=='EE') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a solicitação, sem enviá-la.">AN</A>&nbsp');
            } 
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicitação para outro responsável.">EN</A>&nbsp');
            if (f($row,'sg_tramite')=='EE') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Autorizar compra.">AT</A>&nbsp');
            } 
          } 
        } else {
          if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o pedido para outro responsável.">EN</A>&nbsp');
          } else {
            ShowHTML('          ---&nbsp');
          } 
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
      $w_colspan=6;
      if ($P1!=1 && $P1!=2) {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan='.$w_colspan.' align="right"><b>Total desta página&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.formatNumber($w_parcial).'&nbsp;</td>');
          ShowHTML('          <td colspan=4>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          reset($RS);
          foreach($RS as $row) {
            $w_total += f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan='.$w_colspan.' align="right"><b>Total da listagem&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.formatNumber($w_total).'&nbsp;</td>');
          ShowHTML('          <td colspan=4>&nbsp;</td>');
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
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar o pedido que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
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
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      ShowHTML('   <tr valign="top">');
      ShowHTML('     <td valign="top"><b>Número do <U>p</U>edido:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_codigo" size="20" maxlength="60" value="'.$p_codigo.'"></td>');
      ShowHTML('   <tr valign="top">');
      SelecaoPessoa('<u>S</u>olicitante:','N','Selecione o solicitante do peiddo na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$p_unidade,null,'p_unidade','CLCP',null);
      ShowHTML('   <tr>');
      ShowHTML('     <td valign="top"><b><u>D</u>ata de recebimento e limite para atendimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
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
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar cópia">');
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
  
  // Verifica se o cliente tem o módulo de planejamento estratégico
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'PE');
  if (count($RS)>0) $w_pe='S'; else $w_pe='N'; 


  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_sq_menu_relac            = $_REQUEST['w_sq_menu_relac'];    
    if($w_sq_menu_relac=='CLASSIF') {
      $w_solic_pai              = '';
    } else {
      $w_solic_pai              = $_REQUEST['w_solic_pai'];
    }
    $w_codigo                   = $_REQUEST['w_codigo'];
    $w_chave_pai                = $_REQUEST['w_chave_pai'];    
    $w_plano                    = $_REQUEST['w_plano'];
    $w_sqcc                     = $_REQUEST['w_sqcc'];
    $w_objetivo                 = explodeArray($_REQUEST['w_objetivo']);
    $w_prioridade               = $_REQUEST['w_prioridade'];
    $w_aviso                    = $_REQUEST['w_aviso'];
    $w_dias                     = $_REQUEST['w_dias'];
    $w_chave_pai                = $_REQUEST['w_chave_pai'];
    $w_chave_aux                = $_REQUEST['w_chave_aux'];
    $w_sq_menu                  = $_REQUEST['w_sq_menu'];
    $w_sq_unidade               = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite               = $_REQUEST['w_sq_tramite'];
    $w_solicitante              = $_REQUEST['w_solicitante'];
    $w_cadastrador              = $_REQUEST['w_cadastrador'];
    $w_executor                 = $_REQUEST['w_executor'];
    $w_inicio                   = $_REQUEST['w_inicio'];
    $w_fim                      = $_REQUEST['w_fim'];
    $w_inclusao                 = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao         = $_REQUEST['w_ultima_alteracao'];
    $w_justificativa            = $_REQUEST['w_justificativa'];
    $w_observacao               = $_REQUEST['w_observacao'];
    $w_decisao_judicial         = $_REQUEST['w_decisao_judicial'];      
    $w_numero_original          = $_REQUEST['w_numero_original'];
    $w_data_recebimento         = $_REQUEST['w_data_recebimento'];
    $w_cidade                   = $_REQUEST['w_cidade'];
  } else {
    if (strpos('AEV',$O)!==false || $w_copia>'') {
      // Recupera os dados do pedido
      if ($w_copia>'') {
      $RS = db_getSolicCL::getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
      } else {
      $RS = db_getSolicCL::getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
      }
      if (count($RS)>0) {
        foreach($RS as $row){$RS=$row; break;}
        $w_codigo               = f($RS,'codigo_interno');
        $w_plano                = f($RS,'sq_plano');
        $w_dados_pai            = explode('|@|',f($RS,'dados_pai'));
        $w_sq_menu_relac        = $w_dados_pai[3];
        $RS1 = db_getSolicObjetivo::getInstanceOf($dbms,$w_chave,null,null);
        $RS1 = SortArray($RS1,'nome','asc');
        $w_objetivo = '';
        foreach($RS1 as $row) { $w_objetivo .= ','.f($row,'sq_peobjetivo'); }
        $w_objetivo = substr($w_objetivo,1);
        if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
        $w_prioridade       = f($RS,'prioridade');
        $w_aviso            = f($RS,'aviso_prox_conc');
        $w_dias             = f($RS,'dias_aviso');
        $w_chave_pai        = f($RS,'sq_solic_pai');
        $w_sq_menu          = f($RS,'sq_menu');
        $w_sq_unidade       = f($RS,'sq_unidade');
        $w_sq_tramite       = f($RS,'sq_siw_tramite');
        $w_solicitante      = f($RS,'solicitante');
        $w_cadastrador      = f($RS,'cadastrador');
        $w_executor         = f($RS,'executor');
        $w_sqcc             = f($RS,'sq_cc');
        $w_justificativa    = f($RS,'justificativa');
        $w_observacao       = f($RS,'observacao');
        $w_inicio           = FormataDataEdicao(f($RS,'inicio'));
        $w_fim              = FormataDataEdicao(f($RS,'fim'));
        $w_inclusao         = f($RS,'inclusao');
        $w_ultima_alteracao = f($RS,'ultima_alteracao');
        $w_decisao_judicial = f($RS,'decisao_judicial');
        $w_cidade           = f($RS,'cidade_origem');
        $w_numero_original  = f($RS,'numero_original');
        $w_data_recebimento = FormataDataEdicao(f($RS,'data_recebimento'));
        if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
      } 
    } 
  } 
  // Se não puder cadastrar para outros, carrega os dados do usuário logado
  if ($w_cadgeral=='N') {
    $w_sq_unidade  = $_SESSION['LOTACAO'];
    $w_solicitante = $_SESSION['SQ_PESSOA'];
  } 

  // Recupera os parâmetros da unidade solicitante
  $RS_Unid_CL = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_sq_unidade,'CLUNID',null,null,$w_ano);
  foreach($RS_Unid_CL as $row) { $RS_Unid_CL = $row; break; }

  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
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
    if(nvl(f($RS_Menu,'numeracao_automatica'),0)==0) {
      Validate('w_codigo','Código interno','','1',1,60,'1','1');
    }
    // Trata as possíveis vinculações do projeto
    if($w_pe=='S') {
      if(nvl($w_plano,'')!='') {
        Validate('w_plano','Plano estratégico','SELECT',1,1,18,1,1);
      }
      ShowHTML('  if (theForm["w_objetivo[]"]!=undefined) {');
      ShowHTML('    var i; ');
      ShowHTML('    var w_erro=true; ');  
      ShowHTML('    for (i=0; i < theForm["w_objetivo[]"].length; i++) {');
      ShowHTML('      if (theForm["w_objetivo[]"][i].checked) w_erro=false;');
      ShowHTML('    }');
      ShowHTML('    if (w_erro) {');
      ShowHTML('      alert(\'Você deve informar pelo menos um objetivo estratégico!\'); ');
      ShowHTML('      return false;');
      ShowHTML('    }');
      ShowHTML('  }');
    }
    if(nvl($w_sq_menu_relac,'')!='') {
      Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
      if ($w_sq_menu_relac=='CLASSIF') {
        Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
      } else {
        Validate('w_solic_pai','Vinculação','SELECT',1,1,18,1,1);
      }
    }
    if(nvl($w_sq_menu_relac,'')!='' && nvl($w_plano,'')!='') {
      ShowHTML('    alert(\'Informe um plano estratégico ou uma vinculação. Você não pode escolher ambos!\');');
      ShowHTML('    theForm.w_plano.focus();');
      ShowHTML('    return false;');
    } elseif(nvl($w_sq_menu_relac,'')=='' && nvl($w_plano,'')=='') {
      ShowHTML('    alert(\'Informe um plano estratégico ou uma vinculação!\');');
      ShowHTML('    theForm.w_plano.focus();');
      ShowHTML('    return false;');    
    }
    Validate('w_prioridade','Prioridade','SELECT',1,1,1,'','0123456789');
    Validate('w_fim','Limite para atendimento','DATA',1,10,10,'','0123456789/');
    if($w_decisao_judicial=='S') {
       CompData('w_fim','Limite para atendimento','>=','w_data_recebimento','Data atual');
    } else {
      CompData('w_fim','Limite para atendimento','>=','w_inicio','Data atual');
    }
    if($w_cadgeral=='S') {
      Validate('w_solicitante','Solicitante','HIDDEN',1,1,18,'','0123456789');
      Validate('w_sq_unidade','Setor solicitante','HIDDEN',1,1,18,'','0123456789');
    }
    if($w_decisao_judicial=='S') {
      Validate('w_numero_original','Número original','','1',1,30,'1','1');
      Validate('w_data_recebimento','Data de recebimento','DATA',1,10,10,'','0123456789/');
    }
    Validate('w_justificativa','Justificativa','','1',3,2000,'1','1');
    Validate('w_observacao','Observação','','',3,2000,'1','1');
    if($w_decisao_judicial=='N') {
      Validate('w_dias','Dias de alerta do pedido','1','',1,3,'','0123456789');
      ShowHTML('  if (theForm.w_aviso[0].checked) {');
      ShowHTML('     if (theForm.w_dias.value == \'\') {');
      ShowHTML('        alert(\'Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!\');');
      ShowHTML('        theForm.w_dias.focus();');
      ShowHTML('        return false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     theForm.w_dias.value = \'\';');
      ShowHTML('  }');
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
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if ($w_cidade=='') {
      // Carrega os valores padrão para país, estado e cidade
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
    if(nvl($w_decisao_judicial,'N')=='N') {
      ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.FormataDataEdicao(time()).'">');
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para identificação do pedido de compra, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('          <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    if(nvl(f($RS_Menu,'numeracao_automatica'),0)==0) {
      ShowHTML('      <tr><td><b><U>C</U>ódigo interno:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="w_codigo" size="18" maxlength="60" value="'.$w_codigo.'"></td>');
    }
    if ($w_pe=='S') {
      ShowHTML('          <tr valign="top">');
      selecaoPlanoEstrategico('<u>P</u>lano estratégico:', 'P', 'Selecione o plano ao qual o programa está vinculado.', $w_plano, $w_chave, 'w_plano', 'ULTIMO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_plano\'; document.Form.submit();"');
      ShowHTML('          <tr valign="top">');    
      selecaoObjetivoEstrategico('<u>O</u>bjetivo(s) estratégico(s):', 'P', 'Selecione o(s) objetivo(s) estratégico(s) ao(s) qual(is) o programa está vinculado.', $w_objetivo, $w_plano, 'w_objetivo[]', 'CHECKBOX', null);
    }
    ShowHTML('          <tr valign="top">');
    selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($w_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($w_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null);
      } else {
        SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_solic_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_solic_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_solicitante\'; document.Form.submit();"',$w_chave_pai);
      }
    }
    // Recupera todos os registros para a listagem
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$_SESSION['LOTACAO'],'CLUNID',null,null,$w_ano);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      if ($w_cadgeral=='N') {
        $w_sq_unidade = f($RS,'sq_unidade');
        ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
      } else {
        ShowHTML('          <tr valign="top">');
        SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$w_sq_unidade,null,'w_sq_unidade','CLCP','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_unidade\'; document.Form.submit();"');
      } 
    } else {
      if ($w_cadgeral=='N') {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Sua lotação não está ligada a nenhuma unidade proponente. Entre em contato com os gestores do sistema!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } else {
        ShowHTML('          <tr valign="top">');
        SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$w_sq_unidade,null,'w_sq_unidade','CLCP','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_unidade\'; document.Form.submit();"');
      } 
    }
    ShowHTML('          <tr><td colspan=2><table border=0 colspan=0 cellspan=0 width="100%">');
    if (f($RS_Unid_CL,'registra_judicial')=='S') {
      MontaRadioNS('<b>Decisão judicial?</b>',$w_decisao_judicial,'w_decisao_judicial',null,null,'onClick="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_alerta\'; document.Form.submit();"');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_decisao_judicial" value="N">');
    }
    SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade deste projeto.',$w_prioridade,null,'w_prioridade',null,null);
    ShowHTML('            <td valign="top"><b><u>L</u>imite para atendimento:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data limite para quem o atendimento do pedido seja atendido.">'.ExibeCalendario('Form','w_fim').'</td>');    
    ShowHTML ('         </table>');
    if ($w_cadgeral=='N') {
      ShowHTML('<INPUT type="hidden" name="w_solicitante" value="'.$w_solicitante.'">');
    } else {
      ShowHTML('          <tr valign="top">');
      if(nvl($w_decisao_judicial,'N')=='N') {
        SelecaoPessoa('<u>S</u>olicitante:','S','Selecione o solicitante do pedido na relação.',$w_solicitante,null,'w_solicitante','USUARIOS');
      } else {
        SelecaoPessoaOrigem('<u>S</u>olicitante:','s','Clique na lupa para selecionar o solicitante do pedido.',$w_solicitante,null,'w_solicitante',null,null,null);
      }
    }
    if(nvl($w_decisao_judicial,'N')=='S') {
      ShowHTML('          <tr><td colspan=2><table border=0 colspan=0 cellspan=0 width="100%">');
      ShowHTML('       <tr valign="top">');
      ShowHTML('          <td valign="top"><b><U>N</U>úmero original:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_numero_original" size="30" maxlength="30" value="'.$w_numero_original.'"></td>');
      ShowHTML('          <td valign="top"><b><u>D</u>ata de recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_recebimento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_recebimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data de recebimento do pedido.">'.ExibeCalendario('Form','w_data_recibimento').'</td>');
      ShowHTML ('         </table>');
    }
    ShowHTML('      <tr><td valign="top" colspan=2><b><u>J</u>ustificativa:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="É obrigatório justificar.">'.$w_justificativa.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top" colspan=2><b><u>O</u>bservacao:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>'.$w_observacao.'</TEXTAREA></td>');
    if(nvl($w_decisao_judicial,'N')=='N') {
      ShowHTML('          <tr><td colspan=2><table border=0 colspan=0 cellspan=0 width="100%">');
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Alerta de atraso</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td>Os dados abaixo indicam como deve ser tratada a proximidade da data Término previsto do projeto.</td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      MontaRadioNS('<b>Emite alerta?</b>',$w_aviso,'w_aviso');
      ShowHTML('              <td><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="Número de dias para emissão do alerta de proximidade da data Término previsto do projeto."></td>');
      ShowHTML('          </table>');
      ShowHTML ('         </table>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    }
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
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
    ShowHTML(' alert(\'Opção não disponível\');');
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

  $p_tipo_material      = $_REQUEST['p_tipo_material'];
  $p_sq_cc              = $_REQUEST['p_sq_cc'];
  $p_codigo             = $_REQUEST['p_codigo'];
  $p_nome               = $_REQUEST['p_nome'];
  $p_ordena             = $_REQUEST['p_ordena'];

  // Recupera os dados da solicitacao
  $RS_Solic = db_getSolicCL::getInstanceOf($dbms,null,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O <> 'E') {
    $w_sq_material        = $_REQUEST['w_sq_material'];
    $w_quantidade         = $_REQUEST['w_quantidade'];
  } elseif ($O=='I') {
    if (montaFiltro('GET')!='') {
      $w_filtro='';

      if ($p_codigo>'')  $w_filtro.='<tr valign="top"><td align="right">Código <td>[<b>'.$p_codigo.'</b>] em qualquer parte';
      if ($p_nome>'')    $w_filtro.='<tr valign="top"><td align="right">Nome <td>[<b>'.$p_nome.'</b>] em qualquer parte';
      if ($p_tipo_material>'') {
        $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,$p_tipo_material,null,null,null,null,null,null,'REGISTROS');
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro.='<tr valign="top"><td align="right">Tipo <td>[<b>'.f($RS,'nome_completo').'</b>]';
      } 
      if ($p_sq_cc>'') {
        $RS = db_getCCData::getInstanceOf($dbms,$p_sq_cc);
        $w_filtro.='<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 

    $RS = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,$p_tipo_material,$p_sq_cc,$p_codigo,$p_nome,'S','COMPRA');
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc');
    } else {
      $RS = SortArray($RS,'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc'); 
    }
  } elseif (strpos('L',$O)!==false) {
    $RS = db_getCLSolicItem::getInstanceOf($dbms,null,$w_chave,null,null,null);
    $RS = SortArray($RS,'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc'); 
  } elseif (strpos('AEV',$O)!==false) {
    $RS = db_getCLSolicItem::getInstanceOf($dbms,$w_chave_aux,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave_aux           = f($RS,'chave');
    $w_material            = f($RS,'sq_material');
    $w_quantidade          = formatNumber(f($RS,'quantidade'));
  } 

  // Recupera informações sobre o tipo do material ou serviço
  if (nvl($w_tipo_material,'')!='') {
    $RS_Tipo = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,$w_tipo_material,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS_Tipo as $row) { $RS_Tipo = $row; break; }
    $w_classe = f($RS_Tipo,'classe');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ShowHTML('<TITLE>'.$conSgSistema.' - Itens da solicitação</TITLE>');
  Estrutura_CSS($w_cliente);
  if (strpos('PLIA',$O)!==false) {
    ScriptOpen('JavaScript');
    if ($O=='I') {
      ShowHTML('  function valor(p_indice) {');
      ShowHTML('    if (document.Form["w_sq_material[]"][p_indice].checked) { ');
      ShowHTML('       document.Form["w_quantidade[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_quantidade[]"][p_indice].focus(); ');
      ShowHTML('    } else {');
      ShowHTML('       document.Form["w_quantidade[]"][p_indice].disabled=true; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  function MarcaTodos() {');
      ShowHTML('    if (document.Form["w_sq_material[]"].value==undefined) ');
      ShowHTML('       for (i=0; i < document.Form["w_sq_material[]"].length; i++) {');
      ShowHTML('         document.Form["w_sq_material[]"][i].checked=true;');
      ShowHTML('         document.Form["w_quantidade[]"][i].disabled=false;');
      ShowHTML('       } ');
      ShowHTML('    else document.Form["w_sq_material[]"].checked=true;');
      ShowHTML('  }');
      ShowHTML('  function DesmarcaTodos() {');
      ShowHTML('    if (document.Form["w_sq_material[]"].value==undefined) ');
      ShowHTML('       for (i=0; i < document.Form["w_sq_material[]"].length; i++) {');
      ShowHTML('         document.Form["w_sq_material[]"][i].checked=false;');
      ShowHTML('         document.Form["w_quantidade[]"][i].disabled=true;');
      ShowHTML('       } ');
      ShowHTML('    ');
      ShowHTML('    else document.Form["w_sq_material[]"].checked=false;');
      ShowHTML('  }');
    }     
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if ($O=='P') {
      Validate('p_nome','Nome','1','','3','30','1','1');
      Validate('p_codigo','Código interno','1','','2','30','1','1');
      Validate('p_tipo_material','Tipo do material ou serviço','SELECT','','1','18','','1');
      Validate('p_sq_cc','Classificação','SELECT','','1','18','','1');
      ShowHTML('if (theForm.p_nome.value==\'\' && theForm.p_codigo.value==\'\' && theForm.p_tipo_material.value==\'\' && theForm.p_sq_cc.value==\'\') {');
      ShowHTML(' alert(\'Informe pelo menos um critério de filtragem!\');');
      ShowHTML(' return false;');
      ShowHTML('}');
    } elseif($O=='I') {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_sq_material[]"].value==undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_sq_material[]"].length; i++) {');
      ShowHTML('       if (theForm["w_sq_material[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_sq_material[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Você deve informar pelo menos um item!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      ShowHTML('  for (i=1; i < theForm["w_sq_material[]"].length; i++) {');
      ShowHTML('    if((theForm["w_sq_material[]"][i].checked)&&(theForm["w_quantidade[]"][i].value==\'\')){');
      ShowHTML('      alert(\'Para todas os itens selecionados você deve informar a quantidade!\'); ');
      ShowHTML('      return false;');
      ShowHTML('    }');
      ShowHTML('    if((theForm["w_sq_material[]"][i].checked)&&(theForm["w_quantidade[]"][i].value==\'0,00\')){');
      ShowHTML('      alert(\'Para todas os itens selecionados você deve informar a quantidade maior que zero!\'); ');
      ShowHTML('      return false;');
      ShowHTML('    }');
      ShowHTML('  }');
    } elseif($O=='A') {
      Validate('w_quantidade','Quantidade','1','1','1','18','','1');
      CompValor('w_quantidade','Quantidade','>',0,'1');  
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
    BodyOpen('onLoad="document.Form.p_nome.focus();"');
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

  // Exibe os dados da solicitação
  ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=3><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td><table border=0 width="100%">');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>'.f($RS_Menu,'nome').':<b><br>'.f($RS_Solic,'codigo_interno').'</td>');
  ShowHTML('            <td>Decisão judicial?<b><br>'.RetornaSimNao(f($RS_Solic,'decisao_judicial')).'</td>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Setor solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($O=='L') {
    ShowHTML('<tr><td>');
    ShowHTML('                <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                <a accesskey="F" class="ss" href="#" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_material_pai').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Código','codigo_interno').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Qtd','quantidade').'</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_tipo_material_pai').'</td>');
        ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');
        ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),2).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('        </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
  } elseif ($O=='I') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_material[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_quantidade[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<tr><td>');
    if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=P&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=P&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td NOWRAP><font size="2"><U ID="INICIO" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
    ShowHTML('                                      <U CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_material_pai').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Código','codigo_interno').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Un.','sg_unidade_medida').'</td>');
    ShowHTML('          <td><b>Quantidade</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_cont=0;
      foreach($RS1 as $row){ 
        $w_cont+= 1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"><input type="checkbox" name="w_sq_material[]" value="'.f($row,'chave').'" onClick="valor('.$w_cont.');">');        
        ShowHTML('        <td>'.f($row,'nm_tipo_material_pai').'</td>');
        ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');        
        ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'chave'),$TP,null).'</td>');
        ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('        <td><input type="text" disabled name="w_quantidade[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.formatNumber(f($row,'quantidade'),2).'" onKeyDown="FormataValor(this,18,2,event);" title="Informe a  quantidade."></td>');
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
  } elseif (strpos('IAEV',$O)!==false) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_material" value="'.$w_material.'">');
    ShowHTML('<INPUT type="hidden" name="w_qtd_ant" value="'.$w_quantidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('      <tr><td><b><u>Q</u>uantidade:<br><input accesskey="Q" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" '.$w_Disabled.' onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('      <tr><td colspan=3 align="center"><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');    
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'I');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('      <tr><td colspan=2><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$p_Disabled.' accesskey="N" type="text" name="p_nome" class="sti" SIZE="40" MAXLENGTH="100" VALUE="'.$p_nome.'"></td>');
    ShowHTML('          <td><b><u>C</u>ódigo:</b><br><input '.$p_Disabled.' accesskey="C" type="text" name="p_codigo" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$p_codigo.'"></td>');
    ShowHTML('      <tr valign="top">');
    selecaoTipoMatServ('T<U>i</U>po:','I',null,$p_tipo_material,null,'p_tipo_material','FOLHA',null);
    ShowHTML('      <tr valign="top">');
    SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',$p_sq_cc,null,'p_sq_cc','SIWSOLIC');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
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
    ShowHTML(' alert(\'Opção não disponível\');');
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
  $RS_Solic = db_getSolicCL::getInstanceOf($dbms,null,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
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
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
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

  // Exibe os dados da solicitação
  ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=3><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td><table border=0 width="100%">');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>'.f($RS_Menu,'nome').':<b><br>'.f($RS_Solic,'codigo_interno').'</td>');
  ShowHTML('            <td>Decisão judicial?<b><br>'.RetornaSimNao(f($RS_Solic,'decisao_judicial')).'</td>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Setor solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('<a accesskey="F" class="ss" href="#" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
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
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b></font>.</td>');
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
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));

  if ($w_tipo=='WORD') {
    HeaderWord(null);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0);
  } else {
    Cabecalho();
  }
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de Pedido de compra</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_tipo=='WORD') {
    BodyOpenWord('onLoad=\'this.focus()\'; ');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\'; ');
  }
  if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);
  if ($w_tipo>'' && $w_tipo!='WORD') {
    ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  }
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualPedido($w_chave,'L',$w_usuario,$P1,$P4));
  if ($w_tipo>'' && $w_tipo!='WORD') {
    ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  }
  if ($w_tipo!='WORD') Rodape();
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
  ShowHTML(VisualPedido($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CLPCCAD',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<tr ><td align="center">');
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
    // Recupera os dados da solicitacao
    $RS = db_getSolicCL::getInstanceOf($dbms,null,$w_usuario,$SG,5,
            null,null,null,null,null,null,null,null,null,null,
            $w_chave,null,null,null,null,null,null,
            null,null,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
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
  if ($O=='V') $w_erro = ValidaPedido($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);

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
  ShowHTML(VisualPedido($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || $w_ativo=='N' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CLPCENVIO',$w_pagina.$par,$O);
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
      SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a PCD, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_novo_tramite,'w_novo_tramite','DEVOLUCAO',null);
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
  ShowHTML(VisualPedido($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_dir.$w_pagina.'Grava&SG=CLPCENVIO&O='.$O.'&w_menu='.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $RS = db_getSolicCL::getInstanceOf($dbms,null,$w_usuario,$SG,5,
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
// Rotina de conclusão
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  //Recupera os dados da solicitacao de passagens e diárias
  $RS = db_getSolicCL::getInstanceOf($dbms,null,$w_usuario,$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}  
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  modulo();
  formatavalor();
  ValidateOpen('Validacao');

  ShowHTML('  for (ind=1; ind < theForm["w_quantidade[]"].length; ind++) {');
  Validate('["w_quantidade[]"][ind]','Quantidade autorizada','VALOR','1',4,18,'','1');
  ShowHTML('  }');
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
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
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualPedido($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=CLPCCONC&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" method="POST">');
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
  ShowHTML('<INPUT type="hidden" name="w_sq_solicitacao_item[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_quantidade[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_qtd_ant[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_material[]" value="">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="100%" border="0">');
  $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$w_chave,null,null,null);
  $RS1 = SortArray($RS1,'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc'); 
  ShowHTML('<tr><td colspan=4><b>Informe para cada item a quantidade autorizada para compra:</b>');
  ShowHTML('<tr><td align="center" colspan=4>');  
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td rowspan=2><b>Tipo</td>');
  ShowHTML('          <td rowspan=2><b>Código</td>');
  ShowHTML('          <td rowspan=2><b>Nome</td>');
  ShowHTML('          <td colspan=2><b>Quantidade</td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td><b>Solicitada</td>');
  ShowHTML('          <td><b>Autorizada</td>');
  ShowHTML('        </tr>');
  // Lista os registros selecionados para listagem
  foreach($RS1 as $row){ 
    $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
    ShowHTML('        <td>'.f($row,'nm_tipo_material_pai').'</td>');
    ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');
    ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
    ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),2).'</td>');
    ShowHTML('<INPUT type="hidden" name="w_sq_solicitacao_item[]" value="'.f($row,'chave').'">');
    ShowHTML('<INPUT type="hidden" name="w_qtd_ant[]" value="'.f($row,'quantidade').'">');
    ShowHTML('<INPUT type="hidden" name="w_material[]" value="'.f($row,'sq_material').'">');
    ShowHTML('        <td align="center"><input type="text" name="w_quantidade[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.formatNumber(Nvl(f($row,'quantidade'),0),2).'" onKeyDown="FormataValor(this,18,2,event);" title="Informe a  quantidade autorizada para compra."></td>');
    ShowHTML('        </tr>');
  }
  ShowHTML('    </table>');
  ShowHTML('    <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  $RS   = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $RSM = db_getSolicCL::getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,5,
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
    foreach ($RS as $row) { $RS = $row; break; }
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
    case 'CLPCCAD':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putCLGeral::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_menu'],$_REQUEST['w_sq_unidade'],
          $_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],null,$_REQUEST['w_plano'],
          explodeArray($_REQUEST['w_objetivo']),$_REQUEST['w_sqcc'],
          $_REQUEST['w_solic_pai'],$_REQUEST['w_justificativa'],$_REQUEST['w_observacao'],nvl($_REQUEST['w_inicio'],$_REQUEST['w_data_recebimento']),
          $_REQUEST['w_fim'],$_REQUEST['w_codigo'],$_REQUEST['w_prioridade'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],
          $_REQUEST['w_cidade'],$_REQUEST['w_decisao_judicial'],$_REQUEST['w_numero_original'],$_REQUEST['w_data_recebimento'],'N',&$w_chave_nova,$_REQUEST['w_copia']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'CLPCITEM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_sq_material'])-1; $i=$i+1) {
            if ($_REQUEST['w_sq_material'][$i]>'') {
              dml_putCLSolicItem::getInstanceOf($dbms,$O,$_REQUEST['w_chave_aux'],$_REQUEST['w_chave'],null,$_REQUEST['w_sq_material'][$i],
                  Nvl($_REQUEST['w_quantidade'][$i],0),null,null,null,null);
              //Recupera os dados da pessoa associada ao lançamento
            }
          } 
        } else {
          dml_putCLSolicItem::getInstanceOf($dbms,$O,$_REQUEST['w_chave_aux'],$_REQUEST['w_chave'],null,$_REQUEST['w_material'],
              Nvl($_REQUEST['w_quantidade'],0),Nvl($_REQUEST['w_qtd_ant'],0),null,null,null);
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }     
      break;
    case 'CLPCANEXO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
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
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
          if ($O=='E' && $_REQUEST['w_atual']>'') {
            $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
            foreach ($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
            }
          } 
          dml_putSolicArquivo::getInstanceOf($dbms,$O,
            $w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],
            $w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        } 
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu 
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;      
    case 'CLPCENVIO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Trata o recebimento de upload ou dados 
        if ((false!==(strpos(strtoupper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(strtoupper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
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
            //Rotina para gravação da imagem da versão da solicitacão no log.
            if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
              $RS = db_getTramiteData::getInstanceOf($dbms,$_REQUEST['w_tramite']);
              $w_sg_tramite = f($RS,'sigla');
              if($w_sg_tramite=='CI') {
                $w_html = VisualPedido($_REQUEST['w_chave'],'L',$w_usuario,null,'1');
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
        } else {
          if ($_REQUEST['w_envio']=='N') {
            dml_putSolicEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
              $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
          } else {
            dml_putSolicEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
              $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
          } 
          //Rotina para gravação da imagem da versão da solicitacão no log.
          if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
            $RS = db_getTramiteData::getInstanceOf($dbms,$_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS,'sigla');
            if($w_sg_tramite=='CI') {
              $w_html = VisualPedido($_REQUEST['w_chave'],'L',$w_usuario,null,'1');
              CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
            }
          }  
          // Envia e-mail comunicando a inclusão
          SolicMail($_REQUEST['w_chave'],2);
          // Se for envio da fase de cadastramento, remonta o menu principal
          if ($P1==1) {
            // Recupera os dados para montagem correta do menu
            $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
            ScriptOpen('JavaScript');
            ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG='.f($RS,'sigla').'&TP='.RemoveTP(RemoveTP($TP)).MontaFiltro('GET')).'\';');
            ScriptClose();
          } else {
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
      break;
    case 'CLPCCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        for ($i=0; $i<=count($_POST['w_sq_solicitacao_item'])-1; $i=$i+1) {
          if ($_REQUEST['w_sq_solicitacao_item'][$i]>'') {
            dml_putCLSolicItem::getInstanceOf($dbms,'C',$_REQUEST['w_sq_solicitacao_item'][$i],$_REQUEST['w_chave'],null,Nvl($_REQUEST['w_material'][$i],0),
                Nvl($_REQUEST['w_quantidade'][$i],0),Nvl($_REQUEST['w_qtd_ant'][$i],0),null,null,null);
          }
        }
        $RS = db_getSolicCL::getInstanceOf($dbms,null,$w_usuario,$SG,3,
                null,null,null,null,null,null,null,null,null,null,
                $_REQUEST['w_chave'],null,null,null,null,null,null,
                null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou o pedido para fase de execução!\');');
          ScriptClose();
          exit();
        } else {
          dml_putSolicConc::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,$_SESSION['SQ_PESSOA'],null,null,
              null,null,null,null);
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

