<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getAfastamento.php');
include_once($w_dir_volta.'classes/sp/db_getViagemBenef.php');
include_once($w_dir_volta.'classes/sp/db_getGPTipoAfast.php');
include_once($w_dir_volta.'classes/sp/db_getGPColaborador.php');
include_once($w_dir_volta.'classes/sp/db_getGPParametro.php');
include_once($w_dir_volta.'classes/sp/db_getGPContrato.php');
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getGpDesempenho.php');
include_once($w_dir_volta.'classes/sp/db_getCV.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putGPColaborador.php');
include_once($w_dir_volta.'classes/sp/dml_putGPContrato.php');
include_once($w_dir_volta.'classes/sp/dml_putSiwUsuario.php');
include_once($w_dir_volta.'classes/sp/dml_putGpDesempenho.php');
include_once($w_dir_volta.'funcoes/exibeColaborador.php');
include_once($w_dir_volta.'funcoes/selecaoColaborador.php');
include_once($w_dir_volta.'funcoes/selecaoModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCargo.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
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

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); } 

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par            = strtoupper($_REQUEST['par']);
$P1             = Nvl($_REQUEST['P1'],0);
$P2             = Nvl($_REQUEST['P2'],0);
$P3             = Nvl($_REQUEST['P3'],1);
$P4             = Nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = strtoupper($_REQUEST['SG']);
$R              = strtolower($_REQUEST['R']);
$O              = strtoupper($_REQUEST['O']);
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$w_troca        = strtolower($_REQUEST['w_troca']);
$w_assinatura = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'folha.php?par=';
$w_dir          = 'mod_rh/';
$w_dir_volta    = '../';
$w_Disabled     = 'ENABLED';

if ($O=='') $O='P'; 
switch ($O) {
  case 'I':    $w_TP=$TP.' - Inclus�o';     break;
  case 'P':    $w_TP=$TP.' - Filtragem';    break;
  case 'A':    $w_TP=$TP.' - Altera��o';    break;
  case 'E':
    if ($par=='CONTRATO') $w_TP=$TP.' - Encerramento';
    else                  $w_TP=$TP.' - Exclus�o';
    break;
  default:    $w_TP=$TP.' - Listagem';        break;
} 
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu    = RetornaMenu($w_cliente,$SG);

// Verifica se o cliente tem o m�dulo de viagens
$RS = db_getSiwCliModLis::getInstanceOf($dbms, $w_cliente, null, 'PD');
$w_mod_pd = 'N';
foreach ($RS as $row) $w_mod_pd = 'S';

// Recupera os par�metros do m�dulo de pessoal
$RS_Parametro = db_getGPParametro::getInstanceOf($dbms,$w_cliente,null,null);
foreach ($RS_Parametro as $row) {$RS_Parametro = $row; break;}

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina dos dados de desempenho do colaborador
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  Global $w_minutos_diarios;
  Global $w_primeiro_turno;
  Global $w_segundo_turno;
  $w_chave             = $_REQUEST['w_chave'];
  $w_mes               = $_REQUEST['w_mes'];

  // Configura vari�veis para montagem do calend�rio
  if (nvl($w_mes,'')=='') $w_mes = date('m/Y',time());
  $w_dt_inicio  = first_day(toDate('01/'.$w_mes));
  $w_dt_fim     = last_day(toDate('01/'.$w_mes));
  $w_dia_fim    = date('j',$w_dt_fim);
  $w_mes_atual  = date('m/Y',time());
  $w_dia_atual  = date('j',time());
    
  //Recupera os dados do contrato
  if (nvl($w_chave,'')!='') {
    $RSContrato = db_getGPContrato::getInstanceOf($dbms,$w_cliente,$w_chave,$w_usuario,null,null,null,null,null,null,null,null,null,null);
  } else {
    $RSContrato = db_getGPContrato::getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,null,null);
    $RSContrato = SortArray($RSContrato,'fim','asc');
  }
  foreach($RSContrato as $row) { $RSContrato = $row; break; }
  //print_r($RSContrato);
  $w_inicio_contrato      = formataDataEdicao(f($RSContrato,'inicio'));
  //$w_minutos_diarios      = f($RSContrato,'minutos_diarios');
  $w_carga_diaria         = f($RSContrato,'carga_diaria');
  $w_entrada_manha        = f($RSContrato,'entrada_manha');
  $w_saida_manha          = f($RSContrato,'saida_manha');
  $w_entrada_tarde        = f($RSContrato,'entrada_tarde');
  $w_saida_tarde          = f($RSContrato,'saida_tarde');  
  $w_entrada_noite        = f($RSContrato,'entrada_noite');
  $w_saida_noite          = f($RSContrato,'saida_noite');
  $w_sabado               = f($RSContrato,'sabado');    
  $w_domingo              = f($RSContrato,'domingo');
  $w_minutos_tolerancia   = f($RS_Parametro,'minutos_tolerancia');
  $w_tipo_tolerancia      = f($RS_Parametro,'tipo_tolerancia');
  
  $w_trata_extras         = f($RS_Parametro,'trata_extras');
  if(Nvl($w_trata_extras,'')!= '' && Nvl($w_trata_extras,'')!= 'N'){
    $w_limite_diario_extras = f($RS_Parametro,'limite_diario_extras'); 
  }else{
    $w_limite_diario_extras = '00:00';
  }
  $w_limite = minutos2horario(horario2minutos('',$w_carga_diaria) + horario2minutos('',$w_limite_diario_extras)); 
  
  if(Nvl($w_entrada_manha,'')!='' && Nvl($w_saida_manha,'')!='' ){
    $w_primeiro_turno         = 'manha';
    $w_minutos_primeiro_turno = horario2minutos('',$w_saida_manha) - horario2minutos('',$w_entrada_manha);
      
    if(Nvl($w_entrada_noite,'')!='' && Nvl($w_saida_noite,'')!='' ){
      $w_segundo_turno = 'noite';
      $w_minutos_segundo_turno = horario2minutos('',$w_saida_noite) - horario2minutos('',$w_entrada_noite);
    }elseif(Nvl($w_entrada_tarde,'')!='' && Nvl($w_saida_tarde,'')!='' ){
      $w_segundo_turno = 'tarde';
      $w_minutos_segundo_turno = horario2minutos('',$w_saida_tarde) - horario2minutos('',$w_entrada_tarde);
    }else{
      $w_segundo_turno = '';
      $w_minutos_segundo_turno = 0;
    }  
  }else{
    if(Nvl($w_entrada_tarde,'')!='' && Nvl($w_saida_tarde,'')!='' ){
      $w_primeiro_turno = 'tarde';
          $w_minutos_primeiro_turno = horario2minutos('',$w_saida_tarde) - horario2minutos('',$w_entrada_tarde);
      if(Nvl($w_entrada_noite,'')!='' && Nvl($w_saida_noite,'')!='' ){
        $w_segundo_turno = 'noite';
        $w_minutos_segundo_turno = horario2minutos('',$w_saida_noite) - horario2minutos('',$w_entrada_noite);
      }else{
        $w_segundo_turno = '';
        $w_minutos_segundo_turno = 0;
      }
    }else{
      $w_primeiro_turno = 'noite';
      $w_minutos_primeiro_turno = horario2minutos('',$w_saida_noite) - horario2minutos('',$w_entrada_noite);
      $w_segundo_turno = '';
      $w_minutos_segundo_turno = 0;
    }    
  }
  //$w_minutos_diarios = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
  //Recupera datas especiais do m�s
  include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
  $RS_Ano = db_getDataEspecial::getInstanceOf($dbms,$w_cliente,null,date('Y',$w_dt_inicio),'S',null,null,null);
  $RS_Ano = SortArray($RS_Ano,'data_formatada','asc');
  foreach($RS_Ano as $row) {
    if (date('m/Y',f($row,'data_formatada'))==$w_mes) {
      $w_feriados[date('j',f($row,'data_formatada'))]['nome'] = f($row,'nome');  
      $w_feriados[date('j',f($row,'data_formatada'))]['tipo'] = f($row,'expediente');  
    }
  }
  
  if ($w_mod_pd=='S') {
    $RSMenu_Viagem = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PDINICIAL');
    $RS_Viagem = db_getSolicList::getInstanceOf($dbms,f($RSMenu_Viagem,'sq_menu'),$w_usuario,'PD',4,
        formataDataEdicao($w_dt_inicio),formataDataEdicao($w_dt_fim),null,null,null,null,null,null,null,null,null,
        null, null, null, null, null, null, null,null, null, null, null, null, null, null, $w_usuario);
    $RS_Viagem = SortArray($RS_Viagem,'inicio', 'desc', 'fim', 'desc');

    /* Cria arrays com cada dia do per�odo, 
     * definindo o texto e a cor de fundo para 
     * exibi��o na folha de ponto
     */
    /*foreach($RS_Viagem as $row) {
      $w_ini_viagem = f($row,'inicio');
      $w_fim_viagem = f($row,'fim');
      for ($i=$w_ini_viagem; $i<=$w_fim_viagem; $i=addDays($i,1)) {
        if (date('m/Y',$i)==$w_mes) {
          if ($i==$w_ini_viagem) {
            $w_feriados[date('j',$i)]['nome']  = 'VIAGEM (SA�DA '.date('H:i',f($row,'phpdt_saida')).')';
            $w_feriados[date('j',$i)]['saida'] = date('H:i',f($row,'phpdt_saida'));
            
            if (date('H',f($row,'phpdt_saida'))>18) $w_feriados[date('j',$i)]['tipo'] = 'S';
            elseif (date('H',f($row,'phpdt_saida'))>13) $w_feriados[date('j',$i)]['tipo'] = 'M';
            else $w_feriados[date('j',$i)]['tipo'] = 'N';
            
          } elseif ($i==$w_fim_viagem) {
            $w_feriados[date('j',$i)]['nome'] = 'VIAGEM (CHEGADA '.date('H:i',f($row,'phpdt_chegada')).')';
            $w_feriados[date('j',$i)]['chegada'] = date('H:i',f($row,'phpdt_chegada'));            
            if     (date('H',f($row,'phpdt_chegada'))<18) $w_feriados[date('j',$i)]['tipo'] = 'S';
            elseif (date('H',f($row,'phpdt_chegada'))<14) $w_feriados[date('j',$i)]['tipo'] = 'T';
            else $w_feriados[date('j',$i)]['tipo'] = 'N';
          } else {
            $w_feriados[date('j',$i)]['nome'] = 'VIAGEM';
            $w_feriados[date('j',$i)]['tipo'] = 'N';
          }
        }
      }
    }*/
    foreach($RS_Viagem as $row) {
      $w_ini_viagem = f($row,'inicio');
      $w_fim_viagem = f($row,'fim');
      for ($i=$w_ini_viagem; $i<=$w_fim_viagem; $i=addDays($i,1)) {
        if (date('m/Y',$i)==$w_mes) {
          if ($i==$w_ini_viagem) {
            $w_feriados[date('j',$i)]['nome']  = 'VIAGEM (SA�DA '.date('H:i',f($row,'phpdt_saida')).')';
            $w_feriados[date('j',$i)]['saida'] = date('H:i',f($row,'phpdt_saida'));
            if(Nvl($w_segundo_turno,'')!=''){
              if(Nvl($w_segundo_turno,'')=='tarde'){
                if (date('H',f($row,'phpdt_saida'))>$w_saida_tarde){
                  $w_feriados[date('j',$i)]['tipo'] = 'S';
                }elseif(Nvl($w_primeiro_turno,'')=='manha'){
                  if (date('H',f($row,'phpdt_saida'))>$w_saida_manha) $w_feriados[date('j',$i)]['tipo'] = 'M';
                }
                if (date('H',f($row,'phpdt_saida'))>$w_saida_tarde){
                  $w_feriados[date('j',$i)]['tipo'] = 'S';
                }elseif (date('H',f($row,'phpdt_saida'))>$w_saida_manha){
                  $w_feriados[date('j',$i)]['tipo'] = 'M';
                }              
              }elseif(Nvl($w_segundo_turno,'')=='noite'){
                if (date('H',f($row,'phpdt_saida'))>$w_saida_noite){
                  $w_feriados[date('j',$i)]['tipo'] = 'S';
                }elseif(Nvl($w_primeiro_turno,'')=='manha'){
                  if ((date('H',f($row,'phpdt_saida'))>$w_entrada_noite) || (date('H',f($row,'phpdt_saida'))>$w_saida_manha && date('H',f($row,'phpdt_saida'))<$w_entrada_noite)){
                    $w_feriados[date('j',$i)]['tipo'] = 'M';
                  }elseif (date('H',f($row,'phpdt_saida'))<$w_entrada_manha){
                    $w_feriados[date('j',$i)]['tipo'] = 'N';
                  }
                }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
                  if (date('H',f($row,'phpdt_saida'))>$w_entrada_noite) $w_feriados[date('j',$i)]['tipo'] = 'M';
                }
              }
            }elseif(Nvl($w_primeiro_turno,'')!='' && Nvl($w_segundo_turno,'')==''){
              if(Nvl($w_primeiro_turno,'')=='manha'){
                if (date('H',f($row,'phpdt_saida'))>$w_saida_manha) $w_feriados[date('j',$i)]['tipo'] = 'S';
              }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
                if (date('H',f($row,'phpdt_saida'))>$w_saida_tarde) $w_feriados[date('j',$i)]['tipo'] = 'S';
              }elseif(Nvl($w_primeiro_turno,'')=='noite'){
                if (date('H',f($row,'phpdt_saida'))>$w_saida_noite) $w_feriados[date('j',$i)]['tipo'] = 'S';
              }
            }
            
            //elseif (date('H',f($row,'phpdt_saida'))>13) $w_feriados[date('j',$i)]['tipo'] = 'M';
            else $w_feriados[date('j',$i)]['tipo'] = 'N';
            
          } elseif ($i==$w_fim_viagem) {
            $w_feriados[date('j',$i)]['nome'] = 'VIAGEM (CHEGADA '.date('H:i',f($row,'phpdt_chegada')).')';
            $w_feriados[date('j',$i)]['chegada'] = date('H:i',f($row,'phpdt_chegada'));

            if(Nvl($w_segundo_turno,'')!=''){
              if(Nvl($w_segundo_turno,'')=='tarde'){
                if(Nvl($w_primeiro_turno,'')=='manha'){
                  if (date('H',f($row,'phpdt_chegada'))<$w_entrada_manha){
                    $w_feriados[date('j',$i)]['tipo'] = 'S';
                  }elseif (date('H',f($row,'phpdt_chegada'))>$w_entrada_manha && date('H',f($row,'phpdt_chegada'))<$w_entrada_tarde ){
                    $w_feriados[date('j',$i)]['tipo'] = 'T';
                  }elseif (date('H',f($row,'phpdt_chegada'))>$w_entrada_tarde ){
                    $w_feriados[date('j',$i)]['tipo'] = 'N';
                  }else{
                    $w_feriados[date('j',$i)]['tipo'] = 'M';
                  }
                }
              }elseif(Nvl($w_segundo_turno,'')=='noite'){
                if (date('H',f($row,'phpdt_chegada'))>$w_saida_noite){
                  $w_feriados[date('j',$i)]['tipo'] = 'N';
                }elseif(Nvl($w_primeiro_turno,'')=='manha'){
                  if (date('H',f($row,'phpdt_chegada'))<$w_entrada_manha){
                    $w_feriados[date('j',$i)]['tipo'] = 'S';
                  }elseif(date('H',f($row,'phpdt_chegada'))>$w_entrada_manha && date('H',f($row,'phpdt_chegada'))<$w_saida_noite){
                    $w_feriados[date('j',$i)]['tipo'] = 'T';
                  }
                }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
                  if (date('H',f($row,'phpdt_chegada'))<$w_entrada_tarde){
                    $w_feriados[date('j',$i)]['tipo'] = 'S';
                  }elseif (date('H',f($row,'phpdt_chegada'))<$w_saida_tarde && date('H',f($row,'phpdt_chegada'))>$w_entrada_tarde){
                    $w_feriados[date('j',$i)]['tipo'] = 'T';
                  }
                }
              }
            }elseif(Nvl($w_primeiro_turno,'')!='' && Nvl($w_segundo_turno,'')==''){
              if(Nvl($w_primeiro_turno,'')=='manha'){
                if (date('H',f($row,'phpdt_chegada'))<$w_entrada_manha) $w_feriados[date('j',$i)]['tipo'] = 'S';
              }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
                if (date('H',f($row,'phpdt_chegada'))<$w_entrada_tarde) $w_feriados[date('j',$i)]['tipo'] = 'S';
              }elseif(Nvl($w_primeiro_turno,'')=='noite'){
                if (date('H',f($row,'phpdt_chegada'))<$w_entrada_noite) $w_feriados[date('j',$i)]['tipo'] = 'S';
              }
            }            
            /*if     (date('H',f($row,'phpdt_chegada'))<18) $w_feriados[date('j',$i)]['tipo'] = 'S';
            elseif (date('H',f($row,'phpdt_chegada'))<14) $w_feriados[date('j',$i)]['tipo'] = 'T';
            else $w_feriados[date('j',$i)]['tipo'] = 'N';*/
          } else {
            $w_feriados[date('j',$i)]['nome'] = 'VIAGEM';
            $w_feriados[date('j',$i)]['tipo'] = 'N';
          }
        }
      }
    }    
  }

  //Recupera afastamentos do m�s
  $RS_Afast = db_getAfastamento::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,formataDataEdicao($w_dt_inicio),formataDataEdicao($w_dt_fim),null,null,null,null);
  $RS_Afast = SortArray($RS_Afast,'inicio_data','desc','inicio_periodo','asc','fim_data','desc','inicio_periodo','asc');
  foreach($RS_Afast as $row) {
    $w_ini_afast = f($row,'inicio_data');
    $w_fim_afast = f($row,'fim_data');
    for ($i=$w_ini_afast; $i<=$w_fim_afast; $i=addDays($i,1)) {
    
      /*if (date('m/Y',$i)==$w_mes) {
        $w_feriados[date('j',$i)]['nome'] = f($row,'nm_tipo_afastamento');
        if($i==$w_ini_afast) {
          if (f($row,'inicio_periodo')=='M') $w_feriados[date('j',$i)]['tipo'] = 'N';
          else $w_feriados[date('j',$i)]['tipo'] = 'M';
        } elseif ($i==$w_fim_afast) {
          if (f($row,'fim_periodo')=='T') $w_feriados[date('j',$i)]['tipo'] = 'N';
          else $w_feriados[date('j',$i)]['tipo'] = 'M';
        } else   $w_feriados[date('j',$i)]['tipo'] = 'N';
      }*/
      if (date('m/Y',$i)==$w_mes) {
        $w_feriados[date('j',$i)]['nome'] = f($row,'nm_tipo_afastamento');
        $w_feriados[date('j',$i)]['sigla'] = f($row,'sigla');
        //print_r($row);
        if($i==$w_ini_afast) {
        if(substr(f($row,'sigla'),0,1)=='F'){
          if(f($row,'sigla') == 'FM'){
            if(Nvl($w_segundo_turno,'')!=''){
              if(Nvl($w_primeiro_turno,'')=='manha'){
                $w_feriados[date('j',$i)]['tipo'] = 'T';  
              }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }else{
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }          
            }else{
              if(Nvl($w_primeiro_turno,'')=='manha'){
                $w_feriados[date('j',$i)]['tipo'] = 'N';  
              }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }else{
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }          
            }          
          }elseif(f($row,'sigla') == 'FT'){
            if(Nvl($w_segundo_turno,'')!=''){
              if(Nvl($w_primeiro_turno,'')=='manha'){
                $w_feriados[date('j',$i)]['tipo'] = 'M';  
              }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
                $w_feriados[date('j',$i)]['tipo'] = 'T';
              }else{
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }          
            }else{
              if(Nvl($w_primeiro_turno,'')=='manha'){
                $w_feriados[date('j',$i)]['tipo'] = 'S';  
              }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
                $w_feriados[date('j',$i)]['tipo'] = 'N';
              }else{
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }          
            }          
          }else{
            $w_feriados[date('j',$i)]['tipo'] = 'N';
          }        
        }else{
          if (f($row,'inicio_periodo')=='M'){
            if(Nvl($w_primeiro_turno,'')=='manha'){
              $w_feriados[date('j',$i)]['tipo'] = 'N';
            }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
              $w_feriados[date('j',$i)]['tipo'] = 'N';
            }elseif(Nvl($w_primeiro_turno,'')=='noite'){
              $w_feriados[date('j',$i)]['tipo'] = 'N';
            }
          }else{
            if(Nvl($w_primeiro_turno,'')=='manha'){
              if(Nvl($w_segundo_turno,'')==''){
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }elseif(Nvl($w_segundo_turno,'')=='tarde'){
                $w_feriados[date('j',$i)]['tipo'] = 'M';
              }else{
                $w_feriados[date('j',$i)]['tipo'] = 'T';
              }             
            }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
              $w_feriados[date('j',$i)]['tipo'] = 'N';
            }elseif(Nvl($w_primeiro_turno,'')=='noite'){
              $w_feriados[date('j',$i)]['tipo'] = 'N';
            }          
          }
        }

          //else $w_feriados[date('j',$i)]['tipo'] = 'M';
        }elseif($i==$w_fim_afast) {
        //var_dump(f($row,'fim_periodo')=='T');
          if (f($row,'fim_periodo')=='T'){
            if(Nvl($w_primeiro_turno,'')=='manha'){
              if(Nvl($w_segundo_turno,'')==''){
                $w_feriados[date('j',$i)]['tipo'] = 'N';
              }elseif(Nvl($w_segundo_turno,'')=='tarde'){
                $w_feriados[date('j',$i)]['tipo'] = 'N';
              }else{
                $w_feriados[date('j',$i)]['tipo'] = 'T';
              }            
            }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
              if(Nvl($w_segundo_turno,'')==''){
                $w_feriados[date('j',$i)]['tipo'] = 'N';
              }elseif(Nvl($w_segundo_turno,'')=='noite'){
                $w_feriados[date('j',$i)]['tipo'] = 'T';
              }
              //$w_feriados[date('j',$i)]['tipo'] = 'T';
            }elseif(Nvl($w_primeiro_turno,'')=='noite'){
              $w_feriados[date('j',$i)]['tipo'] = 'S';
            }          
          }else{ // Fim do afastamento � pela manh� (M)
            if(Nvl($w_primeiro_turno,'')=='manha'){
              if(Nvl($w_segundo_turno,'')==''){
                $w_feriados[date('j',$i)]['tipo'] = 'N';
              }elseif(Nvl($w_segundo_turno,'')=='tarde'){
                $w_feriados[date('j',$i)]['tipo'] = 'T';
              }else{
                $w_feriados[date('j',$i)]['tipo'] = 'T';
              }
            }elseif(Nvl($w_primeiro_turno,'')=='tarde'){
              if(Nvl($w_segundo_turno,'')==''){
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }else{
                $w_feriados[date('j',$i)]['tipo'] = 'S';
              }           
            }elseif(Nvl($w_primeiro_turno,'')=='noite'){
              $w_feriados[date('j',$i)]['tipo'] = 'S';
            }elseif(Nvl($w_primeiro_turno,'')=='noite'){
              $w_feriados[date('j',$i)]['tipo'] = 'M';
            }          
          }//$w_feriados[date('j',$i)]['tipo'] = 'N';
          //else $w_feriados[date('j',$i)]['tipo'] = 'M';
        } else   $w_feriados[date('j',$i)]['tipo'] = 'N';
      }    
    }
  }

  if ($w_troca> '' && $O!='E') {
    $w_ano                 = $_REQUEST['w_ano'];
    $w_percentual          = $_REQUEST['w_percentual'];  
  } elseif ($O=='L') {
    //$RS = db_getGpFolha::getInstanceOf($dbms, $w_chave,$w_mes);
    //$RS = SortArray($RS,'data','asc');
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Folha de Ponto</TITLE>');
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
  if(Nvl($w_segundo_turno,'')!=''){
    ShowHTML('  var entrada2        = document.Form["w_entrada2[]"][dia].value;');
    ShowHTML('  var saida2          = document.Form["w_saida2[]"][dia].value;');  
  }else{
    ShowHTML('  var entrada2 = "00:00"');
    ShowHTML('  var saida2   = "00:00"');
  }  
  ShowHTML('  var saldo1 = 0;');
  ShowHTML('  var saldo2 = 0;');
  ShowHTML('  var saldo3 = 0;');
  ShowHTML('  var saldo = "00:00";');
  ShowHTML('  var minutos_tolerancia = '.$w_minutos_tolerancia.';');
  ShowHTML('  var tipo_tolerancia    = '.$w_tipo_tolerancia.';');
  ShowHTML('  var fator              = 0;');
  ShowHTML('  if (entrada1!="" && saida1!="") {');
  ShowHTML('    var minutos1 = parseInt(entrada1.substring(0,2)*60,10) + parseInt(entrada1.substring(3),10)');
  ShowHTML('    var minutos2 = parseInt(saida1.substring(0,2)*60,10) + parseInt(saida1.substring(3),10)');
  ShowHTML('    var saldo1 = minutos2 - minutos1;');
  ShowHTML('  }');
  ShowHTML('  if (entrada2!="" && saida2!="") {');
  ShowHTML('    var minutos3 = parseInt(entrada2.substring(0,2)*60,10) + parseInt(entrada2.substring(3),10)');
  ShowHTML('    var minutos4 = parseInt(saida2.substring(0,2)*60,10) + parseInt(saida2.substring(3),10)');
  ShowHTML('    var saldo2 = minutos4 - minutos3;');
  ShowHTML('  }');
  ShowHTML('  if (saldo1!="" && saldo2!="") saldo3 = saldo1 + saldo2;');
  ShowHTML('  else if (saldo1!="" && saldo2=="") saldo3 = saldo1;');
  ShowHTML('  else if (saldo1=="" && saldo2!="") saldo3 = saldo2;');
  ShowHTML('  var saldo4 = parseInt(saldo3-(minutos_diarios),10);');
  ShowHTML('  if (saldo4!=""){');
  ShowHTML('    if(minutos_diarios > 0 && minutos_diarios > saldo3){');
  ShowHTML('      var diferenca = minutos_diarios - saldo3');
  ShowHTML('      if(diferenca > 0 && diferenca <= minutos_tolerancia){');
  ShowHTML('        saldo4 = saldo4 + diferenca');
  ShowHTML('      }else if(diferenca > minutos_tolerancia){');
  ShowHTML('        saldo4 = saldo4 + minutos_tolerancia;');
  ShowHTML('      }');  
  ShowHTML('    }else if(minutos_diarios < saldo3){');
  ShowHTML('      var diferenca = minutos_diarios - saldo3');
  ShowHTML('      if(diferenca < 0 && diferenca >= -minutos_tolerancia){');
  ShowHTML('        saldo4 = saldo4 + diferenca');
  ShowHTML('      }else if(diferenca < minutos_tolerancia){');
  ShowHTML('        saldo4 = saldo4 - minutos_tolerancia;');
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
  ShowHTML('    calculaMes();');
  ShowHTML('  }');
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
  ShowHTML('   trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   theForm.w_total.value = trabalhadas;');
  
  ShowHTML('   tot_horas = Math.abs(parseInt(tot_atraso/60,10));');
  ShowHTML('   tot_minutos = Math.abs(tot_atraso) - (tot_horas*60);');
  ShowHTML('   trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   theForm.w_atrasos.value = trabalhadas;');

  ShowHTML('   tot_horas = parseInt(tot_extra/60,10);');
  ShowHTML('   tot_minutos = tot_extra - (tot_horas*60);');
  ShowHTML('   trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   theForm.w_extras.value = trabalhadas;');

  ShowHTML('   tot_banco = tot_banco - tot_atraso + tot_extra;');
  ShowHTML('   if (tot_banco<0) sinal = "-";');
  ShowHTML('   tot_horas = Math.abs(parseInt(tot_banco/60,10));');
  ShowHTML('   tot_minutos = Math.abs(tot_banco) - (tot_horas*60);');
  ShowHTML('   trabalhadas = String(100+tot_horas).substring(1) + ":" + String(100+tot_minutos).substring(1);');
  ShowHTML('   theForm.w_banco.value = sinal + trabalhadas;');
  ShowHTML('}');
  
  ValidateOpen('Validacao1');
  Validate('w_mes','M�s','DATAMA','1','7','7','','0123456789/');
  ShowHTML('  var mes = theForm.w_mes.value;');
  ShowHTML('  var comp = parseFloat(mes.substr(3) + mes.substr(0,2));');
  ShowHTML('  if (comp>'.substr($w_mes_atual,3).substr($w_mes_atual,0,2).') {');
  ShowHTML('    alert("M�s n�o pode ser superior ao atual!");');
  ShowHTML('    theForm.w_mes.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  if (comp<'.substr($w_inicio_contrato,6).substr($w_inicio_contrato,3,2).') {');
  ShowHTML('    alert("M�s n�o pode ser anterior ao in�cio da vig�ncia contratual!");');
  ShowHTML('    theForm.w_mes.focus();');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ValidateClose();
  ValidateOpen('Validacao');
  ShowHTML('  for (ind=1; ind <= '.(($w_mes!=date('m/Y',time())) ? $w_dia_fim : $w_dia_atual).'; ind++) {');
  Validate('["w_entrada1[]"][ind]','Entrada Turno 1','HORA','',5,5,'','0123456789:');
  Validate('["w_saida1[]"][ind]','Sa�da Turno 1','HORA','',5,5,'','0123456789:');
  CompHora('["w_entrada1[]"][ind]','Entrada Turno 1','<','["w_saida1[]"][ind]','Sa�da Turno 1');
  if(Nvl($w_segundo_turno,'')!=''){
    Validate('["w_entrada2[]"][ind]','Entrada Turno 2','HORA','',5,5,'','0123456789:');
    CompHora('["w_entrada2[]"][ind]','Entrada Turno 2','>','["w_saida1[]"][ind]','Sa�da Turno 1');
    Validate('["w_saida2[]"][ind]','Sa�da Turno 2','HORA','',5,5,'','0123456789:');
    CompHora('["w_entrada2[]"][ind]','Entrada Turno 2','<','["w_saida2[]"][ind]','Sa�da Turno 2');
    CompHora('["w_entrada1[]"][ind]','Entrada Turno 1','<','["w_saida1[]"][ind]','Sa�da Turno 1');
  }
  CompHora('["w_trabalhadas[]"][ind]','Hora extra di�ria','<=','["w_limite"]',$w_limite_diario_extras);
  //CompHora('w_entrada_manha','Entrada manh�','<','w_saida_manha','Sa�da manh�');
  ShowHTML('  }');
  Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } else if ($O=='E'){
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  } else {
    BodyOpen('onLoad=\'this.focus();\'');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table width="100%" bgcolor="FAEBD7">');
  ShowHTML('  <tr>');
  ShowHTML('    <td>');
  ShowHTML('<table border=1 width="100%">');
  ShowHTML('<table border=1 width="100%"><td><table width="100%">');
  ShowHTML('      <tr><td colspan=2><b><font size="2">'.f($RSContrato,'nome').'</font></b><hr noshade size="1"/>');
  ShowHTML('      <tr valign="top"><td>Matr�cula: <b>'.f($RSContrato,'matricula').'</b>');
  ShowHTML('      <td>Admiss�o: <b>'.formataDataEdicao(f($RSContrato,'inicio')).'</b></td>');
  ShowHTML('</table>');  
  ShowHTML('</table>');
  ShowHTML('    </td>');  
  ShowHTML('  </tr>');  
  ShowHTML('</table>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    AbreForm('Form1',$w_dir.$w_pagina.$par,'POST','return(Validacao1(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<tr valign="top"><td>');
    ShowHTML('  <b><u>M</u>�s:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_mes" class="stio" SIZE="7" MAXLENGTH="7" VALUE="'.$w_mes.'"  onKeyDown="FormataDataMA(this,event);">');
    ShowHTML('  <input class="stb" type="submit" value="Ir">');
    ShowHTML('</td></tr>');
    ShowHTML('</FORM>');

    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_mes" value="'.$w_mes.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_entrada1[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_saida1[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_entrada2[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_saida2[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_trabalhadas[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_autorizadas[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_saldo_dia[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_minutos_diarios[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_limite" value="'.$w_limite.'">');
    ShowHTML('<tr valign="top" align="center"><td>');
    ShowHTML('    <TABLE BORDER="0" CELLSPACING="0" id="folhadeponto" CELLPADDING="5" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2 colspan=2><b>DIA</td>');
    ShowHTML('          <td colspan=2><b>TURNO 1</td>');
    if(Nvl($w_segundo_turno,'')!=''){
      ShowHTML('          <td colspan=2><b>TURNO 2</td>');
    }
    ShowHTML('          <td rowspan=2><b>Horas Trabalhadas</td>');
    ShowHTML('          <td rowspan=2><b>Saldo Di�rio</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>ENTRADA</td>');
    ShowHTML('          <td><b>SA�DA</td>');
    if(Nvl($w_segundo_turno,'')!=''){
      ShowHTML('          <td><b>ENTRADA</td>');
      ShowHTML('          <td><b>SA�DA</td>');
    }
    ShowHTML('        </tr>');
    for ($i=1; $i <= $w_dia_fim; $i++) {
      $w_cor = ($w_cor == '#FFFFFF'?'#EFEFEF':'#FFFFFF'); 
      $w_atual = toDate(substr(100+$i,1,2).'/'.$w_mes);
      if (date('N',$w_atual)==7) {
        if($w_domingo == 'S'){
          $w_fim_semana = false;
        } else { 
          $w_fim_semana = true;
          $w_cor = '#F3FFF2'; 
        }          
      }elseif (date('N',$w_atual)==6) {
        if($w_sabado == 'S'){
          $w_fim_semana = false;
        } else { 
          $w_fim_semana = true;
          $w_cor = '#F3FFF2'; 
        }          
      } else { 
        $w_fim_semana = false; 
      }
      if(is_array($w_feriados[$i])) {
        $w_feriado = true;
        $w_nm_feriado  = strtoupper($w_feriados[$i]['nome']);
        $w_saida[$i]   = $w_feriados[$i]['saida'];
        $w_chegada[$i] = $w_feriados[$i]['chegada'];
        $w_tp_feriado  = $w_feriados[$i]['tipo'];
        $w_sigla       = $w_feriados[$i]['sigla'];
        $w_cor = '#ffffff';
        
        /*Verifica as faltas
         * 
         * FM : falta no per�odo da manh�
         * FT : Falta no per�odo da tarde
         * F  : Falta em todos os per�odos
         * 
         */
        
        if(substr($w_sigla,0,1) == 'F'){
          $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
        }else{
          if($w_tp_feriado == 'M'){
            $w_minutos_diarios[$i] = $w_minutos_primeiro_turno;
          }elseif($w_tp_feriado == 'T'){
            $w_minutos_diarios[$i] = $w_minutos_segundo_turno;
          }elseif($w_tp_feriado == 'N'){
            $w_minutos_diarios[$i] = 0;
          }else{
            $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
          }        
        }
      } else {
        $w_minutos_diarios[$i] = $w_minutos_primeiro_turno + $w_minutos_segundo_turno;
        //echo $i.' & '.$w_minutos_diarios[$i].'<br>';
        $w_feriado = false;
        $w_nm_feriado = '';
        $w_tp_feriado = '';
      }
      //echo $w_tp_feriado;
      ShowHTML('        <tr bgcolor="'.$w_cor.'" align="center">');
      ShowHTML('          <td width="1%" nowrap>&nbsp;'.$i.'&nbsp;</td>');
      ShowHTML('          <td align="left"width="1%" nowrap>&nbsp;'.diaSemana(date('l',$w_atual)).'&nbsp;</td>');
      $w_classe = 'sti';
      //if ($i<$w_dia_atual || $w_mes!=date('m/Y',time())) $w_classe = 'stio';
      if ($w_fim_semana || ($i > $w_dia_atual && $w_mes==date('m/Y',time()))) {
        ShowHTML('          <td colspan="4"><b>&nbsp;'.$w_nm_feriado.'&nbsp;</b></td>');
        if(Nvl($w_segundo_turno,'')!=''){
          ShowHTML('          <td>&nbsp;</td>');
          ShowHTML('          <td>&nbsp;</td>');
        }
        ShowHTML('          <input style="display:none;" readonly type="text" name="w_entrada1[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada1[$i].'">');
        ShowHTML('          <input style="display:none;" readonly type="text" name="w_saida1[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida1[$i].'">');
        ShowHTML('          <input type="hidden" name="w_minutos_diarios[]" VALUE="'.$w_minutos_diarios[$i].'">');
        if(Nvl($w_segundo_turno,'')!=''){
          ShowHTML('          <input style="display:none;" readonly type="text" name="w_entrada2[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada2[$i].'">');
          ShowHTML('          <input style="display:none;" readonly type="text" name="w_saida2[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida2[$i].'">');
        }
        ShowHTML('          <input style="display:none;" readonly type="text" name="w_trabalhadas[]" class="stih" SIZE="5" MAXLENGTH="5" VALUE="'.$w_trabalhadas[$i].'">');
        ShowHTML('          <input style="display:none;" readonly type="text" name="w_saldo_dia[]" class="stih" SIZE="5" MAXLENGTH="6" VALUE="'.$w_saldo_dia[$i].'">');
      } elseif ($w_feriado && $w_tp_feriado!='S') {
        if ($w_tp_feriado=='M') {
          ShowHTML('          <input type="hidden" name="w_minutos_diarios[]" VALUE="'.$w_minutos_diarios[$i].'">');
          ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_entrada1[]" class="'.$w_classe.'" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada1[$i].'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia('.$i.');">');
          ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_saida1[]" class="'.$w_classe.'" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida1[$i].'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia('.$i.');">');
          ShowHTML('          <input style="display:none;" readonly type="text" name="w_entrada2[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada2[$i].'">');
          ShowHTML('          <input style="display:none;" readonly type="text" name="w_saida2[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida2[$i].'">');          
          ShowHTML('          <td colspan=2><b>'.$w_nm_feriado.'</b></td>');
          ShowHTML('          <td><input readonly type="text" name="w_trabalhadas[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_trabalhadas[$i].'">');
          ShowHTML('          <td><input readonly type="text" name="w_saldo_dia[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="6" VALUE="'.$w_saldo_dia[$i].'">');
        } elseif ($w_tp_feriado=='T') {
          ShowHTML('          <input type="hidden" name="w_minutos_diarios[]" VALUE="'.$w_minutos_diarios[$i].'">');
          ShowHTML('          <td colspan=2><b>'.$w_nm_feriado.'</b></td>');
          ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_entrada1[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada1[$i].'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia('.$i.');">');
          ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_saida1[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida1[$i].'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia('.$i.');">');
          ShowHTML('          <input style="display:none;" readonly type="text" name="w_entrada2[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada2[$i].'">');
          ShowHTML('          <input style="display:none;" readonly type="text" name="w_saida2[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida2[$i].'">');          
          ShowHTML('          <td><input readonly type="text" name="w_trabalhadas[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_trabalhadas[$i].'">');
          ShowHTML('          <td><input readonly type="text" name="w_saldo_dia[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="6" VALUE="'.$w_saldo_dia[$i].'">');
          
        } else {
          if(Nvl($w_segundo_turno,'')!=''){
            if($w_sigla != 'F'){
              ShowHTML('          <td colspan="4"><b>'.$w_nm_feriado.'</b></td>');
              ShowHTML('          <td>&nbsp;</td>');
              ShowHTML('          <td>&nbsp;</td>');
            }else{
              ShowHTML('          <td colspan="2"><b>'.$w_nm_feriado.'</b></td>');
            }
          }else{
            if(substr($w_sigla,0,1) != 'F'){
              ShowHTML('          <td colspan="4"><b>'.$w_nm_feriado.'</b></td>');
            }else{
              ShowHTML('          <td colspan="2"><b>'.$w_nm_feriado.'</b></td>');
            }            
          }
          ShowHTML('          <input type="hidden" name="w_minutos_diarios[]" VALUE="'.$w_minutos_diarios[$i].'">');
          ShowHTML('          <input style="display:none;" readonly type="text" name="w_entrada1[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada1[$i].'">');
          ShowHTML('          <input style="display:none;" readonly type="text" name="w_saida1[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida1[$i].'">');
          if(Nvl($w_segundo_turno,'')!=''){
            ShowHTML('          <input style="display:none;" readonly type="text" name="w_entrada2[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada2[$i].'">');
            ShowHTML('          <input style="display:none;" readonly type="text" name="w_saida2[]" class="'.$w_classe.'" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida2[$i].'">');
          }
          if(substr($w_sigla,0,1) == 'F'){
            ShowHTML('          <td><input readonly type="text" name="w_trabalhadas[]" class="stih" SIZE="5" MAXLENGTH="5" VALUE="'.minutos2horario(0).'">');
            ShowHTML('          <td><input  readonly type="text" name="w_saldo_dia[]" class="stih" SIZE="5" MAXLENGTH="6" VALUE="'.'-'.($w_carga_diaria).'">');          
          }else{
            ShowHTML('          <input style="display:none;" readonly type="text" name="w_trabalhadas[]" class="stih" SIZE="5" MAXLENGTH="5" VALUE="'.$w_trabalhadas[$i].'">');
            ShowHTML('          <input style="display:none;" readonly type="text" name="w_saldo_dia[]" class="stih" SIZE="5" MAXLENGTH="6" VALUE="'.$w_saldo_dia[$i].'">');
          }
          
        }
      } else {
        ShowHTML('          <input type="hidden" name="w_minutos_diarios[]" VALUE="'.$w_minutos_diarios[$i].'">');
        ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_entrada1[]" class="'.$w_classe.'" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada1[$i].'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia('.$i.');">');
        ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_saida1[]" class="'.$w_classe.'" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida1[$i].'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia('.$i.');">');
        if(Nvl($w_segundo_turno,'')!=''){
          ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_entrada2[]" class="'.$w_classe.'" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_entrada2[$i].'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia('.$i.');">');
          ShowHTML('          <td><input '.$w_Disabled.' type="text" name="w_saida2[]" class="'.$w_classe.'" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saida2[$i].'" onKeyDown="FormataHora(this,event);" onBlur="calculaDia('.$i.');">');
        }
        ShowHTML('          <td><input readonly type="text" name="w_trabalhadas[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="5" VALUE="'.$w_trabalhadas[$i].'"  onKeyDown="FormataHora(this,event);">');
        ShowHTML('          <td><input readonly type="text" name="w_saldo_dia[]" class="stih" style="text-align:center;" SIZE="5" MAXLENGTH="6" VALUE="'.$w_saldo_dia[$i].'">');
      }
      ShowHTML('        </tr>');
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('  <td><TABLE bgcolor="'.$conTableBgColor.'" BORDER="1" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('      <tr align="center" bgcolor="'.$conTrAlternateBgColor.'" valign="top"><td colspan="2"><b>RESUMO DA FOLHA DE PONTO');
    ShowHTML('      <tr valign="top"><td>Total de Horas Trabalhadas:<td align="center"><input readonly type="text" name="w_total" class="stih" style="text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="">');
    ShowHTML('      <tr valign="top"><td>Total de Horas Extras (H.E.):<td align="center"><input readonly type="text" name="w_extras" class="stih" style="text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="">');
    ShowHTML('      <tr valign="top"><td>Total de Atrasos (H.At.):<td align="center"><input readonly type="text" name="w_atrasos" class="stih" style="text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="">');
    ShowHTML('      <tr valign="top"><td>Saldo de Banco de horas do m�s<br>(H.E. - H.At.):<td align="center"><input readonly type="text" name="w_banco" class="stih" style="text-align:center;" SIZE="12" MAXLENGTH="12" VALUE="">');
    ShowHTML('      <tr align="center" bgcolor="'.$conTrAlternateBgColor.'" valign="top"><td colspan="2"><b>JORNADA DI�RIA');    
    If(nvl($w_entrada_manha,'')!='') ShowHTML('      <tr valign="top"><td>Manh�:<td align="center">&nbsp;'.$w_entrada_manha.'-'.$w_saida_manha.'&nbsp;');
    If(nvl($w_entrada_tarde,'')!='') ShowHTML('      <tr valign="top"><td>Tarde:<td align="center">&nbsp;'.$w_entrada_tarde.'-'.$w_saida_tarde.'&nbsp;');
    If(nvl($w_entrada_noite,'')!='') ShowHTML('      <tr valign="top"><td>Noite:<td align="center">&nbsp;'.$w_entrada_noite.'-'.$w_saida_noite.'&nbsp;');
    ShowHTML('      <tr valign="top"><td>Carga hor�ria:<td align="center">&nbsp;'.$w_carga_diaria.'&nbsp;');
    ShowHTML('      <tr align="center" bgcolor="'.$conTrAlternateBgColor.'" valign="top"><td colspan="2"><b>TOLER�NCIA');
    ShowHTML('      <tr valign="top"><td colspan="2" align="center">&nbsp;'.f($RS_Parametro,'minutos_tolerancia').' minutos '.f($RS_Parametro,'nm_tipo_tolerancia').'&nbsp;');
    ShowHTML('      <tr align="center" bgcolor="'.$conTrAlternateBgColor.'" valign="top"><td colspan="2"><b>OCORR�NCIAS');
    // Exibe as viagens a servi�o do usu�rio logado
    if (count($RS_Viagem)>0) {
      ShowHTML('              <tr><td colspan="2">');
      ShowHTML('                VIAGENS A SERVI�O</b>');
      ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
      ShowHTML('                  <tr align="center" valign="middle">');
      ShowHTML('                    <td>In�cio</td>');
      ShowHTML('                    <td>T�rmino</td>');
      ShowHTML('                    <td>N�</td>');
      ShowHTML('                    <td>Destinos</td>');
      reset($RS_Viagem);
      $w_cor = $w_cor=$conTrBgColor;
      if (count($RS_Viagem)==0) {
        ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=4 align="center">N�o foram encontrados registros.');
      } else {
        foreach($RS_Viagem as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_saida')),'-').'</td>');
          ShowHTML('                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_chegada')),'-').'</td>');
          ShowHTML('                    <td nowrap>');
          ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
          ShowHTML('                      <A class="HL" HREF="'.substr(f($RSMenu_Viagem,'link'),0,strpos(f($RSMenu_Viagem,'link'),'=')).'=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.f($RSMenu_Viagem,'p1').'&P2='.f($RSMenu_Viagem,'p2').'&P3='.f($RSMenu_Viagem,'p3').'&P4='.f($RSMenu_Viagem,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Viagem,'sigla').MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
          ShowHTML('                    <td nowrap>'.f($row,'trechos').'&nbsp;</td>');
          ShowHTML('                  </tr>');
        }
      }
      ShowHTML('                </table>');
    }
    // Exibe afastamentos do usu�rio logado
    if (count($RS_Afast)>0) {
      ShowHTML('              <tr><td colspan="2"><br>');
      // Mostra os per�odos de indisponibilidade
      ShowHTML('                AFASTAMENTOS</b>');
      ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
      ShowHTML('                  <tr align="center" valign="top"><td>In�cio<td>T�rmino<td>Dias<td>Tipo');
      reset($RS_Afast);
      $w_cor = $w_cor=$conTrBgColor;
      if (count($RS_Afast)==0) {
        ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=6 align="center">N�o foram encontrados registros.');
      } else {
        foreach($RS_Afast as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('                <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('                    <td align="center">'.date(d.'/'.m,f($row,'inicio_data')).' ('.f($row,'nm_inicio_periodo').')');
          ShowHTML('                    <td align="center">'.date(d.'/'.m,f($row,'fim_data')).' ('.f($row,'nm_fim_periodo').')');
          ShowHTML('                    <td align="center">'.crlf2br(f($row,'dias')));
          ShowHTML('                    <td>'.f($row,'nm_tipo_afastamento'));
        }
      }
      ShowHTML('                </table>');
    }    
    ShowHTML('    </table><br>');    
    ShowHTML('  </td>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('IAV',$O)===false)) {
    if (!(strpos('V',$O)===false)) {
      $w_Disabled =' DISABLED ';
    } elseif (!(strpos('IA',$O)===false)) {
      $w_ativo = 0;
      $RS = db_getGPContrato::getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,$w_chave,null);
      if (count($RS)>0) {
        foreach ($RS as $row) {
          if ((Nvl(f($row,'fim'),'')=='') && ($w_chave!=f($row,'chave'))) $w_ativo+=1;
        } 
      } 
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CODES',$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    //ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0>');
    ShowHTML('          <tr>');
    if($O=='I'){
      ShowHTML('<td valign="top"><b><u>A</u>no:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_ano" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ano.'"></td>');
    }elseif($O=='A'){
      ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');      
    }    
    ShowHTML('              <td><b><u>P</u>ercentual de desempenho:</b><br><input accesskey="P" type="text" name="w_percentual" class="STI" SIZE="6" MAXLENGTH="6" onKeyDown="FormataValor(this,18,2,event);" VALUE="'.$w_percentual.'">');
    ShowHTML('        </table></td></tr>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    if ($O=='I') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
    } else {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_usuario='.$w_usuario.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('E',$O)===false)) {
    $w_Disabled =' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'CODES',$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center"><div align="justify">Para efetivar a exclus�o do percentual de desempenho, forne�a a assinatura eletr�nica e clique no bot�o <i>Excluir</i>.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>P</u>ercentual de desempenho:</b><br><input accesskey="P" '.$w_Disabled.' type="text" name="w_percentual" class="STI" SIZE="3" MAXLENGTH="3" VALUE="'.$w_percentual.'" ></td></tr>');
        ShowHTML('      <tr valign="top"><td><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('          <input class="stb" type="submit" name="Botao" value="Excluir">');
    ShowHTML('          <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_usuario='.$w_usuario.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('        </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
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
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  //print_r($_REQUEST);
  for($i=1;$i<count($_REQUEST['w_trabalhadas']);$i++){
    if(Nvl($_REQUEST['w_trabalhadas'][$i],'')!=''){
      $minutos[$i] = horario2minutos('',$_REQUEST['w_trabalhadas'][$i]);
      //$teste = array_sum($minutos);
    }
  }
  echo minutos2horario(array_sum($minutos));
  //exit();
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');  
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  AbreSessao();
  switch ($SG) {
    case 'COINICIAL':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putGPColaborador::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_sq_pessoa'],null,null,null,
            null,null,null,null,null,null,null,null,null,null,null,null,null);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
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
  switch ($par)  {
    case 'INICIAL':           Inicial();          break;
    case 'GRAVA':             Grava();            break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
  break;
  } 
} 
?>