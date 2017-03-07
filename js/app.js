$(function () {
        $("#room").change(
            function () {
                $("#together1-group").show();
                $("#together2-group").show();

                if (this.selectedIndex === 2) {
                    $("#together1-group").hide();
                    $("#together2-group").hide();
                    return;
                }

                if (this.selectedIndex === 1) {
                    $("#together2-group").hide();
                    return;
                }

            });
    }
);
