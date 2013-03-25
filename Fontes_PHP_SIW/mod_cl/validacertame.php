<?php
// =========================================================================
// Rotina de validação dos dados do certame
// -------------------------------------------------------------------------

function ValidaCertame($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
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
  }
  foreach($l_rs_solic as $l_row){$l_rs_solic=$l_row; break;}
  $l_erro='';
  $l_tipo='';  

  // Recupera o trâmite atual da solicitação
  $sql = new db_getTramiteData; $l_rs_tramite = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  
  // Recupera os itens do certame
  $sql = new db_getCLSolicItem; $l_rs_item = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
  // Verifica se já foi inserido os itens na licitacao
  if (count($l_rs_item)==0) {
    $l_erro.='<li>Informe pelo menos um item para licitação.';
    $l_tipo=0; 
  }
  // Este bloco faz verificações em solicitações que estão em fases posteriores ao cadastramento inicial
  if (count($l_rs_tramite)>0) {
    if(f($l_rs_tramite,'sigla')=='AP') {
      if(nvl(f($l_rs_solic,'sq_lcmodalidade'),'')=='') {
        $l_erro.='<li>Informe os dados da análise.';
        $l_tipo=0;       
      }
    } elseif(f($l_rs_tramite,'sigla')=='PP') {
      // Verifica se cada item possui o mínimo de pesquisas de preço exigido pela modalidade
      $sql = new db_getCLSolicItem; $l_rs_pesquisa = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'VALIDACAOC');
      if(count($l_rs_pesquisa)>0) {
        foreach($l_rs_pesquisa as $row) {
          if (f($row,'qtd')<f($l_rs_solic,'minimo_pesquisas')) {
            $l_erro .= '<li>'.f($row,'nome').' ('.nvl(f($row,'codigo_interno'),'---').') '.((f($row,'qtd')>0) ? 'só tem '.f($row,'qtd').' pesquisa(s) de preço válida(s).' : 'não tem pesquisa de preço válida.');
            $l_tipo  = 0;
          }
        }
      }
    } elseif(f($l_rs_tramite,'sigla')=='EA') {
      if(nvl(f($l_rs_solic,'sq_lcsituacao'),'')=='') {
        $l_erro.='<li>Informe os dados da análise.';
        $l_tipo=0;       
      }
    } elseif(f($l_rs_tramite,'sigla')=='EE')  {
      $sql = new db_getCLSolicItem; $l_rs_pesquisa = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'VALIDACAOG');
      foreach($l_rs_pesquisa as $row) {
        if (f($row,'qt_propostas')==0) {
          $l_erro .= '<li>A licitação não tem nenhuma proposta.';
          $l_tipo  = 0;
        /*
        } elseif (((f($row,'qt_itens') * f($row,'qt_fornecedores'))!=f($row,'qt_propostas'))) {
          $l_erro .= '<li>Para critério de '.(($w_cliente==6881) ? 'avaliação' : 'julgamento').' global é necessário que cada fornecedor cote todos os itens.';
          $l_tipo  = 0;
        */
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
