<?php
header('Expires: ' . -1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta . 'constants.inc');
include_once($w_dir_volta . 'jscript.php');
include_once($w_dir_volta . 'funcoes.php');
include_once($w_dir_volta . 'classes/db/abreSessao.php');
include_once($w_dir_volta . 'classes/sp/db_getLinkData.php');
include_once($w_dir_volta . 'classes/sp/db_getMenuData.php');
include_once($w_dir_volta . 'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta . 'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicList.php');
include_once($w_dir_volta . 'classes/sp/db_getAfastamento.php');
include_once($w_dir_volta . 'classes/sp/db_getViagemBenef.php');
include_once($w_dir_volta . 'classes/sp/db_getGPTipoAfast.php');
include_once($w_dir_volta . 'classes/sp/db_getGPColaborador.php');
include_once($w_dir_volta . 'classes/sp/db_getGPParametro.php');
include_once($w_dir_volta . 'classes/sp/db_getGPContrato.php');
include_once($w_dir_volta . 'classes/sp/db_getGPFolhaPontoDiario.php');
include_once($w_dir_volta . 'classes/sp/db_getGPFolhaPontoMensal.php');
include_once($w_dir_volta . 'classes/sp/db_getPersonList.php');
include_once($w_dir_volta . 'classes/sp/db_getUserResp.php');
include_once($w_dir_volta . 'classes/sp/db_getGpDesempenho.php');
include_once($w_dir_volta . 'classes/sp/db_getCV.php');
include_once($w_dir_volta . 'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta . 'classes/sp/dml_putGPColaborador.php');
include_once($w_dir_volta . 'classes/sp/dml_putGPContrato.php');
include_once($w_dir_volta . 'classes/sp/dml_putGpDesempenho.php');
include_once($w_dir_volta . 'classes/sp/dml_putGpPontoDiario.php');
include_once($w_dir_volta . 'classes/sp/dml_putGpPontoMensal.php');
include_once($w_dir_volta . 'funcoes/exibeColaborador.php');
include_once($w_dir_volta . 'funcoes/selecaoColaborador.php');
include_once($w_dir_volta . 'funcoes/selecaoModalidade.php');
include_once($w_dir_volta . 'funcoes/selecaoUnidade.php');
include_once($w_dir_volta . 'funcoes/selecaoCargo.php');
include_once($w_dir_volta . 'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta . 'funcoes/selecaoVinculo.php');
include_once('validacolaborador.php');
// =========================================================================
//  /folha.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar o cadastramento de colaboradores
// Mail     : billy@sbpi.com.br
// Criacao  : 11/08/2006 10:00
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = E   : Exclus�o
//                   = L   : Listagem

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par = upper($_REQUEST['par']);
$P1 = Nvl($_REQUEST['P1'], 0);
$P2 = Nvl($_REQUEST['P2'], 0);
$P3 = Nvl($_REQUEST['P3'], 1);
$P4 = Nvl($_REQUEST['P4'], $conPageSize);
$TP = $_REQUEST['TP'];
$SG = upper($_REQUEST['SG']);
$R = lower($_REQUEST['R']);
$O = upper($_REQUEST['O']);
$p_ordena = lower($_REQUEST['p_ordena']);
$w_troca = lower($_REQUEST['w_troca']);
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina = 'folha.php?par=';
$w_dir = 'mod_rh/';
$w_dir_volta = '../';
$w_Disabled = 'ENABLED';

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='')
  $O = 'P';
switch ($O) {
  case 'I': $w_TP = $TP . ' - Inclus�o'; break;
  case 'P': $w_TP = $TP . ' - Filtragem'; break;
  case 'A': $w_TP = $TP . ' - Altera��o'; break;
  case 'E':
    if ($par=='CONTRATO')
      $w_TP = $TP . ' - Encerramento';
    else
      $w_TP=$TP . ' - Exclus�o';
    break;
  default: $w_TP = $TP . ' - Listagem'; break;
}
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);

// Verifica se o cliente tem o m�dulo de viagens
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, 'PD');
$w_mod_pd = 'N';
foreach ($RS as $row)
  $w_mod_pd = 'S';

// Recupera os par�metros do m�dulo de pessoal
$sql = new db_getGPParametro; $RS_Parametro = $sql->getInstanceOf($dbms, $w_cliente, null, null);
foreach ($RS_Parametro as $row) {$RS_Parametro = $row; break;}

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina folha de ponto mensal do colaborador
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  Global $w_minutos_diarios;
  Global $w_primeiro_turno;
  Global $w_segundo_turno;
  $w_chave = $_REQUEST['w_chave'];
  $w_mes = $_REQUEST['w_mes'];

  // Configura vari�veis para montagem do calend�rio
  if (nvl($w_mes, '')=='') $w_mes = date('m/Y', time());
  $w_dt_inicio = first_day(toDate('01/' . $w_mes));
  $w_dt_fim = last_day(toDate('01/' . $w_mes));
  $w_dia_fim = date('j', $w_dt_fim);
  $w_mes_atual = date('m/Y', time());
  $w_dia_atual = date('j', time());

  // Verifica se o usu�rio deve aprovar folhas de ponto do m�s indicado
  $sql = new db_getGPFolhaPontoMensal; $RSUorg = $sql->getInstanceOf($dbms, $w_usuario, substr($w_mes, 3, 4) . substr($w_mes, 0, 2), 'APROVACAO');

  //Recupera os dados do contrato
  if (nvl($w_chave, '')!='') {
    $sql = new db_getGPContrato; $RSContrato = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, $w_usuario, null, null, null, null, null, null, null, formataDataEdicao($w_dt_fim), null, null);
  } else {
    $sql = new db_getGPContrato; $RSContrato = $sql->getInstanceOf($dbms, $w_cliente, null, $w_usuario, null, null, null, null, null, null, null, formataDataEdicao($w_dt_fim), null, null);
    $RSContrato = SortArray($RSContrato, 'fim', 'asc');
  }
  
  if (count($RSUorg)==0 && count($RSContrato)==0) {
    Cabecalho();
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
    ShowHTML('<HR>');
    ShowHTML('<table width="97%" border="0">');
    ShowHTML('  <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O: N�o h� folha de ponto dispon�vel para registro nem aprova��o! Clique <a class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();">aqui</a> para fechar esta janela.</font></b></td>');
    ShowHTML('</table>');
    Rodape();
    exit();
  }
  foreach ($RSContrato as $row) { $RSContrato = $row; break;  }
  $w_contrato = f($RSContrato, 'chave');
  $w_inicio_contrato = formataDataEdicao(f($RSContrato, 'inicio'));
  //$w_minutos_diarios      = f($RSContrato,'minutos_diarios');
  $w_carga_diaria = f($RSContrato, 'carga_diaria');
  $w_entrada_manha = f($RSContrato, 'entrada_manha');
  $w_saida_manha = f($RSContrato, 'saida_manha');
  $w_entrada_tarde = f($RSContrato, 'entrada_tarde');
  $w_saida_tarde = f($RSContrato, 'saida_tarde');
  $w_entrada_noite = f($RSContrato, 'entrada_noite');
  $w_saida_noite = f($RSContrato, 'saida_noite');
  $w_sabado = f($RSContrato, 'sabado');
  $w_domingo = f($RSContrato, 'domingo');
  $w_saldo_banco = f($RSContrato, 'banco_horas_saldo');
  $w_saldo_meses = f($RSContrato, 'banco_horas_mensal');
  $w_trata_extras = f($RSContrato, 'trata_extras');
  $w_tot_banco = horario2minutos('', $w_saldo_banco) + horario2minutos('', $w_saldo_meses);
  $w_minutos_tolerancia = f($RS_Parametro, 'minutos_tolerancia');
  $w_tipo_tolerancia = f($RS_Parametro, 'tipo_tolerancia');

  if (Nvl($w_trata_extras, '')!= '' && Nvl($w_trata_extras, '')!= 'N') {
    $w_limite_diario_extras = f($RS_Parametro, 'limite_diario_extras');
  } else {
    $w_limite_diario_extras = '00:00';
  }

  //C�lculo da toler�ncia de horas extras  
  $w_limite = minutos2horario(horario2minutos('', $w_carga_diaria) + $w_minutos_tolerancia + horario2minutos('', $w_limite_diario_extras));


  //Informa��es da folha de ponto di�ria
  $sql = new db_getGPFolhaPontoDiario; $RSFolha = $sql->getInstanceOf($dbms, $w_contrato, substr($w_mes, 3, 4) . substr($w_mes, 0, 2), null);
  foreach ($RSFolha as $row) {
    $w_dias[intVal(substr(formataDataEdicao(f($row, 'data')), 0, 2))] = $row;
  }

  //Informa��es da folha de ponto mensal
  $sql = new db_getGPFolhaPontoMensal; $RSMensal = $sql->getInstanceOf($dbms, $w_contrato, substr($w_mes, 3, 4) . substr($w_mes, 0, 2), null);
  foreach ($RSMensal as $row) {$RSMensal = $row; break;  }
  $w_total = Nvl(f($RSMensal, 'horas_trabalhadas'), '00:00');
  $w_extras = Nvl(f($RSMensal, 'horas_extras'), '00:00');
  $w_atrasos = Nvl(f($RSMensal, 'horas_atrasos'), '00:00');
  $w_banco = Nvl(f($RSMensal, 'horas_banco'), '00:00');

  if (Nvl($w_entrada_manha, '')!='' && Nvl($w_saida_manha, '')!='') {
    $w_primeiro_turno = 'manh�';
    $w_minutos_primeiro_turno = horario2minutos('', $w_saida_manha) - horario2minutos('', $w_entrada_manha);

    if (Nvl($w_entrada_noite, '')!='' && Nvl($w_saida_noite, '')!='') {
      $w_segundo_turno = 'noite';
      $w_minutos_segundo_turno = horario2minutos('', $w_saida_noite) - horario2minutos('', $w_entrada_noite);
    } elseif (Nvl($w_entrada_tarde, '')!='' && Nvl($w_saida_tarde, '')!='') {
      $w_segundo_turno = 'tarde';
      $w_minutos_segundo_turno = horario2minutos('', $w_saida_tarde) - horario2minutos('', $w_entrada_tarde);
    } else {
      $w_segundo_turno = '';
      $w_minutos_segundo_turno = 0;
    }
  } else {
    if (Nvl($w_entrada_tarde, '')!='' && Nvl($w_saida_tarde, '')!='') {
      $w_primeiro_turno = 'tarde';
      $w_minutos_primeiro_turno = horario2minutos('', $w_saida_tarde) - horario2minutos('', $w_entrada_tarde);
      if (Nvl($w_entrada_noite, '')!='' && Nvl($w_saida_noite, '')!='') {
        $w_segundo_turno = 'noite';
        $w_minutos_segundo_turno = horario2minutos('', $w_saida_noite) - horario2minutos('', $w_entrada_noite);
      } else {
        $w_segundo_turno = '';
        $w_minutos_segundo_turno = 0;
      }
    } else {
      $w_primeiro_turno = 'noite';
      $w_minutos_primeiro_turno = horario2minutos('', $w_saida_noite) - horario2minutos('', $w_entrada_noite);
      $w_segundo_turno = '';
      $w_minutos_segundo_turno = 0;
    }
  }

  //Recupera datas especiais do m�s
  include_once($w_dir_volta . 'classes/sp/db_getDataEspecial.php');
  $sql = new db_getDataEspecial; $RS_Ano = $sql->getInstanceOf($dbms, $w_cliente, null, date('Y', $w_dt_inicio), 'S', null, null, null);
  $RS_Ano = SortArray($RS_Ano, 'data_formatada', 'asc');
  foreach ($RS_Ano as $row) {
    if (date('m/Y', f($row, 'data_formatada'))==$w_mes) {
      $w_feriados[date('j', f($row, 'data_formatada'))]['nome'] = f($row, 'nome');
      $w_feriados[date('j', f($row, 'data_formatada'))]['tipo'] = f($row, 'expediente');
    }
  }

  if ($w_mod_pd=='S') {
    $sql = new db_getLinkData; $RSMenu_Viagem = $sql->getInstanceOf($dbms, $w_cliente, 'PDINICIAL');
    $sql = new db_getSolicList; $RS_Viagem = $sql->getInstanceOf($dbms, f($RSMenu_Viagem, 'sq_menu'), $w_usuario, 'PD', 4,
                    formataDataEdicao($w_dt_inicio), formataDataEdicao($w_dt_fim), null, null, null, null, null, null, null, null, null,
                    null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_usuario);
    $RS_Viagem = SortArray($RS_Viagem, 'inicio', 'desc', 'fim', 'desc');

    /* Cria arrays com cada dia do per�odo,
     * definindo o texto e a cor de fundo para
     * exibi��o na folha de ponto
     */

    foreach ($RS_Viagem as $row) {
      $w_ini_viagem = f($row, 'inicio');
      $w_fim_viagem = f($row, 'fim');
      for ($i = $w_ini_viagem; $i<=$w_fim_viagem; $i = addDays($i, 1)) {
        if (date('m/Y', $i)==$w_mes) {
          if ($i==$w_ini_viagem) {
            $w_feriados[date('j', $i)]['nome'] = 'VIAGEM (SA�DA ' . date('H:i', f($row, 'phpdt_saida')) . ')';
            $w_feriados[date('j', $i)]['saida'] = date('H:i', f($row, 'phpdt_saida'));
            if (Nvl($w_segundo_turno, '')!='') {
              if (Nvl($w_segundo_turno, '')=='tarde') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_tarde) {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (Nvl($w_primeiro_turno, '')=='manh�') {
                  if (date('H', f($row, 'phpdt_saida')) > $w_saida_manha)
                    $w_feriados[date('j', $i)]['tipo'] = 'M';
                }
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_tarde) {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (date('H', f($row, 'phpdt_saida')) > $w_saida_manha) {
                  $w_feriados[date('j', $i)]['tipo'] = 'M';
                }
              } elseif (Nvl($w_segundo_turno, '')=='noite') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_noite) {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (Nvl($w_primeiro_turno, '')=='manh�') {
                  if ((date('H', f($row, 'phpdt_saida')) > $w_entrada_noite) || ( date('H', f($row, 'phpdt_saida')) > $w_saida_manha && date('H', f($row, 'phpdt_saida')) < $w_entrada_noite)) {
                    $w_feriados[date('j', $i)]['tipo'] = 'M';
                  } elseif (date('H', f($row, 'phpdt_saida')) < $w_entrada_manha) {
                    $w_feriados[date('j', $i)]['tipo'] = 'N';
                  }
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  if (date('H', f($row, 'phpdt_saida')) > $w_entrada_noite)
                    $w_feriados[date('j', $i)]['tipo'] = 'M';
                }
              }
            }elseif (Nvl($w_primeiro_turno, '')!='' && Nvl($w_segundo_turno, '')=='') {
              if (Nvl($w_primeiro_turno, '')=='manh�') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_manha)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_tarde)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }elseif (Nvl($w_primeiro_turno, '')=='noite') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_noite)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }
            }

            //elseif (date('H',f($row,'phpdt_saida'))>13) $w_feriados[date('j',$i)]['tipo'] = 'M';
            else
              $w_feriados[date('j', $i)]['tipo'] = 'N';
          } elseif ($i==$w_fim_viagem) {
            //echo '('.formataDataEdicao($i).')';
            $w_feriados[date('j', $i)]['nome'] = 'VIAGEM (CHEGADA ' . date('H:i', f($row, 'phpdt_chegada')) . ')';
            $w_feriados[date('j', $i)]['chegada'] = date('H:i', f($row, 'phpdt_chegada'));

            if (Nvl($w_segundo_turno, '')!='') {
              if (Nvl($w_segundo_turno, '')=='tarde') {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_manha) {
                    $w_feriados[date('j', $i)]['tipo'] = 'S';
                  } elseif (date('H', f($row, 'phpdt_chegada')) > $w_entrada_manha && date('H', f($row, 'phpdt_chegada')) < $w_entrada_tarde) {
                    $w_feriados[date('j', $i)]['tipo'] = 'T';
                  } elseif (date('H', f($row, 'phpdt_chegada')) > $w_entrada_tarde) {
                    $w_feriados[date('j', $i)]['tipo'] = 'N';
                  } else {
                    $w_feriados[date('j', $i)]['tipo'] = 'M';
                  }
                }
              } elseif (Nvl($w_segundo_turno, '')=='noite') {
                if (date('H', f($row, 'phpdt_chegada')) > $w_saida_noite) {
                  $w_feriados[date('j', $i)]['tipo'] = 'N';
                } elseif (Nvl($w_primeiro_turno, '')=='manh�') {
                  if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_manha) {
                    $w_feriados[date('j', $i)]['tipo'] = 'S';
                  } elseif (date('H', f($row, 'phpdt_chegada')) > $w_entrada_manha && date('H', f($row, 'phpdt_chegada')) < $w_saida_noite) {
                    $w_feriados[date('j', $i)]['tipo'] = 'T';
                  }
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_tarde) {
                    $w_feriados[date('j', $i)]['tipo'] = 'S';
                  } elseif (date('H', f($row, 'phpdt_chegada')) < $w_saida_tarde && date('H', f($row, 'phpdt_chegada')) > $w_entrada_tarde) {
                    $w_feriados[date('j', $i)]['tipo'] = 'T';
                  }
                }
              }
            } elseif (Nvl($w_primeiro_turno, '')!='' && Nvl($w_segundo_turno, '')=='') {
              if (Nvl($w_primeiro_turno, '')=='manh�') {
                if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_manha)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_tarde)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }elseif (Nvl($w_primeiro_turno, '')=='noite') {
                if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_noite)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }
            }
            /* if     (date('H',f($row,'phpdt_chegada'))<18) $w_feriados[date('j',$i)]['tipo'] = 'S';
             * lseif (date('H',f($row,'phpdt_chegada'))<14) $w_feriados[date('j',$i)]['tipo'] = 'T';
             * lse $w_feriados[date('j',$i)]['tipo'] = 'N'; */
          } else {
            $w_feriados[date('j', $i)]['nome'] = 'VIAGEM';
            $w_feriados[date('j', $i)]['tipo'] = 'N';
          }
        }
      }
    }
  }

  //Recupera afastamentos do m�s
  $sql = new db_getAfastamento; $RS_Afast = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, null, null, null, formataDataEdicao($w_dt_inicio), formataDataEdicao($w_dt_fim), null, null, null, null);
  $RS_Afast = SortArray($RS_Afast, 'inicio_data', 'desc', 'inicio_periodo', 'asc', 'fim_data', 'desc', 'inicio_periodo', 'asc');
  foreach ($RS_Afast as $row) {
    $w_ini_afast = f($row, 'inicio_data');
    $w_fim_afast = f($row, 'fim_data');
    for ($i = $w_ini_afast; $i<=$w_fim_afast; $i = addDays($i, 1)) {

      /* if (date('m/Y',$i)==$w_mes) {
       * w_feriados[date('j',$i)]['nome'] = f($row,'nm_tipo_afastamento');
       * f($i==$w_ini_afast) {
       * f (f($row,'inicio_periodo')=='M') $w_feriados[date('j',$i)]['tipo'] = 'N';
       * lse $w_feriados[date('j',$i)]['tipo'] = 'M';
       } elseif ($i==$w_fim_afast) {
       * f (f($row,'fim_periodo')=='T') $w_feriados[date('j',$i)]['tipo'] = 'N';
       * lse $w_feriados[date('j',$i)]['tipo'] = 'M';
       } else   $w_feriados[date('j',$i)]['tipo'] = 'N';
       } */
      if (date('m/Y', $i)==$w_mes) {
        $w_feriados[date('j', $i)]['nome'] = f($row, 'nm_tipo_afastamento');
        $w_feriados[date('j', $i)]['sigla'] = f($row, 'sigla');
        if ($i==$w_ini_afast) {
          if (substr(f($row, 'sigla'), 0, 1)=='F') {
            if (f($row, 'sigla') == 'FM') {
              if (Nvl($w_segundo_turno, '')!='') {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  $w_feriados[date('j', $i)]['tipo'] = 'T';
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                }
              } else {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  $w_feriados[date('j', $i)]['tipo'] = 'N';
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                }
              }
            } elseif (f($row, 'sigla') == 'FT') {
              if (Nvl($w_segundo_turno, '')!='') {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  $w_feriados[date('j', $i)]['tipo'] = 'M';
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'T';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                }
              } else {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'N';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                }
              }
            } else {
              $w_feriados[date('j', $i)]['tipo'] = 'N';
            }
          } else {
            if (f($row, 'inicio_periodo')=='M') {
              if (Nvl($w_primeiro_turno, '')=='manh�') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_primeiro_turno, '')=='noite') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              }
            } else {
              if (Nvl($w_primeiro_turno, '')=='manh�') {
                if (Nvl($w_segundo_turno, '')=='') {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (Nvl($w_segundo_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'M';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'T';
                }
              } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_primeiro_turno, '')=='noite') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              }
            }
          }

          //else $w_feriados[date('j',$i)]['tipo'] = 'M';
        } elseif ($i==$w_fim_afast) {
          if (f($row, 'fim_periodo')=='T') {
            if (Nvl($w_primeiro_turno, '')=='manh�') {
              if (Nvl($w_segundo_turno, '')=='') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_segundo_turno, '')=='tarde') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } else {
                $w_feriados[date('j', $i)]['tipo'] = 'T';
              }
            } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
              if (Nvl($w_segundo_turno, '')=='') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_segundo_turno, '')=='noite') {
                $w_feriados[date('j', $i)]['tipo'] = 'T';
              }
              //$w_feriados[date('j',$i)]['tipo'] = 'T';
            } elseif (Nvl($w_primeiro_turno, '')=='noite') {
              $w_feriados[date('j', $i)]['tipo'] = 'S';
            }
          } else { // Fim do afastamento � pela manh� (M)
            if (Nvl($w_primeiro_turno, '')=='manh�') {
              if (Nvl($w_segundo_turno, '')=='') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_segundo_turno, '')=='tarde') {
                $w_feriados[date('j', $i)]['tipo'] = 'T';
              } else {
                $w_feriados[date('j', $i)]['tipo'] = 'T';
              }
            } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
              if (Nvl($w_segundo_turno, '')=='') {
                $w_feriados[date('j', $i)]['tipo'] = 'S';
              } else {
                $w_feriados[date('j', $i)]['tipo'] = 'S';
              }
            } elseif (Nvl($w_primeiro_turno, '')=='noite') {
              $w_feriados[date('j', $i)]['tipo'] = 'S';
            } elseif (Nvl($w_primeiro_turno, '')=='noite') {
              $w_feriados[date('j', $i)]['tipo'] = 'M';
            }
          }//$w_feriados[date('j',$i)]['tipo'] = 'N';
          //else $w_feriados[date('j',$i)]['tipo'] = 'M';
        } else
          $w_feriados[date('j', $i)]['tipo'] = 'N';
      }
    }
  }

  if ($w_troca > '' && $O!='E') {
    $w_ano = $_REQUEST['w_ano'];
    $w_percentual = $_REQUEST['w_percentual'];
  } elseif ($O=='L') {
    //$sql = new db_getGpFolha; $RS = $sql->getInstanceOf($dbms, $w_chave,$w_mes);
    //$RS = SortArray($RS,'data','asc');
  }

  Cabecalho();
  head();
  ShowHTML('<TITLE>' . $conSgSistema . ' - Folha de Ponto</TITLE>');
  Estrutura_CSS($w_cliente);

  ScriptOpen('JavaScript');
  modulo();
  checkbranco();
  FormataDataMA();
  FormataHora();
  SaltaCampo();
  ShowHTML('function calculaDia(dia) {');
  ShowHTML('  var entrada1 = document.Form["w_entrada1[]"][dia].value;');
  ShowHTML('  var saida1 = document.Form["w_saida1[]"][dia].value;');
  ShowHTML('  var minutos_diarios = document.Form["w_minutos_diarios[]"][dia].value;');
  if (Nvl($w_segundo_turno, '')!='') {
    ShowHTML('  var entrada2        = document.Form["w_entrada2[]"][dia].value;');
    ShowHTML('  var saida2          = document.Form["w_saida2[]"][dia].value;');
  } else {
    ShowHTML('  var entrada2 = "00:00"');
    ShowHTML('  var saida2   = "00:00"');
  }
  ShowHTML('  var saldo1 = 0;');
  ShowHTML('  var saldo2 = 0;');
  ShowHTML('  var saldo3 = 0;');
  ShowHTML('  var saldo = "00:00";');
  ShowHTML('  var minutos_tolerancia = ' . $w_minutos_tolerancia . ';');
  ShowHTML('  var tipo_tolerancia    = ' . $w_tipo_tolerancia . ';');
  ShowHTML('  var fator              = 0;');
  ShowHTML('  if (entrada1!="" && saida1!="") {');
  ShowHTML('    var minutos1 = parseInt(entrada1.substring(0,2)*60,10) + parseInt(entrada1.substring(3),10);');
  ShowHTML('    var minutos2 = parseInt(saida1.substring(0,2)*60,10) + parseInt(saida1.substring(3),10);');
  ShowHTML('    var saldo1 = minutos2 - minutos1;');
  ShowHTML('  } else {');
  ShowHTML('    var minutos1 = 0;');
  ShowHTML('    var minutos2 = 0;');
  ShowHTML('    var saldo1 = 0;');
  ShowHTML('  }');
  ShowHTML('  if (entrada2!="" && saida2!="") {');
  ShowHTML('    var minutos3 = parseInt(entrada2.substring(0,2)*60,10) + parseInt(entrada2.substring(3),10)');
  ShowHTML('    var minutos4 = parseInt(saida2.substring(0,2)*60,10) + parseInt(saida2.substring(3),10)');
  ShowHTML('    var saldo2 = minutos4 - minutos3;');
  ShowHTML('  } else {');
  ShowHTML('    var minutos3 = 0;');
  ShowHTML('    var minutos4 = 0;');
  ShowHTML('    var saldo2 = 0;');
  ShowHTML('  }');
  ShowHTML('  if (saldo1!="" && saldo2!="") saldo3 = saldo1 + saldo2;');
  ShowHTML('  else if (saldo1!="" && saldo2=="") saldo3 = saldo1;');
  ShowHTML('  else if (saldo1=="" && saldo2!="") saldo3 = saldo2;');
  ShowHTML('  else saldo3 = 0;');
  ShowHTML('  var saldo4 = parseInt(saldo3-(minutos_diarios),10);');
  ShowHTML('  if (saldo4!=""){');
  ShowHTML('    if(minutos_diarios > 0 && minutos_diarios > saldo3){');
  ShowHTML('      var diferenca = minutos_diarios - saldo3');
  ShowHTML('      if(diferenca > 0 && diferenca <= minutos_tolerancia){');
  ShowHTML('        saldo4 = saldo4 + diferenca');
  ShowHTML('      }else if(diferenca > minutos_tolerancia){');
  ShowHTML('        saldo4 = diferenca;');
  ShowHTML('      }');
  ShowHTML('    }else if(minutos_diarios < saldo3){');
  ShowHTML('      var diferenca = minutos_diarios - saldo3');
  ShowHTML('      if(diferenca < 0 && diferenca >= -minutos_tolerancia){');
  ShowHTML('        saldo4 = saldo4 + diferenca');
  ShowHTML('      }else if(diferenca < minutos_tolerancia){');
  ShowHTML('        saldo4 = diferenca;');
  //ShowHTML('  alert(saldo4);');
  ShowHTML('      }');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  var horas   = parseInt(saldo3/60,10);');
  ShowHTML('  var minutos = (saldo3) - parseInt(horas*60,10);');
  ShowHTML('  saldo = String(100+horas).substring(1) + ":" + String(100+minutos).substring(1);');
  ShowHTML('  var sinal = "";');
  ShowHTML('  if (saldo4<0) sinal = "-";');
  ShowHTML('  saldo4 = Math.abs(saldo4);');
  ShowHTML('  var horas_saldo = parseInt(saldo4/60,10);');
  ShowHTML('  var minutos_saldo = saldo4 - parseInt(horas_saldo*60,10);');
  ShowHTML('  saldo_dia = sinal + String(100+horas_saldo).substring(1) + ":" + String(100+minutos_saldo).substring(1);');
  ShowHTML('  ');
  ShowHTML('  if (saldo1!="" || saldo2!="") {');
  ShowHTML('    document.Form["w_trabalhadas[]"][dia].value = saldo;');
  ShowHTML('    document.Form["w_saldo_dia[]"][dia].value = saldo_dia;');
  ShowHTML('  } else {');
  ShowHTML('    document.Form["w_trabalhadas[]"][dia].value = "";');
  ShowHTML('    document.Form["w_saldo_dia[]"][dia].value = "";');
  ShowHTML('  }');
  ShowHTML('  calculaMes();');
  ShowHTML('}');

  ShowHTML('function calculaMes() {');
  ShowHTML('   var theForm = document.Form;');
  ShowHTML('   var tempo,horas,minutos;');
  ShowHTML('   var tot_horas,tot_minutos,trabalhadas;');
  ShowHTML('   var tot_trabalho = 0;');
  ShowHTML('   var tot_atraso = 0;');
  ShowHTML('   var tot_extra = 0;');
  ShowHTML('   var tot_banco = 0;');
  ShowHTML('   var minutos_diarios;');
  ShowHTML('   var sinal = "";');
  ShowHTML('   for (i=0; i < theForm["w_trabalhadas[]"].length; i++) {');
  ShowHTML('     if (theForm["w_trabalhadas[]"][i].value!="") {');
  ShowHTML('       tempo = theForm["w_trabalhadas[]"][i].value;');
  ShowHTML('       horas   = parseInt(tempo.substr(0,2)*60,10);');
  ShowHTML('       minutos = parseInt(tempo.substr(3,2),10);');
  ShowHTML('       tot_trabalho = tot_trabalho + horas + minutos;');
  ShowHTML('       minutos_diarios = document.Form["w_minutos_diarios[]"][i].value;');
  ShowHTML('       if (theForm["w_saldo_dia[]"][i].value.substr(0,1)=="-") {');
  // Trata o atraso
  ShowHTML('         tempo = theForm["w_saldo_dia[]"][i].value;');
  ShowHTML('         horas   = parseInt(tempo.substr(1,2)*60,10);');
  ShowHTML('         minutos = parseInt(tempo.substr(4,2),10);');
  ShowHTML('         tot_atraso = tot_atraso + horas + minutos;');
  ShowHTML('       } else {');
  // Trata a hora extra
  ShowHTML('         tempo = theForm["w_saldo_dia[]"][i].value;');
  ShowHTML('         horas   = parseInt(tempo.substr(0,2)*60,10);');
  ShowHTML('         minutos = parseInt(tempo.substr(3,2),10);');
  ShowHTML('         tot_extra = tot_extra + horas + minutos;');
  ShowHTML('       }');
  ShowHTML('     }');
  ShowHTML('   }');
  ShowHTML('   tot_horas = parseInt(tot_trabalho/60,10);');
  ShowHTML('   tot_minutos = tot_trabalho - (tot_horas*60);');
  ShowHTML('   if (tot_horas<10) {');
  ShowHTML('     trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   } else {');
  ShowHTML('     trabalhadas = tot_horas + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   }');
  ShowHTML('   theForm.w_total.value = trabalhadas;');

  ShowHTML('   tot_horas = Math.abs(parseInt(tot_atraso/60,10));');
  ShowHTML('   tot_minutos = Math.abs(tot_atraso) - (tot_horas*60);');
  ShowHTML('   if (tot_horas<10) {');
  ShowHTML('     trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   } else {');
  ShowHTML('     trabalhadas = tot_horas + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   }');
  ShowHTML('   theForm.w_atrasos.value = trabalhadas;');

  ShowHTML('   tot_horas = parseInt(tot_extra/60,10);');
  ShowHTML('   tot_minutos = tot_extra - (tot_horas*60);');
  ShowHTML('   if (tot_horas<10) {');
  ShowHTML('     trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   } else {');
  ShowHTML('     trabalhadas = tot_horas + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   }');
  ShowHTML('   theForm.w_extras.value = trabalhadas;');

  ShowHTML('   tot_banco = tot_banco - tot_atraso + tot_extra;');
  ShowHTML('   if (tot_banco<0) sinal = "-"; else sinal = "";');
  ShowHTML('   tot_horas = Math.abs(parseInt(tot_banco/60,10));');
  ShowHTML('   tot_minutos = Math.abs(tot_banco) - (tot_horas*60);');
  ShowHTML('   if (tot_horas<10) {');
  ShowHTML('     trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   } else {');
  ShowHTML('     trabalhadas = tot_horas + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   }');
  if (Nvl($w_trata_extras, '')!= '' && Nvl($w_trata_extras, '')!= 'N') {
    ShowHTML('   theForm.w_banco.value = sinal + trabalhadas;');
    ShowHTML('   theForm.w_banco1.value = sinal + trabalhadas;');
  } else {
    ShowHTML('   theForm.w_banco.value  = "00:00";');
    ShowHTML('   theForm.w_banco1.value = "00:00";');
  }


  // Controle do banco de horas
  ShowHTML('   tempo = "' . $w_saldo_banco . '";');
  ShowHTML('   if (tempo.substr(0,1)=="-") {');
  ShowHTML('     var array = tempo.substr(1).split(":");');
  ShowHTML('     horas   = parseInt(array[0]*60,10);');
  ShowHTML('     minutos = parseInt(array[1],10);');
  ShowHTML('     sinal = -1;');
  ShowHTML('   } else {');
  ShowHTML('     var array = tempo.split(":");');
  ShowHTML('     horas   = parseInt(array[0]*60,10);');
  ShowHTML('     minutos = parseInt(array[1],10);');
  ShowHTML('     sinal = 1;');
  ShowHTML('   }');
  ShowHTML('   var saldo_ini = sinal * (horas + minutos);');

  ShowHTML('   tempo = "' . $w_saldo_meses . '";');
  ShowHTML('   if (tempo.substr(0,1)=="-") {');
  ShowHTML('     var array = tempo.substr(1).split(":");');
  ShowHTML('     horas   = parseInt(array[0]*60,10);');
  ShowHTML('     minutos = parseInt(array[1],10);');
  ShowHTML('     sinal = -1;');
  ShowHTML('   } else {');
  ShowHTML('     var array = tempo.split(":");');
  ShowHTML('     horas   = parseInt(array[0]*60,10);');
  ShowHTML('     minutos = parseInt(array[1],10);');
  ShowHTML('     sinal = 1;');
  ShowHTML('   }');
  ShowHTML('   var saldo_mes = sinal * (horas + minutos);');

  ShowHTML('   tot_banco = saldo_ini + saldo_mes + tot_banco;');
  ShowHTML('   if (tot_banco<0) sinal = "-"; else sinal = "";');
  ShowHTML('   tot_horas = Math.abs(parseInt(tot_banco/60,10));');
  ShowHTML('   tot_minutos = Math.abs(tot_banco) - (tot_horas*60);');
  ShowHTML('   if (tot_horas<10) {');
  ShowHTML('     trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   } else {');
  ShowHTML('     trabalhadas = tot_horas + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   }');
  ShowHTML('   theForm.w_tot_banco.value = sinal + trabalhadas;');
  ShowHTML('}');

  ValidateOpen('Validacao1');
  Validate('w_mes', 'M�s', 'DATAMA', '1', '7', '7', '', '0123456789/');
  ShowHTML('  var mes = theForm.w_mes.value;');
  ShowHTML('  var comp = parseFloat(mes.substr(3) + mes.substr(0,2));');
  ShowHTML('  if (comp>' . substr($w_mes_atual, 3) . substr($w_mes_atual, 0, 2) . ') {');
  ShowHTML('    alert("M�s n�o pode ser superior ao atual!");');
  ShowHTML('    theForm.w_mes.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  if (comp<' . substr($w_inicio_contrato, 6) . substr($w_inicio_contrato, 3, 2) . ') {');
  ShowHTML('    alert("M�s n�o pode ser anterior ao in�cio da vig�ncia contratual!");');
  ShowHTML('    theForm.w_mes.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ValidateClose();
  ValidateOpen('Validacao');
  ShowHTML('  for (ind=1; ind <= ' . (($w_mes!=date('m/Y', time())) ? $w_dia_fim : $w_dia_atual) . '; ind++) {');
  Validate('["w_entrada1[]"][ind]', 'Entrada Turno 1', 'HORA', '', 5, 5, '', '0123456789:');
  Validate('["w_saida1[]"][ind]', 'Sa�da Turno 1', 'HORA', '', 5, 5, '', '0123456789:');
  CompHora('["w_entrada1[]"][ind]', 'Entrada Turno 1', '<', '["w_saida1[]"][ind]', 'Sa�da Turno 1');
  if (Nvl($w_segundo_turno, '')!='') {
    Validate('["w_entrada2[]"][ind]', 'Entrada Turno 2', 'HORA', '', 5, 5, '', '0123456789:');
    CompHora('["w_entrada2[]"][ind]', 'Entrada Turno 2', '>', '["w_saida1[]"][ind]', 'Sa�da Turno 1');
    Validate('["w_saida2[]"][ind]', 'Sa�da Turno 2', 'HORA', '', 5, 5, '', '0123456789:');
    CompHora('["w_entrada2[]"][ind]', 'Entrada Turno 2', '<', '["w_saida2[]"][ind]', 'Sa�da Turno 2');
    CompHora('["w_entrada1[]"][ind]', 'Entrada Turno 1', '<', '["w_saida1[]"][ind]', 'Sa�da Turno 1');
  }
  CompHora('["w_trabalhadas[]"][ind]', 'Hora extra di�ria', '<=', '["w_limite"]', $w_limite_diario_extras);
  //CompHora('w_entrada_manha','Entrada manh�','<','w_saida_manha','Sa�da manh�');
  ShowHTML('  }');
  Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus();\'');
  } else if ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  } else {
    BodyOpen('onLoad=\'this.focus();\'');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table width="100%" bgcolor="#FAEBD7"><tr><td>');
  ShowHTML('  <table border=1 width="100%"><tr><td>');
  ShowHTML('    <tr><td colspan=2><b><font size="2">' . f($RSContrato, 'nome') . '</font></b><hr noshade size="1"/>');
  ShowHTML('    <tr valign="top">');
  ShowHTML('      <td>Matr�cula: <b>' . f($RSContrato, 'matricula') . '</b>');
  ShowHTML('      <td>Admiss�o: <b>' . formataDataEdicao(f($RSContrato, 'inicio')) . '</b></td>');
  ShowHTML('  </table>');
  ShowHTML('</table>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  AbreForm('Form1', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao1(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<tr valign="top"><td>');
  ShowHTML('  <b><u>M</u>�s:</b><br><input ' . $w_Disabled . ' accesskey="M" type="text" name="w_mes" class="stio" SIZE="7" MAXLENGTH="7" VALUE="' . $w_mes . '"  onKeyDown="FormataDataMA(this,event);">');
  ShowHTML('  <input class="stb" type="submit" value="Ir">');
  // Exibe chamada para aprova��o de folhas de ponto se o usu�rio for chefe de unidade
  if (count($RSUorg))
    ShowHTML('  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="SS" href="' . $conRootSIW . 'mod_rh/folha.php?par=lista&w_mes=' . $w_mes . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=GPFPLISTA">ALTERNAR PARA MODO APROVA��O</a>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');

  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, 'L');
  ShowHTML('<INPUT type="hidden" name="w_contrato" value="' . $w_contrato . '">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_mes" value="' . $w_mes . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_entrada1[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_saida1[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_entrada2[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_saida2[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_trabalhadas[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_autorizadas[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_saldo_dia[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_minutos_diarios[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_limite" value="' . $w_limite . '">');
  ShowHTML('<tr valign="top" align="center"><td>');
  ShowHTML('    <TABLE BORDER="0" CELLSPACING="0" id="folhadeponto" CELLPADDING="5" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
  ShowHTML('          <td rowspan=2 colspan=2>DIA</td>');
  ShowHTML('          <td colspan=2>' . upper($w_primeiro_turno) . '</td>');
  if (Nvl($w_segundo_turno, '')!='') {
    ShowHTML('          <td colspan=2>' . upper($w_segundo_turno) . '</td>');
  }
  ShowHTML('          <td rowspan=2>HORAS<BR>TRABALHADAS</td>');
  ShowHTML('          <td rowspan=2>SALDO<BR>DI�RIO</td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
  ShowHTML('          <td>ENTRADA</td>');
  ShowHTML('          <td>SA�DA</td>');
  if (Nvl($w_segundo_turno, '')!='') {
    ShowHTML('          <td>ENTRADA</td>');
    ShowHTML('          <td>SA�DA</td>');
  }
  ShowHTML('        </tr>');
  for ($i = 1; $i <= $w_dia_fim; $i++) {
    if (nvl(f($w_dias[$i], 'horas_autorizadas'), '')!='') {
      $w_Disabled = ' READONLY ';
      $w_imagem = '<img src="' . $conImgOkNormal . '" border=0 width=10 heigth=10 align="center">';
    } else {
      $w_Disabled = ' ENABLED ';
      $w_imagem = '';
    }
    $w_cor = ($w_cor == '#FFFFFF' ? '#EFEFEF' : '#FFFFFF');
    $w_atual = toDate(substr(100 + $i, 1, 2) . '/' . $w_mes);
    if (date('N', $w_atual)==7) {
      if ($w_domingo == 'S') {
        $w_fim_semana = false;
      } else {
        $w_fim_semana = true;
        $w_cor = '#F3FFF2';
      }
    } elseif (date('N', $w_atual)==6) {
      if ($w_sabado == 'S') {
        $w_fim_semana = false;
      } else {
        $w_fim_semana = true;
        $w_cor = '#F3FFF2';
      }
    } else {
      $w_fim_semana = false;
    }
    if (toDate($w_inicio_contrato) > $w_atual) {
      $w_fim_semana = true;
      $w_contratado = true;
    } else {
      $w_contratado = false;
    }
    if (is_array($w_feriados[$i])) {
      $w_feriado = true;
      If (!$w_contratado) {
        $w_nm_feriado = upper($w_feriados[$i]['nome']);
      } else {
        $w_nm_feriado = '';
      }

      $w_saida[$i] = $w_feriados[$i]['saida'];
      $w_chegada[$i] = $w_feriados[$i]['chegada'];
      $w_tp_feriado = $w_feriados[$i]['tipo'];
      $w_sigla = $w_feriados[$i]['sigla'];
      $w_cor = '#ffffff';

      /* Verifica as faltas
       *
       * FM : falta no per�odo da manh�
       * FT : Falta no per�odo da tarde
       * F  : Falta em todos os per�odos
       *
       */

      if (substr($w_sigla, 0, 1) == 'F') {
        $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
      } else {
        if ($w_tp_feriado == 'M') {
          $w_minutos_diarios[$i] = $w_minutos_primeiro_turno;
        } elseif ($w_tp_feriado == 'T') {
          $w_minutos_diarios[$i] = $w_minutos_segundo_turno;
        } elseif ($w_tp_feriado == 'N') {
          $w_minutos_diarios[$i] = 0;
        } else {
          $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
        }
      }
    } else {
      $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
      $w_feriado = false;
      $w_nm_feriado = '';
      $w_tp_feriado = '';
    }

    ShowHTML('        <tr bgcolor="' . $w_cor . '" align="center" ' . (($w_imagem!='') ? 'title="Hor�rios j� autorizados pelo gestor!"' : '') . '>');
    ShowHTML('          <td width="1%" nowrap>' . (($w_imagem!='') ? $w_imagem . '&nbsp;' : '&nbsp;&nbsp;') . $i . '&nbsp;</td>');
    ShowHTML('          <td align="left"width="1%" nowrap>&nbsp;' . diaSemana(date('l', $w_atual)) . '&nbsp;</td>');
    $w_classe = 'sti';
    //if ($i<$w_dia_atual || $w_mes!=date('m/Y',time())) $w_classe = 'stio';
    if ($w_fim_semana || ( $i > $w_dia_atual && $w_mes==date('m/Y', time()))) {
      if (Nvl($w_segundo_turno, '')!='') {
        ShowHTML('          <td colspan="6"><b>&nbsp;' . $w_nm_feriado . '&nbsp;</b></td>');
      } else {
        ShowHTML('          <td colspan="4"><b>&nbsp;' . $w_nm_feriado . '&nbsp;</b></td>');
      }
      ShowHTML('          <td style="display:none"><input style="display:none;" readonly type="text" name="w_entrada1[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_entrada'] . '">');
      ShowHTML('          <td style="display:none"><input style="display:none;" readonly type="text" name="w_saida1[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_saida'] . '">');
      ShowHTML('          <td style="display:none"><input type="hidden" name="w_minutos_diarios[]" VALUE="' . $w_minutos_diarios[$i] . '">');
      if (Nvl($w_segundo_turno, '')!='') {
        ShowHTML('          <td style="display:none"><input style="display:none;" readonly type="text" name="w_entrada2[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_entrada'] . '">');
        ShowHTML('          <td style="display:none"><input style="display:none;" readonly type="text" name="w_saida2[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_saida'] . '">');
      }
      ShowHTML('          <td style="display:none"><input readonly style="display:none;" type="text" name="w_trabalhadas[]" class="stih" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['horas_trabalhadas'] . '">');
      ShowHTML('          <td style="display:none"><input style="display:none;" readonly type="text" name="w_saldo_dia[]" class="stih" SIZE="5" MAXLENGTH="6" VALUE="' . $w_dias[$i]['saldo_diario'] . '">');
    } elseif ($w_feriado && $w_tp_feriado!='S') {
      if ($w_tp_feriado=='M') {
        ShowHTML('          <td style="display:none;"><input type="hidden" name="w_minutos_diarios[]" VALUE="' . $w_minutos_diarios[$i] . '">');
        ShowHTML('          <td><input ' . $w_Disabled . ' type="text" name="w_entrada1[]" class="' . $w_classe . '" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_entrada'] . '" onKeyDown="FormataHora(this,event);" onBlur="calculaDia(' . $i . ');">');
        ShowHTML('          <td><input ' . $w_Disabled . ' type="text" name="w_saida1[]" class="' . $w_classe . '" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_saida'] . '" onKeyDown="FormataHora(this,event);" onBlur="calculaDia(' . $i . ');">');
        ShowHTML('          <td  style="display:none;"><input style="display:none;" readonly type="text" name="w_entrada2[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_entrada'] . '">');
        ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_saida2[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_saida'] . '">');
        ShowHTML('          <td colspan=2><b>' . $w_nm_feriado . '</b></td>');
        ShowHTML('          <td><input readonly type="text" name="w_trabalhadas[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['horas_trabalhadas'] . '">');
        ShowHTML('          <td><input readonly type="text" name="w_saldo_dia[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="6" VALUE="' . $w_dias[$i]['saldo_diario'] . '">');
      } elseif ($w_tp_feriado=='T') {
        ShowHTML('          <td style="display:none;"><input type="hidden" name="w_minutos_diarios[]" VALUE="' . $w_minutos_diarios[$i] . '">');
        ShowHTML('          <td colspan=2><b>' . $w_nm_feriado . '</b></td>');
        ShowHTML('          <td><input ' . $w_Disabled . ' type="text" name="w_entrada1[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_entrada'] . '" onKeyDown="FormataHora(this,event);" onBlur="calculaDia(' . $i . ');">');
        ShowHTML('          <td><input ' . $w_Disabled . ' type="text" name="w_saida1[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_saida'] . '" onKeyDown="FormataHora(this,event);" onBlur="calculaDia(' . $i . ');">');
        ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_entrada2[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_entrada'] . '">');
        ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_saida2[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_saida'] . '">');
        ShowHTML('          <td><input readonly type="text" name="w_trabalhadas[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['horas_trabalhadas'] . '">');
        ShowHTML('          <td><input readonly type="text" name="w_saldo_dia[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="6" VALUE="' . $w_dias[$i]['saldo_diario'] . '">');
      } else {
        if (Nvl($w_segundo_turno, '')!='') {
          if ($w_sigla != 'F') {
            if (Nvl($w_segundo_turno, '')!='') {
              ShowHTML('          <td colspan="6"><b>&nbsp;' . $w_nm_feriado . '&nbsp;</b></td>');
            } else {
              ShowHTML('          <td colspan="4"><b>&nbsp;' . $w_nm_feriado . '&nbsp;</b></td>');
            }
          } else {
            ShowHTML('          <td colspan="4"><b>' . $w_nm_feriado . '</b></td>');
          }
        } else {
          if (substr($w_sigla, 0, 1) != 'F') {
            ShowHTML('          <td colspan="4"><b>' . $w_nm_feriado . '</b></td>');
          } else {
            ShowHTML('          <td colspan="2"><b>' . $w_nm_feriado . '</b></td>');
          }
        }
        ShowHTML('          <td style="display:none;"><input type="hidden" name="w_minutos_diarios[]" VALUE="' . $w_minutos_diarios[$i] . '">');
        ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_entrada1[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_entrada'] . '">');
        ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_saida1[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_saida'] . '">');
        if (Nvl($w_segundo_turno, '')!='') {
          ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_entrada2[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_entrada'] . '">');
          ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_saida2[]" class="' . $w_classe . '" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_saida'] . '">');
        }
        if (substr($w_sigla, 0, 1) == 'F') {
          ShowHTML('          <td><input readonly type="text" name="w_trabalhadas[]" class="stih" SIZE="5" MAXLENGTH="5" VALUE="' . minutos2horario(0) . '">');
          ShowHTML('          <td><input  readonly type="text" name="w_saldo_dia[]" class="stih" SIZE="5" MAXLENGTH="6" VALUE="' . '-' . ($w_carga_diaria) . '">');
        } else {
          ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_trabalhadas[]" class="stih" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['horas_trabalhadas'] . '">');
          ShowHTML('          <td style="display:none;"><input style="display:none;" readonly type="text" name="w_saldo_dia[]" class="stih" SIZE="5" MAXLENGTH="6" VALUE="' . $w_dias[$i]['saldo_diario'] . '">');
        }
      }
    } else {
      ShowHTML('          <td style="display:none;"><input type="hidden" name="w_minutos_diarios[]" VALUE="' . $w_minutos_diarios[$i] . '">');
      ShowHTML('          <td><input ' . $w_Disabled . ' type="text" name="w_entrada1[]" class="' . $w_classe . '" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_entrada'] . '" onKeyDown="FormataHora(this,event);" onBlur="calculaDia(' . $i . ');">');
      ShowHTML('          <td><input ' . $w_Disabled . ' type="text" name="w_saida1[]" class="' . $w_classe . '" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['primeira_saida'] . '" onKeyDown="FormataHora(this,event);" onBlur="calculaDia(' . $i . ');">');
      if (Nvl($w_segundo_turno, '')!='') {
        ShowHTML('          <td><input ' . $w_Disabled . ' type="text" name="w_entrada2[]" class="' . $w_classe . '" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_entrada'] . '" onKeyDown="FormataHora(this,event);" onBlur="calculaDia(' . $i . ');">');
        ShowHTML('          <td><input ' . $w_Disabled . ' type="text" name="w_saida2[]" class="' . $w_classe . '" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['segunda_saida'] . '" onKeyDown="FormataHora(this,event);" onBlur="calculaDia(' . $i . ');">');
      }
      ShowHTML('          <td><input readonly type="text" name="w_trabalhadas[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="' . $w_dias[$i]['horas_trabalhadas'] . '"  onKeyDown="FormataHora(this,event);">');
      ShowHTML('          <td><input readonly type="text" name="w_saldo_dia[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="6" VALUE="' . $w_dias[$i]['saldo_diario'] . '">');
    }
    ShowHTML('        </tr>');
  }
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('  <td width="35%"><TABLE width="90%" bgcolor="' . $conTableBgColor . '" BORDER="1" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '" valign="top"><td colspan="2"><b>RESUMO DA FOLHA DE PONTO');
  ShowHTML('      <tr><td>Horas Trabalhadas<td align="center"><input readonly type="text" name="w_total" class="stih" style="background-color:' . $conTableBgColor . '; border: 0; text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="' . $w_total . '">');
  ShowHTML('      <tr><td>Extras (HE)<td align="center"><input readonly type="text" name="w_extras" class="stih" style="background-color:' . $conTableBgColor . '; border: 0; text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="' . $w_extras . '">');
  ShowHTML('      <tr><td>Atrasos (HAt)<td align="center"><input readonly type="text" name="w_atrasos" class="stih" style="background-color:' . $conTableBgColor . '; border: 0; text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="' . $w_atrasos . '">');
  ShowHTML('      <tr><td>Saldo (HE-HAt)<td align="center"><input readonly type="text" name="w_banco" class="stih" style="background-color:' . $conTableBgColor . '; border: 0; text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="' . $w_banco . '">');

  ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '" valign="top"><td colspan="2"><b>BANCO DE HORAS');
  ShowHTML('      <tr valign="top"><td>Saldo inicial (1)<td align="center">&nbsp;' . $w_saldo_banco . '&nbsp;');
  ShowHTML('      <tr valign="top"><td>Movimenta��es mensais (2)<td align="center">&nbsp;' . $w_saldo_meses . '&nbsp;');
  ShowHTML('      <tr valign="top"><td>Saldo m�s (3)<td align="center"><input readonly type="text" name="w_banco1" class="stih" style="background-color:' . $conTableBgColor . '; border: 0; text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="' . $w_banco . '">');
  $w_tot_banco += horario2minutos('', $w_banco);
  ShowHTML('      <tr valign="top"><td>Total (1+2+3)<td align="center"><input readonly type="text" name="w_tot_banco" class="stih" style="background-color:' . $conTableBgColor . '; border: 0; font:bold; text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="' . minutos2horario($w_tot_banco) . '">');
  if (nvl(f($RSMensal, 'horas_autorizadas'), '')!='')
    ShowHTML('      <tr valign="top"><td>Horas j� aprovadas<td align="center"><input readonly type="text" name="w_autorizadas" class="stih" style="background-color:' . $conTableBgColor . '; border: 0; text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="' . f($RSMensal, 'horas_autorizadas') . '">');

  ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '" valign="top"><td colspan="2"><b>JORNADA DI�RIA');
  If (nvl($w_entrada_manha, '')!='')
    ShowHTML('      <tr valign="top"><td>Manh�<td align="center">&nbsp;' . $w_entrada_manha . '-' . $w_saida_manha . '&nbsp;');
  If (nvl($w_entrada_tarde, '')!='')
    ShowHTML('      <tr valign="top"><td>Tarde<td align="center">&nbsp;' . $w_entrada_tarde . '-' . $w_saida_tarde . '&nbsp;');
  If (nvl($w_entrada_noite, '')!='')
    ShowHTML('      <tr valign="top"><td>Noite<td align="center">&nbsp;' . $w_entrada_noite . '-' . $w_saida_noite . '&nbsp;');
  ShowHTML('      <tr valign="top"><td>Carga hor�ria<td align="center">&nbsp;' . $w_carga_diaria . '&nbsp;');
  ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '" valign="top"><td colspan="2"><b>TOLER�NCIA');
  ShowHTML('      <tr valign="top"><td colspan="2" align="center">&nbsp;' . f($RS_Parametro, 'minutos_tolerancia') . ' minutos ' . f($RS_Parametro, 'nm_tipo_tolerancia') . '&nbsp;');
  if (count($RS_Viagem) > 0 || count($RS_Afast) > 0) {
    ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '" valign="top"><td colspan="2"><b>OCORR�NCIAS');
  }
  // Exibe as viagens a servi�o do usu�rio logado
  if (count($RS_Viagem) > 0) {
    ShowHTML('              <tr><td colspan="2">');
    ShowHTML('                VIAGENS A SERVI�O</b>');
    ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="' . $conTrBgColor . '">');
    ShowHTML('                  <tr align="center" valign="middle">');
    ShowHTML('                    <td>In�cio</td>');
    ShowHTML('                    <td>T�rmino</td>');
    ShowHTML('                    <td>N�</td>');
    ShowHTML('                    <td>Destinos</td>');
    reset($RS_Viagem);
    $w_cor = $w_cor = $conTrBgColor;
    if (count($RS_Viagem)==0) {
      ShowHTML('                  <tr bgcolor="' . $w_cor . '" valign="top"><td colspan=4 align="center">N�o foram encontrados registros.');
    } else {
      foreach ($RS_Viagem as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('                  <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('                    <td align="center">' . Nvl(date(d . '/' . m . ', ' . H . ':' . i, f($row, 'phpdt_saida')), '-') . '</td>');
        ShowHTML('                    <td align="center">' . Nvl(date(d . '/' . m . ', ' . H . ':' . i, f($row, 'phpdt_chegada')), '-') . '</td>');
        ShowHTML('                    <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row, 'sigla'), f($row, 'inicio'), f($row, 'fim'), f($row, 'inicio_real'), f($row, 'fim_real'), f($row, 'aviso_prox_conc'), f($row, 'aviso'), f($row, 'sg_tramite'), null));
        ShowHTML('                      <A class="HL" HREF="' . substr(f($RSMenu_Viagem, 'link'), 0, strpos(f($RSMenu_Viagem, 'link'), '=')) . '=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . f($RSMenu_Viagem, 'p1') . '&P2=' . f($RSMenu_Viagem, 'p2') . '&P3=' . f($RSMenu_Viagem, 'p3') . '&P4=' . f($RSMenu_Viagem, 'p4') . '&TP=' . $TP . '&SG=' . f($RSMenu_Viagem, 'sigla') . MontaFiltro('GET') . '" title="Exibe as informa��es deste registro." target="viagem">' . f($row, 'codigo_interno') . '&nbsp;</a>');
        ShowHTML('                    <td>' . f($row, 'trechos') . '&nbsp;</td>');
        ShowHTML('                  </tr>');
      }
    }
    ShowHTML('                </table>');
  }
  // Exibe afastamentos do usu�rio logado
  if (count($RS_Afast) > 0) {
    ShowHTML('              <tr><td colspan="2"><br>');
    // Mostra os per�odos de indisponibilidade
    ShowHTML('                AFASTAMENTOS</b>');
    ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="' . $conTrBgColor . '">');
    ShowHTML('                  <tr align="center" valign="top"><td>In�cio<td>T�rmino<td>Dias<td>Tipo');
    reset($RS_Afast);
    $w_cor = $w_cor = $conTrBgColor;
    if (count($RS_Afast)==0) {
      ShowHTML('                  <tr bgcolor="' . $w_cor . '" valign="top"><td colspan=6 align="center">N�o foram encontrados registros.');
    } else {
      foreach ($RS_Afast as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('                <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('                    <td align="center">' . date(d . '/' . m, f($row, 'inicio_data')) . ' (' . f($row, 'nm_inicio_periodo') . ')');
        ShowHTML('                    <td align="center">' . date(d . '/' . m, f($row, 'fim_data')) . ' (' . f($row, 'nm_fim_periodo') . ')');
        ShowHTML('                    <td align="center">' . crlf2br(f($row, 'dias')));
        ShowHTML('                    <td>' . f($row, 'nm_tipo_afastamento'));
      }
    }
    ShowHTML('                </table>');
  }
  If (nvl(f($RSMensal, 'ciencia_gestor'), '')!='') {
    ShowHTML('    </table><br>');
    ShowHTML('    <TABLE  width="90%" bgcolor="' . $conTableBgColor . '" BORDER="1" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '" valign="top"><td colspan="2"><b>CI�NCIA DO GESTOR');
    ShowHTML('      <tr valign="top"><td>Gestor<td>' . ExibePessoa(null, $w_cliente, f($RSMensal, 'ciencia_gestor'), $TP, f($RSMensal, 'nm_resumido_gestor')) . '</td>');
    ShowHTML('      <tr valign="top"><td>Data<td align="center">' . FormataDataEdicao(f($RSMensal, 'php_ciencia_data'), 6) . '</td>');
  }
  ShowHTML('    </table><br>');
  ShowHTML('  </td>');
  ShowHTML('      <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('      <tr><td align="center" colspan=5><hr>');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de aprova��o em lote da folha de ponto
// -------------------------------------------------------------------------
function Lista() {
  extract($GLOBALS);
  $w_chave = $_REQUEST['w_chave'];
  $w_contrato = $_REQUEST['w_contrato'];
  $w_mes = $_REQUEST['w_mes'];

  // Configura vari�veis para montagem do calend�rio
  if (nvl($w_mes, '')=='')
    $w_mes = date('m/Y', time());
  $w_dt_inicio = first_day(toDate('01/' . $w_mes));
  $w_dt_fim = last_day(toDate('01/' . $w_mes));
  $w_dia_fim = date('j', $w_dt_fim);
  $w_mes_atual = date('m/Y', time());
  $w_dia_atual = date('j', time());

  // Recupera folhas de ponto do m�s indicado, das pessoas geridas pelo usu�rio
  $sql = new db_getGPFolhaPontoMensal; $RSMensal = $sql->getInstanceOf($dbms, $w_usuario, substr($w_mes, 3, 4) . substr($w_mes, 0, 2), 'APROVACAO');
  $RSMensal = SortArray($RSMensal, 'nm_unidade', 'asc', 'nm_pessoa', 'asc');
  if (count($w_contrato) > 0) {
    $i = 0;
    foreach ($w_chave as $k => $v) {
      $w_marcado[f($row, 'sq_contrato_colaborador')] = 'N';
      foreach ($RSMensal as $row) {
        if ($w_chave[$i]==f($row, 'sq_contrato_colaborador')) {
          $w_marcado[f($row, 'sq_contrato_colaborador')] = $w_contrato[$w_chave[$i]];
          break;
        }
      }
      $i += 1;
    }
    reset($RSMensal);
  } else {
    foreach ($RSMensal as $row) {
      if (nvl(f($row, 'ciencia_gestor'), '')!='') {
        $w_marcado[f($row, 'sq_contrato_colaborador')] = 'S';
      } else {
        $w_marcado[f($row, 'sq_contrato_colaborador')] = 'N';
      }
    }
    reset($RSMensal);
  }
  Cabecalho();
  head();
  ShowHTML('<TITLE>' . $conSgSistema . ' - Aprova��o de Folha de Ponto</TITLE>');
  Estrutura_CSS($w_cliente);

  ScriptOpen('JavaScript');
  modulo();
  checkbranco();
  FormataDataMA();
  FormataHora();
  SaltaCampo();
  ValidateOpen('Validacao1');
  Validate('w_mes', 'M�s', 'DATAMA', '1', '7', '7', '', '0123456789/');
  ShowHTML('  var mes = theForm.w_mes.value;');
  ShowHTML('  var comp = parseFloat(mes.substr(3) + mes.substr(0,2));');
  ShowHTML('  if (comp>' . substr($w_mes_atual, 3) . substr($w_mes_atual, 0, 2) . ') {');
  ShowHTML('    alert("M�s n�o pode ser superior ao atual!");');
  ShowHTML('    theForm.w_mes.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ValidateClose();
  ValidateOpen('Validacao');
  Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
  ShowHTML('  var i; ');
  ShowHTML('  var ind; ');
  ShowHTML('  var w_erro=true; ');
  ShowHTML('  if (theForm["w_chave[]"].length!=undefined) {');
  ShowHTML('    for (i=0; i < theForm["w_chave[]"].length; i++) {');
  ShowHTML('      ind = theForm["w_chave[]"][i].value;');
  ShowHTML('      alert(ind);');
  ShowHTML('      if (theForm["w_contrato["+ind+"]"][0].checked) {');
  ShowHTML('         w_erro=false; ');
  ShowHTML('      }');
  ShowHTML('    }');
  ShowHTML('  } else if (theForm["w_chave[]"]!=undefined) {');
  ShowHTML('      ind = theForm["w_chave[]"].value;');
  ShowHTML('      if (theForm["w_contrato["+ind+"]"][0].checked) {');
  ShowHTML('         w_erro=false; ');
  ShowHTML('      }');
  ShowHTML('  } else {');
  ShowHTML('    alert("Nenhuma folha a autorizar!");');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  if (w_erro) {');
  ShowHTML('    return confirm("Nenhuma folha autorizada. Confirma?"); ');
  ShowHTML('  }');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus();\'');
  } else if ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  } else {
    BodyOpen('onLoad=\'this.focus();\'');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  AbreForm('Form1', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao1(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
  ShowHTML('<tr valign="top"><td>');
  ShowHTML('  <b><u>M</u>�s:</b><br><input ' . $w_Disabled . ' accesskey="M" type="text" name="w_mes" class="stio" SIZE="7" MAXLENGTH="7" VALUE="' . $w_mes . '"  onKeyDown="FormataDataMA(this,event);">');
  ShowHTML('  <input class="stb" type="submit" value="Ir">');
  ShowHTML('  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="SS" href="' . $conRootSIW . 'mod_rh/folha.php?par=inicial&w_usuario=' . $_SESSION['SQ_PESSOA'] . '&w_mes=' . $w_mes . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=COINICIAL">ALTERNAR PARA MODO FOLHA MENSAL</a>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');

  if (count($RSMensal)) {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, 'L');
    ShowHTML('<INPUT type="hidden" name="w_mes" value="' . $w_mes . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td>&nbsp;</td></tr>');
    ShowHTML('<tr valign="top" align="center"><td><table width="97%" bgcolor="' . $conTrBgColor . '" border="0" cellspacing="3">');
    ShowHTML('  <tr bgcolor="' . $conTrBgColor . '"><td colspan="6"><div align="justify"><b><ul>Instru��es</b>:');
    ShowHTML('  <li>Use esta tela para aprovar folhas de ponto em lote.');
    ShowHTML('  <li>Marque as folhas de ponto que deseja aprovar, informe sua assinatura eletr�nica ao final desta p�gina e clique sobre o bot�o gravar.');
    ShowHTML('  <li>Ser�o aprovados todos os hor�rios do m�s corrente, anteriores � data atual.');
    ShowHTML('  <li>Use a opera��o "Detalhar" para ver os hor�rios da folha de ponto.');
    ShowHTML('  </div></td></tr>');
    $w_atual = '';
    foreach ($RSMensal as $row) {
      if ($w_atual!=f($row, 'sq_unidade')) {
        ShowHTML('      <tr><td colspan="6"><hr NOSHADE color=#000000 size=1></td></tr>');
        ShowHTML('      <tr><td colspan="6" bgcolor="#f0f0f0"><font size="2"><b>' . f($row, 'nm_unidade') . ' (' . f($row, 'sg_unidade') . ')</b></td></tr>');
        ShowHTML('      <tr><td colspan="6"><hr NOSHADE color=#000000 size=1></td></tr>');
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '" align="center">');
        ShowHTML('        <td rowspan=2 width="15%" nowrap><b>Folha aprovada?</b></td>');
        ShowHTML('        <td rowspan=2><b>Nome</b></td>');
        ShowHTML('        <td colspan=2><b>Horas</b></td>');
        ShowHTML('        <td rowspan=2><b>Saldo</b></td>');
        ShowHTML('        <td rowspan=2><b>Opera��es</b></td>');
        ShowHTML('      </tr>');
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '" align="center">');
        ShowHTML('        <td><b>Extras</td>');
        ShowHTML('        <td><b>Atrasos</td>');
        ShowHTML('      </tr>');
        $w_atual = f($row, 'sq_unidade');
      }
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      ShowHTML('      <tr bgcolor="' . $w_cor . '">');
      ShowHTML('        <INPUT type="hidden" name="w_chave[]" value="' . f($row, 'sq_contrato_colaborador') . '">');
      montaRadioNS(null, $w_marcado[f($row, 'sq_contrato_colaborador')], 'w_contrato[' . f($row, 'sq_contrato_colaborador') . ']', null, null, null, '1', '&nbsp;');
      ShowHTML('        <td>' . ExibePessoa(null, $w_cliente, f($row, 'sq_pessoa'), $TP, f($row, 'nm_resumido')) . '</td>');
      ShowHTML('        <td align="center">' . Nvl(f($row, 'horas_extras'), '00:00') . '</td>');
      ShowHTML('        <td align="center">' . Nvl(f($row, 'horas_atrasos'), '00:00') . '</td>');
      ShowHTML('        <td align="center">' . Nvl(f($row, 'horas_banco'), '00:00') . '</td>');
      ShowHTML('        <td>');
      ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&O=V&w_usuario=' . f($row, 'sq_pessoa') . '&w_mes=' . $w_mes . '&w_chave=' . f($row, 'sq_contrato_colaborador') . '&R=' . $w_pagina . $par . '&SG=' . $SG . '&TP=' . $TP . MontaFiltro('GET') . '" target="visualFolha" title="Exibe detalhamento da folha de ponto">Detalhar</a>&nbsp;');
      ShowHTML('        </td>');
      ShowHTML('      </tr>');
    }
    ShowHTML('      <tr><td colspan=6><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=6><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  }
  ShowHTML('      <tr><td><hr></tr>');
  ShowHTML('      <tr><td align="center"><b>Nenhum registro encontrado</b></td></tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de visualiza��o da folha de ponto mensal do colaborador
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  Global $w_minutos_diarios;
  Global $w_primeiro_turno;
  Global $w_segundo_turno;
  Global $w_Disabled;
  $w_Disabled = ' READONLY ';
  $w_chave = $_REQUEST['w_chave'];
  $w_mes = $_REQUEST['w_mes'];
  $w_tipo = $_REQUEST['w_tipo'];

  // Configura vari�veis para montagem do calend�rio
  if (nvl($w_mes, '')=='')
    $w_mes = date('m/Y', time());
  $w_dt_inicio = first_day(toDate('01/' . $w_mes));
  $w_dt_fim = last_day(toDate('01/' . $w_mes));
  $w_dia_fim = date('j', $w_dt_fim);
  $w_mes_atual = date('m/Y', time());
  $w_dia_atual = date('j', time());

  //Recupera os dados do contrato
  if (nvl($w_chave, '')!='') {
    $sql = new db_getGPContrato; $RSContrato = $sql->getInstanceOf($dbms, $w_cliente, $w_chave, $w_usuario, null, null, null, null, null, null, null, formataDataEdicao($w_dt_fim), null, null);
  } else {
    $sql = new db_getGPContrato; $RSContrato = $sql->getInstanceOf($dbms, $w_cliente, null, $w_usuario, null, null, null, null, null, null, null, formataDataEdicao($w_dt_fim), null, null);
    $RSContrato = SortArray($RSContrato, 'fim', 'asc');
  }
  foreach ($RSContrato as $row) { $RSContrato = $row; break;  }
  $w_contrato = f($RSContrato, 'chave');
  $w_inicio_contrato = formataDataEdicao(f($RSContrato, 'inicio'));
  //$w_minutos_diarios      = f($RSContrato,'minutos_diarios');
  $w_carga_diaria = f($RSContrato, 'carga_diaria');
  $w_entrada_manha = f($RSContrato, 'entrada_manha');
  $w_saida_manha = f($RSContrato, 'saida_manha');
  $w_entrada_tarde = f($RSContrato, 'entrada_tarde');
  $w_saida_tarde = f($RSContrato, 'saida_tarde');
  $w_entrada_noite = f($RSContrato, 'entrada_noite');
  $w_saida_noite = f($RSContrato, 'saida_noite');
  $w_sabado = f($RSContrato, 'sabado');
  $w_domingo = f($RSContrato, 'domingo');
  $w_saldo_banco = f($RSContrato, 'banco_horas_saldo');
  $w_saldo_meses = f($RSContrato, 'banco_horas_mensal');
  $w_trata_extras = f($RSContrato, 'trata_extras');
  $w_tot_banco = horario2minutos('', $w_saldo_banco) + horario2minutos('', $w_saldo_meses);
  $w_minutos_tolerancia = f($RS_Parametro, 'minutos_tolerancia');
  $w_tipo_tolerancia = f($RS_Parametro, 'tipo_tolerancia');

  if (Nvl($w_trata_extras, '')!= '' && Nvl($w_trata_extras, '')!= 'N') {
    $w_limite_diario_extras = f($RS_Parametro, 'limite_diario_extras');
  } else {
    $w_limite_diario_extras = '00:00';
  }

  //C�lculo da toler�ncia de horas extras  
  $w_limite = minutos2horario(horario2minutos('', $w_carga_diaria) + $w_minutos_tolerancia + horario2minutos('', $w_limite_diario_extras));


  //Informa��es da folha de ponto di�ria
  $sql = new db_getGPFolhaPontoDiario; $RSFolha = $sql->getInstanceOf($dbms, $w_contrato, substr($w_mes, 3, 4) . substr($w_mes, 0, 2), null);
  foreach ($RSFolha as $row) {
    $w_dias[intVal(substr(formataDataEdicao(f($row, 'data')), 0, 2))] = $row;
  }

  //Informa��es da folha de ponto mensal
  $sql = new db_getGPFolhaPontoMensal; $RSMensal = $sql->getInstanceOf($dbms, $w_contrato, substr($w_mes, 3, 4) . substr($w_mes, 0, 2), null);
  foreach ($RSMensal as $row) {$RSMensal = $row; break;  }
  $w_total = Nvl(f($RSMensal, 'horas_trabalhadas'), '00:00');
  $w_extras = Nvl(f($RSMensal, 'horas_extras'), '00:00');
  $w_atrasos = Nvl(f($RSMensal, 'horas_atrasos'), '00:00');
  $w_banco = Nvl(f($RSMensal, 'horas_banco'), '00:00');

  if (Nvl($w_entrada_manha, '')!='' && Nvl($w_saida_manha, '')!='') {
    $w_primeiro_turno = 'manh�';
    $w_minutos_primeiro_turno = horario2minutos('', $w_saida_manha) - horario2minutos('', $w_entrada_manha);

    if (Nvl($w_entrada_noite, '')!='' && Nvl($w_saida_noite, '')!='') {
      $w_segundo_turno = 'noite';
      $w_minutos_segundo_turno = horario2minutos('', $w_saida_noite) - horario2minutos('', $w_entrada_noite);
    } elseif (Nvl($w_entrada_tarde, '')!='' && Nvl($w_saida_tarde, '')!='') {
      $w_segundo_turno = 'tarde';
      $w_minutos_segundo_turno = horario2minutos('', $w_saida_tarde) - horario2minutos('', $w_entrada_tarde);
    } else {
      $w_segundo_turno = '';
      $w_minutos_segundo_turno = 0;
    }
  } else {
    if (Nvl($w_entrada_tarde, '')!='' && Nvl($w_saida_tarde, '')!='') {
      $w_primeiro_turno = 'tarde';
      $w_minutos_primeiro_turno = horario2minutos('', $w_saida_tarde) - horario2minutos('', $w_entrada_tarde);
      if (Nvl($w_entrada_noite, '')!='' && Nvl($w_saida_noite, '')!='') {
        $w_segundo_turno = 'noite';
        $w_minutos_segundo_turno = horario2minutos('', $w_saida_noite) - horario2minutos('', $w_entrada_noite);
      } else {
        $w_segundo_turno = '';
        $w_minutos_segundo_turno = 0;
      }
    } else {
      $w_primeiro_turno = 'noite';
      $w_minutos_primeiro_turno = horario2minutos('', $w_saida_noite) - horario2minutos('', $w_entrada_noite);
      $w_segundo_turno = '';
      $w_minutos_segundo_turno = 0;
    }
  }

  //Recupera datas especiais do m�s
  include_once($w_dir_volta . 'classes/sp/db_getDataEspecial.php');
  $sql = new db_getDataEspecial; $RS_Ano = $sql->getInstanceOf($dbms, $w_cliente, null, date('Y', $w_dt_inicio), 'S', null, null, null);
  $RS_Ano = SortArray($RS_Ano, 'data_formatada', 'asc');
  foreach ($RS_Ano as $row) {
    if (date('m/Y', f($row, 'data_formatada'))==$w_mes) {
      $w_feriados[date('j', f($row, 'data_formatada'))]['nome'] = f($row, 'nome');
      $w_feriados[date('j', f($row, 'data_formatada'))]['tipo'] = f($row, 'expediente');
    }
  }

  if ($w_mod_pd=='S') {
    $sql = new db_getLinkData; $RSMenu_Viagem = $sql->getInstanceOf($dbms, $w_cliente, 'PDINICIAL');
    $sql = new db_getSolicList; $RS_Viagem = $sql->getInstanceOf($dbms, f($RSMenu_Viagem, 'sq_menu'), $w_usuario, 'PD', 4,
                    formataDataEdicao($w_dt_inicio), formataDataEdicao($w_dt_fim), null, null, null, null, null, null, null, null, null,
                    null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_usuario);
    $RS_Viagem = SortArray($RS_Viagem, 'inicio', 'desc', 'fim', 'desc');

    /* Cria arrays com cada dia do per�odo,
     * definindo o texto e a cor de fundo para
     * exibi��o na folha de ponto
     */

    foreach ($RS_Viagem as $row) {
      $w_ini_viagem = f($row, 'inicio');
      $w_fim_viagem = f($row, 'fim');
      for ($i = $w_ini_viagem; $i<=$w_fim_viagem; $i = addDays($i, 1)) {
        if (date('m/Y', $i)==$w_mes) {
          if ($i==$w_ini_viagem) {
            $w_feriados[date('j', $i)]['nome'] = 'VIAGEM (SA�DA ' . date('H:i', f($row, 'phpdt_saida')) . ')';
            $w_feriados[date('j', $i)]['saida'] = date('H:i', f($row, 'phpdt_saida'));
            if (Nvl($w_segundo_turno, '')!='') {
              if (Nvl($w_segundo_turno, '')=='tarde') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_tarde) {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (Nvl($w_primeiro_turno, '')=='manh�') {
                  if (date('H', f($row, 'phpdt_saida')) > $w_saida_manha)
                    $w_feriados[date('j', $i)]['tipo'] = 'M';
                }
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_tarde) {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (date('H', f($row, 'phpdt_saida')) > $w_saida_manha) {
                  $w_feriados[date('j', $i)]['tipo'] = 'M';
                }
              } elseif (Nvl($w_segundo_turno, '')=='noite') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_noite) {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (Nvl($w_primeiro_turno, '')=='manh�') {
                  if ((date('H', f($row, 'phpdt_saida')) > $w_entrada_noite) || ( date('H', f($row, 'phpdt_saida')) > $w_saida_manha && date('H', f($row, 'phpdt_saida')) < $w_entrada_noite)) {
                    $w_feriados[date('j', $i)]['tipo'] = 'M';
                  } elseif (date('H', f($row, 'phpdt_saida')) < $w_entrada_manha) {
                    $w_feriados[date('j', $i)]['tipo'] = 'N';
                  }
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  if (date('H', f($row, 'phpdt_saida')) > $w_entrada_noite)
                    $w_feriados[date('j', $i)]['tipo'] = 'M';
                }
              }
            }elseif (Nvl($w_primeiro_turno, '')!='' && Nvl($w_segundo_turno, '')=='') {
              if (Nvl($w_primeiro_turno, '')=='manh�') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_manha)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_tarde)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }elseif (Nvl($w_primeiro_turno, '')=='noite') {
                if (date('H', f($row, 'phpdt_saida')) > $w_saida_noite)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }
            }

            //elseif (date('H',f($row,'phpdt_saida'))>13) $w_feriados[date('j',$i)]['tipo'] = 'M';
            else
              $w_feriados[date('j', $i)]['tipo'] = 'N';
          } elseif ($i==$w_fim_viagem) {
            $w_feriados[date('j', $i)]['nome'] = 'VIAGEM (CHEGADA ' . date('H:i', f($row, 'phpdt_chegada')) . ')';
            $w_feriados[date('j', $i)]['chegada'] = date('H:i', f($row, 'phpdt_chegada'));

            if (Nvl($w_segundo_turno, '')!='') {
              if (Nvl($w_segundo_turno, '')=='tarde') {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_manha) {
                    $w_feriados[date('j', $i)]['tipo'] = 'S';
                  } elseif (date('H', f($row, 'phpdt_chegada')) > $w_entrada_manha && date('H', f($row, 'phpdt_chegada')) < $w_entrada_tarde) {
                    $w_feriados[date('j', $i)]['tipo'] = 'T';
                  } elseif (date('H', f($row, 'phpdt_chegada')) > $w_entrada_tarde) {
                    $w_feriados[date('j', $i)]['tipo'] = 'N';
                  } else {
                    $w_feriados[date('j', $i)]['tipo'] = 'M';
                  }
                }
              } elseif (Nvl($w_segundo_turno, '')=='noite') {
                if (date('H', f($row, 'phpdt_chegada')) > $w_saida_noite) {
                  $w_feriados[date('j', $i)]['tipo'] = 'N';
                } elseif (Nvl($w_primeiro_turno, '')=='manh�') {
                  if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_manha) {
                    $w_feriados[date('j', $i)]['tipo'] = 'S';
                  } elseif (date('H', f($row, 'phpdt_chegada')) > $w_entrada_manha && date('H', f($row, 'phpdt_chegada')) < $w_saida_noite) {
                    $w_feriados[date('j', $i)]['tipo'] = 'T';
                  }
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_tarde) {
                    $w_feriados[date('j', $i)]['tipo'] = 'S';
                  } elseif (date('H', f($row, 'phpdt_chegada')) < $w_saida_tarde && date('H', f($row, 'phpdt_chegada')) > $w_entrada_tarde) {
                    $w_feriados[date('j', $i)]['tipo'] = 'T';
                  }
                }
              }
            } elseif (Nvl($w_primeiro_turno, '')!='' && Nvl($w_segundo_turno, '')=='') {
              if (Nvl($w_primeiro_turno, '')=='manh�') {
                if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_manha)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_tarde)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }elseif (Nvl($w_primeiro_turno, '')=='noite') {
                if (date('H', f($row, 'phpdt_chegada')) < $w_entrada_noite)
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
              }
            }
            /* if     (date('H',f($row,'phpdt_chegada'))<18) $w_feriados[date('j',$i)]['tipo'] = 'S';
             * lseif (date('H',f($row,'phpdt_chegada'))<14) $w_feriados[date('j',$i)]['tipo'] = 'T';
             * lse $w_feriados[date('j',$i)]['tipo'] = 'N'; */
          } else {
            $w_feriados[date('j', $i)]['nome'] = 'VIAGEM';
            $w_feriados[date('j', $i)]['tipo'] = 'N';
          }
        }
      }
    }
  }

  //Recupera afastamentos do m�s
  $sql = new db_getAfastamento; $RS_Afast = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, null, null, null, formataDataEdicao($w_dt_inicio), formataDataEdicao($w_dt_fim), null, null, null, null);
  $RS_Afast = SortArray($RS_Afast, 'inicio_data', 'desc', 'inicio_periodo', 'asc', 'fim_data', 'desc', 'inicio_periodo', 'asc');
  foreach ($RS_Afast as $row) {
    $w_ini_afast = f($row, 'inicio_data');
    $w_fim_afast = f($row, 'fim_data');
    for ($i = $w_ini_afast; $i<=$w_fim_afast; $i = addDays($i, 1)) {
      if (date('m/Y', $i)==$w_mes) {
        $w_feriados[date('j', $i)]['nome'] = f($row, 'nm_tipo_afastamento');
        $w_feriados[date('j', $i)]['sigla'] = f($row, 'sigla');
        if ($i==$w_ini_afast) {
          if (substr(f($row, 'sigla'), 0, 1)=='F') {
            if (f($row, 'sigla') == 'FM') {
              if (Nvl($w_segundo_turno, '')!='') {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  $w_feriados[date('j', $i)]['tipo'] = 'T';
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                }
              } else {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  $w_feriados[date('j', $i)]['tipo'] = 'N';
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                }
              }
            } elseif (f($row, 'sigla') == 'FT') {
              if (Nvl($w_segundo_turno, '')!='') {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  $w_feriados[date('j', $i)]['tipo'] = 'M';
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'T';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                }
              } else {
                if (Nvl($w_primeiro_turno, '')=='manh�') {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'N';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                }
              }
            } else {
              $w_feriados[date('j', $i)]['tipo'] = 'N';
            }
          } else {
            if (f($row, 'inicio_periodo')=='M') {
              if (Nvl($w_primeiro_turno, '')=='manh�') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_primeiro_turno, '')=='noite') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              }
            } else {
              if (Nvl($w_primeiro_turno, '')=='manh�') {
                if (Nvl($w_segundo_turno, '')=='') {
                  $w_feriados[date('j', $i)]['tipo'] = 'S';
                } elseif (Nvl($w_segundo_turno, '')=='tarde') {
                  $w_feriados[date('j', $i)]['tipo'] = 'M';
                } else {
                  $w_feriados[date('j', $i)]['tipo'] = 'T';
                }
              } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_primeiro_turno, '')=='noite') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              }
            }
          }

          //else $w_feriados[date('j',$i)]['tipo'] = 'M';
        } elseif ($i==$w_fim_afast) {
          if (f($row, 'fim_periodo')=='T') {
            if (Nvl($w_primeiro_turno, '')=='manh�') {
              if (Nvl($w_segundo_turno, '')=='') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_segundo_turno, '')=='tarde') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } else {
                $w_feriados[date('j', $i)]['tipo'] = 'T';
              }
            } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
              if (Nvl($w_segundo_turno, '')=='') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_segundo_turno, '')=='noite') {
                $w_feriados[date('j', $i)]['tipo'] = 'T';
              }
              //$w_feriados[date('j',$i)]['tipo'] = 'T';
            } elseif (Nvl($w_primeiro_turno, '')=='noite') {
              $w_feriados[date('j', $i)]['tipo'] = 'S';
            }
          } else { // Fim do afastamento � pela manh� (M)
            if (Nvl($w_primeiro_turno, '')=='manh�') {
              if (Nvl($w_segundo_turno, '')=='') {
                $w_feriados[date('j', $i)]['tipo'] = 'N';
              } elseif (Nvl($w_segundo_turno, '')=='tarde') {
                $w_feriados[date('j', $i)]['tipo'] = 'T';
              } else {
                $w_feriados[date('j', $i)]['tipo'] = 'T';
              }
            } elseif (Nvl($w_primeiro_turno, '')=='tarde') {
              if (Nvl($w_segundo_turno, '')=='') {
                $w_feriados[date('j', $i)]['tipo'] = 'S';
              } else {
                $w_feriados[date('j', $i)]['tipo'] = 'S';
              }
            } elseif (Nvl($w_primeiro_turno, '')=='noite') {
              $w_feriados[date('j', $i)]['tipo'] = 'S';
            } elseif (Nvl($w_primeiro_turno, '')=='noite') {
              $w_feriados[date('j', $i)]['tipo'] = 'M';
            }
          }
        } else
          $w_feriados[date('j', $i)]['tipo'] = 'N';
      }
    }
  }

  if ($w_troca > '' && $O!='E') {
    $w_ano = $_REQUEST['w_ano'];
    $w_percentual = $_REQUEST['w_percentual'];
  }

  if ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'], 'PORTRAIT')=='PORTRAIT') ? 45 : 30);
    CabecalhoWord($w_cliente, 'Folha de Ponto', 0);
    $w_embed = 'WORD';
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  }elseif ($w_tipo == 'PDF') {
    $w_linha_pag = ((nvl($_REQUEST['orientacao'], 'PORTRAIT')=='PORTRAIT') ? 60 : 35);
    $w_embed = 'WORD';
    HeaderPdf('Folha de Ponto', $w_pag);
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    cabecalho();
    head();
    ShowHTML('<TITLE>' . $conSgSistema . ' - Folha de Ponto</TITLE>');
    Estrutura_CSS($w_cliente);
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    BodyOpen('onLoad=\'this.focus();\'');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    CabecalhoRelatorio($w_cliente, 'Folha de Ponto', 4);
  }
  ShowHTML('<table width="100%" bgcolor="#FAEBD7"><tr><td>');
  ShowHTML('  <table border=1 width="100%"><tr><td>');
  ShowHTML('    <tr><td colspan=2><b><font size="2">' . f($RSContrato, 'nome') . '</font></b><hr noshade size="1"/>');
  ShowHTML('    <tr valign="top">');
  ShowHTML('      <td>Matr�cula: <b>' . f($RSContrato, 'matricula') . '</b>');
  ShowHTML('      <td>Admiss�o: <b>' . formataDataEdicao(f($RSContrato, 'inicio')) . '</b></td>');
  ShowHTML('  </table>');
  ShowHTML('</table>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  ShowHTML('<tr><td colspan="2" align="center"><br><font size="2"><b>FOLHA DE PONTO M�S ' . $w_mes . '</font></br></br></td></tr>');

  ShowHTML('<tr valign="top" align="center"><td>');
  ShowHTML('    <TABLE ' . (($w_tipo=='PDF') ? 'BORDER="1"' : 'BORDER="0" id="folhadeponto"') . ' CELLSPACING="0" CELLPADDING="5" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
  ShowHTML('          <td rowspan=2 colspan=2>DIA</td>');
  ShowHTML('          <td colspan=2>' . upper($w_primeiro_turno) . '</td>');
  if (Nvl($w_segundo_turno, '')!='') {
    ShowHTML('          <td colspan=2>' . upper($w_segundo_turno) . '</td>');
  }
  ShowHTML('          <td rowspan=2>HORAS<BR>TRABALHADAS</td>');
  ShowHTML('          <td rowspan=2>SALDO<BR>DI�RIO</td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
  ShowHTML('          <td>ENTRADA</td>');
  ShowHTML('          <td>SA�DA</td>');
  if (Nvl($w_segundo_turno, '')!='') {
    ShowHTML('          <td>ENTRADA</td>');
    ShowHTML('          <td>SA�DA</td>');
  }
  ShowHTML('        </tr>');
  for ($i = 1; $i <= $w_dia_fim; $i++) {
    if (nvl(f($w_dias[$i], 'horas_autorizadas'), '')!='') {
      $w_Disabled = ' READONLY ';
      $w_imagem = '<img src="' . $conImgOkNormal . '" border=0 width=10 heigth=10 align="center">';
    } else {
      $w_Disabled = ' ENABLED ';
      $w_imagem = '';
    }
    $w_cor = ($w_cor == '#FFFFFF' ? '#EFEFEF' : '#FFFFFF');
    $w_atual = toDate(substr(100 + $i, 1, 2) . '/' . $w_mes);
    if (date('N', $w_atual)==7) {
      if ($w_domingo == 'S') {
        $w_fim_semana = false;
      } else {
        $w_fim_semana = true;
        $w_cor = '#F3FFF2';
      }
    } elseif (date('N', $w_atual)==6) {
      if ($w_sabado == 'S') {
        $w_fim_semana = false;
      } else {
        $w_fim_semana = true;
        $w_cor = '#F3FFF2';
      }
    } else {
      $w_fim_semana = false;
    }
    if (toDate($w_inicio_contrato) > $w_atual) {
      $w_fim_semana = true;
      $w_contratado = true;
    } else {
      $w_contratado = false;
    }
    if (is_array($w_feriados[$i])) {
      $w_feriado = true;
      If (!$w_contratado) {
        $w_nm_feriado = upper($w_feriados[$i]['nome']);
      } else {
        $w_nm_feriado = '';
      }

      $w_saida[$i] = $w_feriados[$i]['saida'];
      $w_chegada[$i] = $w_feriados[$i]['chegada'];
      $w_tp_feriado = $w_feriados[$i]['tipo'];
      $w_sigla = $w_feriados[$i]['sigla'];
      $w_cor = '#ffffff';

      /* Verifica as faltas
       *
       * FM : falta no per�odo da manh�
       * FT : Falta no per�odo da tarde
       * F  : Falta em todos os per�odos
       *
       */

      if (substr($w_sigla, 0, 1) == 'F') {
        $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
      } else {
        if ($w_tp_feriado == 'M') {
          $w_minutos_diarios[$i] = $w_minutos_primeiro_turno;
        } elseif ($w_tp_feriado == 'T') {
          $w_minutos_diarios[$i] = $w_minutos_segundo_turno;
        } elseif ($w_tp_feriado == 'N') {
          $w_minutos_diarios[$i] = 0;
        } else {
          $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
        }
      }
    } else {
      $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
      $w_feriado = false;
      $w_nm_feriado = '';
      $w_tp_feriado = '';
    }

    ShowHTML('        <tr bgcolor="' . $w_cor . '" align="center" ' . (($w_imagem!='') ? 'title="Hor�rios j� autorizados pelo gestor!"' : '') . '>');
    ShowHTML('          <td width="1%" nowrap>' . (($w_imagem!='') ? $w_imagem . '&nbsp;' : '&nbsp;&nbsp;') . $i . '&nbsp;</td>');
    ShowHTML('          <td align="left" width="1%" nowrap>&nbsp;' . diaSemana(date('l', $w_atual)) . '&nbsp;</td>');
    if ($w_fim_semana || ( $i > $w_dia_atual && $w_mes==date('m/Y', time()))) {
      if (Nvl($w_segundo_turno, '')!='') {
        ShowHTML('          <td colspan="6"><b>&nbsp;' . $w_nm_feriado . '&nbsp;</b></td>');
      } else {
        ShowHTML('          <td colspan="4"><b>&nbsp;' . $w_nm_feriado . '&nbsp;</b></td>');
      }
    } elseif ($w_feriado && $w_tp_feriado!='S') {
      if ($w_tp_feriado=='M') {
        ShowHTML('          <td>' . nvl($w_dias[$i]['primeira_entrada'], '&nbsp;'));
        ShowHTML('          <td>' . nvl($w_dias[$i]['primeira_saida'], '&nbsp;'));
        ShowHTML('          <td colspan=2><b>' . $w_nm_feriado . '</b></td>');
        ShowHTML('          <td>' . nvl($w_dias[$i]['horas_trabalhadas'], '&nbsp;'));
        ShowHTML('          <td>' . nvl($w_dias[$i]['saldo_diario'], '&nbsp;'));
      } elseif ($w_tp_feriado=='T') {
        ShowHTML('          <td colspan=2><b>' . $w_nm_feriado . '</b></td>');
        ShowHTML('          <td>' . nvl($w_dias[$i]['primeira_entrada'], '&nbsp;'));
        ShowHTML('          <td>' . nvl($w_dias[$i]['primeira_saida'], '&nbsp;'));
        ShowHTML('          <td>' . nvl($w_dias[$i]['horas_trabalhadas'], '&nbsp;'));
        ShowHTML('          <td>' . nvl($w_dias[$i]['saldo_diario'], '&nbsp;'));
      } else {
        if (Nvl($w_segundo_turno, '')!='') {
          if ($w_sigla != 'F') {
            if (Nvl($w_segundo_turno, '')!='') {
              ShowHTML('          <td colspan="6"><b>&nbsp;' . $w_nm_feriado . '&nbsp;</b></td>');
            } else {
              ShowHTML('          <td colspan="4"><b>&nbsp;' . $w_nm_feriado . '&nbsp;</b></td>');
            }
          } else {
            ShowHTML('          <td colspan="4"><b>' . $w_nm_feriado . '</b></td>');
          }
        } else {
          if (substr($w_sigla, 0, 1) != 'F') {
            ShowHTML('          <td colspan="4"><b>' . $w_nm_feriado . '</b></td>');
          } else {
            ShowHTML('          <td colspan="2"><b>' . $w_nm_feriado . '</b></td>');
          }
        }
        if (substr($w_sigla, 0, 1) == 'F') {
          ShowHTML('          <td>' . minutos2horario(0));
          ShowHTML('          <td>' . '-' . ($w_carga_diaria));
        }
      }
    } else {
      ShowHTML('          <td>' . nvl($w_dias[$i]['primeira_entrada'], '&nbsp;'));
      ShowHTML('          <td>' . nvl($w_dias[$i]['primeira_saida'], '&nbsp;'));
      if (Nvl($w_segundo_turno, '')!='') {
        ShowHTML('          <td>' . nvl($w_dias[$i]['segunda_entrada'], '&nbsp;'));
        ShowHTML('          <td>' . nvl($w_dias[$i]['segunda_saida'], '&nbsp;'));
      }
      ShowHTML('          <td>' . nvl($w_dias[$i]['horas_trabalhadas'], '&nbsp;'));
      ShowHTML('          <td>' . nvl($w_dias[$i]['saldo_diario'], '&nbsp;'));
    }
    ShowHTML('        </tr>');
  }
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('  <td width="35%"><TABLE  width="90%" bgcolor="' . $conTableBgColor . '" BORDER="1" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '"><td colspan="2"><b>RESUMO DA FOLHA DE PONTO');
  ShowHTML('      <tr><td>Horas Trabalhadas<td align="center">' . $w_total);
  ShowHTML('      <tr><td>Extras (HE)<td align="center">' . $w_extras);
  ShowHTML('      <tr><td>Atrasos (HAt)<td align="center">' . $w_atrasos);
  ShowHTML('      <tr><td>Saldo (HE-HAt)<td align="center">' . $w_banco);

  ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '"><td colspan="2"><b>BANCO DE HORAS');
  ShowHTML('      <tr><td>Saldo inicial (1)<td align="center">&nbsp;' . $w_saldo_banco . '&nbsp;');
  ShowHTML('      <tr><td>Movimenta��es mensais (2)<td align="center">&nbsp;' . $w_saldo_meses . '&nbsp;');
  ShowHTML('      <tr><td>Saldo m�s (3)<td align="center">' . $w_banco);
  $w_tot_banco += horario2minutos('', $w_banco);
  ShowHTML('      <tr><td>Total (1+2+3)<td align="center">' . minutos2horario($w_tot_banco));
  if (nvl(f($RSMensal, 'horas_autorizadas'), '')!='')
    ShowHTML('      <tr valign="top"><td>Horas j� aprovadas<td align="center">' . f($RSMensal, 'horas_autorizadas'));

  ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '"><td colspan="2"><b>JORNADA DI�RIA');
  If (nvl($w_entrada_manha, '')!='')
    ShowHTML('      <tr><td>Manh�<td nowrap align="center">&nbsp;' . $w_entrada_manha . '-' . $w_saida_manha . '&nbsp;');
  If (nvl($w_entrada_tarde, '')!='')
    ShowHTML('      <tr><td>Tarde<td nowrap align="center">&nbsp;' . $w_entrada_tarde . '-' . $w_saida_tarde . '&nbsp;');
  If (nvl($w_entrada_noite, '')!='')
    ShowHTML('      <tr><td>Noite<td nowrap align="center">&nbsp;' . $w_entrada_noite . '-' . $w_saida_noite . '&nbsp;');
  ShowHTML('      <tr><td>Carga hor�ria<td align="center">&nbsp;' . $w_carga_diaria . '&nbsp;');
  ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '"><td colspan="2"><b>TOLER�NCIA');
  ShowHTML('      <tr><td colspan="2" align="center">&nbsp;' . f($RS_Parametro, 'minutos_tolerancia') . ' minutos ' . f($RS_Parametro, 'nm_tipo_tolerancia') . '&nbsp;');
  if (count($RS_Viagem) > 0 || count($RS_Afast) > 0) {
    ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '" valign="top"><td colspan="2"><b>OCORR�NCIAS');
  }
  // Exibe as viagens a servi�o do usu�rio logado
  if (count($RS_Viagem) > 0) {
    ShowHTML('              <tr><td colspan="2">');
    ShowHTML('                VIAGENS A SERVI�O</b>');
    ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="' . $conTrBgColor . '">');
    ShowHTML('                  <tr align="center" valign="middle">');
    ShowHTML('                    <td>In�cio</td>');
    ShowHTML('                    <td>T�rmino</td>');
    ShowHTML('                    <td>N�</td>');
    ShowHTML('                    <td>Destinos</td>');
    reset($RS_Viagem);
    $w_cor = $w_cor = $conTrBgColor;
    if (count($RS_Viagem)==0) {
      ShowHTML('                  <tr bgcolor="' . $w_cor . '" valign="top"><td colspan=4 align="center">N�o foram encontrados registros.');
    } else {
      foreach ($RS_Viagem as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('                  <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('                    <td align="center">' . Nvl(date(d . '/' . m . ', ' . H . ':' . i, f($row, 'phpdt_saida')), '-') . '</td>');
        ShowHTML('                    <td align="center">' . Nvl(date(d . '/' . m . ', ' . H . ':' . i, f($row, 'phpdt_chegada')), '-') . '</td>');
        ShowHTML('                    <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row, 'sigla'), f($row, 'inicio'), f($row, 'fim'), f($row, 'inicio_real'), f($row, 'fim_real'), f($row, 'aviso_prox_conc'), f($row, 'aviso'), f($row, 'sg_tramite'), null));
        if ($w_embed=='HTML') {
          ShowHTML('                      <A class="HL" HREF="' . substr(f($RSMenu_Viagem, 'link'), 0, strpos(f($RSMenu_Viagem, 'link'), '=')) . '=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . f($RSMenu_Viagem, 'p1') . '&P2=' . f($RSMenu_Viagem, 'p2') . '&P3=' . f($RSMenu_Viagem, 'p3') . '&P4=' . f($RSMenu_Viagem, 'p4') . '&TP=' . $TP . '&SG=' . f($RSMenu_Viagem, 'sigla') . MontaFiltro('GET') . '" title="Exibe as informa��es deste registro." target="viagem">' . f($row, 'codigo_interno') . '&nbsp;</a>');
        } else {
          ShowHTML(f($row, 'codigo_interno') . '&nbsp;');
        }
        ShowHTML('                    <td nowrap>' . f($row, 'trechos') . '&nbsp;</td>');
        ShowHTML('                  </tr>');
      }
    }
    ShowHTML('                </table>');
  }
  // Exibe afastamentos do usu�rio logado
  if (count($RS_Afast) > 0) {
    ShowHTML('              <tr><td colspan="2"><br>');
    // Mostra os per�odos de indisponibilidade
    ShowHTML('                AFASTAMENTOS</b>');
    ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="' . $conTrBgColor . '">');
    ShowHTML('                  <tr align="center" valign="top"><td>In�cio<td>T�rmino<td>Dias<td>Tipo');
    reset($RS_Afast);
    $w_cor = $w_cor = $conTrBgColor;
    if (count($RS_Afast)==0) {
      ShowHTML('                  <tr bgcolor="' . $w_cor . '" valign="top"><td colspan=6 align="center">N�o foram encontrados registros.');
    } else {
      foreach ($RS_Afast as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('                <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('                    <td align="center">' . date(d . '/' . m, f($row, 'inicio_data')) . ' (' . f($row, 'nm_inicio_periodo') . ')');
        ShowHTML('                    <td align="center">' . date(d . '/' . m, f($row, 'fim_data')) . ' (' . f($row, 'nm_fim_periodo') . ')');
        ShowHTML('                    <td align="center">' . crlf2br(f($row, 'dias')));
        ShowHTML('                    <td>' . f($row, 'nm_tipo_afastamento'));
      }
    }
    ShowHTML('                </table>');
  }
  If (nvl(f($RSMensal, 'ciencia_gestor'), '')!='') {
    ShowHTML('    </table><br>');
    ShowHTML('    <TABLE  width="90%" bgcolor="' . $conTableBgColor . '" BORDER="1" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('      <tr align="center" bgcolor="' . $conTrAlternateBgColor . '" valign="top"><td colspan="2"><b>CI�NCIA DO GESTOR');
    if ($w_embed=='HTML') {
      ShowHTML('      <tr valign="top"><td>Gestor<td>' . ExibePessoa(null, $w_cliente, f($RSMensal, 'ciencia_gestor'), $TP, f($RSMensal, 'nm_resumido_gestor')) . '</td>');
    } else {
      ShowHTML('      <tr valign="top"><td>Gestor<td>' . f($RSMensal, 'nm_gestor') . '</td>');
    }
    ShowHTML('      <tr valign="top"><td>Data<td align="center">' . FormataDataEdicao(f($RSMensal, 'php_ciencia_data'), 6) . '</td>');
  }
  ShowHTML('  </td>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  if ($w_tipo == 'PDF')
    RodapePdf();
  else
    Rodape();
}

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  BodyOpen('onLoad=this.focus();');
  AbreSessao();
  switch ($SG) {
    case 'COINICIAL':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $w_mes = $_REQUEST['w_mes'];
        $w_mes = substr($w_mes, 3, 4) . substr($w_mes, 0, 2);
        $SQL = new dml_putGpPontoMensal; $SQL->getInstanceOf($dbms, 'E', $_REQUEST['w_contrato'], $w_mes, null, null, null, null, null);
        for ($i = 1; $i < count($_REQUEST['w_trabalhadas']); $i++) {
          if (Nvl($_REQUEST['w_entrada1'][$i], '')!='' || Nvl($_REQUEST['w_entrada2'][$i], '')!='') {
            $w_dia = substr((100 + $i), 1, 2) . '/' . $_REQUEST['w_mes'];
            $SQL = new dml_putGpPontoDiario; $SQL->getInstanceOf($dbms, 'I', $_REQUEST['w_contrato'], $w_dia,
                            $_REQUEST['w_entrada1'][$i], $_REQUEST['w_saida1'][$i],
                            $_REQUEST['w_entrada2'][$i], $_REQUEST['w_saida2'][$i],
                            $_REQUEST['w_trabalhadas'][$i], $_REQUEST['w_saldo_dia'][$i]);
          }
        }
        $SQL = new dml_putGpPontoMensal; $SQL->getInstanceOf($dbms, 'I', $_REQUEST['w_contrato'],
                        $w_mes, Nvl($_REQUEST['w_total'], '00:00'),
                        Nvl($_REQUEST['w_extras'], '00:00'),
                        Nvl($_REQUEST['w_atrasos'], '00:00'),
                        Nvl($_REQUEST['w_banco'], '00:00'),
                        null
        );
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Hor�rio registrado.\');');
        ShowHTML('  window.close();');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'GPFPLISTA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $w_mes = $_REQUEST['w_mes'];
        $w_mes = substr($w_mes, 3, 4) . substr($w_mes, 0, 2);
        for ($i = 0; $i < count($_REQUEST['w_chave']); $i++) {
          if (Nvl($_REQUEST['w_chave'][$i], '')!='') {
            if ($_REQUEST['w_contrato'][$_REQUEST['w_chave'][$i]]=='S') {
              // Aprova a folha de ponto mensal
              $SQL = new dml_putGpPontoMensal; $SQL->getInstanceOf($dbms, 'T', $_REQUEST['w_chave'][$i], $w_mes, null, null, null, null, $w_usuario);
            } else {
              // Remove aprova��o da folha de ponto mensal
              $SQL = new dml_putGpPontoMensal; $SQL->getInstanceOf($dbms, 'D', $_REQUEST['w_chave'][$i], $w_mes, null, null, null, null, $w_usuario);
            }
          }
        }
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Autoriza��es registradas");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados n�o encontrado: ' . $SG . '\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
      break;
  }
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------

function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL': Inicial(); break;
    case 'LISTA': Lista(); break;
    case 'VISUAL': Visual(); break;
    case 'GRAVA': Grava(); break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      break;
  }
}
?>
