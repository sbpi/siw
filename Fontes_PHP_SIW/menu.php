<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getCustomerData.php');
include_once('classes/sp/db_getLinkData.php');
include_once('classes/sp/db_getLinkDataUser.php');
include_once('classes/sp/db_getCustomerSite.php');
include_once('classes/sp/db_getLinkSubMenu.php');
include_once('classes/sp/db_getLinkDataParent.php');
include_once('classes/sp/db_getUserData.php');
include_once('classes/sp/db_verificaSenha.php');
include_once('classes/sp/db_verificaAssinatura.php');
include_once('classes/sp/db_updatePassword.php');
// =========================================================================
//  /menu.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Monta a estrutura de frames e o menu da aplicação
// Mail     : alex@sbpi.com.br
// Criacao  : 18/03/2005 21:00
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
//
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = $_REQUEST['P3'];
$P4         = $_REQUEST['P4'];
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = strtoupper($_REQUEST['R']);
$O          = strtoupper($_REQUEST['O']);

$p_cliente  = $_SESSION['P_CLIENTE'];
$sq_pessoa  = $_SESSION['SQ_PESSOA'];
$w_pagina   = 'menu.php?par=';
$w_ImagemPadrao='images/folder/SheetLittle.gif';

if ($O=='' && $par=='TROCASENHA') { $O='A'; }

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão'; break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão'; break;
  case 'V': $w_TP=$TP.' - Envio'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default: $w_TP=$TP; 
}

$w_cliente=RetornaCliente();

Main();

FechaSessao($dbms);

// =========================================================================
// Rotina de montagem da estrutura de frames
// -------------------------------------------------------------------------
function Frames() {
  extract($GLOBALS);
  ShowHTML('<HTML> ');
  ShowHTML('  <HEAD> ');
  Estrutura_CSS($w_cliente);
  ShowHTML('  <TITLE>'.$conSgSistema.' - '.$conNmSistema.'</TITLE> ');
  ShowHTML('  <link href="images/sbpi.ico" rel="shortcut icon">');
  ShowHTML('  </HEAD> ');
  ShowHTML('    <FRAMESET COLS="20%,80%"> ');
  ShowHTML('     <FRAME SRC="menu.php?par=ExibeDocs" SCROLLING="AUTO" FRAMEBORDER="0" FRAMESPACING=0 NAME="menu"> ');
  if ($cliente=='' || $cliente==1) {
    ShowHTML('     <FRAME SRC="branco.htm" FRAMEBORDER="0" SCROLLING="AUTO"  FRAMEBORDER="0" FRAMESPACING=0 NAME="content"> ');
  } else {
    ShowHTML('     <FRAME SRC="" SCROLLING="AUTO" FRAMEBORDER="0" NAME="content"> ');
  }
  ShowHTML('    <NOFRAMES> ');
  ShowHTML('     <BODY BGCOLOR="#FFFFFF" BACKGROUND="images/bg.jpg" BGPROPERTIES="FIXED"> ');
  ShowHTML('      <P>Seu navegador não aceita <I>frames</I>. Atualize-o, preferencialmente, para o Microsoft Internet Explorer 5.5 ou superior.</P> ');
  ShowHTML('     </BODY> ');
  ShowHTML('    </FRAMESET> ');
  ShowHTML('</HTML> ');
  return $function_ret;
}

// =========================================================================
// Rotina de montagem do menu
// -------------------------------------------------------------------------
function ExibeDocs() {
  extract($GLOBALS);

  // Inclusão do arquivo da classe
  include_once("classes/menu/xPandMenu.php");

  // Instanciando a classe menu
  $root = new XMenu();
  $i    = 1;
  $j    = 1;
  $k    = 1;
  $l    = 1;

  if ($SG=='' || ($SG > '' && $O == 'L')) {

    $RS = db_getLinkDataUser::getInstanceOf($dbms, $p_cliente, $sq_pessoa, 'IS NULL');
    foreach ($RS as $row) {
      
      $w_titulo = f($row,'nome');

      if (f($row,'filho') > 0) {

        eval('$node'.i.' = &$root->addItem(new XNode(f($row,\'nome\'),false));');

        $RS1 = DB_GetLinkDataUser::getInstanceOf($dbms, $p_cliente, $sq_pessoa, f($row,'sq_menu'));
        foreach ($RS1 as $row1) {
          $w_titulo=$w_titulo.' - '.f($row1,'NOME');
          if (f($row1,'Filho') >0) {

            eval('$node'.i.'_'.j.' = &$node'.i.'->addItem(new XNode(f($row1,\'nome\'),false));');

            $RS2 = DB_GetLinkDataUser::getInstanceOf($dbms, $p_cliente, $sq_pessoa, f($row1,'sq_menu'));
            foreach ($RS2 as $row2) {

              $w_titulo=$w_titulo.' - '.f($row2,'NOME');
              if (f($row2,'Filho') > 0) {

                eval('$node'.i.'_'.j.'_'.k.' = &$node'.i.'_'.j.'->addItem(new XNode(f($row2,\'nome\'),false));');

                $RS3 = DB_GetLinkDataUser::getInstanceOf($dbms, $p_cliente, $sq_pessoa, f($row2,'sq_menu'));
                foreach ($RS3 as $row3) {

                  $w_titulo=$w_titulo.' - '.f($row3,'NOME');
                  if (f($row3,'IMAGEM') > '') $w_Imagem=f($row3,'IMAGEM'); else $w_Imagem=$w_ImagemPadrao;
                  if (f($row3,'externo')=='S') 
                     eval('$node'.i.'_'.j.'_'.k.'_'.l.' = &$node'.i.'_'.j.'_'.k.'->addItem(new XNode(f($row3,\'nome\'),str_replace(\'@files\',$conFileVirtual.$p_cliente,f($row3,\'LINK\')),$w_Imagem,$w_Imagem,f($row3,\'target\')));');
                  else
                     eval('$node'.i.'_'.j.'_'.k.'_'.l.' = &$node'.i.'_'.j.'_'.k.'->addItem(new XNode(f($row3,\'nome\'),f($row3,\'LINK\').\'&P1=\'.f($row3,\'P1\').\'&P2=\'.f($row3,\'P2\').\'&P3=\'.f($row3,\'P3\').\'&P4=\'.f($row3,\'P4\').\'&TP=<img src=\'.$w_Imagem.\' BORDER=0>\'.$w_titulo.\'&SG=\'.f($row3,\'SIGLA\'),$w_Imagem,$w_Imagem,f($row3,\'target\')));');
 
                  $w_titulo=str_replace(' - '.f($row3,'NOME'),'',$w_titulo);
                  $l = $l + 1;
                }
              } else {
                if (f($row2,'IMAGEM')>'') $w_Imagem=f($row2,'IMAGEM'); else $w_Imagem=$w_ImagemPadrao;

                if (f($row2,'externo')=='S')
                   eval('$node'.i.'_'.j.'_'.k.' = &$node'.i.'_'.j.'->addItem(new XNode(f($row2,\'nome\'),str_replace(\'@files\',$conFileVirtual.$p_cliente,f($row2,\'LINK\')),$w_Imagem,$w_Imagem,f($row2,\'target\')));');
                else
                   eval('$node'.i.'_'.j.'_'.k.' = &$node'.i.'_'.j.'->addItem(new XNode(f($row2,\'nome\'),f($row2,\'LINK\').\'&P1=\'.f($row2,\'P1\').\'&P2=\'.f($row2,\'P2\').\'&P3=\'.f($row2,\'P3\').\'&P4=\'.f($row2,\'P4\').\'&TP=<img src=\'.$w_Imagem.\' BORDER=0>\'.$w_titulo.\'&SG=\'.f($row2,\'SIGLA\'),$w_Imagem,$w_Imagem,f($row2,\'target\')));');

              }

              $w_titulo=str_replace(' - '.f($row2,'NOME'),'',$w_titulo);
              $k = $k + 1;
            }
          } else {

            if (f($row1,'IMAGEM')>'') $w_Imagem=f($row1,'IMAGEM'); else $w_Imagem=$w_ImagemPadrao;

            if (f($row1,'externo')=='S') {

              if (f($row1,'LINK')>'') 
                 eval('$node'.i.'_'.j.' = &$node'.i.'->addItem(new XNode(f($row1,\'nome\'),str_replace(\'@files\',$conFileVirtual.$p_cliente,f($row1,\'LINK\')),$w_Imagem,$w_Imagem,f($row1,\'target\')));');
              else
                 eval('$node'.i.'_'.j.' = &$node'.i.'->addItem(new XNode(f($row1,\'nome\'),\'#\'.f($row1,\'nome\'),$w_Imagem,$w_Imagem,f($row1,\'target\')));');
 
            } else {
              eval('$node'.i.'_'.j.' = &$node'.i.'->addItem(new XNode(f($row1,\'nome\'),f($row1,\'LINK\').\'&P1=\'.f($row1,\'P1\').\'&P2=\'.f($row1,\'P2\').\'&P3=\'.f($row1,\'P3\').\'&P4=\'.f($row1,\'P4\').\'&TP=<img src=\'.$w_Imagem.\' BORDER=0>\'.$w_titulo.\'&SG=\'.f($row1,\'SIGLA\'),$w_Imagem,$w_Imagem,f($row1,\'target\')));');
            }
          }
          $w_titulo=str_replace(' - '.f($row1,'NOME'),'',$w_titulo);
          $j = $j + 1;
        }
      } else {
        if (f($row,'IMAGEM')>'') $w_Imagem=f($row,'IMAGEM'); else $w_Imagem=$w_ImagemPadrao;

        if (f($row,'externo')=='S')
           eval('$node'.i.' = &$root->addItem(new XNode(f($row,\'nome\'),str_replace(\'@files\',$conFileVirtual.$p_cliente,f($row,\'LINK\')),$w_Imagem,$w_Imagem,f($row,\'target\')));');
        else
           eval('$node'.i.' = &$root->addItem(new XNode(f($row,\'nome\'),f($row,\'LINK\').\'&P1=\'.f($row,\'P1\').\'&P2=\'.f($row,\'P2\').\'&P3=\'.f($row,\'P3\').\'&P4=\'.f($row,\'P4\').\'&TP=<img src=\'.$w_Imagem.\' BORDER=0>\'.$w_titulo.\'&SG=\'.f($row,\'SIGLA\'),$w_Imagem,$w_Imagem,f($row,\'target\')));');
      }
      $i = $i +1;
    }
  } else {
    // Se for montagem de sub-menu para uma opção do menu principal
    // Se for passado o número do documento, ele é apresentado na tela, ao invés da descrição
    if ($_REQUEST['w_documento']>'') 
       $w_descricao=$_REQUEST['w_documento'];
    else {
      $RS = db_getLinkData::getInstanceOf($dbms, $p_cliente, $SG);
      $w_descricao=f($RS,'NOME');
    }

    $node1 = &$root->addItem(new XNode($w_descricao,false));

    $RS = db_getLinkSubMenu::getInstanceOf($dbms, $p_cliente, $SG);
    foreach ($RS as $row) {
      $w_titulo = $TP.' - '.f($row,'nome');
      if (f($row,'imagem') > '') $w_Imagem=f($row,'imagem'); else $w_Imagem=$w_ImagemPadrao; 
      if (f($row,'externo')=='S')
         eval('$node1_'.i.' = &$node1->addItem(new XNode(f($row,\'nome\'),str_replace(\'@files\',$conFileVirtual.$p_cliente,f($row,\'LINK\')),$w_Imagem,$w_Imagem,f($row,\'target\')));');
      else {
        if ($_REQUEST['w_cgccpf']>'')
           eval('$node'.i.' = &$node1->addItem(new XNode(f($row,\'nome\'),f($row,\'LINK\').\'&P1=\'.f($row,\'P1\').\'&P2=\'.f($row,\'P2\').\'&P3=\'.f($row,\'P3\').\'&P4=\'.f($row,\'P4\').\'&TP=<img src=\'.$w_Imagem.\' BORDER=0>\'.$w_titulo.\'&SG=\'.f($row,\'SIGLA\').\'&O=L&w_cgccpf=\'.$_REQUEST[\'w_cgccpf\'].MontaFiltro(\'GET\'),$w_Imagem,$w_Imagem,f($row,\'target\')));');
        elseif ($_REQUEST['w_usuario']>'')
              eval('$node'.i.' = &$node1->addItem(new XNode(f($row,\'nome\'),f($row,\'LINK\').\'&P1=\'.f($row,\'P1\').\'&P2=\'.f($row,\'P2\').\'&P3=\'.f($row,\'P3\').\'&P4=\'.f($row,\'P4\').\'&TP=<img src=\'.$w_Imagem.\' BORDER=0>\'.$w_titulo.\'&SG=\'.f($row,\'SIGLA\').\'&O=L&w_usuario=\'.$_REQUEST[\'w_usuario\'].\'&w_menu=\'.f($row,\'menu_pai\'),$w_Imagem,$w_Imagem,f($row,\'target\')));');
        elseif ($_REQUEST['w_sq_acordo']>'')
              eval('$node'.i.' = &$node1->addItem(new XNode(f($row,\'nome\'),f($row,\'LINK\').\'&P1=\'.f($row,\'P1\').\'&P2=\'.f($row,\'P2\').\'&P3=\'.f($row,\'P3\').\'&P4=\'.f($row,\'P4\').\'&TP=<img src=\'.$w_Imagem.\' BORDER=0>\'.$w_titulo.\'&SG=\'.f($row,\'SIGLA\').\'&O=L&w_sq_acordo=\'.$_REQUEST[\'w_sq_acordo\'].\'&w_menu=\'.f($row,\'menu_pai\'),$w_Imagem,$w_Imagem,f($row,\'target\')));');
        else
           eval('$node'.i.' = &$node1->addItem(new XNode(f($row,\'nome\'),f($row,\'LINK\').\'&P1=\'.f($row,\'P1\').\'&P2=\'.f($row,\'P2\').\'&P3=\'.f($row,\'P3\').\'&P4=\'.f($row,\'P4\').\'&TP=<img src=\'.$w_Imagem.\' BORDER=0>\'.$w_titulo.\'&SG=\'.f($row,\'SIGLA\').\'&O=\'.$O.\'&w_chave=\'.$_REQUEST[\'w_chave\'].\'&w_menu=\'.f($row,\'menu_pai\').MontaFiltro(\'GET\'),$w_Imagem,$w_Imagem,f($row,\'target\')));');
      }

      if ($_REQUEST['O']=='I') last($RS);

      $i = $i +1;
    }
    $RS = db_getLinkData::getInstanceOf($dbms, $p_cliente, $SG);
    $node2 = &$root->addItem(new XNode('Nova consulta',$w_pagina.$par.'&O=L&R='.$R.'&SG='.f($row,'sigla').'&TP='.RemoveTP($TP).'&P1='.f($row,'P1').'&P2='.f($row,'P2').'&P3='.f($row,'P3').'&P4='.f($row,'P4').MontaFiltro('GET'),$w_Imagem,$w_Imagem));
    $node3 = &$root->addItem(new XNode('Menu','menu.php?par=ExibeDocs',$w_Imagem,$w_Imagem));
    $i = 4;
  }  

  eval('$node'.i.' = &$root->addItem(new XNode(\'Sair do sistema\',\'menu.php?par=Sair\',$w_Imagem,$w_Imagem,\'_top\', \'onClick="return(confirm(\\\'Confirma saída do sistema?\\\'));"\' ));');

  // Quando for concluída a montagem dos nós, chame a função generateTree(), usando o objeto raiz, para gerar o código HTML.
  // Essa função não possui argumentos.
  // No código da função pode ser verificado que há um parâmetro opcional, usado internamente para chamadas recursivas, necessárias à montagem de toda a árvore.
  $menu_html_code = $root->generateTree();

  // A função retornou o código HTML para exibir o menu


  // Montando a página:
  // 3 pontos:
  // - Referencie o arquivo Javascript
  // - Referencie o arquivo CSS
  // - Exiba o código HTML gerado anteriormente
  ShowHTML('<html>');
  ShowHTML('<head>');
  ShowHTML('  <title>XpandMenu Multi-level</title>');
  ShowHTML('  <!-- CSS FILE for my tree-view menu -->');
  ShowHTML('  <link rel="stylesheet" type="text/css" href="classes/menu/xPandMenu.css">');
  ShowHTML('  <!-- JS FILE for my tree-view menu -->');
  ShowHTML('  <script src="classes/menu/xPandMenu.js"></script>');
  ShowHTML('</head>');
  ShowHTML('<BASEFONT FACE="Verdana, Helvetica, Sans-Serif" SIZE="2">');
  // Decide se montará o body do menu principal ou o body do sub-menu de uma opção a partir do valor de w_sq_pagina

  $RS = db_getCustomerData::getInstanceOf($dbms, $p_cliente);
  print '<BODY topmargin=0 bgcolor="#FFFFFF" BACKGROUND="'.$conFileVirtual.$p_cliente.'/img/'.f($RS,'fundo').'" BGPROPERTIES="FIXED" text="#000000" link="#000000" vlink="#000000" alink="#FF0000" ';
  if ($SG=='') {
    $RS = db_getLinkData::getInstanceOf($dbms, $p_cliente, 'MESA');
    if (!$RS->EOF) {
      if (f($RS,'IMAGEM')>'') {
        ShowHTML('onLoad=\'javascript:top.content.location="'.f($RS,'LINK').'&P1='.f($RS,'P1').'&P2='.f($RS,'P2').'&P3='.f($RS,'P3').'&P4='.f($RS,'P4').'&TP=<img src='.f($RS,'IMAGEM').' BORDER=0>'.f($RS,'nome').'&SG='.f($RS,'SIGLA').'"\'> ');
      } else {
        ShowHTML('onLoad=\'javascript:top.content.location="'.f($RS,'LINK').'&P1='.f($RS,'P1').'&P2='.f($RS,'P2').'&P3='.f($RS,'P3').'&P4='.f($RS,'P4').'&TP=<img src='.$w_ImagemPadrao.' BORDER=0>'.f($RS,'nome').'&SG='.f($RS,'SIGLA').'"\'> ');
      }
    } else {
      ShowHTML('>');
    }
  } else {
    if ($O=='L') {
      $RS = db_getLinkData::getInstanceOf($dbms, $p_cliente, $SG);
      array_key_case_change(&$RS);
      $RS = SortArray($RS,'ordem','asc');
      ShowHTML('onLoad=\'javascript:top.content.location="'.f($RS,'LINK').'&R='.$_REQUEST['R'].'&P1='.f($RS,'P1').'&P2='.f($RS,'P2').'&P3='.f($RS,'P3').'&P4='.f($RS,'P4').'&TP='.$_REQUEST['TP'].' - '.f($RS,'nome').'&SG='.f($RS,'SIGLA').'&O='.$_REQUEST['O'].MontaFiltro('GET').'";\'>');
    } else {
      $RS = db_getLinkDataParent::getInstanceOf($dbms, $p_cliente, $SG);
      array_key_case_change(&$RS);
      $RS = SortArray($RS,'ordem','asc','nome','asc');
      foreach($RS as $row) {
        if ($_REQUEST['w_cgccpf']>'') {
          ShowHTML('onLoad=\'javascript:top.content.location="'.f($row,'LINK').'&R='.$_REQUEST['R'].'&P1='.f($row,'P1').'&P2='.f($row,'P2').'&P3='.f($row,'P3').'&P4='.f($row,'P4').'&TP='.$_REQUEST['TP'].' - '.f($row,'nome').'&SG='.f($row,'SIGLA').'&O='.$_REQUEST['O'].'&w_cgccpf='.$_REQUEST['w_cgccpf'].MontaFiltro('GET').'";\'>');
        } elseif ($_REQUEST['w_usuario']>'') {
          ShowHTML('onLoad=\'javascript:top.content.location="'.f($row,'LINK').'&R='.$_REQUEST['R'].'&P1='.f($row,'P1').'&P2='.f($row,'P2').'&P3='.f($row,'P3').'&P4='.f($row,'P4').'&TP='.$_REQUEST['TP'].' - '.f($row,'nome').'&SG='.f($row,'SIGLA').'&O=L&w_usuario='.$_REQUEST['w_usuario'].MontaFiltro('GET').'";\'>');
        } else {
          ShowHTML('onLoad=\'javascript:top.content.location="'.f($row,'LINK').'&R='.$_REQUEST['R'].'&P1='.f($row,'P1').'&P2='.f($row,'P2').'&P3='.f($row,'P3').'&P4='.f($row,'P4').'&TP='.$_REQUEST['TP'].' - '.f($row,'nome').'&SG='.f($row,'SIGLA').'&O='.$_REQUEST['O'].'&w_chave='.$_REQUEST['w_chave'].'&w_menu='.f($row,'menu_pai').MontaFiltro('GET').'";\'>');
        }
        break;
      }
    }
  }

  ShowHTML('  <CENTER><table border=0 cellpadding=0 height="80" width="100%">');
  ShowHTML('      <tr><td width="100%" valign="center" align="center">');
  $RS = db_getCustomerData::getInstanceOf($dbms, $p_cliente);
  ShowHTML('         <img src="'.$conFileVirtual.$p_cliente.'/img/'.f($RS,'logo1').'" vspace="0" hspace="0" border="1"></td></tr>');
  ShowHTML('      <tr><td height=1><tr><td height=1 bgcolor="#000000">');
  ShowHTML('      <tr><td colspan=2 width="100%"><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
  ShowHTML('          <td>Usuário:<b>'.$_SESSION['NOME_RESUMIDO'].'</b></TD>');
  ShowHTML('          <td align="right"><a class="hl" href="help.php?par=Menu&TP=<img src=images/Folder/hlp.gif border=0> SIW - Visão Geral&SG=MESA&O=L" target="content" title="Exibe informações sobre os módulos do sistema."><img src="images/Folder/hlp.gif" border=0></a></TD>');
  ShowHTML('          </table>');
  ShowHTML('      <tr><td height=1><tr><td height=2 bgcolor="#000000">');
  ShowHTML('  </table></CENTER>');
  ShowHTML('  <table border=0 cellpadding=0 height="80" width="100%"><tr><td nowrap><b>');
  ShowHTML('  <div id="container">');
  echo $menu_html_code;
  ShowHTML('  </div>');
  ShowHTML('  </table>');
  ShowHTML('</body>');
  ShowHTML('</html>');
}

// =========================================================================
// Rotina de troca de senha ou assinatura eletrônica
// -------------------------------------------------------------------------
function TrocaSenha() {
  extract($GLOBALS);

  $RS           = db_getCustomerData::getInstanceOf($dbms, $p_cliente);
  $w_minimo     = f($RS,'tamanho_min_senha');
  $w_maximo     = f($RS,'TAMANHO_MAX_SENHA');
  $w_vigencia   = f($RS,'DIAS_VIG_SENHA');
  $w_aviso      = f($RS,'DIAS_AVISO_EXPIR');

  if ($P1==1) { $w_texto='Senha de Acesso'; } else { $w_texto='Assinatura Eletrônica'; }
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');

  Validate('w_atual',$w_texto.' atual','1','1',$w_minimo,$w_maximo,'1','1');
  Validate('w_nova','Nova '.$w_texto,'1','1',$w_minimo,$w_maximo,'1','1');
  Validate('w_conf','Confirmação da '.$w_texto.' atual','1','1',$w_minimo,$w_maximo,'1','1');
  ShowHTML('  if (theForm.w_atual.value == theForm.w_nova.value) { ');
  ShowHTML('     alert(\'A nova '.$w_texto.' deve ser diferente da atual!\');');
  ShowHTML('     theForm.w_nova.value=\'\';');
  ShowHTML('     theForm.w_conf.value=\'\';');
  ShowHTML('     theForm.w_nova.focus();');
  ShowHTML('     return false;');
  ShowHTML('  }');
  ShowHTML('  if (theForm.w_nova.value != theForm.w_conf.value) { ');
  ShowHTML('     alert(\'Favor informar dois valores iguais para a nova '.$w_texto.'!\');');
  ShowHTML('     theForm.w_nova.value=\'\';');
  ShowHTML('     theForm.w_conf.value=\'\';');
  ShowHTML('     theForm.w_nova.focus();');
  ShowHTML('     return false;');
  ShowHTML('  }');
  ShowHTML('  var checkStr = theForm.w_nova.value;');
  ShowHTML('  var temLetra = false;');
  ShowHTML('  var temNumero = false;');
  ShowHTML('  var checkOK = \'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz\';');
  ShowHTML('  for (i = 0;  i < checkStr.length;  i++)');
  ShowHTML('  {');
  ShowHTML('    ch = checkStr.charAt(i);');
  ShowHTML('    for (j = 0;  j < checkOK.length;  j++)');
  ShowHTML('      if (ch == checkOK.charAt(j)) temLetra = true;');
  ShowHTML('  }');
  ShowHTML('  var checkOK = \'0123456789\';');
  ShowHTML('  for (i = 0;  i < checkStr.length;  i++)');
  ShowHTML('  {');
  ShowHTML('    ch = checkStr.charAt(i);');
  ShowHTML('    for (j = 0;  j < checkOK.length;  j++)');
  ShowHTML('      if (ch == checkOK.charAt(j)) temNumero = true;');
  ShowHTML('  }');
  ShowHTML('  if (!(temLetra && temNumero))');
  ShowHTML('  {');
  ShowHTML('    alert(\'A nova '.$w_texto.' deve conter letras e números.\');');
  ShowHTML('    theForm.w_nova.value=\'\';');
  ShowHTML('    theForm.w_conf.value=\'\';');
  ShowHTML('    theForm.w_nova.focus();');
  ShowHTML('    return (false);');
  ShowHTML('  }');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=\'document.Form.w_atual.focus();\'');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();

  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('      <tr><td valign="top">Usuário:<br><b>'.$_SESSION["NOME"].' ('.$_SESSION["USERNAME"].')</b></td>');
  $RS = db_getUserData::getInstanceOf($dbms, $p_cliente, $_SESSION["USERNAME"]);

  if ($P1==1) {
    // Se for troca de senha de acesso
    ShowHTML('      <tr><td valign="top">Ultima troca de '.$w_texto.':<br><b>'.date('d/m/Y, H:i:s',toDate(f($RS,'dt_ultima_troca_senha'))).'</b></td>');
    ShowHTML('      <tr><td valign="top">Expiração da '.$w_texto.' atual ocorrerá em:<br><b>'.date('d/m/Y, H:i:s',addDays(toDate(f($RS,'dt_ultima_troca_senha')),$w_vigencia)).'</b></td>');
    ShowHTML('      <tr><td valign="top">Você será convidado a trocar sua '.$w_texto.' a partir de:<br><b>'.date('d/m/Y, H:i:s',addDays(toDate(f($RS,'dt_ultima_troca_senha')),$w_vigencia-$w_aviso)).'</b></td>');
  } else if ($P1==2) {
    // Se for troca de assinatura eletrônica
    ShowHTML('      <tr><td valign="top">Ultima troca de '.$w_texto.':<br><b>'.date('d/m/Y, H:i:s',toDate(f($RS,'dt_ultima_troca_assin'))).'</b></td>');
    ShowHTML('      <tr><td valign="top">Expiração da '.$w_texto.' atual ocorrerá em:<br><b>'.date('d/m/Y, H:i:s',addDays(toDate(f($RS,'dt_ultima_troca_assin')),$w_vigencia)).'</b></td>');
    ShowHTML('      <tr><td valign="top">Você será convidado a trocar sua '.$w_texto.' a partir de:<br><b>'.date('d/m/Y, H:i:s',addDays(toDate(f($RS,'dt_ultima_troca_assin')),$w_vigencia-$w_aviso)).'</b></td>');
  }
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('      <tr><td valign="top"><b>'.$w_texto.' <U>a</U>tual:<br><INPUT ACCESSKEY="A" class="sti" type="password" name="w_atual" size="'.$w_maximo.'" maxlength="'.$w_maximo.'"></td>');
  ShowHTML('      <tr><td valign="top"><b><U>N</U>ova '.$w_texto.':<br><INPUT ACCESSKEY="N" class="sti" type="password" name="w_nova" size="'.$w_maximo.'" maxlength="'.$w_maximo.'"></td>');
  ShowHTML('      <tr><td valign="top"><b><U>R</U>edigite nova '.$w_texto.':<br><INPUT ACCESSKEY="R" class="sti" type="password" name="w_conf" size="'.$w_maximo.'" maxlength="'.$w_maximo.'"></td>');
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');

  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Grava nova '.$w_texto.'">');
  ShowHTML('            <input class="stb" type="reset" name="Botao" value="Limpar campos" onClick=\'document.Form.w_atual.focus();\'>');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
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

  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=document.focus();');

  switch ($SG) {
  case 'SGSENHA':
    if (VerificaSenhaAcesso($_SESSION['USERNAME'],strtoupper($_REQUEST['w_atual']))) {
       db_updatePassword::getInstanceOf($dbms,$w_cliente,$_SESSION["SQ_PESSOA"],$_REQUEST["w_nova"],'PASSWORD');
       ScriptOpen('JavaScript');
       ShowHTML('  alert(\'Senha de Acesso alterada com sucesso!\');');
       ScriptClose();
    } else {
       ScriptOpen('JavaScript');
       ShowHTML('  alert(\'Senha de Acesso atual inválida!\');');
       ShowHTML('  history.back(1);');
       ScriptClose();
    } break;
  case 'SGASSINAT':
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_atual']))) {
       db_updatePassword::getInstanceOf($dbms,$w_cliente,$_SESSION["SQ_PESSOA"],$_REQUEST["w_nova"],'SIGNATURE');
       ScriptOpen('JavaScript');
       ShowHTML('  alert(\'Assinatura Eletrônica alterada com sucesso!\');');
       ScriptClose();
    } else {
       ScriptOpen('JavaScript');
       ShowHTML('  alert(\'Assinatura Eletrônica atual inválida!\');');
       ShowHTML('  history.back(1);');
       ScriptClose();
    } break;
  default:
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
    break;
  }
  // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
  $RS1 = db_getLinkData::getInstanceOf($dbms, $_SESSION["P_CLIENTE"], 'MESA');
  ScriptOpen('JavaScript');
  if (f(RS1,'IMAGEM') > '')
     ShowHTML('  location.href=\''.f($RS1,'LINK').'&P1='.f($RS1,'P1').'&P2='.f($RS1,'P2').'&P3='.f($RS1,'P3').'&P4='.f($RS1,'P4').'&TP=<img src='.f($RS1,'IMAGEM').' BORDER=0>'.f($RS1,'NOME').'&SG='.f($RS1,'SIGLA').'\'; ');
  else
     ShowHTML('  location.href=\''.f($RS1,'LINK').'&P1='.f($RS1,'P1').'&P2='.f($RS1,'P2').'&P3='.f($RS1,'P3').'&P4='.f($RS1,'P4').'&TP=<img src='.$w_ImagemPadrao.' BORDER=0>'.f($RS1,'NOME').'&SG='.f($RS1,'SIGLA').'\'; ');

  ScriptClose();
}

// =========================================================================
// Rotina de encerramento da sessão
// -------------------------------------------------------------------------
function Sair() {
  extract($GLOBALS);
  $RS = db_getCustomerSite::getInstanceOf($dbms, $p_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  top.location.href=\''.f($RS,'logradouro').'\';');
  ScriptClose();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  // Verifica se o usuário tem lotação e localização
  if ((strlen($LOTACAO.'')==0 || strlen($LOCALIZACAO.'')==0) && $LogOn=='Sim') {
     ScriptOpen('JavaScript');
     ShowHTML(' alert(\'Você não tem lotação ou localização definida. Entre em contato com o RH!\'); ');
     ShowHTML(' top.location.href=\'default.php\'; ');
     ScriptClose();
     exit();
  }

  switch ($par) {
  case 'GRAVA':         Grava(); break;
  case 'TROCASENHA':    TrocaSenha(); break;
  case 'FRAMES':        Frames(); break;
  case 'EXIBEDOCS':     ExibeDocs(); break;
  case 'SAIR':          Sair(); break;
  default:
    Cabecalho();
    BodyOpen('onLoad=document.focus();');
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
