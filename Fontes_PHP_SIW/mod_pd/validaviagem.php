<?
// =========================================================================
// Rotina de validação dos dados da missão
// -------------------------------------------------------------------------
function ValidaViagem($v_cliente,$v_chave,$v_sg1,$v_sg2,$v_sg3,$v_sg4,$v_tramite) {
  extract($GLOBALS);

  // Se não encontrar erro, esta função retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. A solicitação só pode ser devolvida
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
  $l_rs_solic = db_getSolicData::getInstanceOf($dbms,$v_chave,$v_sg1);

  // Se a solicitação informada não existir, abandona a execução
  if (($l_rs_solic==0)) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  } 

  // Verifica se o cliente tem o módulo de viagens contratado
  $l_rs_modulo = db_getSiwCliModLis::getInstanceOf($dbms,$v_cliente,null,'PD');
  if (!($l_rs_modulo==0)) $l_viagem='S'; else $l_viagem='N';

  $l_erro   = '';
  $l_tipo   = '';

  // Recupera o trâmite atual da solicitação
  $l_rs_tramite = db_getTramiteData::getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));

  // Recupera os dados do proposto
  $l_rs1 = db_getBenef::getInstanceOf($dbms,$v_cliente,Nvl(f($l_rs_solic,'sq_prop'),0),null,null,null,null,null,null,null,null,null,null,null,null);
  $l_existe_rs1 = count($l_rs1);
  if ($l_existe_rs1>0) {
    foreach($l_rs1 as $l_row) { $l_rs1 = $l_row; break; }
  }

  // Recupera os parâmetros do módulo de viagem
  $l_rs2 = db_getPDParametro::getInstanceOf($dbms,$v_cliente,null,null); 
  $l_existe_rs2 = count($l_rs2);

  // Recupera os deslocamentos da viagem
  $l_rs3 = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave, null, $v_sg2);
  $l_existe_rs3 = count($l_rs3);

  // Recupera as vinculações da viagem
  $l_rs4 = db_getPD_Vinculacao::getInstanceOf($dbms,$v_chave,null,null);
  $l_existe_rs4 = count($l_rs4);

  //-----------------------------------------------------------------------------------
  // O bloco abaixo faz as validações na solicitação que não são possíveis de fazer
  // através do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Verifica se foi indicado o proposto e se seus dados estão completos
      // Verifica se foi indicado o proposto
      if ($l_existe_rs1==0) {
        $l_erro .= '<li>O proposto não foi informado';
        $l_tipo  = 0;
      } else {
        // Verifica se o proposto tem os dados bancários cadastrados
        if (nvl(f($l_rs_solic,'sq_banco'),'')=='' || nvl(f($l_rs_solic,'sq_agencia'),'')=='' || nvl(f($l_rs_solic,'numero_conta'),'')=='') {
          $l_erro .= '<li>Dados bancários incompletos.';
          $l_tipo  = 0;
        } 
      } 

      // Verifica se foram cadastrados pelo menos 2 deslocamentos
      if ($l_existe_rs3<2) {
        $l_erro .= '<li>É obrigatório informar pelo menos 2 deslocamentos.';
        $l_tipo  = 0;
      } 

/**
*       // Verifica se a viagem foi vinculada a pelo menos uma tarefa
*       if ($l_existe_rs4==0) {
*          $l_erro .= '<li>É obrigatório vincular a PCD a pelo menos uma atividade ou demanda eventual.';
*          $l_tipo  = 0;
*       } 
*/

  // Este bloco faz verificações em solicitações que estão em fases posteriores ao cadastramento inicial
    if (Nvl(f($l_rs_tramite,'ordem'),'---')>'1') {
        // Verifica se o início da missão atende ao número de dias de antecedência regulamentares. 
        // Se não atender, deve ser informada justificativa.
        if (!(strpos('CH,DF,EA',Nvl(f($l_rs_tramite,'sigla'),'CH'))===false)) {
          if ((f($l_rs_solic,'inicio')-f($l_rs2,'dias_antecedencia')<time()) && nvl(f($l_rs_solic,'justificativa'),'')=='') {
            $l_erro .= '<li>No encaminhamento da PCD deve ser informada a justificativa para não cumprimento dos '.f($l_rs2,'dias_antecedencia').' dias de antecedência do pedido.';
            $l_tipo = 2;
          } 
        } 
        
        if (Nvl(f($l_rs_tramite,'sigla'),'---')=='DF') {
          $l_rs5 = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave,null,f($l_rs_tramite,'sigla'));
          foreach($l_rs5 as $row) {$l_rs5 = $row; break;}
          if (f($l_rs5,'existe')==0) {
            $l_erro .= '<li>É obrigatório informar as diárias, mesmo que os valores sejam zeros.';
            $l_tipo  = 0;
          } 
        } elseif (Nvl(f($l_rs_tramite,'sigla'),'---')=='AE') {
          if ((Nvl(f($l_rs_solic,'pta'),'')=='' && Nvl(f($l_rs_solic,'valor_passagem'),0)==0)) {
            $l_erro .= '<li>É obrigatório informar os dados das passagens.';
            $l_tipo  = 0;
          } 
        } 
        $l_erro = $l_erro;
    } 

  $l_erro = $l_tipo.$l_erro;

  //-----------------------------------------------------------------------------------
  // Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------

  return $l_erro;
} 
?>
