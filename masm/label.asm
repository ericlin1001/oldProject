NAME ERIC
TITLE ADD
DSEG SEGMENT
BYTE1 LABEL BYTE
VAR1 DW "BC",3 DUP("YZ")
BYTE2 EQU THIS DWORD
VAR2 DB "ABCDEFG",2 DUP(" "),2 DUP("ERIC")
DSEG ENDS
SUBTTL REIGHT 
CSEG SEGMENT
ASSUME CS:CSEG,DS:DSEG
START:
MOV AX,DSEG
MOV DS,AX
LEA AX,VAR1
LEA BX,VAR2
MOV CX,2
MOV BX,OFFSET BYTE1
ADDRESS EQU BX
ADD1 EQU INC
NEXT:
MOV DL,[ADDRESS]
MOV AH,2
INT 21H
ADD1 BX
LOOP NEXT
MOV CX,4
MOV BX,OFFSET BYTE2
NEXT1:
MOV DL,[BX]
MOV AH,2
INT 21H
DEC BX
LOOP NEXT1

EXIT:
MOV AH,4CH
INT 21H
CSEG ENDS
END START

