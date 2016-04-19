function removeDupes(arr){
    var nonDupes = [];
    arr.forEach(function(value) {
        if (nonDupes.indexOf(value) == -1) {
            nonDupes.push(value);
        }
    });
    return nonDupes;
}

var consoleLog = function(data){
	console.log(data);
}

var inRange = function(data, min, max){
	if(data >= min && data <= max){
		return true;
	}
	return false;
}

var limitMin = function(data, min){
	if(data < min){
		return min;
	}
	return data;
}

var limitMax = function(data, max){
	if(data > max){
		return max;
	}
	return data;
}

var limitRange = function(data, min, max){
	return limitMax(limitMin(data, min), max);
}

var HEX = function(data,digits){
	var ans = '';
	if(digits !== undefined){
		for(var i = 0; i < digits - data.toString(16).length; i++){
			ans += '0';
		}
	}
	return ans + data.toString(16).toUpperCase();	
}


var toRGB = function(R,G,B){
	if(R instanceof Array){
		return 'rgb(' + R[0] + ', ' + R[1] + ', ' + R[2] + ')';
	}
	return 'rgb(' + R + ', ' + G + ', ' + B + ')';

}

