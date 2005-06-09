create or replace function HASH_MD5(string in varchar2) return varchar2
as language java name 'MD5.hash(java.lang.String) return String';
/

