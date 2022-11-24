jQuery(document).ready(function() {
    $("[data-countdown]").each(function() {
        var $this = $(this), finalDate = $(this).data("countdown");
        $this.countdown(finalDate, function(event) {
            $(this).html(event.strftime("" + '<div class="days"><label>giorni</label><span>%D</span></div>' + '<div class="hours"><label>ore</label><span>%H</span></div>' + '<div class="minutes"><label>minuti</label><span>%M</span></div>' + '<div class="second"><label>secondi</label><span>%S</span></div>'));
        });
    });
});