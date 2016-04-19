"use strict";
function T(a,b){
	if (
		a != b &&
		(typeof a.isEqual == "function" && !a.isEqual(b))
	){
		console.log("unittest don't pass " + a + " != " + b);
		var stacks = new Error().stack.split("\n");
		console.log(stacks[stacks.length-1]);
	}
}

function NT(a,b){
	if (
		a == b &&
		(typeof a.isEqual == "function" && a.isEqual(b))
	){
		console.log("unittest don't pass " + a + " == " + b);
		var stacks = new Error().stack.split("\n");
		console.log(stacks[stacks.length-1]);
	}
}

try{
	//Array.isEqual
	T([0,1,2,3], [0,1,2,3]);
	NT([0,1,2], [0,1,2,3]);
	T([0,1,2,[1,2,3]], [0,1,2,[1,2,3]]);
	NT([0,1,2,[1,2,3]], [0,1,2,[1,2]]);

	//HSV2RGB
	T(HSV2RGB(0, 0 ,0), [0,0,0]);
	T(HSV2RGB(0, 0 ,100), [255,255,255]);
	T(HSV2RGB(0, 100 ,100), [255,0,0]);
	T(HSV2RGB(120, 100 ,100), [0,255,0]);
	T(HSV2RGB(240, 100 ,100), [0,0,255]);
	T(HSV2RGB(60, 100 ,100), [255,255,0]);
	T(HSV2RGB(180, 100 ,100), [0,255,255]);
	T(HSV2RGB(300, 100 ,100), [255,0,255]);
	T(HSV2RGB(0, 0 ,75), [192,192,192]);
	T(HSV2RGB(0, 0 ,50), [128,128,128]);
	T(HSV2RGB(0, 100 ,50), [128,0,0]);
	T(HSV2RGB(60, 100 ,50), [128,128,0]);
	T(HSV2RGB(120, 100 ,50), [0,128,0]);
	T(HSV2RGB(300, 100 ,50), [128,0,128]);
	T(HSV2RGB(180, 100 ,50), [0,128,128]);
	T(HSV2RGB(240, 100 ,50), [0,0,128]);

	//RGB2HSV
	T(RGB2HSV(0,0,0), [0,0,0]);
	T(RGB2HSV(255,255,255), [0,0,100]);
	T(RGB2HSV(255,0,0), [0,100,100]);
	T(RGB2HSV(0,255,0), [120,100,100]);
	T(RGB2HSV(0,0,255), [240,100,100]);
	T(RGB2HSV(255,255,0), [60,100,100]);
	T(RGB2HSV(0,255,255), [180,100,100]);
	T(RGB2HSV(255,0,255), [300,100,100]);
	T(RGB2HSV(192,192,192), [0,0,75]);
	T(RGB2HSV(128,128,128), [0,0,50]);
	T(RGB2HSV(128,0,0), [0,100,50]);
	T(RGB2HSV(128,128,0), [60,100,50]);
	T(RGB2HSV(0,128,0), [120,100,50]);
	T(RGB2HSV(128,0,128), [300,100,50]);
	T(RGB2HSV(0,128,128), [180,100,50]);
	T(RGB2HSV(0,0,128), [240,100,50]);
} catch (e){
	console.log(e);
}