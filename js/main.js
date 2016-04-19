var colorpicker;
$(function(){
	colorpicker = new Colorpicker();
	model.initial();
	view.initial();
	//JSON 拿取SPR Data
	$.getJSON("/ajax/spr.php", function(data){
		alert('QQ');
		model.spr = data;
		$(".frames").html("");
		var html = "";
		for(var i = 0; i < model.spr.frameCount; i++){
			html += "<canvas class='frame' page='" + i + "'>第 " + (i+1) + " 張圖</canvas>";
		}
		$(".frames").html(html);
		controller.changeFramePageSelect(0);
		view.changeFramePageImage();
		view.redrawPattle();
	});


	
	$(".color").on('mousedown', function(e){
		e.preventDefault();
		var $this = $(this);
		model.paletteSelect = $this.index();
		view.drawPaletteSelect();
		return this;
	});

	$("#gridColor").on('contextmenu', function(e){
		if(colorpicker.isShow == true){
			return;
		}
		var $this = $(this);
		var color = model.gridColor;
		colorpicker.setOriRGB(color[0], color[1], color[2]);
		if (e.pageX + 310 > $("body").width()){
			colorpicker.show(e.pageX - 310, e.pageY + 3);
		} else {
			colorpicker.show(e.pageX + 3, e.pageY + 3);
		}
		colorpicker.onsubmit = function(r,g,b,h,s,v,oriR,oriG,oriB){
			color[0] = r;
			color[1] = g;
			color[2] = b;
			view.redrawPattle();
			return this;
		};
		colorpicker.onColorChange = function(r,g,b,h,s,v,oriR,oriG,oriB){
			color[0] = r;
			color[1] = g;
			color[2] = b;
			view.redrawPattle();
			return this;
		}
		colorpicker.oncancel = function(r,g,b,h,s,v,oriR,oriG,oriB){
			color[0] = oriR;
			color[1] = oriG;
			color[2] = oriB;
			view.redrawPattle();
			return this;
		}
		return false;

	});

	$(".color").on('contextmenu', function(e){	
		if(colorpicker.isShow == true){
			return;
		}
		var $this = $(this);
		var rgb = model.spr.palette[$this.index()]
		colorpicker.setOriRGB(rgb[0], rgb[1], rgb[2]);
		if (e.pageX + 310 > $("body").width()){
			colorpicker.show(e.pageX - 310, e.pageY + 3);
		} else {
			colorpicker.show(e.pageX + 3, e.pageY + 3);
		}
		colorpicker.onColorChange = function(r,g,b,h,s,v,oriR,oriG,oriB){
			model.spr.palette[$this.index()][0] = r;
			model.spr.palette[$this.index()][1] = g;
			model.spr.palette[$this.index()][2] = b;
			view.redrawPattle();
			return this;
		}
		colorpicker.onsubmit = function(r,g,b,h,s,v,oriR,oriG,oriB){
			model.spr.palette[$this.index()][0] = r;
			model.spr.palette[$this.index()][1] = g;
			model.spr.palette[$this.index()][2] = b;
			view.redrawPattle();
			return this;
		};
		colorpicker.oncancel = function(r,g,b,h,s,v,oriR,oriG,oriB){
			model.spr.palette[$this.index()][0] = oriR;
			model.spr.palette[$this.index()][1] = oriG;
			model.spr.palette[$this.index()][2] = oriB;
			view.redrawPattle();
			return this;
		}
		return false;
	});

	view.$canvas.on('mousedown',function(e){
		controller.canvasIsPressed = true;
		if(colorpicker.isShow === true){
			var offset = model.offsetPosition(e.pageX, e.pageY);
			var index = model.positionTransform(offset[0], offset[1]);
			if(index == false){
				return;
			}
			var colorIndex = model.spr.frame[model.currentFrame].content[index];
			var color = model.spr.palette[colorIndex];
			if(color !== undefined){
				colorpicker.setR(color[0]);
				colorpicker.setG(color[1]);
				colorpicker.setB(color[2]);
			}
		}else{
			if(typeof controller.canvasMouseDownEvent == 'function'){
				controller.canvasMouseDownEvent(e);
			}
		}
	});
	$(".menubar .menu").on('mousedown', function(e){
		e.preventDefault();
		return false;
	});

	$(".frames").on('click', "[page]", function(e){
		e.preventDefault();
		var $this = $(this);
		var page = $this.attr("page");
		controller.changeFramePageSelect(page);
	})

	$(".icon").on('click', function(e){
		var $this = $(this);
		controller.resetCanvasEvent();
		if($this.hasClass('active')){	
			$('.icon.active').removeClass("active");
			controller.command = null;
		}else{
			$('.icon.active').removeClass("active");
			$this.addClass("active");
			controller.command = $this.attr('data-toolName');
			if(typeof controller[controller.command] == 'function'){
				controller[controller.command]();
				model.modifyDataTemp = new Array();
			}
		}
		e.preventDefault();
		return false;
	});

	$(window).on('mousemove',function(e){
		if(controller.thumbIsPressed === true){
			var $scroll = $('.scrollbar');
			var $thumb = $('.thumb');
			var maxTop = $scroll.height() - $thumb.height();
			var interval = maxTop / 32;
			var moveTop = e.pageY - $scroll.offset().top;
			controller.changeUnitSize(~~(moveTop / interval));
		}
		if(controller.canvasIsPressed === true){
			if(typeof controller.canvasMouseMoveEvent == "function"){
				controller.canvasMouseMoveEvent(e);
			}
		}
	});	
	$(".scrollbar").on('mousedown',function(e){
		controller.thumbIsPressed = true;			
	});
	$(window).on('mouseup',function(e){
		//canvas
		if(controller.canvasIsPressed === true){
			if(typeof controller.canvasMouseUpEvent == "function"){
				controller.canvasMouseUpEvent(e);
			}
			controller.canvasIsPressed = false;
		}
		//scrollbar thumb
		controller.thumbIsPressed = false;
	});

	$(window).on('keydown',function(e){
		consoleLog(e.keyCode);
		switch(e.keyCode){
			case 87: //w
				controller.changePaletteSelect(model.paletteSelect - 16);
				break;
			case 83: //s
				controller.changePaletteSelect(model.paletteSelect + 16);
				break;
			case 65: //a
				controller.changePaletteSelect(model.paletteSelect - 1);
				break;
			case 68: //d
				controller.changePaletteSelect(model.paletteSelect + 1);
				break;
			case 189: //-
				controller.changeUnitSize(model.unitSize - 1); 
				break;
			case 187: //=
				controller.changeUnitSize(model.unitSize + 1); 
				break;
			case 71: //g
				model.gridSwitch = !model.gridSwitch;
				break;
			case 88: //x
				var redoArrayLength = model.redoArray.length;
				if(redoArrayLength > 0){
					var command = model.redoArray[redoArrayLength - 1][0];
					controller[command].canvasRedoEvent();
				}
				break;
			case 90: //z
				var undoArrayLength = model.undoArray.length;
				if(undoArrayLength > 0){
					var command = model.undoArray[undoArrayLength - 1][0];
					controller[command].canvasUndoEvent();
				}
				break;
		}
		if(e.keyCode >= 49 && e.keyCode <= 57){
			var index = e.keyCode - 49;
			if(model.spr.frameCount <= index){
				return;
			}	
			controller.changeFramePageSelect(index);
		}
	});
	setInterval(view.redraw, 30);
	setInterval(view.changeFramePageImage, 300);
});
