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
include_once($w_dir_volta.'classes/sp/db_getCLSolicItem.php');
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
include_once($w_dir_volta.'classes/sp/dml_putMtEntArm.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'funcoes/selecaoAlmoxarifado.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once($w_dir_volta.'funcoes/selecaoLocalSubordination.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServSubord.php');
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
//                   = S   : Estorno
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

$w_assinatura   = $_REQUEST['w_assinatura'];
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
  if ($P1==3 || $SG=='MTENTMAT') $O='P'; else $O='L';
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'S': $w_TP=$TP.' - Estorno';     break;
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

// Verifica se o cliente contratou os módulo de protocolo e financeiro
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null);
$w_mod_ac = 'N';
$w_mod_co = 'N';
$w_mod_fn = 'N';
$w_mod_pa = 'N';
foreach($RS as $row) {
  switch (f($row,'sigla')) {
    case 'AC': $w_mod_ac = 'S'; break;
    case 'CO': $w_mod_co = 'S'; break;
    case 'FN': $w_mod_fn = 'S'; break;
    case 'PA': $w_mod_pa = 'S'; break;
  }
}

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
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
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
        Validate('p_proponente','Material','','','2','30','1','1');
        Validate('p_palavra','Fornecedor','','','2','30','1','1');
        Validate('p_ini_i','Início','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Fim','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != "" && theForm.p_ini_f.value == "") || (theForm.p_ini_i.value == "" && theForm.p_ini_f.value != "")) {');
        ShowHTML('     alert ("Informe ambas as datas ou nenhuma delas!");');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Início','<=','p_ini_f','Fim');
      }
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
  }
  if ($w_Troca>'') {
    // Se for recarga da página
    BodyOpen('onLoad="document.Form.'.$w_Troca.'.focus();"');
  } elseif (strpos('CP',$O)!==false) {
    BodyOpen('onLoad="document.Form.p_proponente.focus()";');
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
      ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Fornecedor','nm_fornecedor_ind').'</b></td>');
      ShowHTML('          <td colspan="4"><b>Documento</b></td>');
      ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Armazenamento','armazenamento').'</b></td>');
      ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Situação','nm_sit').'</b></td>');
      ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Itens','qt_itens').'</b></td>');
      ShowHTML('          <td rowspan="2" class="remover"><b>Operações</b></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_doc').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Número','nr_doc').'</b></td>');
      ShowHTML ('         <td><b>'.LinkOrdena('Emissão','dt_doc').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Valor','valor_doc').'</b></td>');
      ShowHTML('        </tr>');
      
    } else {
      ShowHTML('          <td rowspan="2"><b>Fornecedor</b></td>');
      ShowHTML('          <td colspan="4"><b>Documento</b></td>');
      ShowHTML('          <td rowspan="2"><b>Armazenamento</b></td>');
      ShowHTML('          <td rowspan="2"><b>Situação</b></td>');
      ShowHTML('          <td rowspan="2"><b>Itens</b></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Tipo</b></td>');
      ShowHTML('          <td><b>Documento</b></td>');
      ShowHTML ('         <td><b>Data</b></td>');
      ShowHTML('          <td><b>Valor</b></td>');
      ShowHTML('        </tr>');
    }
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=13 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.(($w_tipo=='WORD') ? f($row,'nm_res_fornecedor') : ExibePessoa('../',$w_cliente,f($row,'sq_fornecedor'),$TP,f($row,'nm_res_fornecedor'))).'</td>');
        ShowHTML('        <td title="'.f($row,'nm_tp_doc').'">'.f($row,'sg_tp_doc').'</td>');
        ShowHTML('        <td>'.f($row,'nr_doc').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'dt_doc'),5).'</td>');
        ShowHTML('        <td align="right" width="1%">&nbsp;'.formatNumber(f($row,'vl_doc'),2).'&nbsp;</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'armazenamento'),5).'</td>');
        ShowHTML('        <td>'.f($row,'nm_sit').'</td>');
        ShowHTML('        <td align="right" width="1%">&nbsp;'.formatNumber(f($row,'qt_itens'),0).'&nbsp;</td>');
        if ($w_tipo!='WORD') {
          ShowHTML('        <td class="remover" width="1%" nowrap>&nbsp;');
          if (strpos('NA,ES',f($row,'sg_sit'))!==false) {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_mtentrada').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais da entrada">AL</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_mtentrada').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão da entrada.">EX</A>&nbsp');
          } elseif (f($row,'qt_saidas')==0) {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Estorno&R='.$w_pagina.$par.'&O=S&w_chave='.f($row,'sq_mtentrada').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Estorno da entrada.">Estornar</A>&nbsp');
          }
          ShowHTML('          <a class="HL" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_copia='.f($row,'sq_mtentrada').MontaFiltro('GET').'" title="Gera um novo registro a partir das informações deste.">CO</a>&nbsp;');
          ShowHTML('          <a class="HL" href="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_mtentrada').'&SG='.f($row,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe os dados da entrada.">VS</a>&nbsp;');
        } 
        ShowHTML('        &nbsp;</td>');
        ShowHTML('      </tr>');
        $w_parcial += f($row,'valor');
      } 
      $w_colspan=4;
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
        foreach($RS as $row) $w_total += f($row,'valor');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
        ShowHTML('          <td colspan='.$w_colspan.' align="right"><b>Total da listagem&nbsp;</td>');
        ShowHTML('          <td align="right"><b>'.formatNumber($w_total).'&nbsp;</td>');
        ShowHTML('          <td colspan=4>&nbsp;</td>');
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
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('   <tr><td valign="top" colspan="2">');
    ShowHTML('   <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      ShowHTML('     <tr valign="top">');
      ShowHTML('       <td><b><U>M</U>aterial:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="30" value="'.$p_proponente.'"></td>');
      ShowHTML('       <td><b>F<u>o</u>rnecedor:<br><INPUT ACCESSKEY="F" TYPE="text" class="sti" NAME="p_palavra" VALUE="'.$p_palavra.'" SIZE="25" MaxLength="30">');
      ShowHTML('     </tr>');
      ShowHTML('     <tr>');
      selecaoTipoMatServSubord('<u>T</u>ipo de material/serviço:','S','Selecione o grupo/subgrupo de material/serviço desejado.',null,$p_pais,'p_pais','ENTMAT',null);
      ShowHTML('     </tr>');
      ShowHTML('     <tr>');
      ShowHTML('       <td valign="top"><b><u>D</u>ata de recebimento ou armazenamento entre:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="D" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    } 
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for cópia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar cópia">');
    } else {
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Incluir">');
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
    $w_codigo                   = $_REQUEST['w_codigo'];
    $w_solicitacao              = $_REQUEST['w_solicitacao'];
    $w_documento                = $_REQUEST['w_documento'];
    $w_fornecedor               = $_REQUEST['w_fornecedor'];
    $w_fornecedor_nm            = $_REQUEST['w_fornecedor_nm'];
    $w_protocolo                = $_REQUEST['w_protocolo'];
    $w_protocolo_nm             = $_REQUEST['w_protocolo_nm'];
    $w_tipo                     = $_REQUEST['w_tipo'];
    $w_prevista                 = $_REQUEST['w_prevista'];
    $w_efetiva                  = $_REQUEST['w_efetiva'];
    $w_armazenamento            = $_REQUEST['w_armazenamento'];
    $w_sq_tipo_documento        = $_REQUEST['w_sq_tipo_documento'];
    $w_numero                   = $_REQUEST['w_numero'];
    $w_executor                 = $_REQUEST['w_executor'];
    $w_data                     = $_REQUEST['w_data'];
    $w_valor                    = $_REQUEST['w_valor'];
    $w_sq_tipo_doc_ant          = $_REQUEST['w_sq_tipo_doc_ant'];
    $w_numero_ant               = $_REQUEST['w_numero_ant'];
    $w_data_ant                 = $_REQUEST['w_data_ant'];
    $w_valor_ant                = $_REQUEST['w_valor_ant'];
    $w_situacao                 = $_REQUEST['w_situacao'];
  } elseif (strpos('AEV',$O)!==false || $w_copia>'') {
    // Recupera os dados da entrada
    if ($w_copia>'') {
      $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
        null,null,null,null,null,null,null,null,null,null,$w_copia,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,null,null,null);
    } else {
      $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
        null,null,null,null,null,null,null,null,null,null,$w_chave,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,null,null,null);
    }
    if (count($RS)>0) {
      foreach($RS as $row){$RS=$row; break;}
      $w_solicitacao              = f($RS,'sq_siw_solicitacao');
      $w_documento                = f($RS,'sq_lancamento_doc');
      $w_executor                 = f($RS,'cadastrador');
      $w_fornecedor               = f($RS,'sq_fornecedor');
      $w_fornecedor_nm            = f($RS,'nm_fornecedor');
      $w_tipo                     = f($RS,'sq_tipo_movimentacao');
      $w_prevista                 = formataDataEdicao(f($RS,'recebimento_previsto'));
      $w_efetiva                  = formataDataEdicao(f($RS,'recebimento_efetivo'));
      $w_armazenamento            = formataDataEdicao(f($RS,'armazenamento'));
      $w_sq_tipo_documento        = f($RS,'sq_tipo_documento');
      $w_nm_tipo_documento        = f($RS, 'nm_tp_doc');
      $w_numero                   = f($RS,'nr_doc');
      $w_data                     = formataDataEdicao(f($RS,'dt_doc'));
      $w_valor                    = formatNumber(f($RS,'vl_doc'));
      $w_sq_tipo_doc_ant          = f($RS,'sq_tipo_documento');
      $w_numero_ant               = f($RS,'nr_doc');
      $w_data_ant                 = formataDataEdicao(f($RS,'dt_doc'));
      $w_valor_ant                = formatNumber(f($RS,'vl_doc'));
      $w_situacao                 = f($RS,'sq_mtsituacao');
    } 
  } 
  
  // Se não puder cadastrar para outros, carrega os dados do usuário logado
  if (nvl($w_protocolo,'')!='') {
    // Se receber o protocolo, recupera as informações do lançamento financeiro a ele associado
    $w_prefixo  = substr($_REQUEST['w_protocolo'],  0, 5)+0;
    $w_nr_prot  = substr($_REQUEST['w_protocolo'],  6, 6)+0;
    $w_ano      = substr($_REQUEST['w_protocolo'], 13, 4)+0;

    $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, 'PADCAD');
    $sql = new db_getSolicList; $RS1 = $sql->getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'PROTOCOLO',3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $w_prefixo, $w_nr_prot, $p_uf, $w_ano, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, $p_sq_orprior);

    $w_erro = true;
    foreach($RS1 as $row1) {
      if (f($row1,'sg_modulo')=='FN') {
        $sql = new db_getLancamentoDoc; $RS2 = $sql->getInstanceOf($dbms,f($row1,'sq_siw_solicitacao'),null,null,null,null,null,null,'DOCS');
        if (count($RS2)>0) {
          $RS2 = SortArray($RS2,'data','asc');
          foreach($RS2 as $row2) {
            $sql = new db_getSolicData; $RS3 = $sql->getInstanceOf($dbms,f($row1,'sq_siw_solicitacao'),f($row1,'sigla'));
            $w_solicitacao              = f($RS3, 'sq_siw_solicitacao');
            $w_executor                 = f($RS,'executor_fn');
            $w_fornecedor               = f($RS3, 'pessoa');
            $w_fornecedor_nm            = f($RS3, 'nm_pessoa_resumido');
            $w_documento                = f($row2, 'sq_lancamento_doc');
            $w_tipo                     = nvl($w_tipo,f($row2, 'sq_tipo_documento'));
            $w_prevista                 = nvl($w_prevista,formataDataEdicao(f($row2, 'data')));
            $w_efetiva                  = nvl($w_efetiva,formataDataEdicao(f($row2, 'data')));
            $w_sq_tipo_documento        = nvl($w_sq_tipo_documento,f($row2, 'sq_tipo_documento'));
            $w_numero                   = nvl($w_numero,f($row2, 'numero'));
            $w_data                     = nvl($w_data,formataDataEdicao(f($row2, 'data')));
            $w_valor                    = nvl($w_valor,formatNumber(f($row2, 'valor')));
            $w_sq_tipo_doc_ant          = f($row2,'sq_tipo_documento');
            $w_numero_ant               = f($row2,'numero');
            $w_data_ant                 = formataDataEdicao(f($row2,'data'));
            $w_valor_ant                = formatNumber(f($row2,'valor'));
            if ($w_fornecedor==nvl($_REQUEST['w_fornecedor'],$w_fornecedor)) {
              $w_erro = false;
              break;
            }
          }
        }
        if (!$w_erro) break;
      }
    }
    $sql = new db_getTipoMovimentacao; $RS4 = $sql->getInstanceOf($dbms,$w_cliente,null,null,'S',null,null,null,null,null,'S',null);
    foreach($RS4 as $row4) {
      if (lower(f($row4,'nome'))=='entrada orçamentária') {
        $w_tipo = f($row4,'chave');
        break;
      }
    }
    if ($w_erro) {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: Protocolo informado não tem lançamento financeiro vinculado!");');
      ScriptClose();
    }
  }
  
  // Itens
  $sql = new db_getMtEntItem; $RS_Itens = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null);
  $RS_Itens = SortArray($RS_Itens,'ordem','asc','nome','asc'); 

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
    if ($O=='I' || $w_mod_fn=='N') {
      // Dados não editáveis se estiver ligado a um lançamento financeiro
      Validate('w_fornecedor_nm','Fornecedor','','1','1','100','1','1');
      Validate('w_sq_tipo_documento','Tipo do documento', '1', '1', '1', '18', '', '0123456789');
      Validate('w_numero','Número do documento', '1', '1', '1', '30', '1', '1');
      Validate('w_data','Data do documento', 'DATA', '1', '10', '10', '', '0123456789/');
      CompData('w_data','Data do documento','<=',formataDataEdicao(time()),'Data atual');
      Validate('w_valor','Valor do documento', 'VALOR', '1', 4, 18, '', '0123456789.,');
    }
    Validate('w_tipo','Tipo da movimentação', 'SELECT', '1', '1', '18', '', '0123456789');
    //Validate('w_prevista','Data prevista para recebimento','DATA',1,10,10,'','0123456789/');
    Validate('w_efetiva','Data efetiva de recebimento','DATA',1,10,10,'','0123456789/');
    Validate('w_executor','Responsável pelo pagamento','SELECT',1,1,18,'','0123456789');
    ShowHTML('  document.Form.Botao[0].disabled = true;');
    ShowHTML('  document.Form.Botao[1].disabled = true;');
  } 
  ValidateClose();
  ShowHTML('  function ajusta(inicial) {');
  ShowHTML('    for (ind=inicial; ind < document.Form1["w_local[]"].length; ind++) {');
  ShowHTML('      if (document.Form1["w_local[]"][ind].selectedIndex==0) {');
  ShowHTML('        document.Form1["w_local[]"][ind].selectedIndex = document.Form1["w_local[]"][inicial].selectedIndex;');
  ShowHTML('      }');
  ShowHTML('    }');
  ShowHTML('  }');
  
  ValidateOpen('Validacao1');
  ShowHTML('  for (ind=1; ind < theForm["w_local[]"].length; ind++) {');
  Validate('["w_local[]"][ind]','local de armazenamento','','1',1,18,'','0123456789');
  ShowHTML('  }');
  Validate('w_armazenamento','Data de armazenamento', 'DATA', '1', '10', '10', '', '0123456789/');
  CompData('w_armazenamento','Data de armazenamento','<=',formataDataEdicao(time()),'data atual');
  CompData('w_armazenamento','Data de armazenamento','>=','w_efetiva','Data efetiva de recebimento');
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpen('onLoad="this.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($O!='I') {
    // Exibe os dados da solicitação
    ShowHTML('<table border=1 width="100%" bgcolor="#FAEBD7"><tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('      <tr><td><table border=0 width="100%">');
    ShowHTML('          <tr><td colspan=3>Fornecedor: <b>'.ExibePessoa('../',$w_cliente,$w_fornecedor,$TP,$w_fornecedor_nm).'</b></td></tr>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td>Documento:<br><b>'.$w_nm_tipo_documento.' '.$w_numero.'</b></td>');
    ShowHTML('            <td>Emissão:<br><b>'.$w_data.'</b></td>');
    ShowHTML('            <td>Valor:<br><b>'.$w_valor.'</b></td>');
    ShowHTML('          </tr>');
    ShowHTML('      </table>');
    ShowHTML('    </TABLE>');
    ShowHTML('</table>');
  }

  if (strpos('IAEV',$O)!==false) {
    ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_solicitacao" value="'.$w_solicitacao.'">');
    ShowHTML('<INPUT type="hidden" name="w_protocolo_siw" value="'.$w_protocolo_siw.'">');
    ShowHTML('<INPUT type="hidden" name="w_documento" value="'.$w_documento.'">');
    ShowHTML('<INPUT type="hidden" name="w_armazenamento" value="'.$w_armazenamento.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tipo_doc_ant" value="'.$w_sq_tipo_doc_ant.'">');
    ShowHTML('<INPUT type="hidden" name="w_numero_ant" value="'.$w_numero_ant.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_ant" value="'.$w_data_ant.'">');
    ShowHTML('<INPUT type="hidden" name="w_valor_ant" value="'.$w_valor_ant.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=4 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4 valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4>Os dados deste bloco serão utilizados para identificação da entrada de material, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    if ($O=='I' || $w_mod_fn=='N') {
      SelecaoPessoaOrigem('<u>F</u>ornecedor:', 'P', 'Clique na lupa para selecionar o fornecedor.', $w_fornecedor, null, 'w_fornecedor', null, null, null);
      if ($w_mod_pa=='S') {
        SelecaoProtocolo('Recuperar a partir do n<u>ú</u>mero do protocolo:','U','Selecione o protocolo de pagamento.',$w_protocolo,null,'w_protocolo',$SG,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_protocolo\'; document.Form.submit();"',2);
      }
      /*
      if ($w_mod_ac=='S' || $w_mod_co=='S' || $w_mod_fn=='S' || $w_mod_pa=='S') {
        ShowHTML('          <td><b>Recuperar a partir do <u>c</u>ódigo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_codigo.'" title="Informe o código do documento."></td>');
      }
      */
      ShowHTML('      <tr valign="top">');
      SelecaoTipoDocumento('<u>D</u>ocumento:','D', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,null,'w_sq_tipo_documento',$SG,null);
      ShowHTML('          <td><b><u>N</u>úmero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o número do documento."></td>');
      ShowHTML('          <td><b><u>E</u>missão:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data do documento.">'.ExibeCalendario('Form','w_data').'</td>');
      ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_fornecedor" value="'.$w_fornecedor.'">');
      ShowHTML('<INPUT type="hidden" name="obj_origem" value="'.$w_fornecedor.'">');
      ShowHTML('<INPUT type="hidden" name="w_sq_tipo_documento" value="'.$w_sq_tipo_documento.'">');
      ShowHTML('<INPUT type="hidden" name="w_numero" value="'.$w_numero.'">');
      ShowHTML('<INPUT type="hidden" name="w_data" value="'.$w_data.'">');
      ShowHTML('<INPUT type="hidden" name="w_valor" value="'.$w_valor.'">');
    }
    ShowHTML('      <tr valign="top">');
    SelecaoTipoMovimentacao('Tipo da <u>m</u>ovimentação:','M', 'Selecione o tipo da movimentação.', $w_tipo,'S',null,'w_tipo','CONSUMO',null);
    //ShowHTML('          <td><b>Data <u>p</u>revista para recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_prevista" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_prevista.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data prevista para recebimento do material.">'.ExibeCalendario('Form','w_prevista').'</td>');
    ShowHTML('          <td><b>Data de <u>e</u>ntrega:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_efetiva" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_efetiva.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de entrega do material.">'.ExibeCalendario('Form','w_efetiva').'</td>');
    SelecaoPessoa('<u>R</u>esponsável pelo pagamento:','R','Selecione o responsável pelo pagamento ao fornecedor.',$w_executor,null,'w_executor','EXECUTORCO',null,2);
    ShowHTML('      <tr><td align="center" colspan=4 height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan=4>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.'inicial&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificadas as pendências listadas abaixo, não sendo possível armazenar/incorporar seus itens.');
        }elseif (substr($w_erro,0,1)=='1') {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificadas as pendências listadas abaixo. O armazenamento/incorporação dos itens só pode ser feito por um gestor do sistema ou deste módulo.');
        } else {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o armazenamento/incorporação dos itens, mas convém sua verificação.');
        } 
        ShowHTML('  <ul>'.substr($w_erro,1,1000));
        ShowHTML('<tr><td colspan=2><HR>');
      } 
    
      // Itens
      ShowHTML('    <tr><td colspan=3 align="center" height="1"></td></tr>');
      ShowHTML('    <tr><td colspan=3><b>Itens</b>&nbsp;&nbsp;[<A class="box HL" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.removeTP($TP).' - Itens'.'&SG='.substr($SG,0,4).'ITEM').'" title="Informa os itens do documento.">'.((count($RS_Itens)>0) ? 'Ajustar' : 'Incluir').'</A>]</td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center" height="1"></td></tr>');
      ShowHTML('    <tr><td colspan="3"><table width="100%" border="0">');
      if (count($RS_Itens)==0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align=center><b>Itens não informados</b></td></tr>');
        ShowHTML('      <tr><td colspan=2 align=center><hr /></td></tr>');
      } else {
        unset($w_classes);
        foreach($RS_Itens as $row) $w_classes[f($row,'classe')] = 1;
        reset($RS_Itens);
        $colspan = 0;
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <table class="tudo" width="100%" BORDER=1 bordercolor="#666666">');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td rowspan=2><b>Item</b></td>');
        ShowHTML('          <td rowspan=2><b>Nome</b></td>');
        if (($w_classes[1] || $w_classes[2] || $w_classes[3]) && !$w_classes[4]) {
          ShowHTML('          <td rowspan=2><b>Marca</b></td>');
        } elseif (!($w_classes[1] || $w_classes[2] || $w_classes[3]) && $w_classes[4]) {
          ShowHTML('          <td rowspan=2><b>Fabricante</b></td>');
        } elseif (($w_classes[1] || $w_classes[2] || $w_classes[3]) && $w_classes[4]) {
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
        if ($w_classes[1] || $w_classes[2] || $w_classes[3]) {
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
        foreach($RS_Itens as $row){ 
          ShowHTML('      <tr valign="top">');
          ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
          ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
          if (f($row,'lote_bloqueado')=='S') {
            ShowHTML('        <td colspan="7">ITEM CANCELADO. '.nvl(f($row,'motivo_bloqueio'),'').'</td>');
          } else {
            $w_total += f($row,'valor_total');
            ShowHTML('        <td>'.nvl(f($row,'marca'),'&nbsp;').'</td>');
            if ($w_classes[4]) {
              ShowHTML('        <td>'.nvl(f($row,'modelo'),'&nbsp;').'</td>');
              ShowHTML('        <td align="center">'.nvl(f($row,'vida_util'),'&nbsp').'</td>');
            }
            if ($w_classes[1]) {
              ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'lote_numero'),5),'&nbsp;').'</td>');
              ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'fabricacao'),5),'&nbsp;').'</td>');
            }
            if ($w_classes[1] || $w_classes[2] || $w_classes[3]) {
              ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'validade'),5),'&nbsp;').'</td>');
              ShowHTML('        <td align="center">'.((f($row,'classe')==1||f($row,'classe')==3) ? f($row,'fator_embalagem') : '&nbsp;').'</td>');
              ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
            }
            ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
            ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_unitario'),2).'</td>');
            ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_total')).'</td>');
            ShowHTML('        </tr>');
          }
        }
        if (count($RS_Itens)>1) ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td colspan='.(5+$colspan).' align="right"><b>Total dos itens</b><td align="right">'.formatNumber($w_total).'</tr>');
        ShowHTML('    </table>');
      }
      ShowHTML('       </table>');

      // Recupera todos os registros para a listagem 
      $sql = new db_getDocumentoArquivo; $RS = $sql->getInstanceOf($dbms,$w_chave,null,null,null,$w_cliente);
      $RS = SortArray($RS,'ordem','asc','nome','asc');
      ShowHTML('    <tr><td colspan=3><br><b>Arquivos</b>&nbsp;&nbsp;[<A class="box HL" HREF="'.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Anexos&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Anexos'.'&SG='.substr($SG,0,4).'ANEXO').'" title="Registra os anexos do documento.">'.((count($RS)>0) ? 'Ajustar' : 'Incluir').'</A>]</td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center" height="1"></td></tr>');
      ShowHTML('    <tr><td colspan="3"><table width="100%" border="0">');
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
      
      if ($O!='I' && nvl($w_erro,'')=='') {
        // Armazenamento
        $sql = new db_getMtEntItem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,'ARMAZEN');
        $RS = SortArray($RS,'ordem','asc','nome','asc'); 
        unset($w_classes);
        foreach($RS as $row) $w_classes[f($row,'classe')] = 1;
        reset($RS);
        ShowHTML('    <tr><td colspan=3><br><b>'.((count($w_classes)>1) ? 'Armazenamento /' : (($w_classes[4]) ? 'Incorporação' : 'Armazenamento')).'</b></td></tr>');
        ShowHTML('    <tr><td colspan="3"><table width="100%" border="0" bgcolor="'.$conTrBgColor.'">');
        ShowHTML('      <tr><td colspan=3 align="center"><font color="#BC3131"><b>ATENÇÃO: execute esta ação somente após informar e conferir os dados da entrada de material.</b></font></td></tr>');
        if (count($RS)==0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align=center><b>Itens não informados</b></td></tr>');
          ShowHTML('      <tr><td colspan=2 align=center><hr /></td></tr>');
        } else {
          $colspan = 0;
          ShowHTML('      <tr><td align="center" colspan="2">');
          AbreForm('Form1',$w_dir.$w_pagina.'Grava','POST','return(Validacao1(this));',null,$P1,$P2,$P3,$P4,$TP,'MTENTARM',$w_pagina.$par,$O);
          ShowHTML(MontaFiltro('POST'));
          ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
          ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
          ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
          ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
          ShowHTML('<INPUT type="hidden" name="w_solicitacao" value="'.$w_solicitacao.'">');
          ShowHTML('<INPUT type="hidden" name="w_documento" value="'.$w_documento.'">');
          ShowHTML('<INPUT type="hidden" name="w_efetiva" value="'.$w_efetiva.'">');
          ShowHTML('<INPUT type="hidden" name="w_situacao" value="'.$w_situacao.'">');
          if (count($w_classes>1) || !$w_classes[4]) {
            ShowHTML('<INPUT type="hidden" name="w_local[]" value="">');
            ShowHTML('<INPUT type="hidden" name="w_item[]" value="">');
          }
          ShowHTML('        <table class="tudo" width="100%" BORDER=1 bordercolor="#666666">');
          ShowHTML('        <tr align="center">');
          ShowHTML('          <td><b>Item</b></td>');
          ShowHTML('          <td><b>Nome</b></td>');
          ShowHTML('          <td><b>U.M.</b></td>');
          ShowHTML('          <td><b>Qtd</b></td>');
          ShowHTML('          <td><b>'.((count($w_classes)>1) ? 'Local de armazenamento /' : (($w_classes[4]) ? 'Ação' : 'Local de armazenamento')).'</b></td>');
          ShowHTML('        </tr>');
          // Lista os registros selecionados para listagem
          $i = 1;
          foreach($RS as $row){ 
            ShowHTML('      <tr valign="top">');
            ShowHTML('        <td align="center">'.f($row,'ordem'));
            ShowHTML('          <INPUT type="hidden" name="w_item[]" value="'.f($row,'sq_entrada_item').'">');
            ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
            ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
            ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
            if (f($row,'classe')<=3) {
              selecaoLocalSubordination(null,null,'Informe o local para armazenamento deste item.',f($row,'sq_almoxarifado_local'),f($row,'sq_almoxarifado'),'w_local[]','ARMAZENA','onChange="ajusta('.$i.');"',1,'');
            } else {
              ShowHTML('        <td align="right">Incorporar</td>');
            }
            ShowHTML('        </tr>');
            $i++;
          }
          ShowHTML('    </table>');
          if (count($w_classes>1) || !$w_classes[4]) {
            ShowHTML('      <tr><td><b><u>D</u>ata de armazenamento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_armazenamento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_armazenamento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de armazenamento do material.">'.ExibeCalendario('Form1','w_armazenamento').'</td>');
            ShowHTML('      <tr><td colspan=2><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
            ShowHTML('      <tr><td align="center" colspan=2 height="1" bgcolor="#000000"></TD></TR>');
            ShowHTML('      <tr><td align="center" colspan=2>');
            ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
            ShowHTML('          </td>');
            ShowHTML('      </tr>');
          }
          ShowHTML('</form>');
        }
        ShowHTML('       </table>');
      }
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
  $w_solic_pai = nvl(f($RS_Solic,'sq_solic_pai'),0);

  // Se pelo menos um item está ligado a uma compra ou contrato, não permite incluir nem excluir itens.
  // Também impede alteração dos dados importados da compra ou contrato, exceto a quantidade.
  $sql = new db_getMtEntItem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,$w_chave_aux,null,null,null,null,null,null,null,null,null,null,null,null);
  $w_edita = true;
  foreach($RS as $row) {
    if (nvl(f($row,'sq_solicitacao_item'),'')!='') {
      $w_edita = false;
      $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,f($row,'sq_solicitacao_item'),f($row,'sq_solic_pai'),null,null,null,null,null,null,null,null,null,null,'VENCEDOR');
      foreach($RS1 as $row1) {
        // Guarda a quantidade comprada. A quantidade informada não pode ser superior a esta.
        $w_qtd_compra = formatNumber(f($row1,'quantidade_autorizada'),0);
        $w_val_item = formatNumber(f($row1,'valor_unidade'));
        $w_val_compra = formatNumber(f($row1,'valor_item'));
      }
    }
  }
  
  if ($w_troca>'' && $O <> 'E') {
    $w_nome               = $_REQUEST['w_nome'];
    $w_material           = $_REQUEST['w_material'];
    $w_material_nm        = $_REQUEST['w_material_nm'];
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
    $w_bloqueio           = $_REQUEST['w_bloqueio'];
    $w_motivo             = $_REQUEST['w_motivo'];
  } elseif (strpos('LI',$O)!==false) {
    $sql = new db_getMtEntItem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'ordem','asc','nome','asc'); 
    $w_proximo = count($RS)+1;
  } 
  
  if (nvl($w_troca,'')=='' && (strpos('AE',$O)!==false || nvl($w_copia,'')!='')) {
    $sql = new db_getMtEntItem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,nvl($w_copia,$w_chave_aux),null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_almoxarifado       = f($RS,'sq_almoxarifado');
    $w_situacao           = f($RS,'sq_sit_item');
    $w_material         = f($RS,'sq_material');
    $w_material_nm      = f($RS,'nome');
    if (nvl($w_copia,'')=='') {
      $w_ordem            = f($RS,'ordem');
    }
    $w_quantidade         = formatNumber(f($RS,'quantidade'),0);
    $w_valor              = formatNumber(f($RS,'valor_total'),2);
    $w_fator              = f($RS,'fator_embalagem');
    $w_validade           = formataDataEdicao(f($RS,'validade'));
    $w_fabricacao         = formataDataEdicao(f($RS,'fabricacao'));
    $w_vida_util          = f($RS,'vida_util');
    $w_lote               = f($RS,'lote_numero');
    $w_fabricante         = f($RS,'marca');
    $w_modelo             = f($RS,'modelo');
    $w_bloqueio           = f($RS,'lote_bloqueado');
    $w_motivo             = f($RS,'motivo_bloqueio');
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
  ScriptOpen('JavaScript');
  FormataValor();
  checkBranco();
  FormataData();
  ValidateOpen('Validacao');
  if ($O=='L') {
    ShowHTML('  for (ind=1; ind < theForm["w_chave_aux[]"].length; ind++) {');
    Validate('["w_quantidade[]"][ind]','Quantidade','1','1','1','18','','1');
    CompValor('["w_quantidade[]"][ind]','Quantidade','>=','0','1');  
    //if (!$w_edita) CompValor('["w_quantidade[]"][ind]','Quantidade','<=',$w_qtd_compra,' quantidade comprada');
    if ($w_edita) Validate('["w_valor[]"][ind]','Valor total','VALOR','1','4','18','','0123456789.,');
    //CompValor('["w_valor[]"][ind]','Valor total','>','0','zero');
    ShowHTML('  }');
  } elseif (strpos('IA',$O)!==false) {
    if ($w_edita) {
      Validate('w_ordem','Ordem','1','1','1','4','','0123456789');
      CompValor('w_ordem','Ordem','>=','0','1');  
      Validate('w_nome','Nome','1','','2','30','1','1');
      Validate('w_material','Material/Serviço','SELECT','1','1','18','','1');
    }
    if (nvl($w_material,'')=='') {
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      Validate('w_quantidade','Quantidade','1','1','1','18','','1');
      CompValor('w_quantidade','Quantidade','>','0','1');  
      if (!$w_edita) CompValor('w_quantidade','Quantidade','<=',$w_qtd_compra,' quantidade comprada');  
      if ($w_edita) {
        Validate('w_valor','Valor total','VALOR','1','4','18','','0123456789.,');
        CompValor('w_valor','Valor total','>','0','zero');
      }
      if ($w_classe==1||$w_classe==2||$w_classe==3) {
        Validate('w_validade','Data de validade','DATA','',10,10,'','0123456789/');
        CompData('w_validade','Data de validade','>=',formataDataEdicao(time()),'Data atual');
        Validate('w_fator','Embalagem','1','1',1,4,'','0123456789');
        CompValor('w_fator','Fator de embalagem','>','0','0');
      } elseif ($w_classe==4) {
        Validate('w_vida_util','Vida útil','1','1',1,4,'','0123456789');
        CompValor('w_vida_util','Vida útil','>','0','0');
      }
      if ($w_classe==1) {
        Validate('w_lote','Lote nr','','',1,20,'1','1');
        Validate('w_fabricacao','Data de fabricação','DATA',1,10,10,'','0123456789/');
        CompData('w_fabricacao','Data de fabricação','<',formataDataEdicao(time()),'Data atual');
      }
      if ($w_classe!=4) {
        Validate('w_fabricante','Marca','1','1',2,50,'1','1');
      } else {
        Validate('w_fabricante','Fabricante','1','1',2,50,'1','1');
        Validate('w_modelo','Marca/Modelo','1','1',2,50,'1','1');
      }
      Validate('w_almoxarifado','Almoxarifado','SELECT','1','1','18','','1');
      Validate('w_motivo','Motivo','1','','2','1000','1','1');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
      if ($O=='I') ShowHTML('  theForm.Botao[2].disabled=true;');
    }
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='L' || !$w_edita) {
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
  ShowHTML('            <td>Emissão:<br><b>'.formataDataEdicao(f($RS_Solic,'dt_doc'),5).'</b></td>');
  ShowHTML('            <td>Valor:<br><b>'.formatNumber(f($RS_Solic,'vl_doc')).'</b></td>');
  ShowHTML('          </tr>');
  if (!$w_edita && $O!='L') {
    ShowHTML('          <tr><td colspan=3><hr>Item: <b>'.$w_ordem.' - '.$w_material_nm.'</b></td></tr>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td>Quantidade:&nbsp;<b>'.$w_qtd_compra.'</b></td>');
    ShowHTML('            <td>$ Unitário:&nbsp;<b>'.$w_val_item.'</b></td>');
    ShowHTML('          </tr>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td>Fator de embalagem:&nbsp;<b>'.$w_fator.'</b></td>');
    ShowHTML('            <td>$ Total:&nbsp;<b>'.$w_val_compra.'</b></td>');
    ShowHTML('          </tr>');
  }
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<table border="0" width="100%">');

  if ($O=='L') {
    unset($w_classes);
    foreach($RS as $row) $w_classes[f($row,'classe')] = 1;
    reset($RS);
    $colspan = 0;
    ShowHTML('<tr><td>');
    if ($w_edita) ShowHTML('      <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('      <a accesskey="F" class="ss" href="javascript:this.status.value;" onClick="parent.$.fancybox.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Item','ordem').'</td>');
    $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    if (!$w_classes[4]) {
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Marca','marca').'</td>');
//    } elseif (!$w_classes[1] && !$w_classes[2] && !$w_classes[3] && $w_classes[4]) {
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Fabricante','marca').'</td>');
//    } elseif (($w_classes[1] || $w_classes[2] || $w_classes[3]) && $w_classes[4]) {
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Fabricante / marca','marca').'</td>');
//    }
//    if ($w_classes[4]) {
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Modelo','modelo').'</td>');
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Vida útil','vida_util').'</td>');
//    }
//    if ($w_classes[1]) {
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Lote','lote_numero').'</td>');
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Fabricação','fabricacao').'</td>');
//    }
//    if ($w_classes[1] || $w_classes[2] || $w_classes[3]) {
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Validade','validade').'</td>');
//      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('F.E.','fator_embalagem').'</td>');
    }
    $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Qtd','quantidade').'</td>');
    if ($w_edita) ShowHTML('          <td><b>'.LinkOrdena('Total','valor_total').'</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
      ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_chave_aux[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_ordem[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_material[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_situacao[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_quantidade[]" value="">');
      if ($w_edita) ShowHTML('<INPUT type="hidden" name="w_valor[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_validade[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_fator[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_vida_util[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_lote[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_fabricacao[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_fabricante[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_modelo[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_almoxarifado[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_bloqueio[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_motivo[]" value="">');
      $w_total = 0;
      foreach($RS as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
        ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null));
        ShowHTML('<INPUT type="hidden" name="w_chave_aux[]" value="'.f($row,'sq_entrada_item').'">');
        ShowHTML('<INPUT type="hidden" name="w_ordem[]" value="'.f($row,'ordem').'">');
        ShowHTML('<INPUT type="hidden" name="w_material[]" value="'.f($row,'sq_material').'">');
        ShowHTML('<INPUT type="hidden" name="w_situacao[]" value="'.f($row,'sq_sit_item').'">');
        ShowHTML('<INPUT type="hidden" name="w_validade[]" value="'.formataDataEdicao(f($row,'validade')).'">');
        ShowHTML('<INPUT type="hidden" name="w_fator[]" value="'.f($row,'fator_embalagem').'">');
        ShowHTML('<INPUT type="hidden" name="w_vida_util[]" value="'.f($row,'vida_util').'">');
        ShowHTML('<INPUT type="hidden" name="w_lote[]" value="'.f($row,'lote_numero').'">');
        ShowHTML('<INPUT type="hidden" name="w_fabricacao[]" value="'.formataDataEdicao(f($row,'fabricacao')).'">');
        ShowHTML('<INPUT type="hidden" name="w_fabricante[]" value="'.f($row,'marca').'">');
        ShowHTML('<INPUT type="hidden" name="w_modelo[]" value="'.f($row,'modelo').'">');
        ShowHTML('<INPUT type="hidden" name="w_almoxarifado[]" value="'.f($row,'sq_almoxarifado').'">');
        ShowHTML('<INPUT type="hidden" name="w_bloqueio[]" value="'.f($row,'lote_bloqueado').'">');
        ShowHTML('<INPUT type="hidden" name="w_motivo[]" value="'.f($row,'motivo_bloqueio').'">');
        if (f($row,'lote_bloqueado')=='S') {
          ShowHTML('        <td colspan="4">ITEM CANCELADO. '.nvl(f($row,'motivo_bloqueio'),'').'</td>');
          ShowHTML('        <td><input type="text" name="w_quantidade[]" class="STI" SIZE="4" MAXLENGTH="18" VALUE="'.formatNumber(f($row,'quantidade'),0).'" style="text-align:right;"></td>');
          ShowHTML('        <td><input type="text" '.$w_Disabled.' name="w_valor[]" class="sti" SIZE="8" MAXLENGTH="18" VALUE="'.formatNumber(f($row,'valor_total')).'" style="text-align:right;" onKeyDown="javascript:FormataValor(this,18,2,event);" title="Informe o valor total do item. O sistema calculará o valor unitário."></td>');
        } else {
          $w_total += f($row,'valor_total');
          ShowHTML('        <td>'.f($row,'marca').'</td>');
          //if ($w_classes[4]) {
          //  ShowHTML('        <td>'.nvl(f($row,'modelo'),'&nbsp;').'</td>');
          //  ShowHTML('        <td align="center">'.nvl(f($row,'vida_util'),'&nbsp').'</td>');
          //}
          //if ($w_classes[1]) {
          //  ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'lote_numero'),5),'&nbsp;').'</td>');
          //  ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'fabricacao'),5),'&nbsp;').'</td>');
          //}
          //if ($w_classes[1] || $w_classes[2] || $w_classes[3]) {
          //  ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'validade'),5),'&nbsp;').'</td>');
          //  ShowHTML('        <td align="center">'.((f($row,'classe')==1||f($row,'classe')==3) ? f($row,'fator_embalagem') : '&nbsp;').'</td>');
          //  ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
          //}
          ShowHTML('        <td><input type="text" name="w_quantidade[]" class="STI" SIZE="4" MAXLENGTH="18" VALUE="'.formatNumber(f($row,'quantidade'),0).'" style="text-align:right;"></td>');
          if ($w_edita) ShowHTML('        <td><input type="text" '.$w_Disabled.' name="w_valor[]" class="sti" SIZE="8" MAXLENGTH="18" VALUE="'.formatNumber(f($row,'valor_total')).'" style="text-align:right;" onKeyDown="javascript:FormataValor(this,18,2,event);" title="Informe o valor total do item. O sistema calculará o valor unitário."></td>');
          //ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
          //ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_unitario'),2).'</td>');
          //ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_total')).'</td>');
        }
        ShowHTML('        <td align="top" nowrap class="remover">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_entrada_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">AL</A>&nbsp');
        if ($w_edita) {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_entrada_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_copia='.f($row,'sq_entrada_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Copiar">CO</A>&nbsp');
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
      ShowHTML('      <tr><td colspan=6 align="center"><hr>');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
      ShowHTML('        </form>');
    } 
    ShowHTML('    </table>');
    ShowHTML('  </td>');
  } elseif (strpos('IAECV',$O)!==false) {
    if (strpos('EVC',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_situacao" value="'.$w_situacao.'">');
    ShowHTML('<INPUT type="hidden" name="w_material_nm" value="'.$w_material_nm.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if (!$w_edita) {
      ShowHTML('<INPUT type="hidden" name="w_ordem" value="'.$w_ordem.'">');
      ShowHTML('<INPUT type="hidden" name="w_material" value="'.$w_material.'">');
      ShowHTML('<INPUT type="hidden" name="w_qtd_compra" value="'.$w_qtd_compra.'">');
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($w_edita) {
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b><u>I</u>tem número:</b><br><input accesskey="I" type="text" name="w_ordem" class="STI" SIZE="5" MAXLENGTH="4" VALUE="'.nvl($w_ordem,$w_proximo).'" '.$w_Disabled.' style="text-align:center;"></td>');
      ShowHTML('          <td colspan=3><b><u>N</u>ome do material:</b><br><input '.$p_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="40" MAXLENGTH="100" VALUE="'.$w_nome.'">');
      ShowHTML('            <input class="stb" type="button" onClick="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_material\'; document.Form.submit();" name="Botao" value="Procurar">');
      ShowHTML('      </tr>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td colspan="4" TITLE="Selecione o material/serviço desejado na listagem ou procure por outro nome."><b>Material:</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="w_material" '.$w_Disabled.' onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_material\'; document.Form.submit();">');
      ShowHTML('          <option value="">---');
      if (nvl($w_nome,'')!='' || $O=='A' || nvl($w_copia,'')!='') {
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
    }
    if (nvl($w_material,'')=='') {
      ShowHTML('      <tr><td colspan=4 align="center"><hr>');
    } else {
      ShowHTML('      <tr><td colspan="6" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan=6 align="center"><b>Classe do material: '.$w_classe.' - '.upper($w_nm_classe).'</b></td></tr>');
      ShowHTML('      <tr><td colspan="6" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td><b><u>Q</u>uantidade:</b><br><input accesskey="Q" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" '.$w_Disabled.' style="text-align:right;"></td>');
      if ($w_edita) ShowHTML('        <td><b>$ <u>T</u>otal:</b><br><input type="text" '.$w_Disabled.' accesskey="T" name="w_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="javascript:FormataValor(this,18,2,event);" title="Informe o valor total do item. O sistema calculará o valor unitário."></td>');
      if ($w_classe==1||$w_classe==2||$w_classe==3) {
        ShowHTML('        <td><b><u>V</u>alidade:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_validade" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_validade.'" onKeyDown="FormataData(this,event);" title="Data de validade do item."></td>');
        ShowHTML('        <td><b><u>F</u>ator de embalagem:</b><br><input type="text" '.$w_Disabled.' accesskey="F" name="w_fator" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.nvl($w_fator,f($row,'fator_embalagem')).'" style="text-align:right;" title="Define o múltiplo da quantidade a ser solicitada."></td>');
      } else {
        if ($w_classe==4) {
          ShowHTML('        <td><b><u>V</u>ida útil (anos):</b><br><input type="text" '.$w_Disabled.' accesskey="V" name="w_vida_util" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.nvl($w_vida_util,f($row,'vida_util')).'" style="text-align:right;" title="Vida útil do bem."></td>');
        }
        if ($w_edita) ShowHTML('<INPUT type="hidden" name="w_fator" value="1">');
      }
      if ($w_classe==1) {
        ShowHTML('      </tr>');
        ShowHTML('      <tr valign="top">');
        ShowHTML('        <td colspan=2><b><u>L</u>ote:</b><br><input '.$p_Disabled.' accesskey="L" type="text" name="w_lote" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_lote.'">');
        ShowHTML('        <td><b><u>F</u>abricação:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fabricacao" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fabricacao.'" onKeyDown="FormataData(this,event);" title="Data de fabricação do item."></td>');
        ShowHTML('      </tr>');
      }
      if ($w_edita) { ShowHTML('      </tr>'); ShowHTML('      <tr valign="top">'); }
      if ($w_classe!=4) {
        ShowHTML('        <td colspan=2><b>M<u>a</u>rca:</b><br><input '.$p_Disabled.' accesskey="A" type="text" name="w_fabricante" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_fabricante.'">');
      } else {
        ShowHTML('        <td colspan=2><b>F<u>a</u>bricante:</b><br><input '.$p_Disabled.' accesskey="A" type="text" name="w_fabricante" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_fabricante.'">');
        ShowHTML('        <td colspan=2><b><u>M</u>odelo:</b><br><input '.$p_Disabled.' accesskey="M" type="text" name="w_modelo" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_modelo.'">');
      }
      ShowHTML('      </tr>');
      ShowHTML('      <tr>');
      SelecaoAlmoxarifado('Al<u>m</u>oxarifado para armazenamento:','M', 'Selecione o almoxarifado onde o material será armazenado.', $w_almoxarifado,'w_almoxarifado',null,null,4);
      ShowHTML('      <tr valign="top">');
      MontaRadioNS('<b>Item cancelado?</b>',$w_bloqueio,'w_bloqueio');
      ShowHTML('          <td colspan="5"><b><u>M</u>otivo do cancelamento:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_motivo" class="STI" ROWS=5 cols=65 title="OBRIGATÓRIO. Descreva a finalidade do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
      ShowHTML('      </tr>');
      ShowHTML('      <tr><td colspan=6 align="center"><hr>');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
  ShowHTML('            <td>Emissão:<br><b>'.formataDataEdicao(f($RS_Solic,'dt_doc'),5).'</b></td>');
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
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
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
  ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
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
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
  if ($P1!=1) {
    // Se não for encaminhamento
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
  } else {
    ShowHTML('  theForm.Botao.disabled=true;');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
  
  $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
    null,null,null,null,null,null,null,null,null,null,
    $w_chave,null,null,null,null,null,null,
    null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $w_situacao                 = f($RS,'sq_mtsituacao');

  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'MTENTMAT',$w_pagina.$par,'E');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_situacao" value="'.$w_situacao.'">');
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
  Rodape();
} 

// =========================================================================
// Rotina de estorno
// -------------------------------------------------------------------------
function Estorno() {
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
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
  if ($P1!=1) {
    // Se não for encaminhamento
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
  } else {
    ShowHTML('  theForm.Botao.disabled=true;');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
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
  
  $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
    null,null,null,null,null,null,null,null,null,null,
    $w_chave,null,null,null,null,null,null,
    null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $w_situacao                 = f($RS,'sq_mtsituacao');

  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'MTENTARM',$w_pagina.$par,'E');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_situacao" value="'.$w_situacao.'">');
  ShowHTML('<tr><td align="center">');
  ShowHTML('  <table width="97%" border="0" bgcolor="'.$conTrBgColor.'">');
  ShowHTML('      <tr><td colspan=4><b>ATENÇÃO: Para efetivar o estorno da entrada de material, informe sua assinatura eletrônica e clique no botão <i>Estornar</i>. Se não desejar a efetivação do estorno, clique no botão <i>Abandonar</i> para voltar à listagem.</b></td></tr>');
  ShowHTML('      <tr><td colspan=4 height=1 bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Estornar">');
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
  switch ($SG) {
    case 'MTENTMAT':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        // Se o cliente tem o módulo financeiro, é obrigatório que a entrada esteja vinculada a um lançamento pago
        if ($w_mod_fn=='S' && $O!='E') {
          $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,null,null,$_REQUEST['w_fornecedor'],$_REQUEST['w_sq_tipo_doc_ant'],$_REQUEST['w_numero_ant'],null,null,null);
          $w_existe = false;
          if (count($RS)==0) {
            $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,null,null,$_REQUEST['w_fornecedor'],$_REQUEST['w_sq_tipo_doc_ant'],'AJUSTAR',null,null,null);
          }
          if (count($RS)>0) {
            $RS = SortArray($RS,'numero','asc');
            $w_data   = true;
            $w_valor  = true;
            foreach($RS as $row2) {
              $w_existe = true;
              if (formataDataEdicao(f($row2,'data'))==$_REQUEST['w_data_ant']) $w_data  = false;
              if (formatNumber(f($row2,'valor'))==$_REQUEST['w_valor_ant'])    $w_valor = false;
              $RS2 = $row2;
            }
          }
          
          if ($w_existe) {
            if ($w_data) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("ATENÇÃO: A data '.$_REQUEST['w_data'].' difere da registrada: '.formataDataEdicao(f($RS2,'data')).'\n'.f($RS2,'codigo_interno').' - '.f($RS2,'nm_tipo_documento').' '.f($RS2,'numero').'");');
              ScriptClose();
              retornaFormulario('w_data');
              exit();
            } elseif ($w_valor) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("ATENÇÃO: O valor '.$_REQUEST['w_valor'].' difere do registrado: '.formatNumber(f($RS2,'valor')).'\n'.f($RS2,'codigo_interno').' - '.f($RS2,'nm_tipo_documento').' '.f($RS2,'numero').'");');
              ScriptClose();
              retornaFormulario('w_valor');
              exit();
            }
          }
        }
        // Recupera o código da situação inicial da movimentação
        $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,'ENTRADA',null,'S',null,null);
        if (count($RS)==0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: A tabela de situações precisa ser carrregada com pelo menos um registro relativo a entrada de material!");');
          ScriptClose();
          retornaFormulario('w_tipo');
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
        $SQL = new dml_putMtEntrada; $SQL->getInstanceOf($dbms,$O,$w_cliente,$w_usuario,$_REQUEST['w_chave'],$_REQUEST['w_copia'],$_REQUEST['w_executor'],
                $_REQUEST['w_fornecedor'],$_REQUEST['w_tipo'],$w_situacao,$_REQUEST['w_solicitacao'],$_REQUEST['w_documento'],
                nvl($_REQUEST['w_prevista'],$_REQUEST['w_efetiva']),$_REQUEST['w_efetiva'],
                $_REQUEST['w_sq_tipo_documento'],$_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_valor'],
                $_REQUEST['w_armazenamento'],$_REQUEST['w_numero_empenho'],$_REQUEST['w_data_empenho'],$w_chave_nova);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href="'.montaURL_JS($w_dir,$w_pagina.(($O=='E') ? 'inicial&O=L' : 'geral&O=A&w_chave='.$w_chave_nova).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'";');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'MTENITEM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if (is_array($_REQUEST['w_chave_aux'])) {
          for ($i=1; $i<=count($_POST['w_chave_aux'])-1; $i=$i+1) {
            if ($_REQUEST['w_chave_aux'][$i]>'') {
              $w_valor = $_REQUEST['w_valor'][$i];

              if (nvl($_REQUEST['w_qtd_compra'],'')!='') {
                $w_valor      = toNumberPHP($_REQUEST['w_valor'][$i]);
                $w_qtd_compra = toNumberPHP($_REQUEST['w_qtd_compra'][$i]);
                $w_quantidade = toNumberPHP($_REQUEST['w_quantidade'][$i]);

                $w_valor = formatNumber($w_valor / $w_qtd_compra * $w_quantidade);
              }

              $SQL = new dml_putMtEntItem; $SQL->getInstanceOf($dbms,'A',$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'][$i],$_REQUEST['w_almoxarifado'][$i],
                      $_REQUEST['w_situacao'][$i],$_REQUEST['w_ordem'][$i],$_REQUEST['w_material'][$i],$_REQUEST['w_quantidade'][$i],$w_valor,$_REQUEST['w_fator'][$i],
                      $_REQUEST['w_validade'][$i],$_REQUEST['w_fabricacao'][$i],$_REQUEST['w_vida_util'][$i],$_REQUEST['w_lote'][$i],$_REQUEST['w_fabricante'][$i],
                      $_REQUEST['w_modelo'][$i],$_REQUEST['w_bloqueio'][$i],$_REQUEST['w_motivo'][$i]);
            }
          } 
          
        } else {
          $w_valor = $_REQUEST['w_valor'];

          if (nvl($_REQUEST['w_qtd_compra'],'')!='' && nvl($_REQUEST['w_valor'],'')!='') {
            $w_valor      = toNumberPHP($_REQUEST['w_valor']);
            $w_qtd_compra = toNumberPHP($_REQUEST['w_qtd_compra']);
            $w_quantidade = toNumberPHP($_REQUEST['w_quantidade']);

            $w_valor = formatNumber($w_valor / $w_qtd_compra * $w_quantidade);
          }

          $SQL = new dml_putMtEntItem; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_almoxarifado'],
                  $_REQUEST['w_situacao'],$_REQUEST['w_ordem'],$_REQUEST['w_material'],$_REQUEST['w_quantidade'],$w_valor,$_REQUEST['w_fator'],
                  $_REQUEST['w_validade'],$_REQUEST['w_fabricacao'],$_REQUEST['w_vida_util'],$_REQUEST['w_lote'],$_REQUEST['w_fabricante'],
                  $_REQUEST['w_modelo'],$_REQUEST['w_bloqueio'],$_REQUEST['w_motivo']);
        }        

        ScriptOpen('JavaScript');
        ShowHTML('  location.href="'.montaURL_JS($w_dir,$w_pagina.'Itens&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'";');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }     
      break;
    case 'MTENANEXO':
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
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;      
    case 'MTENTARM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $sql = new db_getMtMovim; $RS_Solic = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
                $_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS_Solic as $row){$RS_Solic=$row; break;}
        if (f($RS_Solic,'sq_mtsituacao')!=$_REQUEST['w_situacao']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: A entrada de material já teve sua situação alterada!");');
          ShowHTML('  location.href="'.montaURL_JS($w_dir,$w_pagina.'inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'";');
          ScriptClose();
          exit();
        } else {
          $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'VERIFENT',3,null,null,null,null,null,null,null,null,null,null,
                  $_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
          foreach($RS as $row){$RS=$row; break;}
          if (/*f($RS,'entrada_mes_seguinte')=='S'||f($RS,'saida_mes_seguinte')=='S'||*/f($RS,'mes_corrente_fechado')=='S'||f($RS,'mes_seguinte_fechado')=='S'||f($RS,'mes_anterior_fechado')=='N') {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: Não será possível efetivar a ação! Motivo(s):'.
                     //((f($RS,'entrada_mes_seguinte')=='S') ? '\n- Existe entrada em mês posterior' : '').
                     //((f($RS,'saida_mes_seguinte')=='S') ? '\n- Existe saída em mês posterior' : '').
                     ((f($RS,'mes_corrente_fechado')=='S') ? '\n- Mês de entrega efetiva já fechado' : '').
                     ((f($RS,'mes_seguinte_fechado')=='S') ? '\n- Mês posterior ao de entrega efetiva já fechado' : '').
                     ((f($RS,'mes_anterior_fechado')=='N') ? '\n- Mês anterior ao de entrega efetiva não fechado' : '').
                    '");');
            ShowHTML('  location.href="'.montaURL_JS($w_dir,$w_pagina.'inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'";');
            ScriptClose();
            exit();
          }
          
          $SQL = new dml_putMtEntArm; 
          if ($O=='E') {
            // Estorna a entrada
            $SQL->getInstanceOf($dbms,'E',$_REQUEST['w_chave'],null,null);
          } else {
            // Armazena os itens da entrada
            for ($i=0; $i<=count($_POST['w_item'])-1; $i=$i+1) {
              if ($_REQUEST['w_item'][$i]>'') {
                $SQL->getInstanceOf($dbms,'A',$_REQUEST['w_chave'],$_REQUEST['w_item'][$i],$_REQUEST['w_local'][$i]);
              }
            }
          }

          // Registra o armazenamento
          $SQL = new dml_putMtEntrada; $SQL->getInstanceOf($dbms,'V',$w_cliente,$w_usuario,$_REQUEST['w_chave'],null,null,
            null,null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_armazenamento'],null,null,$w_chave_nova);

          ScriptOpen('JavaScript');
          ShowHTML('  location.href="'.montaURL_JS($w_dir,$w_pagina.'inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'";');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
        exit();
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
  case 'ESTORNO':           Estorno(); break;
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

