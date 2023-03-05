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
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
include_once($w_dir_volta.'classes/sp/db_getCronograma.php');
include_once($w_dir_volta.'funcoes/selecaoNumero.php');
include_once($w_dir_volta.'funcoes/selecaoOrdenaRel.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');

// =========================================================================
//  /rel_projeto_pnuma.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório de acompanhamento da execução orçamentário-financeira de projetos PNUMA
// Mail     : alex@sbpi.com.br
// Criacao  : 02/03/2023 17:17
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
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par = upper($_REQUEST['par']);
$P1 = nvl($_REQUEST['P1'], 0);
$P2 = nvl($_REQUEST['P2'], 0);
$P3 = nvl($_REQUEST['P3'], 1);
$P4 = nvl($_REQUEST['P4'], $conPageSize);
$TP = $_REQUEST['TP'];
$SG = upper($_REQUEST['SG']);
$R = $_REQUEST['R'];
$O = upper($_REQUEST['O']);
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina = 'rel_projeto_pnuma.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_fn/';
$w_troca = $_REQUEST['w_troca'];
$w_embed = '';

$p_projeto = $_REQUEST['p_projeto'];
$p_ano = $_REQUEST['p_ano'];
$p_trimestre = $_REQUEST['p_trimestre'];
$p_emissao = trim($_REQUEST['p_emissao']);
$p_nome_revisor = trim($_REQUEST['p_nome_revisor']);
$p_cargo_revisor = trim($_REQUEST['p_cargo_revisor']);
$p_nome_responsavel = trim($_REQUEST['p_nome_responsavel']);
$p_cargo_responsavel = trim($_REQUEST['p_cargo_responsavel']);

// Início e fim de cada trimestre
$w_trimestre[1]['I'] = '01/01/'.$p_ano; $w_trimestre[1]['F'] = '31/03/'.$p_ano;
$w_trimestre[2]['I'] = '01/04/'.$p_ano; $w_trimestre[2]['F'] = '30/06/'.$p_ano;
$w_trimestre[3]['I'] = '01/07/'.$p_ano; $w_trimestre[3]['F'] = '30/09/'.$p_ano;
$w_trimestre[4]['I'] = '01/10/'.$p_ano; $w_trimestre[4]['F'] = '31/12/'.$p_ano;

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O == '') {
  if ($par == 'INICIAL') {
    $O = 'P';
  } else {
    $O = 'L';
  }
}
switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem'; break;
  default: $w_TP = $TP . ' - Listagem';   break;
}
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relatório de execução orçamentário-financeira de projeto.
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  global $w_embed;
  $w_tipo = $_REQUEST['w_tipo'];
  $w_sq_pessoa = upper(trim($_REQUEST['w_sq_pessoa']));

  headerGeral('P', $w_tipo, $w_chave, 'Consulta de '.f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Relatório</TITLE>');
    if ($O == 'P') {
      ScriptOpen('JavaScript');
      CheckBranco();
      FormataData();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_projeto', 'Projeto', 'SELECT', '1', '1', '18', '', '0123456789');
      Validate('p_ano', 'Ano de referência', 'SELECT', '', '4', '4', '', '0123456789');
      Validate('p_trimestre', 'Trimestre de referência', '', '', '1', '1', '', '1234');
      Validate('p_emissao', 'Data de emissão', null, '', '8', '10', '', '0123456789/-');
      Validate('p_nome_revisor', 'Nome do "Official of Executing Division"', '', '', '1', '50', '1', '');
      Validate('p_cargo_revisor', 'Cargo/Função do "Official of Executing Division"', '', '', '1', '50', '1', '');
      Validate('p_nome_responsavel', 'Nome do "ACTO Financial"', '', '', '1', '50', '1', '');
      Validate('p_cargo_responsavel', 'Cargo/Função do "ACTO Financial"', '', '', '1', '50', '1', '');
      ValidateClose();
      ScriptClose();
    }
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    if ($O == 'L') {
      BodyOpenClean('onLoad="this.focus()";');
      CabecalhoRelatorio($w_cliente, 'Annex 6- QER & unliquidated obligations', 4, $w_chave, (($p_logo=='S') ? 'N' : 'S'), 'S');
    } else {
      BodyOpen('onLoad="document.focus()";');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
    }
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {

    $l_html = '';
    $l_html .= chr(13).'<table border="1" cellpadding="2" cellspacing="0" width="99%">';
    
    // -------------------------------------------------
    // Exibe bloco de valores por rubrica do trimestre escolhido
    // Recupera os dados do projeto selecionado
    $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
    
    // Guarda o ano de início do projeto
    $w_ano_inicio_projeto = date('Y',f($RS_Projeto,'inicio'));
    
    // Recupera registros a serem exibidos
    $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,'N',$w_trimestre[$p_trimestre]['I'],$w_trimestre[$p_trimestre]['F'],'PJEXECN');
    
    // Recupera cronograma orçamentário do período informado
    $sql = new db_getCronograma; $RS = $sql->getInstanceOf($dbms,$p_projeto,null,$w_trimestre[1]['I'],$w_trimestre[4]['F'],null,'PERIODO');
    $RS = SortArray($RS,'codigo', 'asc');
    foreach($RS as $row) {
      $Cronograma[f($row,'sq_projeto_rubrica')][f($row,'sg_moeda')] = f($row,'valor_previsto');
    }
    
    // Recupera somente lançamentos concluídos do trimestre escolhido
    $sql = new db_getSolicRubrica; $RS1 = $sql->getInstanceOf($dbms,$p_projeto,null,null,null,null,null,$w_trimestre[$p_trimestre]['I'],$w_trimestre[$p_trimestre]['F'],'PJFINS');
    foreach($RS1 as $row)  {
      $Moeda[f($row,'sg_fn_moeda')]='1';
      $lista = explode(',',str_replace(' ',',',f($row,'lista')));      
      foreach($lista as $k => $v) {
        if (!isset($Valor[f($row,'sq_projeto_rubrica')][f($row,'sg_fn_moeda')])) {
          $Valor[$v][f($row,'sg_fn_moeda')] = f($row,'valor');
          $ValorAnosAnteriores[$v][f($row,'sg_fn_moeda')] = 0;
          $ValorTrimestresAnteriores[$v][f($row,'sg_fn_moeda')] = 0;
        } else {
          $Valor[$v][f($row,'sg_fn_moeda')]+= f($row,'valor');
        }
      }
    }

    // Recupera somente lançamentos em andamento do trimestre escolhido
    $sql = new db_getSolicRubrica; $RS1 = $sql->getInstanceOf($dbms,$p_projeto,null,null,null,null,null,$w_trimestre[$p_trimestre]['I'],$w_trimestre[$p_trimestre]['F'],'PJFINE');
    foreach($RS1 as $row)  {
      $Moeda[f($row,'sg_fn_moeda')]='1';
      $lista = explode(',',str_replace(' ',',',f($row,'lista')));      
      foreach($lista as $k => $v) {
        if (!isset($ValorAndamento[f($row,'sq_projeto_rubrica')][f($row,'sg_fn_moeda')])) {
          $ValorAndamento[$v][f($row,'sg_fn_moeda')] = f($row,'valor');
          $ValorAnosAnteriores[$v][f($row,'sg_fn_moeda')] = 0;
          $ValorTrimestresAnteriores[$v][f($row,'sg_fn_moeda')] = 0;
        } else {
          $ValorAndamento[$v][f($row,'sg_fn_moeda')]+= f($row,'valor');
        }
      }
    }

    if ($w_ano_inicio_projeto<>$p_ano) {
      $w_inicio = '01/01/'.$w_ano_inicio_projeto;
      $w_fim = '31/12/'.($p_ano-1);

      // Recupera valores dos anos anteriores se não foi escolhido o primeiro ano do projeto
      if ($p_ano <> formataDataEdicao(f($RS_Solic,'inicio'))) {
        $sql = new db_getSolicRubrica; $RS1 = $sql->getInstanceOf($dbms,$p_projeto,null,null,null,null,null,$w_inicio,$w_fim,'PJFINS');
        foreach($RS1 as $row)  {
          $lista = explode(',',str_replace(' ',',',f($row,'lista')));      
          foreach($lista as $k => $v) {
            if (!isset($ValorAnosAnteriores[f($row,'sq_projeto_rubrica')][f($row,'sg_fn_moeda')])) {
              $ValorAnosAnteriores[$v][f($row,'sg_fn_moeda')] = f($row,'valor');
            } else {
              $ValorAnosAnteriores[$v][f($row,'sg_fn_moeda')]+= f($row,'valor');
            }
          }
        }
      }
    }
    
    if ($p_trimestre > 1) {
      $w_inicio = '01/01/'.$p_ano;
      $w_fim = $w_trimestre[$p_trimestre-1]['F'];
      $sql = new db_getSolicRubrica; $RS1 = $sql->getInstanceOf($dbms,$p_projeto,null,null,null,null,null,$w_inicio,$w_fim,'PJFINS');
      foreach($RS1 as $row)  {
        $lista = explode(',',str_replace(' ',',',f($row,'lista')));      
        foreach($lista as $k => $v) {
          if (!isset($ValorTrimestresAnteriores[f($row,'sq_projeto_rubrica')][f($row,'sg_fn_moeda')])) {
            $ValorTrimestresAnteriores[$v][f($row,'sg_fn_moeda')] = f($row,'valor');
          } else {
            $ValorTrimestresAnteriores[$v][f($row,'sg_fn_moeda')]+= f($row,'valor');
          }
        }
      }
    }
    
    // Decide a ordem de exibição das moedas no relatório
    $i = 0;
    switch (f($RS_Projeto,'sg_moeda')) {
      case 'USD': $Moeda['USD']='1'; $Ordem[$i]='USD';
                  if (nvl($Moeda['BRL'],'')!='') $Ordem[++$i]='BRL';
                  if (nvl($Moeda['EUR'],'')!='') $Ordem[++$i]='EUR';
                  break;
      case 'BRL': $Moeda['BRL']='1'; $Ordem[$i]='BRL';
                  if (nvl($Moeda['USD'],'')!='') $Ordem[++$i]='USD';
                  if (nvl($Moeda['EUR'],'')!='') $Ordem[++$i]='EUR';
                  break;
      case 'EUR': $Moeda['EUR']='1'; $Ordem[$i]='EUR';
                  if (nvl($Moeda['BRL'],'')!='') $Ordem[++$i]='BRL';
                  if (nvl($Moeda['USD'],'')!='') $Ordem[++$i]='USD';
                  break;
    }
    // Ordena somente após o laço acima pois não há necessidade dele estar ordenado
    $RSQuery = SortArray($RSQuery,'ordena','asc');

    $l_html .= chr(13).'      <tr align="center">';
    $l_html .= chr(13).'          <td colspan="12">Annex 6 - QUARTERLY EXPENDITURE STATEMENT and UNLIQUIDATED OBLIGATIONS REPORT (US$)*</td>';
    $l_html .= chr(13).'      </tr>';
    $l_html .= chr(13).'      <tr>';
    $l_html .= chr(13).'          <td colspan="2">Project title:</td>';
    $l_html .= chr(13).'          <td colspan="10">'.f($RS_Projeto,'titulo').'</td>';
    $l_html .= chr(13).'      </tr>';
    $l_html .= chr(13).'      <tr>';
    $l_html .= chr(13).'          <td colspan="2">Project number:</td>';
    $l_html .= chr(13).'          <td colspan="10">'.f($RS_Projeto,'palavra_chave').'</td>';
    $l_html .= chr(13).'      </tr>';
    $l_html .= chr(13).'      <tr>';
    $l_html .= chr(13).'          <td colspan="2">Project implementing agency/organization:</td>';
    $l_html .= chr(13).'          <td colspan="10">The Amazon Cooperation Treaty Organisation (ACTO)</td>';
    $l_html .= chr(13).'      </tr>';
    $l_html .= chr(13).'      <tr align="center">';
    $l_html .= chr(13).'          <td colspan="2" align="left">Project implementation period:</td>';
    $l_html .= chr(13).'          <td>From:</td><td colspan="4">'.FormataDataEdicao(f($RS_Projeto,'inicio'),11).'</td>';
    $l_html .= chr(13).'          <td>To:</td><td colspan="4">'.FormataDataEdicao(f($RS_Projeto,'fim'),11).'</td>';
    $l_html .= chr(13).'      </tr>';
    $l_html .= chr(13).'      <tr align="center">';
    $l_html .= chr(13).'          <td colspan="2" align="left">Reporting period:</td>';
    $l_html .= chr(13).'          <td>From:</td><td colspan="4">'.FormataDataEdicao(toDate($w_trimestre[$p_trimestre]['I']),11).'</td>';
    $l_html .= chr(13).'          <td>To:</td><td colspan="4">'.FormataDataEdicao(toDate($w_trimestre[$p_trimestre]['F']),11).'</td>';
    $l_html .= chr(13).'      </tr>';
    $l_html .= chr(13).'      <tr align="center">';
    $l_html .= chr(13).'          <td rowspan="3" colspan="2" align="left"><b>UNEP Budget Line</b></td>';
    $l_html .= chr(13).'          <td colspan="2">UNEP approved budget</td>';
    $l_html .= chr(13).'          <td colspan="7">Actual expenditures incurred</td>';
    $l_html .= chr(13).'          <td rowspan="2" width="1%">Cummulative unspent balance to-date</td>';
    $l_html .= chr(13).'      </tr>';
    $l_html .= chr(13).'      <tr align="center">';
    $l_html .= chr(13).'          <td width="1%">Total project budget</td>';
    $l_html .= chr(13).'          <td width="1%">Current YEAR budget</td>';
    $l_html .= chr(13).'          <td width="1%">Cummulative expenditures for current YEAR</td>';
    $l_html .= chr(13).'          <td width="1%">Disbursements for current QUARTER</td>';
    $l_html .= chr(13).'          <td width="1%">Unliquidated obligations for current QUARTER</td>';
    $l_html .= chr(13).'          <td width="1%">Total expenditures for current QUARTER</td>';
    $l_html .= chr(13).'          <td width="1%">Total expenditures for current YEAR</td>';
    $l_html .= chr(13).'          <td width="1%">Cummulative expenditures for previous YEARS</td>';
    $l_html .= chr(13).'          <td width="1%">Total cummulative expenditures to date</td>';
    $l_html .= chr(13).'      </tr>';
    $l_html .= chr(13).'      <tr align="center">';
    $l_html .= chr(13).'          <td>A</td>';
    $l_html .= chr(13).'          <td>B</td>';
    $l_html .= chr(13).'          <td>C</td>';
    $l_html .= chr(13).'          <td>D</td>';
    $l_html .= chr(13).'          <td>E</td>';
    $l_html .= chr(13).'          <td>F=D+E</td>';
    $l_html .= chr(13).'          <td>G=C+F</td>';
    $l_html .= chr(13).'          <td>H</td>';
    $l_html .= chr(13).'          <td>I=G+H</td>';
    $l_html .= chr(13).'          <td>J=A-I</td>';
    $l_html .= chr(13).'      </tr>';

    $w_cor=$conTrBgColor;
    unset($valor_coluna);
    unset($total_coluna);
    for ($i=0;$i<10;$i++) { $valor_coluna[$i] = 0.00; $total_coluna[$i] = 0.00; }
    
    foreach ($RSQuery as $row) {
      // Configura variável que decide se os valores serão impressos
      if (f($row,'ultimo_nivel')=='S') $w_imprime = true; else $w_imprime = false;
      $w_folha = ((f($row,'ultimo_nivel')=='N') ? ' class="folha"' : '');

      $l_html.=chr(13).'      <tr '.$w_folha.'>';
      $l_html.=chr(13).'          <td align="center">'.f($row,'codigo');
      $l_html.=chr(13).'          <td>'.f($row,'descricao').'</td>';
      
      // Imprime valores das rubricas de último nível
      if ($w_imprime) {
        // A - Total project budget
        $valor_coluna[0] = f($row,'total_previsto');
        
        // B - Current YEAR budget	
        $valor_coluna[1] = nvl($Cronograma[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0); 
        
        // C - Cummulative expenditures for current YEAR	
        $valor_coluna[2] = nvl($ValorTrimestresAnteriores[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0); 
        
        // D - Disbursements for current QUARTER	
        $valor_coluna[3] = nvl($Valor[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0); 
        
        // E - Unliquidated obligations for current QUARTER	
        $valor_coluna[4] = nvl($ValorAndamento[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0); 
        
        // F=D+E - Total expenditures for current QUARTER	
        $valor_coluna[5] = $valor_coluna[3]+$valor_coluna[4]; 
        
        // G=C+F - Total expenditures for current YEAR	
        $valor_coluna[6] = $valor_coluna[2]+$valor_coluna[5]; 
        
        // H	- Cummulative expenditures for previous YEARS	
        $valor_coluna[7] = nvl($ValorAnosAnteriores[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0); 
        
        // I=G+H - Total cummulative expenditures to date
        $valor_coluna[8] = $valor_coluna[6]+$valor_coluna[7]; 
        
        // J=A-I	- Cummulative unspent balance to-date
        $valor_coluna[9] = $valor_coluna[0]-$valor_coluna[8]; 
        
        // Acumula nos totalizadores e exibe valores da linha
        for ($i=0;$i<10;$i++) $total_coluna[$i] += $valor_coluna[$i];
        for ($i=0;$i<10;$i++) $l_html.=chr(13).'          <td align="right">'.formatNumber($valor_coluna[$i]).'</td>';
      } else {
        // Rubricas de mais alto nível não têm valores exibidos
        $l_html .= chr(13).'          <td align="center">&nbsp</td>';
        $l_html .= chr(13).'          <td align="center">&nbsp</td>';
        $l_html .= chr(13).'          <td align="center">&nbsp</td>';
        $l_html .= chr(13).'          <td align="center">&nbsp</td>';
        $l_html .= chr(13).'          <td align="center">&nbsp</td>';
        $l_html .= chr(13).'          <td align="center">-</td>';
        $l_html .= chr(13).'          <td align="center">-</td>';
        $l_html .= chr(13).'          <td align="center">&nbsp</td>';
        $l_html .= chr(13).'          <td align="center">-</td>';
        $l_html .= chr(13).'          <td align="center">-</td>';
      }
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'          <tr class="folha" align="right">';
    $l_html.=chr(13).'            <td>&nbsp;</td>';
    $l_html.=chr(13).'            <td align="left"><b>GRAND TOTAL</b></td>';
    for ($i=0;$i<10;$i++) $l_html.=chr(13).'          <td align="right">'.formatNumber($total_coluna[$i]).'</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr><td colspan="12">*Annex 13 is due within 15 days of the end of the quarter to which they refer, i.e., on or before 15 April, 15 July, 15 October and 15 January, the Executing Agency shall submit to UNEP quarterly expenditure reports and explanatory notes on the expenditures</td></tr>';
    $l_html.=chr(13).'          <tr><td colspan="12">&nbsp;</td></tr>';
    

    
    // -------------------------------------------------
    // Exibe bloco de lançamentos financeiros do trimestre escolhido
    $l_html.=chr(13).'          <tr><td colspan="12">The appended schedule "Explanation for expenditures reported in quarterly expenditure statement" should also be completed</td></tr>';
    $l_html.=chr(13).'          <tr align="center"><td colspan="12"><b>EXPLANATION FOR EXPENDITURES REPORTED IN QUARTERLY EXPENDITURE STATEMENT</b></td></tr>';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td>From:</td><td>'.FormataDataEdicao(toDate($w_trimestre[$p_trimestre]['I']),11).'</td>';
    $l_html.=chr(13).'            <td rowspan="3">Total expenditure for QUARTER</td>';
    $l_html.=chr(13).'            <td rowspan="3" colspan="9">EXPLANATION</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td>To:</td><td>'.FormataDataEdicao(toDate($w_trimestre[$p_trimestre]['F']),11).'</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr>';
    $l_html.=chr(13).'            <td>BL**</td>';
    $l_html.=chr(13).'            <td>Budget Line description</td>';
    $l_html.=chr(13).'          </tr>';
    
    $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,(($p_receita=='S') ? null : 'N'),$w_trimestre[$p_trimestre]['I'],$w_trimestre[$p_trimestre]['F'],'PJEXECLN');

    foreach($RSQuery as $row)  {
      if (f($row,'sg_fn_moeda')!='0')         { $Moeda[f($row,'sg_fn_moeda')]='1'; $Total[f($row,'sg_fn_moeda')] = 0; }
      if (nvl(f($row,'fn_sg_moeda'),'')!='')  { $Moeda[f($row,'fn_sg_moeda')]='1'; $Total[f($row,'fn_sg_moeda')] = 0; }
      // Se o relatório tem três moedas diferentes, aborta pois esse é o número atual de moedas ativas
      if (count($Moeda)==3) break;
    }

    // Decide a ordem de exibição das moedas no relatório
    $i = 0;
    switch (f($RS_Projeto,'sg_moeda')) {
      case 'USD': $Moeda['USD']='1'; $Ordem[$i]='USD';
                  if (nvl($Moeda['BRL'],'')!='') $Ordem[++$i]='BRL';
                  if (nvl($Moeda['EUR'],'')!='') $Ordem[++$i]='EUR';
                  break;
      case 'BRL': $Moeda['BRL']='1'; $Ordem[$i]='BRL';
                  if (nvl($Moeda['USD'],'')!='') $Ordem[++$i]='USD';
                  if (nvl($Moeda['EUR'],'')!='') $Ordem[++$i]='EUR';
                  break;
      case 'EUR': $Moeda['EUR']='1'; $Ordem[$i]='EUR';
                  if (nvl($Moeda['BRL'],'')!='') $Ordem[++$i]='BRL';
                  if (nvl($Moeda['USD'],'')!='') $Ordem[++$i]='USD';
                  break;
    }
    $RSQuery = SortArray($RSQuery,'or_rubrica','asc','quitacao','asc','or_financeiro','asc','or_item','asc');
    $w_total = 0;
    foreach ($RSQuery as $row) {
      $l_html.=chr(13).'      <tr'.((f($row,'sg_fn_moeda')<>f($RS_Projeto,'sg_moeda')) ? ' bgcolor="yellow"' : '').'>';
      $l_html.=chr(13).'          <td align="center">'.f($row,'cd_rubrica').'</td>';
      $l_html.=chr(13).'          <td>'.f($row,'nm_rubrica').'</td>';
      $l_html.=chr(13).'          <td align="right" nowrap>'.
               ((f($row,'sg_fn_moeda')<>f($RS_Projeto,'sg_moeda')) ? f($row,'sg_fn_moeda').' ' : '').
               formatNumber(f($row,'valor')).'</td>';
      $l_html.=chr(13).'          <td colspan="9">'.f($row,'descricao').' - '.f($row,'nm_pessoa').'</td>';
      $l_html.=chr(13).'      </tr>';
      if (f($row,'sg_fn_moeda')==f($RS_Projeto,'sg_moeda')) $w_total += f($row,'valor');
    }
    $l_html.=chr(13).'          <tr>';
    $l_html.=chr(13).'            <td align="center"><b>99</b></td>';
    $l_html.=chr(13).'            <td><b>Total as per Expenditure Statement</b></td>';
    $l_html.=chr(13).'            <td align="right" nowrap><b>'.formatNumber($w_total).'</b></td>';
    $l_html.=chr(13).'            <td colspan="9"><b>equals total of column F</b></td>';
    $l_html.=chr(13).'          </tr>';
    
    // -------------------------------------------------
    // Exibe bloco de assinaturas
    $l_html.=chr(13).'          <tr>';
    $l_html.=chr(13).'            <td rowspan="6" colspan="6" align="center">';
    $l_html.=chr(13).'              <div align="left"><blockquote>Brasília/DF, '.$p_emissao.'</blockquote></div>';
    $l_html.=chr(13).'              <p><br><br><br><br><br>'.$p_nome_revisor;
    $l_html.=chr(13).'              <br>'.$p_cargo_revisor;
    $l_html.=chr(13).'              <br>Duly authorized official of Executing Division</p>';
    $l_html.=chr(13).'            </td>';
    $l_html.=chr(13).'            <td rowspan="6" colspan="6" align="center">';
    $l_html.=chr(13).'              <div align="left"><blockquote>Brasília/DF, '.$p_emissao.'</blockquote></div>';
    $l_html.=chr(13).'              <p><br><br><br><br><br>'.$p_nome_responsavel;
    $l_html.=chr(13).'              <br>'.$p_cargo_responsavel;
    $l_html.=chr(13).'              <br>ACTO Financial</p>';
    $l_html.=chr(13).'            </td>';
    $l_html.=chr(13).'          </tr>';
    
    
    $l_html.=chr(13).'        </table></td></tr>';

    ShowHTML($l_html);
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', 'execucao', $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto do contrato na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_ano\'; document.Form.submit();"',null,2);
    ShowHTML('      </tr>');

    $w_ano_inicio = date('Y',time());
    $w_ano_fim = date('Y',time());
    if ($p_projeto>"") {
      // Recupera todos os dados do projeto e rubrica
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_ano_inicio = date('Y',f($RS,'inicio'));
      $w_ano_fim    = date('Y',f($RS,'fim'));
    }
    
    ShowHTML('      <tr valign="top">');
    SelecaoNumero('<u>A</u>no:','A','Selecione o ano para o relatório.',$p_ano,null,'p_ano',null,null,$w_ano_inicio,$w_ano_fim);
    SelecaoNumero('<u>T</u>rimestre:','T','Selecione o trimestre para o relatório.',$p_trimestre,null,'p_trimestre',null,null,1,4);
    ShowHTML('      <tr><td><b><u>D</u>ata do relatório:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="p_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_emissao . '" onKeyDown="FormataData(this,event);"></td>');
    
    ShowHTML('      <tr><td colspan="2"><b>Official of Executing Division</b></td></tr>');
    ShowHTML('      <tr>');
    ShowHTML('        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>N</u>ome:&nbsp;<input ' . $w_Disabled . ' accesskey="N" type="text" name="p_nome_revisor" class="sti" SIZE="35" MAXLENGTH="50" VALUE="' . $p_nome_revisor . '"></td>');
    ShowHTML('        <td><u>C</u>argo: <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_cargo_revisor" class="sti" SIZE="35" MAXLENGTH="50" VALUE="' . $p_cargo_revisor . '"></td>');
    ShowHTML('      </tr>');
    
    ShowHTML('      <tr><td colspan="2"><b>ACTO Financial</b></td></tr>');
    ShowHTML('      <tr>');
    ShowHTML('        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>N</u>ome:&nbsp;<input ' . $w_Disabled . ' accesskey="N" type="text" name="p_nome_responsavel" class="sti" SIZE="35" MAXLENGTH="50" VALUE="' . $p_nome_responsavel . '"></td>');
    ShowHTML('        <td><u>C</u>argo: <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_cargo_responsavel" class="sti" SIZE="35" MAXLENGTH="50" VALUE="' . $p_cargo_responsavel . '"></td>');
    ShowHTML('      </tr>');
    
    ShowHTML('      <tr><td align="center" colspan="2"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </td>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</div>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
}

// =========================================================================
// Rotina de detalhamento financeiro de uma rubrica
// -------------------------------------------------------------------------
function Detalhe() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_edita      = nvl($_REQUEST['w_edita'],'S');

  // Recupera todos os dados do projeto e rubrica
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave_pai,'PJGERAL');
  $w_inicio_projeto = formataDataEdicao(f($RS_Solic,'inicio'));
  $w_fim_projeto    = formataDataEdicao(f($RS_Solic,'fim'));
  $w_projeto  = nvl(f($RS_Solic,'codigo_interno'),$w_chave_pai).' - '.f($RS_Solic,'titulo').' ('.$w_inicio_projeto.' - '.$w_fim_projeto.')';
  $w_valor_projeto = f($RS_Solic,'valor');
  
  // Recupera os dados da rubrica informada
  $sql = new db_getSolicRubrica; $RS_Rubrica = $sql->getInstanceOf($dbms,$w_chave_pai,$w_chave,null,null,null,null,null,null,null);
  foreach($RS_Rubrica as $row) { $RS_Rubrica = $row; break; }

  // Recupera os lançamentos da rubrica
  $sql = new db_getSolicRubrica; $RS = $sql->getInstanceOf($dbms,$w_chave_pai,$w_chave,null,null,null,null,$w_trimestre[$p_trimestre]['I'],$w_trimestre[$p_trimestre]['F'],'PJEXECLN');

  cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Relatório</TITLE>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</HEAD>');
  BodyOpenClean('onLoad=\'this.focus()\';'); 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<hr/>');
  ShowHTML('<div align=center>');
  ShowHTML('<tr><td colspan="2"><table border="0" width="100%">');
  ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>');
  ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify>Projeto:<b> '.$w_projeto.'</b></div></td></tr>');
  ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify>Rubrica:<b> '.f($RS_Rubrica,'codigo').' - '.f($RS_Rubrica,'nome').' </b></div></td></tr>');
  ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>');

  $w_filtro = '';
  if ($w_inicio_periodo!='')    $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Pagamento realizado de <td><b>' . $w_inicio_periodo . '</b> até <b>' . $w_fim_periodo . '</b>';
  ShowHTML('<tr><td align="left" colspan=2>');
  if ($w_filtro > '') ShowHTML('<table border=0>' . $w_filtro . '</table>');

  ShowHTML('<tr><td><a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
  ShowHTML('        <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
  ShowHTML('<tr><td align="center" colspan=3>');
  ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  if ($w_tipo!='WORD') {
    ShowHTML('          <td><b>'.LinkOrdena('Rubrica','or_rubrica').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Lançamento','or_financeiro').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Descrição','descricao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
  } else {
    ShowHTML('          <td><b>Rubrica</b></td>');
    ShowHTML('          <td><b>Lançamento</b></td>');
    ShowHTML('          <td><b>Descrição</b></td>');
    ShowHTML('          <td><b>valor</b></td>');
  }
  ShowHTML('        </tr>');
  if (count($RS)==0) {
    // Se não foram selecionados registros, exibe mensagem
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
  } else {
    $RS = SortArray($RS,'or_rubrica','asc','or_financeiro','asc');
    unset($Total);
    foreach ($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td align="center">'.f($row,'cd_rubrica').'</td>');
      ShowHTML('        <td nowrap>');
      ShowHTML(ExibeImagemSolic(f($row,'sg_menu'),f($row,'inicio'),f($row,'vencimento'),f($row,'inicio'),f($row,'quitacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
      ShowHTML('        '.exibeSolic($w_dir,f($row,'sq_financeiro'),f($row,'cd_financeiro'),'N',$w_tipo).'</td>');
      ShowHTML('        <td>'.f($row,'descricao').'</td>');
      ShowHTML('        <td align="right" nowrap>'.f($row,'sb_moeda').' '.formatNumber(f($row,'valor')).'</td>');
      ShowHTML('      </tr>');
      $valor = f($row,'valor');
      
      // Tratamento retirado em 09/11/2018, a pedido do Márcio
      //if (strpos(f($row,'descricao'),'FCTS')!==false) $valor = abs($valor);
      
      if (nvl($Total[f($row,'sb_moeda')],'')=='') $Total[f($row,'sb_moeda')] = $valor; else $Total[f($row,'sb_moeda')] += $valor;
    } 
    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
    ShowHTML('        <td align="right" colspan="3"><b>Tota'.((count($Total)==1) ? 'l' : 'is').'&nbsp;</b></td>');
    ShowHTML('        <td align="right" nowrap><b>');
    $i = 0;
    ksort($Total);
    foreach($Total as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
    echo('</td>');
    ShowHTML('      </tr>');
  } 
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');

  ShowHTML('</table>');
  Rodape();
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL': Inicial(); break;
    case 'DETALHE': Detalhe(); break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
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
  }
}
?>
