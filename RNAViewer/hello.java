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
	public void drawCutLine(MyPoint from,MyPoint to,double cut){

	}
	public void start(){
	}
	public void stop(){
	}
	public void destroy(){
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
