<?php
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
include_once($w_dir_volta.'classes/sp/db_getFormaPagamento.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoData.php');
// =========================================================================
// Função que retorna os valores a serem atribuídos nas contas contábeis de
// crédito e de débito.
// -------------------------------------------------------------------------
function retornaContasContabeis
    ($p_rs_menu,            $p_cliente,
     $p_sq_tipo_lancamento, $p_forma_pagamento, $p_conta_debito, 
     &$p_cc_debito,         &$p_cc_credito
    )
{
  extract($GLOBALS);
  
  // Recupera a terceira posição da sigla da opção de menu, que define se o lançamento é de receita ou de despesa.
  $l_lancamento = substr(f($p_rs_menu,'sigla'),2,1);

  // Se o tipo de lançamento já foi informado, recupera o código externo para definir a conta contábil de débito.
  // Porém, se for saque para caixa pequeno, a conta contábil de débito será sempre CAIXA. 
  if ($p_sq_tipo_lancamento) {
    if (f($p_rs_menu,'sigla')=='FNDFIXO') {
      $sql = new db_getFormaPagamento; $RS = $sql->getInstanceOf($dbms, $p_cliente,null,null,'REGISTRO', 'S','ESPECIE');
      $p_finalidade = f($RS[0],'codigo_externo');
    } else {
      $sql = new db_getTipoLancamento; $RS = $sql->getInstanceOf($dbms,$p_sq_tipo_lancamento,null,$p_cliente,null);

      $p_finalidade = f($RS[0],'codigo_externo');
    }
  }
  
  // Se a forma de pagamento for ESPÉCIE, usa seu código externo para definir a conta contábil de crédito;
  // caso contrário, e a conta bancária já foi informada, usa seu código externo para definir a conta contábil de crédito
  if ($p_forma_pagamento) {
    if (f($p_rs_menu,'sigla')=='FNDFUNDO') {
      $sql = new db_getFormaPagamento; $RS = $sql->getInstanceOf($dbms, $p_cliente,null,null,'REGISTRO', 'S','ESPECIE');
      $p_recurso = f($RS[0],'codigo_externo');
    } elseif ($p_conta_debito) {
      $sql = new db_getContaBancoData; $RS = $sql->getInstanceOf($dbms,$p_conta_debito);
      $p_recurso = f($RS,'codigo_externo');
    }
  }
  
  if (strpos('AR',$l_lancamento)===false) {
    // Opções de menu que tenham as letras "A" e "R" na terceira posição são de receita.
    // Neste caso, a conta contábil de crédito é a conta bancária de destino (ou o caixa)
    // e a conta contábil de débito é indicada pela finalidade do pagamento.
    $p_cc_debito  = $p_finalidade;
    $p_cc_credito = $p_recurso;
  } else {
    // Em todos os outros casos, a lógica é inversa à anterior, ou seja:
    // A conta contábil de crédito é indicada pela finalidade do pagamento
    // e a conta contábil de débito é a conta bancária de destino (ou o caixa).
    $p_cc_debito  = $p_recurso;
    $p_cc_credito = $p_finalidade;
  }
  
  return 0;
} 
?>
