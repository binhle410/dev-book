(function(window, $)
{
	$(function()
	{
		var pz = PinchZoomer.get("pzEvtExample"),
			eventCtr = 1,
			ctr = 1;
		pz.on(PinchZoomer.ZOOM, updateEventLog);
		pz.on(PinchZoomer.DRAG, updateEventLog);
		pz.on(PinchZoomer.FULLSCREEN_TOGGLE, updateEventLog);
		pz.on(PinchZoomer.ELEM_CHANGE, updateEventLog);
		pz.on(PinchZoomer.GESTURE_START, updateEventLog);
		pz.on(PinchZoomer.GESTURE_END, updateEventLog);
		pz.on(PinchZoomer.LOAD_COMPLETE, updateEventLog);
		
		
		$("#changeContentButton").click(changeContent);
		
		function changeContent(e)
		{
			ctr = ((ctr + 1) % 5) + 1;
			
			pz.elem("<div><img src='#' data-src='assets/masonry" + ctr + ".jpg' data-elem='bg' style='position:absolute; left:0px; top:0px'/></div>", true, false);	
			pz.zoom(1);
		}
		
		function updateEventLog(e)
		{
			var text = "";
			
			if(e.type == PinchZoomer.ZOOM.split(".")[0])
			{
				text = "Event: Zoom - Factor: " + pz.zoom();	
			}
			else if(e.type == PinchZoomer.DRAG.split(".")[0])
			{
				text = "Event: Drag - (Rounded Values) X: " + Math.round(pz.x()) + ", Y: " + Math.round(pz.y());	
			}
			else if(e.type == PinchZoomer.FULLSCREEN_TOGGLE.split(".")[0])
			{
				text = "Event: Fullscreen Toggle - Fullscreen: " + (pz.fullscreen() ? "yes" : "no");	
			}
			else if(e.type == PinchZoomer.GESTURE_START.split(".")[0])
			{
				text = "Event: Touch Start/Mouse Down";	
			}
			else if(e.type == PinchZoomer.GESTURE_END.split(".")[0])
			{
				text = "Event:  Touch End/Mouse Up";	
			}
			else if(e.type == PinchZoomer.LOAD_COMPLETE.split(".")[0])
			{
				text = "Event:  Image/s Loaded";	
			}
			else if(e.type == PinchZoomer.ELEM_CHANGE.split(".")[0])
			{
				text = "Event:  HTML Element Changed";	
			}
			
			$("#eventLog").prepend(eventCtr + " " + text + "<br>");	
			$('#eventLog').css({scrollTop: $('#eventLog').prop('scrollHeight')});
			
			eventCtr++;
		}
		
	});
	
}(window, jQuery)); 