<?php
// =========================================================================
// Rotina de valida��o dos dados do pedido
// -------------------------------------------------------------------------
function ValidaEmprestimo($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
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
  $sql = new db_getSolicPA; $l_rs_solic = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$l_sg1,3,
          null,null,null,null,null,null,null,null,null,null,
          $l_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  // Se a solicita��o informada n�o existir, abandona a execu��o
  if (count($l_rs_solic)==0) {
    return '0<li>N�o existe registro no banco de dados com o n�mero informado.';
  } 
  $l_erro='';
  $l_tipo='';  

  // Recupera os itens do pedido de empr�stimo
  $sql = new db_getPAEmpItem; $l_rs_item = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null);
  
  //-----------------------------------------------------------------------------------
  // O bloco abaixo faz as valida��es na solicita��o que n�o s�o poss�veis de fazer
  // atrav�s do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------
  // Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Verifica se j� foi inserido os itens do pedido
  if (count($l_rs_item)==0) {
    $l_erro.='<li>Informe pelo um item no pedido de empr�stimo.';
    $l_tipo=0; 
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
