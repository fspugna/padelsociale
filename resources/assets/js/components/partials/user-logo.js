$(document).ready(function(){    
    $(document).click(function(e) {

        if ( $(e.target).closest('#user-logo').length === 0 ) {
            // cancel highlighting 
            $("#user-menu-toggle").hide();
        }else{
            $("#user-menu-toggle").toggle();
        }
    });    
});