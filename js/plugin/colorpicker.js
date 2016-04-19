"use strict";
function Colorpicker($target, option){
	this.initial = function(){
		this.$dom = $([
			'<div class="colorpicker">',
				'<div class="pool">',
					'<div class="selected"></div>',
				'</div>',
				'<div class="hbar"><div class="arrow"></div></div>',
				'<div class="right">',
					'<div class="origin-color"></div>',
					'<div class="target-color"></div>',
					'<div>',
						'<div class="RGB">',
							'<div class="label">R <input type="text" value="255" name="r"></div>',
							'<div class="label">G <input type="text" value="0" name="g"></div>',
							'<div class="label">B <input type="text" value="0" name="b"></div>',
						'</div>',
						'<div class="HSV">',
							'<div class="label">H <input type="text" value="0" name="h"></div>',
							'<div class="label">S <input type="text" value="100" name="s"></div>',
							'<div class="label">V <input type="text" value="100" name="v"></div>',
						'</div>',
						'<div class="hex">',
							'<div class="label"># <input type="text" name="hex" value="FF0000"></div>',
						'</div>',
					'</div>',
					'<div class="cancel">',
						'<span></span>',
					'</div>',
					'<div class="submit">',
						'<span></span>',
					'</div>',
				'</div>',
			'</div>'].join(""));
		this.$dom.appendTo($("body"));
		this.$hbar = this.$dom.find(".hbar");
		this.$hbar_arrow = this.$dom.find(".hbar .arrow");
		this.$pool = this.$dom.find(".pool");
		this.$pool_selected = this.$dom.find(".pool .selected");
		this.$h = this.$dom.find("[name='h']");
		this.$s = this.$dom.find("[name='s']");
		this.$v = this.$dom.find("[name='v']");
		this.$r = this.$dom.find("[name='r']");
		this.$g = this.$dom.find("[name='g']");
		this.$b = this.$dom.find("[name='b']");
		this.$hex = this.$dom.find("[name='hex']");
		this.$targetColor = this.$dom.find(".target-color");
		this.$originColor = this.$dom.find(".origin-color");
		this.$submit = this.$dom.find(".submit");
		this.$cancel = this.$dom.find(".cancel");
		this.$h.on('keyup', {that: this}, this.changeH);
		this.$s.on('keyup', {that: this}, this.changeS);
		this.$v.on('keyup', {that: this}, this.changeV);
		this.$r.on('keyup', {that: this}, this.changeR);
		this.$g.on('keyup', {that: this}, this.changeG);
		this.$b.on('keyup', {that: this}, this.changeB);
		this.$submit.on('click', {that: this}, this._onsubmit);
		this.$cancel.on('click', {that: this}, this._oncancel);
		this.$originColor.on('click', {that: this}, this._setOriRGB);
		this.$pool.on('mousedown', {that: this}, this.startSelectSV);
		this.$hbar.on('mousedown', {that: this}, this.startSelectH);
		this.$dom.fadeOut();

	};

	//controller
	this.startSelectH = function(e){
		var that = e.data.that;
		that.$hbar.off('mousedown', that.startSelectH);
		that.$hbar.on('mousemove', {that: that}, that.selectH);
		that.$hbar.on('mouseup', {that: that}, that.endSelectH);
		that.selectH(e);
	};

	this.selectH = function(e){
		var that = e.data.that;
		if (e.which == 0){
			that.endSelectH(e);
			return;
		}
		var top = that.$hbar.offset().top;
		var height = that.$hbar.height();
		that.setH(360 - ~~((e.pageY - top) / height * 360),0);

	};

	this.endSelectH = function(e){
		var that = e.data.that;
		that.$hbar.on('mousedown',{that: that}, that.startSelectH);
		that.$hbar.off('mousemove', that.selectH);
		that.$hbar.off('mouseup', that.endSelectH);
	};

	this.startSelectSV = function(e){
		var that = e.data.that;
		that.$pool.off('mousedown', that.startSelectSV);
		that.$pool.on('mousemove', {that: that}, that.selectSV);
		that.$pool.on('mouseup', {that: that}, that.endSelectSV);
		that.selectSV(e);
	};

	this.selectSV = function(e){
		var that = e.data.that;
		if (e.which == 0){
			that.endSelectH(e);
			return;
		}
		var top = that.$pool.offset().top;
		var left = that.$pool.offset().left;
		var height = that.$pool.height();
		var width = that.$pool.width();
		that.setV(~~(100 - (e.pageY - top) / height * 100),0);
		that.setS(~~((e.pageX - left) / width * 100),0);

	};

	this.endSelectSV = function(e){
		var that = e.data.that;
		that.$pool.on('mousedown',{that: that}, that.startSelectSV);
		that.$pool.off('mousemove', that.selectSV);
		that.$pool.off('mouseup', that.endSelectSV);
	};

	this.changeH = function(e){
		var that = e.data.that;
		that.setH($(this).val()*1,0);
	};

	this.changeS = function(e){
		var that = e.data.that;
		that.setS($(this).val()*1,0);
	};

	this.changeV = function(e){
		var that = e.data.that;
		that.setV($(this).val()*1,0);
	};

	this.changeR = function(e){
		var that = e.data.that;
		that.setR($(this).val()*1,0);
	};

	this.changeG = function(e){
		var that = e.data.that;
		that.setG($(this).val()*1,0);
	};

	this.changeB = function(e){
		var that = e.data.that;
		that.setB($(this).val()*1,0);
	};

	//model
	this.setH = function(h, recalc){
		if (h <= 0) h = 0;
		if (h > 360) h = 360;
		this.h = h;
		if (recalc != 1) this.calcRGB();
		this.hChanged();
	}

	this.setS = function(s, recalc){
		if (s <= 0) s = 0;
		if (s > 100) s = 100;
		this.s = s;
		if (recalc != 1) this.calcRGB();
		this.sChanged();

	};

	this.setV = function(v, recalc){
		if (v <= 0) v = 0;
		if (v > 100) v = 100;
		this.v = v;
		if (recalc != 1) this.calcRGB();
		this.vChanged();
	};

	this.setR = function(r, recalc){
		if (r <= 0) r = 0;
		if (r > 255) r = 255;
		this.r = r;
		if (recalc != 1) this.calcHSV();
		this.rChanged();
	};

	this.setG = function(g, recalc){
		if (g <= 0) g = 0;
		if (g > 255) g = 255;
		this.g = g;
		if (recalc != 1) this.calcHSV();
		this.gChanged();
	};

	this.setB = function(b, recalc){
		if (b <= 0) b = 0;
		if (b > 255) b = 255;
		this.b = b;
		if (recalc != 1) this.calcHSV();
		this.bChanged();
	};

	this.calcRGB = function(){
		var rgb = HSV2RGB(this.h, this.s, this.v);
		this.setR(rgb[0], 1);
		this.setG(rgb[1], 1);
		this.setB(rgb[2], 1);
	};

	this.calcHSV = function(){
		var hsv = RGB2HSV(this.r, this.g, this.b);
		this.setH(hsv[0], 1);
		this.setS(hsv[1], 1);
		this.setV(hsv[2], 1);
	}

	//view
	this.hChanged = function(){
		this.$h.val(this.h);
		var rgb = HSV2RGB(this.h, 100, 100);
		this.$pool.css({background: "rgb(" + rgb[0] + ", " + rgb[1] + ", " + rgb[2] + ")"});
		var height = this.$hbar.height();
		this.$hbar_arrow.css({top: ((360 - this.h) / 360 * height)});
		this.hsvChanged();
	};

	this.sChanged = function(){
		this.$s.val(this.s);
		this.hsvChanged();
	};

	this.vChanged = function(){
		this.$v.val(this.v);
		this.hsvChanged();
	};

	this.rChanged = function(){
		this.$r.val(this.r);
		this.rgbChanged();
	};

	this.gChanged = function(){
		this.$g.val(this.g);
		this.rgbChanged();
	};

	this.bChanged = function(){
		this.$b.val(this.b);
		this.rgbChanged();
	};

	this.hsvChanged = function(){
		this.$pool_selected.css({
			left: this.s / 100 * 150,
			top: 150 - (this.v / 100 * 150)
		});
	};

	this.rgbChanged = function(){
		this.$targetColor.css({background: "rgb(" + this.r + ", " + this.g + ", " + this.b + ")"});
		this.$pool_selected.css({
			background: "rgb(" + this.r + ", " + this.g + ", " + this.b + ")",
		});
		var rhex = this.r.toString(16);
		var ghex = this.g.toString(16);
		var bhex = this.b.toString(16);
		if (rhex.length == 1) rhex = "0" + rhex;
		if (ghex.length == 1) ghex = "0" + ghex;
		if (bhex.length == 1) bhex = "0" + bhex;
		this.$hex.val((rhex + ghex + bhex).toUpperCase());
		if (typeof this.onColorChange == "function"){
			this.onColorChange(this.r, this.g, this.b, this.h, this.s, this.v, this.oriR, this.oriG, this.oriB);
		}
	};

	this._onsubmit = function(e){
		var that = e.data.that;
		if (typeof that.onsubmit == "function"){
			that.onsubmit(that.r, that.g, that.b, that.h, that.s, that.v, that.oriR, that.oriG, that.oriB);
		}
		that.isShow = false;
		that.resetEvent();
		that.$dom.fadeOut();
	}

	this._oncancel = function(e){
		var that = e.data.that;
		if (typeof that.oncancel == "function"){
			that.oncancel(that.r, that.g, that.b, that.h, that.s, that.v, that.oriR, that.oriG, that.oriB);
		}
		that.isShow = false;
		that.resetEvent();
		that.$dom.fadeOut();
	}
	this.resetEvent = function(){
		this.oncancel = null;
		this.onsubmit = null;
		this.onColorChange = null;
	}
	this._setOriRGB = function(e){
		var that = e.data.that;
		that.setR(that.oriR);
		that.setG(that.oriG);
		that.setB(that.oriB);
	}
	this.setOriRGB = function(r, g, b){
		this.oriR = r;
		this.oriG = g;
		this.oriB = b;
		this.setR(this.oriR);
		this.setG(this.oriG);
		this.setB(this.oriB);
		this.$originColor.css({background: "rgb(" + this.r + ", " + this.g + ", " + this.b + ")"});
	};

	this.show = function(x, y){
		if(this.isShow !== false){
			return;
		}
		this.isShow = true;
		this.$dom.fadeIn();
		this.$dom.css({
			left: x,
			top: y
		});
		return;
	};
	this.oriR = 0;
	this.oriG = 0;
	this.oriB = 0;
	this.r = 0;
	this.g = 0;
	this.b = 0;
	this.h = 0;
	this.s = 0;
	this.v = 0;
	this.isShow = false;
	this.$dom = null;
	this.initial();
};

/**
 * Convert HSV to RGB by http://www.rapidtables.com/convert/color/hsv-to-rgb.htm
 * @param h {Number} 0~360
 * @param s {Number} 0~100
 * @param v {Number} 0~100
 * @return {Array} [r,g,b]
 */
var HSV2RGB = function(h, s, v){
	h %= 360;
	s /= 100;
	v /= 100;
	var c = v * s;
	var x = c * ( 1 - Math.abs((h / 60) % 2 - 1));
	var m = v - c;
	var rgb = [];
	switch(Math.floor(h/60)){
		case 0: rgb = [c, x, 0]; break;
		case 1: rgb = [x, c, 0]; break;
		case 2: rgb = [0, c, x]; break;
		case 3: rgb = [0, x, c]; break;
		case 4: rgb = [x, 0, c]; break;
		case 5: rgb = [c, 0, x]; break;
	}
	return rgb.map(function(val){
		return Math.ceil((val + m) * 255);
	});
};

/**
 * Convert RGB to HSV by http://www.rapidtables.com/convert/color/rgb-to-hsv.htm
 * @param r {Number} 0~255
 * @param g {Number} 0~255
 * @param b {Number} 0~255
 * @return {Array} [h,s,v] h {Number} 0 ~ 360, s {Number} 0 ~ 100, v {Number} 0 ~ 100
 */
 var RGB2HSV = function(r, g, b){
 	r = r / 255;
 	g = g / 255;
 	b = b / 255;
 	var max = Math.max(r, g, b);
 	var min = Math.min(r, g, b);
 	var d = max - min;
 	var h, s, v;

 	//hue calculation
 	if (r == max) h = 60 * (~~((g - b) / d) % 6);
 	else if (g == max) h = 60 * ((b - r) / d + 2);
 	else h = 60 * ((r - g) / d + 4);
 	if ( isNaN(h) ) h = 0;
 	if (h < 0) h += 360;

 	//Satruration calculation
 	if (0 == max) s = 0;
 	else s = d / max;

 	//value calculation
 	v = max;
	return [Math.round(h), Math.round(s * 100), Math.round(v * 100)];
};