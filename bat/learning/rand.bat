@echo off

set a=%time:~-2,1%
set max=3
set /a b=%a%/%max%
set /a c=%a% - %max%*%b%+1
echo %c%