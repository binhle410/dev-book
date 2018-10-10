(function(window, $)
{
	$(function()
	 {
	 	var pz = PinchZoomer.get(0),
			useMouseOver = true,
			allowSwitch = true,
			switchObj = {},
			elemChangeObj = {};
		
		TweenLite.set("#switchThumb", { x:26 });
		$("#mousewheelText").off("click").on("click", onMouseInputTextClick)
	 	$("#mouseoverText").off("click").on("click", onMouseInputTextClick)
	 	$("#switchHolder").off("click").on("click", onMouseInputTextClick)
	 
		pz.on(PinchZoomer.ELEM_CHANGE, onElemChange);
	 
		setVars();
		
	function onMouseInputTextClick(e)
	{
		if(allowSwitch)
		{	
			var target = $(e.currentTarget),
				thumbPosX = 0,
				mousewheelClass = "mouseInputText on",
				mouseoverClass = "mouseInputText off";

			if(target.attr("id") == "mouseoverText")
			{
				useMouseOver = true;

			}
			else if(target.attr("id") == "mousewheelText")
			{
				useMouseOver = false;
			}
			else
			{
				useMouseOver = !useMouseOver;
			}

			if(useMouseOver)
			{
				thumbPosX = 26;
				mousewheelClass = "mouseInputText off",
				mouseoverClass = "mouseInputText on";

			}


			TweenLite.to("#switchThumb", 0.25, { x:thumbPosX });
			TweenLite.set("#mousewheelText", { className:mousewheelClass });
			TweenLite.set("#mouseoverText", { className:mouseoverClass });
			//pz.vars({ allowHoverZoom:useMouseOver, allowMouseWheelScroll:useMouseOver });
			pz.zoom(1, 0.25);
			setVars();
			
			allowSwitch = false;
			TweenLite.to(switchObj, 0.1, { onComplete:enableSwitch });
		}
	}
		
	function enableSwitch()
	{
		allowSwitch = true;
	}
		
	function onElemChange()
	{
		TweenLite.to(elemChangeObj, 0, { onComplete:setVars, immediateRender:false });
	}
	  
	function setVars()
	{
		pz.vars({ allowHoverZoom:useMouseOver, allowMouseWheelScroll:useMouseOver });
	}
	 
	});
	
}(window, jQuery));