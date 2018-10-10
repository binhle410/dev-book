(function(window, $)
{
	$(function()
	{
		var pzItems = $("[data-elem='pzItem']"),
			tempItem = $("[data-elem='tempItem']"),
			thumbScrollerElem = $("div[data-elem='thumbScroller']").eq(0),
			thumbHolder = $("div[data-elem='thumbHolder']").eq(0),
			fullscreenThumbHolder = $("div[data-elem='fullscreenThumbHolder']").eq(0),
			thumbScroller = null,
			pz = new PinchZoomer(tempItem),
			_index = -1,
			fullscreenDiv = FullscreenElem._vars.fullscreenDiv,
			prevButton = $("<div class='prevButton'></div>"),
			nextButton = $("<div class='nextButton'></div>");
		
		//initTooltip();
		pzItems.detach();
		setup();
		
		
		function initTooltip(tooltipElem)
		{
			var trigger = Utils.isTouchDevice() ? "click" : "hover",
				markerTooltips = tooltipElem ? $(tooltipElem).find(".tooltip").not(".tooltipstered") : $(".tooltip").not(".tooltipstered"),
				len = markerTooltips.length;
			
			for(var i = 0; i < len; i++)
			{
				var markerTooltip = markerTooltips.eq(i);
				markerTooltip.attr("title", markerTooltip.data("title"));
				
				var tipHanlder = new Hammer(markerTooltip.get(0));
				tipHanlder.on('press pressup', onTipHandler);
				
			}
			
			if(Utils.browser.name == "Firefox")
			{
				markerTooltips.tooltipster({trigger:"hover", theme: 'tooltipster-light'});
				
			}
			else
			{
				markerTooltips.tooltipster({trigger: 'custom',
											triggerOpen: { mouseenter: true },
											triggerClose: { mouseleave: true },
											theme: 'tooltipster-light' });
				
			}
		}
		
		function onTipHandler(e)
		{
			if(e.type == "press")
			{
				$(e.target).tooltipster('open');
			}
			else
			{
				$(e.target).tooltipster('close');
			}
		}
		
		function setIndex(val)
		{
			//if(val !== _index && val >= 0 && val < pzItems.length)
			
			
			if(val >= 0 && val < pzItems.length)
			{
				
				if(val == _index)
				{
					pz.fullscreen(true);
				}
				else
				{
					_index = val;
					
					var pzItem = pzItems.eq(_index);
					//	markers = pzItem.find("*[data-elem='marker']");
					
					pz.elem(pzItem, false, false);
				}
				
				pz.fullscreen(true);
				$(".controlHolder").append(prevButton);
				$(".controlHolder").append(nextButton);
				initTooltip(pz.elem());
				
				if(val == 0)
				{
					prevButton.removeClass("on");	
					prevButton.addClass("off");	
				}
				else
				{
					prevButton.removeClass("off");	
					prevButton.addClass("on");	
				}
				
				if(val == pzItems.length - 1)
				{
					nextButton.removeClass("on");	
					nextButton.addClass("off");	
				}
				else
				{
					nextButton.removeClass("off");	
					nextButton.addClass("on");	
				}
				
			}
		}
		
		function setup()
		{
			var pzLen = pzItems.length,
				thumbs = [];
				
			for(var i = 0; i < pzLen; i++)
			{
				var pzItem = pzItems.eq(i),
					gridVars = Utils.stringToObject("{" + pzItem.data("grid-options") + "}");
				pzItem.data("parsed", false);
				pzItem.data("obj", {});	
				
				if(gridVars.thumbUrl != null)
				{
					var addClass = gridVars.addClass ? " " + gridVars.addClass : "",
						gridItemDiv = $("<div class='grid-item" + addClass + "'><img src='" + gridVars.thumbUrl + "' style='width:100%; height:auto'/></div>");
						
					$("#grid").append(gridItemDiv);	
					gridItemDiv.find("img").on("load", onGridImageLoad);
					gridItemDiv.on("click", {index:i}, onGridItemClick);
				}
			}
			
			prevButton.on("click", onPrev);
			nextButton.on("click", onNext);
			
			
			$("#grid").masonry({ itemSelector: '.grid-item', columnWidth: 200, gutter:10});
			
		}
		
		function onGridImageLoad(e)
		{
			$("#grid").masonry("layout");
		}
		
		function onGridItemClick(e)
		{
			var index = e.data.index;
			setIndex(index);
		}
		
		function onPrev()
		{
			setIndex(_index - 1);
		}
		
		function onNext()
		{
			setIndex(_index + 1);
		}
	});
	
}(window, jQuery)); 