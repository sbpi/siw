<?php
// =========================================================================
// Rotina de valida��o dos dados da entrada de material
// -------------------------------------------------------------------------

function ValidaEntrada($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
  extract($GLOBALS);
  // Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. Nem gestores podem executar a��es
  // 1 - Erro de regra de neg�cio. Apenas gestores podem executar a��es
  // 2 - Alerta. O sistema apenas indica uma situa��o n�o desej�vel mas n�o bloqueia a��es
  //-----------------------------------------------------------------------------------
  // Cria recordsets e vari�veis de trabalho.
  // l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  // de dados espec�ficos da solicita��o que est� sendo validada.
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  // comp�em a entrada de material
  //-----------------------------------------------------------------------------------
  // Recupera os dados da entrada
  $sql = new db_getMtMovim; $l_rs_solic = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$l_sg1,3,
        null,null,null,null,null,null,null,null,null,null,
        $l_chave,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,null,null,null);
  // Se a solicita��o informada n�o existir, abandona a execu��o
  if (count($l_rs_solic)==0) {
    return '0<li>N�o existe registro no banco de dados com o n�mero informado.';
  } else {
    foreach($l_rs_solic as $row) { $l_rs_solic = $row; break; }
  }
  $l_erro='';
  $l_tipo='';  

  // Recupera os itens da entrada de material
  $sql = new db_getMtEntItem; $l_rs_item = $sql->getInstanceOf($dbms,$w_cliente,$l_chave,null,null,null,null,null,null,null,null,null,null,null,null,null);
  
  //-----------------------------------------------------------------------------------
  // O bloco abaixo faz as valida��es na solicita��o que n�o s�o poss�veis de fazer
  // atrav�s do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------
  // Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Verifica se j� foram inseridos os itens da entrada
  if (count($l_rs_item)==0) {
    $l_erro.='<li>Informe pelo menos um item na entrada de material.';
    $l_tipo=0; 
  } else {
    // Verifica se foram inseridos itens inativos e se o valor dos itens � igual ao do documento
    $tot_itens = 0;
    foreach ($l_rs_item as $row) {
      if (f($row,'ativo')=='N') {
        $l_erro .= '<li>'.f($row,'nome').' ('.nvl(f($row,'codigo_interno'),'---').') n�o est� dispon�vel. Remova-o da lista de itens.';
        $l_tipo  = 0;
      }
      $tot_itens += f($row,'valor_total');
    }
    if (f($l_rs_solic,'vl_doc')!=$tot_itens) {
      $l_erro.='<li>Valor do documento ('.formatNumber(f($l_rs_solic,'vl_doc')).') difere da soma dos itens ('.formatNumber($tot_itens).').';
      if ($l_tipo=='') $l_tipo=2;
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
