<%
Option Explicit
Response.Expires = -1
Response.ContentType = "image/gif"

Dim cso		        ' objeto OWC.ChartSpace
Dim m_fso		    ' objeto file system
Dim m_objBinaryFile ' objeto BinaryFileStream
Dim c               ' objeto OWC Constants
Dim cht             ' referencia ao gráfico
Dim axc, axv
Dim sFullFileName   ' caminho físico para o GIF gerado
Dim asSeriesNames(7)
Dim asCategories(7)
Dim asValues(7)
Dim p_tipo, p_grafico, p_tot, p_cad, p_tram, p_conc, p_atraso, p_aviso, p_acima
p_tipo      = Request("p_tipo")
p_grafico   = Request("p_grafico")
p_tot       = Request("p_tot")
p_cad       = Request("p_cad")
p_tram      = Request("p_tram")
p_conc      = Request("p_conc")
p_atraso    = Request("p_atraso")
p_aviso     = Request("p_aviso")
p_acima     = Request("p_acima")
set cso     = server.CreateObject("OWC10.ChartSpace")
With cso
   Set c = .Constants
   asSeriesNames(0) = "Total"
   asSeriesNames(1) = "Cadastramento"
   asSeriesNames(2) = "Tramitação"
   asSeriesNames(3) = "Encerradas"
   asSeriesNames(4) = "Atrasadas"
   asSeriesNames(5) = "Em aviso de atraso"
   asSeriesNames(6) = "Acima do valor previsto"
   asCategories(0) = "Total"
   asCategories(1) = "Cadastramento"
   asCategories(2) = "Tramitação"
   asCategories(3) = "Encerradas"
   asCategories(4) = "Atrasadas"
   asCategories(5) = "Em aviso de atraso"
   asCategories(6) = "Acima do valor previsto"
   asValues(0)     = p_tot
   asValues(1)     = p_cad
   asValues(2)     = p_tram
   asValues(3)     = p_conc
   asValues(4)     = p_atraso
   asValues(5)     = p_aviso
   asValues(6)     = p_acima
   
   Set cht         = .Charts.Add
   
   With cht
      .Type                     = c.chChartTypeBarClustered 'c.chChartTypeColumnClustered
      
      .SetData c.chDimSeriesNames,  c.chDataLiteral, asSeriesNames
      .SeriesCollection(0).SetData c.chDimCategories,   c.chDataLiteral, asCategories(0)
      .SeriesCollection(0).SetData c.chDimValues,       c.chDataLiteral, asValues(0)

      .SeriesCollection(1).SetData c.chDimCategories,   c.chDataLiteral, asCategories(1)
      .SeriesCollection(1).SetData c.chDimValues,       c.chDataLiteral, asValues(1)

      .SeriesCollection(2).SetData c.chDimCategories,   c.chDataLiteral, asCategories(2)
      .SeriesCollection(2).SetData c.chDimValues,       c.chDataLiteral, asValues(2)

      .SeriesCollection(3).SetData c.chDimCategories,   c.chDataLiteral, asCategories(3)
      .SeriesCollection(3).SetData c.chDimValues,       c.chDataLiteral, asValues(3)

      .SeriesCollection(4).SetData c.chDimCategories,   c.chDataLiteral, asCategories(4)
      .SeriesCollection(4).SetData c.chDimValues,       c.chDataLiteral, asValues(4)
      .SeriesCollection(4).Interior.Color = "red"

      .SeriesCollection(5).SetData c.chDimCategories,   c.chDataLiteral, asCategories(5)
      .SeriesCollection(5).SetData c.chDimValues,       c.chDataLiteral, asValues(5)
       
      .SeriesCollection(6).SetData c.chDimCategories,   c.chDataLiteral, asCategories(6)
      .SeriesCollection(6).SetData c.chDimValues,       c.chDataLiteral, asValues(6)
       
      .HasTitle                 = False
      .HasLegend                = False

      Set axc                   = .Axes(c.chAxisPositionCategory)
      axc.HasTitle              = True
      axc.Title.Caption         = "Situação"
      axc.Title.Font.Name       = "Arial"
      axc.Title.Font.Size       = 9
      axc.Title.Font.Bold       = True
      
      Set axv                   = .Axes(c.chAxisPositionValue)
      axv.HasTitle              = True
      axv.Title.Caption         = "Quantidade"
      axv.Title.Font.Name       = "Arial"
      axv.Title.Font.Size       = 9
      axv.Title.Font.Bold       = True
   End With 
End With

' Salva para arquivo temporário
set m_fso = CreateObject("Scripting.FileSystemObject")
sFullFileName = replace(Server.MapPath(".") & "\files\" & m_fso.GetTempName(),".tmp",".gif")
cso.ExportPicture sFullFileName, "gif", 600, 300

on error resume next
' Retorna a imagem como fluxo binário usando o BinaryFileStream ActiveX DLL
set m_objBinaryFile = server.CreateObject("BinaryFileStream.Object")
Response.BinaryWrite m_objBinaryFile.GetFileBytes(CStr(sFullFileName))

' Remove o arquivo temporário
m_objBinaryFile.DeleteFile CStr(sFullFileName)

%>