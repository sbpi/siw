<?php
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getMenuData.php');
include_once('classes/sp/db_getMenuCode.php');
include_once('classes/sp/db_getCcData.php');
include_once('classes/sp/db_getCcTree.php');
include_once('classes/sp/db_getBankHouseList.php');
include_once('classes/sp/db_getBankHouseData.php');
include_once('classes/sp/db_getBankList.php');
include_once('classes/sp/db_getBankData.php');
include_once('classes/sp/db_getMoeda.php');
include_once('classes/sp/db_getMoedaCotacao.php');
include_once('classes/sp/db_verificaAssinatura.php');
include_once('classes/sp/dml_CtCc.php');
include_once('classes/sp/dml_CoBanco.php');
include_once('classes/sp/dml_CoAgencia.php');
include_once('classes/sp/dml_CoMoeda.php');
include_once('classes/sp/dml_CoMoedaCotacao.php');
include_once('funcoes/selecaoCCSubordination.php');
include_once('funcoes/selecaoCC.php');
include_once('funcoes/selecaoBanco.php');
include_once('funcoes/selecaoMoeda.php');


// =========================================================================
//  /Tabela_Financeiras.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia a atualiza��o das tabelas de localiza��o
// Mail     : alex@sbpi.com.br
// Criacao  : 19/03/2003, 16:35
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
//                   = N   : Nova solicita��o de envio

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'tabela_financeiras.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir_volta    = '';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$p_ordena       = $_REQUEST['p_ordena'];
$p_codigo       = $_REQUEST['p_codigo'];
$p_nome         = trim(upper($_REQUEST['p_nome']));
$p_ativo        = $_REQUEST['p_ativo'];
$p_moeda        = $_REQUEST['p_moeda'];
$p_inicio       = $_REQUEST['p_inicio'];
$p_fim          = $_REQUEST['p_fim'];

if ($O=='' && upper($_REQUEST['par'])=='MOEDACOTACAO') {
  $O='P';
} elseif ($O=='') {
  $O='L';
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o'; break;
  case 'A': $w_TP=$TP.' - Altera��o'; break;
  case 'E': $w_TP=$TP.' - Exclus�o'; break;
  case 'V': $w_TP=$TP.' - Envio'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'R': $w_TP=$TP.' - Acessos'; break;
  case 'D': $w_TP=$TP.' - Desativar'; break;
  case 'T': $w_TP=$TP.' - Ativar'; break;
  case 'H': $w_TP=$TP.' - Heran�a'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

if(nvl($w_menu,'')!=''){
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS_Menu,'libera_edicao');
  
  if ($w_libera_edicao=='N' && strpos('LP',$O)===false) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b>Opera��o n�o permitida!</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exit();
  }
}

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de centros de custo
// -------------------------------------------------------------------------
function CentroCusto() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ImagemPadrao = 'images/Folder/SheetLittle.gif';
  $w_troca        = $_REQUEST['w_troca'];
  $w_heranca      = $_REQUEST['w_heranca'];
  $w_sq_cc        = $_REQUEST['w_sq_cc'];

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  if ($w_troca>'' && strpos('EDT',$O)===false) {
    // Se for recarga da p�gina
    $w_sq_cc_pai    = $_REQUEST['w_sq_cc_pai'];
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_receita      = $_REQUEST['w_receita'];
    $w_cliente      = $_REQUEST['w_cliente'];
    $w_sigla        = $_REQUEST['w_sigla'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_regular      = $_REQUEST['w_regular'];
  } elseif ($O!='L') {
    if ($O!='P' && $O!='H') {
      if ($w_heranca>'' || $O!='I') {
        // Se for heran�a, atribui a chave da op��o selecionada para w_sq_cc
        if ($w_heranca>'') $w_sq_cc = $w_heranca;
        $SQL = new db_getCcData; $RS = $SQL->getInstanceOf($dbms,$w_sq_cc);
        $w_sq_cc_pai    = f($RS,'sq_cc_pai');
        $w_nome         = f($RS,'nome');
        $w_sigla        = f($RS,'sigla');
        $w_descricao    = f($RS,'descricao');
        $w_ativo        = f($RS,'ativo');
        $w_receita      = f($RS,'receita');
        $w_regular      = f($RS,'regular');
      } elseif ($w_troca>'') {
        $w_sq_cc_pai    = $_REQUEST['w_sq_cc_pai'];
        $w_nome         = $_REQUEST['w_nome'];
        $w_descricao    = $_REQUEST['w_descricao'];
        $w_regular      = $_REQUEST['w_regular'];
        $w_cliente      = $_REQUEST['w_cliente'];
        $w_sigla        = $_REQUEST['w_sigla'];
        $w_ativo        = $_REQUEST['w_ativo'];
        $w_regular      = $_REQUEST['w_regular'];
      } 
    }
  }
  if ($O!='L') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($O!='P' && $O!='H') {
      if ($O=='I' || $O=='A') {
        Validate('w_nome','Nome','1','1','3','60','1','1');
        Validate('w_descricao','Descri��o','1','1','5','500','1','1');
        Validate('w_sigla','Sigla','1','1','2','20','1','1');
      } 
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    } elseif ($O=='H') {
      Validate('w_heranca','Origem dos dados','SELECT','1','1','10','','1');
      ShowHTML('  if (confirm("Confirma heran�a dos dados da op��o selecionada?")) {');
      ShowHTML('     window.close(); ');
      ShowHTML('     opener.focus(); ');
      ShowHTML('     return true; ');
      ShowHTML('  } ');
      ShowHTML('  else { return false; } ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<style> ');
  ShowHTML(' .lh{text-decoration:none;font=Arial;color="#FF0000"} ');
  ShowHTML(' .lh:HOVER{text-decoration: underline;} ');
  ShowHTML('</style> ');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_nome.focus();"');
  } elseif ($O=='H') {
    BodyOpen('onLoad="document.Form.w_heranca.focus();"');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } 
  if ($O!='H') {
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="99%" border="0">');
  if ($O=='L') {
    ShowHTML('      <tr><td><a accesskey="I" class="ss" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('      <tr><td height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td><b>');
    $SQL = new db_getCcTree; $RS = $SQL->getInstanceOf($dbms,$w_cliente,'IS NULL');
    $w_contOut = 0;
    foreach($RS as $row) {
      $w_titulo  = f($row,'sigla');
      $w_contOut = $w_contOut+1;
      if (f($row,'Filho')>0) {
        ShowHTML('<A HREF=#"'.f($row,'sq_cc').'"></A>');
        ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row,'sigla').'');
        if (f($row,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cc='.f($row,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Altera as informa��es deste centro de custos">AL</A>&nbsp');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_cc='.f($row,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Exclui o centro de custos">EX</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_cc='.f($row,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Impede que este centro de custos seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_cc='.f($row,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Permite que este centro de custos seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('       </div></span>');
        ShowHTML('   <div style="position:relative; left:12;">');
        $SQL = new db_getCcTree; $RS1 = $SQL->getInstanceOf($dbms,$w_cliente,f($row,'sq_cc'));
        foreach($RS1 as $row1) {
          $w_titulo = $w_titulo.' - '.f($row1,'sigla');
          if (f($row1,'Filho')>0) {
            $w_contOut=$w_contOut+1;
            ShowHTML('<A HREF=#"'.f($row1,'sq_cc').'"></A>');
            ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row1,'sigla').'');
            if (f($row1,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cc='.f($row1,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Altera as informa��es deste centro de custos">AL</A>&nbsp');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_cc='.f($row1,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Exclui o centro de custos">EX</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_cc='.f($row1,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Impede que este centro de custos seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_cc='.f($row1,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Permite que este centro de custos seja associado a novos registros">Ativar</A>&nbsp');
            }
            ShowHTML('       </div></span>');
            ShowHTML('   <div style="position:relative; left:12;">');
            $SQL = new db_getCcTree; $RS2 = $SQL->getInstanceOf($dbms,$w_cliente,f($row1,'sq_cc'));
            foreach($RS2 as $row2) {
              $w_titulo = $w_titulo.' - '.f($row2,'sigla');
              if (f($row2,'Filho')>0) {
                $w_contOut = $w_contOut+1;
                ShowHTML('<A HREF=#"'.f($row2,'sq_cc').'"></A>');
                ShowHTML('<span><div align="left"><img src="images/Folder/FolderClose.gif" border=0 align="center"> '.f($row2,'sigla').'');
                if (f($row2,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cc='.f($row2,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Altera as informa��es deste centro de custos">AL</A>&nbsp');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_cc='.f($row2,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Exclui o centro de custos">EX</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_cc='.f($row2,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Impede que este centro de custos seja associado a novos registros">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_cc='.f($row2,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Permite que este centro de custos seja associado a novos registros">Ativar</A>&nbsp');
                } 

                ShowHTML('       </div></span>');
                ShowHTML('   <div style="position:relative; left:12;">');
                $SQL = new db_getCcTree; $RS3 = $SQL->getInstanceOf($dbms,$w_cliente,f($row2,'sq_cc'));
                foreach($RS3 as $row3) {
                  $w_titulo = $w_titulo.' - '.f($row3,'sigla');
                  ShowHTML('<A HREF=#"'.f($row3,'sq_cc').'"></A>');
                  ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row3,'sigla'));
                  if (f($row3,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cc='.f($row3,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Altera as informa��es deste centro de custos">AL</A>&nbsp');
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_cc='.f($row3,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Exclui o centro de custos">EX</A>&nbsp');
                  if (f($row3,'ativo')=='S') {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_cc='.f($row3,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Impede que este centro de custos seja associado a novos registros">Desativar</A>&nbsp');
                  } else {
                    ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_cc='.f($row3,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Permite que este centro de custos seja associado a novos registros">Desativar</A>&nbsp');
                  } 
                  ShowHTML('    <BR>');
                  $w_titulo = str_replace(' - '.f($row3,'sigla'),'',$w_titulo);
                } 
                ShowHTML('   </div>');
              } else {
                ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row2,'sigla'));
                if (f($row2,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cc='.f($row2,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Altera as informa��es deste centro de custos">AL</A>&nbsp');
                ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_cc='.f($row2,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Exclui o centro de custos">EX</A>&nbsp');
                if (f($row2,'ativo')=='S') {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_cc='.f($row2,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Impede que este centro de custos seja associado a novos registros">Desativar</A>&nbsp');
                } else {
                  ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_cc='.f($row2,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Permite que este centro de custos seja associado a novos registros">Ativar</A>&nbsp');
                } 
                ShowHTML('    <BR>');
              } 
              $w_titulo=str_replace(' - '.f($row2,'sigla'),'',$w_titulo);
            } 
            ShowHTML('   </div>');
          } else {
            $w_Imagem=$w_ImagemPadrao;
            ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row1,'sigla'));
            if (f($row1,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cc='.f($row1,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Altera as informa��es deste centro de custos">AL</A>&nbsp');
            ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_cc='.f($row1,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Exclui o centro de custos">EX</A>&nbsp');
            if (f($row1,'ativo')=='S') {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_cc='.f($row1,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Impede que este centro de custos seja associado a novos registros">Desativar</A>&nbsp');
            } else {
              ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_cc='.f($row1,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Permite que este centro de custos seja associado a novos registros">Ativar</A>&nbsp');
            } 
            ShowHTML('    <BR>');
          } 
          $w_titulo=str_replace(' - '.f($row1,'sigla'),'',$w_titulo);
        } 
        ShowHTML('   </div>');
      } else {
        $w_Imagem=$w_ImagemPadrao;
        ShowHTML('    <img src="'.$w_Imagem.'" border=0 align="center"> '.f($row,'sigla').'');
        if (f($row,'ativo')=='S') $w_classe='hl'; else $w_classe='lh';
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cc='.f($row,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Altera as informa��es deste centro de custos">AL</A>&nbsp');
        ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_cc='.f($row,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Exclui o centro de custos">EX</A>&nbsp');
        if (f($row,'ativo')=='S') {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=D&w_sq_cc='.f($row,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Impede que este centro de custos seja associado a novos registros">Desativar</A>&nbsp');
        } else {
          ShowHTML('       <A class="'.$w_classe.'" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=T&w_sq_cc='.f($row,'sq_cc').'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=CT_CC" title="Permite que este centro de custos seja associado a novos registros">Ativar</A>&nbsp');
        } 
        ShowHTML('    <BR>');
      } 
    } 
    if ($w_contOut==0) {
      // Se n�o achou registros
      ShowHTML('N�o foram encontrados registros.');
    } 
  } elseif ($O!='H') {
    if ($O!='I' && $O!='A') $w_Disabled='disabled';
    // Se for inclus�o de nova op��o, permite a heran�a dos dados de outra, j� existente.
    if ($O=='I') {
      ShowHTML('      <tr><td><a accesskey="H" class="ss" HREF="javascript:this.status.value;" onClick="window.open(\''.$w_pagina.$par.'&R='.$w_pagina.'CENTROCUSTO&O=H&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_cc='.$w_sq_cc.'\',\'heranca\',\'top=70,left=10,width=780,height=200,toolbar=no,status=no,scrollbars=no\');"><u>H</u>erdar dados</a>&nbsp;');
      ShowHTML('      <tr><td height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td><b>');      
    } 
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cc" value="'.$w_sq_cc.'">');
    ShowHTML('      <tr><td valign="top"><table width="100%" border=0><tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:<br><INPUT ACCESSKEY="D" TYPE="TEXT" CLASS="sti" NAME="w_nome" SIZE=40 MAXLENGTH=60 VALUE="'.$w_nome.'" '.$w_Disabled.' title="Nome do centro de custo."></td>');
    // Recupera a lista de op��es
    if ($O!='I' && $O!='H') {
      // Se for altera��o, n�o deixa vincular a op��o a ela mesma, nem a seus filhos
      selecaoCCSubordination('<u>S</u>ubordina��o:','S','Se esta op��o estiver subordinada a outra j� existente, informe qual.',$w_sq_cc,$w_sq_cc_pai,'w_sq_cc_pai','PARTE',null);
    } else {
      selecaoCCSubordination('<u>S</u>ubordina��o:','S','Se esta op��o estiver subordinada a outra j� existente, informe qual.',$w_sq_cc,$w_sq_cc_pai,'w_sq_cc_pai','TODOS',null);
    } 
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top" colspan=3><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY="C" class="sti" name="w_descricao" rows=5 cols=80 title="Descreva sucintamente o centro de custo." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td valign="top"><table width="100%" border=0><tr valign="top">');
    ShowHTML('          <td valign="top"><b>S<u>i</u>gla:<br><INPUT ACCESSKEY="S" TYPE="TEXT" CLASS="sti" NAME="w_sigla" SIZE=20 MAXLENGTH=20 VALUE="'.$w_sigla.'" '.$w_Disabled.' title="Informe a sigla desejada para o centro de custo."></td>');
    MontaRadioSN('<b>Regular?</b>',$w_regular,'w_regular','Informe "Sim" se este centro de custo for regular, e "N�o" se ele for extra-or�ament�rio.');
    MontaRadioSN('<b>Recebimento?</b>',$w_receita,'w_receita','Informe "Sim" se este centro de custo for relativo a recebimentos, e "N�o" se ele for relativo a pagamentos.');
    ShowHTML('          </table>');
    if ($O=='I') {
      ShowHTML('      <tr align="left">');
      MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </td></tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    if ($O=='E') {
      ShowHTML('    <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Atualizar">');
    } elseif ($O=='T') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Ativar">');
    } elseif ($O=='D') {
        ShowHTML('  <tr><td align="center"  colspan="3"><input class="stb" type="submit" name="Botao" value="Desativar">');
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('</FORM>');
  } elseif ($O=='H') {
    AbreForm('Form',$R,'POST','return(Validacao(this));','content',$P1,$P2,$P3,$P4,$TP,$SG,$R,'I');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cc" value="'.$w_sq_cc.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Selecione, na rela��o, a op��o a ser utilizada como origem de dados.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td width="100%">');
    ShowHTML('    <table align="center" border="0">');
    ShowHTML('      <tr><td valign="top"><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('      <tr>');
    selecaoCC('<u>O</u>rigem:','O','Selecione na lista o centro de custo a ser usado como origem de dados.',$w_heranca,null,'w_heranca',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center">&nbsp;');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Herdar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  if ($O!='H') {
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de ag�ncias
// -------------------------------------------------------------------------
function Agencia() {
  extract($GLOBALS);
  global $w_Disabled;
  $p_sq_banco = upper($_REQUEST['p_sq_banco']);

  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS,'libera_edicao');

  if ($O!='I' && $p_sq_banco=='') $O='P';

  if ($w_troca>'' && $O!='E') {
    $w_sq_agencia   = $_REQUEST['w_sq_agencia'];
    $w_sq_banco     = $_REQUEST['w_sq_banco'];
    $w_codigo       = $_REQUEST['w_codigo'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_nome         = $_REQUEST['w_nome'];
  } elseif ($O=='L') {
    $SQL = new db_getBankHouseList; $RS = $SQL->getInstanceOf($dbms,$p_sq_banco,$p_nome,$p_ordena,null);
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao', 'desc', 'nome','asc');
    }
  } elseif ($O=='A' || $O=='E') {
    $w_sq_agencia   = $_REQUEST['w_sq_agencia'];
    $SQL = new db_getBankHouseData; $RS = $SQL->getInstanceOf($dbms,$w_sq_agencia);
    $w_sq_banco     = f($RS,'sq_banco');
    $w_codigo       = f($RS,'codigo');
    $w_ativo        = f($RS,'ativo');
    $w_padrao       = f($RS,'padrao');
    $w_nome         = f($RS,'nome');
  } 

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_banco','Banco','SELECT','1','1','18','1','1');
      Validate('w_codigo','C�digo','1','1','4','6','X','0123456789-');
      Validate('w_nome','Nome','1','1','3','60','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclus�o deste registro?")) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_sq_banco','Banco','SELECT','1','1','3','1','1');
      Validate('p_nome','nome','1','','3','50','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') {
      BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
    } else {
      BodyOpen('onLoad="document.Form.w_sq_banco.focus()";');
    } 
  } elseif ($O=='P') {
    BodyOpen('onLoad="document.Form.p_sq_banco.focus()";');
  } else {
    BodyOpen('onLoad="this.focus();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('        <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    } 
    ShowHTML('        <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"></u>F</u>iltrar (Ativo)</font>' : '</u>F</u>iltrar (Inativo)').'</a>');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
    ShowHTML('          <td align="center"><b>Chave</td>');
    ShowHTML('          <td align="center"><b>Banco</td>');
    ShowHTML('          <td align="center"><b>C�digo</td>');
    ShowHTML('          <td align="center"><b>Nome</td>');
    ShowHTML('          <td align="center"><b>Ativo</td>');
    ShowHTML('          <td align="center"><b>Padr�o</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover" ><b>Opera��es</td>');
    }  
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_agencia').'</td>');
        ShowHTML('        <td align="center">'.f($row,'sq_banco').'</td>');
        ShowHTML('        <td align="center">'.f($row,'codigo').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'padrao').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_agencia='.f($row,'sq_agencia').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_agencia='.f($row,'sq_agencia').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        } 
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_agencia" value="'.$w_sq_agencia.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr>');
    selecaoBanco('<u>B</u>anco:','B',null,$w_sq_banco,null,'w_sq_banco',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left"><td valign="top"><table width="100%" cellpadding=0 cellspacing=0><tr><td>');
    ShowHTML('          <td valign="top"><b><U>C</U>�digo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_codigo" size="6" maxlength="6" value="'.$w_codigo.'"></td>');
    ShowHTML('          <td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="40" maxlength="40" value="'.$w_nome.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr>');
    MontaRadioNS('Padr�o?',$w_padrao,'w_padrao');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr align="left">');
    selecaoBanco('<u>B</u>anco:','B',null,$p_sq_banco,null,'p_sq_banco',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_nome" size="40" maxlength="40" value="'.$p_nome.'"></td>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    ShowHTML('          <td valign="top"><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if ($p_ordena=='NOME') {
      ShowHTML('          <option value="">C�digo<option value="nome" SELECTED>Nome<option value="ativo">Ativo');
    } elseif ($p_ordena=='ATIVO') {
      ShowHTML('          <option value="">C�digo<option value="nome">Nome<option value="ativo" SELECTED>Ativo');
    } else {
      ShowHTML('          <option value="" SELECTED>C�digo<option value="nome">Nome<option value="ativo">Ativo');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td>');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';" name="Botao" value="Incluir">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_sq_banco='.$p_sq_banco.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();

  return $function_ret;
} 

// =========================================================================
// Rotina da tabela de bancos
// -------------------------------------------------------------------------
function Banco() {
  extract($GLOBALS);
  global $w_Disabled;
  $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS,'libera_edicao');
  $w_sq_banco      = $_REQUEST['w_sq_banco'];
  $p_ordena        = $_REQUEST['p_ordena'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_codigo        = $_REQUEST['w_codigo'];
    $w_codigo_atual  = $_REQUEST['w_codigo_atual'];    
    $w_nome          = $_REQUEST['w_nome'];
    $w_padrao        = $_REQUEST['w_padrao'];
    $w_ativo         = $_REQUEST['w_ativo'];
    $w_exige         = $_REQUEST['w_exige'];    
  } elseif ($O=='L') {
    $SQL = new db_getBankList; $RS = $SQL->getInstanceOf($dbms,$p_codigo,$p_nome,$p_ativo);
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao', 'desc', 'codigo','asc');
    }
  } elseif ($O=='A' || $O=='E') {
    $w_sq_banco = $_REQUEST['w_sq_banco'];
    $SQL = new db_getBankData; $RS = $SQL->getInstanceOf($dbms,$w_sq_banco);
    $w_nome         = f($RS,'nome');
    $w_padrao       = f($RS,'padrao');
    $w_codigo       = f($RS,'codigo');
    $w_codigo_atual = f($RS,'codigo');    
    $w_ativo        = f($RS,'ativo');
    $w_exige        = f($RS,'exige_operacao');    
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_codigo','C�digo','1','1','3','3','','1');
      Validate('w_nome','Nome','1','1','3','30','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclus�o deste registro?")) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_codigo','C�digo','1','','3','3','','0123456789');
      Validate('p_nome','nome','1','','3','30','1','1');
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_codigo.focus();');
  } elseif ($O=='H') {
    BodyOpen('onLoad=document.Form.w_heranca.focus();');
  } elseif ($O=='L' || $O=='P') {
    BodyOpen('onLoad=this.focus();');
  } else {
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('        <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    } 
    ShowHTML('        <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"></u>F</u>iltrar (Ativo)</font>' : '</u>F</u>iltrar (Inativo)').'</a>');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Chave','sq_banco').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('C�digo','codigo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Padr�o','padrao').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b>Opera��es</td>');
    } 
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($row,'sq_banco').'</td>');
        ShowHTML('        <td align="center">'.f($row,'codigo').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ativo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'padrao').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_banco='.f($row,'sq_banco').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_banco='.f($row,'sq_banco').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"title="Excluir">EX</A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_sq_banco" value="'.$w_sq_banco.'">');
    ShowHTML('<INPUT type="hidden" name="w_codigo_atual" value="'.$w_codigo_atual.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>C</U>�digo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_codigo" size="3" maxlength="3" value="'.$w_codigo.'"></td>');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'"></td>');
    ShowHTML('      <tr align="left">');
    MontaRadioNS('Padr�o?',$w_padrao,'w_padrao');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$w_ativo,'w_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Exige opera��o?',$w_exige,'w_exige');
    ShowHTML('      </tr>');    
    ShowHTML('      <tr><td valign="top"><b>'.$_SESSION['LABEL_CAMPO'].':<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('    <input class="stb" type="submit" name="Botao" value="Excluir">');
    } elseif ($O=='I') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Incluir">');
    } elseif ($O=='A') {
        ShowHTML('  <input class="stb" type="submit" name="Botao" value="Atualizar">');
    }
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><U>C</U>�digo:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="p_codigo" size="3" maxlength="3" value="'.$p_codigo.'"></td>');
    ShowHTML('      <tr><td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_nome" size="50" maxlength="50" value="'.$p_nome.'"></td>');
    ShowHTML('      <tr align="left">');
    MontaRadioSN('Ativo?',$p_ativo,'p_ativo');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    ShowHTML('          <td valign="top"><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if ($p_ordena=='nome') {
      ShowHTML('          <option value="sq_banco">Chave<option value="nome" SELECTED>Nome<option value="">C�digo<option value="ativo">Ativo');
    } elseif ($p_ordena=='chave') {
      ShowHTML('          <option value="sq_banco" SELECTED>Chave<option value="nome">Nome<option value="">C�digo<option value="ativo">Ativo');
    } elseif ($p_ordena=='ativo') {
      ShowHTML('          <option value="sq_banco">Chave<option value="nome">Nome<option value="">C�digo<option value="ativo" SELECTED>Ativo');
    } else{
      ShowHTML('          <option value="sq_banco">Chave<option value="nome">Nome<option value="" SELECTED>C�digo<option value="ativo">Ativo');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td>');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();

  return $function_ret;
} 
// =========================================================================
// Rotina da tabela de Moedas
// -------------------------------------------------------------------------
function Moeda() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];

  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem dos tipos de afastamento</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);

  if ($w_troca>'' && $O!='E') {
    $w_tipo           = $_REQUEST['w_tipo'];
    $w_nome           = $_REQUEST['w_nome'];
    $w_codigo         = $_REQUEST['w_codigo'];
    $w_sigla          = $_REQUEST['w_sigla'];
    $w_simbolo        = $_REQUEST['w_simbolo'];
    $w_tipo           = $_REQUEST['w_tipo'];
    $w_exclusao_ptax  = $_REQUEST['w_exclusao_ptax'];
    $w_ativo          = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    $SQL = new db_getMoeda; $RS = $SQL->getInstanceOf($dbms,null,null,null,null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (!(strpos('AEV',$O)===false) || $w_troca>'') {
    $SQL = new db_getMoeda; $RS = $SQL->getInstanceOf($dbms,$w_chave,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_tipo          = f($RS,'sq_tipo_posto');
    $w_nome          = f($RS,'nome');
    $w_codigo        = f($RS,'codigo');
    $w_sigla         = f($RS,'sigla');
    $w_simbolo       = f($RS,'simbolo');
    $w_tipo          = f($RS,'tipo') ;
    $w_exclusao_ptax = formataDataEdicao(f($RS,'exclusao_ptax'));
    $w_ativo = f($RS,'ativo');
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_codigo','C�digo','1','1','3','3','','0123456789');
      Validate('w_nome','Nome','1','1','3','30','1','1');
      Validate('w_sigla','Sigla','1','1','3','5','1','');
      Validate('w_simbolo','S�mbolo','1','1','1','10','1','');
      Validate('w_tipo','Tipo','1','1','1','1','1','');
      Validate('w_exclusao_ptax','Exclus�o PTAX','DATA','','10','10','','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclus�o deste registro?")) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=\'document.Form.w_tipo.focus();\'');
  } elseif ($O=='L') {
    BodyOpen('onLoad=\'this.focus();\'');
  } else{
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('C�digo','codigo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('S�mbolo','simbolo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','tipo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Exclus�o PTAX','exclusao_ptax').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativo').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td class="remover"><b> Opera��es </td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'codigo').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'simbolo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'tipo').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'exclusao_ptax'),5).'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_moeda').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Altera dos dados deste registro.">AL </A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_moeda').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'" Title="Exclui este registro.">EX </A>&nbsp');
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
    //Aqui come�a a manipula��o de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('         <td colspan=2 width="100%"><table width="100%" border="0">');
    ShowHTML('            <tr><td colspan=2><b><u>C</u>�digo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="sti" SIZE="3" MAXLENGTH="3" VALUE="'.$w_codigo.'"></td>');
    ShowHTML('         </table>');
    ShowHTML('         </td>');
    ShowHTML('      </tr>');
    ShowHTML('      <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td colspan=2><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S"  type="text" name="w_sigla" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      <tr><td colspan=2><b><u>S</u>�mbolo:</b><br><input '.$w_Disabled.' accesskey="S"  type="text" name="w_simbolo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_simbolo.'"></td>');
    ShowHTML('      <tr><td colspan=2><b><u>T</u>ipo:</b><br><input '.$w_Disabled.' accesskey="T"  type="text" name="w_tipo" class="sti" SIZE="1" MAXLENGTH="1" VALUE="'.$w_tipo.'"></td>');
    ShowHTML('      <tr><td colspan=2><b><u>E</u>xclus�o PTAX:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_exclusao_ptax" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_exclusao_ptax.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td colspan=5 align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
}
// =========================================================================
// Rotina de cadastramento das cota��es
// -------------------------------------------------------------------------
function MoedaCotacao() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_moeda            = $_REQUEST['w_moeda'];
    $w_data             = $_REQUEST['w_data'];
    $w_taxa_compra      = $_REQUEST['w_taxa_compra'];
    $w_taxa_venda       = $_REQUEST['w_taxa_venda'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getMoedaCotacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$p_moeda,$p_inicio,$p_fim,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'data','desc','nm_moeda','asc');
    } else {
      $RS = SortArray($RS,'data','desc','nm_moeda','asc');
    }
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado
    $sql = new db_getMoedaCotacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_moeda            = f($RS,'sq_moeda');
    $w_nm_moeda         = f($RS,'nm_moeda');
    $w_data             = formataDataEdicao(f($RS,'data'));
    $w_taxa_compra      = formatNumber(f($RS,'taxa_compra'),4);
    $w_taxa_venda       = formatNumber(f($RS,'taxa_venda'),4);
  } 

  Cabecalho();
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    SaltaCampo();
    CheckBranco();
    FormataData();
    FormataValor();
    if ($O=='P') {
      ShowHTML('function hoje() {');
      ShowHTML('  var obj = document.Form;');
      ShowHTML('  obj.p_inicio.value = "'.date('d/m/Y',time()).'";');
      ShowHTML('  obj.p_fim.value    = "'.date('d/m/Y',time()).'";');
      ShowHTML('}');
      ShowHTML('function mes() {');
      ShowHTML('  var obj = document.Form;');
      ShowHTML('  obj.p_inicio.value = "'.date('01/m/Y',time()).'";');
      ShowHTML('  obj.p_fim.value    = "'.date('d/m/Y',last_day(time())).'";');
      ShowHTML('}');
      ShowHTML('function ano() {');
      ShowHTML('  var obj = document.Form;');
      ShowHTML('  obj.p_inicio.value = "'.date('01/01/Y',time()).'";');
      ShowHTML('  obj.p_fim.value    = "'.date('31/12/Y',time()).'";');
      ShowHTML('}');
    }
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      if ($O=='I') {
        Validate('w_data','Data','DATA','1','10','10','','0123456789/');
        Validate('w_moeda','Moeda','SELECT','1','1','18','','1');
      }
      Validate('w_taxa_compra','Taxa de compra','VALOR','1',6,18,'','0123456789,.');
      Validate('w_taxa_venda','Taxa de venda','VALOR','1',6,18,'','0123456789,.');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
    } elseif ($O=='P') {
      Validate('p_inicio','In�cio do per�odo','DATA','','10','10','','0123456789/');
      Validate('p_fim','T�rmino do per�odo','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_inicio.value != "" && theForm.p_fim.value == "") || (theForm.p_inicio.value == "" && theForm.p_fim.value != "")) {');
      ShowHTML('     alert ("Informe ambas as datas de recebimento ou nenhuma delas!");');
      ShowHTML('     theForm.p_inicio.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_inicio','In�cio do per�odo','<=','p_fim','T�rmino do per�odo');      
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclus�o deste registro?")) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    if ($O=='P') ShowHTML('  theForm.Botao[2].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_data.focus()\';');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_taxa_compra.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'">'.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? '<font color="#BC5100"></u>F</u>iltrar (Ativo)</font>' : '</u>F</u>iltrar (Inativo)').'</a>');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td>'.linkOrdena('Data','data').'</td>');
    ShowHTML('          <td>'.linkOrdena('Moeda','nm_moeda').'</td>');
    ShowHTML('          <td>'.linkOrdena('Taxa Compra','taxa_compra').'</td>');
    ShowHTML('          <td>'.linkOrdena('Taxa Venda','taxa_venda').'</td>');
    ShowHTML('          <td class="remover"><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'data')).'</td>');
        ShowHTML('        <td>'.f($row,'nm_moeda').'</td>');
        ShowHTML('        <td align="center">'.formatNumber(f($row,'taxa_compra'),4).'</td>');
        ShowHTML('        <td align="center">'.formatNumber(f($row,'taxa_venda'),4).'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_moeda_cotacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"title="Alterar">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_moeda_cotacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET').'"title="Excluir">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&p_chave='.$p_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&p_chave='.$p_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('<p>&nbsp;</p></tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled   = ' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O!='I') ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML(montaFiltro('POST'));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    if ($O=='A') {
      ShowHTML('          <td><b>Data:<br>'.$w_data.'</b></td>');
      ShowHTML('          <td><b>Moeda:<br>'.$w_nm_moeda.'</b></td>');
      ShowHTML('<INPUT type="hidden" name="w_data" value="'.$w_data.'">');
      ShowHTML('<INPUT type="hidden" name="w_moeda" value="'.$w_moeda.'">');
    } else {
      ShowHTML('          <td title="Informe a data da cota��o."><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyUp="SaltaCampo(this.form.name,this,10,event);" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_data',$w_dir_volta).'</td>');
      selecaoMoeda('<u>M</u>oeda:','M','Selecione a moeda na rela��o.',$w_moeda,null,'w_moeda','ATIVO',null);
    }
    ShowHTML('          <td title="Informe a taxa de compra."><b><u>C</u>ompra:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_taxa_compra" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_taxa_compra.'" style="text-align:right;" onKeyDown="FormataValor(this,18,4,event);"></td>');
    ShowHTML('          <td title="Informe a taxa de venda."><b><u>V</u>enda:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_taxa_venda" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_taxa_venda.'" style="text-align:right;" onKeyDown="FormataValor(this,18,4,event);"></td>');
    ShowHTML('      <tr><td colspan=4 align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML(montaFiltro('POST',true));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>P</u>er�odo:</b>');
    ShowHTML('            [<A CLASS="HL" HREF="javascript:hoje()">Hoje</A>]');
    ShowHTML('            [<A CLASS="HL" HREF="javascript:mes()">M�s</A>]');
    ShowHTML('            [<A CLASS="HL" HREF="javascript:ano()">Ano</A>]');
    ShowHTML('            <br><input type="text" name="p_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio.'" onKeyUp="SaltaCampo(this.form.name,this,10,event);" onKeyDown="FormataData(this,event);"> a <input type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyUp="SaltaCampo(this.form.name,this,10,event);" onKeyDown="FormataData(this,event);"></td>');
    selecaoMoeda('<u>M</u>oeda:','M','Selecione a moeda na rela��o.',$p_moeda,null,'p_moeda','ATIVO',null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';" name="Botao" value="Incluir">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_sq_banco='.$p_sq_banco.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Rodape();
} 
// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');

  switch ($SG) {
    case 'CT_CC':
    // Verifica se a Assinatura Eletr�nica � v�lida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CtCC; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_sq_cc'],$_REQUEST['w_sq_cc_pai'],$w_cliente,$_REQUEST['w_nome'],
            $_REQUEST['w_descricao'],$_REQUEST['w_sigla'],$_REQUEST['w_receita'],$_REQUEST['w_regular'],
            $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COBANCO':
      $p_nome   = upper($_REQUEST['p_nome']);
      $p_codigo = upper($_REQUEST['p_codigo']);
      $p_ativo  = upper($_REQUEST['p_ativo']);
      $p_exige  = upper($_REQUEST['p_exige']);      
      $p_ordena = $_REQUEST['p_ordena'];
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if  ($_REQUEST['w_codigo']!= nvl($_REQUEST['w_codigo_atual'],'')) {
          if ($O=='I' || $O =='A') {
            // Verifica se j� existe o c�digo do banco informado
            $SQL = new db_getBankList; $RS = $SQL->getInstanceOf($dbms,$_REQUEST['w_codigo'],null,null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("O c�digo j� existe!");');
              ScriptClose();
              RetornaFormulario('w_codigo');
              exit();
            }
          }
        }  
        $SQL = new dml_CoBanco; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_sq_banco'],$_REQUEST['w_nome'],$_REQUEST['w_codigo'],
            $_REQUEST['w_padrao'],$_REQUEST['w_ativo'],$_REQUEST['w_exige']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COAGENCIA': 
      $p_nome       = upper($_REQUEST['p_nome']);
      $p_sq_banco   = upper($_REQUEST['p_sq_banco']);
      $p_ativo      = upper($_REQUEST['p_ativo']);
      $p_ordena     = $_REQUEST['p_ordena'];
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if (nvl($_REQUEST['w_sq_banco'],'')!='' && nvl($_REQUEST['w_codigo'],'')!='') {
          if ($O == 'I' || $O == 'A') {
            $SQL = new db_getBankHouseList;
            $RS = $SQL->getInstanceOf($dbms, $_REQUEST['w_sq_banco'], null, null, $_REQUEST['w_codigo']);
            foreach ($RS as $row) {
              if (($O == 'I' && count($RS) > 0) || ($O == 'A' && count($RS) > 0 && $_REQUEST['w_sq_agencia'] != f($row, 'sq_agencia'))) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert("O c�digo da ag�ncia informada j� existe!");');
                ScriptClose();
                RetornaFormulario('w_codigo');
                exit();
              }
            }
          }
        }

        $SQL = new dml_CoAgencia; $SQL->getInstanceOf($dbms,$O, $_REQUEST['w_sq_agencia'],$_REQUEST['w_sq_banco'],$_REQUEST['w_nome'], $_REQUEST['w_codigo'], $_REQUEST['w_padrao'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COMOEDA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CoMoeda; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_codigo'],$_REQUEST['w_nome'],
            $_REQUEST['w_sigla'],$_REQUEST['w_simbolo'],$_REQUEST['w_tipo'],
            $_REQUEST['w_exclusao_ptax'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'COMOEDACOT':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_CoMoedaCotacao; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$w_cliente,$_REQUEST['w_moeda'],$_REQUEST['w_data'],$_REQUEST['w_taxa_compra'],$_REQUEST['w_taxa_venda']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    }
    return $function_ret;
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'CENTROCUSTO':   CentroCusto();  break;
  case 'AGENCIA':       Agencia();      break;
  case 'BANCO':         Banco();        break;
  case 'MOEDA':         Moeda();        break;
  case 'MOEDACOTACAO':  MoedaCotacao(); break;
  case 'GRAVA':         Grava();        break;
  default:
    Cabecalho();
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 
  return $function_ret;
} 
?>
