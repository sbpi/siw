<%
REM =========================================================================
REM Rotina de valida��o dos dados do colaborador
REM -------------------------------------------------------------------------
Function ValidaColaborador(p_cliente, p_sq_pessoa, p_sq_contrato_colaborador, p_encerramento)

  ' Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  ' Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  ' 0 - Erro de integridade.
  ' 1 - Erro de regra de neg�cio.
  '-----------------------------------------------------------------------------------
  ' Cria recordsets e vari�veis de trabalho.
  ' l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  ' de dados espec�ficos do afastamento que est� sendo validado.
  '-----------------------------------------------------------------------------------
        Dim l_rs_afast, l_rs_ferias, l_rs_viagem
        Dim l_rs1, l_rs2, l_rs3, l_rs4, l_erro, l_tipo, l_cont
        Dim l_existe_rs1, l_existe_rs2, l_existe_rs3, l_existe_rs4
        Set l_rs_afast  = Server.CreateObject("ADODB.RecordSet")
        Set l_rs_ferias = Server.CreateObject("ADODB.RecordSet")
        Set l_rs_viagem = Server.CreateObject("ADODB.RecordSet")
        Set l_rs1 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs2 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs3 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs4 = Server.CreateObject("ADODB.RecordSet")
          
  
  l_erro = "" 
  l_cont = 0
  '-----------------------------------------------------------------------------------
  ' Esta primeira parte verifica o afastamento
  '-----------------------------------------------------------------------------------
        ' Verifica se h� afastamento cadastrado para este colaborador
        DB_GetAfastamento l_rs_afast, p_cliente, null, null, p_sq_contrato_colaborador, p_encerramento, p_encerramento, null, null, null, null
        If not l_rs_afast.eof Then
           while not l_rs_afast.EOF
              l_cont = l_cont + 1
              l_rs_afast.MoveNext
           wend
           l_rs_afast.close
        End If

  '-----------------------------------------------------------------------------------
  ' Esta segunda parte verifica as viagens
  '-----------------------------------------------------------------------------------
        ' Verifica se h� viagens cadastradas para este colaborador
        DB_GetViagemBenef l_rs_viagem, null, p_cliente, p_sq_pessoa, null, null, null, p_encerramento, p_encerramento, null
        If not l_rs_viagem.EOF Then
           while not l_rs_viagem.EOF
              If Nvl(l_rs_viagem("sq_viagem"),"") > "" Then
                 l_cont = l_cont + 1
              End If
              l_rs_viagem.MoveNext
           wend
        End If
        l_rs_viagem.close
          
  '-----------------------------------------------------------------------------------
  ' Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  ' para ser usada com a tag <UL>.
  '-----------------------------------------------------------------------------------
        If l_cont > 0 Then
           If Nvl(p_encerramento,"") > "" Then
              l_erro = l_erro & "<li>Colaborador n�o pode ser encerrado por estar vinculado a afastamentos, f�rias ou viagens.</li>"
           Else
              l_erro = l_erro & "<li>Colaborador n�o pode ser exclu�do por estar vinculado a afastamentos, f�rias ou viagens.</li>"
           End If 
        End If
        ValidaColaborador = l_erro

  '-----------------------------------------------------------------------------------
  ' Fecha recordsets e libera vari�veis de trabalho.
  '-----------------------------------------------------------------------------------
  
  Set l_rs1                 = Nothing
  Set l_rs2                 = Nothing 
  Set l_rs3                 = Nothing 
  Set l_rs4                 = Nothing 
  Set l_rs_afast            = Nothing 
  Set l_rs_ferias           = Nothing 
  Set l_rs_viagem           = Nothing 
  
  Set l_existe_rs1          = Nothing 
  Set l_existe_rs2          = Nothing 
  Set l_existe_rs3          = Nothing 
  Set l_existe_rs4          = Nothing 
  Set l_erro                = Nothing 
  Set l_cont                = Nothing
  Set l_tipo                = Nothing 

End Function
REM =========================================================================
REM Fim da valida��o do afastamento
REM -------------------------------------------------------------------------

%>

