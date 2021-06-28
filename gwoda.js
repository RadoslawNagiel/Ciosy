function gwater(){
	for(y=0;y<mapaWys;y++)
	{
		for(x=0;x<mapaSzer;x++)
		{
			los=Math.floor(Math.random()*1001);
			if(los<1)
			pole[x][y]=3;	
		}
	}
	
	for(i=0;i<2;i++)
	{
		for(y=0;y<mapaWys;y++)
		{
			for(x=0;x<mapaSzer;x++)
			{
				if (x!=0 && y!=0 && x<mapaSzer-1 && y<mapaWys-1 && pole[x][y]!=3)
				{
					los=Math.floor(Math.random()*101);
					if (los<40 && (pole[x-1][y]==3 || pole[x][y-1]==3 || pole[x+1][y]==3 || pole[x][y+1]==3))
						pole[x][y]=3;	
					los=Math.floor(Math.random()*101)
					if(los<80 && ((pole[x-1][y]==3 && pole[x][y-1]==3)
						||(pole[x-1][y]==3 && pole[x][y+1]==3)
						||(pole[x-1][y]==3 && pole[x+1][y]==3)
						||(pole[x][y+1]==3 && pole[x][y-1]==3)
						||(pole[x+1][y]==3 && pole[x][y+1]==3)
						||(pole[x+1][y]==3 && pole[x][y-1]==3)))
							pole[x][y]=3;
					los=Math.floor(Math.random()*101)
					if(los<80 && ((pole[x-1][y]==3 && pole[x][y+1]==3 && pole[x][y-1]==3)
						||(pole[x-1][y]==3 && pole[x][y+1]==3 && pole[x+1][y]==3)
						||(pole[x-1][y]==3 && pole[x][y-1]==3 && pole[x+1][y]==3)
						||(pole[x+1][y]==3 && pole[x][y-1]==3 && pole[x][y+1]==3)))
							pole[x][y]=3;
				}
			}
		}
	}
	for(i=0;i<2;i++)
	{
		for(y=1;y<mapaWys-1;y++)
		{
			for(x=1;x<mapaSzer-1;x++)
			{
				if(pole[x-1][y]==3 && pole[x+1][y]==3 && pole[x][y+1]==3 && pole[x][y-1]==3)
					pole[x][y]=3;
				if((pole[x-1][y]==3 && pole[x][y+1]==3 && pole[x][y-1]==3)
						||(pole[x-1][y]==3 && pole[x][y+1]==3 && pole[x+1][y]==3)
						||(pole[x-1][y]==3 && pole[x][y-1]==3 && pole[x+1][y]==3)
						||(pole[x+1][y]==3 && pole[x][y-1]==3 && pole[x][y+1]==3))
							pole[x][y]=3;
			}
		}
	}
	for(y=1;y<mapaWys-1;y++)
	{
		for(x=1;x<mapaSzer-1;x++)
				if(pole[x][y]==3)
					if((pole[x-1][y]>=3 && pole[x][y+1]>=3 && pole[x][y-1]>=3)
						||(pole[x-1][y]>=3 && pole[x][y+1]>=3 && pole[x+1][y]>=3)
						||(pole[x-1][y]>=3 && pole[x][y-1]>=3 && pole[x+1][y]>=3)
						||(pole[x+1][y]>=3 && pole[x][y-1]>=3 && pole[x][y+1]>=3)
						||(pole[x+1][y]>=3 && pole[x-1][y]>=3)
						||(pole[x][y+1]>=3 && pole[x][y-1]>=3))
						pole[x][y]=3;
					else if(pole[x-1][y]<3 && pole[x+1][y]<3 && pole[x][y+1]<3 && pole[x][y-1]<3)
						pole[x][y]=3.1;
					else if (pole[x+1][y]>=3 && pole[x][y+1]>=3)
					{
						if(pole[x-1][y+1]>=3)
							if(pole[x+1][y-1]>=3)
								pole[x][y]=3.21;
							else 
								pole[x][y]=3.51;
						else if(pole[x+1][y-1]>=3)
							pole[x][y]=3.41;
						else 
							pole[x][y]=3.11;
					}
					else if (pole[x-1][y]>=3 && pole[x][y+1]>=3)
					{
						if(pole[x-1][y-1]>=3)
							if(pole[x+1][y+1]>=3)
								pole[x][y]=3.22;
							else 
								pole[x][y]=3.52;
						else if(pole[x+1][y+1]>=3)
							pole[x][y]=3.42;
						else 
							pole[x][y]=3.12;
					}
					else if (pole[x-1][y]>=3 && pole[x][y-1]>=3)
					{
						if(pole[x+1][y-1]>=3)
							if(pole[x-1][y+1]>=3)
								pole[x][y]=3.23;
							else 
								pole[x][y]=3.53;
						else if(pole[x-1][y+1]>=3)
							pole[x][y]=3.43;
						else 
							pole[x][y]=3.13;
					}
					else if (pole[x+1][y]>=3 && pole[x][y-1]>=3)
					{
						if(pole[x-1][y-1]>=3)
							if(pole[x+1][y+1]>=3)
								pole[x][y]=3.24;
							else 
								pole[x][y]=3.44;
						else if(pole[x+1][y+1]>=3)
							pole[x][y]=3.54;
						else 
							pole[x][y]=3.14;
					}
					else if (pole[x+1][y]>=3)
						pole[x][y]=3.31;
					else if (pole[x][y+1]>=3)
						pole[x][y]=3.32;
					else if (pole[x-1][y]>=3)
						pole[x][y]=3.33;
					else if (pole[x][y-1]>=3)
						pole[x][y]=3.34;
	}
			
	for(y=1;y<mapaWys-1;y++)
	{
		for(x=1;x<mapaSzer-1;x++)
			if(pole[x][y]>3&&pole[x][y]<4)
			{
				if(pole[x][y]==3.41){
					if(pole[x+1][y-1]==3.21 || pole[x+1][y-1]==3.51)pole[x][y]=3.11;;
				}
				else if(pole[x][y]==3.51){
					if(pole[x-1][y+1]==3.21 || pole[x-1][y+1]==3.41)pole[x][y]=3.11;;
				}
				else if(pole[x][y]==3.42){
					if(pole[x+1][y+1]==3.22 || pole[x+1][y+1]==3.52)pole[x][y]=3.12;;
				}
				else if(pole[x][y]==3.52){
					if(pole[x-1][y-1]==3.22 || pole[x-1][y-1]==3.42)pole[x][y]=3.12;;
				}
				else if(pole[x][y]==3.43){
					if(pole[x-1][y+1]==3.23 || pole[x-1][y+1]==3.53)pole[x][y]=3.13;;
				}
				else if(pole[x][y]==3.53){
					if(pole[x+1][y-1]==3.23 || pole[x+1][y-1]==3.43)pole[x][y]=3.13;;
				}
				else if(pole[x][y]==3.44){
					if(pole[x-1][y-1]==3.24 || pole[x-1][y-1]==3.54)pole[x][y]=3.14;;
				}
				else if(pole[x][y]==3.54){
					if(pole[x+1][y+1]==3.24 || pole[x+1][y+1]==3.44)pole[x][y]=3.14;;
				}
				else if(pole[x][y]==3.21){
					if(pole[x-1][y+1]==3.21)pole[x][y]=3.41;;
				}
				else if(pole[x][y]==3.22){
					if(pole[x+1][y+1]==3.22)pole[x][y]=3.52;;
				}
				else if(pole[x][y]==3.23){
					if(pole[x-1][y+1]==3.23)pole[x][y]=3.53;;
				}
				else if(pole[x][y]==3.24){
					if(pole[x+1][y+1]==3.24)pole[x][y]=3.44;;
				}
			}
			else if(pole[x][y]==0)
				if((pole[x+1][y]==3 || pole[x+1][y]==3.32) && (pole[x][y+1]==3 || pole[x][y+1]==3.31))
					pole[x][y]=3.01;
				else if((pole[x-1][y]==3 || pole[x-1][y]==3.32) && (pole[x][y+1]==3 || pole[x][y+1]==3.33))
					pole[x][y]=3.02;
				else if((pole[x-1][y]==3 || pole[x-1][y]==3.34) && (pole[x][y-1]==3 || pole[x][y-1]==3.33))
					pole[x][y]=3.03;
				else if((pole[x+1][y]==3 || pole[x+1][y]==3.34) && (pole[x][y-1]==3 || pole[x][y-1]==3.31))
					pole[x][y]=3.04;
	}	
}