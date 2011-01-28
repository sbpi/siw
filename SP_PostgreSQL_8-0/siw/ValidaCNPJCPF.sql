CREATE OR REPLACE FUNCTION validacnpjcpf(character varying, character varying) RETURNS character varying AS $$
declare
   Result    varchar(2);
  igual     numeric(10)   := 0;
  allValid  boolean      := true;
  soma      numeric(10)   := 0;
  D1        numeric(2)    := 0;
  D2        numeric(2)    := 0;
  retorno   varchar(2);
  teste     numeric(20);
 checkStr  varchar(50) := translate($1,'1./-','1');
begin

  if length(checkSTR) > 18 then retorno= 'ER';
  elsif length(checkSTR) <= 11 then -- Trata CPF
      for i in 1..9 loop
          soma := soma + (substring(checkStr from i for 1)*(11-i));
          -- A crítica abaixo impede CPFs com todos os números iguais
          if substring(checkStr from i for 1) <> substring(checkStr from i-1 for 1) then igual := 1; end if;
      end loop;
      if igual = 0 and $2 is null then retorno = 'ER'; end if;
      D1 := 11-Mod(Soma,11);
      if D1 > 9 then D1 := 0; end if;
      soma := 0;
      if $2 is not null then checkStr := substring(checkStr from 1 for 10)||D1; end if;
      for i in 1..10  loop
          soma := soma + (substring (checkStr from i for 1)*(12-i));
      end loop;
      D2 := 11-Mod(Soma,11);
      if D2 > 9 then D2 := 0; end if;
      if $2 is null then
          if D1||D2 = substring(checkStr from 10 for 2)
             then Result := 'OK';
             else Result := 'ER';
          end if;
      else
          Result := D1||D2;
      end if;
  elsif length(checkSTR) <= 12 then -- Trata CNPJ
      for i in 1..12 loop
          if i < 5
             then soma := soma + (substring (checkStr from i for 1)*(6-i));
             else soma := soma + (substring (checkStr from i for 1)*(14-i));
          end if;
      end loop;
      D1 := 11-Mod(Soma,11);
      if D1 > 9 then D1 := 0; end if;
      soma := 0;
      if $2 is not null then checkStr := substring (checkStr from 1 for 12)||D1; end if;
      for i in 1..13  loop
          if i < 6
             then soma := soma + (substring (checkStr from i for 1)*(7-i));
             else soma := soma + (substring (checkStr from i for 1)*(15-i));
          end if;
      end loop;
      D2 := 11-Mod(Soma,11);
      if D2 > 9 then D2 := 0; end if;
      if $2 is null then
          if D1||D2 = substring (checkStr from 13 for 2)
             then Result := 'OK';
             else Result := 'ER';
          end if;
      else
          Result := D1||D2;
      end if;
  else -- Trata número de processo
      for i in 1..15 loop teste :=substring (checkStr from i for 1) ;

		soma := soma + teste*(17-i);
       end loop;--soma := soma + (substring (checkStr from i for 1)*(17-i)); end loop;
      D1 := 11-Mod(Soma,11);
      if D1 > 9 then D1 := D1 - 10; end if;
      soma := 0;
      if $2 is not null then checkStr := substring (checkStr from 1 for 16)||D1; end if;
      for i in 1..16  loop
          teste := substring (checkStr from i for 1);
          soma := soma + (teste *(18-i));
      end loop;
      D2 := 11-Mod(Soma,11);
      if D2 > 9 then D2 := D2 - 10; end if;
      if $2 is null then
          if D1||D2 = substring (checkStr from 16 for 2)
             then Result := 'OK';
             else Result := 'ER';
          end if;
      else
          Result := cast(D1 as varchar)||cast(D2 as varchar);
      end if;
  end if;
  return Result;
END $$ LANGUAGE 'plpgsql' VOLATILE;
