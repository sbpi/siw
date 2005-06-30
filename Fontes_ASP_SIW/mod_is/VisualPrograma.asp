<%

REM =========================================================================
REM Rotina de visualização dos dados do programa
REM -------------------------------------------------------------------------
Function VisualPrograma(w_chave, O, w_usuario, P1, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html
  Dim w_ImagemPadrao
  Dim w_tipo_visao
  Dim w_p2, w_fases
  Dim w_titulo, w_ppa, w_cd_programa
  
  w_html = ""

  ' Recupera os dados do programa
  DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"

  
  w_tipo_visao = 0
  
  'Se for para exibir só a ficha resumo do programa.
  If P1 = 1 or P1 = 2 Then 
     w_html = w_html & VbCrLf & "<div align=center><center>"
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     w_html = w_html & VbCrLf & "      <table width=""100%"" border=""0"">"
     If Not P4 = 1 Then
        w_html = w_html & VbCrLf & "      <tr><td align=""right"" colspan=""3""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Programa.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=volta&P1=&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações da ação."">Exibir todas as informações</a></td></tr>"
     End If
     w_html = w_html & VbCrLf & "   <tr valign=""top""><td colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Programa PPA:<br><b>" & RS("ds_programa") & "</b></td></tr>"
     w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""#D0D0D0""><td colspan=""3""><font size=""1"">Cód:<br><b>" & RS("cd_programa") & "</b></td>"
      w_html = w_html & VbCrLf & "   </table></td></tr>"
     'w_html = w_html & VbCrLf & "                          <td><font size=""1"">Tipo programa:<br><b>" & RS("nm_tipo_programa") & "</b></td></tr>"
     'If P4 = 1 Then
     '   w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Unidade Administrativa:<br><b>" & RS("nm_unidade_adm") & " </b></td>"
     'Else
     '   w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Unidade Administrativa:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_adm"), RS("sq_unidade_adm"), TP) & "</b></td>"
     'End If
     'If P4 = 1 Then
     '   w_html = w_html & VbCrLf & "             <td><font size=""1"">Área planejamento:<br><b>" & RS("nm_unidade_resp") & " </b></td>"
     'Else
     '   w_html = w_html & VbCrLf & "             <td><font size=""1"">Área planejamento:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
     'End If
     If RS("nm_gerente_programa") > "" or RS("nm_gerente_executivo") > "" Then
        If Not IsNull(RS("nm_gerente_programa")) Then           
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente do programa:<br><b>" & RS("nm_gerente_programa") & " </b></td>"
           If Not IsNull(RS("fn_gerente_programa")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_programa") & " </b></td>"
           End If
           If Not IsNull(RS("em_gerente_programa")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_programa") & " </b></td>"
           End If
        End If
        If Not IsNull(RS("nm_gerente_executivo")) Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente executivo do programa:<br><b>" & RS("nm_gerente_executivo") & " </b></td>"
           If Not IsNull(RS("fn_gerente_executivo")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_executivo") & " </b></td>"
           End If
           If Not IsNull(RS("em_gerente_executivo")) Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_executivo") & " </b></td>"
           End If
        End If
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
     If w_tipo_visao = 0 or w_tipo_visao = 1 Then 
        ' Indicadores do programa
        ' Recupera todos os registros para a listagem     
        DB_GetSolicIndic_IS RS1, w_chave, null, "LISTA"
        RS1.Sort = "ordem"
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""left"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b>&nbsp;Indicadores</td></tr>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""3"">"
           w_html = w_html & VbCrLf & "       <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Indicador</font></td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1""><b>PPA</font></td>"
           'w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Tipo</font></td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Índice referência</font></td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Índice programado</font></td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Índice apurado</font></td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Unidade medida</font></td>"
           'w_html = w_html & VbCrLf & "           <td><font size=""1""><b>Data apuracao</font></td>"
           w_html = w_html & VbCrLf & "         </tr>"
           While Not RS1.EOF
              'If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "         <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "           <td><font size=""1"">"
              If cDbl(P4) = 1 Then
                 w_html = w_html & VbCrLf & RS1("titulo") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & RS1("sq_siw_solicitacao") & "&w_chave_aux=" & RS1("sq_indicador") & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Indicador','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("titulo") & "</A></td>"
              End If
              If RS1("cd_indicador") > "" Then
                 w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">Sim</font></td>"
              Else
                 w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">Não</font></td>"
              End If
              'If RS1("tipo") = "R" Then
              '   w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">Resultado</font></td>"
              'Else
              '   w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">Processo</font></td>"
              'End If
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_referencia"),0)),2)& "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("quantidade"),0)),2)& "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_apurado"),0)),2)& "</font></td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(RS1("nm_unidade_medida"),"---") & "</font></td>"
              'w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("apuracao_referencia")),"---") & "</font></td>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""6""><font size=""1""><DD>Situação atual: <b>" & Nvl(RS1("situacao_atual"),"---") & "</DD></font></td></tr>"
              w_html = w_html & VbCrLf & "      <tr><td colspan=""6""><font size=""1""><DD>O índice programado será alcançado: <b>" & RetornaSimNao(RS1("exequivel")) & "</DD></font></td></tr>"
              If RS1("exequivel") = "N" Then
                 w_html = w_html & VbCrLf & "      <tr><td colspan=""6""><font size=""1""><DD>Informar os motivos que impedem o alcance do índice programado? <b>"&Nvl(RS1("justificativa_inexequivel"),"---")&"</DD></font></td></tr>"
                 w_html = w_html & VbCrLf & "      <tr><td colspan=""6""><font size=""1""><DD>Quais as medidas necessárias para que o índice programado seja alcançado?<b>"&Nvl(RS1("outras_medidas"),"---")&"</DD></font></td></tr>"
              End If

              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></td></tr>"
        End If
        RS1.Close
     End If
     If O = "L" Then
        ' Ações do programa
        ' Recupera todos os registros para a listagem
       DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, RS("cd_programa"), null, null, null, null, null, null
       RS1.Sort = "chave"    
        
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem   
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ações</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""3"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Cod.</font></td>"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Ação</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           While Not RS1.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "        <tr bgcolor=""" & w_cor & """ valign=""top"">"
              If Nvl(RS1("sq_siw_solicitacao"),"") > "" and P4 <> 1 Then
                 'w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & RS1("cd_acao")& "." & RS1("cd_unidade")& "</td>"
                 w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1""><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=" & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS1("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS1("cd_acao")& "." & RS1("cd_unidade")& "</a></td>"
              Else
                 w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & RS1("cd_acao")& "." & RS1("cd_unidade")& "</td>"
              End If
              w_html = w_html & VbCrLf & "          <td><font size=""1"">" & RS1("descricao_acao") & "</td>"
              w_html = w_html & VbCrLf & "        </tr>"
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf &  "      </center>"
           w_html = w_html & VbCrLf & "         </table></td></tr>"  
        End If
        RS1.Close
     End If
     w_html = w_html & VbCrLf & "         </table></td></tr>"
     w_html = w_html & VbCrLf & "         </table></td></tr>"
  Else
     If O = "L" or O = "V" Then ' Se for listagem dos dados
        w_html = w_html & VbCrLf & "<div align=center><center>"
        w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

        w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
        w_html = w_html & VbCrLf & "      <tr><td><font size=2>Programa: <b>" & RS("titulo") & "</b></font></td></tr>"
        
        ' Identificação da ação
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"  
        
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Programa PPA:<br><b>" & RS("ds_programa") & " (" & RS("cd_programa") & ")" & " </b></td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Unidade Administrativa:<br><b>" & RS("nm_unidade_adm") & " </b></td>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Unidade Administrativa:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_adm"), RS("sq_unidade_adm"), TP) & "</b></td>"
        End If
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade Orçamentária:<br><b>" & RS("nm_orgao") & " </b></td>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        If RS("mpog_ppa") = "S" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada SPI/MP:<br><b>Sim</b></td>"
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada SPI/MP:<br><b>Não</b></td>"
        End If
        If RS("relev_ppa") = "S" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada SE/MS:<br><b>Sim</b></td>"
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Selecionada SE/MS:<br><b>Não</b></td>"
        End If
        w_html = w_html & VbCrLf & "        <tr valign=""top"">"
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável monitoramento:<br><b>" & RS("nm_sol") & "</b></td>"   
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável monitoramento:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
        End If
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "             <td><font size=""1"">Área planejamento:<br><b>" & RS("nm_unidade_resp") & " </b></td>"
        Else
           w_html = w_html & VbCrLf & "             <td><font size=""1"">Área planejamento:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
        End If
        w_html = w_html & VbCrLf & "        <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Natureza:<br><b>" & RS("nm_natureza") & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Horizonte:<br><b>" & RS("nm_horizonte") & " </b></td>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Tipo do programa:<br><b>" & RS("nm_tipo_programa") & " </b></td>"
        If Nvl(RS("ln_programa"),"---") = "---" Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Endereço na internet:<br><b>" & Nvl(RS("ln_programa"),"---") & "</b></td>"
        Else
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Endereço na internet:<br><a href=""" & Nvl(RS("ln_programa"),"---") & """ target=""blank""><b>" & Nvl(RS("ln_programa"),"---") & "</b></a></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        w_html = w_html & VbCrLf & "        <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "        <tr valign=""top"">"
        If w_tipo_visao = 0 Then ' Se for visão completa
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Início previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
        w_html = w_html & VbCrLf & "          </table>"
        w_html = w_html & VbCrLf & "          <tr valign=""top""><td colspan=""2""><font size=""1"">Parcerias externas:<br><b>" & CRLF2BR(Nvl(RS("proponente"),"---")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <tr valign=""top""><td colspan=""2""><font size=""1"">Parcerias internas:<br><b>" & CRLF2BR(Nvl(RS("palavra_chave"),"---")) & " </b></td>"  
        
        ' Responsaveis
        If RS("nm_gerente_programa") > "" or RS("nm_gerente_executivo") > "" or RS("nm_gerente_adjunto") > "" Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Responsáveis</td>"  
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
           If Not IsNull(RS("nm_gerente_programa")) Then           
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente do programa:<br><b>" & RS("nm_gerente_programa") & " </b></td>"
              If Not IsNull(RS("fn_gerente_programa")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_programa") & " </b></td>"
              End If
              If Not IsNull(RS("em_gerente_programa")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_programa") & " </b></td>"
              End If
           End If
           If Not IsNull(RS("nm_gerente_executivo")) Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente executivo do programa:<br><b>" & RS("nm_gerente_executivo") & " </b></td>"
              If Not IsNull(RS("fn_gerente_executivo")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_executivo") & " </b></td>"
              End If
              If Not IsNull(RS("em_gerente_executivo")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_executivo") & " </b></td>"
              End If
           End If
           If Not IsNull(RS("nm_gerente_adjunto")) Then
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Gerente executivo adjunto:<br><b>" & RS("nm_gerente_adjunto") & " </b></td>"
              If Not IsNull(RS("fn_gerente_adjunto")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_adjunto") & " </b></td>"
              End If
              If Not IsNull(RS("em_gerente_adjunto")) Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_adjunto") & " </b></td>"
              End If
           End If
           w_html = w_html & VbCrLf & "          </table>"
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

        If w_tipo_visao = 0 or w_tipo_visao = 1 Then
           ' Programação Qualitativa
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programação Qualitativa</td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"" color=""red"">Diretrizes e desafios do governo associados ao programa:<br><b>Falta definir qual o campo deve ser visualizado</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"" color=""red"">Objetivo setorial:<br><b>Falta definir qual o campo deve ser visualizado</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Explique como o programa contribui para que o objetivo setorial seja alcançado:<br><b> " & Nvl(RS("contribuicao_objetivo"),"")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Diretrizes do Plano Nacional de Saúde:<br><b> " & Nvl(RS("diretriz"),"")& "</b></div></td>"           
           'w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Problema:<br><b>" & Nvl(RS("contexto"),"---")& "</b></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Objetivo:<br><b>" & Nvl(RS("objetivo"),"---")& "</b></div></td>"           
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Justificativa:<br><b>" & Nvl(RS("justificativa_sigplan"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Público-alvo:<br><b>" & Nvl(RS("publico_alvo"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Resultados esperados:<br><b>" & Nvl(RS("descricao"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Estratégia implementação:<br><b>" & Nvl(RS("estrategia"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Potencialidades:<br><b>" & Nvl(RS("potencialidades"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Sistemática e estratégias a serem adotadas para o monitoramento do programa:<br><b>" & Nvl(RS("estrategia_monit"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Sistemática e metodologias a serem adotadas para avalição do programa:<br><b>" & Nvl(RS("metodologia_aval"),"---")& "</b></div></td>"
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Observações:<br><b>" & Nvl(RS("justificativa"),"---")& "</b></div></td>"
        End If
        
        ' Indicadores do programa
        ' Recupera todos os registros para a listagem
        DB_GetSolicIndic_IS RS1, w_chave, null, "LISTA"
        RS1.Sort = "ordem"
        
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem   
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Indicadores</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Indicador</font></td>"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>PPA</font></td>"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Data apuração</font></td>"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Unidade medida</font></td>"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Periodicidade</font></td>"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Base geográfica</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           While Not RS1.EOF
              If RS1("cd_indicador") > "" Then
                 w_ppa = "Sim"
              Else
                 w_ppa = "Não"
              End If
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "        <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS1("sq_indicador") & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Indicador','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("titulo") & "</A></td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & w_ppa & "</td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("apuracao_referencia")),"---")& "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">" & Nvl(RS1("nm_unidade_medida"),"---")& "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">" & Nvl(RS1("nm_periodicidade"),"---")& "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">" & Nvl(RS1("nm_base_geografica"),"---")& "</td>"
              w_html = w_html & VbCrLf & "        </tr>"
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "      </form>"
           w_html = w_html & VbCrLf &  "      </center>"
           w_html = w_html & VbCrLf & "         </table></td></tr>"  
        End If
        RS1.Close
        
        ' Restricoes do programa
        ' Recupera todos os registros para a listagem
        DB_GetRestricao_IS RS1, "ISPRRESTR", w_chave, null
        'DB_GetRestricao_IS RS1, w_cliente, w_ano, RS("cd_programa"), null
        RS1.Sort = "inclusao desc"
        
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem   
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Restrições</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Descrição</font></td>"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Tipo restrição</font></td>"
           'w_html = w_html & VbCrLf & "<td><font size=""1""><b>Tipo inclusão</font></td>"
           'w_html = w_html & VbCrLf & "<td><font size=""1""><b>Competência</font></td>"
           w_html = w_html & VbCrLf & "<td><font size=""1""><b>Inclusão</font></td>"
           'w_html = w_html & VbCrLf & "<td><font size=""1""><b>Superação</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           While Not RS1.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              w_html = w_html & VbCrLf & "        <tr bgcolor=""" & w_cor & """ valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=Restricao&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS1("sq_restricao") & "&w_cd_programa=" & RS("cd_programa") & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ISPRRESTR','Restricao','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS1("descricao") & "</A></td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">" & RS1("nm_tp_restricao")& "</td>"
              'w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & NVL(RS1("cd_tipo_inclusao"),"---") & "</td>"
              'w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & NVL(RS1("cd_competencia"),"---")& "</td>"
              w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & FormataDataEdicao(RS1("inclusao"))& "</td>"
              'w_html = w_html & VbCrLf & "          <td align=""center""><font size=""1"">" & NVL(FormataDataEdicao(RS1("superacao")),"---")& "</td>"
              w_html = w_html & VbCrLf & "        </tr>"
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "      </form>"
           w_html = w_html & VbCrLf &  "      </center>"
           w_html = w_html & VbCrLf & "         </table></td></tr>"  
        End If
        RS1.Close

        ' Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
        If O = "L" and w_tipo_visao <> 2 Then
           If w_tipo_visao = 0 Then
              ' Programação Financeira
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programação Financeira</td>"
              DB_GetPPADadoFinanc_IS RS1, RS("cd_programa"), null, w_ano, w_cliente, "VALORFONTE"
              If RS1.EOF Then
                 w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1""><DD><b>Nao existe nenhum valor para este programa</b></DD></td>"
              Else
                 w_cor = ""
                 w_html = w_html & VbCrLf & "                      <tr><td valign=""top"" colspan=""2""><font size=""1"">Fonte: SIGPLAN/MP - PPA 2004-2007</td>"
                 w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Tipo de orçamento:<br><b>" & RS1("nm_orcamento") & "</b></td>"
                 w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Valor por fonte: </td>"
                 w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
                 w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                 w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fonte</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2004*</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2005**</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2006</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2007</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2008</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Total</font></td>"
                 w_html = w_html & VbCrLf & "          </tr>"
                 While Not RS1.EOF 
                    If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                    w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
                    w_html = w_html & VbCrLf & "         <td><font size=""1"">" & RS1("nm_fonte")& "</td>"
                    w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
                    w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
                    w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
                    w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
                    w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
                    w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
                    w_html = w_html & VbCrLf & "       </tr>"
                    RS1.MoveNext
                 wend
              w_html = w_html & VbCrLf & "          </table>"   
              End If
              RS1.Close
              DB_GetPPADadoFinanc_IS RS1, RS("cd_programa"), null, w_ano, w_cliente, "VALORTOTAL"
              w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Valor total: </td>"
              If RS1.EOF Then
                 w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1""><DD><b>Nao existe nenhum valor para este programa</b></DD></td>"
              Else
                 w_cor = ""
                 w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
                 w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                 w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2004*</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2005**</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2006</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2007</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>2008</font></td>"
                 w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Total</font></td>"
                 w_html = w_html & VbCrLf & "          </tr>"
                 If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                 w_html = w_html & VbCrLf & "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
                 w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
                 w_html = w_html & VbCrLf & "       </tr>"
                 w_html = w_html & VbCrLf & "       </table>"  
              End If
              RS1.Close
              w_html = w_html & VbCrLf & "<tr><td valign=""top"" colspan=""2""><font size=""1"">* Valor Lei Orçamentária Anual - LOA 2004 + Créditos</td>"
              w_html = w_html & VbCrLf & "<tr><td valign=""top"" colspan=""2""><font size=""1"">** Valor do Projeto de Lei Orçamentária Anual - PLOA 2005</td>"    
           End If
           ' Alerta 
           If RS("aviso_prox_conc") = "S" Then
              ' Configuração dos alertas de proximidade da data limite para conclusão da demanda
              w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alerta</td>"
              w_html = w_html & VbCrLf & "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
              w_html = w_html & VbCrLf & "      <tr><td><font size=1>Será enviado aviso a partir de <b>" & RS("dias_aviso") & "</b> dias antes de <b>" & FormataDataEdicao(RS("fim")) & "</b></font></td></tr>"
           End If
           If P4 <> 1 Then
              ' Interessados na execução da ação
              DB_GetSolicInter RS, w_chave, null, "LISTA"
              RS.Sort = "nome_resumido"
              If Not Rs.EOF Then
                 TP = RemoveTP(TP)&" - Interessados"
                 w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Interessados na execução</b></center></td>"
                 w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center""><font size=""1""><b><center><B><font size=1>Clique <a class=""HL"" HREF=""" & w_dir & "Programa.asp?par=interess&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=4&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""blank"">aqui</a> para visualizar os Interessados na execução</font></b></center></td>"
              End If
              DesconectaBD
           End If
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
           w_html= w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
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
                 If P4 = 1 Then
                    w_html = w_html & VbCrLf & "        <td nowrap><font size=""1""><b>" & RS("responsavel") & "</b></td>"
                 Else
                    w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
                 End If
                 If (Not IsNull(Tvl(RS("sq_projeto_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
                    If P4 = 1 Then
                       w_html = w_html & VbCrLf & "        <td nowrap><font size=""1""><b>" & RS("destinatario") & "</b></td>"
                    Else
                       w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</td>"
                    End If
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
  End If   
  
  VisualPrograma = w_html

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
  
  Set w_ppa                 = Nothing
  Set w_cd_programa         = Nothing

End Function
REM =========================================================================
REM Fim da visualização dos dados do cliente
REM -------------------------------------------------------------------------

%>

