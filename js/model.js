var view = {};
var model = {};
var controller = {};

model.initial = function(){
	model.unitSize = 10;
	model.gridSwitch = true;
	model.gridColor = [0, 0, 0]; 
	model.backgroundColor = [0, 0, 0];
	model.paletteSelect = 0;
	model.undoArray = [];
	model.redoArray = [];
	model.selectArea = null;
	return this;
}

model.colorDifferenceDegree = function(indexA, indexB){
	var colorA = model.spr.palette[indexA];
	var colorB = model.spr.palette[indexB];
	var ans = 0;
	for(var i = 0; i < 3; i++){
		ans += Math.pow(colorA[i] - colorB[i], 2);
	}
	return Math.sqrt(ans);
}
model.undoArrayPushData = function(){
	model.undoArray.push(model.modifyDataTemp.slice());
	model.modifyDataTemp = [];
	model.redoArray = [];
	if(model.undoArray.length > 10){
		model.undoArray.shift();
	}
}
model.offsetPosition = function(x, y){
	var offset = view.$canvas.offset();
	return [x - offset.left, y - offset.top];
}
model.positionTransform = function(x, y){
	var frame = model.spr.frame[model.currentFrame];
	var X = ~~(x / model.unitSize);
	if(X >= frame.width || X < 0){
		return false;
	}
	var Y = ~~(y / model.unitSize);
	if(Y >= frame.height || Y < 0){
		return false;
	}	
	return Y * frame.width + X;
}
