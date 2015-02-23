<?php
// =========================================================================
// Rotina de autentica��o dos usu�rios
// -------------------------------------------------------------------------
function Valida() {
  extract($GLOBALS);
  $w_erro=0;
  // Recupera informa��es do cliente, relativas ao envio de e-mail
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
  $_SESSION['SMTP_SERVER']     = f($RS, 'smtp_server');
  $_SESSION['SIW_EMAIL_CONTA'] = f($RS, 'siw_email_conta');
  $_SESSION['SIW_EMAIL_SENHA'] = f($RS,'siw_email_senha');

  // Recupera informa��es a serem usadas na montagem das telas para o usu�rio
  $sql = new DB_GetUserData; $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE'], $w_username);
  $_SESSION['USERNAME']        = f($RS,'USERNAME');
  $_SESSION['SQ_PESSOA']       = f($RS,'SQ_PESSOA');
  $_SESSION['NOME']            = f($RS,'NOME');
  $_SESSION['EMAIL']           = f($RS,'EMAIL');
  $_SESSION['NOME_RESUMIDO']   = f($RS,'NOME_RESUMIDO');
  $_SESSION['LOTACAO']         = f($RS,'SQ_UNIDADE');
  $_SESSION['LOCALIZACAO']     = f($RS,'SQ_LOCALIZACAO');
  $_SESSION['INTERNO']         = f($RS,'INTERNO');
  $_SESSION['LOGON']           = 'Sim';
  $_SESSION['ENDERECO']        = f($RS,'SQ_PESSOA_ENDERECO');
  $_SESSION['ANO']             = Date('Y');

  // Registra no servidor syslog
  $w_resultado = enviaSyslog('LV','LOGIN','('.$_SESSION['SQ_PESSOA'].') '.$_SESSION['NOME_RESUMIDO']);

  // Se a gera��o de log estiver ativada, registra.
  if ($conLog) {
    // Define o caminho fisico do diret�rio e do arquivo de log
    $l_caminho = $conLogPath;
    $l_arquivo = $l_caminho.$_SESSION['P_CLIENTE'].'/'.date(Ymd).'.log';

    // Verifica a necessidade de cria��o dos diret�rios de log
    if (!file_exists($l_caminho)) mkdir($l_caminho);
    if (!file_exists($l_caminho.$_SESSION['P_CLIENTE'])) mkdir($l_caminho.$_SESSION['P_CLIENTE']);

    // Abre o arquivo de log
    $l_log = @fopen($l_arquivo, 'a');

    fwrite($l_log, '['.date(ymd.'_'.Gis.'_'.time()).']'.$crlf);
    fwrite($l_log, $_SESSION['USUARIO'].': '.$_SESSION['NOME_RESUMIDO'].' ('.$_SESSION['SQ_PESSOA'].')'.$crlf);
    fwrite($l_log, 'IP     : '.$_SERVER['REMOTE_ADDR'].$crlf);
    fwrite($l_log, 'A��o   : LOGIN REMOTO'.$crlf.$crlf);

    // Fecha o arquivo e o diret�rio de log
    @fclose($l_log);
    @closedir($l_caminho);
  }
}
?>