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
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getAgreeType.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getBankData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getKindPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getAditivoAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoParcela.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoRep.php');
include_once($w_dir_volta.'classes/sp/db_getFormaPagamento.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getConvOutraParte.php');
include_once($w_dir_volta.'classes/sp/db_getConvPreposto.php');
include_once($w_dir_volta.'classes/sp/db_getConvOutroRep.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoAditivo.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoNota.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoNotaCancel.php');
include_once($w_dir_volta.'classes/sp/db_getCLSolicItem.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoTermo.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoParc.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoPreposto.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoRep.php');
include_once($w_dir_volta.'classes/sp/dml_putAditivoAnexo.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoConc.php');
include_once($w_dir_volta.'classes/sp/dml_putConvOutraParte.php');
include_once($w_dir_volta.'classes/sp/dml_putConvPreposto.php');
include_once($w_dir_volta.'classes/sp/dml_putConvOutroRep.php');
include_once($w_dir_volta.'classes/sp/dml_putConvDadosBancario.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoDadosAdicionais.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoAditivo.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoNota.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoNotaCancel.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLog.php');
include_once($w_dir_volta.'funcoes/selecaoTipoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAgencia.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoTipoConclusao.php');
include_once($w_dir_volta.'funcoes/selecaoTipoOutraParte.php');
include_once($w_dir_volta.'funcoes/selecaoLCModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoReajuste.php');
include_once($w_dir_volta.'funcoes/selecaoIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoOutraParte.php');
include_once($w_dir_volta.'funcoes/selecaoAditivo.php');
include_once($w_dir_volta.'funcoes/selecaoLCFonteRecurso.php');
include_once($w_dir_volta.'funcoes/selecaoCTEspecificacao.php');
include_once('visualacordo.php');
include_once('validaacordo.php');

// =========================================================================
//  /contratos.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia as rotinas relativas a controle de contratos e convênios
// Mail     : alex@sbpi.com.br
// Criacao  : 23/01/2005 15:01
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
$w_pagina       = 'contratos.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_ac/';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ((strpos($SG,'PARC')!==false) || !(strpos($SG,'ANEXO')===false) || !(strpos($SG,'OUTRA')===false) || !(strpos($SG,'DADOS')===false) || !(strpos($SG,'PREPOSTO')===false) || !(strpos($SG,'REPR')===false) || !(strpos($SG,'NOTA')===false)) {
  if ((strpos('IGV',$O)===false) && $_REQUEST['w_chave_aux']=='') $O='L';
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

// Verifica se o cliente tem o módulo de compras e licitações contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'CO');
if (count($RS)>0) $w_compras='S'; else $w_compras='N';

// Verifica se o cliente tem o módulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_mod_pa='S'; else $w_mod_pa='N';

// Verifica se o cliente tem o módulo de acordos contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'AC');
if (count($RS)>0) $w_acordo='S'; else $w_acordo='N'; 

// Verifica se o cliente tem o módulo viagens contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PD');
if (count($RS)>0) $w_viagem='S'; else $w_viagem='N'; 

$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'IS');
if (count($RS)>0) $w_acao='S'; else $w_acao='N'; 

// Verifica se o cliente tem o módulo de planejamento estratégico
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PE');
if (count($RS)>0) $w_pe='S'; else $w_pe='N'; 
  
// Carrega os parâmetros do módulo
$sql = new db_getParametro; $RS = $sql->getInstanceOf($dbms,$w_cliente,'AC',null);
if (count($RS)>0) {
  foreach($RS as $row) { $RS = $row; break; }
  $w_padrao_pagamento = f($RS,'texto_pagamento');
} 

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente);
$w_segmento = f($RS_Cliente,'segmento');

$w_copia         = $_REQUEST['w_copia'];
$w_herda         = $_REQUEST['w_herda'];
$p_sq_menu_relac = upper($_REQUEST['p_sq_menu_relac']);
$p_chave_pai     = upper($_REQUEST['p_chave_pai']);
$p_atividade     = upper($_REQUEST['p_atividade']);
$p_ativo         = upper($_REQUEST['p_ativo']);
$p_solicitante   = upper($_REQUEST['p_solicitante']);
$p_prioridade    = upper($_REQUEST['p_prioridade']);
$p_unidade       = upper($_REQUEST['p_unidade']);
$p_proponente    = upper($_REQUEST['p_proponente']);
$p_ordena        = lower($_REQUEST['p_ordena']);
$p_ini_i         = upper($_REQUEST['p_ini_i']);
$p_ini_f         = upper($_REQUEST['p_ini_f']);
$p_fim_i         = upper($_REQUEST['p_fim_i']);
$p_fim_f         = upper($_REQUEST['p_fim_f']);
$p_atraso        = upper($_REQUEST['p_atraso']);
$p_chave         = upper($_REQUEST['p_chave']);
$p_objeto        = upper($_REQUEST['p_objeto']);
$p_pais          = upper($_REQUEST['p_pais']);
$p_regiao        = upper($_REQUEST['p_regiao']);
$p_uf            = upper($_REQUEST['p_uf']);
$p_cidade        = upper($_REQUEST['p_cidade']);
$p_usu_resp      = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp     = upper($_REQUEST['p_uorg_resp']);
$p_palavra       = upper($_REQUEST['p_palavra']);
$p_prazo         = upper($_REQUEST['p_prazo']);
$p_fase          = explodeArray($_REQUEST['p_fase']);
$p_sqcc          = upper($_REQUEST['p_sqcc']);
$p_sq_acao_ppa   = upper($_REQUEST['p_sq_acao_ppa']);
$p_sq_orprior    = upper($_REQUEST['p_sq_orprior']);
$p_empenho       = upper($_REQUEST['p_empenho']);
$p_processo      = upper($_REQUEST['p_processo']);

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
  global $w_Disabled;
  $w_tipo=$_REQUEST['w_tipo'];

  if ($O=='L') {
    if (strpos(upper($R),'GR_')!==false || strpos(upper($R),'PROJETO')!==false || $w_tipo=='WORD') {
      $w_filtro='';
      if (nvl($p_chave_pai,'')>'') {
        if($w_tipo=='WORD') {
          $w_filtro.='<tr valign="top"><td align="right">Vinculação<td>['.exibeSolic($w_dir,$p_chave_pai,null,'S','S').']</td></tr>';
        } else {
          $w_filtro.='<tr valign="top"><td align="right">Vinculação<td>['.exibeSolic($w_dir,$p_chave_pai,null,'S').']</td></tr>';
        }
      }
      if ($p_atividade>'') {
        $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_chave_pai,$p_atividade,'REGISTRO',null);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
      }     
      if ($p_sqcc>'') {
        $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Classificação <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') $w_filtro .= '<tr valign="top"><td align="right">Contrato nº <td>[<b>'.$p_chave.'</b>]';
      if ($p_sq_acao_ppa>'') $w_filtro .= '<tr valign="top"><td align="right">Indicador <td>[<b>'.$p_sq_acao_ppa.'</b>]';
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
        $sql = new db_getAgreeType; $RS = $sql->getInstanceOf($dbms,$p_sq_orprior,null,$w_cliente,null,null,'ALTERA');
        foreach($RS as $row) {$RS = $row; break; }
        $w_filtro .= '<tr valign="top"><td align="right">Tipo do acordo <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uorg_resp>''){
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
      if ($p_proponente>'') {
        if (substr($SG,0,3)=='GCB') $w_filtro .= '<tr valign="top"><td align="right">Bolsista <td>[<b>'.$p_proponente.'</b>]';
        else                        $w_filtro .= '<tr valign="top"><td align="right">Outra parte <td>[<b>'.$p_proponente.'</b>]';
      }
      if ($p_atraso>'')     $w_filtro .= '<tr valign="top"><td align="right">Título <td>[<b>'.$p_atraso.'</b>]';
      if ($p_objeto>'')     $w_filtro .= '<tr valign="top"><td align="right">Objeto <td>[<b>'.$p_objeto.'</b>]';
      if ($p_palavra>'')    $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_empenho>'')    $w_filtro .= '<tr valign="top"><td align="right">Número do empenho<td>[<b>'.$p_empenho.'</b>]';
      if ($p_processo>'')   $w_filtro .= '<tr valign="top"><td align="right">Número do processo<td>[<b>'.$p_processo.'</b>]';
      if ($w_filtro>'')     $w_filtro  = '<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_chave_pai, $p_atividade, 
          $p_sq_acao_ppa, $p_sq_orprior, $p_empenho, $p_processo);
    } else {      
      $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_chave_pai, $p_atividade, 
          $p_sq_acao_ppa, $p_sq_orprior, $p_empenho, $p_processo);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'inicio','asc');
    } else {
      $RS = SortArray($RS,'nm_outra_parte','asc','inicio','desc');
    }
  }
  if ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  } elseif($w_tipo == 'PDF') {
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de '.f($RS_Menu,'nome'),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    if ($P1==2 || $P1==3) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('CP',$O)!==false) {
      if ($P1!=1 || strpos('C',$O)!==false) {
        if(nvl($p_sq_menu_relac,'')>'') {
          if ($p_sq_menu_relac=='CLASSIF') {
            ShowHTML('  if (theForm.p_sqcc.selectedIndex==0) {');
            ShowHTML('    alert("Você deve indicar a classificação!");');
            ShowHTML('    theForm.p_sqcc.focus();');
            ShowHTML('    return false;');
            ShowHTML('  }');
          } else {
            ShowHTML('  if (theForm.p_chave_pai.selectedIndex==0) {');
            ShowHTML('    alert("Você deve indicar a vinculação!");');
            ShowHTML('    theForm.p_chave_pai.focus();');
            ShowHTML('    return false;');
            ShowHTML('  }');
          }
        }                  
        // Se não for cadastramento ou se for cópia
        Validate('p_chave','Chave','','','1','18','','0123456789');
        if (substr($SG,0,3)=='GCB') Validate('p_proponente','Outra parte','','','2','90','1','');
        else                        Validate('p_proponente','Bolsista','','','2','90','1','');
        Validate('p_palavra','Código interno','','','3','90','1','1');
        Validate('p_atraso','Código externo','','','1','90','1','1');
        Validate('p_objeto','Objeto','','','2','90','1','1');
        Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
        Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != "" && theForm.p_ini_f.value == "") || (theForm.p_ini_i.value == "" && theForm.p_ini_f.value != "")) {');
        ShowHTML('     alert ("Informe ambas as datas de recebimento ou nenhuma delas!");');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
        Validate('p_fim_i','Conclusão inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim_f','Conclusão final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != "" && theForm.p_fim_f.value == "") || (theForm.p_fim_i.value == "" && theForm.p_fim_f.value != "")) {');
        ShowHTML('     alert ("Informe ambas as datas de conclusão ou nenhuma delas!");');
        ShowHTML('     theForm.p_fim_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_fim_i','Conclusão inicial','<=','p_fim_f','Conclusão final');
      } 
      Validate('P4','Linhas por página','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</head>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_embed=='WORD') {
      // Se for Word
      BodyOpenWord();
  } elseif ($w_troca>'') {
    // Se for recarga da página
    BodyOpenClean('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='I') {
    BodyOpenClean('onLoad="document.Form.w_smtp_server.focus();"');
  } elseif ($O=='A') {
    BodyOpenClean('onLoad="document.Form.w_nome.focus();"');
  } elseif ($O=='E') {
    BodyOpenClean('onLoad="document.Form.w_assinatura.focus()";');
  } elseif (strpos('CP',$O)!==false) {
    BodyOpenClean('onLoad="document.Form.p_sq_menu_relac.focus()";');
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
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($P1==1 && $w_copia=='') {
      if ($w_embed!='WORD') {
        // Se for cadastramento e não for resultado de busca para cópia
        ShowHTML('<tr><td>');
        if ($w_submenu>'') {
          $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
          foreach($RS1 as $row) {
            ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
            break;
          }
        } else {
          ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        } 
        ShowHTML('    <a accesskey="C" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Inclui um novo contrato a partir dos dados de outro, já existente."><u>C</u>opiar</a>');
        if ($w_compras=='S') ShowHTML('    <a accesskey="H" class="ss" href="'.$w_dir.$w_pagina.'BuscaCompra&R='.$w_pagina.$par.'&O=H&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Inclui um novo contrato a partir de uma compra/licitação."><u>H</u>erdar</a>');
      }
    } 
    if ((strpos(upper($R),'GR_')===false) && (strpos(upper($R),'ACORDO')===false) && $P1!=6) {
      if ($w_embed!='WORD') {
        if ($w_copia>'') {
          // Se for cópia
          if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
            ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          } else {
            ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
          } 
        } else {
          if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
            ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          } else {
            ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
          } 
        } 
      } 
    } 
    ShowHTML('    <td align="right">');
    ShowHTML('    '.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_embed!='WORD') {    
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Código','codigo_interno').'</font></td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Título','nm_acordo').'</font></td>');
      if ($w_segmento=='Público' || $w_mod_pa=='S') {
        if (substr($SG,0,3)=='GCB') ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Empenho','processo').'</font></td>');
        else                        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Processo','processo').'</font></td>');
      }
      if ($_SESSION['INTERNO']=='S') ShowHTML ('          <td rowspan=2><b>'.LinkOrdena('Vinc.','dados_pai').'</td>');
      ShowHTML('          <td colspan=2><b>Vigência</font></td>');
      if (substr($SG,0,3)!='GCA') {
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('$ Previsto','valor_contrato').'</font></td>');
        if (strpos(upper($R),'GR_')!==false) {
          ShowHTML('          <td rowspan=2><b>'.LinkOrdena('$ Liquidado','saldo_contrato').'</font></td>');
        }  
      }
      if ($P1!=1) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</font></td>');
      } 
      ShowHTML('          <td colspan=4><b>Indicadores</font></td>');
      if ($_SESSION['INTERNO']=='S' && $w_embed!='WORD') ShowHTML('          <td class="remover" rowspan=2><b>Operações</font></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('Início','inicio').'</font></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Fim','fim').'</font></td>');
      ShowHTML('          <td><b>'.LinkOrdena('IDEC','idcc').'</font></td>');
      ShowHTML('          <td colspan="2"><b>'.LinkOrdena('IDCC','idcc').'</font></td>');
      ShowHTML('          <td><b>'.LinkOrdena('IGCC','igcc').'</font></td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td rowspan=2><b>Código</font></td>');
      ShowHTML('          <td rowspan=2><b>Título</font></td>');
      if ($w_segmento=='Público' || $w_mod_pa=='S') {
         if (substr($SG,0,3)=='GCB') ShowHTML('          <td rowspan=2><b>Empenho</font></td>');
         else                        ShowHTML('          <td rowspan=2><b>Processo</font></td>');
      }
      if ($_SESSION['INTERNO']=='S') ShowHTML ('          <td rowspan=2><b>Vinc.</td>');
      ShowHTML('          <td colspan=2><b>Vigência</font></td>');
      if (substr($SG,0,3)!='GCA') {
        ShowHTML('          <td rowspan=2><b>$ Previsto</font></td>');
        if (strpos(upper($R),'GR_')!==false) {
          ShowHTML('          <td rowspan=2><b>$ Liquidado</font></td>');
        }  
      }
      if ($P1!=1) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>Fase atual</font></td>');
      } 
      ShowHTML('          <td colspan=4><b>Indicadores</font></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Início</td>');
      ShowHTML('          <td><b>Fim</td>');
      ShowHTML('          <td><b>IDEC</td>');
      ShowHTML('          <td colspan="2"><b>IDCC</b></td>');
      ShowHTML('          <td><b>IGCC</td>');
      ShowHTML('        </tr>');
    }  
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=14 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $w_atual=0;
      if($w_embed!='WORD') {
        $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      } else   {
        $RS1=$RS;
      }
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio'),f($row,'fim'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        if ($w_embed!='WORD') ShowHTML('        <A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4=0&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'objeto').'">'.f($row,'codigo_interno').'&nbsp;</a>');
        else                  ShowHTML('        '.f($row,'codigo_interno').'');
        ShowHTML('        <td>'.f($row,'nm_acordo').'</td>');
        if ($w_mod_pa=='S') {
          if ($w_embed!='WORD' && nvl(f($row,'protocolo_siw'),'')!='') {
            ShowHTML('        <td align="right"><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($row,'protocolo').'&nbsp;</a>');
          } else {
            ShowHTML('        <td>'.f($row,'processo'));
          }
        } elseif ($w_segmento=='Público') ShowHTML('        <td>'.f($row,'processo').'</td>');        
        if ($_SESSION['INTERNO']=='S') {
          if (Nvl(f($row,'dados_pai'),'')!='') ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_embed).'</td>');
          else                                 ShowHTML('        <td>---</td>');
        } 
        ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'inicio'),5),'-').'</td>');
        if (Nvl(f($row,'fim'),'')>'') {
          ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim'),5),'-').'</td>');
        } else {
          ShowHTML('        <td align="center">&nbsp;');
        } 
        if (substr($SG,0,3)!='GCA') {
          ShowHTML('        <td align="right">'.number_format(f($row,'valor_contrato'),2,',','.').'&nbsp;</td>');
          $w_parcial += f($row,'valor_contrato'); 
          if (strpos(upper($R),'GR_')!==false) {
            ShowHTML('        <td align="right">'.formatNumber(Nvl(f($row,'valor_contrato')-f($row,'saldo_contrato'),0)).'</td>');
            $w_atual += (Nvl(f($row,'valor_contrato')-f($row,'saldo_contrato'),0));
          } 
        }
        if ($P1!=1) {
          // Se não for cadastramento, mostra a fase atual
          ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        } 
        ShowHTML('        <td align="center">'.((f($row,'exibe_idec')=='S') ? ExibeSmile('idec',f($row,'idcc')) : '&nbsp;').'</td>');
        ShowHTML('        <td align="center">'.ExibeSmile('idcc',f($row,'idcc')).'</td>');
        ShowHTML('        <td align="center">'.formatNumber(f($row,'idcc')).'%</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'igcc')).'%</td>');
        if ($_SESSION['INTERNO']=='S' && $w_embed!='WORD') { 
          ShowHTML('        <td class="remover" align="top" nowrap>');
          if ($P1!=3) {
            // Se não for acompanhamento
            if ($w_copia>'') {
              // Se for listagem para cópia
              $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
              foreach($RS1 as $row1) {
                ShowHTML('          <a class="hl" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
                break;
              }
            } elseif ($P1==1) {
              // Se for cadastramento
              if ($w_submenu>'') {
                ShowHTML('          <A class="hl" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'codigo_interno').MontaFiltro('GET').'" title="Altera as informações gerais" TARGET="menu">AL</a>&nbsp;');
              } else {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações gerais">AL</A>&nbsp');
              } 
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão.">EX</A>&nbsp');
            } elseif ($P1==2 || $P1==6) {
              // Se for execução
              if ($w_usuario==f($row,'executor')) {
                if ($w_segmento=='Público' && substr($SG,0,3)=='GCD') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Notas&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'- Notas de empenho'.'&SG=GCDNOTA'.MontaFiltro('GET').'" target="Notas" title="Registra as notas de empenho do contrato.">NE</A>&nbsp;');
                }
                if(f($row,'qtd_item')>0) {
                  ShowHTML('          <A class="hl" HREF="mod_cl/certame.php?par=ItensContrato&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Itens de ARP'.'&SG=GCZITEM'.MontaFiltro('GET').'" target="ItensARP" title="Itens de arp.">IT</A>&nbsp;');
                }
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações, sem enviá-la.">AN</A>&nbsp');
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia para outro responsável.">EN</A>&nbsp');
                if (f($row,'sg_tramite')=='EE') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução.">CO</A>&nbsp');
                }
                if(substr($SG,0,3)=='GCR' || substr($SG,0,3)=='GCD' || substr($SG,0,3)=='GCZ') {
                 ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Aditivos&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'- Aditivos'.'&SG=GC'.substr($SG,2,1).'ADITIVO'.MontaFiltro('GET').'" target="Aditivos" title="Registra os aditivos do contrato.">Aditivos</A>&nbsp;');
                }
              } else {
                if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia para outro responsável.">EN</A>&nbsp');
                } else {
                  ShowHTML('          ---&nbsp');
                }
              } 
            } 
          } else {
            if (Nvl(f($row,'solicitante'),0)==$w_usuario || 
               Nvl(f($row,'titular'),0)     ==$w_usuario || 
               Nvl(f($row,'substituto'),0)  ==$w_usuario || 
               Nvl(f($row,'tit_exec'),0)    ==$w_usuario || 
               Nvl(f($row,'subst_exec'),0)  ==$w_usuario
               ) {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia para outro responsável.">EN</A>&nbsp');
            } else {
              ShowHTML('          ---&nbsp');
            } 
          } 
          ShowHTML('        </td>');
        } 
        ShowHTML('      </tr>');
      } 
      if ($P1!=1 && substr($SG,0,3)!='GCA') {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=6 align="right"><b>Total desta página&nbsp;</font></td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</font></td>');
          if (strpos(upper($R),'GR_')!==false) {
            ShowHTML('          <td align="right"><b>'.number_format($w_atual,2,',','.').'&nbsp;</font></td>');
          } 
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            $w_real  += (f($row,'valor_contrato')-Nvl(f($row,'saldo_contrato'),0));
            $w_total += f($row,'valor_contrato');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=6 align="right"><b>Total da listagem&nbsp;</font></td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_total,2,',','.').'&nbsp;</font></td>');
          if (strpos(upper($R),'GR_')!==false) {
            ShowHTML('          <td align="right"><b>'.number_format($w_real,2,',','.').'&nbsp;</font></td>');
          } 
          ShowHTML('          <td colspan=6>&nbsp;</font></td>');
          ShowHTML('        </tr>');
        } 
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
  } elseif (strpos('CP',$O)!==false) {
    if ($w_embed!='WORD') { 
      if ($O=='C') {
        // Se for cópia
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar o que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
      } else {
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
      }
    }   
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    }
    ShowHTML('      <tr><td colspan=2><table border=0 width="100%" cellspacing=0><tr valign="top">');
    selecaoServico('<U>R</U>estringir a:', 'S', null, $p_sq_menu_relac, $w_menu, null, 'p_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($p_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($p_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classificação',null,null,$w_cliente,$p_sqcc,$p_sq_menu_relac,null,'p_sqcc','SIWSOLIC',null);
      } else {
        $sql = new db_getMenuData; $RS_Relac  = $sql->getInstanceOf($dbms,$p_sq_menu_relac);
        if(f($RS_Relac,'sg_modulo')=='PR' && $w_cliente!=10135) {
          SelecaoSolic('Vinculação:',null,null,$w_cliente,$p_chave_pai,$p_sq_menu_relac,f($RS_Menu,'sq_menu'),'p_chave_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_etapa\'; document.Form.submit();"');
          if(nvl($p_chave_pai,'')!='') {
            ShowHTML('      <tr>');
            SelecaoEtapa('<u>T</u>ema e modalidade:','T','Se necessário, indique a etapa desejada para a vinculação.',$p_etapa,$p_chave_pai,null,'p_etapa','CONTRATO',null);
            ShowHTML('      </tr>');
          }
        } else {
          SelecaoSolic('Vinculação:',null,null,$w_cliente,$p_chave_pai,$p_sq_menu_relac,f($RS_Menu,'sq_menu'),'p_chave_pai',f($RS_Relac,'sigla'),null);
        }
      }
    }
    ShowHTML('          </td></tr></table></td></tr>');
    if ($P1!=1 || $O=='C') { 
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>C<u>h</u>ave:<br><INPUT ACCESSKEY="H" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      if (substr($SG,0,3)=='GCB')   ShowHTML('          <td><b><U>B</U>olista:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      else                          ShowHTML('          <td><b>O<U>u</U>tra parte:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>Có<U>d</U>igo interno:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_palavra" size="18" maxlength="18" value="'.$p_palavra.'"></td>');
      ShowHTML('          <td><b>Có<U>d</U>igo externo:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_atraso" size="18" maxlength="18" value="'.$p_atraso.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Gestor do co<u>n</u>trato:','N','Selecione o gestor do contrato na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor responsável:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Responsável atua<u>l</u>:','L','Selecione o responsável atual na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade desejada na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr valign="top">');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
      ShowHTML('          <td><b>Dias para <U>t</U>érmino da vigência:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>Iní<u>c</u>io vigência entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b>Fi<u>m</u> vigência entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      if ($O!='C') {
        // Se não for cópia
        if($P2>0) SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    }
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if ($p_Ordena=='ASSUNTO') {
      ShowHTML('          <option value="assunto" SELECTED>Objeto<option value="inicio">Início vigência<option value="">Término vigência<option value="nm_tramite">Fase atual<option value="prioridade">Outra parte<option value="proponente">Projeto');
    } elseif ($p_Ordena=='INICIO') {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio" SELECTED>Início vigência<option value="">Término vigência<option value="nm_tramite">Fase atual<option value="prioridade">Outra parte<option value="proponente">Projeto');
    } elseif ($p_Ordena=='FIM') {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio">Início vigência<option value="">Término vigência<option value="nm_tramite" SELECTED>Fase atual<option value="prioridade">Outra parte<option value="proponente">Projeto');
    } elseif ($p_Ordena=='NM_OUTRA_PARTE') {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio">Início vigência<option value="">Término vigência<option value="nm_tramite">Fase atual<option value="prioridade" SELECTED>Outra parte<option value="proponente">Projeto');
    } elseif ($p_Ordena=='NM_PROJETO') {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio">Início vigência<option value="">Término vigência<option value="nm_tramite">Fase atual<option value="prioridade">Outra parte<option value="proponente" SELECTED>Projeto');
    } else {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio">Início vigência<option value="" SELECTED>Término vigência<option value="nm_tramite">Fase atual<option value="prioridade">Outra parte<option value="proponente">Projeto');
    } 
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
  if ($p_tipo!='WORD') {
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  }
} 

// =========================================================================
// Rotina de busca de compras e licitações para geração de contrato
// -------------------------------------------------------------------------
function BuscaCompra() {
  extract($GLOBALS);
  global $w_Disabled;

  // Recupera chave do menu de licitações
  $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'CLLCCAD');

  // Recupera certames passíveis de contratação
  $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$_SESSION['SQ_PESSOA'],'CONTRATO',3,
      null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
      null,null,null,null,null,null,null,null,null,null,null);
      
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('<TITLE>Seleção de compra/licitação</TITLE>');
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');

  ShowHTML('<tr><td>Selecione o certame/fornecedor desejado para geração do contrato, clicando na operação "Selecionar".');
  ShowHTML('<tr><td colspan=6>');
  ShowHTML('    <TABLE WIDTH="100%" border=0>');
  if (count($RS)<=0) {
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
  } else {
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('            <td width="1%" nowrap><b>Compra/Lic.</td>');
    if ($w_mod_pa=='S') ShowHTML('            <td width="1%" nowrap><b>Protocolo</td>');
    ShowHTML('            <td colspan=2><b>Fornecedor</td>');
    ShowHTML('            <td><b>Itens</td>');
    ShowHTML('            <td><b>Operações</td>');
    ShowHTML('          </tr>');
    $w_certame    = '';
    $w_fornecedor = '';
    $w_exibe      = false;
    $i            = 0;
    // Recupera a sigla do serviço
    $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
    foreach($RS1 as $row1) { $w_sg_menu = f($row1,'sigla'); break; }
    
    foreach($RS as $row) {
      if ($w_certame!=f($row,'cd_certame')) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr><td colspan=6 height=1 bgcolor="black">');
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'cd_certame').'&nbsp;</td>');
        if ($w_mod_pa=='S') ShowHTML('        <td width="1%" nowrap>&nbsp;'.f($row,'processo').'&nbsp;</td>');
        ShowHTML('        <td width="1%" nowrap align="center">&nbsp;'.f($row,'cd_fornecedor').'&nbsp;</td>');
        ShowHTML('        <td>'.f($row,'nm_fornecedor').'</td>');
        $w_certame    = f($row,'cd_certame');
        $w_fornecedor = f($row,'nm_fornecedor');
        $w_exibe      = true;
      } elseif ($w_fornecedor!=f($row,'nm_fornecedor')) {
        ShowHTML('      <tr><td height=1></td><td height=1></td><td colspan=4 height=1 bgcolor="black">');
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td></td><td></td>');
        ShowHTML('        <td width="1%" nowrap align="center">&nbsp;'.f($row,'cd_fornecedor').'&nbsp;</td>');
        ShowHTML('        <td>'.f($row,'nm_fornecedor').'</td>');
        $w_fornecedor = f($row,'nm_fornecedor');
        $w_exibe      = true;
      } else {
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td></td><td></td><td></td><td></td>');
      }
      ShowHTML('        <td>'.f($row,'ordem').' - '.f($row,'nm_material').'</td>');
      if ($w_exibe) {
        ShowHTML('        <td><a class="hl" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$w_sg_menu.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_herda='.f($row,'sq_siw_solicitacao').'|'.f($row,'sq_pessoa').MontaFiltro('GET').'">Herdar</a>&nbsp;');
      } else {
        ShowHTML('        <td></td>');
      }
      $w_exibe = false;
    }
    ShowHTML('        </table></tr>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave          = $_REQUEST['w_chave'];
  $w_sq_tipo_acordo = $_REQUEST['w_sq_tipo_acordo'];
  $w_readonly       = '';
  $w_erro           = '';
  
  // Verifica se a geração do código será automática ou não
  if (f($RS_Menu,'numeracao_automatica')==0) $w_numeracao_automatica = 'N'; else $w_numeracao_automatica = 'S'; 

  // Carrega os valores padrão para país, estado e cidade 
  // Carrega o segmento do cliente
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente); 
  if ($w_pais=='') {
    $w_pais   = f($RS,'sq_pais');
    $w_uf     = f($RS,'co_uf');
    $w_cidade = f($RS,'sq_cidade_padrao');
  } 
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_codigo_interno       = $_REQUEST['w_codigo_interno'];
    $w_sq_unidade_resp      = $_REQUEST['w_sq_unidade_resp'];
    $w_objeto               = $_REQUEST['w_objeto'];
    $w_aviso                = $_REQUEST['w_aviso'];
    $w_dias                 = $_REQUEST['w_dias'];
    $w_inicio_atual         = $_REQUEST['w_inicio_real'];
    $w_inicio_real          = $_REQUEST['w_inicio_real'];
    $w_fim_real             = $_REQUEST['w_fim_real'];
    $w_concluida            = $_REQUEST['w_concluida'];
    $w_data_conclusao       = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao       = $_REQUEST['w_nota_conclusao'];
    $w_custo_real           = $_REQUEST['w_custo_real'];
    $w_projeto              = $_REQUEST['w_projeto'];
    $w_sq_tipo_acordo       = $_REQUEST['w_sq_tipo_acordo'];
    $w_sq_tipo_pessoa       = $_REQUEST['w_sq_tipo_pessoa'];
    $w_sq_forma_pagamento   = $_REQUEST['w_sq_forma_pagamento'];
    $w_chave                = $_REQUEST['w_chave'];
    $w_chave_pai            = $_REQUEST['w_chave_pai'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_sq_menu              = $_REQUEST['w_sq_menu'];
    $w_sq_unidade           = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite           = $_REQUEST['w_sq_tramite'];
    $w_solicitante          = $_REQUEST['w_solicitante'];
    $w_cadastrador          = $_REQUEST['w_cadastrador'];
    $w_executor             = $_REQUEST['w_executor'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_justificativa        = $_REQUEST['w_justificativa'];
    $w_inicio               = $_REQUEST['w_inicio'];
    $w_fim                  = $_REQUEST['w_fim'];
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
    $w_codigo_interno       = $_REQUEST['w_codigo_interno'];
    $w_titulo               = $_REQUEST['w_titulo'];
    $w_numero_empenho       = $_REQUEST['w_numero_empenho'];
    $w_numero_processo      = $_REQUEST['w_numero_processo'];
    $w_protocolo            = $_REQUEST['w_protocolo'];
    $w_protocolo_nm         = $_REQUEST['w_protocolo_nm'];
    $w_data_assinatura      = $_REQUEST['w_data_assinatura'];
    $w_data_publicacao      = $_REQUEST['w_data_publicacao'];
    $w_sq_lcmodalidade      = $_REQUEST['w_sq_lcmodalidade'];
    $w_aditivo              = $_REQUEST['w_aditivo'];
    $w_sq_menu_relac        = $_REQUEST['w_sq_menu_relac'];
    $w_cd_compra            = $_REQUEST['w_cd_compra'];
    $w_ds_compra            = $_REQUEST['w_ds_compra'];
  } else {
    if ((strpos('AEV',$O)!==false || nvl($w_copia,'')!='' || nvl($w_herda,'')!='') && $w_troca=='') {
      if (nvl($w_copia,'')!='') {
        // Recupera os dados do contrato
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_copia,$SG);
      } elseif (nvl($w_herda,'')!='') {
        // Recupera os dados do contrato
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],'CLLCCAD',3,
            null,null,null,null,null,null,null,null,null,null,substr($w_herda,0,strpos($w_herda,'|')),null,null,null,null,null,null,
            null,null,null,null,null,null,null,null,null,null,null);
        if (count($RS)>0) $RS = $RS[0];
        
        $w_cd_compra = f($RS,'codigo_interno');
        $w_ds_compra = nvl(f($RS,'objeto'),f($RS,'justificativa'));

        // Recupera os dados do beneficiário em co_pessoa
        $sql = new db_getBenef; $RS_Benef = $sql->getInstanceOf($dbms,$w_cliente,substr($w_herda,strpos($w_herda,'|')+1),null,null,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
        if (count($RS_Benef)>0) $RS_Benef = $RS_Benef[0];
      } else {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
      } 
      if (count($RS)>0) {
        $w_codigo_interno       = ((nvl($w_herda,'')!='') ? '' : f($RS,'codigo_interno'));
        $w_titulo               = ((nvl($w_herda,'')!='') ? f($RS_Benef,'nm_pessoa') : f($RS,'titulo'));
        $w_sq_unidade_resp      = ((nvl($w_herda,'')!='') ? '' : f($RS,'sq_unidade'));
        $w_objeto               = f($RS,'objeto');
        $w_aviso                = f($RS,'aviso_prox_conc');
        $w_dias                 = f($RS,'dias_aviso');
        $w_inicio_real          = f($RS,'inicio');
        $w_fim_real             = f($RS,'fim');
        $w_custo_real           = f($RS,'valor_contrato');
        $w_projeto              = f($RS,'sq_solic_pai');
        $w_etapa                = f($RS,'sq_projeto_etapa');
        $w_sq_tipo_acordo       = f($RS,'sq_tipo_acordo');
        $w_sq_tipo_pessoa       = f($RS,'sq_tipo_pessoa');
        $w_sq_forma_pagamento   = f($RS,'sq_forma_pagamento');
        $w_forma_atual          = f($RS,'sq_forma_pagamento');
        $w_chave_pai            = f($RS,'sq_solic_pai');
        $w_chave_aux            = null;
        $w_sq_menu              = f($RS,'sq_menu');
        $w_sq_unidade           = f($RS,'sq_unidade');
        $w_sq_tramite           = f($RS,'sq_siw_tramite');
        $w_solicitante          = ((nvl($w_herda,'')!='') ? '' : f($RS,'solicitante'));
        $w_cadastrador          = f($RS,'cadastrador');
        $w_executor             = f($RS,'executor');
        $w_descricao            = f($RS,'descricao');
        $w_justificativa        = ((nvl($w_herda,'')!='') ? '' : f($RS,'justificativa'));
        $w_inicio               = ((nvl($w_herda,'')!='') ? '' : FormataDataEdicao(f($RS,'inicio')));
        if (strpos('AEV',$O)!==false) {
          $w_inicio_atual       = FormataDataEdicao(f($RS,'inicio'));
        } 
        $w_fim                  = ((nvl($w_herda,'')!='') ? '' : FormataDataEdicao(f($RS,'fim')));
        $w_inclusao             = f($RS,'inclusao');
        $w_ultima_alteracao     = f($RS,'ultima_alteracao');
        $w_conclusao            = f($RS,'conclusao');
        $w_valor                = number_format(f($RS,'valor'),2,',','.');
        $w_opiniao              = f($RS,'opiniao');
        $w_data_hora            = f($RS,'data_hora');
        $w_sqcc                 = f($RS,'sq_cc');
        $w_pais                 = f($RS,'sq_pais');
        $w_uf                   = f($RS,'co_uf');
        $w_cidade               = f($RS,'sq_cidade_origem');
        $w_palavra_chave        = f($RS,'palavra_chave');
        $w_numero_empenho       = f($RS,'empenho');
        $w_numero_processo      = f($RS,'processo');
        $w_protocolo            = nvl(f($RS,'protocolo_completo'),f($RS,'processo'));
        $w_protocolo_nm         = f($RS,'processo');
        $w_opiniao              = f($RS,'opiniao');
        $w_data_assinatura      = FormataDataEdicao(f($RS,'assinatura'));
        $w_data_publicacao      = FormataDataEdicao(f($RS,'publicacao'));
        $w_sq_lcmodalidade      = f($RS,'sq_lcmodalidade');
        $w_aditivo              = nvl(f($RS,'aditivo'),0);
        $w_dados_pai            = explode('|@|',f($RS,'dados_pai'));
        $w_sq_menu_relac        = $w_dados_pai[3];
        if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
        
      } 
    } 
  } 
  if (Nvl($w_sq_tipo_acordo,0)>0) {
    $sql = new db_getAgreeType; $RS = $sql->getInstanceOf($dbms,$w_sq_tipo_acordo,null,$w_cliente,null,null,$SG);
    foreach($RS as $row) {
      $w_cd_modalidade    = f($row,'modalidade');
      $w_prazo_indeterm   = f($row,'prazo_indeterm');
      $w_pessoa_fisica    = f($row,'pessoa_fisica');
      $w_pessoa_juridica  = f($row,'pessoa_juridica'); 
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
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    if($w_numeracao_automatica=='N') Validate('w_codigo_interno','Código interno','1',1,1,60,'1','1');
    if ($w_mod_pa=='S') {
      Validate('w_protocolo_nm','Número do processo','hidden','1','20','20','','0123456789./-');
    } elseif($w_segmento=='Público') {
      if (substr($SG,0,3)=='GCB') Validate('w_numero_processo','Número do empenho (modalidade/nível/mensalidade)','1','1',1,30,'1','1');
      else                        Validate('w_numero_processo','Número do processo','1','1',1,30,'1','1');
    }
    Validate('w_titulo','Título','1',1,5,100,'1','1'); 
    Validate('w_sq_tipo_acordo','Tipo','SELECT',1,1,18,'','0123456789');
    if (substr($SG,0,3)!='GCB') Validate('w_objeto','Objeto','1',1,5,2000,'1','1');
    else                        Validate('w_objeto','Plano de trabalho','1',1,5,2000,'1','1');
    if ($w_pessoa_fisica=='S' && $w_pessoa_juridica=='S') {
      Validate('w_sq_tipo_pessoa','Pessoa a ser contratada','SELECT',1,1,18,'','0123456789');
    } 
    if (substr($SG,0,3)!='GCA' || substr($SG,0,3)!='GCZ') {
      if (substr($SG,0,3)=='GCR') {
        Validate('w_sq_forma_pagamento','Forma de recebimento','SELECT',1,1,18,'','0123456789');
      } elseif (substr($SG,0,3)=='GCD') {
         Validate('w_sq_forma_pagamento','Forma de pagamento','SELECT',1,1,18,'','0123456789');
      } elseif (substr($SG,0,3)!='GCZ') {
        Validate('w_sq_forma_pagamento','Forma de pagamento/recebimento','SELECT',1,1,18,'','0123456789');
      }
    }
    if($w_aditivo==0) {
      Validate('w_inicio','Início vigência','DATA',1,10,10,'','0123456789/');
      Validate('w_fim','Término vigência','DATA',1,10,10,'','0123456789/');
      CompData('w_inicio','Início vigência','<=','w_fim','Término vigência');
      if (substr($SG,0,3)!='GCA' && nvl($w_herda,'')=='') {
        Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
      }
    }
    Validate('w_solicitante','Responsável','',1,1,18,'','0123456789');
    Validate('w_sq_unidade_resp','Setor responsável','HIDDEN',1,1,18,'','0123456789');
    if(nvl($w_sq_menu_relac,'')>'') {
      Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
      if ($w_sq_menu_relac=='CLASSIF') {
        Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
      } else {
        Validate('w_chave_pai','Vinculação','SELECT',1,1,18,1,1);
      }
    } elseif (substr($SG,0,3)=='GCB' || $w_cd_modalidade!='F') {
      Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
    }
    if($w_sq_menu_relac!='CLASSIF') { $sql = new db_getMenuData; $RS_Relac  = $sql->getInstanceOf($dbms,$w_sq_menu_relac); }
    if(f($RS_Relac,'sg_modulo')=='PR' && $w_cliente != '10135') {
       if (substr($SG,0,3)=='GCB') {
         Validate('w_etapa','Tema e modalidade','SELECT',1,1,18,'','0123456789');
       } else {
         Validate('w_etapa','Tema e modalidade','SELECT','',1,18,'','0123456789');
       }
       ShowHTML('  if (theForm.w_etapa[theForm.w_etapa.selectedIndex].value=="" && theForm.w_etapa.selectedIndex != 0) {');
       ShowHTML('     alert("A modalidade selecionada não permite esta vinculação.\n Ela pode estar com  100% de conclusão ou ser usada como tema.");');
       ShowHTML('     theForm.w_etapa.focus();');
       ShowHTML('     return false;');
       ShowHTML('  }');
    }
/**
*     if (substr($SG,0,3)=='GCB') {
*       ShowHTML('if (theForm.w_projeto!=undefined) {');
*       Validate('w_projeto','Projeto','SELECT',1,1,18,'','0123456789');
*       ShowHTML('}');
*       Validate('w_etapa','Tema e modalidade','SELECT',1,1,18,'','0123456789');
*       ShowHTML('  if (theForm.w_etapa[theForm.w_etapa.selectedIndex].value=="" && theForm.w_etapa.selectedIndex != 0) {');
*       ShowHTML('     alert("A modalidade selecionada não permite esta vinculação.\n Ela pode estar com  100% de conclusão ou ser usada como tema.");');
*       ShowHTML('     theForm.w_etapa.focus();');
*       ShowHTML('     return false;');
*       ShowHTML('  }');
*     } elseif ($w_cd_modalidade!='F') {
*       ShowHTML('if (theForm.w_projeto!=undefined) {');
*       Validate('w_projeto','Projeto','SELECT','',1,18,'','0123456789');
*       ShowHTML('}');
*       Validate('w_etapa','Etapa','SELECT','',1,18,'','0123456789');
*       ShowHTML('  if (theForm.w_etapa[theForm.w_etapa.selectedIndex].value=="" && theForm.w_etapa.selectedIndex != 0) {');
*       ShowHTML('     alert("A modalidade selecionada não permite esta vinculação.\n Ela pode estar com  100% de conclusão ou ser usada como tema.");');
*       ShowHTML('     theForm.w_etapa.focus();');
*       ShowHTML('     return false;');
*       ShowHTML('  }');
*     } 
*     if (f($RS_Menu,'solicita_cc')=='S') {
*       if ($w_cd_modalidade!='F') {
*         Validate('w_sqcc','Classificação','SELECT','',1,18,'','0123456789');
*       } else {
*         Validate('w_sqcc','Classificação','SELECT','1',1,18,'','0123456789');
*       } 
*     } 
*     if ($w_cd_modalidade!='F' && f($RS_Menu,'solicita_cc')=='S') {
*       ShowHTML('if (theForm.w_projeto!=undefined) {');
*       ShowHTML('  if (theForm.w_projeto.selectedIndex > 0 && theForm.w_sqcc.selectedIndex > 0) {');
*       ShowHTML('     alert("Informe um projeto ou uma classificação. Você não pode escolher ambos!");');
*       ShowHTML('     theForm.w_projeto.focus();');
*       ShowHTML('     return false;');
*       ShowHTML('  }');
*       ShowHTML('  if (theForm.w_projeto.selectedIndex == 0 && theForm.w_sqcc.selectedIndex == 0) {');
*       ShowHTML('     alert("Informe um projeto ou uma classificação!");');
*       ShowHTML('     theForm.w_projeto.focus();');
*       ShowHTML('     return false;');
*       ShowHTML('  }');
*       ShowHTML('} else {');
*       ShowHTML('  if (theForm.w_sqcc.selectedIndex == 0) {');
*       ShowHTML('     alert("Informe uma classificação!");');
*       ShowHTML('     theForm.w_sqcc.focus();');
*       ShowHTML('     return false;');
*       ShowHTML('  }');
*       ShowHTML('}');
*     } 
*/
    Validate('w_pais','País','SELECT',1,1,18,'','0123456789');
    Validate('w_uf','Estado','SELECT',1,1,3,'1','1');
    Validate('w_cidade','Cidade','SELECT',1,1,18,'','0123456789');
    if (f($RS_Menu,'descricao')=='S') {
      Validate('w_descricao','Resultados esperados','1',1,5,2000,'1','1');
    } 
    if (f($RS_Menu,'justificativa')=='S') {
      Validate('w_justificativa','Observações','1','',5,2000,'1','1');
    } 
    Validate('w_dias','Dias de alerta','1','',1,3,'','0123456789');
    ShowHTML('  if (theForm.w_aviso[0].checked) {');
    ShowHTML('     if (theForm.w_dias.value == "") {');
    ShowHTML('        alert("Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!");');
    ShowHTML('        theForm.w_dias.focus();');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  }');
    ShowHTML('  else {');
    ShowHTML('     theForm.w_dias.value = "";');
    ShowHTML('  }');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">'); 
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpenClean('onLoad="this.focus()";');
  } else {
    if($w_numeracao_automatica=='N') {
      BodyOpenClean('onLoad="document.Form.w_codigo_interno.focus()";');
    } else {
      if ($w_mod_pa=='S') {
        BodyOpenClean('onLoad="document.Form.w_protocolo_nm.focus()";');
      } elseif($w_segmento=='Público') {
        BodyOpenClean('onLoad="document.Form.w_numero_processo.focus()";');
      } else {
        BodyOpenClean('onLoad="document.Form.w_titulo.focus()";');
      }
    }
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled = ' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_herda" value="'.$w_herda.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_forma_atual" value="'.$w_forma_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_inicio_atual" value="'.$w_inicio_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_cd_compra" value="'.$w_cd_compra.'">');
    ShowHTML('<INPUT type="hidden" name="w_ds_compra" value="'.$w_ds_compra.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    if (nvl($w_cd_compra,'')!='') {
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Dados da Licitação</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>Número:</b><br>'.$w_cd_compra.'</td>');
      ShowHTML('          <td><b>Justificativa/Objeto:</b><br>'.$w_ds_compra.'</td>');
      ShowHTML('      </tr></table>');
    }
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para identificação, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr valign="top">');
    if($w_numeracao_automatica=='N') {
      ShowHTML('          <td><b><U>C</U>ódigo interno:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="w_codigo_interno" title="Informar sigla e número do contrato (CTR) ou Ata de Registro de Preços (ATA)" size="18" maxlength="60" value="'.$w_codigo_interno.'"></td>');
    }
    if ($w_mod_pa=='S') {
      SelecaoProtocolo('N<u>ú</u>mero do processo:','U','Selecione o processo da compra/licitação.',$w_protocolo,null,'w_protocolo','JUNTADA',null);
    } elseif($w_segmento=='Público') {
      if (substr($SG,0,3)=='GCB') ShowHTML('          <td><b>N<U>ú</U>mero do empenho (modalidade/nível/mensalidade)<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="w_numero_processo" size="20" maxlength="30" value="'.$w_numero_processo.'"></td>'); 
      else                        ShowHTML('          <td><b>N<U>ú</U>mero do processo:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="w_numero_processo" size="20" maxlength="30" value="'.$w_numero_processo.'"></td>');
    }
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b><u>T</u>ítulo:</b><br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" title="Informar o nome reduzido do fornecedor."></td>');
    ShowHTML('      <tr>');
    SelecaoTipoAcordo('<u>T</u>ipo:','T','Selecione na lista o tipo adequado.',$w_sq_tipo_acordo,null,$w_cliente,'w_sq_tipo_acordo',$SG,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_objeto\'; document.Form.submit();"');
    ShowHTML('      </tr>');
    if (substr($SG,0,3)!='GCB') ShowHTML('      <tr><td colspan=2><b>O<u>b</u>jeto:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_objeto" class="sti" ROWS=5 cols=75 title="Descreva o objeto da contratação.">'.$w_objeto.'</TEXTAREA></td>');
    else                        ShowHTML('      <tr><td colspan=2><b><u>P</u>lano de trabalho:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_objeto" class="sti" ROWS=5 cols=75 title="Descreva o objeto da contratação.">'.$w_objeto.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    if ($w_pessoa_fisica=='S' && $w_pessoa_juridica=='S') {
      $sql = new db_getConvOutraParte; $RS1 = $sql->getInstanceOf($dbms,null,$w_chave,null,null);
      if(count($RS1)==0) {
        SelecaoTipoPessoa('O<u>u</u>tra parte é pessoa:','T','Selecione na lista o tipo de pessoa que será indicada como a outra parte.',$w_sq_tipo_pessoa,$w_cliente,'w_sq_tipo_pessoa',null,null); 
      } else {
        ShowHTML('<INPUT type="hidden" name="w_sq_tipo_pessoa" value="'.$w_sq_tipo_pessoa.'">');
      }
    } elseif (($w_sq_tipo_pessoa==2 && $w_pessoa_juridica=='N') || ($w_sq_tipo_pessoa==1 && $w_pessoa_fisica=='N')) {
      $sql = new db_getConvOutraParte; $RS1 = $sql->getInstanceOf($dbms,null,$w_chave,null,null);
      if(count($RS1)==0) {
        SelecaoTipoPessoa('O<u>u</u>tra parte é pessoa:','T','Selecione na lista o tipo de pessoa que será indicada como a outra parte.',$w_sq_tipo_pessoa,$w_cliente,'w_sq_tipo_pessoa',null,null); 
      } else {
        ShowHTML('<INPUT type="hidden" name="w_sq_tipo_pessoa" value="'.$w_sq_tipo_pessoa.'">');
      }
    } elseif ($w_pessoa_fisica=='S') {
      $sql = new db_getKindPersonList; $RS = $sql->getInstanceOf($dbms,'Física');
      foreach($RS as $row) {
        $w_sq_tipo_pessoa = f($row,'sq_tipo_pessoa');
        ShowHTML('<INPUT type="hidden" name="w_sq_tipo_pessoa" value="'.f($row,'sq_tipo_pessoa').'">');
        break;
      }
    } elseif ($w_pessoa_juridica=='S') {
      $sql = new db_getKindPersonList; $RS = $sql->getInstanceOf($dbms,'Jurídica');
      foreach($RS as $row) {
        $w_sq_tipo_pessoa = f($row,'sq_tipo_pessoa');
        ShowHTML('<INPUT type="hidden" name="w_sq_tipo_pessoa" value="'.f($row,'sq_tipo_pessoa').'">');
        break;
      }
    }        
    if (substr($SG,0,3)=='GCA' || substr($SG,0,3)=='GCZ') {
      $sql = new db_getFormaPagamento; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,'REGISTRO',null,'NAPLICA');
      foreach($RS as $row) { 
        $w_sq_forma_pagamento = f($row,'w_sq_forma_pagamento');
        ShowHTML('<INPUT type="hidden" name="w_sq_forma_pagamento" value="'.f($row,'chave').'">');
      }   
    } elseif (substr($SG,0,3)=='GCR') {
      SelecaoFormaPagamento('<u>F</u>orma de recebimento:','F','Selecione na lista a forma de recebimento para este acordo.',$w_sq_forma_pagamento,substr($SG,0,3).'CAD','w_sq_forma_pagamento',null);
    } elseif (substr($SG,0,3)=='GCD') {
      SelecaoFormaPagamento('<u>F</u>orma de pagamento:','F','Selecione na lista a forma de pagamento para este acordo.',$w_sq_forma_pagamento,substr($SG,0,3).'CAD','w_sq_forma_pagamento',null);
    } else {
      SelecaoFormaPagamento('<u>F</u>orma de pagamento/recebimento:','F','Selecione na lista a forma usual para pagamento/recebimento neste acordo.',$w_sq_forma_pagamento,substr($SG,0,3).'CAD','w_sq_forma_pagamento',null);
    }
    if($w_aditivo==0) {
      ShowHTML('        <tr valign="top">');
      ShowHTML('              <td><b>Iní<u>c</u>io vigência:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_inicio').'</td>');
      ShowHTML('              <td><b><u>F</u>im vigência:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_fim').'</td>');
      if (substr($SG,0,3)!='GCA' && nvl($w_herda,'')=='') {
        ShowHTML('              <td><b>Valo<u>r</u>:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total real ou estimado."></td>');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_valor" value="0,00">');
      }
    } else {
      ShowHTML('        <tr valign="top">');
      ShowHTML('              <td><b>Início vigência:</b><br>'.$w_inicio.'</td>');
      ShowHTML('              <td><b>Fim vigência:</b><br>'.$w_fim.'</td>');
      ShowHTML('              <td><b>Valor:</b><br>'.$w_valor.'</td>');
      ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.$w_inicio.'">');
      ShowHTML('<INPUT type="hidden" name="w_fim" value="'.$w_fim.'">');
      ShowHTML('<INPUT type="hidden" name="w_valor" value="'.$w_valor.'">');
    }
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    SelecaoPessoa('<u>G</u>estor do contrato:','G','Selecione o gestor do contrato.',$w_solicitante,null,'w_solicitante','USUARIOS');
    SelecaoUnidade('<U>S</U>etor responsável monitoramento:','S','Selecione o setor responsável pelo monitoramento.',$w_sq_unidade_resp,null,'w_sq_unidade_resp',null,null);
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Vinculação</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    
    ShowHTML('          <tr valign="top">');
    selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($w_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($w_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null);
      } else {
        if(f($RS_Relac,'sg_modulo')=='PR' && $w_cliente != '10135') {
          SelecaoSolic('Vinculação:', null, null, $w_cliente, $w_chave_pai, $w_sq_menu_relac, f($RS_Menu, 'sq_menu'), 'w_chave_pai', f($RS_Relac, 'sigla'), 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_etapa\'; document.Form.submit();"');
          ShowHTML('      <tr>');
          if (substr(f($RS_Menu, 'sigla'), 2) == 'B')
            SelecaoEtapa('<u>T</u>ema e modalidade:', 'T', 'Se necessário, indique a etapa desejada para a vinculação.', $w_etapa, $w_chave_pai, null, 'w_etapa', 'CONTRATO', null);
          else
            SelecaoEtapa('E<u>t</u>apa:', 'T', 'Se necessário, indique a etapa desejada para a vinculação.', $w_etapa, $w_chave_pai, null, 'w_etapa', 'CONTRATO', null);
          ShowHTML('      </tr>');
        } else {
          SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_chave_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_chave_pai',f($RS_Relac,'sigla'),null);
        }
      }
    }    

/**
*     if (substr($SG,0,3)=='GCB') {
*       ShowHTML('      <tr><td>Selecione uma forma de vinculação.</td></tr>');
*       ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
*       // Recupera dados da opção Projetos
*       ShowHTML('      <tr>');
*       $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCADBOLSA');
*       SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto que deseja a vinculação.',$w_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'w_projeto',f($RS_Menu,'sq_menu'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_etapa\'; document.Form.submit();"');
*       ShowHTML('      </tr>');
*       ShowHTML('      <tr>');
*       SelecaoEtapa('<u>T</u>ema e modalidade:','T','Se necessário, indique a etapa desejada para a vinculação.',$w_etapa,$w_projeto,null,'w_etapa','CONTRATO',null);
*       ShowHTML('      </tr>');
*     } elseif (Nvl($w_cd_modalidade,'F')!='F') {
*       ShowHTML('      <tr><td>Selecione uma forma de vinculação.</td></tr>');
*       ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
*       // Recupera dados da opção Projetos
*       ShowHTML('      <tr>');
*       $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
*       $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,substr($SG,0,3).'CAD');
*       SelecaoProjeto('Pr<u>o</u>jeto:','P','Selecione o projeto que deseja para a vinculação.',$w_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'w_projeto',f($RS1,'sq_menu'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_etapa\'; document.Form.submit();"');
*       ShowHTML('      </tr>');
*       ShowHTML('      <tr>');
*       SelecaoEtapa('<u>E</u>tapa:','E','Se necessário, indique a etapa desejada para a vinculação.',$w_etapa,$w_projeto,null,'w_etapa','CONTRATO',null);
*       ShowHTML('      </tr>');
*     } else {
*       ShowHTML('      <tr><td>Classifique o acordo perante uma das opções exibidas na lista.</font></td></tr>');
*       ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
*     }
*     if (f($RS_Menu,'solicita_cc')=='S') {
*       ShowHTML('          <tr>');
*       SelecaoCC('C<u>l</u>assificação:','L','Selecione um dos itens relacionados.',$w_sqcc,null,'w_sqcc','SIWSOLIC');
*       ShowHTML('          </tr>');
*     } 
*/
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Local do Fornecimento ou Prestação do Serviço</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Selecione país, estado e cidade onde os serviços serão prestados ou onde deverá ocorrer a entrega de produtos. Se mais de uma cidade, selecione a cidade que controlará os serviços ou fornecimentos.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    SelecaoPais('<u>P</u>aís:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
    ShowHTML('          </table>');
    if (f($RS_Menu,'descricao')=='S' || f($RS_Menu,'justificativa')=='S') {
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Informações adicionais</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td>Os dados deste bloco visam orientar os responsáveis pelo monitoramento.</td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      if (f($RS_Menu,'descricao')=='S') {
        ShowHTML('      <tr><td><b>Res<u>u</u>ltados esperados:</b><br><textarea '.$w_Disabled.' accesskey="U" name="w_descricao" class="sti" ROWS=5 cols=75 title="Descreva os resultados esperados com a contratação.">'.$w_descricao.'</TEXTAREA></td>');
      } 
      if (f($RS_Menu,'justificativa')=='S') {
        ShowHTML('      <tr><td><b>Obse<u>r</u>vações:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_justificativa" class="sti" ROWS=5 cols=75 >'.$w_justificativa.'</TEXTAREA></td>');
      } 
    } 
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Alerta de proximidade da data de término</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados abaixo indicam como deve ser tratada a proximidade do final da vigência.</font></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><table border="0" width="100%">');
    ShowHTML('          <tr>');
    MontaRadioNS('<b>Emite alerta?</b>',$w_aviso,'w_aviso');
    ShowHTML('              <td><b>Quantos <U>d</U>ias antes do fim da vigência?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="Número de dias para emissão do alerta de proximidade do final da vigência."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
      $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.'Inicial&w_copia='.$w_copia.'&w_herda='.$w_herda.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
// Rotina de termo de referência
// -------------------------------------------------------------------------
function Termo() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave          = $_REQUEST['w_chave'];
  $w_sq_tipo_acordo = $_REQUEST['w_sq_tipo_acordo'];
  $w_readonly       = '';
  $w_erro           = '';

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_codigo_externo   = $_REQUEST['w_codigo_externo'];
    $w_atividades       = $_REQUEST['w_atividades'];
    $w_produtos         = $_REQUEST['w_produtos'];
    $w_requisitos       = $_REQUEST['w_requisitos'];
    $w_vincula_projeto  = $_REQUEST['w_vincula_projeto'];
    $w_vincula_demanda  = $_REQUEST['w_vincula_demanda'];
    $w_vincula_viagem   = $_REQUEST['w_vincula_viagem'];

    $w_chave=$_REQUEST['w_chave'];
    $w_sq_menu=$_REQUEST['w_sq_menu'];
  } else {
    if (strpos('AEV',$O)!==false) {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
      if (count($RS)>0) {
        $w_codigo_externo   = f($RS,'codigo_externo');
        $w_atividades       = f($RS,'atividades');
        $w_produtos         = f($RS,'produtos');
        $w_requisitos       = f($RS,'requisitos');
        $w_vincula_projeto  = f($RS,'vincula_projeto');
        $w_vincula_demanda  = f($RS,'vincula_demanda');
        $w_vincula_viagem   = f($RS,'vincula_viagem');
        $w_sq_tipo_acordo   = f($RS,'sq_tipo_acordo');
        $w_sq_menu          = f($RS,'sq_menu');
      } 
    } 
  } 
  if (Nvl($w_sq_tipo_acordo,0)>0) {
    $sql = new db_getAgreeType; $RS = $sql->getInstanceOf($dbms,$w_sq_tipo_acordo,null,$w_cliente,null,null,substr($SG,0,3).'GERAL');
    foreach($RS as $row) {
      $w_cd_modalidade = f($row,'modalidade');
      break;
    }
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
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_atividades','Atividades','1','',5,2000,'1','1');
    if (substr($SG,0,3)!='GCA') {
      Validate('w_produtos','Produtos','1','',5,2000,'1','1');
      Validate('w_requisitos','Qualificação','1','',5,2000,'1','1');
    } else {
      Validate('w_produtos','Produtos','1','',5,2000,'1','1');
      Validate('w_requisitos','Requisitos','1','',5,2000,'1','1');
    }
    Validate('w_codigo_externo','Código externo','1','',2,60,'1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpenClean('onLoad="this.focus()";');
  } else {
    BodyOpenClean('onLoad="document.Form.w_atividades.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Especificação dos produtos ou serviços</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    if (substr($SG,0,3)=='GCB') ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para especificação dos produtos ou serviços acordados com o bolsista.</td></tr>');
    else                        ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para especificação dos produtos ou serviços acordados com a outra parte.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');

    ShowHTML('      <tr><td><b><u>A</u>tividades a serem desenvolvidas:</b><br><textarea '.$w_Disabled.' accesskey="A" name="w_atividades" class="sti" ROWS=5 cols=75 title="Descreva as atividades a serem desenvolvidas.">'.$w_atividades.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b><u>P</u>rodutos a serem entregues:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_produtos" class="sti" ROWS=5 cols=75 title="Relacione os produtos a serem entregues.">'.$w_produtos.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>Documentação vincu<u>l</u>ada:</b><br><textarea '.$w_Disabled.' accesskey="L" name="w_requisitos" class="sti" ROWS=5 cols=75 title="Relacione as Qualificações a serem cumpridos para contratação.">'.$w_requisitos.'</TEXTAREA></td>');

    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Informações adicionais</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    if (substr($SG,0,3)=='GCB') ShowHTML('      <tr><td>Os dados deste bloco permitem a identificação pelo bolsista e configuram as possibilidades de vinculação com outros tipos de documento.</td></tr>');
    else                        ShowHTML('      <tr><td>Os dados deste bloco permitem a identificação pela outra parte e configuram as possibilidades de vinculação com outros tipos de documento.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    if (substr($SG,0,3)=='GCB') ShowHTML('      <tr><td><b><u>C</u>ódigo para o bolsista:</b><br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_codigo_externo" size="60" maxlength="60" value="'.$w_codigo_externo.'" title="Informe, se desejar, o código pelo qual este acordo é reconhecido pelo bolsista."></td>');
    else                        ShowHTML('      <tr><td><b><u>C</u>ódigo para a outra parte:</b><br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_codigo_externo" size="60" maxlength="60" value="'.$w_codigo_externo.'" title="Informe, se desejar, o código pelo qual este acordo é reconhecido pela outra parte."></td>');
    if (Nvl($w_cd_modalidade,'')=='F') {
      ShowHTML('          <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
      MontaRadioNS('<b>Pemite vinculação de projetos?</b>',$w_vincula_projeto,'w_vincula_projeto');
      MontaRadioNS('<b>Pemite vinculação de demandas?</b>',$w_vincula_demanda,'w_vincula_demanda');
      MontaRadioNS('<b>Pemite vinculação de viagem?</b>',$w_vincula_viagem,'w_vincula_viagem');
      ShowHTML('      </tr></table>');
    } 

    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
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
    ShowHTML(' alert("Opção não disponível");');
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
// Rotina de dados adicionais
// -------------------------------------------------------------------------
function DadosAdicionais() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave          = $_REQUEST['w_chave'];
  $w_sq_tipo_acordo = $_REQUEST['w_sq_tipo_acordo'];
  $w_readonly       = '';
  $w_erro           = '';

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_numero_certame     = $_REQUEST['w_numero_certame'];
    $w_numero_ata         = $_REQUEST['w_numero_ata'];
    $w_tipo_reajuste      = $_REQUEST['w_tipo_reajuste'];
    $w_limite_variacao    = $_REQUEST['w_limite_variacao'];
    $w_indice_base        = $_REQUEST['w_indice_base'];
    $w_sq_eoindicador     = $_REQUEST['w_sq_eoindicador'];
    $w_sq_lcfonte_recurso = $_REQUEST['w_sq_lcfonte_recurso'];
    $w_espec_despesa      = $_REQUEST['w_espec_despesa'];
    $w_sq_lcmodalidade    = $_REQUEST['w_sq_lcmodalidade'];
    $w_data_assinatura    = $_REQUEST['w_data_assinatura'];
    $w_data_publicacao    = $_REQUEST['w_data_publicacao'];
    $w_pagina_diario      = $_REQUEST['w_pagina_diario'];
    $w_financeiro_unico   = $_REQUEST['w_financeiro_unico'];
    $w_texto_pagamento    = $_REQUEST['w_texto_pagamento'];
    $w_valor_caucao       =  $_REQUEST['w_valor_caucao'];

    $w_chave   = $_REQUEST['w_chave'];
    $w_sq_menu = $_REQUEST['w_sq_menu'];
  } else {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    $w_numero_certame     = f($RS,'numero_certame');
    $w_numero_ata         = f($RS,'numero_ata');
    $w_tipo_reajuste      = f($RS,'tipo_reajuste');
    $w_limite_variacao    = formatNumber(f($RS,'limite_variacao'));
    $w_indice_base        = f($RS,'indice_base');
    $w_sq_eoindicador     = f($RS,'sq_eoindicador');
    $w_sq_lcfonte_recurso = f($RS,'sq_lcfonte_recurso');
    $w_espec_despesa      = f($RS,'sq_especificacao_despesa');
    $w_sq_lcmodalidade    = f($RS,'sq_lcmodalidade');
    $w_data_assinatura    = FormataDataEdicao(f($RS,'assinatura'));
    $w_data_publicacao    = FormataDataEdicao(f($RS,'publicacao'));
    $w_pagina_diario      = f($RS,'pagina_diario_oficial');
    $w_financeiro_unico   = f($RS,'financeiro_unico');
    $w_sq_menu            = f($RS,'sq_menu');
    $w_texto_pagamento    = f($RS,'condicoes_pagamento');
    $w_valor_caucao       = formatNumber(f($RS,'valor_caucao'));
  } 

  if (nvl($w_sq_cc,'')=='') {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    $w_sq_cc              = f($RS,'sq_cc');
  }
  
  if (Nvl($w_sq_tipo_acordo,0)>0) {
    $sql = new db_getAgreeType; $RS = $sql->getInstanceOf($dbms,$w_sq_tipo_acordo,null,$w_cliente,null,null,substr($SG,0,3).'GERAL');
    foreach($RS as $row) {
      $w_cd_modalidade = f($row,'modalidade');
      break;
    }
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataValor();
  FormataData();
  SaltaCampo();
  FormataDataMA();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    if (substr($SG,0,3)=='GCD' || substr($SG,0,3)=='GCZ') Validate('w_sq_lcmodalidade','Modalidade','SELECT','1',1,18,'','0123456789');
    Validate('w_numero_certame','Numero do certame','1','1',1,50,'1','1');
    if (substr($SG,0,3)!='GCZ') {
      if (f($RS_Cliente,'ata_registro_preco')=='S') Validate('w_numero_ata','Número da ata','1','',1,30,'1','1');
      Validate('w_tipo_reajuste','Tipo de reajuste','SELECT','1',1,18,'','0123456789');
      if($w_tipo_reajuste==1) {
        Validate('w_indice_base','Índice base','DATAMA','1',1,7,'1','1');
        Validate('w_sq_eoindicador','Índice de reajuste','SELECT','1',1,18,'','0123456789');
      }
      Validate('w_limite_variacao','Limite de acréscimo/supressão','VALOR','1',4,18,'','0123456789.,');
      if ($w_segmento=='Público') {
        Validate('w_sq_lcfonte_recurso','Fonte de recurso','SELECT','1',1,18,'','0123456789');
        Validate('w_espec_despesa','Especificação de despesa','SELECT','1',1,18,'','0123456789');
      }
    }
    Validate('w_data_assinatura','Data Assinatura','DATA',1,10,10,'','0123456789/');
    if ($w_segmento=='Público' && substr($SG,0,3)!='GCB') {
      Validate('w_data_publicacao','Data Publicação','DATA',1,10,10,'','0123456789/'); 
      Validate('w_pagina_diario','Número da página do D.O.','1','',1,4,'','0123456789');
    }
    Validate('w_valor_caucao','Valor da caução','VALOR','1',4,18,'','0123456789.,');
    Validate('w_texto_pagamento','Condições de pagamento','1',1,2,4000,'1','0123456789');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpenClean(null);
  } else {
    BodyOpenClean('onLoad="document.Form.w_numero_certame.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('<tr valign="top">');
    if (substr($SG,0,3)=='GCD' || substr($SG,0,3)=='GCZ') SelecaoLCModalidade('<u>M</u>odalidade:','M','Selecione na lista a modalidade do contrato.',$w_sq_lcmodalidade,null,'w_sq_lcmodalidade',null,null);
    ShowHTML('          <td><b><u>N</u>úmero do certame:</b><br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_numero_certame" size="30" maxlength="50" value="'.$w_numero_certame.'" title="Número do certame licitatório que originou o contrato."></td>');
    if (substr($SG,0,3)!='GCZ') {
      if (f($RS_Cliente,'ata_registro_preco')=='S') ShowHTML('          <td><b>N<u>ú</u>mero da ata:</b><br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="sti" type="text" name="w_numero_ata" size="30" maxlength="30" value="'.$w_numero_ata.'" title="Número da ata de registro de preços que originou o contrato."></td>');
      ShowHTML('<tr valign="top">');
      SelecaoTipoReajuste('<u>T</u>ipo de reajuste:','T','Indica o tipo de reajuste do contrato.',$w_tipo_reajuste,null,'w_tipo_reajuste',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_tipo_reajuste\'; document.Form.submit();"');
      if($w_tipo_reajuste==1) {
        ShowHTML('          <td><b>Ín<u>d</u>ice base:</b><br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_indice_base" size="7" maxlength="7" value="'.$w_indice_base.'" onKeyDown="FormataDataMA(this,event);" onKeyUp="SaltaCampo(this.form.name,this,7,event);" title="Registra mês e ano (MM/AAAA) do índice origina, quando o acordo permitir reajuste em índices."></td>');
        selecaoIndicador('<U>I</U>ndicador:','I','Selecione o indicador',$w_sq_eoindicador,null,$w_usuario,null,'w_sq_eoindicador',null,null);
      }
      ShowHTML('      <tr><td><b><u>L</u>imite de acréscimo/supressão (%):</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_limite_variacao" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_limite_variacao.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Percentual para indicar o limite de acréscimo ou supressão no valor original."></td>');
      if ($w_segmento=='Público') {
        ShowHTML('<tr valign="top">');
        selecaoLCFonteRecurso('<U>F</U>onte de recurso:','F','Selecione o a fonte de recurso',$w_sq_lcfonte_recurso,null,'w_sq_lcfonte_recurso',null,null);
        selecaoCTEspecificacao('<u>E</u>specificação de despesa:','E','Selecione a especificação de despesa.',$w_espec_despesa,$w_espec_despesa,$w_sq_cc,$_SESSION['ANO'],'w_espec_despesa','S',null,null);
      }
      ShowHTML('<tr valign="top">');
    } else {
      // ARP tem variação de 200%
      ShowHTML('<INPUT type="hidden" name="w_limite_variacao" value="200,00">');
      // ARP não tem reajuste
      ShowHTML('<INPUT type="hidden" name="w_tipo_reajuste" value="0">');
    }
    ShowHTML('<tr valign="top">');
    ShowHTML('          <td><b><u>A</u>ssinatura do contrato:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_data_assinatura" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_assinatura.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_assinatura').'</td>');
    if ($w_segmento=='Público' && substr($SG,0,3)!='GCB') {
      ShowHTML('          <td><b><u>P</u>ublicação D.O.:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_data_publicacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_publicacao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_publicacao').'</td>');
      ShowHTML('          <td><b>Nú<u>m</u>ero da página do D.O.:</b><br><INPUT ACCESSKEY="M" '.$w_Disabled.' class="sti" type="text" name="w_pagina_diario" size="20" maxlength="20" value="'.$w_pagina_diario.'" title="Número da página do D.O."></td>');
    }
    ShowHTML('<tr valign="top">');
    if (substr($SG,0,3)!='GCZ') {
      MontaRadioSN('<b>Parcela paga em uma única liquidação?</b>',$w_financeiro_unico,'w_financeiro_unico',null,null,null);
    } else {
      // ARP não tem financeiro
      ShowHTML('<INPUT type="hidden" name="w_financeiro_unico" value="N">');
    }
    ShowHTML('      <tr><td><b><u>V</u>alor da caução:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_caucao" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_caucao.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Se necessário, Informe o valor da caução do contrato."></td>');
    ShowHTML('        <tr><td colspan=2><b><u>C</u>ondições para pagamento das parcelas:</b><br><textarea '.$w_Disabled.'accesskey="T" name="w_texto_pagamento" class="sti" ROWS="3" COLS="75" title="Condições para pagamento das parcelas.">'.nvl($w_texto_pagamento,$w_padrao_pagamento).'</textarea></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
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
    ShowHTML(' alert("Opção não disponível");');
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
  if ($O=='') $O = 'P';
  $w_erro                   = '';
  $w_troca                  = $_REQUEST['w_troca'];
  $w_chave                  = $_REQUEST['w_chave'];
  $w_chave_aux              = $_REQUEST['w_chave_aux'];
  $w_cpf                    = $_REQUEST['w_cpf'];
  $w_cnpj                   = $_REQUEST['w_cnpj'];
  $w_pessoa_atual           = $_REQUEST['w_pessoa_atual'];
  $w_sq_pessoa              = $_REQUEST['w_sq_pessoa'];
  $w_sq_acordo_outra_parte  = $_REQUEST['w_sq_acordo_outra_parte'];
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  if ($w_sq_pessoa=='' && (strpos($_REQUEST['Botao'],'Selecionar')===false)) {
    $w_pessoa_atual     = f($RS,'outra_parte');
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
  $w_forma_pagamento    = f($RS,'sg_forma_pagamento');
  $w_sq_tipo_pessoa     = f($RS,'sq_tipo_pessoa');
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_chave                  = $_REQUEST['w_chave'];
    $w_chave_aux              = $_REQUEST['w_chave_aux'];
    $w_nome                   = $_REQUEST['w_nome'];
    $w_nome_resumido          = $_REQUEST['w_nome_resumido'];
    $w_sq_pessoa_pai          = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa         = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo        = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo        = $_REQUEST['w_nm_tipo_vinculo'];
    $w_sq_banco               = $_REQUEST['w_sq_banco'];
    $w_sq_agencia             = $_REQUEST['w_sq_agencia'];
    $w_operacao               = $_REQUEST['w_operacao'];
    $w_nr_conta               = $_REQUEST['w_nr_conta'];
    $w_sq_pais_estrang        = $_REQUEST['w_sq_pais_estrang'];
    $w_aba_code               = $_REQUEST['w_aba_code'];
    $w_swift_code             = $_REQUEST['w_swift_code'];
    $w_endereco_estrang       = $_REQUEST['w_endereco_estrang'];
    $w_banco_estrang          = $_REQUEST['w_banco_estrang'];
    $w_agencia_estrang        = $_REQUEST['w_agencia_estrang'];
    $w_cidade_estrang         = $_REQUEST['w_cidade_estrang'];
    $w_informacoes            = $_REQUEST['w_informacoes'];
    $w_codigo_deposito        = $_REQUEST['w_codigo_deposito'];
    $w_interno                = $_REQUEST['w_interno'];
    $w_vinculo_ativo          = $_REQUEST['w_vinculo_ativo'];
    $w_sq_pessoa_telefone     = $_REQUEST['w_sq_pessoa_telefone'];
    $w_ddd                    = $_REQUEST['w_ddd'];
    $w_nr_telefone            = $_REQUEST['w_nr_telefone'];
    $w_sq_pessoa_celular      = $_REQUEST['w_sq_pessoa_celular'];
    $w_nr_celular             = $_REQUEST['w_nr_celular'];
    $w_sq_pessoa_fax          = $_REQUEST['w_sq_pessoa_fax'];
    $w_nr_fax                 = $_REQUEST['w_nr_fax'];
    $w_email                  = $_REQUEST['w_email'];
    $w_sq_pessoa_endereco     = $_REQUEST['w_sq_pessoa_endereco'];
    $w_logradouro             = $_REQUEST['w_logradouro'];
    $w_complemento            = $_REQUEST['w_complemento'];
    $w_bairro                 = $_REQUEST['w_bairro'];
    $w_cep                    = $_REQUEST['w_cep'];
    $w_sq_cidade              = $_REQUEST['w_sq_cidade'];
    $w_co_uf                  = $_REQUEST['w_co_uf'];
    $w_sq_pais                = $_REQUEST['w_sq_pais'];
    $w_pd_pais                = $_REQUEST['w_pd_pais'];
    $w_cpf                    = $_REQUEST['w_cpf'];
    $w_nascimento             = $_REQUEST['w_nascimento'];
    $w_rg_numero              = $_REQUEST['w_rg_numero'];
    $w_rg_emissor             = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao             = $_REQUEST['w_rg_emissao'];
    $w_passaporte_numero      = $_REQUEST['w_passaporte_numero'];
    $w_sq_pais_passaporte     = $_REQUEST['w_sq_pais_passaporte'];
    $w_sexo                   = $_REQUEST['w_sexo'];
    $w_cnpj                   = $_REQUEST['w_cnpj'];
    $w_inscricao_estadual     = $_REQUEST['w_inscricao_estadual'];
    $w_sq_acordo_outra_parte  = $_REQUEST['w_sq_acordo_outra_parte']; 
  } elseif ($O=='L') {
      // Recupera a listatem de outras partes do contrato
      $sql = new db_getConvOutraParte; $RS1 = $sql->getInstanceOf($dbms,null,$w_chave,null,null);
      if (nvl($p_ordena,'')>'') {
        $lista = explode(',',str_replace(' ',',',$p_ordena));
        $RS1 = SortArray($RS1,$lista[0],$lista[1],'inicio','asc');
      } else {
        $RS1 = SortArray($RS1,'outra_parte','asc','inicio','desc');
      }
  } elseif ((strpos($_REQUEST['Botao'],'Alterar')===false) && (strpos($_REQUEST['Botao'],'Procurar')===false) && ($O=='A' || $w_sq_pessoa>'' || $w_cpf>'' || $w_cnpj>'')) {
    // Recupera os dados do beneficiário em co_pessoa
    $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,$w_cpf,$w_cnpj,null,null,null,null,null,null,null,null,null, null, null, null, null);
    if (count($RS)>0) {
      foreach($RS as $row) {
        $w_sq_pessoa            = f($row,'sq_pessoa');
        $w_nome                 = f($row,'nm_pessoa');
        $w_nome_resumido        = f($row,'nome_resumido');
        $w_sq_pessoa_pai        = f($row,'sq_pessoa_pai');
        $w_nm_tipo_pessoa       = f($row,'nm_tipo_pessoa');
        $w_sq_tipo_vinculo      = f($row,'sq_tipo_vinculo');
        $w_nm_tipo_vinculo      = f($row,'nm_tipo_vinculo');
        $w_interno              = f($row,'interno');
        $w_vinculo_ativo        = f($row,'vinculo_ativo');
        $w_sq_pessoa_telefone   = f($row,'sq_pessoa_telefone');
        $w_ddd                  = f($row,'ddd');
        $w_nr_telefone          = f($row,'nr_telefone');
        $w_sq_pessoa_celular    = f($row,'sq_pessoa_celular');
        $w_nr_celular           = f($row,'nr_celular');
        $w_sq_pessoa_fax        = f($row,'sq_pessoa_fax');
        $w_nr_fax               = f($row,'nr_fax');
        $w_email                = f($row,'email');
        $w_sq_pessoa_endereco   = f($row,'sq_pessoa_endereco');
        $w_logradouro           = f($row,'logradouro');
        $w_complemento          = f($row,'complemento');
        $w_bairro               = f($row,'bairro');
        $w_cep                  = f($row,'cep');
        $w_sq_cidade            = f($row,'sq_cidade');
        $w_co_uf                = f($row,'co_uf');
        $w_sq_pais              = f($row,'sq_pais');
        $w_pd_pais              = f($row,'pd_pais');
        $w_cpf                  = f($row,'cpf');
        $w_nascimento           = FormataDataEdicao(f($row,'nascimento'));
        $w_rg_numero            = f($row,'rg_numero');
        $w_rg_emissor           = f($row,'rg_emissor');
        $w_rg_emissao           = FormataDataEdicao(f($row,'rg_emissao'));
        $w_passaporte_numero    = f($row,'passaporte_numero');
        $w_sq_pais_passaporte   = f($row,'sq_pais_passaporte');
        $w_sexo                 = f($row,'sexo');
        $w_cnpj                 = f($row,'cnpj');
        $w_inscricao_estadual   = f($row,'inscricao_estadual');
        if (strpos('CREDITO,DEPOSITO',$w_forma_pagamento)!==false) {
          if (Nvl($w_nr_conta,'')=='') {
            $w_sq_banco     = f($row,'sq_banco');
            $w_sq_agencia   = f($row,'sq_agencia');
            $w_operacao     = f($row,'operacao');
            $w_nr_conta     = f($row,'nr_conta');
          } 
        } 
        break;
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
    ShowHTML('} else {');
    if ($w_sq_tipo_pessoa==1) {
      Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
    } else {
      Validate('w_cnpj','CNPJ','CNPJ','1','18','18','','0123456789/-.');
    } 
    ShowHTML('  theForm.w_sq_pessoa.value = "";');
    ShowHTML('}');
  } elseif ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.w_troca.value.indexOf("Alterar") >= 0) { return true; }');
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
    if ($w_sq_tipo_pessoa==1) {
      Validate('w_nascimento','Data de Nascimento','DATA','',10,10,'',1);
      Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
      Validate('w_rg_numero','Identidade','1',1,2,30,'1','1');
      Validate('w_rg_emissor','Órgão expedidor','1',1,2,30,'1','1');
      Validate('w_rg_emissao','Data de emissão','DATA','',10,10,'','0123456789/');
      Validate('w_passaporte_numero','Passaporte','1','',1,20,'1','1');
      Validate('w_sq_pais_passaporte','País emissor','SELECT','',1,10,'1','1');
    } else {
      Validate('w_inscricao_estadual','Inscrição estadual','1','',2,20,'1','1');
    } 
    Validate('w_ddd','DDD','1','1',2,4,'','0123456789');
    Validate('w_nr_telefone','Telefone','1',1,7,25,'1','1');
    Validate('w_nr_fax','Fax','1','',7,25,'1','1');
    Validate('w_nr_celular','Celular','1','',7,25,'1','1');
    Validate('w_logradouro','Endereço','1',1,4,60,'1','1');
    Validate('w_complemento','Complemento','1','',2,20,'1','1');
    Validate('w_bairro','Bairro','1','',2,30,'1','1');
    Validate('w_sq_pais','País','SELECT',1,1,10,'1','1');
    Validate('w_co_uf','UF','SELECT',1,1,10,'1','1');
    Validate('w_sq_cidade','Cidade','SELECT',1,1,10,'','1');
    if (Nvl($w_pd_pais,'S')=='S') {
      Validate('w_cep','CEP','1','',9,9,'','0123456789-');
    } else {
      Validate('w_cep','CEP','1',1,5,9,'','0123456789');
    } 
    Validate('w_email','E-Mail','1','',4,60,'1','1');
    if (substr($SG,0,3)!='GCR' && substr($SG,0,3)!='GCZ') {
      if (strpos('CREDITO,DEPOSITO',$w_forma_pagamento)!==false) {
        if (substr($SG,0,3)=='GCD'||(substr($SG,0,3)=='GCC')||(substr($SG,0,3)=='GCB')) {
          Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
          Validate('w_sq_agencia','Agencia','SELECT',1,1,10,'1','1');
          if ($w_exige_operacao=='S') Validate('w_operacao','Operação','1','1',1,6,'','0123456789');
          Validate('w_nr_conta','Número da conta','1','1',2,30,'ZXAzxa','0123456789-.');
        } elseif ((substr($SG,0,3)=='GCP')) {
          Validate('w_sq_banco','Banco','SELECT',1,'',10,'1','1');
          Validate('w_sq_agencia','Agencia','SELECT',1,'',10,'1','1');
          if ($w_exige_operacao=='S') Validate('w_operacao','Operação','1','1',1,6,'','0123456789');
          Validate('w_nr_conta','Número da conta','1','',2,30,'ZXAzxa','0123456789-.');
          ShowHTML('  if (!(theForm.w_sq_banco.selectedIndex == 0 && theForm.w_sq_agencia.selectedIndex == 0 && theForm.w_nr_conta == "")) {');
          ShowHTML('     if (theForm.w_sq_banco.selectedIndex == 0 || theForm.w_sq_agencia.selectedIndex == 0 || theForm.w_nr_conta == "") {');
          ShowHTML('        alert("Informe todos os dados bancários ou nenhum deles!");');
          ShowHTML('        document.Form.w_sq_banco.focus();');
          ShowHTML('        return false;');
          ShowHTML('     }');
          ShowHTML('  }');
        }  
      } elseif ($w_forma_pagamento=='ORDEM') {
        Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
        Validate('w_sq_agencia','Agencia','SELECT',1,1,10,'1','1');
      } elseif ($w_forma_pagamento=='EXTERIOR') {
        Validate('w_banco_estrang','Banco de destino','1','1',1,60,1,1);
        Validate('w_aba_code','Código ABA','1','',1,12,1,1);
        Validate('w_swift_code','Código SWIFT','1','',1,30,'',1);
        Validate('w_endereco_estrang','Endereço da agência destino','1','',3,100,1,1);
        ShowHTML('  if (theForm.w_aba_code.value == "" && theForm.w_swift_code.value == "" && theForm.w_endereco_estrang.value == "") {');
        ShowHTML('     alert("Informe código ABA, código SWIFT ou endereço da agência!");');
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
    ShowHTML('  theForm.Botao.disabled=true;');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($O=='L') {
    BodyOpen('null');
  } elseif (($w_cpf=='' && $w_cnpj=='') || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
    // Se o beneficiário ainda não foi selecionado
    if (strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se está sendo feita busca por nome
      BodyOpenClean('onLoad="this.focus()";');
    } else {
      if ($w_sq_tipo_pessoa==1) {
        BodyOpenClean('onLoad="document.Form.w_cpf.focus()";');
      } else {
        BodyOpenClean('onLoad="document.Form.w_cnpj.focus()";');
      } 
    } 
  } elseif ($w_troca>'') {
    BodyOpenClean('onLoad="document.Form.'.$w_troca.'.focus()";');
  } else {
    BodyOpenClean('onLoad="document.Form.w_nome.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS1));
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>'.LinkOrdena('Nome','nm_pessoa').'</font></td>');
        ShowHTML('          <td><b>'.LinkOrdena('Nome resumido','nome_resumido').'</font></td>');
        ShowHTML('          <td><b>CPF/CNPJ</font></td>');
        ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo').'</font></td>');
        ShowHTML('          <td class="remover"><b>Operações</font></td>');
        ShowHTML('        </tr>');
        if (count($RS1)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
        } else {
          foreach($RS1 as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            if (f($row,'sq_tipo_pessoa')==1) {
              ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
            } else {
              ShowHTML('        <td align="center" nowrap>'.Nvl(f($row,'cnpj'),'---').'</td>');
            } 
            ShowHTML('        <td>'.Nvl(f($row,'nm_tipo'),'---').'</td>');
            ShowHTML('        <td class="remover" nowrap>');
            if (f($row,'sq_tipo_pessoa')==1) {
              //ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_acordo_outra_parte='.f($row,'sq_acordo_outra_parte').'&w_chave_aux='.$w_cliente.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
            } else {
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'REPRESENTANTE&R='.$R.'&O=L&w_sq_acordo_outra_parte='.f($row,'sq_acordo_outra_parte').'&w_outra_parte='.f($row,'outra_parte').'&w_tipo=PREPOSTO&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Representante legal').'&SG=GCCPREP\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');"Botao=Selecionar">Rep. Legal</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'REPRESENTANTE&R='.$R.'&O=L&w_sq_acordo_outra_parte='.f($row,'sq_acordo_outra_parte').'&w_outra_parte='.f($row,'outra_parte').'&w_tipo=REPRESENTANTE&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Contato').'&SG=GCCREPRES\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');"Botao=Selecionar">Contato</A>&nbsp');
            }
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_sq_acordo_outra_parte='.f($row,'sq_acordo_outra_parte').'&w_chave_aux='.$w_cliente.'&w_sq_pessoa='.f($row,'outra_parte').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_sq_acordo_outra_parte='.f($row,'sq_acordo_outra_parte').'&w_chave_aux='.$w_cliente.'&w_sq_pessoa='.f($row,'outra_parte').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          } 
        } 
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
  } elseif (strpos('IA',$O)!==false) {
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
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="3">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_outra_parte" value="'.$w_sq_acordo_outra_parte.'">');

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
      if ($w_sq_tipo_pessoa==1) {
        ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      } else {
        ShowHTML('        <tr><td colspan=4><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cnpj" VALUE="'.$w_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
      } 

      ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
      ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
      ShowHTML('        <tr><td colspan=4>');
      ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
      ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('      </table>');
      if ($w_nome>'') {
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,$w_nome,$w_sq_tipo_pessoa,null,null,null,null,null,null,null, null, null, null, null);
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Nome</font></td>');
        ShowHTML('          <td><b>Nome resumido</font></td>');
        if ($w_sq_tipo_pessoa==1) {
          ShowHTML('          <td><b>CPF</font></td>');
        } else {
          ShowHTML('          <td><b>CNPJ</font></td>');
        } 

        ShowHTML('          <td><b>Operações</font></td>');
        ShowHTML('        </tr>');
        if (count($RS)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não há pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            if ($w_sq_tipo_pessoa==1) {
              ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
            } else {
              ShowHTML('        <td align="center">'.Nvl(f($row,'cnpj'),'---').'</td>');
            } 
            ShowHTML('        <td nowrap>');
            if ($w_sq_tipo_pessoa==1) {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=I&w_chave_aux='.$w_chave_aux.'&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            } else {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=I&w_cnpj='.f($row,'cnpj').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            } 
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
      if ($w_sq_tipo_pessoa==1) {
        ShowHTML('          <td>CPF:</font><br><b><font size=2>'.$w_cpf);
        ShowHTML('              <INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
      } else {
        ShowHTML('          <td colspan="2">CNPJ:</font><br><b><font size=2>'.$w_cnpj);
        ShowHTML('              <INPUT type="hidden" name="w_cnpj" value="'.$w_cnpj.'">');
      } 
      ShowHTML('          <tr valign="top">');
      ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
      if ($w_sq_tipo_pessoa==1) {
        ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr valign="top">');
        SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
        ShowHTML('          <td><b>Da<u>t</u>a de nascimento:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nascimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_nascimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
        ShowHTML('          <tr valign="top">');
        ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
        ShowHTML('          <td><b>Ór<u>g</u>ão emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
        ShowHTML('          <td><b>Data de <u>e</u>missão:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');        
        ShowHTML('          <tr valign="top">');
        ShowHTML('          <td><b>Passapo<u>r</u>te:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_passaporte_numero" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_passaporte_numero.'"></td>');
        SelecaoPais('<u>P</u>aís emissor do passaporte:','P',null,$w_sq_pais_passaporte,null,'w_sq_pais_passaporte',null,null);
        ShowHTML('          </table>');
      } else {
        ShowHTML('      <tr><td><b><u>I</u>nscrição estadual:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inscricao_estadual" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_inscricao_estadual.'"></td>');
      } 
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      if ($w_sq_tipo_pessoa==1) {
        ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Endereço comercial, Telefones e e-Mail</td></td></tr>');
      } else {
        ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Endereço principal, Telefones e e-Mail</td></td></tr>');
      } 
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ddd.'"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_nr_telefone.'"> '.consultaTelefone($w_cliente).'</td>');
      ShowHTML('          <td title="Se informar um número de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_fax.'"></td>');
      ShowHTML('          <td title="Se informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_celular.'"></td>');
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
      ShowHTML('              <td colspan=3 title="Se informar um e-mail institucional, informe-o neste campo."><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="sti" SIZE="50" MAXLENGTH="60" VALUE="'.$w_email.'"></td>');
      ShowHTML('          </table>');
      if (substr($SG,0,3)!='GCR' && substr($SG,0,3)!='GCZ') {
        // Se não for acordo de receita
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
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina de cadastramento de representantes
// -------------------------------------------------------------------------
function Representante() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='') $O='L';
  $w_erro                    = '';
  $w_chave                   = $_REQUEST['w_chave'];
  $w_chave_aux               = $_REQUEST['w_chave_aux'];
  $w_cpf                     = $_REQUEST['w_cpf'];
  $w_sq_pessoa               = $_REQUEST['w_sq_pessoa'];
  $w_sq_acordo_outra_parte   = $_REQUEST['w_sq_acordo_outra_parte'];
  $w_outra_parte             = $_REQUEST['w_outra_parte'];
  $w_tipo                    = $_REQUEST['w_tipo'];
  

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_chave                   = $_REQUEST['w_chave'];
    $w_chave_aux               = $_REQUEST['w_chave_aux'];
    $w_nome                    = $_REQUEST['w_nome'];
    $w_nome_resumido           = $_REQUEST['w_nome_resumido'];
    $w_sexo                    = $_REQUEST['w_sexo'];
    $w_sq_pessoa_pai           = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa          = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo         = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo         = $_REQUEST['w_nm_tipo_vinculo'];
    $w_interno                 = $_REQUEST['w_interno'];
    $w_vinculo_ativo           = $_REQUEST['w_vinculo_ativo'];
    $w_rg_numero               = $_REQUEST['w_rg_numero'];
    $w_rg_emissor              = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao              = $_REQUEST['w_rg_emissao'];
    $w_sq_pessoa_telefone      = $_REQUEST['w_sq_pessoa_telefone'];
    $w_ddd                     = $_REQUEST['w_ddd'];
    $w_nr_telefone             = $_REQUEST['w_nr_telefone'];
    $w_sq_pessoa_celular       = $_REQUEST['w_sq_pessoa_celular'];
    $w_nr_celular              = $_REQUEST['w_nr_celular'];
    $w_sq_pessoa_fax           = $_REQUEST['w_sq_pessoa_fax'];
    $w_nr_fax                  = $_REQUEST['w_nr_fax'];
    $w_email                   = $_REQUEST['w_email'];
    $w_sq_acordo_outra_parte   = $_REQUEST['w_sq_acordo_outra_parte'];
    $w_cargo                   = $_REQUEST['w_cargo'];
  } else {
    if ($O == 'L') {
      if ($w_tipo == 'PREPOSTO') {
// Recupera os prepostos pela outra parte
        $sql = new db_getConvPreposto;
        $RS1 = $sql->getInstanceOf($dbms, $w_chave, $w_sq_acordo_outra_parte, null);
        $RS1 = SortArray($RS1, 'sq_pessoa', 'asc');
      } elseif ($w_tipo == 'REPRESENTANTE') {
// Recupera os representantes pela outra parte
        $sql = new db_getConvOutroRep;
        $RS1 = $sql->getInstanceOf($dbms, $w_chave, null, $w_sq_acordo_outra_parte);
        $RS1 = SortArray($RS1, 'sq_pessoa', 'asc');
      }
    } elseif ((strpos($_REQUEST['Botao'], 'Alterar') === false) && (strpos($_REQUEST['Botao'], 'Procurar') === false) && ($O == 'A' || $w_sq_pessoa > '' || $w_cpf > '')) {
// Recupera os dados do beneficiário em co_pessoa
      $sql = new db_getBenef;
      $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_outra_parte, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);

      if (!count($RS) <= 0) {
        foreach ($RS as $row1) {
          $RS = $row1;
          break;
        }
      }
//      exibeArray($row1);
      $sql = new db_getBenef;
      $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_sq_pessoa, null, $w_cpf, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
      if (!count($RS) <= 0) {
        foreach ($RS as $row) {
          $w_sq_pessoa = f($row, 'sq_pessoa');
          $w_nome = f($row, 'nm_pessoa');
          $w_nome_resumido = f($row, 'nome_resumido');
          $w_sexo = f($row, 'sexo');
          $w_sq_pessoa_pai = f($row, 'sq_pessoa_pai');
          $w_nm_tipo_pessoa = f($row, 'nm_tipo_pessoa');
          $w_sq_tipo_vinculo = f($row, 'sq_tipo_vinculo');
          $w_nm_tipo_vinculo = f($row, 'nm_tipo_vinculo');
          $w_interno = f($row, 'interno');
          $w_vinculo_ativo = f($row, 'vinculo_ativo');
          $w_cpf = f($row, 'cpf');
          $w_rg_numero = f($row, 'rg_numero');
          $w_rg_emissor = f($row, 'rg_emissor');
          $w_rg_emissao = FormataDataEdicao(f($row, 'rg_emissao'));
          $w_sq_pessoa_telefone = f($row, 'sq_pessoa_telefone');
          $w_ddd = nvl(f($row, 'ddd'),f($row1, 'ddd'));
          $w_nr_telefone = nvl(f($row, 'nr_telefone'),f($row1, 'nr_telefone'));
          $w_sq_pessoa_celular = nvl(f($row, 'sq_pessoa_celular'),f($row1, 'sq_pessoa_celular'));
          $w_nr_celular = nvl(f($row, 'nr_celular'),f($row1, 'nr_celular'));
          $w_sq_pessoa_fax = nvl(f($row, 'sq_pessoa_fax'),f($row1, 'sq_pessoa_fax'));
          $w_nr_fax = nvl(f($row, 'nr_fax'),f($row1, 'nr_fax'));
          $w_email = nvl(f($row, 'email'),f($row1, 'email'));
          $sql = new db_getConvPreposto;
          if ($w_tipo == 'PREPOSTO')
            $RS1 = $sql->getInstanceOf($dbms, $w_chave, $w_sq_acordo_outra_parte, $w_sq_pessoa);
          elseif ($w_tipo == 'REPRESENTANTE')
            $RS1 = $sql->getInstanceOf($dbms, $w_chave, $w_sq_pessoa, $w_sq_acordo_outra_parte);
          foreach ($RS1 as $row1) {
            $RS1 = $row1;
            break;
          }
          $w_cargo = f($row1, 'cargo');
          break;
        }
      }
    }
  } 

  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  if ($O!='L') {
    ShowHTML('<TITLE>'.$conSgSistema.' - '.(($w_tipo=='PREPOSTO') ? 'Prepostos': 'Representantes').'</TITLE>');
    ScriptOpen('JavaScript');
    Modulo();
    FormataCPF();
    checkBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($w_cpf=='' || strpos($_REQUEST['Botao'],'Procurar')!==false || strpos($_REQUEST['Botao'],'Alterar')!==false) {
      // Se o beneficiário ainda não foi selecionado
      ShowHTML('  if (theForm.Botao.value == "Procurar") {');
      Validate('w_nome','Nome','','1','4','20','1','');
      ShowHTML('  theForm.Botao.value = "Procurar";');
      ShowHTML('} else {');
      Validate('  w_cpf','CPF','CPF','1','14','14','','0123456789-.');
      ShowHTML('  theForm.w_sq_pessoa.value = "";');
      ShowHTML('}');
    } elseif ($O=='I' || $O=='A') {
      Validate('w_nome','Nome','1',1,5,60,'1','1');
      Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
      Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
      Validate('w_rg_numero','Identidade','',1,2,30,'1','1');
      Validate('w_rg_emissor','Órgão expedidor','',1,2,30,'1','1');
      Validate('w_rg_emissao','Data de emissão','DATA','',10,10,'','0123456789/');
      Validate('w_ddd','DDD','1','1',2,4,'','0123456789');
      Validate('w_nr_telefone','Telefone','1',1,7,25,'1','1');
      Validate('w_nr_fax','Fax','1','',7,25,'1','1');
      Validate('w_nr_celular','Celular','1','',7,25,'1','1');
      Validate('w_email','E-Mail','1','1',4,60,'1','1');
      Validate('w_cargo','Cargo','1','',2,40,'1','1');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</head>');
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (strpos('IA',$O)!==false && ($w_cpf=='' || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false)) {
    // Se o beneficiário ainda não foi selecionado
    if (strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se está sendo feita busca por nome
      BodyOpenClean('onLoad=\'this.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.w_cpf.focus()\';');
    } 
  } elseif ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('IA',$O)!==false) {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">'); 
  if ($O=='L') { 
    $sql = new db_getConvOutraParte; $RS = $sql->getInstanceOf($dbms,$w_sq_acordo_outra_parte,$w_chave,$w_outra_parte,null);
    foreach($RS as $row) {
      ShowHTML('    <table width="100%" border="0">');
      ShowHTML(' <tr><td>Outra parte: <b>'.f($row,'nm_pessoa').' </b><br><br>');
    }
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_tipo='.$w_tipo.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_outra_parte='.$w_outra_parte.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                         <a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS1));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>CPF</font></td>');
    ShowHTML('          <td><b>Nome</font></td>');
    ShowHTML('          <td><b>DDD</font></td>');
    ShowHTML('          <td><b>Telefone</font></td>');
    ShowHTML('          <td><b>Fax</font></td>');
    ShowHTML('          <td><b>Celular</font></td>');
    ShowHTML('          <td><b>e-Mail</font></td>');
    ShowHTML('          <td class="remover"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS1)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
          foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'cpf').'</td>');
        ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
        ShowHTML('        <td align="center">'.nvl(f($row,'ddd'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'nr_telefone'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'nr_fax'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'nr_celular'),'---').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'email'),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_tipo='.$w_tipo.'&w_outra_parte='.$w_outra_parte.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_chave_aux='.$w_cliente.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_tipo='.$w_tipo.'&w_outra_parte='.$w_outra_parte.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_chave_aux='.$w_cliente.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IA',$O)!==false) {
    if ($w_cpf=='' || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
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
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_outra_parte" value="'.$w_sq_acordo_outra_parte.'">');
    ShowHTML('<INPUT type="hidden" name="w_outra_parte" value="'.$w_outra_parte.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
    
    if ($w_cpf=='' || strpos($_REQUEST['Botao'],'Alterar')!==false || strpos($_REQUEST['Botao'],'Procurar')!==false) {
      $w_nome=$_REQUEST['w_nome'];
      if (strpos($_REQUEST['Botao'],'Alterar')!==false) {
        $w_cpf  = '';
        $w_nome = '';
      } 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('        <tr><td colspan=4>Informe os dados abaixo e clique no botão "Selecionar" para continuar.</TD>');
      ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_outra_parte='.$w_outra_parte.'\'">');
      ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
      ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
      ShowHTML('        <tr><td colspan=4>');
      ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
      ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'\'">');
      ShowHTML('      </table>');
      if ($w_nome>'') {
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,$w_nome,1,null,null,null,null,null,null,null, null, null, null, null);// Recupera apenas pessoas físicas
        $RS = SortArray($RS,'nm_pessoa','asc');
        ShowHTML('<tr><td align="center" colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Nome</font></td>');
        ShowHTML('          <td><b>Nome resumido</font></td>');
        ShowHTML('          <td><b>CPF</font></td>');
        ShowHTML('          <td><b>Operações</font></td>');
        ShowHTML('        </tr>');
        if (count($RS)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não há pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            ShowHTML('        <td align="center">'.nvl(f($row,'cpf'),'---').'</td>');
            ShowHTML('        <td nowrap>');
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&w_tipo='.$w_tipo.'&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_outra_parte='.$w_outra_parte.'&R='.$R.'&O=I&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Selecionar</A>&nbsp');
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
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td>CPF:</font><br><b><font size=2>'.$w_cpf);
      ShowHTML('              <INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
      ShowHTML('          <tr valign="top">');
      ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
      SelecaoSexo('Se<u>x</u>o:','X', null, $w_sexo, null, 'w_sexo', null, null);
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
      ShowHTML('          <td><b>Ór<u>g</u>ão emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
      ShowHTML('          <td><b>Data de <u>e</u>missão:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');      
      ShowHTML('          </table>');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Telefones e e-Mail</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ddd.'"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_nr_telefone.'"> '.consultaTelefone($w_cliente).'</td>');
      ShowHTML('          <td title="Se o representante informar um número de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_fax.'"></td>');
      ShowHTML('          <td title="Se o representante informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_celular.'"></td>');
      ShowHTML('          <tr><td colspan=4><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="sti" SIZE="40" MAXLENGTH="50" VALUE="'.$w_email.'"></td>');
      ShowHTML('          </table>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>C</u>argo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_cargo" class="sti" SIZE="40" MAXLENGTH="40" VALUE="'.$w_cargo.'"></td>');
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=L&w_sq_acordo_outra_parte='.$w_sq_acordo_outra_parte.'&w_tipo='.$w_tipo.'&w_outra_parte='.$w_outra_parte.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Estrutura_Fecha(); 
  Estrutura_Fecha(); 
  Estrutura_Fecha(); 
  Rodape();
} 

// =========================================================================
// Rotina de parcelas
// -------------------------------------------------------------------------
function Parcelas() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave             = $_REQUEST['w_chave'];
  $w_chave_aux         = $_REQUEST['w_chave_aux'];
  $w_copia             = $_REQUEST['w_copia'];
  $w_sq_acordo_aditivo = $_REQUEST['w_sq_acordo_aditivo'];

  // Recupera dados do contrato
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,$SG);
  $w_inicio           = f($RS_Solic,'inicio');
  $w_fim              = addDays(f($RS_Solic,'fim'),f($RS_Solic,'dias_pagamento'));
  $w_prazo_indeterm   = f($RS_Solic,'prazo_indeterm');
  $w_valor_acordo     = f($RS_Solic,'valor_inicial');
  $w_texto            = '';
  $w_edita            = true;
  
  // Recupera dados do aditivo, caso tenha sido informado
  if (nvl($w_sq_acordo_aditivo,'')!='') {
    $sql = new db_getAcordoAditivo; $RS_Adit = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_acordo_aditivo,$w_chave,null,null,null,null,null,null,null,null,null);
    foreach($RS_Adit as $row) { $RS_Adit = $row; break; }
    if (f($RS_Adit,'prorrogacao')=='N' || (f($RS_Adit,'prorrogacao')=='S' && f($RS_Adit,'valor_aditivo')>0)) {
      $w_inicio         = f($RS_Adit,'inicio');
      $w_valor_acordo   = f($RS_Adit,'valor_aditivo');
    }
    $w_fim              = f($RS_Adit,'fim');
    $w_prazo_indeterm   = f($RS_Adit,'prazo_indeterm');
    $w_prorrogacao      = f($RS_Adit,'prorrogacao');
    $w_acrescimo        = f($RS_Adit,'acrescimo');
    $w_supressao        = f($RS_Adit,'supressao');
    $w_valor_parcela    = f($RS_Adit,'vl_parcela');
    $w_texto            = '<tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>';
    $w_texto            .= chr(13).'<tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="1"><b>';
    $w_texto            .= chr(13).'Aditivo: '.f($RS_Adit,'codigo').' abrangendo o período de '.formataDataEdicao(f($RS_Adit,'inicio')).' a '.formataDataEdicao(f($RS_Adit,'fim'));
    $w_texto            .= chr(13).'  <br>[Prorrogação: '.f($RS_Adit,'nm_prorrogacao').'] [Reajuste: '.f($RS_Adit,'nm_revisao').'] [Tipo: '.f($RS_Adit,'nm_tipo').']';
  }
    
  // Bloqueia a inclusão, geração e exclusão de parcelas ligadas ao contrato se já existir algum aditivo
  if((nvl($w_sq_acordo_aditivo,'')=='' && ((nvl(f($RS_Solic,'aditivo_prorrogacao'),'')!='' && nvl(f($RS_Adit,'valor_aditivo'),0)>0)|| nvl(f($RS_Solic,'aditivo_excedente'),'')!=''))) {
    $w_edita           = false;
  }

  if ($w_troca>'') {
    // Se for recarga da página
    $w_ordem           = $_REQUEST['w_ordem'];
    $w_data            = $_REQUEST['w_data'];
    $w_observacao      = $_REQUEST['w_observacao'];
    $w_valor           = $_REQUEST['w_valor'];
    $w_per_ini         = $_REQUEST['w_per_ini'];
    $w_per_fim         = $_REQUEST['w_per_fim'];
    $w_valor_inicial   = $_REQUEST['w_valor_inicial'];
    $w_valor_excedente = $_REQUEST['w_valor_excedente'];
    $w_valor_reajuste  = $_REQUEST['w_valor_reajuste'];
  } elseif ($O=='L') {
    $sql = new db_getAcordoParcela; $RS = $sql->getInstanceOf($dbms,$w_chave,null,'NOTA',null,null,null,null,null,null,$w_sq_acordo_aditivo);
    $RS = SortArray($RS,'ordem','asc','vencimento','asc');
  } elseif (strpos('AEV',$O)!==false || nvl($w_copia,'')!='') {
    // Recupera os dados do endereço informado
    $sql = new db_getAcordoParcela; $RS = $sql->getInstanceOf($dbms,$w_chave,nvl($w_chave_aux,$w_copia),'NOTA',null,null,null,null,null,null,$w_sq_acordo_aditivo);
    foreach($RS as $row) {
      $w_ordem           = f($row,'ordem');
      $w_data            = FormataDataEdicao(f($row,'vencimento'));
      $w_observacao      = f($row,'observacao');
      $w_valor           = number_format(f($row,'valor'),2,',','.'); 
      $w_per_ini         = FormataDataEdicao(f($row,'inicio'));
      $w_per_fim         = FormataDataEdicao(f($row,'fim'));
      $w_valor_inicial   = formatNumber(f($row,'valor_inicial'));
      $w_valor_excedente = formatNumber(f($row,'valor_excedente'));
      $w_valor_reajuste  = formatNumber(f($row,'valor_reajuste'));
      break;
    }
  } 

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  if (strpos('IAEGCPV',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataValor();
    ShowHTML('function trataUnica() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked || document.Form.w_tipo_geracao[4].checked) {');
    ShowHTML('     document.Form.w_tipo_mes[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_mes[1].checked = false;');
    ShowHTML('     document.Form.w_vencimento[0].checked = false;');
    ShowHTML('     document.Form.w_vencimento[1].checked = false;');
    ShowHTML('     document.Form.w_vencimento[2].checked = false;');
    ShowHTML('     document.Form.w_dia_vencimento.value = "";');
    ShowHTML('     document.Form.w_valor_parcela[0].checked = false;');
    ShowHTML('     document.Form.w_valor_parcela[1].checked = false;');
    if(nvl($w_sq_acordo_aditivo,'')=='') {
      ShowHTML('     document.Form.w_valor_parcela[2].checked = false;');
      ShowHTML('     document.Form.w_valor_parcela[3].checked = false;');
    }
    ShowHTML('     document.Form.w_valor_diferente.value = "";');
    ShowHTML('     if (!document.Form.w_tipo_geracao[4].checked) document.Form.w_qtd_31.value = "";');
    ShowHTML('  } else if (document.Form.w_tipo_geracao[2].checked || document.Form.w_tipo_geracao[3].checked) {');
    ShowHTML('     document.Form.w_qtd_31.value = "";');
    ShowHTML('  }');
    ShowHTML('}');
    ShowHTML('function trataVencimento() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked || document.Form.w_tipo_geracao[4].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[4].checked = false;');
    ShowHTML('   }');
    ShowHTML('  if (document.Form.w_vencimento[0].checked || document.Form.w_vencimento[1].checked) {');
    ShowHTML('     document.Form.w_dia_vencimento.value = "";');
    ShowHTML('   }');
    ShowHTML('}');
    ShowHTML('function trataValor() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked || document.Form.w_tipo_geracao[4].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[4].checked = false;');
    ShowHTML('   }');
    ShowHTML('  if (document.Form.w_valor_parcela[0].checked) {');
    ShowHTML('     document.Form.w_valor_diferente.value = "";');
    ShowHTML('   }');
    ShowHTML('}');
    ShowHTML('function trataDiaVencimento() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked || document.Form.w_tipo_geracao[4].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[4].checked = false;');
    ShowHTML('   }');
    ShowHTML('   document.Form.w_vencimento[2].checked = true;');
    ShowHTML('}');
    ShowHTML('function trataValorDiferente() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked || document.Form.w_tipo_geracao[4].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[4].checked = false;');
    ShowHTML('   }');
    ShowHTML('  if (document.Form.w_valor_parcela[0].checked) {');
    ShowHTML('     document.Form.w_valor_parcela[0].checked = false;');
    ShowHTML('   }');
    ShowHTML('}');
    ShowHTML('function trataQuantidade() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked || document.Form.w_tipo_geracao[2].checked || document.Form.w_tipo_geracao[3].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[2].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[3].checked = false;');
    ShowHTML('   }');
    ShowHTML('     document.Form.w_tipo_geracao[4].checked = true;');
    ShowHTML('}');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_ordem','Número de ordem da parcela','1','1','1','4','','0123456789');
      Validate('w_data','Data de vencimento da parcela','DATA','1','10','10','','0123456789/');
      /*
      if ($w_segmento=='Público' || $w_segmento=='Agência') {
        CompData('w_data','Data de vencimento','>=','w_inicio','Data de início de vigência');
        CompData('w_data','Data de vencimento','<=','w_fim','Data de término de vigência');
      }
      */
      if(nvl($w_sq_acordo_aditivo,'')=='') {
        Validate('w_valor','Valor da parcela','VALOR','1',4,18,'','0123456789.,');
      } else {
        if(f($RS_Adit,'prorrogacao')=='S'||(f($RS_Adit,'prorrogacao')=='N'&&f($RS_Adit,'acrescimo')=='N'&&f($RS_Adit,'supressao')=='N')) Validate('w_valor_inicial','Valor inicial','VALOR','1',4,18,'','0123456789.,');
        if(f($RS_Adit,'acrescimo')=='S'||f($RS_Adit,'supressao')=='S') Validate('w_valor_excedente','Valor do acréscimo/supressão','VALOR','1',4,18,'','-0123456789.,');
        Validate('w_valor_reajuste','Valor reajuste','VALOR','1',4,18,'','0123456789.,-');
      }
      Validate('w_per_ini','Início do período de realização','DATA','1','10','10','','0123456789/');
      /*
      if($w_segmento=='Público' || $w_segmento=='Agência') {
        CompData('w_per_ini','Início do período de realização','>=','w_inicio','Data de início de vigência');
        CompData('w_per_ini','Início do período de realização','<=','w_fim','Data de término de vigência');
      }
       */
      Validate('w_per_fim','Fim do período de realização','DATA','1','10','10','','0123456789/');
      /*
      if($w_segmento=='Público' || $w_segmento=='Agência') {
        CompData('w_per_fim','Fim do período de realização','>=','w_inicio','Data de início de vigência');
        CompData('w_per_fim','Fim do período de realização','<=','w_fim','Data de término de vigência');
      }
       */
      CompData('w_per_ini','Início do período de realização','<=','w_per_fim','Fim do período de realização');
      Validate('w_observacao','Observação','1','','3','200','1','1');
    } elseif ($O=='G') {
      Validate('w_dia_vencimento','Dia de vencimento','1','',1,2,'','0123456789');
      if(nvl($w_sq_acordo_aditivo,'')=='') {
        Validate('w_valor_diferente','Valor da parcela','VALOR','',4,18,'','0123456789.,');
      }
      ShowHTML('  for (i = 0; i < theForm.w_tipo_geracao.length; i++) {');
      ShowHTML('      if (theForm.w_tipo_geracao[i].checked) break;');
      ShowHTML('      if (i == theForm.w_tipo_geracao.length-1) {');
      ShowHTML('         alert("Você deve selecionar uma das opções apresentadas!");');
      ShowHTML('         return false;');
      ShowHTML('      }');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_tipo_geracao[2].checked || theForm.w_tipo_geracao[3].checked ) {');
      ShowHTML('     for (i = 0; i < theForm.w_vencimento.length; i++) {');
      ShowHTML('         if (theForm.w_vencimento[i].checked) break;');
      ShowHTML('         if (i == theForm.w_vencimento.length-1) {');
      ShowHTML('            alert("Você deve selecionar um dia para vencimento das parcelas!");');
      ShowHTML('            return false;');
      ShowHTML('         }');
      ShowHTML('     }');
      ShowHTML('     for (i = 0; i < theForm.w_valor_parcela.length; i++) {');
      ShowHTML('         if (theForm.w_valor_parcela[i].checked) break;');
      ShowHTML('         if (i == theForm.w_valor_parcela.length-1) {');
      ShowHTML('            alert("Você deve selecionar uma das opções para cálculo do valor das parcelas!");');
      ShowHTML('            return false;');
      ShowHTML('         }');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_vencimento[2].checked) {');
      ShowHTML('     if (theForm.w_dia_vencimento.value == "") {');
      ShowHTML('        alert("Você deve informar o dia de vencimento das parcelas!");');
      ShowHTML('        theForm.w_dia_vencimento.focus();');
      ShowHTML('        return false;');
      ShowHTML('     }');
      ShowHTML('     if (theForm.w_dia_vencimento.value > 28) {');
      ShowHTML('        alert("Para vencimento após o dia 28, utilize a opção de vencimento no último dia do mês!");');
      ShowHTML('        theForm.w_dia_vencimento.focus();');
      ShowHTML('        return false;');
      ShowHTML('     }');
      ShowHTML('  }');
      if(nvl($w_sq_acordo_aditivo,'')=='') {      
        ShowHTML('  if (theForm.w_valor_parcela[2].checked || theForm.w_valor_parcela[3].checked) {');
        ShowHTML('     if (theForm.w_valor_diferente.value == "") {');
        ShowHTML('        alert("Você deve informar o valor para a parcela diferente das demais!");');
        ShowHTML('        theForm.w_valor_diferente.focus();');
        ShowHTML('        return false;');
        ShowHTML('     }');
        ShowHTML('  }');
      }
      ShowHTML('  if (theForm.w_tipo_geracao[4].checked) {');
      ShowHTML('     if (theForm.w_qtd_31.value == "") {');
      ShowHTML('        alert("Você deve informar a quantidade de parcelas a serem geradas!");');
      ShowHTML('        theForm.w_qtd_31.focus();');
      ShowHTML('        return false;');
      ShowHTML('     } else {');
      Validate('w_qtd_31','Quantidade de parcelas','VALOR','',1,4,'','0123456789');
      CompValor('w_qtd_31','Quantidade de parcelas','>=','1','1');
      ShowHTML('     }');
      ShowHTML('  }');
      Validate('w_observacao','Observação','1','','3','200','1','1');
    } elseif ($O=='V') {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_sq_acordo_parcela[]"].value==undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_sq_acordo_parcela[]"].length; i++) {');
      ShowHTML('       if (theForm["w_sq_acordo_parcela[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_sq_acordo_parcela[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve informar pelo menos uma parcela!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_ordem.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if (nvl($w_sq_acordo_aditivo,'')!='') {
    ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    ShowHTML('<tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>');
    ShowHTML('  '.f($RS_Solic,'nome').': '.f($RS_Solic,'codigo_interno').' - '.f($RS_Solic,'titulo'));
    ShowHTML('  <br>Vigência: '.formataDataEdicao(f($RS_Solic,'inicio')).' a '.formataDataEdicao(f($RS_Solic,'fim')));
    ShowHTML('  '.$w_texto.'</b></font></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  }
  ShowHTML('<center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="97%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr valign="top"><td>');
    if($w_edita) {
      //if (nvl($w_sq_acordo_aditivo,'')=='' || (f($RS_Adit,'prorrogacao')=='S'||(f($RS_Adit,'prorrogacao')=='N'&&f($RS_Adit,'acrescimo')=='N'&&f($RS_Adit,'supressao')=='N'))) {
        ShowHTML('        <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
      //}
      if (nvl($w_sq_acordo_aditivo,'')=='' || (f($RS_Adit,'revisao')=='S'||f($RS_Adit,'prorrogacao')=='S'||f($RS_Adit,'acrescimo')=='S'||f($RS_Adit,'supressao')=='S')) {
        if($w_prorrogacao=='N') {
          ShowHTML('        <a accesskey="G" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>G</u>erar</a>&nbsp;');
        } else {
          ShowHTML('        <a accesskey="G" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=G&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>G</u>erar</a>&nbsp;');
        }
      }
    }
    if(nvl($w_sq_acordo_aditivo,'')>'') ShowHTML('                         <a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=2>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if (nvl($w_sq_acordo_aditivo,'')=='') {
      ShowHTML('          <td><b>Ordem</font></td>');
      ShowHTML('          <td><b>Período</font></td>');
      ShowHTML('          <td><b>Vencimento</font></td>');
      ShowHTML('          <td><b>Valor</font></td>');
    } else {
      ShowHTML('          <td rowspan=2><b>Ordem</font></td>');
      ShowHTML('          <td rowspan=2><b>Período</font></td>');
      ShowHTML('          <td rowspan=2><b>Vencimento</font></td>');
      ShowHTML('          <td colspan=4><b>Valor</font></td>');
    } 
    ShowHTML('          <td><b>Observação</font></td>');
    ShowHTML('          <td class="remover"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (nvl($w_sq_acordo_aditivo,'')>'') {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Inicial</font></td>');
      ShowHTML('          <td><b>Acres./Supr.</font></td>');
      ShowHTML('          <td><b>Reajuste</font></td>');
      ShowHTML('          <td><b>Total</font></td>');
      ShowHTML('          <td colspan=2><b>&nbsp;</font></td>');
      ShowHTML('        </tr>');
    }
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_total   = 0;
      $w_total_i = 0;
      $w_total_e = 0;
      $w_total_r = 0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>');
        if (nvl(f($row,'quitacao'),'nulo')=='nulo') {
          if (f($row,'vencimento')<addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center">');
          } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
            ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } else {
          if (f($row,'quitacao')<f($row,'vencimento')) {
            ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } 
        ShowHTML('        '.f($row,'ordem').'</td>');
        if(nvl(f($row,'inicio'),'')!='') ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).' a '.FormataDataEdicao(f($row,'fim')).'</td>');
        else                             ShowHTML('        <td align="center">---</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'vencimento')).'</td>');
        if (nvl($w_sq_acordo_aditivo,'')>'') {
          ShowHTML('        <td align="right">'.number_format(f($row,'valor_inicial'),2,',','.').'&nbsp;&nbsp;</td>');
          ShowHTML('        <td align="right">'.number_format(f($row,'valor_excedente'),2,',','.').'&nbsp;&nbsp;</td>');
          ShowHTML('        <td align="right">'.number_format(f($row,'valor_reajuste'),2,',','.').'&nbsp;&nbsp;</td>');
        } 
        ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;&nbsp;</td>');
        ShowHTML('        <td>'.crlf2br(nvl(f($row,'observacao'),'---')).'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        if(nvl(f($row,'quitacao'),'')!='') {
          ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="alert("Parcelas pagas não podem ser alteradas.")";>AL</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="alert("Parcelas pagas não podem ser excluídas.")";>EX</A>&nbsp');
        } else {
          if(nvl(f($row,'sq_acordo_aditivo'),'')!='' && $P1==1) {
            ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="alert("Parcelas ligada a aditivo!\nUse a operação parcelas do aditivo.")";>AL</A>&nbsp');
          } else {
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_parcela').'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
          }
          if($w_edita) {
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_parcela').'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_copia='.f($row,'sq_acordo_parcela').'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" TITLE="Gera uma nova parcela a partir dos dados desta.">CO</A>&nbsp');
          }
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        if(f($row,'prorrogacao')=='N' && (f($row,'acrescimo')=='S'||f($row,'supressao')=='S')) {
          $w_total += f($row,'valor_excedente');
        } else {
          if (1==1 || f($row,'adi_vl_ini')>0 || nvl($w_sq_acordo_aditivo,'')=='') { $w_total_i += f($row,'valor_inicial');   $w_total   += f($row,'valor_inicial'); };
          if (1==1 || f($row,'adi_vl_acr')>0) { $w_total_e += f($row,'valor_excedente'); $w_total   += f($row,'valor_excedente'); };
          if (1==1 || f($row,'adi_vl_rea')>0) { $w_total_r += f($row,'valor_reajuste');  $w_total   += f($row,'valor_reajuste'); };
        }
      } 
    } 
    //if ($w_total>0) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td align="center" colspan=3><b>Total</b></td>');
      if (nvl($w_sq_acordo_aditivo,'')>'') {
        ShowHTML('        <td align="right"><b>'.formatNumber($w_total_i).'</b>&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="right"><b>'.formatNumber($w_total_e).'</b>&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="right"><b>'.formatNumber($w_total_r).'</b>&nbsp;&nbsp;</td>');
      }
      ShowHTML('        <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;&nbsp;</td>');
      ShowHTML('        <td colspan=3>');
      //if (round($w_valor_acordo-$w_total,2)!=0) {
      if (round($w_total-$w_total,2)!=0) {
        //ShowHTML('<b>O valor das parcelas difere do valor contratado ('.formatNumber(round($w_valor_acordo-$w_total,2)).')</b></td>');
        ShowHTML('<b>O valor das parcelas difere do valor contratado ('.formatNumber(round($w_total-$w_total,2)).')</b></td>');
      } else {
        ShowHTML('        &nbsp;</td>');
      }  
      ShowHTML('      </tr>');
    //} 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAE',$O)!==false) {
    if(!$w_edita) {
       $w_Disabled=' READONLY ';
    } else {  
      if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    }
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_aditivo" value="'.$w_sq_acordo_aditivo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.FormataDataEdicao($w_inicio).'">');
    ShowHTML('<INPUT type="hidden" name="w_fim" value="'.FormataDataEdicao($w_fim).'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>ATENÇÃO</b>: a data de vencimento deve estar contida dentro da vigência, de <b>'.FormataDataEdicao($w_inicio).'</b> e <b>'.FormataDataEdicao($w_fim).'</b>.<br>&nbsp;</td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b>Número de <u>o</u>rdem da parcela:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_ordem" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ordem.'" title="Informe o número de ordem da parcela, que indica a seqüência de pagamento."></td>');
    ShowHTML('          <td><b><u>D</u>ata de vencimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de vencimento da parcela.">'.ExibeCalendario('Form','w_data').'</td>');
    if(nvl($w_sq_acordo_aditivo,'')=='') {
      ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor da parcela."></td>');
    } else {
      if(f($RS_Adit,'prorrogacao')=='S'||(f($RS_Adit,'prorrogacao')=='N'&&f($RS_Adit,'acrescimo')=='N'&&f($RS_Adit,'supressao')=='N')) {
        ShowHTML('          <td><b><u>V</u>alor inicial:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_inicial" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_inicial.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor inicial da parcela."></td>');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_valor_inicial" value="'.$w_valor_inicial.'">');
      }
      if(f($RS_Adit,'acrescimo')=='S'||f($RS_Adit,'supressao')=='S') {
        ShowHTML('          <td><b><u>V</u>alor excedente:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_excedente" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_excedente.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor de excedente da parcela."></td>');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_valor_excedente" value="'.$w_valor_excedente.'">');
      }
      //if(f($RS_Adit,'revisao')=='S') {
        ShowHTML('          <td><b><u>V</u>alor reajuste:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_reajuste" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_reajuste.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor de reajuste da parcela."></td>');
      /*
       } else {
        ShowHTML('<INPUT type="hidden" name="w_valor_reajuste" value="'.$w_valor_reajuste.'">');
       }
       */
    }
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><b><u>P</u>eríodo de realização:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_per_ini" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_per_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de início do periodo de realização da parcela.">'.ExibeCalendario('Form','w_per_ini').' a '.'<input '.$w_Disabled.' accesskey="P" type="text" name="w_per_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_per_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de fim do periodo de realização da parcela.">'.ExibeCalendario('Form','w_per_fim').'</td>');
    if(!$w_edita) {
      $w_Disabled=' ';
    }
    ShowHTML('      <tr><td colspan=4><b>Obse<u>r</u>vações:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_observacao" class="sti" ROWS=5 cols=75 >'.$w_observacao.'</TEXTAREA></td>');
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
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='G') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_aditivo" value="'.$w_sq_acordo_aditivo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.FormataDataEdicao($w_inicio).'">');
    ShowHTML('<INPUT type="hidden" name="w_fim" value="'.FormataDataEdicao($w_fim).'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><font size="2"><b>ATENÇÃO</b>: as parcelas existentes, se existirem, serão excluídas.<br>&nbsp;</td>');
    ShowHTML('      <tr><td><b>Dados:</b><ul>');
    ShowHTML('              <li>Vigência: <b>'.FormataDataEdicao($w_inicio).'</b> a <b>'.FormataDataEdicao($w_fim).'</b>');
    ShowHTML('              <li>Valor: <b>'.formatNumber($w_valor_acordo).'</b>');
    ShowHTML('              </ul>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr valign="top"><td colspan=2><b>Dados necessários à geração de parcelas únicas:</b>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=11 onClick="trataUnica();"><td>Gerar uma única parcela, paga no início da vigência</td>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=12 onClick="trataUnica();"><td>Gerar uma única parcela, paga no fim da vigência</td>');
    ShowHTML('          <tr valign="top"><td colspan=2><b>Dados necessários à geração de parcelas mensais:</b>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=21 onClick="trataUnica();"><td>Gerar parcelas mensais com vencimento a cada trinta dias após o início da vigência</td>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=22 onClick="trataUnica();"><td>Gerar parcelas mensais com vencimento a cada trinta dias a partir do início da vigência</td>');
    ShowHTML('          <tr><td><td><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('              <tr valign="top"><td colspan=3><b>Período de referência das parcelas:</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_tipo_mes" value="F"><td>Fechado: deve estar contido em um único mês</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_tipo_mes" value="A"><td>Aberto: pode abranger mais de um mês</td>');
    ShowHTML('              </table>');
    ShowHTML('          <tr><td><td><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('              <tr valign="top"><td colspan=3><b>Dia de vencimento das parcelas:</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_vencimento" value="P" onClick="trataVencimento();"><td>Sempre no primeiro dia do mês</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_vencimento" value="U" onClick="trataVencimento();"><td>Sempre no último dia do mês</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_vencimento" value="D" onClick="trataVencimento();"><td>Sempre no dia <input '.$w_Disabled.' type="text" name="w_dia_vencimento" class="sti" SIZE="2" MAXLENGTH="2" VALUE="" onKeyDown="trataDiaVencimento();" title="Informe o dia de vencimento da parcela."> de cada mês.</td>');
    ShowHTML('              </table>');
    ShowHTML('          <tr><td><td><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('              <tr valign="top"><td colspan=3><b>Valores das parcelas:</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_valor_parcela" value="I" onClick="trataValor();"><td>As parcelas têm valores iguais</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_valor_parcela" value="C" onClick="trataValor();"><td>Primeira e última parcelas proporcionais aos dias</td>');
    if(nvl($w_sq_acordo_aditivo,'')=='') {
      ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_valor_parcela" value="P" onClick="trataValor();"><td>A primeira parcela tem valor diferente das demais</td>');
      ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_valor_parcela" value="U" onClick="trataValor();"><td>A última parcela tem valor diferente das demais</td>');
      ShowHTML('              <tr valign="top"><td colspan=2><td><b>Valor da parcela diferente das demais:</b> <input '.$w_Disabled.' type="text" name="w_valor_diferente" class="sti" SIZE="18" MAXLENGTH="18" style="text-align:right;" onKeyDown="FormataValor(this, 18, 2, event); trataValorDiferente();" VALUE="" title="Informe o valor da primeira parcela. As demais terão valores iguais."></td>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_valor_diferente" value="0">');
    }
    ShowHTML('              </table>');
    ShowHTML('          <tr valign="top"><td colspan=2><b>Quantidade de parcelas definida pelo usuário:</b>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=31 onClick="trataUnica();"><td>Gerar  <input '.$w_Disabled.' type="text" name="w_qtd_31" class="sti" SIZE="2" MAXLENGTH="2" VALUE="" onKeyDown="trataQuantidade();" title="Informe a quantidade desejada de parcelas."> parcelas. Será necessário ajustar os dados relativos à referência e ao vencimento</td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=4><b>Obse<u>r</u>vações gerais a serem gravadas em todas as parcelas:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_observacao" class="sti" ROWS=5 cols=75 >'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gerar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='V') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_aditivo" value="'.$w_sq_acordo_aditivo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_inicio[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_fim[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_parcela[]" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'" align="center"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>Dados:</b><ul>');
    ShowHTML('              <li>Vigência: <b>'.FormataDataEdicao($w_inicio).'</b> a <b>'.FormataDataEdicao($w_fim).'</b>');
    ShowHTML('              <li>Valor: <b>'.formatNumber($w_valor_acordo).'</b>');
    ShowHTML('              </ul>');
    $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'GCDCAD');
    $sql = new db_getAcordoParcela; $RS_Parc = $sql->getInstanceOf($dbms,$w_chave,null,'PERIODO',null,FormataDataEdicao($w_inicio),FormataDataEdicao($w_fim),null,null,null,null);
    $RS_Parc = SortArray($RS_Parc,'ordem','asc');
    ShowHTML('  <tr><td colspan="2">');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td rowspan=2><b>&nbsp;</b></td>');
    ShowHTML('            <td rowspan=2><b>Nº</b></td>');
    ShowHTML('            <td rowspan=2><b>Período</b></td>');
    ShowHTML('            <td rowspan=2><b>Venc.</b></td>');
    ShowHTML('            <td rowspan=2><b>Observações</b></td>');
    ShowHTML('            <td colspan=4><b>Valores</b></td>');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td><b>Inicial</b></td>');
    ShowHTML('            <td><b>Reajuste</b></td>');
    ShowHTML('            <td><b>Acr.Supr.</b></td>');
    ShowHTML('            <td><b>Total</b></td>');
    if (count($RS_Parc)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      if(nvl($w_sq_acordo_aditivo,'')>'') ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><font size="2"><b>Cadastre antes as parcelas do aditivo.</b></font></td></tr>');
      else                                ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><font size="2"><b>Cadastre antes as parcelas do contrato.</b></font></td></tr>');
    } else {
      foreach($RS_Parc as $row) {
        $w_cont+= 1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td width="1%" nowrap align="center"><input type="checkbox" name="w_sq_acordo_parcela[]" value="'.f($row,'sq_acordo_parcela').'">');
        ShowHTML('<INPUT type="hidden" name="w_inicio[]" value="'.FormataDataEdicao(f($row,'inicio')).'">');
        ShowHTML('<INPUT type="hidden" name="w_fim[]" value="'.FormataDataEdicao(f($row,'fim')).'">');
        ShowHTML('        <td>');
        if (nvl(f($row,'quitacao'),'nulo')=='nulo') {
          if (f($row,'vencimento')<addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center">');
          } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
            ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } else {
          if (f($row,'quitacao')<f($row,'vencimento')) {
            ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } 
        ShowHTML('        '.substr(1000+f($row,'ordem'),1,3).'</td>');
        if(nvl(f($row,'inicio'),'')!='') ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).' a '.FormataDataEdicao(f($row,'fim')).'</td>');
        else                             ShowHTML('        <td align="center">---</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'vencimento')).'</td>');
        ShowHTML('        <td>'.f($row,'observacao').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_inicial'),2).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_reajuste'),2).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_excedente'),2).'</td>');
        ShowHTML('        <td align="right"><b>'.formatNumber(f($row,'valor'),2).'</b></td>');
        ShowHTML('      </tr>');
      } 
    }     
    ShowHTML('    </table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('        <input class="stb" type="submit" name="Botao" value="Gerar">');
    ShowHTML('        <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('      </td>');
    ShowHTML('  </tr>');
    ShowHTML('</table>');
    ShowHTML('</TD>');
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

// ------------------------------------------------------------------------- 
// Rotina de anexos 
// ------------------------------------------------------------------------- 
function Anexo() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endereço informado 
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach ($RS as $row) {
      $w_nome      = f($row,'nome');
      $w_descricao = f($row,'descricao');
      $w_caminho   = f($row,'chave_aux');
      break;
    }
  } 
  Cabecalho();
  head();
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
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
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
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
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_caminho.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I' || $O=='A') {
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
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
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    //ShowHTML ' history.go(-1);' 
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
  global $w_TP;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = upper(trim($_REQUEST['w_tipo']));

  // Recupera o logo do cliente a ser usado nas listagens
  if ($w_tipo=='PDF') {
    headerPDF('Visualização de '.f($RS_Menu,'nome'),$w_pag);
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
    BodyOpenClean('onLoad=\'this.focus()\';');
    CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);  
    $w_embed = 'HTML';
  } 
  if ($w_embed!='WORD') {
    ShowHTML('<center><B><font size=1>Clique <a class="HL" href="javascript:history.go(-1);">aqui</a> para voltar à tela anterior</b></font></center>');
  } 
  // Chama a rotina de visualização dos dados da atividade, na opção 'Listagem'
  ShowHTML(VisualAcordo($w_chave,'L',$w_usuario,'4',$w_embed));
  if ($w_embed!='WORD') {
    ShowHTML('<center><B><font size=1>Clique <a class="HL" href="javascript:history.go(-1);">aqui</a> para voltar à tela anterior</b></font></center>');
  }
  if     ($w_tipo=='PDF')      RodapePDF();
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

  // Se for recarga da página
  if ($w_troca>'') {
    $w_observacao=$_REQUEST['w_observacao'];
  } 

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O='E') {
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();

  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados, na opção 'Listagem'
  ShowHTML(VisualAcordo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'GERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Excluir">');
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
// Rotina de tramitação
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_erro       = '';
  $w_tramite    = $_REQUEST['w_tramite'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_destinatario = $_REQUEST['w_destinatario'];
    $w_novo_tramite = $_REQUEST['w_novo_tramite'];
    $w_despacho     = $_REQUEST['w_despacho'];
  } else {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
    if (f($RS, 'sg_tramite') == 'CI') {
      $w_tramite = f($RS, 'sq_siw_tramite');
    }
    $w_tramite      = f($RS,'sq_siw_tramite');
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 

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
  if ($O=='V') $w_erro = ValidaAcordo($w_cliente,$w_chave,substr($SG,0,3).'GERAL',null,null,null,$w_tramite);
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinatário','HIDDEN','1','1','10','','1');
    Validate('w_despacho','Despacho','','1','1','2000','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td align="center" colspan=2>');

  // Chama a rotina de visualização dos dados do projeto, na opção 'Listagem'
  ShowHTML(VisualAcordo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');

  ShowHTML('<tr><td align="center" colspan=2>');
  ShowHTML('  <table width="97%" border="0" bgcolor="'.$conTrBgColor.'">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if ($P1!=1) {
    // Se não for cadastramento
    if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
      SelecaoFase('<u>F</u>ase:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } else {
      SelecaoFase('<u>F</u>ase:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','ERRO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } 

    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
    if ($w_sg_tramite=='CI') {
      SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    } else {
      SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione um destinatário para o projeto na relação.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','USUARIOS');
    } 
  } else {
    if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
      SelecaoFase('<u>F</u>ase:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } else {
      SelecaoFase('<u>F</u>ase:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','ERRO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } 
    SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione um destinatário para o projeto na relação.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','USUARIOS');
  } 
  ShowHTML('    <tr><td colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="sti" ROWS=5 cols=75 title="Descreva a ação esperada pelo destinatário.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Enviar">');
  if ($P1!=1) {
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
function Encaminhamento1() {
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
  if ($O=='V') $w_erro = ValidaAcordo($w_cliente,$w_chave,substr($SG,0,3).'GERAL',null,null,null,$w_tramite);
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
        ShowHTML('  if (theForm.w_envio[0].checked && theForm.w_despacho.value != "") {');
        ShowHTML('     alert("Informe o despacho apenas se for devolução para a fase anterior!");');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_envio[1].checked && theForm.w_despacho.value=="") {');
        ShowHTML('     alert("Informe um despacho descrevendo o motivo da devolução!");');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
  ShowHTML(VisualAcordo($w_chave,'V',$w_usuario,$P1,$P4));
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
        ShowHTML('      <tr><td align="LEFT" colspan=4><b>Informe sua <U>a</U>ssinatura eletrônica para que o envio seja realizado:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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

  // Verifica se há tabela de tipos de log cadastrada para a opção de menu
  $sql = new db_getTipoLog; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_menu, null, null, null, 'S', null);
  if (count($RS)>0) $w_existe_tipo_log = true; else $w_existe_tipo_log = false;
  
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
    $w_tipo_log   = $_REQUEST['w_tipo_log'];
  } 

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($w_existe_tipo_log) Validate('w_tipo_log','Tipo','SELECT','1','1','18','','1');
    Validate('w_observacao','Texto','','1','1','2000','1','1');
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

  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    if ($w_existe_tipo_log) {
      BodyOpenClean('onLoad=\'document.Form.w_tipo_log.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.w_observacao.focus()\';');
    }
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

// Chama a rotina de visualização dos dados do contrato, na opção 'Listagem'
  ShowHTML(VisualAcordo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'ENVIO&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');

  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');

  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  if ($w_existe_tipo_log) {
    ShowHTML('      <tr>');
    SelecaoTipoLog('<u>T</u>ipo:','T','Selecione na lista o tipo adequado.',$w_tipo_log,$w_menu,'w_tipo_log',null,null);
    ShowHTML('      </tr>');
  }
  ShowHTML('      <tr><td><b><u>T</u>exto:</b><br><textarea '.$w_Disabled.' accesskey="T" name="w_observacao" class="sti" ROWS=5 cols=75 title="Redija a anotação desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_tipo_conc  = Nvl($_REQUEST['w_tipo_conc'],-1);
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
  } 

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_inicio_real    = f($RS,'inicio');
  $w_fim_real       = f($RS,'fim');
  $w_custo_real     = number_format(f($RS,'valor'),2,',','.');
  $w_duracao        = f($RS,'duracao');

  // Se for envio, executa verificações nos dados da solicitação
  if ($O=='V') $w_erro = ValidaAcordo($w_cliente,$w_chave,substr($SG,0,3).'GERAL',null,null,null,$w_tramite);

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_tipo_conc','Tipo da conclusão','SELECT',1,1,1,'','1');
    Validate('w_fim_real','Data da rescisão','DATA',1,10,10,'','0123456789/');
    CompData('w_inicio_real','Início da execução','<=','w_fim_real','Término da execução');
    if ($w_tipo_conc==2) {
      Validate('w_nota_conclusao','Motivo da rescisão','','1','1','2000','1','1');
    } else {
      Validate('w_nota_conclusao','Observação','','','2','2000','1','1');
    } 

    if ($w_tipo_conc==0) {
      Validate('w_inicio','Início da vigência','DATA',1,10,10,'','0123456789/');
      Validate('w_fim','Término da vigência','DATA',1,10,10,'','0123456789/');
      CompData('w_inicio','Início da vigência','<=','w_fim','Término da vigência');
      if (substr($SG,0,3)!='GCA') {
        Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
      }
    }

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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_tipo_conc.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados do contrato, na opção 'Listagem'
  ShowHTML(VisualAcordo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'CONC',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<INPUT type="hidden" name="w_inicio_real" value="'.FormataDataEdicao($w_inicio_real).'">');
  ShowHTML('<INPUT type="hidden" name="w_custo_real" value="'.$w_custo_real.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr valign="top">');
  SelecaoTipoConclusao('Tip<u>o</u>:','O','Selecione o tipo de conclusão.',$w_tipo_conc,'w_tipo_conc',$w_menu,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_fim_real\'; document.Form.submit();"');
  if ($w_tipo_conc==2) {
    ShowHTML('              <td><b>Da<u>t</u>a da rescisão:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_fim_real).'" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data da rescisão.">'.ExibeCalendario('Form','w_fim_real').'</td>');
  } else {
    ShowHTML('              <td><b>Da<u>t</u>a de encerramento:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_fim_real).'" onKeyDown="FormataData(this,event);" title="Data de término de encerramento.">'.ExibeCalendario('Form','w_fim_real').'</td>');
  } 
  ShowHTML('          </table>');
  if ($w_tipo_conc==2) {
    ShowHTML('      <tr><td><b>Motivo da r<u>e</u>scisão:</b><br>');
  } else {
    ShowHTML('      <tr><td><b>Obs<u>e</u>rvação:</b><br>');
  } 
  ShowHTML('          <textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="sti" ROWS=5 cols=75 title="Se desejar, insira observações que julgar relevantes.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  if ($w_tipo_conc==0) {
    $w_inicio   = addDays($w_fim_real,1);
    $w_fim      = FormataDataEdicao(addDays($w_inicio,$w_duracao));
    $w_inicio   = FormataDataEdicao($w_inicio);
    $w_valor    = $w_custo_real;
    ShowHTML('      <tr><td align="center" bgcolor="'.$conTrBgColor.'" style="border: 1px solid rgb(0,0,0);"><b>Dados para a renovação</font></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('              <td><b><u>I</u>nício da vigência:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe o novo início da vigência.">'.ExibeCalendario('Form','w_inicio').'</td>');
    ShowHTML('              <td><b><u>T</u>érmino da vigência:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe o novo termino da vigência.">'.ExibeCalendario('Form','w_fim').'</td>');
    if (substr($SG,0,3)!='GCA') {
      ShowHTML('              <td><b>Valo<u>r</u>:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" TITLE="Informe o novo valor total real ou estimado."></td>');
    }
    ShowHTML('          </table>');
  } 

  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Concluir">');
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

// ------------------------------------------------------------------------- 
// Rotina de aditivos
// ------------------------------------------------------------------------- 
function Aditivos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];

  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  $w_contrato_ini   = f($RS_Solic,'inicio');
  $w_contrato_fim   = f($RS_Solic,'fim');

  if ($w_troca>'') {
    // Se for recarga da página 
    $w_protocolo          = $_REQUEST['w_protocolo'];
    $w_sq_cc              = $_REQUEST['w_sq_cc'];
    $w_codigo             = $_REQUEST['w_codigo'];
    $w_objeto             = $_REQUEST['w_objeto'];
    $w_inicio             = $_REQUEST['w_inicio'];
    $w_fim                = $_REQUEST['w_fim'];
    $w_doc_origem         = $_REQUEST['w_doc_origem'];
    $w_doc_data           = $_REQUEST['w_doc_data'];
    $w_variacao_valor     = $_REQUEST['w_variacao_valor'];
    $w_prorrogacao        = $_REQUEST['w_prorrogacao'];
    $w_revisao            = $_REQUEST['w_revisao'];
    $w_acrescimo          = $_REQUEST['w_acrescimo'];
    $w_supressao          = $_REQUEST['w_supressao'];
    $w_observacao         = $_REQUEST['w_observacao'];
    $w_valor_reajuste     = $_REQUEST['w_valor_reajuste'];
    $w_parcela_reajustada = $_REQUEST['w_parcela_reajustada']; 
    $w_valor_inicial      = $_REQUEST['w_valor_inicial'];
    $w_parcela_inicial    = $_REQUEST['w_parcela_inicial']; 
    $w_valor_reajuste     = $_REQUEST['w_valor_reajuste'];
    $w_parcela_reajustada = $_REQUEST['w_parcela_reajustada']; 
    $w_valor_acrescimo    = $_REQUEST['w_valor_acrescimo'];
    $w_parcela_acrescida  = $_REQUEST['w_parcela_acrescida'];
    $w_tipo               = $_REQUEST['w_tipo'];
    $w_sq_acordo_parcela  = $_REQUEST['w_sq_acordo_parcela'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $sql = new db_getAcordoAditivo; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_chave,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'sq_acordo_aditivo','desc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    $sql = new db_getAcordoAditivo; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave_aux,$w_chave,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {
      $w_chave_aux         = f($row,'sq_acordo_aditivo');
      $w_protocolo          = f($row,'protocolo');
      $w_sq_cc              = f($row,'sq_cc');
      $w_codigo             = f($row,'codigo');
      $w_objeto             = f($row,'objeto');
      $w_inicio             = FormataDataEdicao(f($row,'inicio'));
      $w_fim                = FormataDataEdicao(f($row,'fim'));
      $w_doc_origem         = f($row,'documento_origem');
      $w_doc_data           = FormataDataEdicao(f($row,'documento_data'));
      $w_variacao_valor     = formatNumber(f($row,'variacao_valor'),6);
      $w_prorrogacao        = f($row,'prorrogacao');
      if($w_prorrogacao=='N') $w_revisao = 'N';
      else                    $w_revisao = f($row,'revisao');
      $w_acrescimo          = f($row,'acrescimo');
      $w_supressao          = f($row,'supressao');
      $w_observacao         = f($row,'observacao');
      $w_valor_reajuste     = formatNumber(f($row,'valor_reajuste'));
      $w_parcela_reajustada = formatNumber(f($row,'parcela_reajustada'));
      $w_valor_inicial      = formatNumber(f($row,'valor_inicial'));
      $w_parcela_inicial    = formatNumber(f($row,'parcela_inicial'));
      $w_valor_reajuste     = formatNumber(f($row,'valor_reajuste'));
      $w_parcela_reajustada = formatNumber(f($row,'parcela_reajustada'));
      $w_valor_acrescimo    = formatNumber(f($row,'valor_acrescimo'));
      $w_parcela_acrescida  = formatNumber(f($row,'parcela_acrescida'));

      if(nvl($w_acrescimo,'') == 'S')      $w_tipo = 'ACRESCIMO';
      elseif(nvl($w_supressao,'') == 'S')  $w_tipo = 'SUPRESSAO';
      else                                 $w_tipo = 'NAOAPLICA';
      break;
    }
  } 
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Aditivos de contrato</TITLE>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    ShowHTML('function recarrega() {');
    ShowHTML('  document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; ');
    if (substr($SG,0,3)!='GCZ') {
      ShowHTML('  document.Form.w_troca.value=\'w_sq_cc\'; ');
    } else {
      ShowHTML('  document.Form.w_troca.value=\'w_codigo\'; ');
    }
    ShowHTML('  document.Form.O.value=\''.$O.'\'; ');
    ShowHTML('  document.Form.submit(); ');
    ShowHTML('}');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IAE',$O)!==false) {
      if (strpos('IA',$O)!==false) {
        if ($w_segmento=='Público') Validate('w_sq_cc','Classificação','SELECT',1,1,18,'','0123456789');
        Validate('w_codigo','Código','1','1','1','30','1','1');
        Validate('w_inicio','Início','DATA','1','10','10','','0123456789/');
        Validate('w_fim','Fim','DATA','1','10','10','','0123456789/');
        CompData('w_fim','Fim','>=','w_inicio','Início');
        if ($w_prorrogacao=='N') {
          CompData('w_inicio','Início','>=',formataDataEdicao($w_contrato_ini),'início da vigência');
          CompData('w_fim','Fim','<=',formataDataEdicao($w_contrato_fim),'final da vigência');
        }
        Validate('w_objeto','Objeto','1','1','1','2000','1','1');
        Validate('w_observacao','Observação','1','','1','500','1','1');
        if ($w_cliente!=10135) { 
          //ABDI
          Validate('w_doc_origem','Documento de origem','1','','1','30','1','1');
          Validate('w_doc_data','Data do documento','DATA','','10','10','','0123456789/');
        }
        if($w_prorrogacao=='S' && substr($SG,0,3)!='GCZ') {
          Validate('w_valor_inicial','Valor inicial','VALOR','1',4,18,'','0123456789.,');
          Validate('w_parcela_inicial','Valor inicial da parcela','VALOR','1',4,18,'','0123456789.,');
          //CompValor('w_valor_inicial','Valor inicial','>',0,'0');
          //CompValor('w_parcela_inicial','Valor inicial da parcela','>',0,'0');
        }
        if($w_revisao=='S') {
          Validate('w_valor_reajuste','Valor do reajste','VALOR','1',4,18,'','0123456789.,-');
          Validate('w_parcela_reajustada','Valor da parcela do reajuste','VALOR','1',4,18,'','0123456789.,-');
          CompValor('w_valor_reajuste','Valor do reajuste','!=',0,'0');
          CompValor('w_parcela_reajustada','Valor da parcela do reajuste','!=',0,'0');
        }
        if(substr($SG,0,3)!='GCZ' && f($RS_Solic,'limite_variacao')>0) {
          Validate('w_tipo','Acréscimo/Supressão','SELECT',1,1,18,'1','1');
          if(nvl($w_tipo,'')!='NAOAPLICA' && nvl($w_tipo,'')!='') {
            Validate('w_variacao_valor','% de acréscimo/supressão','VALOR','1',8,18,'','0123456789.,');
            CompValor('w_variacao_valor','% de acréscimo/supressão','>',0,'0');
            CompValor('w_variacao_valor','% de acréscimo/supressão','<=',f($RS_Solic,'limite_variacao'),' ao limite de variação');
            Validate('w_valor_acrescimo','Valor do acréscimo/supressao','VALOR','1',4,18,'','0123456789.,-');
            Validate('w_parcela_acrescida','Valor da parcela do acréscimo/supressao','VALOR','1',4,18,'','0123456789.,-');
            CompValor('w_valor_acrescimo','Valor do acréscimo/supressao','!=',0,'0');
            CompValor('w_parcela_acrescida','Valor da parcela do acréscimo/supressao','!=',0,'0');
          }
        }
      }
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    if($w_troca=='TROCA') {
      if($w_tipo=='NAOAPLICA') BodyOpenClean('onLoad=\'document.Form.w_tipo.focus()\';');
      else                     BodyOpenClean('onLoad=\'document.Form.w_variacao_valor.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
    }
  } elseif ($O=='I' || $O=='A') {
    if (substr($SG,0,3)!='GCZ' && $w_segmento=='Público') {
      BodyOpenClean('onLoad=\'document.Form.w_sq_cc.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.w_codigo.focus()\';');
    }
  } elseif ($O=='E') {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  ShowHTML('<tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>');
  ShowHTML('  '.f($RS_Solic,'nome').': '.f($RS_Solic,'codigo_interno').' - '.f($RS_Solic,'titulo'));
  ShowHTML('  <br>Vigência: '.formataDataEdicao(f($RS_Solic,'inicio')).' a '.formataDataEdicao(f($RS_Solic,'fim')));
  ShowHTML('  </b></font></div></td></tr>');
  ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  ShowHTML('<tr><td colspan="3" bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 1px solid rgb(0,0,0);">');
  ShowHTML('  Orientação:<ul>');
  ShowHTML('  <li>Alterações nos dados dos itens devem ser executadas através da operação "IT".');
  ShowHTML('  <li>Outras alterações contratuais, não contempladas nesta tela, exigem a devolução para a fase de cadastramento.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    $sql = new db_getAcordoAditivo; $RS_Aditivo = $sql->getInstanceOf($dbms,$w_cliente,null,$w_chave,null,null,null,null,null,null,null,null,'PARCELAS');
    $RS_Aditivo = SortArray($RS_Aditivo,'sq_acordo_aditivo','desc');
    foreach($RS_Aditivo as $row){$RS_Aditivo=$row; break;}
    ShowHTML('<tr valign="top"><td>');
    //if($P1!=6 && ((count($RS)==0 || Nvl(f($RS_Aditivo,'qtd_parcela'),0)>0) || (f($RS_Aditivo,'prorrogacao')=='N'&&f($RS_Aditivo,'acrescimo')=='N')&&f($RS_Aditivo,'supressao')=='N')) {
      ShowHTML('      <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    //}
    ShowHTML('        <a accesskey="F" class="ss" href="javascript:window.close(); this.opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Código</td>');
    ShowHTML('          <td><b>Início</td>');
    ShowHTML('          <td><b>Fim</td>');
    ShowHTML('          <td><b>Prorrogação</td>');
    if (substr($SG,0,3)!='GCZ') {
      ShowHTML('          <td><b>Reajuste</td>');
      ShowHTML('          <td><b>Tipo</td>');
      ShowHTML('          <td><b>Inicial</td>');
      ShowHTML('          <td><b>Reajuste</td>');
      ShowHTML('          <td><b>Acres./Supr.</td>');
      ShowHTML('          <td><b>Total aditivo</td>');
    } else {
      ShowHTML('          <td><b>Objeto</td>');
    }
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros
      $i = 0;
      $w_diferenca = false;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if(f($row,'prorrogacao')=='S' || f($row,'acrescimo')=='S' || f($row,'supressao')=='S') {
          if (f($row,'valor_aditivo')>0 && f($row,'valor_aditivo')!=f($row,'vl_parcela')) {
            $w_cor        = $conTrBgColorLightRed2; 
            $w_diferenca  = true;
          }
        }
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td width="1%" nowrap>'.f($row,'codigo').'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'inicio')),'---').'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim')),'---').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_prorrogacao').'</td>');
        if (substr($SG,0,3)!='GCZ') {
          ShowHTML('        <td align="center">'.f($row,'nm_revisao').'</td>');
          ShowHTML('        <td align="center">'.f($row,'nm_tipo').'</td>');
          ShowHTML('        <td align="right">'.Nvl(formatNumber(f($row,'valor_inicial'),2),'---').'</td>');
          ShowHTML('        <td align="right">'.Nvl(formatNumber(f($row,'valor_reajuste'),2),'---').'</td>');
          ShowHTML('        <td align="right">'.Nvl(formatNumber(f($row,'valor_acrescimo'),2),'---').'</td>');
          ShowHTML('        <td align="right">'.Nvl(formatNumber(f($row,'valor_aditivo'),2),'---').'</td>');
        } else {
          ShowHTML('        <td>'.f($row,'objeto').'</td>');
        }
        ShowHTML('        <td class="remover" align="top" nowrap>');
        if ($i==0) {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_aditivo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_aditivo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
          if (substr($SG,0,3)!='GCZ'/* && (f($row,'prorrogacao')=='S' || f($row,'revisao')=='S' || f($row,'acrescimo')=='S' || f($row,'supressao')=='S')*/) {
            ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'PARCELAS'.'&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.f($row,'sq_acordo_aditivo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.substr($SG,0,3).'PARC'.'" target="Parcelas">Parcelas</A>&nbsp');
          }
          $i = 1;
        } else {
          ShowHTML('          ---');
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    if ($w_diferenca) ShowHTML('    <tr><td colspan=3><b>Observação: linhas vermelhas têm diferença entre o valor do aditivo e a soma das parcelas.</b>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    if ($O!='E' && substr($SG,0,3)!='GCZ') {
      ShowHTML('      <tr><td><font size=1></td></tr>');
      ShowHTML('      <tr><td colspan=3 bgcolor="#D0D0D0" style="border: 1px solid rgb(0,0,0);"><b><font color="#BC3131">');
      ShowHTML('        ATENÇÃO:<ul>');
      if ($O=='A') {
        ShowHTML('        <li>Se você alterar qualquer valor deste aditivo, ou se mudar os indicativos de reajuste ou acréscimo/supressão, será necessário verificar as parcelas ligadas a ele.');
      } else {
        ShowHTML('        <li>Após incluir este aditivo, verifique se é necessário gerar suas parcelas.');
      }
      ShowHTML('        <li>Se o aditivo for apenas de acréscimo/supressão, o sistema irá ajustar automaticamente seu início e término para coincidir com os períodos de referência das parcelas do primeiro e do último mês do aditivo.');
      ShowHTML('        </ul></b></font></td>');
      ShowHTML('      </tr>');
    }
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_aditivo_fim" value="'.FormataDataEdicao($w_aditivo_fim).'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if($O=='E') {
      ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.$w_inicio.'">');
      ShowHTML('<INPUT type="hidden" name="w_fim" value="'.$w_fim.'">');
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td width="25%"><td width="25%"><td width="25%"><td width="25%"></tr>');
    if (substr($SG,0,3)!='GCZ' && $w_segmento=='Público') {
      ShowHTML('          <tr>');
      SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',nvl($w_sq_cc,f($RS_Solic,'sq_cc')),null,'w_sq_cc','SIWSOLIC');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_cc" value="'.f($RS_Solic,'sq_cc').'">');
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>C</u>ódigo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="STI" SIZE="30" MAXLENGTH="30" VALUE="'.$w_codigo.'" title="Código de identificação do aditivo."></td>');
    if ($w_prorrogacao=='S') {
      if ($O=='I') $w_inicio = formataDataEdicao(addDays($w_contrato_fim,1));
      ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.$w_inicio.'">');
      ShowHTML('          <td>Início:<br><b>'.$w_inicio.'</b></td>');
    } else {
      ShowHTML('          <td><b><u>I</u>nício:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_inicio').'</td>');
    }
    ShowHTML('          <td><b><u>F</u>im:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_fim').'</td>');

    ShowHTML('      <tr><td colspan="4"><b><u>O</u>bjeto:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_objeto" class="STI" ROWS=5 cols=65 title="Objeto do aditamento.">'.$w_objeto.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="4"><b>O<u>b</u>servação:</b><br><textarea '.$w_Disabled.' accesskey="B" name="w_observacao" class="STI" ROWS=5 cols=65 title="Observações gerais sobre o aditivo.">'.$w_observacao.'</TEXTAREA></td>');

    if ($w_cliente!=10135) { 
      //ABDI
      ShowHTML('      <tr><td><b>D<u>o</u>cumento de origem:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_doc_origem" class="STI" SIZE="30" MAXLENGTH="30" VALUE="'.$w_doc_origem.'" title="Registre o tipo e o número do documento que originou o aditivo."></td>');
      ShowHTML('          <td><b>D<u>a</u>ta do documento:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_doc_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_doc_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_doc_data').'</td>');
    }
    ShowHTML('          <tr>');

    ShowHTML('      <tr valign="top"><td colspan="4"><b></b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="25%"><td width="25%"><td width="25%"><td width="25%"></tr>');
    ShowHTML('        <tr valign="top">');
    if($O=='A') {
      ShowHTML('      <td><b>Prorrogação:</b><br>'.retornaSimNao($w_prorrogacao).'</td>');
      ShowHTML('<INPUT type="hidden" name="w_prorrogacao" value="'.$w_prorrogacao.'">');
    } else {
      MontaRadioNS('<b>Prorrogação?</b>',$w_prorrogacao,'w_prorrogacao','Marque a opção SIM se este aditivo prorrogar o contrato original.',null,'onClick="recarrega();"');
    }
    if(($w_prorrogacao=='S') && substr($SG,0,3)!='GCZ') {
      ShowHTML('        <td><b>$ total da <u>p</u>rorrogação:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_valor_inicial" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_inicial.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor total do aditivo, referente ao valor inicial do contrato."></td>');
      ShowHTML('        <td><b>$ da parcela da <u>p</u>rorrogação:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_parcela_inicial" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_parcela_inicial.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor de cada parcela do aditivo, referente ao valor inicial das parcelas do contrato."></td>');
    }
    ShowHTML('        </tr></table>');
    
    ShowHTML('      <tr valign="top"><td colspan="4"><b></b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="25%"><td width="25%"><td width="25%"><td width="25%"></tr>');
    ShowHTML('        <tr valign="top">');
    if (substr($SG,0,3)!='GCZ') {
      MontaRadioNS('<b>Reajuste?</b>',$w_revisao,'w_revisao','Marque a opção SIM se este aditivo reajustar o contrato original.',null,'onClick="recarrega();"');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_revisao" value="N">');
    }
    if($w_revisao=='S') {
      ShowHTML('        <td><b>$ total do <u>r</u>eajuste:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor_reajuste" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_reajuste.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor total do reajuste, referente ao reajuste do valor ao valor de reajuste do contrato."></td>');
      ShowHTML('        <td><b>$ da parcela do <u>r</u>eajuste:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_parcela_reajustada" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_parcela_reajustada.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor de cada parcela do aditivo, referente ao reajuste do valor inicial das parcelas do contrato"></td>');
    }
    ShowHTML('        </tr></table>');

    ShowHTML('      <tr valign="top"><td colspan="4"><b></b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="25%"><td width="25%"><td width="25%"><td width="25%"></tr>');
    ShowHTML('        <tr valign="top">');
    if(substr($SG,0,3)!='GCZ' && f($RS_Solic,'limite_variacao')>0) {
      ShowHTML('          <td valign="top"><b><u>A</u>créscimo/Supressão</b><br><SELECT ACCESSKEY="A" CLASS="STS" NAME="w_tipo" '.$w_Disabled.' onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&O='.$O.'&SG='.$SG.'\'; document.Form.w_troca.value=\'TROCA\'; document.Form.submit();">');
      ShowHTML('          <option value="">---');
      ShowHTML('          <option value="ACRESCIMO"'.((Nvl($w_tipo,'')=='ACRESCIMO') ? ' SELECTED' : '').'>Acréscimo');
      ShowHTML('          <option value="SUPRESSAO"'.((Nvl($w_tipo,'')=='SUPRESSAO') ? ' SELECTED' : '').'>Supressão');
      ShowHTML('          <option value="NAOAPLICA"'.((Nvl($w_tipo,'')=='NAOAPLICA') ? ' SELECTED' : '').'>Não se aplica');
      ShowHTML('          </select>');
      if ($w_tipo!='NAOAPLICA' && nvl($w_tipo,'')!='') {
        ShowHTML('          <td><b>% de acré<u>s</u>cimo/supressão:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_variacao_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_variacao_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,6,event);" title="Percentual de acréscimo ou supressão."></td>');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_variacao_valor" value="0,000000">');
      }
    } elseif (substr($SG,0,3)=='GCZ') {
      ShowHTML('<INPUT type="hidden" name="w_tipo" value="NAOAPLICA">');
      ShowHTML('<INPUT type="hidden" name="w_variacao_valor" value="200,000000">');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_tipo" value="NAOAPLICA">');
      ShowHTML('<INPUT type="hidden" name="w_variacao_valor" value="0,000000">');
    }
    if(substr($SG,0,3)!='GCZ' && f($RS_Solic,'limite_variacao')>0 && $w_tipo!='NAOAPLICA' && nvl($w_tipo,'')!='') {
      ShowHTML('        <td><b>$ total do a<u>c</u>réscimo/supressao:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_valor_acrescimo" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_acrescimo.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor total do aditivo, referente ao acrescimo ao acréscimo/supressão do valor inicial do contrato."></td>');
      ShowHTML('        <td><b>$ da parcela do a<u>c</u>réscimo/supressao:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_parcela_acrescida" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_parcela_acrescida.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor de cada parcela do aditivo, referente ao acréscimo/supressão do valor inicial das parcelas do contrato."></td>');
    }
    ShowHTML('        </tr></table>');

    ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="4"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');

    if ($O=='A') {
      ShowHTML('<tr><td colspan="2"><br><br><b>Anexos do aditivo (máximo de '.formatNumber((f($RS_Cliente, 'upload_maximo') / 1024), 0).' KBytes): (<a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'relAnexo&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&w_chave_aux=' . $w_chave_aux . '&O=I&w_tipo_reg=' . $w_tipo_reg . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&w_cumprimento=' . $w_cumprimento . '&SG=' . $SG . MontaFiltro('GET') . '"><u>I</u>ncluir</a>)<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
      $sql = new db_getAditivoAnexo; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, null);
      $RS = SortArray($RS, 'nome', 'asc', 'tamanho', 'asc');
      ShowHTML('  <tr><td colspan="2">');
      ShowHTML('      <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
      ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('          <td width="1%"><b>Nº</td>');
      ShowHTML('          <td><b>Título</td>');
      ShowHTML('          <td><b>Tipo</td>');
      ShowHTML('          <td><b>KB</td>');
      ShowHTML('          <td><b>Operações</td>');
      ShowHTML('        </tr>');
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><font color="#BC3131"><b>NÃO HÁ ARQUIVOS EM ANEXO.</b></b></td></tr>');
      } else {
        $i = 1;
        foreach ($RS as $row) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('        <td align="center"><b>' . $i++ . '</b></td>');
          ShowHTML('        <td>' . LinkArquivo('HL', $w_cliente, f($row, 'arquivo'), null, null, f($row,'nome'), null) . '</td>');
          ShowHTML('        <td align="center">' . nvl(f($row, 'tipo'), '---') . '</td>');
          ShowHTML('        <td align="center">' . round(f($row, 'tamanho') / 1024, 1) . '</td>');
          ShowHTML('        <td align="center" nowrap>');
          ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave=' . f($row, 'chave') . '&w_chave_aux=' . f($row, 'chave_aux') . '&w_arquivo=' . f($row, 'arquivo') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=ACADIANEXO' . MontaFiltro('GET') . '" title="Exclusão do arquivo." onClick="return(confirm(\'Confirma exclusão do arquivo?\'));">EX</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        }
      }
      ShowHTML('</table>');
    }
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de inclusão de arquivos
// -------------------------------------------------------------------------
function relAnexo() {
  extract($GLOBALS);
  global $w_Disabled;
  //exibeArray($_REQUEST);
  $w_chave       = $_REQUEST['w_chave'];
  $w_chave_aux   = $_REQUEST['w_chave_aux'];
  $w_tipo_reg    = $_REQUEST['w_tipo_reg'];
  $w_cumprimento = $_REQUEST['w_cumprimento'];
  $SG = upper($_REQUEST['SG']);
  $par = upper($_REQUEST['par']);

  
  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  ShowHTML('$(document).ready(function() {');
  ShowHTML('  $("#upload").uploadify({');
  ShowHTML('    "uploader": "' . $conRootSIW . 'classes/uploadify/uploadify.swf",');
  ShowHTML('      "script": "' . $conRootSIW . 'funcoes/upload.php",');
  ShowHTML('      "sizeLimit": "' . f($RS_Cliente, 'upload_maximo') . '",');
  ShowHTML('      "buttonText": "Selecionar",');
  ShowHTML('      "scriptData": {"w_caminho":"' . DiretorioCliente($w_cliente) . '", "w_origem":"ADITIVO", "w_chave":"' . $w_chave . '", "w_chave_aux":"' . $w_chave_aux . '", "w_arquivo":"", "dbms":"' . $_SESSION['DBMS'] . '", "sid":"' . session_id() . '"},');
  ShowHTML('      "onAllComplete" : function(event,data) {alert(data.filesUploaded  + " arquivos(" + data.allBytesLoaded + " bytes) adicionados com sucesso.");document.location.href="' . montaURL_JS($w_dir, $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG='.$SG.'&w_chave=' . $w_chave . '&w_chave_aux=' . $w_chave_aux . '&O=A') . '";},');
  #ShowHTML('      "onComplete" : function(event, queueID, fileObj, response, data) {alert(fileObj.name + response + data);},');
  ShowHTML('      "multi": "true",');
  ShowHTML('      "cancelImg": "' . $conRootSIW . 'classes/uploadify/cancel.png"');
  ShowHTML('  });');
  ShowHTML('});');
  ScriptClose();
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  BodyOpenClean('onLoad="this.focus();"');
  ShowHTML('<table width="100%" border="0" cellpadding="10" cellspacing="0">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Anexos</td></td></tr>');
  ShowHTML('      <tr valign="middle"><td colspan="5" align="left" height="1" bgcolor="#ffffff">Para adicionar anexos, clique em <b>selecionar</b>, localize os arquivos que deseja anexar, em seguida pressione o botão <b>Anexar arquivos</b>.');
  ShowHTML('          <br><br>Observações:<ul style="line-height:150%">');
  ShowHTML('<li>Pode-se usar a tecla <b>Ctrl</b> para selecionar mais de um arquivo no mesmo diretório.</li>');
  ShowHTML('<li>O botão <b>Limpar fila</b> limpa a fila de arquivos selecionados(ainda não anexados), caso se deseje descarta-los.</li>');
  ShowHTML('<li>O botão <img border="0" src="images/cancel.png"> para excluir arquivos específicos da lista.</li>');
  ShowHTML('</ul></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('<tr>');
  ShowHTML('<td align="center" bgcolor="#f5f5f5"><br>');
  ShowHTML('<br><input type="file" id="upload"><br>');
  ShowHTML('</td>');
  ShowHTML('</tr>');
  ShowHTML('<tr>');
  ShowHTML('<tr>');
  ShowHTML('<td align="center" bgcolor="#f5f5f5">');
  ShowHTML('  <button class="stb" onclick="javascript:$(\'#upload\').uploadifyUpload()">Anexar arquivos</button>');
  ShowHTML('  <button class="stb" onclick="javascript:$(\'#upload\').uploadifyClearQueue()">Limpar fila</button>');
  ShowHTML('  <input class="stb" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R. '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG='.$SG.'&w_chave=' . $w_chave . '&w_tipo_reg=' . $w_tipo_reg . '&w_cumprimento=' . $w_cumprimento . '&O=L') . '\';" name="Botao" value="Cancelar">');
  ShowHTML('');
  ShowHTML('</td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
}

// ------------------------------------------------------------------------- 
// Rotina de notas de empenho
// ------------------------------------------------------------------------- 
function Notas() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página 
    $w_sq_tipo_documento     = $_REQUEST['w_sq_tipo_documento'];
    $w_sq_acordo_outra_parte = $_REQUEST['w_sq_acordo_outra_parte'];
    $w_sq_acordo_aditivo     = $_REQUEST['w_sq_acordo_aditivo'];
    $w_numero                = $_REQUEST['w_numero'];
    $w_data                  = $_REQUEST['w_data'];
    $w_valor                 = $_REQUEST['w_valor'];
    $w_sq_lcfonte_recurso    = $_REQUEST['w_sq_lcfonte_recurso'];
    $w_espec_despesa         = $_REQUEST['w_espec_despesa'];
    $w_observacao            = $_REQUEST['w_observacao'];
    $w_abrange_inicial       = $_REQUEST['w_abrange_inicial'];
    $w_abrange_acrescimo     = $_REQUEST['w_abrange_acrescimo'];
    $w_abrange_reajuste      = $_REQUEST['w_abrange_reajuste'];
    $w_sq_acordo_parcela     = $_REQUEST['w_sq_acordo_parcela'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $sql = new db_getAcordoNota; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_chave,null,null,null,null,null,null);
    $RS = SortArray($RS,'data','desc','sq_acordo_aditivo','desc','numero','asc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endereço informado 
    $sql = new db_getAcordoNota; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave_aux,$w_chave,null,null,null,null,null,null);
    foreach ($RS as $row) {
      $w_chave_aux             = f($row,'sq_acordo_nota');
      $w_sq_tipo_documento     = f($row,'sq_tipo_documento');
      $w_sq_acordo_outra_parte = f($row,'sq_acordo_outra_parte');
      $w_sq_acordo_aditivo     = f($row,'sq_acordo_aditivo');
      $w_numero                = f($row,'numero');
      $w_data                  = FormataDataEdicao(f($row,'data'));
      $w_valor                 = formatNumber(f($row,'valor'));
      $w_sq_lcfonte_recurso    = f($row,'sq_lcfonte_recurso');
      $w_espec_despesa         = f($row,'sq_especificacao_despesa');
      $w_observacao            = f($row,'observacao');
      $w_abrange_inicial       = f($row,'abrange_inicial');
      $w_abrange_acrescimo     = f($row,'abrange_acrescimo');
      $w_abrange_reajuste      = f($row,'abrange_reajuste');
      break;
    }
  } 
   Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Notas de empenho</TITLE>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_sq_tipo_documento','Tipo do documento','SELECT',1,1,18,'','0123456789');
      Validate('w_sq_acordo_outra_parte','Outra parte','SELECT','',1,18,'','0123456789');
      if($P1!=1) Validate('w_sq_acordo_aditivo','Aditivo','SELECT','',1,18,'','0123456789');
      Validate('w_numero','Número','1','1','1','30','1','1');
      Validate('w_data','Data','DATA','1','10','10','','0123456789/');
      Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
      Validate('w_sq_lcfonte_recurso','Fonte de recursos','SELECT','1',1,18,'','0123456789');
      Validate('w_espec_despesa','Especificação de despesa','SELECT','1',1,18,'','0123456789');
      Validate('w_observacao','Observação','1','','1','500','1','1');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_sq_acordo_parcela[]"].value==undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_sq_acordo_parcela[]"].length; i++) {');
      ShowHTML('       if (theForm["w_sq_acordo_parcela[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_sq_acordo_parcela[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve informar pelo menos uma parcela!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_sq_tipo_documento.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    if($P1!=1) {
      $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
      $w_executor = f($RS1,'executor');
      ShowHTML('      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
      if (substr($w_sigla,0,3)=='GCA') ShowHTML('      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><b>ACT: '.f($RS1,'codigo_interno').' - '.f($RS1,'titulo').' ('.$w_chave.')'.'</b></div></td></tr>');
      else                             ShowHTML('      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><b>CONTRATO: '.f($RS1,'codigo_interno').' - '.f($RS1,'titulo').' ('.$w_chave.')'.'</b></div></td></tr>');
      ShowHTML('      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    }
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td>');
    if ($P1!=6) {
      ShowHTML('    <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    }
    if($P1==2||$P1==6) ShowHTML('        <a accesskey="F" class="ss" href="javascript:window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if($P1!=1) ShowHTML('          <td rowspan="2"><b>Aditivo</td>');
    ShowHTML('          <td rowspan="2"><b>Numero</td>');
    ShowHTML('          <td rowspan="2"><b>Outra parte</td>');
    ShowHTML('          <td rowspan="2"><b>Data</td>');
    ShowHTML('          <td colspan="3"><b>Valores</td>');
    ShowHTML('          <td class="remover" rowspan="2"><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Emissão</td>');
    ShowHTML('          <td><b>Cancelamento</td>');
    ShowHTML('          <td><b>Total</td>');
    ShowHTML('        </tr>');
    $w_nota         = 0;
    $w_cancelamento = 0;
    $w_total        = 0;
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if($P1!=1) ShowHTML('        <td>'.nvl(f($row,'cd_aditivo'),'---').'</td>');
        ShowHTML('        <td>'.f($row,'sg_tipo_documento').' '.f($row,'numero').'&nbsp;');
        if (f($row,'abrange_inicial')=='S')    ShowHTML('('.f($row,'sg_inicial').')');
        if (f($row,'abrange_acrescimo')=='S')  ShowHTML('('.f($row,'sg_acrescimo').')');
        if (f($row,'abrange_reajuste')=='S')   ShowHTML('('.f($row,'sg_reajuste').')');
        ShowHTML('        <td>'.nvl(f($row,'nm_outra_parte'),'---').'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'data'),5),'---').'</td>');
        ShowHTML('        <td align="right">'.Nvl(formatNumber(f($row,'valor'),2),'---').'</td>');
        ShowHTML('        <td align="right">'.Nvl(formatNumber(f($row,'vl_cancelamento'),2),'---').'</td>');
        ShowHTML('        <td align="right">'.Nvl(formatNumber(f($row,'valor')-f($row,'vl_cancelamento'),2),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_nota').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_nota').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'NOTACANCEL&R='.$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_nota').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Cancelamento').'&SG=GCDNTCANCEL\',\'NotaCancelamento\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');"Botao=Selecionar">NCE</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_nota         += f($row,'valor');
        $w_cancelamento += f($row,'vl_cancelamento');
        $w_total        += (f($row,'valor')-f($row,'vl_cancelamento'));
      } 
    } 
    ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
    ShowHTML('        <td colspan="3" align="right">&nbsp;</td>');
    ShowHTML('        <td align="right"><b>Totais</b></td>');
    ShowHTML('        <td align="right"><b>'.formatNumber($w_nota,2).'</b></td>');
    ShowHTML('        <td align="right"><b>'.formatNumber($w_cancelamento,2).'</b></td>');
    ShowHTML('        <td align="right"><b>'.formatNumber($w_total,2).'</b></td>');
    ShowHTML('        <td>&nbsp;</td>');
    ShowHTML('      </tr>');    
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_parcela[]" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoDocumento('<u>T</u>ipo de documento:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,null,'w_sq_tipo_documento',null,null);
    SelecaoOutraParte('<u>O</u>utra parte:','O', 'Selecione a outra parte favorecida da nota.', $w_sq_acordo_outra_parte,$w_chave,'w_sq_acordo_outra_parte',null,null);
    if($P1!=1)SelecaoAditivo('<u>A</u>ditivo:','A', 'Selecione o aditivo.', $w_sq_acordo_aditivo,$w_chave,'w_sq_acordo_aditivo',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.SG.value=\''.$SG.'\';document.Form.w_troca.value=\'w_numero\'; document.Form.submit();"');
    else      ShowHTML('<INPUT type="hidden" name="w_sq_acordo_aditivo" value="'.$w_sq_acordo_aditivo.'">');
    ShowHTML('      <tr><td><b><u>N</u>úmero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="STI" SIZE="30" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Numero da nota."></td>');
    ShowHTML('          <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data').'</td>');
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor da nota."></td>');
    ShowHTML('      <tr valign="top">');
    selecaoLCFonteRecurso('<U>F</U>onte de recurso:','F','Selecione a fonte de recurso',$w_sq_lcfonte_recurso,null,'w_sq_lcfonte_recurso',null,null);
    if(nvl($w_sq_acordo_aditivo,'')>'') {
      $sql = new db_getAcordoAditivo; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_acordo_aditivo,null,null,null,null,null,null,null,null,null,null);
      foreach($RS as $row){$RS=$row; break;}
      selecaoCTEspecificacao('<u>E</u>specificação de despesa:','E','Selecione a especificação de despesa.',$w_espec_despesa,$w_espec_despesa,f($RS,'sq_cc'),$_SESSION['ANO'],'w_espec_despesa','S',null,null);
    } else {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
      selecaoCTEspecificacao('<u>E</u>specificação de despesa:','E','Selecione a especificação de despesa.',$w_espec_despesa,$w_espec_despesa,f($RS,'sq_cc'),$_SESSION['ANO'],'w_espec_despesa','S',null,null);
    }
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Contempla valor inicial?</b>',$w_abrange_inicial,'w_abrange_inicial');
    MontaRadioNS('<b>Contempla acréscimo?</b>',$w_abrange_acrescimo,'w_abrange_acrescimo');
    MontaRadioNS('<b>Contempla reajuste?</b>',$w_abrange_reajuste,'w_abrange_reajuste');
    ShowHTML('      <tr><td colspan="3"><b>O<u>b</u>servação:</b><br><textarea '.$w_Disabled.' accesskey="B" name="w_observacao" class="STI" ROWS=5 cols=65 title="Observações gerais sobre o aditivo.">'.$w_observacao.'</TEXTAREA></td>');
    //Parcelas a serem ligadas na nota de empenho.
    if(nvl($w_sq_acordo_aditivo,'')>'') ShowHTML('      <tr><td colspan="3"><br><b>Parcelas do aditivo selecionado:</b>');
    else                                ShowHTML('      <tr><td colspan="3"><br><b>Parcelas do contrato:</b>');
    $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'GCDCAD');
    $sql = new db_getAcordoParcela; $RS_Parc = $sql->getInstanceOf($dbms,$w_chave,null,'NOTA',null,null,null,null,null,null,$w_sq_acordo_aditivo);
    $RS_Parc = SortArray($RS_Parc,'ordem','asc');
    ShowHTML('  <tr><td colspan="3">');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td rowspan=2><b>&nbsp;</b></td>');
    ShowHTML('            <td rowspan=2><b>Nº</b></td>');
    ShowHTML('            <td rowspan=2><b>Período</b></td>');
    ShowHTML('            <td rowspan=2><b>Venc.</b></td>');
    ShowHTML('            <td colspan=4><b>Valores</b></td>');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td><b>Inicial</b></td>');
    ShowHTML('            <td><b>Reajuste</b></td>');
    ShowHTML('            <td><b>Acr.Supr.</b></td>');
    ShowHTML('            <td><b>Total</b></td>');
    if (count($RS_Parc)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      if(nvl($w_sq_acordo_aditivo,'')>'') ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><font size="2"><b>Cadastre antes as parcelas do aditivo.</b></font></td></tr>');
      else                                ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><font size="2"><b>Cadastre antes as parcelas do contrato.</b></font></td></tr>');
    } else {
      $w_vl_inicial  = 0;
      $w_vl_acresc   = 0;
      $w_vl_reajuste = 0;
      $w_vl_total    = 0;
      foreach($RS_Parc as $row) {
        $w_cont+= 1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        $sql = new db_getAcordoNota; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave_aux,f($row,'sq_acordo_parcela'),null,null,null,null,null,'PARCELA');
        if(count($RS)>0) ShowHTML('        <td width="1%" nowrap align="center"><input '.$w_Disabled.' type="checkbox" name="w_sq_acordo_parcela[]" value="'.f($row,'sq_acordo_parcela').'" CHECKED>');
        else             ShowHTML('        <td width="1%" nowrap align="center"><input '.$w_Disabled.' type="checkbox" name="w_sq_acordo_parcela[]" value="'.f($row,'sq_acordo_parcela').'">');
        ShowHTML('        <td>');
        if (nvl(f($row,'quitacao'),'nulo')=='nulo') {
          if (f($row,'vencimento')<addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center">');
          } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
            ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } else {
          if (f($row,'quitacao')<f($row,'vencimento')) {
            ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } 
        ShowHTML('        '.substr(1000+f($row,'ordem'),1,3).'</td>');
        if(nvl(f($row,'inicio'),'')!='') ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).' a '.FormataDataEdicao(f($row,'fim')).'</td>');
        else                             ShowHTML('        <td align="center">---</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'vencimento')).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_inicial'),2).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_reajuste'),2).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_excedente'),2).'</td>');
        ShowHTML('        <td align="right"><b>'.formatNumber(f($row,'valor'),2).'</b></td>');
        ShowHTML('      </tr>');
        if (count($RS)>0 || $O=='I') $w_vl_inicial  += f($row,'valor_inicial');
        if (count($RS)>0 || $O=='I') $w_vl_acresc   += f($row,'valor_excedente');
        if (count($RS)>0 || $O=='I') $w_vl_reajuste += f($row,'valor_reajuste');
        if (count($RS)>0 || $O=='I') $w_vl_total    += f($row,'valor');
      } 
    }     
    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
    ShowHTML('        <td align="right" colspan=4><b>Totais:&nbsp;</b>');
    ShowHTML('        <td align="right"><b>'.formatNumber($w_vl_inicial,2).'</b></td>');
    ShowHTML('        <td align="right"><b>'.formatNumber($w_vl_reajuste,2).'</b></td>');
    ShowHTML('        <td align="right"><b>'.formatNumber($w_vl_acresc,2).'</b></td>');
    ShowHTML('        <td align="right"><b>'.formatNumber($w_vl_total,2).'</b></td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('      <tr><td align="center" colspan="3"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Rodape();
}

// ------------------------------------------------------------------------- 
// Rotina de cancelamento de notas de empenho
// ------------------------------------------------------------------------- 
function NotaCancel() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_chave_aux2 = $_REQUEST['w_chave_aux2'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página 
    $w_data  = $_REQUEST['w_data'];
    $w_valor = $_REQUEST['w_valor'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getAcordoNotaCancel; $RS = $sql->getInstanceOf($dbms,null,$w_chave_aux,null,null,null,null);
    $RS = SortArray($RS,'data','desc','sq_acordo_nota','desc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endereço informado 
    $sql = new db_getAcordoNotaCancel; $RS = $sql->getInstanceOf($dbms,null,null,$w_chave_aux2,null,null,null);
    foreach ($RS as $row) {
      $w_chave_aux2 = f($row,'chave_aux2');
      $w_data       = FormataDataEdicao(f($row,'data'));
      $w_valor      = formatNumber(f($row,'valor'));
      break;
    }
  } 
   Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Cancelamento de notas de empenho</TITLE>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_data','Data','DATA','1','10','10','','0123456789/');
      Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_data.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr valign="top"><td colspan=3>');
  ShowHTML('<table border="0" width="100%">');
  $sql = new db_getAcordoNota; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_chave_aux,null,null,null,null,null,null,null);
  foreach($RS1 as $row){$RS1=$row; break;}
  ShowHTML('      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  ShowHTML('      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><b>NOTA: '.f($RS1,'sg_tipo_documento').' '.f($RS1,'numero'));
  if (f($row,'abrange_inicial')=='S')    ShowHTML('('.f($row,'sg_inicial').')');
  if (f($row,'abrange_acrescimo')=='S')  ShowHTML('('.f($row,'sg_acrescimo').')');
  if (f($row,'abrange_reajuste')=='S')   ShowHTML('('.f($row,'sg_reajuste').')');
  ShowHTML('      <tr><td bgcolor="#f0f0f0"><div align=justify><b>Data: '.FormataDataEdicao(f($RS1,'data')).'</b></div></td>');
  ShowHTML('          <td bgcolor="#f0f0f0"><div align=justify><b>Valor: '.formatNumber(f($RS1,'valor')).'</b></div></td>');
  ShowHTML('      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  ShowHTML('    </table>');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave_aux='.$w_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" href="javascript:window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    $w_total        = 0;
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'data'),5),'---').'</td>');
        ShowHTML('        <td align="right">'.Nvl(formatNumber(f($row,'valor'),2),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave_aux='.$w_chave_aux.'&w_chave_aux2='.f($row,'chave_aux2').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave_aux='.$w_chave_aux.'&w_chave_aux2='.f($row,'chave_aux2').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_nota         += f($row,'valor');
        $w_cancelamento += f($row,'valor_cancelamento');
        $w_total        += (f($row,'valor')-f($row,'valor_cancelamento'));
      } 
    } 
    if($w_total>0) {
      ShowHTML('      <tr bgcolor="'.$conTrAlternateBgColor.'" valign="top">');
      ShowHTML('        <td align="right"><b>Total</b></td>');
      ShowHTML('        <td align="right"><b>'.formatNumber($w_total,2).'</b></td>');
      ShowHTML('        <td>&nbsp;</td>');
      ShowHTML('      </tr>');    
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux2" value="'.$w_chave_aux2.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>D</u>ata cancelamento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data').'</td>');
    ShowHTML('        <td><b><u>V</u>alor cancelamento:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor de cancelamento da nota."></td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('      <tr><td align="center" colspan="3"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&w_chave_aux2='.$w_chave_aux2.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Rodape();
}
 
// =========================================================================
// Rotina de preparação para envio de e-mail relativo a contratos
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação. 
//            p_tipo:  1 - Inclusão
//                     2 - Tramitação
//                     3 - Conclusão
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $sql = new db_getSolicData; $RSM = $sql->getInstanceOf($dbms,$p_solic,substr($SG,0,3).'GERAL');
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    // Recupera os dados da tarefa
    $w_html = '<HTML>'.$crlf;
    $w_html.=BodyOpenMail(null).$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1)       $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==2)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITAÇÃO DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==3)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUSÃO DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><b><font size=2 color="#BC3131">ATENÇÃO: Esta é uma mensagem de envio automático. Não responda esta mensagem.</font></b><br><br><td></tr>'.$crlf;
    $w_nome = f($RSM,'nome').' '.f($RSM,'codigo_interno').' ('.f($RSM,'sq_siw_solicitacao').')';
    $w_html.=$crlf.'<tr><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $w_html.=$crlf.'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>'.f($RSM,'nome').': '.f($RSM,'codigo_interno').' '.CRLF2BR(f($RSM,'objeto')).' ('.f($RSM,'sq_siw_solicitacao').')</b></font></div></td></tr>';
    $w_html.=$crlf.'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação do contrato
    $w_html.=$crlf.'      <tr><td width="30%"><td>';
    if (nvl(f($RSM,'nm_projeto'),'')>'') {
      $w_html.=$crlf.'      <tr><td>Projeto: <br><b>'.f($RSM,'nm_projeto').'  ('.f($RSM,'sq_solic_pai').')</b></td>';
    } 
    // Se a classificação foi informada, exibe.
    if (nvl(f($RSM,'sq_cc'),'')>'') {
      $w_html.=$crlf.'      <tr><td><b>Classificação:</b></td>';
      $w_html.=$crlf.'        <td>'.f($RSM,'nm_cc').' </b></td>';
    } 
    $w_html.=$crlf.'        <tr><td><b>Responsável pelo monitoramento:</b></td>';
    $w_html.=$crlf.'          <td>'.f($RSM,'nm_solic').'</td></tr>';
    $w_html.=$crlf.'        <tr><td><b>Unidade responsável pelo monitoramento:</b></td>';
    $w_html.=$crlf.'          <td>'.f($RSM,'nm_unidade_resp').'</td></tr>';
    $w_html.=$crlf.'        <tr><td><b>Início vigência:</b></td>';
    $w_html.=$crlf.'          <td>'.FormataDataEdicao(f($RSM,'inicio')).' </td></tr>';
    $w_html.=$crlf.'        <tr><td><b>Término vigência:</b></td>';
    $w_html.=$crlf.'          <td>'.FormataDataEdicao(f($RSM,'fim')).' </td></tr>';
    // Outra parte
    $sql = new db_getBenef; $RSM1 = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($RSM,'outra_parte'),0),null,null,null,null,Nvl(f($RSM,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null, null, null, null, null);
    if (count($RSM1)>0) {
      foreach($RSM1 as $row) {
        if (substr($SG,0,3)=='GCB')   $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>BOLSISTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'.$crlf;
        else                          $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRA PARTE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'.$crlf;
        $w_html.=$crlf.'      <tr><td colspan=2>';
        $w_html.=$crlf.'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
        if (Nvl(f($RSM,'sq_tipo_pessoa'),0)==1) {
          $w_html.=$crlf.'          - '.f($row,'cpf');
        } else {
          $w_html.=$crlf.'          - '.f($row,'cnpj');
        } 
      }
    } 
    //  $w_html.=$crlf.'</tr>';
    //Recupera o último log
    $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
    foreach ($RS as $row) { $RS = $row; if(nvl(f($row,'destinatario'),'')!='') break; }
    $w_data_encaminhamento = f($RS,'phpdt_data');
    if ($p_tipo == 2) { // Se for tramitação
      // Encaminhamento
      $w_html .= $crlf.'      <tr><td colspan="2"><br><font size="2"><b>ÚLTIMO ENCAMINHAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html .= $crlf.'      <tr><td valign="top" colspan="2">';
      $w_html .= $crlf.'      <tr><td><b>De:</b></td>';
      $w_html .= $crlf.'        <td>'.f($RS,'responsavel').'</td></tr>';
      $w_html .= $crlf.'      <tr><td><b>Para:</b></td>';
      $w_html .= $crlf.'        <td>'.f($RS,'destinatario').'</td></tr>';
      $w_html .= $crlf.'      <tr><td><b>Despacho:</b></td>';
      $w_html .= $crlf.'        <td>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </td></tr>';

      // Configura o destinatário da tramitação como destinatário da mensagem
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl(f($RS,'sq_pessoa_destinatario'),0),null,null);
      $w_destinatarios = f($RS,'email').'|'.f($RS,'nome').'; ';
    }  
    $w_html .= $crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRAS INFORMAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'.$crlf;
    $sql = new db_getCustomerSite; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    $w_html .='      <tr valign="top"><td colspan="2">';
    $w_html .='         Para acessar o sistema use o endereço: <b><a class="ss" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html .='      </td></tr>'.$crlf;
    $w_html .= '      <tr valign="top"><td colspan="2">'.$crlf;
    $w_html .='         Dados da ocorrência:<br>'.$crlf;
    $w_html .='         <ul>'.$crlf;
    $w_html .='         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html .='         <li>Data: <b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
    $w_html .='         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html .='         </ul>'.$crlf;
    $w_html .='      </td></tr>'.$crlf;
    $w_html .='    </table>'.$crlf;
    $w_html .='</td></tr>'.$crlf;
    $w_html .='</table>'.$crlf;
    $w_html .='</BODY>'.$crlf;
    $w_html .='</HTML>'.$crlf;
    
    if(f($RSM,'st_sol')=='S') {
      // Recupera o e-mail do responsável
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    // Recupera o e-mail do titular e do substituto pelo setor responsável
    $sql = new db_getUorgResp; $RS = $sql->getInstanceOf($dbms,f($RSM,'sq_unidade'));
    foreach($RS as $row){$RS=$row; break;}
    if(f($RS,'st_titular')=='S')    $w_destinatarios .= f($RS,'email_titular').'|'.f($RS,'nm_titular').'; ';
    if(f($RS,'st_substituto')=='S') $w_destinatarios .= f($RS,'email_substituto').'|'.f($RS,'nm_substituto').'; ';
    // Prepara os dados necessários ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclusão ou Conclusão
      if ($p_tipo==1) $w_assunto='Inclusão - '.$w_nome; else $w_assunto='Conclusão - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramitação
      $w_assunto='Tramitação - '.$w_nome;
    } 

    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado=EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 

    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: não foi possível proceder o envio do e-mail.\n'.$w_resultado.'");');
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
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpenClean('onLoad=this.focus();');
  if (substr($SG,3,3)=='CAD') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_geraContrato; $SQL->getInstanceOf($dbms,$_REQUEST['w_solic'],$_REQUEST['w_pessoa']);

      ScriptOpen('JavaScript');
      ShowHTML('  alert("Geração concluída com sucesso!");');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit();
    }
  } elseif (strpos($SG,'GERAL')!==false) {
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
      } elseif (nvl($_REQUEST['w_codigo_interno'],'')!='' && ($O=='I' || $O=='A')) {
        $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,$w_menu,$w_usuario,$SG,5,
            null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
            null,$_REQUEST['w_codigo_interno'], null,null,null,null,null,null,null,null,null);
        if(count($RS)>0) {
          foreach($RS as $row) { $RS = $row; break; }
          if ($_REQUEST['w_chave']!=f($row,'sq_siw_solicitacao')) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: Código já existe!");');
            ScriptClose();
            RetornaFormulario(null);
            exit;
          }
        }
      }
      $SQL = new dml_putAcordoGeral; $SQL->getInstanceOf($dbms,$O, $w_cliente, 
          $_REQUEST['w_chave'], $_REQUEST['w_menu'], $_REQUEST['w_sq_unidade_resp'], $_REQUEST['w_solicitante'], 
          $_SESSION['SQ_PESSOA'], $_REQUEST['w_sqcc'], nvl($_REQUEST['w_descricao'],'.'), $_REQUEST['w_justificativa'], 
          $_REQUEST['w_inicio'], $_REQUEST['w_fim'], nvl($_REQUEST['w_valor'],0), $_REQUEST['w_data_hora'], 
          $_REQUEST['w_aviso'], $_REQUEST['w_dias'], $_REQUEST['w_cidade'],  $_REQUEST['w_chave_pai'], 
          $_REQUEST['w_sq_tipo_acordo'], $_REQUEST['w_objeto'], $_REQUEST['w_sq_tipo_pessoa'], 
          $_REQUEST['w_sq_forma_pagamento'], $_REQUEST['w_forma_atual'], $_REQUEST['w_inicio_atual'], $_REQUEST['w_etapa'],
          $_REQUEST['w_codigo_interno'],$_REQUEST['w_titulo'], null,
          nvl($_REQUEST['w_protocolo'],$_REQUEST['w_numero_processo']),null,null,
          &$w_chave_nova, $w_copia, $_REQUEST['w_herda'],&$w_codigo);
      if ($O=='I') {
        // Recupera os dados para montagem correta do menu
        $sql = new db_getMenuData; $RS1 = $sql->getInstanceOf($dbms,$w_menu);
        ScriptOpen('JavaScript');
        ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento='.$w_codigo.'&R='.$R.'&w_menu='.$w_menu.'&SG='.f($RS1,'sigla').'&TP='.RemoveTP($TP)).'\';');
      } elseif ($O=='E') {
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      } else {
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      } 
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'ADIC')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_putAcordoDadosAdicionais; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],
        $_REQUEST['w_numero_certame'],$_REQUEST['w_numero_ata'],$_REQUEST['w_tipo_reajuste'],
        nvl($_REQUEST['w_limite_variacao'],0),$_REQUEST['w_indice_base'],$_REQUEST['w_sq_eoindicador'],
        $_REQUEST['w_sq_lcfonte_recurso'],$_REQUEST['w_espec_despesa'],$_REQUEST['w_sq_lcmodalidade'],
        null, $_REQUEST['w_numero_processo'], $_REQUEST['w_data_assinatura'],
        $_REQUEST['w_data_publicacao'],$_REQUEST['w_financeiro_unico'],$_REQUEST['w_pagina_diario'],
        $_REQUEST['w_texto_pagamento'],$_REQUEST['w_valor_caucao']);

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit();
    }
  } elseif (strpos($SG,'NOTA')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {    
      if($O=='E') {
         $sql = new db_getAcordoNota; $RS_Nota = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave_aux'],null,null,null,null,null,null,'LANCAMENTO');
         if(count($RS_Nota)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Existe lançamento financeiro para esta nota, não sendo possível sua exclusão!");');
          ScriptClose();
          RetornaFormulario(null);
          exit;
        }
      }
      $SQL = new dml_putAcordoNota; 
      $SQL->getInstanceOf($dbms,'EXCLUIPARCELA',
          $_REQUEST['w_chave_aux'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
      $SQL->getInstanceOf($dbms, $O,
          $_REQUEST['w_chave_aux'],$_REQUEST['w_chave'],$_REQUEST['w_sq_tipo_documento'],
          $_REQUEST['w_sq_acordo_outra_parte'],$_REQUEST['w_sq_acordo_aditivo'],$_REQUEST['w_numero'],$_REQUEST['w_data'],
          $_REQUEST['w_valor'],$_REQUEST['w_sq_lcfonte_recurso'],$_REQUEST['w_espec_despesa'],$_REQUEST['w_observacao'],
          $_REQUEST['w_abrange_inicial'],$_REQUEST['w_abrange_acrescimo'],$_REQUEST['w_abrange_reajuste'],null,
          &$w_chave_nova);
      for ($i=0; $i<=count($_POST['w_sq_acordo_parcela'])-1; $i=$i+1) {
        if (Nvl($_REQUEST['w_sq_acordo_parcela'][$i],'')>'') {
          $SQL->getInstanceOf($dbms,'PARCELA',$w_chave_nova,null,null,null,null,null,null,null,null,null,null,
              null,null,null,$_REQUEST['w_sq_acordo_parcela'][$i],null);
        }
      }  
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      RetornaFormulario('w_assinatura');
      exit;
    }
  } elseif (strpos($SG,'NTCANCEL')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {    
      $SQL = new dml_putAcordoNotaCancel; $SQL->getInstanceOf($dbms, $O,
          $_REQUEST['w_chave_aux'],$_REQUEST['w_chave_aux2'],$_REQUEST['w_data'],$_REQUEST['w_valor']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_chave_aux='.$_REQUEST['w_chave_aux'].'&w_chave_aux2='.$_REQUEST['w_chave_aux2'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      RetornaFormulario('w_assinatura');
      exit;
    }
  } elseif (strpos($SG,'ADITIVO')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {    
      if($_REQUEST['w_tipo']=='ACRESCIMO') {
        $w_acrescimo = 'S';
        $w_supressao = 'N';
      } elseif ($_REQUEST['w_tipo']=='SUPRESSAO') {
        $w_acrescimo = 'N';
        $w_supressao = 'S';
      } else {
        $w_acrescimo = 'N';
        $w_supressao = 'N';
      }
      if($O!='E' && ($w_acrescimo=='S' || $w_supressao=='S' || $_REQUEST['w_revisao']=='S')) {
        $sql = new db_getAcordoAditivo; $RS_Aditivo = $sql->getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_chave'],null,null,$_REQUEST['w_inicio'],$_REQUEST['w_fim'],null,null,null,null,'LANCAMENTO');
        if(count($RS_Aditivo)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: O período do aditivo não pode conter nenhum lançamento financeiro liquidado!");');
          ScriptClose();
          RetornaFormulario('w_sq_cc');
          exit;
        }
      }
      if($O=='E') {
        $sql = new db_getAcordoAditivo; $RS_Aditivo = $sql->getInstanceOf($dbms,null,null,$_REQUEST['w_chave'],null,null,$_REQUEST['w_inicio'],$_REQUEST['w_fim'],null,null,null,null,'LANCAMENTOE');
        if(count($RS_Aditivo)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Existe lançamento financeiro ativo para este aditivo, não sendo possível sua exclusão!");');
          ScriptClose();
          RetornaFormulario(null);
          exit;
        } else {
          $sql = new db_getAcordoAditivo; $RS_Aditivo = $sql->getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_chave'],null,null,$_REQUEST['w_inicio'],$_REQUEST['w_fim'],null,null,null,null,'LANCAMENTOF');
          foreach($RS_Aditivo as $row) {
            if(count($RS_Aditivo)>0) {
              $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,'EXCLUSAO',$w_cliente,f($row,'sq_siw_solicitacao'),
                        f($row,'sq_menu'),null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
                        null,null,null,null,null,null,null,null,null,null,null,null);
            } 
          }
        }       
      }
      $SQL = new dml_putAcordoAditivo; $SQL->getInstanceOf($dbms, $O,
            $_REQUEST['w_chave_aux'],$_REQUEST['w_chave'],$_REQUEST['w_protocolo'],
            $_REQUEST['w_codigo'],$_REQUEST['w_objeto'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],
            $_REQUEST['w_duracao'],$_REQUEST['w_doc_origem'],$_REQUEST['w_doc_data'],nvl($_REQUEST['w_variacao_valor'],0),
            $_REQUEST['w_prorrogacao'],$_REQUEST['w_revisao'],$w_acrescimo,$w_supressao,
            $_REQUEST['w_observacao'],nvl($_REQUEST['w_valor_inicial'],0),nvl($_REQUEST['w_parcela_inicial'],0),
            nvl($_REQUEST['w_valor_reajuste'],0),nvl($_REQUEST['w_parcela_reajustada'],0),nvl($_REQUEST['w_valor_acrescimo'],0),
            nvl($_REQUEST['w_parcela_acrescida'],0),$_REQUEST['w_sq_cc'],&$w_chave_nova);
      
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      RetornaFormulario('w_assinatura');
      exit;
    } 
  } elseif (strpos($SG,'ACADIANEXO')!==false) {
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      if ($O == 'E') {
        $sql = new db_getAditivoAnexo; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_arquivo']);
        foreach ($RS as $row) {
          if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho')))
            unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
        }
        $SQL = new dml_putAditivoAnexo; $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_arquivo'], $_REQUEST['w_nome'], $_REQUEST['w_descricao'], $w_file, $w_tamanho, $w_tipo, $w_nome);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href="' . montaURL_JS($w_dir, $R . '&O=A&w_chave=' . $_REQUEST['w_chave'] . '&w_chave_aux=' . $_REQUEST['w_chave_aux'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=GCDADITIVO') . '";');
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
    break;
  } elseif (strpos($SG,'TERMO')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_putAcordoTermo; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],
        $_REQUEST['w_atividades'],$_REQUEST['w_produtos'],$_REQUEST['w_requisitos'],
        $_REQUEST['w_codigo_externo'],$_REQUEST['w_vincula_projeto'],
        $_REQUEST['w_vincula_demanda'],$_REQUEST['w_vincula_viagem'],$_REQUEST['w_prestacao_contas']);

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit();
    } 
  } elseif (strpos($SG,'PARC')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $SQL = new dml_putAcordoParc; 
      if($O=='V') {
        for ($i=1; $i<=count($_POST['w_sq_acordo_parcela'])-1; $i++) {
          if (Nvl($_REQUEST['w_sq_acordo_parcela'][$i],'')!='') {
            $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_sq_acordo_parcela'][$i],
              $_REQUEST['w_sq_acordo_aditivo'], null,null,null,null,null,null,null,
              null,null,null,$_REQUEST['w_inicio'][$i],$_REQUEST['w_fim'][$i],null,null,null,null);
           }
        }
      } else {
        $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],  $_REQUEST['w_sq_acordo_aditivo'], 
                $_REQUEST['w_ordem'],$_REQUEST['w_data'],$_REQUEST['w_valor'], $_REQUEST['w_observacao'], $_REQUEST['w_tipo_geracao'],
                $_REQUEST['w_tipo_mes'],$_REQUEST['w_vencimento'],$_REQUEST['w_dia_vencimento'], $_REQUEST['w_valor_parcela'],
                $_REQUEST['w_valor_diferente'],$_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],$_REQUEST['w_valor_inicial'],
                $_REQUEST['w_valor_excedente'],$_REQUEST['w_valor_reajuste'],$_REQUEST['w_qtd_31']);
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href="'.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_sq_acordo_aditivo='.$_REQUEST['w_sq_acordo_aditivo'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'";');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'OUTRA')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I'){
        $sql = new db_getConvOutraParte; $RS = $sql->getInstanceOf($dbms,null,$_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa'],null);
        foreach($RS as $row){$RS=$row; break;}
        if(count($RS)>0) {
          if (f($RS,'outra_parte')==$_REQUEST['w_sq_pessoa']) {  
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: Outra parte já cadastrada no contrato!");');
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
            ScriptClose();
            exit();
          }
        }
      } elseif ($O=='E') {
        $sql = new db_getConvPreposto; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_sq_acordo_outra_parte'],null);
        if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Existe preposto cadastrado em outra parte!");');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
          exit();
        }
        $sql = new db_getConvOutroRep; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],null ,$_REQUEST['w_sq_acordo_outra_parte']);
        if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Existe representante cadastrado em outra parte!");');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
          exit();
        }
      }
      $SQL = new dml_putConvOutraParte; $SQL->getInstanceOf($dbms,$_REQUEST['O'],$SG,$_REQUEST['w_sq_acordo_outra_parte'],
        $_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa'],
        $_REQUEST['w_tipo'],$_REQUEST['w_chave_aux'],
        $_REQUEST['w_cpf'],$_REQUEST['w_cnpj'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],
        $_REQUEST['w_sexo'],$_REQUEST['w_nascimento'],$_REQUEST['w_rg_numero'],$_REQUEST['w_rg_emissao'],
        $_REQUEST['w_rg_emissor'],$_REQUEST['w_passaporte'],$_REQUEST['w_sq_pais_passaporte'],
        $_REQUEST['w_inscricao_estadual'],$_REQUEST['w_logradouro'],
        $_REQUEST['w_complemento'],$_REQUEST['w_bairro'],$_REQUEST['w_sq_cidade'],
        $_REQUEST['w_cep'],$_REQUEST['w_ddd'],$_REQUEST['w_nr_telefone'],
        $_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],$_REQUEST['w_email'],
        $_REQUEST['w_sq_agencia'],$_REQUEST['w_operacao'],$_REQUEST['w_nr_conta'],
        $_REQUEST['w_sq_pais_estrang'],$_REQUEST['w_aba_code'],$_REQUEST['w_swift_code'],
        $_REQUEST['w_endereco_estrang'],$_REQUEST['w_banco_estrang'],$_REQUEST['w_agencia_estrang'],
        $_REQUEST['w_cidade_estrang'],$_REQUEST['w_informacoes'],$_REQUEST['w_codigo_deposito'],
        $_REQUEST['w_pessoa_atual']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos(substr($SG,3),'PREP')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I'){
        $sql = new db_getConvPreposto; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_sq_acordo_outra_parte'],$_REQUEST['w_sq_pessoa']);
        foreach ($RS as $row) {$RS=$row; break;}
        if (f($RS,'sq_pessoa')==$_REQUEST['w_sq_pessoa'] &&  Nvl($_REQUEST['w_sq_pessoa'],'nulo')!='nulo') {   
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Preposto já cadastrado em Outra parte!");');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_sq_acordo_outra_parte='.$_REQUEST['w_sq_acordo_outra_parte'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
          exit();
        }
      }
      $SQL = new dml_putConvPreposto; $SQL->getInstanceOf($dbms,$_REQUEST['O'],$SG,$_REQUEST['w_chave'],$_REQUEST['w_sq_acordo_outra_parte'],$_REQUEST['w_sq_pessoa'],
        $_REQUEST['w_chave_aux'],$_REQUEST['w_cpf'],$_REQUEST['w_nome'],
        $_REQUEST['w_nome_resumido'],$_REQUEST['w_sexo'],
        $_REQUEST['w_rg_numero'],$_REQUEST['w_rg_emissao'],$_REQUEST['w_rg_emissor'],
        $_REQUEST['w_ddd'],$_REQUEST['w_nr_telefone'],$_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],
        $_REQUEST['w_email'], $_REQUEST['w_cargo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_tipo='.$_REQUEST['w_tipo'].'&w_sq_acordo_outra_parte='.$_REQUEST['w_sq_acordo_outra_parte'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos(substr($SG,3),'REPRES')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {     
      if ($O=='I'){
        $sql = new db_getConvOutroRep; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa'],$_REQUEST['w_sq_acordo_outra_parte']);
        foreach ($RS as $row) {$RS=$row; break;}       
        if (f($RS,'sq_pessoa')==$_REQUEST['w_sq_pessoa'] &&  Nvl($_REQUEST['w_sq_pessoa'],'nulo')!='nulo') {   
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Representante já cadastrado em Outra parte!");');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_sq_acordo_outra_parte='.$_REQUEST['w_sq_acordo_outra_parte'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
          exit();
        }
      }
      $SQL = new dml_putConvOutroRep; $SQL->getInstanceOf($dbms,$_REQUEST['O'],$SG,$_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa'],$_REQUEST['w_sq_acordo_outra_parte'],
        $_REQUEST['w_chave_aux'],$_REQUEST['w_cpf'],$_REQUEST['w_nome'],
        $_REQUEST['w_nome_resumido'],$_REQUEST['w_sexo'],
        $_REQUEST['w_rg_numero'],$_REQUEST['w_rg_emissao'],$_REQUEST['w_rg_emissor'],
        $_REQUEST['w_ddd'],$_REQUEST['w_nr_telefone'],
        $_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],$_REQUEST['w_email'],$_REQUEST['w_cargo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_tipo='.$_REQUEST['w_tipo'].'&w_sq_acordo_outra_parte='.$_REQUEST['w_sq_acordo_outra_parte'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }   
  } elseif (strpos($SG,'ANEXO')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (UPLOAD_ERR_OK===0) {
        $w_maximo = $_REQUEST['w_upload_maximo'];
        foreach ($_FILES as $Chv => $Field) {
          if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!");');
            ScriptClose();
            retornaFormulario('w_observacao');
            exit();
          }
          $w_tamanho = $Field['size'];          
          if ($Field['size'] > 0) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            if ($Field['size'] > $w_maximo) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!");');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            } 
            // Se já há um nome para o arquivo, mantém 
            if ($_REQUEST['w_atual']>'') {
              $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
              foreach ($RS as $row) {
                if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                if (strpos(f($row,'caminho'),'.')!==false) {
                  $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,30);
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
            if ($w_file>'') {
              move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            }
          } elseif(nvl($Field['name'],'')!=''){
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!");');
            ScriptClose();
            retornaFormulario('w_caminho');
            exit();
          } 
        } 
        // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
        if ($O=='E' && $_REQUEST['w_atual']>'') {
          $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
          foreach ($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          }
        } 
        $SQL = new dml_putSolicArquivo; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!");');
        ScriptClose();
        exit();
      } 
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'ENVIO')!==false) {
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
              ShowHTML('  alert("Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!");');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            $w_tamanho = $Field['size'];          
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert("Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!");');
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
            } elseif (nvl($Field['name'],'')!='') {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!");');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            } 
          } 
          $SQL = new dml_putAcordoEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_tipo_log'],$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
          //Rotina para gravação da imagem da versão da solicitacão no log.
          if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS,'sigla');
            if($w_sg_tramite=='CI') {
              $w_html = ShowHTML(VisualAcordo($_REQUEST['w_chave'],'L',$w_usuario,'4','1'));
              CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
            }
          }
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!");');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], substr($SG, 0, 3) . 'GERAL');
        if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite'] && f($RS, 'sq_siw_tramite') != 'CI') {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já fez o encaminhamento para outra fase!");');
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
          ScriptClose();
        } else {

          $SQL = new dml_putAcordoEnvio;
          $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'],
                  $_REQUEST['w_novo_tramite'], 'N', $_REQUEST['w_tipo_log'], $_REQUEST['w_observacao'], $_REQUEST['w_destinatario'], $_REQUEST['w_despacho'],
                  null, null, null, null);
          //Rotina para gravação da imagem da versão da solicitacão no log.
          if ($_REQUEST['w_tramite'] != $_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData;
            $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS, 'sigla');
            if ($w_sg_tramite == 'CI') {
              $w_html = VisualAcordo($_REQUEST['w_chave'], 'L', $w_usuario, '4', '1');
              CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
            }
          }
          // Envia e-mail comunicando a inclusão
          SolicMail($_REQUEST['w_chave'], 2);
          // Se for envio da fase de cadastramento, remonta o menu principal
          if ($P1 == 1) {
            // Recupera os dados para montagem correta do menu
            $sql = new db_getMenuData;
            $RS = $sql->getInstanceOf($dbms, $w_menu);
            ScriptOpen('JavaScript');
            ShowHTML('  parent.menu.location=\'' . montaURL_JS(null, $conRootSIW . 'menu.php?par=ExibeDocs&O=L&R=' . $R . '&SG=' . f($RS, 'sigla') . '&TP=' . RemoveTP(RemoveTP($TP)) . MontaFiltro('GET')) . '\';');
            ScriptClose();
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
            ScriptClose();
          }
        }
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'CONC')!==false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],substr($SG,0,3).'GERAL');
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: Outro usuário já fez o encaminhamento para outra fase!");');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        $SQL = new dml_putAcordoConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
          $_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],
          $_REQUEST['w_custo_real'],$_REQUEST['w_tipo_conc']);
        // Se for renovação, grava os registros a partir do contrato atual
        if ($_REQUEST['w_tipo_conc']==0) {
          // Grava dados gerais
          $SQL = new dml_putAcordoGeral; $SQL->getInstanceOf($dbms,'I',$w_cliente,
            null,f($RS,'sq_menu'),f($RS,'sq_unidade'),f($RS,'solicitante'),
            $_SESSION['SQ_PESSOA'],f($RS,'sq_cc'),f($RS,'descricao'),f($RS,'justificativa'),
            $_REQUEST['w_inicio'],$_REQUEST['w_fim'],nvl($_REQUEST['w_valor'],0),f($RS,'data_hora'),
            f($RS,'aviso_prox_conc'),f($RS,'dias_aviso'),f($RS,'sq_cidade_origem'),f($RS,'sq_solic_pai'),
            f($RS,'sq_tipo_acordo'),f($RS,'objeto'),f($RS,'sq_tipo_pessoa'),
            f($RS,'sq_forma_pagamento'), null, null, f($RS,'sq_projeto_etapa'),
            f($RS,'codigo_interno'), f($RS,'titulo'), f($RS,'empenho'), f($RS,'processo'), FormataDataEdicao(f($RS,'assinatura')),
            FormataDataEdicao(f($RS,'publicacao')),
            &$w_chave_nova, $_REQUEST['w_chave'], null, &$w_codigo);
        } 
        // Envia e-mail comunicando a conclusão
        SolicMail($_REQUEST['w_chave'],3);
        ScriptOpen('JavaScript');
        // Volta para a listagem
        if ($_REQUEST['w_tipo_conc']==0) {
          ShowHTML('  alert("ATENÇÃO: a renovação foi gerada com o código '.$w_codigo.' e está disponível na tela de cadastramento!");');
        } 
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
    ShowHTML('  alert("Bloco de dados não encontrado: '.$SG.'");');
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
  case 'INICIAL':           Inicial();          break;
  case 'GERAL':             Geral();            break;
  case 'TERMO':             Termo();            break;
  case 'DADOSADICIONAIS':   DadosAdicionais();  break;
  case 'OUTRAPARTE':        OutraParte();       break;
  case 'PREPOSTO':          Preposto();         break;
  case 'REPRESENTANTE':     Representante();    break;
  case 'ANEXO':             Anexo();            break;
  case 'PARCELAS':          Parcelas();         break;
  case 'AREAS':             Areas();            break;
  case 'VISUAL':            Visual();           break;
  case 'EXCLUIR':           Excluir();          break;
  case 'ENVIO':             Encaminhamento();   break;
  case 'TRAMITE':           Tramitacao;         break;
  case 'ANOTACAO':          Anotar();           break;
  case 'CONCLUIR':          Concluir();         break;
  case 'ADITIVOS':          Aditivos();         break;
  case 'RELANEXO':          relAnexo();         break;
  case 'NOTAS':             Notas();            break;
  case 'NOTACANCEL':        NotaCancel();       break;
  case 'BUSCACOMPRA':       BuscaCompra();      break;
  case 'GRAVA':             Grava();            break;
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