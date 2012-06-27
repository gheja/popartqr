<html>
	<head>
		<title>Popart QR code - almost pre-alpha</title>
		<script type="text/javascript">
			var timer, last_url = "";
			function update()
			{
				var url = "qr.php?brightness=" + document.getElementById("brightness").value +
					"&contrast=" + document.getElementById("contrast").value +
					"&seed=" + document.getElementById("seed").value +
					"&text=" + document.getElementById("text").value +
					"&inverted=" + (document.getElementById("inverted").checked ? "1" : "0");

				if (url == last_url)
				{
					return;
				}

				document.getElementById("current_url").value = url;
				document.getElementById("qr").src = url;
				last_url = url;
			}
			
			function changed()
			{
				if (timer != null)
				{
					window.clearTimeout(timer);
				}
				timer = window.setTimeout("update();", 100);
			}
			
			function randomize()
			{
				document.getElementById("seed").value = Math.floor(Math.random() * 10000000);
				update();
			}
			window.onload = update;
		</script>
	</head>
	<body>
		<form name="form1" method="get" onsubmit="return false;">
			<label for="brightness">Brightness (0..100): </label><input id="brightness" type="text" value="80"  onchange="changed();" onkeyup="changed();"/><br/>
			<label for="contrast">Contrast (0..100): </label><input id="contrast" type="text" value="30"  onchange="changed();" onkeyup="changed();"/><br/>
			<label for="seed">Seed (0...): </label><input id="seed" type="text" value=""  onchange="changed();" onkeyup="changed();"/> <input type="button" value="!" onclick="randomize(); return false;"><br/>
			<label for="text">Text: </label><input id="text" type="text" value="http://qr.x0.hu/" onchange="changed();" onkeyup="changed();"/><br/>
			<label for="inverted">Inverted: </label><input id="inverted" type="checkbox" value="1" onchange="changed();" onkeyup="changed();" onclick="changed();"/><br/>
			<input type="text" readonly="readonly" id="current_url" />
		</form>
		<img id="qr" />
		<hr/>
		<a href="https://github.com/gheja/popartqr">Source code</a> hosted on <a href="http://github.com/">Github</a>. | Powered by <a href="https://github.com/t0k4rt/phpqrcode">PHP QR Code</a>.
	</body>
</html>
