/**
 * 更新图片验证码。原理是原理是参数随机数的值不同，则从后端返回的验证码也不同
 * @param {Object} selector 图片盒子选择器
 * 
 */
function Refresh_code(selector) {
	//方法一
	var img_src = $(selector).attr('src');//获取图片img标签的属性src的值，即图片地址
	if (img_src.indexOf("?")> 0) {//检测有无问号，若有则不是第一次刷新
		//indexOf() 方法可返回某个指定的字符串值在字符串中首次出现的位置。
		img_arr = img_src.split("?");
		img_src = img_arr[0] + "?random=" + Math.random();//random函数返回一个0~num-1之间的随机数
	} else{//检测有无问号，若无 则不是第一次刷新
		img_src = img_src + "?random=" + Math.random();
	}
	$(selector).attr('src', img_src);//把拼接后的图片地址重新放到图片中去，让其重新以图片地址向后端获取新验证码
	
	//方法二
	// var ts=Date.parse(new Date())/1000;
	// $(selector).attr('src', '/captcha?id='+ts);
}