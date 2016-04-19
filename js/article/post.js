
	$(function(){
        tinymce.init({
        	selector: 'textarea',
			plugins: ["YouTube","ImageUpload","textcolor","link"],
			toolbar:" insertfile undo redo | fontsizeselect forecolor backcolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | YouTube ImageUpload link",
			statusbar: false,
			menubar: false,
			relative_urls: false,
			language:"zh_TW",
        });

        $("input[name='date']")
        	.datepicker({
        		format: "yyyy-mm-dd",
        	})
        	.data("datepicker")
        	.set('value', new Date());

		$("input[name='time']")
			.timepicker({
				minuteStep: 1
			});
	});