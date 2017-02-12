$(function () {
        $("#room").change(
            function () {
                if (this.selectedIndex === 0) {
                    $("#together1-group").hide();
                    $("#together2-group").hide();
                    return;
                }

                if (this.selectedIndex === 1) {
                    $("#together2-group").hide();
                    return;
                }

                $("#together1-group").show();
                $("#together2-group").show();
            });
    }
);
