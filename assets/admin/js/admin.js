console.log('from wp admin widget page');

function slider(element){
	if(jQuery(element).hasClass("active")){
		jQuery(element).removeClass("active");
		jQuery(element).next(".entry-desc").slideUp();
	}else{
		jQuery("#entries .entry-title").removeClass("active");
		jQuery(element).addClass("active");
		jQuery("#entries .entry-desc").slideUp();
		jQuery(element).next(".entry-desc").slideDown();
	}
}