DSEG SEGMENT
VAR1 DW ?
VAR2 DB 4 DUP('A')
VAR3 DD 0004H
LENTH EQU $-VAR1
DSEG ENDS

CSEG SEGMENT
ASSUME CS:CSEG,DS:DSEG
START:
MOV AX,DSEG
MOV DS,AX
MOV BX,LENTH

EXIT:
MOV AH,4CH
INT 21H
CSEG ENDS
END START
