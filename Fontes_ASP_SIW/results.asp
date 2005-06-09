<!-- #INCLUDE FILE="Constants.inc" -->
<html>
<head>
</head>
<BASEFONT FACE="Arial, Helvetica, Sans-Serif">
<body bgcolor="#FFFFFF" background="bg.jpg" bgproperties="fixed">
<font size="1">
<br>
<%

If (InStr(1, Request.Form, "tivogold", 1) = 0 )Then
	Response.Write "<h4> Instrução SQL não informada </h4>"
	Response.Write vbCrLf
Else
	On Error Resume Next
	'Init the dsn string
	DIM svrName, dbName
	svrName = Request.Form("serverName")
	dbName = Request.Form("databaseName")
	pswd = Request.Form("password")
	login = Request.Form("userName")
    dim w_var(100)
    for i = 1 to 100
       If piece(strconn,"=",";",i) = w_var(i-1) Then 
          Exit For 
       Else
          w_var(i) = piece(strconn,"=",";",i)
       End If
    next
    for j = 1 to i-2
       If Instr(uCase(w_var(j)),"UID") > 0 and login > "" Then
          myDSN = myDSN & "UID=" & login & ";"
       ElseIf Instr(uCase(w_var(j)),"USER ID") > 0 and login > "" Then
          myDSN = myDSN & "USER ID=" & login & ";"
       ElseIf Instr(uCase(w_var(j)),"PASSWORD") > 0 and pswd > "" Then
          myDSN = myDSN & "PASSWORD=" & pswd & ";"
       Else
          myDSN = myDSN & w_var(j) & ";"
       End If
    next
    Response.Write myDSN & "<br>"
	'tira os brancos e substitui aspas duplas por aspas simples
	mySql = Replace(trim(Request.Form("sqlStr")), """", "'") 

	'Inicializa variáveis
	dispblank="&nbsp;"
	dispnull="-null-"

	'Inicializa objeto de conexão e executa a query
	set conObj = Server.Createobject("adodb.connection")
	conObj.open myDSN
	set rsObj = conObj.Execute(mySql)

	'Verifica erros no VB ou no database.
	'Se não houver erros verifica se a query é do tipo select e, se for, exibe a tabela, caso
	'contrário exibe o resultado da query

	'Verifica erros no VB
	If Err.Number <> 0 Then
		pad="&nbsp;&nbsp;&nbsp;&nbsp;"
	    response.write "<b>Erro VBScript encontrado executando a página</b><br>"
		response.write pad & "Erro# = <b>" & Err.Number & "</b><br>"
		response.write pad & "Descrição = <b>"
		response.write Err.Description & "</b><br>"
		response.write pad & "Fonte = <b>"
		response.write Err.Source & "</b><br>"
	'Verifica erros no database
	Else
		'Exibe mensagem de sucesso e mostra a query executada
		Response.Write "<b>Instrução executada com sucesso!</b><p>"
	    ' Response.Write "Query :<i> " & mySql & "</i><p>"
    	Response.Write vbCrLf

	    'Verifica se um recordset foi retornado
		If  rsObj.EOF Then
			'Exibe o recordset retornado
			Response.Write "<table border=1>"
			Response.Write vbCrLf
			Response.Write "<tr>"
			Response.Write vbCrLf
			'Exibe o cabeçalho da tabela
			For EACH colName IN rsObj.fields
				Response.Write vbTab
				Response.Write "<td><font size=""1""><b>"&colName.name&"</b></td>"
				Response.Write vbCrLf
			Next
			Response.Write "</tr>"
			Response.Write vbCrLf
 		    Response.Write "<b>Registros não encontrados<b>"

		Else
			'Exibe o recordset retornado
			Response.Write "<table border=1>"
			Response.Write vbCrLf
			Response.Write "<tr>"
			Response.Write vbCrLf
			'Exibe o cabeçalho da tabela
			For EACH colName IN rsObj.fields
				Response.Write vbTab
				Response.Write "<td><font size=""1""><b>"&colName.name&"</b></td>"
				Response.Write vbCrLf
			Next
			Response.Write "</tr>"
			Response.Write vbCrLf

			'Exibe os registros encontrados
			Do UNTIL rsObj.EOF
				Response.Write "<tr>"
				Response.Write vbCrLf
				'Exibe cada campo do registro
				For EACH colName IN rsObj.Fields
			    	fieldVal = colName.Value
			    	'Verifica se o valor do campo é nulo
			    	If isnull(fieldVal) Then
			    		fieldVal=dispnull
			    	End If
			    	'Verifica se o valor do campo tem tamanho zero
			    	If trim(fieldVal)="" Then
			    		fieldVal=dispblank
			    	End If
			    	Response.Write vbTab
			        Response.Write "<td valign=top><font size=""1"">"&fieldVal&"</td>"
			        Response.Write vbCrLf
				Next
				Response.Write "</tr>"
				Response.Write vbCrLf
				rsObj.movenext
			Loop
			Response.Write "</table>"
			Response.Write vbCrLf
		End If
	End If
	Set rsObj = NOTHING
	conObj.close
	Set conObj = NOTHING
End If

REM =========================================================================
REM Retorna uma parte qualquer de uma linha delimitada
REM -------------------------------------------------------------------------
Function Piece (p_line, p_delimiter, p_separator, p_position)

  Dim l_i, l_result, l_actual

  l_actual = p_line
  l_result = p_line
  If not IsNull(p_separator) and (p_separator > "") Then
     For l_i = 1 TO p_position
        If Instr(l_actual,p_separator) > 0 Then
           l_result = Mid(l_actual, 1,Instr(l_actual,p_separator)-1)
           l_actual = Mid(l_actual, Instr(l_actual,p_separator)+1, len(l_actual))
           If l_i = p_position - 1 and Instr(l_actual,p_separator) = 0 Then l_actual = l_actual & ";" End If
        Else
           Piece = ""
           Exit For
        End If
     Next
  End If
  
  Piece = l_result
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

%>
</body>
</html>

