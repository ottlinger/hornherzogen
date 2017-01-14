$(function () {
        $("#room").change(
            function () {
                if (this.selectedIndex === 0) {
                    $("#together2-group").hide();
                }
                else {
                    $("#together2-group").show();
                }
            });
    }
);
