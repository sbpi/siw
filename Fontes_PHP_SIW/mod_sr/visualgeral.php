<?php 
// =========================================================================
// Rotina de visualiza��o da solicita��o
// -------------------------------------------------------------------------
function VisualGeral($l_chave,$O,$l_usuario,$l_sg,$l_tipo) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da tarefa
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceof($dbms,$l_chave,$l_sg);
  $w_tramite_ativo      = f($RS1,'ativo');
  $l_html.=chr(13).'    <table border=0 width="100%">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>SERVI�O: '.f($RS1,'nome').' ('.f($RS1,'sq_siw_solicitacao').')</b></font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';

  // Exibe a vincula��o
  if (substr(f($RS1,'dados_pai'),0,3)!='---') {
    $l_html.=chr(13).'      <tr><td valign="top" width="20%"><b>Vincula��o: </b></td>';
    $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS1,'sq_solic_pai'),f($RS1,'dados_pai'),'S').'</td></tr>';
  }

  // Se a classifica��o foi informada, exibe.
  if (Nvl(f($RS1,'sq_cc'),'')>'') {
    $l_html .= chr(13).'      <tr><td width="20%"><b>Classifica��o:<b></td>';
    $l_html .= chr(13).'        <td>'.f($RS1,'cc_nome').' </td></tr>';
  }

  if ($l_tipo=='WORD') {
    $l_html.=chr(13).'   <tr><td><b>'.((Nvl(f($RS1,'sigla'),'')=='SRSOLCEL') ? 'Benefici�rio' : 'Solicitante').':</b></font></td>';
    $l_html.=chr(13).'       <td>'.f($RS1,'nm_sol').'</font></td></tr>';
  } else {
    $l_html.=chr(13).'   <tr><td><b>'.((Nvl(f($RS1,'sigla'),'')=='SRSOLCEL') ? 'Benefici�rio' : 'Solicitante').':</b></font></td>';
    $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'solicitante'),$TP,f($RS1,'nm_sol')).'</font></td></tr>';
  }
  if ($l_tipo!='WORD') {
    $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Unidade solicitante:</b></td><td colspan="12">'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS1,'nm_unidade_solic'),f($RS1,'sq_unidade'),$TP).'</td>';
  } else {
    $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Unidade solicitante:</b></td><td colspan="12">'.f($RS1,'nm_unidade_solic').'</td></tr>';
  } 
  if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') {
    $l_html.=chr(13).'   <tr><td width="20%"><b>Procedimento:</b></td>';
    $l_html.=chr(13).'       <td><b>'.f($RS1,'nm_procedimento').'</b></font></tr>';
    $l_html.=chr(13).'   <tr><td width="20%"><b>Data e hora de sa�da:</b></td>';
    $l_html.=chr(13).'       <td><b>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_inicio'),3),0,-3),'-').'</b></font></tr>';
    if (f($RS1,'procedimento')==2) {
      $l_html.=chr(13).'   <tr><td width="20%"><b>Previs�o de retorno:</b></td>';
      $l_html.=chr(13).'       <td><b><b>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</b></td></tr>';
    }
  } elseif (Nvl(f($RS1,'sigla'),'')=='SRSOLCEL') {
    $l_html.=chr(13).'   <tr><td width="20%"><b>Per�odo solicitado:</b></td>';
    $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($RS1,'inicio'),5).' a '.FormataDataEdicao(f($RS1,'fim'),5).'</font></tr>';
  } else {
    switch (f($RS1,'data_hora')) {
    case 1 :
      $l_html.=chr(13).'   <tr><td width="20%"><b>Data programada:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</font></td></tr>';
      break;
    case 2 :
      $l_html.=chr(13).'   <tr><td width="20%"><b>Data programada:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</font></td></tr>';
      break;
    case 3 :
      $l_html.=chr(13).'   <tr><td width="20%"><b>In�cio:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_inicio')),'-').'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td width="20%"><b>T�rmino:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</font></td></tr>';
      break;
    case 4 :
      $l_html.=chr(13).'   <tr><td width="20%"><b>In�cio:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_inicio'),0,-3),3),'-').'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td width="20%"><b>T�rmino:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</font></td></tr>';
      break;
    }
  }

  // Verifica se � necess�rio mostrar o recurso
  $sql = new db_getRecurso; $RS_Recursos = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$w_menu,null,null,null,null,null,'SERVICO');
  if (count($RS_Recursos)) $w_exibe_recurso = true; else $w_exibe_recurso = false;
  if ($w_exibe_recurso) {
    $sql = new db_getSolicRecursos; $RS_Recurso = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS_Recurso as $row) {$RS_Recurso = $row; break;}
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%"><b>Recurso:</b></font></td>';
    if (count($RS_Recurso)) {
      $l_html.=chr(13).'       <td>'.ExibeRecurso($w_dir_volta,$w_cliente,f($RS_Recurso,'nm_recurso'),f($RS_Recurso,'sq_recurso'),$TP,null).'<br>'.f($RS_Recurso,'ds_recurso').'</font></td></tr>';
    } else {
      $l_html.=chr(13).'       <td>N�o informado</font></td></tr>';
    }
  }

  if (Nvl(f($RS1,'descricao'),'')!='') {
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%"><b>Detalhamento:</b></font></td>';
    $l_html.=chr(13).'       <td>'.crlf2br(Nvl(f($RS1,'descricao'),'-')).'</font></td></tr>';
  }
  if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') {
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%"><b>Destino:</b></td>';
    $l_html.=chr(13).'       <td>'.crlf2br(Nvl(f($RS1,'destino'),'-')).'</td></tr>';
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%"><b>Qtd. pessoas:</b></td>';
    $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'qtd_pessoas'),'-').'</td></tr>';
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%"><b>Carga:</b></td>';
    $l_html.=chr(13).'       <td>'.RetornaSimNao(Nvl(f($RS1,'carga'),'-')).'</td></tr>';
  } elseif (Nvl(f($RS1,'sigla'),'')=='SRSOLCEL') {
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%"><b>Destino:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS1,'nm_pais_cel').'</td></tr>';
  }
  if (Nvl(f($RS1,'justificativa'),'')!='') {
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%"><b>Justificativa:</b></font></td>';
    $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'justificativa'),'-').'</font></td></tr>';
  }
  if (nvl(f($RS1,'nm_opiniao'),'')!='') {
    $l_html.=chr(13).'   <tr valign="top"><td><b>Opini�o:</b></font></td><td>'.nvl(f($RS1,'nm_opiniao'),'---').'</font></td></tr>';
  }
  if (nvl(f($RS1,'motivo_insatisfacao'),'')!='') {
    $l_html.=chr(13).'   <tr valign="top"><td><b>Motivo(s) da insatisfa��o:</b></font></td><td>'.crlf2br(nvl(f($RS1,'motivo_insatisfacao'),'---')).'</font></td></tr>';
  }

  // Dados da execu��o, exceto para transporte
  if (f($RS1,'or_tramite')>=3 && Nvl(f($RS1,'sigla'),'')=='SRSOLCEL') {
    $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>DADOS DA EXECUC�O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%"><b>Celular:</b></font></td><td>'.f($RS1,'numero_linha').'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Acess�rios entregues:</b></font></td><td>'.nvl(f($RS1,'acessorios_entregues'),'---').'</font></td></tr>';
    if (nvl(f($RS1,'inicio_real'),'')!='') {
      $l_html.=chr(13).'   <tr><td width="20%"><b>In�cio do empr�stimo:</b></font></td><td>'.formataDataEdicao(f($RS1,'inicio_real'),5);
      if (nvl(f($RS1,'observacao'),'')!='') {
        $l_html.='. Observa��es: '.crlf2br(f($RS1,'observacao'));
      }
    }
    if (nvl(f($RS1,'fim_real'),'')!='') {
      $l_html.=chr(13).'   <tr><td width="20%"><b>T�rmino do empr�stimo:</b></font></td><td>'.formataDataEdicao(f($RS1,'fim_real'),5);
      $l_html.=chr(13).'   <tr><td width="20%"><b>Entrega com pend�ncia de acess�rios? </b></font></td><td>'.retornaSimNao(f($RS1,'pendencia'));
      if (nvl(f($RS1,'acessorios_pendentes'),'')!='') {
        $l_html.=': '.crlf2br(f($RS1,'acessorios_pendentes'));
      }
      if (nvl(f($RS1,'descricao'),'')!='') {
        $l_html.=chr(13).'   <tr><td width="20%"><b>Observa��es sobre a entrega:</b></font></td><td>'.crlf2br(f($RS1,'descricao'));
      }
    }
  } elseif (f($RS1,'or_tramite')>1 && Nvl(f($RS1,'sigla'),'')!='SRTRANSP') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA EXECUC�O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%"><b>Previs�o de t�rmino:</b></font></td><td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</font></td></tr>';
    if (nvl(f($RS1,'conclusao'),'')=='') {
      if (nvl(f($RS1,'valor'),'')>'') {
        $l_html.=chr(13).'   <tr><td width="20%"><b>Valor previsto:</b></font></td><td>'.formatNumber(f($RS1,'valor')).'</font></td></tr>';
      }
      if (nvl(f($RS1,'executor'),'')!='') {
        $l_html.=chr(13).'   <tr><td><b>Executor:</b></font></td><td>'.f($RS1,'nm_exec').'</font></td></tr>';
      }
    } 
  } 
  
  // Dados da conclus�o da solicita��o, se ela estiver nessa situa��o
  if (nvl(f($RS1,'conclusao'),'')!='') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUS�O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr valign="top"><td><b>Data de conclus�o:</b></font></td><td>'.substr(formataDataEdicao(f($RS1,'phpdt_conclusao'),3),0,-3).'</font></td></tr>';
    if ($l_tipo=='WORD') {
      $l_html.=chr(13).'   <tr><td><b>Unidade executora:</b></font></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_exec').'</font></td></tr>';
      if (nvl(f($RS1,'executor'),'')!='') {
        if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') $l_html.=chr(13).'   <tr><td><b>Motorista:</b></font></td>';
        else $l_html.=chr(13).'   <tr><td><b>Executor:</b></font></td>';
        $l_html.=chr(13).'       <td>'.f($RS1,'nm_exec').'</font></td></tr>';
        if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') {
          $l_html.=chr(13).'   <tr><td><b>Ve�culo:</b></font></td>';
          $l_html.=chr(13).'       <td>'.f($RS1,'nm_placa').'</font></td></tr>';
        }
      }
    } else {
      $l_html.=chr(13).'   <tr><td><b>Unidade executora:</b></font></td>';
      $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_exec'),f($RS1,'sq_unid_executora'),$TP).'</font></td></tr>';
      if (nvl(f($RS1,'executor'),'')!='') {
        if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') $l_html.=chr(13).'   <tr><td><b>Motorista:</b></font></td>';
        else $l_html.=chr(13).'   <tr><td><b>Executor:</b></font></td>';
        $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'executor'),$TP,f($RS1,'nm_exec')).'</font></td></tr>';
        if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') {
          $l_html.=chr(13).'   <tr><td><b>Ve�culo:</b></font></td>';
          $l_html.=chr(13).'       <td>'.f($RS1,'nm_placa').'</font></td></tr>';
        }
      }
    } 
    if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') {
      $l_html.=chr(13).'       <tr valign="top"><td><b>Data do atendimento:</td>';
      $l_html.=chr(13).'         <td>Sa�da: '.substr(FormataDataEdicao(f($RS1,'phpdt_horario_saida'),3),0,-3).'<br>Retorno: '.substr(FormataDataEdicao(f($RS1,'phpdt_horario_chegada'),3),0,-3).'<b></font></td></tr>';
      $l_html.=chr(13).'       <tr valign="top"><td><b>Hod�metro:</td>';
      $l_html.=chr(13).'         <td>Sa�da: '.f($RS1,'hodometro_saida').'<br>Retorno:'.f($RS1,'hodometro_chegada').'<b></font></td></tr>';
      $l_html.=chr(13).'       <tr><td><b>Parcial:</td>';
      $l_html.=chr(13).'     <td>'.RetornaSimNao(f($RS1,'parcial')).'</b></td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Passageiro:</b></font></td>';
      $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'recebedor'),$TP,f($RS1,'nm_recebedor')).'</font></td></tr>';
    }
    if (nvl(f($RS1,'observacao'),'')!='') $l_html.=chr(13).'   <tr valign="top"><td><b>Observa��es:</b></font></td><td>'.crlf2br(f($RS1,'observacao')).'</font></td></tr>';
  } 

  $w_erro = ValidaGeral($w_cliente,$l_chave,$l_sg,null,null,null,null);
  if ($w_erro>'') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESULTADO DA VALIDA��O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2">';
    if (substr($w_erro,0,1)=='0') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATEN��O:</b> As pend�ncias abaixo devem ser resolvidas antes do encaminhamento para as fases posteriores � atual.</font>';
    } elseif (substr($w_erro,0,1)=='1') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATEN��O:</b> As pend�ncias abaixo devem ser resolvidas antes do encaminhamento para as fases posteriores � atual. Seu encaminhamento para fases posteriores � atual s� pode ser feito por um gestor do sistema ou do m�dulo de viagens.</font>';
    } else {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATEN��O:</b> Foram identificados os alertas listados abaixo. Eles n�o impedem o encaminhamento para fases posteriores � atual, mas conv�m sua verifica��o.</font>';
    } 
    $l_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $l_html.=chr(13).'  </td></tr>';
  } 

  // Encaminhamentos
  $l_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td colspan=2><table border=0 width="100%"><tr><td>';
  include_once($w_dir_volta.'funcoes/exibeLog.php');
  $l_html .= exibeLog($l_chave,$O,$w_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
  $l_html.=chr(13).'        </table></td></tr>';

  $l_html.=chr(13).'         </table>';
  return $l_html;
}
?>