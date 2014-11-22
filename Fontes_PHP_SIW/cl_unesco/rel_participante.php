<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_exec.php');
//include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
//include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
//include_once($w_dir_volta.'classes/sp/db_getCcData.php');
//include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
//include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
//include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
//include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
//include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
//include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
//include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
//include_once($w_dir_volta.'classes/sp/db_getTramiteResp.php');
//include_once($w_dir_volta.'classes/sp/db_getTramiteSolic.php');
//include_once($w_dir_volta.'classes/sp/db_getCodigo.php');
//include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
//include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
//include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
//include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
//include_once($w_dir_volta.'classes/sp/db_getCLFinanceiro.php');
//include_once($w_dir_volta.'classes/sp/db_getSolicObjetivo.php');
//include_once($w_dir_volta.'classes/sp/db_getMatServ.php');
//include_once($w_dir_volta.'classes/sp/db_getCLSolicItem.php');
//include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
//include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
//include_once($w_dir_volta.'funcoes/selecaoFontePesquisa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
//include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
//include_once($w_dir_volta.'funcoes/selecaoPlanoEstrategico.php');
//include_once($w_dir_volta.'funcoes/selecaoObjetivoEstrategico.php');
//include_once($w_dir_volta.'funcoes/selecaoServico.php');
//include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
//include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
//include_once($w_dir_volta.'funcoes/selecaoSolic.php');
//include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
//include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
//include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
//include_once($w_dir_volta.'funcoes/selecaoMatServ.php');
//include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');
//include_once($w_dir_volta.'funcoes/selecaoCC.php');
//include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
//include_once($w_dir_volta.'funcoes/selecaoSexo.php');
//include_once($w_dir_volta.'funcoes/selecaoPais.php');
//include_once($w_dir_volta.'funcoes/selecaoEstado.php');
//include_once($w_dir_volta.'funcoes/selecaoCidade.php');
//include_once($w_dir_volta.'funcoes/selecaoLCModalidade.php');
//include_once($w_dir_volta.'funcoes/selecaoTipoReajuste.php');
//include_once($w_dir_volta.'funcoes/selecaoIndicador.php');
//include_once($w_dir_volta.'funcoes/selecaoLCModEnq.php');
//include_once($w_dir_volta.'funcoes/selecaoLCFonteRecurso.php');
//include_once($w_dir_volta.'funcoes/selecaoCTEspecificacao.php');
//include_once($w_dir_volta.'funcoes/selecaoLCJulgamento.php');
//include_once($w_dir_volta.'funcoes/selecaoLCSituacao.php');
//include_once($w_dir_volta.'funcoes/selecaoMoeda.php');
include_once($w_dir_volta.'mod_cl/visualcertame.php');
include_once($w_dir_volta.'mod_cl/validacertame.php');

// =========================================================================
//  /rel_participante.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório de fornecedores participantes de licitações
// Mail     : alex@sbpi.com.br
// Criacao  : 19/11/2014, 12:00
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
$w_pagina       = 'rel_participante.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'cl_unesco/';
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

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

if ($O=='') $O='P';

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
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
            $p_acao_ppa, null, $p_empenho, $p_servico, $p_moeda);
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
                  $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                  $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                  $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                  $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
                  $p_acao_ppa, null, $p_empenho, $p_servico, $p_moeda);
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
 
    $SQL = "select --case when g.cgccpf is not null and g.cgccpf <> e1.cnpj then 'X' end erro," .$crlf.
           "       codigo2numero(a.codigo_interno) ord_codigo_interno, ".$crlf.
           "       a.codigo_interno, d.fornecedor, d.vencedor, e1.cnpj lic_cnpj, e.nome nm_fornecedor," .$crlf.
           //"       g.cgccpf cnpj_fabs, g.nome fb_fornecedor," .$crlf.
           //"       g.automatico_sa, g.certificacao, g.ds_sa," .$crlf.
           "       a.descricao objeto, f.nome nm_material," .$crlf.
           "       c.quantidade_autorizada qtd, d.valor_unidade unitario, d.valor_item valor" .$crlf.
           "  from siw_solicitacao                        a" .$crlf.
           "       inner         join siw_menu           a1 on (a.sq_menu             = a1.sq_menu and" .$crlf.
           "                                                        a1.sigla              = 'CLLCCAD'" .$crlf.
           "                                                       )" .$crlf.
           "       inner         join cl_solicitacao      b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)" .$crlf.
           "         inner       join cl_solicitacao_item c on (b.sq_siw_solicitacao  = c.sq_siw_solicitacao)" .$crlf.
           "           inner     join cl_item_fornecedor  d on (c.sq_solicitacao_item = d.sq_solicitacao_item)" .$crlf.
           "             inner   join co_pessoa           e on (d.fornecedor          = e.sq_pessoa)" .$crlf.
           "               inner join co_pessoa_juridica e1 on (e.sq_pessoa           = e1.sq_pessoa)" .$crlf.
           "           inner     join cl_material         f on (c.sq_material         = f.sq_material)" .$crlf.
           //"       left          join (select w.automatico_sa, w.certificacao, x.handle, x.cgccpf, x.nome, y.ds_sa" .$crlf.
           //"                             from corporativo.un_solicitacaoadministrativa w" .$crlf.
           //"                                  inner join corporativo.gn_pessoas        x on (w.contratado   = x.handle)" .$crlf.
           //"                                  inner join corporativo.vw_permissao_web  y on (w.certificacao = y.ordem)" .$crlf.
           //"                          )                   g on (a.codigo_externo      = g.automatico_sa)" .$crlf.
           " where d.pesquisa = 'N'";
    $SQL = "select a.sq_menu,            a.sq_modulo,                   a.nome," .$crlf.
           "       a.tramite,            a.ultimo_nivel,                a.p1," .$crlf.
           "       a.p2,                 a.p3,                          a.p4," .$crlf.
           "       a.sigla,              a.descentralizado,             a.externo," .$crlf.
           "       a.acesso_geral,       a.sq_unid_executora,           a1.nome as nm_modulo," .$crlf.
           "       a1.sigla as sg_modulo," .$crlf.
           "       b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante," .$crlf.
           "       b.cadastrador,        b.executor,                    b.descricao as objeto," .$crlf.
           "       b.justificativa,      b.inicio,                      b.fim," .$crlf.
           "       b.inclusao,           b.ultima_alteracao,            b.conclusao," .$crlf.
           "       b.valor,              b.palavra_chave,               b.sq_solic_pai," .$crlf.
           "       b.sq_unidade,         b.sq_cidade_origem," .$crlf.
           "       coalesce(d.numero_certame, b.codigo_interno, to_char(b.sq_siw_solicitacao)) as codigo_interno," .$crlf.
           "       unesco.codigo2numero(coalesce(d.numero_certame, b.codigo_interno, to_char(b.sq_siw_solicitacao))) as ord_codigo_interno," .$crlf.
           "       b.codigo_externo,     b.titulo,                      unesco.acentos(b.titulo) as ac_titulo," .$crlf.
           "       b.sq_plano,           b.sq_cc,                       b.observacao," .$crlf.
           "       b.protocolo_siw,      b.recebedor," .$crlf.
           "       to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao," .$crlf.
           "       to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao," .$crlf.
           "       case when b.sq_solic_pai is null" .$crlf.
           "            then case when b.sq_plano is null" .$crlf.
           "                      then case when n.sq_cc is null" .$crlf.
           "                                then '???'" .$crlf.
           "                                else 'Classif: '||n.nome" .$crlf.
           "                           end" .$crlf.
           "                      else 'Plano: '||b3.titulo" .$crlf.
           "                 end" .$crlf.
           "            else unesco.dados_solic(b.sq_solic_pai)" .$crlf.
           "       end as dados_pai," .$crlf.
           "       b1.nome as nm_tramite,   b1.ordem as or_tramite," .$crlf.
           "       b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail," .$crlf.
           "       b2.acesso," .$crlf.
           "       b6.sq_moeda,             b6.codigo cd_moeda,            b6.nome nm_moeda," .$crlf.
           "       b6.sigla sg_moeda,       b6.simbolo sb_moeda,           b6.ativo at_moeda," .$crlf.
           "       b7.sq_moeda sq_moeda_alt, b7.codigo cd_moeda_alt,       b7.nome nm_moeda_alt," .$crlf.
           "       b7.sigla sg_moeda_alt,   b7.simbolo sb_moeda_alt,       b7.ativo at_moeda_alt," .$crlf.
           "       case when b6.sq_moeda is not null and b7.sq_moeda is not null" .$crlf.
           "            then unesco.conversao(a.sq_pessoa, coalesce(b.inicio, b.inclusao), b6.sq_moeda, b7.sq_moeda, b.valor, 'V')" .$crlf.
           "            else 0" .$crlf.
           "       end valor_alt," .$crlf.
           "       c.informal,                c.sq_tipo_unidade as tp_exec," .$crlf.
           "       c.nome as nm_unidade_exec, c.informal as informal_exec," .$crlf.
           "       c.vinculada as vinc_exec,c.adm_central as adm_exec," .$crlf.
           "       a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec," .$crlf.
           "       c.vinculada,             c.adm_central," .$crlf.
           "       d.sq_especie_documento,  d.sq_eoindicador," .$crlf.
           "       d.sq_eoindicador,     d.sq_lcfonte_recurso,          d.sq_lcmodalidade," .$crlf.
           "       d.sq_lcjulgamento,    d.sq_lcsituacao,               d.sq_unidade as sq_unidade_pai," .$crlf.
           "       d.numero_original,    d.data_recebimento," .$crlf.
           "       d.indice_base,        d.tipo_reajuste," .$crlf.
           "       d.limite_variacao,    d.data_homologacao,            d.data_diario_oficial," .$crlf.
           "       d.pagina_diario_oficial, d.financeiro_unico,         d.decisao_judicial," .$crlf.
           "       d.numero_ata,         d.numero_certame,              d.arp," .$crlf.
           "       d.prioridade,         d.aviso_prox_conc,             d.dias_aviso," .$crlf.
           "       d.sq_especificacao_despesa, d.interno,               d.dias_validade_proposta," .$crlf.
           "       d.sq_financeiro,      d.nota_conclusao,              d.data_abertura," .$crlf.
           "       d.envelope_1,         d.envelope_2,                  d.envelope_3," .$crlf.
           "       to_char(d.data_abertura,'dd/mm/yyyy, hh24:mi:ss') phpdt_data_abertura," .$crlf.
           "       to_char(d.envelope_1,'dd/mm/yyyy, hh24:mi:ss')    phpdt_envelope_1," .$crlf.
           "       to_char(d.envelope_2,'dd/mm/yyyy, hh24:mi:ss')    phpdt_envelope_2," .$crlf.
           "       to_char(d.envelope_3,'dd/mm/yyyy, hh24:mi:ss')    phpdt_envelope_3," .$crlf.
           "       d.fundo_fixo,         d.sq_modalidade_artigo,        coalesce(d.data_homologacao, b.conclusao) as data_autorizacao," .$crlf.
           "       case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade," .$crlf.
           "       case d.tipo_reajuste when 0 then 'Não permite' when 1 then 'Com índice' else 'Sem índice' end as nm_tipo_reajuste," .$crlf.
           "       case when b.protocolo_siw is null" .$crlf.
           "            then d.processo" .$crlf.
           "            else to_char(b5.numero_documento)||'/'||substr(to_char(b5.ano),3)" .$crlf.
           "       end as processo," .$crlf.
           "       case when b5.prefixo is null" .$crlf.
           "            then null" .$crlf.
           "            else to_char(b5.prefixo)||'.'||substr(1000000+to_char(b5.numero_documento),2,6)||'/'||to_char(b5.ano)||'-'||substr(100+to_char(b5.digito),2,2)" .$crlf.
           "       end as protocolo_completo," .$crlf.
           "       cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso," .$crlf.
           "       d4.nome as nm_lcmodalidade, d4.certame, d4.minimo_pesquisas, d4.minimo_participantes, d4.minimo_propostas_validas," .$crlf.
           "       d4.enquadramento_inicial, d4.enquadramento_final, d4.sigla as sg_lcmodalidade," .$crlf.
           "       d4.descricao as ds_lcmodalidade, d4.gera_contrato," .$crlf.
           "       d41.sigla as sg_modalidade_artigo, d41.descricao as ds_modalidade_artigo," .$crlf.
           "       d4.nome||' - '||d41.sigla as nm_enquadramento," .$crlf.
           "       d5.nome as nm_lcjulgamento, d5.item tipo_julgamento," .$crlf.
           "       d6.nome as nm_lcsituacao, d6.conclui_sem_proposta, d6.tela_exibicao, d6.codigo_externo cd_situacao," .$crlf.
           "       d7.nome as nm_especie_documento," .$crlf.
           "       e.sq_tipo_unidade,    e.nome as nm_unidade_resp,        e.informal as informal_resp," .$crlf.
           "       e.vinculada as vinc_resp,e.adm_central as adm_resp,     e.sigla sg_unidade_resp," .$crlf.
           "       e.ordem or_unidade_resp," .$crlf.
           "       e1.sq_pessoa as titular, e2.sq_pessoa as substituto," .$crlf.
           "       f.sq_pais,            f.sq_regiao,                   f.co_uf," .$crlf.
           "       m.sq_menu as sq_menu_pai," .$crlf.
           "       n.sq_cc,              n.nome as nm_cc,                  n.sigla as sg_cc," .$crlf.
           "       o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind," .$crlf.
           "       p.nome_resumido as nm_exec,  p.nome_resumido_ind as nm_exec_ind," .$crlf.
           "       q.nome_resumido as nm_recebedor,  p.nome_resumido_ind as nm_recebedor_ind," .$crlf.
//           "       s.cgccpf cnpj_fabs, s.nome fb_fornecedor," .$crlf.
//           "       s.automatico_sa, s.certificacao, s.ds_sa," .$crlf.
//           "       case when s.cgccpf is not null and s.cgccpf <> d31.cnpj and d2.vencedor = 'S' then 'X' end erro," .$crlf.
           "       d2.fornecedor, d2.vencedor, d31.cnpj lic_cnpj, d3.nome nm_fornecedor," .$crlf.
           "       d11.nome nm_material," .$crlf.
           "       d1.quantidade_autorizada qtd, d2.valor_unidade vl_unit_item, d2.valor_item vl_total_item" .$crlf.
           "  from unesco.siw_menu                                        a" .$crlf.
           "       inner             join unesco.siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)" .$crlf.
           "       inner             join unesco.eo_unidade               c  on (a.sq_unid_executora        = c.sq_unidade)" .$crlf.
           "       inner             join unesco.siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu)" .$crlf.
           "          inner          join (select x.sq_siw_solicitacao, unesco.acesso(x.sq_siw_solicitacao,".$w_usuario.",null) as acesso" .$crlf.
           "                                 from unesco.siw_solicitacao             x" .$crlf.
           "                                      inner  join unesco.cl_solicitacao x1 on (x.sq_siw_solicitacao = x1.sq_siw_solicitacao)" .$crlf.
           "                                      inner join unesco.siw_menu         y on (x.sq_menu        = y.sq_menu and" .$crlf.
           "                                                                        y.sq_menu        = coalesce(".$P2.", y.sq_menu)" .$crlf.
           "                                                                       )" .$crlf.
           "                              )                            b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)" .$crlf.
           "          inner          join unesco.siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)" .$crlf.
           "          inner          join unesco.cl_solicitacao           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)" .$crlf.
           "          inner          join unesco.cl_solicitacao_item      d1 on (b.sq_siw_solicitacao       = d1.sq_siw_solicitacao)" .$crlf.
           "            inner        join unesco.cl_item_fornecedor       d2 on (d1.sq_solicitacao_item     = d2.sq_solicitacao_item and" .$crlf.
           "                                                                  d2.pesquisa                = 'N'" .$crlf.
           "                                                                 )" .$crlf.
           "              inner      join unesco.co_pessoa                d3 on (d2.fornecedor              = d3.sq_pessoa)" .$crlf.
           "                inner    join unesco.co_pessoa_juridica      d31 on (d3.sq_pessoa               = d31.sq_pessoa)" .$crlf.
           "            inner        join unesco.cl_material             d11 on (d1.sq_material             = d11.sq_material)" .$crlf.
           "          inner          join unesco.eo_unidade               e  on (b.sq_unidade               = e.sq_unidade)" .$crlf.
           "          inner          join unesco.co_cidade                f  on (b.sq_cidade_origem         = f.sq_cidade)" .$crlf.
           "          left           join unesco.pe_plano                 b3 on (b.sq_plano                 = b3.sq_plano)" .$crlf.
           "          left           join unesco.pj_projeto               b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)" .$crlf.
           "          left           join unesco.pa_documento             b5 on (b.protocolo_siw            = b5.sq_siw_solicitacao)" .$crlf.
           "          left           join unesco.co_moeda                 b6 on (b.sq_moeda                 = b6.sq_moeda)" .$crlf.
           "            left         join unesco.co_moeda                 b7 on (b6.ativo                   = b7.ativo and" .$crlf.
           "                                                              b7.sigla                   = case coalesce(b6.sigla,'-')" .$crlf.
           "                                                                                                when 'USD' then 'BRL'" .$crlf.
           "                                                                                                when 'BRL' then 'USD'" .$crlf.
           "                                                                                                else '-'" .$crlf.
           "                                                                                           end" .$crlf.
           "                                                             )" .$crlf.
           "            left         join unesco.lc_modalidade            d4 on (d.sq_lcmodalidade          = d4.sq_lcmodalidade)" .$crlf.
           "            left         join unesco.lc_modalidade_artigo    d41 on (d.sq_modalidade_artigo     = d41.sq_modalidade_artigo)" .$crlf.
           "            left         join unesco.lc_julgamento            d5 on (d.sq_lcjulgamento          = d5.sq_lcjulgamento)" .$crlf.
           "            left         join unesco.lc_situacao              d6 on (d.sq_lcsituacao            = d6.sq_lcsituacao)" .$crlf.
           "            left         join unesco.pa_especie_documento     d7 on (d.sq_especie_documento     = d7.sq_especie_documento)" .$crlf.
           "              left       join unesco.eo_unidade_resp          e1 on (e.sq_unidade               = e1.sq_unidade and" .$crlf.
           "                                                              e1.tipo_respons            = 'T'           and" .$crlf.
           "                                                              e1.fim                     is null" .$crlf.
           "                                                             )" .$crlf.
           "              left       join unesco.eo_unidade_resp          e2 on (e.sq_unidade               = e2.sq_unidade and" .$crlf.
           "                                                              e2.tipo_respons            = 'S'           and" .$crlf.
           "                                                              e2.fim                     is null" .$crlf.
           "                                                             )" .$crlf.
           "          left           join unesco.siw_solicitacao          m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)" .$crlf.
           "          left           join unesco.ct_cc                    n  on (b.sq_cc                    = n.sq_cc)" .$crlf.
           "          left           join unesco.co_pessoa                o  on (b.solicitante              = o.sq_pessoa)" .$crlf.
           "          left           join unesco.co_pessoa                p  on (b.executor                 = p.sq_pessoa)" .$crlf.
           "          left           join unesco.co_pessoa                q  on (b.recebedor                = q.sq_pessoa)" .$crlf.
           "          left           join unesco.eo_unidade_resp          a3 on (c.sq_unidade               = a3.sq_unidade and" .$crlf.
           "                                                              a3.tipo_respons            = 'T'           and" .$crlf.
           "                                                              a3.fim                     is null" .$crlf.
           "                                                             )" .$crlf.
           "          left           join unesco.eo_unidade_resp          a4 on (c.sq_unidade               = a4.sq_unidade and" .$crlf.
           "                                                              a4.tipo_respons            = 'S'           and" .$crlf.
           "                                                              a4.fim                     is null" .$crlf.
           "                                                             )" .$crlf.
//           "          left          join (select w.automatico_sa, w.certificacao, x.handle, x.cgccpf, x.nome, y.ds_sa" .$crlf.
//           "                                from corporativo.un_solicitacaoadministrativa w" .$crlf.
//           "                                     inner join corporativo.gn_pessoas        x on (w.contratado   = x.handle)" .$crlf.
//           "                                     inner join corporativo.vw_permissao_web  y on (w.certificacao = y.ordem)" .$crlf.
//           "                             )                         s  on (b.codigo_externo      = s.automatico_sa)" .$crlf.
           " where a.sq_menu              = ".$P2.$crlf.
           "   and b1.sigla               <> 'CA'";
    if (nvl($p_moeda,'')!='')       $SQL.="   and b.sq_moeda             = ".$p_moeda.$crlf;
    if (nvl($p_sq_acao_ppa,'')!='') $SQL.="   and d.sq_modalidade_artigo = ".$p_sq_acao_ppa.$crlf;
    if (nvl($p_sq_orprior,'')!='')  $SQL.="   and b.sq_plano             = ".$p_sq_orprior.$crlf;
    if (nvl($p_pais,'')!='')        $SQL.="   and 0 < (select count(*) from unesco.cl_solicitacao_item x inner join unesco.cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and y.sq_tipo_material in (select sq_tipo_material from unesco.cl_tipo_material connect by prior sq_tipo_material = sq_tipo_pai start with sq_tipo_material=".$p_pais."))".$crlf;
    if (nvl($p_regiao,'')!='')      $SQL.="   and d.processo             like '%".$p_regiao."%'))".$crlf;
    if (nvl($p_cidade,'')!='')      $SQL.="   and d.processo             like '%".$p_cidade."%'".$crlf;
    if (nvl($p_usu_resp,'')!='')    $SQL.="   and d4.sq_lcmodalidade     = ".$p_usu_resp.$crlf;
    if (nvl($p_uorg_resp,'')!='')   $SQL.="   and (b1.sigla <> 'AT' and e.sq_unidade = ".$p_uorg_resp.")".$crlf;
    if (nvl($p_sqcc,'')!='')        $SQL.="   and b.sq_cc                = ".$p_sqcc.$crlf;
    if (nvl($p_projeto,'')!='')     $SQL.="   and b.sq_solic_pai         = ".$p_projeto.$crlf;
    if (nvl($p_processo,'')!='')    $SQL.="   and (('".$p_processo."'    = 'CLASSIF' and b.sq_cc is not null) or ('".$p_processo."' <> 'CLASSIF' and m.sq_menu = to_number(".$p_processo.")))".$crlf;
    if (nvl($p_uf,'')!='')          $SQL.="   and d6.sq_lcsituacao       = ".$p_uf.$crlf;
    if (nvl($p_proponente,'')!='')  $SQL.="   and 0 < (select count(*) from unesco.cl_solicitacao_item x inner join unesco.cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and unesco.acentos(y.nome,null) like '%'||unesco.acentos('".$p_proponente."',null)||'%')".$crlf;
    if (nvl($p_assunto,'')!='')     $SQL.="   and unesco.acentos(b.codigo_externo,null) like '%'||unesco.acentos('".$p_assunto."',null)||'%'".$crlf;
    if (nvl($p_palavra,'')!='')     $SQL.="   and unesco.acentos(d.numero_certame,null) like '%'||unesco.acentos('".$p_palavra."',null)||'%'".$crlf;
    if (nvl($p_empenho,'')!='')     $SQL.="   and (unesco.acentos(b.codigo_interno,null) like '%'||unesco.acentos('".$p_empenho."',null)||'%' or unesco.acentos(d.numero_certame,null) like '%'||unesco.acentos('".$p_empenho."',null)||'%')".$crlf;
    if (nvl($p_prioridade,'')!='')  $SQL.="   and b.executor             = ".$p_prioridade.$crlf;
    if (nvl($p_ativo,'')!='')       $SQL.="   and d.decisao_judicial = '".$p_ativo."'".$crlf;
    if (nvl($p_prazo,'')!='')       $SQL.="   and (d6.sq_lcsituacao is not null and upper(d6.nome) not like '%CANCELADA%')".$crlf;
    if (nvl($p_ini_i,'')!='')       $SQL.="   ((trunc(d.data_abertura) between to_date(".$p_ini_i.",'dd/mm/yyyy') and to_date(".$p_ini_f.",'dd/mm/yyyy') or".$crlf.
                                          "     trunc(d.envelope_1)    between to_date(".$p_ini_i.",'dd/mm/yyyy') and to_date(".$p_ini_f.",'dd/mm/yyyy') or".$crlf.
                                          "     trunc(d.envelope_2)    between to_date(".$p_ini_i.",'dd/mm/yyyy') and to_date(".$p_ini_f.",'dd/mm/yyyy') or".$crlf.
                                          "     trunc(d.envelope_3)    between to_date(".$p_ini_i.",'dd/mm/yyyy')_i and to_date(".$p_ini_f.",'dd/mm/yyyy') or".$crlf.
                                          "     (instr($p_restricao,'CLCAPA') > 0 and -- Tratamento para consulta especial da UNESCO".$crlf.
                                          "      (coalesce(d.data_homologacao, b.conclusao) between to_date(".$p_ini_i.",'dd/mm/yyyy') and to_date(".$p_ini_f.",'dd/mm/yyyy') or".$crlf.
                                          "       (b1.sigla in ('EA','EE') and d.data_abertura < to_date(".$p_ini_f.",'dd/mm/yyyy'))".$crlf.
                                          "      )".$crlf.
                                          "     )".$crlf.
                                          "    )".$crlf.
                                          "   )".$crlf;
    if (nvl($p_fim_i,'')!='')       $SQL.="   and coalesce(d.data_homologacao, b.conclusao) between to_date(".$p_fim_i.",'dd/mm/yyyy') and to_date(".$p_fim_f.",'dd/mm/yyyy')))".$crlf;
    if (nvl($p_atraso,'')!='')      $SQL.="   and d6.sq_lcsituacao is not null and upper(d6.nome) like '%CANCELADA%'".$crlf;
    if (nvl($p_unidade,'')!='')     $SQL.="   and b.sq_unidade           = ".$p_unidade.$crlf;
    if (nvl($p_solicitante,'')!='') $SQL.="   and b.solicitante          = ".$p_solicitante;
    $sql = new db_exec; $RS = $sql->getInstanceOf($dbms,$SQL,$recordcount);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'ord_codigo_interno','asc','vencedor','desc', 'vl_total_item', 'asc');
    } else {
      $RS = SortArray($RS,'ord_codigo_interno','asc','vencedor','desc', 'vl_total_item', 'asc');
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
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('CP',$O)!==false) {
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
      if ((strpos(upper($R),'GR_'))===false && $w_embed!='WORD') {
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      }
    }
    ShowHTML('    <td align="right"><b>'.exportaOffice().'Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan = 0;
    if ($w_embed!='WORD') {
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Vencedor','vencedor').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('CNPJ','lic_cnpj').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Fornecedor','nm_fornecedor').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Código','ord_codigo_interno').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Objeto','objeto').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Item','nm_material').'</td>');
//      if ($_SESSION['INTERNO']=='S') { $colspan++; ShowHTML ('          <td><b>'.LinkOrdena('Vinculação','dados_pai').'</td>'); }
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Modalidade','sg_lcmodalidade').'</td>');
//      if ($w_pa=='S' || $w_segmento=='Público') { $colspan++; ShowHTML ('          <td><b>'.LinkOrdena('Processo','processo').'</td>'); }
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Solicitante','sg_unidade_resp').'</td>');
//      //$colspan++; ShowHTML('          <td><b>'.LinkOrdena('Data limite','fim').'</td>');
//      if ($P1!=1 || $w_pede_valor_pedido=='S') {
        ShowHTML('          <td><b>'.LinkOrdena('Valor'.(($w_sb_moeda>'') ? ' ('.$w_sb_moeda.')' : ''),'valor').'</td>');
//        if ($P1!=1) {
//          ShowHTML('          <td><b>'.LinkOrdena('Situação','nm_lcsituacao').'</td>');
//          if ($w_embed!='WORD') ShowHTML('          <td class="remover" width="1">&nbsp;</td>');
//          ShowHTML('          <td><b>'.LinkOrdena('Executor','nm_exec').'</td>');
//        }
//        if ($P1>2) {
//          if ($w_cliente==6881) ShowHTML('          <td><b>'.LinkOrdena('Código externo','codigo_externo').'</td>');
//          else                  ShowHTML('          <td><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
//        }
//      }      
      ShowHTML('        </tr>');
    } else {
      $colspan++; ShowHTML('          <td><b>Código</td>');
      $colspan++; ShowHTML('          <td><b>Objeto</td>');
//      if ($_SESSION['INTERNO']=='S') { $colspan++; ShowHTML ('          <td><b>Vinculação</td>'); }
//      $colspan++; ShowHTML('          <td><b>Modalidade</td>');
//      if ($w_pa=='S' || $w_segmento=='Público') { $colspan++; ShowHTML ('          <td><b>Processo</td>'); }
//      $colspan++; ShowHTML('          <td><b>Solicitante</td>');
//      //$colspan++; ShowHTML('          <td><b>Data limite</td>');
//      if ($P1!=1 || $w_pede_valor_pedido=='S') {
        ShowHTML('          <td><b>Valor'.(($w_sb_moeda>'') ? ' ('.$w_sb_moeda.')' : '').'</td>');
//        if ($P1!=1) {
//          ShowHTML('          <td><b>Situação</td>');
//          if ($w_embed!='WORD') ShowHTML('          <td class="remover" width="1">&nbsp;</td>');
//          ShowHTML('          <td><b>Executor</td>');
//        }
//        if ($P1>2) {
//          if ($w_cliente==6881) ShowHTML('          <td><b>Código externo</td>');
//          else                  ShowHTML('          <td><b>Fase atual</td>');
//        }
//      }
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
        ShowHTML('        <td align="center" nowrap>'.((f($row,'vencedor')=='S') ? 'X' : '').'</td>');
        ShowHTML('        <td align="center" nowrap>'.f($row,'lic_cnpj').'</td>');
        ShowHTML('        <td>' . ExibePessoa('../', $w_cliente, f($row, 'fornecedor'), $TP, f($row, 'nm_fornecedor')) . '</td>');
        ShowHTML('        <td width="1%" nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),null,null,f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        if ($w_embed!='WORD'){
          ShowHTML('        <A class="HL" HREF="mod_cl/certame.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
        } else {
          ShowHTML('&nbsp;'.f($row,'codigo_interno').'&nbsp;');
        }
        ShowHTML('        <td>'.f($row,'objeto').'</td>');
        ShowHTML('        <td>'.f($row,'nm_material').'</td>');
//        if ($_SESSION['INTERNO']=='S') {
//          if ($w_cliente==6881)                    ShowHTML('        <td>'.f($row,'sg_cc').'</td>');
//          elseif (Nvl(f($row,'dados_pai'),'')!='') ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai')).'</td>');
//          else                                     ShowHTML('        <td>---</td>');
//        } 
//        ShowHTML('        <td title="'.f($row,'nm_lcmodalidade').'" align="center">'.f($row,'sg_lcmodalidade').'</td>');
//        if ($w_pa=='S') {
//          if ($w_embed!='WORD' && nvl(f($row,'protocolo_siw'),'')!='') {
//            ShowHTML('        <td align="center" nowrap><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($row,'processo').'&nbsp;</a>'.'</td>');
//          } else {
//            ShowHTML('        <td align="center" nowrap>'.nvl(f($row,'processo'),'&nbsp;').'</td>');
//          }
//        } elseif ($w_segmento=='Público') {
//          ShowHTML('        <td align="center">'.f($row,'processo').'</td>');
//        }
//        ShowHTML('        <td width="1%" nowrap>&nbsp;'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade'),$TP).'&nbsp;</td>');
//        if ($P1!=1 || $w_pede_valor_pedido=='S') {
          if (f($row,'vencedor')=='S') $w_parcial[f($row,'sb_moeda')] = nvl($w_parcial[f($row,'sb_moeda')],0) + f($row,'vl_total_item');
          ShowHTML('        <td align="right" width="1%" nowrap>'.((nvl($w_sb_moeda,'')=='' && nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').formatNumber(f($row,'vl_total_item')).'</td>');
//          if ($P1!=1) {
//            ShowHTML('        <td>'.Nvl(f($row,'nm_lcsituacao'),'---').'</td>');
//            if ($w_embed!='WORD') ShowHTML('        <td class="remover" width="1">'.ExibeAnotacao('../',$w_cliente,null,f($row,'sq_siw_solicitacao'),f($row,'codigo_interno')).'</td>');
//            ShowHTML('        <td>'.Nvl(f($row,'nm_exec'),'---').'</td>');
//          }
//          if ($P1>2) {
//            if ($w_cliente==6881) ShowHTML('        <td nowrap>'.f($row,'codigo_externo').'</td>');
//            else                  ShowHTML('        <td>'.f($row,'nm_tramite').'</td>');
//          }
//        } 
        ShowHTML('      </tr>');
      } 
      if ($P1!=1) {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_parcial)==1) ? 'l' : 'is').' desta página (apenas vencedores)&nbsp;</td>');
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
          foreach($w_parcial as $k => $v) {
            $w_total[$k] = nvl($w_total[$k],0) + $v;
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_total)==1) ? 'l' : 'is').' da listagem (apenas vencedores)&nbsp;</td>');
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
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'INICIAL':           Inicial();        break;
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

