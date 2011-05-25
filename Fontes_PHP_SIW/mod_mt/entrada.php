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
include_once($w_dir_volta.'classes/sp/db_getDocumentoArquivo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getMtMovim.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicObjetivo.php');
include_once($w_dir_volta.'classes/sp/db_getMatServ.php');
include_once($w_dir_volta.'classes/sp/db_getMtEntItem.php');
include_once($w_dir_volta.'classes/sp/db_getMTFinanceiro.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getTipoMovimentacao.php');
include_once($w_dir_volta.'classes/sp/db_getMtSituacao.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putMtEntrada.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicConc.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putMtEntItem.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'funcoes/selecaoAlmoxarifado.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoPlanoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoObjetivoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMovimentacao.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoMtSituacao.php');
include_once('visualentrada.php');
include_once('validaentrada.php');

// =========================================================================
//  /entrada.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Registra entradas de material
// Mail     : alex@sbpi.com.br
// Criacao  : 24/01/2011, 14:30
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
$w_pagina       = 'entrada.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_mt/';
$w_troca        = $_REQUEST['w_troca'];

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
$w_cadgeral = 'S';

// Verifica se o cliente tem o módulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_mod_pa='S'; else $w_mod_pa='N';

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
$p_fim_i        = upper($_REQUEST['p_fim_i']);
if (nvl($p_fim_i,'')!='') {
  if ($_REQUEST['p_agrega']=='GRCLAUTORIZ') {
    $p_fim_f = formataDataEdicao(last_day(toDate($p_fim_i)));
  } else {
    $p_fim_f = nvl($_REQUEST['p_fim_f'],formataDataEdicao(last_day(toDate($p_fim_i))));
  }
}
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_codigo       = upper($_REQUEST['p_codigo']);
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

// Verifica se o cliente tem o módulo de protocolo e arquivo
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_pa='S'; else $w_pa='N';

// Recupera os parâmetros de funcionamento do módulo de compras
$sql = new db_getParametro; $RS_Parametro = $sql->getInstanceOf($dbms,$w_cliente,'CL',null);
foreach($RS_Parametro as $row){$RS_Parametro=$row; break;}
$w_pede_valor_pedido       = f($RS_Parametro,'pede_valor_pedido');

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
    if ((strpos(upper($R),'GR_')!==false) || ($w_tipo=='WORD')) {
      $w_filtro='';

      if (nvl($p_solic_pai,'')!='') {
        $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),5,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
            null, null, $p_empenho, $p_servico);
        if($w_tipo=='WORD') $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.exibeSolic($w_dir,$p_projeto,f($RS,'dados_solic'),'S','S').'</b>]';
        else                $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.exibeSolic($w_dir,$p_projeto,f($RS,'dados_solic'),'S').'</b>]';
      } elseif (nvl($p_servico,'')!='') {
        if ($p_servico=='CLASSIF') {
          $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>Apenas pedidos com classificação</b>]';
        } else {
          $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$p_servico);
          $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>'.f($RS,'nome').'</b>]';
        }
      } elseif (nvl($_REQUEST['p_agrega'],'')=='GRPRVINC') {
        $w_filtro.='<tr valign="top"><td align="right">Vinculação <td>[<b>Apenas pedidos com vinculação</b>]';
      } elseif (nvl($p_chave,'')!='') {
        $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),5,
                  $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                  $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                  $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                  $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
                  null, null, $p_empenho, $p_servico);
        $w_filtro.='<tr valign="top"><td align="right">Pedido <td>[<b>'.f($RS,'codigo_interno').'</b>]';
      } 
      if ($p_projeto>'') {
        $w_linha++;
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
        $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
      } 
      if ($p_pais>'') {
        $w_linha++;
        $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_pais,null,null,null,null,null,null,'REGISTROS');
        foreach($RS as $row) { $RS = $row; break; }
        $w_filtro .= '<tr valign="top"><td align="right">Tipo de material/serviço<td>[<b>'.f($RS,'nome_completo').'</b>]';
      } 
      if ($p_prazo>'') $w_filtro.=' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_empenho>'')  $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>'.$p_empenho.'</b>]';
      if ($p_proponente>'') { $w_linha++; $w_filtro .= '<tr valign="top"><td align="right">Material<td>[<b>'.$p_proponente.'</b>]'; }
      if ($p_solicitante>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Solicitante <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>'') {
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Unidade solicitante <td>[<b>'.f($RS,'nome').'</b>]';
      }
      if ($p_ini_i>'')      $w_filtro.='<tr valign="top"><td align="right">Homologação <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro.='<tr valign="top"><td align="right">Autorização <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($w_filtro>'')     $w_filtro  ='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    } 
 
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as PCDs visíveis pelo usuário
      $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_servico);
    } else {
      $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_servico);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1], 'nm_fornecedor', 'asc','nr_doc','asc', 'dt_doc', 'asc');
    } else {
      $RS = SortArray($RS, 'nm_fornecedor', 'asc','nr_doc','asc', 'dt_doc', 'asc');
    }
  }
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
  } else {
    Cabecalho();
    head();
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ScriptOpen('Javascript');
    Modulo();
    FormataCPF();
    CheckBranco();
    FormataData();
    SaltaCampo();
    openBox('reload');
    ValidateOpen('Validacao');
    if (strpos('CP',$O)!==false) {
      if ($P1!=1 || $O=='C') {
        // Se não for cadastramento ou se for cópia        
        Validate('p_codigo','Número do pedido','','','2','60','1','1');
        Validate('p_ini_i','Início','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Fim','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != "" && theForm.p_ini_f.value == "") || (theForm.p_ini_i.value == "" && theForm.p_ini_f.value != "")) {');
        ShowHTML('     alert ("Informe ambas as datas ou nenhuma delas!");');
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
    BodyOpen('onLoad="document.Form.'.$w_Troca.'.focus();"');
  } elseif (strpos('CP',$O)!==false) {
    BodyOpen('onLoad="document.Form.p_codigo.focus()";');
  } elseif ($P1==2) {
    BodyOpen(null);
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
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
  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td nowrap>');
    if ($w_tipo!='WORD') { 
      ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;'); 
    }
    if ((strpos(upper($R),'GR_'))===false && $P1!=6 && $w_tipo!='WORD') {
      if ((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_'))) {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.(($w_copia>'') ? 'C' : 'P').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      } else {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.(($w_copia>'') ? 'C' : 'P').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    }
    ShowHTML('    <td colspan=2 nowrap align="right"><b>'.exportaOffice().'Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td><b>'.LinkOrdena('Fornecedor','nm_fornecedor_ind').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Documento','nm_tipo_doc').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Número','nr_doc').'</b></td>');
      ShowHTML ('         <td><b>'.LinkOrdena('Data','dt_doc').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_mov').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Lançamento','sq_siw_solicitacao').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Valor','valor_doc').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Situação','nm_sit').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Itens','qt_itens').'</b></td>');
      ShowHTML('          <td class="remover"><b>Operações</b></td>');
      ShowHTML('        </tr>');
      
    } else {
      ShowHTML('          <td><b>Fornecedor</b></td>');
      ShowHTML('          <td><b>Documento</b></td>');
      ShowHTML ('         <td><b>Data</b></td>');
      ShowHTML('          <td><b>Tipo</b></td>');
      ShowHTML('          <td><b>Lançamento</b></td>');
      ShowHTML('          <td><b>Valor</b></td>');
      ShowHTML('          <td><b>Situação</b></td>');
      ShowHTML('          <td><b>Itens</b></td>');
      ShowHTML('        </tr>');
    }
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.(($w_tipo=='WORD') ? f($row,'nm_fornecedor') : ExibePessoa('../',$w_cliente,f($row,'sq_fornecedor'),$TP,f($row,'nm_fornecedor'))).'</td>');
        ShowHTML('        <td>'.f($row,'nm_tp_doc').'</td>');
        ShowHTML('        <td>'.f($row,'nr_doc').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'dt_doc'),5).'</td>');
        ShowHTML('        <td>'.f($row,'nm_tp_mov').'</td>');
        ShowHTML('        <td width="1%" nowrap>'.(($w_tipo=='WORD') ? f($row,'codigo_interno') : exibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'codigo_interno'))).'</td>');
        ShowHTML('        <td align="right" width="1%">&nbsp;'.formatNumber(f($row,'vl_doc'),2).'&nbsp;</td>');
        ShowHTML('        <td>'.f($row,'nm_sit').'</td>');
        ShowHTML('        <td align="right" width="1%">&nbsp;'.formatNumber(f($row,'qt_itens'),0).'&nbsp;</td>');
        if ($w_tipo!='WORD') {
          ShowHTML('        <td class="remover" width="1%" nowrap>&nbsp;');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_mtentrada').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais do pedido">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_mtentrada').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão do pedido.">EX</A>&nbsp');
          ShowHTML('          <a class="HL" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_copia='.f($row,'sq_mtentrada').MontaFiltro('GET').'" title="Gera um novo registro a partir das informações deste.">CO</a>&nbsp;');
          //ShowHTML('          <A class="box HL" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_mtentrada').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Itens'.'&SG='.substr($SG,0,4).'ITEM').'" title="Escolhe os itens da compra.">Itens</A>&nbsp');
          //ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Anexos&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_mtentrada').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Anexos'.'&SG='.substr($SG,0,4).'ANEXO').'\',\'Anexos\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Vincula arquivos ao pedido de compra.">Anexos</A>&nbsp');
          //ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_mtentrada').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento do pedido">EN</A>&nbsp');
        } 
        ShowHTML('        &nbsp;</td>');
        ShowHTML('      </tr>');
        $w_parcial += f($row,'valor');
      } 
      $w_colspan=6;
      if (ceil(count($RS)/$P4)>1) { 
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
        ShowHTML('          <td colspan='.$w_colspan.' align="right"><b>Total desta página&nbsp;</td>');
        ShowHTML('          <td align="right"><b>'.formatNumber($w_parcial).'&nbsp;</td>');
        ShowHTML('          <td colspan=3>&nbsp;</td>');
        ShowHTML('        </tr>');
      } 
      // Se for a última página da listagem, soma e exibe o valor total
      if ($P3==ceil(count($RS)/$P4)) {
        reset($RS);
        foreach($RS as $row) $w_total += f($row,'valor');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
        ShowHTML('          <td colspan='.$w_colspan.' align="right"><b>Total da listagem&nbsp;</td>');
        ShowHTML('          <td align="right"><b>'.formatNumber($w_total).'&nbsp;</td>');
        ShowHTML('          <td colspan=3>&nbsp;</td>');
        ShowHTML('        </tr>');
      } 
    } 
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
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td valign="top" colspan="2">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      ShowHTML('   <tr valign="top">');
      ShowHTML('     <td valign="top"><b>Número do <U>p</U>edido:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_codigo" size="20" maxlength="60" value="'.$p_codigo.'"></td>');
      ShowHTML('   <tr valign="top">');
      SelecaoPessoa('<u>S</u>olicitante:','N','Selecione o solicitante do pedido na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
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
    ShowHTML(' alert("Opção não disponível");');
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
  $w_copia      = $_REQUEST['w_copia'];    
  $w_readonly   = '';
  $w_erro       = '';
  
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_solicitacao              = $_REQUEST['w_solicitacao'];
    $w_documento                = $_REQUEST['w_documento'];
    $w_fornecedor               = $_REQUEST['w_fornecedor'];
    $w_fornecedor_nm            = $_REQUEST['w_fornecedor_nm'];
    $w_protocolo                = $_REQUEST['w_protocolo'];
    $w_protocolo_nm             = $_REQUEST['w_protocolo_nm'];
    $w_tipo                     = $_REQUEST['w_tipo'];
    $w_prevista                 = $_REQUEST['w_prevista'];
    $w_efetiva                  = $_REQUEST['w_efetiva'];
    $w_sq_tipo_documento        = $_REQUEST['w_sq_tipo_documento'];
    $w_numero                   = $_REQUEST['w_numero'];
    $w_data                     = $_REQUEST['w_data'];
    $w_valor                    = $_REQUEST['w_valor'];
  } elseif (strpos('AEV',$O)!==false || $w_copia>'') {
    // Recupera os dados da entrada
    if ($w_copia>'') {
      $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
        null,null,null,null,null,null,null,null,null,null,
        $w_copia,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,null,null,null);
    } else {
      $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
        null,null,null,null,null,null,null,null,null,null,
        $w_chave,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,null,null,null);
    }
    if (count($RS)>0) {
      foreach($RS as $row){$RS=$row; break;}
      $w_solicitacao              = f($RS,'sq_siw_solicitacao');
      $w_documento                = f($RS,'sq_lancamento_doc');
      $w_fornecedor               = f($RS,'sq_fornecedor');
      $w_fornecedor_nm            = f($RS,'nm_fornecedor');
      $w_tipo                     = f($RS,'sq_tipo_movimentacao');
      $w_prevista                 = formataDataEdicao(f($RS,'recebimento_previsto'));
      $w_efetiva                  = formataDataEdicao(f($RS,'recebimento_efetivo'));
      $w_sq_tipo_documento        = f($RS,'sq_tipo_documento');
      $w_numero                   = f($RS,'nr_doc');
      $w_data                     = formataDataEdicao(f($RS,'dt_doc'));
      $w_valor                    = formatNumber(f($RS,'vl_doc'));
    } 
  } 

  // Se não puder cadastrar para outros, carrega os dados do usuário logado
  if (nvl($w_protocolo,'')!='') {
    // Se receber o protocolo, recupera as informações do lançamento financeiro a ele associado
    $w_prefixo  = substr($_REQUEST['w_protocolo'],  0, 5)+0;
    $w_numero   = substr($_REQUEST['w_protocolo'],  6, 6)+0;
    $w_ano      = substr($_REQUEST['w_protocolo'], 13, 4)+0;

    $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, 'PADCAD');
    $sql = new db_getSolicList; $RS1 = $sql->getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'PROTOCOLO',3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $w_prefixo, $w_numero, $p_uf, $w_ano, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, $p_sq_orprior);

    $w_erro = true;
    foreach($RS1 as $row1) {
      if (f($row1,'sg_modulo')=='FN') {
        $sql = new db_getLancamentoDoc; $RS2 = $sql->getInstanceOf($dbms,f($row1,'sq_siw_solicitacao'),null,'DOCS');
        if (count($RS2)>0) {
          $RS2 = SortArray($RS2,'data','asc');
          foreach($RS2 as $row2) {
            $sql = new db_getSolicData; $RS3 = $sql->getInstanceOf($dbms,f($row1,'sq_siw_solicitacao'),f($row1,'sigla'));
            $w_solicitacao              = f($RS3,  'sq_siw_solicitacao');
            $w_fornecedor               = f($RS3,  'pessoa');
            $w_fornecedor_nm            = f($row3, 'nm_pessoa_resumido');
            $w_documento                = f($row2, 'sq_lancamento_doc');
            $w_tipo                     = f($row2, 'sq_tipo_documento');
            $w_prevista                 = formataDataEdicao(f($row2, 'data'));
            $w_efetiva                  = formataDataEdicao(f($row2, 'data'));
            $w_sq_tipo_documento        = f($row2, 'sq_tipo_documento');
            $w_numero                   = f($row2, 'numero');
            $w_data                     = formataDataEdicao(f($row2, 'data'));
            $w_valor                    = formatNumber(f($row2, 'valor'));
            $sql = new db_getTipoMovimentacao; $RS4 = $sql->getInstanceOf($dbms,$w_cliente,null,null,'S',null,null,null,null,null,'S',null);
            foreach($RS4 as $row4) {
              if (lower(f($row4,'nome'))=='entrada orçamentária') {
                $w_tipo = f($row4,'chave');
                break;
              }
            }
            $w_erro = false;
            break;
          }
        }
        if (!$w_erro) break;
      }
    }
  }
  if ($w_erro) {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("ATENÇÃO: Protocolo informado não tem lançamento financeiro vinculado!");');
    ScriptClose();
  }
  
  if ($O!='I') {
    // Validação
    $w_erro = ValidaEntrada($w_cliente,$w_chave,$SG,null,null,null,null);
  }

  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataValor();
  FormataData();
  SaltaCampo();
  openBox('reload');
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_fornecedor_nm','Fornecedor','','1','1','40','1','1');
    Validate('w_tipo','Tipo da movimentação', 'SELECT', '1', '1', '18', '', '0123456789');
    Validate('w_prevista','Data prevista para recebimento','DATA',1,10,10,'','0123456789/');
    Validate('w_efetiva','Data efetiva de recebimento','DATA',1,10,10,'','0123456789/');
    Validate('w_sq_tipo_documento','Tipo do documento', '1', '1', '1', '18', '', '0123456789');
    Validate('w_numero','Número do documento', '1', '1', '1', '30', '1', '1');
    Validate('w_data','Data do documento', 'DATA', '1', '10', '10', '', '0123456789/');
    Validate('w_valor','Valor do documento', 'VALOR', '1', 4, 18, '', '0123456789.,');
    ShowHTML('  document.Form.Botao[0].disabled = true;');
    ShowHTML('  document.Form.Botao[1].disabled = true;');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="this.focus()";');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpen('onLoad="this.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if ($w_cidade=='') {
      // Carrega os valores padrão para país, estado e cidade
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
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
    ShowHTML('<INPUT type="hidden" name="w_solicitacao" value="'.$w_solicitacao.'">');
    ShowHTML('<INPUT type="hidden" name="w_documento" value="'.$w_documento.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=4 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4 valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4>Os dados deste bloco serão utilizados para identificação da entrada de material, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    SelecaoPessoaOrigem('<u>F</u>ornecedor:', 'P', 'Clique na lupa para selecionar o fornecedor.', $w_fornecedor, null, 'w_fornecedor', null, null, null);
    if ($w_mod_pa=='S') {
      SelecaoProtocolo('Recuperar a partir do n<u>ú</u>mero do protocolo:','U','Selecione o protocolo de pagamento.',$w_protocolo,null,'w_protocolo',$SG,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_protocolo\'; document.Form.submit();"',3);
    }
    ShowHTML('       <tr valign="top">');
    SelecaoTipoMovimentacao('Tipo da <u>m</u>ovimentação:','M', 'Selecione o tipo da movimentação.', $w_tipo,'S',null,'w_tipo',null,null);
    ShowHTML('          <td><b>Data <u>p</u>revista para recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_prevista" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_prevista.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data prevista para recebimento do material ou serviço.">'.ExibeCalendario('Form','w_prevista').'</td>');
    ShowHTML('          <td><b>Data <u>e</u>fetiva de recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_efetiva" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_efetiva.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de entrega do material ou conclusão do serviço.">'.ExibeCalendario('Form','w_efetiva').'</td>');
    ShowHTML('       <tr valign="top">');
    SelecaoTipoDocumento('<u>D</u>ocumento:','D', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,'w_sq_tipo_documento',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_numero\'; document.Form.submit();"');
    ShowHTML('          <td><b><u>N</u>úmero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o número do documento."></td>');
    ShowHTML('          <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data do documento.">'.ExibeCalendario('Form','w_data').'</td>');
    ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    ShowHTML('      <tr><td align="center" colspan=4 height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan=4>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('<tr><td colspan=2><font size=2><HR>');

    if ($O!='I') {
      if ($w_erro>'') {
        if (substr($w_erro,0,1)=='0') {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo, não sendo possível armazenar/incorporar seus itens.');
        }elseif (substr($w_erro,0,1)=='1') {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo. O armazenamento/incorporação dos itens só pode ser feito por um gestor do sistema ou do módulo de projetos.');
        } else {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o armazenamento/incorporação dos itens, mas convém sua verificação.');
        } 
        ShowHTML('  <ul>'.substr($w_erro,1,1000));
        ShowHTML('<tr><td colspan=2><HR>');
      } 
    
      // Itens
      ShowHTML('    <tr><td colspan=3 align="center" height="1"></td></tr>');
      ShowHTML('    <tr><td colspan=3><b>Itens</b>&nbsp;&nbsp;[<A class="box HL" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.removeTP($TP).' - Itens'.'&SG='.substr($SG,0,4).'ITEM').'" title="Informa os itens do documento.">Ajustar</A>]</td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center" height="1"></td></tr>');
      ShowHTML('    <tr><td colspan="3"><table width="100%" border="0">');
      $sql = new db_getMtEntItem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null);
      $RS = SortArray($RS,'ordem','asc','nome','asc'); 
      if (count($RS)==0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align=center><b>Itens não informados</b></td></tr>');
        ShowHTML('      <tr><td colspan=2 align=center><hr /></td></tr>');
      } else {
        unset($w_classes);
        foreach($RS as $row) $w_classes[f($row,'classe')] = 1;
        reset($RS);
        $colspan = 0;
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <table class="tudo" width="100%" BORDER=1 bordercolor="#666666">');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td rowspan=2><b>Item</b></td>');
        ShowHTML('          <td rowspan=2><b>Nome</b></td>');
        if (($w_classes[1] || $w_classes[3]) && !$w_classes[4]) {
          ShowHTML('          <td rowspan=2><b>Marca</b></td>');
        } elseif (!($w_classes[1] || $w_classes[3]) && $w_classes[4]) {
          ShowHTML('          <td rowspan=2><b>Fabricante</b></td>');
        } elseif (($w_classes[1] || $w_classes[3]) && $w_classes[4]) {
          ShowHTML('          <td rowspan=2><b>Fabricante / Marca</b></td>');
        }
        if ($w_classes[4]) {
          ShowHTML('          <td rowspan=2><b>Modelo</b></td>');
          $colspan++;
          ShowHTML('          <td rowspan=2><b>Vida útil</b></td>');
          $colspan++;
        }
        if ($w_classes[1]) {
          ShowHTML('          <td rowspan=2><b>Lote</b></td>');
          ShowHTML('          <td rowspan=2><b>Fabricação</b></td>');
          $colspan += 2;
        }
        if ($w_classes[1] || $w_classes[3]) {
          ShowHTML('          <td rowspan=2><b>Validade</b></td>');
          ShowHTML('          <td rowspan=2><b>Fator<br>Embal.</b></td>');
          ShowHTML('          <td rowspan=2><b>U.M.</b></td>');
          $colspan += 3;
        }
        ShowHTML('          <td rowspan=2><b>Qtd</b></td>');
        ShowHTML('          <td colspan=2><b>Valores</b></td>');
        ShowHTML('        </tr>');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td><b>Unit.</b></td>');
        ShowHTML('          <td><b>Total</b></td>');
        ShowHTML('        </tr>');
        // Lista os registros selecionados para listagem
        $w_total = 0;
        foreach($RS as $row){ 
          ShowHTML('      <tr valign="top">');
          ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
          ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
          ShowHTML('        <td>'.f($row,'marca').'</td>');
          if ($w_classes[4]) {
            ShowHTML('        <td>'.nvl(f($row,'modelo'),'&nbsp;').'</td>');
            ShowHTML('        <td align="center">'.nvl(f($row,'vida_util'),'&nbsp').'</td>');
          }
          if ($w_classes[1]) {
            ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'lote_numero'),5),'&nbsp;').'</td>');
            ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'fabricacao'),5),'&nbsp;').'</td>');
          }
          if ($w_classes[1] || $w_classes[3]) {
            ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'validade'),5),'&nbsp;').'</td>');
            ShowHTML('        <td align="center">'.((f($row,'classe')==1||f($row,'classe')==3) ? f($row,'fator_embalagem') : '&nbsp;').'</td>');
            ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
          }
          ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
          ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_unitario'),10).'</td>');
          ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_total')).'</td>');
          ShowHTML('        </tr>');
          $w_total += f($row,'valor_total');
        }
        if (count($RS)>1) ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td colspan='.(5+$colspan).' align="right"><b>Total dos itens</b><td align="right">'.formatNumber($w_total).'</tr>');
        ShowHTML('    </table>');
      }
      ShowHTML('       </table>');

      ShowHTML('    <tr><td colspan=3><br><b>Arquivos</b>&nbsp;&nbsp;[<A class="box HL" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Anexos&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Anexos'.'&SG='.substr($SG,0,4).'ANEXO').'" title="Registra os anexos do documento.">Ajustar</A>]</td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center" height="1"></td></tr>');
      ShowHTML('    <tr><td colspan="3"><table width="100%" border="0">');
      // Recupera todos os registros para a listagem 
      $sql = new db_getDocumentoArquivo; $RS = $sql->getInstanceOf($dbms,$w_chave,null,null,null,$w_cliente);
      $RS = SortArray($RS,'ordem','asc','nome','asc');
      if (count($RS)==0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align=center><b>Arquivos não anexados</b></td></tr>');
      } else {
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <table class="tudo" width="100%" BORDER=1 bordercolor="#666666">');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td><b>Ordem</td>');
        ShowHTML('          <td><b>Título</td>');
        ShowHTML('          <td><b>Descrição</td>');
        ShowHTML('          <td><b>Tipo</td>');
        ShowHTML('          <td><b>KB</td>');
        ShowHTML('        </tr>');
        if (count($RS)==0) {
          // Se não foram selecionados registros, exibe mensagem 
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
        } else {
          // Lista os registros selecionados para listagem 
          foreach($RS as $row) {
            ShowHTML('      <tr valign="top">');
            ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
            ShowHTML('        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>');
            ShowHTML('        <td>'.Nvl(f($row,'descricao'),'---').'</td>');
            ShowHTML('        <td>'.f($row,'tipo').'</td>');
            ShowHTML('        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>');
            ShowHTML('      </tr>');
          } 
        } 
        ShowHTML('</table>');
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

  // Recupera os dados da solicitacao
  $sql = new db_getMtMovim; $RS_Solic = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,$w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O <> 'E') {
    $w_nome               = $_REQUEST['w_nome'];
    $w_material           = $_REQUEST['w_material'];
    $w_ordem              = $_REQUEST['w_ordem'];
    $w_quantidade         = $_REQUEST['w_quantidade'];
    $w_valor              = $_REQUEST['w_valor'];
    $w_validade           = $_REQUEST['w_validade'];
    $w_fabricacao         = $_REQUEST['w_fabricacao'];
    $w_fator              = $_REQUEST['w_fator'];
    $w_vida_util          = $_REQUEST['w_vida_util'];
    $w_lote               = $_REQUEST['w_lote'];
    $w_fabricante         = $_REQUEST['w_fabricante'];
    $w_modelo             = $_REQUEST['w_modelo'];
  } elseif (strpos('LI',$O)!==false) {
    $sql = new db_getMtEntItem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'ordem','asc','nome','asc'); 
    $w_proximo = count($RS)+1;
  } elseif (strpos('AE',$O)!==false) {
    $sql = new db_getMtEntItem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,$w_chave_aux,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_almoxarifado       = f($RS,'sq_almoxarifado');
    $w_situacao           = f($RS,'sq_sit_item');
    $w_material           = f($RS,'sq_material');
    $w_ordem              = f($RS,'ordem');
    $w_quantidade         = formatNumber(f($RS,'quantidade'),0);
    $w_valor              = formatNumber(f($RS,'valor_total'),2);
    $w_fator              = f($RS,'fator_embalagem');
    $w_validade           = formataDataEdicao(f($RS,'validade'));
    $w_fabricacao         = formataDataEdicao(f($RS,'fabricacao'));
    $w_vida_util          = f($RS,'vida_util');
    $w_lote               = f($RS,'lote_numero');
    $w_fabricante         = f($RS,'marca');
    $w_modelo             = f($RS,'modelo');
  } 

  // Recupera informações sobre o tipo do material ou serviço
  if (nvl($w_material,'')!='') {
    $sql = new db_getMatServ; $RS_Mat = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_material,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS_Mat as $row) { $RS_Mat = $row; break; }
    $w_classe    = f($RS_Mat,'classe');
    $w_nm_classe = f($RS_Mat,'nm_classe');

    // Recupera o código da situação inicial da movimentação
    $sql = new db_getMtSituacao; $RS_Sit = $sql->getInstanceOf($dbms,$w_cliente,(($w_classe==4) ? 'ENTMATPER' : 'ENTMATCON'),null,'S',null,null);
    if (count($RS_Sit)==0) {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: A tabela de situações precisa ser carrregada com pelo menos um registro relativo a entrada de material!");');
      retornaFormulario('w_tipo');
      ScriptClose();
      exit();
    } else {
      $RS_Sit = SortArray($RS_Sit,'sigla','asc');
      foreach($RS_Sit as $row){ $RS_Sit=$row; break;}
      $w_situacao = f($RS_Sit,'chave');
    }
    
  } 

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.f($RS_Menu,'nome').' - Itens</TITLE>');
  Estrutura_CSS($w_cliente);
  Estrutura_CSS($w_cliente);
  if (strpos('IA',$O)!==false) {
    ScriptOpen('JavaScript');
    checkBranco();
    FormataValor();
    FormataData();
    ValidateOpen('Validacao');
    Validate('w_ordem','Ordem','1','1','1','4','','0123456789');
    Validate('w_nome','Nome','1','','3','30','1','1');
    Validate('w_material','Material/Serviço','SELECT','1','1','18','','1');
    if (nvl($w_material,'')=='') {
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      CompValor('w_ordem','Ordem','>','0','1');  
      Validate('w_quantidade','Quantidade','1','1','1','18','','1');
      CompValor('w_quantidade','Quantidade','>','0','1');  
      Validate('w_valor','Valor total','VALOR','1','4','18','','0123456789.,');
      CompValor('w_valor','Valor total','>','0','zero');
      if ($w_classe==1||$w_classe==3) {
        Validate('w_validade','Data de validade','DATA',1,10,10,'','0123456789/');
        CompData('w_validade','Data de validade','>=',formataDataEdicao(time()),'Data atual');
        Validate('w_fator','Embalagem','1','1',1,4,'','0123456789');
        CompValor('w_fator','Fator de embalagem','>','0','0');
      } elseif ($w_classe==4) {
        Validate('w_vida_util','Vida útil','1','1',1,4,'','0123456789');
        CompValor('w_vida_util','Vida útil','>','0','0');
      }
      if ($w_classe==1) {
        Validate('w_lote','Lote n','','',1,20,'1','1');
        Validate('w_fabricacao','Data de fabricação','DATA',1,10,10,'','0123456789/');
        CompData('w_fabricacao','Data de fabricação','<',formataDataEdicao(time()),'Data atual');
      }
      if ($w_classe==1||$w_classe==3||$w_classe==4) {
        if ($w_classe==1||$w_classe==3) {
          Validate('w_fabricante','Marca','1','1',2,50,'1','1');
        } elseif ($w_classe==4) {
          Validate('w_fabricante','Fabricante','1','1',2,50,'1','1');
          Validate('w_modelo','Marca/Modelo','1','1',2,50,'1','1');
        }
      }
      Validate('w_almoxarifado','Almoxarifado','SELECT','1','1','18','','1');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
      if ($O=='I') ShowHTML('  theForm.Botao[2].disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='L'){
    BodyOpen('onLoad="this.focus();"');
  } elseif (strpos('IA',$O)!==false) {
    BodyOpen('onLoad="document.Form.w_ordem.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  // Exibe os dados da solicitação
  ShowHTML('<table border=1 width="100%" bgcolor="#FAEBD7"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td><table border=0 width="100%">');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Tipo: <b>'.f($RS_Solic,'nm_tp_mov').'</b></td>');
  ShowHTML('            <td colspan=2>Situação: <b>'.f($RS_Solic,'nm_sit').'</b></td>');
  ShowHTML('          </tr>');
  ShowHTML('          <tr><td colspan=3>Fornecedor: <b>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'sq_fornecedor'),$TP,f($RS_Solic,'nm_fornecedor')).'</b></td></tr>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Documento:<br><b>'.f($RS_Solic,'nm_tp_doc').' '.f($RS_Solic,'nr_doc').'</b></td>');
  ShowHTML('            <td>Data:<br><b>'.formataDataEdicao(f($RS_Solic,'dt_doc'),5).'</b></td>');
  ShowHTML('            <td>Valor:<br><b>'.formatNumber(f($RS_Solic,'vl_doc')).'</b></td>');
  ShowHTML('          </tr>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<table border="0" width="100%">');

  if ($O=='L') {
    ShowHTML('<tr><td>');
    ShowHTML('      <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('      <a accesskey="F" class="ss" href="javascript:this.status.value;" onClick="parent.$.fancybox.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>'.exportaOffice().'Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Item','ordem').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('U.M.','sg_unidade_medida').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Qtd','quantidade').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fator<br>Embal.','fator_embalagem').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Marca','marca').'</td>');
    ShowHTML('          <td colspan=2><b>Valores</td>');
    ShowHTML('          <td rowspan=2 class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Unit.','valor_unitario').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Total','valor_total').'</td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_total = 0;
      foreach($RS as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
        ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
        ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
        ShowHTML('        <td align="center">'.f($row,'fator_embalagem').'</td>');
        ShowHTML('        <td>'.f($row,'marca').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_unitario'),10).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_total')).'</td>');
        ShowHTML('        <td align="top" nowrap class="remover">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_entrada_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_entrada_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('        </tr>');
        $w_total += f($row,'valor_total');
      }
      if (count($RS)>1) ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=7 align="right"><b>Total dos itens</b><td align="right">'.formatNumber($w_total).'<td>&nbsp;</td></tr>');
    } 
    ShowHTML('    </table>');
    ShowHTML('  </td>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_situacao" value="'.$w_situacao.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>I</u>tem número:</b><br><input accesskey="I" type="text" name="w_ordem" class="STI" SIZE="5" MAXLENGTH="4" VALUE="'.nvl($w_ordem,$w_proximo).'" '.$w_Disabled.' style="text-align:center;"></td>');
    ShowHTML('          <td colspan=3><b><u>N</u>ome do material/serviço:</b><br><input '.$p_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="40" MAXLENGTH="100" VALUE="'.$w_nome.'">');
    ShowHTML('            <input class="stb" type="button" onClick="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_material\'; document.Form.submit();" name="Botao" value="Procurar">');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td colspan="4" TITLE="Selecione o material/serviço desejado na listagem ou procure por outro nome."><b>Material/Serviço:</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="w_material" '.$w_Disabled.' onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_material\'; document.Form.submit();">');
    ShowHTML('          <option value="">---');
    if (nvl($w_nome,'')!='' || $O=='A') {
      if (nvl($w_nome,'')!='') {
        $sql = new db_getMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,$w_nome,'S',null,null,null,null,null,null,null,null,null,null,null,null,null);
      } else {
        $sql = new db_getMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_material,null,null,null,'S',null,null,null,null,null,null,null,null,null,null,null,null,null);
      }
      $RS = SortArray($RS,'nome','asc'); 
    } else {
      $RS = array();
    }
    foreach ($RS as $row) {
      ShowHTML('          <option value="'.f($row,'chave').'" '.((nvl(f($row,'chave'),0)==nvl($w_material,0)) ? 'SELECTED' : '').'>'.f($row,'nome').' ('.f($row,'nm_unidade_medida').')');
    } 
    ShowHTML('          </select>');
    if (nvl($w_material,'')=='') {
      ShowHTML('      <tr><td colspan=4 align="center"><hr>');
    } else {
      ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan=4 align="center"><b>Classe do material: '.$w_classe.' - '.upper($w_nm_classe).'</b></td></tr>');
      ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td><b><u>Q</u>uantidade:</b><br><input accesskey="Q" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" '.$w_Disabled.' style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);"></td>');
      ShowHTML('        <td><b>$ <u>T</u>otal:</b><br><input type="text" '.$w_Disabled.' accesskey="T" name="w_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do item. O sistema calculará o valor unitário."></td>');
      if ($w_classe==1||$w_classe==3) {
        ShowHTML('        <td><b><u>V</u>alidade:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_validade" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_validade.'" onKeyDown="FormataData(this,event);" title="Data de validade do item."></td>');
        ShowHTML('        <td><b><u>F</u>ator de embalagem:</b><br><input type="text" '.$w_Disabled.' accesskey="F" name="w_fator" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.nvl($w_fator,f($row,'fator_embalagem')).'" style="text-align:right;" title="Define o múltiplo da quantidade a ser solicitada."></td>');
      } else {
        if ($w_classe==4) {
          ShowHTML('        <td><b><u>V</u>ida útil (anos):</b><br><input type="text" '.$w_Disabled.' accesskey="V" name="w_vida_util" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.nvl($w_vida_util,f($row,'vida_util')).'" style="text-align:right;" title="Vida útil do bem."></td>');
        }
        ShowHTML('<INPUT type="hidden" name="w_fator" value="1">');
      }
      ShowHTML('      </tr>');
      if ($w_classe==1) {
        ShowHTML('      <tr valign="top">');
        ShowHTML('        <td colspan=2><b><u>L</u>ote:</b><br><input '.$p_Disabled.' accesskey="L" type="text" name="w_lote" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_lote.'">');
        ShowHTML('        <td><b><u>F</u>abricação:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fabricacao" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fabricacao.'" onKeyDown="FormataData(this,event);" title="Data de fabricação do item."></td>');
        ShowHTML('      </tr>');
      }
      if ($w_classe==1||$w_classe==3||$w_classe==4) {
        ShowHTML('      <tr valign="top">');
        if ($w_classe==1||$w_classe==3) {
          ShowHTML('        <td colspan=2><b>M<u>a</u>rca:</b><br><input '.$p_Disabled.' accesskey="A" type="text" name="w_fabricante" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_fabricante.'">');
        } elseif ($w_classe==4) {
          ShowHTML('        <td colspan=2><b>F<u>a</u>bricante:</b><br><input '.$p_Disabled.' accesskey="A" type="text" name="w_fabricante" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_fabricante.'">');
          ShowHTML('        <td colspan=2><b><u>M</u>odelo:</b><br><input '.$p_Disabled.' accesskey="M" type="text" name="w_modelo" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_modelo.'">');
        }
        ShowHTML('      </tr>');
      }
      ShowHTML('      <tr>');
      SelecaoAlmoxarifado('Al<u>m</u>oxarifado para armazenamento:','M', 'Selecione o almoxarifado onde o material será armazenado.', $w_almoxarifado,'w_almoxarifado',null,null,4);
      ShowHTML('      <tr><td colspan=4 align="center"><hr>');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  $sql = new db_getMtMovim; $RS_Solic = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página 
    $w_ordem     = $_REQUEST['w_ordem'];
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif (strpos('LI',$O)!==false) {
    // Recupera todos os registros para a listagem 
    $sql = new db_getDocumentoArquivo; $RS = $sql->getInstanceOf($dbms,$w_chave,null,null,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
    $w_proximo = count($RS)+1;
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endereço informado 
    $sql = new db_getDocumentoArquivo; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_chave_aux,null,null,$w_cliente);
    foreach ($RS as $row) {
      $w_ordem     = f($row,'ordem');
      $w_nome      = f($row,'nome');
      $w_descricao = f($row,'descricao');
      $w_caminho   = f($row,'chave_aux');
    }
  } 
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.f($RS_Menu,'nome').' - Anexos</TITLE>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_ordem','Ordem','1','1','1','4','','0123456789');
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
    BodyOpenClean('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I' || $O=='A') {
    BodyOpenClean('onLoad="document.Form.w_ordem.focus()";');
  } else {
    BodyOpenClean('onLoad="this.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();

  // Exibe os dados da solicitação
  ShowHTML('<table border=1 width="100%" bgcolor="#FAEBD7"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td><table border=0 width="100%">');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Tipo: <b>'.f($RS_Solic,'nm_tp_mov').'</b></td>');
  ShowHTML('            <td colspan=2>Situação: <b>'.f($RS_Solic,'nm_sit').'</b></td>');
  ShowHTML('          </tr>');
  ShowHTML('          <tr><td colspan=3>Fornecedor: <b>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'sq_fornecedor'),$TP,f($RS_Solic,'nm_fornecedor')).'</b></td></tr>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Documento:<br><b>'.f($RS_Solic,'nm_tp_doc').' '.f($RS_Solic,'nr_doc').'</b></td>');
  ShowHTML('            <td>Data:<br><b>'.formataDataEdicao(f($RS_Solic,'dt_doc'),5).'</b></td>');
  ShowHTML('            <td>Valor:<br><b>'.formatNumber(f($RS_Solic,'vl_doc')).'</b></td>');
  ShowHTML('          </tr>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  ShowHTML('<table border="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('      <a accesskey="F" class="ss" href="javascript:this.status.value;" onClick="parent.$.fancybox.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Ordem</td>');
    ShowHTML('          <td><b>Título</td>');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      // Se não foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
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
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_caminho.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I' || $O=='A') {
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b></font>.</td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>O</u>rdem:</b><br><input accesskey="O" type="text" name="w_ordem" class="STI" SIZE="5" MAXLENGTH="4" VALUE="'.nvl($w_ordem,$w_proximo).'" '.$w_Disabled.' style="text-align:center;"></td>');
    ShowHTML('        <td><b><u>T</u>ítulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="OBRIGATÓRIO. Informe um título para o arquivo."></td>');
    ShowHTML('      <tr><td colspan="2"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="OBRIGATÓRIO. Descreva a finalidade do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    if ($w_caminho>'') {
      ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
    } 
    ShowHTML('      <tr><td align="center" colspan="2"><hr>');
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
  Rodape();
} 
// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = upper(trim($_REQUEST['w_tipo']));

  if ($w_tipo=='PDF') {
    headerPdf('Visualização de '.f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad="this.focus()"; ');
    CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);
    $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualEntrada($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  ScriptOpen('JavaScript');
  ShowHTML('  var comando, texto;');
  ShowHTML('  if (window.name!="content") {');
  ShowHTML('    $(".lk").html("<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela");');
  ShowHTML('  }');
  ScriptClose();
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
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML(VisualEntrada($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'MTENTMAT',$w_pagina.$par,$O);
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
    $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,5,
            null,null,null,null,null,null,null,null,null,null,
            $w_chave,null,null,null,null,null,null,
            null,null,null,null,null,null,null,null,null,null,null);
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
  if ($O=='V') $w_erro = ValidaPedido($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($w_sg_tramite!='CI') {
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EC' || $w_sg_tramite=='EE' || $w_ativo=='N') {
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
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualEntrada($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  //if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EC' || $w_sg_tramite=='EE' || $w_ativo=='N' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,4).'ENVIO',$w_pagina.$par,$O);
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
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EC' || $w_sg_tramite=='EE' || $w_ativo=='N') {
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
      if (!(substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EC' || $w_sg_tramite=='EE' || $w_ativo=='N')) {
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
  //}
  ShowHTML('</table>');
  Rodape();
} 

// =========================================================================
// Rotina de conclusão
// -------------------------------------------------------------------------
function Atender() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  //Recupera os dados da solicitacao de passagens e diárias
  $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,5,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $w_dados_pai      = explode('|@|',f($RS,'dados_pai'));
  $w_sq_menu_relac  = $w_dados_pai[3];
  if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
  $w_solic_pai      = f($RS,'sq_solic_pai');
  $w_chave_pai      = f($RS,'sq_solic_pai');
  $w_fundo_fixo     = f($RS,'fundo_fixo');
  $w_nota_conclusao = f($RS,'nota_conclusao');
  $w_financeiro     = f($RS,'sq_financeiro');
  $w_rubrica        = f($RS,'sq_projeto_rubrica');
  $w_lancamento     = f($RS,'sq_tipo_lancamento');
  if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
  
  if (nvl($w_troca,'')!='') {
    // Se for recarga da página
    $w_sq_menu_relac  = $_REQUEST['w_sq_menu_relac'];    
    if($w_sq_menu_relac=='CLASSIF') {
      $w_solic_pai    = '';
    } else {
      $w_solic_pai    = $_REQUEST['w_solic_pai'];
    }
    $w_chave_pai      = $_REQUEST['w_chave_pai'];    
    $w_financeiro     = $_REQUEST['w_financeiro'];
    $w_rubrica        = $_REQUEST['w_rubrica'];
    $w_lancamento     = $_REQUEST['w_lancamento'];
    $w_fundo_fixo     = $_REQUEST['w_fundo_fixo'];
    $w_nota_conclusao = $_REQUEST['w_nota_conclusao'];
  }

  if ($w_solic_pai>'') {
    // Recupera as possibilidades de vinculação financeira
    $sql = new db_getMTFinanceiro; $RS_Financ = $sql->getInstanceOf($dbms,$w_cliente,$w_menu,$w_solic_pai,null,null,null,null,null,null,null,null);
  }
  
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  modulo();
  formatavalor();
  ValidateOpen('Validacao');

  if(nvl($w_sq_menu_relac,'')!='') {
    Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
    if ($w_sq_menu_relac=='CLASSIF') {
      Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
    } else {
      Validate('w_solic_pai','Vinculação','SELECT',1,1,18,1,1);
    }
  }
  if (count($RS_Financ)>1) {
    Validate('w_rubrica','Rubrica','SELECT',1,1,18,'','0123456789');
    Validate('w_lancamento','Tipo de lançamento','SELECT',1,1,18,'','0123456789');
  }
  
  ShowHTML('  for (ind=1; ind < theForm["w_quantidade[]"].length; ind++) {');
  Validate('["w_quantidade[]"][ind]','Quantidade autorizada','VALOR','1',1,18,'','0123456789.');
  CompValor('["w_quantidade[]"][ind]','Quantidade autorizada','<=','["w_qtd_ant[]"][ind]','Quantidade solicitada');
  ShowHTML('  }');
  Validate('w_nota_conclusao','Nota de conclusão','','','1','2000','1','1');
  ShowHTML('  if (theForm.w_fundo_fixo[1].checked && theForm.w_nota_conclusao.value.length>0) {');
  ShowHTML('    alert("Nota de conclusão pode ser preenchida somente se o pagamento for por fundo fixo!");');
  ShowHTML('    theForm.w_nota_conclusao.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualEntrada($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,4).'ATEND',$w_pagina.$par,'T');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_sq_solicitacao_item[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_quantidade[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_qtd_ant[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_material[]" value="">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="100%" border="0">');

  ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Vinculação Orçamentária-Financeira</td></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr valign="top">');
  selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
  if(Nvl($w_sq_menu_relac,'')!='') {
    ShowHTML('          <tr valign="top">');
    if ($w_sq_menu_relac=='CLASSIF') {
      SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null,null,'<BR />',2);
    } else {
      SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_solic_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_solic_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_solicitante\'; document.Form.submit();"',$w_chave_pai,'<BR />',2);
    }
  }
  if ($w_solic_pai>'') {
    if (count($RS_Financ)>1) {
      ShowHTML('      <tr valign="top">');
      SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_rubrica,$w_solic_pai,'T','w_rubrica','CLFINANC','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_rubrica\'; document.Form.submit();"');
      SelecaoTipoLancamento('<u>T</u>ipo de lançamento:','T','Selecione na lista o tipo de lançamento adequado.',$w_lancamento,null,$w_cliente,'w_lancamento','MTEN'.str_pad($w_solic_pai,10,'0',STR_PAD_LEFT).str_pad($w_rubrica,10,'0',STR_PAD_LEFT).'T',null);
    } elseif (count($RS_Financ)==1) {
      foreach($RS_Financ as $row) { $RS_Financ = $row; break; }
      ShowHTML('<INPUT type="hidden" name="w_financeiro" value="'.f($RS_Financ,'chave').'">');
    }
  }
  
  ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Quantidades Autorizadas</td></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  $sql = new db_getMtEntItem; $RS1 = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
  $RS1 = SortArray($RS1,'nm_tipo_material','asc','nome','asc'); 
  ShowHTML('<tr><td colspan=4><b>Informe para cada item a quantidade autorizada para compra:</b>');
  ShowHTML('<tr><td align="center" colspan=4>');  
  ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td rowspan=2><b>Tipo</td>');
  ShowHTML('          <td rowspan=2><b>Código</td>');
  ShowHTML('          <td rowspan=2><b>Nome</td>');
  ShowHTML('          <td rowspan=2><b>U.M.</td>');
  ShowHTML('          <td colspan=2><b>Quantidade</td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td><b>Solicitada</td>');
  ShowHTML('          <td><b>Autorizada</td>');
  ShowHTML('        </tr>');
  // Lista os registros selecionados para listagem
  $i=1;
  foreach($RS1 as $row){
    $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
    ShowHTML('        <td>'.f($row,'nm_tipo_material').'</td>');
    ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');
    ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
    ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
    ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
    ShowHTML('<INPUT type="hidden" name="w_sq_solicitacao_item[]" value="'.f($row,'chave').'">');
    ShowHTML('<INPUT type="hidden" name="w_qtd_ant[]" value="'.formatNumber(f($row,'quantidade'),0).'">');
    ShowHTML('<INPUT type="hidden" name="w_material[]" value="'.f($row,'sq_material').'">');
    ShowHTML('        <td align="center"><input type="text" name="w_quantidade[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.nvl($_REQUEST['w_quantidade'][$i],Nvl(formatNumber(f($row,'quantidade'),0),0)).'" style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);" title="Informe a  quantidade autorizada para compra."></td>');
    ShowHTML('        </tr>');
    $i++;
  }
  ShowHTML('    </table>');
  ShowHTML('      <tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Dados Gerais</td></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  MontaRadioNS('<b>Pagamento por fundo fixo? <font color="#BC3131"></font></b>',$w_fundo_fixo,'w_fundo_fixo');
  ShowHTML('    <tr><td colspan="4"><b>Nota d<u>e</u> conclusão: <font color="#BC3131">(preencher apenas se o pagamento por fundo fixo)</font></b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Se pagamento por fundo fixo, você pode registrar uma nota de conclusão opcional.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  ShowHTML('    <tr><td colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Atender">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
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
  $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,5,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}  
  $w_nota_conclusao = nvl(f($RS,'nota_conclusao'),f($RS,'justificativa'));
  $w_fundo_fixo     = f($RS,'fundo_fixo');
  
  if (nvl($w_troca,'')!='') {
    $w_nota_conclusao = $_REQUEST['w_nota_conclusao'];
  }

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ScriptOpen('JavaScript');
  modulo();
  formatavalor();
  ValidateOpen('Validacao');
  if ($w_fundo_fixo=='N') {
    if ($w_pa == 'S') {
      //Vincula a compra com o módulo de protocolo
      Validate('w_nota_conclusao','Detalhamento do assunto','1','1','1','2000','1','1');
    } else {
      Validate('w_nota_conclusao','Nota de conclusão','1','','1','500','1','1');
    }
  }
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualEntrada($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,4).'CONC&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" method="POST">');
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
  ShowHTML('<INPUT type="hidden" name="w_fundo_fixo" value="'.$w_fundo_fixo.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="100%" border="0">');
  if ($w_fundo_fixo=='N') {
    if ($w_pa == 'S') {
      //Se ABDI, vincula a viagem com o módulo de protocolo
      ShowHTML('    <tr><td colspan=3><font size=2><b>DADOS PARA GERAÇÃO DO PROTOCOLO</b></font></td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('    <tr valign="top"><td width="30%"><b>Detalhamento do assunto:</b><td title="Descreva de forma objetiva o conteúdo do documento."><textarea ' . $w_Disabled . ' accesskey="O" name="w_nota_conclusao" class="STI" ROWS=5 cols=75>' . $w_nota_conclusao . '</TEXTAREA></td>');
    } else {
      ShowHTML('    <tr><td colspan=4><b><u>N</u>ota de conclusão:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Se desejar, registre observações a respeito desta solicitação.">'.$w_nota_conclusao.'</TEXTAREA></td>');
    }
  }
  ShowHTML('    <tr><td width="30%"><b><U>A</U>ssinatura Eletrônica:<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  $sql = new db_getMtMovim; $RSM = $sql->getInstanceOf($dbms,$w_cliente,$_SESSION['SQ_PESSOA'],$SG,5,
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
        $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$p_solic,'PDGERAL');
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
    if (!count($RS)==0) {
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
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'MTENTMAT':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Recupera o código da situação inicial da movimentação
        $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,'ENTRADA',null,'S',null,null);
        if (count($RS)==0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: A tabela de situações precisa ser carrregada com pelo menos um registro relativo a entrada de material!");');
          retornaFormulario('w_tipo');
          ScriptClose();
          exit();
        } else {
          $RS = SortArray($RS,'sigla','asc');
          foreach($RS as $row){$RS=$row; break;}
          $w_situacao = f($RS,'chave');
        }
        // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
        if ($O=='E') {
          $sql = new db_getDocumentoArquivo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],null,null,null,$w_cliente);
          foreach ($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          }
        } 
        $SQL = new dml_putMtEntrada; $SQL->getInstanceOf($dbms,$O,$w_cliente,$w_usuario,$_REQUEST['w_chave'],$_REQUEST['w_copia'],
          $_REQUEST['w_fornecedor'],$_REQUEST['w_tipo'],$w_situacao,$_REQUEST['w_solicitacao'],$_REQUEST['w_documento'],
          $_REQUEST['w_prevista'],$_REQUEST['w_efetiva'],$_REQUEST['w_sq_tipo_documento'],$_REQUEST['w_numero'],$_REQUEST['w_data'],
          $_REQUEST['w_valor'],$_REQUEST['w_armazenamento'],$_REQUEST['w_numero_empenho'],$_REQUEST['w_data_empenho'],&$w_chave_nova);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href="'.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$w_chave_nova.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'";');
          ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'MTENITEM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {

        $SQL = new dml_putMtEntItem; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_almoxarifado'],
                $_REQUEST['w_situacao'],$_REQUEST['w_ordem'],$_REQUEST['w_material'],$_REQUEST['w_quantidade'],$_REQUEST['w_valor'],$_REQUEST['w_fator'],
                $_REQUEST['w_validade'],$_REQUEST['w_fabricacao'],$_REQUEST['w_vida_util'],$_REQUEST['w_lote'],$_REQUEST['w_fabricante'],
                $_REQUEST['w_modelo']);
        
        ScriptOpen('JavaScript');
        ShowHTML('  location.href="'.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'";');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }     
      break;
    case 'MTENANEXO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
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
                $sql = new db_getDocumentoArquivo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],null,null,$w_cliente);
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
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
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
            $sql = new db_getDocumentoArquivo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],null,null,$w_cliente);
            foreach ($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
            }
          } 
          $SQL = new dml_putDocumentoArquivo; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_ordem'],$_REQUEST['w_tipo_arquivo'],$_REQUEST['w_descricao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!");');
          ScriptClose();
          exit();
        } 
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu 
        ShowHTML('  location.href="'.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'";');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;      
    case 'MTENENVIO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $sql = new db_getMtMovim;
        $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, $SG, 3, null, null, null, null, null, null, null, null, null, null,
                        $_REQUEST['w_chave'], null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
        foreach ($RS as $row) {$RS = $row;break;}
        if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!");');
          ScriptClose();
          retornaFormulario('w_observacao');
          exit();
        } else {
          if ($_REQUEST['w_envio'] == 'N') {
            $SQL = new dml_putSolicEnvio;
            $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'], null,
                    $_REQUEST['w_envio'], $_REQUEST['w_despacho'], null, null, null, null);
          } else {
            $SQL = new dml_putSolicEnvio;
            $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'], $_REQUEST['w_novo_tramite'],
                    $_REQUEST['w_envio'], $_REQUEST['w_despacho'], null, null, null, null);
          }
          //Rotina para gravação da imagem da versão da solicitacão no log.
          if ($_REQUEST['w_tramite'] != $_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData;
            $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS, 'sigla');
            if ($w_sg_tramite == 'CI') {
              $w_html = VisualEntrada($_REQUEST['w_chave'], 'L', $w_usuario, null, '1');
              CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
            }
          }
          // Envia e-mail comunicando o envio
          SolicMail($_REQUEST['w_chave'], 2);
          // Se for envio da fase de cadastramento, remonta o menu principal
          ScriptOpen('JavaScript');
          ShowHTML('  location.href="' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '";');
          ScriptClose();
        }
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'MTENATEND':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
                $_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou o pedido para fase de execução!");');
          ScriptClose();
          exit();
        } else {
          // Grava as quantidades constantes do documento
          $SQL = new dml_putMtEntItem; 
          for ($i=0; $i<=count($_POST['w_sq_solicitacao_item'])-1; $i=$i+1) {
            if ($_REQUEST['w_sq_solicitacao_item'][$i]>'') {
              $SQL->getInstanceOf($dbms,'C',$_REQUEST['w_sq_solicitacao_item'][$i],$_REQUEST['w_chave'],null,Nvl($_REQUEST['w_material'][$i],0),
                  Nvl($_REQUEST['w_quantidade'][$i],0),Nvl($_REQUEST['w_qtd_ant'][$i],0),null,null,null);
            }
          }

          // Grava vinculação orçamentária-financeira
          $SQL = new dml_putMTGeral; $SQL->getInstanceOf($dbms,'T',$_REQUEST['w_chave'],$_REQUEST['w_menu'],null,
            null,null,null,$_REQUEST['w_plano'],explodeArray($_REQUEST['w_objetivo']),$_REQUEST['w_sqcc'],
            $_REQUEST['w_solic_pai'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
            $_REQUEST['w_financeiro'],$_REQUEST['w_rubrica'],$_REQUEST['w_lancamento'],null,&$w_chave_nova,null);
            
          // Grava tipo de pagamento e nota de conclusão
          $SQL = new dml_putMTDados; $SQL->getInstanceOf($dbms,'AUTORIZ',$_REQUEST['w_chave'],null,null,null,null,null,null,null,
            null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
            $_REQUEST['w_nota_conclusao'],$_REQUEST['w_fundo_fixo']);
          /*
          if ($_REQUEST['w_fundo_fixo']=='S') {
            // Conclui a solicitação
            $SQL = new dml_putSolicConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
                $_SESSION['SQ_PESSOA'],$_REQUEST['w_nota_conclusao'],null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_fundo_fixo']);
          }
          */
          ScriptOpen('JavaScript');
          ShowHTML('  location.href="'.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'";');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'MTENCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
                $_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou o pedido para fase de execução!");');
          ScriptClose();
          exit();
        } else {
          if ($_REQUEST['w_fundo_fixo']=='N') {
            // Grava o protocolo somente se não for fundo fixo
            $SQL = new dml_putMTDados; $SQL->getInstanceOf($dbms,'PROT',$_REQUEST['w_chave'],null,$_REQUEST['w_numero_processo'],null,null,null,null,null,
              null,null,null,null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_protocolo'],null,null,null,null);
          }
            
          // Conclui a solicitação
          $SQL = new dml_putSolicConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
              $_SESSION['SQ_PESSOA'],$_REQUEST['w_nota_conclusao'],null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_fundo_fixo']);
          // Envia e-mail comunicando a conclusão
          SolicMail($_REQUEST['w_chave'],3);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href="'.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'";');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
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
  case 'INICIAL':           Inicial(); break;
  case 'GERAL':             Geral(); break;
  case 'ITENS':             Itens(); break;
  case 'ANEXOS':            Anexos(); break;
  case 'VISUAL':            Visual(); break;
  case 'EXCLUIR':           Excluir(); break;
  case 'ENVIO':             Encaminhamento(); break;
  case 'ATENDER':           Atender(); break;
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

