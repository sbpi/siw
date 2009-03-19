<?
  header('Expires: ' .
-1500);
  session_start();
  $w_dir_volta = '../';
  include_once ($w_dir_volta . 'constants.inc');
  include_once ($w_dir_volta . 'jscript.php');
  include_once ($w_dir_volta . 'funcoes.php');
  include_once ($w_dir_volta . 'classes/db/abreSessao.php');
  include_once ($w_dir_volta . 'classes/sp/db_getMenuData.php');
  include_once ($w_dir_volta . 'classes/sp/db_getSiwCliModLis.php');
  include_once ($w_dir_volta . 'classes/sp/db_getCustomerData.php');
  include_once ($w_dir_volta . 'classes/sp/db_getPersonData.php');
  include_once ($w_dir_volta . 'classes/sp/db_getDeskTop_Recurso.php');
  include_once ($w_dir_volta . 'classes/sp/db_exec.php');
  include_once ($w_dir_volta . 'classes/sp/db_getDeskTop.php');
  include_once ($w_dir_volta . 'classes/sp/db_getAlerta.php');
  include_once ($w_dir_volta . 'classes/sp/db_getSolicList.php');
  include_once ($w_dir_volta . 'classes/sp/db_getSolicResultado.php');
  include_once ($w_dir_volta . 'classes/sp/db_getEtapaAnexo.php');
  include_once ($w_dir_volta . 'classes/sp/db_getLinkData.php');
  include_once ($w_dir_volta . 'funcoes/selecaoPessoa.php');
  include_once ($w_dir_volta . 'funcoes/selecaoUnidade.php');
  include_once ($w_dir_volta . 'funcoes/selecaoProjeto.php');
  include_once ($w_dir_volta . 'funcoes/selecaoPrograma.php');

  include_once ($w_dir_volta . 'visualalerta.php');
  // =========================================================================
  //  trabalho.php
  // ------------------------------------------------------------------------
  // Nome     : Alexandre Vinhadelli Papadópolis
  // Descricao: Gerencia a atualização das tabelas do sistema
  // Mail     : alex@sbpi.com.br
  // Criacao  : 24/03/2003 16:55
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
  if ($_SESSION['LOGON'] != 'Sim') {
    EncerraSessao();
  }

  // Declaração de variáveis
  $dbms = abreSessao :: getInstanceOf($_SESSION['DBMS']);

  // Carrega variáveis locais com os dados dos parâmetros recebidos
  $par = strtoupper($_REQUEST['par']);
  $P1 = $_REQUEST['P1'];
  $P2 = $_REQUEST['P2'];
  $P3 = $_REQUEST['P3'];
  $P4 = $_REQUEST['P4'];
  $TP = $_REQUEST['TP'];
  $SG = strtoupper($_REQUEST['SG']);
  $R = $_REQUEST['R'];
  $O = strtoupper($_REQUEST['O']);
  
  $p_programa    = $_REQUEST['p_programa'];
  $p_projeto     = $_REQUEST['p_projeto'];
  $p_solicitante = strtoupper($_REQUEST['p_solicitante']);
  $p_unidade     = strtoupper($_REQUEST['p_unidade']);
  $p_texto       = $_REQUEST['p_texto'];
  $p_ordena      = strtolower($_REQUEST['p_ordena']);

  $w_assinatura = strtoupper($_REQUEST['w_assinatura']);
  $w_pagina = 'resultados.php?par=';
  $w_Disabled = 'ENABLED';
  $w_dir = 'cl_pitce/';

  $w_cliente = RetornaCliente();
  $w_usuario = RetornaUsuario();
  $w_ano = RetornaAno();
  $w_mes = $_REQUEST['w_mes'];

  // Configura variáveis para montagem do calendário
  if (nvl($w_mes, '') == '')
    $w_mes = date('m', time());
  $w_inicio = first_day(toDate('01/' . substr(100 + (intVal($w_mes) - 1), 1, 2) . '/' . $w_ano));
  $w_fim = last_day(toDate('01/' . substr(100 + (intVal($w_mes) + 1), 1, 2) . '/' . $w_ano));
  $w_mes1 = substr(100 + intVal($w_mes) - 1, 1, 2);
  $w_mes3 = substr(100 + intVal($w_mes) + 1, 1, 2);
  $w_ano1 = $w_ano;
  $w_ano3 = $w_ano;
  // Ajusta a mudança de ano
  if ($w_mes1 == '00') {
    $w_mes1 = '12';
    $w_ano1 = $w_ano -1;
  }
  if ($w_mes3 == '13') {
    $w_mes3 = '01';
    $w_ano3 = $w_ano +1;
  }

  if ($O == '')
    $O = 'L';

  switch ($O) {
    case 'I' :
      $w_TP = $TP . ' - Inclusão';
      break;
    case 'A' :
      $w_TP = $TP . ' - Alteração';
      break;
    case 'E' :
      $w_TP = $TP . ' - Exclusão';
      break;
    case 'V' :
      $w_TP = $TP . ' - Envio';
      break;
    case 'P' :
      $w_TP = $TP . ' - Filtragem';
      break;
    default :
      $w_TP = $TP . ' - Listagem';
  }

  Main();

  FechaSessao($dbms);

  exit;

  // =========================================================================
  // Controle da mesa de trabalho
  // -------------------------------------------------------------------------
  function Mesa() {
    extract($GLOBALS);

    // Recupera os dados do cliente
    $RS_Cliente = db_getCustomerData :: getInstanceOf($dbms, $w_cliente);

    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . ';">');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('<style>');
    ShowHTML('#menu_superior{);');
    ShowHTML('   float:right;');
    ShowHTML(' }');
    ShowHTML('#calendario{');
    ShowHTML('  cursor:pointer;');
    ShowHTML('  width: 128px;');
    ShowHTML('  height: 128px;');
    ShowHTML('  background:url(' . $w_dir . 'calendario.gif) no-repeat;');
    ShowHTML('}');
    ShowHTML('#resultados{');
    ShowHTML('  cursor:pointer;');
    ShowHTML('  width: 128px;');
    ShowHTML('  height: 128px;');
    ShowHTML('  background-image:url(' . $w_dir . 'resultados.gif);');
    ShowHTML('}');
    ShowHTML('#download{');
    ShowHTML('  cursor:pointer;');
    ShowHTML('  width: 128px;');
    ShowHTML('  height: 128px;');
    ShowHTML('  background-image:url(' . $w_dir . 'download.gif);');
    ShowHTML('}');

    ShowHTML('</style>');
    ShowHTML('</HEAD>');
    BodyOpen('onLoad=this.focus();');

    ShowHTML('<table border="0" width="100%">');
    ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>' . $w_TP . '</font></b>');
    ShowHTML('    <td align="right">');

    // Se o geo-referenciamento estiver habilitado para o cliente, exibe link para acesso à visualização
    if (f($RS_Cliente, 'georeferencia') == 'S') {
      ShowHTML('      <a href="mod_gr/exibe.php?par=inicial&O=L&TP=' . $TP . ' - Geo-referenciamento" title="Clique para visualizar os mapas geo-referenciados." target="_blank"><img src="' . $conImgGeo . '" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    }

    if ($_SESSION['DBMS'] != 5) {
      // Exibe, se necessário, sinalizador para alerta
      $RS = db_getAlerta :: getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
      if (count($RS) > 0) {
        $w_sinal = $conImgAlLow;
        $w_msg = 'Clique para ver alertas de atraso e proximidade da data de conclusão.';
        foreach ($RS as $row) {
          if ($w_usuario == f($row, 'solicitante')) {
            $w_sinal = $conImgAlMed;
            $w_msg = 'Há alertas nos quais sua você é o responsável ou o solicitante. Clique para vê-los.';
          }
          if ($w_usuario == nvl(f($row, 'sq_exec'), f($row, 'solicitante'))) {
            $w_sinal = $conImgAlHigh;
            $w_msg = 'Há alertas nos quais sua intervenção é necessária. Clique para vê-los.';
            break;
          }
        }
        ShowHTML('      <a href="' . $w_pagina . 'alerta&O=L&TP=' . $TP . ' - Alertas" title="' . $w_msg . '"><img src="' . $w_sinal . '" border=0></a></font></b>');
      }
    }

    ShowHTML('<tr><td colspan=2><hr>');
    ShowHTML('</table>');
    ShowHTML('<center>');
    if ($O == "L") {
      $w_pde = 0;
      $w_pns = 0;
      $w_pne = 0;
      $w_pne1 = 0;
      $w_pne2 = 0;
      $w_pne3 = 0;
      $w_todos = 0;
      $SQL = "select b.sq_solic_pai, b.sq_siw_solicitacao, b.codigo_interno, b.sq_plano, count(a.sq_siw_solicitacao) as qtd from siw_solicitacao a join siw_solicitacao b on (a.sq_solic_pai = b.sq_siw_solicitacao) inner join siw_menu c on (b.sq_menu = c.sq_menu and c.sigla = 'PEPROCAD' and c.sq_pessoa = " . $w_cliente . ") group by b.sq_solic_pai, b.sq_siw_solicitacao, b.codigo_interno, b.sq_plano";
      $RS = db_exec :: getInstanceOf($dbms, $SQL, $recordcount);
      foreach ($RS as $row) {
        switch (f($row, 'codigo_interno')) {
          case 'PDE' :
            $w_plano = f($row, 'sq_plano');
            $c_pde = f($row, 'sq_siw_solicitacao');
            $w_pde = f($row, 'qtd');
            $w_todos += f($row, 'qtd');
            break;
          case 'PNS' :
            $c_pns = f($row, 'sq_siw_solicitacao');
            $w_pns = f($row, 'qtd');
            $w_todos += f($row, 'qtd');
            break;
          case 'PNE1' :
            $c_pne = f($row, 'sq_solic_pai');
            $c_pne1 = f($row, 'sq_siw_solicitacao');
            $w_pne1 = f($row, 'qtd');
            $w_pne += f($row, 'qtd');
            $w_todos += f($row, 'qtd');
            break;
          case 'PNE2' :
            $c_pne = f($row, 'sq_solic_pai');
            $c_pne2 = f($row, 'sq_siw_solicitacao');
            $w_pne2 = f($row, 'qtd');
            $w_pne += f($row, 'qtd');
            $w_todos += f($row, 'qtd');
            break;
          case 'PNE3' :
            $c_pne = f($row, 'sq_solic_pai');
            $c_pne3 = f($row, 'sq_siw_solicitacao');
            $w_pne3 = f($row, 'qtd');
            $w_pne += f($row, 'qtd');
            $w_todos += f($row, 'qtd');
            break;
        }
      }
      ShowHTML('<div id="menu_superior">');
      ShowHTML('<a href="' . $w_dir . 'resultados.php?par=inicial"><div id="resultados"></div></a>');
      ShowHTML('<a href="' . $w_dir . $w_pagina . 'calendario&O=P&TP=' . $TP . ' - Calendário&SG=' . $SG . '"><div id="calendario"></div></a>');
      ShowHTML('<a title="Estrutura de governança da PDP" href="' . LinkArquivo(null, $w_cliente, 'governanca.pdf', null, null, null, 'EMBED') . '" target="download"><div id="download"></div></a>');
      ShowHTML('</div>');
      ShowHTML('<img name="pdp" src="' . $w_dir . 'pdp.gif" width="611" height="402" border="0" id="pdp" usemap="#m_pdp" alt="" /><map name="m_pdp" id="m_pdp">');
      ShowHTML('<area shape="poly" coords="376,374,608,374,608,402,376,402,376,374" target="programa" href="' . $w_dir . 'pe_relatorios.php?par=rel_executivo&O=L&p_plano=' . $w_plano . '&p_legenda=S&p_projeto=S" target="PRG"" title="Agendas de ação: ' . $w_todos . '" />');
      ShowHTML('<area shape="poly" coords="258,248,261,240,269,237,593,237,600,240,603,248,603,253,600,260,593,263,269,263,261,260,258,253,258,248,258,248" target="programa" href="' . $w_dir . 'pe_relatorios.php?par=rel_executivo&p_programa=' . $c_pne . '&O=L&p_sinal=S&p_plano=' . $w_plano . '&p_legenda=S&p_projeto=S" title="Agendas de ação: ' . $w_pne . '" />');
      ShowHTML('<area shape="poly" coords="409,79,410,76,412,73,421,71,597,71,606,73,608,76,609,79,609,110,608,113,606,116,597,118,421,118,412,116,410,113,409,110,409,79,409,79" target="arquivo" href="' . $w_dir . $w_pagina . 'arquivos&p_codigo=CG-PDP - Docs&TP=' . $TP . ' - Documentos do Conselho Gestor da PDP" title="Documentos do Conselho Gestor da PDP" />');
      ShowHTML('<area shape="poly" coords="495,292,496,287,499,283,503,280,509,279,596,279,602,280,606,283,609,287,610,292,610,336,609,341,606,345,602,348,596,349,509,349,503,348,499,345,496,341,495,336,495,292,495,292" target="programa" href="' . $w_dir . 'pe_relatorios.php?par=rel_executivo&p_plano=' . $w_plano . '&p_programa=' . $c_pne2 . '&O=L&p_sinal=S&p_legenda=S&p_projeto=S" title="Agendas de ação: ' . $w_pne2 . '" />');
      ShowHTML('<area shape="poly" coords="372,293,373,288,376,284,380,281,386,281,473,281,479,281,483,284,486,288,487,293,487,338,486,343,483,347,479,350,473,351,386,351,380,350,376,347,373,343,372,338,372,293,372,293" target="programa" href="' . $w_dir . 'pe_relatorios.php?par=rel_executivo&p_plano=' . $w_plano . '&p_programa=' . $c_pne3 . '&O=L&p_sinal=S&p_legenda=S&p_projeto=S" title="Agendas de ação: ' . $w_pne3 . '" />');
      ShowHTML('<area shape="poly" coords="251,293,252,288,254,284,259,281,265,281,351,281,357,281,362,284,364,288,366,293,366,338,364,343,362,347,357,350,351,351,265,351,259,350,254,347,252,343,251,338,251,293,251,293" target="programa" href="' . $w_dir . 'pe_relatorios.php?par=rel_executivo&p_plano=' . $w_plano . '&p_programa=' . $c_pne1 . '&O=L&p_sinal=S&p_legenda=S&p_projeto=S" title="Agendas de ação: ' . $w_pne1 . '" />');
      ShowHTML('<area shape="poly" coords="127,292,128,287,131,283,135,280,141,279,228,279,234,280,238,283,241,287,242,292,242,336,241,341,238,345,234,348,228,349,141,349,135,348,131,345,128,341,127,336,127,292,127,292" target="programa" href="' . $w_dir . 'pe_relatorios.php?par=rel_executivo&p_plano=' . $w_plano . '&p_programa=' . $c_pde . '&O=L&p_sinal=S&p_legenda=S&p_projeto=S" title="Agendas de ação: ' . $w_pde . '" />');
      ShowHTML('<area shape="poly" coords="1,292,2,287,5,283,9,280,15,279,102,279,108,280,112,283,115,287,116,292,116,336,115,341,112,345,108,348,102,349,15,349,9,348,5,345,2,341,1,336,1,292,1,292" target="programa" href="' . $w_dir . 'pe_relatorios.php?par=rel_executivo&p_programa=' . $c_pns . '&O=L&p_sinal=S&p_plano=' . $w_plano . '&p_legenda=S&p_projeto=S" target="PNS" title="Agendas de ação: ' . $w_pns . '" />');
      ShowHTML('<area shape="poly" coords="233,84,234,79,237,75,242,72,247,71,369,71,374,72,379,75,382,79,383,84,383,105,382,110,379,114,374,117,369,118,247,118,242,117,237,114,234,110,233,105,233,84,233,84" target="arquivo" href="' . $w_dir . $w_pagina . 'arquivos&p_codigo=MDIC-PDP - Docs&TP=' . $TP . ' - Documentos do MDIC" title="Documentos do MDIC" />');
      ShowHTML('<area shape="poly" coords="233,154,234,149,237,145,242,142,247,141,369,141,374,142,379,145,382,149,383,154,383,175,382,180,379,184,374,187,369,188,247,188,242,187,237,184,234,180,233,175,233,154,233,154" target="arquivo" href="' . $w_dir . $w_pagina . 'arquivos&p_codigo=SE-PDP - Docs&TP=' . $TP . ' - Documentos da Secretaria Executiva" title="Documentos da Secretaria Executiva da PDP" />');
      ShowHTML('<area shape="poly" coords="233,14,234,9,237,5,242,2,247,1,369,1,374,2,379,5,382,9,383,14,383,35,382,40,379,44,374,47,369,48,247,48,242,47,237,44,234,40,233,35,233,14,233,14" target="arquivo" href="' . $w_dir . $w_pagina . 'arquivos&p_codigo=CNDI-PDP - Docs&TP=' . $TP . ' - Documentos do CNDI" title="Documentos do CNDI" />');
      ShowHTML('</map>');
      ShowHTML('<table border="0" width="100%">');
      ShowHTML('      <tr><td colspan=3><p>&nbsp;</p>');
    } else {
      ScriptOpen("JavaScript");
      ShowHTML(' alert(\'Opção não disponível\');');
      ShowHTML(' history.back(1);');
      ScriptClose();
    }
    ShowHTML('</table>');
    ShowHTML('</body>');
    ShowHTML('</html>');
    //Rodape();
  }

  // =========================================================================
  // Exibe alertas de atraso e proximidade da data de conclusao
  // -------------------------------------------------------------------------
  function Alerta() {
    extract($GLOBALS);

    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . ';">');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<table border="0" width="100%">');
    ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>' . $w_TP . '</font></b>');
    $RS_Volta = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'MESA');
    ShowHTML('  <td align="right"><a class="SS" href="' . $conRootSIW . f($RS_Volta, 'link') . '&P1=' . f($RS_Volta, 'p1') . '&P2=' . f($RS_Volta, 'p2') . '&P3=' . f($RS_Volta, 'p3') . '&P4=' . f($RS_Volta, 'p4') . '&TP=<img src=' . f($RS_Volta, 'imagem') . ' BORDER=0>' . f($RS_Volta, 'nome') . '&SG=' . f($RS_Volta, 'sigla') . '" target="content">Voltar para ' . f($RS_Volta, 'nome') . '</a>');
    ShowHTML('<tr><td colspan=2><hr>');
    ShowHTML('</table>');
    ShowHTML('<center>');
    ShowHTML('<table border="0" width="100%">');
    if ($O == 'L') {
      // Recupera solicitações a serem listadas
      $RS_Solic = db_getAlerta :: getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
      $RS_Solic = SortArray($RS_Solic, 'cliente', 'asc', 'usuario', 'asc', 'nm_modulo', 'asc', 'nm_servico', 'asc', 'titulo', 'asc');

      // Recupera pacotes de trabalho a serem listados
      $RS_Pacote = db_getAlerta :: getInstanceOf($dbms, $w_cliente, $w_usuario, 'PACOTE', 'N', null);
      $RS_Pacote = SortArray($RS_Pacote, 'cliente', 'asc', 'usuario', 'asc', 'nm_projeto', 'asc', 'cd_ordem', 'asc');

      ShowHTML(VisualAlerta($w_cliente, $w_usuario, 'TELA', $RS_Solic, $RS_Pacote));
    } else {
      ScriptOpen("JavaScript");
      ShowHTML(' alert(\'Opção não disponível\');');
      ShowHTML(' history.back(1);');
      ScriptClose();
    }
    ShowHTML('</table>');
    ShowHTML('</center>');
    Rodape();
  }

  // =========================================================================
  // Exibe Resultados da PDP
  // -------------------------------------------------------------------------
  function Inicial() {
  
  extract($GLOBALS);
  
  $w_tipo=$_REQUEST['w_tipo'];
  
	if ($w_tipo=='PDF') {
    headerpdf('Visualização de resultados',$w_pag);
    $w_embed = 'WORD';
	} elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de resultados',0);
    $w_embed = 'WORD';
	} else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . ';">');
    ScriptOpen('Javascript');
    ValidateOpen('Validacao');
    Validate('p_texto','Texto','','',3,50,'1','1');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    BodyOpen('onLoad=this.focus();');
    //if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);
    $w_embed="HTML";
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_pesquisa" value="">');
    ShowHTML('<table border="0" width="97%">');
    ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>' . $w_TP . '</font></b>');
    ShowHTML('<tr><td><hr>');
    ShowHTML(' <table width="100%">');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td>');
    selecaoPrograma('P<u>r</u>ograma:', 'R', 'Se desejar, selecione um dos programas.', $p_programa, $p_plano, $p_objetivo, 'p_programa', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.target=\'\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'p_projeto\'; document.Form.submit();"');
    ShowHTML('     </td>');
    ShowHTML('     <td>');
    $RS = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PJCAD');
    SelecaoProjeto('Agenda de açã<u>o</u>:', 'O', 'Selecione um item na relação.', $p_projeto, $w_usuario, f($RS, 'sq_menu'), $p_programa, $p_objetivo, $p_plano, 'p_projeto', 'PJLIST', null);
    ShowHTML('     </td>');
    ShowHTML('   </tr>');
    //ShowHTML('                                                 <input '.$w_Disabled.' accesskey="P" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').'</td>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('     <td>');
    SelecaoUnidade('Setor responsável:', null, null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('     </td>');
    ShowHTML('     <td>');
    SelecaoPessoa('Respo<u>n</u>sável:', 'N', 'Selecione o responsável pelo projeto na relação.', $p_solicitante, null, 'p_solicitante', 'USUARIOS');
    ShowHTML('     </td>');
    ShowHTML('   </tr>');
    ShowHTML(' <tr valign="top">');
    ShowHTML('  <td>');
    ShowHTML('  <td><strong>');
    ShowHTML('    <label>Pesquisa por Texto:<br>');
    ShowHTML('    </label>');
    ShowHTML('    </strong>');
    ShowHTML('      <input class="STI" type="text" size="80" maxlength="80" name="p_texto" id="p_texto" value="'. $p_texto .'"></td>');
    ShowHTML('  <td>');
    ShowHTML('  <td colspan="1" title="Selecione o respons&aacute;vel pelo projeto na rela&ccedil;&atilde;o.">&nbsp;</td>');
    ShowHTML(' </tr>');
    ShowHTML('   <tr valign="top">');
    ShowHTML('      <td align="center" colspan="4"><hr/>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\'; javascript:document.Form.p_pesquisa.value=\'S\';">');
    ShowHTML('      </tr>');
    ShowHTML(' </table>');
    ShowHTML('</FORM>');
    // Exibe o calendário da organização
    include_once ($w_dir_volta . 'classes/sp/db_getDataEspecial.php');
    for ($i = $w_ano1; $i <= $w_ano3; $i++) {
      $RS_Ano[$i] = db_getDataEspecial :: getInstanceOf($dbms, $w_cliente, null, $i, 'S', null, null, null);
      $RS_Ano[$i] = SortArray($RS_Ano[$i], 'data_formatada', 'asc');
    }

    // Recupera os dados da unidade de lotação do usuário
    include_once ($w_dir_volta . 'classes/sp/db_getUorgData.php');
    $RS_Unidade = db_getUorgData :: getInstanceOf($dbms, $_SESSION['LOTACAO']);

    if (nvl($w_viagem, '') != '') {
      $RSMenu_Viagem = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PDINICIAL');
      $RS_Viagem = db_getSolicList :: getInstanceOf($dbms, f($RSMenu_Viagem, 'sq_menu'), $w_usuario, 'PD', 4, formataDataEdicao($w_inicio), formataDataEdicao($w_fim), null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_usuario);
      $RS_Viagem = SortArray($RS_Viagem, 'inicio', 'desc', 'fim', 'desc');

      // Cria arrays com cada dia do período, definindo o texto e a cor de fundo para exibição no calendário
      foreach ($RS_Viagem as $row) {
        $w_saida = f($row, 'phpdt_saida');
        $w_chegada = f($row, 'phpdt_chegada');
        if (f($row, 'concluida') == 'S') {
          retornaArrayDias(f($row, 'phpdt_saida'), f($row, 'phpdt_chegada'), & $w_datas, 'Viagem a serviço\r\nSituação: Finalizada', 'N');
        }
        elseif (f($row, 'sg_tramite') == 'AE' || f($row, 'sg_tramite') == 'EE') {
          retornaArrayDias(f($row, 'phpdt_saida'), f($row, 'phpdt_chegada'), & $w_datas, 'Viagem a serviço\r\nSituação: Confirmada', 'N');
        } else {
          retornaArrayDias(f($row, 'phpdt_saida'), f($row, 'phpdt_chegada'), & $w_datas, 'Viagem a serviço\r\nSituação: Prevista', 'N');
        }
        $w_datas[formataDataEdicao($w_saida)]['valor'] = str_replace('serviço', 'serviço (saída às ' . date('H:i', $w_saida) . 'h)', $w_datas[formataDataEdicao($w_saida)]['valor']);
        $w_datas[formataDataEdicao($w_chegada)]['valor'] = str_replace('serviço', 'serviço (chegada às ' . date('H:i', $w_chegada) . 'h)', $w_datas[formataDataEdicao($w_chegada)]['valor']);
      }
      reset($RS_Viagem);
      foreach ($RS_Viagem as $row) {
        $w_saida = f($row, 'phpdt_saida');
        $w_chegada = f($row, 'phpdt_chegada');
        retornaArrayDias(f($row, 'phpdt_saida'), f($row, 'phpdt_chegada'), & $w_cores, $conTrBgColorLightRed1, 'N');
        if (date('H', $w_saida) > 13)
          $w_cores[formataDataEdicao($w_saida)]['valor'] = $conTrBgColorLightRed2;
        if (date('H', $w_chegada) < 14)
          $w_cores[formataDataEdicao($w_chegada)]['valor'] = $conTrBgColorLightRed2;
      }
    }

    // Verifica a quantidade de colunas a serem exibidas
    $w_colunas = 1;

    // Configura a largura das colunas
    switch ($w_colunas) {
      case 1 :
        $width = "100%";
        break;
      case 2 :
        $width = "50%";
        break;
      case 3 :
        $width = "33%";
        break;
      case 4 :
        $width = "25%";
        break;
      default :
        $width = "100%";
    }

    ShowHTML('        <table width="100%" border="0" align="center" CELLSPACING=0 CELLPADDING=0 BorderColorDark=' . $conTableBorderColorDark . ' BorderColorLight=' . $conTableBorderColorLight . '><tr valign="top">');

    // Exibe calendário e suas ocorrências ==============
    ShowHTML('          <td width="' . $width . '" align="center"><table border="1" cellpadding=0 cellspacing=0>');
    ShowHTML('            <tr><td colspan=3 width="100%"><table width="100%" border=0 cellpadding=0 cellspacing=0><tr>');
    ShowHTML('              <td bgcolor="' . $conTrBgColor . '"><A class="hl" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=' . $O . '&w_mes=' . $w_mes1 . '&w_ano=' . $w_ano1 . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' &SG=' . $SG . MontaFiltro('GET') . '"><<<</A>');
    ShowHTML('              <td align="center" bgcolor="' . $conTrBgColor . '"><b>Calendário ' . f($RS_Cliente, 'nome_resumido') . ' (' . f($RS_Unidade, 'nm_cidade') . ')</td>');
    ShowHTML('              <td align="right" bgcolor="' . $conTrBgColor . '"><A class="hl" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=' . $O . '&w_mes=' . $w_mes3 . '&w_ano=' . $w_ano3 . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' &SG=' . $SG . MontaFiltro('GET') . '">>>></A>');
    ShowHTML('              </table>');
    // Variáveis para controle de exibição do cabeçalho das datas especiais
    $w_detalhe1 = false;
    $w_detalhe2 = false;
    $w_detalhe3 = false;
    ShowHTML('            <tr valign="top">');
    ShowHTML('              <td align="center">' . montaCalendario($RS_Ano[$w_ano1], $w_mes1 . $w_ano1, $w_datas, $w_cores, & $w_detalhe1) . ' </td>');
    ShowHTML('              <td align="center">' . montaCalendario($RS_Ano[$w_ano], $w_mes . $w_ano, $w_datas, $w_cores, & $w_detalhe2) . ' </td>');
    ShowHTML('              <td align="center">' . montaCalendario($RS_Ano[$w_ano3], $w_mes3 . $w_ano3, $w_datas, $w_cores, & $w_detalhe3) . ' </td>');

    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
      ShowHTML('            <tr><td colspan=3 bgcolor="' . $conTrBgColor . '">');
      ShowHTML('              <b>Clique sobre o dia em destaque para ver detalhes.</b>');
    }

    // Exibe informações complementares sobre o calendário
    ShowHTML('            <tr valign="top" bgcolor="' . $conTrBgColor . '">');
    ShowHTML('              <td colspan=3 align="center">');
    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
      ShowHTML('                <table width="100%" border="0" cellspacing=1>');
      if (count($RS_Ano) == 0) {
        ShowHTML('                  <tr valign="top"><td align="center">&nbsp;');
      } else {
        ShowHTML('                  <tr valign="top"><td align="center"><b>Data<td><b>Ocorrências');
        reset($RS_Ano);
        for ($i = $w_ano1; $i <= $w_ano3; $i++) {
          $RS_Ano_Atual = $RS_Ano[$i];
          foreach ($RS_Ano_Atual as $row_ano) {
            // Exibe apenas as ocorrências do trimestre selecionado
            if (f($row_ano, 'data_formatada') >= $w_inicio && f($row_ano, 'data_formatada') <= $w_fim) {
              ShowHTML('                  <tr valign="top">');
              ShowHTML('                    <td align="center">' . date(d . '/' . m, f($row_ano, 'data_formatada')));
              ShowHTML('                    <td>' . f($row_ano, 'nome'));
            }
          }
        }
        ShowHTML('              </table>');
      }
    }
    ShowHTML('          </table>');
    // Final da exibição do calendário e suas ocorrências ==============
    ShowHTML('</table>');
    
  }
    if($_REQUEST['p_pesquisa'] == 'S' && ($_REQUEST['p_programa'] > '' or $_REQUEST['p_projeto'] > '' or $_REQUEST['p_unidade'] > '' or $_REQUEST['p_solicitante'] > '' or $_REQUEST['p_texto'] > '')){
      $RS_Resultado = db_getSolicResultado :: getInstanceOf($dbms,$w_cliente,$p_programa,$p_projeto,$p_unidade,$p_solicitante,$p_texto,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),'LISTA');
	    if ($p_ordena>'') { 
			  $lista = explode(',',str_replace(' ',',',$p_ordena));
			  $RS_Resultado = SortArray($RS_Resultado,$lista[0],$lista[1],'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
			} else {
			  $RS_Resultado = SortArray($RS_Resultado,'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
			}
    ShowHTML('<table width="100%">');
	  ShowHTML('<tr><td align="right" colspan="2"><br/><br/>');      
    if ($w_embed!='WORD') {
      CabecalhoRelatorio($w_cliente,'Visualização de resultados',4,$w_chave,null);
    }
      ShowHTML('<tr><td align="right" colspan="2"><b>Resultados: ' . count($RS_Resultado) . ' </b></td></tr>');
      ShowHTML('<tr><td align="center" colspan=2>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
      ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
      if($w_embed != 'WORD'){
        ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Mês/Ano','mes_ano').'&nbsp;</td>');
        ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Programa','cd_programa').'&nbsp;</td>');
        ShowHTML('          <td><b>&nbsp;'.linkOrdena('Agenda de Ação','cd_projeto').'&nbsp;</td>');
        ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Resultado','titulo').'&nbsp;</td>');
        ShowHTML('          <td nowrap><b>&nbsp;Ação&nbsp;</td>');
      }else{
        ShowHTML('          <td nowrap><b>&nbsp;Mês/Ano&nbsp;</td>');
        ShowHTML('          <td nowrap><b>&nbsp;Programa&nbsp;</td>');
        ShowHTML('          <td nowrap><b>&nbsp;Agenda de Ação&nbsp;</td>');
        ShowHTML('          <td nowrap><b>&nbsp;Resultado&nbsp;</td>');
      }      
      ShowHTML('        </tr>');
      $w_cor = $conTrBgColor;
      if (count($RS_Resultado) == 0) {
        ShowHTML('    <tr align="center"><td colspan="5">Nenhum resultado encontrado para os critérios informados.</td>');
      } else {
        foreach ($RS_Resultado as $row) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('    <tr valign="top" bgColor="' . $w_cor . '">');
          ShowHTML('      <td align="center" width="1%" nowrap>' . Date('d/m/Y', Nvl(f($row, 'mes_ano'), '---')) . '</td>');
          ShowHTML('      <td align="center" width="1%" title="'.f($row, 'nm_programa').'" nowrap>' . Nvl(f($row, 'cd_programa'), '---') . '</td>');
          ShowHTML('      <td align="center" width="1%" title="'.f($row, 'nm_projeto').'" nowrap>' . Nvl(f($row, 'cd_projeto'), '---') . '</td>');
          ShowHTML('      <td title="'.f($row, 'descricao').'">'.f($row, 'titulo').' </td>');
          if($w_embed != 'WORD'){
            ShowHTML('      <td nowrap><A target="item" class="HL" href="cl_pitce/projeto.php?par=atualizaetapa&R='.$w_pagina.$par.'&O=V&w_chave='.f($row, 'sq_siw_solicitacao').'&w_chave_aux='.f($row, 'sq_projeto_etapa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe dados do item">Exibir</A></td>');
          }
          ShowHTML('    </tr>');
        }
		ShowHTML('  </table>');
		ShowHTML('<tr><td>&nbsp;</td></tr>');        
      }
    }
    ShowHTML('</center>');
    if     ($w_tipo=='PDF')  RodapePDF();
    else if ($w_tipo!='WORD') Rodape();
  }

  // =========================================================================
  // Rotina principal
  // -------------------------------------------------------------------------
  function Main() {
    extract($GLOBALS);
    switch ($par) {
      case 'INICIAL' :
        Inicial();
        break;
      default :
        Cabecalho();
        BodyOpen('onLoad=this.focus();');
        Estrutura_Topo_Limpo();
        Estrutura_Menu();
        Estrutura_Corpo_Abre();
        Estrutura_Texto_Abre();
        ShowHTML('<center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center>');
        Estrutura_Texto_Fecha();
        Estrutura_Fecha();
        Estrutura_Fecha();
        Estrutura_Fecha();
        Rodape();
    }
  }
?>

