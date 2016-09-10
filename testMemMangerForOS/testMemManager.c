/*#include<stdio.h>*/
void printf(char *str){
     }
void printInt(int a,int b){
     
     }
     void CR(){
          }
typedef long MemAddress;
typedef unsigned int uint;
typedef struct MemBlock{
#define MemBlockState_Fail 0
#define MemBlockState_Empty 1
#define MemBlockState_Used 2
	MemAddress start;
	MemAddress end;
   /* unsigned int size;*/
	/*int state;*/
}MemBlock;


typedef struct MemHole{
#define MemHoleState_Empty 0
#define MemHoleState_Partial 1   /****use for non-leaf node*/
#define MemHoleState_Full 2     /*****use for leaf node****/
	MemBlock mb;
	int logSize;
	int state;
	struct MemHole *leftChild;
	struct MemHole *rightChild;
	struct MemHole *fellow; /****its sibling node.***/
	struct MemHole *parent; /****its sibling node.***/
	struct MemHole *next;
}MemHole;
#define newMemHolePoolSize 32
MemHole newMemHolePool[newMemHolePoolSize];
MemHole *pEmptyMemHoleRoot; 

void printMemHole();
void initNewMemHolePool(){
	int i;
	for(i=0;i<newMemHolePoolSize-1;i++){
		newMemHolePool[i].next=&newMemHolePool[i+1];
	}
	newMemHolePool[newMemHolePoolSize-1].next=&newMemHolePool[0];
	pEmptyMemHoleRoot=&newMemHolePool[0];
}
MemHole *newMemHole(){
	MemHole *pmh=0;
	if(pEmptyMemHoleRoot->next!=pEmptyMemHoleRoot){/**root is not used**/
		pmh=pEmptyMemHoleRoot->next;
		pEmptyMemHoleRoot->next=pmh->next;
		pmh->next=0;
	}
	if(pmh!=0){
		pmh->leftChild=0;
		pmh->rightChild=0;
		pmh->fellow=0;
		pmh->parent=0;
	}
	return pmh;
}
void deleteMemHole(MemHole*pmh){
	if(pmh&&pmh->next==0){
		pmh->next=pEmptyMemHoleRoot->next;
		pEmptyMemHoleRoot->next=pmh;  
	}
}

void printMemHolePool(){
	MemHole *p;
	int count=0;
	for(p=pEmptyMemHoleRoot->next;p!=pEmptyMemHoleRoot;p=p->next){
		count++;
	}
	printf("available MemHolePool count:");printInt(count,10);CR();
}
/*********************/
const uint memManager_L=5;
const uint memManager_U=19;
const uint memManagerAlloMax=16; /***this should <=memManager_U***/
MemHole *rootHole;
void memManagerInit(){
	initNewMemHolePool();
	rootHole=newMemHole();/***use one holeNode**/
	rootHole->logSize=memManager_U;
	rootHole->state=MemHoleState_Empty;
	rootHole->mb.start=0x10000l;
	rootHole->mb.end=  0x8FFFFl;
}
void setupChildMemBlock(MemHole *parent){
     parent->leftChild->mb.start=parent->mb.start;
     parent->leftChild->mb.end=(parent->mb.start+parent->mb.end)/2;
     parent->rightChild->mb.start=parent->leftChild->mb.end+1;
     parent->rightChild->mb.end=parent->mb.end;
}
void printMemBlock(MemBlock *pmb){
     MemAddress addr=pmb->start;
     int offset;
      int seg;
      
    offset =(int)(addr&0xffff);
    seg =(int)(addr>>16);
     printf(" 0x%04X:%04X",seg,offset);
     
     addr=pmb->end;
      offset =(int)(addr&0xffff);
    seg =(int)(addr>>16);
    
    printf(" 0x%04X:%04X ",seg,offset);
    printf(" size:");printInt(pmb->end-pmb->start+1,10);CR();
}
     
MemHole*getMemHoleR(MemHole *from,const int logi){                     
	MemHole* temp;
	if(from->logSize ==logi){
		if(from->state==MemHoleState_Empty){
			from->state=MemHoleState_Full;
			return from;
		}else{
			return 0;
		}
	}else if(from->logSize > logi){
		if(from->leftChild){/**this is same as if(from->rightChild)*/
			/****has child****/
			temp=getMemHoleR(from->leftChild,logi);
			if(temp)return temp;
			else return getMemHoleR(from->rightChild,logi);
		}else{/****no child,this is a leaf,split this one into two node***/
			if(from->state==MemHoleState_Empty){
				from->state=MemHoleState_Partial;
				/****/
				from->leftChild=newMemHole();
				if(from->leftChild==0){
					printf("no engouth pool ");
					/***not enough talbe pool to descrip the memory***/
					return from;
				}
				from->rightChild=newMemHole();
				if(from->rightChild==0){
					printf("no engouth pool ");
					/***not enough talbe pool to descrip the memory***/
					deleteMemHole(from->leftChild);  
					from->leftChild=0;
					return from;          
				}
				/****/
				temp=from->leftChild;
				temp->logSize=from->logSize-1;
				temp->state=MemHoleState_Empty;
				temp->parent=from;
				/****/
				temp=from->rightChild;
				temp->logSize=from->logSize-1;
				temp->state=MemHoleState_Empty;
				temp->parent=from;
				/***/
				from->leftChild->fellow=from->rightChild;
				from->rightChild->fellow=from->leftChild;
				/***/
				setupChildMemBlock(from);
				return getMemHoleR(from->leftChild,logi);
			}else{
				return 0;
			}
		}
	}else{
		return 0;/***fail****/
	}
}

MemHole*getMemHole(int logi){/***return 0 as fail***/
printf("try get :%d\n",logi);
if(logi>memManagerAlloMax)return 0;
/*	if(logi>memManager_U)return 0;*/
	if(logi<memManager_L)logi=memManager_L;
	MemHole *pmh=getMemHoleR(rootHole,logi);
	if(pmh!=0){
               printf("allocate :");printInt(logi,10);printf(" success\n");
	
	}else{
          printf("allocate :");printInt(logi,10);printf(" fail\n");
          
            

	}
if(pmh)	printMemBlock(&pmh->mb);
	printMemHole  (); 
	return pmh;
}
void combineFellowHole(MemHole*pmh){/***input:ensure pmh->state ==MemHoleState_Empty***/
	if(pmh->fellow && pmh->fellow->state==MemHoleState_Empty){
		/***start combining...*/
		pmh=pmh->parent;
		deleteMemHole(pmh->leftChild);    
		pmh->leftChild=0;  
		deleteMemHole(pmh->rightChild);   
		pmh->rightChild=0;
		pmh->state=MemHoleState_Empty;
		combineFellowHole(pmh);         
	}
}
void releaseMemHole(MemHole*pmh){/****this should be a leaf node.***/
	printf("release ");
	if(pmh){
		printf("%d .\n",pmh->logSize);
		pmh->state=MemHoleState_Empty;
		combineFellowHole(pmh);
	}
	printMemHole();
}
#define Trace(m) printf(#m"=");printInt(m,10);CR();


/***************memory interface**************/
MemBlock* tryGetMemBlock(unsigned int size){
	int i=1;
	MemHole *pmh;
	while(((unsigned int)((unsigned int)1<<i))<size)i++;
	printf("try get size:");printInt(size,10);CR();
	pmh=getMemHole(i);
	if(pmh!=0){
		return &pmh->mb;
	}else{
		return 0;/***fail****/
	}
}
void releaseMemBlock(MemBlock * m){
	releaseMemHole((MemHole*)(m));
}
/****************end memory interface**************/

void printTimesChar(char ch,int t){
     int i;
	for( i=0;i<t;i++){printf("%c",ch);}
}
void visitNode(MemHole *pmh){
	/***pmh is ensured !=0***/
	if(pmh->leftChild){/***not a leaf node****/

	}else{
		/****is a leaf node***/
		int logi=pmh->logSize;
		logi-=memManager_L-2;
		int leftSpace=logi/2-1;
		char ch=' ';
		if(pmh->state==MemHoleState_Empty){
			ch=' ';
		}else if(pmh->state==MemHoleState_Partial){
			ch='.';
		}else if(pmh->state==MemHoleState_Full){
			ch='*';
		}
		printTimesChar(ch,leftSpace);
		if(pmh->logSize>9){
		}else{
              printf("%c",ch);
		}
	printf("%d",pmh->logSize);
		printTimesChar(ch,logi-leftSpace-2);
		  printf("|");

	}
}

void inorderTravser(MemHole *pmh){
	if(pmh!=0){
		if(pmh->leftChild){/***has child****/
			inorderTravser(pmh->leftChild);
			visitNode(pmh);
			inorderTravser(pmh->rightChild);
		}else{
			visitNode(pmh);
		}

	}
}
void printMemHole(){
	printf("begin\n|");
	inorderTravser(rootHole);
	printf("\nend\n");
	printMemHolePool();
	printf("\nend\n");
}

void test(){
	MemBlock *pmh[10];
	int p=0;
	printMemHole();
	pmh[p++]=  tryGetMemBlock(4);
	pmh[p++]= tryGetMemBlock(128);
	pmh[p++]=  tryGetMemBlock(256);
	pmh[p++]= tryGetMemBlock(4056);
	pmh[p++]=  tryGetMemBlock(5000);
	releaseMemBlock(pmh[0]);
	releaseMemBlock(pmh[3]);
	releaseMemBlock(pmh[2]);
	releaseMemBlock(pmh[1]);
	pmh[p++]=  tryGetMemBlock(80000);
	releaseMemBlock(pmh[4]);
	MemBlock *t;
	t=pmh[p++]= tryGetMemBlock(90000);
	releaseMemBlock(t);
}

void test1(){
	MemHole *pmh[10];
	int p=0;
	printMemHole();
	pmh[p++]=  getMemHole(2);
	pmh[p++]= getMemHole(7);
	pmh[p++]=  getMemHole(8);
	pmh[p++]= getMemHole(12);
	pmh[p++]=  getMemHole(18);
	releaseMemHole(pmh[0]);
	releaseMemHole(pmh[3]);
	releaseMemHole(pmh[2]);
	releaseMemHole(pmh[1]);
	pmh[p++]=  getMemHole(19);
	releaseMemHole(pmh[4]);
	MemHole *t;
	t=pmh[p++]=  getMemHole(19);
	releaseMemHole(t);
}
int main(){ 
	memManagerInit();
	test();
	getchar();
	return 0;
}
