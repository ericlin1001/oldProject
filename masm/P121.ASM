DSEG SEGMENT
VAR1 DW "AB"
DSEG ENDS
EXTRA1 SEGMENT
VAR2 DW "AB"
EXTRA1 ENDS
CSEG SEGMENT
ASSUME CS:CSEG,DS:DSEG,ES:EXTRA1
START:
MOV AX,DSEG
MOV DS,AX
MOV AX,EXTRA1
MOV ES,AX
LEA SI,VAR1
LEA DI,VAR2
STD
CMPSW
JE EQU1
MOV BX,0FF00H
JMP EXIT
EQU1:
MOV BX,00FFH

EXIT:
MOV AH,4CH
INT 21H
CSEG ENDS
END START
