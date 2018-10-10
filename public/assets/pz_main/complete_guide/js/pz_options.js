(function(window, $)
{

    $(function()
    {

		var pz = new PinchZoomer($("[data-elem='tempItem']")),
		radioElems = $("input:radio"),
		selectElems = $("select"),
			orientationVal = "",
			contentVal;

		radioElems.on("change", onContentChange);
		selectElems.on("change", onOptionsChange);	

		onContentChange();

		Utils.initTooltip($("form"));	

		function onContentChange(e)
		{
			if(pz != null)
			{
				orientationVal =  $("input[name=orientation]:checked").val();
				contentVal = $("input[name=content]:checked").val();

				TweenMax.set($("#orientationHolder"), { className:orientationVal + "Holder" });

				pz.elem($("<div class='" + contentVal + "Div' data-options='adjustHolderSize:false'><img data-src='assets/" + contentVal + ".jpg' class='optionImg'/><img data-src='assets/red_marker.png' class='marker redMarker' data-elem='marker' data-options='x:760; y:550; transformOrigin:20px 28px' data-tooltip='This is marker 1'/><img data-src='assets/red_marker.png' class='marker redMarker' data-elem='marker' data-options='x:570; y:250; transformOrigin:20px 28px' data-tooltip='This is marker 2'/></div>"), false, false);
				
				pz.resetElem(true);
			}        
			onOptionsChange();
		}


		function onOptionsChange(e)
		{
			var varsObj = {},
				varCtr = 0,
				varStr = "data-options='adjustHolderSize:false;";

			for(var i = 0; i < selectElems.length; i++)
			{
				var elem = selectElems.eq(i),
				elemName = elem.attr("name"),
				value = Utils.getRealValue(elem.val());

				varsObj[elemName] = value;


				if(value != PinchZoomer.defaultVars[elemName])
				{
					if(varCtr == 0)
					{
						varStr += " ";
					}
					
					if(varCtr > 0)
					{
						varStr += "; ";
					}

					varStr += elemName + ":" + value; 


					varCtr++;
				}
			}

			varStr += "'";


			var codeStr = "<div class='" + contentVal + "Div' " + varStr + ">\n   <img data-src='assets/" + contentVal + ".jpg' class='optionImg'/>\n   <img data-src='assets/red_marker.png' class='marker redMarker' data-elem='marker' data-options='x:760; y:550; transformOrigin:20px 28px' data-tooltip='This is marker 1'/>\n   <img data-src='assets/red_marker.png' class='marker redMarker' data-elem='marker' data-options='x:570; y:250; transformOrigin:20px 28px' data-tooltip='This is marker 2'/>\n</div>";

			$("#generatedCode").empty();
			$("#generatedCode").text(codeStr);
			prettyPrint();
			pz.vars(varsObj);
		}
    }
)
}(window, jQuery));