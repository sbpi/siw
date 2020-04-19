<?php
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
include_once($w_dir_volta.'classes/sp/db_getFormaPagamento.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoData.php');
// =========================================================================
// Fun��o que retorna os valores a serem atribu�dos nas contas cont�beis de
// cr�dito e de d�bito.
// -------------------------------------------------------------------------
function retornaContasContabeis
    ($p_rs_menu,            $p_cliente,
     $p_sq_tipo_lancamento, $p_forma_pagamento, $p_conta_debito, 
     &$p_cc_debito,         &$p_cc_credito
    )
{
  extract($GLOBALS);
  
  // Recupera a terceira posi��o da sigla da op��o de menu, que define se o lan�amento � de receita ou de despesa.
  $l_lancamento = substr(f($p_rs_menu,'sigla'),2,1);

  // Se o tipo de lan�amento j� foi informado, recupera o c�digo externo para definir a conta cont�bil de d�bito.
  // Por�m, se for saque para caixa pequeno, a conta cont�bil de d�bito ser� sempre CAIXA. 
  if ($p_sq_tipo_lancamento) {
    if (f($p_rs_menu,'sigla')=='FNDFIXO') {
      $sql = new db_getFormaPagamento; $RS = $sql->getInstanceOf($dbms, $p_cliente,null,null,'REGISTRO', 'S','ESPECIE');
      $p_finalidade = f($RS[0],'codigo_externo');
    } else {
      $sql = new db_getTipoLancamento; $RS = $sql->getInstanceOf($dbms,$p_sq_tipo_lancamento,null,$p_cliente,null);

      $p_finalidade = f($RS[0],'codigo_externo');
    }
  }
  
  // Se a forma de pagamento for ESP�CIE, usa seu c�digo externo para definir a conta cont�bil de cr�dito;
  // caso contr�rio, e a conta banc�ria j� foi informada, usa seu c�digo externo para definir a conta cont�bil de cr�dito
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
    // Op��es de menu que tenham as letras "A" e "R" na terceira posi��o s�o de receita.
    // Neste caso, a conta cont�bil de cr�dito � a conta banc�ria de destino (ou o caixa)
    // e a conta cont�bil de d�bito � indicada pela finalidade do pagamento.
    $p_cc_debito  = $p_finalidade;
    $p_cc_credito = $p_recurso;
  } else {
    // Em todos os outros casos, a l�gica � inversa � anterior, ou seja:
    // A conta cont�bil de cr�dito � indicada pela finalidade do pagamento
    // e a conta cont�bil de d�bito � a conta banc�ria de destino (ou o caixa).
    $p_cc_debito  = $p_recurso;
    $p_cc_credito = $p_finalidade;
  }
  
  return 0;
} 
?>
