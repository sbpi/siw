<%
REM =========================================================================
REM Rotina de visualização dos dados da tarefa
REM -------------------------------------------------------------------------
Function VisualTarefa(w_chave, O, w_usuario, P4, w_identificacao, w_conclusao, w_responsavel, w_anexo, w_ocorrencia, w_dados_consulta)
  
  Dim w_html
  
  w_html = ""

  ' Recupera os dados da tarefa
  DB_GetSolicData_IS RS1, w_chave, "ISTAGERAL"
  
  w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
  w_html = w_html & VbCrLf & "      <tr><td colspan=""2""  bgcolor=""#f0f0f0""><div align=justify><font size=""2""><b>TAREFA: "& RS1("sq_siw_solicitacao")& " - " & RS1("titulo") & "</b></font></div></td></tr>"
  w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
  
  ' Identificação da tarefa
  If uCase(w_identificacao) = uCase("sim") Then
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>IDENTIFICAÇÃO DA TAREFA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     If Not IsNull(RS1("nm_projeto")) Then
        ' Recupera os dados da ação
        DB_GetSolicData_IS RS2, RS1("sq_solic_pai"), "ISACGERAL"
        ' Se a ação no PPA for informada, exibe.
        If Not IsNull(RS2("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Programa:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS2("cd_ppa_pai") & " - " & RS2("nm_ppa_pai") & "</font></div></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Ação:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS2("cd_acao") & " - " & RS2("nm_ppa") & "</font></div></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Unidade:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS2("cd_unidade") & " - " & RS2("ds_unidade") & "</font></div></td></tr>"
        End If
        ' Se o programa interno for informado, exibe.
        If Not IsNull(RS2("sq_isprojeto")) Then
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Programa Interno:</b></font></td>"
           If Nvl(RS2("cd_pri"),"") > "" Then
              w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1""><b>" & RS2("cd_pri") & " - " & RS2("nm_pri") & "</b></font></div></td></tr>"
           Else
              w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1""><b>" & RS2("nm_pri") & "</b></font></div></td></tr>"
           End If
        End If
     End If
     w_html = w_html & VbCrLf & "   <tr><td width=""30%""><font size=""1""><b>Descrição:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & Nvl(RS1("assunto"),"-") & "</font></div></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Recurso Programado " & w_ano & ":</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">R$ " & FormatNumber(RS1("valor"),2) & "</font></td></tr>"
     If Not IsNull(RS2("cd_acao")) Then
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Limite Orçamentário " & w_ano & ":</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">R$ " & FormatNumber(RS1("custo_real"),2) & "</font></td></tr>"     
     End If
     If P4 = 1 Then
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Área Planejamento:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS1("nm_unidade_resp") & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Responsável SISPLAM:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS1("nm_sol") & "</font></td></tr>"
     Else
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Área Planejamento:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & ExibeUnidade("../", w_cliente, RS1("nm_unidade_resp"), RS1("sq_unidade"), TP) & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Responsável SISPLAM:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS1("solicitante"), TP, RS1("nm_sol_comp")) & "</font></td></tr>"
     End If
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Início Previsto:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormataDataEdicao(RS1("inicio")) & "</font></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Fim Previsto:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormataDataEdicao(RS1("fim")) & "</font></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Prioridade:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RetornaPrioridade(RS1("prioridade")) & "</font></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Parecerias Externas:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS1("proponente"),"-") & "</font></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Fase Atual:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS1("nm_tramite"),"-") & "</font></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Resultados Esperados:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS1("descricao"),"-") & "</font></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Observação:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS1("justificativa"),"-") & "</font></td></tr>"
  End If
  
  ' Dados da conclusão do programa, se ela estiver nessa situação
  If uCase(w_conclusao) = uCase("sim") Then
     If RS1("concluida") = "S" and Nvl(RS1("data_conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONCLUSÃO DA TAREFA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
        If IsNull(RS2("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Recurso Executado:</b></font></td>"
           w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormatNumber(RS1("custo_real"),2) & "</font></td></tr>"
        End If
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Início Real:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormataDataEdicao(RS1("inicio_real")) & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Fim Real:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & FormataDataEdicao(RS1("fim_real")) & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Nota de Conclusão:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><font size=""1"">" & CRLF2BR(RS1("nota_conclusao")) & "</font></div></td></tr>"
     End If
  End If
  
  'Responsável
  If uCase(w_responsavel) = uCase("sim") Then
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>RESPONSÁVEIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     If RS1("nm_responsavel") > "" Then
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Responsável pela Tarefa:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & RS1("nm_responsavel") & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Telefone:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS1("fn_responsavel"),"-") & "</font></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>E-mail:</b></font></td>"
        w_html = w_html & VbCrLf & "       <td><font size=""1"">" & Nvl(RS1("em_responsavel"),"-") & "</font></td></tr>"
     Else
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><font size=""1""><div align=""center"">Nenhum responsável cadastrado</div></font></td>"
     End If
     RS1.Close
  End If
  
  ' Arquivos vinculados ao programa
  If uCase(w_anexo) = uCase("sim") Then
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     DB_GetSolicAnexo RS2, w_chave, null, w_cliente
     RS2.Sort = "nome"
     If Not RS2.EOF Then
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
        w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
        w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Título</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Descrição</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Tipo</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>KB</b></font></div></td>"    
        w_html = w_html & VbCrLf & "       </tr>"
        While Not RS2.EOF
           w_html = w_html & VbCrLf & "       <tr><td><font size=""1"">" & LinkArquivo("HL", w_cliente, RS2("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS2("nome"), null) & "</font></td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1"">" & Nvl(RS2("descricao"),"-") & "</font></td>"
           w_html = w_html & VbCrLf & "           <td><font size=""1"">" & RS2("tipo") & "</font></td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right""><font size=""1"">" & Round(cDbl(RS2("tamanho"))/1024,1) & "&nbsp;</font></td>"
           w_html = w_html & VbCrLf & "      </tr>"
           RS2.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></div></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Nenhuma arquivo cadastrado</font></div></td></tr>"
     End If
     RS2.Close
  End If
  
  ' Encaminhamentos
  If uCase(w_ocorrencia) = uCase("sim") Then
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     DB_GetSolicLog RS1, w_chave, null, "LISTA"
     RS1.Sort = "data desc"
     If Not RS1.EOF Then
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
        w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
        w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Data</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Ocorrência/Anotação</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Responsável</b></font></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>Fase/Destinatário</b></font></div></td>"
        w_html = w_html & VbCrLf & "       </tr>"
        w_html = w_html & VbCrLf & "       <tr><td colspan=""4""><font size=""1"">Fase Atual: <b>" & RS1("fase") & "</b></td></tr>"
        While Not RS1.EOF
           w_html = w_html & VbCrLf & "    <tr><td nowrap><font size=""1"">" & FormataDataEdicao(FormatDateTime(RS1("data"),2)) & ", " & FormatDateTime(RS1("data"),4)& "</font></td>"
           If Nvl(RS1("caminho"),"") > "" and P4 = 0 Then
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS1("despacho"),"---")) & "<br>[" & LinkArquivo("HL", w_cliente, RS1("sq_siw_arquivo"), "_blank", "Clique para exibir o arquivo em outra janela.", "Anexo - " & RS1("tipo") & " - " & Round(cDbl(RS1("tamanho"))/1024,1) & " KB", null) & "]" & "</font></td>"
           Else
              w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS1("despacho"),"---")) & "</font></td>"
           End If
           If P4 = 0 Then
              w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS1("sq_pessoa"), TP, RS1("responsavel")) & "</font></td>"
           Else
              w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS1("responsavel")& "</font></td>"
           End If
           If (Not IsNull(Tvl(RS1("sq_demanda_log")))) and (Not IsNull(Tvl(RS1("destinatario")))) Then
              If P4 = 0 Then
                 w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa("../", w_cliente, RS1("sq_pessoa_destinatario"), TP, RS1("destinatario")) & "</font></td>"
              Else
                 w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS1("destinatario")& "</font></td>"
              End If
           ElseIf (Not IsNull(Tvl(RS1("sq_demanda_log")))) and IsNull(Tvl(RS1("destinatario"))) Then
              w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anotação</font></td>"
           Else
              w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RS1("tramite"),"---") & "</font></td>"
           End If
           w_html = w_html & VbCrLf & "      </tr>"
           RS1.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></div></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center""><font size=""1"">Não foi encontrado nenhum encaminhamento</font></div></td></tr>"
     End If
     RS1.Close
  End If
  
  'Dados da consulta
  If uCase(w_dados_consulta) = uCase("sim") Then
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Consulta Realizada por:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  Session("NOME_RESUMIDO") & "</font></td></tr>"
     w_html = w_html & VbCrLf & "   <tr><td><font size=""1""><b>Data da Consulta:</b></font></td>"
     w_html = w_html & VbCrLf & "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(now(),2)) & ", " & FormatDateTime(now(),4) & "</font></td></tr>"
  End If
  w_html = w_html & VbCrLf & "       </table>"
  VisualTarefa = w_html

  Set w_html = Nothing

End Function
%>