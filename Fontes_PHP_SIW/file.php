<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');

// =========================================================================
//  /file.asp
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Devolve arquivos físicos para o cliente
// Mail     : alex@sbpi.com.br
// Criacao  : 07/02/2006, 10:23
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------

ob_start();

$w_cliente  = $_REQUEST['cliente'];
$w_id       = $_REQUEST['id'];
$w_force    = Nvl($_REQUEST['force'],'false');
$w_sessao   = $_REQUEST['sessao'];
$w_erro     = 0; // Se tiver valor diferente de 0, exibe mensagem de erro

if (Nvl($w_cliente,'')=='' || Nvl($w_id,'')=='' || (Nvl($w_sessao,'')=='' && $_SESSION['DBMS']=='')) {
  $w_erro=1; // Parâmetros incorretos
} elseif (!(strpos($w_id,'.')===false)) {
  $w_nome       = '';
  $w_descricao  = '';
  $w_inclusao   = '';
  $w_tamanho    = '';
  $w_tipo       = substr($w_id,(strpos($w_id,'.') ? strpos($w_id,'.')+1 : 0)-1,30);
  $w_caminho    = $w_id;
  $w_filename   = $w_id;
} else {
  // Configura objetos de BD
  $dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

  // Tenta recuperar os dados do arquivo selecionado
  $RS = db_getSiwArquivo::getInstanceOf($dbms,$w_cliente,$w_id,null);
  if (count($RS)==0) {
    $w_erro=2; // Arquivo não encontrado
  } else {
    $w_nome      = f($RS,'nome');
    $w_descricao = f($RS,'descricao');
    $w_inclusao  = f($RS,'inclusao');
    $w_tamanho   = f($RS,'tamanho');
    $w_tipo      = f($RS,'tipo');
    $w_caminho   = f($RS,'caminho');
    $w_filename  = f($RS,'nome_original');
  } 
  FechaSessao($dbms);
} 

if ($w_erro>0) { // Se houve erro, exibe HTML
  Cabecalho();
  BodyOpenClean('onLoad=document.focus();');
  ShowHTML('<div align=center><center><b>');
  if ($w_erro==1) {
    ShowHTML('Parâmetros de chamada incorretos');
  } else {
    ShowHTML('Arquivo inexistente');
  } 
  ShowHTML('</b></center></div>');
  Rodape();
} else {
  $strFileName = $w_caminho;
  if (strlen($strFileName)>0) DownloadFile($strFileName,$w_force);
} 

exit;

function DownloadFile($strFileName,$blnForceDownload) {
  extract($GLOBALS);

  //----------------------
  //first step: verify the file exists
  //----------------------

  //build file path:
  $strFilePath = $conFilePhysical.$w_cliente.'\\';
  // add backslash if needed:
  if (substr($strFilePath,strlen($strFilePath)-(1))!='\\') $strFilePath = $strFilePath.'\\';
  $strFilePath = $strFilePath.$strFileName;

  //check that the file exists:
  if (!(file_exists($strFilePath))) {
    ShowHTML('Arquivo inexistente');
    exit();
  } 

  //----------------------
  //second step: get file size.
  //----------------------
  $fileSize = filesize($strFilePath);

  //----------------------
  //third step: check whether file is binary or not and get content type of the file. (according to its extension)
  //----------------------
  $blnBinary  = GetContentType($w_tipo,$strExtension);
  $strAllFile = '';
  if (!(strpos($w_filename,'.')===false)) $w_filename = $w_filename.$strExtension;

  //----------------------
  //fourth step: read the file contents.
  //----------------------
  $strAllFile = '';
  if ($blnBinary) {
    $objStream = fopen($strFilePath, 'rb');
  } else {
    $objStream = fopen($strFilePath, 'r');
  } 
  do {
    $data = fread($objStream, 8192);
    if (strlen($data)==0) break;
    $strAllFile .= $data;
  } while (true);
  fclose($objStream);

  //----------------------
  //final step: apply content type and send file contents to the browser
  //----------------------
  if ($blnForceDownload=='true') {
    header('Content-Disposition'.': '.'attachment; filename='.$w_filename);
  } else {
    header('Content-Disposition'.': '.'filename='.$w_filename);
  } 
  header('Content-Length'.': '.$fileSize);
  if (!(strpos($w_tipo,'.')===false)) {
    header('Content-type: '.$w_tipo);
  } 
  if ($blnBinary) {
    print $strAllFile;
  } else {
    print $strAllFile;
  } 

  return $function_ret;
} 

function GetContentType(&$strName,&$Extension) {
  extract($GLOBALS);

  //return whether binary or not, put type into second parameter
  switch ($strName) {
    case 'video/x-ms-asf':                  $Extension='.asf';      $GetContentType=true;   break;
    case 'video/avi':                       $Extension='.avi';      $GetContentType=true;   break;
    case 'application/msword':              $Extension='.doc';      $GetContentType=true;   break;
    case 'application/zip':                 $Extension='.zip';      $GetContentType=true;   break;
    case 'application/vnd.ms-excel':        $Extension='.xls';      $GetContentType=true;   break;
    case 'application/vnd.ms-powerpoint':   $Extension='.ppt';      $GetContentType=true;   break;
    case 'image/gif':                       $Extension='.gif';      $GetContentType=true;   break;
    case 'image/jpeg':                      $Extension='.jpg';      $GetContentType=true;   break;
    case 'audio/wav':                       $Extension='.wav';      $GetContentType=true;   break;
    case 'audio/mpeg3':                     $Extension='.mp3';      $GetContentType=true;   break;
    case 'video/mpeg':                      $Extension='.mpg';      $GetContentType=true;   break;
    case 'application/rtf':                 $Extension='.rtf';      $GetContentType=true;   break;
    case 'text/html':                       $Extension='.htm';      $GetContentType=false;  break;
    case 'text/asp':                        $Extension='.asp';      $GetContentType=false;  break;
    case 'text/plain':                      $Extension='.htm';      $GetContentType=false;  break;
    case '.gif':                            $Extension='.gif';      $GetContentType=true;   break;
    case '.js':                             $Extension='.js';       $GetContentType=true;   break;
    case '.css':                            $Extension='.css';      $GetContentType=false;  break;
    case '.jpg':                            $Extension='.jpg';      $GetContentType=true;   break;
    case '.jpeg':                           $Extension='.jpg';      $GetContentType=true;   break;
    default:                                $Extension='';          $GetContentType=true;   break;
  } 
  return $function_ret;
} 
?>
