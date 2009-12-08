create or replace trigger TG_PA_DOCUMENTO_LOG_IN_UP
  before insert or update on pa_documento_log  
  for each row
declare
   w_ano          number(4);
   w_sequencial   number(18) := 0;
   w_reg          pa_parametro%rowtype;
   w_unid         pa_unidade%rowtype;
   w_unid_pai     number(18);
   w_sg_tramite   siw_menu.sigla%type;
begin
  If INSERTING and :new.nu_guia is null and :new.recebedor is null Then
     -- Recupera os dados da unidade pai
     select coalesce(sq_unidade_pai,sq_unidade) into w_unid_pai from pa_unidade where sq_unidade = :new.unidade_origem;
    
     -- Recupera os dados da unidade numeradora
     select * into w_unid from pa_unidade where sq_unidade = w_unid_pai;
    
     -- Recupera os parâmetros do cliente informado
     select * into w_reg from pa_parametro where cliente = w_unid.cliente;
    
     -- Recupera o trâmite do documento
     select sigla into w_sg_tramite from siw_solicitacao x inner join siw_tramite y on (x.sq_siw_tramite = y.sq_siw_tramite) where x.sq_siw_solicitacao = :new.sq_siw_solicitacao;
    
     -- Verifica se há necessidade de reinicializar o sequencial em função da troca do ano
     If to_char(sysdate,'yyyy') > w_reg.ano_corrente Then
        w_ano        := to_char(sysdate,'yyyy');
        w_sequencial := 1;
        
        -- Atualiza a tabela de parâmetros
        Update pa_parametro Set ano_corrente = w_ano Where cliente = w_unid.cliente;
    
        -- Atualiza a tabela de unidades
        Update pa_unidade Set numero_tramite = 0, numero_transferencia = 0, numero_eliminacao = 0 Where cliente = w_unid.cliente and sq_unidade_pai is null;
     Else
        w_ano        := w_reg.ano_corrente;
        If w_sg_tramite = 'AT' Then
           w_sequencial := coalesce(w_unid.numero_transferencia,0) + 1;
        Else
           w_sequencial := coalesce(w_unid.numero_tramite,0) + 1;
        End If;
     End If;
    
     -- Atualiza a tabela de unidades
     If w_sg_tramite = 'AT' Then
        Update pa_unidade Set numero_transferencia = w_sequencial Where sq_unidade = w_unid_pai;
     Else
        Update pa_unidade Set numero_tramite = w_sequencial Where sq_unidade = w_unid_pai;
     End If;

     :new.nu_guia  := w_sequencial;
     :new.ano_guia := w_ano;
  End If;
end TG_PA_DOCUMENTO_LOG_IN_UP;
/
