<?
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
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getConvOutraParte.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoParcela.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoAditivo.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoNota.php');
include_once($w_dir_volta.'funcoes/selecaoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoAno.php');
// =========================================================================
//  /relatorios.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
// Mail     : celso@sbpi.com.br
// Criacao  : 14/04/2007 14:00
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
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }
// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);
// Carrega variáveis locais com os dados dos parâmetros recebidos
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];
$par        = strtoupper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);
$p_ordena   = $_REQUEST['p_ordena'];
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'relatorios.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_ac/';
if ($O=='') $O='P';
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
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
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
// Demonstrativo juridico/financeiro
// -------------------------------------------------------------------------
function Demonstrativo() {
  extract($GLOBALS);
  $w_acordo  = $_REQUEST['w_acordo'];
  //$p_inicio  = $_REQUEST['p_inicio'];
  //$p_fim     = $_REQUEST['p_fim'];
  $w_tipo    = $_REQUEST['w_tipo'];
  if ($O=='L') {
    if ($w_tipo=='WORD') {
      HeaderWord(null);
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      CabecalhoWord($w_cliente,'DEMONSTRATIVO JURÍDICO/FINANCEIRO',$w_pag);
      $w_embed = 'WORD';
      //CabecalhoWord($w_cliente,$w_TP,0);
    } else {
      Cabecalho();
      $w_embed = 'EMBED';
      ShowHTML('<HEAD>');
      ShowHTML('<TITLE>Demonstrativo jurídico/financeiro</TITLE>');
      ShowHTML('</HEAD>');
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpenClean('onLoad=\'this.focus()\'; ');
      CabecalhoRelatorio($w_cliente,'DEMONSTRATIVO JURÍDICO/FINANCEIRO',4,$w_acordo);      
    }
    ShowHTML('<table width="100%" align="center" border="0" cellpadding=0 cellspacing="3">');
    //ShowHTML('<tr><td colspan="4" align="right"  cellspacing=00><b>Ano</b>: '.$w_ano);
    ShowHTML('<tr><td colspan="4">');
    $w_acordo_atual = 0;
    $RS_Solic = db_getSolicList::getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,f($RS_Menu,'sigla'),4,
                  null,null,null,null,null,null,null,null,null,null,$w_acordo,null,null,null,null,
                  null, null,null,null,null,null,null,null,null,null,null,null,null);
    if (count($RS_Solic)==0) {
      ShowHTML('   <tr><td colspan="4"><br><hr NOSHADE color=#000000 size=4></td></tr>');
      ShowHTML('   <tr><td colspan="4" align="center" bgcolor="#f0f0f0"><font size="2"><b>Nenhum registro encontrado para os parâmetros informados</b></td></tr>');
      ShowHTML('   <tr><td colspan="4"><hr NOSHADE color=#000000 size=4></td></tr>');
    } else {
      foreach ($RS_Solic as $row) {
        if($w_acordo_atual==0 || $w_acordo_atual<>f($row,'sq_siw_solicitacao')) {
          ShowHTML('<tr><td colspan="4"><hr NOSHADE color=#000000 size=4></td></tr>');
          ShowHTML('<tr><td bgcolor="#f0f0f0" colspan="2"><b>'.strtoupper(f($row,'nome')).': '.f($row,'codigo_interno').' - '.f($row,'titulo').' ('.f($row,'sq_siw_solicitacao').')');
          ShowHTML('    <td bgcolor="#f0f0f0" colspan="2" align=right><b>PROCESSO: </b>'.nvl(f($row,'processo'),'---'));
          ShowHTML('<tr><td colspan="4"><hr NOSHADE color=#000000 size=4></td></tr>');
          $RS_Outra = db_getConvOutraParte::getInstanceOf($dbms,null,f($row,'sq_siw_solicitacao'),null,null);
          foreach($RS_Outra as $row1) ShowHTML('   <tr valign="top"><td><b>RAZÃO SOCIAL</b><td colspan="3">'.nvl(f($row1,'cnpj'),f($row,'cpf')).' - '.f($row1,'nm_pessoa').'</div></td></tr>');
          ShowHTML('   <tr valign="top"><td><b>OBJETO</b><td colspan=3>'.f($row,'objeto'));
          ShowHTML('   <tr valign="top">');
          ShowHTML('       <td valign="top"><b>TIPO DE REAJUSTE</b><td>'.f($row,'nm_tipo_reajuste'));
          if (nvl(f($row,'tipo_reajuste'),-1)==1) {
            ShowHTML('       <td width="1%" nowrap><b>ÍNDICE BASE </b><td>'.f($row,'nm_indicador').' de '.f($row,'indice_base'));
            if (nvl(f($row,'vl_indice_base'),'')!='') ShowHTML(' ('.formatNumber(f($row,'vl_indice_base'),4).')');
            else ShowHTML(' (não informado)');
          }
          ShowHTML('   <tr valign="top">');
          ShowHTML('       <td ><b>VIGÊNCIA INICIAL</b><td>'.FormataDataEdicao(f($row,'inicio_real'),5).' a '.FormataDataEdicao(f($row,'fim_real'),5).' ('.f($row,'meses_acordo').' meses)');
          ShowHTML('       <td width="1%" nowrap><b>DOTAÇÃO </b><td>'.f($row,'nm_cc').' - '.f($row,'sg_cc'));
          ShowHTML('   <tr valign="top">');
          if (f($row,'fim_real')<>f($row,'fim')) {
            ShowHTML('       <td ><b>VIGÊNCIA TOTAL</b><td>'.FormataDataEdicao(f($row,'inicio'),5).' a '.FormataDataEdicao(f($row,'fim'),5).' ('.f($row,'meses_contrato').' meses)');
          } else {
            ShowHTML('       <td colspan="2">&nbsp;');
          }
          ShowHTML('       <td width="1%" nowrap><b>ELEM.DESP. </b><td>'.nvl(f($row,'cd_espec_despesa'),'---').' - '.nvl(f($row,'nm_espec_despesa'),'---'));
          ShowHTML('   <tr valign="top">');
          ShowHTML('       <td colspan="2">&nbsp;</td>');
          ShowHTML('       <td width="1%" nowrap><b>FONTE </b><td>'.nvl(f($row,'cd_lcfonte_recurso'),'---').' - '.nvl(f($row,'nm_lcfonte_recurso'),'---'));
          
          //Aditivos
          $RS_Aditivo = db_getAcordoAditivo::getInstanceOf($dbms,$w_cliente,null,f($row,'sq_siw_solicitacao'),null,null,null,null,null,null,null,null,null);
          foreach($RS_Aditivo as $row2) {
            ShowHTML('   <tr valign="top"><td><b>'.f($row2,'codigo').'</b><td colspan="3">'.FormataDataEdicao(f($row2,'inicio'),5).' a '.FormataDataEdicao(f($row2,'fim'),5).' ('.f($row2,'duracao').' dias) - '.f($row2,'objeto'));
          }  
          if (f($row,'aviso_prox_conc')=='S') ShowHTML('   <tr valign="top"><td><b>DENÚNCIA</b><td colspan="3"> -'.f($row,'dias_aviso').' dias (a partir de '.formataDataEdicao(f($row,'aviso'),5).')');
          if(nvl($w_acordo,'')>'') {
            ShowHTML('<tr valign="bottom"><td align="left" colspan="2"><b>Execução do ano '.$w_ano.':</b>');
            ShowHTML('<td align="right" height="1%" colspan="2"><br>');
            for ($i=date('Y',f($row,'inicio')); $i<=date('Y',f($row,'fim')); $i++) {
              if($w_ano!=$i && $w_tipo!='WORD')  ShowHTML('<a class="hl" href="'.$w_dir.$w_pagina.'Demonstrativo&R='.$w_pagina.$par.'&O=L&w_acordo='.$w_acordo.'&w_ano='.$i.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.$i.'&nbsp;</a>');
            }
            ShowHTML('</td></tr>');
          }
          //Parcelas
          $w_valor_inicial  = f($row,'valor');
          $w_fim            = f($row,'fim_real');
          $w_sg_tramite     = f($row,'sg_tramite');
          $RS_Parc = db_getAcordoParcela::getInstanceOf($dbms,f($row,'sq_siw_solicitacao'),null,'RELJUR',null,'01/01/'.$w_ano,'31/12/'.$w_ano,null,null,null,null);
          $RS_Parc = SortArray($RS_Parc,'ordem','asc', 'dt_lancamento', 'asc', 'dt_nota', 'asc');
          if(count($RS_Parc)==0) {
            ShowHTML('   <tr><td colspan="4">');
            ShowHTML('    <table   height="40" width="100%" border="1">');
            ShowHTML('      <tr valign="middle">');
            ShowHTML('        <td align="center" bgcolor="#f0f0f0"><font size="2"><b>Nenhuma parcela encontrada para o ano selecionado.</b></td>');
            ShowHTML('      </tr>');
            ShowHTML('    </table>');
            ShowHTML('  </tr>');
          } else {
            // Recupera informações sobre as notas ligadas ao contrato ou a seus aditivos
            $RS_Nota = db_getAcordoNota::getInstanceOf($dbms,$w_cliente,null,f($row,'sq_siw_solicitacao'),null,null,null,'01/01/'.$w_ano,'31/12/'.$w_ano,null);
            $RS_Nota = SortArray($RS_Nota,'data','asc', 'numero', 'asc');
            if (count($RS_Nota)>0) {
              $i = 0;
              foreach($RS_Nota as $row) {
                $w_cab[$i] = f($row,'sg_tipo_documento').'<br>'.f($row,'numero');
                $w_cab1[$i] = '<table border=0 width="100%"><tr valign="top"><td><b>';
                if (f($row,'abrange_inicial')=='S')   $w_cab1[$i] .= '('.f($row,'sg_inicial').')';
                if (f($row,'abrange_acrescimo')=='S') $w_cab1[$i] .= '('.f($row,'sg_acrescimo').')';
                if (f($row,'abrange_reajuste')=='S')  $w_cab1[$i] .= '('.f($row,'sg_reajuste').')';
                $w_cab1[$i] .= '<td align="right"><b>'.formatNumber(f($row,'valor')).'</table>';
                $w_nota[f($row,'sq_acordo_nota')] = $i;
                $w_valor[$i] = f($row,'valor');
                $w_saldo[$i] = 0;
                if (nvl(f($row,'data_cancelamento'),'')!='') {
                  $w_cancelamento[f($row,'data_cancelamento')][f($row,'sq_acordo_nota')] = f($row,'valor_cancelamento');
                  ksort($w_cancelamento);
                }
                $i += 1;
              }
            }
            $w_total = 0;
            $w_liq   = 0;
            $w_pago  = 0;
            $i       = -1;
            $j       = 0;
            $w_atual = '';
            foreach($RS_Parc as $row3) {
              if ($w_atual!=f($row3,'dt_lancamento')) {
                $i += 1;
                if(nvl(f($row3,'inicio'),'')!='') {
                  if (is_array($w_cancelamento)) {
                    foreach($w_cancelamento as $k => $v) {
                      if ($k > f($row3,'inicio') && $k < f($row3,'fim')) {
                        foreach($v as $k1 => $v1) {
                          $w_linha[$i][0] = '<img src="'.$conImgCancel.'" border=0 width=10 heigth=15 align="center" title="Cancelamento de valor de nota!">';
                          $w_linha[$i][1] = 'NCE';
                          $w_linha[$i][2] = formataDataEdicao($k,5);
                          $w_linha[$i][3] = '&nbsp;';
                          $w_linha[$i][4] = '&nbsp;';
                          $w_linha[$i][5] = '&nbsp;';
                          $w_linha[$i][6] = '&nbsp;';
                          $w_linha[$i][7] = '&nbsp;';
                          $w_linha[$i][8+$w_nota[$k1]] = $w_cancelamento[$k][$k1];
                        }
                        $i += 1;
                        unset($w_cancelamento[$k]);
                      }
                    }
                  }
                }

                if (Nvl($w_sg_tramite,'-')=='CR' && $w_fim-f($row3,'vencimento')<0) {
                  $w_linha[$i][0] = '           <img src="'.$conImgCancel.'" border=0 width=10 heigth=15 align="center" title="Parcela cancelada!">';
                } elseif (Nvl(f($row3,'quitacao'),'nulo')=='nulo') {
                  if (f($row3,'vencimento')<addDays(time(),-1))  {
                    $w_linha[$i][0] = '           <img src="'.$conImgAtraso.'" border=0 width=10 heigth=15 align="center">';
                  } elseif (f($row3,'vencimento')-addDays(time(),-1)<=5) {
                    $w_linha[$i][0] = '           <img src="'.$conImgAviso.'" border=0 width=10 height=15 align="center">';
                  } else {
                    $w_linha[$i][0] = '           <img src="'.$conImgNormal.'" border=0 width=10 height=15 align="center">';
                  } 
                } else {
                  if (f($row3,'quitacao')>f($row3,'vencimento')) {
                    $w_linha[$i][0] = '           <img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=15 align="center">';
                  } else {
                    $w_linha[$i][0] = '           <img src="'.$conImgOkNormal.'" border=0 width=10 height=15 align="center">';
                  } 
                } 
                $w_linha[$i][0] .= f($row3,'ordem');
  
                // Demais colunas
                if(nvl(f($row3,'inicio'),'')!='') {
                  $w_linha[$i][1] = FormataDataEdicao(nvl(f($row3,'referencia_inicio'),f($row3,'inicio')),5).' a '.FormataDataEdicao(nvl(f($row3,'referencia_fim'),f($row3,'fim')),5);
                } else {
                  $w_linha[$i][1] = '---';
                }
                $w_linha[$i][2] = FormataDataEdicao(f($row3,'vencimento'),5);
                $w_linha[$i][3] = formatNumber(f($row3,'valor'));
                if (Nvl(f($row3,'cd_lancamento'),'')>'') {
                  if($w_tipo!='WORD') $w_linha[$i][4] = '<A class="hl" HREF="mod_fn/lancamento.php?par=Visual&O=L&w_chave='.f($row3,'sq_lancamento').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG=FN'.substr($SG,2,1).'CONT" title="Exibe as informações do lançamento." target="Lancamento">'.nvl(f($row3,'processo'),f($row3,'cd_lancamento')).'</a>';
                  else                $w_linha[$i][4] = nvl(f($row3,'processo'),f($row3,'cd_lancamento'));
                  $w_linha[$i][5] = formatNumber(f($row3,'vl_lancamento'));
                  $w_linha[$i][6] = FormataDataEdicao(f($row3,'dt_lancamento'),5);
                  $w_linha[$i][7] = Nvl(FormataDataEdicao(f($row3,'quitacao'),5),'---');
                  if (Nvl(f($row3,'dt_lancamento'),'nulo') <> 'nulo') $w_liq += f($row3,'vl_lancamento');
                  if (Nvl(f($row3,'quitacao'),'nulo') <> 'nulo') $w_pago += f($row3,'vl_lancamento');
                } else {
                  $w_linha[$i][4] = '&nbsp;';
                  $w_linha[$i][5] = '&nbsp;';
                  $w_linha[$i][6] = '&nbsp;';
                  $w_linha[$i][7] = '&nbsp;';
                } 
                $w_total += f($row3,'valor');
                $j  = 8;
                $w_atual = f($row3,'dt_lancamento');
              }
              if (nvl(f($row3,'sq_acordo_nota'),'')!='') {
                if (f($row3,'abrange_inicial')=='S')   $w_linha[$i][$j+$w_nota[f($row3,'sq_acordo_nota')]] = f($row3,'valor_inicial');
                if (f($row3,'abrange_acrescimo')=='S') $w_linha[$i][$j+$w_nota[f($row3,'sq_acordo_nota')]] = f($row3,'valor_excedente');
                if (f($row3,'abrange_reajuste')=='S')  $w_linha[$i][$j+$w_nota[f($row3,'sq_acordo_nota')]] = f($row3,'valor_reajuste');
              }
            }
            if (is_array($w_cancelamento)) {
              foreach($w_cancelamento as $k => $v) {
                $i += 1;
                foreach($v as $k1 => $v1) {
                  $w_linha[$i][0] = '<img src="'.$conImgCancel.'" border=0 width=10 heigth=15 align="center" title="Cancelamento de valor de nota!">';
                  $w_linha[$i][1] = 'NCE';
                  $w_linha[$i][2] = formataDataEdicao($k,5);
                  $w_linha[$i][3] = '&nbsp;';
                  $w_linha[$i][4] = '&nbsp;';
                  $w_linha[$i][5] = '&nbsp;';
                  $w_linha[$i][6] = '&nbsp;';
                  $w_linha[$i][7] = '&nbsp;';
                  $w_linha[$i][8+$w_nota[$k1]] = $w_cancelamento[$k][$k1];
                }
                unset($w_cancelamento[$k]);
              }
            }
            // Imprime tabela de parcelas
            ShowHTML('      <tr><td colspan="4"><div align="center">');
            ShowHTML('        <table width=100%  border="1" bordercolor="#00000" cellpadding="0" cellspacing="0">');
            ShowHTML('          <tr align="center" bgColor="#f0f0f0">');
            ShowHTML('            <td rowspan=2 colspan=4><b>Parcelas</b></td>');
            ShowHTML('            <td rowspan=2 colspan=2><b>Liquidação</b></td>');
            ShowHTML('            <td rowspan=2 colspan=2><b>Datas de Pagamento</b></td>');
            if (count($w_cab)>0) ShowHTML('            <td colspan="'.count($w_cab).'"><b>Notas</b></td>');
            ShowHTML('          <tr align="center" bgColor="#f0f0f0">');
            for ($k=0; $k<count($w_cab); $k++) ShowHTML('            <td><b>'.$w_cab[$k].'</b></td>');
            ShowHTML('          </tr>');
            ShowHTML('          <tr align="center" bgColor="#f0f0f0">');
            ShowHTML('            <td><b>Nº</b></td>');
            ShowHTML('            <td><b>Período</b></td>');
            ShowHTML('            <td><b>Vencimento</b></td>');
            ShowHTML('            <td><b>Valor</b></td>');
            ShowHTML('            <td><b>Lançamento</b></td>');
            ShowHTML('            <td><b>Valor</b></td>');
            ShowHTML('            <td><b>Prevista</b></td>');
            ShowHTML('            <td><b>Realizada</b></td>');
            for ($k=0; $k<count($w_cab1); $k++) ShowHTML('            <td align="right" nowrap><b>'.$w_cab1[$k].'</b></td>');
            ShowHTML('          </tr>');
            for ($k=0; $k<=$i; $k++) {
              ShowHTML('        <tr valign="top">');
              ShowHTML('          <td align="center" nowrap><font size="1">'.$w_linha[$k][0]);
              ShowHTML('          <td align="center"><font size="1">'.$w_linha[$k][1]);
              ShowHTML('          <td align="center"><font size="1">'.$w_linha[$k][2]);
              ShowHTML('          <td align="right"><font size="1">'.$w_linha[$k][3]);
              ShowHTML('          <td align="center"><font size="1">'.$w_linha[$k][4]);
              ShowHTML('          <td align="right"><font size="1">'.$w_linha[$k][5]);
              ShowHTML('          <td align="center"><font size="1">'.$w_linha[$k][6]);
              ShowHTML('          <td align="center"><font size="1">'.$w_linha[$k][7]);
              for ($l=0; $l<count($w_cab); $l++) {
                if (nvl($w_linha[$k][8+$l],0)>0) {
                  if ($w_linha[$k][3]=='&nbsp;') {
                    ShowHTML('          <td align="right"><font size="1">('.formatNumber($w_linha[$k][8+$l]).')');
                  } else {
                    ShowHTML('          <td align="right"><font size="1">'.formatNumber($w_linha[$k][8+$l]).'&nbsp;');
                  }
                  $w_saldo[$l] += $w_linha[$k][8+$l];
                } else {
                  ShowHTML('          <td align="right"><font size="1">&nbsp;');
                }
              }
            } 
            ShowHTML('      <tr valign="top">');
            ShowHTML('        <td align="right" colspan=3><font size="1"><b>Previsto&nbsp;</b></td>');
            ShowHTML('        <td align="right"><font size="1"><b>'.formatNumber($w_total).'</b></td>');
            ShowHTML('        <td align="right"><font size="1"><b>Liquidado&nbsp;</b></td>');
            ShowHTML('        <td align="right"><font size="1"><b>'.formatNumber($w_liq).'</b></td>');
            ShowHTML('        <td align="right"><font size="1"><b>Pago&nbsp;</b></td>');
            ShowHTML('        <td align="right"><font size="1"><b>'.formatNumber($w_pago).'</b></td>');
            for ($l=0; $l<count($w_cab); $l++) {
              ShowHTML('          <td align="right"><font size="1"><b>'.formatNumber(round(($w_valor[$l]-$w_saldo[$l]),2)).'&nbsp;');
            }
            ShowHTML('      </tr>');
            ShowHTML('         </table></td></tr>');
          }         
        }
        $w_acordo_atual = f($row,'sq_siw_solicitacao');
      }
      ShowHTML('   <tr><td colspan="4"><hr NOSHADE color=#000000 size=4></td></tr>');
    }
  } elseif ($O=='P') {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<TITLE>Demostrativo jurídico/financeiro</TITLE>');
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    Validate('w_acordo','Acordo','SELECT','','1','18','1','1');
    Validate('w_ano','Ano','SELECT','1','1','4','1','1');
    //Validate('p_inicio','Data inicial de vigência','DATA','',10,10,'','0123456789/');
    //Validate('p_fim','Data final de vigência','DATA','',10,10,'','0123456789/');
    //CompData('p_inicio','Data inicial do período de reporte','<=','p_fim','Data final do período de reporte');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=\'document.Form.w_acordo.focus()\';');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Relatorio',$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    SelecaoAcordo(f($RS_Menu,'nome').':',null,null,$w_cliente,$w_acordo,f($RS_Menu,'sq_menu'),'w_acordo',f($RS_Menu,'sigla'),null);
    ShowHTML('          </table>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    SelecaoAno('<u>A</u>no:','A','Selecione o ano para o relatório de resumo geral.',$w_ano,null,'w_ano',null,null,'5');
    //ShowHTML('        <td><b><u>V</u>igência:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_inicio').' a ');
    //ShowHTML('                                                 <input '.$w_Disabled.' accesskey="V" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').'</td>');
    ShowHTML('          </table>');
    ShowHTML('    <table width="90%" border="0">');
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
  if ($w_tipo!='WORD') Rodape();
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'DEMONSTRATIVO': Demonstrativo(); break;
    default:
      cabecalho();
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
  } 
}
?>