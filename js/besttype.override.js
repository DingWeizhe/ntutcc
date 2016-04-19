"use strict";
Array.prototype.isEqual = function(target){
	if (this == target) return true;
	if (this == null || target == null) return false;
	if (this.length != target.length) return false;
	for(var i in this){
		if (
			(this[i] !== target[i]) &&
			(typeof this[i].isEqual != "function" ||
			!this[i].isEqual(target[i]))
		){
			return false;
		}
	}
	return true;
}
