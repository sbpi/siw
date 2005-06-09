<%
REM =========================================================================
REM Rotina de visualização dos dados do projeto
REM -------------------------------------------------------------------------
Function VisualProjeto(w_chave, O, w_usuario, P1, P4)

  Dim Rsquery, w_Erro
  Dim w_Imagem, w_html, w_TrBgColor
  Dim w_ImagemPadrao, w_tramite, w_or_tramite
  Dim w_tipo_visao
  Dim w_p2, w_fases
  Dim w_acordo, w_count
  Set RsQuery = Server.CreateObject("ADODB.RecordSet")
  
  If P4 = 1 Then w_TrBgColor = "" Else w_TrBgColor = conTrBgColor End If
  
  w_html = ""

  ' Verifica se o cliente tem o módulo de acordos contratado
  DB_GetSiwCliModLis RS, w_cliente, null
  RS.Filter = "sigla='AC'"
  If Not RS.EOF Then w_acordo = "S" Else w_acordo = "N" End If
  DesconectaBD

  ' Recupera os dados do projeto
  DB_GetSolicData RS, w_chave, "PJGERAL"
  w_tramite     = RS("sq_siw_tramite")
  w_or_tramite  = cStr(RS("or_tramite"))

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
     DB_GetSolicInter Rsquery, w_chave, w_usuario, "REGISTRO"
     If Not RSquery.EOF Then
        ' Se for interessado, verifica a visão cadastrada para ele.
        w_tipo_visao = cDbl(RSquery("tipo_visao"))
     Else
        DB_GetSolicAreas Rsquery, w_chave, Session("sq_lotacao"), "REGISTRO"
        If Not RSquery.EOF Then
           ' Se for de uma das unidades envolvidas, tem visão parcial
           w_tipo_visao = 1
        Else
           ' Caso contrário, tem visão resumida
           w_tipo_visao = 2
        End If

        If SolicAcesso(w_chave, w_usuario) > 2 Then w_tipo_visao = 1 End If
     End If
  End If
  
  ' Se for listagem ou envio, exibe os dados de identificação do projeto
  If O = "L" or O = "V" or O = "T" Then ' Se for listagem dos dados
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & w_TrBgColor & """><td>"

     w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
     w_html = w_html & VbCrLf & "      <tr valign=""bottom"">"
     w_html = w_html & VbCrLf & "          <td><font size=1>Projeto: <b>" & RS("titulo") & " (" & RS("sq_siw_solicitacao") & ")</b></font></td>"
     w_html = w_html & VbCrLf & "          <td><font size=1>Nº do PRONAC: <b>" & Nvl(RS("palavra_chave"),"---") & "</b></font></td>"
     If Not (P1 = 4 or P4 = 1) Then
        w_html = w_html & VbCrLf & "       <td align=""right""><font size=""1""><b><A class=""hl"" HREF=""" & w_dir & "Projeto.asp?par=Visual&O=T&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=volta&P1=4&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=GDPCAD"" title=""Exibe as informações do projeto."">Exibir todas as informações</a></td></tr>"
     End If
      
      ' Identificação do projeto
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação do projeto</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=""1"">Resumo do projeto:<br><b>" & Nvl(CRLF2BR(RS("descricao")),"---") & " </b></td>"
     If Nvl(RS("justificativa"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=""1"">Local:<br><b>" & Nvl(CRLF2BR(RS("justificativa")),"---") & " </b></td>" End If
     ' Se a classificação foi informada, exibe.
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3""><font size=""1"">Classificação:<br><b>" & RS("cc_nome") & " </b></td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Informações adicionais</td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     If Not P4 = 1 Then
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Gerente:<br><b>" & ExibePessoa(w_dir_volta, w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade responsável:<br><b>" & ExibeUnidade(w_dir_volta, w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
     Else
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Gerente:<br><b>" & RS("nm_sol") & "</b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade responsável:<br><b>" & RS("nm_unidade_resp") & "</b></td>"
     End If
     If Nvl(RS("prioridade"),"") > "" Then
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RS("prioridade")) & " </b></td>"
     End If
     If w_acordo = "S" Then
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        If RS("vincula_contrato") = "S" Then w_html = w_html & VbCrLf & "<td><font size=""1"">Permite a vinculação de contratos:<br><b>Sim</b>" Else w_html = w_html & VbCrLf & "<td><font size=""1"">Permite a vinculação de contratos:<br><b>Não</b>" End If
        If RS("vincula_viagem")   = "S" Then w_html = w_html & VbCrLf & "<td><font size=""1"">Permite a vinculação de viagens:<br><b>Sim</b>" Else w_html = w_html & VbCrLf & "<td><font size=""1"">Permite a vinculação de viagens:<br><b>Não</b>" End If
        DB_GetViagemBenef RSQuery, RS("sq_siw_solicitacao"), w_cliente, null, null, null, null 
        w_html = w_html & VbCrLf & "              <td><font size=""1"">Passagens(Limite/Cadastradas):<br><b>" & FormatNumber(cDbL(Nvl(RS("limite_passagem"),0)),0) & "/" & RSQuery.RecordCount & " </b></td>"
        RSQuery.Close
     End If

     If Nvl(RS("inicio"),"") > "" Then
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "              <TD><font size=""1"">Período de realização do evento:<br>De " & Nvl(FormataDataEdicao(RS("inicio_real")),"---") & " a " & Nvl(FormataDataEdicao(RS("fim_real")),"---")
        w_html = w_html & VbCrLf & "              <TD><font size=""1"">Período do projeto:<br>De " & Nvl(FormataDataEdicao(RS("inicio")),"---") & " a " & Nvl(FormataDataEdicao(RS("fim")),"---")
        w_html = w_html & VbCrLf & "              <td><font size=""1"">Orçamento previsto:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
        If P1 = 4 Then
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           w_html = w_html & VbCrLf & "              <td><font size=""1"">Cidade do evento:<br><b>" & Nvl(RS("nm_cidade_evento"),"---") & " </b></td>"
        End If
     End If

     w_html = w_html & VbCrLf & "          </table>"

     ' Dados da conclusão do projeto, se ela estiver nessa situação
     If RS("concluida") = "S" and Nvl(RS("data_conclusao"),"") > "" Then
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da conclusão</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <tr valign=""top"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da execução:<br><b>" & FormataDataEdicao(RS("inicio_real")) & " </b></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da execução:<br><b>" & FormataDataEdicao(RS("fim_real")) & " </b></td>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Custo real:<br><b>" & FormatNumber(RS("custo_real"),2) & " </b></td>"
        End If
        w_html = w_html & VbCrLf & "          </table>"
        If w_tipo_visao = 0 Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RS("nota_conclusao")) & " </b></td>"
        End If
     End If

     ' Proponente
     DB_GetBenef RSQuery, w_cliente, Nvl(RS("outra_parte"),0), null, null, null, Nvl(RS("sq_tipo_pessoa"),0), null, null
     w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Proponente</td>"
     If RSQuery.EOF Then
        w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=2><b>Proponente não informado"
     Else
        w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>"
        w_html = w_html & VbCrLf & "          " & RSQuery("nm_pessoa") & " (" & RSQuery("nome_resumido") & ")"
        If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 1 Then
           w_html = w_html & VbCrLf & "          - " & RSQuery("cpf")
        Else
           w_html = w_html & VbCrLf & "          - " & RSQuery("cnpj")
        End IF
        w_html = w_html & VbCrLf & "          </font><font size=1>- UF: " & Nvl(RS("nm_uf"),"---")
        If P1 = 4 Then ' Exibe ficha completa
           If cDbl(RS("sq_tipo_pessoa")) = 1 Then
              w_html = w_html & VbCrLf & "      <tr><td colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Sexo:<b><br>" & RSQuery("nm_sexo") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de nascimento:<b><br>" & Nvl(FormataDataEdicao(RSQuery("nascimento")),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Identidade:<b><br>" & Nvl(RSQuery("rg_numero"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de emissão:<b><br>" & Nvl(RSQuery("rg_emissao"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Órgão emissor:<b><br>" & Nvl(RSQuery("rg_emissor"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Passaporte:<b><br>" & Nvl(RSQuery("passaporte_numero"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">País emissor:<b><br>" & Nvl(RSQuery("nm_pais_passaporte"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          </table>"
           Else
              w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=""1"">Inscrição estadual:<b><br>" & Nvl(RSQuery("inscricao_estadual"),"---") & "</td>"
           End If
           If cDbl(RS("sq_tipo_pessoa")) = 1 Then
              w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Endereço comercial, Telefones e e-Mail</td>"
           Else
              w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Endereço principal, Telefones e e-Mail</td>"
           End If
           w_html = w_html & VbCrLf & "      <tr><td colspan=""3""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Endereço:<b><br>" & Nvl(RSQuery("logradouro"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Complemento:<b><br>" & Nvl(RSQuery("complemento"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Bairro:<b><br>" & Nvl(RSQuery("bairro"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           If Nvl(RSQuery("nm_cidade"),"") > "" Then
             If RSQuery("pd_pais") = "S" Then
                w_html = w_html & VbCrLf & "          <td><font size=""1"">Cidade:<b><br>" & RSQuery("nm_cidade") & "-" & RSQuery("co_uf") & "</td>"
             Else
                w_html = w_html & VbCrLf & "          <td><font size=""1"">Cidade:<b><br>" & RSQuery("nm_cidade") & "-" & RSQuery("nm_pais") & "</td>"
             End If
           Else
             w_html = w_html & VbCrLf & "          <td><font size=""1"">Cidade:<b><br>---</td>"
           End If
           w_html = w_html & VbCrLf & "          <td><font size=""1"">CEP:<b><br>" & Nvl(RSQuery("cep"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           If Nvl(RSQuery("nr_telefone"),"") > "" Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<b><br>(" & RSQuery("ddd") & ") " & RSQuery("nr_telefone") & "</td>"
           Else
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<b><br>---</td>"
           End If
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Fax:<b><br>" & Nvl(RSQuery("nr_fax"),"---") & "</td>"
           w_html = w_html & VbCrLf & "          <td><font size=""1"">Celular:<b><br>" & Nvl(RSQuery("nr_celular"),"---") & "</td>"
           If Nvl(RSQuery("email"),"nulo") <> "nulo" Then
              If Not P4 = 1 Then
                 w_html = w_html & VbCrLf & "          <tr><td colspan=4><font size=""1"">e-Mail:<b><br><a class=""hl"" href=""mailto:" & RSQuery("email") & """>" & RSQuery("email") & "</a></td>"
              Else
                 w_html = w_html & VbCrLf & "          <tr><td colspan=4><font size=""1"">e-Mail:<b><br>" & RSQuery("email") & "</td>"
              End If
           Else
              w_html = w_html & VbCrLf & "          <tr><td colspan=4><font size=""1"">e-Mail:<b><br>---</td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
           w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" style=""border: 1px solid rgb(0,0,0);""><font size=""1""><b>Dados bancários</td>"
           w_html = w_html & VbCrLf & "      <tr><td colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
           w_html = w_html & VbCrLf & "          <tr valign=""top"">"
           If Nvl(RSQuery("cd_banco"),"") > "" Then
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>" & RSQuery("cd_banco") & " - " & RSQuery("nm_banco") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>" & RSQuery("cd_agencia") & " - " & RSQuery("nm_agencia") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Operação:<b><br>" & Nvl(RSQuery("operacao"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Número da conta:<b><br>" & Nvl(RSQuery("nr_conta"),"---") & "</td>"
           Else
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Banco:<b><br>---</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Agência:<b><br>---</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Operação:<b><br>---</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Número da conta:<b><br>---</td>"
           End If
           w_html = w_html & VbCrLf & "          </table>"
        End If
     End If

     If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 2 Then ' Se proponente for pessoa jurídica
        w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Representante</td>"
        ' Representantes
        DB_GetAcordoRep RSQuery, RS("sq_siw_solicitacao"), w_cliente, null, null
        If RSQuery.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=2><b>Representante não informado"
        Else
           DB_GetBenef RSQuery, w_cliente, RSQuery("sq_pessoa"), null, null, null, null, null, null
           w_html = w_html & VbCrLf & "      <tr><td colspan=2><font size=2><b>"
           w_html = w_html & VbCrLf & "          " & RSQuery("nm_pessoa") & " (" & RSQuery("nome_resumido") & ")"
           w_html = w_html & VbCrLf & "          - " & RSQuery("cpf")
           w_html = w_html & VbCrLf & "          </font><font size=1>  - Sexo: " & RSQuery("nm_sexo") & "</td>"
           If P1 = 4 Then ' Exibe ficha completa
              w_html = w_html & VbCrLf & "      <tr><td colspan=""3""><table border=0 width=""100%"" cellspacing=0>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Identidade:<b><br>" & Nvl(RSQuery("rg_numero"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de emissão:<b><br>" & Nvl(RSQuery("rg_emissao"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Órgão emissor:<b><br>" & Nvl(RSQuery("rg_emissor"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              If Nvl(RSQuery("nr_telefone"),"") > "" Then
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<b><br>(" & RSQuery("ddd") & ") " & RSQuery("nr_telefone") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "          <td><font size=""1"">Telefone:<b><br>---</td>"
              End If
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Fax:<b><br>" & Nvl(RSQuery("nr_fax"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Celular:<b><br>" & Nvl(RSQuery("nr_celular"),"---") & "</td>"
              If Nvl(RSQuery("email"),"nulo") <> "nulo" Then
                 If Not P4 = 1 Then
                    w_html = w_html & VbCrLf & "              <td><font size=""1"">e-Mail:<b><br><a class=""hl"" href=""mailto:" & RSQuery("email") & """>" & RSQuery("email") & "</a></td>"
                 Else
                    w_html = w_html & VbCrLf & "              <td><font size=""1"">e-Mail:<b><br>" & RSQuery("email") & "</td>"
                 End If
              Else
                 w_html = w_html & VbCrLf & "              <td><font size=""1"">e-Mail:<b><br>---</td>"
              End If
              w_html = w_html & VbCrLf & "          </table>"
           End If
        End If
     End If

     If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 2 and P1 = 4 Then ' Se proponente for pessoa jurídica
        ' Preposto
        DB_GetBenef RSQuery, w_cliente, Nvl(RS("preposto"),0), null, null, null, null, null, null
        w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Preposto</td>"
        If RSQuery.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=2><b>Preposto não informado"
        Else
           w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=2><b>"
           w_html = w_html & VbCrLf & "          " & RSQuery("nm_pessoa") & " (" & RSQuery("nome_resumido") & ")"
           w_html = w_html & VbCrLf & "          - " & RSQuery("cpf")
           If P1 = 4 Then ' Exibe ficha completa
              w_html = w_html & VbCrLf & "      <tr><td colspan=""3""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Sexo:<b><br>" & Nvl(RSQuery("nm_sexo"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Identidade:<b><br>" & Nvl(RSQuery("rg_numero"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de emissão:<b><br>" & Nvl(RSQuery("rg_emissao"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Órgão emissor:<b><br>" & Nvl(RSQuery("rg_emissor"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          </table>"
           End If
        End If
     End If
  End If
  
  ' Apoio
  If P1 = 4 Then
     DB_GetSolicApoioList RSQuery, w_chave, null, null
     RSQuery.Sort = "sq_solic_apoio"
     If Not RSQuery.EOF Then ' Se não foram selecionados registros, exibe mensagem
        w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Apoio</td>"
        w_html = w_html & VbCrLf & "<tr><td colspan=3>"
        w_html = w_html & VbCrLf & "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        w_html = w_html & VbCrLf & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Tipo de apoio</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Entidade</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Valor</font></td>"
        w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Inclusão/Alteração</font></td>"
        w_html = w_html & VbCrLf & "        </tr>"
        While Not RSQuery.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RSQuery("nm_tipo_apoio") & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & RSQuery("Entidade") & "</td>"
           w_html = w_html & VbCrLf & "        <td align=""right""><font size=""1"">" & FormatNumber(RSQuery("valor"),2) & "&nbsp;&nbsp;</td>"
           w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RSQuery("ultima_atualizacao")) & "(" & RSQuery("nome_resumido") &")</td>"
           w_html = w_html & VbCrLf & "      </tr>"
           RSQuery.MoveNext
        wend
        w_html = w_html & VbCrLf & "    </table>"
     End If
  End If
  
  ' Passagens
  If P1 = 4 Then
     DB_GetViagemBenef RSQuery, RS("sq_siw_solicitacao"), w_cliente, null, null, null, null 
     w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Passagens</td>"
        If RSQuery.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td colspan=3><font size=2><b>Nennhuma passagem foi informada"
        Else
           While Not RSQuery.EOF
              w_html = w_html & VbCrLf & "      <tr><td colspan=3 style=""border: 1px solid rgb(0,0,0);""><font size=2><b>"
              w_html = w_html & VbCrLf & "          " & RSQuery("nm_pessoa") & " (" & RSQuery("nome_resumido") & ")"
              w_html = w_html & VbCrLf & "          - " & RSQuery("cpf")
              w_html = w_html & VbCrLf & "      <tr><td colspan=""3""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Sexo:<b><br>" & RSQuery("nm_sexo") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Identidade:<b><br>" & RSQuery("rg_numero") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de emissão:<b><br>" & Nvl(RSQuery("rg_emissao"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Órgão emissor:<b><br>" & RSQuery("rg_emissor") & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Passaporte:<b><br>" & RSQuery("passaporte_numero") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">País emissor:<b><br>" & RSQuery("nm_pais_passaporte") & "</td>"
              If Nvl(RSQuery("nr_telefone"),"") > "" Then
                 w_html = w_html & VbCrLf & "       <td><font size=""1"">Telefone:<b><br>(" & RSQuery("ddd") & ") " & RSQuery("nr_telefone") & "</td>"
              Else
                 w_html = w_html & VbCrLf & "       <td><font size=""1"">Telefone:<b><br>---</td>"
              End If
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Fax:<b><br>" & Nvl(RSQuery("nr_fax"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <tr valign=""top"">"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de saída:<b><br>" & FormataDataEdicao(RSQuery("saida")) & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de retorno:<b><br>" & FormataDataEdicao(RSQuery("saida")) & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Valor:<b><br>" & FormatNumber(cDbl(Nvl(RSQuery("valor"),0)),2) & "</td>"
              w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Cidade de origem:<b><br>" & RSQuery("nm_cidade_origem") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Cidade de destino:<b><br>" & RSQuery("nm_cidade_destino") & "</td>"
              w_html = w_html & VbCrLf & "          <td><font size=""1"">Reserva:<b><br>" & Nvl(RSQuery("reserva"),"---") & "</td>"
              w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Bilhete:<b><br>" & Nvl(RSQuery("bilhete"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          <td colspan=""3""><font size=""1"">Trechos:<b><br>" & Nvl(RSQuery("trechos"),"---") & "</td>"
              w_html = w_html & VbCrLf & "          </table>"
              RSQuery.MoveNext
           wend
        End If
     End If
   
  ' Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  If w_tipo_visao <> 2 and (O = "L" or O = "T") and P1 = 4 Then
     If RS("aviso_prox_conc") = "S" Then
        ' Configuração dos alertas de proximidade da data limite para conclusão do projeto
        w_html = w_html & VbCrLf & "      <tr><td colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Alertas</td>"
        w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","Não") & " </b></td>"
        w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"
        w_html = w_html & VbCrLf & "          </table>"
     End If
     
     'Lista das atividades que não são ligadas a nenhuma etapa
     If O = "T" Then
        DB_GetSolicList RS, w_menu, w_usuario, "GDPCAD", 3, _
        null, null, null, null, null, null, _
        null, null, null, null, _
        null, null, null, null, null, null, null, _
        null, null, null, null, null, w_chave, null, null, null
        RS.Filter = "sq_projeto_etapa = null"
        
        If Not RS.EOF Then
           w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Atividades ligadas ao projeto</td>"
           w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"
           w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Nº</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Detalhamento</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Responsável</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Unidade</font></td>"
           w_html = w_html & VbCrLf & "            <td colspan=2><font size=""1""><b>Execução</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Conc.</font></td>"
           w_html = w_html & VbCrLf & "            <td rowspan=2><font size=""1""><b>Ativ.</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>De</font></td>"
           w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Até</font></td>"
           w_html = w_html & VbCrLf & "          </tr>"
           While not RS.EOF
              If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
              w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """><td><font size=""1"">"
              If RS("concluida") = "N" Then
                 If RS("fim") < Date() Then
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                 End If
              Else
                 If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                 Else
                    w_html = w_html & VbCrLf & "   <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                 End If
              End If
              If Not P4 = 1 Then
                 w_html = w_html & VbCrLf & "  <A class=""hl"" HREF=""ProjetoAtiv.asp?par=Visual&R=ProjetoAtiv.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" target=""blank"">" & RS("sq_siw_solicitacao") & "</a>"
              Else
                 w_html = w_html & VbCrLf & "  " & RS("sq_siw_solicitacao") & ""
              End If
              w_html = w_html & VbCrLf & "   <td><font size=""1"">" & Nvl(RS("assunto"),"-")
              If Not P4 = 1 Then
                 w_html = w_html & VbCrLf & "     <td><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("solicitante"), TP, RS("nm_resp")) & "</td>"
              Else
                 w_html = w_html & VbCrLf & "     <td><font size=""1"">" & RS("nm_resp") & "</td>"
              End If
              w_html = w_html & VbCrLf & "     <td><font size=""1"">" & RS("sg_unidade_resp") & "</td>"
              w_html = w_html & VbCrLf & "     <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("inicio")),"-") & "</td>"
              w_html = w_html & VbCrLf & "     <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("fim")),"-") & "</td>"
              w_html = w_html & VbCrLf & "     <td colspan=2 nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
              RS.MoveNext
           Wend
           w_html = w_html & VbCrLf & "      </td></tr></table>"
        End If         
        DesconectaBD
     End If
     
     ' Etapas do projeto
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
  End If

  ' Se for envio, executa verificações nos dados da solicitação
  w_erro = ValidaProjeto(w_cliente, w_chave, "PJGERAL", null, null, null, Nvl(w_tramite,0))
  If (Session("interno") = "S" or w_or_tramite = 1) and w_erro > "" Then
     w_html = w_html & VbCrLf &  "<tr bgcolor=""" & w_TrBgColor & """><td colspan=3><font size=2>"
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

  If Session("interno") = "S" and P1 = 4 and (O = "L" or O = "V" or O = "T") Then ' Se for listagem dos dados
     ' Encaminhamentos
     DB_GetSolicLog RS, w_chave, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Ocorrências e Anotações</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""3"">"
     w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & VbCrLf & "          <tr bgcolor=""" & w_TrBgColor & """ align=""center"">"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Data</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Despacho/Observação</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Responsável</font></td>"
     w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Fase / Destinatário</font></td>"
     w_html = w_html & VbCrLf & "          </tr>"
     If Rs.EOF Then
        w_html = w_html & VbCrLf & "      <tr bgcolor=""" & w_TrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados encaminhamentos.</b></td></tr>"
     Else
        w_cor = w_TrBgColor
        While Not Rs.EOF
          If w_cor = w_TrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = w_TrBgColor End If
          w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"
          w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & FormataDataEdicao(RS("data")) & "</td>"
          If Nvl(RS("caminho"),"") > "" Then
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---") & "<br>[<a class=""hl"" href=""" & conFileVirtual & w_cliente & "/" & RS("caminho") & """ target=""_blank"" title=""Clique para exibir o anexo em outra janela."">Anexo - " & RS("tipo") & " - " & Round(cDbl(RS("tamanho"))/1024,1) & " KB</a>]") & "</td>"
          Else
             w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
          End If
          'w_html = w_html & VbCrLf & "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("despacho"),"---")) & "</td>"
          If Not P4 = 1 Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa"), TP, RS("responsavel")) & "</td>"
          Else
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS("responsavel") & "</td>"
          End If
          If (Not IsNull(Tvl(RS("sq_projeto_log")))) and (Not IsNull(Tvl(RS("destinatario")))) Then
             If Not P4 = 1 Then
                w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa_destinatario"), TP, RS("destinatario")) & "</td>"
             Else
                w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & RS("destinatario") & "</td>"
             End If
          ElseIf (Not IsNull(Tvl(RS("sq_projeto_log")))) and IsNull(Tvl(RS("destinatario"))) Then
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">Anotação</td>"
          Else
             w_html = w_html & VbCrLf & "        <td nowrap><font size=""1"">" & Nvl(RS("tramite"),"---") & "</td>"
          End If
          w_html = w_html & VbCrLf & "      </tr>"
          Rs.MoveNext
          w_count = w_count + 1
        wend
        w_html = w_html & VbCrLf & "         </table></td></tr>"
     End If
     DesconectaBD
  End If
  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</table>"
  
  VisualProjeto = w_html

  Set w_p2                  = Nothing 
  Set w_fases               = Nothing 

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

