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
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicObjetivo.php');
include_once($w_dir_volta.'classes/sp/db_getMatServ.php');
include_once($w_dir_volta.'classes/sp/db_getCLSolicItem.php');
include_once($w_dir_volta.'classes/sp/db_getCLFinanceiro.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getFNParametro.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putCLGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putCLDados.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicConc.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putCLSolicItem.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'funcoes/retornaCadastrador_CL.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
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
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoMoeda.php');
include_once('visualpedido.php');
include_once('validapedido.php');

// =========================================================================
//  /pedido.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o seviço de pedido de compra
// Mail     : celso@sbpi.com.br
// Criacao  : 24/08/2007, 11:00
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
$w_pagina       = 'pedido.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_cl/';
$w_troca        = $_REQUEST['w_troca'];
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
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
$w_cadgeral = RetornaCadastrador_CL($w_menu, $w_usuario);

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

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

// Verifica se o cliente tem o módulo de protocolo e arquivo
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null);
$w_pa='N';
$w_fn='N';
foreach($RS as $row) {
  switch (f($row,'sigla')) {
  case 'PA': $w_pa = 'S'; break;
  case 'FN': $w_fn = 'S'; break;
  }
}

if ($w_fn=='S') {
  // Recupera os parâmetros de funcionamento do módulo
  $sql = new db_getFNParametro; $RS_FN = $sql->getInstanceOf($dbms,$w_cliente,null,null);
  foreach($RS_FN as $row) { $RS_FN = $row; break; }
}
// Recupera os parâmetros de funcionamento do módulo de compras
$sql = new db_getParametro; $RS_Parametro = $sql->getInstanceOf($dbms,$w_cliente,'CL',null);
foreach($RS_Parametro as $row){$RS_Parametro=$row; break;}
$w_pede_valor_pedido       = f($RS_Parametro,'pede_valor_pedido');

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
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),5,
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
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),5,
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
      $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_servico);
    } else {
      $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
          null, null, $p_empenho, $p_servico);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'phpdt_inclusao','desc', 'fim', 'desc', 'prioridade', 'asc');
    } else {
      $RS = SortArray($RS,'phpdt_inclusao','desc', 'fim', 'desc', 'prioridade', 'asc');
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
        Validate('p_codigo','Número do pedido','','','2','60','1','1');
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
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_Troca>'') {
      // Se for recarga da página
      BodyOpenClean('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } elseif (strpos('CP',$O)!==false) {
      BodyOpenClean('onLoad=\'document.Form.p_codigo.focus()\';');
    } elseif ($P1==2) {
      BodyOpenClean(null);
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    if ((strpos(upper($R), 'GR_')) === false) {
      Estrutura_Texto_Abre();
    } else {
      CabecalhoRelatorio($w_cliente, 'Consulta de ' . f($RS_Menu, 'nome'), 4);
    }
    if ($w_filtro > '') ShowHTML($w_filtro);
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    if ($w_embed=='WORD') { 
      ShowHTML('<tr><td colspan=2>');
    } else {
      ShowHTML('<tr><td>');
      if ($P1==1 && $w_copia=='') {
        // Se for cadastramento e não for resultado de busca para cópia
        if ($w_embed!='WORD') { 
          ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;'); 
          ShowHTML('    <a accesskey="C" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
        }
      } 
      if ((strpos(upper($R),'GR_'))===false && $P1!=6 && $w_embed!='WORD') {
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
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Código','phpdt_inclusao').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Objeto','objeto').'</td>');
      if ($_SESSION['INTERNO']=='S') { $colspan++; ShowHTML ('          <td><b>'.LinkOrdena('Vinculação','dados_pai').'</td>'); }
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Solicitante','sg_unidade_resp').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>'.LinkOrdena('$ Estimado','valor').'</td>');
      if ($P1!=1) ShowHTML('          <td><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      ShowHTML('          <td class="remover"><b>Operações</td>');
      ShowHTML('        </tr>');
      
    } else {
      $colspan++; ShowHTML('          <td><b>Código</td>');
      $colspan++; ShowHTML('          <td><b>Justificativa</td>');
      if ($_SESSION['INTERNO']=='S') { $colspan++; ShowHTML ('          <td><b>Vinculação</td>'); }
      $colspan++; ShowHTML('          <td><b>Solicitante</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>$ Estimado</td>');
      if ($P1!=1) ShowHTML('          <td><b>Fase atual</td>');
      ShowHTML('        </tr>');
    }
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="'.($colspan+3).'" align="center"><b>Não foram encontrados registros.</b></td></tr>');
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
        if($w_embed!='WORD') {
          ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
        } else {
          ShowHTML('        '.f($row,'codigo_interno'));
        }
        ShowHTML('        <td>'.f($row,'objeto').'</td>');
        if ($_SESSION['INTERNO']=='S') {
           if (Nvl(f($row,'dados_pai'),'')!='') ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai')).'</td>');
          else                                 ShowHTML('        <td>---</td>');
        } 
        ShowHTML('        <td width="1%" nowrap>&nbsp;'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade'),$TP).'&nbsp;</td>');
        if ($_SESSION['INTERNO']=='S') ShowHTML('        <td align="right" width="1%" nowrap>&nbsp;'.((nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').formatNumber(f($row,'valor'),2).'&nbsp;</td>');
        $w_parcial[f($row,'sb_moeda')] = nvl($w_parcial[f($row,'sb_moeda')],0) + f($row,'valor');
        if ($P1!=1) ShowHTML('        <td>'.f($row,'nm_tramite').'</td>');
        if($w_embed!='WORD') {
          ShowHTML('        <td class="remover" width="1%" nowrap>');
          if ($P1!=3 && $P1!=5 && $P1!=6) {
            // Se não for acompanhamento
            if ($w_copia>'') {
              // Se for listagem para cópia
              $sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
              foreach($RS as $row1) { $RS = $row1; break; }
              ShowHTML('          <a accesskey="I" class="HL" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
            } elseif ($P1==1) {
              // Se for cadastramento
              if ($w_submenu>'') {
                ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'codigo_interno').MontaFiltro('GET').'" title="Altera as informações cadastrais do pedido" TARGET="menu">AL</a>&nbsp;');
              } else {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais do pedido">AL</A>&nbsp');
              } 
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão do pedido.">EX</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Itens&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Itens'.'&SG='.substr($SG,0,4).'ITEM').'\',\'Itens\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Escolhe os itens da compra.">Itens</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Anexos&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Anexos'.'&SG='.substr($SG,0,4).'ANEXO').'\',\'Anexos\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Vincula arquivos ao pedido de compra.">Anexos</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento do pedido">EN</A>&nbsp');
            } elseif ($P1==2) {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a solicitação, sem enviá-la.">AN</A>&nbsp');
              if (f($row,'sg_tramite')=='AF') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Atender&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Autorizar compra.">AT</A>&nbsp');
              } 
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a solicitação para outro responsável.">EN</A>&nbsp');
              if (f($row,'sg_tramite')=='EC' || f($row,'sg_tramite')=='EE') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Concluir a solicitação.">CO</A>&nbsp');
              } 
            } 
          } else {
            if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o pedido para outro responsável.">EN</A>&nbsp');
            } else {
              ShowHTML('          ---&nbsp');
            } 
          } 
          ShowHTML('        </td>');
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
          ShowHTML('          <td'.(($w_embed=='WORD') ? '': ' colspan=4').'>&nbsp;</td>');
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
          foreach($w_total as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
          echo('</td>');
          ShowHTML('          <td'.(($w_embed=='WORD') ? '': ' colspan=4').'>&nbsp;</td>');
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
      ShowHTML('  alert(\'ATENÇÃO: Sua lotação não tem permissão para registrar pedidos de compra. Entre em contato com os gestores do sistema!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
      exit;
    } 
  }

  // Verifica se o cliente tem o módulo de planejamento estratégico
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PE');
  if (count($RS)>0) $w_pe='S'; else $w_pe='N';
  $w_pe = 'N'; // Trava para evitar exibição dos dados do módulo de planejamento estratégico. 


  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_sq_menu_relac            = $_REQUEST['w_sq_menu_relac'];    
    if($w_sq_menu_relac=='CLASSIF') {
      $w_solic_pai              = '';
    } else {
      $w_solic_pai              = $_REQUEST['w_solic_pai'];
    }
    $w_codigo                   = $_REQUEST['w_codigo'];
    $w_chave_pai                = $_REQUEST['w_chave_pai'];
    $w_solic_pai                = $_REQUEST['w_solic_pai'];    
    $w_plano                    = $_REQUEST['w_plano'];
    $w_sqcc                     = $_REQUEST['w_sqcc'];
    $w_objetivo                 = explodeArray($_REQUEST['w_objetivo']);
    $w_prioridade               = $_REQUEST['w_prioridade'];
    $w_aviso                    = $_REQUEST['w_aviso'];
    $w_dias                     = $_REQUEST['w_dias'];
    $w_chave_aux                = $_REQUEST['w_chave_aux'];
    $w_sq_menu                  = $_REQUEST['w_sq_menu'];
    $w_sq_unidade               = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite               = $_REQUEST['w_sq_tramite'];
    $w_solicitante              = $_REQUEST['w_solicitante'];
    $w_cadastrador              = $_REQUEST['w_cadastrador'];
    $w_executor                 = $_REQUEST['w_executor'];
    $w_inicio                   = $_REQUEST['w_inicio'];
    $w_fim                      = $_REQUEST['w_fim'];
    $w_valor                    = $_REQUEST['w_valor'];
    $w_inclusao                 = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao         = $_REQUEST['w_ultima_alteracao'];
    $w_justificativa            = $_REQUEST['w_justificativa'];
    $w_observacao               = $_REQUEST['w_observacao'];
    $w_decisao_judicial         = $_REQUEST['w_decisao_judicial'];      
    $w_numero_original          = $_REQUEST['w_numero_original'];
    $w_data_recebimento         = $_REQUEST['w_data_recebimento'];
    $w_cidade                   = $_REQUEST['w_cidade'];
    $w_especie_documento        = $_REQUEST['w_especie_documento'];
    $w_financeiro               = $_REQUEST['w_financeiro'];
    $w_rubrica                  = $_REQUEST['w_rubrica'];
    $w_lancamento               = $_REQUEST['w_lancamento'];
    $w_objeto                   = $_REQUEST['w_objeto'];
    $w_moeda                    = $_REQUEST['w_moeda'];
  } else {
    if (strpos('AEV',$O)!==false || $w_copia>'') {
      // Recupera os dados do pedido
      if ($w_copia>'') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_copia,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
      } else {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
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
        $w_solic_pai         = f($RS,'sq_solic_pai');
        $w_prioridade        = f($RS,'prioridade');
        $w_aviso             = f($RS,'aviso_prox_conc');
        $w_dias              = f($RS,'dias_aviso');
        $w_chave_pai         = f($RS,'sq_solic_pai');
        $w_sq_menu           = f($RS,'sq_menu');
        $w_sq_unidade        = f($RS,'sq_unidade');
        $w_sq_tramite        = f($RS,'sq_siw_tramite');
        $w_solicitante       = f($RS,'solicitante');
        $w_cadastrador       = f($RS,'cadastrador');
        $w_executor          = f($RS,'executor');
        $w_sqcc              = f($RS,'sq_cc');
        $w_justificativa     = f($RS,'justificativa');
        $w_observacao        = f($RS,'observacao');
        $w_inicio            = FormataDataEdicao(f($RS,'inicio'));
        $w_fim               = FormataDataEdicao(f($RS,'fim'));
        $w_valor             = formatNumber(f($RS,'valor'));
        $w_inclusao          = f($RS,'inclusao');
        $w_ultima_alteracao  = f($RS,'ultima_alteracao');
        $w_decisao_judicial  = f($RS,'decisao_judicial');
        $w_cidade            = f($RS,'cidade_origem');
        $w_numero_original   = f($RS,'numero_original');
        $w_data_recebimento  = FormataDataEdicao(f($RS,'data_recebimento'));
        $w_especie_documento = f($RS,'sq_especie_documento');
        $w_financeiro        = f($RS,'sq_financeiro');
        $w_rubrica           = f($RS,'sq_projeto_rubrica');
        $w_lancamento        = f($RS,'sq_tipo_lancamento');
        $w_objeto            = f($RS,'objeto');
        $w_moeda             = f($RS,'sq_moeda');
        if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
      } 
    } 
  } 

  // Se não puder cadastrar para outros, carrega os dados do usuário logado
  if ($w_cadgeral=='N') {
    $w_sq_unidade  = $_SESSION['LOTACAO'];
    $w_solicitante = $_SESSION['SQ_PESSOA'];
  } 

  // Recupera os parâmetros da unidade solicitante
  $sql = new db_getUorgList; $RS_Unid_CL = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_unidade,'CLUNID',null,null,$w_ano);
  foreach($RS_Unid_CL as $row) { $RS_Unid_CL = $row; break; }

  if ($w_solic_pai>'') {
    // Recupera as possibilidades de vinculação financeira
    $sql = new db_getCLFinanceiro; $RS_Financ = $sql->getInstanceOf($dbms,$w_cliente,$w_menu,$w_solic_pai,null,null,null,null,null,null,null,null);
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
      ShowHTML('      alert(\'Você deve informar pelo menos um objetivo estratégico!\'); ');
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
        ShowHTML('    alert(\'Informe um plano estratégico ou uma vinculação. Você não pode escolher ambos!\');');
        ShowHTML('    theForm.w_plano.focus();');
        ShowHTML('    return false;');
      } elseif(nvl($w_sq_menu_relac,'')=='' && nvl($w_plano,'')=='') {
        ShowHTML('    alert(\'Informe um plano estratégico ou uma vinculação!\');');
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
    if($w_cadgeral=='S') {
      Validate('w_solicitante','Solicitante','HIDDEN',1,1,18,'','0123456789');
      Validate('w_sq_unidade','Unidade solicitante','HIDDEN',1,1,18,'','0123456789');
    }
    /*
    Validate('w_prioridade','Prioridade','SELECT',1,1,1,'','0123456789');
    Validate('w_fim','Limite para atendimento','DATA',1,10,10,'','0123456789/');
    if($w_decisao_judicial=='S') {
       CompData('w_fim','Limite para atendimento','>=','w_data_recebimento','Data atual');
    } else {
      CompData('w_fim','Limite para atendimento','>=','w_inicio','Data atual');
    }
    */
    if ($w_pede_valor_pedido=='S') {
      if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') Validate('w_moeda','Moeda','SELECT',1,1,18,'','0123456789');
      Validate('w_valor','Valor estimado','VALOR',1,4,18,'','0123456789,.');
      CompValor('w_valor','Valor estimado','>',0,'zero');
    }
    if($w_decisao_judicial=='S') {
      Validate('w_numero_original','Número original','','1',1,30,'1','1');
      Validate('w_data_recebimento','Data de recebimento','DATA',1,10,10,'','0123456789/');
      Validate('w_especie_documento','Espécie documental','SELECT',1,1,18,'','0123456789');
    }
    Validate('w_objeto','Objeto','','1',3,2000,'1','1');
    Validate('w_justificativa','Justificativa','','',3,2000,'1','1');
    Validate('w_observacao','Observação','','',3,2000,'1','1');
    if (count($RS_Financ)>1) {
      Validate('w_rubrica','Rubrica','SELECT',1,1,18,'','0123456789');
      Validate('w_lancamento','Tipo de lançamento','SELECT',1,1,18,'','0123456789');
    }
    /*
    if($w_decisao_judicial=='N') {
      Validate('w_dias','Dias de alerta do pedido','1','',1,3,'','0123456789');
      ShowHTML('  if (theForm.w_aviso[0].checked) {');
      ShowHTML('     if (theForm.w_dias.value == \'\') {');
      ShowHTML('        alert(\'Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!\');');
      ShowHTML('        theForm.w_dias.focus();');
      ShowHTML('        return false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     theForm.w_dias.value = \'\';');
      ShowHTML('  }');
    }
    */
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
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    if(nvl($w_decisao_judicial,'N')=='N') {
      ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.FormataDataEdicao(time()).'">');
    }
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3>Os dados deste bloco serão utilizados para identificação do pedido de compra, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    if(nvl(f($RS_Menu,'numeracao_automatica'),0)==0) {
      ShowHTML('      <tr><td><b><U>C</U>ódigo interno:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="w_codigo" size="18" maxlength="60" value="'.$w_codigo.'"></td>');
    }
    if ($w_pe=='S') {
      ShowHTML('          <tr valign="top">');
      selecaoPlanoEstrategico('<u>P</u>lano estratégico:', 'P', 'Selecione o plano ao qual o programa está vinculado.', $w_plano, $w_chave, 'w_plano', 'ULTIMO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_plano\'; document.Form.submit();"');
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
    if ($w_cadgeral=='N') {
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
    } else {
      // Recupera todos os registros para a listagem
      ShowHTML('          <tr valign="top">');
      SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',nvl($w_sq_unidade,$_SESSION['LOTACAO']),null,'w_sq_unidade','CLCP','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_unidade\'; document.Form.submit();"',3);
    }
    if (f($RS_Unid_CL,'registra_judicial')=='S') {
      MontaRadioNS('<b>Decisão judicial?</b>',$w_decisao_judicial,'w_decisao_judicial',null,null,'onClick="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_alerta\'; document.Form.submit();"');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_decisao_judicial" value="N">');
    }
    if(nvl($w_decisao_judicial,'N')=='S') {
      ShowHTML('       <tr valign="top">');
      ShowHTML('          <td valign="top"><b><U>N</U>úmero original:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="w_numero_original" size="30" maxlength="30" value="'.$w_numero_original.'"></td>');
      ShowHTML('          <td valign="top"><b><u>D</u>ata de recebimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_recebimento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_recebimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data de recebimento do pedido.">'.ExibeCalendario('Form','w_data_recibimento').'</td>');
      selecaoEspecieDocumento('<u>E</u>spécie documental:','E','Selecione a espécie do documento.',$w_especie_documento,null,'w_especie_documento',null,null);
    }
    if ($w_cadgeral=='N') {
      ShowHTML('<INPUT type="hidden" name="w_solicitante" value="'.$w_solicitante.'">');
    } else {
      ShowHTML('          <tr valign="top">');
      if(nvl($w_decisao_judicial,'N')=='N') {
        SelecaoPessoa('<u>S</u>olicitante:','S','Selecione o solicitante do pedido na relação.',nvl($w_solicitante,$_SESSION['SQ_PESSOA']),null,'w_solicitante','USUARIOS');
      } else {
        SelecaoPessoaOrigem('<u>S</u>olicitante:','s','Clique na lupa para selecionar o solicitante do pedido.',$w_solicitante,null,'w_solicitante',null,null,null);
      }
    }
    ShowHTML('          <tr valign="top">');
    //SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade deste projeto.',$w_prioridade,null,'w_prioridade',null,null);
    //ShowHTML('            <td valign="top"><b><u>L</u>imite para atendimento:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data limite para quem o atendimento do pedido seja atendido.">'.ExibeCalendario('Form','w_fim').'</td>');    
    ShowHTML('<INPUT type="hidden" name="w_prioridade" value="'.$w_prioridade.'">');
    //ShowHTML('<INPUT type="hidden" name="w_fim" value="'.$w_fim.'">');
    if ($w_pede_valor_pedido=='S') {
      if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') {
        selecaoMoeda('<u>M</u>oeda:','U','Selecione a moeda na relação.',$w_moeda,null,'w_moeda','ATIVO',null);
      }
      ShowHTML('            <td colspan="2"><b><u>V</u>alor estimado:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor estimado para a solicitação."></td>');
    }
    ShowHTML('      <tr><td colspan=3><b>O<u>b</u>jeto:</b><br><textarea '.$w_Disabled.' accesskey="B" name="w_objeto" class="STI" ROWS=5 cols=75 title="É obrigatório informar o objeto.">'.$w_objeto.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan=3><b><u>J</u>ustificativa:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Informe a justificativa para a compra.">'.$w_justificativa.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan=3><b><u>O</u>bservações:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>'.$w_observacao.'</TEXTAREA></td>');
    if ($w_solic_pai>'') {
      if (count($RS_Financ)>1) {
        ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Dados para Pagamento</td></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr valign="top">');
        SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_rubrica,$w_solic_pai,'T','w_rubrica','CLFINANC','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_rubrica\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lançamento:','T','Selecione na lista o tipo de lançamento adequado.',$w_lancamento,null,$w_cliente,'w_lancamento','CLPC'.str_pad($w_solic_pai,10,'0',STR_PAD_LEFT).str_pad($w_rubrica,10,'0',STR_PAD_LEFT).'T',null);
      } elseif (count($RS_Financ)==1) {
        foreach($RS_Financ as $row) { $RS_Financ = $row; break; }
        ShowHTML('<INPUT type="hidden" name="w_financeiro" value="'.f($RS_Financ,'chave').'">');
      }
    }
    /*
    if(nvl($w_decisao_judicial,'N')=='N') {
      ShowHTML('      <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Alerta de proximidade da data de término</td></td></tr>');
      ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan=3>Os dados abaixo indicam como deve ser tratada a proximidade da data Término previsto do projeto.</td></tr>');
      ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr valign="top">');
      MontaRadioNS('<b>Emite alerta?</b>',$w_aviso,'w_aviso');
      ShowHTML('        <td><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="Número de dias para emissão do alerta de proximidade da data Término previsto do projeto."></td>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    }
    */
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="N">');

    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
// Rotina de itens da compra
// -------------------------------------------------------------------------
function Itens() {
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
  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Solic as $row){$RS_Solic=$row; break;}

  if ($w_troca>'' && $O <> 'E') {
    $w_sq_material        = $_REQUEST['w_sq_material'];
    $w_quantidade         = $_REQUEST['w_quantidade'];
    $w_detalhamento       = $_REQUEST['w_detalhamento'];
  } elseif ($O=='I') {
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
    $sql = new db_getCLSolicItem; $RS = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'nm_tipo_material','asc','nome','asc'); 
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getCLSolicItem; $RS_Item = $sql->getInstanceOf($dbms,$w_chave_aux,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS_Item as $row) {$RS_Item = $row; break;}
    $w_chave_aux           = f($RS_Item,'chave');
    $w_material            = f($RS_Item,'sq_material');
    $w_detalhamento        = f($RS_Item,'det_item');
    $w_quantidade          = formatNumber(f($RS_Item,'quantidade'),0);
  } 

  // Recupera informações sobre o tipo do material ou serviço
  if (nvl($w_tipo_material,'')!='') {
    $sql = new db_getTipoMatServ; $RS_Tipo = $sql->getInstanceOf($dbms,$w_cliente,$w_tipo_material,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS_Tipo as $row) { $RS_Tipo = $row; break; }
    $w_classe = f($RS_Tipo,'classe');
  } 

  Cabecalho();
  head();
  ShowHTML('<TITLE>'.f($RS_Menu,'nome').' - Itens</TITLE>');
  Estrutura_CSS($w_cliente);
  Estrutura_CSS($w_cliente);
  if (strpos('PLIA',$O)!==false) {
    ScriptOpen('JavaScript');
    if ($O=='I') {
      ShowHTML('  function valor(p_indice) {');
      ShowHTML('    if (document.Form["w_sq_material[]"][p_indice].checked) { ');
      ShowHTML('       document.Form["w_quantidade[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_quantidade[]"][p_indice].focus(); ');
      ShowHTML('    } else {');
      ShowHTML('       document.Form["w_quantidade[]"][p_indice].disabled=true; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  function MarcaTodos() {');
      ShowHTML('    if (document.Form["w_sq_material[]"].value==undefined) ');
      ShowHTML('       for (i=0; i < document.Form["w_sq_material[]"].length; i++) {');
      ShowHTML('         document.Form["w_sq_material[]"][i].checked=true;');
      ShowHTML('         document.Form["w_quantidade[]"][i].disabled=false;');
      ShowHTML('       } ');
      ShowHTML('    else document.Form["w_sq_material[]"].checked=true;');
      ShowHTML('  }');
      ShowHTML('  function DesmarcaTodos() {');
      ShowHTML('    if (document.Form["w_sq_material[]"].value==undefined) ');
      ShowHTML('       for (i=0; i < document.Form["w_sq_material[]"].length; i++) {');
      ShowHTML('         document.Form["w_sq_material[]"][i].checked=false;');
      ShowHTML('         document.Form["w_quantidade[]"][i].disabled=true;');
      ShowHTML('       } ');
      ShowHTML('    ');
      ShowHTML('    else document.Form["w_sq_material[]"].checked=false;');
      ShowHTML('  }');
    }     
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if ($O=='P') {
      Validate('p_nome','Nome','1','','3','30','1','1');
      Validate('p_codigo','Código interno','1','','2','30','1','1');
      Validate('p_tipo_material','Tipo do material ou serviço','SELECT','','1','18','','1');
      if ($w_cliente!=6881) Validate('p_sq_cc','Classificação','SELECT','','1','18','','1');
      ShowHTML('if (theForm.p_nome.value=="" && theForm.p_codigo.value=="" && theForm.p_tipo_material.value=="" && theForm.p_sq_cc.value=="") {');
      ShowHTML(' alert("Informe pelo menos um critério de filtragem!");');
      ShowHTML(' return false;');
      ShowHTML('}');
    } elseif($O=='I') {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_sq_material[]"].value==undefined) {');
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
      ShowHTML('    if((theForm["w_sq_material[]"][i].checked)&&(theForm["w_quantidade[]"][i].value=="")){');
      ShowHTML('      alert("Para todas os itens selecionados você deve informar a quantidade!"); ');
      ShowHTML('      return false;');
      ShowHTML('    }');
      ShowHTML('    if((theForm["w_sq_material[]"][i].checked)&&(theForm["w_quantidade[]"][i].value=="0,00")){');
      ShowHTML('      alert("Para todas os itens selecionados você deve informar a quantidade maior que zero!"); ');
      ShowHTML('      return false;');
      ShowHTML('    }');
      ShowHTML('  }');
    } elseif($O=='A') {
      Validate('w_quantidade','Quantidade','1','1','1','18','','1');
      CompValor('w_quantidade','Quantidade','>',0,'1');  
      Validate('w_detalhamento','Detalhamento das características do item','1','','2','4000','1','1');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    if ($O=='P'){
      ShowHTML('  theForm.Botao[2].disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='P'){
    BodyOpen('onLoad="document.Form.p_nome.focus();"');
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
  ShowHTML('            <td>Data do pedido:<b><br>'.formataDataEdicao(f($RS_Solic,'inicio')).'</td>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Unidade solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');

  if ($O=='L') {
    ShowHTML('<tr><td>');
    ShowHTML('                <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Código','codigo_interno').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('U.M.','sg_unidade_medida').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Qtd','quantidade').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Detalhamento','det_item').'</td>');
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
        ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');
        ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>');
        ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>');
        ShowHTML('        <td>'.crlf2br(f($row,'det_item')).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('        </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
  } elseif ($O=='I') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_material[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_quantidade[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('      <tr><td colspan="2" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: se o item desejado não existir, entre em contato com '.ExibeUnidade('../',$w_cliente,f($RS_Menu,'sg_unidade'),f($RS_Menu,'sq_unid_executora'),$TP).'.</font></td>');
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
    ShowHTML('          <td><b>'.LinkOrdena('U.M.','sg_unidade_medida').'</td>');
    ShowHTML('          <td><b>Quantidade</td>');
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
        ShowHTML('        <td><input type="text" disabled name="w_quantidade[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.f($row,'quantidade').'" style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);" title="Informe a  quantidade."></td>');
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
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_material" value="'.$w_material.'">');
    ShowHTML('<INPUT type="hidden" name="w_qtd_ant" value="'.$w_quantidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('      <tr><td>Material:<br><b>'.f($RS_Item,'codigo_interno').' - '.f($RS_Item,'nome').'</b><br><br></td>');
    ShowHTML('      <tr><td><b><u>Q</u>uantidade:<br><input accesskey="Q" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" '.$w_Disabled.' style="text-align:right;" onKeyDown="FormataValor(this,18,0,event);"></td>');
    ShowHTML('      <tr><td><b><u>D</u>etalhamento:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_detalhamento" class="STI" ROWS=5 cols=75 title="Descreva as características desejadas para este item, de modo a evitar mal entendidos sobre o que se deseja.">'.$w_detalhamento.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_menu='.$w_menu.'&w_chave='.$w_chave.'&w_chave_aux='.$w_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');    
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
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
    if ($w_cliente!=6881) {
      ShowHTML('      <tr valign="top">');
      SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',$p_sq_cc,null,'p_sq_cc','SIWSOLIC');
    } else {
      ShowHTML('<INPUT type="hidden" name="p_sq_cc" value="">');
    }
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
  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
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
  ShowHTML('<TITLE>'.f($RS_Menu,'nome').' - Anexos</TITLE>');
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
  ShowHTML('            <td>Data do pedido:<b><br>'.formataDataEdicao(f($RS_Solic,'inicio')).'</td>');
  ShowHTML('          <tr valign="top">');
  ShowHTML('            <td>Solicitante:<b><br>'.ExibePessoa('../',$w_cliente,f($RS_Solic,'solicitante'),$TP,f($RS_Solic,'nm_solic')).'</td>');
  ShowHTML('            <td>Unidade solicitante:<b><br>'.ExibeUnidade('../',$w_cliente,f($RS_Solic,'sg_unidade_resp'),f($RS_Solic,'sq_unidade'),$TP).'</td>');
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
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
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
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_embed;

  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = upper(trim($_REQUEST['w_tipo']));

  headerGeral('V', $w_tipo, $w_chave, 'Visualização de '.f($RS_Menu,'nome'), $w_embed, null, 4, $w_linha_pag,$w_filtro);
  
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  ShowHTML(VisualPedido($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') {
    ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
    ScriptOpen('JavaScript');
    ShowHTML('  var comando, texto;');
    ShowHTML('  if (window.name!="content") {');
    ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
    ShowHTML('  }');
    ScriptClose();
  }
  if ($w_tipo=='PDF') RodapePDF();
  else                Rodape();
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
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML(VisualPedido($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CLPCCAD',$w_pagina.$par,$O);
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
    $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,5,
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
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualPedido($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  //if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EC' || $w_sg_tramite=='EE' || $w_ativo=='N' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CLPCENVIO',$w_pagina.$par,$O);
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
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  $w_troca      = $_REQUEST['w_troca'];
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
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_observacao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualPedido($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_dir.$w_pagina.'Grava&SG=CLPCENVIO&O='.$O.'&w_menu='.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}  
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<INPUT type="hidden" name="w_novo_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top"><b>A<u>n</u>otação:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anotação desejada.">'.$w_observacao.'</TEXTAREA></td>');
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
function Atender() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  //Recupera os dados da solicitacao de passagens e diárias
  $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,5,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $w_dados_pai      = explode('|@|',f($RS,'dados_pai'));
  $w_sq_menu_relac  = $w_dados_pai[3];
  $w_sqcc           = f($RS,'sq_cc');
  $w_solic_pai      = f($RS,'sq_solic_pai');
  $w_chave_pai      = f($RS,'sq_solic_pai');
  $w_fundo_fixo     = f($RS,'fundo_fixo');
  $w_nota_conclusao = f($RS,'nota_conclusao');
  $w_financeiro     = f($RS,'sq_financeiro');
  $w_rubrica        = f($RS,'sq_projeto_rubrica');
  $w_lancamento     = f($RS,'sq_tipo_lancamento');
  $w_valor          = f($RS,'valor');
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
    $sql = new db_getCLFinanceiro; $RS_Financ = $sql->getInstanceOf($dbms,$w_cliente,$w_menu,$w_solic_pai,null,null,null,null,null,null,null,null);
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
  if ($w_fn=='N' || ($w_fn=='S' && f($RS_FN,'fundo_fixo_valor')>=$w_valor)) {
    Validate('w_nota_conclusao','Nota de conclusão','','','1','2000','1','1');
    ShowHTML('  if (theForm.w_fundo_fixo[1].checked && theForm.w_nota_conclusao.value.length>0) {');
    ShowHTML('    alert("Nota de conclusão pode ser preenchida somente se o pagamento for por fundo fixo!");');
    ShowHTML('    theForm.w_nota_conclusao.focus();');
    ShowHTML('    return false;');
    ShowHTML('  }');
  }
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualPedido($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CLPCATEND',$w_pagina.$par,'T');
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
      SelecaoTipoLancamento('<u>T</u>ipo de lançamento:','T','Selecione na lista o tipo de lançamento adequado.',$w_lancamento,null,$w_cliente,'w_lancamento','CLPC'.str_pad($w_solic_pai,10,'0',STR_PAD_LEFT).str_pad($w_rubrica,10,'0',STR_PAD_LEFT).'T',null);
    } elseif (count($RS_Financ)==1) {
      foreach($RS_Financ as $row) { $RS_Financ = $row; break; }
      ShowHTML('<INPUT type="hidden" name="w_financeiro" value="'.f($RS_Financ,'chave').'">');
    }
  }
  
  ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Quantidades Autorizadas</td></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,null,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
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
  $w_texto = '';
  if ($w_fn=='S') {
    if (f($RS_FN,'fundo_fixo_valor')<$w_valor) {
      $w_texto = ' <font color="#BC3131">ATENÇÃO: VALOR ESTIMADO DA COMPRA ('.formatNumber($w_valor).') SUPERA LIMITE DE PAGAMENTO POR FUNDO FIXO ('.formatNumber(f($RS_FN,'fundo_fixo_valor')).')</font>';
      ShowHTML('      <tr valign="top"><td>');
      ShowHTML('<b>Pagamento por fundo fixo?&nbspNão<br>'.$w_texto.'</b>');
      ShowHTML('<INPUT type="hidden" name="w_fundo_fixo" value="N">');
    } else {
      MontaRadioNS('<b>Pagamento por fundo fixo?'.$w_texto.'</b>',$w_fundo_fixo,'w_fundo_fixo');
      ShowHTML('    <tr><td colspan="4"><b>Nota d<u>e</u> conclusão: <font color="#BC3131">(preencher apenas se o pagamento por fundo fixo)</font></b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Se pagamento por fundo fixo, você pode registrar uma nota de conclusão opcional.">'.$w_nota_conclusao.'</TEXTAREA></td>');
    }
  } else {
    MontaRadioNS('<b>Pagamento por fundo fixo?'.$w_texto.'</b>',$w_fundo_fixo,'w_fundo_fixo');
    ShowHTML('    <tr><td colspan="4"><b>Nota d<u>e</u> conclusão: <font color="#BC3131">(preencher apenas se o pagamento por fundo fixo)</font></b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Se pagamento por fundo fixo, você pode registrar uma nota de conclusão opcional.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  }
  ShowHTML('    <tr><td colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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

  //Recupera os dados da solicitacao de passagens e diárias
  $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,5,null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $w_nota_conclusao = nvl(f($RS,'nota_conclusao'),f($RS,'justificativa'));
  $w_fundo_fixo     = f($RS,'fundo_fixo');
  
  // Se não for fundo fixo e o módulo de protocolo e arquivo estiver disponível, 
  // então será gerado protocolo com assunto igual ao objeto da solicitação.
  if ($w_fundo_fixo=='N' && $w_pa == 'S') $w_nota_conclusao = f($RS,'objeto');
  
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
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da PCD, na opção 'Listagem'
  ShowHTML(VisualPedido($w_chave,'L',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=CLPCCONC&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" method="POST">');
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
  ShowHTML('    <tr><td width="30%"><b>'.$_SESSION['LABEL_CAMPO'].':<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
        ShowHTML('  alert(\'ATENÇÃO: não há permissão de escrita no diretório.\\n'.$conFilePhysical.$w_cliente.'\');');
        ScriptClose();
      } else {
        if (!$handle = fopen($w_file,'w')) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: não foi possível abrir o arquivo para escrita.\\n'.$w_file.'\');');
          ScriptClose();
        } else {
          if (!fwrite($handle, RelatorioViagem($p_solic))) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: não foi possível inserir o conteúdo do arquivo.\\n'.$w_file.'\');');
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
        ShowHTML('  alert(\'ATENÇÃO: não foi possível remover o arquivo temporário.\\n'.$w_file.'\');');
        ScriptClose();
      }
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail.\\n'.$w_resultado.'\');');
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
  //exit($SG);
  switch ($SG) {
    case 'CLPCCAD':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCLGeral; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_menu'],$_REQUEST['w_sq_unidade'],
          $_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],null,$_REQUEST['w_plano'],explodeArray($_REQUEST['w_objetivo']),$_REQUEST['w_sqcc'],
          $_REQUEST['w_solic_pai'],$_REQUEST['w_justificativa'],$_REQUEST['w_objeto'],$_REQUEST['w_observacao'],nvl($_REQUEST['w_inicio'],
          $_REQUEST['w_data_recebimento']),$_REQUEST['w_fim'],$_REQUEST['w_moeda'],$_REQUEST['w_valor'],$_REQUEST['w_codigo'],
          $_REQUEST['w_prioridade'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],$_REQUEST['w_decisao_judicial'],
          $_REQUEST['w_numero_original'],$_REQUEST['w_data_recebimento'],'N','N',$_REQUEST['w_especie_documento'],$_REQUEST['w_financeiro'],
          $_REQUEST['w_rubrica'],$_REQUEST['w_lancamento'],null,&$w_chave_nova,$_REQUEST['w_copia']);
        
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
    case 'CLPCITEM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCLSolicItem; 
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_sq_material'])-1; $i=$i+1) {
            if ($_REQUEST['w_sq_material'][$i]>'') {
              $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave_aux'],$_REQUEST['w_chave'],null,$_REQUEST['w_sq_material'][$i],
                  null,Nvl($_REQUEST['w_quantidade'][$i],0),null,null,null,null);
            }
          } 
        } else {
          $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave_aux'],$_REQUEST['w_chave'],null,$_REQUEST['w_material'],
              $_REQUEST['w_detalhamento'],Nvl($_REQUEST['w_quantidade'],0),Nvl($_REQUEST['w_qtd_ant'],0),null,null,null);
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
      break;
    case 'CLPCANEXO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
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
            $w_tamanho = $Field['size'];            
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
              if ($_REQUEST['w_atual']>'') {
                $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
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
            }elseif(nvl($Field['name'],'')!=''){
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
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
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        } 
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu 
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;      
    case 'CLPCENVIO':
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
            $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
            //Rotina para gravação da imagem da versão da solicitacão no log.
            if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
              $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
              $w_sg_tramite = f($RS,'sigla');
              if($w_sg_tramite=='CI') {
                $w_html = VisualPedido($_REQUEST['w_chave'],'L',$w_usuario,null,'1');
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
          $sql = new db_getSolicCL;
          $RS = $sql->getInstanceOf($dbms, null, $w_usuario, $SG, 3, null, null, null, null, null, null, null, null, null, null,
                          $_REQUEST['w_chave'], null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
          foreach ($RS as $row) {$RS = $row;break;}
          if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!\');');
            ScriptClose();
            retornaFormulario('w_observacao');
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
                $w_html = VisualPedido($_REQUEST['w_chave'], 'L', $w_usuario, null, '1');
                CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
              }
            }
            // Envia e-mail comunicando o envio
            SolicMail($_REQUEST['w_chave'], 2);
            // Se for envio da fase de cadastramento, remonta o menu principal
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
            ScriptClose();
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
                $w_erro = ValidaPedido($w_cliente,$w_chave,$_POST['p_agrega'],null,null,null,$w_tramite);
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
    case 'CLPCATEND':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
                $_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou o pedido para fase de execução!\');');
          ScriptClose();
          exit();
        } else {
          // Grava as quantidades autorizadas
          $SQL = new dml_putCLSolicItem; 
          for ($i=0; $i<=count($_POST['w_sq_solicitacao_item'])-1; $i=$i+1) {
            if ($_REQUEST['w_sq_solicitacao_item'][$i]>'') {
              $SQL->getInstanceOf($dbms,'C',$_REQUEST['w_sq_solicitacao_item'][$i],$_REQUEST['w_chave'],null,Nvl($_REQUEST['w_material'][$i],0),
                  null,Nvl($_REQUEST['w_quantidade'][$i],0),Nvl($_REQUEST['w_qtd_ant'][$i],0),null,null,null);
            }
          }

        // Grava vinculação orçamentária-financeira
          $SQL = new dml_putCLGeral; $SQL->getInstanceOf($dbms,'T',$_REQUEST['w_chave'],$_REQUEST['w_menu'],null,
            null,null,null,$_REQUEST['w_plano'],explodeArray($_REQUEST['w_objetivo']),$_REQUEST['w_sqcc'],
            $_REQUEST['w_solic_pai'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
            $_REQUEST['w_financeiro'],$_REQUEST['w_rubrica'],$_REQUEST['w_lancamento'],null,&$w_chave_nova,null);
            
          // Grava tipo de pagamento e nota de conclusão
          $SQL = new dml_putCLDados; $SQL->getInstanceOf($dbms,'AUTORIZ',$_REQUEST['w_chave'],null,null,null,null,null,null,null,
            null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
            $_REQUEST['w_nota_conclusao'],$_REQUEST['w_fundo_fixo'],null,null);
          /*
          if ($_REQUEST['w_fundo_fixo']=='S') {
            // Conclui a solicitação
            $SQL = new dml_putSolicConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
                $_SESSION['SQ_PESSOA'],$_REQUEST['w_nota_conclusao'],null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_fundo_fixo']);
          }
          */
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
    case 'CLPCCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
                $_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        foreach($RS as $row){$RS=$row; break;}
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou o pedido para fase de execução!\');');
          ScriptClose();
          exit();
        } else {
          if ($_REQUEST['w_fundo_fixo']=='N') {
            // Grava o protocolo somente se não for fundo fixo
            $SQL = new dml_putCLDados; $SQL->getInstanceOf($dbms,'PROT',$_REQUEST['w_chave'],null,$_REQUEST['w_numero_processo'],null,null,null,null,null,
              null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_protocolo'],null,null,null,null,null,null);
          }
            
          // Conclui a solicitação
          $SQL = new dml_putSolicConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
              $_SESSION['SQ_PESSOA'],$_REQUEST['w_nota_conclusao'],null,null,null,null,null,null,null,null,null,null,$_REQUEST['w_fundo_fixo']);
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
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
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
  case 'ANOTACAO':          Anotar(); break;
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
