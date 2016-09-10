@echo off
echo 0123456789>a.txt
set count=0
:start
copy a.txt +a.txt b.txt
copy b.txt +b.txt a.txt
set /a count=%count%+1
if %count% GEQ %1 goto :exit
goto :start
:exit
del b.txt /f
