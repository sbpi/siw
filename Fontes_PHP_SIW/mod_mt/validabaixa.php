<?php
// =========================================================================
// Rotina de validação dos dados da baixa de bens patrimoniais
// -------------------------------------------------------------------------

function ValidaBaixa($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
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
  $sql = new db_getMtBaixaBem; $l_rs_solic = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$l_sg1,3,
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

  $sql = new db_getMtBaixaBem; $l_rs_item = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ITENS',3,
          null,null,null,null,null,null,null,null,null,null,$l_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  $l_rs_item = SortArray($l_rs_item,'numero_rgp','asc');
  
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
    $l_erro.='<li>Informe pelo menos um item na baixa de bem patrimonial.';
    $l_tipo=0; 
  } else {
    // Bem não pode estar em outra baixa
    foreach ($l_rs_item as $row) {
      if (nvl(f($row,'cd_baixa'),'')!='') {
        $l_erro .= '<li>RGP '.f($row,'numero_rgp').' já está inserido na baixa '.f($row,'cd_baixa').'. Remova-o desta baixa para poder enviar.';
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
