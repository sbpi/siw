<?php
header('Expires: ' . -1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getEspecieDocumento_PA.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PA.php');
include_once($w_dir_volta.'classes/sp/db_getNaturezaDoc_PA.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getCaixa.php');
include_once($w_dir_volta.'classes/sp/db_getAssunto_PA.php');
include_once($w_dir_volta.'classes/sp/db_getProtocolo.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putCaixaDevolucao.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoVincula.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoAutua.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoAnexa.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoJunta.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoDesm.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoArqSet.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoArqCen.php');
include_once($w_dir_volta.'funcoes/selecaoNaturezaDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoGuarda.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');
include_once($w_dir_volta.'funcoes/selecaoAssuntoRadio.php');
include_once($w_dir_volta.'funcoes/selecaoCaixa.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoArquivoLocalSubordination.php');
include_once('visualdocumento.php');
include_once('visualGR.php');

// =========================================================================
//  /processo.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Contém rotinas para operação com processos
// Mail     : alex@sbpi.com.br
// Criacao  : 28/02/2007, 15:18
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
$w_pagina = 'processo.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_pa/';
$w_troca = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$p_ordena = lower($_REQUEST['p_ordena']);

if (strpos('PADTRANSF', $SG) !== false) {
  if ($O!='P' && $O != 'I' && $O != 'E' && nvl($_REQUEST['w_chave_aux'], $_REQUEST['w_sq_pessoa']) == '') {
    $O = 'L';
  }
} elseif ($O == '') {
  $O = 'P';
}

switch ($O) {
  case 'I': $w_TP = $TP . ' - Inclusão';    break;
  case 'A': $w_TP = $TP . ' - Alteração';   break;
  case 'E': $w_TP = $TP . ' - Exclusão';    break;
  case 'P': $w_TP = $TP . ' - Filtragem';   break;
  case 'C': $w_TP = $TP . ' - Cópia';       break;
  case 'V': $w_TP = $TP . ' - Envio';       break;
  case 'M': $w_TP = $TP . ' - Serviços';    break;
  case 'H': $w_TP = $TP . ' - Herança';     break;
  case 'T': $w_TP = $TP . ' - Ativar';      break;
  case 'D': $w_TP = $TP . ' - Desativar';   break;
  default: $w_TP = $TP . ' - Listagem';     break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, 'PADCAD');
$w_ano = RetornaAno();

$p_numero_doc       = upper($_REQUEST['p_numero_doc']);
$p_atividade        = upper($_REQUEST['p_atividade']);
$p_ativo            = upper($_REQUEST['p_ativo']);
$p_solicitante      = upper($_REQUEST['p_solicitante']);
$p_prioridade       = upper($_REQUEST['p_prioridade']);
$p_unidade          = upper($_REQUEST['p_unidade']);
$p_proponente       = upper($_REQUEST['p_proponente']);
$p_ini_i            = upper($_REQUEST['p_ini_i']);
$p_ini_f            = upper($_REQUEST['p_ini_f']);
$p_fim_i            = upper($_REQUEST['p_fim_i']);
$p_fim_f            = upper($_REQUEST['p_fim_f']);
$p_atraso           = upper($_REQUEST['p_atraso']);
$p_assunto          = upper($_REQUEST['p_assunto']);
$p_pais             = upper($_REQUEST['p_pais']);
$p_regiao           = upper($_REQUEST['p_regiao']);
$p_uf               = upper($_REQUEST['p_uf']);
$p_tipo             = upper($_REQUEST['p_tipo']);
$p_cidade           = upper($_REQUEST['p_cidade']);
$p_usu_resp         = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp        = upper($_REQUEST['p_uorg_resp']);
$p_internas         = upper($_REQUEST['p_internas']);
$p_palavra          = upper($_REQUEST['p_palavra']);
$p_prazo            = upper($_REQUEST['p_prazo']);
$p_fase             = explodeArray($_REQUEST['p_fase']);
$p_sq_acao_ppa      = upper($_REQUEST['p_sq_acao_ppa']);
$p_processo         = upper($_REQUEST['p_processo']);
$p_empenho          = upper($_REQUEST['p_empenho']);

$p_protocolo        = $_REQUEST['p_protocolo'];
$p_chave            = explodeArray($_REQUEST['p_chave']);
if (!is_array($p_chave)) $p_chave = $_REQUEST['p_chave'];
$p_chave_aux        = $_REQUEST['p_chave_aux'];
$p_prefixo          = $_REQUEST['p_prefixo'];
$p_numero           = $_REQUEST['p_numero'];
$p_ano              = $_REQUEST['p_ano'];
$p_unid_autua       = $_REQUEST['p_unid_autua'];
$p_unid_posse       = nvl($_REQUEST['p_unid_posse'],$_SESSION['LOTACAO']);
$p_nu_guia          = $_REQUEST['p_nu_guia'];
$p_ano_guia         = $_REQUEST['p_ano_guia'];
$p_ini              = $_REQUEST['p_ini'];
$p_fim              = $_REQUEST['p_fim'];
$p_classif          = $_REQUEST['p_classif'];
$p_classif_nm       = substr($_REQUEST['p_classif_nm'],0,strpos($_REQUEST['p_classif_nm'],' '));
$p_caixa            = $_REQUEST['p_caixa'];
$p_unidade          = $_REQUEST['p_unidade'];
$p_tipo_despacho    = nvl($_REQUEST['w_tipo_despacho'], $_REQUEST['p_tipo_despacho']);
$p_detalhamento     = $_REQUEST['p_detalhamento'];
if (nvl($p_classif, '') != '') {
  $sql = new db_getAssunto_PA;
  $RS_Assunto = $sql->getInstanceOf($dbms, $w_cliente, $p_classif, null, null, null, null, null, null, null, null, 'REGISTROS');
  foreach ($RS_Assunto as $row) { $RS_Assunto = $row; break; }
  $p_sq_acao_ppa = f($row,'codigo');
}

$sql = new db_getMenuData;
$RS_Menu = $sql->getInstanceOf($dbms, $w_menu);

$sql = new db_getParametro;
$RS_Parametro = $sql->getInstanceOf($dbms, $w_cliente, 'PA', null);
foreach ($RS_Parametro as $row) {
  $RS_Parametro = $row;
  break;
}

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Controla funcionalidades do módulo de protocolo
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;

  switch ($SG) {
    case 'PADAUTUA':    $w_nm_operacao = 'Autuar';              $w_rotina = 'Autuar';     break;
    case 'PADANEXA':    $w_nm_operacao = 'Anexar';              $w_rotina = 'Anexar';     break;
    case 'PADJUNTA':    $w_nm_operacao = 'Apensar';             $w_rotina = 'Apensar';    break;
    case 'PADVINCULA':  $w_nm_operacao = 'Vincular processos';  $w_rotina = 'Vincular';   break;
    case 'PADTRANSF':   $w_nm_operacao = 'Arquivar';            $w_rotina = 'Arquivar';   break;
    case 'PADELIM':     $w_nm_operacao = 'Eliminar';            $w_rotina = 'Eliminar';   break;
    case 'PADARQ':      $w_nm_operacao = 'Arquivar';            $w_rotina = 'ArqCentral'; break;
    case 'PADEMPREST':  $w_nm_operacao = 'Emprestar';           $w_rotina = 'Emprestar';  break;
    case 'PADDESM':     $w_nm_operacao = 'Desmembrar';          $w_rotina = 'Desmembrar'; break;
    case 'PADALTREG':   $w_nm_operacao = 'Alterar';             $w_rotina = 'Alterar';    break;
  }

  if ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getProtocolo;
    $RS = $sql->getInstanceOf($dbms, $w_menu, $w_usuario, $SG, $p_chave, $p_chave_aux, $p_prefixo, $p_numero, $p_ano, 
                  $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2, $p_tipo_despacho, $p_empenho, 
                  $p_solicitante, $p_unidade, $p_proponente, $p_sq_acao_ppa, $p_assunto, $p_processo);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'sg_unidade', 'asc', 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    } else {
      $RS = SortArray($RS, 'sg_unidade', 'asc', 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    }
  }
  Cabecalho();
  head();
  if ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
    Validate('p_numero', 'Número', '1', '', '1', '6', '', '0123456789');
    Validate('p_ano', 'Ano', '1', '', '4', '4', '', '0123456789');
    Validate('p_unid_posse', 'Unidade de posse', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('p_proponente', 'Origem externa', '', '', '2', '90', '1', '');
    //Validate('p_sq_acao_ppa', 'Código do assunto', 'HIDDEN', '', '1', '10', '1', '1');
    Validate('p_assunto', 'Detalhamento do assunto', '', '', '4', '90', '1', '1');
    Validate('p_processo', 'Interessado', '', '', '2', '90', '1', '1');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Selecione o documento desejado, clicando sobre a operação <i>' . $w_nm_operacao . '</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    //ShowHTML('          <td rowspan=2><b>'.linkOrdena('Último despacho','nm_despacho').'</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Protocolo', 'protocolo') . '</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Unidade de Origem', 'nm_unid_origem') . '</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Limite', 'fim') . '</td>');
    ShowHTML('          <td class="remover" rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>' . linkOrdena('Espécie', 'nm_especie') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Nº', 'numero_original') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Data', 'inicio') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Procedência', 'nm_origem_doc') . '</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=9 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        //ShowHTML('        <td>'.f($row,'nm_despacho').'</td>');
        ShowHTML('        <td align="center"><A class="HL" HREF="' . $w_dir . 'documento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
        ShowHTML('        <td>' . f($row, 'nm_unid_origem') . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
        ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
        ShowHTML('        <td align="center">' . date(d . '/' . m . '/' . y, f($row, 'inicio')) . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
        ShowHTML('        <td align="center">' . ((nvl(f($row, 'fim'), '') != '') ? date(d . '/' . m . '/' . y, f($row, 'fim')) : '&nbsp;') . '</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        if ($SG == 'PADALTREG') {
          if (nvl(f($row,'copias'),0)==0) {
            ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $w_rotina . '&R=' . $w_pagina . $par . '&O=A&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '">' . $w_nm_operacao . '</A>&nbsp');
          } else {
            ShowHTML('          <A class="HL" HREF="" onClick="alert(\'Não é possível alterar cópia. Altere o protocolo original!\'); return false;" title="Não é possível alterar cópia. Altere o protocolo original.">' . $w_nm_operacao . '</a>&nbsp;');
          }
          if (nvl(f($row,'qtd_vinculado'),0)==0) {
            ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $w_rotina . '&R=' . $w_pagina . $par . '&O=E&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '">Excluir</A>&nbsp');
          } else {
            ShowHTML('          <A class="HL" HREF="" onClick="alert(\'Não é possível excluir protocolo com cópias. Exclua primeiro as cópias!\'); return false;" title="Exclua primeiro as cópias deste protocolo.">Excluir</a>&nbsp;');
          }
      } else {
          ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $w_rotina . '&R=' . $w_pagina . $par . '&O=A&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '">' . $w_nm_operacao . '</A>&nbsp');
    }
    ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=' . $O . '&P1=' . $P1 . '&P2=' . $P2 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </ul><b>Condições para ter acesso ao protocolo</b>: (pelo menos uma delas deve ser satisfeita)<ul>');
    ShowHTML('  <li>O usuário deve ter registrado o protocolo, que deve estar na sua unidade de lotação ou em qualquer unidade gerida por ele;');
    ShowHTML('  <li>O protocolo deve estar na unidade indicada como "Procedência" do documento original e o usuário deve estar lotado nessa unidade;');
    ShowHTML('  <li>O protocolo é uma solicitação de viagem e sua posse é do setor de viagens;');
    ShowHTML('  <li>Foi indicado pelo menos um critério de busca e o usuário é gestor do sistema ou gestor do módulo de protocolo e arquivo.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que detém a posse do protocolo:', 'U', 'Selecione a unidade de posse.', $p_unid_posse, $w_usuario, 'p_unid_posse', 'CADPA', null);
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="' . $p_empenho . '">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $p_solicitante, null, 'p_solicitante', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_ini') . ' e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:', 'O', null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="' . $p_proponente . '"></td>');
    ShowHTML('      <tr valign="top">');
//    ShowHTML('          <td><b>Código do <U>a</U>ssunto:<br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="p_sq_acao_ppa" size="10" maxlength="10" value="'.$p_sq_acao_ppa.'"></td>');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="' . $p_assunto . '"></td>');
//    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" ' . $w_Disabled . ' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="' . $p_processo . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $p_classif, null, 'p_classif', 'FOLHA', null, '2');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de alteração dos dados gerais do protocolo
// -------------------------------------------------------------------------
function Alterar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave    = $_REQUEST['w_chave'];
  $w_tipo     = nvl($_REQUEST['w_tipo'], 'N');
  $w_processo = nvl($_REQUEST['w_processo'], 'N');
  $w_circular = nvl($_REQUEST['w_circular'], 'N');
  $w_readonly = '';
  $w_erro = '';

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_protocolo = $_REQUEST['w_protocolo'];
    $w_doc_original = $_REQUEST['w_doc_original'];
    $w_especie_documento = $_REQUEST['w_especie_documento'];
    $w_natureza_documento = $_REQUEST['w_natureza_documento'];
    $w_data_documento = $_REQUEST['w_data_documento'];
    $w_fim = $_REQUEST['w_fim'];
    $w_interno = $_REQUEST['w_interno'];
    $w_tipo_pessoa = $_REQUEST['w_tipo_pessoa'];
    $w_pais = $_REQUEST['w_pais'];
    $w_uf = $_REQUEST['w_uf'];
    $w_cidade = $_REQUEST['w_cidade'];
    $w_data_recebimento = $_REQUEST['w_data_recebimento'];
    $w_nm_assunto = $_REQUEST['w_nm_assunto'];
    $w_pessoa_interes = $_REQUEST['w_pessoa_interes'];
    $w_assunto = $_REQUEST['w_assunto'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_observacao = $_REQUEST['w_observacao'];
    $w_copias = $_REQUEST['w_copias'];
    $w_volumes = $_REQUEST['w_volumes'];
    $w_un_autuacao = $_REQUEST['w_un_autuacao'];
    $w_dt_autuacao = $_REQUEST['w_dt_autuacao'];
    $w_nm_pessoa_origem = $_REQUEST['w_nm_pessoa_origem'];
    $w_pessoa_origem = $_REQUEST['w_pessoa_origem'];
    $w_sq_unidade = $_REQUEST['w_sq_unidade'];
  } else {
    if (!(strpos('AEV', $O) === false) || $w_copia > '') {
      // Recupera os dados da ação
      if ($w_copia > '') {
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $w_copia, $SG);
      } else {
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
      }
      if (count($RS) > 0) {
        $w_protocolo = f($RS, 'protocolo');
        $w_processo = f($RS, 'processo');
        $w_tipo = f($RS, 'processo');
        $w_doc_original = f($RS, 'numero_original');
        $w_especie_documento = f($RS, 'sq_especie_documento');
        $w_natureza_documento = f($RS, 'sq_natureza_documento');
        $w_data_documento = formataDataEdicao(f($RS, 'inicio'));
        $w_fim = formataDataEdicao(f($RS, 'fim'));
        $w_interno = f($RS, 'interno');
        $w_tipo_pessoa = f($RS, 'sq_tipo_pessoa');
        $w_pais = f($RS, 'sq_pais');
        $w_uf = f($RS, 'co_uf');
        $w_cidade = f($RS, 'sq_cidade_origem');
        $w_data_recebimento = formataDataEdicao(f($RS, 'data_recebimento'));
        $w_pessoa_interes = f($RS, 'pessoa_interes');
        $w_assunto = f($RS, 'sq_assunto');
        $w_descricao = f($RS, 'descricao');
        $w_copias = f($RS, 'copias');
        $w_volumes = f($RS, 'volumes');
        $w_un_autuacao = f($RS, 'unidade_autuacao');
        $w_dt_autuacao = formataDataEdicao(f($RS, 'data_autuacao'));
        $w_nm_pessoa_origem = f($RS, 'nm_pessoa_origem');
        $w_pessoa_origem = f($RS, 'pessoa_origem');
        $w_sq_unidade = f($RS, 'sq_unidade');
      }
    }
  }

  // Configura variáveis de controle para processos e circulares
  if (nvl($w_especie_documento, '') != '') {
    $sql = new db_getEspecieDocumento_PA;
    $RS = $sql->getInstanceOf($dbms, $w_especie_documento, $w_cliente, null, null, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    if (f($RS, 'sigla') == 'PROC') {
      $w_processo = 'S';
      $w_circular = 'N';
    } elseif (strpos(upper(f($RS, 'nome')), 'CIRCULAR') !== false) {
      $w_processo = 'N';
      $w_circular = 'S';
    } else {
      $w_processo = 'N';
      $w_circular = 'N';
    }
    // Carrega assunto padrão do documento
    if (nvl($w_assunto, '') == '')
      $w_assunto = f($RS, 'sq_assunto');
  }

  if (nvl($w_assunto, '') == '') {
    // Só pode haver um registro para classificação provisória
    $sql = new db_getAssunto_PA;
    $RS_Assunto = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, null, null, null, null, null, 'PROVISORIO');
    foreach ($RS_Assunto as $row) {
      $RS_Assunto = $row;
      break;
    }
    if (count($RS_Assunto) > 0)
      $w_assunto = f($RS_Assunto, 'sq_assunto');
  }
  Cabecalho();
  head();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  ShowHTML('function telaDocumento(tipo) {');
  ShowHTML('  document.Form.w_troca.value=\'w_especie_documento\';');
  ShowHTML('  document.Form.action=\'' . $w_dir . $w_pagina . $par . '\';');
  ShowHTML('  document.Form.O.value=\'' . $O . '\';');
  ShowHTML('  document.Form.submit();');
  ShowHTML('}');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O == 'I' || $O == 'A') {
    Validate('w_especie_documento', 'Espécie documental', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_doc_original', 'Nº do documento', '1', '1', 1, 30, '1', '1');
    Validate('w_data_documento', 'Data do documento', 'DATA', '1', 10, 10, '', '0123456789/');
    CompData('w_data_documento', 'Data do documento', '<=', FormataDataEdicao(time()), 'data atual');
    Validate('w_interno', 'Procedência', 'SELECT', 1, 1, 1, 'SN', '');
    if ($w_interno == 'N') {
      Validate('w_pais', 'País', 'SELECT', 1, 1, 18, '', '0123456789');
      Validate('w_uf', 'Estado', 'SELECT', 1, 1, 3, '1', '1');
      Validate('w_cidade', 'Cidade', 'SELECT', 1, 1, 18, '', '0123456789');
      Validate('w_pessoa_origem', 'Pessoa de origem', 'HIDDEN', 1, 1, 18, '', '0123456789');
      Validate('w_pessoa_interes', 'Interessado principal', 'HIDDEN', 1, 1, 18, '', '0123456789');
    } else {
      Validate('w_sq_unidade', 'Unidade de origem', 'SELECT', 1, 1, 18, '', '0123456789');
    }
    Validate('w_data_recebimento', 'Data de criação/recebimento', 'DATA', '1', 10, 10, '', '0123456789/');
    CompData('w_data_recebimento', 'Data de criação/recebimento', '>=', 'w_data_documento', 'Data do documento');
    CompData('w_data_recebimento', 'Data de criação/recebimento', '<=', FormataDataEdicao(time()), 'data atual');
    if ($w_processo == 'S') {
      Validate('w_dt_autuacao', 'Data de autuação', 'DATA', '1', 10, 10, '', '0123456789/');
      Validate('w_volumes', 'Nº de volumes', '1', '1', 1, 18, '', '0123456789');
      CompValor('w_volumes', 'Nº de volumes', '>', 0, 'zero');
    } elseif ($w_circular == 'S') {
      Validate('w_copias', 'Nº de cópias', '1', '1', 1, 18, '', '0123456789');
      CompValor('w_copias', 'Nº de cópias', '>', 2, 'dois');
    }
    Validate('w_fim', 'Data limite para conclusão', 'DATA', '', 10, 10, '', '0123456789/');
    CompData('w_fim', 'Data limite para conclusão', '>=', 'w_data_documento', 'Data do documento');
    Validate('w_assunto', 'Classificação', 'HIDDEN', 1, 1, 18, '', '0123456789');
    Validate('w_descricao', 'Detalhamento do assunto', '1', '1', 1, 2000, '1', '1');
    if ($O == 'E')
      Validate('w_observacao', 'Observações a respeito da exclusão', '1', '1', 1, 2000, '1', '1');
    Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
  } elseif ($O == 'E' || $O == 'A') {
    Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
    ShowHTML('  return(confirm("Confirma cancelamento do protocolo ' . $w_protocolo . '?\nEsta operação não poderá ser desfeita!"));');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif ($O == 'E') {
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  } elseif ($O == 'V') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_especie_documento.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV', $O) === false)) {
    if ($w_pais == '') {
      // Carrega os valores padrão para país, estado e cidade
      $sql = new db_getCustomerData;
      $RS = $sql->getInstanceOf($dbms, $w_cliente);
      $w_pais = f($RS, 'sq_pais');
      $w_uf = f($RS, 'co_uf');
      $w_cidade = f($RS, 'sq_cidade_padrao');
    }
    if ($O == 'E')
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="' . $w_copia . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="' . f($RS_Menu, 'data_hora') . '">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . f($RS_Menu, 'sq_menu') . '">');
    ShowHTML('<INPUT type="hidden" name="w_protocolo" value="' . $w_protocolo . '">');
    ShowHTML('<INPUT type="hidden" name="w_processo" value="' . $w_processo . '">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="' . $w_tipo . '">');
    if ($w_interno == 'S' and $w_processo == 'S') {
      ShowHTML('<INPUT type="hidden" name="w_un_autuacao" value="' . f($RS_Menu, 'sq_unid_executora') . '">');
    }
    ShowHTML('<INPUT type="hidden" name="w_circular" value="' . $w_circular . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    ShowHTML('      <tr><td bgcolor="' . $conTrAlternateBgColor . '" colspan=5 align="center"><font size="2"><b>' . $w_protocolo . '</b></font></td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=5><b>IDENTIFICAÇÃO</b></td></tr>');
    ShowHTML('      <tr valign="top">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $w_especie_documento, null, 'w_especie_documento', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_doc_original\'; document.Form.submit();"');
    ShowHTML('           <td title="Informe o número do documento de origem."><b>Número:</b><br><INPUT ' . $w_Disabled . ' class="STI" type="text" name="w_doc_original" size="20" maxlength="30" value="' . $w_doc_original . '" ></td>');
    ShowHTML('           <td title="Informe a data do documento de origem."><b>D<u>a</u>ta:</b><br><input ' . $w_Disabled . ' accesskey="A" type="text" name="w_data_documento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_data_documento . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data original do documento.">' . ExibeCalendario('Form', 'w_data_documento') . '</td>');
    selecaoOrigem('<u>O</u>rigem:', 'O', 'Indique se a origem é interna ou externa.', $w_interno, null, 'w_interno', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
    ShowHTML('      <tr><td colspan=5>&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4><b>PROCEDÊNCIA</b></td></tr>');
    ShowHTML('        <tr valign="top">');
    if ($w_interno == 'N') {
      ShowHTML('       <tr valign="top">');
      SelecaoPais('<u>P</u>aís:', 'P', "Selecione o país de procedência.", $w_pais, null, 'w_pais', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
      SelecaoEstado('E<u>s</u>tado:', 'S', "Selecione o estado de procedência.", $w_uf, $w_pais, null, 'w_uf', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
      ShowHTML('          <td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoCidade('<u>C</u>idade:', 'C', "Selecione a cidade de procedência.", $w_cidade, $w_pais, $w_uf, 'w_cidade', null, null);
      ShowHTML('           </table>');
      ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoPessoaOrigem('<u>P</u>essoa de origem:', 'P', 'Clique na lupa para selecionar a pessoa de origem.', $w_pessoa_origem, null, 'w_pessoa_origem', null, null, null);
      ShowHTML('           </table>');
      ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoPessoaOrigem('<u>I</u>nteressado principal:', 'I', 'Clique na lupa para selecionar o interessado principal.', $w_pessoa_interes, null, 'w_pessoa_interes', null, null, null);
      ShowHTML('           </table>');
    } else {
      ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoUnidade('<U>U</U>nidade de origem:', 'U', 'Selecione a unidade de origem.', nvl($w_sq_unidade, $_SESSION['LOTACAO']), null, 'w_sq_unidade', 'MOD_PA', null);
      ShowHTML('           </table>');
    }
    ShowHTML('      <tr><td colspan=5>&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=5><b>DADOS COMPLEMENTARES</b></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('           <td valign="top" title="Informe a data de criação ou de recebimento."><b><u>D</u>ata de criação/recebimento:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="w_data_recebimento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . nvl($w_data_recebimento, $w_data_documento) . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_data_recebimento') . '</td>');
    if ($w_processo == 'S') {
      ShowHTML('           <td title="Data de autuação do processo."><b>Data de autuação:</b><br><input ' . $w_Disabled . ' type="text" name="w_dt_autuacao" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_dt_autuacao . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_fim') . '</td>');
      ShowHTML('           <td title="Informe quantos volumes compõem o processo."><b>Nº de volumes:</b><br><INPUT ' . $w_Disabled . ' class="STI" type="text" name="w_volumes" size="3" maxlength="3" value="' . $w_volumes . '" ></td>');
    } elseif ($w_circular == 'S') {
      ShowHTML('           <td title="Informe o número de cópias da circular."><b>Nº de cópias:</b><br><INPUT ' . $w_Disabled . ' class="STI" type="text" name="w_copias" size="5" maxlength="18" value="' . $w_copias . '" ></td>');
    }
    selecaoNaturezaDocumento('<u>N</u>atureza:', 'N', 'Indique a natureza do documento.', $w_natureza_documento, null, 'w_natureza_documento', null, null);
    ShowHTML('           <td title="OPCIONAL. Limite para término da tramitação do documento."><b>Data limite:</b><br><input ' . $w_Disabled . ' type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_fim') . '</td>');
    ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $w_assunto, null, 'w_assunto', 'FOLHA', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_descricao\'; document.Form.submit();"');
    ShowHTML('           </table>');
    if (nvl($w_assunto, '') != '') {
      $sql = new db_getAssunto_PA;
      $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_assunto, null, null, null, null, null, null, null, null, 'REGISTROS');
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=5 align="center">');
      ShowHTML('          <TABLE WIDTH="100%" BORDER="0">');
      ShowHTML('            <tr bgcolor="#DADADA">');
      ShowHTML('              <td><b>Código</td>');
      ShowHTML('              <td><b>Descrição</td>');
      ShowHTML('              <td><b>Detalhamento</td>');
      ShowHTML('              <td><b>Observação</td>');
      ShowHTML('            </tr>');
      foreach ($RS as $row) {
        ShowHTML('            <tr valign="top" bgcolor="#DADADA">');
        ShowHTML('              <td width="1%" nowrap>' . f($row, 'codigo') . '</td>');
        ShowHTML('              <td>');
        ShowHTML('                ' . f($row, 'descricao'));
        if (nvl(f($row, 'ds_assunto_pai'), '') != '') {
          echo '<br>';
          if (nvl(f($row, 'ds_assunto_bis'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_bis')) . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_avo'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_avo')) . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_pai'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_pai')));
        }
        ShowHTML('              <td>' . nvl(f($row, 'detalhamento'), '---') . '</td>');
        ShowHTML('              <td>' . nvl(f($row, 'observacao'), '---') . '</td>');
      }
      ShowHTML('            </table></tr>');
    }
    ShowHTML('      <tr><td colspan="5" title="Descreva de forma objetiva o conteúdo do documento."><b>D<u>e</u>talhamento:</b><br><textarea ' . $w_Disabled . ' accesskey="E" name="w_descricao" class="STI" ROWS=5 cols=75>' . $w_descricao . '</TEXTAREA></td>');
    if ($O == 'E') {
      ShowHTML('    <tr><td colspan=5 align="center"><hr>');
      ShowHTML('      <tr><td colspan="5" title="OPCIONAL. Se desejar, registre observações sobre a exclusão do protocolo."><b><u>O</u>bservações sobre a exclusão:</b><br><textarea ccesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>' . $w_observacao . '</TEXTAREA></td>');
    }
    ShowHTML('    <tr><td colspan=5>&nbsp;</td></tr>');
    ShowHTML('    <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td colspan=5 align="center"><hr>');
    ShowHTML('      <tr><td align="center" colspan="5">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    $sql = new db_getLinkData;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, $SG);
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, f($RS, 'link') . '&O=L&SG=' . f($RS, 'sigla') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
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
// Arquivamento central
// -------------------------------------------------------------------------
function Central() {
  extract($GLOBALS);
  global $w_Disabled;

  if ($O == 'L') {
    $sql = new db_getCaixa;
    $RS = $sql->getInstanceOf($dbms, $p_chave, $w_cliente, $w_usuario, $p_unidade, $p_caixa, null, $p_unid_autua, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, null,null,null,null,$SG);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'sg_unidade', 'asc', 'numero', 'asc', 'pasta', 'asc', 'cd_assunto', 'asc', 'protocolo', 'asc');
    } else {
      $RS = SortArray($RS, 'sg_unidade', 'asc', 'numero', 'asc', 'pasta', 'asc', 'cd_assunto', 'asc', 'protocolo', 'asc');
    }
    $w_existe = count($RS);

    if (count($w_chave) > 0) {
      $i = 0;
      foreach ($w_chave as $k => $v) {
        foreach ($RS as $row) {
          if ($w_chave[$i] == f($row, 'sq_siw_solicitacao')) {
            $w_marcado[f($row, 'sq_siw_solicitacao')] = 'ok';
            break;
          }
        }
        $i++;
      }
      reset($RS);
    }
  }
  Cabecalho();
  head();
  if ($O == 'L') {
    if ($w_existe) {
      ScriptOpen('JavaScript');
      ShowHTML('  $(document).ready(function() {');
      ShowHTML('    $("#marca_todos").click(function() {');
      ShowHTML('      var checked = this.checked;');
      ShowHTML('      $(".item").each(function() {');
      ShowHTML('        this.checked = checked;');
      ShowHTML('      });');
      ShowHTML('    });');
      ShowHTML('  });');
      ValidateOpen('Validacao');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["w_chave[]"].length; i++) {');
      ShowHTML('    if (theForm["w_chave[]"][i].checked) w_erro=false; ');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve informar pelo menos um protocolo!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      Validate('w_local', 'Local de arquivamento', 'SELECT', '', 1, 18, '', '0123456789');
      Validate('w_observacao', 'Motivo da devolução', '1', '', 1, 2000, '1', '1');
      ShowHTML('  if (theForm.w_envio[0].checked && theForm.w_local.selectedIndex==0) {');
      ShowHTML('    alert("Informe o local de armazenamento!"); ');
      ShowHTML('    return false;');
      ShowHTML('  } else if (theForm.w_envio[1].checked && theForm.w_observacao.value=="") {');
      ShowHTML('    alert("Informe o motivo da devolução!"); ');
      ShowHTML('    return false;');
      ShowHTML('  } ');
      Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
      // Se não for encaminhamento
      ShowHTML('  theForm.Botao.disabled=true;');
      ValidateClose();
      ScriptClose();
    }
  } elseif ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }

  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML(montaFiltro('POST'));
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right"><b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    if (count($RS)) {
      ShowHTML('          <td align="center"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></td>');
    } else {
      ShowHTML('          <td align="center">&nbsp;</td>');
    }
    ShowHTML('          <td><b>' . linkOrdena('Caixa', 'numero') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Assunto', 'assunto') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Data Limite', 'data_limite') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Intermediário', 'intermediario') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Destinação Final', 'destinacao_final') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Protocolos', 'qtd') . '</td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=9 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td>');
        ShowHTML('          <INPUT type="hidden" name="w_tramite[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'sq_siw_tramite') . '">');
        ShowHTML('          <INPUT type="hidden" name="w_unid_origem[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'unidade_int_posse') . '">');
        ShowHTML('          <INPUT type="hidden" name="w_unid_autua[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'unidade_autuacao') . '">');
        if (nvl($w_marcado[f($row, 'sq_siw_solicitacao')], '') != '') {
          ShowHTML('          <input class="item" type="CHECKBOX" CHECKED name="w_chave[]" value="' . f($row, 'sq_caixa') . '" ></td>');
        } else {
          ShowHTML('          <input class="item" type="CHECKBOX" name="w_chave[]" value="' . f($row, 'sq_caixa') . '" ></td>');
        }
        ShowHTML('        </td>');
        ShowHTML('        <td><A onclick="window.open (\'' . montaURL_JS($w_dir, 'relatorio.php?par=ConteudoCaixa' . '&R=' . $w_pagina . 'IMPRIMIR' . '&O=L&w_chave=' . f($row, 'sq_caixa') . '&w_formato=HTML&orientacao=PORTRAIT&&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">' . f($row, 'numero') . '/' . f($row, 'sg_unidade') . '</a>&nbsp;');
        ShowHTML('        <td>' . f($row, 'assunto') . '</td>');
        ShowHTML('        <td align="center">' . formataDataEdicao(f($row, 'data_limite')) . '</td>');
        ShowHTML('        <td align="center">' . f($row, 'intermediario') . '</td>');
        ShowHTML('        <td>' . f($row, 'destinacao_final') . '</td>');
        ShowHTML('        <td align="center">' . f($row, 'qtd') . '</td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_existe) {
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td colspan="2" align="center"><br><br>');
      ShowHTML('  <tr><td colspan="2" bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
      ShowHTML('  Orientação:<ul>');
      ShowHTML('  <li>Selecione as caixas desejadas, clique sobre a operação a ser executada.');
      ShowHTML('  <li>Para arquivar a caixa, informe o local de de armazenamento.');
      ShowHTML('  <li>Para devolver a caixa, informe o motivo da devolução. A caixa será devolvida para o arquivo setorial de origem.');
      ShowHTML('  <li>Informe sua assinatura eletrônica e clique sobre o botão para confirmar a operação.');
      ShowHTML('  </ul></b></font></td>');
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td colspan="2" align="center"><br><br>');
      ShowHTML('  <table width="97%" border="0">');
      ShowHTML('  <tr><td colspan="2">');
      ShowHTML('    <tr valign="top"><td width="30%"><input ' . $w_Disabled . ' class="STR" type="radio" name="w_envio" value="N" ' . ((Nvl($w_envio, 'N') == 'N') ? 'checked' : '') . ' onClick="document.Form.w_observacao.value=\'\'; document.Form.Botao.value=\'Arquivar\';"> Arquivar:');
      selecaoArquivoLocalSubordination(null, null, 'Informe a localização da caixa no arquivo central.', $w_local, f($RS_Parametro, 'arquivo_central'), 'w_local', 'FOLHA', null, null, null, null, null, null);
      ShowHTML('    <tr valign="top"><td width="30%"><input ' . $w_Disabled . ' class="STR" type="radio" name="w_envio" value="S" ' . ((Nvl($w_envio, 'N') == 'N') ? '' : 'checked') . '  onClick="document.Form.w_local.selectedIndex=0; document.Form.Botao.value=\'Devolver\';"> Devolver para o Arquivo Setorial:<td title="Descreva de forma objetiva onde o documento encontra-se no arquivo setorial."><textarea ' . $w_Disabled . ' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>' . $w_observacao . '</TEXTAREA></td>');
      ShowHTML('    <tr><td colspan=3>&nbsp;</td></tr>');
      ShowHTML('    <tr><td colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center"><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Arquivar">');
      ShowHTML('      </td>');
      ShowHTML('    </tr>');
      ShowHTML('  </table>');
    }
    ShowHTML('    </FORM>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    SelecaoCaixa('<u>C</u>aixa:', 'C', "Selecione a caixa transferida.", $p_caixa, $w_cliente, null, 'p_caixa', $SG, null);
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade original da caixa:', 'U', 'Selecione a unidade que transferiu a caixa.', $p_unidade, null, 'p_unidade', 'MOD_PA', null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Perío<u>d</u>o entre:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input ' . $w_Disabled . ' accesskey="T" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de visualização da guia de tramitação
// -------------------------------------------------------------------------
function EmitirGR() {
  extract($GLOBALS);
  $w_unidade = nvl($w_unidade, $_REQUEST['w_unidade']);
  $w_nu_guia = nvl($w_nu_guia, $_REQUEST['w_nu_guia']);
  $w_ano_guia = nvl($w_ano_guia, $_REQUEST['w_ano_guia']);

  HeaderWord($_REQUEST['orientacao']);
  ShowHTML(VisualGR($w_unidade, $w_nu_guia, $w_ano_guia));
  Rodape();
}

// =========================================================================
// Rotina de autuação de processos
// -------------------------------------------------------------------------
function Autuar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $sql = new db_getSolicData;
  $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
  if (count($RS) > 0) {
    $w_processo = f($RS, 'processo');
    $w_descricao = nvl($_REQUEST['w_descricao'], f($RS, 'descricao'));
    $w_unidade_autua = nvl($_REQUEST['w_unidade_autua'], f($RS, 'sq_unidade_autua'));
  }

  // Verifica se o documento a ser autuado já é um processo
  if ($w_processo == 'S') {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Este documento já foi autuado.");');
    ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
    ScriptClose();
    exit();
  }

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_unidade_autua', 'Unidade interessada na autuação', 'SELECT', '1', 1, 18, '', '1');
  Validate('w_descricao', 'Detalhamento do assunto', '1', '1', 1, 2000, '1', '1');
  Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
  // Se não for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_protocolo" value="' . f($RS, 'protocolo_completo') . '">');
  ShowHTML('<tr><td bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orientação:<ul>');
  ShowHTML('  <li>Verifique se realmente deseja autuar este documento, transformando-o em processo.');
  ShowHTML('  <li>Leia atentamente os dados que serão registrados para esta autuação e clique no botão "Autuar" para confirmar a operação ou no botão "Abandonar" para voltar à tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO DOCUMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">' . f($RS, 'nm_tipo') . ':<td><b>' . f($RS, 'protocolo') . '</b></td></tr>');
  if (f($RS, 'interno') == 'S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>' . ExibeUnidade('../', $w_cliente, f($RS, 'nm_unid_origem'), f($RS, 'sq_unidade'), $TP) . '</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>' . f($RS, 'nm_pessoa_origem') . '</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>' . f($RS, 'nm_pessoa_interes') . '</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>' . f($RS, 'nm_cidade') . '</td></tr>');
  ShowHTML('   <tr><td>Espécie documental:</td>');
  ShowHTML('       <td>' . f($RS, 'nm_especie') . '</td></tr>');
  ShowHTML('   <tr><td>Número:</td>');
  ShowHTML('       <td>' . f($RS, 'numero_original') . '</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>' . formataDataEdicao(f($RS, 'inicio')) . '</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DA AUTUAÇÃO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data da autuação:<td><b>' . formataDataEdicao(time()) . '</b></td></tr>');
  $sql = new db_getUorgData;
  $RS_Unid = $sql->getInstanceOf($dbms, $_SESSION['LOTACAO']);
  ShowHTML('    <tr><td width="30%">Unidade autuadora:<td><b>' . f($RS_Unid, 'nome') . '</b></td></tr>');
  ShowHTML('    <tr><td width="30%">Usuário autuador:<td><b>' . $_SESSION['NOME'] . '</b></td></tr>');
  SelecaoUnidade('<U>U</U>nidade interessada:', 'U', 'Selecione a unidade interessada na autuação do processo.', $w_unidade_autua, null, 'w_unidade_autua', 'MOD_PA', null, 1, '<td>');
  ShowHTML('    <tr valign="top"><td width="30%"><b>D<u>e</u>talhamento do assunto:</b><td title="Descreva de forma objetiva o conteúdo do documento."><textarea ' . $w_Disabled . ' accesskey="E" name="w_descricao" class="STI" ROWS=5 cols=75>' . $w_descricao . '</TEXTAREA></td>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Autuar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
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
// Rotina de anexação e apensação de documentos e processos
// -------------------------------------------------------------------------
function Juntar() {

  extract($GLOBALS);
  global $w_Disabled;

  $w_assunto = $_REQUEST['w_assunto'];
  $w_chave   = $_REQUEST['w_chave'];

  switch ($SG) {
    case 'PADAUTUA':    $w_nm_operacao = 'Autuar';        break;
    case 'PADANEXA':    $w_nm_operacao = 'Anexar';        break;
    case 'PADJUNTA':    $w_nm_operacao = 'Apensar';       break;
    case 'PADTRANSF':   $w_nm_operacao = 'Arquivar';      break;
    case 'PADELIM':     $w_nm_operacao = 'Eliminar';      break;
    case 'PADARQ':      $w_nm_operacao = 'Arquivar';      break;
    case 'PADEMPREST':  $w_nm_operacao = 'Emprestar';     break;
    case 'PADDESM':     $w_nm_operacao = 'Desmembrar';    break;
    case 'PADALTREG':   $w_nm_operacao = 'Alterar';       break;
  }

  if ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getProtocolo;
    $RS = $sql->getInstanceOf($dbms, $w_menu, $w_usuario, $SG, $p_chave, $p_chave_aux, $p_prefixo, $p_numero, $p_ano, 
                  $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2, $p_tipo_despacho, $p_empenho, 
                  $p_solicitante, $p_unidade, $p_proponente, $p_sq_acao_ppa, $p_assunto, $p_processo);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'sg_unidade', 'asc', 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    } else {
      $RS = SortArray($RS, 'sq_documento_pai', 'asc', 'sg_unidade', 'asc', 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    }
    $w_existe = count($RS);

    if (count($w_chave) > 0) {
      $i = 0;
      foreach ($w_chave as $k => $v) {
        foreach ($RS as $row) {
          if ($w_chave[$i] == f($row, 'sq_siw_solicitacao')) {
            $w_marcado[f($row, 'sq_siw_solicitacao')] = 'ok';
            break;
          }
        }
        $i++;
      }
      reset($RS);
    }
  }



  Cabecalho();
  head();
  if ($O == 'P') {
    ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
    Validate('p_numero', 'Número', '1', '', '1', '6', '', '0123456789');
    Validate('p_ano', 'Ano', '1', '', '4', '4', '', '0123456789');
    Validate('p_unid_posse', 'Unidade de posse', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('p_proponente', 'Origem externa', '', '', '2', '90', '1', '');
    Validate('p_assunto', 'Detalhamento do assunto', '', '', '4', '90', '1', '1');
    Validate('p_processo', 'Interessado', '', '', '2', '90', '1', '1');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } elseif ($w_existe) {
    ScriptOpen('JavaScript');
    ShowHTML('  $(document).ready(function() {');
    ShowHTML('    $("#marca_todos").click(function() {');
    ShowHTML('      var checked = this.checked;');
    ShowHTML('      $(".item").each(function() {');
    ShowHTML('        this.checked = checked;');
    ShowHTML('      });');
    ShowHTML('    });');
    ShowHTML('  });');
    FormataProtocolo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($w_existe) {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
      ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
      ShowHTML('       w_erro=false; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve informar pelo menos um protocolo!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (!confirm(\'Confirma a geração de guia de tramitação APENAS para ' . (($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral')) ? 'as caixas selecionadas' : 'os documentos selecionados') . '?\')) return false;');
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" width="100%">');

  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Selecione os documentos desejados e clique sobre o botão <i>' . $w_nm_operacao . '</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_ordena" value="">');
    ShowHTML(montaFiltro('POST'));
    ShowHTML('    <td align="right"><b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    if ($w_existe) {
      ShowHTML('          <td rowspan="2" align="center"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></td>');
    } else {
      ShowHTML('          <td rowspan="2" align="center">&nbsp;</td>');
    }
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Protocolo Recebedor', 'protocolo_pai', 'Form') . '</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Protocolo a ' . $w_nm_operacao, 'protocolo', 'Form') . '</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Unidade de Origem', 'nm_unid_origem', 'Form') . '</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Limite', 'fim', 'Form') . '</td>');
    //ShowHTML('          <td rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>' . linkOrdena('Espécie', 'nm_especie', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Nº', 'numero_original', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Data', 'inicio', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Procedência', 'nm_origem_doc', 'Form') . '</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=9 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      $w_atual = '';
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td align="center"><input type="CHECKBOX" class="item" ' . ((nvl($w_marcado[f($row, 'sq_siw_solicitacao')], '') != '') ? 'CHECKED' : '') . ' name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '"></td>');
        ShowHTML('        <td align="center"><A class="HL" HREF="' . $w_dir . 'documento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_documento_pai') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo_pai') . '&nbsp;</a>');
        ShowHTML('        <td align="center"><A class="HL" HREF="' . $w_dir . 'documento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
        ShowHTML('        <td>' . f($row, 'nm_unid_origem') . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
        ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
        ShowHTML('        <td align="center">' . date(d . '/' . m . '/' . y, f($row, 'inicio')) . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
        ShowHTML('        <td align="center">' . ((nvl(f($row, 'fim'), '') != '') ? date(d . '/' . m . '/' . y, f($row, 'fim')) : '&nbsp;') . '</td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_existe) {
      ShowHTML('    <tr><td colspan="3">&nbsp;</td></tr>');
      ShowHTML('    <tr><td colspan=3><b>DADOS DA ' . (($SG == 'PADANEXA') ? 'ANEXAÇÃO' : 'APENSAÇÃO') . '</b></td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('    <tr><td width="30%">Data:<td colspan=2><b>' . formataDataEdicao(time()) . '</b></tr>');
      ShowHTML('    <tr><td width="30%">Usuário responsável:<td><b>' . $_SESSION['NOME'] . '</b></td></tr>');
      ShowHTML('    <tr><td width="30%">'.$_SESSION['LABEL_CAMPO'].':<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center"><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="' . $w_nm_operacao . '">');
      ShowHTML('      </td>');
      ShowHTML('    </tr>');
    }
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('  </tr>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todos os protocolos que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que detém a posse do protocolo:', 'U', 'Selecione a unidade de posse.', $p_unid_posse, $w_usuario, 'p_unid_posse', 'CADPA', null);
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="' . $p_empenho . '">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $p_solicitante, null, 'p_solicitante', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_ini') . ' e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:', 'O', null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="' . $p_proponente . '"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="' . $p_assunto . '"></td>');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" ' . $w_Disabled . ' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="' . $p_processo . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $w_assunto, null, 'w_assunto', 'FOLHA', null, '2');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de vinculação de processos
// -------------------------------------------------------------------------
function Vincular() {
  extract($GLOBALS);
  global $w_Disabled;
  // Recupera as variáveis utilizadas na filtragem
  $w_protocolo      = $_REQUEST['w_protocolo'];
  $erro             = '';
  
  if ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getProtocolo; $RS = $sql->getInstanceOf($dbms, $w_menu, $w_usuario, $SG, $p_chave, $p_chave_aux,
                    $p_prefixo, $p_numero, $p_ano, null, null, null, null, null, null, 2, null, null, null, null, 
                    null, null, null, null);
    $RS = SortArray($RS, 'sg_unidade', 'asc', 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    
    if (count($RS)==0) {
      $w_erro = 'Processo '.((nvl($p_prefixo,'')!='') ? $p_prefixo.'.' : '').$p_numero.'/'.$p_ano.' não existe na base de dados!'; 
    } else {
      foreach($RS as $row) {
        if (f($row,'processo')=='N') {
          $w_erro = 'Protocolo '.((nvl($p_prefixo,'')!='') ? $p_prefixo.'.' : '').$p_numero.'/'.$p_ano.' não é um processo. Somente é permitida a vinculação entre processos!';
        } else {

          $w_chave = f($row,'sq_siw_solicitacao');
          $w_sigla = f($row,'sg_menu');
          
          // Recupera os dados do processo a ser vinculado
          $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_sigla);
          
          if (nvl($w_protocolo,'')!='') {
            $w_prefixo  = substr($_REQUEST['w_protocolo'], 0, 5);
            $w_numero   = substr($_REQUEST['w_protocolo'], 6, 6);
            $w_ano      = substr($_REQUEST['w_protocolo'], 13, 4);
            // Recupera a chave do protocolo de destino
            $sqlv = new db_getProtocolo; $RSV = $sqlv->getInstanceOf($dbms, $w_menu, $w_usuario, $SG, $p_chave, $p_chave_aux,
                    $w_prefixo, $w_numero, $w_ano, null, null, null, null, null, null, 2, null, null, null, null, 
                    null, null, null, null);
            foreach($RSV as $row) {
              $w_chave_dest = f($row,'sq_siw_solicitacao');
              
              // Recupera os dados do processo de destino
              $RS1 = $sql->getInstanceOf($dbms,f($row,'sq_siw_solicitacao'),$w_sigla);
              break;
            }
          }
        }
      }
    }
  }
  
  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  if ($O == 'L') {
    if (is_Array($RS1)) {
      ShowHTML('  if ('.$w_chave.'=='.$w_chave_dest.') {');
      ShowHTML('    alert("Processo de origem não pode ser o mesmo ao qual será vinculado!");');
      ShowHTML('    return false;');
      ShowHTML('  }');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    }
  } elseif ($O == 'P') {
    Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
    Validate('p_numero', 'Número', '1', '1', '1', '6', '', '0123456789');
    Validate('p_ano', 'Ano', '1', '1', '4', '4', '', '0123456789');
    ShowHTML('  theForm.Botao.disabled=true;');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($O == 'P') {
    BodyOpen('onLoad=\'document.Form.p_numero.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  
  if ($w_erro!='') {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("'.$w_erro.'");');
    ShowHTML(' history.back();');
    ScriptClose();
    exit();
  }
  
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<HR>');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_dest" value="'.$w_chave_dest.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<tr><td bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe os dados solicitados e clique no botão "Gravar" para confirmar a operação ou no botão "Abandonar" para voltar à tela anterior.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('    <tr><td colspan=2><b>PROCESSO A SER VINCULADO</b></td></tr>');
    ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('    <tr><td width="30%">'.f($RS,'nm_tipo').':<td><b>'.f($RS,'protocolo').'</b></td></tr>');
    if (f($RS,'interno')=='S') {
      ShowHTML('   <tr><td width="30%">Unidade:</td><td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>');
    } else {
      ShowHTML('   <tr><td>Pessoa:</td><td>'.f($RS,'nm_pessoa_origem').'</td></tr>');
      ShowHTML('   <tr><td>Interessado principal:</td><td>'.f($RS,'nm_pessoa_interes').'</td></tr>');
    }
    ShowHTML('   <tr><td>Cidade:</td><td>'.f($RS,'nm_cidade').'</td></tr>');
    ShowHTML('   <tr><td>Espécie documental:</td><td>'.f($RS,'nm_especie').'</td></tr>');
    ShowHTML('   <tr><td>Número:</td><td>'.f($RS,'numero_original').'</td></tr>');
    ShowHTML('   <tr><td>Data do documento:</td><td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>');
  
    ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
    ShowHTML('    <tr><td colspan=2><b>VINCULAR AO PROCESSO DE ORIGEM</b></td></tr>');
    ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
    SelecaoProtocolo('<U>P</U>rocesso:', 'P', 'Selecione o processo ao qual deseja vincular o processo informado acima.', $w_protocolo, null, 'w_protocolo', 'JUNTADA', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_protocolo\'; document.Form.submit();"',1,'<td>');
    if (is_Array($RS1)) {
      if (f($RS1,'interno')=='S') {
        ShowHTML('   <tr><td width="30%">Unidade:</td><td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unid_origem'),f($RS1,'sq_unidade'),$TP).'</td></tr>');
      } else {
        ShowHTML('   <tr><td>Pessoa:</td><td>'.f($RS1,'nm_pessoa_origem').'</td></tr>');
        ShowHTML('   <tr><td>Interessado principal:</td><td>'.f($RS1,'nm_pessoa_interes').'</td></tr>');
      }
      ShowHTML('   <tr><td>Cidade:</td><td>'.f($RS1,'nm_cidade').'</td></tr>');
      ShowHTML('   <tr><td>Espécie documental:</td><td>'.f($RS1,'nm_especie').'</td></tr>');
      ShowHTML('   <tr><td>Número:</td><td>'.f($RS1,'numero_original').'</td></tr>');
      ShowHTML('   <tr><td>Data do documento:</td><td>'.formataDataEdicao(f($RS1,'inicio')).'</td></tr>');
    
      ShowHTML('    <tr><td width="30%">'.$_SESSION['LABEL_CAMPO'].':<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td colspan=2 align="center"><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Gravar">');
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
      ShowHTML('      </td>');
      ShowHTML('    </tr>');
    }
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe o número do processo que deseja vincular a outro e clique sobre a operação <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Após verificar se o protocolo informado existe e que é um processo, o sistema permitirá a vinculação.');
    ShowHTML('  </b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>Processo a ser vinculado:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <tr><td colspan="2" align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de desmembramento de protocolos
// -------------------------------------------------------------------------
function Desmembrar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $sql = new db_getSolicData;
  $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
  if (count($RS) > 0)
    $w_processo = f($RS, 'processo');

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  ScriptOpen('JavaScript');
  FormataProtocolo();
  FormataData();
  SaltaCampo();
  CheckBranco();
  ValidateOpen('Validacao');
  ShowHTML('  var i; ');
  ShowHTML('  var w_erro=true; ');
  ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
  ShowHTML('    if (theForm["w_chave[]"][i].checked) w_erro=false; ');
  ShowHTML('  }');
  ShowHTML('  if (w_erro) {');
  ShowHTML('    alert("Você deve informar pelo menos um protocolo!"); ');
  ShowHTML('    return false;');
  ShowHTML('  }');
  Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
  // Se não for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<tr><td bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orientação:<ul>');
  ShowHTML('  <li>Informe os protocolos a serem desmembrados e clique no botão "Desmembrar" para confirmar a operação ou no botão "Abandonar" para voltar à tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>PROTOCOLO PRINCIPAL</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">' . f($RS, 'nm_tipo') . ':<td><b>' . f($RS, 'protocolo') . '</b></td></tr>');
  if (f($RS, 'interno') == 'S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>' . ExibeUnidade('../', $w_cliente, f($RS, 'nm_unid_origem'), f($RS, 'sq_unidade'), $TP) . '</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>' . f($RS, 'nm_pessoa_origem') . '</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>' . f($RS, 'nm_pessoa_interes') . '</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>' . f($RS, 'nm_cidade') . '</td></tr>');
  ShowHTML('   <tr><td>Espécie documental:</td>');
  ShowHTML('       <td>' . f($RS, 'nm_especie') . '</td></tr>');
  ShowHTML('   <tr><td>Número:</td>');
  ShowHTML('       <td>' . f($RS, 'numero_original') . '</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>' . formataDataEdicao(f($RS, 'inicio')) . '</td></tr>');

  $sql = new db_getSolicList;
  $RS_Juntado = $sql->getInstanceOf($dbms, f($RS, 'sq_menu'), $w_usuario, 'PAD', 5,
                  $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante,
                  $p_unidade, $p_prioridade, $p_ativo, $p_proponente,
                  $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                  $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, f($RS, 'sq_siw_solicitacao'), $p_atividade,
                  null, null, $p_empenho, $p_numero_orig);
  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>PROTOCOLOS A SEREM DESMEMBRADOS</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('<tr><td align="center" colspan=2>');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
  ShowHTML('          <td rowspan=2><b>&nbsp;</td>');
  ShowHTML('          <td rowspan=2><b>Tipo</td>');
  ShowHTML('          <td rowspan=2><b>Protocolo</td>');
  ShowHTML('          <td colspan=4><b>Documento original</td>');
  ShowHTML('          <td rowspan=2><b>Limite</td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
  ShowHTML('          <td><b>Espécie</td>');
  ShowHTML('          <td><b>Nº</td>');
  ShowHTML('          <td><b>Data</td>');
  ShowHTML('          <td><b>Procedência</td>');
  ShowHTML('        </tr>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  if (count($RS_Juntado) <= 0) {
    // Se não foram selecionados registros, exibe mensagem
    ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
  } else {
    // Lista os registros selecionados para listagem
    $w_atual = '';
    $i = 0;
    foreach ($RS_Juntado as $row) {
      //if (f($row,'tipo_juntada')=='P') {
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      ShowHTML('      <tr bgcolor="' . $w_cor . '">');
      ShowHTML('        <td align="center">');
      if (nvl($w_marcado[f($row, 'sq_siw_solicitacao')], '') != '') {
        ShowHTML('          <input type="CHECKBOX" CHECKED name="w_chave[]" value="' . f($row, 'sq_solic_pai') . '" ></td>');
      } else {
        ShowHTML('          <input type="CHECKBOX" name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '" ></td>');
      }
      ShowHTML('        </td>');
      ShowHTML('        <td align="center">' . f($row, 'nm_tipo_protocolo') . '</td>');
      ShowHTML('        <td align="center"><A class="HL" HREF="' . $w_dir . 'documento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
      ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
      ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
      ShowHTML('        <td align="center">' . date(d . '/' . m . '/' . y, f($row, 'inicio')) . '</td>');
      ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
      ShowHTML('        <td align="center">' . ((nvl(f($row, 'fim'), '') != '') ? date(d . '/' . m . '/' . y, f($row, 'fim')) : '&nbsp;') . '</td>');
      ShowHTML('      </tr>');
      $i += 1;
      //}
    }
  }
  ShowHTML('      </center>');
  ShowHTML('    </table>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO DESMEMBRAMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data:<td><b>' . formataDataEdicao(time()) . '</b></td></tr>');
  ShowHTML('    <tr><td width="30%">Responsável:<td><b>' . $_SESSION['NOME'] . '</b></td></tr>');
  ShowHTML('    <tr><td width="30%">'.$_SESSION['LABEL_CAMPO'].':<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Desmembrar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
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
// Executa o arquivamento setorial
// -------------------------------------------------------------------------
function ArqSetorial() {
  extract($GLOBALS);
  global $w_Disabled;
  global $p_unid_posse;
  
  if (is_array($_REQUEST['w_chave'])) {
    $itens = $_REQUEST['w_chave'];
  } else {
    $itens = explode(',', $_REQUEST['w_chave']);
  }

  if ($w_troca > '') {
    // Se for recarga da página
    $w_chave            = $_REQUEST['w_chave'];
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_protocolo        = $_REQUEST['w_protocolo'];
    $w_observacao       = $_REQUEST['w_observacao'];
  }

  // Verifica se a unidade de lotação do usuário está cadastrada na relação de unidades do módulo
  $sql = new db_getUorgList;
  $RS_Prot = $sql->getInstanceOf($dbms, $w_cliente, null, 'MOD_PA_PROT', null, null, $w_ano);
  foreach ($RS_Prot as $row) {
    $RS_Prot = $row;
    break;
  }

  if ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getProtocolo;
    $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, $SG, $p_chave, $p_chave_aux,$p_prefixo, $p_numero, $p_ano, 
                  $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2, $p_tipo_despacho, $p_empenho, 
                  $p_solicitante, $p_unidade, $p_proponente, $p_sq_acao_ppa, $p_assunto, $p_processo);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'prefixo', 'asc', 'ano', 'desc', 'numero_documento', 'asc');
    } else {
      $RS = SortArray($RS, 'prefixo', 'asc', 'ano', 'desc', 'numero_documento', 'asc');
    }
    $w_existe = count($RS);

    if (count($w_chave) > 0) {
      $i = 0;
      foreach ($w_chave as $k => $v) {
        foreach ($RS as $row) {
          if ($w_chave[$i] == f($row, 'sq_siw_solicitacao')) {
            $w_marcado[f($row, 'sq_siw_solicitacao')] = 'ok';
            break;
          }
        }
        $i++;
      }
      reset($RS);
    }
  }
  Cabecalho();
  head();
  if ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');

    Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
    Validate('p_numero', 'Número', '1', '', '1', '6', '', '0123456789');
    Validate('p_ano', 'Ano', '1', '', '4', '4', '', '0123456789');
    Validate('p_proponente', 'Origem externa', '', '', '2', '90', '1', '');
    Validate('p_unid_posse', 'Unidade de posse', 'SELECT', '1', '1', '18', '', '1');
    //Validate('p_sq_acao_ppa', 'Código do assunto', 'HIDDEN', '', '1', '10', '1', '1');
    Validate('p_assunto', 'Detalhamento do assunto', '', '', '4', '90', '1', '1');
    Validate('p_processo', 'Interessado', '', '', '2', '90', '1', '1');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } elseif ($w_existe) {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($w_existe) {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
      ShowHTML('    if (theForm["w_chave[]"][i].checked) w_erro=false; ');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Você deve informar pelo menos um protocolo!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      //Validate('w_chave', 'nº. de protocolo', 'CHECKBOX', '1', '1', '5', '', '1');
      Validate('w_observacao', 'Observações sobre o acondicionamento do protocolo', '1', '1', 1, 2000, '1', '1');
      Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
      ShowHTML('  if (!confirm(\'Confirma a geração de guia de tramitação APENAS para ' . (($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral')) ? 'as caixas selecionadas' : 'os documentos selecionados') . '?\')) return false;');
      // Se não for encaminhamento
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" width="100%">');

  if ($O == 'L') {
    if (upper($par) != 'ARQSETORIAL') {
      ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
      ShowHTML('  ATENÇÃO:<ul>');
      ShowHTML('  <li>PROTOCOLOS JUNTADOS NÃO PODEM SER ENVIADOS.');
      ShowHTML('  <li>Se o trâmite for para pessoa jurídica, não se esqueça de informar para qual unidade dessa entidade você está enviando.');
      ShowHTML('  <li>Informe sua assinatura eletrônica e clique sobre o botão <i>Gerar Guia de Tramitação</i>.');
      ShowHTML('  </ul></b></font></td>');
    }
    //// Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td colspan=2>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td width="1%" nowrap><b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td rowspan=2><b>&nbsp;</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . linkOrdena('Protocolo', 'protocolo', 'Form') . '</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . linkOrdena('Tipo', 'nm_tipo', 'Form') . '</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Resumo', '', 'Form') . '</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>' . linkOrdena('Espécie', 'nm_especie', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Nº', 'numero_original', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Data', 'inicio', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Procedência', 'nm_origem_doc', 'Form') . '</td>');
    ShowHTML('        </tr>');
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<input type="hidden" name="w_chave[]" value=""></td>');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="' . f($RS_Solic, 'unidade_int_posse') . '">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="' . f($RS_Solic, 'pessoa_ext_posse') . '">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_despacho" value="' . $p_tipo_despacho . '">');
    if (nvl($_REQUEST['p_ordena'], '') == '') ShowHTML('<INPUT type="hidden" name="p_ordena" value="">');
    ShowHTML(MontaFiltro('POST'));

    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      $i = 0;
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        if ($SG == 'PADTRANSF') {
          ShowHTML('        <td align="center" width="1%" nowrap>');
          ShowHTML('          <INPUT type="hidden" name="w_tramite[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'sq_siw_tramite') . '">');
          ShowHTML('          <INPUT type="hidden" name="w_unid_origem[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'unidade_int_posse') . '">');
          ShowHTML('          <input type="CHECKBOX" ' . ((nvl($w_marcado[f($row, 'sq_siw_solicitacao')], '') != '') ? 'CHECKED' : '') . ' class="w_chave_cb" name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '"></td>');
          ShowHTML('          <INPUT type="hidden" name="w_unid_autua[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'unidade_autuacao') . '">');
          /* if (nvl($w_marcado[f($row,'sq_siw_solicitacao')],'')!='') {
            ShowHTML('          <input type="CHECKBOX" CHECKED name="w_chave[]" value="'.f($row,'sq_solic_pai').'" ></td>');
            } else {
            if(in_array(f($row,'sq_siw_solicitacao'),$itens)){
            ShowHTML('          <input type="CHECKBOX" CHECKED  name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'"></td>');
            }else{
            ShowHTML('          <input type="CHECKBOX"  name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'"></td>');
            }
            } */
          ShowHTML('        </td>');
          ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="' . $w_dir . 'documento.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
          ShowHTML('        <td width="10">&nbsp;' . f($row, 'nm_tipo') . '</td>');
          ShowHTML('        <td>&nbsp;' . f($row, 'nm_especie') . '</td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;' . f($row, 'numero_original') . '</td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;' . formataDataEdicao(f($row, 'inicio'), 5) . '&nbsp;</td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;' . f($row, 'nm_origem_doc') . '</td>');
          if (strlen(Nvl(f($row, 'descricao'), '-')) > 50)
            $w_titulo = substr(Nvl(f($row, 'descricao'), '-'), 0, 50) . '...'; else
            $w_titulo=Nvl(f($row, 'descricao'), '-');
          if (f($row, 'sg_tramite') == 'CA')
            ShowHTML('        <td width="50%" title="' . htmlspecialchars(f($row, 'descricao')) . '"><strike>' . $w_titulo . '</strike></td>');
          else
            ShowHTML('        <td width="50%" title="' . htmlspecialchars(f($row, 'descricao')) . '">' . $w_titulo . '</td>');
        }
        ShowHTML('      </tr>');
        $i += 1;
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    if ($w_existe) {
      ShowHTML('    <tr><td colspan="3">&nbsp;</td></tr>');
      ShowHTML('    <tr><td colspan=3><b>DADOS DO ARQUIVAMENTO</b></td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('    <tr><td width="30%">Data do arquivamento:<td colspan=2><b>' . formataDataEdicao(time()) . '</b></td></tr>');
      $sql = new db_getUorgData;
      $RS_Unid = $sql->getInstanceOf($dbms, $_SESSION['LOTACAO']);
      ShowHTML('    <tr><td width="30%">Unidade arquivadora:<td colspan=2><b>' . f($RS_Unid, 'nome') . '</b></td></tr>');
      ShowHTML('    <tr><td width="30%">Usuário arquivador:<td colspan=2><b>' . $_SESSION['NOME'] . '</b></td></tr>');
      ShowHTML('    <tr valign="top"><td width="30%">Acondicionamento:<td title="Descreva de forma objetiva onde o documento encontra-se no arquivo setorial."><textarea ' . $w_Disabled . ' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>' . $w_observacao . '</TEXTAREA></td>');
      ShowHTML('    <tr><td colspan=3>&nbsp;</td></tr>');
      ShowHTML('    <tr><td colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td colspan=3 align="center"><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Arquivar">');
      ShowHTML('      </td>');
      ShowHTML('    </tr>');
    }
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que detém a posse do protocolo:', 'U', 'Selecione a unidade de posse.', $p_unid_posse, $w_usuario, 'p_unid_posse', 'CADPA', null);
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="' . $p_empenho . '">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $p_solicitante, null, 'p_solicitante', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_ini') . ' e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:', 'O', null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="' . $p_proponente . '"></td>');
    ShowHTML('      <tr valign="top">');
//    ShowHTML('          <td><b>Código do <U>a</U>ssunto:<br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="p_sq_acao_ppa" size="10" maxlength="10" value="'.$p_sq_acao_ppa.'"></td>');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="' . $p_assunto . '"></td>');
//    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" ' . $w_Disabled . ' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="' . $p_processo . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $p_classif, null, 'p_classif', 'FOLHA', null, '2');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de transferência de documentos para o arquivo setorial
// -------------------------------------------------------------------------
function Arquivar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $sql = new db_getSolicData;
  $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
  if (count($RS) > 0)
    $w_processo = f($RS, 'processo');

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_observacao', 'Observações sobre o acondicionamento do protocolo', '1', '1', 1, 2000, '1', '1');
  Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
  // Se não for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_protocolo" value="' . f($RS, 'protocolo') . '">');
  ShowHTML('<tr><td bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orientação:<ul>');
  ShowHTML('  <li>ATENÇÃO: Certifique-se de que realmente deseja arquivar este protocolo.');
  ShowHTML('  <li>Leia atentamente os dados que serão registrados para este arquivamento e clique no botão "Arquivar" para confirmar a operação ou no botão "Abandonar" para voltar à tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=3><b>DADOS DO DOCUMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">' . f($RS, 'nm_tipo') . ':<td colspan=2><b>' . f($RS, 'protocolo') . '</b></td></tr>');
  if (f($RS, 'interno') == 'S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td><td colspan=2>' . ExibeUnidade('../', $w_cliente, f($RS, 'nm_unid_origem'), f($RS, 'sq_unidade'), $TP) . '</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td><td colspan=2>' . f($RS, 'nm_pessoa_origem') . '</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td><td colspan=2>' . f($RS, 'nm_pessoa_interes') . '</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td><td colspan=2>' . f($RS, 'nm_cidade') . '</td></tr>');
  ShowHTML('   <tr><td>Espécie documental:</td><td colspan=2>' . f($RS, 'nm_especie') . '</td></tr>');
  ShowHTML('   <tr><td>Número:</td><td colspan=2>' . f($RS, 'numero_original') . '</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td><td colspan=3>' . formataDataEdicao(f($RS, 'inicio')) . '</td></tr>');

  ShowHTML('    <tr><td colspan=3>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=3><b>DADOS DO ARQUIVAMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data do arquivamento:<td colspan=2><b>' . formataDataEdicao(time()) . '</b></td></tr>');
  $sql = new db_getUorgData;
  $RS_Unid = $sql->getInstanceOf($dbms, f($RS, 'unidade_int_posse'));
  ShowHTML('    <tr><td width="30%">Unidade arquivadora:<td colspan=2><b>' . f($RS_Unid, 'nome') . '</b></td></tr>');
  ShowHTML('    <tr><td width="30%">Usuário arquivador:<td colspan=2><b>' . $_SESSION['NOME'] . '</b></td></tr>');
  ShowHTML('    <tr valign="top"><td width="30%">Acondicionamento:<td title="Descreva de forma objetiva onde o documento encontra-se no arquivo setorial."><textarea ' . $w_Disabled . ' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>' . $w_observacao . '</TEXTAREA></td>');
  ShowHTML('    <tr><td colspan=3>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=3 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Arquivar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
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
// Rotina de registro da eliminação de documentos
// -------------------------------------------------------------------------
function Eliminar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $sql = new db_getSolicData;
  $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
  if (count($RS) > 0)
    $w_processo = f($RS, 'processo');

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
  // Se não for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_protocolo" value="' . f($RS, 'protocolo') . '">');
  ShowHTML('<tr><td bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orientação:<ul>');
  ShowHTML('  <li>ATENÇÃO: Certifique-se de que realmente deseja eliminar este protocolo.');
  ShowHTML('  <li>Leia atentamente os dados que serão registrados para esta eliminação e clique no botão "Eliminar" para confirmar a operação ou no botão "Abandonar" para voltar à tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO DOCUMENTO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">' . f($RS, 'nm_tipo') . ':<td><b>' . f($RS, 'protocolo') . '</b></td></tr>');
  if (f($RS, 'interno') == 'S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>' . ExibeUnidade('../', $w_cliente, f($RS, 'nm_unid_origem'), f($RS, 'sq_unidade'), $TP) . '</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>' . f($RS, 'nm_pessoa_origem') . '</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>' . f($RS, 'nm_pessoa_interes') . '</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>' . f($RS, 'nm_cidade') . '</td></tr>');
  ShowHTML('   <tr><td>Espécie documental:</td>');
  ShowHTML('       <td>' . f($RS, 'nm_especie') . '</td></tr>');
  ShowHTML('   <tr><td>Número:</td>');
  ShowHTML('       <td>' . f($RS, 'numero_original') . '</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>' . formataDataEdicao(f($RS, 'inicio')) . '</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DA ELIMINAÇÃO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data do arquivamento:<td><b>' . formataDataEdicao(time()) . '</b></td></tr>');
  $sql = new db_getUorgData;
  $RS_Unid = $sql->getInstanceOf($dbms, $_SESSION['LOTACAO']);
  ShowHTML('    <tr><td width="30%">Unidade responsável:<td><b>' . f($RS_Unid, 'nome') . '</b></td></tr>');
  ShowHTML('    <tr><td width="30%">Usuário responsável:<td><b>' . $_SESSION['NOME'] . '</b></td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Eliminar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
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
// Rotina de empréstimo de documentos arquivados
// -------------------------------------------------------------------------
function Emprestar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  // Recupera os dados do documento
  $sql = new db_getSolicData;
  $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
  if (count($RS) > 0)
    $w_processo = f($RS, 'processo');

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  ScriptOpen('JavaScript');
  FormataProtocolo();
  FormataData();
  SaltaCampo();
  CheckBranco();
  ValidateOpen('Validacao');
  Validate('w_sq_unidade', 'Unidade de destino', 'SELECT', 1, 1, 18, '', '0123456789');
  Validate('w_data', 'Data do empréstimo', 'DATA', '1', '10', '10', '', '0123456789/');
  Validate('w_retorno_limite', 'Data limite para retorno', 'DATA', '', 10, 10, '', '0123456789/');
  CompData('w_retorno_limite', 'Data limite para retorno', '>=', FormataDataEdicao(time()), 'data atual');
  Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
  // Se não for encaminhamento
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_sq_unidade.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<tr><td bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
  ShowHTML('  Orientação:<ul>');
  ShowHTML('  <li>ATENÇÃO: Verifique se realmente deseja emprestar este protocolo.');
  ShowHTML('  <li>Informe os dados solicitados e clique no botão "Emprestar" para confirmar a operação ou no botão "Abandonar" para voltar à tela anterior.');
  ShowHTML('  </ul></b></font></td>');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan=2><b>PROTOCOLO A SER EMPRESTADO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%">' . f($RS, 'nm_tipo') . ':<td><b>' . f($RS, 'protocolo') . '</b></td></tr>');
  if (f($RS, 'interno') == 'S') {
    ShowHTML('   <tr><td width="30%">Unidade:</td>');
    ShowHTML('       <td>' . ExibeUnidade('../', $w_cliente, f($RS, 'nm_unid_origem'), f($RS, 'sq_unidade'), $TP) . '</td></tr>');
  } else {
    ShowHTML('   <tr><td>Pessoa:</td>');
    ShowHTML('       <td>' . f($RS, 'nm_pessoa_origem') . '</td></tr>');
    ShowHTML('   <tr><td>Interessado principal:</td>');
    ShowHTML('       <td>' . f($RS, 'nm_pessoa_interes') . '</td></tr>');
  }
  ShowHTML('   <tr><td>Cidade:</td>');
  ShowHTML('       <td>' . f($RS, 'nm_cidade') . '</td></tr>');
  ShowHTML('   <tr><td>Espécie documental:</td>');
  ShowHTML('       <td>' . f($RS, 'nm_especie') . '</td></tr>');
  ShowHTML('   <tr><td>Número:</td>');
  ShowHTML('       <td>' . f($RS, 'numero_original') . '</td></tr>');
  ShowHTML('   <tr><td>Data do documento:</td>');
  ShowHTML('       <td>' . formataDataEdicao(f($RS, 'inicio')) . '</td></tr>');

  ShowHTML('    <tr><td colspan=2>&nbsp;</td></tr>');
  ShowHTML('    <tr><td colspan=2><b>DADOS DO EMPRÉSTIMO</b></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('    <tr><td width="30%"><u>U</u>nidade a emprestar:');
  SelecaoUnidade(null, 'U', null, $w_sq_unidade, null, 'w_sq_unidade', 'MOD_PA', null);
  ShowHTML('    <tr><td width="30%"><u>D</u>ata do empréstimo:<td><input ' . $w_Disabled . ' accesskey="D" type="text" name="w_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_data . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td></tr>');
  ShowHTML('    <tr><td width="30%">Data <u>l</u>imite para retorno:<td><input ' . $w_Disabled . ' accesskey="O" type="text" name="w_retorno_limite" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_retorno_limite . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_retorno_limite') . '</td></tr>');

  ShowHTML('    <tr><td width="30%">'.$_SESSION['LABEL_CAMPO'].':<td> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td colspan=2 align="center"><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Emprestar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  $w_file = '';
  $w_tamanho = '';
  $w_tipo = '';
  $w_nome = '';
  Cabecalho();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  BodyOpen(null);
  if ($SG == 'PADALTREG') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
      if ($O == 'E') {
        $sql = new db_getSolicLog;
        $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], null, null, 'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e não excluída.
        // Nessa situação, não é necessário excluir os arquivos.
        if (count($RS) <= 1) {
          $sql = new db_getSolicAnexo;
          $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], null, $w_cliente);
          foreach ($RS as $row) { {
              if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho')))
                unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
            }
          }
        }
      } else {
        // Grava baseline do documento
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PADCAD');
        $w_html = VisualDocumento($_REQUEST['w_chave'], 'T', $_SESSION['SQ_PESSOA'], $P1, 'WORD', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'N');
        CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), f($RS, 'sq_siw_tramite'));
      }

      // Grava as alterações
      $SQL = new dml_putDocumentoGeral;
      $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $w_copia, $_REQUEST['w_menu'],
              nvl($_REQUEST['w_sq_unidade'], $_SESSION['LOTACAO']), null,
              nvl($_REQUEST['w_pessoa_origem'], $_SESSION['SQ_PESSOA']), $_SESSION['SQ_PESSOA'], $_REQUEST['w_solic_pai'],
              $_REQUEST['w_vinculo'], nvl($_REQUEST['w_tipo'], $_REQUEST['w_processo']), $_REQUEST['w_circular'],
              $_REQUEST['w_especie_documento'], $_REQUEST['w_doc_original'], $_REQUEST['w_data_documento'],
              $_REQUEST['w_volumes'], $_REQUEST['w_dt_autuacao'],
              $_REQUEST['w_copias'], $_REQUEST['w_natureza_documento'], $_REQUEST['w_fim'], $_REQUEST['w_data_recebimento'],
              $_REQUEST['w_interno'], $_REQUEST['w_pessoa_origem'], $_REQUEST['w_pessoa_interes'], $_REQUEST['w_cidade'],
              $_REQUEST['w_assunto'], $_REQUEST['w_descricao'], $_REQUEST['w_observacao'], $w_chave_nova, $w_codigo);

      ScriptOpen('JavaScript');
      // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
      $sql = new db_getLinkData;
      $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $SG);
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS1, 'link') . '&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG == 'PADAUTUA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
      $SQL = new dml_putDocumentoAutua;
      $SQL->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_unidade_autua'], $_SESSION['SQ_PESSOA'], $_REQUEST['w_descricao']);
      $w_protocolo = $_REQUEST['w_protocolo'];
      $w_prefixo  = substr($_REQUEST['w_protocolo'], 0, 5);
      $w_numero   = substr($_REQUEST['w_protocolo'], 6, 6);
      $w_ano      = substr($_REQUEST['w_protocolo'], 13, 4);
      
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Autuação realizada com sucesso!\\nImprima a etiqueta na próxima tela.");');
      ShowHTML('  parent.menu.location="'.montaURL_JS(null, $conRootSIW .'menu.php?par=ExibeDocs&O=P&R='.$R.'&SG=RELPAETIQ&p_prefixo='.$w_prefixo.'&p_numero='.$w_numero.'&p_ano='.$w_ano.'&TP='.RemoveTP(RemoveTP($TP))).'";');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif ($SG == 'PADVINCULA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
      $SQL = new dml_putDocumentoVincula;
      $SQL->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_chave_dest'], $_SESSION['SQ_PESSOA']);

      ScriptOpen('JavaScript');
      ShowHTML('  alert("Vinculação entre processos realizada com sucesso!");');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir, $w_pagina.'Vincular&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif ($SG == 'PADANEXA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {

      //Percorre o laço de w_chave e registra a anexação
      $SQL = new dml_putDocumentoAnexa;
      for ($i = 0; $i <= count($_POST['w_chave']) - 1; $i = $i + 1) {
        if (Nvl($_POST['w_chave'][$i], '') > '') {
          $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $_SESSION['SQ_PESSOA']);
        }
      }

      ScriptOpen('JavaScript');
      ShowHTML('  alert("Anexação realizada com sucesso!");');
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Juntar&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif ($SG == 'PADJUNTA') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
      $SQL = new dml_putDocumentoJunta;
      for ($i = 0; $i <= count($_POST['w_chave']) - 1; $i = $i + 1) {
        if (Nvl($_POST['w_chave'][$i], '') > '') {
          $SQL->getInstanceOf($dbms, $_REQUEST['w_chave'][$i], $_SESSION['SQ_PESSOA']);
        }
      }

      ScriptOpen('JavaScript');
      ShowHTML('  alert("Apensação realizada com sucesso!");');
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Juntar&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif ($SG == 'PADDESM') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
      $SQL = new dml_putDocumentoDesm;
      for ($i = 0; $i <= count($_POST['w_chave']) - 1; $i = $i + 1) {
        if (Nvl($_POST['w_chave'][$i], '') > '') {
          $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $_SESSION['SQ_PESSOA']);
        }
      }

      ScriptOpen('JavaScript');
      ShowHTML('  alert("Desmembramento realizado com sucesso!");');
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Inicial&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif ($SG == 'PADTRANSF') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
      $SQL = new dml_putDocumentoArqSet;
      for ($i = 0; $i <= count($_POST['w_chave']) - 1; $i = $i + 1) {
        if (Nvl($_POST['w_chave'][$i], '') > '') {
          $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $_SESSION['SQ_PESSOA'], $_REQUEST['w_observacao']);
        }
      }

      ScriptOpen('JavaScript');
      ShowHTML('  alert("Arquivamento setorial realizado com sucesso!");');
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'ArqSetorial&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif ($SG == 'PADARQ') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
      if ($_REQUEST['w_envio'] == 'N') {
        // Se arquivamento
        $SQL = new dml_putDocumentoArqCen;
        for ($i = 0; $i <= count($_POST['w_chave']) - 1; $i = $i + 1) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $_SESSION['SQ_PESSOA'], $_REQUEST['w_local'], null);
          }
        }
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Arquivamento central realizado com sucesso!");');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Central&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        // Se devolução para arquivo setorial
        $SQL = new dml_putCaixaDevolucao;
        for ($i = 0; $i <= count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $w_usuario, $_REQUEST['w_observacao']);
          }
        }
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Devolução realizada com sucesso!");');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Central&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("Bloco de dados não encontrado: ' . $SG . '");');
    ScriptClose();
    exibevariaveis();
  }
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':     Inicial();            break;
    case 'CENTRAL':     Central();            break;
    case 'ALTERAR':     Alterar();            break;
    case 'AUTUAR':      Autuar();             break;
    case 'ANEXAR':      Anexar();             break;
    case 'VINCULAR':    Vincular();           break;
    case 'JUNTAR':      Juntar();             break;
    case 'APENSAR':     Apensar();            break;
    case 'ARQSETORIAL': ArqSetorial();        break;
    case 'ARQCENTRAL':  ArqCentral();         break;
    case 'ELIMINAR':    Eliminar();           break;
    case 'EMPRESTAR':   Emprestar();          break;
    case 'DESMEMBRAR':  Desmembrar();         break;
    case 'EMITIRGR':    EmitirGR();           break;
    case 'GRAVA':       Grava();              break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      exibevariaveis();
      break;
  }
}
?>
