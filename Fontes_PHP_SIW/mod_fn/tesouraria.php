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
$P2         = nvl($_REQUEST['P2'],3);
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
$p_semPais      = $_REQUEST['p_semPais'];

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
$w_TP       = RetornaTitulo($TP, $O);

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

// Verifica se o cliente tem o módulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_mod_pa='S'; else $w_mod_pa='N';

// Verifica se deve a tela de tesouraria deve ser exibida de forma resumida
// O teste baseia-se no cliente. Lembrar de criar parâmetro no módulo financeiro
$w_visao_completa = true;
if ($_SESSION['P_CLIENTE']==10135) $w_visao_completa = false;

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

Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_SG = $SG;
  $w_tipo = $_REQUEST['w_tipo'];
  if ($P2==3 && strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')===false) {
    // Tela de histórico deve ter filtro
    $RS = array();
  } else {
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $sql = new db_getSolicFN; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$w_SG),3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, $p_sq_acao_ppa, $p_sq_orprior, $p_empenho);
    } else {
      // Ajusta parâmetro de busca se foi indicada recuperação apenas de lançamentos sem valores por país
      if ($p_semPais=='S') $w_SG = 'FNSEMPAIS';
      
      $sql = new db_getSolicFN; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$w_SG),$P2,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, $p_sq_acao_ppa, $p_sq_orprior, $p_empenho);
    }
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'dt_pagamento','asc','ord_codigo_interno','asc');
    } else {
      $RS = SortArray($RS,'dt_pagamento','asc','ord_codigo_interno','asc');
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
    //Validate('p_uf','Projeto','','','2','90','1','1');    
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
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));

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
    SelecaoTipoLancamento('<u>T</u>ipo de lancamento:','T','Selecione na lista o tipo de lançamento adequado.',$p_sq_orprior,null,$w_cliente,'p_sq_orprior',f($RS_Menu_Origem,'sigla'),null,2);
    if ($P2==3) MontaRadioSN('<b>Descarta lançamentos encerrados?',$p_ativo,'p_ativo',null,null,null,1);
    ShowHTML('      <tr valign="top">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      SelecaoCC('C<u>l</u>assificação:','C','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC',null,3);
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_sq_acao_ppa" VALUE="'.$p_sq_acao_ppa.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    ShowHTML('          <td><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_empenho" VALUE="'.$p_empenho.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
    MontaRadioNS('<b>Exibe apenas lançamentos sem valores por país?',$p_semPais,'p_semPais',null,null,null,1);
    ShowHTML('      <tr valign="top">');
    ShowHTML('      <tr valign="top">');
    $sql = new db_getLinkData; $l_rs = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto na relação.',$p_projeto,$w_usuario,f($l_rs,'sq_menu'),null,null,null,'p_projeto','PJLIST',null,2,3);
    //ShowHTML('          <td><b><U>P</U>rojeto:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="sti" type="text" name="p_uf" size="18" maxlength="18" value="'.$p_uf.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>V</U>inculação:<br><INPUT ACCESSKEY="V" '.$w_Disabled.' class="sti" type="text" name="p_atraso" size="18" maxlength="18" value="'.$p_atraso.'"></td>');
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
      $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Código','ord_codigo_interno').'</td>');
      if ($w_segmento=='Público' || $w_mod_pa=='S') {
        $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Protocolo','protocolo').'</font></td>');
      }
      if ($w_visao_completa && $P2==1) {
        $colspan++; ShowHTML('          <td rowspan="2"><b>'.LinkOrdena((($P1==0) ? 'Vencim.' : 'Pagam.'),'dt_pagamento').'</td>');
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
        //ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Forma','sg_forma_pagamento').'</td>');
        //ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('BCO','cd_banco').'</td>');
        //ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('AGE','cd_agencia').'</td>');
        //ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('C/C','numero_conta').'</td>');
      }
      if ($P2>1) ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Conta Débito','conta_debito').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td class="remover" rowspan="2"><b>Operações</td>');
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
        //ShowHTML('          <td rowspan="2"><b>Forma</td>');
        //ShowHTML('          <td rowspan="2"><b>BCO</td>');
        //ShowHTML('          <td rowspan="2"><b>AGE</td>');
        //ShowHTML('          <td rowspan="2"><b>C/C</td>');
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
      $w_parcial = array();
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
        if     (substr(f($row,'sigla'),2,1)=='R' || f($row,'sigla')=='FNAAPLICA') $w_valor == $w_valor;
        elseif (substr(f($row,'sigla'),2,1)=='D') $w_valor = -1 * $w_valor;
        $w_parcial[f($row,'sb_moeda')] = nvl($w_parcial[f($row,'sb_moeda')],0) + $w_valor;

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

          //if (substr(f($row,'sigla'),2,1)=='R') {
          //  ShowHTML('        <td colspan=4>&nbsp;</td>');
          //} else {
          //  ShowHTML('        <td'.((Nvl(f($row,'sg_forma_pagamento'),'')!='') ? ' title="'.f($row,'nm_forma_pagamento').'"' : '').'>'.Nvl(f($row,'sg_forma_pagamento'),'&nbsp;').'</td>');
          //  ShowHTML('        <td align="center"'.((nvl(f($row,'cd_banco'),'')!='') ? ' title="'.f($row,'nm_banco').'"' : '').'>'.Nvl(f($row,'cd_banco'),'&nbsp;').'</td>');
          //  ShowHTML('        <td align="center"'.((nvl(f($row,'cd_agencia'),'')!='') ? ' title="'.f($row,'nm_agencia').'"' : '').'>'.Nvl(f($row,'cd_agencia'),'&nbsp;').'</td>');
          //  ShowHTML('        <td>'.Nvl(f($row,'numero_conta'),'&nbsp;').'</td>');
          //}
        }
        if ($P2>1) {
          if (nvl(f($row,'conta_debito'),'')!='') {
            ShowHTML('        <td>'.f($row,'nm_banco_debito').' '.f($row,'conta_debito').((nvl(f($row,'sg_moeda_cc'),'')=='') ? '' : ' ('.f($row,'sg_moeda_cc').')').'</td>');
          } else {
            ShowHTML('        <td>&nbsp;</td>');
          }
        }

        if ($w_tipo!='WORD') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          switch (f($row,'sigla')) {
            case 'FNDREEMB':  $w_destino = 'reembolso';     $w_acao = 'Pagar';      break;
            case 'FNDFUNDO':  $w_destino = 'pagfundo';      $w_acao = '';           break;
            case 'FNDFIXO':   $w_destino = 'fundofixo';     $w_acao = 'Encerrar';   break;
            case 'FNAAPLICA': $w_destino = 'aplicacao';     $w_acao = '';           break;
            case 'FNATRANSF': $w_destino = 'transferencia'; $w_acao = '';           break;
            case 'FNDTARIFA': $w_destino = 'tarifa';        $w_acao = '';           break;
            default: 
              $w_destino = 'lancamento';
              $w_acao    = ((substr(f($row,'sigla'),2,1)=='R') ? 'Receber' : 'Pagar');
          }

          if (strpos('FNAAPLICA,FNATRANSF,FNDTARIFA',f($row,'sigla'))===false) {
            // Aplicações, transferências e tarifas não têm rotinas de registro de anotação
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Registra anotações para o lançamento, sem enviá-lo.">AN</A>&nbsp');
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
              if (substr(f($row,'sigla'),0,3)!='FNA' && f($row,'sigla')!='FNDTARIFA' && f($row,'sigla')!='FNDREEMB' && f($row,'sigla')!='FNDFIXO') {
                if ($w_visao_completa) {
                  ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=OutraParte&R='.$w_pagina.$par.'&O=A&w_menu='.nvl($w_menu,f($row,'sq_menu')).'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG='.substr(f($row,'sigla'),0,3).'OUTRAP').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa dados da pessoa associada ao lançamento.">PE</a>&nbsp');
                } else {
                  if (nvl(f($row,'pessoa'),'')=='' || substr(f($row,'sigla'),3)=='REEMB') ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=OutraParte&R='.$w_pagina.$par.'&O=A&w_menu='.nvl($w_menu,f($row,'sq_menu')).'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pessoa'.'&SG='.substr(f($row,'sigla'),0,3).'OUTRAP').'\',\'Pessoa\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa dados da pessoa associada ao lançamento.">PE</a>&nbsp');
                }
                
                //if (substr(f($row,'sigla'),0,3)!='FNR') {
                  if (piece(f($row,'dados_pai'),null,'|@|',12)!=='CO') ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=Documento&R='.$w_pagina.$par.'&O=L&w_menu='.f($row,'sq_menu').'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Doc'.'&SG=DOCUMENTO').'\',\'Doc\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa documentos e comprovantes associados ao lançamento.">Doc</A>&nbsp');
                //}
              }
              ShowHTML('          <A class="hl" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET')).'" title="Envia o lançamento para outro responsável ou fase.">EN</A>&nbsp');
              if (nvl($w_acao,'')!='') ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'"'.(($P2==2) ? ' title="Ajuste nos dados do pagamento.">AP' : '>'.$w_acao).'</A>&nbsp');
            } else {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Ajustes nos dados do pagamento.">AL</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
              if (nvl($w_acao,'')!='') ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'"'.(($P2>=2) ? ' title="Ajuste nos dados do pagamento.">AP' : '>'.$w_acao).'</A>&nbsp');
            }
          } else {
            if (f($row,'sigla')=='FNDTARIFA' || substr(f($row,'sigla'),0,3)=='FNA' || f($row,'sigla')=='FNDFIXO') {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Registro do pagamento.">AL</A>&nbsp');
              if (substr(f($row,'sigla'),0,3)=='FNA' || f($row,'sigla')=='FNDTARIFA' || f($row,'sigla')=='FNDFIXO') {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Excluir&R='.$w_pagina.$par.'&O=E&w_retorno=Volta&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
              } else {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_retorno=Volta&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exclusão do lançamento.">EX</A>&nbsp');
              }
              if (substr(f($row,'sigla'),0,3)=='FNA' || f($row,'sigla')=='FNDTARIFA' || f($row,'sigla')=='FNDFIXO') {
                ShowHTML('          <A class="hl" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_menu='.f($row,'sq_menu').'&SG='.f($row,'sigla').MontaFiltro('GET')).'" title="Envia o lançamento para outro responsável ou fase.">EN</A>&nbsp');
              }
            } else {
              ShowHTML('          <A class="hl" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_destino.'.php?par=envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_menu='.f($row,'sq_menu').'&SG='.f($row,'sigla').MontaFiltro('GET')).'" title="Envia o lançamento para outro responsável ou fase.">EN</A>&nbsp');
              if (f($row,'sigla')=='FNDFUNDO') {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Ajustes nos dados do pagamento.">AL</A>&nbsp');
              } elseif (f($row,'sg_tramite')=='AT') {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Ajuste nos dados do pagamento.">AP</A>&nbsp');
              }
            }
          }
          if (strpos('AC,CO',piece(f($row,'dados_pai'),null,'|@|',12))===false) {
            // Pagamento de contrato ou de compras não pode ser copiado.
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_destino.'.php?par=Geral&R='.$w_pagina.$par.'&O=I&w_copia='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Gera novo lançamento a partir dos dados deste.">CP</A>&nbsp');
          }
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      }
      if ($P2>1||$w_visao_completa) {
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td align="right" colspan="'.$colspan.'"><b>Subtota'.((count($w_parcial)==1) ? 'l' : 'is').'</td>');
          ShowHTML('          <td align="right" nowrap><b>');
          $i = 0;
          ksort($w_parcial);
          foreach($w_parcial as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber(abs($v),2)); $i++; }
          echo('</td>');

          ShowHTML('          <td align="center"><b>');
          $i = 0;
          foreach($w_parcial as $k => $v) { echo((($i) ? '<br>' : '').(($v>0) ? '+' : (($v<0) ? '-' : ''))); $i++; }
          echo('</td>');

          ShowHTML('          <td colspan="8">&nbsp;</td>');
          ShowHTML('        </tr>');
        }
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          $w_total = array();
          foreach($RS as $row) {
            $w_valor = nvl(((f($row,'sg_tramite')=='AT') ? f($row,'valor_atual') : f($row,'valor')),0);
            if (substr(f($row,'sigla'),2,1)=='R' || f($row,'sigla')=='FNAAPLICA') $$w_valor = $w_valor;
            elseif (substr(f($row,'sigla'),2,1)=='D') $w_valor = -1 * $w_valor;
            $w_total[f($row,'sb_moeda')] = nvl($w_total[f($row,'sb_moeda')],0) + $w_valor;
          }
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td align="right" colspan="'.$colspan.'"><b>Tota'.((count($w_total)==1) ? 'l' : 'is').'</td>');
          ShowHTML('          <td align="right" nowrap><b>');
          $i = 0;
          ksort($w_total);
          foreach($w_total as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber(abs($v),2)); $i++; }
          echo('</td>');

          ShowHTML('          <td align="center"><b>');
          $i = 0;
          foreach($w_total as $k => $v) { echo((($i) ? '<br>' : '').(($v>0) ? '+' : (($v<0) ? '-' : ''))); $i++; }
          echo('</td>');

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
    //echo FormataDataEdicao(time(),2);
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
  } elseif(strpos('AEV',$O)!==false || nvl($w_copia,'')!='') {
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

    if (nvl($w_troca,'')=='' && (nvl($w_copia,'')!='' || nvl($w_chave,'')!='')) {
      // Recupera dados do comprovante
      $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,nvl($w_copia,$w_chave),null,null,null,null,null,null,'DOCS');
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
    $sql = new db_getSolicRubrica; $RS_Rub = $sql->getInstanceOf($dbms,f($RS_Vinc,'sq_solic_pai'),null,'S',null,null,null,null,null,'SELECAO');

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
    ShowHTML('        <td><b>Valor:</b><br>'.formatNumber(f($RS_Vinc,'valor')));

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
      SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,f($RS_Vinc,'sq_solic_pai'),null,'w_sq_projeto_rubrica','SELECAO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_projeto_rubrica\'; document.Form.submit();"');
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
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
  ShowHTML(MontaFiltro('POST'));
  
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
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
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS_Cliente,'upload_maximo')/1024).'KBytes</b>.</font></td>');
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
    $sql = new db_getSolicRubrica; $RS_Rub = $sql->getInstanceOf($dbms,f($RS_Solic,'sq_solic_pai'),null,'S',null,null,null,null,null,'SELECAO');

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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
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
      SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,f($RS_Solic,'sq_solic_pai'),null,'w_sq_projeto_rubrica','SELECAO',null);
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
          $_REQUEST['w_texto_autorizacao'],$_REQUEST['w_moeda'],$w_chave_nova, $w_codigo);


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
          $_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_serie'],$_REQUEST['w_moeda'],$_REQUEST['w_valor'],
          'N','N','N',null,null,null,null, $w_chave_doc);

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