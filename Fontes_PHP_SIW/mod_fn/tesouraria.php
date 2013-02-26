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
include_once($w_dir_volta.'classes/sp/db_getMenuList.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getBankData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoValor.php');
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
include_once($w_dir_volta.'classes/sp/db_getKindPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getCronograma.php'); 
include_once($w_dir_volta.'classes/sp/db_getVincKindList.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoNota.php');
include_once($w_dir_volta.'classes/sp/db_getImposto.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoOutra.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroConc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/dml_putImpostoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'funcoes/selecaoImposto.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoMoeda.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoContaBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAcordoParcela.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
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
include_once($w_dir_volta.'funcoes/selecaoRubricaApoio.php');
include_once($w_dir_volta.'funcoes/selecaoTipoRubrica.php');
include_once('visuallancamento.php');
include_once('validalancamento.php');
//  /tesouraria.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Controle de tesouraria
// Mail     : alex@sbpi.com.br
// Criacao  : 12/02/2001 13:30
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
$P2         = nvl($_REQUEST['P2'],1);
$P3         = nvl($_REQUEST['P3'],1);

if ($P2>2)  {
  if (nvl($_REQUEST['P4'],0)==2000) $P4 = $conPageSize; else  $P4 = nvl($_REQUEST['P4'],$conPageSize);
} else {
  $P4 = 2000;
}

$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'tesouraria.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];

$w_copia        = $_REQUEST['w_copia'];
if ((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) || $P2==3) {
  $p_fechado = 'all';
} else {
  $p_fechado = 'none';
}
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
$p_sq_acao_ppa  = $_REQUEST['p_sq_acao_ppa'];
$p_empenho      = $_REQUEST['p_empenho'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
if (nvl($SG,'')!='') $w_menu = RetornaMenu($w_cliente,$SG);

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

// Verifica se o cliente tem o módulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_mod_pa='S'; else $w_mod_pa='N';

// Verifica se deve a tela de tesouraria deve ser exibida de forma resumida
// O teste baseia-se no cliente. Lembrar de criar parâmetro no módulo financeiro
$w_visao_completa = true;
if ($_SESSION['P_CLIENTE']==10135 || $_SESSION['P_CLIENTE']==17305) $w_visao_completa = false;

if (nvl($SG,'')!='') {
  // Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
  $sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
  if (count($RS)>0) {
    $w_submenu = 'Existe';
  } else {
    $w_submenu = '';
  }
  // Recupera a configuração do serviço
 $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
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
  if ($P2==3 && strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')===false) {
    // Tela de histórico deve ter filtro
    $RS = array();
  } else {
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $sql = new db_getSolicFN; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, $p_sq_acao_ppa, $p_sq_orprior, $p_empenho);
    } else {
      $sql = new db_getSolicFN; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P2,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, $p_sq_acao_ppa, $p_sq_orprior, $p_empenho);
    }
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'dt_pagamento','asc','nm_pessoa','asc','vencimento','asc','valor','asc');
    } else {
      $RS = SortArray($RS,'dt_pagamento','asc','nm_pessoa','asc','vencimento','asc','valor','asc');
    } 
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
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=tesouraria.php?par=inicial&P1=0&P2=1&P3='.$P3.'&P4='.$P4.'&TP='.$TP.montaFiltro('GET').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    FormataCPF();
    FormataCNPJ();
    Modulo();
    SaltaCampo();
    openBox('reload');
    ValidateOpen('Validacao');
    Validate('p_sq_acao_ppa','CPF','CPF','','14','14','','0123456789-.');
    Validate('p_empenho','CNPJ','CNPJ','','18','18','','0123456789/-.');
    Validate('p_atraso','Vinculação','','','2','90','1','1');
    Validate('p_uf','Projeto','','','2','90','1','1');
    Validate('p_proponente','Beneficiário','','','2','90','1','');
    Validate('p_fim_i','Pagamento inicial','DATA','','10','10','','0123456789/');
    Validate('p_fim_f','Pagamento final','DATA','','10','10','','0123456789/');
    CompData('p_fim_i','Pagamento inicial','<=','p_fim_f','Pagamento final');
    if ($w_segmento=='Público' || $w_mod_pa=='S') {
      Validate('p_regiao', 'Número', '1', '', '1', '6', '', '0123456789');
      Validate('p_cidade', 'Ano', '1', '', '4', '4', '', '0123456789');
    }
    if ($w_visao_completa) {
      Validate('p_ini_i','Vencimento inicial','DATA','','10','10','','0123456789/');
      Validate('p_ini_f','Vencimento final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_ini_i','Vencimento inicial','<=','p_ini_f','Vencimento final');
      ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
      ShowHTML('     theForm.p_fim_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('p_palavra','Código lançamento','','','2','90','1','1');
      Validate('p_objeto','Finalidade/justificativa','','','2','90','1','1');
      Validate('p_prazo','Dias para o vencimento','','','1','2','','0123456789');
      //Validate('p_chave','Número do lançamento','','','1','18','','0123456789');
    }
    if ($w_visao_completa || $P2 > 2) Validate('P4','Linhas por página','1','1','1','4','','0123456789');
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  elseif ($O=='I') BodyOpen('onLoad=\'document.Form.w_smtp_server.focus();\'');
  elseif ($O=='A') BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  elseif ($O=='E') BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  elseif (strpos('CP',$O)!==false) BodyOpen('onLoad=\'document.focus();\'');
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
  
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($w_tipo!='WORD') {
      ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'TipoLancamento&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'">[<u>I</u>ncluir novo lançamento]</a>&nbsp;&nbsp;&nbsp;');
    }
    ShowHTML('                         <a class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2=1&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.(($P2==1) ? '<font color="#BC5100">' : '').'[Movimento]</font></a>');
    ShowHTML('                         <a class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2=2&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.(($P2==2) ? '<font color="#BC5100">' : '').'[Agendamentos]</font></a>');
    ShowHTML('                         <a class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2=3&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.(($P2==3) ? '<font color="#BC5100">' : '').'[Relatórios]</font></a>');
    $sql = new db_getLinkData; $RS_Volta = $sql->getInstanceOf($dbms,$w_cliente,'MESA');
    ShowHTML('  &nbsp;&nbsp;&nbsp;&nbsp;<a class="SS" href="'.$conRootSIW.f($RS_Volta,'link').'&P1='.f($RS_Volta,'p1').'&P2='.f($RS_Volta,'p2').'&P3='.f($RS_Volta,'p3').'&P4='.f($RS_Volta,'p4').'&TP=<img src='.f($RS_Volta,'imagem').' BORDER=0>'.f($RS_Volta,'nome').'&SG='.f($RS_Volta,'sigla').'" target="content">[Voltar para '.f($RS_Volta,'nome').']</a>');
    ShowHTML('    <td align="right">');
    //if ($w_tipo!='WORD') {
      //ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      //ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    //}      
    ShowHTML('    <b>Registros: '.count($RS));
  
    // Filtro
    ShowHTML('<tr><td colspan="2"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('  <tr id="tr-0" bgcolor="'.$conTrBgColor.'"><td>'.colapsar(0,$p_fechado).'<b>Filtro</b>');
    ShowHTML('  <tr style="display:'.$p_fechado.'" id="tr-0-1_1" class="arvore"><td width="100%"><table border="0" width="100%" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('    <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td width="100%"><table border=0 cellspacing=0 align="center" width="97%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="p_fechado" value="'.$p_fechado.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    ShowHTML('      <tr><td><table border=0 width="100%">');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoLancamento('<u>T</u>ipo de lancamento:','T','Selecione na lista o tipo de lançamento adequado.',$p_sq_orprior,null,$w_cliente,'p_sq_orprior',f($RS_Menu_Origem,'sigla'),null,3);
    ShowHTML('      <tr valign="top">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      SelecaoCC('C<u>l</u>assificação:','C','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC',null,3);
    } 
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_sq_acao_ppa" VALUE="'.$p_sq_acao_ppa.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    ShowHTML('          <td><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_empenho" VALUE="'.$p_empenho.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>V</U>inculação:<br><INPUT ACCESSKEY="V" '.$w_Disabled.' class="sti" type="text" name="p_atraso" size="18" maxlength="18" value="'.$p_atraso.'"></td>');
    ShowHTML('          <td><b><U>P</U>rojeto:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="sti" type="text" name="p_uf" size="18" maxlength="18" value="'.$p_uf.'"></td>');
    ShowHTML('          <td><b><U>B</U>eneficiário:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoContaBanco('C<u>o</u>nta bancária:','O','Selecione a conta bancária envolvida no lançamento.',$p_pais,null,'p_pais',null,null);
    ShowHTML('          <td><b>Paga<u>m</u>ento entre:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    if ($w_segmento=='Público' || $w_mod_pa=='S') {
      ShowHTML('          <td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_regiao" style="text-align:right;" size="7" maxlength="6" value="' . $p_regiao . '">/<INPUT class="STI" type="text" name="p_cidade" size="4" maxlength="4" value="' . $p_cidade . '"></td>');
      if ($w_visao_completa) ShowHTML('      <tr valign="top">');
    }
    if ($w_visao_completa) {
      ShowHTML('          <td><b>Ven<u>c</u>imento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>Có<U>d</U>igo lançamento:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_palavra" size="18" maxlength="18" value="'.$p_palavra.'"></td>');
      ShowHTML('          <td><b><U>F</U>inalidade/justificativa:<br><INPUT ACCESSKEY="F" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
      ShowHTML('          <td><b>Dias para o <U>v</U>encimento:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      /*
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>Chave do lançament<U>o</U>:<br><INPUT ACCESSKEY="O" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      if($w_segmento=='Público') {
        ShowHTML('          <td><b><U>N</U>úmero do processo:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_processo" size="18" maxlength="18" value="'.$p_processo.'"></td>');
      }
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor responsável:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor do contrato na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde o contrato se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr valign="top">');
      ShowHTML('      <tr valign="top">');
      SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
      */
    }
    if ($P2>2) {
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    } else {
      ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    }
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('  </td></tr></table>');
    ShowHTML('</td></tr></table>');
    
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan = 0;
    if ($w_tipo!='WORD') {    
      $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Código','codigo_interno').'</td>');
      if ($w_segmento=='Público' || $w_mod_pa=='S') {
        $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Protocolo','protocolo').'</font></td>');
      }
      if ($w_visao_completa && $P2==1) {
        $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena((($P1==0) ? 'Vencimento' : 'Pagamento'),'dt_pagamento').'</td>');
      } elseif ($P2>1) {
        $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Dt. Pag.','dt_pagamento').'</td>');
      }
      if ($w_visao_completa) {
        $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Beneficiário','nm_pessoa_resumido').'</td>');
      } else {
        $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Beneficiário','nm_pessoa').'</td>');
      }
      ShowHTML('          <td colspan="3"><b>Documento</td>');
      ShowHTML('          <td rowspan="2" width="1%">&nbsp;</td>');
      if ($w_visao_completa) {
        ShowHTML ('         <td rowspan="2"><b>'.LinkOrdena('Vinculação','dados_pai').'</td>');
        ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Projeto','dados_avo').'</td>');
        ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Forma','sg_forma_pagamento').'</td>');
        ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('BCO','cd_banco').'</td>');
        ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('AGE','cd_agencia').'</td>');
        ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('C/C','numero_conta').'</td>');
      }
      if ($P2>1) ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Conta Débito','conta_debito').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan="2"><b>Operações</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Tipo','sg_doc').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Número','nr_doc').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
      ShowHTML('        </tr>');
    } else {
      $colspan++; ShowHTML('          <td rowspan="2"><b>Dt. Pag.</td>');
      $colspan++; ShowHTML('          <td rowspan="2"><b>Código</td>');
      $colspan++; ShowHTML('          <td rowspan="2"><b>Beneficiário</td>');
      ShowHTML('          <td colspan="3"><b>Documento</td>');
      $colspan++; ShowHTML('          <td rowspan="2" width="1%">&nbsp;</td>');
      if ($w_visao_completa) {
        ShowHTML('          <td rowspan="2"><b>Vinculação</td>');
        ShowHTML('          <td rowspan="2"><b>Projeto</td>');
        ShowHTML('          <td rowspan="2"><b>Forma</td>');
        ShowHTML('          <td rowspan="2"><b>BCO</td>');
        ShowHTML('          <td rowspan="2"><b>AGE</td>');
        ShowHTML('          <td rowspan="2"><b>C/C</td>');
      }
      if ($P2>1) ShowHTML('          <td rowspan="2"><b>Conta Débito</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      $colspan++; ShowHTML('          <td><b>Tipo</td>');
      $colspan++; ShowHTML('          <td><b>Número</td>');
      ShowHTML('          <td><b>Valor</td>');
      ShowHTML('        </tr>');
    }  
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=15 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      if($w_tipo!='WORD') $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      else                $RS1 = $RS;
      $w_alerta = false;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if (f($row,'sg_tramite')=='PP') {
          ShowHTML('      <tr bgcolor="'.$conTrBgColorLightRed1.'" valign="top">');
          $w_alerta = true;
        } else {
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        }
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'vencimento'),f($row,'inicio'),f($row,'quitacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        ShowHTML('        '.exibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'codigo_interno'),'N',$w_tipo).'</td>');
        if ($w_mod_pa=='S') {
          if ($w_embed!='WORD' && nvl(f($row,'protocolo_siw'),'')!='') {
            ShowHTML('        <td align="right"><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($row,'protocolo').'&nbsp;</a>');
          } else {
            ShowHTML('        <td align="right">'.f($row,'protocolo'));
          }
        }
        if ($w_visao_completa) {
          ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'dt_pagamento'),5),'-').'</td>');
        } elseif ($P2>1) {
          ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'dt_pagamento'),5),'-').'</td>');
        }
        if (f($row,'sigla')=='FNATRANSF') {
          // Transferência entre contas
          ShowHTML('        <td colspan="3">'.f($row,'nm_banco').' '.f($row,'numero_conta').'</td>');
        } elseif (substr(f($row,'sigla'),0,3)=='FNA' || f($row,'sigla')=='FNDTARIFA') {
          ShowHTML('        <td colspan="3">'.f($row,'nm_banco_debito').'</td>');
        } else {
          if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
            if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,(($w_visao_completa) ? f($row,'nm_pessoa_resumido') : f($row,'nm_pessoa'))).'</td>');
            else                 ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
          } else {
            ShowHTML('        <td align="center">---</td>');
          }
          ShowHTML('        <td title="'.f($row,'nm_doc').'">'.f($row,'sg_doc').'</td>');
          ShowHTML('        <td>'.f($row,'nr_doc').'</td>');
        }
        ShowHTML('        <td align="right">'.((nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').formatNumber(f($row,'valor')).'&nbsp;</td>');
        $w_valor = nvl(((f($row,'sg_tramite')=='AT') ? f($row,'valor_atual') : f($row,'valor')),0);
        if     (substr(f($row,'sigla'),2,1)=='R' || f($row,'sigla')=='FNAAPLICA') $w_parcial += Nvl($w_valor,0);
        elseif (substr(f($row,'sigla'),2,1)=='D') $w_parcial -= Nvl($w_valor,0);

        if ($w_valor==0 || f($row,'sigla')=='FNATRANSF')                          { ShowHTML('          <td>&nbsp;</td>'); }
        elseif (substr(f($row,'sigla'),2,1)=='R' || f($row,'sigla')=='FNAAPLICA') { ShowHTML('          <td align="center"><b>+</b></td>'); }
        elseif (substr(f($row,'sigla'),2,1)=='D') { ShowHTML('          <td align="center"><b>-</b></td>'); }
        
        if ($w_visao_completa) {
          $w_pai_projeto = false;
          if (Nvl(f($row,'dados_pai'),'')!='') {
            $w_pai = explode('|@|',f($row,'dados_pai'));
            if ($w_pai[0]=='???') {
              ShowHTML('        <td>&nbsp;</td>');
            } else {
              if ($w_pai[11]=='PR') {
                $w_pai_projeto = true;
                ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_tipo).'</td>');
              }
              ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_tipo).'</td>');
            }
          } else {
            ShowHTML('        <td>&nbsp;</td>');
          }
            
          if (!$w_pai_projeto && Nvl(f($row,'dados_avo'),'')!='') {
            $w_avo = explode('|@|',f($row,'dados_avo'));
            if ($w_avo[11]=='PR') { 
              ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_avo'),f($row,'dados_avo'),'N',$w_tipo).'</td>');
            } else {
              ShowHTML('        <td>&nbsp;</td>');
            }
          }
          
          if (substr(f($row,'sigla'),2,1)=='R') {
            ShowHTML('        <td colspan=4>&nbsp;</td>');
          } else {
            ShowHTML('        <td'.((Nvl(f($row,'sg_forma_pagamento'),'')!='') ? ' title="'.f($row,'nm_forma_pagamento').'"' : '').'>'.Nvl(f($row,'sg_forma_pagamento'),'&nbsp;').'</td>');
            ShowHTML('        <td align="center"'.((nvl(f($row,'cd_banco'),'')!='') ? ' title="'.f($row,'nm_banco').'"' : '').'>'.Nvl(f($row,'cd_banco'),'&nbsp;').'</td>');
            ShowHTML('        <td align="center"'.((nvl(f($row,'cd_agencia'),'')!='') ? ' title="'.f($row,'nm_agencia').'"' : '').'>'.Nvl(f($row,'cd_agencia'),'&nbsp;').'</td>');
            ShowHTML('        <td>'.Nvl(f($row,'numero_conta'),'&nbsp;').'</td>');
          }
        }
        if ($P2>1) {
          if (nvl(f($row,'conta_debito'),'')!='') {
            ShowHTML('        <td>'.f($row,'nm_banco_debito').' '.f($row,'conta_debito').((nvl(f($row,'sg_moeda_cc'),'')=='') ? '' : ' ('.f($row,'sg_moeda_cc').')').'</td>');
          } else {
            ShowHTML('        <td>&nbsp;</td>');
          }
        }
        
        if ($w_tipo!='WORD') {
          ShowHTML('        <td align="top" nowrap>');
          if (f($row,'sigla')=='FNDREEMB') {
            $w_destino = 'reembolso';
            $w_acao    = 'Pagar';
          } elseif (f($row,'sigla')=='FNDFUNDO') {
            $w_destino = 'pagfundo';
            $w_acao    = 'Pagar';
          } elseif (f($row,'sigla')=='FNDFUNDO') {
            $w_destino = 'fundofixo';
            $w_acao    = 'Encerrar';
          } elseif (f($row,'sigla')=='FNAAPLICA') {
            $w_destino = 'aplicacao';
            $w_acao    = 'Registrar';
          } elseif (f($row,'sigla')=='FNATRANSF') {
            $w_destino = 'transferencia';
            $w_acao    = 'Registrar';
          } elseif (f($row,'sigla')=='FNDTARIFA') {
            $w_destino = 'tarifa';
            $w_acao    = 'Registrar';
          } else {
            $w_destino = 'lancamento';
            $w_acao    = ((substr(f($row,'sigla'),2,1)=='R') ? 'Receber' : 'Pagar');
          }
          
          if (f($row,'sg_tramite')=='EE' || $P2==2) {
            if (f($row,'sigla')!='FNDFUNDO') {
              if (f($row,'sigla')=='FNDTARIFA' || substr(f($row,'sigla'),0,3)=='FNA' || (f($row,'usuario_logado')=='S' && f($row,'sigla')!='FNDREEMB')) {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Registro do pagamento.">AL</A>&nbsp');
                if (substr(f($row,'sigla'),0,3)=='FNA' || f($row,'sigla')=='FNDTARIFA' || f($row,'sigla')=='FNDFIXO') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Excluir&R='.$w_pagina.$par.'&O=E&w_retorno=Volta&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
                } else {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_retorno=Volta&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
                }
              }
              if (substr(f($row,'sigla'),0,3)!='FNA' && f($row,'sigla')!='FNDTARIFA' && f($row,'sigla')!='FNDREEMB') {
                if ($w_visao_completa) {
                  ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=OutraParte&R='.$w_pagina.$par.'&O=A&w_menu='.nvl($w_menu,f($row,'sq_menu')).'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG='.substr(f($row,'sigla'),0,3).'OUTRAP').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa dados da pessoa associada ao lançamento.">Pessoa</A>&nbsp');
                  if(nvl(f($row,'qtd_nota'),'')!='') ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=Notas&R='.$w_pagina.$par.'&O=L&w_menu='.nvl($w_menu,f($row,'sq_menu')).'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Notas'.'&SG=NOTA').'\',\'Nota\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa os valores específicos para cada nota de empenho ligado a parcela.">NE</A>&nbsp');
                } else {
                  if (nvl(f($row,'pessoa'),'')=='' || substr(f($row,'sigla'),3)=='REEMB') ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=OutraParte&R='.$w_pagina.$par.'&O=A&w_menu='.nvl($w_menu,f($row,'sq_menu')).'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG='.substr(f($row,'sigla'),0,3).'OUTRAP').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa dados da pessoa associada ao lançamento.">Pessoa</A>&nbsp');
                }
              }
              if (f($row,'sigla')=='FNDTARIFA' || substr(f($row,'sigla'),0,3)=='FNA' || (f($row,'usuario_logado')=='S' && f($row,'sigla')!='FNDREEMB')) {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'">'.$w_acao.'</A>&nbsp');
              }
              if (substr(f($row,'sigla'),0,3)!='FNA' && f($row,'sigla')!='FNDTARIFA' && f($row,'sigla')!='FNDFIXO' && !(f($row,'usuario_logado')=='S' && f($row,'sigla')!='FNDREEMB')) {
                ShowHTML('          <A class="hl" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET')).'" title="Envia o lançamento para outro responsável ou fase.">EN</A>&nbsp');
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'"'.(($P2==2) ? ' title="Ajuste nos dados do pagamento.">AL' : '>'.$w_acao).'</A>&nbsp');
              }
            } else {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'"'.(($P2>=2) ? ' title="Ajuste nos dados do pagamento.">AL' : '>'.$w_acao).'</A>&nbsp');
            }
          } else {
            if (f($row,'sigla')=='FNDTARIFA' || substr(f($row,'sigla'),0,3)=='FNA' || f($row,'sigla')=='FNDFIXO') {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Registro do pagamento.">AL</A>&nbsp');
              if (substr(f($row,'sigla'),0,3)=='FNA' || f($row,'sigla')=='FNDTARIFA') {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Excluir&R='.$w_pagina.$par.'&O=E&w_retorno=Volta&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
              } else {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_retorno=Volta&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
              }
            } else {
              ShowHTML('          <A class="hl" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_menu='.f($row,'sq_menu').'&SG='.f($row,'sigla').MontaFiltro('GET')).'" title="Envia o lançamento para outro responsável ou fase.">EN</A>&nbsp');
              if (f($row,'sigla')=='FNDFUNDO') {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Ajustes nos dados do pagamento.">AL</A>&nbsp');
              } else {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Ajuste nos dados do pagamento.">AL</A>&nbsp');
              }
            }
          }
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      }
      if ($P2>1||$w_visao_completa) { 
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td align="right" colspan="'.$colspan.'"><b>Subtotal</td>');
          ShowHTML('          <td align="right"><b>'.formatNumber(abs($w_parcial),2).'&nbsp;</td>');
          
          if ($w_parcial>0)     ShowHTML('          <td align="center"><b>+</b></td>');
          elseif ($w_parcial<0) ShowHTML('          <td align="center"><b>-</b></td>');
          else                  ShowHTML('          <td>&nbsp;</td>');

          ShowHTML('          <td colspan="8">&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            $w_valor = nvl(((f($row,'sg_tramite')=='AT') ? f($row,'valor_atual') : f($row,'valor')),0);
            if (substr(f($row,'sigla'),2,1)=='R' || f($row,'sigla')=='FNAAPLICA') $w_total += Nvl($w_valor,0);
            elseif (substr(f($row,'sigla'),2,1)=='D') $w_total -= Nvl($w_valor,0);
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td align="right" colspan="'.$colspan.'"><b>Total</td>');
          ShowHTML('          <td align="right"><b>'.formatNumber(abs($w_total),2).'&nbsp;</td>');
          
          if ($w_total>0)     ShowHTML('          <td align="center"><b>+</b></td>');
          elseif ($w_total<0) ShowHTML('          <td align="center"><b>-</b></td>');
          else                ShowHTML('          <td>&nbsp;</td>');

          ShowHTML('          <td colspan="8">&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_alerta) {
      ShowHTML('<tr><td colspan=3><b>Observação: linhas na cor vermelha indicam pendência para pagamento.');
    }
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
  $w_chave_vinc         = $_REQUEST['w_chave_vinc'];
  $w_sq_projeto_rubrica = $_REQUEST['w_sq_projeto_rubrica'];
  $w_imposto            = $_REQUEST['w_imposto'];
  $w_sq_documento       = $_REQUEST['w_sq_documento'];
  $w_sq_tipo_lancamento = $_REQUEST['w_sq_tipo_lancamento'];
  $w_exige_autorizacao  = 'N';
  $w_readonly           = '';
  $w_erro               = '';

  // Recupera os dados do lançamento financeiro de origem
  $sql = new db_getSolicData; $RS_Vinc = $sql->getInstanceOf($dbms,$w_chave_vinc);
  $dados_vinc = explode('|@|',f($RS_Vinc,'dados_solic'));
  $RS_Vinc = $sql->getInstanceOf($dbms,$w_chave_vinc,$dados_vinc[5]);

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
    $w_solic_apoio          = $_REQUEST['w_solic_apoio'];
    $w_data_autorizacao     = $_REQUEST['w_data_autorizacao'];
    $w_texto_autorizacao    = $_REQUEST['w_texto_autorizacao'];
    $w_numero_processo      = $_REQUEST['w_numero_processo'];
    $w_protocolo            = $_REQUEST['w_protocolo'];
    $w_protocolo_nm         = $_REQUEST['w_protocolo_nm'];
    $w_qtd_nota             = $_REQUEST['w_qtd_nota'];
    $w_per_ini              = $_REQUEST['w_per_ini'];
    $w_per_fim              = $_REQUEST['w_per_fim'];
    $w_texto_pagamento      = $_REQUEST['w_texto_pagamento'];
    $w_moeda                = $_REQUEST['w_moeda'];
    $w_solic_apoio          = $_REQUEST['w_solic_apoio'];
    $w_data_autorizacao     = $_REQUEST['w_data_autorizacao'];
    $w_texto_autorizacao    = $_REQUEST['w_texto_autorizacao'];

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
      $w_imposto              = f($RS,'sq_imposto');
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
      $w_valor                = formatNumber(f($RS,'valor'));
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
      $w_sq_projeto_rubrica   = f($RS,'sq_projeto_rubrica');
      $w_moeda                = f($RS,'sq_moeda');
      $w_solic_apoio          = f($RS,'sq_solic_apoio');
      $w_data_autorizacao     = FormataDataEdicao(f($RS,'data_autorizacao'));
      $w_texto_autorizacao    = f($RS,'texto_autorizacao');
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

  if (nvl($w_sq_prop,'')=='') $w_sq_prop = $w_usuario;
  
  if (nvl($w_imposto,'')!='') {
    // Recupera os dados da finalidade informada
    $sql = new db_getImposto;
    $RS_Imposto = $sql->getInstanceOf($dbms, $w_imposto, $w_cliente);
    foreach ($RS_Imposto as $row) {
      $RS_Imposto = $row;
      break;
    }

    // Inicializa o valor da moeda como sendo a mesma do lançamento pai
    if (nvl($w_moeda,'')=='') $w_moeda = f($RS_Vinc,'sq_moeda');
    
    if (nvl(f($RS_Imposto, 'sq_tipo_documento'), '') != '' && nvl($_REQUEST['w_sq_tipo_documento'], '') == '')
      $w_sq_tipo_documento = f($RS_Imposto, 'sq_tipo_documento');
    if (nvl(f($RS_Imposto, 'sq_tipo_lancamento'), '') != '')
      $w_sq_tipo_lancamento = f($RS_Imposto, 'sq_tipo_lancamento');
    if (nvl(f($RS_Imposto, 'sq_forma_pagamento'), '') != '')
      $w_sq_forma_pagamento = f($RS_Imposto, 'sq_forma_pagamento');

    if (f($RS_Imposto, 'tipo_beneficiario') != 3) {
      if (f($RS_Imposto, 'tipo_beneficiario') == 0)
        $w_pessoa = f($RS_Vinc, 'pessoa');              // Igual ao do lançamento financeiro
      elseif (f($RS_Imposto, 'tipo_beneficiario') == 1)
        $w_pessoa = $w_cliente;                        // A própria organização
      elseif (f($RS_Imposto, 'tipo_beneficiario') == 2)
        $w_pessoa = f($RS_Imposto, 'sq_beneficiario');  // Padrão vinculado à finalidade
    }

    if (f($RS_Imposto, 'tipo_vinculo') != 2) {
      if (f($RS_Imposto, 'tipo_vinculo') == 0) {
        // Igual ao do lançamento financeiro
        if (nvl(f($RS_Vinc, 'sq_cc'), '') != '') {
          $w_sq_menu_relac = 'CLASSIF';
          $w_sqcc = f($RS_Vinc, 'sq_cc');
        } else {
          $w_dados_vinc = explode('|@|', f($RS_Vinc, 'dados_pai'));
          $w_sq_menu_relac = $w_dados_vinc[3];
          $w_chave_pai = f($RS_Vinc, 'sq_solic_pai');
        }
      } elseif (f($RS_Imposto, 'tipo_vinculo') == 1) {
        // Padrão vinculado à finalidade
        if (nvl(f($RS_Imposto, 'sq_cc_vinculo'), '') != '') {
          $w_sq_menu_relac = 'CLASSIF';
          $w_sqcc = f($RS_Imposto, 'sq_cc_vinculo');
        } elseif (nvl(f($RS_Imposto, 'sq_solic_vinculo'), '') != '') {
          $sql = new db_getSolicData;
          $RS_Vinc_pai = $sql->getInstanceOf($dbms, f($RS_Imposto, 'sq_solic_vinculo'));
          $dados_vinc = explode('|@|', f($RS_Vinc_pai, 'dados_solic'));
          $w_sq_menu_relac = $dados_vinc[3];
          $w_chave_pai = f($RS_Imposto, 'sq_solic_vinculo');
        }
      }
    }
  }
  
  if (nvl($w_chave_vinc,'')!='') {
    // Se ligado a projeto, recupera rubricas
    $sql = new db_getSolicRubrica; $RS_Rub = $sql->getInstanceOf($dbms,f($RS_Vinc,'sq_solic_pai'),null,'S',null,null,null,null,null,'FOLHA');

    if (count($RS_Rub)>0) {
      if (nvl($w_sq_projeto_rubrica,'')=='' && $O!='I') {
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
      
      // Inicializa o valor da rubrica como sendo a mesma do lançamento pai
      if (nvl($w_sq_projeto_rubrica,'')=='') $w_sq_projeto_rubrica = f($RS_Vinc,'sq_projeto_rubrica');

      if (nvl($w_sq_projeto_rubrica,'')!='') {
        // Recupera dados da rubrica
        $sql = new db_getSolicRubrica; $RS = $sql->getInstanceOf($dbms,f($RS_Vinc,'sq_solic_pai'),$w_sq_projeto_rubrica,null,null,null,null,null,null,null);
        foreach($RS as $row) { 
          $w_exige_autorizacao = f($row,'exige_autorizacao'); 
        }
        
        
        // Verificar fontes de financiamento possíveis. Se apenas uma, atribui direto.
        $sql = new db_getCronograma; $RS_Fonte = $sql->getInstanceOf($dbms,$w_sq_projeto_rubrica,null,null,null,null,'RUBFONTES');
        if (count($RS_Fonte)==0) {
          $w_exibe_ff = false;
        } else {
          $w_exibe_ff = true;
          if (count($RS_Fonte)==1 || nvl($w_solic_apoio,'')!='') {
            foreach($RS_Fonte as $row) { 
              if (nvl($w_solic_apoio,f($row,'sq_solic_apoio'))==f($row,'sq_solic_apoio')) {
                $w_solic_apoio = f($row,'sq_solic_apoio'); 
                break; 
              }
            }
            if (count($RS_Fonte)==1) $w_exibe_ff = false;
          }
        }

        // Inicializa o valor da fonte de financiamento como sendo a mesma do lançamento pai
        if (nvl($w_solic_apoio,'')=='' && nvl($w_troca,'')=='') $w_solic_apoio = f($RS_Vinc,'sq_solic_apoio');
      }
    }
  }

  // Recupera a sigla do tipo do documento para tratar a Nota Fiscal
  if ($w_sq_tipo_documento>'') {
    $sql = new db_getTipoDocumento; $RS2 = $sql->getInstanceOf($dbms,$w_sq_tipo_documento,$w_cliente,null,null);
    foreach ($RS2 as $row) { $w_tipo = f($row,'sigla'); break; }
  } 
  
  // Recupera os documentos do lançamento financeiro
  $sql = new db_getLancamentoDoc; $RS_Docs = $sql->getInstanceOf($dbms,$w_chave_vinc,null,null,null,null,null,null,'DOCS');
  $RS_Docs = SortArray($RS_Docs,'data','asc');
  if (count($RS_Docs)==1) {
    foreach($RS_Docs as $row) {
      $w_sq_documento = f($row,'sq_lancamento_doc');
      $RS_Documento = $row;
    }
  }
  
  // Tenta recuperar os dados do beneficiário
  if (Nvl($w_pessoa,'')!='' || Nvl($_REQUEST['w_cpf'],'')!='' || Nvl($_REQUEST['w_cnpj'],'')!='') {
    // Recupera os dados do proponente
    $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_pessoa,null,((nvl($w_pessoa,'')=='') ? $_REQUEST['w_cpf'] : null),((nvl($w_pessoa,'')=='') ? $_REQUEST['w_cnpj'] : null),null,null,null,null,null,null,null,null,null, null, null, null, null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_cpf           = f($RS,'cpf');
      $w_cnpj          = f($RS,'cnpj');
      $w_tipo_pessoa   = f($RS,'sq_tipo_pessoa');
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
  
  if(nvl($w_sq_menu_relac,0)>0) { $sql = new db_getMenuData; $RS_Relac  = $sql->getInstanceOf($dbms,$w_sq_menu_relac); }

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
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    if ($O=='I') Validate('w_imposto','Finalidade','SELECT',1,1,18,'','0123456789');
    if (count($RS_Docs)>1) {
      Validate('w_sq_documento','Documento','SELECT',1,1,18,'','0123456789');
    }
    if (f($RS_Imposto,'tipo_vinculo')==2) {
      if ($w_exige_relac) Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
      if(nvl($w_sq_menu_relac,'')!='') {
        if ($w_sq_menu_relac=='CLASSIF') {
          Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
        } else {
          Validate('w_chave_pai','Vinculação','SELECT',1,1,18,1,1);
        }
      }
    }
    if (count($RS_Rub)>0) Validate('w_sq_projeto_rubrica','Rubrica', 'SELECT', 1, 1, 18, '', '0123456789');
    if ($w_exibe_ff) Validate('w_solic_apoio','Fonte de financiamento','SELECT',1,1,18,'','0123456789');
    if ($w_exige_autorizacao=='S') {
      Validate('w_data_autorizacao','Data "No objection"','DATA',1,10,10,'','0123456789/');
      Validate('w_texto_autorizacao','Texto "No objection"','1','','2','500','1','0123456789');
    }
    Validate('w_sq_tipo_lancamento','Tipo do '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento'),'SELECT',1,1,18,'','0123456789');
    Validate('w_descricao','Detalhamento','1',1,5,2000,'1','1');
    Validate('w_tipo_pessoa',''.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'Recebimento': 'Pagamento').' para pessoa','SELECT',1,1,18,'','0123456789');   
    Validate('w_sq_forma_pagamento','Forma de recebimento','SELECT',1,1,18,'','0123456789');       
    if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') Validate('w_moeda','Moeda','SELECT',1,1,18,'','0123456789');
    Validate('w_valor','Valor total do documento','VALOR','1',4,18,'','0123456789-.,');
    Validate('w_vencimento','Vencimento','DATA',1,10,10,'','0123456789/');
    if ($w_tipo_pessoa==1) Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
    else                   Validate('w_cnpj','CNPJ','CNPJ','1','18','18','','0123456789/-.');
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
    if ($w_tipo_pessoa==1) Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
    Validate('w_sq_tipo_documento','Tipo do documento', '1', '1', '1', '18', '', '0123456789');
    Validate('w_numero','Número do documento', '1', '1', '1', '30', '1', '1');
    Validate('w_data','Data de emissão', 'DATA', '1', '10', '10', '', '0123456789/');
    ShowHTML('  disAll();');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'')                               BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif (!(strpos('AEV',$O)===false))           BodyOpen('onLoad=\'this.focus()\';');
  else                                           BodyOpen('onLoad=\'document.Form.w_imposto.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
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
    ShowHTML('<INPUT type="hidden" name="w_chave_vinc" value="'.$w_chave_vinc.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0"><b>Pagamento Original</td></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b>Código:</b><br>'.f($RS_Vinc,'codigo_interno'));
    ShowHTML('        <td><b>Finalidade:</b><br>'.f($RS_Vinc,'descricao'));
    ShowHTML('        <td><b>Valor:</b><br>'.formatNumber(f($RS_Vinc,'valor')+f($RS_Vinc,'vl_outros')-f($RS_Vinc,'vl_abatimento')));
    
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  
    ShowHTML('      <tr>'.(($w_chave>'') ? '<td><font size="2"><b>'.$w_codigo_interno.' ('.$w_chave.')</b></font></td>' : ''));
    if ($O=='I') {
      SelecaoImposto('<u>F</u>inalidade:','T', 'Selecione a finalidade do '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').'.', $w_imposto,$w_cliente,'w_imposto',null,'onChange="disAll(); document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_imposto\'; document.Form.submit();"');
    } else {
      SelecaoImposto('<u>F</u>inalidade:','T', 'Selecione a finalidade do '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').'.', $w_imposto,$w_cliente,'w_imposto',null,' disabled ');
      ShowHTML('<INPUT type="hidden" name="w_imposto" value="'.$w_imposto.'">');
    }
    
    if (count($RS_Docs)==1) {
      ShowHTML('<INPUT type="hidden" name="w_sq_documento" value="'.$w_sq_documento.'">');
    } else {
      ShowHTML('      <tr><td colspan="3" title="Selecione o documento ao qual este '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').' é vinculado."><b><b><u>D</u>ocumento:</b><br>');
      ShowHTML('         <SELECT ACCESSKEY="D" CLASS="sts" NAME="w_sq_documento" '.$w_Disabled.'>');
      ShowHTML('            <option value="">---');
      foreach($RS_Docs as $row) {
        ShowHTML('            <option value="'.f($row,'sq_lancamento_doc').'" '.((Nvl($w_sq_documento,'')==f($row,'sq_lancamento_doc')) ? 'SELECTED' : '').'>'.f($row,'nm_tipo_documento').' '.f($row,'numero'));
      }
      ShowHTML('         </select>');
      ShowHTML('      </td></tr>');
    }

    if (nvl(f($RS_Imposto,'tipo_vinculo'),-1)==2) {
      ShowHTML('          <tr valign="top">');
      if ($w_exige_relac) selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);

      if(Nvl($w_sq_menu_relac,'')!='') {
        ShowHTML('          <tr valign="top">');
        if ($w_sq_menu_relac=='CLASSIF') {
          SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null,null,'<BR />',2);
        } else {
          SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_chave_pai,$w_sq_menu_relac,$w_menu,'w_chave_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_tipo_lancamento\'; document.Form.submit();"',$w_chave_pai,'<BR />',2);
        }
      }
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave_pai" value="'.$w_chave_pai.'">');
    }

    if(count($RS_Rub)>0) {
      ShowHTML('      <tr>');
      SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,f($RS_Vinc,'sq_solic_pai'),null,'w_sq_projeto_rubrica','RUBRICAS','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_projeto_rubrica\'; document.Form.submit();"');
      ShowHTML('      </tr>');
      
      // Trata fonte de financiamento
      if ($w_exibe_ff) {
        ShowHTML('      <tr>');
        SelecaoRubricaApoio('<u>F</u>onte de financiamento:','F', 'Selecione a fonte de financiamento que dará suporte ao lançamento.', $w_solic_apoio,$w_sq_projeto_rubrica,'w_solic_apoio','RUBFONTE',null);
        ShowHTML('      </tr>');
      } else {
        ShowHTML('          <INPUT type="hidden" name="w_solic_apoio" value="'.$w_solic_apoio.'">');
      }

      // Trata autorização da despesa
      if ($w_exige_autorizacao=='S') {
        ShowHTML('      <tr><td colspan="3"><b><u>D</u>ata <i>No objection</i>:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_autorizacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_data_autorizacao,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_autorizacao').'</td>');
        ShowHTML('      <tr><td colspan="3"><b><u>T</u>exto <i>No objection</i>:</b><br><textarea '.$w_Disabled.' accesskey="T" name="w_texto_autorizacao" class="sti" ROWS=3 cols=75 title="Texto de autorização da despesa">'.$w_texto_autorizacao.'</TEXTAREA></td>');
      }
    }
    
    ShowHTML('      <tr>');
    SelecaoTipoLancamento('<u>T</u>ipo de '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').':','T','Selecione na lista o tipo de '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').' adequado.',$w_sq_tipo_lancamento,null,$w_cliente,'w_sq_tipo_lancamento',$SG,null,2);
    ShowHTML('      </tr>');
        
    // Se a descrição não foi recebida, formata conforme especificação da ABDI.
    if (nvl($w_descricao,'')==''||$w_descricao==f($RS_Imposto,'descricao')) {
      $w_descricao = f($RS_Imposto,'nome').' referente a '.lower(f($RS_Documento,'nm_tipo_documento')).' '.f($RS_Documento,'numero').', emitido por '.f($RS_Vinc,'nm_pessoa').'.';
    }elseif(strpos($w_descricao,f($RS_Imposto,'nome'))===false){
      $w_descricao = f($RS_Imposto,'nome').' referente a '.lower(f($RS_Documento,'nm_tipo_documento')).' '.f($RS_Documento,'numero').', emitido por '.f($RS_Vinc,'nm_pessoa').'.';
    }
    ShowHTML('      <tr><td colspan=3><b><u>D</u>etalhamento:</b><br><textarea '.$w_Disabled.' accesskey="F" name="w_descricao" class="sti" ROWS=3 cols=75 title="Detalhamento do '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').'.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="3"><table border=0 width="100%">');
    ShowHTML('        <tr valign="top">');
    SelecaoTipoPessoa(''.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'Recebimento': 'Pagamento').' para pessoa:','T','Selecione na lista o tipo de pessoa associada a este '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').'.',$w_tipo_pessoa,$w_cliente,'w_tipo_pessoa',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_forma_pagamento\'; document.Form.submit();"',null);
    SelecaoFormaPagamento('<u>F</u>orma de pagamento:','F','Selecione na lista a forma de pagamento para este '.((substr(f($RS_Menu,'sigla'),2,1)=='R') ? 'recebimento': 'pagamento').'.',$w_sq_forma_pagamento,$SG,'w_sq_forma_pagamento',null);
    if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') {
      ShowHTML('        <tr valign="top">');
      selecaoMoeda('<u>M</u>oeda:','U','Selecione a moeda na relação.',$w_moeda,null,'w_moeda','ATIVO',null);
    }
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    ShowHTML('              <td><b><u>D</u>ata do pagamento:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_vencimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_vencimento,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_vencimento').'</td>');

    ShowHTML('      <tr><td colspan="5" valign="top" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados do Beneficiário</td></td></tr>');
    ShowHTML('      <tr valign="top">');
    if ($w_tipo_pessoa==1) ShowHTML('        <tr><td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);" onBlur="disAll(); document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nome\'; document.Form.submit();">');
    else                   ShowHTML('        <tr><td><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cnpj" VALUE="'.$w_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);" onBlur="disAll(); document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nome\'; document.Form.submit();">');
    ShowHTML('            <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('            <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
    if ($w_tipo_pessoa==1) SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);

    // Recupera o tipo de vínculo
    $sql = new db_getVincKindList; $RS = $sql->getInstanceOf($dbms, $w_cliente, 'S', (($w_tipo_pessoa==1) ? 'Física' : 'Jurídica'), 'Fornecedor', null);
    foreach($RS as $row) { ShowHTML('<INPUT type="hidden" name="w_vinculo" value="'.f($row,'sq_tipo_vinculo').'">'); break; }
    
    ShowHTML('      <tr><td colspan="5" valign="top" align="center" style="border: 1px solid rgb(0,0,0);"><b>Documento de despesa</td></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoDocumento('<u>T</u>ipo:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,null,'w_sq_tipo_documento',null,null);
    ShowHTML('          <td><b><u>N</u>úmero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o número do documento."></td>');
    ShowHTML('          <td><b><u>D</u>ata de emissão:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data do documento.">'.ExibeCalendario('Form','w_data').'</td>');
    //if (Nvl($w_tipo,'-')=='NF') ShowHTML('          <td><b><u>S</u>érie:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_serie" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_serie.'" title="Informado apenas se o documento for NOTA FISCAL. Informe a série ou, se não tiver, digite ÚNICA."></td>');
    //ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_doc" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_doc.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    ShowHTML('<INPUT type="hidden" name="w_valor_doc" value="'.$w_valor_doc.'">');
    
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="STB" type="button" onClick="javascript:window.close(); opener.focus();" name="Botao" value="Abandonar">');
    ShowHTML('          </td>');
    
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
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
        Validate('w_swift_code','Código SWIFT','1','1',1,30,1,1);
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
    ShowHTML('  disAll();');
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
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
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
    $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_chave_aux,null,null,null,null,null,null);
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
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Documentos</TITLE>');
  Estrutura_CSS($w_cliente);
  if (strpos('IAEGCP',$O)!==false) {
    ScriptOpen('JavaScript');
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
    }
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
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
    ShowHTML('      <a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>Número</td>');
    ShowHTML('          <td><b>Emissão</td>');
    //ShowHTML('          <td><b>Série</td>');
    ShowHTML('          <td><b>Valor</td>');
    //ShowHTML('          <td><b>Patrimônio</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_total=0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_tipo_documento').'</td>');
        ShowHTML('        <td align="center">'.f($row,'numero').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'data')).'</td>');
        //ShowHTML('        <td align="center">'.Nvl(f($row,'serie'),'---').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor')).'&nbsp;&nbsp;</td>');
        //ShowHTML('        <td align="center">'.f($row,'nm_patrimonio').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        if (strpos(f($RS_Menu,'sigla'),'VIA')===false) ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        if (strpos(f($RS_Menu,'sigla'),'VIA')===false) ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        if (f($row,'detalha_item')=='S') ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'ITENS&R='.$w_pagina.$par.'&O=&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'- Itens'.'&SG=ITEM').'\',\'Item\',\'toolbar=no,width=780,height=530,top=40,left=20,scrollbars=yes\');" title="Informa os itens do documento.">Itens</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_total=$w_total+f($row,'valor');
      } 
    }
    if ($w_total>0) {
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
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoTipoDocumento('<u>T</u>ipo:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,null,'w_sq_tipo_documento',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_numero\'; document.Form.submit();"');
    ShowHTML('          <td><b><u>N</u>úmero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o número do documento."></td>');
    ShowHTML('          <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data do documento.">'.ExibeCalendario('Form','w_data').'</td>');
    /*
    if (Nvl($w_tipo,'-')=='NF') {
      ShowHTML('          <td><b><u>S</u>érie:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_serie" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_serie.'" title="Informado apenas se o documento for NOTA FISCAL. Informe a série ou, se não tiver, digite ÚNICA."></td>');
    } 
    */
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    //MontaRadioNS('<b>Patrimônio?</b>',$w_patrimonio,'w_patrimonio');
    ShowHTML('<INPUT type="hidden" name="w_patrimonio" value="N">');
    ShowHTML('          </table>');
    if ($w_incid_tributo=='N' && $w_incid_retencao=='N' && substr(f($RS_Menu,'sigla'),2,1)=='D') {
      ShowHTML('<INPUT type="hidden" name="w_tributo" value="'.$w_incid_tributo.'">');
      ShowHTML('<INPUT type="hidden" name="w_retencao" value="'.$w_incid_retencao.'">');
    } else {
      if ($w_incid_retencao=='S' || substr(f($RS_Menu,'sigla'),2,1)=='R') {
        ShowHTML('      <tr>');
        MontaRadioSN('<b>Haverá retenção de tributos para este documento?',Nvl($w_retencao,$w_incid_retencao),'w_retencao');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_retencao" value="'.$w_incid_retencao.'">');
      } 
      if ($w_incid_tributo=='S' || substr(f($RS_Menu,'sigla'),2,1)=='R') {
        ShowHTML('      <tr>');
        MontaRadioSN('<b>Haverá pagamento de tributos para este documento?',Nvl($w_tributo,$w_incid_tributo),'w_tributo');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_tributo" value="N">');
      } 
    } 
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
}
// =========================================================================
// Rotina de documentos com rubricas
// -------------------------------------------------------------------------
function RubricaDoc() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave         = $_REQUEST['w_chave'];
  $w_chave_aux     = $_REQUEST['w_chave_aux'];
  // Recupera os dados do lançamento
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_sq_tipo_documento    = $_REQUEST['w_sq_tipo_documento'];
    $w_numero               = $_REQUEST['w_numero'];
    $w_data                 = $_REQUEST['w_data'];
    $w_serie                = $_REQUEST['w_serie'];
    $w_valor                = $_REQUEST['w_valor_doc'];
    $w_tipo                 = $_REQUEST['w_tipo'];
    $w_sq_rubrica_destino   = $_REQUEST['w_sq_rubrica_destino'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,$w_chave,null,null,null,null,null,null,'DOCS');
    $RS = SortArray($RS,'data','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endereço informado
    $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_chave_aux,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_tipo_documento    = f($RS,'sq_tipo_documento');
    $w_numero               = f($RS,'numero');
    $w_data                 = FormataDataEdicao(f($RS,'data'));
    $w_serie                = f($RS,'serie');
    $w_valor                = formatNumber(f($RS,'valor'));
  }
  if ($w_sq_tipo_documento>'') {
    $sql = new db_getTipoDocumento; $RS2 = $sql->getInstanceOf($dbms,$w_sq_tipo_documento,$w_cliente,null,null);
    foreach ($RS2 as $row) {
      $w_tipo = f($row,'sigla');
    }
  }   
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  if (strpos('IAEGCP',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataValor();
    FormataData();
    SaltaCampo();
    ShowHTML('  function valor(p_indice) {');
    ShowHTML('    if (document.Form["w_sq_projeto_rubrica[]"][p_indice].checked) { ');
    if(f($RS1,'tipo_rubrica')==2) {
      ShowHTML('       document.Form["w_sq_rubrica_destino[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_valor[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_sq_rubrica_destino[]"][p_indice].focus(); ');    
    } else {
      ShowHTML('       document.Form["w_valor[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_valor[]"][p_indice].focus(); ');        
    }
    ShowHTML('    } else {');
    if(f($RS1,'tipo_rubrica')==1)  {
      ShowHTML('       document.Form["w_sq_projeto_rubrica[]"][p_indice].checked=true; ');
      ShowHTML('       document.Form["w_valor[]"][p_indice].focus(); ');
    } else {
      if(f($RS1,'tipo_rubrica')==2) 
        ShowHTML('       document.Form["w_sq_rubrica_destino[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_valor[]"][p_indice].disabled=true; ');
    }
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  function MarcaTodos() {');
    ShowHTML('    if (document.Form["w_sq_projeto_rubrica[]"].value==undefined) ');
    ShowHTML('       for (i=0; i < document.Form["w_sq_projeto_rubrica[]"].length; i++) {');
    ShowHTML('         document.Form["w_sq_projeto_rubrica[]"][i].checked=true;');
    ShowHTML('         document.Form["w_valor[]"][i].disabled=false;');
    ShowHTML('       } ');
    ShowHTML('    else document.Form["w_sq_projeto_rubrica[]"].checked=true;');
    ShowHTML('  }');
    ShowHTML('  function DesmarcaTodos() {');
    ShowHTML('    if (document.Form["w_sq_projeto_rubrica[]"].value==undefined) ');
    ShowHTML('       for (i=0; i < document.Form["w_sq_projeto_rubrica[]"].length; i++) {');
    ShowHTML('         document.Form["w_sq_projeto_rubrica[]"][i].checked=false;');
    ShowHTML('         document.Form["w_valor[]"][i].disabled=true;');
    ShowHTML('       } ');
    ShowHTML('    ');
    ShowHTML('    else document.Form["w_sq_projeto_rubrica[]"].checked=false;');
    ShowHTML('  }');        
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
      Validate('w_valor_doc','Valor total do documento', 'VALOR', '1', 4, 18, '', '0123456789.,-');
      if(f($RS1,'tipo_rubrica')==1) {
        ShowHTML('       for (i=1; i < document.Form["w_sq_projeto_rubrica[]"].length; i++) {');
        ShowHTML('         if(document.Form["w_sq_projeto_rubrica[]"][i].checked==false) {');
        ShowHTML('           alert(\'Para movimentações do tipo dotação inicial, todas as rubricas devem ser marcas e os valores preenchidos!\');');
        ShowHTML('           return false;');
        ShowHTML('         } ');
        ShowHTML('       } ');
      } else {
        ShowHTML('  var i; ');
        ShowHTML('  var w_erro=true; ');
        ShowHTML('  if (theForm["w_sq_projeto_rubrica[]"].value==undefined) {');
        ShowHTML('     for (i=0; i < theForm["w_sq_projeto_rubrica[]"].length; i++) {');
        ShowHTML('       if (theForm["w_sq_projeto_rubrica[]"][i].checked) w_erro=false;');
        ShowHTML('     }');
        ShowHTML('  }');
        ShowHTML('  else {');
        ShowHTML('     if (theForm["w_sq_projeto_rubrica[]"].checked) w_erro=false;');
        ShowHTML('  }');
        ShowHTML('  if (w_erro) {');
        ShowHTML('    alert(\'Você deve informar pelo menos uma rubrica!\'); ');
        ShowHTML('    return false;');
        ShowHTML('  }');
        if(f($RS1,'tipo_rubrica')==2) {
          ShowHTML('  for (i=1; i < theForm["w_sq_projeto_rubrica[]"].length; i++) {');
          ShowHTML('    if((theForm["w_sq_projeto_rubrica[]"][i].checked)&&(theForm["w_sq_rubrica_destino[]"][i].selectedIndex==0)){');
          ShowHTML('      alert(\'Para todas as rubricas selecionadas você deve informar a rubrica de destino do valor!\'); ');
          ShowHTML('      return false;');
          ShowHTML('    }');
          ShowHTML('  }');            
        }
      }
      ShowHTML('  for (i=1; i < theForm["w_sq_projeto_rubrica[]"].length; i++) {');
      ShowHTML('    if((theForm["w_sq_projeto_rubrica[]"][i].checked)&&(theForm["w_valor[]"][i].value==\'\')){');
      ShowHTML('      alert(\'Para todas as rubricas selecionadas você deve informar o valor da mesma!\'); ');
      ShowHTML('      return false;');
      ShowHTML('    }');
      ShowHTML('  }');            
    }
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
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
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td>Projeto:<br><b>'.f($RS1,'nm_projeto').' </b></td>');
  ShowHTML('          <td>Tipo de movimentação:<br><b>'.f($RS1,'nm_tipo_rubrica').' </b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('  <tr><td>&nbsp;');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                         <a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>Número</td>');
    ShowHTML('          <td><b>Emissão</td>');
    // ShowHTML('          <td><b>Série</td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_total=0;
      foreach($RS as $row) {
        $sql = new db_getLancamentoRubrica; $RS2 = $sql->getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null);        
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td rowspan="2">'.f($row,'nm_tipo_documento').'</td>');
        ShowHTML('        <td align="center">'.f($row,'numero').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'data')).'</td>');
        // ShowHTML('        <td align="center">'.Nvl(f($row,'serie'),'---').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor')).'&nbsp;&nbsp;</td>');
        ShowHTML('        <td rowspan="2" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_lancamento_doc').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        ShowHTML('      <tr width="100%" bgcolor="'.$w_TrBgColor.'" align="center"><td colspan=4 align="center">');
        ShowHTML(documentorubrica($RS2,f($RS1,'tipo_rubrica')));
        $w_total=$w_total+f($row,'valor');
      } 
    }
    if ($w_total>0) {
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
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    $sql = new db_getsolicRubrica; 
    if((f($RS1,'tipo_rubrica')==1) || (f($RS1,'tipo_rubrica')==4))  $RS = $sql->getInstanceOf($dbms,f($RS1,'sq_solic_pai'),null,'S',null,null,'N',null,null,null);
    elseif(f($RS1,'tipo_rubrica')==2)  $RS = $sql->getInstanceOf($dbms,f($RS1,'sq_solic_pai'),null,'S',null,null,null,null,null,null);
    elseif(f($RS1,'tipo_rubrica')==3)  $RS = $sql->getInstanceOf($dbms,f($RS1,'sq_solic_pai'),null,'S',null,null,'S',null,null,null);  
    else                               $RS = $sql->getInstanceOf($dbms,f($RS1,'sq_solic_pai'),null,'S',null,null,null,null,null,null);
    $RS = SortArray($RS,'ordena','asc');
    //Rotina de escolha e gravação das parcelas para o lançamento
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_projeto_rubrica[]" value="">');    
    ShowHTML('<INPUT type="hidden" name="w_sq_rubrica_destino[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_valor[]" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');    
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoTipoDocumento('<u>T</u>ipo:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,null,'w_sq_tipo_documento',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_numero\'; document.Form.submit();"');
    ShowHTML('          <td><b><u>N</u>úmero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o número do documento."></td>');
    ShowHTML('          <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data do documento."></td>');
    /*
    if (Nvl($w_tipo,'-')=='NF') {
      ShowHTML('          <td><b><u>S</u>érie:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_serie" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_serie.'" title="Informado apenas se o documento for NOTA FISCAL. Informe a série ou, se não tiver, digite ÚNICA."></td>');
    } 
    */
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_doc" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    ShowHTML('          <tr><td colspan="5" valign="top" align="center">&nbsp;');    
    ShowHTML('          <tr><td colspan="5" valign="top" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Rubricas</td>');    
    ShowHTML('<tr><td align="center" colspan=5>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    if(f($RS1,'tipo_rubrica')==1) {
      ShowHTML('            <td NOWRAP>&nbsp;');
    } else { 
      ShowHTML('            <td NOWRAP><font size="2"><U ID="INICIO" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
      ShowHTML('                                      <U CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');     
    }
    ShowHTML('            <td><b>Código</b></td>');
    ShowHTML('            <td><b>Nome</b></td>');
    ShowHTML('            <td><b>Classificação</b></td>');
    if(f($RS1,'tipo_rubrica')==2) ShowHTML('            <td><b>Destino</b></td>');
    ShowHTML('            <td><b>Valor</b></td>');
    ShowHTML('          </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_cont=0;
      foreach($RS as $row) {
        $sql = new db_getLancamentoRubrica; $RS2 = $sql->getInstanceOf($dbms,null,$w_chave_aux,f($row,'sq_projeto_rubrica'),null);
        foreach($RS2 as $row2){$RS2=$row2;break;}
        $w_cont += 1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;        
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="middle" align="center">');
        if((f($RS2,'sq_rubrica_origem')==f($row,'sq_projeto_rubrica')) || (f($RS1,'tipo_rubrica')==1)) {
          ShowHTML('        <td><input type="checkbox" name="w_sq_projeto_rubrica[]" value="'.f($row,'sq_projeto_rubrica').'" onClick="valor('.$w_cont.');" READONLY    CHECKED>');
        } else {
          ShowHTML('        <td><input type="checkbox" name="w_sq_projeto_rubrica[]" value="'.f($row,'sq_projeto_rubrica').'" onClick="valor('.$w_cont.');">');
        }
        ShowHTML('        <td>'.f($row,'codigo').'</td>');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_cc').'</td>');
        if((f($RS2,'sq_rubrica_origem')==f($row,'sq_projeto_rubrica')) || (f($RS1,'tipo_rubrica')==1)) {
          if(f($RS1,'tipo_rubrica')==2) SelecaoRubrica(null,null, 'Selecione a rubrica de destino.', f($RS2,'sq_rubrica_destino'),f($RS1,'sq_projeto'),f($row,'sq_projeto_rubrica'),'w_sq_rubrica_destino[]',null,null);                  
          ShowHTML('        <td><input type="text" name="w_valor[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.formatNumber(Nvl(f($RS2,'valor'),0)).'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor."></td>');
        } else {
          $w_Disabled = 'disabled';
          if(f($RS1,'tipo_rubrica')==2) SelecaoRubrica(null,null, 'Selecione a rubrica de destino.', f($RS2,'sq_rubrica_destino'),f($RS1,'sq_projeto'),f($row,'sq_projeto_rubrica'),'w_sq_rubrica_destino[]',null,null);        
          $w_Disabled = 'enabled';        
          ShowHTML('        <td><input type="text" disabled name="w_valor[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.formatNumber(Nvl(f($RS2,'valor'),0)).'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor."></td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('<tr><td align="center" colspan="5">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&R='.$R.'&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('  </td>');
    ShowHTML('</FORM>');
    ShowHTML('</tr>');
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
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
}

// =========================================================================
// Rotina de itens do documento
// -------------------------------------------------------------------------
function Itens() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_sq_lancamento_doc  = $_REQUEST['w_sq_lancamento_doc'];
  $w_sq_documento_item  = $_REQUEST['w_sq_documento_item'];
  // Recupera os dados do lançamento/
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_sq_projeto_rubrica   = $_REQUEST['w_sq_projeto_rubrica'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_quantidade           = $_REQUEST['w_quantidade'];
    $w_valor_unitario       = $_REQUEST['w_valor_unitario'];
    $w_ordem                = $_REQUEST['w_ordem'];
    $w_data_cotacao         = $_REQUEST['w_data_cotacao'];
    $w_valor_cotacao        = $_REQUEST['w_valor_cotacao'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getLancamentoItem; $RS = $sql->getInstanceOf($dbms,null,$w_sq_lancamento_doc,null,null,null);
    $RS = SortArray($RS,'ordem','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endereço informado
    $sql = new db_getLancamentoItem; $RS = $sql->getInstanceOf($dbms,$w_sq_documento_item,$w_sq_lancamento_doc,null,null,null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_projeto_rubrica   = f($RS,'sq_projeto_rubrica');
    $w_descricao            = f($RS,'descricao');
    $w_quantidade           = f($RS,'quantidade');
    $w_valor_unitario       = formatNumber(f($RS,'valor_unitario'));
    $w_ordem                = f($RS,'ordem');
    $w_data_cotacao         = formataDataEdicao(f($RS,'data_cotacao'));
    $w_valor_cotacao        = formatNumber(f($RS,'valor_cotacao'),4);
  } 
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Documento - Itens</TITLE>');
  Estrutura_CSS($w_cliente);
  if (strpos('IAE',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataValor();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      if (strpos(f($RS_Menu,'sigla'),'EVENT')!==false || strpos(f($RS_Menu,'sigla'),'REEMB')!==false) {  
        if(nvl(f($RS1,'qtd_rubrica'),0)>0) Validate('w_sq_projeto_rubrica','Rubrica do projeto', '1', '1', '1', '18', '', '0123456789');
      }
      Validate('w_descricao','Descrição', '1', '1', '1', '500', '1', '1');
      Validate('w_ordem','Ordem','1','1','1','18','','1');
      Validate('w_quantidade','Quantidade','1','1','1','18','','0123456789');
      if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
        Validate('w_data_cotacao','Data da cotação', 'DATA', '1', 10, 10, '', '0123456789/');
        Validate('w_valor_cotacao','Valor da cotação', 'VALOR', '1', 6, 18, '', '0123456789.,-');
      }
      Validate('w_valor_unitario','Valor unitário do item', 'VALOR', '1', 4, 18, '', '0123456789.,-');
    }
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    if (strpos(f($RS_Menu,'sigla'),'EVENT')!==false || strpos(f($RS_Menu,'sigla'),'REEMB')!==false) {  
      if(nvl(f($RS1,'qtd_rubrica'),0)>0)  BodyOpen('onLoad=\'document.Form.w_sq_projeto_rubrica.focus()\';');
      else                                BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
    }
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
  ShowHTML('          <td>Forma de pagamento: <b>'.f($RS1,'nm_forma_pagamento').' </b></td>');
  ShowHTML('          <td>Vencimento: <b>'.FormataDataEdicao(f($RS1,'vencimento')).' </b></td>');
  ShowHTML('          <td>Valor do pagamento: <b>'.formatNumber(Nvl(f($RS1,'valor'),0)).' </b></td>');
  $sql = new db_getLancamentoDoc; $RS2 = $sql->getInstanceOf($dbms,$w_chave,$w_sq_lancamento_doc,null,null,null,null,null,null);
  foreach ($RS2 as $row2) {$RS2=$row2; break;}
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td>Documento: <b>'.f($RS2,'numero').' </b></td>');
  ShowHTML('          <td>Tipo: <b>'.f($RS2,'nm_tipo_documento').' </b></td>');
  ShowHTML('          <td>Valor: <b>'.formatNumber(Nvl(f($RS2,'valor'),0)).' </b></td>');
  if(Nvl(f($RS1,'sq_projeto'),0)>0) {
    ShowHTML('      <tr><td colspan="2">Projeto: <b>'.f($RS1,'nm_projeto').'</b></td>');
    if(Nvl(f($RS1,'nm_tipo_rubrica'),'')>'') ShowHTML('          <td>Tipo de movimentação: <b>'.f($RS1,'nm_tipo_rubrica').'</b></td>');
  } else {
    if(Nvl(f($RS1,'nm_tipo_rubrica'),0)>0) ShowHTML('          <tr><td colspan="3">Tipo de movimentação: <b>'.f($RS1,'nm_tipo_rubrica').'</b></td></tr>');
  }
  ShowHTML('    </TABLE>');  
  ShowHTML('</TABLE>');
  ShowHTML('  <tr><td>&nbsp;');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>');
    if (strpos(f($RS_Menu,'sigla'),'VIA')===false) ShowHTML('      <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.$w_sq_lancamento_doc.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;'); 
    ShowHTML('      <a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Ordem</td>');
    if (strpos(f($RS_Menu,'sigla'),'EVENT')!==false || strpos(f($RS_Menu,'sigla'),'REEMB')!==false) {     
      if(nvl(f($RS1,'qtd_rubrica'),0)>0) ShowHTML('          <td><b>Rubrica</td>');
    }
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Qtd.</td>');
    if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
      ShowHTML('          <td><b>Data cotação</td>');
      ShowHTML('          <td><b>Valor cotação</td>');
    }
    ShowHTML('          <td><b>$ Unitário</td>');
    ShowHTML('          <td><b>$ Total</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_total=0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
        if (strpos(f($RS_Menu,'sigla'),'EVENT')!==false || strpos(f($RS_Menu,'sigla'),'REEMB')!==false) {  
           if(nvl(f($RS1,'qtd_rubrica'),0)>0) ShowHTML('        <td align="left">'.nvl(f($row,'codigo_rubrica'),'---').'</td>');
        }
        ShowHTML('        <td align="left">'.f($row,'descricao').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
        if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
          ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'data_cotacao')).'</td>');
          ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_cotacao'),4).'&nbsp;&nbsp;</td>');
        }
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_unitario')).'&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_total')).'&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.f($row,'sq_lancamento_doc').'&w_sq_documento_item='.f($row,'sq_documento_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        if (strpos(f($RS_Menu,'sigla'),'VIA')===false) ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.f($row,'sq_lancamento_doc').'&w_sq_documento_item='.f($row,'sq_documento_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_total += f($row,'valor_total');
      } 
    }
    if ($w_total>0) {
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
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    if (strpos(f($RS_Menu,'sigla'),'VIA')===false) $w_readonly = ''; else $w_readonly = ' READONLY ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_lancamento_doc" value="'.$w_sq_lancamento_doc.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_documento_item" value="'.$w_sq_documento_item.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if (strpos(f($RS_Menu,'sigla'),'EVENT')!==false || strpos(f($RS_Menu,'sigla'),'REEMB')!==false) {  
      if(nvl(f($RS1,'qtd_rubrica'),0)>0) SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,f($RS1,'sq_projeto'),null,'w_sq_projeto_rubrica','RUBRICAS',null);
    }
    ShowHTML('      <tr><td colspan=5><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' '.$w_readonly.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Escreva um texto de descrição para este item do documento.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b><u>O</u>rdem:<br><input accesskey="Q" type="text" name="w_ordem" class="STI" SIZE="4" MAXLENGTH="18" VALUE="'.$w_ordem.'" '.$w_Disabled.' '.$w_readonly.'></td>');
    ShowHTML('          <td><b><u>Q</u>uantidade:<br><input accesskey="Q" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" '.$w_Disabled.''.$w_readonly.' style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);"></td>');
    if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
      ShowHTML('          <td><b>Da<u>t</u>a da cotação:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_data_cotacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.nvl($w_data_cotacao,formataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b><u>V</u>alor cotação:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_cotacao" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_cotacao.'" style="text-align:right;" onKeyDown="FormataValor(this,18,4,event);" title="Informe a cotação da moeda na data de conversão."></td>');
      ShowHTML('<INPUT type="hidden" name="w_sq_projeto_rubrica" value="'.$w_sq_projeto_rubrica.'">');
    }
    ShowHTML('          <td><b><u>V</u>alor unitário:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_unitario" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_unitario.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor unitário do item."></td>');
    ShowHTML('      <tr><td colspan=5 align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_sq_lancamento_doc='.$w_sq_lancamento_doc.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
}

// =========================================================================
// Rotina de notas do pagamento
// -------------------------------------------------------------------------
function Notas() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave              = $_REQUEST['w_chave'];
  
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));

  // Recupera os dados do endereço informado
  $sql = new db_getLancamentoDoc; $RS_Lanc = $sql->getInstanceOf($dbms,$w_chave,null,null,null,null,null,null,'NOTA');
  $RS_Lanc = SortArray($RS_Lanc,'data','asc');
   
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataValor();
  ValidateOpen('Validacao');
  //ShowHTML('  alert(document.Form.length); return false; ');
  ShowHTML('  for (var ind=0;ind < document.Form.length;ind++) { ');
  ShowHTML('    tipo = document.Form.elements[ind].type.toLowerCase();');
  ShowHTML('    if (tipo==\'text\' && !document.Form.elements[ind].disabled) {');
  ShowHTML('      for (idx=1; idx < document.Form[document.Form.elements[ind].name].length; idx++) {');
  Validate('[document.Form.elements[ind].name][idx]','','VALOR','1',4,18,'','0123456789.,-');
  ShowHTML('      }');
  ShowHTML('    } ');
  ShowHTML('  } ');
  ShowHTML('  disAll();');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=\'this.focus()\';');
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=2 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr><td colspan=3><b>'.upper(f($RS_Solic,'nome')).' '.f($RS_Solic,'codigo_interno').' ('.$w_chave.')</b></td>');
  ShowHTML('      <tr><td colspan="3">Finalidade: <b>'.CRLF2BR(f($RS_Solic,'descricao')).'</b></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td>Forma de pagamento: <b>'.f($RS_Solic,'nm_forma_pagamento').' </b></td>');
  ShowHTML('          <td>Vencimento: <b>'.FormataDataEdicao(f($RS_Solic,'vencimento')).' </b></td>');
  ShowHTML('          <td>Valor do pagamento: <b>'.formatNumber(Nvl(f($RS_Solic,'valor'),0)).' </b></td>');
  ShowHTML('    </TABLE>');  
  ShowHTML('    </TABLE>');  
  ShowHTML('<tr><td>&nbsp;');
  ShowHTML('      <tr><td bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
  ShowHTML('        ATENÇÃO:<ul>');
  ShowHTML('        <li>Se necessário, altere os valores correspondentes a valor inicial, excedente e reajuste.');
  ShowHTML('        <li>Clique no botão "Gravar" para confirmar as alterações.');
  ShowHTML('        <li>Após gravar os dados, clique no botão "Fechar" para voltar à listagem atualizada de lançamentos.');
  ShowHTML('        <li>O valor total do lançamento será recalculado em função dos valores constantes da tabela.');
  ShowHTML('        </ul></b></font></td>');
  ShowHTML('      </tr>');
  ShowHTML('<tr><td>&nbsp;');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.'Notas',$O);
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_sq_acordo_nota[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_sq_lancamento_doc[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_sq_tipo_documento[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_numero[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_data[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_inicial[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_excedente[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_reajuste[]" value="">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('  <table border="1">');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td rowspan=2><b>Nota</td>');
  ShowHTML('          <td colspan=3><b>Valores da Parcela</td>');
  ShowHTML('          <td colspan=3><b>Valores do Lançamento</td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td width="1%" nowrap><b>Inicial</td>');
  ShowHTML('          <td width="1%" nowrap><b>Excedente</td>');
  ShowHTML('          <td width="1%" nowrap><b>Reajuste</td>');
  ShowHTML('          <td width="1%" nowrap><b>Inicial</td>');
  ShowHTML('          <td width="1%" nowrap><b>Excedente</td>');
  ShowHTML('          <td width="1%" nowrap><b>Reajuste</td>');
  ShowHTML('        </tr>');

  foreach($RS_Lanc as $row) {
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_nota[]" value="'.f($row,'sq_acordo_nota').'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_lancamento_doc[]" value="'.f($row,'sq_lancamento_doc').'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_documento[]" value="'.f($row,'sq_tipo_documento').'">');
    ShowHTML('<INPUT type="hidden" name="w_numero[]" value="'.f($row,'numero').'">');
    ShowHTML('<INPUT type="hidden" name="w_data[]" value="'.FormataDataEdicao(f($row,'data')).'">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td align="center">'.f($row,'sg_nota').' '.f($row,'numero_nota').'</td>');
    ShowHTML('        <td align="right">'.FormatNumber(Nvl(f($row,'parcela_ini'),0)).'</td>');
    ShowHTML('        <td align="right">'.FormatNumber(Nvl(f($row,'parcela_exc'),0)).'</td>');
    ShowHTML('        <td align="right">'.FormatNumber(Nvl(f($row,'parcela_rea'),0)).'</td>');
    if (f($row,'abrange_inicial')=='S') {
      ShowHTML('                     <td align="center"><input '.$w_Disabled.' type="text" name="w_inicial[]" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.FormatNumber(Nvl(f($row,'valor_inicial'),0),2).'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do documento correspondente ao valor original da parcela."></td>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_inicial[]" value="'.formatNumber(0,2).'">');
      ShowHTML('          <td>&nbsp;</td>');
    }
    if (f($row,'abrange_acrescimo')=='S') {
      ShowHTML('                     <td align="center"><input '.$w_Disabled.' type="text" name="w_excedente[]" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.FormatNumber(Nvl(f($row,'valor_excedente'),0),2).'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do documento correspondente ao valor original da parcela."></td>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_excedente[]" value="'.formatNumber(0,2).'">');
      ShowHTML('          <td>&nbsp;</td>');
    }
    if (f($row,'abrange_reajuste')=='S') {
      ShowHTML('                     <td align="center"><input '.$w_Disabled.' type="text" name="w_reajuste[]" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.FormatNumber(Nvl(f($row,'valor_reajuste'),0),2).'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do documento correspondente ao valor original da parcela."></td>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_reajuste[]" value="'.formatNumber(0,2).'">');
      ShowHTML('          <td>&nbsp;</td>');
    }
  }
  ShowHTML('  </table>');
  ShowHTML('      <tr><td colspan=2 align="center"><hr>');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="window.close(); opener.location.reload(); opener.focus();" name="Botao" value="Fechar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
} 

// =========================================================================
// Rotina de seleção de tipos de lançamento
// -------------------------------------------------------------------------
function TipoLancamento() {
  extract($GLOBALS);

  if ($w_troca>'') {
    // Se for recarga da página)
    $w_sq_acordo_parcela    = $_REQUEST['w_sq_acordo_parcela'];
    $w_sq_tipo_lancamento   = $_REQUEST['w_sq_tipo_lancamento'];
    $w_valor                = $_REQUEST['w_valor'];
  } elseif ($O=='I') {
    // Recupera todos os serviços do módulo financeiro
    $sql = new db_getMenuList; $RS = $sql->getInstanceOf($dbms,$w_cliente,'X',null,'FN');
    $RS = SortArray($RS,'ordem','asc','nome','asc');
  } 
  Cabecalho();
  head();
  if (strpos('IAP',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td><P>Selecione o tipo de lançamento que deseja incluir, clicando sobre seu nome: </p>');
  ShowHTML('<tr><td><ul>');
  ShowHTML('  <LI><P><a class="ss" HREF="'.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'">Voltar para tela anterior</a></p>');
  if ($O=='I') {
    //Rotina de escolha do tipo de lançamento a ser incluído
    
    foreach($RS as $row) {
      if (f($row,'sigla')=='FNDVIA') continue;
      $rotina   = 'geral';
      $operacao = 'I';
      $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,f($row,'sg_servico'));
      if (count($RS1)>0) { 
        // Se tiver submenu, recupera o link para o cadastramento inicial (de menor ordem)
        $RS1 = SortArray($RS1,'ordem','asc');
        foreach($RS1 as $row1) { $reg = $row1; break; }
      } else {
        $reg = $row;
      }
      if (strpos(f($reg,'sigla'),'CONT')!==false) {
        $rotina   = 'BuscaParcela';
        $operacao = 'P';
      }
      ShowHTML('  <li><a class="HL" HREF="'.montaURL_JS($w_dir,substr(f($reg,'link'),0,strpos(f($reg,'link'),'par=')+4).$rotina.'&O='.$operacao.'&w_menu='.f($row,'sq_menu').'&P1='.$P1.'&P2='.$P2.'&P3='.f($reg,'p3').'&P4='.f($reg,'p4').'&TP='.removeTP($TP).' - '.f($reg,'nome').'&SG='.f($reg,'sigla').MontaFiltro('GET')).'">'.f($reg,'nome').'</a>');
    }
  }
  ShowHTML('</ul></table>');
  ShowHTML('</center>');
  Rodape();
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
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
  // Chama a rotina de visualização dos dados do lançamento, na opção 'Listagem'
  ShowHTML(VisualLancamento($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
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
  $w_retorno    = $_REQUEST['w_retorno'];
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
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
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
  ShowHTML('<INPUT type="hidden" name="w_retorno" value="'.$w_retorno.'">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.'inicial&O=L&&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
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
    ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Enviar">');
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
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
        ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
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
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
  $w_sq_tipo_lancamento = nvl($w_sq_tipo_lancamento,f($RS_Solic,'sq_tipo_lancamento'));
  $w_inicio             = FormataDataEdicao(time());

  $sql = new db_getContaBancoList; $RS_Conta = $sql->getInstanceOf($dbms,$w_cliente,null,'FINANCEIRO');
  if (count($RS_Conta)>1) { 
    $w_exige_conta = true; 
  } else {
    $w_exige_conta = false;
    if (count($RS_Conta)==1) {
      foreach($RS_Conta as $row) $RS_Conta = $row;
      $w_conta = f($RS_Conta,'sq_pessoa_conta');
    }
  }
  
  $RS_Rub = array();
  
  // Se reembolso, recupera a rubrica apenas do primeiro item do primeiro documento pois são todos iguais
  if (strpos('REEMB',substr($SG,3))!==false) {
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
    Validate('w_sq_tipo_lancamento','Tipo de lançamento', 'SELECT', 1, 1, 18, '', '0123456789');
    if (count($RS_Rub)>0) Validate('w_sq_projeto_rubrica','Rubrica', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_quitacao','Data do pagamento', 'DATA', 1, 10, 10, '', '0123456789/');
    Validate('w_valor_real','Valor real','VALOR','1', 4, 18, '', '0123456789.,-');
    if (w_sg_forma_pagamento=='DEPOSITO') Validate('w_codigo_deposito','Código do depósito', '1', '1', 1, 50, '1', '1');
    if ($w_exige_conta) Validate('w_conta','Conta bancária', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_observacao','Observação', '', '', '1', '500', '1', '1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
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
    ShowHTML('      <tr><td colspan="4" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
    ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    ShowHTML('      <tr>');
    SelecaoTipoLancamento('<u>T</u>ipo de lancamento:','T','Selecione na lista o tipo de lançamento adequado.',$w_sq_tipo_lancamento,null,$w_cliente,'w_sq_tipo_lancamento',substr($SG,0,3).'VINC',null,2);
    ShowHTML('      </tr>');
    if(count($RS_Rub)>0) {
      ShowHTML('      <tr>');
      SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,f($RS_Solic,'sq_solic_pai'),null,'w_sq_projeto_rubrica','RUBRICAS',null);
      ShowHTML('      </tr>');
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>D</u>ata do '.((substr($SG,2,1)=='R') ? 'recebimento' : 'pagamento').':</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_quitacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_quitacao,$w_inicio).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de pagamento deste lançamento.">'.ExibeCalendario('Form','w_quitacao').'</td>');
    ShowHTML('        <td><b>Valo<u>r</u> real:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor_real" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_real.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor real do lançamento."></td>');
    if ($w_sg_forma_pagamento=='DEPOSITO') {
      ShowHTML('        <td><b><u>C</u>ódigo do depósito:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo_deposito" class="sti" SIZE="20" MAXLENGTH="50" VALUE="'.$w_codigo_deposito.'" title="Informe o código do depósito identificado."></td>');
    }
    if ($w_exige_conta) {
      SelecaoContaBAnco('C<u>o</u>nta bancária:','O','Selecione a conta bancária envolvida no lançamento.',$w_conta,null,'w_conta',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_conta" value="'.$w_conta.'">');
    }
    ShowHTML('      <tr><td colspan="4"><b>Obs<u>e</u>rvação:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_observacao" class="sti" ROWS=5 cols=75 title="Descreva o quanto a demanda atendeu aos resultados esperados.">'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="4"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
    ShowHTML('      <tr colspan="4"><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
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
    $v_html.=chr(13).'        <td><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha4\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'cd_rubrica_origem').' - '.f($row,'nm_rubrica_origem').'</A>&nbsp</td>';
    if($l_tipo==2) $v_html.=chr(13).'        <td>'.f($row,'cd_rubrica_destino').' - '.f($row,'nm_rubrica_destino').'</td>';
    $v_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor')).'</td>';
    $v_html.=chr(13).'      </tr>';
    $w_total += f($row,'valor');
  } 
  if ($w_total>0) {
    $v_html.=chr(13).'      <tr valign="top">';
    if($l_tipo==2) 
      $v_html.=chr(13).'        <td colspan=2 align="right"><b>Total</b></td>';
    else
      $v_html.=chr(13).'        <td align="right"><b>Total</b></td>';
    $v_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';  
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
        ShowHTML('      <td align="right">-'.formatNumber(f($row,'valor')).'</td>');
      else
        ShowHTML('      <td align="right">'.formatNumber(f($row,'valor')).'</td>');
      if(nvl(f($row,'sg_tramite'),'')=='AT') {
        if(f($row,'tipo_rubrica')==5)
          ShowHTML('      <td align="right">-'.formatNumber(f($row,'valor')).'</td>');
        else
          ShowHTML('      <td align="right">'.formatNumber(f($row,'valor')).'</td>');
      } else {
        ShowHTML('      <td align="right">'.formatNumber(0).'</td>');
      }
      ShowHTML('      <td><A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&O=L&w_chave='.f($row,'sq_lancamento').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG='.f($row,'sg_lancamento_menu').MontaFiltro('GET').'" title="Exibe as informações do lançamento"> '.f($row,'cd_lancamento').' - '.f($row,'nm_lancamento').'</a>');
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
      ShowHTML('        <td align="right"><b>'.formatNumber($w_total_previsto).'</b></td>');
      ShowHTML('        <td align="right"><b>'.formatNumber($w_total_real).'</b></td>');
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  $w_retorno    = $_REQUEST['w_retorno'];
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  if (strpos($SG,'EVENT')!==false || strpos($SG,'REEMB')!==false || $SG=='FNDVIA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {

      if ($O=='I') {
        // Verifica se já existe lançamento financeiro para o imposto e documento informados
        $sql = new db_getImpostoDoc; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave_vinc'],$_REQUEST['w_sq_documento'],null);
        if (count($RS)>0) {
          $w_erro = false;
          foreach($RS as $row) {
            if (f($row,'sq_imposto')==$_REQUEST['w_imposto']) {
              $w_erro = true;
            }
          }
          if ($w_erro) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe lançamento financeiro para a finalidade informada!");');
            ShowHTML('  window.close(); opener.focus();');
            ScriptClose();
            exit();
          }
        }
      }
      $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_menu'],
          $_REQUEST['w_sq_unidade'],$_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_sqcc'],
          $_REQUEST['w_descricao'],$_REQUEST['w_vencimento'],Nvl($_REQUEST['w_valor'],0),$_REQUEST['w_data_hora'],
          $_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],$_REQUEST['w_chave_pai'],
          $_REQUEST['w_sq_acordo_parcela'],$_REQUEST['w_observacao'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
          Nvl($_REQUEST['w_sq_forma_pagamento'],''),$_REQUEST['w_tipo_pessoa'],$_REQUEST['w_forma_atual'],
          $_REQUEST['w_vencimento_atual'],$_REQUEST['w_tipo_rubrica'],nvl($_REQUEST['w_protocolo'],$_REQUEST['w_numero_processo']),
          $_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],$_REQUEST['w_texto_pagamento'],$_REQUEST['w_solic_vinculo'],
          $_REQUEST['w_sq_projeto_rubrica'],$_REQUEST['w_solic_apoio'],$_REQUEST['w_data_autorizacao'],
          $_REQUEST['w_texto_autorizacao'],$_REQUEST['w_moeda'],&$w_chave_nova,&$w_codigo);

      
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

        //Grava os dados da vinculação
        $SQL = new dml_putImpostoDoc; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_sq_documento'], $_REQUEST['w_imposto'],null,
          $w_chave_nova,null,null,null,$_REQUEST['w_valor'],0,0,$_REQUEST['w_vencimento'],$_REQUEST['w_vencimento']);
      
        // Recupera os trâmites de cadastramento inicial e de execução 
        $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_menu,null, null,'S');
        $RS = SortArray($RS,'ordem','asc');
        foreach ($RS as $row) {
          if (f($row,'sigla')=='CI') $w_ci = f($row,'sq_siw_tramite');
          elseif (f($row,'sigla')=='EE') $w_ee = f($row,'sq_siw_tramite');
        }
          
        // Grava versão da solicitação
        $w_html = VisualLancamento($w_chave_nova,'L',$w_usuario,'2','1');
        CriaBaseLine($w_chave_nova,$w_html,f($RS_Menu,'nome'),$w_ee);
          
        // Envia a solicitação para execução   
        $SQL = new dml_putLancamentoEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$w_chave_nova,$w_usuario,$w_ci,
                  $w_ee,'N',null,$w_usuario,'Envio automático de lançamento financeiro.',null,null,null,null);
      }

      ScriptOpen('JavaScript');
      if ($w_retorno=='Volta') {
        // Volta para o módulo tesouraria
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        // Fecha a janela atual
        ShowHTML('  window.close(); opener.location.reload(); opener.focus();');
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
      ShowHTML('  window.close();');
      ShowHTML('  opener.location.reload();');
      ShowHTML('  opener.focus();');
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
      $SQL = new dml_putLancamentoDoc; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_sq_tipo_documento'],
        $_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_serie'],$_REQUEST['w_valor'],$_REQUEST['w_patrimonio'],$_REQUEST['w_retencao'],
        $_REQUEST['w_tributo'],null,null,null,null,&$w_chave_nova);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG=='NOTA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putLancamentoDoc; 
      for ($i=0; $i<=count($_POST['w_sq_acordo_nota'])-1; $i=$i+1) {
        if (Nvl($_REQUEST['w_sq_acordo_nota'][$i],'')>'') {
          $SQL->getInstanceOf($dbms,'A',$_REQUEST['w_chave'],$_REQUEST['w_sq_lancamento_doc'][$i],$_REQUEST['w_sq_tipo_documento'][$i],
             $_REQUEST['w_numero'][$i],$_REQUEST['w_data'][$i],null,null,
             'N','N','N',$_REQUEST['w_sq_acordo_nota'][$i],$_REQUEST['w_inicial'][$i],$_REQUEST['w_excedente'][$i],$_REQUEST['w_reajuste'][$i],&$w_chave_nova);
        } 
      }
      if ($i>0) {
        // Atualiza o valor de SIW_SOLICITACAO a partir da soma dos valores das notas ligadas ao lançamento
        $SQL = new dml_putLancamentoDoc; $SQL->getInstanceOf($dbms,'V',$_REQUEST['w_chave'],null,null,null,null,null,null,null,
            null,null,null,null,null,null,&$w_chave_nova);
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG=='RUBRICADOC') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
     $SQL = new dml_putLancamentoDoc; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_sq_tipo_documento'],
        $_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_serie'],$_REQUEST['w_valor_doc'],'N','N','N',null,null,null,null,&$w_chave_nova);
      $SQL = new dml_putLancamentoRubrica; 
      $SQL->getInstanceOf($dbms,'E',$w_chave_nova,null,null,null);
      for ($i=0; $i<=count($_POST['w_sq_projeto_rubrica'])-1; $i=$i+1) {
        if (Nvl($_REQUEST['w_sq_projeto_rubrica'][$i],'')>'') {
          $SQL->getInstanceOf($dbms,'I',$w_chave_nova,$_REQUEST['w_sq_projeto_rubrica'][$i],$_REQUEST['w_sq_rubrica_destino'][$i],$_REQUEST['w_valor'][$i]);
        } 
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }     
    // Inclusão, Alteração e exclusão de documentos relativos a um lançamento.
  } elseif ($SG=='ITEM') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putLancamentoItem; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_sq_lancamento_doc'],$_REQUEST['w_sq_documento_item'],
        $_REQUEST['w_sq_projeto_rubrica'],$_REQUEST['w_descricao'],$_REQUEST['w_quantidade'],$_REQUEST['w_valor_unitario'],
        $_REQUEST['w_ordem'],$_REQUEST['w_data_cotacao'],$_REQUEST['w_valor_cotacao'],null);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&w_sq_lancamento_doc='.$_REQUEST['w_sq_lancamento_doc'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }     
    // Envio de lançamentos.
  } elseif (!(strpos($SG,'ENVIO')===false)) {
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
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
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
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
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
      // Se for envio da fase de cadastramento, remonta o menu principal
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
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
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou este contrato para outra fase!\');');
        ScriptClose();
        retornaFormulario();
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
          $SQL = new dml_putFinanceiroConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_quitacao'],
            $_REQUEST['w_valor_real'],$_REQUEST['w_codigo_deposito'],$_REQUEST['w_conta'],$_REQUEST['w_sq_tipo_lancamento'],
            $_REQUEST['w_sq_projeto_rubrica'],
            $_REQUEST['w_observacao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
      } 
      // Volta para a listagem
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif ($SG=='FNDCONT' || $SG=='FNRCONT') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      if ($O=='I') {
        $sql1 = new db_getSolicData;
        $SQL1 = new dml_putFinanceiroGeral;
        $sql2 = new db_getBenef;   
        $sql3 = new db_getAcordoNota; 
        $SQL2 = new dml_putLancamentoOutra; 
        $SQL3 = new dml_putLancamentoDoc; 
        for ($i=0; $i<=count($_POST['w_sq_acordo_parcela'])-1; $i=$i+1) {
          if ($_REQUEST['w_sq_acordo_parcela'][$i]>'') {
            $RS1 = $sql1->getInstanceOf($dbms,$_REQUEST['w_chave_pai'][$i],'GCCAD');
            $w_tipo = '';
            if(Nvl(f($RS1,'qtd_rubrica'),0)>0) $w_tipo=5;
            $SQL1->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$w_menu,$_REQUEST['w_sq_unidade'],
                $_REQUEST['w_solicitante'][$i],$w_usuario,$_REQUEST['w_sqcc'][$i],$_REQUEST['w_descricao'][$i],$_REQUEST['w_vencimento'][$i],
                Nvl($_REQUEST['w_valor'][$i],0),$_REQUEST['w_data_hora'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],
                $_REQUEST['w_chave_pai'][$i],$_REQUEST['w_sq_acordo_parcela'][$i],$_REQUEST['w_observacao'],$_REQUEST['w_sq_tipo_lancamento'][$i],
                $_REQUEST['w_sq_forma_pagamento'][$i],$_REQUEST['w_tipo_pessoa'][$i],$_REQUEST['w_forma_atual'][$i],null,
                $w_tipo,nvl($_REQUEST['w_protocolo'],$_REQUEST['w_numero_processo']),$_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],
                $_REQUEST['w_texto_pagamento'],f($RS1,'sq_solic_pai'),null,$_REQUEST['w_solic_apoio'],$_REQUEST['w_data_autorizacao'],
                $_REQUEST['w_texto_autorizacao'],$_REQUEST['w_moeda'],&$w_chave_nova,&$w_codigo);
            //Recupera os dados da pessoa associada ao lançamento
            $RS = $sql2->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_outra_parte'][$i],null,null,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
            foreach ($RS as $row) {$RS=$row; break;}
            //Grava os dados da pessoa
            $SQL2->getInstanceOf($dbms,$O,$SG,$w_chave_nova,$w_cliente,$_REQUEST['w_outra_parte'][$i],f($RS,'cpf'),f($RS,'cnpj'),
                null,null,null,null,null,null,null,null,null,null,f($RS,'logradouro'),f($RS,'complemento'),f($RS,'bairro'),f($RS,'sq_cidade'),
                f($RS,'cep'),f($RS,'ddd'),f($RS,'nr_telefone'),f($RS,'nr_fax'),f($RS,'nr_celular'),f($RS,'email'),null,null,null,null,null,null,
                null,null,null,null,null,null,null,null);
            $RS_Nota = $sql3->getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_sq_acordo_parcela'][$i],null,null,null,null,null,'PARCELAS');
            foreach($RS_Nota as $row1) {
              $SQL3->getInstanceOf($dbms,$O,$w_chave_nova,null,f($row1,'sq_tipo_documento'),
                 f($row1,'numero'),FormataDataEdicao(f($row1,'data')),null,formatNumber(f($row1,'valor_total'),2),
                 'N','N','N',f($row1,'sq_acordo_nota'),formatNumber(f($row1,'inicial_parc'),2),formatNumber(f($row1,'excedente_parc'),2),formatNumber(f($row,'reajuste_parc'),2),null);
            }
          }
        } 
      } else {
        $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_menu'],$_REQUEST['w_sq_unidade'],$_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],
            $_REQUEST['w_sqcc'],$_REQUEST['w_descricao'],$_REQUEST['w_vencimento'],Nvl($_REQUEST['w_valor'],0),$_REQUEST['w_data_hora'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],
            $_REQUEST['w_cidade'],$_REQUEST['w_chave_pai'],$_REQUEST['w_sq_acordo_parcela'],$_REQUEST['w_observacao'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),Nvl($_REQUEST['w_sq_forma_pagamento'],''),
            $_REQUEST['w_tipo_pessoa'],$_REQUEST['w_forma_atual'],$_REQUEST['w_vencimento_atual'],null,nvl($_REQUEST['w_protocolo'],$_REQUEST['w_numero_processo']),
            $_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],$_REQUEST['w_texto_pagamento'],$_REQUEST['w_solic_vinculo'],$_REQUEST['w_sq_projeto_rubrica'],
            $_REQUEST['w_solic_apoio'],$_REQUEST['w_data_autorizacao'],$_REQUEST['w_texto_autorizacao'],$_REQUEST['w_moeda'],&$w_chave_nova,&$w_codigo);
      } 
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
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
    case 'RUBRICADOC':      RubricaDoc();       break;    
    case 'ITENS':           Itens();            break;
    case 'NOTAS':           Notas();            break;
    case 'TIPOLANCAMENTO':  TipoLancamento();   break;
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