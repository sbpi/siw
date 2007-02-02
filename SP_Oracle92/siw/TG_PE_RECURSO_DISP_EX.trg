create or replace trigger TG_PE_RECURSO_DISP_EX
  before delete on pe_recurso_disponivel  
  for each row
begin
  if :old.inicio is not null then
     -- Exclui períodos de indisponibilidade que coincidam com o período do registro
     delete pe_recurso_indisponivel a
      where sq_perecurso = :old.sq_perecurso
        and (a.inicio between :old.inicio and :old.fim or
             a.fim    between :old.inicio and :old.fim
            );
  end if;
end TG_PE_RECURSO_DISP_EX;
/
