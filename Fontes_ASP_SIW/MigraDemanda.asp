<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /MigraDemanda.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas de localização
REM Mail     : alex@sbpi.com.br
REM Criacao  : 10/06/2003, 15:20
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = I   : Inclusão
REM                   = A   : Alteração
REM                   = C   : Cancelamento
REM                   = E   : Exclusão
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicitação de envio

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3, SQL
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca, w_cor
Dim w_ContOut
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao, w_negrito, w_cor_fonte
Dim w_Assinatura, w_Cliente, w_Classe, w_Usuario, w_sq_usuario_central
Dim w_dir_volta
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = Request("P3")
P4           = Request("P4")
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_troca      = Request("w_troca")
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "MigraDemanda.asp?par="
w_Disabled   = "ENABLED"

If O = "" Then O = "L" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "R" 
     w_TP = TP & " - Resumo"
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_sq_usuario_central = RetornaUsuarioCentral()
Main

FechaSessao

Set w_sq_usuario_central = Nothing
Set w_cor_fonte     = Nothing
Set w_negrito       = Nothing
Set w_cor           = Nothing
Set w_classe        = Nothing
Set w_usuario       = Nothing
Set w_cliente       = Nothing
Set RS              = Nothing
Set RS1             = Nothing
Set RS2             = Nothing
Set RS3             = Nothing
Set SQL             = Nothing
Set Par             = Nothing
Set P1              = Nothing
Set P2              = Nothing
Set P3              = Nothing
Set P4              = Nothing
Set TP              = Nothing
Set SG              = Nothing
Set R               = Nothing
Set O               = Nothing
Set w_ImagemPadrao  = Nothing
Set w_Imagem        = Nothing
Set w_Titulo        = Nothing
Set w_ContOut       = Nothing
Set w_Cont          = Nothing
Set w_Pagina        = Nothing
Set w_Disabled      = Nothing
Set w_TP            = Nothing
Set w_troca         = Nothing
Set w_Assinatura    = Nothing

REM =========================================================================
REM Rotina de informação de ligações
REM -------------------------------------------------------------------------
Sub Inicial

  Dim w_sq_ligacao
  Dim w_sq_cc, p_sq_cc
  Dim w_sq_acordo, p_sq_acordo
  Dim w_assunto, w_imagem
  Dim w_ativo, p_ativo
  Dim w_trabalho
  Dim w_outra_parte_contato, p_outra_parte_contato
  Dim p_Ordena
  Dim w_fax
  Dim w_soma
  Dim w_destino
  Dim w_sq_central_telefonica, w_recebida, w_entrante
  Dim p_numero, p_inicio, p_fim

  w_titulo              = ""
  w_sq_ligacao          = Request("w_sq_ligacao")
  p_sq_cc               = uCase(Request("p_sq_cc"))
  p_outra_parte_contato = uCase(Request("p_outra_parte_contato"))
  p_ativo               = uCase(Request("p_ativo"))
  p_inicio              = uCase(Request("p_inicio"))
  p_fim                 = uCase(Request("p_fim"))
  p_numero              = uCase(Request("p_numero"))
  p_ordena              = uCase(Request("p_ordena"))
  
  If O = "L" or O = "I" Then

     SQL = "select a.cd_solicitacao, 940 sq_menu,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then 4 -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then 1 -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 3 -- concluída  " & VbCrLf & _
           "               else case when dt_fase7 is null     then 2 -- execução  " & VbCrLf & _
           "                         else                           3 -- concluída  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end siw_tramite,  " & VbCrLf & _
           "       case IsNull(a.cd_user_orig, a.cd_user_inseriu)  " & VbCrLf & _
           "            when 1 then 280 -- Alexandre  " & VbCrLf & _
           "            when 2 then 301 -- Iuri  " & VbCrLf & _
           "            when 3 then 301 -- Celso  " & VbCrLf & _
           "            when 4 then 301 -- Manuela  " & VbCrLf & _
           "            when 6 then 301  -- Liliane  " & VbCrLf & _
           "            when 7 then 280 -- Daniel  " & VbCrLf & _
           "            else   null  " & VbCrLf & _
           "       end solicitante,  " & VbCrLf & _
           "       case a.cd_user_inseriu  " & VbCrLf & _
           "            when 1 then 280 -- Alexandre  " & VbCrLf & _
           "            when 2 then 301 -- Iuri  " & VbCrLf & _
           "            when 3 then 301 -- Celso  " & VbCrLf & _
           "            when 4 then 301 -- Manuela  " & VbCrLf & _
           "            when 6 then 301  -- Liliane  " & VbCrLf & _
           "            when 7 then 280 -- Daniel  " & VbCrLf & _
           "            else   null  " & VbCrLf & _
           "       end cadastrador,  " & VbCrLf & _
           "       case a.cd_resp_fase5  " & VbCrLf & _
           "            when 1 then 280 -- Alexandre  " & VbCrLf & _
           "            when 2 then 301 -- Iuri  " & VbCrLf & _
           "            when 3 then 301 -- Celso  " & VbCrLf & _
           "            when 4 then 301 -- Manuela  " & VbCrLf & _
           "            when 6 then 301  -- Liliane  " & VbCrLf & _
           "            when 7 then 280 -- Daniel  " & VbCrLf & _
           "            else   null  " & VbCrLf & _
           "       end executor,  " & VbCrLf & _
           "       null descricao, null justificativa,  " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103) inicio,   " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103) fim,  " & VbCrLf & _
           "       convert(varchar, a.dt_solicitacao,                               120) inclusao,  " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_fase2          ,a.dt_solicitacao),  120) ultima_alteracao,  " & VbCrLf & _
           "       case when a.dt_fase7 is null then   " & VbCrLf & _
           "                 case when a.cd_fase_solicitacao = 8   " & VbCrLf & _
           "                      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)  " & VbCrLf & _
           "                      else null  " & VbCrLf & _
           "                  end  " & VbCrLf & _
           "            else convert(varchar, a.dt_fase7, 103)  " & VbCrLf & _
           "       end conclusao,  " & VbCrLf & _
           "       case when IsNull(a.nr_horas_previsao,0) = 0 then 0.5  " & VbCrLf & _
           "            else a.nr_horas_previsao  " & VbCrLf & _
           "       end valor,   " & VbCrLf & _
           "       null opiniao, 3 data_hora,   " & VbCrLf & _
           "       case a.cd_dep_orig  " & VbCrLf & _
           "            when 1 then 189 -- Presidência  " & VbCrLf & _
           "            when 2 then 192 -- TI  " & VbCrLf & _
           "            when 3 then 194 -- Projetos  " & VbCrLf & _
           "            when 4 then 195 -- Administração  " & VbCrLf & _
           "            when 5 then 196 -- Atendimento  " & VbCrLf & _
           "       end sq_unidade,  " & VbCrLf & _
           "       null sq_solic_pai,   " & VbCrLf & _
           "       null sq_cc,  " & VbCrLf & _
           "       c.ds_categoria+'; '+a.nr_solicitacao palavra_chave,  " & VbCrLf & _
           "       1724 sq_cidade_origem,  " & VbCrLf & _
           "       case IsNull(a.cd_dep_dest, a.cd_dep_orig)  " & VbCrLf & _
           "            when 1 then 189 -- Presidência  " & VbCrLf & _
           "            when 2 then 192 -- TI  " & VbCrLf & _
           "            when 3 then 194 -- Projetos  " & VbCrLf & _
           "            when 4 then 195 -- Administração  " & VbCrLf & _
           "            when 5 then 196 -- Atendimento  " & VbCrLf & _
           "       end sq_unidade_resp,  " & VbCrLf & _
           "       a.ds_solicitacao assunto,  " & VbCrLf & _
           "       IsNull(a.cd_prioridade,3)-1 prioridade,  " & VbCrLf & _
           "       'N' aviso_prox_conc, 0 dias_aviso,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103)  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end inicio_real,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_fase2),  103)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_fase2),  103)  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end fim_real,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then 'S'  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then 'N'  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 'S'  " & VbCrLf & _
           "               else case when dt_fase7 is null     then 'N'  " & VbCrLf & _
           "                         else                           'S'  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end concluida,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null  " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end data_conclusao,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 'Demanda atendida'  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null  " & VbCrLf & _
           "                         else                           'Demanda atendida'  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end nota_conclusao,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then -- concluída  " & VbCrLf & _
           "                    case when IsNull(g.valor,0) = 0  " & VbCrLf & _
           "                         Then 0.5  " & VbCrLf & _
           "                         Else g.valor  " & VbCrLf & _
           "                    end  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else case when IsNull(g.valor,0) = 0  " & VbCrLf & _
           "                                   Then 0.5  " & VbCrLf & _
           "                                   Else g.valor  " & VbCrLf & _
           "                               end  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end custo_real,  " & VbCrLf & _
           "       b.nome proponente,  " & VbCrLf & _
           "       case when h.dt_inicio is null then  " & VbCrLf & _
           "            case when a.dt_fase2 > a.dt_previsao_inicio  " & VbCrLf & _
           "                 then convert(varchar, IsNull(a.dt_fase2,           a.dt_solicitacao),  120)  " & VbCrLf & _
           "                 else convert(varchar, IsNull(a.dt_previsao_inicio, a.dt_solicitacao),  120)  " & VbCrLf & _
           "            end  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "            case when a.dt_fase2 < h.dt_inicio  " & VbCrLf & _
           "                 then convert(varchar, IsNull(a.dt_fase2,           a.dt_solicitacao),  120)  " & VbCrLf & _
           "                 else convert(varchar, IsNull(h.dt_inicio,          a.dt_solicitacao),  120)  " & VbCrLf & _
           "            end  " & VbCrLf & _
           "       end dt_despacho_cad,  " & VbCrLf & _
           "       replace(substring(a.obs_fase3,1,500), 'Cadastramento direto de atividade', 'Executar demanda') despacho_cadastramento,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(h.dt_fim   ,a.dt_solicitacao),  120)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(h.dt_fim   ,a.dt_solicitacao),  120)  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end dt_despacho_exec,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then h.observacao -- concluída  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else                           h.observacao -- concluída  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end despacho_execucao,  " & VbCrLf & _
           "       case when dt_exclusao is null               then null -- não excluída " & VbCrLf & _
           "       else                                             convert(varchar, dt_exclusao,  120) " & VbCrLf & _
           "       end dt_exclusao " & VbCrLf & _
           "  from tbl_solicitacoes a  " & VbCrLf & _
           "          inner      join tbl_clientes        b on (a.cd_cliente      = b.cd_cliente)  " & VbCrLf & _
           "          inner      join tbl_categorias      c on (a.cd_categoria    = c.cd_categoria)  " & VbCrLf & _
           "          inner      join tbl_grupos          d on (a.cd_grupo        = d.cd_grupo)  " & VbCrLf & _
           "          inner      join tbl_usuarios        e on (a.cd_resp_fase5   = e.cd_usuario)  " & VbCrLf & _
           "          left outer join tbl_departamentos   f on (a.cd_dep_dest     = f.cd_departamento)  " & VbCrLf & _
           "          left outer join   " & VbCrLf & _
           "               (select cd_solicitacao, sum(round(cast(DATEDIFF(minute, dt_inicio, dt_fim) as numeric)/60,2)) valor  " & VbCrLf & _
           "                  from tbl_desenvolvimento  " & VbCrLf & _
           "                group by cd_solicitacao)      g on (a.cd_solicitacao  = g.cd_solicitacao)  " & VbCrLf & _
           "          left outer join   " & VbCrLf & _
           "               (select cd_solicitacao, dt_inicio, dt_fim, obs_desenvolvimento observacao  " & VbCrLf & _
           "                  from tbl_desenvolvimento x  " & VbCrLf & _
           "                 where cd_desenvolvimento = (select max(cd_desenvolvimento)  " & VbCrLf & _
           "                                               from tbl_desenvolvimento  " & VbCrLf & _
           "                                              where cd_solicitacao = x.cd_solicitacao  " & VbCrLf & _
           "                                            )) h on (a.cd_solicitacao  = h.cd_solicitacao)  " & VbCrLf & _
           "order by a.dt_solicitacao  "

     SQL = "select a.cd_solicitacao, 753 sq_menu,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then 4 -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then 1 -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 3 -- concluída  " & VbCrLf & _
           "               else case when dt_fase7 is null     then 2 -- execução  " & VbCrLf & _
           "                         else                           3 -- concluída  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end siw_tramite,  " & VbCrLf & _
           "       case IsNull(a.cd_user_orig, a.cd_user_inseriu)  " & VbCrLf & _
           "            when 1 then 31 -- Alexandre  " & VbCrLf & _
           "            when 2 then 66 -- Iuri  " & VbCrLf & _
           "            when 3 then 62 -- Celso  " & VbCrLf & _
           "            when 4 then 70 -- Manuela  " & VbCrLf & _
           "            when 6 then 68  -- Liliane  " & VbCrLf & _
           "            when 7 then 64 -- Daniel  " & VbCrLf & _
           "            else   null  " & VbCrLf & _
           "       end solicitante,  " & VbCrLf & _
           "       case a.cd_user_inseriu  " & VbCrLf & _
           "            when 1 then 31 -- Alexandre  " & VbCrLf & _
           "            when 2 then 66 -- Iuri  " & VbCrLf & _
           "            when 3 then 62 -- Celso  " & VbCrLf & _
           "            when 4 then 70 -- Manuela  " & VbCrLf & _
           "            when 6 then 68  -- Liliane  " & VbCrLf & _
           "            when 7 then 64 -- Daniel  " & VbCrLf & _
           "            else   null  " & VbCrLf & _
           "       end cadastrador,  " & VbCrLf & _
           "       case a.cd_resp_fase5  " & VbCrLf & _
           "            when 1 then 31 -- Alexandre  " & VbCrLf & _
           "            when 2 then 66 -- Iuri  " & VbCrLf & _
           "            when 3 then 62 -- Celso  " & VbCrLf & _
           "            when 4 then 70 -- Manuela  " & VbCrLf & _
           "            when 6 then 68  -- Liliane  " & VbCrLf & _
           "            when 7 then 64 -- Daniel  " & VbCrLf & _
           "            else   null  " & VbCrLf & _
           "       end executor,  " & VbCrLf & _
           "       null descricao, null justificativa,  " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103) inicio,   " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103) fim,  " & VbCrLf & _
           "       convert(varchar, a.dt_solicitacao,                               120) inclusao,  " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_fase2          ,a.dt_solicitacao),  120) ultima_alteracao,  " & VbCrLf & _
           "       case when a.dt_fase7 is null then   " & VbCrLf & _
           "                 case when a.cd_fase_solicitacao = 8   " & VbCrLf & _
           "                      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)  " & VbCrLf & _
           "                      else null  " & VbCrLf & _
           "                  end  " & VbCrLf & _
           "            else convert(varchar, a.dt_fase7, 103)  " & VbCrLf & _
           "       end conclusao,  " & VbCrLf & _
           "       case when IsNull(a.nr_horas_previsao,0) = 0 then 0.5  " & VbCrLf & _
           "            else a.nr_horas_previsao  " & VbCrLf & _
           "       end valor,   " & VbCrLf & _
           "       null opiniao, 3 data_hora,   " & VbCrLf & _
           "       case a.cd_dep_orig  " & VbCrLf & _
           "            when 1 then 26 -- Presidência  " & VbCrLf & _
           "            when 2 then 30 -- TI  " & VbCrLf & _
           "            when 3 then 32 -- Projetos  " & VbCrLf & _
           "            when 4 then 28 -- Administração  " & VbCrLf & _
           "            when 5 then 56 -- Atendimento  " & VbCrLf & _
           "       end sq_unidade,  " & VbCrLf & _
           "       null sq_solic_pai,   " & VbCrLf & _
           "       case a.cd_cliente   " & VbCrLf & _
           "            when 1 then 4 -- CTIS Integrare Web  " & VbCrLf & _
           "            when 2 then case a.cd_grupo   " & VbCrLf & _
           "                             when 2 then 8   -- Unesco SIW  " & VbCrLf & _
           "                             when 3 then 122 -- Unesco SICOF  " & VbCrLf & _
           "                        end   " & VbCrLf & _
           "            when 5 then case a.cd_grupo   " & VbCrLf & _
           "                             when 6 then 7  -- Maxsoft Follow-Up  " & VbCrLf & _
           "                             when 9 then 21 -- Maxsoft SBQ  " & VbCrLf & _
           "                        end   " & VbCrLf & _
           "            when 10 then 123 -- SBPI Pesquisa e desenvolvimento  " & VbCrLf & _
           "       end sq_cc,  " & VbCrLf & _
           "       c.ds_categoria+'; '+a.nr_solicitacao palavra_chave,  " & VbCrLf & _
           "       1724 sq_cidade_origem,  " & VbCrLf & _
           "       case IsNull(a.cd_dep_dest, a.cd_dep_orig)  " & VbCrLf & _
           "            when 1 then 26 -- Presidência  " & VbCrLf & _
           "            when 2 then 30 -- TI  " & VbCrLf & _
           "            when 3 then 32 -- Projetos  " & VbCrLf & _
           "            when 4 then 28 -- Administração  " & VbCrLf & _
           "            when 5 then 56 -- Atendimento  " & VbCrLf & _
           "       end sq_unidade_resp,  " & VbCrLf & _
           "       a.ds_solicitacao assunto,  " & VbCrLf & _
           "       IsNull(a.cd_prioridade,3)-1 prioridade,  " & VbCrLf & _
           "       'N' aviso_prox_conc, 0 dias_aviso,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103)  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end inicio_real,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_fase2),  103)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_fase2),  103)  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end fim_real,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then 'S'  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then 'N'  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 'S'  " & VbCrLf & _
           "               else case when dt_fase7 is null     then 'N'  " & VbCrLf & _
           "                         else                           'S'  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end concluida,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null  " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end data_conclusao,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 'Demanda atendida'  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null  " & VbCrLf & _
           "                         else                           'Demanda atendida'  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end nota_conclusao,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then -- concluída  " & VbCrLf & _
           "                    case when IsNull(g.valor,0) = 0  " & VbCrLf & _
           "                         Then 0.5  " & VbCrLf & _
           "                         Else g.valor  " & VbCrLf & _
           "                    end  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else case when IsNull(g.valor,0) = 0  " & VbCrLf & _
           "                                   Then 0.5  " & VbCrLf & _
           "                                   Else g.valor  " & VbCrLf & _
           "                               end  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end custo_real,  " & VbCrLf & _
           "       b.nome proponente,  " & VbCrLf & _
           "       case when h.dt_inicio is null then  " & VbCrLf & _
           "            case when a.dt_fase2 > a.dt_previsao_inicio  " & VbCrLf & _
           "                 then convert(varchar, IsNull(a.dt_fase2,           a.dt_solicitacao),  120)  " & VbCrLf & _
           "                 else convert(varchar, IsNull(a.dt_previsao_inicio, a.dt_solicitacao),  120)  " & VbCrLf & _
           "            end  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "            case when a.dt_fase2 < h.dt_inicio  " & VbCrLf & _
           "                 then convert(varchar, IsNull(a.dt_fase2,           a.dt_solicitacao),  120)  " & VbCrLf & _
           "                 else convert(varchar, IsNull(h.dt_inicio,          a.dt_solicitacao),  120)  " & VbCrLf & _
           "            end  " & VbCrLf & _
           "       end dt_despacho_cad,  " & VbCrLf & _
           "       replace(substring(a.obs_fase3,1,500), 'Cadastramento direto de atividade', 'Executar demanda') despacho_cadastramento,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(h.dt_fim   ,a.dt_solicitacao),  120)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(h.dt_fim   ,a.dt_solicitacao),  120)  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end dt_despacho_exec,  " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada  " & VbCrLf & _
           "       else  " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento  " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then h.observacao -- concluída  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução  " & VbCrLf & _
           "                         else                           h.observacao -- concluída  " & VbCrLf & _
           "                     end  " & VbCrLf & _
           "          end  " & VbCrLf & _
           "       end despacho_execucao,  " & VbCrLf & _
           "       case when dt_exclusao is null               then null -- não excluída " & VbCrLf & _
           "       else                                             convert(varchar, dt_exclusao,  120) " & VbCrLf & _
           "       end dt_exclusao " & VbCrLf & _
           "  from tbl_solicitacoes a  " & VbCrLf & _
           "          inner      join tbl_clientes        b on (a.cd_cliente      = b.cd_cliente)  " & VbCrLf & _
           "          inner      join tbl_categorias      c on (a.cd_categoria    = c.cd_categoria)  " & VbCrLf & _
           "          inner      join tbl_grupos          d on (a.cd_grupo        = d.cd_grupo)  " & VbCrLf & _
           "          inner      join tbl_usuarios        e on (a.cd_resp_fase5   = e.cd_usuario)  " & VbCrLf & _
           "          left outer join tbl_departamentos   f on (a.cd_dep_dest     = f.cd_departamento)  " & VbCrLf & _
           "          left outer join   " & VbCrLf & _
           "               (select cd_solicitacao, sum(round(cast(DATEDIFF(minute, dt_inicio, dt_fim) as numeric)/60,2)) valor  " & VbCrLf & _
           "                  from tbl_desenvolvimento  " & VbCrLf & _
           "                group by cd_solicitacao)      g on (a.cd_solicitacao  = g.cd_solicitacao)  " & VbCrLf & _
           "          left outer join   " & VbCrLf & _
           "               (select cd_solicitacao, dt_inicio, dt_fim, obs_desenvolvimento observacao  " & VbCrLf & _
           "                  from tbl_desenvolvimento x  " & VbCrLf & _
           "                 where cd_desenvolvimento = (select max(cd_desenvolvimento)  " & VbCrLf & _
           "                                               from tbl_desenvolvimento  " & VbCrLf & _
           "                                              where cd_solicitacao = x.cd_solicitacao  " & VbCrLf & _
           "                                            )) h on (a.cd_solicitacao  = h.cd_solicitacao)  " & VbCrLf & _
           "order by a.dt_solicitacao  "

     SQL = "select a.nr_solicitacao, a.cd_solicitacao, 753 sq_menu,    " & VbCrLf & _
           "       case when dt_exclusao is not null           then 4 -- cancelada    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then 1 -- cadastramento    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 3 -- concluída    " & VbCrLf & _
           "               else case when dt_fase7 is null     then 2 -- execução    " & VbCrLf & _
           "                         else                           3 -- concluída    " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end siw_tramite,    " & VbCrLf & _
           "       case IsNull(a.cd_user_orig, a.cd_user_inseriu)  when 8 then 64 else   31 end solicitante,    " & VbCrLf & _
           "       case a.cd_user_inseriu  when 8 then 64 else  31 end cadastrador,    " & VbCrLf & _
           "       case when a.cd_resp_fase5 is null" & VbCrLf & _
           "            then case a.cd_resp_fase3 when 8 then 64 else  31 end " & VbCrLf & _
           "            else case a.cd_resp_fase5 when 8 then 64 else  31 end " & VbCrLf & _
           "       end executor, " & VbCrLf & _
           "       null descricao, null justificativa,    " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103) inicio,     " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103) fim,  " & VbCrLf & _
           "       convert(varchar, IsNull(a.dt_fase2          ,a.dt_solicitacao),  120) ultima_alteracao,    " & VbCrLf & _
           "       case when a.dt_fase7 is null then     " & VbCrLf & _
           "                 case when a.cd_fase_solicitacao = 8     " & VbCrLf & _
           "                      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)    " & VbCrLf & _
           "                      else null    " & VbCrLf & _
           "                  end    " & VbCrLf & _
           "            else convert(varchar, a.dt_fase7, 103)    " & VbCrLf & _
           "       end conclusao,    " & VbCrLf & _
           "       case when IsNull(a.nr_horas_previsao,0) = 0 then 0.5    " & VbCrLf & _
           "            else a.nr_horas_previsao    " & VbCrLf & _
           "       end valor,     " & VbCrLf & _
           "       null opiniao, 3 data_hora,     " & VbCrLf & _
           "       case a.cd_user_inseriu  when 8 then 34 else  26 end sq_unidade,    " & VbCrLf & _
           "       null sq_solic_pai,     " & VbCrLf & _
           "       case when a.cd_grupo = 36                      then 9 -- Currículo  " & VbCrLf & _
           "            when a.cd_grupo in (10,30,31,32,33,34,35) then 8 -- SIW  " & VbCrLf & _
           "            else                                      122 -- SICOF  " & VbCrLf & _
           "       end sq_cc,    " & VbCrLf & _
           "       c.ds_categoria+'; '+a.nr_solicitacao palavra_chave,    " & VbCrLf & _
           "       1724 sq_cidade_origem,    " & VbCrLf & _
           "       case a.cd_resp_fase5    when 8 then 34 else  26 end sq_unidade_resp,    " & VbCrLf & _
           "       a.ds_solicitacao assunto,    " & VbCrLf & _
           "       2 prioridade,    " & VbCrLf & _
           "       'N' aviso_prox_conc, 0 dias_aviso,  " & VbCrLf & _  
           "       case when dt_exclusao is not null           then null -- cancelada    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103)    " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução    " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_inicio,a.dt_solicitacao),  103)    " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end inicio_real,    " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_fase2),  103)    " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução    " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_fase2),  103)    " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end fim_real,    " & VbCrLf & _
           "       case when dt_exclusao is not null           then 'S'    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then 'N'    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 'S'    " & VbCrLf & _
           "               else case when dt_fase7 is null     then 'N'    " & VbCrLf & _
           "                         else                           'S'    " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end concluida,    " & VbCrLf & _
           "       case when dt_exclusao is not null           then null  " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)    " & VbCrLf & _
           "               else case when dt_fase7 is null     then null    " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(a.dt_previsao_fim   ,a.dt_solicitacao),  103)    " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end data_conclusao,    " & VbCrLf & _
           "       case when dt_exclusao is not null           then null    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then 'Demanda atendida'    " & VbCrLf & _
           "               else case when dt_fase7 is null     then null    " & VbCrLf & _
           "                         else                           'Demanda atendida'    " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end nota_conclusao,    " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then -- concluída    " & VbCrLf & _
           "                    case when IsNull(g.valor,0) = 0    " & VbCrLf & _
           "                         Then 0.5    " & VbCrLf & _
           "                         Else g.valor    " & VbCrLf & _
           "                    end    " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução    " & VbCrLf & _
           "                         else case when IsNull(g.valor,0) = 0    " & VbCrLf & _
           "                                   Then 0.5    " & VbCrLf & _
           "                                   Else g.valor    " & VbCrLf & _
           "                               end    " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end custo_real,    " & VbCrLf & _
           "       i.abrev_nome proponente,    " & VbCrLf & _
           "       case when a.dt_solicitacao <= a.dt_previsao_inicio and a.dt_previsao_inicio <= a.dt_fase2  " & VbCrLf & _
           "            then convert(varchar, a.dt_solicitacao, 120)  " & VbCrLf & _
           "            else case when a.dt_solicitacao <= a.dt_fase2 and a.dt_previsao_inicio >= a.dt_fase2  " & VbCrLf & _
           "                      then convert(varchar, a.dt_solicitacao, 120)  " & VbCrLf & _
           "                      else case when a.dt_solicitacao > a.dt_solicitacao and a.dt_solicitacao >= a.dt_fase2  " & VbCrLf & _
           "                                then convert(varchar, a.dt_fase2, 120)  " & VbCrLf & _
           "                                else convert(varchar, a.dt_solicitacao, 120)  " & VbCrLf & _
           "                           end  " & VbCrLf & _
           "                 end  " & VbCrLf & _
           "       end inclusao,  " & VbCrLf & _
           "       case when h.dt_inicio is null then    " & VbCrLf & _
           "            case when a.dt_fase2 > a.dt_previsao_inicio    " & VbCrLf & _
           "                 then convert(varchar, IsNull(a.dt_fase2,           a.dt_solicitacao),  120)    " & VbCrLf & _
           "                 else convert(varchar, IsNull(a.dt_previsao_inicio, a.dt_solicitacao),  120)    " & VbCrLf & _
           "            end    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "            case when a.dt_fase2 < h.dt_inicio    " & VbCrLf & _
           "                 then convert(varchar, IsNull(a.dt_fase2,           a.dt_solicitacao),  120)    " & VbCrLf & _
           "                 else convert(varchar, IsNull(h.dt_inicio,          a.dt_solicitacao),  120)    " & VbCrLf & _
           "            end    " & VbCrLf & _
           "       end dt_despacho_cad,    " & VbCrLf & _
           "       replace(substring(a.obs_fase3,1,500), 'Cadastramento direto de atividade', 'Executar demanda') despacho_cadastramento,    " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then convert(varchar, IsNull(h.dt_fim   ,a.dt_solicitacao),  120)  " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução    " & VbCrLf & _
           "                         else                           convert(varchar, IsNull(h.dt_fim   ,a.dt_solicitacao),  120)  " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end dt_despacho_exec,    " & VbCrLf & _
           "       case when dt_exclusao is not null           then null -- cancelada    " & VbCrLf & _
           "       else    " & VbCrLf & _
           "          case when a.cd_fase_solicitacao in (1,2) then null -- cadastramento    " & VbCrLf & _
           "               when a.cd_fase_solicitacao = 8      then h.observacao -- concluída    " & VbCrLf & _
           "               else case when dt_fase7 is null     then null -- execução    " & VbCrLf & _
           "                         else                           h.observacao -- concluída    " & VbCrLf & _
           "                     end    " & VbCrLf & _
           "          end    " & VbCrLf & _
           "       end despacho_execucao,  " & VbCrLf & _
           "       case when dt_exclusao is null               then null -- não excluída  " & VbCrLf & _
           "       else                                             convert(varchar, dt_exclusao,  120)  " & VbCrLf & _
           "       end dt_exclusao  " & VbCrLf & _
           "  from tbl_solicitacoes a    " & VbCrLf & _
           "          inner      join tbl_categorias      c on (a.cd_categoria    = c.cd_categoria)    " & VbCrLf & _
           "          inner      join tbl_grupos          d on (a.cd_grupo        = d.cd_grupo)    " & VbCrLf & _
           "          left outer join tbl_departamentos   f on (a.cd_dep_dest     = f.cd_departamento)    " & VbCrLf & _
           "          left outer join     " & VbCrLf & _
           "               (select cd_solicitacao, sum(round(cast(DATEDIFF(minute, dt_inicio, dt_fim) as numeric)/60,2)) valor    " & VbCrLf & _
           "                  from tbl_desenvolvimento    " & VbCrLf & _
           "                group by cd_solicitacao)      g on (a.cd_solicitacao  = g.cd_solicitacao)    " & VbCrLf & _
           "          left outer join     " & VbCrLf & _
           "               (select cd_solicitacao, dt_inicio, dt_fim, obs_desenvolvimento observacao    " & VbCrLf & _
           "                  from tbl_desenvolvimento x    " & VbCrLf & _
           "                 where cd_desenvolvimento = (select max(cd_desenvolvimento)    " & VbCrLf & _
           "                                               from tbl_desenvolvimento    " & VbCrLf & _
           "                                              where cd_solicitacao = x.cd_solicitacao    " & VbCrLf & _
           "                                            )) h on (a.cd_solicitacao  = h.cd_solicitacao)  " & VbCrLf & _
           "          inner      join tbl_usuarios        i on (a.cd_user_orig  = i.cd_usuario)    " & VbCrLf & _
           " where (cd_user_fase2 in (8,29) or  " & VbCrLf & _
           "        cd_resp_fase3 in (8,29) or  " & VbCrLf & _
           "        cd_resp_fase4 in (8,29) or  " & VbCrLf & _
           "        cd_resp_fase5 in (8,29)  " & VbCrLf & _
           "      )  " & VbCrLf & _
           "order by a.dt_previsao_inicio  " & VbCrLf
     Set RS1 = Server.CreateObject("ADODB.Recordset")
     RS1.Open SQL, strconn3, adOpenStatic
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>"
  ScriptOpen "JavaScript"
  ScriptClose
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "I" Then
    ShowHTML "<tr><td><font size=""2""><a accesskey=""M"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>M</u>igrar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS1.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nr.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Pedido</font></td>"
    ShowHTML "          <td><font size=""1""><b>Palavra-chave</font></td>"
    ShowHTML "          <td><font size=""1""><b>Início</font></td>"
    ShowHTML "          <td><font size=""1""><b>Término</font></td>"
    ShowHTML "          <td><font size=""1""><b>Concluída</font></td>"
    ShowHTML "        </tr>"
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS1.EOF
        ' Exibe apenas as demandas ainda não migradas
        SQL = "select count(*) existe from siw_solicitacao where palavra_chave like '%" & trim(RS1("nr_solicitacao")) & "%'" & VbCrLf
        Set RS3 = Server.CreateObject("ADODB.Recordset")
        RS3.Open SQL, strconn, adOpenStatic
        
        If cDbl(RS3("existe")) = 0 Then
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ title=""" & replace(RS1("assunto"),"""","'") & """>"
            ShowHTML "        <td align=""center""><font size=""1"">" & RS1("cd_solicitacao") & "</td>"
            ShowHTML "        <td align=""center""><font size=""1"" >" & FormataDataEdicao(RS1("inclusao")) & "&nbsp;</td>"
            ShowHTML "        <td><font size=""1"">" & RS1("palavra_chave") & "</td>"
            ShowHTML "        <td align=""center""><font size=""1"" >" & FormataDataEdicao(RS1("inicio")) & "&nbsp;</td>"
            ShowHTML "        <td align=""center""><font size=""1"" >" & FormataDataEdicao(RS1("fim")) & "&nbsp;</td>"
            ShowHTML "        <td align=""center""><font size=""1"" >" & FormataDataEdicao(RS1("concluida")) & "&nbsp;</td>"
            ShowHTML "      </tr>"
            If O = "I" Then
               Dim w_chave, w_chave1
               SQL = "select sq_siw_solicitacao.nextval w_chave from dual " & VbCrLf
               ConectaBD SQL
               w_chave = RS("w_chave")
               
               ' Grava dados da solicitação
               SQL = "insert into siw_solicitacao (" & VbCrLf & _
                     "     sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, " & VbCrLf & _
                     "     cadastrador,        executor,      descricao,           justificativa, " & VbCrLf & _
                     "     inicio,             fim,           inclusao,            ultima_alteracao, " & VbCrLf & _
                     "     conclusao,          valor,         opiniao,             data_hora, " & VbCrLf & _
                     "     sq_unidade,         sq_cc,         sq_cidade_origem,    palavra_chave)" & VbCrLf & _
                     "values (" & VbCrLf & _
                     "     " & w_Chave & ", " & VbCrLf & _
                     "     " & RS1("sq_menu") & ", " & VbCrLf & _
                     "     " & RS1("siw_tramite") & ", " & VbCrLf & _
                     "     " & RS1("solicitante") & ", " & VbCrLf & _
                     "     " & RS1("cadastrador") & ", " & VbCrLf & _
                     "     " & Nvl(RS1("executor"),RS1("solicitante")) & ", " & VbCrLf & _
                     "     null, " & VbCrLf & _
                     "     null, " & VbCrLf & _
                     "     to_date('" & RS1("inicio") & "', 'dd/mm/yyyy'), " & VbCrLf & _
                     "     to_date('" & RS1("fim") & "', 'dd/mm/yyyy'), " & VbCrLf & _
                     "     to_date('" & RS1("inclusao") & "', 'yyyy-mm-dd hh24:mi:ss'), " & VbCrLf & _
                     "     to_date('" & RS1("ultima_alteracao") & "', 'yyyy-mm-dd hh24:mi:ss'), " & VbCrLf & _
                     "     to_date('" & RS1("conclusao") & "', 'dd/mm/yyyy'), " & VbCrLf & _
                     "     replace('" & RS1("valor") & "',',','.'), " & VbCrLf & _
                     "     null, " & VbCrLf & _
                     "     " & RS1("data_hora") & ", " & VbCrLf & _
                     "     " & RS1("sq_unidade") & ", " & VbCrLf
               If RS1("sq_cc") > "" Then SQL = SQL & "     " & RS1("sq_cc") & ", " & VbCrLf Else SQL = SQL & "     null, " & VbCrLf End If
               SQL = SQL & _
                     "     " & RS1("sq_cidade_origem") & ", " & VbCrLf & _
                     "     '" & RS1("palavra_chave") & "') " & VbCrLf
               ExecutaSQL (SQL)

               ' Grava dados da demanda
               SQL = "insert into gd_demanda (" & VbCrLf & _
                     "     sq_siw_solicitacao,  sq_unidade_resp, assunto,           prioridade, " & VbCrLf & _
                     "     aviso_prox_conc,     concluida,       dias_aviso,        proponente, " & VbCrLf & _
                     "     inicio_real,         fim_real,        data_conclusao,    nota_conclusao, " & VbCrLf & _
                     "     custo_real)" & VbCrLf & _
                     "values (" & VbCrLf & _
                     "     " & w_Chave & ", " & VbCrLf & _
                     "     " & RS1("sq_unidade_resp") & ", " & VbCrLf & _
                     "     '" & Mid(RS1("assunto"),1,2000) & "', " & VbCrLf & _
                     "     " & RS1("prioridade") & ", " & VbCrLf & _
                     "     '" & RS1("aviso_prox_conc") & "', " & VbCrLf & _
                     "     '" & RS1("concluida") & "', " & VbCrLf & _
                     "     " & RS1("dias_aviso") & ", " & VbCrLf & _
                     "     '" & RS1("proponente") & "', " & VbCrLf
               If RS1("inicio_real") > ""       Then SQL = SQL & "     to_date('" & RS1("inicio_real") & "', 'dd/mm/yyyy'), "       & VbCrLf Else SQL = SQL & "     null," & VbCrLf End If
               If RS1("fim_real") > ""          Then SQL = SQL & "     to_date('" & RS1("fim_real") & "', 'dd/mm/yyyy'), "          & VbCrLf Else SQL = SQL & "     null," & VbCrLf End If
               If RS1("data_conclusao") > ""    Then SQL = SQL & "     to_date('" & RS1("data_conclusao") & "', 'dd/mm/yyyy'), "    & VbCrLf Else SQL = SQL & "     null," & VbCrLf End If
               If RS1("nota_conclusao") > ""    Then SQL = SQL & "     '" & RS1("nota_conclusao") & "', "                           & VbCrLf Else SQL = SQL & "     null," & VbCrLf End If
               If RS1("custo_real") > ""        Then SQL = SQL & "     replace('" & RS1("custo_real") & "',',','.')) "              & VbCrLf Else SQL = SQL & "     0)" & VbCrLf End If
               ExecutaSQL (SQL)


               Dim w_inclusao
               If cDate(RS1("inclusao")) < cDate(RS1("dt_despacho_cad")) then
                  w_inclusao = RS1("inclusao")
               Else
                  w_inclusao = RS1("dt_despacho_cad")
               End If
               
               ' Grava log de cadastramento
               SQL = "Insert Into siw_solic_log " & VbCrLf & _
                     "   (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, " & VbCrLf & _
                     "    sq_siw_tramite,            data,               devolucao, " & VbCrLf & _
                     "    observacao " & VbCrLf & _
                     "   ) " & VbCrLf & _
                     "   (select sq_siw_solic_log.nextval, " & VbCrLf & _
                     "           " & w_chave & ", " & VbCrLf & _
                     "           " & RS1("cadastrador") & ", " & VbCrLf & _
                     "           a.sq_siw_tramite, " & VbCrLf & _
                     "           to_date('" & w_inclusao & "', 'yyyy-mm-dd hh24:mi:ss'), " & VbCrLf & _
                     "           'N', " & VbCrLf & _
                     "           'Cadastramento inicial' " & VbCrLf & _
                     "      from siw_tramite a " & VbCrLf & _
                     "     where a.sq_menu = " & RS1("sq_menu") & " " & VbCrLf & _
                     "       and a.sigla   = 'CI' " & VbCrLf & _
                     "   ) " & VbCrLf
               ExecutaSQL (SQL)

               ' Coloca Alexandre como interessado na demanda
               If Nvl(RS1("cadastrador"),"0") <> 31 and Nvl(RS1("solicitante"),"0") <> 31 and Nvl(RS1("executor"),"0") <> 31 Then
                  SQL = "Insert Into gd_demanda_interes " & VbCrLf & _
                        "       (sq_pessoa, sq_siw_solicitacao, tipo_visao, envia_email) " & VbCrLf & _
                        "Values (       31, " & w_chave & ", 0, 'S') " & VbCrLf
                  ExecutaSQL (SQL)
               End If
               
               ' Coloca Liliane como interessada na demanda
               If Nvl(RS1("cadastrador"),"0") <> 68 and Nvl(RS1("solicitante"),"0") <> 68 and Nvl(RS1("executor"),"0") <> 68 Then
                  SQL = "Insert Into gd_demanda_interes " & VbCrLf & _
                        "       (sq_pessoa, sq_siw_solicitacao, tipo_visao, envia_email) " & VbCrLf & _
                        "Values (       68, " & w_chave & ", 0, 'S') " & VbCrLf
                  ExecutaSQL (SQL)
               End If

               ' Se foi para execução, grava dados do encaminhamento
               If Nvl(RS1("despacho_cadastramento"),"") > "" Then
                  SQL = "select sq_siw_solic_log.nextval w_chave1 from dual " & VbCrLf
                  ConectaBD SQL
                  w_chave1 = RS("w_chave1")

                  SQL = "Insert Into siw_solic_log " & VbCrLf & _
                        "   (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, " & VbCrLf & _
                        "    sq_siw_tramite,            data,               devolucao, " & VbCrLf & _
                        "    observacao " & VbCrLf & _
                        "   ) " & VbCrLf & _
                        "   (select " & w_chave1 &  ", " & VbCrLf & _
                        "           " & w_chave & ", " & VbCrLf & _
                        "           " & RS1("cadastrador") & ", " & VbCrLf & _
                        "           a.sq_siw_tramite, " & VbCrLf & _
                        "           to_date('" & RS1("dt_despacho_cad") & "', 'yyyy-mm-dd hh24:mi:ss'), " & VbCrLf & _
                        "           'N', " & VbCrLf & _
                        "           'Envio da fase ""Cadastramento"" para a fase ""Em execução""' " & VbCrLf & _
                        "      from siw_tramite a " & VbCrLf & _
                        "     where a.sq_menu = " & RS1("sq_menu") & " " & VbCrLf & _
                        "       and a.sigla   = 'CI' " & VbCrLf & _
                        "   ) " & VbCrLf
                  ExecutaSQL (SQL)

                  SQL = "Insert Into gd_demanda_log " & VbCrLf & _
                        "      (sq_demanda_log,            sq_siw_solicitacao, cadastrador,  " & VbCrLf & _
                        "       destinatario,              data_inclusao,      observacao,  " & VbCrLf & _
                        "       despacho,                  sq_siw_solic_log " & VbCrLf & _
                        "      ) " & VbCrLf & _
                        "   (select sq_demanda_log.nextval, " & VbCrLf & _
                        "           " & w_chave & ", " & VbCrLf & _
                        "           " & RS1("cadastrador") & ", " & VbCrLf & _
                        "           " & Nvl(RS1("executor"),RS1("solicitante")) & ", " & VbCrLf & _
                        "           to_date('" & RS1("dt_despacho_cad") & "', 'yyyy-mm-dd hh24:mi:ss'), " & VbCrLf & _
                        "           null, " & VbCrLf & _
                        "           '" & RS1("despacho_cadastramento") & "', " & VbCrLf & _
                        "           " & w_chave1 & " " & VbCrLf & _
                        "      from dual " & VbCrLf & _
                        "   ) " & VbCrLf
                  ExecutaSQL (SQL)
               End If

               ' Se foi para execução, grava dados do encaminhamento
               If Nvl(RS1("despacho_execucao"),"") > "" Then
                  SQL = "select sq_siw_solic_log.nextval w_chave1 from dual " & VbCrLf
                  ConectaBD SQL
                  w_chave1 = RS("w_chave1")

                  SQL = "Insert Into siw_solic_log " & VbCrLf & _
                        "   (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, " & VbCrLf & _
                        "    sq_siw_tramite,            data,               devolucao, " & VbCrLf & _
                        "    observacao " & VbCrLf & _
                        "   ) " & VbCrLf & _
                        "   (select " & w_chave1 &  ", " & VbCrLf & _
                        "           " & w_chave & ", " & VbCrLf & _
                        "           " & Nvl(RS1("executor"),RS1("solicitante")) & ", " & VbCrLf & _
                        "           a.sq_siw_tramite, " & VbCrLf & _
                        "           to_date('" & RS1("dt_despacho_exec") & "', 'yyyy-mm-dd hh24:mi:ss'), " & VbCrLf & _
                        "           'N', " & VbCrLf & _
                        "           'Conclusão da demanda' " & VbCrLf & _
                        "      from siw_tramite a " & VbCrLf & _
                        "     where a.sq_menu = " & RS1("sq_menu") & " " & VbCrLf & _
                        "       and a.ordem   = 2 " & VbCrLf & _
                        "   ) " & VbCrLf
                  ExecutaSQL (SQL)
               End If

               ' Se existirem logs de desenvolvimento, grava-os na base
               SQL = "select a.cd_solicitacao,  " & VbCrLf & _
                     "       case a.cd_usuario " & VbCrLf & _
                     "            when 1 then 31 -- Alexandre " & VbCrLf & _  
                     "            when 2 then 66 -- Iuri   " & VbCrLf & _
                     "            when 3 then 62 -- Celso   " & VbCrLf & _
                     "            when 4 then 70 -- Manuela   " & VbCrLf & _
                     "            when 6 then 68  -- Liliane   " & VbCrLf & _
                     "            when 7 then 64 -- Daniel   " & VbCrLf & _
                     "            else   null   " & VbCrLf & _
                     "       end cadastrador,  " & VbCrLf & _
                     "       convert(varchar, a.dt_fim,  120) data_inclusao, " & VbCrLf & _
                     "       replace(substring(a.obs_desenvolvimento,1,1950), 'Cadastramento direto de OS','Cumprimento da demanda') + char(13) + char(10) +  " & VbCrLf & _
                     "          'De ' + convert(varchar, a.dt_inicio, 103) + ', ' + convert(varchar, a.dt_inicio, 108) +  " & VbCrLf & _
                     "          ' a ' + convert(varchar, a.dt_fim, 103) + ', ' + convert(varchar, a.dt_fim, 108) " & VbCrLf & _
                     "       observacao " & VbCrLf & _
                     "  from tbl_desenvolvimento a " & VbCrLf & _
                     " where a.cd_solicitacao = " & RS1("cd_solicitacao") & " " & VbCrLf & _
                     "order by cd_solicitacao, dt_fim " & VbCrLf
               Set RS2 = Server.CreateObject("ADODB.Recordset")
               RS2.Open SQL, strconn3, adOpenStatic
               While not RS2.EOF
                  SQL = "Insert Into gd_demanda_log " & VbCrLf & _
                        "      (sq_demanda_log, sq_siw_solicitacao, cadastrador,  data_inclusao, observacao)  " & VbCrLf & _
                        "   (select sq_demanda_log.nextval, " & VbCrLf & _
                        "           " & w_chave & ", " & VbCrLf & _
                        "           " & Nvl(Nvl(RS2("cadastrador"), RS1("executor")), RS1("cadastrador")) & ", " & VbCrLf & _
                        "           to_date('" & RS2("data_inclusao") & "', 'yyyy-mm-dd hh24:mi:ss'), " & VbCrLf & _
                        "           '" & RS2("observacao") & "' " & VbCrLf & _
                        "      from dual " & VbCrLf & _
                        "   ) " & VbCrLf
                  ExecutaSQL (SQL)
                  RS2.MoveNext
               Wend

               ' Se foi excluída, grava dados da exclusão
               If Nvl(RS1("dt_exclusao"),"") > "" Then
                  SQL = "select sq_siw_solic_log.nextval w_chave1 from dual " & VbCrLf
                  ConectaBD SQL
                  w_chave1 = RS("w_chave1")

                  SQL = "Insert Into siw_solic_log " & VbCrLf & _
                        "   (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, " & VbCrLf & _
                        "    sq_siw_tramite,            data,               devolucao, " & VbCrLf & _
                        "    observacao " & VbCrLf & _
                        "   ) " & VbCrLf & _
                        "   (select " & w_chave1 &  ", " & VbCrLf & _
                        "           " & w_chave & ", " & VbCrLf & _
                        "           " & Nvl(RS1("executor"),RS1("solicitante")) & ", " & VbCrLf & _
                        "           a.sq_siw_tramite, " & VbCrLf & _
                        "           to_date('" & RS1("dt_exclusao") & "', 'yyyy-mm-dd hh24:mi:ss'), " & VbCrLf & _
                        "           'N', " & VbCrLf & _
                        "           'Cancelamento' " & VbCrLf & _
                        "      from siw_tramite a " & VbCrLf & _
                        "     where a.sq_menu = " & RS1("sq_menu") & " " & VbCrLf & _
                        "       and a.ordem   = 2 " & VbCrLf & _
                        "   ) " & VbCrLf
                  ExecutaSQL (SQL)
               End If
            End If
        End If
        RS1.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    RS1.Close
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_recebida                = Nothing
  Set w_entrante                = Nothing
  Set w_sq_central_telefonica   = Nothing
  Set w_destino                 = Nothing
  Set w_soma                    = Nothing
  Set w_fax                     = Nothing
  Set w_imagem                  = Nothing
  Set w_sq_ligacao              = Nothing
  Set w_sq_cc                   = Nothing
  Set w_sq_acordo               = Nothing
  Set w_assunto                 = Nothing
  Set w_ativo                   = Nothing
  Set w_trabalho                = Nothing
  Set w_outra_parte_contato     = Nothing

  Set p_inicio                  = Nothing
  Set p_fim                     = Nothing
  Set p_numero                  = Nothing
  Set p_ativo                   = Nothing
  Set p_sq_cc                   = Nothing
  Set p_sq_acordo               = Nothing
  Set p_outra_parte_contato     = Nothing
  Set p_Ordena                  = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de informação de ligações
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  Select Case Par
    Case "INICIAL"
       Inicial
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

