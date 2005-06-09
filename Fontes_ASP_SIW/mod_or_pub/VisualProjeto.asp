<%

REM =========================================================================
REM Rotina de visualização dos dados da ação
REM -------------------------------------------------------------------------
Function VisualProjeto(w_chave, O, w_usuario, P1, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim w_tipo_visao
  Dim RS1,RS2, RS3, RS4
  Dim w_p2, w_fases
  Dim w_titulo
  
  w_html = ""

  ' Recupera os dados da ação
  DB_GetSolicData RS, w_chave, "PJGERAL"

  ' O código abaixo foi comentado em 23/11/2004, devido à mudança na regra definida pelo usuário,
  ' que agora permite visão geral para todos os usuários
  
  ' Recupera o tipo de visão do usuário
  'If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("cadastrador"),0)) = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
  '   cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) Then
  '   ' Se for solicitante, executor ou cadastrador, tem visão completa
  '   w_tipo_visao = 0
  'Else
  '   DB_GetSolicInter Rsquery, w_chave, w_usuario, "REGISTRO"
  '   If Not RSquery.EOF Then
  '      ' Se for interessado, verifica a visão cadastrada para ele.
  '      w_tipo_visao = cDbl(RSquery("tipo_visao"))
  '   Else
  '      DB_GetSolicAreas Rsquery, w_chave, Session("sq_lotacao"), "REGISTRO"
  '      If Not RSquery.EOF Then
  '         ' Se for de uma das unidades envolvidas, tem visão parcial
  '         w_tipo_visao = 1
  '      Else
  '         ' Caso contrário, tem visão resumida
  '         w_tipo_visao = 2
  '      End If
  '   End If
  'End If
  
  w_tipo_visao = 0
  
  'Se for para exibir só a ficha resumo da ação.
  If P1 = 1 or P1 = 2 Then 
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "  <table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "      <table width=""100%"" border=""0"">"
     If Not P4 = 1 Then
        w_html = w_html & VbCrLf & "      <tr><td align=""right"" colspan=""3""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Projeto.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=volta&P1=&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações da ação."">Exibir todas as informações</a></td></tr>"
     End If
     
     ' Se a iniciativa prioritária for informada, exibe.
     If Not IsNull(RS("sq_orprioridade")) Then
        w_html = w_html & VbCrLf & " <tr><td valign=""top"" colspan=""3""><font size=""1"">Iniciativa prioritária:<br><b>" & RS("nm_pri") & "</b></td></tr>"
     End If     
     
     ' Se a ação no PPA for informada, exibe.
     If Not IsNull(RS("sq_acao_ppa")) Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3""><font size=""1"">Programa PPA:<br><b>" & RS("nm_ppa_pai") & "</b></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3""><font size=""1"">Cód:<br><b>" & RS("cd_ppa_pai") & "</b></td></tr>"
        w_html = w_html & VbCrLf & "      <tr bgcolor=""#D0D0D0""><td valign=""top"" colspan=""3""><font size=""1"">Ação PPA:<br><b>" & RS("nm_ppa") & " </b></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""1""><font size=""1"">Cód:<br><b>" & RS("cd_ppa") & "</b></td>"
     Else
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""1""><font size=""1"">Ação:<br><b>" & RS("titulo") & "</b></td>"
     End If
     If w_tipo_visao = 0 Then ' Se for visão completa
        w_html = w_html & VbCrLf & "          <td valign=""top"" colspan=""2""><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td></tr>"
     End If
     DB_GetPersonData RS1, w_cliente, RS("Solicitante"), null, null  
     If P4 = 1 Then
        w_html = w_html & VbCrLf & "         <tr><td valign=""top""><font size=""1"">Responsável monitoramento:<br><b>" & RS("nm_sol") & "</b></td>"
        w_html = w_html & VbCrLf & "             <td valign=""top""><font size=""1"">E-mail:<br><b>" & RS1("email") & "</b></td></tr>"
     Else
        w_html = w_html & VbCrLf & "         <tr><td valign=""top""><font size=""1"">Responsável monitoramento:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
        w_html = w_html & VbCrLf & "             <td valign=""top""><font size=""1"">E-mail:<br><b><A class=""HL"" HREF=""mailto:" & RS1("email") & """>" & RS1("email") & "</a></b></td>"
     End If 
     w_html = w_html & VbCrLf & "                <td valign=""top""><font size=""1"">Telefone:<br><b>" & Nvl(RS1("telefone"),"---") & " </b></td>"
     w_html = w_html & VbCrLf & "            </tr>"
     RS1.Close
     w_html = w_html & VbCrLf & "         </table></td></tr>"
     
     If w_tipo_visao = 0 or w_tipo_visao = 1 Then 
        ' Metas da ação
        ' Recupera todos os registros para a listagem     
        DB_GetSolicEtapa RS1, w_chave, null, "LSTNULL"
        RS1.Sort = "ordem"
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""left"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b>&nbsp;Metas Cadastradas</td></tr>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
            w_html = w_html & VbCrLf & "       <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           While Not RS1.EOF
              w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Produto</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Unidade medida</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>LOA</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Cumulativa</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""2""><font size=""1""><b>Será cumprida</font></td>"
              w_html = w_html & VbCrLf & "           <td rowspan=""1"" colspan=""3""><font size=""1""><b>Quantitativo</font></td>"
              w_html = w_html & VbCrLf & "         </tr>"
              w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Programado</font></td>"
              w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Realizado</font></td>"
              w_html = w_html & VbCrLf & "           <td><font size=""1""><b>% Realizado</font></td>"
              w_html = w_html & VbCrLf & "         </tr>"
              w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "           <td nowrap><font size=""1"">"
              If (RS1("fim_previsto") < Date()) and (cDbl(Nvl(RS1("perc_conclusao"),0)) < 100) Then
                 w_html = w_html & VbCrLf & "           <img src=""" & conImgAtraso & """ border=0 width=15 height=15 align=""center"">"
              ElseIf cDbl(Nvl(RS1("perc_conclusao"),0)) < 100 Then
                 w_html = w_html & VbCrLf & "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
              Else
                 w_html = w_html & VbCrLf & "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
              End If
              If cDbl(P4) = 1 Then
                 w_html = w_html & VbCrLf & RS1("titulo") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & RS1("sq_siw_solicitacao") & "&w_chave_aux=" & RS1("sq_projeto_etapa") & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("titulo") & "</A></td>"
              End If
              w_html = w_html & VbCrLf & "          <td><font size=""1"">" & Nvl(RS1("unidade_medida"),"---") & "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(RS1("nm_programada"),"---") & "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(RS1("nm_cumulativa"),"---") & "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(RS1("nm_exequivel"),"---")  & "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">" & cDbl(Nvl(RS1("quantidade"),0)) & "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">"&(cDbl(Nvl(RS1("quantidade"),0)) * cDbl(Nvl(RS1("perc_conclusao"),0)))/100&"</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""right""><font size=""1"">"& cDbl(Nvl(RS1("perc_conclusao"),0))&"</font></td></tr>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Especifição do produto: <b>" & Nvl(RS1("descricao"),"---") & "</DD></font></td></tr>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Situação atual: <b>" & Nvl(RS1("situacao_atual"),"---") & "</DD></font></td></tr>"
              If RS1("exequivel") = "N" Then
                 w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Quais os motivos para o não cumprimento da meta? <b>"&Nvl(RS1("justificativa_inexequivel"),"---")&"</DD></font></td></tr>"
                 w_html = w_html & VbCrLf & "      <tr><td colspan=""8""><font size=""1""><DD>Quais as medidas necessárias para o cumprimento da meta? <b>"&Nvl(RS1("outras_medidas"),"---")&"</DD></font></td></tr>"
              End If
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        End If
        RS1.Close
     End If
     If w_tipo_visao = 0 Then     
        'Financiamento
        DB_GetFinancAcaoPPA RS1, w_chave, RetornaCliente(), null
        If RS("cd_ppa") > "" Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""left"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b>&nbsp;Financiamento</b>"
           DB_GetOrImport RS2, null, w_cliente, null, null, null, null, null
           RS2.Sort ="data_arquivo desc"
           w_html = w_html & VbCrLf & "          <font size=""2"">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fonte: SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: " & Nvl(FormataDataEdicao(RS2("data_arquivo")),"-") & "</td></tr>"
           RS2.Close
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Cód. Prog.</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Cód. Ação</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Aprovado</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Empenhado</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Saldo</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Liquidado</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>A Liquidar</font></td>"
           w_html = w_html & VbCrLf & "          </tr>" 
           w_html = w_html & VbCrLf & "      <tr valign=""top"">"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("cd_ppa_pai") & "</td>"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("cd_ppa") & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS("aprovado"),2) & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS("empenhado"),2) & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("aprovado"),0.00))-cDbl(Nvl(RS("empenhado"),0.00)),2) & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS("liquidado"),2) & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber((cDbl(Nvl(RS("empenhado"),0.00))-cDbl(Nvl(RS("liquidado"),0.00))),2) & "</td>"
           w_html = w_html & VbCrLf & "      </tr>"   
           If Not RS1.EOF Then
              While Not RS1.EOF
                 w_html = w_html & VbCrLf & "      <tr valign=""top"">"
                 w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("cd_ppa_pai") & "</td>"
                 w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("cd_ppa") & "</td>"
                 w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("aprovado"),2) & "</td>"
                 w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("empenhado"),2) & "</td>"
                 w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("aprovado"),0.00))-cDbl(Nvl(RS1("empenhado"),0.00)),2) & "</td>"
                 w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("liquidado"),2) & "</td>"
                 w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber((cDbl(Nvl(RS1("empenhado"),0.00))-cDbl(Nvl(RS1("liquidado"),0.00))),2) & "</td>"
                 w_html = w_html & VbCrLf & "      </tr>"   
                 w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & conTrAlternateBgColor & """>"
                 w_html = w_html & VbCrLf & "        <td colspan=7><DD><font size=""1""><b>Observação:</b> " & Nvl(RS1("observacao"),"---") & "</DD></td>"
                 w_html = w_html & VbCrLf & "      </tr>"
                 RS1.MoveNext
              wend
           End If
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        ElseIf Not RS1.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""left"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b>&nbsp;Financiamento</b>"
           DB_GetOrImport RS2, null, w_cliente, null, null, null, null, null
           RS2.Sort ="data_arquivo desc"
           w_html = w_html & VbCrLf & "          <font size=""2"">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fonte: SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: " & Nvl(FormataDataEdicao(RS2("data_arquivo")),"-") & "</td></tr>"
           RS2.Close
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Cód. Prog.</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Cód. Ação</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Aprovado</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Empenhado</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Saldo</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Liquidado</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>A Liquidar</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"    
           While Not RS1.EOF
              w_html = w_html & VbCrLf & "      <tr valign=""top"">"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("cd_ppa_pai") & "</td>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("cd_ppa") & "</td>"
              w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("aprovado"),2) & "</td>"
              w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("empenhado"),2) & "</td>"
              w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("aprovado"),0.00))-cDbl(Nvl(RS1("empenhado"),0.00)),2) & "</td>"
              w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("liquidado"),2) & "</td>"
              w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber((cDbl(Nvl(RS1("empenhado"),0.00))-cDbl(Nvl(RS1("liquidado"),0.00))),2) & "</td>"
              w_html = w_html & VbCrLf & "      </tr>"   
              w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & conTrAlternateBgColor & """>"
              w_html = w_html & VbCrLf & "        <td colspan=7><DD><font size=""1""><b>Observação:</b> " & Nvl(RS1("observacao"),"---") & "</DD></td>"
              w_html = w_html & VbCrLf & "      </tr>"
              RS1.MoveNext
           wend
           RS1.close
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        End If
     
        ' Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na ProjetoAtiv.asp para listagem das tarefas
        DB_GetLinkData RS, RetornaCliente(), "ORPCAD"
        DB_GetSolicList rs, RS("sq_menu"), RetornaUsuario(), "ORPCAD", 5, _
           null, null, null, null, null, null, _
           null, null, null, null, _
           null, null, null, null, null, null, null, _
           null, null, null, null, null, w_chave, null, null, null
        RS.sort = "ordem, fim, prioridade"
        If Not RS.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""left"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b>&nbsp;Tarefas Cadastradas</td></tr>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Nº</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Detalhamento</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Parcerias</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Fim<br>previsto</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Programado<br>R$ 1,00</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Executado<br>R$ 1,00</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Fase atual</font></td>"
           w_html = w_html & VbCrLf & "            <td nowrap><font size=""1""><b>Prioridade</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           While Not RS.EOF 
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">"
              If RS("concluida") = "N" Then
                 If RS("fim") < Date() Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                 End IF
              Else
                 If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                 End If
              End If
              If P4 = 1 Then
                 w_html = w_html & VbCrLf             & RS("sq_siw_solicitacao") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "         <A class=""HL"" HREF=""" & w_dir & "Projetoativ.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" target=""blank"">" & RS("sq_siw_solicitacao") & "&nbsp;</a></td>"
              End If
              'If Len(Nvl(RS("assunto"),"-")) > 80 Then w_titulo = Mid(Nvl(RS("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("assunto"),"-") End If
              If RS("sg_tramite") = "CA" Then
                 w_html = w_html & VbCrLf & "      <td><font size=""1""><strike>" & Nvl(RS("assunto"),"-") & "</strike></td>"
              Else
                 w_html = w_html & VbCrLf & "      <td><font size=""1"">" &  Nvl(RS("assunto"),"-") & "</td>"
              End If
              w_html = w_html & VbCrLf & "         <td><font size=""1"">" & Nvl(RS("palavra_chave"),"---") & "</td>"
              w_html = w_html & VbCrLf & "         <td><font size=""1"">" & Nvl(RS("proponente"),"---") & "</td>"
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("fim"),2),"-") & "</td>"
              w_html = w_html & VbCrLf & "         <td align=""right"" nowrap><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("valor"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "         <td align=""right"" nowrap><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("custo_real"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">" & RetornaPrioridade(RS("prioridade")) & "</td></tr>"
              RS.MoveNext        
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>" 
        End If  
        DesconectaBD
     End If     
     w_html = w_html & VbCrLf & "</table>"
     w_html = w_html & VbCrLf & "</center>"
     w_html = w_html & VbCrLf & "</div>"
  Else
     If O = "L" or O = "V" Then ' Se for listagem dos dados
        w_html = w_html & VbCrLf & "<div align=center><center>"
        w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

        w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
        w_html = w_html & VbCrLf & "      <tr><td><font size=2>Ação: <b>" & RS("titulo") & "</b></font></td></tr>"
      
        ' Identificação da ação
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"
        
        ' Se a classificação foi informada, exibe.
        If Not IsNull(RS("sq_cc")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Classificação:<br><b>" & RS("cc_nome") & " </b></td>"
        End If
        
        ' Se a iniciativa prioritária for informada, exibe.
        If Not IsNull(RS("sq_orprioridade")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Iniciativa prioritária:<br><b>" & RS("nm_pri")
           If Not IsNull(RS("cd_pri")) Then 
              w_html = w_html & VbCrLf & " (" & RS("cd_pri") & ")" 
           End If
           If Not IsNull(RS("resp_pri")) Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Responsável iniciativa prioritária:<br><b>" & RS("resp_pri") & " </b></td>"
              If Not IsNull(RS("fone_pri")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_pri") & " </b></td>"
           End If
           If Not IsNull(RS("mail_ppa_pai")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("mail_pri") & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
        w_html = w_html & VbCrLf & "          </b></td>"
     End If     
     
     ' Se a ação no PPA for informada, exibe.
     If Not IsNull(RS("sq_acao_ppa")) Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Programa PPA:<br><b>" & RS("nm_ppa_pai") & " (" & RS("cd_ppa_pai") & ")" & " </b></td>"
        If Not IsNull(RS("resp_ppa_pai")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente executivo:<br><b>" & RS("resp_ppa_pai") & " </b></td>"
           If Not IsNull(RS("fone_ppa_pai")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_ppa_pai") & " </b></td>"
           End If
           If Not IsNull(RS("mail_ppa_pai")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("mail_ppa_pai") & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Ação PPA:<br><b>" & RS("nm_ppa") & " (" & RS("cd_ppa") & ")" & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        If RS("mpog_ppa") = "S" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada MP:<br><b>Sim</b></td>"
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada MP:<br><b>Não</b></td>"
        End If
        If RS("relev_ppa") = "S" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada SE/MS:<br><b>Sim</b></td>"
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada SE/MS:<br><b>Não</b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If Not IsNull(RS("resp_ppa")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Coordenador:<br><b>" & RS("resp_ppa") & " </b></td>"
           If Not IsNull(RS("fone_ppa")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_ppa") & " </b></td>"
           End If
           If Not IsNull(RS("mail_ppa")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("mail_ppa") & " </b></td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
     End If
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     'w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     'w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Abrangência da ação:(Quando Brasília-DF, impacto nacional. Quando a capital de um estado, impacto estadual.)<br><b>" & RS("nm_cidade") & " (" & RS("co_uf") & ")</b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável monitoramento:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Setor responsável monitoramento:<br><b>" & RS("nm_unidade_resp") & " </b></td>"
     If w_tipo_visao = 0 Then ' Se for visão completa
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
     End If
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Início previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
     'w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RS("prioridade")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top""><td><font size=""1"">Parcerias externas:<br><b>" & CRLF2BR(Nvl(RS("proponente"),"---")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top""><td><font size=""1"">Parcerias internas:<br><b>" & CRLF2BR(Nvl(RS("palavra_chave"),"---")) & " </b></td>"  
     w_html = w_html & VbCrLf & "          </table>"

     If w_tipo_visao = 0 or w_tipo_visao = 1 Then
        ' Informações adicionais
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Informações adicionais</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Situação problema:<br><b>" & CRLF2BR(Nvl(RS("problema"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Objetivo da ação:<br><b>" & CRLF2BR(Nvl(RS("objetivo"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Descrição da ação:<br><b>" & CRLF2BR(Nvl(RS("ds_acao"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Público alvo:<br><b>" & CRLF2BR(Nvl(RS("publico_alvo"),"---")) & " </b></td>"
        If Nvl(RS("descricao"),"") > "" Then 
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Resultados da ação:<br><b>" & CRLF2BR(RS("descricao")) & " </b></td>" 
        End If
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Estratégia de implantação:<br><b>" & CRLF2BR(Nvl(RS("estrategia"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Indicadores de desempenho:<br><b>" & CRLF2BR(Nvl(RS("indicadores"),"---")) & " </b></td>"
        If w_tipo_visao = 0 and Nvl(RS("justificativa"),"") > "" Then ' Se for visão completa
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Observações:<br><b>" & CRLF2BR(RS("justificativa")) & " </b></td>"
        End If
     End If
     
     If w_tipo_visao = 0 or w_tipo_visao = 1 Then
        ' Outras iniciativas
        DB_GetOrPrioridadeList RS1, w_chave, RetornaCliente(), null
        RS1.Sort = "Existe desc"
        If Not RS1.EOF Then
           If cDbl(Nvl(RS1("Existe"),0)) > 0 Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Outras iniciativas</td>"
              w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
              w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
              w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
              w_html = w_html & VbCrLf & "          </tr>"    
              While Not RS1.EOF
                 If cDbl(Nvl(RS1("Existe"),0)) > 0 Then
                    If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                    w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
                    w_html = w_html & VbCrLf & "        <td><font size=""1""><ul><li>" & RS1("nome") & "</td>"
                    w_html = w_html & VbCrLf & "      </tr>"
                 End If
                 RS1.MoveNext
              wend
              w_html = w_html & VbCrLf & "         </table></td></tr>"
           End If
        End If
        RS1.close
     End If
     
     ' Dados da conclusão da ação, se ela estiver nessa situação
     If RS("concluida") = "S" and Nvl(RS("data_conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da conclusão</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da execução:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da execução:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Recurso executado:<br><b>" & FormatNumber(RS("custo_real"),2) & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RS("nota_conclusao")) & " </b></td>"
        End If
     End If
  End If
  
  If w_tipo_visao = 0 Then
       'Financiamento
     DB_GetFinancAcaoPPA RS1, w_chave, RetornaCliente(), null
     If RS("cd_ppa") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Financiamento</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Código</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Dotação autorizada</font></td>"
        w_html = w_html & VbCrLf & "          </tr>" 
        w_html = w_html & VbCrLf & "      <tr valign=""top"">"
        w_html = w_html & VbCrLf & "        <td><font size=""1"">" &RS("cd_ppa_pai") & "." & RS("cd_ppa") & "</td>"
        w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nm_ppa") & "</td>"
        w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS("aprovado"),2) & "</td>"
        w_html = w_html & VbCrLf & "      </tr>"   
        If Not RS1.EOF Then
           While Not RS1.EOF
              w_html = w_html & VbCrLf & "      <tr valign=""top"">"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" &RS1("cd_ppa_pai") & "." & RS1("cd_ppa") & "</td>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("nome") & "</td>"
              w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("aprovado"),2) & "</td>"
              w_html = w_html & VbCrLf & "      </tr>"
              w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & conTrAlternateBgColor & """>"
              w_html = w_html & VbCrLf & "        <td colspan=3><DD><font size=""1""><b>Observação:</b> " & Nvl(RS1("observacao"),"---") & "</DD></td>"
              w_html = w_html & VbCrLf & "      </tr>"
              RS1.MoveNext
           wend
        End If
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     ElseIf Not RS1.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Financiamento</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Código</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Aprovado</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        While Not RS1.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">" &RS1("cd_ppa_pai") & "." & RS1("cd_ppa") & "</td>"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS1("nome") & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RS1("aprovado"),2) & "</td>"
           w_html = w_html & VbCrLf & "      </tr>"
           w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
           w_html = w_html & VbCrLf & "        <td colspan=3><DD><font size=""1""><b>Observação:</b> " & Nvl(RS1("observacao"),"---") & "</DD></td>"
           w_html = w_html & VbCrLf & "      </tr>"
           RS1.MoveNext
        wend
        RS1.close
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
  End If 
  ' Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  If O = "L" and w_tipo_visao <> 2 Then
     If RS("aviso_prox_conc") = "S" Then
        ' Configuração dos alertas de proximidade da data limite para conclusão da demanda
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alerta</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
        w_html = w_html & VbCrLf & "      <tr><td><font size=1>Será enviado aviso a partir de <b>" & RS("dias_aviso") & "</b> dias antes de <b>" & FormataDataEdicao(RS("fim")) & "</b></font></td></tr>"
        'w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        'w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","Não") & " </b></td>"
        'w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"
        'w_html = w_html & VbCrLf & "          </table>"
     End If

     ' Interessados na execução da ação
     DB_GetSolicInter RS, w_chave, null, "LISTA"
     RS.Sort = "nome_resumido"
     If Not Rs.EOF Then
        TP = RemoveTP(TP)&" - Interessados"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Interessados na execução</b></center></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center""><font size=""1""><b><center><B><font size=1>Clique <a class=""HL"" HREF=""" & w_dir & "Projeto.asp?par=interess&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=4&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""blank"">aqui</a> para visualizar os Interessados na execução</font></b></center></td>"
        'w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        'w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        'w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo de visão</font></td>"
        'w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Envia e-mail</font></td>"
        'w_html = w_html & VbCrLf & "          </tr>"    
        'While Not Rs.EOF
        '  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        '  w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
        '  w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome_resumido") & "</td>"
        '  w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
        '  w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","Não") & "</td>"
        '  w_html = w_html & VbCrLf & "      </tr>"
        '  Rs.MoveNext
        'wend
        'w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     ' Áreas envolvidas na execução da ação
     DB_GetSolicAreas RS, w_chave, null, "LISTA"
     RS.Sort = "nome"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Áreas/Instituições envolvidas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Papel</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome") & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("papel") & "</td>"
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     ' Etapas da ação
     ' Recupera todos os registros para a listagem
     DB_GetSolicEtapa RS, w_chave, null, "LISTA"
     RS.Sort = "ordem"

    ' Recupera o código da opção de menu  a ser usada para listar as atividades
     w_p2 = ""
     While Not RS.EOF
        If cDbl(Nvl(RS("P2"),0)) > cDbl(0) Then
           w_p2 = RS("P2")
           RS.MoveLast
        End If
        RS.MoveNext
     Wend
     DesconectaBD

     DB_GetSolicEtapa RS, w_chave, null, "LSTNULL"
     RS.Sort = "ordem"
     If Not RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        
        ' Monta função JAVASCRIPT para fazer a chamada para a lista de atividades
        If w_p2 > "" Then
           w_html = w_html & VbCrLf & "<SCRIPT LANGUAGE=""JAVASCRIPT"">"
           w_html = w_html & VbCrLf & "  function lista (projeto, etapa) {"
           w_html = w_html & VbCrLf & "    document.Form.p_projeto.value=projeto;"
           w_html = w_html & VbCrLf & "    document.Form.p_atividade.value=etapa;"
           w_html = w_html & VbCrLf & "    document.Form.p_agrega.value='GRDMETAPA';"
           DB_GetTramiteList RS1, w_P2, null
           RS1.Sort = "ordem"
           w_html = w_html & VbCrLf & "    document.Form.p_fase.value='';"
           w_fases = ""
           While Not RS1.EOF
              If RS1("sigla") <> "CA" Then
                 w_fases = w_fases & "," & RS1("sq_siw_tramite")
              End If
              RS1.MoveNext
           Wend
           w_html = w_html & VbCrLf & "    document.Form.p_fase.value='" & Mid(w_fases,2,100) & "';"
           w_html = w_html & VbCrLf & "    document.Form.submit();"
           w_html = w_html & VbCrLf & "  }"
           w_html = w_html & VbCrLf & "</SCRIPT>"
           DB_GetMenuData RS1, w_p2
           AbreForm "Form", RS1("link"), "POST", "return(Validacao(this));", "Atividades",3,w_P2,1,null,w_TP,RS1("sigla"),w_pagina & par,"L"
           w_html = w_html & VbCrLf & MontaFiltro("POST")
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_projeto"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_atividade"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_agrega"" value="""">"
           w_html = w_html & VbCrLf & "<input type=""Hidden"" name=""p_fase"" value="""">"
        End If
        
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Metas físicas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Metas</font></td>"
        'w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Produto</font></td>"
        'w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Responsável</font></td>"
        'w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Setor</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Execução até</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Conc.</font></td>"
        'w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Ativ.</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
        'w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        'w_html = w_html & VbCrLf & "          <td><font size=""1""><b>De</font></td>"
        'w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Até</font></td>"
        'w_html = w_html & VbCrLf & "          </tr>"
        While Not RS.EOF
           w_html = w_html & VbCrLf & EtapaLinha(w_chave, Rs("sq_projeto_etapa"), Rs("titulo"), RS("nm_resp"), RS("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), RS("qt_ativ"), "<b>", null, "PROJETO")
         
           ' Recupera as etapas vinculadas ao nível acima
           DB_GetSolicEtapa RS1, w_chave, RS("sq_projeto_etapa"), "LSTNIVEL"
           RS1.Sort = "ordem"
           While Not RS1.EOF
             w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, null, "PROJETO")
 
             ' Recupera as etapas vinculadas ao nível acima
             DB_GetSolicEtapa RS2, w_chave, RS1("sq_projeto_etapa"), "LSTNIVEL"
             RS2.Sort = "ordem"
             While Not RS2.EOF
               w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, null, "PROJETO")

               ' Recupera as etapas vinculadas ao nível acima
               DB_GetSolicEtapa RS3, w_chave, RS2("sq_projeto_etapa"), "LSTNIVEL"
               RS3.Sort = "ordem"
               While Not RS3.EOF
                 w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, null, "PROJETO")

                 ' Recupera as etapas vinculadas ao nível acima
                 DB_GetSolicEtapa RS4, w_chave, RS3("sq_projeto_etapa"), "LSTNIVEL"
                 RS4.Sort = "ordem"
                 While Not RS4.EOF
                   w_html = w_html & VbCrLf & EtapaLinha(w_chave, RS4("sq_projeto_etapa"), RS4("titulo"), RS4("nm_resp"), RS4("sg_setor"), RS4("inicio_previsto"), RS4("fim_previsto"), RS4("perc_conclusao"), RS4("qt_ativ"), null, null, "PROJETO")
                   RS4.MoveNext
                 wend

                 RS3.MoveNext
               wend

               RS2.MoveNext
             wend

             RS1.MoveNext
           wend
        
           RS.MoveNext
        wend
        w_html = w_html & VbCrLf & "      </form>"
        w_html = w_html & VbCrLf &  "      </center>"
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD
     
     ' Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na ProjetoAtiv.asp para listagem das tarefas
     DB_GetLinkData RS, RetornaCliente(), "ORPCAD"
      DB_GetSolicList rs, RS("sq_menu"), RetornaUsuario(), "ORPCAD", 5, _
           null, null, null, null, null, null, _
           null, null, null, null, _
           null, null, null, null, null, null, null, _
           null, null, null, null, null, w_chave, null, null, null
     RS.sort = "ordem, fim, prioridade"
     If Not RS.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Tarefas</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nº</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Detalhamento</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fim previsto</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase atual</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"
           While Not RS.EOF 
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "       <tr valign=""top"" bgcolor=""" & w_cor & """>"
              w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">"
              If RS("concluida") = "N" Then
                 If RS("fim") < Date() Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                 End IF
              Else
                 If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                 End If
              End If
              w_html = w_html & VbCrLf & "         <A class=""HL"" HREF=""" & w_dir & "Projetoativ.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" target=""blank"">" & RS("sq_siw_solicitacao") & "&nbsp;</a>"
              w_html = w_html & VbCrLf & "         <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</td>"
              If Len(Nvl(RS("assunto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("assunto"),"-") End If
              If RS("sg_tramite") = "CA" Then
                 w_html = w_html & VbCrLf & "      <td><font size=""1""><strike>" & w_titulo & "</strike></td>"
              Else
                 w_html = w_html & VbCrLf & "      <td><font size=""1"">" & w_titulo & "</td>"
              End If
              w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("fim"),2),"-") & "</td>"
              w_html = w_html & VbCrLf & "         <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
              RS.MoveNext        
           wend
        w_html = w_html & VbCrLf & "         </table></td></tr>" 
     End If  
     DesconectaBD
     
     ' Recursos envolvidos na execução da ação
     DB_GetSolicRecurso RS, w_chave, null, "LISTA"
     RS.Sort = "tipo, nome"
     If Not Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Recursos</td>"
        w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
        w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"
        w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Finalidade</font></td>"
        w_html = w_html & VbCrLf & "          </tr>"    
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RetornaTipoRecurso(RS("tipo")) & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome") & "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("finalidade"),"---")) & "</td>"
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
     End If
     DesconectaBD
     w_html = w_html & VbCrLf & "         </table></td></tr>"

  End If

  If O = "L" or O = "V" Then ' Se for listagem dos dados
     If w_tipo_visao <> 2 Then
        ' Arquivos vinculados
        DB_GetSolicAnexo RS, w_chave, null, w_cliente
        RS.Sort = "nome"
        If Not Rs.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Arquivos anexos</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Título</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Descrição</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Tipo</font></td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1""><b>KB</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           w_cor = conTrBgColor
           While Not Rs.EOF
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
             w_html = w_html & VbCrLf & "        <td><font size=""1""><a class=""HL"" href=""" & conFileVirtual & w_cliente & "/" & RS("caminho") & """ target=""_blank"" title=""Clique para exibir o arquivo em outra janela."">" & RS("nome") & "</a></td>"
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & Nvl(RS("descricao"),"---") & "</td>"
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("tipo") & "</td>"
             w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & Round(cDbl(RS("tamanho"))/1024,1) & "&nbsp;</td>"
             w_html = w_html & VbCrLf & "      </tr>"
             Rs.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        End If
        DesconectaBD
     End If
     ' Encaminhamentos
     DB_GetSolicLog RS, w_chave, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ocorrências e Anotações</td>"
     w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Data</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Despacho/Observação</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase / Destinatário</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"    
     If Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados encaminhamentos.</b></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        w_html = w_html & VbCrLf & "        <td colspan=6><font size=""1"">Fase atual: <b>" & RS("fase") & "</b></td>"
        While Not Rs.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormatDateTime(RS("data"),2) & ", " & FormatDateTime(RS("data"),4)& "</td>"
          w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
          If (Not IsNull(Tvl(RS("sq_projeto_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</td>"
          ElseIf (Not IsNull(Tvl(RS("sq_projeto_log")))) and IsNull(Tvl(RS("destinatario"))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anotação</td>"
          Else
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RS("tramite"),"---") & "</td>"
          End If
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD

     w_html = w_html & VbCrLf & "</table>"
  End If
  End If
  
  VisualProjeto = w_html

  Set w_p2                  = Nothing 
  Set w_fases               = Nothing 
  Set RS2                   = Nothing 
  Set RS3                   = Nothing 
  Set RS4                   = Nothing 

  Set w_tipo_visao          = Nothing 
  Set w_erro                = Nothing 
  Set Rsquery               = Nothing
  Set w_ImagemPadrao        = Nothing
  Set w_Imagem              = Nothing

End Function
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

