select 'ALTER '||OBJECT_TYPE||' '||OBJECT_NAME||' COMPILE;' from user_objects where object_type ('FUNCTION','PROCEDURE','VIEW','TRIGGER') order by object_type, last_ddl_time;

select 'alter '||object_type||' '||object_name||' compile'
  from user_objects
 where status='INVALID'
     and object_type ('FUNCTION', 'PROCEDURE', 'TRIGGER')
order by object_type;
