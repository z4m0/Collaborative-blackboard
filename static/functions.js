function compare() {
	text = $("#pissarra").val();
	var lines = new Array();
	lines = text.split('\n');
	var count = 0;
	for (i = 0; i < lines.length; i++) {
		pos = search(lines[i], cache);
		if (pos >= 0) {
			// move a line
			if (i != pos) {
				changesid[count] = i;
				changesloc[count] = pos;
				changes[count] = null;
				count++;
			}
		} else {
			// new line
			changesid[count] = i;
			changesloc[count] = null;
			changes[count] = lines[i];
			count++;
		}
	}
	cache = lines;

}
function search(string, array) {
	var i = 0;
	var found = false;
	while (!found && i < array.length) {
		if (array[i] == string) {
			found = true;
			return i;
		}
		++i;
	}
	return -1;
}
var started = false;
function sendData() {
	var time = new Date();
	changes = new Array();
	changesid = new Array();
	changesloc = new Array();
	compare();
	var size = cache.length;
	$.post("index.php?send ", {
		'from' : time.getTime(),
		'id' : changesid,
		'loc' : changesloc,
		'lines' : changes,
		'size' : size
	});
	$("#pissarra").focusout(function() {
		focused = false;
		if (!started) {
			// reciveData();
		}
	});
	date = new Date();
	lastUpdate = date.getTime();
	if (focused) {
		window.setTimeout(sendData, 1500);
	}
}
var count = 0;
function reciveData() {
	started = true;
	// console.log(started);
	if (!focused || focused) {// lol tautology I will change it
		count++;
		var schanges = new Array();
		var schangesid = new Array();
		// console.log("focused: " + focused);
		$.getJSON("index.php?wall", {
			'from' : lastUpdate
		}, function(json) {
			var content = '';
			first = true;
			if (json.lines) {
				$.each(json.lines, function(i, line) {
					schanges[i] = line.text;
					schangesid[i] = line.id;
				});
				for (i = 0; i < schanges.length; i++) {
					cache[schangesid[i] - 1] = schanges[i];
				}
				for (i = 0; i < cache.length; i++) {
					if (!first)
						content += '\n';
					else
						first = false;
					if (cache[i]) {
						content += cache[i];
					}
				}
				for (i = json.size; i < cache.length; i++) {
					// console.log(i);
					delete cache[i];
				}
				// console.log(schanges);
				// console.log(cache);
				//console.log(content);
				$("#pissarra").text(content);
			}
		});
		/*
		 * $("#pissarra").focusin(function() { focused = true; sendData(); });
		 */

		// console.log(count);
		count--;
		date = new Date();
		lastUpdate = date.getTime();
		if (started) {
			if(focused)
				window.setTimeout(reciveData, 5000);
			else
			window.setTimeout(reciveData, 1500);
		}
	}
}