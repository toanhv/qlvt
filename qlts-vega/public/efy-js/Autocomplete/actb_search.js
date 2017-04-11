function actb(obj,ca,fa,fEnter,single,editable,sWebsitePart){
	/* ---- Public Variables ---- */
	this.actb_timeOut = -1; // Autocomplete Timeout in ms (-1: autocomplete never time out)
	this.actb_lim = 5;    // Number of elements autocomplete can show (-1: no limit)
	this.actb_firstText = false; // should the auto complete be limited to the beginning of keyword?
	this.actb_mouse = true; // Enable Mouse Support
	this.actb_delimiter = new Array(';');  // Delimiter for multiple autocomplete. Set it to empty array for single autocomplete
	this.actb_startcheck = 0; // Show widget only after this number of characters is typed in.
	/* ---- Public Variables ---- */
	//editable = 0; test
	/* --- Styles --- */
	this.actb_bgColor = '#FAFAFA';
	this.actb_textColor = 'black';
	this.actb_hTextColor = '#FFF';
	this.actb_hColor = '#3142C5';
	this.actb_fFamily = 'Arial';
	this.actb_fSize = '12px';
	this.actb_hStyle = 'text-decoration:underline;font-weight="bold"';
	this.border_Style = '1px solid black'
	/* --- Styles --- */
	
	/* ---- Private Variables ---- */
	var actb_delimwords = new Array();
	var actb_cdelimword = 0;
	var actb_delimchar = new Array();
	var actb_display = false;
	var actb_pos = 0;
	var actb_total = 0;
	var actb_curr = null;
	var actb_rangeu = 0;
	var actb_ranged = 0;
	var actb_bool = new Array();
	var actb_pre = 0;
	var actb_toid;
	var actb_tomake = false;
	var actb_getpre = "";
	var actb_mouse_on_list = 1;
	var actb_kwcount = 0;
	var actb_caretmove = false;
	this.actb_keywords = new Array();
	this.actb_fillKey = new Array();
	this.fEnter = fEnter;
	/* ---- Private Variables---- */
	
	this.actb_keywords = ca;
	this.actb_fillKey = fa;
	var actb_self = this;

	actb_curr = obj;
	addEvent(actb_curr,"focus",actb_setup);
	function actb_setup(){
		addEvent(document,"keydown",actb_checkkey);
		addEvent(actb_curr,"blur",actb_clear);
		addEvent(document,"keypress",actb_keypress);
		addEvent(actb_curr,"click",getvalue);
	}
	//Ham boi den mot gia tri khi NSD click vao no
	//obj -- doi tuong chua gia tri VD: Textbox
	function getvalue(posoption){
		var str 	= obj.value;
		var istart 	= getCaretStart(obj)
		var position = new Array();
		var isbreak = 0;
		var posend = 0;
		var posstart = 0;
		for(var i = istart; i <= str.length; i++){
			for(var j = 0; j < actb_self.actb_delimiter.length; j++){
				if(str.charAt(i) == actb_self.actb_delimiter[j] || i == str.length){
					posend = i; 
					isbreak = 1;
					break;
				}
			}
			if(isbreak == 1)
				break;
		}
		isbreak = 0;
		for(i = istart; i >= 0; i--){
			for(j = 0; j < actb_self.actb_delimiter.length; j++){
				if(str.charAt(i) == actb_self.actb_delimiter[j]){
					posstart = i+1; 
					isbreak = 1;
					break;
				}else if(i == 0){
					posstart = i;
					isbreak = 1;
					break;
				}
			}
			if(isbreak == 1)
				break;
		}
		setSelection(obj,posstart,posend)
	}
	function actb_clear(evt){
		if (!evt) evt = event;
		if(editable == 0){
			var str = '';
			arrstr = splitArray(actb_curr.value,actb_self.actb_delimiter);
			for(i = 0; i < arrstr.length; i++){
				var name = arrstr[i].trim();
				for(var j = 0; j < actb_self.actb_fillKey.length; j++){
					if(name == actb_self.actb_fillKey[j]){
						if(single == 0){
							if(str.search(arrstr[i].trim()) < 0)
								str += arrstr[i].trim() + ';';
						}
						else {
							if(str.search(arrstr[i].trim()) < 0)
								str += arrstr[i].trim();
						}
						break;
					}
				}
			}
		}else{
			var str = '';
			arrstr = splitArray(actb_curr.value,actb_self.actb_delimiter);
			for(i = 0; i < arrstr.length; i++){
				if(arrstr[i].trim() != '')
					if(single == 0){
						if(str.search(arrstr[i].trim()) < 0)
							str += arrstr[i].trim() + ';';
					}
					else {
						if(str.search(arrstr[i].trim()) < 0)
							str += arrstr[i].trim();
					}
			}
		}
		if(single == 0)
				actb_curr.value = str + ' ';
		else	actb_curr.value = str;
		removeEvent(document,"keydown",actb_checkkey);
		removeEvent(actb_curr,"blur",actb_clear);
		removeEvent(document,"keypress",actb_keypress);
		removeEvent(actb_curr,"click",getvalue);
		actb_removedisp();
	}
	function actb_parse(n){
		if (actb_self.actb_delimiter.length > 0){
			var t = actb_delimwords[actb_cdelimword].trim().addslashes();
			var plen = actb_delimwords[actb_cdelimword].trim().length;
		}else{
			var t = actb_curr.value.addslashes();
			var plen = actb_curr.value.length;
		}
		var tobuild = '';
		var i;

		if (actb_self.actb_firstText){
			var re = new RegExp("^" + t, "i");
		}else{
			var re = new RegExp(t, "i");
		}
		
		var p = n.search(re);
		//alert(p);
		var tTmp;
		tTmp = '';
		for (i=0;i<p;i++){
			if(n.substr(i,1)==' '){
				tTmp += '&nbsp;';
			}else{
				tTmp += n.substr(i,1);
			}
		}
		tobuild = tTmp + "<font style='"+(actb_self.actb_hStyle)+"'>"
		tTmp = '';
		for (i=p;i<plen+p;i++){
			if(n.substr(i,1)==' '){
				tTmp += '&nbsp;';
			}else{
				tTmp += n.substr(i,1);
			}
		}
		tobuild += tTmp + "</font>";
		tTmp = '';
		for (i=plen+p;i<n.length;i++){
			if(n.substr(i,1)==' '){
				tTmp += '&nbsp;';
			}else{
				tTmp += n.substr(i,1);
			}
		}
		return tobuild + tTmp;
	}
	function actb_generate(){
		if (document.getElementById('tat_table')){ actb_display = false;document.body.removeChild(document.getElementById('tat_table')); } 
		if (actb_kwcount == 0){
			actb_display = false;
			return;
		}
		var str = '';
		if(editable == 0){
			for(i = 0; i < actb_delimwords.length; i++){
				if(i != actb_cdelimword){
					var name = actb_delimwords[i].trim();
					for(var j = 0; j < actb_self.actb_fillKey.length; j++){
						if(name == actb_self.actb_fillKey[j]){
							if(single == 0){
								if(str.search(actb_delimwords[i]) < 0)
									str 		+= actb_delimwords[i] + ';';
							}
							else {
								if(str.search(actb_delimwords[i]) < 0)
									str 		+= actb_delimwords[i];
							}
							break;
						}
					}
				}else
					str += actb_delimwords[i];
			}
		}else{
			for(i = 0; i < actb_delimwords.length; i++){
				if(i != actb_cdelimword){
					if(actb_delimwords[i].trim() != '')
						if(single == 0){
							if(str.search(actb_delimwords[i]) < 0)
								str += actb_delimwords[i].trim() + ';';
						}
						else	{
							if(str.search(actb_delimwords[i]) < 0)
								str += actb_delimwords[i].trim();
						}
				}else
					if(actb_delimwords[i].trim() != '')
						if(str.search(actb_delimwords[i]) < 0)
							str += actb_delimwords[i];
			}
		}
		if(str.charAt(str.length) == '' && str.charAt(str.length - 1) == ';' && single == 0){
			str += ' ';
		}
		actb_curr.value = str;
		a = document.createElement('table');
		a.style.borderTop = actb_self.border_Style;
		a.style.borderBottom = actb_self.border_Style;
		a.style.borderLeft = actb_self.border_Style;
		a.style.borderRight = actb_self.border_Style;
		a.cellSpacing='1px';
		a.cellPadding='2px';
		a.style.position='absolute';
		a.style.top = eval(curTop(actb_curr) + actb_curr.offsetHeight) + "px";
		a.style.left = curLeft(actb_curr) + "px";
		a.style.backgroundColor=actb_self.actb_bgColor;
		a.id = 'tat_table';
		document.body.appendChild(a);
		var i;
		var first = true;
		var _first = false;
		var j = 1;
		if (actb_self.actb_mouse){
			a.onmouseout = actb_table_unfocus;
			a.onmouseover = actb_table_focus;
		}
		var counter = 0;
		for (i=0;i<actb_self.actb_keywords.length;i++){
			if (actb_bool[i]){
				_first = false;
				counter++;
				r = a.insertRow(-1);
				if (first && !actb_tomake){
					r.style.backgroundColor = actb_self.actb_hColor;
					first = false;
					_first = true;
					actb_pos = counter;
				}else if(actb_pre == i){
					r.style.backgroundColor = actb_self.actb_hColor;
					first = false;
					_first = true;
					actb_pos = counter;
				}else{
					r.style.backgroundColor = actb_self.actb_bgColor;
				}
				r.id = 'tat_tr'+(j);			
				c = r.insertCell(-1);
				if(_first == true){
					//alert(actb_self.actb_hColor);
					c.style.color = actb_self.actb_hTextColor;
				}else{
					c.style.color = actb_self.actb_textColor;
				}
				c.style.fontFamily = actb_self.actb_fFamily;
				c.style.fontSize = actb_self.actb_fSize;
				c.innerHTML = actb_parse(actb_self.actb_keywords[i]);
				c.id = 'tat_td'+(j);
				c.setAttribute('pos',j);
				if (actb_self.actb_mouse){
					c.style.cursor = 'pointer';
					c.onclick=actb_mouseclick;
					c.onmouseover = actb_table_highlight;
				}
				j++;
			}
			if (j - 1 == actb_self.actb_lim && j < actb_total){
				r = a.insertRow(-1);
				r.style.backgroundColor = actb_self.actb_bgColor;
				c = r.insertCell(-1);
				c.style.color = actb_self.actb_textColor;
				c.style.fontFamily = 'arial narrow';
				c.style.fontSize = actb_self.actb_fSize;
				c.style.backgroundColor = '#FAFAFA'
				c.colspan = '2';
				c.align='center';
				c.innerHTML = "<img src='" + sWebsitePart + "public/Images/DownArrow.gif'>";
				if (actb_self.actb_mouse){
					c.style.cursor = 'pointer';
					c.onclick = actb_mouse_down;
				}
				break;
			}
		}
		actb_rangeu = 1;
		actb_ranged = j-1;
		actb_display = true;
		if (actb_pos <= 0) actb_pos = 1;
	}
	function actb_remake(){
		document.body.removeChild(document.getElementById('tat_table'));
		a = document.createElement('table');
		a.style.borderTop = actb_self.border_Style;
		a.style.borderBottom = actb_self.border_Style;
		a.style.borderLeft = actb_self.border_Style;
		a.style.borderRight = actb_self.border_Style;
		a.cellSpacing='1px';
		a.cellPadding='2px';
		a.style.position='absolute';
		a.style.top = eval(curTop(actb_curr) + actb_curr.offsetHeight) + "px";
		a.style.left = curLeft(actb_curr) + "px";
		a.style.backgroundColor=actb_self.actb_bgColor;
		a.id = 'tat_table';
		if (actb_self.actb_mouse){
			a.onmouseout= actb_table_unfocus;
			a.onmouseover=actb_table_focus;
		}
		document.body.appendChild(a);
		var i;
		var first = true;
		var j = 1;
		if (actb_rangeu > 1){
			r = a.insertRow(-1);
			r.style.backgroundColor = actb_self.actb_bgColor;
			c = r.insertCell(-1);
			c.style.color = actb_self.actb_textColor;
			c.style.fontFamily = 'arial narrow';
			c.style.fontSize = actb_self.actb_fSize;
			c.align='center';
			c.style.backgroundColor = '#FAFAFA'
			c.innerHTML = "<img src='" + sWebsitePart + "public/Images/UpArrow.gif'>";
			if (actb_self.actb_mouse){
				c.style.cursor = 'pointer';
				c.onclick = actb_mouse_up;
			}
		}
		for (i=0;i<actb_self.actb_keywords.length;i++){
			if (actb_bool[i]){
				if (j >= actb_rangeu && j <= actb_ranged){
					r = a.insertRow(-1);
					r.style.backgroundColor = actb_self.actb_bgColor;
					r.id = 'tat_tr'+(j);
					c = r.insertCell(-1);
					c.style.color = actb_self.actb_textColor;
					c.style.fontFamily = actb_self.actb_fFamily;
					c.style.fontSize = actb_self.actb_fSize;
					c.innerHTML = actb_parse(actb_self.actb_keywords[i]);
					c.id = 'tat_td'+(j);
					c.setAttribute('pos',j);
					if (actb_self.actb_mouse){
						c.style.cursor = 'pointer';
						c.onclick=actb_mouseclick;
						c.onmouseover = actb_table_highlight;
					}
					j++;
				}else{
					j++;
				}
			}
			if (j > actb_ranged) break;
		}
		if (j-1 < actb_total){
			r = a.insertRow(-1);
			r.style.backgroundColor = actb_self.actb_bgColor;
			c = r.insertCell(-1);
			c.style.color = actb_self.actb_textColor;
			c.style.fontFamily = 'arial narrow';
			c.style.fontSize = actb_self.actb_fSize;
			c.align='center';
			c.style.backgroundColor = '#FAFAFA'
			c.innerHTML = "<img src='" + sWebsitePart + "public/Images/DownArrow.gif'>";
			if (actb_self.actb_mouse){
				c.style.cursor = 'pointer';
				c.onclick = actb_mouse_down;
			}
		}
	}
	function actb_goup(){
		if (!actb_display) return;
		if (actb_pos == 1) return;
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_textColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_bgColor;
		actb_pos--;
		if (actb_pos < actb_rangeu) actb_moveup();
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_hTextColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_hColor;
		if (actb_toid) clearTimeout(actb_toid);
		if (actb_self.actb_timeOut > 0) actb_toid = setTimeout(function(){actb_mouse_on_list=0;actb_removedisp();},actb_self.actb_timeOut);
	}
	function actb_godown(){
		if (!actb_display) return;
		if (actb_pos == actb_total) return;
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_textColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_bgColor;
		actb_pos++;
		if (actb_pos > actb_ranged) actb_movedown();
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_hTextColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_hColor;
		if (actb_toid) clearTimeout(actb_toid);
		if (actb_self.actb_timeOut > 0) actb_toid = setTimeout(function(){actb_mouse_on_list=0;actb_removedisp();},actb_self.actb_timeOut);
	}
	function actb_movedown(){
		actb_rangeu++;
		actb_ranged++;
		actb_remake();
	}
	function actb_moveup(){
		actb_rangeu--;
		actb_ranged--;
		actb_remake();
	}

	/* Mouse */
	function actb_mouse_down(){
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_textColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_bgColor;
		actb_pos++;
		actb_movedown();
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_hTextColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_hColor;
		actb_curr.focus();
		actb_mouse_on_list = 0;
		if (actb_toid) clearTimeout(actb_toid);
		if (actb_self.actb_timeOut > 0) actb_toid = setTimeout(function(){actb_mouse_on_list=0;actb_removedisp();},actb_self.actb_timeOut);
	}
	function actb_mouse_up(evt){
		if (!evt) evt = event;
		if (evt.stopPropagation){
			evt.stopPropagation();
		}else{
			evt.cancelBubble = true;
		}
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_textColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_bgColor;
		actb_pos--;
		actb_moveup();
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_hTextColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_hColor;
		actb_curr.focus();
		actb_mouse_on_list = 0;
		if (actb_toid) clearTimeout(actb_toid);
		if (actb_self.actb_timeOut > 0) actb_toid = setTimeout(function(){actb_mouse_on_list=0;actb_removedisp();},actb_self.actb_timeOut);
	}
	function actb_mouseclick(evt){
		if (!evt) evt = event;
		if (!actb_display) return;
		actb_mouse_on_list = 0;
		actb_pos = this.getAttribute('pos');
		actb_penter();
	}
	function actb_table_focus(){
		actb_mouse_on_list = 1;
	}
	function actb_table_unfocus(){
		actb_mouse_on_list = 0;
		if (actb_toid) clearTimeout(actb_toid);
		if (actb_self.actb_timeOut > 0) actb_toid = setTimeout(function(){actb_mouse_on_list = 0;actb_removedisp();},actb_self.actb_timeOut);
	}
	function actb_table_highlight(){
		actb_mouse_on_list = 1;
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_textColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_bgColor;
		actb_pos = this.getAttribute('pos');
		while (actb_pos < actb_rangeu) actb_moveup();
		while (actb_pos > actb_ranged) actb_movedown();
		document.getElementById('tat_td'+actb_pos).style.color = actb_self.actb_hTextColor;
		document.getElementById('tat_tr'+actb_pos).style.backgroundColor = actb_self.actb_hColor;
		if (actb_toid) clearTimeout(actb_toid);
		if (actb_self.actb_timeOut > 0) actb_toid = setTimeout(function(){actb_mouse_on_list = 0;actb_removedisp();},actb_self.actb_timeOut);
	}
	/* ---- */

	function actb_insertword(a){
		if (actb_self.actb_delimiter.length > 0){
			str = '';
			l=0;
			for (i=0;i<actb_delimwords.length;i++){
				if (actb_cdelimword == i){
					prespace = postspace = '';
					gotbreak = false;
					for (j=0;j<actb_delimwords[i].length;++j){
						if (actb_delimwords[i].charAt(j) != ' '){
							gotbreak = true;
							break;
						}
						prespace += ' ';
					}
					for (j=actb_delimwords[i].length-1;j>=0;--j){
						if (actb_delimwords[i].charAt(j) != ' ') break;
						postspace += ' ';
					}
					str += prespace;
					str += a;
					l = str.length;
					if (gotbreak) str += postspace;
				}else{
					str += actb_delimwords[i];
				}
				if (i != actb_delimwords.length - 1){
					str += actb_delimchar[i];
				}
			}
			var arrstr = splitArray(str,actb_self.actb_delimiter);
			var strtemp = '';
			if(editable == 0){
				for(i = 0; i < arrstr.length; i++){
					if(i != actb_cdelimword){
						var name = arrstr[i].trim();
						for(var j = 0; j < actb_self.actb_fillKey.length; j++){
							if(name == actb_self.actb_fillKey[j]){
								if(single == 0){
									if(strtemp.search(arrstr[i].trim()) < 0)
										strtemp += arrstr[i].trim() + ';';
								}
								else	{
									if(strtemp.search(arrstr[i].trim()) < 0)
										strtemp += arrstr[i].trim();
								}
								break;
							}
						}
					}else
						if(single == 0){
							if(strtemp.search(arrstr[i].trim()) < 0)
							 	strtemp += arrstr[i].trim() + ';';
						}
						else	{
							if(strtemp.search(arrstr[i].trim()) < 0)
								strtemp += arrstr[i].trim();
						}
				}
			}else{
				for(i = 0; i < arrstr.length; i++){
					if(arrstr[i].trim() != '')
						if(single == 0){
							if(strtemp.search(arrstr[i].trim()) < 0)
								strtemp += arrstr[i].trim() + ';';
						}
						else	 {
							if(strtemp.search(arrstr[i].trim()) < 0)
								strtemp += arrstr[i].trim();
						}
				}
			}
			if(single == 0)
					actb_curr.value = strtemp + ' ';
			else	actb_curr.value = strtemp;
			setCaret(actb_curr,l);
		}else{
			actb_curr.value = a;
		}
		actb_mouse_on_list = 0;
		actb_removedisp();
	}
	function actb_penter(){
		if (!actb_display) return;
		actb_display = false;
		var word = '';
		var c = 0;
		for (var i=0;i<=actb_self.actb_keywords.length;i++){
			if (actb_bool[i]) c++;
			if (c == actb_pos && single == '0'){
				word = actb_self.actb_fillKey[i] + ';';
				break;
			}
			if(c == actb_pos && single == '1'){
				word = actb_self.actb_fillKey[i];
				break;
			}
		}
		actb_insertword(word);
		l = getCaretStart(actb_curr);	
		if (actb_self.fEnter!=""){
			//alert(actb_self.fEnter);
			//alert(actb_self.fEnter  + i + ")");
			eval(actb_self.fEnter + (i) + ")");
		}
	}
	function actb_removedisp(){
		if (actb_mouse_on_list==0){
			actb_display = 0;
			if (document.getElementById('tat_table')){ document.body.removeChild(document.getElementById('tat_table')); }
			if (actb_toid) clearTimeout(actb_toid);
		}
	}
	function actb_keypress(e){
		if (actb_caretmove) stopEvent(e);
		return !actb_caretmove;
	}
	function actb_checkkey(evt){
		if (!evt) evt = event;
		if (evt.shiftKey){return};
		a = evt.keyCode;
		caret_pos_start = getCaretStart(actb_curr);
		actb_caretmove = 0;
		switch (a){
			case 38:
				actb_goup();
				actb_caretmove = 1;
				return false;
				break;
			case 37:
				var str = actb_curr.value;
				for(i = caret_pos_start; i >= 0; i--){
					if(str.charAt(i) == ';'){
						posend = i; break;
					}else if(i == 0){
						posend = i; break;
					}
				}
				for(i = posend - 1; i >= 0; i--){
					if(str.charAt(i) == ';'){
						posstart = i+1 ; break;
					}else if(i==0){
						posstart = i ; break;
					}
				}
				setSelection(obj,posstart,posend);
				actb_goup();
				actb_caretmove = 1;
				return false;
				break;
			case 39:
				var str = actb_curr.value;
				for(i = caret_pos_start; i <= str.length; i++){
					if(str.charAt(i) == ';' || i == str.length){
						posstart = i+1; break;
					}
				}
				for(i = posstart + 1; i <= str.length; i++){
					if(str.charAt(i) == ';' || i == str.length){
						posend = i ; break;
					}
				}
				setSelection(obj,posstart,posend);
				actb_goup();
				actb_caretmove = 1;
				return false;
				break;
			case 40:
				if (actb_display){
					actb_godown();
					actb_caretmove = 1;
					return false;
				}else{
					setTimeout(function(){actb_tocomplete(a)},50);
				}
				break;
			case 13: case 9:
				if (actb_display){
					actb_caretmove = 1;
					actb_penter();
					return false;
				}else{
					return true;
				}
				break;
			default:
				setTimeout(function(){actb_tocomplete(a)},50);
				break;
		}
	}

	function actb_tocomplete(kc){
		if (kc == 38 || kc == 13) return;
		var i;
		if (actb_display){
			var word = 0;
			var c = 0;
			for (var i=0;i<=actb_self.actb_keywords.length;i++){
				if (actb_bool[i]) c++;
				if (c == actb_pos){
					word = i;
					break;
				}
			}
			actb_pre = word;
		}else{actb_pre = -1};
		//if (actb_curr.value == ''){
		//	actb_mouse_on_list = 0;
		//	actb_removedisp();
		//	return;
		//}
		if (actb_self.actb_delimiter.length > 0){
			caret_pos_start = getCaretStart(actb_curr);
			caret_pos_end = getCaretEnd(actb_curr);
			delim_split = '';
			for (i=0;i<actb_self.actb_delimiter.length;i++){
				delim_split += actb_self.actb_delimiter[i];
			}
			delim_split = delim_split.addslashes();
			delim_split_rx = new RegExp("(["+delim_split+"])");
			c = 0;
			actb_delimwords = new Array();
			actb_delimwords[0] = '';
			for (i=0,j=actb_curr.value.length;i<actb_curr.value.length;i++,j--){
				if (actb_curr.value.substr(i,j).search(delim_split_rx) == 0){
					ma = actb_curr.value.substr(i,j).match(delim_split_rx);
					actb_delimchar[c] = ma[1];
					c++;
					actb_delimwords[c] = '';
				}else{
					actb_delimwords[c] += actb_curr.value.charAt(i);
				}
			}
			var l = 0;
			actb_cdelimword = -1;
			for (i=0;i<actb_delimwords.length;i++){
				if (caret_pos_end >= l && caret_pos_end <= l + actb_delimwords[i].length){
					actb_cdelimword = i;
				}
				l+=actb_delimwords[i].length + 1;
			}
			var ot = actb_delimwords[actb_cdelimword].trim(); 
			var t = actb_delimwords[actb_cdelimword].addslashes().trim();
		}else{
			var ot = actb_curr.value;
			var t = actb_curr.value.addslashes();
		}
		if (ot.length < actb_self.actb_startcheck) return this;
		if (actb_self.actb_firstText){
			var re = new RegExp("^" + t, "i");
		}else{
			var re = new RegExp(t, "i");
		}
		actb_total = 0;
		actb_tomake = false;
		actb_kwcount = 0;
		for (i=0;i<actb_self.actb_keywords.length;i++){
			actb_bool[i] = false;
			if ((ot.length == 0)||(re.test(actb_self.actb_keywords[i]))){
				actb_total++;
				actb_bool[i] = true;
				actb_kwcount++;
				if (actb_pre == i) actb_tomake = true;
			}
		}
		if (actb_toid) clearTimeout(actb_toid);
		if (actb_self.actb_timeOut > 0) actb_toid = setTimeout(function(){actb_mouse_on_list = 0;actb_removedisp();},actb_self.actb_timeOut);
		actb_generate();
	}
	return this;
}