$(document).ready(function() {
	$('.readImg').on("change", readText);
})

function readText(){
	var imgname = "imgShow",
		filename = "readImg",
		that = this,
		watch = $('.' + imgname);

	var file_size_check = checkSize.apply(this);
	console.log(file_size_check);
	if (file_size_check) {
		if(that.files && that.files[0]){
			var index = $('.' + filename).index(that);
			if (that.files[0].type.match(/image.*/i)) {
				var reader = new FileReader();
				reader.readAsDataURL(that.files[0]);
				reader.onload = function (e) {
					watch.eq(index).attr("src", this.result);
				};
			} else {
				watch.eq(index).attr("src", "/images/noimg.jpg");
				alert("Please updata image!!");
			}
		}//end if html5 filelist support		
	} else {
		$(this).val(null);
	}
}

var fileSize = 0; //檔案大小
var SizeLimit = 2 * 1024 * 1024;  //上傳上限，單位:byte

function checkSize() {
	//FOR IE FIX
	console.log(this);
	var isIE = /msie/.test(navigator.userAgent.toLowerCase());
	// console.log($.support, isIE);
	if (isIE) {
		fileSize = this.fileSize;
	} else {
		fileSize = this.files.item(0).size;
	}
	console.log(fileSize);
	if (fileSize > SizeLimit) {
		Message((fileSize / 1024 / 1024).toPrecision(4));
		return false;
	} else {
		return true;
	}
}

function Message(file) {
	var limit = 2,
		msg = "糟糕，圖片超過上傳限制" + limit + "MB囉";

	message = $("<div id='message-box'></div>");
	message.html(msg);
	upload_light_box.find("#message-box").hide();
	upload_light_box.find('.wrap').append(message);
	upload_light_box.show();
	upload_light_box
		.delay(1000)
		.fadeOut(500, function() {
			var tmp = upload_light_box.find(message);
			tmp.remove();
			upload_light_box.find("#message-box").show();
		});
}