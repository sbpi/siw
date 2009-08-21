<?php
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
  $w_or_tramite      = f($RS,'or_tramite');
  $w_tramite         = f($RS,'sq_siw_tramite');
  $w_tramite_ativo   = f($RS,'ativo');
  $w_valor_inicial   = f($RS,'valor');
  $w_fim             = f($RS,'fim_real');
  $w_sg_tramite      = f($RS,'sg_tramite');
  $w_sigla           = f($RS,'sigla');
  $w_aditivo         = f($RS,'aditivo');
  $w_forma_pagamento = f($RS,'sg_forma_pagamento');
  $w_internacional   = f($RS,'internacional');

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
      if(nvl(f($RS,'sq_solic_pai'),'')!='' && f($RS,'ativo')=='S') {
        // Exibe saldos das rubricas
        $RS_Fin = db_getPD_Financeiro::getInstanceOf($dbms,$w_cliente,null,f($RS,'sq_solic_pai'),null,null,null,null,null,null,null,null,'ORCAM_SIT');
        $RS_Fin = SortArray($RS_Fin,'cd_rubrica','asc','nm_rubrica','asc','nm_lancamento','asc');
        $l_html.=chr(13).'      <tr valign="top"><td><b>Disponibilidade orçamentária:</b></td>';
        $l_html.=chr(13).'      <td><table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td><b>Rubrica</td>';
        $l_html.=chr(13).'          <td><b>Descrição</td>';
        $l_html.=chr(13).'          <td><b>% Executado</td>';
        $l_html.=chr(13).'         <td><b>Saldo (R$)</td>';
        $l_html.=chr(13).'        </tr>';
        if (count($RS_Fin)<=0) {
          $l_html.=chr(13).'      <tr><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>';
        } else {
          foreach ($RS_Fin as $row) {
            $l_html.=chr(13).'      <tr valign="top">';
            $l_html.=chr(13).'        <td>'.Nvl(f($row,'cd_rubrica'),'&nbsp;').'&nbsp;'.Nvl(f($row,'nm_rubrica'),'&nbsp;').'</td>';
            $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'&nbsp;').'</td>';
            $l_html.=chr(13).'        <td align="center">'.formatNumber(f($row,'perc_exec'))  .'</td>';
            $l_html.=chr(13).'        <td align="center">'.formatNumber(f($row,'saldo')).'</td>';
            $l_html.=chr(13).'      </tr>';
          } 
        } 
        $l_html.=chr(13).'      </center>';
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'  </td>';
        $l_html.=chr(13).'</tr>';
      }
      
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
      $l_html.=chr(13).'      <tr valign="top"><td><b>Período:</b></td><td>'.FormataDataEdicao(f($RS,'inicio')).' a '.FormataDataEdicao(f($RS,'fim')).'</td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td><b>Categoria da diária:</b></td><td>'.f($RS,'nm_diaria').' </b></td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td><b>Contato na ausência:</b></td><td>'.nvl(f($RS,'proponente'),'---').' </b></td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td><b>Agenda:</b></td><td>'.nvl(crLf2Br(f($RS,'assunto')),'---').' </b></td></tr>';
      if (Nvl(f($RS,'justificativa_dia_util'),'')>'') {
        // Se o campo de justificativa de dias úteis para estiver preenchido, exibe
        $l_html.=chr(13).'      <tr valign="top"><td><b>Justif. viagem contendo fim de semana/feriado:</b></td><td>'.crLf2Br(f($RS,'justificativa_dia_util')).' </b></td></tr>';
      } 
      if (Nvl(f($RS,'justificativa'),'')>'') {
        // Se o campo de justificativa estiver preenchido, exibe
        $l_html.=chr(13).'      <tr valign="top"><td><b>Justif. pedido com menos de '.f($RS,'dias_antecedencia').' dias:</b></td><td>'.crLf2Br(f($RS,'justificativa')).' </b></td></tr>';
      } 
    }

    // Dados do proposto
    if ($l_proposto='S') {
      $RSQuery = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null);
      
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>BENEFICIÁRIO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (count($RSQuery)==0) {
        $l_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Beneficiário não informado';
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
      $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'S','PDGERAL');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ROTEIRO PREVISTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Origem</td>';
      $l_html.=chr(13).'          <td><b>Aeroporto</td>';
      $l_html.=chr(13).'          <td><b>Destino</td>';
      $l_html.=chr(13).'          <td><b>Aeroporto</td>';
      $l_html.=chr(13).'          <td><b>Saída</td>';
      $l_html.=chr(13).'          <td><b>Chegada</td>';
      $l_html.=chr(13).'          <td><b>Agenda no<br>dia viagem</td>';
      $l_html.=chr(13).'          <td><b>Transp.</td>';
      $l_html.=chr(13).'          <td><b>Emite<br>bilhete</td>';
      //$l_html.=chr(13).'          <td><b>Valor</td>';
      //$l_html.=chr(13).'          <td><b>Cia.</td>';
      //$l_html.=chr(13).'          <td><b>Vôo</td>';
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
          $l_html.=chr(13).'        <td>'.nvl(f($row,'aeroporto_origem'),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td>'.f($row,'nm_destino').'</td>';
          $l_html.=chr(13).'        <td>'.nvl(f($row,'aeroporto_destino'),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),6),0,-3).'</td>';
          $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),6),0,-3).'</td>';
          $l_html.=chr(13).'        <td align="center">'.f($row,'nm_compromisso').'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'nm_meio_transporte'),'---').'</td>';
          $l_html.=chr(13).'        <td align="center">'.f($row,'nm_passagem').'</td>';
          //$l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_trecho')).'</td>';
          //$l_html.=chr(13).'        <td>'.nvl(f($row,'nm_cia_transporte'),'&nbsp;').'</td>';
          //$l_html.=chr(13).'        <td align="center">'.nvl(f($row,'codigo_voo'),'&nbsp;').'</td>';
          $l_html.=chr(13).'      </tr>';
          $w_tot_bilhete  += nvl(f($row,'valor_trecho'),0);
        } 
        //$l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        //$l_html.=chr(13).'        <td align="right" colspan=7><b>TOTAL</b></td>';
        //$l_html.=chr(13).'        <td align="right"><b>'.Nvl(formatNumber($w_tot_bilhete),0).'</b></td>';
        //$l_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
      } 
      $l_html.=chr(13).'    </table>';
      $l_html.=chr(13).'  </td>';
      $l_html.=chr(13).'</tr>';

      if (f($RS,'cumprimento')!='C' && f($RS,'cumprimento')!='N') {
        $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'P','PDGERAL');
        $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ROTEIRO REALIZADO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=chr(13).'      <tr><td colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td><b>Origem</td>';
        $l_html.=chr(13).'          <td><b>Aeroporto</td>';
        $l_html.=chr(13).'          <td><b>Destino</td>';
        $l_html.=chr(13).'          <td><b>Aeroporto</td>';
        $l_html.=chr(13).'          <td><b>Saída</td>';
        $l_html.=chr(13).'          <td><b>Chegada</td>';
        $l_html.=chr(13).'          <td><b>Agenda no<br>dia viagem</td>';
        $l_html.=chr(13).'          <td><b>Transp.</td>';
        $l_html.=chr(13).'          <td><b>Emite<br>bilhete</td>';
        //$l_html.=chr(13).'          <td><b>Valor</td>';
        //$l_html.=chr(13).'          <td><b>Cia.</td>';
        //$l_html.=chr(13).'          <td><b>Vôo</td>';
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
            $l_html.=chr(13).'        <td>'.nvl(f($row,'aeroporto_origem'),'&nbsp;').'</td>';
            $l_html.=chr(13).'        <td>'.f($row,'nm_destino').'</td>';
            $l_html.=chr(13).'        <td>'.nvl(f($row,'aeroporto_destino'),'&nbsp;').'</td>';
            $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),6),0,-3).'</td>';
            $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),6),0,-3).'</td>';
            $l_html.=chr(13).'        <td align="center">'.f($row,'nm_compromisso').'</td>';
            $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'nm_meio_transporte'),'---').'</td>';
            $l_html.=chr(13).'        <td align="center">'.f($row,'nm_passagem').'</td>';
            //$l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_trecho')).'</td>';
            //$l_html.=chr(13).'        <td>'.nvl(f($row,'nm_cia_transporte'),'&nbsp;').'</td>';
            //$l_html.=chr(13).'        <td align="center">'.nvl(f($row,'codigo_voo'),'&nbsp;').'</td>';
            $l_html.=chr(13).'      </tr>';
            $w_tot_bilhete  += nvl(f($row,'valor_trecho'),0);
          } 
          //$l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
          //$l_html.=chr(13).'        <td align="right" colspan=7><b>TOTAL</b></td>';
          //$l_html.=chr(13).'        <td align="right"><b>'.Nvl(formatNumber($w_tot_bilhete),0).'</b></td>';
          //$l_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
        } 
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'  </td>';
        $l_html.=chr(13).'</tr>';
      }
    }

    // Diárias, hospedagens e veículos
    if($l_diaria=='S') {
      $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'S','PDDIARIA');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      $i = 0;
      foreach($RS1 as $row) {
        if ($i==0) $w_inicio = f($row,'saida');
        $w_fim = f($row,'chegada');
        $i++;
      }
      reset($RS1);
      $i = 1;
      if (count($RS1)>0) {
        foreach($RS1 as $row) {
          //if (nvl(f($row,'sq_diaria'),0)>0) {
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
            $w_trechos[$i][34] = formataDataEdicao(f($row,'hospedagem_checkin'));
            $w_trechos[$i][35] = formataDataEdicao(f($row,'hospedagem_checkout'));
            $w_trechos[$i][36] = f($row,'hospedagem_observacao');
            $w_trechos[$i][37] = formataDataEdicao(f($row,'veiculo_retirada'));
            $w_trechos[$i][38] = formataDataEdicao(f($row,'veiculo_devolucao'));
            $w_trechos[$i][39] = f($row,'destino_nacional');
            $w_trechos[$i][40] = f($row,'saida_internacional');
            $w_trechos[$i][41] = f($row,'chegada_internacional');
            $w_trechos[$i][42] = f($row,'origem_nacional');
            
            // Cria array para guardar o valor total por moeda
            if ($w_trechos[$i][13]>'') $w_tot_diaria_S[$w_trechos[$i][13]] = 0;
            if ($w_trechos[$i][18]>'') $w_tot_diaria_S[$w_trechos[$i][18]] = 0;
            if ($w_trechos[$i][12]>'') $w_tot_diaria_S[$w_trechos[$i][23]] = 0;
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
          //}
        } 
        if ($i>1) $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PREVISÃO DE DIÁRIAS, HOSPEDAGENS E VEÍCULOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $w_cor          = $conTrBgColor;
        $j              = $i;
        $i              = 1;
        $w_diarias      = 0;
        $w_locacoes     = 0;
        $w_hospedagens  = 0;
        $w_tot_local    = 0;
        while($i<=count($w_trechos)) {
          $w_max_hosp     = ceil((toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400);
          $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
          $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
          $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
          
          if ($w_diarias>0)     $w_tot_diaria_S[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes<>0)   $w_tot_diaria_S[$w_trechos[$i][23]] += $w_locacoes;
          //if ($w_hospedagens>0) $w_tot_diaria_S[$w_trechos[$i][18]] += $w_hospedagens;
          
          $w_tot_local = $w_diarias + $w_locacoes;
          
          if (($i>1 && $i<count($w_trechos)) || 
              ($w_trechos[$i][40]==0 && 
               $w_trechos[$i][41]==0 && 
               ($w_trechos[$i][42]=='S' || toDate(FormataDataEdicao($w_trechos[$i][6]))!=$w_fim) && 
               ($w_tot_local!=0 || $i!=count($w_trechos))
              )
             ) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            
            // Configura a quantidade de linhas do trecho
            $rowspan = 1;
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
            $rowspan_local = $rowspan;
            if ($w_trechos[$i][28]>'') $rowspan_local += 1;
            if ($w_trechos[$i][29]>'') $rowspan_local += 1;
            
            $l_html.=chr(13).'      <tr><td colspan=2 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);"><b>'.$w_trechos[$i][5].'</b></td>';
            $l_html.=chr(13).'      <tr valign="top"><td><b>Estada:</b><td>'.substr(FormataDataEdicao($w_trechos[$i][6],4),0,-3).' a '.substr(FormataDataEdicao($w_trechos[$i][7],4),0,-3);
            if ($w_trechos[$i][32]=='S' || $w_trechos[$i][33]=='S')  {
              $l_html.=chr(13).'      <tr valign="top"><td><b>Horários:</b><td>';
              if ($w_trechos[$i][32]=='S') $l_html.=chr(13).'Saída após  as 18:00';
              if ($w_trechos[$i][32]=='S' && $w_trechos[$i][33]=='S') $l_html.=chr(13).'/';
              if ($w_trechos[$i][33]=='S') $l_html.=chr(13).'Chegada até 12:00';
            }
            if ($w_trechos[$i][30]=='N' || $w_trechos[$i][31]=='N') {
              $l_html.=chr(13).'      <tr valign="top"><td><b>Compromissos:</b><td>';
              if ($w_trechos[$i][30]=='N') $l_html.=chr(13).'Sem compromisso na ida';
              if ($w_trechos[$i][30]=='N' && $w_trechos[$i][31]=='N') $l_html.=chr(13).'/';
              if ($w_trechos[$i][31]=='N') $l_html.=chr(13).'Sem compromisso na volta';
            }
            $l_html.=chr(13).'      <tr valign="top"><td><b>Diárias:</b><td>';
            if ($w_trechos[$i][12]=='S') {
              $l_html.=chr(13).'Sim.</td>';
            } else {
              $l_html.=chr(13).'Não. Justificativa: '.crlf2br($w_trechos[$i][28]).'</td>';
            }
            if (f($RS,'hospedagem')=='S' && $w_trechos[$i][39]=='S') {
            if ($w_trechos[$i][15]=='S') {
                $l_html.=chr(13).'      <tr valign="top"><td><b>Hospedagem:</b><td>'.$w_trechos[$i][34].' a '.$w_trechos[$i][35].'. Observação: '.crlf2br($w_trechos[$i][36]).'</td>';
              } else {
                $l_html.=chr(13).'      <tr valign="top"><td><b>Hospedagem:</b><td>Não. Justificativa: '.crlf2br($w_trechos[$i][36]).'</td>';
              }
            }
            if ($w_trechos[$i][20]=='S' && $w_trechos[$i][27]>'' && f($RS,'veiculo')=='S') {
              $l_html.=chr(13).'      <tr valign="top"><td><b>Veículo:</b><td>'.$w_trechos[$i][37].' a '.$w_trechos[$i][38].'. Justificativa: '.crlf2br($w_trechos[$i][29]).'</td>';
            }
          }
          $i += 1;
        }
      }

      if (f($RS,'cumprimento')!='C') {
        unset($w_trechos);
        $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'P','PDDIARIA');
        $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
        $i = 0;
        foreach($RS1 as $row) {
          if ($i==0) $w_inicio = f($row,'saida');
          $w_fim = f($row,'chegada');
          $i++;
        }
        reset($RS1);
        $i = 1;
        if (count($RS1)>0) {
          foreach($RS1 as $row) {
            //if (nvl(f($row,'sq_diaria'),0)>0) {
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
              $w_trechos[$i][34] = formataDataEdicao(f($row,'hospedagem_checkin'));
              $w_trechos[$i][35] = formataDataEdicao(f($row,'hospedagem_checkout'));
              $w_trechos[$i][36] = f($row,'hospedagem_observacao');
              $w_trechos[$i][37] = formataDataEdicao(f($row,'veiculo_retirada'));
              $w_trechos[$i][38] = formataDataEdicao(f($row,'veiculo_devolucao'));
              $w_trechos[$i][39] = f($row,'destino_nacional');
              $w_trechos[$i][40] = f($row,'saida_internacional');
              $w_trechos[$i][41] = f($row,'chegada_internacional');
              $w_trechos[$i][42] = f($row,'origem_nacional');
              
              // Cria array para guardar o valor total por moeda
              if ($w_trechos[$i][13]>'') $w_tot_diaria_P[$w_trechos[$i][13]] = 0;
              if ($w_trechos[$i][18]>'') $w_tot_diaria_P[$w_trechos[$i][18]] = 0;
              if ($w_trechos[$i][12]>'') $w_tot_diaria_P[$w_trechos[$i][23]] = 0;
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
            //}
          } 
          if ($i>1) $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>REALIZAÇÃO DE DIÁRIAS, HOSPEDAGENS E VEÍCULOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
          $w_cor          = $conTrBgColor;
          $j              = $i;
          $i              = 1;
          $w_diarias      = 0;
          $w_locacoes     = 0;
          $w_hospedagens  = 0;
          $w_tot_local    = 0;
          while($i<=count($w_trechos)) {
            $w_max_hosp     = ceil((toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400);
            $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
            $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
            $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
            
            if ($w_diarias>0)     $w_tot_diaria_P[$w_trechos[$i][13]] += $w_diarias;
            if ($w_locacoes<>0)   $w_tot_diaria_P[$w_trechos[$i][23]] += $w_locacoes;
            //if ($w_hospedagens>0) $w_tot_diaria_P[$w_trechos[$i][18]] += $w_hospedagens;
            
            $w_tot_local = $w_diarias + $w_locacoes;
            
            if (($i>1 && $i<count($w_trechos)) || ($w_trechos[$i][40]==0 && $w_trechos[$i][41]==0 && ($w_trechos[$i][42]=='S' || toDate(FormataDataEdicao($w_trechos[$i][6]))!=$w_fim) && ($w_tot_local!=0 || $i!=count($w_trechos)))) {
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              
              // Configura a quantidade de linhas do trecho
              $rowspan = 1;
              if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
              if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
              $rowspan_local = $rowspan;
              if ($w_trechos[$i][28]>'') $rowspan_local += 1;
              if ($w_trechos[$i][29]>'') $rowspan_local += 1;
              
              $l_html.=chr(13).'      <tr><td colspan=2 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);"><b>'.$w_trechos[$i][5].'</b></td>';
              $l_html.=chr(13).'      <tr valign="top"><td><b>Estada:</b><td>'.substr(FormataDataEdicao($w_trechos[$i][6],4),0,-3).' a '.substr(FormataDataEdicao($w_trechos[$i][7],4),0,-3);
              if ($w_trechos[$i][32]=='S' || $w_trechos[$i][33]=='S')  {
                $l_html.=chr(13).'      <tr valign="top"><td><b>Horários:</b><td>';
                if ($w_trechos[$i][32]=='S') $l_html.=chr(13).'Saída após  as 18:00';
                if ($w_trechos[$i][32]=='S' && $w_trechos[$i][33]=='S') $l_html.=chr(13).'/';
                if ($w_trechos[$i][33]=='S') $l_html.=chr(13).'Chegada até 12:00';
              }
              if ($w_trechos[$i][30]=='N' || $w_trechos[$i][31]=='N') {
                $l_html.=chr(13).'      <tr valign="top"><td><b>Compromissos:</b><td>';
                if ($w_trechos[$i][30]=='N') $l_html.=chr(13).'Sem compromisso na ida';
                if ($w_trechos[$i][30]=='N' && $w_trechos[$i][31]=='N') $l_html.=chr(13).'/';
                if ($w_trechos[$i][31]=='N') $l_html.=chr(13).'Sem compromisso na volta';
              }
              $l_html.=chr(13).'      <tr valign="top"><td><b>Diárias:</b><td>';
              if ($w_trechos[$i][12]=='S') {
                $l_html.=chr(13).'Sim.</td>';
              } else {
                $l_html.=chr(13).'Não. Justificativa: '.crlf2br($w_trechos[$i][28]).'</td>';
              }
              if (f($RS,'hospedagem')=='S'&&$w_trechos[$i][39]=='S') {
                if ($w_trechos[$i][15]=='S') {
                  $l_html.=chr(13).'      <tr valign="top"><td><b>Hospedagem:</b><td>'.$w_trechos[$i][34].' a '.$w_trechos[$i][35].'. Observação: '.crlf2br($w_trechos[$i][36]).'</td>';
                } else {
                  $l_html.=chr(13).'      <tr valign="top"><td><b>Hospedagem:</b><td>Não. Justificativa: '.crlf2br($w_trechos[$i][36]).'</td>';
                }
              }
              if ($w_trechos[$i][20]=='S' && $w_trechos[$i][27]>'' && f($RS,'veiculo')=='S') {
                $l_html.=chr(13).'      <tr valign="top"><td><b>Veículo:</b><td>'.$w_trechos[$i][37].' a '.$w_trechos[$i][38].'. Justificativa: '.crlf2br($w_trechos[$i][29]).'</td>';
              }
            }
            $i += 1;
          }
        }
      }
    }
    
    // Alterações de viagem
    $RS1 = db_getPD_Alteracao::getInstanceOf($dbms,$l_chave,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'autorizacao_data','asc', 'chave', 'asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ALTERAÇÕES DE VIAGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td colspan=5><b>Diferenças</td>';
      $l_html.=chr(13).'          <td colspan=3><b>Autorização</td>';
      $l_html.=chr(13).'          <td rowspan=2><b>Justificativa</td>';
      $l_html.=chr(13).'        </tr>';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Tarifas</td>';
      $l_html.=chr(13).'          <td><b>Taxas</td>';
      $l_html.=chr(13).'          <td><b>Hospedagens</td>';
      $l_html.=chr(13).'          <td><b>Diárias</td>';
      $l_html.=chr(13).'          <td><b>Total</td>';
      $l_html.=chr(13).'          <td><b>Nome</td>';
      $l_html.=chr(13).'          <td><b>Cargo</td>';
      $l_html.=chr(13).'          <td><b>Data</td>';
      $l_html.=chr(13).'        </tr>';
      $w_cor=$conTrBgColor;
      $i             = 1;
      $w_tot_alt     = 0;
      foreach ($RS1 as $row) {
        $w_tot_alt      = f($row,'diaria_valor')+f($row,'hospedagem_valor')+f($row,'bilhete_tarifa')+f($row,'bilhete_taxa');
        $w_total       += $w_tot_alt;
        $l_html.=chr(13).'        <tr valign="top">';
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'bilhete_tarifa')).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'bilhete_taxa')).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'hospedagem_valor')).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'diaria_valor')).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber($w_tot_alt).'</td>';
        $l_html.=chr(13).'          <td>'.f($row,'nm_autorizador').'</td>';
        $l_html.=chr(13).'          <td>'.f($row,'autorizacao_cargo').'</td>';
        $l_html.=chr(13).'          <td align="center">'.FormataDataEdicao(f($row,'autorizacao_data'),5).'</td>';
        $l_html.=chr(13).'          <td>'.nvl(CRLF2BR(f($row,'justificativa')),'---');
        if (nvl(f($row,'sq_siw_arquivo'),'')!='') {
          $l_html.='<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nm_arquivo'),null).'</td>';
        }
        $l_html.=chr(13).'        </tr>';
      } 
      $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $l_html.=chr(13).'        <td align="right" colspan="4"><b>TOTAL</b></td>';
      $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
      $l_html.=chr(13).'        <td align="right" colspan="4">&nbsp;</td>';
      $l_html.=chr(13).'      </tr>';
      $l_html.=chr(13).'         </table></td></tr>';
    } 
  
    // Pagamento de diárias
    if($l_diaria=='S' && $w_or_tramite>4) {
      unset($w_trechos);
      unset($w_tot_diaria_S);
      $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'S','PDDIARIA');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      if (count($RS1)>0) {
        $i = 0;
        foreach($RS1 as $row) {
          if ($i==0) $w_inicio = f($row,'saida');
          $w_fim = f($row,'chegada');
          $i++;
        }
        reset($RS1);
        $i = 1;
        foreach($RS1 as $row) {
          //if (nvl(f($row,'sq_diaria'),0)>0) {
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
            $w_trechos[$i][34] = f($row,'calculo_diaria_qtd');
            $w_trechos[$i][35] = f($row,'calculo_diaria_texto');
            $w_trechos[$i][36] = f($row,'calculo_hospedagem_qtd');
            $w_trechos[$i][37] = f($row,'calculo_hospedagem_texto');
            $w_trechos[$i][38] = f($row,'calculo_veiculo_qtd');
            $w_trechos[$i][39] = f($row,'calculo_veiculo_texto');
            
            // Cria array para guardar o valor total por moeda
            if ($w_trechos[$i][13]>'') $w_tot_diaria_S[$w_trechos[$i][13]] = 0;
            if ($w_trechos[$i][18]>'') $w_tot_diaria_S[$w_trechos[$i][18]] = 0;
            if ($w_trechos[$i][12]>'') $w_tot_diaria_S[$w_trechos[$i][23]] = 0;
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
          //}
        } 
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PAGAMENTO PREVISTO DE DIÁRIAS, HOSPEDAGENS E VEÍCULOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=chr(13).'      <tr><td colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'           <td><b>Localidade</td>';
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
        while($i<=count($w_trechos)) {
          $w_max_hosp     = ceil((toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400);
          $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
          $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
          $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
          
          if ($w_diarias>0)     $w_tot_diaria_S[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes<>0)   $w_tot_diaria_S[$w_trechos[$i][23]] += $w_locacoes;
          //if ($w_hospedagens>0) $w_tot_diaria_S[$w_trechos[$i][18]] += $w_hospedagens;
          
          $w_tot_local = $w_diarias + $w_locacoes;
          
          if ($w_tot_local!=0) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            
            // Configura a quantidade de linhas do trecho
            $rowspan = 1;
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
            $rowspan_local = $rowspan;
            if ($w_trechos[$i][35]>''||$w_trechos[$i][37]>''||$w_trechos[$i][39]>'') $rowspan_local += 1;
            
            $l_html.=chr(13).'     <tr valign="top">';
            $l_html.=chr(13).'       <td rowspan="'.$rowspan_local.'"><b>'.$w_trechos[$i][5].'</b>';
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
            if ($w_trechos[$i][35]>''||$w_trechos[$i][37]>''||$w_trechos[$i][39]>'') {
              $l_html.=chr(13).'     <tr><td colspan="6">';
              if ($w_trechos[$i][35]>'') $l_html.=chr(13).'         <li>Quantidade calculada de diárias alterada de <b>'.formatNumber($w_trechos[$i][34],1).'</b> para <b>'.formatNumber($w_trechos[$i][8],1).'</b>. Motivo: <b>'.$w_trechos[$i][35].'</b></li>';
              if ($w_trechos[$i][37]>'') $l_html.=chr(13).'         <li>Quantidade calculada de hospedagens alterada de <b>'.formatNumber($w_trechos[$i][36],1).'</b> para <b>'.formatNumber($w_trechos[$i][16],1).'</b>. Motivo: <b>'.$w_trechos[$i][37].'</b></li>';
              if ($w_trechos[$i][39]>'') $l_html.=chr(13).'         <li>Quantidade calculada de diárias de veículo alterada de <b>'.formatNumber($w_trechos[$i][38],1).'</b> para <b>'.formatNumber($w_trechos[$i][21],1).'</b>. Motivo: <b>'.$w_trechos[$i][39].'</b></li>';
            }
          }
          $i += 1;
        }
        $l_html.=chr(13).'     <tr bgcolor="'.$conTrBgColor.'"><td colspan="7" align="center"><b>TOTAL:';
        foreach($w_tot_diaria_S as $k => $v) {
          $l_html.=chr(13).'       &nbsp;&nbsp;&nbsp;&nbsp;'.$k.' '.formatNumber($v);
        }
        $l_html.=chr(13).'     </b></td></tr>';
        $l_html.=chr(13).'        </table></td></tr>';
      }
    }

    // Pagamento de diárias
    if($l_diaria=='S' && $w_or_tramite>=9) {
      unset($w_trechos);
      unset($w_tot_diaria_P);
      $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'P','PDDIARIA');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      if (count($RS1)>0) {
        $i = 0;
        foreach($RS1 as $row) {
          if ($i==0) $w_inicio = f($row,'saida');
          $w_fim = f($row,'chegada');
          $i++;
        }
        reset($RS1);
        $i = 1;
        foreach($RS1 as $row) {
          //if (nvl(f($row,'sq_diaria'),0)>0) {
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
            $w_trechos[$i][34] = f($row,'calculo_diaria_qtd');
            $w_trechos[$i][35] = f($row,'calculo_diaria_texto');
            $w_trechos[$i][36] = f($row,'calculo_hospedagem_qtd');
            $w_trechos[$i][37] = f($row,'calculo_hospedagem_texto');
            $w_trechos[$i][38] = f($row,'calculo_veiculo_qtd');
            $w_trechos[$i][39] = f($row,'calculo_veiculo_texto');
            
            // Cria array para guardar o valor total por moeda
            if ($w_trechos[$i][13]>'') $w_tot_diaria_P[$w_trechos[$i][13]] = 0;
            if ($w_trechos[$i][18]>'') $w_tot_diaria_P[$w_trechos[$i][18]] = 0;
            if ($w_trechos[$i][12]>'') $w_tot_diaria_P[$w_trechos[$i][23]] = 0;
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
        //} 
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PAGAMENTO REALIZADO DE DIÁRIAS, HOSPEDAGENS E VEÍCULOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=chr(13).'      <tr><td colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'           <td><b>Localidade</td>';
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
        while($i<=count($w_trechos)) {
          $w_max_hosp     = ceil((toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400);
          $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
          $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
          $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
          
          if ($w_diarias>0)     $w_tot_diaria_P[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes<>0)   $w_tot_diaria_P[$w_trechos[$i][23]] += $w_locacoes;
          //if ($w_hospedagens>0) $w_tot_diaria_P[$w_trechos[$i][18]] += $w_hospedagens;
          
          $w_tot_local = $w_diarias + $w_locacoes;
          
          if ($w_tot_local!=0) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            
            // Configura a quantidade de linhas do trecho
            $rowspan = 1;
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
            $rowspan_local = $rowspan;
            if ($w_trechos[$i][35]>''||$w_trechos[$i][37]>''||$w_trechos[$i][39]>'') $rowspan_local += 1;
            
            $l_html.=chr(13).'     <tr valign="top">';
            $l_html.=chr(13).'       <td rowspan="'.$rowspan_local.'"><b>'.$w_trechos[$i][5].'</b>';
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
            if ($w_trechos[$i][35]>''||$w_trechos[$i][37]>''||$w_trechos[$i][39]>'') {
              $l_html.=chr(13).'     <tr><td colspan="6">';
              if ($w_trechos[$i][35]>'') $l_html.=chr(13).'         <li>Quantidade calculada de diárias alterada de <b>'.formatNumber($w_trechos[$i][34],1).'</b> para <b>'.formatNumber($w_trechos[$i][8],1).'</b>. Motivo: <b>'.$w_trechos[$i][35].'</b></li>';
              if ($w_trechos[$i][37]>'') $l_html.=chr(13).'         <li>Quantidade calculada de hospedagens alterada de <b>'.formatNumber($w_trechos[$i][36],1).'</b> para <b>'.formatNumber($w_trechos[$i][16],1).'</b>. Motivo: <b>'.$w_trechos[$i][37].'</b></li>';
              if ($w_trechos[$i][39]>'') $l_html.=chr(13).'         <li>Quantidade calculada de diárias de veículo alterada de <b>'.formatNumber($w_trechos[$i][38],1).'</b> para <b>'.formatNumber($w_trechos[$i][21],1).'</b>. Motivo: <b>'.$w_trechos[$i][39].'</b></li>';
            }
          }
          $i += 1;
        }
        $l_html.=chr(13).'     <tr bgcolor="'.$conTrBgColor.'"><td colspan="7" align="center"><b>TOTAL:';
        foreach($w_tot_diaria_P as $k => $v) {
          $l_html.=chr(13).'       &nbsp;&nbsp;&nbsp;&nbsp;'.$k.' '.formatNumber($v);
        }
        $l_html.=chr(13).'     </b></td></tr>';
        $l_html.=chr(13).'        </table></td></tr>';
      }    
    }
    $w_diferenca = false;
    // Dados da prestação de contas
    if ($w_or_tramite>=9 && f($RS,'cumprimento')!='N') {
      // Acerto de contas da viagem
      if($l_diaria=='S' && $w_or_tramite>=9 && (is_array($w_tot_diaria_P) || f($RS,'cumprimento')=='C')) {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DIFERENÇA DE DIÁRIAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=chr(13).'      <tr><td colspan="2">';
        $l_html.=chr(13).'        <table border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center"><TD><B><FONT SIZE=2>VALOR';
        foreach($w_tot_diaria_S as $k => $v) $l_html.=chr(13).'       <TD><B><FONT SIZE=2>'.$k;
        $l_html.=chr(13).'       </tr>';
        $l_html.=chr(13).'       <tr align="RIGHT"><TD><FONT SIZE=2>Devido';
        if (is_array($w_tot_diaria_P)) {
          foreach($w_tot_diaria_P as $k => $v) {
            $l_html.=chr(13).'       <TD><FONT SIZE=2>'.formatNumber($v);
          }
        } else {
          foreach($w_tot_diaria_S as $k => $v) {
            $l_html.=chr(13).'       <TD><FONT SIZE=2>0,00';
          }
        }
        $l_html.=chr(13).'       <tr align="RIGHT"><TD><FONT SIZE=2>Recebido';
        foreach($w_tot_diaria_S as $k => $v) {
          $l_html.=chr(13).'       <TD><FONT SIZE=2>'.formatNumber($w_tot_diaria_S[$k]);
        }
        $l_html.=chr(13).'       </tr>';
        $l_html.=chr(13).'       </tr>';
        $l_html.=chr(13).'       <tr bgcolor="'.$conTrBgColor.'" align="RIGHT"><TD><B><FONT SIZE=2>Diferença';
        foreach($w_tot_diaria_S as $k => $v) {
          $l_html.=chr(13).'       <TD><B><FONT SIZE=2>'.formatNumber($w_tot_diaria_P[$k]-$v);
          if (($w_tot_diaria_P[$k]-$v)!=0) $w_diferenca = true;
        }
        $l_html.=chr(13).'     </b></td></tr>';
        $l_html.=chr(13).'        </table></td></tr>';
      }
      
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PRESTAÇÃO DE CONTAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2">';
      $l_html.=chr(13).'      <tr><td><b>Tipo de cumprimento:</b></td><td>'.f($RS,'nm_cumprimento').'</td></tr>';
      if (f($RS,'cumprimento')=='P') {
        $l_html.=chr(13).'      <tr valign="top"><td valign="top"><b>Motivo do cumprimento parcial:</b></td><td>'.nvl(CRLF2BR(f($RS,'nota_conclusao')),'---').'</td></tr>';
      } elseif (f($RS,'cumprimento')=='C') {
        $l_html.=chr(13).'      <tr valign="top"><td valign="top"><b>Motivo do cancelamento:</b></td><td>'.nvl(CRLF2BR(f($RS,'nota_conclusao')),'---').'</td></tr>';
      } 
      if ($w_diferenca) {
        $l_html.=chr(13).'      <tr><td><b>Diferença de diárias:</b></td><td>';
        foreach($w_tot_diaria_S as $k => $v) {
          $l_html.=$k.' '.formatNumber($w_tot_diaria_P[$k]-$v).'&nbsp;&nbsp;&nbsp;';
        }
        $l_html.='</td>';
      }
      $l_html.=chr(13).'      <tr valign="top"><td><b>Reembolso ao beneficiário:</b></td><td>';
      if (f($RS,'reembolso')=='S') {
        // Valores a serem reembolsados
        $RS_Reembolso = db_getPD_Reembolso::getInstanceOf($dbms,$l_chave,null,null,null);
        $RS_Reembolso = SortArray($RS_Reembolso,'sg_moeda','asc');
        
        $l_html.=chr(13).'      <table border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td colspan=3><b>Solicitação</b></td>';
        $l_html.=chr(13).'          <td colspan=2><b>Autorização</b></td>';
        $l_html.=chr(13).'        </tr>';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">';
        $l_html.=chr(13).'          <td><b>Moeda</b></td>';
        $l_html.=chr(13).'          <td><b>Valor</td>';
        $l_html.=chr(13).'          <td><b>Justificativa</td>';
        $l_html.=chr(13).'          <td><b>Valor</td>';
        $l_html.=chr(13).'          <td><b>Observação</td>';
        $l_html.=chr(13).'        </tr>';
        if (count($RS_Reembolso)<=0) {
          $l_html.=chr(13).'      <tr><td colspan=8 align="center"><font color="#BC3131"><b>VALORES NÃO INFORMADOS.</b></b></td></tr>';
        } else {
          foreach($RS_Reembolso as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            $l_html.=chr(13).'      <tr valign="top">';
            $l_html.=chr(13).'        <td>'.f($row,'sg_moeda').' ('.f($row,'nm_moeda').')</td>';
            $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_solicitado')).'</td>';
            $l_html.=chr(13).'        <td>'.crlf2br(f($row,'justificativa')).'</td>';
            if ($w_or_tramite<=9) {
              // No trâmite de prestação de contas
              $l_html.=chr(13).'        <td align="center" colspan="2">&nbsp;</td>';
            } elseif ($w_or_tramite==10 && f($row,'valor_autorizado')==0 && f($row,'observacao')=='') {
              // No trâmite de verificação da prestação de contas mas sem valor informado.
              $l_html.=chr(13).'        <td align="center" colspan="2">Em análise</td>';
            } else {
              // No trâmite de verificação da prestação de contas e com valor informado, ou em trâmite posterior a PC
              $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_autorizado')).'</td>';
              $l_html.=chr(13).'        <td>'.nvl(crlf2br(f($row,'observacao')),'---').'</td>';
            }
            $l_html.=chr(13).'        </td>';
            $l_html.=chr(13).'      </tr>';
          }
        }
        $l_html.=chr(13).'    </table>';
      }
      if (f($RS,'ressarcimento')=='S') {
        $l_html.=chr(13).'      <tr><td><b>Data da devolução:</b></td><td>'.formataDataEdicao(f($RS,'ressarcimento_data')).'</td></tr>';      
        $l_html.=chr(13).'      <tr valign="top"><td><b>Código do depósito identificado:</b></td><td>'.nvl(f($RS,'deposito_identificado'),'---').'</td></tr>';
      }
      $l_html.=chr(13).'      <tr><td><b>Valor devolvido:</b></td><td>R$ '.formatNumber(f($RS,'ressarcimento_valor')).'</td></tr>';
      if (f($RS,'ressarcimento')=='S') {
        $l_html.=chr(13).'      <tr valign="top"><td><b>Observação:</b></td><td>'.nvl(CRLF2BR(f($RS,'ressarcimento_observacao')),'---').'</td></tr>';
      } 
      if (f($RS,'cumprimento')!='C') $l_html.=chr(13).'      <tr valign="top"><td valign="top"><b>Relatório de viagem:</b></td><td>'.nvl(CRLF2BR(f($RS,'relatorio')),'---').'</td></tr>';
      if (nvl(f($RS,'sq_relatorio_viagem'),'')!='') {
        $l_html.=chr(13).'      <tr valign="top"><td><b>Anexo do relatório:</b></td><td>'.LinkArquivo('HL',$w_cliente,f($RS,'sq_relatorio_viagem'),'_blank','Clique para exibir o arquivo em outra janela.',f($RS,'nm_arquivo'),null).'</td>';
      }
    }

    // Cotação de passagens
    if($l_deslocamento=='S' && $w_or_tramite>=2 && $w_internacional=='S') {
      $RS1 = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'S','COTPASS');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>COTAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Origem</td>';
      $l_html.=chr(13).'          <td><b>Destino</td>';
      $l_html.=chr(13).'          <td><b>Saída</td>';
      $l_html.=chr(13).'          <td><b>Chegada</td>';
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
          $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_trecho')).'</td>';
          $l_html.=chr(13).'        <td>'.nvl(f($row,'nm_cia_transporte'),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'codigo_voo'),'&nbsp;').'</td>';
          $l_html.=chr(13).'      </tr>';
          $w_tot_bilhete  += nvl(f($row,'valor_trecho'),0);
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right" colspan=4><b>TOTAL</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.Nvl(formatNumber($w_tot_bilhete),0).'</b></td>';
        $l_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
      } 
      $l_html.=chr(13).'    </table>';
      $l_html.=chr(13).'  </td>';
      $l_html.=chr(13).'</tr>';
    }

    if ($w_or_tramite>4) {
      // Bilhete de passagem
      $RS1 = db_getPD_Bilhete::getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,null);
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
        $l_html.=chr(13).'          <td colspan=5><b>Valores</td>';
        $l_html.=chr(13).'          <td colspan=3><b>Faturamento</td>';
        $l_html.=chr(13).'        </tr>';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td><b>Valor Cheio</td>';
        $l_html.=chr(13).'          <td><b>Valor Bilhete</td>';
        $l_html.=chr(13).'          <td><b>Embarque</td>';
        $l_html.=chr(13).'          <td><b>Taxas</td>';
        $l_html.=chr(13).'          <td><b>Total</td>';
        $l_html.=chr(13).'          <td><b>Fatura</td>';
        $l_html.=chr(13).'          <td nowrap><b>$ Desconto</td>';
        $l_html.=chr(13).'          <td nowrap><b>$ Faturado</td>';
        $l_html.=chr(13).'        </tr>';
        $w_cor=$conTrBgColor;
        $i             = 1;
        $w_tot_bil     = 0;
        $w_tot_pta     = 0;
        $w_tot_tax     = 0;
        $w_tot_bilhete = 0;
        $w_tot_desconto = 0;
        $w_tot_fatura   = 0;
        $w_total       = 0;
        foreach ($RS1 as $row) {
          $w_tot_bilhete  = f($row,'valor_bilhete')+f($row,'valor_pta')+f($row,'valor_taxa_embarque');
          $w_tot_cheio    += f($row,'valor_bilhete_cheio');
          $w_tot_bil      += f($row,'valor_bilhete');
          $w_tot_pta      += f($row,'valor_pta');
          $w_tot_tax      += f($row,'valor_taxa_embarque');
          $w_tot_desconto += nvl(f($row,'valor_desconto'),0);
          $w_tot_fatura   += nvl(f($row,'vl_bilhete_fatura'),0);
          $w_total        += $w_tot_bilhete;
          $w_rowspan      = ((nvl(f($row,'observacao'),'')=='') ? 1 : 2);
          $l_html.=chr(13).'        <tr valign="middle">';
          $l_html.=chr(13).'           <td align="center" rowspan="'.$w_rowspan.'">'.FormataDataEdicao(f($row,'data'),5).'</td>';
          $l_html.=chr(13).'           <td rowspan="'.$w_rowspan.'">'.f($row,'nm_cia_transporte').'</td>';
          $l_html.=chr(13).'           <td rowspan="'.$w_rowspan.'">'.f($row,'numero').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'trecho').'</td>';
          $l_html.=chr(13).'           <td>'.nvl(f($row,'rloc'),'&nbsp;').'</td>';
          $l_html.=chr(13).'           <td align="center">'.f($row,'classe').'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_bilhete_cheio')).'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_bilhete')).'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_taxa_embarque')).'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_pta')).'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber($w_tot_bilhete).'</td>';
          $l_html.=chr(13).'           <td align="center">'.nvl(f($row,'nr_fatura'),'&nbsp;').'</td>';
          $l_html.=chr(13).'           <td align="right">'.((nvl(f($row,'nr_fatura'),'')=='') ? '&nbsp;' : formatNumber(f($row,'valor_desconto'))).'</td>';
          $l_html.=chr(13).'           <td align="right">'.((nvl(f($row,'nr_fatura'),'')=='') ? '&nbsp;' : formatNumber(f($row,'vl_bilhete_fatura'))).'</td>';
          $l_html.=chr(13).'        </tr>';
          if (nvl(f($row,'observacao'),'')!='') $l_html.=chr(13).'        <tr><td colspan=7>Observação: '.crlf2br(f($row,'observacao')).'</td></tr>';
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right" colspan="6"><b>TOTAIS</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_cheio).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_bil).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_tax).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_pta).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
        if ($w_tot_fatura==0) {
          $l_html.=chr(13).'        <td colspan=3>&nbsp;</td>';
        } else {
          $l_html.=chr(13).'        <td>&nbsp;</td>';
          $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_desconto).'</b></td>';
          $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_fatura).'</b></td>';
        }
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td></tr>';
      }

      // Prepara array de impressão dos dados orçamentários
      $RS_Financ = db_getPD_Financeiro::getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,null,'ORCAM_PREV');
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
      $RS_Financ = db_getPD_Financeiro::getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,null,'FINANC_PREV');
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
          $l_html .= chr(13).'          <td>'.$w_orc[$i]['cd_rubrica'].' - '.$w_orc[$i]['nm_rubrica'].'&nbsp';
          foreach($w_orc_moeda as $k=>$v) {
            $l_html.=chr(13).'          <td align="right">'.formatNumber($w_orc[$i]['moeda'][$k]).'</td>';
            $w_tot[$k] += $w_orc[$i]['moeda'][$k];
          }
          $l_html.=chr(13).'      </tr>';
          $i++;
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right"><b>TOTAIS</b></td>';
        foreach($w_orc_moeda as $k=>$v) $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($w_tot[$k]).'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td>';
        unset($w_tot);

        // Exibe previsão financeira
        $l_html.=chr(13).'        <td align="center" width="50%"><table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr align="center">';
        if (count($w_fin_moeda)==1) {
          $l_html.=chr(13).'          <td colspan="2" bgColor="#f0f0f0"><b>FINANCEIRO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Tipo de Lançamento</b></td>';
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
          foreach($w_fin_moeda as $k=>$v) {
            $l_html.=chr(13).'          <td align="right">'.formatNumber($w_fin[$i]['moeda'][$k]).'</td>';
            $w_tot[$k] += $w_fin[$i]['moeda'][$k];
          }
          $l_html.=chr(13).'      </tr>';
          $i++;
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right"><b>TOTAIS</b></td>';
        foreach($w_fin_moeda as $k=>$v) $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($w_tot[$k]).'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td></tr>';
        $l_html.=chr(13).'       </table></td></tr>';
      } 
      
      $RS1 = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'FILHOS',null,
        null,null,null,null,null,null,null,null,null,null,$l_chave, null, null, null, null, null, null,
        null, null, null, null, null, null, null, null, null);
      $RS1 = SortArray($RS1,'inclusao','asc', 'codigo_interno', 'asc');
      if (count($RS1)>0) {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>EXECUÇÃO FINANCEIRA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=chr(13).'      <tr><td colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td><b>Código</td>';
        $l_html.=chr(13).'          <td><b>Histórico</td>';
        $l_html.=chr(13).'          <td><b>Valor</td>';
        $l_html.=chr(13).'          <td><b>Situação atual</td>';
        $l_html.=chr(13).'          <td><b>Data Pagamento</td>';
        $l_html.=chr(13).'        </tr>';
        $w_cor=$conTrBgColor;
        $i             = 1;
        $w_total       = 0;
        foreach ($RS1 as $row) {
          $w_total       += f($row,'valor');
          $l_html.=chr(13).'        <tr valign="middle">';
          $l_html.=chr(13).'           <td>'.f($row,'codigo_interno').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'descricao').'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor')).'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'nm_tramite').'</td>';
          $l_html.=chr(13).'           <td align="center">'.nvl(formataDataEdicao(f($row,'conclusao')),'&nbsp;').'</td>';
          $l_html.=chr(13).'        </tr>';
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right" colspan="2"><b>TOTAL</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
        $l_html.=chr(13).'        <td align="right" colspan="2">&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td></tr>';
      }
  
      // Arquivos vinculados
      $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS1 = SortArray($RS1,'nome','asc');
      if (count($RS1)>0) {
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
        foreach($RS1 as $row) {
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
    if (1==0) {//($l_anexo = 'S' && $w_or_tramite > 5) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="0" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr><td><a target="Emissao" class="hl" title="Emitir autorização e proposta de concessão." href="'.$w_dir.$w_pagina.'Emissao&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'">Autorização para emissão de bilhetes</A>';
      $l_html.=chr(13).'        <tr><td><a target="Relatorio" class="hl" title="Emitir relatório para prestacao de contas." href="'.$w_dir.$w_pagina.'Prestacaocontas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Relatório de viagem</A>';
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