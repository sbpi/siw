<%

REM =========================================================================
REM Rotina de visualização dos dados do programa
REM -------------------------------------------------------------------------
Function VisualPrograma(w_chave, O, w_usuario, P1, P4, w_identificacao, w_responsavel, w_qualitativa, w_orcamentaria, w_indicador, w_restricao, w_interessado, w_anexo, w_acao, w_ocorrencia, w_consulta)

  Dim w_html
  
  w_html = ""

  ' Recupera os dados do programa
  DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"
  

  'Se for para exibir só a ficha resumo do programa.
  If P1 = 1 or P1 = 2 or P1 = 3 Then
     If Not P4 = 1 Then
        w_html = w_html & VbCrLf & "      <tr><td align=""right"" colspan=""2""><font size=""1""><br><b><A class=""HL"" HREF=""" & w_dir & "Programa.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=volta&P1=&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações da ação."">Exibir todas as informações</a></td></tr>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""  bgcolor=""#f0f0f0""><div align=justify><font size=""2""><b>PROGRAMA: "& RS("cd_programa")& " - " & RS("ds_programa") & "</b></font></div></td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
     
     ' Identificação do programa
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>IDENTIFICAÇÃO DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
    
     w_html = w_html & VbCrLf & "   <tr><td width=""30%""><font size=""1""><b>Unidade Orçamentária:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_orgao") & "</font></td></tr>"
     If P4 = 1 Then
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Unidade Administrativa:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_unidade_adm") & "</font></td></tr>"
     Else
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Unidade Administrativa:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & ExibeUnidade("../", w_cliente, RS("nm_unidade_adm"), RS("sq_unidade_adm"), TP) & "</font></td></tr>"
     End If     
     If P4 = 1 Then
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Área Planejamento:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_unidade_resp") & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Responsável Monitoramento:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_sol") & "</font></td></tr>"
     Else
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Área Planejamento:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & ExibeUnidade("../", w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Responsável Monitoramento:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol_comp")) & "</font></td></tr>"
     End If
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Endereço Internet:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("ln_programa"),"-") & "</font></td></tr>"

     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Recurso Programado " & w_ano & ":</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">R$ " & FormatNumber(RS("valor"),2) & "</font></td></tr>"

     ' Indicadores do programa
     ' Recupera todos os registros para a listagem     
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>INDICADORES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     DB_GetSolicIndic_IS RS1, w_chave, null, "LISTA", null, null
     RS1.Sort = "ordem"
     If Not RS1.EOF Then
        w_cont = 1
        While Not RS1.EOF
           DB_GetSolicIndic_IS RS2, w_chave, RS1("sq_indicador"), "REGISTRO", null, null
           w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><font size=""1""><b>" & w_cont & ") Indicador:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><font size=""1""><b>" & RS2("titulo") & "</b></font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Tipo indicador:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  RS2("nm_tipo") & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Quantitativo:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormatNumber((Nvl(RS2("quantidade"),0)),2) & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Índice de Referência:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  RS2("valor_referencia") & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Data de Referência:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(RS2("apuracao_referencia"),2)) & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Índice Apurado:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS2("valor_apurado"),0)),0) & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Indicador PPA:</b></font></td>"
           If Nvl(RS2("cd_indicador"),"") > "" Then
              w_html = w_html & VbCrLf & "       <td><font size=""1"">Sim</font></td></tr>"
           Else
              w_html = w_html & VbCrLf & "       <td><font size=""1"">Não</font></td></tr>"
           End If
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Unidade de Medida:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  Nvl(RS2("nm_unidade_medida"),"-") & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Cumulativo:</b></font></td>"
           If RS2("cumulativa") = "N" Then
              w_html = w_html & VbCrLf & "       <td><font size=""1"">Não</font></td></tr>"
           Else
              w_html = w_html & VbCrLf & "       <td><font size=""1"">Sim</font></td></tr>"
           End If
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
           w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
           w_html = w_html & VbCrLf & "       <tr><td bgColor=""#cccccc"" colspan=""4""><div align=""center""><font size=""1""><b>Previsão</b></font></div></td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2004</b></font></div></td>"
           w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2005</b></font></div></td>"
           w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2006</b></font></div></td>"
           w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2007</b></font></div></td>"
           w_html = w_html & VbCrLf & "       </tr>"
           w_html = w_html & VbCrLf & "       <tr><td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS2("previsao_ano_1"),0)),2) & "</td>"
           w_html = w_html & VbCrLf & "           <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS2("previsao_ano_2"),0)),2) & "</td>"
           w_html = w_html & VbCrLf & "           <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS2("previsao_ano_3"),0)),2) & "</td>"
           w_html = w_html & VbCrLf & "           <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS2("previsao_ano_4"),0)),2) & "</td>"
           w_html = w_html & VbCrLf & "       </tr>"
           w_html = w_html & VbCrLf & "     </table></div></td></tr>"
           RS2.Close
           RS1.MoveNext
           w_cont = w_cont + 1
        wend
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Nenhum indicador cadastrado para este programa</font></div></td></tr>"
     End If
     RS1.Close

     ' Listagem das restrições do programa
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>RESTRIÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     DB_GetRestricao_IS RS1, "ISPRRESTR", w_chave, null
     RS1.Sort = "inclusao desc"
     If Not RS1.EOF Then
        w_cont = 1
        While Not RS1.EOF
           w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><font size=""1""><b>" & w_cont & ") Tipo:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><font size=""1"">" &  RS1("nm_tp_restricao") & "</b></font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Descrição:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  RS1("descricao") & "</font></td></tr>"     
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Providência:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  Nvl(RS1("providencia"),"-") & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Data de Inclusão:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(RS1("inclusao"),2)) & ", " & FormatDateTime(RS1("inclusao"),4) & "</font></td></tr>"
           w_cont = w_cont + 1
           RS1.MoveNext
        Wend
     Else
         w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Nenhuma restrição cadastrada</font></div></td></tr>"
     End If

     ' Ações do programa
     ' Recupera todos os registros para a listagem
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>AÇÕES DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, RS("cd_programa"), null, null, null, null, null, null
     RS1.Sort = "chave"       
     If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem  
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
        w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
        w_html = w_html & VbCrLf & "       <tr><td bgColor=""#cccccc"" colspan=""4""><div align=""center""><font size=""1""><b>Ações</b></font></div></td></tr>"
        w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0"" width=""5%"" ><div align=""center""><font size=""1""><b>Cód.</b></font></div></td>"
        w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0"" width=""46%""><div align=""center""><font size=""1""><b>Descrição</b></font></div></td>"
        w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0"" width=""30%""><div align=""center""><font size=""1""><b>Unidade</b></font></div></td>"
        w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0"" width=""14%""><div align=""center""><font size=""1""><b>Fase</b></font></div></td>"
        w_html = w_html & VbCrLf & "       </tr>"
        While Not RS1.EOF
           If Nvl(RS1("sq_siw_solicitacao"),"") > "" and P4 <> 1 Then
              w_html = w_html & VbCrLf & "       <tr valign=""top""><td align=""center""><font size=""1""><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=" & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS1("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS1("cd_acao") & "</a></td>"
           Else
              w_html = w_html & VbCrLf & "       <tr valign=""top""><td align=""center""><font size=""1"">" & RS1("cd_acao") & "</td>"
           End If
           w_html = w_html & VbCrLf & "           <td><font size=""1"">" & RS1("descricao_acao") & "</td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1"">" & RS1("cd_unidade") & " - " & RS1("ds_unidade") & "</td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1"">" & Nvl(RS1("nm_tramite"),"Não Cadastrada") & "</td>"
           w_html = w_html & VbCrLf & "       </tr>"
           RS1.MoveNext
        wend
         w_html = w_html & VbCrLf & "     </table></div></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Não existe nenhuma ação para este programa</font></div></td></tr>"
     End If
     RS1.Close
     
     ' Encaminhamentos
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     DB_GetSolicLog RS, w_chave, null, "LISTA"
     RS.Sort = "data desc"
     If Not RS.EOF Then
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
        w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
        w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Data</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Ocorrência/Anotação</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Responsável</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Fase/Destinatário</b></font></div></td>"
        w_html = w_html & VbCrLf & "       </tr>"
        w_html = w_html & VbCrLf & "       <tr><td colspan=""4""><font size=""1"">Fase Atual: <b>" & RS("fase") & "</b></td></tr>"
        While Not RS.EOF
           w_html = w_html & VbCrLf & "    <tr><td nowrap><font size=""1"">" & FormataDataEdicao(FormatDateTime(RS("data"),2)) & ", " & FormatDateTime(RS("data"),4)& "</td>"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</font></td>"
           w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</font></td>"
           If (Not IsNull(Tvl(RS("sq_projeto_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
              w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</font></td>"
           ElseIf (Not IsNull(Tvl(RS("sq_projeto_log")))) and IsNull(Tvl(RS("destinatario"))) Then
              w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anotação</font></td>"
           Else
              w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RS("tramite"),"---") & "</font></td>"
           End If
           w_html = w_html & VbCrLf & "      </tr>"
           RS.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></div></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Não foi encontrado nenhum encaminhamento</font></div></td></tr>"
     End If
     DesconectaBD
     w_html = w_html & VbCrLf & "</table>"
  Else        
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""  bgcolor=""#f0f0f0""><div align=justify><font size=""2""><b>PROGRAMA: "& RS("cd_programa")& " - " & RS("ds_programa") & "</b></font></div></td></tr>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
     
     ' Identificação do programa
     If uCase(w_identificacao) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>IDENTIFICAÇÃO DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
    
        w_html = w_html & VbCrLf & "   <tr><td width=""30%""><font size=""1""><b>Unidade Orçamentária:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1""><b>" & RS("nm_orgao") & "</b></font></div></td></tr>"
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Unidade Administrativa:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_unidade_adm") & "</font></td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Unidade Administrativa:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & ExibeUnidade("../", w_cliente, RS("nm_unidade_adm"), RS("sq_unidade_adm"), TP) & "</font></td></tr>"
        End If     
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Área Planejamento:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_unidade_resp") & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Responsável Monitoramento:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_sol") & "</font></td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Área Planejamento:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & ExibeUnidade("../", w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Responsável Monitoramento:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol_comp")) & "</font></td></tr>"
        End If
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Endereço Internet:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("ln_programa"),"-") & "</font></td></tr>"
        
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Recurso Programado " & w_ano & ":</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">R$ " & FormatNumber(RS("valor"),2) & "</font></td></tr>"
        
        If RS("mpog_ppa") = "S" Then
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Selecionado SPI/MP:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">Sim</font></td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Selecionado SPI/MP:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">Não</font></td></tr>"
        End If
        If RS("relev_ppa") = "S" Then
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Selecionado SE/SEPPIR:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">Sim</font></td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Selecionado SE/SEPPIR:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">Não</font></td></tr>"
        End If
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Natureza:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_natureza") & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Tipo Programa:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_tipo_programa") & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Horizonte:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_horizonte") & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Parcerias Externas:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & CRLF2BR(Nvl(RS("proponente"),"-")) & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Parcerias Internas:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & CRLF2BR(Nvl(RS("palavra_chave"),"-")) & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Fase Atual do Programa:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("nm_tramite"),"-") & "</font></td></tr>"
     End If
     
     ' Responsaveis
     If uCase(w_responsavel) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>RESPONSÁVEIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
        If RS("nm_gerente_programa") > "" or RS("nm_gerente_executivo") > "" or RS("nm_gerente_adjunto") > "" Then
           If Not IsNull(RS("nm_gerente_programa")) Then
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Gerente do Programa:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_gerente_programa") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Telefone:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("fn_gerente_programa"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>E-mail:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("em_gerente_programa"),"-") & "</font></td></tr>"
           End If
           If Not IsNull(RS("nm_gerente_executivo")) Then
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Gerente Executivo do Programa:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_gerente_executivo") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Telefone:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("fn_gerente_executivo"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>E-mail:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("em_gerente_executivo"),"-") & "</font></td></tr>"
           End If
           If Not IsNull(RS("nm_gerente_adjunto")) Then
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Gerente Executivo Adjunto:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS("nm_gerente_adjunto") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Telefone:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("fn_gerente_adjunto"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>E-mail:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS("em_gerente_adjunto"),"-") & "</font></td></tr>"
           End If
        Else
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><font size=""1""><div align=""center"">Nenhum responsável cadastrado</div></font></td>"
        End If
     End If
     
     If uCase(w_identificacao) = uCase("sim") Then   
        ' Dados da conclusão do programa, se ela estiver nessa situação
        If RS("concluida") = "S" and Nvl(RS("data_conclusao"),"") > "" Then
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONCLUSÃO DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Recurso Executado:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormatNumber(RS("custo_real"),2) & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Nota de Conclusão:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & CRLF2BR(RS("nota_conclusao")) & "</font></div></td></tr>"
        End If
     End If
     
     ' Programação Qualitativa
     If uCase(w_qualitativa) = uCase("sim") Then   
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><br><font size=""2""><b>PROGRAMAÇÃO QUALITATIVA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Objetivo:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("objetivo"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Contribuição do programa para que o objetivo setorial seja alcançado:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("contribuicao_objetivo"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Justificativa:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("justificativa_sigplan"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Público Alvo:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("publico_alvo"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Resultados Esperados:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("descricao"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Estratégia de Implementação:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("estrategia"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Potencialidades:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("potencialidades"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Sistemática e estratégias a serem adotadas para o monitoramento do programa:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("estrategia_monit"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Sistemática e estratégias a serem adotadas para a avaliação do programa:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("metodologia_aval"),"-") & "</font></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Observações:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS("justificativa"),"-") & "</font></div></td></tr>"
     End If
     
     ' Programação orçamentaria
     If uCase(w_orcamentaria) = uCase("sim") Then   
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>PROGRAMAÇÃO ORÇAMENTÁRIA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
        w_cont = 1
        DB_GetPPADadoFinanc_IS RS1, RS("cd_programa"), null, w_ano, w_cliente, "VALORFONTE"
        If RS1.EOF Then
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><font size=""1"">Nao existe nenhuma programação financeira para este programa</font></td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Valor Estimado para o Programa:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormatNumber(Nvl(RS("valor_estimado"),0),2) & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Valor no PPA:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormatNumber(Nvl(RS("valor_ppa"),0),2) & "</font></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Tipo de Orçamento:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  Nvl(RS1("nm_orcamento"),"-") & "</font></td></tr>"        
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2"" valigin=""top"" bgcolor=""#f0f0f0""><font size=""1""><b>Valor por Fonte:</b></font></td>"
           While Not RS1.EOF 
              w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><font size=""1""><b>" & w_cont & ") Fonte:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><font size=""1""><b>" & RS1("nm_fonte") & "</b></font></td></tr>"     
              w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
              w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
              w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2004</b></font></div></td>"
              w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2005</b></font></div></td>"
              w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2006</b></font></div></td>"  
              w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2007</b></font></div></td>"
              w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2008</b></font></div></td>"    
              w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Total 2004-2008</b></font></div></td></tr>"        
              w_html = w_html & VbCrLf & "       <tr><td><div align=""right""><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00))) & "</font></div></td>"     
              w_html = w_html & VbCrLf & "           <td><div align=""right""><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00))) & "</font></div></td>"     
              w_html = w_html & VbCrLf & "           <td><div align=""right""><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00))) & "</font></div></td>"     
              w_html = w_html & VbCrLf & "           <td><div align=""right""><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00))) & "</font></div></td>"     
              w_html = w_html & VbCrLf & "           <td><div align=""right""><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00))) & "</font></div></td>"     
              w_html = w_html & VbCrLf & "           <td><div align=""right""><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00))) & "</font></div></td></tr>"     
              w_html = w_html & VbCrLf & "     </table></div></td></tr>" 
              RS1.MoveNext
              w_cont = w_cont + 1
           wend 
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><font size=""1"">Fonte dos Dados: SIGPLAN/MP</font></td></tr>"
        End If
        RS1.Close
     End If
     
     ' Indicadores do programa
     If uCase(w_indicador) = uCase("sim") Then   
        ' Recupera todos os registros para a listagem     
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>INDICADORES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
        DB_GetSolicIndic_IS RS1, w_chave, null, "LISTA", null, null
        RS1.Sort = "ordem"
        If Not RS1.EOF Then
           w_cont = 1
           While Not RS1.EOF
              DB_GetSolicIndic_IS RS2, w_chave, RS1("sq_indicador"), "REGISTRO", null, null
              w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><font size=""1""><b>" & w_cont & ") Indicador:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><font size=""1""><b>" & RS2("titulo") & "</b></font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Tipo indicador:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  Nvl(RS2("nm_tipo"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Quantitativo:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormatNumber((Nvl(RS2("quantidade"),0)),2) & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Índice de Referência:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS2("valor_referencia"),0)),0) & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Data de Referência:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(RS2("apuracao_referencia"),2)) & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Índice Apurado:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormatNumber(cDbl(Nvl(RS2("valor_apurado"),0)),0) & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Indicador PPA:</b></font></td>"
              If Nvl(RS2("cd_indicador"),"") > "" Then
                 w_html = w_html & VbCrLf & "       <td><font size=""1"">Sim</font></td></tr>"
              Else
                 w_html = w_html & VbCrLf & "       <td><font size=""1"">Não</font></td></tr>"
              End If
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Unidade de Medida:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  Nvl(RS2("nm_unidade_medida"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Cumulativo:</b></font></td>"
              If RS2("cumulativa") = "N" Then
                 w_html = w_html & VbCrLf & "       <td><font size=""1"">Não</font></td></tr>"
              Else
                 w_html = w_html & VbCrLf & "       <td><font size=""1"">Sim</font></td></tr>"
              End If
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Fórmula de Calculo:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("formula"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Fonte:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("fonte"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Periodicidade:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("nm_periodicidade"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Base Geográfica:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("nm_base_geografica"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Conceituação:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("conceituacao"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Interpretação:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("interpretacao"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Usos:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("usos"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Limitações:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("limitacoes"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Categorias sugeridas para análise:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("categoria_analise"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Dados estatísticos e comentários:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("comentarios"),"-") & "</font></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td valign=""top""><font size=""1""><b>Observações:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td valign=""top""><font size=""1"">" &  Nvl(RS2("observacao"),"-") & "</font></td></tr>"
              
              w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
              w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
              w_html = w_html & VbCrLf & "       <tr><td bgColor=""#cccccc"" colspan=""4""><div align=""center""><font size=""1""><b>Previsão</b></font></div></td></tr>"
              w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2004</b></font></div></td>"
              w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2005</b></font></div></td>"
              w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2006</b></font></div></td>"
              w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>2007</b></font></div></td>"
              w_html = w_html & VbCrLf & "       </tr>"
              w_html = w_html & VbCrLf & "       <tr><td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS2("previsao_ano_1"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "           <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS2("previsao_ano_2"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "           <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS2("previsao_ano_3"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "           <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS2("previsao_ano_4"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "       </tr>"
              w_html = w_html & VbCrLf & "     </table></div></td></tr>"
              RS2.Close
              RS1.MoveNext
             w_cont = w_cont + 1
            wend
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Nenhum indicador cadastrado para este programa</font></div></td></tr>"
        End If
        RS1.Close
     End If
     
     ' Listagem das restrições do programa
     If uCase(w_restricao) = uCase("sim") Then   
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>RESTRIÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"     
        DB_GetRestricao_IS RS1, "ISPRRESTR", w_chave, null
        RS1.Sort = "inclusao desc"
        If Not RS1.EOF Then
           w_cont = 1
           While Not RS1.EOF
              w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><font size=""1""><b>" & w_cont & ") Tipo:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><font size=""1""><b>" &  RS1("nm_tp_restricao") & "</b></font></td></tr>"     
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Descrição:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  RS1("descricao") & "</font></td></tr>"     
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Providência:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  Nvl(RS1("providencia"),"-") & "</font></td></tr>"     
              w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Data de Inclusão:</b></font></td>"
              w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(RS1("inclusao"),2)) & ", " & FormatDateTime(RS1("inclusao"),4) & "</font></td></tr>"     
              w_cont = w_cont + 1
              RS1.MoveNext
           Wend
        Else
            w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Nenhuma restrição cadastrada</font></div></td></tr>"     
        End If     
        RS1.Close
     End If
     
     ' Interessados na execução do programa
     If uCase(w_interessado) = uCase("sim") Then   
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>INTERESSADOS NA EXECUÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
        DB_GetSolicInter RS1, w_chave, null, "LISTA"
        RS1.Sort = "nome_resumido"
        If Not RS1.EOF Then
           TP = RemoveTP(TP)&" - Interessados"
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Clique <a class=""HL"" HREF=""" & w_dir & "Acao.asp?par=interess&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=4&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""blank"">aqui</a> para visualizar os Interessados na execução</font></div></td></tr>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Nenhum interessado cadastrado</font></div></td></tr>"
        End If
        RS1.Close  
     End If
     
     ' Arquivos vinculados ao programa
     If uCase(w_anexo) = uCase("sim") Then   
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
        DB_GetSolicAnexo RS1, w_chave, null, w_cliente
        RS1.Sort = "nome"
        If Not RS1.EOF Then
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
           w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
           w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Título</b></font></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Descrição</b></font></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Tipo</b></font></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>KB</b></font></div></td>"    
           w_html = w_html & VbCrLf & "       </tr>"
           While Not RS1.EOF
              w_html = w_html & VbCrLf & "       <tr><td><font size=""1"">" & LinkArquivo("HL", w_cliente, RS1("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS1("nome"), null) & "</font></td>"
              w_html = w_html & VbCrLf & "           <td><font size=""1"">" & Nvl(RS1("descricao"),"-") & "</font></td>"
              w_html = w_html & VbCrLf & "           <td><font size=""1"">" & RS1("tipo") & "</font></td>"
              w_html = w_html & VbCrLf & "         <td><div align=""right""><font size=""1"">" & Round(cDbl(RS1("tamanho"))/1024,1) & "&nbsp;</font></td>"
              w_html = w_html & VbCrLf & "      </tr>"
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></div></td></tr>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Nenhuma arquivo cadastrado</font></div></td></tr>"
        End If
        RS1.Close
     End If
     
     ' Ações do programa
     If uCase(w_acao) = uCase("sim") Then   
        ' Recupera todos os registros para a listagem
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>AÇÕES DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
        DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, RS("cd_programa"), null, null, null, null, null, null
        RS1.Sort = "chave"       
        If Not RS1.EOF Then ' Se não foram selecionados registros, exibe mensagem  
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
           w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
           w_html = w_html & VbCrLf & "       <tr><td bgColor=""#cccccc"" colspan=""4""><div align=""center""><font size=""1""><b>Ações</b></font></div></td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0"" width=""5%"" ><div align=""center""><font size=""1""><b>Cód.</b></font></div></td>"
           w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0"" width=""46%""><div align=""center""><font size=""1""><b>Descrição</b></font></div></td>"
           w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0"" width=""30%""><div align=""center""><font size=""1""><b>Unidade</b></font></div></td>"
           w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0"" width=""14%""><div align=""center""><font size=""1""><b>Fase</b></font></div></td>"
           w_html = w_html & VbCrLf & "       </tr>"
           While Not RS1.EOF
              If Nvl(RS1("sq_siw_solicitacao"),"") > "" and P4 <> 1 Then
                 w_html = w_html & VbCrLf & "       <tr valign=""top""><td align=""center""><font size=""1""><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=" & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS1("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS1("cd_acao") & "</a></td>"
              Else
                 w_html = w_html & VbCrLf & "       <tr valign=""top""><td align=""center""><font size=""1"">" & RS1("cd_acao") & "</td>"
              End If
              w_html = w_html & VbCrLf & "           <td><font size=""1"">" & RS1("descricao_acao") & "</td>"
              w_html = w_html & VbCrLf & "           <td><font size=""1"">" & RS1("cd_unidade") & " - " & RS1("ds_unidade") & "</td>"
              w_html = w_html & VbCrLf & "           <td><font size=""1"">" & Nvl(RS1("nm_tramite"),"Não Cadastrada") & "</td>"
              w_html = w_html & VbCrLf & "       </tr>"
              RS1.MoveNext
           wend
            w_html = w_html & VbCrLf & "     </table></div></td></tr>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Não existe nenhuma ação para este programa</font></div></td></tr>"
        End If
        RS1.Close     
     End If
     
     ' Encaminhamentos
     If uCase(w_ocorrencia) = uCase("sim") Then   
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"     
        DB_GetSolicLog RS, w_chave, null, "LISTA"
        RS.Sort = "data desc"
        If Not RS.EOF Then
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
           w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
           w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Data</b></font></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Ocorrência/Anotação</b></font></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Responsável</b></font></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Fase/Destinatário</b></font></div></td>"    
           w_html = w_html & VbCrLf & "       </tr>"
           w_html = w_html & VbCrLf & "       <tr><td colspan=""4""><font size=""1"">Fase Atual: <b>" & RS("fase") & "</b></td></tr>"
           While Not RS.EOF
              w_html = w_html & VbCrLf & "    <tr><td nowrap><font size=""1"">" & FormataDataEdicao(FormatDateTime(RS("data"),2)) & ", " & FormatDateTime(RS("data"),4)& "</td>"
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</font></td>"
              w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</font></td>"
              If (Not IsNull(Tvl(RS("sq_projeto_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
                 w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</font></td>"
              ElseIf (Not IsNull(Tvl(RS("sq_projeto_log")))) and IsNull(Tvl(RS("destinatario"))) Then
                 w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anotação</font></td>"
              Else
                 w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RS("tramite"),"---") & "</font></td>"
              End If
              w_html = w_html & VbCrLf & "      </tr>"
              RS.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></div></td></tr>"        
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Não foi encontrado nenhum encaminhamento</font></div></td></tr>"
        End If
        DesconectaBD
     End If
     
     'Dados da Consulta
     If uCase(w_consulta) = uCase("sim") Then   
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"     
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Consulta Realizada por:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  Session("NOME_RESUMIDO") & "</font></td></tr>"     
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Data da Consulta:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(now(),2)) & ", " & FormatDateTime(now(),4) & "</font></td></tr>"     
     End If
  End If
  
  VisualPrograma = w_html

End Function
%>