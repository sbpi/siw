<?php
// =========================================================================
// Rotina de valida��o dos dados do certame
// -------------------------------------------------------------------------

function ValidaCertame($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
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
                  null,null,null,null,null,null,null,null,null,null,null);
  // Se a solicita��o informada n�o existir, abandona a execu��o
  if (count($l_rs_solic)==0) {
    return '0<li>N�o existe registro no banco de dados com o n�mero informado.';
  }
  foreach($l_rs_solic as $l_row){$l_rs_solic=$l_row; break;}
  $l_erro='';
  $l_tipo='';  

  // Recupera o tr�mite atual da solicita��o
  $sql = new db_getTramiteData; $l_rs_tramite = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  
  // Recupera os itens do certame
  $sql = new db_getCLSolicItem; $l_rs_item = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
  // Verifica se j� foi inserido os itens na licitacao
  if (count($l_rs_item)==0) {
    $l_erro.='<li>Informe pelo menos um item para licita��o.';
    $l_tipo=0; 
  }
  // Este bloco faz verifica��es em solicita��es que est�o em fases posteriores ao cadastramento inicial
  if (count($l_rs_tramite)>0) {
    if(f($l_rs_tramite,'sigla')=='AP') {
      if(nvl(f($l_rs_solic,'sq_lcmodalidade'),'')=='') {
        $l_erro.='<li>Informe os dados da an�lise.';
        $l_tipo=0;       
      }
    } elseif(f($l_rs_tramite,'sigla')=='PP') {
      // Verifica se cada item possui o m�nimo de pesquisas de pre�o exigido pela modalidade
      $sql = new db_getCLSolicItem; $l_rs_pesquisa = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'VALIDACAOC');
      if(count($l_rs_pesquisa)>0) {
        foreach($l_rs_pesquisa as $row) {
          if (f($row,'qtd')<f($l_rs_solic,'minimo_pesquisas')) {
            $l_erro .= '<li>'.f($row,'nome').' ('.nvl(f($row,'codigo_interno'),'---').') '.((f($row,'qtd')>0) ? 's� tem '.f($row,'qtd').' pesquisa(s) de pre�o v�lida(s).' : 'n�o tem pesquisa de pre�o v�lida.');
            $l_tipo  = 0;
          }
        }
      }
    } elseif(f($l_rs_tramite,'sigla')=='EA') {
      if(nvl(f($l_rs_solic,'sq_lcsituacao'),'')=='') {
        $l_erro.='<li>Informe os dados da an�lise.';
        $l_tipo=0;       
      }
    } elseif(f($l_rs_tramite,'sigla')=='EE')  {
      $sql = new db_getCLSolicItem; $l_rs_pesquisa = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'VALIDACAOG');
      foreach($l_rs_pesquisa as $row) {
        if (f($row,'qt_propostas')==0) {
          $l_erro .= '<li>A licita��o n�o tem nenhuma proposta.';
          $l_tipo  = 0;
        /*
        } elseif (((f($row,'qt_itens') * f($row,'qt_fornecedores'))!=f($row,'qt_propostas'))) {
          $l_erro .= '<li>Para crit�rio de '.(($w_cliente==6881) ? 'avalia��o' : 'julgamento').' global � necess�rio que cada fornecedor cote todos os itens.';
          $l_tipo  = 0;
        */
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
