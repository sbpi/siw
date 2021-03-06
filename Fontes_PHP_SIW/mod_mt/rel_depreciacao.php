<?php
/*
select p.sq_permanente chave_siw, p.numero_rgp rfid,
       c.nome as grupo,
       a.nome||case when p.marca is not null then ' '||p.marca end||
       case when p.modelo is not null then ' '||p.modelo end||
       case when p.numero_serie is not null then ' S�rie: '||p.numero_serie end||' '||
       p.descricao_complementar bem, 
       to_char(p.data_tombamento,'dd/mm/yyyy') tombamento,
       p.cc_patrimonial, 
       brl.valor_atual vl_aquisicao,
       p.vida_util,
       p.vida_util * 365 vida_util_dias,
       --(brl.valor_atual/(p.vida_util*365) * 30) deprec_mensal,
       --(brl.valor_atual/(p.vida_util*365)) deprec_diaria,
       case when p.data_tombamento > to_date('31/12/2018','dd/mm/yyyy') then 0
            when to_date('31/12/2018','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 then p.vida_util * 365
            else to_date('31/12/2018','dd/mm/yyyy') - p.data_tombamento 
       end dias_inicio_periodo,
       case when p.data_tombamento > to_date('31/12/2018','dd/mm/yyyy') then 0
            when p.data_tombamento + (p.vida_util * 365) <=  to_date('31/12/2018','dd/mm/yyyy') then 100
            else round((to_date('31/12/2018','dd/mm/yyyy') - p.data_tombamento) / (365*p.vida_util) * 100,2) 
       end percentual_inicio_periodo,
       case when to_date('31/12/2018','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, null, to_date('31/12/2018','dd/mm/yyyy')) end deprec_inicio_periodo,
       case when to_date('01/01/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('31/01/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/01/2019','dd/mm/yyyy'), to_date('31/01/2019','dd/mm/yyyy')) end mes_1,
       case when to_date('01/02/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('28/02/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/02/2019','dd/mm/yyyy'), to_date('28/02/2019','dd/mm/yyyy')) end mes_2,
       case when to_date('01/03/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('31/03/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/03/2019','dd/mm/yyyy'), to_date('31/03/2019','dd/mm/yyyy')) end mes_3,
       case when to_date('01/04/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('30/04/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/04/2019','dd/mm/yyyy'), to_date('30/04/2019','dd/mm/yyyy')) end mes_4,
       case when to_date('01/05/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('31/05/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/05/2019','dd/mm/yyyy'), to_date('31/05/2019','dd/mm/yyyy')) end mes_5,
       case when to_date('01/06/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('30/06/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/06/2019','dd/mm/yyyy'), to_date('30/06/2019','dd/mm/yyyy')) end mes_6,
       case when to_date('01/07/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('31/07/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/07/2019','dd/mm/yyyy'), to_date('31/07/2019','dd/mm/yyyy')) end mes_7,
       case when to_date('01/08/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('31/08/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/08/2019','dd/mm/yyyy'), to_date('31/08/2019','dd/mm/yyyy')) end mes_8,
       case when to_date('01/09/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('30/09/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/09/2019','dd/mm/yyyy'), to_date('30/09/2019','dd/mm/yyyy')) end mes_9,
       case when to_date('01/10/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('31/10/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/10/2019','dd/mm/yyyy'), to_date('31/10/2019','dd/mm/yyyy')) end mes_10,
       case when to_date('01/11/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('30/11/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/11/2019','dd/mm/yyyy'), to_date('30/11/2019','dd/mm/yyyy')) end mes_11,
       case when to_date('01/12/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('31/12/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/12/2019','dd/mm/yyyy'), to_date('31/12/2019','dd/mm/yyyy')) end mes_12,
       p.cc_depreciacao,
       case when to_date('01/01/2019','dd/mm/yyyy') - p.data_tombamento > p.vida_util * 365 or to_date('31/01/2019','dd/mm/yyyy') < p.data_tombamento then 0 else calculaDepreciacao(p.sq_permanente, brl.sq_moeda, to_date('01/01/2019','dd/mm/yyyy'), to_date('31/12/2019','dd/mm/yyyy')) end deprec_ano,
       calculaDepreciacao(p.sq_permanente, brl.sq_moeda, p.data_tombamento, to_date('31/12/2019','dd/mm/yyyy')) deprec_total,
       case when p.data_tombamento > to_date('31/12/2019','dd/mm/yyyy') then 0
            when p.data_tombamento + (p.vida_util * 365) <=  to_date('31/12/2019','dd/mm/yyyy') then 100
            else round((to_date('31/12/2019','dd/mm/yyyy') - p.data_tombamento) / (365*p.vida_util) * 100,2) 
       end percentual_fim_periodo,
       brl.valor_atual - calculaDepreciacao(p.sq_permanente, brl.sq_moeda, p.data_tombamento, to_date('31/12/2019','dd/mm/yyyy')) valor_fim_periodo
  from mt_permanente p
       inner           join cl_material         a  on (p.sq_material         = a.sq_material)
         inner         join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
         inner         join mt_almoxarifado     x  on (p.sq_almoxarifado     = x.sq_almoxarifado)
       left            join (select cot.sq_permanente, cot.sq_moeda, cot.valor_aquisicao, cot.valor_atual, cot.data_valor_atual
                               from mt_bem_cotacao      cot
                                    inner join co_moeda moe on (cot.sq_moeda = moe.sq_moeda)
                              where moe.sigla = 'BRL'
                            )                brl  on (p.sq_permanente       = brl.sq_permanente)
 where p.ativo = 'S'
   and x.nome = 'Imobilizado Contabilidade'
   and p.data_tombamento <= to_date('31/12/2019','dd/mm/yyyy')
   --and p.data_tombamento + (p.vida_util * 365) >= to_date('01/01/2019','dd/mm/yyyy')
   --and p.numero_rgp = 6016
   --and p.data_tombamento + (p.vida_util * 365) between to_date('01/01/2019','dd/mm/yyyy') and to_date('31/12/2019','dd/mm/yyyy')
   --and p.data
order by 3,2
*/

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
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Relat�rio de deprecia��o de bens
// Mail     : alex@sbpi.com.br
// Criacao  : 17/11/2019, 11:06
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = C   : Cancelamento
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = M   : Configura��o de servi�os

// Carrega vari�veis locais com os dados dos par�metros recebidos
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

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
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
$p_expirado       = $_REQUEST['p_expirado'];
$p_fim            = $_REQUEST['p_fim'];
$p_endereco       = $_REQUEST['p_endereco'];
$p_codigo_externo = $_REQUEST['p_codigo_externo'];

$p_ordena         = $_REQUEST['p_ordena'];
$p_volta          = upper($_REQUEST['p_volta']);

if ($SG=='MTDEPREC') {
  if ($O=='') $O='P';
} elseif ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';        break;
  case 'A': $w_TP=$TP.' - Altera��o';       break;
  case 'E': $w_TP=$TP.' - Exclus�o';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - C�pia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'H': $w_TP=$TP.' - Heran�a';         break;
  case 'M': $w_TP=$TP.' - Servi�os';        break;
  default:  $w_TP=$TP.' - Listagem';        break;
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

// Recupera as informa��es do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente);

// Recupera as informa��es da op�ao de menu;
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
// Se for sub-menu, pega a configura��o do pai
if (f($RS_Menu,'ultimo_nivel') == 'S') {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

// Verifica se o cliente tem o m�dulo de materiais
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'AL');
if (count($RS)>0) $w_al='S'; else $w_al='N'; 

// Verifica se o cliente tem o m�dulo de projetos
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PR');
if (count($RS)>0) $w_pr='S'; else $w_pr='N'; 

// Verifica se o cliente tem o m�dulo financeiro
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'FN');
if (count($RS)>0) $w_fn='S'; else $w_fn='N'; 

// Recupera os par�metros do m�dulo de compras e licita��es
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

  if ($w_troca>'') {
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
  }

  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']); 
  } else {
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    ShowHTML('<TITLE>'.$conSgSistema.' - Materiais e Servi�os</TITLE>');
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
      Validate('p_financeiro','C�digo interno','1','','2','30','1','1');
      Validate('p_projeto','Projeto','SELECT','','1','18','','1');
      Validate('p_descricao','Descri��o complementar','','','2','2000','1','1');
      Validate('p_marca','Marca','','',1,50,'1','1');
      Validate('p_modelo','Modelo','','',1,50,'1','1');
      Validate('p_observacao','Observa��o','','',1,2000,'1','1');
      Validate('p_codigo_externo','C�digo externo','','',1,30,'1','1');
      Validate('p_inicio','In�cio do per�odo', 'DATA', 1, '10', '10', '', '0123456789/');
      Validate('p_fim','Fim do per�odo', 'DATA', 1, '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_inicio.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_inicio.value == \'\' && theForm.p_fim.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_inicio.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_inicio','In�cio do per�odo','<=','p_fim','Fim do per�odo');
      ShowHTML('  if (theForm.p_expirado.checked && theForm.p_inicio.value == \'\') {');
      ShowHTML('     alert (\'Para busca por bens com vida �til expirada no per�odo � obrigat�rio informar o per�odo!\');');
      ShowHTML('     theForm.p_inicio.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ValidateClose();
      ScriptClose();
    } 
  
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
  }
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
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
  if ($SG=='MTDEPREC') $rel = 'depreciacao';
  else $rel = 'detDesp';
  AbreForm('Form',$w_dir.$w_pagina.$rel,'POST','return(Validacao(this));','Deprecia��o',$P1,'',$P3,null,$TP,$SG,$R,'L');
  ShowHTML(montaFiltro('POST',true));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('      <tr><td colspan=2><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td><b><u>R</u>GP Atual:</b><br><input '.$p_Disabled.' accesskey="R" type="text" name="p_rgp" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$p_rgp.'"></td>');
  ShowHTML('          <td><b><u>B</u>em:</b><br><input '.$p_Disabled.' accesskey="B" type="text" name="p_material" class="sti" SIZE="40" MAXLENGTH="40" VALUE="'.$p_material.'"></td>');
  ShowHTML('          <td><b><u>C</u>�digo do lan�amento financeiro:</b><br><input '.$p_Disabled.' accesskey="C" type="text" name="p_financeiro" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$p_financeiro.'"></td>');

  ShowHTML('      <tr>');
  SelecaoEndereco('En<u>d</u>ere�o principal:','d',null,$p_endereco,$w_cliente,'p_endereco','FISICO', 'onChange="document.Form.target=\'\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_endereco\'; document.Form.submit();"', 3);
  ShowHTML('      </tr>');

  ShowHTML('      <tr valign="top">');
  selecaoalmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado ao qual o bem pertence.', $p_almoxarifado,'p_almoxarifado',null,null);

  ShowHTML('      <tr valign="top">');
  SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$p_unidade,$p_endereco,'p_unidade','MOD_MT','onChange="document.Form.target=\'\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_unidade\'; document.Form.submit();"', 3);

  ShowHTML('      <tr>');
  selecaoLocalizacao('Lo<U>c</U>aliza��o:','C',null,$p_localizacao,$p_unidade,'p_localizacao',null,null,3);
  ShowHTML('      </tr>');

  ShowHTML('      <tr valign="top">');
  selecaoTipoMatServ('T<U>i</U>po de material:','I',null,$p_tipo_material,null,'p_tipo_material','FOLHAPER',null,3);

  ShowHTML('          <tr>');
  $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
  SelecaoSolic('Projeto:',null,null,$w_cliente,$p_projeto,$w_sq_menu_relac,f($RS1,'sq_menu'),'p_projeto',f($RS_Menu,'sigla'),null,$p_projeto,'<BR />',3);
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td><b><u>M</u>arca:</b><br><input accesskey="M" type="text" name="p_marca" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_marca.'"></td>');
  ShowHTML('        <td><b><u>M</u>odelo:</b><br><input accesskey="M" type="text" name="p_modelo" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_modelo.'"></td>');
  selecaoMtSituacao('<u>S</u>itua��o f�sica:','S', null, $p_situacao,'p_situacao','BEM',null);
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td><b><U>D</U>escri��o complementar:<br><input accesskey="M" type="text" name="p_descricao" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_descricao.'"></td>');
  ShowHTML('        <td><b><U>O</U>bserva��o:<br><input accesskey="M" type="text" name="p_observacao" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_observacao.'"></td>');
  ShowHTML('        <td><b><u>C</u>�digo externo:</b><br><input accesskey="C" type="text" name="p_codigo_externo" class="sti" SIZE="25" MAXLENGTH="30" VALUE="'.$p_codigo_externo.'"></td>');
  ShowHTML('      <tr><td colspan="3" valign="top"><b><u>P</u>er�odo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_inicio').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').' ');
  ShowHTML('          <input class="item" type="CHECKBOX" '.(($p_expirado=='S') ? 'CHECKED' : '').' name="p_expirado" value="S"> Apenas bens com expira��o da vida �til no per�odo informado');

  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td><b>Recuperar:</b><br>');
  ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"'.(($p_ativo=='S') ? ' checked' : '').'> Apenas ativos<br>');
  ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"'.(($p_ativo=='N') ? ' checked' : '').'> Apenas inativos<br>');
  ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""'.((nvl($p_ativo,'X')=='X') ? ' checked' : '').'> Tanto faz');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
  ShowHTML('          </table>');
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
  ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 

// =========================================================================
// Relat�rio de deprecia��o
// -------------------------------------------------------------------------
function depreciacao() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_copia              = $_REQUEST['w_copia'];
  $w_tipo               = $_REQUEST['w_tipo'];
  $w_tipo_material      = $_REQUEST['w_tipo_material'];
  
  $sql = new db_getMTBem;
  $RSQuery = $sql->getInstanceOf($dbms,$w_cliente, $w_usuario, $p_chave, $p_sqcc, 
          $p_projeto, $p_financeiro, $p_tipo_material, $p_material, $p_rgp, $p_descricao,
          $p_marca, $p_modelo, $p_observacao, $p_ativo, $p_almoxarifado, $p_endereco, 
          $p_unidade,  $p_localizacao, $p_situacao, $p_inicio, $p_fim, $p_codigo_externo,
          (($p_expirado=='S') ? 'EXPIRACAO' : $p_restricao));
  $RSQuery = SortArray($RSQuery,'nm_tipo_material','asc','nome_completo','asc','numero_rgp','asc'); 

  $w_embed  = '';
  $l_html   = '';
  $w_filtro = '';
  
  headerGeral('P', $w_tipo, $w_chave, $conSgSistema.' - Patrim�nio', $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Patrim�nio</TITLE>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    BodyOpenClean('onLoad="this.focus()";');
    CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave);
    ShowHTML('<HR>');
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  if ($w_tipo!='EXCEL') {
    if ($p_rgp>'')          $w_filtro.='<tr><td align="right">RGP <td>[<b>'.$p_rgp.'</b>]';
    if ($p_material>'')     $w_filtro.='<tr><td align="right">Bem <td>[<b>'.$p_material.'</b>] em qualquer parte';
    if ($p_financeiro>'')   $w_filtro.='<tr><td align="right">Financeiro <td>[<b>'.$p_financeiro.'</b>]';
    if ($p_endereco>'') {
      $sql = new db_getAddressData; $RS = $sql->getInstanceOf($dbms, $p_endereco);
      $w_filtro.='<tr><td align="right">Endere�o <td>[<b>'.f($RS,'endereco_completo').'</b>]';
    } 
    if ($p_almoxarifado>'') {
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_almoxarifado,null,null,null,null,'OUTROS');
      $w_filtro.='<tr><td align="right">Almoxarifado <td>[<b>'.f($RS[0],'nome').'</b>]';
    } 
    if ($p_tipo_material>'') {
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_tipo_material,null,null,null,null,null,null,'REGISTROS');
      $w_filtro.='<tr><td align="right">Tipo <td>[<b>'.f($RS[0],'nome_completo').'</b>]';
    } 
    if ($p_projeto>'') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informa��es do projeto." target="_blank">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_marca>'')        $w_filtro.='<tr><td align="right">Marca <td>[<b>'.$p_marca.'</b>] em qualquer parte';
    if ($p_modelo>'')       $w_filtro.='<tr><td align="right">Modelo <td>[<b>'.$p_modelo.'</b>] em qualquer parte';
    if ($p_situacao>'') {
      $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$p_situacao,null,null,null);
      $w_filtro.='<tr><td align="right">Situa��o f�sica <td>[<b>'.f($RS[0],'nome').'</b>]';
    } 
    if ($p_descricao>'')    $w_filtro.='<tr><td align="right">Descri��o <td>[<b>'.$p_descricao.'</b>] em qualquer parte';
    if ($p_observacao>'')   $w_filtro.='<tr><td align="right">Observa��o <td>[<b>'.$p_observacao.'</b>] em qualquer parte';
    if ($p_fim>'')          $w_filtro .= '<tr valign="top"><td align="right">Per�odo <td>[<b>'.$p_inicio.'-'.$p_fim.'</b>]';
    if ($p_expirado=='S') $w_filtro.='<tr><td align="right">Restri��o <td>[<b>Apenas bens com vida �til expirada no per�odo</b>]';
    if ($p_ativo=='S') {
      $w_filtro.='<tr><td align="right">Situa��o <td>[<b>Apenas itens ativos</b>]';
    } elseif ($p_ativo=='N') {
      $w_filtro.='<tr><td align="right">Situa��o <td>[<b>Apenas itens inativos</b>]';
    } else {
      $w_filtro.='<tr><td align="right">Situa��o <td>[<b>Itens ativos e inativos</b>]';
    }
    if ($w_filtro) {
      $l_html .= '<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    }
  }
  
  
  $w_resumo = array();
  $w_qtd_geral = 0;
  $w_aquisicao_geral = 0;
  $w_periodo_geral = 0;
  $w_acumulado_geral = 0;
  $w_atual_geral = 0;
  $w_atual = '*0*'; // valor qualquer apenas para marcar o in�cio da execu��o

  $l_html .= '<tr><td align="center" colspan=3><div align="center">';
  $l_html .= '    <TABLE class="tudo" width=99%  border="1" bordercolor="#00000">';
  if (count($RSQuery)<=0) {
    // Se n�o foram selecionados registros, exibe mensagem
    $l_html .= '      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>N�o foram encontrados registros.</b></td></tr>';
  } else {
    foreach($RSQuery as $row){ 
      if ($w_atual != f($row,'nm_tipo_material')) {
        if ($w_atual != '*0*') {
          // Linha de totais do tipo de material
          $l_html .= '      <tr valign="top">';
          $l_html .= '        <td align="right" colspan='.($colspan-4).'><b> Total '.$w_atual.' ('.formatNumber($w_resumo[$w_atual]['qtd'],0).' bens)</b></td>';
          $l_html .= '        <td align="right"><b>'.formatNumber($w_resumo[$w_atual]['aquisicao']).'</b></td>';
          $l_html .= '        <td align="right"><b>'.formatNumber($w_resumo[$w_atual]['periodo']).'</b></td>';
          $l_html .= '        <td align="right"><b>'.formatNumber($w_resumo[$w_atual]['acumulado']).'</b></td>';
          $l_html .= '        <td align="right"><b>'.formatNumber($w_resumo[$w_atual]['atual']).'</b></td>';
          $l_html .= '      </tr>';

          $w_qtd_geral += $w_resumo[$w_atual]['qtd'];
          $w_aquisicao_geral += $w_resumo[$w_atual]['aquisicao'];
          $w_periodo_geral += $w_resumo[$w_atual]['periodo'];
          $w_acumulado_geral += $w_resumo[$w_atual]['acumulado'];
          $w_atual_geral += $w_resumo[$w_atual]['atual'];
        }
        $colspan = 0;
        depreciacao_cab($w_tipo, $l_html, $colspan, f($row,'nm_tipo_material'));
        $w_atual = f($row,'nm_tipo_material');
        $w_resumo[$w_atual]['qtd'] = 0;
        $w_resumo[$w_atual]['aquisicao'] = 0;
        $w_resumo[$w_atual]['periodo'] = 0;
        $w_resumo[$w_atual]['acumulado'] = 0;
        $w_resumo[$w_atual]['atual'] = 0;
      }
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $l_html .= '      <tr valign="top">';
      //$l_html .= '        <td title="'.f($row,'nm_projeto').'">'.nvl(f($row,'cd_projeto'),'---').'</td>';
      $l_html .= '        <td align="center"'.((nvl(f($row,'observacao'),'')!='') ? ' title="'.  CRLF2BR(f($row,'observacao')).'"' : '').'>'.f($row,'numero_rgp').'</td>';
      $l_html .= '        <td>'.ExibePermanente($w_dir_volta,$w_cliente,f($row,'nome_completo').((nvl(f($row,'descricao_complementar'),'')!='') ? ' '.f($row,'descricao_complementar') : ''),f($row,'chave'),$TP,null,null,$w_tipo).'</td>';
      //$l_html .= '        <td>'.f($row,'descricao_complementar').'</td>';
      if (!$p_almoxarifado) $l_html .= '        <td nowrap>'.f($row,'nm_almoxarifado').'</td>';
      //if (!$p_unidade) $l_html .= '        <td>'.nvl(f($row,'nm_unidade'),'---').'</td>';
      if (!$p_endereco) $l_html .= '        <td>'.nvl(f($row,'logradouro'),'---').'</td>';
      if (!$p_localizacao) $l_html .= '        <td nowrap>'.nvl(f($row,'nm_localizacao'),'---').'</td>';
      $l_html .= '        <td align="center">'.formataDataEdicao(f($row,'data_tombamento'),5).'</td>';
      $l_html .= '        <td align="center">'.f($row,'vida_util').'</td>';
      $l_html .= '        <td align="right">'.nvl(formatNumber(f($row,'vl_atual_brl'),2),'---').'</td>';
      /*
      $l_html .= '        <td align="right">'.nvl(formatNumber(f($row,'vl_atual_usd'),2),'---').'</td>';
      $l_html .= '        <td align="right">'.nvl(formatNumber(f($row,'vl_atual_eur'),2),'---').'</td>';
      */
      $l_html .= '        <td align="right">'.nvl(formatNumber(f($row,'vl_depreciado_brl_periodo'),2),'---').'</td>'; // Deprecia��o mensal
      $l_html .= '        <td align="right">'.nvl(formatNumber(f($row,'vl_depreciado_brl'),2),'---').'</td>'; // Deprecia��o acumulada
      $l_html .= '        <td align="right">'.nvl(formatNumber((f($row,'vl_atual_brl') - f($row,'vl_depreciado_brl')),2),'---').'</td>';
      /*
      $l_html .= '        <td align="right">'.nvl(formatNumber(f($row,'vl_depreciado_usd'),2),'---').'</td>';
      $l_html .= '        <td align="right">'.nvl(formatNumber(f($row,'vl_depreciado_eur'),2),'---').'</td>';
      */
      
      // Acumula valores
      $w_resumo[$w_atual]['qtd']++;
      $w_resumo[$w_atual]['aquisicao'] += f($row,'vl_atual_brl');
      $w_resumo[$w_atual]['periodo'] += f($row,'vl_depreciado_brl_periodo');
      $w_resumo[$w_atual]['acumulado'] += f($row,'vl_depreciado_brl');
      $w_resumo[$w_atual]['atual'] += (f($row,'vl_atual_brl') - f($row,'vl_depreciado_brl'));
    }

    if ($w_atual != '*0*') {
      // Linha de totais do �ltimo tipo de material
      $l_html .= '      <tr valign="top">';
      $l_html .= '        <td align="right" colspan='.($colspan-4).'><b> Total '.$w_atual.' ('.formatNumber($w_resumo[$w_atual]['qtd'],0).' bens)</b></td>';
      $l_html .= '        <td align="right"><b>'.formatNumber($w_resumo[$w_atual]['aquisicao']).'</b></td>';
      $l_html .= '        <td align="right"><b>'.formatNumber($w_resumo[$w_atual]['periodo']).'</b></td>';
      $l_html .= '        <td align="right"><b>'.formatNumber($w_resumo[$w_atual]['acumulado']).'</b></td>';
      $l_html .= '        <td align="right"><b>'.formatNumber($w_resumo[$w_atual]['atual']).'</b></td>';
      $l_html .= '      </tr>';

      $w_qtd_geral += $w_resumo[$w_atual]['qtd'];
      $w_aquisicao_geral += $w_resumo[$w_atual]['aquisicao'];
      $w_periodo_geral += $w_resumo[$w_atual]['periodo'];
      $w_acumulado_geral += $w_resumo[$w_atual]['acumulado'];
      $w_atual_geral += $w_resumo[$w_atual]['atual'];
    }

    // Linha de totais gerais da emiss�o
    $l_html .= '      <tr valign="middle" style="height: 40px;">';
    $l_html .= '        <td align="right" colspan='.($colspan-4).'><b> TOTAL GERAL ('.formatNumber($w_qtd_geral,0).' BENS)</b></td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_aquisicao_geral).'</b></td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_periodo_geral).'</b></td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_acumulado_geral).'</b></td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_atual_geral).'</b></td>';
    $l_html .= '      </tr>';
    
  }

  $l_html .= '    </table></div>';
  $l_html .= '  </td>';
  $l_html .= '</tr>';

  if ($w_atual != '*0*') {
    // Quadro resumo
    $l_html .= '<tr style="height: 40px;"><td colspan=3>&nbsp;</td></tr>';
    $l_html .= '<tr><td align="center" colspan=3><div align="center">';
    $l_html .= '    <TABLE class="tudo" border="1" bordercolor="#00000">';
    $l_html .= '      <tr style="height: 40px;">';
    $l_html .= '        <td align="center" colspan=6><b>RESUMO</b></td>';
    $l_html .= '      </tr>';
    $l_html .= '      <tr align="center">';
    $l_html .= '        <td rowspan="2"><b>Grupo</b></td>';
    $l_html .= '        <td rowspan="2"><b>Bens</b></td>';
    $l_html .= '        <td rowspan="2"><b>Valor<br>Aquisi��o (BRL)</b></td>';
    $l_html .= '        <td colspan="2"><b>Deprecia��o(BRL)</b></td>';
    $l_html .= '        <td rowspan="2"><b>Valor em<br>'.str_replace('/20','/',$p_fim).' (BRL)</b></td>';
    $l_html .= '      </tr>';
    $l_html .= '      <tr align="center">';
    $l_html .= '        <td><b>'.str_replace('/20','/',$p_inicio).' a<br>'.str_replace('/20','/',$p_fim).'</b></td>';
    $l_html .= '        <td><b>Acumulada</b></td>';
    $l_html .= '      </tr>';
    foreach($w_resumo as $k => $v) {
      $l_html .= '      <tr valign="top">';
      $l_html .= '        <td>'.$k.'</td>';
      $l_html .= '        <td align="right">'.formatNumber($v['qtd'],0).'</td>';
      $l_html .= '        <td align="right">'.formatNumber($v['aquisicao']).'</td>';
      $l_html .= '        <td align="right">'.formatNumber($v['periodo']).'</td>';
      $l_html .= '        <td align="right">'.formatNumber($v['acumulado']).'</td>';
      $l_html .= '        <td align="right">'.formatNumber($v['atual']).'</td>';
      $l_html .= '      </tr>';
    }
    $l_html .= '      <tr valign="top">';
    $l_html .= '        <td><b>TOTAIS</td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_qtd_geral,0).'</b></td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_aquisicao_geral).'</b></td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_periodo_geral).'</b></td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_acumulado_geral).'</b></td>';
    $l_html .= '        <td align="right"><b>'.formatNumber($w_atual_geral).'</b></td>';
    $l_html .= '      </tr>';
    $l_html .= '    </table></div>';
  }

  ShowHTML($l_html);
  
  ShowHTML('</table>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
} 

function depreciacao_cab($w_tipo, &$l_html, &$colspan, $titulo) {
  extract($GLOBALS);
  $texto = '';
  
  $texto .= '        <tr align="center">';
  //$colspan++; $texto .= '          <td rowspan=2><b>Projeto</td>';
  $colspan++; $texto .= '          <td rowspan=2><b>RGP</td>';
  $colspan++; $texto .= '          <td rowspan=2><b>Bem</td>';
  //$colspan++; $texto .= '          <td rowspan=2><b>Detalhamento</td>';
  if (!$p_almoxarifado) { $colspan++; $texto .= '          <td rowspan=2><b>Almoxarifado</td>'; }
  //if (!$p_unidade) { $colspan++; $texto .= '          <td rowspan=2><b>Unidade</td>'; }
  if (!$p_endereco) { $colspan++; $texto .= '          <td rowspan=2><b>Endere�o</td>'; }
  if (!$p_localizacao) { $colspan++; $texto .= '          <td rowspan=2><b>Localiza��o</td>'; }
  $colspan++; $texto .= '          <td rowspan=2><b>Tombamento</td>';
  $colspan++; $texto .= '          <td rowspan=2><b>Vida �til (Anos)</td>';
  //$texto .= '          <td colspan=3><b>Valor Aquisi��o</b></td>';
  $colspan++; $texto .= '          <td rowspan=2><b>Valor Aquisi��o (BRL)</b></td>';
  //$texto .= '          <td colspan=3><b>Valor Depreciado</b></td>';
  $texto .= '          <td colspan=2><b>Deprecia��o (BRL)</b></td>';     
  $colspan++; $texto .= '          <td rowspan=2><b>Valor em '.str_replace('/20','/',$p_fim).' (BRL)</b></td>';
  $texto .= '        </tr>';
  $texto .= '        <tr align="center">';
  //$colspan++; $texto .= '          <td><b>BRL</b></td>';
  //$colspan++; $texto .= '          <td><b>USD</b></td>';
  //$colspan++; $texto .= '          <td><b>EUR</b></td>';
  //$colspan++; $texto .= '          <td><b>Mensal</b></td>';
  $colspan++; $texto .= '          <td nowrap><b>'.str_replace('/20','/',$p_inicio).' a<br>'.str_replace('/20','/',$p_fim).'</b></td>';
  $colspan++; $texto .= '          <td><b>Acumulada</b></td>'; 
  //$colspan++; $texto .= '          <td><b>BRL</b></td>';
  //$colspan++; $texto .= '          <td><b>USD</b></td>';
  //$colspan++; $texto .= '          <td><b>EUR</b></td>';

  $l_html .= '      <tr valign="middle" style="height: 40px;" bgcolor="'.$w_cor.'">';
  $l_html .= '        <td colspan='.$colspan.' align="center"><b>'.$titulo.'<b></td>';
  $l_html .= $texto;

  $l_html .= '        </tr>';
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':            Inicial();           break;
    case 'DEPRECIACAO':        Depreciacao();       break;
    default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exibevariaveis();
  break;
  } 
} 
?>