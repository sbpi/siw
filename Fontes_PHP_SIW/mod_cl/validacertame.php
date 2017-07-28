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
                  null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  // Se a solicita��o informada n�o existir, abandona a execu��o
  if (count($l_rs_solic)==0) {
    return '0<li>N�o existe registro no banco de dados com o n�mero informado.';
  }
  foreach($l_rs_solic as $l_row){$l_rs_solic=$l_row; break;}
  $l_erro='';
  $l_tipo='';  

  // Recupera o tr�mite atual da solicita��o
  $sql = new db_getTramiteData; $l_rs_tramite = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  
  if (f($l_rs_solic,'minimo_participantes')>0) {
    // Recupera os itens do certame se a modalidade exigir participantes
    $sql = new db_getCLSolicItem; $l_rs_item = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
    // Verifica se j� foi inserido os itens na licitacao
    if (count($l_rs_item)==0) {
      $l_erro.='<li>Informe pelo menos um item para licita��o.';
      $l_tipo=0; 
    }
    // Verifica se foram inseridos itens inativos
    foreach ($l_rs_item as $row) {
      if (f($row,'exibe_catalogo')=='N' || f($row,'ativo')=='N') {
        $l_erro .= '<li>'.f($row,'nome').' ('.nvl(f($row,'codigo_interno'),'---').') n�o est� dispon�vel. Remova-o da lista de itens.';
        $l_tipo  = 0;
      }
      if (nvl(f($row,'det_item'),'')=='') {
        $l_erro .= '<li>'.f($row,'nome').' ('.nvl(f($row,'codigo_interno'),'---').') precisa ser detalhado. Na tela de itens, clique na opera��o "AL" deste item e informe os dados solicitados.';
        $l_tipo  = 0;        
      }
    }
  }
  
  // Verifica se cada item possui o m�nimo de pesquisas de pre�o exigido pela modalidade
  // ou se foi informada justificativa para quebra da regra
  if(nvl(f($l_rs_solic,'justificativa_regra_pesquisas'),'')=='' && (f($l_rs_tramite,'sigla')=='PP' || f($l_rs_tramite,'sigla')=='EE' || (f($l_rs_tramite,'sigla')=='CI') && f($l_rs_solic,'certame')=='N')) {
    $sql = new db_getCLSolicItem; $l_rs_pesquisa = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'VALIDACAOC');
    foreach($l_rs_pesquisa as $row) {
      if (f($row,'qtd_desejada')>0) {
        if (f($row,'qtd_pesquisas')<f($l_rs_solic,'minimo_pesquisas')) {
          $l_erro .= '<li>'.f($l_rs_solic,'nm_lcmodalidade').' exige '.f($l_rs_solic,'minimo_pesquisas').'  pesquisa'.((f($l_rs_solic,'minimo_pesquisas')!=1) ? 's' : '').' de pre�o v�lida'.((f($l_rs_solic,'minimo_pesquisas')!=1) ? 's' : '').' e '.f($row,'nome').((f($row,'qtd_pesquisas')>0) ? ' s� tem '.f($row,'qtd_pesquisas') : ' n�o tem nenhuma').'. Cadastre a quantidade exigida ou justifique quando for concluir.';
          if ($l_tipo == '') $l_tipo = 2;
        }
      }
    }
  }

  // Verifica se cada item possui o m�nimo de propostas exigido pela modalidade
  // ou se foi informada justificativa para quebra da regra
  if(nvl(f($l_rs_solic,'justificativa_regra_propostas'),'')=='' && (f($l_rs_tramite,'sigla')=='EE' || (f($l_rs_tramite,'sigla')=='CI') && f($l_rs_solic,'certame')=='N')) {
    $sql = new db_getCLSolicItem; $l_rs_pesquisa = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'VALIDACAOG');
    foreach($l_rs_pesquisa as $row) {
      if (f($row,'qtd_desejada')>0) {
        if (f($row,'qtd_propostas')==0) {
          $l_erro .= '<li>� obrigat�rio informar ao menos uma proposta para o item '.f($row,'nome').' ('.nvl(f($row,'codigo_interno'),'---').') ';
          $l_tipo = 0;
        } elseif (f($row,'qtd_propostas')<f($l_rs_solic,'minimo_participantes')) {
          $l_erro .= '<li>'.f($l_rs_solic,'nm_lcmodalidade').' exige '.f($l_rs_solic,'minimo_participantes').'  proposta'.((f($l_rs_solic,'minimo_participantes')!=1) ? 's' : '').' e '.f($row,'nome').((f($row,'qtd_propostas')>0) ? ' s� tem '.f($row,'qtd_propostas') : ' n�o tem nenhuma').'. Cadastre a quantidade exigida ou justifique quando for concluir.';
          if ($l_tipo == '') $l_tipo = 2;
        }
      }
    }
  }
  
  // Este bloco faz verifica��es em solicita��es que est�o em fases posteriores ao cadastramento inicial
  if (count($l_rs_tramite)>0 || (f($l_rs_tramite,'sigla')=='CI') && f($l_rs_solic,'gera_contrato')=='N') {
    if(f($l_rs_tramite,'sigla')=='AP') {
      if(nvl(f($l_rs_solic,'sq_lcmodalidade'),'')=='') {
        $l_erro.='<li>Informe os dados da an�lise.';
        $l_tipo=0;       
      }
    } elseif(f($l_rs_tramite,'sigla')=='EA' || f($l_rs_tramite,'sigla')=='EE') {
      if(nvl(f($l_rs_solic,'sq_lcsituacao'),'')!='') {
        $l_erro.='<li>Informe os dados da an�lise.';
        $l_tipo=0;       
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
