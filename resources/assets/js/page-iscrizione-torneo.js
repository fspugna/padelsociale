$(document).ready(function(){
    $('select[name="select-zone"]').change(function(){
        
        var id_zone = $(this).val();        
        var id_tournament = $('input[name="id_tournament"]').val();
        
        console.log("redirect", "/tournament/" + id_tournament + "/zone/" + id_zone + "/show");
        document.location.href = "/tournament/" + id_tournament + "/zone/" + id_zone + "/show";

    });
    
});