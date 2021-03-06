@ECHO OFF
@cls
SETLOCAL ENABLEDELAYEDEXPANSION
SET FILEPATH=E:\masm
rem %FILEPATH%
SET ISREEDIT=Y
SET ISDEBUG=Y
SET NUMERRORS=0
SET FILENAME=%1
CALL :CFILENAME
SET ERRORFILENAME=AUTOMASMERRORTEMP.TXT
IF NOT EXIST %FILENAME%.ASM COPY MODEL.TXT %FILENAME%.ASM
ECHO.>AUTOTEMP.BAT
:START
CLS
@VIM %FILENAME%.ASM
ECHO ****************************MASM %FILENAME%.ASM****************************>%ERRORFILENAME%
@TYPE 3CR|MASM %FILENAME%.ASM>MASM%ERRORFILENAME%
CALL :DELTHREE MASM%ERRORFILENAME% %ERRORFILENAME%
ECHO ****************************END MASM %FILENAME%.ASM****************************>>%ERRORFILENAME%
ECHO.>>%ERRORFILENAME%
ECHO ****************************LINK %FILENAME%.OBJ****************************>>%ERRORFILENAME%
@TYPE 3CR|LINK %FILENAME%.OBJ>LINK%ERRORFILENAME%
CALL :DELTHREE LINK%ERRORFILENAME% %ERRORFILENAME%
ECHO ****************************END LINK %FILENAME%.OBJ**************************>>%ERRORFILENAME%
@echo.
CALL :HAVEERROR
CLS
ECHO ****************************AUTOMASM %FILENAME%****************************
IF %NUMERRORS% GTR 0 (
ECHO %NUMERRORS% ERRORS^!^! 
ECHO.
SET ISREEDIT=Y
CALL :FREEDIT
) ELSE (
ECHO NO ERRORS^!^!
ECHO.
SET ISDEBUG=Y
CALL :FDEBUG
SET ISREEDIT=N
CALL :FREEDIT
)




GOTO :EXIT

REM *************************FUNCTION AREA**********************
:DELTHREE
FOR /F "skip=3 tokens=*" %%I IN (%1) DO ECHO %%I>>%2
GOTO :EOF

:HAVEERROR
SET NUMERRORS=0
TYPE %ERRORFILENAME%|FIND /I "ERRORS">TEMPERROR.TXT
FOR /F "TOKENS=1,2,3" %%I IN (TEMPERROR.TXT) DO (
IF NOT "%%I"=="0" SET /A NUMERRORS=!NUMERRORS!+%%I
)
REM ECHO NUMERRORS=%NUMERRORS%.
DEL TEMPERROR.TXT
GOTO :EOF
:FINISHTHIS
GOTO :EOF

:FDEBUG
SET ISDEBUG=Y
SET /P ISDEBUG=DO YOU WANT TO DEBUG %FILENAME%.EXE?(%ISDEBUG%)(Y/N) 
IF /I "%ISDEBUG:~0,1%"=="N" GOTO :EOF
@echo ****************************Debug %FILENAME%.EXE****************************
@DEBUG %FILENAME%.EXE
@ECHO.
@ECHO ****************************END DEBUG %FILENAME%.EXE************************
GOTO :EOF

:FREEDIT
@ECHO.
SET /P ISREEDIT=DO YOU WANT TO REEDIT %FILENAME%.ASM?(%ISREEDIT%)(Y/N)
IF /I "%ISREEDIT:~0,1%"=="Y" (
ECHO @ECHO Output:>AUTOTEMP.BAT
ECHO @TYPE %ERRORFILENAME%>>AUTOTEMP.BAT
ECHO @ECHO Press any key to exit...>>AUTOTEMP.BAT
ECHO @PAUSE^>NUL>>AUTOTEMP.BAT
ECHO @EXIT>>AUTOTEMP.BAT
START AUTOTEMP.BAT
 GOTO :START
) else (
ECHO.
ECHO ****************************END AUTOMASM %FILENAME%****************************
)
GOTO :EOF

:CFILENAME
getbeforedotCpp.exe %FILENAME%>tempcl.txt
for /f "tokens=1" %%m in (tempcl.txt) do set FILENAME=%%m
REM echo FILENAME=%FILENAME%
DEL tempcl.txt
GOTO :EOF




:EXIT

IF EXIST AUTOTEMP.BAT DEL AUTOTEMP.BAT
IF EXIST %ERRORFILENAME% DEL %ERRORFILENAME%