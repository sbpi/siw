<%
REM =========================================================================
REM Rotina de valida��o dos dados do acordo
REM -------------------------------------------------------------------------
Function ValidaAcordo(p_cliente, p_chave, p_sg1, p_sg2, p_sg3, p_sg4, p_tramite)

  ' Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  ' Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  ' 0 - Erro de integridade. Nem gestores podem encaminhar a solicita��o
  ' 1 - Erro de regra de neg�cio. Apenas gestores podem encaminhar a solicita��o
  ' 2 - Alerta. O sistema indica uma situa��o n�o desej�vel mas permite que o usu�rio
  '     encaminhe o projeto
  '-----------------------------------------------------------------------------------
  ' Cria recordsets e vari�veis de trabalho.
  ' l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  ' de dados espec�ficos da solicita��o que est� sendo validada.
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
  ' comp�em a solicita��o
  '-----------------------------------------------------------------------------------
        ' Recupera os dados da solicita��o
        DB_GetSolicData l_rs_solic, p_chave, p_sg1
          
        ' Se a solicita��o informada n�o existir, abandona a execu��o
        If l_rs_solic.eof Then
           ValidaAcordo = "0<li>N�o existe registro no banco de dados com o n�mero informado."
           l_rs_solic.close
           Exit Function
        End If
          
        ' Verifica se o cliente tem o m�dulo de acordos contratado
        DB_GetSiwCliModLis l_rs_modulo, p_cliente, null
        l_rs_modulo.Filter = "sigla='AC'"
        If Not l_rs_modulo.EOF Then l_acordo = "S" Else l_acordo = "N" End If
        l_rs_modulo.close

        l_erro = ""
        l_tipo = ""

        ' Recupera o tr�mite atual da solicita��o
        DB_GetTramiteData l_rs_tramite, l_rs_solic("sq_siw_tramite")
          
        ' Recupera os dados da outra parte
        DB_GetBenef l_rs1, p_cliente, Nvl(l_rs_solic("outra_parte"),0), null, null, null, null, null, null
        If l_rs1.eof Then l_existe_rs1 = 0 Else l_existe_rs1 = l_rs1.recordCount End If

        ' Recupera os dados do preposto
        DB_GetBenef l_rs2, p_cliente, Nvl(l_rs_solic("preposto"),0), null, null, null, null, null, null
        If l_rs2.eof Then l_existe_rs2 = 0 Else l_existe_rs2 = l_rs2.recordCount End If

  '-----------------------------------------------------------------------------------
  ' O bloco abaixo faz as valida��es na solicita��o que n�o s�o poss�veis de fazer
  ' atrav�s do JavaScript por envolver mais de uma tela
  '-----------------------------------------------------------------------------------
  
        '-----------------------------------------------------------------------------
        ' Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
        ' um encaminhamento.
        '-----------------------------------------------------------------------------
        
        If l_existe_rs1 = 0 Then 
           ' Verifica se foi indicada a outra parte
           l_erro = l_erro & "<li>A outra parte n�o foi informada"
           l_tipo = 0
        Else
           If cDbl(Nvl(l_rs_solic("sq_tipo_pessoa"),0)) = 1 Then 
              ' Se outra parte for pessoa f�sica, n�o pode ter preposto
              If l_existe_rs2 > 0 Then
                 l_erro = l_erro & "<li>Quando a outra parte � pessoa f�sica n�o pode haver preposto."
                 l_tipo = 0
              End If
           Else
              If not (cDbl(Nvl(l_rs_solic("sq_tipo_pessoa"),0)) = cDbl(Nvl(l_rs1("sq_tipo_pessoa"),0))) Then
                 ' A outra parte deve ser do tipo informado na tela de dados gerais
                 l_erro = l_erro & "<li>A outra parte n�o � do tipo informado na tela de dados gerais."
                 l_tipo = 0
              End If

              ' Se outra parte for pessoa jur�dica, deve ter preposto
              If l_existe_rs2 = 0 Then
                 l_erro = l_erro & "<li>Se a outra parte for pessoa jur�dica deve haver preposto informado."
                 l_tipo = 0
              Else
                 If not (cDbl(Nvl(l_rs2("sq_tipo_pessoa"),0)) = 1) Then
                    ' O preposto deve ser pessoa f�sica
                    l_erro = l_erro & "<li>O preposto deve ser pessoa f�sica."
                    l_tipo = 0
                 End If
              End If
           End If
        End If
        l_rs1.close()
        l_rs2.close()

  If not l_rs_tramite.eof Then
        If Nvl(l_rs_tramite("ordem"),"---") > "1" Then
           ' Este bloco faz verifica��es em solicita��es que est�o em fases posteriores ao
           ' cadastramento inicial
           l_erro = l_erro
        End If
  End If
  
  l_erro = l_tipo & l_erro

  '-----------------------------------------------------------------------------------
  ' Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  ' para ser usada com a tag <UL>.
  '-----------------------------------------------------------------------------------

        ValidaAcordo = l_erro

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
  Set l_acordo              = Nothing

End Function
REM =========================================================================
REM Fim da valida��o do projeto
REM -------------------------------------------------------------------------

%>

