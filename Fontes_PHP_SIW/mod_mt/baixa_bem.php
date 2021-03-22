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
include_once($w_dir_volta.'classes/sp/db_getMtBaixaBem.php');
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
include_once($w_dir_volta.'classes/sp/dml_putMtBaixaBem.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicConc.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putMtBaixaItem.php');
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
include_once('visualbaixa.php');
include_once('validabaixa.php');

// =========================================================================
//  /baixa_bem.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Registra entradas de material
// Mail     : alex@sbpi.com.br
// Criacao  : 08/03/2021, 11:13
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

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'baixa_bem.php?par=';
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

// Verifica se o cliente contratou os módulo de protocolo e financeiro
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null);
$w_mod_pa = 'N';
$w_mod_fn = 'N';
foreach($RS as $row) {
  switch (f($row,'sigla')) {
    case 'PA': $w_mod_pa = 'S'; break;
    case 'FN': $w_mod_fn = 'S'; break;
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
        $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),5,
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
        $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),5,
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
      $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_servico);
    } else {
      $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_servico);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1], 'ord_codigo_interno', 'asc');
    } else {
      $RS = SortArray($RS, 'ord_codigo_interno', 'asc');
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
    if ($P1==1) {
      // Se for cadastramento e não for resultado de busca para cópia
      if ($w_embed!='WORD') { 
        ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;'); 
      }
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
      ShowHTML('          <td><b>'.LinkOrdena('Código','ord_codigo_interno').'</td>');
      if ($w_segmento=='Público' || $w_mod_pa=='S') {
        ShowHTML('          <td><b>'.LinkOrdena('Protocolo','processo').'</font></td>');
      }
      ShowHTML('          <td><b>'.LinkOrdena('Almoxarifado','nm_almoxarifado').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Beneficiário','nm_fornecedor_ind').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_mov').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Descrição','descricao').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('Itens','qt_itens').'</b></td>');
      ShowHTML('          <td class="remover"><b>Operações</b></td>');
      ShowHTML('        </tr>');
      
    } else {
      ShowHTML('          <td><b>Código</b></td>');
      if ($w_segmento=='Público' || $w_mod_pa=='S') {
        ShowHTML('          <td><b>Protocolo</b></td>');
      }
      ShowHTML('          <td><b>Almoxarifado</b></td>');
      ShowHTML ('         <td><b>Beneficiário</b></td>');
      ShowHTML('          <td><b>Tipo</b></td>');
      ShowHTML ('         <td><b>Descrição</b></td>');
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
        ShowHTML('        <td nowrap>');
        if ($w_tipo!='WORD') ShowHTML('        <A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.f($row,'codigo_interno').'&nbsp;</a>');
        else                 ShowHTML('        '.f($row,'codigo_interno').''); 
        if ($w_mod_pa=='S') {
          if ($w_embed!='WORD' && nvl(f($row,'protocolo_siw'),'')!='') {
            ShowHTML('        <td align="right"><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($row,'protocolo').'&nbsp;</a>');
          } else {
            ShowHTML('        <td align="right">'.f($row,'protocolo'));
          }
        }
        ShowHTML('        <td>'.f($row,'nm_almoxarifado').'</td>');
        ShowHTML('        <td>'.(($w_tipo=='WORD') ? f($row,'nm_fornecedor') : ExibePessoa('../',$w_cliente,f($row,'sq_fornecedor'),$TP,f($row,'nm_fornecedor'))).'</td>');
        ShowHTML('        <td>'.f($row,'nm_tp_mov').'</td>');
        ShowHTML('        <td>'.crlf2br(f($row,'descricao')).'</td>');
        ShowHTML('        <td align="right" width="1%">&nbsp;'.formatNumber(f($row,'qt_itens'),0).'&nbsp;</td>');
        if ($P1!=3 && $P1!=5 && $P1!=6 && $w_embed != 'WORD') {
          ShowHTML('        <td class="remover" width="1%" nowrap>&nbsp;');
          if ($P1==1) {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais da entrada">AL</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão da entrada.">EX</A>&nbsp');
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Listagem&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lançamento para outro responsável.">EN</A>&nbsp');
          } elseif ($P1==2) {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicitação para outro responsável.">EN</A>&nbsp');
            if (f($row,'sg_tramite')=='EE') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Efetivar baixa.">CO</A>&nbsp');
            } 
          }
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
        ShowHTML('        &nbsp;</td>');
        ShowHTML('      </tr>');
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
    $w_almoxarifado             = $_REQUEST['w_almoxarifado'];
    $w_descricao                = $_REQUEST['w_descricao'];
    $w_observacao               = $_REQUEST['w_observacao'];
    $w_fornecedor               = $_REQUEST['w_fornecedor'];
    $w_fornecedor_nm            = $_REQUEST['w_fornecedor_nm'];
    $w_protocolo                = $_REQUEST['w_protocolo'];
    $w_protocolo_nm             = $_REQUEST['w_protocolo_nm'];
    $w_tipo                     = $_REQUEST['w_tipo'];
  } elseif (strpos('AEV',$O)!==false || $w_copia>'') {
    // Recupera os dados da baixa
    $sql = new db_getMtBaixaBem; $RS_Solic = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
        null,null,null,null,null,null,null,null,null,null,
        $w_chave,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,null,null,null);
    if (count($RS_Solic)>0) {
      foreach($RS_Solic as $row){$RS_Solic=$row; break;}
      $w_almoxarifado             = f($RS_Solic,'sq_almoxarifado');
      $w_descricao                = f($RS_Solic,'descricao');
      $w_observacao               = f($RS_Solic,'observacao');
      $w_fornecedor               = f($RS_Solic,'sq_fornecedor');
      $w_fornecedor_nm            = f($RS_Solic,'nm_fornecedor');
      $w_tipo                     = f($RS_Solic,'sq_tipo_movimentacao');
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
  }
  
  if ($O!='I') {
    // Validação
    $w_erro = ValidaBaixa($w_cliente,$w_chave,$SG,null,null,null,null);
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
    if ($O=='I') {
      // Dados editáveis apenas na inclusão
      Validate('w_almoxarifado','Almoxarifado','SELECT','1','1','18','','1');
    }
    Validate('w_fornecedor_nm','Beneficiário','','1','1','40','1','1');
    Validate('w_tipo','Tipo da movimentação', 'SELECT', '1', '1', '18', '', '0123456789');
    Validate('w_descricao','Motivação para a baixa','1','1','2','500','1','1');
    if ($O=='E') {
      // Dados editáveis apenas na exclusão
      Validate('w_observacao','Motivação para o cancelamento da baixa','1','1','2','500','1','1');
    }
    ShowHTML('  document.Form.Botao[0].disabled = true;');
    ShowHTML('  document.Form.Botao[1].disabled = true;');
  } 
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
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

  if (strpos('IAEV',$O)!==false) {
    ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_unidade" value="'.$_SESSION['LOTACAO'].'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=4 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4 valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4>Os dados deste bloco serão utilizados para identificação da baixa, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan=4 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('      <tr>');
    if ($O=='I') {
      selecaoalmoxarifado('Al<u>m</u>oxarifado onde estão os bens a serem baixados:','M', 'Selecione o almoxarifado onde estão os bens a serem baixados.', $w_almoxarifado,'w_almoxarifado',null,null);
    } else {
      ShowHTML('      <tr><td><b>Almoxarifado:<br>'.f($RS_Solic,'nm_almoxarifado').'</td>');
      ShowHTML('<INPUT type="hidden" name="w_almoxarifado" value="'.$w_almoxarifado.'">');
    }
    SelecaoPessoaOrigem('Bene<u>f</u>iciário:', 'P', 'Clique na lupa para selecionar o beneficiário.', $w_fornecedor, null, 'w_fornecedor', null, null, null);
    if ($w_mod_pa=='S') {
      SelecaoProtocolo('Recuperar a partir do n<u>ú</u>mero do protocolo:','U','Selecione o protocolo de pagamento.',$w_protocolo,null,'w_protocolo',$SG,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_protocolo\'; document.Form.submit();"',3);
    }
    ShowHTML('      <tr valign="top">');
    SelecaoTipoMovimentacao('Tipo da <u>m</u>ovimentação:','M', 'Selecione o tipo da movimentação.', $w_tipo,null,'S','w_tipo',$SG,null);
    ShowHTML('      <tr><td colspan=3><b>Motivação para a <U>b</U>aixa:<br><TEXTAREA ACCESSKEY="B" class="sti" name="w_descricao" rows=3 cols=80." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
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
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificadas as pendências listadas abaixo, não sendo possível dar andamento à baixa.');
        }elseif (substr($w_erro,0,1)=='1') {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificadas as pendências listadas abaixo. O envio desta baixa só pode ser feita por um gestor do sistema ou deste módulo.');
        } else {
          ShowHTML('  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o envio desta baixa, mas convém sua verificação.');
        } 
        ShowHTML('  <ul>'.substr($w_erro,1,1000));
        ShowHTML('<tr><td colspan=2><HR>');
      } 
    
      // Itens
      AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'MTITEM',$w_pagina.$par,$O);
      ShowHTML(MontaFiltro('POST'));
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('    <tr><td colspan=3 align="center" height="1"></td></tr>');
      ShowHTML('    <tr><td colspan=3><b>Itens</b></td></tr>');

      ShowHTML('      <tr><td align="center" colspan=3>');
      ShowHTML('            Incluir RGP <INPUT '.$w_Disabled.' class="sti" type="text" name="w_rgp" size="14" maxlength="14" value="">');
      ShowHTML('            <input '.$w_Disabled.' class="STB" type="submit" name="Botao" value="Gravar">');
      ShowHTML('          </td>');
      ShowHTML('      </tr>');

      ShowHTML('    <tr><td colspan=3 align="center" height="1"></td></tr>');
      ShowHTML('    <tr><td colspan="3"><table width="100%" border="0">');
      $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ITENS',3,
          null,null,null,null,null,null,null,null,null,null,$w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
      $RS = SortArray($RS,'numero_rgp','asc','nome','asc'); 
      if (count($RS)==0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align=center><b>Itens não informados</b></td></tr>');
      } else {
        $colspan = 0;
        ShowHTML('      <tr><td align="center" colspan="2">');
        ShowHTML('        <table class="tudo" width="100%" BORDER=1 bordercolor="#666666">');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td><b>RGP</b></td>');
        ShowHTML('          <td><b>Descrição</b></td>');
        ShowHTML('          <td><b>Operações</b></td>');
        ShowHTML('        </tr>');
        // Lista os registros selecionados para listagem
        foreach($RS as $row){ 
          ShowHTML('      <tr valign="top">');
          ShowHTML('        <td align="center">'.f($row,'numero_rgp').'</td>');
          ShowHTML('        <td>'.ExibePermanente($w_dir_volta,$w_cliente,f($row,'nome_completo'),f($row,'sq_permanente'),$TP,null,null).'</td>');
          ShowHTML('        <td><A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_saida_item').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MTITEM" title="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A></td>');
        }
        ShowHTML('    </table>');
      }
      ShowHTML('      <tr><td colspan=2 align=center><hr /></td></tr>');
      ShowHTML('       </table>');
      ShowHTML('</FORM>');

      /*
      // Arquivos
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
      */
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
  $sql = new db_getMtBaixaBem; $RS_Solic = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
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
  ShowHTML('            <td>Código: <b>'.f($RS_Solic,'codigo_interno').'</b></td>');
  ShowHTML('            <td>Almoxarifado: <b>'.f($RS_Solic,'nm_almoxarifado').'</b></td>');
  ShowHTML('            <td>Tipo: <b>'.f($RS_Solic,'nm_tp_mov').'</b></td>');
  ShowHTML('          </tr>');
  ShowHTML('          <tr><td colspan=3>Beneficiário: <b>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'sq_fornecedor'),$TP,f($RS_Solic,'nm_fornecedor')).'</b></td></tr>');
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
  ShowHTML(VisualBaixa($w_chave,'L',$w_usuario,$P1,$w_embed));
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
  ShowHTML('</HEAD>');
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
  
  $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
    null,null,null,null,null,null,null,null,null,null,$w_chave,null,null,null,null,null,null,
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
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
  } else {
    // Recupera os dados da solicitacao
    $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
      null,null,null,null,null,null,null,null,null,null,$w_chave,null,null,null,null,null,null,
      null,null,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
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
  if ($O=='V') $w_erro = ValidaBaixa($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
      if (strpos($w_erro,'pesquisa')!==false) {
        Validate('w_just_pesquisa', 'Justificativa para o não cumprimento do número mínimo de pesquisas de preço', '', '1', '1', '2000', '1', '1');
      } 
      if (strpos($w_erro,'proposta')!==false) {
        Validate('w_just_proposta', 'Justificativa para o não cumprimento do número mínimo de propostas', '', '1', '1', '2000', '1', '1');
      } 
    } elseif ($w_sg_tramite!='CI') {
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
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    
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
  ShowHTML(VisualBaixa($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || $w_ativo=='N' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'MTBXENVIO',$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('    <tr><td colspan="2"><table border=0 width="100%">');
    
    if ($w_sg_tramite=='CI') {
      if (!(substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE' || $w_ativo=='N')) {
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
      ShowHTML('    <tr><td><b>Tipo do encaminhamento</b><br>');
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
      SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)','F','Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','DEVFLUXO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolução):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a PCD.">'.$w_despacho.'</TEXTAREA></td>');
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
// Rotina de conclusão
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  //Recupera os dados da solicitação
  $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
        null,null,null,null,null,null,null,null,null,null,$w_chave,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  
  $w_tramite       = f($RS,'sq_siw_tramite');
  $w_efetivacao   = FormataDataEdicao(f($RS,'data_efetivacao'));

  // Se for recarga da página
  if ($w_troca>'') {
    $w_efetivacao         = $_REQUEST['w_efetivacao'];
    $w_nota_conclusao     = $_REQUEST['w_nota_conclusao'];
  }

  // Verifica se há erros na baixa
  $w_erro = ValidaBaixa($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);

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
  SaltaCampo();
  ValidateOpen('Validacao');
  Validate('w_efetivacao','Data de efetivação da baixa','DATA','1',10,10,'','0123456789/');
  Validate('w_nota_conclusao','Nota de conclusão','','','1','2000','1','1');
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if (substr(Nvl($w_erro,'nulo'),0,1)!='0') {
    BodyOpen('onLoad="document.Form.w_efetivacao.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualBaixa($w_chave,'L',$w_usuario,$P1,$P4));
  
  if (substr(Nvl($w_erro,'nulo'),0,1)!=='0') {
    ShowHTML('<HR>');
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'MTBXCONC', $w_pagina . $par, $O, null, 'enctype="multipart/form-data"');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_caminho.'">');
    ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="' . f($RS_Cliente, 'upload_maximo') . '">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="100%" border="0">');
    ShowHTML('    <tr valign="top">');
    ShowHTML('      <td><b><u>D</u>ata de efetivação da baixa:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_efetivacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_homologacao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_efetivacao').'</td>');
    ShowHTML('    <tr><td colspan="3"><b>Nota d<u>e</u> conclusão:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Descreva o quanto o projeto atendeu aos resultados esperados.">'.$w_nota_conclusao.'</TEXTAREA></td>');
    
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
  } else {
    ShowHTML('    <tr><td align="center" colspan=3><hr>');
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
  }
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
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'MTBAIXA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        // Recupera o código da situação inicial da movimentação
        $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,'BEM',null,'S',null,'IN');
        if (count($RS)==0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: A tabela de situações precisa ser carrregada com o registro INSERVÍVEL!");');
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
             
        $SQL = new dml_putMtBaixaBem; $SQL->getInstanceOf($dbms,$O,$w_cliente,$w_usuario,$_REQUEST['w_chave'],$_REQUEST['w_menu'],
          $_REQUEST['w_unidade'],$_REQUEST['w_descricao'],$_REQUEST['w_observacao'],$_REQUEST['w_almoxarifado'],
          $_REQUEST['w_fornecedor'],$_REQUEST['w_tipo'],$w_chave_nova, $w_codigo);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href="'.montaURL_JS($w_dir,$w_pagina.(($O=='E') ? 'inicial&O=L' : 'geral&O=A&w_chave='.nvl($_REQUEST['w_chave'],$w_chave_nova)).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'";');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'MTITEM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {

        $SQL = new dml_putMtBaixaItem; 
        $SQL->getInstanceOf($dbms,(($_REQUEST['w_chave_aux']=="") ? 'I' : 'E'),$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_rgp']);
        
        ScriptOpen('JavaScript');
        ShowHTML('  location.href="'.montaURL_JS($w_dir,$w_pagina.'geral&O=A&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=MTBAIXA'.MontaFiltro('GET')).'";');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }     
      break;
    case 'MTBAANEXO':
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
    case 'MTBXENVIO':
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
          $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
                null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_chave'],null,null,null,null,null,null,
                null,null,null,null,null,null,null,null,null,null,null);
          foreach($RS as $row){$RS=$row; break;}
          
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
                $w_html = VisualBaixa($_REQUEST['w_chave'], 'L', $w_usuario, null, '1');
                CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
              }
            }
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
    case 'MTBXCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {

        $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
              null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_chave'],null,null,null,null,null,null,
              null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}

        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATENÇÃO: Outro usuário já encaminhou o pedido para fase de execução!");');
          ScriptClose();
          exit();
        } else {            
          // Registra a conclusão da solicitação
          $SQL = new dml_putSolicConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_efetivacao'],
              null,$_REQUEST['w_nota_conclusao'],null,null,null,null,null,null,null,null,null,null,null);
              
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
  case 'INICIAL':           Inicial(); break;
  case 'GERAL':             Geral(); break;
  case 'ANEXOS':            Anexos(); break;
  case 'VISUAL':            Visual(); break;
  case 'EXCLUIR':           Excluir(); break;
  case 'ENVIO':             Encaminhamento(); break;
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

