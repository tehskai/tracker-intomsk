	function add_image_field(i) 
	{
		var place = document.getElementById('f' + i + '_place');
		if(!place) return;

		var cnt = document.getElementById('f' + i + '_cnt');
		if(!cnt) return;
		
		place.innerHTML += '<br> <input type="file" name="f' + i + '_' + cnt.value + '" size="70" />';
		
		cnt.value++;
	}
	
	function show_image(obj, p) 
	{
		if( p != '' ) add_image_field(p);
	
		if(navigator.appName != 'Microsoft Internet Explorer') return;
		
		var id = 'i' + obj.name;
		var img = document.getElementById( id );
		if(!img) return;

		img.style.display = 'block';	
		img.src = 'file://' + obj.value;
	}
	
	function showhide( id )
	{
		var el = document.getElementById(id);
		if(!el) return;
		
		if( el.style.display == 'none' ) {
			el.style.display = 'block';
		} else {
			el.style.display = 'none';
		}
	}

function wopen( sUri, iWidth, iHeight, ret )
{
	var sWindowName = 'win' + Math.floor( Math.random()*1000 );
	var iRealWidth = iWidth ? iWidth : 600;
	var iRealHeight = iHeight ? iHeight : screen.height - 300;
	
	if(iRealWidth > screen.width) {
		iRealWidth = screen.width
	}
	
	if(iRealHeight > screen.height) {
		iRealHeight = screen.height;
	}

	var iLeft = Math.round( (screen.width-iRealWidth)/2 );
	var iTop =  Math.round( (screen.height-iRealHeight)/2 ) - 35;

	var sWindowOptions = 'status=no,menubar=yes,toolbar=no';
	sWindowOptions += ',resizable=yes,scrollbars=yes,location=no';
	sWindowOptions += ',width='  + iRealWidth;
	sWindowOptions += ',height=' + iRealHeight;
	sWindowOptions += ',left='   + iLeft;
	sWindowOptions += ',top='    + iTop;

	var oWindow = window.open( sUri, sWindowName, sWindowOptions );
	oWindow.focus();

	return ret ? oWindow : false;
}


var ml_vals = Array();
function mlCheckForLast (name)
{
	if (document.getElementById) {
		var btns = document.getElementsByName('drop_'+name);
		for (var i = 0; i < btns.length; i ++) {
			btns[i].disabled = (btns.length == 1) ? true : false;
		}
	}
}
function mlAddVal (btn)
{
	if (document.getElementById) {
		var name = btn.name.substr(7);
		var tr = btn;
		while (tr.tagName.toLowerCase() != 'tr') {
			tr = tr.parentNode;
		}
		var tr_new = tr.parentNode.insertBefore(tr.cloneNode(true),tr.nextSibling);

		var tds = tr_new.getElementsByTagName('td');
		for (var i = 0; i < ml_vals[name].length; i ++) {
			var el = tds[ml_vals[name][i][0]].getElementsByTagName(ml_vals[name][i][1])[0];
			el.value = ml_vals[name][i][2];
		}

		mlCheckForLast(name);
	}
}
function mlDropVal (btn)
{
	if (document.getElementById) {
		var name = btn.name.substr(5);
		var tr = btn;
		while (tr.tagName.toLowerCase() != 'tr') {
			tr = tr.parentNode;
		}
		tr.parentNode.removeChild(tr);
		mlCheckForLast(name);
	}
}
function mlAddVals (name, args)
{
	if (document.getElementById) {
		var btns = document.getElementsByName('drop_'+name);
		var tr = btns[(btns.length - 1)];
		while (tr.tagName.toLowerCase() != 'tr') {
			tr = tr.parentNode;
		}
		
		var empty = 0;
		var tds = tr.getElementsByTagName('td');
		for (var i = 0; i < ml_vals[name].length; i ++) {
			var el = tds[ml_vals[name][i][0]].getElementsByTagName(ml_vals[name][i][1])[0];
			if (el.value != ml_vals[name][i][2]) {
				empty ++;
			}
		}
		
		if (empty > 0) {
			var tr_new = tr.parentNode.insertBefore(tr.cloneNode(true),tr.nextSibling);
			var tds = tr_new.getElementsByTagName('td');
		} else {
			var tds = tr.getElementsByTagName('td');
		}
		
		for (var i = 0; i < ml_vals[name].length; i ++) {
			var el = tds[ml_vals[name][i][0]].getElementsByTagName(ml_vals[name][i][1])[0];
			el.value = args[i];
		}
		mlCheckForLast(name);
	}
}

function ch_var(chk, id)
{
	var inp = document.getElementById('f' + id);
	if(!inp) return;
	
	if( chk.checked ) { // add value
		if( inp.value.indexOf(chk.value) < 0 ) {
			inp.value += ((inp.value != '') ? ', ' : '') + chk.value;
		}
	} else {
		str = inp.value;
		
		var regEx = new RegExp (chk.value, 'gi');
		str = str.replace(regEx, '')

		regEx = new RegExp (', , ', 'gi');
		str = str.replace(regEx, ', ')

		regEx = new RegExp (', $', 'gi');
		str = str.replace(regEx, '')

		regEx = new RegExp ('^, ', 'gi');
		str = str.replace(regEx, '')
		
		inp.value = str;
	}
}
