DSEG SEGMENT
VAR1 DB 12H,34H,56H,78H
VAR2 DB 0ABH,0CDH,0EFH
SUM DD ?
DSEG ENDS

CSEG SEGMENT
ASSUME CS:CSEG,DS:DSEG
START:
MOV AX,DSEG
MOV DS,AX
MOV BX,5678H
LEA SI,SUM
MOV DWORD PTR [SI],BX

MOV AH,4CH
INT 21H
CSEG ENDS
END START
