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
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteResp.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteSolic.php');
include_once($w_dir_volta.'classes/sp/db_getCodigo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getCLFinanceiro.php');
include_once($w_dir_volta.'classes/sp/db_getSolicObjetivo.php');
include_once($w_dir_volta.'classes/sp/db_getMatServ.php');
include_once($w_dir_volta.'classes/sp/db_getCLSolicItem.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putCLGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicConc.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putCLSolicItem.php');
include_once($w_dir_volta.'classes/sp/dml_putCLARPItem.php');
include_once($w_dir_volta.'classes/sp/dml_putPessoa.php');
include_once($w_dir_volta.'classes/sp/dml_putCLItemFornecedor.php');
include_once($w_dir_volta.'classes/sp/dml_putCLDados.php');
include_once($w_dir_volta.'funcoes/retornaCadastrador_CL.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoFontePesquisa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoPlanoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoObjetivoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoLCModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoReajuste.php');
include_once($w_dir_volta.'funcoes/selecaoIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoLCModEnq.php');
include_once($w_dir_volta.'funcoes/selecaoLCFonteRecurso.php');
include_once($w_dir_volta.'funcoes/selecaoCTEspecificacao.php');
include_once($w_dir_volta.'funcoes/selecaoLCJulgamento.php');
include_once($w_dir_volta.'funcoes/selecaoLCSituacao.php');
include_once($w_dir_volta.'funcoes/selecaoMoeda.php');
include_once('visualcertame.php');
include_once('validacertame.php');

// =========================================================================
//  /certame.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o seviço de pedido de compra
// Mail     : celso@sbpi.com.br
// Criacao  : 27/08/2007, 15:00
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

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'certame.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_cl/';
$w_troca        = $_REQUEST['w_troca'];
$w_volta        = $_REQUEST['w_volta'];
$w_embed        = '';

$w_tipo         = $_REQUEST['w_tipo'];
$w_copia        = $_REQUEST['w_copia'];
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_atividade    = upper($_REQUEST['p_atividade']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_sq_prop      = upper($_REQUEST['p_sq_prop']);
$p_ordena       = lower($_REQUEST['p_ordena']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);

if (strlen($p_ini_i)==7) {
  if (nvl($p_ini_f,'')=='') $p_ini_f = date('d/m/Y', mktime(0, 0, 0, (substr($p_ini_i,5) + 1), 0, substr($p_ini_i,0,4)));;  
  $p_ini_i = '01/'.substr($p_ini_i,5).'/'.substr($p_ini_i,0,4);
}

$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
if (strlen($p_fim_i)==7) {
  if (nvl($p_fim_f,'')=='') $p_fim_f = date('d/m/Y', mktime(0, 0, 0, (substr($p_fim_i,5) + 1), 0, substr($p_fim_i,0,4)));;  
  $p_fim_i = '01/'.substr($p_fim_i,5).'/'.substr($p_fim_i,0,4);
}

$p_atraso       = upper($_REQUEST['p_atraso']);
$p_codigo       = upper($_REQUEST['p_codigo']);
$p_acao_ppa     = upper($_REQUEST['p_acao_ppa']);
$p_empenho      = upper($_REQUEST['p_empenho']);
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
$p_sqcc         = upper($_REQUEST['p_sqcc']);
$p_moeda        = $_REQUEST['p_moeda'];
$p_vencedor     = $_REQUEST['p_vencedor'];
$p_externo      = $_REQUEST['p_externo'];
$p_cnpj         = $_REQUEST['p_cnpj'];
$p_fornecedor   = $_REQUEST['p_fornecedor'];

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
$w_cadgeral = RetornaCadastrador_CL($w_menu, $w_usuario);

if (strpos($SG,'ZITEM')!==false) {
  if ((strpos('IP',$O)===false) && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 

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

$sql = new db_getParametro; $RS_Parametro = $sql->getInstanceOf($dbms,$w_cliente,'CL',null);
foreach($RS_Parametro as $row){$RS_Parametro=$row;}

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

// Verifica se o cliente tem o módulo de planejamento estratégico
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PE');
if (count($RS)>0) $w_pe='S'; else $w_pe='N'; 
$w_pe = 'N'; // Trava para evitar exibição dos dados do módulo de planejamento estratégico. 

// Verifica se o cliente tem o módulo de protocolo e arquivo
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_pa='S'; else $w_pa='N'; 

// Verifica se é possível vincular o certame
$w_vincula = false;
$sql = new db_getMenuRelac; $RS = $sql->getInstanceOf($dbms, $w_menu, 'S', 'S', 'S', 'SERVICO');
if (count($RS)) $w_vincula = true;
elseif (f($RS_Menu,'solicita_cc')=='S') $w_vincula = true; 
else {
  // Verifica se deve ser indicada opção para vinculação a plano estratégico
  $sql = new db_getPlanoEstrategico; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,'REGISTROS');
  foreach ($RS1 as $row1) {
    $sql = new db_getPlanoEstrategico; $RS2 = $sql->getInstanceOf($dbms,$w_cliente,f($row1,'chave'),null,null,null,null,null,'MENU');
    foreach($RS2 as $row2){
      if(f($row2,'sq_menu')==$chaveAux && nvl(f($row2,'sq_plano'),'')!=''){
        $w_vincula = true;
      }
    }
  }
}

//Verifica se há trâmite de pesquisa de preços
$w_pede_valor_pedido = 'S';
$w_tramite_analise   = 'N';
$sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_menu,null,null,null);
foreach($RS as $row) { 
  if (f($row,'sigla')=='PP') {
    $w_pede_valor_pedido = 'N';
  } elseif (f($row,'sigla')=='EA' && f($row,'ativo')=='S') {
    $w_tramite_analise   = 'S';
  }
}

// Se foi informada moeda, recupera seu símbolo e nome.
if ($p_moeda>'') {
  $sql = new db_getMoeda; $RS = $sql->getInstanceOf($dbms, $p_moeda, null, null, null, null);
  foreach($RS as $row) {
    $w_sb_moeda = f($row,'simbolo'); 
    $w_nm_moeda = f($row,'nome'); 
  }
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

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de listagem dos pedidos
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  global $w_embed;
  if (is_array($_REQUEST['w_chave'])) {
    $itens = $_REQUEST['w_chave'];
  } else {
    $itens = explode(',', $_REQUEST['w_chave']);
  }
  $w_envio    = $_REQUEST['w_envio'];
  $w_despacho = $_REQUEST['w_despacho'];
  $w_tipo     = $_REQUEST['w_tipo'];

  if ($O=='L') {
    if ((strpos(upper($R),'GR_')!==false) || ($w_tipo=='WORD')) {
      $w_filtro='';

      if (nvl($p_solic_pai,'')!='') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,$p_uorg_resp, $p_palavra, $p_prazo, $p_fase, 
            $p_sqcc, $p_projeto, $p_atividade, $p_acao_ppa, null, $p_empenho, $p_servico, $p_moeda, $p_vencedor, $p_externo, $p_cnpj, $p_fornecedor);
          if($w_tipo=='WORD') $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.exibeSolic($w_dir,$p_projeto,f($RS,'dados_solic'),'S','S').'</b>]';
          else                $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.exibeSolic($w_dir,$p_projeto,f($RS,'dados_solic'),'S').'</b>]';
      } elseif ($p_sqcc>'') {
        $w_linha++;
        $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
        $w_filtro .= '<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
      } elseif ($p_projeto>'') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
        $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
      } elseif (nvl($p_servico,'')!='') {
        if ($p_servico=='CLASSIF') {
          $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>Apenas pedidos com classificação</b>]';
        } else {
          $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$p_servico);
          $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.f($RS,'nome').'</b>]';
        }
      } 
      if (nvl($_REQUEST['p_agrega'],'')=='GRPRVINC') {
        $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>Apenas pedidos com vinculação</b>]';
      } 
      if ($p_pais>'') {
        $w_linha++;
        $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
        foreach($RS as $row) { $RS = $row; break; }
        $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
      } 
      if (nvl($p_chave,'')!='') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
                  $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                  $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, 
                  $p_sqcc, $p_projeto, $p_atividade, $p_acao_ppa, null, $p_empenho, $p_servico, $p_moeda, $p_vencedor, $p_externo, $p_cnpj, $p_fornecedor);
        $w_filtro.='<tr valign="top"><td align="right">Pedido <td>[<b>'.f($RS,'codigo_interno').'</b>]';
      } 
      //if ($p_prazo>'') $w_filtro.=' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_empenho>'')  $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]';
      if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
      if ($p_regiao>'' || $p_cidade>'') {
        $w_linha++;
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_regiao>'') ? str_pad($p_regiao,6,'0',PAD_RIGHT) : '*').'/'.(($p_cidade>'') ? $p_cidade : '*').'</b>]';
      } 
      if ($p_usu_resp>'') {
        $w_linha++;
        $sql = new db_getLCModalidade; $RS = $sql->getInstanceOf($dbms, $p_usu_resp, $w_cliente, null, null, null, null);
        foreach($RS as $row) { $RS = $row; break; }
        $w_filtro .= '<tr valign="top"><td align="right">Modalidade <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_moeda>'') {
        $w_linha++;
        $w_filtro .= '<tr valign="top"><td align="right">Moeda <td>[<b>'.$w_nm_moeda.'</b>]';
      } 
      if ($p_cnpj>'')       { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">CPF/CNPJ <td>[<b>'.$p_cnpj.'</b>]'; }
      if ($p_fornecedor>'') { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Fornecedor <td>[<b>'.$p_fornecedor.'</b>] (busca em qualquer parte do nome)'; }
      if ($p_vencedor>'')   { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Apenas certames com indicação de vencedor <td>[<b>Sim</b>]'; }
      if ($p_externo>'')    { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Código '.(($w_cliente==6881) ? 'SA' : 'externo').' <td>[<b>'.$p_externo.'</b>]'; }
      if ($p_assunto>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Código externo <td>[<b>'.$p_assunto.'</b>]'; }
      if ($p_solicitante>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Solicitante <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_uf>'') {
        $w_linha++;
        $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_uf, $w_cliente, null, null, null, null, null, null);
        foreach ($RS as $row) {
          $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação do certame <td>[<b>'.f($row,'nome').'</b>]';
          break;
        }
      } 
      if ($p_unidade>'') {
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Eventos do certame <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro.='<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($w_filtro>'')     $w_filtro  ='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
 
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as PCDs visíveis pelo usuário
      $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, 
          $p_sqcc, $p_projeto, $p_atividade, $p_acao_ppa, null, $p_empenho, $p_servico, $p_moeda, $p_vencedor, $p_externo, $p_cnpj, $p_fornecedor);
    } else {
      $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante, $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, 
          $p_sqcc, $p_projeto, $p_atividade, $p_acao_ppa, null, $p_empenho, $p_servico, $p_moeda, $p_vencedor, $p_externo, $p_cnpj, $p_fornecedor);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'ord_codigo_interno','asc','inclusao','desc', 'fim', 'desc', 'prioridade', 'asc');
    } else {
      $RS = SortArray($RS,'ord_codigo_interno','asc','inclusao','desc', 'fim', 'desc', 'prioridade', 'asc');
    }
  }
  
  $w_linha_pag    = 0;
  headerGeral('P', $w_tipo, $w_chave, 'Consulta de '.f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);
  
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ScriptOpen('Javascript');
    Modulo();
    FormataCPF();
    if ($O=='L' && count($RS) && $P1==2) {
      ShowHTML('  $(document).ready(function() {');
      ShowHTML('    $("#marca_todos").click(function() {');
      ShowHTML('      var checked = this.checked;');
      ShowHTML('      $(".item").each(function() {');
      ShowHTML('        this.checked = checked;');
      ShowHTML('      });');
      ShowHTML('    });');
      ShowHTML('  });');
    }
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($O=='L') {
      if (count($RS) && $P1==2) {
        ShowHTML('  var i; ');
        ShowHTML('  var w_erro=true; ');
        ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
        ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
        ShowHTML('       w_erro=false; ');
        ShowHTML('       break; ');
        ShowHTML('    }');
        ShowHTML('  }');
        ShowHTML('  if (w_erro) {');
        ShowHTML('    alert("Você deve selecionar pelo menos um registro!"); ');
        ShowHTML('    return false;');
        ShowHTML('  }');
        Validate('w_despacho','Despacho','','','1','2000','1','1');
        ShowHTML('  if (theForm.w_envio[0].checked && theForm.w_despacho.value != \'\') {');
        ShowHTML('     alert("Informe o despacho apenas se for devolução para a fase anterior!");');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_envio[1].checked && theForm.w_despacho.value==\'\') {');
        ShowHTML('     alert("Informe um despacho descrevendo o motivo da devolução!");');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
      }
    } elseif (strpos('CP',$O)!==false) {
      if ($P1!=1 || $O=='C') {
        // Se não for cadastramento ou se for cópia        
        Validate('p_empenho','Código da licitação','','','2','60','1','1');
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
    ShowHTML('<base HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    if ($w_troca > '') {
      // Se for recarga da página
      BodyOpenClean('onLoad="document.Form.' . $w_troca . '.focus();\'');
    } elseif (strpos('CP', $O) !== false) {
      BodyOpenClean('onLoad="document.Form.p_empenho.focus();"');
    } elseif ($P1==2) {
      BodyOpenClean(null);
    } else {
      BodyOpenClean('onLoad="this.focus();"');
    }
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    if ((strpos(upper($R),'GR_'))===false) {
      Estrutura_Texto_Abre();
    } else {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
    }
    if ($w_filtro > '') ShowHTML($w_filtro);
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    if ($w_embed == 'WORD') {
      ShowHTML('<tr><td colspan="2">');
    } else {
      ShowHTML('<tr><td>');
      if ($P1==1 && $w_copia=='') {
        // Se for cadastramento e não for resultado de busca para cópia
        if ($w_embed!='WORD') { 
          ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;'); 
          ShowHTML('    <a accesskey="C" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
        }
      } 
      if ((strpos(upper($R),'GR_'))===false && $w_embed!='WORD') {
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
    }
    ShowHTML('    <td align="right"><b>'.exportaOffice().'Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan = 0;
    if ($w_embed!='WORD') {
      if (count($RS) && $P1==2) {
        $colspan++; ShowHTML('          <td align="center" width="15"><span class="remover"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></span></td>');
      }
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Código','ord_codigo_interno').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Objeto','objeto').'</td>');
      if ($_SESSION['INTERNO']=='S') { $colspan++; ShowHTML ('          <td><b>'.LinkOrdena('Vinculação','dados_pai').'</td>'); }
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Modalidade','sg_lcmodalidade').'</td>');
      if ($w_pa=='S' || $w_segmento=='Público') { $colspan++; ShowHTML ('          <td><b>'.LinkOrdena('Processo','processo').'</td>'); }
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Solicitante','sg_unidade_resp').'</td>');
      //$colspan++; ShowHTML('          <td><b>'.LinkOrdena('Data limite','fim').'</td>');
      if ($P1!=1 || $w_pede_valor_pedido=='S') {
        if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>'.LinkOrdena('Valor'.(($w_sb_moeda>'') ? ' ('.$w_sb_moeda.')' : ''),'valor').'</td>');
        if ($P1!=1) {
          ShowHTML('          <td><b>'.LinkOrdena('Situação','nm_lcsituacao').'</td>');
          if ($w_embed!='WORD') ShowHTML('          <td class="remover" width="1">&nbsp;</td>');
          ShowHTML('          <td><b>'.LinkOrdena('Executor','nm_exec').'</td>');
        }
        if ($P1>2) {
          if ($w_cliente==6881) ShowHTML('          <td><b>'.LinkOrdena('Código externo','codigo_externo').'</td>');
          else                  ShowHTML('          <td><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
        }
      }      
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td class="remover"><b>Operações</td>');
      ShowHTML('        </tr>');
    } else {
      $colspan++; ShowHTML('          <td><b>Código</td>');
      $colspan++; ShowHTML('          <td><b>Objeto</td>');
      if ($_SESSION['INTERNO']=='S') { $colspan++; ShowHTML ('          <td><b>Vinculação</td>'); }
      $colspan++; ShowHTML('          <td><b>Modalidade</td>');
      if ($w_pa=='S' || $w_segmento=='Público') { $colspan++; ShowHTML ('          <td><b>Processo</td>'); }
      $colspan++; ShowHTML('          <td><b>Solicitante</td>');
      //$colspan++; ShowHTML('          <td><b>Data limite</td>');
      if ($P1!=1 || $w_pede_valor_pedido=='S') {
        if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>Valor'.(($w_sb_moeda>'') ? ' ('.$w_sb_moeda.')' : '').'</td>');
        if ($P1!=1) {
          ShowHTML('          <td><b>Situação</td>');
          if ($w_embed!='WORD') ShowHTML('          <td class="remover" width="1">&nbsp;</td>');
          ShowHTML('          <td><b>Executor</td>');
        }
        if ($P1>2) {
          if ($w_cliente==6881) ShowHTML('          <td><b>Código externo</td>');
          else                  ShowHTML('          <td><b>Fase atual</td>');
        }
      }
      ShowHTML('        </tr>');
    }
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="'.($colspan+4).'" align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial = array();
      if($w_embed!='WORD') {
        $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
        if ($P1==2) {
          ShowHTML('<span class="remover">');
          AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'LOTE', $w_pagina . $par, $O);
          ShowHTML('<INPUT type="hidden" name="p_agrega" value="'.$SG.'">');
          ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
          ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
          ShowHTML('<input type="hidden" name="w_chave[]" value=""></td>');
          ShowHTML('<input type="hidden" name="w_lista[]" value=""></td>');
          if (nvl($_REQUEST['p_ordena'], '') == '') ShowHTML('<INPUT type="hidden" name="p_ordena" value="">');
          ShowHTML(MontaFiltro('POST'));
          ShowHTML('</span>');
        }
      } else {
        $RS1 = $RS;
      }
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if ($P1==2) {
          ShowHTML('        <td align="center"><span class="remover">');
          ShowHTML('          <INPUT type="hidden" name="w_tramite[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'sq_siw_tramite') . '">');
          ShowHTML('          <INPUT type="hidden" name="w_lista[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'codigo_interno') . '">');
          if (in_array(f($row, 'sq_siw_solicitacao'), $itens)) {
            ShowHTML('          <input class="item" type="CHECKBOX" CHECKED  name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
          } else {
            ShowHTML('          <input class="item" type="CHECKBOX"  name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
          }
          ShowHTML('        </span></td>');
        }
        ShowHTML('        <td width="1%" nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),null,null,f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        if ($w_embed!='WORD'){
          ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
        } else {
          ShowHTML('&nbsp;'.f($row,'codigo_interno').'&nbsp;');
        }
        ShowHTML('        <td>'.f($row,'objeto').'</td>');
        if ($_SESSION['INTERNO']=='S') {
          if ($w_cliente==6881)                    ShowHTML('        <td>'.f($row,'sg_cc').'</td>');
          elseif (Nvl(f($row,'dados_pai'),'')!='') ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai')).'</td>');
          else                                     ShowHTML('        <td>---</td>');
        } 
        ShowHTML('        <td title="'.f($row,'nm_lcmodalidade').'" align="center">'.f($row,'sg_lcmodalidade').'</td>');
        if ($w_pa=='S') {
          if ($w_embed!='WORD' && nvl(f($row,'protocolo_siw'),'')!='') {
            ShowHTML('        <td align="center" nowrap><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($row,'processo').'&nbsp;</a>'.'</td>');
          } else {
            ShowHTML('        <td align="center" nowrap>'.nvl(f($row,'processo'),'&nbsp;').'</td>');
          }
        } elseif ($w_segmento=='Público') {
          ShowHTML('        <td align="center">'.f($row,'processo').'</td>');
        }
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade'),$TP).'&nbsp;</td>');
        if ($P1!=1 || $w_pede_valor_pedido=='S') {
          $w_parcial[f($row,'sb_moeda')] = nvl($w_parcial[f($row,'sb_moeda')],0) + f($row,'valor');
          if ($_SESSION['INTERNO']=='S') ShowHTML('        <td align="right" width="1%" nowrap>'.((nvl($w_sb_moeda,'')=='' && nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').formatNumber(f($row,'valor')).'</td>');
          if ($P1!=1) {
            ShowHTML('        <td>'.Nvl(f($row,'nm_lcsituacao'),'---').'</td>');
            if ($w_embed!='WORD') ShowHTML('        <td class="remover" width="1">'.ExibeAnotacao('../',$w_cliente,null,f($row,'sq_siw_solicitacao'),f($row,'codigo_interno')).'</td>');
            ShowHTML('        <td>'.Nvl(f($row,'nm_exec'),'---').'</td>');
          }
          if ($P1>2) {
            if ($w_cliente==6881) ShowHTML('        <td nowrap>'.f($row,'codigo_externo').'</td>');
            else                  ShowHTML('        <td>'.f($row,'nm_tramite').'</td>');
          }
        } 
        if ($P1!=3 && $P1!=5 && $P1!=6 && $w_embed != 'WORD') {
          ShowHTML('        <td class="remover" width="1%" nowrap>');
          // Se não for acompanhamento
          if ($w_copia>'') {
            // Se for listagem para cópia
            $sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
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
            ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Anexos&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Anexos'.'&SG='.substr($SG,0,4).'ANEXO').'\',\'Anexos\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Vincula arquivos ao pedido de compra.">Anexos</A>&nbsp');
            if (f($row,'minimo_participantes')==0) {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Concluir licitação.">CO</A>&nbsp');
            } else {
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Itens'.'&SG='.substr($SG,0,4).'ITEM').'\',\'Itens\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Escolhe os itens a partir de solicitações de compra.">SC</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.'pedido.php?par=Itens&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Itens'.'&SG=CLPCITEM').'\',\'Itens\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Escolhe os itens de forma avulsa, a partir do catálogo.">AV</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento do pedido">EN</A>&nbsp');
            }
          } elseif ($P1==2) {
            if (f($row,'sg_tramite')=='EE') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a solicitação, sem enviá-la.">AN</A>&nbsp');
              if ($w_tramite_analise=='S') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Informar&R='.$w_pagina.$par.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Situação&SG=CLLCSITUACAO'.MontaFiltro('GET').'" title="Alterar a situação da solicitação.">IN</A>&nbsp');
              } else {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'dadosanalise&R='.$w_pagina.$par.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Dados da análise&SG=CLLCDADOS'.MontaFiltro('GET').'" title="Informar os dados da solicitação.">IN</A>&nbsp');
              }
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'PesquisaPreco&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_pesquisa=N&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Propostas'.'&SG='.substr($SG,0,4).'PRECO').'\',\'Proposta\',\'resizable=yes,status=no,toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Insere as propostas da licitação.">Propostas</A>&nbsp');
            } elseif (f($row,'sg_tramite')=='AP') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'DadosPrevios&R='.$w_pagina.$par.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Informar&SG=CLLCPROT'.MontaFiltro('GET').'" title="Informar.">Informar</A>&nbsp');
            } elseif (f($row,'sg_tramite')=='PP') {
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'PesquisaPreco&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_pesquisa=S&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Pesquisa de preço'.'&SG='.substr($SG,0,4).'PRECO').'\',\'PesquisaPreco\',\'resizable=yes,status=no,toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Insere a pesquisa de preco do itens da solicitação.">Pesquisa de preço</A>&nbsp');
            } elseif (f($row,'sg_tramite')=='EA') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'dadosanalise&R='.$w_pagina.$par.'&w_chave='.f($row,'sq_siw_solicitacao').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Dados da análise&SG=CLLCDADOS'.MontaFiltro('GET').'" title="Inserir os dados de análise.">Dados da análise</A>&nbsp');
            }
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicitação para outro responsável.">EN</A>&nbsp');
            if (f($row,'sg_tramite')=='EE') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Concluir licitação.">CO</A>&nbsp');
            } 
          } 
          ShowHTML('        </td>');
        } else {
          if ($w_embed!='WORD'){
            ShowHTML('        <td class="remover" width="1%" nowrap>');
            if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o pedido para outro responsável.">EN</A>&nbsp');
            } else {
              ShowHTML('          ---&nbsp');
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
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_parcial)==1) ? 'l' : 'is').' desta página&nbsp;</td>');
          ShowHTML('          <td align="right" nowrap><b>');
          $i = 0;
          ksort($w_parcial);
          foreach($w_parcial as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
          echo('</td>');
          ShowHTML('          <td colspan='.(($w_embed=='WORD') ? '3' : '4').'>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          $w_total = array();
          foreach($RS as $row) {
            $w_total[f($row,'sb_moeda')] = nvl($w_total[f($row,'sb_moeda')],0) + f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_total)==1) ? 'l' : 'is').' da listagem&nbsp;</td>');
          ShowHTML('          <td align="right" nowrap><b>');
          $i = 0;
          ksort($w_total);
          foreach($w_total as $k => $v) { echo((($i) ? '<div></div>' : '').((nvl($w_sb_moeda,'')=='') ? $k : '').' '.formatNumber($v,2)); $i++; }
          echo('</td>');
          ShowHTML('          <td colspan="'.(($w_embed=='WORD') ? '3' : '4').'">&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if (count($RS) && $w_embed!='WORD') {
      if ($P1==2) {
        ShowHTML('<span class="remover">');
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan=3>');
        ShowHTML('  <table width="97%" border="0">');
        ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%">');
        ShowHTML('      <tr><td><b>Tipo do Encaminhamento</b><br>');
        ShowHTML('        <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N"'.((Nvl($w_envio,'N')=='N') ? ' checked' : '').'> Enviar para a próxima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S"'.((Nvl($w_envio,'N')=='S') ? ' checked' : '').'> Devolver para a fase anterior');
        ShowHTML('      <tr>');
        ShowHTML('      <tr><td><b>D<u>e</u>spacho (informar apenas se for devolução):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a solicitação.">'.$w_despacho.'</TEXTAREA></td>');
        ShowHTML('    </table>');
        ShowHTML('    <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr><input class="STB" type="submit" name="Botao" value="Enviar"></td></tr>');
        ShowHTML('  </table>');
        ShowHTML('  </TD>');
        ShowHTML('</tr>');
        ShowHTML('</FORM>');
      }
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
    ShowHTML('      <tr><td colspan="2">');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      ShowHTML('   <tr valign="top">');
      ShowHTML('     <td><b><U>C</U>ódigo da licitação:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="p_empenho" size="20" maxlength="60" value="'.$p_empenho.'"></td>');
      SelecaoPessoa('<u>R</u>esponsável pela execução:','N','Selecione o executor na relação.',$p_prioridade,null,'p_prioridade','USUARIOS');
      ShowHTML('   <tr valign="top">');
      SelecaoPessoa('<u>S</u>olicitante:','N','Selecione o solicitante na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$p_unidade,null,'p_unidade','CLCP',null);
      ShowHTML('   <tr>');
      ShowHTML('     <td><b><u>D</u>ata de recebimento e limite para atendimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('<tr>');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('        <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
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
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  
  if ($w_tipo == 'PDF') RodapePdf();
  else                  Rodape();
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
  
  // Verifica se a lotação de usuários comuns tem permissão para cadastrar pedidos
  if ($w_cadgeral=='N') {
    $sql = new db_getUorgList; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_SESSION['LOTACAO'],'CLUNID',null,null,$w_ano);
    foreach($RS as $row) { $RS = $row; break; }
    if (count($RS)==0 ||(count($RS)>0 && f($RS,'solicita_compra')!='S')) {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: Sua lotação não tem permissão para realizar compras/licitações. Entre em contato com os gestores do sistema!");');
      ShowHTML('  history.back(1);');
      ScriptClose();
      exit;
    } 
  }

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_sq_menu_relac      = $_REQUEST['w_sq_menu_relac'];    
    if($w_sq_menu_relac=='CLASSIF') {
      $w_solic_pai        = '';
    } else {
      $w_solic_pai        = $_REQUEST['w_solic_pai'];
    }
    $w_codigo             = $_REQUEST['w_codigo'];
    $w_chave_pai          = $_REQUEST['w_chave_pai'];    
    $w_plano              = $_REQUEST['w_plano'];
    $w_sqcc               = $_REQUEST['w_sqcc'];
    $w_objetivo           = explodeArray($_REQUEST['w_objetivo']);
    $w_prioridade         = $_REQUEST['w_prioridade'];
    $w_aviso              = $_REQUEST['w_aviso'];
    $w_dias               = $_REQUEST['w_dias'];
    $w_chave_aux          = $_REQUEST['w_chave_aux'];
    $w_sq_menu            = $_REQUEST['w_sq_menu'];
    $w_sq_unidade         = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite         = $_REQUEST['w_sq_tramite'];
    $w_solicitante        = $_REQUEST['w_solicitante'];
    $w_cadastrador        = $_REQUEST['w_cadastrador'];
    $w_executor           = $_REQUEST['w_executor'];
    $w_inicio             = $_REQUEST['w_inicio'];
    $w_fim                = $_REQUEST['w_fim'];
    $w_valor              = $_REQUEST['w_valor'];
    $w_inclusao           = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao   = $_REQUEST['w_ultima_alteracao'];
    $w_justificativa      = $_REQUEST['w_justificativa'];
    $w_observacao         = $_REQUEST['w_observacao'];
    $w_cidade             = $_REQUEST['w_cidade'];
    $w_arp                = $_REQUEST['w_arp'];
    $w_sq_lcmodalidade    = $_REQUEST['w_sq_lcmodalidade'];
    $w_numero_processo    = $_REQUEST['w_numero_processo'];
    $w_protocolo          = $_REQUEST['w_protocolo'];
    $w_protocolo_nm       = $_REQUEST['w_protocolo_nm'];
    $w_financeiro         = $_REQUEST['w_financeiro'];
    $w_rubrica            = $_REQUEST['w_rubrica'];
    $w_lancamento         = $_REQUEST['w_lancamento'];
    $w_objeto             = $_REQUEST['w_objeto'];
    $w_moeda              = $_REQUEST['w_moeda'];
  } else {
    if (strpos('AEV',$O)!==false || $w_copia>'') {
      // Recupera os dados do pedido
      if ($w_copia>'') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,null,null,null,null,null,null,null,null,null,null,
            $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
      } else {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,null,null,null,null,null,null,null,null,null,null,
            $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
      }
      if (count($RS)>0) {
        foreach($RS as $row){$RS=$row; break;}
        $w_codigo               = f($RS,'codigo_interno');
        $w_plano                = f($RS,'sq_plano');
        $w_dados_pai            = explode('|@|',f($RS,'dados_pai'));
        $w_sq_menu_relac        = $w_dados_pai[3];
        $sql = new db_getSolicObjetivo; $RS1 = $sql->getInstanceOf($dbms,$w_chave,null,null);
        $RS1 = SortArray($RS1,'nome','asc');
        $w_objetivo = '';
        foreach($RS1 as $row) { $w_objetivo .= ','.f($row,'sq_peobjetivo'); }
        $w_objetivo = substr($w_objetivo,1);
        if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
        $w_prioridade       = f($RS,'prioridade');
        $w_aviso            = f($RS,'aviso_prox_conc');
        $w_dias             = f($RS,'dias_aviso');
        $w_chave_pai        = f($RS,'sq_solic_pai');
        $w_solic_pai        = f($RS,'sq_solic_pai');
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
        $w_origem           = f($RS,'origem');
        $w_fim              = FormataDataEdicao(f($RS,'fim'));
        $w_valor             = formatNumber(f($RS,'valor'));
        $w_inclusao         = f($RS,'inclusao');
        $w_arp              = f($RS,'arp');
        $w_ultima_alteracao = f($RS,'ultima_alteracao');
        if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
        $w_sq_lcmodalidade  = f($RS,'sq_lcmodalidade');
        $w_numero_processo  = f($RS,'protocolo_completo');
        $w_protocolo        = f($RS,'protocolo_completo');
        $w_protocolo_nm     = f($RS,'protocolo_completo');
        $w_financeiro       = f($RS,'sq_financeiro');
        $w_rubrica          = f($RS,'sq_projeto_rubrica');
        $w_lancamento       = f($RS,'sq_tipo_lancamento');
        $w_objeto           = f($RS,'objeto');
        $w_moeda            = f($RS,'sq_moeda');
      } 
    } 
  } 
  // Se não puder cadastrar para outros, carrega os dados do usuário logado
  if ($w_cadgeral=='N') {
    $w_sq_unidade  = $_SESSION['LOTACAO'];
    $w_solicitante = $_SESSION['SQ_PESSOA'];
  } 

  if ($w_solic_pai>'') {
    // Recupera as possibilidades de vinculação financeira
    $sql = new db_getCLFinanceiro; $RS_Financ = $sql->getInstanceOf($dbms,$w_cliente,$w_menu,$w_solic_pai,null,null,null,null,null,null,null,null);
  }
  
  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  FormataValor();
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
    
    // Trata as possíveis vinculações da solicitação
    if ($w_vincula) {
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
        ShowHTML('      alert("Você deve informar pelo menos um objetivo estratégico!"); ');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('  }');
  
        if(nvl($w_sq_menu_relac,'')!='') {
          Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
          if ($w_sq_menu_relac=='CLASSIF') {
            Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
          } else {
            Validate('w_solic_pai','Vinculação','SELECT',1,1,18,1,1);
          }
        }
        if(nvl($w_sq_menu_relac,'')!='' && nvl($w_plano,'')!='') {
          ShowHTML('    alert("Informe um plano estratégico ou uma vinculação. Você não pode escolher ambos!");');
          ShowHTML('    theForm.w_plano.focus();');
          ShowHTML('    return false;');
        } elseif(nvl($w_sq_menu_relac,'')=='' && nvl($w_plano,'')=='') {
          ShowHTML('    alert("Informe um plano estratégico ou uma vinculação!");');
          ShowHTML('    theForm.w_plano.focus();');
          ShowHTML('    return false;');    
        }
      } else {
        Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
        if(nvl($w_sq_menu_relac,'')!='') {
          if ($w_sq_menu_relac=='CLASSIF') {
            Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
          } else {
            Validate('w_solic_pai','Vinculação','SELECT',1,1,18,1,1);
          }
        }
      }
    }
    /*
    Validate('w_prioridade','Prioridade','SELECT',1,1,1,'','0123456789');
    Validate('w_fim','Limite para atendimento','DATA',1,10,10,'','0123456789/');
    CompData('w_fim','Limite para atendimento','>=','w_inicio','Data atual');
    */
    if($w_cadgeral=='S') {
      Validate('w_solicitante','Solicitante','HIDDEN',1,1,18,'','0123456789');
      Validate('w_sq_unidade','Setor solicitante','HIDDEN',1,1,18,'','0123456789');
    }
    Validate('w_sq_lcmodalidade','Modalidade','SELECT','1',1,18,'','0123456789');
    if ($w_pa=='S') {
      Validate('w_protocolo_nm','Número do processo','hidden','','20','20','','0123456789./-');
    } elseif($w_segmento=='Público') {
      Validate('w_numero_processo','Número do processo','1','1',1,30,'1','1');
    }
    Validate('w_objeto','Objeto','','1',3,2000,'1','1');
    Validate('w_justificativa','Justificativa','','',3,2000,'1','1');
    Validate('w_observacao','Observação','','',3,2000,'1','1');
    if (count($RS_Financ)>1) {
      Validate('w_rubrica','Rubrica','SELECT',1,1,18,'','0123456789');
      Validate('w_lancamento','Tipo de lançamento','SELECT',1,1,18,'','0123456789');
    }
    if ($w_pede_valor_pedido=='S') {
      if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') Validate('w_moeda','Moeda','SELECT',1,1,18,'','0123456789');
      Validate('w_valor','Valor estimado','VALOR',1,4,18,'','0123456789,.');
      CompValor('w_valor','Valor estimado','>',0,'zero');
    }
    if($w_decisao_judicial=='N') {
      Validate('w_dias','Dias de alerta do pedido','1','',1,3,'','0123456789');
      ShowHTML('  if (theForm.w_aviso[0].checked) {');
      ShowHTML('     if (theForm.w_dias.value == \'\') {');
      ShowHTML('        alert("Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!");');
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
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    $w_cliente_arp = f($RS,'ata_registro_preco');
    if ($w_cidade=='') {
      $w_cidade=f($RS,'sq_cidade_padrao');
    }   
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro=Validacao($w_sq_solicitacao,$sg);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_pai" value="'.$w_chave_pai.'">');
    if(nvl($w_decisao_judicial,'N')=='N' && $w_cliente!=6881) {
      ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.FormataDataEdicao(time()).'">');
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para identificação do pedido de compra, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('          <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    if(nvl(f($RS_Menu,'numeracao_automatica'),0)==0) {
      ShowHTML('      <tr><td><b><U>C</U>ódigo interno:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="w_codigo" size="18" maxlength="60" value="'.$w_codigo.'"></td>');
    }
    // Verifica as possibilidades de vinculação
    if ($w_vincula) {
      if ($w_pe=='S') {
        ShowHTML('          <tr valign="top">');
        selecaoPlanoEstrategico('<u>P</u>lano estratégico:', 'P', 'Selecione o plano ao qual o programa está vinculado.', $w_plano, $w_chave, 'w_plano', 'ULTIMO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_solicitante\'; document.Form.submit();"');
        ShowHTML('          <tr valign="top">');    
        selecaoObjetivoEstrategico('<u>O</u>bjetivo(s) estratégico(s):', 'P', 'Selecione o(s) objetivo(s) estratégico(s) ao(s) qual(is) o programa está vinculado.', $w_objetivo, $w_plano, 'w_objetivo[]', 'CHECKBOX', null);
      }
      ShowHTML('          <tr valign="top">');
      selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
      if(Nvl($w_sq_menu_relac,'')!='') {
        ShowHTML('          <tr valign="top">');
        if ($w_sq_menu_relac=='CLASSIF') {
          SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null,null,'<BR />',2);
        } else {
          SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_solic_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_solic_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_solicitante\'; document.Form.submit();"',$w_chave_pai,'<BR />',2);
        }
      }
    }
    ShowHTML('          <tr><td colspan=2><table border=0 colspan=0 cellspan=0 width="100%">');
    /*
    SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade deste projeto.',$w_prioridade,null,'w_prioridade',null,null);
    ShowHTML('            <td><b><u>L</u>imite para atendimento:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data prevista para conclusão da licitação.">'.ExibeCalendario('Form','w_fim').'</td>');
    */
    ShowHTML('<INPUT type="hidden" name="w_prioridade" value="'.$w_prioridade.'">');
    ShowHTML('<INPUT type="hidden" name="w_fim" value="'.$w_fim.'">');
    if ($w_cliente_arp=='S') {
      MontaRadioNS('<b>Gera ARP?</b>',$w_arp,'w_arp');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_arp" value="N">');
    }
    ShowHTML ('         </table>');
    ShowHTML('          <tr valign="top">');
    SelecaoPessoa('<u>S</u>olicitante:','S','Selecione o solicitante do pedido na relação.',nvl($w_solicitante,$w_usuario),null,'w_solicitante','USUARIOS');
    // Recupera todos os registros para a listagem
    $sql = new db_getUorgList; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_SESSION['LOTACAO'],'CLUNID',null,null,$w_ano);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      if ($w_cadgeral=='N') {
        $w_sq_unidade = f($RS,'sq_unidade');
        ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
      } else {
        SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',nvl($w_sq_unidade,$_SESSION['LOTACAO']),null,'w_sq_unidade','CLCP',null);
      } 
    } else {
      if ($w_cadgeral=='N') {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: Sua lotação não tem permissão para realizar compras/licitações. Entre em contato com os gestores do sistema!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } else {
        SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$w_sq_unidade,null,'w_sq_unidade','CLCP',null);
      } 
    }
    ShowHTML('          <tr>');
    SelecaoLCModalidade('<u>M</u>odalidade:','M','Selecione na lista a modalidade do certame.',$w_sq_lcmodalidade,null,'w_sq_lcmodalidade',null,null);
    if ($w_pa=='S') {
      SelecaoProtocolo('N<u>ú</u>mero do protocolo:','U','Selecione o protocolo da compra.',$w_protocolo,null,'w_protocolo','JUNTADA',null);
    } elseif($w_segmento=='Público') {
      ShowHTML('          <td><b>N<u>ú</u>mero do protocolo:</b><br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="sti" type="text" name="w_numero_processo" size="30" maxlength="30" value="'.$w_numero_processo.'" title="Número do processo de compra/contratação."></td>');
    }
    if ($w_pede_valor_pedido=='S') {
      ShowHTML('          <tr>');
      if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') {
        selecaoMoeda('<u>M</u>oeda:','U','Selecione a moeda na relação.',$w_moeda,null,'w_moeda','ATIVO',null);
      }
      ShowHTML('        <td colspan=2><b><u>V</u>alor estimado:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor estimado para a solicitação."></td>');
    }
    ShowHTML('      <tr><td colspan=2><b>O<u>b</u>jeto:</b><br><textarea '.$w_Disabled.' accesskey="B" name="w_objeto" class="STI" ROWS=5 cols=75 title="É obrigatório informar o objeto.">'.$w_objeto.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan=2><b><u>J</u>ustificativa:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="É obrigatório justificar.">'.$w_justificativa.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan=2><b><u>O</u>bservação:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>'.$w_observacao.'</TEXTAREA></td>');
    if ($w_solic_pai>'') {
      if (count($RS_Financ)>1) {
        ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Dados para Pagamento</td></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr valign="top">');
        SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_rubrica,$w_solic_pai,'T','w_rubrica','CLFINANC','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_rubrica\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lançamento:','T','Selecione na lista o tipo de lançamento adequado.',$w_lancamento,null,$w_cliente,'w_lancamento','CLLC'.str_pad($w_solic_pai,10,'0',STR_PAD_LEFT).str_pad($w_rubrica,10,'0',STR_PAD_LEFT).'T',null);
      } elseif (count($RS_Financ)==1) {
        foreach($RS_Financ as $row) { $RS_Financ = $row; break; }
        ShowHTML('<INPUT type="hidden" name="w_financeiro" value="'.f($RS_Financ,'chave').'">');
      }
    }
    /*
    ShowHTML('      <tr><td colspan=2 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" bgcolor="#D0D0D0"><b>Alerta de proximidade da data de término</td></td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=2>Os dados abaixo indicam como deve ser tratada a proximidade da data Término previsto do projeto.</td></tr>');
    ShowHTML('      <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    MontaRadioNS('<b>Emite alerta?</b>',$w_aviso,'w_aviso');
    ShowHTML('              <td><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="Número de dias para emissão do alerta de proximidade da data Término previsto do projeto."></td>');
    */
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="N">');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.'inicial&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
// Rotina de itens da licitacao
// -------------------------------------------------------------------------
function Itens() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_chave_aux          = $_REQUEST['w_chave_aux'];
  $w_solic_pai          = $_REQUEST['w_solic_pai'];

  // Recupera os dados da solicitacao
  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O <> 'E') {
    $w_sq_material        = $_REQUEST['w_sq_material'];
    $w_quantidade         = $_REQUEST['w_quantidade'];
  } elseif ($O=='I') {
    $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,null,null,null,null,null,null,null,null,null,null,null,'LCITEM');
    $RS = SortArray($RS,'ordem','asc','nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc'); 
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc');
    } else {
      $RS = SortArray($RS,'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc'); 
    }
  } elseif (strpos('L',$O)!==false) {
    $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
    $RS = SortArray($RS,'ordem','asc','nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc','dados_pai','asc'); 
  } elseif (strpos('AE',$O)!==false) {
    $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,$w_chave_aux,null,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave_aux           = f($RS,'chave');
    $w_material            = f($RS,'sq_material');
    $w_quantidade          = formatNumber(f($RS,'quantidade_autorizada'),0);
    $w_cancelado           = f($RS,'cancelado');
    $w_motivo_cancelamento = f($RS,'motivo_cancelamento');
  } 

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<TITLE>'.$conSgSistema.' - Itens da solicitação</TITLE>');
  Estrutura_CSS($w_cliente);
  if (strpos('LIA',$O)!==false) {
    ScriptOpen('JavaScript');
    if ($O=='I') {
      ShowHTML('  function MarcaTodos() {');
      ShowHTML('    if (document.Form["marca"].checked) {');
      ShowHTML('       for (i=1; i < document.Form["w_item_pedido[]"].length; i++) {');
      ShowHTML('         document.Form["w_item_pedido[]"][i].checked=true;');
      ShowHTML('       } ');
      ShowHTML('    } else { ');
      ShowHTML('       for (i=1; i < document.Form["w_item_pedido[]"].length; i++) {');
      ShowHTML('         document.Form["w_item_pedido[]"][i].checked=false;');
      ShowHTML('       } ');
      ShowHTML('    }');
      ShowHTML('  }');
    }     
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if($O=='I') {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_item_pedido[]"].length!=undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_item_pedido[]"].length; i++) {');
      ShowHTML('       if (theForm["w_item_pedido[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_item_pedido[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve informar pelo menos um item!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
    } elseif($O=='A') {
      Validate('w_quantidade','Quantidade','1','1','1','18','','1');    
      Validate('w_motivo_cancelamento','Cancelamento', '1', '', '1', '500', '1', '1');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
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
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Setor solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($O=='L') {
    ShowHTML('<tr><td>');
    ShowHTML('                <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>'.exportaOffice().'Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_material_pai').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Código','codigo_interno').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Pedido','dados_pai').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Qtd','quantidade').'</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual        = 0;
      $w_exibe        = false;
      $w_item_lic     = 0;
      foreach($RS as $row){ 
        if ($w_atual!=f($row,'sq_material')) {
          if ($w_exibe) {
            ShowHTML('      <tr bgcolor="'.$w_cor.'"><td colspan=3><td align="right" nowrap><b>Total do item</td>');
            ShowHTML('        <td align="right">'.formatNumber($w_item_lic,2).'</td>');
            ShowHTML('        <td>&nbsp;</td>');
          }
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td>'.f($row,'nm_tipo_material_pai').'</td>');
          ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');
          ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
          $w_atual      = f($row,'sq_material');
          $w_exibe      = false;
          $w_item_lic   = 0;
        } else {
          ShowHTML('      <tr bgcolor="'.$w_cor.'">');
          ShowHTML('        <td colspan=3></td>');
          $w_exibe = true;
        }
        ShowHTML('        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai')).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'qtd_pedido'),2).'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&w_chave_aux2='.f($row,'item_pedido').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('        </tr>');
        $w_item_lic   += f($row,'qtd_pedido');
      }
    } 
    if ($w_exibe) {
      ShowHTML('      <tr bgcolor="'.$w_cor.'"><td colspan=3><td align="right" nowrap><b>Total do item</td>');
      ShowHTML('        <td align="right">'.formatNumber($w_item_lic,2).'</td>');
      ShowHTML('        <td>&nbsp;</td>');
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
  } elseif ($O=='I') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_item_pedido[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<tr><td>');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('          <td NOWRAP><font size="2"><input type="checkbox" name="marca" value="" onClick="javascript:MarcaTodos();" TITLE="Marca/desmarca todos os itens da relação">');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_material_pai').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Código','codigo_interno').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('U.M.','sg_unidade_medida').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Qtd.','quantidade').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Pedido','dados_solic').'</td>');
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
        ShowHTML('        <td align="center"><input type="checkbox" name="w_item_pedido[]" value="'.f($row,'chave').'">');
        ShowHTML('        <td>'.f($row,'nm_tipo_material_pai').'</td>');
        ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');        
        ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
        ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade_autorizada'),0).'</td>');
        ShowHTML('        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'dados_solic')).'</td>');
        ShowHTML('      </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&R='.$R.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('  </td>');    
    ShowHTML('</FORM>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  rodape();
} 

// =========================================================================
// Rotina de itens do contrato
// -------------------------------------------------------------------------
function ItensContrato() {
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
  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O <> 'E') {
    $w_ordem              = $_REQUEST['w_ordem'];
    $w_material           = $_REQUEST['w_material'];
    $w_codigo             = $_REQUEST['w_codigo'];
    $w_quantidade         = $_REQUEST['w_quantidade'];
    $w_valor              = $_REQUEST['w_valor'];
    $w_fabricante         = $_REQUEST['w_fabricante'];
    $w_marca_modelo       = $_REQUEST['w_marca_modelo'];
    $w_embalagem          = $_REQUEST['w_embalagem'];
    $w_fator              = $_REQUEST['w_fator'];
    $w_cancelado          = $_REQUEST['w_cancelado'];
    $w_motivo             = $_REQUEST['w_motivo'];
  } elseif ($O=='Z') {
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      $w_filtro='';

      if ($p_codigo>'')  $w_filtro.='<tr valign="top"><td align="right">Código <td>[<b>'.$p_codigo.'</b>] em qualquer parte';
      if ($p_nome>'')    $w_filtro.='<tr valign="top"><td align="right">Nome <td>[<b>'.$p_nome.'</b>] em qualquer parte';
      if ($p_tipo_material>'') {
        $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_tipo_material,null,null,null,null,null,null,'REGISTROS');
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro.='<tr valign="top"><td align="right">Tipo <td>[<b>'.f($RS,'nome_completo').'</b>]';
      } 
      if ($p_sq_cc>'') {
        $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sq_cc);
        $w_filtro.='<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 

    $sql = new db_getMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,$p_tipo_material,$p_codigo,$p_nome,'S','S',null,null,null,null,null,null,null,null,null,null,null,'COMPRA');
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_tipo_material','asc','nome','asc');
    } else {
      $RS = SortArray($RS,'nm_tipo_material','asc','nome','asc'); 
    }
  } elseif (strpos('L',$O)!==false) {
    $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,'ITEMARP');
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'ordem','asc','nm_tipo_material','asc','nome','asc'); 
    } else {
      $RS = SortArray($RS,'ordem','asc','nm_tipo_material','asc','nome','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,$w_chave_aux,$w_chave,null,null,null,null,null,null,null,null,null,null,'ITEMARP');
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave_aux           = f($RS,'chave');
    $w_material            = f($RS,'sq_material');
    $w_nm_material         = f($RS,'nome');
    $w_nm_unidade_medida   = f($RS,'nm_unidade_medida');
    $w_codigo              = f($RS,'codigo_interno');
    $w_quantidade          = formatNumber(f($RS,'quantidade'),0);
    $w_ordem               = f($RS,'ordem');
    $w_valor               = formatNumber(f($RS,'valor_unit_est'),4);
    $w_fabricante          = f($RS,'fabricante');
    $w_marca_modelo        = f($RS,'marca_modelo');
    $w_embalagem           = f($RS,'embalagem');
    $w_fator               = f($RS,'fator_embalagem');
    $w_cancelado           = f($RS,'cancelado');
    $w_motivo              = f($RS,'motivo_cancelamento');
  } 

  // Recupera informações sobre o tipo do material ou serviço
  if (nvl($w_tipo_material,'')!='') {
    $sql = new db_getTipoMatServ; $RS_Tipo = $sql->getInstanceOf($dbms,$w_cliente,$w_tipo_material,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS_Tipo as $row) { $RS_Tipo = $row; break; }
    $w_classe = f($RS_Tipo,'classe');
  } 

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<TITLE>'.$conSgSistema.' - Itens de acordo</TITLE>');
  Estrutura_CSS($w_cliente);
  if (strpos('PZLIA',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if ($O=='P') {
      Validate('p_nome','Nome','1','','3','30','1','1');
      Validate('p_codigo','Código interno','1','','2','30','1','1');
      Validate('p_tipo_material','Tipo do material ou serviço','SELECT','','1','18','','1');
      Validate('p_sq_cc','Classificação','SELECT','','1','18','','1');
      ShowHTML('if (theForm.p_nome.value=="" && theForm.p_codigo.value=="" && theForm.p_tipo_material.value=="" && theForm.p_sq_cc.value=="") {');
      ShowHTML(' alert("Informe pelo menos um critério de filtragem!");');
      ShowHTML(' return false;');
      ShowHTML('}');
    } elseif($O=='Z') {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_sq_material[]"].length!=undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_sq_material[]"].length; i++) {');
      ShowHTML('       if (theForm["w_sq_material[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_sq_material[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve informar pelo menos um item!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      ShowHTML('  for (i=1; i < theForm["w_sq_material[]"].length; i++) {');
      ShowHTML('    if((theForm["w_sq_material[]"][i].checked)&&(theForm["w_quantidade[]"][i].value==\'\')){');
      ShowHTML('      alert("Para todas os itens selecionados você deve informar a quantidade!"); ');
      ShowHTML('      return false;');
      ShowHTML('    }');
      ShowHTML('    if((theForm["w_sq_material[]"][i].checked)&&(theForm["w_quantidade[]"][i].value==\'0,00\')){');
      ShowHTML('      alert("Para todas os itens selecionados você deve informar a quantidade maior que zero!"); ');
      ShowHTML('      return false;');
      ShowHTML('    }');
      ShowHTML('  }');
    } elseif($O=='I' || $O=='A') {
      Validate('w_ordem','Item','1','1','1','5','1','1');
      Validate('w_codigo','Código','1','1','1','30','1','1');
      Validate('w_fabricante','Fabricante','1','1',2,50,'1','1');
      Validate('w_marca_modelo','Marca/Modelo','1','1',2,50,'1','1');
      Validate('w_embalagem','Embalagem','1','1',2,20,'1','1');
      Validate('w_fator','Fator de embalagem','1','1',2,20,'1','1');
      Validate('w_valor','Valor unitário','1','1','1','18','','1');
      CompValor('w_valor','Valor unitário','>',0,'0,00');  
      if ($w_cliente==9614) {
        Validate('w_quantidade','CMM','1','1','1','18','','1');
        CompValor('w_quantidade','CMM','>',0,'1');  
      } else {
        Validate('w_quantidade','Quantidade','1','1','1','18','','1');
        CompValor('w_quantidade','Quantidade','>',0,'1');  
      }
      Validate('w_motivo','Motivo indisponibilidade','1','',2,500,'1','1');
      ShowHTML('  if (theForm.w_cancelado[0].checked && theForm.w_motivo.value=="") {');
      ShowHTML('    alert("Informe o motivo da indisponibilidade!"); ');
      ShowHTML('    theForm.w_motivo.focus(); ');
      ShowHTML('    return false;');
      ShowHTML('  } else if (theForm.w_cancelado[1].checked && theForm.w_motivo.value!="") {');
      ShowHTML('    alert("Motivo da indisponibilidade só pode ser informado se o item estiver indisponível!"); ');
      ShowHTML('    theForm.w_motivo.focus(); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
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
  } elseif ($O=='L' || $P1!=1) {
    BodyOpen('onLoad="this.focus();"');
  } elseif (strpos('ZIA',$O)!==false) {
    BodyOpen('onLoad="document.Form.w_ordem.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  if ($P1!=1) {
    // Recupera os dados do contrato
    $sql = new db_getSolicData; $RS_Cont = $sql->getInstanceOf($dbms,$w_chave,$SG);

    ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    ShowHTML('<tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>');
    ShowHTML('  '.f($RS_Cont,'nome').': '.f($RS_Cont,'codigo_interno').' - '.f($RS_Cont,'titulo'));
    ShowHTML('  <br>Vigência: '.formataDataEdicao(f($RS_Cont,'inicio')).' a '.formataDataEdicao(f($RS_Cont,'fim')));
    ShowHTML('  </b></font></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    ShowHTML('<tr><td colspan="3" bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Se houver outras alterações nos dados de itens, não contempladas nesta tela, exigem a devolução para a fase de cadastramento.');
    ShowHTML('  </ul></b></font></td>');
  }

  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($P1==1) ShowHTML('                <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    if ($P1!=1) ShowHTML('                <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Item','ordem').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Código','ord_codigo_interno').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Indisponível','cancelado').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('$ Unit','valor_unit_est').'</td>');
    if ($w_cliente==9614) ShowHTML('          <td><b>'.LinkOrdena('CMM','quantidade').'</td>'); else ShowHTML('          <td><b>'.LinkOrdena('Quantidade','quantidade').'</td>');
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
        ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
        ShowHTML('        <td width="1%" nowrap>'.f($row,'codigo_interno').'</td>');
        ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
        ShowHTML('        <td width="1%" nowrap align="center">'.f($row,'nm_cancelado').'</td>');
        ShowHTML('        <td width="1%" nowrap align="right">'.formatNumber(f($row,'valor_unit_est'),4).'</td>');
        ShowHTML('        <td width="1%" nowrap align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
        ShowHTML('        <td width="1%" nowrap align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">AL</A>&nbsp');
        if ($P1==1) ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('        </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
  } elseif ($O=='Z') {
    ShowHTML('<tr><td colspan="3" bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Se o item desejado não constar da lista, entre em contato com a área de padronização de materiais para criar um novo código.');
    ShowHTML('  </ul></b></font></td>');

    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_material[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_quantidade[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<tr><td>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=P&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=P&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td NOWRAP><font size="2"><U ID="INICIO" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
    ShowHTML('                                      <U CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_material').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Código','codigo_interno').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Un.','sg_unidade_medida').'</td>');
    if ($w_cliente==9614) ShowHTML('          <td><b>'.LinkOrdena('CMM','quantidade').'</td>'); else ShowHTML('          <td><b>'.LinkOrdena('Quantidade','quantidade').'</td>');
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
        ShowHTML('        <td>'.f($row,'nm_tipo_material').'</td>');
        ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');        
        ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'chave'),$TP,null).'</td>');
        ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('        <td><input type="text" disabled name="w_quantidade[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.formatNumber(f($row,'quantidade'),0).'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe a  quantidade."></td>');
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
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    if ($P1!=1 && $O=='A') $w_readonly = ' READONLY '; else $w_readonly = '';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_material" value="'.$w_material.'">');
    ShowHTML('<INPUT type="hidden" name="w_qtd_ant" value="'.$w_quantidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b><b><u>I</u>tem:</b><br> </b><input '.$w_Disabled.$w_readonly.' accesskey="I" type="text" name="w_ordem" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_ordem.'"></td>');
    //SelecaoMatServ('<U>C</U>ódigo:','U','Selecione o item.',$w_codigo,$w_chave,null,'w_codigo','CLARP',null);
    ShowHTML('          <td><b><b><u>C</u>ódigo:</b><br> </b><input '.$w_Disabled.$w_readonly.' accesskey="C" type="text" name="w_codigo" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$w_codigo.'"></td>');
    ShowHTML('        </tr>');
    if (nvl($w_nm_material,'')!='') {
      ShowHTML('        <tr valign="top">');
      ShowHTML('          <td colspan=2>Nome:<br><b>'.$w_nm_material.'</b></td>');
      ShowHTML('          <td>Unidade de medida:<br><b>'.$w_nm_unidade_medida.'</b></td>');
      ShowHTML('        </tr>');
    }
    ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top"><td colspan="7">');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b><b><u>F</u>abricante:</b><br> </b><input '.$w_Disabled.' accesskey="F" type="text" name="w_fabricante" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$w_fabricante.'"></td>');
    ShowHTML('          <td><b><b><u>M</u>arca/Modelo:</b><br></b><input '.$w_Disabled.' accesskey="M" type="text" name="w_marca_modelo" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$w_marca_modelo.'"></td>');
    ShowHTML('          <td><b><b><u>E</u>mbalagem:</b><br></b><input '.$w_Disabled.' accesskey="E" type="text" name="w_embalagem" class="sti" SIZE="15" MAXLENGTH="20" VALUE="'.$w_embalagem.'"></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b><u>V</u>alor unitário:</b><br><input type="text" '.$w_Disabled.' accesskey="V" name="w_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,4,event);" title="Informe o valor unitário do item."></td>');
    if ($w_cliente==9614) {
      ShowHTML('          <td><b><u>C</u>MM:<br><input accesskey="C" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" '.$w_Disabled.' style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);"></td>');
    } else {
      ShowHTML('          <td><b><u>Q</u>uantidade:<br><input accesskey="Q" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" '.$w_Disabled.' style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);"></td>');
    }
    ShowHTML('          <td title="Informe o múltiplo de unidades a ser solicitado."><b><b>Fa<u>t</u>or de embalagem:</b><br></b><input '.$w_Disabled.' accesskey="T" type="text" name="w_fator" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_fator.'"></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Indisponível?</b>',$w_cancelado,'w_cancelado');
    ShowHTML('          <td colspan=2><b><u>M</u>otivo indisponibilidade:</b><br><textarea '.$w_Disabled.' accesskey="M" name="w_motivo" class="STI" ROWS=3 cols=40 title="Se o item estiver indisponível, informe o motivo.">'.$w_motivo.'</TEXTAREA></td>');
    ShowHTML('        </tr>');
    ShowHTML('      <tr><td colspan=3 align="center"><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');    
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'I');
    ShowHTML(montaFiltro('POST',true));
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
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  rodape();
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
  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endereço informado 
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach ($RS as $row) {
      $w_nome      = f($row,'nome');
      $w_descricao = f($row,'descricao');
      $w_caminho   = f($row,'chave_aux');
    }
  } 
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Título','1','1','1','255','1','1');
      Validate('w_descricao','Descrição','1','','1','1000','1','1');
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
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Setor solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('<a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
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
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
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
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  Rodape();
} 
// =========================================================================
// Rotina de pesquisa de preço dos itens da licitacao
// -------------------------------------------------------------------------
function PesquisaPreco() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  $w_troca          = $_REQUEST['w_troca'];
  $w_sq_pessoa      = $_REQUEST['w_sq_pessoa'];
  $w_tipo_pessoa    = $_REQUEST['w_tipo_pessoa'];
  $w_pesquisa       = $_REQUEST['w_pesquisa'];
  
  $p_nome           = upper($_REQUEST['p_nome']);
  $p_cpf            = $_REQUEST['p_cpf'];
  $p_cnpj           = $_REQUEST['p_cnpj'];
  $p_restricao      = $_REQUEST['p_restricao'];
  $p_campo          = $_REQUEST['p_campo'];

  // Recupera os dados da solicitacao
  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'') {
    //Dados do primero formulário (Formulário de dados cadastrais)
    $w_cpf                  = $_REQUEST['w_cpf'];
    $w_cnpj                 = $_REQUEST['w_cnpj'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_nome_resumido        = $_REQUEST['w_nome_resumido'];
    $w_sq_pessoa_pai        = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa       = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo      = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo      = $_REQUEST['w_nm_tipo_vinculo'];
    $w_interno              = $_REQUEST['w_interno'];
    $w_vinculo_ativo        = $_REQUEST['w_vinculo_ativo'];
    $w_nascimento           = $_REQUEST['w_nascimento'];
    $w_rg_numero            = $_REQUEST['w_rg_numero'];
    $w_rg_emissor           = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao           = $_REQUEST['w_rg_emissao'];
    $w_passaporte_numero    = $_REQUEST['w_passaporte_numero'];
    $w_sq_pais_passaporte   = $_REQUEST['w_sq_pais_passaporte'];
    $w_sexo                 = $_REQUEST['w_sexo'];
    $w_inscricao_estadual   = $_REQUEST['w_inscricao_estadual'];
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
    $marca                  = $_REQUEST['marca'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_inicio               = $_REQUEST['w_inicio'];
    $w_dias                 = $_REQUEST['w_dias'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_origem               = $_REQUEST['w_origem'];
    $w_fabricante           = $_REQUEST['w_fabricante'];
    $w_marca_modelo         = $_REQUEST['w_marca_modelo'];
    $w_embalagem            = $_REQUEST['w_embalagem'];
    $w_fator                = $_REQUEST['w_fator'];
  } elseif ($O!='L' && ($O=='A' || $w_sq_pessoa>'' || $O=='I')) {
    // Recupera os dados do fornecedor em co_pessoa
    $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,Nvl($w_sq_pessoa,0),null,$w_cpf,$w_cnpj,null,null,null,null,null,null,null,null,null,null,null,null,null);
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
        $w_tipo_pessoa          = f($row,'sq_tipo_pessoa');
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
        break;
      }
    }
  } elseif ($O=='P') {
    if (nvl($p_cpf.$p_cnpj.$p_nome,'')!='') {
      $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_pessoa,null,$p_cpf,$p_cnpj,$p_nome,null,null,null,null,null,null,null,null,null,null,null,null);
    }
  } elseif (strpos('L',$O)!==false) {
    // Verifica se é cotação ou proposta
    if ($w_pesquisa=='S') {
      $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
    } else {
      $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,'PROPOSTA');
    }
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc','valor_unit_est','asc');
    } else {
      if ($w_pesquisa=='S') {
        $RS = SortArray($RS,'nome','asc','valor_unidade','asc');
      } else {
        $RS = SortArray($RS,'ordem','asc','nome','asc','valor_unit_est','asc');
      }
    }
  } 

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - '.(($w_pesquisa=='S') ? 'Pesquisas de preço' : 'Propostas').'</TITLE>');
  Estrutura_CSS($w_cliente);
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    Modulo();
    FormataCPF();
    FormataCNPJ();
    FormataCEP();
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataValor();
    if (strpos('IAE',$O)!==false) {
      ShowHTML('  function valor(p_indice) {');
      ShowHTML('    var theForm = document.Form; ');
      ShowHTML('    if (theForm["w_chave_aux[]"][p_indice].checked) { ');
      //ShowHTML('       theForm["w_origem[]"][p_indice].disabled=false; ');
      ShowHTML('       theForm["w_valor[]"][p_indice].disabled=false; ');
      ShowHTML('       theForm["w_inicio[]"][p_indice].disabled=false; ');
      if($w_pesquisa=='S')ShowHTML('       theForm["w_origem[]"][p_indice].disabled=false; ');
      ShowHTML('       theForm["w_dias[]"][p_indice].disabled=false; ');
      ShowHTML('       if(theForm["w_classe[]"][p_indice].value==4) {');
      ShowHTML('         theForm["w_fabricante[]"][p_indice].disabled=false; ');
      ShowHTML('         theForm["w_marca_modelo[]"][p_indice].disabled=false; ');
      ShowHTML('       }');
      ShowHTML('    } else {');
      ShowHTML('       theForm["w_valor[]"][p_indice].disabled=true; ');
      ShowHTML('       theForm["w_inicio[]"][p_indice].disabled=true; ');
      if($w_pesquisa=='S')ShowHTML('       theForm["w_origem[]"][p_indice].disabled=true; ');
      ShowHTML('       theForm["w_dias[]"][p_indice].disabled=true; ');
      ShowHTML('       if(theForm["w_classe[]"][p_indice].value==4) {');
      ShowHTML('         theForm["w_fabricante[]"][p_indice].disabled=true; ');
      ShowHTML('         theForm["w_marca_modelo[]"][p_indice].disabled=true; ');
      ShowHTML('       }');
      ShowHTML('    }');
      ShowHTML('  }');    
      ShowHTML('  function MarcaTodos() {');
      ShowHTML('    var theForm = document.Form; ');
      ShowHTML('    if (theForm["marca"].checked) {');
      ShowHTML('       for (i=1; i < theForm["w_chave_aux[]"].length; i++) {');
      ShowHTML('         theForm["w_chave_aux[]"][i].checked=true;');
      ShowHTML('         theForm["w_valor[]"][i].disabled=false; ');
      ShowHTML('         theForm["w_inicio[]"][i].disabled=false; ');
      if($w_pesquisa=='S')ShowHTML('       theForm["w_origem[]"][i].disabled=false; ');
      ShowHTML('         theForm["w_dias[]"][i].disabled=false; ');
      ShowHTML('         if(theForm["w_classe[]"][i].value==3 || theForm["w_classe[]"][i].value==4) {');
      ShowHTML('           theForm["w_fabricante[]"][i].disabled=false; ');
      ShowHTML('           theForm["w_marca_modelo[]"][i].disabled=false; ');
      ShowHTML('           if(theForm["w_classe[]"][i].value==3) {');
      ShowHTML('             theForm["w_embalagem[]"][i].disabled=false; ');
      if($w_pesquisa=='N') ShowHTML('           theForm["w_fator[]"][i].disabled=false; ');
      ShowHTML('           }');
      ShowHTML('         }');
      ShowHTML('       } ');
      ShowHTML('    } else { ');
      ShowHTML('       for (i=1; i < theForm["w_chave_aux[]"].length; i++) {');
      ShowHTML('         theForm["w_chave_aux[]"][i].checked=false;');
      ShowHTML('         theForm["w_valor[]"][i].disabled=true; ');
      ShowHTML('         theForm["w_inicio[]"][i].disabled=true; ');
      if($w_pesquisa=='S')ShowHTML('       theForm["w_origem[]"][i].disabled=true; ');
      ShowHTML('         theForm["w_dias[]"][i].disabled=true; ');
      ShowHTML('         if(theForm["w_classe[]"][i].value==3 || theForm["w_classe[]"][i].value==4) {');
      ShowHTML('           theForm["w_fabricante[]"][i].disabled=true; ');
      ShowHTML('           theForm["w_marca_modelo[]"][i].disabled=true; ');
      ShowHTML('           if(theForm["w_classe[]"][i].value==3) {');
      ShowHTML('             theForm["w_embalagem[]"][i].disabled=true; ');
      if($w_pesquisa=='N') ShowHTML('           theForm["w_fator[]"][i].disabled=true; ');
      ShowHTML('           }');
      ShowHTML('         }');
      ShowHTML('       } ');
      ShowHTML('    }');
      ShowHTML('  }');
    }
    ValidateOpen('Validacao');
    if ($O=='P') {
      Validate('p_nome','Nome','1','','2','100','1','1');
      Validate('p_cpf','CPF','CPF','','14','14','','0123456789.-');
      Validate('p_cnpj','CNPJ','CNPJ','','18','18','','0123456789.-/');
      ShowHTML('  if (theForm.p_nome.value=="" && theForm.p_cpf.value=="" && theForm.p_cnpj.value=="") {');
      ShowHTML('     alert (\'Informe um critério para busca!\');');
      ShowHTML('     theForm.p_nome.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } elseif (strpos('IA',$O)!==false) {
      Validate('w_nome','Nome','1',1,2,60,'1','1');
      if ($w_tipo_pessoa==1) {
        if ($w_pesquisa=='S') {
          Validate('w_cpf','CPF','CPF','','14','14','','0123456789-.');
        } else {
          Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
        }
      } else {
        if ($w_pesquisa=='S') {
          Validate('w_cnpj','CNPJ','CNPJ','','18','18','','0123456789/-.');
        } else {
          Validate('w_cnpj','CNPJ','CNPJ','1','18','18','','0123456789/-.');
        }
      }
      Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
      if ($w_tipo_pessoa==1) {
        Validate('w_nascimento','Data de Nascimento','DATA','',10,10,'',1);
        Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
        Validate('w_rg_numero','Identidade','1','',2,30,'1','1');
        Validate('w_rg_emissao','Data de emissão','DATA','',10,10,'','0123456789/');
        Validate('w_rg_emissor','Órgão expedidor','1','',2,30,'1','1');
        Validate('w_passaporte_numero','Passaporte','1','',1,20,'1','1');
        Validate('w_sq_pais_passaporte','País emissor','SELECT','',1,10,'1','1');
        ShowHTML('  if ((theForm.w_rg_numero.value+theForm.w_rg_emissao.value+theForm.w_rg_emissor.value)!="" && (theForm.w_rg_numero.value=="" || theForm.w_rg_emissor.value=="")) {');
        ShowHTML('     alert("Os campos identidade, data de emissão e órgão emissor devem ser informados em conjunto!\\nDos três, apenas a data de emissão é opcional.");');
        ShowHTML('     theForm.w_rg_numero.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if ((theForm.w_passaporte_numero.value+theForm.w_sq_pais_passaporte[theForm.w_sq_pais_passaporte.selectedIndex].value)!="" && (theForm.w_passaporte_numero.value=="" || theForm.w_sq_pais_passaporte.selectedIndex==0)) {');
        ShowHTML('     alert("Os campos passaporte e país emissor devem ser informados em conjunto!");');
        ShowHTML('     theForm.w_passaporte_numero.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
      } else {
        Validate('w_inscricao_estadual','Inscrição estadual','1','',2,20,'1','1');
      }
      Validate('w_ddd','DDD','1','',2,4,'','0123456789');
      Validate('w_nr_telefone','Telefone','1','',7,25,'1','1');
      Validate('w_nr_fax','Fax','1','',7,25,'1','1');
      Validate('w_nr_celular','Celular','1','',7,25,'1','1');
      Validate('w_logradouro','Endereço','1','',4,60,'1','1');
      Validate('w_complemento','Complemento','1','',2,20,'1','1');
      Validate('w_bairro','Bairro','1','',2,30,'1','1');
      Validate('w_sq_pais','País','SELECT','',1,10,'1','1');
      Validate('w_co_uf','UF','SELECT','',1,10,'1','1');
      Validate('w_sq_cidade','Cidade','SELECT','',1,10,'','1');
      if (Nvl($w_pd_pais,'S')=='S') {
        Validate('w_cep','CEP','1','',9,9,'','0123456789-');
      } else {
        Validate('w_cep','CEP','1','',5,9,'','0123456789');
      }

      ShowHTML(' if( (theForm.w_ddd.value.length > 0) || (theForm.w_email.value.length > 0) || (theForm.w_logradouro.value.length > 0) ){');
      ShowHTML('   if( (theForm.w_sq_pais.value == 0) || (theForm.w_co_uf.value == 0) || (theForm.w_sq_cidade.value == 0)  ){');
      ShowHTML('      alert("Dados do bloco endereço/telefone exigem o campo cidade preenchido!");');
      ShowHTML('      theForm.w_sq_pais.focus();');
      ShowHTML('      return false;');
      ShowHTML('   } ');
      ShowHTML(' } ');

      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  var w_cont=1; ');
      ShowHTML('  if (theForm["w_chave_aux[]"].length!=undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_chave_aux[]"].length; i++) {');
      if(f($RS_Solic,'nm_lcjulgamento')=='Global') {
        ShowHTML('       if (theForm["w_chave_aux[]"][i].checked) {');
        ShowHTML('         w_erro=false;');
        ShowHTML('         w_cont=w_cont+1;');
        ShowHTML('       } else {');
        ShowHTML('         w_erro=true;');
        ShowHTML('       }');
      } else {
        ShowHTML('       if (theForm["w_chave_aux[]"][i].checked) w_erro=false;');
      }
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_chave_aux[]"].checked) w_erro=false;');
      ShowHTML('  }');
      if(f($RS_Solic,'nm_lcjulgamento')=='Global') {
        ShowHTML('  if (w_cont!=1 && theForm["w_chave_aux[]"].length!=w_cont) {');
        ShowHTML('    alert("Para licitação de '.(($w_cliente==6881) ? 'avaliação' : 'julgamento').' global, todos os itens deve ser selecionados!"); ');
        ShowHTML('    return false;');
        ShowHTML('  } else if (w_cont==1){');
        ShowHTML('    return confirm("Nenhum item foi selecionado, deseja continuar?"); ');
        ShowHTML('  }');
      } else {
        ShowHTML('  if (w_erro) {');
        ShowHTML('    return confirm("Nenhum item foi selecionado, deseja continuar?"); ');
        ShowHTML('  }');
      }
      ShowHTML('  for (ind=1; ind < theForm["w_chave_aux[]"].length; ind++) {');
      ShowHTML('    if(theForm["w_chave_aux[]"][ind].checked) {');
      if ($w_pesquisa=='S') {
        Validate('["w_origem[]"][ind]','Fonte da Pesquisa','',1,1,10,'1','1');
        Validate('["w_inicio[]"][ind]','Pesq. preço','DATA',1,10,10,'','0123456789/');
      }else{
        Validate('["w_inicio[]"][ind]','Proposta','DATA',1,10,10,'','0123456789/');
      }
      Validate('["w_dias[]"][ind]','Dias de Validade','',1,1,10,'','0123456789');
      Validate('["w_valor[]"][ind]','Valor','VALOR','1',6,18,'','0123456789.,');
      CompValor('["w_valor[]"][ind]','Valor','>','0','zero');
      ShowHTML('      if(theForm["w_classe[]"][ind].value==4) {');
      if ($w_pesquisa=='S') {
        // Campos opcionais na cotação
        Validate('["w_fabricante[]"][ind]','Fabricante','1','',2,50,'1','1');
        Validate('["w_marca_modelo[]"][ind]','Marca/Modelo','1','',2,50,'1','1');
      } else {
        // Campos obrigatórios na proposta
        Validate('["w_fabricante[]"][ind]','Fabricante','1','1',2,50,'1','1');
        Validate('["w_marca_modelo[]"][ind]','Marca/Modelo','1','1',2,50,'1','1');
      }
      ShowHTML('      }');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;'); 
    } elseif ($O=='E') {
      ShowHTML('  if (confirm("Confirma a exclusão do registro?")) {');
      ShowHTML('    theForm.Botao[0].disabled=true;');
      ShowHTML('    theForm.Botao[1].disabled=true;'); 
      ShowHTML('  } else {'); 
      ShowHTML('    return false;'); 
      ShowHTML('  }'); 
    }
    ValidateClose();   
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if (nvl($w_troca,'')!='') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif($O=='P') {
    BodyOpenClean('onLoad=\'document.Form.p_nome.focus()\';');
  } elseif($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
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
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Setor solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  if (nvl(f($RS_Solic,'nm_lcjulgamento'),'nulo')!='nulo') ShowHTML('            <td>'.(($w_cliente==6881) ? 'Avaliação' : 'Julgamento').':<b><br>'.f($RS_Solic,'nm_lcjulgamento').'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if (count($RS)>0) {
      ShowHTML('                <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('                <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    // Verifica se é cotação ou proposta
    $colspan=0;
    if ($w_pesquisa=='S') {
      $colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Tipo','nm_tipo_material_pai').'</td>');
      $colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Código','codigo_interno').'</td>');
      $colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Nome','nome').'</td>');
      $colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Pedido','dados_pai').'</td>');
      $colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Qtd','quantidade').'</td>');
      $colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('U.M.','sg_unidade_medida').'</td>');
      ShowHTML('          <td colspan=3><b>Última pesquisa</b></td>');
      $colspan++; ShowHTML('          <td rowspan=2 nowrap><b>'.LinkOrdena('Pesq. preços','qtd_cotacao').'</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr align="center">');
      $colspan++; ShowHTML('          <td bgColor="#f0f0f0" colspan=2><b>Validade</b></td>');
      $colspan++; ShowHTML('          <td bgColor="#f0f0f0" nowrap><b>$ Médio</b></td>');
    } else {
      $colspan++; ShowHTML('          <td><b>Item</td>');
      $colspan++; ShowHTML('          <td><b>Material</td>');
      $colspan++; ShowHTML('          <td><b>Qtd.</td>');
      $colspan++; ShowHTML('          <td><b>U.M.</td>');
      $colspan++; ShowHTML('          <td><b>Fornecedor</td>');
      $colspan++; ShowHTML('          <td><b>Dt.Prop.</b></td>');
      $colspan++; ShowHTML('          <td><b>Dias Valid.</b></td>');
      $colspan++; ShowHTML('          <td><b>$ Unitário</td>');
      $colspan++; ShowHTML('          <td><b>Total</td>');
      $colspan++; ShowHTML('          <td><b>Operações</td>');
      ShowHTML('        </tr>');
    }
    ShowHTML('        </tr>');    
    if (count($RS)==0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$colspan.' align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual        = 999;
      $rowspan        = 1;
      $w_exibe        = false;
      $w_item_lic     = 0;
      foreach($RS as $row){ 
        // Verifica se é cotação ou proposta
        if ($w_pesquisa=='S') {
          if ($w_atual>=$rowspan) {
            if ($w_exibe) {
              ShowHTML('      <tr bgcolor="'.$w_cor.'"><td colspan=3><td align="right" nowrap><b>Total do item</td>');
              ShowHTML('        <td align="right">'.formatNumber($w_item_lic,0).'</td>');
              ShowHTML('        <td colspan=5>&nbsp;</td>');
            }
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_tipo_material_pai').'</td>');
            ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');
            ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
            $w_atual      = 0;
            $rowspan      = f($row,'qtd_proposta');
            $w_exibe      = false;
            $w_item_lic   = 0;
          } else {
            ShowHTML('      <tr bgcolor="'.$w_cor.'">');
            ShowHTML('        <td colspan=3></td>');
            $w_exibe = true;
          }
          $w_atual++;
          ShowHTML('        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai')).'</td>');
          ShowHTML('        <td align="right">'.formatNumber(f($row,'qtd_pedido'),0).'</td>');
          if (nvl(f($row,'pesquisa_data'),'')=='') {
            ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
            ShowHTML('        <td colspan=4 align="center">Não há nenhuma pesquisa de preço</td>');
          } elseif (!$w_exibe) {
            ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
            ShowHTML('        <td align="center" width="1%" nowrap>'.ExibeSinalPesquisa(false,f($row,'pesquisa_data'),f($row,'pesquisa_validade'),f($row,'pesquisa_aviso')).'</td>');
            ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'pesquisa_validade'),5),'---').'</td>');
            ShowHTML('        <td align="center">'.nvl(formatNumber(f($row,'pesquisa_preco_medio'),4),'---').'</td>');
            ShowHTML('        <td align="center">'.f($row,'qtd_cotacao').'</td>');
          } else {
            ShowHTML('        <td colspan=5>&nbsp;</td>');
          }
          ShowHTML('          <td>');
          ShowHTML('            <a class="HL" HREF="'.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&w_volta=L&O=A&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'fornecedor').'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'" title="Altera os dados da pesquisa de preços">AL</a>');
          ShowHTML('            <a class="HL" HREF="'.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&w_volta=L&O=E&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'fornecedor').'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'" title="Exclusão da pesquisa de preços">EX</a>');
          ShowHTML('          </td>');
          ShowHTML('        </tr>');
          $w_item_lic   += f($row,'qtd_pedido');
        } else {
          if ($w_atual>=$rowspan) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('        <td align="center" rowspan='.f($row,'qtd_proposta').'>'.f($row,'ordem').'</td>');
            ShowHTML('        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
            ShowHTML('        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),0),'---').'</td>');
            ShowHTML('        <td rowspan='.f($row,'qtd_proposta').' align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
            $w_atual = 0;
            $rowspan = f($row,'qtd_proposta');
          } else {
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          }
          $w_atual++;
          ShowHTML('        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>');
          ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>');
          if ($w_pesquisa=='S') {
            ShowHTML('        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>');
          } else {
            ShowHTML('        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>');
          }
          ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'valor_unidade'),4),'---').'</td>');
          ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'valor_item'),4),'---').'</td>');
          ShowHTML('        <td>');
          if (nvl(f($row,'fornecedor'),'')!='') {
            ShowHTML('          <a class="HL" HREF="'.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$w_pagina.$par.'&w_volta=L&O=A&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'fornecedor').'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'" title="Altera ou exclui os dados da proposta.">AL</a>');
          }
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        }
      }
    } 
    if ($w_exibe && $w_pesquisa=='S') {
      ShowHTML('      <tr bgcolor="'.$w_cor.'"><td colspan=3><td align="right" nowrap><b>Total do item</td>');
      ShowHTML('        <td align="right">'.formatNumber($w_item_lic,2).'</td>');
      ShowHTML('        <td colspan=5>&nbsp;</td>');
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
  } elseif (strpos('IAE',$O)!==false) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    //Formulário com os dados do fornecedor
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="'.$w_tipo_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_fornecedor" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_pesquisa" value="'.$w_pesquisa.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_classe[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_valor[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_inicio[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_origem[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_dias[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_fabricante[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_marca_modelo[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_embalagem[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_fator[]" value="">');
    
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
    ShowHTML('          <tr valign="top">');
    ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    if ($w_tipo_pessoa==1) {
      ShowHTML('             <td><b><u>C</u>PF:<br><INPUT '.$w_Disabled.' ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    } else {
      ShowHTML('             <td><b><u>C</u>NPJ:<br><INPUT '.$w_Disabled.' ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cnpj" VALUE="'.$w_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
    }
    ShowHTML('          <tr valign="top">');
    ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
    if ($w_tipo_pessoa==1) {
      SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
      ShowHTML('          <td><b>Da<u>t</u>a de nascimento:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nascimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_nascimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('        <tr valign="top">');
      ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
      ShowHTML('          <td><b>Data de <u>e</u>missão:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b>Ór<u>g</u>ão emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
      ShowHTML('        <tr valign="top">');
      ShowHTML('          <td><b>Passapo<u>r</u>te:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_passaporte_numero" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_passaporte_numero.'"></td>');
      SelecaoPais('<u>P</u>aís emissor do passaporte:','P',null,$w_sq_pais_passaporte,null,'w_sq_pais_passaporte',null,null);
    } else {
      ShowHTML('          <td><b><u>I</u>nscrição estadual:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inscricao_estadual" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_inscricao_estadual.'"></td>');
    } 
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    if ($w_tipo_pessoa==1) {
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
    //Campos com os dados da pesquisa
    if ($w_pesquisa=='S') {
      $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$w_chave,$w_sq_pessoa,null,null,null,null,null,null,null,null,null,'FORNECEDORC');
      $RS = SortArray($RS,'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc','dados_pai','asc');    
    } else {
      $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$w_chave,$w_sq_pessoa,null,null,null,null,null,null,null,null,null,'FORNECEDORP');
      $RS = SortArray($RS,'ordem','asc','nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc','dados_pai','asc');    
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan="2">');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    if ($w_pesquisa=='S') {
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Pesquisa</td></td></tr>');
    } else {
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Proposta</td></td></tr>');
    }
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('<tr><td>');
    
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O!='E') {
        ShowHTML('<tr><td colspan="8" bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
        ShowHTML('  Orientação:<ul>');
        ShowHTML('  <li>Selecione os itens propostos pelo fornecedor e, para cada um deles, informe os dados solicitados.');
        ShowHTML('  <li>O campo valor deve ser informado com quatro casas decimais. Ex: se valor for "25", informe "25,0000", digitando apenas os números.');
        ShowHTML('  </ul></b></font></td>');
        ShowHTML('</tr>');
      }

      $w_cor=$conTrAlternateBgColor;
      $w_cont=0;
      ShowHTML('          <tr bgcolor="'.$w_cor.'" align="center">');
      if ($O!='E') {
        if (nvl($marca,'')!='') {
          ShowHTML('            <td NOWRAP><font size="2"><input checked type="checkbox" name="marca" value="x" onClick="javascript:MarcaTodos();" TITLE="Marca/desmarca todos os itens da relação">');
        } else {
          ShowHTML('            <td NOWRAP><font size="2"><input type="checkbox" name="marca" value="x" onClick="javascript:MarcaTodos();" TITLE="Marca/desmarca todos os itens da relação">');
        }
      }
      ShowHTML('            <td><b>Item</td>');
      ShowHTML('            <td><b>Nome</td>');
      ShowHTML('            <td><b>Qtd.</td>');
      ShowHTML('            <td><b>U.M.</td>');
      
      if ($w_pesquisa=='S'){
        ShowHTML('            <td><b>Fonte da Pesquisa</td>');
        ShowHTML('            <td><b>Dt.Pesq.</td>'); 
      }else{
        ShowHTML('            <td><b>Dt.Prop.</td>');
      }
      ShowHTML('            <td><b>Dias Valid.</td>');
      ShowHTML('            <td><b>$ Unitário</td>');
      ShowHTML('          </tr>');
      $i       = 1;
      $w_atual = 0;
      foreach($RS as $row) {
        if ($w_atual!=f($row,'chave')) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          $w_cont+= 1;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          if ($O!='E') {
            if(nvl(f($row,'fornecedor_data'),'')!='' || nvl($w_chave_aux[$i],'')!='') {
              ShowHTML('        <td align="center" valign="middle" '.((f($row,'classe')==3) ? 'rowspan="2"' : '').'><input type="checkbox" name="w_chave_aux[]" value="'.nvl($w_chave_aux[$i],f($row,'chave')).'" onClick="valor('.$w_cont.');" checked>');
              $w_Disabled = 'ENABLED';
            } else {
              ShowHTML('        <td align="center" valign="middle" '.((f($row,'classe')==3) ? 'rowspan="2"' : '').'><input type="checkbox" name="w_chave_aux[]" value="'.nvl($w_chave_aux[$i],f($row,'chave')).'" onClick="valor('.$w_cont.');">');
              $w_Disabled = 'DISABLED';
            }
          }
          ShowHTML('        <INPUT type="hidden" name="w_classe[]" value="'.f($row,'classe').'">');
          ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
          ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
          ShowHTML('        <td align="center">'.f($row,'quantidade').'</td>');
          ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
          $w_atual      = f($row,'sq_material');
          $w_exibe      = false;
          $w_item_lic   = 0;
          if ($w_pesquisa=='S'){
            SelecaoFontePesquisa(null,null,null,nvl($w_origem,f($row,'origem')),null,'w_origem[]',null,null);
          }
          ShowHTML('        <td nowrap align="center"><input '.$w_Disabled.' type="text" name="w_inicio[]" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.nvl($w_inicio[$i],Nvl(formataDataEdicao(f($row,'fornecedor_data')),formataDataEdicao(time()))).'" onKeyDown="FormataData(this,event);" title="Data da pesquisa de preço."></td>');
          if ($w_pesquisa=='S') {
            ShowHTML('        <td nowrap align="center"><input '.$w_Disabled.' type="text" name="w_dias[]" class="STI" SIZE="4" MAXLENGTH="10" VALUE="'.nvl($w_dias[$i],f($RS_Parametro,'dias_validade_pesquisa')).'" title="Dias de validade da pesquisa de preço."></td>');
          } else {
            ShowHTML('        <td nowrap align="center"><input '.$w_Disabled.' type="text" name="w_dias[]" class="STI" SIZE="4" MAXLENGTH="10" VALUE="'.nvl(nvl($w_dias[$i],f($row,'dias_validade_proposta')),f($row,'dias_validade_certame')).'" title="Dias de validade da proposta."></td>');
          }
          if(nvl(f($row,'fornecedor_valor'),'')!='') {
            ShowHTML('        <td align="center"><input type="text" '.$w_Disabled.' name="w_valor[]" class="sti" SIZE="13" MAXLENGTH="18" VALUE="'.nvl($w_valor[$i],formatNumber(f($row,'fornecedor_valor'),4)).'" style="text-align:right;" onKeyDown="FormataValor(this,18,4,event);" title="Informe o valor unitário do item."></td>');
          } else {
            ShowHTML('        <td align="center"><input type="text" '.$w_Disabled.' name="w_valor[]" class="sti" SIZE="13" MAXLENGTH="18" VALUE="'.nvl($w_valor[$i],'').'" style="text-align:right;" onKeyDown="FormataValor(this,18,4,event);" title="Informe o valor unitário do item."></td>');
          }
          ShowHTML('        </tr>');
          if (f($row,'classe')==3) {
            ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top"><td><td colspan="7">');
            ShowHTML('          <TABLE WIDTH="100%" border=0>');
            ShowHTML('            <tr valign="top">');
            ShowHTML('              <td><b>Fabricante: </b><input '.$w_Disabled.' type="text" name="w_fabricante[]" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.nvl($w_fabricante[$i],f($row,'fabricante')).'"></td>');
            ShowHTML('              <td><b>Marca/Modelo: </b><input '.$w_Disabled.' type="text" name="w_marca_modelo[]" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.nvl($w_marca_modelo[$i],f($row,'marca_modelo')).'"></td>');
            ShowHTML('              <td><b>Embalagem: </b><input '.$w_Disabled.' type="text" name="w_embalagem[]" class="sti" SIZE="15" MAXLENGTH="20" VALUE="'.nvl($w_embalagem[$i],f($row,'embalagem')).'"></td>');
            if ($w_pesquisa=='N') ShowHTML('              <td><b>Fator de embalagem: </b><input '.$w_Disabled.' type="text" name="w_fator[]" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.nvl($w_fator[$i],f($row,'fator_embalagem')).'"></td>');
            ShowHTML('        </table>');
            ShowHTML('        </tr>');
          } elseif (f($row,'classe')==4) {
            ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top"><td><td colspan="7">');
            ShowHTML('          <TABLE WIDTH="100%" border=0>');
            ShowHTML('            <tr valign="top">');
            ShowHTML('              <td><b>Fabricante: </b><input '.$w_Disabled.' type="text" name="w_fabricante[]" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.nvl($w_fabricante[$i],f($row,'fabricante')).'"></td>');
            ShowHTML('              <td><b>Marca/Modelo: </b><input '.$w_Disabled.' type="text" name="w_marca_modelo[]" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.nvl($w_marca_modelo[$i],f($row,'marca_modelo')).'"></td>');
            ShowHTML('          </table>');
            ShowHTML('        </tr>');
            ShowHTML('<INPUT type="hidden" name="w_embalagem[]" value="">');
            ShowHTML('<INPUT type="hidden" name="w_fator[]" value="">');
          } else {
            ShowHTML('<INPUT type="hidden" name="w_fabricante[]" value="">');
            ShowHTML('<INPUT type="hidden" name="w_marca_modelo[]" value="">');
            ShowHTML('<INPUT type="hidden" name="w_embalagem[]" value="">');
            ShowHTML('<INPUT type="hidden" name="w_fator[]" value="">');
          }
          $i += 1;
        }
      }
    }
    ShowHTML('        </table></tr>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="'.(($O=='E') ? 'Excluir' : 'Gravar').'">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&R='.$R.'&O='.((nvl($w_volta,'')!=='') ? $w_volta : 'P').'&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).montaFiltro('GET').'\';" name="Botao"  value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (strpos('P',$O)!==false) {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="p_restricao" value="'.$p_restricao.'">');
    ShowHTML('<INPUT type="hidden" name="p_campo" value="'.$p_campo.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_pesquisa" value="'.$w_pesquisa.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="justify"><b><ul>Instruções</b>:');
    ShowHTML('  <li>Informe parte do nome da pessoa, o CPF ou o CNPJ.');
    ShowHTML('  <li>Quando a relação for exibida, selecione a ação desejada clicando sobre o link <i>Selecionar</i>.');
    ShowHTML('  <li>Após informar os critérios de busca, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.');
    ShowHTML('  <li>Se a pessoa desejada não for encontrada, clique no botão <i>Cadastrar nova pessoa</i>, exibido abaixo da listagem.');
    ShowHTML('  <li><b>Evite cadastrar pessoas que já existem. Procure-a de diversas formas antes de cadastrá-la.</b>');
    ShowHTML('  <li><b>Se precisar alterar os dados de uma pessoa, entre em contato com os gestores do módulo.</b>');
    ShowHTML('  </ul>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan=2><b>Parte do <U>n</U>ome da pessoa:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_nome" size="50" maxlength="100" value="'.$p_nome.'">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_cpf" VALUE="'.$p_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    ShowHTML('        <td><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_cnpj" VALUE="'.$p_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" name="Botao" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
    if ($p_nome!='' || $p_cpf!='' || $p_cnpj!='') {
      ShowHTML('<tr><td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
      ShowHTML('<tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" border=0>');
      if (count($RS)==0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
        ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>CPF/CNPJ</font></td>');
        ShowHTML('            <td><b>Nome</font></td>');
        ShowHTML('            <td><b>Operações</font></td>');
        ShowHTML('          </tr>');
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('            <td align="center" width="1%" nowrap>'.nvl(f($row,'identificador_primario'),'---').'</td>');
          ShowHTML('            <td>'.f($row,'nm_pessoa').'</td>');
          ShowHTML('            <td><a class="ss" HREF="'.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$R.'&O=A&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'">Selecionar</a>');
        }
        ShowHTML('        </table></tr>');
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      } 
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="Button" name="BotaoCad" value="Cadastrar pessoa física" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$R.'&O=I&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_tipo_pessoa=1&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';">');
      ShowHTML('            <input class="stb" type="Button" name="BotaoCad" value="Cadastrar pessoa jurídica" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$R.'&O=I&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_tipo_pessoa=2&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';">');
    } 
    ShowHTML('    </table>');
    ShowHTML('    </TD>');    
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
}
// =========================================================================
// Rotina de dados de protocolo e modalidade
// -------------------------------------------------------------------------
function DadosPrevios() {
  extract($GLOBALS);
  global $w_Disabled;
  
  $w_chave          = $_REQUEST['w_chave'];
  $w_readonly       = '';
  $w_erro           = '';

  // Carrega o segmento do cliente
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  $w_segmento    = f($RS,'segmento');
  $w_cliente_arp = f($RS,'ata_registro_preco');

  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,null,null,null,null,null,null,null,null,null,null,
        $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}
  
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_lcmodalidade    = $_REQUEST['w_sq_lcmodalidade'];
    $w_numero_processo    = $_REQUEST['w_numero_processo'];
    $w_certame            = $_REQUEST['w_certame'];
    $w_numero_certame     = $_REQUEST['w_numero_certame'];
    $w_numero_ata         = $_REQUEST['w_numero_ata'];
    $w_tipo_reajuste      = $_REQUEST['w_tipo_reajuste'];
    $w_indice_base        = $_REQUEST['w_indice_base'];
    $w_sq_eoindicador     = $_REQUEST['w_sq_eoindicador'];
    $w_limite_variacao    = $_REQUEST['w_limite_variacao'];
    $w_sq_lcfonte_recurso = $_REQUEST['w_sq_lcfonte_recurso'];
    $w_sq_espec_despesa   = $_REQUEST['w_sq_espec_despesa'];  
    $w_sq_lcjulgamento    = $_REQUEST['w_sq_lcjulgamento'];
    $w_sq_lcsituacao      = $_REQUEST['w_sq_lcsituacao'];
    $w_financeiro_unico   = $_REQUEST['w_financeiro_unico'];
    $w_arp                = $_REQUEST['w_arp'];
    $w_dias               = $_REQUEST['w_dias'];
    $w_dias_ant           = $_REQUEST['w_dias_ant'];
    $w_chave_aux          = $_REQUEST['w_chave_aux'];
    $w_ordem              = $_REQUEST['w_ordem'];
    $w_dias_item          = $_REQUEST['w_dias_item'];
    $w_protocolo          = $_REQUEST['w_protocolo'];
    $w_protocolo_nm       = $_REQUEST['w_protocolo_nm'];
  } else {
    $w_sq_lcmodalidade    = f($RS_Solic,'sq_lcmodalidade');
    $w_numero_processo    = f($RS_Solic,'processo');
    $w_certame            = $w_certame;
    $w_numero_certame     = f($RS_Solic,'numero_certame');
    $w_numero_ata         = f($RS_Solic,'numero_ata');
    $w_tipo_reajuste      = f($RS_Solic,'tipo_reajuste');
    $w_limite_variacao    = f($RS_Solic,'limite_variacao');
    $w_indice_base        = f($RS_Solic,'indice_base');    
    $w_sq_eoindicador     = f($RS_Solic,'sq_eoindicador');
    $w_limite_variacao    = formatNumber(f($RS_Solic,'limite_variacao'));
    $w_sq_lcfonte_recurso = f($RS_Solic,'sq_lcfonte_recurso');
    $w_sq_espec_despesa   = f($RS_Solic,'sq_especificacao_despesa');
    $w_sq_lcjulgamento    = f($RS_Solic,'sq_lcjulgamento');
    $w_sq_lcsituacao      = f($RS_Solic,'sq_lcsituacao');
    $w_financeiro_unico   = f($RS_Solic,'financeiro_unico');
    $w_arp                = f($RS_Solic,'arp');
    $w_dias               = f($RS_Solic,'dias_validade_proposta');
    $w_dias_ant           = f($RS_Solic,'dias_validade_proposta');
    $w_protocolo          = f($RS_Solic,'processo');
    $w_protocolo_nm       = f($RS_Solic,'processo');
  } 

  // Recupera informação sobre a modalidade ter certame
  if (nvl($w_sq_lcmodalidade,'')!='') {
    $sql = new db_getLCModalidade; $RS = $sql->getInstanceOf($dbms, $w_sq_lcmodalidade, $w_cliente, null, null, null, null);
    foreach ($RS as $row) { $RS = $row; break; }
    $w_certame = f($RS,'certame');
  }
  
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_sq_lcmodalidade','Modalidade','SELECT','1',1,18,'','0123456789');
  if ($w_pa=='S') {
    Validate('w_protocolo_nm','Número do processo','hidden','1','20','20','','0123456789./-');
  } elseif($w_segmento=='Público') {
    Validate('w_numero_processo','Número do processo','1','1',1,30,'1','1');
  }
  //if ($w_certame=='S') Validate('w_numero_certame','Número do certame','1','1',1,30,'1','1');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_sq_lcmodalidade.focus()\';');
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
  ShowHTML('            <td>'.f($RS_Menu,'nome').':<b><br>'.f($RS_Solic,'codigo_interno').'</td>');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Setor solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_arp" value="'.$w_arp.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('<tr><td colspan="3" bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orientação:<ul>');
  ShowHTML('  <li>Informe abaixo a modalidade, para que o sistema possa verificar a necessidade de pesquisas de preço e de certame.');
  ShowHTML('  <li>Informe também o protocolo do processo de compra.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr valign="top">');
  //SelecaoLCModalidade('<u>M</u>odalidade:','M','Selecione na lista a modalidade do certame.',$w_sq_lcmodalidade,null,'w_sq_lcmodalidade',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_lcmodalidade\'; document.Form.submit();"');
  SelecaoLCModalidade('<u>M</u>odalidade:','M','Selecione na lista a modalidade do certame.',$w_sq_lcmodalidade,null,'w_sq_lcmodalidade',null,null);
  if ($w_pa=='S') {
    SelecaoProtocolo('N<u>ú</u>mero do protocolo:','U','Selecione o protocolo da compra.',$w_protocolo,null,'w_protocolo','JUNTADA',null);
  } elseif($w_segmento=='Público') {
    ShowHTML('          <td><b>N<u>ú</u>mero do protocolo:</b><br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="sti" type="text" name="w_numero_processo" size="30" maxlength="30" value="'.$w_numero_processo.'" title="Número do processo de compra/contratação."></td>');
  }
  //if ($w_certame=='S') ShowHTML('          <td><b><u>N</u>úmero do certame:</b><br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_numero_certame" size="30" maxlength="30" value="'.$w_numero_certame.'" title="Número do certame licitatório."></td>');
  ShowHTML('      <tr><td align="center" colspan=3><hr>');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&SG=CLLCCAD&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.removeTP($TP).MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
// Rotina de dados de análise
// -------------------------------------------------------------------------
function DadosAnalise() {
  extract($GLOBALS);
  global $w_Disabled;
  
  $w_chave          = $_REQUEST['w_chave'];
  $w_readonly       = '';
  $w_erro           = '';
  
    // Carrega o segmento do cliente
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  $w_segmento    = f($RS,'segmento');
  $w_cliente_arp = f($RS,'ata_registro_preco');
  
  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_lcmodalidade    = $_REQUEST['w_sq_lcmodalidade'];
    $w_numero_processo    = $_REQUEST['w_numero_processo'];
    $w_numero_certame     = $_REQUEST['w_numero_certame'];
    $w_executor           = $_REQUEST['w_executor'];
    $w_numero_ata         = $_REQUEST['w_numero_ata'];
    $w_tipo_reajuste      = $_REQUEST['w_tipo_reajuste'];
    $w_indice_base        = $_REQUEST['w_indice_base'];
    $w_sq_eoindicador     = $_REQUEST['w_sq_eoindicador'];
    $w_limite_variacao    = $_REQUEST['w_limite_variacao'];
    $w_sq_lcfonte_recurso = $_REQUEST['w_sq_lcfonte_recurso'];
    $w_sq_espec_despesa   = $_REQUEST['w_sq_espec_despesa'];  
    $w_sq_lcjulgamento    = $_REQUEST['w_sq_lcjulgamento'];
    $w_sq_lcsituacao      = $_REQUEST['w_sq_lcsituacao'];
    $w_financeiro_unico   = $_REQUEST['w_financeiro_unico'];
    $w_arp                = $_REQUEST['w_arp'];
    $w_dias               = $_REQUEST['w_dias'];
    $w_dias_ant           = $_REQUEST['w_dias_ant'];
    $w_chave_aux          = $_REQUEST['w_chave_aux'];
    $w_ordem              = $_REQUEST['w_ordem'];
    $w_dias_item          = $_REQUEST['w_dias_item'];
    $w_quantidade         = $_REQUEST['w_quantidade'];
    $w_detalhamento       = $_REQUEST['w_detalhamento'];
    $w_protocolo          = $_REQUEST['w_protocolo'];
    $w_protocolo_nm       = $_REQUEST['w_protocolo_nm'];
    $w_contrato           = $_REQUEST['w_contrato'];
    $w_abertura           = $_REQUEST['w_abertura'];
    $w_envelope_1         = $_REQUEST['w_envelope_1'];
    $w_envelope_2         = $_REQUEST['w_envelope_2'];
    $w_envelope_3         = $_REQUEST['w_envelope_3'];
    $w_prioridade         = $_REQUEST['w_prioridade'];
    $w_inicio             = $_REQUEST['w_inicio'];
  } else {
    $w_sq_lcmodalidade    = f($RS_Solic,'sq_lcmodalidade');
    $w_numero_processo    = f($RS_Solic,'processo');
    $w_numero_certame     = f($RS_Solic,'numero_certame');
    $w_executor           = f($RS_Solic,'executor');
    $w_numero_ata         = f($RS_Solic,'numero_ata');
    $w_tipo_reajuste      = f($RS_Solic,'tipo_reajuste');
    $w_limite_variacao    = f($RS_Solic,'limite_variacao');
    $w_indice_base        = f($RS_Solic,'indice_base');    
    $w_sq_eoindicador     = f($RS_Solic,'sq_eoindicador');
    $w_limite_variacao    = formatNumber(f($RS_Solic,'limite_variacao'));
    $w_sq_lcfonte_recurso = f($RS_Solic,'sq_lcfonte_recurso');
    $w_sq_espec_despesa   = f($RS_Solic,'sq_especificacao_despesa');
    $w_sq_lcjulgamento    = f($RS_Solic,'sq_lcjulgamento');
    $w_sq_lcsituacao      = f($RS_Solic,'sq_lcsituacao');
    $w_financeiro_unico   = f($RS_Solic,'financeiro_unico');
    $w_arp                = f($RS_Solic,'arp');
    $w_dias               = f($RS_Solic,'dias_validade_proposta');
    $w_dias_ant           = f($RS_Solic,'dias_validade_proposta');
    $w_protocolo          = f($RS_Solic,'protocolo_completo');
    $w_protocolo_nm       = f($RS_Solic,'protocolo_completo');
    $w_abertura           = substr(formataDataEdicao(f($RS_Solic,'phpdt_data_abertura'),3),0,-3);
    $w_envelope_1         = substr(formataDataEdicao(f($RS_Solic,'phpdt_envelope_1'),3),0,-3);
    $w_envelope_2         = substr(formataDataEdicao(f($RS_Solic,'phpdt_envelope_2'),3),0,-3);
    $w_envelope_3         = substr(formataDataEdicao(f($RS_Solic,'phpdt_envelope_3'),3),0,-3);
    $w_prioridade         = f($RS_Solic,'prioridade');
    $w_inicio             = FormataDataEdicao(f($RS_Solic,'inicio'));
  }

  if (nvl($w_sq_lcmodalidade,'')!='') {
    $sql = new db_getLCModalidade; $RS_Modal = $sql->getInstanceOf($dbms, $w_sq_lcmodalidade, $w_cliente, null, null, null, null);
    foreach($RS_Modal as $row) { $RS_Modal = $row; break; }
    $w_contrato = f($RS_Modal,'gera_contrato');
    $w_certame  = f($RS_Modal,'certame');
  }
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataValor();
  SaltaCampo();
  FormataDataMA();
  FormataData();
  FormataDataHora();
  ShowHTML('function diasValidade(p_campo) { ');
  ShowHTML('  if (document.Form.w_dias_ant.value!=p_campo.value) {');
  ShowHTML('    for (i=1; i < document.Form["w_dias_item[]"].length; i++) {');
  ShowHTML('      document.Form["w_dias_item[]"][i].value=p_campo.value;');
  ShowHTML('    }');
  ShowHTML('    document.Form.w_dias_ant.value=p_campo.value;');
  ShowHTML('  }');
  ShowHTML('}');
  ValidateOpen('Validacao');
  Validate('w_sq_lcmodalidade','Modalidade','SELECT','1',1,18,'','0123456789');
  if ($w_pa=='S') {
    Validate('w_protocolo_nm','Número do processo','hidden','','20','20','','0123456789./-');
  } elseif($w_segmento=='Público') {
    Validate('w_numero_processo','Número do processo','1','1',1,30,'1','1');
  }
  Validate('w_sq_lcsituacao','Situação','SELECT','1',1,18,'','0123456789');
  Validate('w_dias','Mínimo de dias de validade','1','1',1,10,'','0123456789');
  Validate('w_prioridade','Prioridade','SELECT',1,1,1,'','0123456789');
  Validate('w_inicio','Início da licitação','DATA','',10,10,'','0123456789/');
  if ($w_certame=='S') {
    //Validate('w_numero_certame','Número do certame','1','1',1,50,'1','1');
    Validate('w_sq_lcjulgamento','Critério de '.(($w_cliente==6881) ? 'avaliação' : 'julgamento'),'SELECT','1',1,18,'','0123456789');
    Validate('w_abertura','Data de recebimento das propostas','DATAHORA','',17,17,'','0123456789/,: ');
    Validate('w_envelope_1','Data de abertura do envelope 1','DATAHORA','',17,17,'','0123456789/,: ');
    Validate('w_envelope_2','Data de abertura do envelope 2','DATAHORA','',17,17,'','0123456789/,: ');
    Validate('w_envelope_3','Data de abertura do envelope 3','DATAHORA','',17,17,'','0123456789/,: ');
  }
  if($w_cliente_arp=='S') {
    Validate('w_sq_lcfonte_recurso','Fonte de recurso','SELECT','1',1,18,'','0123456789');
    Validate('w_sq_espec_despesa','Especificação de despesa','SELECT','1',1,18,'','0123456789');
  }
  if($w_arp=='S') {
    Validate('w_numero_ata','Número da ata','1','1',1,30,'1','1');
  }
  if ($w_contrato=='S') {
    Validate('w_tipo_reajuste','Tipo de reajuste','SELECT','1',1,18,'','0123456789');
    if($w_tipo_reajuste==1) {
      Validate('w_indice_base','Índice base','DATAMA','1',1,7,'1','1');
      Validate('w_sq_eoindicador','Índice de reajuste','SELECT','1',1,18,'','0123456789');
    }
    Validate('w_limite_variacao','Limite de acréscimo/supressão','VALOR','1',4,18,'','0123456789.,');  
  }
  ShowHTML('  for (ind=1; ind < theForm["w_ordem[]"].length; ind++) {');
  Validate('["w_ordem[]"][ind]','Número de ordem','VALOR','1',1,5,'','0123456789');
  ShowHTML('  }');
  ShowHTML('  for (ind=1; ind < theForm["w_dias_item[]"].length; ind++) {');
  Validate('["w_dias_item[]"][ind]','Dias da proposta para o item','VALOR','1',1,4,'','0123456789');
  ShowHTML('  }');
  ShowHTML('  for (ind=1; ind < theForm["w_detalhamento[]"].length; ind++) {');
  Validate('["w_quantidade[]"][ind]','Quantidade','1','1','1','18','','0123456789');
  Validate('["w_detalhamento[]"][ind]','Detalhamento do item','1','1','2','4000','1','1');
  ShowHTML('  }');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_sq_lcmodalidade.focus()\';');
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
  ShowHTML('            <td>'.f($RS_Menu,'nome').':<b><br>'.f($RS_Solic,'codigo_interno').'</td>');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Setor solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_arp" value="'.$w_arp.'">');
  ShowHTML('<INPUT type="hidden" name="w_dias_ant" value="'.$w_dias_ant.'">');
  ShowHTML('<INPUT type="hidden" name="w_dias_item[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_ordem[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_quantidade[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_detalhamento[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave_aux[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_arp" value="">');
  ShowHTML('<INPUT type="hidden" name="w_contrato" value="'.$w_contrato.'">');
  ShowHTML('<INPUT type="hidden" name="w_financeiro_unico" value="N">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('<tr valign="top">');

  SelecaoLCModalidade('<u>M</u>odalidade:','M','Selecione na lista a modalidade do certame.',$w_sq_lcmodalidade,null,'w_sq_lcmodalidade',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_lcmodalidade\'; document.Form.submit();"');
  if ($w_pa=='S') {
    SelecaoProtocolo('N<u>ú</u>mero do protocolo:','U','Selecione o protocolo da compra.',$w_protocolo,null,'w_protocolo','JUNTADA',null);
  } elseif($w_segmento=='Público') {
    ShowHTML('          <td><b>N<u>ú</u>mero do protocolo:</b><br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="sti" type="text" name="w_numero_processo" size="30" maxlength="30" value="'.$w_numero_processo.'" title="Número do processo de compra/contratação."></td>');
  }
  SelecaoLCSituacao('<u>S</u>ituação:','S','Selecione a situação do certame.',$w_sq_lcsituacao,null,'w_sq_lcsituacao','DADOS',null);
  if($w_cliente_arp=='S') {
    ShowHTML('<tr valign="top">');
    selecaoLCFonteRecurso('<U>F</U>onte de recurso:','F','Selecione a fonte de recurso',$w_sq_lcfonte_recurso,null,'w_sq_lcfonte_recurso',null,null);
    selecaoCTEspecificacao('<u>E</u>specificação de despesa:','E','Selecione a especificação de despesa.',$w_espec_despesa,$w_sq_espec_despesa,$w_sq_cc,$_SESSION['ANO'],'w_sq_espec_despesa','S',null,null);
  }
  ShowHTML('<tr valign="top">');
  SelecaoPessoa('<u>R</u>esponsável pela execução:','N','Selecione o executor na relação.',$w_executor,null,'w_executor','USUARIOS');
  if($w_arp=='S') {
    ShowHTML('          <td><b>Número da <u>a</u>ta:</b><br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="sti" type="text" name="w_numero_ata" size="30" maxlength="30" value="'.$w_numero_ata.'" title="Número da ata."></td>');
  }
  ShowHTML('<tr valign="top">');
  ShowHTML('          <td><b>Mínimo de <u>d</u>ias de validade das propostas:</b><br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="w_dias" size="4" maxlength="10" value="'.$w_dias.'" title="Número mínimo de dias para a validade das propostas." onBlur="diasValidade(this);"></td>');
  SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade desta licitação.',$w_prioridade,null,'w_prioridade',null,null);
  ShowHTML('            <td><b><u>I</u>nício da licitação:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data de início dos trabalhos para execução da licitação.">'.ExibeCalendario('Form','w_inicio').'</td>');
  if ($w_certame=='S') {
    ShowHTML('<tr valign="top">');
    //ShowHTML('      <td><b><u>N</u>úmero do certame:</b><br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_numero_certame" size="30" maxlength="50" value="'.$w_numero_certame.'" title="Número do certame licitatório."></td>');
    SelecaoLCJulgamento('Critério de '.(($w_cliente==6881) ? 'ava<u>l</u>iação' : 'ju<u>l</u>gamento').':','L','Selecione o critério de '.(($w_cliente==6881) ? 'avaliação' : 'julgamento').' do certame.',$w_sq_lcjulgamento,null,'w_sq_lcjulgamento',null,null);
    ShowHTML('      <td><b><u>D</u>ata de recebimento das propostas:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_abertura" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_abertura.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);">'.ExibeCalendario('Form','w_abertura').'</td>');
    ShowHTML('<tr valign="top">');
    ShowHTML('      <td><b><u>D</u>ata de abertura do envelope 1:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_envelope_1" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_envelope_1.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);">'.ExibeCalendario('Form','w_envelope_1').'</td>');
    ShowHTML('      <td><b><u>D</u>ata de abertura do envelope 2:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_envelope_2" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_envelope_2.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);">'.ExibeCalendario('Form','w_envelope_2').'</td>');
    ShowHTML('      <td><b><u>D</u>ata de abertura do envelope 3:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_envelope_3" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_envelope_3.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);">'.ExibeCalendario('Form','w_envelope_3').'</td>');
  }
  if ($w_contrato=='S') {
    ShowHTML('      <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0"><b>Dados para geração do contrato</td></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3>Os dados abaixo servem como parâmetro para geração do contrato e constam do Edital.</td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('<tr valign="top">');
    SelecaoTipoReajuste('<u>T</u>ipo de reajuste:','T','Indica o tipo de reajuste do certame.',$w_tipo_reajuste,null,'w_tipo_reajuste',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_tipo_reajuste\'; document.Form.submit();"');
    if($w_tipo_reajuste==1) {
      ShowHTML('          <td><b>Ín<u>d</u>ice base:</b><br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_indice_base" size="7" maxlength="7" value="'.$w_indice_base.'" onKeyDown="FormataDataMA(this,event);" onKeyUp="SaltaCampo(this.form.name,this,7,event);" title="Registra mês e ano (MM/AAAA) do índice original, quando o certame permitir reajuste em índices."></td>');
      selecaoIndicador('<U>I</U>ndicador:','I','Define o índice de reajuste do contrato se for permitido.',$w_sq_eoindicador,null,$w_usuario,null,'w_sq_eoindicador',null,null);
    }
    ShowHTML('      <tr><td><b><u>L</u>imite de acréscimo/supressão (%):</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_limite_variacao" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_limite_variacao.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Percentual para indicar o limite de acréscimo ou supressão no valor original."></td>');
  }
  ShowHTML('      <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0"><b>Ordenação dos itens</td></td></tr>');
  ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan=3>Na lista abaixo, revise a ordenação dos itens da licitação.</td></tr>');
  ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,'COMPRA');
  $RS = SortArray($RS,'ordem','asc','nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc'); 
  ShowHTML('<tr><td colspan=3>');
  ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="'.$conTrAlternateBgColor.'" align="center">');
  ShowHTML('          <td><b>Ordem</td>');
  ShowHTML('          <td><b>Dias</td>');
  ShowHTML('          <td><b>Código</td>');
  ShowHTML('          <td><b>Nome</td>');
  ShowHTML('          <td><b>Quantidade</td>');
  ShowHTML('        </tr>');
  // Lista os registros selecionados para listagem
  $w_atual    = 0;
  $w_exibe    = false;
  $w_item_lic = 0;
  $w_cor      = $conTrAlternateBgColor;
  $i          = 1;
  foreach($RS as $row) { 
    $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux[]" value="'.f($row,'chave').'">');
    ShowHTML('        <td rowspan="2" align="center" nowrap><INPUT class="sti" type="text" name="w_ordem[]" size="5" maxlength="5" value="'.nvl(nvl($w_ordem[$i],f($row,'ordem')),$i).'" title="Ordem do item na licitação."></td>');
    ShowHTML('        <td rowspan="2" align="center" nowrap><INPUT class="sti" type="text" name="w_dias_item[]" size="4" maxlength="10" value="'.nvl($w_dias_item[$i],f($row,'dias_validade_item')).'" title="Mínimo de dias de validade das propostas de preço para este item."></td>');
    ShowHTML('        <td rowspan="2">'.f($row,'codigo_interno').'</td>');
    ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
    $w_atual = f($row,'sq_material');
    ShowHTML('        <td align="center"><input type="text" name="w_quantidade[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.nvl($w_quantidade[$i],formatNumber(f($row,'quantidade'),0)).'" style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);" title="Informe a  quantidade."></td>');
    ShowHTML('      <tr bgcolor="'.$w_cor.'"><td colspan="2"><b><u>D</u>etalhamento:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_detalhamento[]" class="STI" ROWS=5 cols=75 title="Descreva as características desejadas para este item, de modo a evitar mal entendidos sobre o que se deseja.">'.nvl($w_detalhamento[$i],f($row,'det_item')).'</TEXTAREA></td>');
    $i += 1;
  } 
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="center" colspan=3><hr>');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&SG=CLLCCAD&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.removeTP($TP).MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
// Rotina de alteração da situação do certame
// -------------------------------------------------------------------------
function Informar() {
  extract($GLOBALS);
  global $w_Disabled;
  
  $w_chave          = $_REQUEST['w_chave'];
  $w_readonly       = '';
  $w_erro           = '';
    
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_lcsituacao      = $_REQUEST['w_sq_lcsituacao'];
    $w_abertura           = $_REQUEST['w_abertura'];
    $w_envelope_1         = $_REQUEST['w_envelope_1'];
    $w_envelope_2         = $_REQUEST['w_envelope_2'];
    $w_envelope_3         = $_REQUEST['w_envelope_3'];
    $w_prioridade         = $_REQUEST['w_prioridade'];
    $w_inicio             = $_REQUEST['w_inicio'];
  } else {
    $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,null,null,null,null,null,null,null,null,null,null,
            $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
    $w_sq_lcsituacao    = f($RS,'sq_lcsituacao');
    $w_abertura         = substr(formataDataEdicao(f($RS_Solic,'phpdt_data_abertura'),3),0,-3);
    $w_envelope_1       = substr(formataDataEdicao(f($RS_Solic,'phpdt_envelope_1'),3),0,-3);
    $w_envelope_2       = substr(formataDataEdicao(f($RS_Solic,'phpdt_envelope_2'),3),0,-3);
    $w_envelope_3       = substr(formataDataEdicao(f($RS_Solic,'phpdt_envelope_3'),3),0,-3);
    $w_prioridade       = f($RS,'prioridade');
    $w_inicio           = FormataDataEdicao(f($RS,'inicio'));
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
  ValidateOpen('Validacao');
  Validate('w_sq_lcsituacao','Situação','SELECT','1',1,18,'','0123456789');
  Validate('w_inicio','Início da licitação','DATA','',10,10,'','0123456789/');
  Validate('w_prioridade','Prioridade','SELECT',1,1,1,'','0123456789');
  Validate('w_abertura','Data de recebimento das propostas','DATAHORA','',17,17,'','0123456789/,: ');
  Validate('w_envelope_1','Data de abertura do envelope 1','DATAHORA','',17,17,'','0123456789/,: ');
  Validate('w_envelope_2','Data de abertura do envelope 2','DATAHORA','',17,17,'','0123456789/,: ');
  Validate('w_envelope_3','Data de abertura do envelope 3','DATAHORA','',17,17,'','0123456789/,: ');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_sq_lcsituacao.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('  <table width="100%" border="0">');
  ShowHTML('    <tr valign="top">');
  SelecaoLCSituacao('<u>S</u>ituação:','S','Selecione a situação do certame.',$w_sq_lcsituacao,null,'w_sq_lcsituacao','DADOS',null);
  SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade deste projeto.',$w_prioridade,null,'w_prioridade',null,null);
  ShowHTML('      <td><b><u>D</u>ata de recebimento das propostas:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_abertura" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_abertura.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);">'.ExibeCalendario('Form','w_abertura').'</td>');
  ShowHTML('      <td><b><u>I</u>nicio da licitação:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data de início dos trabalhos para execução da licitação.">'.ExibeCalendario('Form','w_inicio').'</td>');
  ShowHTML('    </tr>');
  ShowHTML('    <tr valign="top">');
  ShowHTML('      <td><b><u>D</u>ata de abertura do envelope 1:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_envelope_1" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_envelope_1.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);">'.ExibeCalendario('Form','w_envelope_1').'</td>');
  ShowHTML('      <td><b><u>D</u>ata de abertura do envelope 2:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_envelope_2" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_envelope_2.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);">'.ExibeCalendario('Form','w_envelope_2').'</td>');
  ShowHTML('      <td><b><u>D</u>ata de abertura do envelope 3:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_envelope_3" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_envelope_3.'" onKeyDown="FormataDataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);">'.ExibeCalendario('Form','w_envelope_3').'</td>');
  ShowHTML('      <tr><td align="center" colspan="4" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('      <tr><td align="center" colspan="4">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&SG=CLLCCAD&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.removeTP($TP).MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_embed;

  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = upper(trim($_REQUEST['w_tipo']));

  headerGeral('V', $w_tipo, $w_chave, 'Visualização de '.f($RS_Menu,'nome'), $w_embed, null, 4, $w_linha_pag,$w_filtro);

  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualCertame($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') {
    ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
    ScriptOpen('JavaScript');
    ShowHTML('  var comando, texto;');
    ShowHTML('  if (window.name!="content" && window.name!="Lista") {');
    ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
    ShowHTML('  }');
    ScriptClose();
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
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
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
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML(VisualCertame($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CLLCCAD',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<tr ><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  $w_tramite    = $_REQUEST['w_tramite'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];    
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
  } else {
    // Recupera os dados da solicitacao
    $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,5,null,null,null,null,null,null,null,null,null,null,
            $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
    $w_inicio        = f($RS,'inicio');
    $w_fim           = f($RS,'fim');
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
  if ($O=='V') $w_erro = ValidaCertame($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);

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
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<center>');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualCertame($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || $w_ativo=='N' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CLLCENVIO',$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('    <tr><td colspan="2"><table border=0 width="100%">');
    if ($w_sg_tramite=='CI') {
      if (substr(Nvl($w_erro,'nulo'),0,1)!='0') {
        // Se cadastramento inicial
        ShowHTML('<INPUT type="hidden" name="w_envio" value="N">');
        ShowHTML('      </table>');
        ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr>');
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
        ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
      } else {
        ShowHTML('    <tr><td align="center" colspan=4><hr>');
        ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');    
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
      SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a PCD, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','DEVFLUXO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolução):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a PCD.">'.$w_despacho.'</TEXTAREA></td>');
      if (!(substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE' || $w_ativo=='N')) {
        if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
          if (addDays($w_inicio,-$w_prazo)<addDays(time(),-1)) {
            ShowHTML('    <tr><td><b><u>J</u>ustificativa para não cumprimento do prazo regulamentar de '.$w_prazo.' dias:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o início da viagem for anterior a '.FormataDataEdicao(addDays(time(),$w_prazo)).', justifique o motivo do não cumprimento do prazo regulamentar para o pedido.">'.$w_justificativa.'</TEXTAREA></td>');
          } 
        } 
      } 
      ShowHTML('      </table>');
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anotação','','1','1','2000','1','1');
    Validate('w_caminho','Arquivo','','','5','255','1','1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
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
  // Chama a rotina de visualização dos dados da solicitacao, na opção 'Listagem'
  ShowHTML(VisualCertame($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=CLLCENVIO&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td><b>A<u>n</u>otação:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anotação desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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

  //Recupera os dados da solicitação
  $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
            $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $w_tramite       = f($RS,'sq_siw_tramite');
  $w_gera_contrato = f($RS,'gera_contrato');
  $w_situacao      = f($RS,'sq_lcsituacao');
  $w_modalidade    = f($RS,'sq_lcmodalidade');
  $w_responsavel   = f($RS,'recebedor');
  $w_executor      = f($RS,'executor');
  $w_artigo        = f($RS,'sq_modalidade_artigo');
  $w_fundo_fixo    = f($RS,'fundo_fixo');
  $w_participantes = f($RS,'minimo_participantes');
  $w_conclusao     = f($RS,'conclui_sem_proposta');

  // Se for recarga da página
  if ($w_troca>'') {
    $w_homologacao        = $_REQUEST['w_homologacao'];
    $w_data_diario        = $_REQUEST['w_data_diario'];    
    $w_pagina_diario      = $_REQUEST['w_pagina_diario'];
    $w_executor           = $_REQUEST['w_executor'];
    $w_nota_conclusao     = $_REQUEST['w_nota_conclusao'];
    $w_responsavel        = $_REQUEST['w_responsavel'];
    $w_situacao           = $_REQUEST['w_situacao'];
    $w_artigo             = $_REQUEST['w_artigo'];
    $w_fundo_fixo         = $_REQUEST['w_fundo_fixo'];
    $w_vencedor           = $_REQUEST['w_vencedor'];
  }
  
  // Recupera enquadramentos
  $sql = new db_getLCModEnq; $RS_Enq = $sql->getInstanceOf($dbms, $w_modalidade, null, null, null, null);

  // Recupera os itens da solicitação
  $sql = new db_getCLSolicItem; $RS_Itens = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
  
  $w_indica_usuario = 'N';
  if (f($RS,'gera_contrato')=='N') {
    // Modalidades sem contrato geram pagamento eventual
    $sql = new db_getMenuCode; $RS_Fin = $sql->getInstanceOf($dbms,$w_cliente,'FNDEVENT');
    foreach ($RS_Fin as $row) { $RS_Fin = $row; break; }
    if (count($RS_Fin)>0) {
      $sql = new db_getTramiteList; $RS_Tramite = $sql->getInstanceOf($dbms,f($RS_Fin,'sq_menu'),null,null,null);
      $RS_Tramite = SortArray($RS_Tramite,'ordem','asc');
      foreach($RS_Tramite as $row) { $RS_Tramite = $row; break; }
      $w_indica_usuario = 'S';
    }
  }

  // Recupera dados da situação selecionada
  $sql = new db_getLCSituacao; $RS_Sit = $sql->getInstanceOf($dbms, $w_situacao, $w_cliente, null, null, null, null, null, null);
  foreach ($RS_Sit as $row) { $RS_Sit = $row; break; }
  $w_conclusao = f($RS_Sit,'conclui_sem_proposta');

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  head();
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  FormataValor();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') {
    Validate('w_homologacao','Data de homologação','DATA','',10,10,'','0123456789/');
  } elseif ($w_gera_contrato=='S') {
    Validate('w_homologacao','Data de homologação','DATA','',10,10,'','0123456789/');
    Validate('w_data_diario','Data de publicação no diário oficial','DATA','',10,10,'','0123456789/');
    Validate('w_pagina_diario','Página do diário oficial','1','',1,4,'','1');
  } else {
    Validate('w_homologacao','Data de autorização','DATA','',10,10,'','0123456789/');
    Validate('w_executor','Responsável pelo pagamento','HIDDEN',1,1,18,'','0123456789');
  }
  Validate('w_nota_conclusao','Nota de conclusão','','','1','2000','1','1');
  if ($w_indica_usuario=='S') Validate('w_responsavel','Responsável pelo recebimento','HIDDEN',1,1,18,'','0123456789');
  Validate('w_situacao','Situação','SELECT',1,1,18,'','1');
  if (count($RS_Enq)>0) Validate('w_artigo','Artigo','SELECT',1,1,18,'','1');
  // Se a modalidade não permite participantes, então não valida itens
  if ($w_participantes>0 && $w_conclusao=='N') {
    ShowHTML('  var i; ');
    ShowHTML('  var j; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=1; i <= '.count($RS_Itens).'; i++) {');
    ShowHTML('    if (theForm["w_vencedor["+i+"]"].length!=undefined) {');
    ShowHTML('       for (j=0; j < theForm["w_vencedor["+i+"]"].length; j++) {');
    ShowHTML('         if (theForm["w_vencedor["+i+"]"][j].checked) w_erro=false;');
    ShowHTML('       }');
    ShowHTML('    } else {');
    ShowHTML('       if (theForm["w_vencedor["+i+"]"].checked) w_erro=false;');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert("Você deve indicar o vencedor de cada um dos itens!"); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
  }
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if (substr(Nvl($w_erro,'nulo'),0,1)!='0') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad="this.focus()";');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualCertame($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'CLLCCONC', $w_pagina . $par, $O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="100%" border="0">');
  // Se a modalidade não permite participantes, então não exibe mensagem
  if ($w_participantes>0) {
    ShowHTML('    <tr><td colspan="3" bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('      <ul>Orientação:');
    ShowHTML('      <li>Informe os dados solicitados e indique o vencedor para cada item.');
    if ($w_indica_usuario=='S') {
      ShowHTML('<INPUT type="hidden" name="w_financeiro_menu" value="'.f($RS_Fin,'sq_menu').'">');
      ShowHTML('<INPUT type="hidden" name="w_financeiro_tramite" value="'.f($RS_Tramite,'sq_siw_tramite').'">');
      ShowHTML('      <li><b>SERÁ GERADO '.upper(f($RS_Fin,'nome')).', NO TRÂMITE DE '.upper(f($RS_Tramite,'nome')).', DISPONÍVEL PARA O RESPONSÁVEL PELO PAGAMENTO, INDICADO NO CAMPO ABAIXO.</b>');
    }
    ShowHTML('      </b></font></td>');
  }
  ShowHTML('    <tr valign="top">');
  if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') {
    ShowHTML('      <td><b><u>D</u>ata de homologação:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_homologacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_homologacao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_homologacao').'</td>');
  } elseif ($w_gera_contrato=='S') {
    ShowHTML('      <td><b><u>D</u>ata de homologação:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_homologacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_homologacao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_homologacao').'</td>');
    ShowHTML('      <td><b>Da<u>t</u>a de publicação no diário oficial:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_data_diario" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_diario.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_diario').'</td>');
    ShowHTML('      <td><b><U>P</U>ágina do diário oficial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="w_pagina_diario" size="4" maxlength="4" value="'.$w_pagina_diario.'"></td>');
  } else {
    ShowHTML('      <td><b><u>D</u>ata de autorização:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_homologacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_homologacao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_homologacao').'</td>');
    SelecaoPessoa('<u>R</u>esponsável pelo pagamento:','R','Selecione o responsável pelo pagamento ao fornecedor.',$w_executor,null,'w_executor','EXECUTORCO',null,2);
  }
  ShowHTML('    <tr><td colspan="3"><b>Nota d<u>e</u> conclusão:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Descreva o quanto o projeto atendeu aos resultados esperados.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  if ($w_indica_usuario=='S') {
    ShowHTML('    <tr>');
    SelecaoPessoa('<u>R</u>esponsável pelo recebimento:','R','Selecione o responsável pelo recebimento do material/serviço na relação.',$w_responsavel,null,'w_responsavel','USUARIOS',null,3);
  }
  ShowHTML('    <tr valign="top">');
  SelecaoLCSituacao('<u>S</u>ituação:','S','Selecione a situação do certame.',$w_situacao,null,'w_situacao','CONCLUSAO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_situacao\'; document.Form.submit();"');
  if (count($RS_Enq)>0) SelecaoLcModEnq('<u>A</u>rtigo:','A',null,$w_artigo,$w_modalidade,'w_artigo',null,null);
  if ($w_gera_contrato=='N') {
    MontaRadioNS('<b>Pagamento por fundo fixo?</b>',$w_fundo_fixo,'w_fundo_fixo');
  }

  // Se a modalidade não permite participantes, então não há indicação de vencedores
  if ($w_participantes>0) {
    ShowHTML('<tr><td colspan=3><b>Vencedores:</b><br>');
    $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,'PROPOSTA');
    if (count($RS1)>0) {
      $RS1 = SortArray($RS1,'ordem','asc','nome','asc','valor_unidade','asc');
      ShowHTML('    <tr><td colspan=3>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="1" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrAlternateBgColor.'" align="center">');
      ShowHTML('          <td><b>Ordem</td>');
      ShowHTML('          <td><b>Item</td>');
      ShowHTML('          <td><b>Qtd.</td>');
      ShowHTML('          <td><b>Fornecedor</td>');
      ShowHTML('          <td><b>Validade</td>');
      ShowHTML('          <td><b>$ Unitário</td>');
      ShowHTML('          <td><b>Vencedor</td>');
      ShowHTML('        </tr>');
      // Lista os registros selecionados para listagem
      $w_atual    = 0;
      $i          = 0;
      $w_exibe    = false;
      $w_item_lic = 0;
      $w_cor      = $conTrAlternateBgColor;
      foreach($RS1 as $row) { 
        if ($w_atual!=f($row,'sq_material')) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td align="center" rowspan='.f($row,'qtd_proposta').'>'.nvl(f($row,'ordem'),'&nbsp').'</td>');
          ShowHTML('        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
          ShowHTML('        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),0),'---').'</td>');
          $w_atual      = f($row,'sq_material');
          $i += 1;
        } else {
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        }
        if (nvl(f($row,'fornecedor'),'')=='') {
          // Se não há proposta para o item
          ShowHTML('        <td align="center" colspan="4">---<INPUT type="hidden" name="w_vencedor['.$i.']" value=""></td>');
        } else {
          ShowHTML('        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>');
          ShowHTML('        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>');
          ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'valor_unidade'),4),'---').'</td>');
          ShowHTML('          <INPUT type="hidden" name="w_chave_aux[]" value="'.f($row,'chave').'">');
          if ($w_conclusao=='S') {
            // Se a situação não exige indicador de vencedor
            ShowHTML('        <td align="center">---<INPUT type="hidden" name="w_vencedor['.$i.']" value=""></td>');
          } else {
            ShowHTML('        <td align="center" nowrap><INPUT class="str" type="radio" name="w_vencedor['.$i.']" value="'.f($row,'sq_item_fornecedor').'"'.((nvl($w_vencedor[$i],'')=='') ? '' : 'CHECKED').'></td>');
          }
        }
      } 
      ShowHTML('      </table>');
    }
  }
  ShowHTML('    <tr><td align="LEFT" colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=3><hr>');
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
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $sql = new db_getSolicCL; $RSM = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],f($RS_Menu,'sigla'),5,
          null,null,null,null,null,null,null,null,null,null,
          $p_solic,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
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
        ShowHTML('  alert("ATENÇÃO: não há permissão de escrita no diretório.\\n'.$conFilePhysical.$w_cliente.'");');
        ScriptClose();
      } else {
        if (!$handle = fopen($w_file,'w')) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: não foi possível abrir o arquivo para escrita.\\n'.$w_file.'");');
          ScriptClose();
        } else {
          if (!fwrite($handle, RelatorioViagem($p_solic))) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: não foi possível inserir o conteúdo do arquivo.\\n'.$w_file.'");');
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
    $w_html .= $crlf.'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DA PCD</td>';
    $w_html .= $crlf.'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html .= $crlf.'          <tr valign="top">';
    $w_html .= $crlf.'            <td>Proposto:<br><b>'.f($RSM,'nm_prop').'</b></td>';
    $w_html .= $crlf.'            <td>Unidade proponente:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
    $w_html .= $crlf.'          <tr valign="top">';
    $w_html .= $crlf.'            <td>Primeira saída:<br><b>'.FormataDataEdicao(f($RSM,'inicio')).' </b></td>';
    $w_html .= $crlf.'            <td>Último retorno:<br><b>'.FormataDataEdicao(f($RSM,'fim')).' </b></td>';
    $w_html .= $crlf.'          </table>';
    // Informações adicionais
    if (Nvl(f($RSM,'descricao'),'')>'') {
      if (Nvl(f($RSM,'descricao'),'')>'') $w_html .= $crlf.'      <tr><td>Descrição da PCD:<br><b>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
    } 
    $w_html .= $crlf.'    </table>';
    $w_html .= $crlf.'</tr>';

    //Recupera o último log
    $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
    foreach ($RS as $row) { $RS = $row; break; }
    $w_data_encaminhamento = f($RS,'phpdt_data');
    if ($p_tipo==2) {
      if ($w_sg_tramite=='EE') {
        // Recupera o número máximo de dias para entrega da prestação de contas
        $sql = new db_getPDParametro; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,null);
        foreach($RS1 as $row) { $RS1 = $row; break; }
        $w_dias_prest_contas = f($RS1,'dias_prestacao_contas');

        $w_html .= $crlf.'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ORIENTAÇÕES PARA PRESTAÇÃO DE CONTAS</td>';
        $w_html .= $crlf.'        <tr><td colspan="2" bgcolor="'.$w_TrBgColor.'">';
        $w_html .= $crlf.'          <p>Esta PCD foi autorizada. Você deve entregar os documentos abaixo na unidade proponente (<b>'.f($RSM,'nm_unidade_resp').')</b>';
        $w_html .= $crlf.'          <ul>';
        $w_html .= $crlf.'          <li>Relatório de viagem (anexo) preenchido;';
        $w_html .= $crlf.'          <li>Bilhetes de embarque;';
        $w_html .= $crlf.'          <li>Notas fiscais de taxi, restaurante e hotel.';
        $w_html .= $crlf.'          </ul>';
        $w_html .= $crlf.'          <p>A data limite para entrega é até o último dia útil antes de: <b>'.substr(FormataDataEdicao(addDays(f($RSM,'fim'),$w_dias_prest_contas),4),0,-10).' </b>; caso contrário, suas viagens serão automaticamente bloqueadas pelo sistema.';

        $w_html .= $crlf.'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA CONCESSÃO</td>';
        // Benefícios servidor
        $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$p_solic,'PDGERAL');
        if (count($RS1)>0) {
          $w_html .= $crlf.'        <tr><td colspan="2" align="center" bgcolor="'.$w_TrBgColor.'"><b>Benefícios recebidos pelo proposto</td>';
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
        $w_html .= $crlf.'        <tr><td colspan="2" align="center" bgcolor="'.$w_TrBgColor.'"><b>Dados da viagem/cálculo das diárias</td>';

        if ($j==1) {
          $w_html .= $crlf.'        </tr>';
          $w_html .= $crlf.'        </table></td></tr>';
          $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$p_solic,'PDGERAL');
          $w_html .= $crlf.'        <tr><td align="center" colspan="2"><TABLE WIDTH="100%" bgcolor="'.$w_TrBgColor.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
          $w_html .= $crlf.'        <tr><td><b>Nº do PTA/Ticket: </b>'.f($RS1,'PTA').'</td>';
          $w_html .= $crlf.'            <td><b>Data da emissão: </b>'.FormataDataEdicao(f($RS1,'emissao_bilhete')).'</td>';
          $w_html .= $crlf.'      </table>';
          $w_html .= $crlf.'    </td>';
        }
      } else {
        $w_html .= $crlf.'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
        $w_html .= $crlf.'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';
        $w_html .= $crlf.'          <tr><td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
        if (Nvl(f($RS,'despacho'),'')!='') {
          $w_html.=$crlf.'          <tr><td>Despacho:<br><b>'.CRLF2BR(f($RS,'despacho')).' </b></td>';
        }
        $w_html .= $crlf.'          </table>';
      }
    } 
    $w_html .= $crlf.'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
    $sql = new db_getCustomerSite; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
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
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
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
    $sql = new db_getTramiteResp; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null);
    if (!count($RS)<=0) {
      foreach($RS as $row) {
        $w_destinatarios .= f($row,'email').'|'.f($row,'nome').'; ';
     } 
    } 
    if(f($RSM,'st_sol')=='S') {
      // Recupera o e-mail do responsável
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    if(f($RSM,'st_prop')=='S') {
      // Recupera o e-mail do proposto
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,f($RSM,'sq_prop'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    // Executa o envio do e-mail
    if ($w_destinatarios>'') $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,$w_anexos);

    if ($w_sg_tramite=='EE') {
      // Remove o arquivo temporário
      if (!unlink($w_file)) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: não foi possível remover o arquivo temporário.\\n'.$w_file.'");');
        ScriptClose();
      }
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: não foi possível proceder o envio do e-mail.\\n'.$w_resultado.'");');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'CLLCCAD':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        // Se foi informado um código para o registro, impede sua duplicação
        if (nvl($_REQUEST['w_codigo'],'')!='') {
          $sql = new db_getCodigo; $RS = $sql->getInstanceOf($dbms,$w_cliente,'SOLICITACAO',$_REQUEST['w_codigo'],f($RS_Menu,'sq_menu'));
          foreach($RS as $row) {
            if (f($row,'chave')!=nvl($_REQUEST['w_chave'],0)) {
              ScriptOpen('JavaScript');
              ShowHTML('alert("ATENÇÃO: já existe outra licitação com o código '.$_REQUEST['w_codigo'].'!");');
              ScriptClose();
              retornaFormulario('w_codigo');
            }
          }
        }

        $SQL = new dml_putCLGeral; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_menu'],$_REQUEST['w_sq_unidade'],
          $_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],null,$_REQUEST['w_plano'],
          explodeArray($_REQUEST['w_objetivo']),$_REQUEST['w_sqcc'],$_REQUEST['w_solic_pai'],
          $_REQUEST['w_justificativa'],$_REQUEST['w_objeto'],$_REQUEST['w_observacao'],nvl($_REQUEST['w_inicio'],$_REQUEST['w_data_recebimento']),
          $_REQUEST['w_fim'],$_REQUEST['w_moeda'],$_REQUEST['w_valor'],$_REQUEST['w_codigo'],
          $_REQUEST['w_prioridade'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],'N',null,null,
          $_REQUEST['w_arp'],'N',null,$_REQUEST['w_financeiro'],$_REQUEST['w_rubrica'],$_REQUEST['w_lancamento'],
          null,&$w_chave_nova,$_REQUEST['w_copia']);        

        if ($O!='E') {
          $SQL = new dml_putCLDados; $SQL->getInstanceOf($dbms,'PROT',$w_chave_nova,null,$_REQUEST['w_sq_lcmodalidade'],$_REQUEST['w_numero_processo'],
            $_REQUEST['w_abertura'],$_REQUEST['w_envelope_1'],$_REQUEST['w_envelope_2'],$_REQUEST['w_envelope_3'],
            $_REQUEST['w_codigo'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
            $_REQUEST['w_protocolo'],null,null,null,null,null,null);
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
      break;
  case 'CLLCITEM':
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putCLSolicItem; 
      if ($O=='I') {
        for ($i=0; $i<=count($_POST['w_item_pedido'])-1; $i=$i+1) {
          if ($_REQUEST['w_item_pedido'][$i]>'') {
            $SQL->getInstanceOf($dbms,'V',$_REQUEST['w_chave_aux'],$_REQUEST['w_chave'],
                $_REQUEST['w_item_pedido'][$i],null,null,null,null,null,null,null);
          }
        } 
      } else {
        $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave_aux'],$_REQUEST['w_chave'],$_REQUEST['w_chave_aux2'],
            null,null,null,null,null,null,null);
      } 
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$w_menu.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
    break;
  case 'GCZITEM':
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      if ($O=='I' || $O=='A') {
        // Testa a existência do código
        $sql = new db_getMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,Nvl($_REQUEST['w_codigo'],''),null,null,null,null,null,null,null,null,null,null,null,null,null,null,'EXISTECOD');
        foreach($RS as $row) { $RS = $row; break; }
        if (f($RS,'existe')==0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("Código inexistente!");');
          ScriptClose(); 
          retornaFormulario('w_codigo');
          exit;
        }

        // Testa a existência do código
        $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$_REQUEST['w_chave'],null,null,null,null,$_REQUEST['w_codigo'],null,null,null,null,null,'ITEMARP');
        foreach($RS as $row) { $RS1 = $row; break; }
        if (count($RS)>0 && nvl(f($RS1,'chave'),$_REQUEST['w_chave_aux'])!=$_REQUEST['w_chave_aux']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("Código já cadastrado neste documento!");');
          ScriptClose(); 
          retornaFormulario('w_codigo');
          exit;
        }
      }
      
      $SQL = new dml_putCLARPItem; $SQL->getInstanceOf($dbms,$O,$w_cliente, $w_usuario, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_ordem'],
          $_REQUEST['w_codigo'], $_REQUEST['w_fabricante'], $_REQUEST['w_marca_modelo'], $_REQUEST['w_embalagem'], $_REQUEST['w_fator'],
          $_REQUEST['w_quantidade'], $_REQUEST['w_valor'], $_REQUEST['w_cancelado'], $_REQUEST['w_motivo'], $_REQUEST['w_origem']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }     
    break;
  case 'CLLCPRECO':
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      if ($O=='I' || $O=='A') {
        if ($_REQUEST['w_tipo_pessoa']==1) {
          // Verifica se já existe pessoa física com o CPF informado
          $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_pessoa'],null,nvl($_REQUEST['w_cpf'],'0'),null,null,$_REQUEST['w_tipo_pessoa'],null,null,null,null,null,null,null, null, null, null, null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe pessoa cadastrada com o CPF informado!\\nVerifique os dados.");');
            ScriptClose();
            retornaFormulario('w_cpf');
            exit;
          }
          // Verifica se já existe pessoa física com o mesmo nome. Se existir, é obrigatório informar o CPF.
          $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,null,nvl($_REQUEST['w_nome'],'0'),$_REQUEST['w_tipo_pessoa'],null,null,null,null,null,null,null, null, null, null, null,'EXISTE');
          if (count($RS)>0) {
            foreach ($RS as $row) {
              if (strlen(f($row,'nm_pessoa'))==strlen($_REQUEST['w_nome']) && (nvl(f($row,'identificador_primario'),'')=='' || nvl($_REQUEST['w_cpf'],'')=='')) {
                ScriptOpen('JavaScript');
                if (nvl(f($row,'identificador_primario'),'')=='') {
                  ShowHTML('  alert("Já existe pessoa cadastrada com o nome informado!\\nVerifique os dados e, se necessário, solicite ao gestor a alteração dos dados da pessoa já cadastrada.");');
                } else {
                  ShowHTML('  alert("Já existe pessoa cadastrada com o nome informado!\\nNeste caso é obrigatório informar o CPF.");');
                }
                ScriptClose();
                retornaFormulario('w_cpf');
                exit;
              }
            }
          }
        } else {
          // Verifica se já existe pessoa jurídica com o CNPJ informado
          $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,nvl($_REQUEST['w_cnpj'],'0'),null,$_REQUEST['w_tipo_pessoa'],null,null,null,null,null,null,null, null, null, null, null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Já existe pessoa jurídica cadastrada com o CNPJ informado!\\nVerifique os dados.");');
            ScriptClose();
            retornaFormulario('w_cnpj');
            exit;
          }
          // Verifica se já existe pessoa jurídica com o mesmo nome. Se existir, é obrigatório informar o CNPJ.
          $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,null,nvl($_REQUEST['w_nome'],'0'),$_REQUEST['w_tipo_pessoa'],null,null,null,null,null,null,null, null, null, null, null,'EXISTE');
          if (count($RS)>0) {
            foreach ($RS as $row) {
              if (strlen(f($row,'nm_pessoa'))==strlen($_REQUEST['w_nome']) && (nvl(f($row,'identificador_primario'),'')=='' || nvl($_REQUEST['w_cnpj'],'')=='')) {
                ScriptOpen('JavaScript');
                if (nvl(f($row,'identificador_primario'),'')=='') {
                  ShowHTML('  alert("Já existe pessoa cadastrada com o nome informado!\\nVerifique os dados e, se necessário, solicite ao gestor a alteração dos dados da pessoa já cadastrada.");');
                } else {
                  ShowHTML('  alert("Já existe pessoa cadastrada com o nome informado!\\nNeste caso é obrigatório informar o CNPJ.");');
                }
                ScriptClose();
                retornaFormulario('w_cnpj');
                exit;
              }
            }
          }
        }

        $SQL = new dml_putPessoa; $SQL->getInstanceOf($dbms,$_REQUEST['O'],$w_cliente,'FORNECEDOR',
            $_REQUEST['w_tipo_pessoa'],$_REQUEST['w_tipo_vinculo'],$_REQUEST['w_sq_pessoa'],$_REQUEST['w_cpf'],
            $_REQUEST['w_cnpj'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],
            $_REQUEST['w_sexo'],$_REQUEST['w_nascimento'],$_REQUEST['w_rg_numero'],
            $_REQUEST['w_rg_emissao'],$_REQUEST['w_rg_emissor'],$_REQUEST['w_passaporte_numero'],
            $_REQUEST['w_sq_pais_passaporte'],$_REQUEST['w_inscricao_estadual'],$_REQUEST['w_logradouro'],
            $_REQUEST['w_complemento'],$_REQUEST['w_bairro'],$_REQUEST['w_sq_cidade'],
            $_REQUEST['w_cep'],$_REQUEST['w_ddd'],$_REQUEST['w_nr_telefone'],
            $_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],$_REQUEST['w_email'],null,&$w_chave_nova);

        // Apaga todos os itens cotados dessa solicitação
        $SQL = new dml_putCLItemFornecedor; 
        $SQL->getInstanceOf($dbms,'E',$w_cliente,$_REQUEST['w_chave'],null,$w_chave_nova,null,null,null,null,null,null,null,null,null,$_REQUEST['w_pesquisa'],null);

        // Insere as cotaçoes e atualiza a tabela de materiais
        for ($i=0; $i<=count($_POST['w_chave_aux'])-1; $i=$i+1) {
          if (Nvl($_REQUEST['w_chave_aux'][$i],'')>'') {
            $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'][$i],$w_chave_nova,
              $_REQUEST['w_inicio'][$i],$_REQUEST['w_dias'][$i],$_REQUEST['w_valor'][$i],$_REQUEST['w_fabricante'][$i],
              $_REQUEST['w_marca_modelo'][$i],$_REQUEST['w_embalagem'][$i],$_REQUEST['w_fator'][$i],0,'N',$_REQUEST['w_pesquisa'],$_REQUEST['w_origem'][$i]);
          } 
        }
      } elseif ($O=='E') {
        // Apaga todos os itens cotados dessa solicitação
        $SQL = new dml_putCLItemFornecedor; $SQL->getInstanceOf($dbms1,'E',$w_cliente,$_REQUEST['w_chave'],null,$_REQUEST['w_fornecedor'],null,null,null,null,null,null,null,null,null,$_REQUEST['w_pesquisa'],null);
      }
      
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$w_menu.'&w_chave='.$_REQUEST['w_chave'].'&w_pesquisa='.$_REQUEST['w_pesquisa'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
    break;
  case 'CLLCDADOS':
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putCLDados; 
      $SQL->getInstanceOf($dbms,'DADOS',$_REQUEST['w_chave'],$_REQUEST['w_executor'],$_REQUEST['w_sq_lcmodalidade'],
        $_REQUEST['w_numero_processo'],$_REQUEST['w_abertura'],$_REQUEST['w_envelope_1'],$_REQUEST['w_envelope_2'],
        $_REQUEST['w_envelope_3'],$_REQUEST['w_numero_certame'],$_REQUEST['w_numero_ata'],$_REQUEST['w_tipo_reajuste'],
        $_REQUEST['w_indice_base'],$_REQUEST['w_sq_eoindicador'],nvl($_REQUEST['w_limite_variacao'],0),
        $_REQUEST['w_sq_lcfonte_recurso'],$_REQUEST['w_sq_espec_despesa'],$_REQUEST['w_sq_lcjulgamento'],$_REQUEST['w_sq_lcsituacao'],
        $_REQUEST['w_financeiro_unico'],null,null,null,null,$_REQUEST['w_dias'],null,$_REQUEST['w_protocolo'],$_REQUEST['w_inicio'],
        $_REQUEST['w_prioridade'],null,null,null,null);
      
      // Atualiza a ordem dos itens da solicitação
      for ($i=0; $i<=count($_POST['w_chave_aux'])-1; $i=$i+1) {
        if (Nvl($_REQUEST['w_chave_aux'][$i],'')>'') {
          $SQL->getInstanceOf($dbms,'ORDENACAO',$_REQUEST['w_chave_aux'][$i],null,null,null,null,null,null,null,null,null,null,
              null,null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_ordem'][$i],null,
              $_REQUEST['w_dias_item'][$i],null,null,null,null,null,$_REQUEST['w_quantidade'][$i],$_REQUEST['w_detalhamento'][$i]);
        } 
      }
      $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,'COMPRA');
      $RS = SortArray($RS,'ordem','asc','nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc');       
      $w_cont = 1;
      foreach($RS as $row) {
        $SQL = new dml_putCLDados; $SQL->getInstanceOf($dbms,'ORDENACAO',f($row,'chave'),null,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null,null,null,null,$w_cont,null,null,null,null,null,null,null,null,null);
        $w_cont+=1;
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&SG=CLLCCAD'.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit();
    }
    break;
  case 'CLLCPROT':
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putCLDados; $SQL->getInstanceOf($dbms,'PROT',$_REQUEST['w_chave'],null,$_REQUEST['w_sq_lcmodalidade'],
        $_REQUEST['w_numero_processo'],$_REQUEST['w_abertura'],$_REQUEST['w_envelope_1'],$_REQUEST['w_envelope_2'],$_REQUEST['w_envelope_3'],
        $_REQUEST['w_numero_certame'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
        $_REQUEST['w_protocolo'],null,null,null,null,null,null);

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&SG=CLLCCAD'.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit();
    }
    break;
  case 'CLLCSITUACAO':
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putCLDados; $SQL->getInstanceOf($dbms,'SITUACAO',$_REQUEST['w_chave'],null,null,null,
        $_REQUEST['w_abertura'],$_REQUEST['w_envelope_1'],$_REQUEST['w_envelope_2'],$_REQUEST['w_envelope_3'],
        null,null,null,null,null,null,null,null,null,$_REQUEST['w_sq_lcsituacao'],null,null,null,null,null,
        null,null,null,$_REQUEST['w_inicio'],$_REQUEST['w_prioridade'],null,null,null,null);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CLLCCAD'.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit();
    }
    break;    
  case 'CLLCANEXO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
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
              if ($_REQUEST['w_atual']>'') {
                $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
                foreach ($RS as $row) {
                  if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                  if (!(strpos(f($row,'caminho'),'.')===false)) {
                    $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,30);
                  } else {
                    $w_file = basename(f($row,'caminho'));
                  }
                }
              } else {
                $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
                }
              } 
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            }elseif(nvl($Field['name'],'')!=''){
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
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu 
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$w_menu.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;      
    case 'CLLCENVIO':
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
                ShowHTML('  alert("Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!");');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              }
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
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
                }
                $w_tamanho = $Field['size'];
                $w_tipo    = $Field['type'];
                $w_nome    = $Field['name'];
                if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
              } elseif(nvl($Field['name'],'')!='') {
                ScriptOpen('JavaScript');
                ShowHTML('  alert("Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!");');
                ScriptClose();
                retornaFormulario('w_caminho');
                exit();
              } 
            } 
            $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                $_REQUEST['w_tramite'],'N',$_REQUEST['w_observacao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!");');
            ScriptClose();
          } 
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } else {
          $sql = new db_getSolicCL;
          $RS = $sql->getInstanceOf($dbms, null, $w_usuario, $SG, 3, null, null, null, null, null, null, null, null, null,
                          null, $_REQUEST['w_chave'], null, null, null, null, null, null, null, null, null, null, null, null, null, null,
                          null, null, null,null,null,null,null,null);
          foreach ($RS as $row) {
            $RS = $row;
            break;
          }
          if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou o pedido para fase de execução!");');
            ScriptClose();
            exit();
          } else {
            $SQL = new dml_putSolicEnvio;
            if ($_REQUEST['w_envio'] == 'N') {
              $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'], null,
                      $_REQUEST['w_envio'], $_REQUEST['w_despacho'], null, null, null, null);
            } else {
              $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'], $_REQUEST['w_novo_tramite'],
                      $_REQUEST['w_envio'], $_REQUEST['w_despacho'], null, null, null, null);
            }
            //Rotina para gravação da imagem da versão da solicitacão no log.
            if ($_REQUEST['w_tramite'] != $_REQUEST['w_novo_tramite']) {
              $sql = new db_getTramiteData;
              $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite']);
              $w_sg_tramite = f($RS, 'sigla');
              if ($w_sg_tramite == 'CI') {
                $w_html = VisualCertame($_REQUEST['w_chave'], 'L', $w_usuario, null, '1');
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
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'LOTE':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
        ShowHTML('<b>Resultado do envio:</b>');
        for ($i = 1; $i < count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $w_tramite = $_POST['w_tramite'][$_POST['w_chave'][$i]];
            $w_chave   = $_POST['w_chave'][$i];
            $w_codigo  = $_POST['w_lista'][$_POST['w_chave'][$i]];

            // Recupera dados do trâmite atual
            $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
            $w_sg_tramite = f($RS,'sigla');
            $w_nm_tramite = f($RS,'nome');

            ShowHTML('<table border="1" width="100%"><tr valign="top"><td width="15%"><b>'.$w_codigo.'</b></td>');
            if ($_POST['w_envio']=='N') {

              if ($w_sg_tramite=='EE') {
                // Se não há fase posterior, não pode haver envio.
                echo '<td>Fase atual ja é a última.</td>';
              } else {
                // Verifica se a solicitação atende às exigências para envio
                $w_erro = ValidaCertame($w_cliente,$w_chave,$_POST['p_agrega'],null,null,null,$w_tramite);
                if (substr(Nvl($w_erro,'nulo'),0,1)=='0') {
                  echo '<td>'.substr($w_erro,1).'</td>';
                } else {
                  // Envia a solicitação
                  $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_POST['w_menu'],$w_chave,$w_usuario,
                    $w_tramite,null,$_POST['w_envio'],$_POST['w_despacho'],null,null,null,null);

                  // Envia e-mail comunicando o envio
                  SolicMail($w_chave,2);

                  echo '<td>Enviado</td>';
                }
              }
            } else {
              //Verifica a fase imediatamente anterior à atual.
              $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,$w_chave,'DEVFLUXO',null);
              $RS = SortArray($RS,'ordem','desc');
              foreach($RS as $row) { $RS = $row; break; }
              $w_novo_tramite = f($RS,'sq_siw_tramite');
              if (nvl($w_novo_tramite,'')=='') {
                echo '<td>Não há fase anterior à atual ("'.$w_nm_tramite.'").</td>';
              } else {
                // Devolve a solicitação
                $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_POST['w_menu'],$w_chave,$w_usuario,
                  $w_tramite,$w_novo_tramite,$_POST['w_envio'],$_POST['w_despacho'],null,null,null,null);

                // Envia e-mail comunicando a devolução
                SolicMail($w_chave,2);

                echo '<td>Devolvido</td>';
              }
            } 
            echo '</table>';
            flush();
          }
        }
        ShowHTML('<p>Clique <a class="HL" href="'.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'">aqui</a> para voltar à tela anterior.</p>');
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'CLLCCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,
                null,$_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,
                null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou o pedido para fase de execução!");');
          ScriptClose();
          exit();
        } else {
          $SQL = new dml_putCLDados; 
          $SQL->getInstanceOf($dbms,'CONCLUSAO',$_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,
              null,null,null,null,null,null,null,null,null,$_REQUEST['w_homologacao'],$_REQUEST['w_data_diario'],
              $_REQUEST['w_pagina_diario'],null,null,null,null,null,null,null,null,null,null);

          // Registra o vencedor de cada item
          for ($i=0; $i<=count($_POST['w_vencedor'])-1; $i=$i+1) {
            if (Nvl($_REQUEST['w_vencedor'][$i],'')>'') {
              $SQL->getInstanceOf($dbms,'VENCEDOR',$_REQUEST['w_vencedor'][$i],null,null,null,null,null,null,
                null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
            } 
          }

          // Registra a conclusão da solicitação
          $SQL = new dml_putSolicConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
              nvl($_REQUEST['w_executor'],$_SESSION['SQ_PESSOA']),$_REQUEST['w_nota_conclusao'],null,null,null,null,null,$_REQUEST['w_financeiro_menu'],
              $_REQUEST['w_financeiro_tramite'],$_REQUEST['w_responsavel'],$_REQUEST['w_situacao'],$_REQUEST['w_artigo'],
              $_REQUEST['w_fundo_fixo']);
          
          // Envia e-mail comunicando a conclusão
          SolicMail($_REQUEST['w_chave'],3);
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
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Bloco de dados não encontrado: '.$SG.'");');
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
  case 'INICIAL':           Inicial();        break;
  case 'GERAL':             Geral();          break;
  case 'ITENS':             Itens();          break;
  case 'ITENSCONTRATO':     ItensContrato();  break;
  case 'ANEXOS':            Anexos();         break;
  case 'PESQUISAPRECO':     PesquisaPreco();  break;
  case 'DADOSPREVIOS':      DadosPrevios();   break;
  case 'DADOSANALISE':      DadosAnalise();   break;
  case 'INFORMAR':          Informar();       break;
  case 'VISUAL':            Visual();         break;
  case 'EXCLUIR':           Excluir();        break;
  case 'ENVIO':             Encaminhamento(); break;
  case 'ANOTACAO':          Anotar();         break;
  case 'CONCLUIR':          Concluir();       break;
  case 'GRAVA':             Grava();          break; 
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

