<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getAddressData.php');
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

// =========================================================================
//  /rel_depreciacao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório de depreciação de bens
// Mail     : alex@sbpi.com.br
// Criacao  : 17/11/2019, 11:06
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
$w_pagina     = 'rel_depreciacao.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_mt/';
$w_troca      = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$p_chave          = $_REQUEST['p_chave'];
$p_tipo_material  = $_REQUEST['p_tipo_material'];
$p_financeiro     = $_REQUEST['p_financeiro'];
$p_descricao      = $_REQUEST['p_descricao'];
$p_marca          = $_REQUEST['p_marca'];
$p_modelo         = $_REQUEST['p_modelo'];
$p_observacao     = $_REQUEST['p_observacao'];
$p_ativo          = $_REQUEST['p_ativo'];
$p_rgp            = $_REQUEST['p_rgp'];
$p_sqcc           = $_REQUEST['p_sqcc'];
$p_projeto        = $_REQUEST['p_projeto'];
$p_material       = $_REQUEST['p_material'];
$p_almoxarifado   = $_REQUEST['p_almoxarifado'];
$p_unidade        = $_REQUEST['p_unidade'];
$p_localizacao    = $_REQUEST['p_localizacao'];
$p_situacao       = $_REQUEST['p_situacao'];
$p_inicio         = $_REQUEST['p_inicio'];
$p_fim            = $_REQUEST['p_fim'];
$p_endereco       = $_REQUEST['p_endereco'];
$p_codigo_externo = $_REQUEST['p_codigo_externo'];

$p_ordena         = $_REQUEST['p_ordena'];
$p_volta          = upper($_REQUEST['p_volta']);

if ($SG=='MTDEPREC') {
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
      if ($p_endereco>'') {
        $sql = new db_getAddressData; $RS = $sql->getInstanceOf($dbms, $p_endereco);
        $w_filtro.='<tr><td align="right">Endereço <td>[<b>'.f($RS,'endereco_completo').'</b>]';
      } 
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
      if ($p_fim>'')      $w_filtro .= '<tr valign="top"><td align="right">Tombamento <td>[<b>'.$p_inicio.'-'.$p_fim.'</b>]';
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
            $p_unidade,  $p_localizacao, $p_situacao, $p_inicio, $p_fim, $p_codigo_externo,
            $p_restricao);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_tipo_material','asc','numero_rgp','asc');
    } else {
      $RS = SortArray($RS,'nm_tipo_material','asc','numero_rgp','asc'); 
    }
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
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_rgp','RGP Atual','1','','1','18','','0123456789');
      Validate('p_material','Bem','1','','2','90','1','1');
      Validate('p_financeiro','Código interno','1','','2','30','1','1');
      Validate('p_projeto','Projeto','SELECT','','1','18','','1');
      Validate('p_descricao','Descrição complementar','','','2','2000','1','1');
      Validate('p_marca','Marca','','',1,50,'1','1');
      Validate('p_modelo','Modelo','','',1,50,'1','1');
      Validate('p_observacao','Observação','','',1,2000,'1','1');
      Validate('p_codigo_externo','Código externo','','',1,30,'1','1');
      Validate('p_inicio','Tombamento inicial', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_fim','Tombamento final', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_inicio.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_inicio.value == \'\' && theForm.p_fim.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de tombamento ou nenhuma delas!\');');
      ShowHTML('     theForm.p_inicio.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_inicio','Tombamento inicial','<=','p_fim','Tombamento final');
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
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Tipo','nm_tipo_material').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Almoxarifado','nm_almoxarifado').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Unidade','nm_unidade').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Localização','nm_localizacao').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Projeto','cd_projeto').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('RGP','numero_rgp').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Bem','nome').'</td>');
      //ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Detalhamento','descricao_complementar').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Tombamento','data_tombamento').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Vida Útil (Anos)','vida_util').'</td>');
      ShowHTML('          <td colspan=3><b>Valor Aquisição</b></td>');
      ShowHTML('          <td colspan=3><b>Valor Depreciado</b></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('BRL','vl_atual_brl').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('USD','vl_atual_usd').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('EUR','vl_atual_eur').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('BRL','vl_depreciado_brl').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('USD','vl_depreciado_usd').'</b></td>');
      ShowHTML('          <td><b>'.LinkOrdena('EUR','vl_depreciado_eur').'</b></td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td rowspan=2><b>Tipo</td>');
      //ShowHTML('          <td rowspan=2><b>Almoxarifado</td>');
      //ShowHTML('          <td rowspan=2><b>Unidade</td>');
      //ShowHTML('          <td rowspan=2><b>Localização</td>');
      //ShowHTML('          <td rowspan=2><b>Projeto</td>');
      ShowHTML('          <td rowspan=2><b>RGP</td>');
      ShowHTML('          <td rowspan=2><b>Bem</td>');
      //ShowHTML('          <td rowspan=2><b>Detalhamento</td>');
      ShowHTML('          <td rowspan=2><b>Tombamento</td>');
      ShowHTML('          <td rowspan=2><b>Vida Útil (Anos)</td>');
      ShowHTML('          <td colspan=3><b>Valor Aquisição</b></td>');
      ShowHTML('          <td colspan=3><b>Valor Depreciado</b></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>BRL</b></td>');
      ShowHTML('          <td><b>USD</b></td>');
      ShowHTML('          <td><b>EUR</b></td>');
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
        ShowHTML('        <td>'.f($row,'nm_tipo_material').'</td>');
        //ShowHTML('        <td nowrap>'.f($row,'nm_almoxarifado').'</td>');
        //ShowHTML('        <td>'.nvl(f($row,'nm_unidade'),'---').'</td>');
        //ShowHTML('        <td>'.nvl(f($row,'nm_localizacao'),'---').'</td>');
        //ShowHTML('        <td title="'.f($row,'nm_projeto').'">'.nvl(f($row,'cd_projeto'),'---').'</td>');
        ShowHTML('        <td align="center"'.((nvl(f($row,'observacao'),'')!='') ? ' title="'.  CRLF2BR(f($row,'observacao')).'"' : '').'>'.f($row,'numero_rgp').'</td>');
        if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePermanente($w_dir_volta,$w_cliente,f($row,'nome_completo'),f($row,'chave'),$TP,null,((nvl(f($row,'descricao_complementar'),'')!='') ? f($row,'descricao_complementar') : null)).'</td>');
        else                 ShowHTML('        <td>'.f($row,'nome_completo').'</td>');
        //ShowHTML('        <td>'.f($row,'descricao_complementar').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'data_tombamento'),5).'</td>');
        ShowHTML('        <td align="center">'.f($row,'vida_util').'</td>');
        ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'vl_atual_brl'),2),'---').'</td>');
        ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'vl_atual_usd'),2),'---').'</td>');
        ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'vl_atual_eur'),2),'---').'</td>');
        ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'vl_depreciado_brl'),2),'---').'</td>');
        ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'vl_depreciado_usd'),2),'---').'</td>');
        ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'vl_depreciado_eur'),2),'---').'</td>');
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
    ShowHTML('      <tr><td valign="top"><b><u>T</u>ombamento entre:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_inicio').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').'</td>');

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
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':            Inicial();           break;
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