<?
// =========================================================================
// Rotina de visualização dos dados da viagem
// -------------------------------------------------------------------------
function VisualViagem($l_chave,$l_o,$l_usuario,$l_p1,$l_tipo,$l_identificacao='S',$l_proposto='S',$l_deslocamento='S',
      $l_orcamentaria='S',$l_diaria='S',$l_vinculacao='S',$l_anexo='S',$l_ocorrencia='S') {
  extract($GLOBALS);
  if ($l_tipo!='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  
  // Carrega o segmento do cliente
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente); 
  $w_segmento = f($RS,'segmento');
  
  // Recupera os dados do acordo
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_tramite_ativo  = f($RS,'ativo');
  $w_valor_inicial  = f($RS,'valor');
  $w_fim            = f($RS,'fim_real');
  $w_sg_tramite     = f($RS,'sg_tramite');
  $w_sigla          = f($RS,'sigla');
  $w_aditivo        = f($RS,'aditivo');
  $w_forma_pagamento= f($RS,'sg_forma_pagamento');

  // Recupera o tipo de visão do usuário
  if ($_SESSION['INTERNO']=='N') {
    // Se for usuário externa, tem visão resumida
    $w_tipo_visao=2;
  } elseif (Nvl(f($RS,'solicitante'),0)==$l_usuario || 
    Nvl(f($RS,'executor'),0)==$l_usuario || 
    Nvl(f($RS,'cadastrador'),0)==$l_usuario || 
    Nvl(f($RS,'titular'),0)==$l_usuario || 
    Nvl(f($RS,'substituto'),0)==$l_usuario || 
    Nvl(f($RS,'tit_exec'),0)==$l_usuario || 
    Nvl(f($RS,'subst_exec'),0)==$l_usuario || 
    SolicAcesso($l_chave,$l_usuario)>=8) {
      // Se for solicitante, executor ou cadastrador, tem visão completa
      $w_tipo_visao=0;
  } else {
    if (SolicAcesso($l_chave,$l_usuario)>2) $w_tipo_visao=1;
  } 
  // Se for listagem ou envio, exibe os dados de identificação do acordo
  // Se for listagem dos dados
  if ($l_o=='L' || $l_o=='V') {
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if ($w_segmento=='Públicoxx') { 
      $l_html.=chr(13).'      <tr><td bgcolor="#f0f0f0"><font size="2"><b>PROCESSO: '.nvl(f($RS,'processo'),'---').'<td bgcolor="#f0f0f0" align="right"><font size=2><b>'.f($RS,'codigo_interno').' ('.$l_chave.')</b></font></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0" align=justify><font size="2"><b>'.f($RS,'codigo_interno').' ('.$l_chave.')</b></font></td></tr>';
    }
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    
    // Identificação da viagem
    if ($l_identificacao=='S') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS GERAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      
      // Exibe a vinculação
      $l_html.=chr(13).'      <tr><td valign="top"><b>Vinculação: </b></td>';
      if($l_tipo!='WORD') $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';
      else                $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S').'</td></tr>';
  
      if (nvl(f($RS,'nm_etapa'),'')>'') {
        if (substr($w_sigla,0,3)=='GCB') {   
          $l_html.=chr(13).'      <tr valign="top"><td><b>Modalidade: </b></td>';
          $l_html.=chr(13).'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
        } else { 
          $l_html.=chr(13).'      <tr valign="top"><td><b>Etapa: </b></td>';
          $l_html.=chr(13).'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
        }
      } 
  
      // Se a classificação foi informada, exibe.
      if (Nvl(f($RS,'sq_cc'),'')>'') {
        $l_html .= chr(13).'      <tr><td width="30%"><b>Classificação:<b></td>';
        $l_html .= chr(13).'        <td>'.f($RS,'nm_cc').' </td></tr>';
      }
      
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Objetivo/assunto/evento:</b></td><td>'.crLf2Br(f($RS,'descricao')).'</td></tr>';
      if ($l_tipo!='WORD') {
        $l_html.=chr(13).'      <tr valign="top"><td><b>Unidade proponente:</b></td><td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';
      } else {
        $l_html.=chr(13).'      <tr valign="top"><td><b>Unidade proponente:</b></td><td>'.f($RS,'nm_unidade_resp').'</td></tr>';
      } 
      $l_html.=chr(13).'      <tr valign="top"><td><b>Tipo:</b></td><td>'.f($RS,'nm_tipo_missao').' </b></td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td><b>Período:</b></td><td>'.FormataDataEdicao(f($RS,'inicio')).' a '.FormataDataEdicao(f($RS,'fim')).'</td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td><b>Substituto eventual:</b></td><td>'.nvl(f($RS,'proponente'),'---').' </b></td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td><b>Agenda:</b></td><td>'.nvl(crLf2Br(f($RS,'assunto')),'---').' </b></td></tr>';
      if (Nvl(f($RS,'justificativa_dia_util'),'')>'') {
        // Se o campo de justificativa de dias úteis para estiver preenchido, exibe
        $l_html.=chr(13).'      <tr valign="top"><td><b>Justif. início/término sextas, sábados, domingos e feriados:</b></td><td>'.crLf2Br(f($RS,'justificativa_dia_util')).' </b></td></tr>';
      } 
      if (Nvl(f($RS,'justificativa'),'')>'') {
        // Se o campo de justificativa estiver preenchido, exibe
        $l_html.=chr(13).'      <tr valign="top"><td><b>Justif. pedido com menos de '.f($RS,'dias_antecedencia').' dias:</b></td><td>'.crLf2Br(f($RS,'justificativa')).' </b></td></tr>';
      } 

      // Dados da conclusão da solicitação, se ela estiver nessa situação
      if (Nvl(f($RS,'conclusao'),'')>'') {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ENCERRAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=chr(13).'      <tr><td valign="top" colspan="2">';
        $l_html.=chr(13).'      <tr><td><b>Início da vigência:</b></td>';
        $l_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio')).'</td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Término da vigência:</b></td>';
        $l_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim')).'</td></tr>';
        if ($w_tipo_visao==0 && substr($w_sigla,0,3)!='GCA') {
          $l_html.=chr(13).'    <tr><td><b>Valor realizado:</b></td>';
          $l_html.=chr(13).'      <td>'.formatNumber(f($RS,'valor_atual')).'</td></tr>';
        } 
        if ($w_tipo_visao==0) {
          $l_html.=chr(13).'      <tr><td valign="top"><b>Nota de conclusão:</b></td>';
          $l_html.=chr(13).'          <td>'.nvl(CRLF2BR(f($RS,'observacao')),'---').'</td></tr>';
        } 
      } else {
        // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
        if ($w_tipo_visao!=2 && ($l_o=='L' || $l_o=='T')) {
          if (f($RS,'aviso_prox_conc')=='S') {
            // Configuração dos alertas de proximidade da data limite para conclusão do acordo
            $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ALERTAS DE PROXIMIDADE DA DATA PREVISTA DE TÉRMINO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
            $l_html.=chr(13).'      <tr><td><b>Emite aviso:</b></td>';
            $l_html.=chr(13).'        <td>'.retornaSimNao(f($RS,'aviso_prox_conc')).', a partir de '.formataDataEdicao(f($RS,'aviso')).'.</td></tr>';
          } 
        } 
      }
    }

    // Dados do proposto
    if ($l_proposto='S') {
      $RSQuery = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null);
      
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PROPOSTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (count($RSQuery)==0) {
        $l_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Proposto não informado';
      } else {
        foreach($RSQuery as $row) { 
          $l_html.=chr(13).'      <tr><td colspan=2 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><b>';
          $l_html.=chr(13).'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').') - '.f($row,'cpf').'</b>';
          $l_html.=chr(13).'      <tr><td colspan="2">';
          $l_html.=chr(13).'      <tr><td><b>Sexo:</b></td><td>'.f($row,'nm_sexo').'</td></tr>';
          $l_html.=chr(13).'      <tr><td><b>Matrícula:</b></td><td>'.Nvl(f($row,'matricula'),'---').'</td></tr>';
          $l_html.=chr(13).'      <tr><td><b>Identidade:</b></td><td>'.f($row,'rg_numero').'</td></tr>';
          $l_html.=chr(13).'      <tr><td><b>Data de emissão:</b></td><td>'.FormataDataEdicao(Nvl(f($row,'rg_emissao'),'---')).'</td>';
          $l_html.=chr(13).'      <tr><td><b>Órgão emissor:</b></td><td>'.f($row,'rg_emissor').'</td></tr>';
          $l_html.=chr(13).'      <tr valign="top"><td><b>Telefone:</b></td>'; 
          if (nvl(f($row,'ddd'),'nulo')!='nulo') {
            $l_html.=chr(13).'            <td>('.f($row,'ddd').') '.f($row,'nr_telefone').'</td></tr>';
          } else {
            $l_html.=chr(13).'            <td>---</td></tr>';
          }
          $l_html.=chr(13).'      <tr><td><b>Fax:</b></td><td>'.Nvl(f($row,'nr_fax'),'---').'</td></tr>';
          $l_html.=chr(13).'      <tr><td><b>Celular:</b></td> <td>'.Nvl(f($row,'nr_celular'),'---').'</td></tr>';
          $l_html.=chr(13).'      <tr><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Dados para recebimento das diárias</td>';
          $l_html.=chr(13).'      <tr><td><b>Forma de recebimento:</b></td><td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
          if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
            $l_html.=chr(13).'          <tr><td><b>Banco:</b></td><td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Agência:</b></td><td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
            if (f($RS,'exige_operacao')=='S') $l_html.=chr(13).'          <tr><td><b>Operação:</b></td><td>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
            $l_html.=chr(13).'          <tr><td><b>Número da conta:</b></td><td>'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
          } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
            $l_html.=chr(13).'          <tr><td><b>Banco:</b></td><td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Agência:</b></td><td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
          } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
            $l_html.=chr(13).'          <tr><td><b>Banco:</b></td><td>'.f($RS,'banco_estrang').'</td>';
            $l_html.=chr(13).'          <tr><td><b>ABA Code:</b></td><td>'.Nvl(f($RS,'aba_code'),'---').'</td>';
            $l_html.=chr(13).'          <tr><td><b>SWIFT Code:</b></td><td>'.Nvl(f($RS,'swift_code'),'---').'</td>';
            $l_html.=chr(13).'          <tr><td><b>Endereço da agência:</b></td><td>'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
            $l_html.=chr(13).'          <tr><td><b>Agência:</b></td><td>'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
            $l_html.=chr(13).'          <tr><td><b>Número da conta:</b></td><td>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
            $l_html.=chr(13).'          <tr><td><b>Cidade:</b></td><td>'.f($RS,'cidade_estrang').'</td>';
            $l_html.=chr(13).'          <tr><td><b>País:</b></td><td>'.f($RS,'pais_estrang').'</td>';
            $l_html.=chr(13).'          <tr><td><b>Informações adicionais:</b></td><td>'.nvl(f($RS,'informacoes'),'---').'</td>';
          } 
        } 
      }
    }

    // Vinculações a atividades
    $RS1 = db_getPD_Vinculacao::getInstanceOf($dbms,$l_chave,null,null);
    $RS1 = SortArray($RS1,'inicio','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>VINCULAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Nº</td>';
      $l_html.=chr(13).'          <td><b>Projeto</td>';
      $l_html.=chr(13).'          <td><b>Detalhamento</td>';
      $l_html.=chr(13).'          <td><b>Início</td>';
      $l_html.=chr(13).'          <td><b>Fim</td>';
      $l_html.=chr(13).'          <td><b>Situação</td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_total=0;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td nowrap>';
        if (f($row,'concluida')=='N') {
          if (f($row,'fim')<addDays(time(),-1)) {
            $l_html.=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
          } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
            $l_html.=chr(13).'           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
          } else {
            $l_html.=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
          } 
        } else {
          if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
            $l_html.=chr(13).'           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
          } else {
            $l_html.=chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
          } 
        } 
        if (nvl(f($row,'sq_projeto'),'')=='') {
          $l_html.=chr(13).'        <A class="HL" TARGET="VISUAL" HREF="mod_dm/demanda.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_volta=fecha&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        } else {
          $l_html.=chr(13).'        <A class="HL" TARGET="VISUAL" HREF="projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_volta=fecha&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        }
        $l_html.=chr(13).'        <td>'.nvl(f($row,'nm_projeto'),'---').'</td>';
        if (strlen(Nvl(f($row,'assunto'),'-'))>50) $w_assunto=substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_assunto=Nvl(f($row,'assunto'),'-');
        if (f($row,'sg_tramite')=='CA') {
          $l_html.=chr(13).'        <td title="'.htmlspecialchars(f($row,'assunto')).'"><strike>'.$w_assunto.'</strike></td>';
        } else {
          $l_html.=chr(13).'        <td title="'.htmlspecialchars(f($row,'assunto')).'">'.$w_assunto.'</td>';
        } 
        if (f($row,'concluida')=='N') {
          $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>';
          $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'fim')).'</td>';
        } else {
          $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'inicio_real')).'</td>';
          $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'fim_real')).'</td>';
        } 
        $l_html.=chr(13).'        <td>'.f($row,'nm_tramite').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    } 
    
    // Deslocamentos
    if($l_deslocamento=='S') {
      $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'PDGERAL');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DESLOCAMENTOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Origem</td>';
      $l_html.=chr(13).'          <td><b>Destino</td>';
      $l_html.=chr(13).'          <td><b>Saída</td>';
      $l_html.=chr(13).'          <td><b>Chegada</td>';
      $l_html.=chr(13).'          <td><b>Agenda no<br>dia viagem</td>';
      $l_html.=chr(13).'          <td><b>Transp.</td>';
      $l_html.=chr(13).'          <td><b>Emite<br>bilhete</td>';
      $l_html.=chr(13).'          <td><b>Valor</td>';
      $l_html.=chr(13).'          <td><b>Cia.</td>';
      $l_html.=chr(13).'          <td><b>Vôo</td>';
      $l_html.=chr(13).'        </tr>';
      if (count($RS1)==0) {
        // Se não foram selecionados registros, exibe mensagem 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem 
        $w_tot_bilhete  = 0;
        foreach($RS1 as $row) {
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html.=chr(13).'        <td>'.f($row,'nm_origem').'</td>';
          $l_html.=chr(13).'        <td>'.f($row,'nm_destino').'</td>';
          $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),6),0,-3).'</td>';
          $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),6),0,-3).'</td>';
          $l_html.=chr(13).'        <td align="center">'.f($row,'nm_compromisso').'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'nm_meio_transporte'),'---').'</td>';
          $l_html.=chr(13).'        <td align="center">'.f($row,'nm_passagem').'</td>';
          $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_trecho')).'</td>';
          $l_html.=chr(13).'        <td>'.nvl(f($row,'nm_cia_transporte'),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'codigo_voo'),'&nbsp;').'</td>';
          $l_html.=chr(13).'      </tr>';
          $w_tot_bilhete  += nvl(f($row,'valor_trecho'),0);
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right" colspan=7><b>TOTAL</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.Nvl(formatNumber($w_tot_bilhete),0).'</b></td>';
        $l_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
      } 
      $l_html.=chr(13).'    </table>';
      $l_html.=chr(13).'  </td>';
      $l_html.=chr(13).'</tr>';
    }

    // Diárias
    if($l_diaria=='S') {
      $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'PDDIARIA');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      if (count($RS1)>0) {
        $i = 1;
        foreach($RS1 as $row) {
          $w_trechos[$i][1]  = f($row,'sq_diaria');
          $w_trechos[$i][2]  = f($row,'sq_deslocamento');
          $w_trechos[$i][3]  = f($row,'sq_deslocamento');
          $w_trechos[$i][4]  = f($row,'cidade_dest');
          $w_trechos[$i][5]  = f($row,'nm_destino');
          $w_trechos[$i][6]  = f($row,'phpdt_chegada');
          $w_trechos[$i][7]  = f($row,'phpdt_saida');
          $w_trechos[$i][8]  = Nvl(f($row,'quantidade'),0);
          $w_trechos[$i][9]  = Nvl(f($row,'valor'),0);
          $w_trechos[$i][10] = f($row,'saida');
          $w_trechos[$i][11] = f($row,'chegada');
          $w_trechos[$i][12] = f($row,'diaria');
          $w_trechos[$i][13] = f($row,'sg_moeda_diaria');
          $w_trechos[$i][14] = f($row,'vl_diaria');
          $w_trechos[$i][15] = f($row,'hospedagem');
          $w_trechos[$i][16] = Nvl(f($row,'hospedagem_qtd'),0);
          $w_trechos[$i][17] = Nvl(f($row,'hospedagem_valor'),0);
          $w_trechos[$i][18] = f($row,'sg_moeda_hospedagem');
          $w_trechos[$i][19] = f($row,'vl_diaria_hospedagem');
          $w_trechos[$i][20] = f($row,'veiculo');
          $w_trechos[$i][21] = Nvl(f($row,'veiculo_qtd'),0);
          $w_trechos[$i][22] = Nvl(f($row,'veiculo_valor'),0);
          $w_trechos[$i][23] = f($row,'sg_moeda_veiculo');
          $w_trechos[$i][24] = f($row,'vl_diaria_veiculo');
          $w_trechos[$i][25] = f($row,'sq_valor_diaria');
          $w_trechos[$i][26] = f($row,'sq_diaria_hospedagem');
          $w_trechos[$i][27] = f($row,'sq_diaria_veiculo');
          $w_trechos[$i][28] = f($row,'justificativa_diaria');
          $w_trechos[$i][29] = f($row,'justificativa_veiculo');
          $w_trechos[$i][30] = f($row,'compromisso');
          $w_trechos[$i][31] = f($row,'compromisso');
          $w_trechos[$i][32] = 'N';
          $w_trechos[$i][33] = 'N';
    
          // Cria array para guardar o valor total por moeda
          if ($w_trechos[$i][13]>'') $w_tot_diaria[$w_trechos[$i][13]] = 0;
          if ($w_trechos[$i][18]>'') $w_tot_diaria[$w_trechos[$i][18]] = 0;
          if ($w_trechos[$i][12]>'') $w_tot_diaria[$w_trechos[$i][23]] = 0;
          if ($i==1) {
            // Se a primeira saída for após as 18:00, deduz meia diária
            if (intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_saida'),2)))>180000) {
              $w_trechos[$i][32] = 'S';
            }
          } else {
            // Se a última chegada for até 12:00, deduz meia diária
            if ($i==count($RS1) && intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_chegada'),2)))<=120000) {
              $w_trechos[$i-1][33] = 'S';
            }
            $w_trechos[$i-1][3]  = f($row,'sq_deslocamento');
            $w_trechos[$i-1][7]  = f($row,'phpdt_saida');
            $w_trechos[$i-1][31] = f($row,'compromisso');
          }
          $i += 1;
        } 
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DIÁRIAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=chr(13).'      <tr><td colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'           <td><b>Destino</td>';
        $l_html.=chr(13).'           <td><b>Chegada</td>';
        $l_html.=chr(13).'           <td><b>Saída</td>';
        $l_html.=chr(13).'           <td><b>Item</td>';
        $l_html.=chr(13).'           <td><b>Qtd.</td>';
        $l_html.=chr(13).'           <td><b>$ Unitário</td>';
        $l_html.=chr(13).'           <td><b>$ Total</td>';
        $l_html.=chr(13).'         </tr>';
        $w_cor          = $conTrBgColor;
        $j              = $i;
        $i              = 1;
        $w_diarias      = 0;
        $w_locacoes     = 0;
        $w_hospedagens  = 0;
        $w_tot_local    = 0;
        while($i!=($j-1)) {
          $w_max_hosp     = (toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400;
          $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
          $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
          $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
          
          if ($w_diarias>0)     $w_tot_diaria[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes<>0)   $w_tot_diaria[$w_trechos[$i][23]] += $w_locacoes;
          if ($w_hospedagens>0) $w_tot_diaria[$w_trechos[$i][18]] += $w_hospedagens;
          
          $w_tot_local = $w_diarias + $w_hospedagens + $w_locacoes;
          
          if ($w_tot_local!=0) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            
            // Configura a quantidade de linhas do trecho
            $rowspan = 1;
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
            $rowspan_local = $rowspan;
            if ($w_trechos[$i][28]>'') $rowspan_local += 1;
            if ($w_trechos[$i][29]>'') $rowspan_local += 1;
            
            $l_html.=chr(13).'     <tr valign="top">';
            $l_html.=chr(13).'       <td rowspan="'.$rowspan_local.'"><b>'.$w_trechos[$i][5].'</b>: '.$w_max_hosp;
            if ($w_max_hosp==1) $l_html.=chr(13).' dia'; else $l_html.=chr(13).' dias';
            if ($w_trechos[$i][32]=='S') $l_html.=chr(13).'<br>Saída após  as 18:00';
            if ($w_trechos[$i][33]=='S') $l_html.=chr(13).'<br>Chegada até 12:00';
            if ($w_trechos[$i][30]=='N') $l_html.=chr(13).'<br>Sem compromisso na ida';
            if ($w_trechos[$i][31]=='N') $l_html.=chr(13).'<br>Sem compromisso na volta';
            $l_html.=chr(13).'<br>'.$w_trechos[$i][13].' '.formatNumber($w_tot_local);
            $l_html.=chr(13).'       <td align="center" rowspan="'.$rowspan.'">'.substr(FormataDataEdicao($w_trechos[$i][6],4),0,-3).'</td>';
            $l_html.=chr(13).'       <td align="center" rowspan="'.$rowspan.'">'.substr(FormataDataEdicao($w_trechos[$i][7],4),0,-3).'</td>';
            if ($w_trechos[$i][25]>'' && nvl(f($RS,'diaria'),'')!='') {
              $l_html.=chr(13).'         <td>Diária ('.$w_trechos[$i][13].')</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][8],1).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][9]).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_diarias,2).'</td>';
              $l_html.=chr(13).'       </tr>';
            }
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0) {
              $l_html.=chr(13).'       <tr valign="top">';
              $l_html.=chr(13).'         <td>Veículo ('.$w_trechos[$i][23].') -'.formatNumber($w_trechos[$i][24],0).'%</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][21],1).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber(-1*$w_trechos[$i][9]*$w_trechos[$i][22]/100).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_locacoes,2).'</td>';
              $l_html.=chr(13).'       </tr>';
            }
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) {
              $l_html.=chr(13).'       <tr valign="top">';
              $l_html.=chr(13).'         <td>Hospedagem ('.$w_trechos[$i][18].')</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][16],1).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][17]).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_hospedagens,2).'</td>';
              $l_html.=chr(13).'       </tr>';
            }
            if ($w_trechos[$i][28]>'' || $w_trechos[$i][29]>'') {
              if ($w_trechos[$i][28]>'') {
                $l_html.=chr(13).'       <tr valign="top">';
                $l_html.=chr(13).'         <td colspan=6>Justificativa para diária acima do permitido: '.crLf2Br($w_trechos[$i][28]).'</td>';
                $l_html.=chr(13).'       </tr>';
              }
              if ($w_trechos[$i][29]>'') {
                $l_html.=chr(13).'       <tr valign="top">';
                $l_html.=chr(13).'         <td colspan=6>Justificativa para locação de veículo: '.crLf2Br($w_trechos[$i][29]).'</td>';
                $l_html.=chr(13).'       </tr>';
              }
            }
          }
          $i += 1;
        }
        $l_html.=chr(13).'     <tr bgcolor="'.$conTrBgColor.'"><td colspan="7" align="center"><b>TOTAL:';
        foreach($w_tot_diaria as $k => $v) {
          $l_html.=chr(13).'       &nbsp;&nbsp;&nbsp;&nbsp;'.$k.' '.formatNumber($v);
        }
        $l_html.=chr(13).'     </b></td></tr>';
        $l_html.=chr(13).'        </table></td></tr>';
      }
    }

    // Bilhete de passagem
    $RS1 = db_getPD_Bilhete::getInstanceOf($dbms,$l_chave,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'data','asc', 'nm_cia_transporte', 'asc', 'numero', 'asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>BILHETES EMITIDOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td rowspan=2><b>Emissão</td>';
      $l_html.=chr(13).'          <td rowspan=2><b>Cia.</td>';
      $l_html.=chr(13).'          <td rowspan=2><b>Número</td>';
      $l_html.=chr(13).'          <td rowspan=2><b>Trecho</td>';
      $l_html.=chr(13).'          <td rowspan=2><b>RLOC</td>';
      $l_html.=chr(13).'          <td rowspan=2><b>Classe</td>';
      $l_html.=chr(13).'          <td colspan=4><b>Valores</td>';
      $l_html.=chr(13).'        </tr>';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Bilhete</td>';
      $l_html.=chr(13).'          <td><b>Embarque</td>';
      $l_html.=chr(13).'          <td><b>PTA</td>';
      $l_html.=chr(13).'          <td><b>Total</td>';
      $l_html.=chr(13).'        </tr>';
      $w_cor=$conTrBgColor;
      $i             = 1;
      $w_tot_bil     = 0;
      $w_tot_pta     = 0;
      $w_tot_tax     = 0;
      $w_tot_bilhete = 0;
      $w_total       = 0;
      foreach ($RS1 as $row) {
        $w_tot_bilhete = f($row,'valor_bilhete')+formatNumber(f($row,'valor_pta'))+formatNumber(f($row,'valor_taxa_embarque'));
        $w_tot_bil     += f($row,'valor_bilhete');
        $w_tot_pta     += f($row,'valor_pta');
        $w_tot_tax     += f($row,'valor_taxa_embarque');
        $w_total       += $w_tot_bilhete;
        $l_html.=chr(13).'        <tr valign="middle">';
        $l_html.=chr(13).'           <td align="center">'.FormataDataEdicao(f($row,'data'),5).'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'nm_cia_transporte').'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'numero').'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'trecho').'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'rloc').'</td>';
        $l_html.=chr(13).'           <td align="center">'.f($row,'classe').'</td>';
        $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_bilhete')).'</td>';
        $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_taxa_embarque')).'</td>';
        $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_pta')).'</td>';
        $l_html.=chr(13).'           <td align="right">'.formatNumber($w_tot_bilhete).'</td>';
        $l_html.=chr(13).'        </tr>';
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right" colspan="6"><b>TOTAIS</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_bil).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_tax).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_pta).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    } 
  
    if (1==1) {
    //if ($w_tipo_visao!=2) {
      // Previsão orçamentária-financeira

      // Prepara array de impressão dos dados orçamentários
      $RS_Financ = db_getPD_Financeiro::getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,'ORCAM_PREV');
      $RS_Financ = SortArray($RS_Financ,'cd_rubrica','asc','sg_moeda','asc');
      if (count($RS_Financ)>0) {
        $i       = -1;
        $w_atual = '';
        foreach($RS_Financ as $row) {
          if ($w_atual!=f($row,'cd_rubrica')) {
            $w_atual = f($row,'cd_rubrica');
            $i++;
          }
          $w_orc[$i]['sq_rubrica'] = f($row,'sq_rubrica');
          $w_orc[$i]['cd_rubrica'] = f($row,'cd_rubrica');
          $w_orc[$i]['nm_rubrica'] = f($row,'nm_rubrica');
          $w_orc[$i]['moeda'][f($row,'sg_moeda')] = f($row,'valor');
          if (!is_array($w_orc_moeda[f($row,'sg_moeda')])) {
            $w_orc_moeda[f($row,'sg_moeda')] = f($row,'valor');
          } else {
            $w_orc_moeda[f($row,'sg_moeda')] += f($row,'valor');
          }
        }
      }
      // Prepara array de impressão dos dados financeiros
      $RS_Financ = db_getPD_Financeiro::getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,'FINANC_PREV');
      $RS_Financ = SortArray($RS_Financ,'nm_lancamento','asc','sg_moeda','asc');
      if (count($RS_Financ)>0) {
        $i       = -1;
        $w_atual = '';
        foreach($RS_Financ as $row) {
          if ($w_atual!=f($row,'nm_lancamento')) {
            $w_atual = f($row,'nm_lancamento');
            $i++;
          }
          $w_fin[$i]['sq_lancamento'] = f($row,'sq_lancamento');
          $w_fin[$i]['nm_lancamento'] = f($row,'nm_lancamento');
          $w_fin[$i]['moeda'][f($row,'sg_moeda')] = f($row,'valor');
          if (!is_array($w_fin_moeda[f($row,'sg_moeda')])) {
            $w_fin_moeda[f($row,'sg_moeda')] = f($row,'valor');
          } else {
            $w_fin_moeda[f($row,'sg_moeda')] += f($row,'valor');
          }
        }
      }

      if (count($w_orc)>0 || count($w_fin)>0) {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PREVISÃO ORÇAMENTÁRIA-FINANCEIRA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan=2><table width="100%" border=0 cellpadding=0 cellspacing=5><tr valign="top">';
        
        // Exibe previsão orçamentária
        $l_html.=chr(13).'        <td align="center" width="50%"><table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr align="center">';
        if (count($w_orc_moeda)==1) {
          $l_html.=chr(13).'          <td colspan="2" bgColor="#f0f0f0"><b>ORÇAMENTÁRIO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Rubrica</b></td>';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Valor</b></td>';
        } else {
          $l_html.=chr(13).'          <td colspan="'.(1+(2*count($w_orc_moeda))).'" bgColor="#f0f0f0"><b>ORÇAMENTÁRIO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td rowspan="2" bgColor="#f0f0f0"><b>Rubrica</b></td>';
          $l_html.=chr(13).'          <td colspan="'.(2*count($w_orc_moeda)).'" bgColor="#f0f0f0"><b>Valor</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          foreach($w_orc_moeda as $k=>$v) $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>'.$k.'</b></td>';
          $l_html.=chr(13).'          </tr>';
        }
        $l_html.=chr(13).'          </tr>';
        $w_cor=$w_TrBgColor;
        $i = 0;
        while ($i<count($w_orc)) {
          $l_html.=chr(13).'      <tr valign="top">';
          if ($l_tipo!='WORD') $l_html .= chr(13).'          <td><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.$w_orc[$i]['cd_rubrica'].' - '.$w_orc[$i]['nm_rubrica'].'</A>&nbsp';
          else                 $l_html .= chr(13).'          <td>'.$w_orc[$i]['cd_rubrica'].' - '.$w_orc[$i]['nm_rubrica'].'&nbsp';
          foreach($w_orc_moeda as $k=>$v) $l_html.=chr(13).'          <td align="right">'.formatNumber($w_orc[$i]['moeda'][$k]).'</td>';
          $l_html.=chr(13).'      </tr>';
          $i++;
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right"><b>TOTAIS</b></td>';
        foreach($w_orc_moeda as $k=>$v) $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($v).'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td>';

        // Exibe previsão financeira
        $l_html.=chr(13).'        <td align="center" width="50%"><table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr align="center">';
        if (count($w_fin_moeda)==1) {
          $l_html.=chr(13).'          <td colspan="2" bgColor="#f0f0f0"><b>FINANCEIRO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Rubrica</b></td>';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Valor</b></td>';
        } else {
          $l_html.=chr(13).'          <td colspan="'.(1+(2*count($w_fin_moeda))).'" bgColor="#f0f0f0"><b>FINANCEIRO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td rowspan="2" bgColor="#f0f0f0"><b>Classificação</b></td>';
          $l_html.=chr(13).'          <td colspan="'.(2*count($w_fin_moeda)).'" bgColor="#f0f0f0"><b>Valor</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          foreach($w_fin_moeda as $k=>$v) $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>'.$k.'</b></td>';
          $l_html.=chr(13).'          </tr>';
        }
        $l_html.=chr(13).'          </tr>';
        $w_cor=$w_TrBgColor;
        $i = 0;
        while ($i<count($w_fin)) {
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html .= chr(13).'          <td>'.$w_fin[$i]['nm_lancamento'].'&nbsp';
          foreach($w_fin_moeda as $k=>$v) $l_html.=chr(13).'          <td align="right">'.formatNumber($w_fin[$i]['moeda'][$k]).'</td>';
          $l_html.=chr(13).'      </tr>';
          $i++;
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right"><b>TOTAIS</b></td>';
        foreach($w_fin_moeda as $k=>$v) $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($v).'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td></tr>';
        $l_html.=chr(13).'       </table></td></tr>';
      } 
      
      // Arquivos vinculados
      $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS = SortArray($RS,'nome','asc');
      if (count($RS)>0) {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan="2" align="center">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr align="center">';
        $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Título</b></td>';
        $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Descrição</b></td>';
        $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Tipo</b></td>';
        $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>KB</b></td>';
        $l_html.=chr(13).'          </tr>';
        $w_cor=$w_TrBgColor;
        foreach($RS as $row) {
          $l_html.=chr(13).'      <tr valign="top">';
          if ($l_tipo!='WORD') {
            $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
          } else {
            $l_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
          } 
          $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
          $l_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
          $l_html.=chr(13).'        <td align="right">'.(round(f($row,'tamanho')/1024,1)).'&nbsp;</td>';
          $l_html.=chr(13).'      </tr>';
        } 
        $l_html.=chr(13).'         </table></td></tr>';
      } 
    } 
    
    // Arquivos gerados para a PCD
    if ($l_anexo = 'S' && $w_or_tramite > 4) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center"><tr bgcolor="'.$w_TrBgColor.'"><td><a target="Emissao" class="hl" title="Emitir autorização e proposta de concessão." href="'.$w_dir.$w_pagina.'Emissao&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'">Autorização para emissão de bilhetes</A>';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center"><td><a target="Relatorio" class="hl" title="Emitir relatório para prestacao de contas." href="'.$w_dir.$w_pagina.'Prestacaocontas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Relatório de viagem</A>';
      $l_html.=chr(13).'      </table></td></tr>';
    }
  }
   
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaViagem($w_cliente,$l_chave,'PDGERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $l_html.=chr(13).'<tr><td colspan=2><HR>';
    $l_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td colspan=2>';
    if (substr($w_erro,0,1)=='0') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual.</font>';
    } elseif (substr($w_erro,0,1)=='1') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos.</font>';
    } else {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.</font>';
    } 
    $l_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $l_html.=chr(13).'  </td></tr>';
  } 

  // Encaminhamentos
  if ($l_ocorrencia=='S') {
    include_once($w_dir_volta.'funcoes/exibeLog.php');
    $l_html .= exibeLog($l_chave,$l_o,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
  } 
  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
}
?>