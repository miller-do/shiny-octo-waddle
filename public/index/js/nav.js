$(function(){
	var current_url = '__SELF__';
	console.log(current_url);
	$('header>ul>li>a[href="'+current_url+'"]').addClass('active');
})