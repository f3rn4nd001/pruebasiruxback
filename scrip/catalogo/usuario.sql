DROP PROCEDURE IF EXISTS stpInsertarCatUsuario;
CREATE PROCEDURE `stpInsertarCatUsuario` (`uuid2ecodUsuario` VARCHAR(50), `tNombrev` VARCHAR(40), `tApellidov` VARCHAR(40), `tCRUPv` VARCHAR(25), `tRFCv` VARCHAR(30), `tSexov` VARCHAR(20),`nTelefonov` VARCHAR(20),`tNotasv` VARCHAR(250),`nEdadv` INT(8), `ecodEstatusv` VARCHAR(50), `loginEcodUsuarios` VARCHAR(50),`fhNacimientov` VARCHAR(50),`iUsuariov` longtext )

BEGIN

declare existe int;
set existe = (select count(*) from catusuarios where ecodUsuario = uuid2ecodUsuario);
if existe = 0
	then
	insert into catusuarios(`ecodUsuario`, `tNombre`, `tApellido`, `tCRUP`, `tRFC`,`nTelefono`, `nEdad`, `tSexo`, `ecodEstatus`, `fhCreacion`, `ecodCreacion`,`fhNacimiento`,`iUsuario`,`tNotas` )
	values (uuid2ecodUsuario,tNombrev,tApellidov,tCRUPv,tRFCv,nTelefonov,nEdadv,tSexov,ecodEstatusv,NOW(),loginEcodUsuarios,fhNacimientov,iUsuariov,tNotasv);
	SELECT uuid2ecodUsuario AS Codigo;
else
    UPDATE catusuarios set tNombre = tNombrev, tApellido = tApellidov, tCRUP = tCRUPv, tRFC = tRFCv, nTelefono = nTelefonov, nEdad = nEdadv, tSexo = tSexov, fhNacimiento = fhNacimientov,tNotas = tNotasv, iUsuario = iUsuariov, ecodEdicion = loginEcodUsuarios, fhEdicion = NOW(), ecodEstatus = ecodEstatusv where ecodUsuario = uuid2ecodUsuario;
    SELECT uuid2ecodUsuario AS Codigo;	
END if;
end