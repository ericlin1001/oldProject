DSEG SEGMENT

DSEG ENDS

CSEG SEGMENT
ASSUME CS:CSEG,DS:DSEG
START:
MOV AX,DSEG
MOV DS,AX
HERE: JMP HERE

EXIT:
MOV AH,4CH
INT 21H
CSEG ENDS
END START
