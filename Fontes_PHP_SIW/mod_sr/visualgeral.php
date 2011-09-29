<?php 
// =========================================================================
// Rotina de visualização da solicitação
// -------------------------------------------------------------------------
function VisualGeral($l_chave,$O,$l_usuario,$l_sg,$l_tipo) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da tarefa
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceof($dbms,$l_chave,$l_sg);
  $w_tramite_ativo      = f($RS1,'ativo');
  $l_html.=chr(13).'    <table border=0 width="100%">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>SERVIÇO: '.f($RS1,'nome').' ('.f($RS1,'sq_siw_solicitacao').')</b></font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';

  // Exibe a vinculação
  if (substr(f($RS1,'dados_pai'),0,3)!='---') {
    $l_html.=chr(13).'      <tr><td valign="top" width="20%"><b>Vinculação: </b></td>';
    $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS1,'sq_solic_pai'),f($RS1,'dados_pai'),'S').'</td></tr>';
  }

  // Se a classificação foi informada, exibe.
  if (Nvl(f($RS1,'sq_cc'),'')>'') {
    $l_html .= chr(13).'      <tr><td width="20%"><b>Classificação:<b></td>';
    $l_html .= chr(13).'        <td>'.f($RS1,'cc_nome').' </td></tr>';
  }

  if ($l_tipo=='WORD') {
    $l_html.=chr(13).'   <tr><td><b>'.((Nvl(f($RS1,'sigla'),'')=='SRSOLCEL') ? 'Beneficiário' : 'Solicitante').':</b></font></td>';
    $l_html.=chr(13).'       <td>'.f($RS1,'nm_sol').'</font></td></tr>';
  } else {
    $l_html.=chr(13).'   <tr><td><b>'.((Nvl(f($RS1,'sigla'),'')=='SRSOLCEL') ? 'Beneficiário' : 'Solicitante').':</b></font></td>';
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
    $l_html.=chr(13).'   <tr><td width="20%"><b>Data e hora de saída:</b></td>';
    $l_html.=chr(13).'       <td><b>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_inicio'),3),0,-3),'-').'</b></font></tr>';
    if (f($RS1,'procedimento')==2) {
      $l_html.=chr(13).'   <tr><td width="20%"><b>Previsão de retorno:</b></td>';
      $l_html.=chr(13).'       <td><b><b>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</b></td></tr>';
    }
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
      $l_html.=chr(13).'   <tr><td width="20%"><b>Início:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_inicio')),'-').'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td width="20%"><b>Término:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</font></td></tr>';
      break;
    case 4 :
      $l_html.=chr(13).'   <tr><td width="20%"><b>Início:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_inicio'),0,-3),3),'-').'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td width="20%"><b>Término:</b></font></td>';
      $l_html.=chr(13).'       <td>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</font></td></tr>';
      break;
    }
  }

  // Verifica se é necessário mostrar o recurso
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
      $l_html.=chr(13).'       <td>Não informado</font></td></tr>';
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
    $l_html.=chr(13).'   <tr valign="top"><td><b>Opinião:</b></font></td><td>'.nvl(f($RS1,'nm_opiniao'),'---').'</font></td></tr>';
  }
  if (nvl(f($RS1,'motivo_insatisfacao'),'')!='') {
    $l_html.=chr(13).'   <tr valign="top"><td><b>Motivo(s) da insatisfação:</b></font></td><td>'.crlf2br(nvl(f($RS1,'motivo_insatisfacao'),'---')).'</font></td></tr>';
  }

  // Dados da execução, exceto para transporte
  if (f($RS1,'or_tramite')>1 && Nvl(f($RS1,'sigla'),'')!='SRTRANSP') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA EXECUCÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%"><b>Previsão de término:</b></font></td><td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</font></td></tr>';
    if (nvl(f($RS1,'conclusao'),'')=='') {
      if (nvl(f($RS1,'valor'),'')>'') {
        $l_html.=chr(13).'   <tr><td width="20%"><b>Valor previsto:</b></font></td><td>'.formatNumber(f($RS1,'valor')).'</font></td></tr>';
      }
      if (nvl(f($RS1,'executor'),'')!='') {
        $l_html.=chr(13).'   <tr><td><b>Executor:</b></font></td><td>'.f($RS1,'nm_exec').'</font></td></tr>';
      }
    } 
  } 
  
  // Dados da conclusão da solicitação, se ela estiver nessa situação
  if (nvl(f($RS1,'conclusao'),'')!='') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr valign="top"><td><b>Data de conclusão:</b></font></td><td>'.substr(formataDataEdicao(f($RS1,'phpdt_conclusao'),3),0,-3).'</font></td></tr>';
    if ($l_tipo=='WORD') {
      $l_html.=chr(13).'   <tr><td><b>Unidade executora:</b></font></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_exec').'</font></td></tr>';
      if (nvl(f($RS1,'executor'),'')!='') {
        if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') $l_html.=chr(13).'   <tr><td><b>Motorista:</b></font></td>';
        else $l_html.=chr(13).'   <tr><td><b>Executor:</b></font></td>';
        $l_html.=chr(13).'       <td>'.f($RS1,'nm_exec').'</font></td></tr>';
        if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') {
          $l_html.=chr(13).'   <tr><td><b>Veículo:</b></font></td>';
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
          $l_html.=chr(13).'   <tr><td><b>Veículo:</b></font></td>';
          $l_html.=chr(13).'       <td>'.f($RS1,'nm_placa').'</font></td></tr>';
        }
      }
    } 
    if (Nvl(f($RS1,'sigla'),'')=='SRTRANSP') {
      $l_html.=chr(13).'       <tr valign="top"><td><b>Data do atendimento:</td>';
      $l_html.=chr(13).'         <td>Saída: '.substr(FormataDataEdicao(f($RS1,'phpdt_horario_saida'),3),0,-3).'<br>Retorno: '.substr(FormataDataEdicao(f($RS1,'phpdt_horario_chegada'),3),0,-3).'<b></font></td></tr>';
      $l_html.=chr(13).'       <tr valign="top"><td><b>Hodômetro:</td>';
      $l_html.=chr(13).'         <td>Saída: '.f($RS1,'hodometro_saida').'<br>Retorno:'.f($RS1,'hodometro_chegada').'<b></font></td></tr>';
      $l_html.=chr(13).'       <tr><td><b>Parcial:</td>';
      $l_html.=chr(13).'     <td>'.RetornaSimNao(f($RS1,'parcial')).'</b></td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Passageiro:</b></font></td>';
      $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'recebedor'),$TP,f($RS1,'nm_recebedor')).'</font></td></tr>';
    }
    if (nvl(f($RS1,'observacao'),'')!='') $l_html.=chr(13).'   <tr valign="top"><td><b>Observações:</b></font></td><td>'.crlf2br(f($RS1,'observacao')).'</font></td></tr>';
  } 
  // Encaminhamentos
  $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
  $sql = new db_getSolicLog; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,null,'LISTA');
  $RS1 = SortArray($RS1,'phpdt_data','desc','sq_siw_solic_log','desc');
  $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
  $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
  $l_html.=chr(13).'       <tr valign="top">';
  $l_html.=chr(13).'         <td align="center"><b>Data</b></td>';
  $l_html.=chr(13).'         <td align="center"><b>Ocorrência/Anotação</b></td>';
  $l_html.=chr(13).'         <td align="center"><b>Responsável</b></td>';
  $l_html.=chr(13).'         <td align="center"><b>Fase</b></td>';
  $l_html.=chr(13).'       </tr>';
  $i=0;
  if (count($RS1)==0) {
    $l_html.=chr(13).'      <tr><td colspan=4 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
  } else {
    $i = 0;
    foreach ($RS1 as $row1) {
      $l_html.=chr(13).'      <tr valign="top">';
      if ($i==0) {
        $l_html.=chr(13).'     <td colspan=4>Fase atual: <b>'.f($row1,'fase').'</b></td></tr>';
        if ($w_tramite_ativo=='S') {
          // Recupera os responsáveis pelo tramite
          $sql = new db_getTramiteResp; $RS2 = $sql->getInstanceOf($dbms,$l_chave,null,null);
          $l_html .= chr(13).'      <tr bgcolor="'.$w_TrBgColor.'" valign="top">';
          $l_html .= chr(13).'        <td colspan=4>Responsáveis pelo trâmite: <b>';
          if (count($RS2)>0) {
            $j = 0;
            foreach($RS2 as $row2) {
              if ($j==0) {
                $w_tramite_resp = f($row2,'nome_resumido');
                if ($l_tipo!='WORD') $l_html .= chr(13).ExibePessoa($w_dir_volta,$w_cliente,f($row2,'sq_pessoa'),$TP,f($row2,'nome_resumido'));
                else          $l_html .= chr(13).$w_tramite_resp;
                $j = 1;
              } else {
                if (strpos($w_tramite_resp,f($row,'nome_resumido'))===false) {
                  if ($l_tipo!='WORD') $l_html .= chr(13).', '.ExibePessoa($w_dir_volta,$w_cliente,f($row2,'sq_pessoa'),$TP,f($row2,'nome_resumido'));
                  else                 $l_html .= chr(13).', '.f($row2,'nome_resumido');
                }
                $w_tramite_resp .= f($row2,'nome_resumido');
              }
            } 
          } 
          $l_html .= chr(13).'</b></td>';
        } 
        $l_html.=chr(13).'      <tr valign="top">';
        $i=1;
      }
      $l_html.=chr(13).'        <td nowrap align="center">'.FormataDataEdicao(f($row1,'phpdt_data'),3).'</td>';
      if (Nvl(f($row1,'caminho'),'')>'') {
        $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row1,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row1,'tipo').' - '.round(f($row1,'tamanho')/1024,1).' KB',null)).'</td>';
      } else {
        $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'despacho'),'---')).'</td>';
      }
      if($l_tipo!='WORD') $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'responsavel')).'</td>';
      else                $l_html.=chr(13).'        <td nowrap>'.f($row1,'responsavel').'</td>';
      if ((Nvl(f($row1,'chave_log'),'')>'')  && (Nvl(f($row1,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
      else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row1,'tramite'),'---').'</td>';
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  }
  $l_html.=chr(13).'         </table>';
  return $l_html;
}
?>