rem controlShutdown.bat

@echo off
@cls
rem �޸������������ʱ��͹ػ���ʱ��.(24Сʱ��,ֻ��������)
set /a startHour=12
set /a startMinu=00
set /a sleepHour=12+11
set /a sleepMinu=30

rem ���濪ʼ����,��Ҫ�Ҹ�.
rem start the program.
set regStartName=%~n0
set startPath=%~f0
call :autoRun

set /a delay = 0
set hour=%time:~0,2%
set minu=%time:~3,2%
set second=%time:~6,2%
rem test
rem added
set day=0
if "%date:~12,1%"=="��" set day=Satday
if "%date:~12,1%"=="��" set day=Sunday
if not "%day%"=="0" (
echo since today is %day%,you can play the computer all day.
goto :exit
)
rem end added
set /a tempn=%hour%*60+%minu%
set /a temps=%startHour%*60+%startMinu%
if %tempn% GEQ %temps% (
echo OK!
echo You can play the computer until sleepTime %sleepHour%:%sleepMinu%.
echo ^^_^^
set /a delay=((%sleepHour%-%hour%^)*60+%sleepMinu%-%minu%^)*60-%second%
) else (
echo Sorry,now the time is %time:~0,-6% ,and it doesn't reach starTime %startHour%:%startMinu%.
echo So,you can't play the computer now.
echo The computer is shuting down...
set delay=30
)
echo.
echo delay=%delay% (second).
shutdown -s -t %delay%
goto :exit

:autoRun
reg add HKCU\Software\Microsoft\Windows\CurrentVersion\Run /v %regStartName% /t REG_SZ /d "%startPath%" /f >nul
if ERRORLEVEL 1 (
:autoRunChoice
set /p ch=Do you want this to start with computer?(y/n^)
if /i "%ch:~0,1%"=="y" (
goto :autoRun
) else (
if /i "%ch:~0,1%"=="n" (
echo this won't start with computer.
) else (
goto :autoRunChoice
)
)

)
goto :eof

:exit
pause>nul
rem end controlShutdown.bat