<?php
// =========================================================================
// Rotina de validação dos dados do certame
// -------------------------------------------------------------------------

function ValidaARP($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
  extract($GLOBALS);
  // Se não encontrar erro, esta função retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. Nem gestores podem encaminhar a solicitação
  // 1 - Erro de regra de negócio. Apenas gestores podem encaminhar a solicitação
  // 2 - Alerta. O sistema indica uma situação não desejável mas permite que o usuário
  //     encaminhe o projeto
  //-----------------------------------------------------------------------------------
  // Cria recordsets e variáveis de trabalho.
  // l_rs1 até l_rs4 são recordsets que podem ser usados para armazenar dados de blocos
  // de dados específicos da solicitação que está sendo validada.
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  // compõem a solicitação
  //-----------------------------------------------------------------------------------
  // Recupera os dados da solicitação
  $sql = new db_getSolicCL; $l_rs_solic = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$l_sg1,3,
                  null,null,null,null,null,null,null,null,null,null,
                  $l_chave,null,null,null,null,null,null,
                  null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
          null,null,null,null);
  // Se a solicitação informada não existir, abandona a execução
  if (count($l_rs_solic)==0) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  }
  foreach($l_rs_solic as $l_row){$l_rs_solic=$l_row; break;}
  $l_erro='';
  $l_tipo='';  

  // Recupera o trâmite atual da solicitação
  $sql = new db_getTramiteData; $l_rs_tramite = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  
  // Cancela a crítica se o trâmite solicitação não for ativo
  if (f($l_rs_tramite,'ativo')=='N') {
    return $l_erro;
    exit;
  }
  
  // Recupera os itens da arp
  $sql = new db_getCLSolicItem; $l_rs_item = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'ARP');
  // Verifica se já foi inserido os itens na licitacao
  if (count($l_rs_item)==0) {
    $l_erro.='<li>Informe pelo menos um item para ARP.';
    $l_tipo=0; 
  } elseif (count($l_rs_tramite)>0) {
    // Pedidos internos não podem relacionar itens de atas diferentes
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
        $l_erro .= '<li>Pedidos internos não podem relacionar itens de atas diferentes.';
        $l_tipo  = 0;
      }
    }

    // Este bloco faz verificações em solicitações que estão em fases posteriores ao cadastramento inicial
    if(f($l_rs_tramite,'ordem')>1 && f($l_rs_tramite,'ativo')=='S') {
      // Verifica se cada item possui no minimo duas pesquisas de preço
      reset($l_rs_item);
      foreach($l_rs_item as $row) {
        // Verifica se cada item possui no minimo duas pesquisas de preço
        if(f($row,'qtd_cotacao')<2) {
          $l_erro .= '<li>'.nvl(f($row,'codigo_interno'),'---').' - '.f($row,'nome').' não tem pelo menos 2 pesquisas de preço válidas.';
          $l_tipo  = 0;
        }
      }
    } 
  }
  
  // Configura a variável de retorno com o tipo de erro e a mensagem
  $l_erro = $l_tipo.$l_erro;
  //-----------------------------------------------------------------------------------
  // Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------

  return $l_erro;
}
?>