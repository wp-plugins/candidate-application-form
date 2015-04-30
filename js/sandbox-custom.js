$(document).ready(function() {
	$("div#audience_custom-0").hide();						   


$(".defaultText").focus(function(srcc)
    {
        if ($(this).val() == $(this)[0].title)
        {
            $(this).removeClass("defaultTextActive");
            $(this).val("");
        }
    });
    
    $(".defaultText").blur(function()
    {
        if ($(this).val() == "")
        {
            $(this).addClass("defaultTextActive");
            $(this).val($(this)[0].title);
        }
    });
    
    $(".defaultText").blur();
	
});

function event_add_audience_custom() {

	var new_audience_custom = clone_container_custom("div#audience_custom", "div#audience_custom", "div#audience_custom div.audience_custom", "audience_custom");

	$("div#audience_custom div.audience_custom:last").after(new_audience_custom);
	$("div#audience_custom div.audience_custom").each(function(i) {
		$(this).removeClass('row-even');
		if(i % 2 != 0) {
			$(this).addClass('row-even');
		}
	});

	$("div#" + new_audience_custom.attr("id") + " a.del-circle-button_custom").click(function(e) {
		e.preventDefault();
		delete_item(new_audience_custom, null);
	});
	
	new_audience_custom.fadeIn();
}
function delete_item(obj, record_info) {
	obj.remove();
}
function clone_container_custom(container, selector, collection, newname) {
	var maxId = 0;
	var getClass = collection.split(' ')[1].split('.')[1];
	$("."+getClass).each(function() {
	   var id = $(this).attr('id').split('-')[1];
	   if( id > maxId)
		  maxId = id;
	});
	maxId = parseInt(maxId)+1;	

	var new_container_custom = $(container + " " + selector + "-0").clone(true).hide();
	var new_id = $(collection).size();
	$(new_container_custom).attr("id", newname + "-" + maxId);
	$(new_container_custom).find("input[id^='productsku-']").attr("id","productsku-"+maxId);
	$(new_container_custom).find("input[id^='productqty-']").attr("id","productqty-"+maxId);	
	$(new_container_custom).find("input[id^='productprice-']").attr("id","productprice-"+maxId);
	$(new_container_custom).find("input[id^='productattributes-']").attr("id","productattributes-"+maxId);	
	return new_container_custom;
}

