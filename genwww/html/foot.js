//收藏本站
function AddFavorite(title, url) 
{
    try 
	{
        window.external.addFavorite(url, title);
    }
    catch (e) 
	{
        try 
		{
            window.sidebar.addPanel(title, url, "");
        }
        catch (e) 
		{
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
};

//window.onload = function ()
//{
//	iframe_say.window.location.reload();
//};

window.onscroll = function()
{
	var h =document.body.scrollTop,top = document.getElementById('goTopButton');
	if(h>0)
	{
		top.style.display = 'block';
	}
	else
	{
		top.style.display = 'none';
	}
}