create or replace trigger TG_PE_RECURSO_UP
  before update on pe_recurso  
  for each row
begin
  if :old.disponibilidade_tipo <> :new.disponibilidade_tipo then
     -- se o tipo de disponibilidade foi alterado, remove o cronograma do recurso
     delete pe_recurso_indisponivel a where a.sq_perecurso = :old.sq_perecurso;
     delete pe_recurso_disponivel   a where a.sq_perecurso = :old.sq_perecurso;
  end if;
end TG_PE_RECURSO_UP;
/
