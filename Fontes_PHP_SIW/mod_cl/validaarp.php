<?php
// =========================================================================
// Rotina de valida��o dos dados do certame
// -------------------------------------------------------------------------

function ValidaARP($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
  extract($GLOBALS);
  // Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. Nem gestores podem encaminhar a solicita��o
  // 1 - Erro de regra de neg�cio. Apenas gestores podem encaminhar a solicita��o
  // 2 - Alerta. O sistema indica uma situa��o n�o desej�vel mas permite que o usu�rio
  //     encaminhe o projeto
  //-----------------------------------------------------------------------------------
  // Cria recordsets e vari�veis de trabalho.
  // l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  // de dados espec�ficos da solicita��o que est� sendo validada.
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  // comp�em a solicita��o
  //-----------------------------------------------------------------------------------
  // Recupera os dados da solicita��o
  $sql = new db_getSolicCL; $l_rs_solic = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$l_sg1,3,
                  null,null,null,null,null,null,null,null,null,null,
                  $l_chave,null,null,null,null,null,null,
                  null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
          null,null,null,null);
  // Se a solicita��o informada n�o existir, abandona a execu��o
  if (count($l_rs_solic)==0) {
    return '0<li>N�o existe registro no banco de dados com o n�mero informado.';
  }
  foreach($l_rs_solic as $l_row){$l_rs_solic=$l_row; break;}
  $l_erro='';
  $l_tipo='';  

  // Recupera o tr�mite atual da solicita��o
  $sql = new db_getTramiteData; $l_rs_tramite = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  
  // Cancela a cr�tica se o tr�mite solicita��o n�o for ativo
  if (f($l_rs_tramite,'ativo')=='N') {
    return $l_erro;
    exit;
  }
  
  // Recupera os itens da arp
  $sql = new db_getCLSolicItem; $l_rs_item = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'ARP');
  // Verifica se j� foi inserido os itens na licitacao
  if (count($l_rs_item)==0) {
    $l_erro.='<li>Informe pelo menos um item para ARP.';
    $l_tipo=0; 
  } elseif (count($l_rs_tramite)>0) {
    // Pedidos internos n�o podem relacionar itens de atas diferentes
    if (f($l_rs_solic,'interno')=='S') {
      $w_atual = '';
      $w_erro_ata = false;
      reset($l_rs_item);
      foreach($l_rs_item as $row) {
        if ($w_atual=='') {
          $w_atual = f($row,'numero_ata');
        } elseif ($w_atual != f($row,'numero_ata')) {
          $w_erro_ata = true;
        }
      }
      if ($w_erro_ata) {
        $l_erro .= '<li>Pedidos internos n�o podem relacionar itens de atas diferentes.';
        $l_tipo  = 0;
      }
    }

    // Este bloco faz verifica��es em solicita��es que est�o em fases posteriores ao cadastramento inicial
    if(f($l_rs_tramite,'ordem')>1 && f($l_rs_tramite,'ativo')=='S') {
      // Verifica se cada item possui no minimo duas pesquisas de pre�o
      reset($l_rs_item);
      foreach($l_rs_item as $row) {
        // Verifica se cada item possui no minimo duas pesquisas de pre�o
        if(f($row,'qtd_cotacao')<2) {
          $l_erro .= '<li>'.nvl(f($row,'codigo_interno'),'---').' - '.f($row,'nome').' n�o tem pelo menos 2 pesquisas de pre�o v�lidas.';
          $l_tipo  = 0;
        }
      }
    } 
  }
  
  // Configura a vari�vel de retorno com o tipo de erro e a mensagem
  $l_erro = $l_tipo.$l_erro;
  //-----------------------------------------------------------------------------------
  // Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------

  return $l_erro;
}
?>