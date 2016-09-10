function drawState(states,drawOrientationPer)
n=size(states,1);
XY=states(:,1:2)';
x=XY(1,:);
y=XY(2,:);
ts=states(:,3);
len=10;
axis([-len len -len len]);
plot(x,y);
hold on;
%
drawOrientationPer=uint32(drawOrientationPer);
if(nargin==2 && drawOrientationPer>0)
ox=x;
oy=y;
olen=0.3;
ox(2,:)=x-olen*sin(ts');
oy(2,:)=y+olen*cos(ts');
ox=ox(:,1:drawOrientationPer:end);
oy=oy(:,1:drawOrientationPer:end);
axis([-len len -len len]);
plot(ox,oy);
hold on;
end
%


