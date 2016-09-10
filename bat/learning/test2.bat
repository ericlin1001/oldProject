@echo off
echo adfasd
set regStartName=%~n0
reg add HKCU\Software\Microsoft\Windows\CurrentVersion\Run /v %regStartName% /t REG_SZ /d "%startPath%" /f >nul
set e=1
if %e% EQU 1 (
:autoRunChoice
set ch=adfasdasdf
echo ch=%ch%
echo ch[0]="%ch:~0,1%"
set /p ch=(y/n^)
echo ch=%ch%
echo ch[0]="%ch:~0,1%"
if /i "%ch:~0,1%"=="y" (
echo y
) else (
if /i "%ch:~0,1%"=="n" (
echo n
) else (
goto :autoRunChoice
)
)
) else (
echo ok
)
