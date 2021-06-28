<!DOCTYPE html>
<head>
	<meta charset='utf-8'>
	<title>LP</title>
	<link rel=stylesheet href=main.css>
	<link rel="Shortcut icon" href="ikona.ico" />
</head>
<body style='background-color:#6bab40;color:#dbc8a7; font-size:20px;'>
<?php
session_start();
	if(!isset($_SESSION['login']))
		header("Location: index.php");
?>
<center>
	<table>
	<tr>
		<form method='POST' action='index.php'>
			<input type='submit' value='Menu' class='przycisk'>
		</form>&nbsp;
		<form method='POST' action='zalogowany.php'>
			<input type="hidden" name="wyloguj" value="true">
			<input type='submit' value='Wyloguj' class='przycisk'>
		</form>&nbsp;
		<form method='POST' action='zapis2.php'>
			<input type="hidden" id="abc" name="m" value='nic'>
			<input type='submit' value='Zapisz' class='przycisk'>
		</form>&nbsp;
		<input type='button' onclick="zapisz()" value='Zapisz' class='przycisk'>
		<br>
	</tr>
	</table>
	
<canvas id="gc" width="1000" height="600" onmousemove="mouseMove(event)"></canvas>
<script src="gwoda.js"></script>
<script>

	window.onload=function()
	{
		canv = document.getElementById("gc");
		ctx=canv.getContext("2d");
		loadImage();
		generate();
		createCraftingSlot();
		wczytaj();
		while(pole[Math.round(posX-0.49)][Math.round(posY-0.49)]>=3 && pole[Math.round(posX-0.49)][Math.round(posY-0.49)]<4)
			posX++;
		document.addEventListener("keydown",keyDown);
		document.addEventListener("keyup",keyUp);
		document.addEventListener("click",click);
		setInterval(game,1000/60);
		//setTimeout(function(){window.location.href = "http://www.google.com";},5000);
	}
	{//Mapa
		szer=20;
		wys=15;
		mapaSzer=2000;
		mapaWys=1000;
		size=40;
		
		mobID=1000;
		mobs = [];
		bushs = [];
		drzewa = [];
		for(x=0;x<mapaSzer;x++)
		{
				drzewa[x]=[mapaWys];
				bushs[x]=[mapaWys];		
		}
	}
	{//Postać mechanika
		moveX=moveY=0;
		posX=mapaSzer/2;
		posY=mapaWys/2;
		ost=0;
		
	}
	{//GUI
		eq=false;
		craft=false;	
		cursorX=cursorY=0;	
		gameTimer=0;
		select=1;
		craftPage=1;
		klikniecie = new Mouse(0,false);
		crafting= [15];
		ekwipunek= [5];
		for(x=0;x<5;x++)
			{
				ekwipunek[x]=[6];
				for(y=0;y<6;y++)
					ekwipunek[x][y]=new Slot(0,0,0)
			}
		
		pole = [mapaSzer];
		for(x=0;x<mapaSzer;x++)
			{
				pole[x]=[mapaWys];
				for(y=0;y<mapaWys;y++)
					pole[x][y]=0;
			}
		mobHit = new Napis(false);
		playerHit = new Napis(false);
		powolnaSmierc = new Napis(false);
	}
	{//Animacja
		rzucenie = new Rzut(0,0,0,0,0,0);
		frameNr = 1;
		maxFrame = 4;
		animacjaDelay=0.5;
		animacjaPodnies=false;
		animacjaSpeed=0.2;
		animacjaStan=0.5;
	}
	{//Postac atrybuty
		speed=0.06;
		zycie=100;
		pragnienie=100;
		glod=100;
		picieDelay=500;
		picieMaxDelay=500;
		glodDelay=700;
		glodMaxDelay=700;
		zycieDelay=300;
		zycieMaxDelay=300;
		smiercDelay=100;
		smiercMaxDelay=100;
		maxWalkaDelay=60;
		walkaDelay=60;
		maxAtak=8;
	}
		
	function generate(){
		gwater();
		for(x=0;x<mapaSzer;x++)
			for(y=0;y<mapaWys;y++)
				if(pole[x][y]==0)
				{
					los=Math.random();
					if(los<0.04)
					{
						if(pole[x][y+1]==0)
						{
							typ=Math.floor(Math.random()*4)+1;
							delay=Math.floor(Math.random()*30);
							pole[x][y+1]=1;
							drzewa[x][y+1]= new Drzewo(typ,delay);
						}
					}
					else if(los<0.05)
						pole[x][y]=2;
					else if(los<0.06)
						pole[x][y]=6;
					else if(los<0.08)
						pole[x][y]=7;
					else if(los<0.1)
					{
						los=Math.random();
						stan=true;
						delay=0;
						if(los<0.5)
						{
							stan=false;
							delay=Math.floor(Math.random()*20);
						}
						typ=Math.floor(Math.random()*4+1);
						pole[x][y]=9+typ/10;
						bushs[x][y]= new Bush(typ,stan,delay);
					}
					else if(los<0.102)
					{
						pole[x][y]=mobID;
						los=Math.random();
						if(los<0.06)
							mobs[mobID] = new Mob(mobID,x,y,"mammoth",200,100,500);
						else if(los<0.53)
							mobs[mobID] = new Mob(mobID,x,y,"boar",30,15,100);
						else
							mobs[mobID] = new Mob(mobID,x,y,"wolf",20,5,40);
						mobID++;
					}
					else
						pole[x][y]=0;
				}
	
	}

	function game(){
		gameTimer+=0.0002778;
		if(moveX<0 && (posX<szer || kolizja(37)))
			moveX=0;
		else if(moveX>0 && (posX>mapaSzer-szer || kolizja(39)))
			moveX=0;
		if(moveY<0 && (posY<wys || kolizja(38)))
			moveY=0;
		else if(moveY>0 && (posY>mapaWys-wys || kolizja(40)))
			moveY=0;
		
		if(zycie>0)
		{
			if(!eq && !craft)
			{
				posX+=moveX;
				posY+=moveY;	
			}
			if(glodDelay<=0)
			{
				glodDelay=glodMaxDelay;
				if(glod>0)glod--;
			}
			else
				glodDelay--;
			if(picieDelay<=0)
			{
				picieDelay=picieMaxDelay;
				if(pragnienie>0)pragnienie--;
			}
			else
				picieDelay--;
			
			if(pragnienie<=0 || glod<=0)
			{
				if(smiercDelay<=0)
				{
					zycie--;
					smiercDelay=smiercMaxDelay;
					powolnaSmierc.x = (390);
					powolnaSmierc.y = (240);
					powolnaSmierc.text = "-1";
					powolnaSmierc.pokaz = true;
				}
				else
					smiercDelay--;
			}
			else if(zycie<100)
			{
				if(zycieDelay<=0)
				{
					if(pragnienie>zycie && glod>zycie)zycie++;
					zycieDelay=zycieMaxDelay;
				}
				else zycieDelay--;
			}
		}
		else
		{
			eq=false;
			craft=false;			
		}
		
		pX=Math.round(posX);
		pY=Math.round(posY);
		ctx.fillStyle="#2d6b1d";
		ctx.fillRect(0,0,canv.width-200,canv.height);
		//ctx.drawImage(tlo,(pX-posX)*size*4,(pY-posY)*size*4);

		pokazMape();
		if(!eq && !craft) pokazPostac();
		
		for(x=0;x<szer;x++)
			for(y=0;y<wys;y++)
				if(pole[x+pX-10][y+pY-6]==1)
				{
					if(y==14)
						drzewa[x+pX-10][y+pY-6].rosnij();
					if(drzewa[x+pX-10][y+pY-6].typ==3)
						ctx.drawImage(tree3l,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
					else if(drzewa[x+pX-10][y+pY-6].typ==4)
						ctx.drawImage(tree4l,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
				}
		pX=Math.round(posX-0.49);
		pY=Math.round(posY-0.49);
		
		if(pole[pX][pY]>999 && zycie>0)
			walka();
		if(mobHit.pokaz)
			mobHit.pokazTekst();
		if(playerHit.pokaz)
			playerHit.pokazTekst();
		if(powolnaSmierc.pokaz)
			powolnaSmierc.pokazTekst();
		if(eq) 
			pokazEkwipunek();
		else if(craft)
			pokazCrafting();
		pokazMenu();
	}

	function keyDown(evt){
		if(zycie>0)
			switch(evt.keyCode)
			{
				case 37:
				case 65:
					moveX=-speed;
					break;
				case 38:
				case 87:
					moveY=-speed;
					break;
				case 39:
				case 68:
					moveX=speed;
					break;
				case 40:
				case 83:
					moveY=speed;
					break;
				case 13:
				case 32:
					if(!eq && !craft)
					{
						pX=Math.round(posX-0.49);
						pY=Math.round(posY-0.79);
						if(pole[pX][pY+1]==1 && drzewa[pX][pY+1].typ==4) podnies(8);
						else podnies(pole[pX][pY]);
					}
					break;
				case 69:
					if(eq && klikniecie.start)
					{
						ekwipunek[klikniecie.poleX][klikniecie.poleY].klikniecie=false;
						klikniecie.start=false;
					}
					if(!craft)eq=!eq;
					break;
				case 67:
					if(!eq)craft=!craft;
					break;
				case 49:
					select=1;
					break;
				case 50:
					select=2;
					break;
				case 51:
					select=3;
					break;
				case 52:
					select=4;
					break;
				case 53:
					select=5;
					break;
				case 81:
					if(ekwipunek[select-1][5].id==7 && !rzucenie.stan)
						rzucenie.start();
					else if(ekwipunek[select-1][5].id==5 || (ekwipunek[select-1][5].id>9 && ekwipunek[select-1][5].id<10))
						zjedz(ekwipunek[select-1][5].id);
					else if(ekwipunek[select-1][5].id>=10 && ekwipunek[select-1][5].id<14)
						interakcja(ekwipunek[select-1][5].id);
					break;
			}
	}

	function keyUp(evt){
		switch(evt.keyCode)
		{
			case 37:
			case 65:
			case 39:
			case 68:
				moveX=0;
				break;
			case 38:
			case 87:
			case 40:
			case 83:
				moveY=0;
				break;
		}
	}

	function loadImage(){
		postac=document.createElement("IMG");
		postac.setAttribute("src","img/postac.png");
		postacl=document.createElement("IMG");
		postacl.setAttribute("src","img/postacl.png");
		postacp=document.createElement("IMG");
		postacp.setAttribute("src","img/postacp.png");
		postact=document.createElement("IMG");
		postact.setAttribute("src","img/postact.png");
		postacwl=document.createElement("IMG");
		postacwl.setAttribute("src","img/postacwl.png");
		postacwp=document.createElement("IMG");
		postacwp.setAttribute("src","img/postacwp.png");
		zbieraniegif=document.createElement("IMG");
		zbieraniegif.setAttribute("src","img/zbieraniegif.png");
		postacgif=document.createElement("IMG");
		postacgif.setAttribute("src","img/postacgif.png");
		postaclgif=document.createElement("IMG");
		postaclgif.setAttribute("src","img/postaclgif.png");
		postacpgif=document.createElement("IMG");
		postacpgif.setAttribute("src","img/postacpgif.png");
		postactgif=document.createElement("IMG");
		postactgif.setAttribute("src","img/postactgif.png");
		postacwpgif=document.createElement("IMG");
		postacwpgif.setAttribute("src","img/postacwpgif.png");
		dead=document.createElement("IMG");
		dead.setAttribute("src","img/dead.png");
		
		tree1=document.createElement("IMG");
		tree1.setAttribute("src","img/tree1.png");
		tree2=document.createElement("IMG");
		tree2.setAttribute("src","img/tree2.png");
		tree3=document.createElement("IMG");
		tree3.setAttribute("src","img/tree3.png");
		tree4=document.createElement("IMG");
		tree4.setAttribute("src","img/tree4.png");
		tree3l=document.createElement("IMG");
		tree3l.setAttribute("src","img/tree3l.png");
		tree4l=document.createElement("IMG");
		tree4l.setAttribute("src","img/tree4l.png");
		bole=document.createElement("IMG");
		bole.setAttribute("src","img/bole.png");
		bole1=document.createElement("IMG");
		bole1.setAttribute("src","img/bole1.png");
		bole2=document.createElement("IMG");
		bole2.setAttribute("src","img/bole2.png");
		
		water=document.createElement("IMG");
		water.setAttribute("src","img/water.png");
		water0=document.createElement("IMG");
		water0.setAttribute("src","img/water0.png");
		water1=document.createElement("IMG");
		water1.setAttribute("src","img/water1.png");
		water2=document.createElement("IMG");
		water2.setAttribute("src","img/water2.png");
		water3=document.createElement("IMG");
		water3.setAttribute("src","img/water3.png");
		water4=document.createElement("IMG");
		water4.setAttribute("src","img/water4.png");
		water5=document.createElement("IMG");
		water5.setAttribute("src","img/water5.png");
		waterr=document.createElement("IMG");
		waterr.setAttribute("src","img/waterr.png");
		splash=document.createElement("IMG");
		splash.setAttribute("src","img/splash.png");

		stone=document.createElement("IMG");
		stone.setAttribute("src","img/stone.png");
		stick=document.createElement("IMG");
		stick.setAttribute("src","img/stick.png");
		rock=document.createElement("IMG");
		rock.setAttribute("src","img/rock.png");
		meat=document.createElement("IMG");
		meat.setAttribute("src","img/meat.png");
		leaf=document.createElement("IMG");
		leaf.setAttribute("src","img/leaf.png");
		pickaxe=document.createElement("IMG");
		pickaxe.setAttribute("src","img/pickaxe.png");
		shovel=document.createElement("IMG");
		shovel.setAttribute("src","img/shovel.png");
		spear=document.createElement("IMG");
		spear.setAttribute("src","img/spear.png");
		bowl=document.createElement("IMG");
		bowl.setAttribute("src","img/bowl.png");
		bowl2=document.createElement("IMG");
		bowl2.setAttribute("src","img/bowl2.png");
		ax=document.createElement("IMG");
		ax.setAttribute("src","img/ax.png");
		campfire=document.createElement("IMG");
		campfire.setAttribute("src","img/campfire.png");
		club=document.createElement("IMG");
		club.setAttribute("src","img/club.png");
		
		boar=document.createElement("IMG");
		boar.setAttribute("src","img/boar.png");
		wolf=document.createElement("IMG");
		wolf.setAttribute("src","img/wolf.png");
		mammoth=document.createElement("IMG");
		mammoth.setAttribute("src","img/mammoth.png");
		
		bush=document.createElement("IMG");
		bush.setAttribute("src","img/bush.png");
		bush1=document.createElement("IMG");
		bush1.setAttribute("src","img/bush1.png");
		bush2=document.createElement("IMG");
		bush2.setAttribute("src","img/bush2.png");
		bush3=document.createElement("IMG");
		bush3.setAttribute("src","img/bush3.png");
		bush4=document.createElement("IMG");
		bush4.setAttribute("src","img/bush4.png");
		fruit1=document.createElement("IMG");
		fruit1.setAttribute("src","img/fruit1.png");
		fruit2=document.createElement("IMG");
		fruit2.setAttribute("src","img/fruit2.png");
		fruit3=document.createElement("IMG");
		fruit3.setAttribute("src","img/fruit3.png");
		fruit4=document.createElement("IMG");
		fruit4.setAttribute("src","img/fruit4.png");
		
		strz=document.createElement("IMG");
		strz.setAttribute("src","img/strz.png");
		E=document.createElement("IMG");
		E.setAttribute("src","img/E.png");
	}

	function kolizja(x){
		speedX=speedY=0;
		switch(x)
		{
		case 37:
			speedX-=speed;
			break;
		case 38:
			speedY-=speed;
			break;
		case 39:
			speedX+=speed;
			break;
		case 40:
			speedY+=speed;
			break;		
		}
		pX=Math.round(posX-0.49+speedX);
		pY=Math.round(posY-0.49+speedY);
		
		if(pole[pX][pY]==1)
		{
			pX1=pX;
			pX2=pX;
			pY1=pY;
			if(drzewa[pX][pY].typ==1)
			{
				pX1=Math.round(posX-0.49+speedX-0.35);
				pX2=Math.round(posX-0.49+speedX+0.25);
				pY1=Math.round(posY-0.96+speedY)
			}
			else
			{
				pX1=Math.round(posX-0.49+speedX-0.25);
				pX2=Math.round(posX-0.49+speedX+0.15);
			}
			if(!(pX1!=pX || pX2!=pX || pY1!=pY))
				return true;
		}
		else if(pole[pX][pY]==2)
		{
			pY2=Math.round(posY-0.66+speedY)
			if(pY2==pY)
				return true;
		}
		else if(pole[pX][pY]==3 || (pole[pX][pY]>=3.09 && pole[pX][pY]<4))
			return true;
		else if(pole[pX][pY]>=9 && pole[pX][pY]<10)
		{
			pY2=Math.round(posY-0.66+speedY)
			if(pY==pY2)
				return true;
		}
		return false;
	}

	function podnies(id){//
		pX=Math.round(posX-0.49);
		pY=Math.round(posY-0.79);
		if((id>4 && id<9) || (id>9 && id<10 && bushs[pX][pY].stan) || (id>=10 && id<18))
		{
			for(y=0;y<6;y++)
				for(x=0;x<5;x++)
				{
					if((((id==17.1 || id==17.2) && ekwipunek[x][y].id==17 ) || id==ekwipunek[x][y].id) && ekwipunek[x][y].id && ekwipunek[x][y].ilosc < ekwipunek[x][y].max)
					{
						if(id==8)
						{
							ekwipunek[x][y].ilosc++;
							return 0;
						}
						else if(id>9 && id<10)
						{
							ekwipunek[x][y].ilosc++;
							bushs[pX][pY].podnies();
							animacjaPodnies=true;
							return 0;
						}
						else if(id>=17 && id<18)
						{
							ekwipunek[x][y].ilosc++;
							if(pole[pX][pY]==17)pole[pX][pY]=0;
							else pole[pX][pY]-=0.1;
							pole[pX][pY]=Round(pole[pX][pY], 1)
							return 0;
						}
						ekwipunek[x][y].ilosc++;
						pole[pX][pY]=0;
						if(id==5)ekwipunek[x][y].max=5;
						return 0;
					}
				}
			y=4;
			do
			{
				y++;
				if(y==6)y=0;
				for(x=0;x<5;x++)
				{
					if(!ekwipunek[x][y].id)
					{
						if(id==8)
						{
							ekwipunek[x][y].id=8;
							ekwipunek[x][y].max=20;
						}
						else if(id>9 && id<10)
						{
							animacjaPodnies=true;
							ekwipunek[x][y].id=id;
							bushs[pX][pY].podnies();
							ekwipunek[x][y].max=10;
						}
						else if(id>=10 && id<17)
						{
							ekwipunek[x][y].id=id;
							ekwipunek[x][y].max=1;
						}
						else if(id>=17 && id<18)
						{
							if(id==17)
								pole[pX][pY]=0;
							else
								pole[pX][pY]-=0.1;
							pole[pX][pY]=Round(pole[pX][pY], 1);
							ekwipunek[x][y].id=17;
							ekwipunek[x][y].max=10;
						}
						else
						{
							ekwipunek[x][y].id=id;
							ekwipunek[x][y].max=10;
							if(id==5)ekwipunek[x][y].max=5;
							pole[pX][pY]=0;
						}
						ekwipunek[x][y].ilosc++;
						return 0;
					}
				}
			}while(y!=4);
		}
	}

	function walka(x){
		pX=Math.round(posX-0.49);
		pY=Math.round(posY-0.49);
		Id=pole[pX][pY];
		ctx.fillStyle="black";
		ctx.font = "20px Arial";
		ctx.fillText(mobs[Id].live, 405+(mobs[Id].Px-posX)*size, 335+(mobs[Id].Py-posY)*size);
		if(walkaDelay<=0)
		{
			walkaDelay=maxWalkaDelay;
			los=Math.round(Math.random()*maxAtak);
			mobs[Id].live-=los;
			mobHit.x = (380+(mobs[Id].Px-posX)*size);
			mobHit.y = (330+(mobs[Id].Py-posY)*size);
			mobHit.animPost = true;
			mobHit.text = "-"+los;
			mobHit.pokaz = true;
		}
		else 
			walkaDelay-=1;
		if(mobs[Id].delay<=0)
		{
			mobs[Id].delay=mobs[Id].maxDelay;
			los=Math.round(Math.random()*mobs[Id].atak);
			zycie-=los;
			if(zycie<0)zycie=0;
			playerHit.x = (390);
			playerHit.y = (240);
			playerHit.text = "-"+los;
			playerHit.pokaz = true;
		}
		else 
			mobs[Id].delay-=1;
		
		if(mobs[Id].live<=0)
			{
				mobs[Id].destroy;
				pole[pX][pY]=5;
			}
	}

	function Mob(id,x,y,nazwa,zycie,atak,szybkosc){
		this.id=id;
		this.Px=x;
		this.Py=y;
		this.nazwa=nazwa;
		this.live=zycie;
		this.atak=atak;
		this.delay=szybkosc;
		this.maxDelay=szybkosc;
	}

	function Napis(pokaz){
		this.pokaz=pokaz;
		this.text=0;
		this.x=0;
		this.y=0;
		this.animPost=false;
		this.delay=30;
		this.maxDelay=30;
		this.pokazTekst = function()
		{
			ctx.fillStyle="red";
			ctx.font = "20px Arial";
			ctx.fillText(this.text, this.x, this.y);
			this.y--;
			this.delay--;
			if(this.delay<=0)
			{
				this.delay=this.maxDelay
				this.pokaz=false
			}
		}
	}

	function pokazPostac(){
		if(zycie<=0)
			ctx.drawImage(dead, 378, 243);
		else if(mobHit.pokaz && mobHit.animPost)
		{
			animacjaSpeed=0.15;
			if(ost==2)animacja(5);
			else animacja(5);
		}
		else if(animacjaPodnies)
		{
			ost=3;
			animacja(4);
		}
		else if(moveX<0)
		{
			ost=1;
			animacja(1);
		}	
		else if(moveX>0)
		{
			ost=2;
			animacja(2);
		}	
		else if(moveY<0)
		{
			ost=3;
			animacja(3);
		}	
		else if(moveY>0)
		{
			ost=0;
			animacja(0);
		}
		else
			switch(ost)
			{
				case 0:
					ctx.drawImage(postac, 378, 243);
					break;
				case 1:
					ctx.drawImage(postacl, 378, 243);
					break;
				case 2:
					ctx.drawImage(postacp, 378, 243);
					break;
				case 3:
					ctx.drawImage(postact, 378, 243);
					break;
			}	
	}

	function pokazMenu(){
		ctx.fillStyle="#3d3922";
		ctx.fillRect(canv.width-220,0,220,canv.height);//ramka prawo
		ctx.fillStyle="#6b643f";
		ctx.fillRect(canv.width-200,20,180,300);//prawy panel tło
		ctx.fillStyle="#3d3922";
		ctx.fillRect(0,0,20,canv.height);//ramka lewo
		ctx.fillRect(0,0,canv.width,20);//ramka góra
		ctx.fillRect(0,canv.height-20,canv.width,20);//ramka dół
			
		for(x=0;x<5;x++)
		{
			if(select==x+1) ctx.fillStyle="#3d3d3d";
			else ctx.fillStyle="#3d3922";
			ctx.fillRect(canv.width-195+35*x,canv.height-345,30,60);
			ctx.fillStyle="#82794e";
			ctx.font = "20px Arial";
				if(ekwipunek[x][5].id!=0)
				{
					ctx.drawImage(nazwaPrzedmiotu(ekwipunek[x][5].id),canv.width-200+35*x, canv.height-345, 40, 40);
					polX=192;
					if(ekwipunek[x][5].ilosc<10)polX=185;
					ctx.fillText(ekwipunek[x][5].ilosc,canv.width-polX+35*x,canv.height-290);
				}
		}
		ctx.fillStyle="#262626";
		ctx.fillRect(canv.width-200,canv.height-270,50,250);//zycie tlo
		ctx.fillRect(canv.width-135,canv.height-270,50,250);//pragnienie tlo
		ctx.fillRect(canv.width-70,canv.height-270,50,250);//naglod tlo
		ctx.fillStyle="#8c050c";
		ctx.fillRect(canv.width-200,canv.height-20-2.5*zycie,50,2.5*zycie);//zycie
		ctx.fillStyle="#2687b5";
		ctx.fillRect(canv.width-135,canv.height-20-2.5*pragnienie,50,2.5*pragnienie);//pragnienie
		ctx.fillStyle="#c4766c";
		ctx.fillRect(canv.width-70,canv.height-20-2.5*glod,50,2.5*glod);//naglod
		
		ctx.fillStyle="#cecece";
		ctx.font = "20px Arial";
		ctx.fillText("X: "+pX+"  Y: "+pY,810,50);
		ctx.fillText(zycie,807,470);
		ctx.fillText(pragnienie,872,470);
		ctx.fillText(glod,937,470);
	}
	
	function nazwaPrzedmiotu(id){
		switch(id)
		{
			case 0:
				return "";
			case 2:
				return rock;
				break;
			case 5:
				return meat;
				break;
			case 6:
				return stick;
				break;
			case 7:
				return stone;
				break;
			case 8:
				return leaf;
				break;
			case 9.1:
				return fruit1;
				break;
			case 9.2:
				return fruit2;
				break;
			case 9.3:
				return fruit3;
				break;
			case 9.4:
				return fruit4;
				break;
			case 10:
				return ax;
				break;
			case 11:
				return shovel;
				break;
			case 12:
				return pickaxe;
				break;
			case 13:
				return bowl;
				break;
			case 13.1:
				return bowl2;
				break;
			case 14:
				return campfire;
				break;
			case 15:
				return club;
				break;
			case 16:
				return spear;
				break;
			case 17:
				return bole;
				break;
			case 17.1:
				return bole1;
				break;
			case 17.2:
				return bole2;
				break;
			default:
				alert(id);
				return E;
				break;
		}
	}
	
	function zjedz(id){
		switch (id)
		{
			case 5:
				if(glod<100)
				{
					ekwipunek[select-1][5].ilosc--;
					if(ekwipunek[select-1][5].ilosc==0)ekwipunek[select-1][5].id=0;
					if(glod<91)glod+=10;
					else glod=100;
				}
				break;
			case 9.1:
				if(zycie<100)
				{
					ekwipunek[select-1][5].ilosc--;
					if(ekwipunek[select-1][5].ilosc==0)ekwipunek[select-1][5].id=0;
					if(zycie<91)zycie+=5;
					else zycie=100;
				}
				break;
			case 9.2:
			case 9.3:
			case 9.4:
				if(glod<100)
				{
					ekwipunek[select-1][5].ilosc--;
					if(ekwipunek[select-1][5].ilosc==0)ekwipunek[select-1][5].id=0;
					if(glod<96)glod+=5;
					else glod=100;
				}
				break;
		}
	}

	function pokazMape(){
		for(x=0;x<szer;x++)
			for(y=0;y<wys;y++)
			{
				if(pole[x+pX-10][y+pY-7]>0 && pole[x+pX-10][y+pY-7]<1000)
				{
					if(pole[x+pX-10][y+pY-7]>9 && pole[x+pX-10][y+pY-7]<10)
					{
						
						if(!bushs[x+pX-10][y+pY-7].stan) 
						{
							if(bushs[x+pX-10][y+pY-7].delay<gameTimer)
								bushs[x+pX-10][y+pY-7].stan=true;
							ctx.drawImage(bush,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
						}
						else
							switch(pole[x+pX-10][y+pY-7])
							{
								case 9.1:
									ctx.drawImage(bush1,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
									break;
								case 9.2:
									ctx.drawImage(bush2,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
									break;
								case 9.3:
									ctx.drawImage(bush3,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
									break;
								case 9.4:
									ctx.drawImage(bush4,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
									break;	
							}
					}
					else if(pole[x+pX-10][y+pY-7]>=3 && pole[x+pX-10][y+pY-7]<4)
					{
						ctx.save();
						ctx.translate(0, 0);
						switch(pole[x+pX-10][y+pY-7])
						{
							case 3:
								ctx.drawImage(water, x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3.1:
								ctx.drawImage(water0, x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3.01:
								ctx.drawImage(waterr, x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3.02:
								ctx.rotate(90*Math.PI/180);
								ctx.drawImage(waterr, y*size+(pY-posY)*size, -x*size-(pX-posX)*size-size);
								break;	
							case 3.03:
								ctx.rotate(180*Math.PI/180);
								ctx.drawImage(waterr, -x*size-(pX-posX)*size-size, -y*size-(pY-posY)*size-size);
								break;	
							case 3.04:
								ctx.rotate(270*Math.PI/180);
								ctx.drawImage(waterr, -y*size-(pY-posY)*size-size, x*size+(pX-posX)*size);
								break;	
							case 3.11:
								ctx.drawImage(water1, x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3.12:
								ctx.rotate(90*Math.PI/180);
								ctx.drawImage(water1, y*size+(pY-posY)*size, -x*size-(pX-posX)*size-size);
								break;	
							case 3.13:
								ctx.rotate(180*Math.PI/180);
								ctx.drawImage(water1, -x*size-(pX-posX)*size-size, -y*size-(pY-posY)*size-size);
								break;	
							case 3.14:
								ctx.rotate(270*Math.PI/180);
								ctx.drawImage(water1, -y*size-(pY-posY)*size-size, x*size+(pX-posX)*size);
								break;	
							case 3.21:
								ctx.drawImage(water2, x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3.22:
								ctx.rotate(90*Math.PI/180);
								ctx.drawImage(water2, y*size+(pY-posY)*size, -x*size-(pX-posX)*size-size);
								break;	
							case 3.23:
								ctx.rotate(180*Math.PI/180);
								ctx.drawImage(water2, -x*size-(pX-posX)*size-size, -y*size-(pY-posY)*size-size);
								break;	
							case 3.24:
								ctx.rotate(270*Math.PI/180);
								ctx.drawImage(water2, -y*size-(pY-posY)*size-size, x*size+(pX-posX)*size);
								break;		
							case 3.31:
								ctx.drawImage(water3, x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3.32:
								ctx.rotate(90*Math.PI/180);
								ctx.drawImage(water3, y*size+(pY-posY)*size, -x*size-(pX-posX)*size-size);
								break;	
							case 3.33:
								ctx.rotate(180*Math.PI/180);
								ctx.drawImage(water3, -x*size-(pX-posX)*size-size, -y*size-(pY-posY)*size-size);
								break;	
							case 3.34:
								ctx.rotate(270*Math.PI/180);
								ctx.drawImage(water3, -y*size-(pY-posY)*size-size, x*size+(pX-posX)*size);
								break;		
							case 3.41:
								ctx.drawImage(water4, x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3.42:
								ctx.rotate(90*Math.PI/180);
								ctx.drawImage(water4, y*size+(pY-posY)*size, -x*size-(pX-posX)*size-size);
								break;	
							case 3.43:
								ctx.rotate(180*Math.PI/180);
								ctx.drawImage(water4, -x*size-(pX-posX)*size-size, -y*size-(pY-posY)*size-size);
								break;	
							case 3.44:
								ctx.rotate(270*Math.PI/180);
								ctx.drawImage(water4, -y*size-(pY-posY)*size-size, x*size+(pX-posX)*size);
								break;		
							case 3.51:
								ctx.drawImage(water5, x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3.52:
								ctx.rotate(90*Math.PI/180);
								ctx.drawImage(water5, y*size+(pY-posY)*size, -x*size-(pX-posX)*size-size);
								break;	
							case 3.53:
								ctx.rotate(180*Math.PI/180);
								ctx.drawImage(water5, -x*size-(pX-posX)*size-size, -y*size-(pY-posY)*size-size);
								break;	
							case 3.54:
								ctx.rotate(270*Math.PI/180);
								ctx.drawImage(water5, -y*size-(pY-posY)*size-size, x*size+(pX-posX)*size);
								break;		
						}
						ctx.restore();
					}
					else if(pole[x+pX-10][y+pY-7]==1)
					{
						drzewa[x+pX-10][y+pY-7].rosnij();
						switch(drzewa[x+pX-10][y+pY-7].typ)
						{
							case 1:
								ctx.drawImage(tree1,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 2:
								ctx.drawImage(tree2,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 3:
								ctx.drawImage(tree3,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
							case 4:
								ctx.drawImage(tree4,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
								break;
						}
					}
					else
						ctx.drawImage(nazwaPrzedmiotu(pole[x+pX-10][y+pY-7]), x*size+(pX-posX)*size, y*size+(pY-posY)*size);
				}
				else if(pole[x+pX-10][y+pY-7]>=1000)
				{
					if(mobs[pole[x+pX-10][y+pY-7]].nazwa=="boar")
						ctx.drawImage(boar,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
					else if(mobs[pole[x+pX-10][y+pY-7]].nazwa=="wolf")
						ctx.drawImage(wolf,x*size+(pX-posX)*size, y*size+(pY-posY)*size);
					else if(mobs[pole[x+pX-10][y+pY-7]].nazwa=="mammoth")
						ctx.drawImage(mammoth,x*size+(pX-posX)*size-20, y*size+(pY-posY)*size-20);
				}
			}
		if(rzucenie.stan)
		{
			rzucenie.przesunKamien();
			rzucenie.pokazKamien();
		}
	}

	function pokazEkwipunek(){
		ctx.fillStyle="#3d3922";
		ctx.fillRect(160,120,480,330);
		ctx.fillStyle="#6b643f";
		ctx.fillRect(170,130,460,310);
		
		for(x=0;x<5;x++)
		{
			for(y=0;y<5;y++)
			{
				if(ekwipunek[x][y].klikniecie)ctx.fillStyle="#7f7f7f";
				else ctx.fillStyle="#82794e";
				ctx.fillRect(180+90*x,140+50*y,80,40);
			}
			if(ekwipunek[x][5].klikniecie)ctx.fillStyle="#3d3d3d";
			else ctx.fillStyle="#3d3922";
			ctx.fillRect(180+90*x,390,80,40);
		}
		ctx.fillStyle="#3d3922";
		ctx.font = "25px Arial";
		
		for(x=0;x<5;x++)
			for(y=0;y<6;y++)
				{
					if(ekwipunek[x][y].id)
					{
						ctx.drawImage(nazwaPrzedmiotu(ekwipunek[x][y].id),180+90*x, 140+50*y, 40, 40);
						polX=215;
						if(ekwipunek[x][y].ilosc<10)polX=228;
						if(y==5)ctx.fillStyle="#82794e";
						else ctx.fillStyle="#3d3922";
						ctx.fillText(ekwipunek[x][y].ilosc,polX+90*x,170+50*y);
					}
				}
			if(klikniecie.start)
				ctx.drawImage(nazwaPrzedmiotu(klikniecie.id),cursorX, cursorY, 40, 40);	
	}

	function Slot(id,ilosc,max){
		this.id=id;
		this.ilosc=ilosc;
		this.max=max;
		this.klikniecie=false;
	}

	function mouseMove(e){
		cursorX=e.offsetX-20;
		cursorY=e.offsetY-20;
	}

	function click(){//
		if(eq)
		{
			if(!klikniecie.start)
			{
				for(x=0;x<5;x++)
					for(y=0;y<6;y++)
					{
						if(cursorX>160+90*x && cursorX<160+90*x+80 && cursorY>120+50*y && cursorY<120+50*y+40)
						{
							if(ekwipunek[x][y].id)
							{
								ekwipunek[x][y].klikniecie=true;
								klikniecie.id=ekwipunek[x][y].id;
								klikniecie.start=true;
								klikniecie.poleX=x;
								klikniecie.poleY=y;
							}
							return 0;
						}
					}
			}
			else
			{
				for(x=0;x<5;x++)
					for(y=0;y<6;y++)
					{
						if(cursorX>160+90*x && cursorX<160+90*x+80 && cursorY>120+50*y && cursorY<120+50*y+40)
						{
							pom1=ekwipunek[x][y].id;
							pom2=ekwipunek[x][y].ilosc;
							pom3=ekwipunek[x][y].max;
							ekwipunek[x][y].id=ekwipunek[klikniecie.poleX][klikniecie.poleY].id;
							ekwipunek[x][y].ilosc=ekwipunek[klikniecie.poleX][klikniecie.poleY].ilosc;
							ekwipunek[x][y].max=ekwipunek[klikniecie.poleX][klikniecie.poleY].max;
							ekwipunek[klikniecie.poleX][klikniecie.poleY].id=pom1;
							ekwipunek[klikniecie.poleX][klikniecie.poleY].ilosc=pom2;
							ekwipunek[klikniecie.poleX][klikniecie.poleY].max=pom3;
							ekwipunek[klikniecie.poleX][klikniecie.poleY].klikniecie=false;
							klikniecie.start=false;
							return 0;
						}
					}
			}
		}
		else if(craft)
		{
			for(i=0;i<5;i++)
			{
				if(cursorX>180+90*i && cursorX<260+90*i && cursorY>140 && cursorY<220)
				{
					if(crafting[i+(craftPage-1)*5].canDoIt)
					{
						sticks=crafting[i+(craftPage-1)*5].sticks;
						stones=crafting[i+(craftPage-1)*5].stones;
						leafs=crafting[i+(craftPage-1)*5].leafs;
						for(x=0;x<5;x++)
							for(y=0;y<6;y++)
							{
								if(ekwipunek[x][y].id==6)
								{
									if(ekwipunek[x][y].ilosc>sticks)
									{
										ekwipunek[x][y].ilosc-=sticks;
										sticks=0;
									}
									else
									{
										sticks-=ekwipunek[x][y].ilosc;
										ekwipunek[x][y].ilosc=0;
										ekwipunek[x][y].id=0;
									}
								}
								else if(ekwipunek[x][y].id==7)
								{
									if(ekwipunek[x][y].ilosc>stones)
									{
										ekwipunek[x][y].ilosc-=stones;
										stones=0;
									}
									else
									{
										stones-=ekwipunek[x][y].ilosc;
										ekwipunek[x][y].ilosc=0;
										ekwipunek[x][y].id=0;
									}
								}
								else if(ekwipunek[x][y].id==8)
								{
									if(ekwipunek[x][y].ilosc>leafs)
									{
										ekwipunek[x][y].ilosc-=leafs;
										leafs=0;
									}
									else
									{
										leafs-=ekwipunek[x][y].ilosc;
										ekwipunek[x][y].ilosc=0;
										ekwipunek[x][y].id=0;
									}
								}
							}
						podnies(crafting[i+(craftPage-1)*5].id);
					}
				}
			}
			if(cursorX>560 && cursorX<600 && cursorY>370 && cursorY<410 && craftPage<3)craftPage++;
			else if(cursorX>160 && cursorX<200 && cursorY>370 && cursorY<410 && craftPage>1)craftPage--;
		}
	}

	function Mouse(id,start){
		this.id=id;
		this.poleX=0;
		this.poleY=0;			
		this.start=start;
	}

	function Bush(typ,stan,delay){
		this.typ=typ;
		this.stan=stan;
		this.delay=delay;
		this.podnies = function()
		{
			this.stan=false;
			this.delay=gameTimer+Math.floor(Math.random()*15+5);
		}			
	}
	
	function Drzewo(typ,delay){
		this.typ=typ;
		this.delay=delay;
		
		this.rosnij = function()
		{
			if(this.typ<4 && this.delay<gameTimer)
			{
				this.typ++;
				this.delay=this.delay+Math.floor(Math.random()*20+10);
			}
		}	
	}

	function Rzut(stan,poX,poY,mX,mY,delay){
		this.stan=stan;
		this.posX=poX;
		this.posY=poY;
		this.moveX=mX;
		this.moveY=mY;
		this.delay=delay;
		this.sladDelay=15;
		this.sladX=0;
		this.sladY=0;
		
		this.start = function()
		{
			ekwipunek[select-1][5].ilosc--;
			if(ekwipunek[select-1][5].ilosc==0)ekwipunek[select-1][5].id=0;
			this.stan=1;
			this.moveX=0;
			this.moveY=0;
			this.sladDelay=0;
			this.posX=posX-0.5;
			this.posY=posY-1;
			if(moveX>0 || ost==2)
				this.moveX=0.14;
			if(moveX<0 || ost==1)
				this.moveX=-0.14;
			if(moveY>0 || ost==0)
				this.moveY=0.14;
			if(moveY<0 || ost==3)
				this.moveY=-0.14;
			if((moveX!=0 && moveY!=0)||(moveX==0 && moveY==0))
			{
				this.moveX*=0.72;
				this.moveY*=0.72;
			}
			//if(this.moveX==0)this.moveX=((Math.random()*3)-1)/100;
			//else if(this.moveY==0)this.moveY=((Math.random()*3)-1)/100;
			this.delay=Math.floor(Math.random()*20)+70;
		}
		
		this.przesunKamien = function()
		{
			ppX=Math.round(this.posX);
			ppY=Math.round(this.posY);
			if(this.delay<=0)
			{
				if(pole[ppX][ppY]>=3 && pole[ppX][ppY]<4 && this.stan==1)
				{
					los=Math.random();
					if(los<0.6 && (this.moveX>0.08 || this.moveX<-0.08 || this.moveY<-0.08 || this.moveY>0.08))
					{
						if(this.moveX<0.08 && this.moveX>-0.08)
						{
							
							if(this.moveY>0)
							{
								this.moveY-=(Math.floor(Math.random()*3))/100;
								this.delay+=200*this.moveY;
							}
							else 
							{
								this.moveY+=(Math.floor(Math.random()*3))/100;
								this.delay+=-200*this.moveY;
							}
							this.moveX+=(Math.floor(Math.random()*3)-1)/100;
						}
						else if(this.moveY<0.08 && this.moveY>-0.08)
						{
							
							if(this.moveX>0)
							{
								this.moveX-=(Math.floor(Math.random()*3))/100;
								this.delay+=200*this.moveX;
							}
							else 
							{
								this.moveX+=(Math.floor(Math.random()*3))/100;
								this.delay+=-200*this.moveX;
							}
							this.moveY+=(Math.floor(Math.random()*3)-1)/100;
						}
					}
					else 
					{
						this.stan=2;
					}
					this.sladDelay=20;
					this.sladX=this.posX;
					this.sladY=this.posY;
				}
				else if(pole[ppX][ppY]==0)
				{
					pole[ppX][ppY]=7;
					this.stan=2;
				}
				if(this.sladDelay<=0)
					this.stan=0;
			}
			else if(pole[ppX][ppY]>999)
			{
				Id=pole[ppX][ppY];
				los=Math.round(Math.random()*3);
				mobs[Id].live-=los;
				if(mobs[Id].live<=0)
				{
					mobs[Id].destroy;
					pole[ppX][ppY]=5;
				}
				mobHit.styl=0;
				mobHit.x = (410+(mobs[Id].Px-posX)*size);
				mobHit.y = (290+(mobs[Id].Py-posY)*size);
				mobHit.text = "-"+los;
				mobHit.pokaz = true;
				mobHit.animPost = false;
				this.stan=0;
				this.sladDelay=0;
				this.delay=0;
				this.moveX=0;
				this.moveY=0;
			}
			else
			{
				this.posX+=this.moveX;
				this.posY+=this.moveY;
			}
			this.delay--;
		}
		this.pokazKamien = function()
		{
			if(this.sladDelay>0)
			{
				ctx.drawImage(splash, 400+(this.sladX-posX)*size, 280+(this.sladY-posY)*size);
				this.sladDelay--;
			}
			if(this.stan==1)
				ctx.drawImage(stone, 400+(this.posX-posX)*size, 280+(this.posY-posY)*size);
		}
	}

	function CraftingSlot(name,id,page,sticks,stones,leafs){//
		this.name=name;
		this.id=id;
		this.page=page;
		this.sticks=sticks;
		this.stones=stones;
		this.leafs=leafs;
		this.canDoIt=false;		
		
		this.canDo = function(sticks,stones,leafs)
		{
			if(this.sticks<=sticks && this.stones<=stones  && this.leafs<=leafs)
			{
				this.canDoIt=true;	
				return true;	
			}
			this.canDoIt=false;	
			return false;
		}
	}
	
	function createCraftingSlot(){//
		crafting[0]=new CraftingSlot(ax,10,1,3,3,0);
		crafting[1]=new CraftingSlot(shovel,11,1,3,3,0);
		crafting[2]=new CraftingSlot(pickaxe,12,1,3,5,0);
		crafting[3]=new CraftingSlot("",0,1,0,0,0);
		crafting[4]=new CraftingSlot("",0,1,0,0,0);
		
		crafting[5]=new CraftingSlot(bowl,13,2,0,0,40);
		crafting[6]=new CraftingSlot(campfire,14,2,10,0,20);
		crafting[7]=new CraftingSlot("",0,2,0,0,0);
		crafting[8]=new CraftingSlot("",0,2,0,0,0);
		crafting[9]=new CraftingSlot("",0,2,0,0,0);
		
		crafting[10]=new CraftingSlot(club,15,3,6,0,0);
		crafting[11]=new CraftingSlot(spear,16,3,6,2,0);
		crafting[12]=new CraftingSlot("",0,3,0,0,0);
		crafting[13]=new CraftingSlot("",0,3,0,0,0);
		crafting[14]=new CraftingSlot("",0,3,0,0,0);
	}
	
	function animacja(id){
		frameXpos = (Math.round(animacjaStan-1))*40;
		switch(id)
		{
			case 0:
				ctx.drawImage(postacgif, frameXpos, 0, 40, 40, 378, 243, 40, 40);
				break;
			case 1:
				ctx.drawImage(postaclgif, frameXpos, 0, 40, 40, 378, 243, 40, 40);
				break;
			case 2:
				ctx.drawImage(postacpgif, frameXpos, 0, 40, 40, 378, 243, 40, 40);
				break;
			case 3:
				ctx.drawImage(postactgif, frameXpos, 0, 40, 40, 378, 243, 40, 40);
				break;
			case 4:
				ctx.drawImage(zbieraniegif, frameXpos, 0, 40, 40, 378, 243, 40, 40);
				break;
			case 5:
				ctx.drawImage(postacwpgif, frameXpos, 0, 40, 40, 378, 243, 40, 40);
				break;
		}
		animacjaStan+=animacjaSpeed;
		if(animacjaStan>3.4)
		{
			if(id==4)animacjaPodnies=false;
			animacjaStan=0.5;
			animacjaSpeed=0.2;
		}
	}
	
	function pokazCrafting(){//
		sticks=0;
		stones=0;
		leafs=0;
		for(x=0;x<5;x++)
			for(y=0;y<6;y++)
			{
				if(ekwipunek[x][y].id==7)stones+=ekwipunek[x][y].ilosc;
				else if(ekwipunek[x][y].id==6)sticks+=ekwipunek[x][y].ilosc;
				else if(ekwipunek[x][y].id==8)leafs+=ekwipunek[x][y].ilosc;
			}
				
		ctx.fillStyle="#3d3922";
		ctx.fillRect(160,120,480,330);
		ctx.fillStyle="#6b643f";
		ctx.fillRect(170,130,460,310);
		plus=(craftPage-1)*5;
		
		for(x=0;x<5;x++)
			if(crafting[x+plus].name!="")
			{
				if(crafting[x+plus].canDo(sticks,stones,leafs)) ctx.fillStyle="#82794e";
				else ctx.fillStyle="#7f7f7f";
				ctx.fillRect(180+x*90,140,80,80);
				ctx.drawImage(crafting[x+plus].name,180+x*90, 140);
			}
		ctx.save();
		ctx.rotate(180*Math.PI/180);
		ctx.drawImage(strz,-220,-430);
		ctx.restore();
		ctx.drawImage(strz,580,390);
	}
	
	function zapisz(){
		var eqSave="";
		for(y=0;y<6;y++)
			for(x=0;x<5;x++)
				{
					eqSave+=ekwipunek[x][y].id;
					eqSave+="|";
					eqSave+=ekwipunek[x][y].ilosc;
					eqSave+="|";
					eqSave+=ekwipunek[x][y].max;
					eqSave+="_";
				}
				
		var mapSave="";
		for(x=0;x<mapaSzer;x++)
			for(y=0;y<mapaWys;y++)
			{
				mapSave+=pole[x][y];
				mapSave+="|";
			}
		document.getElementById("abc").value=mapSave;
		
		var xhttp = new XMLHttpRequest();
		var url = 'zapis.php?e='+eqSave+'&z='+zycie+'&g='+glod+'&p='+pragnienie+'&x='+posX+'&y='+posY+'&m=x';
		xhttp.open("GET", url, true);
		xhttp.send();
		alert("Zapisano pomyślnie.");
		
		//url = 'zapis2.php?m='+mapSave;
		//xhttp.open("GET", url, true);
		//xhttp.send();
		//alert(url);
	}
	
	function mapSave()
	{
		var mapSave="";
			for(y=0;y<8156;y++)
				{
					mapSave+="9";
				}
		return mapSave;
	}
	
	function wczytaj(){
		var wczytane = (
		<?php
			$id=$_SESSION['id_profil'];
			//$serwer = @mysqli_connect('mysql.cba.pl','ciosy','1qaz@WSX','ciosy') or die("Błąd połączenia z serwerem");
			$serwer = @mysqli_connect('localhost','root','','ciosy');
			$x = @mysqli_fetch_object(mysqli_query($serwer,"SELECT * FROM postac WHERE id_postaci=$id"));
			$eq=$x->ekwipunek;
			$zycie=$x->zycie;
			$glod=$x->glod;
			$pragnienie=$x->pragnienie;
			echo "'";
			echo $eq;
			echo "'";
		?>		
		);
		zycie=<?php echo $x->zycie; ?>;
		glod=<?php echo $x->glod; ?>;
		pragnienie=<?php echo $x->pragnienie; ?>;
		<?php @mysqli_close($serwer); ?>
		x=0;
		y=0;
		id="";
		num=1;
		for(i=0;i<wczytane.length;i++)
		{
			if(wczytane[i]=="_")
			{
				ekwipunek[x][y].max=parseFloat(id);
				id="";
				x++;
				if(x==5)
				{
					x=0;
					y++;
				}
			}
			else if(wczytane[i]=="|")
			{
				if(num==1)
				{
					ekwipunek[x][y].id=parseFloat(id);
					id="";
					num++;
				}
				else
				{
					num=1;
					ekwipunek[x][y].ilosc=parseFloat(id);
					id="";
				}
			}
			else
			{
				id+=wczytane[i];
			}
		}
	}

	function interakcja(id){
		pX=Math.round(posX-0.49);
		pY=Math.round(posY-0.79);
		switch(id){
			case 10:
				if(pole[pX][pY]==1)
				{
					switch(drzewa[pX][pY].typ)
					{
						case 1:
							pole[pX][pY]=6;
							break;
						case 2:
							pole[pX][pY]=17;
							break;
						case 3:
							pole[pX][pY]=17.1;
							break;
						case 4:
							pole[pX][pY]=17.2;
							break;
					}
				}
				break;
			case 12:
				if(pole[pX][pY]==2)
					pole[pX][pY]=7;
				break;
			case 13:
				if(pole[pX][pY]>=3 && pole[pX][pY]<4)
					ekwipunek[select-1][5].id=13.1;
				break;
			case 13.1:
				if(pragnienie<100)
				{
					pragnienie+=20;
					if(pragnienie>100)pragnienie=100;
					ekwipunek[select-1][5].id=13;
				}
				break;
		}
	}
	
	function Round(n, k){
	factor = Math.pow(10, k);
	return Math.round(n*factor)/factor;
	}

</script>
</body>