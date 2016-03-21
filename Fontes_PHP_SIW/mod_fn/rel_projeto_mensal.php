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
include_once($w_dir_volta.'funcoes/selecaoAno.php');
include_once($w_dir_volta.'funcoes/selecaoMes.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');

// =========================================================================
//  /rel_projeto_mensal.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório mensal da execução orçamentário-financeira de um projeto
// Mail     : alex@sbpi.com.br
// Criacao  : 08/12/2014 12:09
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
$w_pagina = 'rel_projeto_mensal.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_fn/';
$w_troca = $_REQUEST['w_troca'];
$w_embed = '';

$p_projeto = $_REQUEST['p_projeto'];
$p_ano = nvl($_REQUEST['p_ano'],strftime('%Y',(time())));
$p_mes = nvl($_REQUEST['p_mes'],strftime('%m',(time())));
$p_inicio = nvl($_REQUEST['p_inicio'],'01/'.$p_mes.'/'.$p_ano);
$p_fim = nvl($_REQUEST['p_fim'],formataDataEdicao(last_day(toDate('01/'.$p_mes.'/'.$p_ano))));
$p_nome = upper(trim($_REQUEST['p_nome']));
$p_sintetico = upper(trim($_REQUEST['p_sintetico']));
$p_financeiro = upper(trim($_REQUEST['p_financeiro']));
$p_concluido = $_REQUEST['p_concluido'];
$p_ordena = lower($_REQUEST['p_ordena']);

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

  if ($O == 'L') {
    // Recupera os dados do projeto selecionado
    $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
    
    // Recupera as rubricas do projeto
    $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$p_projeto,null,'S',null,null,(($p_financeiro=='N') ? null : 'N'),'01/01/'.$p_ano,'31/12/'.$p_ano,$SG);
    foreach($RSQuery as $row)  {
      if (f($row,'total_dolar')!='0') { $Moeda['USD']='1';  $Total['USD'] = 0; }
      if (f($row,'total_real')!='0')  { $Moeda['BRL']='1'; $Total['BRL'] = 0; }
      if (f($row,'total_euro')!='0')  { $Moeda['EUR']='1'; $Total['EUR'] = 0; }
      // Se o projeto tem três moedas diferentes, aborta pois esse é o número atual de moedas ativas
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
    // Ordena somente após o laço acima pois não há necessidade dele estar ordenado
    $RSQuery = SortArray($RSQuery,'ordena','asc','mes','asc');
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
      Validate('p_ano', 'Ano para emissão', 'SELECT', '1', '4', '4', '', '0123456789');
      Validate('p_mes', 'Mês para emissão', 'SELECT', '1', '2', '2', '', '0123456789');
      ValidateClose();
      ScriptClose();
    }
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    if ($O == 'L') {
      BodyOpenClean('onLoad="this.focus()";');
      CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave);
    } else {
      BodyOpen('onLoad="document.focus()";');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
    }
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Prepara o array de totais e o do nome do mês
    for ($i=1; $i<=13; $i++) {
      $Totais[$i] = 0;
      switch ($i) {
        case  1: $Mes[$i] = 'Jan'; break;
        case  2: $Mes[$i] = 'Fev'; break;
        case  3: $Mes[$i] = 'Mar'; break;
        case  4: $Mes[$i] = 'Abr'; break;
        case  5: $Mes[$i] = 'Mai'; break;
        case  6: $Mes[$i] = 'Jun'; break;
        case  7: $Mes[$i] = 'Jul'; break;
        case  8: $Mes[$i] = 'Ago'; break;
        case  9: $Mes[$i] = 'Set'; break;
        case 10: $Mes[$i] = 'Out'; break;
        case 11: $Mes[$i] = 'Nov'; break;
        case 12: $Mes[$i] = 'Dez'; break;
        default: $Mes[$i] = 'Total'; break;
      }
    }
    
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro = '';
    $w_filtro.='<tr valign="top"><td align="right">'.(($p_sintetico=='N') ? 'Despesas pagas no mês' : 'Mês selecionado:').' <td><b>' . $p_mes . '/' . $p_ano . '</b>';

    if ($p_financeiro=='S') $w_filtro.='<tr valign="top"><td align="right" colspan="2"><b>Rubricas de aplicação financeira omitidas</b>';
    if ($p_sintetico=='S')  $w_filtro.='<tr valign="top"><td align="right" colspan="2"><b>Versão sintética (apenas rubricas de mais alto nível)</b>';
    
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro > '') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>' . $w_filtro . '</ul></tr></table>');

    $l_html = '';

    $l_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="99%">';
    
    $l_html.=chr(13).'    <tr><td colspan="2"><table width="100%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if (nvl(f($RS_Projeto,'sq_plano'),'')!='') {
      if ($w_embed=='WORD') $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.upper(f($RS_Projeto,'nm_plano')).'</b></font></td></tr>';
      else                  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.ExibePlano('../',$w_cliente,f($RS_Projeto,'sq_plano'),$TP,upper(f($RS_Projeto,'nm_plano'))).'</b></font></td></tr>';
    }
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PROJETO: '.f($RS_Projeto,'codigo_interno').' - '.f($RS_Projeto,'titulo').' ('.f($RS_Projeto,'sq_siw_solicitacao').')</b></font></td></tr>';
    if ($w_tipo!='EXCEL') {
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html .= chr(13).'    <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top" align="center">';
      if ($w_embed!='WORD') {
        $l_html .= chr(13).'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IDE',$TP,'IDE').': '.ExibeSmile('IDE',$w_ide).' '.formatNumber(f($RS_Projeto,'ide'),2).'%</b></td>';
        $l_html .= chr(13).'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IGE',$TP,'IGE').': '.ExibeSmile('IGE',$w_ige).' '.formatNumber(f($RS_Projeto,'ige'),2).'%</b></td>';
        $l_html .= chr(13).'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IDC',$TP,'IDC').': '.ExibeSmile('IDC',$w_idc).' '.formatNumber(f($RS_Projeto,'idc'),2).'%</b></td>';
        $l_html .= chr(13).'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IGC',$TP,'IGC').': '.ExibeSmile('IGC',$w_igc).' '.formatNumber(f($RS_Projeto,'igc'),2).'%</b></td>';
      } else {
        $l_html .= chr(13).'        <td width="25%">IDE: '.ExibeSmile('IDE',$w_ide).' '.formatNumber(f($RS_Projeto,'ide'),2).'%</b></td>';
        $l_html .= chr(13).'        <td width="25%">IGE: '.ExibeSmile('IGE',$w_ige).' '.formatNumber(f($RS_Projeto,'ige'),2).'%</b></td>';
        $l_html .= chr(13).'        <td width="25%">IDC: '.ExibeSmile('IDC',$w_idc).' '.formatNumber(f($RS_Projeto,'idc'),2).'%</b></td>';
        $l_html .= chr(13).'        <td width="25%">IGC: '.ExibeSmile('IGC',$w_igc).' '.formatNumber(f($RS_Projeto,'igc'),2).'%</b></td>';
      }
      $l_html .= chr(13).'      </table>';
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=1></td></tr>';
     
      // Exibe a vinculação
      $l_html.=chr(13).'      <tr><td valign="top" width="30%"><b>Vinculação: </b></td>';
      if($w_embed!='WORD') $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Projeto,'sq_solic_pai'),f($RS_Projeto,'dados_pai'),'S').'</td></tr>';
      else                 $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Projeto,'sq_solic_pai'),f($RS_Projeto,'dados_pai'),'S','S').'</td></tr>';

      $l_html .= chr(13).'      <tr><td><b>Início previsto:</b></td>';
      $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'inicio')).' </td></tr>';
      $l_html .= chr(13).'      <tr><td><b>Término previsto:</b></td>';
      $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'fim')).' </td></tr>';
      $l_html.=chr(13).'        <tr><td><b>Fase atual:</b></td>';
      $l_html.=chr(13).'          <td>'.Nvl(f($RS_Projeto,'nm_tramite'),'-').'</td></tr>';
    }
    $l_html .= chr(13).'</table>';
    $l_html.=chr(13).'      <tr><td colspan=2><br><font size="2"><b>'.$l_nome_menu['RUBRICA'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'        <table'.(($p_sintetico=='S') ? ' class="tudo"' : '').' width=99%  border="'.($p_sintetico=='N' ? '0' : '1').'" bordercolor="#00000">';
    if ($p_sintetico=='S') {
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="1%" nowrap><b>Código</td>';
      $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Descrição</td>';
      $l_html.=chr(13).'            <td colspan="13" bgColor="#f0f0f0"  align="center"><b>Execução Orçamentária de '.$p_ano.' '.((nvl(f($RS_Projeto,'sg_moeda'),'')!='') ? ' ('.f($RS_Projeto,'sg_moeda').')' : '').'</td>';
      $l_html.=chr(13).'          </tr>';
      $l_html.=chr(13).'          <tr align="center" >';
      for ($i=1; $i<=13; $i++) {
        $l_html.=chr(13).'            <td bgColor="'.(($i==(0+$p_mes)) ? $conTrBgColorLightBlue1 : '#f0f0f0').'"><b>'.$Mes[$i].'</b></td>';
      }
      $l_html.=chr(13).'          </tr>';      
    }
    $w_cor=$conTrBgColor;
    
    // Carrega vetor com os valores mensais da rubrica
    $w_atual = 0;
    foreach ($RSQuery as $row) {
      if (f($row,'ultimo_nivel')=='S') {
        if ($w_atual!=f($row,'sq_projeto_rubrica')) {
          $w_atual = f($row,'sq_projeto_rubrica');

          // Inicializa o vetor com os valores mensais da rubrica
          for ($i=1; $i<=13; $i++) $Valor[$w_atual][$i] = 0;
        }
        //Armazena o valor mensal da rubrica
        if (nvl(f($row,'mes'),0)>0) {$w_mes = (f($row,'mes')-($p_ano*100)); $Valor[$w_atual][$w_mes] = f($row,'valor'); }
        
        // Acumula o total da rubrica
        $Valor[$w_atual][13] += f($row,'valor');
        
        // Acumula o total mensal
        if (nvl(f($row,'mes'),0)>0) {$w_mes = (f($row,'mes')-($p_ano*100)); $Totais[$w_mes] += f($row,'valor'); }
        
        $Totais[13] += f($row,'valor');
      }
    }
    
    $w_atual = 0;
    foreach ($RSQuery as $row) {
      
      $w_rowspan = '';
      if (f($row,'sq_projeto_rubrica')!=$w_atual) {
        $w_atual = f($row,'sq_projeto_rubrica');

        if (f($row,'ultimo_nivel')=='S' && $p_sintetico=='N' && $Valor[$w_atual][(0+$p_mes)]>0) $w_rowspan = ' rowspan="2"';
        
        $w_folha = ((f($row,'ultimo_nivel')=='N') ? ' class="folha"' : '');

        $l_html.=chr(13).'      <tr valign="top"'.$w_folha.(($p_sintetico=='N') ? ' height="40px"' : '').'>';
        if($w_embed!='WORD') $l_html.=chr(13).'          <td'.$w_rowspan.'><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$w_dir.$w_pagina.'detalhe&O=L&w_chave='.f($row,'sq_projeto_rubrica').'&w_chave_pai='.$p_projeto.'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG=PJCRONOGRAMA'.MontaFiltro('GET')).'\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Clique para detalhar as despesas da rubrica no mês selecionado.">'.f($row,'codigo').'</A>&nbsp;';
        else                 $l_html.=chr(13).'          <td'.$w_rowspan.'>'.f($row,'codigo').'&nbsp;';
        $l_html.=chr(13).'          <td'.((f($row,'ultimo_nivel')=='N') ? ' colspan="14"' : '').'>'.f($row,'descricao').' </td>';
        
        if (f($row,'ultimo_nivel')=='S') {
          for ($i=1; $i<=13; $i++) {
            if ($p_sintetico=='S') $l_html.=chr(13).'          <td width="6%" nowrap align="right"'.(($i==(0+$p_mes)) ? ' bgColor="'.$conTrBgColorLightBlue1.'">' : '>').formatNumber($Valor[$w_atual][$i]).' </td>';
            else                   $l_html.=chr(13).'          <td width="6%" nowrap>'.(($i==(0+$p_mes)) ? '<b>' : '').$Mes[$i].': '.formatNumber($Valor[$w_atual][$i]).' </td>';
          }
        }
        if (f($row,'ultimo_nivel')=='S' && $p_sintetico=='N' && abs($Valor[$w_atual][(0+$p_mes)])>0) {
          // Recupera despesas da rubrica no mês
          $sql = new db_getSolicRubrica; $RS1 = $sql->getInstanceOf($dbms,$p_projeto,$w_atual,'S',null,null,(($p_financeiro=='N') ? null : 'N'),$p_inicio,$p_fim,'PJEXECLS');
          $RS1 = SortArray($RS1,'or_rubrica','asc','or_financeiro','asc');
          
          $i_html =chr(13).'      <tr valign="top"><td align="center" colspan="14">';
          $i_html.=chr(13).'        <table class="tudo" width="100%"  border="1" bordercolor="#00000">';
          $i_html.=chr(13).'          <tr align="center">';
          $csl++; $i_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="5%"><b>Nr</td>';
          $csl++; $i_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="25%"><b>Despesas do mês '.$p_mes.'/'.$p_ano.'</td>';
          $csl++; $i_html.=chr(13).'            <td colspan="3" bgColor="#f0f0f0"><b>Comprovante de Pagamento</td>';
          $csl++; $i_html.=chr(13).'            <td colspan="3" bgColor="#f0f0f0"><b>Pagamento ('.f($RS_Projeto,'sb_moeda').')</td>';
          $csl++; $i_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="25%"><b>Fornecedor</td>';
          $i_html.=chr(13).'          </tr>';
          $i_html.=chr(13).'          <tr align="center" >';
          $i_html.=chr(13).'            <td bgColor="#f0f0f0" width="6%"><b>Tipo</td>';
          $i_html.=chr(13).'            <td bgColor="#f0f0f0" width="6%"><b>Número</td>';
          $i_html.=chr(13).'            <td bgColor="#f0f0f0" width="7%"><b>Valor</td>';
          $i_html.=chr(13).'            <td bgColor="#f0f0f0" width="6%"><b>Número</td>';
          $i_html.=chr(13).'            <td bgColor="#f0f0f0" width="6%"><b>Valor</td>';
          $i_html.=chr(13).'            <td bgColor="#f0f0f0" width="6%"><b>Data</td>';
          $i_html.=chr(13).'          </tr>';
          $w_cor=$conTrBgColor;
          $w_total_previsto  = 0;
          $i = 0;
          foreach ($RS1 as $row1) {
            if (f($row1,'sb_fn_moeda')!=f($RS_Projeto,'sb_moeda') && f($row1,'valor_doc')==f($row1,'valor')) {
              continue;
            } else {
              $i++;
              $i_html.=chr(13).'      <tr valign="top">';
              $i_html.=chr(13).'          <td align="center">'.$i.' </td>';
              $i_html.=chr(13).'          <td>'.f($row1,'descricao').' </td>';
              $i_html.=chr(13).'          <td>'.f($row1,'nm_tipo_documento').' </td>';
              $i_html.=chr(13).'          <td>'.f($row1,'numero').' </td>';
              $i_html.=chr(13).'          <td align="right" nowrap>'.f($row1,'sb_fn_moeda').' '.formatNumber(f($row1,'valor_doc')).' </td>';
              $i_html.=chr(13).'          <td nowrap>'.exibeSolic($w_dir,f($row1,'sq_financeiro'),f($row1,'cd_financeiro'),'N',$w_tipo);
              $i_html.=chr(13).'          <td align="right">'.formatNumber(f($row1,'valor')).' </td>';
              $i_html.=chr(13).'          <td align="right">'.nvl(FormataDataEdicao(f($row1,'quitacao'),5),'&nbsp;').'</td>';
              $i_html.=chr(13).'          <td>'.f($row1,'nm_pessoa').' </td>';
              $i_html.=chr(13).'      </tr>';
              $w_total_previsto += f($row1,'valor');
            }
          } 
          $i_html.=chr(13).'      <tr valign="top"><td colspan=6 align="right"><b>Total: </b></td><td align="right"><b>'.formatNumber($w_total_previsto).' </b></td><td colspan=3>&nbsp;</td>';
          $i_html.=chr(13).'        </table></td></tr>';
          $l_html.=$i_html;
        }
        $l_html.=chr(13).'      </tr>';
      }
    } 
    $l_html.=chr(13).'          <tr class="folha"'.(($p_sintetico=='N') ? ' height="40px"' : '').'>';
    $l_html.=chr(13).'            <td align="right" colspan="2" bgColor="#f0f0f0">Totais&nbsp;</td>';
    for ($i=1; $i<=13; $i++) {
      $l_html.=chr(13).'          <td bgColor="'.(($i==(0+$p_mes)) ? $conTrBgColorLightBlue1 : '#f0f0f0"').'"'.(($p_sintetico=='N') ? ' width="6%" nowrap>'.$Mes[$i].': ' : ' align="right">').formatNumber($Totais[$i]).' </td>';
    }
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'        </table></td></tr>';

    ShowHTML($l_html);
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', 'mensal', $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_concluido" value="S">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto do contrato na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',$w_atributo,1,2);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    SelecaoAno('<u>A</u>no de emissão do relatório','A','Informe o ano para emissão.',$p_ano,$w_cliente,'p_ano',null,null);
    SelecaoMes('<u>M</u>ês para detalhamento das despesas','M','Informe o mês para emissão.',$w_cliente,$p_mes,'p_mes',null,null);
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Omite rubricas de aplicação financeira?</b>',$p_financeiro,'p_financeiro',null,null,null,2);
    ShowHTML('      </tr><tr>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Omite detalhamento das despesas?</b>',$p_sintetico,'p_sintetico',null,null,null,2);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan="2"><hr>');
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

  // Recupera as rubricas do projeto
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
  if ($p_inicio!='')    $w_filtro.='<tr valign="top"><td align="right">Pagamento realizado de <td><b>' . $p_inicio . '</b> até <b>' . $p_fim . '</b>';
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
