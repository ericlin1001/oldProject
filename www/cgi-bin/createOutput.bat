@echo off
set input=%1
set inputArg=%~3
set output=%2
rem -----do----
%input% %inputArg%>%output%
rem pause>nul