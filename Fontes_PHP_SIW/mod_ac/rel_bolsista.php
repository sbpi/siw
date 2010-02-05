<?
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
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getRelBolsista.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoAno.php');
include_once($w_dir_volta.'funcoes/selecaoMes.php');
// =========================================================================
//  /rel_bolsista.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Relatório para contrato de bolsistas
// Mail     : celso@sbpi.com.br
// Criacao  : 26/01/2007 10:00
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Geração de gráfico
//                   = W   : Geração de documento no formato MS-Word (Office 2003)

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }


// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'rel_bolsista.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_ac/';
$w_troca        = upper($_REQUEST['w_troca']);

if ($O=='') $O='P';

switch ($O) {
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relatório geral financeiro para acompanhamento das bolsas
// -------------------------------------------------------------------------
function Rel_Bolsista() {
  extract($GLOBALS);
  $p_chave  = $_REQUEST['p_chave'];
  $p_tipo   = $_REQUEST['p_tipo'];
  $p_mes    = strval($_REQUEST['p_mes']);
  $p_ano    = $_REQUEST['p_ano'];
  if (nvl($p_chave,'')>'') {
    $RS1 = db_getSolicData::getInstanceOf($dbms,$p_chave,'PJGERAL');
    $w_inicio     = f($RS1,'inicio');
    $w_fim        = f($RS1,'fim');
    if(f($RS1,'meses_projeto')==0)  $w_meses = 1;
    else                            $w_meses = f($RS1,'meses_projeto');
    $w_ano_i      = date('Y',$w_inicio);
    $w_ano_f      = date('Y',$w_fim);
    $w_anos       = $w_ano_f - $w_ano_1 + 1;
    $w_mes_i      = 13-intval(date('m',f($RS1,'inicio')));
    $w_mes_f      = intval(date('m',f($RS1,'fim')));
    $w_cota_total = nvl(f($RS1,'valor'),0);
    if($w_cota_total!=0 && $w_meses!=0) $w_cota_mes   = f($RS1,'valor') / $w_meses;
    else                                $w_cota_mes   = 0;
    $w_titulo     = f($RS1,'titulo');
  }
  if ($O=='L') {
    // Recupera o logo do cliente a ser usado nas listagens
    $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
    if (f($RS,'logo')>'')   $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  if($p_tipo=='F') {
    $RS = db_getRelBolsista::getInstanceOf($dbms,$p_chave,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'or_tema','asc','or_nivel','asc','nm_bolsista','asc','phpdt_vencimento','asc');
  } elseif($p_tipo=='T') {
    $RS = db_getRelBolsista::getInstanceOf($dbms,$p_chave,null,null,null,null,null,null,'TEMA');
    $RS = SortArray($RS,'or_tema','asc','or_nivel','asc','nm_bolsista','asc','phpdt_vencimento','asc');
  } elseif($p_tipo=='M') {
    $RS = db_getRelBolsista::getInstanceOf($dbms,$p_chave,null,null,null,null,null,null,'MENSAL');
  } elseif($p_tipo=='R') {
    $RS = db_getRelBolsista::getInstanceOf($dbms,$p_chave,null,null,null,null,$p_mes,$p_ano,'RESUMO1');
    $RS = SortArray($RS,'or_tema','asc','or_nivel','asc','nm_bolsista','asc','phpdt_vencimento','asc');
  }
  if ($w_tipo_rel=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_pag=1;
    $w_linha=8;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('<div align="center">');
    ShowHTML('<table width="100%" border="0" cellspacing="3">');
    ShowHTML('<tr><td colspan="2">');
    ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
    ShowHTML($w_titulo.'<br>'.$w_ano_i.'/'.$w_ano_f);
    ShowHTML('</FONT></TD></TR></TABLE>');
  } else {
    Cabecalho();
    head();
    if($p_tipo=='F') {
      ShowHTML('<TITLE>Relatório financeiro geral de bolsistas</TITLE>');
    } elseif($p_tipo=='T') {
      ShowHTML('<TITLE>Relatório financeiro por tema de bolsistas</TITLE>');
    } elseif($p_tipo=='M') {
      ShowHTML('<TITLE>Relatório financeiro mensal bolsistas</TITLE>');
    }
    if (!(strpos('P',$O)===false)) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_chave','Projeto de bolsista','SELECT','1','1','30','1','1');
      Validate('p_tipo','Tipo do relatório','SELECT','1','1','1','1','1');
      ShowHTML('  if (theForm.p_tipo.value != \'R\' && (theForm.p_mes.value != \'\' || theForm.p_ano.value != \'\')) {');
      ShowHTML('     alert (\'Para informar o mês e\\\ou ano, somente no relatorio do tipo resumo geral!\');');
      ShowHTML('     return false;');
      ShowHTML('  } else if (theForm.p_tipo.value == \'R\' && (theForm.p_mes.value == \'\' || theForm.p_ano.value == \'\')) {');
      ShowHTML('     alert (\'No relatório do tipo resumo geral, é obrigatório informar o mês e o ano!\');');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($O=='L') {
      BodyOpenClean(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      ShowHTML('<div align="center">');
      ShowHTML('<table width="100%" border="0" cellspacing="3">');
      ShowHTML('<tr><td colspan="2">');
      ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></TD><TD ALIGN="RIGHT" NOWRAP><B><FONT SIZE=4 COLOR="#000000">');
      if($w_ano_i!=$w_ano_f) ShowHTML($w_titulo.'<br> Exercício '.$w_ano_i.'/'.$w_ano_f);
      else                   ShowHTML($w_titulo.'<br> Exercício '.$w_ano_i);
      ShowHTML('</FONT></B></TD></TR></TABLE>');
    } else {
      BodyOpen('onLoad=\'document.Form.p_chave.focus()\';');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<div align=center><center>');
      ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
      ShowHTML('<HR>');
    } 
  } 
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td colspan="2"><div align="center">');
    ShowHTML('<table border="0" width="100%">');
    $w_reg = 0;
    ShowHTML('</ul></td></tr></table>');
    ShowHTML('</div></td></tr>');
    //Relatório financeiro
    if($p_tipo=='F') {
      ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>QUADRO DEMONSTRATIVO GERAL</b></font></div></td></tr>');
      ShowHTML('<tr><td colspan="2">');
      ShowHTML('      <table border="1" bordercolor="#00000" width="100%">');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Nº</b></td>');
      ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Nome do bolsista</b></td>');
      ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Nível</b></td>');
      ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Tema</b></td>');
      ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Valor</td>');
      ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="2"><b>Vigência</td>');
      for($w_ano=$w_ano_i;$w_ano<=$w_ano_f;$w_ano++) {
        if($w_ano_i==$w_ano_f)    ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="'.$w_meses.'"><b>'.$w_ano.'</td>');
        elseif($w_ano==$w_ano_i)  ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="'.$w_mes_i.'"><b>'.$w_ano.'</td>');
        elseif($w_ano==$w_ano_f)  ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="'.$w_mes_f.'"><b>'.$w_ano.'</td>');
        else                      ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="12"><b>'.$w_ano.'</td>');
      }
      ShowHTML('          <td bgColor="#f0f0f0" rowspan="2"><b>TOTAL</td>');    
      ShowHTML('        </tr>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td bgColor="#f0f0f0"><b>Início</b></td>');
      ShowHTML('          <td bgColor="#f0f0f0"><b>Fim</b></td>');
      for($w_ano=$w_ano_i;$w_ano<=$w_ano_f;$w_ano++) {
        $x=1;
        $y=12;
        if($w_ano_i==$w_ano_f) {
          $x=intval(date('m',f($RS1,'inicio')));
          $y=intval(date('m',f($RS1,'fim')));
        } elseif($w_ano==$w_ano_i) {
          $x=intval(date('m',f($RS1,'inicio')));
        } elseif($w_ano==$w_ano_f) {
          $y=intval(date('m',f($RS1,'fim')));
        }
        for($w_nome=$x;$w_nome<=$y;$w_nome++) {
          switch ($w_nome) {
            case 1:   ShowHTML('          <td bgColor="#f0f0f0"><b>JAN</b></td>'); break;
            case 2:   ShowHTML('          <td bgColor="#f0f0f0"><b>FEV</b></td>'); break;
            case 3:   ShowHTML('          <td bgColor="#f0f0f0"><b>MAR</b></td>'); break;
            case 4:   ShowHTML('          <td bgColor="#f0f0f0"><b>ABR</b></td>'); break;
            case 5:   ShowHTML('          <td bgColor="#f0f0f0"><b>MAI</b></td>'); break;
            case 6:   ShowHTML('          <td bgColor="#f0f0f0"><b>JUN</b></td>'); break;
            case 7:   ShowHTML('          <td bgColor="#f0f0f0"><b>JUL</b></td>'); break;
            case 8:   ShowHTML('          <td bgColor="#f0f0f0"><b>AGO</b></td>'); break;
            case 9:   ShowHTML('          <td bgColor="#f0f0f0"><b>SET</b></td>'); break;
            case 10:  ShowHTML('          <td bgColor="#f0f0f0"><b>OUT</b></td>'); break;
            case 11:  ShowHTML('          <td bgColor="#f0f0f0"><b>NOV</b></td>'); break;
            case 12:  ShowHTML('          <td bgColor="#f0f0f0"><b>DEZ</b></td>'); break;
         }
        }
      }
      ShowHTML('        </tr>');
      if (count($RS)<=0) {
        $w_linha += 1;
        ShowHTML('    <tr><td colspan="'.($w_meses+8).'"><div align="center"><b>Não foram encontrados registros</b></div></td></tr>');
      } else {
        // Listagem das metas de acordo com o filtro selecionado na tela de filtragem
        $w_cont           = 0;
        $w_total_parcela  = 0;
        foreach($RS as $row) {
          if ($w_linha>19 && $w_tipo_rel=='WORD') {
            ShowHTML('    </table>');
            ShowHTML('  </td>');
            ShowHTML('</tr>');
            ShowHTML('</table>');
            ShowHTML('</center></div>');
            ShowHTML('    <br style="page-break-after:always">');
            $w_linha=6;
            $w_pag=$w_pag+1;
            ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'WORD').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
            ShowHTML('RELATÓRIO FINANCEIRO DE BOLSISTAS');
            ShowHTML('</FONT><TR><TD WIDTH="50%" ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B>');
            ShowHTML('<TR><TD COLSPAN="2" ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$w_pag.'</B></TD></TR>');
            ShowHTML('</TD></TR>');
            ShowHTML('</FONT></B></TD></TR></TABLE>');
            ShowHTML('<div align=center><center>');
            ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
            ShowHTML('<tr><td colspan="2"><div align="center">');
            ShowHTML('<table border="0" width="100%">');
            if (nvl($p_chave,'')>'') {
              $RS1 = db_getSolicData::getInstanceOf($dbms,$p_chave,'PJGERAL');
              ShowHTML('<tr><td width="15%"><b>Projeto:</b></td><td>'.f($RS1,'titulo').' - '.f($RS1,'sigla').'</td>');
            } 
            ShowHTML('</ul></td></tr></table>');
            ShowHTML('</div></td></tr>');
            ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
            ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>QUADRO DEMONSTRATIVO GERAL</b></font></div></td></tr>');
            ShowHTML('<tr><td colspan="2">');
            ShowHTML('      <table border="1" bordercolor="#00000" width="100%">');
            ShowHTML('        <tr align="center">');
            ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Nome do bolsista</b></td>');
            ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Nível</b></td>');
            ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Tema</b></td>');
            ShowHTML('          <td bgColor="#f0f0f0" rowspan="2" colspan="1"><b>Valor</td>');
            ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="2"><b>Vigência</td>');
            for($w_ano=$w_ano_i;$w_ano<=$w_ano_f;$w_ano++) {
              if($w_ano==$w_ano_i)      ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="'.$w_mes_i.'"><b>'.$w_ano.'</td>');
              elseif($w_ano==$w_ano_f)  ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="'.$w_mes_f.'"><b>'.$w_ano.'</td>');
              else                      ShowHTML('          <td bgColor="#f0f0f0" rowspan="1" colspan="12"><b>'.$w_ano.'</td>');
            }
            ShowHTML('          <td bgColor="#f0f0f0" rowspan="2"><b>TOTAL</td>');    
            ShowHTML('        </tr>');
            ShowHTML('        <tr align="center">');
            ShowHTML('          <td bgColor="#f0f0f0"><b>Início</b></td>');
            ShowHTML('          <td bgColor="#f0f0f0"><b>Fim</b></td>');
            for($w_ano=$w_ano_i;$w_ano<=$w_ano_f;$w_ano++) {
              $x=1;
              $y=12;
              if($w_ano==$w_ano_i)      $x=intval(date('m',f($RS1,'inicio')));
              elseif($w_ano==$w_ano_f)  $y=intval(date('m',f($RS1,'fim')));
              for($w_nome=$x;$w_nome<=$y;$w_nome++) {
                switch ($w_nome) {
                  case 1:   ShowHTML('          <td bgColor="#f0f0f0"><b>JAN</b></td>'); break;
                  case 2:   ShowHTML('          <td bgColor="#f0f0f0"><b>FEV</b></td>'); break;
                  case 3:   ShowHTML('          <td bgColor="#f0f0f0"><b>MAR</b></td>'); break;
                  case 4:   ShowHTML('          <td bgColor="#f0f0f0"><b>ABR</b></td>'); break;
                  case 5:   ShowHTML('          <td bgColor="#f0f0f0"><b>MAI</b></td>'); break;
                  case 6:   ShowHTML('          <td bgColor="#f0f0f0"><b>JUN</b></td>'); break;
                  case 7:   ShowHTML('          <td bgColor="#f0f0f0"><b>JUL</b></td>'); break;
                  case 8:   ShowHTML('          <td bgColor="#f0f0f0"><b>AGO</b></td>'); break;
                  case 9:   ShowHTML('          <td bgColor="#f0f0f0"><b>SET</b></td>'); break;
                  case 10:  ShowHTML('          <td bgColor="#f0f0f0"><b>OUT</b></td>'); break;
                  case 11:  ShowHTML('          <td bgColor="#f0f0f0"><b>NOV</b></td>'); break;
                  case 12:  ShowHTML('          <td bgColor="#f0f0f0"><b>DEZ</b></td>'); break;
                }
              }
              ShowHTML('        </tr>');
            } 
          }
          $w_contrato = 0;
          //Inicio da montagem da lista das ações e metas de acordo com o filtro
          if (nvl(f($row,'sq_acordo'),'')!=nvl($w_sq_acordo_atual,'')) {
            $w_contrato = 1;
            if ($w_cont!=0) {
              ShowHTML('   <td align="right">'.formatNumber(f($row1,'valor'),2).'</td>');
              ShowHTML(' </tr>');
              $w_total_parcela += f($row1,'valor_parcela');
            } 

            // Recupera e acumula os valores mensais do bolsista
            // Acumula também valores totais para uso no final da impressão
            $RS1 = db_getRelBolsista::getInstanceOf($dbms,$p_chave,f($row,'outra_parte'),null,null,null,null,null,null);
            $RS1 = SortArray($RS1,'phpdt_vencimento','asc');
            unset($w_valor);
            foreach($RS1 as $row1) {
              $w_valor[$w_contrato][intval(date('Y',f($row1,'vencimento')))][intval(date('m',f($row1,'vencimento')))] += f($row1,'valor_parcela');
              $w_total_gasto += f($row1,'valor_parcela');
              $w_total_mes[intval(date('Y',f($row1,'vencimento')))][intval(date('m',f($row1,'vencimento')))] += f($row1,'valor_parcela');

              // Registra o gasto mensal acumulado
              $w_atual = intval(date('Y',f($row1,'vencimento')).str_pad(date('m',f($row1,'vencimento')),2,'0',STR_PAD_LEFT));
              for($i=intval(date('Y',f($row1,'vencimento'))); $i<=$w_ano_f; $i++) {
                for($j=1; $j<=12; $j++) {
                  if ($w_atual<=intval($i.str_pad($j,2,'0',STR_PAD_LEFT))) $w_acumulado[$i][$j] += f($row1,'valor_parcela');
                }
              }
            }
  
            $w_cont=1;
            ShowHTML(' <tr valign="top">');

            // Se o bolsista ainda está com contrato ativo, exibe um contador
            if (f($row,'contrato_ativo')=='S') {
              $w_reg += 1;
              ShowHTML('   <td align="center"><b>'.$w_reg.'</td>');
            } else {
              ShowHTML('   <td>&nbsp;</td>');
            }
            ShowHTML('   <td nowrap>'.f($row,'nm_bolsista').'</td>');
            ShowHTML('   <td nowrap align="center">'.f($row,'nm_nivel').'</td>');
            ShowHTML('   <td align="center">'.f($row,'or_tema').'</td>');
            ShowHTML('   <td align="right">'.number_format(f($row,'valor_parcela'),2,',','.').'</td>');
            ShowHTML('   <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>');
            ShowHTML('   <td align="center">'.FormataDataEdicao(f($row,'fim')).'</td>');
            for($i=$w_ano_i; $i<=$w_ano_f; $i++) {
              $mes_ini = 1; 
              $mes_fim = 12;
              if($w_ano_i==$w_ano_f) {
                $mes_ini = intval(date('m',$w_inicio));
                $mes_fim = intval(date('m',$w_fim));
              } elseif ($i==$w_ano_i) {
                $mes_ini = intval(date('m',$w_inicio));
              } elseif ($i==$w_ano_f) {
                $mes_fim = intval(date('m',$w_fim));
              }
              for($j=$mes_ini; $j<=$mes_fim; $j++) {
                if ($w_valor[$w_contrato][$i][$j]>0) ShowHTML('   <td align="right">'.number_format($w_valor[$w_contrato][$i][$j],2,',','.').'</td>');
                else                                 ShowHTML('   <td align="right">&nbsp;</td>');
              }
            }
            $w_sq_acordo_atual = nvl(f($row,'sq_acordo'),'');
          }
        } 
        ShowHTML('   <td align="right">'.number_format(f($row,'valor'),2,',','.').'</td>');
        ShowHTML(' </tr>');
        $w_total_parcela += f($row,'valor_parcela');
      } 

      // Imprime a linha de totais das colunas
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td><b>TOTAL</b></td>');
      for($i=1;$i<=2;$i++) ShowHTML('          <td>&nbsp;</td>');
      ShowHTML('          <td align="right">'.formatNumber($w_total_parcela,2));
      for($i=1;$i<=($w_meses+2);$i++) ShowHTML('          <td>&nbsp;</td>');
      ShowHTML('          <td align="right"><b>'.formatNumber($w_total_gasto,2).'</b>');

      // Imprime a linha do resumo financeiro
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td align="center" colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>RESUMO FINANCEIRO</b></td>');
      for($i=1;$i<=($w_meses+2);$i++) ShowHTML('          <td>&nbsp;</td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td align="center" colspan="4"><b>COTA FINANCEIRA '.$w_ano_i.' '.$w_ano_f.'</b>');
      ShowHTML('          <td align="right"><b>'.formatNumber($w_cota_total,2).'</b>');
      ShowHTML('          <td nowrap><b>Cota Mensal</b>');
      for($i=1;$i<=$w_meses;$i++) {
        ShowHTML('          <td align="right">'.formatNumber($w_cota_mes,2)); 
      }
      ShowHTML('          <td align="right"><b>'.formatNumber($w_cota_total,2).'</b>');

      // Imprime a linha do total gasto/comprometido
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td align="center" colspan="4"><b>TOTAL GASTO/COMPROMETIDO</b>');
      ShowHTML('          <td align="right"><b>'.formatNumber($w_total_gasto,2).'</b>');
      ShowHTML('          <td nowrap><b>Gasto Mensal</b>');
      for($i=$w_ano_i; $i<=$w_ano_f; $i++) {
        $mes_ini = 1; 
        $mes_fim = 12;
        if($w_ano_i==$w_ano_f) {
          $mes_ini = intval(date('m',$w_inicio));
          $mes_fim = intval(date('m',$w_fim));
        } elseif ($i==$w_ano_i) {
          $mes_ini = intval(date('m',$w_inicio));
        } elseif ($i==$w_ano_f) {
          $mes_fim = intval(date('m',$w_fim));
        }
        for($j=$mes_ini; $j<=$mes_fim; $j++) {
          if ($w_total_mes[$i][$j]>0) ShowHTML('   <td align="right">'.formatNumber($w_total_mes[$i][$j],2).'</td>');
          else                        ShowHTML('   <td align="right">&nbsp;</td>');
        }
      }
      ShowHTML('          <td align="right"><b>'.formatNumber($w_total_gasto,2).'</b>') ;

      // Imprime a linha do saldo
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td align="center" colspan="4"><b>SALDO</b>');
      ShowHTML('          <td align="right"><font color="#0000FF"><b>'.formatNumber($w_cota_total - $w_total_gasto,2).'</b></font>');
      ShowHTML('          <td nowrap><b>Saldo Mensal</b>');
      for($i=$w_ano_i; $i<=$w_ano_f; $i++) {
        $mes_ini = 1; 
        $mes_fim = 12;
        if($w_ano_i==$w_ano_f) {
          $mes_ini = intval(date('m',$w_inicio));
          $mes_fim = intval(date('m',$w_fim));
        } elseif ($i==$w_ano_i) {
          $mes_ini = intval(date('m',$w_inicio));
        } elseif ($i==$w_ano_f) {
          $mes_fim = intval(date('m',$w_fim));
        }
        for($j=$mes_ini; $j<=$mes_fim; $j++) {
          if ($w_total_mes[$i][$j]>0) ShowHTML('   <td align="right"><font color="#0000FF">'.formatNumber($w_cota_mes - $w_total_mes[$i][$j],2).'</td></font>');
          else                        ShowHTML('   <td align="right">&nbsp;</td>');
        }
      }
      ShowHTML('          <td align="right"><b>'.formatNumber($w_cota_total - $w_total_gasto,2).'</b>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td colspan="4">&nbsp;');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td nowrap><b>Gasto Total</b>');
      for($i=$w_ano_i; $i<=$w_ano_f; $i++) {
        $mes_ini = 1; 
        $mes_fim = 12;
        if($w_ano_i==$w_ano_f) {
          $mes_ini = intval(date('m',$w_inicio));
          $mes_fim = intval(date('m',$w_fim));
        } elseif ($i==$w_ano_i) {
          $mes_ini = intval(date('m',$w_inicio));
        } elseif ($i==$w_ano_f) {
          $mes_fim = intval(date('m',$w_fim));
        }
        for($j=$mes_ini; $j<=$mes_fim; $j++) {
          if ($w_acumulado[$i][$j]>0) ShowHTML('   <td align="right">'.formatNumber($w_acumulado[$i][$j],2).'</td>');
          else                        ShowHTML('   <td align="right">&nbsp;</td>');
        }
      }
      ShowHTML('          <td align="right"><b>'.formatNumber($w_total_gasto,2).'</b>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td colspan="4">&nbsp;');
      ShowHTML('          <td>&nbsp;');
      ShowHTML('          <td nowrap><b>Cota Total</b>');
      for($i=1;$i<=$w_meses;$i++) ShowHTML('          <td align="right">'.formatNumber($w_cota_mes * $i,2));
      ShowHTML('          <td align="right">&nbsp;');
    //Relatório por tema
    } elseif ($p_tipo=='T') {
      ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>QUADRO DEMONSTRATIVO POR TEMA</b></font></div></td></tr>');
      $w_tema_atual     = 0;
      $w_nivel_atual    = 0;
      $w_contrato_atual = 0;
      $w_linha          = 0;
      $w_linha_ant      = 0;
      $w_valor_tema     = 0;
      $w_valor_nivel    = 0;
      $w_valor_contrato = 0;
      $w_qtd_contrato   = 0;
      $w_valor          = 0;
      $w_cota_mensal    = 0;
      $w_gasto_mensal_tema  = 0;
      $w_valor_projeto      = 0;
      $w_fim_projeto        = 0;
      $w_meses_fim_projeto  = 0;
      if (count($RS)<=0) {
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><font size="2" color="red"><b>Não foram encontrados registros</b></font></div></td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');        
      } else {
        foreach($RS as $row) {
          //Testa de quebra de contrato(bolsista)
          if($w_contrato_atual!=Nvl(f($row,'sq_acordo'),-1)) {
            //Testa de quebra de nível(tipo de bolsa)
            if($w_nivel_atual!=f($row,'sq_nivel')) {
              $w_linha += 1;
              $w_gasto_mensal_tema += nvl(($w_qtd_contrato*$w_valor),0);
            }
            //Testa de quebra de tema
            if($w_tema_atual!=f($row,'sq_tema'))  {
              $w_linha             = 1;
              if($w_tema_atual!=0) {
                //Armazena no array a parte do resumo do relatorio
                $w_relatorio[$w_tema_atual][0]['nome_tema'] = $w_nome_tema;
                $w_relatorio[$w_tema_atual][0]['resp_tema'] = $w_resp_tema;
                $w_relatorio[$w_tema_atual][1][5] = 'Cota mensal do projeto mãe';
                $w_relatorio[$w_tema_atual][1][6] = $w_cota_mensal;
                $w_relatorio[$w_tema_atual][2][5] = 'Gasto mensal do tema';
                $w_relatorio[$w_tema_atual][2][6] = $w_gasto_mensal_tema;
                $w_relatorio[$w_tema_atual][3][5] = 'Participação percentual mensal';
                $w_relatorio[$w_tema_atual][3][6] = (($w_gasto_mensal_tema/$w_cota_mensal)*100);
                $w_relatorio[$w_tema_atual][4][5] = 'Cota anual do projeto mãe';
                $w_relatorio[$w_tema_atual][4][6] = $w_valor_projeto;
                $w_relatorio[$w_tema_atual][5][5] = 'Total gasto no tema até o momento';
                $w_relatorio[$w_tema_atual][6][5] = 'Participação percentual anual';
                $w_relatorio[$w_tema_atual][6][6] = (($w_relatorio[$w_tema_atual][5][6]/$w_valor_projeto)*100);
                $w_relatorio[$w_tema_atual][7][5] = 'Total comprometido até '.mesAno(date('F',$w_fim_projeto)).' de '.date('Y',$w_fim_projeto);
                $w_relatorio[$w_tema_atual][7][6] = ($w_gasto_mensal_tema*$w_meses_fim_projeto);
                $w_relatorio[$w_tema_atual][8][5] = 'Bolsistas envolvidos no tema';
              }
              $w_gasto_mensal_tema = 0;
              $w_pessoa_atual      = '';
              $w_linha_bolsa       = 1;
              $w_coluna            = 1;
            }
            $w_relatorio[f($row,'sq_tema')][$w_linha][1]  = f($row,'nm_nivel');
            if (nvl(f($row,'meses_nivel'),0)!=0) {
              $w_relatorio[f($row,'sq_tema')][$w_linha][2]  = (f($row,'valor_nivel')/f($row,'meses_nivel'));
            } else {
              $w_relatorio[f($row,'sq_tema')][$w_linha][2]  = 0;
            }
            if($w_nivel_atual!=0) {
              $w_relatorio[$w_tema_atual][$w_linha_ant][3]      = $w_qtd_contrato;
              $w_relatorio[$w_tema_atual][$w_linha_ant][4]      = ($w_qtd_contrato*$w_valor);
            }
            if($w_nivel_atual!=f($row,'sq_nivel')) {
              if (Nvl(f($row,'sq_acordo'),0)==0) $w_qtd_contrato = 0; else $w_qtd_contrato = 1;
            } else {
              $w_qtd_contrato += 1;
            }
          }
          //Armazena a data do mes anterior ao atual
          if (intval(date('m',time()))==1)$w_data_atual = intval(intval(date('Y',time())-1).'12');
          else                            $w_data_atual = intval(date('Y',time()).intval(date('m',time()))-1);
          //Armazena a data do vencimento da parcela
          $w_data_vencimento = intval(date('Y',f($row,'vencimento')).date('m',f($row,'vencimento')));
          //Atualização das variáveis de acordo com a ultima linha do resultset
          $w_nome_tema      = f($row,'nm_tema');
          $w_resp_tema      = f($row,'nm_resp_etapa');
          $w_tema_atual     = f($row,'sq_tema');
          $w_nivel_atual    = f($row,'sq_nivel');
          $w_contrato_atual = f($row,'sq_acordo');
          $w_valor          = nvl(f($row,'valor_parcela'),0);
          if (nvl(f($row,'meses_projeto'),0)!=0) {
            $w_cota_mensal    = (f($row,'valor_projeto')/f($row,'meses_projeto'));
          } else {
            $w_cota_mensal    = 0;
          }
          $w_linha_ant      = $w_linha;
          $w_valor_projeto  = f($row,'valor_projeto');
          $w_fim_projeto    = f($row,'fim_projeto');
          if(intval(date('d',time()))>intval(date('d',f($row,'vencimento')))) $w_meses_fim_projeto = (f($row,'meses_fim_projeto')-1);
          else                                                                $w_meses_fim_projeto = f($row,'meses_fim_projeto');
          //Faz o somatório do total gasto até o momento no tema, testando a data do vencimento da parcela e da data atual        
          if($w_data_vencimento < $w_data_atual)  $w_relatorio[f($row,'sq_tema')][5][6] += f($row,'valor_parcela');
          //Armazena no array o nome dos bolsistas por tema
          if ($w_pessoa_atual!=Nvl(f($row,'nm_bolsista'),'-') && (Nvl(f($row,'nm_bolsista'),'-') != '-')) {
            $w_relatorio[$w_tema_atual][0]['bolsista'][$w_linha_bolsa][$w_coluna] = f($row,'nm_bolsista').' ('.f($row,'nm_nivel').')';
            if ($w_coluna==2) {
              $w_linha_bolsa += 1;
              $w_coluna       = 1;
            } else {
              $w_coluna      += 1;
            }
            $w_pessoa_atual   = f($row,'nm_bolsista');
          }
        }
        //Armazena os valores da ultima linha do resultset
        $w_relatorio[$w_tema_atual][$w_linha_ant][3]      = $w_qtd_contrato;
        $w_relatorio[$w_tema_atual][$w_linha_ant][4]      = ($w_qtd_contrato*$w_valor);
        $w_gasto_mensal_tema += ($w_qtd_contrato*$w_valor);
        $w_relatorio[$w_tema_atual][0]['nome_tema'] = $w_nome_tema;
        $w_relatorio[$w_tema_atual][0]['resp_tema'] = $w_resp_tema;
 
        //Armazena no array a parte do resumo do relatorio
        $w_relatorio[$w_tema_atual][1][5] = 'Cota mensal do projeto mãe';
        $w_relatorio[$w_tema_atual][1][6] = $w_cota_mensal;
        $w_relatorio[$w_tema_atual][2][5] = 'Gasto mensal do tema';
        $w_relatorio[$w_tema_atual][2][6] = $w_gasto_mensal_tema;
        $w_relatorio[$w_tema_atual][3][5] = 'Participação percentual mensal';
        $w_relatorio[$w_tema_atual][3][6] = (($w_gasto_mensal_tema/$w_cota_mensal)*100);
        $w_relatorio[$w_tema_atual][4][5] = 'Cota anual do projeto mãe';
        $w_relatorio[$w_tema_atual][4][6] = $w_valor_projeto;
        $w_relatorio[$w_tema_atual][5][5] = 'Total gasto no tema até o momento';
        $w_relatorio[$w_tema_atual][6][5] = 'Participação percentual anual';
        $w_relatorio[$w_tema_atual][6][6] = (($w_relatorio[$w_tema_atual][5][6]/$w_valor_projeto)*100);
        $w_relatorio[$w_tema_atual][7][5] = 'Total comprometido até '.mesAno(date('F',$w_fim_projeto)).' de '.date('Y',$w_fim_projeto);
        $w_relatorio[$w_tema_atual][7][6] = ($w_gasto_mensal_tema*$w_meses_fim_projeto);      
        $w_relatorio[$w_tema_atual][8][5] = 'Bolsistas envolvidos no tema';
        //Descarregar o array para montar o relatório na tela
        $w_cont = 1;
        foreach($w_relatorio as $rel) {
          if($w_cont>1) {
            ShowHTML('        <tr bgColor="#f0f0f0">');
            ShowHTML('          <td colspan="2" align="right">TOTAIS:</td>');
            ShowHTML('          <td align="center">'.$w_qtd_contr_total.'</td>');
            ShowHTML('          <td align="right">'.formatNumber($w_gasto_total,2).'</td>');
            ShowHTML('          <td colspan="2">&nbsp;</td>');
            ShowHTML('    </table></td></tr>');
          }
          $w_qtd_contr_total = 0;
          $w_gasto_total = 0;        
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><font size="2"><b>TEMA '.$w_cont.': '.$rel[0][nome_tema].'</b></font></td></tr>');
          ShowHTML('<tr><td colspan="2"><b>Responsável: '.$rel[0][resp_tema].'</b></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2">');        
          ShowHTML('      <table border="1" bordercolor="#00000" width="100%">');
          ShowHTML('        <tr>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"><b>Modalidade</b></td>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"><b>Valor R$</b></td>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"><b>Nº</b></td>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"><b>Gasto mensal R$</b></td>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"colspan="2"><b>Resumo R$</td>');
          $w_aux = 1;
          for($i=1;$i<count($rel);$i++) {
            ShowHTML('        <tr>');
            ShowHTML('          <td>'.nvl($rel[$i][1],'&nbsp;').'</td>');
            ShowHTML('          <td align="right">'.formatNumber($rel[$i][2],2).'</td>');
            ShowHTML('          <td align="center">'.nvl($rel[$i][3],'&nbsp;').'</td>');
            $w_qtd_contr_total += $rel[$i][3];
            if (nvl($rel[$i][4],0)!=0)    {
              ShowHTML('          <td align="right">'.formatNumber($rel[$i][4],2).'</td>');
              $w_gasto_total += ($rel[$i][3]*$rel[$i][2]);
            } else {
              ShowHTML('          <td align="right">&nbsp;</td>');
            }
            if($i==3) {
              ShowHTML('          <td bgColor="#f0f000">'.nvl($rel[$i][5],'&nbsp;').'</td>');
              ShowHTML('          <td align="right" bgColor="#f0f000">'.formatNumber(nvl($rel[$i][6],0),2).'%</td>');
            } elseif($i==6) {
              ShowHTML('          <td bgColor="#f0f000">'.nvl($rel[$i][5],'&nbsp;').'</td>');
              ShowHTML('          <td align="right" bgColor="#f0f000">'.formatNumber(nvl($rel[$i][6],0),2).'%</td>');
            } elseif($i==7) {
              ShowHTML('          <td bgColor="#f0f000">'.nvl($rel[$i][5],'&nbsp;').'</td>');
              ShowHTML('          <td align="right" bgColor="#f0f000">'.formatNumber(nvl($rel[$i][6],0),2).'</td>');
            } elseif($i==8) {
              ShowHTML('          <td align="center" colspan="2" bgColor="#f0f0f0"><b>'.nvl($rel[$i][5],'&nbsp;').'</b></td>');
            } elseif($i>8) {
              echo 
              ShowHTML('          <td>'.nvl($rel[0]['bolsista'][$w_aux][1],'&nbsp;').'</td>');
              ShowHTML('          <td>'.nvl($rel[0]['bolsista'][$w_aux][2],'&nbsp;').'</td>');
              $w_aux += 1;
            } else {
              ShowHTML('          <td bgColor="#f0f000">'.nvl($rel[$i][5],'&nbsp;').'</td>');
              if (nvl($rel[$i][6],0)!=0)    ShowHTML('          <td align="right" bgColor="#f0f000">'.formatNumber($rel[$i][6],2).'</td>');
              else                          ShowHTML('          <td align="right" bgColor="#f0f000">&nbsp;</td>');
            }
          }
          for($j=$w_aux;$j<=count($rel[0]['bolsista']);$j++) {
            ShowHTML('        <tr>');
            ShowHTML('          <td>&nbsp;</td>');
            ShowHTML('          <td>&nbsp;</td>');
            ShowHTML('          <td>&nbsp;</td>');
            ShowHTML('          <td>&nbsp;</td>');
            ShowHTML('          <td>'.nvl($rel[0]['bolsista'][$j][1],'&nbsp;').'</td>');
            ShowHTML('          <td>'.nvl($rel[0]['bolsista'][$j][2],'&nbsp;').'</td>');
          }
          $w_cont += 1;
        }
        ShowHTML('        <tr bgColor="#f0f0f0">');
        ShowHTML('          <td colspan="2" align="right">TOTAIS:</td>');
        ShowHTML('          <td align="center">'.$w_qtd_contr_total.'</td>');
        ShowHTML('          <td align="right">'.formatNumber($w_gasto_total,2).'</td>');
        ShowHTML('          <td colspan="2">&nbsp;</td>');      
        ShowHTML('    </table></td></tr>');
      }
    //Relatório mensal
    } elseif ($p_tipo=='M') {
      ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>QUADRO DEMONSTRATIVO POR MÊS</b></font></div></td></tr>');
      $w_linha        = 1;
      $w_gasto_mensal = 0;
      $w_gasto_total  = 0;
      if (count($RS)<=0) {
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><font size="2" color="red"><b>Não foram encontrados registros</b></font></div></td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');        
      } else {
        foreach($RS as $row) {
          if((nvl($w_mes_atual,'')!='')&&($w_mes_atual!=date('Ym',f($row,'vencimento')))) {
            $w_relatorio[$w_mes_atual][0]['mes'] = mesAno(date('F',toDate('01'.'/'.substr($w_mes_atual,4,2).'/'.substr($w_mes_atual,0,4)))).'/'.substr($w_mes_atual,0,4);
            $w_relatorio[$w_mes_atual][1][5] = 'Cota mensal';
            if (nvl(f($row,'meses_projeto'),0)!=0) {
              $w_relatorio[$w_mes_atual][1][6] = (f($row,'valor_projeto')/f($row,'meses_projeto'));
            } else {
              $w_relatorio[$w_mes_atual][1][6] = 0;
            }
            $w_relatorio[$w_mes_atual][2][5] = 'Gasto mensal';
            $w_relatorio[$w_mes_atual][2][6] = $w_gasto_mensal;
            $w_relatorio[$w_mes_atual][3][5] = 'Saldo mensal';
            $w_relatorio[$w_mes_atual][3][6] = ($w_relatorio[$w_mes_atual][1][6]-$w_gasto_mensal);
            $w_relatorio[$w_mes_atual][4][5] = 'Cota anual '.$w_ano_i.'/'.$w_ano_f;
            $w_relatorio[$w_mes_atual][4][6] = f($row,'valor_projeto');
            $w_relatorio[$w_mes_atual][5][5] = 'Total gasto até o momento';
            $w_relatorio[$w_mes_atual][5][6] = $w_gasto_total;
            $w_relatorio[$w_mes_atual][6][5] = 'Saldo real';
            $w_relatorio[$w_mes_atual][6][6] = (f($row,'valor_projeto')-$w_relatorio[$w_mes_atual][5][6]);
            $w_relatorio[$w_mes_atual][7][5] = 'Total comprometido até '.mesAno(date('F',$w_fim_projeto)).' de '.date('Y',$w_fim_projeto);
            $w_relatorio[$w_mes_atual][7][6] = ($w_gasto_mensal*$w_meses_fim_projeto);
            $w_relatorio[$w_mes_atual][8][5] = 'Saldo projetado';
            $w_relatorio[$w_mes_atual][8][6] = (f($row,'valor_projeto')-($w_gasto_mensal*$w_meses_fim_projeto)-$w_gasto_total);
            $w_relatorio[$w_mes_atual][9][5] = 'Observações';
            $w_linha        = 0;
            $w_gasto_mensal = 0;
          }
          if((nvl($w_nivel_atual,'')!='')&&($w_nivel_atual!=f($row,'nm_nivel'))) {
            $w_linha += 1;  
          }
          if (nvl(f($row,'vencimento'),'nulo')!='nulo') {
            $w_relatorio[date('Ym',f($row,'vencimento'))][f($row,'or_nivel')][1] = f($row,'nm_nivel');
            if (nvl(f($row,'meses_nivel'),0)!=0) {
              $w_relatorio[date('Ym',f($row,'vencimento'))][f($row,'or_nivel')][2] = (f($row,'valor_nivel')/f($row,'meses_nivel'));
            } else {
              $w_relatorio[date('Ym',f($row,'vencimento'))][f($row,'or_nivel')][2] = 0;
            }
            $w_relatorio[date('Ym',f($row,'vencimento'))][f($row,'or_nivel')][3] += 1;
            $w_qtd_contrato = $w_relatorio[date('Ym',f($row,'vencimento'))][f($row,'or_nivel')][3];
            if (nvl(f($row,'meses_nivel'),0)!=0) {
              $w_relatorio[date('Ym',f($row,'vencimento'))][f($row,'or_nivel')][4] = ($w_qtd_contrato*(f($row,'valor_nivel')/f($row,'meses_nivel')));
            } else {
              $w_relatorio[date('Ym',f($row,'vencimento'))][f($row,'or_nivel')][4] = 0;
            }
          } else {
            $w_mes_inicio = date('Ym',$w_inicio);
            $w_mes_fim    = date('Ym',$w_fim);
            for ($i=$w_mes_inicio; $i<=$w_mes_fim; $i++) {
              $l_ano = substr($i,0,4);
              $l_mes = substr($i,4,2);
              if ($l_mes > 12) $i = ($l_ano+1).'01';
              if (!isset($w_relatorio[$i][f($row,'or_nivel')][1])) {
                $w_relatorio[$i][f($row,'or_nivel')][1] = f($row,'nm_nivel');
                if (nvl(f($row,'meses_nivel'),0)!=0) {
                  $w_relatorio[$i][f($row,'or_nivel')][2] = (f($row,'valor_nivel')/f($row,'meses_nivel'));
                } else {
                  $w_relatorio[$i][f($row,'or_nivel')][2] = 0;
                }
                $w_relatorio[$i][f($row,'or_nivel')][3] = 0;
                $w_relatorio[$i][f($row,'or_nivel')][4] = 0;
              }
            }
          }
          $w_gasto_mensal     += nvl(f($row,'valor_parcela'),0);
          $w_gasto_total      += nvl(f($row,'valor_parcela'),0);
          $w_nivel_atual       = f($row,'nm_nivel');
          $w_mes_atual         = date('Ym',f($row,'vencimento'));
          $w_valor_projeto     = f($row,'valor_projeto');
          $w_meses_projeto     = f($row,'meses_projeto');
          $w_meses_fim_projeto = f($row,'meses_fim_projeto');
          $w_fim_projeto       = f($row,'fim_projeto');
        }
        if ($w_gasto_mensal>0) {
          $w_relatorio[$w_mes_atual][0]['mes'] = mesAno(date('F',toDate('01'.'/'.substr($w_mes_atual,4,2).'/'.substr($w_mes_atual,0,4)))).'/'.substr($w_mes_atual,0,4);
          $w_relatorio[$w_mes_atual][1][5] = 'Cota mensal';
          $w_relatorio[$w_mes_atual][1][6] = ($w_valor_projeto/$w_meses_projeto);
          $w_relatorio[$w_mes_atual][2][5] = 'Gasto mensal';
          $w_relatorio[$w_mes_atual][2][6] = $w_gasto_mensal;
          $w_relatorio[$w_mes_atual][3][5] = 'Saldo mensal';
          $w_relatorio[$w_mes_atual][3][6] = ($w_relatorio[$w_mes_atual][1][6]-$w_gasto_mensal);
          $w_relatorio[$w_mes_atual][4][5] = 'Cota anual '.$w_ano_i.'/'.$w_ano_f;
          $w_relatorio[$w_mes_atual][4][6] = $w_valor_projeto;
          $w_relatorio[$w_mes_atual][5][5] = 'Total gasto até o momento';
          $w_relatorio[$w_mes_atual][5][6] = $w_gasto_total;
          $w_relatorio[$w_mes_atual][6][5] = 'Saldo real';
          $w_relatorio[$w_mes_atual][6][6] = ($w_valor_projeto-$w_relatorio[$w_mes_atual][5][6]);
          $w_relatorio[$w_mes_atual][7][5] = 'Total comprometido até '.mesAno(date('F',$w_fim_projeto)).' de '.date('Y',$w_fim_projeto);
          $w_relatorio[$w_mes_atual][7][6] = ($w_gasto_mensal*$w_meses_fim_projeto);
          $w_relatorio[$w_mes_atual][8][5] = 'Saldo projetado';
          $w_relatorio[$w_mes_atual][8][6] = ($w_valor_projeto-($w_gasto_mensal*$w_meses_fim_projeto)-$w_relatorio[$w_mes_atual][5][6]);
          $w_relatorio[$w_mes_atual][9][5] = 'Observações';
        }
        //Descarregar o array para montar o relatório na tela
        $w_cont = 1;
        foreach($w_relatorio as $rel) {
          if($w_cont>1) {
            ShowHTML('        <tr>');
            ShowHTML('          <td colspan="2" align="right" bgColor="#f0f0f0">TOTAIS:</td>');
            ShowHTML('          <td align="center" bgColor="#f0f0f0">'.$w_qtd_contr_total.'</td>');
            ShowHTML('          <td align="right" bgColor="#f0f0f0">'.formatNumber($w_gasto_total,2).'</td>');
            ShowHTML('          <td colspan="2">&nbsp;</td>');
            ShowHTML('    </table></td></tr>');
          }
          $w_qtd_contr_total = 0;
          $w_gasto_total = 0;        
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2"><font size="2"><b>DEMONSTRATIVO MENSAL - '.$rel[0]['mes'].'</b></font></td></tr>');
          ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          ShowHTML('<tr><td colspan="2">');        
          ShowHTML('      <table border="1" bordercolor="#00000" width="100%">');
          ShowHTML('        <tr>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"><b>Modalidade</b></td>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"><b>Valor R$</b></td>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"><b>Nº</b></td>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"><b>Gasto mensal R$</b></td>');
          ShowHTML('          <td align="center" bgColor="#f0f0f0"colspan="2"><b>Resumo R$</td>');
          for($i=1;$i<count($rel);$i++) {
            ShowHTML('        <tr>');
            ShowHTML('          <td>'.nvl($rel[$i][1],'&nbsp;').'</td>');
            ShowHTML('          <td align="right">'.formatNumber($rel[$i][2],2).'</td>');
            ShowHTML('          <td align="center">'.nvl($rel[$i][3],'&nbsp;').'</td>');
            $w_qtd_contr_total += $rel[$i][3];
            if (nvl($rel[$i][4],0)!=0)    {
              ShowHTML('          <td align="right">'.formatNumber($rel[$i][4],2).'</td>');
              $w_gasto_total += ($rel[$i][3]*$rel[$i][2]);
            } else {
              ShowHTML('          <td align="right">&nbsp;</td>');
            }
            if($i==7) {
              ShowHTML('          <td bgColor="#f0f000">'.nvl($rel[$i][5],'&nbsp;').'</td>');
              ShowHTML('          <td align="right" bgColor="#f0f000">'.formatNumber(nvl($rel[$i][6],0),2).'</td>');
            } elseif($i==9) {
              ShowHTML('          <td colspan="2" bgColor="#f0f0f0"><b>'.nvl($rel[$i][5],'&nbsp;').'</b></td>');
            } elseif($i>9) {
              ShowHTML('          <td colspan="2">&nbsp;</td>');
            } else {
              ShowHTML('          <td bgColor="#f0f000">'.nvl($rel[$i][5],'&nbsp;').'</td>');
              if (nvl($rel[$i][6],0)!=0)    ShowHTML('          <td align="right" bgColor="#f0f000">'.formatNumber($rel[$i][6],2).'</td>');
              else                          ShowHTML('          <td align="right" bgColor="#f0f000">&nbsp;</td>');
            }
          }
          $w_cont += 1;
        }
        ShowHTML('        <tr>');
        ShowHTML('          <td colspan="2" align="right" bgColor="#f0f0f0">TOTAIS:</td>');
        ShowHTML('          <td align="center" bgColor="#f0f0f0">'.$w_qtd_contr_total.'</td>');
        ShowHTML('          <td align="right" bgColor="#f0f0f0">'.formatNumber($w_gasto_total,2).'</td>');
        ShowHTML('          <td colspan="2">&nbsp;</td>');      
        ShowHTML('    </table></td></tr>');
      }
    //Relatório de resumo geral
    } elseif ($p_tipo=='R') {
      ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>QUADRO DEMONSTRATIVO POR TEMA</b></font></div></td></tr>');
      ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
      ShowHTML('<tr><td colspan="2"><div align="center"><font size="3"><b>RESUMO GERAL ('.mesAno(date('F',toDate('01/'.$p_mes.'/'.$p_ano))).'/'.$p_ano.')</b></font></div></td></tr>');
      ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
      if (count($RS)<=0) {
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2">&nbsp;</td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><font size="2" color="red"><b>Não foram encontrados registros</b></font></div></td></tr>');
        ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');        
      } else {
        $w_cont            = 1;
        $w_linha           = 0;
        $w_tema_atual      = 0;
        $w_vr_total_bev    = 0;
        $w_vr_total_dtiev  = 0;
        $w_qtd_total_bev   = 0;
        $w_qtd_total_dtiev = 0;
        foreach($RS as $row) {
          if($w_cont==1) {
            ShowHTML('<tr><td colspan="2"><div align="center"><font size="2"><b>Coordenador geral: '.f($row,'nm_projeto_resp').'</b></font></div></td></tr>');
            ShowHTML('<tr><td colspan="2"><div align="center"><hr NOSHADE color=#000000 size=2></div></td></tr>');
          }
          $w_relatorio[f($row,'or_tema')][1] = 'TEMA '.f($row,'or_tema');
          $w_relatorio[f($row,'or_tema')][2] += f($row,'valor_parcela');
          if(nvl(f($row,'sq_acordo'),'')>'') $w_relatorio[f($row,'or_tema')][3] += 1;
          if (!(strpos('BEV',f($row,'nm_nivel'))===false)) {
            $w_vr_total_bev    += f($row,'valor_parcela');
            if(nvl(f($row,'sq_acordo'),'')>'') $w_qtd_total_bev   += 1; 
          } else {
            $w_vr_total_dtiev  += f($row,'valor_parcela');
            if(nvl(f($row,'sq_acordo'),'')>'') $w_qtd_total_dtiev += 1;             
          }
          if($w_tema_atual!=f($row,'sq_tema')) $w_linha += 1;
          $w_tema_atual = f($row,'sq_tema');
          $w_cont += 1;
        }
        $w_relatorio[($w_linha+1)][1] = 'TOTAL BEV';
        $w_relatorio[($w_linha+1)][2] = $w_vr_total_bev;
        $w_relatorio[($w_linha+1)][3] = $w_qtd_total_bev;
        $w_relatorio[($w_linha+2)][1] = 'TOTAL DTI/EV';
        $w_relatorio[($w_linha+2)][2] = $w_vr_total_dtiev;
        $w_relatorio[($w_linha+2)][3] = $w_qtd_total_dtiev;
        $w_relatorio[($w_linha+3)][1] = 'TOTAL GERAL';
        $w_relatorio[($w_linha+3)][2] = ($w_vr_total_bev + $w_vr_total_dtiev);
        $w_relatorio[($w_linha+3)][3] = ($w_qtd_total_bev + $w_qtd_total_dtiev);
        $RS = db_getRelBolsista::getInstanceOf($dbms,$p_chave,null,null,null,null,$p_mes,$p_ano,'RESUMO1');
        $w_linha       = 1;
        $w_nivel_atual = 0;
        foreach($RS as $row) {
          $w_relatorio[f($row,'or_nivel')][4] = f($row,'nm_nivel');
          if (nvl(f($row,'meses_nivel'),0)!=0) {
            $w_relatorio[f($row,'or_nivel')][5] = (f($row,'valor_nivel')/f($row,'meses_nivel'));
          } else {
            $w_relatorio[f($row,'or_nivel')][5] = 0;
          }
          if(nvl(f($row,'sq_acordo'),'')>'')  $w_relatorio[f($row,'or_nivel')][6] += 1;
          $w_relatorio[f($row,'or_nivel')][7] += f($row,'valor_parcela');
        }
        ShowHTML('<tr><td colspan="2">');        
        ShowHTML('      <table border="1" bordercolor="#00000" width="100%">');
        ShowHTML('        <tr valign="top">');
        ShowHTML('          <td align="center" bgcolor="'.$conTrAlternateBgColor.'"><b>Temas</b></td>');
        ShowHTML('          <td align="center" bgcolor="'.$conTrAlternateBgColor.'"><b>Valor R$</b></td>');
        ShowHTML('          <td align="center" bgcolor="'.$conTrAlternateBgColor.'"><b>Nº de bolsistas</b></td>');
        ShowHTML('          <td align="center" bgcolor="'.$conTrAlternateBgColor.'"><b>Mod./Nível</b></td>');
        ShowHTML('          <td align="center" bgcolor="'.$conTrAlternateBgColor.'"><b>Valor R$</b></td>');
        ShowHTML('          <td align="center" bgcolor="'.$conTrAlternateBgColor.'"><b>Nº</b></td>');
        ShowHTML('          <td align="center" bgcolor="'.$conTrAlternateBgColor.'"><b>Gasto R$</b></td>');
        foreach($w_relatorio as $rel) {
          ShowHTML('        <tr valign="top">');
          if ((!(strpos('TOTAL BEV',$rel[1])===false))||(!(strpos('TOTAL DTI/EV',$rel[1])===false))||(!(strpos('TOTAL GERAL',$rel[1])===false))) {
            ShowHTML('          <td bgcolor="'.$conTrAlternateBgColor.'"><b>'.nvl($rel[1],'&nbsp;').'</b></td>');
            ShowHTML('          <td bgcolor="'.$conTrAlternateBgColor.'" align="right">'.formatNumber(nvl($rel[2],0),2).'</td>');
            ShowHTML('          <td bgcolor="'.$conTrAlternateBgColor.'" align="center">'.nvl($rel[3],0).'</td>');
          } else {
            ShowHTML('          <td><b>'.nvl($rel[1],'&nbsp;').'</b></td>');
            ShowHTML('          <td align="right">'.formatNumber(nvl($rel[2],0),2).'</td>');
            ShowHTML('          <td align="center">'.nvl($rel[3],0).'</td>');
          }
          ShowHTML('          <td><b>'.nvl($rel[4],'&nbsp;').'</b></td>');
          ShowHTML('          <td align="right">'.formatNumber(nvl($rel[5],0),2).'</td>');
          ShowHTML('          <td align="center">'.nvl($rel[6],0).'</td>');
          ShowHTML('          <td align="right">'.formatNumber(nvl($rel[7],0),2).'</td>');
        }
        ShowHTML('    </table></td></tr>');
      }      
    }
    ShowHTML('    </table>');
    ShowHTML('  </div></td>');
    ShowHTML('</tr>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Bolsista',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCADBOLSA');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto na relação.',$p_chave,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_chave','PJCADBOLSA',null,2);
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');    
    ShowHTML('        <td valign="top" title="Selecione o tipo de relatório"><b>Tipo do relatório</b><br><SELECT ACCESSKEY="T" CLASS="sts" NAME="p_tipo" '.$w_Disabled.'>');
    ShowHTML('          <option value="">---');
    ShowHTML('          <option value="F">Financeiro');
    ShowHTML('          <option value="T">Por tema');
    ShowHTML('          <option value="M">Mensal');
    ShowHTML('          <option value="R">Resumo geral');
    ShowHTML('        </select>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td valign="top">');
    ShowHTML('          <table width="100%" border="0">');
    SelecaoMes('<u>M</u>ês:','M','Selecione o mês para o relatório de resumo geral.',null,null,'p_mes',null,null);
    SelecaoAno('<u>A</u>no:','A','Selecione o ano para o relatório de resumo geral.',$w_ano,null,'p_ano',null,null);
    ShowHTML('          </table></td></tr>');
    ShowHTML('    </table></td></tr>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
  case 'REL_BOLSISTA':      Rel_Bolsista();     break;
  default:
    Cabecalho();
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
