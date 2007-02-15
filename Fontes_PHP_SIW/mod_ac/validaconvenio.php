<?
// =========================================================================
// Rotina de validação dos dados do convenio
// -------------------------------------------------------------------------
function ValidaConvenio($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
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
  $l_rs_solic = db_getSolicData::getInstanceOf($dbms,$l_chave,$l_sg1);

  // Se a solicitação informada não existir, abandona a execução
  if (count($l_rs_solic)==0) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  } 
  $l_erro='';
  $l_tipo='';
  $l_lista=''; 
  if (f($l_rs_solic,'ativo')=='S') {
    if (substr(f($l_rs_solic,'sigla'),0,3)=='GCC' && f($l_rs_solic,'cd_modalidade')!='I') {
      $l_erro=$l_erro.'<li>Para convênio, o tipo deve ser de parceria institucional.';
      $l_tipo=0;
    }
  }
  // Verifica se o cliente tem o módulo de acordos contratado
  $l_rs_modulo = db_getSiwCliModLis::getInstanceOf($dbms,$l_cliente,null,'AC');
  if (count($l_rs_modulo)>0) $l_acordo='S'; else $l_acordo='N';
 
  // Recupera o trâmite atual da solicitação
  $l_rs_tramite = db_getTramiteData::getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));

// Recupera os dados da outra parte
  $l_rs1 = db_getBenef::getInstanceOf($dbms,$l_cliente,Nvl(f($l_rs_solic,'outra_parte'),0),null,null,null,null,null,null);
  if (($l_rs1==0)) {
    $l_existe_rs1=0; 
  } else {
    $l_existe_rs1=count($l_rs1);
    foreach($l_rs1 as $row) {
      $l_rs1 = $row;
      break;
    }
  }

  // Recupera os dados do preposto
  $l_rs2 = db_getBenef::getInstanceOf($dbms,$l_cliente,Nvl(f($l_rs_solic,'preposto'),0),null,null,null,null,null,null);
  if (count($l_rs2)==0) {
    $l_existe_rs2=0; 
  } else {
    $l_existe_rs2=count($l_rs2);
    foreach($l_rs2 as $row) {
      $l_rs2 = $row;
      break;
    }
  }
   
  // Recupera os dados das parcelas
  $l_rs3 = db_getAcordoParcela::getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,null,null);
  if (count($l_rs3)==0) {
    $l_existe_rs3=0; 
  } else {
    $l_existe_rs3=count($l_rs3);
  }
     
  // Recupera os dados da outra parte
  $l_rs_conv = db_getConvOutraParte::getInstanceOf($dbms,null,$l_chave,null,null);
  if (($l_rs_conv==0)) {
    $l_existe_rs_conv=0; 
  } else {
    $l_existe_rs_conv=count($l_rs_conv);
    foreach($l_rs_conv as $row) {
      // Recupera os dados do preposto
      $l_rs_conv2 = db_getConvPreposto::getInstanceOf($dbms,$l_chave,f($row,'sq_acordo_outra_parte'),null);
      if (count($l_rs_conv2)==0) {    
        $l_existe_rs_conv2=0;
        $l_erro.='<li> O preposto da outra parte <b>('.f($row,'nome_resumido').')</b> não foi infomado.';
        $l_tipo = 0;
      } else {
        $l_existe_rs_conv2=count($l_rs_conv2);
      }
    }
  }
  //-----------------------------------------------------------------------------------
  // O bloco abaixo faz as validações na solicitação que não são possíveis de fazer
  // através do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------
  
  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Validações para a outra parte e preposto
  // Verifica se foi indicada a outra parte
      // Verifica se foi indicada a outra parte
      if ($l_existe_rs1==0) {
        $l_erro.='<li>A outra parte do tipo convente não foi informada';
        $l_tipo=0;
      } 
  // Verifica as parcelas
  if ($l_existe_rs3==0) {
    $l_erro.='<li>É obrigatório informar pelo menos uma parcela';
    $l_tipo=0;
  } else {
    // Verifica se a soma das parcelas é igual ao valor total do acordo
    $l_valor_pacelas=0.00;
    foreach($l_rs3 as $row) { $l_valor_parcelas += f($row,'valor'); }
    if (round(f($l_rs_solic,'valor_inicial')-$l_valor_parcelas,2)!=0) {
      $l_erro.='<li>Valor do acordo ('.number_format(f($l_rs_solic,'valor_inicial'),2,',','.').') difere da soma das parcelas ('.number_format($l_valor_parcelas,2,',','.').')';
      $l_tipo=0;
    }
  }
  // Este bloco faz verificações em solicitações que estão em fases posteriores ao
  // cadastramento inicial
  if (count($l_rs_tramite)>0) {
    if (Nvl(f($l_rs_tramite,'ordem'),'---')>'1') {
      $l_erro=$l_erro; 
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