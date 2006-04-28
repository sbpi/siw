<%
REM =========================================================================
REM Rotina de valida��o dos dados do lan�amento financeiro
REM -------------------------------------------------------------------------
Function ValidaLancamento(p_cliente, p_chave, p_sg1, p_sg2, p_sg3, p_sg4, p_tramite)

  ' Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  ' Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  ' 0 - Erro de integridade. Nem gestores podem encaminhar a solicita��o
  ' 1 - Erro de regra de neg�cio. Apenas gestores podem encaminhar a solicita��o
  ' 2 - Alerta. O sistema indica uma situa��o n�o desej�vel mas permite que o usu�rio
  '     encaminhe o lan�amento
  '-----------------------------------------------------------------------------------
  ' Cria recordsets e vari�veis de trabalho.
  ' l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  ' de dados espec�ficos da solicita��o que est� sendo validada.
  '-----------------------------------------------------------------------------------
  Dim l_rs_modulo, l_rs_solic, l_rs_tramite
  Dim l_rs1, l_rs2, l_rs3, l_rs4, l_erro, l_tipo
  Dim l_existe_rs1, l_existe_rs2, l_existe_rs3, l_existe_rs4
  Dim l_financeiro
  Set l_rs_modulo = Server.CreateObject("ADODB.RecordSet")
  Set l_rs_solic = Server.CreateObject("ADODB.RecordSet")
  Set l_rs_tramite = Server.CreateObject("ADODB.RecordSet")
  Set l_rs1 = Server.CreateObject("ADODB.RecordSet")
  Set l_rs2 = Server.CreateObject("ADODB.RecordSet")
  Set l_rs3 = Server.CreateObject("ADODB.RecordSet")
  Set l_rs4 = Server.CreateObject("ADODB.RecordSet")
          
  
  '-----------------------------------------------------------------------------------
  ' Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  ' comp�em a solicita��o
  '-----------------------------------------------------------------------------------
  ' Recupera os dados da solicita��o
  DB_GetSolicData l_rs_solic, p_chave, p_sg1
  
  '-----------------------------------------------------------------------------
  ' Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
  ' um encaminhamento.
  '-----------------------------------------------------------------------------
    
  ' Se a solicita��o informada n�o existir, abandona a execu��o
  If l_rs_solic.eof Then
     ValidaLancamento = "0<li>N�o existe registro no banco de dados com o n�mero informado."
     l_rs_solic.close
     Exit Function
  End If
    
  ' Verifica se o cliente tem o m�dulo financeiro contratado
  DB_GetSiwCliModLis l_rs_modulo, p_cliente, null, "FN"
  If Not l_rs_modulo.EOF Then l_financeiro = "S" Else l_financeiro = "N" End If
  l_rs_modulo.close

  l_erro = ""
  l_tipo = ""

  ' Recupera o tr�mite atual da solicita��o
  DB_GetTramiteData l_rs_tramite, l_rs_solic("sq_siw_tramite")
          
     '-----------------------------------------------------------------------------
     ' Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
     ' um encaminhamento independente da fase e em alguns casos quando a fase for
     ' diferente de conclus�o.
     ' 1 - Verifica se o valor do lan�amento � maior que zero
     ' 2 - Verifica se o valor do lan�amento � igual � soma dos valores dos documentos
     '-----------------------------------------------------------------------------
     ' 1 - Verifica se o valor do lan�amento � maior que zero
     If cDbl(l_rs_solic("valor")) = 0 Then
        l_erro = l_erro & "<li>O lan�amento n�o pode ter valor zero."
        l_tipo = 0
     End If
     ' 2 - Verifica se o valor do lan�amento � igual � soma dos valores dos documentos
     DB_GetLancamentoDoc l_rs1, p_chave, null, "LISTA"
     If l_rs1.eof Then l_existe_rs1 = 0 Else l_existe_rs1 = l_rs1.recordCount End If
     If ((cDbl(l_rs_solic("valor")) <> cDbl(l_rs_solic("valor_doc"))) and l_rs1.RecordCount <> 0) and Nvl(l_rs_tramite("ordem"),"---") <= "2" Then
        l_erro = l_erro & "<li>O valor do lan�amento (<b>R$ " & FormatNumber(cDbl(Nvl(l_rs_solic("valor"),0)),2) & "</b>) difere da soma dos valores dos documentos (<b>R$ " & FormatNumber(cDbl(Nvl(l_rs_solic("valor_doc"),0)),2) & "</b>)."
        l_tipo = 0
     End If
     
     '-----------------------------------------------------------------------------
     ' Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
     ' um encaminhamento e tiver fase posterior a 2�.
     ' 1 - Recupera os dados da pessoa
     ' 2 - Verifica se foi indicada a pessoa
     ' 3 - Verifica se a pessoa informada � do tipo indicada no cadastro do lan�amento 
     ' 4 - Recupera os documentos associados ao lan�amento
     ' 5 - Verifica se a pessoa foi indicada
     ' 6 - Verifica se o valor do lan�amento � igual � soma dos valores dos documentos
     '-----------------------------------------------------------------------------

     If not l_rs_tramite.eof Then
        If Nvl(l_rs_tramite("ordem"),"---") > "2" Then
           l_erro = l_erro
           If Nvl(l_rs_tramite("sigla"),"---") = "EE" Then
              ' 1 - Recupera os dados da pessoa
              DB_GetBenef l_rs1, p_cliente, Nvl(l_rs_solic("pessoa"),0), null, null, null, null, null, null
              If l_rs1.eof Then l_existe_rs1 = 0 Else l_existe_rs1 = l_rs1.recordCount End If

              If l_existe_rs1 = 0 Then
                 ' 2 - Verifica se foi indicada a pessoa
                 l_erro = l_erro & "<li>A pessoa n�o foi informada"
                 l_tipo = 0
              Else
                 If not (cDbl(Nvl(l_rs_solic("sq_tipo_pessoa"),0)) = cDbl(Nvl(l_rs1("sq_tipo_pessoa"),0))) Then
                   ' 3 - Verifica se a pessoa informada � do tipo indicada no cadastro do lan�amento 
                   l_erro = l_erro & "<li>A pessoa n�o � do tipo informado na tela de dados gerais."
                   l_tipo = 0
                 End If
              End If
              ' 4 - Recupera os documentos associados ao lan�amento
              DB_GetLancamentoDoc l_rs1, p_chave, null, "LISTA"
              If l_rs1.eof Then l_existe_rs1 = 0 Else l_existe_rs1 = l_rs1.recordCount End If
              If l_existe_rs1 = 0 Then
                 ' 5 - Verifica se a pessoa foi indicada
                 l_erro = l_erro & "<li>N�o foram informados documentos para o lan�amento. Informe pelo menos um."
                 l_tipo = 0
              Else
                 ' 6 - Verifica se o valor do lan�amento � igual � soma dos valores dos documentos
                 If cDbl(l_rs_solic("valor")) <> cDbl(l_rs_solic("valor_doc")) Then
                    l_erro = l_erro & "<li>O valor do lan�amento (<b>R$ " & FormatNumber(cDbl(Nvl(l_rs_solic("valor"),0)),2) & "</b>) difere da soma dos valores dos documentos (<b>R$ " & FormatNumber(cDbl(Nvl(l_rs_solic("valor_doc"),0)),2) & "</b>)."
                    l_tipo = 1
                 End If
              End If
           End If
        End If
     End If

     l_erro = l_tipo & l_erro

     '-----------------------------------------------------------------------------------
     ' Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
     ' para ser usada com a tag <UL>.
     '-----------------------------------------------------------------------------------
     ValidaLancamento = l_erro

  '-----------------------------------------------------------------------------------
  ' Fecha recordsets e libera vari�veis de trabalho.
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
  Set l_financeiro          = Nothing
  
End Function
REM =========================================================================
REM Fim da valida��o do projeto
REM -------------------------------------------------------------------------

%>

