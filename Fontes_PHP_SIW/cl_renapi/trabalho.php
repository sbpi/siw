<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';

include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop_Recurso.php');
include_once($w_dir_volta.'classes/sp/db_exec.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop.php');
include_once($w_dir_volta.'classes/sp/db_getAlerta.php');
include_once($w_dir_volta.'classes/sp/db_getUserList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicResultado.php');
include_once($w_dir_volta.'classes/sp/db_getStateList.php');
include_once($w_dir_volta.'classes/sp/db_getCountryList.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoPrograma.php');
include_once($w_dir_volta.'funcoes/selecaoTipoEventoCheck.php');
include_once($w_dir_volta.'funcoes/selecaoMes.php');
include_once($w_dir_volta.'funcoes/selecaoTipoArquivoTab.php');
include_once($w_dir_volta.'visualalerta.php');
// =========================================================================
//  trabalho.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia a atualização das tabelas do sistema
// Mail     : alex@sbpi.com.br
// Criacao  : 24/03/2003 16:55
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
//
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = C   : Cancelamento
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);
$browser = browser_info();
// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = $_REQUEST['P3'];
$P4         = $_REQUEST['P4'];
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);

$p_agenda      = $_REQUEST['p_agenda'];
$p_programa    = $_REQUEST['p_programa'];
$p_projeto     = $_REQUEST['p_projeto'];
$p_unidade     = $_REQUEST['p_unidade'];
$p_projeto     = $_REQUEST['p_projeto'];
$p_texto       = $_REQUEST['p_texto'];
$p_tipo_evento = explodeArray($_REQUEST['p_tipo_evento']);
$p_ordena     = $_REQUEST['p_ordena'];
$p_descricao   = $_REQUEST['p_descricao'];
$p_situacao    = $_REQUEST['p_situacao'];

$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'trabalho.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'cl_renapi/';

$w_cliente      = RetornaCliente();
$w_usuario      = RetornaUsuario();
$w_troca        = $_REQUEST['w_troca'];
$w_mesano      = $_REQUEST['w_mesano'];
$w_ano          = RetornaAno();

// Configura variáveis para montagem do calendário
if (nvl($w_mes,'')=='') $w_mes = date('m',time());

if (nvl($w_mesano,'')=='') {
  $w_mesano = $w_mes.'/'.$w_ano;
} else {
  $w_mes = substr($w_mesano,0,2);
  $w_ano = substr($w_mesano,3);
}

$w_mes1    = substr(100+intVal($w_mes),1,2);
$w_mes2    = substr(100+intVal($w_mes)+1,1,2);
$w_mes3    = substr(100+intVal($w_mes)+2,1,2);
$w_mes4    = substr(100+intVal($w_mes)+3,1,2);
$w_mes5    = substr(100+intVal($w_mes)+4,1,2);
$w_mes6    = substr(100+intVal($w_mes)+5,1,2);
$w_ano1    = $w_ano;
$w_ano2    = $w_ano;
$w_ano3    = $w_ano;
$w_ano4    = $w_ano;
$w_ano5    = $w_ano;
$w_ano6    = $w_ano;
// Ajusta a mudança de ano
if ($w_mes2 > 12)  { $w_mes2 = '01'; $w_mes3 = '02'; $w_mes4 = '03'; $w_mes5 = '04'; $w_mes6 = '05'; $w_ano2 = $w_ano+1; $w_ano3 = $w_ano2; $w_ano4 = $w_ano2; $w_ano5 = $w_ano2; $w_ano6 = $w_ano2;}
if ($w_mes3 > 12)  { $w_mes3 = '01'; $w_mes4 = '02'; $w_mes5 = '03'; $w_mes6 = '04'; $w_ano3 = $w_ano + 1; $w_ano4 = $w_ano3; $w_ano5 = $w_ano3; $w_ano6 = $w_ano3;}
if ($w_mes4 > 12)  { $w_mes4 = '01'; $w_mes5 = '02'; $w_mes6 = '03'; $w_ano4 = $w_ano + 1; $w_ano5 = $w_ano4; $w_ano6 = $w_ano4;}
if ($w_mes5 > 12)  { $w_mes5 = '01'; $w_mes6 = '02'; $w_ano5 = $w_ano + 1; $w_ano6 = $w_ano5; }
if ($w_mes6 > 12)  { $w_mes6 = '01'; $w_ano6 = $w_ano + 1; }

$w_inicio  = first_day(toDate('01/'.substr(100+(intVal($w_mes1)),1,2).'/'.$w_ano));
$w_fim     = last_day(toDate('01/'.substr(100+(intVal($w_mes6)),1,2).'/'.$w_ano6));

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão'; break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão'; break;
  case 'V': $w_TP=$TP.' - Envio'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Define visualizações disponíveis para o usuário
$RS_Usuario = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null);
  
// Identifica se o vínculo do usuário é com a a Secretaria executiva
if (strtoupper(f($RS_Usuario,'nome_vinculo'))=='SECRETARIA EXECUTIVA') {
  $w_usuario_se = true;
} else {
  $w_usuario_se = false;
}

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Controle da mesa de trabalho
// -------------------------------------------------------------------------
function Mesa() {
  extract($GLOBALS);

  // Recupera os dados do cliente
  $RS_Cliente = db_getCustomerData::getInstanceOf($dbms,$w_cliente);

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('  <link rel="stylesheet" type="text/css" href="cl_renapi/nucleos.css">');
  ShowHTML('<style>');
  ShowHTML('#menu_superior{);');
  ShowHTML('   float:right;');
  ShowHTML(' }');
  ShowHTML('#calendario{');
  ShowHTML('  cursor:pointer;');
  ShowHTML('  width: 130px;');
  ShowHTML('  height: 136px;');
  ShowHTML('  background:url('.$w_dir.'calendario.gif) no-repeat;');
  ShowHTML('}');
  ShowHTML('#resultados{');
  ShowHTML('  cursor:pointer;');
  ShowHTML('  width: 130px;');
  ShowHTML('  height: 136px;');
  ShowHTML('  background:url('.$w_dir.'resultados.gif) no-repeat;');
  ShowHTML('}');
  ShowHTML('#download{');
  ShowHTML('  cursor:pointer;');
  ShowHTML('  width: 130px;');
  ShowHTML('  height: 136px;');
  ShowHTML('  background:url('.$w_dir.'download.gif) no-repeat;');
  ShowHTML('}');
  
 
 
  
  ShowHTML('</style>');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<style>img div {  behavior: url(iepngfix.htc)}</style>');
  
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  ShowHTML('    <td align="right">');

  // Se o geo-referenciamento estiver habilitado para o cliente, exibe link para acesso à visualização
  if (f($RS_Cliente,'georeferencia')=='S') {
    ShowHTML('      <a href="mod_gr/exibe.php?par=inicial&O=L&TP='.$TP.' - Geo-referenciamento" title="Clique para visualizar os mapas geo-referenciados." target="_blank"><img src="'.$conImgGeo.'" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
  }
  
  if ($_SESSION['DBMS']!=5) {
    // Exibe, se necessário, sinalizador para alerta
    $RS = db_getAlerta::getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
    if (count($RS)>0) {
      $w_sinal = $conImgAlLow;
      $w_msg   = 'Clique para ver alertas de atraso e proximidade da data de conclusão.';
      foreach($RS as $row) {
        if ($w_usuario==f($row,'solicitante')) {
          $w_sinal = $conImgAlMed;
          $w_msg   = 'Há alertas nos quais sua você é o responsável ou o solicitante. Clique para vê-los.';
        }
        if ($w_usuario==nvl(f($row,'sq_exec'),f($row,'solicitante')))  {
          $w_sinal = $conImgAlHigh;
          $w_msg   = 'Há alertas nos quais sua intervenção é necessária. Clique para vê-los.';
          break;
        }
      }
      ShowHTML('      <a href="'.$w_pagina.'alerta&O=L&TP='.$TP.' - Alertas" title="'.$w_msg.'"><img src="'.$w_sinal.'" border=0></a></font></b>');
    }
  }

  ShowHTML('<tr><td colspan=2><hr>');
  ShowHTML('</table>');
  ShowHTML('<center>');
  if ($O=="L") {
    $w_pde   = 0;
    $w_pns   = 0;
    $w_pne   = 0;
    $w_pne1  = 0;
    $w_pne2  = 0;
    $w_pne3  = 0;
    $w_todos = 0;
    $SQL = "select a.sq_plano ".$crlf. 
           "  from siw_solicitacao        a ".$crlf. 
           "       inner join siw_tramite d on (a.sq_siw_tramite = d.sq_siw_tramite and ".$crlf. 
           "                                    d.ativo          = 'S'".$crlf. 
           "                                   ) ".$crlf. 
           "  where a.codigo_interno='PIN' ";
    $RS = db_exec::getInstanceOf($dbms,$SQL,$recordcount);
    foreach($RS as $row) { $RS = $row; break; }
    $w_plano = f($RS,'sq_plano');
    
    $SQL = "select b.sq_solic_pai, b.sq_siw_solicitacao, b.codigo_interno, b.sq_plano, ".$crlf. 
           "       count(a.sq_siw_solicitacao) as qtd  ".$crlf. 
           "  from siw_solicitacao              a ".$crlf. 
           "       inner   join siw_menu       a1 on (a.sq_menu            = a1.sq_menu and ".$crlf. 
           "                                          a1.sigla             = 'PJCAD' and ".$crlf. 
           "                                          a1.sq_pessoa         = ".$w_cliente.$crlf. 
           "                                         )".$crlf. 
           "       inner   join siw_tramite    a2 on (a.sq_siw_tramite     = a2.sq_siw_tramite and ".$crlf. 
           "                                          a2.ativo             = 'S'".$crlf. 
           "                                         ) ".$crlf. 
           "       inner   join (select x.sq_siw_solicitacao, acesso(x.sq_siw_solicitacao,".$w_usuario.") as acesso".$crlf.
           "                       from siw_solicitacao x ".$crlf. 
           "                     group by x.sq_siw_solicitacao ".$crlf. 
           "                    )              a3 on (a.sq_siw_solicitacao = a3.sq_siw_solicitacao) ".$crlf. 
           "       inner   join siw_solicitacao b on (a.sq_solic_pai       = b.sq_siw_solicitacao) ".$crlf. 
           "         inner join siw_menu        c on (b.sq_menu            = c.sq_menu and ".$crlf. 
           "                                          c.sigla              = 'PEPROCAD' and ".$crlf. 
           "                                          c.sq_pessoa          = ".$w_cliente.$crlf. 
           "                                         )".$crlf. 
           "         inner join siw_tramite     d on (b.sq_siw_tramite     = d.sq_siw_tramite and ".$crlf. 
           "                                          d.ativo              = 'S'".$crlf. 
           "                                         ) ".$crlf. 
           "       left    join siw_solicitacao e on (b.sq_solic_pai       = e.sq_siw_solicitacao) ".$crlf. 
           "         left  join siw_menu        f on (e.sq_menu            = f.sq_menu and ".$crlf. 
           "                                          f.sigla              = 'PEPROCAD' and ".$crlf. 
           "                                          f.sq_pessoa          = ".$w_cliente.$crlf. 
           "                                         )".$crlf. 
           "         left  join siw_tramite     g on (e.sq_siw_tramite     = g.sq_siw_tramite and ".$crlf. 
           "                                          g.ativo              = 'S'".$crlf. 
           "                                         ) ".$crlf. 
           "  where 0 < a3.acesso ".$crlf. 
           "    and (b.sq_plano = ".nvl($w_plano,0)." or e.sq_plano = ".nvl($w_plano,0).") ".$crlf. 
    "group by b.sq_solic_pai, b.sq_siw_solicitacao, b.codigo_interno, b.sq_plano";
    $RS = db_exec::getInstanceOf($dbms,$SQL,$recordcount);
    $c_pde = 0;
    $c_pns = 0;
    $c_pne = 0;
    $c_pne1 = 0;
    $c_pne2 = 0;
    $c_pne3 = 0;
    
    foreach($RS as $row) { 
      $w_plano = f($row,'sq_plano');
      switch (f($row,'codigo_interno')) {
        case 'PDE':  $c_pde  = f($row,'sq_siw_solicitacao'); $w_pde = f($row,'qtd'); break;
        case 'PAS':  $c_pns  = f($row,'sq_siw_solicitacao'); $w_pns = f($row,'qtd'); break;
        case 'PMAE': $c_pne = f($row,'sq_solic_pai'); $c_pne1 = f($row,'sq_siw_solicitacao'); $w_pne1 = f($row,'qtd'); break;
        case 'PCE': $c_pne = f($row,'sq_solic_pai'); $c_pne2 = f($row,'sq_siw_solicitacao'); $w_pne2 = f($row,'qtd'); break;
        case 'PFC': $c_pne = f($row,'sq_solic_pai'); $c_pne3 = f($row,'sq_siw_solicitacao'); $w_pne3 = f($row,'qtd'); break;
      }
    }
    $w_pne   = $w_pne1 + $w_pne2 + $w_pne3;
    $w_todos = $w_pns + $w_pde + $w_pne1 + $w_pne2 + $w_pne3; 
    ShowHTML('<div id="menu_superior">');
    ShowHTML('<a href="'.$w_dir.'indicador.php?par=FramesAfericao&TP='.$TP.' - Status&p_plano='.$w_plano.'&SG='.$SG.'" title="Consulta ao status da RENAPI."><div id="resultados"></div></a>');
    ShowHTML('<a href="'.$w_dir.$w_pagina.'calendario&TP='.$TP.' - Calendário&p_plano='.$w_plano.'&SG='.$SG.'" title="Consulta de Programas, eventos e reuniões da RENAPI."><div id="calendario"></div></a>');
    ShowHTML('<a title="Consulta a documentos da RENAPI" href="'.$w_dir.$w_pagina.'arquivos&p_codigo=TODOS&TP='.$TP.' - Documentos"><div id="download"></div></a>');
    ShowHTML('</div>');
    ShowHTML('<div id="status">');
    ShowHTML('<div style="width:150px; height:20px; overflow:auto;">');
    ShowHTML('<table align="left" width="100%" cellpadding="0" cellspacing="0">');
    ShowHTML('  <tr>');
    ShowHTML('    <th width="80">UF&nbsp;');
    ShowHTML('    </th>');
    ShowHTML('    <th align="center" width="40">PIN');
    ShowHTML('    </th>');
    ShowHTML('    <th width="30">&nbsp;&nbsp;&nbsp;&nbsp;PAA');
    ShowHTML('    </th>');
    ShowHTML('    <th width="20">&nbsp;');
    ShowHTML('    </th>');    
    ShowHTML('  </tr>');
    ShowHTML('</table>');
    ShowHTML('</div>');
    ShowHTML('<div style="width:180px; height:150px; overflow:auto;">');
    ShowHTML('<table width="100%" cellpadding="0" cellspacing="0">');
    $RS = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PJCAD');
    $RS1 = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),5,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente, 
            $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, 
            $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, 
            $p_atividade, null, $p_orprior, null, $p_servico);
             
    $RS1  = SortArray($RS1,'co_uf','asc','codigo_interno','desc');
    $w_atual = '';
    $f1 = imagecreatefromgif('nucleo.gif');
    $f2 = imagecreatefromgif('renapi.gif');
    foreach($RS1 as $row) {
      switch (f($row,'sq_regiao')){
        case 1: $cor = '#B4AB8F'; break;
        case 2: $cor = '#63BD7B'; break;
        case 3: $cor = '#B4AB8F'; break;
        case 4: $cor = '#A1A1A4'; break;                              
        case 5: $cor = '#EE8583'; break;
        case 6: $cor = '#E0CB85'; break;
        default: $cor = '#FFFFFF';
      }
      
      if(substr(f($row,'codigo_interno'),0,3)=='PIN' && f($row,'sg_tramite')=='AT'){
        switch (f($row,'co_uf')){
          case 'AC': imagecopy($f2,$f1,30,210,0,0,17,16);  break;
          case 'AL': imagecopy($f2,$f1,555,220,0,0,17,16); break;
          case 'AP': imagecopy($f2,$f1,322,37,0,0,17,16);  break;
          case 'AM': imagecopy($f2,$f1,200,150,0,0,17,16); break;
          case 'BA': imagecopy($f2,$f1,430,270,0,0,17,16); break;
          case 'CE': imagecopy($f2,$f1,493,130,0,0,17,16); break;
          case 'ES': imagecopy($f2,$f1,485,387,0,0,17,16); break;
          case 'GO': imagecopy($f2,$f1,335,335,0,0,17,16); break;
          case 'MA': imagecopy($f2,$f1,420,130,0,0,17,16); break;
          case 'MT': imagecopy($f2,$f1,220,240,0,0,17,16); break;
          case 'MS': imagecopy($f2,$f1,250,370,0,0,17,16); break;
          case 'MG': imagecopy($f2,$f1,430,320,0,0,17,16); break;
          case 'PA': imagecopy($f2,$f1,350,100,0,0,17,16); break;
          case 'PB': imagecopy($f2,$f1,555,180,0,0,17,16); break;
          case 'PR': imagecopy($f2,$f1,295,445,0,0,17,16); break;
          case 'PE': imagecopy($f2,$f1,552,200,0,0,17,16); break;
          case 'PI': imagecopy($f2,$f1,466,162,0,0,17,16); break;
          case 'RJ': imagecopy($f2,$f1,445,425,0,0,17,16); break;
          case 'RN': imagecopy($f2,$f1,550,160,0,0,17,16); break;
          case 'RS': imagecopy($f2,$f1,290,545,0,0,17,16); break;
          case 'RO': imagecopy($f2,$f1,155,218,0,0,17,16); break;
          case 'RR': imagecopy($f2,$f1,175,22,0,0,17,16);  break;
          case 'SC': imagecopy($f2,$f1,365,505,0,0,17,16); break;
          case 'SP': imagecopy($f2,$f1,370,430,0,0,17,16); break;
          case 'SE': imagecopy($f2,$f1,532,238,0,0,17,16); break;
          case 'TO': imagecopy($f2,$f1,360,250,0,0,17,16); break;
          case 'DF': imagecopy($f2,$f1,385,321,0,0,17,16); break;
        }
      }
      // $cor = ($cor == '#f5f5f5'?'#ffffff':'#f5f5f5');
      if(Nvl($w_atual,'')!=f($row,'co_uf')){
        ShowHTML('  <tr>');
        ShowHTML('    <td bgcolor="'.$cor.'" align="center" width="80"><a style="color: #333333; text-decoration: none;" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf='.f($row,'co_uf').'&SG='.f($RS,'sigla').'">'.f($row,'co_uf').'</a></td>');
        $w_atual = f($row,'co_uf');
      }      
      if(f($row,'sg_tramite')!='AT'){
        ShowHTML('    <td align="center" width="40"><A class="HL" href="'.$w_dir.'projeto.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.exibeSmile('IDE',f($row,'ide')).'</a></td>');
      } else {
        ShowHTML('<td align="center" width="40"><A class="HL" href="'.$w_dir.'projeto.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null).'</a></td>');
      }      
    }
    imagegif($f2, 'mapa.gif');
    
    /*ShowHTML('  <tr bgcolor="'.$cor.'">');
    ShowHTML('    <td colspan="4" align="center" height="5px" width="60%">&nbsp;');
    ShowHTML('    </td>');
    ShowHTML('  </tr>');    */
    ShowHTML('</table>');
    ShowHTML('</div>');
    ShowHTML('</div>');
    ShowHTML('<div id="mapa">');
    

    
    /*
    
    ShowHTML('<span title="Núcleo instalado no Acre" style="position: relative; top: 38.5%; right: 30.8%;">');
    ShowHTML('<IMG SRC="cl_renapi/nucleo.png" id="acre">');
    ShowHTML('</span>');
    
    ShowHTML('<SPAN title="Núcleo instalado em Roraima" style="position: relative; top: 6.5%; right: 9.5%;">');
    ShowHTML('<IMG SRC="cl_renapi/nucleo.png">');
    ShowHTML('</SPAN> ');
    
    ShowHTML('<SPAN title="Núcleo instalado em Rondônia" style="position: relative; top: 46.5%; right: 14.5%;">');
    ShowHTML('<IMG SRC="cl_renapi/nucleo.png">');
    ShowHTML('</SPAN> ');

    ShowHTML('<SPAN title="Núcleo instalado no Rio Grande do Sul" style="position: relative; top: 95%; left: 2%;">');
    ShowHTML('<IMG SRC="cl_renapi/nucleo.png">');
    ShowHTML('</SPAN> ');
    
    ShowHTML('<SPAN title="Núcleo instalado em Goiás" style="position: relative; top: 60.5%; left: 2%;">');
    ShowHTML('<IMG SRC="cl_renapi/nucleo.png">');
    ShowHTML('</SPAN> ');    
    
    ShowHTML('<SPAN title="Núcleo instalado em Alagoas" style="position: relative; top: 40.2%; left: 35%;">');
    ShowHTML('<IMG SRC="cl_renapi/nucleo.png">');
    ShowHTML('</SPAN> ');        

    ShowHTML('<SPAN title="Núcleo instalado em Sergipe" style="position: relative; top: 43.2%; left: 27.5%;">');
    ShowHTML('<IMG SRC="cl_renapi/nucleo.png">');
    ShowHTML('</SPAN> ');    */    
        
    ShowHTML('<img name="renapi" src="'.$w_dir.'mapa.gif" width="100%" border="0" id="pdp" usemap="#m_pdp" alt="" />');
    ShowHTML('<map name="m_pdp" id="m_pdp">');
    //http://www2.sbpi.com.br/siw/cl_renapi/projeto.php?par=inicial&P1=1&P2=&P3=&P4=&TP=%3Cimg%20src=images/Folder/SheetLittle.gif%20BORDER=0%3EProgramas%20-%20Programa&SG=PJCAD
    //href="cl_renapi/projeto.php?par=atualizaetapa&R='.$w_pagina.$par.'&O=V&w_chave='.f($row, 'sq_siw_solicitacao').'&w_chave_aux='.f($row, 'sq_projeto_etapa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"
    $RSPais = db_getCountryList::getInstanceOf($dbms,null,$p_nome,$p_ativo,$p_sigla);
    foreach($RSPais as $row){
      If(Nvl(f($row,'padrao'),'N') == 'S'){
        $w_pais = f($row,'sq_pais');
      }
    }
    ShowHTML('<area shape="rect" coords="310,259,329,272" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=DF&SG='.f($RS,'sigla').'" alt="Distrito Federal" />');
    ShowHTML('<area shape="poly" coords="129,38,121,41,118,47,104,55,99,53,95,57,81,36,73,42,70,41,68,43,50,43,48,52,56,53,58,57,47,59,47,68,54,80,48,120,41,119,32,122,25,122,13,129,8,143,10,147,2,152,2,156,4,158,8,159,13,163,17,163,24,165,30,167,45,171,61,178,88,190,96,184,101,186,105,183,109,185,112,180,120,180,125,166,136,166,151,177,190,176,192,159,191,151,198,134,216,95,186,75,184,62,170,64,168,68,166,75,160,76,154,74,151,80,151,85,145,79,144,70,140,56,137,45,138,42" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=AM&SG='.f($RS,'sigla').'" alt="Amazonas" />');
    ShowHTML('<area shape="poly" coords="89,192,76,200,67,206,43,205,42,184,33,190,25,192,20,187,9,186,12,180,1,162,1,156,47,171,86,189" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=AC&SG='.f($RS,'sigla').'" alt="Acre" />');
    ShowHTML('<area shape="poly" coords="112,14,137,18,140,14,162,6,169,1,175,17,175,43,183,51,183,63,172,63,165,75,157,73,152,75,150,84,144,77,138,42,129,38,121,35,120,28,119,21,116,19,114,18,113,16,113,15" href="rr.html" alt="Roraima" />');
    ShowHTML('<area shape="poly" coords="90,190,105,189,105,207,112,220,120,224,134,227,144,233,149,237,162,238,167,233,173,222,170,214,172,209,171,206,153,204,152,178,146,177,136,167,125,167,121,180,112,181,109,185,100,186,88,188,89,189" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=RO&SG='.f($RS,'sigla').'" alt="Rondônia" />');
    ShowHTML('<area shape="poly" coords="153,177,153,204,172,206,170,215,173,221,164,238,170,272,191,273,190,279,194,286,200,290,211,286,218,284,231,289,241,288,248,285,247,291,255,293,257,279,260,271,270,267,276,255,280,252,284,238,287,226,284,209,291,190,209,184,200,176,198,168,192,159,191,176,168,177,158,177" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=MT&SG='.f($RS,'sigla').'" alt="Mato Grosso" />');
    ShowHTML('<area shape="poly" coords="235,35,248,37,259,38,269,21,275,10,280,17,286,38,295,44,293,56,287,60,286,64,271,81,265,79,252,55,237,42" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=AP&SG='.f($RS,'sigla').'" alt="Amapá" />');
    ShowHTML('<area shape="poly" coords="183,50,189,51,193,46,204,41,219,42,225,35,233,34,235,42,247,51,254,58,259,70,266,80,270,81,291,58,300,64,312,68,314,74,327,74,342,80,334,103,324,123,313,130,314,140,311,149,304,152,304,158,301,160,303,168,299,176,293,181,290,190,209,182,201,176,191,152,216,95,186,74" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=PA&SG='.f($RS,'sigla').'" alt="Pará" />');
    ShowHTML('<area shape="poly" coords="200,290,200,298,194,313,195,318,194,321,196,344,212,347,216,345,218,349,224,350,227,368,234,370,241,369,249,360,250,354,260,349,270,340,278,322,281,319,280,315,282,310,263,301,255,293,247,291,247,285,232,289,218,284" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=MS&SG='.f($RS,'sigla').'" alt="Mato Grosso do Sul" />');
    ShowHTML('<area shape="poly" coords="240,369,241,378,237,386,240,389,245,389,249,397,256,399,266,399,278,401,281,396,285,396,288,394,291,395,295,394,299,396,304,393,312,393,313,389,317,384,313,380,312,380,311,376,303,376,304,372,300,366,298,358,287,353,285,355,272,352,270,349,265,352,258,352,251,353,246,364" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=PR&SG='.f($RS,'sigla').'" alt="Paraná" />');
    ShowHTML('<area shape="poly" coords="312,394,311,406,313,411,312,416,309,427,298,436,294,434,296,426,287,425,278,416,269,411,260,409,245,408,248,397,257,398,276,402,281,396,287,395,300,396,304,394" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=SC&SG='.f($RS,'sigla').'" alt="Santa Catarina" />');
    ShowHTML('<area shape="poly" coords="297,436,289,450,280,462,270,471,264,476,262,483,252,491,250,483,254,478,246,469,233,461,226,456,220,458,218,453,208,445,204,449,201,446,206,440,224,422,244,409,261,409,278,416,284,423,295,427,295,430,295,430" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=RS&SG='.f($RS,'sigla').'" alt="Rio Grande do Sul" />');
    ShowHTML('<area shape="poly" coords="319,384,334,370,341,370,344,364,348,366,361,360,364,353,358,348,345,352,344,353,339,354,334,349,337,336,334,334,331,336,329,329,328,320,322,318,314,319,306,321,302,317,288,315,282,319,278,321,270,339,257,351,269,350,285,354,297,358,300,364,302,375,310,377" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=SP&SG='.f($RS,'sigla').'" alt="S&atilde;o Paulo" />');
    ShowHTML('<area shape="poly" coords="313,131,326,139,322,157,329,167,337,167,331,172,331,178,334,186,337,193,345,196,335,207,338,213,339,229,332,229,322,233,308,229,303,228,300,233,292,228,291,224,287,226,285,213,291,188,299,175,304,167,301,161,304,155,311,149,314,141,315,136" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=TO&SG='.f($RS,'sigla').'" alt="Tocantins" />');
    ShowHTML('<area shape="poly" coords="365,359,379,354,391,354,391,350,398,346,405,344,403,337,405,334,398,333,393,328,390,329,388,339,382,342,377,344,371,344,358,348,365,351" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=RJ&SG='.f($RS,'sigla').'" alt="Rio de Janeiro" />');
    ShowHTML('<area shape="poly" coords="405,333,408,328,410,329,419,312,421,297,413,292,402,296,403,302,401,304,405,310,399,321,396,321,394,328,398,332" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=ES&SG='.f($RS,'sigla').'" alt="Espírito Santo" />');
    ShowHTML('<area shape="poly" coords="447,214,436,203,442,200,443,194,441,190,441,185,453,192,460,198" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=SE&SG='.f($RS,'sigla').'" alt="Sergipe" />');
    ShowHTML('<area shape="poly" coords="440,185,443,178,453,184,460,182,465,179,475,178,471,186,461,198,452,190" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=AL&SG='.f($RS,'sigla').'" alt="Alagoas" />');
    ShowHTML('<area shape="poly" coords="475,178,479,168,479,160,472,160,471,163,462,164,453,171,450,165,453,160,450,156,442,164,432,163,427,163,421,158,410,159,409,162,409,168,401,175,409,182,409,185,415,181,422,173,427,176,438,182,443,178,453,183,464,178" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=PE&SG='.f($RS,'sigla').'" alt="Pernambuco" />');
    ShowHTML('<area shape="poly" coords="479,160,478,149,465,147,461,145,458,154,454,150,451,151,448,147,452,145,444,144,440,147,434,146,432,152,433,160,432,163,442,164,450,156,453,160,451,164,453,170,461,164,470,163,473,159" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=PB&SG='.f($RS,'sigla').'" alt="Para&iacute;ba" />');
    ShowHTML('<area shape="poly" coords="478,149,475,133,469,129,458,130,451,128,444,128,442,135,438,140,435,142,435,146,439,146,448,143,452,145,449,149,453,150,457,153,461,145" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=RN&SG='.f($RS,'sigla').'" alt="Rio Grande do Norte" />');
    ShowHTML('<area shape="poly" coords="450,127,436,114,432,111,418,103,401,102,400,111,402,117,401,128,405,133,407,148,410,152,410,160,421,158,428,163,432,161,433,151,435,143,442,132,448,128" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=CE&SG='.f($RS,'sigla').'" alt="Ceará" />');
    ShowHTML('<area shape="poly" coords="342,80,350,85,356,85,364,96,368,96,373,95,384,100,393,101,393,105,390,109,380,119,382,133,379,139,381,146,379,152,374,152,367,152,358,160,349,162,348,169,345,174,344,183,345,195,338,192,331,176,337,168,331,166,323,157,326,139,321,133,313,130,329,115,339,98" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=MA&SG='.f($RS,'sigla').'" alt="Maranh&atilde;o" />');
    ShowHTML('<area shape="poly" coords="393,100,399,103,399,112,401,118,400,128,403,134,405,148,410,153,408,161,407,167,397,179,383,186,374,182,367,185,369,191,366,199,352,202,347,193,345,196,346,172,349,163,359,159,367,153,379,152,379,139,380,120" href="pi.html" alt="Piau&iacute;" />');
    ShowHTML('<area shape="poly" coords="446,214,442,224,437,229,429,233,429,250,430,266,426,290,421,295,409,285,418,269,414,267,399,265,395,258,379,251,374,253,368,250,367,246,361,247,345,258,342,255,343,248,339,244,338,234,338,214,336,206,347,194,354,202,359,201,366,199,371,193,371,188,373,183,383,186,393,182,400,176,408,181,409,185,421,174,439,181,441,190,443,194,442,199,438,202,442,210" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=BA&SG='.f($RS,'sigla').'" alt="Bahia" />');
    ShowHTML('<area shape="poly" coords="341,255,336,252,336,257,332,257,332,263,334,267,327,268,326,275,329,278,324,285,328,289,326,295,321,300,308,299,302,302,291,302,281,317,293,316,302,317,306,321,315,319,323,318,330,321,331,335,336,336,336,344,339,353,343,353,350,350,359,347,369,344,378,343,387,339,389,329,393,328,394,321,399,320,403,310,401,306,401,298,411,291,408,285,417,269,400,266,395,259,379,252,371,252,367,247,353,253,347,258,340,255" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=MG&SG='.f($RS,'sigla').'" alt="Minas Gerais" />');
    ShowHTML('<area shape="poly" coords="282,310,289,301,301,300,307,297,321,299,325,294,324,285,329,279,325,269,333,267,331,263,331,256,335,256,336,252,343,255,342,246,338,241,338,230,328,233,322,234,313,233,304,230,302,234,291,228,291,224,284,241,281,254,277,257,270,267,261,273,258,279,256,293,263,301" href="cl_renapi/projeto.php?par=inicial&R='.$w_pagina.$par.'&P1=5&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_pais='.$w_pais.'&p_uf=GO&SG='.f($RS,'sigla').'" alt="Goiás" />');
    ShowHTML('</map>');
    ShowHTML('</div>');
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</center>');
  ShowHTML('</body>');
  ShowHTML('</html>');
  //Rodape();
}

// =========================================================================
// Exibe alertas de atraso e proximidade da data de conclusao
// -------------------------------------------------------------------------
function Alerta() {
  extract($GLOBALS);
  
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  $RS_Volta = db_getLinkData::getInstanceOf($dbms,$w_cliente,'MESA');
  ShowHTML('  <td align="right"><a class="SS" href="'.$conRootSIW.f($RS_Volta,'link').'&P1='.f($RS_Volta,'p1').'&P2='.f($RS_Volta,'p2').'&P3='.f($RS_Volta,'p3').'&P4='.f($RS_Volta,'p4').'&TP=<img src='.f($RS_Volta,'imagem').' BORDER=0>'.f($RS_Volta,'nome').'&SG='.f($RS_Volta,'sigla').'" target="content">Voltar para '.f($RS_Volta,'nome').'</a>');
  ShowHTML('<tr><td colspan=2><hr>');
  ShowHTML('</table>');
  ShowHTML('<center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=='L') {
    // Recupera solicitações a serem listadas
    $RS_Solic = db_getAlerta::getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
    $RS_Solic = SortArray($RS_Solic, 'cliente', 'asc', 'usuario', 'asc', 'nm_modulo','asc', 'nm_servico', 'asc', 'titulo', 'asc');

    // Recupera pacotes de trabalho a serem listados
    $RS_Pacote = db_getAlerta::getInstanceOf($dbms, $w_cliente, $w_usuario, 'PACOTE', 'N', null);
    $RS_Pacote = SortArray($RS_Pacote, 'cliente', 'asc', 'usuario', 'asc', 'nm_projeto','asc', 'cd_ordem', 'asc');

    ShowHTML(VisualAlerta($w_cliente, $w_usuario, 'TELA', $RS_Solic, $RS_Pacote));
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Exibe calendário da PDP
// -------------------------------------------------------------------------
function Arquivos() {
  extract($GLOBALS);

  $p_unidade = $_REQUEST['p_unidade'];
  $p_codigo  = $_REQUEST['p_codigo'];
  $p_tipo    = $_REQUEST['p_tipo'];
  $p_titulo  = $_REQUEST['p_titulo'];
  
  if ($p_codigo=='TODOS') {
    $RS_Unidade = array();
    if (nvl($p_unidade,'')!='') { 
      $RS_Unidade = db_getUorgList::getInstanceOf($dbms,$w_cliente,$p_unidade,null,null,null,null);
      foreach($RS_Unidade as $row) { $RS_Unidade = $row; break; }
    }
  } elseif (nvl($p_unidade,'')!='') {
    $RS_Unidade = db_getUorgList::getInstanceOf($dbms,$w_cliente,$p_unidade,null,null,null,null);
    foreach($RS_Unidade as $row) { $RS_Unidade = $row; break; }
  
    $p_codigo = f($RS_Unidade,'sigla');
  } else {
    $RS_Unidade = db_getUorgList::getInstanceOf($dbms,$w_cliente,null,null,null,$p_codigo,null);
    foreach($RS_Unidade as $row) { $RS_Unidade = $row; break; }
  
    $p_unidade = f($RS_Unidade,'sq_unidade');

  }

  if (nvl($p_unidade,'')!='') {
    $RS_Membros = db_getUserList::getInstanceOf($dbms,$w_cliente,null,$p_unidade,null,null,null,null,null,null,null,null,null,null,null,null,null);
  } else {
    $RS_Membros = array();
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('  <!-- CSS FILE for my tree-view menu -->');
  ShowHTML('  <link rel="stylesheet" type="text/css" href="'.$w_dir_volta.'classes/menu/xPandMenu.css">');
  ShowHTML('  <!-- JS FILE for my tree-view menu -->');
  ShowHTML('  <script src="'.$w_dir_volta.'classes/menu/xPandMenu.js"></script>');
  ScriptOpen('JavaScript');
  checkBranco();
  formatadatama();
  ValidateOpen('Validacao');
  Validate('p_texto','Texto','','',3,50,'1','1');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  $w_embed="HTML";
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  ShowHTML('<tr><td><hr>');
  if (count($RS_Membros)>0) {
    ShowHTML('<fieldset><table width="100%" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('   <tr><td><b>Composição</b></td>');
    foreach($RS_Membros as $row) {
      ShowHTML('  <tr valign="top">');
      ShowHTML('    <td width="1%" nowrap><li>'.f($row,'nome').((f($row,'localizacao')!='Única') ? ' ('.f($row,'localizacao').')' : '').'</td>');
      ShowHTML('    <td><a href="mailto:'.f($row,'email').'">'.f($row,'email').'</a></td>');
      ShowHTML('  </tr>');
    }
    ShowHTML('</table></fieldset>');
    ShowHTML('<tr><td><hr>');
  }
  ShowHTML(' <fieldset><table width="100%" bgcolor="'.$conTrBgColor.'">');
  AbreForm('Form', $w_dir.$w_pagina.$par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="p_codigo" value="'.$p_codigo.'">');
  ShowHTML('<INPUT type="hidden" name="p_pesquisa" value="">');
  ShowHTML('<INPUT type="hidden" name="w_mes" value="'.$w_mes.'">');
  ShowHTML('<INPUT type="hidden" name="p_unidade" value="'.f($RS_Unidade,'sq_unidade').'">');
  ShowHTML('   <tr><td colspan="2"><b>Documentos</b></td>');
  if ($p_codigo=='TODOS') {
    ShowHTML('   <tr>');
    SelecaoUnidade('<u>Á</u>rea', 'A', null, $p_unidade, null, 'p_unidade', 'CL_RENAPI', null, null, '<td>');
    ShowHTML('   </tr>');
  }
  ShowHTML('   <tr>');
  SelecaoTipoArquivoTab('Ti<u>p</u>o:','P',null,$p_tipo,null,'p_tipo',null,null,null,'<td>');
  ShowHTML('   </tr>');
  ShowHTML('   <tr><td><b>Pesquisa por <u>t</u>exto</b></td>');
  ShowHTML('   <td><input class="STI" accesskey="T" type="text" size="50" maxlength="50" name="p_texto" value="'. $p_texto .'"></td>');
  ShowHTML('   </tr>');
  ShowHTML('   <tr><td>&nbsp;</td>');
  ShowHTML('       <td>');
  ShowHTML('       <input class="STB" type="submit" name="Botao" value="BUSCAR" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\'; javascript:document.Form.p_pesquisa.value=\'S\';">');
  $RS_Volta = db_getLinkData::getInstanceOf($dbms, $w_cliente, 'MESA');
  ShowHTML('       <input class="STB" type="button" name="Botao" value="VOLTAR" onClick="javascript:location.href=\''.$conRootSIW.f($RS_Volta, 'link').'&P1='.f($RS_Volta, 'p1').'&P2='.f($RS_Volta, 'p2').'&P3='.f($RS_Volta, 'p3').'&P4='.f($RS_Volta, 'p4').'&TP=<img src='.f($RS_Volta, 'imagem').' BORDER=0>'.f($RS_Volta, 'nome').'&SG='.f($RS_Volta, 'sigla').'\';">');
  ShowHTML('   </td></tr>');
  ShowHTML('</FORM>');
  ShowHTML(' </table></fieldset>');
  
  if($_REQUEST['p_pesquisa'] == 'S'){
    $RS = db_getUorgAnexo::getInstanceOf($dbms,f($RS_Unidade,'sq_unidade'),null,$p_tipo,$p_titulo,$w_cliente);
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'ordem','asc','nome','asc');
    } else {
      $RS = SortArray($RS,'ordem','asc','nome','asc');
    }
    ShowHTML('<tr><td colspan=2><hr>');
    
    if (count($RS)==0) {
      ShowHTML('<tr><td colspan=2>Registro não encontrado');
    } else {
      ShowHTML('<tr><td colspan="2"><b>'.strtoupper(f($row,'titulo')).'</b>');
      ShowHTML('<tr><td align="center" colspan=2>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.linkOrdena('Tipo do arquivo','nm_tipo_arquivo').'</td>');
      ShowHTML('          <td><b>'.linkOrdena('Título','nome').'</td>');
      ShowHTML('          <td><b>'.linkOrdena('Resumo','descricao').'</td>');
      ShowHTML('          <td><b>'.linkOrdena('Data','inclusao').'</td>');
      ShowHTML('          <td><b>'.linkOrdena('Formato','tipo').'</td>');
      ShowHTML('          <td><b>'.linkOrdena('Tamanho','tamanho').'</td>');
      ShowHTML('        </tr>');
      $w_cor=$conTrBgColor;
      foreach($RS as $row1) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('    <tr bgColor="'.$w_cor.'">');
        ShowHTML('     <td>'.f($row1,'nm_tipo_arquivo').'</td>');
        ShowHTML('     <td>'.LinkArquivo('HL',$w_cliente,f($row1,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row1,'nome'),null).'</td>');
        ShowHTML('     <td>'.Nvl(f($row1,'descricao'),'---').'</td>');
        ShowHTML('     <td>'.formataDataEdicao(f($row1,'inclusao')).'</td>');
        ShowHTML('     <td>'.f($row1,'tipo').'</td>');
        ShowHTML('     <td align="right">'.round(f($row1,'tamanho')/1024,1).' KB&nbsp;</td>');
      } 
      ShowHTML('  </table>');
      ShowHTML('<tr><td>&nbsp;</td></tr>');
    }
  }
  if ($w_usuario_se && $p_codigo=='PDPSE') {
    $RS = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PJMON');
    $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),4,
              null,null,null,null,null,null,null,null,null,null,
              null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach($RS as $row) {
      ShowHTML('<tr><td colspan=2><hr>');
      ShowHTML('<fieldset><table width="100%" bgcolor="'.$conTrBgColor.'">');
      ShowHTML('            <FONT SIZE="2"><A class="SS" HREF="cl_renapi/monitor.php?par=Visual&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.f($row,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">Acompanhamento e Monitoramento</a></FONT>');
      ShowHTML('</table></fieldset>');
      break;
    }
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Exibe calendário da PDP
// -------------------------------------------------------------------------
function Calendario() {
  extract($GLOBALS);
  
  $p_plano = $_REQUEST['p_plano'];

  if ($w_troca>'' && $O!='E') {
    $w_acontecimento = $_REQUEST['w_acontecimento'];
    $w_programa      = $_REQUEST['w_programa'];
    $w_projeto       = $_REQUEST['w_projeto'];
    $w_secretaria    = $_REQUEST['w_secretaria'];
    $w_coordenacao   = $_REQUEST['w_coordenacao'];
    $w_comite        = $_REQUEST['w_comite'];
    $w_cc            = $_REQUEST['w_cc'];
    $w_asunto        = $_REQUEST['w_asunto'];
    $w_local         = $_REQUEST['w_local'];
    $w_data_inicio   = $_REQUEST['w_data_inicio'];
    $w_data_termino  = $_REQUEST['w_data_termino'];
    $w_hora_inicio   = $_REQUEST['w_hora_inicio'];
    $w_hora_termino  = $_REQUEST['w_hora_termino'];
    $w_mensagem      = $_REQUEST['w_mensagem'];
  }  
  
  $w_tipo=$_REQUEST['w_tipo'];  
  if ($w_tipo=='PDF') {
    headerpdf('Visualização de Calendário',$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de Calendário',0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
    ShowHTML('  <!-- CSS FILE for my tree-view menu -->');
    ShowHTML('  <link rel="stylesheet" type="text/css" href="'.$w_dir_volta.'classes/menu/xPandMenu.css">');
    ShowHTML('  <!-- JS FILE for my tree-view menu -->');
    ShowHTML('  <script src="'.$w_dir_volta.'classes/menu/xPandMenu.js"></script>');
    ShowHTML('<link href="xPandMenu.css" rel="stylesheet" type="text/css">');
    ScriptOpen('JavaScript');
    modulo();
    CheckBranco();
    FormataDataMA();
    FormataData();
    FormataHora();
    SaltaCampo();
    ValidateOpen('Validacao');
    if($O=='L'){
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm.p_agenda.checked) w_erro=false;');
      ShowHTML('  for (i=0; i < theForm["p_tipo_evento[]"].length; i++) {');
      ShowHTML('    if (theForm["p_tipo_evento[]"][i].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Você deve informar pelo menos um tipo de evento!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      Validate('p_programa','Programa','SELECT',null,1,18,'','0123456789');
      Validate('w_mesano','Mês inicial','DATAMA','1','7','7','','0123456789/');
      ShowHTML('  theForm.p_pesquisa.value="OK";');
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    }  
    
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<table border="0" width="100%">');
    ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
    ShowHTML('<tr><td><hr>');
    ShowHTML(' <fieldset><table width="100%" bgcolor="'.$conTrBgColor.'">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_pesquisa" value="">');
    ShowHTML('   <tr><td><b><u>M</u>ês inicial</b> (mm/aaaa)</b><td><input '.$w_Disabled.' accesskey="m" type="text" name="w_mesano" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.$w_mesano.'" onKeyDown="FormataDataMA(this,event);" onBlur="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.target=\'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_programa\'; document.Form.submit();"></td>');
    ShowHTML('   <tr>');
    ShowHTML('     <td><b>Recuperar</b><td>');
    ShowHTML('          <input type="CHECKBOX" name="p_agenda" value="S" '.((nvl($p_agenda,'')!='') ? 'checked': '').'> Agenda de ação');
    $RS_Projeto = db_getLinkData::getInstanceOf($dbms,$w_cliente,'EVCAD');
    SelecaoTipoEventoCheck(null,null,null,$p_tipo_evento,f($RS_Projeto,'sq_menu'),'p_tipo_evento[]',null,null,null,'&nbsp;');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    selecaoPrograma('<u>M</u>acroprograma', 'R', 'Se desejar, selecione um dos macroprogramas.', $p_programa, $p_plano, $p_objetivo, 'p_programa', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.target=\'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_projeto\'; document.Form.submit();"',1,null,'<td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    $RS = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PJCAD');
    SelecaoProjeto('<u>P</u>rograma', 'P', 'Selecione um item na relação.', $p_projeto, $w_usuario, f($RS, 'sq_menu'), $p_programa, $p_objetivo, $p_plano, 'p_projeto', 'PJLIST', null, 1, null, '<td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');    
    SelecaoUnidade('<u>Ó</u>rgão responsável', 'O', null, $p_unidade, null, 'p_unidade', null, null,null,'<td>');
    ShowHTML('   <tr><td><b>Pesquisa por <u>t</u>exto<td><input class="STI" accesskey="T" type="text" size="80" maxlength="80" name="p_texto" id="p_texto" value="'. $p_texto .'"></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr><td><b>Exibir</b></td>');
    ShowHTML('       <td>');
    ShowHTML('     <input type="checkbox" '.((nvl($p_descricao,'')!='') ? 'checked' : '').'  name="p_descricao"  value="1" />Detalhamento do item');
    ShowHTML('     <input type="checkbox" '.((nvl($p_situacao,'')!='') ? 'checked' : '').' name="p_situacao" value="1" />Situação atual do item');
    ShowHTML('   </td></tr>');
    ShowHTML('   <tr><td><td colspan="2">');
    ShowHTML('     <input class="STB" type="submit" name="Botao" value="BUSCAR">');
    $RS_Volta = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'MESA');
    ShowHTML('       <input class="STB" type="button" name="Botao" value="VOLTAR" onClick="javascript:location.href=\''.$conRootSIW.f($RS_Volta, 'link').'&P1='.f($RS_Volta, 'p1').'&P2='.f($RS_Volta, 'p2').'&P3='.f($RS_Volta, 'p3').'&P4='.f($RS_Volta, 'p4').'&TP=<img src='.f($RS_Volta, 'imagem').' BORDER=0>'.f($RS_Volta, 'nome').'&SG='.f($RS_Volta, 'sigla').'\';">');
    ShowHTML('   </tr>');
    ShowHTML('          </form>');
    ShowHTML(' </table></fieldset>');
    
    // Exibe o calendário da organização
    include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
    for ($i=$w_ano1;$i<=$w_ano6;$i++) {
      if (nvl($i,0)>0 && !(is_array($RS_Ano[$i]))) {
        $RS_Ano[$i] = db_getDataEspecial::getInstanceOf($dbms,$w_cliente,null,$i,'S',null,null,null);
        $RS_Ano[$i] = SortArray($RS_Ano[$i],'data_formatada','asc');
      }
    }
    // Recupera os dados da unidade de lotação do usuário
    include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
    $RS_Unidade = db_getUorgData::getInstanceOf($dbms,$_SESSION['LOTACAO']);
    if (nvl($_REQUEST['p_pesquisa'],'')!='') {
      $RS_Resultado = db_getSolicResultado :: getInstanceOf($dbms,$w_cliente,$p_programa,$p_projeto,$p_unidade,$p_solicitante,$p_texto,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null,null,null,null,null,null,$p_agenda,$p_tipo_evento,'CALEND');
      if ($p_ordena>'') { 
        $lista = explode(',',str_replace(' ',',',$p_ordena));
        $RS_Resultado = SortArray($RS_Resultado,$lista[0],$lista[1],'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
      } else {
        $RS_Resultado = SortArray($RS_Resultado,'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
      }
      $RS_ResultCal = SortArray($RS_Resultado,'mes_ano','asc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
      
      // Cria arrays com cada dia do período, definindo o texto e a cor de fundo para exibição no calendário
      foreach($RS_ResultCal as $row) {
        $w_saida   = f($row,'mes_ano');
        $w_chegada = f($row,'mes_ano');
        retornaArrayDias(f($row,'mes_ano'), f($row,'mes_ano'), &$w_datas, (((nvl(f($row, 'sq_projeto_etapa'),'')!='')) ? 'Item da agenda de ação.' : f($row, 'nm_tipo_evento')), 'N');
      }
      reset($RS_ResultCal);
      foreach($RS_ResultCal as $row) {
        $w_saida   = f($row,'mes_ano');
        $w_chegada = f($row,'mes_ano');
        retornaArrayDias(f($row,'mes_ano'), f($row,'mes_ano'), &$w_cores, (((nvl(f($row, 'sq_projeto_etapa'),'')!='')) ? $conTrBgColorLightYellow2 : ((f($row, 'sg_tipo_evento')=='REUNIAO') ? $conTrBgColorLightGreen2 : $conTrBgColorLightBlue1)), 'N');
      }
    }

    // Verifica a quantidade de colunas a serem exibidas
    $w_colunas = 1;

    // Configura a largura das colunas
    switch ($w_colunas) {
    case 1:  $width = "100%";  break;
    case 2:  $width = "50%";  break;
    case 3:  $width = "33%";  break;
    case 4:  $width = "25%";  break;
    default: $width = "100%";
    }
    ShowHTML('        <table width="100%" border="0" align="center" CELLSPACING=0 CELLPADDING=0 BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'><tr valign="top">');
    // Exibe calendário e suas ocorrências ==============
    ShowHTML('          <td width="'.$width.'" align="center"><table border="1" cellpadding=0 cellspacing=0>');
    ShowHTML('            <tr><td colspan=6 width="100%"><table width="100%" border=0 cellpadding=0 cellspacing=0><tr>');
    ShowHTML('              <td align="center" bgcolor="'.$conTrBgColor.'"><b>Calendário '.f($RS_Cliente, 'nome_resumido').' ('.f($RS_Unidade, 'nm_cidade').')</td>');
    ShowHTML('              </table>');
    // Variáveis para controle de exibição do cabeçalho das datas especiais
    $w_detalhe1 = false;
    $w_detalhe2 = false;
    $w_detalhe3 = false;
    $w_detalhe4 = false;
    $w_detalhe5 = false;
    $w_detalhe6 = false;
    
    ShowHTML('            <tr valign="top">');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano1],$w_mes1.$w_ano1,$w_datas,$w_cores,&$w_detalhe1).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano2],$w_mes2.$w_ano2,$w_datas,$w_cores,&$w_detalhe2).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano3],$w_mes3.$w_ano3,$w_datas,$w_cores,&$w_detalhe3).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano4],$w_mes4.$w_ano4,$w_datas,$w_cores,&$w_detalhe4).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano5],$w_mes5.$w_ano5,$w_datas,$w_cores,&$w_detalhe5).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano6],$w_mes6.$w_ano6,$w_datas,$w_cores,&$w_detalhe6).' </td>');

    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3 || $w_detalhe4 || $w_detalhe5 || $w_detalhe6) {
      ShowHTML('            <tr><td colspan=6 bgcolor="'.$conTrBgColor.'">');
      ShowHTML('              <b>Clique sobre o dia em destaque para ver detalhes.</b>');
    }

    // Exibe informações complementares sobre o calendário
    ShowHTML('            <tr valign="top" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('              <td colspan=3 align="center">');
    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3 || $w_detalhe4 || $w_detalhe5 || $w_detalhe6) {
      ShowHTML('                <table width="100%" border="0" cellspacing=1>');
      if (count($RS_Ano)==0) {
        ShowHTML('                  <tr valign="top"><td align="center">&nbsp;');
      } else {
        ShowHTML('                  <tr valign="top"><td align="center"><b>Data<td><b>Ocorrências');
        reset($RS_Ano);
        foreach($RS_Ano as $RS_Ano_Atual) {
          foreach($RS_Ano_Atual as $row_ano) {
            // Exibe apenas as ocorrências do trimestre selecionado
            if (f($row_ano,'data_formatada') >= $w_inicio && f($row_ano,'data_formatada') <= $w_fim) {
              ShowHTML('                  <tr valign="top">');
              ShowHTML('                    <td align="center">'.formataDataEdicao(f($row_ano,'data_formatada'),5));
              ShowHTML('                    <td>'.f($row_ano,'nome'));
            }
          }
        }
        ShowHTML('              </table>');
      }
    }
    ShowHTML('              <td colspan=3>Legenda:<br><table border=0>');
    ShowHTML('              <tr><td bgcolor="'.$conTrBgColorLightGreen2.'">&nbsp;&nbsp;&nbsp;<td>Reuniões da RENAPI');
    ShowHTML('              <tr><td bgcolor="'.$conTrBgColorLightYellow2.'">&nbsp;&nbsp;&nbsp;<td>Itens de Agendas de Ação');
    ShowHTML('              <tr><td bgcolor="'.$conTrBgColorLightBlue1.'">&nbsp;&nbsp;&nbsp;<td>Outros eventos');
    ShowHTML('              <tr><td style="border: 1px solid rgb(0,0,0);">&nbsp;<td>Feriados');
    ShowHTML('              <tr><td style="border: 2px solid rgb(0,0,0);">&nbsp;<td>Data de hoje');
    ShowHTML('              </table><br>Observação: as reuniões da RENAPI terão prioridade sobre os demais tipos de eventos.');
    ShowHTML('          </table>');
    ShowHTML('  </table>');
  }
// Final da exibição do calendário e suas ocorrências ==============
  if (nvl($_REQUEST['p_pesquisa'],'')!='') {
    $w_legenda='    <table id="legenda">';
    $w_legenda.='      <tr><td colspan="2"><table border=0>';
    $w_legenda.='        <tr valign="top"><td colspan=6>'.(($w_embed=='WORD') ? 'Legenda para itens de agenda de ação: ' : '').ExibeImagemSolic('ETAPA',null,null,null,null,null,null,null, null,true);
    $w_legenda.='      </table>';
    $w_legenda.='    </table>';
  
    if ($w_embed!='WORD') {
      // Inclusão do arquivo da classe
      include_once($w_dir_volta.'classes/menu/xPandMenu.php');
      
      $root = new XMenu();
      $node1 = &$root->addItem(new XNode('Legenda para itens de agenda de ação',false,$conRootSIW.'images/Folder/LineBeginPlus.gif',$conRootSIW.'images/Folder/LineBeginMinus.gif'));
      $node11 = &$node1->addItem(new XNode($w_legenda,false,'',''));
    
      // Quando for concluída a montagem dos nós, chame a função generateTree(), usando o objeto raiz, para gerar o código HTML.
      // Essa função não possui argumentos.
      // No código da função pode ser verificado que há um parâmetro opcional, usado internamente para chamadas recursivas, necessárias à montagem de toda a árvore.
      ShowHTML(str_replace('Xnode','Xnode1',str_replace('Xleaf','Xleaf1',$root->generateTree())));
    } else {
      ShowHTML($w_legenda);
    }
    
    $RS_Resultado = db_getSolicResultado :: getInstanceOf($dbms,$w_cliente,$p_programa,$p_projeto,$p_unidade,$p_solicitante,$p_texto,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null,null,null,null,null,null,$p_agenda,$p_tipo_evento,'CALEND');
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS_Resultado = SortArray($RS_Resultado,$lista[0],$lista[1],'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
    } else {
      $RS_Resultado = SortArray($RS_Resultado,'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
    }
    ShowHTML('<table width="100%">');
    ShowHTML('<tr><td align="right" colspan="2"><hr>');      
    if ($w_embed!='WORD') {
      CabecalhoRelatorio($w_cliente,'Visualização de Calendário',4,$w_chave,null);
    }
    
    ShowHTML('  <td>Período de busca: <b>'.formataDataEdicao($w_inicio).'</b> e <b>'.formataDataEdicao($w_fim).'</b></td>');
    ShowHTML('  <td align="right">Resultados: '.count($RS_Resultado).'</td></tr>');
    ShowHTML('<tr><td align="center" colspan=2>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    if($w_embed != 'WORD'){    
      ShowHTML('          <td width="10">&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Data','mes_ano').'&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Macro-<br>programa','cd_programa').'&nbsp;</td>');
      ShowHTML('          <td><b>&nbsp;'.linkOrdena('Programa','cd_projeto').'&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Item/Evento','titulo').'&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Realizador','sg_setor').'&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Local','local').'&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Ação&nbsp;</td>');
    } else {
      ShowHTML('          <td width="10">&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Data&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Macro-<br>programa&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Programa&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Item/Evento&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Realizador&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Local&nbsp;</td>');
    }      
    ShowHTML('        </tr>');
    $w_cor = $conTrBgColor;
    if (count($RS_Resultado) == 0) {
      ShowHTML('    <tr align="center"><td colspan="8">Nenhum resultado encontrado para os critérios informados.</td>');
    } else {
      foreach ($RS_Resultado as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('    <tr valign="top" bgColor="' . $w_cor . '">');
        if (nvl(f($row, 'sq_projeto_etapa'),'')!='') {
          ShowHTML('      <td bgcolor="'.$conTrBgColorLightYellow2.'">&nbsp;</td>');
        } elseif (f($row, 'sg_tipo_evento')=='REUNIAO') {
          ShowHTML('      <td bgcolor="'.$conTrBgColorLightGreen2.'">&nbsp;</td>');
        } else {
          ShowHTML('      <td bgcolor="'.$conTrBgColorLightBlue1.'">&nbsp;</td>');
        }
        ShowHTML('      <td align="center" width="1%" nowrap>' . Date('d/m/Y', Nvl(f($row, 'mes_ano'), '---')) . '</td>');
        ShowHTML('      <td align="center" width="1%" title="'.f($row, 'nm_programa').'" nowrap>' . Nvl(f($row, 'cd_programa'), '---') . '</td>');
        ShowHTML('      <td align="center" width="1%" title="'.f($row, 'nm_projeto').'" nowrap>' . Nvl(f($row, 'cd_projeto'), '---') . '</td>');
        ShowHTML('      <td>'.((nvl(f($row,'sq_projeto_etapa'),'')!='') ? ExibeImagemSolic('ETAPA',f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row, 'fim_real'),null,null,null,f($row, 'perc_conclusao')).'&nbsp;' : '').f($row, 'titulo'));
        if(nvl($p_descricao,'') != ''){
          ShowHTML('      <br/><b>Descrição:</b><br/>'.crlf2br(nvl(f($row,'descricao'),'---')));                
        }
        if(nvl($p_situacao,'') != '' && nvl(f($row, 'sq_projeto_etapa'),'')!=''){
          ShowHTML('      <br/><b>Situação atual:</b><br/>'.crlf2br(nvl(f($row,'situacao_atual'),'---')));                
        }
        
        if($w_embed != 'WORD'){    
          ShowHTML('      <td align="center">'.ExibeUnidade(null,$w_cliente,f($row,'sg_setor'),f($row,'sq_unidade'),$TP).' </td>');
        }else{
          ShowHTML('      <td align="center">'.f($row,'sg_setor').' </td>');
        }
        ShowHTML('      <td>'.nvl(f($row, 'motivo_insatisfacao'),'---').' </td>');
        if ($w_embed != 'WORD') {
          if (nvl(f($row, 'sq_projeto_etapa'),'')!='') {
            ShowHTML('      <td nowrap><A target="item" class="HL" href="cl_renapi/projeto.php?par=atualizaetapa&R='.$w_pagina.$par.'&O=V&w_chave='.f($row, 'sq_siw_solicitacao').'&w_chave_aux='.f($row, 'sq_projeto_etapa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe dados do item">Exibir</A></td>');
          } else {
            ShowHTML('      <td nowrap><A target="item" class="HL" href="cl_renapi/evento.php?par=visual&R='.$w_pagina.$par.'&O=L&w_tipo=Fecha&w_chave='.f($row, 'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=EVCAD" title="Exibe dados do evento">Exibir</A></td>');
          }
        }
        ShowHTML('    </tr>');
      }
      ShowHTML('  </table>');
      ShowHTML('<tr><td>&nbsp;</td></tr>');
    }
    ShowHTML('</center>');
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  if     ($w_tipo=='PDF')  RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'MESA':          Mesa();         break;
  case 'ARQUIVOS':      Arquivos();   break;
  case 'CALENDARIO':    Calendario();   break;
  case 'ALERTA':        Alerta();       break;
  default:
    Cabecalho();
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  }
}
?>