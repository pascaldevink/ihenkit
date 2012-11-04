// ==UserScript==
// @name        IHenkIt
// @namespace   Tweakers
// @description IHenkIt button on http://tweakers.net
// @include     http://tweakers.net/*
// @include     http://www.tweakers.net/*
// @include     http://gathering.tweakers.net/*
// @version     1
// ==/UserScript==

var serverUrl = 'http://ihenk.it';
//var serverUrl = 'http://localhost:4567';
//var serverUrl = 'http://ihenkit.eu1.frbit.net';

// CSS Styles
var css = "#ihenkit #numberOfHenks {" +
	"  position: relative;" +
	"  float: left;" +
	"  height: 23px;" +
	"  width: 35px;" +
	"  margin-left: 4px;" +
	"  padding-top: 10px;" +
	"  text-align: center;" +
	"  color: #666666;" +
	"  font-size: 11px;" +
	"  border: 1px solid #CCCCCC;" +
	"  border-radius: 3px;" +
	"}" +
	"#ihenkit img {" +
	"  cursor: pointer;" +
	"  float: left;" +
	"}";

// Base64 encoded Henk image
var henkImage = 'R0lGODlhegAjANUAALmyg2xMD4hlBoGes/GyAYWFhrq7u//VBMXFxZmYmOTl5nBsa/zJA6enqI2CT9LT1P/sLlJOQuzw88aWAVh9m9mnAaaFCf7aEXWQpqymgkY6FiwrJ4l8O52gp3dsRxAEBf/jHHt8fjkjAJ6enpq025yWjujq7bSbKV5cW9jDK5CRkjMRAf3dIkJba8TR29jg5zlWbBkrOEZpgi5AT67BzrqnIMC8q4qKi5KCfO3CArCxsfP09Pj5+d3e3vX29vT19SH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjEgNjQuMTQwOTQ5LCAyMDEwLzEyLzA3LTEwOjU3OjAxICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1LjEgV2luZG93cyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo0NTNFQkYzMjFGNDkxMUUyQTNEM0Q4NjEzRTM5MUIzMSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo0NTNFQkYzMzFGNDkxMUUyQTNEM0Q4NjEzRTM5MUIzMSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjQ1M0VCRjMwMUY0OTExRTJBM0QzRDg2MTNFMzkxQjMxIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjQ1M0VCRjMxMUY0OTExRTJBM0QzRDg2MTNFMzkxQjMxIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+Af/+/fz7+vn49/b19PPy8fDv7u3s6+rp6Ofm5eTj4uHg397d3Nva2djX1tXU09LR0M/OzczLysnIx8bFxMPCwcC/vr28u7q5uLe2tbSzsrGwr66trKuqqainpqWko6KhoJ+enZybmpmYl5aVlJOSkZCPjo2Mi4qJiIeGhYSDgoGAf359fHt6eXh3dnV0c3JxcG9ubWxramloZ2ZlZGNiYWBfXl1cW1pZWFdWVVRTUlFQT05NTEtKSUhHRkVEQ0JBQD8+PTw7Ojk4NzY1NDMyMTAvLi0sKyopKCcmJSQjIiEgHx4dHBsaGRgXFhUUExIREA8ODQwLCgkIBwYFBAMCAQAAIfkEAAAAAAAsAAAAAHoAIwBABv9ARW9ILBqPyKRyyWw6n1Ahz0etWqsGQ2iT+Nw+qs9oUzAgrui0es1uu99r3nCaHt1GhszloCENKBYVFBgkGzkMBDYNDQtnbS4wLxIULhQDVpAvVZk+lj5/Pi4zlzQyEpOVl1cSMhShM66ZLzAub3I9PD+6u7wIKjojIwUBAgIBx8URKiOLNwq80NHS09TV1tfYure52d3e3+Dh4Ntw5ebn6Onp5G07KCcQICAX9PUXIAIoEur8/f5V26RJEKZDB4oJByYImFChYYUJFgJYYCAgBIIEZa5lQoVqVyaPkX5Y+lFKwseNlDry6thxlgtvAashCJHAhLUeBUL0EMezp0//bXP+CR1K1By7cg8QKChgAkGPolCjpjmq5sGGBjM2cEgBoSsEFjU8bIgw5qnUs/+oVk0BwgKZCIcChJgh4ACDE/vKoax1hRMVv25QoRmgao0ovm1iQmtg4MGNBhzsHshR4YDlAwRy2EXkoUGCBzo0RmJFgYIEXh91yYIRo/UMFydHtzIdjSWlHy4nvcCmuJoCCwRWfNiAYwGKDx8EEOCw46fz5916T5OgIoSB0zhRhP6h4MZj6ODDR5Muvrx5cbegqF/Pvr37KLh4yJ9Pv779+/jz69/Pv79//0GhJeCA5ahF4IEIUmHgGozdMIMOHxiwAR4GJGihLQGusYMCYYzw/0EEGjggAAcaRCDGhM9cqOIVC1ohwQIfLFBDPCzUWKM8+cSY14ortngFAPE8lMMeDFiAkF02nLMXGoAB1oZgVxD2yGsY4lJVYz5kwMIFGiTVgZEdGGDCBgQwUMFTCBiggBt7sWLKX5FsEucsMcRZiQyt0eADRzK8+QkNk2DQ2gA0DOqDS1VyI40BKtzAwR4VbNBCAJoJ0MIGFthFgAcd3LCTaJJQMAo0orRmaiQu4RZJbKGOWtttLdEyy27XkPeDCgXkWoAHDPR6GQETMGAZQwxMgIKuIYCKCmGohaQaqs4+u9tezEJj20u5UUCrNbZCs4MDRSKnQQnFIbcCARWU8HrNXj+kllq7IdH5GrzTjnbbuz9cqyps89Y6hzUPNKbDQiIgZ3AAFQSAQJo2necwev9Sk4AKDTxWwAIdoCDCxiXcsADFyxSAwMMkwxQxNQjcUJ1vC9jxTMkw+4tLzDTH3G3NOEN3yw489+zzz0AHLfTQRBdt9NFIJ91DEAA7';

var userBar = document.getElementById('userbar');
var isLoggedIn = userBar.className == 'loggedin';

var data;

if (isLoggedIn)
{
	var galleryUrl = userBar.children[0].children[1].children[0].children[0].children[0].attributes[0].value;
	var userId = galleryUrl.substring(galleryUrl.lastIndexOf('/')+1);

	GM_xmlhttpRequest({
		method: "GET",
		url: serverUrl+"/list?userId=266225&url="+window.location.href,
		onload: function(response) {
			if (response.status != 200)
			{
				console.log(response);

				data = eval("(" + response.responseText + ")");
				if (data.error.code !== 600)
					alert('Er is iets mis gegaan tijdens het Henken: error code ' + data.error.code);

				return;
			}

			data = eval("(" + response.responseText + ")");
			var nrOfHenks = data.henks;

			var head = document.getElementsByTagName('head')[0];
			var style = document.createElement('style');
			style.type = 'text/css';
			if(style.styleSheet){
				style.styleSheet.cssText = css;
			}else{
				style.appendChild(document.createTextNode(css));
			}
			head.appendChild(style);

			var button = insertHenkButtonOnPage(nrOfHenks);

			button.addEventListener('click', function() {
				GM_xmlhttpRequest({
					method: "POST",
					url: serverUrl+"/henk",
					data: "userId="+userId+"&url="+window.location.href,
					headers: {
						"Content-Type": "application/x-www-form-urlencoded"
					},
					onload: function(response) {
						if (response.status != 200)
						{
							console.log(response);
							data = eval("(" + response.responseText + ")");

							if (data.error.code == 600)
								alert('Deze kun je (nog) niet Henken, helaas.');
							else if (data.error.code == 5)
								alert('Je hebt deze al geHenkt!');
							else
								alert('Er is iets mis gegaan tijdens het Henken: error code ' + data.error.code);

							return;
						}

						data = eval("(" + response.responseText + ")");
						nrOfHenks = data.henks;

						var henksBlock = document.getElementById('numberOfHenks');
						henksBlock.innerHTML = nrOfHenks;
					}
				});
			});
		}
	});
}

function insertHenkButtonOnPage(nrOfHenks)
{
	var buttonWrapper = document.createElement('div');
	buttonWrapper.id = 'ihenkit';

	var button = document.createElement('img');
	button.src = 'data:image/gif;base64,' + henkImage;
	buttonWrapper.appendChild(button);

	var henksBlock = document.createElement('div');
	henksBlock.id = 'numberOfHenks';
	henksBlock.innerHTML = nrOfHenks;

	buttonWrapper.appendChild(henksBlock);

	var pathname = window.location.pathname;
	if (pathname.indexOf('list_messages') > 0)
	{
		// Forum
		var listItem = document.createElement('li');
		listItem.appendChild(buttonWrapper);

		var actionList = document.getElementsByClassName('action_list')[0];
		actionList.appendChild(listItem);
	}
	else
	{
		// Content page
		var clearBreak = document.createElement('br');
		clearBreak.className = 'clear';
		buttonWrapper.appendChild(clearBreak);

		var relevancyColumn = document.getElementsByClassName('relevancyColumn')[0];
		relevancyColumn.insertBefore(buttonWrapper, relevancyColumn.childNodes[0]);
	}

	return button;
}