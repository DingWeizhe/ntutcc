controller.Select = function(){
	controller.canvasMouseDownEvent = function(e){
		model.selectArea = null;
		controller.startSelect = null;
		controller.endSelect = null;
		controller.isSelecting = true;
		controller.startSelect = model.offsetPosition(e.pageX, e.pageY);
		controller.endSelect = controller.startSelect;
		return this;
	}
	controller.canvasMouseMoveEvent = function(e){
		controller.endSelect = model.offsetPosition(e.pageX, e.pageY);
		return this;
	}
	controller.canvasMouseUpEvent = function(e){
		controller.endSelect = model.offsetPosition(e.pageX, e.pageY);
		controller.isSelecting = false;
		controller.selectRange(controller.startSelect, controller.endSelect);
		return this;
	}
}
controller.selectRange = function(start, end){
	var minX = Math.min(start[0], end[0]);
	var maxX = Math.max(start[0], end[0]);
	var minY = Math.min(start[1], end[1]);
	var maxY = Math.max(start[1], end[1]);
	var temp = [];
	var interval = model.unitSize / 2;
	for(var i = minX; i < maxX; i += interval){
		for(var j = minY; j < maxY; j += interval){
			temp.push(model.positionTransform(i, j));
		}
	}
	model.selectArea = removeDupes(temp);
}

controller.Fill = function(){
	controller.canvasMouseDownEvent = function(e){
		var offset = model.offsetPosition(e.pageX, e.pageY);
		var index = model.positionTransform(offset[0], offset[1]);
		if(index === false){
			return false;
		}
		var frame = model.spr.frame[model.currentFrame];
		var content = frame.content;
		var oriColor = new Number(content[index]);
		if(oriColor == model.paletteSelect){
			return false;
		}
		var stack = [];
		var dirs = [1, -1, frame.width, -frame.width];
		var currentPosition;
		model.modifyDataTemp.push([index,content[index]]);
		content[index] = model.paletteSelect;	
		stack.push(index);
		while(1){
			for(i = 0; i < dirs.length; i++){
				var temp = currentPosition + dirs[i];
				if (temp < content.length && temp >= 0){
					if (content[temp] == oriColor){
						model.modifyDataTemp.push([temp,oriColor]);
						content[temp] = model.paletteSelect;
						stack.push(temp);
					}
				}
			}
			if (stack.length == 0){
				break;
			} else {
				currentPosition = stack.pop();
			}		
		}
		model.modifyDataTemp.unshift('Color');
		model.undoArrayPushData();
		return this;
	}
}

controller.Pencil = function(){
	controller.canvasMouseDownEvent = function(e){
		var offset = model.offsetPosition(e.pageX, e.pageY);
		var index = model.positionTransform(offset[0], offset[1]);
		if(index === false){
			return false;
		}
		var content = model.spr.frame[model.currentFrame].content;
		var palette = model.spr.palette;
		if(content[index] != model.paletteSelect){
			model.modifyDataTemp.push([index,content[index]]);
			content[index] = model.paletteSelect;
		}
		return this;
	}
	controller.canvasMouseMoveEvent = function(e){
		controller.canvasMouseDownEvent(e);
		return this;
	}
	controller.canvasMouseUpEvent = function(e){
		model.modifyDataTemp.unshift('Color');
		model.undoArrayPushData();
		return this;
	}
}

controller.Color = {};
controller.Color.canvasUndoEvent = function(){
	if(model.undoArray.length == 0){
		return false;
	}
	var modifyDataTemp = model.undoArray.pop();
	modifyDataTemp = controller.modifyData(modifyDataTemp);
	model.redoArray.push(modifyDataTemp);
	return this;
}
controller.Color.canvasRedoEvent = function(){
	if(model.redoArray.length == 0){
		return false;
	}
	var modifyDataTemp = model.redoArray.pop();
	modifyDataTemp = controller.modifyData(modifyDataTemp);
	model.undoArray.push(modifyDataTemp);
	return this;
}



controller.changePaletteSelect = function(data){
	if(inRange(data, 0, model.spr.palette.length - 1)){
		model.paletteSelect = data;
		view.drawPaletteSelect();
		return this;
	}
	return false;
}
controller.modifyData = function(data){
	var modifyDataTemp = new Array(data[0]);
	var frame = model.spr.frame[model.currentFrame];
	for(var i = 1; i < data.length; i ++){
		if(inRange(data[i][0],0,frame.content.length - 1)){
			modifyDataTemp.push([data[i][0],frame.content[data[i][0]]]);
			frame.content[data[i][0]] = data[i][1];
		}
	}
	return modifyDataTemp;
}

controller.TakeColor = function(){
	controller.canvasMouseDownEvent = function(e){
		var offset = model.offsetPosition(e.pageX, e.pageY);
		var index = model.positionTransform(offset[0], offset[1]);
		if(index === false){
			return false;
		}
		var content = model.spr.frame[model.currentFrame].content;
		controller.changePaletteSelect(content[index]);
		return this;
	}
}
controller.changeFramePageSelect = function(index){
	model.currentFrame = index;
	view.changeFramePageSelect(index);
	return this;
}
controller.changeUnitSize = function(data){
	model.unitSize = limitRange(data,1,32);
	view.changeUnitSize();
	return this;
}
controller.resetCanvasEvent = function(){
	controller.canvasMouseDownEvent = null;
	controller.canvasMouseUpEvent = null;
	controller.canvasMouseMoveEvent = null;
	return this;
}
