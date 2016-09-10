@echo off
set sentences="%~1"
mshta vbscript:createobject("sapi.spvoice").speak(%sentences%)(window.close)