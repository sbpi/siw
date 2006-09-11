<%

REM =========================================================================
REM Rotina de visualiza��o dos dados da a��o
REM -------------------------------------------------------------------------
Function VisualAcao(w_chave, O, w_usuario, P1, P4, w_identificacao, w_responsavel, w_qualitativa, w_orcamentaria, w_meta, w_restricao, w_tarefa, w_interessado, w_anexo, w_ocorrencia, w_dados_consulta, w_conclusao)

  Dim w_html
  Dim w_realizado_1, w_realizado_2, w_realizado_3, w_realizado_4, w_realizado_5, w_realizado_6
  Dim w_realizado_7, w_realizado_8, w_realizado_9, w_realizado_10, w_realizado_11, w_realizado_12
  Dim w_revisado_1, w_revisado_2, w_revisado_3, w_revisado_4, w_revisado_5, w_revisado_6
  Dim w_revisado_7, w_revisado_8, w_revisado_9, w_revisado_10, w_revisado_11, w_revisado_12    
  Dim w_atraso, w_noprazo, w_aviso, w_cancelado, w_concluido, w_conc_atraso, w_total
  Dim w_vr_atraso, w_vr_noprazo, w_vr_aviso, w_vr_cancelado, w_vr_concluido, w_vr_conc_atraso, w_vr_total
  
  w_html = ""

  ' Recupera os dados da a��o
  DB_GetSolicData_IS RS1, w_chave, "ISACGERAL"
  

  'Se for para exibir s� a ficha resumo da a��o.
  If P1 = 1 or P1 = 2 or P1 = 3 Then 
     If Not P4 = 1 Then
        w_html = w_html & VbCrLf & "      <tr><td align=""right"" colspan=""2""><br><b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & RS1("sq_siw_solicitacao") & "&w_tipo=volta&P1=&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informa��es da a��o."">Exibir todas as informa��es</a></td></tr>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td  colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
     If Not IsNull(RS1("cd_acao")) Then
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""  bgcolor=""#f0f0f0""><div align=justify><font size=""2""><b>A��O: "& RS1("cd_acao")& " - " & RS1("nm_ppa") & "</b></div></td></tr>"
     Else
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""  bgcolor=""#f0f0f0""><div align=justify><font size=""2""><b>A��O: " & RS1("titulo") & "</b></div></td></tr>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
     
     ' Identifica��o da a��o
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>IDENTIFICA��O DA A��O<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
    
     ' Se a a��o no PPA for informada, exibe.
     If Not IsNull(RS1("cd_acao")) Then
        w_html = w_html & VbCrLf & "   <tr><td width=""30%""><b>Programa:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><b>" & RS1("cd_ppa_pai") & " - " & RS1("nm_ppa_pai") & "</b></div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><b>A��o:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & RS1("cd_acao") & " - " & RS1("nm_ppa") & "</div></td></tr>"
     End If
     
     ' Se o programa interno for informado, exibe.
     If Not IsNull(RS1("sq_isprojeto")) Then
        w_html = w_html & VbCrLf & "   <tr><td width=""30%""><b>Programa Interno:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify""><b>" & RS1("nm_pri") & "</b></div></td></tr>"
        If IsNull(RS1("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr><td><b>Recurso Programado " & w_ano & ":</b></td>"
           w_html = w_html & VbCrLf & "       <td>R$ " & FormatNumber(RS1("valor"),2) & "</td></tr>"
        End If
     End If        
     If Not IsNull(RS1("cd_acao")) Then
        w_html = w_html & VbCrLf & "   <tr><td><b>Org�o:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & RS1("nm_orgao") & "</td></tr>"
     End If
     If P4 = 1 Then
        w_html = w_html & VbCrLf & "   <tr><td><b>Unidade Administrativa:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & RS1("nm_unidade_adm") & "</td></tr>"
     Else
        w_html = w_html & VbCrLf & "   <tr><td><b>Unidade Administrativa:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & ExibeUnidade("../", w_cliente, RS1("nm_unidade_adm"), RS1("sq_unidade_adm"), TP) & "</td></tr>"
     End If
     If Not IsNull(RS1("cd_acao")) Then
        w_html = w_html & VbCrLf & "   <tr><td><b>Unidade Or�ament�ria:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & RS1("cd_unidade") & " - " & RS1("ds_unidade") & "</td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><b>Recurso Programado " & w_ano & ":</b></td>"
        w_html = w_html & VbCrLf & "       <td>R$ " & FormatNumber(RS1("valor"),2) & "</td></tr>"
     End If        
     If P4 = 1 Then
        w_html = w_html & VbCrLf & "   <tr><td><b>�rea Planejamento:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & RS1("nm_unidade_resp") & "</td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><b>Respons�vel Monitoramento:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & RS1("nm_sol") & "</td></tr>"
     Else
        w_html = w_html & VbCrLf & "   <tr><td><b>�rea Planejamento:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & ExibeUnidade("../", w_cliente, RS1("nm_unidade_resp"), RS1("sq_unidade"), TP) & "</td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><b>Respons�vel Monitoramento:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & ExibePessoa("../", w_cliente, RS1("solicitante"), TP, RS1("nm_sol_comp")) & "</td></tr>"
     End If
     w_html = w_html & VbCrLf & "   <tr><td><b>Fase Atual da A��o:</b></td>"
     w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("nm_tramite"),"-") & "</td></tr>"

     ' Listagem das metas da a��o
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>METAS F�SICAS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
     DB_GetSolicMeta_IS RS2, w_chave, null, "LSTNULL", null, null, null, null, null, null, null, null, null
     RS2.Sort = "ordem"
     If Not RS2.EOF Then
        w_cont = 1
        While Not RS2.EOF
           DB_GetSolicMeta_IS RS3, w_chave, RS2("sq_meta"), "REGISTRO", null, null, null, null, null, null, null, null, null
           w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><b>" & w_cont & ") Meta:</b></td>"
           If Nvl(RS3("descricao_subacao"),"") > "" Then
              w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><b>" & RS2("titulo") & "("& RS3("descricao_subacao")&")</b></td></tr>"
           Else
              w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><b>" & RS2("titulo") & "</b></td></tr>"
           End If
           w_html = w_html & VbCrLf & "   <tr><td><b>Descri��o da Meta:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  RS2("descricao") & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Quantitativo Programado:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & (Nvl(RS2("quantidade"),0)) & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Unidade Medida:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  RS2("unidade_medida") & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Meta Cumulativa:</b></td>"
           If RS2("cumulativa") = "N" Then
              w_html = w_html & VbCrLf & "       <td>N�o</td></tr>"
           Else
              w_html = w_html & VbCrLf & "       <td>Sim</td></tr>"
           End If
           w_html = w_html & VbCrLf & "   <tr><td><b>Meta PPA:</b></td>"
           If Nvl(RS2("cd_subacao"),"") > "" Then
              w_html = w_html & VbCrLf & "       <td>Sim</td></tr>"
           Else
              w_html = w_html & VbCrLf & "       <td>N�o</td></tr>"
           End If  
           w_html = w_html & VbCrLf & "   <tr><td><b>Setor Respons�vel pela Meta:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(RS2("sg_setor")) & "</td></tr>"

           w_html = w_html & VbCrLf & "   <tr><td><b>Previs�o Inicio:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(RS2("inicio_previsto")) & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Previs�o T�rmino:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(RS2("fim_previsto")) & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Percentual de Conclus�o:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("perc_conclusao"),0) & "%</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Situa��o atual da meta:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("situacao_atual"),"-") & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>A meta ser� cumprida:</b></td>"
           If RS2("exequivel") = "N" Then
              w_html = w_html & VbCrLf & "       <td>N�o</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Justificativa para o n�o cumprimento da meta:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("justificativa_inexequivel"),"-") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Medidas necess�rias para realiza��o da meta:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("outras_medidas"),"-") & "</td></tr>"
           Else
              w_html = w_html & VbCrLf & "       <td>Sim</td></tr>"     
           End If          
           w_html = w_html & VbCrLf & "   <tr><td><b>Cria��o/�ltima Atualiza��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(FormatDateTime(RS2("ultima_atualizacao"),2)) & ", " & FormatDateTime(RS2("ultima_atualizacao"),4) & "</td></tr>"
           RS2.MoveNext
           w_cont = w_cont + 1
        wend
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhuma meta cadastrada para esta a��o</div></td></tr>"
     End If
     RS2.Close

     ' Listagem das restri��es da a��o
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>RESTRI��ES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
     DB_GetRestricao_IS RS2, "ISACRESTR", w_chave, null
     RS2.Sort = "inclusao desc"
     If Not RS2.EOF Then
        w_cont = 1
        While Not RS2.EOF
           w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><b>" & w_cont & ") Tipo:</b></td>"
           w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><b>" &  RS2("nm_tp_restricao") & "</b></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Descri��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  RS2("descricao") & "</td></tr>"     
           w_html = w_html & VbCrLf & "   <tr><td><b>Provid�ncia:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("providencia"),"-") & "</td></tr>"     
           w_html = w_html & VbCrLf & "   <tr><td><b>Data de Inclus�o:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(FormatDateTime(RS2("inclusao"),2)) & ", " & FormatDateTime(RS2("inclusao"),4) & "</td></tr>"     
           w_cont = w_cont + 1
           RS2.MoveNext
        Wend
     Else
         w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhuma restri��o cadastrada</div></td></tr>"
     End If     
     RS2.Close
     
     ' Listagem das tarefas na visualiza��o da a��o, rotina adquirida apartir da rotina exitente na Tarefas.asp para listagem das tarefas     
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>TAREFAS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"     
     DB_GetLinkData RS2, RetornaCliente(), "ISTCAD"
     DB_GetSolicList_IS RS2, RS2("sq_menu"), RetornaUsuario(), "ISTCAD", 4, _
           null, null, null, null, null, null, _
           null, null, null, null, _
           null, null, null, null, null, null, null, _
           null, null, null, null, w_chave, null, null, null, null, null, w_ano
     RS2.sort = "ordem, fim, prioridade"
     If Not RS2.EOF Then
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
        w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
        w_html = w_html & VbCrLf & "   <tr><td width=""8%"" bgColor=""#f0f0f0""><div align=""center""><b>C�digo</b></div></td>"
        w_html = w_html & VbCrLf & "       <td bgColor=""#f0f0f0""><div align=""center""><b>Tarefa</b></div></td>"
        w_html = w_html & VbCrLf & "       <td width=""12%"" bgColor=""#f0f0f0""><div align=""center""><b>Respons�vel</b></div></td>"  
        w_html = w_html & VbCrLf & "       <td width=""10%"" bgColor=""#f0f0f0""><div align=""center""><b>In�cio</b></div></td>"
        w_html = w_html & VbCrLf & "       <td width=""10%"" bgColor=""#f0f0f0""><div align=""center""><b>Fim</b></div></td>"        
        w_html = w_html & VbCrLf & "       <td width=""12%"" bgColor=""#f0f0f0""><div align=""center""><b>Valor (R$)</b></div></td>"
        w_html = w_html & VbCrLf & "       <td width=""13%"" bgColor=""#f0f0f0""><div align=""center""><b>Fase Atual</b></div></td></tr>"        
        While Not RS2.EOF         
           w_html = w_html & VbCrLf & "   <tr><td nowrap><b>"
           If RS2("concluida") = "N" Then
              If RS2("fim") < Date() Then
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgAtraso & """ border=0 width=14 heigth=14 align=""center"">"
              ElseIf RS2("aviso_prox_conc") = "S" and (RS2("aviso") <= Date()) Then
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgAviso & """ border=0 width=14 height=14 align=""center"">"
              ElseIf RS2("sg_tramite") = "CA" Then
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgCancel & """ border=0 width=14 height=14 align=""center"">"
              Else
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgNormal & """ border=0 width=14 height=14 align=""center"">"
              End If
           Else
              If RS2("fim") < Nvl(RS2("fim_real"),RS2("fim")) Then
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgOkAtraso & """ border=0 width=14 heigth=14 align=""center"">"
              Else
                 w_html = w_html & VbCrLf & "          <img src=""" & conImgOkNormal & """ border=0 width=14 height=14 align=""center"">"
              End If
           End If        
           w_html = w_html & VbCrLf & "    <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS2("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informa��es deste registro."" target=""blank"">" & RS2("sq_siw_solicitacao") & "&nbsp;</a>"
           w_html = w_html & VbCrLf & "    <td>" & Nvl(RS2("titulo"),"-") & "</td>"
           w_html = w_html & VbCrLf & "    <td>"& ExibePessoa("../", w_cliente, RS2("solicitante"), TP, RS2("nm_solic")) & "</td>"
           w_html = w_html & VbCrLf & "    <td><div align=""center"">"& FormataDataEdicao(RS2("inicio")) & "</div></td>"
           w_html = w_html & VbCrLf & "    <td><div align=""center"">"& FormataDataEdicao(RS2("fim")) & "</div></td>"
           w_html = w_html & VbCrLf & "    <td><div align=""right"">"& FormatNumber(cDbl(Nvl(RS2("valor"),0)),2) & "</div></td>"
           w_html = w_html & VbCrLf & "    <td>"& RS2("nm_tramite") & "</td>"
           RS2.MoveNext        
        wend        
        w_html = w_html & VbCrLf & "         </table></div></td></tr>" 
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhuma tarefa cadastrada</div></td></tr>"
     End If  
     RS2.Close
     
     ' Encaminhamentos
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>OCORR�NCIAS E ANOTA��ES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"     
     DB_GetSolicLog RS1, w_chave, null, "LISTA"
     RS1.Sort = "data desc"
     If Not RS1.EOF Then
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
        w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
        w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><b>Data</b></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Ocorr�ncia/Anota��o</b></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Respons�vel</b></div></td>"
        w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Fase/Destinat�rio</b></div></td>"    
        w_html = w_html & VbCrLf & "       </tr>"
        w_html = w_html & VbCrLf & "       <tr><td colspan=""4"">Fase Atual: <b>" & RS1("fase") & "</b></td></tr>"
        While Not RS1.EOF
           w_html = w_html & VbCrLf & "    <tr><td nowrap>" & FormataDataEdicao(FormatDateTime(RS1("data"),2)) & ", " & FormatDateTime(RS1("data"),4)& "</td>"
           w_html = w_html & VbCrLf & "        <td>" & CRLF2BR(Nvl(RS1("despacho"),"---")) & "</td>"
           w_html = w_html & VbCrLf & "        <td nowrap>" & ExibePessoa("../", w_cliente, RS1("sq_pessoa"), TP, RS1("responsavel")) & "</td>"
           If (Not IsNull(Tvl(RS1("sq_projeto_log")))) and (Not IsNull(Tvl(RS1("destinatario")))) Then
              w_html = w_html & VbCrLf & "        <td nowrap>" & ExibePessoa("../", w_cliente, RS1("sq_pessoa_destinatario"), TP, RS1("destinatario")) & "</td>"
           ElseIf (Not IsNull(Tvl(RS1("sq_projeto_log")))) and IsNull(Tvl(RS1("destinatario"))) Then
              w_html = w_html & VbCrLf & "        <td nowrap>Anota��o</td>"
           Else
              w_html = w_html & VbCrLf & "        <td nowrap>" & Nvl(RS1("tramite"),"---") & "</td>"
           End If
           w_html = w_html & VbCrLf & "      </tr>"
           RS1.MoveNext
        wend
        w_html = w_html & VbCrLf & "         </table></div></td></tr>"
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">N�o foi encontrado nenhum encaminhamento</div></td></tr>"
     End If
     RS1.Close
     w_html = w_html & VbCrLf & "</table>"
  Else        
     w_html = w_html & VbCrLf & "      <tr><td  colspan=""2""><br><hr NOSHADE color=#000000 size=4></td></tr>"
     If Not IsNull(RS1("cd_acao")) Then
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""  bgcolor=""#f0f0f0""><div align=justify><font size=""2""><b>A��O: "& RS1("cd_acao")& " - " & RS1("nm_ppa") & "</b></div></td></tr>"
     Else
        w_html = w_html & VbCrLf & "   <tr><td colspan=""2""  bgcolor=""#f0f0f0""><div align=justify><font size=""2""><b>A��O: " & RS1("titulo") & "</b></div></td></tr>"
     End If
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><hr NOSHADE color=#000000 size=4></td></tr>"
     
     ' Identifica��o da a��o
     If uCase(w_identificacao) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>IDENTIFICA��O DA A��O<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        ' Se a a��o no PPA for informada, exibe.
        If Not IsNull(RS1("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr><td width=""30%""><b>Programa:</b></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify""><b>" & RS1("cd_ppa_pai") & " - " & RS1("nm_ppa_pai") & "</b></div></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>A��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & RS1("cd_acao") & " - " & RS1("nm_ppa") & "</div></td></tr>"
        End If
        ' Se o programa interno for informado, exibe.
        If Not IsNull(RS1("sq_isprojeto")) Then
           w_html = w_html & VbCrLf & "   <tr><td width=""30%""><b>Programa Interno:</b></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify""><b>" & RS1("nm_pri") & "</b></div></td></tr>"
           If IsNull(RS1("cd_acao")) Then
              w_html = w_html & VbCrLf & "   <tr><td><b>Recurso Programado " & w_ano & ":</b></td>"
              w_html = w_html & VbCrLf & "       <td>R$ " & FormatNumber(RS1("valor"),2) & "</td></tr>"           
           End If
        End If        
        If Not IsNull(RS1("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr><td><b>Org�o:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS1("nm_orgao") & "</td></tr>"
        End If
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "   <tr><td><b>Unidade Administrativa:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS1("nm_unidade_adm") & "</td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><b>Unidade Administrativa:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & ExibeUnidade("../", w_cliente, RS1("nm_unidade_adm"), RS1("sq_unidade_adm"), TP) & "</td></tr>"
        End If
        If Not IsNull(RS1("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr><td><b>Unidade Or�ament�ria:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS1("cd_unidade") & " - " & RS1("ds_unidade") & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Recurso Programado " & w_ano & ":</b></td>"
           w_html = w_html & VbCrLf & "       <td>R$ " & FormatNumber(RS1("valor"),2) & "</td></tr>"           
        End If        
        If RS1("mpog_ppa") = "S" Then
           w_html = w_html & VbCrLf & "   <tr><td><b>Selecionada SPI/MP:</b></td>"
           w_html = w_html & VbCrLf & "       <td>Sim</td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><b>Selecionada SPI/MP:</b></td>"
           w_html = w_html & VbCrLf & "       <td>N�o</td></tr>"
        End If
        If RS1("relev_ppa") = "S" Then
           w_html = w_html & VbCrLf & "   <tr><td><b>Selecionada SE/SEPPIR:</b></td>"
           w_html = w_html & VbCrLf & "       <td>Sim</td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><b>Selecionada SE/SEPPIR:</b></td>"
           w_html = w_html & VbCrLf & "       <td>N�o</td></tr>"
        End If
        If P4 = 1 Then
           w_html = w_html & VbCrLf & "   <tr><td><b>�rea Planejamento:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS1("nm_unidade_resp") & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Respons�vel Monitoramento:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS1("nm_sol") & "</td></tr>"
        Else
           w_html = w_html & VbCrLf & "   <tr><td><b>�rea Planejamento:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & ExibeUnidade("../", w_cliente, RS1("nm_unidade_resp"), RS1("sq_unidade"), TP) & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Respons�vel Monitoramento:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & ExibePessoa("../", w_cliente, RS1("solicitante"), TP, RS1("nm_sol_comp")) & "</td></tr>"
        End If
        If Not IsNull(RS1("cd_acao")) Then
           DB_GetAcaoPPA_IS RS2, w_cliente, w_ano, RS1("cd_ppa_pai"), RS1("cd_acao"), null, RS1("cd_unidade"), null, null, null
           w_html = w_html & VbCrLf & "   <tr><td><b>Fun��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS2("ds_funcao") & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Sub-fun��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS2("ds_subfuncao") & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Esfera:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS2("ds_esfera") & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Tipo de A��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & RS2("nm_tipo_acao") & "</td></tr>"
           RS2.Close
        End If
        'w_html = w_html & VbCrLf & "     <tr valign=""top"">"
        'w_html = w_html & VbCrLf & "       <td>In�cio previsto:<br><b>" & FormataDataEdicao(RS1("inicio")) & " </b></td>"
        'w_html = w_html & VbCrLf & "       <td>Fim previsto:<br><b>" & FormataDataEdicao(RS1("fim")) & " </b></td>"
        'w_html = w_html & VbCrLf & "     </table>"
        w_html = w_html & VbCrLf & "   <tr><td><b>Parcerias Externas:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & CRLF2BR(Nvl(RS1("proponente"),"-")) & "</td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><b>Parcerias Internas:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & CRLF2BR(Nvl(RS1("palavra_chave"),"-")) & "</td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><b>Fase Atual da A��o:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("nm_tramite"),"-") & "</td></tr>"
     End If
     
     ' Responsaveis
     If uCase(w_responsavel) = uCase("sim") Then  
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>RESPONS�VEIS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        If RS1("nm_gerente_programa") > "" or RS1("nm_gerente_executivo") > "" or RS1("nm_gerente_adjunto") > "" or RS1("resp_ppa") > "" or RS1("resp_pri") > "" Then
           If Not IsNull(RS1("nm_gerente_programa")) Then           
              w_html = w_html & VbCrLf & "   <tr><td><b>Gerente do Programa:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & RS1("nm_gerente_programa") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Telefone:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("fn_gerente_programa"),"-") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>E-mail:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("em_gerente_programa"),"-") & "</td></tr>"
           End If
           If Not IsNull(RS1("nm_gerente_executivo")) Then
              w_html = w_html & VbCrLf & "   <tr><td><b>Gerente Executivo do Programa:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & RS1("nm_gerente_executivo") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Telefone:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("fn_gerente_executivo"),"-") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>E-mail:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("em_gerente_executivo"),"-") & "</td></tr>"
           End If
           If Not IsNull(RS1("nm_gerente_adjunto")) Then
              w_html = w_html & VbCrLf & "   <tr><td><b>Gerente Executivo Adjunto:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & RS1("nm_gerente_adjunto") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Telefone:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("fn_gerente_adjunto"),"-") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>E-mail:</b></td>"
             w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("em_gerente_adjunto"),"-") & "</td></tr>"
           End If
           If Not IsNull(RS1("resp_ppa")) Then
              w_html = w_html & VbCrLf & "   <tr><td><b>Coordenador:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & RS1("resp_ppa") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Telefone:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("fone_ppa"),"-") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>E-mail:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("mail_ppa"),"-") & "</td></tr>"
           End If
           If Not IsNull(RS1("resp_pri")) Then
              w_html = w_html & VbCrLf & "   <tr><td><b>Respons�vel pela A��o:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & RS1("resp_pri") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Telefone:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("fone_pri"),"-") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>E-mail:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & Nvl(RS1("mail_pri"),"-") & "</td></tr>"
           End If
        Else
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">Nenhum respons�vel cadastrado</div></td>"
        End If
     End If  
     
     
     ' Dados da conclus�o da a��o, se ela estiver nessa situa��o
     If uCase(w_conclusao) = uCase("sim") Then 
        If RS1("concluida") = "S" and Nvl(RS1("data_conclusao"),"") > "" Then
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONCLUS�O DA A��O<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Recurso Executado:</b></td>"
           w_html = w_html & VbCrLf & "       <td>" & FormatNumber(RS1("custo_real"),2) & "</td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td><b>Nota de Conclus�o:</b></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & CRLF2BR(RS1("nota_conclusao")) & "</div></td></tr>"
        End If
     End If
        
     ' Programa��o Qualitativa
     If uCase(w_qualitativa) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>PROGRAMA��O QUALITATIVA<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        If Not IsNull(RS1("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Descri��o da A��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & Nvl(RS1("descricao_ppa"),"-") & "</div></td></tr>"     
        End If
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Justificativa:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & Nvl(RS1("problema"),"-") & "</div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Objetivo Espec�fico:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & Nvl(RS1("objetivo"),"-") & "</div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>P�blico Alvo:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & Nvl(RS1("publico_alvo"),"-") & "</div></td></tr>"
        If Not IsNull(RS1("cd_acao")) Then
           w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Base Legal:</b></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify"">" &Nvl(RS1("base_legal"),"-") & "</div></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Forma de Implementa��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify"">"
           If cDbl(RS1("cd_tipo_acao")) = 1 or cDbl(RS1("cd_tipo_acao")) = 2 Then 
              If RS1("direta") = "S" Then
                 w_html = w_html & VbCrLf & " direta"
              ElseIf RS1("descentralizada") = "S" Then
                 w_html = w_html & VbCrLf & " descentralizada"
              ElseIf RS1("linha_credito") = "S" Then
                 w_html = w_html & VbCrLf & " linha de cr�dito"
              End If
           ElseIf cDbl(RS1("cd_tipo_acao")) = 4 Then
              If RS1("transf_obrigatoria") = "S" Then
                 w_html = w_html & VbCrLf & " transfer�ncia obrigat�ria"
              ElseIf RS1("transf_voluntaria") = "S" Then
                 w_html = w_html & VbCrLf & " transfer�ncia volunt�ria"
              ElseIf RS1("transf_outras") = "S" Then
                 w_html = w_html & VbCrLf & " outras"
              End If
           End If
           w_html = w_html & VbCrLf & "</div></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Detalhamento da Implementa��o:</b></td>"
           w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & Nvl(RS1("detalhamento"),"-") & "</div></td></tr>"
        End If
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Sistem�tica e Estrat�gias a serem Adotadas para o Monitoramento da A��o:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify"">" &Nvl(RS1("estrategia"),"-") & "</div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Sistem�tica e Metodologias a serem Adotadas para Avalia��o da A��o:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & Nvl(RS1("sistematica"),"-") & "</div></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td valign=""top""><b>Observa��es:</b></td>"
        w_html = w_html & VbCrLf & "       <td><div align=""justify"">" & Nvl(RS1("justificativa"),"-") & "</div></td></tr>"
     End If
     
     ' Programa��o or�amentaria
     If uCase(w_orcamentaria) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>PROGRAMA��O OR�AMENT�RIA<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        If Not IsNull(RS1("cd_acao")) Then
           If cDbl(RS1("cd_tipo_acao")) <> 3 Then
              DB_GetPPADadoFinanc_IS RS2, RS1("cd_acao"), RS1("cd_unidade"), w_ano, w_cliente, "VALORTOTALMENSAL"
              If Not RS2.EOF Then
                 w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
                 w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
                 w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><b>Ano</b></div></td>"
                 w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><b>Valor Aprovado</b></div></td>"
                 w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><b>Valor Autorizado</b></div></td>"
                 w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><b>Valor Realizado</b></div></td>"  
                 w_html = w_html & VbCrLf & "       <tr><td><div align=""right""><b>" &  w_ano & "</b></div></td>"     
                 w_html = w_html & VbCrLf & "           <td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("previsao_ano"),0.00))) & "</div></td>"
                 w_html = w_html & VbCrLf & "           <td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("atual_ano"),0.00))) & "</div></td>"
                 w_html = w_html & VbCrLf & "           <td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("real_ano"),0.00))) & "</div></td>"
                 w_html = w_html & VbCrLf & "       <tr><td width=""10%"" bgColor=""#f0f0f0""><div align=""center""><b>M�s</b></div></td>"
                 w_html = w_html & VbCrLf & "           <td width=""30%"" bgColor=""#f0f0f0""><div align=""center""><b>Inicial</b></div></td>"
                 w_html = w_html & VbCrLf & "           <td width=""30%"" bgColor=""#f0f0f0""><div align=""center""><b>Revisado</b></div></td>"
                 w_html = w_html & VbCrLf & "           <td width=""30%"" bgColor=""#f0f0f0""><div align=""center""><b>Realizado</b></div></td></tr>"
                 w_html = w_html & VbCrLf & "       <tr><td width=""10%"" align=""right""><b>Janeiro:"
                 w_html = w_html & VbCrLf & "           <td align=""right"" width=""30%"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_1"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_1"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_1"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Fevereiro:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_2"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_2"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_2"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Mar�o:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_3"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_3"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_3"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Abril:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_4"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_4"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_4"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Maio:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_5"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_5"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_5"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Junho:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_6"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_6"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_6"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Julho:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_7"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_7"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_7"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Agosto:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_8"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_8"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_8"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Setembro:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_9"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_9"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_9"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Outubro:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_10"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_10"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_10"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Novembro:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_11"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_11"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_11"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Dezembro:"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_ini_mes_12"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("cron_mes_12"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "           <td align=""right"">" & FormatNumber(cDbl(Nvl(RS2("real_mes_12"),0)),2)& "</td>"
                 w_html = w_html & VbCrLf & "       <tr><td align=""right""><b>Total:"
                 w_html = w_html & VbCrLf & "           <td align=""right""><b>" & FormatNumber(cDbl(Nvl(RS2("cron_ini_total"),0)),2) & "</b></td>"
                 w_html = w_html & VbCrLf & "           <td align=""right""><b>" & FormatNumber(cDbl(Nvl(RS2("cron_mes_total"),0)),2)& "</b></td>"
                 w_html = w_html & VbCrLf & "           <td align=""right""><b>" & FormatNumber(cDbl(Nvl(RS2("real_mes_total"),0)),2)& "</b></td>"              
                 w_html = w_html & VbCrLf & "     </table></div></td></tr>"                        
              End If
              RS2.Close        
              w_cont = 1
              DB_GetPPADadoFinanc_IS RS2, RS1("cd_acao"), RS1("cd_unidade"), w_ano, w_cliente, "VALORFONTEACAO"
              If RS2.EOF Then
                 w_html = w_html & VbCrLf & "   <tr><td colspan=""2"">Nao existe nenhum valor para esta a��o</td></tr>"
              Else
                 If cDbl(RS1("cd_tipo_acao")) = 1 Then
                    w_html = w_html & VbCrLf & "   <tr><td><b>Realizado at� " & (w_ano - 1) & ":</b></td>"
                    w_html = w_html & VbCrLf & "       <td>" & FormatNumber(Nvl(RS1("valor_ano_anterior"),0),2) & "</td></tr>"
                    w_html = w_html & VbCrLf & "   <tr><td><b>Justificativa da Repercus�o Financeira sobre o Custeio da Uni�o:</b></td>"
                    w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS1("reperc_financeira"),"-") & "</td></tr>"
                    w_html = w_html & VbCrLf & "   <tr><td><b>Valor Estimado da Repercuss�o Financeira por Ano (R$ 1,00):</b></td>"
                    w_html = w_html & VbCrLf & "       <td>" & FormatNumber(Nvl(RS1("valor_reperc_financeira"),0),2) & "</td></tr>"
                 End If
                 w_html = w_html & VbCrLf & "   <tr><td colspan=""2"" valigin=""top"" bgcolor=""#f0f0f0""><b>Valor por Fonte:</b></td>"
                 While Not RS2.EOF 
                    w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><b>" & w_cont & ") Fonte:</b></td>"
                    w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><b>" & RS2("nm_fonte") & "</b></td></tr>"
                    w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
                    w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
                    w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><b>2004</b></div></td>"
                    w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><b>2005</b></div></td>"
                    w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><b>2006</b></div></td>"  
                    w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><b>2007</b></div></td>"
                    w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><b>2008</b></div></td>"    
                    w_html = w_html & VbCrLf & "           <td bgColor=""#f0f0f0""><div align=""center""><b>Total 2004-2008</b></div></td></tr>"
                    w_html = w_html & VbCrLf & "       <tr><td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("valor_ano_1"),0.00))) & "</div></td>"
                    w_html = w_html & VbCrLf & "           <td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("valor_ano_2"),0.00))) & "</div></td>"
                    w_html = w_html & VbCrLf & "           <td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("valor_ano_3"),0.00))) & "</div></td>"
                    w_html = w_html & VbCrLf & "           <td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("valor_ano_4"),0.00))) & "</div></td>"
                    w_html = w_html & VbCrLf & "           <td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("valor_ano_5"),0.00))) & "</div></td>"
                    w_html = w_html & VbCrLf & "           <td><div align=""right"">" &  FormatNumber(cDbl(Nvl(RS2("valor_total"),0.00))) & "</div></td></tr>"
                    w_html = w_html & VbCrLf & "     </table></div></td></tr>"
                    RS2.MoveNext
                    w_cont = w_cont + 1
                 wend 
                 w_html = w_html & VbCrLf & "   <tr><td colspan=""2"">Fonte dos Dados: SIGPLAN/MP</td></tr>"
              End If
              RS2.Close
           Else
              w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">N�o existe programa��o financeira para esta a��o, pois esta � uma a��o n�o or�ament�ria</div></td></tr>"
           End If
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">N�o existe programa��o financeira para esta a��o, pois esta � uma a��o n�o or�ament�ria</div></td></tr>"
        End If
     End If
     
     ' Listagem das metas da a��o
     If uCase(w_meta) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>METAS F�SICAS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        DB_GetSolicMeta_IS RS2, w_chave, null, "LSTNULL", null, null, null, null, null, null, null, null, null
        RS2.Sort = "ordem"
        If Not RS2.EOF Then
           w_cont = 1
           While Not RS2.EOF
              DB_GetSolicMeta_IS RS3, w_chave, RS2("sq_meta"), "REGISTRO", null, null, null, null, null, null, null, null, null
              w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><b>" & w_cont & ") Meta:</b></td>"
              If Nvl(RS3("descricao_subacao"),"") > "" Then
                 w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><b>" & RS2("titulo") & "("& RS3("descricao_subacao")&")</b></td></tr>"
              Else
                 w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><b>" & RS2("titulo") & "</b></td></tr>"
              End If
              w_html = w_html & VbCrLf & "   <tr><td><b>Descri��o da Meta:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  RS2("descricao") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Quantitativo Programado:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" & (Nvl(RS2("quantidade"),0)) & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Unidade Medida:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  RS2("unidade_medida") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Meta Cumulativa:</b></td>"
              If RS2("cumulativa") = "N" Then
                 w_html = w_html & VbCrLf & "       <td>N�o</td></tr>"
              Else
                 w_html = w_html & VbCrLf & "       <td>Sim</td></tr>"
              End If
              w_html = w_html & VbCrLf & "   <tr><td><b>Meta PPA:</b></td>"
              If Nvl(RS2("cd_subacao"),"") > "" Then
                 w_html = w_html & VbCrLf & "       <td>Sim</td></tr>"
              Else
                 w_html = w_html & VbCrLf & "       <td>N�o</td></tr>"
              End If
              w_html = w_html & VbCrLf & "   <tr><td><b>Setor Respons�vel pela Meta:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(RS2("sg_setor")) & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Previs�o Inicio:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(RS2("inicio_previsto")) & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Previs�o T�rmino:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(RS2("fim_previsto")) & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Percentual de Conclus�o:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("perc_conclusao"),0) & "%</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Situa��o atual da meta:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("situacao_atual"),"-") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>A meta ser� cumprida:</b></td>"
              If RS2("exequivel") = "N" Then
                 w_html = w_html & VbCrLf & "       <td>N�o</td></tr>"
                 w_html = w_html & VbCrLf & "   <tr><td><b>Justificativa para o n�o cumprimento da meta:</b></td>"
                 w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("justificativa_inexequivel"),"-") & "</td></tr>"
                 w_html = w_html & VbCrLf & "   <tr><td><b>Medidas necess�rias para realiza��o da meta:</b></td>"
                 w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("outras_medidas"),"-") & "</td></tr>"
              Else
                 w_html = w_html & VbCrLf & "       <td>Sim</td></tr>"
              End If
              w_html = w_html & VbCrLf & "   <tr><td><b>Cria��o/�ltima Atualiza��o:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(FormatDateTime(RS2("ultima_atualizacao"),2)) & ", " & FormatDateTime(RS2("ultima_atualizacao"),4) & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
              w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
              w_html = w_html & VbCrLf & "   <tr><td width=""10%"" bgColor=""#f0f0f0""><div align=""center""><b>M�s</b></div></td>"
              w_html = w_html & VbCrLf & "       <td width=""20%"" bgColor=""#f0f0f0""><div align=""center""><b>Inicial</b></div></td>"
              w_html = w_html & VbCrLf & "       <td width=""20%"" bgColor=""#f0f0f0""><div align=""center""><b>Revisado</b></div></td>"
              w_html = w_html & VbCrLf & "       <td width=""20%"" bgColor=""#f0f0f0""><div align=""center""><b>Realizado</b></div></td>"
              w_html = w_html & VbCrLf & "       <td width=""30%"" bgColor=""#f0f0f0""><div align=""center""><b>Financeiro Realizado</b></div></td>"
              If Not RS3.EOF Then
                 DB_GetMetaMensal_IS RS4, RS2("sq_meta")
                 w_realizado_1 = ""
                 w_revisado_1  = ""
                 w_realizado_2 = ""
                 w_revisado_2  = ""
                 w_realizado_3 = ""
                 w_revisado_3  = ""
                 w_realizado_4 = ""
                 w_revisado_4  = ""
                 w_realizado_5 = ""
                 w_revisado_5  = ""
                 w_realizado_6 = ""
                 w_revisado_6  = ""
                 w_realizado_7 = ""
                 w_revisado_7  = ""
                 w_realizado_8 = ""
                 w_revisado_8  = ""
                 w_realizado_9 = ""
                 w_revisado_9  = ""
                 w_realizado_10 = ""
                 w_revisado_10  = ""
                 w_realizado_11 = ""
                 w_revisado_11  = ""
                 w_realizado_12 = ""
                 w_revisado_12  = ""                                     
                 If Not RS4.EOF Then
                    While Not RS4.EOF
                       Select Case Month(cDate(RS4("referencia")))
                          Case  1 w_realizado_1  = RS4("realizado")
                                  w_revisado_1   = RS4("revisado")
                          Case  2 w_realizado_2  = RS4("realizado")
                                  w_revisado_2   = RS4("revisado")
                          Case  3 w_realizado_3  = RS4("realizado")
                                  w_revisado_3   = RS4("revisado")
                          Case  4 w_realizado_4  = RS4("realizado")
                                  w_revisado_4   = RS4("revisado")
                          Case  5 w_realizado_5  = RS4("realizado")
                                  w_revisado_5   = RS4("revisado")
                          Case  6 w_realizado_6  = RS4("realizado")
                                  w_revisado_6   = RS4("revisado")
                          Case  7 w_realizado_7  = RS4("realizado")
                                  w_revisado_7   = RS4("revisado")
                          Case  8 w_realizado_8  = RS4("realizado")
                                  w_revisado_8   = RS4("revisado")
                          Case  9 w_realizado_9  = RS4("realizado")
                                  w_revisado_9   = RS4("revisado")
                          Case 10 w_realizado_10 = RS4("realizado")
                                  w_revisado_10  = RS4("revisado")
                          Case 11 w_realizado_11 = RS4("realizado")
                                  w_revisado_11  = RS4("revisado")
                          Case 12 w_realizado_12 = RS4("realizado")
                                  w_revisado_12  = RS4("revisado")
                       End Select
                       RS4.MoveNext
                    Wend
                 End If
                 RS4.Close
                 w_html = w_html & VbCrLf & "<tr><td width=""10%"" align=""right""><b>Janeiro:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_1"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">"& Nvl(w_revisado_1,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">"& Nvl(w_realizado_1,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_1"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Fevereiro:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_2"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_2,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_2,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_2"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Mar�o:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_3"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_3,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_3,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_3"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Abril:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_4"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_4,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_4,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_4"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Maio:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_5"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_5,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_5,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_5"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Junho:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_6"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_6,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_6,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_6"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Julho:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_7"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_7,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_7,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_7"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Agosto:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_8"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_8,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_8,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_8"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Setembro:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_9"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_9,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_9,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_9"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Outubro:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_10"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_10,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_10,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_10"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Novembro:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_11"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_11,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_11,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_11"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Dezembro:"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(RS3("cron_ini_mes_12"),"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_revisado_12,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & Nvl(w_realizado_12,"-") & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right"">" & FormatNumber(cDbl(Nvl(RS3("real_mes_12"),0)),2) & "</td>"
                 w_html = w_html & VbCrLf & "<tr><td align=""right""><b>Total:"
                 w_html = w_html & VbCrLf & "    <td align=""right""><b>" & cDbl(Nvl(RS3("cron_ini_mes_1"),0))+cDbl(Nvl(RS3("cron_ini_mes_2"),0))+cDbl(Nvl(RS3("cron_ini_mes_3"),0))+cDbl(Nvl(RS3("cron_ini_mes_4"),0))+cDbl(Nvl(RS3("cron_ini_mes_5"),0))+cDbl(Nvl(RS3("cron_ini_mes_6"),0))+cDbl(Nvl(RS3("cron_ini_mes_7"),0))+cDbl(Nvl(RS3("cron_ini_mes_8"),0))+cDbl(Nvl(RS3("cron_ini_mes_9"),0))+cDbl(Nvl(RS3("cron_ini_mes_10"),0))+cDbl(Nvl(RS3("cron_ini_mes_11"),0))+cDbl(Nvl(RS3("cron_ini_mes_12"),0)) & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right""><b>" & cDbl(Nvl(w_revisado_1,0))+cDbl(Nvl(w_revisado_2,0))+cDbl(Nvl(w_revisado_3,0))+cDbl(Nvl(w_revisado_4,0))+cDbl(Nvl(w_revisado_5,0))+cDbl(Nvl(w_revisado_6,0))+cDbl(Nvl(w_revisado_7,0))+cDbl(Nvl(w_revisado_8,0))+cDbl(Nvl(w_revisado_9,0))+cDbl(Nvl(w_revisado_10,0))+cDbl(Nvl(w_revisado_11,0))+cDbl(Nvl(w_revisado_12,0)) & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right""><b>" & cDbl(Nvl(w_realizado_1,0))+cDbl(Nvl(w_realizado_2,0))+cDbl(Nvl(w_realizado_3,0))+cDbl(Nvl(w_realizado_4,0))+cDbl(Nvl(w_realizado_5,0))+cDbl(Nvl(w_realizado_6,0))+cDbl(Nvl(w_realizado_7,0))+cDbl(Nvl(w_realizado_8,0))+cDbl(Nvl(w_realizado_9,0))+cDbl(Nvl(w_realizado_10,0))+cDbl(Nvl(w_realizado_11,0))+cDbl(Nvl(w_realizado_12,0)) & "&nbsp;</td>"
                 w_html = w_html & VbCrLf & "    <td align=""right""><b>" & FormatNumber(cDbl(Nvl(RS3("real_mes_1"),0))+cDbl(Nvl(RS3("real_mes_2"),0))+cDbl(Nvl(RS3("real_mes_3"),0))+cDbl(Nvl(RS3("real_mes_4"),0))+cDbl(Nvl(RS3("real_mes_5"),0))+cDbl(Nvl(RS3("real_mes_6"),0))+cDbl(Nvl(RS3("real_mes_7"),0))+cDbl(Nvl(RS3("real_mes_8"),0))+cDbl(Nvl(RS3("real_mes_9"),0))+cDbl(Nvl(RS3("real_mes_10"),0))+cDbl(Nvl(RS3("real_mes_11"),0))+cDbl(Nvl(RS3("real_mes_12"),0)),2) & "</td>"
              Else
                 w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhuma dado mensal foi informado para esta meta</div></td></tr>"
              End If
              RS3.Close
              w_html = w_html & VbCrLf & "      </table></div></td></tr>"
              RS2.MoveNext
              w_cont = w_cont + 1
           wend
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhuma meta cadastrada para esta a��o</div></td></tr>"
        End If
        RS2.Close
     End If
     
     ' Listagem das restri��es da a��o
     If uCase(w_restricao) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>RESTRI��ES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        DB_GetRestricao_IS RS2, "ISACRESTR", w_chave, null
        RS2.Sort = "inclusao desc"
        If Not RS2.EOF Then
           w_cont = 1
           While Not RS2.EOF
              w_html = w_html & VbCrLf & "   <tr><td valigin=""top"" bgcolor=""#f0f0f0""><b>" & w_cont & ") Tipo:</b></td>"
              w_html = w_html & VbCrLf & "       <td bgcolor=""#f0f0f0""><b>" &  RS2("nm_tp_restricao") & "</b></td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Descri��o:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  RS2("descricao") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Provid�ncia:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  Nvl(RS2("providencia"),"-") & "</td></tr>"
              w_html = w_html & VbCrLf & "   <tr><td><b>Data de Inclus�o:</b></td>"
              w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(FormatDateTime(RS2("inclusao"),2)) & ", " & FormatDateTime(RS2("inclusao"),4) & "</td></tr>"
              w_cont = w_cont + 1
              RS2.MoveNext
           Wend
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhuma restri��o cadastrada</div></td></tr>"
        End If     
     End If
     
     ' Listagem das tarefas na visualiza��o da a��o, rotina adquirida apartir da rotina exitente na Tarefas.asp para listagem das tarefas
     If w_tarefa = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>TAREFAS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        DB_GetLinkData RS2, RetornaCliente(), "ISTCAD"
        DB_GetSolicList_IS RS2, RS2("sq_menu"), RetornaUsuario(), "ISTCAD", 3, _
           null, null, null, null, null, null, _
           null, null, null, null, _
           null, null, null, null, null, null, null, _
           null, null, null, null, w_chave, null, null, null, null, null, w_ano
        RS2.sort = "ordem, fim, prioridade"
        If Not RS2.EOF Then
           w_aviso          = 0
           w_atraso         = 0
           w_noprazo        = 0
           w_cancelado      = 0
           w_concluido      = 0 
           w_conc_atraso    = 0
           w_vr_aviso       = 0
           w_vr_atraso      = 0
           w_vr_noprazo     = 0
           w_vr_cancelado   = 0
           w_vr_concluido   = 0 
           w_vr_conc_atraso = 0
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
           w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
           w_html = w_html & VbCrLf & "   <tr><td width=""8%"" bgColor=""#f0f0f0""><div align=""center""><b>C�digo</b></div></td>"
           w_html = w_html & VbCrLf & "       <td bgColor=""#f0f0f0""><div align=""center""><b>Tarefa</b></div></td>"
           w_html = w_html & VbCrLf & "       <td width=""12%"" bgColor=""#f0f0f0""><div align=""center""><b>Respons�vel</b></div></td>"
           w_html = w_html & VbCrLf & "       <td width=""10%"" bgColor=""#f0f0f0""><div align=""center""><b>In�cio</b></div></td>"
           w_html = w_html & VbCrLf & "       <td width=""10%"" bgColor=""#f0f0f0""><div align=""center""><b>Fim</b></div></td>"
           w_html = w_html & VbCrLf & "       <td width=""12%"" bgColor=""#f0f0f0""><div align=""center""><b>Valor (R$)</b></div></td>"
           w_html = w_html & VbCrLf & "       <td width=""13%"" bgColor=""#f0f0f0""><div align=""center""><b>Fase Atual</b></div></td></tr>"
           While Not RS2.EOF         
              w_html = w_html & VbCrLf & "   <tr><td><b>"
              If RS2("concluida") = "N" Then
                 If RS2("fim") < Date() Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgAtraso & """ border=0 width=14 heigth=14 align=""center"">"
                    w_atraso = w_atraso + 1
                    w_vr_atraso = w_vr_atraso + cDbl(Nvl(RS2("valor"),0))
                 ElseIf RS2("aviso_prox_conc") = "S" and (RS2("aviso") <= Date()) Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgAviso & """ border=0 width=14 height=14 align=""center"">"
                    w_aviso = w_aviso + 1
                    w_vr_aviso = w_vr_aviso + cDbl(Nvl(RS2("valor"),0))
                 ElseIf RS2("sg_tramite") = "CA" Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgCancel & """ border=0 width=14 height=14 align=""center"">"
                    w_cancelado = w_cancelado + 1
                    w_vr_cancelado = w_vr_cancelado + cDbl(Nvl(RS2("valor"),0))             
                 Else
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgNormal & """ border=0 width=14 height=14 align=""center"">"
                    w_noprazo = w_noprazo + 1
                    w_vr_noprazo = w_vr_noprazo + cDbl(Nvl(RS2("valor"),0))
                 End If
              Else
                 If RS2("fim") < Nvl(RS2("fim_real"),RS2("fim")) Then
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgOkAtraso & """ border=0 width=14 heigth=14 align=""center"">"
                    w_conc_atraso = w_conc_atraso + 1
                    w_vr_conc_atraso = w_vr_conc_atraso + cDbl(Nvl(RS2("valor"),0))
                 Else
                    w_html = w_html & VbCrLf & "          <img src=""" & conImgOkNormal & """ border=0 width=14 height=14 align=""center"">"
                    w_concluido = w_concluido + 1
                    w_vr_concluido = w_vr_concluido + cDbl(Nvl(RS2("valor"),0))
                 End If
              End If
              w_html = w_html & VbCrLf & "    <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS2("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informa��es deste registro."" target=""blank"">" & RS2("sq_siw_solicitacao") & "&nbsp;</a>"
              w_html = w_html & VbCrLf & "    <td>" & Nvl(RS2("titulo"),"-") & "</td>"
              w_html = w_html & VbCrLf & "    <td>"& ExibePessoa("../", w_cliente, RS2("solicitante"), TP, RS2("nm_solic")) & "</td>"
              w_html = w_html & VbCrLf & "    <td><div align=""center"">"& FormataDataEdicao(RS2("inicio")) & "</div></td>"
              w_html = w_html & VbCrLf & "    <td><div align=""center"">"& FormataDataEdicao(RS2("fim")) & "</div></td>"
              w_html = w_html & VbCrLf & "    <td><div align=""right"">"& FormatNumber(cDbl(Nvl(RS2("valor"),0)),2) & "</div></td>"
              w_html = w_html & VbCrLf & "    <td>"& RS2("nm_tramite") & "</td>"
              RS2.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></div></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><br>"
           w_total = w_atraso + w_aviso + w_noprazo + w_concluido + w_conc_atraso
           w_vr_total = w_vr_atraso + w_vr_aviso + w_vr_noprazo + w_vr_concluido + w_vr_conc_atraso
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
           w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
           w_html = w_html & VbCrLf & "       <tr><td width=""8%"" bgColor=""#f0f0f0""><div align=""center""><b>Simbolo</b></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Situa��o da Tarefa</b></div></td>"
           w_html = w_html & VbCrLf & "         <td width=""22%"" bgColor=""#f0f0f0""><div align=""center""><b>Valor Total</b></div></td>"
           w_html = w_html & VbCrLf & "         <td width=""10%"" bgColor=""#f0f0f0""><div align=""center""><b>% Valor</b></div></td>"
           w_html = w_html & VbCrLf & "         <td width=""12%"" bgColor=""#f0f0f0""><div align=""center""><b>N� de Tarefas</b></div></td>"
           w_html = w_html & VbCrLf & "         <td width=""13%"" bgColor=""#f0f0f0""><div align=""center""><b>% Tarefas</b></div></td>"
           w_html = w_html & VbCrLf & "       <tr><td><div align=""center""><img src=""" & conImgNormal & """ border=0 width=14 height=14 align=""center""></div></td>"
           w_html = w_html & VbCrLf & "         <td>No Prazo</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & FormatNumber(cDbl(w_vr_noprazo),2) & "</td>"
           If cDbl(w_vr_total) > 0 Then
              w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_vr_noprazo/w_vr_total)*100),2) & "</td>"
           Else
              w_html = w_html & VbCrLf & "         <td><div align=""right"">0</td>"
           End If
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & w_noprazo & "</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_noprazo/w_total)*100),2) & "</td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td><div align=""center""><img src=""" & conImgAtraso & """ border=0 width=14 height=14 align=""center""></div></td>"
           w_html = w_html & VbCrLf & "         <td>Em Atraso</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & FormatNumber(cDbl(w_vr_atraso),2) & "</td>"
           If cDbl(w_vr_total) > 0 Then
              w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_vr_atraso/w_vr_total)*100),2) & "</td>"
           Else
              w_html = w_html & VbCrLf & "         <td><div align=""right"">0</td>"
           End If
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & w_atraso & "</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_atraso/w_total)*100),2) & "</td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td><div align=""center""><img src=""" & conImgAviso & """ border=0 width=14 height=14 align=""center""></div></td>"
           w_html = w_html & VbCrLf & "         <td>Em Aviso</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & FormatNumber(cDbl(w_vr_aviso),2) & "</td>"
           If cDbl(w_vr_total) > 0 Then
              w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_vr_aviso/w_vr_total)*100),2) & "</td>"
           Else
              w_html = w_html & VbCrLf & "         <td><div align=""right"">0</td>"
           End If
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & w_aviso & "</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_aviso/w_total)*100),2) & "</td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td><div align=""center""><img src=""" & conImgOkNormal & """ border=0 width=14 height=14 align=""center""></div></td>"
           w_html = w_html & VbCrLf & "         <td>Conclu�da no Prazo</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & FormatNumber(cDbl(w_vr_concluido),2) & "</td>"
           If cDbl(w_vr_total) > 0 Then
              w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_vr_concluido/w_vr_total)*100),2) & "</td>"
           Else
              w_html = w_html & VbCrLf & "         <td><div align=""right"">0</td>"
           End If
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & w_concluido & "</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_concluido/w_total)*100),2) & "</td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td><div align=""center""><img src=""" & conImgOkAtraso & """ border=0 width=14 height=14 align=""center""></div></td>"
           w_html = w_html & VbCrLf & "         <td>Conclu�da Ap�s o Prazo</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & FormatNumber(cDbl(w_vr_conc_atraso),2) & "</td>"
           If cDbl(w_vr_total) > 0 Then
              w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_vr_conc_atraso/w_vr_total)*100),2) & "</td>"
           Else
              w_html = w_html & VbCrLf & "         <td><div align=""right"">0</td>"
           End If
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & w_conc_atraso & "</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & round(((w_conc_atraso/w_total)*100),2) & "</td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td><div align=""center""><img src=""" & conImgCancel & """ border=0 width=14 height=14 align=""center""></div></td>"
           w_html = w_html & VbCrLf & "         <td>Cancelada(*)</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & FormatNumber(cDbl(w_vr_cancelado),2) & "</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">-</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">" & w_cancelado & "</td>"
           w_html = w_html & VbCrLf & "         <td><div align=""right"">-</td></tr>"
           w_html = w_html & VbCrLf & "       <tr><td colspan=""2"" bgColor=""#f0f0f0""><b>Total</b></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""right"">" & FormatNumber(cDbl(w_vr_total),2) & "</td>"
           If cDbl(w_vr_total) > 0 Then
              w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""right"">" & round(((w_vr_total/w_vr_total)*100),2) & "%</td>"
           Else
              w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""right"">0%</td>"
           End If
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""right"">" & w_total & "</td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""right"">" & round(((w_total/w_total)*100),2) & "%</td></tr>"
           w_html = w_html & VbCrLf & "     </table></div></td></tr>"
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2"">(*) Valores n�o considerados no c�lculo dos totais</td></tr>"     
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhuma tarefa cadastrada</div></td></tr>"
        End If
        RS2.Close
     End If
     
     ' Interessados na execu��o da a��o
     If uCase(w_interessado) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>INTERESSADOS NA EXECU��O<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        DB_GetSolicInter RS1, w_chave, null, "LISTA"
        RS1.Sort = "nome_resumido"
        If Not RS1.EOF Then
           TP = RemoveTP(TP)&" - Interessados"
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Clique <a class=""HL"" HREF=""" & w_dir & "Acao.asp?par=interess&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=4&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""blank"">aqui</a> para visualizar os Interessados na execu��o</div></td></tr>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhum interessado cadastrado</div></td></tr>"
        End If
        RS1.Close
     End If
     
     ' Arquivos vinculados
     If uCase(w_anexo) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        DB_GetSolicAnexo RS1, w_chave, null, w_cliente
        RS1.Sort = "nome"
        If Not RS1.EOF Then
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
           w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
           w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><b>T�tulo</b></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Descri��o</b></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Tipo</b></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>KB</b></div></td>"
           w_html = w_html & VbCrLf & "       </tr>"
           While Not RS1.EOF
              w_html = w_html & VbCrLf & "       <tr><td>" & LinkArquivo("HL", w_cliente, RS1("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS1("nome"), null) & "</td>"
              w_html = w_html & VbCrLf & "           <td>" & Nvl(RS1("descricao"),"-") & "</td>"
              w_html = w_html & VbCrLf & "           <td>" & RS1("tipo") & "</td>"
              w_html = w_html & VbCrLf & "         <td><div align=""right"">" & Round(cDbl(RS1("tamanho"))/1024,1) & "&nbsp;</td>"
              w_html = w_html & VbCrLf & "      </tr>"
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></div></td></tr>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">Nenhuma arquivo cadastrado</div></td></tr>"
        End If
        RS1.Close
     End If
     
     ' Encaminhamentos
     If uCase(w_ocorrencia) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>OCORR�NCIAS E ANOTA��ES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        DB_GetSolicLog RS1, w_chave, null, "LISTA"
        RS1.Sort = "data desc"
        If Not RS1.EOF Then
           w_html = w_html & VbCrLf & "   <tr><td colspan=""2""><div align=""center"">"
           w_html = w_html & VbCrLf & "     <table width=100%  border=""1"" bordercolor=""#00000"">"
           w_html = w_html & VbCrLf & "       <tr><td bgColor=""#f0f0f0""><div align=""center""><b>Data</b></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Ocorr�ncia/Anota��o</b></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Respons�vel</b></div></td>"
           w_html = w_html & VbCrLf & "         <td bgColor=""#f0f0f0""><div align=""center""><b>Fase/Destinat�rio</b></div></td>"
           w_html = w_html & VbCrLf & "       </tr>"
           w_html = w_html & VbCrLf & "       <tr><td colspan=""4"">Fase Atual: <b>" & RS1("fase") & "</b></td></tr>"
           While Not RS1.EOF
              w_html = w_html & VbCrLf & "    <tr><td nowrap>" & FormataDataEdicao(FormatDateTime(RS1("data"),2)) & ", " & FormatDateTime(RS1("data"),4)& "</td>"
              w_html = w_html & VbCrLf & "        <td>" & CRLF2BR(Nvl(RS1("despacho"),"---")) & "</td>"
              w_html = w_html & VbCrLf & "        <td nowrap>" & ExibePessoa("../", w_cliente, RS1("sq_pessoa"), TP, RS1("responsavel")) & "</td>"
              If (Not IsNull(Tvl(RS1("sq_projeto_log")))) and (Not IsNull(Tvl(RS1("destinatario")))) Then
                 w_html = w_html & VbCrLf & "        <td nowrap>" & ExibePessoa("../", w_cliente, RS1("sq_pessoa_destinatario"), TP, RS1("destinatario")) & "</td>"
              ElseIf (Not IsNull(Tvl(RS1("sq_projeto_log")))) and IsNull(Tvl(RS1("destinatario"))) Then
                 w_html = w_html & VbCrLf & "        <td nowrap>Anota��o</td>"
              Else
                 w_html = w_html & VbCrLf & "        <td nowrap>" & Nvl(RS1("tramite"),"---") & "</td>"
              End If
              w_html = w_html & VbCrLf & "      </tr>"
              RS1.MoveNext
           wend
           w_html = w_html & VbCrLf & "         </table></div></td></tr>"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><div align=""center"">N�o foi encontrado nenhum encaminhamento</div></td></tr>"
        End If
        RS1.Close
     End If
     
     If uCase(w_dados_consulta) = uCase("sim") Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><b>Consulta Realizada por:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" &  Session("NOME_RESUMIDO") & "</td></tr>"
        w_html = w_html & VbCrLf & "   <tr><td><b>Data da Consulta:</b></td>"
        w_html = w_html & VbCrLf & "       <td>" &  FormataDataEdicao(FormatDateTime(now(),2)) & ", " & FormatDateTime(now(),4) & "</td></tr>"
     End If  
  End If
  
  VisualAcao = w_html
  
  Set w_html = Nothing
  
End Function
%>

