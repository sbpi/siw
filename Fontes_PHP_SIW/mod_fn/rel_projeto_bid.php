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
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoOrdenaRel.php');

// =========================================================================
//  /rel_projeto_bid.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório de acompanhamento da execução orçamentário-financeira de um projeto
// Mail     : alex@sbpi.com.br
// Criacao  : 19/07/2016 10:43
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
$w_pagina = 'rel_projeto_bid.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_fn/';
$w_troca = $_REQUEST['w_troca'];
$w_embed = '';

$p_projeto = $_REQUEST['p_projeto'];
$p_inicio = $_REQUEST['p_inicio'];
$p_fim = $_REQUEST['p_fim'];
$p_nome = upper(trim($_REQUEST['p_nome']));
$p_sintetico = upper(trim($_REQUEST['p_sintetico']));
$p_financeiro = upper(trim($_REQUEST['p_financeiro']));
$p_concluido = $_REQUEST['p_concluido'];
$p_ordena = lower($_REQUEST['p_ordena']);

$p_logo    = trim($_REQUEST['p_logo']);
$p_numero  = trim($_REQUEST['p_numero']);
$p_nome    = trim($_REQUEST['p_nome']);
$p_cargo   = trim($_REQUEST['p_cargo']);
$p_emissao = trim($_REQUEST['p_emissao']);
  
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

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

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

  if ($O == 'L') {
    // Recupera os dados do projeto selecionado
    $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
    
    // Recupera as rubricas do projeto
    $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,(($p_financeiro=='N') ? null : 'N'),$p_inicio,$p_fim,'PJEXEC'.$p_concluido);

    $sql = new db_getSolicRubrica; $RS1 = $sql->getInstanceOf($dbms,$p_projeto,null,null,null,null,null,$p_inicio,$p_fim,'PJFIN'.$p_concluido);
    foreach($RS1 as $row)  {
      $Moeda[f($row,'sg_fn_moeda')]='1';
      if (f($row,'aplicacao_financeira')=='N') {
        if (!isset($Total[f($row,'sg_fn_moeda')])) {
          $Total[f($row,'sg_fn_moeda')] = f($row,'valor');
        } else {
          $Total[f($row,'sg_fn_moeda')]+=f($row,'valor');
        }
      }
      $lista = explode(',',str_replace(' ',',',f($row,'lista')));      
      foreach($lista as $k => $v) {
        if (!isset($Valor[f($row,'sq_projeto_rubrica')][f($row,'sg_fn_moeda')])) {
          $Valor[$v][f($row,'sg_fn_moeda')] = f($row,'valor');
        } else {
          $Valor[$v][f($row,'sg_fn_moeda')]+=f($row,'valor');
        }
      }
    }

    if (toDate($p_inicio)>f($RS_Projeto,'inicio')) {
      $p_fim_anterior = FormataDataEdicao(toDate($p_inicio)-(24*60*60));

      $sql = new db_getSolicRubrica; $RS2 = $sql->getInstanceOf($dbms,$p_projeto,null,null,null,null,null,FormataDataEdicao(f($RS_Projeto,'inicio')),$p_fim_anterior,'PJFIN'.$p_concluido);
      foreach($RS2 as $row)  {
        $Anterior[f($row,'sg_fn_moeda')]='1';
        if (f($row,'aplicacao_financeira')=='N') {
          if (!isset($TotAnterior[f($row,'sg_fn_moeda')])) {
            $TotAnterior[f($row,'sg_fn_moeda')] = f($row,'valor');
          } else {
            $TotAnterior[f($row,'sg_fn_moeda')]+=f($row,'valor');
          }
        }
        $lista = explode(',',str_replace(' ',',',f($row,'lista')));      
        foreach($lista as $k => $v) {
          if (!isset($Anterior[f($row,'sq_projeto_rubrica')][f($row,'sg_fn_moeda')])) {
            $Anterior[$v][f($row,'sg_fn_moeda')] = f($row,'valor');
          } else {
            $Anterior[$v][f($row,'sg_fn_moeda')]+=f($row,'valor');
          }
        }
      }
    } else {
      $TotAnterior = $Total;
      $Anterior = $Valor;
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
  }

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
      Validate('p_inicio', 'Pagamento inicial', 'DATA', '1', '10', '10', '', '0123456789/');
      Validate('p_fim', 'Pagamento final', 'DATA', '1', '10', '10', '', '0123456789/');
      CompData('p_inicio', 'Pagamento inicial', '<=', 'p_fim', 'Pagamento final');
      Validate('p_numero', 'Número da solicitação', '', '', '1', '10', '1', '');
      Validate('p_nome', 'Nome completo', '', '', '1', '50', '1', '');
      Validate('p_cargo', 'Cargo/Função', '', '', '1', '50', '1', '');
      Validate('p_emissao', 'Data de emissão', 'DATA', '', '10', '10', '', '0123456789/');
      ValidateClose();
      ScriptClose();
    }
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    if ($O == 'L') {
      BodyOpenClean('onLoad="this.focus()";');
      CabecalhoRelatorio($w_cliente, 'Desembolsos e Aportes Locais', 4, $w_chave, (($p_logo=='S') ? 'N' : 'S'), 'S');
    } else {
      BodyOpen('onLoad="document.focus()";');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
    }
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    if ($p_logo=='N') {
      ShowHTML('<tr><td align="left" colspan=2>');
      ShowHTML('<table border=0><tr><td colspan="10">Pagamento realizado de <b>' . $p_inicio . '</b> até <b>' . $p_fim . '</b></tr></table>');
    }

    $l_html = '';
    $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'        <table class="tudo" width=99%  border="1" cellspacing="0" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td rowspan="3" colspan="7">'.(($p_logo=='S') ? '<img ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,'img/logo-bid.png',null,null,null,'EMBED').'" alt="img" /><br><br>' : '').'<b>CONTROLE DE DESEMBOLSOS E APORTES LOCAIS<br>Equivalente em '.f($RS_Projeto,'sb_moeda').'</td>';
    $l_html.=chr(13).'            <td colspan="3"><b>Orçamento Vigente - Em '.f($RS_Projeto,'sb_moeda').'</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td>BID - '.f($RS_Projeto,'sb_moeda').'</td>';
    $l_html.=chr(13).'            <td align="right">'.formatNumber(f($RS_Projeto,'valor')).'</td>';
    $l_html.=chr(13).'            <td align="right">100,0%</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td>Local - '.f($RS_Projeto,'sb_moeda').'</td>';
    $l_html.=chr(13).'            <td align="right">0,00</td>';
    $l_html.=chr(13).'            <td align="right">0,0%</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td colspan="7" align="left" valign="bottom"><b>Projeto nº '.nvl(f($RS_Projeto,'codigo_externo'),f($RS_Projeto,'codigo_interno')).'</td>';
    $l_html.=chr(13).'            <td>Total - '.f($RS_Projeto,'sb_moeda').'</td>';
    $l_html.=chr(13).'            <td align="right">'.formatNumber(f($RS_Projeto,'valor')).'</td>';
    $l_html.=chr(13).'            <td align="right">100,0%</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td rowspan="2" colspan="2" bgColor="#f0f0f0">Categorias de Investimento</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">Orçamento Vigente</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">Acumulado<br>Solicitação Anterior</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">Solicitação nº '.$p_numero.'</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">Acumulado Atual</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" width="70">Local</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Desembolso BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Aporte Local</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Desembolso BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Aporte Local</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Desembolso BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Aporte Local</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">[1]</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">[2]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[3]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[4]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[5]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[6]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[7]=[3]+[5]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[8]=[4]+[6]</td>';
    $l_html.=chr(13).'          </tr>';

    $w_cor=$conTrBgColor;
    $w_total_previsto  = 0;
    foreach ($RSQuery as $row) {
      $l_previsto  = f($row,'total_previsto');

      $l_executado = nvl($Valor[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0);
      // Configura variável que decide se os valores serão impressos
      if (nvl($Valor[f($row,'sq_projeto_rubrica')]['USD'],0)!=0 || nvl($Valor[f($row,'sq_projeto_rubrica')]['BRL'],0)!=0 || nvl($Valor[f($row,'sq_projeto_rubrica')]['EUR'],0)!=0) $w_imprime = true; else $w_imprime = false;
      if (f($row,'ultimo_nivel')=='S' && f($row,'aplicacao_financeira')=='N') {
        $w_total_previsto += $l_previsto;
      }

      $l_execAnt = nvl($Anterior[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0);

      $w_folha = ((f($row,'ultimo_nivel')=='N' && $p_sintetico=='N') ? ' class="folha"' : '');

      if ($p_sintetico=='N' || ($p_sintetico=='S' && f($row,'sq_rubrica_pai')=='')) {
        if ($p_financeiro=='N' || ($p_financeiro=='S' && f($row,'aplicacao_financeira')=='N')) {
            $l_html.=chr(13).'      <tr valign="top"'.$w_folha.'>';
            if($w_embed!='WORD') $l_html.=chr(13).'          <td '.$w_rowspan.' align="center"><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$w_dir.$w_pagina.'detalhe&O=L&w_chave='.f($row,'sq_projeto_rubrica').'&w_chave_pai='.$p_projeto.'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG=PJCRONOGRAMA'.MontaFiltro('GET')).'\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações desta rubrica.">'.f($row,'codigo').'</A>&nbsp;';
            else                 $l_html.=chr(13).'          <td '.$w_rowspan.' align="center">'.f($row,'codigo').'&nbsp;';
            $l_html.=chr(13).'          <td>'.f($row,'descricao').' </td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber($l_previsto).' </td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber($l_execAnt).'</td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber($l_executado).'</td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber(nvl($l_execAnt,0)+nvl($l_executado,0)).'</td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'      </tr>';
        }
      }
    } 
    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td align="center" bgColor="#f0f0f0">A</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">&nbsp;Subtotal</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($w_total_previsto).' </td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]+$Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td align="center" bgColor="#f0f0f0">B</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">&nbsp;Fundo Rotativo</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#000000" colspan="2">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#000000">&nbsp; </td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#000000">&nbsp; </td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#000000">&nbsp; </td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td align="center" bgColor="#f0f0f0">C</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">&nbsp;Total A - B</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($w_total_previsto).' </td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]+$Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td align="center" bgColor="#f0f0f0">D</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">&nbsp;Total (BID + Local)</td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($w_total_previsto).' </td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]+$Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td align="center" bgColor="#f0f0f0">E</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">&nbsp;(C/D*100)</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">100,0%</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,0%</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">100,0%</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,0%</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">100,0%</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,0%</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">100,0%</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,0%</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td colspan="10" align="center"><table border=0 width=40%>';
    $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'              <tr><td><font size="2"><b>Brasília, '.$p_emissao.'.</b></font></td></tr>';
    $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'              <tr><td align="center">_____________________________</td></tr>';
    $l_html.=chr(13).'              <tr><td align="center"><font size="2"><b>'.$p_nome.'</b></font></td></tr>';
    $l_html.=chr(13).'              <tr><td align="center"><font size="2"><b>'.$p_cargo.'</b></font></td></tr>';
    $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'            </table>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'        </table></td></tr>';

    ShowHTML($l_html);
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    if ($P2==2) $rel = 'estado'; else $rel=$par;
    AbreForm('Form', $w_dir . $w_pagina . $rel, 'POST', 'return(Validacao(this));', $rel, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_concluido" value="S">');
    ShowHTML('<INPUT type="hidden" name="p_financeiro" value="S">');
    ShowHTML('<INPUT type="hidden" name="p_sintetico" value="N">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto do contrato na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',$w_atributo);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td><b><u>P</u>agamento entre:</b><br><input ' . $w_Disabled . ' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_inicio . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_inicio') . ' e <input ' . $w_Disabled . ' type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr><td><b>Número da <u>S</u>olicitação:</b><br><input ' . $w_Disabled . ' accesskey="S" type="text" name="p_numero" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_numero . '"></td>');
    ShowHTML('      <tr><td><b><u>N</u>ome completo:</b><br><input ' . $w_Disabled . ' accesskey="N" type="text" name="p_nome" class="sti" SIZE="35" MAXLENGTH="50" VALUE="' . $p_nome . '"></td>');
    ShowHTML('      <tr><td><b><u>C</u>argo/função:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_cargo" class="sti" SIZE="35" MAXLENGTH="50" VALUE="' . $p_cargo . '"></td>');
    ShowHTML('      <tr><td><b><u>D</u>ata do relatório:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="p_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_emissao . '" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Exibe logomarca do BID?</b>',$p_logo,'p_logo');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
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
  ShowHTML('</div>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
}

// =========================================================================
// Relatório de execução orçamentário-financeira de projeto.
// -------------------------------------------------------------------------
function EstadoExecucao() {
  extract($GLOBALS);
  global $w_Disabled;
  global $w_embed;
  $w_tipo = $_REQUEST['w_tipo'];
  $w_sq_pessoa = upper(trim($_REQUEST['w_sq_pessoa']));

  if ($O == 'L') {
    // Recupera os dados do projeto selecionado
    $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
    
    // Recupera as rubricas do projeto
    $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,(($p_financeiro=='N') ? null : 'N'),$p_inicio,$p_fim,'PJEXEC'.$p_concluido);

    $sql = new db_getSolicRubrica; $RS1 = $sql->getInstanceOf($dbms,$p_projeto,null,null,null,null,null,$p_inicio,$p_fim,'PJFIN'.$p_concluido);
    foreach($RS1 as $row)  {
      $Moeda[f($row,'sg_fn_moeda')]='1';
      if (f($row,'aplicacao_financeira')=='N') {
        if (!isset($Total[f($row,'sg_fn_moeda')])) {
          $Total[f($row,'sg_fn_moeda')] = f($row,'valor');
        } else {
          $Total[f($row,'sg_fn_moeda')]+=f($row,'valor');
        }
      }
      $lista = explode(',',str_replace(' ',',',f($row,'lista')));      
      foreach($lista as $k => $v) {
        if (!isset($Valor[f($row,'sq_projeto_rubrica')][f($row,'sg_fn_moeda')])) {
          $Valor[$v][f($row,'sg_fn_moeda')] = f($row,'valor');
        } else {
          $Valor[$v][f($row,'sg_fn_moeda')]+=f($row,'valor');
        }
      }
    }

    if (toDate($p_inicio)>f($RS_Projeto,'inicio')) {
      $p_fim_anterior = FormataDataEdicao(toDate($p_inicio)-(24*60*60));

      $sql = new db_getSolicRubrica; $RS2 = $sql->getInstanceOf($dbms,$p_projeto,null,null,null,null,null,FormataDataEdicao(f($RS_Projeto,'inicio')),$p_fim_anterior,'PJFIN'.$p_concluido);
      foreach($RS2 as $row)  {
        $Anterior[f($row,'sg_fn_moeda')]='1';
        if (f($row,'aplicacao_financeira')=='N') {
          if (!isset($TotAnterior[f($row,'sg_fn_moeda')])) {
            $TotAnterior[f($row,'sg_fn_moeda')] = f($row,'valor');
          } else {
            $TotAnterior[f($row,'sg_fn_moeda')]+=f($row,'valor');
          }
        }
        $lista = explode(',',str_replace(' ',',',f($row,'lista')));      
        foreach($lista as $k => $v) {
          if (!isset($Anterior[f($row,'sq_projeto_rubrica')][f($row,'sg_fn_moeda')])) {
            $Anterior[$v][f($row,'sg_fn_moeda')] = f($row,'valor');
          } else {
            $Anterior[$v][f($row,'sg_fn_moeda')]+=f($row,'valor');
          }
        }
      }
    } else {
      $TotAnterior = $Total;
      $Anterior = $Valor;
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
  }

  headerGeral('P', $w_tipo, $w_chave, 'Consulta de '.f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Relatório</TITLE>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    BodyOpenClean('onLoad="this.focus()";');
    CabecalhoRelatorio($w_cliente, 'Estado de Execução', 4, $w_chave, 'S', 'S', (($p_logo=='S') ? 'img/logo-bid.png' : ''));
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td align="left" colspan=2>');
    ShowHTML('<table border=0><tr><td colspan="11">Pagamento realizado de <b>' . $p_inicio . '</b> até <b>' . $p_fim . '</b></tr></table>');

    $l_html = '';
    $l_html.=chr(13).'      <table class="tudo" width=99%  border="0" cellspacing="0" bordercolor="#00000" align="center">';
    $l_html.=chr(13).'        <tr style="height: 40;"><td colspan="11" align="center"><font size="3"><b>ESTADO DE EXECUÇÃO DO PROJETO</b></font></center>';
    $l_html.=chr(13).'        <tr><td colspan="11"><b>NOME DO ORGANISMO EXECUTOR: '.f($RS_Cliente,'nome').' - '.f($RS_Cliente,'nome_resumido').'</b></td></tr>';
    $l_html.=chr(13).'        <tr><td colspan="11"><b>Nº do Contrato de Empréstimo ou Convênio de Cooperação Técnica: '.nvl(f($RS_Projeto,'codigo_externo'),f($RS_Projeto,'codigo_interno')).'</b></td></tr>';
    $l_html.=chr(13).'        <tr><td colspan="11"><b>Nº da Solicitação: '.$p_numero.'</b></td></tr>';
    $l_html.=chr(13).'        <tr><td colspan="11"><b>Data: '.$p_emissao.'</b></td></tr>';
    $l_html.=chr(13).'        <tr><td colspan="11">&nbsp;</td></tr>';
    $l_html.=chr(13).'      </table>';
    $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'        <table class="tudo" width=99%  border="1" cellspacing="0" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Categorias e Subcategorias de Investimento convorme Contrato/Convênio</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Orçamento Vigente BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Orçamento Vigente Aporte Local</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">Desembolso Acumulado por Categoria e Subcategoria de Investimento até a Solicitação Anterior</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">Desembolso por Categoria e Subcategoria de Investimentos nesta Solicitação</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">Desembolso Acumulado por Categorias e Subcategorias de Investimento</td>';
    $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0">Saldo Disponível por Categorias e Subcategorias de Investimento</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">(LMS1)</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">(LMS1)</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" width="70">&nbsp;</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Aporte Local</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Aporte Local</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Aporte Local</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">BID</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">Aporte Local</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[1]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[2]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[3]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[4]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[5]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">[6]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap>[8]=[3]+[5]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap>[9]=[4]+[6]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap>[10]=[1]-[8]</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" nowrap>[11]=[2]+[9]</td>';
    $l_html.=chr(13).'          </tr>';

    $w_cor=$conTrBgColor;
    $w_total_previsto  = 0;
    foreach ($RSQuery as $row) {
      $l_previsto  = f($row,'total_previsto');

      $l_executado = nvl($Valor[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0);
      // Configura variável que decide se os valores serão impressos
      if (nvl($Valor[f($row,'sq_projeto_rubrica')]['USD'],0)!=0 || nvl($Valor[f($row,'sq_projeto_rubrica')]['BRL'],0)!=0 || nvl($Valor[f($row,'sq_projeto_rubrica')]['EUR'],0)!=0) $w_imprime = true; else $w_imprime = false;
      if (f($row,'ultimo_nivel')=='S' && f($row,'aplicacao_financeira')=='N') {
        $w_total_previsto += $l_previsto;
      }

      $l_execAnt = nvl($Anterior[f($row,'sq_projeto_rubrica')][f($RS_Projeto,'sg_moeda')],0);

      $w_folha = ((f($row,'ultimo_nivel')=='N' && $p_sintetico=='N') ? ' class="folha"' : '');

      if ($p_sintetico=='N' || ($p_sintetico=='S' && f($row,'sq_rubrica_pai')=='')) {
        if ($p_financeiro=='N' || ($p_financeiro=='S' && f($row,'aplicacao_financeira')=='N')) {
            $l_html.=chr(13).'      <tr valign="top"'.$w_folha.'>';
            if($w_embed!='WORD') $l_html.=chr(13).'          <td><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$w_dir.$w_pagina.'detalhe&O=L&w_chave='.f($row,'sq_projeto_rubrica').'&w_chave_pai='.$p_projeto.'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG=PJCRONOGRAMA'.MontaFiltro('GET')).'\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações desta rubrica.">'.f($row,'codigo').'</A>&nbsp;';
            else                 $l_html.=chr(13).'          <td>'.f($row,'codigo').'&nbsp;';
            $l_html.=f($row,'descricao').' </td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber($l_previsto).' </td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber($l_execAnt).'</td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber($l_executado).'</td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber(nvl($l_execAnt,0)+nvl($l_executado,0)).'</td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'          <td align="right">'.formatNumber($l_previsto-nvl($l_execAnt,0)-nvl($l_executado,0)).'</td>';
            $l_html.=chr(13).'          <td align="right">0,00</td>';
            $l_html.=chr(13).'      </tr>';
        }
      }
    } 
    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">A. SUBTOTAL POR FONTE</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($w_total_previsto).' </td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]+$Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">'.formatNumber($w_total_previsto-$TotAnterior[f($RS_Projeto,'sg_moeda')]-$Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">0,00</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">B. TOTAL BID + APORTE LOCAL</td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($w_total_previsto).' </td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($TotAnterior[f($RS_Projeto,'sg_moeda')]+$Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'            <td colspan="2" align="center" bgColor="#f0f0f0">'.formatNumber($w_total_previsto-$TotAnterior[f($RS_Projeto,'sg_moeda')]-$Total[f($RS_Projeto,'sg_moeda')]).'</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0">C. PARI-PASSU</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'            <td align="right" bgColor="#f0f0f0">&nbsp;</td>';
    $l_html.=chr(13).'          </tr>';

    $l_html.=chr(13).'          <tr class="folha">';
    $l_html.=chr(13).'            <td colspan="11" align="center"><table border=0 width=40%>';
    $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'              <tr><td><font size="2"><b>Brasília, '.$p_emissao.'.</b></font></td></tr>';
    $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'              <tr><td align="center">_____________________________</td></tr>';
    $l_html.=chr(13).'              <tr><td align="center"><font size="2"><b>'.$p_nome.'</b></font></td></tr>';
    $l_html.=chr(13).'              <tr><td align="center"><font size="2"><b>'.$p_cargo.'</b></font></td></tr>';
    $l_html.=chr(13).'              <tr style="height: 25"><td>&nbsp;</td></tr>';
    $l_html.=chr(13).'            </table>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'        </table></td></tr>';

    ShowHTML($l_html);
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
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
  $sql = new db_getSolicRubrica; $RS = $sql->getInstanceOf($dbms,$w_chave_pai,$w_chave,null,null,null,null,$p_inicio,$p_fim,'PJEXECL'.$p_concluido);

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
  if ($p_inicio!='')    $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Pagamento realizado de <td><b>' . $p_inicio . '</b> até <b>' . $p_fim . '</b>';
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
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'or_rubrica','asc','or_financeiro','asc');
    } else {
      $RS = SortArray($RS,'or_rubrica','asc','or_financeiro','asc');
    }
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
      if (nvl($Total[f($row,'sb_moeda')],'')=='') $Total[f($row,'sb_moeda')] = f($row,'valor'); else $Total[f($row,'sb_moeda')] += f($row,'valor');
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
    case 'ESTADO': EstadoExecucao(); break;
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
