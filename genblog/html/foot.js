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
};

function cnzz()
{
	(function() {
		var cnzz = document.createElement('script');
		cnzz.type = 'text/javascript';
		cnzz.src = 'http://s22.cnzz.com/z_stat.php?id=1000362336&web_id=1000362336';
		(document.getElementsByTagName('body')[0]
		||document.getElementsByTagName('head')[0]).appendChild(cnzz);
	})();
};
	
function duoshuo()
{
	//多说
	(function() {
		var ds = document.createElement('script');
		ds.type = 'text/javascript';
		ds.async = false;
		ds.src = 'http://static.duoshuo.com/embed.js';
		ds.charset = 'UTF-8';
		(document.getElementsByTagName('body')[0]
		|| document.getElementsByTagName('head')[0]).appendChild(ds);
	})();
};
	
function dhsay()
{
	var dh_say_content =new Array(
		'当你的才华还撑不起你 的野心时，那你就应该静下心来学习！',
		'民主制度的特点是公开性，专制制度的特点是神秘性。',
		'快乐，不在繁华热闹中，而在内心宁静里。',
		'穷则独善其身，富则妻妾成群。',
		'政治人物都是为了国家，不同的是，有人为国，有人为家。',
		'自己选择的路跪着也要走完。',
		'自己不勇敢就没人替你坚强。',
		'宁愿跑起来被绊倒无数次，也不愿规规矩矩走一辈子。',
		'久利之事勿为，众争之地勿往。',
		'食能止饥，饮能止渴，畏能止祸，足能止贪。',
		'好便宜不可与共财，狐疑者不可与共事。',
		'快乐与贫富无关，与内心相连。',
		'忍辱负重，顺其自然。',
		'贱不谋贵，外不谋内，疏不谋亲。',
		'君子与小人斗，小人必胜。',
		'我们可以爱一个人爱到不要命，但是我们绝不能爱一个人爱到不要脸。',
		'人生最大的悲哀，莫过于青春已不再，青春痘还在！',
		'钱财就象雨水，没有会渴死，多了会淹死。',
		'工作就像爱人，虽然经常烦恼，但没有会更烦恼。',
		'只要你按时到达目的地，很少有人在乎你开的是奔驰还是手扶拖拉机。',
		'不一定选择正确的，而应选择你不后悔的。',
		'一个人虽然可以选择许多路，但不能同时走两条路。',
		'一步登不上高山，但一步不慎，却能从悬崖上掉下来。',
		'向昨天要经验，向今天要成果，向明天要动力。',
		'人生道路上的每一个里程碑，都刻着两个字“起点”。',
		'昨天越来越多，明天越来越少。',
		'是狼就炼好牙，是羊就炼好腿。',
		'让朋友低估你的优点，让敌人高估你的缺点。',
		'你只看见内涵的博文,却没有看见我夜以继日的编辑。',
		'成功的人不是赢在起点，而是赢在转折点。',
		'人生没有如果，只有后果和结果。',
		'人生就八个字：喜怒哀乐忧愁烦恼，八个字里喜和乐只占两个，看透就好了。',
		'少年的时光就是晃，用大把时间彷徨，只用几个瞬间来成长。',
		'满脑子只有钱的人 一辈子都赚不到钱。',
		'知识就像内裤，看不见，但很重要。',
		'英雄难过美人关，我不是英雄，美人让我过了关。',
		'探索的旅程不在于发现新大陆，而在于培养新视角。',
		'我可以选择放弃，但不可以放弃选择',
		'咸鱼翻身，还是咸鱼！',
		'叹气是最浪费时间的事情，哭泣是最浪费力气的行径。',
		'不是人人都能活得低调，可以低调的基础是随时都能高调。',
		'长得这么帅干什么？又不能用脸来刷卡！',
		'谈恋爱就像剥洋葱，总有一层会让你流眼泪。',
		'成功的男人能够挣到比妻子花的更多的钱，成功的女人能找到这样的男人。',
		'年轻时候拍下许多照片给别人看；等到老了才明白照片是拍给自己看的。',
		'如果你经常不按时吃饭，就要按时吃药了。',
		'微小的幸福就在身边，容易满足就是天堂。',
		'成功有个副作用，就是以为过去的做法同样适用于将来。',
		'喜欢一个人，就是在一起时很开心，爱一个人，就是即使不开心，也想在一起。',
		'感觉不到痛苦的爱情，不是真正的爱情，感觉不到幸福的婚姻，必是悲哀的婚姻。',
		'人之所以活得累，是因为放不下架子，撕不开面子，解不开情节。',
		'人不可以把钱带进坟墓，但钱可以把人带进坟墓',
		'出发之前永远是梦想，上路后永远是挑战。',
		'很多时候，看的太透反而不快乐，还不如幼稚的没心没肺。',
		'成长是一场残忍的掠夺，抢去天真，夺走单纯。',
		'每一个伟大，多会有争议；每一次完美，都有无穷的缺憾。',
		'真正成功的人生在于是否努力地实现自己的目标，是否充实而快乐地活着！',
		'别忘了答应自己要做的事情，别忘了答应自己要去的地方，无论有多难，有多远。',
		'学着做你自己，并优雅地放手所有不属于你的东西。',
		'不要去欺骗别人，因为你能骗到的人，都是相信你的人！',
		'人生最可怕的事情，就是比你聪明的人还比你勤奋。',
		'当你感到失落，无聊，无趣时，来这里看看 :-)',
		'“勤奋”是穷人通向成功最便捷的路。');
	var random = Math.floor(Math.random()*dh_say_content.length);
	var randomcontent = dh_say_content[random];
	var dhsay=document.getElementById('dhsay');
	dhsay.innerText=randomcontent;
}	
	
window.onload = function ()
{
	cnzz();
	duoshuo();
	dhsay();
	//iframe_say.window.location.reload();
};