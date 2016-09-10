@echo off
set input=%1
set output=%2
call vcvars32.bat>nul
cl /Fe%output% %input%>nul
rem pause>nul