function countdown()
{
	date = new Date();
	now = servertime + Math.round(date.getTime() / 1000) - loadtime;
	for (i = 0; i < cdarray.length; i++)
	{
		tg = document.getElementById(cdarray[i]);
		fin = tg.title;
		s = fin - now;
		m = 0;
		h = 0;
		d = 0;
		if(s < 0)
		{
			tg.innerHTML = "-";
		}
		else
		{
			if(s > 59)
			{
				m = Math.floor(s / 60);
				s = s - m * 60;
			}
			if(m > 59)
			{
				h = Math.floor(m / 60);
				m = m - h * 60;
			}
			if(h > 23)
			{
				d = Math.floor(h / 24);
				h = h - d * 24;
			}
			if(s < 10)
			{
				s = "0" + s;
			}
			if(m < 10)
			{
				m = "0" + m;
			}
			if(h < 10)
			{
				h = "0" + h;
			}
			tg.innerHTML = d + ":" + h + ":" + m + ":" + s + "";
		}
	}
	window.setTimeout("countdown();", 999);
}

function printtime(tg, t)
{
	date = new Date(1000 * t);

	s = date.getSeconds();
	m = date.getMinutes();
	h = date.getHours();
	if (s < 10)
	{
		s = "0" + s;
	}
	if (m < 10)
	{
		m = "0" + m;
	}
	if (h < 10)
	{
		h = "0" + h;
	}
	x = h + ":" + m + ":" + s + "";
	if((date.getDate() != loaddate.getDate()) || (date.getMonth() != loaddate.getMonth()))
	{
		d = date.getDate();
		if(d < 10)
		{
			d = "0" + d;
		}
		o = date.getMonth() + 1;
		if(o < 10)
		{
			o = "0" + o;
		}
		x = d + ". " + o + ". " + date.getFullYear() + " " + x;
	}
	document.getElementById(tg).innerHTML = x;
}

function toggledisplay(s)
{
	tg = document.getElementById(s);
	if(tg.style.display == "none")
	{
		tg.style.display = "block";
	}
	else
	{
		tg.style.display = "none";
	}
}

loaddate = new Date();
loadtime = Math.round(loaddate.getTime() / 1000);
cdarray = new Array();
