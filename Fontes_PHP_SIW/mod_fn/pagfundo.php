<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getBankData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoValor.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoIncid.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getVincKindList.php');
include_once($w_dir_volta.'classes/sp/db_getKindPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoNota.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoOutra.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroConc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoValor.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoContaBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAcordoParcela.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAgencia.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoTipoRubrica.php');
include_once('visuallancamento.php');
include_once('validalancamento.php');
// =========================================================================
//  /pagfundo.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia as rotinas relativas ao controle de lançamentos financeiros
// Mail     : celso@sbpi.com.br
// Criacao  : 14/07/2006 13:30
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
$w_pagina       = 'pagfundo.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos($SG,'ANEXO')!==false || strpos($SG,'PARC')!==false || strpos($SG,'REPR')!==false) {
  if (strpos('IG',$O)===false && $_REQUEST['w_chave_aux']=='') $O='L';
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
  case 'G': $w_TP=$TP.' - Gerar';       break;
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

// Verifica se o cliente tem o módulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_mod_pa='S'; else $w_mod_pa='N';

// Verifica se o cliente tem o módulo de compras contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'CO');
if (count($RS)>0) $w_mod_co='S'; else $w_mod_co='N';

$w_copia        = $_REQUEST['w_copia'];
$p_projeto      = upper($_REQUEST['p_projeto']);
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
$p_objeto       = upper($_REQUEST['p_objeto']);
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
$p_sq_orprior   = $_REQUEST['p_sq_orprior'];
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configuração do serviço
if ($P2>10) {
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
  global $w_Disabled;
  $w_tipo = $_REQUEST['w_tipo'];
  if ($O=='L') {
    if ((!(strpos(upper($R),'GR_')===false)) || (!(strpos(upper($R),'PROJETO')===false)) || ($w_tipo=='WORD')) {
      $w_filtro='';
      if ($p_projeto>'') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
        if ($w_tipo=='WORD') {
          $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b>'.f($RS,'titulo').'</b>]';
        } else {
          $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto." target="_blank">'.f($RS,'titulo').'</a></b>]';
        }
      } 
    }
    if ($p_sqcc>'') {
      $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
      $w_filtro .= '<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') $w_filtro .= '<tr valign="top"><td align="right">Atividade nº <td>[<b>'.$p_chave.'</b>]';
    if ($p_prazo>'') $w_filtro .= ' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
    if ($p_solicitante>'') {
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade responsável <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro .= '<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_sq_orprior>''){
      $sql = new db_getTipoLancamento; $RS = $sql->getInstanceOf($dbms,$p_sq_orprior,null,$w_cliente,null);
      foreach($RS as $row) {$RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo do lançamento <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uorg_resp>'') {
      $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro .= '<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
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
    if ($p_prioridade>'') $w_filtro .= '<tr valign="top"><td align="right">Prioridade <td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
    if ($p_proponente>'') $w_filtro .= '<tr valign="top"><td align="right">Parceria externa <td>[<b>'.$p_proponente.'</b>]';
    if ($p_objeto>'')     $w_filtro .= '<tr valign="top"><td align="right">Detalhamento <td>[<b>'.$p_objeto.'</b>]';
    if ($p_palavra>'')    $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.$p_palavra.'</b>]';
    if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
    if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
    if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr><td><b>Filtro:</b></td>'.$w_filtro.'</table></div>';
  }
  if ($w_copia>'') {
    // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
    $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, $p_sq_orprior);
  } else {
    $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, $p_sq_orprior);
  }
  if ($p_ordena>'') {
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RS = SortArray($RS,$lista[0],$lista[1],'vencimento','asc');
  } else {
    $RS = SortArray($RS,'nm_pessoa','asc','vencimento','desc');
  } 
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']); 
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ShowHTML('</HEAD>');
  } else {
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('CP',$O)!==false) {
      if ($P1!=1 || $O=='C') {
        // Se não for cadastramento ou se for cópia
        Validate('p_chave','Número do lançamento','','','1','18','','0123456789');
        Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
        Validate('p_proponente','Parcerias externas','','','2','90','1','');
        Validate('p_objeto','Assunto','','','2','90','1','1');
        Validate('p_palavra','Palavras-chave','','','2','90','1','1');
        Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
        Validate('p_fim_i','Conclusão inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim_f','Conclusão final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
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
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  elseif ($O=='I') BodyOpen('onLoad=\'document.Form.w_smtp_server.focus();\'');
  elseif ($O=='A') BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  elseif ($O=='E') BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  elseif (strpos('CP',$O)!==false) BodyOpen('onLoad=\'document.Form.p_projeto.focus();\'');
  else             BodyOpenClean(null);
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  if($w_tipo!='WORD') {
    if ((strpos(upper($R),'GR_'))===false) {
      Estrutura_Texto_Abre();
    } else {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
   }
  }
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($P1==1) {
      // Se for cadastramento e não for resultado de busca para cópia
      ShowHTML('<tr>');
      if ($w_tipo!='WORD') {
        ShowHTML('    <td>');
        if (strpos($SG,'CONT')!==false) ShowHTML('    <td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Buscaparcela&R='.$w_pagina.$par.'&O=P&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        else                               ShowHTML('    <td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      }
    } 
    if ((strpos(upper($R),'GR_')===false) && (strpos(upper($R),'LANCAMENTO')===false) && (Nvl($R,'')>'')) {
      if ($w_copia>'') {
        // Se for cópia
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } else {
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    }
    ShowHTML('    <td align="right">');
    //if ($w_tipo!='WORD') {
      //ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      //ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    //}      
    ShowHTML('    <b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo!='WORD') {    
      ShowHTML('          <td><b>'.LinkOrdena('Código','codigo_interno').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Pessoa','nm_pessoa_resumido').'</td>');
      if (strpos($SG,'CONT')!==false)  ShowHTML('          <td><b>'.LinkOrdena('Contrato (Parcela)','cd_acordo').'</td>');
      else                                ShowHTML ('          <td><b>'.LinkOrdena('Vinculação','dados_pai').'</td>');
      if (strpos('CONT',substr($SG,3))!==false) {
        ShowHTML('          <td><b>'.LinkOrdena('Referência','referencia_fim').'</td>');
      }
      if (f($RS_Menu,'sigla')=='FNDVIA' || f($RS_Menu,'sigla')=='FNREVENT') {
        ShowHTML('          <td><b>'.LinkOrdena('Projeto','dados_avo').'</td>');
      }
      ShowHTML('          <td><b>'.LinkOrdena('Vencimento','vencimento').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>Operações</td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td><b>Código</td>');
      ShowHTML('          <td><b>Pessoa</td>');
      if (strpos($SG,'CONT')!==false)  ShowHTML('          <td><b>Contrato (Parcela)</td>');
      else                             ShowHTML('          <td><b>Vinculação</td>');
      if (strpos('CONT',substr($SG,3))!==false) {
        ShowHTML('          <td><b>Referência</td>');
      }
      if (f($RS_Menu,'sigla')=='FNDVIA' || f($RS_Menu,'sigla')=='FNREVENT') {
        ShowHTML('          <td><b>Projeto</td>');
      }
      ShowHTML('          <td><b>Vencimento</td>');
      ShowHTML('          <td><b>Valor</td>');
      ShowHTML('        </tr>');
    }  
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      if($w_tipo!='WORD') $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      else                $RS1 = $RS;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'vencimento'),f($row,'inicio'),f($row,'quitacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        if ($w_tipo!='WORD') ShowHTML('        <A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'obj_acordo').' ::> '.f($row,'descricao').'">'.f($row,'codigo_interno').'&nbsp;</a>');
        else                 ShowHTML('        '.f($row,'codigo_interno').''); 
        if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
          if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,f($row,'nm_pessoa_resumido')).'</td>');
          else                 ShowHTML('        <td>'.f($row,'nm_pessoa_resumido').'</td>');
        } else {
          ShowHTML('        <td align="center">---</td>');
        }
        if (strpos($SG,'CONT')!==false) {
          if ($w_tipo!='WORD') ShowHTML('        <td><A class="hl" HREF="'.'mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($row,'sq_solic_pai').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GC'.substr($SG,2,1).'CAD" title="Exibe as informações do acordo." target="_blank">'.f($row,'cd_acordo').' ('.f($row,'or_parcela').')</a></td>');
          else                 ShowHTML('        <td>'.f($row,'cd_acordo').' ('.f($row,'or_parcela').')</td>');
        } else {
          if (Nvl(f($row,'dados_pai'),'')!='') {
            ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_tipo).'</td>');
          } else {
            ShowHTML('        <td>---</td>');
          }
        } 
        if (f($RS_Menu,'sigla')=='FNDVIA' || f($RS_Menu,'sigla')=='FNREVENT') {
          if (Nvl(f($row,'dados_avo'),'')!='') {
            ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_avo'),f($row,'dados_avo'),'N',$w_tipo).'</td>');
          } else {
            ShowHTML('        <td>---</td>');
          }
        }
        if (strpos('CONT',substr($SG,3))!==false) {
          if (nvl(f($row,'referencia_inicio'),'')!='') {
            ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'referencia_inicio'),5).' a '.FormataDataEdicao(f($row,'referencia_fim'),5).'</td>');
          } else {
            ShowHTML('        <td align="center">-</td>');
          }
        }
        ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'vencimento'),5),'-').'</td>');
        ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
        $w_parcial += Nvl(f($row,'valor'),0);
        if ($w_tipo!='WORD') {
          if ($_SESSION['INTERNO']=='S') {
            ShowHTML('        <td align="top" nowrap>');
            if ($P1!=3) {
              // Se não for acompanhamento
              if ($P1==1) {
                  // Se for cadastramento
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais do lançamento">AL</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'OutraParte&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG='.substr($SG,0,3).'OUTRAP').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa dados da pessoa associada ao lançamento.">Pessoa</A>&nbsp');
                  /*
                  if (!(strpos($SG,'FNR')===false)) {
                    $sql = new db_getsolicRubrica; $l_rs_rubrica = $sql->getInstanceOf($dbms,f($row,'sq_solic_pai'),null,null,null,null,null,null,null,null);
                    if (count($l_rs_rubrica)>0) ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'RubricaDoc&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Docs'.'&SG=RUBRICADOC').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lançamento.">Docs</A>&nbsp');
                    else              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Documento&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Docs'.'&SG=DOCUMENTO').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lançamento.">Docs</A>&nbsp');
                  } else {
                    ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Documento&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Docs'.'&SG=DOCUMENTO').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lançamento.">Docs</A>&nbsp');
                  }
                  if(nvl(f($row,'qtd_nota'),0)!=0) ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Notas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Notas'.'&SG=NOTA').'\',\'Nota\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa os valores específicos para cada nota de empenho ligado a parcela.">NE</A>&nbsp');
                  */
                  //ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registro do pagamento.">Concluir</A>&nbsp');
              } elseif ($P1==2 || $P1==6) {
                 // Se for execução
                $sql   = new db_getSolicData; $l_rs_solic = $sql->getInstanceOf($dbms,f($row,'sq_siw_solicitacao'),$SG);
                $sql = new db_getTramiteData; $l_rs_tramite = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
                if ($P1==2) {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para o lançamento, sem enviá-la.">AN</A>&nbsp');
                  if (Nvl(f($l_rs_tramite,'sigla'),'---')=='EE') {
                    ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'OutraParte&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG='.substr($SG,0,3).'OUTRAP').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa dados da pessoa associada ao lançamento.">Pessoa</A>&nbsp');
                    /*
                    if (!(strpos($SG,'FNR')===false)) {
                      $sql = new db_getsolicRubrica; $l_rs_rubrica = $sql->getInstanceOf($dbms,f($row,'sq_solic_pai'),null,null,null,null,null,null,null,null);
                      if (count($l_rs_rubrica)>0) ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'RubricaDoc&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Docs'.'&SG=RUBRICADOC').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lançamento.">Docs</A>&nbsp');
                      else              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Documento&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Docs'.'&SG=DOCUMENTO').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lançamento.">Docs</A>&nbsp');
                    } else {
                      ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Documento&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Docs'.'&SG=DOCUMENTO').'\',\'Doc\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lançamento.">Docs</A>&nbsp');
                    }
                    */
                  }
                  //if(nvl(f($row,'qtd_nota'),'')!='') ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Notas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Notas'.'&SG=NOTA').'\',\'Nota\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa os valores específicos para cada nota de empenho ligado a parcela.">NE</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
                  if (Nvl(f($l_rs_tramite,'sigla'),'---')=='EE')
                    ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registro do pagamento.">'.((substr($SG,2,1)=='R') ? 'Receber' : 'Pagar').'</A>&nbsp');
                } else {
                  if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                    ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
                  } else {
                    ShowHTML('          ---&nbsp');
                  }
                } 
              } 
            } else {
              if (Nvl(f($row,'solicitante'),0)    == $w_usuario || 
                  Nvl(f($row,'titular'),0)        == $w_usuario || 
                  Nvl(f($row,'substituto'),0)     == $w_usuario || 
                  Nvl(f($row,'tit_exec'),0)       == $w_usuario || 
                  Nvl(f($row,'subst_exec'),0)     == $w_usuario ||
                  RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
              } else {
                ShowHTML('          ---&nbsp');
              }
            } 
            ShowHTML('        </td>');
          } 
        }
        ShowHTML('      </tr>');
      } 
      if ($P1!=1) {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          if (strpos('CONT',substr($SG,3))===false && f($RS_Menu,'sigla')!='FNDVIA' && f($RS_Menu,'sigla')!='FNREVENT') {
            ShowHTML('          <td colspan=4 align="right"><b>Total desta página&nbsp;</td>');
          } else {
            ShowHTML('          <td colspan=5 align="right"><b>Total desta página&nbsp;</td>');
          }
          ShowHTML('          <td align="right"><b>'.formatNumber($w_parcial,2).'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') $w_total += f($row,'valor_atual');
            else                            $w_total += f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          if (strpos('CONT',substr($SG,3))===false && f($RS_Menu,'sigla')!='FNDVIA' && f($RS_Menu,'sigla')!='FNREVENT') {
            ShowHTML('          <td colspan=4 align="right"><b>Total da listagem&nbsp;</td>');
          } else {
            ShowHTML('          <td colspan=5 align="right"><b>Total da listagem&nbsp;</td>');
          }
          ShowHTML('          <td align="right"><b>'.formatNumber($w_total,2).'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($w_tipo!='WORD') {
      ShowHTML('<tr><td align="center" colspan=3>');
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
      ShowHTML('</tr>');
    } 
  } elseif(strpos('CP',$O)!==false) {
    if ($O=='C') {
      // Se for cópia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Para selecionar o lançamento que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    // Recupera dados da opção Projetos
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto do lançamento na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto',$w_menu,null);
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr>');
        SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('      </tr>');
      } 
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pelo monitoramento do lançamento na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor responsável:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Responsável atua<u>l</u>:','L','Selecione o responsável atual pelo lançamento na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde o lançamento se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr>');
      SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      ShowHTML('          <td><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td><b>Iní<u>c</u>io vigência entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b>Fi<u>m</u> vigência entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td><b>Exibe lançamentos vencidos?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="str" class="str" type="radio" name="p_atraso" value="N"> Não');
        else                ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="str" class="str" type="radio" name="p_atraso" value="N" checked> Não');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if     ($p_ordena=='ASSUNTO')       ShowHTML('          <option value="assunto" SELECTED>Objeto<option value="vencimento">Início vigência<option value="">Término vigência<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='vencimento')    ShowHTML('          <option value="assunto">Objeto<option value="vencimento" SELECTED>Início vigência<option value="">Término vigência<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='FIM')           ShowHTML('          <option value="assunto">Objeto<option value="vencimento">Início vigência<option value="">Término vigência<option value="nm_tramite" SELECTED>Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='NM_pessoa')     ShowHTML('          <option value="assunto">Objeto<option value="vencimento">Início vigência<option value="">Término vigência<option value="nm_tramite">Fase atual<option value="pessoa" SELECTED>Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='NM_PROJETO')    ShowHTML('          <option value="assunto">Objeto<option value="vencimento">Início vigência<option value="">Término vigência<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente" SELECTED>Projeto');
    else                                ShowHTML('          <option value="assunto">Objeto<option value="vencimento">Início vigência<option value="" SELECTED>Término vigência<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    ShowHTML('          </select></td>');
    ShowHTML('          <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for cópia
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar cópia">');
    } else {
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
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
  Rodape();
} 
// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
    
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_sq_tipo_lancamento = $_REQUEST['w_sq_tipo_lancamento'];
  $w_readonly           = '';
  $w_erro               = '';

  // Carrega o segmento cliente
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente); 
  $w_segmento = f($RS,'segmento');
  
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_cpf           = $_REQUEST['w_cpf'];
    $w_cnpj          = $_REQUEST['w_cnpj'];
    $w_sq_prop       = $_REQUEST['w_sq_prop'];
    $w_nome          = $_REQUEST['w_nome'];
    $w_nome_resumido = $_REQUEST['w_nome_resumido'];
    $w_sexo          = $_REQUEST['w_sexo'];
    $w_vinculo       = $_REQUEST['w_vinculo'];
    $w_solic_vinculo = $_REQUEST['w_solic_vinculo'];

    // Se for recarga da página
    $w_sq_menu_relac        = $_REQUEST['w_sq_menu_relac'];    
    if($w_sq_menu_relac=='CLASSIF') {
      $w_chave_pai          = '';
    } else {
      $w_chave_pai          = $_REQUEST['w_chave_pai'];
    }
    $w_pessoa               = $_REQUEST['w_pessoa'];
    $w_tipo_pessoa          = $_REQUEST['w_tipo_pessoa'];
    $w_nm_tipo_pessoa       = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_acordo_parcela    = $_REQUEST['w_sq_acordo_parcela'];
    $w_sq_forma_pagamento   = $_REQUEST['w_sq_forma_pagamento'];
    $w_forma_atual          = $_REQUEST['w_forma_atual'];
    $w_vencimento_atual     = $_REQUEST['w_vencimento_atual'];
    $w_sq_tipo_lancamento   = $_REQUEST['w_sq_tipo_lancamento'];
    $w_observacao           = $_REQUEST['w_observacao'];
    $w_aviso                = $_REQUEST['w_aviso'];
    $w_dias                 = $_REQUEST['w_dias'];
    $w_codigo_interno       = $_REQUEST['w_codigo_interno'];
    $w_chave                = $_REQUEST['w_chave'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_sq_menu              = $_REQUEST['w_sq_menu'];
    $w_sq_unidade           = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite           = $_REQUEST['w_sq_tramite'];
    $w_solicitante          = $_REQUEST['w_solicitante'];
    $w_cadastrador          = $_REQUEST['w_cadastrador'];
    $w_executor             = $_REQUEST['w_executor'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_justificativa        = $_REQUEST['w_justificativa'];
    $w_emissao              = $_REQUEST['w_emissao'];
    $w_vencimento           = $_REQUEST['w_vencimento'];
    $w_inclusao             = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao     = $_REQUEST['w_ultima_alteracao'];
    $w_conclusao            = $_REQUEST['w_conclusao'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_opiniao              = $_REQUEST['w_opiniao'];
    $w_data_hora            = $_REQUEST['w_data_hora'];
    $w_pais                 = $_REQUEST['w_pais'];
    $w_uf                   = $_REQUEST['w_uf'];
    $w_cidade               = $_REQUEST['w_cidade'];
    $w_palavra_chave        = $_REQUEST['w_palavra_chave'];
    $w_sqcc                 = $_REQUEST['w_sqcc'];
    $w_tipo_rubrica         = $_REQUEST['w_tipo_rubrica'];
    $w_numero_processo      = $_REQUEST['w_numero_processo'];
    $w_protocolo            = $_REQUEST['w_protocolo'];
    $w_protocolo_nm         = $_REQUEST['w_protocolo_nm'];
    $w_qtd_nota             = $_REQUEST['w_qtd_nota'];
    $w_per_ini              = $_REQUEST['w_per_ini'];
    $w_per_fim              = $_REQUEST['w_per_fim'];
    $w_texto_pagamento      = $_REQUEST['w_texto_pagamento'];

    // Recarrega dados do comprovante
    $w_sq_tipo_documento    = $_REQUEST['w_sq_tipo_documento'];
    $w_numero               = $_REQUEST['w_numero'];
    $w_data                 = $_REQUEST['w_data'];
    $w_serie                = $_REQUEST['w_serie'];
    $w_valor_doc            = $_REQUEST['w_valor_doc'];
    $w_patrimonio           = $_REQUEST['w_patrimonio'];
    $w_tipo                 = $_REQUEST['w_tipo'];
  } elseif(strpos('AEV',$O)!==false || $w_copia>'') {
    // Recupera os dados do lançamento

    $sql = new db_getSolicData; 
    if ($w_copia>'') $RS = $sql->getInstanceOf($dbms,$w_copia,$SG);
    else             $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    if (count($RS)>0) {
      $w_sq_unidade           = f($RS,'sq_unidade');
      $w_observacao           = f($RS,'observacao');
      $w_aviso                = f($RS,'aviso_prox_conc');
      $w_dias                 = f($RS,'dias_aviso');
      $w_sq_acordo_parcela    = f($RS,'sq_acordo_parcela');
      $w_sq_tipo_lancamento   = f($RS,'sq_tipo_lancamento');
      $w_pessoa               = f($RS,'pessoa');
      $w_tipo_pessoa          = f($RS,'sq_tipo_pessoa');
      $w_nm_tipo_pessoa       = f($RS,'nm_tipo_pessoa');
      $w_sq_forma_pagamento   = f($RS,'sq_forma_pagamento');
      $w_forma_atual          = f($RS,'sq_forma_pagamento');
      $w_codigo_interno       = f($RS,'codigo_interno');
      $w_chave_pai            = f($RS,'sq_solic_pai');
      $w_chave_aux            = null;
      $w_sq_menu              = f($RS,'sq_menu');
      $w_sq_unidade           = f($RS,'sq_unidade');
      $w_sq_tramite           = f($RS,'sq_siw_tramite');
      $w_solicitante          = f($RS,'solicitante');
      $w_cadastrador          = f($RS,'cadastrador');
      $w_executor             = f($RS,'executor');
      $w_descricao            = f($RS,'descricao');
      $w_justificativa        = f($RS,'justificativa');
      $w_vencimento           = FormataDataEdicao(f($RS,'fim'));
      $w_vencimento_atual     = FormataDataEdicao(f($RS,'fim'));
      $w_inclusao             = f($RS,'inclusao');
      $w_ultima_alteracao     = f($RS,'ultima_alteracao');
      $w_conclusao            = f($RS,'conclusao');
      $w_opiniao              = f($RS,'opiniao');
      $w_data_hora            = f($RS,'data_hora');
      $w_sqcc                 = f($RS,'sq_cc');
      $w_pais                 = f($RS,'sq_pais');
      $w_uf                   = f($RS,'co_uf');
      $w_cidade               = f($RS,'sq_cidade_origem');
      $w_palavra_chave        = f($RS,'palavra_chave');
      $w_valor                = number_format(f($RS,'valor'),2,',','.');
      $w_tipo_rubrica         = f($RS,'tipo_rubrica');
      $w_numero_processo      = f($RS,'processo');      
      $w_protocolo            = f($RS,'processo');
      $w_protocolo_nm         = f($RS,'processo');
      $w_nm_tipo_rubrica      = f($RS,'nm_tipo_rubrica');
      $w_qtd_nota             = f($RS,'qtd_nota');
      $w_per_ini              = FormataDataEdicao(f($RS,'referencia_inicio'));
      $w_per_fim              = FormataDataEdicao(f($RS,'referencia_fim'));
      $w_texto_pagamento      = f($RS,'condicoes_pagamento');
      $w_cpf                  = f($RS,'cpf');
      $w_cnpj                 = f($RS,'cnpj');
      $w_nome                 = f($RS,'nm_pessoa');
      $w_nome_resumido        = f($RS,'nm_pessoa_resumido');
      $w_sexo                 = f($RS,'sexo');
      $w_vinculo              = f($RS,'sq_tipo_vinculo');
      $w_solic_vinculo        = f($RS,'sq_solic_vinculo');
      $w_uf                   = f($RS,'co_uf');
      $w_sq_prop              = f($RS,'sq_prop');
      $w_dados_pai            = explode('|@|',f($RS,'dados_pai'));
      $w_sq_menu_relac        = $w_dados_pai[3];
      if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
    }

    if (nvl($w_copia,'')=='') {
      // Recupera dados do comprovante
      $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,$w_chave,null,null,null,null,null,null,'DOCS');
      $RS = SortArray($RS,'sq_tipo_documento','asc');
      foreach ($RS as $row) {$RS=$row; break;}
      $w_chave_doc           =  f($RS,'sq_lancamento_doc');
      $w_sq_tipo_documento    = f($RS,'sq_tipo_documento');
      $w_numero               = f($RS,'numero');
      $w_data                 = FormataDataEdicao(f($RS,'data'));
      $w_serie                = f($RS,'serie');
      $w_valor_doc            = formatNumber(f($RS,'valor'));
      $w_patrimonio           = f($RS,'patrimonio');
      $w_tributo              = f($RS,'calcula_tributo');
      $w_retencao             = f($RS,'calcula_retencao');
    }
  }

  // Recupera a sigla do tipo do documento para tratar a Nota Fiscal
  if ($w_sq_tipo_documento>'') {
    $sql = new db_getTipoDocumento; $RS2 = $sql->getInstanceOf($dbms,$w_sq_tipo_documento,$w_cliente,null);
    foreach ($RS2 as $row) { $w_tipo = f($row,'sigla'); break; }
  } 
  
  // Default: pagamento para pessoa jurídica
  $w_tipo_pessoa = nvl($w_tipo_pessoa,2);
  
  // Tenta recuperar os dados do beneficiário
  if (Nvl($w_tipo_pessoa,'')!='' && (Nvl($w_pessoa,'')!='' || Nvl($_REQUEST['w_cpf'],'')!='' || Nvl($_REQUEST['w_cnpj'],'')!='')) {
    // Recupera os dados do proponente
    $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_pessoa,null,$_REQUEST['w_cpf'],$_REQUEST['w_cnpj'],null,$w_tipo_pessoa,null,null,null,null,null,null,null, null, null, null, null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_cpf           = f($RS,'cpf');
      $w_cnpj          = f($RS,'cnpj');
      $w_sq_prop       = f($RS,'sq_pessoa');
      $w_nome          = f($RS,'nm_pessoa');
      $w_nome_resumido = f($RS,'nome_resumido');
      $w_sexo          = f($RS,'sexo');
      $w_vinculo       = f($RS,'sq_tipo_vinculo');
    }
  }
  
  // Recupera as possibilidades de vinculação
  $w_exige_relac = true;
  if (f($RS_Menu,'solicita_cc')=='N') {
    $sql = new db_getMenuRelac; $RS = $sql->getInstanceOf($dbms, $w_menu, null, null, null, 'SERVICO');
    if (count($RS)==1) {
      $w_exige_relac = false;
      foreach($RS as $row) $w_sq_menu_relac = f($row,'sq_menu');
    }
  }
  
  // Recupera o trâmite de conclusão
  $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_menu,null,null,null);
  $RS = SortArray($RS,'ordem','asc');
  foreach ($RS as $row) {
    if (f($row,'sigla')=='AT') {
      $w_tramite_conc = f($row,'sq_siw_tramite');
      break;
    }
  }   
  
  // Recupera dados do fundo fixo
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave_pai,'FNDFIXO');
  
  if (nvl($w_solic_vinculo,'')!='') {
    // Recupera dados da solicitação de compra
    $sql = new db_getLinkData; $RSL = $sql->getInstanceOf($dbms,$w_cliente,'CLPCCAD');
    $sql = new db_getSolicCL; $RS_Vinculo = $sql->getInstanceOf($dbms,f($RSL,'sq_menu'),$w_usuario,f($RSL,'sigla'),5,
        null,null,null,null,null,null,null,null,null,null,$w_solic_vinculo, null, null, null, null, null, null,
        null, null, null, null, null, null, null,null, null, null, null);
    foreach($RS_Vinculo as $row) { $RS_Vinculo = $row; break; }
    $w_justificativa = f($RS_Vinculo,'justificativa');
    $w_objeto        = f($RS_Vinculo,'descricao');
    if (nvl($_REQUEST['w_descricao'],'')!='') {
      if     ($_REQUEST['w_descricao']==$w_descricao)     $w_descricao = nvl($w_objeto,$w_justificativa);
      elseif ($_REQUEST['w_descricao']==$w_justificativa) $w_descricao = nvl($w_objeto,$w_justificativa);
    } else {
      $w_descricao = nvl($w_objeto,$w_justificativa);
    }
  }

  if(nvl($w_sq_menu_relac,0)>0) { $sql = new db_getMenuData; $RS_Relac  = $sql->getInstanceOf($dbms,$w_sq_menu_relac); }

  // Recupera acréscimos e supressões possíveis para o lançamento financeiro
  $sql = new db_getLancamentoValor; $RS_Valores = $sql->getInstanceOf($dbms,$w_cliente,$w_menu,$w_chave,$w_sq_lancamento_doc,null,'EDICAO');
  $RS_Valores = SortArray($RS_Valores,'tp_valor','desc','ordenacao','asc');
  $i=0;
  unset($w_valores);
  foreach ($RS_Valores as $row) {
    $i++;
    $w_valores[$i]['chave'] = f($row,'sq_valores');
    $w_valores[$i]['nome']  = f($row,'nome');
    $w_valores[$i]['tipo']  = f($row,'tp_valor');
    $w_valores[$i]['valor'] = formatNumber(nvl(f($row,'valor'),0));
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
  FormataCPF();
  FormataCNPJ();
  Modulo();
  ShowHTML('function botoes() {');
  ShowHTML('  document.Form.Botao[0].disabled = true;');
  ShowHTML('  document.Form.Botao[1].disabled = true;');
  ShowHTML('}');
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    if ($w_exige_relac) Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
    if(nvl($w_sq_menu_relac,'')!='') {
      if ($w_sq_menu_relac=='CLASSIF') {
        Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
      } else {
        Validate('w_chave_pai','Vinculação','SELECT',1,1,18,1,1);
      }
    }
    if($w_segmento=='Público') {
      Validate('w_numero_processo','Número do processo','1','',1,30,'1','1');
    }
    Validate('w_sq_tipo_lancamento','Tipo do lançamento','SELECT',1,1,18,'','0123456789');
    Validate('w_descricao','Finalidade','1',1,5,2000,'1','1');
    if ($w_cliente!=10135) Validate('w_tipo_pessoa','Lançamento para pessoa','SELECT',1,1,18,'','0123456789');   
    Validate('w_sq_forma_pagamento','Forma de recebimento','SELECT',1,1,18,'','0123456789');       
    Validate('w_vencimento','Data de pagamento','DATA',1,10,10,'','0123456789/');
    if ($w_tipo_pessoa==1) Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
    else                   Validate('w_cnpj','CNPJ','CNPJ','1','18','18','','0123456789/-.');
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    if ($w_cliente!=10135) {
      Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
    } else {
     ShowHTML('  if (theForm.w_nome_resumido.value=="") {');
     ShowHTML('    theForm.w_nome_resumido.value=theForm.w_nome.value.substr(0,20);');
     ShowHTML('}');
    }
    if ($w_tipo_pessoa==1) Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
    Validate('w_sq_tipo_documento','Tipo do documento', '1', '1', '1', '18', '', '0123456789');
    Validate('w_numero','Número do documento', '1', '1', '1', '30', '1', '1');
    Validate('w_data','Data de emissão do documento', 'DATA', '1', '10', '10', '', '0123456789/');
    Validate('w_valor','Valor total do documento','VALOR','1',4,18,'','0123456789.,');
    //if (Nvl($w_tipo,'-')=='NF') Validate('w_serie','Série do documento', '1', '1', 1, 10, '1', '1');
    //Validate('w_valor_doc','Valor total do documento', 'VALOR', '1', 4, 18, '', '0123456789.,');
    if (is_array($w_valores)) {
      ShowHTML('  for (ind=1; ind < theForm["w_valores[]"].length; ind++) {');
      Validate('["w_valores[]"][ind]','!','VALOR','1','4','18','','0123456789.,');
      ShowHTML('  }');
    }
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'')                               BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif (!(strpos('EV',$O)===false))            BodyOpen('onLoad=\'this.focus()\';');
  elseif ($w_exige_relac)                        BodyOpen('onLoad=\'document.Form.w_sq_menu_relac.focus()\';');
  else                                           BodyOpen('onLoad=\'document.Form.w_chave_pai.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($w_chave>'') ShowHTML('      <tr><td><font size="2"><b>'.$w_codigo_interno.' ('.$w_chave.')</b></td>');
  if (strpos('IAEV',$O)!==false) {
    if (Nvl($w_pais,'')=='') {
      // Carrega os valores padrão para país, estado e cidade
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = Nvl(f($RS_Menu,'sq_cidade'),f($RS,'sq_cidade_padrao'));
    } 
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    }  
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite_conc.'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_solicitante" value="'.$_SESSION['SQ_PESSOA'].'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.f($RS_Menu,'sq_unid_executora').'">');
    ShowHTML('<INPUT type="hidden" name="w_forma_atual" value="'.$w_forma_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_vencimento_atual" value="'.$w_vencimento_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_parcela" value="'.$w_sq_acordo_parcela.'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    ShowHTML('<INPUT type="hidden" name="w_dias" value="3">');
    ShowHTML('<INPUT type="hidden" name="w_codigo_interno" value="'.$w_codigo_interno.'">');
    ShowHTML('<INPUT type="hidden" name="w_qtd_nota" value="'.$w_qtd_nota.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_doc" value="'.$w_chave_doc.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan=2 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2>Os dados deste bloco serão utilizados para identificação do lançamento, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');

    ShowHTML('          <tr valign="top">');
    if ($w_exige_relac) selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($w_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($w_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null);
      } else {
        if ($w_mod_pa=='S') {
          $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'FNDFIXO');
          $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),6,
                null,null,null,null,null,null, null,null,null,null,$w_chave_pai, null, null, null, null, null, null,
                null, null, null, null, null, null, null, null, null);

          ShowHTML('          <td colspan="4"><b><u>V</u>inculação</b><br /><SELECT ACCESSKEY="V" CLASS="sts" NAME="w_chave_pai" '.$w_Disabled.' onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_tipo_lancamento\'; document.Form.submit();">');
          if (count($RS)==0) {
            ShowHTML('          <option value="">Nenhum registro encontrado!');
          } else {
            ShowHTML('          <option value="">---');
            foreach($RS as $row) {
              ShowHTML('          <option value="'.f($row,'sq_siw_solicitacao').'"'.((f($row,'sq_siw_solicitacao')==nvl($w_chave_pai,0) || count($RS)==1) ? ' SELECTED' : '').'>'.f($row,'protocolo'));
            }
          }
          ShowHTML('          </select>');
        } else {
          SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_chave_pai,$w_sq_menu_relac,$w_menu,'w_chave_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_tipo_lancamento\'; document.Form.submit();"',$w_chave_pai);
          if (nvl(f($RS_Relac,'sigla'),'')!='') $sql = new db_getSolicData; $RS_Pai = $sql->getInstanceOf($dbms,$w_chave_pai,f($RS_Relac,'sigla'));

          if ($w_mod_pa=='S' && nvl($w_chave_pai,'')!='') ShowHTML('         <td><b>N<U>ú</U>mero do processo:<br><INPUT ACCESSKEY="U" READONLY class="STI" type="text" name="w_protocolo_nm" size="20" maxlength="30" value="'.f($RS_Pai,'processo').'"></td>');
        }
      }
    }

    if ($w_mod_co=='S') { 
      $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'CLPCCAD');
      ShowHTML('      <tr>');
      SelecaoSolic('Solicitação de compra:',null,null,$w_cliente,$w_solic_vinculo,'COMPRA_FUNDO',f($RS,'sq_menu'),'w_solic_vinculo',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_solic_vinculo\'; document.Form.submit();"',null,'<BR />',3);
      if (nvl($w_solic_vinculo,'')!='') {
        $sql = new db_getSolicCL; 
        $RS_Compra = $sql->getInstanceOf($dbms,null,$w_usuario,f($RS,'sigla'),5,null,null,null,null,null,null,null,null,null,null,
                            $w_solic_vinculo,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS_Compra as $row) {$RS_Compra = $row; break; }
        ShowHTML('      <tr valign="top">');
        ShowHTML('        <td><b>Valor da compra:</b><br>'.formatNumber(f($RS_Compra,'valor')).'</td>');
        if (nvl(f($RS_Compra,'descricao'),'')!='') {
          ShowHTML('        <td><b>Objeto:</b><br>'.crlf2br(f($RS_Compra,'descricao')).'</td>');
        } else {
          ShowHTML('        <td><b>Justificativa:</b><br>'.crlf2br(f($RS_Compra,'justificativa')).'</td>');
        }
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      <tr>');
    SelecaoTipoLancamento('<u>T</u>ipo de pagamento:','T','Selecione na lista o tipo de pagamento adequado.',$w_sq_tipo_lancamento,$w_menu,$w_cliente,'w_sq_tipo_lancamento',substr($SG,0,3).'VINC',null,2);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td colspan=2><b><u>F</u>inalidade:</b><br><textarea '.$w_Disabled.' accesskey="F" name="w_descricao" class="sti" ROWS=3 cols=75 title="Finalidade do lançamento.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%">');
    ShowHTML('        <tr valign="top">');
    if ($w_cliente!=10135) {
      SelecaoTipoPessoa('Lançamento para pessoa:','T','Selecione na lista o tipo de pessoa associada a este lançamento.',$w_tipo_pessoa,$w_cliente,'w_tipo_pessoa',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_forma_pagamento\'; document.Form.submit();"',null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="'.$w_tipo_pessoa.'">');
    }
    SelecaoFormaPagamento('<u>F</u>orma de pagamento:','F','Selecione na lista a forma de pagamento para este lançamento.',$w_sq_forma_pagamento,$SG,'w_sq_forma_pagamento',null);
    ShowHTML('              <td><b><u>D</u>ata de pagamento:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_vencimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_vencimento,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_vencimento').'</td>');

    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" valign="top" align="center" bgcolor="#D0D0D0"><b>Dados do Beneficiário</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    if ($w_tipo_pessoa==1) ShowHTML('        <tr><td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);" onBlur="botoes(); document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nome\'; document.Form.submit();">');
    else                   ShowHTML('        <tr><td><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cnpj" VALUE="'.$w_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);" onBlur="botoes(); document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nome\'; document.Form.submit();">');
    if ($w_cliente!=10135) {
      ShowHTML('            <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('            <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
    } else {
      ShowHTML('            <td colspan="2"><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('<INPUT type="hidden" name="w_nome_resumido" value="'.$w_nome_resumido.'">');
    }
    if ($w_tipo_pessoa==1) SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);

    // Recupera o tipo de vínculo
    $sql = new db_getVincKindList; $RS = $sql->getInstanceOf($dbms, $w_cliente, 'S', (($w_tipo_pessoa==1) ? 'Física' : 'Jurídica'), 'Fornecedor', null);
    foreach($RS as $row) { ShowHTML('<INPUT type="hidden" name="w_vinculo" value="'.f($row,'sq_tipo_vinculo').'">'); break; }
    
    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" valign="top" align="center" bgcolor="#D0D0D0"><b>Documento de despesa</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoDocumento('<u>T</u>ipo do documento:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,$w_menu,'w_sq_tipo_documento',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_numero\'; document.Form.submit();"');
    ShowHTML('          <td><b><u>N</u>úmero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o número do documento."></td>');
    ShowHTML('          <td><b><u>E</u>missão:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data do documento.">'.ExibeCalendario('Form','w_data').'</td>');
    //if (Nvl($w_tipo,'-')=='NF') ShowHTML('          <td><b><u>S</u>érie:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_serie" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_serie.'" title="Informado apenas se o documento for NOTA FISCAL. Informe a série ou, se não tiver, digite ÚNICA."></td>');
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    //ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_doc" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_doc.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    ShowHTML('<INPUT type="hidden" name="w_valor_doc" value="'.$w_valor_doc.'">');
    if (is_array($w_valores)){
      ShowHTML('<INPUT type="hidden" name="w_sq_valores[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_valores[]" value="">');
      foreach($w_valores as $row) {
        ShowHTML('<INPUT type="hidden" name="w_sq_valores[]" value="'.f($row,'chave').'">');
        ShowHTML('      <tr><td colspan="3" align="right"><b>'.f($row,'nome').':</b><td><input '.$w_Disabled.' accesskey="V" type="text" name="w_valores[]" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.f($row,'valor').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
      }
    }
    
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
     $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
     ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
    //ShowHTML ' history.back(1);'
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
// Rotina de cadastramento da outra parte
// -------------------------------------------------------------------------
function OutraParte() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='') $O='P';
  $w_erro='';
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_cpf            = $_REQUEST['w_cpf'];
  $w_cnpj           = $_REQUEST['w_cnpj'];
  $w_sq_pessoa      = $_REQUEST['w_sq_pessoa'];
  $w_pessoa_atual   = $_REQUEST['w_pessoa_atual'];
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  $w_dados_pai      = explode('|@|',f($RS1,'dados_pai'));
  $w_sigla_pai      = $w_dados_pai[5];
  
  if ($w_sq_pessoa=='' && (strpos($_REQUEST['Botao'],'Selecionar')===false)) {
    $w_sq_pessoa    =f($RS1,'pessoa');
    $w_pessoa_atual =f($RS1,'pessoa');
  } elseif (strpos($_REQUEST['Botao'],'Selecionar')===false) {
    $w_sq_banco         = f($RS1,'sq_banco');
    $w_sq_agencia       = f($RS1,'sq_agencia');
    $w_operacao         = f($RS1,'operacao_conta');
    $w_nr_conta         = f($RS1,'numero_conta');
    $w_sq_pais_estrang  = f($RS1,'sq_pais_estrang');
    $w_aba_code         = f($RS1,'aba_code');
    $w_swift_code       = f($RS1,'swift_code');
    $w_endereco_estrang = f($RS1,'endereco_estrang');
    $w_banco_estrang    = f($RS1,'banco_estrang');
    $w_agencia_estrang  = f($RS1,'agencia_estrang');
    $w_cidade_estrang   = f($RS1,'cidade_estrang');
    $w_informacoes      = f($RS1,'informacoes');
    $w_codigo_deposito  = f($RS1,'codigo_deposito');
  } 
  $w_forma_pagamento    = f($RS1,'sg_forma_pagamento');
  $w_tipo_pessoa        = f($RS1,'sq_tipo_pessoa');
   
  if (Nvl($w_sq_pessoa,0)==0) { $O='I'; } else { $O='A'; }
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
    $w_passaporte_numero    = $_REQUEST['w_passaporte_numero'];
    $w_sq_pais_passaporte   = $_REQUEST['w_sq_pais_passaporte'];
    $w_sexo                 = $_REQUEST['w_sexo'];
    $w_cnpj                 = $_REQUEST['w_cnpj'];
    $w_inscricao_estadual   = $_REQUEST['w_inscricao_estadual'];
  } elseif (strpos($_REQUEST['Botao'],'Alterar')===false && strpos($_REQUEST['Botao'],'Procurar')===false && ($O=='A' || $w_sq_pessoa>'' || $w_cpf>'' || $w_cnpj>'')) {
    // Recupera os dados do beneficiário em co_pessoa
    $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,$w_cpf,$w_cnpj,null,null,null,null,null,null,null,null,null, null, null, null, null);
    foreach ($RS as $row) {$RS=$row; break;}
    if (count($RS) > 0) {
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
      $w_passaporte_numero    = f($RS,'passaporte_numero');
      $w_sq_pais_passaporte   = f($RS,'sq_pais_passaporte');
      $w_sexo                 = f($RS,'sexo');
      $w_cnpj                 = f($RS,'cnpj');
      $w_inscricao_estadual   = f($RS,'inscricao_estadual');
      if (strpos('CREDITO,DEPOSITO',$w_forma_pagamento)!==false) {
        if (Nvl($w_nr_conta,'')=='') {
          $w_sq_banco     = f($RS,'sq_banco');
          $w_sq_agencia   = f($RS,'sq_agencia');
          $w_operacao     = f($RS,'operacao');
          $w_nr_conta     = f($RS,'nr_conta');
        } 
      } 
    } 
  } 

  // Recupera informação do campo operação do banco selecionado
  if (nvl($w_sq_banco,'')>'') {
    $sql = new db_getBankData; $RS_Banco = $sql->getInstanceOf($dbms, $w_sq_banco);
    $w_exige_operacao = f($RS_Banco,'exige_operacao');
  }
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Pessoa</TITLE>');
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
  if (($w_cpf=='' && $w_cnpj=='') || (!(strpos($_REQUEST['Botao'],'Procurar')===false)) || (!(strpos($_REQUEST['Botao'],'Alterar')===false))) {
    // Se o beneficiário ainda não foi selecionado
    ShowHTML('  if (theForm.Botao.value == "Procurar") {');
    Validate('w_nome','Nome','','1','4','20','1','');
    ShowHTML('  theForm.Botao.value = "Procurar";');
    ShowHTML('}');
    ShowHTML('else {');
    if ($w_tipo_pessoa==1) Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
    else                   Validate('w_cnpj','CNPJ','CNPJ','1','18','18','','0123456789/-.');
    ShowHTML('  theForm.w_sq_pessoa.value = \'\';');
    ShowHTML('}');
  } elseif ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value.indexOf(\'Alterar\') >= 0) { return true; }');
    if (Nvl($w_sq_pessoa,'')=='') {
      Validate('w_nome','Nome','1',1,5,60,'1','1');
      Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
    } 
    if ($w_tipo_pessoa==1) {
      Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
      if ($w_sigla_pai=='FNDFIXO') {
        Validate('w_rg_numero','Identidade','1','',2,30,'1','1');
        Validate('w_rg_emissor','Órgão expedidor','1','',2,30,'1','1');
      } else {
        Validate('w_rg_numero','Identidade','1',1,2,30,'1','1');
        Validate('w_rg_emissor','Órgão expedidor','1',1,2,30,'1','1');
      }
    } else {
      Validate('w_inscricao_estadual','Inscrição estadual','1','',2,20,'1','1');
    } 
    if ($w_sigla_pai=='FNDFIXO') {
      Validate('w_ddd','DDD','1','',2,4,'','0123456789');
      Validate('w_nr_telefone','Telefone','1','',7,25,'1','1');
      Validate('w_nr_fax','Fax','1','',7,25,'1','1');
      Validate('w_nr_celular','Celular','1','',7,25,'1','1');
      ShowHTML('  if (theForm.w_ddd.value=="" && (theForm.w_nr_telefone.value!="" || theForm.w_nr_fax.value!="" || theForm.w_nr_celular.value!="")) {');
      ShowHTML('     alert("Se telefone, fax ou celular forem indicados, é obrigatório informar seu DDD!");');
      ShowHTML('     document.Form.w_ddd.focus();');
      ShowHTML('     return false;');
      ShowHTML('  } else if (theForm.w_ddd.value!="" && (theForm.w_nr_telefone.value=="" && theForm.w_nr_fax.value=="" && theForm.w_nr_celular.value=="")) {');
      ShowHTML('     alert("Se DDD for indicado, informe pelo menos o telefone. Fax e celular são opcionais!");');
      ShowHTML('     document.Form.w_nr_telefone.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_logradouro','Logradouro','1','',4,60,'1','1');
      Validate('w_complemento','Complemento','1','',2,20,'1','1');
      Validate('w_bairro','Bairro','1','',2,30,'1','1');
      Validate('w_sq_pais','País','SELECT','',1,10,'1','1');
      Validate('w_co_uf','UF','SELECT','',1,10,'1','1');
      Validate('w_sq_cidade','Cidade','SELECT','',1,10,'','1');
      Validate('w_cep','CEP','1','',9,9,'','0123456789-');
      ShowHTML('  if (theForm.w_logradouro.value=="" && (theForm.w_complemento.value!="" || theForm.w_bairro.value!="" || theForm.w_cep.value!="" || theForm.w_sq_pais.selectedIndex>0 || theForm.w_co_uf.valueselectedIndex>0 || theForm.w_sq_cidade.valueselectedIndex>0)) {');
      ShowHTML('     alert("Se pais, estado ou cidade forem indicados, é obrigatório informar o logradouro!");');
      ShowHTML('     document.Form.w_logradouro.focus();');
      ShowHTML('     return false;');
      ShowHTML('  } else if (theForm.w_logradouro.value!="" && (theForm.w_sq_pais.selectedIndex==0 || theForm.w_co_uf.selectedIndex==0 || theForm.w_sq_cidade.selectedIndex==0)) {');
      ShowHTML('     alert("Se logradouro for indicado, informe pais, estado e cidade!");');
      ShowHTML('     document.Form.w_logradouro.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
    } else {
      Validate('w_ddd','DDD','1','1',2,4,'','0123456789');
      Validate('w_nr_telefone','Telefone','1',1,7,25,'1','1');
      Validate('w_nr_fax','Fax','1','',7,25,'1','1');
      Validate('w_nr_celular','Celular','1','',7,25,'1','1');
      Validate('w_logradouro','Logradouro','1',1,4,60,'1','1');
      Validate('w_complemento','Complemento','1','',2,20,'1','1');
      Validate('w_bairro','Bairro','1','',2,30,'1','1');
      Validate('w_sq_pais','País','SELECT',1,1,10,'1','1');
      Validate('w_co_uf','UF','SELECT',1,1,10,'1','1');
      Validate('w_sq_cidade','Cidade','SELECT',1,1,10,'','1');
      if (Nvl($w_pd_pais,'S')=='S') Validate('w_cep','CEP','1','',9,9,'','0123456789-');
      else                          Validate('w_cep','CEP','1',1,5,9,'','0123456789');
    }
    Validate('w_email','E-Mail','1','',4,60,'1','1');
    if (substr(f($RS1,'sigla'),0,3)=='FND') {
      if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
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
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    if (!(strpos('CONT',f($RS_Menu,'sigla'))===false)) {
      // Se não for lançamento para parcela de contrato
      ShowHTML('  theForm.Botao[2].disabled=true;');
    } 
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (($w_cpf=='' && $w_cnpj=='') || !(strpos($_REQUEST['Botao'],'Alterar')===false) || !(strpos($_REQUEST['Botao'],'Procurar')===false)) {
    // Se o beneficiário ainda não foi selecionado
    if (!(strpos($_REQUEST['Botao'],'Procurar')===false)) {
      // Se está sendo feita busca por nome
      BodyOpen('onLoad=\'this.focus()\';');
    } else {
      if ($w_tipo_pessoa==1) BodyOpen('onLoad=\'document.Form.w_cpf.focus()\';');
      else                   BodyOpen('onLoad=\'document.Form.w_cnpj.focus()\';');
    } 
  } elseif ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    if (Nvl($w_sq_pessoa,'')>'') {
      if ($w_tipo_pessoa==1) {
        BodyOpen('onLoad=\'document.Form.w_sexo.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.w_inscricao_estadual.focus()\';');
      } 
    } else {
      BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
    } 
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr><td colspan=3><b>'.upper(f($RS1,'nome')).' '.f($RS1,'codigo_interno').' ('.$w_chave.')</b></td>');
  ShowHTML('      <tr><td colspan="3">Finalidade: <b>'.CRLF2BR(f($RS1,'descricao')).'</b></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td>Forma de pagamento:<br><b>'.f($RS1,'nm_forma_pagamento').' </b></td>');
  ShowHTML('          <td>Vencimento:<br><b>'.FormataDataEdicao(f($RS1,'vencimento')).' </b></td>');
  ShowHTML('          <td>Valor:<br><b>'.number_format(Nvl(f($RS1,'valor'),0),2,',','.').' </b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('  <tr><td>&nbsp;');
  if (strpos('IA',$O)!==false) {
    if (($w_cpf=='' && $w_cnpj=='') || (strpos($_REQUEST['Botao'],'Alterar')!==false) || !(strpos($_REQUEST['Botao'],'Procurar')===false)) {
      // Se o beneficiário ainda não foi selecionado
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    } else {
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
      if (Nvl($w_sq_pessoa,'')>'') {
        ShowHTML('<INPUT type="hidden" name="w_nome" value="'.$w_nome.'">');
        ShowHTML('<INPUT type="hidden" name="w_nome_resumido" value="'.$w_nome_resumido.'">');
      } 
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
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="'.$w_pessoa_atual.'">');
    if (($w_cpf=='' && $w_cnpj=='') || !(strpos($_REQUEST['Botao'],'Alterar')===false) || !(strpos($_REQUEST['Botao'],'Procurar')===false)) {
      $w_nome=$_REQUEST['w_nome'];
      if (!(strpos($_REQUEST['Botao'],'Alterar')===false)) {
        $w_cpf='';
        $w_cnpj='';
        $w_nome='';
      } 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('        <tr><td colspan=4><font size=2>Informe os dados abaixo e clique no botão "Selecionar" para continuar.</TD>');
      if ($w_tipo_pessoa==1) ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      else                   ShowHTML('        <tr><td colspan=4><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cnpj" VALUE="'.$w_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
      ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('            <INPUT class="stb" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Cancelar">');
      ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
      ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
      ShowHTML('        <tr><td colspan=4>');
      ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
      ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('      </table>');
      if ($w_nome>'') {
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,$w_nome,$w_tipo_pessoa,null,null,null,null,null,null,null, null, null, null, null);
        $RS = SortArray($RS,'nm_pessoa','asc');
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('          <td><b>Nome resumido</td>');
        if ($w_tipo_pessoa==1)  ShowHTML('          <td><b>CPF</td>');
        else                    ShowHTML('          <td><b>CNPJ</td>');
        ShowHTML('          <td><b>Operações</td>');
        ShowHTML('        </tr>');
        if (count($RS)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não há pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            if ($w_tipo_pessoa==1) ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
            else                   ShowHTML('        <td align="center">'.Nvl(f($row,'cnpj'),'---').'</td>');
            ShowHTML('        <td nowrap>');
            if ($w_tipo_pessoa==1) ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_cpf='.f($row,'cpf').'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            else                   ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_cnpj='.f($row,'cnpj').'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
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
      if (Nvl($w_sq_pais,'')=='' && $w_sigla_pai!='FNDFIXO') {
        // Carrega os valores padrão para país, estado e cidade
        $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
        $w_sq_pais    = f($RS,'sq_pais');
        $w_co_uf      = f($RS,'co_uf');
        $w_sq_cidade  = f($RS,'sq_cidade_padrao');
      } 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center"><font color="#BC5151"><b>ATENÇÃO: Para garantir a gravaçao dos dados bancários, clique sobre o botão "Gravar".</b></font></td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      if ($w_tipo_pessoa==1) {
        ShowHTML('          <td>CPF:<br><b><font size=2>'.$w_cpf);
        ShowHTML('              <INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
      } else {
        ShowHTML('          <td>CNPJ:<br><b><font size=2>'.$w_cnpj);
        ShowHTML('              <INPUT type="hidden" name="w_cnpj" value="'.$w_cnpj.'">');
      } 
      if (Nvl($w_sq_pessoa,'')>'') {
        ShowHTML('             <td>Nome completo:<b><br>'.$w_nome.'</td>');
        ShowHTML('             <td>Nome resumido:<b><br>'.$w_nome_resumido.'</td>');
      } else {
        ShowHTML('          <tr valign="top">');
        ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
        ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
      } 
      if ($w_tipo_pessoa==1) {
        ShowHTML('          <tr valign="top">');
        SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
        ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
        ShowHTML('          <td><b>Ór<u>g</u>ão emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="10" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
      } else {
        ShowHTML('      <tr><td colspan="3"><b><u>I</u>nscrição estadual:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inscricao_estadual" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_inscricao_estadual.'"></td>');
      } 
      ShowHTML('          </table>');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      if ($w_tipo_pessoa==1) ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Endereço comercial, Telefones e e-Mail</td></td></tr>');
      else                   ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Endereço principal, Telefones e e-Mail</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ddd.'"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_nr_telefone.'"> '.consultaTelefone($w_cliente).'</td>');
      ShowHTML('          <td title="Se a pessoa informar um número de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_fax.'"></td>');
      ShowHTML('          <td title="Se a pessoa informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_celular.'"></td>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td colspan=2><b>En<u>d</u>ereço:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_logradouro" class="sti" SIZE="50" MAXLENGTH="50" VALUE="'.$w_logradouro.'"></td>');
      ShowHTML('          <td><b>C<u>o</u>mplemento:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_complemento" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_complemento.'"></td>');
      ShowHTML('          <td><b><u>B</u>airro:</b><br><input '.$w_Disabled.' accesskey="B" type="text" name="w_bairro" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_bairro.'"></td>');
      ShowHTML('          <tr valign="top">');
      SelecaoPais('<u>P</u>aís:','P',null,$w_sq_pais,null,'w_sq_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_co_uf\'; document.Form.submit();"');
      ShowHTML('          <td>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$w_co_uf,$w_sq_pais,null,'w_co_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$w_sq_cidade,$w_sq_pais,$w_co_uf,'w_sq_cidade',null,null);
      ShowHTML('          <tr valign="top">');
      if (Nvl($w_pd_pais,'S')=='S') {
        ShowHTML('              <td><b>C<u>E</u>P:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_cep" class="sti" SIZE="9" MAXLENGTH="9" VALUE="'.$w_cep.'" onKeyDown="FormataCEP(this,event);"></td>');
      } else {
        ShowHTML('              <td><b>C<u>E</u>P:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_cep" class="sti" SIZE="9" MAXLENGTH="9" VALUE="'.$w_cep.'"></td>');
      } 
      ShowHTML('              <td colspan=3 title="Se a pessoa informar um e-mail institucional, informe-o neste campo."><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="sti" SIZE="50" MAXLENGTH="60" VALUE="'.$w_email.'"></td>');
      ShowHTML('          </table>');
      if (substr(f($RS_Menu,'sigla'),0,3)!='FNR') {
        // Se não for lançamento de receita
        if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
          ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados bancários</td></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('      <tr valign="top">');
          SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde deverão ser feitos os pagamentos referentes ao lançamento.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
          SelecaoAgencia('A<u>g</u>ência:','A','Selecione a agência onde deverão ser feitos os pagamentos referentes ao lançamento.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
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
          SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde deverão ser feitos os pagamentos referentes ao lançamento.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
          SelecaoAgencia('A<u>g</u>ência:','A','Selecione a agência onde deverão ser feitos os pagamentos referentes ao lançamento.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
        } elseif ($w_forma_pagamento=='EXTERIOR') {
          ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados da conta no exterior</td></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><b><font color="#BC3131">ATENÇÃO:</font></b> É obrigatório o preenchimento de um destes campos: Swift Code, ABA Code ou Endereço da Agência.</td></tr>');
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
      } 
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
      if (strpos(f($RS_Menu,'sigla'),'CONT')===false && strpos(f($RS_Menu,'sigla'),'VIA')===false) {
        // Se não for lançamento para parcela de contrato
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Alterar pessoa" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.submit();">');
      } 
      ShowHTML('            <input class="stb" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Cancelar">');
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
} 
// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = upper(trim($_REQUEST['w_tipo']));
  if ($w_tipo=='PDF') {
    headerpdf('Visualização de '.f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean(null); 
  if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);
  $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  // Chama a rotina de visualização dos dados do lançamento, na opção 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  ScriptOpen('JavaScript');
  ShowHTML('  var comando, texto;');
  ShowHTML('  if (window.name!="content") {');
  ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
  ShowHTML('  }');
  ScriptClose();
  if ($w_tipo=='PDF') RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
} 
// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
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
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados do lançamento, na opção 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Excluir">');
  if ($P1==0) {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } else {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  }
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
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
// Rotina de tramitação
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  // Se envio de reembolso, chama a rotina de envio sem indicação de destinatário
  if ($SG=='FNDREEMB') {
    EncAutomatico();
    exit();
  }
  
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_erro       = '';
  if ($w_troca>'') {
    // Se for recarga da página
    $w_tramite=$_REQUEST['w_tramite'];
    $w_destinatario=$_REQUEST['w_destinatario'];
    $w_novo_tramite=$_REQUEST['w_novo_tramite'];
    $w_despacho=$_REQUEST['w_despacho'];
  } else {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    $w_tramite        = f($RS,'sq_siw_tramite');
    $w_sg_tramite_ant = f($RS,'sg_tramite');
    $w_novo_tramite   = f($RS,'sq_siw_tramite');
  }
   
  // Recupera os dados da solicitação
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  
  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_novo_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');
  if ($w_ativo == 'N') {
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_menu,null, null,'S');
    $RS = SortArray($RS,'ordem','asc');
    foreach ($RS as $row) {
      $w_novo_tramite = f($row,'sq_siw_tramite');
      $w_sg_tramite   = f($row,'sigla');
      break;
    }   
  }
  // Se for envio, executa verificações nos dados da solicitação
  if ($O=='V') $w_erro = ValidaLancamento($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinatário', 'HIDDEN', '1', '1', '10', '', '1');
    Validate('w_despacho','Despacho', '', '1', '1', '2000', '1', '1');
    Validate('w_assinatura','Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
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
    BodyOpen('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
  // Chama a rotina de visualização dos dados do projeto, na opção 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if(($w_sg_tramite_ant=='AT')&&($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0')) {
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b>Não é possível o envio do lançamento enquanto as correções listadas não forem feitas.</b></font></td>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
  } else {  
    if (nvl(f($RS_Solic,'condicoes_pagamento'),'')!='') {
      ShowHTML('      <tr><td bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);" colspan=4><b><font color="#BC3131">');
      ShowHTML('        VERIFIQUE AS CONDIÇÕES ABAIXO ANTES DE EXECUTAR O ENVIO:<ul>');
      ShowHTML('        <li>'.str_replace($crlf,'<li>',f($RS_Solic,'condicoes_pagamento')));
      ShowHTML('        </b></font></td>');
      ShowHTML('      </tr>');
      ShowHTML('<tr><td>&nbsp;');
    }
    if ($P1!=1) {
      // Se não for cadastramento
      if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S'))
        SelecaoFase('<u>F</u>ase do lançamento:','F','Se deseja alterar a fase atual do lançamento, selecione a fase para a qual deseja enviá-la.', $w_novo_tramite, $w_menu, null,'w_novo_tramite', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      else
        SelecaoFase('<u>F</u>ase do lançamento:','F','Se deseja alterar a fase atual do lançamento, selecione a fase para a qual deseja enviá-la.', $w_novo_tramite, $w_tramite, null,'w_novo_tramite', 'ERRO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
      if ($w_sg_tramite=='CI')
        SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para o lançamento.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'CADASTRAMENTO');
      else
        SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione um destinatário para o lançamento na relação.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'USUARIOS');
    } else {
      if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
        SelecaoFase('<u>F</u>ase do lançamento:','F','Se deseja alterar a fase atual do lançamento, selecione a fase para a qual deseja enviá-la.', $w_novo_tramite, $w_menu, null,'w_novo_tramite', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      } else {
        SelecaoFase('<u>F</u>ase do lançamento:','F','Se deseja alterar a fase atual do lançamento, selecione a fase para a qual deseja enviá-la.', $w_novo_tramite, $w_tramite, null,'w_novo_tramite', 'ERRO', null);
      }
      SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione um destinatário para o lançamento na relação.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'USUARIOS');
    }     
    ShowHTML('    <tr><td colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="sti" ROWS=5 cols=75 title="Descreva a ação esperada pelo destinatário na execução do lançamento.">'.$w_despacho.'</TEXTAREA></td>');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Enviar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    }
  }
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
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
// Rotina de tramitação
// -------------------------------------------------------------------------
function EncAutomatico() {
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
    $w_justif_dia_util  = $_REQUEST['w_justif_dia_util'];
  } else {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    $w_inicio           = f($RS,'inicio');
    $w_tramite          = f($RS,'sq_siw_tramite');
    $w_justificativa    = f($RS,'justificativa');
    $w_prazo            = f($RS,'limite_envio');
    $w_antecedencia     = f($RS,'dias_antecedencia');
    $w_justif_dia_util  = f($RS,'justificativa_dia_util');
    $w_fim_semana       = f($RS,'fim_semana');
  }

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');

  if ($w_sg_tramite!='CI') {
    //Verifica a fase anterior para a caixa de seleção da fase.
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,$w_chave,'DEVFLUXO',null);
    $RS = SortArray($RS,'ordem','desc');
    foreach($RS as $row) { $RS = $row; break; }
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  }

  // Se for envio, executa verificações nos dados da solicitação
  if ($O=='V') $w_erro = ValidaLancamento($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (substr(Nvl($w_erro,'nulo'),0,1)!='0' && (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S'))) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($w_sg_tramite!='CI') {
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
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVAUT',$w_pagina.$par,$O);
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
      SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_tramite,$w_chave,'w_novo_tramite','DEVFLUXO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolução):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a solicitação.">'.$w_despacho.'</TEXTAREA></td>');
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
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } elseif ($P1!=1) {
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
  $w_chave=$_REQUEST['w_chave'];
  $w_chave_aux=$_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anotação', '', '1', '1', '2000', '1', '1');
    Validate('w_caminho','Arquivo', '', '', '5', '255', '1', '1');
    Validate('w_assinatura','Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
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
  if ($w_troca>'')
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados do lançamento, na opção 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'ENVIO&O='.$O.'&w_menu='.$w_menu.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
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
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.f($RS,'upload_maximo').'/1024. KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td><b>A<u>n</u>otação:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="sti" ROWS=5 cols=75 title="Redija a anotação desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
  if ($P1==0) {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } else {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  }
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
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
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_quitacao           = $_REQUEST['w_quitacao'];
    $w_valor_real         = $_REQUEST['w_valor_real'];
    $w_codigo_deposito    = $_REQUEST['w_codigo_deposito'];
    $w_observacao         = $_REQUEST['w_observacao'];
    $w_conta              = $_REQUEST['w_conta'];
    $w_sq_tipo_lancamento = $_REQUEST['$w_sq_tipo_lancamento'];
  } 

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,$SG);
  $w_tramite            = f($RS_Solic,'sq_siw_tramite');
  $w_conta              = f($RS_Solic,'sq_pessoa_conta');
  $w_valor_real         = formatNumber(f($RS_Solic,'valor'));
  $w_sg_forma_pagamento = f($RS_Solic,'sg_forma_pagamento');
  $w_sq_tipo_lancamento = f($RS_Solic,'sq_tipo_lancamento');
  $w_inicio             = FormataDataEdicao(time());
  $w_quitacao           = FormataDataEdicao(f($RS_Solic,'quitacao'));
  $w_observacao         = f($RS_Solic,'observacao');
  
  // Se ligado a projeto, recupera rubricas
  $sql = new db_getSolicRubrica; $RS_Rub = $sql->getInstanceOf($dbms,f($RS_Solic,'sq_solic_pai'),null,'S',null,null,null,null,null,null);

  // Se reembolso, recupera a rubrica apenas do primeiro item do primeiro documento pois são todos iguais
  if (strpos('REEMB',substr($SG,3))!==false) {
    // Recupera os documentos do lançamento
    $sql = new db_getLancamentoDoc; $RS_Doc = $sql->getInstanceOf($dbms,$w_chave,null,null,null,null,null,null,'DOCS');
    
    if (count($RS_Doc)>0) {
      foreach($RS_Doc as $row) {
        $sql = new db_getLancamentoItem; $RS_Item = $sql->getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null,null);
        foreach($RS_Item as $row1) {
          $w_sq_projeto_rubrica = f($row1,'sq_projeto_rubrica');
          break;
        }
        break;
      }
    }
  }
  
  // Se pagamento de viagem, recupera os dados da solicitação
  if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
    $sql = new db_getSolicData; $RS_Viagem = $sql->getInstanceOf($dbms,f($RS_Solic,'sq_solic_pai'),'PDINICIAL');
    $w_inicio = formataDataEdicao(f($RS_Viagem,'inicio'));
  }
  
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaLancamento($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($w_erro=='' || substr(Nvl($w_erro,'-'),0,1)!='0') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('REEMB',substr($SG,3))!==false) Validate('w_sq_tipo_lancamento','Tipo de pagamento', 'SELECT', 1, 1, 18, '', '0123456789');
    if (count($RS_Rub)>0) Validate('w_sq_projeto_rubrica','Rubrica', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_quitacao','Data do pagamento', 'DATA', 1, 10, 10, '', '0123456789/');
    CompData('w_quitacao','Data do pagamento','<=',FormataDataEdicao(time()),'data atual');
    Validate('w_valor_real','Valor real','VALOR','1', 4, 18, '', '0123456789.,');
    Validate('w_observacao','Observação', '', '', '1', '500', '1', '1');
    Validate('w_assinatura','Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
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
  } elseif ($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0') {
    BodyOpen('onLoad=\'document.Form.Botao.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados do lançamento, na opção 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'CONC&O='.$O.'&w_menu='.$w_menu.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
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
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS_Solic,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  if ($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0') {
     ShowHTML('    <tr><td colspan="4" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b>Não é possível registrar o pagamento enquanto as correções listadas não forem feitas.</b></font></td>');
     ShowHTML('    <tr><td colspan="4" align="center" colspan=4><hr>');
     ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } else {
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    ShowHTML('      <tr><td colspan="4" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.f($RS,'upload_maximo').'/1024. KBytes</b>.</font></td>');
    ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    if (strpos('REEMB',substr($SG,3))!==false) {
      ShowHTML('      <tr>');
      SelecaoTipoLancamento('<u>T</u>ipo de pagamento:','T','Selecione na lista o tipo de pagamento adequado.',$w_sq_tipo_lancamento,$w_menu,$w_cliente,'w_sq_tipo_lancamento',substr($SG,0,3).'VINC',null,2);
      ShowHTML('      </tr>');
      if(count($RS_Rub)>0) {
        ShowHTML('      <tr>');
        SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,f($RS_Solic,'sq_solic_pai'),null,'w_sq_projeto_rubrica','RUBRICAS',null);
        ShowHTML('      </tr>');
      }
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_tipo_lancamento" value="'.$w_sq_tipo_lancamento.'">');
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>D</u>ata do '.((substr($SG,2,1)=='R') ? 'recebimento' : 'pagamento').':</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_quitacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_quitacao,$w_inicio).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de pagamento deste lançamento.">'.ExibeCalendario('Form','w_quitacao').'</td>');
    ShowHTML('        <td><b>Valo<u>r</u> real:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor_real" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_real.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor real do lançamento."></td>');
    ShowHTML('      <tr><td colspan="4"><b>Obs<u>e</u>rvação:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_observacao" class="sti" ROWS=5 cols=75 title="Descreva o quanto a demanda atendeu aos resultados esperados.">'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="4"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
    ShowHTML('      <tr colspan="4"><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    }
  } 
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
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
// Imprime a linha com as rubricas do documento
// -------------------------------------------------------------------------
function documentorubrica($v_RS3,$l_tipo){
  extract($GLOBALS);
//  $v_html=chr(13).'    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
  $v_html=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
  $v_html.=chr(13).'        <tr align="center">';
  $v_html.=chr(13).'          <td width="60%"><b>Rubrica</td>';
  if($l_tipo==2) $v_html.=chr(13).'          <td><b>Rubrica destino</td>';
  $v_html.=chr(13).'          <td><b>Valor</td>';
  $v_html.=chr(13).'        </tr>';
  foreach($v_RS3 as $row) {
    $v_html.=chr(13).'      <tr valign="top">';
    $v_html.=chr(13).'        <td><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/pagfundo.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha4\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'cd_rubrica_origem').' - '.f($row,'nm_rubrica_origem').'</A>&nbsp</td>';
    if($l_tipo==2) $v_html.=chr(13).'        <td>'.f($row,'cd_rubrica_destino').' - '.f($row,'nm_rubrica_destino').'</td>';
    $v_html.=chr(13).'        <td align="right">'.number_format(f($row,'valor'),2,',','.').'</td>';
    $v_html.=chr(13).'      </tr>';
    $w_total += f($row,'valor');
  } 
  if ($w_total>0) {
    $v_html.=chr(13).'      <tr valign="top">';
    if($l_tipo==2) 
      $v_html.=chr(13).'        <td colspan=2 align="right"><b>Total</b></td>';
    else
      $v_html.=chr(13).'        <td align="right"><b>Total</b></td>';
    $v_html.=chr(13).'        <td align="right"><b>'.number_format($w_total,2,',','.').'</b></td>';  
    $v_html.=chr(13).'      </tr>';
  }
  $v_html.=chr(13).'    </table>';
  return $v_html;
}
// =========================================================================
// Rotina de itens do documento
// -------------------------------------------------------------------------
function FichaRubrica() {
  extract($GLOBALS);
  $w_sq_projeto_rubrica  = $_REQUEST['w_sq_projeto_rubrica'];
  // Recupera os dados do lançamento
  $sql = new db_getSolicRubrica; $RS = $sql->getInstanceOf($dbms,null,$w_sq_projeto_rubrica,null,null,null,null,null,null,'FICHA');
  foreach($RS as $row){$RS=$row; break;}
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen(null);
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (count($RS)<=0) {
    ScriptOpen('JavaScript');
    ShowHTML('alert(\'Não existe nenhum lançamento para este rubrica!\');');
    ShowHTML('window.close();');
    ShowHTML('opener.focus();');
    ScriptClose();
  } else {
    if(nvl(f($RS,'nm_label'),'')>'')
      ShowHTML('  <tr><td colspan="2"><font size="2">'.f($RS,'nm_label').': <b><A class="hl" HREF="mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($RS,'sq_acordo').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sg').'" title="Exibe as informações.">'.f($RS,'cd_acordo').' ('.f($RS,'sq_acordo').')</a></b></font></td>');
    ShowHTML('  <tr><td colspan="2"><font size="2">Projeto: <b><A class="hl" HREF="projeto.php?par=Visual&O=L&w_chave='.f($RS,'sq_projeto').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($RS,'nm_projeto').'</a></b></font></td>');   
    ShowHTML('  <tr><td colspan="2"><font size="2">Rubrica: <b>'.f($RS,'codigo_rubrica').' - '.f($RS,'nm_rubrica').'</b></font></td>');
    ShowHTML('  <tr><td colspan="2"><font size="2">Classificação: <b>'.f($RS,'nm_cc').'</b></font></td>');
    ShowHTML('  <tr><td colspan="2">&nbsp</td></tr>');
    $sql = new db_getSolicRubrica; $RS = $sql->getInstanceOf($dbms,null,$w_sq_projeto_rubrica,null,null,null,null,null,null,'FICHA');
    $RS = SortArray($RS,'phpdt_vencimento','desc','sq_lancamento','desc');
    ShowHTML('  <tr><td><a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('      <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('  <tr><td colspan="2">');
    ShowHTML('  <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('    <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('      <td rowspan=2><b>Operação</td>');
    ShowHTML('      <td rowspan=2><b>Data</td>');
    ShowHTML('      <td colspan=2><b>Valor</td>');
    ShowHTML('      <td rowspan=2><b>Histórico</td>');
    ShowHTML('    </tr>');
    ShowHTML('    <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('      <td><b>Previsto</td>');
    ShowHTML('      <td><b>Real</td>');
    ShowHTML('    </tr>');    
    // Lista os registros selecionados para listagem
    $w_total_previsto=0;
    $w_total_real=0;
    foreach($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('    <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('      <td>'.f($row,'operacao').'</td>');
      ShowHTML('      <td align="center">'.FormataDataEdicao(f($row,'vencimento')).'</td>');
      if(f($row,'tipo_rubrica')==5)
        ShowHTML('      <td align="right">-'.number_format(f($row,'valor'),2,',','.').'</td>');
      else
        ShowHTML('      <td align="right">'.number_format(f($row,'valor'),2,',','.').'</td>');
      if(nvl(f($row,'sg_tramite'),'')=='AT') {
        if(f($row,'tipo_rubrica')==5)
          ShowHTML('      <td align="right">-'.number_format(f($row,'valor'),2,',','.').'</td>');
        else
          ShowHTML('      <td align="right">'.number_format(f($row,'valor'),2,',','.').'</td>');
      } else {
        ShowHTML('      <td align="right">'.number_format(0,2,',','.').'</td>');
      }
      ShowHTML('      <td><A class="hl" HREF="mod_fn/pagfundo.php?par=Visual&O=L&w_chave='.f($row,'sq_lancamento').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG='.f($row,'sg_lancamento_menu').MontaFiltro('GET').'" title="Exibe as informações do lançamento"> '.f($row,'cd_lancamento').' - '.f($row,'nm_lancamento').'</a>');
      ShowHTML('    </tr>');
      if(f($row,'tipo_rubrica')==5)
        $w_total_previsto -= f($row,'valor');
      else
        $w_total_previsto += f($row,'valor');
      if(nvl(f($row,'sg_tramite'),'')=='AT') {
        if(f($row,'tipo_rubrica')==5)
          $w_total_real -= f($row,'valor');
        else
          $w_total_real += f($row,'valor');
      }
    } 
    if (nvl($w_total_previsto,'')!='') {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td align="right" colspan=2><b>Saldo atual</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format($w_total_previsto,2,',','.').'</b></td>');
      ShowHTML('        <td align="right"><b>'.number_format($w_total_real,2,',','.').'</b></td>');
      ShowHTML('        <td>&nbsp;</td>');
      ShowHTML('      </tr>');
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('</table>');
    ShowHTML('</center>');
    Estrutura_Texto_Fecha();
  } 
}
// =========================================================================
// Rotina de preparação para envio de e-mail relativo a lançamentos
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
  $sql = new db_getSolicData; $RSM = $sql->getInstanceOf($dbms,$p_solic,substr($SG,0,3).'GERAL');
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    // Recupera os dados da tarefa
    $w_html='<HTML>'.$crlf;
    $w_html.=BodyOpenMail(null).$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==2) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITAÇÃO DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==3) {
     $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUSÃO DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    } 
    $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></td>'.$crlf;
    $w_nome=f($RSM,'nome').' '.f($RSM,'codigo_interno').' ('.f($RSM,'sq_siw_solicitacao').')';
    $w_html.=$crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'      <tr><td>Tipo de lançamento: <b>'.f($RSM,'nm_tipo_lancamento').' </b></td>';
    $w_html.=$crlf.'      <tr><td>Finalidade: <b>'.f($RSM,'codigo_interno').' ('.f($RSM,'sq_siw_solicitacao').')<br>'.CRLF2BR(f($RSM,'descricao')).'</b></td></tr>';
    // Identificação do contrato
    $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DO LANÇAMENTO</td>';
    if (Nvl(f($RSM,'nm_projeto'),'')>'') $w_html.=$crlf.'      <tr><td>Projeto: <br><b>'.f($RSM,'nm_projeto').'  ('.f($RSM,'sq_solic_pai').')</b></td>';
    // Se a classificação foi informada, exibe.
    if (Nvl(f($RSM,'sq_cc'),'')>'') {
      $w_html.=$crlf.'      <tr><td>Classificação:<br><b>'.f($RSM,'nm_cc').' </b></td>';
    } 
    $w_html.=$crlf.'      <tr><td><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>Forma de pagamento:<br><b>'.f($RSM,'nm_forma_pagamento').' </b></td>';
    $w_html.=$crlf.'          <td>Vencimento:<br><b>'.FormataDataEdicao(f($RSM,'vencimento')).' </b></td>';
    $w_html.=$crlf.'          <td>Valor:<br><b>'.number_format(Nvl(f($RSM,'valor'),0),2,',','.').' </b></td>';
    $w_html.=$crlf.'          </table>';
    // Outra parte
    $sql = new db_getBenef; $RSM1 = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($RSM,'pessoa'),0),null,null,null,null,Nvl(f($RSM,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null, null, null, null, null);
    if (count($RSM1) > 0) {
      foreach ($RSM1 as $row)
      $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRA PARTE</td>';
      $w_html.=$crlf.'      <tr><td><b>';
      $w_html.=$crlf.'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
      if (Nvl(f($RSM,'sq_tipo_pessoa'),0)==1) {
        $w_html.=$crlf.'          - '.f($row,'cpf'); 
      } else {
        $w_html.=$crlf.'          - '.f($row,'cnpj');
      } 
    } 
    if ($p_tipo==3) {
      // Se for conclusão
      // Dados da conclusão do lançamento, se ela estiver nessa situação
      if (Nvl(f($RSM,'conclusao'),'')>'' && Nvl(f($RSM,'quitacao'),'')>'') {
        $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DO PAGAMENTO</td>';
        $w_html.=$crlf.'      <tr><td><table border=0 width="100%" cellspacing=0>';
        $w_html.=$crlf.'          <tr valign="top">';
        $w_html.=$crlf.'          <td>Data:<br><b>'.FormataDataEdicao(f($RSM,'quitacao')).' </b></td>';
        if (Nvl(f($RSM,'codigo_deposito'),'')>'') $w_html.=$crlf.'          <td>Código do depósito:<br><b>'.f($RSM,'codigo_deposito').' </b></td>';
        $w_html.=$crlf.'          </table>';
        $w_html.=$crlf.'      <tr><td>Observação:<br><b>'.CRLF2BR(Nvl(f($RSM,'observacao'),'---')).' </b></td>';
      } 
      if (Nvl(f($RSM,'nm_cc'),'')>'') {
        // Se for vinculado a classificação, envia aos que participaram da tramitação
        $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null,'LISTA');
        $RS = SortArray($RS,'phpdt_data','desc');
        foreach($RS as $row) {
          if (f($row,'sq_pessoa_destinatario')>'') {
            // Configura os destinatários da mensagem
            $sql = new db_getPersonData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,nvl(f($RS,'sq_pessoa_destinatario'),0),null,null);
            $w_destinatarios .= f($RS1,'email').'|'.f($RS1,'nome').'; ';          } 
        } 
      } else {
        // Caso contrário envia para o responsável pelo projeto 
        $sql = new db_getUorgResp; $RS = $sql->getInstanceOf($dbms,f($RSM,'sq_unidade'));
        foreach($RS as $row){$RS=$row; break;}
        if(f($RS,'st_titular')=='S')    $w_destinatarios .= f($RS,'email_titular').'|'.f($RS,'nm_titular').'; ';
        if(f($RS,'st_substituto')=='S') $w_destinatarios .= f($RS,'email_substituto').'|'.f($RS,'nm_substituto').'; ';
      }
    }
    //Recupera o último log
    $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
    foreach ($RS as $row) { $RS = $row; if(nvl(f($row,'destinatario'),'')!='') break; }
    $w_data_encaminhamento = f($RS,'phpdt_data');
    if ($p_tipo==2) {
      $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
      $w_html.=$crlf.'      <tr><td><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf.'          <tr valign="top">';
      $w_html.=$crlf.'          <td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
      $w_html.=$crlf.'          <td>Para:<br><b>'.f($RS,'destinatario').'</b></td>';
      $w_html.=$crlf.'          <tr valign="top"><td colspan=2>Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
      $w_html.=$crlf.'          </table>';
      // Configura o destinatário da tramitação como destinatário da mensagem
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,f($RS,'sq_pessoa_destinatario'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    } 
    $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
    $sql = new db_getCustomerSite; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    $w_html.='      <tr valign="top"><td>'.$crlf;
    $w_html.='         Para acessar o sistema use o endereço: <b><a class="ss" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td>'.$crlf;
    $w_html.='         Dados da ocorrência:<br>'.$crlf;
    $w_html.='         <ul>'.$crlf;
    $w_html.='         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html.='         <li>Data<b> '.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
    $w_html.='         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html.='         </ul>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='    </table>'.$crlf;
    $w_html.='</td></tr>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</BODY>'.$crlf;
    $w_html.='</HTML>'.$crlf;
    // Prepara os dados necessários ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclusão ou Conclusão
      if ($p_tipo==1) {
        $w_assunto = 'Inclusão - '.$w_nome;
      } else {
        $w_assunto = 'Conclusão - '.$w_nome;
      }
    } elseif ($p_tipo==2) {
      // Tramitação
      $w_assunto = ' Tramitação - '.$w_nome;
    } 
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if (Nvl($w_resultado,'')>'') {
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
  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  if (strpos($SG,'FUNDO')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_menu'],
          $_REQUEST['w_sq_unidade'],$_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_sqcc'],
          $_REQUEST['w_descricao'],$_REQUEST['w_vencimento'],Nvl($_REQUEST['w_valor'],0),$_REQUEST['w_data_hora'],
          $_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],$_REQUEST['w_chave_pai'],
          $_REQUEST['w_sq_acordo_parcela'],$_REQUEST['w_observacao'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
          Nvl($_REQUEST['w_sq_forma_pagamento'],''),$_REQUEST['w_tipo_pessoa'],$_REQUEST['w_forma_atual'],
          $_REQUEST['w_vencimento_atual'],$_REQUEST['w_tipo_rubrica'],nvl($_REQUEST['w_protocolo'],$_REQUEST['w_numero_processo']),
          $_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],$_REQUEST['w_texto_pagamento'],$_REQUEST['w_solic_vinculo'],
          $_REQUEST['w_sq_projeto_rubrica'],&$w_chave_nova,&$w_codigo);

      
      if ($O!='E') {
        // Reembolso sempre é para o usuário logado
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,$_REQUEST['w_cpf'],$_REQUEST['w_cnpj'],null,null,null,null,null,null,null,null,null, null, null, null, null);
        foreach ($RS as $row) {$RS=$row; break;}
  
        if (nvl($_REQUEST['w_chave'],'')!='') {
          // Se a solicitação já existe, recupera os dados bancários
          $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
          $w_pessoa_atual     = f($RS1,'pessoa');
          $w_conta            = f($RS1,'sq_pessoa_conta');
          $w_sq_banco         = f($RS1,'sq_banco');
          $w_sq_agencia       = f($RS1,'sq_agencia');
          $w_operacao         = f($RS1,'operacao_conta');
          $w_nr_conta         = f($RS1,'numero_conta');
          $w_sq_pais_estrang  = f($RS1,'sq_pais_estrang');
          $w_aba_code         = f($RS1,'aba_code');
          $w_swift_code       = f($RS1,'swift_code');
          $w_endereco_estrang = f($RS1,'endereco_estrang');
          $w_banco_estrang    = f($RS1,'banco_estrang');
          $w_agencia_estrang  = f($RS1,'agencia_estrang');
          $w_cidade_estrang   = f($RS1,'cidade_estrang');
          $w_informacoes      = f($RS1,'informacoes');
          $w_codigo_deposito  = f($RS1,'codigo_deposito');
        } 
  
        //Grava os dados da pessoa
        $SQL = new dml_putLancamentoOutra; $SQL->getInstanceOf($dbms,$O,$SG,$w_chave_nova,$w_cliente,null,$_REQUEST['w_cpf'],$_REQUEST['w_cnpj'],
            $_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],$_REQUEST['w_sexo'],null,null,null,null,null,null,null,
            f($RS,'logradouro'),f($RS,'complemento'),f($RS,'bairro'),f($RS,'sq_cidade'),f($RS,'cep'),
            f($RS,'ddd'),f($RS,'nr_telefone'),f($RS,'nr_fax'),f($RS,'nr_celular'),f($RS,'email'), $w_sq_agencia, $w_operacao, 
            $w_nr_conta, $w_sq_pais_estrang, $w_aba_code, $w_swift_code, $w_endereco_estrang, $w_banco_estrang, $w_agencia_estrang, 
            $w_cidade_estrang, $w_informacoes, $w_codigo_deposito, $w_pessoa_atual, $w_conta);
            
        //Grava os dados do comprovante de despesa
        $SQL = new dml_putLancamentoDoc; $SQL->getInstanceOf($dbms,$O,$w_chave_nova,$_REQUEST['w_chave_doc'],$_REQUEST['w_sq_tipo_documento'],
          $_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_serie'],$_REQUEST['w_valor'],
          'N','N','N',null,null,null,null,&$w_chave_doc);

        // Grava acréscimos e supressões
        $SQL = new dml_putLancamentoValor;  $SQL->getInstanceOf($dbms,'E',$w_chave_doc,null,null);

        // Insere os registros com valor maior que zero
        for ($i=0; $i<=count($_POST['w_valores'])-1; $i=$i+1) {
          if (Nvl($_REQUEST['w_valores'][$i],'0,00')!='0,00') {
            $SQL->getInstanceOf($dbms,'I',$w_chave_doc,$_REQUEST['w_sq_valores'][$i],$_REQUEST['w_valores'][$i]);
          } 
        }

        $SQL = new dml_putFinanceiroConc; $SQL->getInstanceOf($dbms,$w_menu,$w_chave_nova,$w_usuario,$_REQUEST['w_tramite'],
          $_REQUEST['w_vencimento'],Nvl($_REQUEST['w_valor'],0),null,null,Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
          null,'Conclusão automática de pagamento por fundo fixo.',null,null,null,null);
      }
    
      if ($O=='A') {
        $w_html = VisualLancamento($_REQUEST['w_chave'],'L',$w_usuario,1,'1');
        CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
      }
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'OUTRAP')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_putLancamentoOutra; $SQL->getInstanceOf($dbms,$O,$SG,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_sq_pessoa'],
          $_REQUEST['w_cpf'],$_REQUEST['w_cnpj'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],$_REQUEST['w_sexo'],
          $_REQUEST['w_nascimento'],$_REQUEST['w_rg_numero'],$_REQUEST['w_rg_emissao'],$_REQUEST['w_rg_emissor'],
          $_REQUEST['w_passaporte_numero'],$_REQUEST['w_sq_pais_passaporte'],$_REQUEST['w_inscricao_estadual'],
          $_REQUEST['w_logradouro'],$_REQUEST['w_complemento'],$_REQUEST['w_bairro'],$_REQUEST['w_sq_cidade'],$_REQUEST['w_cep'],
          $_REQUEST['w_ddd'],$_REQUEST['w_nr_telefone'],$_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],$_REQUEST['w_email'],
          $_REQUEST['w_sq_agencia'],$_REQUEST['w_operacao'],$_REQUEST['w_nr_conta'],$_REQUEST['w_sq_pais_estrang'],
          $_REQUEST['w_aba_code'],$_REQUEST['w_swift_code'],$_REQUEST['w_endereco_estrang'],$_REQUEST['w_banco_estrang'],
          $_REQUEST['w_agencia_estrang'],$_REQUEST['w_cidade_estrang'],$_REQUEST['w_informacoes'],$_REQUEST['w_codigo_deposito'],
          $_REQUEST['w_tipo_pessoa_atual'],$_REQUEST['w_conta']);
      ScriptOpen('JavaScript');
      ShowHTML('  window.close();');
      ShowHTML('  opener.location.reload();');
      ShowHTML('  opener.focus();');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
    // Inclusão, Alteração e exclusão de documentos relativos a um lançamento.
  } elseif ($SG=='DOCUMENTO') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_putLancamentoDoc; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_sq_tipo_documento'],
        $_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_serie'],$_REQUEST['w_valor'],$_REQUEST['w_patrimonio'],$_REQUEST['w_retencao'],
        $_REQUEST['w_tributo'],null,null,null,null,&$w_chave_nova);
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG=='ITEM') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_putLancamentoItem; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_sq_lancamento_doc'],$_REQUEST['w_sq_documento_item'],
        $_REQUEST['w_sq_projeto_rubrica'],$_REQUEST['w_descricao'],$_REQUEST['w_quantidade'],$_REQUEST['w_valor_unitario'],
        $_REQUEST['w_ordem'],$_REQUEST['w_data_cotacao'],$_REQUEST['w_valor_cotacao'],null);
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&w_sq_lancamento_doc='.$_REQUEST['w_sq_lancamento_doc'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }     
    // Envio de lançamentos.
  } elseif (!(strpos($SG,'ENVIO')===false)) {
    // Verifica se a Assinatura Eletrônica é válida 
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Trata o recebimento de upload ou dados 
      if ((false!==(strpos(upper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(upper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
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
          $SQL = new dml_putLancamentoEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
          //Rotina para gravação da imagem da versão da solicitacão no log.
          if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS,'sigla');
            if($w_sg_tramite=='CI') {
              $w_html = VisualLancamento($_REQUEST['w_chave'],'L',$w_usuario,$P1,'1');
              CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
            }
          }
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        if ($P1==0) {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        }
        ScriptClose();
      } else {
        $SQL = new dml_putLancamentoEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
          $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
          null,null,null,null);
        //Rotina para gravação da imagem da versão da solicitacão no log.
        if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
          $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
          $w_sg_tramite = f($RS,'sigla');
          if($w_sg_tramite=='CI') {
            $w_html = VisualLancamento($_REQUEST['w_chave'],'L',$w_usuario,$P1,'1');
            CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
          }
        }  
        // Envia e-mail comunicando de tramitação
        SolicMail($_REQUEST['w_chave'],2);
        ScriptOpen('JavaScript');
        if ($P1==0) {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        }
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'ENVAUT')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Trata o recebimento de upload ou dados 
      if ($_REQUEST['w_envio']=='N') {
        $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
          $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
      } else {
        $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
          $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
      } 
      //Rotina para gravação da imagem da versão da solicitacão no log.
      if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
        $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
        $w_sg_tramite = f($RS,'sigla');
        if($w_sg_tramite=='CI') {
          ShowHTML(VisualLancamento($_REQUEST['w_chave'],'V',$w_usuario,$P1,$P4));
          CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
        }
      }  
      // Envia e-mail comunicando o envio
      SolicMail($_REQUEST['w_chave'],2);
      // Se for envio da fase de cadastramento, remonta o menu principal
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'CONC')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou este contrato para outra fase!\');');
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
          $SQL = new dml_putFinanceiroConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_quitacao'],
            $_REQUEST['w_valor_real'],$_REQUEST['w_codigo_deposito'],$_REQUEST['w_conta'],$_REQUEST['w_sq_tipo_lancamento'],
            $_REQUEST['w_sq_projeto_rubrica'],
            $_REQUEST['w_observacao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
          // Envia e-mail comunicando a conclusão
          SolicMail($_REQUEST['w_chave'],3);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
      } 
      // Volta para a listagem
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
  }
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':         Inicial();          break;
    case 'GERAL':           Geral();            break;
    case 'OUTRAPARTE':      OutraParte();       break;
    case 'DOCUMENTO':       Documentos();       break;
    case 'RUBRICADOC':      RubricaDoc();       break;    
    case 'ITENS':           Itens();            break;
    case 'NOTAS':           Notas();            break;
    case 'BUSCAPARCELA':    BuscaParcela();     break;
    case 'FICHARUBRICA':    FichaRubrica();     break;
    case 'VISUAL':          Visual();           break;
    case 'EXCLUIR':         Excluir();          break;
    case 'ENVIO':           Encaminhamento();   break;
    case 'ANOTACAO':        Anotar();           break;
    case 'CONCLUIR':        Concluir();         break;
    case 'GRAVA':           Grava();            break;
    default:
      cabecalho();
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