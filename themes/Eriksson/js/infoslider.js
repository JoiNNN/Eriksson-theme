/** 
 * Slider script with jQuery 
 * and jQuery cookie plugin
 * Author: JoiNNN
 */
jQuery(document).ready(function() {
		/* Info slider */
		var showInfo = $.cookie('showInfo');
		var slideBtn = $("#infoSlideBtn").addClass("flright");
		if (showInfo != 'collapsed') {
				$(".info_wrap").css({ height: "76px"});
				$(slideBtn).removeClass("show").addClass("hide").html(locale_hide + '<span class="arrowh"> <\/span>');
		} else {
				$(slideBtn).removeClass("hide").addClass("show").html(locale_show + '<span class="arrows"> <\/span>');
				$(".info_wrap").css({ height: "0px"});
		}

		slideBtn.click(function() {
			var showInfo = $.cookie('showInfo');
			if (showInfo != 'collapsed') {
				$(".info_wrap").stop().animate({ 
					height: "0px"
					}, 300 );
				$.cookie('showInfo', 'collapsed', { path:'/' }); //Add cookie
				$(this).removeClass("hide").addClass("show").html(locale_show + '<span class="arrows"> <\/span>');
			} else {
				$(".info_wrap").stop().animate({ 
					height: "76px"
					}, 300 );
				$.cookie('showInfo', 'expanded', { path:'/' }); //Add cookie
				$(this).removeClass("show").addClass("hide").html(locale_hide + '<span class="arrowh"> <\/span>');
			}
		});

		/* Member area */
		var showMember = $.cookie('showMember');
		if (showMember != "expanded") {
				$(".member_wrap").css({ height: "0px" });
		} else {
				$(".member_wrap").css({ height: "60px" });
      }

		$("#topMember").click( function(e) {
			e.preventDefault();
			var showMember = $.cookie('showMember');
			if (showMember != 'expanded') {
				$(".member_wrap").stop().animate({ 
					height: "60px"
					}, 200 );
				$.cookie('showMember', 'expanded', { path:'/' }); //Add cookie
			} else {
				$(".member_wrap").stop().animate({ 
					height: "0px"
					}, 200 );
				$.cookie('showMember', 'collapsed', { path:'/' }); //Add cookie
			}
		});
});