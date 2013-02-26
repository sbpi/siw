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
include_once($w_dir_volta.'classes/sp/db_getLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoValor.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCotacao.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoIncid.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getKindPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoNota.php');
include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoOutra.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroConc.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicCotacao.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoValor.php');
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
include_once($w_dir_volta.'funcoes/selecaoMoeda.php');
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
include_once('visualreembolso.php');
include_once('validareembolso.php');
// =========================================================================
//  /reembolso.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia as rotinas relativas ao controle de reembolso
// Mail     : alex@sbpi.com.br
// Criacao  : 01/09/2011 13:30
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
$P1         = nvl($_REQUEST['P1'],9);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper(nvl($_REQUEST['SG'],$_REQUEST['f_SG']));
$R          = $_REQUEST['R'];
$O          = upper(nvl($_REQUEST['O'],$_REQUEST['f_O']));
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'reembolso.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];

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

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos($SG,'ANEXO')!==false || strpos($SG,'PARC')!==false || strpos($SG,'REPR')!==false) {
  if (strpos('IG',$O)===false && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

// Verifica se o cliente tem o módulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_mod_pa='S'; else $w_mod_pa='N';

// Verifica se deve ser exigida conta débito
$sql = new db_getContaBancoList; $RS_Conta = $sql->getInstanceOf($dbms,$w_cliente,null,'FINANCEIRO');
if (count($RS_Conta)>1) { 
  $w_exige_conta = true; 
} else {
  $w_exige_conta = false;
  if (count($RS_Conta)==1) {
    foreach($RS_Conta as $row) $RS_Conta = $row;
    $w_conta_debito = f($RS_Conta,'sq_pessoa_conta');
  }
}

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
    if (strpos(upper($R),'GR_')!==false || strpos(upper($R),'PROJETO')!==false || $w_tipo=='WORD') {
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
    if (substr($SG,3)=='CONT') {
      if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Vigência <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    } elseif (substr($SG,3)=='VIA') {
      if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Viagem <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    }
    if ($p_fim_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Pagamento <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
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
  $w_vinc_proj = false;
  if (substr($SG, 3) == 'CONT') {
    $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'GCDCAD');
    $sql = new db_getMenuRelac;
    $RS_Vinc = $sql->getInstanceOf($dbms, f($RS1, 'sq_menu'), null, null, null, null);
    foreach ($RS_Vinc as $row) {
      if(f($row,'sg_modulo') == 'PR'){
        $w_vinc_proj = true;
      }
    }
  }
  
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']); 
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
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
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
  }
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
    if (strpos(upper($R),'GR_')===false) {
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
        if (substr($SG,3)=='CONT') ShowHTML('    <td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Buscaparcela&R='.$w_pagina.$par.'&O=P&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        else                       ShowHTML('    <td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2=1&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      }
    } 
    if (strpos(upper($R),'GR_')===false && strpos(upper($R),'LANCAMENTO')===false && Nvl($R,'')!='') {
      if ($w_copia>'') {
        // Se for cópia
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } else {
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan++; ShowHTML('          <td><b>'.(($w_tipo=='WORD') ? 'Código' : LinkOrdena('Código','codigo_interno')).'</td>');
    if ($P1 > 2 && $P1 != 6 && ($w_segmento=='Público' || $w_mod_pa=='S')) {
      $colspan++; ShowHTML('          <td><b>'.(($w_tipo=='WORD') ? 'Protocolo' : LinkOrdena('Protocolo','protocolo')).'</font></td>');
    }
    $colspan++; ShowHTML('          <td><b>'.(($w_tipo=='WORD') ? 'Beneficiário' : LinkOrdena('Beneficiário','nm_pessoa_resumido')).'</td>');
    $colspan++; ShowHTML('          <td><b>'.(($w_tipo=='WORD') ? 'Tipo do Lançamento' : LinkOrdena('Tipo do Lançamento','nm_tipo_lancamento')).'</td>');
    $colspan++; ShowHTML('          <td><b>'.(($w_tipo=='WORD') ? 'Discriminação das despesas' : LinkOrdena('Discriminação das Despesas','nr_doc')).'</td>');
    $colspan++; ShowHTML ('          <td><b>'.(($w_tipo=='WORD') ? 'Vinculação' : LinkOrdena('Vinculação','dados_pai')).'</td>');
    $colspan++; ShowHTML('          <td><b>'.(($w_tipo=='WORD') ? 'Referência' : LinkOrdena('Referência','referencia_inicio')).'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Valor','valor_doc').'</td>');
    if ($_SESSION['INTERNO']=='S') ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.($colspan+2).' align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial = 0;
      if($w_tipo!='WORD') $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      else                $RS1 = $RS;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'vencimento'),f($row,'inicio'),f($row,'quitacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        ShowHTML('        '.(($w_tipo=='WORD') ? f($row,'codigo_interno') : '<A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'obj_acordo').' ::> '.f($row,'descricao').'">'.f($row,'codigo_interno').'&nbsp;</a>'));
        if ($P1 > 2 && $P1 != 6 && ($w_segmento=='Público' || $w_mod_pa=='S')) {
          if ($w_embed!='WORD' && nvl(f($row,'protocolo_siw'),'')!='') {
            ShowHTML('        <td align="right"><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($row,'protocolo').'&nbsp;</a>');
          } else {
            ShowHTML('        <td align="right">'.f($row,'protocolo'));
          }
        }
        if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
          if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,f($row,'nm_pessoa_resumido')).'</td>');
          else                 ShowHTML('        <td>'.f($row,'nm_pessoa_resumido').'</td>');
        } else {
          ShowHTML('        <td align="center">---</td>');
        }
        ShowHTML('        <td>'.f($row,'nm_tipo_lancamento'));
        ShowHTML('        <td>'.crlf2br(f($row,'descricao')));
        if (Nvl(f($row,'dados_pai'),'')!='') {
          ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_tipo).'</td>');
        } else {
          ShowHTML('        <td>---</td>');
        }
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'referencia_inicio'),9).'</td>');
        ShowHTML('        <td align="right">'.((nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').formatNumber(f($row,'valor')).'&nbsp;</td>');
        $w_parcial += Nvl(f($row,'valor'),0);
        if ($w_tipo!='WORD') {
          if ($_SESSION['INTERNO']=='S') {
            ShowHTML('        <td align="top" nowrap class="remover">');
            if ($P1!=3) {
              // Se não for acompanhamento
              if ($P1==1) {
                  // Se for cadastramento
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2=1&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais do lançamento">AL</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
              } elseif ($P1==2 || $P1==6) {
                 // Se for execução
                if ($P1==2) {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para o lançamento, sem enviá-la.">AN</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
                  if (f($row,'sg_tramite')=='EE')
                    ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registro do pagamento.">'.((substr($SG,2,1)=='R') ? 'Receber' : 'Pagar').'</A>&nbsp');
                } else {
                  if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                    ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
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
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
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
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Total desta página&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.formatNumber($w_parcial,2).'&nbsp;</td>');
          if ($w_tipo!='WORD') ShowHTML('          <td>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') $w_total += f($row,'valor_atual');
            else                            $w_total += f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Total da listagem&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.formatNumber($w_total,2).'&nbsp;</td>');
          if ($w_tipo!='WORD') ShowHTML('          <td>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
      } 
    } 
    ShowHTML('    </table></div>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($w_tipo!='WORD') {
      ShowHTML('<tr><td align="center" colspan=3>');
      if ($R>'' || $P4 > $conPageSize) {
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
      if (strpos($SG,'CONT')!==false) {
        ShowHTML('          <td><b><u>V</u>igência entre:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      } elseif (strpos($SG,'VIA')!==false) {
        ShowHTML('          <td><b><u>V</u>iagem entre:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      }
      ShowHTML('          <td><b><u>P</u>agamento entre:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
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
    ShowHTML(' alert("Opção não disponível");');
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
  // Carrega o segmento do cliente
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente); 
  
  $w_segmento = f($RS,'segmento');
  
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_menu_relac        = $_REQUEST['w_sq_menu_relac'];    
    if($w_sq_menu_relac=='CLASSIF') {
      $w_chave_pai          = '';
    } else {
      $w_chave_pai          = $_REQUEST['w_chave_pai'];
    }

    $w_pessoa               = $_REQUEST['w_pessoa'];
    $w_pessoa_atual         = $_REQUEST['w_pessoa_atual'];
    $w_tipo_pessoa          = $_REQUEST['w_tipo_pessoa'];
    $w_nm_tipo_pessoa       = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_acordo_parcela    = $_REQUEST['w_sq_acordo_parcela'];
    $w_sq_forma_pagamento   = $_REQUEST['w_sq_forma_pagamento'];
    $w_forma_atual          = $_REQUEST['w_forma_atual'];
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
    $w_vencimento_atual     = $_REQUEST['w_vencimento_atual'];
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
    $w_solic_vinculo        = $_REQUEST['w_solic_vinculo']; 
    $w_sq_projeto_rubrica   = $_REQUEST['w_sq_projeto_rubrica'];
    $w_projeto              = $_REQUEST['w_projeto'];
    $w_chave_doc            = $_REQUEST['w_chave_doc'];
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
    $w_moeda                = $_REQUEST['w_moeda'];

  } elseif(strpos('AEV',$O)!==false || $w_copia>'') {
    // Recupera os dados do lançamento

    if ($w_copia>'') { $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_copia,$SG); }
    else             { $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG); }
    if (count($RS)>0) {

      $RS_Lancamento          = $RS;
      $w_sq_unidade           = f($RS,'sq_unidade');
      $w_observacao           = f($RS,'observacao');
      $w_aviso                = f($RS,'aviso_prox_conc');
      $w_dias                 = f($RS,'dias_aviso');
      $w_sq_acordo_parcela    = f($RS,'sq_acordo_parcela');
      $w_sq_tipo_lancamento   = f($RS,'sq_tipo_lancamento');
      $w_chave_doc            =  f($RS,'sq_lancamento_doc');
      $w_pessoa               = f($RS,'pessoa');
      $w_pessoa_atual         = f($RS,'pessoa');
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
      $w_valor                = formatNumber(f($RS,'valor'));
      $w_tipo_rubrica         = f($RS,'tipo_rubrica');
      $w_numero_processo      = f($RS,'processo');
      $w_protocolo            = f($RS,'processo');
      $w_protocolo_nm         = f($RS,'processo');
      $w_nm_tipo_rubrica      = f($RS,'nm_tipo_rubrica');
      $w_qtd_nota             = f($RS,'qtd_nota');
      $w_per_ini              = FormataDataEdicao(f($RS,'referencia_inicio'),9);
      $w_per_fim              = FormataDataEdicao(f($RS,'referencia_fim'));
      $w_texto_pagamento      = f($RS,'condicoes_pagamento');
      $w_dados_pai            = explode('|@|',f($RS,'dados_pai'));
      $w_sq_menu_relac        = $w_dados_pai[3];
      $w_dados_avo            = explode('|@|',f($RS,'dados_avo'));
      $w_projeto              = $w_dados_avo[11];
      if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
      $w_solic_vinculo        = f($RS,'sq_solic_vinculo');
      $w_sq_projeto_rubrica   = f($RS,'sq_projeto_rubrica');
      $w_sq_banco             = f($RS,'sq_banco');
      $w_sq_agencia           = f($RS,'sq_agencia');
      $w_operacao             = f($RS,'operacao_conta');
      $w_nr_conta             = f($RS,'numero_conta');
      $w_sq_pais_estrang      = f($RS,'sq_pais_estrang');
      $w_aba_code             = f($RS,'aba_code');
      $w_swift_code           = f($RS,'swift_code');
      $w_endereco_estrang     = f($RS,'endereco_estrang');
      $w_banco_estrang        = f($RS,'banco_estrang');
      $w_agencia_estrang      = f($RS,'agencia_estrang');
      $w_cidade_estrang       = f($RS,'cidade_estrang');
      $w_informacoes          = f($RS,'informacoes');
      $w_codigo_deposito      = f($RS,'codigo_deposito');
      $w_moeda                = f($RS,'sq_moeda');
    } 
  }
  
  if ($O!='I') {
    // Validação
    $w_erro = ValidaReembolso($w_cliente,$w_chave,$SG,null,null,null,null);
  }
  
  if(nvl($w_sq_menu_relac,0)>0) { $sql = new db_getMenuData; $RS_Relac  = $sql->getInstanceOf($dbms,$w_sq_menu_relac); }
  
  // Verifica as formas de pagamento possíveis. Se apenas uma, atribui direto
  $sql = new db_getFormaPagamento; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, $SG, null,'S',null);
  $w_exibe_fp = true;
  if (count($RS)==1 || nvl($w_sq_forma_pagamento,'')!='') {
    foreach($RS as $row) { 
      if (nvl($w_sq_forma_pagamento,f($row,'sq_forma_pagamento'))==f($row,'sq_forma_pagamento')) {
        $w_sq_forma_pagamento = f($row,'sq_forma_pagamento'); 
        $w_forma_pagamento    = f($row,'sigla'); 
        $w_nm_forma_pagamento = f($row,'nome'); 
        break; 
      }
    }
    if (count($RS)==1) $w_exibe_fp = false;
  }

  // Usuário logado é o beneficiário padrão do lançamento
  $w_pessoa = nvl($w_pessoa,$_SESSION['SQ_PESSOA']);
  
  // Recupera os dados do beneficiário
  if (Nvl($w_pessoa,'')!='') {
    $sql = new db_getBenef; $RS_Benef = $sql->getInstanceOf($dbms,$w_cliente,$w_pessoa,null,null,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
    if (count($RS_Benef)>0) {
      foreach($RS_Benef as $row) { $RS_Benef = $row; break; }
      $w_cpf           = f($RS_Benef,'cpf');
      $w_cnpj          = f($RS_Benef,'cnpj');
      $w_sq_prop       = f($RS_Benef,'sq_pessoa');
      $w_nome          = f($RS_Benef,'nm_pessoa');
      $w_nome_resumido = f($RS_Benef,'nome_resumido');
      $w_sexo          = f($RS_Benef,'sexo');
      $w_vinculo       = f($RS_Benef,'sq_tipo_vinculo');
      $w_tipo_pessoa   = f($RS_Benef,'sq_tipo_pessoa');
      $w_sq_unidade    = f($RS_Benef,'sq_unidade_benef');
      if (nvl($w_forma_pagamento,'')!='') {
        if (strpos('CREDITO,DEPOSITO,ORDEM',$w_forma_pagamento)!==false) {
          if (Nvl($w_nr_conta,'')=='' || nvl($w_troca,'-')!='w_sq_tipo_lancamento') {
            $w_sq_banco     = nvl(f($RS_Benef,'sq_banco'),$w_sq_banco);
            $w_sq_agencia   = nvl(f($RS_Benef,'sq_agencia'),$w_sq_agencia);
            $w_operacao     = nvl(f($RS_Benef,'operacao'),$w_operacao);
            $w_nr_conta     = f($RS_Benef,'nr_conta');
          } 
        } 
      } 
    }
  }
  
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataMA();
  FormataValor();
  openBox('reload');
  ShowHTML('  function texto() {');
  ShowHTML('    obj = document.Form;');
  ShowHTML('    var tipo_lancamento = obj.w_sq_tipo_lancamento[obj.w_sq_tipo_lancamento.selectedIndex].text;');
  ShowHTML('    tipo_lancamento = tipo_lancamento.substr(tipo_lancamento.indexOf(". ")+2);');
  ShowHTML('    var valor = obj.w_valor.value;');
  ShowHTML('    var beneficiario = obj.w_pessoa[obj.w_pessoa.selectedIndex].text;');
  ShowHTML('    var mes = obj.w_per_ini.value;');
  ShowHTML('    obj.w_descricao.value = "Pagamento de reembolso de despesas referente a " + tipo_lancamento + " no valor de " + valor + " para " + beneficiario + " referente ao mês " + mes + ".";');
  ShowHTML('  }');
  ValidateOpen('Validacao');
  Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
  if(nvl($w_sq_menu_relac,'')!='') {
    if ($w_sq_menu_relac=='CLASSIF') {
      Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
    } else {
      Validate('w_chave_pai','Vinculação','SELECT',1,1,18,1,1);
    }
  }
  if (f($RS_Menu,'solicita_cc')=='N') {
    Validate('w_chave_pai','Projeto','SELECT','1',1,18,'','0123456789');
  } else {
    Validate('w_chave_pai','Projeto','SELECT','',1,18,'','0123456789');
    Validate('w_sqcc','Classificação','SELECT','',1,18,'','0123456789');
    ShowHTML('  if (theForm.w_chave_pai.selectedIndex > 0 && theForm.w_sqcc.selectedIndex > 0) {');
    ShowHTML('     alert(\'Informe um projeto ou uma classificação. Você não pode escolher ambos!\');');
    ShowHTML('     theForm.w_chave_pai.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  if (theForm.w_chave_pai.selectedIndex == 0 && theForm.w_sqcc.selectedIndex == 0) {');
    ShowHTML('     alert(\'Informe um projeto ou uma classificação!\');');
    ShowHTML('     theForm.w_chave_pai.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
  } 
  Validate('w_pessoa','Beneficiário','SELECT',1,1,18,'','0123456789');       
  Validate('w_sq_tipo_lancamento','Tipo do lançamento','SELECT',1,1,18,'','0123456789');
  Validate('w_descricao','Discriminação das despesas','1',1,5,2000,'1','1');
  Validate('w_per_ini','Mês de Referência','DATAMA',1,7,7,'','0123456789/');
  if ($w_exibe_fp) Validate('w_sq_forma_pagamento','Forma de pagamento','SELECT',1,1,18,'','0123456789');
  if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') Validate('w_moeda','Moeda','SELECT',1,1,18,'','0123456789');
  Validate('w_valor','Valor do reembolso','VALOR','1',4,18,'','0123456789.,-');
  if (nvl($w_forma_pagamento,'')!='') {
    if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
      Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
      Validate('w_sq_agencia','Agência','SELECT',1,1,10,'1','1');
      if ($w_exige_operacao=='S') Validate('w_operacao','Operação','1','1',1,6,'','0123456789');
      Validate('w_nr_conta','Número da conta','1','1',2,30,'ZXAzxa','0123456789-');
    } elseif ($w_forma_pagamento=='ORDEM') {
      Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
      Validate('w_sq_agencia','Agência','SELECT',1,1,10,'1','1');
    } elseif ($w_forma_pagamento=='EXTERIOR') {
      Validate('w_banco_estrang','Banco de destino','1','1',1,60,1,1);
      Validate('w_aba_code','Código ABA','1','',1,12,1,1);
      Validate('w_swift_code','Código SWIFT','1','1',1,30,1,1);
      Validate('w_endereco_estrang','Endereço da agência destino','1','',3,100,1,1);
      ShowHTML('  if (theForm.w_aba_code.value == \'\' && theForm.w_swift_code.value == \'\' && theForm.w_endereco_estrang.value == \'\') {');
      ShowHTML('     alert(\'Informe código ABA, código SWIFT ou endereço da agência!\');');
      ShowHTML('     document.Form.w_aba_code.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_agencia_estrang','Nome da agência destino','1','1',1,60,1,1);
      Validate('w_nr_conta','Número da conta','1',1,1,30,1,1);
      Validate('w_cidade_estrang','Cidade da agência','1','1',1,60,1,1);
      Validate('w_sq_pais_estrang','País da agência','SELECT','1',1,18,1,1);
      Validate('w_informacoes','Informações adicionais','1','',5,200,1,1);
    }
  }
  ShowHTML('  disAll();');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'')                 BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();  texto();\'');
  elseif (strpos('EV',$O)!==false) BodyOpen('onLoad=\'this.focus()\';');
  else                             BodyOpen('onLoad=\'document.Form.w_valor.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (nvl($w_qtd_nota,0)>0) {
    ShowHTML('      <tr><td bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('        ATENÇÃO:<ul>');
    ShowHTML('        <li>O valor do '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').' será recalculado em função das notas a ele vinculadas. Use a operação "NE" da listagem para alterar os valores das notas.');
    ShowHTML('        </ul></b></font></td>');
    ShowHTML('      </tr>');
    ShowHTML('<tr><td>&nbsp;');
  }
  if ($w_chave>'') ShowHTML('      <tr><td><font size="2"><b>'.$w_codigo_interno.' ('.$w_chave.')</b></td>');
  if (strpos('IAEV',$O)!==false) {
    if (Nvl($w_pais,'')=='') {
      // Carrega os valores padrão para país, estado e cidade
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = Nvl(f($RS_Menu,'sq_cidade'),f($RS,'sq_cidade_padrao'));
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_solicitante" value="'.$_SESSION['SQ_PESSOA'].'">');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_forma_atual" value="'.$w_forma_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_vencimento" value="'.formataDataEdicao(addDays(time(),30)).'">');
    if ($O!='I') ShowHTML('<INPUT type="hidden" name="w_vencimento_atual" value="'.formataDataEdicao(addDays(time(),30)).'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    ShowHTML('<INPUT type="hidden" name="w_dias" value="3">');
    ShowHTML('<INPUT type="hidden" name="w_codigo_interno" value="'.$w_codigo_interno.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_doc" value="'.$w_chave_doc.'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
    ShowHTML('<INPUT type="hidden" name="w_projeto" value="'.$w_projeto.'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="'.$w_pessoa_atual.'">');
    $sql = new db_getKindPersonList; $RS_TipoPessoa = $sql->getInstanceOf($dbms, null);
    foreach($RS_TipoPessoa as $row) {
      if (substr(f($row,'nome'),0,1)=='F') {
        ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="'.f($row,'sq_tipo_pessoa').'">');
      }
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan="3" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="3" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="3">Os dados deste bloco serão utilizados para identificação do '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').', bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');

    ShowHTML('          <tr valign="top">');
    selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($w_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($w_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null,null,'<BR />',3);
      } else {
        SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_chave_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_chave_pai',f($RS_Relac,'sigla'),null,$w_chave_pai,'<BR />',3);
      }
    }
    if (nvl(f($RS_Relac,'sigla'),'')!='') { $sql = new db_getSolicData; $RS_Pai = $sql->getInstanceOf($dbms,$w_chave_pai,f($RS_Relac,'sigla')); }
    
    ShowHTML('      <tr>');
    SelecaoPessoa('<u>B</u>eneficiário:','B','Selecione o beneficiário deste lançamento.',$w_pessoa,null,'w_pessoa','CONTRATADOS','onChange="texto(); document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_pessoa\'; document.Form.submit();"');
    SelecaoTipoLancamento('<u>T</u>ipo de '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').':','T','Selecione na lista o tipo de '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').' adequado.',$w_sq_tipo_lancamento,$w_menu,$w_cliente,'w_sq_tipo_lancamento',substr($SG,0,3).'VINC', 'onChange="texto();"',1);
    if ($w_exibe_fp) {
      SelecaoFormaPagamento('<u>F</u>orma de pagamento:','F','Selecione na lista a forma desejada para este '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').'.',$w_sq_forma_pagamento,$SG,'w_sq_forma_pagamento',null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_forma_pagamento" value="'.$w_sq_forma_pagamento.'">');
    }
    ShowHTML('       <tr valign="top">');
    if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') {
      selecaoMoeda('<u>M</u>oeda:','U','Selecione a moeda na relação.',$w_moeda,null,'w_moeda','ATIVO',null);
    }
    ShowHTML('        <td><b><u>V</u>alor do reembolso:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento." onBlur="texto();"></td>');
    ShowHTML('        <td><b><u>M</u>ês de referência:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_per_ini" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.Nvl($w_per_ini,FormataDataEdicao(time(),9)).'" onKeyDown="FormataDataMA(this,event);" onBlur="texto();"></td>');
    ShowHTML('      <tr><td colspan=2><b><u>D</u>iscriminação das despesas:</b><br><textarea READONLY accesskey="D" name="w_descricao" class="sti" ROWS=3 cols=75 title="Discrimine as despesas para as quais deseja ser reembolsado.">'.$w_descricao.'</TEXTAREA></td>');
    
    if (nvl($w_forma_pagamento,'')!='' && strpos('CREDITO,DEPOSITO,ORDEM,EXTERIOR',$w_forma_pagamento)!==false) {
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>DADOS PARA '.upper($w_nm_forma_pagamento).'</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
        ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('      <tr valign="top">');
        SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde deverão ser feitos os pagamentos referentes ao lançamento.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
        SelecaoAgencia('A<u>g</u>ência:','A','Selecione a agência onde deverão ser feitos os pagamentos referentes ao lançamento.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
        ShowHTML('      <tr valign="top">');
        if ($w_exige_operacao=='S') ShowHTML('          <td title="Alguns bancos trabalham com o campo "Operação", além do número da conta. A Caixa Econômica Federal é um exemplo. Se for o caso,informe a operação neste campo; caso contrário, deixe-o em branco."><b>O<u>p</u>eração:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_operacao" class="sti" SIZE="6" MAXLENGTH="6" VALUE="'.$w_operacao.'"></td>');
        ShowHTML('          <td title="Informe o número da conta bancária, colocando o dígito verificador, se existir, separado por um hífen. Exemplo: 11214-3. Se o banco não trabalhar com dígito verificador, informe apenas números. Exemplo: 10845550."><b>Número da con<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nr_conta.'"></td>');
        ShowHTML('          </table>');
      } elseif ($w_forma_pagamento=='ORDEM') {
        ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('      <tr valign="top">');
        SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde deverão ser feitos os pagamentos referentes ao lançamento.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
        SelecaoAgencia('A<u>g</u>ência:','A','Selecione a agência onde deverão ser feitos os pagamentos referentes ao lançamento.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
      } elseif ($w_forma_pagamento=='EXTERIOR') {
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
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Voltar">');
    } else {
      $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    }
    if (($O!='I') && (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S'))) {
      ShowHTML('      <input class="stb" type="button" name="Botao" value="Enviar" onClick="location.href=\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_tipo=Volta&P1='.$P1.'&P2=1&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\'">');
    }
    ShowHTML('          </td>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
    
    if ($O!='I') {
      if ($w_erro>'') {
        ShowHTML('<tr><td colspan=2><font size=2><HR>');
        if (substr($w_erro,0,1)=='0') {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificadas as pendências listadas abaixo, não sendo possível seu encaminhamento para fases posteriores à atual, nem seu pagamento.');
        }elseif (substr($w_erro,0,1)=='1') {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificadas as pendências listadas abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou deste módulo.');
        } else {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.');
        } 
        ShowHTML('  <ul>'.substr($w_erro,1,1000));
        ShowHTML('<tr><td colspan=2><HR>');
      }
      ShowHTML('       </table>');
    }
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
    ShowHTML('  if (theForm.w_troca.value.indexOf(\'Alterar\') >= 0) { return true; }');
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
      if (f($RS_Menu,'sigla')=='FNDVIA') {
        Validate('w_logradouro','Logradouro','1','',4,60,'1','1');
        ShowHTML('  if (theForm.w_logradouro.value!="" && (theForm.w_sq_pais.selectedIndex==0 || theForm.w_co_uf.selectedIndex==0 || theForm.w_sq_cidade.selectedIndex==0)) {');
        ShowHTML('     alert("Se logradouro for indicado, informe pais, estado e cidade!");');
        ShowHTML('     document.Form.w_logradouro.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
      } else {
        Validate('w_logradouro','Logradouro','1',1,4,60,'1','1');
      }
      Validate('w_complemento','Complemento','1','',2,20,'1','1');
      Validate('w_bairro','Bairro','1','',2,30,'1','1');
      Validate('w_sq_pais','País','SELECT',1,1,10,'1','1');
      Validate('w_co_uf','UF','SELECT',1,1,10,'1','1');
      Validate('w_sq_cidade','Cidade','SELECT',1,1,10,'','1');
      if (Nvl($w_pd_pais,'S')=='S') Validate('w_cep','CEP','1','',9,9,'','0123456789-');
      else                          Validate('w_cep','CEP','1',1,5,9,'','0123456789');
    }
    Validate('w_email','E-Mail','1','',4,60,'1','1');
    ShowHTML('  if (theForm.w_email.value!="" && (theForm.w_sq_pais.selectedIndex==0 || theForm.w_co_uf.selectedIndex==0 || theForm.w_sq_cidade.selectedIndex==0)) {');
    ShowHTML('     alert("Se e-mail for indicado, informe pais, estado e cidade!");');
    ShowHTML('     document.Form.w_email.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
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
        Validate('w_swift_code','Código SWIFT','1','1',1,30,'',1);
        Validate('w_endereco_estrang','Endereço da agência destino','1','',3,100,1,1);
        ShowHTML('  if (theForm.w_aba_code.value == \'\' && theForm.w_swift_code.value == \'\' && theForm.w_endereco_estrang.value == \'\') {');
        ShowHTML('     alert(\'Informe código ABA, código SWIFT ou endereço da agência!\');');
        ShowHTML('     document.Form.w_aba_code.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        Validate('w_agencia_estrang','Nome da agência destino','1','1',1,60,1,1);
        Validate('w_nr_conta','Número da conta','1',1,1,30,1,1);
        Validate('w_cidade_estrang','Cidade da agência','1','1',1,60,1,1);
        Validate('w_sq_pais_estrang','País da agência','SELECT','1',1,18,1,1);
        Validate('w_informacoes','Informações adicionais','1','',5,200,1,1);
      }
    } 
    ShowHTML('  disAll();');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
  ShowHTML('          <td>Valor:<br><b>'.formatNumber(Nvl(f($RS1,'valor'),0)).' </b></td>');
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
      if ($P2==1) {
        ShowHTML('            <INPUT class="stb" type="button" onClick="parent.$.fancybox.close();" name="Botao" value="Cancelar">');
      } else {
        ShowHTML('            <INPUT class="stb" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Cancelar">');
      }
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
      if ($P2==1) {
        ShowHTML('            <INPUT type="button" class="stb" onClick="javascript:parent.$.fancybox.close();" name="Botao" value="Cancelar">');
      } else {
        ShowHTML('            <input class="stb" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Cancelar">');
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
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
} 
// =========================================================================
// Rotina de documentos
// -------------------------------------------------------------------------
function Documentos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_sq_lancamento_doc  = $_REQUEST['w_sq_lancamento_doc'];
  // Recupera os dados do lançamento
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_sq_tipo_documento    = $_REQUEST['w_sq_tipo_documento'];
    $w_numero               = $_REQUEST['w_numero'];
    $w_data                 = $_REQUEST['w_data'];
    $w_serie                = $_REQUEST['w_serie'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_patrimonio           = $_REQUEST['w_patrimonio'];
    $w_tipo                 = $_REQUEST['w_tipo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,$w_chave,null,null,null,null,null,null,'DOCS');
    $RS = SortArray($RS,'data','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endereço informado
    $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_sq_lancamento_doc,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_tipo_documento    = f($RS,'sq_tipo_documento');
    $w_numero               = f($RS,'numero');
    $w_data                 = FormataDataEdicao(f($RS,'data'));
    $w_serie                = f($RS,'serie');
    $w_valor                = formatNumber(f($RS,'valor'));
    $w_patrimonio           = f($RS,'patrimonio');
    $w_tributo              = f($RS,'calcula_tributo');
    $w_retencao             = f($RS,'calcula_retencao');
  } 
  // Recupera a sigla do tipo do documento para tratar a Nota Fiscal e
  // verifica se o tipo de documento tem incidência de tributos e retenção.
  if ($w_sq_tipo_documento>'') {
    $sql = new db_getImpostoIncid; $RS2 = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,$w_sq_tipo_documento,null,'INCIDENCIA');
    if (count($RS2)<=0) {
      $w_incid_tributo='N';
      $w_incid_retencao='N';
    } else {
      foreach ($RS2 as $row) {
        $w_incid_tributo  = f($row,'calcula_tributo');
        $w_incid_retencao = f($row,'calcula_retencao');
        break;
      }
    } 
    $sql = new db_getTipoDocumento; $RS2 = $sql->getInstanceOf($dbms,$w_sq_tipo_documento,$w_cliente,null,null);
    foreach ($RS2 as $row) {
      $w_tipo = f($row,'sigla');
    }
  } 
  
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
  ShowHTML('<TITLE>'.$conSgSistema.' - Documentos</TITLE>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  if (strpos('IAEGCP',$O)!==false) {
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_sq_tipo_documento','Tipo do documento', '1', '1', '1', '18', '', '0123456789');
      Validate('w_numero','Número do documento', '1', '1', '1', '30', '1', '1');
      Validate('w_data','Data do documento', 'DATA', '1', '10', '10', '', '0123456789/');
      /*
      if (Nvl($w_tipo,'-')=='NF') {
        Validate('w_serie','Série do documento', '1', '1', 1, 10, '1', '1');
      } 
      */
      Validate('w_valor','Valor total do documento', 'VALOR', '1', 4, 18, '', '0123456789.,-');
      if (is_array($w_valores)) {
        ShowHTML('  for (ind=1; ind < theForm["w_valores[]"].length; ind++) {');
        Validate('["w_valores[]"][ind]','!','VALOR','1','4','18','','0123456789.,-');
        ShowHTML('  }');
      }
    }
    ShowHTML('  disAll();');
    ValidateClose();
  } 
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_sq_tipo_documento.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr><td colspan=3><b>'.upper(f($RS1,'nome')).' '.f($RS1,'codigo_interno').' ('.$w_chave.')</b></td>');
  ShowHTML('      <tr><td colspan="3">Finalidade: <b>'.CRLF2BR(f($RS1,'descricao')).'</b></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td>Forma de pagamento:<br><b>'.f($RS1,'nm_forma_pagamento').' </b></td>');
  ShowHTML('          <td>Vencimento:<br><b>'.FormataDataEdicao(f($RS1,'vencimento')).' </b></td>');
  ShowHTML('          <td>Valor:<br><b>'.formatNumber(Nvl(f($RS1,'valor'),0)).' </b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('  <tr><td>&nbsp;');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>');
    if (count($RS)==0 && strpos(f($RS_Menu,'sigla'),'VIA')===false) ShowHTML('      <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;'); 
    ShowHTML('      <a accesskey="F" class="ss" href="javascript:this.status.value;" onClick="parent.$.fancybox.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>Número</td>');
    ShowHTML('          <td><b>Emissão</td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td><b>Dedução</td>');
    ShowHTML('          <td><b>Acréscimo</td>');
    ShowHTML('          <td><b>Total</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_total=0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_tipo_documento').'</td>');
        ShowHTML('        <td align="center">'.f($row,'numero').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'data')).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor')).'&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'deducao')).'&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'acrescimo')).'&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor')+f($row,'acrescimo')-f($row,'deducao')).'&nbsp;&nbsp;</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        if (strpos(f($RS_Menu,'sigla'),'VIA')===false) ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        if (strpos(f($RS_Menu,'sigla'),'VIA')===false) ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        if (f($row,'detalha_item')=='S' && $P2!=1) ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'ITENS&R='.$w_pagina.$par.'&O=&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'- Itens'.'&SG=ITEM').'\',\'Item\',\'toolbar=no,width=780,height=530,top=40,left=20,scrollbars=yes\');" title="Informa os itens do documento.">Itens</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_total=$w_total+f($row,'valor');
      } 
    }
    if ($w_total>0 && count($RS)>1) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td align="center" colspan=3><b>Total</b></td>');
      ShowHTML('        <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;&nbsp;</td>');
      ShowHTML('        <td colspan="2">&nbsp;</td>');
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_lancamento_doc" value="'.$w_sq_lancamento_doc.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan="3"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoTipoDocumento('<u>T</u>ipo do documento:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,null,'w_sq_tipo_documento',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_numero\'; document.Form.submit();"');
    ShowHTML('          <td><b><u>N</u>úmero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o número do documento."></td>');
    ShowHTML('          <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data do documento.">'.ExibeCalendario('Form','w_data').'</td>');
    /*
    if (Nvl($w_tipo,'-')=='NF') {
      ShowHTML('          <td><b><u>S</u>érie:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_serie" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_serie.'" title="Informado apenas se o documento for NOTA FISCAL. Informe a série ou, se não tiver, digite ÚNICA."></td>');
    } 
    */
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    if (is_array($w_valores)){
      ShowHTML('<INPUT type="hidden" name="w_sq_valores[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_valores[]" value="">');
      foreach($w_valores as $row) {
        ShowHTML('<INPUT type="hidden" name="w_sq_valores[]" value="'.f($row,'chave').'">');
        ShowHTML('      <tr><td colspan="3" align="right"><b>'.f($row,'nome').':</b><td><input '.$w_Disabled.' accesskey="V" type="text" name="w_valores[]" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.f($row,'valor').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
      }
    }
    //MontaRadioNS('<b>Patrimônio?</b>',$w_patrimonio,'w_patrimonio');
    ShowHTML('<INPUT type="hidden" name="w_patrimonio" value="N">');
    ShowHTML('          </table>');
    if ($w_incid_tributo=='N' && $w_incid_retencao=='N' && substr(f($RS_Menu,'sigla'),2,1)=='D') {
      ShowHTML('<INPUT type="hidden" name="w_tributo" value="'.$w_incid_tributo.'">');
      ShowHTML('<INPUT type="hidden" name="w_retencao" value="'.$w_incid_retencao.'">');
    } else {
      if ($w_incid_retencao=='S' || substr(f($RS_Menu,'sigla'),2,1)=='R') {
        ShowHTML('      <tr>');
        MontaRadioSN('<b>Haverá retenção de tributos para este documento?',Nvl($w_retencao,$w_incid_retencao),'w_retencao',null,null,null,3);
      } else {
        ShowHTML('<INPUT type="hidden" name="w_retencao" value="'.$w_incid_retencao.'">');
      } 
      if ($w_incid_tributo=='S' || substr(f($RS_Menu,'sigla'),2,1)=='R') {
        ShowHTML('      <tr>');
        MontaRadioSN('<b>Haverá pagamento de tributos para este documento?',Nvl($w_tributo,$w_incid_tributo),'w_tributo',null,null,null,3);
      } else {
        ShowHTML('<INPUT type="hidden" name="w_tributo" value="N">');
      } 
    } 
    ShowHTML('      <tr><td align="center" colspan="3"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Voltar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('</FORM>');
    if ($O!='I') {
      // Itens
      $sql = new db_getLancamentoItem; $RS = $sql->getInstanceOf($dbms,null,$w_sq_lancamento_doc,null,null,null);
      ShowHTML('    <tr><td colspan=3 align="center" height="1" bgcolor="'.$conTrAlternateBgColor.'"></td></tr>');
      ShowHTML('    <tr><td colspan=2 bgcolor="'.$conTrAlternateBgColor.'"><b>Itens&nbsp;&nbsp;'.((substr(f($RS_Menu,'sigla'),3)!='VIA') ? '[<a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.$w_sq_lancamento_doc.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=ITEM"><u>I</u>ncluir</a>]' : '' ).'</b></td>');
      ShowHTML('        <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.exportaOffice().'<b>Registros: '.count($RS));
      ShowHTML('    <tr><td colspan=3 align="center" height="1" bgcolor="'.$conTrAlternateBgColor.'"></td></tr>');
      ShowHTML('    <tr><td colspan="3"><table border=0 width="100%">');
      // Recupera todos os registros para a listagem
      if (count($RS)>0) {
        $RS = SortArray($RS,'ordem','asc');
        // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Ordem</td>');
        if(nvl(f($RS1,'qtd_rubrica'),0)>0) ShowHTML('          <td><b>Rubrica</td>');
        ShowHTML('          <td><b>Descrição</td>');
        ShowHTML('          <td><b>Qtd.</td>');
        if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
          ShowHTML('          <td><b>Data cotação</td>');
          ShowHTML('          <td><b>Valor cotação</td>');
        }
        ShowHTML('          <td><b>$ Unitário</td>');
        ShowHTML('          <td><b>$ Total</td>');
        ShowHTML('          <td class="remover"><b>Operações</td>');
        ShowHTML('        </tr>');
        if (count($RS)==0) {
          // Se não foram selecionados registros, exibe mensagem
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
        } else {
          // Lista os registros selecionados para listagem
          $w_total=0;
          foreach($RS as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
            if(nvl(f($RS1,'qtd_rubrica'),0)>0) ShowHTML('        <td align="left">'.f($row,'codigo_rubrica').' - '.f($row,'nm_rubrica').'</td>');
            ShowHTML('        <td align="left">'.f($row,'descricao').'</td>');
            ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
            if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
              ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'data_cotacao')).'</td>');
              ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_cotacao'),4).'&nbsp;&nbsp;</td>');
            }
            ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_unitario')).'&nbsp;&nbsp;</td>');
            ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_total')).'&nbsp;&nbsp;</td>');
            ShowHTML('        <td class="remover" align="top" nowrap>');
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.f($row,'sq_lancamento_doc').'&w_sq_documento_item='.f($row,'sq_documento_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=ITEM">AL</A>&nbsp');
            if (strpos(f($RS_Menu,'sigla'),'VIA')===false) ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.f($row,'sq_lancamento_doc').'&w_sq_documento_item='.f($row,'sq_documento_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=ITEM" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
            $w_total += f($row,'valor_total');
          } 
        }
        if ($w_total>0 && count($RS)>1) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td align="right" colspan="'.((strpos(f($RS_Menu,'sigla'),'VIA')===false) ? ((nvl(f($RS1,'qtd_rubrica'),0)>0) ? 5 : 4) : 6).'"><b>Total</b></td>');
          ShowHTML('        <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;&nbsp;</td>');
          ShowHTML('        <td colspan="1">&nbsp;</td>');
          ShowHTML('      </tr>');
        }
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      }
      ShowHTML('    </table>');
      
    }
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
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
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    BodyOpenClean(null); 
    if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);
    $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  // Chama a rotina de visualização dos dados do lançamento, na opção 'Listagem'
  ShowHTML(VisualReembolso($w_chave,'L',$w_usuario,$P1,$w_embed));
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
  ShowHTML(VisualReembolso($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');

  // Recupera os dados da solicitação
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,$SG);

  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_menu_relac        = $_REQUEST['w_sq_menu_relac'];    
    if($w_sq_menu_relac=='CLASSIF') {
      $w_chave_pai          = '';
    } else {
      $w_chave_pai          = $_REQUEST['w_chave_pai'];
    }
    $w_per_ini              = $_REQUEST['w_per_ini'];
    $w_valor                = $_REQUEST['w_valor'];

    $w_tramite          = $_REQUEST['w_tramite'];
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_justif_dia_util  = $_REQUEST['w_justif_dia_util'];
  } else {
    $w_chave_pai        = f($RS_Solic,'sq_solic_pai');
    $w_dados_pai        = explode('|@|',f($RS_Solic,'dados_pai'));
    $w_sq_menu_relac    = $w_dados_pai[3];
    $w_dados_avo        = explode('|@|',f($RS_Solic,'dados_avo'));
    $w_projeto          = $w_dados_avo[11];
    if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
    $w_solic_vinculo    = f($RS_Solic,'sq_solic_vinculo');
    $w_per_ini          = FormataDataEdicao(f($RS_Solic,'referencia_inicio'),9);
    $w_valor            = formatNumber(f($RS_Solic,'valor'));

    $w_inicio           = f($RS_Solic,'inicio');
    $w_tramite          = f($RS_Solic,'sq_siw_tramite');
    $w_justificativa    = f($RS_Solic,'justificativa');
    $w_prazo            = f($RS_Solic,'limite_envio');
    $w_antecedencia     = f($RS_Solic,'dias_antecedencia');
    $w_justif_dia_util  = f($RS_Solic,'justificativa_dia_util');
    $w_fim_semana       = f($RS_Solic,'fim_semana');
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
  if ($O=='V') $w_erro = ValidaReembolso($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (substr(Nvl($w_erro,'nulo'),0,1)!='0' && (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S'))) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataDataMA();
    FormataValor();
    ValidateOpen('Validacao');
    if ($w_sg_tramite!='CI') {
      if ($w_sg_tramite=='EA') {
        Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
        if(nvl($w_sq_menu_relac,'')!='') {
          if ($w_sq_menu_relac=='CLASSIF') {
            Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
          } else {
            Validate('w_chave_pai','Vinculação','SELECT',1,1,18,1,1);
          }
        }
        if (f($RS_Menu,'solicita_cc')=='N') {
          Validate('w_chave_pai','Projeto','SELECT','1',1,18,'','0123456789');
        } else {
          Validate('w_chave_pai','Projeto','SELECT','',1,18,'','0123456789');
          Validate('w_sqcc','Classificação','SELECT','',1,18,'','0123456789');
          ShowHTML('  if (theForm.w_chave_pai.selectedIndex > 0 && theForm.w_sqcc.selectedIndex > 0) {');
          ShowHTML('     alert(\'Informe um projeto ou uma classificação. Você não pode escolher ambos!\');');
          ShowHTML('     theForm.w_chave_pai.focus();');
          ShowHTML('     return false;');
          ShowHTML('  }');
          ShowHTML('  if (theForm.w_chave_pai.selectedIndex == 0 && theForm.w_sqcc.selectedIndex == 0) {');
          ShowHTML('     alert(\'Informe um projeto ou uma classificação!\');');
          ShowHTML('     theForm.w_chave_pai.focus();');
          ShowHTML('     return false;');
          ShowHTML('  }');
        } 
        Validate('w_per_ini','Mês de Referência','DATAMA',1,7,7,'','0123456789/');
        Validate('w_valor','Valor do reembolso','VALOR','1',4,18,'','0123456789.,-');
      }
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualReembolso($w_chave,'V',$w_usuario,$P1,$P4));
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
        ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr>');
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
      }
    } else {
      if ($w_sg_tramite=='EA') {
        ShowHTML('          <tr valign="top">');
        selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
        if(Nvl($w_sq_menu_relac,'')!='') {
          ShowHTML('          <tr valign="top">');
          if ($w_sq_menu_relac=='CLASSIF') {
            SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null);
          } else {
            SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_chave_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_chave_pai',f($RS_Relac,'sigla'),null,$w_chave_pai);
          }
        }
        ShowHTML('      <tr><td><b><u>V</u>alor do reembolso:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
        ShowHTML('      <tr><td><b><u>M</u>ês de referência:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_per_ini" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.Nvl($w_per_ini,FormataDataEdicao(time(),9)).'" onKeyDown="FormataDataMA(this,event);"></td>');
      }

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
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
    }
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } elseif ($P1!=1) {
      // Se não for cadastramento, volta para a listagem
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } elseif ($P2==1) {
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
  ShowHTML(VisualReembolso($w_chave,'V',$w_usuario,$P1,$P4));
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
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,$SG);
  $w_quitacao           = FormataDataEdicao(f($RS_Solic,'quitacao'));
  $w_observacao         = f($RS_Solic,'observacao');
  $w_tramite            = f($RS_Solic,'sq_siw_tramite');
  $w_conta_debito       = f($RS_Solic,'sq_pessoa_conta');
  $w_valor_real         = formatNumber(f($RS_Solic,'valor')+f($RS_Solic,'vl_outros')-f($RS_Solic,'vl_abatimento'));
  $w_sg_forma_pagamento = f($RS_Solic,'sg_forma_pagamento');
  $w_sq_tipo_lancamento = nvl($w_sq_tipo_lancamento,f($RS_Solic,'sq_tipo_lancamento'));
  $w_inicio             = FormataDataEdicao(time());
  $w_moeda_solic        = f($RS_Solic,'sq_moeda');

  // Se for recarga da página
  if ($w_troca>'') {
    extract($_POST);
  }

  if (nvl($w_conta_debito,'')!='') {
    $sql = new db_getContaBancoData; $RS_Conta = $sql->getInstanceOf($dbms,$w_conta_debito);
    $w_moeda_conta  = f($RS_Conta,'sq_moeda');
  }
  
  $RS_Rub = array();
  
  // Se reembolso, recupera a rubrica apenas do primeiro item do primeiro documento pois são todos iguais
  if (substr($SG,3)=='REEMB') {
    // Se ligado a projeto, recupera rubricas
    $sql = new db_getSolicRubrica; $RS_Rub = $sql->getInstanceOf($dbms,f($RS_Solic,'sq_solic_pai'),null,'S',null,null,null,null,null,null);

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
  
  // Prepara array com os valores das moedas a serem gravadas
  if ($w_moeda_solic!=$w_moeda_pai || $w_moeda_solic!=$w_moeda_conta) {
    unset($w_moedas);
    if ($w_moeda_solic!=$w_moeda_pai && nvl($w_moeda_pai,'')!='')     $w_moedas[f($RS_Pai,'sq_moeda')]   = f($RS_Pai,'sb_moeda');
    if ($w_moeda_solic!=$w_moeda_conta && nvl($w_moeda_conta,'')!='') $w_moedas[f($RS_Conta,'sq_moeda')] = f($RS_Conta,'sb_moeda');
    if (is_array($w_moedas)) asort($w_moedas);
    
    $sql = new db_getSolicCotacao; $RS_Moeda_Cot = $sql->getInstanceOf($dbms,$w_cliente, $w_chave,null,null,null,null);
    foreach($RS_Moeda_Cot as $row) {
      if ($w_moeda_solic!=f($row,'sq_moeda_cot') && array_key_exists(f($row,'sq_moeda_cot'),$w_moedas)) {
        $linha = '$w_valor_'.f($row,'sq_moeda_cot').' = nvl($_REQUEST[\'w_valor_'.f($row,'sq_moeda_cot').'\'],\''.formatNumber(f($row,'vl_cotacao')).'\');';
        eval($linha);
      }
    }
  }
  
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaReembolso($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
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
    Validate('w_sq_tipo_lancamento','Tipo de lançamento', 'SELECT', 1, 1, 18, '', '0123456789');
    if (count($RS_Rub)>0) Validate('w_sq_projeto_rubrica','Rubrica', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_quitacao','Data do pagamento', 'DATA', 1, 10, 10, '', '0123456789/');
    Validate('w_valor_real','Valor real','VALOR','1', 4, 18, '', '0123456789.,-');
    if (is_array($w_moedas)) {
      foreach($w_moedas as $k => $v) {
        Validate('w_valor_'.$k,'Valor líquido ('.str_replace('&eur;','EURO',$v).')','VALOR','1', 4, 18, '', '0123456789.,-');
      }        
    }
    if (w_sg_forma_pagamento=='DEPOSITO') Validate('w_codigo_deposito','Código do depósito', '1', '1', 1, 50, '1', '1');
    if ($w_exige_conta) Validate('w_conta_debito','Conta bancária', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_observacao','Observação', '', '', '1', '500', '1', '1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
  ShowHTML(VisualReembolso($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'CONC&O='.$O.'&w_menu='.$w_menu.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML('<INPUT type="hidden" name="f_O" value="'.$O.'">');
  ShowHTML('<INPUT type="hidden" name="f_SG" value="'.$SG.'">');
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
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    }
  } else {
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    ShowHTML('      <tr><td colspan="4" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
    ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    ShowHTML('      <tr>');
    SelecaoTipoLancamento('<u>T</u>ipo de lancamento:','T','Selecione na lista o tipo de lançamento adequado.',$w_sq_tipo_lancamento,$w_menu,$w_cliente,'w_sq_tipo_lancamento',substr($SG,0,3).'VINC',null,3);
    ShowHTML('      </tr>');
    if(count($RS_Rub)>0) {
      ShowHTML('      <tr>');
      SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,f($RS_Solic,'sq_solic_pai'),null,'w_sq_projeto_rubrica','RUBRICAS',null);
      ShowHTML('      </tr>');
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>D</u>ata do '.((substr($SG,2,1)=='R') ? 'recebimento' : 'pagamento').':</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_quitacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_quitacao,$w_inicio).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de pagamento deste lançamento.">'.ExibeCalendario('Form','w_quitacao').'</td>');
    ShowHTML('        <td><b>Valo<u>r</u> real '.((nvl(f($RS_Solic,'sb_moeda'),'')!='') ? ' ('.f($RS_Solic,'sb_moeda').')' : '').':</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor_real" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_real.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor real do lançamento."></td>');
    if ($w_sg_forma_pagamento=='DEPOSITO') {
      ShowHTML('        <td><b><u>C</u>ódigo do depósito:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo_deposito" class="sti" SIZE="20" MAXLENGTH="50" VALUE="'.$w_codigo_deposito.'" title="Informe o código do depósito identificado."></td>');
    }
    if ($w_exige_conta) {
      SelecaoContaBanco('C<u>o</u>nta bancária:','O','Selecione a conta bancária envolvida no lançamento.',$w_conta_debito,null,'w_conta_debito',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.f_O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_conta_debito\'; document.Form.submit();"');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_conta" value="'.$w_conta_debito.'">');
    }

    // Exige valor nas moedas da solicitação pai e da conta bancária, se forem diferentes da moeda da solicitação
    if (is_array($w_moedas)) {
      foreach($w_moedas as $k => $v) {
        ShowHTML('<INPUT type="hidden" name="w_moeda[]" value="'.$k.'">');
        eval('$valor = $w_valor_'.$k.';');
        ShowHTML('        <tr><td><td><b>Valo<u>r</u> líquido ('.$v.'):</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor_'.$k.'" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do lançamento na moeda informada.">'.converteMoeda('Form','w_quitacao','w_valor_'.$k,'w_valor_real',f($RS_Solic,'sq_moeda'),$k).'</td>');
      }
    }

    if (count($RS_Doc)>0) {
      // Pagamentos vinculados só podem existir se houver documento para o lançamento financeiro (fatura, nota fiscal, recibo etc)
      $sql = new db_getImpostoDoc; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,$w_SG);
      $RS1 = SortArray($RS1,'sq_lancamento_doc','asc','phpdt_inclusao','asc','calculo','asc','esfera','asc','nm_imposto','asc');
      ShowHTML('      <tr><td colspan="2"><br><b>LANÇAMENTOS VINCULADOS</b>&nbsp;&nbsp;&nbsp;<A class="ss" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.'tesouraria.php?par=geral&R='.$w_pagina.$par.'&O=I&SG=FNDEVENT&w_chave_vinc='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Lançamento vinculado&SG=FNDEVENT').'\',\'SolicVinc\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Incluir lançamento vinculado a este.">Incluir</A>&nbsp</td></tr>');  
      ShowHTML('      <tr><td colspan="3" align="center"><table width=100%  border="1" bordercolor="#00000">');
      ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('            <td><b>Documento</td>');
      ShowHTML('            <td><b>Código</td>');
      ShowHTML('            <td><b>Finalidade</td>');
      ShowHTML('            <td><b>Beneficiário</td>');
      ShowHTML('            <td><b>Emissão</td>');
      ShowHTML('            <td><b>Valor</td>');
      ShowHTML('            <td><b>Operações</td>');
      ShowHTML('          </tr>');
      if (count($RS1)==0) {
        ShowHTML('      <tr valign="top" bgcolor="'.$conTrBgColor.'"><td align="center" colspan=8><b>Nenhum registro encontrado.</b></td></tr>');
      } else {
        $w_cor=$w_TrBgColor;
        $w_atual = '';
        $w_total = 0;
        $i       = 0;
        foreach ($RS1 as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          if ($w_atual <> f($row,'sq_lancamento_doc')) {
            ShowHTML('      <tr valign="top" BGCOLOR="'.$w_cor.'">');
            ShowHTML('          <td>'.f($row,'nm_tipo_documento').' '.f($row,'numero').'</td>');
            $w_atual = f($row,'sq_lancamento_doc');
          } else {
            ShowHTML('      <tr valign="top" BGCOLOR="'.$w_cor.'">');
            ShowHTML('          <td>&nbsp;</td>');
          }
          ShowHTML('          <td>');
          ShowHTML(ExibeImagemSolic(f($row,'imp_sigla'),f($row,'imp_inicio'),f($row,'imp_vencimento'),f($row,'imp_inicio'),f($row,'imp_quitacao'),f($row,'imp_aviso'),f($row,'aviso'),f($row,'imp_sg_tramite'), null));
          if (nvl(f($row,'solic_imposto'),'')!='') {
            ShowHTML('        '.exibeSolic($w_dir,f($row,'solic_imposto'),f($row,'imp_codigo'),'N',$l_tipo).'</td>');
          }
          ShowHTML('          <td>'.f($row,'nm_imposto').'</td>');
          if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nm_pessoa')).'</td>');
          else                 ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
          ShowHTML('          <td align="center">'.formataDataEdicao(f($row,'quitacao_imposto'),5).'</td>');
          ShowHTML('          <td align="right">R$ '.formatNumber(f($row,'vl_total')).'</td>');
          ShowHTML('          <td>');
          ShowHTML('            <A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.'tesouraria.php?par=geral&R='.$w_pagina.$par.'&O=A&SG=FNDEVENT&w_chave_vinc='.$w_chave.'&w_chave='.f($row,'solic_imposto').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Lançamento vinculado&SG=FNDEVENT').'\',\'SolicVinc\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Altera as informações cadastrais do lançamento">AL</A>&nbsp');
          ShowHTML('            <A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.'tesouraria.php?par=excluir&R='.$w_pagina.$par.'&O=E&SG=FNDEVENT&w_chave_vinc='.$w_chave.'&w_chave='.f($row,'solic_imposto').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Lançamento vinculado&SG=FNDEVENT').'\',\'SolicVinc\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Exclusão do lançamento">EX</A>&nbsp');
          if (f($row,'calculo')!=0) $w_vl_total+=f($row,'vl_total');
        } 
        if (count($RS1)>1) {
          ShowHTML('      <tr valign="top" bgcolor="'.$conTrBgColor.'">');
          ShowHTML('        <td align="right" colspan=5><b>Total das retenções</b></td>');
          ShowHTML('        <td align="right"><b>'.formatNumber($w_vl_total).'</b></td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('      </tr>');
        }
      } 
      ShowHTML('      </table></td></tr>');
    }
    
    ShowHTML('      <tr><td colspan="4"><b>Obs<u>e</u>rvação:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_observacao" class="sti" ROWS=5 cols=75 title="Descreva o quanto a demanda atendeu aos resultados esperados.">'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="4"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
    ShowHTML('      <tr colspan="4"><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
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
    $w_html.=$crlf.'          <td>Valor:<br><b>'.formatNumber(Nvl(f($RSM,'valor'),0)).' </b></td>';
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
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  
  if (strpos($SG,'REEMB')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_menu'],
          $_REQUEST['w_sq_unidade'],$_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_sqcc'],
          $_REQUEST['w_descricao'],$_REQUEST['w_vencimento'],
          Nvl($_REQUEST['w_valor'],0),$_REQUEST['w_data_hora'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],
          $_REQUEST['w_chave_pai'],$_REQUEST['w_sq_acordo_parcela'],$_REQUEST['w_observacao'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
          $_REQUEST['w_sq_forma_pagamento'],$_REQUEST['w_tipo_pessoa'],$_REQUEST['w_forma_atual'],
          $_REQUEST['w_vencimento_atual'],$_REQUEST['w_tipo_rubrica'],nvl($_REQUEST['w_protocolo'],$_REQUEST['w_numero_processo']),
          ((nvl($_REQUEST['w_per_ini'],'')=='') ? '' : '01/').$_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],
          $_REQUEST['w_texto_pagamento'],null,$_REQUEST['w_sq_projeto_rubrica'],
          $_REQUEST['w_solic_apoio'],$_REQUEST['w_data_autorizacao'],$_REQUEST['w_texto_autorizacao'],$_REQUEST['w_moeda'],
          &$w_chave_nova,&$w_codigo);

      if ($O!='E') {

        // Recupera os dados do beneficiário
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_pessoa'],null,null,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
        foreach ($RS as $row) {$RS=$row; break;}

        //Grava os dados do beneficiário
        $SQL = new dml_putLancamentoOutra; $SQL->getInstanceOf($dbms,$O,$SG,$w_chave_nova,$w_cliente,$_REQUEST['w_pessoa'],
            f($RS,'cpf'),f($RS,'cnpj'),null,null,null,null,null,null,null,null,null,null,f($RS,'logradouro'),f($RS,'complemento'),f($RS,'bairro'),
            f($RS,'sq_cidade'),f($RS,'cep'),f($RS,'ddd'),f($RS,'nr_telefone'),f($RS,'nr_fax'),f($RS,'nr_celular'),f($RS,'email'), 
            $_REQUEST['w_sq_agencia'],$_REQUEST['w_operacao'],$_REQUEST['w_nr_conta'],$_REQUEST['w_sq_pais_estrang'],
            $_REQUEST['w_aba_code'],$_REQUEST['w_swift_code'],$_REQUEST['w_endereco_estrang'],$_REQUEST['w_banco_estrang'],
            $_REQUEST['w_agencia_estrang'],$_REQUEST['w_cidade_estrang'],$_REQUEST['w_informacoes'],$_REQUEST['w_codigo_deposito'],
            $_REQUEST['w_pessoa_atual'],$_REQUEST['w_conta']);
      }
          
      ScriptOpen('JavaScript');
      if ($P1==0) {
        // Recupera os trâmites de cadastramento inicial e de execução 
        $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_menu,null, null,'S');
        $RS = SortArray($RS,'ordem','asc');
        foreach ($RS as $row) {
          if (f($row,'sigla')=='CI') $w_ci = f($row,'sq_siw_tramite');
          elseif (f($row,'sigla')=='EE') $w_ee = f($row,'sq_siw_tramite');
        }
        
        // Grava versão da solicitação
        $w_html = VisualReembolso($w_chave_nova,'L',$w_usuario,'2','1');
        CriaBaseLine($w_chave_nova,$w_html,f($RS_Menu,'nome'),$w_ee);
        
        // Envia a solicitação para execução   
        $SQL = new dml_putLancamentoEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$w_chave_nova,$w_usuario,$w_ci,
                $w_ee,'N',null,$w_usuario,'Envio automático de lançamento financeiro.',null,null,null,null);
        

        if ($P2==1) {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'geral&O=A&w_chave='.nvl($_REQUEST['w_chave'],$w_chave_nova).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        } else {
          // Volta para o módulo tesouraria
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
        }
      } elseif ($P2==1 && $O!='E') {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'geral&O=A&w_chave='.nvl($_REQUEST['w_chave'],$w_chave_nova).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'OUTRAP')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
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
      ShowHTML('  parent.location.reload();');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
    // Inclusão, Alteração e exclusão de documentos relativos a um lançamento.
  } elseif ($SG=='DOCUMENTO') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putLancamentoDoc; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_sq_lancamento_doc'],$_REQUEST['w_sq_tipo_documento'],
        $_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_serie'],$_REQUEST['w_valor'],$_REQUEST['w_patrimonio'],$_REQUEST['w_retencao'],
        $_REQUEST['w_tributo'],null,null,null,null,&$w_chave_nova);

      $SQL = new dml_putLancamentoValor; 
      
      // Remove os registros existentes
      $SQL->getInstanceOf($dbms,'E',$w_chave_nova,null,null);
      
      // Insere os registros com valor maior que zero
      for ($i=0; $i<=count($_POST['w_valores'])-1; $i=$i+1) {
        if (Nvl($_REQUEST['w_valores'][$i],'0,00')!='0,00') {
          $SQL->getInstanceOf($dbms,'I',$w_chave_nova,$_REQUEST['w_sq_valores'][$i],$_REQUEST['w_valores'][$i]);
        } 
      }
      ScriptOpen('JavaScript');
      
      if ($P1==0) {
        if ($P2==1) {
          // Volta para a tela do documento
          //ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=A&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&w_sq_lancamento_doc='.nvl($_REQUEST['w_sq_lancamento_doc'],$w_chave_nova).'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
        }
      } elseif ($P2==1 && $O!='E') {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=A&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&w_sq_lancamento_doc='.nvl($_REQUEST['w_sq_lancamento_doc'],$w_chave_nova).'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
    // Envio de lançamentos.
  } elseif (strpos($SG,'ENVIO')!==false) {
    // Verifica se a Assinatura Eletrônica é válida 
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
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
              $w_html = VisualReembolso($_REQUEST['w_chave'],'L',$w_usuario,$P1,'1');
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
        } elseif ($P2==1) {
          ShowHTML('  parent.location.reload();');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        }
        ScriptClose();
      } else {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $SG);
        if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta solicitação para outra fase!\');');
          ScriptClose();
        } else {
          if (Nvl($_REQUEST['w_valor'],0)>0) {
            // Atualiza os dados da solicitação
            $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,'A',$w_cliente,$_REQUEST['w_chave'],f($RS,'sq_menu'),
                f($RS,'sq_unidade'),f($RS,'solicitante'),f($RS,'pessoa'),f($RS,'sq_cc'),f($RS,'descricao'),FormataDataEdicao(f($RS,'fim')),
                Nvl($_REQUEST['w_valor'],0),f($RS,'data_hora'),f($RS,'aviso_prox_conc'),f($RS,'dias_aviso'),f($RS,'sq_cidade_origem'),
                $_REQUEST['w_chave_pai'],f($RS,'sq_acordo_parcela'),f($RS,'observacao'),f($RS,'sq_tipo_lancamento'),
                f($RS,'sq_forma_pagamento'),f($RS,'sq_tipo_pessoa'),f($RS,'sq_forma_pagamento'),FormataDataEdicao(f($RS,'fim')),f($RS,'tipo_rubrica'),
                f($RS,'processo'),((nvl($_REQUEST['w_per_ini'],'')=='') ? '' : '01/').$_REQUEST['w_per_ini'],
                FormataDataEdicao(f($RS,'referencia_fim')),f($RS,'condicoes_pagamento'),null,f($RS,'sq_projeto_rubrica'),
                f($RS,'sq_solic_apoio'),FormataDataEdicao(f($RS,'data_autorizacao')),f($RS,'texto_autorizacao'),f($RS,'sq_moeda'),
                &$w_chave_nova,&$w_codigo);
          }
          
          $SQL = new dml_putLancamentoEnvio;
          $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'],
                  $_REQUEST['w_novo_tramite'], 'N', $_REQUEST['w_observacao'], $_REQUEST['w_destinatario'], $_REQUEST['w_despacho'],
                  null, null, null, null);
          //Rotina para gravação da imagem da versão da solicitacão no log.
          if ($_REQUEST['w_tramite'] != $_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData;
            $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS, 'sigla');
            if ($w_sg_tramite == 'CI') {
              $w_html = VisualReembolso($_REQUEST['w_chave'], 'L', $w_usuario, $P1, '1');
              CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
            }
          }
          // Envia e-mail comunicando de tramitação
          SolicMail($_REQUEST['w_chave'], 2);
          ScriptOpen('JavaScript');
          if ($P1 == 0) {
            ShowHTML('  location.href=\'' . montaURL_JS($w_dir, 'tesouraria.php?par=inicial&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . MontaFiltro('GET')) . '\';');
          } elseif ($P2==1) {
            ShowHTML('  parent.location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
          } else {
            ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
          }
          ScriptClose();
        }
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'ENVAUT')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $SG);
      if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta solicitação para outra fase!\');');
        ScriptClose();
      } else {
        if (Nvl($_REQUEST['w_valor'],0)>0) {
          // Atualiza os dados da solicitação
          $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,'A',$w_cliente,$_REQUEST['w_chave'],f($RS,'sq_menu'),
              f($RS,'sq_unidade'),f($RS,'solicitante'),f($RS,'pessoa'),f($RS,'sq_cc'),f($RS,'descricao'),FormataDataEdicao(f($RS,'fim')),
              Nvl($_REQUEST['w_valor'],0),f($RS,'data_hora'),f($RS,'aviso_prox_conc'),f($RS,'dias_aviso'),f($RS,'sq_cidade_origem'),
              $_REQUEST['w_chave_pai'],f($RS,'sq_acordo_parcela'),f($RS,'observacao'),f($RS,'sq_tipo_lancamento'),
              f($RS,'sq_forma_pagamento'),f($RS,'sq_tipo_pessoa'),f($RS,'sq_forma_pagamento'),FormataDataEdicao(f($RS,'fim')),f($RS,'tipo_rubrica'),
              f($RS,'processo'),((nvl($_REQUEST['w_per_ini'],'')=='') ? '' : '01/').$_REQUEST['w_per_ini'],
              FormataDataEdicao(f($RS,'referencia_fim')),f($RS,'condicoes_pagamento'),null,f($RS,'sq_projeto_rubrica'),
              f($RS,'sq_solic_apoio'),FormataDataEdicao(f($RS,'data_autorizacao')),f($RS,'texto_autorizacao'),f($RS,'sq_moeda'),
              &$w_chave_nova,&$w_codigo);
        }

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
            ShowHTML(VisualReembolso($_REQUEST['w_chave'],'V',$w_usuario,$P1,$P4));
            CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
          }
        }  
        // Envia e-mail comunicando o envio
        SolicMail($_REQUEST['w_chave'],2);
      }
      
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
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'CONC')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      $w_moeda_solic = f($RS,'sq_moeda');
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
          if (is_array($_REQUEST['w_moeda'])) {
            // Remove as cotações existentes
            $SQL = new dml_putSolicCotacao; $SQL->getInstanceOf($dbms,'E',$_REQUEST['w_chave'],null,null);
            
            // Insere a cotação da moeda da solicitação
            $SQL = new dml_putSolicCotacao; $SQL->getInstanceOf($dbms,'I',$_REQUEST['w_chave'],$w_moeda_solic,$_REQUEST['w_valor_real']);
            
            // Insere as cotações das moedas da solicitação pai e da conta bancária, desde que sejam diferentes da moeda da solicitação
            foreach($_REQUEST['w_moeda'] as $k=>$v) {
              $SQL = new dml_putSolicCotacao; $SQL->getInstanceOf($dbms,'I',$_REQUEST['w_chave'],$v,$_REQUEST['w_valor'.'_'.$v]);
            }
          }
          $SQL = new dml_putFinanceiroConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                  $_REQUEST['w_quitacao'],$_REQUEST['w_valor_real'],$_REQUEST['w_codigo_deposito'],$_REQUEST['w_conta_debito'],
                  $_REQUEST['w_sq_tipo_lancamento'],$_REQUEST['w_sq_projeto_rubrica'],$_REQUEST['w_observacao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
          
          $w_html = VisualReembolso($_REQUEST['w_chave'],'L',$w_usuario,'2','1');
          CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
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
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
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