<%
REM =========================================================================
REM Rotina de validação dos dados do projeto
REM -------------------------------------------------------------------------
Function ValidaProjeto(p_cliente, p_chave, p_sg1, p_sg2, p_sg3, p_sg4, p_tramite)

  ' Se não encontrar erro, esta função retorna cadeia fazia.
  ' Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  ' 0 - Erro de integridade. Nem gestores podem encaminhar a solicitação
  ' 1 - Erro de regra de negócio. Apenas gestores podem encaminhar a solicitação
  ' 2 - Alerta. O sistema indica uma situação não desejável mas permite que o usuário
  '     encaminhe o projeto
  '-----------------------------------------------------------------------------------
  ' Cria recordsets e variáveis de trabalho.
  ' l_rs1 até l_rs4 são recordsets que podem ser usados para armazenar dados de blocos
  ' de dados específicos da solicitação que está sendo validada.
  '-----------------------------------------------------------------------------------
        Dim l_rs_modulo, l_rs_solic, l_rs_tramite
        Dim l_rs1, l_rs2, l_rs3, l_rs4, l_erro, l_tipo
        Dim l_existe_rs1, l_existe_rs2, l_existe_rs3, l_existe_rs4
        Dim l_acordo
        Set l_rs_modulo = Server.CreateObject("ADODB.RecordSet")
        Set l_rs_solic = Server.CreateObject("ADODB.RecordSet")
        Set l_rs_tramite = Server.CreateObject("ADODB.RecordSet")
        Set l_rs1 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs2 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs3 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs4 = Server.CreateObject("ADODB.RecordSet")
          
  
  '-----------------------------------------------------------------------------------
  ' Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  ' compõem a solicitação
  '-----------------------------------------------------------------------------------
        ' Recupera os dados da solicitação
        DB_GetSolicData l_rs_solic, p_chave, p_sg1
          
        ' Se a solicitação informada não existir, abandona a execução
        If l_rs_solic.eof Then
           ValidaProjeto = "0<li>Não existe registro no banco de dados com o número informado."
           l_rs_solic.close
           Exit Function
        End If
          
        ' Verifica se o cliente tem o módulo de acordos contratado
        DB_GetSiwCliModLis l_rs_modulo, p_cliente, null
        l_rs_modulo.Filter = "sigla='AC'"
        If Not l_rs_modulo.EOF Then l_acordo = "S" Else l_acordo = "N" End If
        l_rs_modulo.close

        l_erro = ""
        l_tipo = ""

        ' Recupera o trâmite atual da solicitação
        DB_GetTramiteData l_rs_tramite, l_rs_solic("sq_siw_tramite")
          
        ' Recupera os dados do proponente
        DB_GetBenef l_rs1, p_cliente, Nvl(l_rs_solic("outra_parte"),0), null, null, null, null, null, null
        If l_rs1.eof Then l_existe_rs1 = 0 Else l_existe_rs1 = l_rs1.recordCount End If

        ' Recupera os dados do preposto
        DB_GetBenef l_rs2, p_cliente, Nvl(l_rs_solic("preposto"),0), null, null, null, null, null, null
        If l_rs2.eof Then l_existe_rs2 = 0 Else l_existe_rs2 = l_rs2.recordCount End If

  '-----------------------------------------------------------------------------------
  ' O bloco abaixo faz as validações na solicitação que não são possíveis de fazer
  ' através do JavaScript por envolver mais de uma tela
  '-----------------------------------------------------------------------------------
  
        '-----------------------------------------------------------------------------
        ' Verificações de integridade de dados da solicitação, feitas sempre que houver
        ' um encaminhamento.
        '-----------------------------------------------------------------------------
        DB_GetViagemBenef l_rs1, p_chave, p_cliente, null, null, null, null, null, null, null
        If cDbl(Nvl(l_rs_solic("limite_passagem"),0)) = 0 Then
           l_erro = l_erro & "<li>O limite de passagens não foi informado"
           l_tipo = 0
        End If
        If cDbl(l_rs1.RecordCount) <> cDbl(Nvl(l_rs_solic("limite_passagem"),0)) Then
           ' Verifica se foi indicado um proponente
           l_erro = l_erro & "<li>A quantidade das passagens cadastradas (<b>" & l_rs1.RecordCount & "</b>) deve ser igual ao limite autorizado para o projeto (<b>" & Nvl(l_rs_solic("limite_passagem"),0) & "</b>)"
           l_tipo = 0
        End If
        If l_existe_rs1 = 0 Then 
           ' Verifica se foi indicado um proponente
           l_erro = l_erro & "<li>O proponente não foi informado"
           l_tipo = 0
        Else
           If cDbl(Nvl(l_rs_solic("sq_tipo_pessoa"),0)) = 1 Then 
              ' Se proponente for pessoa física, não pode ter preposto
              If l_existe_rs2 > 0 Then
                 l_erro = l_erro & "<li>Proponentes pessoa física não podem ter preposto."
                 l_tipo = 0
              End If
           Else
              If not (cDbl(Nvl(l_rs_solic("sq_tipo_pessoa"),0)) = cDbl(Nvl(l_rs_solic("sq_tipo_pessoa"),0))) Then
                 ' O proponente deve ser do tipo informado na tela de dados gerais
                 l_erro = l_erro & "<li>O proponente não é do tipo informado na tela de dados gerais."
                 l_tipo = 0
              End If

              ' Se proponente for pessoa jurídica, deve ter preposto
              If l_existe_rs2 = 0 Then
                 l_erro = l_erro & "<li>Proponentes pessoa jurídica devem ter preposto informado."
                 l_tipo = 0
              Else
                 If not (cDbl(Nvl(l_rs2("sq_tipo_pessoa"),0)) = 1) Then
                    ' O preposto deve ser pessoa física
                    l_erro = l_erro & "<li>O preposto deve ser pessoa física."
                    l_tipo = 0
                 End If

                 If Nvl(l_rs2("rg_numero"),"") = "" Then
                    l_erro = l_erro & "<li>Os dados do preposto não foram informados na íntegra."
                    l_tipo = 0
                 End If
              End If
           End If

           If Nvl(l_rs_solic("nm_cidade"),"") = "" Then
              l_erro = l_erro & "<li>Os dados do proponente não foram informados na íntegra."
              l_tipo = 0
           End If
           
        End If
        l_rs1.close()
        l_rs2.close()

  If not l_rs_tramite.eof Then
     If Nvl(l_rs_tramite("ordem"),"---") > "1" Then
        ' Este bloco faz verificações em solicitações que estão em fases posteriores ao
        ' cadastramento inicial
        l_erro = l_erro
        If (cDbl(Nvl(l_rs_solic("cidade_evento"),0)) = 0) or _
           (Nvl(l_rs_solic("inicio_real"),"") = "")       or _
           (Nvl(l_rs_solic("fim_real"),"") = "")          or _
           (cDbl(Nvl(l_rs_solic("limite_passagem"),0)) = 0)  _
        Then
           ' Os dados de indetificação do evento e roteiro de viagem devem ser informados na tela de informações adicionais
           l_erro = l_erro & "<li>Os dados de indetificação do evento e roteiro de viagem não foram informados (Trâmite Aprovação -> Operação ""Informar"")."
           l_tipo = 0
        End If
     End If
  End If
  
  l_erro = l_tipo & l_erro

  '-----------------------------------------------------------------------------------
  ' Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
  ' para ser usada com a tag <UL>.
  '-----------------------------------------------------------------------------------

        ValidaProjeto = l_erro

  '-----------------------------------------------------------------------------------
  ' Fecha recordsets e libera variáveis de trabalho.
  '-----------------------------------------------------------------------------------
  l_rs_solic.close
  l_rs_tramite.close
  
  Set l_rs1                 = Nothing
  Set l_rs2                 = Nothing 
  Set l_rs3                 = Nothing 
  Set l_rs4                 = Nothing 
  Set l_rs_solic            = Nothing 
  Set l_rs_tramite          = Nothing 
  Set l_rs_modulo           = Nothing 

  Set l_existe_rs1          = Nothing 
  Set l_existe_rs2          = Nothing 
  Set l_existe_rs3          = Nothing 
  Set l_existe_rs4          = Nothing 
  Set l_erro                = Nothing 
  Set l_tipo                = Nothing 
  Set l_acordo              = Nothing

End Function
REM =========================================================================
REM Fim da validação do projeto
REM -------------------------------------------------------------------------

%>

