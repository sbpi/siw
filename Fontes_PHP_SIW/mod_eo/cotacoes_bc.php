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
include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
include_once($w_dir_volta.'classes/sp/db_getMoedaCotacao.php');
include_once($w_dir_volta.'classes/ws/ws_getBacen.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_CotacaoBacen.php');

// =========================================================================
//  /cotacoes_bc.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Importação de cotações de moedas do Banco Central do Brasil.
// Mail     : alex@sbpi.com.br
// Criacao  : 03/03/2015 10:36
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
$w_pagina = 'cotacoes_bc.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_eo/';
$w_troca = $_REQUEST['w_troca'];
$w_embed = '';

$p_inicio = $_REQUEST['p_inicio'];
$p_fim = $_REQUEST['p_fim'];
if (is_array($_REQUEST['p_serie'])) {
  $itens = $_REQUEST['p_serie'];
} else {
  $itens = explode(',', $_REQUEST['p_serie']);
}

$p_moeda = $_REQUEST['p_moeda'];
$p_ordena = lower($_REQUEST['p_ordena']);

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (nvl($O,'')=='' && $par == 'INICIAL') $O = 'P';

$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);
$w_TP = retornaTitulo($TP,$O);

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Importação de cotações de moedas do Banco Central do Brasil.
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  global $w_embed;
  
  if ($w_troca>'' && strpos('EDT',$O)===false) {
    // Se for recarga da página
    $w_atualiza = $_REQUEST['w_atualiza'];
  }

  $w_tipo = $_REQUEST['w_tipo'];
  $w_tipo = $_REQUEST['w_tipo'];
  $w_sq_pessoa = upper(trim($_REQUEST['w_sq_pessoa']));
  
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - '.f($RS_Menu,'nome').'</TITLE>');
  ScriptOpen('JavaScript');
  if ($O=='P') {
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=1; i < theForm["p_serie[]"].length; i++) {');
    ShowHTML('    if (theForm["p_serie[]"][i].checked) {');
    ShowHTML('       w_erro=false; ');
    ShowHTML('       break; ');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert("Você deve selecionar pelo menos série para importação!"); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    Validate('p_inicio', 'Início', 'DATA', '1', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Fim', 'DATA', '1', '10', '10', '', '0123456789/');
    CompData('p_inicio', 'Pagamento inicial', '<=', 'p_fim', 'Pagamento final');
    ShowHTML('  document.getElementById("form-data").style.display = "none";');
    ShowHTML('  document.getElementById("progress").style.display = "block";');
    ValidateClose();
  } else {
    ValidateOpen('Validacao');
    // Verifica se o período selecionado pode ser passado para a rotina de gravação.
    ShowHTML('  var form_vars = document.Form.elements.length;');
    ShowHTML('  var max_vars  = '.ini_get('max_input_vars').';');
    ShowHTML('  if (form_vars>max_vars) {');
    ShowHTML('    alert("Total de valores a serem atualizados é superior ao limite do servidor!\nReduza-o a, no máximo, "+ Math.floor(100*max_vars/form_vars)+"% da quantidade atual reduzindo o período ou desmarcando algumas das cotações.");');
    ShowHTML('    return false;');
    ShowHTML('  }');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','15','1','1');
    ShowHTML('  document.getElementById("form-data1").style.display = "none";');
    ShowHTML('  document.getElementById("progress1").style.display = "block";');
    ValidateClose();
  }
  ScriptClose();

  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</HEAD>');
  
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='P') {
    BodyOpen('onLoad="document.Form.p_inicio.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  }

  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($O=='L') {
    ShowHTML('<div class="progress" id="progress" align="center"><img src="images/ajax-loaderback-med.gif" />');
    ShowHTML('  <blockquote>Processando...</blockquote>');
    ShowHTML('</div>');
    flush();
    ShowHTML('<div class="form-data" style="display:none;" id="form-data" align="center">');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr><td>');

    // Recupera códigos das séries do BACEN e os valores das cotações já existentes
    $SQL = new db_getMoeda; $RS = $SQL->getInstanceOf($dbms,null,'PDRB',null,'S',null,null);
    $i = 0;
    $w_array = array();
    $sql = new db_getMoedaCotacao;
    foreach($RS as $row) {
      if (f($row,'bc_serie_compra')!='' && in_array(f($row,'bc_serie_compra'),$itens)) {
        $w_series[f($row,'bc_serie_compra')]['chave'] = f($row,'sq_moeda');
        $w_series[f($row,'bc_serie_compra')]['nome'] = f($row,'nome');
        $w_series[f($row,'bc_serie_compra')]['sigla'] = f($row,'sigla');
        $w_series[f($row,'bc_serie_compra')]['tipo'] = 'V';
        $w_series[f($row,'bc_serie_compra')]['tipo'] = 'C';
        $w_moedas[$i] = f($row,'sq_moeda'); $i++;
        $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,f($row,'sq_moeda'),$p_inicio,$p_fim,null);
        $RS1 = SortArray($RS1,'sq_moeda','desc','data','asc');
        foreach($RS1 as $row1) {
          $w_array[f($row1,'data')][f($row1,'bc_serie_compra')]['atual'] = f($row1,'taxa_compra');
        }
      }
      if (f($row,'bc_serie_venda')!='' && in_array(f($row,'bc_serie_venda'),$itens))  {
        $w_series[f($row,'bc_serie_venda')]['chave'] = f($row,'sq_moeda');
        $w_series[f($row,'bc_serie_venda')]['nome'] = f($row,'nome');
        $w_series[f($row,'bc_serie_venda')]['sigla'] = f($row,'sigla');
        $w_series[f($row,'bc_serie_venda')]['tipo'] = 'V';
        $w_moedas[$i] = f($row,'sq_moeda'); $i++;
        $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,f($row,'sq_moeda'),$p_inicio,$p_fim,null);
        $RS1 = SortArray($RS1,'sq_moeda','desc','data','asc');
        foreach($RS1 as $row1) {
          $w_array[f($row1,'data')][f($row1,'bc_serie_venda')]['atual'] = f($row1,'taxa_venda');
        }
      }
    }
    
    // Monta array com os códigos das séries a serem pesquisadas.
    $w_lista_series = array();
    foreach($w_series as $k => $v) array_push($w_lista_series,$k);
    
    // Carrega array com os resultados do web service.
    $ws = new ws_getBacen(); $ws_array = $ws->GetValoresSeriesXML($w_lista_series, $p_inicio, $p_fim);
    
    if (!$ws->getError()) {
      // Faz merge do array de cotações com o retornado pelo web service.
      foreach($ws_array as $data => $a) {
        foreach($a as $serie => $v) {        
          if (!isset($w_array[$data][$serie]['atual'])) $w_array[$data][$serie]['atual'] = null;
          $w_array[$data][$serie]['bacen'] = $v;
        }
      }
      ksort($w_array);
      foreach($w_array as $data => $s) {
        $w_util = false;
        // Só e dia útil se pelo menos um elemento do array tiver cotação do BACEN.
        foreach($s as $k => $v) { if (isset($v['bacen'])) $w_util = true; break; }
        if (!$w_util) {
          unset($w_array[$data]);
        }
      }

      // Formulário de apresentação e gravação dos dados
      ShowHTML('<div class="progress" id="progress1" style="display:none;" align="center"><img src="images/ajax-loaderback-med.gif" />');
      ShowHTML('  <blockquote>Processando...</blockquote>');
      ShowHTML('</div>');
      ShowHTML('<div class="form-data" id="form-data1" align="center">');
      ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
      ShowHTML('<tr>');
      ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($w_array));
      ShowHTML('  <tr><td align="center">');
      AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina.$par, $O);
      ShowHTML(MontaFiltro('POST'));
      ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      $i = 0;
      foreach($w_array as $data => $s) {
        if (!$i) {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td rowspan="2"><b>Data</b></td>');
          foreach($w_series as $k => $v) {
            ShowHTML('        <td colspan="2" align="center"><b>'.$w_series[$k]['nome'].' '.(($w_series[$k]['tipo']=='C') ? 'Compra' : 'Venda').'</b></td>');
          }
          ShowHTML('        </tr>');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          foreach($w_series as $k => $v) {
            ShowHTML('        <td align="center"><b>BACEN</b></td>');
            ShowHTML('          <td><b>'.$conSgSistema.'</b></td>');
          }
          ShowHTML('        </tr>');
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $conTrAlternateBgColor : $conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.formataDataEdicao($data).'</td>');
        foreach($w_series as $k => $v) {
          // Identifica com cor amarela diferenças entre os valores do BACEN e os já gravados no sistema.
          if (nvl(formatNumber($v['atual'],4),'-')!='-' && formatNumber($v['bacen'],4)!=nvl(formatNumber($v['atual'],4),'-')) {
            if ($w_cor==$conTrBgColor) $class = ' bgcolor="'.$conTrBgColorLightYellow1.'"'; else $class = ' bgcolor="'.$conTrBgColorLightYellow2.'"'; 
          } else {
            $class = '';
          }

          ShowHTML('        <td align="center"'.$class.'>'.formatNumber($s[$k]['bacen'],4));
          ShowHTML('        <td align="center"'.$class.'>'.nvl(formatNumber($s[$k]['atual'],4),'-').'<span class="remover">');
          ShowHTML('          <input type="hidden" name="w_data['.$i.']" value="'.formataDataEdicao($data).'">');
          ShowHTML('          <input type="hidden" name="w_moeda['.$i.']" value="'.$w_series[$k]['chave'].'">');
          ShowHTML('          <input type="hidden" name="w_tipo['.$i.']" value="'.$w_series[$k]['tipo'].'">');
          ShowHTML('          <input type="hidden" name="w_valor_bacen['.$i.']" value="'.formatNumber($s[$k]['bacen'],4).'">');
          ShowHTML('          <input type="hidden" name="w_valor_atual['.$i.']" value="'.formatNumber($s[$k]['atual'],4).'">');
          ShowHTML('          </span>');
          $i++;
        }
        ShowHTML('      </tr>');
      } 
      ShowHTML('    </table>');
      ShowHTML('    <tr>');
      MontaRadioNS('<b>Se já houver cotação gravada no sistema (coluna '.$conSgSistema.'), deseja sobrepor pela do BACEN?</b>',$w_atualiza,'w_atualiza');
      ShowHTML('    <tr><td><b>'.$_SESSION['LABEL_CAMPO'].':</b><br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
      ShowHTML('    <tr><td align="center"><hr>');
      ShowHTML('         <input class="STB" type="submit" name="Botao" value="Importar cotações">');
      ShowHTML('         <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG.montaFiltro('GET')) . '\';" name="Botao" value="Refazer filtragem">');
      ShowHTML('        </td>');
      ShowHTML('    </tr>');
      ShowHTML('</FORM>');
      ShowHTML('</table>');
      ShowHTML('</div>');
    } else {
      $Err = $ws->getError();
      $w_message = upper(substr($Err['message'],strpos($Err['message'],':')+2));
      if ($w_message=='VALUE(S) NOT FOUND') {
        // Se não foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center"><b>Não foram encontrados registros para os parâmetros informados.</b></td></tr>');
      } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center"><b>ERRO AO REALIZAR A CAPTURA DE DADOS DO WEBSERVICE</b></td></tr>');
        ShowHTML('      <tr><td><b>MENSAGEM:</b> [' . $Err['message'] .']<hr width="50%"></b></td></tr>');
        ShowHTML('      <tr><td><b>REQUEST:</b><br>' . $Err['request'] .'<hr width="50%"></b></td></tr>');
        ShowHTML('      <tr><td><b>RESPONSE:</b><br>' . $Err['response'] .'<hr width="50%"></b></td></tr>');
      }
      ShowHTML('      <tr><td align="center"><br><br><b><a href="'.$w_dir.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.montaFiltro('GET').'">Clique para nova filtragem.</a></b></td></tr>');
    }
    ShowHTML('</table>');
    ShowHTML('</div>');
    ScriptOpen('JavaScript');
    ShowHTML('  document.getElementById("progress").style.display = "none";');
    ShowHTML('  document.getElementById("form-data").style.display = "block";');
    ScriptClose();
  } elseif ($O=='P') {
    ShowHTML('<div class="progress" id="progress" style="display:none;" align="center"><img src="images/ajax-loaderback-med.gif" />');
    ShowHTML('  <blockquote>Processando...</blockquote>');
    ShowHTML('</div>');
    ShowHTML('<div class="form-data" id="form-data" align="center">');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<input type="hidden" name="p_serie[]" value=""></td>');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table width="99%" border="0" bgcolor="' . $conTrBgColor . '">');
    ShowHTML('      <tr>');
    // Recupera códigos das séries do BACEN.
    $SQL = new db_getMoeda; $RS = $SQL->getInstanceOf($dbms,null,'PDRB',null,'S',null,null);
    $RS = sortArray($RS, 'nome', 'asc');
    ShowHTML('      <tr><td><b>Marque as cotações a serem importadas:</b>');
    foreach($RS as $row) {
      if (f($row,'bc_serie_venda')!='')  {
        ShowHTML('          <br><input class="item" type="CHECKBOX" name="p_serie[]" value="' . f($row, 'bc_serie_venda') . '"'.((in_array(f($row, 'bc_serie_venda'), $itens)) ? ' CHECKED' : '').'> '.f($row,'nome').' Venda');
      }
      if (f($row,'bc_serie_compra')!='') {
        ShowHTML('          <br><input class="item" type="CHECKBOX" name="p_serie[]" value="' . f($row, 'bc_serie_compra') . '"'.((in_array(f($row, 'bc_serie_compra'), $itens)) ? ' CHECKED' : '').'> '.f($row,'nome').' Compra');
      }
    }
    ShowHTML('      <tr><td><b><u>P</u>eríodo desejado para importação:</b><br><input ' . $w_Disabled . ' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_inicio . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_inicio') . ' e <input ' . $w_Disabled . ' type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
    ShowHTML('</div>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  AbreSessao();

  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');

  switch ($SG) {
    case 'EOCOTABC':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {

        // O BACEN informa taxas de câmbio apenas para os dias úteis. 
        // A gravação deve assegurar que serão gravadas também as taxas para os dias não-úteis.
        // Por exemplo, a taxa de sexta-feira deve ser replicada para o sábado e o domingo.
        $w_atual = toDate($p_inicio);
        $w_array = array();
        for ($i=0; $i<=count($_POST['w_data'])-1; $i++) {
          $w_data  = date('Y-m-d',toDate($_POST['w_data'][$i]));
          $w_moeda = $_POST['w_moeda'][$i];
          $w_tipo  = $_POST['w_tipo'][$i];
          $w_valor_bacen = $_POST['w_valor_bacen'][$i];
          $w_valor_atual = $_POST['w_valor_atual'][$i];
          $w_array[$w_data][$w_moeda][$w_tipo]['data'] = $_POST['w_data'][$i];
          $w_array[$w_data][$w_moeda][$w_tipo]['bacen'] = $w_valor_bacen;
          $w_array[$w_data][$w_moeda][$w_tipo]['atual'] = $w_valor_atual;
          $w_array[$w_data][$w_moeda][$w_tipo]['util'] = 'S';
        }

        $_curr   = toDate($p_inicio);
        $_last   = toDate($p_fim);
        // Se necessário, ajusta a data final para, no máximo, a data anterior à atual.
        if ($_last>time()) $_last = $_previous = mktime(0, 0, 0, date(m,time()), date(d,time())-1, date(Y,time()));
        while ($_curr<=$_last) {
          $w_atual = date('Y-m-d',$_curr);
          if (!is_array($w_array[$w_atual])) {
            // Carrega data anterior em variável
            $_previous = mktime(0, 0, 0, date(m,$_curr), date(d,$_curr)-1, date(Y,$_curr));
            $w_ant = date('Y-m-d',$_previous);
            if (!is_array($w_array[$w_ant])) {
              // Se não há cotações anteriores no formulário, passa zero para o banco
              // e a rotina de gravação tenta recuperar valor anterior já gravado.
              foreach($w_array as $data => $r_data) {
                foreach($r_data as $moeda => $r_moeda) {
                  foreach($r_moeda as $tipo => $v) {
                    $w_array[$w_atual][$moeda][$tipo]['data'] = formataDataEdicao($_curr);
                    $w_array[$w_atual][$moeda][$tipo]['bacen'] = '0,0000';
                    $w_array[$w_atual][$moeda][$tipo]['atual'] = '0,0000';
                    $w_array[$w_atual][$moeda][$tipo]['util'] = 'N';
                  }
                }
                break;
              }
            } else {
              // Copia valores da data anterior para a atual.
              foreach($w_array[$w_ant] as $moeda => $r_moeda) {
                foreach($r_moeda as $tipo => $v) {
                  $w_array[$w_atual][$moeda][$tipo]['data'] = formataDataEdicao($_curr);
                  $w_array[$w_atual][$moeda][$tipo]['bacen'] = $v['bacen'];
                  $w_array[$w_atual][$moeda][$tipo]['atual'] = $v['atual'];
                  $w_array[$w_atual][$moeda][$tipo]['util'] = 'N';
                }
              }
            }
          }
          // Incrementa para o próximo dia
          $_curr = mktime(0, 0, 0, date(m,$_curr), date(d,$_curr)+1, date(Y,$_curr));
        }
        ksort($w_array);
        
        // Grava o array
        foreach($w_array as $data => $r_data) {
          foreach($r_data as $moeda => $r_moeda) {
            foreach($r_moeda as $tipo => $v) {
              if ($v['util']=='N' || $_POST['w_atualiza']=='S' || ($_POST['w_atualiza']=='N' && $v['bacen']!=nvl($v['atual'],'-'))) {
                // Configura valor a ser gravado.
                if ($_POST['w_atualiza']=='S') $w_valor = $v['bacen'];
                else $w_valor = nvl($v['atual'],$v['bacen']);

                // Grava o valor
                $SQL = new dml_CotacaoBacen;
                $SQL->getInstanceOf($dbms, $w_cliente, $moeda, $v['data'], $tipo, $w_valor);
              }
            }
          }
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 

      break;
  }
  rodape();
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL': Inicial(); break;
    case 'GRAVA':   Grava();   break;
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
