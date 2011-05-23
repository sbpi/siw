<?php
// =========================================================================
// Rotina de validação dos dados da entrada de material
// -------------------------------------------------------------------------

function ValidaEntrada($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
  extract($GLOBALS);
  // Se não encontrar erro, esta função retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. Nem gestores podem executar ações
  // 1 - Erro de regra de negócio. Apenas gestores podem executar ações
  // 2 - Alerta. O sistema apenas indica uma situação não desejável mas não bloqueia ações
  //-----------------------------------------------------------------------------------
  // Cria recordsets e variáveis de trabalho.
  // l_rs1 até l_rs4 são recordsets que podem ser usados para armazenar dados de blocos
  // de dados específicos da solicitação que está sendo validada.
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  // compõem a entrada de material
  //-----------------------------------------------------------------------------------
  // Recupera os dados da entrada
  $sql = new db_getMtMovim; $l_rs_solic = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$l_sg1,3,
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

  // Recupera os itens da entrada de material
  $sql = new db_getMtEntItem; $l_rs_item = $sql->getInstanceOf($dbms,$w_cliente,$l_chave,null,null,null,null,null,null,null,null,null,null,null,null,null);
  
  //-----------------------------------------------------------------------------------
  // O bloco abaixo faz as validações na solicitação que não são possíveis de fazer
  // através do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Verifica se já foram inseridos os itens da entrada
  if (count($l_rs_item)==0) {
    $l_erro.='<li>Informe pelo menos um item na entrada de material.';
    $l_tipo=0; 
  } else {
    // Verifica se foram inseridos itens inativos e se o valor dos itens é igual ao do documento
    $tot_itens = 0;
    foreach ($l_rs_item as $row) {
      if (f($row,'ativo')=='N') {
        $l_erro .= '<li>'.f($row,'nome').' ('.nvl(f($row,'codigo_interno'),'---').') não está disponível. Remova-o da lista de itens.';
        $l_tipo  = 0;
      }
      $tot_itens += f($row,'valor_total');
    }
    if (f($l_rs_solic,'vl_doc')!=$tot_itens) {
      $l_erro.='<li>Valor do documento ('.formatNumber(f($l_rs_solic,'vl_doc')).') difere da soma dos itens ('.formatNumber($tot_itens).').';
      if ($l_tipo=='') $l_tipo=2;
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
