$(document).ready(function(){
    
    /** Zone */
    var div_width = $(".tabs-scroller-zones").width();
    var selected_zone = $("#selected_zone").val();   
    
    var selected_item = document.getElementById("menu_zone_"+selected_zone);

    if( selected_item ){
        var style = selected_item.currentStyle || window.getComputedStyle(selected_item),
            width = selected_item.offsetWidth, // or use style.width
            margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight),
            padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight),
            border = parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
        
        var selected_item_width = width + padding + margin;        

        var center_position = $("#menu_zone_"+selected_zone).position().left - (div_width/2) + (selected_item_width/2);    
        
        $(".tabs-scroller-zones").animate({scrollLeft: center_position}, 0);
    }

    /** CategoryType */
    var div_width = $(".tabs-scroller-categoryType").width();
    var selected_category_type = $("#selected_category_type").val();       
    var selected_item = document.getElementById("menu_category_type_"+selected_category_type);
    
    if( selected_item ){
        var style = selected_item.currentStyle || window.getComputedStyle(selected_item),
            width = selected_item.offsetWidth, // or use style.width
            margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight),
            padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight),
            border = parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
        
        var selected_item_width = width + padding + margin;        

        var center_position = $("#menu_category_type_"+selected_category_type).position().left - (div_width/2) + (selected_item_width/2);    
        
        $(".tabs-scroller-categoryType").animate({scrollLeft: center_position}, 0);
    }

    /** Category */
    var div_width = $(".tabs-scroller-category").width();
    var selected_category = $("#selected_category").val();       
    var selected_item = document.getElementById("menu_category_"+selected_category);
    if( selected_item ){    
        var style = selected_item.currentStyle || window.getComputedStyle(selected_item),
            width = selected_item.offsetWidth, // or use style.width
            margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight),
            padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight),
            border = parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
        
        var selected_item_width = width + padding + margin;        

        var center_position = $("#menu_category_"+selected_category).position().left - (div_width/2) + (selected_item_width/2);    
        
        $(".tabs-scroller-category").animate({scrollLeft: center_position}, 0);
    }

    /** Group / Bracket */
    var div_width = $(".tabs-scroller-groups").width();
    var selected_group = parseInt($("#selected_group").val());   
    var selected_bracket = parseInt($("#selected_bracket").val());   
    
    if( !isNaN(selected_group) ){
        var selected_item = document.getElementById("menu_group_"+selected_group);
        if( selected_item ){   
            var style = selected_item.currentStyle || window.getComputedStyle(selected_item),
                width = selected_item.offsetWidth, // or use style.width
                margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight),
                padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight),
                border = parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
            
            var selected_item_width = width + padding + margin;        
        
            var center_position = $("#menu_group_"+selected_group).position().left - (div_width/2) + (selected_item_width/2);        
        }
    }else if(!isNaN(selected_bracket) ){
        var selected_item = document.getElementById("menu_bracket_"+selected_bracket);
        
        if( selected_item ){
            var style = selected_item.currentStyle || window.getComputedStyle(selected_item),
                width = selected_item.offsetWidth, // or use style.width
                margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight),
                padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight),
                border = parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
            
            var selected_item_width = width + padding + margin;        
        
            var center_position = $("#menu_bracket_"+selected_bracket).position().left - (div_width/2) + (selected_item_width/2);
        }
    }    

    if( center_position ){
        $(".tabs-scroller-groups").animate({scrollLeft: center_position}, 0);
    }



    /** Round */
    var div_width = $(".tabs-scroller-calendar").width();    
    var selected_round = $("#selected_round").val();           
    var selected_item = document.getElementById("menu_round_"+selected_round);    

    if( selected_item ){
        var style = selected_item.currentStyle || window.getComputedStyle(selected_item),
            width = selected_item.offsetWidth, // or use style.width
            margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight),
            padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight),
            border = parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
        
        var selected_item_width = width + padding + margin;        

        var center_position = $("#menu_round_"+selected_round).position().left - (div_width/2) + (selected_item_width/2);    
        
        $(".tabs-scroller-calendar").animate({scrollLeft: center_position}, 0);
    }

    
});