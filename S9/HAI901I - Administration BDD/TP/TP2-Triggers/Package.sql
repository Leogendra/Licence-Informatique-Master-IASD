create or replace package Finances
 as 
vTx_EF constant number := 6.55957; 
vTx_ED constant number := 1.3926; 

 function conversionF_EF (euros in number) return number; 
 procedure conversionP_EF (euros in number, francs out number); 
 function conversionF_ED (euros in number) return number; 
end Finances ; 
 / 


create or replace package body Finances 
as 

function conversion (montant in number, taux in number) 
return number 
is 
begin 
return (round(montant * taux, 2)); 
Exception when OTHERS then return null; 
end; 

function conversionF_ED (euros in number) 
return number is 
begin 
return conversion (euros, vTx_ED); 
end; 

function conversionF_EF (euros in number) 
return number is 
begin 
return conversion (euros, vTx_EF); 
end; 


procedure conversionP_EF (euros in number, francs out number) 
is 
begin 
francs := (round(euros * vTx_EF, 2)); 
Exception when OTHERS then dbms_output.put_line(' erreur argument '); 
end; 

end Finances; 
/ 

select salaire, Finances.conversionF_ED(salaire) as enDollars, 
Finances.conversionF_EF(salaire) as enFrancs from emp; 

select * from emp where Finances.conversionF_EF(salaire) > 10000 ; 