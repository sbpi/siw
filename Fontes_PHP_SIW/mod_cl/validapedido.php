<?php
// =========================================================================
// Rotina de validação dos dados do pedido
// -------------------------------------------------------------------------

function ValidaPedido($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
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
          null,null,null,null,null,null,null,null,null,null,null);
  // Se a solicitação informada não existir, abandona a execução
  if (count($l_rs_solic)==0) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  } else {
    foreach($l_rs_solic as $row) { $l_rs_solic = $row; break; }
  }
  $l_erro='';
  $l_tipo='';  

  // Recupera o trâmite atual da solicitação
  $sql = new db_getTramiteData; $l_rs_tramite = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  
  // Recupera os itens do pedido de compra
  $sql = new db_getCLSolicItem; $l_rs_item = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,null);
  
  //-----------------------------------------------------------------------------------
  // O bloco abaixo faz as validações na solicitação que não são possíveis de fazer
  // através do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Verifica se já foram inseridos os itens do pedido
  if (count($l_rs_item)==0) {
    $l_erro.='<li>Informe pelo menos um item no pedido de compra.';
    $l_tipo=0; 
  } else {
    // Verifica se foram inseridos itens inativos
    foreach ($l_rs_item as $row) {
      if (f($row,'exibe_catalogo')=='N' || f($row,'ativo')=='N') {
        $l_erro .= '<li>'.f($row,'nome').' ('.nvl(f($row,'codigo_interno'),'---').') não está disponível. Remova-o da lista de itens.';
        $l_tipo  = 0;
      }
    }
  } 

  // Este bloco faz verificações em solicitações que estão em fases posteriores ao cadastramento inicial
  if (count($l_rs_tramite)>0) {
    if(f($l_rs_tramite,'sigla')=='AF') {
      // Verifica se pelo menos um item foi autorizado com quantidade maior que zero.
      $l_autorizado = false;
      foreach ($l_rs_item as $row) {
        if (f($row,'quantidade_autorizada')>0) {
          $l_autorizado = true;
        }
      }
      if (!$l_autorizado) {
        $l_erro .= '<li>Pelo menos um item deve ser autorizado com quantidade maior que zero.';
        $l_tipo  = 0;
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
