/*

===================================================
XHTML/CSS/DHTML Semantically correct drop down menu 
===================================================
Author: Sam Hampton-Smith
Site: http://www.hampton-smith.com

Description:	This script takes a nested set of <ul>s
		and turns it into a fully functional
		DHTML menu. All that is required is 
		the correct use of class names, and
		the application of some CSS.
		
Use:		Please leave this information at the
		top of this file, and it would be nice
		if you credited me/dropped me an email
		to let me know you have used the menu.
		sam AT hampton-smith.com


---------------------------------------------------
Credits: 	Inspiration/Code borrowed from Dave Lindquist (http://www.gazingus.org)
		Menu hide functionality was aided by some code I found on http://www.jessett.com/

*/


	var currentMenu = null;
	var mytimer = null;
	var timerOn = false;
	var opera = window.opera ? true : false;

	if (!document.getElementById)
		document.getElementById = function() { return null; }
	
	function initialiseMenu(menu, starter, root) {
//		var menuId = menu.attributes(0).value;
		var leftstarter = false;
	
		if (menu == null || starter == null) return;
			currentMenu = menu;
	
		starter.onmouseover = function() {
			if (currentMenu) {
				//alert(this.parentNode.parentNode.id + ' ' + currentMenu.id);
				if (this.parentNode.parentNode!=currentMenu) {
					currentMenu.style.visibility = "hidden";
					

				}
				if (this.parentNode.parentNode==root) {
					tempCurrentMenu = currentMenu
					while (tempCurrentMenu.parentNode.parentNode!=root) {
						tempCurrentMenu.parentNode.parentNode.style.visibility = "hidden";
						tempCurrentMenu = tempCurrentMenu.parentNode.parentNode;
					}
				}
				currentMenu = null;
				this.showMenu();
	        	}
		}
	
		menu.onmouseover = function() {
			if (currentMenu) {
				currentMenu = null;
				this.showMenu();
	        	}
		}	
	
		starter.showMenu = function() {
			if (!opera) {
				if (this.parentNode.parentNode==root) {
					menu.style.left = this.offsetLeft + "px";
					menu.style.top = this.offsetTop + this.offsetHeight + "px";
				}
				else {
				 	menu.style.left = this.offsetLeft + this.offsetWidth + "px";
				 	menu.style.top = this.offsetTop + "px";
				}
			}
			else {
				if (this.parentNode.parentNode==root) {
					menu.style.left = this.offsetLeft + "px";
					menu.style.top = this.offsetHeight + "px";
				}
				else {
				 	menu.style.left = this.offsetWidth + "px";
//					alert(this.offsetTop);
				 	menu.style.top = this.offsetTop + "px"; //menu.style.top - menu.style.offsetHeight + "px";
				}

			}
			menu.style.visibility = "visible";
			currentMenu = menu;
		}

		starter.onfocus	 = function() {
			starter.onmouseover();
		}
	
		menu.onfocus	 = function() {
//			currentMenu.style.visibility="hidden";
		}

		menu.showMenu = function() {
			menu.style.visibility = "visible";
			currentMenu = menu;
			stopTime();
		}

		menu.hideMenu = function()  {
			if (!timerOn) {
				//alert(this.id);
				mytimer = setInterval("killMenu('" + this.id + "', '" + root.id + "');", 2000);
				timerOn = true;
				for (var x=0;x<menu.childNodes.length;x++) {
					if (menu.childNodes[x].nodeName=="LI") {
						if (menu.childNodes[x].getElementsByTagName("UL").length>0) {
							menuItem = menu.childNodes[x].getElementsByTagName("UL").item(0);
							menuItem.style.visibility = "hidden";
						}
					}
				}
			}
		}

		menu.onmouseout = function(event) {
			this.hideMenu();
		}

		starter.onmouseout = function() {
			for (var x=0;x<menu.childNodes.length;x++) {
				if (menu.childNodes[x].nodeName=="LI") {
					if (menu.childNodes[x].getElementsByTagName("UL").length>0) {
						menuItem = menu.childNodes[x].getElementsByTagName("UL").item(0);
						menuItem.style.visibility = "hidden";
					}
				}
			}
			menu.style.visibility = "hidden";
			//menu.hideMenu();
		}
}
	function killMenu(menu, root) {
		var menu = document.getElementById(menu);
		var root = document.getElementById(root);
		menu.style.visibility = "hidden";
		for (var x=0;x<menu.childNodes.length;x++) {
			if (menu.childNodes[x].nodeName=="LI") {
				if (menu.childNodes[x].getElementsByTagName("UL").length>0) {
					menuItem = menu.childNodes[x].getElementsByTagName("UL").item(0);
					menuItem.style.visibility = "hidden";
				}
			}
		}
		while (menu.parentNode.parentNode!=root) {
			menu.parentNode.parentNode.style.visibility = "hidden";
			menu = menu.parentNode.parentNode;
		}
		stopTime();
	}
	function stopTime() {
		if (mytimer) {
		 	 clearInterval(mytimer);
			 mytimer = null;
			 timerOn = false;
		}
	} 

	window.onload = function() {
		//alert("loaded");
		var root = document.getElementById("menuList");
		//alert("About to execute getmenus");
		getMenus(root, root);
	}

function getMenus(elementItem, root) {
	var selectedItem;
	var menuStarter;
	var menuItem;
	//alert("in getmenus");
	//alert(elementItem.childNodes.length);
	for (var x=0;x<elementItem.childNodes.length;x++) {
		//alert(elementItem.childNodes[x].nodeName);
		if (elementItem.childNodes[x].nodeName=="LI") {
			//alert("Yes!");
			if (elementItem.childNodes[x].getElementsByTagName("UL").length>0) {
				//alert("Set up");
				menuStarter = elementItem.childNodes[x].getElementsByTagName("A").item(0);
				menuItem = elementItem.childNodes[x].getElementsByTagName("UL").item(0);
				getMenus(menuItem, root);
				initialiseMenu(menuItem, menuStarter, root);
			}
		}
	}
	//return true;
}