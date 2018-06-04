<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_exec.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
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

$p_atraso        = upper($_REQUEST['p_atraso']);
$p_codigo        = upper($_REQUEST['p_codigo']);
$p_acao_ppa      = upper($_REQUEST['p_acao_ppa']);
$p_empenho       = upper($_REQUEST['p_empenho']);
$p_chave         = upper($_REQUEST['p_chave']);
$p_assunto       = upper($_REQUEST['p_assunto']);
$p_tipo_material = upper($_REQUEST['p_tipo_material']);
$p_seq_protocolo = upper($_REQUEST['p_seq_protocolo']);
$p_situacao      = upper($_REQUEST['p_situacao']);
$p_ano_protocolo = upper($_REQUEST['p_ano_protocolo']);
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
$p_moeda         = $_REQUEST['p_moeda'];
$p_vencedor      = $_REQUEST['p_vencedor'];
$p_externo       = $_REQUEST['p_externo'];
$p_cnpj          = $_REQUEST['p_cnpj'];
$p_fornecedor    = $_REQUEST['p_fornecedor']; 

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

if ($O=='') {
  $p_vencedor = 'S';
  $O='P';
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

// Se foi informada moeda, recupera seu símbolo e nome.
if ($p_moeda>'') {
  $sql = new db_getMoeda; $RS = $sql->getInstanceOf($dbms, $p_moeda, null, null, null, null, null);
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
    if ((strpos(upper($R),'REL_')!==false) || ($w_tipo=='WORD')) {
      $w_filtro='';

      if (nvl($p_solic_pai,'')!='') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_tipo_material, $p_seq_protocolo, $p_situacao, $p_ano_protocolo, $p_usu_resp,$p_uorg_resp, $p_palavra, $p_prazo, $p_fase, 
            $p_sqcc, $p_projeto, $p_atividade, $p_acao_ppa, null, $p_empenho, $p_servico, $p_moeda,null,null,null,null,null,null,null,null);
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
      if ($p_tipo_material>'') {
        $w_linha++;
        $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_tipo_material,null,null,null,null,null,null,'REGISTROS');
        foreach($RS as $row) { $RS = $row; break; }
        $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
      } 
      if (nvl($p_chave,'')!='') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
                  $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                  $p_chave, $p_assunto, $p_tipo_material, $p_seq_protocolo, $p_situacao, $p_ano_protocolo, $p_usu_resp,$p_uorg_resp, $p_palavra, $p_prazo, $p_fase, 
                  $p_sqcc, $p_projeto, $p_atividade, $p_acao_ppa, null, $p_empenho, $p_servico, $p_moeda,null,null,null,null,null,null,null,null);
        $w_filtro.='<tr valign="top"><td align="right">Pedido <td>[<b>'.f($RS,'codigo_interno').'</b>]';
      } 
      //if ($p_prazo>'') $w_filtro.=' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_empenho>'')  $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]';
      if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
      if ($p_seq_protocolo>'' || $p_ano_protocolo>'') {
        $w_linha++;
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Protocolo <td>[<b>'.(($p_seq_protocolo>'') ? str_pad($p_seq_protocolo,6,'0',PAD_RIGHT) : '*').'/'.(($p_ano_protocolo>'') ? $p_ano_protocolo : '*').'</b>]';
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
      if ($p_situacao>'') {
        $w_linha++;
        $sql = new db_getLCSituacao; $RS = $sql->getInstanceOf($dbms, $p_situacao, $w_cliente, null, null, null, null, null, null);
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
      if ($p_cnpj>'')       $w_filtro.='<tr valign="top"><td align="right">CPF/CNPJ <td>[<b>'.$p_cnpj.'</b>]';
      if ($p_fornecedor>'') $w_filtro.='<tr valign="top"><td align="right">Fornecedor <td>[<b>'.$p_fornecedor.'</b>] (busca em qualquer parte do nome)';
      if ($p_vencedor>'')   { $w_linha++; $w_filtro.='<tr valign="top"><td align="right">Apenas certames com indicação de vencedor <td>[<b>Sim</b>]'; }
      if ($p_externo>'')    $w_filtro.='<tr valign="top"><td align="right">Código '.(($w_cliente==6881) ? 'SA' : 'externo').' <td>[<b>Sim</b>]';
      if ($w_filtro>'')     $w_filtro  ='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
 
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
           "       codigo2numero(coalesce(d.numero_certame, b.codigo_interno, to_char(b.sq_siw_solicitacao))) as ord_codigo_interno," .$crlf.
           "       b.codigo_externo,     b.titulo,                      acentos(b.titulo) as ac_titulo," .$crlf.
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
           "            else dados_solic(b.sq_solic_pai)" .$crlf.
           "       end as dados_pai," .$crlf.
           "       b1.nome as nm_tramite,   b1.ordem as or_tramite," .$crlf.
           "       b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail," .$crlf.
           "       b2.acesso," .$crlf.
           "       b6.sq_moeda,             b6.codigo cd_moeda,            b6.nome nm_moeda," .$crlf.
           "       b6.sigla sg_moeda,       b6.simbolo sb_moeda,           b6.ativo at_moeda," .$crlf.
           "       b7.sq_moeda sq_moeda_alt, b7.codigo cd_moeda_alt,       b7.nome nm_moeda_alt," .$crlf.
           "       b7.sigla sg_moeda_alt,   b7.simbolo sb_moeda_alt,       b7.ativo at_moeda_alt," .$crlf.
           "       case when b6.sq_moeda is not null and b7.sq_moeda is not null" .$crlf.
           "            then conversao(a.sq_pessoa, coalesce(b.inicio, b.inclusao), b6.sq_moeda, b7.sq_moeda, b.valor, 'V')" .$crlf.
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
           "       s.cgccpf cnpj_fabs, s.nome fb_fornecedor," .$crlf.
           "       s.automatico_sa, s.certificacao, s.ds_sa, s.link," .$crlf.
           "       case when s.cgccpf is not null and s.cgccpf <> nvl(d31.cnpj,d32.cpf) and d2.vencedor = 'S' then 'X' end erro," .$crlf.
           "       d2.fornecedor, d2.vencedor, nvl(d31.cnpj,d32.cpf) lic_cnpj, d3.nome nm_fornecedor," .$crlf.
           "       d11.nome nm_material," .$crlf.
           "       d1.ordem or_item, d1.quantidade_autorizada qtd, d2.valor_unidade vl_unit_item, d2.valor_item vl_total_item" .$crlf.
           "  from siw_menu                                        a" .$crlf.
           "       inner             join siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)" .$crlf.
           "       inner             join eo_unidade               c  on (a.sq_unid_executora        = c.sq_unidade)" .$crlf.
           "       inner             join siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu)" .$crlf.
           "          inner          join (select x.sq_siw_solicitacao, acesso(x.sq_siw_solicitacao,".$w_usuario.",null) as acesso" .$crlf.
           "                                 from siw_solicitacao             x" .$crlf.
           "                                      inner  join cl_solicitacao x1 on (x.sq_siw_solicitacao = x1.sq_siw_solicitacao)" .$crlf.
           "                                      inner join siw_menu         y on (x.sq_menu        = y.sq_menu and" .$crlf.
           "                                                                        y.sq_menu        = coalesce(".$P2.", y.sq_menu)" .$crlf.
           "                                                                       )" .$crlf.
           "                              )                            b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)" .$crlf.
           "          inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)" .$crlf.
           "          inner          join cl_solicitacao           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)" .$crlf.
           "          inner          join cl_solicitacao_item      d1 on (b.sq_siw_solicitacao       = d1.sq_siw_solicitacao)" .$crlf.
           "            inner        join cl_item_fornecedor       d2 on (d1.sq_solicitacao_item     = d2.sq_solicitacao_item and" .$crlf.
           "                                                                  d2.pesquisa                = 'N'" .$crlf.
           "                                                                 )" .$crlf.
           "              inner      join co_pessoa                d3 on (d2.fornecedor              = d3.sq_pessoa)" .$crlf.
           "                left     join co_pessoa_juridica      d31 on (d3.sq_pessoa               = d31.sq_pessoa)" .$crlf.
           "                left     join co_pessoa_fisica        d32 on (d3.sq_pessoa               = d32.sq_pessoa)" .$crlf.
           "            inner        join cl_material             d11 on (d1.sq_material             = d11.sq_material)" .$crlf.
           "          inner          join eo_unidade               e  on (b.sq_unidade               = e.sq_unidade)" .$crlf.
           "          inner          join co_cidade                f  on (b.sq_cidade_origem         = f.sq_cidade)" .$crlf.
           "          left           join pe_plano                 b3 on (b.sq_plano                 = b3.sq_plano)" .$crlf.
           "          left           join pj_projeto               b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)" .$crlf.
           "          left           join pa_documento             b5 on (b.protocolo_siw            = b5.sq_siw_solicitacao)" .$crlf.
           "          left           join co_moeda                 b6 on (b.sq_moeda                 = b6.sq_moeda)" .$crlf.
           "            left         join co_moeda                 b7 on (b6.ativo                   = b7.ativo and" .$crlf.
           "                                                              b7.sigla                   = case coalesce(b6.sigla,'-')" .$crlf.
           "                                                                                                when 'USD' then 'BRL'" .$crlf.
           "                                                                                                when 'BRL' then 'USD'" .$crlf.
           "                                                                                                else '-'" .$crlf.
           "                                                                                           end" .$crlf.
           "                                                             )" .$crlf.
           "            left         join lc_modalidade            d4 on (d.sq_lcmodalidade          = d4.sq_lcmodalidade)" .$crlf.
           "            left         join lc_modalidade_artigo    d41 on (d.sq_modalidade_artigo     = d41.sq_modalidade_artigo)" .$crlf.
           "            left         join lc_julgamento            d5 on (d.sq_lcjulgamento          = d5.sq_lcjulgamento)" .$crlf.
           "            left         join lc_situacao              d6 on (d.sq_lcsituacao            = d6.sq_lcsituacao)" .$crlf.
           "            left         join pa_especie_documento     d7 on (d.sq_especie_documento     = d7.sq_especie_documento)" .$crlf.
           "              left       join eo_unidade_resp          e1 on (e.sq_unidade               = e1.sq_unidade and" .$crlf.
           "                                                              e1.tipo_respons            = 'T'           and" .$crlf.
           "                                                              e1.fim                     is null" .$crlf.
           "                                                             )" .$crlf.
           "              left       join eo_unidade_resp          e2 on (e.sq_unidade               = e2.sq_unidade and" .$crlf.
           "                                                              e2.tipo_respons            = 'S'           and" .$crlf.
           "                                                              e2.fim                     is null" .$crlf.
           "                                                             )" .$crlf.
           "          left           join siw_solicitacao          m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)" .$crlf.
           "          left           join ct_cc                    n  on (b.sq_cc                    = n.sq_cc)" .$crlf.
           "          left           join co_pessoa                o  on (b.solicitante              = o.sq_pessoa)" .$crlf.
           "          left           join co_pessoa                p  on (b.executor                 = p.sq_pessoa)" .$crlf.
           "          left           join co_pessoa                q  on (b.recebedor                = q.sq_pessoa)" .$crlf.
           "          left           join eo_unidade_resp          a3 on (c.sq_unidade               = a3.sq_unidade and" .$crlf.
           "                                                              a3.tipo_respons            = 'T'           and" .$crlf.
           "                                                              a3.fim                     is null" .$crlf.
           "                                                             )" .$crlf.
           "          left           join eo_unidade_resp          a4 on (c.sq_unidade               = a4.sq_unidade and" .$crlf.
           "                                                              a4.tipo_respons            = 'S'           and" .$crlf.
           "                                                              a4.fim                     is null" .$crlf.
           "                                                             )" .$crlf;
    if (strpos($_SERVER['HTTP_HOST'],'unesco')!==false) {
      $SQL.="          left          join (select w.automatico_sa, w.certificacao, x.handle, x.cgccpf, x.nome, y.ds_sa," .$crlf.
            "                                     seguranca.FCLinkWeb(w.automatico_sa,1,1,167) link" .$crlf.
            "                                from corporativo.un_solicitacaoadministrativa     w" .$crlf.
            "                                     inner join corporativo.gn_pessoas            x on (w.contratado   = x.handle)" .$crlf.
            "                                     inner join corporativo.vw_permissao_web      y on (w.certificacao = y.ordem)" .$crlf.
            "                             )                         s  on (b.codigo_externo      = s.automatico_sa)" .$crlf;
    } else {
      $SQL.="          left          join (select null automatico_sa, null certificacao, null handle, null cgccpf, null nome, null ds_sa, null link from dual where 1=0) s on (b.codigo_externo = s.automatico_sa)" .$crlf;
    }
    $SQL.= " where a.sq_menu              = ".$P2.$crlf.
           "   and b1.sigla               <> 'CA'";
    if (nvl($p_vencedor,'')=='S')   $SQL.="   and d2.vencedor            = '".$p_vencedor."'".$crlf;
    if (nvl($p_externo,'')!='')     $SQL.="   and b.codigo_externo       is not null and acentos(b.codigo_externo) like '%'||acentos('".$p_externo."')||'%'".$crlf;
    if (nvl($p_fornecedor,'')!='')  $SQL.="   and (acentos(d3.nome,null) like '%'||acentos('".$p_fornecedor."',null)||'%' or acentos(s.nome,null) like '%'||acentos('".$p_fornecedor."',null)||'%')".$crlf;
    if (nvl($p_cnpj,'')!='')        $SQL.="   and (nvl(d31.cnpj,d32.cpf) = '".$p_cnpj."' or s.cgccpf = '".$p_cnpj."')".$crlf;
    if (nvl($p_moeda,'')!='')       $SQL.="   and b.sq_moeda             = ".$p_moeda.$crlf;
    if (nvl($p_sq_acao_ppa,'')!='') $SQL.="   and d.sq_modalidade_artigo = ".$p_sq_acao_ppa.$crlf;
    if (nvl($p_sq_orprior,'')!='')  $SQL.="   and b.sq_plano             = ".$p_sq_orprior.$crlf;
    if (nvl($p_tipo_material,'')!='')        $SQL.="   and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and y.sq_tipo_material in (select sq_tipo_material from cl_tipo_material connect by prior sq_tipo_material = sq_tipo_pai start with sq_tipo_material=".$p_tipo_material."))".$crlf;
    if (nvl($p_seq_protocolo,'')!='')      $SQL.="   and d.processo             like '%".$p_seq_protocolo."%'))".$crlf;
    if (nvl($p_ano_protocolo,'')!='')      $SQL.="   and d.processo             like '%".$p_ano_protocolo."%'".$crlf;
    if (nvl($p_usu_resp,'')!='')    $SQL.="   and d4.sq_lcmodalidade     = ".$p_usu_resp.$crlf;
    if (nvl($p_uorg_resp,'')!='')   $SQL.="   and (b1.sigla <> 'AT' and e.sq_unidade = ".$p_uorg_resp.")".$crlf;
    if (nvl($p_sqcc,'')!='')        $SQL.="   and b.sq_cc                = ".$p_sqcc.$crlf;
    if (nvl($p_projeto,'')!='')     $SQL.="   and b.sq_solic_pai         = ".$p_projeto.$crlf;
    if (nvl($p_servico,'')!='')    $SQL.="   and (('".$p_servico."'    = 'CLASSIF' and b.sq_cc is not null) or ('".$p_servico."' <> 'CLASSIF' and m.sq_menu = to_number(".$p_servico.")))".$crlf;
    if (nvl($p_situacao,'')!='')          $SQL.="   and d6.sq_lcsituacao       = ".$p_situacao.$crlf;
    if (nvl($p_proponente,'')!='')  $SQL.="   and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(y.nome,null) like '%'||acentos('".$p_proponente."',null)||'%')".$crlf;
    if (nvl($p_assunto,'')!='')     $SQL.="   and acentos(b.codigo_externo,null) like '%'||acentos('".$p_assunto."',null)||'%'".$crlf;
    if (nvl($p_palavra,'')!='')     $SQL.="   and acentos(d.numero_certame,null) like '%'||acentos('".$p_palavra."',null)||'%'".$crlf;
    if (nvl($p_empenho,'')!='')     $SQL.="   and (acentos(b.codigo_interno,null) like '%'||acentos('".$p_empenho."',null)||'%' or acentos(d.numero_certame,null) like '%'||acentos('".$p_empenho."',null)||'%')".$crlf;
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
    if ($w_filtro > '') ShowHTML('<div align="left">'.$w_filtro.'</div>');
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
    $rowspan = 1;
    if (strpos($_SERVER['HTTP_HOST'],'unesco')!==false) $rowspan = 2;
    if ($w_embed!='WORD') {
      if (nvl($p_vencedor,'')=='') { $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Vencedor','vencedor').'</td>'); }
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('CPF/CNPJ','lic_cnpj').'</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Fornecedor','nm_fornecedor').'</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Código','ord_codigo_interno').'</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Objeto','objeto').'</td>');
      $colspan++; ShowHTML ('         <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Vinculação','dados_pai').'</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Modalidade','sg_lcmodalidade').'</td>');
//      if ($w_pa=='S' || $w_segmento=='Público') { $colspan++; ShowHTML ('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Processo','processo').'</td>'); }
//      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Solicitante','sg_unidade_resp').'</td>');
//      //$colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Data limite','fim').'</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Item','nm_material').'</td>');
      ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Valor'.(($w_sb_moeda>'') ? ' ('.$w_sb_moeda.')' : ''),'valor').'</td>');
//        if ($P1!=1) {
//          ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Situação','nm_lcsituacao').'</td>');
//          if ($w_embed!='WORD') ShowHTML('          <td rowspan="'.$rowspan.'" class="remover" width="1">&nbsp;</td>');
//          ShowHTML('          <td rowspan="'.$rowspan.'"><b>'.LinkOrdena('Executor','nm_exec').'</td>');
//        }
      if (strpos($_SERVER['HTTP_HOST'],'unesco')!==false) ShowHTML('          <td colspan="2"><b>SA</td>');
      ShowHTML('        </tr>');
      if (strpos($_SERVER['HTTP_HOST'],'unesco')!==false) {
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>'.LinkOrdena('Código','automatico_sa').'</td>');
        ShowHTML('          <td><b>'.LinkOrdena('Fase','ds_sa').'</td>');
        ShowHTML('        </tr>');
      }
    } else {
      if (nvl($p_vencedor,'')=='') { $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>Vencedor</td>'); }
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>CPF/CNPJ</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>Fornecedor</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>Código</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>Objeto</td>');
      $colspan++; ShowHTML ('          <td rowspan="'.$rowspan.'"><b>Vinculação</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>Modalidade</td>');
//      if ($w_pa=='S' || $w_segmento=='Público') { $colspan++; ShowHTML ('          <td rowspan="'.$rowspan.'"><b>Processo</td>'); }
//      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>Solicitante</td>');
//      //$colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>Data limite</td>');
      $colspan++; ShowHTML('          <td rowspan="'.$rowspan.'"><b>Item</td>');
      ShowHTML('          <td rowspan="'.$rowspan.'"><b>Valor'.(($w_sb_moeda>'') ? ' ('.$w_sb_moeda.')' : '').'</td>');
//        if ($P1!=1) {
//          ShowHTML('          <td rowspan="'.$rowspan.'"><b>Situação</td>');
//          if ($w_embed!='WORD') ShowHTML('          <td rowspan="'.$rowspan.'" class="remover" width="1">&nbsp;</td>');
//          ShowHTML('          <td rowspan="'.$rowspan.'"><b>Executor</td>');
//        }
      if (strpos($_SERVER['HTTP_HOST'],'unesco')!==false) ShowHTML('          <td colspan="2"><b>SA</td>');
      ShowHTML('        </tr>');
      if (strpos($_SERVER['HTTP_HOST'],'unesco')!==false) {
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Código</td>');
        ShowHTML('          <td><b>Fase</td>');
        ShowHTML('        </tr>');
      }
    }
    $w_erro = false;
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
        if (f($row,'erro')=='X') {
          ShowHTML('      <tr bgcolor="'.$conTrBgColorLightYellow2.'" valign="top">'); 
          $w_erro = true;
        } else {
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        }
        if (nvl($p_vencedor,'')=='') ShowHTML('        <td align="center" nowrap>'.((f($row,'vencedor')=='S') ? 'X' : '').'</td>');
        ShowHTML('        <td align="center" nowrap'.((f($row,'erro')=='X') ? ' title="'.f($row,'cnpj_fabs').' - '.f($row,'fb_fornecedor').'"' : '').'>'.f($row,'lic_cnpj').'</td>');
        ShowHTML('        <td width="20%">' . ExibePessoa('../', $w_cliente, f($row, 'fornecedor'), $TP, f($row, 'nm_fornecedor')) . '</td>');
        ShowHTML('        <td width="1%" nowrap>');
        if ($w_embed!='WORD'){
          ShowHTML('        <A class="HL" target="_blank" HREF="mod_cl/certame.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
        } else {
          ShowHTML('&nbsp;'.f($row,'codigo_interno').'&nbsp;');
        }
        ShowHTML('        <td width="20%">'.f($row,'objeto').'</td>');
        ShowHTML('        <td'.((f($row,'sg_cc')!='') ? ' title="'.f($row,'dados_pai').'"' : '').'>'.f($row,'sg_cc').'</td>');
        ShowHTML('        <td title="'.f($row,'nm_lcmodalidade').'" align="center">'.f($row,'sg_lcmodalidade').'</td>');
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
        ShowHTML('        <td>'.f($row,'or_item').' - '.f($row,'nm_material').'</td>');
        if (f($row,'vencedor')=='S') $w_parcial[f($row,'sb_moeda')] = nvl($w_parcial[f($row,'sb_moeda')],0) + f($row,'vl_total_item');
        ShowHTML('        <td align="right" width="1%" nowrap>'.((nvl($w_sb_moeda,'')=='' && nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').formatNumber(f($row,'vl_total_item')).'</td>');
//          if ($P1!=1) {
//            ShowHTML('        <td>'.Nvl(f($row,'nm_lcsituacao'),'---').'</td>');
//            if ($w_embed!='WORD') ShowHTML('        <td class="remover" width="1">'.ExibeAnotacao('../',$w_cliente,null,f($row,'sq_siw_solicitacao'),f($row,'codigo_interno')).'</td>');
//            ShowHTML('        <td>'.Nvl(f($row,'nm_exec'),'---').'</td>');
//          }
        if (strpos($_SERVER['HTTP_HOST'],'unesco')!==false) {
          if (f($row,'vencedor')=='S' && nvl(f($row,'automatico_sa'),'')!='') {
            // Bloco exibido apenas se a rotina estiver rodando no servidor da UNESCO.
            if (nvl(f($row,'automatico_sa'),'')!='') {
              ShowHTML('        <td nowrap><a class="HL" target="_blank" href="'.f($row,'link').'">'.f($row,'automatico_sa').'</a>');
            } else {
              ShowHTML('        <td>&nbsp;</td>');
            }
            ShowHTML('        <td>'.f($row,'ds_sa').'</td>');
          } else {
            ShowHTML('        <td colspan="2">&nbsp;</td>');
          }
        }
        ShowHTML('      </tr>');
      } 
      if ($P1!=1) {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_parcial)==1) ? 'l' : 'is').' desta página '.((nvl($p_vencedor,'')=='') ? '(apenas vencedores)': '').'&nbsp;</td>');
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
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_total)==1) ? 'l' : 'is').' da listagem '.((nvl($p_vencedor,'')=='') ? '(apenas vencedores)': '').'&nbsp;</td>');
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
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_erro) ShowHTML('<tr><td colspan=3><b>ATENÇÃO: Linhas na cor amarela indicam divergência entre o vencedor do certame e o beneficiário da SA. Passe o mouse sobre o CPF/CNPJ para ver os dados informados na SA.</td></tr>');
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
      SelecaoSolic('Classificação:',null,null,$w_cliente,$p_sqcc,'CLASSIF',null,'p_sqcc','SIWSOLIC',null,null,'<BR />',2);
      ShowHTML('   <tr valign="top">');
      SelecaoPessoa('<u>S</u>olicitante:','N','Selecione o solicitante na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$p_unidade,null,'p_unidade','CLCP',null);
      ShowHTML('   <tr valign="top">');
      ShowHTML('     <td><b>C<U>P</U>F/CNPJ: (insira pontos, barras e traços)<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_cnpj" size="20" maxlength="20" value="'.$p_cnpj.'"></td>');
      ShowHTML('     <td><b><U>F</U>ornecedor: (qualquer parte do nome)<br><INPUT ACCESSKEY="F" '.$w_Disabled.' class="STI" type="text" name="p_fornecedor" size="40" maxlength="60" value="'.$p_fornecedor.'"></td>');
      //ShowHTML('   <tr valign="top">');
      //ShowHTML('     <td><b><u>D</u>ata de recebimento e limite para atendimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('   <tr valign="top">');
      ShowHTML('     <td><input type="checkbox" class="stc" name="p_vencedor" value="S"'.((nvl($p_vencedor,'')!='') ? ' checked' : '').'> Recuperar apenas certames com vencedores indicados');
      ShowHTML('     <td><b>Có<u>d</u>igo '.(($w_cliente==6881) ? 'SA': 'externo').': (qualquer parte do código)<br><INPUT ACCESSKEY="O" '.$w_Disabled.' class="STI" type="text" name="p_externo" size="20" maxlength="60" value="'.$p_externo.'"></td>');
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

