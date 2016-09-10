import java.awt.*;
import java.applet.*;
import java.util.Vector;
public class hello extends Applet
{
	public Vector points=new Vector();
	public Vector strs=new Vector();
	public void paint(Graphics g){
		g.drawString("hello world!",5,35);
		g.setColor(getForeground());
		if(points.size()%2!=0){
			points.addElement(points.elementAt(points.size()-1));
		}
		for(int i=0;i<points.size();i+=2){
			MyPoint from=(MyPoint)points.elementAt(i);
			MyPoint to=(MyPoint)points.elementAt(i+1);

			g.drawLine((int)from.getX(),(int)from.getY(),
					(int)(to.getX()-from.getX()),(int)(to.getY()-from.getY()));
		}
	}
	public void init(){
		setBackground(Color.white);

		points.addElement(new MyPoint(0,0));
		points.addElement(new MyPoint(10,10));
		points.addElement(new MyPoint(50,70));
		points.addElement(new MyPoint(90,80));

	}

	public void start(){
	}
	public void stop(){
	}
	public void destroy(){
	}
//***************************for computing*****************
private Boolean drawCutLine(Graphics g, Point from, Point to, Number cut) {
			Point base = from.clone();
			Point dir = to.subtract(from);
			Number len = dir.length;
			if (len <= cut * 2) {
				return false;
			}
			dir = pointMulti(dir, 1 / len);
			from = base.add(pointMulti(dir, spaceLen));
			to = base.add(pointMulti(dir, len - spaceLen));
			//
			g.moveTo(from.x, from.y);
			g.lineTo(to.x, to.y);
			return true;
		}
		private Point pointMulti(Point p, Number t) {
			return new Point(p.x*t,p.y*t);
		}
		private Array createLoop(int num) {
			Array a = new Array();
			if (num == 2) trace("error:createLoop num must >2");
			Number angle = Number(num - 2) * Math.PI / Number(num);
			//
			Point p = new Point( -1, 0);
			a.push(p);
			int i ;
			for (i = 1; i < num; i++) {
				p = a[i - 1];
				a.push(rotatePoint(new Point(-p.x,-p.y),angle));
			}
			
			a[0] = new Point(0, 0);
			for ( i = 1; i < num; i++) {
				Point tempP = a[i];
				a[i] = tempP.add( a[i - 1] as Point);
			}
			return a;
		}
		private void create() {
			points = new Array();
			int loopL = -1, int loopR = -1;
			int tailL, int tailR;
			int l, int r;
			int i = l;
			
			l = 0; r = matches.length-1;
			while (l < r && matches[l] != "1") l++;
			if (l < r) {
			trace("normal");
	
			while (l < r && matches[r] != "1") r--;
			
			tailL = l;
			tailR = r;
			//
			loopL = l;
			loopR = r;
			while (l < r) {
				 l++;
				 while (l < r && matches[l] != "1") l++;
				 if (l == r) break;
				 loopL = l;
				 r--;
				 while (matches[r] != "1") r--;
				 
			}
			loopR = r;
			l = loopL;
			//
			arrayFill(createLoop(r - l + 1), points, l);
			Array leftPoints;
			Array rightPoints;
			while(1){
				for (l = loopL-1; l >= tailL; l--) if (matches[l] == '1') break;
				for (r = loopR+1; r <= tailR; r++) if (matches[r] == '1') break;
				if (matches[l] != '1') break;
				//
				 leftPoints = createSideLoop(loopL - l + 1);
				leftPoints= arrayMultiP(leftPoints, new Point( -1, 1));
				rightPoints = createSideLoop(r - loopR + 1);
				//
				Number heightL = arrayGetHeight(leftPoints);
				Number heightR = arrayGetHeight(rightPoints);
				if (heightL < heightR) { leftPoints = arrayMultiP(leftPoints, new Point(1,heightR / heightL)); }
				else if(heightL > heightR){ rightPoints = arrayMultiP(rightPoints, new Point(1,heightL / heightR)); }
				//
				arrayFill(arrayAdd(leftPoints, points[loopL]).reverse(), points, l);
				arrayFill(arrayAdd(rightPoints, points[loopR]), points, loopR+1);
				//
				loopL = l;
				loopR = r;
				if (loopR == tailR) break;
			}
			
			leftPoints = createLine(tailL + 1);
			leftPoints= arrayMultiP(leftPoints, new Point( -1, 1));
			rightPoints = createLine(matches.length - tailR);
			//
			arrayFill(arrayAdd(leftPoints, points[loopL]).reverse(), points, 0);
			arrayFill(arrayAdd(rightPoints, points[loopR]), points, tailR+1);
			//
			}else {
				trace("contain only 0");
				points[0] = new Point(0, 0);
				arrayFill(createLine(matches.length), points, 1);
			}
			trace("points", points);
			
		}
		private Rectangle arrayGetRect(Array arr) {
			Number minX, Number maxX;
			Number minY, Number maxY;
			minX = maxX =0;
			minY = maxY =0;
			for (int i = 0; i < arr.length; i++) {
				Point p = arr[i] as Point;
				if (minX > p.x) minX = p.x;
				else if (maxX < p.x) maxX = p.x;
				if (minY > p.y) minY = p.y;
				else if (maxY < p.y) maxY = p.y;
			}
			return new Rectangle(minX, minY, maxX, maxY);
		}
		private Number arrayGetWidth(Array arr) {
			//assume that the (0,0) is included in arr
			Number minX, Number maxX;
			minX = maxX =0;
			for (int i = 0; i < arr.length; i++) {
				Point p = arr[i] as Point;
				if (minX > p.x) minX = p.x;
				else if (maxX < p.x) maxX = p.x;
			}
			return maxX - minX;
		}
		private Number arrayGetHeight(Array arr) {
			//assume that the (0,0) is included in arr
			Number minY, Number maxY;
			minY = maxY =0;
			for (int i = 0; i < arr.length; i++) {
				Point p = arr[i] as Point;
				if (minY > p.y) minY = p.y;
				else if (maxY < p.y) maxY = p.y;
			}
			return maxY - minY;
		}
		private Array createLine(int num) {
			//not conatin the first one (0,0); 
			//return a array with len of num-1
			Array a = new Array();
			for (int i = 0; i < num - 1; i++) {
				a.push(new Point(i + 1, 0));
			}
			return a;
		}
		private Array createSideLoop(int num) {
			//not contain the first one (0,0)
			//return a array with len of num-1
			Array a = new Array();
			switch(num) {
				case 2:
				a.push(new Point(0,-1));
				break;
				case 3:
				a.push(new Point(Math.sqrt(3) / 2, -0.5));
				a.push(new Point(0,-1));
				
				break;
				case 4:
				a.push(new Point(Math.sqrt(3) / 2, -0.5));
				a.push(new Point(Math.sqrt(3) / 2, -1.5));
				a.push(new Point(0,-2));
				
				break;
				 default
					int numLen = 2 * (num - 1);
					Array loop = createLoop(numLen);
					loop.splice(0, 1);
					Number angle = Math.PI *(1- (numLen-2) / numLen) / 2.0;
					for (int i = 0; i < loop.length; i++) {
						Point tempP = loop[i];
						loop[i] = rotatePoint(tempP, angle);
					}
					arrayFill(loop, a, 0, num-1);
					a = arrayMulti(a, -1);
				break;
				
				
			}
			return a;
		}
		private Point rotatePoint(Point p, Number a) {
			Number cosa = Math.cos(a);
			Number sina = Math.sin(a);
			return new Point(p.x *cosa  - p.y *sina, p.x * sina + p.y * cosa );                                                                                          
		}
		private Array arrayMulti(Array arr, Number t) {
			return arrayMultiP(arr,new Point(t,t));
		}
		private Array arrayMultiP(Array arr, Point t) {
			for (int i = 0; i < arr.length; i++) {
				Point p = arr[i] as Point;
				 p.x *= t.x;
				 p.y*=t.y;
				 arr[i] = p;
			}
			return arr;
		}
		private Array arrayAdd(Array arr, Point adden) {
			for (int i = 0; i < arr.length; i++) {
				Point p = arr[i] as Point;
				arr[i] = p.add(adden) ;
			}
			return arr;
		}
		private void arrayFill(Array from, Array to, int start, int len=-1) {
			if (len == -1) len = from.length;
			for (int i = 0; i < len; i++) {
				to[start + i] = from[i];
			}
		}
		private TextField getChar( String c, int posx=0, int posy=0) {
			TextField t = new TextField();
			t.text = c.toUpperCase();
			t.autoSize = TextFieldAutoSize.CENTER;
			t.selectable = false;
			t.x = posx-t.width/2;
			t.y = posy-t.height/2;
			addChild(t);
			return t;
		}
}

class MyPoint{
	public MyPoint(double x,double y){
		_x=x;
		_y=y;
	}
	public double getX(){return _x;}
	public double getY(){return _y;}
	private double _x;
	private double _y;
}
