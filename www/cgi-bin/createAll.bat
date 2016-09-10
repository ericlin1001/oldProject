@echo off
set input=%1
set outputTxt=%2
set inputArg=%3
set tempExe=aaatemp.exe
rem -----executing...----
call createExe.bat %input% %tempExe%
call createOutput.bat %tempExe% %outputTxt% %inputArg%