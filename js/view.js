CanvasRenderingContext2D.prototype.strokeDashedLine = function (fromX, fromY, toX, toY, pattern) {  
    // default interval distance -> 5px 
    if (typeof pattern === "undefined") {  
        pattern = 5;  
    }  
    // calculate the delta x and delta y  
    var dx = (toX - fromX);  
    var dy = (toY - fromY);  
    var distance = Math.floor(Math.sqrt(dx * dx + dy * dy));  
    var dashlineInteveral = (pattern <= 0) ? distance : (distance / pattern);  
    var deltay = (dy / distance) * pattern;  
    var deltax = (dx / distance) * pattern;  
    // draw dash line  
    this.beginPath();  
    for(var i = 0; i < dashlineInteveral; i++) {  
        if(i % 2) {  
            this.lineTo(fromX + i * deltax, fromY + i * deltay);  
        } else {                      
            this.moveTo(fromX + i * deltax, fromY + i * deltay);                    
        }                 
    }  
    this.closePath();  
    this.stroke();  
    return this;
};  


view.initial = function(){
	view.$canvas = $("canvas");
	view.ctx = view.$canvas[0].getContext("2d");
	view.changeUnitSize();
	view.drawPaletteSelect();
	view.toolName = ['Pencil','Select','Lasso','Move','Fill','TakeColor','Rotate','Flip Hpeizontal','Flip Vertical'];
	$('.tool .icon').each(function(i){
		$(this).attr('data-toolName',view.toolName[i]);
	});
	view.$canvas.attr("width", view.$canvas.parent().width());
	view.$canvas.attr("height", view.$canvas.parent().height());
	view.dashLinePattern = 5;
	return this;
}
view.drawCanvasBackground = function(){
	view.ctx.fillStyle = toRGB(model.backgroundColor);
	view.ctx.fillRect(0, 0, view.$canvas.width(), view.$canvas.height());
	return this;
}
view.drawCanvas = function(ctx, currentFrame, unitSize){
	if(ctx === undefined){
		return false;
	}
	var frame = model.spr.frame[currentFrame];
	for (var i = 0; i < frame.content.length; i++){
		var colorIndex = frame.content[i];
		var color = model.spr.palette[colorIndex];
		ctx.fillStyle = toRGB(color);
		ctx.fillRect(unitSize * (i % frame.width), unitSize * (~~(i / frame.width)), unitSize, unitSize);
	}
	return this;
}
view.drawPaletteSelect = function(){
	var select = model.paletteSelect;
	$(".palette .color").each(function(i){
		if(select == i){
			$(this).css("border", '1px outset #FF8528');
		}else{
			$(this).css("border", '1px solid #000000');
		}
	});
}


view.redraw = function(){
	if (typeof model.spr == "undefined"){
		return false;
	}
	view.drawCanvasBackground();
	view.drawCanvas(view.ctx, model.currentFrame, model.unitSize);
	if(model.gridSwitch){
		view.drawGrid();
	}
	if(controller.isSelecting){
		view.drawSelectTool(controller.startSelect, controller.endSelect, 5);
	}
	if(model.selectArea != null){
		view.drawSelectArea();
	}
	return this;
}

view.redrawPattle = function(){
	$('#gridColor').css('background', toRGB(model.gridColor));
	$(".palette .color").each(function(i){
		var color = model.spr.palette[i];
		$(this).css("background", "rgb("+color[0]+", "+color[1]+", "+color[2]+")");
	});
	return this;
}


//畫網格
view.drawGrid = function(){
	var frame = model.spr.frame[model.currentFrame];
	var imageWidth = frame.width * model.unitSize;
	var imageHeight = frame.height * model.unitSize;
	view.ctx.fillStyle = toRGB(model.gridColor);
	for(var i = 1; i < frame.width; i++){
		//直線
		view.ctx.fillRect(model.unitSize * i, 0, 0.2,imageHeight);
	}
	for(var i = 1; i < frame.height; i++){
		//橫線
		view.ctx.fillRect(0, model.unitSize * i, imageWidth, 0.2);
	}
	return this;
}

view.drawSelectTool = function(from, to, p){
	if(typeof from == "undefined" || typeof to == "undefined"){
		return false;
	} 
	view.ctx.strokeStyle = "#000000";
	view.ctx.strokeDashedLine(from[0], from[1], to[0], from[1], view.dashLinePattern);
	view.ctx.strokeDashedLine(from[0], to[1], to[0], to[1], view.dashLinePattern);
	view.ctx.strokeDashedLine(from[0], from[1], from[0], to[1], view.dashLinePattern);
	view.ctx.strokeDashedLine(to[0], from[1], to[0], to[1], view.dashLinePattern);
	return this;
}

view.changeUnitSize = function(){
	var maxTop = $('.scrollbar').height() - $('.thumb').height();
	var interval = maxTop / 32;
	$('.thumb').css("top", limitRange((model.unitSize - 1) * interval, 0, maxTop) + "px");	
	$('.hint').text(model.unitSize);
	return this;
}

view.changeFramePageImage = function(){
	$('.frame').each(function(i){
		var $this = $(this);
		if(typeof $this[0].getContext != 'function'){
			return false;
		}
		var ctx = $this[0].getContext("2d");
		$this.attr("width", 80).attr("height", 80);
		view.drawCanvas(ctx,i,1);
	});
	return this;
}

/**
 * 改變顯示的頁數
 * @param {int} index 頁數
 * @return {object} this
 * @example
 * changeFramePageSelect(3)
 */
view.changeFramePageSelect = function(index){
	$('.frame').each(function(i){
		var $this = $(this);
		if(index == i){
			$(".frames .active").removeClass("active");
			$this.addClass("active");
		}
	});
	return this;
}
/**
 * [drawSelectArea description]
 * @return {[type]}
 */
view.drawSelectArea = function(){
	minPosition = Math.min.apply(null, model.selectArea);

}

