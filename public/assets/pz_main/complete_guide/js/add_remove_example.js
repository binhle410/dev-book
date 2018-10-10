$(function()
{
	"use strict";
	var pzContents = ["<img src='#' data-src='assets/masonry1.jpg' data-elem='bg'/>",
					  "<img src='#' data-src='assets/masonry2.jpg' data-elem='bg'/>",
					  "<img src='#' data-src='assets/masonry3.jpg' data-elem='bg'/>",
					  "<img src='#' data-src='assets/masonry4.jpg' data-elem='bg'/>",
					  "<img src='#' data-src='assets/masonry5.jpg' data-elem='bg'/>",
					  "<img src='#' data-src='assets/masonry6.jpg' data-elem='bg'/>"],
		ctr = 1;
	
	
	$("#addPZButton").click(onAddPZClick);
	onAddPZClick();
	
	function onAddPZClick()
	{
		
		var index = Math.floor(Math.random() * pzContents.length),
			pzContent = $("<div id='pzDiv" + ctr + "' class='row-fluid text-center' style='padding-top:10px'><div class='span6 offset3' style='border: 1px solid #EEE; padding:5px'><div class='text-center' style='position:relative'><div id='pz" + ctr + "' style='overflow:hidden'>" + pzContents[index] + "</div></div><input id='removePZButton" + ctr + "' name='removePZButton" + ctr + "' type='button' value='Remove'></div></div>");
		
		    $("#pzInstanceHolder").append(pzContent);
			$("#pz" + ctr).pinchzoomer();
			$("#removePZButton" + ctr).click({id:ctr}, removePZ);
		
		ctr++;
	}
	
	function removePZ(e)
	{
		var id = e.data.id;
		
		PinchZoomer.remove("pz" + id);
		$("#pzDiv" + id).remove();
		
	}
	
});