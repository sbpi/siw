<%
REM =========================================================================
REM Rotina de valida��o dos dados da miss�o
REM -------------------------------------------------------------------------
Function ValidaViagem(p_cliente, p_chave, p_sg1, p_sg2, p_sg3, p_sg4, p_tramite)

  ' Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  ' Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  ' 0 - Erro de integridade. A solicita��o s� pode ser devolvida
  ' 1 - Erro de regra de neg�cio. Apenas gestores podem encaminhar a solicita��o
  ' 2 - Alerta. O sistema indica uma situa��o n�o desej�vel mas permite que o usu�rio
  '     encaminhe o projeto
  '-----------------------------------------------------------------------------------
  ' Cria recordsets e vari�veis de trabalho.
  ' l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  ' de dados espec�ficos da solicita��o que est� sendo validada.
  '-----------------------------------------------------------------------------------
        Dim l_rs_modulo, l_rs_solic, l_rs_tramite
        Dim l_rs1, l_rs2, l_rs3, l_rs4, l_rs5, l_erro, l_tipo
        Dim l_existe_rs1, l_existe_rs2, l_existe_rs3, l_existe_rs4, l_existe_rs5
        Dim l_viagem
        Set l_rs_modulo = Server.CreateObject("ADODB.RecordSet")
        Set l_rs_solic = Server.CreateObject("ADODB.RecordSet")
        Set l_rs_tramite = Server.CreateObject("ADODB.RecordSet")
        Set l_rs1 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs2 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs3 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs4 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs5 = Server.CreateObject("ADODB.RecordSet")
          
  
  '-----------------------------------------------------------------------------------
  ' Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  ' comp�em a solicita��o
  '-----------------------------------------------------------------------------------
        ' Recupera os dados da solicita��o
        DB_GetSolicData l_rs_solic, p_chave, p_sg1
          
        ' Se a solicita��o informada n�o existir, abandona a execu��o
        If l_rs_solic.eof Then
           ValidaViagem = "0<li>N�o existe registro no banco de dados com o n�mero informado."
           l_rs_solic.close
           Exit Function
        End If
          
        ' Verifica se o cliente tem o m�dulo de viagens contratado
        DB_GetSiwCliModLis l_rs_modulo, p_cliente, null, "PD"
        If Not l_rs_modulo.EOF Then l_viagem = "S" Else l_viagem = "N" End If
        l_rs_modulo.close

        l_erro = ""
        l_tipo = ""

        ' Recupera o tr�mite atual da solicita��o
        DB_GetTramiteData l_rs_tramite, l_rs_solic("sq_siw_tramite")
          
        ' Recupera os dados do proposto
        DB_GetBenef l_rs1, p_cliente, Nvl(l_rs_solic("sq_prop"),0), null, null, null, null, null, null
        If l_rs1.eof Then l_existe_rs1 = 0 Else l_existe_rs1 = l_rs1.recordCount End If

        ' Recupera os par�metros do m�dulo de viagem
        DB_GetPDParametro l_rs2, p_cliente, null, null
        If l_rs2.eof Then l_existe_rs2 = 0 Else l_existe_rs2 = l_rs2.recordCount End If

        ' Recupera os deslocamentos da viagem
        DB_GetPD_Deslocamento l_rs3, p_chave, null, p_sg2
        If l_rs3.eof Then l_existe_rs3 = 0 Else l_existe_rs3 = l_rs3.recordCount End If

        ' Recupera as vincula��es da viagem
        DB_GetSolicList_IS l_rs4, l_rs_solic("sq_menu"), w_usuario, "PDVINC", 5, _
           null, null, null, null, null, null, null, null, null, null, p_chave, _
           null, null, null, null, null, null, null, null, null, null, null, _
           null, null, null, null, null, w_ano
        If l_rs4.eof Then l_existe_rs4 = 0 Else l_existe_rs4 = l_rs4.recordCount End If
        
  '-----------------------------------------------------------------------------------
  ' O bloco abaixo faz as valida��es na solicita��o que n�o s�o poss�veis de fazer
  ' atrav�s do JavaScript por envolver mais de uma tela
  '-----------------------------------------------------------------------------------
  
        '-----------------------------------------------------------------------------
        ' Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
        ' um encaminhamento.
        '-----------------------------------------------------------------------------

        ' Verifica se o in�cio da miss�o atende ao n�mero de dias de anteced�ncia
        ' regulamentares. Se n�o atender, deve ser informada justificativa.
        If (l_rs_solic("inicio") - cDbl(l_rs2("dias_antecedencia")) < Date()) and nvl(l_rs_solic("justificativa"),"") = "" Then
           l_erro = l_erro & "<li>No encaminhamento da PCD deve ser informada a justificativa para n�o cumprimento dos " & l_rs2("dias_antecedencia") & " dias de anteced�ncia do pedido."
           l_tipo = 2
        End If
           
        ' Verifica se foi indicada a outra parte e se seus dados est�o completos
        If l_existe_rs1 = 0 Then 
           l_erro = l_erro & "<li>A outra parte n�o foi informada"
           l_tipo = 0
        Else
           ' Verifica se o benefici�rio tem os dados banc�rios cadastrados
           If nvl(l_rs1("sq_banco"),"") = "" or nvl(l_rs1("sq_agencia"),"") = "" or  nvl(l_rs1("nr_conta"),"") = "" Then
              l_erro = l_erro & "<li>Dados banc�rios incompletos."
              l_tipo = 0
           End If
        End If
  
        ' Verifica se foram cadastrados pelo menos 2 deslocamentos
        If l_existe_rs3 < 2 Then
           l_erro = l_erro & "<li>� obrigat�rio informar pelo menos 2 deslocamentos."
           l_tipo = 0
        End If
           
        ' Verifica se a viagem foi vinculada a pelo menos uma tarefa
        'If l_existe_rs4 < 1 Then
        '   l_erro = l_erro & "<li>� obrigat�rio vincular a PCD a pelo menos uma tarefa."
        '   l_tipo = 0
        'End If
        If not l_rs_tramite.eof Then
              If Nvl(l_rs_tramite("ordem"),"---") > "1" Then
                 ' Este bloco faz verifica��es em solicita��es que est�o em fases posteriores ao
                 ' cadastramento inicial
                 If Nvl(l_rs_tramite("sigla"),"---") = "DF" Then
                    DB_GetPD_Deslocamento l_rs5, p_chave, null, l_rs_tramite("sigla")
                    If cDbl(l_rs5("existe")) = 0 Then
                       l_erro = l_erro & "<li>� obrigat�rio informar as di�rias, mesmo que os valores sejam zeros."
                       l_tipo = 0
                    End If
                 ElseIf Nvl(l_rs_tramite("sigla"),"---") = "AE" Then
                    If (Nvl(l_rs_solic("pta"),"") = "" and cDbl(Nvl(l_rs_solic("valor_passagem"),0)) = 0) Then
                       l_erro = l_erro & "<li>� obrigat�rio informar os dados das passagens."
                       l_tipo = 0
                    End If
                 End If
                 l_erro = l_erro
              End If
        End If
    
        l_rs1.close()
        
  l_erro = l_tipo & l_erro

  '-----------------------------------------------------------------------------------
  ' Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  ' para ser usada com a tag <UL>.
  '-----------------------------------------------------------------------------------

        ValidaViagem = l_erro

  '-----------------------------------------------------------------------------------
  ' Fecha recordsets e libera vari�veis de trabalho.
  '-----------------------------------------------------------------------------------
  l_rs_solic.close
  l_rs_tramite.close
  
  Set l_rs1                 = Nothing
  Set l_rs2                 = Nothing 
  Set l_rs3                 = Nothing 
  Set l_rs4                 = Nothing 
  Set l_rs5                 = Nothing 
  Set l_rs_solic            = Nothing 
  Set l_rs_tramite          = Nothing 
  Set l_rs_modulo           = Nothing 

  Set l_existe_rs1          = Nothing 
  Set l_existe_rs2          = Nothing 
  Set l_existe_rs3          = Nothing 
  Set l_existe_rs4          = Nothing 
  Set l_existe_rs5          = Nothing 
  Set l_erro                = Nothing 
  Set l_tipo                = Nothing 
  Set l_viagem              = Nothing
End Function
%>

