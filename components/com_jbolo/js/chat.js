function gsCookie(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {  
                var cookie = $jbolo.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};


/*

Copyright (c) 2009 Anant Garg (anantgarg.com | inscripts.com)

This script may be used for non-commercial purposes only. For any
commercial purposes, please contact the author at 
anant.garg@inscripts.com

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

*/


var windowFocus = true;
var username;
var name;
var chatHeartbeatCount = 0;
var minChatHeartbeat = 1000;
var maxChatHeartbeat = 33000;
var chatHeartbeatTime = minChatHeartbeat;
var originalTitle;
var blinkOrder = 0;

var chatboxFocus = new Array();
var newMessages = new Array();
var newMessagesWin = new Array();
var chatBoxes = new Array();


$jbolo(document).ready(function(){
	originalTitle = document.title;
	startChatSession();

	$jbolo([window, document]).blur(function(){
		windowFocus = false;
	}).focus(function(){
		windowFocus = true;
		document.title = originalTitle;
	});
});

function strstr( haystack, needle ,bool) {
    var pos = 0;
     
    haystack += '';
    pos = haystack.indexOf( needle );
    if (pos == -1) {
        return false;
    } else{
        if( bool ){
            return haystack.substr( 0, pos );
        } else{
            return haystack.slice( pos );
        }
    }
}

function restructureChatBoxes(){
	align = 0;
	for (x in chatBoxes) {
		if (chatBoxes) {
			chatboxtitle = chatBoxes[x];
			if ((strstr(chatBoxes[x], 'function')) == false) {
				if ($jbolo("#chatbox_" + chatboxtitle).css('display') != 'none') {
					if (align == 0) {
						$jbolo("#chatbox_" + chatboxtitle).css('right', '20px');
					}
					else {
						width = (align) * (225 + 7) + 20;
						$jbolo("#chatbox_" + chatboxtitle).css('right', width + 'px');
					}
					align++;
				}
			}
		}	
	}
}

function chatWith(chatuser,nombre) {
  if( nombre == '' )
  {
	$jbolo.ajax({
	  url: "index.php?option=com_jbolo&action=nombreusuario&user="+chatuser,
	  cache: false,
	  dataType: "json",
	  success: function(data) { 
		createChatBox(chatuser,0,data.nombre);
	}});
  }else
  {
	  createChatBox(chatuser,0,nombre);
  }
}

function createChatBox(chatboxtitle,minimizeChatBox,label) {
		if ($jbolo("#chatbox_"+chatboxtitle).length > 0) { 
			if ($jbolo("#chatbox_"+chatboxtitle).css('display') == 'none') {
				$jbolo("#chatbox_"+chatboxtitle).css('display','block');
				restructureChatBoxes();
			}
			$jbolo("#chatbox_"+chatboxtitle+" .chatboxtextarea").focus();
			return;
		}
		
		label	= label.length > 25 ? label.substr(0,25)+'...' : label;
	
		$jbolo(" <div />" ).attr("id","chatbox_"+chatboxtitle)
		.addClass("chatbox")
		//.html('<div class="chatboxhead" onclick="toggleChatBoxGrowth(\''+chatboxtitle+'\')"><div class="chatboxtitle">'+chatboxtitle+'</div><div class="chatboxoptions"><span class="minimize">_</span> <span onclick="closeChatBox(\''+chatboxtitle+'\')" class="minimize">X</span></div><br clear="all"/></div><div class="chatboxcontent"></div><div class="chatboxinput"><textarea class="chatboxtextarea" onkeydown="javascript:return checkChatBoxInputKey(event,this,\''+chatboxtitle+'\');"></textarea></div>')
		.html('<div class="chatboxhead" onclick="toggleChatBoxGrowth(\''+chatboxtitle+'\')"><div class="chatboxtitle">'+label+'</div><div class="chatboxoptions"><span class="minimize">_</span> <span onclick="closeChatBox(\''+chatboxtitle+'\')" class="minimize">X</span></div><br clear="all"/></div><div class="chatboxcontent"></div><div class="chatboxinput"><textarea class="chatboxtextarea" onkeydown="javascript:return checkChatBoxInputKey(event,this,\''+chatboxtitle+'\');"></textarea></div>')
		.appendTo($jbolo( "body" ));
	
		$jbolo("#chatbox_"+chatboxtitle).css('bottom', '0px');
		$jbolo("#chatbox_"+chatboxtitle).css('z-index', 1000);
	
		chatBoxeslength = 0;
		
		for (x in chatBoxes) {
			if (chatBoxes) {
				if ((strstr(chatBoxes[x], 'function')) == false) {
					if ($jbolo("#chatbox_" + chatBoxes[x]).css('display') != 'none') {
						chatBoxeslength++;
					}
				}
			}	
		}
	
	
		if (chatBoxeslength == 0) {
			$jbolo("#chatbox_"+chatboxtitle).css('right', '20px');
		} else {
			width = (chatBoxeslength)*(225+7)+20;
			$jbolo("#chatbox_"+chatboxtitle).css('right', width+'px');
		}
		
		chatBoxes.push(chatboxtitle);
	
		if (minimizeChatBox == 1) {
			minimizedChatBoxes = new Array();
	
			if (gsCookie('chatbox_minimized')) {
				minimizedChatBoxes = gsCookie('chatbox_minimized').split(/\|/);
			}
			minimize = 0;
			for (j=0;j<minimizedChatBoxes.length;j++) { 
				if (minimizedChatBoxes[j]) {
					if (minimizedChatBoxes[j] == chatboxtitle) {
						minimize = 1;
					}
				}	
			}
	
			if (minimize == 1) {
				//$jbolo('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','none');
				setTimeout('$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").css("display","none");', 100);
				$jbolo('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','none');
			}
		}
	
		chatboxFocus[chatboxtitle] = false;
	
		$jbolo("#chatbox_"+chatboxtitle+" .chatboxtextarea").blur(function(){
			chatboxFocus[chatboxtitle] = false;
			$jbolo("#chatbox_"+chatboxtitle+" .chatboxtextarea").removeClass('chatboxtextareaselected');
		}).focus(function(){
			chatboxFocus[chatboxtitle] = true;
			newMessages[chatboxtitle] = false;
			$jbolo('#chatbox_'+chatboxtitle+' .chatboxhead').removeClass('chatboxblink');
			$jbolo("#chatbox_"+chatboxtitle+" .chatboxtextarea").addClass('chatboxtextareaselected');
		});
	
		$jbolo("#chatbox_"+chatboxtitle).click(function() {
			if ($jbolo('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display') != 'none') {
				$jbolo("#chatbox_"+chatboxtitle+" .chatboxtextarea").focus();
			}
		});
	
		$jbolo("#chatbox_"+chatboxtitle).show();
}


function chatHeartbeat(){ 

	var itemsfound = 0;
	
	if (windowFocus == false) {
 
		var blinkNumber = 0;
		var titleChanged = 0;
		for (x in newMessagesWin) {
			if (newMessagesWin[x]) {
				if (newMessagesWin[x] == true) {
					++blinkNumber;
					if (blinkNumber >= blinkOrder) {
						document.title = x + ' says...';
						titleChanged = 1;
						break;
					}
				}
			}	
		}
		
		if (titleChanged == 0) {
			document.title = originalTitle;
			blinkOrder = 0;
		} else {
			++blinkOrder;
		}

	} else {
		for (x in newMessagesWin) { 
			newMessagesWin[x] = false;
		}
	}

	for (x in newMessages) { 
		if (newMessages[x] == true) {
			if (chatboxFocus[x] == false) {
				//FIXME: add toggle all or none policy, otherwise it looks funny
				$jbolo('#chatbox_'+x+' .chatboxhead').toggleClass('chatboxblink');
			}
		}
	}
	//index.php?option=com_chat&
	$jbolo.ajax({
	  url: "index.php?option=com_jbolo&action=chatheartbeat",
	  cache: false,
	  dataType: "json",
	  success: function(data) { 

		$jbolo.each(data.items, function(i,item){
			if (item)	{ // fix strange ie bug

				chatboxtitle = item.id;
				thismessage = doReplace(item.m);
				
				if ($jbolo("#chatbox_"+chatboxtitle).length <= 0) {
					createChatBox(chatboxtitle,0,item.label);
				}
				if ($jbolo("#chatbox_"+chatboxtitle).css('display') == 'none') {
					$jbolo("#chatbox_"+chatboxtitle).css('display','block');
					restructureChatBoxes();
				}
				
				if (item.s == 1) {
					item.f = username;
				}

				if (item.s == 2) {
					$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+thismessage+'</span></div>');
				} else {

					newMessages[chatboxtitle] = true;
					newMessagesWin[chatboxtitle] = true;
					$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.fl+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+thismessage+'</span></div>');
				}
				$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
				itemsfound += 1;
				
			} 
		});

		chatHeartbeatCount++;

		if (itemsfound > 0) {
			chatHeartbeatTime = minChatHeartbeat;
			chatHeartbeatCount = 1;
		} else if (chatHeartbeatCount >= 10) {
			chatHeartbeatTime *= 2;
			chatHeartbeatCount = 1;
			if (chatHeartbeatTime > maxChatHeartbeat) {
				chatHeartbeatTime = maxChatHeartbeat;
			}
		}

		setTimeout('chatHeartbeat();',chatHeartbeatTime);
	}});
}

function closeChatBox(chatboxtitle) {
	$jbolo('#chatbox_'+chatboxtitle).css('display','none');
	restructureChatBoxes();

	$jbolo.post("index.php?option=com_jbolo&action=closechat", { chatbox: chatboxtitle} , function(data){	
	});

}

function toggleChatBoxGrowth(chatboxtitle) {
	if ($jbolo('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display') == 'none') {  
		
		var minimizedChatBoxes = new Array();
		
		if (gsCookie('chatbox_minimized')) {
			minimizedChatBoxes = gsCookie('chatbox_minimized').split(/\|/);
		}

		var newCookie = '';

		for (i=0;i<minimizedChatBoxes.length;i++) { 
			if (minimizedChatBoxes[i] != chatboxtitle) {
				newCookie += chatboxtitle+'|';
			}
		}

		newCookie = newCookie.slice(0, -1)
	

		gsCookie('chatbox_minimized', newCookie);
		$jbolo('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','block');
		$jbolo('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','block');
		$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);

		// IE positioning hack
		if ($jbolo.browser.msie && $jbolo.browser.version.substr(0,1)<8) {
			$jbolo('#chatbox_'+chatboxtitle).removeClass('chatbox_m');
		}
	} else {
		
		var newCookie = chatboxtitle;

		if (gsCookie('chatbox_minimized')) {
			newCookie += '|'+gsCookie('chatbox_minimized');
		}


		gsCookie('chatbox_minimized',newCookie);
		$jbolo('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','none');
		$jbolo('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','none');

		// IE positioning hack
		if ($jbolo.browser.msie && $jbolo.browser.version.substr(0,1)<8) {
			$jbolo('#chatbox_'+chatboxtitle).addClass('chatbox_m');
		}
		//if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<8 )
	}
	
}

function checkChatBoxInputKey(event,chatboxtextarea,chatboxtitle) {  
	 
	if(event.keyCode == 13 && event.shiftKey == 0)  {
		message = $jbolo(chatboxtextarea).val();
		message = message.replace(/^\s+|\s+$/g,"");

		$jbolo(chatboxtextarea).val('');
		$jbolo(chatboxtextarea).focus();
		$jbolo(chatboxtextarea).css('height','44px');
		if (message != '') {
			$jbolo.post("index.php?option=com_jbolo&action=sendchat", {to: chatboxtitle, message: message} , function(data){
				message = message.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");
				nmessage = doReplace(message);
				$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+name+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+nmessage+'</span></div>');
				$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
			});
		}
		chatHeartbeatTime = minChatHeartbeat;
		chatHeartbeatCount = 1;

		return false;
	}

	var adjustedHeight = chatboxtextarea.clientHeight;
	var maxHeight = 94;

	if (maxHeight > adjustedHeight) {
		adjustedHeight = Math.max(chatboxtextarea.scrollHeight, adjustedHeight);
		if (maxHeight)
			adjustedHeight = Math.min(maxHeight, adjustedHeight);
		if (adjustedHeight > chatboxtextarea.clientHeight)
			$jbolo(chatboxtextarea).css('height',adjustedHeight+8 +'px');
	} else {
		$jbolo(chatboxtextarea).css('overflow','auto');
	}
	 
}

function startChatSession(){
	$jbolo.ajax({
	  url: "index.php?option=com_jbolo&action=startchatsession",
	  cache: false,
	  dataType: "json",
	  success: function(data) {  

		username = data.username;
		name = data.name;
		if (username == "undefined") { username = "Guest"; }

		$jbolo.each(data.items, function(i,item){ 
			if (item)	{ // fix strange ie bug

				chatboxtitle = item.id;
				thismessage = doReplace(item.m);
				
				if ($jbolo("#chatbox_"+chatboxtitle).length <= 0) {
					//createChatBox(chatboxtitle,1,item.label);
					createChatBox(chatboxtitle,0,item.label);
				}
				
				if (item.s == 1) {
					item.f = username;
				}

				if (item.s == 2) {
					$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+thismessage+'</span></div>');
				} else {
					$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.fl+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+thismessage+'</span></div>');
				}
			}
		});
		
		for (i=0;i<chatBoxes.length;i++) { 
			chatboxtitle = chatBoxes[i];
			$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
			setTimeout('$jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($jbolo("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);', 100); // yet another strange ie bug
			setTimeout("toggleChatBoxGrowth('"+chatboxtitle+"');",100);
		}
	
	setTimeout('chatHeartbeat();',chatHeartbeatTime);
		
	}});
}
