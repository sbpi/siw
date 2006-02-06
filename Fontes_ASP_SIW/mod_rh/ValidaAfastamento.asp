<%
REM =========================================================================
REM Rotina de valida��o dos dados do afastamento
REM -------------------------------------------------------------------------
Function ValidaAfastamento(p_cliente, p_chave, p_sq_contrato_colaborador, p_dt_ini, p_dt_fim, p_periodo_ini, p_periodo_fim, p_dias)

  ' Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  ' Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  ' 0 - Erro de integridade.
  ' 1 - Erro de regra de neg�cio.
  '-----------------------------------------------------------------------------------
  ' Cria recordsets e vari�veis de trabalho.
  ' l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  ' de dados espec�ficos do afastamento que est� sendo validado.
  '-----------------------------------------------------------------------------------
        Dim l_rs_afast, l_rs_ferias
        Dim l_rs1, l_rs2, l_rs3, l_rs4, l_erro, l_tipo
        Dim l_existe_rs1, l_existe_rs2, l_existe_rs3, l_existe_rs4
        Set l_rs_afast = Server.CreateObject("ADODB.RecordSet")
        Set l_rs1 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs2 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs3 = Server.CreateObject("ADODB.RecordSet")
        Set l_rs4 = Server.CreateObject("ADODB.RecordSet")
          
  
  '-----------------------------------------------------------------------------------
  ' Esta primeira parte carrega o afastamento
  '-----------------------------------------------------------------------------------
        l_erro = "" 
        
        ' Verifica se h� afastamento cadastrado no periodo informado
        DB_GetAfastamento l_rs_afast, p_cliente, null, null, p_sq_contrato_colaborador, p_dt_ini, p_dt_fim, null, null, p_chave, null
        If not l_rs_afast.eof Then
           while not l_rs_afast.EOF
              l_erro = l_erro & "<li>No per�odo informado, existe <b>" & l_rs_afast("nm_tipo_afastamento") & " (" & FormataDataEdicao(FormatDateTime(l_rs_afast("inicio_data"),2)) & "-" & l_rs_afast("inicio_periodo") & " a " & FormataDataEdicao(FormatDateTime(l_rs_afast("fim_data"),2)) & "-" & l_rs_afast("fim_periodo") & ")</b>.</li>"
              l_rs_afast.MoveNext
           wend
           l_rs_afast.close
        End If
          
  '-----------------------------------------------------------------------------------
  ' Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  ' para ser usada com a tag <UL>.
  '-----------------------------------------------------------------------------------

        ValidaAfastamento = l_erro

  '-----------------------------------------------------------------------------------
  ' Fecha recordsets e libera vari�veis de trabalho.
  '-----------------------------------------------------------------------------------
  
  Set l_rs1                 = Nothing
  Set l_rs2                 = Nothing 
  Set l_rs3                 = Nothing 
  Set l_rs4                 = Nothing 
  Set l_rs_afast            = Nothing 
  Set l_rs_ferias           = Nothing 

  Set l_existe_rs1          = Nothing 
  Set l_existe_rs2          = Nothing 
  Set l_existe_rs3          = Nothing 
  Set l_existe_rs4          = Nothing 
  Set l_erro                = Nothing 
  Set l_tipo                = Nothing 

End Function
REM =========================================================================
REM Fim da valida��o do afastamento
REM -------------------------------------------------------------------------

%>

