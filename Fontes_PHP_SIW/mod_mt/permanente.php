<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getUnidadeMedida.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PE.php');
include_once($w_dir_volta.'classes/sp/db_getMTBem.php');
include_once($w_dir_volta.'classes/sp/db_getMtSituacao.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicMT.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoAlmoxarifado.php');
include_once($w_dir_volta.'funcoes/selecaoEndereco.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoMtSituacao.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'classes/sp/dml_putMTPermanente.php');
include_once($w_dir_volta.'classes/sp/dml_putPessoa.php');
include_once($w_dir_volta.'classes/sp/dml_putCLPesqFornecedor.php');

// =========================================================================
//  /permanente.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Cadastro de bens permanentes
// Mail     : alex@sbpi.com.br
// Criacao  : 06/11/2016, 19:14
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
//                   = M   : Configuração de serviços

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
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina     = 'permanente.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_mt/';
$w_troca      = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$p_chave         = $_REQUEST['p_chave'];
$p_tipo_material = $_REQUEST['p_tipo_material'];
$p_financeiro    = $_REQUEST['p_financeiro'];
$p_descricao     = $_REQUEST['p_descricao'];
$p_marca         = $_REQUEST['p_marca'];
$p_modelo        = $_REQUEST['p_modelo'];
$p_observacao    = $_REQUEST['p_observacao'];
$p_ativo         = $_REQUEST['p_ativo'];
$p_rgp           = $_REQUEST['p_rgp'];
$p_sqcc          = $_REQUEST['p_sqcc'];
$p_projeto       = $_REQUEST['p_projeto'];
$p_material      = $_REQUEST['p_material'];
$p_almoxarifado  = $_REQUEST['p_almoxarifado'];
$p_unidade       = $_REQUEST['p_unidade'];
$p_localizacao   = $_REQUEST['p_localizacao'];
$p_situacao      = $_REQUEST['p_situacao'];
$p_inicio        = $_REQUEST['p_inicio'];
$p_fim           = $_REQUEST['p_fim'];
$p_endereco     = $_REQUEST['p_endereco'];

$p_ordena        = $_REQUEST['p_ordena'];
$p_volta         = upper($_REQUEST['p_volta']);

if ($SG=='MTBEM') {
  if ($O=='') $O='P';
} elseif ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';        break;
  case 'A': $w_TP=$TP.' - Alteração';       break;
  case 'E': $w_TP=$TP.' - Exclusão';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - Cópia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'H': $w_TP=$TP.' - Herança';         break;
  case 'M': $w_TP=$TP.' - Serviços';        break;
  default:  $w_TP=$TP.' - Listagem';        break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

if(nvl($w_menu,'')!=''){
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS_Menu,'libera_edicao');
  
  if ($w_libera_edicao=='N' && strpos('LP',$O)===false) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b>Operação não permitida!</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exit();
  }
}

$w_ano      = RetornaAno();

// Recupera as informações do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente);

// Recupera as informações da opçao de menu;
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel') == 'S') {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

// Verifica se o cliente tem o módulo de materiais
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'AL');
if (count($RS)>0) $w_al='S'; else $w_al='N'; 

// Verifica se o cliente tem o módulo de projetos
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PR');
if (count($RS)>0) $w_pr='S'; else $w_pr='N'; 

// Verifica se o cliente tem o módulo financeiro
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'FN');
if (count($RS)>0) $w_fn='S'; else $w_fn='N'; 

// Recupera os parâmetros do módulo de compras e licitações
$sql = new db_getParametro; $RS_Parametro = $sql->getInstanceOf($dbms,$w_cliente,'CL',null);
foreach($RS_Parametro as $row){$RS_Parametro=$row;}

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de dados gerais
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_copia              = $_REQUEST['w_copia'];
  $w_tipo               = $_REQUEST['w_tipo'];
  $w_tipo_material      = $_REQUEST['w_tipo_material'];
  
  // Configuração do nível de acesso
  $w_restricao = 'EDICAOT';
  if ($p_acesso=='I') $w_restricao = 'EDICAOP';

  if ($w_troca>'' && $O <> 'E') {
    $w_sqcc            = $_REQUEST['w_sqcc'];
    $w_projeto         = $_REQUEST['w_projeto'];
    $w_almoxarifado    = $_REQUEST['w_almoxarifado'];
    $w_unidade         = $_REQUEST['w_unidade'];
    $w_localizacao     = $_REQUEST['w_localizacao'];
    $w_rgp             = $_REQUEST['w_rgp'];
    $w_entrada         = $_REQUEST['w_entrada'];
    $w_forn_garantia   = $_REQUEST['w_forn_garantia'];
    $w_fim_garantia    = $_REQUEST['w_fim_garantia'];
    $w_tombamento      = formataDataEdicao($_REQUEST['w_tombamento']);
    $w_vida_util       = $_REQUEST['w_vida_util'];
    $w_descricao       = $_REQUEST['w_descricao'];
    $w_observacao      = $_REQUEST['w_observacao'];
    $w_tipo_material   = $_REQUEST['w_tipo_material'];
    $w_material        = $_REQUEST['w_material'];
    $w_situacao        = $_REQUEST['w_situacao'];
    $w_codigo_externo  = $_REQUEST['w_codigo_externo'];
    $w_marca           = $_REQUEST['w_marca'];
    $w_modelo          = $_REQUEST['w_modelo'];
    $w_numero_serie    = $_REQUEST['w_numero_serie'];
    $w_valor_brl       = formatNumber($_REQUEST['w_valor_brl']);
    $w_data_brl        = FormataDataEdicao($_REQUEST['w_data_brl']);
    $w_valor_usd       = formatNumber($_REQUEST['w_valor_usd']);
    $w_data_usd        = FormataDataEdicao($_REQUEST['w_data_usd']);
    $w_valor_eur       = formatNumber($_REQUEST['w_valor_eur']);
    $w_data_eur        = FormataDataEdicao($_REQUEST['w_data_eur']);
    $w_ativo           = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      $w_filtro='';

      if ($p_rgp>'')     $w_filtro.='<tr><td align="right">RGP <td>[<b>'.$p_rgp.'</b>]';
      if ($p_material>'')     $w_filtro.='<tr><td align="right">Bem <td>[<b>'.$p_material.'</b>] em qualquer parte';
      if ($p_financeiro>'')     $w_filtro.='<tr><td align="right">Financeiro <td>[<b>'.$p_financeiro.'</b>]';
      if ($p_almoxarifado>'') {
        $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_almoxarifado,null,null,null,null,'OUTROS');
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro.='<tr><td align="right">Almoxarifado <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_tipo_material>'') {
        $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_tipo_material,null,null,null,null,null,null,'REGISTROS');
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro.='<tr><td align="right">Tipo <td>[<b>'.f($RS,'nome_completo').'</b>]';
      } 
      if ($p_projeto>'') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
        $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto." target="_blank">'.f($RS,'titulo').'</a></b>]';
      } 
      if ($p_marca>'')     $w_filtro.='<tr><td align="right">Marca <td>[<b>'.$p_marca.'</b>] em qualquer parte';
      if ($p_modelo>'')     $w_filtro.='<tr><td align="right">Modelo <td>[<b>'.$p_modelo.'</b>] em qualquer parte';
      if ($p_situacao>'') {
        $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$p_situacao,null,null,null);
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro.='<tr><td align="right">Situação física <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_descricao>'')    $w_filtro.='<tr><td align="right">Descrição <td>[<b>'.$p_descricao.'</b>] em qualquer parte';
      if ($p_observacao>'')     $w_filtro.='<tr><td align="right">Observação <td>[<b>'.$p_observacao.'</b>] em qualquer parte';
      if ($p_ativo=='S') {
        $w_filtro.='<tr><td align="right">Situação <td>[<b>Apenas itens ativos</b>]';
      } elseif ($p_ativo=='N') {
        $w_filtro.='<tr><td align="right">Situação <td>[<b>Apenas itens inativos</b>]';
      } else {
        $w_filtro.='<tr><td align="right">Situação <td>[<b>Itens ativos e inativos</b>]';
      }
      if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 
    $sql = new db_getMTBem;
    $RS = $sql->getInstanceOf($dbms,$w_cliente, $w_usuario, $p_chave, $p_sqcc, 
            $p_projeto, $p_financeiro, $p_tipo_material, $p_material, $p_rgp, $p_descricao,
            $p_marca, $p_modelo, $p_observacao, $p_ativo, $p_almoxarifado, $p_endereco, 
            $p_unidade,  $p_localizacao, $p_situacao, $p_inicio, $p_fim, $p_restricao);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'numero_rgp','asc');
    } else {
      $RS = SortArray($RS,'numero_rgp','asc'); 
    }
  } elseif (strpos('MCAEV',$O)!==false) {
    $sql = new db_getMTBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_sqcc            = f($RS,'sq_cc');
    $w_projeto         = f($RS,'sq_projeto');
    $w_almoxarifado    = f($RS,'sq_almoxarifado');
    $w_unidade         = f($RS,'sq_unidade');
    $w_localizacao     = f($RS,'sq_localizacao');
    if ($O!='C') {
      $w_rgp           = f($RS,'numero_rgp');
     $w_codigo_externo = f($RS,'codigo_externo');
    }
    $w_entrada         = f($RS,'sq_entrada_item');
    $w_forn_garantia   = f($RS,'fornecedor_garantia');
    $w_fim_garantia    = formataDataEdicao(f($RS,'data_fim_garantia'));
    $w_tombamento      = formataDataEdicao(f($RS,'data_tombamento'));
    $w_vida_util       = f($RS,'vida_util');
    $w_descricao       = f($RS,'descricao_complementar');
    $w_observacao      = f($RS,'observacao');
    $w_tipo_material   = f($RS,'sq_tipo_material');
    $w_material        = f($RS,'sq_material');
    $w_marca           = f($RS,'marca');
    $w_situacao        = f($RS,'sq_mtsituacao');
    $w_modelo          = f($RS,'modelo');
    $w_numero_serie    = f($RS,'numero_serie');
    $w_valor_brl       = formatNumber(f($RS,'vl_atual_brl'));
    $w_data_brl        = FormataDataEdicao(f($RS,'dt_vl_atual_brl'));
    $w_valor_usd       = formatNumber(f($RS,'vl_atual_usd'));
    $w_data_usd        = FormataDataEdicao(f($RS,'dt_vl_atual_usd'));
    $w_valor_eur       = formatNumber(f($RS,'vl_atual_eur'));
    $w_data_eur        = FormataDataEdicao(f($RS,'dt_vl_atual_eur'));
    $w_ativo           = f($RS,'ativo');
  } 

  // Recupera informações sobre o tipo do material ou serviço
  if (nvl($w_tipo_material,'')!='') {
    $sql = new db_getTipoMatServ; $RS_Tipo = $sql->getInstanceOf($dbms,$w_cliente,$w_tipo_material,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS_Tipo as $row) { $RS_Tipo = $row; break; }
    $w_classe = f($RS_Tipo,'classe');
  } 

  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']); 
  } else {
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    ShowHTML('<TITLE>'.$conSgSistema.' - Materiais e Serviços</TITLE>');
    Estrutura_CSS($w_cliente);
    if (strpos('PCIAE',$O)!==false) {
      ScriptOpen('JavaScript');
      CheckBranco();
      FormataData();
      FormataValor();
      ValidateOpen('Validacao');
      if (strpos('CIA',$O)!==false) {
        Validate('w_rgp','RGP Atual','1','1','1','18','','0123456789');
        Validate('w_tombamento','Data de tombamento','DATA',1,10,10,'','0123456789/');
        Validate('w_vida_util','Vida útil','1','1','1','2','','0123456789');
        Validate('w_almoxarifado','Almoxarifado','SELECT','1','1','18','','1');
        Validate('w_situacao','Situação física','SELECT','1','1','18','','1');
        Validate('w_codigo_externo','Código externo','','','1','30','1','1');
        Validate('w_unidade','Unidade','SELECT','1','1','18','','1');
        Validate('w_localizacao','Localização','SELECT','1','1','18','','1');
        Validate('w_projeto','Projeto','SELECT','','1','18','','1');
        Validate('w_descricao','Descrição complementar','1','1','2','2000','1','1');
        Validate('w_tipo_material','Tipo do material','SELECT','1','1','18','','1');
        Validate('w_material','Material','SELECT','1','1','18','','1');
        Validate('w_marca','Marca','','',1,50,'1','1');
        Validate('w_modelo','Modelo','','',1,50,'1','1');
        Validate('w_numero_serie','Número de série','','',1,50,'1','1');
        Validate('w_valor_brl','Valor Reais','VALOR','',4,18,'','0123456789.,-');
        Validate('w_data_brl','Data alteração valor em BRL','DATA','',10,10,'','0123456789/');
        Validate('w_valor_usd','Valor Dólar','VALOR','',4,18,'','0123456789.,-');
        Validate('w_data_usd','Data alteração valor em BRL','DATA','',10,10,'','0123456789/');
        Validate('w_valor_eur','Valor Euro','VALOR','',4,18,'','0123456789.,-');
        Validate('w_data_eur','Data alteração valor em BRL','DATA','',10,10,'','0123456789/');
        ShowHTML('  if ((theForm.w_valor_brl.value=="" || theForm.w_valor_brl.value=="0,00") && ');
        ShowHTML('      (theForm.w_valor_usd.value=="" || theForm.w_valor_usd.value=="0,00") && ');
        ShowHTML('      (theForm.w_valor_eur.value=="" || theForm.w_valor_eur.value=="0,00") ');
        ShowHTML('     ) {');
        ShowHTML('    alert("Informe pelo menos um dos valores e sua data!");');
        ShowHTML('    return false;');
        ShowHTML('  } else {');
        ShowHTML('    if (theForm.w_valor_brl.value!="0,00" && theForm.w_data_brl.value=="") {');
        ShowHTML('      alert("Informe a data da alteração do valor em reais!");');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('    if (theForm.w_valor_brl.value!="0,00" && theForm.w_data_brl.value=="") {');
        ShowHTML('      alert("Informe a data da alteração do valor em reais!");');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('    if (theForm.w_valor_usd.value!="0,00" && theForm.w_data_usd.value=="") {');
        ShowHTML('      alert("Informe a data da alteração do valor em dólar!");');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_valor_eur.value!="0,00" && theForm.w_data_eur.value=="") {');
        ShowHTML('    alert("Informe a data da alteração do valor em euro!");');
        ShowHTML('    return false;');
        ShowHTML('  }');
        Validate('w_observacao','Observação','','',1,2000,'1','1');
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      } elseif ($O=='P') {
        Validate('p_rgp','RGP Atual','1','','1','18','','0123456789');
        Validate('p_material','Bem','1','','2','90','1','1');
        Validate('p_financeiro','Código interno','1','','2','30','1','1');
        Validate('p_projeto','Projeto','SELECT','','1','18','','1');
        Validate('p_descricao','Descrição complementar','','','2','2000','1','1');
        Validate('p_marca','Marca','','',1,50,'1','1');
        Validate('p_modelo','Modelo','','',1,50,'1','1');
        Validate('p_observacao','Observação','','',1,2000,'1','1');
        Validate('p_codigo_externo','Código externo','','',1,30,'1','1');
      } elseif ($O=='E') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
        ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\'));');
        ShowHTML('     { return (true); }; ');
        ShowHTML('     { return (false); }; ');
      }
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($w_tipo=='WORD'){
    BodyOpenWord(null);
  } elseif ($O=='P'){
    BodyOpen('onLoad="document.Form.p_rgp.focus();"');
  } elseif (strpos('CIA',$O)!==false) {
    BodyOpen('onLoad="document.Form.w_rgp.focus();"');
  } elseif ($O=='L'){
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
  if ($O=='L') {
    if ($w_tipo!='WORD') {
      ShowHTML('<tr>');
      ShowHTML('  <td>');
      if ($w_libera_edicao=='S') {
        if($P1!=1) ShowHTML('        <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
      }  
      if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('        <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      else                       ShowHTML('        <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right">');

    ShowHTML('    '.exportaOffice().'<b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('RGP','numero_rgp').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Bem','nome').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Projeto','cd_projeto').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Localização','nm_localizacao').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Almoxarifado','nm_almoxarifado').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Detalhamento','descricao_complementar').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Tipo','nm_tipo_material').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Tombamento','data_tombamento').'</td>');
      ShowHTML('          <td colspan=3><b>Valor</b></td>');
      if ($w_libera_edicao=='S') {
        ShowHTML('          <td class="remover" rowspan=2><b> Operações </td>');
      }
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('BRL','vl_atual_brl').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('USD','vl_atual_usd').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('EUR','vl_atual_eur').'</b></td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td rowspan=2><b>RGP</td>');
      ShowHTML('          <td rowspan=2><b>Bem</td>');
      ShowHTML('          <td rowspan=2><b>Detalhamento</td>');
      ShowHTML('          <td rowspan=2><b>Tipo</td>');
      ShowHTML('          <td rowspan=2><b>Tombamento</td>');
      ShowHTML('          <td colspan=3><b>Pesquisa mais recente</b></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>BRL</b></td>');
      ShowHTML('          <td><b>USD</b></td>');
      ShowHTML('          <td><b>EUR</b></td>');
      ShowHTML('        </tr>');
    }  
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"'.((nvl(f($row,'observacao'),'')!='') ? ' title="'.  CRLF2BR(f($row,'observacao')).'"' : '').'>'.f($row,'numero_rgp').'</td>');
        if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePermanente($w_dir_volta,$w_cliente,f($row,'nome_completo'),f($row,'chave'),$TP,null,((nvl(f($row,'descricao_complementar'),'')!='') ? f($row,'descricao_complementar') : null)).'</td>');
        else                 ShowHTML('        <td>'.f($row,'nome_completo').'</td>');
        ShowHTML('        <td title="'.f($row,'nm_projeto').'">'.nvl(f($row,'cd_projeto'),'---').'</td>');
        ShowHTML('        <td title="'.f($row,'nm_unidade').'">'.nvl(f($row,'nm_localizacao'),'---').'</td>');
        ShowHTML('        <td nowrap>'.f($row,'nm_almoxarifado').'</td>');
        //ShowHTML('        <td>'.f($row,'descricao_complementar').'</td>');
        //ShowHTML('        <td>'.f($row,'nm_tipo_material').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'data_tombamento'),5).'</td>');
        ShowHTML('            <td align="right">'.nvl(formatNumber(f($row,'vl_atual_brl'),2),'---').'</td>');
        ShowHTML('            <td align="right">'.nvl(formatNumber(f($row,'vl_atual_usd'),2),'---').'</td>');
        ShowHTML('            <td align="right">'.nvl(formatNumber(f($row,'vl_atual_eur'),2),'---').'</td>');
        if ($w_tipo!='WORD') {
          if ($w_libera_edicao == 'S') {
            ShowHTML('        <td class="remover" align="top" nowrap>');
            ShowHTML('          <A class="hl" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=A&w_chave=' . f($row, 'chave') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' &SG=' . $SG . MontaFiltro('GET') . '" Title="Altera os dados deste registro.">AL</A>&nbsp');
            ShowHTML('          <A class="hl" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=E&w_chave=' . f($row, 'chave') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' &SG=' . $SG . MontaFiltro('GET') . '" Title="Exclui deste registro.">EX</A>&nbsp');
            ShowHTML('          <A class="hl" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=C&w_chave=' . f($row, 'chave') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' &SG=' . $SG . MontaFiltro('GET') . '" Title="Inclui um novo item a partir dos dados deste registro.">CO</A>&nbsp');
          }
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        } 
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($w_tipo!='WORD') {
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
    }
    ShowHTML('</tr>');
  } elseif (strpos('CIAEV',$O)!==false) {
    //Aqui começa a manipulação de registros
    if ($O=='C') ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: Dados importados de outro registro. Altere os dados necessários antes de executar a inclusão..</b></font>.</td>');
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED '; 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(montaFiltro('POST'));
    if ($O!='C') ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sqcc" value="'.$w_sqcc.'">');
    ShowHTML('<INPUT type="hidden" name="w_entrada" value="'.$w_entrada.'">');
    ShowHTML('<INPUT type="hidden" name="w_forn_garantia" value="'.$w_forn_garantia.'">');
    ShowHTML('<INPUT type="hidden" name="w_fim_garantia" value="'.$w_fim_garantia.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>R</u>GP:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_rgp" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_rgp.'"></td>');
    ShowHTML('        <td><b><u>T</u>ombamento:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_tombamento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_tombamento.'"></td>');
    ShowHTML('        <td><b><u>V</u>ida útil (em anos):</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_vida_util" class="sti" SIZE="2" MAXLENGTH="2" VALUE="'.$w_vida_util.'"></td>');
    ShowHTML('      <tr>');
    selecaoUnidade('<U>U</U>nidade:','U',null,$w_unidade,null,'w_unidade',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_localizacao\'; document.Form.submit();"',3);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    selecaoLocalizacao('Lo<U>c</U>alização:','C',null,$w_localizacao,$w_unidade,'w_localizacao',null,null,3);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    selecaoalmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado ao qual o bem pertence.', $w_almoxarifado,'w_almoxarifado',null,null);
    selecaoMtSituacao('<u>S</u>ituação física:','S', 'Indique a situação física do bem.', $w_situacao,'w_situacao','BEM',null);
    ShowHTML('        <td title="Se necessário, informe o código pelo qual o bem é identificado em outro sistema."><b><u>C</u>ódigo externo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo_externo" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$w_codigo_externo.'"></td>');
    ShowHTML('          <tr>');
    $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoSolic('Projeto:',null,null,$w_cliente,$w_projeto,$w_sq_menu_relac,f($RS1,'sq_menu'),'w_projeto',f($RS_Menu,'sigla'),null,$w_projeto,'<BR />',3);
    ShowHTML('      <tr>');
    selecaoTipoMatServ('T<U>i</U>po:','I',null,$w_tipo_material,null,'w_tipo_material','FOLHAPER','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_material\'; document.Form.submit();"',3);
    ShowHTML('      <tr>');
    SelecaoMatServ('<U>M</U>aterial:','M','Selecione o material.',$w_material,$w_tipo_material,null,'w_material','PATRIMONIO',null, 3);
    ShowHTML('      <tr><td colspan=3><b><U>D</U>escrição complementar:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=3 cols=80." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td title="Informe a marca ou fabricante do bem."><b><u>M</u>arca:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_marca" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$w_marca.'"></td>');
    ShowHTML('        <td><b><u>M</u>odelo:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_modelo" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$w_modelo.'"></td>');
    ShowHTML('        <td><b><u>N</u>úmero de série:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_numero_serie" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$w_numero_serie.'"></td>');
    ShowHTML('      <tr><td colspan="3"><TABLE bgcolor="'.$conTableBgColor.'" BORDER="1" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr valign="top" align="center">');
    ShowHTML('          <td colspan="6" bgColor="#f0f0f0"><b>Valores (Pelo menos um deve estar preenchido)</b></td>');
    ShowHTML('        <tr valign="top" align="center">');
    ShowHTML('          <td colspan="2" bgColor="#f0f0f0"><b>Real</b></td>');
    ShowHTML('          <td colspan="2" bgColor="#f0f0f0"><b>Dólar</b></td>');
    ShowHTML('          <td colspan="2" bgColor="#f0f0f0"><b>Euro</b></td>');
    ShowHTML('        <tr valign="top" align="center">');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Valor Atual</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Última alteração</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Valor Atual</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Última alteração</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Valor Atual</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>Última alteração</b></td>');
    ShowHTML('        <tr valign="top" align="center">');
    ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_valor_brl" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.nvl($w_valor_brl,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_data_brl" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_brl.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_valor_usd" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.nvl($w_valor_usd,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_data_usd" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_usd.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_valor_eur" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.nvl($w_valor_eur,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_data_eur" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_eur.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('      </table>');
    ShowHTML('      <tr title="Informações que julgar relevantes para o bem."><td colspan=3><b><U>O</U>bservação:<br><TEXTAREA ACCESSKEY="O" class="sti" name="w_observacao" rows=5 cols=80." '.$w_Disabled.'>'.$w_observacao.'</textarea></td>');
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I' || $O=='C') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,'',$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td colspan=2><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>R</u>GP Atual:</b><br><input '.$p_Disabled.' accesskey="R" type="text" name="p_rgp" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$p_rgp.'"></td>');
    ShowHTML('          <td><b><u>B</u>em:</b><br><input '.$p_Disabled.' accesskey="B" type="text" name="p_material" class="sti" SIZE="40" MAXLENGTH="40" VALUE="'.$p_material.'"></td>');
    ShowHTML('          <td><b><u>C</u>ódigo do lançamento financeiro:</b><br><input '.$p_Disabled.' accesskey="C" type="text" name="p_financeiro" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$p_financeiro.'"></td>');

    ShowHTML('      <tr>');
    SelecaoEndereco('En<u>d</u>ereço principal:','d',null,$p_endereco,$w_cliente,'p_endereco','FISICO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_endereco\'; document.Form.submit();"', 3);
    ShowHTML('      </tr>');

    ShowHTML('      <tr valign="top">');
    selecaoalmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado ao qual o bem pertence.', $p_almoxarifado,'p_almoxarifado',null,null);

    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$p_unidade,$p_endereco,'p_unidade','MOD_MT','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_unidade\'; document.Form.submit();"', 3);

    ShowHTML('      <tr>');
    selecaoLocalizacao('Lo<U>c</U>alização:','C',null,$p_localizacao,$p_unidade,'p_localizacao',null,null,3);
    ShowHTML('      </tr>');

    ShowHTML('      <tr valign="top">');
    selecaoTipoMatServ('T<U>i</U>po de material:','I',null,$p_tipo_material,null,'p_tipo_material','FOLHAPER',null,3);

    ShowHTML('          <tr>');
    $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoSolic('Projeto:',null,null,$w_cliente,$p_projeto,$w_sq_menu_relac,f($RS1,'sq_menu'),'p_projeto',f($RS_Menu,'sigla'),null,$p_projeto,'<BR />',3);
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>M</u>arca:</b><br><input accesskey="M" type="text" name="p_marca" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_marca.'"></td>');
    ShowHTML('        <td><b><u>M</u>odelo:</b><br><input accesskey="M" type="text" name="p_modelo" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_modelo.'"></td>');
    selecaoMtSituacao('<u>S</u>ituação física:','S', null, $p_situacao,'p_situacao','BEM',null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><U>D</U>escrição complementar:<br><input accesskey="M" type="text" name="p_descricao" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_descricao.'"></td>');
    ShowHTML('        <td><b><U>O</U>bservação:<br><input accesskey="M" type="text" name="p_observacao" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_observacao.'"></td>');
    ShowHTML('        <td><b><u>C</u>ódigo externo:</b><br><input accesskey="C" type="text" name="p_codigo_externo" class="sti" SIZE="25" MAXLENGTH="30" VALUE="'.$p_codigo_externo.'"></td>');

    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Recuperar:</b><br>');
    ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"'.(($p_ativo=='S') ? ' checked' : '').'> Apenas ativos<br>');
    ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"'.(($p_ativo=='N') ? ' checked' : '').'> Apenas inativos<br>');
    ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""'.((nvl($p_ativo,'X')=='X') ? ' checked' : '').'> Tanto faz');
    ShowHTML('      <tr valign="top">');
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
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 

// =========================================================================
// Rotina de pesquisa de preço dos itens do catálogo
// -------------------------------------------------------------------------
function PesquisaPreco() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_troca          = $_REQUEST['w_troca'];
  $w_sq_pessoa      = $_REQUEST['w_sq_pessoa'];
  $w_tipo_pessoa    = $_REQUEST['w_tipo_pessoa'];
  
  $p_descricao      = upper($_REQUEST['p_descricao']);
  $p_forn           = upper($_REQUEST['p_forn']);
  $p_cpf            = $_REQUEST['p_cpf'];
  $p_cnpj           = $_REQUEST['p_cnpj'];
  $p_restricao      = $_REQUEST['p_restricao'];
  $p_campo          = $_REQUEST['p_campo'];

  // Recupera os dados do item
  $sql = new db_getMTBem; $RS_Item = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS_Item as $row){$RS_Item=$row; break;}

  if ($w_troca>'' && $O!='E') {
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
    $w_inicio               = $_REQUEST['w_inicio'];
    $w_dias                 = $_REQUEST['w_dias'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_fabricante           = $_REQUEST['w_fabricante'];
    $w_marca_modelo         = $_REQUEST['w_marca_modelo'];
    $w_embalagem            = $_REQUEST['w_embalagem'];
    $w_fator                = $_REQUEST['w_fator'];
    $w_origem               = $_REQUEST['w_origem'];
  } elseif ($O=='A' || nvl($w_sq_pessoa,'')!='' || $O=='I' || nvl($w_troca,'')!='') {
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
  } elseif ($O=='L') {  
    $sql = new db_getMTBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'PESQMAT');
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_fornecedor','asc');
    } else {
      $RS = SortArray($RS,'phpdt_fim','desc','nm_fornecedor','asc');
    }    
  } elseif ($O=='P') {
    $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_pessoa,null,$p_cpf,$p_cnpj,$p_forn,null,null,null,null,null,null,null,null,null,null,null,null);
  } 

  Cabecalho();
  head();
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
    ValidateOpen('Validacao');
    if ($O=='P') {
      Validate('p_forn','Nome','1','','3','100','1','1');
      Validate('p_cpf','CPF','CPF','','14','14','','0123456789.-');
      Validate('p_cnpj','CNPJ','CNPJ','','18','18','','0123456789.-/');
      ShowHTML('  if (theForm.p_forn.value=="" && theForm.p_cpf.value=="" && theForm.p_cnpj.value=="") {');
      ShowHTML('     alert ("Informe um critério para busca!");');
      ShowHTML('     theForm.p_forn.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='I' || $O=='A') {
      Validate('w_nome','Nome','1',1,5,60,'1','1');
      if ($w_tipo_pessoa==1) {
        Validate('w_cpf','CPF','CPF','','14','14','','0123456789-.');
      } else {
        Validate('w_cnpj','CNPJ','CNPJ','','18','18','','0123456789/-.');
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
        ShowHTML('     alert("Os campos identidade, data de emissão e órgão emissor devem ser informados em conjunto!\\nDos três, apenas a data de emissão é opcional.");');        ShowHTML('     theForm.w_rg_numero.focus();');
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
      Validate('w_origem','Fonte da Pesquisa','',1,1,10,'1','1');
      Validate('w_inicio','Pesq. preço','DATA',1,10,10,'','0123456789/');
      Validate('w_dias','Dias de Validade','',1,1,10,'','0123456789');
      Validate('w_valor','Valor da pesquisa de preço','VALOR','1',6,18,'','0123456789.,');
      if (f($RS_Item,'classe')!=5) {
        Validate('w_fabricante','Fabricante','1','',2,50,'1','1');
        Validate('w_marca_modelo','Marca/Modelo','1','',2,50,'1','1');
        Validate('w_embalagem','Embalagem','1','',2,20,'1','1');
      }
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
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
      ShowHTML('  if ((theForm.w_nr_telefone.value+theForm.w_nr_fax.value+theForm.w_nr_celular.value)!="" && theForm.w_ddd.value=="") {');
      ShowHTML('     alert("O campo DDD é obrigatório quando informar telefone, fax ou celular!");');
      ShowHTML('     theForm.w_ddd.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_ddd.value!="" && theForm.w_nr_telefone.value=="") {');
      ShowHTML('     alert("Se informar o DDD, então informe obrigatoriamente o telefone!\\nFax e celular são opcionais.");');
      ShowHTML('     theForm.w_nr_telefone.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_ddd.value!="" && (theForm.w_sq_pais.value=="" || theForm.w_co_uf.value=="" || theForm.w_sq_cidade.value=="")) {');
      ShowHTML('     alert("Se informar telefone, fax ou celular, então informe o país, estado e cidade!");');
      ShowHTML('     theForm.w_sq_pais.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if ((theForm.w_complemento.value+theForm.w_bairro.value+theForm.w_cep.value)!="" && theForm.w_logradouro.value=="") {');
      ShowHTML('     alert("O campo logradouro é obrigatório quando informar os campos complemento, bairro ou CEP!");');
      ShowHTML('     theForm.w_logradouro.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_logradouro.value!="" && theForm.w_cep.value=="") {');
      ShowHTML('     alert("O campo CEP é obrigatório quando informar o endereço da pessoa!");');
      ShowHTML('     theForm.w_cep.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_email','E-Mail','1','',4,60,'1','1');
      ShowHTML('  if ((theForm.w_ddd.value+theForm.w_logradouro.value+theForm.w_email.value)!="" && (theForm.w_sq_pais.value=="" || theForm.w_co_uf.value=="" || theForm.w_sq_cidade.value=="")) {');
      ShowHTML('     alert("Se informar algum telefone, o endereço ou o e-mail da pessoa, então informe o país, estado e cidade!");');
      ShowHTML('     theForm.w_sq_pais.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');      
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;'); 
    }
    ValidateClose();   
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (nvl($w_troca,'')!='') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif($O=='P') {
    BodyOpenClean('onLoad=\'document.Form.p_forn.focus()\';');
  } elseif($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif($O=='E') {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
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
  ShowHTML('            <td><b>'.f($RS_Item,'codigo_interno').'</td>');
  ShowHTML('            <td><b>'.ExibeMaterial($w_dir_volta,$w_cliente,f($RS_Item,'nome'),f($RS_Item,'chave'),$TP,null).'</td>');
  ShowHTML('          <tr valign="top">');
  If (nvl(f($RS_Item,'pesquisa_preco_medio'),'')=='') {
    ShowHTML('            <td>$ médio pesquisado:<b><br>Sem pesquisa válida</td>');
  } else {
    ShowHTML('            <td>$ médio pesquisado:<b><br>'.formatNumber(f($RS_Item,'pesquisa_preco_medio'),4).'</td>');
  }
  if (f($RS_Item,'numero_ata')!='') {
    ShowHTML('            <td>$ ARP:<b><br>'.formatNumber(f($RS_Item,'preco_ata'),4).'&nbsp;&nbsp;(ARP: '.f($RS_Item,'numero_ata').' valida até '.FormataDataEdicao(f($RS_Item,'validade_ata')).')</td>');
  }
  ShowHTML('      </table>');
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.'Inicial&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Voltar</a>');
    ShowHTML('    <td align="right">');
    ShowHTML('    '.exportaOffice().'<b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fornecedor','nm_fornecedor').'</td>');
    ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fonte','nm_origem').'</td>');
    ShowHTML('          <td bgColor="#f0f0f0" colspan=3><b>Pesquisa</b></td>');
    ShowHTML('          <td class="remover" rowspan=2><b> Operações </td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td bgColor="#f0f0f0" colspan=2><b>'.LinkOrdena('Validade','phpdt_fim').'</b></td>');
    ShowHTML('          <td bgColor="#f0f0f0"><b>'.LinkOrdena('Valor','valor_item').'</b></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_fornecedor').'</td>');
        ShowHTML('        <td>'.f($row,'nm_origem').'</td>');
        if (nvl(f($row,'phpdt_fim'),'')=='') {
          ShowHTML('            <td colspan=3 align="center">&nbsp;</td>');
        } else {
          ShowHTML('            <td align="center" width="1%" nowrap>'.ExibeSinalPesquisa(false,f($row,'phpdt_inicio'),f($row,'phpdt_fim'),f($row,'aviso')).'</td>');
          ShowHTML('            <td align="center">'.nvl(formataDataEdicao(f($row,'phpdt_fim'),5),'---').'</td>');
          ShowHTML('            <td align="center">'.nvl(formatNumber(f($row,'valor_unidade'),4),'---').'</td>');
        }
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_sq_pessoa='.f($row,'fornecedor').'&w_chave_aux='.f($row,'sq_item_fornecedor').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Altera os dados deste registro.">AL</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_sq_pessoa='.f($row,'fornecedor').'&w_chave_aux='.f($row,'sq_item_fornecedor').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Exclui a pesquisa do banco de dados.">EX</A>&nbsp');
        //ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_sq_pessoa='.f($row,'fornecedor').'&w_chave_aux='.f($row,'sq_item_fornecedor').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');  
  } elseif (strpos('IAE',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED '; 
    //Recupera os dados do item
    if(nvl($w_chave_aux,'')=='') {
      $sql = new db_getMTBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
      foreach ($RS as $row) {
        $w_origem      = f($row,'origem');
        $w_nome_item   = f($row,'nome');
        $w_chave_item  = f($row,'chave');
        $w_codigo_item = f($row,'codigo_interno');
        $w_inicio      = nvl($w_inicio,formataDataEdicao(time()));
        break;
      }
    } else {
      $sql = new db_getMTBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,$w_chave_aux,null,null,'PESQMAT');
      //exibeArray($RS);
      foreach ($RS as $row) {
        $w_origem      = f($row,'origem');
        $w_nome_item    = f($row,'nome');
        $w_chave_item   = f($row,'chave');
        $w_codigo_item  = f($row,'codigo_interno');
        $w_inicio       = Nvl(formataDataEdicao(f($row,'phpdt_inicio')),formataDataEdicao(time()));
        $w_dias         = f($row,'dias_validade_proposta');
        $w_valor        = formatNumber(f($row,'valor_unidade'),4);
        $w_fabricante   = f($row,'fabricante');
        $w_marca_modelo = f($row,'marca_modelo');
        $w_embalagem    = f($row,'embalagem');
        $w_fator        = f($row,'w_fator');
        break;
      }      
    }

    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="'.$w_tipo_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_fornecedor" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_material" value="'.f($RS_Item,'chave').'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');    
    
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
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Pesquisa</td></td></tr>');
    ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('<tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    $w_cor=$conTrAlternateBgColor;
    ShowHTML('          <tr bgcolor="'.$w_cor.'" align="center">');
    ShowHTML('            <td><b>Código</td>');
    ShowHTML('            <td><b>Nome</td>');
    ShowHTML('            <td><b>U.M.</td>');
    ShowHTML('            <td><b>Fonte da Pesquisa</td>');
    ShowHTML('            <td><b>Dt.Pesq.</td>');
    ShowHTML('            <td><b>Dias Valid.</td>');
    ShowHTML('            <td><b>Valor</td>');
    ShowHTML('          </tr>');
    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
    ShowHTML('        <td>'.$w_codigo_item.'</td>');
    ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,$w_nome_item,$w_chave_item,$TP,null).'</td>');
    ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
    SelecaoFontePesquisa(null,null,null,$w_origem,null,'w_origem',null,null);
    ShowHTML('        <td align="center" nowrap><input '.$w_Disabled.' type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('        <td align="center" nowrap><input '.$w_Disabled.' type="text" name="w_dias" class="STI" SIZE="4" MAXLENGTH="10" VALUE="'.nvl($w_dias,f($RS_Parametro,'dias_validade_pesquisa')).'" title=Dias de validade da pesquisa de preço."></td>');
    if(nvl($w_valor,'')!='') {
      ShowHTML('        <td align="center"><input type="text" '.$w_Disabled.' name="w_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,4,event);" title="Informe o valor unitário do item."></td>');
    } else {
      ShowHTML('        <td align="center"><input type="text" '.$w_Disabled.' name="w_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="" style="text-align:right;" onKeyDown="FormataValor(this,18,4,event);" title="Informe o valor unitário do item."></td>');
    }
    ShowHTML('        </tr>');
    if (f($RS_Item,'classe')!=5) {
      ShowHTML('        <tr bgcolor="'.$w_cor.'" valign="top"><td colspan="8">');
      ShowHTML('          <TABLE WIDTH="100%" border=0>');
      ShowHTML('            <tr valign="top">');
      ShowHTML('              <td><b>Fabricante: </b><input '.$w_Disabled.' type="text" name="w_fabricante" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$w_fabricante.'"></td>');
      ShowHTML('              <td><b>Marca/Modelo: </b><input '.$w_Disabled.' type="text" name="w_marca_modelo" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$w_marca_modelo.'"></td>');
      ShowHTML('              <td><b>Embalagem: </b><input '.$w_Disabled.' type="text" name="w_embalagem" class="sti" SIZE="15" MAXLENGTH="20" VALUE="'.$w_embalagem.'"></td>');
      ShowHTML('        </table>');
      ShowHTML('        </tr>');        
    }
    ShowHTML('              <INPUT type="hidden" name="w_fator" value="'.f($row,'fator_embalagem').'">');
    ShowHTML('        </table></tr>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('      <tr><td colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if($O=='I') ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&R='.$R.'&O=P&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).montaFiltro('GET').'\';" name="Botao"  value="Cancelar">');
    else        ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&R='.$R.'&O=L&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).montaFiltro('GET').'\';" name="Botao"  value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
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
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
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
    ShowHTML('<INPUT type="hidden" name="p_descricao" value="'.$p_descricao.'">');
    ShowHTML('<INPUT type="hidden" name="p_rgp" value="'.$p_rgp.'">');
    ShowHTML('<INPUT type="hidden" name="p_projeto" value="'.$p_projeto.'">');
    ShowHTML('<INPUT type="hidden" name="p_unidade" value="'.$p_unidade.'">');
    ShowHTML('<INPUT type="hidden" name="p_sqcc" value="'.$p_sqcc.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_pesquisa" value="'.$w_pesquisa.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan=2><b>Parte do <U>n</U>ome da pessoa:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_forn" size="50" maxlength="100" value="'.$p_forn.'">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_cpf" VALUE="'.$p_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    ShowHTML('        <td><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_cnpj" VALUE="'.$p_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" name="Botao" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=L&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_pesquisa='.$w_pesquisa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).montaFiltro('GET').'\';" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
    if ($p_forn!='' || $p_cpf!='' || $p_cnpj!='') {
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
          ShowHTML('            <td><a class="ss" HREF="'.montaURL_JS($w_dir,$w_pagina.$par.'&R='.$R.'&O=A&w_chave='.$w_chave.'&w_menu='.$w_menu.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).montaFiltro('GET').'">Selecionar</a>');
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
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="justify"><b><ul>Instruções</b>:');
    ShowHTML('  <li>Informe parte do nome da pessoa, o CPF ou o CNPJ.');
    ShowHTML('  <li>Quando a relação for exibida, selecione a ação desejada clicando sobre o link <i>Selecionar</i>.');
    ShowHTML('  <li>Após informar os critérios de busca, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.');
    ShowHTML('  <li>Se a pessoa desejada não for encontrada, clique no botão <i>Cadastrar nova pessoa</i>, exibido abaixo da listagem.');
    ShowHTML('  <li><b>Evite cadastrar pessoas que já existem. Procure-a de diversas formas antes de cadastrá-la.</b>');
    ShowHTML('  <li><b>Se precisar alterar os dados de uma pessoa, entre em contato com os gestores do módulo.</b>');
    ShowHTML('  </ul>');
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
// Rotina de tela de exibição do recurso
// -------------------------------------------------------------------------
function TelaPermanente() {
  extract($GLOBALS);
  global $w_Disabled, $w_TP;

  $w_chave = $_REQUEST['w_chave'];
  $w_solic = $_REQUEST['w_solic'];

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<TITLE>SIW - Patrimônio</TITLE>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad="this.focus();"');
  $w_TP = 'Ficha de Bem Patrimonial';
  Estrutura_Texto_Abre();
  ShowHTML(visualPermanente($w_chave,true,$w_solic));
  Estrutura_Texto_Fecha();
} 

// =========================================================================
// Monta string com os dados do material ou serviço
// -------------------------------------------------------------------------
function visualPermanente($l_chave,$l_navega=true,$l_solic) {
  extract($GLOBALS);

  // Recupera os dados do material ou serviço
  $sql = new db_getMTBem; $l_rs = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach ($l_rs as $row) { $l_rs = $row; break; }

  // Se for listagem dos dados
  $l_html = '';
  $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
  $l_html.=chr(13).'<tr><td align="center">';

  $l_html.=chr(13).'    <table width="99%" border="0">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><table width="100%" border="0">';
  $l_html.=chr(13).'        <tr valign="top">';
  $l_html.=chr(13).'          <td>RGP<br><b>'.f($l_rs,'numero_rgp').'</b></td>';
  $l_html.=chr(13).'          <td><br><b>'.f($l_rs,'nome').'</b></td>';
  $l_html.=chr(13).'          <td>Tombamento<br><b>'.formataDataEdicao(f($l_rs,'data_tombamento')).'</b></td>';
  $l_html.=chr(13).'          <td>Situação física<br><b>'.f($l_rs,'nm_situacao').'</b></td>';
  if (nvl(f($l_rs,'codigo_externo'),'')!='') {
    $l_html.=chr(13).'          <td>Código externo:<br><b>'.f($l_rs,'codigo_externo').'</b></td>';
  }
  $l_html.=chr(13).'        <tr><td><td colspan="4" height="1" bgcolor="#000000"></td></tr>';
  $l_html.=chr(13).'        <tr><td><td colspan="4" bgcolor="#f0f0f0">Detalhamento: '.CRLF2BR(f($l_rs,'descricao_complementar')).'</td></tr>';
  $l_html.=chr(13).'        <tr><td><td colspan="4" height="1" bgcolor="#000000"></td></tr>';
  $l_html.=chr(13).'        <tr valign="top">';
  $l_html.=chr(13).'          <td>&nbsp;</td>';
  $l_html.=chr(13).'          <td>Valor de aquisição<br><b>'.
          ((nvl(f($l_rs,'vl_aquisicao_brl'),'0,00')!='0,00') ? ' BRL '.formatNumber(f($l_rs,'vl_aquisicao_brl')) : '').
          ((nvl(f($l_rs,'vl_aquisicao_usd'),'0,00')!='0,00') ? ' USD '.formatNumber(f($l_rs,'vl_aquisicao_usd')) : '').
          ((nvl(f($l_rs,'vl_aquisicao_eur'),'0,00')!='0,00') ? ' EUR '.formatNumber(f($l_rs,'vl_aquisicao_eur')) : '').
          '</b></td>';
  if (nvl(f($l_rs,'vl_aquisicao_brl'),'0,00')!=nvl(f($l_rs,'vl_atual_brl'),'0,00') ||
      nvl(f($l_rs,'vl_aquisicao_usd'),'0,00')!=nvl(f($l_rs,'vl_atual_usd'),'0,00') ||
      nvl(f($l_rs,'vl_aquisicao_eur'),'0,00')!=nvl(f($l_rs,'vl_atual_eur'),'0,00')
     )
  {
    $l_html.=chr(13).'          <td colspan="2" nowrap>Valor atual e última alteração<br><b>'.
            ((nvl(f($l_rs,'vl_atual_brl'),'0,00')!='0,00') ? ' BRL '.formatNumber(f($l_rs,'vl_atual_brl')).' ('.FormataDataEdicao(f($l_rs,'dt_vl_atual_brl')).')' : '').
            ((nvl(f($l_rs,'vl_atual_usd'),'0,00')!='0,00') ? ' USD '.formatNumber(f($l_rs,'vl_atual_usd')).' ('.FormataDataEdicao(f($l_rs,'dt_vl_atual_usd')).')' : '').
            ((nvl(f($l_rs,'vl_atual_eur'),'0,00')!='0,00') ? ' EUR '.formatNumber(f($l_rs,'vl_atual_eur')).' ('.FormataDataEdicao(f($l_rs,'dt_vl_atual_eur')).')' : '').
            '</b></td>';
  } else {
    $l_html.=chr(13).'          <td colspan="2">&nbsp;</td>';
  }
  $l_html.=chr(13).'        <tr><td><td colspan="4" height="1" bgcolor="#000000"></td></tr>';
  $l_html.=chr(13).'        <tr valign="top">';
  $l_html.=chr(13).'          <td>&nbsp;</td>';
  $l_html.=chr(13).'          <td>Marca<br><b>'.nvl(f($l_rs,'marca'),'---').'</b></td>';
  $l_html.=chr(13).'          <td>Modelo<br><b>'.nvl(f($l_rs,'modelo'),'---').'</b></td>';
  $l_html.=chr(13).'          <td>Nº de série<br><b>'.nvl(f($l_rs,'numero_serie'),'---').'</b></td>';
  $l_html.=chr(13).'        </table>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>';
  if (nvl(f($l_rs,'nm_cc'),'')!='')      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Classificação:<b></td><td>'.f($l_rs,'nm_cc').' </td></tr>';
  if (nvl(f($l_rs,'nm_projeto'),'')!='') $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Projeto:<b></td><td>'.f($l_rs,'cd_projeto').' - '.f($l_rs,'nm_projeto').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Classe:<b></td><td>'.f($l_rs,'nm_classe').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Tipo:<b></td><td>'.f($l_rs,'nm_tipo_material_completo').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Almoxarifado:<b></td><td>'.f($l_rs,'nm_almoxarifado').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Unidade:<b></td><td>'.f($l_rs,'nm_unidade').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Localização:<b></td><td>'.f($l_rs,'nm_localizacao').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Endereço:<b></td><td>'.f($l_rs,'logradouro').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Vida útil:<b></td><td>'.nvl(f($l_rs,'vida_util'),'---').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td><b>Observação:</b></td><td>'.CRLF2BR(nvl(f($l_rs,'observacao'),'---')).' </td></tr>';
  
  /*

  if (f($RS_Cliente,'ata_registro_preco')=='S') {
    // Exibe atas de registro de preço onde o item esteja disponível
    $sql = new db_getMTBem; $l_rs1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,'S',null,'S','S','S','S','S','S','S',null,'S',null,null,null,'RELATORIO');
    $l_rs1 = SortArray($l_rs1,'numero_ata','asc','nr_item_ata','asc'); 
 
    $l_html.=chr(13).'      <tr><td colspan="2" align="center"><br>';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" colspan=7><b>ATAS DE RP</b></td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Ata</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Fim vigência</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Item</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Detentor</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>CMM</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Preço</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>% Dif.</td>';
    $l_html.=chr(13).'          </tr>';
    if (count($l_rs1)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $l_html.=chr(13).'      <tr><td colspan=11 align="center"><b>Não foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      foreach($l_rs1 as $row){
        $l_html.=chr(13).'      <tr valign="top">';
        // Se a validade da proposta for menor que o exigido, destaca em vermelho
        $w_percentual_acrescimo = f($row,'percentual_acrescimo');
        if (f($row,'variacao_valor')>f($row,'percentual_acrescimo')) {
          $w_destaque = ' BGCOLOR="'.$conTrBgColorLightRed2.'"';
        } else {
          $w_destaque = '';
        }
        $l_html.=chr(13).'        <td align="center">'.f($row,'numero_ata').'</td>';
        $l_html.=chr(13).'        <td align="center">'.formataDataEdicao(f($row,'fim'),5).'</td>';
        $l_html.=chr(13).'        <td align="center">'.f($row,'nr_item_ata').'</td>';
        $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_detentor_ata'),$TP,f($row,'nm_detentor_ata')).'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),2).'</td>';
        $l_html.=chr(13).'        <td align="right" '.$w_destaque.'>'.nvl(formatNumber(f($row,'valor_unidade'),4),'---').'</td>';
        if (nvl(f($row,'variacao_valor'),'')!='') {
          $l_html.=chr(13).'        <td align="right" '.$w_destaque.'>'.formatNumber(f($row,'variacao_valor'),2).'</td>';
        } else {
          $l_html.=chr(13).'        <td align="right">&nbsp;</td>';
        }
        $l_html.=chr(13).'        </tr>';
      }
    } 
    $l_html.=chr(13).'    </table>';
    $l_html.=chr(13).'<tr><td colspan="2"><b>Observação: linhas com fundo vermelho indicam valor de compra fora da faixa aceitável ($ médio +/- '.$w_percentual_acrescimo.'%).';
  }

  $l_html.=chr(13).'      <tr><td colspan="2" align="center"><br>';
  $l_html.=chr(13).'        <table class="tudo" width=100%  border="1" bordercolor="#00000">';    
  $l_html.=chr(13).'          <tr align="center">';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" colspan=3><b>PESQUISA MAIS RECENTE</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" colspan=3><b>PREÇOS</b></td>';
  $l_html.=chr(13).'          </tr>';
  $l_html.=chr(13).'          <tr align="center">';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" colspan=2><b>Cotação</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Validade</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Menor</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Maior</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Médio</b></td>';
  $l_html.=chr(13).'          </tr>';
  $l_html.=chr(13).'          <tr align="center">';
  if (nvl(f($l_rs,'pesquisa_data'),'')=='') {
    $l_html.=chr(13).'            <td colspan=6 align="center">Nenhuma pesquisa encontrada</td>';
  } else {
    $l_html.=chr(13).'            <td align="center" width="1%" nowrap>'.ExibeSinalPesquisa(false,f($l_rs,'pesquisa_data'),f($l_rs,'pesquisa_validade'),f($l_rs,'pesquisa_aviso')).'</td>';
    $l_html.=chr(13).'            <td align="center"><b>'.nvl(formataDataEdicao(f($l_rs,'pesquisa_data'),5),'---').'</b></td>';
    $l_html.=chr(13).'            <td align="center"><b>'.nvl(formataDataEdicao(f($l_rs,'pesquisa_validade'),5),'---').'</b></td>';
    if (nvl(f($l_rs,'pesquisa_preco_menor'),'')!='') {
      $l_html.=chr(13).'            <td align="center"><b>'.nvl(formatNumber(f($l_rs,'pesquisa_preco_menor'),4),'---').'</b></td>';
      $l_html.=chr(13).'            <td align="center"><b>'.nvl(formatNumber(f($l_rs,'pesquisa_preco_maior'),4),'---').'</b></td>';
      $l_html.=chr(13).'            <td align="center"><b>'.nvl(formatNumber(f($l_rs,'pesquisa_preco_medio'),4),'---').'</b></td>';
    } else {
      $l_html.=chr(13).'            <td align="center" colspan=3><b>Sem pesquisa de preço válida.</b></td>';
    }
  }
  $l_html.=chr(13).'          </tr>';
  $l_html.=chr(13).'         </table></td></tr>';


  // Exibe pesquisas de preço
  $sql = new db_getMTBem; $l_rs = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,'S',null,null,null,null,null,'PESQMAT');
  $l_rs = SortArray($l_rs,'phpdt_fim','desc','valor_unidade','asc','nm_fornecedor','asc'); 
  $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PESQUISAS DE PREÇO VÁLIDAS ('.count($l_rs).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
  if (count($l_rs)==0) {
    $l_html.=chr(13).'      <tr><td colspan="2" align="center">Nenhuma pesquisa válida encontrada</td></tr>';
  } else {
    $l_html.=chr(13).'      <tr><td colspan="2" align="center">';
    $l_html.=chr(13).'        <table class="tudo" width=100%  border="1" bordercolor="#00000">';    
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Fornecedor</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Fonte</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Cotação</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Dias Valid.</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Fim Valid.</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Preço</b></td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$conTrBgColor;
    foreach($l_rs as $row) {
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'        <td width="1%" nowrap>'.ExibeSinalPesquisa(false,f($row,'phpdt_inicio'),f($row,'phpdt_fim'),f($row,'aviso')).'</td>';
      $l_html.=chr(13).'        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
      $l_html.=chr(13).'        <td>'.f($row,'nm_origem').'</td>';
      $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'inicio'),5).'</td>';
      $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
      $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'fim'),5).'</td>';
      $l_html.=chr(13).'        <td align="right" nowrap>'.formatNumber(f($row,'valor_unidade'),4).'</td>';
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'         </table></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan=2><table border=0><tr><td colspan=3><b>Legenda:</b><tr><td>'.ExibeSinalPesquisa(true,null,null,null).'</td></tr></table>';
  }

  if ($w_al=='S') {
    // Exibe histórico de compras
    $sql = new db_getSolicMT; $l_rs1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ALMAPA',3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente,null, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
        $l_chave, null, $p_empenho, null);

    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>CONTROLE DE ESTOQUE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2" align="center"><br>';
    $l_html.=chr(13).'        <table class="tudo" width=100%  border="1" bordercolor="#00000">';    
    $l_html.=chr(13).'        <tr align="center">';
    $l_html.=chr(13).'          <td rowspan="2" bgColor="#f0f0f0"><b>DATA</b></td>';
    $l_html.=chr(13).'          <td colspan="3" bgColor="#f0f0f0"><b>ENTRADA</b></td>';
    $l_html.=chr(13).'          <td colspan="3" bgColor="#f0f0f0"><b>SAÍDA</b></td>';
    $l_html.=chr(13).'          <td colspan="3" bgColor="#f0f0f0"><b>ESTOQUE</b></td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>QTD.</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>V.U.</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>V.T.</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>QTD.</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>V.U.</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>V.T.</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>QTD.</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>V.U.</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>V.T.</b></td>';
    $l_html.=chr(13).'        </tr>';
    if (count($l_rs1)==0) {
      // Se não foram selecionados registros, exibe mensagem
      $l_html.=chr(13).'      <tr><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_qtd = 0;
      $w_vu  = 0;
      $w_vt  = 0;
      foreach($l_rs1 as $row){
        $l_html.=chr(13).'        <tr valign="top">';
        if (nvl(f($row,'quantidade_pedida'),0)==0) {
          $l_html.=chr(13).'          <td align="center" title="Data de armazenamento">'.formataDataEdicao(f($row,'armazenamento'),5).'</td>';
          $l_html.=chr(13).'          <td align="center">+'.formatNumber(f($row,'qt_entrada'),0).'</td>';
          $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'vl_entrada'),5).'</td>';
          $l_html.=chr(13).'          <td align="right">+'.formatNumber(f($row,'tot_entrada'),2).'</td>';
          $l_html.=chr(13).'          <td colspan="3">&nbsp;</td>';
          $w_vu   = f($row,'vl_entrada');
          $w_qtd += f($row,'qt_entrada');
          $w_vt  += f($row,'tot_entrada');
        } else {
          $l_html.=chr(13).'          <td align="center" title="Data efetiva de entrega">'.formataDataEdicao(f($row,'data_efetivacao'),5).'</td>';
          $l_html.=chr(13).'          <td colspan="3">&nbsp;</td>';
          $l_html.=chr(13).'          <td align="center">-'.formatNumber(f($row,'quantidade_entregue'),0).'</td>';
          $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'preco_medio')).'</td>';
          $l_html.=chr(13).'          <td align="right">-'.formatNumber(f($row,'vl_saida')).'</td>';
          $w_vu   = f($row,'preco_medio');
          $w_qtd -= f($row,'quantidade_entregue');
          $w_vt  -= f($row,'vl_saida');
        }
        $l_html.=chr(13).'          <td align="center">'.formatNumber($w_qtd,0).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber($w_vu,5).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber($w_vt,2).'</td>';
        $l_html.=chr(13).'        </tr>';
      }
    } 
    $l_html.=chr(13).'    </table>';
  }
  */

  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>';
  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;

} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  global $w_Disabled;
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');

  switch ($SG) {
    case 'MTBEM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='C' || $O=='I' || $O=='A') {
          // Testa a existência do nome
          $sql = new db_getMTBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$_REQUEST['w_chave'],null,null,null,null,null,$_REQUEST['w_rgp'],null,null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
          if (count($RS)>0) {
            foreach ($RS as $row) { $RS = $row; break; }
            if (f($RS,'existe')>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Já existe material ou serviço com este nome!");');
              ScriptClose(); 
              retornaFormulario('w_rgp');
              break;
            } 
          }

          if (nvl($_REQUEST['w_codigo_interno'],'nulo')!='nulo') {
            // Testa a existência do código
            $sql = new db_getMTBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,nvl($_REQUEST['w_chave'],''),null,$_REQUEST['w_codigo_interno'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
            if (count($RS)>0) {
              foreach ($RS as $row) { $RS = $row; break; }
              if (f($RS,'existe')>0) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert("Já existe material ou serviço com este código!");');
                ScriptClose(); 
                retornaFormulario('w_codigo_interno');
                break;
              } 
            }
          }
        }/* elseif ($O=='E') {
          $sql = new db_getMTBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Não é possível excluir este material ou serviço. Ele está ligado a algum documento!");');
            ScriptClose();
            break;
            retornaFormulario('w_assinatura');
          } 
        } */
        
        $SQL = new dml_putMTPermanente; $SQL->getInstanceOf($dbms,$O,$w_cliente,$w_usuario, $_REQUEST['w_chave'],$_REQUEST['w_copia'],
            $_REQUEST['w_localizacao'], $_REQUEST['w_almoxarifado'], $_REQUEST['w_projeto'],$_REQUEST['w_sqcc'],
            $_REQUEST['w_material'],$_REQUEST['w_entrada'],$_REQUEST['w_situacao'],$_REQUEST['w_forn_garantia'],
            $_REQUEST['w_rgp'], $_REQUEST['w_tombamento'], $_REQUEST['w_descricao'], $_REQUEST['w_codigo_externo'], 
            $_REQUEST['w_numero_serie'], $_REQUEST['w_marca'], $_REQUEST['w_modelo'], $_REQUEST['w_fim_garantia'], 
            $_REQUEST['w_vida_util'], $_REQUEST['w_observacao'], $_REQUEST['w_ativo'],
            $_REQUEST['w_valor_brl'], $_REQUEST['w_valor_usd'], $_REQUEST['w_valor_eur'],
            $_REQUEST['w_data_brl'], $_REQUEST['w_data_usd'], $_REQUEST['w_data_eur'],
            $w_chave_nova);

        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&O=L&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'CLPESQUISA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if ($_REQUEST['w_tipo_pessoa']==1) {
            // Verifica se já existe pessoa física com o CPF informado
            $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_pessoa'],null,nvl($_REQUEST['w_cpf'],'0'),null,null,$_REQUEST['w_tipo_pessoa'],null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Já existe pessoa cadastrada com o CPF informado!\\nVerifique os dados.\');');
              ScriptClose();
              retornaFormulario('w_cpf');
              exit;
            }
            // Verifica se já existe pessoa física com o mesmo nome. Se existir, é obrigatório informar o CPF.
            $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,null,nvl($_REQUEST['w_nome'],'0'),$_REQUEST['w_tipo_pessoa'],null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
            if (count($RS)>0) {
              foreach ($RS as $row) {
                if (strlen(f($row,'nm_pessoa'))==strlen($_REQUEST['w_nome']) && (nvl(f($row,'identificador_primario'),'')=='' || nvl($_REQUEST['w_cpf'],'')=='')) {
                  ScriptOpen('JavaScript');
                  if (nvl(f($row,'identificador_primario'),'')=='') {
                    ShowHTML('  alert(\'Já existe pessoa cadastrada com o nome informado!\\nVerifique os dados e, se necessário, solicite ao gestor a alteração dos dados da pessoa já cadastrada.\');');
                  } else {
                    ShowHTML('  alert(\'Já existe pessoa cadastrada com o nome informado!\\nNeste caso é obrigatório informar o CPF.\');');
                  }
                  ScriptClose();
                  retornaFormulario('w_cpf');
                  exit;
                }
              }
            }
          } else {
            // Verifica se já existe pessoa jurídica com o CNPJ informado
            $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,nvl($_REQUEST['w_cnpj'],'0'),null,$_REQUEST['w_tipo_pessoa'],null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Já existe pessoa jurídica cadastrada com o CNPJ informado!\\nVerifique os dados.\');');
              ScriptClose();
              retornaFormulario('w_cnpj');
              exit;
            }
            // Verifica se já existe pessoa jurídica com o mesmo nome. Se existir, é obrigatório informar o CNPJ.
            $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,null,nvl($_REQUEST['w_nome'],'0'),$_REQUEST['w_tipo_pessoa'],null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
            if (count($RS)>0) {
              foreach ($RS as $row) {
                if (strlen(f($row,'nm_pessoa'))==strlen($_REQUEST['w_nome']) && (nvl(f($row,'identificador_primario'),'')=='' || nvl($_REQUEST['w_cnpj'],'')=='')) {
                  ScriptOpen('JavaScript');
                  if (nvl(f($row,'identificador_primario'),'')=='') {
                    ShowHTML('  alert(\'Já existe pessoa cadastrada com o nome informado!\\nVerifique os dados e, se necessário, solicite ao gestor a alteração dos dados da pessoa já cadastrada.\');');
                  } else {
                    ShowHTML('  alert(\'Já existe pessoa cadastrada com o nome informado!\\nNeste caso é obrigatório informar o CNPJ.\');');
                  }
                  ScriptClose();
                  retornaFormulario('w_cnpj');
                  exit;
                }
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
            $_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],$_REQUEST['w_email'],null,$w_chave_nova);
      
        // Inseri as cotaçoes e atualiza a tabela de materiais
        $SQL = new dml_putCLPesqFornecedor; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave_aux'],null,$w_chave_nova,
           $_REQUEST['w_inicio'],$_REQUEST['w_dias'],$_REQUEST['w_valor'],$_REQUEST['w_fabricante'],
           $_REQUEST['w_marca_modelo'],$_REQUEST['w_embalagem'],$_REQUEST['w_fator'],$_REQUEST['w_sq_material'],
           $_REQUEST['w_origem']);
      
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
    default:
      exibevariaveis();
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
      ScriptClose();
      break;
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':            Inicial();           break;
    case 'DISPONIVEL':         Disponivel();        break;
    case 'INDISPONIVEL':       Indisponivel();      break;
    case 'TELAPERMANENTE':     TelaPermanente();    break;
    case 'SOLICPERIODO':       SolicPeriodo();      break;
    case 'PESQUISAPRECO':      PesquisaPreco();     break;
    case 'GRAVA':              Grava();             break;
    default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exibevariaveis();
  break;
  } 
} 
?>