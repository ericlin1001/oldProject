@echo off
set task=%~1
echo task=%task%
:kill
taskkill /f /im "%task%"
if not errorlevel 0 goto :kill
echo Have kill all the task:%task%.