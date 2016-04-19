var Point = Class.extend(function(){
		this.constructor = function(px, py){
			if(px instanceof Point){
				this.x = px.x;
				this.y = px.y;
			}else if(px instanceof Array){
				this.x = px[0];
				this.y = px[1];
			}else{
				this.x = px;
				this.y = py;
			}	
			return this;
		};

		this.print = function(ctx){
			ctx.fillStyle = "rgba(25, 255, 75, 0.8)";
			ctx.fillRect(this.x - 10, this.y - 10, 10, 10);
			return this;
		}

		this.add = function(point){
			if(point instanceof Point){
				return new Point(this.x + point.x, this.y + point.y);
			}
			return this;
		}

		this.sub = function(point){
			if(point instanceof Point){
				return new Point(this.x - point.x, this.y - point.y);
			}
			return this;
		}

		this.distance = function(point){
			if(point instanceof Point){
				return Math.pow(this.x - point.x, 2) + Math.pow(this.y - point.y, 2)
			}
			return this;
		};

		this.consoleLog = function(){
			console.log('x: ' + this.x +', y: ' + this.y);
			return this;
		}  
		return this;
	});