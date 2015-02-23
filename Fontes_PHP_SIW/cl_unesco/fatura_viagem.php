<?php
header('Expires: '.-1500);
// Garante que a sessão será reinicializada.
session_start();
if (isset($_SESSION['LOGON1'])) {
    echo '<SCRIPT LANGUAGE="JAVASCRIPT">';
    echo ' alert("Já existe outra sessão ativa!\nEncerre o sistema na outra janela do navegador ou aguarde alguns instantes.\nUSE SEMPRE A OPÇÃO \"SAIR DO SISTEMA\" para encerrar o uso da aplicação.");';
    echo ' history.back();';
    echo '</SCRIPT>';
    exit();
}

$_SESSION['DBMS']      = 1;
$_SESSION['P_CLIENTE'] = 6881;
$_SESSION['SQ_PESSOA'] = 14054;
$w_username            = 'suporte';

$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_exec.php');
include_once('funcoes_fabsweb.php');

// =========================================================================
//  /fatura_viagem.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Processa faturas eletrônicas da agência de viagens da UNESCO
// Mail     : alex@sbpi.com.br
// Criacao  : 13/02/2015, 10:03
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
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'fatura_viagem.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'cl_unesco/';
$w_troca        = $_REQUEST['w_troca'];
$w_volta        = $_REQUEST['w_volta'];
$w_embed        = '';

$w_tipo         = $_REQUEST['w_tipo'];
$p_usuario      = $_REQUEST['p_usuario'];

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_TP       = RetornaTitulo($TP,$O);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de indicação dos arquivos a serem processados
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  
  $w_max_file_uploads = ini_get('max_file_uploads');

  HtmlOpen(null);
  ShowHTML('<TITLE>FABS-WEB - Processamento de faturas eletrônicas</TITLE>');
  ScriptOpen('Javascript');
  ShowHTML('  function lista(fld) {');
  ShowHTML('    var lines = fld.files;');
  ShowHTML('    var html = "";');
  ShowHTML('    // Exibe os arquivos selecionados, destacando os que não correspondem à  extensão desejada');
  ShowHTML('    for (var i = 0; i < lines.length; i++) {');
  ShowHTML('        var sampleIcon = \'<img src="images/xml.jpg" />\';');
  ShowHTML('        var errorClass = "";');
  ShowHTML('        if(typeof lines[i] != undefined){');
  ShowHTML('          if(lines[i].type!="text/xml") {');
  ShowHTML('            sampleIcon = \'<img src="images/unknown.png" />\';');
  ShowHTML('            errorClass =" invalid";');
  ShowHTML('          } ');
  ShowHTML('          html += \'<div class="dfiles\'+errorClass+\'" rel="\'+i+\'"><h5>\'+sampleIcon+lines[i].name+\'</h5></div>\';');
  ShowHTML('        }');
  ShowHTML('    }');
  ShowHTML('    ');
  ShowHTML('    if (lines.length>0) {');
  ShowHTML('      // Exibe botões de submissão do formulário');
  ShowHTML('      document.getElementById("submitHandler").type = "submit";');
  ShowHTML('      document.getElementById("resetForm").type  = "reset";');
  ShowHTML('      document.getElementById("arqs").innerHTML = html;');
  ShowHTML('    } else {');
  ShowHTML('      // Oculta botões de submissão do formulário');
  ShowHTML('      document.getElementById("submitHandler").type = "hidden";');
  ShowHTML('      document.getElementById("resetForm").type  = "hidden";');
  ShowHTML('      document.getElementById("arqs").innerHTML = "<h1>Nenhum arquivo selecionado</h1>";');
  ShowHTML('    }');
  ShowHTML('  }');
  ValidateOpen('Validacao');
  ShowHTML('  lines = document.getElementById("file").files;');
  ShowHTML('  if (lines.length==0) {');
  ShowHTML('    alert("Clique no botão \"Escolher arquivos\" para selecionar os arquivos a serem processados!");');
  ShowHTML('    return false;');
  ShowHTML('  } else if (lines.length>'.$w_max_file_uploads.') {');
  ShowHTML('    alert("Você selecionou "+lines.length+" arquivos e o número máximo é '.$w_max_file_uploads.'!\nClique no botão \"Reiniciar\" para selecionar novamente.");');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  for (var i = 0; i < lines.length; i++) {');
  ShowHTML('    if(lines[i].type!="text/xml") {');
  ShowHTML('      alert("Somente arquivos XML podem ser processados!\nClique em \"Reiniciar\" e selecione corretamente or arquivos.");');
  ShowHTML('      return false;');
  ShowHTML('    } ');
  ShowHTML('  }');
  ShowHTML('  document.getElementById("form-data").style.display = "none";');
  ShowHTML('  document.getElementById("progress").style.display = "block";');
  ShowHTML('  return true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  ShowHTML('<link href="'.$conRootSIW.'classes/menu/upload.css" type="text/css" rel="stylesheet" />');
  ShowHTML('</HEAD>');
  
  ShowHTML('<body lang="pt-BR"><div class="container-fluid"><div class="col-md-12">');
  ShowHTML('<ol class="breadcrumb"><li>'.str_replace(' - ','<li>',$TP).'</li></ol>');
  ShowHTML('<div class="progress" id="progress" style="display:none;"><img src="images/ajax-loaderback-med.gif" />');
  ShowHTML('  <blockquote>Processando...</blockquote>');
  ShowHTML('</div>');
  ShowHTML('<div class="form-data" id="form-data">');
  ShowHTML('  <blockquote>');
  ShowHTML('    Selecione até '.$w_max_file_uploads.' arquivos para processamento, clicando no botão abaixo.');
  ShowHTML('  </blockquote>');
  ShowHTML('  <form action="'.$w_dir.$w_pagina.'processa" method="post" enctype="multipart/form-data" onSubmit="return(Validacao(this))">');
  ShowHTML('     <input type="hidden" name="p_usuario" value="'.$p_usuario.'">');
  ShowHTML('     <input type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('     <input type="file" name="file[]"id="file" accept="text/xml" multiple class="buttonUpload" onChange="lista(this)"/><br>');
  ShowHTML('     <div id="arqs" class="uploadArea">');
  ShowHTML('       <h1>Nenhum arquivo selecionado</h1>');
  ShowHTML('     </div><br>');
  ShowHTML('     <div align="center">');
  ShowHTML('       <input type="hidden" name="ok" id="submitHandler" value="Enviar" class="buttonUpload"/>');
  ShowHTML('       <input type="hidden"  name="resetForm" id="resetForm" value="Reiniciar" class="buttonUpload" onClick="location.reload();"/>');
  ShowHTML('     </div>');
  ShowHTML('  </form>');
  ShowHTML('</div>');
  ShowHTML('</div></div></BODY>');
  ShowHTML('</HTML>');
} 

// =========================================================================
// Processa os arquivos selecionados
// -------------------------------------------------------------------------
function Processa() {
  extract($GLOBALS);

  HtmlOpen(null);
  ShowHTML('<TITLE>FABS-WEB - Processamento de faturas eletrônicas</TITLE>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  ShowHTML('<link href="'.$conRootSIW.'classes/menu/upload.css" type="text/css" rel="stylesheet" />');
  ShowHTML('</HEAD>');
  ShowHTML('<body lang="pt-BR"><div class="container-fluid"><div class="col-md-12">');
  ShowHTML('<ol class="breadcrumb"><li>'.str_replace(' - ','<li>',$TP).'</li></ol>');
  ShowHTML('<blockquote>Resultado do Processamento</blockquote>');
  ShowHTML('<div id="arqs" class="uploadArea">');
  if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['ok'])) {
      $i = 0;
      $total = count($_FILES['file']['name']);
      for ($i=0; $i<$total; $i++) {
        // Array com os nomes dos arquivos transferidos pelo usuário
        $filename   = $_FILES['file']['name'][$i];
        // Array com os nomes temporários dos arquivos recebidos via upload
        $tmp_name   = $_FILES['file']['tmp_name'][$i];
        // String com o conteúdo do arquivo
        $xmlstring  = file_get_contents($tmp_name);
        // Apaga o arquivo do disco, pois será usada somente a string
        unlink($tmp_name);
        // Formata o XML para ser exibido no HTML
        $xml_screen = str_replace(chr(13),'<br />',str_replace(' ','&nbsp;&nbsp;',str_replace('<','&lt;',str_replace('>','&gt;',$xmlstring))));
        // Chama a rotina que processa o WS (vide funcoes_fabsweb.php)
        $response   = ws_fatura($xmlstring);
        // O retorno da função é no padrão HTML. Então, converte HTML ENTITIES para caracteres puros.
        $format     = str_replace('<br />',chr(13),str_replace('&nbsp;&nbsp;',' ',str_replace('&lt;','<',str_replace('&gt;','>',$response))));
        // Monta array com os códigos e os textos obtidos no WS (vide funcoes_fabsweb.php)
        $codigo     = retornaElementoXML($format,'Codigo');
        $texto      = retornaElementoXML($format,'Texto');
        // Monta texto com entradas LI para cada bilhete da fatura
        $resposta   = '';
        $erro       = true;
        for ($j = 0; $j < count($codigo); $j++) {
          $resposta.='<li>'.$texto[$j].'</li>';
          // Se houver pelo menos um código = 27 (indica que há algum erro com o bilhete).
          if ($codigo[$j]==27) $erro = true;
        }
        // Se algum bilhete da fatura teve erro, usa classe destacada para chamar a atenção do usuário.
        ShowHTML('<div class="dfiles'.(($erro) ? ' invalid' : '').'" rel="'.$i.'"><h5>'.$filename.'</h5></div>');
        ShowHTML('<blockquote><font size=2>'.$resposta.'</font></blockquote>');
      }
    }
  }
  ShowHTML('<div align="center"><a href="'.$w_dir.$w_pagina.'inicial&TP='.$TP.montaFiltro('GET').'">Clique aqui para novo processamento</a></div>');
  ShowHTML('</div>');
  ShowHTML('</div></div></BODY>');
  ShowHTML('</HTML>');
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  login_fabs();
  switch ($par) {
  case 'INICIAL':           Inicial();        break;
  case 'PROCESSA':          Processa();       break;
  default:
    Cabecalho();
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
    break;
  } 
} 
?>

