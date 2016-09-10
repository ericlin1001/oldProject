@ECHO OFF
@cls
IF NOT EXIST %1.ASM COPY MODEL.TXT %1.ASM
@EDIT %1.ASM
@TYPE 3CR|MASM %1.ASM>qfmasmlog.txt
@TYPE 3CR|LINK %1.OBJ>>qfmasmlog.txt
@type qfmasmlog.txt
@echo.
SET B=Y
SET /P B=DO YOU WANT TO DEBUG %1.EXE?(Y)(Y/N) 
IF /I "%B:~0,1%"=="N" GOTO :REEDIT
@echo ****************************Debug %1.EXE****************************
@DEBUG %1.EXE
@ECHO.
@ECHO ****************************END DEBUG %1.EXE************************
:REEDIT
@ECHO.
SET A=N
SET /P A=DO YOU WANT TO REEDIT %1.ASM?(N)(Y/N)
IF "%A:~0,1%"=="Y" QFMASM %1