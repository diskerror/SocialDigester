$(function() {

	var cloudOptions = {
		autoResize: true,
		delay: 0
	};

	$('#cloud1').jQCloud(
		[
			{text: 'Politicator.com', weight: 10},
			{text: 'What ARE they saying?', weight: 5},
			{text: 'Twitter!', weight: 4},
			{text: ' ', weight: 1}
		],
		cloudOptions);

	$('#cloud2').jQCloud(
		[
			{text: 'Politicator.com', weight: 10},
			{text: 'SAY WHAT?', weight: 5},
			{text: 'Twitter!', weight: 4},
			{text: ' ', weight: 1}
		],
		cloudOptions);

	setTimeout(getHashtags, 1400);
	function getHashtags() {
		setTimeout(getHashtags, 3400);	//	milliseconds between cloud updates

		// (function(i, s, o, g, r, a, m) {
		// 	i['GoogleAnalyticsObject'] = r;
		// 	i[r] = i[r] || function() {
		// 		(i[r].q = i[r].q || []).push(arguments)
		// 	}, i[r].l = 1 * new Date();
		// 	a = s.createElement(o);
		// 	m = s.getElementsByTagName(o)[0];
		// 	a.async = 1;
		// 	a.src = g;
		// 	m.parentNode.insertBefore(a, m)
		// })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
		// ga('create', 'UA-76466106-1', 'auto');
		// ga('send', 'pageview');

		$.ajax({
			url: '/index/tagCloud',
			dataType: 'json',
			success: function(data) {
				if (data.length !== 0) {
					$('#cloud1').jQCloud('update', data);
				} else {
					$('#cloud1').jQCloud('update', [
						{text: 'I’m sleeping...', weight: 10},
						{text: ' « snore » ', weight: 6},
						{text: ' ', weight: 1}
					]);
				}
				$('[title!=""]').qtip({style: {classes: 'qtip-rounded'}, show: {solo: true}});//.reposition(true);
			}
		});
	};


	setTimeout(getAllHashtags, 1600);
	function getAllHashtags() {
		setTimeout(getAllHashtags, 3500);	//	milliseconds between cloud updates

		$.ajax({
			url: '/index/tagCloudAll',
			dataType: 'json',
			success: function(data) {
				if (data.length !== 0) {
					$('#cloud2').jQCloud('update', data);
				} else {
					$('#cloud2').jQCloud('update', [
						{text: 'I’m sleeping...', weight: 10},
						{text: ' « snore » ', weight: 6},
						{text: ' ', weight: 1}
					]);
				}
				$('[title!=""]').qtip({style: {classes: 'qtip-rounded'}, show: {solo: true}});//.reposition(true);
			}
		});
	};


	setTimeout(getTextWords, 1800);
	function getTextWords() {
		setTimeout(getTextWords, 2300);	//	milliseconds between cloud updates
		$.ajax({
			url: '/index/textwords',
			dataType: 'json',
			success: function(data) {
				if (data.length !== 0) {
					$('#text_words').jQCloud('update', data);
				} else {
					$('#text_words').jQCloud('update', [
						{text: 'I’m sleeping...', weight: 10},
						{text: ' « snore » ', weight: 6},
						{text: ' ', weight: 1}
					]);
				}
				$('[title!=""]').qtip({style: {classes: 'qtip-rounded'}, show: {solo: true}});//.reposition(true);
			}
		});
	};


	setTimeout(getUserMentions, 2000);
	function getUserMentions() {
		setTimeout(getUserMentions, 4300);	//	milliseconds between cloud updates

		$.ajax({
			url: '/index/usermentions',
			dataType: 'json',
			success: function(data) {
				if (data.length !== 0) {
					$('#user_mentions').jQCloud('update', data);
				} else {
					$('#user_mentions').jQCloud('update', [
						{text: 'I’m sleeping...', weight: 10},
						{text: ' « snore » ', weight: 6},
						{text: ' ', weight: 1}
					]);
				}
				$('[title!=""]').qtip({style: {classes: 'qtip-rounded'}, show: {solo: true}});//.reposition(true);
			}
		});
	};


	setTimeout(getRetweets, 2800);

	function getRetweets() {
		setTimeout(getRetweets, 8300);	//	milliseconds between cloud updates

		$.ajax({
			url: '/index/retweets',
			dataType: 'json',
			success: function(data) {
				if (data.length !== 0) {
					console.log(data);
					$('#retweets').jQCloud('update', data);
				} else {
					$('#retweets').jQCloud('update', [
						{text: 'I’m sleeping...', weight: 10},
						{text: ' « snore » ', weight: 6},
						{text: ' ', weight: 1}
					]);
				}
				$('[title!=""]').qtip({style: {classes: 'qtip-rounded'}, show: {solo: true}});//.reposition(true);
			}
		});
	};


	setTimeout(getUsers, 3100);

	function getUsers() {
		setTimeout(getUsers, 9400);	//	milliseconds between cloud updates

		$.ajax({
			url: '/index/users',
			dataType: 'json',
			success: function(data) {
				if (data.length !== 0) {
					$('#users').jQCloud('update', data);
				} else {
					$('#users').jQCloud('update', [
						{text: 'I’m sleeping...', weight: 10},
						{text: ' « snore » ', weight: 6},
						{text: ' ', weight: 1}
					]);
				}
				$('[title!=""]').qtip({style: {classes: 'qtip-rounded'}, show: {solo: true}});//.reposition(true);
			}
		});
	};


	setTimeout(getSummary, 900);

	function getSummary() {
		setTimeout(getSummary, 14000);

		$.ajax({
			url: '/index/summary',
			dataType: 'json',
			success: function(data) {
				$('#text1').html('<p>' + data.join('</p><p>') + '</p>');
			}
		});
	};

});

function ToTwitter(hashtags) {
	window.open('https://twitter.com/search?f=tweets&vertical=news&q=%23' + hashtags.join('%20OR%20%23'), '_blank');
};

function ToTwitterAt(users) {
	window.open('https://twitter.com/search?f=tweets&vertical=news&q=%40' + users.join('%20OR%20%40'), '_blank');
};
