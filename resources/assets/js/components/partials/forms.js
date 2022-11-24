function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#input__image-container__preview").attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}