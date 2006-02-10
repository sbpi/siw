<%
REM =========================================================================
REM Rotina de visualização dos dados da missão
REM -------------------------------------------------------------------------
Function VisualViagem(w_chave, O, w_usuario, P1, P4)

  Dim RS, Rsquery, w_Erro
  Dim w_Imagem, w_html, w_TrBgColor
  Dim w_ImagemPadrao, w_tramite
  Dim w_tipo_visao, w_titulo
  Dim w_total, w_valor, w_real, w_fim, w_sg_tramite
  Dim w_total_diaria
  Set RS = Server.CreateObject("ADODB.RecordSet")
  Set RSQuery = Server.CreateObject("ADODB.RecordSet")
  
  If P4 = 1 Then w_TrBgColor = "" Else w_TrBgColor = conTrBgColor End If

  w_html = ""

  ' Recupera os dados da viagem
  DB_GetSolicData RS, w_chave, Mid(SG,1,3) & "GERAL"
  w_tramite     = RS("sq_siw_tramite")
  w_valor       = cDbl(RS("valor"))
  w_fim         = cDate(RS("fim"))
  w_sg_tramite  = RS("sg_tramite")

  ' Recupera o tipo de visão do usuário
  If cDbl(Nvl(RS("solicitante"),0))  = cDbl(w_usuario) or _
     cDbl(Nvl(RS("executor"),0))     = cDbl(w_usuario) or _
     cDbl(Nvl(RS("cadastrador"),0))  = cDbl(w_usuario) or _
     cDbl(Nvl(RS("titular"),0))      = cDbl(w_usuario) or _
     cDbl(Nvl(RS("substituto"),0))   = cDbl(w_usuario) or _
     cDbl(Nvl(RS("tit_exec"),0))     = cDbl(w_usuario) or _
     cDbl(Nvl(RS("subst_exec"),0))   = cDbl(w_usuario) or _
     SolicAcesso(w_chave, w_usuario) >= 8 Then
     ' Se for solicitante, executor ou cadastrador, tem visão completa
     w_tipo_visao = 0
  Else
     If SolicAcesso(w_chave, w_usuario) > 2 Then w_tipo_visao = 1 End If
  End If
  
  ' Se for listagem ou envio, exibe os dados de identificação do acordo
  If O = "L" or O = "V" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & w_TrBgColor & """><td>"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"

     w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=1>Número: <b>" & RS("codigo_interno") & " (" & w_chave & ")<br>" & "</b></font></td></tr>"
      
      ' Identificação do acordo
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"
     w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Descrição:<br><b>" & RS("descricao") & "</b></td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     If Not P4 = 1 Then
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade proponente:<br><b>" & ExibeUnidade(w_dir_volta, w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
     Else
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade proponente:<br><b>" & RS("nm_unidade_resp") & "</b></td>"
     End If
     w_html = w_html & VbCrLf & "          <td valign=""top"" colspan=""2""><font size=""1"">Tipo:<br><b>" & RS("nm_tipo_missao") & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Primeira saída:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Último retorno:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"

     If Nvl(RS("justificativa_dia_util"),"") > "" Then ' Se o campo de justificativa de dias úteis para estiver preenchido, exibe
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Justificativa para início e término de viagens em sextas-feiras, sábados, domingos e feriados:<br><b>" & CRLF2BR(RS("justificativa_dia_util")) & " </b></td>"
     End If

     If Nvl(RS("justificativa"),"") > "" Then ' Se o campo de justificativa estiver preenchido, exibe
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Justificativa do não cumprimento do prazo de solicitação:<br><b>" & CRLF2BR(RS("justificativa")) & " </b></td>"
     End If

     ' Dados da conclusão da demanda, se ela estiver nessa situação
     If Nvl(RS("conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados do encerramento</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da vigência:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da vigência:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Valor realizado:<br><b>" & FormatNumber(RS("valor_atual"),2) & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RS("observacao")) & " </b></td>"
        End If
     End If

     ' Outra parte
     DB_GetBenef RSQuery, w_cliente, Nvl(RS("sq_prop"),0), null, null, null, 1, null, null
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Proposto</td>"
     If RSQuery.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>Proposto não informado."
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><table border=0 width=""100%"">"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">CPF:<b><br>" & RSQuery("cpf") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Nome:<b><br>" & RSQuery("nm_pessoa") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Nome resumido:<b><br>" & RSQuery("nome_resumido") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Sexo:<b><br>" & RSQuery("nm_sexo") & "</td>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Matrícula SIAPE:<b><br>" & Nvl(RS("matricula"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Identidade:<b><br>" & Nvl(RSQuery("rg_numero"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de emissão:<b><br>" & Nvl(RSQuery("rg_emissao"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Órgão emissor:<b><br>" & Nvl(RSQuery("rg_emissor"),"---") & "</td>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Telefones</td>"
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<b><br>(" & Nvl(RSQuery("ddd"),"---") & ") " & Nvl(RSQuery("nr_telefone"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Fax:<b><br>" & Nvl(RSQuery("nr_fax"),"---") & "</td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Celular:<b><br>" & Nvl(RSQuery("nr_celular"),"---") & "</td>"
        w_html = w_html & VbCrLf & "      <tr><td colspan=""4"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Dados bancários</td>"
        If 1 = 1 Then 'Instr("CREDITO,DEPOSITO",RS("sg_forma_pagamento")) > 0 Then
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           If Nvl(RS("cd_banco"),"") > "" Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("cd_banco") & " - " & RS("nm_banco") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>" & RS("cd_agencia") & " - " & RS("nm_agencia") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Operação:<b><br>" & Nvl(RS("operacao_conta"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Número da conta:<b><br>" & Nvl(RS("numero_conta"),"---") & "</td>"
           Else
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>---</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>---</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Operação:<b><br>---</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Número da conta:<b><br>---</td>"
           End If
        ElseIf RS("sg_forma_pagamento") = "ORDEM" Then
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           If Nvl(RS("cd_banco"),"") > "" Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("cd_banco") & " - " & RS("nm_banco") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>" & RS("cd_agencia") & " - " & RS("nm_agencia") & "</td>"
           Else
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>---</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>---</td>"
           End If
        ElseIf RS("sg_forma_pagamento") = "EXTERIOR" Then
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RS("banco_estrang") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">ABA Code:<b><br>" & Nvl(RS("aba_code"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">SWIFT Code:<b><br>" & Nvl(RS("swift_code"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <tr><td colspan=3><font size=""1"">Endereço da agência:<b><br>" & Nvl(RS("endereco_estrang"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Agência:<b><br>" & Nvl(RS("agencia_estrang"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Número da conta:<b><br>" & Nvl(RS("numero_conta"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td colspan=2><font size=""1"">Cidade:<b><br>" & RS("nm_cidade") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">País:<b><br>" & RS("nm_pais") & "</td>"
        End If
        w_html = w_html & VbCrLf & "        </table>"
     End If
  End If
   
  ' Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  If w_tipo_visao <> 2 and (O = "L" or O = "T") Then
     If RS("aviso_prox_conc") = "S" Then
        ' Configuração dos alertas de proximidade da data limite para conclusão do acordo
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alertas</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","Não") & " </b></td>"
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"
        w_html = w_html & VbCrLf & "          </table>"
     End If
  End If

  ' Deslocamentos
  DB_GetPD_Deslocamento RS, w_chave, null, "PDGERAL"
  RS.Sort = "saida, chegada"
  If Not Rs.EOF Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Deslocamentos</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Origem</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Destino</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Saida</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Chegada</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     w_cor = w_TrBgColor
     w_total = 0
     While Not Rs.EOF
       If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
       w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
       w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nm_origem") & "</td>"
       w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nm_destino") & "</td>"
       w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(FormatDateTime(RS("saida"),2)) & ", " &  Mid(FormatDateTime(RS("saida"),3),1,5) & "</td>"
       w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(FormatDateTime(RS("chegada"),2)) & ", " &  Mid(FormatDateTime(RS("chegada"),3),1,5) & "</td>"
       w_html = w_html & VbCrLf & "      </tr>"
       Rs.MoveNext
     wend
     w_html = w_html & VbCrLf & "         </table></td></tr>"
  End If
  DesconectaBD
  
  ' Benefícios servidor
  DB_GetSolicData RS, w_chave, "PDGERAL"
  If Not RS.EOF Then
     w_html = w_html & VbCrLf & "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Benefícios recebidos pelo servidor</td>"
     w_html = w_html & VbCrLf & "        <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "          <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"     
     w_html = w_html & VbCrLf & "            <tr valign=""top"">"    
     If cDbl(Nvl(RS("valor_alimentacao"),0)) > 0 Then
        w_html = w_html & VbCrLf & "           <td><font size=""1"">Auxílio-alimentação: <b>Sim</b></td>"
     Else
        w_html = w_html & VbCrLf & "           <td><font size=""1"">Auxílio-alimentação: <b>Não</b></td>"
     End If
     w_html = w_html & VbCrLf & "              <td><font size=""1"">Valor R$: <b>" & FormatNumber(Nvl(RS("valor_alimentacao"),0),2)& "</b></td>"
     w_html = w_html & VbCrLf & "            </tr>"
     w_html = w_html & VbCrLf & "            <tr valign=""top"">"    
     If cDbl(Nvl(RS("valor_transporte"),0)) > 0 Then
        w_html = w_html & VbCrLf & "           <td><font size=""1"">Auxílio-transporte: <b>Sim</b></td>"
     Else
        w_html = w_html & VbCrLf & "           <td><font size=""1"">Auxílio-transporte: <b>Não</b></td>"
     End If
     w_html = w_html & VbCrLf & "              <td><font size=""1"">Valor R$: <b>" & FormatNumber(Nvl(RS("valor_transporte"),0),2)& "</b></td>"
     w_html = w_html & VbCrLf & "            </tr>"
     w_html = w_html & VbCrLf & "          </table></td></tr>"
  End If
  DesconectaBD
  
  'Dados da viagem
  w_html = w_html & VbCrLf & "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da viagem/cálculo das diárias</td>"  
  DB_GetPD_Deslocamento RSQuery, w_chave, null, "DADFIN"
  RSQuery.Sort = "saida, chegada"
  If Not RSQuery.EOF Then
     w_html = w_html & VbCrLf & "     <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "       <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "         <td><font size=""1""><b>Destino</font></td>"
     w_html = w_html & VbCrLf & "         <td><font size=""1""><b>Saida</font></td>"
     w_html = w_html & VbCrLf & "         <td><font size=""1""><b>Chegada</font></td>"
     w_html = w_html & VbCrLf & "         <td><font size=""1""><b>Quantidade de diárias</font></td>"
     w_html = w_html & VbCrLf & "         <td><font size=""1""><b>Valor unitário R$</font></td>"
     w_html = w_html & VbCrLf & "         <td><font size=""1""><b>Total por localidade - R$</font></td>"
     w_html = w_html & VbCrLf & "         </tr>"
     w_cor = conTrBgColor
     w_total = 0
     While Not RSQuery.EOF
       w_html = w_html & VbCrLf & "     <tr valign=""top"" bgcolor=""" & conTrBgColor & """>"
       w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RSQuery("nm_destino") & "</td>"
       w_html = w_html & VbCrLf & "       <td align=""center""><font size=""1"">" & FormataDataEdicao(FormatDateTime(RSQuery("saida"),2)) & ", " &  Mid(FormatDateTime(RSQuery("saida"),3),1,5) & "</td>"
       w_html = w_html & VbCrLf & "       <td align=""center""><font size=""1"">" & FormataDataEdicao(FormatDateTime(RSQuery("chegada"),2)) & ", " &  Mid(FormatDateTime(RSQuery("chegada"),3),1,5) & "</td>"
       w_html = w_html & VbCrLf & "       <td align=""right""><font size=""1"">" & FormatNumber(Nvl(RSQuery("quantidade"),0),1) & "</td>"
       w_html = w_html & VbCrLf & "       <td align=""right""><font size=""1"">" & FormatNumber(Nvl(RSQuery("valor"),0),2) & "</td>"
       w_html = w_html & VbCrLf & "       <td align=""right"" bgcolor=""" & conTrAlternateBgColor & """><font size=""1"">" & FormatNumber(cDbl(FormatNumber(Nvl(RSQuery("quantidade"),0),1)) * cDbl(FormatNumber(Nvl(RSQuery("valor"),0),2)),2) & "</td>"
       w_html = w_html & VbCrLf & "     </tr>"
       w_total = w_total + (cDbl(FormatNumber(Nvl(RSQuery("quantidade"),0),1)) * cDbl(FormatNumber(Nvl(RSQuery("valor"),0),2)))
       RSQuery.MoveNext
     wend
     Rsquery.Close()
     w_html = w_html & VbCrLf & "        <tr bgcolor=""" & conTrBgColor & """>"
     w_html = w_html & VbCrLf & "          <td rowspan=""5"" align=""right"" colspan=""3""><font size=""1"">&nbsp;</td>"
     w_html = w_html & VbCrLf & "          <td colspan=""2""><font size=""1""><b>(a) subtotal:</b></td>"
     w_html = w_html & VbCrLf & "          <td align=""right"" bgcolor=""" & conTrAlternateBgColor & """><font size=""1"">" & FormatNumber(Nvl(w_total,0),2)& "</td>"
     w_html = w_html & VbCrLf & "        </tr>" 
     w_html = w_html & VbCrLf & "        <tr bgcolor=""" & conTrBgColor & """>"
     w_html = w_html & VbCrLf & "          <td colspan=""2""><font size=""1""><b>(b) adicional:</b></td>"
     w_html = w_html & VbCrLf & "          <td align=""right"" bgcolor=""" & conTrBgColor & """><font size=""1"">" & FormatNumber(Nvl(RS("valor_adicional"),0),2)& "</td>"
     w_html = w_html & VbCrLf & "        </tr>" 
     w_html = w_html & VbCrLf & "        <tr bgcolor=""" & conTrBgColor & """>"
     w_html = w_html & VbCrLf & "          <td colspan=""2""><font size=""1""><b>(c) desconto auxílio-alimentação:</b></td>"
     w_html = w_html & VbCrLf & "          <td align=""right"" bgcolor=""" & conTrBgColor & """><font size=""1"">" & FormatNumber(Nvl(RS("desconto_alimentacao"),0),2)& "</td>"
     w_html = w_html & VbCrLf & "        </tr>" 
     w_html = w_html & VbCrLf & "        <tr bgcolor=""" & conTrBgColor & """>"
     w_html = w_html & VbCrLf & "          <td colspan=""2""><font size=""1""><b>(d) desconto auxílio-transporte:</b></td>"
     w_html = w_html & VbCrLf & "          <td align=""right"" bgcolor=""" & conTrBgColor & """><font size=""1"">" & FormatNumber(Nvl(RS("desconto_transporte"),0),2)& "</td>"
     w_html = w_html & VbCrLf & "        </tr>" 
     w_html = w_html & VbCrLf & "        <tr bgcolor=""" & conTrBgColor & """>"
     w_html = w_html & VbCrLf & "          <td colspan=""2""><font size=""1""><b>Total(a + b - c - d):</b></td>"
     w_html = w_html & VbCrLf & "          <td align=""right"" bgcolor=""" & conTrAlternateBgColor & """><font size=""1"">" & FormatNumber(cDbl(FormatNumber(Nvl(w_total,0),2)) + cDbl(FormatNumber(Nvl(RS("valor_adicional"),0),2)) - cDbl(FormatNumber(Nvl(RS("desconto_alimentacao"),0),2)) - cDbl(FormatNumber(Nvl(RS("desconto_transporte"),0),2)),2) & "</td>"
     w_html = w_html & VbCrLf & "        </tr>" 
     w_html = w_html & VbCrLf & "        </table></td></tr>"
  End If
  DesconectaBD
  
  ' Vinculações a tarefas
  DB_GetLinkData RS, w_cliente, "ISTCAD"

  DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, "PDVINC", 5, _
     null, null, null, null, null, null, null, null, null, null, w_chave, _
     null, null, null, null, null, null, null, null, null, null, null, _
     null, null, null, null, null, w_ano
  RS.Sort = "titulo"
  If Not Rs.EOF Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Vinculada às Tarefas</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Nº</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Tarefa</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Início</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Fim</font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Situação</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     w_cor = w_TrBgColor
     w_total = 0
     While Not Rs.EOF
       If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
       w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
       w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">"
       If RS("concluida") = "N" Then
          If RS("fim") < Date() Then
             w_html = w_html & VbCrLf & "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
          ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
             w_html = w_html & VbCrLf & "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
          Else
             w_html = w_html & VbCrLf & "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
          End IF
       Else
          If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
             w_html = w_html & VbCrLf & "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
          Else
             w_html = w_html & VbCrLf & "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
          End IF
       End If
       w_html = w_html & VbCrLf & "        <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações da tarefa."">" & RS("sq_siw_solicitacao") & "</a>"
       If Len(Nvl(RS("titulo"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("titulo"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("titulo"),"-") End If
       If RS("sg_tramite") = "CA" Then
          w_html = w_html & VbCrLf & "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1""><strike>" & w_titulo & "</strike></td>"
       Else
          w_html = w_html & VbCrLf & "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"
       End IF
       If RS("concluida") = "N" Then
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio")) & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("fim")) & "</td>"
       Else
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio_real")) & "</td>"
          w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("fim_real")) & "</td>"
       End If
       w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nm_tramite") & "</td>"
       w_html = w_html & VbCrLf & "      </tr>"
       Rs.MoveNext
     wend
     w_html = w_html & VbCrLf & "         </table></td></tr>"
  End If
  DesconectaBD
  
  ' Se for envio, executa verificações nos dados da solicitação
  w_erro = ValidaViagem(w_cliente, w_chave, Mid(SG,1,2)&"GERAL", null, null, null, Nvl(w_tramite,0))
  If w_erro > "" Then
     w_html = w_html & VbCrLf &  "<tr bgcolor=""" & w_TrBgColor & """><td colspan=2><font size=2>"
     w_html = w_html & VbCrLf &  "<HR>"
     If Mid(w_erro,1,1) = "0" Then
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual."
     ElseIf Mid(w_erro,1,1) = "1" Then
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos."
     Else
        w_html = w_html & VbCrLf &  "  <font color=""#BC3131""><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação."
     End If
     w_html = w_html & VbCrLf &  "  <ul>" & Mid(w_erro,2,1000) & "</ul>"
     w_html = w_html & VbCrLf &  "  </font></td></tr>"
  End If

  If O = "L" or O = "V" or O = "T" Then ' Se for listagem dos dados
     ' Encaminhamentos
     DB_GetSolicLog RS, w_chave, null, "LISTA"
     RS.Sort = "data desc, sq_siw_solic_log desc"
     'w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">&nbsp;</td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ocorrências e Anotações</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Data</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"    
     If Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_TrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados encaminhamentos.</b></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_TrBgColor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "        <td colspan=6><font size=""1"">Fase atual: <b>" & RS("fase") & "</b></td>"
        w_cor = w_TrBgColor
        While Not Rs.EOF
          If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormatDateTime(RS("data"),2) & ", " & FormatDateTime(RS("data"),4)& "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
          If Not P4 = 1 Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
          Else
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS("responsavel") & "</td>"
          End If
          If (Not IsNull(Tvl(RS("sq_demanda_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
             If Not P4 = 1 Then
                w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</td>"
             Else
                w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS("destinatario") & "</td>"
             End If
          Else
          End If
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     w_html = w_html & VbCrLf & "         </table></td></tr>"

  End If
  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</table>"
  
  VisualViagem = w_html

  Set w_tipo_visao          = Nothing 
  Set w_erro                = Nothing 
  Set Rsquery               = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing
  Set w_titulo              = Nothing

End Function
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

