$(function() {

	var cloudOptions = {
		autoResize: true,
		delay: 0
	};

	var doRun = false;

	$('#cloud1').jQCloud([{text: 'Politicator.com', weight: 10}, {
		text: 'What ARE they saying?',
		weight: 5
	}, {text: 'Twitter!', weight: 4}, {text: ' ', weight: 1}], cloudOptions);

	(function getHashtags() {
		if (!doRun) {
			doRun = true;
			setTimeout(getHashtags, 1800);
			return;
		}

		(function(i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function() {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
		ga('create', 'UA-76466106-1', 'auto');
		ga('send', 'pageview');

		$.ajax({
			url: '/index/tagcloud',
			dataType: 'json',
			success: function(data) {
				$('#cloud1').jQCloud('update', data);
				$('[title!=""]').qtip({style: {classes: 'qtip-rounded'}, show: {solo: true}});//.reposition(true);
			},
			complete: function() {
				setTimeout(getHashtags, 3500);	//	milliseconds between cloud updates
			}
		});
	})();

	(function getSummary() {
		if (!doRun) {
			setTimeout(getSummary, 4000);
			return;
		}

		$.ajax({
			url: '/index/summary',
			dataType: 'json',
			success: function(data) {
				$('#text1').html('<p>' + data.join('</p><p>') + '</p>');
			},
			complete: function() {
				setTimeout(getSummary, 9000);	//	milliseconds between text updates
			}
		});
	})();

});

$(function() {
	$("#tag-count-slider").slider({
		orientation: "vertical",
		range: "min",
		min: 20,
		max: 120,
		value: 100,
		slide: function(event, ui) {
			$("#tag-count").val(ui.value);
		}
	});
	$("#tag-count").val($("#tag-count-slider").slider("value"));
});

$(function() {
	$("#update-seconds-slider").slider({
		orientation: "vertical",
		range: "min",
		min: 1000,
		max: 10000,
		value: 3500,
		slide: function(event, ui) {
			$("#update-seconds").val(ui.value);
		}
	});
	$("#update-seconds").val($("#update-seconds-slider").slider("value") / 1000);
});

function ToTwitter(hashtags) {
	window.open('https://twitter.com/search?f=tweets&vertical=news&q=%23' + hashtags.join('%7c%23'), '_blank');
};
