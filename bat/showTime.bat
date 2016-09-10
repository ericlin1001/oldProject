
@echo off
:start
cls
@echo %time:~0,8%
ping 127.0.0.1 -n 2 >nul
goto :start