typedef long MemAddress;
typedef unsigned int uint;
typedef struct MemBlock{
#define MemBlockState_Fail 0
#define MemBlockState_Empty 1
#define MemBlockState_Used 2
	MemAddress start;
	MemAddress end;
	unsigned int size;
	int state;
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
#define newMemHolePoolSize 15
MemHole newMemHolePool[newMemHolePoolSize];
MemHole *pEmptyMemHoleRoot; 
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

/*********************/
const uint memManager_L=5;
const uint memManager_U=19;

MemHole *rootHole;
void memManagerInit(){
	initNewMemHolePool();
	rootHole=newMemHole();/***use one holeNode**/
	rootHole->logSize=memManager_U;
	rootHole->state=MemHoleState_Empty;
}
void setupChildMemBlock(MemHole *parent){

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
					/***not enough talbe pool to descrip the memory***/
					return from;
				}
				from->rightChild=newMemHole();
				if(from->rightChild==0){
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
	if(logi>memManager_U)return 0;
	if(logi<memManager_L)logi=memManager_L;
	MemHole *pmh=getMemHoleR(rootHole,logi);
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
	if(pmh){
		pmh->state=MemHoleState_Empty;
		combineFellowHole(pmh);
	}

}

/***************memory interface**************/
MemBlock* tryGetMemBlock(unsigned int size){
	int i=1;
	MemHole *pmh;
	while(((unsigned int)((unsigned int)1<<i))<size)i++;
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


void test(){
	MemHole *pmh[10];
	int p=0;
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
	test();
	system("pause");
	return 0;
}

