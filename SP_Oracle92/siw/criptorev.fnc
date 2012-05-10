create or replace function CRIPTOREV(TEXTOORIGINAL in varchar2) return varchar2 is
  Result           varchar2(4000);
  w_relacionamento varchar2(4000);
  w_cont           number(10);
  w_cont1          number(10);
  w_texto          varchar2(8);
  w_resultado      varchar2(8);
  w_teste          varchar2(2);
  w_1              varchar2(1);
  w_2              varchar2(1);
  k                number(10);
  l                number(10);
  m                number(10);
begin
  w_Relacionamento := 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz-+~#*(({}[]-\/,.:;$#@!&·ÈÌ˙Û¡…Õ⁄”Á«„√Í ı’';
  Result := '';
  
  w_cont := 1;
  for k in 1 .. (length(TEXTOORIGINAL)/8) loop
      -- O teste È feito de 8 em 8 bytes
      w_texto := substr(TEXTOORIGINAL,w_cont,8);
      l := 1;
      while l <= length(w_relacionamento) loop
         -- Pega a primeira letra
         w_1 := substr(w_relacionamento, l, 1);
         m := 1;
         if criptografia(w_1) = w_texto then
            Result := Result || w_1;
            exit;
         else
           while m <= length(w_relacionamento) loop
              -- Pega a segunda letra
              w_2 := substr(w_relacionamento, m, 1);
              w_teste := w_1 || w_2;
              if criptografia(w_teste) = w_texto then
                 Result := Result || w_teste;
                 m := 99999;
                 l := 99999;
              end if;
              m := m+1;
           end loop;
         end if;
         l := l + 1;
      end loop;
      w_cont := w_cont + 8;
  end loop;
  
  return(Result);
end CRIPTOREV;
/
