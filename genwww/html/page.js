function showhide(id1,id2,tx1,tx2)
{

	var id1div = document.getElementById(id1);
	var id2div = document.getElementById(id2);
	if(id2div.style.display=='block')
	{
		id2div.style.display='none';
		id1div.innerHTML=tx1;
	}
	else if(id2div.style.display=='none')
	{
		id2div.style.display='block';
		id1div.innerHTML=tx2;
	}
};