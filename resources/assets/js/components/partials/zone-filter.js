$("#zone_filter").on('keyup', function(){
    let value = $(this).val();
    filterZones(value);
});

$("#zone_filter").on('change', function(){
    let value = $(this).val();
    filterZones(value);
});

function filterZones(value){
    if( value !== '' ){
        $(".menu_zone").each(function(k, val){
            let club = $(this).text().toLowerCase();
            if( club.indexOf(value.toLowerCase()) >= 0 ){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    }else{
        $(".menu_zone").each(function(k, val){
            $(this).show();
        });
    }
}
