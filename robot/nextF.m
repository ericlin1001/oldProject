function newfs=nextF(states,fs)
[v w]=basicController(states(size(states,1),:));
newfs=[fs;[v w]];
return ;


